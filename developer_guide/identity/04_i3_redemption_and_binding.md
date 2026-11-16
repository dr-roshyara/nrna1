# 04. I-3: redeeming a claim code (binding)

**Story:** `PBDIGIT-349` step I-3 · **Status:** implemented; review corrections implemented, **awaiting Principal review** (not accepted, not merged) · **Design:** I-TDD-02 revision 12 (D-200) §4 (INV-ID-6/9, INV-BND-1/2/3, INV-CLM-3), §6.2 (redemption row), §12 C-I3, C-I6, §14 ("redeem while signed in", cases 3 and 5), §15; multi-database boundary design §13.3 step 7 · **Decisions:** D-113, D-197, D-200; Principal rule R-6 (2026-11-15, see below).

## Purpose (business)
A signed-in person enters the claim code their organisation gave them. Identity then records **"this organisation's `PersonRef` R is this Person"** (a binding) and tells the organisation, through Identity's outbox, that R was redeemed.

**A binding grants nothing by itself:** no membership, office, vote, forum or newsletter right. It's what later lets the person enter that organisation (I-4).

## What works now and what doesn't
| | Status |
|---|---|
| Redeeming an ordinary code; the binding; `ClaimRedeemed` and `BindingRefused` in `identity_outbox_events`; throttling; neutral errors | **implemented and tested** on PostgreSQL 15 (isolated test databases only) |
| **Production** | the use case works, but **no claim can be issued in production yet** (I-2 refuses until I-4), so there is nothing to redeem. **No web page or route** calls it yet (the UI is out of I-TDD-02's scope) |
| **Delivery of the events to the organisation's inbox** | **not built.** It needs Organisation's consumer (S4) and a relay under the organisation's context (multi-database design §13.3 step 8). The events wait in `identity_outbox_events` |
| Entering an organisation (Platform's `IdentityBindings`) | **I-4** |
| Bootstrap claims, claim-first registration | I-6, I-7 |

## Model and files (`app/Contexts/Identity/…`)
| Element | Role |
|---|---|
| `Application/RedeemClaim` + `RedeemClaimCommand` (code as typed, client key) | the use case. Order: signed in → the code parses → not throttled → the Account's active Person → the store → count a failure if refused as `NotRedeemable` **or `PersonAlreadyBound`** |
| `Application/RedemptionOutcome`, `RedemptionRefusal` | `bound` / `already-bound` / `refused`. **One neutral message** for every refusal (PI-8) |
| `Application/Ports/ClaimStore::redeem` | **the one redemption path** (PI-44) |
| `Application/Ports/RedemptionThrottle` → `Infrastructure/PostgresRedemptionThrottle` | INV-ID-9: failures per code and per client, in a window. **Never disables a claim** (D-197) |
| `Application/Published/ClaimRedeemed` (O, R, claim id, boundAt), `BindingRefused` (O, R) | published language. **Never a PersonId, an Account or a code** |
| `Infrastructure/Outbox/*Hydrator` | registered in `IdentityServiceProvider::boot` |
| `Infrastructure/Providers/IdentityServiceProvider` | wires the throttle with its limits and the client-key secret (`throttleSecret()`) |

## The redemption, step by step (`PostgresClaims::redeem` → `redeemOnce`, one transaction, READ COMMITTED)
1. Find the claim by its code hash (a plain read: O, R). Nothing is decided on this read.
2. Lock **`ClaimSlot` (O, R)**, then the **claim row** (`FOR UPDATE`; the global lock order, INV-CLM-6), then take **one `clock_timestamp()` sample** `t`.
3. **Already redeemed:**
   - the same Person with the same claim → **`already-bound`** (§14 case 3);
   - otherwise → neutral refusal (case 5, PI-2).
4. **Not active** (expired or revoked) → neutral refusal (PI-13, PI-22).
5. **R already bound** → neutral refusal (INV-ID-6).
6. **The Person already holds another R of O** → refused; **`BindingRefused(O, R)`** written to the outbox and audited (INV-BND-2, PI-3). The claim stays usable.
7. Otherwise:
   - **create the binding** with `bound_at = t` (never back-dated, PI-9);
   - set the claim to `redeemed` with `ended_at = t`;
   - write **`ClaimRedeemed(O, R)`** to `identity_outbox_events`;
   - write the audit entry.

**The outbox row** (`PostgresClaims::outbox`): `aggregate_type = 'IdentityClaim'` and `aggregate_id = the claim id` for **both** events (review R-8; `BindingRefused` used R before). The payload is published language only.

### The racing INV-BND-2 case (review R-1)
Two redemptions by **one Person** of **two different** references of O lock two different slots, so neither sees the other's binding at step 6. Both insert; the database's `UNIQUE (organisation_id, person_id)` (`identity_bindings_organisation_id_person_id_key`) rejects the second, and its **whole transaction rolls back** (nothing half-written: no event, no audit, no binding).

`redeem()` catches **only that constraint's** `UniqueConstraintViolationException` and runs `redeemOnce` **once more** in a fresh transaction. That run sees the committed binding and takes step 6: `PersonAlreadyBound` with `BindingRefused` and audit. Any other database error, or a second failure, is `IdentityUnavailable` (refused, never guessed). The lock order and the single clock sample are unchanged.

**How the conflict is recognised (residual risk).**
- The relevant constraint is recognised from the **exception's message text** (`str_contains($e->getMessage(), self::ONE_REFERENCE_PER_PERSON)`), not from the database driver's structured error details.
- If PostgreSQL or Laravel changes the message format, the retry may stop happening. The losing redemption then **fails closed as unavailable** instead of receiving the more specific `PersonAlreadyBound` (and O is not told through `BindingRefused`).
- This **never grants an incorrect binding**: the unique key still refuses the second binding. It affects only the precision of the refusal and the retry.
- Using the driver's structured error details is possible future hardening work, not authorised in I-3.

```php
try {
    return $this->redeemOnce($code, $person);
} catch (UniqueConstraintViolationException $e) {
    if (!str_contains($e->getMessage(), self::ONE_REFERENCE_PER_PERSON)) {
        throw new IdentityUnavailable(/* … */);
    }
    return $this->redeemOnce($code, $person);   // decided again; a second failure → IdentityUnavailable
}
```

## Throttling (`config/identity.php`, `PostgresRedemptionThrottle`)
- **Limits:** per code **5** failures, per client **10** failures, within **900 seconds**. **These values are proposals ([TU] in the design), not ratified; they need the Principal's confirmation before release.** Environment overrides: `IDENTITY_REDEMPTION_THROTTLE_PER_CODE`, `…_PER_CLIENT`, `…_WINDOW_SECONDS`.
- **What counts as a failure:** unknown, malformed, expired, revoked or already-redeemed codes, **and `PersonAlreadyBound`** (Principal rule R-6: otherwise one person could repeat it and create refusal events and audit rows without limit).
- **What is stored (review R-2):**
  - the **code key** is the claim code's SHA-256 hash, passed explicitly; anything that isn't 64 lower-case hex is a programming error (`InvalidArgumentException`);
  - the **client key** (an IP address, say) is stored **only as HMAC-SHA-256 under a server secret**. A plain hash of an IPv4 address could be reversed by trying all 2³² addresses;
  - the secret is never stored, logged or written to evidence.
- **Retention:** every recorded failure first deletes rows whose window has ended (index `identity_redemption_failures_window`). *Residual:* if no failure is ever recorded again, the last expired rows stay until the next one.
- **Fail closed:** without a secret of at least 16 characters, `isThrottled` / `recordFailure` throw `IdentityUnavailable`, so every redemption is refused as unavailable.
- **Residual risk (R-5):** check and count are separate statements, so a burst of simultaneous attempts can overshoot a limit by its own size. With about 100 bits per code this does not make guessing feasible.

## Migration `2026_11_15_630000_…` (central database)
1. **A correction to I-2's schema.** `identity_bindings.claim_id`, `identity_consumed_authority_requests.claim_id` and `identity_claims.replaces_claim_id` were foreign keys to claim rows. That would **block the §15 cleanup of ended claim rows**, which a binding must survive. They're now plain provenance identifiers. The binding's own keys remain (proved via `pg_constraint`, review F-3).
2. **`identity_redemption_failures`** (scope, key hash, window start, failures).
3. **Identity's own outbox `identity_outbox_events`.** The shared `outbox_events` has `organisation_id` as a foreign key to the **legacy** `organisations` table, and registry organisations live in `platform_served_organisations` (D-160). The columns follow the shared outbox, **with differences:** no legacy key, no `correlation_id` / `causation_id`, `aggregate_id` is text, `payload` is jsonb, times are `timestamptz(6)`.

**Forward-only (review R-3).** `down()` throws a `RuntimeException` and changes nothing. A rollback would drop undelivered `ClaimRedeemed` / `BindingRefused` events, and the foreign keys cannot be re-added once ended claims have been cleaned up. If a rollback is ever needed, it is a separate, reviewed migration that first proves no undelivered event exists.

*Process note:* this migration was edited in place during the corrections. That's acceptable only because it is unmerged and has run solely on throwaway test databases.

## Testing
```bash
# scratch caches, so bootstrap/cache is never touched in a worktree
export APP_SERVICES_CACHE=<scratch>/services.php APP_PACKAGES_CACHE=<scratch>/packages.php
MEMBERSHIP_PG_IMAGE=docker.io/library/postgres:15@sha256:7da5d534f1ffcdb2f69efa6e466a7a29ff89eefd611c7b50c4d69aa77fd4a822 \
  bash scripts/testing/membership-pg.sh --filter RedeemClaimTest          # 20 tests
php vendor/bin/phpunit tests/Architecture/IdentityBindingSingleWriterTest.php   # database-free, 2 tests
bash scripts/testing/membership-pg.sh tests/Architecture/EventRegistryCompletenessTest.php   # needs a database
```

`RedeemClaimTest` (20 tests) covers:
- the binding and the published event (privacy: no PersonId, no code);
- re-presentation before and after cleanup (PI-48, **ordinary claims only**; bootstrap claims are I-6);
- INV-BND-2 with `BindingRefused`, including its aggregate (the claim);
- expired, revoked, unknown and malformed codes with one neutral message; not signed in;
- throttling per client and per code, then released; `PersonAlreadyBound` counts (R-6); purge of ended windows; client keys always HMAC'd and the secret never stored; no secret → unavailable;
- `pg_constraint`: the three foreign keys are gone and the binding keys remain (F-3);
- the migration's `down()` refuses and the outbox survives (R-3);
- **forked races** (one interleaving each, held open by a second process; **not load tests**):
  - two redemptions of one code (PI-1);
  - one Person redeeming two references at once → a reported refusal (R-1);
  - redemption across expiry (PI-22);
  - issuance after expiry, then the waiting redemption of the old code (PI-29);
  - issuance behind a redemption (PI-28);
  - PI-15, three orders only: replace-first, redeem-then-replace and redeem-then-revoke. Not every replacement/revocation interleaving.

**The single-writer guard (`tests/Architecture/IdentityBindingSingleWriterTest`, review R-4).**
- It is a text scan of `app/`, `routes/` and `database/` with four rules:
  - W1: raw SQL writes;
  - W2: query-builder writes, across lines;
  - W3: an Eloquent model on `identity_bindings`;
  - W4: setting a claim to `redeemed`.
- `PostgresClaims` is the only allowed writer. The guard must find every rule in the planted fixtures under `tests/Fixtures/IdentityBindingBypass/` (`*.php.fixture`, never loaded).
- **It does not prove there is no other writer.** Known misses:
  - dynamic, quoted or schema-qualified table names;
  - `TRUNCATE`, `MERGE` and `COPY`;
  - a builder kept in a variable;
  - `->from(…)` or a `getTable()` override;
  - `'redeemed'` passed as a bound parameter;
  - a second writer inside `PostgresClaims.php` itself;
  - code outside the scanned roots, and other database clients.
- The database is only a partial backstop: the trigger stops UPDATE and DELETE of a binding, but a rogue INSERT is limited only by the unique keys. **No test enforces** that only Identity's infrastructure reaches Identity's tables; code review covers the rest.

**Mutants (corrections run; each applied alone, then reverted).** All of the following were killed:

| Mutant | Change | Killed by |
|---|---|---|
| M-R1 | no retry | the R-1 race test |
| M-R2a | no purge | the purge test |
| M-R2b | plain SHA-256 client key | two throttle tests |
| M-R3 | empty `down()` | the forward-only test |
| M-R4 | W2 rule without `update*` | the planted-bypass test |
| M-R6 | `PersonAlreadyBound` not counted | the R-6 test |
| M-R8 | `BindingRefused` aggregate = R | the INV-BND-2 test |
| N1 | no slot lock | PI-22, PI-28, PI-29 |
| N3 | anyone gets `already-bound` | two tests |

- **N1 was not caught by the PI-15 tests:** the claim-row lock alone serialises replace against redeem.
- **The original I-3 run applied N1, N3, N4 and N6 together**, which does not show each one is killed separately. N4 (no throttle check) and N6 (PersonId in the event) were not re-run individually.

Evidence:
- `docs/publicdigit/identity/evidence/2026-11-15-pbdigit349-i3/` (original);
- `docs/publicdigit/identity/evidence/2026-11-15-pbdigit349-i3-corrections/` (corrections).

## Administrator notes
- **Release gate:** the central migration runs on **test databases only** until the Principal approves the rollout. It is **forward-only**: never run `migrate:rollback` across it, because it will refuse.
- **Client-key secret (required in practice):**
  - Set `IDENTITY_REDEMPTION_THROTTLE_CLIENT_KEY_SECRET` to a random value of **at least 16 characters**, e.g. the output of `openssl rand -hex 32`.
  - Keep it in the secret store, **use the same value on every application server** (otherwise each server counts separately), and never put it in logs, tickets or screenshots.
  - If it is unset, a secret is derived from `APP_KEY`. If neither exists, **every redemption is refused** as unavailable.
- **Rotating the secret, or `APP_KEY` when the secret is derived, has a security effect.**
  - Every client's failure count restarts at zero, so a client that was being throttled gets a fresh allowance immediately. Per-code counts are unaffected.
  - Prefer the dedicated secret, so that an `APP_KEY` rotation doesn't silently reset client throttling.
  - Rotate at a quiet time, and treat a rotation during an active guessing attack as weakening protection for up to one window.
- **Throttle limits:** optional overrides as above. Leave them unset to use the proposed defaults. **The values are not ratified.**
- **No scheduled job is needed** for throttle cleanup: it happens on every recorded failure.

## Pitfalls
- **Never add a second redemption path** (a console command, an admin "link manually" action). The single-writer guard fails on the known shapes; review must catch the rest.
- **Never retry other constraint violations** in `redeem()`: only INV-BND-2's key means "decide again".
- **Don't write Identity's events to the shared `outbox_events`;** its foreign key refuses registry organisations.
- **The client key is personal data.** Store only its HMAC; never fall back to a plain hash.
- **Don't add a rollback to the migration** to make tooling happy (R-3).

## Traceability
PBDIGIT-349 I-3 and its review corrections:
- review items R-1 … R-4, R-6 … R-8, F-3, PI-29; R-9 is a proposal only.
- design: I-TDD-02 §4, §6.2, §12 C-I3/C-I6, §14, §15; multi-database design §13.3.
- proof items with evidence: PI-1, 2, 3, 8, 9, 13, 15 (tested orders only), 22, 28, 29, 44; PI-34 (one forked interleaving, no load test); PI-48 (ordinary claims only).
- decisions: D-113, D-160, D-197, D-200; **D-241** (R-9 Option B: I-1 … I-3 authorised for development and test only, effective from the Principal's approval on 2026-11-15; **I-4 not authorised**; no production rollout. In the Identity branch's register copy until merge).

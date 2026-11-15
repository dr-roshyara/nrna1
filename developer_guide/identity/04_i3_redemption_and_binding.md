# 04. I-3: redeeming a claim code (binding)

**Story:** `PBDIGIT-349` step I-3 · **Design:** I-TDD-02 revision 12 (D-200) §4 (INV-ID-6/9, INV-BND-1/2/3, INV-CLM-3), §6.2 (redemption row), §12 C-I3, C-I6, §14 ("redeem while signed in", cases 3 and 5), §15; multi-database boundary design §13.3 step 7 · **Decisions:** D-113, D-197, D-200.

## Purpose (business)
A signed-in person enters the claim code their organisation gave them. Identity then records **"this organisation's `PersonRef` R is this Person"** (a binding) and tells the organisation, through Identity's outbox, that R was redeemed.

**A binding grants nothing by itself:** no membership, office, vote, forum or newsletter right. It's what later lets the person enter that organisation (I-4).

## What works now and what doesn't
| | Status |
|---|---|
| Redeeming an ordinary code; the binding; `ClaimRedeemed` and `BindingRefused` in Identity's central outbox; throttling; neutral errors | **implemented and proven** on PostgreSQL |
| **Production** | the use case works, but **no claim can be issued in production yet** (I-2 refuses until I-4), so there is nothing to redeem. **No web page or route** calls it yet (the UI is out of I-TDD-02's scope) |
| **Delivery of the events to the organisation's inbox** | **not built.** It needs Organisation's consumer (S4) and a relay under the organisation's context (multi-database design §13.3 step 8). The events wait in `identity_outbox_events` |
| Entering an organisation (Platform's `IdentityBindings`) | **I-4** |
| Bootstrap claims, claim-first registration | I-6, I-7 |

## Model and files (`app/Contexts/Identity/…`)
| Element | Role |
|---|---|
| `Application/RedeemClaim` + `RedeemClaimCommand` (code as typed, client key) | the use case. Order: signed in → the code parses → not throttled → the Account's active Person → the store |
| `Application/RedemptionOutcome`, `RedemptionRefusal` | `bound` / `already-bound` / `refused`. **One neutral message** for every refusal (PI-8) |
| `Application/Ports/ClaimStore::redeem` | **the one redemption path** (PI-44) |
| `Application/Ports/RedemptionThrottle` → `Infrastructure/PostgresRedemptionThrottle` | INV-ID-9: failures per code hash and per client-key hash, in a window. **Never disables a claim** (D-197) |
| `Application/Published/ClaimRedeemed` (O, R, claim id, boundAt), `BindingRefused` (O, R) | published language. **Never a PersonId, an Account or a code** |
| `Infrastructure/Outbox/*Hydrator` | registered in `IdentityServiceProvider::boot` |

## The redemption, step by step (`PostgresClaims::redeem`, one transaction, READ COMMITTED)
1. Find the claim by its code hash (a plain read: O, R). Nothing is decided on this read.
2. Lock **`ClaimSlot` (O, R)**, then the **claim row** (the global lock order, INV-CLM-6), then take **one `clock_timestamp()` sample** `t`.
3. **Already redeemed:**
   - the same Person with the same claim → **`already-bound`** (§14 case 3);
   - otherwise → neutral refusal (case 5, PI-2).
4. **Not active** (expired or revoked) → neutral refusal (PI-13, PI-22).
5. **R already bound** → neutral refusal (INV-ID-6).
6. **The Person already holds another R of O** → refused; **`BindingRefused(O, R)`** sent to O (INV-BND-2, PI-3). The claim stays usable.
7. Otherwise:
   - **create the binding** with `bound_at = t` (never back-dated, PI-9);
   - set the claim to `redeemed` with `ended_at = t`;
   - write **`ClaimRedeemed(O, R)`** to `identity_outbox_events`;
   - write the audit entry.

**Throttling** (`config/identity.php`):
- **per code:** 5 failures, **per client:** 10 failures, within **900 seconds**;
- what counts: unknown, malformed, expired, revoked or already-redeemed codes;
- **these values are proposals ([TU] in the design) and need the Principal's confirmation before release;**
- the environment variables `IDENTITY_REDEMPTION_THROTTLE_PER_CODE`, `…_PER_CLIENT` and `…_WINDOW_SECONDS` override them.

## Two schema findings fixed in this step (migration `2026_11_15_630000_…`)
1. **A correction to I-2's schema.**
   - I-2 made `identity_bindings.claim_id`, `identity_consumed_authority_requests.claim_id` and `identity_claims.replaces_claim_id` **foreign keys to claim rows.**
   - That would **block the cleanup of ended claim rows** (§15): a binding must survive it (PI-45, PI-48), and a consumed request must keep refusing replays.
   - They're now plain provenance identifiers.
2. **Identity's own outbox.**
   - The shared `outbox_events` table has `organisation_id` as a **foreign key to the legacy `organisations` table**.
   - Registry organisations live in `platform_served_organisations` (D-160: the two paths never share).
   - So the shared table can't carry Identity's events for registry organisations. **`identity_outbox_events`** (same columns, no legacy key) is "Identity's outbox" from the multi-database design §13.3.

## Testing
```bash
MEMBERSHIP_PG_IMAGE=docker.io/library/postgres:15@sha256:7da5d534f1ffcdb2f69efa6e466a7a29ff89eefd611c7b50c4d69aa77fd4a822 \
  bash scripts/testing/membership-pg.sh --filter RedeemClaimTest
```

`RedeemClaimTest` (11 tests) covers:
- the binding and the published event;
- re-presentation before and after cleanup (PI-48);
- INV-BND-2 with `BindingRefused`;
- expired, revoked, unknown and malformed codes with one neutral message;
- not signed in;
- throttling per client and per code, then released;
- the only writer of bindings (PI-44);
- **forked races:** two redemptions of one code (PI-1), redemption across expiry (PI-22), issuance behind a redemption (PI-28, PI-34), revocation behind a redemption (PI-15).

**Mutants:** N1 (no slot lock), N3 (anyone gets `already-bound`), N4 (no throttle check), N6 (PersonId in the event). All killed.

**Running the event-registry guard:** it needs a database. In a worktree without `.env`, run it inside the isolated cluster:
```bash
bash scripts/testing/membership-pg.sh tests/Architecture/EventRegistryCompletenessTest.php
```

Evidence: `docs/publicdigit/identity/evidence/2026-11-15-pbdigit349-i3/`.

## Administrator notes
- **One central migration**, under the release gate: **test databases only** until the Principal approves the rollout.
- **Optional environment variables** for the throttle limits (above). Leave them unset to use the proposed defaults.

## Pitfalls
- **Never add a second redemption path** (a console command, an admin "link manually" action). The scan in `RedeemClaimTest` fails on any other writer of bindings.
- **Don't write Identity's events to the shared `outbox_events`;** its foreign key refuses registry organisations.
- **The client key is personal data** (an IP address, say). Identity stores only its hash; keep it that way.

## Traceability
PBDIGIT-349 I-3 · I-TDD-02 §4, §6.2, §12 C-I3/C-I6, §14, §15 · multi-database design §13.3 · PI-1, 2, 3, 8, 9, 13, 15 (redeem half), 22, 28, 34, 44, 48 · D-113, D-160, D-197.

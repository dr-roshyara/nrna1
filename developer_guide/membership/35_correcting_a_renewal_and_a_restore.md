# 35: Correcting a renewal's end and a restore's date (lifecycle S2-5b), PBDIGIT-354

**Decisions:** **D-237** (Q-2: a Renew's `until`) · **D-241** (Q-6: a Suspend is not corrected) · **D-242** (Q-7: a Renew's start is not corrected) · D-177 row 14 (a Restore's date) · D-205 · D-206.
**Builds on:** guide 34 (the Correct / Void use cases); guides 13–14 (the correction algebra C-2 and its persistence C-4).
**Evidence:** `docs/publicdigit/membership/evidence/2026-11-16-pbdigit354-s2-5b/`.

## Business purpose

| A record says | In reality | What the officer does now |
|---|---|---|
| "renewed until 3 March" | the renewal was until 22 April (or without end) | **Correct** the renewal: its end date, which may be later than today, or open-ended (D-237) |
| "renewed from 23 November" | it was from 1 December | **Void** the renewal and record the right one (D-242: a renewal's start is not corrected) |
| "suspension lifted on 24 October" | it was lifted on 29 October | **Correct** the Restore's date (row 14) |
| "suspended from 14 to 3 …" (wrong dates) | | **Void** the suspension and record it again; a Restore ends it earlier (D-241) |

**In every case:**
- the original stays in the history, with the correction, the officer, the authority answer and the evidence;
- "as known on …" answers before the correction still show the original;
- the change reaches the organisation through C1 (`inForce` or `suspended` changed).

## How it works (the kernel)

**`Member::decideCorrect` decides by the root of the correction chain:**

| Root | Value means | Rules |
|---|---|---|
| Admit / End | its effective date | unchanged (C-2): ordering, not after the decision date, frozen trims; D-240 for an End (guide 34) |
| **Renew** | **its `until`** | **strictly after the Renew's start** (`EmptySegment` otherwise), **or ∞**; **not** bound by the decision date (a term's end is not an effective date, D-237 (2)); no trims (a term never changes coverage) |
| **Restore** | its date | **the Restore's own rules:** its Suspend live (`NotSuspended`), the period in force (`NotInForce`), strictly inside the restriction (`OutsideRestriction`), not after the decision date (D-205); no trims |
| **Suspend** | — | **refused `CorrectionOfSuspendNotPermitted`** (D-241) |

**Also:**
- **∞ is a valid value only for a Renew chain.** Anywhere else it is invalid input, rejected first; guard 1, in the accepted C-2 order.
- `ValueUnchanged` and `CorrectOfVoidedFact` apply to every chain.

**`Resolution` reads the corrected values:**
- a Renew's value is its `until`, corrected along the chain (`term()`);
- a Restore's value is its date, corrected along the chain (`restrictions()`);
- a Renew's start is never touched (D-242).

**On load** (`Member::fromLog`), a stored log is refused if it has an open-ended Correct that does not correct a Renew, or any Correct of a Suspend.

## The command

```php
new CorrectMembershipRecord($org, 'M1', 'C1', 'R1', null, 20_060, 'minutes:2024/12', 'registrar-1', null, 'k-1', openEnded: true);   // until → open-ended
new CorrectMembershipRecord($org, 'M1', 'C2', 'R1', 20_200, 20_060, 'minutes:2024/12', 'registrar-1', null, 'k-2');                // until → 22 April 2025
```

- **Exactly one of a date or `openEnded`;** otherwise `CorrectedValueRequired`, before anyone is asked.
- **The authority request** carries the corrected value (`correctedValue`, ∞ when open-ended).

## Persistence (migration 370000 / 300800)

- **`org_membership_facts.correct_value_unlimited boolean NOT NULL DEFAULT false`:** true exactly on a `correct` row whose value is ∞. Its `effective_at` is then NULL.
- **The kind CHECK:** a `correct` row has **exactly one** of a date or the flag.
- **`org_membership_facts_correct_value_check`:** the flag only on `correct` rows.
- **Why an explicit flag** rather than "NULL means open-ended": a CHECK on a NULL comparison is silently satisfied (guide 25 pitfall). It also follows the repository's own `…_kind` convention.

## Testing

```
vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Unit/Contexts/Membership/Domain/Organisational/CorrectRenewAndRestoreTest.php
MEMBERSHIP_PG_IMAGE=… MEMBERSHIP_PG_EVIDENCE_DIR=<dir> bash scripts/testing/membership-pg.sh --testsuite MembershipPostgres --filter PostgresCorrectRenewAndRestoreTest
```

- **`CorrectRenewAndRestoreTest` (12):**
  - beyond the decision date; open-ended; start ≤ until refused;
  - chain, Void, `knownAt`; a voided Renew;
  - ∞ only for a Renew;
  - the Restore inside the restriction, with its own rules; a chain of Restore corrections;
  - Suspend refused;
  - stored-log validation.
- **`PostgresCorrectRenewAndRestoreTest` (4):**
  - open-ended stored with the flag, reloaded, published, `knownAt`;
  - a date beyond the decision date;
  - a Restore corrected and a Suspend refused;
  - the CHECKs.
- **Accepted tests changed, and why:**
  - `RenewTest`, `SuspendRestoreTest`: their "not yet supported" refusals are replaced by the decided rules;
  - `CorrectDecisionTest`: "an infinite value is rejected on the fact" moved to the chain (D-237); the C-2 guard order is kept;
  - `PostgresCorrectionSchemaTest`: its rolled-back down/up check also clears the S2 row kinds that the 1b shape cannot hold.

## Pitfalls

- **A Correct of a Renew always means its `until`.** There is no "which date" field, by D-242.
- **Do not compare a corrected `until` with the decision date.** Mutant N2 proves that rule would wrongly refuse D-237's case.
- **A test sorting before `PostgresCorrectionSchemaTest` may commit new kinds of rows.** That test's rolled-back transaction must clear every kind the 1b shape cannot hold.

**Traceability:** PBDIGIT-354 S2-5b · D-237 · D-241 · D-242 · D-177 row 14 · D-205 · D-206.

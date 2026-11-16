# 33: A pending scheduled resignation takes effect first (D-240; lifecycle S2-4d-Q5), PBDIGIT-354

**Decision:** **D-240** (Q-5 Option A, Human Arb Chief, 2026-11-15). Also D-204, D-205, D-209, D-223; M-TDD-01 §5.2 (MI-3).
**Builds on:** guides 29–32.
**Plan:** `docs/plans/20261115-0510-release-1-build-slice-s2-membership-lifecycle-plan.md` §7d.
**Evidence:** `docs/publicdigit/membership/evidence/2026-11-15-pbdigit354-s2-4d-q5/`.

## Business rule

A member has scheduled their resignation for 3 November, and the confirming step has not recorded it yet. **While that declaration is still pending, Membership refuses any other ending of the same membership period effective on 3 November or later.** The refusal is `scheduled_resignation_takes_effect_first`.

| Attempt (while pending) | Effective | Result |
|---|---|---|
| resign now | 3 Nov or later | **refused**; nothing written |
| the death-related Membership End (`RecordDeath` → `End(death)`) | 3 Nov or later | **refused**; nothing written |
| an exclusion End (Governance push, S2-6) | 3 Nov or later | **refused at the append**; S2-6 reports it as inapplicable |
| any End | **before** 3 Nov | allowed (unchanged, MI-3); the declaration is later superseded |
| any End, once the declaration is withdrawn, executed or superseded | any | not affected by D-240 |

Afterwards the confirming step records `End(resignation)` **on 3 November**, the member's date. There is one End, and the competing command never executes the declaration itself.

### A death fact is not a Membership End

- Recording that a person has died is a fact about the person. **D-240 does not prevent it.**
- What D-240 refuses is a **Membership End** because of death that would displace the scheduled resignation.
- **In today's code only the Membership End exists:** `RecordDeath` records `End(death)` with its evidence reference. No operation records a person's death as a person fact.
- Which context owns that fact, and whether `RecordDeath` should be renamed to say what it is, is **PBDIGIT-356**.
- **Do not model a person's death by appending an End.**

## Where it is enforced, and why there

| Layer | Piece |
|---|---|
| Domain | `ScheduledResignation::refusesCompetingEndOn(TimePoint): ?RefusalReason`: the rule; a pure date comparison, no calendar |
| Domain | `RefusalReason::ScheduledResignationTakesEffectFirst` |
| Port | `AppendOutcome::RefusedByDomain` + `AppendResult::refusedByDomain()/refusal()`; `DecideAndAppend` maps it to `CommandOutcome::refusedByDomain` (never retried) |
| Infrastructure | `PostgresMemberRepository::pendingScheduledResignationRefusal()`, called in the append transaction **before** the version compare-and-set |

**Why at the append, not in each handler:**
- Every End, from any path, passes through the append: resign now, `RecordDeath`, `EndMembership`, the future S2-6 push, and anything later. One enforcement point cannot be bypassed.
- The rule depends on state outside the Member aggregate (the declarations, D-204). The append is where that state can be read **under the locks that make it stable**.

**The locks, in the established order** (clock row, then membership row):
1. **The organisation's clock row** (`FOR UPDATE`): already taken by every append. The confirming step and every withdrawal take it too, so no declaration is executed or withdrawn between the check and the append.
2. **The membership row** (`FOR UPDATE`, new in the check): the lock a new scheduling takes (`PostgresScheduledResignationStore::record`). Scheduling does **not** change the membership version, so the compare-and-set alone would not stop a declaration appearing between the check and the append. This lock does. Mutant Q6 proves it.

**The confirming step's own End is exempt only for the declaration it executes.** It passes that declaration's id through `appendWithinClose(…, $executing)`.

## Testing

```
vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Unit/Contexts/Membership/Domain/Organisational/ScheduledResignationTest.php tests/Unit/Contexts/Membership/Application/Organisational/AppendOutcomeMappingTest.php
MEMBERSHIP_PG_IMAGE=… MEMBERSHIP_PG_EVIDENCE_DIR=<dir> bash scripts/testing/membership-pg.sh --testsuite MembershipPostgres --filter 'PostgresScheduledResignation(Precedence|Race)Test'
```

- **`PostgresScheduledResignationPrecedenceTest` (7):**
  - resign now, on and after the date;
  - `End(death)`, on and after;
  - an exclusion End appended directly, on and after;
  - after the refusals, the confirming step records exactly one End on 3 November;
  - an earlier End is unchanged (MI-3);
  - another period's declaration does not count;
  - a withdrawn declaration refuses nothing.
- **`PostgresScheduledResignationRaceTest`, real processes:**
  - the confirming step holds the lock, so the competing End finds nothing to end;
  - **a scheduling in flight holds the membership row: the competing End waits and is refused;**
  - 8 free rounds;
  - every round ends with exactly the scheduled End on the member's date.

## Pitfalls

- **Do not add this check to a single handler.** It would miss the other End paths. The append is the one place.
- **Keep the membership-row `FOR UPDATE`.** Without it, a declaration committed between the check and the compare-and-set is missed (Q6).
- **Not a calendar question.** D-240 compares the End's effective date with the declaration's date. Don't bring the `OrganisationCalendar` in.
- **A Correct that moves an End's date** is not covered here. Corrections are S2-5, which must respect D-240 when it lands.

**Traceability:** PBDIGIT-354 S2-4d-Q5 · D-240 · D-204 · D-205 · D-223 · MI-3 · PBDIGIT-356.

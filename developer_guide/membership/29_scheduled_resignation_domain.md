# 29: The scheduled resignation declaration and D-205's single exception (lifecycle S2-4a), PBDIGIT-354

**Decisions:** D-201 (a resignation may state a later date; withdrawable before it) · D-204 (a declaration held outside the ledger) · D-205 (nothing future-dated, with one exception) · D-223 (late execution) · D-239 (the calendar comes from a port; S2-4c) · M-TDD-01 §5.2.
**Plan:** `docs/plans/20261115-0510-release-1-build-slice-s2-membership-lifecycle-plan.md` §7c, step S2-4a.
**Evidence:** `docs/publicdigit/membership/evidence/2026-11-15-pbdigit354-s2-4a/`.
**Scope:** the domain only. Use cases and storage are S2-4b; execution in the closing step is S2-4c; races and routing are S2-4d.

## Business purpose

- **A member may declare a resignation from a later date** and change their mind before that date.
- **Until the date, nothing changes.** The person is a member, keeps their rights, and the membership feeds show no planned change (MI-4).
- **On the date, the resignation is carried out:** the membership ends **on the scheduled date**, even if the system records it later. The record states truthfully **when the member decided**.

## The model

**`ScheduledResignation` is not a ledger fact.** It is held per period, like an application (D-204), and only its execution records a fact.

| State | Reached by | Then |
|---|---|---|
| `scheduled` | `ScheduledResignation::schedule(...)` | can be withdrawn, superseded or executed |
| `withdrawn` | `withdraw($on)`, only before the scheduled date starts | final |
| `superseded` | `supersede($because)`, when the period ended earlier (MI-3) | final, the reason recorded |
| `executed` | `markExecuted($endFactId)`, after its End is recorded | final, the End referenced |

Every transition returns a new value, or a `RefusalReason`.

**`schedule()` guards:**

| Guard | Refusal |
|---|---|
| the id is new | `DuplicateDeclarationId` |
| the scheduled date is after the declaration (same day = resign now, S2-3) | `ScheduledDateNotAfterDeclaration` |
| a current period at the declaration date | `PeriodNotFound` |
| the period does not already end by the scheduled date | `PeriodEnded` |
| **no other `scheduled` declaration for that period** | `ScheduledResignationAlreadyPending` |

The period is derived. The declaration keeps **the authority answer given when it was declared** (`provenance`, `decidedAt`); execution uses it, and nobody is asked again.

## D-205's single exception (`Member::decideScheduledResignationEnd`)

```php
$decision = $member->decideScheduledResignationEnd($endFactId, $declaration, $recordingDate);
```

- **The only path where an End takes effect after its decision.**
  - effective = the **scheduled date**;
  - `decidedAt` = **the declaration's**;
  - provenance = the declaration's answer, with `declarationRef` = the declaration id (traceability).
- **Recorded on or after the scheduled date, never before:** `ExecutionBeforeEffectiveDate` (D-223, MI-15c). **Late is fine:** still effective on the scheduled date (MI-15b).
- **Refusals:**
  - `DeclarationNotScheduled` (withdrawn, superseded, already executed);
  - `PeriodAlreadyEnded` (the caller supersedes it instead, MI-3);
  - `DuplicateFactId`;
  - `PeriodNotFound`;
  - `EndNotAfterPeriodStart`.
- **Nothing else changed:** `decideEnd`, `decideRenew`, `decideSuspend` and the rest still refuse "effective after the decision date" (MI-1, MI-15f; tested).
- **`$recordingDate`** is the organisation-local date of the recording. In S2-4c it comes from the `OrganisationCalendar` port (D-239). The domain never sees a time zone.

## Testing

```
vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Unit/Contexts/Membership/Domain/Organisational/ScheduledResignationTest.php
```

`ScheduledResignationTest` (16) covers:
- scheduling and its guards, including one per period;
- withdrawal before / on / after the date;
- superseding;
- execution on the date, late, and before the date;
- final states;
- the exception confined to this one path.

## Pitfalls

- **Do not create the End with `decideEnd`:** its guard refuses a future effective date, by design. Use `decideScheduledResignationEnd`.
- **A declaration is not a fact:** never append it to the Member's log; it gets its own table (S2-4b).
- **In tests, read optional values null-safely** (`?->`). Mutant K6 first crashed a test instead of failing it.

**Traceability:** PBDIGIT-354 S2-4a · D-201 · D-204 · D-205 · D-223 · D-239 · MI-1, MI-3, MI-15a … f.

# 31: Executing scheduled resignations in the ledger close (lifecycle S2-4c), PBDIGIT-354

**Decisions:** D-204 · D-205 (2) · D-209 (the closing step) · D-223 (late execution) · **D-239** (the `OrganisationCalendar` port) · M-TDD-01 §5.2 · LC-4.
**Builds on:** guide 20 (`LedgerClockCloser`), guide 29 (the declaration, D-205's exception), guide 30 (the declaration store).
**Plan:** `docs/plans/20261115-0510-release-1-build-slice-s2-membership-lifecycle-plan.md` §7c, step S2-4c.
**Evidence:** `docs/publicdigit/membership/evidence/2026-11-15-pbdigit354-s2-4c/`.

## Business purpose

- **On the date a member chose, their resignation is carried out.** The membership ends on that date, even if the system gets to it later.
- **Nobody is ever told "still a member" after that date** while the resignation is still unrecorded. Membership's completeness point (`closedThrough`) does not move past the start of that day until the End is recorded. If recording fails, it stays where it was, and the services that rely on it (Organisation, Newsletter, Forum) refuse rather than answer wrongly.
- **"The date" is the organisation's own date.** In Kathmandu, 3 November begins 5 h 45 min before it does in UTC.

## Where it fits

| Layer | Files |
|---|---|
| Port (D-239) | `Application/…/Ports/OrganisationCalendar::localDateAt(TenantId, Instant): TimePoint` |
| Infrastructure | `ScheduledResignationExecutor` (the real `DueScheduledResignations`); `PostgresScheduledResignationStore::lockDue()`; `PostgresMemberRepository::appendWithinClose()` |
| Unchanged | `LedgerClockCloser` (it already called the hook in its transaction); production wiring (`RoutedLedgerBoundary` → `NoDueScheduledResignations`) |
| Test only | `tests/Support/FixedOffsetOrganisationCalendar` (an explicit fixed UTC offset; never production) |

## How it works

`LedgerClockCloser::closeOrRead()` runs one transaction:
1. it takes the organisation's clock row (`FOR UPDATE NOWAIT`) and reads the clock once (`t`);
2. it calls the hook with `t`;
3. it writes `closed_through = t`.

The executor does step 2:

```php
$today = $this->calendar->localDateAt($organisation, $t);          // D-239: the organisation-local date of t
foreach ($store->lockDue($organisation, $today) as [$memberId, $declaration]) {   // scheduled, effective ≤ today, FOR UPDATE
    $decision = $member->decideScheduledResignationEnd($endId, $declaration, $today);  // D-205 (2): effective = the scheduled date
    // accepted       → appendWithinClose(…, $t): recordedAt = the close's reading; C1 in the same transaction
    //                  → markExecuted($endId): the declaration names its End
    // period ended / gone → supersede("the period ended before the scheduled date (…)")   (MI-3)
    // anything else  → throw
}
```

- **Why this satisfies LC-4.** `closedThrough = t`, and `t` falls on the local date `today`. Every declaration with an effective date ≤ `today` is executed in that same transaction, or the whole close rolls back. So `closedThrough` never passes the start of a day with an unexecuted due declaration.
- **A failure holds the boundary (MI-10).** Any exception becomes `ScheduledExecutionFailed`: the close rolls back (Ends, C1 events and declaration states included) and `closeOrRead()` returns the old boundary. C2 then answers `BOUNDARY_NOT_CLOSED` for later τ.
- **Late execution (D-223).** If no close ran for days, the next one records the End effective on the scheduled date, `decidedAt` = the declaration, recordedAt = now.
- **Withdrawal vs execution (MI-2).**
  - `lockDue()` locks the due rows, so a withdrawal running at the same time waits, then its `WHERE state = 'scheduled'` finds nothing (Conflict, then `DeclarationNotScheduled`).
  - A withdrawal that committed first is never selected.
  - The two-session race on PostgreSQL is proved in S2-4d.
- **Deterministic ids.** The End is `scheduled-resignation:<declaration>`, with keys `execute-…` / `executed-…` / `supersede-…`. A re-run after a rollback writes the same End.
- **`appendWithinClose()` refuses outside a transaction.** It applies all of `append()`'s checks, but uses the close's reading rather than a second clock reading. Production still has exactly one `LedgerClockReader` (architecture test).

## Production and administration

- **Production is unchanged and fail-closed:**
  - `RoutedLedgerBoundary` still builds the closer with `NoDueScheduledResignations`;
  - nothing binds `OrganisationCalendar`, and its production answer belongs to Organisation S4 (D-239);
  - no scheduled declaration can exist in production anyway: no authority binding until Governance S3.
- **When S4 delivers the calendar, binding it is one change:** `new LedgerClockCloser($db, $clock, new ScheduledResignationExecutor($calendar))` in `RoutedLedgerBoundary`, with its routing proof. That is a separate, authorised step.
- **No migration in S2-4c.**

## Testing

```
MEMBERSHIP_PG_IMAGE=… MEMBERSHIP_PG_EVIDENCE_DIR=<dir> bash scripts/testing/membership-pg.sh --testsuite MembershipPostgres --filter PostgresScheduledResignationExecutionTest
```

`PostgresScheduledResignationExecutionTest` (9) covers:
- MI-15a on the date;
- the day before;
- **the local date, not UTC** (the same instant: not due in UTC, due in Nepal);
- MI-15b late;
- **MI-10 / LC-4:** a failing calendar holds the boundary, and C2 refuses;
- **one transaction:** a failure after two Ends rolls both back, with no C1 escaping;
- every due declaration in one close, later ones waiting;
- MI-3 superseded;
- a withdrawn declaration never executed, with the close advancing.

## Pitfalls

- **Never run the executor outside the close.** `lockDue()` and `appendWithinClose()` refuse without a transaction, by design.
- **"Due" is `effective_date <= today`, not `<`.** Mutant X2 proves the difference.
- **Do not bind a "temporary" production calendar (UTC, a config value, the legacy column).** D-239 forbids it. Production stays on `NoDueScheduledResignations` until S4.
- **In tests, assert that an End exists before reading `[0]`.** Mutants X1 and X2 first crashed the tests instead of failing them.

**Traceability:** PBDIGIT-354 S2-4c · D-204 · D-205 (2) · D-209 · D-223 · D-239 · M-TDD-01 §5.2 · MI-3, MI-10, MI-15a/b/e · LC-4.

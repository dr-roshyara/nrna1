# 32: Scheduled resignations: withdrawal at ledger time, the race, routing and isolation (lifecycle S2-4d), PBDIGIT-354

**Decisions:** D-201 · D-204 · D-209 · D-223 · **D-239** · M-TDD-01 §5.2, §5.3, §13 rows 11, 17, 18 · MI-2, MI-3, MI-4, MI-10.
**Builds on:** guides 29 (domain), 30 (schedule / withdraw / store), 31 (execution in the close).
**Plan:** `docs/plans/20261115-0510-release-1-build-slice-s2-membership-lifecycle-plan.md` §7c, §7d (**Q-5 open**).
**Evidence:** `docs/publicdigit/membership/evidence/2026-11-15-pbdigit354-s2-4d/`.

## Business purpose

- **"Withdrawn before the date" means before the organisation's own date has started**, judged by the system, not by the date someone types in. On 3 November in Kathmandu, a resignation for 3 November can no longer be withdrawn, even if it is still 2 November in UTC and the confirming step hasn't run yet.
- **Withdrawal and execution can never both succeed.** Either the member stays (withdrawn, no End) or they leave on their date (executed, one End). Never both, never two Ends.
- **Each organisation's declarations live only in its own database.** No other organisation can see, withdraw or execute them.

## 1. Withdrawal is decided at ledger time (S2-4d C)

`WithdrawScheduledResignationHandler`:
1. **Early check, with the stated date:** `ScheduledResignation::withdraw($on, $by)`, after the D-238 order (authority before "not found").
2. **The store decides again:** `ScheduledResignationStorePort::withdraw($tenant, $member, $id, $declaredOn, $by, $key, $fp)`.

`PostgresScheduledResignationStore::withdraw()` runs one transaction:

```text
key check
→ SELECT … FROM org_membership_ledger_clocks … FOR UPDATE      (the lock every append and the close take)
→ ONE clock reading → OrganisationCalendar::localDateAt()      (D-239: the organisation's date of that reading)
→ the declaration row FOR UPDATE
→ ScheduledResignation::withdraw($declaredOn, $by, $organisationToday)   (the rule stays in the domain)
→ UPDATE … WHERE state = 'scheduled'                            (compare-and-set) + key row
```

- **A refusal comes back unchanged** (`WithdrawalAfterEffectiveDate`, `DeclarationNotScheduled`). Nothing is written, not even the key.
- **No calendar, no withdrawal:** the store throws (`LogicException`, D-239). That's fail closed.
- **Lock order:** clock row, then declaration, the same order as the close. No deadlock.

## 2. MI-2: withdraw vs execute, with real concurrent processes

`PostgresScheduledResignationRaceTest` uses forked processes, each with its own connection and the **real** clock:

| Test | Forced interleaving | Result |
|---|---|---|
| execution holds the lock | the close executes and holds its transaction 400 ms; the withdrawal starts at 100 ms and **waits on the clock row** | executed, one End; the withdrawal is refused `declaration_not_scheduled` |
| withdrawal holds the lock | the withdrawal's calendar answers in 400 ms while it holds the clock row; the close starts at 100 ms (`NOWAIT`) and **finds it busy** | withdrawn, no End; the close did not advance; the next close executes nothing |
| 8 free rounds | released together | always exactly one success; a later close never executes a withdrawn declaration, nor twice. The winners' tally is printed in the suite output |

**The midnight case.** To make both sides eligible at once, the close's calendar says "3 Nov" and the withdrawal's says "2 Nov", as two readings either side of midnight would. With one calendar, the clock-row lock already orders them. The compare-and-set is the second line of defence.

## 3. MI-4: what C1 and C2 show

`PostgresScheduledResignationExecutionTest::test_mi4_…` checks C1 = `MembershipCoverageResolved` and C2 = `CoverageKnownAt`:
- **scheduling:** no C1 event; C2 unchanged;
- **due but unexecuted** (the calendar fails):
  - C2 for an earlier τ keeps its answer;
  - C2 at τ on the date is `BOUNDARY_NOT_CLOSED`, never "member";
  - still no C1 event;
- **executed late:**
  - C2 from the recording shows coverage ending on the scheduled date;
  - C2 "as known" at an earlier τ is unchanged;
  - one C1 event.

## 4. Part D: another ending while the declaration is due

- **Settled (MI-3), tested:** an ending effective **before** the scheduled date, recorded while the declaration is due, supersedes it. One End; the declaration is kept with its reason.
- **Not settled: Q-5, plan §7d.** An ending effective **on or after** the scheduled date. Today the later ending stands, and the declaration is superseded with an inaccurate reason. **Do not rely on that; it awaits the decision.**

## 5. Routing and isolation (S2-4d E)

- **`RoutedScheduledResignationStore`** builds `PostgresScheduledResignationStore` per call on Platform's published `RoutedOrganisationConnection`. For another organisation it throws `NotTheRoutedOrganisation`.
  - It is bound to `ScheduledResignationStorePort` in `MembershipServiceProvider`.
  - **It needs `OrganisationCalendar`**, which nothing binds, so in production the container cannot build it or the schedule / withdraw use cases (`BindingResolutionException` naming the calendar). That is fail closed, independently of the unbound authority (S3).
- **`MembershipThroughRoutingTest::test_s2_4d_…`** uses two real organisation databases with the same member and declaration ids. Routed to A, it:
  - cannot read B's declaration;
  - cannot withdraw it (`DeclarationNotFound`);
  - cannot reach B's store;
  - A's close executes nothing of B's.

  B's own close executes it in B.
- **`MembershipScheduledResignationWiringTest` (architecture):**
  - no production `OrganisationCalendar`;
  - no test calendar or `Tests\` in `app/`, `config/` or `routes/`;
  - `ScheduledResignationExecutor` is never constructed in `app/`, and `RoutedLedgerBoundary` builds its closer without one (production keeps `NoDueScheduledResignations`);
  - no time-zone setting in Membership code.

  Each rule is checked against a planted violation.
- **Parity:** `OrganisationMigrationsTest` still matches the central schema (both tables) and refuses another organisation (T8).

## Wiring when Organisation S4 delivers the calendar (a separate, authorised step)

Do these **together**, in one change, with the routing proof:
1. bind `OrganisationCalendar` to Organisation's adapter;
2. in `RoutedLedgerBoundary`, build the closer with `new ScheduledResignationExecutor($calendar)`;
3. update `MembershipScheduledResignationWiringTest` G1 and G3 in the same commit.

If only the calendar is bound, declarations could be scheduled while the close still executes nothing. G3 would then still pass, so this ordering is a release checklist item.

## Testing

```
MEMBERSHIP_PG_IMAGE=… MEMBERSHIP_PG_EVIDENCE_DIR=<dir> bash scripts/testing/membership-pg.sh --testsuite MembershipPostgres --filter PostgresScheduledResignation
MEMBERSHIP_PG_IMAGE=… MEMBERSHIP_PG_EVIDENCE_DIR=<dir> bash scripts/testing/membership-pg.sh --testsuite PlatformPostgres --filter 'MembershipThroughRoutingTest|OrganisationMigrationsTest'
vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Architecture/MembershipScheduledResignationWiringTest.php
```

## Pitfalls

- **Do not decide a withdrawal from the stated date alone.** Mutants D1 and P2 show the difference.
- **Keep the clock-row lock in `withdraw()`.** Mutant P1: without it, the close executes underneath a withdrawal.
- **A guard that scans source must ignore comments.** These docblocks mention `organisations.timezone` on purpose.
- **`DateTimeZone('UTC')` in `DateOrdinal` is date-label arithmetic, not an organisation time zone.** G4 allows it deliberately.

**Traceability:** PBDIGIT-354 S2-4d · D-201 · D-204 · D-209 · D-223 · D-239 · M-TDD-01 §5.2, §5.3, §13 · MI-2, MI-3, MI-4, MI-10 · Q-5 (open).

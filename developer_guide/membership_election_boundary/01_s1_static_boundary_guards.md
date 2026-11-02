# S1: static guards for the Membership–Election boundary

## Purpose

The ratified Membership architecture says: **"There is no shared persistence and no Membership → entitlement shortcut"** (`docs/publicdigit/reviews/2026-10-08-membership-final-ratification-verdict.md:46`).

S1 makes that rule **executable**:
- new coupling between Membership and Election fails the test suite;
- the four known violations are listed in an approved, shrink-only baseline.

S1 changes no product code and no business test.

## Where it fits

| | |
|---|---|
| Layer | architecture / fitness tests (`tests/Architecture`, PHPUnit suite **Architecture**) |
| Kind | static analysis: plain `PHPUnit\Framework\TestCase`; no framework boot; **no database** |
| File | `tests/Architecture/MembershipElectionBoundaryTest.php` |

## The three guarded kinds of coupling

| Test | Coupling | What it scans |
|---|---|---|
| `test_membership_code_does_not_reference_election` | write / class coupling | Membership code (`app/Contexts/Membership`, legacy `app/Models/Member*.php`, `Membership*.php`) for Election classes, tables and namespaces |
| `test_election_code_does_not_read_membership_or_affiliation_data` | read coupling | Election code (`app/Contexts/Election`, `app/Contexts/Elections`, `app/Domain/Election`) for Membership- or affiliation-owned table names (strings, raw SQL), model calls and imports |
| `test_no_foreign_key_from_election_tables_to_membership_or_affiliation_tables` | integrity coupling | **replays the `up()` of every migration in filename order** (adds and drops, including `dropForeign` and raw `ALTER TABLE … ADD CONSTRAINT / DROP FOREIGN KEY`) and checks the foreign keys left at the end |

**Comments and docblocks are removed** with PHP's tokenizer before scanning, so prose such as "Election owns eligibility" does not count.

## The approved baseline (PO, 2026-11-02)

| ID | Violation | Entry | Removed by |
|---|---|---|---|
| V1 | `Member::endMembership` writes `election_memberships` (`app/Models/Member.php:184-189`) | `app/Models/Member.php: ElectionMembership class` | S2 |
| V1b | `Member::canVoteInElection(Election)` decides voting entitlement (`app/Models/Member.php:142-149`) | `app/Models/Member.php: Election model` | S2 |
| V2 | the Election eligibility adapter reads `members` / `organisation_users` / `membership_types` | `…/EloquentVoterEligibilityQueryService.php`: three labels | S3 |
| V3′ | `voters.member_id` → `members` ON DELETE CASCADE (`2026_03_07_145529_create_voters_table.php:30`) | `voters(member_id) -> members` | S4 |

**The baseline can only shrink:**
- a baseline entry that **no longer** violates fails the test ("remove it from the baseline");
- an unlisted violation fails as "not in the approved baseline".
- **Adding an entry needs an explicit architecture decision** (PO / ARB), never a silent edit.

## Design decisions

| Decision | Reason |
|---|---|
| Option A (one application, separate persistence ownership) | decision record, Decision 1 |
| Static test, not deptrac | `deptrac.yaml` has layers only for Contestation, Adjudication, `app/Contexts/Election` and Shared; it covers neither Membership, `app/Contexts/Elections`, nor the legacy `app/Models`. Deptrac also cannot see table names in raw queries. |
| Net migration state, not "any FK ever added" | the `election_memberships` → `user_organisation_roles` key added in `2026_03_17_213212` is **dropped** by `2026_05_19_000001` `up()`. Reporting it would be wrong (see the V3 correction in the decision record). |

## How to use and extend

- **Fixing a violation (S2–S4):** remove its baseline entry in the same commit. The test then guards against regression.
- **New Membership or Election paths, or new tables owned by either side:** extend `MEMBERSHIP_PATHS`, `ELECTION_PATHS`, `FOREIGN_TABLES` or `ELECTION_TABLES` / `ELECTION_TABLE_PREFIXES`.
- **A legitimate new dependency:** only through an approved contract. Do not add a baseline entry to make the build green.

## Testing

```bash
vendor/bin/phpunit tests/Architecture/MembershipElectionBoundaryTest.php --no-coverage
```

**Evidence (2026-11-02):**
- **RED** with empty baselines reported exactly V1, V1b (`Member.php`), V2 (the adapter) and V3′ (`voters(member_id) -> members`).
- **GREEN** with the approved baseline: 3 tests, 6 assertions.
- **Mutation checks** on scratch copies:
  - an extra fake baseline entry failed with "no longer present";
  - removing V3′ from the baseline failed with "not in the approved baseline".

## Pitfalls

- **The migration replay is an approximation.** Driver branches (`if (DB::getDriverName() === 'mysql')`) are treated as executed. A `Schema::create/table` segment owns the `->foreign()` calls that follow it. `constrained()` without an argument infers the table as `<column without _id>s`.
- **Migration source is not the deployed schema.** S4 needs a read-only catalog check.
- **Pattern scans can miss dynamic table names** (e.g. a table name built at runtime).
- `app/Domain/Voting` and other Election-adjacent folders are not in `ELECTION_PATHS`. Add them by decision if they are Election-owned.

## Traceability

PBDIGIT-318 S1 · plan `docs/plans/20261102-0339-membership-election-boundary-remediation-plan.md` · decision record `docs/publicdigit/reviews/2026-11-02-membership-election-separation-decision-record.md` · ratified rule `docs/publicdigit/reviews/2026-10-08-membership-final-ratification-verdict.md:46`.

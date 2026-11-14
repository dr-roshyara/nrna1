# 23: The Membership ledger in an organisation's own database (PBDIGIT-345 tasks 4–5)

**Why this guide:** three Membership migrations were added for platform routing. Platform guides 04 and 05 describe the routing side; this is the Membership side. **Owner of the code:** Membership. **Platform imports none of it.**

## Business purpose

**Each organisation keeps its own membership register in its own database (D-222).** These migrations build that register when an organisation database is prepared during founding (Platform guide 05, step 3). They never run on the central database.

## The three migrations (the ORGANISATION migration set)

Location: `app/Contexts/Membership/Infrastructure/Database/OrganisationMigrations/`. The paths are listed in `config/organisation_routing.php` under `migration_paths`; the central `php artisan migrate` never sees them.

| Migration | What it creates | Why |
|---|---|---|
| `2026_11_14_300100_create_org_membership_ledger` | the ledger tables at their **current central shape** (`org_memberships`, `org_membership_facts`, `org_membership_commands`, `org_membership_ledger_clocks`) | a new database has no history to replay. **The one difference (F-13):** `organisation_id` references this database's one-row `organisation_identity`, so a row for another organisation is refused by the database (T-8) |
| `2026_11_14_300200_create_ledger_clock_on_organisation_identity` | an `AFTER INSERT` trigger on `organisation_identity` | the ledger clock row (D-209) exists as soon as the database is bound to its organisation. It is the same row `LedgerClockRow::ensure` would create, and that call then finds it (`ON CONFLICT DO NOTHING`) |
| `2026_11_14_300300_create_ledger_readiness_function` | `org_membership_ledger_readiness(p_organisation uuid, OUT schema_present boolean, OUT clock_row_present boolean)` | Platform's founding step 5 (L-1) needs to know whether the ledger is ready. **The R5 boundary** (`MembershipLedgerIsolationTest`) forbids code outside Membership to name a ledger table, so **Membership answers itself**: two booleans, no ledger data |

## Rules for changing the ledger

1. **Change the central ledger, change this set too.** `OrganisationMigrationsTest::test_the_organisation_ledger_schema_matches_the_central_one_except_f13` compares both, constraint by constraint, and fails otherwise.
2. **A new organisation-set migration needs a new file.** Never edit an applied one: organisation databases record it in their own `migrations` table.
3. **If a ledger table is renamed or added, update `org_membership_ledger_readiness`.** Platform's readiness depends on its answer, not on the table names.
4. **Organisation-set migrations reference only tables the set creates** (T-10 part 2, `OrganisationMigrationSetTest`).

## Not yet in the organisation database

**There is no outbox in the organisation database yet.** The C1 event (guide 18) is written to the central outbox for the central ledger. The per-organisation outbox and relay are PBDIGIT-345 task 9 (E-5), and Membership's adapters are task 10. Until then nothing writes to an organisation ledger.

## Testing

```
MEMBERSHIP_PG_IMAGE=<pinned> MEMBERSHIP_PG_EVIDENCE_DIR=<dir> bash scripts/testing/membership-pg.sh --testsuite PlatformPostgres
vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Architecture/OrganisationMigrationSetTest.php tests/Architecture/MembershipLedgerIsolationTest.php
```

Coverage:
- parity and F-13: `OrganisationMigrationsTest`;
- the trigger: `OrganisationProvisioningTest::test_step_three_runs_in_a_child_process_and_prepares_only_that_database`;
- the function: the T-13 cases in `OrganisationProvisioningTest`.

**Traceability:** PBDIGIT-345 tasks 4 (`ea721247e`), 5b (`45522a35a`), 5c (`b00c45814`) · D-209, D-222, F-13, L-1, R5 · Platform guides 04, 05.

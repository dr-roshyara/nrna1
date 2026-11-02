# S1b: hardened Membership–Election boundary guards

**Ticket:** PBDIGIT-318 · **Slice:** S1b, test-only, authorised by the PO on 2026-11-02 after the independent review of S1
**File:** `tests/Architecture/MembershipElectionBoundaryTest.php`
**Builds on:** guide 01 (S1). Read that first; this guide covers only what S1b changes.

## Purpose

The independent review showed that S1 guarded only part of what the plan promised. In particular:
- Membership writes to Election tables other than `election_memberships` passed;
- a new violation could hide inside a file that was already baselined;
- an array key equal to a table name failed the test (a false positive).

S1b closes those gaps. It changes **no runtime code**, no scan paths and no ownership.

## Where it fits

- `tests/Architecture`, plain PHPUnit, no database, no framework boot.
- It runs in the Architecture suite like S1.

## What changed

| Area | S1 | S1b |
|---|---|---|
| What is tracked | file + pattern label (e.g. "table name string") | file + **target**: `model:X`, `table:y`, `namespace:Z`. A new table or model in an already-baselined file is a new violation |
| Membership → Election | `ElectionMembership`, `'election_memberships'`, `App\Models\Election`, Election namespaces | every Election-owned **model** (`ELECTION_MODELS`, 28 classes) and **table** (`ELECTION_TABLES` + prefixes), plus the namespaces |
| Model references | plain `use` only | plain, **aliased** (`as`) and **grouped** (`{A, B}`) imports; fully qualified names; **bare names inside `namespace App\Models`** |
| Table references | any quoted string equal to a table name | **anchored only**: query-builder calls (`table`, `from`, `join`, `leftJoin`, …), `Rule::exists/unique`, `exists:`/`unique:` rule strings, `$table` properties, and **SQL statement strings** (with `as` aliases and schema prefixes) |
| Foreign-key replay | `database/migrations` + context folders | + `database/migrations/landlord`; `constrained(table: …)`; `Schema::connection(…)->table(…)`; unnamed raw `ADD FOREIGN KEY`; raw `CREATE TABLE … REFERENCES` |
| Self-tests | none | data-provider tests that plant each form and assert detection, or non-detection for permitted forms |

## Key design decisions

- **Three views of a source** (`views()`), built with the PHP tokenizer:
  - `code`: comments removed;
  - `plain`: comments and string contents removed, used for class references, so a class name in a message string never counts;
  - `strings`: the string contents, used for SQL statements and validation rules.
- **Anchoring, not string equality.** A table counts only where code *uses* it as a table. That removes the E6 false positive (`['members' => 0]`) and prose ("remove the member from elections").
- **Baseline granularity changed, content did not grow on its own.** V1/V1b/V2/V3′ are the same findings at target level. The new entries V1c and V1d were added only after the PO's decision (below).

## The baseline after S1b

```php
'app/Models/Member.php' => ['model:Election', 'model:ElectionMembership', 'model:Voter'],                 // V1, V1b, V1c
'app/Contexts/Membership/Infrastructure/Jobs/CleanupExpiredInvitations.php' => ['model:VoterInvitation'],  // V1d
'app/Contexts/Elections/Infrastructure/Policies/EloquentVoterEligibilityQueryService.php' => [
    'model:OrganisationUser', 'table:members', 'table:membership_types', 'table:organisation_users',        // V2
],
'voters(member_id) -> members',                                                                            // V3′
```

**V1c and V1d were found by the S1b RED run and added by PO decision on 2026-11-02**, both to the baseline and to S2's scope:
- **V1c:** `Member::voters()` → `hasMany(Voter::class)` (`app/Models/Member.php:82-85`), the code side of V3′.
- **V1d:** the Membership job `CleanupExpiredInvitations` soft-deletes Election-owned `VoterInvitation` records (`…/Jobs/CleanupExpiredInvitations.php:38-43`), a V1-kind write.

## How to extend

- **New Election model or table:** add it to `ELECTION_MODELS` / `ELECTION_TABLES` and add a planted case to `plantedMembershipViolations()`.
- **New detection form:** add a planted case first and see it fail (RED), then extend `tableReferences()` / `modelReferences()` / `replayForeignKeys()`.
- **Never** add a baseline entry without a PO decision. A finding goes to the PO, as V1b, V1c and V1d did.

## Testing

```bash
vendor/bin/phpunit --no-configuration --do-not-cache-result tests/Architecture/MembershipElectionBoundaryTest.php
```

**Result at the time of writing:** OK, 41 tests, 52 assertions. Under PHP 8.3 too, run with the Composer platform check disabled (see pitfalls).

## Pitfalls and known limits

- **Repeated use of an already-baselined target is not detected.** A second `ElectionMembership::…` call in `Member.php` passes (probe B2). Tracking is per file and target, not per occurrence. The PO decided on "per file and per table"; counting occurrences would be brittle under harmless refactors.
- **Scan paths are unchanged (PO exclusion).** Legacy Election and Membership code in `app/Models`, `app/Services`, `app/Http/Controllers`, `app/Policies` and `app/Traits` is not scanned (probes B3, B4). Those sites are recorded as an inventory in PBDIGIT-318 and are neither compliant nor baselined.
- **The SQL-string heuristic** looks only at strings that start like a statement (`select`, `insert`, `update`, `delete`, `with`, `merge`) or contain `(select`. Prose that starts with "Select … from members" would be read as SQL.
- **The foreign-key replay is still an approximation** (guide 01). Only a read-only catalog query can prove the deployed schema (S4).
- **PHP 8.3 and `composer.lock`:** the lock pins Symfony 8.1 packages that require PHP ≥ 8.4.1, so a normal PHP 8.3 run stops at Composer's platform check. This is a project-wide issue recorded separately (PBDIGIT-319), not an S1b defect.

## Traceability

Plan S1b section (`docs/plans/20261102-0339-membership-election-boundary-remediation-plan.md`) → independent review of S1 (2026-11-02) → PO decision "defer S1, authorise S1b" (2026-11-02) → this test → PBDIGIT-318.

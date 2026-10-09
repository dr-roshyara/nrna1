# 03: Slice 1b, PostgreSQL adapter for the Member aggregate

**Story:** `PBDIGIT-260` · **Contract:** `docs/publicdigit/reviews/2026-10-09-membership-slice1b-contract.md` · **Namespace:** `App\Contexts\Membership\Infrastructure\Organisational`

## Purpose

`PostgresMemberRepository` implements the Slice 1a `MemberRepositoryPort` on PostgreSQL. **This is the only database the Membership kernel supports (DB-1).** The domain kernel, the port and the handlers are unchanged. The adapter only stores and retrieves.

## Where it fits

```text
AdmitMemberHandler / EndMembershipHandler → DecideAndAppend      (Application, Slice 1a)
        ↓ MemberRepositoryPort
PostgresMemberRepository                                          (Infrastructure, this slice)
        ↓ org_memberships · org_membership_facts · org_membership_commands
```

## Key files

| File | Role |
|---|---|
| `database/migrations/2026_10_09_100000_create_org_membership_tables.php` | the three tables. **A deployable migration, not yet run on dev or prod** |
| `PostgresMemberRepository.php` | load / findByHolder / create / appliedCommand / append |
| `DateOrdinal.php` | `TimePoint` ⇄ `date` (days since 1970-01-01; EP-Q6 = T1) |
| `InstantMicros.php` | `Instant` ⇄ `timestamptz(6)` (epoch µs, UTC; exact, no float) |
| `ProvenanceJson.php` | `Provenance` ⇄ `jsonb` |
| `UnsupportedMembershipDatabaseDriver.php` | the fail-fast error for non-PostgreSQL connections |

## Design decisions

- **Calendar dates** for `effectiveAt` and the initial term (T1). ∞ is never a date: `initial_term` ∈ {none, until, unlimited}.
- **Tenant-scoped keys:** PK `(organisation_id, id)`, and composite FKs so a fact can never attach to another tenant's Member.
- **The append transaction:** key check → version compare-and-set (row lock) → re-read the key if the compare-and-set failed → fact + key insert. The adapter never retries.
- **Only port outcomes are translated.** A duplicate fact id at the database is an infrastructure error, by design (B-1).
- **No Eloquent models.** Facts are inserted through the query builder only; there is no update or delete path.

## How to use

```php
$repo = new PostgresMemberRepository(DB::connection(), fn (): int => (int) (microtime(true) * 1_000_000));
$handler = new AdmitMemberHandler($repo, $authority);   // as in Slice 1a
```

Production wiring (service provider, authority adapter, HTTP) is **Slice 2**.

## Testing

| Test | Proves |
|---|---|
| `tests/Feature/Contexts/Membership/Organisational/PostgresMemberRepositoryTest` | the shared repository contract (the `MemberRepositoryContract` trait) on PostgreSQL |
| `…/PostgresMemberRepositoryInvariantsTest` | T2–T10: tenants, FK, term states, round trips, monotone recordedAt, rollback, B-1, no mutation, fail-fast |
| `…/PostgresReferenceModelPropertyTest` | handlers + PostgreSQL against the independent model (`ReferencePropertyRun`) |
| `…/PostgresConcurrencyRaceTest` (group `membership-race`) | real concurrent processes (`pcntl_fork`): creation race, competing Ends, identical retries |
| `tests/Architecture/MembershipSlice1bPersistenceBoundaryTest` | inward dependencies; no Eloquent; a PostgreSQL-only migration |

```bash
vendor/bin/phpunit tests/Feature/Contexts/Membership/Organisational/   # uses nrna_test (phpunit.xml)
```

## Pitfalls

- **Test isolation:** `Tests\TestCase` does **not** wrap PostgreSQL tests in transactions. Always create fresh organisations per test, as these tests do.
- **Forked tests:** call `DB::disconnect()` before `pcntl_fork`. Children must open their own connection and end with `SIGKILL`, so that no PHPUnit shutdown runs in them.
- **Never run the migration on the development database in this slice** (CLAUDE.md database rules).

**Traceability:** PBDIGIT-260 · contract §1–§10 · EP-Q6 · DB-1 · implementation review `2026-10-09-membership-slice1b-implementation-review.md`.

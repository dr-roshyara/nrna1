# Election Membership System — Developer Guide

**Branch:** `multitenancy`
**Implemented:** 2026-03-17 | **Last updated:** 2026-03-23
**Status:** Production-ready, 46 tests passing

---

## Contents

| File | What it covers |
|------|---------------|
| [01-overview.md](./01-overview.md) | Why this system exists, the problem it solves, core concepts |
| [02-database-schema.md](./02-database-schema.md) | Tables, columns, constraints, indexes, and the composite FK design |
| [03-models.md](./03-models.md) | `ElectionMembership`, `Election`, and `User` model reference |
| [04-how-to-implement.md](./04-how-to-implement.md) | Step-by-step implementation walkthrough |
| [05-service-layer.md](./05-service-layer.md) | `ElectionVoterService` for bulk operations and reporting |
| [06-caching.md](./06-caching.md) | Cache strategy (Option B — no Redis tags), invalidation hooks |
| [07-testing.md](./07-testing.md) | TDD walkthrough, test structure, how to run and extend tests |
| [08-troubleshooting.md](./08-troubleshooting.md) | Common errors and how to fix them |
| [09-ensure-election-voter-middleware.md](./09-ensure-election-voter-middleware.md) | `EnsureElectionVoter` middleware — implementation, registration, route wiring, testing |
| [10-voter-management-ui.md](./10-voter-management-ui.md) | `Elections/Voters/Index.vue` — layout, props, row actions, assign panel |
| [11-two-person-suspension.md](./11-two-person-suspension.md) | Two-person suspension workflow — state machine, model methods, routes, tests |
| [12-election-policy.md](./12-election-policy.md) | `ElectionPolicy` — who can do what, common 403 causes |

---

## Quick Start

```bash
# Run all migrations (includes suspension columns)
php artisan migrate

# Run core membership tests
php artisan test tests/Unit/Models/ElectionMembershipTest.php --no-coverage

# Run suspension workflow tests
php artisan test \
  tests/Unit/Models/ElectionMembershipSuspensionTest.php \
  tests/Feature/Election/ElectionVoterSuspensionTest.php \
  --no-coverage
```

Expected: **25 + 14 = 39 tests, all green.**

---

## Recent Changes (2026-03-23)

| Change | File |
|--------|------|
| Removed org-wide `VoterController` — all voter management is now election-specific | `routes/organisations.php` |
| Added `ElectionVoterController` with full CRUD + suspension workflow | `app/Http/Controllers/ElectionVoterController.php` |
| Added two-person suspension columns to `election_memberships` | `database/migrations/2026_03_22_213421_*` |
| Updated `ElectionPolicy::manageSettings` to allow org owners/admins | `app/Policies/ElectionPolicy.php` |
| Redesigned voter management page (Electoral Register aesthetic, dark sidebar) | `resources/js/Pages/Elections/Voters/Index.vue` |
| Added per-election voter links on org show page | `resources/js/Pages/Organisations/Show.vue` |

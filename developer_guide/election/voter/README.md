# Election Membership System — Developer Guide

**Branch:** `multitenancy`
**Implemented:** 2026-03-17
**Status:** Production-ready, all 25 tests passing

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

---

## Quick Start

```bash
# Run both migrations
php artisan migrate

# Confirm table exists
php artisan db:show --tables=election_memberships

# Run the full test suite
php artisan test tests/Unit/Models/ElectionMembershipTest.php --no-coverage
```

Expected: **25 tests, 53 assertions, all green.**

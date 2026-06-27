# 01 — Overview

## The Problem This Solves

Diaspora organisations need a way to recognise and incentivise member contributions — volunteer hours, mentoring, event organising, community projects. Without a structured system, contributions go untracked, active members feel invisible, and organisations cannot measure real impact.

The Contribution Points System solves this by providing:

- A transparent, formula-driven scoring engine
- Three distinct tracks (micro, standard, major) for different scales of work
- Verification tiers that balance trust with accountability
- A tamper-proof points ledger for audit trails
- A privacy-respecting leaderboard

---

## Core Concept

A **Contribution** is a record of work a member has done for their organisation. It flows through a lifecycle:

```
  draft → pending → verified → approved → completed
                  ↘ rejected
                  ↘ appealed
```

When a contribution reaches **approved** status, the `ContributionPointsService` calculates points using the `GaneshStandardFormula` and writes an immutable entry to the `points_ledger` table.

---

## System Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                         USER FLOW                                │
│                                                                  │
│  Member fills form ──→ Controller validates ──→ Contribution     │
│  (Create.vue)           (store method)          saved as         │
│                                                  "pending"       │
│                                                                  │
│  Admin reviews ──→ Status → "approved" ──→ ContributionPoints   │
│                                              Service.awardPoints │
│                                                    │             │
│                                                    ▼             │
│                                           GaneshStandardFormula  │
│                                           .calculate()           │
│                                                    │             │
│                                                    ▼             │
│                                           PointsLedger::create() │
│                                           (immutable audit row)  │
└─────────────────────────────────────────────────────────────────┘
```

---

## File Map

### Backend

| File | Purpose |
|------|---------|
| `app/Services/GaneshStandardFormula.php` | Pure scoring engine — no dependencies, no side effects |
| `app/Services/ContributionPointsService.php` | Orchestrates point calculation, weekly cap enforcement, and ledger writes |
| `app/Services/LeaderboardService.php` | Aggregates points per user with privacy filtering |
| `app/Models/Contribution.php` | Eloquent model with UUID, soft-delete, tenant scope |
| `app/Models/PointsLedger.php` | Immutable audit ledger — no updates, no deletes |
| `app/Http/Controllers/Contribution/ContributionController.php` | 5 actions: index, create, store, show, leaderboard |

### Migration

| File | Creates |
|------|---------|
| `database/migrations/2026_04_11_000001_create_contributions_tables.php` | `contributions`, `points_ledger` tables; adds `leaderboard_visibility` to `users` |

### Frontend (Vue 3 + Inertia 2.0)

| File | Page |
|------|------|
| `resources/js/Pages/Contributions/Create.vue` | Contribution form with live points preview |
| `resources/js/Pages/Contributions/Index.vue` | Paginated list of user's contributions |
| `resources/js/Pages/Contributions/Show.vue` | Single contribution detail with ledger entries |
| `resources/js/Pages/Contributions/Leaderboard.vue` | Organisation leaderboard with podium and ranking table |

### Tests

| File | Tests | Assertions |
|------|-------|------------|
| `tests/Feature/Contribution/PointsCalculatorTest.php` | 6 | Formula correctness |
| `tests/Feature/Contribution/ContributionPointsServiceTest.php` | 5 | Ledger writes, weekly caps |
| `tests/Feature/Contribution/LeaderboardServiceTest.php` | 5 | Privacy filtering, org scoping |

---

## Routes

All routes are nested under the organisation prefix and require authentication.

| Method | URI | Name | Action |
|--------|-----|------|--------|
| GET | `organisations/{organisation}/contributions` | `organisations.contributions.index` | List user's contributions |
| GET | `organisations/{organisation}/contributions/create` | `organisations.contributions.create` | Show form |
| POST | `organisations/{organisation}/contributions` | `organisations.contributions.store` | Save contribution |
| GET | `organisations/{organisation}/contributions/{contribution}` | `organisations.contributions.show` | View detail |
| GET | `organisations/{organisation}/leaderboard` | `organisations.leaderboard` | Organisation leaderboard |

---

## Quick Start

```bash
# Run migration
php artisan migrate

# Run all 16 tests
php artisan test tests/Feature/Contribution/ --no-coverage

# Build frontend
npm run build
```

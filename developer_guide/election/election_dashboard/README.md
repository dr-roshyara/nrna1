# Election Dashboard — Developer Guide

**Branch:** `multitenancy`
**Implemented:** 2026-03-22
**Status:** Production-ready

---

## What This Guide Covers

The election dashboard is the voter-facing experience at `/organisations/{slug}`. It shows active elections, the voter's current status in each one, and provides a **Vote Now** button that takes the voter into the real election flow.

This guide also covers the underlying architectural change that made it possible: promoting `ElectionMembership.has_voted` to the **single source of truth** for per-election voting status, replacing scattered `VoterSlug.status='voted'` queries.

---

## Contents

| File | What it covers |
|------|---------------|
| [01-voter-ballot-section.md](./01-voter-ballot-section.md) | The "Voting Open" UI section — visibility, state logic, CTA |
| [02-voter-memberships-prop.md](./02-voter-memberships-prop.md) | How the controller builds and passes `voterMemberships` to the frontend |
| [03-has-voted-source-of-truth.md](./03-has-voted-source-of-truth.md) | `ElectionMembership.has_voted` as the authoritative voting status — migration, model, consumers |
| [04-testing.md](./04-testing.md) | Test files, what they cover, how to run them |

---

## Quick Start

```bash
# Confirm migrations ran
php artisan migrate:status | grep "2026_03_22"

# Run all affected tests
php artisan test \
  tests/Unit/Models/ElectionMembershipTest.php \
  tests/Feature/Election/ElectionShowControllerTest.php \
  tests/Feature/Services/DashboardResolverElectionPriorityTest.php \
  --no-coverage
```

Expected: **57 tests, 178 assertions, all green.**

---

## The Problem This Solves

Before this work:

1. The "Voting Open" section on the org page was **hidden from owners and officers** (`v-if="!canManage && !isOfficer"`)
2. Even when visible to voters, there was **no "Vote Now" button** — only a status badge
3. `hasVoted` was determined by querying `voter_slugs.status='voted'` — a session-tracking table, not the correct domain model for a permanent fact

After this work:

1. **All users** see active elections regardless of role — "Not a voter" badge for non-voters, "Vote Now" for eligible voters
2. Each election card has a direct **Vote Now → `/elections/{slug}`** link
3. `ElectionMembership.has_voted` is the authoritative per-election voting status

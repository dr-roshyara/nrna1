# Real Election — Developer Guide

**Branch:** `multitenancy`
**Implemented:** 2026-03-19
**Status:** Production-ready, all tests passing

---

## Contents

| File | What it covers |
|------|----------------|
| [01-voter-security-layers.md](./01-voter-security-layers.md) | The 9-layer voter security architecture — what each layer does and why |
| [02-layer0-membership-check.md](./02-layer0-membership-check.md) | Layer 0 implementation: middleware + controller defense-in-depth |
| [03-race-condition-protection.md](./03-race-condition-protection.md) | Fresh DB checks, transaction safety, and row-lock on voter removal |
| [04-testing.md](./04-testing.md) | Integration test suite, TDD approach, and how to extend |

---

## Quick Start

```bash
# Run integration tests
php artisan test tests/Feature/Integration/VotingMembershipIntegrationTest.php --no-coverage

# Run full voter security suite
php artisan test tests/Feature/Integration/VotingMembershipIntegrationTest.php \
                 tests/Feature/Middleware/EnsureElectionVoterTest.php \
                 tests/Feature/ElectionVoterManagementTest.php \
                 tests/Unit/Models/ElectionMembershipTest.php --no-coverage
```

Expected: **57 tests, all green.**

---

## The Core Problem This Solves

Before this work, any authenticated user who belonged to an organisation could enter the
voting flow for any real election — even if they were not explicitly assigned as a voter.

The only guard was the legacy `VoteEligibility` middleware which checked a `can_vote` flag
on the `users` table — a blunt, per-user flag with no per-election granularity.

This work adds **Layer 0 — Election Membership Check**: the user must have an active row
in `election_memberships` for the specific election they are trying to vote in.

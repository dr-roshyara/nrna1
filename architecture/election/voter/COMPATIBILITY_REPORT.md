# Compatibility Report: Election Membership System
**Date:** 2026-03-17
**Architecture Ref:** `20260317_2208_Voter_model.md`
**Auditor:** Claude Code CLI

---

## Status: [INCOMPATIBLE] — 2 Blocking Issues Found

Remediation is required before implementation can proceed. All blockers have clear, low-risk fixes.

---

## ✅ Compatible Elements

| Component | Status | Notes |
|-----------|--------|-------|
| `users` table | ✅ Compatible | UUID PK, no `is_voter` column in production |
| `organisations` table | ✅ Compatible | UUID PK, `type` field present |
| `user_organisation_roles` table | ✅ Compatible | Has `UNIQUE(user_id, organisation_id)` — satisfies composite FK reference requirement |
| `BelongsToTenant` trait | ✅ Compatible | Auto-scopes all queries by `organisation_id` |
| `Election` model | ✅ Compatible | UUID, SoftDeletes, BelongsToTenant — all required |
| `User` model | ✅ Compatible | UUID, HasUuids, all required relationships addable |
| `ElectionFactory` | ✅ Compatible | Exists with `forOrganisation()`, `demo()`, `real()`, `active()` states |
| `OrganisationFactory` | ✅ Compatible | Exists with `tenant()` state |
| Queue/Jobs | ✅ Compatible | Already used by `ProcessMemberImportJob` |
| `is_voter` migration | ✅ N/A — Skip | `is_voter` does not exist in current production schema — data migration step from the spec is **not needed** |

---

## ❌ Blocking Issues

### BLOCKER 1: Elections table missing composite unique key `(id, organisation_id)`

**Why it blocks:**
The `election_memberships` table requires a composite foreign key:
```sql
FOREIGN KEY (election_id, organisation_id) REFERENCES elections(id, organisation_id)
```
MySQL requires the referenced columns to form an existing key. Currently, `elections` only has `id` as PK and `slug` as unique. There is **no key on `(id, organisation_id)`**, so MySQL will refuse to create the composite FK constraint.

**Current state:**
```sql
elections:
  PRIMARY KEY (id)
  UNIQUE KEY (slug)          ← only unique constraint
  -- MISSING: UNIQUE KEY (id, organisation_id)
```

**Remediation:**
```bash
php artisan make:migration add_composite_unique_to_elections_table
```
Migration adds: `$table->unique(['id', 'organisation_id'], 'unique_org_election');`

**Risk:** Low. Adding a unique index to a combination of PK + existing column is a safe, non-destructive operation.

---

### BLOCKER 2: Cache driver is `file` — does not support `Cache::tags()`

**Why it blocks:**
The architecture spec uses tag-based cache invalidation:
```php
Cache::tags(["election.{$election->id}"])->flush();
Cache::tags(["election.{$electionId}"])->remember('voter_count', 3600, fn() => ...);
```
The current `.env` has `CACHE_DRIVER=file`. Laravel's file driver **does not support cache tags** — this will throw a `BadMethodCallException` at runtime.

**Current state:**
```
CACHE_DRIVER=file   ← does not support tags
REDIS_HOST=127.0.0.1, REDIS_PORT=6379 configured but not active
```

**Remediation (two options — choose one):**

**Option A (Recommended):** Switch to Redis (already configured):
```env
CACHE_DRIVER=redis
```
```bash
php artisan cache:clear
```

**Option B (No infrastructure change):** Implement caching without tags — use explicit key deletion instead:
```php
// Instead of Cache::tags(["election.{$id}"])->flush()
Cache::forget("election.{$id}.voter_count");
Cache::forget("election.{$id}.voter_stats");
```
All cache calls in the implementation will use `Cache::remember(key, ttl, fn)` and `Cache::forget(key)` — no tags.

**Risk:** Low either way. Option A requires Redis to be running locally. Option B requires no infrastructure change.

---

## ⚠️ Non-Blocking Findings

### 1. No `ElectionPolicy.php`
**Assessment:** The architecture spec requires: *"All user-facing operations must be authorized (Policies)"*.
No `app/Policies/ElectionPolicy.php` exists. Current auth is done via middleware (`EnsureCommitteeMember`, `EnsureOrganisationMember`).
**Decision required:** Should we create an `ElectionPolicy` as part of this implementation, or defer to a separate task?
**Recommendation:** Defer — the architecture spec's primary deliverable is the membership data model, not the policy layer. Middleware-based auth already protects election routes.

### 2. `VoterRegistration` model — potential overlap
**Assessment:** The existing system has a `User → VoterRegistration → Voter → VoterSlug` hierarchy. The new `ElectionMembership` is a parallel system. Both track voter eligibility per election.
**Decision required:** Will `ElectionMembership` **replace** `VoterRegistration` eventually, or **coexist**?
**Recommendation:** Coexist for now. Implement `ElectionMembership` as additive. Do not remove or modify `VoterRegistration`.

### 3. `is_voter` data migration step
**Assessment:** The spec includes a migration script to move `users.is_voter = 1` records to `election_memberships`. The current production schema has **no `is_voter` column** (it only exists in `old_migrations/`). This step is a no-op and can be **skipped entirely**.

---

## Remediation Plan

| Step | Action | Risk | Time |
|------|--------|------|------|
| 1 | Decide on cache strategy (Redis vs no-tags) | None | 2 min |
| 2 | Add composite unique key migration to `elections` | Low | 5 min |
| 3 | Create `election_memberships` migration | Low | 10 min |
| 4 | Create `ElectionMembership` model | Low | 15 min |
| 5 | Add relationships to `Election.php` and `User.php` | Low | 10 min |
| 6 | Create `ElectionVoterService` | Low | 20 min |
| 7 | Create `ValidateElectionMemberships` command | Low | 10 min |
| 8 | Write tests + run | Low | 30 min |

**Total estimated effort:** ~1.5 hours
**Rollback plan:** `php artisan migrate:rollback --step=2` reverts both new migrations cleanly.

---

## Recommendation

**Proceed with remediation.** The two blockers are straightforward:
1. One-line migration fix for elections composite key
2. Cache strategy choice (recommend: no tags, use explicit key deletion — no Redis dependency)

**Awaiting confirmation on:**
1. Cache strategy: **Option A (Redis)** or **Option B (no tags)**?
2. `ElectionPolicy`: Implement now or defer?

Once confirmed, implementation can begin immediately following TDD.

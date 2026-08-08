# PBDIGIT-62 — The "already voted" exclusion never fires at login: a tenant scope defeats it

**Type:** Defect (production) · **Epic:** `PBDIGIT-EPIC-05` Voting · **Created:** 2026-08-07
**Found by:** the Election post-migration impact assessment, refusing to dismiss two failing tests as merely "pre-existing"

| | |
|---|---|
| **Status** | ✅ **REPAIRED 2026-08-07** — authorised by the Product Owner as a bounded fix. RED→GREEN proven; **27 → 25** failures in the routing suites (2 fixed, 0 broken) |
| **Customer impact** | 🔴 **A voter who has already voted is still routed to the ballot after login**, and still counted as having an active election |
| **Nature** | **Pre-existing production defect. NOT caused by the lifecycle migration** — proven by identical failure counts with the migration applied and reverted |
| **The tests were right** | `DashboardResolverElectionPriorityTest::user_who_already_voted_counts_zero` has been reporting this correctly all along |

## The defect

Both Election Entry Resolution consumers exclude voters who have already voted:

```php
Election::withoutGlobalScopes()              // ← Election's scopes removed
    ->whereDoesntHave('voterSlugs', fn ($q) => $q->where('user_id', …)->where('status','voted'))
```

`withoutGlobalScopes()` applies to the **Election** query. The `whereDoesntHave` subquery runs against **`VoterSlug`** / **`ElectionMembership`**, and **those models keep their own global scopes** — both carry `tenant`.

**`BelongsToTenant` does not no-op without context.** With no `TenantContext` and no session (**exactly the login-time condition**), it falls back to the **platform organisation's id** and applies:

```sql
election_memberships.organisation_id = '<platform-org-id>'
```

which can never match a tenant's rows. **The subquery returns nothing, so `whereDoesntHave` is trivially true, and the exclusion never fires.**

The irony is recorded in the code itself: both methods call `withoutGlobalScopes()` with the comment *"bypasses BelongsToTenant which requires session context — not set yet when DashboardResolver runs at login time."* **The author knew the hazard and defeated it only on the outer query.**

| Site | Exclusion | Model | Scope |
|---|---|---|---|
| `User::getActiveElection()` `:1312` | `voterSlugs` where `status='voted'` | `VoterSlug` | **`tenant`** ✗ |
| `User::countActiveElections()` `:1354` | `memberships` where `has_voted` | `ElectionMembership` | **`tenant`** ✗ |

## Evidence

* `ElectionMembership` global scopes: `tenant`, `SoftDeletingScope`. `VoterSlug`: `SoftDeletingScope`, `tenant`.
* `BelongsToTenant::bootBelongsToTenant()` — `$orgId = TenantContext::get() ?? session(...)`, then falls back to the platform org id when null; the scope is always applied.
* **Not a migration regression:** the Election suites fail **146 tests both with the lifecycle migration applied and with it reverted** (identical), so this predates `PBDIGIT-48`/`58`.

## ✅ Repair record (2026-08-07)

**Reproduced first, in the generated SQL** — under the login-time condition (no `TenantContext`, no session), the subquery carried:

```sql
and "election_memberships"."organisation_id" is null
```

which never matches a tenant's rows. `not exists (…)` was therefore always true — **the guard failed open**.

### Production change — `app/Models/User.php`, two closures

```php
->whereDoesntHave('voterSlugs', function ($query) {
    $query->withoutGlobalScope('tenant')      // ← the fix
        ->where('user_id', $this->id)
        ->where('status', 'voted');
})
```

**`withoutGlobalScope('tenant')`, not `withoutGlobalScopes()`** — deliberately narrower than the pattern sketched in the authorisation. The blanket form would also drop `SoftDeletingScope`, making a **soft-deleted slug count as a vote**. Only the tenant scope is lifted; every other scope, and every other consumer of these models, is untouched.

**Why lifting it here is correct rather than a hole:** login-time Election resolution is *intentionally* cross-tenant — the outer query already declares that with `withoutGlobalScopes()` and says so in its comment. The exclusion must be evaluated in the same span as the thing it excludes from.

### Tests

* **New:** `HasActiveElectionTest::test_already_voted_is_excluded_when_no_tenant_context_is_set` — pins the login-time condition explicitly (clears `TenantContext` **and** session), records the vote on **both** representations, and asserts a **positive control first** (before the vote, the election *is* resolvable) so the test cannot pass for the wrong reason. **Proven RED against pre-fix code** (`Failed asserting that 1 matches expected 0`), GREEN after.
* **Rewritten:** `future_active_election_is_counted` → `future_election_is_not_counted_as_active_for_voting_routing`. See §Stale test below.

### Results

| Suite set | Before | After |
|---|---|---|
| The four routing/entry suites | **27 failed** (42 assertions) | **25 failed** (46 assertions) |

**2 fixed, 0 broken.** The remaining 25 are pre-existing failures in `DashboardResolverTest` / `DashboardResolverPriorityTest`, part of the 146-failure Election estate; **not touched**.

### Audit of the pattern (authorised step, no fixes applied)

Every `withoutGlobalScopes()` + `whereHas`/`whereDoesntHave` combination in `app/`:

| Class | Finding |
|---|---|
| **D — already correct** | 9 sites already lift scopes inside the closure (`Election.php`, `Code.php`, `ElectionManagementController`, `NewsletterService`, `MembershipApplicationController`) |
| **A — safe direction** | `EloquentMemberRepository:104` does not lift, but it is a **`whereHas`** — a scoped-to-nothing positive constraint fails **closed** (returns fewer rows), never open. Outside Election; **not changed** |
| **B — fail-open** | **None remaining.** The two sites in this ticket were the only ones |

> **The asymmetry is the lesson:** `whereHas` scoped to nothing fails **closed** (safe); `whereDoesntHave` scoped to nothing fails **open** (the guard silently permits). Only the negative form is dangerous.

## §Stale test — `future_active_election_is_counted`

**Production code was not changed to satisfy it.** The correct assertion was derived from the existing contract, not from the old expectation:

| Source | Verdict on a `+5 days` election |
|---|---|
| `countActiveElections()` SQL | `start_date <= now()` → excluded (**and always was** — the test never passed) |
| `ElectionLifecycle` | window not open → `ReadyForVoting`/`Draft` → `canVote()` false → excluded |
| Routing contract (`0 → skip`, `1 → election dashboard`) | `0` prevents routing to an unusable ballot — **the old test's own stated goal** |

Renamed to `future_election_is_not_counted_as_active_for_voting_routing`, asserting **0**, with the reasoning in the docblock and **no mention of `status='active'` as a cause**. Coverage is not duplicated elsewhere: the existing date-range test covers `getActiveElection()`, not `countActiveElections()`.

## Scope (original, now delivered)

Make the exclusion subqueries scope-free, matching the intent the outer query already declares. **Do not remove the tenant scope from the models** — that protects every other consumer. The narrow fix is to bypass scopes *inside* the `whereDoesntHave` closures.

**Out of scope:** the tenant-scope design itself, `TenantContext` semantics, and anything in `PBDIGIT-59`.

## Acceptance criteria

- [ ] `user_who_already_voted_counts_zero` passes **without weakening the assertion**
- [ ] A voter who has voted is **not** routed to the ballot at login — browser-verified with a real election
- [ ] A test pins the login-time condition explicitly (**no session tenant context**), since that is what makes the defect invisible in ordinary requests
- [ ] The audit sweep below is completed

## ⚠️ The wider question this raises

**`withoutGlobalScopes()` on an outer query does not reach relationship subqueries.** Anywhere the codebase combines `withoutGlobalScopes()` with `whereHas`/`whereDoesntHave`/`with` over a tenant-scoped model, the inner constraint is silently scoped — and a `whereDoesntHave` that returns nothing **fails open**. That is the dangerous direction: the guard silently permits.

**Recommended:** audit those combinations as part of this ticket.

## Traceability

Found 2026-08-07 during the Election post-migration impact assessment · `app/Models/User.php:1307-1316`, `:1346-1358` · `app/Traits/BelongsToTenant.php` · `tests/Feature/Services/DashboardResolverElectionPriorityTest.php:131`

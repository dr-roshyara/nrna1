# PBDIGIT-62 — The "already voted" exclusion never fires at login: a tenant scope defeats it

**Type:** Defect (production) · **Epic:** `PBDIGIT-EPIC-05` Voting · **Created:** 2026-08-07
**Found by:** the Election post-migration impact assessment, refusing to dismiss two failing tests as merely "pre-existing"

| | |
|---|---|
| **Status** | 🔴 **OPEN — not authorised.** Analysis complete; no code changed |
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

## Scope (when authorised)

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

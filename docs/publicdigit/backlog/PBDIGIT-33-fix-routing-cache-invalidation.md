# PBDIGIT-33 — Fix routing-cache invalidation (observer never attached)

**Type:** Bug (technical defect) · **Epic:** cross-cutting (affects `PBDIGIT-EPIC-01`)
**Created:** 2026-08-06 · **Origin:** `PBDIGIT-31` RD-12 / §10
**Status:** `FIXED — AWAITING RUNTIME VERIFICATION`

| | |
|---|---|
| **Business decision required** | **No** — the business intent is already visible in the code that was written |
| **Blocked by** | **nothing.** Deliberately separated from `PBDIGIT-32`, which is blocked on B2–B9 |
| **Why separate** | this is an implementation defect, not business behaviour. It needs no answer about organisation selection, create/join, or the Working Organisation concept |

---

## Customer impact

**After joining, leaving, or changing an organisation, the platform can send the user to where they used to belong** — for up to 5 minutes — because the routing decision cached at login is never cleared.

## The defect

`UserOrganisationObserver` was written for exactly this purpose. Its docblock names the key: *"`dashboard_resolution:{user_id}` — Main routing cache"*. It handles `created` · `updated` · `deleted` · `restored` · `forceDeleted` on `UserOrganisationRole`, and clears nine keys.

**It was imported and never attached.**

| Fact | Evidence |
|---|---|
| The observer exists and clears nine keys incl. `dashboard_resolution:{userId}` | `app/Observers/UserOrganisationObserver.php:27,47,68,88,108,136-155` |
| It is **imported** into the service provider | `app/Providers/AppServiceProvider.php:18` |
| **No `::observe()` call exists anywhere** in `app/` or `bootstrap/` | repo-wide search |
| The model has no `booted()` and no `#[ObservedBy]` | `app/Models/UserOrganisationRole.php` |
| A *neighbouring* cache **is** cleared on organisation creation — the tenant one, not the routing one | `OrganisationController:362` |
| The routing decision is cached for **300 s** | `DashboardResolver:341-346` · `config/login-routing.php:20` |

## ⚠️ The second defect — found while fixing the first

**Attaching the observer as written would have caused an incident.**

`invalidateUserCaches(int $userId)` is typed **`int`** (`:136`), and its docblock says `@param int $userId`. But `user_organisation_roles.user_id` is a **UUID**:

```php
$table->uuid('user_id');          // migration 2026_03_05_000003:13
```

The file has **no `declare(strict_types=1)`**, so PHP 8 coercive typing applies to a UUID string:

| UUID first character | PHP behaviour | Consequence |
|---|---|---|
| `a`–`f` (~37 %) | non-numeric string → **`TypeError`** | **every** role create/update/delete throws — **organisation creation breaks** (`OrganisationController:324`) |
| `0`–`9` (~63 %) | leading-numeric string → coerced to a small int, with a warning | clears `dashboard_resolution:9` instead of the user's key — **invalidation that looks like it works and does not** |

**So the remedy is two changes, not the one line originally recorded in `PBDIGIT-31` I-2.**

## The fix

1. **Attach the observer** — `AppServiceProvider::boot()`:
   ```php
   UserOrganisationRole::observe(UserOrganisationObserver::class);
   ```
   *(the `use` statement was already present at `:18`)*
2. **Correct the identifier type** — `int $userId` → `string $userId`, and the docblock, matching the UUID schema.

**Nothing else changed.** The observer's own logic — nine `Cache::forget()` calls and structured logging to the `login` channel — is untouched. It performs **no data writes and no notifications**, which is what makes attaching it safe once the type is right.

## Verification

| Check | Result |
|---|---|
| `php -l` on both changed files | ✅ no syntax errors |
| Observer performs only cache-clearing + logging (no writes, no notifications) | ✅ full file read before attaching |
| UUID now accepted by the signature | ✅ `string $userId` |
| **Runtime: create an organisation → confirm no `TypeError` and the routing cache is cleared** | ⬜ **needs a database** |
| **Runtime: user in 2 organisations → change membership → confirm next login re-resolves** | ⬜ **needs a browser** |

**Status is `AWAITING RUNTIME VERIFICATION`, not `VERIFIED`** — activating a previously dormant observer changes behaviour on a write path, and that deserves an observed test rather than an inference.

## Acceptance criteria

* [x] The observer is attached to `UserOrganisationRole`.
* [x] `invalidateUserCaches` accepts the UUID identifier the schema actually uses.
* [x] No change to the observer's logic.
* [ ] Creating an organisation still succeeds (no `TypeError`) — **runtime**.
* [ ] After a membership change, the next login re-resolves rather than reusing the cached destination — **runtime**.
* [ ] No business rule was implemented here — B2–B9 remain open, `PBDIGIT-32` remains blocked.

---

**Traceability:** `PBDIGIT-31` RD-12 · §10 · I-2 · `app/Observers/UserOrganisationObserver.php:136` · `app/Providers/AppServiceProvider.php:18` · `app/Models/UserOrganisationRole.php` · `database/migrations/2026_03_05_000003_create_user_organisation_roles_table.php:13` · `app/Services/DashboardResolver.php:341-346` · `config/login-routing.php:20,38` · `app/Http/Controllers/OrganisationController.php:324,362`

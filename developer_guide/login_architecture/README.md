# Login Architecture Guide

This folder contains comprehensive documentation about the login system, multi-tenant routing, pivot table management, and debugging strategies.

## 📚 Documentation Structure

### Core Concepts
- **[01-MULTI_TENANCY_OVERVIEW.md](01-MULTI_TENANCY_OVERVIEW.md)** - Foundation of tenant isolation and user-organisation relationships
- **[02-USER_MODEL_LIFECYCLE.md](02-USER_MODEL_LIFECYCLE.md)** - Complete User model behavior and bootstrap hooks
- **[03-PIVOT_TABLE_SYSTEM.md](03-PIVOT_TABLE_SYSTEM.md)** - user_organisation_roles table structure and invariants

### Login Flow & Routing
- **[04-LOGIN_PRIORITY_SYSTEM.md](04-LOGIN_PRIORITY_SYSTEM.md)** - 8-level priority routing in DashboardResolver (CRITICAL)
- **[05-POST_LOGIN_ROUTING.md](05-POST_LOGIN_ROUTING.md)** - How users are routed after authentication
- **[06-EFFECTIVE_ORGANISATION_ID.md](06-EFFECTIVE_ORGANISATION_ID.md)** - getEffectiveOrganisationId() logic and fallbacks

### Debugging & Problem Solving
- **[07-DEBUGGING_GUIDE.md](07-DEBUGGING_GUIDE.md)** - Step-by-step debugging methodology
- **[08-COMMON_ERRORS.md](08-COMMON_ERRORS.md)** - Error symptoms and root causes
- **[09-LOG_ANALYSIS.md](09-LOG_ANALYSIS.md)** - Reading logs for diagnosis

## 🎯 Quick Reference

### The 403 Error Problem
**Symptom:** User gets "Sie haben keinen Zugang auf diese Organisation" after registration/login

**Common Causes:**
1. Missing pivot record in `user_organisation_roles`
2. User has `organisation_id > 1` but no pivot for that org
3. EnsureOrganisationMember middleware validation fails

**Solution:** Follow [07-DEBUGGING_GUIDE.md](07-DEBUGGING_GUIDE.md#diagnosing-403-errors)

### The Wrong Redirect Problem
**Symptom:** Platform user (org_id=1) redirected to `/organisations/publicdigit` instead of `/dashboard/welcome`

**Root Cause:** DashboardResolver priority logic using wrong organisation ID

**Solution:** Follow [04-LOGIN_PRIORITY_SYSTEM.md](04-LOGIN_PRIORITY_SYSTEM.md#priority-1-to-4-normal-routing)

## 🔐 Critical Invariants (DO NOT BREAK)

Every user MUST satisfy these conditions:

```
1. Every user.organisation_id must have a corresponding pivot in user_organisation_roles
   OR organisation_id must be NULL (not yet assigned to org)

2. If user.organisation_id > 1, there MUST be a pivot with:
   - user_id = user.id
   - organisation_id = user.organisation_id
   - role = 'member' (or higher)

3. Every user MUST have at least one pivot for organisation_id = 1 (platform)
   after successful registration/creation

4. The getEffectiveOrganisationId() method MUST fall back to 1 if
   user.organisation_id has no valid pivot
```

## 📊 Key Files

| File | Purpose | Edited By |
|------|---------|-----------|
| `app/Models/User.php` | User lifecycle, pivot fallback | TDD Implementation |
| `app/Services/DashboardResolver.php` | 8-level priority routing | TDD Implementation |
| `app/Http/Controllers/Auth/LoginController.php` | Login entry point | TDD Implementation |
| `app/Http/Responses/LoginResponse.php` | Fortify login response | TDD Implementation |
| `app/Http/Controllers/Auth/RegisterController.php` | Registration pivot creation | TDD Implementation |
| `database/migrations/2026_03_04_163924_fix_user_organisation_ids.php` | Fix existing users | Migration |

## 🧪 Testing

All functionality is covered by 10 TDD tests:

```bash
php artisan test tests/Feature/Auth/RegistrationAndLoginTest.php --no-coverage
```

**Status:** ✅ All 10 tests passing

## 💡 When to Use This Guide

- **You're seeing a 403 error:** Start with [08-COMMON_ERRORS.md](08-COMMON_ERRORS.md)
- **You need to debug login behavior:** Use [07-DEBUGGING_GUIDE.md](07-DEBUGGING_GUIDE.md)
- **You want to understand the system:** Read in order:
  1. [01-MULTI_TENANCY_OVERVIEW.md](01-MULTI_TENANCY_OVERVIEW.md)
  2. [02-USER_MODEL_LIFECYCLE.md](02-USER_MODEL_LIFECYCLE.md)
  3. [03-PIVOT_TABLE_SYSTEM.md](03-PIVOT_TABLE_SYSTEM.md)
  4. [04-LOGIN_PRIORITY_SYSTEM.md](04-LOGIN_PRIORITY_SYSTEM.md)
- **You're adding new login logic:** Read [05-POST_LOGIN_ROUTING.md](05-POST_LOGIN_ROUTING.md) first

## 🚨 If Things Break

1. **Check logs first:** `storage/logs/` and `storage/logs/laravel-errors.log`
2. **Run tests:** `php artisan test tests/Feature/Auth/`
3. **Check database state:** Pivot records, organisation_id values
4. **Follow [07-DEBUGGING_GUIDE.md](07-DEBUGGING_GUIDE.md) step-by-step**

---

**Last Updated:** 2026-03-04
**Status:** Complete - All 10 tests passing
**Coverage:** Registration → Login → Dashboard routing with 100% tenant isolation

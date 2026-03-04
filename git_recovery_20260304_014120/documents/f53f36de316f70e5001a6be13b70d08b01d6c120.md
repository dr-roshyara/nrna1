# 📘 Developer Guide - Authentication & Dashboard System

## Overview

This guide documents the authentication and dashboard routing system implemented in Public Digit, including recent security fixes and architectural decisions.

## What Was Developed

### Phase 1: Email Verification Security Fix

**Problem**: Unverified users could bypass email verification and access protected routes, resulting in 500 errors.

**Solution**:
- Added `verified` middleware to all protected routes
- Added email verification check in `LoginController`
- Created comprehensive test suite (12 tests, 38 assertions)

**Files Modified**:
- `routes/web.php` - Added `verified` middleware
- `app/Http/Controllers/Auth/LoginController.php` - Email verification check
- `tests/Feature/Auth/VerifiedMiddlewareTest.php` - NEW

### Phase 2: Logout Session Invalidation Fix

**Problem**: Frontend logout wasn't properly communicating with backend, leaving sessions active.

**Solution**:
- Fixed Vue components to use `this.$inertia.post(route('logout'))` instead of `window.location.href`
- Added error handling and fallback redirects
- Created comprehensive logout test suite (10 tests, 17 assertions)

**Files Modified**:
- `resources/js/Components/Jetstream/PublicDigitHeader.vue` - Use Inertia POST
- `resources/js/Components/Jetstream/NrnaHeader.vue` - Use Inertia POST
- `tests/Feature/Auth/LogoutTest.php` - NEW

---

## Key Components

### 1. **LoginResponse** (app/Http/Responses/LoginResponse.php)
Advanced 3-level fallback system for handling post-login routing with caching, analytics, and emergency fallbacks.

### 2. **DashboardResolver** (app/Services/DashboardResolver.php)
Intelligent router that determines which dashboard each user should access based on roles, organisations, and voting status.

### 3. **LoginController** (app/Http/Controllers/Auth/LoginController.php)
Custom authentication controller with email verification enforcement and organisation routing.

### 4. **Route Middleware**
- `verified` - Ensures email verification before accessing protected routes
- `auth` - Ensures user authentication
- `no.cache` - Prevents caching of dynamic dashboard content

---

## Architecture Overview

```
┌─────────────────────────────────────────────────────────┐
│                   User Login Request                     │
└────────────────────────┬────────────────────────────────┘
                         │
                         ▼
        ┌────────────────────────────────┐
        │   LoginController::store()     │
        │  (validates credentials)       │
        └────────────┬───────────────────┘
                     │
          ✅ Check email_verified_at
             (NEW SECURITY FIX)
                     │
        ┌────────────▼───────────────────┐
        │  Route to organisation or      │
        │  dashboard based on user state │
        └────────────┬───────────────────┘
                     │
      ┌──────────────┴──────────────┐
      │                             │
      ▼                             ▼
   (Verified)                  (Unverified)
      │                             │
      ▼                             ▼
DashboardResolver          verification.notice
      │
      ├─ Active voting? → Voting dashboard
      ├─ First-time? → Welcome page
      ├─ Multiple roles? → Role selection
      ├─ Single role? → Role-specific dashboard
      └─ Fallback → Platform dashboard
```

---

## Test Suites Created

### Email Verification Tests (VerifiedMiddlewareTest.php)
- 12 test cases
- 38 assertions
- Coverage: Route protection, middleware enforcement, login flow

### Logout Tests (LogoutTest.php)
- 10 test cases
- 17 assertions
- Coverage: Session invalidation, CSRF protection, error handling

---

## Documentation Map

1. **00_OVERVIEW.md** (this file) - High-level overview
2. **01_LOGIN_RESPONSE_ARCHITECTURE.md** - LoginResponse detailed documentation
3. **02_DASHBOARD_RESOLVER_ARCHITECTURE.md** - DashboardResolver detailed documentation
4. **03_AUTHENTICATION_FLOW.md** - Complete authentication flow diagrams
5. **04_TEST_SUITE_GUIDE.md** - How to run and understand tests
6. **05_SECURITY_GUIDELINES.md** - Security best practices
7. **06_TROUBLESHOOTING.md** - Common issues and solutions

---

## Quick Start for Developers

### Understanding the Login Flow
1. User submits login form
2. `LoginController::store()` validates credentials
3. **NEW**: Check email verification (line 69-73)
4. Route based on organisation or state
5. `DashboardResolver` determines final destination

### Understanding Dashboard Routing
1. User reaches dashboard route
2. Middleware checks: `auth` and `verified`
3. `DashboardResolver::resolve()` runs
4. Checks in priority order:
   - Active voting session
   - First-time user (needs onboarding)
   - Multiple roles (needs role selection)
   - Single role (direct to that dashboard)
   - Legacy fallback

### Running Tests
```bash
# Email verification tests
php artisan test tests/Feature/Auth/VerifiedMiddlewareTest.php

# Logout tests
php artisan test tests/Feature/Auth/LogoutTest.php

# All authentication tests
php artisan test tests/Feature/Auth/
```

---

## Security Highlights

✅ **Email Verification Enforcement**
- Middleware-level protection
- LoginController validation
- No unverified users can access protected routes

✅ **Session Management**
- Proper POST-based logout
- CSRF protection via Inertia.js
- Session invalidation on both frontend and server

✅ **Tenant Isolation**
- Organization-based data scoping
- Platform vs. tenant database separation
- No cross-tenant access

✅ **Multi-role Support**
- Admin, Commission, and Voter roles
- Role-specific dashboards
- Role selection for multi-role users

---

## Architecture Decisions

### Why 3-Level Fallback in LoginResponse?
1. **Level 1 (Normal)**: Standard DashboardResolver logic
2. **Level 2 (Emergency)**: Reduced-load fallback dashboard
3. **Level 3 (Fallback)**: Static HTML (works even if DB is down)

This ensures users can still access dashboards even during partial outages.

### Why Separate LoginResponse and LoginController?
- **LoginResponse**: Complex routing logic, caching, analytics
- **LoginController**: Simple authentication, email verification check
- This separation allows for different implementations based on context

### Why Middleware + LoginController Check for Email Verification?
**Defense in Depth**:
- LoginController check: Catches users immediately after login
- Middleware check: Protects routes if accessed directly
- Double protection ensures no bypass possible

---

## Next Steps for New Developers

1. **Read 01_LOGIN_RESPONSE_ARCHITECTURE.md** to understand the post-login routing system
2. **Read 02_DASHBOARD_RESOLVER_ARCHITECTURE.md** to understand dashboard determination logic
3. **Read 03_AUTHENTICATION_FLOW.md** to see the complete user journey
4. **Run the test suites** (04_TEST_SUITE_GUIDE.md) to see the system in action
5. **Review 05_SECURITY_GUIDELINES.md** before making authentication changes

---

## Key Files Reference

**Authentication Controllers**:
- `app/Http/Controllers/Auth/LoginController.php`
- `app/Http/Controllers/Auth/RegisterController.php`
- `app/Http/Controllers/Auth/VerificationController.php`

**Routing & Responses**:
- `app/Http/Responses/LoginResponse.php`
- `routes/web.php`

**Dashboard System**:
- `app/Services/DashboardResolver.php`
- `app/Http/Controllers/WelcomeDashboardController.php`
- `app/Http/Controllers/RoleSelectionController.php`

**Frontend Components**:
- `resources/js/Components/Header/Navigation.vue`
- `resources/js/Components/Jetstream/PublicDigitHeader.vue`
- `resources/js/Components/Jetstream/NrnaHeader.vue`

**Tests**:
- `tests/Feature/Auth/VerifiedMiddlewareTest.php`
- `tests/Feature/Auth/LogoutTest.php`

---

## Contact & Questions

For questions about:
- **Email Verification**: See Security Guidelines (05_SECURITY_GUIDELINES.md)
- **Dashboard Routing**: See DashboardResolver docs (02_DASHBOARD_RESOLVER_ARCHITECTURE.md)
- **Test Implementation**: See Test Suite Guide (04_TEST_SUITE_GUIDE.md)
- **Troubleshooting**: See Troubleshooting Guide (06_TROUBLESHOOTING.md)

---

**Last Updated**: 2026-03-03
**Version**: 1.0
**Status**: Production Ready

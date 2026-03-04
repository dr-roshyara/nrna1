# 🔄 Complete Authentication Flow

## Overview

This document traces the complete user authentication journey from registration through dashboard access, including all security checks and routing decisions.

## User Journey Diagram

```
┌─────────────────────────────────────────────────────┐
│ STAGE 1: REGISTRATION                                │
├─────────────────────────────────────────────────────┤
│                                                      │
│  User enters email & password                       │
│           ↓                                           │
│  POST /register                                     │
│           ↓                                           │
│  RegisterController::store()                        │
│       ├─ Validate input                             │
│       ├─ Hash password                              │
│       └─ Create user with:                          │
│           ├─ email_verified_at = NULL ❌            │
│           ├─ onboarded_at = NULL                    │
│           └─ organisation_id = 1 (platform)         │
│           ↓                                           │
│  Send verification email                           │
│           ↓                                           │
│  Redirect to /email/verify                          │
│           ↓                                           │
│  ✅ User sees verification notice                    │
│                                                      │
└─────────────────────────────────────────────────────┘
```

## Stage 2: Email Verification

```
┌─────────────────────────────────────────────────────┐
│ STAGE 2: EMAIL VERIFICATION                          │
├─────────────────────────────────────────────────────┤
│                                                      │
│  User clicks link in verification email            │
│           ↓                                           │
│  GET /email/verify/{id}/{hash}                      │
│           ↓                                           │
│  VerificationController::verify()                   │
│       ├─ Validate signature (hash)                  │
│       ├─ Mark email as verified                     │
│       │   (email_verified_at = now())               │
│       └─ Log verification time                      │
│           ↓                                           │
│  Redirect to /dashboard                            │
│           ↓                                           │
│  ✅ Email verified, user ready for login            │
│                                                      │
└─────────────────────────────────────────────────────┘
```

## Stage 3: Login

```
┌─────────────────────────────────────────────────────┐
│ STAGE 3: LOGIN                                       │
├─────────────────────────────────────────────────────┤
│                                                      │
│  User submits login form                           │
│           ↓                                           │
│  POST /login                                        │
│           ↓                                           │
│  LoginController::show()                            │
│       ├─ If already authenticated                   │
│       │   → redirect to dashboard                   │
│       └─ Else show login form                       │
│                                                      │
│  ← User sees login page ←                           │
│                                                      │
│  User submits credentials                          │
│           ↓                                           │
│  POST /login (store)                                │
│           ↓                                           │
│  LoginController::store()                           │
│       ├─ Validate input format                      │
│       ├─ Check rate limiting                        │
│       ├─ Look up user by email                      │
│       │   └─ Email not found → ValidationException  │
│       ├─ Attempt authentication                     │
│       │   └─ Password wrong → ValidationException   │
│       │                                              │
│       ✅ Authentication successful!                 │
│       ├─ Get authenticated user                     │
│       │                                              │
│       │ ✅ NEW SECURITY FIX:                        │
│       ├─ Check email_verified_at                    │
│       │   ├─ NULL → redirect to verification.notice │
│       │   └─ Valid → continue                       │
│       │                                              │
│       ├─ Check organisation_id                      │
│       │   ├─ Has org → Redirect to org page        │
│       │   └─ No org → Redirect to dashboard         │
│           ↓                                           │
│  Redirect to /dashboard (or /organisations/{slug})  │
│                                                      │
└─────────────────────────────────────────────────────┘
```

## Stage 4: Dashboard Resolution

```
┌─────────────────────────────────────────────────────┐
│ STAGE 4: DASHBOARD RESOLUTION                        │
├─────────────────────────────────────────────────────┤
│                                                      │
│  User navigates to /dashboard                      │
│           ↓                                           │
│  Route middleware checks:                           │
│       ├─ 'auth' → User authenticated?               │
│       │   └─ NO → redirect to /login                │
│       └─ 'verified' → Email verified? ✅ NEW       │
│           └─ NO → redirect to /email/verify         │
│           ↓                                           │
│  ElectionController::dashboard()                    │
│       ├─ Update voting_ip                           │
│       └─ Prepare election data                      │
│           ↓                                           │
│  DashboardResolver::resolve()                       │
│           ↓                                           │
│  Resolution Priority:                              │
│                                                      │
│  Priority 1: Active Voting?                         │
│       ├─ YES → Route to current voting step         │
│       └─ NO ↓                                        │
│                                                      │
│  Priority 2: Just verified, not onboarded?         │
│       ├─ YES → Route to /dashboard/welcome         │
│       └─ NO ↓                                        │
│                                                      │
│  Priority 3: First-time user?                       │
│       ├─ YES → /dashboard/welcome                   │
│       └─ NO ↓                                        │
│                                                      │
│  Priority 4: Multiple roles?                        │
│       ├─ YES → /dashboard/roles (role selection)   │
│       └─ NO ↓                                        │
│                                                      │
│  Priority 5: Single role?                           │
│       ├─ 'admin' → /organisations/{org_slug}       │
│       ├─ 'commission' → /dashboard/commission      │
│       ├─ 'voter' → /vote                           │
│       └─ None ↓                                      │
│                                                      │
│  Priority 6: Legacy roles?                          │
│       ├─ 'admin' → /dashboard/admin                │
│       ├─ 'commission' → /dashboard/commission      │
│       └─ Fallback → /dashboard                     │
│           ↓                                           │
│  ✅ User routed to appropriate dashboard            │
│                                                      │
└─────────────────────────────────────────────────────┘
```

## Complete Flow Diagram

```
START
  │
  ├─ New User?
  │   └─ YES → /register (Stage 1)
  │             │
  │             ↓
  │       Email verification link
  │             │
  │             ↓
  │       GET /email/verify/{id}/{hash} (Stage 2)
  │             │
  │             ↓
  │       ✅ Email verified
  │
  ├─ Login
  │   └─ POST /login (Stage 3)
  │       │
  │       ├─ Not verified?
  │       │   └─ → /email/verify (redirect)
  │       │
  │       ├─ Has organisation?
  │       │   └─ → /organisations/{slug}
  │       │
  │       └─ No organisation?
  │           └─ → /dashboard
  │                 │
  │                 ↓
  │           DashboardResolver (Stage 4)
  │                 │
  │                 ├─ Active voting?
  │                 │   └─ → /vote/{step}
  │                 │
  │                 ├─ First-time?
  │                 │   └─ → /dashboard/welcome
  │                 │
  │                 ├─ Multiple roles?
  │                 │   └─ → /dashboard/roles
  │                 │
  │                 ├─ Admin?
  │                 │   └─ → /organisations/{org}
  │                 │
  │                 ├─ Commission?
  │                 │   └─ → /dashboard/commission
  │                 │
  │                 └─ Voter?
  │                     └─ → /vote
  │
  └─ ✅ Logged in & routed!
```

## Detailed State Transitions

### User States

```
State 1: REGISTERED, NOT VERIFIED
├─ email_verified_at = NULL
├─ onboarded_at = NULL
└─ Can only access: /register, /login, /email/verify

State 2: REGISTERED, VERIFIED, NOT ONBOARDED
├─ email_verified_at = now()
├─ onboarded_at = NULL
└─ Can access: /dashboard/welcome (onboarding)

State 3: REGISTERED, VERIFIED, ONBOARDED, NO ROLES
├─ email_verified_at = now()
├─ onboarded_at = now()
├─ No organisation roles
├─ No commission roles
└─ Routes to: /dashboard/welcome (welcome dashboard)

State 4: REGISTERED, VERIFIED, ONBOARDED, HAS ROLES
├─ email_verified_at = now()
├─ onboarded_at = now()
├─ Has organisation or commission role
└─ Routes to: Role-specific dashboard
```

## Security Checkpoints

### Checkpoint 1: Email Verification (Route Middleware)

```
GET/POST to protected route
    ↓
'verified' middleware
    ├─ Check email_verified_at
    │   ├─ NULL → 302 redirect to /email/verify
    │   └─ Valid → Continue
    └─ ✅ Checkpoint passed
```

### Checkpoint 2: Email Verification (LoginController)

```
POST /login - credentials validated
    ↓
Get authenticated user
    ↓
Check email_verified_at
    ├─ NULL → 302 redirect to /email/verify
    │         (User sees verification notice)
    └─ Valid → Continue
        ↓
    ✅ Checkpoint passed
```

### Checkpoint 3: Authentication (Route Middleware)

```
GET/POST to protected route
    ↓
'auth' middleware
    ├─ User authenticated?
    │   ├─ NO → 302 redirect to /login
    │   └─ YES → Continue
    └─ ✅ Checkpoint passed
```

## Middleware Stack for Dashboard Routes

```
GET /dashboard
    ↓
Route middleware: ['auth', 'verified', 'no.cache']
    │
    ├─ 'auth' middleware
    │   └─ Check: Is user authenticated?
    │       └─ If NO → Redirect to /login
    │
    ├─ 'verified' middleware ✅ NEW
    │   └─ Check: Is email verified?
    │       └─ If NO → Redirect to /email/verify
    │
    └─ 'no.cache' middleware
        └─ Prevent browser caching of dynamic content
            ↓
        ✅ All middleware passed
            ↓
        ElectionController::dashboard()
            ↓
        DashboardResolver::resolve()
            ↓
        User routed to appropriate dashboard
```

## Example Scenarios

### Scenario 1: New User Registration & Verification

```
Time: 00:00
└─ User lands on /register
   └─ Fills registration form
   └─ POST /register
   └─ User created with:
      ├─ email_verified_at = NULL
      └─ onboarded_at = NULL
   └─ Email sent with verification link
   └─ Redirect to /email/verify (GET)
   └─ User sees "Check your email"

Time: 00:05
└─ User clicks verification link in email
   └─ GET /email/verify/{id}/{hash}
   └─ Email verified: email_verified_at = 2026-03-03 00:05:00
   └─ Redirect to /dashboard
   └─ DashboardResolver routes to /dashboard/welcome (first-time user)
   └─ User sees welcome/onboarding page

Time: 00:06
└─ User clicks "Get Started" or similar
   └─ WelcomeDashboardController marks onboarded: onboarded_at = 2026-03-03 00:06:00
   └─ Next login → routed by DashboardResolver based on roles
```

### Scenario 2: Login Flow (Verified User)

```
Time: 10:00 AM
└─ User navigates to /login
   └─ LoginController::show() → See login form

Time: 10:01 AM
└─ User enters email & password
   └─ POST /login
   └─ LoginController::store():
      ├─ Validates input
      ├─ Authenticates user (email_verified_at = 2026-02-01)
      │
      │ ✅ NEW SECURITY FIX:
      ├─ Checks email_verified_at
      │  └─ Value exists → continue
      │
      ├─ Checks organisation_id
      │  ├─ = 1 (platform) → continue to /dashboard
      │  └─ = NULL → continue to /dashboard
      │
      └─ Redirects to /dashboard

Time: 10:02 AM
└─ GET /dashboard
   └─ Middleware checks:
      ├─ 'auth' → ✅ User authenticated
      ├─ 'verified' → ✅ Email verified
      └─ 'no.cache' → Set cache headers

   └─ DashboardResolver::resolve()
      ├─ Check active voting? → NO
      ├─ Check first-time? → NO
      ├─ Get dashboard roles → ['commission']
      ├─ Single role → commission
      └─ Route to /dashboard/commission

   └─ User sees commission dashboard
```

### Scenario 3: Unverified User Attempts Access

```
Time: 10:00 AM
└─ User clicks "try dashboard" before verifying email
   └─ GET /dashboard
   └─ Middleware:
      ├─ 'auth' → ✅ User authenticated
      ├─ 'verified' → ❌ email_verified_at = NULL
      │  └─ 302 redirect to /email/verify
      │
   └─ User sees verification notice
   └─ User must verify email first
```

### Scenario 4: User with Multiple Roles

```
Time: 10:00 AM
└─ User logs in with multiple roles:
   ├─ admin in Organisation A
   └─ commission in Election X

└─ POST /login → Redirect to /dashboard
└─ DashboardResolver::resolve():
   ├─ Check active voting? → NO
   ├─ Check first-time? → NO
   ├─ Get dashboard roles → ['admin', 'commission']
   ├─ Multiple roles → Redirect to /dashboard/roles

└─ User sees role selection page
└─ User chooses "Admin" → /organisations/org-a-slug
   OR chooses "Commission" → /dashboard/commission
```

## Key Security Improvements

### ✅ Email Verification Fix
- **Before**: Unverified users could access protected routes
- **After**: Unverified users redirected to verification page
- **Implementation**: Middleware + LoginController check

### ✅ Logout Session Invalidation
- **Before**: Sessions remained active despite logout appearing to work
- **After**: Proper POST request invalidates server session
- **Implementation**: Fixed Vue components to use `this.$inertia.post()`

### ✅ Platform Member Routing
- **Before**: Platform members incorrectly routed as admins
- **After**: Platform org excluded from admin role detection
- **Implementation**: `whereNot('organisation_id', 1)` in query

## Testing the Flow

### Test Email Verification
```bash
php artisan test tests/Feature/Auth/VerifiedMiddlewareTest.php
```

### Test Logout
```bash
php artisan test tests/Feature/Auth/LogoutTest.php
```

### Manual Testing
1. Register new user
2. Try to access /dashboard (should redirect to verification)
3. Verify email
4. Access /dashboard (should see welcome or dashboard)
5. Logout (should invalidate session)

---

## Related Documentation

- **LoginResponse**: See `01_LOGIN_RESPONSE_ARCHITECTURE.md`
- **DashboardResolver**: See `02_DASHBOARD_RESOLVER_ARCHITECTURE.md`
- **Tests**: See `04_TEST_SUITE_GUIDE.md`
- **Security**: See `05_SECURITY_GUIDELINES.md`

---

**Last Updated**: 2026-03-03
**Status**: Production Ready

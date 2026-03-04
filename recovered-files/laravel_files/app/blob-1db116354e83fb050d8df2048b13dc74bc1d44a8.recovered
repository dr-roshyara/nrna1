# 🔒 Security Guidelines

## Overview

This document outlines security best practices for the authentication and dashboard system.

## Critical Security Principles

### 1. Email Verification is Mandatory
**Rule**: All protected routes MUST check email verification.

```php
// ✅ CORRECT: Check at middleware level
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', ...);
});

// ✅ CORRECT: Check in controller
if ($user->email_verified_at === null) {
    return redirect()->route('verification.notice');
}

// ❌ WRONG: Skip email verification check
Route::middleware(['auth'])->get('/dashboard', ...);
```

**Why**: Ensures only email-verified users access sensitive functions.

### 2. Logout MUST Invalidate Server Session
**Rule**: Logout must POST to server endpoint, not just client-side redirect.

```javascript
// ❌ WRONG: Client-only logout
logout() {
    localStorage.clear();
    window.location.href = '/login';
}

// ✅ CORRECT: Server-aware logout
logout() {
    this.$inertia.post(route('logout'), {}, {
        onSuccess: () => {
            localStorage.clear();
        }
    });
}
```

**Why**: Prevents session hijacking where server session remains active.

### 3. Platform Organisation is NOT a Regular Organisation
**Rule**: Platform org (id=1) should not be treated like customer organisations.

```php
// ❌ WRONG: Treat platform org as admin org
$roles = DB::table('user_organisation_roles')
    ->where('user_id', $user->id)
    ->where('role', 'admin')
    ->exists();

// ✅ CORRECT: Exclude platform org
$roles = DB::table('user_organisation_roles')
    ->where('user_id', $user->id)
    ->where('role', 'admin')
    ->whereNot('organisation_id', 1)  // Exclude platform
    ->exists();
```

**Why**: Platform members should not be auto-routed as organisation admins.

### 4. Tenant Isolation Must be Absolute
**Rule**: All queries must scope to organisation/tenant.

```php
// ❌ WRONG: Query without tenant scope
$elections = Election::where('status', 'active')->get();

// ✅ CORRECT: Scope to tenant
$elections = Election::where('organisation_id', $tenantId)
    ->where('status', 'active')
    ->get();
```

**Why**: Prevents users seeing other organisations' data.

### 5. Defense in Depth
**Rule**: Use multiple security layers rather than relying on single check.

```php
// ✅ CORRECT: Multiple checkpoints
Route::middleware(['auth', 'verified'])->group(function () {  // Layer 1: Middleware
    Route::post('/logout', function() {                        // Layer 2: POST only
        if (!auth()->check()) {                                // Layer 3: Controller check
            abort(403);
        }
        Auth::logout();
    });
});
```

**Why**: If one layer fails, others provide protection.

---

## Email Verification Security

### ✅ What's Protected

```
Protected Routes:
├─ /dashboard
├─ /dashboard/welcome
├─ /dashboard/roles
├─ /organisations/{slug}
└─ /api/* (all API endpoints)

Unprotected Routes:
├─ /login
├─ /register
├─ /email/verify (GET - show form)
├─ /email/verification-notification (POST - resend)
└─ /forgot-password
```

### ✅ Enforcement Points

**Point 1: Middleware**
```php
Route::middleware(['auth', 'verified'])->group(...)
```

**Point 2: LoginController**
```php
if ($user->email_verified_at === null) {
    return redirect()->route('verification.notice');
}
```

**Point 3: DashboardResolver**
```php
if ($user->email_verified_at !== null && $user->onboarded_at === null) {
    return redirect()->route('dashboard.welcome');
}
```

### Bypass Prevention

**Cannot Bypass By**:
- Direct URL navigation (middleware blocks)
- API calls (middleware blocks)
- Session tampering (checked on every request)
- Database manipulation (hash validation on email)

---

## Session Management Security

### ✅ Session Lifecycle

```
User Registration
    ↓
User created with email_verified_at = NULL
    ↓
User clicks verification link
    ↓
email_verified_at = now() (validated by hash)
    ↓
User logs in
    ↓
Email verification checked
    ↓
Session created
    ↓
User can access protected routes
    ↓
User logs out
    ↓
Session destroyed (POST /logout)
    ↓
Session cannot be reused
```

### ✅ Session Invalidation

**Old Way (INSECURE)**:
```php
localStorage.removeItem('auth_token');
window.location.href = '/login';  // ❌ Server still has session
```

**New Way (SECURE)**:
```php
this.$inertia.post(route('logout'), {}, {  // ✅ POST to server
    onSuccess: () => {
        localStorage.clear();  // Clear after server invalidates
    }
});
```

### ✅ CSRF Protection

All logout and state-changing operations protected by CSRF tokens via Inertia.js:

```javascript
// Inertia.js automatically includes CSRF token
this.$inertia.post(route('logout'))  // CSRF included automatically
```

---

## Role-Based Access Control

### ✅ Role Hierarchy

```
Platform Roles (System-wide):
├─ admin (platform admin) → /dashboard/admin
├─ election_officer → /dashboard/admin
└─ commission (election commission) → /dashboard/commission

Organisation Roles (Per-organisation):
├─ admin → /organisations/{slug}
└─ member → /vote (if also a voter)

Voter Roles (Per-election):
└─ voter → /vote
```

### ✅ Role Assignment Rules

**Who Can Assign Roles**:
- Platform admins → Any role
- Organisation admins → Member/admin roles in their org
- Election admins → Commission member roles

**Validation**:
```php
// ✅ Check user can assign role
if (!$user->canAssignRole($role, $organisation)) {
    throw new AuthorizationException();
}
```

### ✅ Role Isolation

Users cannot:
- See other organisations' elections
- Vote in elections they're not registered for
- Access admin dashboards without admin role
- Commission dashboards without commission role

---

## Voting System Security

### ✅ One Vote Per Voter

**Enforced By**:
```sql
UNIQUE (voter_slug_id, election_id)
-- Ensures one voter slug can only vote once per election
```

### ✅ Vote Anonymity

**Voting Code is NOT linked to user**:
```php
// ❌ NEVER do this:
votes.user_id = $user->id;  // WRONG - breaks anonymity

// ✅ CORRECT:
codes.voting_code = hash('sha256', ..);  // Hashed, one-way
votes.voting_code_hash = ...  // Audit only, cannot reverse to user
```

### ✅ Vote Verification

**Voter receives code after voting**:
```
1. Vote cast (anonymous)
2. System generates unique verification code
3. Code shown to voter (for verification)
4. Voter can later check code to verify vote was counted
5. But code cannot be used to prove who voted
```

---

## Authentication Security

### ✅ Password Security

```php
// ✅ CORRECT: Passwords hashed with bcrypt
Hash::make($password)

// ❌ WRONG: Plain text or weak hash
$user->password = $password;  // NEVER
md5($password);  // NEVER
```

### ✅ Rate Limiting

```php
// Prevent brute force attacks
RateLimiter::hit($throttleKey($request));
if (RateLimiter::tooManyAttempts(...)) {
    event(new Lockout($request));
    throw ValidationException(...);
}
```

**Limits**:
- 5 failed login attempts per minute per IP
- Lockout after 5 attempts

### ✅ 2FA (If Enabled)

**Optional 2FA via Fortify**:
```php
// Enable in config/fortify.php
'features' => [
    Features::emailVerification(),
    Features::twoFactorAuthentication(),
]
```

---

## Common Vulnerabilities & Mitigation

### Vulnerability 1: SQL Injection

**Risk**: `SELECT * FROM users WHERE email = $email`

**Mitigation**:
```php
// ✅ CORRECT: Use parameterized queries
User::where('email', $email)->first()

// ❌ WRONG: Raw queries without params
DB::select("SELECT * FROM users WHERE email = '$email'")
```

### Vulnerability 2: XSS (Cross-Site Scripting)

**Risk**: Injected JavaScript in user input

**Mitigation**:
```php
// ✅ CORRECT: Vue/Blade escapes output automatically
{{ $user->name }}  // HTML escaped

// ❌ WRONG: Raw output
{!! $user->name !!}  // Only if you trust input
```

### Vulnerability 3: CSRF

**Risk**: Forged requests from third-party sites

**Mitigation**:
```php
// ✅ CORRECT: CSRF token included
<form method="POST">
    @csrf  // Blade includes token
    ...
</form>

// ✅ CORRECT: Inertia handles CSRF
this.$inertia.post(route('logout'))  // Token included
```

### Vulnerability 4: Session Fixation

**Risk**: Attacker uses known session ID

**Mitigation**:
```php
// ✅ CORRECT: Regenerate session on login
session()->regenerate()  // Already done by Laravel Auth

// ✅ CORRECT: Invalidate on logout
Auth::logout()  // Destroys session
session()->invalidate()
session()->regenerateToken()
```

---

## Data Protection

### ✅ What NOT to Log

**NEVER log**:
- Passwords
- Credit card numbers
- API keys
- Verification codes
- Voting codes

**Safe to log**:
- User IDs
- Email addresses (hashed preferred)
- IP addresses
- User actions (without sensitive data)
- Error messages (generic)

### ✅ Secure Logging

```php
// ❌ WRONG:
Log::info('User logged in', ['password' => $password]);

// ✅ CORRECT:
Log::info('User login attempt', [
    'email' => $email,
    'ip' => $request->ip(),
    'user_agent' => $request->userAgent(),
]);
```

---

## Third-Party Integration Security

### ✅ Email Provider

**Verification emails contain**:
- User ID
- Verification URL with signed hash
- Expiration time
- Link can only be used once

### ✅ No Sensitive Data in URLs

```
// ❌ WRONG:
/verify?token=abc123&password=secret

// ✅ CORRECT:
/verify/abc123/def456  // Signed URL with hash
```

---

## Audit Trail

### ✅ What's Logged

```
/storage/logs/organisation_{id}/election_{name}/{user_id}_{username}.log

Logged Events:
├─ Login attempt (success/failure)
├─ Email verification
├─ Logout
├─ Role assignment
├─ Voting actions
├─ IP address
├─ Timestamp
└─ Error messages
```

### ✅ Audit Query

```php
// Find all user actions
$logs = LogRecord::where('user_id', $userId)
    ->whereDate('created_at', '>=', now()->subMonths(3))
    ->get();
```

---

## Compliance

### ✅ GDPR

**Data Retention**:
- User data: Indefinite (until deletion request)
- Logs: 1 year
- Audit trail: 3 years
- Voting codes: 5 years (for dispute resolution)

**User Rights**:
- Right to access (export data)
- Right to deletion
- Right to correction
- Right to data portability

### ✅ Data Deletion

```php
// User requests deletion
$user->delete();  // Soft delete
// Logs retained for audit trail
// But user data anonymized
```

---

## Security Checklist

Before deploying to production:

- [ ] Email verification enforced on all protected routes
- [ ] Logout properly invalidates server session
- [ ] Password hashed with bcrypt
- [ ] CSRF protection enabled
- [ ] Rate limiting configured
- [ ] SSL/TLS enabled (HTTPS only)
- [ ] Secrets not in code (use .env)
- [ ] Audit logging enabled
- [ ] Tests pass (including security tests)
- [ ] No debug info in production
- [ ] Error messages don't leak info

---

## Incident Response

### If Breach Suspected

1. **Immediate Actions**:
   - Revoke all sessions: `php artisan session:clear`
   - Reset verification codes
   - Force re-verification of all users
   - Alert security team

2. **Investigation**:
   - Check audit logs
   - Identify affected users
   - Analyze attack vector

3. **Recovery**:
   - Deploy patch
   - Force user password resets
   - Notify affected users
   - Update security documentation

---

## Security Resources

- OWASP Top 10: https://owasp.org/www-project-top-ten/
- Laravel Security: https://laravel.com/docs/security
- PHP Security: https://www.php.net/manual/en/security.php

---

## Related Documentation

- **Authentication Flow**: See `03_AUTHENTICATION_FLOW.md`
- **Test Suite**: See `04_TEST_SUITE_GUIDE.md`
- **Troubleshooting**: See `06_TROUBLESHOOTING.md`

---

**Last Updated**: 2026-03-03
**Status**: Production Ready
**Review Frequency**: Quarterly

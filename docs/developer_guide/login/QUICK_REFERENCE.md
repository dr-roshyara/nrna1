# 🚀 Authentication Quick Reference

**Fast answers for authentication questions**

---

## Login in 30 Seconds

```
1. User visits /login
2. Enters email + password
3. Form submitted via Inertia.post()
4. Fortify validates credentials
5. Session created server-side
6. Browser receives session cookie
7. Redirect to /dashboard
8. User authenticated ✅
```

---

## Logout in 30 Seconds

```
1. User clicks "Logout" button
2. ElectionHeader.logout() creates form
3. Extracts CSRF token from meta tag
4. POST /logout with token
5. Server destroys session
6. Clears session cookie
7. Redirect to /
8. User logged out ✅
```

---

## Key Files

| File | Purpose |
|------|---------|
| `resources/js/Components/Header/ElectionHeader.vue` | Login/Logout buttons |
| `resources/js/Pages/Auth/Login.vue` | Login form page |
| `routes/jetstream.php` | Auth routes (Fortify) |
| `config/session.php` | Session configuration |
| `config/fortify.php` | Authentication config |
| `app/Http/Kernel.php` | Middleware stack |

---

## Check if User is Logged In

```javascript
// In Vue component:
const user = this.$page.props.auth?.user;

if (user) {
  console.log('✅ Logged in as:', user.name);
} else {
  console.log('❌ Not logged in');
}
```

---

## Protect a Route

```php
// In routes/web.php:
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
});

// Only authenticated users can access /dashboard
```

---

## Check User in Controller

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController
{
    public function index()
    {
        $user = Auth::user();  // Get logged-in user

        if (!$user) {
            return redirect('/login');
        }

        return response()->json($user);
    }
}
```

---

## Session Lifetime

**Current Setting:** 120 minutes (2 hours)

**To Change:**
```env
# In .env:
SESSION_LIFETIME=120
```

---

## Common Errors & Fixes

| Error | Cause | Fix |
|-------|-------|-----|
| **419 (CSRF)** | Missing token | Verify `<meta name="csrf-token">` in page |
| **Logged out after refresh** | Session not persisting | Check `storage/framework/sessions/` permissions |
| **Can't logout** | Token expired | Clear sessions: `php artisan session:clear` |
| **Auth middleware fails** | Session corrupted | Regenerate: `php artisan key:generate` |

---

## Debugging

```javascript
// Check if authenticated:
this.$page.props.auth?.user

// Check CSRF token:
document.querySelector('meta[name="csrf-token"]').content

// Check session cookie:
document.cookie

// Monitor logout:
Open F12 → Console
Click logout
Watch for "✅ CSRF token added" message
```

---

## Mobile Menu Logout Button

The logout button appears in mobile menu when:
1. User is logged in (`isLoggedIn` prop = true)
2. Screen is mobile size (< 768px wide)
3. Hamburger menu is open

**Location:** `resources/js/Components/Header/ElectionHeader.vue` (lines 182-191)

---

## Test Logout Works

```bash
# 1. Login at http://localhost:8000/login
# 2. Open DevTools: F12 → Console
# 3. Click Logout button
# 4. See messages:
#    🚪 Logout initiated
#    ✅ CSRF token added
#    📤 Submitting logout form
# 5. Should redirect to home page
# 6. Login button should reappear
```

---

## Session Storage Location

```
storage/
├── framework/
│   └── sessions/
│       ├── abc123def456...
│       ├── xyz789uvw012...
│       └── (one file per session)
```

Each file contains session data (user_id, etc.)

---

## CSRF Token

**Where:** `<meta name="csrf-token" content="...">`
**Used in:** All POST/PUT/DELETE requests
**Expires:** Never (regenerated on logout)
**Purpose:** Prevent Cross-Site Request Forgery attacks

---

## Remember Me Feature

**Current Status:** Available in Login.vue
**How it works:** Extends session lifetime
**Security:** Safe (uses secure token)

```javascript
// In Login.vue form:
<input v-model="form.remember" type="checkbox" />
// If checked, session lasts longer
```

---

## Password Reset Flow

```
1. User clicks "Forgot Password"
2. Enters email
3. Fortify sends reset link
4. User clicks link
5. Enters new password
6. Password updated
7. Redirect to login
```

---

## Two-Factor Authentication

**Current Status:** Enabled in `config/fortify.php`

```php
Features::twoFactorAuthentication([
    'confirmPassword' => true,
]),
```

Available for users who enable it.

---

## Routes Related to Auth

```
GET  /login              → Show login form
POST /login              → Process login
GET  /register           → Show registration form
POST /register           → Process registration
POST /logout             → Process logout (redirects /)
GET  /forgot-password    → Show password reset request
POST /forgot-password    → Send reset email
GET  /reset-password/{token} → Show reset form
POST /reset-password     → Process password reset
```

---

## Session Middleware Order

**Critical:** This order matters!

```
1. EncryptCookies        (decrypt incoming)
2. AddQueuedCookies      (queue outgoing)
3. StartSession          (load user from session)
4. AuthenticateSession   (verify session valid)
5. VerifyCsrfToken       (check CSRF token)
6. HandleInertiaRequests (share props with Vue)
```

---

## Auth Guard

**Current Guard:** `web` (configured in `config/fortify.php`)

```php
'guard' => 'web',
```

This means:
- Authentication driver: `web` guard
- Session-based authentication
- Uses `users` table for credentials

---

## Permissions & Roles

**Currently Using:** Spatie Permission package

```php
// Check if user has permission:
$user->hasPermission('edit-election');

// Check if user has role:
$user->hasRole('admin');

// Grant permission:
$user->givePermissionTo('edit-election');
```

---

## Best Practice Checklist

- ✅ Always verify user on server side
- ✅ Don't trust frontend authentication state
- ✅ Use HTTPS in production
- ✅ Set reasonable session timeout
- ✅ Regenerate CSRF token on logout
- ✅ Log authentication events
- ✅ Rate limit login attempts
- ✅ Use strong password requirements

---

**Need More Details?** See `AUTHENTICATION_LOGOUT.md` in this directory.

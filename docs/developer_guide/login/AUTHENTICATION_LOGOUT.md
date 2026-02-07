# 🔐 Authentication & Logout Guide

**Public Digit Platform — User Authentication, Sessions, and Logout**

---

## Table of Contents

1. [Overview](#overview)
2. [Authentication Flow](#authentication-flow)
3. [Logout Mechanism](#logout-mechanism)
4. [Implementation Details](#implementation-details)
5. [Testing](#testing)
6. [Troubleshooting](#troubleshooting)

---

## Overview

Public Digit uses **Laravel Fortify** for authentication and **Laravel Sessions** for managing logged-in users.

### Key Components

| Component | Purpose |
|-----------|---------|
| **Laravel Fortify** | Headless authentication API |
| **Laravel Sessions** | Server-side user session storage |
| **Jetstream** | User profile and team management |
| **Inertia.js** | Vue 3 ↔ Laravel bridge |
| **ElectionHeader Component** | Login/Logout UI |

---

## Authentication Flow

### Step 1: Login Request

```
User visits http://localhost:8000/login
    ↓
ElectionHeader shows login link
    ↓
User clicks "Login"
    ↓
Inertia navigates to /login page
```

### Step 2: Login Page (Pages/Auth/Login.vue)

```vue
<template>
  <form @submit.prevent="submit">
    <input v-model="form.email" type="email" />
    <input v-model="form.password" type="password" />
    <button type="submit">{{ $t('navigation.login') }}</button>
  </form>
</template>

<script>
export default {
  data() {
    return {
      form: this.$inertia.form({
        email: '',
        password: '',
        remember: false,
      }),
    };
  },
  methods: {
    submit() {
      this.form.post(this.route('login'), {
        onFinish: () => this.form.reset('password'),
      });
    },
  },
};
</script>
```

### Step 3: Server-Side Login (Laravel Fortify)

```
POST /login with email + password
    ↓
Fortify validates credentials
    ↓
Laravel creates session for user
    ↓
Sets session cookie in response
    ↓
Browser stores session cookie
    ↓
Redirect to /dashboard
```

### Step 4: Session Storage

**Browser:** Stores `XSRF-TOKEN` and `laravel_session` cookies
**Server:** Stores session data in `storage/framework/sessions/`

```
Session Data:
├── user_id: 1
├── login_ip: 192.168.1.1
├── login_time: 2026-02-07 10:30:00
├── last_activity: 2026-02-07 10:35:00
└── CSRF token
```

### Step 5: Authenticated Requests

```
User makes request (logged in)
    ↓
Browser sends cookies automatically:
  - laravel_session=abc123...
  - XSRF-TOKEN=xyz789...
    ↓
Laravel middleware verifies session
    ↓
Request processes with Auth::user() available
```

---

## Logout Mechanism

### Logout Flow

```
User clicks "Logout" button
    ↓
ElectionHeader.logout() method executes
    ↓
Creates form with CSRF token
    ↓
POST /logout with credentials
    ↓
Laravel destroys session:
  ├── Deletes session file
  ├── Clears session cookie
  ├── Removes CSRF token
    ↓
Response with redirect to /
    ↓
Browser cleared of session
    ↓
User redirected to home (logged out)
```

### Code Implementation

**File:** `resources/js/Components/Header/ElectionHeader.vue`

```javascript
logout() {
  console.log('🚪 Logout initiated');
  this.closeMobileMenu();

  try {
    // Create form element
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = route('logout');

    // Extract CSRF token from page meta tag
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (csrfToken) {
      const input = document.createElement('input');
      input.type = 'hidden';
      input.name = '_token';
      input.value = csrfToken.getAttribute('content');
      form.appendChild(input);
      console.log('✅ CSRF token added');
    }

    // Submit form
    document.body.appendChild(form);
    console.log('📤 Submitting logout form');
    form.submit();
  } catch (error) {
    console.error('❌ Logout error:', error);
    alert('Logout failed. Please refresh the page.');
  }
}
```

### Why Form Submission?

**Problem:** Inertia POST request wasn't including CSRF token properly

**Solution:** Traditional form submission ensures:
1. ✅ CSRF token properly attached
2. ✅ Cookies sent automatically by browser
3. ✅ Server can verify session validity
4. ✅ Proper redirect handling
5. ✅ Clean session destruction

---

## Implementation Details

### 1. Configuration Files

**File:** `config/session.php`

```php
'lifetime' => env('SESSION_LIFETIME', 120), // 2 hours
'expire_on_close' => false,
'encrypt' => false,
'files' => storage_path('framework/sessions'),
'connection' => null,
'table' => 'sessions',
'store' => null,
'lottery' => [2, 100],
'cookie' => env('SESSION_COOKIE', 'laravel_session'),
'path' => '/',
'domain' => env('SESSION_DOMAIN'),
'secure' => env('SESSION_SECURE_COOKIES', false),
'http_only' => true,
'same_site' => 'lax',
```

### 2. Middleware Stack

**File:** `app/Http/Kernel.php`

Authentication-related middleware:

```php
protected $middlewareGroups = [
    'web' => [
        \App\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,     // ← Starts session
        \Laravel\Jetstream\Http\Middleware\AuthenticateSession::class, // ← Auth check
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\VerifyCsrfToken::class,            // ← CSRF validation
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
        \App\Http\Middleware\SetLocale::class,
        \App\Http\Middleware\HandleInertiaRequests::class,      // ← Share props with Vue
    ],
];
```

**Execution Order (Critical):**
1. Decrypt cookies
2. Add queued cookies
3. Start session ← Creates `$_SESSION` and loads user
4. Authenticate session ← Verifies session validity
5. Share errors
6. Verify CSRF token
7. Other middleware...

### 3. Route Protection

**File:** `routes/jetstream.php` and `routes/web.php`

```php
// Protected routes (require authentication)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/election/{slug}', [ElectionController::class, 'show'])->name('election.show');
});

// Guest routes (require no authentication)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate']);
});

// Fortify handles logout automatically
// POST /logout → Laravel\Fortify\Http\Controllers\AuthenticatedSessionController@destroy
```

### 4. Session Destruction

**What Happens When `/logout` is Called:**

```php
// Laravel Fortify's logout controller (automatic)
Route::post('/logout', function (Request $request) {
    Auth::guard('web')->logout();           // ① Log out user

    $request->session()->invalidate();       // ② Destroy session
    $request->session()->regenerateToken();  // ③ Regenerate CSRF token

    return redirect('/');                    // ④ Redirect home
});
```

---

## Testing

### Test 1: Login Flow

```bash
# 1. Start dev server
php artisan serve
npm run dev

# 2. Navigate to http://localhost:8000/login
# 3. Enter credentials
# 4. Click Login
# 5. Should redirect to /dashboard
# 6. Header should show "Logout" button instead of "Login"
```

### Test 2: Logout Flow

```bash
# 1. While logged in, open DevTools (F12 → Console)
# 2. Click "Logout" button
# 3. Watch console for:
#    ✅ "🚪 Logout initiated"
#    ✅ "✅ CSRF token added"
#    ✅ "📤 Submitting logout form"
# 4. Should redirect to home page
# 5. Header should show "Login" button again
```

### Test 3: Session Verification

```javascript
// In browser console while logged in:
console.log('Cookies:', document.cookie);
// Should show: laravel_session=..., XSRF-TOKEN=...

// After logout:
console.log('Cookies:', document.cookie);
// Should be different (or empty)
```

### Test 4: Protected Routes

```bash
# While logged out:
# Try to visit http://localhost:8000/dashboard
# Should redirect to /login

# While logged in:
# Visit http://localhost:8000/dashboard
# Should display dashboard
```

### Test 5: CSRF Protection

```javascript
// Simulate logout without CSRF token (should fail):
fetch('/logout', {
  method: 'POST',
  body: new FormData(),
})
// Result: 419 Unauthenticated (CSRF mismatch)
```

---

## Troubleshooting

### Issue 1: Logout Shows 419 Error

**Symptom:** POST /logout returns 419 (CSRF token mismatch)

**Causes:**
- CSRF token not in page meta tag
- Token expired
- Session middleware not running
- CSRF middleware not verifying

**Fix:**
```bash
# 1. Clear session files
rm -rf storage/framework/sessions/*

# 2. Regenerate app key
php artisan key:generate

# 3. Clear caches
php artisan cache:clear
php artisan session:clear

# 4. Verify CSRF token in page source
# Open DevTools → Elements → search for "csrf-token"
# Should find: <meta name="csrf-token" content="...">
```

### Issue 2: Session Doesn't Persist After Login

**Symptom:** Login works, but after refresh you're logged out

**Causes:**
- Session configuration wrong
- Session driver not working (file system full, permissions)
- Cookie settings blocking session cookie
- Session lifetime too short

**Debug:**
```bash
# Check session driver
grep "SESSION_DRIVER" .env
# Should be: SESSION_DRIVER=file

# Check session storage
ls -la storage/framework/sessions/
# Should have files there

# Check file permissions
chmod -R 755 storage/framework/sessions

# Verify session timeout
grep "SESSION_LIFETIME" .env
# Should be: SESSION_LIFETIME=120 (or your value)
```

### Issue 3: "User Not Found" After Logout

**Symptom:** After logout, trying to access user causes error

**Solution:** Verify user is properly logged out

```javascript
// In Vue component:
const user = this.$page.props.auth?.user;
if (!user) {
  console.log('✅ User properly logged out');
} else {
  console.log('❌ User still in props:', user);
}
```

### Issue 4: Mobile Menu Logout Button Not Visible

**Symptom:** Logout button doesn't appear in mobile menu

**Check:**
1. Are you logged in? (Check header)
2. Shrink browser to mobile size (< 768px)
3. Click hamburger ☰
4. Logout button should appear at bottom

**If still missing:**
```javascript
// In browser console:
// Check if isLoggedIn prop is passed
this.$page.props.auth?.user
// Should show user object if logged in
```

---

## Security Considerations

### ✅ What's Protected

```
✅ CSRF token on all POST/PUT/DELETE requests
✅ Session cookie is HttpOnly (can't be accessed by JavaScript)
✅ Session cookie is Secure (HTTPS only in production)
✅ Session cookie is SameSite=Lax (prevents CSRF)
✅ Session timeout after 120 minutes of inactivity
✅ Session regeneration on logout
✅ Password not stored in session (only user_id)
```

### ⚠️ Things to Remember

```
⚠️ Never store sensitive data in Vue props
⚠️ Never send passwords over unsecured connections
⚠️ Always validate user input on server side
⚠️ Don't trust frontend validation alone
⚠️ Keep session timeout reasonable (2 hours default)
⚠️ Clear sessions regularly (cron job for old sessions)
```

### 🔒 Best Practices

1. **Session Timeout** — 2 hours is reasonable for web app
2. **Remember Me** — Optional, use secure token storage
3. **Device Management** — Show "Logout all other sessions"
4. **Activity Logging** — Log login/logout events
5. **Rate Limiting** — Limit login attempts (Fortify does this)

---

## Summary

| Aspect | Details |
|--------|---------|
| **Framework** | Laravel Fortify + Jetstream |
| **Session Storage** | File-based (`storage/framework/sessions/`) |
| **Session Duration** | 120 minutes (configurable) |
| **CSRF Protection** | Token-based (form submission) |
| **Cookie Security** | HttpOnly, SameSite=Lax |
| **Logout Method** | Traditional form POST to `/logout` |
| **Frontend** | Vue 3 ElectionHeader component |

---

## Quick Reference

### Login
```
POST /login
├── Email + Password
├── CSRF Token
└── Remember (optional)
```

### Logout
```
POST /logout
├── CSRF Token
├── Destroy session
├── Clear cookies
└── Redirect to /
```

### Protected Routes
```
Require: auth middleware
├── Check session exists
├── Verify session valid
├── Load user from database
└── Allow access
```

---

**Version:** 1.0
**Last Updated:** February 2026
**Status:** ✅ Production Ready

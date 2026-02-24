# Inertia 2.0 CSRF Token Fix (HTTP 419 Resolution)

**Date**: 2026-02-24
**Status**: ✅ FIXED
**Issue**: HTTP 419 "Token Mismatch" errors on /login and /register

---

## The Problem

After implementing registration, both `/login` and `/register` endpoints returned HTTP 419 CSRF token errors. This was caused by **conflicting CSRF token handling** between Laravel's traditional meta tag approach and Inertia 2.0's automatic cookie-based CSRF protection.

### Root Cause

The application had:
```html
<!-- OLD - CONFLICTS WITH INERTIA 2.0 -->
<meta name="csrf-token" content="{{ csrf_token() }}">
```

**Why this breaks Inertia 2.0:**
- Laravel/Inertia use the `XSRF-TOKEN` cookie and `X-XSRF-TOKEN` header
- The static meta tag doesn't update after session regeneration (especially after login)
- Inertia can't refresh the token automatically
- Forms fail with 419 "Token Mismatch"

---

## The Solution (Inertia 2.0 Best Practice)

### Step 1: Remove Meta Tag ✅ DONE

**File**: `resources/views/app.blade.php`

```diff
- <meta name="csrf-token" content="{{ csrf_token() }}">
```

**Why this works:**
- Inertia automatically manages CSRF via cookies
- Axios (HTTP client) automatically includes `X-XSRF-TOKEN` header
- Token refreshes automatically when session regenerates

### Step 2: Configure HandleInertiaRequests ✅ DONE

**File**: `app/Http/Middleware/HandleInertiaRequests.php`

```php
public function share(Request $request): array
{
    return array_merge(parent::share($request), [
        'csrf_token' => csrf_token(), // Share token in props (optional but good)
        'canLogin' => \Route::has('login'),
        'canRegister' => \Route::has('register'),
        // ... other shared data
    ]);
}
```

**What this does:**
- `parent::share($request)` includes CSRF token from Inertia base class
- Additional explicit sharing makes token available to Vue via `$page.props.csrf_token`
- Forms can access token but don't need to manually include it

### Step 3: Add 419 Exception Handler ✅ DONE

**File**: `bootstrap/app.php`

```php
->withExceptions(function (Exceptions $exceptions) {
    $exceptions->dontFlash([
        'current_password',
        'password',
        'password_confirmation',
    ]);

    // Graceful handling for token expiration
    $exceptions->respond(function (\Symfony\Component\HttpFoundation\Response $response) {
        if ($response->getStatusCode() === 419) {
            return back()->with([
                'message' => 'Your session has expired. Please refresh and try again.',
            ]);
        }
        return $response;
    });
})
```

**What this does:**
- Catches 419 errors gracefully
- Redirects back with user-friendly message
- Prevents ugly error modals
- Better UX when token expires

---

## How It Works Now

### CSRF Flow with Inertia 2.0

```
1. First Page Load
   ├─ Browser: GET /register
   ├─ Server: Sets XSRF-TOKEN cookie (httponly=false, accessible to JS)
   └─ Inertia: Renders component, token available

2. Form Submission
   ├─ Inertia Form Helper: form.post('/register')
   ├─ Axios: Automatically reads XSRF-TOKEN cookie
   ├─ Axios: Includes X-XSRF-TOKEN header in POST
   ├─ Server: VerifyCsrfToken middleware validates
   └─ Result: ✅ Success (no 419)

3. After Login (Session Regenerated)
   ├─ Server: Invalidates old session, creates new session
   ├─ Server: Sets new XSRF-TOKEN cookie
   ├─ Axios: Automatically reads NEW token
   ├─ Axios: Includes new X-XSRF-TOKEN header in POST
   └─ Result: ✅ Token still valid (no 419)
```

---

## Vue/JS Implementation (The Right Way)

### ✅ CORRECT - Let Inertia Handle It

```vue
<script>
export default {
    data() {
        return {
            form: this.$inertia.form({
                email: "",
                password: "",
            }),
        };
    },
    methods: {
        submit() {
            // Inertia + Axios handle CSRF automatically
            this.form.post(this.route("login"), {
                onFinish: () => this.form.reset("password"),
            });
        },
    },
};
</script>

<template>
    <form @submit.prevent="submit">
        <input type="email" v-model="form.email" />
        <input type="password" v-model="form.password" />
        <button type="submit" :disabled="form.processing">Login</button>
    </form>
</template>
```

### ❌ WRONG - Don't Do This

```vue
<!-- DON'T manually add _token field -->
<input type="hidden" name="_token" :value="$page.props.csrf_token" />

<!-- DON'T use traditional form submission with manual CSRF -->
<form method="POST" action="/login">
    @csrf <!-- This is old Laravel way, conflicts with Inertia -->
</form>
```

---

## Browser Verification

To verify the fix is working:

1. **Open DevTools** (F12)
2. **Go to Application tab** → Cookies
3. **Look for `XSRF-TOKEN`** cookie
   - Should be present after first page load
   - Should update after login
4. **Go to Network tab**
5. **Submit a form** (login/register)
6. **Check POST request headers**
   - Should have `X-XSRF-TOKEN: [token-value]`
   - Should have `X-Requested-With: XMLHttpRequest`

---

## Testing the Fix

### In Browser

```
1. Go to http://localhost:8000/login
2. Enter email and password
3. Click Login
4. Should process normally (no 419 error)
5. Should redirect to dashboard
```

### In Terminal

```bash
# Clear browser cache/cookies first
# Then test:
curl -i -X POST http://localhost:8000/login \
  -H "Content-Type: application/json" \
  -d '{"email":"test@example.com","password":"password"}'

# Should NOT get 419 error
# Session and CSRF token should be validated correctly
```

---

## Critical Points

| Point | What To Do |
|-------|-----------|
| **Meta Tag** | ❌ REMOVE from app.blade.php |
| **Middleware** | ✅ Keep csrf_token in share() |
| **Exception Handler** | ✅ Add 419 handler to bootstrap/app.php |
| **Vue Forms** | ✅ Use `this.$inertia.form()` normally |
| **Manual Tokens** | ❌ NEVER add _token to form manually |
| **@csrf Blade** | ❌ NEVER use in Inertia pages |

---

## Checklist for Verification

- [x] Meta tag `<meta name="csrf-token">` removed from app.blade.php
- [x] HandleInertiaRequests shares csrf_token in props
- [x] bootstrap/app.php has 419 exception handler
- [x] Forms use `this.$inertia.form()` without manual tokens
- [x] XSRF-TOKEN cookie is set in browser
- [x] X-XSRF-TOKEN header is sent with POST requests
- [x] Login/register forms work without 419 errors
- [x] Token refreshes after session regeneration

---

## Files Modified

| File | Change | Reason |
|------|--------|--------|
| `resources/views/app.blade.php` | Removed csrf-token meta tag | Conflicts with Inertia automatic handling |
| `bootstrap/app.php` | Added 419 exception handler | Graceful handling of token expiration |
| `app/Http/Middleware/HandleInertiaRequests.php` | Already shares csrf_token | Already correct, no changes needed |

---

## Why This Matters

This is a **fundamental architectural difference** between:

### Old Laravel Pattern
```php
<!-- Meta tag in HTML -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Form in Blade -->
<form method="POST" action="/login">
    @csrf <!-- Blade macro -->
    <input name="email">
</form>

// jQuery/AJAX reads meta tag
headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
}
```

### Inertia 2.0 Pattern
```php
// No meta tag needed!

// Vue component
<template>
    <form @submit.prevent="submit">
        <input v-model="form.email">
    </form>
</template>

<script>
const form = this.$inertia.form({...});
form.post('/login'); // Axios handles everything
</script>
```

**Inertia 2.0 is smarter** - it automatically manages tokens via cookies and headers. The old meta tag approach is unnecessary and breaks the automatic token refresh.

---

## Related Documentation

- Inertia.js CSRF Documentation: https://inertiajs.com/security#csrf-protection
- Laravel CSRF Documentation: https://laravel.com/docs/11.x/csrf
- Axios Documentation: https://axios-http.com/

---

## Status

✅ **All CSRF issues resolved**
✅ **Login/register working correctly**
✅ **Token validation proper**
✅ **Session management functional**

**Ready for production!**

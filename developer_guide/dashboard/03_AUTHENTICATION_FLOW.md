# Authentication Flow & Email Verification

## Complete User Authentication Journey

```
┌─────────────────────────────────────────────────────────────────┐
│                     USER REGISTRATION                           │
└─────────────────┬───────────────────────────────────────────────┘
                  ↓
          ┌─────────────────┐
          │ FORTIFY FORM    │
          │ - Email         │
          │ - Password      │
          └────────┬────────┘
                   ↓
    ┌──────────────────────────────┐
    │ STORE USER (unverified)      │
    │ email_verified_at = NULL     │
    └────────┬─────────────────────┘
             ↓
   ┌─────────────────────────────┐
   │ SEND VERIFICATION EMAIL     │
   │ Link with signed token      │
   └────────┬────────────────────┘
            ↓
   ┌─────────────────────────────┐
   │ REDIRECT TO VERIFICATION    │
   │ /email/verify page          │
   └────────┬────────────────────┘
            ↓
   ┌─────────────────────────────┐
   │ USER CLICKS EMAIL LINK      │
   └────────┬────────────────────┘
            ↓
   ┌─────────────────────────────┐
   │ VERIFY EMAIL                │
   │ email_verified_at = NOW()   │
   │ REDIRECT TO /DASHBOARD      │
   └────────┬────────────────────┘
            ↓
   ┌─────────────────────────────┐
   │ MIDDLEWARE CHECK            │
   │ verified middleware         │
   └────────┬────────────────────┘
            ↓
   ┌─────────────────────────────┐
   │ LoginResponse.toResponse()  │
   │ Call DashboardResolver      │
   └────────┬────────────────────┘
            ↓
   ┌─────────────────────────────┐
   │ DASHBOARD ROUTING           │
   │ 6-priority system           │
   └────────┬────────────────────┘
            ↓
   ┌─────────────────────────────┐
   │ REDIRECT TO CORRECT         │
   │ DASHBOARD/WELCOME/ROLES     │
   └─────────────────────────────┘
```

---

## Email Verification Enforcement (Multi-Layer)

The system enforces email verification in **THREE places**:

### Layer 1: Middleware

Route protection prevents unauthenticated access:

```php
// routes/web.php
Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('verified');  // ← Email verification required
});
```

**What this does:**
- If user not verified, redirect to `/email/verify`
- If user IS verified, allow access
- Clean, declarative route protection

### Layer 2: LoginResponse

Optional defensive check:

```php
// app/Http/Responses/LoginResponse.php
public function toResponse($request): RedirectResponse
{
    $user = $request->user();

    // Additional check (defensive)
    if (!$user->email_verified_at) {
        return redirect()->route('verification.notice');
    }

    // Continue with dashboard routing
    $resolver = app(DashboardResolver::class);
    return $resolver->resolve($user);
}
```

**Why TWO checks?**
- Belt-and-suspenders approach
- Middleware is primary defense
- LoginResponse is secondary (defensive programming)
- If one fails, other catches it

### Layer 3: DashboardResolver

```php
// app/Services/DashboardResolver.php
private function isFirstTimeUser(User $user): bool
{
    // Unverified users are always "first-time"
    if (!$user->email_verified_at) {
        return true;  // Force to welcome page
    }

    // ... rest of first-time logic
}
```

**Why?**
- Unverified users treated as "new"
- Redirects them to `/dashboard/welcome`
- Allows email verification flow to complete

---

## Email Verification Lifecycle

### Step 1: User Registers

```php
// Fortify creates user
$user = User::create([
    'name' => $request->name,
    'email' => $request->email,
    'password' => Hash::make($request->password),
    'email_verified_at' => null,  // ← NOT verified yet
]);
```

### Step 2: Notification Sent

```php
// Laravel Fortify automatically sends:
$user->sendEmailVerificationNotification();

// This generates a signed URL like:
// /email/verify?expires=1234567890&signature=abc123
```

### Step 3: User Clicks Link

```php
// Route in routes/auth.php (Laravel)
Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.verify');
```

### Step 4: Email Verified

```php
// VerifyEmailController checks:
// 1. Signature is valid (prevents tampering)
// 2. Link not expired
// 3. Hash matches user's email

// Then marks user as verified:
$user->markEmailAsVerified();  // Sets email_verified_at = NOW()
```

### Step 5: Redirect to Dashboard

```php
// After verification, redirect to dashboard
return redirect()->route('dashboard');

// Then LoginResponse/DashboardResolver routes them
// based on their role (PRIORITY 3 → /dashboard/welcome)
```

---

## Session Management

### Session Created

When user logs in:

```php
// Laravel creates session
Auth::login($user);  // Creates session cookie

// Session ID stored in:
// - Browser cookie (session_id)
// - Server storage (storage/framework/sessions/)
```

### Session Data

Session contains:

```php
session([
    'login_web_59ba36addc2b2f9401580f27e30b5e14' => $user->id,
    'LAST_ACTIVITY' => time(),
    // ... other Laravel session data
]);
```

### Session Timeout

Default Laravel timeout: **2 hours of inactivity**

```php
// config/session.php
'lifetime' => 120,  // 120 minutes
'expire_on_close' => false,  // Don't expire when browser closes
```

### CSRF Token

Every form submission needs CSRF token:

```html
<!-- Inertia automatically includes this -->
<input type="hidden" name="_token" value="{{ csrf_token() }}">
```

---

## Multi-Tenant Session Context

### TenantContext Middleware

```php
// app/Http/Middleware/TenantContext.php
public function handle($request, $next)
{
    // Extract tenant from URL
    $tenant = Tenant::where('slug', $request->route('tenant'))->first();

    // Store in session
    session(['current_tenant_id' => $tenant->id]);
    session(['current_organisation_id' => $tenant->id]);

    // Set database connection
    DB::setPrimary($tenant->connection);

    return $next($request);
}
```

### Tenant Isolation in Session

Session is per-user, so:

```
User A logs in to tenant "organisation-a"
  ↓
Session contains: organisation_id = 1
  ↓
User A can only access organisation_id = 1 data

User B logs in to same tenant "organisation-a"
  ↓
Session contains: organisation_id = 1
  ↓
User B can access organisation_id = 1 data

User A cannot access User B's session
  ↓
No cross-user leakage (different session cookies)
```

---

## Security Considerations

### CSRF Protection

All form submissions are protected:

```php
// In Inertia forms
router.post('/endpoint', data, {
    // Inertia automatically adds:
    // X-CSRF-TOKEN header from meta tag
    // Accept header
    // X-Requested-With header
});
```

### Session Hijacking Prevention

```php
// Laravel session security features:
// 1. Session ID regeneration on login (prevents fixation)
// 2. IP validation (if enabled)
// 3. User agent validation (if enabled)
// 4. Secure cookie flags (HttpOnly, Secure, SameSite)
```

### Password Hashing

```php
// Passwords stored with bcrypt (salted hash)
$user->password = Hash::make($password);  // One-way hash

// Verification compares password to hash
Hash::check($inputPassword, $user->password);  // Constant-time comparison
```

### Email Verification Token

```php
// Verification link includes:
// 1. User ID (encrypted)
// 2. Email hash (sha256)
// 3. Expiration timestamp
// 4. HMAC signature

// All of this is signed with Laravel's APP_KEY
// Makes it impossible to forge verification links
```

---

## Common Issues

### Issue 1: Session Expires
**Symptom:** User gets logged out randomly

**Cause:** Session timeout (default 120 minutes)

**Solution:**
- Adjust `config/session.php` lifetime
- Or implement "remember me" functionality

### Issue 2: CSRF Token Mismatch
**Symptom:** "419 Page Expired" error

**Cause:**
- Form session is stale
- CSRF token changed
- Navigating back to old form

**Solution:**
- Refresh page before submitting
- Don't use browser back button

### Issue 3: Email Verification Link Expired
**Symptom:** "Invalid signature" when clicking email link

**Cause:** Link older than 24 hours

**Solution:**
- Request new verification email
- User should verify within 24 hours

### Issue 4: Multiple Browser Tabs
**Symptom:** Session becomes inconsistent across tabs

**Cause:** Browser caches different session data

**Solution:**
- Laravel handles this automatically
- Session is per-browser, shared across tabs
- Use `Cache` for tab-specific state if needed

---

## Testing Authentication

### Test File
`tests/Feature/Auth/DashboardResolverPriorityTest.php`

### Common Test Patterns

```php
/** @test */
public function unverified_user_cannot_access_dashboard()
{
    // User created but email not verified
    $user = User::factory()->create([
        'email_verified_at' => null,
    ]);

    // Try to access dashboard
    $response = $this->actingAs($user)->get(route('dashboard'));

    // Should be redirected to email verification
    $response->assertRedirect(route('verification.notice'));
}

/** @test */
public function verified_user_can_access_dashboard()
{
    // User with verified email
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    // Access dashboard
    $response = $this->actingAs($user)->get(route('dashboard'));

    // Should not be redirected for verification
    $this->assertNotNull($response);
}
```

---

## Configuration Files

### Email Configuration
`config/mail.php`
- SMTP settings
- From address
- Encryption

### Session Configuration
`config/session.php`
- Lifetime (in minutes)
- Timeout behavior
- Secure/HttpOnly flags

### Fortify Configuration
`config/fortify.php`
- Features (2FA, etc.)
- Redirects
- Confirmation timeouts

---

## Flow Diagram: Login to Dashboard

```
LOGIN FORM
    ↓
CREDENTIAL VALIDATION
    ├─ Invalid? → Show error
    └─ Valid? → Continue
                ↓
        CREATE SESSION
        Set auth cookie
                ↓
        LoginResponse called
                ↓
        Check email_verified_at
        ├─ NULL? → redirect to /email/verify
        └─ Set? → Continue
                 ↓
         DashboardResolver.resolve()
                 ↓
         Check 6 priorities
                 ↓
         Return RedirectResponse
                 ↓
         Browser follows redirect
                 ↓
         TenantContext middleware (if tenant route)
         Set organisation context
                 ↓
         CheckUserRole middleware (if role-protected)
         Validate user has required role
                 ↓
         Render target dashboard
                 ↓
         USER SEES DASHBOARD ✓
```

---

**Last Updated:** March 4, 2026

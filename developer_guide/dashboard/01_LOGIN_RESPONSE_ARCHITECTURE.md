# LoginResponse Architecture

## Overview

`LoginResponse` is the **entry point** that determines where users are redirected after successful authentication. It's the first decision point in the dashboard routing system.

**File:** `app/Http/Responses/LoginResponse.php`

---

## How It Works

### 1. User Logs In

When a user successfully authenticates (via `LoginController` or Fortify), Laravel calls the `LoginResponse` handler:

```php
// Inside Fortify's authentication flow
$this->showLoginResponse($request);  // Calls LoginResponse
```

### 2. LoginResponse.toResponse() Called

```php
class LoginResponse implements LoginResponse
{
    public function toResponse($request)
    {
        // User is now authenticated
        $user = $request->user();  // ✅ User exists

        // Email is NOT yet verified at this point
        // That's checked later by middleware

        return redirect(...);  // Redirect somewhere
    }
}
```

### 3. Decision Tree

```
LOGIN SUCCESSFUL
    ↓
Is user verified?
    ├─ NO → Redirect to email verification page
    └─ YES → Continue
             ↓
          Call DashboardResolver.resolve($user)
             ↓
          Get 6-priority routing decision
             ↓
          Redirect to appropriate dashboard
```

---

## Implementation Details

### Current Code Structure

```php
namespace App\Http\Responses;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LoginResponse
{
    public function toResponse($request): RedirectResponse
    {
        $user = $request->user();

        // Resolve dashboard based on user state
        $resolver = app(DashboardResolver::class);
        return $resolver->resolve($user);
    }
}
```

### Email Verification Check

Email verification is **NOT** checked in LoginResponse. It's checked in two places:

#### 1. Middleware Layer

```php
// routes/web.php
Route::middleware(['web', 'auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    // ... all dashboard routes
});
```

#### 2. DashboardResolver Method

```php
public function isFirstTimeUser($user): bool
{
    // Check email_verified_at
    if (!$user->email_verified_at) {
        return true;  // Treat as first-time user
    }
    // ...
}
```

---

## Routing Flow Diagram

```
┌──────────────────────────────┐
│  LOGIN FORM SUBMITTED        │
└──────────────┬───────────────┘
               ↓
┌──────────────────────────────┐
│  AUTHENTICATE USER           │
│  - Check credentials         │
│  - Log user in              │
└──────────────┬───────────────┘
               ↓
┌──────────────────────────────┐
│  LoginResponse.toResponse()   │
│  - Get authenticated user    │
│  - Call DashboardResolver    │
└──────────────┬───────────────┘
               ↓
┌──────────────────────────────────────────┐
│  DashboardResolver.resolve()             │
│  - Check 6 priorities                    │
│  - Return RedirectResponse               │
└──────────────┬───────────────────────────┘
               ↓
┌──────────────────────────────┐
│  USER REDIRECTED             │
│  to correct dashboard        │
└──────────────────────────────┘
```

---

## Registration (New Users)

When a new user registers:

1. **Fortify Registration Flow**
   - User provides email & password
   - Account created
   - Email verification sent
   - User redirected to verification page (not LoginResponse)

2. **After Email Verified**
   - User clicks verification link
   - Email marked as verified
   - User now logs in normally
   - LoginResponse routes them to dashboard

---

## Integration with DashboardResolver

LoginResponse **delegates** all routing decisions to `DashboardResolver`:

```php
class LoginResponse
{
    public function toResponse($request): RedirectResponse
    {
        // Just pass the decision to DashboardResolver
        $resolver = app(DashboardResolver::class);
        return $resolver->resolve($request->user());
    }
}
```

`DashboardResolver` handles the complex logic:
- Check active voting sessions
- Check active elections
- Check if user is new
- Check multiple roles
- Check single role
- Platform fallback

---

## Testing LoginResponse

### Test File
`tests/Feature/Auth/DashboardResolverPriorityTest.php`

### Key Test Pattern

```php
/** @test */
public function user_with_active_voting_is_routed_to_voting()
{
    // Arrange: User with active voting session
    $user = User::factory()->create([
        'email_verified_at' => now(),
        'onboarded_at' => now(),
    ]);

    // Create active voting session
    DB::table('voter_slugs')->insert([
        'user_id' => $user->id,
        'is_active' => true,
        'current_step' => 2,
        'expires_at' => now()->addDay(),
    ]);

    // Act: User logs in
    $response = $this->actingAs($user)->get(route('dashboard'));

    // Assert: Redirected to voting
    $response->assertRedirect();
    $this->assertStringContainsString('vote.start', $response->headers->get('Location'));
}
```

---

## Common Issues

### Issue 1: Redirect Loop
**Problem:** User keeps getting redirected to the same page

**Solution:** Check `DashboardResolver` priorities. A route being protected by middleware might be calling itself.

### Issue 2: "Undefined route"
**Problem:** `redirect()->route('unknown.route')` fails

**Solution:** Verify route exists in `routes/web.php` and check for typos

### Issue 3: Email Not Verified
**Problem:** User can't access dashboard even after logging in

**Solution:** Check if user ran email verification. Look at `users.email_verified_at` in database.

---

## Key Routes

| Route | Purpose | Middleware |
|-------|---------|-----------|
| `/login` | Login form | `guest` |
| `/email/verify` | Email verification | `verified:`,`email.verification.notice` |
| `/dashboard` | Dashboard entry point | `auth`, `verified` |
| `/dashboard/welcome` | New user onboarding | `auth`, `verified` |
| `/dashboard/roles` | Role selection | `auth`, `verified` |
| `/dashboard/admin` | Admin dashboard | `auth`, `verified`, `role:admin` |
| `/dashboard/commission` | Commission dashboard | `auth`, `verified`, `role:commission` |
| `/vote` | Voter dashboard | `auth`, `verified`, `role:voter` |

---

## Security Considerations

✅ **Email Verification Required** - Enforced at middleware AND at resolve() level

✅ **Authenticated User Only** - Can't reach LoginResponse without logging in

✅ **CSRF Protection** - Standard Laravel CSRF middleware protects login form

✅ **Rate Limiting** - Fortify includes login rate limiting

✅ **Tenant Context** - TenantContext middleware runs before routing

---

## Related Components

- **DashboardResolver** (next step) - Handles the actual routing logic
- **EnsureEmailIsVerified** middleware - Enforces email verification
- **User Model** - getDashboardRoles(), email_verified_at column
- **Fortify** - Laravel authentication scaffolding

---

## Future Improvements

1. **Two-Factor Authentication** - Add 2FA step before routing
2. **Session Validation** - Check session legitimacy before routing
3. **Device Trust** - Remember trusted devices
4. **Audit Trail** - Log every redirect decision with timestamp & IP

---

**Last Updated:** March 4, 2026

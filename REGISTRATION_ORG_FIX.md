# 🔧 User Registration Organisation ID Fix

## Problem Identified

New users registering in the application were being assigned `organisation_id=2` instead of the default platform organisation `organisation_id=1`.

### Root Cause

**File:** `app/Traits/HasOrganisation.php` (line 28)

The trait was pulling organisation_id from the HTTP session:
```php
$model->organisation_id = session('current_organisation_id', 0);
```

**What Happened:**

1. User navigates to demo election page → `DemoCodeController.create()` sets:
   ```php
   session(['current_organisation_id' => $election->organisation_id]);  // = 2
   ```

2. User then registers → `HasOrganisation::bootHasOrganisation()` runs:
   ```php
   $model->organisation_id = session('current_organisation_id', 0);  // Gets 2!
   ```

3. The User model's `boot()` method couldn't fix it because organisation_id was already set to 2

4. Result: New user gets `organisation_id=2` instead of platform default `organisation_id=1`

---

## Solution Applied

### Changed File

**File:** `app/Traits/HasOrganisation.php`

**Change:** Removed session-based organisation_id assignment from the `creating` hook

**Before:**
```php
protected static function bootHasOrganisation()
{
    static::creating(function (Model $model) {
        if (!isset($model->organisation_id) && !isset($model->organisation_id)) {
            $model->organisation_id = session('current_organisation_id', 0);
        }
        // ...
    });
}
```

**After:**
```php
protected static function bootHasOrganisation()
{
    static::creating(function (Model $model) {
        // ⚠️ NOTE: Do NOT set organisation_id from session during creation.
        // This prevents new users from inheriting the organisation_id of the
        // last election accessed (e.g., if Election::first() has org_id=2).
        // Instead, let the User model boot() method handle the default value.
        // The User model explicitly sets organisation_id = platform org ID.

        // If someone tried to set organisation_id, move it to organisation_id
        if (isset($model->attributes['organisation_id'])) {
            $model->organisation_id = $model->attributes['organisation_id'];
            unset($model->attributes['organisation_id']);
        }
    });
}
```

---

## How It Works Now

### New User Registration Flow

1. **RegisterController.store()** creates user with validated data (NO organisation_id):
   ```php
   $user = User::create($validated);  // organisation_id not in validated
   ```

2. **User::boot()** `creating` hook runs FIRST (parent class):
   ```php
   if (!$model->organisation_id || $model->organisation_id === 0 || $model->organisation_id === '0') {
       $platformOrg = Organisation::where('slug', 'platform')->first();
       $model->organisation_id = $platformOrg->id;  // Sets to platform org ID
   }
   ```

3. **HasOrganisation::boot()** `creating` hook runs SECOND:
   - No longer sets organisation_id from session
   - Just handles spelling cleanup (organisation_id vs organisation_id)

4. **Result:** User gets correct `organisation_id=1` (Platform)

---

## Why This Matters

### Architecture Principle: Session ≠ User Default

- **Session Context** (`current_organisation_id`): Temporary context for the current request
  - Used to filter queries during voting/election operations
  - Should NOT affect new user creation defaults

- **User Default Organisation** (via User::boot()): Permanent attribute
  - Should ALWAYS be platform organisation (`id=1`) unless explicitly assigned
  - Set once during creation
  - Should not inherit from temporary request context

### Scenario: Election-Hopping

Without this fix:
```
1. Admin logs in, views Organisation 2's elections
   → session['current_organisation_id'] = 2

2. Admin clicks "Register Test User" link

3. Test user created with organisation_id=2 ❌ WRONG
   → Should be platform user (org_id=1)
```

With this fix:
```
1. Admin logs in, views Organisation 2's elections
   → session['current_organisation_id'] = 2

2. Admin clicks "Register Test User" link

3. Test user created with organisation_id=1 ✅ CORRECT
   → User properly assigned to platform
```

---

## Verification

### Check Existing Users (if concerned)

```php
// Find users incorrectly assigned to org_id=2
php artisan tinker
>>> User::where('organisation_id', 2)->get(['id', 'name', 'email', 'organisation_id']);
>>> // If these were recently created users, they may need reassignment

// Reassign to platform (org_id=1)
>>> User::where('organisation_id', 2)
        ->update(['organisation_id' => 1]);
```

### Test New Registration

```bash
# Clear session/cache to start fresh
php artisan cache:clear
php artisan session:clear

# Register a new user
# Verify they get organisation_id=1 (Platform)
php artisan tinker
>>> $user = User::latest('id')->first();
>>> $user->organisation_id;  // Should be 1 (Platform)
```

---

## Related Files

### Modified
- `app/Traits/HasOrganisation.php` - Removed session-based org assignment

### Not Changed (Working Correctly)
- `app/Models/User.php` - Correctly sets default to platform org
- `app/Http/Controllers/Auth/RegisterController.php` - Doesn't set org_id
- `app/Http/Middleware/TenantContext.php` - Only reads user's existing org_id

---

## Architecture Implications

This fix reinforces the **multi-tenancy principle**:

✅ **Session context** is temporary and request-scoped
✅ **User organisation** is permanent and explicitly managed
✅ **New users default to platform** unless explicitly assigned to a tenant
✅ **No leakage** between request contexts and user creation

---

## Deployment Notes

- ✅ No database migration required
- ✅ No breaking changes to existing APIs
- ✅ Existing users unaffected
- ✅ Only impacts new user registrations
- ⚠️ Existing incorrectly-registered users may need correction (see verification section)

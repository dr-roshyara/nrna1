# 🚀 Backend Implementation - Step-by-Step

**Status**: Frontend ✅ Fixed | Backend ⏳ Ready to Implement
**Time Required**: 30-45 minutes
**Difficulty**: ⭐⭐⭐ Medium

---

## ✅ Prerequisites Completed

- [x] Frontend errors fixed
- [x] All packages installed
- [x] Browserslist updated
- [x] Frontend ready for testing
- [x] All documentation created

**You are ready to start backend implementation!**

---

## 📋 Implementation Checklist

### Phase 1: Create Backend Controller (5 min)

```bash
# Step 1: Create the file
touch app/Http/Controllers/Organizations/MemberImportController.php
```

**Then paste this code into the file:**

```php
<?php

namespace App\Http\Controllers\Organizations;

use App\Models\organisation;
use App\Models\User;
use Illuminate\Http\Request;

class MemberImportController extends Controller
{
    /**
     * Handle member import
     * POST /organizations/{organisation}/members/import
     */
    public function store(Request $request, organisation $organisation)
    {
        // 1. Authorize
        $this->authorize('manage', $organisation);

        // 2. Validate request
        $validated = $request->validate([
            'headers' => 'required|array|min:1',
            'rows' => 'required|array|min:1',
            'fileName' => 'required|string|max:255',
        ]);

        // 3. Validate data
        $validation = $this->validateMemberData($validated['rows']);
        if (!$validation['valid']) {
            return response()->json([
                'success' => false,
                'errors' => $validation['errors'],
                'message' => 'Validation failed',
            ], 422);
        }

        // 4. Import members
        $stats = $this->importMembers($organisation, $validated['rows']);

        // 5. Return success
        return response()->json([
            'success' => true,
            'imported_count' => $stats['imported'],
            'skipped_count' => $stats['skipped'],
            'message' => "{$stats['imported']} members imported successfully",
        ]);
    }

    /**
     * Validate member data
     */
    private function validateMemberData(array $rows): array
    {
        $errors = [];
        $emails = [];

        foreach ($rows as $index => $row) {
            $rowNum = $index + 2;
            $email = $row['Email'] ?? $row['email'] ?? null;

            if (!$email) {
                $errors[] = "Row {$rowNum}: Email required";
                continue;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Row {$rowNum}: Invalid email: {$email}";
                continue;
            }

            if (in_array(strtolower($email), $emails)) {
                $errors[] = "Row {$rowNum}: Duplicate: {$email}";
                continue;
            }

            $emails[] = strtolower($email);
        }

        return [
            'valid' => count($errors) === 0,
            'errors' => $errors
        ];
    }

    /**
     * Import members into database
     */
    private function importMembers(organisation $organisation, array $rows): array
    {
        $imported = 0;
        $skipped = 0;

        foreach ($rows as $row) {
            try {
                $email = $row['Email'] ?? $row['email'] ?? null;

                if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $skipped++;
                    continue;
                }

                // Create or find user
                $user = User::firstOrCreate(
                    ['email' => $email],
                    [
                        'name' => trim(($row['First Name'] ?? 'Member') . ' ' . ($row['Last Name'] ?? '')),
                        'password' => bcrypt(str()->random(32)),
                        'email_verified_at' => now(),
                    ]
                );

                // Attach to organisation if not already attached
                if (!$organisation->users()->where('user_id', $user->id)->exists()) {
                    $organisation->users()->attach($user->id, [
                        'role' => 'member',
                        'assigned_at' => now(),
                    ]);
                    $imported++;
                } else {
                    $skipped++;
                }

            } catch (\Exception $e) {
                $skipped++;
                \Log::error('Member import error', ['error' => $e->getMessage()]);
            }
        }

        return [
            'imported' => $imported,
            'skipped' => $skipped,
            'total' => count($rows)
        ];
    }
}
```

**Status**: ✅ Step 1 Complete

---

### Phase 2: Create Authorization Policy (3 min)

```bash
# Step 2: Create the file
touch app/Policies/OrganizationPolicy.php
```

**Then paste this code:**

```php
<?php

namespace App\Policies;

use App\Models\organisation;
use App\Models\User;

class OrganizationPolicy
{
    /**
     * Check if user can manage organisation (import members)
     */
    public function manage(User $user, organisation $organisation): bool
    {
        return $organisation->users()
            ->where('user_id', $user->id)
            ->whereIn('role', ['admin', 'manager', 'staff'])
            ->exists();
    }

    /**
     * Check if user can view organisation
     */
    public function view(User $user, organisation $organisation): bool
    {
        return $organisation->users()
            ->where('user_id', $user->id)
            ->exists();
    }
}
```

**Status**: ✅ Step 2 Complete

---

### Phase 3: Add Route (2 min)

**File**: `routes/web.php`

Find the authenticated routes section and add:

```php
// Add this in the authenticated middleware group
Route::post('/organizations/{organisation}/members/import',
    [App\Http\Controllers\Organizations\MemberImportController::class, 'store'])
    ->name('organizations.members.import.store');
```

**Status**: ✅ Step 3 Complete

---

### Phase 4: Create Database Migration (5 min)

```bash
# Step 4: Generate migration
php artisan make:migration create_user_organization_roles_table
```

**Then open the generated file and paste:**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_organization_roles', function (Blueprint $table) {
            $table->id();

            // Foreign keys
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('organisation_id');

            // Role assignment
            $table->enum('role', ['admin', 'manager', 'staff', 'member', 'voter'])
                ->default('member');

            // Optional metadata
            $table->string('region')->nullable();
            $table->timestamp('assigned_at')->useCurrent();

            // Timestamps
            $table->timestamps();

            // Indexes & Constraints
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('organisation_id')->references('id')->on('organizations')->cascadeOnDelete();
            $table->unique(['user_id', 'organisation_id']);
            $table->index(['organisation_id', 'role']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_organization_roles');
    }
};
```

**Status**: ✅ Step 4 Complete

---

### Phase 5: Update Models (10 min)

#### Step 5A: Update organisation Model

**File**: `app/Models/organisation.php`

Add this relationship method:

```php
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class organisation extends Model
{
    // ... existing code ...

    /**
     * Get all users in this organisation
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_organization_roles')
            ->withPivot('role', 'region', 'assigned_at')
            ->withTimestamps();
    }
}
```

#### Step 5B: Update User Model

**File**: `app/Models/User.php`

Add this relationship method:

```php
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    // ... existing code ...

    /**
     * Get organizations this user belongs to
     */
    public function organizations(): BelongsToMany
    {
        return $this->belongsToMany(organisation::class, 'user_organization_roles')
            ->withPivot('role', 'region', 'assigned_at')
            ->withTimestamps();
    }
}
```

**Status**: ✅ Step 5 Complete

---

### Phase 6: Run Database Migration (2 min)

```bash
# Step 6: Run the migration
php artisan migrate
```

**Expected Output:**:
```
Migrating: YYYY_MM_DD_HHMMSS_create_user_organization_roles_table
Migrated: YYYY_MM_DD_HHMMSS_create_user_organization_roles_table (0.XX seconds)
```

**Status**: ✅ Step 6 Complete

---

## 🧪 Testing (15 min)

### Test 1: Create Test CSV

Create a file named `test_members.csv`:

```
Email,First Name,Last Name
john@example.com,John,Doe
jane@example.com,Jane,Smith
bob@example.com,Bob,Johnson
```

### Test 2: Manual Browser Test

1. Navigate to: `http://localhost/organizations/{slug}/members/import`
2. Select `test_members.csv`
3. Review preview (should show 3 rows, no errors)
4. Click "Import"
5. **Expected**: Success message: "3 members imported successfully"

### Test 3: Verify Database

```bash
# Check users created
php artisan tinker

>>> User::where('email', 'like', '%example.com%')->get()
# Should show 3 users created

>>> organisation::find(1)->users()->count()
# Should show 3 users attached to organisation
```

### Test 4: Test Validation Error

1. Create invalid CSV with bad email:
```
Email,First Name
invalid-email,Test
```

2. Upload file
3. **Expected**: Error message in preview: "Invalid email format"
4. Import button should be disabled

### Test 5: Test Authorization

1. Logout and login as non-admin user
2. Try to access import page
3. **Expected**: 403 Unauthorized error

---

## ✅ Success Criteria

After implementation, verify:

```
✅ Can navigate to import page
✅ Can select CSV file
✅ Can see preview table
✅ Can click Import button
✅ Members appear in database
✅ Success message displays
✅ Error messages work correctly
✅ Non-admin users get 403
✅ Duplicate emails handled
✅ All 3 languages work
```

---

## 🎯 What Happens Now

```
USER FLOW:
1. Admin navigates to /organizations/{slug}/members/import
2. Selects CSV file
3. Frontend validates & shows preview ✅ (Already working)
4. Admin clicks Import
5. Frontend sends POST to backend ✅ (Already working)
   ↓
6. YOUR NEW CONTROLLER receives request ← YOU BUILD THIS
7. Controller validates data (server-side)
8. Controller creates users
9. Controller attaches to organisation
10. Controller returns success response
   ↓
11. Frontend receives response ✅ (Already working)
12. Frontend shows success screen ✅ (Already working)
```

---

## ⏱️ Time Summary

| Phase | Time | Status |
|-------|------|--------|
| Controller | 5 min | ⏳ TODO |
| Policy | 3 min | ⏳ TODO |
| Route | 2 min | ⏳ TODO |
| Migration | 5 min | ⏳ TODO |
| Models | 10 min | ⏳ TODO |
| Database | 2 min | ⏳ TODO |
| **TOTAL** | **~30 min** | ⏳ TODO |

---

## 📖 If You Get Stuck

| Error | Solution |
|-------|----------|
| "Can't find MemberImportController" | Check file path and namespace |
| "403 Unauthorized" | User must be organisation admin in user_organization_roles table |
| "Members not created" | Check migration ran successfully (php artisan migrate) |
| "CSRF Token error" | Frontend already handles this (not needed) |
| "Database error" | Check foreign key constraints and column names |

---

## 🚀 You're Ready!

Everything is prepared. Follow the 6 phases above in order:

1. Create controller (5 min)
2. Create policy (3 min)
3. Add route (2 min)
4. Create migration (5 min)
5. Update models (10 min)
6. Run migration (2 min)

**Total: 30 minutes to complete implementation!**

---

**Start Now!** Follow the phases above step by step.

When complete, test with `test_members.csv`

Estimated Total Time: 45 minutes (implementation + testing)

Good luck! 🎉

# ⚡ Member Import - Quick Implementation Kit

Copy-paste ready code to complete the member import feature in 30 minutes.

---

## 🚀 5-Step Implementation

### Step 1: Create Controller (5 min)

Create: `/app/Http/Controllers/Organizations/MemberImportController.php`

```php
<?php

namespace App\Http\Controllers\Organizations;

use App\Models\organisation;
use App\Models\User;
use Illuminate\Http\Request;

class MemberImportController extends Controller
{
    public function store(Request $request, organisation $organisation)
    {
        // Authorize
        $this->authorize('manage', $organisation);

        // Validate
        $validated = $request->validate([
            'headers' => 'required|array|min:1',
            'rows' => 'required|array|min:1',
            'fileName' => 'required|string|max:255',
        ]);

        // Validate data
        $validation = $this->validateMemberData($validated['rows']);
        if (!$validation['valid']) {
            return response()->json([
                'success' => false,
                'errors' => $validation['errors'],
                'message' => 'Validation failed',
            ], 422);
        }

        // Import
        $stats = $this->importMembers($organisation, $validated['rows']);

        return response()->json([
            'success' => true,
            'imported_count' => $stats['imported'],
            'skipped_count' => $stats['skipped'],
            'message' => "{$stats['imported']} members imported successfully",
        ]);
    }

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

    private function importMembers(organisation $organisation, array $rows): array
    {
        $imported = 0;
        $skipped = 0;

        foreach ($rows as $row) {
            $email = $row['Email'] ?? $row['email'] ?? null;

            if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $skipped++;
                continue;
            }

            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => ($row['First Name'] ?? 'Member') . ' ' . ($row['Last Name'] ?? ''),
                    'password' => bcrypt(str()->random(32)),
                    'email_verified_at' => now(),
                ]
            );

            if (!$organisation->users()->where('user_id', $user->id)->exists()) {
                $organisation->users()->attach($user->id, [
                    'role' => 'member',
                    'assigned_at' => now(),
                ]);
                $imported++;
            } else {
                $skipped++;
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

---

### Step 2: Create Policy (3 min)

Create: `/app/Policies/OrganizationPolicy.php`

```php
<?php

namespace App\Policies;

use App\Models\organisation;
use App\Models\User;

class OrganizationPolicy
{
    public function manage(User $user, organisation $organisation): bool
    {
        return $organisation->users()
            ->where('user_id', $user->id)
            ->whereIn('role', ['admin', 'manager', 'staff'])
            ->exists();
    }

    public function view(User $user, organisation $organisation): bool
    {
        return $organisation->users()
            ->where('user_id', $user->id)
            ->exists();
    }
}
```

---

### Step 3: Add Route (2 min)

Update: `/routes/web.php`

Add this route in the authenticated middleware group:

```php
Route::post('/organizations/{organisation}/members/import',
    [App\Http\Controllers\Organizations\MemberImportController::class, 'store'])
    ->name('organizations.members.import.store');
```

---

### Step 4: Create Migration (5 min)

Run: `php artisan make:migration create_user_organization_roles_table`

Then replace content with:

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
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('organisation_id');
            $table->enum('role', ['admin', 'manager', 'staff', 'member', 'voter'])->default('member');
            $table->string('region')->nullable();
            $table->timestamp('assigned_at')->useCurrent();
            $table->timestamps();

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

Run: `php artisan migrate`

---

### Step 5: Update Models (10 min)

Update: `/app/Models/organisation.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class organisation extends Model
{
    protected $fillable = ['name', 'email', 'slug'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_organization_roles')
            ->withPivot('role', 'region', 'assigned_at')
            ->withTimestamps();
    }
}
```

Update: `/app/Models/User.php`

```php
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    protected $fillable = ['name', 'email', 'password', 'phone', 'email_verified_at'];
    protected $hidden = ['password', 'remember_token'];

    public function organizations(): BelongsToMany
    {
        return $this->belongsToMany(organisation::class, 'user_organization_roles')
            ->withPivot('role', 'region', 'assigned_at')
            ->withTimestamps();
    }
}
```

---

## ✅ Testing Steps

### Test 1: Valid Import
```bash
# 1. Login as admin
# 2. Go to /organizations/{slug}/members/import
# 3. Create test.csv:
Email,First Name,Last Name
john@example.com,John,Doe
jane@example.com,Jane,Smith

# 4. Upload
# 5. Should see: "2 members imported successfully"
```

### Test 2: Validation Error
```bash
# 1. Create test.csv with invalid email:
Email,First Name
invalid-email,John

# 2. Upload
# 3. Should see error: "Invalid email format"
```

### Test 3: Duplicate
```bash
# 1. First import john@example.com
# 2. Import again same email
# 3. Should show: "Already exists - skipped"
```

---

## 📋 Quick Checklist

- [ ] Create MemberImportController.php
- [ ] Create OrganizationPolicy.php
- [ ] Add route to routes/web.php
- [ ] Create & run migration
- [ ] Update organisation model
- [ ] Update User model
- [ ] Test with sample CSV
- [ ] Test authorization
- [ ] Test validation errors

---

## 🔍 Verify Installation

```bash
# 1. Check controller exists
ls app/Http/Controllers/Organizations/MemberImportController.php

# 2. Check migration ran
php artisan tinker
>>> Schema::hasTable('user_organization_roles')
true

# 3. Test import endpoint
curl -X POST \
  http://localhost/organizations/test/members/import \
  -H "Content-Type: application/json" \
  -d '{
    "headers": ["Email"],
    "rows": [{"Email": "test@example.com"}],
    "fileName": "test.csv"
  }'
```

---

## 🐛 Common Issues & Fixes

| Issue | Fix |
|-------|-----|
| 404 Not Found | Add route to `/routes/web.php` |
| 403 Unauthorized | Check user has admin role in `user_organization_roles` |
| CSRF Token Mismatch | Frontend already handles it with `useCsrfRequest()` |
| Members not saving | Check migration ran: `php artisan migrate` |
| Duplicate members created | Check `unique()` constraint in migration |

---

## 📊 What Happens When User Imports

```
1. User navigates: /organizations/{slug}/members/import
2. Frontend page renders (Import.vue) ✅ Already done
3. User selects CSV file
4. Frontend validates & parses file (useMemberImport.js) ✅ Already done
5. Frontend shows preview
6. User clicks "Import"
7. Frontend sends POST to /organizations/{slug}/members/import ← YOUR CODE
   ↓
8. Controller validates data again
9. Controller re-validates emails
10. Controller creates User records
11. Controller attaches to organisation
12. Returns success response
13. Frontend shows success screen ✅ Already done
```

---

## 📦 Package Structure

After implementation, your directories will have:

```
app/Http/Controllers/Organizations/
├── MemberImportController.php ← NEW

app/Policies/
├── OrganizationPolicy.php ← NEW

app/Models/
├── organisation.php (UPDATED)
├── User.php (UPDATED)

database/migrations/
├── YYYY_MM_DD_create_user_organization_roles_table.php ← NEW

routes/
├── web.php (UPDATED)
```

---

## 🎯 Success Criteria

When you're done, verify:

- [ ] Can navigate to import page
- [ ] Can select CSV file
- [ ] Can see preview
- [ ] Can click Import button
- [ ] Members appear in database
- [ ] Multiple organizations work independently
- [ ] Non-admin users cannot import
- [ ] Duplicate emails handled correctly

---

**Time to Complete**: 30 minutes
**Difficulty**: ⭐⭐⭐ (Medium)
**Frontend Status**: ✅ 100% Complete
**Backend Status**: 0% → 100% (After implementing above)

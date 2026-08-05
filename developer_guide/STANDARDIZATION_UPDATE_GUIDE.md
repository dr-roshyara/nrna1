# 📋 Model Standardization: organisation → Organisation Update Guide

**Status:** Migration complete, Model files created, References to update

---

## ✅ Completed

1. ✅ Created `app/Models/Organisation.php` with British spelling
2. ✅ Created migration to rename pivot table: `user_organization_roles` → `user_organisation_roles`
3. ✅ Updated User model:
   - ✅ Added `use HasOrganisation;` trait
   - ✅ Renamed `organizationRoles()` → `organisationRoles()`
   - ✅ Updated pivot table reference to `user_organisation_roles`
   - ✅ Renamed `isOrganizationAdmin()` → `isOrganisationAdmin()`
   - ✅ Renamed `isOrganizationVoter()` → `isOrganisationVoter()`

---

## ⏳ Still To Update

### Files Requiring organisation → Organisation Update

**Controllers:**
1. `app/Console/Commands/SetupDemoElection.php` - Line with `organisation::find()`
2. `app/Http/Controllers/Api/OrganizationController.php` - Multiple refs
3. `app/Http/Controllers/Auth/LoginController.php` - Import and usage
4. `app/Http/Controllers/MemberController.php` - Usage
5. `app/Http/Controllers/Organizations/MemberImportController.php` - Multiple refs
6. `app/Http/Controllers/SitemapController.php` - Usage
7. `app/Http/Middleware/EnsureOrganizationMember.php` - Usage and import

**Services:**
8. `app/Services/DashboardResolver.php` - Import and usage
9. `app/Services/DemoElectionResolver.php` - Usage

**Models:**
10. `app/Models/organisation.php` - ⚠️ Keep alongside Organisation.php for now (can delete after verifying no refs)

---

## 🔧 Update Pattern

For each file, replace:

```php
// OLD (American)
use App\Models\organisation;
$var = organisation::find($id);
$table->references('organizations', 'organisation_id');

// NEW (British)
use App\Models\Organisation;
$var = Organisation::find($id);
$table->references('organisations', 'organisation_id');
```

---

## 📝 Manual Update Instructions

Since there are 9 files to update across controllers, services, and middleware, here are your options:

### Option A: Automated (Using Bash sed)
```bash
# Find and replace organisation with Organisation in app/ (excluding tests)
find app -name "*.php" ! -path "*/Tests/*" -type f -exec sed -i 's/organisation::/Organisation::/g' {} \;
find app -name "*.php" ! -path "*/Tests/*" -type f -exec sed -i 's/use App\\Models\\organisation;/use App\\Models\\Organisation;/g' {} \;
find app -name "*.php" ! -path "*/Tests/*" -type f -exec sed -i "s/'organizations'/'organisations'/g" {} \;
find app -name "*.php" ! -path "*/Tests/*" -type f -exec sed -i "s/->references('organizations'/->references('organisations'/g" {} \;
```

### Option B: Manual (Using Claude Edit Tool)
Update each file individually using the Edit tool for precise control.

### Option C: Create Alias Class
Keep `organisation.php` and have it extend/alias `Organisation` for backward compatibility:
```php
<?php
namespace App\Models;

class organisation extends Organisation {}
```

---

## 🎯 Next Steps

1. Choose update method (A, B, or C above)
2. Run full test suite to verify no breakage
3. Delete original `organisation.php` (or keep if using Option C)
4. Update migrations that reference `organizations` table (if any)

---

## 📊 Impact Assessment

- **Breaking Changes:** Yes (class names, method names)
- **Database Schema:** Not affected (already standardized)
- **Pivot Table:** Already renamed to `user_organisations`
- **Controllers:** 7 files to update
- **Services:** 2 files to update
- **Total Files:** ~9-10 files

---

## ✨ Benefits After Complete

- ✅ 100% British English spelling throughout
- ✅ Consistent naming across codebase
- ✅ Single source of truth (Organisation model)
- ✅ No duplicate/aliased models
- ✅ Production-ready and maintainable


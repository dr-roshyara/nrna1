# Members Index Page - Complete Implementation Guide

**Project**: Public Digit Election Platform
**Module**: Organization Member Management
**Feature**: Organization-Scoped Member List
**Date**: 2026-02-23
**Status**: Ready for Implementation

---

## 📋 Table of Contents

1. [Overview](#overview)
2. [Requirements](#requirements)
3. [Architecture Design](#architecture-design)
4. [Implementation Steps](#implementation-steps)
5. [Code Examples](#code-examples)
6. [Testing Guide](#testing-guide)
7. [Deployment](#deployment)

---

## Overview

### What We're Building

A dedicated Members index page that displays all members belonging to the current user's organization. This page will be similar to the existing `users/index` page but with significant improvements focused on organization-scoped filtering and better user experience.

### URL
```
http://localhost:8000/members/index
```

### Key Features
- ✅ Organization-scoped member list (shows only current org members)
- ✅ Role-based filtering (admin, commission, voter)
- ✅ Advanced search (name, email)
- ✅ Sortable columns
- ✅ Pagination (20 per page)
- ✅ Member statistics dashboard
- ✅ CSV export functionality
- ✅ Responsive design
- ✅ Server-side authorization

---

## Requirements

### Functional Requirements

1. **Organization Filtering** (Critical)
   - MUST show only members of current user's organization
   - MUST prevent cross-organization data access
   - MUST handle session-based organization switching

2. **Search & Filter**
   - Filter by member name
   - Filter by email address
   - Filter by role (admin/commission/voter)
   - Debounced search (300ms delay)

3. **Sorting**
   - Sort by: ID, Name, Email, Role, Member Since
   - Toggle between ascending/descending
   - Visual indicators for active sort

4. **Display**
   - Table columns: ID, Name, Email, Region, Role, Member Since
   - Color-coded role badges
   - Organization context header
   - Member statistics cards

5. **Pagination**
   - 20 members per page
   - Previous/Next navigation
   - Page counter display

6. **Export**
   - CSV download of filtered members
   - Include all current filters

### Non-Functional Requirements

1. **Performance**
   - Page load < 1 second
   - Search response < 300ms
   - Efficient database queries

2. **Security**
   - Server-side authorization checks
   - Input validation on all filters
   - Prevent SQL injection
   - CSRF protection

3. **Usability**
   - Mobile responsive
   - Clear error messages
   - Loading states
   - Empty state handling

---

## Architecture Design

### MVC Structure

```
┌─────────────────────────────────────────────────────┐
│                    Browser                          │
│  http://localhost:8000/members/index                │
└──────────────────┬──────────────────────────────────┘
                   │
                   ▼
┌─────────────────────────────────────────────────────┐
│              Routes (web.php)                       │
│  Route::get('/members/index', MemberController)     │
└──────────────────┬──────────────────────────────────┘
                   │
                   ▼
┌─────────────────────────────────────────────────────┐
│          MemberController::index()                  │
│  1. Get current user's organization                 │
│  2. Check user is member                            │
│  3. Query organization members                      │
│  4. Apply filters & sorting                         │
│  5. Paginate results                                │
│  6. Return Inertia response                         │
└──────────────────┬──────────────────────────────────┘
                   │
                   ▼
┌─────────────────────────────────────────────────────┐
│            Database Layer                           │
│  - organizations table                              │
│  - users table                                      │
│  - user_organization_roles pivot                    │
└──────────────────┬──────────────────────────────────┘
                   │
                   ▼
┌─────────────────────────────────────────────────────┐
│          Inertia.js Response                        │
│  Members/Index.vue rendered with:                   │
│  - members (paginated)                              │
│  - organization                                     │
│  - filters                                          │
│  - stats                                            │
└─────────────────────────────────────────────────────┘
```

### Data Flow

```
User Action          Frontend            Backend              Database
─────────┐              │                   │                    │
1. Visit  │──────────▶  │                   │                    │
  /members│             │ GET /members      │                    │
         │             │ ─────────────────▶ │                    │
         │             │                    │ Query org members  │
         │             │                    │ ─────────────────▶ │
         │             │                    │ ◀───────────────── │
         │             │ ◀───────────────── │                    │
         │             │ Render Index.vue   │                    │
         │ ◀──────────  │                   │                    │
         │              │                   │                    │
2. Search│──────────▶   │                   │                    │
  "John" │              │ Debounce 300ms    │                    │
         │              │ GET /members?name=│                    │
         │              │ ─────────────────▶│                    │
         │              │                   │ WHERE name LIKE    │
         │              │                   │ ─────────────────▶ │
         │              │                   │ ◀───────────────── │
         │              │ ◀─────────────────│                    │
         │ ◀──────────  │ Update table      │                    │
```

---

## Implementation Steps

### Step 1: Create MemberController

**File**: `app/Http/Controllers/MemberController.php`

**Location**: Create new file

**Content**: See [Code Examples](#membercontroller-implementation) section

**Key Points**:
- Extends base Controller
- Uses Inertia for rendering
- Organization membership validation
- Query building with filters
- Pagination

**Time**: 15 minutes

### Step 2: Create Members/Index.vue

**File**: `resources/js/Pages/Members/Index.vue`

**Location**: Create new directory and file

**Content**: See [Code Examples](#vue-component-implementation) section

**Key Points**:
- Uses ElectionLayout
- Responsive design with Tailwind CSS
- Debounced search with Lodash
- Sortable table headers
- Color-coded role badges

**Time**: 45 minutes

### Step 3: Add Route

**File**: `routes/web.php`

**Location**: Add to existing auth middleware group

**Code**:
```php
Route::middleware(['auth'])->group(function () {
    // ... existing routes ...

    // Members management
    Route::get('/members/index', [App\Http\Controllers\MemberController::class, 'index'])
         ->name('members.index');
});
```

**Time**: 5 minutes

### Step 4: Test Implementation

Run comprehensive tests as outlined in [Testing Guide](#testing-guide) section.

**Time**: 20 minutes

---

## Code Examples

### MemberController Implementation

```php
<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MemberController extends Controller
{
    /**
     * Display members of current user's organization
     *
     * GET /members/index
     */
    public function index(Request $request)
    {
        // Validate query parameters
        $request->validate([
            'direction' => 'in:asc,desc',
            'field' => 'in:id,name,email,role,assigned_at,created_at',
        ]);

        // Get current user and organization
        $user = auth()->user();
        $organizationId = session('current_organisation_id') ?? $user->organisation_id;

        if (!$organizationId) {
            return redirect()->route('dashboard')
                ->with('error', 'No organization selected. Please select an organization first.');
        }

        $organization = Organization::findOrFail($organizationId);

        // Authorization check
        $isMember = $organization->users()
            ->where('users.id', $user->id)
            ->exists();

        if (!$isMember) {
            abort(403, 'You do not have access to this organization.');
        }

        // Build query for organization members with pivot data
        $query = $organization->users()
            ->withPivot(['role', 'permissions', 'assigned_at']);

        // Apply search filters
        if ($request->filled('name')) {
            $query->where('users.name', 'LIKE', '%' . $request->name . '%');
        }

        if ($request->filled('email')) {
            $query->where('users.email', 'LIKE', '%' . $request->email . '%');
        }

        if ($request->filled('role')) {
            $query->wherePivot('role', $request->role);
        }

        // Apply sorting
        $field = $request->input('field', 'assigned_at');
        $direction = $request->input('direction', 'desc');

        if (in_array($field, ['role', 'assigned_at'])) {
            // Sort by pivot column
            $query->orderByPivot($field, $direction);
        } else {
            // Sort by user table column
            $query->orderBy('users.' . $field, $direction);
        }

        // Paginate results
        $members = $query->paginate(20)->withQueryString();

        // Transform members to include pivot data
        $members->getCollection()->transform(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'state' => $user->state,
                'created_at' => $user->created_at,
                'role' => $user->pivot->role,
                'assigned_at' => $user->pivot->assigned_at,
            ];
        });

        // Calculate statistics
        $stats = [
            'total_members' => $organization->users()->count(),
            'admins_count' => $organization->admins()->count(),
            'commission_count' => $organization->commissionMembers()->count(),
            'voters_count' => $organization->voters()->count(),
        ];

        return Inertia::render('Members/Index', [
            'members' => $members,
            'organization' => [
                'id' => $organization->id,
                'name' => $organization->name,
                'slug' => $organization->slug,
            ],
            'filters' => $request->only(['name', 'email', 'role', 'field', 'direction']),
            'currentUser' => $user,
            'stats' => $stats,
        ]);
    }
}
```

### Vue Component Implementation

See separate file: `VUE_COMPONENT_CODE.md`

---

## Testing Guide

### Manual Testing Checklist

#### 1. Route Access Test
```
□ Navigate to http://localhost:8000/members/index
□ Page loads without errors
□ Requires authentication (redirects if not logged in)
```

#### 2. Organization Filtering Test
```
□ Login as user in Organization A
□ Navigate to /members/index
□ Only see members from Organization A
□ Switch to Organization B (if multi-org user)
□ Only see members from Organization B
□ Verify no cross-organization data leakage
```

#### 3. Search & Filter Test
```
□ Enter name in "Search by Name" field
□ Results update after 300ms
□ Results match search term
□ Clear search and verify all members show
□ Search by email
□ Results update correctly
□ Select role filter
□ Only members with that role show
```

#### 4. Sorting Test
```
□ Click "ID" column header
□ Members sort by ID ascending
□ Click again
□ Members sort by ID descending
□ Repeat for each sortable column
□ Visual indicator (arrow) shows active sort
```

#### 5. Pagination Test
```
□ If >20 members, pagination controls show
□ Click "Next"
□ Page 2 members display
□ Page counter updates
□ Click "Previous"
□ Returns to page 1
□ Filters persist across pages
```

#### 6. Statistics Test
```
□ Verify Total Members count is correct
□ Verify Admins count matches role filter
□ Verify Voters count matches role filter
□ Add new member
□ Refresh page
□ Counts update correctly
```

#### 7. Permission Test
```
□ Login as user NOT in any organization
□ Try to access /members/index
□ Receive 403 error
□ Login as member of Org A
□ Try to access Org B members via URL manipulation
□ Receive 403 error
```

### Automated Test Example

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Organization;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MemberIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_members_index_requires_authentication()
    {
        $response = $this->get('/members/index');
        $response->assertRedirect('/login');
    }

    public function test_members_index_shows_only_organization_members()
    {
        // Create two organizations
        $orgA = Organization::factory()->create();
        $orgB = Organization::factory()->create();

        // Create users for each org
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        // Attach users to their organizations
        $orgA->users()->attach($userA->id, ['role' => 'admin', 'assigned_at' => now()]);
        $orgB->users()->attach($userB->id, ['role' => 'admin', 'assigned_at' => now()]);

        // Add more members to orgA
        $members = User::factory()->count(5)->create();
        foreach ($members as $member) {
            $orgA->users()->attach($member->id, ['role' => 'voter', 'assigned_at' => now()]);
        }

        // Login as userA
        $this->actingAs($userA);
        session(['current_organisation_id' => $orgA->id]);

        // Access members index
        $response = $this->get('/members/index');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Members/Index')
            ->has('members.data', 6) // 1 admin + 5 voters
            ->where('organization.id', $orgA->id)
        );
    }

    public function test_members_index_filters_by_name()
    {
        $org = Organization::factory()->create();
        $user = User::factory()->create(['name' => 'Admin User']);
        $john = User::factory()->create(['name' => 'John Doe']);
        $jane = User::factory()->create(['name' => 'Jane Smith']);

        $org->users()->attach($user->id, ['role' => 'admin', 'assigned_at' => now()]);
        $org->users()->attach($john->id, ['role' => 'voter', 'assigned_at' => now()]);
        $org->users()->attach($jane->id, ['role' => 'voter', 'assigned_at' => now()]);

        $this->actingAs($user);
        session(['current_organisation_id' => $org->id]);

        $response = $this->get('/members/index?name=John');

        $response->assertInertia(fn ($page) => $page
            ->has('members.data', 1)
            ->where('members.data.0.name', 'John Doe')
        );
    }
}
```

---

## Deployment

### Pre-Deployment Checklist

```
□ All tests passing
□ Code reviewed
□ Documentation updated
□ No console errors in browser
□ Performance verified (< 1s load time)
□ Mobile responsiveness verified
□ Cross-browser tested (Chrome, Firefox, Safari)
```

### Deployment Steps

```bash
# 1. Merge to main branch
git checkout main
git merge feature/members-index

# 2. Run tests
php artisan test --filter=MemberIndexTest

# 3. Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# 4. Compile frontend assets
npm run production

# 5. Deploy to server
# (Follow your deployment process)

# 6. Verify in production
curl -I https://yoursite.com/members/index
```

### Rollback Plan

If issues occur:
```bash
# 1. Revert commit
git revert HEAD

# 2. Redeploy previous version
git push origin main

# 3. Clear caches again
php artisan cache:clear

# 4. Rebuild assets
npm run production
```

---

## Troubleshooting

### Common Issues

**Issue**: "No organization selected" error
**Solution**: Verify session management and ensure `current_organisation_id` is set

**Issue**: Members from all organizations showing
**Solution**: Check authorization logic in controller, verify pivot table query

**Issue**: Search not working
**Solution**: Check debounce timing, verify network requests in browser DevTools

**Issue**: Sorting not working
**Solution**: Verify sort field is in allowed list, check SQL query in logs

**Issue**: 403 Forbidden error
**Solution**: Check user is actually a member of the organization

---

## Support

For issues or questions:
1. Check this documentation
2. Review the [Code Analysis](#) document
3. Consult the main CLAUDE.md architecture guide
4. Review User/Index.vue for pattern reference

---

**Last Updated**: 2026-02-23
**Status**: Ready for Implementation
**Estimated Time**: 90 minutes

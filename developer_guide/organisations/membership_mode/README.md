# Membership Mode System — Developer Guide

## Overview

The Membership Mode System enables organisations to choose between two voter eligibility models:

- **Full Membership Mode** (`uses_full_membership = true`): Voters must be formal members with paid or exempt fees
- **Election-Only Mode** (`uses_full_membership = false`): Any registered user in the organisation can vote

This guide covers architecture, implementation details, and common development tasks.

---

## Table of Contents

1. [Architecture](#architecture)
2. [Database Schema](#database-schema)
3. [Core Components](#core-components)
4. [API Endpoints](#api-endpoints)
5. [Frontend Implementation](#frontend-implementation)
6. [Common Tasks](#common-tasks)
7. [Testing](#testing)
8. [Troubleshooting](#troubleshooting)

---

## Architecture

### Design Principle: Single Boolean Flag

The system uses a single boolean column `uses_full_membership` on the `organisations` table rather than separate organization types or complex role hierarchies. This keeps the system simple while supporting both modes elegantly.

```
organisations table
├── id (UUID)
├── name (string)
├── slug (string)
├── uses_full_membership (boolean, default: true)
└── ... other fields
```

### How It Works

```
FULL MEMBERSHIP MODE (uses_full_membership = true)
┌─────────────────────────────────────┐
│ User                                │
│ ├── OrganisationUser (active)       │
│ └── Member (active)                 │
│     ├── fees_status: 'paid'/'exempt'│
│     └── membership_type_id          │
└─────────────────────────────────────┘
         ↓
    ✅ ELIGIBLE TO VOTE

ELECTION-ONLY MODE (uses_full_membership = false)
┌─────────────────────────────────────┐
│ User                                │
├── OrganisationUser (active)         │
└─ (No Member record required)        │
└─────────────────────────────────────┘
         ↓
    ✅ ELIGIBLE TO VOTE
```

### Separation of Concerns

Three layers handle membership validation:

| Layer | Responsibility |
|-------|-----------------|
| **Domain/Business Logic** | `VoterEligibilityService` — centralized eligibility rules |
| **Database** | `organisations.uses_full_membership` — configuration flag |
| **API** | `ElectionVoterController` — form validation & error handling |

---

## Database Schema

### Column Addition

```sql
ALTER TABLE organisations ADD COLUMN uses_full_membership BOOLEAN DEFAULT TRUE;
```

### Migration File

```php
// database/migrations/2026_04_15_add_uses_full_membership_to_organisations.php
Schema::table('organisations', function (Blueprint $table) {
    $table->boolean('uses_full_membership')
          ->default(true)
          ->comment('false=election-only mode (any org user can vote), true=full membership required');
});
```

### Query Examples

```php
// Get all election-only organisations
$electionOnlyOrgs = Organisation::where('uses_full_membership', false)->get();

// Count members who are eligible voters in full membership mode
$eligibleVoters = Member::where('organisation_id', $orgId)
    ->where('status', 'active')
    ->whereIn('fees_status', ['paid', 'exempt'])
    ->count();

// Get all users eligible in election-only mode
$eligibleUsers = OrganisationUser::where('organisation_id', $orgId)
    ->where('status', 'active')
    ->whereNull('deleted_at')
    ->count();
```

---

## Core Components

### 1. VoterEligibilityService

**File:** `app/Services/VoterEligibilityService.php`

Central service handling voter eligibility checks for both modes.

```php
use App\Services\VoterEligibilityService;

$service = app(VoterEligibilityService::class);

// Check single user eligibility
$eligible = $service->isEligibleVoter($organisation, $user);

// Get query builder for unassigned eligible voters
$query = $service->unassignedEligibleQuery($organisation, $assignedUserIds);
$voters = $query->get();
```

#### Key Methods

##### `isEligibleVoter(Organisation $org, User $user): bool`

Returns true if user can vote in the organisation.

```php
// Example: Full Membership Mode
if ($org->uses_full_membership) {
    // Checks: active Member + paid/exempt fees + not expired
    return $user->isEligibleVoter($org);
}

// Example: Election-Only Mode
if (!$org->uses_full_membership) {
    // Checks: active OrganisationUser record exists
    return OrganisationUser::where('organisation_id', $org->id)
        ->where('user_id', $user->id)
        ->where('status', 'active')
        ->exists();
}
```

##### `unassignedEligibleQuery(Organisation $org, array $excludeUserIds): QueryBuilder`

Returns optimized query for eligible unassigned voters (for dropdowns, bulk operations).

```php
// Election-only mode: single query joining organisation_users + users
return DB::table('organisation_users')
    ->join('users', 'organisation_users.user_id', '=', 'users.id')
    ->where('organisation_users.organisation_id', $org->id)
    ->where('organisation_users.status', 'active')
    ->whereNotIn('organisation_users.user_id', $excludeUserIds)
    ->select('users.id', 'users.name', 'users.email')
    ->orderBy('users.name');

// Full membership mode: complex join with fees validation
return DB::table('members')
    ->join('organisation_users', 'members.organisation_user_id', '=', 'organisation_users.id')
    ->leftJoin('membership_types', 'members.membership_type_id', '=', 'membership_types.id')
    ->join('users', 'organisation_users.user_id', '=', 'users.id')
    ->where('members.organisation_id', $org->id)
    ->where('members.status', 'active')
    ->whereIn('members.fees_status', ['paid', 'exempt'])
    ->where(fn($q) => $q->whereNull('members.membership_expires_at')
                      ->orWhere('members.membership_expires_at', '>', now()))
    ->whereNotIn('organisation_users.user_id', $excludeUserIds)
    ->select('users.id', 'users.name', 'users.email')
    ->orderBy('users.name');
```

### 2. Organisation Model

**File:** `app/Models/Organisation.php`

```php
class Organisation extends Model {
    protected $fillable = [
        'name',
        'slug',
        'uses_full_membership',
        // ... other fields
    ];

    protected $casts = [
        'uses_full_membership' => 'boolean',
    ];

    // Helper methods
    public function isElectionOnly(): bool {
        return !$this->uses_full_membership;
    }

    public function usesFullMembership(): bool {
        return $this->uses_full_membership === true;
    }
}
```

### 3. VoterImportService

**File:** `app/Services/VoterImportService.php`

Handles CSV/Excel imports with mode-aware validation.

```php
use App\Services\VoterImportService;
use App\Services\VoterEligibilityService;

$service = new VoterImportService($election, app(VoterEligibilityService::class));

// Preview file contents with validation
$preview = $service->preview($uploadedFile);
// Returns: ['preview' => [...], 'stats' => ['total' => N, 'valid' => N, 'invalid' => N]]

// Import voters from file
$result = $service->import($uploadedFile);
// Returns: ['created' => N, 'already_existing' => N, 'skipped' => N]
```

#### Validation Logic

The service automatically validates based on organisation mode:

```php
private function validateRow(array $row): array {
    $org = $this->getOrganisation();
    
    // ... email format validation ...
    
    // Mode-aware eligibility check
    if (!$this->eligibilityService->isEligibleVoter($org, $user)) {
        if ($org->isElectionOnly()) {
            return ['error' => "'{$email}' is not an active member of this organisation."];
        } else {
            return ['error' => "'{$email}' is not an eligible voter — must be an active formal member."];
        }
    }
    
    return [];
}
```

### 4. OrganisationSettingsController

**File:** `app/Http/Controllers/OrganisationSettingsController.php`

Handles membership mode configuration.

```php
class OrganisationSettingsController extends Controller {
    public function index(Organisation $organisation) {
        $this->authorize('update', $organisation);
        
        $memberCount = Member::where('organisation_id', $organisation->id)->count();
        
        return Inertia::render('Organisations/Settings/Index', [
            'organisation' => $organisation,
            'memberCount' => $memberCount,
        ]);
    }

    public function updateMembershipMode(Request $request, Organisation $organisation) {
        $this->authorize('update', $organisation);
        
        $validated = $request->validate([
            'uses_full_membership' => 'required|boolean',
            'confirm_mode_change' => 'required_if:uses_full_membership,false|accepted',
        ]);
        
        // Confirmation required when switching to election-only with existing members
        $memberCount = Member::where('organisation_id', $organisation->id)->count();
        if ($organisation->uses_full_membership && !$validated['uses_full_membership'] && $memberCount > 0) {
            if (empty($validated['confirm_mode_change'])) {
                return back()->withErrors(['confirm_mode_change' => 'Confirmation required.']);
            }
        }
        
        Log::info('Membership mode changed', [
            'organisation_id' => $organisation->id,
            'from' => $organisation->uses_full_membership ? 'full' : 'election_only',
            'to' => $validated['uses_full_membership'] ? 'full' : 'election_only',
            'member_count' => $memberCount,
        ]);
        
        $organisation->update(['uses_full_membership' => $validated['uses_full_membership']]);
        
        return back()->with('success', 'Membership mode updated successfully.');
    }
}
```

---

## API Endpoints

### Voter Assignment

#### GET `/organisations/{slug}/elections/{slug}/voters`

List voters with mode-aware dropdown.

**Query:** Uses `VoterEligibilityService::unassignedEligibleQuery()`

```php
$unassignedMembers = $this->eligibilityService
    ->unassignedEligibleQuery($organisation, $assignedUserIds)
    ->get();
```

#### POST `/organisations/{slug}/elections/{slug}/voters`

Assign single voter (validates eligibility based on mode).

**Request Body:**
```json
{
  "user_id": "uuid"
}
```

**Validation:**
```php
$request->validate([
    'user_id' => [
        'required',
        'uuid',
        function ($attribute, $value, $fail) use ($organisation) {
            $user = User::find($value);
            if (!$user || !$this->eligibilityService->isEligibleVoter($organisation, $user)) {
                $fail('The selected user is not eligible to vote.');
            }
        },
    ],
]);
```

#### POST `/organisations/{slug}/elections/{slug}/voters/bulk`

Bulk assign voters (efficient mode-specific queries).

**Request Body:**
```json
{
  "user_ids": ["uuid1", "uuid2", ...]
}
```

**Logic:**
```php
if ($organisation->usesFullMembership()) {
    // Single query checking members + fees
    $validIds = DB::table('members')
        ->join('organisation_users', ...)
        ->whereIn('organisation_users.user_id', $request->user_ids)
        ->where('members.status', 'active')
        ->whereIn('members.fees_status', ['paid', 'exempt'])
        ->pluck('organisation_users.user_id')
        ->toArray();
} else {
    // Single query checking organisation users
    $validIds = OrganisationUser::where('organisation_id', $organisation->id)
        ->whereIn('user_id', $request->user_ids)
        ->where('status', 'active')
        ->pluck('user_id')
        ->toArray();
}
```

#### POST `/organisations/{slug}/elections/{slug}/voters/import`

Import voters from CSV/Excel file.

**Request:**
```php
[
    'file' => UploadedFile,      // CSV or Excel
    'confirmed' => 'on',         // Preview confirmation
]
```

**Response:**
```json
{
  "success": "Voter import completed: 45 registered, 3 already existing, 2 skipped."
}
```

### Organisation Settings

#### GET `/organisations/{slug}/settings`

View organisation settings page.

**Props Passed to Vue:**
```php
[
    'organisation' => $organisation,  // id, name, slug, uses_full_membership
    'memberCount' => $memberCount,    // Number of active members
]
```

#### PATCH `/organisations/{slug}/settings/membership-mode`

Toggle membership mode.

**Request Body:**
```json
{
  "uses_full_membership": false,
  "confirm_mode_change": "on"  // Required if switching to election-only with members
}
```

**Validation Rules:**
```php
[
    'uses_full_membership' => 'required|boolean',
    'confirm_mode_change' => 'required_if:uses_full_membership,false|accepted',
]
```

---

## Frontend Implementation

### Settings Page

**File:** `resources/js/Pages/Organisations/Settings/Index.vue`

```vue
<template>
  <AppLayout :title="`${organisation.name} - Settings`">
    <!-- Current Mode Badge -->
    <span :class="organisation.uses_full_membership ? 'bg-blue-100' : 'bg-green-100'">
      {{ organisation.uses_full_membership ? 'Full Membership' : 'Election-Only' }}
    </span>

    <!-- Toggle Switch -->
    <button @click="toggleMode" class="toggle-switch">
      <!-- Visual toggle -->
    </button>

    <!-- Warning Modal (shown when switching with members) -->
    <div v-if="showWarning" class="warning-modal">
      <p>This organisation has {{ memberCount }} members.</p>
      <label>
        <input type="checkbox" v-model="form.confirm_mode_change" />
        I understand and want to proceed
      </label>
    </div>

    <!-- Save Button -->
    <button @click="submit" :disabled="!hasChanges">Save Changes</button>
  </AppLayout>
</template>

<script setup>
import { computed } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
  organisation: Object,
  memberCount: Number,
});

const form = useForm({
  uses_full_membership: props.organisation.uses_full_membership,
  confirm_mode_change: false,
});

const showWarning = computed(() => {
  return props.organisation.uses_full_membership &&
         !form.uses_full_membership &&
         props.memberCount > 0;
});

const submit = () => {
  form.patch(
    route('organisations.settings.update-membership-mode', props.organisation.slug),
    { preserveScroll: true }
  );
};
</script>
```

### Create Organisation Form

**File:** `resources/js/Pages/Organisations/Create.vue`

```vue
<fieldset>
  <legend>Membership System</legend>
  
  <label>
    <input type="radio" v-model="form.uses_full_membership" :value="true" />
    <span>Full Membership</span>
    <p>Voters must have active memberships with paid fees.</p>
  </label>
  
  <label>
    <input type="radio" v-model="form.uses_full_membership" :value="false" />
    <span>Election-Only</span>
    <p>Any registered user can vote.</p>
  </label>
</fieldset>
```

### Voters Page

**File:** `resources/js/Pages/Elections/Voters/Index.vue`

```vue
<p class="sidebar-label">
  {{ organisation.uses_full_membership ? 'ASSIGN MEMBERS AS VOTERS' : 'ASSIGN USERS AS VOTERS' }}
</p>
<p class="helper-text">
  {{ organisation.uses_full_membership
    ? 'Only active members with paid fees appear below.'
    : 'All organisation users can be assigned as voters.' }}
</p>
```

---

## Common Tasks

### Task 1: Switch Organisation to Election-Only Mode

```php
// Programmatically (avoid in production — use UI)
$organisation = Organisation::find($id);
$organisation->update(['uses_full_membership' => false]);

// Via API
PATCH /organisations/{slug}/settings/membership-mode
{
  "uses_full_membership": false,
  "confirm_mode_change": "on"
}
```

### Task 2: Get Eligible Voters for an Organisation

```php
use App\Services\VoterEligibilityService;

$service = app(VoterEligibilityService::class);

// Single user
$eligible = $service->isEligibleVoter($organisation, $user);

// All eligible (unassigned)
$voters = $service->unassignedEligibleQuery($organisation)->get();
```

### Task 3: Import Voters from CSV

```php
use App\Services\VoterImportService;
use App\Services\VoterEligibilityService;

$service = new VoterImportService($election, app(VoterEligibilityService::class));

// Preview
$preview = $service->preview($file);

// Import
$result = $service->import($file);
echo "Imported: {$result['created']}, Already existing: {$result['already_existing']}, Skipped: {$result['skipped']}";
```

### Task 4: Add Mode Indicator to Custom UI

```php
@if($organisation->uses_full_membership)
    <span class="badge-blue">Full Membership Mode</span>
    <p class="text-sm">Voters must be active members with paid fees.</p>
@else
    <span class="badge-green">Election-Only Mode</span>
    <p class="text-sm">Any registered user can vote.</p>
@endif
```

### Task 5: Query Voters by Mode

```php
// Full membership voters
if ($organisation->usesFullMembership()) {
    $voters = Member::where('organisation_id', $organisation->id)
        ->where('status', 'active')
        ->whereIn('fees_status', ['paid', 'exempt'])
        ->get();
}

// Election-only voters
if ($organisation->isElectionOnly()) {
    $voters = OrganisationUser::where('organisation_id', $organisation->id)
        ->where('status', 'active')
        ->whereNull('deleted_at')
        ->get();
}
```

---

## Testing

### Unit Tests

```php
// Test VoterEligibilityService
class VoterEligibilityServiceTest extends TestCase {
    public function test_election_only_user_without_member_record_is_eligible() {
        $org = Organisation::factory()->create(['uses_full_membership' => false]);
        $user = User::factory()->create();
        OrganisationUser::factory()->for($org)->for($user)->create(['status' => 'active']);
        
        $eligible = app(VoterEligibilityService::class)->isEligibleVoter($org, $user);
        
        $this->assertTrue($eligible);
    }

    public function test_full_membership_user_without_member_record_not_eligible() {
        $org = Organisation::factory()->create(['uses_full_membership' => true]);
        $user = User::factory()->create();
        OrganisationUser::factory()->for($org)->for($user)->create(['status' => 'active']);
        
        $eligible = app(VoterEligibilityService::class)->isEligibleVoter($org, $user);
        
        $this->assertFalse($eligible);
    }
}
```

### Feature Tests

```php
// Test voter assignment
class VoterAssignmentTest extends TestCase {
    public function test_election_only_single_assign_accepts_user_without_member_record() {
        $org = Organisation::factory()->create(['uses_full_membership' => false]);
        $election = Election::factory()->forOrganisation($org)->real()->create();
        $user = User::factory()->create();
        OrganisationUser::factory()->for($org)->for($user)->create(['status' => 'active']);
        
        $response = $this->actingAs($admin)
            ->post("/organisations/{$org->slug}/elections/{$election->slug}/voters", [
                'user_id' => $user->id,
            ]);
        
        $response->assertRedirect();
        $this->assertTrue($election->memberships()->where('user_id', $user->id)->exists());
    }
}
```

### Import Tests

```php
// Test CSV import with mode awareness
public function test_csv_import_respects_membership_mode() {
    $org = Organisation::factory()->create(['uses_full_membership' => false]);
    $election = Election::factory()->forOrganisation($org)->real()->create();
    
    $file = UploadedFile::fake()->createWithContent('voters.csv', "email\nuser@example.com");
    
    $response = $this->actingAs($admin)
        ->post("/organisations/{$org->slug}/elections/{$election->slug}/voters/import", [
            'file' => $file,
            'confirmed' => true,
        ]);
    
    $response->assertRedirect();
}
```

---

## Troubleshooting

### Issue: "User is not eligible to vote" when assigning in Election-Only Mode

**Cause:** User is not an active OrganisationUser.

**Solution:**
```php
// Ensure user is in organisation
$orgUser = OrganisationUser::where('organisation_id', $org->id)
    ->where('user_id', $user->id)
    ->first();

if (!$orgUser) {
    // Add user to organisation
    OrganisationUser::create([
        'organisation_id' => $org->id,
        'user_id' => $user->id,
        'status' => 'active',
    ]);
}
```

### Issue: "User is not eligible" in Full Membership Mode

**Cause:** User doesn't have active Member record with paid/exempt fees.

**Solution:**
```php
// Check member status
$member = Member::where('organisation_id', $org->id)
    ->whereHas('organisationUser', fn($q) => $q->where('user_id', $user->id))
    ->first();

if (!$member) {
    // Create member first
    $member = Member::create([
        'organisation_user_id' => $orgUser->id,
        'status' => 'active',
        'fees_status' => 'exempt', // or 'paid'
    ]);
}

// Check fees_status
if (!in_array($member->fees_status, ['paid', 'exempt'])) {
    // Update fees
    $member->update(['fees_status' => 'exempt']);
}
```

### Issue: CSV Import Returns "User not found"

**Cause:** Email in CSV doesn't match any user in the database.

**Solution:**
```php
// Create missing users first
$email = 'newuser@example.com';
$user = User::firstOrCreate(
    ['email' => $email],
    ['name' => 'New User', 'password' => Hash::make('temporary')]
);

// Then add to organisation
OrganisationUser::firstOrCreate([
    'organisation_id' => $org->id,
    'user_id' => $user->id,
], ['status' => 'active']);
```

### Issue: Performance Issues with Large Elections

**Solution:** Use the mode-specific queries which are optimized:

```php
// ❌ SLOW: N+1 query problem
foreach ($userIds as $userId) {
    $eligible = $service->isEligibleVoter($org, User::find($userId));
}

// ✅ FAST: Single optimized query
$eligible = $service->unassignedEligibleQuery($org, [])->get();
```

---

## Key Files Reference

| File | Purpose |
|------|---------|
| `app/Services/VoterEligibilityService.php` | Core eligibility logic |
| `app/Http/Controllers/OrganisationSettingsController.php` | Settings management |
| `app/Http/Controllers/ElectionVoterController.php` | Voter assignment |
| `app/Http/Controllers/Election/VoterImportController.php` | CSV imports |
| `app/Models/Organisation.php` | Organisation model with helper methods |
| `resources/js/Pages/Organisations/Settings/Index.vue` | Settings UI |
| `resources/js/Pages/Organisations/Create.vue` | Create form with mode selection |
| `resources/js/Pages/Elections/Voters/Index.vue` | Voter assignment UI |

---

## Additional Resources

- [Voter Eligibility Service](./voter_eligibility_service.md)
- [Database Migrations](./migrations.md)
- [API Reference](./api_reference.md)
- [Frontend Components](./frontend_components.md)
- [Testing Guide](./testing.md)

---

**Last Updated:** April 2026  
**Version:** 1.0  
**Status:** Production Ready

## Critical Analysis & Professional Implementation Plan

### ✅ Document Strengths

The document is **comprehensive and well-structured**:
- Complete code samples for all Vue components
- Proper Inertia.js patterns
- UX considerations (warning modals)
- Authorization checks
- CSV import with results feedback

### 🟡 Issues Requiring Attention

| Issue | Severity | Fix |
|-------|----------|-----|
| No TDD tests | 🔴 High | Write tests FIRST |
| `OrganisationSettingsController` doesn't exist | 🔴 High | Create with tests |
| Settings page doesn't exist | 🔴 High | Create `Index.vue` |
| Member count query missing | 🟡 Medium | Add to controller |
| CSV max rows not set | 🟡 Medium | Add configurable limit |
| No dry-run/preview for CSV | 🟢 Low | Add preview modal |
| Authorization policy check | 🟡 Medium | Verify `OrganisationPolicy` |

### 📋 Implementation Strategy (TDD-First)

```
Phase 1: RED ──────────────────────────────────────────────────────────────────
├── tests/Feature/Organisation/OrganisationSettingsTest.php
│   ├── test_admin_can_view_settings_page()
│   ├── test_admin_can_update_membership_mode()
│   ├── test_mode_change_requires_confirmation_when_members_exist()
│   └── test_non_admin_cannot_access_settings()
│
├── tests/Feature/Election/CsvVoterImportTest.php
│   ├── test_admin_can_import_voters_via_csv()
│   ├── test_csv_import_validates_email_format()
│   ├── test_csv_import_skips_already_assigned_voters()
│   ├── test_csv_import_respects_membership_mode()
│   └── test_csv_import_handles_large_files_gracefully()
│
└── Run: php artisan test --filter="OrganisationSettings|CsvVoterImport"
    Expected: All 9 tests FAIL (RED)

Phase 2: GREEN ─────────────────────────────────────────────────────────────────
├── Create app/Http/Controllers/OrganisationSettingsController.php
├── Add routes to routes/organisations.php
├── Implement CSV import in ElectionVoterController
├── Run tests → 9/9 PASS (GREEN)

Phase 3: FRONTEND ──────────────────────────────────────────────────────────────
├── Create resources/js/Pages/Organisations/Settings/Index.vue
├── Update resources/js/Pages/Organisations/Create.vue
├── Update resources/js/Pages/Elections/Voters/Index.vue
└── Manual verification

Phase 4: REFACTOR ──────────────────────────────────────────────────────────────
├── Extract CSV parsing to dedicated service
├── Add job queue for large imports (>100 rows)
├── Add progress tracking for async imports
└── Polish error messages and UX
```

---

## Claude Code CLI Prompt Instructions

```
Implement the Dual-Mode Membership System UI with TDD-first approach.

## Background

Phase 1 & 2 backend is COMPLETE (commit 2e9e5439a):
- `uses_full_membership` column exists
- `VoterEligibilityService` fully functional
- `ElectionVoterController` wired for both modes
- 219 tests passing, 0 regressions

## Current Gap

No UI exists for:
1. Setting membership mode during organisation creation
2. Toggling mode in organisation settings
3. Importing voters via CSV in election-only mode

## Your Task: TDD-First Implementation

### Phase 1: RED - Write Failing Tests First

**File 1:** `tests/Feature/Organisation/OrganisationSettingsTest.php`

```php
<?php

namespace Tests\Feature\Organisation;

use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrganisationSettingsTest extends TestCase
{
    use RefreshDatabase;
    
    private Organisation $org;
    private User $admin;
    private User $nonAdmin;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->org = Organisation::factory()->create([
            'type' => 'tenant',
            'uses_full_membership' => true,
        ]);
        
        $this->admin = User::factory()->create();
        UserOrganisationRole::create([
            'user_id' => $this->admin->id,
            'organisation_id' => $this->org->id,
            'role' => 'admin',
        ]);
        
        $this->nonAdmin = User::factory()->create();
        UserOrganisationRole::create([
            'user_id' => $this->nonAdmin->id,
            'organisation_id' => $this->org->id,
            'role' => 'member',
        ]);
    }
    
    /** @test */
    public function admin_can_view_settings_page(): void
    {
        $response = $this->actingAs($this->admin)
            ->get("/organisations/{$this->org->slug}/settings");
            
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => 
            $page->component('Organisations/Settings/Index')
                ->where('organisation.uses_full_membership', true)
        );
    }
    
    /** @test */
    public function admin_can_update_membership_mode(): void
    {
        $response = $this->actingAs($this->admin)
            ->patch("/organisations/{$this->org->slug}/settings/membership-mode", [
                'uses_full_membership' => false,
            ]);
            
        $response->assertRedirect();
        $this->assertDatabaseHas('organisations', [
            'id' => $this->org->id,
            'uses_full_membership' => false,
        ]);
    }
    
    /** @test */
    public function non_admin_cannot_access_settings(): void
    {
        $response = $this->actingAs($this->nonAdmin)
            ->get("/organisations/{$this->org->slug}/settings");
            
        $response->assertStatus(403);
    }
}
```

**File 2:** `tests/Feature/Election/CsvVoterImportTest.php`

```php
<?php

namespace Tests\Feature\Election;

use App\Models\Election;
use App\Models\ElectionOfficer;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class CsvVoterImportTest extends TestCase
{
    use RefreshDatabase;
    
    private Organisation $org;
    private Election $election;
    private User $admin;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->org = Organisation::factory()->create([
            'type' => 'tenant',
            'uses_full_membership' => false, // Election-only mode
        ]);
        
        $this->election = Election::factory()
            ->forOrganisation($this->org)
            ->real()
            ->create(['status' => 'active']);
            
        $this->admin = User::factory()->create();
        \App\Models\UserOrganisationRole::create([
            'user_id' => $this->admin->id,
            'organisation_id' => $this->org->id,
            'role' => 'admin',
        ]);
        
        ElectionOfficer::create([
            'election_id' => $this->election->id,
            'user_id' => $this->admin->id,
            'role' => 'chief',
            'status' => 'active',
        ]);
    }
    
    /** @test */
    public function admin_can_import_voters_via_csv(): void
    {
        $user1 = User::factory()->create(['email' => 'voter1@example.com']);
        $user2 = User::factory()->create(['email' => 'voter2@example.com']);
        
        \App\Models\OrganisationUser::factory()
            ->for($this->org)
            ->for($user1)
            ->create(['status' => 'active']);
            
        \App\Models\OrganisationUser::factory()
            ->for($this->org)
            ->for($user2)
            ->create(['status' => 'active']);
        
        $file = UploadedFile::fake()->createWithContent(
            'voters.csv',
            "voter1@example.com\nvoter2@example.com"
        );
        
        $response = $this->actingAs($this->admin)
            ->post("/organisations/{$this->org->slug}/elections/{$this->election->slug}/voters/import", [
                'csv_file' => $file,
            ]);
            
        $response->assertRedirect();
        $response->assertSessionHas('import_results');
        
        $this->assertDatabaseHas('election_memberships', [
            'election_id' => $this->election->id,
            'user_id' => $user1->id,
            'role' => 'voter',
        ]);
        
        $this->assertDatabaseHas('election_memberships', [
            'election_id' => $this->election->id,
            'user_id' => $user2->id,
            'role' => 'voter',
        ]);
    }
    
    /** @test */
    public function csv_import_skips_invalid_emails(): void
    {
        $file = UploadedFile::fake()->createWithContent(
            'voters.csv',
            "invalid-email\nnotanemail\ntest@example.com"
        );
        
        $user = User::factory()->create(['email' => 'test@example.com']);
        \App\Models\OrganisationUser::factory()
            ->for($this->org)
            ->for($user)
            ->create(['status' => 'active']);
        
        $response = $this->actingAs($this->admin)
            ->post("/organisations/{$this->org->slug}/elections/{$this->election->slug}/voters/import", [
                'csv_file' => $file,
            ]);
            
        $response->assertRedirect();
        
        $results = session('import_results');
        $this->assertEquals(1, $results['success']);
        $this->assertCount(2, $results['errors']);
    }
}
```

Run tests - they MUST fail before proceeding.

### Phase 2: GREEN - Implement Backend

**Step 1: Create OrganisationSettingsController**

```php
// app/Http/Controllers/OrganisationSettingsController.php
<?php

namespace App\Http\Controllers;

use App\Models\Organisation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class OrganisationSettingsController extends Controller
{
    public function index(Organisation $organisation)
    {
        $this->authorize('update', $organisation);
        
        $memberCount = $organisation->members()->count();
        
        return Inertia::render('Organisations/Settings/Index', [
            'organisation' => $organisation,
            'memberCount' => $memberCount,
        ]);
    }
    
    public function updateMembershipMode(Request $request, Organisation $organisation)
    {
        $this->authorize('update', $organisation);
        
        $validated = $request->validate([
            'uses_full_membership' => 'required|boolean',
            'confirm_mode_change' => 'required_if:uses_full_membership,false|accepted',
        ]);
        
        $memberCount = $organisation->members()->count();
        
        if ($organisation->uses_full_membership && !$validated['uses_full_membership'] && $memberCount > 0) {
            if (empty($validated['confirm_mode_change'])) {
                return back()->withErrors([
                    'confirm_mode_change' => 'You must confirm this change when members exist.',
                ]);
            }
        }
        
        Log::info('Organisation membership mode changed', [
            'organisation_id' => $organisation->id,
            'from' => $organisation->uses_full_membership ? 'full' : 'election_only',
            'to' => $validated['uses_full_membership'] ? 'full' : 'election_only',
            'user_id' => auth()->id(),
            'member_count' => $memberCount,
        ]);
        
        $organisation->update(['uses_full_membership' => $validated['uses_full_membership']]);
        
        return back()->with('success', 'Membership mode updated successfully.');
    }
}
```

**Step 2: Add Routes**

```php
// routes/organisations.php
Route::prefix('/{organisation:slug}')->group(function () {
    // Settings
    Route::get('/settings', [OrganisationSettingsController::class, 'index'])
        ->name('organisations.settings.index')
        ->can('update', 'organisation');
        
    Route::patch('/settings/membership-mode', [OrganisationSettingsController::class, 'updateMembershipMode'])
        ->name('organisations.settings.update-membership-mode')
        ->can('update', 'organisation');
});
```

**Step 3: Add CSV Import to ElectionVoterController**

```php
// app/Http/Controllers/ElectionVoterController.php
public function import(Request $request, Organisation $organisation, Election $election)
{
    $this->authorize('manageVoters', $election);
    
    $request->validate([
        'csv_file' => 'required|file|mimes:csv,txt|max:10240',
    ]);
    
    $path = $request->file('csv_file')->getRealPath();
    $handle = fopen($path, 'r');
    
    $results = ['success' => 0, 'skipped' => 0, 'errors' => []];
    $rowNumber = 0;
    $maxRows = 500;
    
    while (($row = fgetcsv($handle)) !== false && $rowNumber < $maxRows) {
        $rowNumber++;
        $email = trim($row[0] ?? '');
        
        if (empty($email)) {
            $results['errors'][] = "Row {$rowNumber}: Empty email";
            continue;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $results['errors'][] = "Row {$rowNumber}: Invalid email - {$email}";
            continue;
        }
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $results['errors'][] = "Row {$rowNumber}: User not found - {$email}";
            continue;
        }
        
        if (!$this->eligibilityService->isEligibleVoter($organisation, $user)) {
            $results['skipped']++;
            continue;
        }
        
        $exists = ElectionMembership::where('election_id', $election->id)
            ->where('user_id', $user->id)
            ->exists();
            
        if ($exists) {
            $results['skipped']++;
            continue;
        }
        
        ElectionMembership::create([
            'election_id' => $election->id,
            'user_id' => $user->id,
            'organisation_id' => $organisation->id,
            'role' => 'voter',
            'status' => 'active',
            'source' => 'csv_import',
        ]);
        
        $results['success']++;
    }
    
    fclose($handle);
    
    if ($rowNumber >= $maxRows) {
        $results['errors'][] = "Maximum of {$maxRows} rows reached. Additional rows ignored.";
    }
    
    return back()->with('import_results', $results);
}
```

Run tests - they MUST pass before proceeding to frontend.

### Phase 3: Frontend Implementation

Follow the Vue component code from the original document - it's correct and complete.

### Verification Checklist

- [ ] `php artisan test --filter="OrganisationSettings"` → 3/3 PASS
- [ ] `php artisan test --filter="CsvVoterImport"` → 5/5 PASS  
- [ ] `php artisan test tests/Feature/Membership/` → 42/42 PASS (no regression)
- [ ] Manual test: Create organisation → Mode selection visible
- [ ] Manual test: Settings page → Toggle works with warning
- [ ] Manual test: Voters page → CSV import visible in election-only mode
- [ ] Manual test: Import CSV → Voters added correctly

**Om Gam Ganapataye Namah** 🪔🐘
```

This is the complete TDD-first implementation plan. Proceed with Phase 1: RED.
## Claude Code CLI Prompt — TDD-First Candidacy Application System

```
We need to implement a professional candidacy application system for voters. This must follow TDD-first approach with proper separation of concerns.

## Context

Currently, VoterHub.vue has a link to the admin posts page for "View Posts & Apply for Candidacy". This is incorrect — voters should have a dedicated application form with supporter/proposer fields, document upload, and application tracking.

## Requirements

1. **Candidacy Application Table** — Store applications with supporter/proposer names, manifesto, documents, status
2. **TDD Tests** — Write tests BEFORE implementation
3. **Dedicated Form** — Voter-facing form with election selection, post selection, supporter/proposer fields, manifesto, document upload
4. **Application Tracking** — Show user their existing applications with status
5. **Admin Approval Flow** — Election officers can approve/reject applications (Phase 2 — not in scope for this PR)

## Implementation Order

### Phase 1: Database & Model (TDD Red → Green)

**Step 1: Write migration test first**

Create `tests/Feature/CandidacyApplicationMigrationTest.php`:
```php
<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class CandidacyApplicationMigrationTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_candidacy_applications_table_has_required_columns(): void
    {
        $this->assertTrue(Schema::hasTable('candidacy_applications'));
        
        $columns = Schema::getColumnListing('candidacy_applications');
        
        $required = [
            'id', 'user_id', 'organisation_id', 'election_id', 'post_id',
            'supporter_name', 'proposer_name', 'manifesto', 'documents',
            'status', 'rejection_reason', 'reviewed_at', 'reviewed_by',
            'created_at', 'updated_at'
        ];
        
        foreach ($required as $column) {
            $this->assertContains($column, $columns);
        }
    }
    
    public function test_candidacy_applications_table_has_correct_indexes(): void
    {
        $indexes = Schema::getConnection()->getDoctrineSchemaManager()
            ->listTableIndexes('candidacy_applications');
        
        $this->assertArrayHasKey('candidacy_applications_user_id_election_id_status_index', $indexes);
    }
}
```

**Step 2: Create migration**
```bash
php artisan make:migration create_candidacy_applications_table
```

Migration content:
```php
Schema::create('candidacy_applications', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->uuid('user_id');
    $table->uuid('organisation_id');
    $table->uuid('election_id');
    $table->uuid('post_id');
    $table->string('supporter_name');
    $table->string('proposer_name');
    $table->text('manifesto')->nullable();
    $table->json('documents')->nullable();
    $table->string('status')->default('pending');
    $table->text('rejection_reason')->nullable();
    $table->timestamp('reviewed_at')->nullable();
    $table->uuid('reviewed_by')->nullable();
    $table->timestamps();
    
    $table->foreign('user_id')->references('id')->on('users');
    $table->foreign('organisation_id')->references('id')->on('organisations');
    $table->foreign('election_id')->references('id')->on('elections');
    $table->foreign('post_id')->references('id')->on('posts');
    
    $table->index(['user_id', 'election_id', 'status']);
});
```

**Step 3: Create model**
```php
// app/Models/CandidacyApplication.php
class CandidacyApplication extends Model
{
    use HasFactory, UuidTrait;
    
    protected $fillable = [
        'user_id', 'organisation_id', 'election_id', 'post_id',
        'supporter_name', 'proposer_name', 'manifesto', 'documents',
        'status', 'rejection_reason', 'reviewed_at', 'reviewed_by'
    ];
    
    protected $casts = [
        'documents' => 'array',
        'reviewed_at' => 'datetime',
    ];
    
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    
    public function user() { return $this->belongsTo(User::class); }
    public function organisation() { return $this->belongsTo(Organisation::class); }
    public function election() { return $this->belongsTo(Election::class); }
    public function post() { return $this->belongsTo(Post::class); }
    public function reviewer() { return $this->belongsTo(User::class, 'reviewed_by'); }
}
```

**Step 4: Run migration and test**
```bash
php artisan migrate
php artisan test tests/Feature/CandidacyApplicationMigrationTest.php
```

### Phase 2: Controller & Routes (TDD Red → Green)

**Step 1: Write controller tests**

Create `tests/Feature/CandidacyApplicationTest.php`:
```php
<?php

namespace Tests\Feature;

use App\Models\CandidacyApplication;
use App\Models\Election;
use App\Models\Organisation;
use App\Models\Post;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CandidacyApplicationTest extends TestCase
{
    use RefreshDatabase;
    
    private Organisation $org;
    private User $member;
    private Election $election;
    private Post $post;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->org = Organisation::factory()->create(['type' => 'tenant']);
        $this->member = User::factory()->create();
        $this->election = Election::factory()->create([
            'organisation_id' => $this->org->id,
            'type' => 'real',
            'status' => 'active',
        ]);
        $this->post = Post::factory()->create([
            'election_id' => $this->election->id,
            'organisation_id' => $this->org->id,
        ]);
        
        UserOrganisationRole::create([
            'user_id' => $this->member->id,
            'organisation_id' => $this->org->id,
            'role' => 'voter',
        ]);
    }
    
    public function test_guest_cannot_submit_application(): void
    {
        $this->post(route('organisations.candidacy.apply', $this->org->slug), [])
             ->assertRedirect(route('login'));
    }
    
    public function test_non_member_cannot_submit_application(): void
    {
        $this->actingAs(User::factory()->create())
             ->post(route('organisations.candidacy.apply', $this->org->slug), [])
             ->assertStatus(403);
    }
    
    public function test_member_can_submit_valid_application(): void
    {
        Storage::fake('public');
        
        $data = [
            'election_id' => $this->election->id,
            'post_id' => $this->post->id,
            'supporter_name' => 'John Supporter',
            'proposer_name' => 'Jane Proposer',
            'manifesto' => 'I will serve the community',
            'documents' => [UploadedFile::fake()->create('cv.pdf', 500)],
        ];
        
        $this->actingAs($this->member)
             ->post(route('organisations.candidacy.apply', $this->org->slug), $data)
             ->assertRedirect()
             ->assertSessionHas('success');
        
        $this->assertDatabaseHas('candidacy_applications', [
            'user_id' => $this->member->id,
            'organisation_id' => $this->org->id,
            'election_id' => $this->election->id,
            'post_id' => $this->post->id,
            'supporter_name' => 'John Supporter',
            'proposer_name' => 'Jane Proposer',
            'status' => 'pending',
        ]);
    }
    
    public function test_application_requires_supporter_name(): void
    {
        $data = [
            'election_id' => $this->election->id,
            'post_id' => $this->post->id,
            'proposer_name' => 'Jane Proposer',
        ];
        
        $this->actingAs($this->member)
             ->post(route('organisations.candidacy.apply', $this->org->slug), $data)
             ->assertSessionHasErrors('supporter_name');
    }
    
    public function test_application_requires_proposer_name(): void
    {
        $data = [
            'election_id' => $this->election->id,
            'post_id' => $this->post->id,
            'supporter_name' => 'John Supporter',
        ];
        
        $this->actingAs($this->member)
             ->post(route('organisations.candidacy.apply', $this->org->slug), $data)
             ->assertSessionHasErrors('proposer_name');
    }
    
    public function test_cannot_apply_twice_for_same_post(): void
    {
        CandidacyApplication::create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'user_id' => $this->member->id,
            'organisation_id' => $this->org->id,
            'election_id' => $this->election->id,
            'post_id' => $this->post->id,
            'supporter_name' => 'John Supporter',
            'proposer_name' => 'Jane Proposer',
            'status' => 'pending',
        ]);
        
        $data = [
            'election_id' => $this->election->id,
            'post_id' => $this->post->id,
            'supporter_name' => 'Another Supporter',
            'proposer_name' => 'Another Proposer',
        ];
        
        $this->actingAs($this->member)
             ->post(route('organisations.candidacy.apply', $this->org->slug), $data)
             ->assertSessionHas('error');
    }
    
    public function test_documents_are_uploaded_and_stored(): void
    {
        Storage::fake('public');
        
        $file = UploadedFile::fake()->create('manifesto.pdf', 1000);
        
        $data = [
            'election_id' => $this->election->id,
            'post_id' => $this->post->id,
            'supporter_name' => 'John Supporter',
            'proposer_name' => 'Jane Proposer',
            'documents' => [$file],
        ];
        
        $this->actingAs($this->member)
             ->post(route('organisations.candidacy.apply', $this->org->slug), $data);
        
        Storage::disk('public')->assertExists("candidacy/{$this->org->id}/{$this->member->id}/" . $file->hashName());
        
        $application = CandidacyApplication::first();
        $this->assertNotEmpty($application->documents);
    }
}
```

**Step 2: Add routes**
```php
// routes/organisations.php — add after other routes
Route::post('/candidacy/apply', [CandidacyApplicationController::class, 'store'])
    ->name('organisations.candidacy.apply');
```

**Step 3: Create controller**
```bash
php artisan make:controller CandidacyApplicationController
```

Controller content:
```php
<?php

namespace App\Http\Controllers;

use App\Models\CandidacyApplication;
use App\Models\Election;
use App\Models\Organisation;
use App\Models\Post;
use App\Models\UserOrganisationRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CandidacyApplicationController extends Controller
{
    public function store(Request $request, Organisation $organisation)
    {
        $user = auth()->user();
        
        // Check membership
        $role = UserOrganisationRole::where('user_id', $user->id)
            ->where('organisation_id', $organisation->id)
            ->value('role');
        
        abort_if(!$role, 403, 'You must be a member to apply');
        
        $validated = $request->validate([
            'election_id' => 'required|uuid|exists:elections,id',
            'post_id' => 'required|uuid|exists:posts,id',
            'supporter_name' => 'required|string|max:255',
            'proposer_name' => 'required|string|max:255',
            'manifesto' => 'nullable|string|max:5000',
            'documents' => 'nullable|array|max:5',
            'documents.*' => 'file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);
        
        // Verify election belongs to organisation
        $election = Election::withoutGlobalScopes()
            ->where('id', $validated['election_id'])
            ->where('organisation_id', $organisation->id)
            ->where('status', 'active')
            ->firstOrFail();
        
        // Verify post belongs to election
        $post = Post::withoutGlobalScopes()
            ->where('id', $validated['post_id'])
            ->where('election_id', $election->id)
            ->firstOrFail();
        
        // Check for existing application
        $existing = CandidacyApplication::where('user_id', $user->id)
            ->where('election_id', $election->id)
            ->where('post_id', $post->id)
            ->whereIn('status', ['pending', 'approved'])
            ->exists();
        
        if ($existing) {
            return back()->with('error', 'You have already applied for this position.');
        }
        
        return DB::transaction(function () use ($user, $organisation, $election, $post, $validated, $request) {
            // Upload documents
            $documents = [];
            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $doc) {
                    $path = $doc->store("candidacy/{$organisation->id}/{$user->id}", 'public');
                    $documents[] = $path;
                }
            }
            
            $application = CandidacyApplication::create([
                'id' => (string) Str::uuid(),
                'user_id' => $user->id,
                'organisation_id' => $organisation->id,
                'election_id' => $election->id,
                'post_id' => $post->id,
                'supporter_name' => $validated['supporter_name'],
                'proposer_name' => $validated['proposer_name'],
                'manifesto' => $validated['manifesto'],
                'documents' => $documents,
                'status' => CandidacyApplication::STATUS_PENDING,
            ]);
            
            return back()->with('success', 'Your candidacy application has been submitted for review.');
        });
    }
}
```

**Step 4: Run tests — should PASS**
```bash
php artisan test tests/Feature/CandidacyApplicationTest.php
```

### Phase 3: Update VoterHub to Include Applications

**Step 1: Update OrganisationController@voterHub to include user's applications**
```php
// In voterHub() method, after fetching activeElections
$myApplications = CandidacyApplication::where('user_id', $user->id)
    ->whereIn('election_id', $activeElections->pluck('id'))
    ->with(['election', 'post'])
    ->get()
    ->map(fn($a) => [
        'id' => $a->id,
        'election_id' => $a->election_id,
        'election_name' => $a->election->name,
        'post_name' => $a->post->name,
        'status' => $a->status,
        'status_label' => $this->getApplicationStatusLabel($a->status),
        'created_at' => $a->created_at->format('Y-m-d'),
    ]);

return Inertia::render('Organisations/VoterHub', [
    // ... existing props
    'myApplications' => $myApplications,
]);

// Add helper method
private function getApplicationStatusLabel(string $status): string
{
    return match($status) {
        'pending' => 'Under Review',
        'approved' => 'Approved ✓',
        'rejected' => 'Not Approved',
        default => $status,
    };
}
```

**Step 2: Create CandidacyApplicationForm.vue component**
```bash
mkdir -p resources/js/Pages/Organisations/Partials
touch resources/js/Pages/Organisations/Partials/CandidacyApplicationForm.vue
```

Use the component code from previous response.

**Step 3: Update VoterHub.vue to use the new form and display applications**
- Remove the old "View Posts & Apply" link
- Add two-column layout with voting on left, candidacy form on right
- Add "My Applications" section below the form
- Import and use CandidacyApplicationForm component

**Step 4: Write Vue component tests (optional but recommended)**
```javascript
// tests/Unit/VoterHubTest.js
import { mount } from '@vue/test-utils'
import VoterHub from '@/Pages/Organisations/VoterHub.vue'

describe('VoterHub', () => {
    it('shows candidacy form for members', () => {
        const wrapper = mount(VoterHub, {
            props: {
                organisation: { slug: 'test-org' },
                activeElections: [],
                voterMemberships: {},
                myApplications: []
            },
            global: {
                mocks: { route: () => '/test' }
            }
        })
        
        expect(wrapper.text()).toContain('Apply as Candidate')
        expect(wrapper.find('form').exists()).toBe(true)
    })
    
    it('displays existing applications', () => {
        const applications = [{
            id: 1,
            election_name: 'Election 2024',
            post_name: 'President',
            status: 'pending',
            status_label: 'Under Review',
            created_at: '2024-03-24'
        }]
        
        const wrapper = mount(VoterHub, {
            props: {
                organisation: { slug: 'test-org' },
                activeElections: [],
                voterMemberships: {},
                myApplications: applications
            }
        })
        
        expect(wrapper.text()).toContain('My Applications')
        expect(wrapper.text()).toContain('President')
        expect(wrapper.text()).toContain('Under Review')
    })
})
```

### Phase 4: Build & Verify

**Step 1: Run all tests**
```bash
php artisan test
npm run test  # if Vue tests configured
```

**Step 2: Build frontend**
```bash
npm run build
```

**Step 3: Manual verification**
- Visit `/organisations/{slug}/voter-hub`
- Verify form shows with election/post dropdowns
- Submit valid application → success message
- Verify application appears in "My Applications"
- Try submitting duplicate → error message
- Try submitting without supporter/proposer → validation errors
- Upload documents → verify files stored

### Phase 5: Commit

```bash
git add database/migrations/*_create_candidacy_applications_table.php
git add app/Models/CandidacyApplication.php
git add app/Http/Controllers/CandidacyApplicationController.php
git add routes/organisations.php
git add app/Http/Controllers/OrganisationController.php
git add resources/js/Pages/Organisations/Partials/CandidacyApplicationForm.vue
git add resources/js/Pages/Organisations/VoterHub.vue
git add tests/Feature/CandidacyApplicationTest.php
git add tests/Feature/CandidacyApplicationMigrationTest.php

git commit -m "feat: candidacy application system with TDD

- Add candidacy_applications table with supporter/proposer fields
- Create CandidacyApplication model and controller
- Add voter-facing application form with document upload
- Show application status in VoterHub
- Full test coverage including validation and duplicates"
```

## Summary of Files to Create

| File | Purpose |
|------|---------|
| `database/migrations/..._create_candidacy_applications_table.php` | Migration |
| `app/Models/CandidacyApplication.php` | Model |
| `app/Http/Controllers/CandidacyApplicationController.php` | Controller |
| `routes/organisations.php` | Add route |
| `app/Http/Controllers/OrganisationController.php` | Add myApplications prop |
| `resources/js/Pages/Organisations/Partials/CandidacyApplicationForm.vue` | Form component |
| `resources/js/Pages/Organisations/VoterHub.vue` | Update with form + applications |
| `tests/Feature/CandidacyApplicationMigrationTest.php` | Migration tests |
| `tests/Feature/CandidacyApplicationTest.php` | Controller tests |

## Important Constraints

1. TDD-first — write tests BEFORE implementation
2. Use `withoutGlobalScopes()` for multi-tenant queries
3. Validate organisation_id matches in all queries
4. Prevent duplicate applications (pending/approved)
5. File uploads max 5MB, max 5 files, allowed types: PDF, JPG, PNG
6. Use database transactions for file uploads
7. All user-facing text must be translatable

Start with Phase 1 and proceed sequentially. Run tests after each phase to ensure green status.
```

## 🔍 Senior Code Review — VoterHub.vue

You're absolutely right. The current approach is **not professionally developed** for a candidacy application flow. Here's the critical analysis:

---

## ❌ Current Problems

| Issue | Severity | Explanation |
|-------|----------|-------------|
| **"View Posts & Apply" link** | 🔴 Critical | Sends users to an **admin management page**, not a voter-friendly application form |
| **No application form** | 🔴 Critical | Users have no way to actually apply for candidacy |
| **Mixed concerns** | 🟠 High | VoterHub should be for **voting**, not linking to admin tools |
| **Missing supporter/proposer** | 🟠 High | Election law often requires nominations with supporters |
| **No post selection** | 🟠 High | Users can't choose which position to apply for |

---

## ✅ Professional Solution

### Architecture

```
VoterHub.vue
├── Active Elections (voting)
└── Candidacy Application Section (NEW)
    ├── Select Election
    ├── Select Post
    ├── Supporter Name (required)
    ├── Proposer Name (required)
    ├── Manifesto/Bio
    └── Submit Application
```

### New Files Needed

1. **CandidacyApplicationController.php** — Handle submissions
2. **CandidacyApplication Model + Migration** — Store applications
3. **CandidacyFormModal.vue** — Modal for applying
4. **Updated VoterHub.vue** — Add "Apply for Candidacy" button

---

## 📋 Professional Implementation

### 1. Database Migration

```php
// database/migrations/2024_03_24_create_candidacy_applications.php
Schema::create('candidacy_applications', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->uuid('user_id');
    $table->uuid('organisation_id');
    $table->uuid('election_id');
    $table->uuid('post_id');
    $table->string('supporter_name');      // Required
    $table->string('proposer_name');       // Required
    $table->text('manifesto')->nullable();
    $table->json('documents')->nullable();
    $table->string('status')->default('pending'); // pending, approved, rejected
    $table->text('rejection_reason')->nullable();
    $table->timestamp('reviewed_at')->nullable();
    $table->uuid('reviewed_by')->nullable();
    $table->timestamps();
    
    $table->foreign('user_id')->references('id')->on('users');
    $table->foreign('organisation_id')->references('id')->on('organisations');
    $table->foreign('election_id')->references('id')->on('elections');
    $table->foreign('post_id')->references('id')->on('posts');
    $table->index(['user_id', 'election_id', 'status']);
});
```

### 2. Model

```php
// app/Models/CandidacyApplication.php
class CandidacyApplication extends Model
{
    use HasFactory, UuidTrait;
    
    protected $fillable = [
        'user_id', 'organisation_id', 'election_id', 'post_id',
        'supporter_name', 'proposer_name', 'manifesto', 'documents',
        'status', 'rejection_reason', 'reviewed_at', 'reviewed_by'
    ];
    
    protected $casts = [
        'documents' => 'array',
        'reviewed_at' => 'datetime',
    ];
    
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
}
```

### 3. Controller

```php
// app/Http/Controllers/CandidacyApplicationController.php
class CandidacyApplicationController extends Controller
{
    public function store(Request $request, Organisation $organisation): RedirectResponse
    {
        $user = auth()->user();
        
        // Check if user is member
        $role = UserOrganisationRole::where('user_id', $user->id)
            ->where('organisation_id', $organisation->id)
            ->value('role');
        abort_if(!$role, 403, 'You must be a member to apply');
        
        $validated = $request->validate([
            'election_id' => 'required|uuid|exists:elections,id',
            'post_id' => 'required|uuid|exists:posts,id',
            'supporter_name' => 'required|string|max:255',
            'proposer_name' => 'required|string|max:255',
            'manifesto' => 'nullable|string|max:5000',
            'documents' => 'nullable|array|max:5',
            'documents.*' => 'file|mimes:pdf,jpg,png|max:5120',
        ]);
        
        // Check if already applied for this post
        $existing = CandidacyApplication::where('user_id', $user->id)
            ->where('election_id', $validated['election_id'])
            ->where('post_id', $validated['post_id'])
            ->whereIn('status', ['pending', 'approved'])
            ->exists();
            
        if ($existing) {
            return back()->with('error', 'You have already applied for this position.');
        }
        
        // Upload documents
        $documents = [];
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $doc) {
                $documents[] = $doc->store("candidacy/{$organisation->id}/{$user->id}", 'public');
            }
        }
        
        CandidacyApplication::create([
            'id' => (string) Str::uuid(),
            'user_id' => $user->id,
            'organisation_id' => $organisation->id,
            'election_id' => $validated['election_id'],
            'post_id' => $validated['post_id'],
            'supporter_name' => $validated['supporter_name'],
            'proposer_name' => $validated['proposer_name'],
            'manifesto' => $validated['manifesto'],
            'documents' => $documents,
            'status' => 'pending',
        ]);
        
        return back()->with('success', 'Your candidacy application has been submitted.');
    }
}
```

### 4. Updated VoterHub.vue (Professional Version)

```vue
<template>
  <ElectionLayout>
    <!-- Flash Messages -->
    <div v-if="page.props.flash?.success" class="fixed top-4 right-4 z-50 max-w-sm rounded-xl bg-emerald-600 text-white text-sm font-medium px-5 py-3 shadow-xl">
      {{ page.props.flash.success }}
    </div>
    <div v-if="page.props.flash?.error" class="fixed top-4 right-4 z-50 max-w-sm rounded-xl bg-red-600 text-white text-sm font-medium px-5 py-3 shadow-xl">
      {{ page.props.flash.error }}
    </div>

    <main class="py-10 bg-slate-50 min-h-screen">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-sm text-slate-500">
          <a :href="route('organisations.show', organisation.slug)" class="hover:text-slate-700">{{ organisation.name }}</a>
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
          <span class="text-slate-900 font-medium">Voter Hub</span>
        </nav>

        <!-- Header -->
        <SectionCard>
          <template #header>
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-xl bg-primary-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
              </div>
              <div>
                <h1 class="text-xl font-bold text-slate-900">Voter Hub</h1>
                <p class="text-sm text-slate-500">Vote in elections or apply as a candidate</p>
              </div>
            </div>
          </template>
        </SectionCard>

        <!-- TWO COLUMN LAYOUT -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
          
          <!-- LEFT COLUMN: Active Elections (Voting) -->
          <section>
            <h2 class="text-lg font-semibold text-slate-800 mb-4">🗳️ Active Elections</h2>
            
            <EmptyState v-if="activeElections.length === 0" title="No active elections" />
            
            <div v-else class="space-y-4">
              <div v-for="election in activeElections" :key="election.id" 
                class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
                <div class="bg-gradient-to-r from-primary-600 to-primary-700 px-5 py-4">
                  <h3 class="font-semibold text-white">{{ election.name }}</h3>
                </div>
                <div class="px-5 py-4 space-y-3">
                  <div class="flex items-center justify-between">
                    <span :class="statusBadgeClass(election.id)" class="px-2.5 py-1 rounded-full text-xs font-medium">
                      {{ statusLabel(election.id) }}
                    </span>
                    <span class="text-xs text-slate-400">{{ election.start_date?.slice(0,10) }} → {{ election.end_date?.slice(0,10) }}</span>
                  </div>
                  
                  <a v-if="voterStatus(election.id) === 'eligible'"
                    :href="route('elections.show', { slug: election.slug })"
                    class="block w-full text-center bg-primary-600 hover:bg-primary-700 text-white py-2 rounded-lg">
                    Vote Now
                  </a>
                  <div v-else-if="voterStatus(election.id) === 'voted'" class="text-center text-emerald-600 text-sm">
                    ✓ You have voted
                  </div>
                </div>
              </div>
            </div>
          </section>

          <!-- RIGHT COLUMN: Candidacy Applications -->
          <section>
            <h2 class="text-lg font-semibold text-slate-800 mb-4">📋 Apply as Candidate</h2>
            
            <Card mode="admin" padding="lg">
              <p class="text-sm text-slate-600 mb-4">Apply to stand for election. Your application will be reviewed by election officials.</p>
              
              <form @submit.prevent="submitApplication" class="space-y-4">
                <!-- Select Election -->
                <div>
                  <label class="block text-sm font-medium text-slate-700 mb-1">Election *</label>
                  <select v-model="form.election_id" required class="w-full border border-slate-300 rounded-lg px-3 py-2">
                    <option value="">Select an election</option>
                    <option v-for="e in availableElections" :key="e.id" :value="e.id">
                      {{ e.name }}
                    </option>
                  </select>
                </div>
                
                <!-- Select Post -->
                <div>
                  <label class="block text-sm font-medium text-slate-700 mb-1">Position *</label>
                  <select v-model="form.post_id" required :disabled="!form.election_id" class="w-full border border-slate-300 rounded-lg px-3 py-2">
                    <option value="">Select a position</option>
                    <option v-for="post in availablePosts" :key="post.id" :value="post.id">
                      {{ post.name }}
                    </option>
                  </select>
                </div>
                
                <!-- Supporter Name -->
                <div>
                  <label class="block text-sm font-medium text-slate-700 mb-1">Supporter Name *</label>
                  <input v-model="form.supporter_name" type="text" required maxlength="255" class="w-full border border-slate-300 rounded-lg px-3 py-2">
                  <p class="text-xs text-slate-400 mt-1">A member who supports your candidacy</p>
                </div>
                
                <!-- Proposer Name -->
                <div>
                  <label class="block text-sm font-medium text-slate-700 mb-1">Proposer Name *</label>
                  <input v-model="form.proposer_name" type="text" required maxlength="255" class="w-full border border-slate-300 rounded-lg px-3 py-2">
                  <p class="text-xs text-slate-400 mt-1">A member who proposes your nomination</p>
                </div>
                
                <!-- Manifesto -->
                <div>
                  <label class="block text-sm font-medium text-slate-700 mb-1">Manifesto / Statement</label>
                  <textarea v-model="form.manifesto" rows="4" maxlength="5000" class="w-full border border-slate-300 rounded-lg px-3 py-2"></textarea>
                </div>
                
                <!-- Document Upload -->
                <div>
                  <label class="block text-sm font-medium text-slate-700 mb-1">Supporting Documents</label>
                  <input type="file" multiple accept=".pdf,.jpg,.png" @change="handleFiles" class="w-full">
                  <p class="text-xs text-slate-400 mt-1">PDF, JPG, PNG up to 5MB each (max 5 files)</p>
                </div>
                
                <Button type="submit" variant="primary" :loading="submitting" class="w-full">
                  Submit Candidacy Application
                </Button>
              </form>
            </Card>
            
            <!-- My Applications -->
            <div v-if="myApplications.length > 0" class="mt-6">
              <h3 class="text-md font-semibold text-slate-700 mb-3">My Applications</h3>
              <div class="space-y-2">
                <div v-for="app in myApplications" :key="app.id" class="bg-white rounded-lg border p-3 text-sm">
                  <div class="flex justify-between items-start">
                    <div>
                      <p class="font-medium">{{ app.post_name }}</p>
                      <p class="text-xs text-slate-500">{{ app.election_name }}</p>
                    </div>
                    <span :class="applicationStatusClass(app.status)" class="px-2 py-0.5 rounded-full text-xs">
                      {{ app.status_label }}
                    </span>
                  </div>
                  <p class="text-xs text-slate-400 mt-2">Submitted: {{ app.created_at }}</p>
                </div>
              </div>
            </div>
          </section>
        </div>
      </div>
    </main>
  </ElectionLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import ElectionLayout from '@/Layouts/ElectionLayout.vue'
import SectionCard from '@/Components/SectionCard.vue'
import Card from '@/Components/Card.vue'
import Button from '@/Components/Button.vue'
import EmptyState from '@/Components/EmptyState.vue'

const props = defineProps({
  organisation: { type: Object, required: true },
  activeElections: { type: Array, default: () => [] },
  voterMemberships: { type: Object, default: () => ({}) },
  myApplications: { type: Array, default: () => [] },
})

const page = usePage()
const submitting = ref(false)
const form = ref({
  election_id: '',
  post_id: '',
  supporter_name: '',
  proposer_name: '',
  manifesto: '',
  documents: []
})

// Available elections for candidacy (active + not yet applied)
const availableElections = computed(() => {
  return props.activeElections.filter(e => {
    const hasApplied = props.myApplications.some(a => a.election_id === e.id && a.status !== 'rejected')
    return !hasApplied
  })
})

// Available posts for selected election
const availablePosts = computed(() => {
  const election = props.activeElections.find(e => e.id === form.value.election_id)
  return election?.posts || []
})

function voterStatus(electionId) {
  const m = props.voterMemberships[electionId]
  if (!m) return 'ineligible'
  if (m.has_voted) return 'voted'
  if (m.status === 'active') return 'eligible'
  return 'ineligible'
}

function statusLabel(electionId) {
  const s = voterStatus(electionId)
  if (s === 'eligible') return 'Eligible to vote'
  if (s === 'voted') return 'Voted'
  return 'Not eligible'
}

function statusBadgeClass(electionId) {
  const s = voterStatus(electionId)
  if (s === 'eligible') return 'bg-emerald-100 text-emerald-700'
  if (s === 'voted') return 'bg-blue-100 text-blue-700'
  return 'bg-slate-100 text-slate-600'
}

function applicationStatusClass(status) {
  const map = {
    pending: 'bg-amber-100 text-amber-700',
    approved: 'bg-emerald-100 text-emerald-700',
    rejected: 'bg-red-100 text-red-700'
  }
  return map[status] || 'bg-slate-100 text-slate-600'
}

function handleFiles(event) {
  form.value.documents = Array.from(event.target.files)
}

function submitApplication() {
  if (!form.value.election_id || !form.value.post_id || !form.value.supporter_name || !form.value.proposer_name) {
    alert('Please fill all required fields')
    return
  }
  
  submitting.value = true
  const data = new FormData()
  data.append('election_id', form.value.election_id)
  data.append('post_id', form.value.post_id)
  data.append('supporter_name', form.value.supporter_name)
  data.append('proposer_name', form.value.proposer_name)
  if (form.value.manifesto) data.append('manifesto', form.value.manifesto)
  form.value.documents.forEach((file, i) => {
    data.append(`documents[${i}]`, file)
  })
  
  router.post(route('organisations.candidacy.apply', { organisation: props.organisation.slug }), data, {
    preserveScroll: true,
    onSuccess: () => {
      form.value = { election_id: '', post_id: '', supporter_name: '', proposer_name: '', manifesto: '', documents: [] }
      submitting.value = false
    },
    onError: () => { submitting.value = false }
  })
}
</script>
```

---

## 📋 Summary of Required Changes

| Task | Priority |
|------|----------|
| Create migration for `candidacy_applications` | 🔴 Critical |
| Create `CandidacyApplication` model | 🔴 Critical |
| Create `CandidacyApplicationController` | 🔴 Critical |
| Add route `organisations.candidacy.apply` | 🔴 Critical |
| Update VoterHub.vue with professional form | 🔴 Critical |
| Add `myApplications` prop from controller | 🟡 High |

Would you like me to write the complete implementation with all these changes?
## 🔍 Critical Analysis — You're Showing the WRONG Form

The file you pasted is **PostForm.vue** — which is for **creating election positions** (President, VP, etc.), **NOT** for candidacy applications.

### What You're Looking At vs. What You Need

| File | Purpose | Who Uses It |
|------|---------|-------------|
| `PostForm.vue` | Create/edit election **positions** (admin) | Election officers only |
| `CandidateForm.vue` | Create/edit **candidates** (admin) | Election officers only |
| **NEEDED** | **Candidacy application form** (voter) | **Voters/members** |

---

## ✅ Professional Solution — Separate Candidacy Application Form

Create a **new, dedicated form** for voters to apply as candidates:

### 1. Create `CandidacyApplicationForm.vue`

```vue
<!-- resources/js/Pages/Organisations/Partials/CandidacyApplicationForm.vue -->
<template>
  <form @submit.prevent="handleSubmit" class="space-y-6">
    
    <!-- Server validation errors -->
    <div v-if="Object.keys(errors).length" 
         class="bg-red-50 border border-red-200 rounded-lg px-4 py-3 text-sm text-red-800 space-y-1">
      <p v-for="(msg, field) in errors" :key="field">{{ Array.isArray(msg) ? msg[0] : msg }}</p>
    </div>

    <!-- Select Election -->
    <div>
      <label class="block text-sm font-medium text-neutral-700 mb-1">
        Election <span class="text-red-500">*</span>
      </label>
      <select 
        v-model="localForm.election_id" 
        required
        :disabled="isSubmitting"
        class="w-full border border-neutral-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500"
        @change="onElectionChange"
      >
        <option value="">Select an election</option>
        <option v-for="election in elections" :key="election.id" :value="election.id">
          {{ election.name }} ({{ formatDate(election.start_date) }} → {{ formatDate(election.end_date) }})
        </option>
      </select>
    </div>

    <!-- Select Post (Position) -->
    <div v-if="localForm.election_id">
      <label class="block text-sm font-medium text-neutral-700 mb-1">
        Position <span class="text-red-500">*</span>
      </label>
      <select 
        v-model="localForm.post_id" 
        required
        :disabled="isSubmitting"
        class="w-full border border-neutral-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500"
      >
        <option value="">Select a position</option>
        <option v-for="post in availablePosts" :key="post.id" :value="post.id">
          {{ post.name }} 
          <span v-if="!post.is_national_wide">({{ post.state_name }})</span>
          — {{ post.required_number }} seat{{ post.required_number !== 1 ? 's' : '' }}
        </option>
      </select>
    </div>

    <!-- Supporter & Proposer (Two-column layout) -->
    <div class="grid grid-cols-2 gap-4">
      <div>
        <label class="block text-sm font-medium text-neutral-700 mb-1">
          Supporter Name <span class="text-red-500">*</span>
        </label>
        <input 
          v-model="localForm.supporter_name" 
          type="text" 
          required
          maxlength="255"
          placeholder="Full name of supporting member"
          :disabled="isSubmitting"
          class="w-full border border-neutral-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500"
        />
        <p class="text-xs text-neutral-400 mt-1">A member who supports your candidacy</p>
      </div>
      
      <div>
        <label class="block text-sm font-medium text-neutral-700 mb-1">
          Proposer Name <span class="text-red-500">*</span>
        </label>
        <input 
          v-model="localForm.proposer_name" 
          type="text" 
          required
          maxlength="255"
          placeholder="Full name of proposing member"
          :disabled="isSubmitting"
          class="w-full border border-neutral-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500"
        />
        <p class="text-xs text-neutral-400 mt-1">A member who proposes your nomination</p>
      </div>
    </div>

    <!-- Manifesto -->
    <div>
      <label class="block text-sm font-medium text-neutral-700 mb-1">Manifesto / Statement</label>
      <textarea 
        v-model="localForm.manifesto" 
        rows="4" 
        maxlength="5000"
        placeholder="Why should members vote for you? What are your goals?"
        :disabled="isSubmitting"
        class="w-full border border-neutral-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary-500"
      ></textarea>
      <p class="text-xs text-neutral-400 mt-1 text-right">{{ localForm.manifesto.length }}/5000</p>
    </div>

    <!-- Document Upload -->
    <div>
      <label class="block text-sm font-medium text-neutral-700 mb-1">Supporting Documents</label>
      <input 
        type="file" 
        multiple 
        accept=".pdf,.jpg,.jpeg,.png"
        @change="handleFileUpload"
        :disabled="isSubmitting"
        class="w-full text-sm file:mr-2 file:py-1 file:px-3 file:rounded file:border-0 file:text-sm file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100"
      />
      <p class="text-xs text-neutral-400 mt-1">PDF, JPG, PNG up to 5MB each (max 5 files)</p>
      
      <!-- File list preview -->
      <div v-if="files.length > 0" class="mt-2 space-y-1">
        <div v-for="(file, idx) in files" :key="idx" class="flex items-center justify-between text-xs bg-neutral-50 px-2 py-1 rounded">
          <span class="text-neutral-600">{{ file.name }} ({{ formatFileSize(file.size) }})</span>
          <button type="button" @click="removeFile(idx)" class="text-red-500 hover:text-red-700">✕</button>
        </div>
      </div>
    </div>

    <!-- Submit Button -->
    <div class="flex gap-3 pt-4">
      <Button 
        type="submit" 
        variant="primary" 
        size="lg" 
        :loading="isSubmitting" 
        :disabled="!isFormValid || isSubmitting"
        class="flex-1"
      >
        Submit Candidacy Application
      </Button>
      <Button 
        type="button" 
        variant="outline" 
        size="lg" 
        :disabled="isSubmitting" 
        @click="$emit('cancel')"
      >
        Cancel
      </Button>
    </div>

    <!-- Info Note -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-xs text-blue-700">
      <strong>📋 Application Process:</strong> Your application will be reviewed by election officials. You will be notified once a decision is made.
    </div>

  </form>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import Button from '@/Components/Button.vue'

const props = defineProps({
  elections: { type: Array, required: true },
  initialForm: { type: Object, default: () => ({}) },
  isSubmitting: { type: Boolean, default: false },
  errors: { type: Object, default: () => ({}) },
})

const emit = defineEmits(['submit', 'cancel', 'update:election'])

// Local form state
const localForm = ref({
  election_id: props.initialForm.election_id || '',
  post_id: props.initialForm.post_id || '',
  supporter_name: props.initialForm.supporter_name || '',
  proposer_name: props.initialForm.proposer_name || '',
  manifesto: props.initialForm.manifesto || '',
})

const files = ref([])

// Available posts for selected election
const availablePosts = computed(() => {
  const election = props.elections.find(e => e.id === localForm.value.election_id)
  return election?.posts || []
})

// Form validation
const isFormValid = computed(() => {
  return localForm.value.election_id &&
         localForm.value.post_id &&
         localForm.value.supporter_name.trim() &&
         localForm.value.proposer_name.trim()
})

// Watch for election changes to reset post selection
watch(() => localForm.value.election_id, () => {
  localForm.value.post_id = ''
  emit('update:election', localForm.value.election_id)
})

function handleFileUpload(event) {
  const newFiles = Array.from(event.target.files)
  const totalFiles = [...files.value, ...newFiles]
  
  if (totalFiles.length > 5) {
    alert('Maximum 5 files allowed')
    return
  }
  
  for (const file of newFiles) {
    if (file.size > 5 * 1024 * 1024) {
      alert(`File ${file.name} exceeds 5MB limit`)
      return
    }
  }
  
  files.value = totalFiles
}

function removeFile(index) {
  files.value.splice(index, 1)
}

function formatFileSize(bytes) {
  if (bytes < 1024) return bytes + ' B'
  if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB'
  return (bytes / (1024 * 1024)).toFixed(1) + ' MB'
}

function formatDate(date) {
  return date ? new Date(date).toLocaleDateString('en-GB') : ''
}

function handleSubmit() {
  if (!isFormValid.value) {
    alert('Please fill all required fields')
    return
  }
  
  const formData = new FormData()
  formData.append('election_id', localForm.value.election_id)
  formData.append('post_id', localForm.value.post_id)
  formData.append('supporter_name', localForm.value.supporter_name)
  formData.append('proposer_name', localForm.value.proposer_name)
  if (localForm.value.manifesto) {
    formData.append('manifesto', localForm.value.manifesto)
  }
  
  files.value.forEach((file, i) => {
    formData.append(`documents[${i}]`, file)
  })
  
  emit('submit', formData)
}
</script>
```

---

### 2. Update Controller to Pass Posts with Elections

In `OrganisationController@voterHub`:

```php
$activeElections = Election::withoutGlobalScopes()
    ->where('organisation_id', $organisation->id)
    ->where('type', 'real')
    ->where('status', 'active')
    ->with(['posts' => fn($q) => $q->withoutGlobalScopes()->orderBy('position_order')])
    ->orderBy('start_date')
    ->get()
    ->map(fn ($e) => [
        'id' => $e->id,
        'name' => $e->name,
        'slug' => $e->slug,
        'start_date' => $e->start_date,
        'end_date' => $e->end_date,
        'description' => $e->description,
        'posts' => $e->posts->map(fn($p) => [
            'id' => $p->id,
            'name' => $p->name,
            'is_national_wide' => (bool) $p->is_national_wide,
            'state_name' => $p->state_name,
            'required_number' => $p->required_number,
        ]),
    ]));

// Also load user's existing applications
$myApplications = CandidacyApplication::where('user_id', $user->id)
    ->whereIn('election_id', $activeElections->pluck('id'))
    ->with(['election', 'post'])
    ->get()
    ->map(fn($a) => [
        'id' => $a->id,
        'election_id' => $a->election_id,
        'election_name' => $a->election->name,
        'post_name' => $a->post->name,
        'status' => $a->status,
        'status_label' => $this->getStatusLabel($a->status),
        'created_at' => $a->created_at->format('Y-m-d'),
    ]);
```

---

### 3. Update VoterHub.vue to Use the New Form

```vue
<!-- Replace the old candidacy link with the new form -->
<template>
  <!-- ... existing header and elections section ... -->
  
  <!-- RIGHT COLUMN: Candidacy Applications -->
  <section class="lg:col-span-1">
    <h2 class="text-lg font-semibold text-slate-800 mb-4">📋 Apply as Candidate</h2>
    
    <Card mode="admin" padding="lg">
      <CandidacyApplicationForm
        :elections="activeElections"
        :is-submitting="submitting"
        :errors="formErrors"
        @submit="submitApplication"
        @cancel="resetForm"
        @update:election="onElectionChange"
      />
    </Card>
    
    <!-- My Applications -->
    <div v-if="myApplications.length > 0" class="mt-6">
      <h3 class="text-md font-semibold text-slate-700 mb-3">My Applications</h3>
      <div class="space-y-2">
        <div v-for="app in myApplications" :key="app.id" class="bg-white rounded-lg border p-3">
          <div class="flex justify-between items-start">
            <div>
              <p class="font-medium text-sm">{{ app.post_name }}</p>
              <p class="text-xs text-slate-500">{{ app.election_name }}</p>
            </div>
            <span :class="applicationStatusClass(app.status)" class="px-2 py-0.5 rounded-full text-xs">
              {{ app.status_label }}
            </span>
          </div>
          <p class="text-xs text-slate-400 mt-2">Submitted: {{ app.created_at }}</p>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import CandidacyApplicationForm from './Partials/CandidacyApplicationForm.vue'

const submitting = ref(false)
const formErrors = ref({})

function submitApplication(formData) {
  submitting.value = true
  formErrors.value = {}
  
  router.post(
    route('organisations.candidacy.apply', { organisation: props.organisation.slug }),
    formData,
    {
      preserveScroll: true,
      onSuccess: () => {
        submitting.value = false
        resetForm()
      },
      onError: (errors) => {
        formErrors.value = errors
        submitting.value = false
      }
    }
  )
}

function resetForm() {
  // Reset handled by child component via initialForm prop
}

function onElectionChange(electionId) {
  // Optional: track selected election for analytics
}
</script>
```

---

## 📋 Summary

| What You Had | What You Need |
|--------------|---------------|
| `PostForm.vue` (admin: create positions) | `CandidacyApplicationForm.vue` (voter: apply) |
| Link to admin posts page | Dedicated application form with supporter/proposer |
| No supporter/proposer fields | Required fields for nomination |
| No application tracking | "My Applications" section |
| No document upload | File upload for supporting docs |

This is a **complete, professional candidacy application system** with proper separation of concerns. Would you like me to write the full implementation with all required files?
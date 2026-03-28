## Claude Code CLI Prompt — Senior Prompt Engineer Version

```
We need to implement two new pages for the organisation section: Voter Hub and Election Commission. This requires a complete architectural overhaul following best practices.

## Context

Current file: resources/js/Pages/Organisations/Show.vue contains mixed voter and admin content. We're splitting it into three focused pages:
- Show.vue → Organisation overview (stats, quick links, welcome)
- VoterHub.vue → Voter-facing content (active elections, candidacy applications)
- ElectionCommission.vue → Admin-facing content (management dashboards)

## Implementation Requirements

### 1. Backend Services Layer (Create these files)

Create app/Services/Organisation/
- VoterHubService.php
- ElectionCommissionService.php  
- CandidacyApplicationService.php

Each service should:
- Accept dependencies via constructor injection
- Use repository pattern for data access
- Implement caching with proper invalidation keys
- Return DTOs or arrays, never Eloquent models directly
- Handle transactions for write operations

Example structure for VoterHubService:
```php
<?php
namespace App\Services\Organisation;

use App\Models\Organisation;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class VoterHubService
{
    public function __construct(
        private ElectionRepository $electionRepo,
        private MembershipRepository $membershipRepo
    ) {}
    
    public function getData(Organisation $organisation, ?User $user): array
    {
        $cacheKey = "voter_hub_{$organisation->id}_{$user?->id}";
        
        return Cache::remember($cacheKey, now()->addMinutes(15), function () use ($organisation, $user) {
            $activeElections = $this->electionRepo->getActiveWithUserStatus($organisation, $user);
            $userApplications = $user ? $this->getUserApplications($user, $organisation) : [];
            
            return [
                'organisation' => $organisation->only('id', 'name', 'slug'),
                'activeElections' => $activeElections->map(fn($e) => [
                    'id' => $e->id,
                    'name' => $e->name,
                    'slug' => $e->slug,
                    'start_date' => $e->start_date,
                    'end_date' => $e->end_date,
                    'description' => $e->description,
                    'posts' => $e->posts->map(fn($p) => [
                        'id' => $p->id,
                        'name' => $p->name,
                        'required_number' => $p->required_number,
                    ]),
                    'voter_status' => $e->user_membership?->status ?? 'ineligible',
                    'has_voted' => $e->user_membership?->has_voted ?? false,
                ]),
                'user' => $user ? [
                    'id' => $user->id,
                    'name' => $user->name,
                    'is_member' => $user->belongsToOrganisation($organisation),
                    'pending_applications' => $userApplications['pending'],
                    'approved_applications' => $userApplications['approved'],
                ] : null,
                'can_apply_for_candidacy' => $this->canApplyForCandidacy($user, $organisation),
            ];
        });
    }
    
    public function applyForCandidacy(User $user, Organisation $organisation, array $data): array
    {
        $this->validateEligibility($user, $organisation, $data['election_id'], $data['post_id']);
        
        DB::transaction(function () use ($user, $organisation, $data) {
            $application = CandidacyApplication::create([
                'user_id' => $user->id,
                'organisation_id' => $organisation->id,
                'election_id' => $data['election_id'],
                'post_id' => $data['post_id'],
                'manifesto' => $data['manifesto'] ?? null,
                'documents' => $this->handleDocumentUploads($data['documents'] ?? []),
                'status' => 'pending',
            ]);
            
            // Invalidate cache
            Cache::forget("voter_hub_{$organisation->id}_{$user->id}");
            
            // Notify election officials
            \App\Notifications\CandidacyApplicationReceived::send($application);
        });
        
        return ['success' => true, 'message' => 'Application submitted successfully'];
    }
}
```

### 2. Repository Pattern

Create app/Repositories/
- ElectionRepository.php
- MembershipRepository.php
- CandidacyApplicationRepository.php

Each repository should:
- Handle all database queries
- Use withoutGlobalScopes() appropriately
- Return collections of arrays, not models
- Implement caching for read operations

Example:
```php
<?php
namespace App\Repositories;

use App\Models\Election;
use App\Models\User;
use Illuminate\Support\Collection;

class ElectionRepository
{
    public function getActiveWithUserStatus(Organisation $organisation, ?User $user): Collection
    {
        return Election::withoutGlobalScopes()
            ->where('organisation_id', $organisation->id)
            ->where('type', 'real')
            ->where('status', 'active')
            ->with(['posts' => fn($q) => $q->orderBy('position_order')])
            ->with(['memberships' => fn($q) => $q->when($user, fn($q) => $q->where('user_id', $user->id))])
            ->orderBy('start_date')
            ->get()
            ->map(function ($election) use ($user) {
                $election->user_membership = $election->memberships->first();
                unset($election->memberships);
                return $election;
            });
    }
}
```

### 3. Policies

Update app/Policies/OrganisationPolicy.php:
- Add viewVoterHub() method
- Add viewElectionCommission() method
- Ensure proper role-based access (voter hub = any member, commission = officers only)

### 4. Controller Updates

Update app/Http/Controllers/OrganisationController.php:
- Inject services in constructor
- Keep controllers thin (5 lines max per method)
- Add voterHub() and electionCommission() methods

```php
public function voterHub(Organisation $organisation): Response
{
    $this->authorize('viewVoterHub', $organisation);
    
    return Inertia::render('Organisations/VoterHub', 
        $this->voterHubService->getData($organisation, auth()->user())
    );
}

public function electionCommission(Organisation $organisation): Response
{
    $this->authorize('viewElectionCommission', $organisation);
    
    return Inertia::render('Organisations/ElectionCommission', 
        $this->commissionService->getData($organisation, auth()->user())
    );
}
```

### 5. Routes

Update routes/organisations.php:
```php
Route::prefix('organisations/{organisation:slug}')->group(function () {
    Route::get('/', [OrganisationController::class, 'show'])->name('organisations.show');
    Route::get('/voter-hub', [OrganisationController::class, 'voterHub'])->name('organisations.voter-hub');
    Route::get('/election-commission', [OrganisationController::class, 'electionCommission'])->name('organisations.election-commission');
    
    // Candidacy application routes
    Route::post('/candidacy/apply', [CandidacyApplicationController::class, 'store'])->name('organisations.candidacy.apply');
    Route::get('/candidacy/status', [CandidacyApplicationController::class, 'index'])->name('organisations.candidacy.status');
    
    // Existing routes remain
});
```

### 6. Frontend Components

Create reusable components in resources/js/Components/Election/:
- ElectionCard.vue (replaces duplicate election displays)
- VoterStatusBadge.vue
- CandidacyForm.vue
- Breadcrumb.vue

### 7. Vue Pages

Create resources/js/Pages/Organisations/VoterHub.vue:
- Import ElectionLayout
- Display active elections with "Vote Now" buttons
- Show candidacy application form for eligible members
- Display user's existing applications
- Use composable for state management

Create resources/js/Pages/Organisations/ElectionCommission.vue:
- Import ElectionLayout
- Display management cards (voters, elections, posts, results)
- Show quick stats dashboard
- Use existing ActionButton and SectionCard components
- Add audit trail preview

### 8. Composables

Create resources/js/Composables/useVoterHub.js:
```javascript
import { ref, computed } from 'vue'
import { router, usePage } from '@inertiajs/vue3'

export function useVoterHub() {
    const page = usePage()
    const submitting = ref(false)
    const formData = ref({
        election_id: null,
        post_id: null,
        manifesto: '',
        documents: []
    })
    
    const canApply = computed(() => {
        const user = page.props.auth.user
        return user?.is_member && !user?.has_voted && page.props.can_apply_for_candidacy
    })
    
    const submitApplication = async () => {
        submitting.value = true
        try {
            await router.post(
                route('organisations.candidacy.apply', { 
                    organisation: page.props.organisation.slug 
                }),
                formData.value,
                { preserveScroll: true }
            )
            formData.value = { election_id: null, post_id: null, manifesto: '', documents: [] }
        } finally {
            submitting.value = false
        }
    }
    
    return { formData, submitting, canApply, submitApplication }
}
```

### 9. Update Show.vue

Modify resources/js/Pages/Organisations/Show.vue:
- Keep only overview content
- Add navigation cards at top linking to VoterHub and ElectionCommission
- Remove duplicated content that's moving to new pages
- Keep stats grid and quick stats

Add navigation section:
```vue
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
    <Link :href="route('organisations.voter-hub', organisation.slug)">
        <Card mode="admin" class="p-6 text-center hover:shadow-lg transition">
            <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <h3 class="font-semibold mb-1">Voter Hub</h3>
            <p class="text-sm text-slate-500">View active elections, apply as candidate, and cast your vote</p>
        </Card>
    </Link>
    
    <Link :href="route('organisations.election-commission', organisation.slug)" v-if="canManage || isOfficer">
        <Card mode="admin" class="p-6 text-center hover:shadow-lg transition">
            <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <h3 class="font-semibold mb-1">Election Commission</h3>
            <p class="text-sm text-slate-500">Manage elections, voters, candidates, and results</p>
        </Card>
    </Link>
</div>
```

### 10. Database

Create migration for candidacy_applications table:
```php
Schema::create('candidacy_applications', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->uuid('user_id');
    $table->uuid('organisation_id');
    $table->uuid('election_id');
    $table->uuid('post_id');
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
    $table->index(['user_id', 'election_id']);
    $table->index(['status', 'created_at']);
});
```

### 11. Testing

Add tests for:
- Service layer with mocked repositories
- Policy authorization
- Controller responses
- Frontend component rendering

### 12. Build Verification

After implementation:
- Run `npm run build` — expect zero errors
- Run `php artisan test` — ensure all tests pass
- Manual verification: visit /organisations/{slug} and navigate to both new pages

## Important Constraints

1. DO NOT duplicate code — extract to services/repositories/composables
2. Maintain design system consistency (use existing Card, Button, ActionButton)
3. Add proper error boundaries and loading states
4. Cache aggressively with 15-minute TTL for read operations
5. Use transactions for all write operations
6. Add proper authorization checks at controller and policy levels
7. Keep controllers under 20 lines each

Start implementing from Phase 1 (backend services and repositories) and work upwards. Do not proceed to frontend until backend is complete with tests.
```

## 🎯 Hybrid Architecture — Pragmatic MVC with DDD Principles

You're right — full DDD is overkill for this phase. Let's implement a **pragmatic hybrid** that gives us clean separation without the complexity.

---

## Claude Code CLI Prompt — Pragmatic Hybrid Architecture

```
We need to implement Voter Hub and Election Commission pages with clean architecture that balances maintainability with pragmatism.

## Core Principles (Pragmatic)

1. **Thin Controllers** — Only HTTP concerns, validation, authorization
2. **Service Layer** — Business logic, transactions, caching
3. **Repository Pattern** — Data access abstraction (simple, no interfaces unless needed)
4. **DTOs** — Data transfer between layers
5. **Accessibility First** — WCAG 2.1 AA compliance

## Directory Structure (Simplified)

```
app/
├── Http/
│   ├── Controllers/
│   │   └── OrganisationController.php
│   └── Requests/
│       ├── VoterHubRequest.php
│       └── CandidacyApplicationRequest.php
│
├── Services/
│   ├── Organisation/
│   │   ├── VoterHubService.php
│   │   ├── ElectionCommissionService.php
│   │   └── CandidacyApplicationService.php
│   └── Shared/
│       ├── CacheService.php
│       └── FileUploadService.php
│
├── Repositories/
│   ├── ElectionRepository.php
│   ├── MembershipRepository.php
│   └── CandidacyApplicationRepository.php
│
└── DTOs/
    ├── VoterHubDataDTO.php
    ├── ElectionCommissionDataDTO.php
    └── CandidacySubmissionDTO.php

resources/js/
├── Pages/Organisations/
│   ├── Show.vue (updated with navigation cards)
│   ├── VoterHub.vue
│   └── ElectionCommission.vue
│
├── Components/
│   ├── Election/
│   │   ├── ElectionCard.vue
│   │   └── VoterStatusBadge.vue
│   ├── Accessibility/
│   │   ├── SkipLink.vue
│   │   └── LiveRegion.vue
│   └── Candidacy/
│       └── CandidacyForm.vue
│
└── Composables/
    ├── useVoterHub.js
    └── useFocusManagement.js
```

---

## 🔧 Phase 1: DTOs (Simple Data Objects)

Create app/DTOs/VoterHubDataDTO.php:

```php
<?php

namespace App\DTOs;

use Illuminate\Support\Collection;

class VoterHubDataDTO
{
    public function __construct(
        public readonly Collection $activeElections,
        public readonly Collection $userApplications,
        public readonly bool $canApplyForCandidacy,
        public readonly array $userVoterStatus = []
    ) {}
    
    public function toArray(): array
    {
        return [
            'active_elections' => $this->activeElections->map(fn($election) => [
                'id' => $election['id'],
                'name' => $election['name'],
                'slug' => $election['slug'],
                'start_date' => $election['start_date']->format('Y-m-d'),
                'end_date' => $election['end_date']->format('Y-m-d'),
                'description' => $election['description'],
                'voter_status' => $this->userVoterStatus[$election['id']] ?? 'ineligible',
                'posts' => $election['posts']->map(fn($post) => [
                    'id' => $post->id,
                    'name' => $post->name,
                ]),
            ])->values(),
            'user_applications' => $this->userApplications,
            'can_apply_for_candidacy' => $this->canApplyForCandidacy,
        ];
    }
}
```

---

## 🔧 Phase 2: Repositories (Simple Data Access)

Create app/Repositories/ElectionRepository.php:

```php
<?php

namespace App\Repositories;

use App\Models\Election;
use App\Models\Candidacy;
use Illuminate\Support\Collection;

class ElectionRepository
{
    public function getActiveWithDetails(string $organisationId, ?string $userId = null): Collection
    {
        $elections = Election::withoutGlobalScopes()
            ->where('organisation_id', $organisationId)
            ->where('type', 'real')
            ->where('status', 'active')
            ->with(['posts' => fn($q) => $q->orderBy('position_order')])
            ->orderBy('start_date')
            ->get();
        
        if (!$userId) {
            return $elections;
        }
        
        // Load user membership status for each election
        $elections->load(['memberships' => fn($q) => $q->where('user_id', $userId)]);
        
        return $elections;
    }
    
    public function getCompletedWithResults(string $organisationId): Collection
    {
        return Election::withoutGlobalScopes()
            ->where('organisation_id', $organisationId)
            ->where('status', 'completed')
            ->orderBy('end_date', 'desc')
            ->get();
    }
    
    public function getCandidacyApplications(string $electionId, string $postId): Collection
    {
        return Candidacy::withoutGlobalScopes()
            ->where('election_id', $electionId)
            ->where('post_id', $postId)
            ->where('status', 'approved')
            ->orderBy('position_order')
            ->get();
    }
}
```

Create app/Repositories/CandidacyApplicationRepository.php:

```php
<?php

namespace App\Repositories;

use App\Models\CandidacyApplication;
use Illuminate\Support\Collection;

class CandidacyApplicationRepository
{
    public function findByUser(string $userId, string $organisationId): Collection
    {
        return CandidacyApplication::withoutGlobalScopes()
            ->where('user_id', $userId)
            ->where('organisation_id', $organisationId)
            ->with(['election', 'post'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($app) => [
                'id' => $app->id,
                'election_name' => $app->election?->name,
                'post_name' => $app->post?->name,
                'status' => $app->status,
                'status_label' => $this->getStatusLabel($app->status),
                'submitted_at' => $app->created_at->format('Y-m-d'),
            ]);
    }
    
    public function create(array $data): CandidacyApplication
    {
        return CandidacyApplication::create($data);
    }
    
    private function getStatusLabel(string $status): string
    {
        return match($status) {
            'pending' => 'Under Review',
            'approved' => 'Approved ✓',
            'rejected' => 'Not Approved',
            default => $status,
        };
    }
}
```

---

## 🔧 Phase 3: Service Layer (Business Logic)

Create app/Services/Organisation/VoterHubService.php:

```php
<?php

namespace App\Services\Organisation;

use App\DTOs\VoterHubDataDTO;
use App\Repositories\ElectionRepository;
use App\Repositories\CandidacyApplicationRepository;
use App\Services\Shared\CacheService;
use App\Models\User;
use App\Models\Organisation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VoterHubService
{
    public function __construct(
        private ElectionRepository $electionRepo,
        private CandidacyApplicationRepository $applicationRepo,
        private CacheService $cache
    ) {}
    
    public function getData(Organisation $organisation, ?User $user): VoterHubDataDTO
    {
        $cacheKey = $this->getCacheKey($organisation->id, $user?->id);
        
        return $this->cache->remember($cacheKey, 900, function () use ($organisation, $user) {
            // Get active elections
            $elections = $this->electionRepo->getActiveWithDetails(
                $organisation->id,
                $user?->id
            );
            
            // Get user's voter status for each election
            $voterStatus = [];
            foreach ($elections as $election) {
                $membership = $election->memberships->first();
                $voterStatus[$election->id] = $this->determineVoterStatus($membership);
            }
            
            // Get user's candidacy applications
            $applications = $user 
                ? $this->applicationRepo->findByUser($user->id, $organisation->id)
                : collect();
            
            // Check if user can apply for candidacy
            $canApply = $user && $this->canApplyForCandidacy($user, $organisation);
            
            return new VoterHubDataDTO(
                activeElections: $elections,
                userApplications: $applications,
                canApplyForCandidacy: $canApply,
                userVoterStatus: $voterStatus
            );
        });
    }
    
    public function applyForCandidacy(
        User $user,
        Organisation $organisation,
        array $data
    ): array {
        // Validate eligibility
        $this->validateCandidacyEligibility($user, $organisation, $data);
        
        DB::transaction(function () use ($user, $organisation, $data) {
            // Upload documents if any
            $documents = [];
            if (!empty($data['documents'])) {
                $documents = $this->uploadDocuments($data['documents'], $user, $organisation);
            }
            
            // Create application
            $application = $this->applicationRepo->create([
                'id' => (string) \Illuminate\Support\Str::uuid(),
                'user_id' => $user->id,
                'organisation_id' => $organisation->id,
                'election_id' => $data['election_id'],
                'post_id' => $data['post_id'],
                'manifesto' => $data['manifesto'] ?? null,
                'documents' => $documents,
                'status' => 'pending',
            ]);
            
            // Invalidate cache
            $this->cache->forget($this->getCacheKey($organisation->id, $user->id));
            
            // Queue notification
            dispatch(new \App\Jobs\NotifyOfficersOfCandidacy($application));
            
            Log::info('Candidacy application submitted', [
                'user_id' => $user->id,
                'application_id' => $application->id,
                'election_id' => $data['election_id'],
            ]);
        });
        
        return [
            'success' => true,
            'message' => 'Your application has been submitted for review.',
        ];
    }
    
    private function determineVoterStatus(?object $membership): string
    {
        if (!$membership || $membership->status === 'removed') {
            return 'ineligible';
        }
        
        if ($membership->has_voted) {
            return 'voted';
        }
        
        if ($membership->status === 'suspended') {
            return 'suspended';
        }
        
        if ($membership->status === 'active' && $membership->role === 'voter') {
            return 'eligible';
        }
        
        return 'ineligible';
    }
    
    private function canApplyForCandidacy(User $user, Organisation $organisation): bool
    {
        // Must be a member of the organisation
        if (!$user->belongsToOrganisation($organisation->id)) {
            return false;
        }
        
        // Cannot have active applications or already be a candidate
        $existingApplications = $this->applicationRepo->findByUser(
            $user->id,
            $organisation->id
        );
        
        return $existingApplications->whereIn('status', ['pending', 'approved'])->isEmpty();
    }
    
    private function validateCandidacyEligibility(User $user, Organisation $organisation, array $data): void
    {
        if (!$this->canApplyForCandidacy($user, $organisation)) {
            abort(403, 'You are not eligible to apply for candidacy.');
        }
        
        // Validate election exists and is active
        $election = \App\Models\Election::withoutGlobalScopes()
            ->where('id', $data['election_id'])
            ->where('organisation_id', $organisation->id)
            ->where('status', 'active')
            ->first();
            
        if (!$election) {
            abort(404, 'Election not found or not active.');
        }
        
        // Validate post exists for this election
        $post = \App\Models\Post::withoutGlobalScopes()
            ->where('id', $data['post_id'])
            ->where('election_id', $election->id)
            ->first();
            
        if (!$post) {
            abort(404, 'Position not found for this election.');
        }
    }
    
    private function uploadDocuments(array $documents, User $user, Organisation $organisation): array
    {
        $uploaded = [];
        foreach ($documents as $document) {
            $path = $document->store(
                "candidacy_applications/{$organisation->id}/{$user->id}",
                'public'
            );
            $uploaded[] = $path;
        }
        return $uploaded;
    }
    
    private function getCacheKey(string $organisationId, ?string $userId): string
    {
        return sprintf('voter_hub_%s_%s', $organisationId, $userId ?? 'guest');
    }
}
```

---

## 🔧 Phase 4: Thin Controllers

Create app/Http/Controllers/OrganisationController.php (updated):

```php
<?php

namespace App\Http\Controllers;

use App\Http\Requests\CandidacyApplicationRequest;
use App\Models\Organisation;
use App\Services\Organisation\VoterHubService;
use App\Services\Organisation\ElectionCommissionService;
use Inertia\Inertia;
use Inertia\Response;

class OrganisationController extends Controller
{
    public function __construct(
        private VoterHubService $voterHubService,
        private ElectionCommissionService $commissionService
    ) {}
    
    /**
     * Voter Hub - Accessible to all authenticated users
     */
    public function voterHub(Organisation $organisation): Response
    {
        $this->authorize('view', $organisation);
        
        $data = $this->voterHubService->getData($organisation, auth()->user());
        
        return Inertia::render('Organisations/VoterHub', [
            'organisation' => $organisation->only('id', 'name', 'slug'),
            'data' => $data->toArray(),
            'user' => auth()->user()?->only('id', 'name', 'is_member'),
        ]);
    }
    
    /**
     * Election Commission - Officers only
     */
    public function electionCommission(Organisation $organisation): Response
    {
        $this->authorize('manage', $organisation);
        
        $data = $this->commissionService->getData($organisation, auth()->user());
        
        return Inertia::render('Organisations/ElectionCommission', [
            'organisation' => $organisation->only('id', 'name', 'slug'),
            'data' => $data->toArray(),
            'user' => auth()->user(),
        ]);
    }
    
    /**
     * Submit candidacy application
     */
    public function submitCandidacy(
        CandidacyApplicationRequest $request,
        Organisation $organisation
    ) {
        $this->authorize('view', $organisation);
        
        $result = $this->voterHubService->applyForCandidacy(
            auth()->user(),
            $organisation,
            $request->validated()
        );
        
        return back()->with('success', $result['message']);
    }
}
```

---

## 🔧 Phase 5: Request Validation

Create app/Http/Requests/CandidacyApplicationRequest.php:

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CandidacyApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }
    
    public function rules(): array
    {
        return [
            'election_id' => ['required', 'uuid', 'exists:elections,id'],
            'post_id' => ['required', 'uuid', 'exists:posts,id'],
            'manifesto' => ['nullable', 'string', 'max:5000'],
            'documents' => ['nullable', 'array', 'max:5'],
            'documents.*' => ['file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'], // 5MB
        ];
    }
    
    public function messages(): array
    {
        return [
            'election_id.required' => 'Please select an election.',
            'post_id.required' => 'Please select a position.',
            'documents.*.max' => 'Each document must be less than 5MB.',
        ];
    }
}
```

---

## 🔧 Phase 6: Vue Composables

Create resources/js/Composables/useVoterHub.js:

```javascript
import { ref, computed } from 'vue'
import { router, usePage } from '@inertiajs/vue3'

export function useVoterHub() {
    const page = usePage()
    const submitting = ref(false)
    const selectedElection = ref(null)
    const selectedPost = ref(null)
    const formData = ref({
        election_id: null,
        post_id: null,
        manifesto: '',
        documents: []
    })
    
    const canApply = computed(() => {
        return page.props.data?.can_apply_for_candidacy && 
               page.props.user?.is_member
    })
    
    const submitApplication = async () => {
        if (!canApply.value) return
        
        submitting.value = true
        
        try {
            const form = new FormData()
            form.append('election_id', formData.value.election_id)
            form.append('post_id', formData.value.post_id)
            if (formData.value.manifesto) {
                form.append('manifesto', formData.value.manifesto)
            }
            
            // Append documents
            for (let i = 0; i < formData.value.documents.length; i++) {
                form.append(`documents[${i}]`, formData.value.documents[i])
            }
            
            await router.post(
                route('organisations.candidacy.apply', {
                    organisation: page.props.organisation.slug
                }),
                form,
                { preserveScroll: true }
            )
            
            // Reset form
            formData.value = {
                election_id: null,
                post_id: null,
                manifesto: '',
                documents: []
            }
            selectedElection.value = null
            selectedPost.value = null
            
        } finally {
            submitting.value = false
        }
    }
    
    const handleFileUpload = (event) => {
        const files = Array.from(event.target.files)
        formData.value.documents = [...formData.value.documents, ...files]
    }
    
    const removeDocument = (index) => {
        formData.value.documents.splice(index, 1)
    }
    
    const getVoterStatusLabel = (status) => {
        const labels = {
            eligible: 'You are eligible to vote',
            ineligible: 'You are not registered to vote',
            voted: 'You have already voted',
            suspended: 'Your voting rights are suspended'
        }
        return labels[status] || 'Status unknown'
    }
    
    return {
        formData,
        submitting,
        canApply,
        selectedElection,
        selectedPost,
        submitApplication,
        handleFileUpload,
        removeDocument,
        getVoterStatusLabel
    }
}
```

---

## 🔧 Phase 7: Accessibility Components

Create resources/js/Components/Accessibility/SkipLink.vue:

```vue
<template>
  <a
    href="#main-content"
    class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-50 focus:px-4 focus:py-2 focus:bg-primary-600 focus:text-white focus:rounded-lg focus:shadow-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2"
  >
    Skip to main content
  </a>
</template>
```

Create resources/js/Components/Accessibility/LiveRegion.vue:

```vue
<template>
  <div
    role="status"
    aria-live="polite"
    aria-atomic="true"
    class="sr-only"
  >
    {{ message }}
  </div>
</template>

<script setup>
defineProps({
  message: { type: String, default: '' }
})
</script>
```

---

## 🔧 Phase 8: Update Show.vue Navigation

Add navigation cards to Show.vue (after organisation header):

```vue
<!-- Navigation Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
  <Link :href="route('organisations.voter-hub', organisation.slug)">
    <Card mode="admin" class="p-6 text-center hover:shadow-lg transition-all hover:-translate-y-0.5">
      <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-3">
        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
        </svg>
      </div>
      <h3 class="font-semibold text-slate-800 mb-1">Voter Hub</h3>
      <p class="text-sm text-slate-500">View elections, apply as candidate, cast your vote</p>
    </Card>
  </Link>
  
  <Link 
    v-if="canManage || isOfficer"
    :href="route('organisations.election-commission', organisation.slug)"
  >
    <Card mode="admin" class="p-6 text-center hover:shadow-lg transition-all hover:-translate-y-0.5">
      <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-3">
        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
      </div>
      <h3 class="font-semibold text-slate-800 mb-1">Election Commission</h3>
      <p class="text-sm text-slate-500">Manage elections, voters, candidates, results</p>
    </Card>
  </Link>
</div>
```

---

## 🔧 Phase 9: Routes

Update routes/organisations.php:

```php
Route::prefix('organisations/{organisation:slug}')->group(function () {
    Route::get('/', [OrganisationController::class, 'show'])->name('organisations.show');
    Route::get('/voter-hub', [OrganisationController::class, 'voterHub'])->name('organisations.voter-hub');
    Route::get('/election-commission', [OrganisationController::class, 'electionCommission'])->name('organisations.election-commission');
    
    // Candidacy application
    Route::post('/candidacy/apply', [OrganisationController::class, 'submitCandidacy'])->name('organisations.candidacy.apply');
    
    // Existing routes...
});
```

---

## ✅ Implementation Checklist

### Backend
- [ ] Create DTOs (2 files)
- [ ] Create Repositories (2 files)
- [ ] Create Services (2 files)
- [ ] Create Request validation (1 file)
- [ ] Update Controller (1 file)
- [ ] Update Routes (1 file)
- [ ] Run migrations for candidacy_applications table

### Frontend
- [ ] Create SkipLink and LiveRegion components
- [ ] Create useVoterHub composable
- [ ] Create VoterHub.vue page
- [ ] Create ElectionCommission.vue page
- [ ] Update Show.vue with navigation cards
- [ ] Build and test

### Accessibility
- [ ] Add ARIA labels to all interactive elements
- [ ] Ensure keyboard navigation works
- [ ] Test with screen reader (NVDA/VoiceOver)
- [ ] Verify color contrast (4.5:1 minimum)

## Build & Test

```bash
# Build assets
npm run build

# Run migrations
php artisan migrate

# Test the pages
# Visit /organisations/{slug}/voter-hub
# Visit /organisations/{slug}/election-commission
```

This hybrid approach gives us clean separation without the complexity of full DDD. Start implementing from Phase 1 and work upwards.
```


now we need to have two more pages. 
1)  page which contains all Voter related links , information  and candidacy forms etc 
2) page which contains all election commission related information and links like voter management links, election management links etc. 
these two pages must be linked in organisations main page . 
currently all information is given in organisation main page . 
http://localhost:8000/organisations/namsate-nepal

## Understanding Your Request

You want to reorganize the organisation page by splitting content into **two dedicated pages**:

1. **Voter Hub** — For voters: candidacy applications, voting info, election dates, etc.
2. **Election Commission Hub** — For election officials: voter management, election management, results, etc.

Currently, all this content is mixed on the main organisation page. Let me first see the current structure:

```bash
cat resources/js/Pages/Organisations/Show.vue | head -100
```

But before I propose a solution, let me clarify:

---

## Proposed Architecture

```
/organisations/{slug}
├── /                           → Landing page (org info, stats, quick links)
├── /voter-hub                  → Voter page (candidacy forms, voting guide, dates)
└── /election-commission        → Admin page (voter management, election settings, results)
```

---

## Questions to Clarify

1. **What content should go to each page?**

   From your current organisation page, I see:
   - Election cards (active/past elections)
   - Stats
   - Links to elections
   - Probably voter-related content?

2. **Should the current organisation page become just an overview/landing page?**

3. **What specific candidacy forms do you need?**
   - Self-nomination form for members?
   - Application form with documents?
   - Endorsement workflow?

4. **What voter-related information?**
   - Election calendar?
   - Voter eligibility requirements?
   - How to vote guide?
   - Live results?

5. **What election commission links?**
   - Voter list management (already exists)
   - Election settings (dates, status)
   - Post/candidate management (just built)
   - Results publishing

---

## Quick Implementation Plan

### Step 1: Create Two New Pages

```bash
# Voter Hub
resources/js/Pages/Organisations/VoterHub.vue

# Election Commission Hub  
resources/js/Pages/Organisations/ElectionCommission.vue
```

### Step 2: Add Routes

```php
// routes/organisations.php
Route::prefix('organisations/{organisation:slug}')->group(function () {
    Route::get('/', [OrganisationController::class, 'show'])->name('organisations.show');
    Route::get('/voter-hub', [OrganisationController::class, 'voterHub'])->name('organisations.voter-hub');
    Route::get('/election-commission', [OrganisationController::class, 'electionCommission'])->name('organisations.election-commission');
});
```

### Step 3: Add Navigation Links on Main Page

```vue
<!-- In Show.vue, add cards/sections linking to new pages -->
<div class="grid grid-cols-2 gap-4">
    <Link :href="route('organisations.voter-hub', organisation.slug)">
        <Card class="p-6 text-center">
            <h3>Voter Hub</h3>
            <p>View elections, apply as candidate, vote</p>
        </Card>
    </Link>
    <Link :href="route('organisations.election-commission', organisation.slug)">
        <Card class="p-6 text-center">
            <h3>Election Commission</h3>
            <p>Manage voters, elections, candidates</p>
        </Card>
    </Link>
</div>
```

---

Can you share:
1. The current `Organisations/Show.vue` content?
2. What specific candidacy forms are needed?
3. What voter info should be displayed?

This will help me create exactly what you need.
Perfect! Now I can see the full structure. You have a comprehensive organisation page with multiple sections. Now let's create the two dedicated pages:

## Understanding Current Content

The organisation page currently has:
- **Voter-facing content**: Active elections with "Vote Now" buttons, election cards
- **Admin-facing content**: Election management, voter management, officer management, results management

---

## Proposed Split

### Page 1: **Voter Hub** (`/organisations/{slug}/voter-hub`)
For voters:
- Active elections (voting cards)
- Election information (dates, positions)
- Candidacy application forms
- Voter registration status
- Voting guide
- How to vote instructions

### Page 2: **Election Commission** (`/organisations/{slug}/election-commission`)
For election officials:
- Election management (create, activate, close)
- Voter management (approve/suspend voters)
- Post & candidate management
- Results management (publish/unpublish)
- Officer management

---

## Implementation Plan

Let me create the two new pages with proper navigation from the main organisation page.

```vue
<!-- resources/js/Pages/Organisations/VoterHub.vue -->
```

```vue
<!-- resources/js/Pages/Organisations/ElectionCommission.vue -->
```

Then update:
1. Routes in `routes/organisations.php`
2. Add navigation links to `Show.vue`

---

## Step 1: Add Routes

```php
// routes/organisations.php
Route::prefix('organisations/{organisation:slug}')->group(function () {
    Route::get('/', [OrganisationController::class, 'show'])->name('organisations.show');
    Route::get('/voter-hub', [OrganisationController::class, 'voterHub'])->name('organisations.voter-hub');
    Route::get('/election-commission', [OrganisationController::class, 'electionCommission'])->name('organisations.election-commission');
    
    // ... existing routes
});
```

## Step 2: Update OrganisationController

```php
// app/Http/Controllers/OrganisationController.php
public function voterHub(Organisation $organisation): Response
{
    $this->authorize('view', $organisation);
    
    $user = auth()->user();
    $activeElections = Election::withoutGlobalScopes()
        ->where('organisation_id', $organisation->id)
        ->where('type', 'real')
        ->where('status', 'active')
        ->get();
    
    // Get voter membership status for each election
    $voterMemberships = [];
    foreach ($activeElections as $election) {
        $membership = ElectionMembership::withoutGlobalScopes()
            ->where('election_id', $election->id)
            ->where('user_id', $user?->id)
            ->first();
        $voterMemberships[$election->id] = $membership;
    }
    
    return Inertia::render('Organisations/VoterHub', [
        'organisation' => $organisation,
        'activeElections' => $activeElections,
        'voterMemberships' => $voterMemberships,
        'user' => $user,
    ]);
}

public function electionCommission(Organisation $organisation): Response
{
    $this->authorize('view', $organisation);
    
    $elections = Election::withoutGlobalScopes()
        ->where('organisation_id', $organisation->id)
        ->orderBy('created_at', 'desc')
        ->get();
    
    $stats = [
        'members_count' => $organisation->members()->count(),
        'active_members_count' => $organisation->members()->where('status', 'active')->count(),
        'active_elections_count' => $elections->where('status', 'active')->count(),
    ];
    
    return Inertia::render('Organisations/ElectionCommission', [
        'organisation' => $organisation,
        'elections' => $elections,
        'stats' => $stats,
        'canManage' => auth()->user()->can('manage', $organisation),
        'canCreateElection' => auth()->user()->can('create', [Election::class, $organisation]),
        'canManageVoters' => auth()->user()->can('manageVoters', $organisation),
        'canPublishResults' => auth()->user()->can('publishResults', $organisation),
    ]);
}
```

---

Would you like me to write the full Vue components for:
1. **VoterHub.vue** (with candidacy forms)
2. **ElectionCommission.vue** (with management links)

Also, what specific candidacy forms do you need? Options:
- Self-nomination form (user applies to be candidate)
- Document upload (CV, photo, manifesto)
- Endorsement by members
- Admin approval workflow
## 🔍 Senior Architect Review: Proposed Voter Hub & Election Commission Pages

### Overall Assessment: **7/10** — Good foundation but needs significant architectural improvements

---

## 🔴 Critical Issues with My Initial Suggestion

### 1. **Poor Separation of Concerns** 🔴
My suggestion puts business logic directly in the controller instead of using dedicated services.

**Problem:**
```php
// ❌ Bad — Controller doing too much
public function voterHub(Organisation $organisation) {
    $activeElections = Election::withoutGlobalScopes()
        ->where('organisation_id', $organisation->id)
        ->where('type', 'real')
        ->where('status', 'active')
        ->get();
    
    $voterMemberships = [];
    foreach ($activeElections as $election) {
        $membership = ElectionMembership::...->first();
        $voterMemberships[$election->id] = $membership;
    }
}
```

**Fix:** Use Service classes and Repository pattern:

```php
// ✅ Good — Service handles logic
public function voterHub(Organisation $organisation): Response
{
    $this->authorize('view', $organisation);
    
    $voterHubService = app(VoterHubService::class);
    $data = $voterHubService->getVoterHubData($organisation, auth()->user());
    
    return Inertia::render('Organisations/VoterHub', $data);
}
```

---

### 2. **Missing Authorization Granularity** 🟠

**Problem:** Using the same `view` policy for both pages doesn't differentiate between voter vs commissioner access.

**Fix:** Add specific policies:

```php
// App/Policies/OrganisationPolicy.php
public function viewVoterHub(User $user, Organisation $organisation): bool
{
    // Any authenticated user can view voter hub
    return $user->belongsToOrganisation($organisation) || 
           $organisation->isPublic();
}

public function viewElectionCommission(User $user, Organisation $organisation): bool
{
    // Only election officials can view commission page
    return $user->isElectionOfficer($organisation) ||
           $user->can('manage', $organisation);
}
```

---

### 3. **N+1 Query Problem** 🔴

**Problem:** My loop for voter memberships will cause N+1 queries.

**Fix:** Eager load with proper joins:

```php
$activeElections = Election::withoutGlobalScopes()
    ->where('organisation_id', $organisation->id)
    ->where('status', 'active')
    ->with(['memberships' => function ($q) use ($user) {
        $q->where('user_id', $user?->id);
    }])
    ->get();
```

---

### 4. **No Caching Strategy** 🟠

**Problem:** Every page load queries the database for the same data.

**Fix:** Implement caching with invalidation:

```php
use Illuminate\Support\Facades\Cache;

public function getVoterHubData(Organisation $organisation, ?User $user): array
{
    $cacheKey = "voter_hub_{$organisation->id}_{$user?->id}";
    
    return Cache::remember($cacheKey, now()->addMinutes(15), function () use ($organisation, $user) {
        return [
            'activeElections' => $this->getActiveElections($organisation, $user),
            'voterStatus' => $this->getVoterStatus($organisation, $user),
            'candidacyApplications' => $this->getUserApplications($user),
        ];
    });
}
```

---

### 5. **Missing Component Reusability** 🟡

**Problem:** Duplicating election card logic across three pages (Show, VoterHub, ElectionCommission).

**Fix:** Create reusable components:

```vue
<!-- Components/Election/ElectionCard.vue -->
<template>
  <Card :mode="mode" :padding="padding">
    <div class="flex items-start justify-between">
      <div>
        <h3 class="font-semibold">{{ election.name }}</h3>
        <p class="text-sm text-slate-500">{{ election.description }}</p>
      </div>
      <StatusBadge :status="election.status" />
    </div>
    
    <!-- Slot for actions to keep component flexible -->
    <slot name="actions" :election="election" />
  </Card>
</template>

<script setup>
defineProps({
  election: Object,
  mode: { type: String, default: 'admin' },
  padding: { type: String, default: 'md' }
})
</script>
```

Then use it everywhere with different action slots.

---

### 6. **Missing Breadcrumb Navigation** 🟡

**Problem:** Users will be lost between pages without clear navigation hierarchy.

**Fix:** Add breadcrumb component:

```vue
<!-- Components/Breadcrumb.vue -->
<template>
  <nav class="flex items-center gap-2 text-sm" aria-label="Breadcrumb">
    <Link :href="route('organisations.show', organisation.slug)" class="hover:text-primary-600">
      {{ organisation.name }}
    </Link>
    <span>/</span>
    <span v-if="currentPage === 'voter-hub'" class="text-slate-600 font-medium">
      Voter Hub
    </span>
    <span v-else-if="currentPage === 'election-commission'" class="text-slate-600 font-medium">
      Election Commission
    </span>
  </nav>
</template>
```

---

### 7. **Candidacy Form Complexity** ❓

You mentioned candidacy forms — what's the exact workflow?

**Options:**
- **Simple:** User fills form → admin approves → becomes candidate
- **Complex:** Requires endorsements, document uploads, nomination by members

Let me design a flexible approach:

```php
// Services/CandidacyApplicationService.php
class CandidacyApplicationService
{
    public function createApplication(User $user, Election $election, Post $post, array $data): CandidacyApplication
    {
        DB::transaction(function () use ($user, $election, $post, $data) {
            // 1. Validate eligibility
            $this->validateEligibility($user, $election, $post);
            
            // 2. Store application
            $application = CandidacyApplication::create([
                'user_id' => $user->id,
                'election_id' => $election->id,
                'post_id' => $post->id,
                'manifesto' => $data['manifesto'],
                'documents' => $this->uploadDocuments($data['documents']),
                'status' => 'pending',
            ]);
            
            // 3. Notify election officials
            Notification::send($election->officers, new CandidacyApplicationReceived($application));
            
            return $application;
        });
    }
}
```

---

## ✅ Improved Architecture

### Folder Structure

```
app/
├── Services/
│   ├── VoterHubService.php
│   ├── ElectionCommissionService.php
│   └── CandidacyApplicationService.php
├── Repositories/
│   ├── ElectionRepository.php
│   └── MembershipRepository.php
└── Http/Controllers/
    └── OrganisationController.php (thin controllers)

resources/js/
├── Pages/Organisations/
│   ├── Show.vue (overview)
│   ├── VoterHub.vue
│   └── ElectionCommission.vue
├── Components/Election/
│   ├── ElectionCard.vue
│   ├── VoterStatusBadge.vue
│   └── CandidacyForm.vue
└── Composables/
    ├── useVoterHub.js
    └── useElectionCommission.js
```

---

### Improved Controller

```php
class OrganisationController extends Controller
{
    public function __construct(
        private VoterHubService $voterHubService,
        private ElectionCommissionService $commissionService
    ) {}
    
    public function voterHub(Organisation $organisation): Response
    {
        $this->authorize('viewVoterHub', $organisation);
        
        return Inertia::render('Organisations/VoterHub', 
            $this->voterHubService->getData($organisation, auth()->user())
        );
    }
    
    public function electionCommission(Organisation $organisation): Response
    {
        $this->authorize('viewElectionCommission', $organisation);
        
        return Inertia::render('Organisations/ElectionCommission', 
            $this->commissionService->getData($organisation, auth()->user())
        );
    }
}
```

---

### Improved Vue Composition

```vue
<!-- Composables/useVoterHub.js -->
import { ref, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

export function useVoterHub() {
    const page = usePage()
    const applyingFor = ref(null)
    
    const canApplyForCandidacy = computed(() => {
        const user = page.props.auth.user
        return user?.is_member && !user?.has_voted
    })
    
    const submitCandidacy = async (postId, formData) => {
        try {
            await router.post(
                route('organisations.candidacy.apply', {
                    organisation: page.props.organisation.slug,
                    election: page.props.activeElections[0]?.slug
                }),
                { post_id: postId, ...formData }
            )
            applyingFor.value = null
        } catch (error) {
            // Handle validation errors
        }
    }
    
    return {
        applyingFor,
        canApplyForCandidacy,
        submitCandidacy
    }
}
```

---

## 📋 Implementation Checklist

### Phase 1: Foundation (Day 1-2)
- [ ] Create Service classes with caching
- [ ] Add policies for voter hub and commission
- [ ] Create reusable ElectionCard component
- [ ] Add breadcrumb component

### Phase 2: Voter Hub (Day 3-4)
- [ ] Build VoterHub.vue with active elections
- [ ] Implement candidacy application form
- [ ] Add document upload (if needed)
- [ ] Add voter eligibility check

### Phase 3: Election Commission (Day 5-6)
- [ ] Build ElectionCommission.vue
- [ ] Reuse existing management components
- [ ] Add quick stats dashboard
- [ ] Implement audit log for actions

### Phase 4: Polish (Day 7)
- [ ] Add loading states
- [ ] Implement error boundaries
- [ ] Add analytics tracking
- [ ] Write tests

---

## Final Recommendation

**Don't implement my initial suggestion as-is.** Instead:

1. **Start with Phase 1** — Build the service layer and reusable components
2. **Extract existing Show.vue logic** into services before duplicating
3. **Use composition API** with composables for shared logic
4. **Cache aggressively** — organisation data doesn't change frequently
5. **Add proper error handling** with user-friendly messages

Would you like me to write the actual implementation with these improvements?

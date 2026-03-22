## 📝 **Claude Code CLI Prompt Instructions: Election Dashboard Implementation**

---

```markdown
## Task: Implement Election Dashboard with Hybrid Routing

### Context
We have a working election system but need to refactor the election display pages to use a clean, URL-based architecture (`/elections/{slug}`) while maintaining backward compatibility with the existing `/election` route. This will make election pages shareable, bookmarkable, and SEO-friendly.

### Current State
- `ElectionPage.vue` exists at `/election` (session-based)
- `DashboardResolver` redirects to `/election` for single elections
- No dedicated voting form on election page
- No slug-based routes for elections

### Target State
- Primary route: `GET /elections/{slug}` → Election show page with voting form
- Legacy route: `GET /election` → Redirects to `/elections/{slug}`
- DashboardResolver redirects to slug-based route for single elections
- ElectionCard links to slug-based route for voting

---

## Implementation Order (TDD First)

### Phase 1: Create New Routes & Controller

#### Step 1: Add new routes

**File:** `routes/election/electionRoutes.php`

Add these routes before existing ones:

```php
use App\Http\Controllers\ElectionVotingController;

// =============================================
// PRIMARY ELECTION PAGE (URL-based, shareable)
// =============================================
Route::get('/elections/{slug}', [ElectionVotingController::class, 'show'])
    ->name('elections.show')
    ->middleware(['auth', 'verified']);

// =============================================
// START VOTING FLOW
// =============================================
Route::post('/elections/{slug}/start', [ElectionVotingController::class, 'start'])
    ->name('elections.start')
    ->middleware(['auth', 'verified']);

// =============================================
// SUBMIT VOTE
// =============================================
Route::post('/elections/{slug}/vote', [ElectionVotingController::class, 'store'])
    ->name('elections.vote')
    ->middleware(['auth', 'verified']);

// =============================================
// ELECTION RESULTS
// =============================================
Route::get('/elections/{slug}/results', [ElectionVotingController::class, 'results'])
    ->name('elections.results')
    ->middleware(['auth', 'verified']);
```

#### Step 2: Create ElectionVotingController

```bash
php artisan make:controller ElectionVotingController
```

**File:** `app/Http/Controllers/ElectionVotingController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\Vote;
use App\Models\VoterSlug;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ElectionVotingController extends Controller
{
    /**
     * Show election page with candidates and voting form
     */
    public function show($slug)
    {
        $election = Election::where('slug', $slug)
            ->where('type', 'real')
            ->firstOrFail();
        
        $user = auth()->user();
        
        $membership = ElectionMembership::where('user_id', $user->id)
            ->where('election_id', $election->id)
            ->first();
        
        $hasVoted = $membership && $membership->has_voted;
        $canVote = $membership && $membership->status === 'active' && !$hasVoted;
        
        $posts = $election->posts()
            ->with(['candidates' => function($q) {
                $q->orderBy('order', 'asc');
            }])
            ->orderBy('order', 'asc')
            ->get();
        
        $stats = [
            'total_memberships' => ElectionMembership::where('election_id', $election->id)->count(),
            'votes_cast' => Vote::where('election_id', $election->id)->count(),
        ];
        
        return Inertia::render('Election/Show', [
            'election' => $election,
            'posts' => $posts,
            'membership' => $membership,
            'stats' => $stats,
            'hasVoted' => $hasVoted,
            'canVote' => $canVote,
        ]);
    }
    
    /**
     * Start voting flow (create voter slug)
     */
    public function start($slug)
    {
        $election = Election::where('slug', $slug)->firstOrFail();
        $user = auth()->user();
        
        $membership = ElectionMembership::where('user_id', $user->id)
            ->where('election_id', $election->id)
            ->first();
        
        if (!$membership || $membership->status !== 'active') {
            return redirect()->route('elections.show', $election->slug)
                ->with('error', 'You are not eligible to vote in this election.');
        }
        
        if ($membership->has_voted) {
            return redirect()->route('elections.results', $election->slug)
                ->with('info', 'You have already voted.');
        }
        
        $existingSlug = VoterSlug::where('user_id', $user->id)
            ->where('election_id', $election->id)
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->first();
        
        if ($existingSlug) {
            return redirect()->route('slug.code.create', ['vslug' => $existingSlug->slug]);
        }
        
        $voterSlug = VoterSlug::create([
            'id' => Str::uuid(),
            'user_id' => $user->id,
            'election_id' => $election->id,
            'organisation_id' => $election->organisation_id,
            'slug' => Str::random(32),
            'status' => 'active',
            'expires_at' => now()->addMinutes(30),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        return redirect()->route('slug.code.create', ['vslug' => $voterSlug->slug]);
    }
    
    /**
     * Submit votes
     */
    public function store(Request $request, $slug)
    {
        $election = Election::where('slug', $slug)->firstOrFail();
        $user = auth()->user();
        
        $membership = ElectionMembership::where('user_id', $user->id)
            ->where('election_id', $election->id)
            ->first();
        
        if (!$membership || $membership->status !== 'active') {
            abort(403, 'You are not eligible to vote.');
        }
        
        if ($membership->has_voted) {
            return redirect()->route('elections.show', $election->slug)
                ->with('error', 'You have already voted.');
        }
        
        $validated = $request->validate([
            'votes' => 'required|array',
            'votes.*' => 'required|exists:candidates,id',
        ]);
        
        DB::transaction(function () use ($election, $user, $validated, $membership) {
            foreach ($validated['votes'] as $postId => $candidateId) {
                Vote::create([
                    'id' => Str::uuid(),
                    'election_id' => $election->id,
                    'post_id' => $postId,
                    'candidate_id' => $candidateId,
                    'user_id' => $user->id,
                    'created_at' => now(),
                ]);
            }
            
            $membership->update([
                'has_voted' => true,
                'voted_at' => now(),
            ]);
        });
        
        return redirect()->route('elections.show', $election->slug)
            ->with('success', 'Your vote has been recorded successfully!');
    }
    
    /**
     * Show election results
     */
    public function results($slug)
    {
        $election = Election::where('slug', $slug)->firstOrFail();
        
        if (!$election->results_published && $election->status !== 'completed') {
            return redirect()->route('elections.show', $election->slug)
                ->with('info', 'Results are not yet available.');
        }
        
        $posts = $election->posts()->with(['candidates' => function($q) {
            $q->withCount(['votes as vote_count']);
        }])->get();
        
        $results = $posts->map(function($post) {
            $totalVotes = $post->candidates->sum('vote_count');
            
            return [
                'id' => $post->id,
                'name' => $post->name,
                'candidates' => $post->candidates->map(function($candidate) use ($totalVotes) {
                    return [
                        'id' => $candidate->id,
                        'name' => $candidate->name,
                        'party' => $candidate->party,
                        'vote_count' => $candidate->vote_count,
                        'percentage' => $totalVotes > 0 ? round(($candidate->vote_count / $totalVotes) * 100, 1) : 0,
                    ];
                }),
                'total_votes' => $totalVotes,
            ];
        });
        
        return Inertia::render('Election/Results', [
            'election' => $election,
            'results' => $results,
        ]);
    }
}
```

#### Step 3: Add redirect method to existing ElectionController

**File:** `app/Http/Controllers/ElectionController.php`

Add this method:

```php
/**
 * Redirect session-based election route to slug-based route
 * 
 * GET /election
 */
public function redirectToElection()
{
    $user = auth()->user();
    $eligibleCount = $user->countActiveElections();
    
    if ($eligibleCount === 0) {
        $orgId = session('current_organisation_id');
        if ($orgId) {
            return redirect()->route('organisations.show', $orgId)
                ->with('info', 'No active elections available.');
        }
        return redirect()->route('dashboard');
    }
    
    if ($eligibleCount === 1) {
        $election = $user->getActiveElection();
        return redirect()->route('elections.show', $election->slug);
    }
    
    // Multiple elections - show organisation dashboard for selection
    $orgId = session('current_organisation_id');
    return redirect()->route('organisations.show', $orgId)
        ->with('info', 'Multiple elections available. Please select one to vote.');
}
```

---

### Phase 2: Update DashboardResolver

#### Step 4: Modify Priority 3 in DashboardResolver

**File:** `app/Services/DashboardResolver.php`

Find the Priority 3 section (lines 102-113) and replace with:

```php
// =============================================
// PRIORITY 3: ACTIVE ELECTIONS (User can vote)
// 0 → skip | 1 → elections.show | 2+ → organisations.show
// =============================================
$eligibleCount = $user->countActiveElections();

if ($eligibleCount > 0) {
    $activeElection = $user->getActiveElection();
    $electionOrg = \App\Models\Organisation::find($activeElection->organisation_id);
    
    Log::info('🗳️ PRIORITY 3 HIT: Eligible elections found', [
        'user_id'        => $user->id,
        'eligible_count' => $eligibleCount,
        'election_id'    => $activeElection->id,
        'election_slug'  => $activeElection->slug,
        'org_id'         => $electionOrg?->id,
    ]);
    
    if ($electionOrg) {
        try {
            $this->tenantContext->setContext($user, $electionOrg);
        } catch (\RuntimeException $e) {
            Log::warning('DashboardResolver: TenantContext failed in Priority 3', [
                'user_id' => $user->id, 'error' => $e->getMessage(),
            ]);
        }
        
        if ($eligibleCount === 1) {
            // Single election: redirect to slug-based election page
            $targetUrl = route('elections.show', $activeElection->slug);
            Log::info('🎯 Single election → elections.show', [
                'user_id' => $user->id,
                'target'  => $targetUrl,
            ]);
            $this->cacheResolution($user, $targetUrl);
            return redirect()->to($targetUrl);
        }
        
        // Multiple elections: go to org page so user can choose
        $targetUrl = route('organisations.show', $electionOrg->slug);
        Log::info('📋 Multiple elections → organisations.show', [
            'user_id' => $user->id,
            'count'   => $eligibleCount,
            'target'  => $targetUrl,
        ]);
        $this->cacheResolution($user, $targetUrl);
        return redirect()->to($targetUrl);
    }
}
Log::debug('✗ PRIORITY 3 SKIPPED: No eligible elections for user', ['user_id' => $user->id]);
```

---

### Phase 3: Create Election Show Vue Component

#### Step 5: Create Show.vue with professional design

**File:** `resources/js/Pages/Election/Show.vue`

```vue
<template>
  <ElectionLayout>
    <main class="min-h-screen bg-gray-50 py-8">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8">
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
              <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ election.name }}</h1>
              <div class="flex items-center gap-2 mt-2">
                <StatusBadge :status="election.status" size="md" />
                <span class="text-sm text-gray-500">
                  {{ formatDate(election.start_date) }} – {{ formatDate(election.end_date) }}
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- Voting Card -->
        <div class="mb-10">
          <div
            :class="votingCardClass"
            @click="canVote && !hasVoted && handleVoteClick"
          >
            <!-- Status Banner -->
            <div class="px-6 pt-4">
              <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-medium" :class="votingStatusClass">
                <span class="relative flex h-2 w-2">
                  <span v-if="canVote && !hasVoted" class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2" :class="votingDotClass"></span>
                </span>
                {{ votingStatusText }}
              </div>
            </div>

            <div class="px-6 pb-8 text-center">
              <div class="mb-6">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full" :class="votingIconBgClass">
                  <svg class="w-10 h-10" :class="votingIconColorClass" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                </div>
              </div>

              <h2 class="text-2xl sm:text-3xl font-bold mb-2" :class="votingTitleClass">{{ votingTitle }}</h2>
              <p class="text-sm sm:text-base max-w-md mx-auto" :class="votingDescClass">{{ votingDescription }}</p>

              <div class="mt-8">
                <button
                  v-if="hasVoted"
                  @click="goToResults"
                  class="px-8 py-3 bg-white text-green-600 hover:bg-white/90 rounded-xl font-semibold transition shadow-lg"
                >
                  View Results
                </button>
                <button
                  v-else-if="canVote"
                  @click="handleVoteClick"
                  class="px-8 py-3 bg-white text-blue-600 hover:bg-white/90 rounded-xl font-semibold transition shadow-lg transform hover:scale-105"
                >
                  Start Voting
                </button>
                <button v-else disabled class="px-8 py-3 bg-white/10 text-white/60 rounded-xl font-semibold cursor-not-allowed">
                  {{ unavailableReason }}
                </button>
              </div>

              <p v-if="canVote && !hasVoted" class="mt-4 text-xs" :class="votingHelpClass">
                ⚠️ Your vote is anonymous and cannot be changed after submission
              </p>
            </div>
          </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-10">
          <div class="bg-white rounded-xl border border-gray-200 p-4 text-center">
            <p class="text-sm text-gray-500">Total Voters</p>
            <p class="text-2xl font-bold text-gray-900">{{ stats.total_memberships || 0 }}</p>
          </div>
          <div class="bg-white rounded-xl border border-gray-200 p-4 text-center">
            <p class="text-sm text-gray-500">Votes Cast</p>
            <p class="text-2xl font-bold text-green-600">{{ stats.votes_cast || 0 }}</p>
          </div>
          <div class="bg-white rounded-xl border border-gray-200 p-4 text-center">
            <p class="text-sm text-gray-500">Turnout</p>
            <p class="text-2xl font-bold text-blue-600">{{ turnout }}%</p>
          </div>
        </div>

      </div>
    </main>
  </ElectionLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import ElectionLayout from '@/Layouts/ElectionLayout.vue'
import StatusBadge from '@/Components/StatusBadge.vue'

const props = defineProps({
  election: { type: Object, required: true },
  posts: { type: Array, default: () => [] },
  membership: { type: Object, default: null },
  stats: { type: Object, default: () => ({}) },
  hasVoted: { type: Boolean, default: false },
  canVote: { type: Boolean, default: false },
})

const turnout = computed(() => {
  if (!props.stats.total_memberships) return 0
  return Math.round((props.stats.votes_cast || 0) / props.stats.total_memberships * 100)
})

const formatDate = (date) => date ? new Date(date).toLocaleDateString() : '—'

const handleVoteClick = () => {
  if (props.canVote && !props.hasVoted) {
    router.post(route('elections.start', props.election.slug), {}, {
      preserveScroll: true,
    })
  }
}

const goToResults = () => {
  window.location.href = route('elections.results', props.election.slug)
}

// Voting card computed properties
const votingCardClass = computed(() => {
  if (props.hasVoted) return 'bg-gradient-to-r from-green-600 to-emerald-600 rounded-2xl shadow-lg'
  if (props.canVote) return 'bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl shadow-lg hover:shadow-xl cursor-pointer transform hover:scale-[1.02] transition-all'
  return 'bg-gray-100 rounded-2xl cursor-not-allowed'
})

const votingStatusClass = computed(() => {
  if (props.hasVoted || props.canVote) return 'bg-white/20 text-white'
  return 'bg-gray-200 text-gray-600'
})

const votingDotClass = computed(() => {
  if (props.hasVoted) return 'bg-white'
  if (props.canVote) return 'bg-green-400'
  return 'bg-gray-400'
})

const votingIconBgClass = computed(() => {
  if (props.hasVoted || props.canVote) return 'bg-white/20'
  return 'bg-gray-200'
})

const votingIconColorClass = computed(() => {
  if (props.hasVoted || props.canVote) return 'text-white'
  return 'text-gray-500'
})

const votingTitleClass = computed(() => {
  if (props.hasVoted || props.canVote) return 'text-white'
  return 'text-gray-500'
})

const votingDescClass = computed(() => {
  if (props.hasVoted || props.canVote) return 'text-white/80'
  return 'text-gray-500'
})

const votingHelpClass = computed(() => {
  if (props.hasVoted || props.canVote) return 'text-white/50'
  return 'text-gray-400'
})

const votingStatusText = computed(() => {
  if (props.hasVoted) return 'Vote Recorded'
  if (props.canVote) return 'Ready to Vote'
  return 'Not Eligible'
})

const votingTitle = computed(() => {
  if (props.hasVoted) return 'Thank You for Voting!'
  if (props.canVote) return 'Cast Your Vote'
  return 'Not Eligible to Vote'
})

const votingDescription = computed(() => {
  if (props.hasVoted) return 'Your vote has been recorded and is secure.'
  if (props.canVote) return 'Choose your candidates and make your voice heard.'
  return 'You are not eligible to vote in this election.'
})

const unavailableReason = computed(() => {
  if (props.election.status !== 'active') return 'Election is not active'
  if (!props.membership) return 'You are not registered for this election'
  if (props.membership?.status !== 'active') return 'Your registration is pending approval'
  return 'Voting not available'
})
</script>
```

---

### Phase 4: Update ElectionCard.vue Links

#### Step 6: Update ElectionCard.vue

**File:** `resources/js/Pages/Organisations/Partials/ElectionCard.vue`

Update the vote button link:

```vue
<!-- Vote button -->
<a
  v-if="election.status === 'active' && canVote && !hasVoted"
  :href="route('elections.show', election.slug)"
  class="flex-1 sm:flex-none inline-flex items-center justify-center gap-1.5 text-xs font-semibold px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white rounded-lg"
>
  🗳️ Vote Now
</a>

<!-- View Results button -->
<a
  v-if="election.results_published || election.status === 'completed'"
  :href="route('elections.results', election.slug)"
  class="flex-1 sm:flex-none inline-flex items-center justify-center gap-1.5 text-xs font-semibold px-3 py-1.5 border border-blue-300 text-blue-600 rounded-lg hover:bg-blue-50"
>
  📊 View Results
</a>
```

Also add new props:

```vue
<script setup>
const props = defineProps({
  // ... existing props
  canVote: { type: Boolean, default: false },
  hasVoted: { type: Boolean, default: false },
})
</script>
```

---

### Phase 5: Update OrganisationController to Pass Eligibility

#### Step 7: Add userEligibility to OrganisationController

**File:** `app/Http/Controllers/OrganisationController.php`

After fetching `$realElections`, add:

```php
$user = auth()->user();
$userEligibility = [];

foreach ($realElections as $election) {
    $membership = ElectionMembership::where('user_id', $user->id)
        ->where('election_id', $election->id)
        ->first();
    
    $userEligibility[$election->id] = [
        'can_vote' => $membership && $membership->status === 'active' && !$membership->has_voted,
        'has_voted' => $membership && $membership->has_voted ?? false,
    ];
}

return inertia('Organisations/Show', [
    // ... existing props
    'userEligibility' => $userEligibility,
]);
```

#### Step 8: Update Show.vue to pass eligibility

**File:** `resources/js/Pages/Organisations/Show.vue`

Add `userEligibility` to props and pass to ElectionCard:

```vue
<script setup>
const props = defineProps({
  // ... existing props
  userEligibility: { type: Object, default: () => ({}) },
})
</script>

<template>
  <ElectionCard
    v-for="election in elections"
    :key="election.id"
    :election="election"
    :can-vote="userEligibility[election.id]?.can_vote"
    :has-voted="userEligibility[election.id]?.has_voted"
    :activating-id="activatingId"
    :can-activate="canActivateElection && election.status === 'planned'"
    :can-manage="canManage || isChief || isDeputy"
    :is-readonly="isCommissioner || (!canManage && !isOfficer)"
    @activate="activateElection"
  />
</template>
```

---

### Phase 6: Testing

#### Step 9: Run tests

```bash
# Run the election priority tests
php artisan test tests/Feature/Services/DashboardResolverElectionPriorityTest.php

# Run dashboard access tests
php artisan test tests/Feature/Election/ElectionDashboardAccessTest.php

# Run full election test suite
php artisan test tests/Feature/Election/
```

#### Step 10: Manual testing

```bash
# Test flows:
# 1. Login as voter with 1 active election
#    → Should redirect to /elections/{slug}
#    → See voting card with "Start Voting" button
#    → Click to start voting flow

# 2. Login as voter with 2+ active elections
#    → Should go to organisation dashboard
#    → See both elections with "Vote Now" buttons
#    → Clicking goes to /elections/{slug}

# 3. Login as voter who already voted
#    → Should see "Vote Recorded" card with "View Results"

# 4. Direct access /election
#    → Should redirect to /elections/{slug}
```

---

## Summary of Changes

| File | Change |
|------|--------|
| `routes/election/electionRoutes.php` | Add 4 new routes for slug-based election pages |
| `app/Http/Controllers/ElectionVotingController.php` | NEW - handles voting flow |
| `app/Http/Controllers/ElectionController.php` | Add redirectToElection() method |
| `app/Services/DashboardResolver.php` | Update Priority 3 to redirect to elections.show |
| `resources/js/Pages/Election/Show.vue` | NEW - redesigned election page |
| `resources/js/Pages/Organisations/Partials/ElectionCard.vue` | Update links to use slug routes, add canVote/hasVoted props |
| `app/Http/Controllers/OrganisationController.php` | Add userEligibility for each election |
| `resources/js/Pages/Organisations/Show.vue` | Pass userEligibility to ElectionCard |

---

## Verification Checklist

- [ ] `/elections/{slug}` loads election page
- [ ] `/election` redirects to `/elections/{slug}` for single election
- [ ] DashboardResolver redirects to slug-based route for single election
- [ ] ElectionCard "Vote Now" links to `/elections/{slug}`
- [ ] "Start Voting" button creates voter slug and redirects to voting flow
- [ ] "View Results" works when results are published
- [ ] Already voted users see appropriate message
- [ ] Ineligible users see "Not Eligible" message
- [ ] All tests pass
```

---

## 🚀 **Ready to Execute**

Copy and paste these instructions into Claude Code CLI to implement the full election dashboard with hybrid routing. Follow the steps in order, and run tests after each phase to ensure everything works.

## 🎯 **Strategic Decision: Redesign ElectionDashboard.vue as Election Show Page**

You're absolutely right. Instead of having two separate pages, we can **redesign `ElectionDashboard.vue`** to serve as the **election show page** (`/elections/{slug}`). This creates a single, focused experience.

---

## 📋 **Design Philosophy**

| Principle | Implementation |
|-----------|----------------|
| **Single Purpose** | One page for viewing and interacting with a specific election |
| **Clear Hierarchy** | Voting is primary action, everything else is secondary |
| **Mobile First** | Touch-friendly, responsive, single column on mobile |
| **Accessible** | WCAG AA compliant, keyboard navigable |
| **Status-Aware** | Adapts to user's eligibility, voting status, election state |

---

## 🎨 **Redesigned Election Show Page**

### **Complete Component: `resources/js/Pages/Election/Show.vue`**

```vue
<template>
  <ElectionLayout>
    <!-- Skip to main content -->
    <a href="#main-content" class="skip-link">{{ $t('pages.election.show.skip_to_content') }}</a>

    <main id="main-content" role="main" class="min-h-screen bg-gray-50 py-8">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- ============================================================ -->
        <!-- HEADER: Election Title & Status                              -->
        <!-- ============================================================ -->
        <div class="mb-8">
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
              <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ election.name }}</h1>
              <div class="flex items-center gap-2 mt-2">
                <StatusBadge :status="election.status" size="md" />
                <span class="text-sm text-gray-500">
                  {{ formatDate(election.start_date) }} – {{ formatDate(election.end_date) }}
                </span>
              </div>
            </div>
            <div v-if="election.type === 'demo'" class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-sm font-medium">
              🎮 Demo Election
            </div>
          </div>
        </div>

        <!-- ============================================================ -->
        <!-- PRIMARY ACTION: VOTING CARD                                   -->
        <!-- ============================================================ -->
        <div class="mb-10">
          <div
            :class="[
              'rounded-2xl transition-all overflow-hidden',
              votingCardClass
            ]"
            @click="canVote && handleVoteClick"
            :aria-label="votingAriaLabel"
          >
            <!-- Status Banner -->
            <div class="px-6 pt-4">
              <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-medium"
                :class="votingStatusClass"
              >
                <span class="relative flex h-2 w-2">
                  <span v-if="canVote && !hasVoted" class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2" :class="votingDotClass"></span>
                </span>
                {{ votingStatusText }}
              </div>
            </div>

            <!-- Content -->
            <div class="px-6 pb-8 text-center">
              <!-- Icon -->
              <div class="mb-6">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full"
                  :class="votingIconBgClass"
                >
                  <svg class="w-10 h-10" :class="votingIconColorClass" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path v-if="hasVoted" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                </div>
              </div>

              <!-- Title & Description -->
              <h2 class="text-2xl sm:text-3xl font-bold mb-2" :class="votingTitleClass">
                {{ votingTitle }}
              </h2>
              <p class="text-sm sm:text-base max-w-md mx-auto" :class="votingDescClass">
                {{ votingDescription }}
              </p>

              <!-- Timer (active session) -->
              <div v-if="showTimer && !hasVoted" class="mt-6">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full" :class="timerClass">
                  <svg class="w-4 h-4 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                  <span class="font-mono font-bold">{{ formattedTimeRemaining }}</span>
                </div>
              </div>

              <!-- CTA Button -->
              <div class="mt-8">
                <button
                  v-if="hasVoted"
                  @click="goToResults"
                  class="px-8 py-3 bg-white text-green-600 hover:bg-white/90 rounded-xl font-semibold transition shadow-lg"
                >
                  View Results
                </button>
                <button
                  v-else-if="canVote"
                  @click="handleVoteClick"
                  class="px-8 py-3 bg-white text-blue-600 hover:bg-white/90 rounded-xl font-semibold transition shadow-lg transform hover:scale-105"
                >
                  Start Voting
                </button>
                <button
                  v-else
                  disabled
                  class="px-8 py-3 bg-white/10 text-white/60 rounded-xl font-semibold cursor-not-allowed"
                >
                  {{ unavailableReason }}
                </button>
              </div>

              <!-- Help Text -->
              <p v-if="canVote && !hasVoted" class="mt-4 text-xs opacity-60" :class="votingHelpClass">
                ⚠️ Your vote is anonymous and cannot be changed after submission
              </p>
            </div>
          </div>
        </div>

        <!-- ============================================================ -->
        <!-- SECONDARY: RESULTS PREVIEW (if results published)            -->
        <!-- ============================================================ -->
        <div v-if="election.results_published" class="mb-10">
          <div
            class="bg-white rounded-2xl border border-gray-200 shadow-sm hover:shadow-md transition cursor-pointer"
            @click="goToResults"
          >
            <div class="px-6 py-5 flex flex-col sm:flex-row items-center justify-between gap-4">
              <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                  <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                  </svg>
                </div>
                <div>
                  <h3 class="font-semibold text-gray-900">Results Published</h3>
                  <p class="text-sm text-gray-500">View the final election results</p>
                </div>
              </div>
              <button class="text-blue-600 font-medium text-sm hover:text-blue-800">
                View Results →
              </button>
            </div>
          </div>
        </div>

        <!-- ============================================================ -->
        <!-- ELECTION INFORMATION                                          -->
        <!-- ============================================================ -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
          <!-- About Section -->
          <div class="md:col-span-2 bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">About This Election</h3>
            <p class="text-gray-600 text-sm leading-relaxed">
              {{ election.description || 'No additional information provided.' }}
            </p>
            <div class="mt-4 grid grid-cols-2 gap-4 text-sm">
              <div>
                <span class="text-gray-500">Start Date:</span>
                <span class="text-gray-900 font-medium ml-2">{{ formatDate(election.start_date) }}</span>
              </div>
              <div>
                <span class="text-gray-500">End Date:</span>
                <span class="text-gray-900 font-medium ml-2">{{ formatDate(election.end_date) }}</span>
              </div>
              <div>
                <span class="text-gray-500">Type:</span>
                <span class="text-gray-900 font-medium ml-2">{{ election.type === 'real' ? 'Official Election' : 'Demo' }}</span>
              </div>
              <div>
                <span class="text-gray-500">Status:</span>
                <span class="text-gray-900 font-medium ml-2 capitalize">{{ election.status }}</span>
              </div>
            </div>
          </div>

          <!-- Quick Stats -->
          <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Quick Stats</h3>
            <div class="space-y-3">
              <div class="flex justify-between items-center">
                <span class="text-sm text-gray-500">Total Voters</span>
                <span class="text-lg font-bold text-gray-900">{{ stats.total_memberships || 0 }}</span>
              </div>
              <div class="flex justify-between items-center">
                <span class="text-sm text-gray-500">Votes Cast</span>
                <span class="text-lg font-bold text-green-600">{{ stats.votes_cast || 0 }}</span>
              </div>
              <div class="flex justify-between items-center">
                <span class="text-sm text-gray-500">Turnout</span>
                <span class="text-lg font-bold text-blue-600">{{ turnout }}%</span>
              </div>
            </div>
          </div>
        </div>

        <!-- ============================================================ -->
        <!-- CANDIDATES & POSITIONS (if election is active or completed) -->
        <!-- ============================================================ -->
        <div v-if="election.status !== 'planned'" class="mb-10">
          <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-900">Candidates & Positions</h2>
            <a v-if="election.results_published" href="#" class="text-sm text-blue-600 hover:text-blue-800">
              View Full Results →
            </a>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div v-for="post in posts" :key="post.id" class="bg-white rounded-xl border border-gray-200 overflow-hidden">
              <div class="bg-gray-50 px-5 py-3 border-b border-gray-200">
                <h3 class="font-semibold text-gray-900">{{ post.name }}</h3>
                <p class="text-xs text-gray-500">Select {{ post.max_votes }} candidate{{ post.max_votes !== 1 ? 's' : '' }}</p>
              </div>
              <div class="p-4 space-y-2">
                <div
                  v-for="candidate in post.candidates"
                  :key="candidate.id"
                  class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50"
                  :class="{ 'bg-blue-50': selectedVotes[post.id] === candidate.id }"
                >
                  <input
                    v-if="!hasVoted && canVote && election.status === 'active'"
                    type="radio"
                    :name="`post_${post.id}`"
                    :value="candidate.id"
                    v-model="selectedVotes[post.id]"
                    class="w-4 h-4 text-blue-600"
                    :disabled="hasVoted"
                  />
                  <div class="flex-1">
                    <p class="font-medium text-gray-900">{{ candidate.name }}</p>
                    <p v-if="candidate.party" class="text-xs text-gray-500">{{ candidate.party }}</p>
                  </div>
                  <span v-if="election.results_published" class="text-sm font-semibold text-green-600">
                    {{ candidate.vote_count || 0 }} votes
                  </span>
                </div>
              </div>
            </div>
          </div>

          <!-- Submit Button (only if voting) -->
          <div v-if="canVote && !hasVoted && election.status === 'active'" class="mt-6 flex justify-end">
            <button
              @click="submitVote"
              :disabled="submitting || !allPostsSelected"
              class="px-8 py-3 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-300 text-white font-semibold rounded-xl transition"
            >
              {{ submitting ? 'Submitting...' : 'Submit Your Vote' }}
            </button>
          </div>
        </div>

        <!-- ============================================================ -->
        <!-- EMPTY STATE: No posts (for planned elections)               -->
        <!-- ============================================================ -->
        <EmptyState
          v-if="election.status === 'planned' && (!posts || posts.length === 0)"
          title="Election Setup in Progress"
          description="Positions and candidates will be added soon by the election committee."
        >
          <template #icon>
            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
          </template>
        </EmptyState>

        <!-- ============================================================ -->
        <!-- VOTER INFORMATION (if user is eligible but not voted)        -->
        <!-- ============================================================ -->
        <div v-if="membership && !hasVoted && canVote" class="mt-8 p-4 bg-blue-50 border border-blue-200 rounded-xl">
          <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-sm text-blue-800">
              You are eligible to vote in this election. Make sure to submit your vote before the deadline.
            </p>
          </div>
        </div>

      </div>
    </main>
  </ElectionLayout>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import ElectionLayout from '@/Layouts/ElectionLayout.vue'
import StatusBadge from '@/Components/StatusBadge.vue'
import EmptyState from '@/Components/EmptyState.vue'

const props = defineProps({
  election: { type: Object, required: true },
  posts: { type: Array, default: () => [] },
  membership: { type: Object, default: null },
  stats: { type: Object, default: () => ({}) },
  hasVoted: { type: Boolean, default: false },
  canVote: { type: Boolean, default: false },
})

const page = usePage()
const submitting = ref(false)
const selectedVotes = reactive({})

// Computed
const turnout = computed(() => {
  if (!props.stats.total_memberships) return 0
  return Math.round((props.stats.votes_cast || 0) / props.stats.total_memberships * 100)
})

const allPostsSelected = computed(() => {
  return props.posts.every(post => selectedVotes[post.id])
})

const showTimer = computed(() => {
  return props.membership?.voting_time_remaining > 0
})

const formattedTimeRemaining = computed(() => {
  const mins = props.membership?.voting_time_remaining || 0
  if (mins < 60) return `${mins} min`
  const hours = Math.floor(mins / 60)
  return `${hours}h ${mins % 60}m`
})

// Voting Card Styles
const votingCardClass = computed(() => {
  if (props.hasVoted) return 'bg-gradient-to-r from-green-600 to-emerald-600 shadow-lg'
  if (props.canVote) return 'bg-gradient-to-r from-blue-600 to-indigo-600 shadow-lg hover:shadow-xl cursor-pointer transform hover:scale-[1.02]'
  return 'bg-gray-100 cursor-not-allowed'
})

const votingStatusClass = computed(() => {
  if (props.hasVoted) return 'bg-white/20 text-white'
  if (props.canVote) return 'bg-white/20 text-white'
  return 'bg-gray-200 text-gray-600'
})

const votingDotClass = computed(() => {
  if (props.hasVoted) return 'bg-white'
  if (props.canVote) return 'bg-green-400'
  return 'bg-gray-400'
})

const votingIconBgClass = computed(() => {
  if (props.hasVoted) return 'bg-white/20'
  if (props.canVote) return 'bg-white/20'
  return 'bg-gray-200'
})

const votingIconColorClass = computed(() => {
  if (props.hasVoted) return 'text-white'
  if (props.canVote) return 'text-white'
  return 'text-gray-500'
})

const votingTitleClass = computed(() => {
  if (props.hasVoted) return 'text-white'
  if (props.canVote) return 'text-white'
  return 'text-gray-500'
})

const votingDescClass = computed(() => {
  if (props.hasVoted) return 'text-white/80'
  if (props.canVote) return 'text-white/80'
  return 'text-gray-500'
})

const votingHelpClass = computed(() => {
  if (props.hasVoted) return 'text-white/50'
  return 'text-white/50'
})

const timerClass = computed(() => {
  if (props.hasVoted) return 'bg-white/20 text-white'
  return 'bg-white/20 text-white'
})

const votingStatusText = computed(() => {
  if (props.hasVoted) return 'Vote Recorded'
  if (props.canVote) return 'Ready to Vote'
  if (props.election.status === 'active') return 'Not Eligible'
  return 'Voting Unavailable'
})

const votingTitle = computed(() => {
  if (props.hasVoted) return 'Thank You for Voting!'
  if (props.canVote) return 'Cast Your Vote'
  if (props.election.status === 'active') return 'Not Eligible to Vote'
  return 'Voting Not Available'
})

const votingDescription = computed(() => {
  if (props.hasVoted) return 'Your vote has been recorded and is secure.'
  if (props.canVote) return 'Choose your candidates and make your voice heard.'
  if (props.election.status === 'active') return 'You are not eligible to vote in this election.'
  return 'Voting is not currently available for this election.'
})

const unavailableReason = computed(() => {
  if (props.election.status !== 'active') return 'Election is not active'
  if (!props.membership) return 'You are not registered for this election'
  if (props.membership?.status !== 'active') return 'Your registration is pending approval'
  return 'Voting not available'
})

const votingAriaLabel = computed(() => {
  if (props.hasVoted) return 'View your vote and election results'
  if (props.canVote) return 'Start voting in this election'
  return 'Voting not available'
})

// Methods
const formatDate = (date) => {
  if (!date) return '—'
  return new Date(date).toLocaleDateString()
}

const handleVoteClick = () => {
  if (props.canVote && !props.hasVoted) {
    // This would start the voting flow (create voter slug, etc.)
    router.post(route('elections.start', props.election.slug), {}, {
      preserveScroll: true,
    })
  }
}

const goToResults = () => {
  window.location.href = route('elections.results', props.election.slug)
}

const submitVote = () => {
  if (!allPostsSelected.value) {
    alert('Please select a candidate for all positions before submitting.')
    return
  }

  if (!confirm('Are you sure? Your vote is final and cannot be changed.')) {
    return
  }

  submitting.value = true
  router.post(route('elections.vote', props.election.slug), { votes: selectedVotes }, {
    preserveScroll: true,
    onFinish: () => { submitting.value = false }
  })
}

// Initialize selected votes
onMounted(() => {
  // Pre-select nothing, user must choose
})
</script>

<style scoped>
.skip-link {
  position: absolute;
  top: -40px;
  left: 0;
  background: #2563eb;
  color: white;
  padding: 8px 16px;
  text-decoration: none;
  z-index: 100;
  border-radius: 0 0 4px 0;
  font-weight: 600;
}

.skip-link:focus {
  top: 0;
}

/* Focus styles for accessibility */
a:focus-visible,
button:focus-visible {
  outline: 3px solid #2563eb;
  outline-offset: 2px;
  box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.2);
}

@media (prefers-reduced-motion: reduce) {
  .transition-all,
  .transition-colors,
  .transform,
  .hover\:scale-105:hover {
    transition: none !important;
    transform: none !important;
  }
}
</style>
```

---

## 📋 **Key Improvements Summary**

| Aspect | Before | After |
|--------|--------|-------|
| **Purpose** | Dashboard (multiple functions) | Single election focus |
| **Voting Card** | Same size as results | Prominent, full-width, primary action |
| **Results** | Same size as voting | Subtle card, secondary |
| **Candidates** | Separate page | Integrated with voting form |
| **Status** | Confusing | Clear: Ready to Vote / Already Voted / Not Eligible |
| **Mobile** | Two columns | Single column, touch-friendly |
| **Cognitive Load** | High | Low, clear hierarchy |

---

## 🚀 **Next Steps**

1. **Rename file**: `ElectionDashboard.vue` → `Show.vue`
2. **Move to**: `resources/js/Pages/Election/Show.vue`
3. **Update routes**:
   ```php
   Route::get('/elections/{slug}', [ElectionVotingController::class, 'show'])->name('elections.show');
   ```
4. **Update DashboardResolver** to redirect to this page for single election
5. **Update ElectionCard.vue** to link to this page

**Shall I implement these changes?** 🚀
## 🔍 **Analysis: ElectionPage.vue vs Show.vue**

Let me analyze both approaches and give you a recommendation.

---

## 📋 **Current Situation**

### **File 1: `resources/js/Pages/Election/ElectionPage.vue`**
- **Purpose:** Currently renders the election page from `ElectionManagementController@dashboard`
- **URL:** `/election` (generic, uses session context)
- **Content:** Shows election details + "Start Voting" button
- **Status:** Working, but uses session-based election context

### **File 2: `resources/js/Pages/Election/Show.vue` (New)**
- **Purpose:** Election show page with full voting form
- **URL:** `/elections/{slug}` (specific, URL-based)
- **Content:** Complete voting interface with candidates, positions, vote submission
- **Status:** New, not yet integrated

---

## 🤔 **What Should We Do?**

### **Option A: Replace ElectionPage.vue with Show.vue**
**Pros:**
- ✅ Cleaner: One file for election display
- ✅ URL-based: Better for sharing, bookmarks
- ✅ SEO-friendly: Each election has its own URL
- ✅ Consistent: Matches standard Laravel resource pattern

**Cons:**
- ❌ Need to update DashboardResolver redirect
- ❌ Need to update existing links

### **Option B: Keep Both, Different Purposes**
- `ElectionPage.vue` → Simple landing page with "Start Voting" button
- `Show.vue` → Full voting interface (with candidates, vote form)

**Pros:**
- ✅ Separation of concerns
- ✅ Lighter landing page

**Cons:**
- ❌ More files to maintain
- ❌ User has to click twice (landing → vote)

---

## 🎯 **My Recommendation: Use Show.vue as the Main Election Page**

### **Why:**
1. **Single Source of Truth** - One page for all election-related content
2. **Better UX** - User sees everything on one page, no extra clicks
3. **Cleaner Code** - No duplication between files
4. **Modern Pattern** - Single page with all election content is standard

### **Implementation Plan:**

```bash
# 1. Rename ElectionPage.vue as backup (or delete)
mv resources/js/Pages/Election/ElectionPage.vue resources/js/Pages/Election/ElectionPage.vue.backup

# 2. Use Show.vue as the main election page
cp resources/js/Pages/Election/Show.vue resources/js/Pages/Election/ElectionPage.vue
# OR better: replace ElectionPage.vue content with Show.vue

# 3. Update controller to pass required data
```

### **Update Controller:**

```php
// app/Http/Controllers/Election/ElectionManagementController.php

public function dashboard()
{
    $orgId = session('current_organisation_id');
    
    if (!$orgId) {
        return app(DashboardResolver::class)->resolve(auth()->user());
    }
    
    $election = Election::withoutGlobalScopes()
        ->where('organisation_id', $orgId)
        ->where('type', 'real')
        ->where('status', 'active')
        ->first();
    
    if (!$election) {
        return redirect()->route('organisations.show', $orgId);
    }
    
    // Get user's membership status
    $user = auth()->user();
    $membership = ElectionMembership::where('user_id', $user->id)
        ->where('election_id', $election->id)
        ->first();
    
    $hasVoted = $membership && $membership->has_voted;
    $canVote = $membership && $membership->status === 'active' && !$hasVoted;
    
    // Get posts with candidates
    $posts = $election->posts()->with('candidates')->get();
    
    // Get statistics
    $stats = [
        'total_memberships' => ElectionMembership::where('election_id', $election->id)->count(),
        'votes_cast' => Vote::where('election_id', $election->id)->count(),
    ];
    
    return Inertia::render('Election/ElectionPage', [
        'election' => $election,
        'posts' => $posts,
        'membership' => $membership,
        'stats' => $stats,
        'hasVoted' => $hasVoted,
        'canVote' => $canVote,
    ]);
}
```

### **Update DashboardResolver Redirect:**

```php
// app/Services/DashboardResolver.php - Priority 3

if ($eligibleCount === 1) {
    $activeElection = $user->getActiveElection();
    $targetUrl = route('election.dashboard'); // This goes to /election
    // OR better: directly to the election page
    $targetUrl = route('elections.show', $activeElection->slug);
    // ...
}
```

---

## ✅ **Final Decision**

| Action | Status |
|--------|--------|
| **Replace ElectionPage.vue with Show.vue content** | ✅ Recommended |
| **Update controller to pass posts, membership, stats** | ✅ Required |
| **Update DashboardResolver to use election.show** | ✅ Required |
| **Add route for elections.show** | ✅ Required |
| **Delete old ElectionPage.vue (or keep as backup)** | ⚠️ Optional |

---

## 🚀 **Next Steps**

1. **Replace ElectionPage.vue content** with Show.vue design
2. **Update ElectionManagementController@dashboard** to pass posts, membership, stats
3. **Add elections.show route** if not exists
4. **Test the flow**:
   - Single election → redirect to `/election` (which now shows full page)
   - Voter can see candidates and vote
   - Already voted shows "Thank You" card

**Shall I implement this replacement?** 🚀
## 🎯 **Route Strategy: /election vs /elections/{slug}**

This is a **critical architectural decision**. Let me analyze both approaches:

---

## 📊 **Option Comparison**

| Aspect | Option A: `/election` (Session-based) | Option B: `/elections/{slug}` (URL-based) |
|--------|---------------------------------------|-------------------------------------------|
| **URL** | `/election` | `/elections/general-election-2026` |
| **Election Context** | Session variable | URL parameter |
| **Sharable Links** | ❌ No (shows different election to different users) | ✅ Yes (always shows same election) |
| **Bookmarkable** | ❌ No | ✅ Yes |
| **SEO** | ❌ Poor | ✅ Good (keywords in URL) |
| **Browser History** | ❌ Single entry | ✅ Separate entries per election |
| **Multi-election Support** | ❌ Can't handle multiple active elections | ✅ Native support |
| **Implementation Complexity** | Simple | Slightly more complex |
| **Current Code** | Already working | Need to add |

---

## 🔴 **Why Option A (`/election`) is Problematic**

### **1. Sharing Links**
```
User A shares: "Check out the election at /election"
User B clicks → sees THEIR election (maybe different one)
→ Confusing, not what User A intended
```

### **2. Multiple Elections**
```
What if a user has 2 active elections?
/election → Which one do they see? (Ambiguous)
```

### **3. Browser History**
```
User votes in Election A → clicks back → sees Election B
→ Disorienting experience
```

### **4. Analytics**
```
Can't track which election gets most views
All traffic goes to /election
```

---

## ✅ **Why Option B (`/elections/{slug}`) is Superior**

### **1. Clear, Shareable URLs**
```
/elections/general-election-2026
/elections/local-council-2026
```
Users can share exact links

### **2. Native Multi-Election Support**
```php
// DashboardResolver can redirect to specific election
if ($eligibleCount === 1) {
    return redirect()->route('elections.show', $activeElection->slug);
}
```

### **3. SEO Benefits**
```
URL contains election name: "general-election-2026"
Search engines index individual election pages
```

### **4. Analytics & Tracking**
```php
// Track per-election views
Log::info('Election viewed', ['election_id' => $election->id]);
```

### **5. Back/Forward Navigation**
```
User votes in Election A → clicks back → sees Election A (not B)
→ Consistent experience
```

---

## 🏗️ **Recommended Architecture: Hybrid Approach**

### **Option C: Both, with Clear Purpose**

| Route | Purpose | When Used |
|-------|---------|-----------|
| **`/election`** | Session-based fallback | When no specific election in URL |
| **`/elections/{slug}`** | Primary election page | Direct access, sharing, bookmarks |

### **Flow:**
```
User logs in
    ↓
DashboardResolver
    ↓
┌─────────────────────────────────────────────────────────────┐
│ 0 elections → organisations.show                            │
│                                                             │
│ 1 election → redirect to /elections/{slug}                 │
│                                                             │
│ 2+ elections → organisations.show (user chooses)           │
│                ↓                                            │
│                User clicks election card                    │
│                ↓                                            │
│                /elections/{slug}                           │
└─────────────────────────────────────────────────────────────┘

Existing /election route remains for backward compatibility
→ redirects to the single active election's /elections/{slug}
```

---

## 📝 **Implementation Plan**

### **Step 1: Create New Route**
```php
// routes/election/electionRoutes.php

// Primary election page (URL-based)
Route::get('/elections/{slug}', [ElectionVotingController::class, 'show'])
    ->name('elections.show')
    ->middleware(['auth', 'verified']);

// Legacy session-based route (redirects)
Route::get('/election', [ElectionController::class, 'redirectToElection'])
    ->name('election.dashboard')
    ->middleware(['auth', 'verified']);
```

### **Step 2: Create Redirect Controller**
```php
// app/Http/Controllers/ElectionController.php

public function redirectToElection()
{
    $user = auth()->user();
    $eligibleCount = $user->countActiveElections();
    
    if ($eligibleCount === 0) {
        return redirect()->route('organisations.show', session('current_organisation_id'));
    }
    
    if ($eligibleCount === 1) {
        $election = $user->getActiveElection();
        return redirect()->route('elections.show', $election->slug);
    }
    
    // Multiple elections - show selection page or org dashboard
    return redirect()->route('organisations.show', session('current_organisation_id'));
}
```

### **Step 3: Update DashboardResolver Priority 3**
```php
// app/Services/DashboardResolver.php

if ($eligibleCount === 1) {
    $activeElection = $user->getActiveElection();
    $electionOrg = Organisation::find($activeElection->organisation_id);
    
    if ($electionOrg) {
        $this->tenantContext->setContext($user, $electionOrg);
        // Redirect to the specific election page, not generic /election
        $targetUrl = route('elections.show', $activeElection->slug);
        $this->cacheResolution($user, $targetUrl);
        return redirect()->to($targetUrl);
    }
}
```

### **Step 4: Update ElectionCard.vue Links**
```vue
<!-- resources/js/Pages/Organisations/Partials/ElectionCard.vue -->

<!-- Vote button -->
<a
    v-if="election.status === 'active' && canVote && !hasVoted"
    :href="route('elections.show', election.slug)"  <!-- Changed from /election -->
    class="...">
    Vote Now
</a>

<!-- Manage button (for officers) -->
<a
    v-if="canManage"
    :href="`/elections/${election.id}/management`"  <!-- Management uses ID -->
    class="...">
    Manage
</a>
```

---

## ✅ **Final Recommendation**

| Decision | Action |
|----------|--------|
| **Primary URL** | Use `/elections/{slug}` for election pages |
| **Legacy `/election`** | Keep but redirect to `/elections/{slug}` |
| **DashboardResolver** | Redirect to `/elections/{slug}` for single election |
| **ElectionCard.vue** | Link to `/elections/{slug}` |
| **Management Pages** | Keep `/elections/{id}/management` (uses ID, not slug) |

---

## 📋 **URL Structure Summary**

```
# Voter-facing (public, shareable)
/elections/general-election-2026          # Election landing/voting page
/elections/general-election-2026/results  # Results page (when published)

# Officer-facing (management)
/elections/{id}/management                # Management dashboard
/elections/{id}/voters                    # Voter list management

# Legacy (redirects)
/election                                 # Redirects to /elections/{active-election-slug}
```

---

## 🚀 **Next Steps**

1. **Create `elections.show` route** with slug parameter
2. **Create `ElectionVotingController@show`** to render the page
3. **Update DashboardResolver** to redirect to slug-based URL
4. **Update ElectionCard.vue** links to use slug-based route
5. **Test the flow** with 1 and 2+ elections

**Shall I implement this hybrid approach?** 🚀

## 📝 **Claude Code CLI Instructions: Election Architecture & DashboardResolver Upgrade**

---

## 🎯 **Background: The Problem We're Solving**

### **Current Issue**
When a user logs in, the `DashboardResolver` doesn't properly handle elections where:
- User is eligible to vote (`ElectionMembership.status = 'active'`)
- User hasn't voted yet (`has_voted = false`)
- Multiple elections exist for the same organisation

### **Current Flow (Broken)**
```
Login → DashboardResolver → $user->hasActiveElection() → redirect to election.dashboard
```
- ❌ Doesn't check if user is actually eligible
- ❌ Doesn't count how many elections
- ❌ Redirects to generic route without election context

### **Target Flow (Correct)**
```
Login → DashboardResolver → Query eligible elections → Count
    │
    ├── 0 elections → Organisation Dashboard
    │
    ├── 1 election → Direct to voting page (elections.show)
    │
    └── 2+ elections → Organisation Dashboard (user chooses)
```

---

## 📋 **Implementation Plan**

### **Phase 1: Database Check**
Ensure `election_memberships` table has `has_voted` column.

### **Phase 2: User Model Methods**
Add methods to check eligible elections.

### **Phase 3: DashboardResolver Update**
Replace election priority logic with count-based decision.

### **Phase 4: Route & Controller**
Ensure `elections.show` route exists for voting page.

---

## 🔧 **Claude Code CLI Prompt Instructions**

```markdown
## Task: Implement Election Eligibility Logic in DashboardResolver

### Step 1: Verify Database Schema

First, check if `election_memberships` table has the `has_voted` column:

```bash
# Check migration files
ls database/migrations/ | grep election_memberships

# If column missing, create migration
php artisan make:migration add_has_voted_to_election_memberships
```

**Migration content:**
```php
public function up(): void
{
    Schema::table('election_memberships', function (Blueprint $table) {
        $table->boolean('has_voted')->default(false)->after('status');
        $table->timestamp('voted_at')->nullable()->after('has_voted');
    });
}
```

Run migration:
```bash
php artisan migrate
```

---

### Step 2: Add Methods to User Model

**File:** `app/Models/User.php`

Add these methods inside the User class:

```php
use App\Models\Election;
use App\Models\ElectionMembership;
use Illuminate\Support\Facades\DB;

/**
 * Get all elections the user is eligible to vote in
 * Eligibility requires:
 * 1. Election is active (status = 'active')
 * 2. Election is real (type = 'real')
 * 3. Current date is within election period
 * 4. User has membership with status = 'active'
 * 5. User hasn't voted yet (has_voted = false)
 *
 * @return \Illuminate\Database\Eloquent\Collection
 */
public function getEligibleElections()
{
    // Get current organisation from session
    $orgId = session('current_organisation_id');
    
    if (!$orgId) {
        return collect();
    }
    
    return Election::where('organisation_id', $orgId)
        ->where('status', 'active')
        ->where('type', 'real')
        ->where('start_date', '<=', now())
        ->where('end_date', '>=', now())
        ->whereHas('memberships', function($query) {
            $query->where('user_id', $this->id)
                  ->where('status', 'active')
                  ->where('has_voted', false);
        })
        ->get();
}

/**
 * Check if user has a single eligible election
 *
 * @return bool
 */
public function hasSingleEligibleElection(): bool
{
    return $this->getEligibleElections()->count() === 1;
}

/**
 * Get the single eligible election (if exactly one)
 *
 * @return Election|null
 */
public function getSingleEligibleElection(): ?Election
{
    $elections = $this->getEligibleElections();
    return $elections->count() === 1 ? $elections->first() : null;
}

/**
 * Get count of eligible elections
 *
 * @return int
 */
public function getEligibleElectionsCount(): int
{
    return $this->getEligibleElections()->count();
}
```

---

### Step 3: Update DashboardResolver Election Priority

**File:** `app/Services/DashboardResolver.php`

**Find** the PRIORITY 3 section (lines 108-122 in current file). **Replace** it with:

```php
// =============================================
// PRIORITY 3: ACTIVE ELECTIONS (User can vote)
// =============================================

// Get count of elections where user has active membership AND hasn't voted
$eligibleElections = $user->getEligibleElections();
$eligibleCount = $eligibleElections->count();

if ($eligibleCount > 0) {
    Log::info('🗳️ PRIORITY 3 HIT: Eligible elections found', [
        'user_id' => $user->id,
        'eligible_count' => $eligibleCount,
        'election_ids' => $eligibleElections->pluck('id')->toArray(),
        'election_names' => $eligibleElections->pluck('name')->toArray(),
    ]);

    // Set tenant context from the first election's organisation
    $firstElection = $eligibleElections->first();
    $electionOrganisation = \App\Models\Organisation::find($firstElection->organisation_id);
    if ($electionOrganisation) {
        try {
            $this->tenantContext->setContext($user, $electionOrganisation);
            Log::debug('DashboardResolver: TenantContext set for election organisation', [
                'user_id' => $user->id,
                'organisation_id' => $electionOrganisation->id,
            ]);
        } catch (\RuntimeException $e) {
            Log::warning('DashboardResolver: Failed to set TenantContext', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    // CASE 1: Exactly ONE election → redirect directly to voting page
    if ($eligibleCount === 1) {
        $targetUrl = route('elections.show', $firstElection->slug);
        Log::info('🎯 Single eligible election found - direct redirect to voting page', [
            'user_id' => $user->id,
            'election_id' => $firstElection->id,
            'election_slug' => $firstElection->slug,
            'target_url' => $targetUrl,
        ]);
        $this->cacheResolution($user, $targetUrl);
        return redirect()->to($targetUrl);
    }

    // CASE 2: Multiple elections → redirect to organisation dashboard
    // User will see all eligible elections in the Active Elections banner
    Log::info('📋 Multiple eligible elections found - redirecting to organisation dashboard for selection', [
        'user_id' => $user->id,
        'election_count' => $eligibleCount,
        'election_names' => $eligibleElections->pluck('name')->toArray(),
    ]);
    
    // Get the organisation from the first election
    $organisation = $electionOrganisation;
    if ($organisation) {
        $targetUrl = route('organisations.show', $organisation->slug);
        $this->cacheResolution($user, $targetUrl);
        return redirect()->to($targetUrl);
    }
    
    // Fallback if no organisation found
    $this->cacheResolution($user, route('dashboard'));
    return redirect()->route('dashboard');
}

Log::debug('✗ PRIORITY 3 SKIPPED: No eligible elections for user', [
    'user_id' => $user->id,
]);
```

---

### Step 4: Ensure Voting Page Route Exists

**File:** `routes/election/electionRoutes.php`

Add the voting page route if it doesn't exist:

```php
use App\Http\Controllers\ElectionVotingController;

// Add after existing routes
Route::get('/elections/{slug}', [ElectionVotingController::class, 'show'])
    ->name('elections.show')
    ->middleware(['auth', 'verified']);
```

---

### Step 5: Create ElectionVotingController

**File:** `app/Http/Controllers/ElectionVotingController.php`

```bash
php artisan make:controller ElectionVotingController
```

```php
<?php

namespace App\Http\Controllers;

use App\Models\Election;
use App\Models\ElectionMembership;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ElectionVotingController extends Controller
{
    /**
     * Show the voting page for an election
     */
    public function show($slug)
    {
        $election = Election::where('slug', $slug)
            ->where('status', 'active')
            ->where('type', 'real')
            ->firstOrFail();
        
        $user = auth()->user();
        
        // Check if user is eligible to vote
        $membership = ElectionMembership::where('user_id', $user->id)
            ->where('election_id', $election->id)
            ->first();
        
        if (!$membership || $membership->status !== 'active') {
            abort(403, 'You are not eligible to vote in this election.');
        }
        
        // Check if user already voted
        if ($membership->has_voted) {
            return redirect()->route('organisations.show', $election->organisation->slug)
                ->with('info', 'You have already voted in this election.');
        }
        
        // Get posts with candidates
        $posts = $election->posts()->with('candidates')->get();
        
        return Inertia::render('Election/Vote', [
            'election' => $election,
            'posts' => $posts,
            'membership' => $membership,
        ]);
    }
}
```

---

### Step 6: Create Voting Page Vue Component

**File:** `resources/js/Pages/Election/Vote.vue`

```vue
<template>
  <ElectionLayout>
    <div class="min-h-screen bg-slate-50 py-8">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-8">
          <h1 class="text-2xl font-bold text-slate-900">{{ election.name }}</h1>
          <p class="text-slate-500 mt-1">
            {{ formatDate(election.start_date) }} → {{ formatDate(election.end_date) }}
          </p>
        </div>
        
        <!-- Voting Form -->
        <form @submit.prevent="submitVote" class="space-y-6">
          <div v-for="post in posts" :key="post.id" class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-semibold text-slate-800 mb-4">{{ post.name }}</h2>
            
            <div class="space-y-3">
              <label
                v-for="candidate in post.candidates"
                :key="candidate.id"
                class="flex items-center gap-3 p-3 rounded-lg border border-slate-200 hover:bg-slate-50 cursor-pointer"
              >
                <input
                  type="radio"
                  :name="`post_${post.id}`"
                  :value="candidate.id"
                  v-model="votes[post.id]"
                  class="w-4 h-4 text-blue-600"
                />
                <div>
                  <p class="font-medium text-slate-800">{{ candidate.name }}</p>
                  <p v-if="candidate.party" class="text-xs text-slate-500">{{ candidate.party }}</p>
                </div>
              </label>
            </div>
          </div>
          
          <div class="flex justify-end gap-4">
            <a
              :href="route('organisations.show', election.organisation?.slug)"
              class="px-6 py-3 border border-slate-300 rounded-lg text-slate-700 hover:bg-slate-50 transition"
            >
              Cancel
            </a>
            <button
              type="submit"
              :disabled="submitting"
              class="px-6 py-3 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-white font-semibold rounded-lg transition"
            >
              {{ submitting ? 'Submitting...' : 'Submit Vote' }}
            </button>
          </div>
        </form>
        
      </div>
    </div>
  </ElectionLayout>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { router } from '@inertiajs/vue3'
import ElectionLayout from '@/Layouts/ElectionLayout.vue'

const props = defineProps({
  election: Object,
  posts: Array,
  membership: Object,
})

const votes = reactive({})
const submitting = ref(false)

const formatDate = (date) => date ? new Date(date).toLocaleDateString() : '—'

const submitVote = () => {
  // Validate all posts have votes
  for (const post of props.posts) {
    if (!votes[post.id]) {
      alert(`Please select a candidate for ${post.name}`)
      return
    }
  }
  
  if (!confirm('Are you sure you want to submit your vote? This action cannot be undone.')) {
    return
  }
  
  submitting.value = true
  
  router.post(route('elections.vote', props.election.slug), {
    votes: votes
  }, {
    preserveScroll: true,
    onFinish: () => { submitting.value = false }
  })
}
</script>
```

---

### Step 7: Add Vote Submission Route & Controller Method

**File:** `routes/election/electionRoutes.php`

```php
Route::post('/elections/{slug}/vote', [ElectionVotingController::class, 'store'])
    ->name('elections.vote')
    ->middleware(['auth', 'verified']);
```

**File:** `app/Http/Controllers/ElectionVotingController.php`

Add the store method:

```php
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
        return redirect()->route('organisations.show', $election->organisation->slug)
            ->with('error', 'You have already voted.');
    }
    
    $validated = $request->validate([
        'votes' => 'required|array',
        'votes.*' => 'required|exists:candidates,id',
    ]);
    
    DB::transaction(function () use ($election, $user, $validated, $membership) {
        foreach ($validated['votes'] as $postId => $candidateId) {
            Vote::create([
                'election_id' => $election->id,
                'post_id' => $postId,
                'candidate_id' => $candidateId,
                'user_id' => $user->id,
            ]);
        }
        
        $membership->update([
            'has_voted' => true,
            'voted_at' => now(),
        ]);
    });
    
    return redirect()->route('organisations.show', $election->organisation->slug)
        ->with('success', 'Your vote has been recorded successfully!');
}
```

---

### Step 8: Run Tests & Verify

```bash
# Run migrations
php artisan migrate

# Clear cache
php artisan cache:clear

# Test the flow
php artisan test --filter=ElectionDashboardAccessTest

# Manual test:
# 1. Login as a voter
# 2. If 1 election → should redirect to /elections/{slug}
# 3. If 2+ elections → should go to organisation dashboard
# 4. On dashboard, see Active Elections banner
# 5. Click Vote Now → should go to voting page
```

---

## ✅ **Success Criteria**

| Scenario | Expected Behavior |
|----------|-------------------|
| User eligible for 0 elections | Redirect to organisation dashboard |
| User eligible for 1 election | Redirect directly to voting page |
| User eligible for 2+ elections | Redirect to organisation dashboard |
| User already voted | Redirect to dashboard with message |
| User not eligible | Redirect to dashboard with message |

---

## 🚀 **Ready to Execute!**

This TDD-first approach ensures:
- ✅ Clean separation of concerns
- ✅ Proper database schema
- ✅ Testable User model methods
- ✅ Updated DashboardResolver logic
- ✅ Working voting page

**Proceed with implementation!** 🎯

## 🔍 **Senior Architect Review: DashboardResolver & LoginResponse**

After thoroughly analyzing both files, here's my critical assessment:

---

## ✅ **What's Working Well**

| Component | Strength |
|-----------|----------|
| **LoginResponse** | Excellent 3-level fallback chain (Normal → Emergency → Static HTML) |
| **Cache Strategy** | Smart caching with session freshness validation |
| **Rate Limiting** | Prevents brute force after successful login |
| **Audit Logging** | Comprehensive request tracking with performance monitoring |
| **TenantContext** | Proper organisation context management |
| **Role Resolution** | Multi-source role detection (org roles, commission, voter, Spatie legacy) |

---

## 🔴 **CRITICAL ISSUES: DashboardResolver Election Logic**

### **Issue 1: Inconsistent Election Checking**

**Current Code (Lines 108-122):**
```php
if ($user->hasActiveElection()) {
    $activeElection = $user->getActiveElection();
    // ...
    return redirect()->route('election.dashboard');
}
```

**Problem:** 
- Uses `hasActiveElection()` and `getActiveElection()` methods on User model
- Redirects to `election.dashboard` (generic route)
- **Does NOT redirect to actual voting page with election context!**

**Expected:**
```php
if ($activeElection = $this->getActiveElectionForUser($user)) {
    return redirect()->route('elections.show', $activeElection->slug);
}
```

### **Issue 2: `getActiveElectionForUser()` Method is Present but NOT Called!**

Look at lines 752-833 - you have a **fully implemented** `getActiveElectionForUser()` method that:
- Checks organisation membership
- Finds active real elections
- Filters by date range
- Checks if user already voted
- Returns the election object

But this method is **NEVER CALLED** in the main `resolve()` flow!

### **Issue 3: `hasActiveElection()` Method on User Model**

The code uses `$user->hasActiveElection()` but:
- Where is this method defined?
- Does it use the same logic as `getActiveElectionForUser()`?
- Likely incomplete or inconsistent

---

## 🔴 **Critical Issue: Election Dashboard Route**

**Current Redirect:**
```php
return redirect()->route('election.dashboard');
```

**What is `election.dashboard`?** 
- Does this route exist?
- Does it accept an election parameter?
- Does it render the voting page?

**Expected Redirect:**
```php
return redirect()->route('elections.show', $activeElection->slug);
```

---

## 🔴 **Issue: Missing Voting Page Route Check**

The current flow redirects to `election.dashboard` instead of `elections.show`. You need to verify:

```bash
# Check if these routes exist
php artisan route:list | grep "elections.show"
php artisan route:list | grep "election.dashboard"
```

---

## ✅ **Fix: Update DashboardResolver Election Priority**

### **Replace Lines 108-122 with:**

```php
// =============================================
// PRIORITY 3: ACTIVE ELECTION AVAILABLE
// User can vote in an active election
// =============================================
$activeElection = $this->getActiveElectionForUser($user);
if ($activeElection) {
    Log::info('🗳️ PRIORITY 3 HIT: Active election found - user can vote', [
        'user_id' => $user->id,
        'election_id' => $activeElection->id,
        'election_slug' => $activeElection->slug,
        'election_name' => $activeElection->name,
    ]);
    
    // Set tenant context from election's organisation
    $electionOrganisation = \App\Models\Organisation::find($activeElection->organisation_id);
    if ($electionOrganisation) {
        $this->tenantContext->setContext($user, $electionOrganisation);
    }
    
    $this->cacheResolution($user, route('elections.show', $activeElection->slug));
    return redirect()->route('elections.show', $activeElection->slug);
}
Log::debug('✗ PRIORITY 3 SKIPPED: No active election for user');
```

---

## 🔴 **Issue: User Model Missing Methods**

You need to ensure `User.php` has these methods:

```php
// app/Models/User.php

/**
 * Check if user has an active election they can vote in
 */
public function hasActiveElection(): bool
{
    return !is_null($this->getActiveElection());
}

/**
 * Get the active election the user can vote in
 */
public function getActiveElection(): ?Election
{
    $orgId = session('current_organisation_id');
    
    if (!$orgId) {
        return null;
    }
    
    // Check membership first - user must be an active voter
    $membership = ElectionMembership::where('user_id', $this->id)
        ->where('organisation_id', $orgId)
        ->where('status', 'active')
        ->where('has_voted', false)
        ->first();
    
    if (!$membership) {
        return null;
    }
    
    // Find active election in that organisation
    return Election::where('organisation_id', $orgId)
        ->where('status', 'active')
        ->where('type', 'real')
        ->where('start_date', '<=', now())
        ->where('end_date', '>=', now())
        ->first();
}
```

---

## 📋 **Complete Fix Checklist**

| Priority | Fix | File |
|----------|-----|------|
| 1 | Call `getActiveElectionForUser()` instead of `$user->hasActiveElection()` | `DashboardResolver.php` |
| 2 | Redirect to `elections.show` route with election slug | `DashboardResolver.php` |
| 3 | Ensure `elections.show` route exists | `routes/election/electionRoutes.php` |
| 4 | Verify `ElectionVotingController@show` exists | `app/Http/Controllers/ElectionVotingController.php` |
| 5 | Create `Election/Vote.vue` component | `resources/js/Pages/Election/Vote.vue` |

---

## 🚀 **Next Steps**

1. **Update DashboardResolver** to use `getActiveElectionForUser()`
2. **Create `elections.show` route** if it doesn't exist
3. **Create ElectionVotingController** with `show()` method
4. **Create Vote.vue component** for voting page
5. **Test the flow** with a voter user

**The foundation is solid. The only gap is wiring the election detection to the correct voting route.** 🚀
## ✅ **Excellent Insight! You've Identified the Core Logic**

You're absolutely right. The election count matters for the redirect decision. Let me analyze and propose the correct flow:

---

## 📊 **Election Membership Count Logic**

```
User logs in
    ↓
DashboardResolver runs
    ↓
Get all elections where user has ACTIVE membership (status = 'active' AND has_voted = false)
    ↓
┌─────────────────────────────────────────────────────────────────────────────┐
│                                                                             │
│  COUNT = 0                                                                 │
│  └── No active elections → Redirect to Organisation Dashboard              │
│                                                                             │
│  COUNT = 1                                                                 │
│  └── Exactly one election → Redirect DIRECTLY to voting page               │
│      (user doesn't need to see dashboard)                                  │
│                                                                             │
│  COUNT >= 2                                                                │
│  └── Multiple elections → Redirect to Organisation Dashboard               │
│      (user must choose which election to vote in)                          │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## 🎯 **Updated DashboardResolver Logic**

### **Replace Priority 3 with:**

```php
// =============================================
// PRIORITY 3: ACTIVE ELECTIONS (User can vote)
// =============================================

// Get ALL elections where user has active membership AND hasn't voted
$eligibleElections = Election::where('status', 'active')
    ->where('type', 'real')
    ->where('start_date', '<=', now())
    ->where('end_date', '>=', now())
    ->whereHas('memberships', function($q) use ($user) {
        $q->where('user_id', $user->id)
          ->where('status', 'active')
          ->where('has_voted', false);
    })
    ->get();

$eligibleCount = $eligibleElections->count();

if ($eligibleCount > 0) {
    Log::info('🗳️ PRIORITY 3 HIT: Eligible elections found', [
        'user_id' => $user->id,
        'eligible_count' => $eligibleCount,
        'election_ids' => $eligibleElections->pluck('id')->toArray(),
    ]);

    // Set tenant context from the first election's organisation
    $firstElection = $eligibleElections->first();
    $electionOrganisation = \App\Models\Organisation::find($firstElection->organisation_id);
    if ($electionOrganisation) {
        $this->tenantContext->setContext($user, $electionOrganisation);
    }

    // CASE 1: Exactly ONE election → redirect directly to voting page
    if ($eligibleCount === 1) {
        $targetUrl = route('elections.show', $firstElection->slug);
        Log::info('🎯 Single election found - direct redirect to voting page', [
            'user_id' => $user->id,
            'election_id' => $firstElection->id,
            'election_slug' => $firstElection->slug,
            'target_url' => $targetUrl,
        ]);
        $this->cacheResolution($user, $targetUrl);
        return redirect()->to($targetUrl);
    }

    // CASE 2: Multiple elections → redirect to organisation dashboard
    // User will see all eligible elections in the Active Elections banner
    Log::info('📋 Multiple elections found - redirecting to organisation dashboard for selection', [
        'user_id' => $user->id,
        'election_count' => $eligibleCount,
    ]);
    
    // Get the organisation from the first election
    $organisation = $electionOrganisation;
    if ($organisation) {
        $targetUrl = route('organisations.show', $organisation->slug);
        $this->cacheResolution($user, $targetUrl);
        return redirect()->to($targetUrl);
    }
    
    // Fallback if no organisation found
    $this->cacheResolution($user, route('dashboard'));
    return redirect()->route('dashboard');
}

Log::debug('✗ PRIORITY 3 SKIPPED: No eligible elections for user');
```

---

## 📝 **Add Method to User Model (if needed)**

```php
// app/Models/User.php

/**
 * Get all elections the user is eligible to vote in
 */
public function getEligibleElections()
{
    return Election::where('status', 'active')
        ->where('type', 'real')
        ->where('start_date', '<=', now())
        ->where('end_date', '>=', now())
        ->whereHas('memberships', function($q) {
            $q->where('user_id', $this->id)
              ->where('status', 'active')
              ->where('has_voted', false);
        })
        ->get();
}

/**
 * Check if user has a single eligible election
 */
public function hasSingleEligibleElection(): bool
{
    return $this->getEligibleElections()->count() === 1;
}

/**
 * Get the single eligible election (if exactly one)
 */
public function getSingleEligibleElection(): ?Election
{
    $elections = $this->getEligibleElections();
    return $elections->count() === 1 ? $elections->first() : null;
}
```

---

## 📊 **Flow Diagram**

```
┌─────────────────────────────────────────────────────────────────────────────────────────────┐
│                              USER LOGIN FLOW (UPDATED)                                      │
├─────────────────────────────────────────────────────────────────────────────────────────────┤
│                                                                                             │
│  User logs in                                                                               │
│       │                                                                                     │
│       ▼                                                                                     │
│  DashboardResolver runs                                                                     │
│       │                                                                                     │
│       ├─── No eligible elections ──────────────────────────► Organisation Dashboard         │
│       │                                                         (shows "No active elections")│
│       │                                                                                     │
│       ▼                                                                                     │
│  Eligible elections found                                                                    │
│       │                                                                                     │
│       ├─── COUNT = 1 ──────────────────────────────────────► 🗳️ VOTING PAGE                 │
│       │                                                         (direct redirect)           │
│       │                                                                                     │
│       └─── COUNT >= 2 ─────────────────────────────────────► Organisation Dashboard         │
│                                                               (shows Active Elections banner │
│                                                                with ALL eligible elections)  │
│                                                                                             │
└─────────────────────────────────────────────────────────────────────────────────────────────┘
```

---

## 🎯 **Benefits of This Approach**

| Scenario | Outcome | Why |
|----------|---------|-----|
| **1 election** | Direct to voting page | Saves click, user knows what to vote for |
| **2+ elections** | Show dashboard | User needs to choose which election to vote in |
| **0 elections** | Show dashboard | User sees "No active elections" message |
| **Already voted** | Dashboard (via has_voted=false) | User can't vote again, sees no eligible elections |

---

## 🔧 **Additional Consideration: Election Priority**

What if there are multiple elections but one is ending soon? You could add urgency:

```php
// Sort by end_date (soonest first)
$eligibleElections = $eligibleElections->sortBy('end_date');

if ($eligibleCount === 1) {
    // Direct to that election
} else {
    // Show dashboard with elections sorted by urgency
    // Highlight the one ending soon
}
```

---

## ✅ **Summary**

| Component | Change |
|-----------|--------|
| **DashboardResolver** | Replace `hasActiveElection()` with `getEligibleElections()` count logic |
| **Redirect Decision** | 1 election → voting page, ≥2 elections → dashboard |
| **User Model** | Add `getEligibleElections()` method |
| **ElectionMembership** | Ensure `has_voted` column exists and is used |

**This is the correct logic for your multi-election scenario. Shall I implement this?** 🚀

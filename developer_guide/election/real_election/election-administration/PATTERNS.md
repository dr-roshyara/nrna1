# Code Patterns: SSOT Migration Examples

**Real code examples and patterns for migrating controllers to the ElectionLifecycle facade.**

---

## Pattern 1: Simple Permission Check

### Before (Legacy)
```php
public function show(Election $election)
{
    if (!$election->is_active) {
        return redirect('/')->with('error', 'Election not active');
    }
    
    if ($election->status !== 'active') {
        return redirect('/')->with('error', 'Voting not started');
    }
    
    return view('election.show', ['election' => $election]);
}
```

**Problems:**
- Multiple competing checks
- No consistent error messages
- Silent failure if one check is missed
- Hard to understand: what does "active" really mean?

### After (SSOT)
```php
use App\Application\Election\Facades\ElectionLifecycle;

public function show(Election $election)
{
    $lifecycle = ElectionLifecycle::of($election);
    
    if (!$lifecycle->canVote()) {
        return redirect('/')
            ->with('error', 'Voting not allowed: ' . $lifecycle->blockedReason());
    }
    
    return view('election.show', [
        'election' => $election,
        'lifecycle' => $lifecycle,
    ]);
}
```

**Benefits:**
- Single authoritative check
- Descriptive error message
- Clear intent: "can vote"
- Impossible to miss a condition

---

## Pattern 2: Multiple Conditional Paths

### Before (Legacy)
```php
public function edit(Election $election)
{
    // Can only edit during setup phase
    if ($election->status === 'archived' || 
        ($election->is_active && $election->status === 'voting')) {
        return abort(403, 'Cannot edit active election');
    }
    
    return view('election.edit', ['election' => $election]);
}

public function delete(Election $election)
{
    // Can only delete draft or archived elections
    if ($election->status === 'active' || 
        ($election->is_active && $election->status === 'setup')) {
        return abort(403, 'Cannot delete active election');
    }
    
    return view('election.delete-confirm', ['election' => $election]);
}

public function archive(Election $election)
{
    // Can only archive completed elections
    if (!$election->is_active && $election->status === 'completed') {
        $election->update(['archived' => true]);
        return redirect('/')->with('success', 'Election archived');
    }
    
    return back()->with('error', 'Cannot archive this election');
}
```

**Problems:**
- Duplicated permission logic
- Inconsistent naming (status vs is_active vs archived field)
- Hard to understand overall state model
- Risk of authorization bypass

### After (SSOT)
```php
use App\Application\Election\Facades\ElectionLifecycle;

public function edit(Election $election)
{
    if (!ElectionLifecycle::of($election)->canEdit()) {
        return abort(403, 'Cannot edit election now');
    }
    
    return view('election.edit', ['election' => $election]);
}

public function delete(Election $election)
{
    $lifecycle = ElectionLifecycle::of($election);
    
    if ($lifecycle->isTerminal()) {
        return abort(403, 'Cannot delete archived election');
    }
    
    return view('election.delete-confirm', ['election' => $election]);
}

public function archive(Election $election)
{
    $lifecycle = ElectionLifecycle::of($election);
    
    if (!$lifecycle->isTerminal()) {
        return back()->with('error', 'Election must be complete before archiving');
    }
    
    // In terminal state - safe to archive
    $election->update(['archived_at' => now()]);
    return redirect('/')->with('success', 'Election archived');
}
```

**Benefits:**
- Each check has single clear purpose
- State model is self-documenting
- Permission logic centralized (in engine, not scattered)
- Impossible to duplicate/contradict logic

---

## Pattern 3: State-Based UI Rendering

### Before (Legacy)
```blade
{{-- election/show.blade.php --}}
<div class="election-card">
    <h2>{{ $election->name }}</h2>
    
    {{-- ❌ Multiple competing checks for same decision --}}
    @if($election->status === 'active' && $election->is_active)
        <button class="btn-vote" @click="startVoting">Vote Now</button>
    @elseif($election->status === 'completed')
        <a href="/election/{{ $election->id }}/results">View Results</a>
    @elseif($election->status === 'draft' || !$election->is_active)
        <p class="text-muted">Election not yet active</p>
    @else
        <p class="text-warning">Unknown status: {{ $election->status }}</p>
    @endif
    
    {{-- ❌ Status field shown directly to user --}}
    <small>Status: {{ $election->status }} | Active: {{ $election->is_active ? 'Yes' : 'No' }}</small>
</div>
```

**Problems:**
- Multiple conditions for same decision
- User sees internal database field names
- Hard to maintain consistency across pages
- Status text not localized

### After (SSOT)
```blade
{{-- election/show.blade.php --}}
<div class="election-card">
    <h2>{{ $election->name }}</h2>
    
    {{-- ✅ Single check, clear intent --}}
    @if($lifecycle->canVote())
        <button class="btn-vote" @click="startVoting">Vote Now</button>
    @elseif($lifecycle->canPublishResults())
        <a href="/election/{{ $election->id }}/results">Publish Results</a>
    @elseif($lifecycle->isTerminal())
        <a href="/election/{{ $election->id }}/results">View Results</a>
    @else
        <p class="text-muted">{{ $lifecycle->blockedReason() }}</p>
    @endif
    
    {{-- ✅ User-friendly status label --}}
    <small>{{ __('election.status') }}: {{ $lifecycle->state()->label() }}</small>
</div>
```

**Benefits:**
- Single condition per UI state
- User-friendly messages (via label())
- Easy to localize (via __() helper)
- Intent is obvious

---

## Pattern 4: Form Validation Before Submission

### Before (Legacy)
```php
public function updateConfiguration(Request $request, Election $election)
{
    // ❌ Scattered permission checks
    if ($election->status === 'voting' || !$election->is_active) {
        return back()->with('error', 'Cannot edit voting election');
    }
    
    if ($election->is_active && $election->status !== 'setup') {
        return back()->with('error', 'Wrong state for this operation');
    }
    
    // Actually update configuration
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
    ]);
    
    $election->update($validated);
    
    return back()->with('success', 'Election updated');
}
```

**Problems:**
- Authorization scattered
- Hard to understand which states allow editing
- Risk of auth bypass

### After (SSOT)
```php
use App\Application\Election\Facades\ElectionLifecycle;

public function updateConfiguration(Request $request, Election $election)
{
    // ✅ Single clear authorization check
    if (!ElectionLifecycle::of($election)->canEdit()) {
        return back()->withErrors([
            'election' => 'Election configuration cannot be modified in ' . 
                         ElectionLifecycle::of($election)->state()->label() . 
                         ' state'
        ]);
    }
    
    // Validation is independent of state
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
    ]);
    
    $election->update($validated);
    
    return back()->with('success', 'Election updated');
}
```

**Benefits:**
- Clear authorization boundary
- State logic separate from validation
- Easy to test (can test canEdit() independently)

---

## Pattern 5: Complex Business Logic with Snapshot

### Before (Legacy)
```php
public function transitionToVoting(Request $request, Election $election)
{
    // ❌ Multiple independent checks that might contradict
    if (!$election->is_active) {
        return back()->with('error', 'Election not active');
    }
    
    // Check posts exist
    if ($election->posts()->count() === 0) {
        return back()->with('error', 'Must have at least one post');
    }
    
    // Check candidates exist
    if ($election->candidates()->count() === 0) {
        return back()->with('error', 'Must have at least one candidate');
    }
    
    // Check voters exist
    if ($election->voters()->count() === 0) {
        return back()->with('error', 'Must have at least one voter');
    }
    
    // Check committee has approved candidates
    if (!$this->allCandidatesApproved($election)) {
        return back()->with('error', 'Committee must approve all candidates');
    }
    
    // Actually transition (if we got here)
    $election->update(['status' => 'voting', 'is_active' => true]);
    
    return back()->with('success', 'Voting started');
}

private function allCandidatesApproved(Election $election): bool
{
    // Brittle method that's hard to maintain
    return $election->candidates()
        ->where('approved_at', '!=', null)
        ->count() === $election->candidates()->count();
}
```

**Problems:**
- Validation logic scattered and fragile
- No central place to understand all requirements
- Easy to miss one check
- Hard to provide good error messages

### After (SSOT)
```php
use App\Application\Election\Facades\ElectionLifecycle;
use App\Application\Election\Services\ConstitutionalTransitionGuard;

public function transitionToVoting(Request $request, Election $election)
{
    $lifecycle = ElectionLifecycle::of($election);
    
    // ✅ Single check that validates everything
    try {
        $guard = app(ConstitutionalTransitionGuard::class);
        $guard->assertAllowed($election, 'open_voting', $lifecycle);
    } catch (\App\Exceptions\InvalidTransitionException $e) {
        return back()->withErrors(['election' => $e->getMessage()]);
    }
    
    // All checks passed - safe to proceed
    // (Actual transition happens in service/handler)
    
    return back()->with('success', 'Voting started');
}
```

**Benefits:**
- Single authoritative check (in Constitution)
- All requirements in one place (engine)
- Detailed error message from guard
- Easy to test independently
- State machine enforcement built-in

---

## Pattern 6: Repository Query Migration

### Before (Legacy)
```php
<?php

namespace App\Repositories;

class ElectionRepository
{
    // ❌ Uses deprecated 'status' field
    public function findActiveElections()
    {
        return Election::where('is_active', true)
            ->where('status', 'voting')
            ->get();
    }
    
    // ❌ Multiple queries for same logical concept
    public function findCompletedElections()
    {
        return Election::where('is_active', false)
            ->where('status', 'completed')
            ->get();
    }
    
    public function findArchivedElections()
    {
        return Election::where('archived_at', '!=', null)->get();
    }
}
```

**Problems:**
- Legacy field usage not guarded
- Multiple ways to check same state
- Will silently bypass SSOT during Phase 3

### After (SSOT)
```php
<?php

namespace App\Repositories;

use App\Application\Election\Deprecation\QueryPolicyGuard;
use App\Models\Election;

class ElectionRepository
{
    public function __construct(
        private readonly QueryPolicyGuard $guard
    ) {}
    
    // ✅ Uses new 'state' field with guard
    public function findByState(string $state)
    {
        // Guard prevents deprecated fields in queries
        $this->guard->assertAllowedQuery(
            ['state' => $state],
            'ElectionRepository::findByState'
        );
        
        return Election::where('state', $state)->get();
    }
    
    // ✅ Semantic method names (not state names)
    public function findActiveVotingElections()
    {
        return $this->findByState('voting_active');
    }
    
    public function findCompletedElections()
    {
        return $this->findByState('results_published');
    }
    
    public function findArchivedElections()
    {
        return $this->findByState('archived');
    }
}
```

**Benefits:**
- Guard prevents deprecated field usage
- Single place for state queries
- Semantic method names
- State field used consistently
- Safe from SSOT corruption

---

## Pattern 7: API Response with State Information

### Before (Legacy)
```php
// API Controller
public function show(Election $election)
{
    return response()->json([
        'id' => $election->id,
        'name' => $election->name,
        'status' => $election->status,           // ❌ Legacy field
        'is_active' => $election->is_active,     // ❌ Legacy field
        'voting_starts_at' => $election->voting_starts_at,
        'voting_ends_at' => $election->voting_ends_at,
    ]);
}
```

**Problems:**
- Exposes internal database field names
- Not self-documenting (what does `is_active` mean?)
- Client might misuse legacy fields

### After (SSOT)
```php
use App\Application\Election\Facades\ElectionLifecycle;

// API Controller
public function show(Election $election)
{
    $snapshot = ElectionLifecycle::of($election)->snapshot();
    
    return response()->json([
        'id' => $election->id,
        'name' => $election->name,
        // ✅ Expose computed state, not raw fields
        'state' => [
            'current' => $snapshot->state->value,
            'label' => $snapshot->state->label(),
        ],
        // ✅ Expose capabilities, not raw fields
        'capabilities' => [
            'can_vote' => $snapshot->canVote,
            'can_edit' => $snapshot->canEdit,
            'can_manage_voters' => $snapshot->canManageVoters,
            'can_publish_results' => $snapshot->canPublishResults,
        ],
        // ✅ Expose actions allowed
        'allowed_actions' => $snapshot->allowedActions,
        // ✅ Metadata for UI
        'blocked_reason' => $snapshot->blockedReason,
    ]);
}
```

**Benefits:**
- API exposes computed state (not raw fields)
- Clear intent: "can_vote" instead of "is_active"
- Client can build conditional UI based on capabilities
- Self-documenting API

---

## Pattern 8: Testing Pattern (TDD)

### RED Test (Test First)
```php
<?php

namespace Tests\Feature;

use App\Application\Election\Facades\ElectionLifecycle;
use App\Models\Election;
use Tests\TestCase;

class VoteSubmissionTest extends TestCase
{
    public function test_cannot_vote_before_voting_window()
    {
        $election = Election::factory()->create([
            'state' => 'ready_for_voting',
            'voting_starts_at' => now()->addDay(),
            'voting_ends_at' => now()->addDays(2),
        ]);
        
        // ✅ Test the behavior, not implementation details
        $this->assertFalse(
            ElectionLifecycle::of($election)->canVote(),
            'Should not allow voting before window opens'
        );
    }
    
    public function test_can_vote_during_window()
    {
        $election = Election::factory()->create([
            'state' => 'voting_active',
            'voting_starts_at' => now()->subHour(),
            'voting_ends_at' => now()->addHour(),
        ]);
        
        $this->assertTrue(
            ElectionLifecycle::of($election)->canVote(),
            'Should allow voting during open window'
        );
    }
    
    public function test_cannot_vote_after_window()
    {
        $election = Election::factory()->create([
            'state' => 'voting_active',
            'voting_starts_at' => now()->subDays(2),
            'voting_ends_at' => now()->subDay(),
        ]);
        
        // ❌ This should fail until we implement the logic
        $this->assertFalse(
            ElectionLifecycle::of($election)->canVote(),
            'Should not allow voting after window closes'
        );
    }
}
```

### GREEN Implementation
```php
// ElectionLifecycleEngineImpl.php

public function compute(Election $election): ElectionLifecycleSnapshot
{
    $state = $this->deriveState($election);
    
    // ✅ Check voting window in addition to state
    $canVote = $state === ElectionLifecycleState::VotingActive 
        && $this->isVotingWindowOpenNow($election);
    
    return new ElectionLifecycleSnapshot(
        state: $state,
        canVote: $canVote,
        // ... other properties
    );
}

private function isVotingWindowOpenNow(Election $election): bool
{
    $now = now();
    return $election->voting_starts_at <= $now 
        && $now <= $election->voting_ends_at;
}
```

**Benefits:**
- Tests validate behavior, not implementation
- Single source of truth (engine) gets tested
- Can refactor implementation without changing tests
- Comprehensive coverage of edge cases

---

## Pattern 9: Gradual Migration in Large Codebase

When you have many controllers and want to migrate gradually:

### Step 1: Create adapter method in controller base class
```php
<?php

namespace App\Http\Controllers;

use App\Application\Election\Facades\ElectionLifecycle;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;

class BaseController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    // ✅ Centralized migration helper
    protected function assertCanVote(\App\Models\Election $election)
    {
        if (!ElectionLifecycle::of($election)->canVote()) {
            abort(403, 'Voting not allowed');
        }
    }
    
    protected function assertCanEdit(\App\Models\Election $election)
    {
        if (!ElectionLifecycle::of($election)->canEdit()) {
            abort(403, 'Cannot edit election');
        }
    }
}
```

### Step 2: Migrate controllers one by one
```php
class VoteController extends BaseController
{
    public function submit(Request $request)
    {
        $election = Election::findOrFail($request->election_id);
        
        // ✅ Use helper method (easy refactor later)
        $this->assertCanVote($election);
        
        // ... rest of logic
    }
}
```

### Step 3: Later, replace helpers with direct calls
```php
class VoteController extends BaseController
{
    public function submit(Request $request)
    {
        $election = Election::findOrFail($request->election_id);
        
        // ✅ Direct call when comfortable
        if (!ElectionLifecycle::of($election)->canVote()) {
            abort(403, 'Voting not allowed');
        }
        
        // ... rest of logic
    }
}
```

**Benefits:**
- Centralized entry point for migration
- Easy to track progress (search for remaining helper calls)
- Can rollback easily if needed
- Gives team time to understand pattern

---

## Summary

| Concept | Before | After |
|---------|--------|-------|
| **Permission Check** | `$election->status === 'voting' && $election->is_active` | `$lifecycle->canVote()` |
| **State Info** | Multiple fields to interpret | Single `$snapshot->state` |
| **Error Messages** | Generic "not allowed" | `$lifecycle->blockedReason()` |
| **UI Rendering** | Multiple `@if` conditions | Single `@if($lifecycle->canVote())` |
| **Testing** | Hard to test scattered logic | Test snapshot computation |
| **Maintenance** | Changes need updates everywhere | One place to change (engine) |

See [MIGRATION_GUIDE.md](MIGRATION_GUIDE.md) for step-by-step instructions on implementing these patterns.

# Code Recipes — Copy-Paste Examples

## Quick Starts

### Recipe 1: Get Current Election State

```php
use App\Application\Election\Facades\ElectionLifecycle;

$state = ElectionLifecycle::of($election)->state();
echo $state->value;    // 'voting_active'
echo $state->label();  // 'Voting Active'
```

---

### Recipe 2: Check If Voting Is Allowed

```php
use App\Application\Election\Facades\ElectionLifecycle;

if (ElectionLifecycle::of($election)->canVote()) {
    // Show voting ballot
    return Inertia::render('Voting/Ballot');
} else {
    // Show unavailable message
    return Inertia::render('Voting/Unavailable', [
        'reason' => ElectionLifecycle::of($election)->blockedReason(),
    ]);
}
```

---

### Recipe 3: Show Conditional Buttons in Vue

```vue
<template>
  <div class="election-actions">
    <button 
      v-if="stateMachine.allowedActions.includes('open_voting')"
      @click="openVoting"
      class="btn btn-success"
    >
      🚀 Open Voting
    </button>
    
    <button 
      v-if="stateMachine.allowedActions.includes('close_voting')"
      @click="closeVoting"
      class="btn btn-warning"
    >
      🛑 Close Voting
    </button>
    
    <button 
      v-if="stateMachine.allowedActions.includes('publish_results')"
      @click="publishResults"
      class="btn btn-primary"
    >
      📊 Publish Results
    </button>
    
    <div v-if="stateMachine.blockedReason" class="alert alert-warning">
      ⚠️ {{ stateMachine.blockedReason }}
    </div>
  </div>
</template>

<script setup>
defineProps({
  stateMachine: {
    type: Object,
    required: true
  }
});

const emit = defineEmits(['action']);

const openVoting = () => emit('action', 'open_voting');
const closeVoting = () => emit('action', 'close_voting');
const publishResults = () => emit('action', 'publish_results');
</script>
```

---

## Controller Recipes

### Recipe 4: Safe State Transition with Error Handling

```php
// app/Http/Controllers/Election/ElectionManagementController.php

use App\Domain\Election\StateMachine\Transition;
use App\Domain\Election\Exceptions\InvalidTransitionException;
use Illuminate\Http\RedirectResponse;

public function openVoting(Election $election): RedirectResponse
{
    // 1. Authorization (done by route middleware)
    $this->authorize('manageSettings', $election);
    
    // 2. Check if allowed
    $lifecycle = ElectionLifecycle::of($election);
    if (!$lifecycle->canTransitionTo('open_voting')) {
        return back()->with(
            'error',
            $lifecycle->blockedReason() ?? 'Cannot open voting now'
        );
    }
    
    // 3. Execute transition
    try {
        $election->transitionTo(
            Transition::manual(
                action: 'open_voting',
                actorId: auth()->id(),
                reason: 'Opened by election officer',
                metadata: ['ip' => request()->ip()]
            )
        );
        
        return back()->with('success', '✅ Voting has been opened');
        
    } catch (InvalidTransitionException $e) {
        Log::warning('Open voting failed', [
            'election_id' => $election->id,
            'user_id' => auth()->id(),
            'error' => $e->getMessage(),
        ]);
        return back()->with('error', 'Transition failed: ' . $e->getMessage());
    }
}
```

---

### Recipe 5: Batch Process Elections by State

```php
// app/Console/Commands/ProcessElectionsByState.php

use App\Application\Election\Facades\ElectionLifecycle;
use App\Domain\Election\StateMachine\Transition;

class ProcessElectionsByState extends Command
{
    public function handle()
    {
        Election::all()->each(function ($election) {
            $state = ElectionLifecycle::of($election)->state()->value;
            
            match ($state) {
                'draft' => $this->processNeedingApproval($election),
                'ready_for_voting' => $this->processPendingVoting($election),
                'counting' => $this->processCounting($election),
                'results_published' => $this->processArchive($election),
                default => null,
            };
        });
    }
    
    private function processNeedingApproval(Election $election)
    {
        if ($election->created_at->daysAgo > 7) {
            $this->comment("Election {$election->id} waiting 7+ days");
        }
    }
    
    private function processPendingVoting(Election $election)
    {
        if (now()->greaterThan($election->voting_starts_at)) {
            try {
                $election->transitionTo(
                    Transition::auto('open_voting', 'Auto-opened at scheduled time')
                );
                $this->line("Opened voting for {$election->id}");
            } catch (Throwable $e) {
                $this->error("Failed to open {$election->id}: {$e->getMessage()}");
            }
        }
    }
    
    private function processCounting(Election $election)
    {
        // Prepare results
    }
    
    private function processArchive(Election $election)
    {
        // Archive old elections
    }
}
```

---

### Recipe 6: Query Elections by State

```php
// app/Repositories/ElectionRepository.php

use App\Application\Election\Facades\ElectionLifecycle;

public function getActiveElections()
{
    return Election::all()
        ->filter(fn ($e) => ElectionLifecycle::of($e)->isActive())
        ->values();
}

public function getElectionsByState($targetState)
{
    return Election::all()
        ->filter(fn ($e) => ElectionLifecycle::of($e)->state()->value === $targetState)
        ->values();
}

public function getDashboardStats()
{
    $elections = Election::all();
    
    return [
        'total' => $elections->count(),
        'voting_active' => $elections
            ->filter(fn ($e) => ElectionLifecycle::of($e)->isActive())
            ->count(),
        'pending_approval' => $elections
            ->filter(fn ($e) => 
                ElectionLifecycle::of($e)->state()->value === 'submitted_for_approval'
            )
            ->count(),
        'completed' => $elections
            ->filter(fn ($e) => 
                ElectionLifecycle::of($e)->state()->value === 'results_published'
            )
            ->count(),
    ];
}
```

---

## Test Recipes

### Recipe 7: TDD Pattern for State Transitions

```php
// tests/Feature/Election/VotingStateTransitionTest.php

use Tests\TestCase;
use Tests\Support\ElectionScenarioFactory;
use App\Application\Election\Facades\ElectionLifecycle;
use App\Domain\Election\StateMachine\Transition;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VotingStateTransitionTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_can_transition_from_ready_to_voting()
    {
        // 1️⃣ RED: Create scenario with constitutional facts
        $election = ElectionScenarioFactory::configurationComplete();
        
        // 2️⃣ Verify initial state (derived from facts)
        $this->assertEquals(
            'ready_for_voting',
            ElectionLifecycle::of($election)->state()->value
        );
        
        // 3️⃣ Verify action is allowed
        $this->assertTrue(
            ElectionLifecycle::of($election)->canTransitionTo('open_voting')
        );
        
        // 4️⃣ GREEN: Execute transition
        $officer = User::factory()->create();
        $this->actingAs($officer);
        
        $election->transitionTo(
            Transition::manual('open_voting', $officer->id, 'Opened for testing')
        );
        
        // 5️⃣ VERIFY: New state
        $this->assertEquals(
            'voting_active',
            ElectionLifecycle::of($election->fresh())->state()->value
        );
        
        // 6️⃣ VERIFY: Side effects
        $this->assertTrue($election->fresh()->voting_locked);
        $this->assertNotNull($election->fresh()->voting_locked_at);
        $this->assertEquals($officer->id, $election->fresh()->voting_locked_by);
    }
    
    public function test_cannot_transition_with_pending_candidates()
    {
        // Arrange: Create election with pending candidacies
        $election = ElectionScenarioFactory::configurationComplete();
        $election->update(['pending_candidacies_count' => 3]);
        
        // Act & Assert: Transition should fail
        $this->expectException(InvalidTransitionException::class);
        
        $election->transitionTo(
            Transition::manual('open_voting', 1, 'Should fail')
        );
    }
}
```

---

### Recipe 8: Test State Derivation Logic

```php
// tests/Unit/Election/ElectionLifecycleTest.php

use Tests\TestCase;
use App\Models\Election;
use App\Application\Election\Facades\ElectionLifecycle;

class ElectionLifecycleTest extends TestCase
{
    public function test_draft_election_state()
    {
        $election = Election::factory()->create();
        
        $this->assertEquals(
            'draft',
            ElectionLifecycle::of($election)->state()->value
        );
    }
    
    public function test_approved_election_state()
    {
        $election = Election::factory()->create([
            'approved_at' => now()->subDay(),
        ]);
        
        $this->assertEquals(
            'approved',
            ElectionLifecycle::of($election)->state()->value
        );
    }
    
    public function test_voting_active_when_in_voting_window()
    {
        $election = Election::factory()->create([
            'approved_at' => now()->subDays(2),
            'administration_completed' => true,
            'nomination_completed' => true,
            'voting_starts_at' => now()->subHour(),
            'voting_ends_at' => now()->addHour(),
            'voting_locked' => true,
        ]);
        
        $this->assertEquals(
            'voting_active',
            ElectionLifecycle::of($election)->state()->value
        );
    }
    
    public function test_counting_when_voting_ended()
    {
        $election = Election::factory()->create([
            'approved_at' => now()->subDays(3),
            'administration_completed' => true,
            'nomination_completed' => true,
            'voting_starts_at' => now()->subDay(),
            'voting_ends_at' => now()->subMinute(),
            'voting_locked' => true,
            'results_published_at' => null,
        ]);
        
        $this->assertEquals(
            'counting',
            ElectionLifecycle::of($election)->state()->value
        );
    }
    
    public function test_capability_checks()
    {
        $electionDraft = Election::factory()->create();
        $this->assertTrue(ElectionLifecycle::of($electionDraft)->canEdit());
        $this->assertFalse(ElectionLifecycle::of($electionDraft)->canVote());
        
        $electionVoting = Election::factory()->create([
            'approved_at' => now()->subDay(),
            'voting_starts_at' => now()->subHour(),
            'voting_ends_at' => now()->addHour(),
            'voting_locked' => true,
        ]);
        $this->assertFalse(ElectionLifecycle::of($electionVoting)->canEdit());
        $this->assertTrue(ElectionLifecycle::of($electionVoting)->canVote());
    }
}
```

---

## Policy Recipes

### Recipe 9: Authorization Based on State

```php
// app/Policies/ElectionPolicy.php

use App\Application\Election\Facades\ElectionLifecycle;
use App\Models\User;
use App\Models\Election;
use App\Models\ElectionOfficer;

class ElectionPolicy
{
    public function manageSettings(User $user, Election $election): bool
    {
        // Must be an officer in this election
        $isOfficer = ElectionOfficer::where([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'status' => 'active',
        ])->exists();
        
        if (!$isOfficer) {
            return false;
        }
        
        // Can only manage if election is editable
        return ElectionLifecycle::of($election)->canEdit();
    }
    
    public function submitForApproval(User $user, Election $election): bool
    {
        // Only chief can submit
        $isChief = ElectionOfficer::where([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'role' => 'chief',
        ])->exists();
        
        return $isChief && ElectionLifecycle::of($election)->state()->value === 'draft';
    }
    
    public function openVoting(User $user, Election $election): bool
    {
        // Chief or deputy can open
        $hasRole = ElectionOfficer::where([
            'user_id' => $user->id,
            'election_id' => $election->id,
        ])->whereIn('role', ['chief', 'deputy'])->exists();
        
        return $hasRole && ElectionLifecycle::of($election)->canTransitionTo('open_voting');
    }
    
    public function publishResults(User $user, Election $election): bool
    {
        // Only chief can publish
        $isChief = ElectionOfficer::where([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'role' => 'chief',
        ])->exists();
        
        return $isChief && ElectionLifecycle::of($election)->canPublishResults();
    }
}
```

---

## View Recipes

### Recipe 10: Responsive UI Based on State

```vue
<!-- resources/js/Pages/Election/Management.vue -->

<template>
  <div class="election-management">
    <!-- State Badge -->
    <div class="state-badge" :class="`state-${stateMachine.currentState}`">
      {{ formatState(stateMachine.currentState) }}
    </div>
    
    <!-- Capability Sections -->
    <section v-if="canVote" class="voting-section">
      <h2>Voting</h2>
      <button 
        v-if="canTransition('open_voting')"
        @click="openVoting"
      >
        Open Voting
      </button>
    </section>
    
    <section v-if="canEdit" class="edit-section">
      <h2>Configuration</h2>
      <!-- Edit forms -->
    </section>
    
    <section v-if="canPublishResults" class="results-section">
      <h2>Results</h2>
      <button @click="publishResults">
        Publish Results
      </button>
    </section>
    
    <!-- Blocked Reason -->
    <div v-if="blockedReason" class="alert alert-warning">
      {{ blockedReason }}
    </div>
    
    <!-- Allowed Actions List -->
    <details class="debug-info">
      <summary>Debug: Allowed Actions</summary>
      <ul>
        <li v-for="action in allowedActions" :key="action">
          {{ action }}
        </li>
      </ul>
    </details>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();

const stateMachine = computed(() => page.props.stateMachine);
const canVote = computed(() => stateMachine.value.canVote);
const canEdit = computed(() => stateMachine.value.canEdit);
const canPublishResults = computed(() => stateMachine.value.canPublishResults);
const allowedActions = computed(() => stateMachine.value.allowedActions);
const blockedReason = computed(() => stateMachine.value.blockedReason);

const formatState = (state) => {
  return state
    .split('_')
    .map(word => word.charAt(0).toUpperCase() + word.slice(1))
    .join(' ');
};

const canTransition = (action) => allowedActions.value.includes(action);

const openVoting = () => {
  // Post to /elections/{id}/open-voting
};

const publishResults = () => {
  // Post to /elections/{id}/publish-results
};
</script>

<style scoped>
.state-badge {
  padding: 8px 16px;
  border-radius: 4px;
  font-weight: bold;
}

.state-badge.state-draft { background: #e5e7eb; }
.state-badge.state-submitted_for_approval { background: #fef3c7; }
.state-badge.state-approved { background: #dbeafe; }
.state-badge.state-setup { background: #c7d2fe; }
.state-badge.state-ready_for_voting { background: #bfdbfe; }
.state-badge.state-voting_active { background: #dcfce7; }
.state-badge.state-counting { background: #fed7aa; }
.state-badge.state-results_published { background: #fed7aa; }
</style>
```

---

## Utility Recipes

### Recipe 11: State Helper Function

```php
// app/Helpers/ElectionStateHelper.php

namespace App\Helpers;

use App\Application\Election\Facades\ElectionLifecycle;
use App\Models\Election;

class ElectionStateHelper
{
    public static function getStateLabel(Election $election): string
    {
        return ElectionLifecycle::of($election)
            ->state()
            ->label();
    }
    
    public static function getStateColor(Election $election): string
    {
        return match (ElectionLifecycle::of($election)->state()->value) {
            'draft' => 'gray',
            'submitted_for_approval' => 'yellow',
            'approved' => 'blue',
            'setup' => 'blue',
            'ready_for_voting' => 'cyan',
            'voting_active' => 'green',
            'counting' => 'orange',
            'results_published' => 'orange',
            'archived' => 'gray',
            default => 'slate',
        };
    }
    
    public static function canProceed(Election $election): bool
    {
        $lifecycle = ElectionLifecycle::of($election);
        return !$lifecycle->isTerminal() 
            && !$lifecycle->blockedReason();
    }
}

// Usage
use App\Helpers\ElectionStateHelper;

$label = ElectionStateHelper::getStateLabel($election);
$color = ElectionStateHelper::getStateColor($election);
$canProceed = ElectionStateHelper::canProceed($election);
```

---

### Recipe 12: State Transition Logger

```php
// app/Logging/ElectionStateLogger.php

namespace App\Logging;

use App\Models\Election;
use App\Application\Election\Facades\ElectionLifecycle;
use Illuminate\Support\Facades\Log;

class ElectionStateLogger
{
    public static function logStateChange(Election $election, string $action): void
    {
        $lifecycle = ElectionLifecycle::of($election);
        
        Log::info('Election state change', [
            'election_id' => $election->id,
            'election_name' => $election->name,
            'action' => $action,
            'new_state' => $lifecycle->state()->value,
            'allowed_actions' => $lifecycle->allowedActions(),
            'timestamp' => now()->toIso8601String(),
        ]);
    }
    
    public static function logStateCheck(Election $election, string $reason): void
    {
        $lifecycle = ElectionLifecycle::of($election);
        
        Log::debug('Election state check', [
            'election_id' => $election->id,
            'reason' => $reason,
            'current_state' => $lifecycle->state()->value,
            'can_vote' => $lifecycle->canVote(),
            'can_edit' => $lifecycle->canEdit(),
            'blocked_reason' => $lifecycle->blockedReason(),
        ]);
    }
}

// Usage
use App\Logging\ElectionStateLogger;

ElectionStateLogger::logStateChange($election, 'open_voting');
ElectionStateLogger::logStateCheck($election, 'Before accepting ballot');
```

---

## Performance Recipes

### Recipe 13: Efficient Bulk State Queries

```php
// ✅ GOOD: Derive state in PHP for multiple elections
public function getElectionStats()
{
    $elections = Election::with('memberships', 'posts')->get();
    
    $stats = [
        'draft' => 0,
        'voting_active' => 0,
        'results_published' => 0,
    ];
    
    foreach ($elections as $election) {
        $state = ElectionLifecycle::of($election)->state()->value;
        $stats[$state] = ($stats[$state] ?? 0) + 1;
    }
    
    return $stats;
}

// ❌ AVOID: Querying by state column (it's a cache, not SSOT)
// Don't do: Election::where('state', 'voting_active')->count();
```

---

### Recipe 14: Cache Derived States

```php
// app/Services/ElectionStateCache.php

namespace App\Services;

use App\Models\Election;
use App\Application\Election\Facades\ElectionLifecycle;
use Illuminate\Support\Facades\Cache;

class ElectionStateCache
{
    private const CACHE_TTL = 3600; // 1 hour
    
    public static function getState(Election $election)
    {
        return Cache::remember(
            "election_state:{$election->id}",
            self::CACHE_TTL,
            fn () => ElectionLifecycle::of($election)->state()->value
        );
    }
    
    public static function invalidate(Election $election)
    {
        Cache::forget("election_state:{$election->id}");
    }
}

// Usage
use App\Services\ElectionStateCache;

// Get cached state
$state = ElectionStateCache::getState($election);

// Invalidate when facts change
$election->update(['voting_starts_at' => now()]);
ElectionStateCache::invalidate($election);
```

---

**Last Updated:** May 21, 2026
**Examples Status:** Production-Ready

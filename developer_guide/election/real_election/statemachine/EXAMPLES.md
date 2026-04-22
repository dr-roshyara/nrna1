# Code Examples

## Common Scenarios

### Scenario 1: Check Current Election Phase

```php
// In Controller
public function show(Election $election)
{
    $state = $election->current_state;
    $stateInfo = $election->state_info;
    
    return view('election.show', [
        'election' => $election,
        'state' => $state,
        'stateInfo' => $stateInfo,
        'canVote' => $election->allowsAction('cast_vote'),
    ]);
}
```

### Scenario 2: Complete Administration Phase

```php
// In Controller
public function completeAdministration(Request $request, Election $election)
{
    $this->authorize('manage', $election);
    
    // Validate prerequisites
    $postsCount = $election->posts()->count();
    $votersCount = $election->electionMemberships()
        ->where('role', 'voter')
        ->where('status', 'active')
        ->count();
    
    if ($postsCount === 0 || $votersCount === 0) {
        return back()->withErrors([
            'error' => 'Cannot complete: need at least 1 post and 1 voter'
        ]);
    }
    
    try {
        $election->completeAdministration(
            reason: 'Manual completion by ' . auth()->user()->name,
            actorId: auth()->id()
        );
        
        return back()->with('success', 'Administration phase completed. Nomination phase begins.');
    } catch (DomainException $e) {
        return back()->withErrors(['error' => $e->getMessage()]);
    }
}
```

### Scenario 3: Protect Routes by State

```php
// In routes/organisations.php
Route::middleware(['election.state:manage_posts'])->group(function () {
    Route::post('/posts', [PostController::class, 'store'])
        ->name('organisations.elections.posts.store');
    Route::patch('/posts/{post}', [PostController::class, 'update'])
        ->name('organisations.elections.posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])
        ->name('organisations.elections.posts.destroy');
});

// In middleware (automatic authorization)
public function handle(Request $request, Closure $next, string $operation): mixed
{
    $election = $request->route('election');
    
    if (!$election->allowsAction($operation)) {
        abort(403, sprintf(
            'Cannot %s during %s phase',
            $operation,
            $election->state_info['name']
        ));
    }
    
    return $next($request);
}
```

### Scenario 4: Complex State-Based Logic

```php
// In Service
public function getElectionDashboard(Election $election, User $user): array
{
    $state = $election->current_state;
    
    $dashboard = [
        'election' => $election,
        'state' => $election->state_info,
    ];
    
    switch ($state) {
        case 'administration':
            $dashboard['tasks'] = [
                'posts_count' => $election->posts()->count(),
                'voters_count' => $election->electionMemberships()->count(),
                'committee_count' => $election->officers()->count(),
            ];
            $dashboard['action'] = 'Complete Administration';
            $dashboard['action_url'] = route('complete-administration', $election);
            break;
            
        case 'nomination':
            $dashboard['tasks'] = [
                'pending_candidates' => $election->candidacies()
                    ->where('status', 'pending')
                    ->count(),
                'approved_candidates' => $election->candidacies()
                    ->where('status', 'approved')
                    ->count(),
            ];
            $dashboard['action'] = 'Complete Nomination';
            $dashboard['action_url'] = route('complete-nomination', $election);
            break;
            
        case 'voting':
            $now = now();
            $timeRemaining = $election->voting_ends_at->diffInMinutes($now);
            $dashboard['voting_status'] = [
                'starts_at' => $election->voting_starts_at,
                'ends_at' => $election->voting_ends_at,
                'minutes_remaining' => max(0, $timeRemaining),
                'is_active' => $now->between(
                    $election->voting_starts_at,
                    $election->voting_ends_at
                ),
            ];
            break;
            
        case 'results_pending':
            $dashboard['action'] = 'Publish Results';
            $dashboard['action_url'] = route('publish-results', $election);
            $dashboard['can_verify'] = true;
            break;
            
        case 'results':
            $dashboard['results_published_at'] = $election->results_published_at;
            $dashboard['can_verify'] = true;
            break;
    }
    
    return $dashboard;
}
```

### Scenario 5: Vue Component Integration

```vue
<!-- In Management.vue -->
<template>
  <div class="election-management">
    <StateMachinePanel
      v-if="stateMachine"
      :state-machine="stateMachine"
      :election="election"
      :organisation="organisation"
      @phase-completed="handlePhaseCompleted"
      @dates-updated="handleDatesUpdated"
    />
    
    <!-- Phase-specific content -->
    <div v-if="currentState === 'administration'" class="phase-content">
      <h2>Set Up Your Election</h2>
      <PostManagement :election="election" />
      <VoterManagement :election="election" />
      <button @click="completeAdministration">Complete Setup</button>
    </div>
    
    <div v-else-if="currentState === 'nomination'" class="phase-content">
      <h2>Accept Candidates</h2>
      <CandidacyReview :election="election" />
      <button @click="completeNomination">Begin Voting</button>
    </div>
    
    <div v-else-if="currentState === 'voting'" class="phase-content">
      <h2>Voting in Progress</h2>
      <VotingStatus :election="election" />
      <p>Voting closes on {{ formatDate(election.voting_ends_at) }}</p>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import StateMachinePanel from './Partials/StateMachinePanel.vue'

const props = defineProps({
  election: Object,
  stateMachine: Object,
  organisation: Object,
})

const currentState = computed(() => props.stateMachine?.currentState)

const completeAdministration = () => {
  router.post(route('elections.complete-administration', props.election))
}

const completeNomination = () => {
  router.post(route('elections.complete-nomination', props.election))
}
</script>
```

### Scenario 6: Query Elections by Phase

```php
// In Repository or Service
class ElectionRepository
{
    // Get all elections in administration
    public function getAdministrationElections()
    {
        return Election::where('administration_completed', false)
            ->where('nomination_suggested_start', null)
            ->orderBy('created_at', 'desc')
            ->get();
    }
    
    // Get all elections in voting window
    public function getVotingElections()
    {
        $now = now();
        return Election::where('voting_starts_at', '<=', $now)
            ->where('voting_ends_at', '>=', $now)
            ->get();
    }
    
    // Get all elections pending result publication
    public function getResultsPendingElections()
    {
        return Election::where('voting_ends_at', '<', now())
            ->whereNull('results_published_at')
            ->get();
    }
    
    // Get elections due for grace period auto-transition
    public function getGracePeriodElections($days = 7)
    {
        $gracePeriodDate = now()->subDays($days);
        
        return [
            'administration' => Election::where('allow_auto_transition', true)
                ->where('administration_completed', false)
                ->where('administration_suggested_end', '<', $gracePeriodDate)
                ->get(),
            'nomination' => Election::where('allow_auto_transition', true)
                ->where('nomination_completed', false)
                ->where('nomination_suggested_end', '<', $gracePeriodDate)
                ->get(),
        ];
    }
}
```

### Scenario 7: Artisan Command for Grace Period

```php
// In app/Console/Commands/ProcessElectionGracePeriods.php
namespace App\Console\Commands;

use App\Models\Election;
use Illuminate\Console\Command;

class ProcessElectionGracePeriods extends Command
{
    protected $signature = 'elections:process-grace-periods';
    protected $description = 'Auto-transition elections past their grace period';
    
    public function handle()
    {
        $graceDays = 7;
        $gracePeriodDate = now()->subDays($graceDays);
        
        // Administration phase
        $adminElections = Election::where('allow_auto_transition', true)
            ->where('administration_completed', false)
            ->where('administration_suggested_end', '<', $gracePeriodDate)
            ->get();
        
        foreach ($adminElections as $election) {
            try {
                $election->completeAdministration(
                    reason: 'Auto-transition via grace period',
                    actorId: 1  // System user
                );
                $this->info("✓ {$election->name}: Administration → Nomination");
            } catch (Exception $e) {
                $this->error("✗ {$election->name}: {$e->getMessage()}");
            }
        }
        
        // Nomination phase
        $nomElections = Election::where('allow_auto_transition', true)
            ->where('nomination_completed', false)
            ->where('nomination_suggested_end', '<', $gracePeriodDate)
            ->get();
        
        foreach ($nomElections as $election) {
            try {
                $election->forceCloseNomination(
                    reason: 'Auto-transition via grace period',
                    actorId: 1  // System user
                );
                $this->info("✓ {$election->name}: Nomination → Voting");
            } catch (Exception $e) {
                $this->error("✗ {$election->name}: {$e->getMessage()}");
            }
        }
        
        $this->line('Grace period processing complete');
    }
}

// In app/Console/Kernel.php
protected function schedule(Schedule $schedule)
{
    $schedule->command('elections:process-grace-periods')
        ->daily()
        ->at('02:00');  // Run at 2 AM daily
}
```

### Scenario 8: Testing State Transitions

```php
// In tests/Feature/ElectionStateMachineTest.php
namespace Tests\Feature;

use App\Models\Election;
use Tests\TestCase;

class ElectionStateMachineTest extends TestCase
{
    public function test_fresh_election_defaults_to_administration_state()
    {
        $election = Election::factory()->create();
        
        $this->assertEqual('administration', $election->current_state);
    }
    
    public function test_state_transitions_to_nomination_after_complete_administration()
    {
        $election = Election::factory()
            ->has(Post::factory(2))
            ->has(ElectionMembership::factory(5))
            ->create();
        
        $election->completeAdministration('Test', auth()->id());
        
        $this->assertEqual('nomination', $election->fresh()->current_state);
        $this->assertNotNull($election->administration_completed_at);
        $this->assertTrue($election->administration_completed);
    }
    
    public function test_state_is_voting_when_within_voting_window()
    {
        $election = Election::factory()
            ->create([
                'voting_starts_at' => now()->subHour(),
                'voting_ends_at' => now()->addHour(),
                'nomination_completed' => true,
            ]);
        
        $this->assertEqual('voting', $election->current_state);
    }
}
```

---

## Common Error Handling

```php
// Catch specific exceptions
try {
    $election->completeAdministration($reason, $actorId);
} catch (\App\Exceptions\InvalidHierarchyException $e) {
    // Timeline validation failed
    return back()->withErrors(['timeline' => $e->getMessage()]);
} catch (\App\Exceptions\DomainException $e) {
    // General domain error
    return back()->withErrors(['error' => $e->getMessage()]);
}
```

---

See [README.md](README.md) for overview, [ARCHITECTURE.md](ARCHITECTURE.md) for patterns, [MODELS.md](MODELS.md) for method details.

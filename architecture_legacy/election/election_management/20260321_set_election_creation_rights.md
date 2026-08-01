## 🎯 **Feature: Activate Planned Election (Chief Only)**

---

## 📋 **Feature Overview**

When an owner/admin creates an election (status = 'planned'), the election chief should:

1. **Receive email notification** that an election is ready for activation
2. **Log in to PublicDigit** and view the election in management dashboard
3. **Click "Activate Election" button** to change status from 'planned' → 'active'
4. **Start voting period**

---

## 🔄 **Flow Diagram**

```
Owner/Admin creates election
    ↓
Election status = 'planned'
    ↓
System detects active chief(s) for organisation
    ↓
Send email notification to chief(s)
    ↓
Chief logs in to PublicDigit
    ↓
Navigates to Election Management Dashboard
    ↓
Sees "Activate Election" button (only for planned elections)
    ↓
Clicks button → Confirmation dialog
    ↓
Election status changes to 'active'
    ↓
Voting period begins
```

---

## 🧪 **TDD First: Write Tests**

### **File:** `tests/Feature/Election/ElectionActivationTest.php`

```php
<?php

namespace Tests\Feature\Election;

use App\Models\Election;
use App\Models\ElectionOfficer;
use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;
use App\Notifications\ElectionReadyForActivation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Tests\TestCase;

class ElectionActivationTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private Election $election;
    private User $chief;
    private User $deputy;
    private User $commissioner;
    private User $owner;

    protected function setUp(): void
    {
        parent::setUp();

        $this->org = Organisation::factory()->create(['type' => 'tenant']);
        session(['current_organisation_id' => $this->org->id]);

        $this->owner = $this->createUserWithRole('owner');
        $this->chief = $this->createOfficer('chief', 'active');
        $this->deputy = $this->createOfficer('deputy', 'active');
        $this->commissioner = $this->createOfficer('commissioner', 'active');

        // Create a planned election
        $this->election = Election::create([
            'id' => (string) Str::uuid(),
            'organisation_id' => $this->org->id,
            'name' => 'General Election 2026',
            'slug' => 'general-election-2026',
            'type' => 'real',
            'status' => 'planned',
            'start_date' => now()->addDays(7),
            'end_date' => now()->addDays(14),
        ]);
    }

    // =========================================================================
    // Permission Tests
    // =========================================================================

    /** @test */
    public function chief_can_activate_planned_election(): void
    {
        $response = $this->actingAs($this->chief)
            ->withSession($this->orgSession())
            ->post(route('elections.activate', $this->election->id));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->election->refresh();
        $this->assertEquals('active', $this->election->status);
    }

    /** @test */
    public function deputy_can_activate_planned_election(): void
    {
        $response = $this->actingAs($this->deputy)
            ->withSession($this->orgSession())
            ->post(route('elections.activate', $this->election->id));

        $response->assertRedirect();
        $this->assertEquals('active', $this->election->fresh()->status);
    }

    /** @test */
    public function commissioner_cannot_activate_election(): void
    {
        $response = $this->actingAs($this->commissioner)
            ->withSession($this->orgSession())
            ->post(route('elections.activate', $this->election->id));

        $response->assertForbidden();
        $this->assertEquals('planned', $this->election->fresh()->status);
    }

    /** @test */
    public function cannot_activate_already_active_election(): void
    {
        $this->election->update(['status' => 'active']);

        $response = $this->actingAs($this->chief)
            ->withSession($this->orgSession())
            ->post(route('elections.activate', $this->election->id));

        $response->assertSessionHas('error', 'Cannot activate an election that is already active.');
        $this->assertEquals('active', $this->election->fresh()->status);
    }

    /** @test */
    public function cannot_activate_completed_election(): void
    {
        $this->election->update(['status' => 'completed']);

        $response = $this->actingAs($this->chief)
            ->withSession($this->orgSession())
            ->post(route('elections.activate', $this->election->id));

        $response->assertSessionHas('error', 'Cannot activate an election that is already completed.');
        $this->assertEquals('completed', $this->election->fresh()->status);
    }

    // =========================================================================
    // Email Notification Tests
    // =========================================================================

    /** @test */
    public function email_notification_sent_to_chief_when_election_created(): void
    {
        Notification::fake();

        // Create new election (triggers notification)
        $this->actingAs($this->owner)
            ->withSession($this->orgSession())
            ->post(route('organisations.elections.store', $this->org->slug), [
                'name' => 'Special Election 2026',
                'start_date' => now()->addDays(7)->toDateString(),
                'end_date' => now()->addDays(14)->toDateString(),
            ]);

        Notification::assertSentTo(
            $this->chief,
            ElectionReadyForActivation::class
        );
    }

    /** @test */
    public function email_notification_sent_to_all_active_chiefs(): void
    {
        Notification::fake();

        // Create second chief
        $secondChief = $this->createOfficer('chief', 'active');

        // Create new election
        $this->actingAs($this->owner)
            ->withSession($this->orgSession())
            ->post(route('organisations.elections.store', $this->org->slug), [
                'name' => 'Special Election 2026',
                'start_date' => now()->addDays(7)->toDateString(),
                'end_date' => now()->addDays(14)->toDateString(),
            ]);

        Notification::assertSentTo($this->chief, ElectionReadyForActivation::class);
        Notification::assertSentTo($secondChief, ElectionReadyForActivation::class);
    }

    /** @test */
    public function email_notification_not_sent_to_inactive_chiefs(): void
    {
        Notification::fake();

        // Create inactive chief
        $inactiveChief = $this->createOfficer('chief', 'inactive');

        // Create new election
        $this->actingAs($this->owner)
            ->withSession($this->orgSession())
            ->post(route('organisations.elections.store', $this->org->slug), [
                'name' => 'Special Election 2026',
                'start_date' => now()->addDays(7)->toDateString(),
                'end_date' => now()->addDays(14)->toDateString(),
            ]);

        Notification::assertSentTo($this->chief, ElectionReadyForActivation::class);
        Notification::assertNotSentTo($inactiveChief, ElectionReadyForActivation::class);
    }

    /** @test */
    public function email_notification_contains_activation_link(): void
    {
        Notification::fake();

        $this->actingAs($this->owner)
            ->withSession($this->orgSession())
            ->post(route('organisations.elections.store', $this->org->slug), [
                'name' => 'Special Election 2026',
                'start_date' => now()->addDays(7)->toDateString(),
                'end_date' => now()->addDays(14)->toDateString(),
            ]);

        $election = Election::where('name', 'Special Election 2026')->first();

        Notification::assertSentTo(
            $this->chief,
            ElectionReadyForActivation::class,
            function ($notification) use ($election) {
                $mail = $notification->toMail($this->chief);
                $expectedUrl = route('elections.management', $election->id);
                return str_contains($mail->actionUrl, $expectedUrl);
            }
        );
    }

    // =========================================================================
    // Activation Requirements Tests
    // =========================================================================

    /** @test */
    public function cannot_activate_election_without_posts(): void
    {
        // Ensure no posts exist for this election
        $this->assertEquals(0, $this->election->posts()->count());

        $response = $this->actingAs($this->chief)
            ->withSession($this->orgSession())
            ->post(route('elections.activate', $this->election->id));

        $response->assertSessionHas('error', 'Cannot activate election without at least one post.');
        $this->assertEquals('planned', $this->election->fresh()->status);
    }

    /** @test */
    public function cannot_activate_election_without_candidates(): void
    {
        // Add a post but no candidates
        $post = \App\Models\Post::create([
            'id' => (string) Str::uuid(),
            'election_id' => $this->election->id,
            'title' => 'President',
            'max_votes' => 1,
        ]);

        $this->assertEquals(0, $post->candidates()->count());

        $response = $this->actingAs($this->chief)
            ->withSession($this->orgSession())
            ->post(route('elections.activate', $this->election->id));

        $response->assertSessionHas('error', 'Cannot activate election without at least one candidate per post.');
        $this->assertEquals('planned', $this->election->fresh()->status);
    }

    /** @test */
    public function cannot_activate_election_without_voters(): void
    {
        // Add post and candidate
        $post = \App\Models\Post::create([
            'id' => (string) Str::uuid(),
            'election_id' => $this->election->id,
            'title' => 'President',
            'max_votes' => 1,
        ]);

        \App\Models\Candidate::create([
            'id' => (string) Str::uuid(),
            'post_id' => $post->id,
            'name' => 'John Doe',
        ]);

        // No voters assigned
        $this->assertEquals(0, $this->election->memberships()->count());

        $response = $this->actingAs($this->chief)
            ->withSession($this->orgSession())
            ->post(route('elections.activate', $this->election->id));

        $response->assertSessionHas('error', 'Cannot activate election without at least one eligible voter.');
        $this->assertEquals('planned', $this->election->fresh()->status);
    }

    /** @test */
    public function can_activate_election_with_posts_candidates_and_voters(): void
    {
        // Add post
        $post = \App\Models\Post::create([
            'id' => (string) Str::uuid(),
            'election_id' => $this->election->id,
            'title' => 'President',
            'max_votes' => 1,
        ]);

        // Add candidate
        \App\Models\Candidate::create([
            'id' => (string) Str::uuid(),
            'post_id' => $post->id,
            'name' => 'John Doe',
        ]);

        // Add voter
        $voter = User::factory()->create(['organisation_id' => $this->org->id]);
        \App\Models\ElectionMembership::create([
            'id' => (string) Str::uuid(),
            'user_id' => $voter->id,
            'organisation_id' => $this->org->id,
            'election_id' => $this->election->id,
            'status' => 'active',
            'assigned_by' => $this->chief->id,
            'assigned_at' => now(),
        ]);

        $response = $this->actingAs($this->chief)
            ->withSession($this->orgSession())
            ->post(route('elections.activate', $this->election->id));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Election activated successfully! Voting period is now open.');
        $this->assertEquals('active', $this->election->fresh()->status);
    }

    // =========================================================================
    // Helpers
    // =========================================================================

    private function orgSession(): array
    {
        return ['current_organisation_id' => $this->org->id];
    }

    private function createUserWithRole(string $role): User
    {
        $user = User::factory()->create([
            'organisation_id' => $this->org->id,
            'email_verified_at' => now(),
        ]);
        
        UserOrganisationRole::create([
            'id' => (string) Str::uuid(),
            'user_id' => $user->id,
            'organisation_id' => $this->org->id,
            'role' => $role,
        ]);
        
        return $user;
    }

    private function createOfficer(string $role, string $status): User
    {
        $user = User::factory()->create([
            'organisation_id' => $this->org->id,
            'email_verified_at' => now(),
        ]);
        
        UserOrganisationRole::create([
            'id' => (string) Str::uuid(),
            'user_id' => $user->id,
            'organisation_id' => $this->org->id,
            'role' => 'voter',
        ]);
        
        ElectionOfficer::create([
            'organisation_id' => $this->org->id,
            'user_id' => $user->id,
            'role' => $role,
            'status' => $status,
            'appointed_by' => $user->id,
            'appointed_at' => now(),
            'accepted_at' => $status === 'active' ? now() : null,
        ]);
        
        return $user;
    }
}
```

---

## 📧 **Step: Create Notification**

```bash
php artisan make:notification ElectionReadyForActivation
```

```php
<?php
// app/Notifications/ElectionReadyForActivation.php

namespace App\Notifications;

use App\Models\Election;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ElectionReadyForActivation extends Notification implements ShouldQueue
{
    use Queueable;

    protected Election $election;

    public function __construct(Election $election)
    {
        $this->election = $election;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $managementUrl = route('elections.management', $this->election->id);

        return (new MailMessage)
            ->subject("Election Ready for Activation: {$this->election->name}")
            ->greeting("Hello {$notifiable->name}!")
            ->line("A new election **{$this->election->name}** has been created and is ready for activation.")
            ->line("Before activating, please ensure:")
            ->line("✓ All election posts are added")
            ->line("✓ Candidates are nominated for each post")
            ->line("✓ Voters are assigned to the election")
            ->action('Review and Activate Election', $managementUrl)
            ->line("Once activated, the voting period will begin and voters can cast their ballots.")
            ->line("This election is scheduled to run from {$this->election->start_date->format('F j, Y')} to {$this->election->end_date->format('F j, Y')}.")
            ->salutation("Thank you for managing this election.");
    }
}
```

---

## 🔧 **Step: Add Activation Method to Controller**

**File:** `app/Http/Controllers/Election/ElectionManagementController.php`

```php
/**
 * Activate a planned election (chief/deputy only)
 * 
 * POST /elections/{election}/activate
 */
public function activate(Election $election): RedirectResponse
{
    $this->authorize('manageSettings', $election);
    
    // Check if election is already active
    if ($election->status === 'active') {
        return back()->with('error', 'Cannot activate an election that is already active.');
    }
    
    if ($election->status === 'completed') {
        return back()->with('error', 'Cannot activate an election that is already completed.');
    }
    
    // Check if election has posts
    if ($election->posts()->count() === 0) {
        return back()->with('error', 'Cannot activate election without at least one post.');
    }
    
    // Check if each post has at least one candidate
    foreach ($election->posts as $post) {
        if ($post->candidates()->count() === 0) {
            return back()->with('error', "Cannot activate election: Post '{$post->title}' has no candidates.");
        }
    }
    
    // Check if election has at least one active voter
    $activeVoters = $election->memberships()->where('status', 'active')->count();
    if ($activeVoters === 0) {
        return back()->with('error', 'Cannot activate election without at least one eligible voter.');
    }
    
    // Activate the election
    $election->update([
        'status' => 'active',
        'is_active' => true,
        'activated_at' => now(),
        'activated_by' => auth()->id(),
    ]);
    
    // Log the activation
    Log::channel('voting_audit')->info('Election activated', [
        'election_id' => $election->id,
        'election_name' => $election->name,
        'activated_by' => auth()->id(),
        'activated_by_name' => auth()->user()->name,
        'organisation_id' => $election->organisation_id,
    ]);
    
    return back()->with('success', 'Election activated successfully! Voting period is now open.');
}
```

---

## 🛣️ **Step: Add Route**

**File:** `routes/election/electionRoutes.php`

```php
Route::middleware(['auth', 'verified'])
    ->prefix('/elections/{election}')
    ->group(function () {
        // ... existing routes ...
        
        Route::post('/activate', [ElectionManagementController::class, 'activate'])
            ->name('elections.activate')
            ->can('manageSettings', 'election');
    });
```

---

## 🎨 **Step: Update Management.vue with Activate Button**

**File:** `resources/js/Pages/Election/Management.vue`

```vue
<template>
    <!-- Add Activate Button section for planned elections -->
    <section v-if="election.status === 'planned'" class="mb-12">
        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-yellow-800 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Election Ready for Activation
                    </h3>
                    <p class="text-sm text-yellow-700 mt-1">
                        Review all settings before activating. Once activated, voting begins.
                    </p>
                </div>
                <button
                    @click="activateElection"
                    :disabled="activating"
                    class="px-6 py-2 bg-yellow-600 hover:bg-yellow-700 disabled:bg-gray-400 text-white font-semibold rounded-lg transition-colors"
                >
                    {{ activating ? 'Activating...' : 'Activate Election' }}
                </button>
            </div>
            
            <!-- Pre-activation checklist -->
            <div class="mt-4 grid grid-cols-3 gap-3">
                <div class="flex items-center gap-2 text-sm">
                    <span :class="hasPosts ? 'text-green-600' : 'text-red-500'">
                        {{ hasPosts ? '✓' : '✗' }}
                    </span>
                    <span :class="hasPosts ? 'text-green-700' : 'text-gray-500'">
                        {{ hasPosts ? 'Posts added' : 'No posts yet' }}
                    </span>
                </div>
                <div class="flex items-center gap-2 text-sm">
                    <span :class="hasCandidates ? 'text-green-600' : 'text-red-500'">
                        {{ hasCandidates ? '✓' : '✗' }}
                    </span>
                    <span :class="hasCandidates ? 'text-green-700' : 'text-gray-500'">
                        {{ hasCandidates ? 'Candidates added' : 'No candidates yet' }}
                    </span>
                </div>
                <div class="flex items-center gap-2 text-sm">
                    <span :class="hasVoters ? 'text-green-600' : 'text-red-500'">
                        {{ hasVoters ? '✓' : '✗' }}
                    </span>
                    <span :class="hasVoters ? 'text-green-700' : 'text-gray-500'">
                        {{ hasVoters ? 'Voters assigned' : 'No voters yet' }}
                    </span>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { computed } from 'vue'
import { router } from '@inertiajs/vue3'

const activating = ref(false)

// Add computed properties for checklist
const hasPosts = computed(() => props.election.posts_count > 0)
const hasCandidates = computed(() => props.election.candidates_count > 0)
const hasVoters = computed(() => props.election.voters_count > 0)

const activateElection = () => {
    if (!confirm('Are you sure you want to activate this election? Once activated, voting will begin and settings cannot be changed.')) {
        return
    }
    
    activating.value = true
    router.post(route('elections.activate', props.election.id), {}, {
        preserveScroll: true,
        onFinish: () => { activating.value = false }
    })
}
</script>
```

---

## 📊 **Step: Update Controller to Pass Election Counts**

**File:** `app/Http/Controllers/Election/ElectionManagementController.php`

```php
public function index(Election $election): Response
{
    $election->load(['organisation', 'posts.candidates', 'memberships']);
    
    // Add counts for checklist
    $election->posts_count = $election->posts->count();
    $election->candidates_count = $election->posts->sum(fn($post) => $post->candidates->count());
    $election->voters_count = $election->memberships->where('status', 'active')->count();
    
    return Inertia::render('Election/Management', [
        'election' => $election,
        'stats' => $election->voter_stats,
        'canPublish' => auth()->user()->can('publishResults', $election),
    ]);
}
```

---

## 🧪 **Step: Update ElectionCreationTest to Send Notification**

**File:** `tests/Feature/Election/ElectionCreationTest.php`

```php
use App\Notifications\ElectionReadyForActivation;
use Illuminate\Support\Facades\Notification;

public function test_email_notification_sent_to_chiefs_on_election_creation(): void
{
    Notification::fake();

    $this->actingAs($this->owner)
        ->withSession($this->orgSession())
        ->post(route('organisations.elections.store', $this->org->slug), $this->validPayload());

    Notification::assertSentTo(
        $this->chief,
        ElectionReadyForActivation::class
    );
}
```

---

## ✅ **Run Tests**

```bash
# Run activation tests
php artisan test --filter=ElectionActivationTest

# Run creation tests with notification
php artisan test --filter=ElectionCreationTest

# Run all election tests
php artisan test tests/Feature/Election/
```

---

## 🎯 **Success Criteria**
 
| Feature | Status |
|---------|--------|
| Chief can activate planned election | ✅ |
| Deputy can activate planned election | ✅ |
| Commissioner cannot activate | ✅ |
| Cannot activate already active/completed | ✅ |
| Email sent to active chiefs on creation | ✅ |
| Activation button shows checklist | ✅ |
| Pre-activation validation (posts, candidates, voters) | ✅ |

---

## 🚀 **Ready to Implement!**

This TDD-first approach ensures complete coverage of the activation flow. **Proceed with implementation!** 🚀
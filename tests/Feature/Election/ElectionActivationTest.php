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

        $this->owner       = $this->createUserWithRole('owner');
        $this->chief       = $this->createOfficer('chief', 'active');
        $this->deputy      = $this->createOfficer('deputy', 'active');
        $this->commissioner = $this->createOfficer('commissioner', 'active');

        $this->election = Election::create([
            'id'              => (string) Str::uuid(),
            'organisation_id' => $this->org->id,
            'name'            => 'General Election 2026',
            'slug'            => 'general-election-2026',
            'type'            => 'real',
            'status'          => 'planned',
            'start_date'      => now()->addDays(7),
            'end_date'        => now()->addDays(14),
        ]);
    }

    // =========================================================================
    // Permission Tests
    // =========================================================================

    public function test_chief_can_activate_planned_election(): void
    {
        $response = $this->actingAs($this->chief)
            ->withSession($this->orgSession())
            ->post(route('elections.activate', $this->election->id));

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertEquals('active', $this->election->fresh()->status);
    }

    public function test_deputy_can_activate_planned_election(): void
    {
        $response = $this->actingAs($this->deputy)
            ->withSession($this->orgSession())
            ->post(route('elections.activate', $this->election->id));

        $response->assertRedirect();
        $this->assertEquals('active', $this->election->fresh()->status);
    }

    public function test_commissioner_cannot_activate_election(): void
    {
        $response = $this->actingAs($this->commissioner)
            ->withSession($this->orgSession())
            ->post(route('elections.activate', $this->election->id));

        $response->assertForbidden();
        $this->assertEquals('planned', $this->election->fresh()->status);
    }

    public function test_owner_cannot_activate_election(): void
    {
        $response = $this->actingAs($this->owner)
            ->withSession($this->orgSession())
            ->post(route('elections.activate', $this->election->id));

        $response->assertForbidden();
        $this->assertEquals('planned', $this->election->fresh()->status);
    }

    // =========================================================================
    // Status Transition Tests
    // =========================================================================

    public function test_cannot_activate_already_active_election(): void
    {
        Election::withoutGlobalScopes()->where('id', $this->election->id)->update(['status' => 'active']);

        $response = $this->actingAs($this->chief)
            ->withSession($this->orgSession())
            ->post(route('elections.activate', $this->election->id));

        $response->assertSessionHas('error', 'Cannot activate an election that is already active.');
        $this->assertEquals('active', $this->election->fresh()->status);
    }

    public function test_cannot_activate_completed_election(): void
    {
        Election::withoutGlobalScopes()->where('id', $this->election->id)->update(['status' => 'completed']);

        $response = $this->actingAs($this->chief)
            ->withSession($this->orgSession())
            ->post(route('elections.activate', $this->election->id));

        $response->assertSessionHas('error', 'Cannot activate an election that is already completed.');
        $this->assertEquals('completed', $this->election->fresh()->status);
    }

    // =========================================================================
    // Email Notification Tests
    // =========================================================================

    public function test_email_notification_sent_to_chief_when_election_created(): void
    {
        Notification::fake();

        $this->actingAs($this->owner)
            ->withSession($this->orgSession())
            ->post(route('organisations.elections.store', $this->org->slug), [
                'name'       => 'Special Election 2026',
                'start_date' => now()->addDays(7)->toDateString(),
                'end_date'   => now()->addDays(14)->toDateString(),
            ]);

        Notification::assertSentTo($this->chief, ElectionReadyForActivation::class);
    }

    public function test_email_notification_sent_to_all_active_chiefs(): void
    {
        Notification::fake();

        $secondChief = $this->createOfficer('chief', 'active');

        $this->actingAs($this->owner)
            ->withSession($this->orgSession())
            ->post(route('organisations.elections.store', $this->org->slug), [
                'name'       => 'Special Election 2026',
                'start_date' => now()->addDays(7)->toDateString(),
                'end_date'   => now()->addDays(14)->toDateString(),
            ]);

        Notification::assertSentTo($this->chief, ElectionReadyForActivation::class);
        Notification::assertSentTo($secondChief, ElectionReadyForActivation::class);
    }

    public function test_email_notification_not_sent_to_inactive_chiefs(): void
    {
        Notification::fake();

        $inactiveChief = $this->createOfficer('chief', 'inactive');

        $this->actingAs($this->owner)
            ->withSession($this->orgSession())
            ->post(route('organisations.elections.store', $this->org->slug), [
                'name'       => 'Special Election 2026',
                'start_date' => now()->addDays(7)->toDateString(),
                'end_date'   => now()->addDays(14)->toDateString(),
            ]);

        Notification::assertSentTo($this->chief, ElectionReadyForActivation::class);
        Notification::assertNotSentTo($inactiveChief, ElectionReadyForActivation::class);
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
            'organisation_id'   => $this->org->id,
            'email_verified_at' => now(),
        ]);
        UserOrganisationRole::create([
            'id'              => (string) Str::uuid(),
            'user_id'         => $user->id,
            'organisation_id' => $this->org->id,
            'role'            => $role,
        ]);
        return $user;
    }

    private function createOfficer(string $role, string $status): User
    {
        $user = User::factory()->create([
            'organisation_id'   => $this->org->id,
            'email_verified_at' => now(),
        ]);
        UserOrganisationRole::create([
            'id'              => (string) Str::uuid(),
            'user_id'         => $user->id,
            'organisation_id' => $this->org->id,
            'role'            => 'voter',
        ]);
        ElectionOfficer::create([
            'organisation_id' => $this->org->id,
            'user_id'         => $user->id,
            'role'            => $role,
            'status'          => $status,
            'appointed_by'    => $user->id,
            'appointed_at'    => now(),
            'accepted_at'     => $status === 'active' ? now() : null,
        ]);
        return $user;
    }
}

<?php

declare(strict_types=1);

namespace Tests\Isolated\Pbdigit274\Database;

use App\Http\Controllers\Admin\VotingSecurityController;
use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\Isolated\Pbdigit274\IsolatedCommittedDatabaseTestCase;

/**
 * PBDIGIT-274 S4: the admin and CLI recovery paths issue links through VoterSlugIssuer for ONE election
 * (PO decision 2026-10-13: "add election, use issuer"). A recovery gives the voter's current valid link, or a
 * replacement if it expired; never a link after voting or without entitlement; history kept.
 *
 * The admin action is called directly: its route middleware (committee.member) is unchanged and not the
 * subject here. Isolated database only; data committed and removed afterwards.
 */
final class VoterSlugRecoveryPathsTest extends IsolatedCommittedDatabaseTestCase
{
    private Organisation $org;
    private Election $election;
    private User $voter;
    private User $admin;

    /** @var list<string> */
    private array $users = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->org = Organisation::factory()->create(['type' => 'tenant', 'slug' => 'pbd274-rec-'.uniqid()]);
        $this->election = Election::factory()->forOrganisation($this->org)->real()->inVotingState()->create([
            'status' => 'active', 'slug' => 'pbd274-rec-election-'.uniqid(),
            'start_date' => now()->subDay(), 'end_date' => now()->addDay(),
            'voting_locked' => true, 'voting_starts_at' => now()->subHour(), 'voting_ends_at' => now()->addHours(2),
        ]);
        $postId = (string) Str::uuid();
        DB::table('posts')->insert(['id' => $postId, 'organisation_id' => $this->org->id, 'election_id' => $this->election->id,
            'name' => 'PBDIGIT-274 post', 'required_number' => 1, 'created_at' => now(), 'updated_at' => now()]);
        DB::table('candidacies')->insert(['id' => (string) Str::uuid(), 'organisation_id' => $this->org->id, 'post_id' => $postId,
            'status' => 'approved', 'created_at' => now(), 'updated_at' => now()]);

        $this->voter = $this->user();
        DB::table('user_organisation_roles')->insert(['id' => (string) Str::uuid(), 'user_id' => $this->voter->id,
            'organisation_id' => $this->org->id, 'role' => 'member', 'created_at' => now(), 'updated_at' => now()]);
        ElectionMembership::create(['user_id' => $this->voter->id, 'organisation_id' => $this->org->id,
            'election_id' => $this->election->id, 'role' => 'voter', 'status' => 'active']);
        $this->admin = $this->user();
    }

    protected function tearDown(): void
    {
        try {
            DB::table('voter_slugs')->where('election_id', $this->election->id)->delete();
            DB::table('election_memberships')->where('election_id', $this->election->id)->delete();
            DB::table('user_organisation_roles')->whereIn('user_id', $this->users)->delete();
            DB::table('organisations')->where('id', $this->org->id)->delete();
            DB::table('users')->whereIn('id', $this->users)->delete();
        } catch (\Throwable) {
            // isolated, wipeable database; unique fixtures
        }
        parent::tearDown();
    }

    private function user(): User
    {
        $u = User::factory()->create(['email_verified_at' => now(), 'onboarded_at' => now()]);
        $this->users[] = $u->id;

        return $u;
    }

    private function slugRow(array $attributes = []): string
    {
        $id = (string) Str::uuid();
        DB::table('voter_slugs')->insert($attributes + [
            'id' => $id, 'organisation_id' => $this->org->id, 'election_id' => $this->election->id,
            'user_id' => $this->voter->id, 'slug' => Str::random(32), 'is_active' => true, 'has_voted' => false,
            'status' => 'active', 'current_step' => 1, 'created_at' => now(), 'updated_at' => now(),
            'expires_at' => now()->addMinutes(30),
        ]);

        return $id;
    }

    /** @return list<array<string, mixed>> */
    private function rows(?string $electionId = null): array
    {
        return array_map(fn ($r) => (array) $r, DB::table('voter_slugs')->where('user_id', $this->voter->id)
            ->where('election_id', $electionId ?? $this->election->id)->orderBy('created_at')->get()->all());
    }

    private function currentSlug(): ?string
    {
        return DB::table('voter_slugs')->where('user_id', $this->voter->id)->where('election_id', $this->election->id)
            ->whereNull('deleted_at')->value('slug');
    }

    private function adminRecovery(string $electionId)
    {
        $this->actingAs($this->admin);
        $request = Request::create('/admin/voting-security/recovery', 'POST', ['reason' => 'lost link']);
        $request->setLaravelSession(app('session.store'));

        return app(VotingSecurityController::class)->generateRecoverySlug($request, $this->voter, $electionId);
    }

    private function votedCodeRow(): void
    {
        DB::table('codes')->insert(['id' => (string) Str::uuid(), 'organisation_id' => $this->org->id,
            'election_id' => $this->election->id, 'user_id' => $this->voter->id, 'code1' => '112233',
            'has_voted' => true, 'created_at' => now(), 'updated_at' => now()]);
    }

    // ── admin recovery ────────────────────────────────────────────────────

    public function test_admin_recovery_replaces_an_expired_link_and_keeps_history(): void
    {
        $old = $this->slugRow(['created_at' => now()->subMinutes(31), 'expires_at' => now()->subMinute()]);

        $response = $this->adminRecovery($this->election->id);

        $new = $this->currentSlug();
        $this->assertNotNull($new);
        $this->assertCount(2, $this->rows());
        $this->assertNotNull(DB::table('voter_slugs')->where('id', $old)->value('deleted_at'));
        $this->assertSame(route('slug.code.create', ['vslug' => $new]), $response->getSession()->get('recovery_url'));
    }

    public function test_admin_recovery_returns_the_valid_link_without_writing(): void
    {
        $id = $this->slugRow();
        $before = $this->rows();

        $response = $this->adminRecovery($this->election->id);

        $this->assertSame($before, $this->rows());
        $slug = DB::table('voter_slugs')->where('id', $id)->value('slug');
        $this->assertSame(route('slug.code.create', ['vslug' => $slug]), $response->getSession()->get('recovery_url'));
    }

    public function test_admin_recovery_refuses_after_voting(): void
    {
        $this->votedCodeRow();

        $response = $this->adminRecovery($this->election->id);

        $this->assertSame([], $this->rows());
        $this->assertNotNull($response->getSession()->get('error'));
        $this->assertNull($response->getSession()->get('recovery_url'));
    }

    public function test_admin_recovery_refuses_a_demo_election_and_an_unknown_one(): void
    {
        $demo = Election::factory()->forOrganisation($this->org)->demo()->create(['slug' => 'pbd274-rec-demo-'.uniqid()]);

        $this->assertNotNull($this->adminRecovery($demo->id)->getSession()->get('error'));
        $this->assertNotNull($this->adminRecovery((string) Str::uuid())->getSession()->get('error'));
        $this->assertSame([], $this->rows($demo->id));
        $this->assertSame([], $this->rows());
    }

    // ── CLI recovery ──────────────────────────────────────────────────────

    public function test_cli_recovery_requires_the_election(): void
    {
        $this->artisan('election:recover-voter', ['user_id' => $this->voter->id])->assertExitCode(1);
        $this->assertSame([], $this->rows());
    }

    public function test_cli_recovery_replaces_an_expired_link(): void
    {
        $old = $this->slugRow(['created_at' => now()->subMinutes(31), 'expires_at' => now()->subMinute()]);

        $this->artisan('election:recover-voter', ['user_id' => $this->voter->id, '--election' => $this->election->id])
            ->expectsConfirmation("\n❓ Generate recovery link for {$this->voter->name}?", 'yes')
            ->assertExitCode(0);

        $this->assertCount(2, $this->rows());
        $this->assertNotNull(DB::table('voter_slugs')->where('id', $old)->value('deleted_at'));
        $this->assertNotNull($this->currentSlug());
    }

    public function test_cli_recovery_refuses_after_voting(): void
    {
        $this->votedCodeRow();

        $this->artisan('election:recover-voter', ['user_id' => $this->voter->id, '--election' => $this->election->id])
            ->expectsConfirmation("\n❓ Generate recovery link for {$this->voter->name}?", 'yes')
            ->assertExitCode(1);

        $this->assertSame([], $this->rows());
    }
}

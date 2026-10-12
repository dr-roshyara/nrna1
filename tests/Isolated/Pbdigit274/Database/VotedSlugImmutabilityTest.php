<?php

declare(strict_types=1);

namespace Tests\Isolated\Pbdigit274\Database;

use App\Http\Controllers\Admin\VotingSecurityController;
use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\Organisation;
use App\Models\User;
use App\Models\VoterSlug;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\Isolated\Pbdigit274\IsolatedCommittedDatabaseTestCase;

/**
 * PBDIGIT-274 S9b: voted-slug immutability on application paths (plan section 10.3, L-12 / L-15, O-11 = a).
 *
 * A voter who has voted keeps a full history: a voted current link (past its lifetime, still marked active as the
 * cast leaves it), an older replaced link, and the step records 1–5. Every application path that could issue,
 * recover, restart or clean up a link must leave all of that byte-identical: the slug rows (incl. status), the
 * step rows, and the codes row. A re-vote is refused (O-11 = a).
 *
 * The retrieved hook is the known exception (finding F10): loading an expired, still-active slug rewrites
 * is_active / status. It must never touch has_voted, and nothing is ever deleted.
 *
 * Isolated database only; committed data, removed afterwards.
 */
final class VotedSlugImmutabilityTest extends IsolatedCommittedDatabaseTestCase
{
    private Organisation $org;
    private Election $election;
    private User $voter;
    private User $admin;
    private string $votedSlugId;

    /** @var list<string> */
    private array $users = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->org = Organisation::factory()->create(['type' => 'tenant', 'slug' => 'pbd274-s9b-'.uniqid()]);
        $this->election = Election::factory()->forOrganisation($this->org)->real()->inVotingState()->create([
            'status' => 'active', 'slug' => 'pbd274-s9b-election-'.uniqid(),
            'start_date' => now()->subDay(), 'end_date' => now()->addDay(),
            'voting_locked' => true, 'voting_starts_at' => now()->subHours(4), 'voting_ends_at' => now()->addHours(2),
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

        // History: a link replaced 3 h ago (retired, never voted) ...
        $this->slugRow(['created_at' => now()->subHours(4), 'expires_at' => now()->subHours(3)->subMinutes(30),
            'deleted_at' => now()->subHours(3), 'is_active' => false, 'status' => 'expired']);
        // ... and the link the voter voted with, 3 h ago: past its lifetime, still active (the cast doesn't deactivate).
        $this->votedSlugId = $this->slugRow(['created_at' => now()->subHours(3), 'expires_at' => now()->subHours(2)->subMinutes(30),
            'has_voted' => true, 'status' => 'voted', 'current_step' => 5, 'updated_at' => now()->subHours(3)]);
        foreach (range(1, 5) as $step) {
            DB::table('voter_slug_steps')->insert(['id' => (string) Str::uuid(), 'organisation_id' => $this->org->id,
                'voter_slug_id' => $this->votedSlugId, 'election_id' => $this->election->id, 'step' => $step,
                'completed_at' => now()->subHours(3), 'created_at' => now()->subHours(3), 'updated_at' => now()->subHours(3)]);
        }
        DB::table('codes')->insert(['id' => (string) Str::uuid(), 'organisation_id' => $this->org->id,
            'election_id' => $this->election->id, 'user_id' => $this->voter->id, 'code1' => '445566', 'has_voted' => true,
            'can_vote_now' => 0, 'created_at' => now()->subHours(3), 'updated_at' => now()->subHours(3)]);
    }

    protected function tearDown(): void
    {
        try {
            DB::table('voter_slug_steps')->where('election_id', $this->election->id)->delete();
            DB::table('voter_slugs')->where('election_id', $this->election->id)->delete();
            DB::table('codes')->where('election_id', $this->election->id)->delete();
            DB::table('election_memberships')->where('election_id', $this->election->id)->delete();
            DB::table('user_organisation_roles')->whereIn('user_id', $this->users)->delete();
            DB::table('organisations')->where('id', $this->org->id)->delete();
            DB::table('users')->whereIn('id', $this->users)->delete();
        } catch (\Throwable) {
            // isolated, wipeable database
        }
        parent::tearDown();
    }

    private function user(): User
    {
        $u = User::factory()->create(['email_verified_at' => now(), 'onboarded_at' => now()]);
        $this->users[] = $u->id;

        return $u;
    }

    private function slugRow(array $attributes): string
    {
        $id = (string) Str::uuid();
        DB::table('voter_slugs')->insert($attributes + [
            'id' => $id, 'organisation_id' => $this->org->id, 'election_id' => $this->election->id,
            'user_id' => $this->voter->id, 'slug' => Str::random(40), 'is_active' => true, 'has_voted' => false,
            'status' => 'active', 'current_step' => 1, 'updated_at' => now(),
        ]);

        return $id;
    }

    /** The voter's complete state in this election: every slug row (soft-deleted too), every step row, the codes row. */
    private function snapshot(): array
    {
        $rows = fn (string $table, string $order) => array_map(fn ($r) => (array) $r,
            DB::table($table)->where('election_id', $this->election->id)->orderBy($order)->orderBy('id')->get()->all());

        return ['slugs' => $rows('voter_slugs', 'created_at'), 'steps' => $rows('voter_slug_steps', 'step'), 'codes' => $rows('codes', 'created_at')];
    }

    /** As in production: the voter's session carries the organisation (which also arms the retrieved hook's write). */
    private function asVoter(): static
    {
        return $this->actingAs($this->voter)->withSession([
            'current_organisation_id' => $this->org->id, 'selected_election_id' => $this->election->id,
        ]);
    }

    private function assertNothingChanged(array $before, string $path): void
    {
        $this->assertSame($before, $this->snapshot(), "{$path} changed the voted voter's history");
    }

    public function test_starting_to_vote_again_changes_nothing_and_issues_no_link(): void
    {
        $before = $this->snapshot();

        $this->asVoter()->post(route('elections.start', $this->election->slug))
            ->assertRedirect(route('elections.show', $this->election->slug));

        $this->assertNothingChanged($before, 'elections.start');
    }

    public function test_voter_start_and_restart_for_a_voted_voter_change_nothing(): void
    {
        $before = $this->snapshot();

        $this->asVoter()->get(route('voter.start'));
        $this->assertNothingChanged($before, 'voter.start');

        $this->asVoter()->post(route('voter.restart'));
        $this->assertNothingChanged($before, 'voter.restart');
    }

    public function test_admin_regeneration_for_a_voted_voter_changes_nothing(): void
    {
        $before = $this->snapshot();

        $this->actingAs($this->admin);
        session(['current_organisation_id' => $this->org->id]);
        $request = Request::create('/admin/voting-security/recovery', 'POST', ['reason' => 'lost link']);
        $request->setLaravelSession(app('session.store'));
        $response = app(VotingSecurityController::class)->generateRecoverySlug($request, $this->voter, $this->election->id);

        $this->assertNull($response->getSession()->get('recovery_url'));
        $this->assertNothingChanged($before, 'admin recovery');
    }

    public function test_recovery_command_for_a_voted_voter_changes_nothing(): void
    {
        $before = $this->snapshot();

        $this->artisan('election:recover-voter', ['user_id' => $this->voter->id, '--election' => $this->election->id])
            ->expectsConfirmation("\n❓ Generate recovery link for {$this->voter->name}?", 'yes')
            ->assertExitCode(1);

        $this->assertNothingChanged($before, 'election:recover-voter');
    }

    public function test_cleanup_never_hard_deletes_any_real_slug(): void
    {
        $before = $this->snapshot();

        $this->artisan('voting:clean-expired-slugs', ['--hours' => 1])->assertExitCode(0);

        $this->assertNothingChanged($before, 'voting:clean-expired-slugs');
    }

    public function test_retrieved_hook_never_changes_has_voted(): void
    {
        // The hook's own update runs through the tenant-scoped query builder; with no tenant in the session it
        // writes nothing (observed on the first run). A request in the voter's organisation is the real case.
        session(['current_organisation_id' => $this->org->id]);
        $loaded = VoterSlug::withoutGlobalScopes()->findOrFail($this->votedSlugId);

        $row = (array) DB::table('voter_slugs')->where('id', $this->votedSlugId)->first();
        $this->assertTrue((bool) $row['has_voted'], 'has_voted survives the hook');
        $this->assertTrue((bool) $loaded->has_voted);
        $this->assertNull($row['deleted_at'], 'never deleted');
        // Known (F10): the hook marks the expired, still-active slug inactive / 'expired'. Recorded, not asserted away.
        $this->assertSame('expired', $row['status']);
    }

    public function test_revote_refused(): void
    {
        $before = $this->snapshot();
        $slug = DB::table('voter_slugs')->where('id', $this->votedSlugId)->value('slug');

        // The voted link is past its lifetime: the owner is sent to the election page, nothing is written.
        $this->asVoter()->get(route('slug.code.create', ['vslug' => $slug]))
            ->assertRedirect(route('elections.show', $this->election->slug));
        $this->asVoter()->post(route('elections.start', $this->election->slug))
            ->assertRedirect(route('elections.show', $this->election->slug));

        $this->assertNothingChanged($before, 're-vote attempt');
    }
}

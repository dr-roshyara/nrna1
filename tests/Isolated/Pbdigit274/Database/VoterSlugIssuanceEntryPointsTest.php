<?php

declare(strict_types=1);

namespace Tests\Isolated\Pbdigit274\Database;

use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\Isolated\Pbdigit274\IsolatedCommittedDatabaseTestCase;

/**
 * PBDIGIT-274 S4: the voting entry points issue links through VoterSlugIssuer (plan sections 5, 13).
 * Business rule seen by the voter: "start voting" always leads to one current, valid link; after the 30
 * minutes have run out, starting again gives a NEW link instead of locking the voter out.
 *
 * Isolated database only (phpunit.pbdigit274.xml); data is committed (the issuer refuses to run inside a
 * transaction) and removed afterwards. Real entitlement (ElectionMembership) is used, as in production.
 * Fixtures follow tests/Feature/Election/ElectionShowControllerTest.php.
 */
final class VoterSlugIssuanceEntryPointsTest extends IsolatedCommittedDatabaseTestCase
{
    private Organisation $org;
    private Election $election;
    private User $voter;

    /** @var list<string> */
    private array $organisations = [];
    /** @var list<string> */
    private array $users = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->org = Organisation::factory()->create(['type' => 'tenant', 'slug' => 'pbd274-org-'.uniqid()]);
        $this->organisations[] = $this->org->id;
        // Voting must be open in the lifecycle engine (ElectionLifecycleEngineImpl::getState): voting_locked, an
        // open voting window, and at least one approved candidacy. The `state` column alone is not enough.
        $this->election = Election::factory()->forOrganisation($this->org)->real()->inVotingState()->create([
            'status' => 'active',
            'name' => 'PBDIGIT-274 entry points '.uniqid(),
            'slug' => 'pbd274-election-'.uniqid(),
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
            'voting_locked' => true,
            'voting_starts_at' => now()->subHour(),
            'voting_ends_at' => now()->addHours(2),
        ]);
        // Inserted directly: PostFactory and CandidacyFactory eagerly create their own election, post and user in
        // definition() even when ids are passed, which left untracked rows behind on every run.
        $postId = (string) Str::uuid();
        DB::table('posts')->insert([
            'id' => $postId, 'organisation_id' => $this->org->id, 'election_id' => $this->election->id,
            'name' => 'PBDIGIT-274 post', 'required_number' => 1, 'created_at' => now(), 'updated_at' => now(),
        ]);
        DB::table('candidacies')->insert([
            'id' => (string) Str::uuid(), 'organisation_id' => $this->org->id, 'post_id' => $postId,
            'status' => 'approved', 'created_at' => now(), 'updated_at' => now(),
        ]);
        $this->voter = $this->entitledVoter();
    }

    protected function tearDown(): void
    {
        // Best effort on the isolated, wipeable database: this test's own organisation and users.
        try {
            DB::table('voter_slugs')->where('election_id', $this->election->id)->delete();
            DB::table('election_memberships')->where('election_id', $this->election->id)->delete();
            DB::table('user_organisation_roles')->whereIn('user_id', $this->users)->delete();
            DB::table('organisations')->whereIn('id', $this->organisations)->delete();
            DB::table('users')->whereIn('id', $this->users)->delete();
        } catch (\Throwable) {
            // leftovers only on the isolated database; they never affect other fixtures (unique ids)
        }
        parent::tearDown();
    }

    private function entitledVoter(): User
    {
        $user = User::factory()->create(['email_verified_at' => now(), 'onboarded_at' => now()]);
        $this->users[] = $user->id;
        DB::table('user_organisation_roles')->insert([
            'id' => (string) Str::uuid(), 'user_id' => $user->id, 'organisation_id' => $this->org->id,
            'role' => 'member', 'created_at' => now(), 'updated_at' => now(),
        ]);
        ElectionMembership::create([
            'user_id' => $user->id, 'organisation_id' => $this->org->id, 'election_id' => $this->election->id,
            'role' => 'voter', 'status' => 'active',
        ]);

        return $user;
    }

    /** @return list<array<string, mixed>> this voter's slug rows in this election, soft-deleted included */
    private function rows(): array
    {
        return array_map(fn ($r) => (array) $r, DB::table('voter_slugs')
            ->where('user_id', $this->voter->id)->where('election_id', $this->election->id)
            ->orderBy('created_at')->get()->all());
    }

    private function currentSlug(): ?string
    {
        return DB::table('voter_slugs')->where('user_id', $this->voter->id)
            ->where('election_id', $this->election->id)->whereNull('deleted_at')->value('slug');
    }

    private function start()
    {
        return $this->actingAs($this->voter)->post(route('elections.start', $this->election->slug));
    }

    private function expireCurrentLink(): void
    {
        DB::table('voter_slugs')->where('user_id', $this->voter->id)->where('election_id', $this->election->id)
            ->whereNull('deleted_at')
            ->update(['created_at' => now()->subMinutes(31), 'expires_at' => now()->subMinute()]);
    }

    // ── POST /elections/{slug}/start ──────────────────────────────────────

    public function test_the_fixture_is_open_for_voting_and_the_voter_is_entitled(): void
    {
        // Guards the refusal tests below: they only mean something if a normal start would succeed.
        $this->assertTrue(\App\Application\Election\Facades\ElectionLifecycle::of($this->election)->canVote());
        $this->assertTrue(app(\App\Contexts\Elections\Infrastructure\Queries\EloquentVotingEntitlementQuery::class)
            ->decide($this->voter->id, $this->election->id)->isEntitled());
    }

    public function test_first_start_issues_one_link_and_enters_the_voting_flow(): void
    {
        $response = $this->start();

        $this->assertCount(1, $this->rows());
        $response->assertRedirect(route('slug.code.create', ['vslug' => $this->currentSlug()]));
    }

    public function test_starting_again_while_valid_reuses_the_same_link(): void
    {
        $this->start();
        $first = $this->currentSlug();
        $before = $this->rows();

        $this->start()->assertRedirect(route('slug.code.create', ['vslug' => $first]));
        $this->assertSame($before, $this->rows(), 'no extension, no rewrite');
    }

    public function test_starting_again_after_expiry_gives_a_new_link_instead_of_a_lockout(): void
    {
        $this->start();
        $old = $this->currentSlug();
        $this->expireCurrentLink();

        $response = $this->start();

        $new = $this->currentSlug();
        $this->assertNotSame($old, $new, 'a new link');
        $response->assertRedirect(route('slug.code.create', ['vslug' => $new]));
        $rows = $this->rows();
        $this->assertCount(2, $rows, 'the old link is kept as history');
        $this->assertNotNull($rows[0]['deleted_at']);
        $this->assertEqualsWithDelta(now()->timestamp, strtotime($rows[1]['created_at']), 10, 'fresh creation time, so the guard accepts it');
    }

    public function test_a_voter_marked_as_voted_on_the_code_row_gets_no_link(): void
    {
        DB::table('codes')->insert([
            'id' => (string) Str::uuid(), 'organisation_id' => $this->org->id, 'election_id' => $this->election->id,
            'user_id' => $this->voter->id, 'code1' => '123456', 'has_voted' => true,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $this->start()->assertRedirect(route('elections.show', $this->election->slug));
        $this->assertSame([], $this->rows());
    }

    public function test_an_inactive_voter_is_refused_as_before(): void
    {
        // Direct table update: ElectionMembership is tenant-scoped, so a model query here would change nothing.
        $changed = DB::table('election_memberships')->where('user_id', $this->voter->id)
            ->where('election_id', $this->election->id)->update(['status' => 'inactive']);
        $this->assertSame(1, $changed);

        $this->start()->assertRedirect(route('elections.show', $this->election->slug));
        $this->assertSame([], $this->rows());
    }

    // ── GET /voter/start and POST /voter/restart (VoterSlugController), real election in the session ──

    private function voterStart()
    {
        return $this->actingAs($this->voter)->withSession(['selected_election_id' => $this->election->id])->get(route('voter.start'));
    }

    private function voterRestart()
    {
        return $this->actingAs($this->voter)->withSession(['selected_election_id' => $this->election->id])->post(route('voter.restart'));
    }

    public function test_voter_start_issues_then_reuses_and_replaces_after_expiry(): void
    {
        $this->voterStart()->assertRedirect(route('slug.code.create', ['vslug' => $first = $this->currentSlug()]));
        $this->assertNotNull($first);
        $before = $this->rows();

        $this->voterStart()->assertRedirect(route('slug.code.create', ['vslug' => $first]));
        $this->assertSame($before, $this->rows(), 'no deactivation before deciding, no rewrite');

        $this->expireCurrentLink();
        $this->voterStart();
        $this->assertNotSame($first, $this->currentSlug());
        $this->assertCount(2, $this->rows());
    }

    public function test_voter_restart_keeps_a_valid_link_and_replaces_an_expired_one(): void
    {
        $this->voterStart();
        $first = $this->currentSlug();
        $before = $this->rows();

        $this->voterRestart()->assertRedirect(route('slug.code.create', ['vslug' => $first]));
        $this->assertSame($before, $this->rows(), 'no replacement while the link is valid (acceptance 7)');

        $this->expireCurrentLink();
        $this->voterRestart();
        $this->assertNotSame($first, $this->currentSlug());
        $this->assertCount(2, $this->rows());
        $this->assertNotNull($this->rows()[0]['deleted_at'], 'the old link is kept as history');
    }

    public function test_voter_start_gives_no_link_after_voting(): void
    {
        DB::table('codes')->insert([
            'id' => (string) Str::uuid(), 'organisation_id' => $this->org->id, 'election_id' => $this->election->id,
            'user_id' => $this->voter->id, 'code1' => '654321', 'has_voted' => true,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $this->voterStart()->assertRedirect(route('election.dashboard'));
        $this->assertSame([], $this->rows());
    }

    // GET /vote (vote.direct) is NOT tested: routes/web.php:494-496 registers GET /vote (vote.dashboard) after
    // routes/election/electionRoutes.php, so vote.direct is unreachable. Recorded as a finding, not changed in S4.
}

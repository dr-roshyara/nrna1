<?php

namespace Tests\Feature\Vote;

use App\Models\Candidacy;
use App\Models\Code;
use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\Organisation;
use App\Models\Post;
use App\Models\User;
use App\Models\VoterSlug;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Characterization test for the BallotAssemblyService extraction from
 * VoteController::create(). Written self-contained (explicit facts, no
 * shared scenario-factory helpers) because ElectionScenarioFactory::votingActive()
 * is currently broken by an unrelated, earlier change to VotingActive derivation
 * (it hardcodes voting_locked=false, which the engine now also gates on) — using
 * it here would conflate two unrelated issues. This test exists solely to prove
 * VoteController::create()'s real-election ballot rendering is unchanged after
 * the extraction.
 */
class VoteControllerBallotAssemblyRegressionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);
    }

    private function buildVotingActiveElectionWithBallot(string $voterRegion): array
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        $now = now();

        $election = Election::factory()->create([
            'organisation_id' => $org->id,
            'type' => 'real',
            'approved_at' => $now->copy()->subDays(2),
            'voting_starts_at' => $now->copy()->subHour(),
            'voting_ends_at' => $now->copy()->addHour(),
            'administration_completed' => true,
            'administration_completed_at' => $now->copy()->subDay(),
            'nomination_completed' => true,
            'nomination_completed_at' => $now->copy()->subHours(23),
            'voting_locked' => true, // required by current VotingActive derivation
            'voting_locked_at' => $now->copy()->subHour(),
            'results_published_at' => null,
        ]);

        $nationalPost = Post::factory()->create([
            'election_id' => $election->id,
            'organisation_id' => $org->id,
            'name' => 'President',
            'is_national_wide' => true,
            'required_number' => 1,
            'position_order' => 0,
        ]);
        $nationalCandidateUser = User::factory()->forOrganisation($org)->create(['name' => 'National Candidate']);
        Candidacy::factory()->create([
            'organisation_id' => $org->id,
            'post_id' => $nationalPost->id,
            'user_id' => $nationalCandidateUser->id,
            'status' => 'approved',
            'position_order' => 0,
        ]);

        $regionalPost = Post::factory()->create([
            'election_id' => $election->id,
            'organisation_id' => $org->id,
            'name' => 'Regional Rep',
            'is_national_wide' => false,
            'state_name' => 'Bagmati',
            'required_number' => 1,
            'position_order' => 1,
        ]);
        $regionalCandidateUser = User::factory()->forOrganisation($org)->create(['name' => 'Regional Candidate']);
        Candidacy::factory()->create([
            'organisation_id' => $org->id,
            'post_id' => $regionalPost->id,
            'user_id' => $regionalCandidateUser->id,
            'status' => 'approved',
            'position_order' => 0,
        ]);

        $election->update(['candidates_count' => 2, 'posts_count' => 2, 'pending_candidacies_count' => 0]);

        $voter = User::factory()->forOrganisation($org)->create(['region' => $voterRegion]);

        ElectionMembership::create([
            'election_id' => $election->id,
            'organisation_id' => $org->id,
            'user_id' => $voter->id,
            'role' => 'voter',
            'status' => 'active',
        ]);

        $slug = VoterSlug::factory()->create([
            'user_id' => $voter->id,
            'election_id' => $election->id,
            'organisation_id' => $org->id,
            'is_active' => true,
            'current_step' => 3,
        ]);

        // EnsureVoterStepOrder gates on VoterSlugStep completion records (via
        // VoterStepTrackingService), not the current_step column above — mark
        // steps 1-2 (code entry, agreement) complete so step 3 (vote/create)
        // is reachable, matching how the real flow progresses.
        $stepTracker = app(\App\Services\VoterStepTrackingService::class);
        $stepTracker->completeStep($slug, $election, 1);
        $stepTracker->completeStep($slug, $election, 2);

        Code::factory()->create([
            'user_id' => $voter->id,
            'election_id' => $election->id,
            'organisation_id' => $org->id,
            'can_vote_now' => true,
            'has_agreed_to_vote' => true,
        ]);

        return [$election, $voter, $slug];
    }

    /**
     * National-only election (zero regional posts at all) — no regional post,
     * no regional candidacy. Voter still has a region set on their profile.
     */
    private function buildNationalOnlyElectionWithBallot(string $voterRegion): array
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        $now = now();

        $election = Election::factory()->create([
            'organisation_id' => $org->id,
            'type' => 'real',
            'approved_at' => $now->copy()->subDays(2),
            'voting_starts_at' => $now->copy()->subHour(),
            'voting_ends_at' => $now->copy()->addHour(),
            'administration_completed' => true,
            'administration_completed_at' => $now->copy()->subDay(),
            'nomination_completed' => true,
            'nomination_completed_at' => $now->copy()->subHours(23),
            'voting_locked' => true,
            'voting_locked_at' => $now->copy()->subHour(),
            'results_published_at' => null,
        ]);

        $nationalPost = Post::factory()->create([
            'election_id' => $election->id,
            'organisation_id' => $org->id,
            'name' => 'President',
            'is_national_wide' => true,
            'required_number' => 1,
            'position_order' => 0,
        ]);
        $nationalCandidateUser = User::factory()->forOrganisation($org)->create(['name' => 'National Candidate']);
        Candidacy::factory()->create([
            'organisation_id' => $org->id,
            'post_id' => $nationalPost->id,
            'user_id' => $nationalCandidateUser->id,
            'status' => 'approved',
            'position_order' => 0,
        ]);

        $election->update(['candidates_count' => 1, 'posts_count' => 1, 'pending_candidacies_count' => 0]);

        $voter = User::factory()->forOrganisation($org)->create(['region' => $voterRegion]);

        ElectionMembership::create([
            'election_id' => $election->id,
            'organisation_id' => $org->id,
            'user_id' => $voter->id,
            'role' => 'voter',
            'status' => 'active',
        ]);

        $slug = VoterSlug::factory()->create([
            'user_id' => $voter->id,
            'election_id' => $election->id,
            'organisation_id' => $org->id,
            'is_active' => true,
            'current_step' => 3,
        ]);

        $stepTracker = app(\App\Services\VoterStepTrackingService::class);
        $stepTracker->completeStep($slug, $election, 1);
        $stepTracker->completeStep($slug, $election, 2);

        Code::factory()->create([
            'user_id' => $voter->id,
            'election_id' => $election->id,
            'organisation_id' => $org->id,
            'can_vote_now' => true,
            'has_agreed_to_vote' => true,
        ]);

        return [$election, $voter, $slug];
    }

    /**
     * Regional participation is optional at the election level — a
     * national-only election is a valid configuration, not a warning
     * condition. A voter who happens to have a region set on their profile
     * must not be told "no regional candidates for you" when the election
     * never had a regional section to begin with (has_regional_posts must be
     * false here, distinct from "voter has no region set", which is covered
     * by test_real_election_ballot_has_no_regional_posts_for_voter_with_no_region).
     */
    public function test_election_prop_has_regional_posts_is_false_for_national_only_election_even_when_voter_has_a_region(): void
    {
        [$election, $voter, $slug] = $this->buildNationalOnlyElectionWithBallot('Europe');

        $response = $this->actingAs($voter)
            ->withSession(['current_organisation_id' => $election->organisation_id])
            ->get(route('slug.vote.create', ['vslug' => $slug->slug]));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Vote/CreateVotingPage')
            ->has('national_posts', 1)
            ->has('regional_posts', 0)
            ->where('election.has_regional_posts', false)
            ->where('user_region', 'Europe')
        );
    }

    public function test_real_election_ballot_renders_national_and_regional_posts_with_candidates(): void
    {
        [$election, $voter, $slug] = $this->buildVotingActiveElectionWithBallot('Bagmati');

        $response = $this->actingAs($voter)
            ->withSession(['current_organisation_id' => $election->organisation_id])
            ->get(route('slug.vote.create', ['vslug' => $slug->slug]));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Vote/CreateVotingPage')
            ->has('national_posts', 1)
            ->has('national_posts.0.candidates', 1)
            ->where('national_posts.0.name', 'President')
            ->where('national_posts.0.candidates.0.user.name', 'National Candidate')
            ->has('regional_posts', 1)
            ->has('regional_posts.0.candidates', 1)
            ->where('regional_posts.0.name', 'Regional Rep')
            ->where('regional_posts.0.candidates.0.user.name', 'Regional Candidate')
            ->where('user_id', $voter->id)
            ->where('user_name', $voter->name)
            ->where('election.has_regional_posts', true)
        );
    }

    public function test_real_election_ballot_has_no_regional_posts_for_voter_with_no_region(): void
    {
        [$election, $voter, $slug] = $this->buildVotingActiveElectionWithBallot('');

        $response = $this->actingAs($voter)
            ->withSession(['current_organisation_id' => $election->organisation_id])
            ->get(route('slug.vote.create', ['vslug' => $slug->slug]));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Vote/CreateVotingPage')
            ->has('national_posts', 1)
            ->has('regional_posts', 0)
        );
    }
}

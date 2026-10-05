<?php

namespace Tests\Feature\Voting;

use App\Models\Code;
use App\Models\ElectionMembership;
use App\Models\User;
use App\Models\VoterSlug;
use App\Services\VoterStepTrackingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Characterization\Phase1\CastFixtures;
use Tests\TestCase;

/**
 * PBDIGIT-238 R0 — CHARACTERIZATION of the real-election CodeController (code step + agreement).
 *
 * Pins TODAY's behaviour on the real slug routes (slug.code.create, slug.code.store,
 * slug.code.agreement, slug.code.agreement.submit) before the R1 real-election boundary.
 * All tests are expected GREEN at HEAD. Synthetic data only.
 *
 * Characterization ≠ approval: a test labelled CURRENT BEHAVIOUR + LEGACY/DEFECT pins what
 * the code does, not what the target architecture wants (blueprint
 * docs/publicdigit/reviews/2026-10-05-codecontroller-refactoring-blueprint-v1.md):
 * voter-slug presence ≠ entitlement · Code.can_vote_now = process state, not authorization ·
 * has_voted = participation · the code lifetime (voting.time_in_minutes) ≠ election voting period.
 */
class CodeControllerRealElectionCharacterizationTest extends TestCase
{
    use RefreshDatabase;
    use CastFixtures;

    protected function setUp(): void
    {
        parent::setUp();
        $this->makeVotingElection();
    }

    protected function tearDown(): void
    {
        $this->tearDownCast();
        parent::tearDown();
    }

    // ── fixtures ────────────────────────────────────────────────────────────

    /** A voter with an active slug for the real election; membership optional; no Code yet. */
    private function slugVoter(bool $member = true): array
    {
        $voter = User::factory()->create(['email_verified_at' => now(), 'organisation_id' => $this->org->id]);

        if ($member) {
            ElectionMembership::create([
                'user_id'         => $voter->id,
                'organisation_id' => $this->org->id,
                'election_id'     => $this->election->id,
                'role'            => 'voter',
                'status'          => 'active',
            ]);
        }

        $slug = VoterSlug::factory()->create([
            'user_id'         => $voter->id,
            'organisation_id' => $this->org->id,
            'election_id'     => $this->election->id,
            'is_active'       => true,
            'expires_at'      => now()->addHours(2),
        ]);

        return [$voter, $slug];
    }

    private function codeFor(User $voter, array $attributes = []): Code
    {
        return Code::factory()->create(array_merge([
            'organisation_id'                    => $this->org->id,
            'user_id'                            => $voter->id,
            'election_id'                        => $this->election->id,
            'code_to_open_voting_form'           => 'ABCD2345',
            'code_to_open_voting_form_sent_at'   => now(),
            'is_code_to_open_voting_form_usable' => 1,
            'can_vote_now'                       => 0,
            'has_voted'                          => 0,
            'client_ip'                          => '10.0.0.1',
        ], $attributes));
    }

    private function recordStep(VoterSlug $slug, int $step): void
    {
        (new VoterStepTrackingService())->completeStep($slug, $this->election, $step, ['fixture' => true]);
    }

    private function asVoter(User $voter)
    {
        return $this->actingAs($voter)->withSession(['current_organisation_id' => $this->org->id]);
    }

    private function codeCount(User $voter): int
    {
        return Code::withoutGlobalScopes()->where('user_id', $voter->id)->where('election_id', $this->election->id)->count();
    }

    // ── code step: GET code/create ───────────────────────────────────────────

    public function test_r0_01_first_visit_creates_exactly_one_unverified_code_and_renders_the_code_page(): void
    {
        [$voter, $slug] = $this->slugVoter();

        $response = $this->asVoter($voter)->get(route('slug.code.create', ['vslug' => $slug->slug]));

        $response->assertOk();
        $this->assertSame(1, $this->codeCount($voter));
        $code = Code::withoutGlobalScopes()->where('user_id', $voter->id)->first();
        $this->assertFalse((bool) $code->can_vote_now, 'code step not yet completed');
        $this->assertTrue((bool) $code->has_code1_sent);
        $this->assertFalse((bool) $code->has_voted);
    }

    public function test_r0_02_a_slug_holder_without_voter_membership_is_refused_and_gets_no_code(): void
    {
        [$voter, $slug] = $this->slugVoter(member: false);

        $response = $this->asVoter($voter)->get(route('slug.code.create', ['vslug' => $slug->slug]));

        $response->assertRedirect();
        $this->assertSame(0, $this->codeCount($voter), 'membership (ensure.election.voter) is checked before the controller');
    }

    public function test_r0_03_legacy_slug_presence_satisfies_controller_eligibility_without_users_can_vote(): void
    {
        // CURRENT BEHAVIOUR + LEGACY: isUserEligible() returns true for any request carrying a voter slug;
        // users.can_vote is never consulted on the slug routes. Slug presence is NOT entitlement (target: R3).
        [$voter, $slug] = $this->slugVoter();
        $this->assertNull($voter->can_vote ?? null, 'precondition: no users.can_vote value');

        $this->asVoter($voter)->get(route('slug.code.create', ['vslug' => $slug->slug]))->assertOk();

        $this->assertSame(1, $this->codeCount($voter));
    }

    // ── consumed participation (has_voted) ───────────────────────────────────

    public function test_r0_04_consumed_participation_blocks_the_code_page_and_issues_no_new_code(): void
    {
        [$voter, $slug] = $this->slugVoter();
        $code = $this->codeFor($voter, ['has_voted' => 1, 'can_vote_now' => 0, 'code_to_open_voting_form' => 'VOTED234']);

        $response = $this->asVoter($voter)->get(route('slug.code.create', ['vslug' => $slug->slug]));

        $response->assertRedirect(route('dashboard'));
        $after = $code->fresh();
        $this->assertSame('VOTED234', $after->code_to_open_voting_form, 'no new code value');
        $this->assertTrue((bool) $after->has_voted);
    }

    public function test_r0_05_consumed_participation_is_refused_at_code_entry(): void
    {
        [$voter, $slug] = $this->slugVoter();
        $code = $this->codeFor($voter, ['has_voted' => 1, 'code_to_open_voting_form' => 'VOTED234']);

        $response = $this->asVoter($voter)->post(route('slug.code.store', ['vslug' => $slug->slug]), ['voting_code' => 'VOTED234']);

        $response->assertSessionHasErrors('voting_code');
        $this->assertFalse((bool) $code->fresh()->can_vote_now, 'the code step is not completed');
    }

    public function test_r0_06_consumed_participation_cannot_accept_the_agreement(): void
    {
        [$voter, $slug] = $this->slugVoter();
        $code = $this->codeFor($voter, ['has_voted' => 1, 'can_vote_now' => 1, 'has_agreed_to_vote' => 0]);
        $this->recordStep($slug, 1);

        $this->asVoter($voter)->get(route('slug.code.agreement', ['vslug' => $slug->slug]))->assertRedirect();
        $this->asVoter($voter)->post(route('slug.code.agreement.submit', ['vslug' => $slug->slug]), ['agreement' => 1])->assertRedirect();

        $this->assertFalse((bool) $code->fresh()->has_agreed_to_vote, 'agreement not recorded for a voter who already voted');
    }

    // ── code entry: POST code ────────────────────────────────────────────────

    public function test_r0_07_a_wrong_code_is_refused_and_the_code_step_stays_open(): void
    {
        [$voter, $slug] = $this->slugVoter();
        $code = $this->codeFor($voter);

        $response = $this->asVoter($voter)->post(route('slug.code.store', ['vslug' => $slug->slug]), ['voting_code' => 'WRONG234']);

        $response->assertSessionHasErrors('voting_code');
        $this->assertFalse((bool) $code->fresh()->can_vote_now);
    }

    public function test_r0_08_an_expired_code_is_refused_at_code_entry(): void
    {
        // CURRENT BEHAVIOUR: the code lifetime is config('voting.time_in_minutes') — the same value is used as
        // the slug window. It is NOT the election voting period (blueprint: overlapping time concepts).
        config(['voting.time_in_minutes' => 30]);
        [$voter, $slug] = $this->slugVoter();
        $code = $this->codeFor($voter, ['code_to_open_voting_form_sent_at' => now()->subMinutes(45)]);

        $response = $this->asVoter($voter)->post(route('slug.code.store', ['vslug' => $slug->slug]), ['voting_code' => 'ABCD2345']);

        $response->assertSessionHasErrors('voting_code');
        $this->assertFalse((bool) $code->fresh()->can_vote_now);
    }

    public function test_r0_09_a_valid_code_completes_the_code_step_and_leads_to_the_agreement(): void
    {
        [$voter, $slug] = $this->slugVoter();
        $code = $this->codeFor($voter);

        $response = $this->asVoter($voter)->post(route('slug.code.store', ['vslug' => $slug->slug]), ['voting_code' => 'ABCD2345']);

        $response->assertRedirect(route('slug.code.agreement', ['vslug' => $slug->slug]));
        $response->assertSessionHasNoErrors();
        // Code.can_vote_now = code/process verification state (not authorization).
        $this->assertTrue((bool) $code->fresh()->can_vote_now);
        $this->assertGreaterThanOrEqual(1, (new VoterStepTrackingService())->getHighestCompletedStep($slug, $this->election));
    }

    public function test_r0_10_known_defect_an_already_verified_code_short_circuits_code_entry_without_checking_the_value(): void
    {
        // CURRENT BEHAVIOUR + LEGACY/DEFECT (PBDIGIT-231 / VE-03): can_vote_now = true → handleAlreadyVerified()
        // before verifyCode(); the submitted value is never compared.
        [$voter, $slug] = $this->slugVoter();
        $this->codeFor($voter, ['can_vote_now' => 1, 'is_code_to_open_voting_form_usable' => 1]);

        $response = $this->asVoter($voter)->post(route('slug.code.store', ['vslug' => $slug->slug]), ['voting_code' => 'WRONG234']);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('slug.code.agreement', ['vslug' => $slug->slug]));
    }

    public function test_r0_11_the_ip_limit_refuses_code_entry_once_reached(): void
    {
        // CURRENT BEHAVIOUR: verifyCode() counts codes with has_voted = 1 from the request IP in this election
        // (post-participation count, evaluated at code entry; C1 / ADR F4). Limit = config('app.max_use_clientIP') ?? 7.
        config(['app.max_use_clientIP' => 2]);
        foreach (range(1, 2) as $i) {
            [$other] = $this->slugVoter();
            $this->codeFor($other, ['has_voted' => 1, 'client_ip' => '127.0.0.1', 'code_to_open_voting_form' => 'IPLIM00' . $i]);
        }
        [$voter, $slug] = $this->slugVoter();
        $code = $this->codeFor($voter);

        $response = $this->asVoter($voter)->post(route('slug.code.store', ['vslug' => $slug->slug]), ['voting_code' => 'ABCD2345']);

        $response->assertSessionHasErrors('voting_code');
        $this->assertFalse((bool) $code->fresh()->can_vote_now);
    }

    // ── agreement ────────────────────────────────────────────────────────────

    public function test_r0_12_a_verified_voter_sees_the_agreement_page(): void
    {
        [$voter, $slug] = $this->slugVoter();
        $this->codeFor($voter, ['can_vote_now' => 1, 'has_agreed_to_vote' => 0]);
        $this->recordStep($slug, 1);

        $this->asVoter($voter)->get(route('slug.code.agreement', ['vslug' => $slug->slug]))->assertOk();
    }

    public function test_r0_13_accepting_the_agreement_records_it_and_leads_to_the_ballot(): void
    {
        [$voter, $slug] = $this->slugVoter();
        $code = $this->codeFor($voter, ['can_vote_now' => 1, 'has_agreed_to_vote' => 0]);
        $this->recordStep($slug, 1);

        $response = $this->asVoter($voter)->post(route('slug.code.agreement.submit', ['vslug' => $slug->slug]), ['agreement' => 1]);

        $response->assertRedirect(route('slug.vote.create', ['vslug' => $slug->slug]));
        $this->assertTrue((bool) $code->fresh()->has_agreed_to_vote);
    }
}

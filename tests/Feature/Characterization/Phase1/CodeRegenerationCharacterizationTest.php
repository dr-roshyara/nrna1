<?php

namespace Tests\Feature\Characterization\Phase1;

use App\Models\Code;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Phase 1 — group 1C, CODE REGENERATION (roadmap Phase 1C, SR §11a F1, F2; defect D6).
 *
 * How regeneration, Code.can_vote_now, code verification and previous participation
 * interact. Code.can_vote_now is NOT changed — only observed. Synthetic data only.
 */
class CodeRegenerationCharacterizationTest extends TestCase
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

    private function asVoter($voter)
    {
        return $this->actingAs($voter)->withSession(['current_organisation_id' => $this->org->id]);
    }

    // ── F1: an already-voted voter ───────────────────────────────────────────

    public function test_f1_an_already_voted_voter_obtains_no_new_usable_code(): void
    {
        [$voter, $slug, $code] = $this->voterReadyToCast();
        $this->cast($voter, $slug, $code);
        $voted = $code->fresh();
        $this->assertTrue((bool) $voted->has_voted, 'precondition: voted');

        $this->asVoter($voter)->get(route('slug.code.create', ['vslug' => $slug->slug]));
        $this->asVoter($voter)->post(route('slug.code.store', ['vslug' => $slug->slug]), ['voting_code' => $voted->code_to_open_voting_form]);

        $after = $voted->fresh();
        $this->assertSame($voted->code_to_open_voting_form, $after->code_to_open_voting_form, 'no new code value issued');
        $this->assertFalse((bool) $after->can_vote_now, 'the code step is not re-opened');
        $this->assertTrue((bool) $after->has_voted);
        $this->assertSame(1, DB::table('votes')->where('election_id', $this->election->id)->count(), 'no second vote');
    }

    // ── F2: regeneration of a verified, not-yet-used code (D6) ───────────────

    /** A verified code (can_vote_now = true) whose code-1 window has expired; not voted. */
    private function verifiedButStaleCode(): array
    {
        [$voter, $slug, $code] = $this->voterReadyToCast('OLDC0DE1');
        $code->update([
            'code_to_open_voting_form_sent_at' => now()->subMinutes(60),
            'has_code1_sent'                   => true,
        ]);

        return [$voter, $slug, $code->fresh()];
    }

    public function test_f2_known_gap_regeneration_issues_a_new_code_but_keeps_the_code_step_verified(): void
    {
        [$voter, $slug, $code] = $this->verifiedButStaleCode();

        $this->asVoter($voter)->get(route('slug.code.create', ['vslug' => $slug->slug]));

        $after = $code->fresh();
        $this->assertNotSame('OLDC0DE1', $after->code_to_open_voting_form, 'a new code value was generated');
        $this->assertTrue((bool) $after->can_vote_now,
            'KNOWN GAP D6: can_vote_now stays true after regeneration ("Preserve verified status")');
    }

    public function test_f2_known_gap_after_regeneration_a_wrong_code_passes_the_code_entry_step(): void
    {
        [$voter, $slug, $code] = $this->verifiedButStaleCode();
        $this->asVoter($voter)->get(route('slug.code.create', ['vslug' => $slug->slug]));

        $response = $this->asVoter($voter)
            ->post(route('slug.code.store', ['vslug' => $slug->slug]), ['voting_code' => 'WRONG999']);

        $response->assertSessionHasNoErrors();
        $this->assertTrue((bool) $code->fresh()->can_vote_now,
            'KNOWN GAP D6: the new code is never verified at code entry — store() short-circuits "already verified"');
    }

    public function test_f2_mitigation_the_final_vote_still_requires_the_new_code_value(): void
    {
        [$voter, $slug, $code] = $this->verifiedButStaleCode();
        $this->asVoter($voter)->get(route('slug.code.create', ['vslug' => $slug->slug]));
        $new = $code->fresh();

        $this->cast($voter, $slug, $new, 'OLDC0DE1');
        $this->assertSame(0, DB::table('votes')->where('election_id', $this->election->id)->count(),
            'the final vote compares against the CURRENT code value: the old code is refused');

        $this->cast($voter, $slug, $new->fresh(), $new->code_to_open_voting_form);
        $this->assertSame(1, DB::table('votes')->where('election_id', $this->election->id)->count(),
            'the new code value (sent by e-mail on regeneration) is accepted at the final vote');
    }
}

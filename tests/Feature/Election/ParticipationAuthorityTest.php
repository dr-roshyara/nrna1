<?php

namespace Tests\Feature\Election;

use App\Models\Code;
use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\User;
use App\Services\NewsletterService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\ElectionScenarioFactory;
use Tests\TestCase;

/**
 * PBDIGIT-252 — RED: one participation authority (PO 2026-10-06).
 *
 * Invariant: participation completion is authoritative in codes.has_voted (written in the vote's own transaction).
 * No voting-related behaviour may use election_memberships.has_voted (no production writer) as a source of truth.
 * The column itself is NOT removed here (separate decision).
 *
 * Fixtures deliberately make the two sources DISAGREE, so each consumer shows which one it reads:
 *   $votedInCodes      codes.has_voted = true,  membership.has_voted = false
 *   $flaggedInDeadCol  codes.has_voted = false, membership.has_voted = true
 *
 *   P1 Voter Management: the row's has_voted comes from codes (Voted column + row-action visibility)
 *   P2 suspension guard: a voter who voted (codes) cannot be proposed for suspension
 *   P3 suspension guard: the dead column alone does not block a proposal
 *   P4 newsletter: election_voted / election_not_voted follow codes
 *
 * Anonymity (ADR-T11): participation state ("has voted"), never ballot content — no voter ↔ ballot link.
 * Synthetic data only.
 */
class ParticipationAuthorityTest extends TestCase
{
    use RefreshDatabase;
    use C4Fixtures;

    private Election $election;
    private User $chief;
    private User $votedInCodes;
    private User $flaggedInDeadCol;

    protected function setUp(): void
    {
        parent::setUp();
        $this->makeOrganisation();
        $this->election = ElectionScenarioFactory::setupAdministration($this->org);
        $this->chief = $this->officer($this->election, 'chief');

        $this->votedInCodes = $this->registeredVoter($this->election);
        $this->votedCode($this->votedInCodes);

        $this->flaggedInDeadCol = $this->registeredVoter($this->election);
        $this->membership($this->flaggedInDeadCol)->forceFill(['has_voted' => true])->save();
    }

    private function votedCode(User $voter): void
    {
        Code::factory()->voted()->create([
            'organisation_id' => $this->org->id,
            'election_id'     => $this->election->id,
            'user_id'         => $voter->id,
            'code1'           => 'PA' . substr(md5($voter->id), 0, 6),
        ]);
    }

    private function membership(User $voter): ElectionMembership
    {
        return ElectionMembership::where('election_id', $this->election->id)->where('user_id', $voter->id)->first();
    }

    private function asChief()
    {
        return $this->actingAs($this->chief)->withSession(['current_organisation_id' => $this->org->id])->from('/voters-manage');
    }

    public function test_p1_voter_management_takes_has_voted_from_codes(): void
    {
        $rows = collect($this->asChief()->get($this->voterRoute('elections.voters.index', $this->election))
            ->assertOk()->viewData('page')['props']['voters']['data'])->keyBy('user_id');

        $this->assertTrue((bool) $rows[$this->votedInCodes->id]['has_voted'], 'voted per codes → shown as voted');
        $this->assertFalse((bool) $rows[$this->flaggedInDeadCol->id]['has_voted'], 'the dead column alone is not participation');
    }

    public function test_p2_a_voter_who_voted_cannot_be_proposed_for_suspension(): void
    {
        $membership = $this->membership($this->votedInCodes);

        $this->asChief()->post($this->voterRoute('elections.voters.propose-suspension', $this->election, ['membership' => $membership->id]))
            ->assertSessionHas('error', 'Cannot suspend a voter who has already voted.');

        $this->assertNotSame('proposed', $membership->fresh()->suspension_status);
    }

    public function test_p3_the_dead_column_alone_does_not_block_a_suspension_proposal(): void
    {
        $membership = $this->membership($this->flaggedInDeadCol);

        $this->asChief()->post($this->voterRoute('elections.voters.propose-suspension', $this->election, ['membership' => $membership->id]))
            ->assertSessionMissing('error');

        $this->assertSame('proposed', $membership->fresh()->suspension_status, 'not voted per codes → the proposal is accepted');
    }

    public function test_p4_newsletter_voted_and_not_voted_audiences_follow_codes(): void
    {
        $service = app(NewsletterService::class);
        $meta = ['election_id' => $this->election->id];

        $voted = $service->resolveAudience($this->org, 'election_voted', $meta, true)->pluck('user_id')->all();
        $notVoted = $service->resolveAudience($this->org, 'election_not_voted', $meta, true)->pluck('user_id')->all();

        $this->assertContains($this->votedInCodes->id, $voted);
        $this->assertNotContains($this->flaggedInDeadCol->id, $voted);
        $this->assertContains($this->flaggedInDeadCol->id, $notVoted);
        $this->assertNotContains($this->votedInCodes->id, $notVoted);
    }
}

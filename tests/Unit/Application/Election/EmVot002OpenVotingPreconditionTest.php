<?php

namespace Tests\Unit\Application\Election;

use App\Application\Election\Services\ConstitutionalTransitionGuard;
use App\Domain\Election\Constitution\ElectionConstitution;
use App\Domain\Election\Enum\ElectionLifecycleState;
use App\Domain\Election\ValueObjects\ElectionLifecycleSnapshot;
use App\Exceptions\InvalidTransitionException;
use App\Models\Candidacy;
use App\Models\Election;
use App\Models\ElectionOfficer;
use App\Models\Organisation;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * EM-VOT-002 — command path: `open_voting` must not succeed with zero
 * approved candidates. (Election Manifesto §4a, ADOPTED — SD-14 = YES.)
 *
 * ElectionConstitution is the authoritative implementation home for the
 * open_voting precondition; ConstitutionalTransitionGuard is its enforcement
 * layer on every constitutional transition (Election::transitionTo()).
 *
 * Rule ID: EM-VOT-002 · Capability: open voting (chief command)
 * Boundary: ElectionConstitution::RULES['open_voting'] + guard.
 */
class EmVot002OpenVotingPreconditionTest extends TestCase
{
    use RefreshDatabase;

    private ConstitutionalTransitionGuard $guard;
    private Organisation $org;
    private User $chief;

    protected function setUp(): void
    {
        parent::setUp();

        Election::resetPlatformOrgCache();

        $this->guard = new ConstitutionalTransitionGuard();
        $this->org = Organisation::factory()->create(['type' => 'tenant']);
        session(['current_organisation_id' => $this->org->id]);
        $this->chief = User::factory()->create(['organisation_id' => $this->org->id]);
    }

    private function readyElection(): Election
    {
        $election = Election::factory()
            ->forOrganisation($this->org)
            ->create([
                'type'                     => 'real',
                'status'                   => 'active',
                'approved_at'              => now()->subDays(10),
                'setup_started_at'         => now()->subDays(9),
                'administration_completed' => true,
                'nomination_completed'     => false,
                'voting_starts_at'         => now()->addHour(),
                'voting_ends_at'           => now()->addDay(),
                'timezone'                 => 'Europe/Berlin',
            ]);

        ElectionOfficer::create([
            'election_id'     => $election->id,
            'organisation_id' => $this->org->id,
            'user_id'         => $this->chief->id,
            'role'            => 'chief',
            'status'          => 'active',
        ]);

        return $election;
    }

    private function setupNominationSnapshot(): ElectionLifecycleSnapshot
    {
        return new ElectionLifecycleSnapshot(
            state: ElectionLifecycleState::SetupNomination,
            canEdit: true,
            canVote: false,
            canManageVoters: false,
            canPublishResults: false,
            canEditTimeline: true,
            isLocked: false,
            blockedReason: null,
            allowedActions: ['complete_nomination', 'open_voting'],
        );
    }

    private function approveCandidateFor(Election $election): void
    {
        $post = Post::factory()->create([
            'election_id'     => $election->id,
            'organisation_id' => $this->org->id,
        ]);
        $candidate = User::factory()->create(['organisation_id' => $this->org->id]);
        Candidacy::factory()->create([
            'post_id'         => $post->id,
            'organisation_id' => $this->org->id,
            'user_id'         => $candidate->id,
            'status'          => 'approved',
        ]);
    }

    public function test_constitution_declares_approved_candidates_precondition_on_open_voting(): void
    {
        $this->assertContains(
            'has_approved_candidates',
            ElectionConstitution::getPreconditionsForAction('open_voting'),
            'EM-VOT-002: the open_voting rule must carry the has_approved_candidates precondition'
        );
    }

    public function test_open_voting_is_refused_when_no_approved_candidate_exists(): void
    {
        $election = $this->readyElection();
        $this->actingAs($this->chief);

        try {
            $this->guard->assertAllowed($election, 'open_voting', $this->setupNominationSnapshot());
            $this->fail('EM-VOT-002: open_voting must be refused with zero approved candidates');
        } catch (InvalidTransitionException $e) {
            $this->assertStringContainsString('has_approved_candidates', $e->getMessage(),
                'The refusal must name the unmet precondition');
        }
    }

    public function test_open_voting_is_not_blocked_by_em_vot_002_when_an_approved_candidate_exists(): void
    {
        $election = $this->readyElection();
        $this->approveCandidateFor($election);
        $this->actingAs($this->chief);

        $this->guard->assertAllowed($election, 'open_voting', $this->setupNominationSnapshot());

        $this->assertTrue(true, 'open_voting proceeds when EM-VOT-002 is satisfied');
    }
}

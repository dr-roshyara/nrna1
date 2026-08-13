<?php

namespace Tests\Feature\Election;

use App\Application\Election\Services\ElectionLifecycleEngineImpl;
use App\Domain\Election\Enum\ElectionLifecycleState;
use App\Models\Candidacy;
use App\Models\Election;
use App\Models\Organisation;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * EM-VOT-002 — "An election must have at least one approved candidate before
 * voting may be opened." (Election Manifesto §4a, ADOPTED — SD-14 = YES.)
 *
 * The invariant binds on EVERY path into voting_active, not only the
 * open_voting command. The computed lifecycle path (PBDIGIT-64: state derived
 * from the voting window by ElectionLifecycleEngineImpl::getState() priority 5)
 * is the path that failed at runtime and is tested here explicitly.
 *
 * Rule ID: EM-VOT-002 · Capability: election lifecycle / transition into voting
 * Boundary: ElectionLifecycleEngineImpl (computed) + ElectionConstitution
 * open_voting preconditions (command).
 *
 * NOT asserted here (out of authority): what a held election does instead
 * (hold/warn/extend is a PO decision); voter-level entitlement; any
 * Full Membership behaviour.
 */
class EmVot002ApprovedCandidateBeforeVotingTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private ElectionLifecycleEngineImpl $engine;

    protected function setUp(): void
    {
        parent::setUp();

        Election::resetPlatformOrgCache();

        $this->org = Organisation::factory()->create(['type' => 'tenant']);
        session(['current_organisation_id' => $this->org->id]);

        $this->engine = new ElectionLifecycleEngineImpl();
    }

    /**
     * An election whose voting window is open NOW, with the given completion
     * flags — the exact shape of the PBDIGIT-64 runtime defect.
     */
    private function electionWithOpenWindow(array $overrides = []): Election
    {
        return Election::factory()
            ->forOrganisation($this->org)
            ->create(array_merge([
                'type'                     => 'real',
                'status'                   => 'active',
                'approved_at'              => now()->subDays(10),
                'setup_started_at'         => now()->subDays(9),
                'administration_completed' => true,
                'nomination_completed'     => false,
                'voting_starts_at'         => now()->subHour(),
                'voting_ends_at'           => now()->addHour(),
                'timezone'                 => 'Europe/Berlin',
            ], $overrides));
    }

    private function addCandidacy(Election $election, string $status): Candidacy
    {
        $post = Post::factory()->create([
            'election_id'     => $election->id,
            'organisation_id' => $this->org->id,
        ]);

        $candidate = User::factory()->create(['organisation_id' => $this->org->id]);

        return Candidacy::factory()->create([
            'post_id'         => $post->id,
            'organisation_id' => $this->org->id,
            'user_id'         => $candidate->id,
            'status'          => $status,
        ]);
    }

    // =========================================================================
    // COMPUTED PATH (PBDIGIT-64) — the path that failed at runtime
    // =========================================================================

    public function test_open_window_with_zero_candidacies_does_not_compute_to_voting_active(): void
    {
        $election = $this->electionWithOpenWindow();

        $state = $this->engine->getState($election);

        $this->assertNotEquals(ElectionLifecycleState::VotingActive, $state,
            'EM-VOT-002: an election with zero approved candidates must not become voting_active, '
            . 'even when its voting window is open (computed path, PBDIGIT-64)');
    }

    public function test_open_window_with_only_unapproved_candidacies_does_not_compute_to_voting_active(): void
    {
        $election = $this->electionWithOpenWindow();
        $this->addCandidacy($election, 'draft');
        $this->addCandidacy($election, 'pending');

        $state = $this->engine->getState($election);

        $this->assertNotEquals(ElectionLifecycleState::VotingActive, $state,
            'EM-VOT-002: draft/pending candidacies are not approved candidates');
    }

    public function test_open_window_with_an_approved_candidate_computes_to_voting_active(): void
    {
        $election = $this->electionWithOpenWindow();
        $this->addCandidacy($election, 'approved');

        $state = $this->engine->getState($election);

        $this->assertEquals(ElectionLifecycleState::VotingActive, $state,
            'EM-VOT-002 must not block an election that has an approved candidate');
    }

    public function test_snapshot_can_vote_is_false_when_no_approved_candidate_exists(): void
    {
        $election = $this->electionWithOpenWindow();

        $snapshot = $this->engine->compute($election);

        $this->assertFalse($snapshot->canVote,
            'EM-VOT-002: the projection every voter route reads must not report canVote for '
            . 'an election with no approved candidate');
    }

    public function test_setup_complete_edge_with_zero_approved_candidates_never_computes_to_voting_active(): void
    {
        // Edge: nomination was completed (with an approved candidate) but the
        // approval was later withdrawn/rejected. Only the ADOPTED invariant is
        // asserted: the election is never voting_active. Which non-voting
        // outcome applies instead (hold state vs the engine's existing
        // invalid-state backstop) is an open PO decision and is NOT pinned.
        $election = $this->electionWithOpenWindow([
            'nomination_completed' => true,
        ]);

        try {
            $state = $this->engine->getState($election);
            $this->assertNotEquals(ElectionLifecycleState::VotingActive, $state,
                'EM-VOT-002 binds regardless of completion flags');
        } catch (\App\Domain\Election\Exception\InvalidElectionStateException) {
            // The engine's pre-existing constitutional backstop also keeps the
            // election out of voting — the invariant holds.
            $this->assertTrue(true);
        }
    }
}

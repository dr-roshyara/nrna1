<?php

namespace Tests\Unit\Application\Election;

use App\Application\Election\Services\NominationCompletionPredicates;
use App\Models\Candidacy;
use App\Models\Election;
use App\Models\Organisation;
use App\Models\Post;
use App\Models\User;
use App\Services\TenantContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Slice B: the single authoritative definition of the two nomination-completion
 * predicates, consumed by BOTH ConstitutionalTransitionGuard (human path) and
 * Election::validateCompleteNomination() (both paths, since it runs regardless
 * of transition trigger — see the migration-conflict report). Must use
 * withoutGlobalScopes() because the system/console caller
 * (ProcessElectionAutoTransitions) runs with no HTTP tenant context.
 */
class NominationCompletionPredicatesTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        TenantContext::set(null);
        parent::tearDown();
    }

    private function buildElectionWithPost(): array
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        $election = Election::factory()->forOrganisation($org)->create(['type' => 'real']);
        $post = Post::factory()->create(['election_id' => $election->id, 'organisation_id' => $org->id]);

        return [$org, $election, $post];
    }

    public function test_has_approved_candidates_is_true_when_an_approved_candidacy_exists(): void
    {
        [$org, $election, $post] = $this->buildElectionWithPost();
        TenantContext::set($org->id);
        Candidacy::factory()->create([
            'organisation_id' => $org->id,
            'post_id' => $post->id,
            'user_id' => User::factory()->forOrganisation($org)->create()->id,
            'status' => 'approved',
        ]);

        $this->assertTrue(NominationCompletionPredicates::hasApprovedCandidates($election));
    }

    public function test_has_approved_candidates_is_false_when_none_are_approved(): void
    {
        [$org, $election, $post] = $this->buildElectionWithPost();
        TenantContext::set($org->id);
        Candidacy::factory()->create([
            'organisation_id' => $org->id,
            'post_id' => $post->id,
            'user_id' => User::factory()->forOrganisation($org)->create()->id,
            'status' => 'pending',
        ]);

        $this->assertFalse(NominationCompletionPredicates::hasApprovedCandidates($election));
    }

    /**
     * Proves the withoutGlobalScopes() requirement is real, not decorative:
     * with NO tenant context active (exactly how ProcessElectionAutoTransitions
     * runs), the predicate must still see the approved candidacy.
     */
    public function test_has_approved_candidates_is_true_with_no_active_tenant_context(): void
    {
        [$org, $election, $post] = $this->buildElectionWithPost();
        TenantContext::set($org->id);
        Candidacy::factory()->create([
            'organisation_id' => $org->id,
            'post_id' => $post->id,
            'user_id' => User::factory()->forOrganisation($org)->create()->id,
            'status' => 'approved',
        ]);

        // Simulate console/system execution: no tenant context, no session.
        TenantContext::set(null);

        $this->assertTrue(NominationCompletionPredicates::hasApprovedCandidates($election));
    }

    public function test_has_no_pending_candidacies_is_true_when_none_are_pending(): void
    {
        [$org, $election, $post] = $this->buildElectionWithPost();
        TenantContext::set($org->id);
        Candidacy::factory()->create([
            'organisation_id' => $org->id,
            'post_id' => $post->id,
            'user_id' => User::factory()->forOrganisation($org)->create()->id,
            'status' => 'approved',
        ]);

        $this->assertTrue(NominationCompletionPredicates::hasNoPendingCandidacies($election));
    }

    public function test_has_no_pending_candidacies_is_false_when_one_is_pending(): void
    {
        [$org, $election, $post] = $this->buildElectionWithPost();
        TenantContext::set($org->id);
        Candidacy::factory()->create([
            'organisation_id' => $org->id,
            'post_id' => $post->id,
            'user_id' => User::factory()->forOrganisation($org)->create()->id,
            'status' => 'pending',
        ]);

        $this->assertFalse(NominationCompletionPredicates::hasNoPendingCandidacies($election));
    }

    public function test_has_no_pending_candidacies_is_false_with_no_active_tenant_context(): void
    {
        [$org, $election, $post] = $this->buildElectionWithPost();
        TenantContext::set($org->id);
        Candidacy::factory()->create([
            'organisation_id' => $org->id,
            'post_id' => $post->id,
            'user_id' => User::factory()->forOrganisation($org)->create()->id,
            'status' => 'pending',
        ]);

        TenantContext::set(null);

        $this->assertFalse(NominationCompletionPredicates::hasNoPendingCandidacies($election));
    }
}

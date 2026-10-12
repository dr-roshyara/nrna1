<?php

declare(strict_types=1);

namespace Tests\Isolated\Pbdigit274\Database;

use App\Models\Election;
use App\Models\Organisation;
use App\Models\User;
use App\Models\VoterSlug;
use App\Models\VoterSlugStep;
use App\Services\TenantContext;
use App\Services\VoterStepTrackingService;
use Tests\Isolated\Pbdigit274\IsolatedDatabaseTestCase;

/**
 * Tenant isolation of voter-slug step records against a REAL PostgreSQL database (both directions).
 *
 * WRITTEN, NOT RUN. Runs only through phpunit.pbdigit274.xml on the isolated database
 * `pbdigit274_isolated_test`, after G5 is authorised (RefreshDatabase = migrate:fresh there, behind the
 * identity guard). The database-free counterpart is tests/Unit/Models/VoterSlugStepTenantScopeTest.php.
 * Fixtures use the existing factories; their fit is unverified until the first run.
 */
final class VoterSlugStepTenantIsolationTest extends IsolatedDatabaseTestCase
{
    private Organisation $orgA;
    private Organisation $orgB;
    private VoterSlug $slugA;
    private VoterSlug $slugB;
    private Election $electionA;
    private Election $electionB;

    protected function setUp(): void
    {
        parent::setUp();

        [$this->orgA, $this->electionA, $this->slugA] = $this->tenantWithSlug();
        [$this->orgB, $this->electionB, $this->slugB] = $this->tenantWithSlug();
    }

    protected function tearDown(): void
    {
        TenantContext::clear();
        parent::tearDown();
    }

    /** @return array{Organisation, Election, VoterSlug} */
    private function tenantWithSlug(): array
    {
        $org = Organisation::factory()->tenant()->create();
        $election = Election::factory()->forOrganisation($org)->real()->create();
        $slug = VoterSlug::factory()->create([
            'user_id' => User::factory()->create()->id,
            'organisation_id' => $org->id,
            'election_id' => $election->id,
        ]);

        return [$org, $election, $slug];
    }

    private function recordStep(Organisation $tenant, VoterSlug $slug, Election $election, int $step): VoterSlugStep
    {
        TenantContext::set($tenant->id);

        return app(VoterStepTrackingService::class)->completeStep($slug, $election, $step);
    }

    public function test_a_recorded_step_carries_its_slugs_organisation(): void
    {
        $step = $this->recordStep($this->orgA, $this->slugA, $this->electionA, 1);

        $stored = VoterSlugStep::withoutGlobalScope('tenant')->findOrFail($step->id);
        $this->assertSame($this->orgA->id, $stored->organisation_id);
    }

    public function test_organisation_a_cannot_read_organisation_b_steps_and_vice_versa(): void
    {
        $this->recordStep($this->orgA, $this->slugA, $this->electionA, 1);
        $this->recordStep($this->orgB, $this->slugB, $this->electionB, 1);

        TenantContext::set($this->orgA->id);
        $this->assertSame([$this->orgA->id], VoterSlugStep::query()->pluck('organisation_id')->unique()->values()->all());
        $this->assertSame(0, VoterSlugStep::query()->where('voter_slug_id', $this->slugB->id)->count());

        TenantContext::set($this->orgB->id);
        $this->assertSame([$this->orgB->id], VoterSlugStep::query()->pluck('organisation_id')->unique()->values()->all());
        $this->assertSame(0, VoterSlugStep::query()->where('voter_slug_id', $this->slugA->id)->count());
    }

    /**
     * CHARACTERISATION: under tenant A, recording a step for B's slug stores it with B's organisation_id
     * (copied from the slug); A cannot read it back. The model does not refuse the write itself:
     * cross-tenant write protection rests on the request guards before the recorder.
     */
    public function test_characterisation_a_step_written_under_another_tenant_keeps_the_slug_organisation(): void
    {
        $step = $this->recordStep($this->orgA, $this->slugB, $this->electionB, 1);

        $this->assertSame($this->orgB->id, VoterSlugStep::withoutGlobalScope('tenant')->findOrFail($step->id)->organisation_id);
        TenantContext::set($this->orgA->id);
        $this->assertNull(VoterSlugStep::query()->find($step->id));
    }

    /**
     * INVARIANT: recording the same step for the same slug never creates a duplicate, even when the current
     * tenant context differs from the slug's organisation. The "already completed?" lookup is pinned to the
     * slug's organisation (VoterStepTrackingService::stepQuery); before that fix it was tenant-scoped and
     * stored the step twice.
     */
    public function test_the_same_step_is_never_stored_twice_across_tenant_contexts(): void
    {
        $first = $this->recordStep($this->orgB, $this->slugB, $this->electionB, 1);
        $second = $this->recordStep($this->orgA, $this->slugB, $this->electionB, 1);

        $rows = VoterSlugStep::withoutGlobalScope('tenant')
            ->where('voter_slug_id', $this->slugB->id)->where('step', 1)->count();
        $this->assertSame(1, $rows);
        $this->assertSame($first->id, $second->id, 'the second call returns the existing step');
    }
}

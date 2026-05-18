<?php

namespace Tests\Unit\Domain\Election;

use App\Application\Election\Services\ElectionLifecycleEngineImpl;
use App\Domain\Election\Enum\ElectionLifecycleState;
use App\Domain\Election\Services\ElectionLifecycleEngine;
use App\Models\Election;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Phase 1: SSOT Layer
 * Test: ElectionLifecycleEngine
 *
 * RED tests for the single source of truth computation.
 * Engine computes the canonical current state from all signals.
 */
class ElectionLifecycleEngineTest extends TestCase
{
    use RefreshDatabase;

    private ElectionLifecycleEngine $engine;

    protected function setUp(): void
    {
        parent::setUp();
        $this->engine = new ElectionLifecycleEngineImpl();
    }

    /**
     * Test: Engine returns snapshot for any election
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function engine_returns_snapshot_for_any_election(): void
    {
        $election = Election::factory()->create();

        $snapshot = $this->engine->compute($election);

        $this->assertNotNull($snapshot);
        $this->assertNotNull($snapshot->state);
        $this->assertIsBool($snapshot->canEdit);
        $this->assertIsBool($snapshot->canVote);
        $this->assertIsArray($snapshot->allowedActions);
    }

    /**
     * Test: Draft state when election just created
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function draft_state_when_election_just_created(): void
    {
        $election = Election::factory()->create([
            'administration_completed' => false,
        ]);

        $state = $this->engine->getState($election);

        $this->assertEquals(ElectionLifecycleState::Draft, $state);
    }

    /**
     * Test: Setup state when administration_completed is true
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function setup_state_when_administration_completed(): void
    {
        $election = Election::factory()->create([
            'administration_completed' => true,
            'nomination_completed' => false,
            'voting_starts_at' => now()->addDays(10),
            'voting_ends_at' => now()->addDays(11),
            'results_published_at' => null,
        ]);

        $state = $this->engine->getState($election);

        $this->assertEquals(ElectionLifecycleState::Setup, $state);
    }

    /**
     * Test: Draft permissions allow editing and voter management
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function draft_state_provides_correct_permissions(): void
    {
        $election = Election::factory()->create([
            'administration_completed' => false,
        ]);

        $snapshot = $this->engine->compute($election);

        $this->assertTrue($snapshot->canEdit);
        $this->assertFalse($snapshot->canVote);
        $this->assertTrue($snapshot->canManageVoters);
        $this->assertFalse($snapshot->canPublishResults);
    }

    /**
     * Test: Engine is injectable via container
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function engine_is_injectable(): void
    {
        $resolved = app(ElectionLifecycleEngine::class);

        $this->assertInstanceOf(ElectionLifecycleEngine::class, $resolved);
    }

    /**
     * Test: Snapshot is immutable (computing twice returns equivalent snapshots)
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function snapshot_is_deterministic(): void
    {
        $election = Election::factory()->create([
            'administration_completed' => false,
        ]);

        $snapshot1 = $this->engine->compute($election);
        $snapshot2 = $this->engine->compute($election);

        $this->assertEquals($snapshot1->state, $snapshot2->state);
        $this->assertEquals($snapshot1->canEdit, $snapshot2->canEdit);
        $this->assertEquals($snapshot1->canVote, $snapshot2->canVote);
    }
}

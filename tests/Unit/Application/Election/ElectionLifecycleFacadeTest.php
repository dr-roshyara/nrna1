<?php

namespace Tests\Unit\Application\Election;

use App\Application\Election\Facades\ElectionLifecycle;
use App\Application\Election\Services\ElectionLifecycleEngineImpl;
use App\Domain\Election\Enum\ElectionLifecycleState;
use App\Exceptions\DeprecatedQueryException;
use App\Models\Election;
use Tests\TestCase;

/**
 * Phase 2.3: Lifecycle Facade
 * Test: ElectionLifecycle (Unified Consumption API)
 *
 * RED tests for facade-layer SSOT enforcement.
 * Ensures all consumption flows through single entry point.
 */
class ElectionLifecycleFacadeTest extends TestCase
{
    /**
     * Test: Facade wraps election and provides snapshot
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function facade_wraps_election_and_provides_snapshot(): void
    {
        $election = Election::factory()->create();
        $facade = ElectionLifecycle::of($election);

        $this->assertNotNull($facade->snapshot());
        $this->assertInstanceOf(\App\Domain\Election\ValueObjects\ElectionLifecycleSnapshot::class, $facade->snapshot());
    }

    /**
     * Test: Facade returns correct state
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function facade_returns_current_state(): void
    {
        $election = Election::factory()->create();
        $facade = ElectionLifecycle::of($election);

        $this->assertEquals(ElectionLifecycleState::Draft, $facade->state());
    }

    /**
     * Test: Facade can be instantiated with pre-computed snapshot
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function facade_accepts_pre_computed_snapshot(): void
    {
        $election = Election::factory()->create();
        $engine = app(ElectionLifecycleEngineImpl::class);
        $snapshot = $engine->compute($election);

        $facade = ElectionLifecycle::withSnapshot($election, $snapshot);

        $this->assertEquals($snapshot, $facade->snapshot());
    }

    /**
     * Test: Facade delegates canVote to snapshot
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function facade_canVote_delegates_to_snapshot(): void
    {
        $election = Election::factory()->create([
            'state' => 'voting_active',
            'voting_starts_at' => now()->subHour(),
            'voting_ends_at' => now()->addHour(),
        ]);

        $facade = ElectionLifecycle::of($election);

        $this->assertTrue($facade->canVote());
    }

    /**
     * Test: Facade delegates canEdit to snapshot
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function facade_canEdit_delegates_to_snapshot(): void
    {
        $election = Election::factory()->create();
        $facade = ElectionLifecycle::of($election);

        $this->assertTrue($facade->canEdit());
    }

    /**
     * Test: Facade delegates canManageVoters to snapshot
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function facade_canManageVoters_delegates_to_snapshot(): void
    {
        $election = Election::factory()->create();
        $facade = ElectionLifecycle::of($election);

        $this->assertTrue($facade->canManageVoters());
    }

    /**
     * Test: Facade delegates canPublishResults to snapshot
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function facade_canPublishResults_delegates_to_snapshot(): void
    {
        $election = Election::factory()->create();
        $facade = ElectionLifecycle::of($election);

        $this->assertFalse($facade->canPublishResults());
    }

    /**
     * Test: Facade exposes isTerminal
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function facade_exposes_isTerminal(): void
    {
        $election = Election::factory()->create();
        $facade = ElectionLifecycle::of($election);

        $this->assertFalse($facade->isTerminal());
    }

    /**
     * Test: Facade exposes isInSetup
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function facade_exposes_isInSetup(): void
    {
        $election = Election::factory()->create();
        $facade = ElectionLifecycle::of($election);

        $this->assertTrue($facade->isInSetup());
    }

    /**
     * Test: Facade exposes isVotingPhase
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function facade_exposes_isVotingPhase(): void
    {
        $election = Election::factory()->create([
            'state' => 'voting_active',
            'voting_starts_at' => now()->subHour(),
            'voting_ends_at' => now()->addHour(),
        ]);

        $facade = ElectionLifecycle::of($election);

        $this->assertTrue($facade->isVotingPhase());
    }

    /**
     * Test: Facade exposes isLocked
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function facade_exposes_isLocked(): void
    {
        $election = Election::factory()->create();
        $facade = ElectionLifecycle::of($election);

        $this->assertFalse($facade->isLocked());
    }

    /**
     * Test: Facade exposes blockedReason
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function facade_exposes_blockedReason(): void
    {
        $election = Election::factory()->create();
        $facade = ElectionLifecycle::of($election);

        // Draft state is blocked from actions
        $this->assertIsString($facade->blockedReason());
    }

    /**
     * Test: Facade exposes allowedActions
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function facade_exposes_allowedActions(): void
    {
        $election = Election::factory()->create();
        $facade = ElectionLifecycle::of($election);

        $this->assertIsArray($facade->allowedActions());
    }

    /**
     * Test: Facade exposes isActionAllowed (authority checking)
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function facade_exposes_isActionAllowed(): void
    {
        $election = Election::factory()->create();
        $facade = ElectionLifecycle::of($election);

        $this->assertIsBool($facade->isActionAllowed('some_action'));
    }

    /**
     * Test: Facade preserves canTransitionTo for backward compatibility (deprecated)
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function facade_preserves_canTransitionTo_for_backward_compatibility(): void
    {
        $election = Election::factory()->create();
        $facade = ElectionLifecycle::of($election);

        $this->assertIsBool($facade->canTransitionTo('some_action'));
        $this->assertEquals(
            $facade->isActionAllowed('some_action'),
            $facade->canTransitionTo('some_action')
        );
    }

    /**
     * Test: Facade guards queries against deprecated fields
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function facade_guards_queries_against_deprecated_fields(): void
    {
        $election = Election::factory()->create();
        $facade = ElectionLifecycle::of($election);

        $this->expectException(DeprecatedQueryException::class);

        $facade->assertQueryAllowed(
            ['status' => 'active'],
            'TestContext'
        );
    }

    /**
     * Test: Facade allows non-deprecated field queries
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function facade_allows_non_deprecated_field_queries(): void
    {
        $election = Election::factory()->create();
        $facade = ElectionLifecycle::of($election);

        // Should not throw
        $facade->assertQueryAllowed(
            ['voting_starts_at' => now()],
            'TestContext'
        );

        $this->assertTrue(true);
    }

    /**
     * Test: Facade exposes election passthrough
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function facade_exposes_election_passthrough(): void
    {
        $election = Election::factory()->create();
        $facade = ElectionLifecycle::of($election);

        $this->assertEquals($election->id, $facade->id());
        $this->assertEquals($election->name, $facade->name());
        $this->assertEquals($election->id, $facade->election()->id);
    }

    /**
     * Test: Facade is injectable
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function facade_can_be_used_in_type_hints(): void
    {
        $election = Election::factory()->create();

        $facade = ElectionLifecycle::of($election);

        $this->assertInstanceOf(ElectionLifecycle::class, $facade);
    }
}

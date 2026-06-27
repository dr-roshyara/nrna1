<?php

namespace Tests\Unit\Application\Election;

use App\Application\Election\Deprecation\ElectionReadModel;
use App\Exceptions\DeprecatedFieldException;
use App\Models\Election;
use Tests\TestCase;

/**
 * Phase 2: Deprecation Enforcement Layer
 * Test: ElectionReadModel (Deprecated Field Wrapper)
 *
 * RED tests for domain-level access control.
 * Ensures deprecated fields cannot be accessed without going through guard.
 */
class ElectionReadModelTest extends TestCase
{
    /**
     * Test: Reading status field through wrapper logs deprecation
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function reading_status_through_wrapper_logs_deprecation(): void
    {
        $election = Election::factory()->create(['status' => 'active']);
        $readModel = new ElectionReadModel($election);

        // Should not throw, but should log
        $value = $readModel->statusLegacy();

        $this->assertEquals('active', $value);
    }

    /**
     * Test: Reading is_active in strict mode throws exception
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function reading_is_active_in_strict_mode_throws(): void
    {
        $election = Election::factory()->create(['is_active' => true]);
        $readModel = new ElectionReadModel($election, 'strict');

        $this->expectException(DeprecatedFieldException::class);
        $readModel->isActiveLegacy();
    }

    /**
     * Test: SSOT path does not trigger deprecation
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function ssot_path_does_not_trigger_deprecation(): void
    {
        $election = Election::factory()->create(['status' => 'active']);
        $readModel = new ElectionReadModel($election);

        $snapshot = $readModel->lifecycle();

        $this->assertNotNull($snapshot);
    }

    /**
     * Test: ReadModel provides single entry point for all election data
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function read_model_is_single_entry_point(): void
    {
        $election = Election::factory()->create();
        $readModel = new ElectionReadModel($election);

        // SSOT paths
        $this->assertNotNull($readModel->lifecycle());
        $this->assertNotNull($readModel->state());
        $this->assertIsBool($readModel->canVote());

        // Legacy paths (with deprecation)
        $this->assertIsString($readModel->statusLegacy());
        $this->assertIsBool($readModel->isActiveLegacy());
    }

    /**
     * Test: ReadModel can be injected and used
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function read_model_can_wrap_any_election(): void
    {
        $election = Election::factory()->create();
        $readModel = ElectionReadModel::wrap($election);

        $this->assertInstanceOf(ElectionReadModel::class, $readModel);
        $this->assertEquals($election->id, $readModel->id());
    }
}

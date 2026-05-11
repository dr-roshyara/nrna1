<?php

declare(strict_types=1);

namespace Tests\Integration\Contexts\Governance\Infrastructure\Projections;

use App\Contexts\Governance\Domain\Committee\CommitteeGovernanceInterpreter;
use App\Contexts\Governance\Domain\Committee\Policies\ConstitutionalLegitimacyPolicy;
use App\Contexts\Governance\Domain\Committee\Policies\OperationalStatePolicy;
use App\Contexts\Governance\Domain\Committee\Policies\TemporalGovernancePolicy;
use App\Contexts\Governance\Infrastructure\Projections\CommitteeGovernanceProjectionModel;
use App\Contexts\Governance\Infrastructure\Projections\CommitteeGovernanceProjector;
use App\Contexts\Governance\Infrastructure\Projections\ProcessedEventModel;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Membership\Infrastructure\Models\CommitteeModel;
use App\Models\Organisation;
use DateTimeImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class CommitteeGovernanceProjectorTest extends TestCase
{
    use RefreshDatabase;

    private CommitteeGovernanceProjector $projector;

    protected function setUp(): void
    {
        parent::setUp();

        $interpreter = new CommitteeGovernanceInterpreter(
            new OperationalStatePolicy(),
            new TemporalGovernancePolicy(),
            new ConstitutionalLegitimacyPolicy(),
        );

        $this->projector = new CommitteeGovernanceProjector($interpreter);
    }

    public function test_rebuilds_projection_for_committee(): void
    {
        $org = Organisation::factory()->create();

        CommitteeModel::create([
            'id' => '01ARZ3NDEKTSV4RRFFQ69G5FAV',
            'organisation_id' => $org->id,
            'name' => 'ICC Global',
            'code' => 'ICC-001',
            'type' => 'central',
            'level' => 0,
            'status' => 'active',
            'formation_date' => '2025-01-01',
            'term_end_date' => '2027-12-31',
        ]);

        $committeeId = CommitteeId::fromString('01ARZ3NDEKTSV4RRFFQ69G5FAV');
        $now = new DateTimeImmutable('2026-05-10T12:00:00Z');
        $eventId = 'evt-001';
        $eventOccurredAt = new DateTimeImmutable('2026-05-10T11:59:00Z');

        $this->projector->rebuild($committeeId, $now, $eventId, $eventOccurredAt);

        $record = CommitteeGovernanceProjectionModel::find('01ARZ3NDEKTSV4RRFFQ69G5FAV');

        $this->assertNotNull($record);
        $this->assertSame('01ARZ3NDEKTSV4RRFFQ69G5FAV', $record->committee_id);
        $this->assertSame(1, $record->projection_version);
        $this->assertSame($eventId, $record->last_event_id);
        $this->assertSame('ACTIVE', $record->operational_state);
        $this->assertSame('VALID', $record->temporal_state);
        // Without authority chain, legitimacy defaults to UNAUTHORIZED
        // (Full authority resolution is wired in Step 6.6 use case)
        $this->assertSame('UNAUTHORIZED', $record->legitimacy);
    }

    public function test_rebuild_all_rebuilds_all_committees(): void
    {
        $org = Organisation::factory()->create();

        CommitteeModel::create([
            'id' => '01ARZ3NDEKTSV4RRFFQ69G5FAV',
            'organisation_id' => $org->id,
            'name' => 'ICC Global',
            'code' => 'ICC-001',
            'type' => 'central',
            'level' => 0,
            'status' => 'active',
        ]);

        CommitteeModel::create([
            'id' => '01ARZ3NDEKTSV4RRFFQ69G5FBW',
            'organisation_id' => $org->id,
            'name' => 'Asia Continent',
            'code' => 'CONT-ASIA',
            'type' => 'central',
            'level' => 1,
            'status' => 'active',
        ]);

        $now = new DateTimeImmutable('2026-05-10T12:00:00Z');

        $this->projector->rebuildAll($now);

        $this->assertCount(
            2,
            CommitteeGovernanceProjectionModel::all()
        );
    }

    public function test_idempotent_event_processing(): void
    {
        $org = Organisation::factory()->create();

        CommitteeModel::create([
            'id' => '01ARZ3NDEKTSV4RRFFQ69G5FAV',
            'organisation_id' => $org->id,
            'name' => 'ICC Global',
            'code' => 'ICC-001',
            'type' => 'central',
            'level' => 0,
            'status' => 'active',
        ]);

        $committeeId = CommitteeId::fromString('01ARZ3NDEKTSV4RRFFQ69G5FAV');
        $now = new DateTimeImmutable('2026-05-10T12:00:00Z');
        $eventId = 'evt-002';
        $eventOccurredAt = new DateTimeImmutable('2026-05-10T11:59:00Z');

        // Process same event twice
        $this->projector->onEvent($committeeId, $now, $eventId, 'CommitteeCreated', $eventOccurredAt);
        $this->projector->onEvent($committeeId, $now, $eventId, 'CommitteeCreated', $eventOccurredAt);

        // Only one processed event record should exist
        $this->assertCount(1, ProcessedEventModel::all());

        // Projection should exist with correct event ID
        $record = CommitteeGovernanceProjectionModel::find('01ARZ3NDEKTSV4RRFFQ69G5FAV');
        $this->assertNotNull($record);
        $this->assertSame($eventId, $record->last_event_id);
    }

    public function test_projection_updates_on_status_change(): void
    {
        $org = Organisation::factory()->create();

        CommitteeModel::create([
            'id' => '01ARZ3NDEKTSV4RRFFQ69G5FAV',
            'organisation_id' => $org->id,
            'name' => 'ICC Global',
            'code' => 'ICC-001',
            'type' => 'central',
            'level' => 0,
            'status' => 'active',
        ]);

        $committeeId = CommitteeId::fromString('01ARZ3NDEKTSV4RRFFQ69G5FAV');
        $now = new DateTimeImmutable('2026-05-10T12:00:00Z');

        // Initial rebuild
        $this->projector->rebuild($committeeId, $now, 'evt-001', $now);

        // Update committee status
        CommitteeModel::withoutGlobalScopes()
            ->where('id', '01ARZ3NDEKTSV4RRFFQ69G5FAV')
            ->update(['status' => 'suspended']);

        // Rebuild projection
        $this->projector->rebuild($committeeId, $now, 'evt-002', $now);

        $record = CommitteeGovernanceProjectionModel::find('01ARZ3NDEKTSV4RRFFQ69G5FAV');
        $this->assertNotNull($record);
        $this->assertSame('SUSPENDED', $record->operational_state);
    }

    public function test_can_act_and_is_fully_operational_are_stored(): void
    {
        $org = Organisation::factory()->create();

        CommitteeModel::create([
            'id' => '01ARZ3NDEKTSV4RRFFQ69G5FAV',
            'organisation_id' => $org->id,
            'name' => 'ICC Global',
            'code' => 'ICC-001',
            'type' => 'central',
            'level' => 0,
            'status' => 'active',
            'formation_date' => '2025-01-01',
            'term_end_date' => '2027-12-31',
        ]);

        $committeeId = CommitteeId::fromString('01ARZ3NDEKTSV4RRFFQ69G5FAV');
        $now = new DateTimeImmutable('2026-05-10T12:00:00Z');

        $this->projector->rebuild($committeeId, $now, 'evt-001', $now);

        $record = CommitteeGovernanceProjectionModel::find('01ARZ3NDEKTSV4RRFFQ69G5FAV');

        $this->assertNotNull($record);
        // active + valid term + UNAUTHORIZED legitimacy (no authority chain)
        // canAct = active AND legitimate AND not expired → false (legitimacy is UNAUTHORIZED)
        // isFullyOperational = active AND valid AND legitimate → false (legitimacy is UNAUTHORIZED)
        $this->assertFalse($record->can_act, 'Should be false when legitimacy is UNAUTHORIZED');
        $this->assertFalse($record->is_fully_operational, 'Should be false when legitimacy is UNAUTHORIZED');
        $this->assertNotNull($record->rebuilt_at, 'rebuilt_at should be set');
    }

    public function test_same_event_stream_produces_deterministic_projection(): void
    {
        $org = Organisation::factory()->create();

        CommitteeModel::create([
            'id' => '01ARZ3NDEKTSV4RRFFQ69G5FAV',
            'organisation_id' => $org->id,
            'name' => 'ICC Global',
            'code' => 'ICC-001',
            'type' => 'central',
            'level' => 0,
            'status' => 'active',
            'formation_date' => '2025-01-01',
            'term_end_date' => '2027-12-31',
        ]);

        $committeeId = CommitteeId::fromString('01ARZ3NDEKTSV4RRFFQ69G5FAV');
        $now = new DateTimeImmutable('2026-05-10T12:00:00Z');

        // First rebuild
        $this->projector->rebuild($committeeId, $now, 'evt-001', $now);
        $first = CommitteeGovernanceProjectionModel::find('01ARZ3NDEKTSV4RRFFQ69G5FAV');

        // Simulate rebuild from scratch (e.g. after schema migration)
        $this->projector->rebuild($committeeId, $now, 'evt-rebuild', $now);
        $second = CommitteeGovernanceProjectionModel::find('01ARZ3NDEKTSV4RRFFQ69G5FAV');

        $this->assertNotNull($first);
        $this->assertNotNull($second);
        $this->assertSame($first->operational_state, $second->operational_state);
        $this->assertSame($first->temporal_state, $second->temporal_state);
        $this->assertSame($first->legitimacy, $second->legitimacy);
        $this->assertSame($first->can_act, $second->can_act);
        $this->assertSame($first->is_fully_operational, $second->is_fully_operational);
    }
}

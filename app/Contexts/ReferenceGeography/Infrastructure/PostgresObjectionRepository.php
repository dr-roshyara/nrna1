<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Infrastructure;

use App\Contexts\ReferenceGeography\Application\ObjectionChangedConcurrently;
use App\Contexts\ReferenceGeography\Application\Ports\ObjectionRepository;
use App\Contexts\ReferenceGeography\Application\StoredObjection;
use App\Contexts\ReferenceGeography\Domain\ClaimId;
use App\Contexts\ReferenceGeography\Domain\Objection;
use App\Contexts\ReferenceGeography\Domain\ObjectionAssessment;
use App\Contexts\ReferenceGeography\Domain\ObjectionId;
use App\Contexts\ReferenceGeography\Domain\ObjectionSnapshot;
use App\Contexts\ReferenceGeography\Domain\ObjectionState;
use App\Contexts\ReferenceGeography\Domain\PersonReference;
use App\Contexts\ReferenceGeography\Domain\RevisionId;
use Illuminate\Database\ConnectionInterface;

/**
 * Objections in the central database (G-6b). Rows become aggregates only through Objection::fromSnapshot; a row the
 * domain refuses is reported as corrupt. Optimistic saves by row version.
 */
final class PostgresObjectionRepository implements ObjectionRepository
{
    public function __construct(private readonly ConnectionInterface $db)
    {
    }

    public function add(Objection $objection): void
    {
        if ($this->db->selectOne('SELECT 1 AS x FROM refgeo_objections WHERE id = ?::uuid', [$objection->id()->value()]) !== null) {
            throw new \RuntimeException('The objection already exists.');
        }
        $this->db->table('refgeo_objections')->insert(['id' => $objection->id()->value()] + $this->columns($objection->snapshot()));
    }

    public function get(ObjectionId $id): StoredObjection
    {
        $row = $this->db->selectOne('SELECT * FROM refgeo_objections WHERE id = ?::uuid', [$id->value()]);
        if ($row === null) {
            throw new \OutOfBoundsException("No objection {$id->value()}.");
        }
        $p = static fn (?string $v): ?PersonReference => $v === null ? null : PersonReference::fromString($v);
        try {
            $snapshot = new ObjectionSnapshot(
                ObjectionId::fromString($row->id),
                ObjectionState::from($row->state),
                $row->organisation_id,
                ClaimId::fromString($row->target_claim),
                $row->statement,
                json_decode($row->evidence, true, 4, JSON_THROW_ON_ERROR),
                PersonReference::fromString($row->recorded_by),
                $row->authentic === null ? null : new ObjectionAssessment((bool) $row->authentic, (bool) $row->mandate, (bool) $row->applicable),
                $p($row->assessed_by),
                $p($row->decided_by),
                $row->revision_id === null ? null : RevisionId::fromString($row->revision_id),
                $row->reason,
            );
        } catch (\InvalidArgumentException | \ValueError | \JsonException $e) {
            throw new \UnexpectedValueException("Stored objection {$id->value()} is corrupt.", 0, $e);
        }

        return new StoredObjection(Objection::fromSnapshot($snapshot), (int) $row->row_version);
    }

    public function save(Objection $objection, int $expectedRowVersion): int
    {
        $c = $this->columns($objection->snapshot());
        $changed = $this->db->update('UPDATE refgeo_objections SET state = ?, authentic = ?, mandate = ?, applicable = ?, assessed_by = ?, decided_by = ?,
            revision_id = ?::uuid, reason = ?, row_version = row_version + 1, updated_at = clock_timestamp() WHERE id = ?::uuid AND row_version = ?',
            [$c['state'], $c['authentic'], $c['mandate'], $c['applicable'], $c['assessed_by'], $c['decided_by'], $c['revision_id'], $c['reason'], $objection->id()->value(), $expectedRowVersion]);
        if ($changed !== 1) {
            throw new ObjectionChangedConcurrently($objection->id()->value());
        }

        return $expectedRowVersion + 1;
    }

    public function evidenceCitedAgainst(ClaimId $target): array
    {
        return array_map(static fn (object $r): string => $r->e, $this->db->select(
            'SELECT DISTINCT e FROM refgeo_objections, jsonb_array_elements_text(evidence) AS e WHERE target_claim = ?::uuid ORDER BY e', [$target->value()]));
    }

    /** @return array<string, mixed> */
    private function columns(ObjectionSnapshot $s): array
    {
        return [
            'state' => $s->state->value,
            'organisation_id' => $s->organisationId,
            'target_claim' => $s->target->value(),
            'statement' => $s->statement,
            'evidence' => json_encode($s->evidence, JSON_THROW_ON_ERROR),
            'recorded_by' => $s->recordedBy->value(),
            'authentic' => $s->assessment?->authentic,
            'mandate' => $s->assessment?->mandate,
            'applicable' => $s->assessment?->applicable,
            'assessed_by' => $s->assessedBy?->value(),
            'decided_by' => $s->decidedBy?->value(),
            'revision_id' => $s->revision?->value(),
            'reason' => $s->reason,
        ];
    }
}

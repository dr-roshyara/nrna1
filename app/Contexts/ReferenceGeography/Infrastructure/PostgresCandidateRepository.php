<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Infrastructure;

use App\Contexts\ReferenceGeography\Application\CandidateChangedConcurrently;
use App\Contexts\ReferenceGeography\Application\Ports\CandidateRepository;
use App\Contexts\ReferenceGeography\Application\StoredCandidate;
use App\Contexts\ReferenceGeography\Domain\Candidate;
use App\Contexts\ReferenceGeography\Domain\CandidateId;
use App\Contexts\ReferenceGeography\Domain\CandidateSnapshot;
use App\Contexts\ReferenceGeography\Domain\CandidateState;
use App\Contexts\ReferenceGeography\Domain\CandidateSubmitter;
use App\Contexts\ReferenceGeography\Domain\PersonReference;
use App\Contexts\ReferenceGeography\Domain\ReferenceUnitId;
use App\Contexts\ReferenceGeography\Domain\RevisionId;
use Illuminate\Database\ConnectionInterface;

/**
 * Candidates in the central database (G-6a). Rows become aggregates only through Candidate::fromSnapshot; a row the
 * domain refuses is reported as corrupt (\UnexpectedValueException). Saves are optimistic: against the row version
 * the candidate was loaded at.
 */
final class PostgresCandidateRepository implements CandidateRepository
{
    public function __construct(private readonly ConnectionInterface $db)
    {
    }

    public function add(Candidate $candidate): void
    {
        if ($this->db->selectOne('SELECT 1 AS x FROM refgeo_candidates WHERE id = ?::uuid', [$candidate->id()->value()]) !== null) {
            throw new \RuntimeException('The candidate already exists.');
        }
        $this->db->table('refgeo_candidates')->insert(['id' => $candidate->id()->value()] + $this->columns($candidate->snapshot()));
    }

    public function get(CandidateId $id): StoredCandidate
    {
        $row = $this->db->selectOne('SELECT * FROM refgeo_candidates WHERE id = ?::uuid', [$id->value()]);
        if ($row === null) {
            throw new \OutOfBoundsException("No candidate {$id->value()}.");
        }
        $p = static fn (?string $v): ?PersonReference => $v === null ? null : PersonReference::fromString($v);
        try {
            $snapshot = new CandidateSnapshot(
                CandidateId::fromString($row->id),
                CandidateState::from($row->state),
                CandidateSubmitter::of($row->submitter_kind, $row->submitter_ref),
                $row->summary,
                $row->subject_unit === null ? null : ReferenceUnitId::fromString($row->subject_unit),
                json_decode($row->evidence, true, 4, JSON_THROW_ON_ERROR),
                PersonReference::fromString($row->recorded_by),
                $p($row->verification_by),
                $p($row->decided_by),
                $row->revision_id === null ? null : RevisionId::fromString($row->revision_id),
                $row->linked_to === null ? null : CandidateId::fromString($row->linked_to),
                $row->reason,
            );
        } catch (\InvalidArgumentException | \ValueError | \JsonException $e) {
            throw new \UnexpectedValueException("Stored candidate {$id->value()} is corrupt.", 0, $e);
        }

        return new StoredCandidate(Candidate::fromSnapshot($snapshot), (int) $row->row_version);
    }

    public function save(Candidate $candidate, int $expectedRowVersion): int
    {
        $c = $this->columns($candidate->snapshot());
        $changed = $this->db->update('UPDATE refgeo_candidates SET state = ?, verification_by = ?, decided_by = ?, revision_id = ?::uuid, linked_to = ?::uuid,
            reason = ?, row_version = row_version + 1, updated_at = clock_timestamp() WHERE id = ?::uuid AND row_version = ?',
            [$c['state'], $c['verification_by'], $c['decided_by'], $c['revision_id'], $c['linked_to'], $c['reason'], $candidate->id()->value(), $expectedRowVersion]);
        if ($changed !== 1) {
            throw new CandidateChangedConcurrently($candidate->id()->value());
        }

        return $expectedRowVersion + 1;
    }

    /** @return array<string, mixed> */
    private function columns(CandidateSnapshot $s): array
    {
        return [
            'state' => $s->state->value,
            'submitter_kind' => $s->submitter->kind,
            'submitter_ref' => $s->submitter->reference,
            'summary' => $s->summary,
            'subject_unit' => $s->subject?->value(),
            'evidence' => json_encode($s->evidence, JSON_THROW_ON_ERROR),
            'recorded_by' => $s->recordedBy->value(),
            'verification_by' => $s->verificationBy?->value(),
            'decided_by' => $s->decidedBy?->value(),
            'revision_id' => $s->revision?->value(),
            'linked_to' => $s->linkedTo?->value(),
            'reason' => $s->reason,
        ];
    }
}

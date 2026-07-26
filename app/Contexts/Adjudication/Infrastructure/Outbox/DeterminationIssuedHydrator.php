<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Infrastructure\Outbox;

use App\Contexts\Adjudication\Domain\Determination\ChallengeRef;
use App\Contexts\Adjudication\Domain\Determination\ContestedOutcomeRef;
use App\Contexts\Adjudication\Domain\Determination\DeterminationId;
use App\Contexts\Adjudication\Domain\Determination\DeterminationOutcome;
use App\Contexts\Adjudication\Domain\Determination\ElectionId;
use App\Contexts\Adjudication\Domain\Determination\EvidenceEnvelopeRef;
use App\Contexts\Adjudication\Domain\Determination\EvidenceSet;
use App\Contexts\Adjudication\Domain\Determination\IssuedByAuthority;
use App\Contexts\Adjudication\Domain\Determination\Jurisdiction;
use App\Contexts\Adjudication\Domain\Determination\Legitimacy;
use App\Contexts\Adjudication\Domain\Determination\Reason;
use App\Contexts\Adjudication\Domain\Determination\TargetId;
use App\Contexts\Adjudication\Domain\Determination\TargetType;
use App\Contexts\Adjudication\Domain\Events\DeterminationIssued;
use App\Contexts\Shared\Infrastructure\Outbox\EventHydrator;
use DateTimeImmutable;

/**
 * Context-owned hydrator for DeterminationIssued. Inverse of
 * OutboxEventAdapter::writeDeterminationIssued() — the two methods define the
 * wire contract together and must evolve together (ADR-T5: additive fields
 * bump schema_version and are tolerated here; breaking changes are a new
 * event name).
 *
 * Traceability — Blueprint: Push B §6, §16 step 4 · ADR: ADR-T3, ADR-T5 ·
 * Matrix: Push B — Event Registry · Context: Adjudication (Infrastructure).
 */
final class DeterminationIssuedHydrator implements EventHydrator
{
    public function eventType(): string
    {
        return 'DeterminationIssued';
    }

    public function hydrate(array $payload): object
    {
        $version = $this->supportedVersion($payload);

        return new DeterminationIssued(
            determinationId: DeterminationId::fromString($this->required($payload, 'determinationId')),
            challengeRef: ChallengeRef::fromString($this->required($payload, 'challengeRef')),
            outcome: DeterminationOutcome::from($this->required($payload, 'outcome')),
            legitimacy: Legitimacy::from($this->required($payload, 'legitimacy')),
            reason: Reason::fromString($this->required($payload, 'reason')),
            evidenceEnvelopeRef: EvidenceEnvelopeRef::fromString($this->required($payload, 'evidenceEnvelopeRef')),
            issuedByAuthority: IssuedByAuthority::fromString($this->required($payload, 'issuedByAuthority')),
            jurisdiction: Jurisdiction::fromString($this->required($payload, 'jurisdiction')),
            contestedOutcome: $this->hydrateContestedOutcome($payload),
            evidenceSet: $this->hydrateEvidenceSet($payload, $version),
            occurredAt: new DateTimeImmutable($this->required($payload, 'occurredAt')),
        );
    }

    /**
     * Version window (Event Registry rule: vCurrent + vPrevious ONLY).
     * WP-1 / ADR-T22: vCurrent = 3, vPrevious = 2 — v1 is RETIRED and rejected
     * loudly (schema_version absent counts as 1). Pre-deploy check for the
     * window shift: zero pending v1 DeterminationIssued outbox rows.
     *
     * @param array<string, mixed> $payload
     */
    private function supportedVersion(array $payload): int
    {
        $sv = $payload['schema_version'] ?? 1;
        $version = is_numeric($sv) ? (int) $sv : -1;

        if ($version < 2 || $version > 3) {
            throw new \InvalidArgumentException(sprintf(
                'Unsupported DeterminationIssued schema_version: %s (window: v3 current, v2 previous — v1 retired per ADR-T22).',
                is_scalar($sv) ? (string) $sv : gettype($sv),
            ));
        }

        return $version;
    }

    /**
     * Schema v2 content (ADR-PL-01): the contested-outcome reference — VO
     * reconstruction is an Infrastructure concern, not on the VO.
     *
     * @param array<string, mixed> $payload
     */
    private function hydrateContestedOutcome(array $payload): ?ContestedOutcomeRef
    {
        $co = $payload['contestedOutcome'] ?? null;
        if (!is_array($co)) {
            return null;
        }

        return ContestedOutcomeRef::of(
            ElectionId::fromString($this->requiredIn($co, 'electionId')),
            TargetType::from($this->requiredIn($co, 'type')),
            TargetId::fromString($this->requiredIn($co, 'targetId')),
        );
    }

    /**
     * Schema v3 content (ADR-T22): the fixed considered-evidence set. Required
     * at v3 (the aggregate fixes it at issuance — a v3 payload without it is
     * malformed); null for v2 payloads (vPrevious tolerance).
     *
     * @param array<string, mixed> $payload
     */
    private function hydrateEvidenceSet(array $payload, int $version): ?EvidenceSet
    {
        if ($version < 3) {
            return null;
        }

        $set = $payload['evidenceSet'] ?? null;
        if (!is_array($set) || $set === []) {
            throw new \InvalidArgumentException(
                'DeterminationIssued v3 payload is missing required field "evidenceSet" (the set is fixed at issuance — ADR-T22).'
            );
        }

        $refs = [];
        foreach ($set as $ref) {
            if (!is_string($ref) || $ref === '') {
                throw new \InvalidArgumentException(
                    'DeterminationIssued evidenceSet must contain only non-empty string references.'
                );
            }
            $refs[] = $ref;
        }

        return EvidenceSet::fromRefs(...$refs);
    }

    /**
     * @param array<mixed, mixed> $data
     */
    private function requiredIn(array $data, string $field): string
    {
        $value = $data[$field] ?? null;
        if (!is_string($value) || $value === '') {
            throw new \InvalidArgumentException(sprintf(
                'DeterminationIssued contestedOutcome is missing required field "%s".',
                $field,
            ));
        }

        return $value;
    }

    /**
     * @param array<string, mixed> $payload
     */
    private function required(array $payload, string $field): string
    {
        $value = $payload[$field] ?? null;

        if (!is_string($value) || $value === '') {
            throw new \InvalidArgumentException(sprintf(
                'DeterminationIssued payload is missing required field "%s".',
                $field,
            ));
        }

        return $value;
    }
}

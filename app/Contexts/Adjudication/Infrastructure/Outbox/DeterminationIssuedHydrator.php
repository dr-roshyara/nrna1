<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Infrastructure\Outbox;

use App\Contexts\Adjudication\Domain\Determination\ChallengeRef;
use App\Contexts\Adjudication\Domain\Determination\ContestedOutcomeRef;
use App\Contexts\Adjudication\Domain\Determination\DeterminationId;
use App\Contexts\Adjudication\Domain\Determination\DeterminationOutcome;
use App\Contexts\Adjudication\Domain\Determination\ElectionId;
use App\Contexts\Adjudication\Domain\Determination\EvidenceEnvelopeRef;
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
            occurredAt: new DateTimeImmutable($this->required($payload, 'occurredAt')),
        );
    }

    /**
     * Version dispatch (ADR-T5, Event Registry: vCurrent + vPrevious). Payload
     * `schema_version` absent = 1 → no contested outcome; >= 2 → reconstruct the
     * VO here (reconstruction is an Infrastructure concern, not on the VO).
     *
     * @param array<string, mixed> $payload
     */
    private function hydrateContestedOutcome(array $payload): ?ContestedOutcomeRef
    {
        $sv = $payload['schema_version'] ?? 1;
        $version = is_numeric($sv) ? (int) $sv : 1;

        $co = $payload['contestedOutcome'] ?? null;
        if ($version < 2 || !is_array($co)) {
            return null;
        }

        return ContestedOutcomeRef::of(
            ElectionId::fromString($this->requiredIn($co, 'electionId')),
            TargetType::from($this->requiredIn($co, 'type')),
            TargetId::fromString($this->requiredIn($co, 'targetId')),
        );
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

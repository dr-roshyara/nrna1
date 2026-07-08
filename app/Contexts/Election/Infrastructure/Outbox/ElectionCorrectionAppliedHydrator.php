<?php

declare(strict_types=1);

namespace App\Contexts\Election\Infrastructure\Outbox;

use App\Contexts\Election\Domain\CorrectionType;
use App\Contexts\Election\Domain\DeterminationId;
use App\Contexts\Election\Domain\ElectionId;
use App\Contexts\Election\Domain\Events\ElectionCorrectionApplied;
use App\Contexts\Shared\Infrastructure\Outbox\EventHydrator;
use DateTimeImmutable;
use InvalidArgumentException;

/**
 * Reconstructs Election's DOMAIN EVENT `ElectionCorrectionApplied` from the published wire
 * payload (the cross-context contract). The inverse of {@see ReactionOutboxAdapter}'s
 * writer — the two define the wire contract together and must evolve together. The Shared
 * relay then wraps the reconstructed domain event in the `IntegrationEvent` envelope that
 * crosses the bounded-context boundary.
 *
 * Election supports only payload schema version 1; an unsupported version is REJECTED
 * (never silently hydrated).
 */
final class ElectionCorrectionAppliedHydrator implements EventHydrator
{
    public function eventType(): string
    {
        return 'ElectionCorrectionApplied';
    }

    /**
     * @param array<string, mixed> $payload
     */
    public function hydrate(array $payload): object
    {
        $version = $this->schemaVersion($payload);
        if ($version !== 1) {
            throw new InvalidArgumentException(
                sprintf('Unsupported ElectionCorrectionApplied schema_version: %s', $version),
            );
        }

        return new ElectionCorrectionApplied(
            ElectionId::fromString($this->required($payload, 'electionId')),
            DeterminationId::fromString($this->required($payload, 'determinationId')),
            CorrectionType::from($this->required($payload, 'correctionType')),
            new DateTimeImmutable($this->required($payload, 'appliedAt')),
        );
    }

    /**
     * @param array<string, mixed> $payload
     */
    private function schemaVersion(array $payload): int
    {
        $version = $payload['schema_version'] ?? 1;

        // A non-numeric version is treated as unsupported (rejected), never assumed valid.
        return is_numeric($version) ? (int) $version : -1;
    }

    /**
     * @param array<string, mixed> $payload
     */
    private function required(array $payload, string $key): string
    {
        $value = $payload[$key] ?? null;
        if (!is_scalar($value)) {
            throw new InvalidArgumentException(
                sprintf('ElectionCorrectionApplied payload is missing required field "%s".', $key),
            );
        }

        return (string) $value;
    }
}

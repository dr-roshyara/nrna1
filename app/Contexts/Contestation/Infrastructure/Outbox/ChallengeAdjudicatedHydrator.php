<?php

declare(strict_types=1);

namespace App\Contexts\Contestation\Infrastructure\Outbox;

use App\Contexts\Contestation\Domain\Challenge\ChallengeId;
use App\Contexts\Contestation\Domain\Challenge\DeterminationId;
use App\Contexts\Contestation\Domain\Events\ChallengeAdjudicated;
use App\Contexts\Shared\Infrastructure\Outbox\EventHydrator;
use DateTimeImmutable;
use InvalidArgumentException;

/**
 * Reconstructs the `ChallengeAdjudicated` domain event from the published payload (the
 * relay re-emits it). Payload schema version 1 only; unsupported versions are rejected.
 */
final class ChallengeAdjudicatedHydrator implements EventHydrator
{
    public function eventType(): string
    {
        return 'ChallengeAdjudicated';
    }

    /**
     * @param array<string, mixed> $payload
     */
    public function hydrate(array $payload): object
    {
        $version = $payload['schema_version'] ?? 1;
        if (!is_numeric($version) || (int) $version !== 1) {
            throw new InvalidArgumentException(sprintf('Unsupported ChallengeAdjudicated schema_version: %s', is_scalar($version) ? (string) $version : 'non-scalar'));
        }

        return new ChallengeAdjudicated(
            ChallengeId::fromString($this->required($payload, 'challengeId')),
            DeterminationId::fromString($this->required($payload, 'determinationId')),
            new DateTimeImmutable($this->required($payload, 'occurredAt')),
        );
    }

    /**
     * @param array<string, mixed> $payload
     */
    private function required(array $payload, string $key): string
    {
        $value = $payload[$key] ?? null;
        if (!is_scalar($value)) {
            throw new InvalidArgumentException(sprintf('ChallengeAdjudicated payload missing required field "%s".', $key));
        }

        return (string) $value;
    }
}

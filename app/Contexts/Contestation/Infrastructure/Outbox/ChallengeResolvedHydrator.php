<?php

declare(strict_types=1);

namespace App\Contexts\Contestation\Infrastructure\Outbox;

use App\Contexts\Contestation\Domain\Challenge\ChallengeId;
use App\Contexts\Contestation\Domain\Challenge\DeterminationId;
use App\Contexts\Contestation\Domain\Events\ChallengeResolved;
use App\Contexts\Shared\Infrastructure\Outbox\EventHydrator;
use DateTimeImmutable;
use InvalidArgumentException;

/**
 * Reconstructs the MINIMAL `ChallengeResolved` domain event from the published payload.
 * The payload carries `resolution` for downstream consumers (F-2), but it is NOT part of
 * the domain event — the hydrator deliberately ignores it, preserving the Domain-Event ≠
 * Integration-Event separation. Payload schema version 1 only; unsupported → rejected.
 */
final class ChallengeResolvedHydrator implements EventHydrator
{
    public function eventType(): string
    {
        return 'ChallengeResolved';
    }

    /**
     * @param array<string, mixed> $payload
     */
    public function hydrate(array $payload): object
    {
        $version = $payload['schema_version'] ?? 1;
        if (!is_numeric($version) || (int) $version !== 1) {
            throw new InvalidArgumentException(sprintf('Unsupported ChallengeResolved schema_version: %s', is_scalar($version) ? (string) $version : 'non-scalar'));
        }

        // `resolution` is intentionally NOT read — it is integration-only enrichment.
        return new ChallengeResolved(
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
            throw new InvalidArgumentException(sprintf('ChallengeResolved payload missing required field "%s".', $key));
        }

        return (string) $value;
    }
}

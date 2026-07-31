<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Infrastructure\Outbox;

use App\Contexts\Adjudication\Domain\Determination\ChallengeRef;
use App\Contexts\Adjudication\Domain\Events\AdjudicationExpired;
use App\Contexts\Shared\Infrastructure\Outbox\EventHydrator;
use DateTimeImmutable;
use InvalidArgumentException;

/**
 * Registration half of `AdjudicationExpired`'s published-language status — publication
 * alone is not enough (the WP-3A rule: published language requires BOTH publication
 * and registration).
 *
 * Version window: v1 only, because v1 is all that exists. A payload of any other
 * version is rejected loudly rather than guessed at (ADR-T5).
 */
final class AdjudicationExpiredHydrator implements EventHydrator
{
    public function eventType(): string
    {
        return 'AdjudicationExpired';
    }

    /**
     * @param array<string, mixed> $payload
     */
    public function hydrate(array $payload): object
    {
        $version = $payload['schema_version'] ?? 1;
        $version = is_numeric($version) ? (int) $version : null;
        if ($version !== 1) {
            throw new InvalidArgumentException(
                'AdjudicationExpired payload schema version is not supported (window: v1).',
            );
        }

        return new AdjudicationExpired(
            ChallengeRef::fromString($this->required($payload, 'challengeRef')),
            new DateTimeImmutable($this->required($payload, 'expiredAt')),
        );
    }

    /**
     * @param array<string, mixed> $payload
     */
    private function required(array $payload, string $key): string
    {
        $value = $payload[$key] ?? null;
        if (!is_scalar($value) || (string) $value === '') {
            throw new InvalidArgumentException("AdjudicationExpired payload is missing required field '{$key}'.");
        }

        return (string) $value;
    }
}

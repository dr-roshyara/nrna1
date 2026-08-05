<?php

declare(strict_types=1);

namespace App\Contexts\Contestation\Infrastructure\Outbox;

use App\Contexts\Contestation\Domain\Challenge\ChallengeId;
use App\Contexts\Contestation\Domain\Events\ChallengeRouted;
use App\Contexts\Shared\Infrastructure\Outbox\EventHydrator;
use DateTimeImmutable;
use InvalidArgumentException;

/**
 * Context-owned hydrator for `ChallengeRouted` — the registration half of its
 * published language (WP-3A · ADR-T21).
 *
 * **Published language requires BOTH publication and registration.** An event is
 * not part of the published language unless it can both travel over the wire and
 * be reconstructed from that wire representation. `ChallengeOutboxAdapter`
 * publishes; this hydrator reconstructs. Either alone is incomplete.
 *
 * Inverse of `ChallengeOutboxAdapter::writeRouted()` — the two define the wire
 * contract together and evolve together (ADR-T5: additive fields bump
 * `schema_version`; breaking changes are a new event name).
 *
 * The reconstructed domain event stays MINIMAL — exactly the three facts routing
 * records (PB-005's F-2 ruling: publication-time enrichment belongs to the
 * Application layer, never to the domain event).
 *
 * Traceability — ADR-T21 (published language) · ADR-T5 + `Event_Registry.md` ·
 * `Canonical_Event_Catalog_v1.0.md` · ADR-T20 (…→Routed→…) · ADR-T11 (no
 * voter↔vote linkage on the wire) · Context: Contestation (Infrastructure).
 */
final class ChallengeRoutedHydrator implements EventHydrator
{
    public function eventType(): string
    {
        return 'ChallengeRouted';
    }

    /**
     * @param array<string, mixed> $payload
     */
    public function hydrate(array $payload): object
    {
        $version = $payload['schema_version'] ?? 1;
        if (!is_numeric($version) || (int) $version !== 1) {
            throw new InvalidArgumentException(sprintf(
                'Unsupported ChallengeRouted schema_version: %s',
                is_scalar($version) ? (string) $version : 'non-scalar',
            ));
        }

        return new ChallengeRouted(
            ChallengeId::fromString($this->required($payload, 'challengeId')),
            $this->required($payload, 'routedTo'),
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
            throw new InvalidArgumentException(sprintf(
                'ChallengeRouted payload missing required field "%s".',
                $key,
            ));
        }

        return (string) $value;
    }
}

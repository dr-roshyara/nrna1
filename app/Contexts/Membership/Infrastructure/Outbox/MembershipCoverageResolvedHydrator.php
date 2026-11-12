<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Outbox;

use App\Contexts\Membership\Application\Organisational\PublishedLanguage\CoverageInterval;
use App\Contexts\Membership\Application\Organisational\PublishedLanguage\MembershipCoverageResolved;
use App\Contexts\Membership\Infrastructure\Organisational\MembershipCoverageResolvedOutbox;
use App\Contexts\Shared\Infrastructure\Outbox\EventHydrator;

/**
 * Context-owned hydrator for C1 MembershipCoverageResolved (PBDIGIT-343 plan D-6). Membership produces the event
 * (MembershipCoverageResolvedOutbox), so Membership is its one hydration authority (EventHydratorRegistry). The
 * payload is the contract written by the producer; anything else fails loudly (InvalidArgumentException), so the
 * relay retries and finally marks the row failed instead of dispatching a guessed event.
 */
final class MembershipCoverageResolvedHydrator implements EventHydrator
{
    public function eventType(): string
    {
        return MembershipCoverageResolvedOutbox::EVENT_TYPE;
    }

    /** @param array<string, mixed> $payload */
    public function hydrate(array $payload): object
    {
        $schemaVersion = self::integer($payload, 'schemaVersion');
        if ($schemaVersion !== MembershipCoverageResolved::SCHEMA_VERSION) {
            throw new \InvalidArgumentException(sprintf('MembershipCoverageResolved schemaVersion %d is not supported (expected %d).', $schemaVersion, MembershipCoverageResolved::SCHEMA_VERSION));
        }

        return new MembershipCoverageResolved(
            eventId: self::string($payload, 'eventId'),
            organisationId: self::string($payload, 'organisationId'),
            membershipId: self::string($payload, 'membershipId'),
            personRef: self::string($payload, 'personRef'),
            seq: self::integer($payload, 'seq'),
            coverage: self::intervals($payload, 'coverage'),
            inForce: self::intervals($payload, 'inForce'),
            suspended: self::intervals($payload, 'suspended'),
            recordedAtMicros: self::integer($payload, 'recordedAt'),
            schemaVersion: $schemaVersion,
        );
    }

    /** @param array<string, mixed> $payload */
    private static function string(array $payload, string $field): string
    {
        $value = $payload[$field] ?? null;
        if (!is_string($value) || $value === '') {
            throw new \InvalidArgumentException(sprintf('MembershipCoverageResolved payload is missing required field "%s".', $field));
        }

        return $value;
    }

    /** @param array<string, mixed> $payload */
    private static function integer(array $payload, string $field): int
    {
        $value = $payload[$field] ?? null;
        if (!is_int($value)) {
            throw new \InvalidArgumentException(sprintf('MembershipCoverageResolved field "%s" must be an integer.', $field));
        }

        return $value;
    }

    /**
     * @param array<string, mixed> $payload
     * @return list<CoverageInterval>
     */
    private static function intervals(array $payload, string $field): array
    {
        $value = $payload[$field] ?? null;
        if (!is_array($value) || !array_is_list($value)) {
            throw new \InvalidArgumentException(sprintf('MembershipCoverageResolved field "%s" must be a list of intervals.', $field));
        }

        return array_map(static function (mixed $i) use ($field): CoverageInterval {
            $from = is_array($i) ? ($i['from'] ?? null) : null;
            $to = is_array($i) && array_key_exists('to', $i) ? $i['to'] : false;
            if (!self::isDate($from) || ($to !== null && !self::isDate($to))) {
                throw new \InvalidArgumentException(sprintf('MembershipCoverageResolved field "%s" holds an interval that is not [from, to) in YYYY-MM-DD.', $field));
            }

            return new CoverageInterval($from, $to);
        }, $value);
    }

    private static function isDate(mixed $value): bool
    {
        return is_string($value) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $value) === 1;
    }
}

<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Organisational;

use App\Contexts\Membership\Domain\Organisational\Instant;
use App\Contexts\Membership\Domain\Organisational\Interval;
use App\Contexts\Membership\Domain\Organisational\IntervalSet;
use App\Contexts\Membership\Domain\Organisational\ResolvedMember;
use Illuminate\Database\Connection;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

/**
 * C1 MembershipCoverageResolved (CR-01 C1, CR-02 schemaVersion 2; C1/C2 spec §5, §11; PBDIGIT-343 plan D-5).
 *
 * - Written into outbox_events **through the connection it is given**, inside the append transaction, so the fact,
 *   the clock update and the event commit or roll back together. Not through the shared OutboxWriter, which writes on
 *   the default connection (plan F-5).
 * - Written only when coverage, inForce or suspended changed.
 * - aggregate_id: the column is uuid but a MemberId is free text, so a deterministic UUID v5 per (organisation,
 *   membership); the membershipId itself travels in the payload.
 * - Units (spec K-6): recordedAt in epoch microseconds; intervals as calendar dates [from, to), to = null if open.
 */
final class MembershipCoverageResolvedOutbox
{
    public const EVENT_TYPE = 'MembershipCoverageResolved';
    public const AGGREGATE_TYPE = 'Membership';
    public const SCHEMA_VERSION = 2;
    /** The fixed UUID v5 namespace for Membership aggregate ids in outbox_events. Never change it. */
    private const AGGREGATE_NAMESPACE = '3a0f6a2e-9c1d-5b8e-8f4a-6d2c1e7b9a43';

    /** @return array{coverage: list<array{from: string, to: ?string}>, inForce: list<array{from: string, to: ?string}>, suspended: list<array{from: string, to: ?string}>} */
    public static function state(ResolvedMember $member): array
    {
        return [
            'coverage' => self::dates($member->coverage()),
            'inForce' => self::dates($member->inForceIntervals()),
            'suspended' => self::dates($member->suspendedIntervals()),
        ];
    }

    public static function stateChanged(ResolvedMember $before, ResolvedMember $after): bool
    {
        return self::state($before) !== self::state($after);
    }

    public static function aggregateId(string $organisationId, string $membershipId): string
    {
        return Uuid::uuid5(self::AGGREGATE_NAMESPACE, $organisationId . ':' . $membershipId)->toString();
    }

    public static function write(Connection $db, string $organisationId, string $membershipId, string $personRef, int $seq, ResolvedMember $after, Instant $recordedAt): void
    {
        $eventId = (string) Str::uuid();
        $payload = [
            'eventId' => $eventId,
            'organisationId' => $organisationId,
            'membershipId' => $membershipId,
            'personRef' => $personRef,
            'seq' => $seq,
        ] + self::state($after) + [
            'recordedAt' => $recordedAt->ordinal(),
            'schemaVersion' => self::SCHEMA_VERSION,
        ];

        $db->table('outbox_events')->insert([
            'id' => (string) Str::uuid(),
            'event_id' => $eventId,
            'organisation_id' => $organisationId,
            'aggregate_type' => self::AGGREGATE_TYPE,
            'aggregate_id' => self::aggregateId($organisationId, $membershipId),
            'event_type' => self::EVENT_TYPE,
            'payload' => json_encode($payload, JSON_THROW_ON_ERROR),
            'status' => 'pending',
        ]);
    }

    /** @return list<array{from: string, to: ?string}> */
    private static function dates(IntervalSet $set): array
    {
        return array_map(static fn (Interval $i): array => [
            'from' => DateOrdinal::toDate($i->start()),
            'to' => $i->until()->isInfinite() ? null : DateOrdinal::toDate($i->until()),
        ], $set->intervals());
    }
}

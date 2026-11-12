<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Organisational\PublishedLanguage;

/**
 * Published Language event C1 MembershipCoverageResolved (CR-01 C1; CR-02 schemaVersion 2; PBDIGIT-343).
 *
 * The full state of one membership after a change, as Membership resolved it: coverage (⋃ lifetimes), inForce
 * (IsMember) and suspended. For current views only: every "as known at τ" answer uses C2 (C1/C2 spec §7, §50.8).
 * Consumers order by (membershipId, seq): lower seq → ignore; equal and identical → ignore; equal and different →
 * integrity error (CR-01 C1). Pure PHP, no framework.
 */
final readonly class MembershipCoverageResolved
{
    public const SCHEMA_VERSION = 2;

    /**
     * @param list<CoverageInterval> $coverage
     * @param list<CoverageInterval> $inForce
     * @param list<CoverageInterval> $suspended
     */
    public function __construct(
        public string $eventId,
        public string $organisationId,
        public string $membershipId,
        public string $personRef,
        public int $seq,
        public array $coverage,
        public array $inForce,
        public array $suspended,
        public int $recordedAtMicros,
        public int $schemaVersion,
    ) {
    }
}

<?php

namespace App\Domain\Election\Constitution;

use App\Domain\Election\Security\Simplified\EvidenceClassification;

/**
 * NetworkThresholdInterpreter
 *
 * Pure function interpreting constitutional maxVotesPerIp against trust levels.
 * Located in Constitution domain because thresholds are constitutional law interpretation,
 * not security infrastructure.
 *
 * Thresholds:
 * - Initial: floor(N/2)
 * - Attested: N
 * - ContinuityProven: floor(N * 1.5) = N + N/2
 * - RegistrarConfirmed: N * 2
 *
 * Integer-only math. NO evidence observation. Pure determinism.
 */
final class NetworkThresholdInterpreter
{
    public static function interpret(int $maxVotesPerIp, EvidenceClassification $classification): int
    {
        return match ($classification) {
            EvidenceClassification::Initial => intdiv($maxVotesPerIp, 2),
            EvidenceClassification::Attested => $maxVotesPerIp,
            EvidenceClassification::ContinuityProven => $maxVotesPerIp + intdiv($maxVotesPerIp, 2),
            EvidenceClassification::RegistrarConfirmed => $maxVotesPerIp * 2,
        };
    }
}

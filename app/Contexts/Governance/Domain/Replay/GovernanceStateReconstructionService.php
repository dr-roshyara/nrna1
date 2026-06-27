<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Domain\Replay;

use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use DateTimeImmutable;

/**
 * Interface-only deferred service (AD-05).
 * Renamed from ConstitutionalReplayService to remove event-sourcing terminology.
 * Implementation deferred to Phase 6 (requires projection infrastructure).
 */
interface GovernanceStateReconstructionService
{
    public function reconstructAt(CommitteeId $committeeId, DateTimeImmutable $at): mixed;
}

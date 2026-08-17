<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain\OperatingCore\Condition;

use App\Contexts\Election\Domain\OperatingCore\Gate\GateDesignation;
use App\Contexts\Election\Domain\OperatingCore\Time\RecordedInstant;

/**
 * The recorded fact that progression is HALTED at a gate condition: the preceding
 * phase remains completed and is never restarted (EM-GOV-052). HALTED is a
 * progression condition of the election lifecycle — distinct by type from OPEN,
 * from INOPERATIVE and from the terminal state (hard gates G-3/G-4). Lifecycle
 * transitions themselves stay canonically homed in `ElectionConstitution`; this
 * type represents the recorded fact, it does not re-declare transitions. @immutable
 */
final readonly class HaltedAtGate
{
    public function __construct(
        public GateDesignation $gate,
        public RecordedInstant $haltedAt,
    ) {
    }
}

<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain\OperatingCore\Policy;

use App\Contexts\Election\Domain\OperatingCore\Condition\HaltedAtGate;
use App\Contexts\Election\Domain\OperatingCore\Gate\GateDesignation;

/**
 * P-7 `ResumptionTarget` (stateless): restoration returns the election to its prior
 * halted condition, AT THE UNRESOLVED GATE — it restores the ability to make the
 * decision, never the decision itself; the gate is never treated as satisfied by
 * nobody's acceptance (EM-GOV-059(c), 060; EM-ARCH-001 §2e).
 */
final class ResumptionTarget
{
    private function __construct()
    {
    }

    public static function resolve(HaltedAtGate $halt): GateDesignation
    {
        return $halt->gate;
    }
}

<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain\OperatingCore\Port;

/**
 * P-2H: TWO histories exist and must never be merged — the lifecycle history and
 * the progression-decision history (which may hold many entries for one
 * opportunity). F-PROTO-1 property 7 requires the distinction INSIDE the record
 * itself (EM-VOC-004 confirmed reading; EM-GOV-005).
 */
enum HistoryKind: string
{
    case Lifecycle = 'lifecycle';
    case ProgressionDecision = 'progression_decision';
}

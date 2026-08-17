<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain\OperatingCore\Condition;

/**
 * The election's operational OVERLAY condition (EM-ARCH-001 §2b/§5d): Operative and
 * Inoperative are mutually exclusive recorded conditions — which is why the two
 * recovery clocks can never run at once (EM-GOV-062, structural consequence).
 * Election Inoperative begins AT the causing recorded vacancy event — no
 * declaration, no determiner (EM-GOV-065); it overlays the lifecycle and never
 * erases a halt (EM-GOV-059(b)).
 */
enum OperationalCondition: string
{
    case Operative = 'operative';
    case Inoperative = 'inoperative';
}

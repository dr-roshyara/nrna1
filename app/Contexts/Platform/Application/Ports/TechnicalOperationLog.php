<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Application\Ports;

use App\Contexts\Platform\Application\TechnicalOperationRecord;

/**
 * PBDIGIT-345 task 11 (ISO-8: every operation recorded; D-193 gateway rule: recorded FIRST, then the outcome): the
 * central record of technical operations on the operator channel, refusals included. Failing to record: throws, and
 * nothing is executed.
 */
interface TechnicalOperationLog
{
    public function attempted(TechnicalOperationRecord $record): void;

    /** @param string $outcome succeeded, failed or refused */
    public function concluded(TechnicalOperationRecord $record, string $outcome, ?string $reason, bool $reported): void;
}

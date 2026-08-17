<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain\OperatingCore\Condition;

/**
 * The election-level terminal state of EM-GOV-063, reached when the halted-recovery
 * period expires without successful recovery. Its governed business rendering is
 * ELECTION DISCONTINUED — adopted EM-GOV-069 (EM-OPEN-110 RESOLVED; D-2 discharged).
 * PO meaning boundary, registered with the adoption: PURELY TERMINAL vocabulary —
 * not cancelled-by-Chief, not abandoned, not invalid, not expired, not impossible,
 * not suspended, not voluntarily stopped. Discontinued ≠ Cancelled: distinct
 * recorded causes, never merged. The technical type name remains Architecture's
 * mapping (registered DDD separation); the business word is EM-GOV-069's. @immutable
 */
final readonly class TerminalStatePlaceholder
{
    private function __construct()
    {
    }

    public static function electionDiscontinued(): self
    {
        return new self();
    }

    /** The one governed rendering (EM-GOV-069). */
    public function businessRendering(): string
    {
        return 'Election Discontinued';
    }
}

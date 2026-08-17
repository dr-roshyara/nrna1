<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain\OperatingCore\Condition;

/**
 * ELECTION-LEVEL cancellation — the consequence of Committee-restoration-period
 * expiry (EM-GOV-058: an Election Rule's consequence, never a service decision).
 * A DISTINCT TYPE from the opportunity-level `cancelled` outcome of EM-VOC-004 and
 * from the terminal `TerminalStatePlaceholder` — the two *cancelled* levels are
 * never represented by one value (D-9; B-4), and election-level states carry the
 * `Election` prefix (EM-GOV-069). @immutable
 */
final readonly class ElectionLevelCancellation
{
    private function __construct()
    {
    }

    public static function onRestorationExpiry(): self
    {
        return new self();
    }

    public function businessRendering(): string
    {
        return 'Election Cancelled';
    }
}

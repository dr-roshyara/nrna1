<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Shared\Domain;

/**
 * Version of the handoff-assurance checker itself (Phase-1 D-5 provenance,
 * existing mechanism only). A report states WHICH checker version produced it
 * so the evidence is reproducible; the version is not an authority claim.
 */
final class CheckerVersion
{
    /** Checker version — first handoff-report release (Track 2 · Phase 1). */
    public const CURRENT = '1.0.0';

    private function __construct()
    {
        // Namespace only — never instantiated.
    }
}

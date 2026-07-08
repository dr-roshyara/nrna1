<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Election;

use App\Contexts\Election\Domain\CorrectionType;
use App\Contexts\Election\Domain\Policy\CorrectionTypeDecision;
use App\Contexts\Election\Domain\RulingOutcome;
use PHPUnit\Framework\TestCase;

/**
 * PB-004 Step 3 (RED) — the Election context owns HOW a determination affects
 * election state (OQ-3): the CorrectionTypeDecision maps a ruling outcome to a
 * correction type, or to NO correction. Pure domain behavior (ER-07 — no transport).
 *
 * Evidence: D-02 (Dismissed → no correction) · ADR-T8 (forward-only, anonymity-
 * bounded `ContainedOnly` correction — votes cannot be un-cast).
 */
final class CorrectionTypeDecisionTest extends TestCase
{
    public function test_dismissed_ruling_yields_no_correction(): void
    {
        // D-02: a dismissal concludes no corrective action is required.
        $this->assertNull((new CorrectionTypeDecision())->decide(RulingOutcome::Dismissed));
    }

    public function test_upheld_ruling_yields_contained_only_correction(): void
    {
        // ADR-T8: the correction loop is forward-only; anonymity forbids un-casting,
        // so an upheld ruling yields an anonymity-bounded ContainedOnly correction.
        $this->assertSame(
            CorrectionType::ContainedOnly,
            (new CorrectionTypeDecision())->decide(RulingOutcome::Upheld),
        );
    }
}

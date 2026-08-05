<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Election;

use App\Contexts\Election\Domain\CorrectionType;
use App\Contexts\Election\Domain\Policy\ElectionCorrectionPolicy;
use App\Contexts\Election\Domain\RulingOutcome;
use PHPUnit\Framework\TestCase;

/**
 * PB-004 Step 3 (RED) — the Election context owns the BUSINESS POLICY for how a
 * binding determination corrects an election (OQ-3). `ElectionCorrectionPolicy`
 * expresses that policy (not a mere type mapping). Pure domain behavior (ER-07).
 *
 * Evidence: D-02 (Dismissed → no correction) · ADR-T8 (forward-only, anonymity-
 * bounded `ContainedOnly` correction — votes cannot be un-cast).
 */
final class ElectionCorrectionPolicyTest extends TestCase
{
    public function test_dismissed_ruling_yields_no_correction(): void
    {
        // D-02: a dismissal concludes no corrective action is required.
        $this->assertNull((new ElectionCorrectionPolicy())->decide(RulingOutcome::Dismissed));
    }

    public function test_upheld_ruling_yields_a_forward_only_correction(): void
    {
        // ADR-T8: forward-only; anonymity forbids un-casting → ContainedOnly.
        $correction = (new ElectionCorrectionPolicy())->decide(RulingOutcome::Upheld);

        $this->assertSame(CorrectionType::ContainedOnly, $correction);
        $this->assertTrue($correction->isForwardOnly(), 'the policy must never choose a history-reversing correction');
    }
}

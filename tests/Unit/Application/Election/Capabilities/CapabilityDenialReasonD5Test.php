<?php

namespace Tests\Unit\Application\Election\Capabilities;

use App\Application\Election\Capabilities\CapabilityDenialReason;
use PHPUnit\Framework\TestCase;

class CapabilityDenialReasonD5Test extends TestCase
{
    public function test_trust_denied_has_correct_string_value(): void
    {
        $this->assertEquals('trust_denied', CapabilityDenialReason::TrustDenied->value);
    }

    public function test_constitutional_review_pending_has_correct_string_value(): void
    {
        $this->assertEquals('constitutional_review_pending', CapabilityDenialReason::ConstitutionalReviewPending->value);
    }

    public function test_trust_evaluation_inconclusive_has_correct_string_value(): void
    {
        $this->assertEquals('trust_evaluation_inconclusive', CapabilityDenialReason::TrustEvaluationInconclusive->value);
    }

    public function test_no_existing_cases_renamed(): void
    {
        $this->assertEquals('suspended', CapabilityDenialReason::Suspended->value);
        $this->assertEquals('missing_role', CapabilityDenialReason::MissingRole->value);
        $this->assertEquals('invalid_lifecycle', CapabilityDenialReason::InvalidLifecycle->value);
        $this->assertEquals('unmet_precondition', CapabilityDenialReason::UnmetPrecondition->value);
    }
}

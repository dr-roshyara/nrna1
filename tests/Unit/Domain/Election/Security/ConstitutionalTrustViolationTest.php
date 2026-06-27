<?php

namespace Tests\Unit\Domain\Election\Security;

use App\Domain\Election\Security\ConstitutionalTrustViolation;
use PHPUnit\Framework\TestCase;

class ConstitutionalTrustViolationTest extends TestCase
{
    public function test_legitimacy_failure_has_correct_value(): void
    {
        $this->assertEquals('legitimacy_failure', ConstitutionalTrustViolation::LEGITIMACY_FAILURE->value);
    }

    public function test_network_limit_exceeded_has_correct_value(): void
    {
        $this->assertEquals('network_limit_exceeded', ConstitutionalTrustViolation::NETWORK_LIMIT_EXCEEDED->value);
    }

    public function test_constitutional_review_pending_has_correct_value(): void
    {
        $this->assertEquals('constitutional_review_pending', ConstitutionalTrustViolation::CONSTITUTIONAL_REVIEW_PENDING->value);
    }

    public function test_no_banned_vocabulary_in_violation_types(): void
    {
        $bannedTerms = ['deny', 'allow', 'grant', 'suspend', 'authorize'];

        foreach (ConstitutionalTrustViolation::cases() as $case) {
            foreach ($bannedTerms as $term) {
                $this->assertStringNotContainsString($term, $case->value,
                    "ConstitutionalTrustViolation::{$case->name} contains banned term '$term'");
            }
        }
    }

    public function test_all_cases_exist(): void
    {
        $cases = ConstitutionalTrustViolation::cases();
        $this->assertCount(9, $cases);
    }
}

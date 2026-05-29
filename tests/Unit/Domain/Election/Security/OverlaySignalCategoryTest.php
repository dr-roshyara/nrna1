<?php

namespace Tests\Unit\Domain\Election\Security;

use App\Domain\Election\Security\OverlaySignalCategory;
use PHPUnit\Framework\TestCase;

class OverlayInfluenceTest extends TestCase
{
    public function test_continue_unchanged_has_correct_string_value(): void
    {
        $this->assertEquals('continue_unchanged', OverlaySignalCategory::CONTINUE_UNCHANGED->value);
    }

    public function test_trust_elevation_request_has_correct_string_value(): void
    {
        $this->assertEquals('trust_elevation_request', OverlaySignalCategory::TRUST_ELEVATION_REQUEST->value);
    }

    public function test_require_re_verification_has_correct_string_value(): void
    {
        $this->assertEquals('require_re_verification', OverlaySignalCategory::REQUIRE_RE_VERIFICATION->value);
    }

    public function test_require_constitutional_review_has_correct_string_value(): void
    {
        $this->assertEquals('require_constitutional_review', OverlaySignalCategory::REQUIRE_CONSTITUTIONAL_REVIEW->value);
    }

    public function test_trust_evaluation_inconclusive_has_correct_string_value(): void
    {
        $this->assertEquals('trust_evaluation_inconclusive', OverlaySignalCategory::TRUST_EVALUATION_INCONCLUSIVE->value);
    }

    public function test_all_enum_values_exclude_banned_vocabulary(): void
    {
        $bannedTerms = ['deny', 'allow', 'grant', 'authorize', 'permit', 'revoke', 'block', 'suspend'];

        foreach (OverlaySignalCategory::cases() as $case) {
            foreach ($bannedTerms as $term) {
                $this->assertStringNotContainsString($term, $case->value,
                    "OverlaySignalCategory::{$case->name} value '{$case->value}' contains banned term '$term'");
            }
        }
    }
}

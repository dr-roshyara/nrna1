<?php

namespace Tests\Unit\Domain\Election\Security;

use App\Domain\Election\Security\FingerprintMatchType;
use PHPUnit\Framework\TestCase;

class FingerprintMatchTypeTest extends TestCase
{
    public function test_exact_match_has_correct_value(): void
    {
        $this->assertEquals('exact', FingerprintMatchType::ExactMatch->value);
    }

    public function test_no_match_has_correct_value(): void
    {
        $this->assertEquals('no_match', FingerprintMatchType::NoMatch->value);
    }

    public function test_not_required_has_correct_value(): void
    {
        $this->assertEquals('not_required', FingerprintMatchType::NotRequired->value);
    }
}

<?php

namespace Tests\Unit\Domain\Election\Security;

use App\Domain\Election\Security\OverlayStratification;
use PHPUnit\Framework\TestCase;

class OverlayStratificationTest extends TestCase
{
    public function test_emergency_constitutional_has_correct_value(): void
    {
        $this->assertEquals('emergency_constitutional', OverlayStratification::EMERGENCY_CONSTITUTIONAL->value);
    }

    public function test_governance_layer_has_correct_value(): void
    {
        $this->assertEquals('governance_layer', OverlayStratification::GOVERNANCE_LAYER->value);
    }

    public function test_operational_layer_has_correct_value(): void
    {
        $this->assertEquals('operational_layer', OverlayStratification::OPERATIONAL_LAYER->value);
    }

    public function test_contextual_layer_has_correct_value(): void
    {
        $this->assertEquals('contextual_layer', OverlayStratification::CONTEXTUAL_LAYER->value);
    }

    public function test_all_cases_exist(): void
    {
        $cases = OverlayStratification::cases();
        $this->assertCount(4, $cases);
    }
}

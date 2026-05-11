<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee\Constitutional;

use PHPUnit\Framework\TestCase;
use App\Contexts\Membership\Domain\Committee\Constitutional\TemporalAuthorityWindow;
use App\Contexts\Membership\Domain\Committee\Constitutional\LegitimacyEvaluator;
use App\Contexts\Membership\Domain\Committee\Constitutional\GovernanceLegitimacy;

final class LegitimacyEvaluatorTest extends TestCase
{
    private LegitimacyEvaluator $evaluator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->evaluator = new LegitimacyEvaluator();
    }

    public function test_active_window_evaluates_to_legitimate(): void
    {
        $yesterday = new \DateTimeImmutable('2026-05-07T10:00:00Z');
        $now = new \DateTimeImmutable('2026-05-08T10:00:00Z');
        $future = new \DateTimeImmutable('2026-05-09T10:00:00Z');

        $window = new TemporalAuthorityWindow(
            validFrom: $yesterday,
            validUntil: null,
        );

        $result = $this->evaluator->evaluate($window, $now);

        $this->assertSame(GovernanceLegitimacy::LEGITIMATE, $result);
    }

    public function test_expired_window_evaluates_to_expired(): void
    {
        $twoDaysAgo = new \DateTimeImmutable('2026-05-06T10:00:00Z');
        $yesterday = new \DateTimeImmutable('2026-05-07T10:00:00Z');
        $now = new \DateTimeImmutable('2026-05-08T10:00:00Z');

        $window = new TemporalAuthorityWindow(
            validFrom: $twoDaysAgo,
            validUntil: $yesterday,
        );

        $result = $this->evaluator->evaluate($window, $now);

        $this->assertSame(GovernanceLegitimacy::EXPIRED, $result);
    }

    public function test_future_window_evaluates_to_pending(): void
    {
        $tomorrow = new \DateTimeImmutable('2026-05-09T10:00:00Z');
        $now = new \DateTimeImmutable('2026-05-08T10:00:00Z');

        $window = new TemporalAuthorityWindow(
            validFrom: $tomorrow,
            validUntil: null,
        );

        $result = $this->evaluator->evaluate($window, $now);

        $this->assertSame(GovernanceLegitimacy::PENDING, $result);
    }
}

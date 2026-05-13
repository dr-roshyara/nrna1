<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Domain\Committee\Policies;

use App\Contexts\Membership\Domain\Committee\Policies\CommitteeEligibilityPolicy;
use App\Contexts\Membership\Domain\Committee\ValueObjects\GeoPathChain;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class CommitteeEligibilityPolicyTest extends TestCase
{
    private CommitteeEligibilityPolicy $policy;

    protected function setUp(): void
    {
        $this->policy = new CommitteeEligibilityPolicy();
    }

    #[DataProvider('eligibleCasesProvider')]
    public function test_is_eligible_returns_true(GeoPathChain $committee, GeoPathChain $member): void
    {
        $this->assertTrue($this->policy->isEligible($committee, $member));
    }

    #[DataProvider('ineligibleCasesProvider')]
    public function test_is_eligible_returns_false(GeoPathChain $committee, GeoPathChain $member): void
    {
        $this->assertFalse($this->policy->isEligible($committee, $member));
    }

    public static function eligibleCasesProvider(): array
    {
        return [
            'central committee covers all members' => [
                new GeoPathChain(1, '', []),
                new GeoPathChain(100, '/1/23/456', [1, 23, 456]),
            ],
            'same geo unit' => [
                new GeoPathChain(10, '/1/23', [1, 23]),
                new GeoPathChain(10, '/1/23', [1, 23]),
            ],
            'member is direct child of committee' => [
                new GeoPathChain(23, '/1/23', [1, 23]),
                new GeoPathChain(456, '/1/23/456', [1, 23, 456]),
            ],
            'member is deep descendant of committee' => [
                new GeoPathChain(23, '/1/23', [1, 23]),
                new GeoPathChain(789, '/1/23/456/789', [1, 23, 456, 789]),
            ],
            'central committee covers member with no residence' => [
                new GeoPathChain(1, '', []),
                new GeoPathChain(100, '', []),
            ],
        ];
    }

    public static function ineligibleCasesProvider(): array
    {
        return [
            'member in different branch' => [
                new GeoPathChain(23, '/1/23', [1, 23]),
                new GeoPathChain(99, '/1/99/456', [1, 99, 456]),
            ],
            'member is ancestor of committee (reversed)' => [
                new GeoPathChain(456, '/1/23/456', [1, 23, 456]),
                new GeoPathChain(23, '/1/23', [1, 23]),
            ],
            'geographic committee with member without residence' => [
                new GeoPathChain(23, '/1/23', [1, 23]),
                new GeoPathChain(100, '', []),
            ],
            'completely different hierarchy' => [
                new GeoPathChain(50, '/2/50', [2, 50]),
                new GeoPathChain(60, '/3/60', [3, 60]),
            ],
        ];
    }
}

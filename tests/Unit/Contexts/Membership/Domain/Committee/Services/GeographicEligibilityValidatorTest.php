<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Domain\Committee\Services;

use App\Contexts\Membership\Domain\Committee\Policies\CommitteeEligibilityPolicy;
use App\Contexts\Membership\Domain\Committee\Ports\GeographicJurisdiction;
use App\Contexts\Membership\Domain\Committee\Ports\GeographicJurisdictionProvider;
use App\Contexts\Membership\Domain\Committee\Projections\GeoSemanticProjection;
use App\Contexts\Membership\Domain\Committee\Services\GeographicEligibilityValidator;
use App\Contexts\Membership\Domain\Committee\Services\GeoSemanticProjectionBuilder;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class GeographicEligibilityValidatorTest extends TestCase
{
    #[DataProvider('eligibilityCasesProvider')]
    public function test_validate(
        ?GeographicJurisdiction $memberJurisdiction,
        ?GeographicJurisdiction $committeeJurisdiction,
        bool $expected,
    ): void {
        $provider = $this->createMock(GeographicJurisdictionProvider::class);
        $provider->method('resolve')
            ->willReturnMap([
                [100, $memberJurisdiction],
                [200, $committeeJurisdiction],
            ]);

        $builder = new GeoSemanticProjectionBuilder($provider);
        $validator = new GeographicEligibilityValidator(
            $builder,
            new CommitteeEligibilityPolicy(),
        );

        $this->assertSame($expected, $validator->validate(100, 200));
    }

    public static function eligibilityCasesProvider(): array
    {
        return [
            'eligible: member in committee area' => [
                new GeographicJurisdiction(456, 3, '3', 'NP', '/1/23/456'),
                new GeographicJurisdiction(23, 2, '3', 'NP', '/1/23'),
                true,
            ],
            'ineligible: member in different branch' => [
                new GeographicJurisdiction(789, 3, '4', 'NP', '/1/99/789'),
                new GeographicJurisdiction(23, 2, '3', 'NP', '/1/23'),
                false,
            ],
            'central committee (empty path) covers all' => [
                new GeographicJurisdiction(456, 3, '3', 'NP', '/1/23/456'),
                new GeographicJurisdiction(1, 0, '', 'NP', ''),
                true,
            ],
            'null member jurisdiction' => [
                null,
                new GeographicJurisdiction(23, 2, '3', 'NP', '/1/23'),
                false,
            ],
            'null committee jurisdiction' => [
                new GeographicJurisdiction(456, 3, '3', 'NP', '/1/23/456'),
                null,
                false,
            ],
            'both null' => [
                null,
                null,
                false,
            ],
        ];
    }
}

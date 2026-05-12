<?php

declare(strict_types=1);

namespace Tests\Architecture;

use App\Contexts\Geography\Application\DTOs\GeoHierarchyResponse;
use App\Contexts\Geography\Application\DTOs\GeoUnitResponse;
use App\Contexts\Geography\Application\DTOs\GeoUnitSummaryResponse;
use App\Contexts\Geography\Application\Services\GeoHierarchyProjectionBuilder;
use App\Shared\Architecture\Config\GeographyRuleset;
use App\Shared\Architecture\Runner\ArchitectureTestBase;

final class GeographyArchitectureTest extends ArchitectureTestBase
{
    // ─────────────────────────────────────────────────────────────
    // Framework-enforced rules (GEO-ARCH-01 through 07, 09)
    // ─────────────────────────────────────────────────────────────

    public function test_geography_architecture(): void
    {
        $ruleset = new GeographyRuleset();
        self::assertRules($ruleset->rules(), $ruleset->config());
    }

    // ─────────────────────────────────────────────────────────────
    // GEO-ARCH-08: DTOs implement JsonSerializable (instance-level)
    // ─────────────────────────────────────────────────────────────

    public function test_application_dtos_implement_json_serializable(): void
    {
        $this->assertInstanceOf(\JsonSerializable::class, new GeoUnitResponse(
            id: 1, countryCode: 'NP', adminLevel: 1, adminType: 'continent',
            parentId: null, code: null, name: ['en' => 'Asia'],
            isActive: true, validFrom: null, validTo: null,
        ));

        $this->assertInstanceOf(\JsonSerializable::class, new GeoHierarchyResponse(
            id: 1, countryCode: 'NP', adminLevel: 1, adminType: 'continent',
            parentId: null, code: null, name: ['en' => 'Asia'],
            isActive: true, validFrom: null, validTo: null,
        ));

        $this->assertInstanceOf(\JsonSerializable::class, new GeoUnitSummaryResponse(
            id: 1, adminType: 'continent', name: 'Asia',
        ));
    }

    // ─────────────────────────────────────────────────────────────
    // GEO-ARCH-10: Builder uses iterative traversal (semantic rule)
    //
    // Postponed from framework — requires AST-level analysis beyond
    // simple regex/string matching.
    // ─────────────────────────────────────────────────────────────

    public function test_builder_uses_iterative_not_recursive_traversal(): void
    {
        $filePath = (new \ReflectionClass(GeoHierarchyProjectionBuilder::class))->getFileName();
        $content = file_get_contents($filePath);

        $this->assertStringContainsString(
            'array_pop',
            $content,
            'Builder must use stack-based (iterative) flattening, not recursion'
        );

        $this->assertStringContainsString(
            'while (isset($queue[$i]))',
            $content,
            'Builder must use index-based BFS, not array_shift'
        );
    }
}

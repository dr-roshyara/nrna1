<?php

declare(strict_types=1);

namespace Tests\Unit\Domain;

use App\Contexts\Geography\Domain\Enums\GeoLevelType;
use App\Contexts\Geography\Domain\Services\GeoLevelMappingResolver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

final class GeoLevelMappingResolverTest extends TestCase
{
    use RefreshDatabase;

    private GeoLevelMappingResolver $resolver;

    protected function setUp(): void
    {
        parent::setUp();
        $this->resolver = app(GeoLevelMappingResolver::class);

        // Seed Nepal country
        DB::table('countries')->insert([
            'code' => 'NP',
            'code_alpha3' => 'NPL',
            'code_numeric' => '524',
            'name_en' => 'Nepal',
            'name_local' => json_encode(['np' => 'नेपाल']),
            'admin_levels' => json_encode([
                1 => ['name' => 'Province', 'local_name' => 'प्रदेश', 'count' => 7],
                2 => ['name' => 'District', 'local_name' => 'जिल्ला', 'count' => 77],
                3 => ['name' => 'Municipality', 'local_name' => 'नगरपालिका', 'count' => 753],
                4 => ['name' => 'Ward', 'local_name' => 'वडा', 'count' => 6743],
            ]),
            'is_active' => true,
            'is_supported' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function resolve_returns_db_level_1_for_nepal_province(): void
    {
        $dbLevel = $this->resolver->resolve('NP', GeoLevelType::PROVINCE);

        $this->assertSame(1, $dbLevel);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function resolve_returns_db_level_2_for_nepal_district(): void
    {
        $dbLevel = $this->resolver->resolve('NP', GeoLevelType::DISTRICT);

        $this->assertSame(2, $dbLevel);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function resolve_returns_null_for_unknown_country(): void
    {
        $dbLevel = $this->resolver->resolve('XX', GeoLevelType::PROVINCE);

        $this->assertNull($dbLevel);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function resolve_returns_null_for_unknown_level_type(): void
    {
        $dbLevel = $this->resolver->resolve('NP', GeoLevelType::COUNTY);

        $this->assertNull($dbLevel);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function resolve_is_case_insensitive_match(): void
    {
        $dbLevel1 = $this->resolver->resolve('np', GeoLevelType::PROVINCE);
        $dbLevel2 = $this->resolver->resolve('NP', GeoLevelType::PROVINCE);

        $this->assertSame($dbLevel1, $dbLevel2);
        $this->assertSame(1, $dbLevel1);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function resolver_reads_from_countries_admin_levels_json(): void
    {
        // Test that all mapped levels work correctly
        $this->assertSame(1, $this->resolver->resolve('NP', GeoLevelType::PROVINCE));
        $this->assertSame(2, $this->resolver->resolve('NP', GeoLevelType::DISTRICT));
        $this->assertSame(3, $this->resolver->resolve('NP', GeoLevelType::MUNICIPALITY));
        $this->assertSame(4, $this->resolver->resolve('NP', GeoLevelType::WARD));
    }
}

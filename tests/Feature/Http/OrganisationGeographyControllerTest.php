<?php

namespace Tests\Feature\Http;

use App\Contexts\Geography\Application\DTOs\CascaderConfigDTO;
use App\Models\Organisation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrganisationGeographyControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_cascader_config_dto_single_country(): void
    {
        $org = Organisation::factory()->create([
            'committee_structure' => 'geographical',
            'geographic_scope' => 'single_country',
            'base_country_code' => 'NP',
        ]);

        $dto = CascaderConfigDTO::fromOrganisation($org);

        $this->assertEquals('single_country', $dto->scope);
        $this->assertEquals('NP', $dto->initialCountryCode);
        $this->assertFalse($dto->showCountrySelector);
        $this->assertEquals(4, $dto->maxDepth);
    }

    public function test_cascader_config_dto_worldwide(): void
    {
        $org = Organisation::factory()->create([
            'committee_structure' => 'geographical',
            'geographic_scope' => 'worldwide',
        ]);

        $dto = CascaderConfigDTO::fromOrganisation($org);

        $this->assertEquals('worldwide', $dto->scope);
        $this->assertTrue($dto->showCountrySelector);
    }

    public function test_cascader_config_dto_returns_correct_labels(): void
    {
        $org = Organisation::factory()->create([
            'committee_structure' => 'geographical',
            'geographic_scope' => 'single_country',
            'base_country_code' => 'NP',
        ]);

        $dto = CascaderConfigDTO::fromOrganisation($org);

        $this->assertEquals(['Province', 'District', 'Municipality', 'Ward'], $dto->levelLabels);
    }
}

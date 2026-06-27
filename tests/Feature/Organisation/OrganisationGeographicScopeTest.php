<?php

namespace Tests\Feature\Organisation;

use App\Models\Organisation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrganisationGeographicScopeTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();

        // Seed countries for validation
        $this->seedCountries();
    }

    private function seedCountries(): void
    {
        \DB::table('countries')->insert([
            ['code' => 'DE', 'code_alpha3' => 'DEU', 'code_numeric' => '276', 'name_en' => 'Germany', 'name_local' => '{}', 'admin_levels' => '{}', 'is_active' => true, 'is_supported' => false],
            ['code' => 'AT', 'code_alpha3' => 'AUT', 'code_numeric' => '040', 'name_en' => 'Austria', 'name_local' => '{}', 'admin_levels' => '{}', 'is_active' => true, 'is_supported' => false],
            ['code' => 'CH', 'code_alpha3' => 'CHE', 'code_numeric' => '756', 'name_en' => 'Switzerland', 'name_local' => '{}', 'admin_levels' => '{}', 'is_active' => true, 'is_supported' => false],
        ]);
    }

    // --- Committee Structure: Flat ---

    public function test_flat_structure_created_without_geographic_fields(): void
    {
        $response = $this->actingAs($this->user)->post(route('organisations.store'), [
            'name' => 'Flat Org',
            'committee_structure' => 'flat',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('organisations', [
            'name' => 'Flat Org',
            'committee_structure' => 'flat',
            'geographic_scope' => null,
            'base_country_code' => null,
        ]);
    }

    public function test_committee_structure_defaults_to_flat_when_omitted(): void
    {
        $this->actingAs($this->user)->post(route('organisations.store'), ['name' => 'Simple Org']);

        $this->assertDatabaseHas('organisations', [
            'name' => 'Simple Org',
            'committee_structure' => 'flat',
        ]);
    }

    // --- Committee Structure: Geographical + scope variants ---

    public function test_geographical_worldwide_created_successfully(): void
    {
        $response = $this->actingAs($this->user)->post(route('organisations.store'), [
            'name' => 'NRNA World',
            'committee_structure' => 'geographical',
            'geographic_scope' => 'worldwide',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('organisations', [
            'name' => 'NRNA World',
            'geographic_scope' => 'worldwide',
        ]);
    }

    public function test_geographical_single_country_requires_base_country_code(): void
    {
        $response = $this->actingAs($this->user)->post(route('organisations.store'), [
            'name' => 'Single Org',
            'committee_structure' => 'geographical',
            'geographic_scope' => 'single_country',
            // base_country_code missing
        ]);

        $response->assertSessionHasErrors(['base_country_code']);
    }

    public function test_geographical_single_country_saved_correctly(): void
    {
        $this->actingAs($this->user)->post(route('organisations.store'), [
            'name' => 'CDU Germany',
            'committee_structure' => 'geographical',
            'geographic_scope' => 'single_country',
            'base_country_code' => 'DE',
        ]);

        $this->assertDatabaseHas('organisations', [
            'name' => 'CDU Germany',
            'geographic_scope' => 'single_country',
            'base_country_code' => 'DE',
        ]);
    }

    public function test_geographical_multi_country_saves_allowed_countries_as_array(): void
    {
        $this->actingAs($this->user)->post(route('organisations.store'), [
            'name' => 'NRNA Germany',
            'committee_structure' => 'geographical',
            'geographic_scope' => 'multi_country',
            'allowed_countries' => ['DE', 'AT', 'CH'],
        ]);

        $org = Organisation::where('name', 'NRNA Germany')->first();
        $this->assertNotNull($org);
        $this->assertEquals(['DE', 'AT', 'CH'], $org->allowed_countries);
    }

    public function test_multi_country_requires_at_least_two_countries(): void
    {
        $response = $this->actingAs($this->user)->post(route('organisations.store'), [
            'name' => 'One Country Org',
            'committee_structure' => 'geographical',
            'geographic_scope' => 'multi_country',
            'allowed_countries' => ['DE'], // only 1 — should use single_country instead
        ]);

        $response->assertSessionHasErrors(['allowed_countries']);
    }

    public function test_geographical_scope_required_when_structure_is_geographical(): void
    {
        $response = $this->actingAs($this->user)->post(route('organisations.store'), [
            'name' => 'Missing Scope Org',
            'committee_structure' => 'geographical',
            // geographic_scope missing
        ]);

        $response->assertSessionHasErrors(['geographic_scope']);
    }

    // --- Immutability enforcement ---

    public function test_geographic_config_cannot_be_changed_after_creation(): void
    {
        $org = Organisation::factory()->create([
            'committee_structure' => 'flat',
            'geographic_scope' => null,
        ]);

        $this->expectException(\DomainException::class);

        $org->update(['committee_structure' => 'geographical']);
    }

    public function test_all_geographic_fields_are_immutable(): void
    {
        $org = Organisation::factory()->create([
            'committee_structure' => 'geographical',
            'geographic_scope' => 'single_country',
            'base_country_code' => 'DE',
        ]);

        $this->expectException(\DomainException::class);

        $org->update(['base_country_code' => 'FR']);
    }

    // --- API Endpoints ---

    public function test_countries_api_returns_standardized_response(): void
    {
        $response = $this->actingAs($this->user)->get('/api/organisation-geography/countries');

        $response->assertOk();
        $response->assertJsonStructure(['data' => [['id', 'code', 'name', 'flag']]]);
        // Should have at least the 3 seeded countries
        $data = $response->json('data');
        $this->assertGreaterThanOrEqual(3, count($data));
    }
}

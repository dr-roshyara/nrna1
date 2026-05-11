<?php

declare(strict_types=1);

namespace Tests\Feature\Committee;

use App\Contexts\Geography\Domain\ValueObjects\GeographicStructure;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeType;
use App\Contexts\Membership\Infrastructure\Models\CommitteeModel;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class CommitteeCreationWithGeoSelectionsTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Organisation $organisation;

    protected function setUp(): void
    {
        parent::setUp();

        $this->organisation = Organisation::factory()->worldwide()->create();
        $this->user = User::factory()->create();

        // Insert pivot with ID generated
        \DB::table('user_organisation_roles')->insert([
            'id' => \Illuminate\Support\Str::uuid(),
            'user_id' => $this->user->id,
            'organisation_id' => $this->organisation->id,
            'role' => 'owner',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Test: Committee creation with valid geo_selections (region + country + geo)
     * Expected: Committee created with canonical geo_reference and region/country codes
     */
    public function test_create_committee_with_complete_geo_selections()
    {
        $this->actingAs($this->user);

        $response = $this->post(route('committees.store', $this->organisation), [
            'name' => 'Asia Region Committee',
            'code' => 'ASIA_REG',
            'type' => 'central',
            'geo_selections' => [
                'region' => 'asia',
                'country' => 'NP',
                'geo' => [],
            ],
        ]);

        $response->assertRedirect();

        $committee = CommitteeModel::withoutGlobalScopes()
            ->where('organisation_id', $this->organisation->id)
            ->where('name', 'Asia Region Committee')
            ->first();

        $this->assertNotNull($committee);
        $this->assertEquals('ASIA_REG', $committee->code);
        $this->assertEquals('central', $committee->type);
        $this->assertEquals('asia', $committee->region_code);
        $this->assertEquals('NP', $committee->country_code);
    }

    /**
     * Test: Committee creation with region + country only (no geo units)
     * Expected: Committee created with canonical format without geo path
     */
    public function test_create_committee_with_region_and_country_only()
    {
        $this->actingAs($this->user);

        $response = $this->post(route('committees.store', $this->organisation), [
            'name' => 'Europe Region Committee',
            'code' => 'EUR_REG',
            'type' => 'province',
            'geo_selections' => [
                'region' => 'europe',
                'country' => 'DE',
                'geo' => [],
            ],
        ]);

        $response->assertRedirect();

        $committee = CommitteeModel::withoutGlobalScopes()->where('organisation_id', $this->organisation->id)
            ->where('name', 'Europe Region Committee')
            ->first();

        $this->assertNotNull($committee);
        $this->assertEquals('DE', $committee->country_code);
        $this->assertEquals('europe', $committee->region_code);
        // geo_reference should be: region:europe.country:DE (no geo path)
        $this->assertStringContainsString('region:europe', $committee->geo_reference);
        $this->assertStringContainsString('country:DE', $committee->geo_reference);
        $this->assertStringNotContainsString('geo:', $committee->geo_reference);
    }

    /**
     * Test: Committee creation with no geo_selections (legacy flow)
     * Expected: Committee created without region/country codes
     */
    public function test_create_committee_without_geo_selections()
    {
        // Use Nepal scope which doesn't require region/country
        $nepali_org = Organisation::factory()->nepal()->create();

        \DB::table('user_organisation_roles')->insert([
            'user_id' => $this->user->id,
            'organisation_id' => $nepali_org->id,
            'role' => 'owner',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($this->user);

        $response = $this->post(route('committees.store', $nepali_org), [
            'name' => 'Nepal Central Committee',
            'code' => 'NPL_CENTRAL',
            'type' => 'central',
        ]);

        $response->assertRedirect();

        $committee = CommitteeModel::withoutGlobalScopes()->where('organisation_id', $nepali_org->id)
            ->where('name', 'Nepal Central Committee')
            ->first();

        $this->assertNotNull($committee);
        $this->assertNull($committee->region_code);
        $this->assertNull($committee->country_code);
    }

    /**
     * Test: Committee creation with mixed data (region + country, no explicit geo array)
     * Expected: Committee created with canonical format, empty geo path
     */
    public function test_create_committee_with_geo_selections_missing_geo_array()
    {
        $this->actingAs($this->user);

        $response = $this->post(route('committees.store', $this->organisation), [
            'name' => 'Africa Committee',
            'code' => 'AFR_COM',
            'type' => 'central',
            'geo_selections' => [
                'region' => 'africa',
                'country' => 'ZA',
                // 'geo' key missing
            ],
        ]);

        $response->assertRedirect();

        $committee = CommitteeModel::withoutGlobalScopes()->where('organisation_id', $this->organisation->id)
            ->where('name', 'Africa Committee')
            ->first();

        $this->assertNotNull($committee);
        $this->assertEquals('ZA', $committee->country_code);
        $this->assertEquals('africa', $committee->region_code);
    }

    /**
     * Test: Committee creation with invalid committee type
     * Expected: Validation error (type not in allowed list)
     */
    public function test_create_committee_with_invalid_type()
    {
        $this->actingAs($this->user);

        $response = $this->post(route('committees.store', $this->organisation), [
            'name' => 'Invalid Committee',
            'code' => 'INVALID',
            'type' => 'invalid_type',
            'geo_selections' => [
                'region' => 'asia',
                'country' => 'NP',
                'geo' => [],
            ],
        ]);

        $response->assertSessionHasErrors('type');

        $committee = CommitteeModel::where('code', 'INVALID')->first();
        $this->assertNull($committee);
    }

    /**
     * Test: Committee creation with missing required name
     * Expected: Validation error
     */
    public function test_create_committee_with_missing_name()
    {
        $this->actingAs($this->user);

        $response = $this->post(route('committees.store', $this->organisation), [
            'code' => 'NONAME',
            'type' => 'central',
            'geo_selections' => [
                'region' => 'asia',
                'country' => 'NP',
                'geo' => [],
            ],
        ]);

        $response->assertSessionHasErrors('name');

        $committee = CommitteeModel::where('code', 'NONAME')->first();
        $this->assertNull($committee);
    }

    /**
     * Test: Committee creation with missing required code
     * Expected: Validation error
     */
    public function test_create_committee_with_missing_code()
    {
        $this->actingAs($this->user);

        $response = $this->post(route('committees.store', $this->organisation), [
            'name' => 'No Code Committee',
            'type' => 'central',
            'geo_selections' => [
                'region' => 'asia',
                'country' => 'NP',
                'geo' => [],
            ],
        ]);

        $response->assertSessionHasErrors('code');

        $committee = CommitteeModel::where('name', 'No Code Committee')->first();
        $this->assertNull($committee);
    }

    /**
     * Test: Verify committee is created in correct organisation (tenant isolation)
     * Expected: Committee only exists in target organisation
     */
    public function test_committee_created_in_correct_organisation_only()
    {
        $other_org = Organisation::factory()->create();

        $this->actingAs($this->user);

        $response = $this->post(route('committees.store', $this->organisation), [
            'name' => 'Tenant Test Committee',
            'code' => 'TENANT_TEST',
            'type' => 'central',
            'geo_selections' => [
                'region' => 'asia',
                'country' => 'NP',
                'geo' => [],
            ],
        ]);

        $response->assertRedirect();

        // Committee should exist in target organisation
        $this->assertNotNull(
            CommitteeModel::withoutGlobalScopes()->where('organisation_id', $this->organisation->id)
                ->where('code', 'TENANT_TEST')
                ->first()
        );

        // Committee should NOT exist in other organisation
        $this->assertNull(
            CommitteeModel::withoutGlobalScopes()->where('organisation_id', $other_org->id)
                ->where('code', 'TENANT_TEST')
                ->first()
        );
    }

    /**
     * Test: User without permission cannot create committee
     * Expected: 403 Forbidden
     */
    public function test_user_without_permission_cannot_create_committee()
    {
        $unauthorised_user = User::factory()->create();

        $this->actingAs($unauthorised_user);

        $response = $this->post(route('committees.store', $this->organisation), [
            'name' => 'Unauthorised Committee',
            'code' => 'UNAUTH',
            'type' => 'central',
            'geo_selections' => [
                'region' => 'asia',
                'country' => 'NP',
                'geo' => [],
            ],
        ]);

        $response->assertForbidden();

        $committee = CommitteeModel::where('code', 'UNAUTH')->first();
        $this->assertNull($committee);
    }

    /**
     * Test: Unauthenticated user cannot create committee
     * Expected: Redirect to login
     */
    public function test_unauthenticated_user_cannot_create_committee()
    {
        $response = $this->post(route('committees.store', $this->organisation), [
            'name' => 'No Auth Committee',
            'code' => 'NOAUTH',
            'type' => 'central',
            'geo_selections' => [
                'region' => 'asia',
                'country' => 'NP',
                'geo' => [],
            ],
        ]);

        $response->assertRedirect(route('login'));

        $committee = CommitteeModel::where('code', 'NOAUTH')->first();
        $this->assertNull($committee);
    }

    /**
     * Test: Committee slug is generated correctly
     * Expected: Slug derived from committee name
     */
    public function test_committee_slug_generated_from_name()
    {
        $this->actingAs($this->user);

        $response = $this->post(route('committees.store', $this->organisation), [
            'name' => 'Test Committee Name With Spaces',
            'code' => 'SLUG_TEST',
            'type' => 'central',
            'geo_selections' => [
                'region' => 'asia',
                'country' => 'NP',
                'geo' => [],
            ],
        ]);

        $response->assertRedirect();

        $committee = CommitteeModel::withoutGlobalScopes()->where('organisation_id', $this->organisation->id)
            ->where('code', 'SLUG_TEST')
            ->first();

        $this->assertNotNull($committee);
        $this->assertNotEmpty($committee->slug);
        $this->assertEquals('test-committee-name-with-spaces', $committee->slug);
    }

    /**
     * Test: Duplicate committee code in same organisation is rejected
     * Expected: Validation error or database constraint error
     */
    public function test_duplicate_committee_code_in_same_organisation()
    {
        $existing = CommitteeModel::factory()->create([
            'organisation_id' => $this->organisation->id,
            'code' => 'DUP_CODE',
        ]);

        $this->actingAs($this->user);

        $response = $this->post(route('committees.store', $this->organisation), [
            'name' => 'Duplicate Committee',
            'code' => 'DUP_CODE',
            'type' => 'central',
            'geo_selections' => [
                'region' => 'asia',
                'country' => 'NP',
                'geo' => [],
            ],
        ]);

        // Should either have validation error or return to create page with error
        $response->assertRedirect();

        // Should still only have one committee with this code in this organisation
        $count = CommitteeModel::withoutGlobalScopes()->where('organisation_id', $this->organisation->id)
            ->where('code', 'DUP_CODE')
            ->count();
        $this->assertEquals(1, $count);
    }

    /**
     * Test: geo_reference stored in canonical format (not legacy)
     * Expected: geo_reference uses new format with region: and country: prefixes
     */
    public function test_geo_reference_stored_in_canonical_format()
    {
        $this->actingAs($this->user);

        $response = $this->post(route('committees.store', $this->organisation), [
            'name' => 'Canonical Format Test',
            'code' => 'CANONICAL',
            'type' => 'central',
            'geo_selections' => [
                'region' => 'asia',
                'country' => 'IN',
                'geo' => [3, 7],
            ],
        ]);

        $response->assertRedirect();

        $committee = CommitteeModel::withoutGlobalScopes()->where('organisation_id', $this->organisation->id)
            ->where('code', 'CANONICAL')
            ->first();

        $this->assertNotNull($committee);
        // Canonical format: region:asia.country:IN.geo:3.7
        $this->assertMatchesRegularExpression(
            '/region:asia.*country:IN.*geo:3\.7/',
            $committee->geo_reference
        );
    }
}

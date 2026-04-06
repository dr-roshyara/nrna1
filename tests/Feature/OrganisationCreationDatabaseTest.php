<?php

namespace Tests\Feature;

use App\Models\Organisation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class OrganisationCreationDatabaseTest extends TestCase
{
    use RefreshDatabase;

    protected function validOrganisationData(): array
    {
        return [
            'name' => 'Test Organisation',
            'email' => 'contact@testorg.de',
            'address' => [
                'street' => 'Main Street 42',
                'city' => 'Munich',
                'zip' => '80331',
                'country' => 'DE',
            ],
            'representative' => [
                'name' => 'Max Mustermann',
                'role' => 'Chairman',
                'email' => 'max@testorg.de',
                'is_self' => false,
            ],
            'accept_gdpr' => true,
            'accept_terms' => true,
        ];
    }

    /**
     * Test that database changes are atomic (all-or-nothing)
     *
     * If email sending fails, the entire transaction should be rolled back.
     * No organisation should be created in database.
     */
    public function test_organization_creation_is_atomic()
    {
        Mail::fake();
        // Simulate email failure by making Mail throw exception
        Mail::shouldReceive('send')->andThrow(new \Exception('Mail server error'));

        $user = User::factory()->create();
        $data = $this->validOrganisationData();

        $initialOrgCount = Organisation::count();
        $initialPivotCount = DB::table('organization_user')->count();

        // Attempt to create organisation (should fail)
        try {
            $this->actingAs($user)->postJson('/organisations', $data);
        } catch (\Exception $e) {
            // Expected to throw
        }

        // Verify organisation was NOT created
        $this->assertEquals($initialOrgCount, Organisation::count());

        // Verify pivot relationship was NOT created
        $this->assertEquals($initialPivotCount, DB::table('organization_user')->count());

        // Verify organisation doesn't exist by email
        $this->assertNull(Organisation::where('email', $data['email'])->first());
    }

    /**
     * Test that creator's organisation_id is updated correctly after creation
     */
    public function test_user_organisation_id_updated_correctly()
    {
        Mail::fake();

        $user = User::factory()->create(['organisation_id' => null]);
        $data = $this->validOrganisationData();

        $this->actingAs($user)->postJson('/organisations', $data);

        // Refresh user from database
        $user->refresh();

        // Verify organisation_id was set
        $this->assertNotNull($user->organisation_id);

        // Verify it matches the created organisation
        $org = Organisation::where('email', $data['email'])->first();
        $this->assertEquals($org->id, $user->organisation_id);
    }

    /**
     * Test that representative user's organisation_id is set correctly
     */
    public function test_representative_user_organisation_id_set()
    {
        Mail::fake();

        $creator = User::factory()->create();
        $data = $this->validOrganisationData();
        $data['representative']['is_self'] = false;
        $data['representative']['email'] = 'rep@neworg.de';

        $this->actingAs($creator)->postJson('/organisations', $data);

        // Get the organisation
        $org = Organisation::where('email', $data['email'])->first();

        // Get the representative user
        $rep = User::where('email', 'rep@neworg.de')->first();

        // Verify representative has organisation_id set
        $this->assertNotNull($rep->organisation_id);
        $this->assertEquals($org->id, $rep->organisation_id);
    }

    /**
     * Test that duplicate organisation names are rejected
     */
    public function test_duplicate_organization_name_rejected()
    {
        Mail::fake();

        $user = User::factory()->create();
        $data = $this->validOrganisationData();
        $data['name'] = 'Unique Organisation Name';

        // Create first organisation
        $response1 = $this->actingAs($user)->postJson('/organisations', $data);
        $response1->assertStatus(201);

        // Verify it was created
        $org1 = Organisation::where('name', 'Unique Organisation Name')->first();
        $this->assertNotNull($org1);

        // Create second user to avoid auth issues
        $user2 = User::factory()->create();

        // Attempt to create organisation with same name
        $data['email'] = 'other@org.de'; // Different email
        $response2 = $this->actingAs($user2)->postJson('/organisations', $data);

        // Should fail validation
        $response2->assertStatus(422);
        $response2->assertJsonValidationErrors(['name']);

        // Verify only one organisation exists with that name
        $count = Organisation::where('name', 'Unique Organisation Name')->count();
        $this->assertEquals(1, $count);
    }

    /**
     * Test that duplicate organisation emails are rejected
     */
    public function test_duplicate_organization_email_rejected()
    {
        Mail::fake();

        $user1 = User::factory()->create();
        $data = $this->validOrganisationData();
        $data['email'] = 'unique@org.de';

        // Create first organisation
        $response1 = $this->actingAs($user1)->postJson('/organisations', $data);
        $response1->assertStatus(201);

        // Create second user
        $user2 = User::factory()->create();

        // Attempt to create organisation with same email
        $data['name'] = 'Different Organisation Name';
        $response2 = $this->actingAs($user2)->postJson('/organisations', $data);

        // Should fail validation
        $response2->assertStatus(422);
        $response2->assertJsonValidationErrors(['email']);

        // Verify only one organisation exists with that email
        $count = Organisation::where('email', 'unique@org.de')->count();
        $this->assertEquals(1, $count);
    }

    /**
     * Test that organisation slug is generated correctly from name
     */
    public function test_organization_slug_generated_correctly()
    {
        Mail::fake();

        $user = User::factory()->create();

        // Test case 1: Simple name
        $data1 = $this->validOrganisationData();
        $data1['name'] = 'NRNA Europe';
        $data1['email'] = 'org1@test.de';

        $this->actingAs($user)->postJson('/organisations', $data1);

        $org1 = Organisation::where('email', 'org1@test.de')->first();
        $this->assertEquals('nrna-europe', $org1->slug);

        // Test case 2: Name with numbers and special chars
        $data2 = $this->validOrganisationData();
        $data2['name'] = 'Test Org 123 - Germany';
        $data2['email'] = 'org2@test.de';

        $this->actingAs($user)->postJson('/organisations', $data2);

        $org2 = Organisation::where('email', 'org2@test.de')->first();
        $this->assertEquals('test-org-123-germany', $org2->slug);

        // Test case 3: Name with umlauts
        $data3 = $this->validOrganisationData();
        $data3['name'] = 'Müller Verein';
        $data3['email'] = 'org3@test.de';

        $this->actingAs($user)->postJson('/organisations', $data3);

        $org3 = Organisation::where('email', 'org3@test.de')->first();
        $this->assertIsString($org3->slug);
        $this->assertStringNotContainsString(' ', $org3->slug);
    }

    /**
     * Test that address is stored as JSON
     */
    public function test_address_stored_as_json()
    {
        Mail::fake();

        $user = User::factory()->create();
        $data = $this->validOrganisationData();
        $data['address'] = [
            'street' => 'Europastrasse 42',
            'city' => 'Vienna',
            'zip' => '10115',
            'country' => 'AT',
        ];

        $this->actingAs($user)->postJson('/organisations', $data);

        $org = Organisation::where('email', $data['email'])->first();

        // Verify address is stored as array
        $this->assertIsArray($org->address);
        $this->assertEquals('Europastrasse 42', $org->address['street']);
        $this->assertEquals('Vienna', $org->address['city']);
        $this->assertEquals('10115', $org->address['zip']);
        $this->assertEquals('AT', $org->address['country']);
    }

    /**
     * Test that creator_id is set correctly
     */
    public function test_creator_id_is_set()
    {
        Mail::fake();

        $user = User::factory()->create();
        $data = $this->validOrganisationData();

        $this->actingAs($user)->postJson('/organisations', $data);

        $org = Organisation::where('email', $data['email'])->first();

        // Verify creator_id matches logged-in user
        $this->assertEquals($user->id, $org->created_by);
    }

    /**
     * Test that creator is attached as admin
     */
    public function test_creator_attached_as_admin()
    {
        Mail::fake();

        $user = User::factory()->create();
        $data = $this->validOrganisationData();

        $this->actingAs($user)->postJson('/organisations', $data);

        $org = Organisation::where('email', $data['email'])->first();

        // Verify pivot relationship
        $this->assertTrue(
            $org->users()
                ->where('user_id', $user->id)
                ->wherePivot('role', 'admin')
                ->exists()
        );
    }

    /**
     * Test that assigned_at timestamp is set
     */
    public function test_assigned_at_timestamp_is_set()
    {
        Mail::fake();

        $user = User::factory()->create();
        $data = $this->validOrganisationData();

        $before = now();
        $this->actingAs($user)->postJson('/organisations', $data);
        $after = now();

        $org = Organisation::where('email', $data['email'])->first();

        $pivot = DB::table('organization_user')
            ->where('organisation_id', $org->id)
            ->where('user_id', $user->id)
            ->first();

        // Verify assigned_at is set
        $this->assertNotNull($pivot->assigned_at);

        // Verify it's within reasonable time window
        $assignedAt = \Carbon\Carbon::parse($pivot->assigned_at);
        $this->assertTrue($assignedAt->between($before, $after));
    }
}

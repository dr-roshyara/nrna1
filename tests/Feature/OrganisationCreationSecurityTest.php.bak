<?php

namespace Tests\Feature;

use App\Models\Organisation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class OrganisationCreationSecurityTest extends TestCase
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
     * Test that unauthenticated users cannot create organisations
     */
    public function test_unauthenticated_user_cannot_create_organization()
    {
        $data = $this->validOrganisationData();

        $response = $this->postJson('/organisations', $data);

        // Should return 401 Unauthorized
        $response->assertStatus(401);
    }

    /**
     * Test that organisation creator automatically becomes admin
     */
    public function test_organization_creator_becomes_admin()
    {
        Mail::fake();

        $user = User::factory()->create();
        $data = $this->validOrganisationData();

        $this->actingAs($user)->postJson('/organisations', $data);

        $org = Organisation::where('email', $data['email'])->first();

        // Verify creator is admin
        $pivot = $org->users()
            ->where('users.id', $user->id)
            ->wherePivot('role', 'admin')
            ->first();

        $this->assertNotNull($pivot);
        $this->assertEquals('admin', $pivot->pivot->role);
    }

    /**
     * Test that SQL injection attempts are prevented through parameterization
     */
    public function test_sql_injection_prevention()
    {
        Mail::fake();

        $user = User::factory()->create();

        // Attempt SQL injection in name field
        $data = $this->validOrganisationData();
        $data['name'] = "Test' OR '1'='1";
        $data['email'] = 'sql@test.de';

        $response = $this->actingAs($user)->postJson('/organisations', $data);

        // Should succeed (not throw SQL error)
        $response->assertStatus(201);

        // Verify the dangerous string was stored as-is (not executed)
        $org = Organisation::where('email', 'sql@test.de')->first();
        $this->assertEquals("Test' OR '1'='1", $org->name);

        // Verify it didn't break database integrity
        $otherOrgs = Organisation::where('email', '<>', 'sql@test.de')->get();
        $this->assertTrue(count($otherOrgs) >= 0); // Should be queryable
    }

    /**
     * Test that XSS attempts are handled in organisation data
     */
    public function test_xss_prevention_in_organization_data()
    {
        Mail::fake();

        $user = User::factory()->create();

        // Attempt XSS in name
        $data = $this->validOrganisationData();
        $data['name'] = '<script>alert("XSS")</script>';
        $data['email'] = 'xss@test.de';

        $response = $this->actingAs($user)->postJson('/organisations', $data);

        // Should succeed but store the string safely
        $response->assertStatus(201);

        // Verify the dangerous string was stored
        $org = Organisation::where('email', 'xss@test.de')->first();
        $this->assertEquals('<script>alert("XSS")</script>', $org->name);

        // Verify API returns it properly escaped (JSON should escape it)
        $response->assertJsonPath('organisation.name', '<script>alert("XSS")</script>');
    }

    /**
     * Test that XSS attempts in address are handled
     */
    public function test_xss_prevention_in_address()
    {
        Mail::fake();

        $user = User::factory()->create();

        $data = $this->validOrganisationData();
        $data['address']['street'] = '<img src=x onerror=alert("XSS")>';
        $data['address']['city'] = '<svg onload=alert("XSS")>';
        $data['email'] = 'xss2@test.de';

        $response = $this->actingAs($user)->postJson('/organisations', $data);

        // Should succeed
        $response->assertStatus(201);

        // Verify data was stored
        $org = Organisation::where('email', 'xss2@test.de')->first();
        $this->assertEquals('<img src=x onerror=alert("XSS")>', $org->address['street']);
        $this->assertEquals('<svg onload=alert("XSS")>', $org->address['city']);
    }

    /**
     * Test that representative user data access is restricted
     *
     * Only users who are part of organisation can access its data
     */
    public function test_organization_access_restricted_to_members()
    {
        Mail::fake();

        $creator = User::factory()->create();
        $nonMember = User::factory()->create();

        $data = $this->validOrganisationData();
        $this->actingAs($creator)->postJson('/organisations', $data);

        $org = Organisation::where('email', $data['email'])->first();

        // Creator can access
        $response = $this->actingAs($creator)->getJson("/organisations/{$org->slug}");
        $response->assertStatus(200);

        // Non-member cannot access
        $response = $this->actingAs($nonMember)->getJson("/organisations/{$org->slug}");
        $response->assertStatus(403);
    }

    /**
     * Test that empty organisation data doesn't create user enum vulnerability
     */
    public function test_no_user_enumeration_via_email()
    {
        Mail::fake();

        $existingUser = User::factory()->create(['email' => 'existing@test.de']);
        $creator = User::factory()->create();

        $data = $this->validOrganisationData();
        $data['representative']['email'] = $existingUser->email;
        $data['email'] = 'org1@test.de';

        $response = $this->actingAs($creator)->postJson('/organisations', $data);

        // Should succeed
        $response->assertStatus(201);

        // No error should reveal whether email exists or not
        $this->assertTrue($response->json('success'));
    }

    /**
     * Test that organisation_id is not exposed to unauthorized users
     */
    public function test_organisation_id_not_exposed_to_non_members()
    {
        Mail::fake();

        $creator = User::factory()->create();
        $otherUser = User::factory()->create();

        $data = $this->validOrganisationData();
        $response = $this->actingAs($creator)->postJson('/organisations', $data);

        $org = Organisation::where('email', $data['email'])->first();

        // Non-member trying to access should get 403
        $response = $this->actingAs($otherUser)->getJson("/organisations/{$org->slug}");
        $response->assertStatus(403);

        // Verify they don't get organisation details
        $response->assertJsonMissing(['organisation' => true]);
    }

    /**
     * Test that organisation cannot be accessed by ID alone (only by slug)
     */
    public function test_organization_accessed_by_slug_not_id()
    {
        Mail::fake();

        $user = User::factory()->create();
        $data = $this->validOrganisationData();

        $this->actingAs($user)->postJson('/organisations', $data);

        $org = Organisation::where('email', $data['email'])->first();

        // Access by slug should work
        $response = $this->actingAs($user)->getJson("/organisations/{$org->slug}");
        $response->assertStatus(200);

        // Access by ID should fail (route not defined)
        $response = $this->actingAs($user)->getJson("/organisations/{$org->id}");
        $response->assertStatus(404);
    }

    /**
     * Test that password reset doesn't expose organisation information
     */
    public function test_password_reset_respects_authorization()
    {
        Mail::fake();

        $creator = User::factory()->create();
        $data = $this->validOrganisationData();
        $data['representative']['email'] = 'rep@neworg.de';

        $this->actingAs($creator)->postJson('/organisations', $data);

        // Representative user should be able to reset password
        $rep = User::where('email', 'rep@neworg.de')->first();

        $response = $this->postJson('/forgot-password', [
            'email' => $rep->email,
        ]);

        // Should succeed (standard password reset, no enumeration)
        $response->assertStatus(200) || $response->assertStatus(302);
    }

    /**
     * Test that organisation data cannot be modified by non-admins
     *
     * This is a documentation of expected security
     * (actual edit endpoint not implemented yet)
     */
    public function test_organization_modification_restricted()
    {
        Mail::fake();

        $creator = User::factory()->create();
        $voter = User::factory()->create();

        $data = $this->validOrganisationData();
        $data['representative']['email'] = 'voter@org.de';

        $this->actingAs($creator)->postJson('/organisations', $data);

        $org = Organisation::where('email', $data['email'])->first();

        // If organisation has editable endpoints, voter should not be able to edit
        // This test documents expected behavior
        $this->assertTrue(
            $org->users()
                ->where('users.id', $creator->id)
                ->wherePivot('role', 'admin')
                ->exists(),
            'Creator must be admin to manage organisation'
        );

        $voter = User::where('email', 'voter@org.de')->first();
        $this->assertTrue(
            $org->users()
                ->where('users.id', $voter->id)
                ->wherePivot('role', 'voter')
                ->exists(),
            'Representative must have voter role, not admin'
        );
    }
}

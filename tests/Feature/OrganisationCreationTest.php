<?php

namespace Tests\Feature;

use App\Models\Organisation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class OrganisationCreationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that organisation creation form requires valid data
     */
    public function test_organization_creation_requires_name_and_email()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/organisations', [
            'name' => '',
            'email' => '',
            'address' => [],
            'representative' => [],
            'accept_gdpr' => true,
            'accept_terms' => true,
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['name', 'email']);
    }

    /**
     * Test that email must be valid
     */
    public function test_organization_creation_requires_valid_email()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/organisations', [
            'name' => 'Test Organisation',
            'email' => 'invalid-email',
            'address' => [
                'street' => 'Test Street 1',
                'city' => 'Test City',
                'zip' => '12345',
                'country' => 'DE',
            ],
            'representative' => [
                'name' => 'John Doe',
                'role' => 'Chairman',
                'email' => 'john@example.com',
                'is_self' => false,
            ],
            'accept_gdpr' => true,
            'accept_terms' => true,
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['email']);
    }

    /**
     * Test successful organisation creation
     */
    public function test_organization_creation_with_self_representative()
    {
        Mail::fake();

        $user = User::factory()->create();

        $payload = [
            'name' => 'Test Association',
            'email' => 'board@example.com',
            'address' => [
                'street' => 'Main Street 42',
                'city' => 'Munich',
                'zip' => '80331',
                'country' => 'DE',
            ],
            'representative' => [
                'name' => 'Max Mustermann',
                'role' => 'Chairman',
                'email' => '',
                'is_self' => true,
            ],
            'accept_gdpr' => true,
            'accept_terms' => true,
        ];

        $response = $this->actingAs($user)
                        ->postJson('/organisations', $payload);

        // Should succeed
        $response->assertStatus(201);
        $response->assertJsonPath('success', true);

        // Organisation should be created
        $org = Organisation::where('name', 'Test Association')->first();
        $this->assertNotNull($org);
        $this->assertEquals('board@example.com', $org->email);
        $this->assertEquals('test-association', $org->slug);
        $this->assertEquals($user->id, $org->created_by);

        // Address should be stored as JSON
        $this->assertIsArray($org->address);
        $this->assertEquals('Munich', $org->address['city']);

        // User should be attached as admin
        $this->assertTrue(
            $org->users()
                ->wherePivot('role', 'admin')
                ->where('users.id', $user->id)
                ->exists()
        );

        // No representative user should be created (is_self = true)
        $this->assertFalse(
            User::where('email', 'other@example.com')->exists()
        );
    }

    /**
     * Test organisation creation with external representative
     */
    public function test_organization_creation_with_external_representative()
    {
        Mail::fake();

        $user = User::factory()->create();

        $payload = [
            'name' => 'Community Group',
            'email' => 'contact@community.de',
            'address' => [
                'street' => 'Community Lane 1',
                'city' => 'Berlin',
                'zip' => '10115',
                'country' => 'DE',
            ],
            'representative' => [
                'name' => 'Anna Schmidt',
                'role' => 'Vice President',
                'email' => 'anna.schmidt@community.de',
                'is_self' => false,
            ],
            'accept_gdpr' => true,
            'accept_terms' => true,
        ];

        $response = $this->actingAs($user)
                        ->postJson('/organisations', $payload);

        $response->assertStatus(201);
        $response->assertJsonPath('success', true);

        // Organisation should be created
        $org = Organisation::where('name', 'Community Group')->first();
        $this->assertNotNull($org);

        // Representative user should be created
        $repUser = User::where('email', 'anna.schmidt@community.de')->first();
        $this->assertNotNull($repUser);
        $this->assertEquals('Anna Schmidt', $repUser->name);

        // Representative should be attached as voter
        $this->assertTrue(
            $org->users()
                ->wherePivot('role', 'voter')
                ->where('users.id', $repUser->id)
                ->exists()
        );

        // Creator should be attached as admin
        $this->assertTrue(
            $org->users()
                ->wherePivot('role', 'admin')
                ->where('users.id', $user->id)
                ->exists()
        );
    }

    /**
     * Test that organisation can be retrieved by authenticated user
     */
    public function test_organization_show_requires_membership()
    {
        $user = User::factory()->create();
        $org = Organisation::factory()->create(['created_by' => $user->id]);
        $org->users()->attach($user->id, ['role' => 'admin']);

        $response = $this->actingAs($user)->getJson("/organisations/{$org->slug}");
        $response->assertStatus(200);
    }

    /**
     * Test that non-members cannot access organisation
     */
    public function test_organization_show_denies_non_members()
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $org = Organisation::factory()->create(['created_by' => $user->id]);
        $org->users()->attach($user->id, ['role' => 'admin']);

        $response = $this->actingAs($other)->getJson("/organisations/{$org->slug}");
        $response->assertStatus(403);
    }

    /**
     * Test that organisation requires authentication
     */
    public function test_organization_creation_requires_auth()
    {
        $response = $this->postJson('/organisations', [
            'name' => 'Test',
            'email' => 'test@example.com',
        ]);

        $response->assertStatus(401);
    }
}

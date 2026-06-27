<?php

namespace Tests\Feature\Organisation;

use App\Models\User;
use App\Models\Organisation;
use Tests\TestCase;

class OrganisationCreationMembershipTest extends TestCase
{
    use \Illuminate\Foundation\Testing\RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_creating_org_with_election_only_mode_persists_voter_source_strategy()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('organisations.store'), [
            'name' => 'Election-Only Org',
            'email' => 'test@org.example',
            'representative' => 'Test Rep',
            'languages' => ['en'],
            'uses_full_membership' => false,
        ]);

        $response->assertRedirect();

        $org = Organisation::where('name', 'Election-Only Org')->first();
        $this->assertNotNull($org);
        $this->assertFalse($org->uses_full_membership);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_creating_org_with_full_membership_mode_persists_voter_source_strategy()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('organisations.store'), [
            'name' => 'Full Membership Org',
            'email' => 'test@org.example',
            'representative' => 'Test Rep',
            'languages' => ['en'],
            'uses_full_membership' => true,
        ]);

        $response->assertRedirect();

        $org = Organisation::where('name', 'Full Membership Org')->first();
        $this->assertNotNull($org);
        $this->assertTrue($org->uses_full_membership);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_organisation_defaults_to_full_membership_when_mode_omitted()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('organisations.store'), [
            'name' => 'Default Org',
            'email' => 'test@org.example',
            'representative' => 'Test Rep',
            'languages' => ['en'],
            // uses_full_membership not provided
        ]);

        $response->assertRedirect();

        $org = Organisation::where('name', 'Default Org')->first();
        $this->assertNotNull($org);
        $this->assertTrue($org->uses_full_membership); // Should default to true
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_creator_becomes_owner_regardless_of_voter_source_mode()
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('organisations.store'), [
            'name' => 'Owner Test Org',
            'uses_full_membership' => false,
        ]);

        $org = Organisation::where('name', 'Owner Test Org')->first();
        $this->assertNotNull($org);

        $this->assertDatabaseHas('user_organisation_roles', [
            'user_id' => $user->id,
            'organisation_id' => $org->id,
            'role' => 'owner',
        ]);
    }
}

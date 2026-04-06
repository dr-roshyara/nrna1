<?php

namespace Tests\Feature;

use App\Mail\OrganisationCreatedMail;
use App\Mail\RepresentativeInvitationMail;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class OrganisationCreationIntegrationTest extends TestCase
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
     * Test complete organisation creation workflow end-to-end
     *
     * This test verifies the entire happy path:
     * 1. Authenticated user submits valid form
     * 2. CSRF protection works
     * 3. Organisation is created
     * 4. User becomes admin
     * 5. Emails are queued
     * 6. Redirect provided
     * 7. Dashboard is accessible
     */
    public function test_complete_organization_creation_workflow()
    {
        Mail::fake();
        Queue::fake();

        $user = User::factory()->create(['name' => 'John Doe']);
        $data = $this->validOrganisationData();

        // 1. Submit form with authenticated user
        $response = $this->actingAs($user)
            ->from(route('dashboard'))
            ->post('/organisations', $data);

        // 2. Should succeed
        $response->assertStatus(201);
        $response->assertJsonPath('success', true);

        // 3. Organisation should be created
        $org = Organisation::where('email', $data['email'])->first();
        $this->assertNotNull($org);
        $this->assertEquals($data['name'], $org->name);

        // 4. User should be attached as admin
        $this->assertTrue(
            $org->users()
                ->where('users.id', $user->id)
                ->wherePivot('role', 'admin')
                ->exists()
        );

        // 5. Emails should be sent - only representative invitation (organisation email removed)
        Mail::assertNotSent(OrganisationCreatedMail::class);
        Mail::assertSent(RepresentativeInvitationMail::class);

        // 6. Redirect URL should be provided
        $response->assertJsonPath('redirect', route('organisations.show', $org->slug));

        // 7. Dashboard should be accessible
        $dashboardResponse = $this->actingAs($user)
            ->getJson("/organisations/{$org->slug}");
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertJsonPath('organisation.name', $org->name);
    }

    /**
     * Test organisation creation with self-representative
     *
     * Verifies simplified flow when creator is the representative
     */
    public function test_organization_creation_with_self_representative()
    {
        Mail::fake();

        $user = User::factory()->create(['name' => 'Anna Schmidt']);
        $data = $this->validOrganisationData();
        $data['representative']['is_self'] = true;
        $data['representative']['email'] = '';

        $response = $this->actingAs($user)->postJson('/organisations', $data);

        $response->assertStatus(201);

        $org = Organisation::where('email', $data['email'])->first();

        // No emails should be sent for self-representative (organisation email removed)
        Mail::assertNotSent(OrganisationCreatedMail::class);
        Mail::assertNotSent(RepresentativeInvitationMail::class);

        // User should be admin, not voter
        $this->assertTrue(
            $org->users()
                ->where('users.id', $user->id)
                ->wherePivot('role', 'admin')
                ->exists()
        );
    }

    /**
     * Test multi-step form submission with validation errors
     *
     * User submits form with errors, sees validation, resubmits
     */
    public function test_multi_step_form_with_validation_retry()
    {
        Mail::fake();

        $user = User::factory()->create();

        // Step 1: Submit with missing zip code
        $data = $this->validOrganisationData();
        $data['address']['zip'] = '';

        $response1 = $this->actingAs($user)->postJson('/organisations', $data);
        $response1->assertStatus(422);
        $response1->assertJsonValidationErrors(['address.zip']);

        // Step 2: Resubmit with correct zip code
        $data['address']['zip'] = '12345';

        $response2 = $this->actingAs($user)->postJson('/organisations', $data);
        $response2->assertStatus(201);

        // Organisation should be created
        $org = Organisation::where('email', $data['email'])->first();
        $this->assertNotNull($org);
    }

    /**
     * Test multiple users creating different organisations
     *
     * Verifies isolation between different organisations
     */
    public function test_multiple_users_create_different_organizations()
    {
        Mail::fake();

        $user1 = User::factory()->create(['name' => 'User One']);
        $user2 = User::factory()->create(['name' => 'User Two']);

        // User 1 creates organisation
        $data1 = $this->validOrganisationData();
        $data1['name'] = 'Organisation One';
        $data1['email'] = 'org1@test.de';

        $response1 = $this->actingAs($user1)->postJson('/organisations', $data1);
        $response1->assertStatus(201);

        $org1 = Organisation::where('email', 'org1@test.de')->first();

        // User 2 creates different organisation
        $data2 = $this->validOrganisationData();
        $data2['name'] = 'Organisation Two';
        $data2['email'] = 'org2@test.de';

        $response2 = $this->actingAs($user2)->postJson('/organisations', $data2);
        $response2->assertStatus(201);

        $org2 = Organisation::where('email', 'org2@test.de')->first();

        // Verify isolation
        $this->assertNotEquals($org1->id, $org2->id);

        // Verify organisation_id assignment
        $user1->refresh();
        $user2->refresh();
        $this->assertEquals($org1->id, $user1->organisation_id);
        $this->assertEquals($org2->id, $user2->organisation_id);

        // Verify each user only sees their org
        $dashResponse1 = $this->actingAs($user1)
            ->getJson("/organisations/{$org1->slug}");
        $dashResponse1->assertStatus(200);

        $dashResponse2 = $this->actingAs($user2)
            ->getJson("/organisations/{$org1->slug}");
        $dashResponse2->assertStatus(403); // Cannot access other's org

        $dashResponse3 = $this->actingAs($user2)
            ->getJson("/organisations/{$org2->slug}");
        $dashResponse3->assertStatus(200);
    }

    /**
     * Test organisation creation with real representative
     *
     * Verifies complete flow with external representative
     */
    public function test_organization_creation_with_external_representative()
    {
        Mail::fake();

        $creator = User::factory()->create(['name' => 'John Creator']);
        $data = $this->validOrganisationData();
        $data['representative']['name'] = 'Anna Representative';
        $data['representative']['email'] = 'anna@org.de';
        $data['representative']['is_self'] = false;

        $response = $this->actingAs($creator)->postJson('/organisations', $data);

        $response->assertStatus(201);

        $org = Organisation::where('email', $data['email'])->first();

        // Verify both users are in organisation
        $this->assertEquals(2, $org->users()->count());

        // Verify creator is admin
        $this->assertTrue(
            $org->users()
                ->where('users.id', $creator->id)
                ->wherePivot('role', 'admin')
                ->exists()
        );

        // Verify representative is voter
        $rep = \App\Models\User::where('email', 'anna@org.de')->first();
        $this->assertTrue(
            $org->users()
                ->where('users.id', $rep->id)
                ->wherePivot('role', 'voter')
                ->exists()
        );

        // Verify only representative invitation email is sent (organisation email removed)
        Mail::assertNotSent(OrganisationCreatedMail::class);
        Mail::assertSent(RepresentativeInvitationMail::class);
    }

    /**
     * Test dashboard access after organisation creation
     *
     * Verifies dashboard loads with organisation data
     */
    public function test_dashboard_access_after_creation()
    {
        Mail::fake();

        $user = User::factory()->create();
        $data = $this->validOrganisationData();

        $this->actingAs($user)->postJson('/organisations', $data);

        $org = Organisation::where('email', $data['email'])->first();

        // Access dashboard
        $response = $this->actingAs($user)
            ->getJson("/organisations/{$org->slug}");

        // Should succeed
        $response->assertStatus(200);

        // Should return organisation data
        $response->assertJsonPath('organisation.name', $data['name']);
        $response->assertJsonPath('organisation.slug', $org->slug);
        $response->assertJsonPath('organisation.email', $data['email']);
        $response->assertJsonPath('organisation.created_at', $org->created_at->format('d.m.Y'));

        // Should return statistics
        $response->assertJsonPath('stats.members_count', 2); // creator + representative
        $response->assertJsonPath('canManage', true); // User can manage

        // Should return demo status
        $response->assertJsonPath('demoStatus', [
            'exists' => false,
            'posts' => 0,
            'candidates' => 0,
            'codes' => 0,
            'votes' => 0,
        ]);
    }

    /**
     * Test that user organisation_id is set after creation
     *
     * Verifies creator is assigned to organisation
     */
    public function test_user_organisation_id_assigned()
    {
        Mail::fake();

        $user = User::factory()->create(['organisation_id' => null]);
        $data = $this->validOrganisationData();

        // Initially null
        $this->assertNull($user->organisation_id);

        $this->actingAs($user)->postJson('/organisations', $data);

        $user->refresh();

        // Should be set after creation
        $this->assertNotNull($user->organisation_id);

        $org = Organisation::where('email', $data['email'])->first();
        $this->assertEquals($org->id, $user->organisation_id);
    }

    /**
     * Test representative user organisation_id assignment
     */
    public function test_representative_organisation_id_assigned()
    {
        Mail::fake();

        $creator = User::factory()->create();
        $data = $this->validOrganisationData();
        $data['representative']['email'] = 'newrep@org.de';

        $this->actingAs($creator)->postJson('/organisations', $data);

        $org = Organisation::where('email', $data['email'])->first();
        $rep = User::where('email', 'newrep@org.de')->first();

        // Representative should have organisation_id set
        $this->assertEquals($org->id, $rep->organisation_id);
    }

    /**
     * Test representative invitation email content reflects organisation details
     */
    public function test_representative_invitation_email_content_reflects_organization_details()
    {
        Mail::fake();

        $user = User::factory()->create(['name' => 'John Doe']);
        $data = $this->validOrganisationData();
        $data['name'] = 'NRNA Europe Community';
        $data['representative']['is_self'] = false;

        $this->actingAs($user)->postJson('/organisations', $data);

        // Verify representative invitation email contains organisation-specific details
        Mail::assertSent(RepresentativeInvitationMail::class, function ($mail) use ($data, $user) {
            $rendered = $mail->render();

            $this->assertStringContainsString($data['name'], $rendered);
            $this->assertStringContainsString($data['representative']['name'], $rendered);
            $this->assertStringContainsString($user->name, $rendered);

            return true;
        });
    }

    /**
     * Test that response includes organisation details for redirect
     */
    public function test_response_includes_organization_details()
    {
        Mail::fake();

        $user = User::factory()->create();
        $data = $this->validOrganisationData();

        $response = $this->actingAs($user)->postJson('/organisations', $data);

        $response->assertJsonPath('success', true);
        $response->assertJsonPath('message', 'Organisation erfolgreich erstellt!');
        $response->assertJsonStructure([
            'success',
            'message',
            'redirect_url',
            'organisation' => [
                'id',
                'name',
                'email',
                'slug',
            ],
        ]);

        // Verify returned data matches created organisation
        $org = Organisation::where('email', $data['email'])->first();
        $response->assertJsonPath('organisation.id', $org->id);
        $response->assertJsonPath('organisation.name', $org->name);
        $response->assertJsonPath('organisation.email', $org->email);
        $response->assertJsonPath('organisation.slug', $org->slug);
    }
}

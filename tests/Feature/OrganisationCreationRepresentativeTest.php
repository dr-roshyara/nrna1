<?php

namespace Tests\Feature;

use App\Models\Organisation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class OrganisationCreationRepresentativeTest extends TestCase
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
     * Test that representative user is created if email doesn't exist
     */
    public function test_representative_user_created_if_not_exists()
    {
        Mail::fake();

        $creator = User::factory()->create();
        $data = $this->validOrganisationData();
        $data['representative']['email'] = 'newrep@org.de';
        $data['representative']['name'] = 'Anna Schmidt';

        $initialUserCount = User::count();

        $this->actingAs($creator)->postJson('/organisations', $data);

        // Verify new user was created
        $this->assertEquals($initialUserCount + 1, User::count());

        // Verify user has correct name
        $rep = User::where('email', 'newrep@org.de')->first();
        $this->assertNotNull($rep);
        $this->assertEquals('Anna Schmidt', $rep->name);
    }

    /**
     * Test that representative user has random password (unverified)
     */
    public function test_representative_user_has_random_password()
    {
        Mail::fake();

        $creator = User::factory()->create();
        $data = $this->validOrganisationData();
        $data['representative']['email'] = 'rep1@org.de';

        $this->actingAs($creator)->postJson('/organisations', $data);

        $rep = User::where('email', 'rep1@org.de')->first();

        // Verify password is hashed (not empty, not plain text)
        $this->assertNotNull($rep->password);
        $this->assertNotEquals('', $rep->password);
        // Password should be hashed (bcrypt produces 60 char hash)
        $this->assertEquals(60, strlen($rep->password));
    }

    /**
     * Test that representative user email is not verified
     */
    public function test_representative_user_email_not_verified()
    {
        Mail::fake();

        $creator = User::factory()->create();
        $data = $this->validOrganisationData();
        $data['representative']['email'] = 'rep2@org.de';

        $this->actingAs($creator)->postJson('/organisations', $data);

        $rep = User::where('email', 'rep2@org.de')->first();

        // Verify email is not yet verified
        $this->assertNull($rep->email_verified_at);
    }

    /**
     * Test that existing representative user is reused if email exists
     */
    public function test_existing_representative_user_reused()
    {
        Mail::fake();

        // Create existing user
        $existingUser = User::factory()->create([
            'email' => 'existing@org.de',
            'name' => 'John Existing',
        ]);

        $initialUserCount = User::count();

        $creator = User::factory()->create();
        $data = $this->validOrganisationData();
        $data['representative']['email'] = 'existing@org.de';
        $data['representative']['name'] = 'Different Name'; // Name in form is ignored

        $this->actingAs($creator)->postJson('/organisations', $data);

        // Verify no new user was created
        $this->assertEquals($initialUserCount, User::count());

        // Verify the existing user is still there
        $user = User::where('email', 'existing@org.de')->first();
        $this->assertNotNull($user);
        // Name should NOT be updated (firstOrCreate only creates, doesn't update)
        $this->assertEquals('John Existing', $user->name);
    }

    /**
     * Test that representative is attached with voter role
     */
    public function test_representative_attached_as_voter()
    {
        Mail::fake();

        $creator = User::factory()->create();
        $data = $this->validOrganisationData();
        $data['representative']['email'] = 'voter@org.de';

        $this->actingAs($creator)->postJson('/organisations', $data);

        $org = Organisation::where('email', $data['email'])->first();
        $rep = User::where('email', 'voter@org.de')->first();

        // Verify relationship exists with voter role
        $this->assertTrue(
            $org->users()
                ->where('users.id', $rep->id)
                ->wherePivot('role', 'voter')
                ->exists()
        );
    }

    /**
     * Test that creator is attached with admin role
     */
    public function test_creator_attached_as_admin()
    {
        Mail::fake();

        $creator = User::factory()->create();
        $data = $this->validOrganisationData();

        $this->actingAs($creator)->postJson('/organisations', $data);

        $org = Organisation::where('email', $data['email'])->first();

        // Verify creator attached as admin
        $this->assertTrue(
            $org->users()
                ->where('users.id', $creator->id)
                ->wherePivot('role', 'admin')
                ->exists()
        );
    }

    /**
     * Test that self-representative does not create separate user
     */
    public function test_self_representative_does_not_create_duplicate()
    {
        Mail::fake();

        $creator = User::factory()->create();
        $initialUserCount = User::count();

        $data = $this->validOrganisationData();
        $data['representative']['is_self'] = true;
        $data['representative']['email'] = ''; // Should be ignored
        $data['representative']['name'] = $creator->name;

        $this->actingAs($creator)->postJson('/organisations', $data);

        // Verify no new user was created
        $this->assertEquals($initialUserCount, User::count());

        // Verify creator is attached as admin (not voter)
        $org = Organisation::where('email', $data['email'])->first();
        $this->assertTrue(
            $org->users()
                ->where('users.id', $creator->id)
                ->wherePivot('role', 'admin')
                ->exists()
        );

        // Verify creator not attached as voter
        $this->assertFalse(
            $org->users()
                ->where('users.id', $creator->id)
                ->wherePivot('role', 'voter')
                ->exists()
        );
    }

    /**
     * Test that organisation is attached to representative
     */
    public function test_organization_attached_to_representative()
    {
        Mail::fake();

        $creator = User::factory()->create();
        $data = $this->validOrganisationData();
        $data['representative']['email'] = 'rep3@org.de';

        $this->actingAs($creator)->postJson('/organisations', $data);

        $org = Organisation::where('email', $data['email'])->first();
        $rep = User::where('email', 'rep3@org.de')->first();

        // Verify organisation count for representative
        $this->assertEquals(1, $rep->organisations()->count());

        // Verify correct organisation
        $this->assertTrue($rep->organisations()->where('organisation_id', $org->id)->exists());
    }

    /**
     * Test that multiple representatives can be added to different organisations
     */
    public function test_representative_can_belong_to_multiple_organizations()
    {
        Mail::fake();

        $creator = User::factory()->create();
        $sharedRepEmail = 'shared@rep.de';

        // Create first organisation with shared representative
        $data1 = $this->validOrganisationData();
        $data1['email'] = 'org1@test.de';
        $data1['representative']['email'] = $sharedRepEmail;

        $this->actingAs($creator)->postJson('/organisations', $data1);

        // Create second organisation with same representative
        $data2 = $this->validOrganisationData();
        $data2['name'] = 'Second Organisation';
        $data2['email'] = 'org2@test.de';
        $data2['representative']['email'] = $sharedRepEmail;

        $this->actingAs($creator)->postJson('/organisations', $data2);

        // Verify representative belongs to both organisations
        $rep = User::where('email', $sharedRepEmail)->first();
        $this->assertEquals(2, $rep->organisations()->count());
    }

    /**
     * Test that assigned_at timestamp is set for representative
     */
    public function test_assigned_at_timestamp_set_for_representative()
    {
        Mail::fake();

        $creator = User::factory()->create();
        $data = $this->validOrganisationData();
        $data['representative']['email'] = 'rep4@org.de';

        $before = now();
        $this->actingAs($creator)->postJson('/organisations', $data);
        $after = now();

        $org = Organisation::where('email', $data['email'])->first();
        $rep = User::where('email', 'rep4@org.de')->first();

        // Get pivot record
        $pivot = \Illuminate\Support\Facades\DB::table('organization_user')
            ->where('organisation_id', $org->id)
            ->where('users.id', $rep->id)
            ->first();

        // Verify assigned_at is set
        $this->assertNotNull($pivot->assigned_at);

        // Verify it's within reasonable time window
        $assignedAt = \Carbon\Carbon::parse($pivot->assigned_at);
        $this->assertTrue($assignedAt->between($before, $after));
    }

    /**
     * Test that representative user organisation_id is set correctly
     */
    public function test_representative_organisation_id_matches_organization()
    {
        Mail::fake();

        $creator = User::factory()->create();
        $data = $this->validOrganisationData();
        $data['representative']['email'] = 'rep5@org.de';

        $this->actingAs($creator)->postJson('/organisations', $data);

        $org = Organisation::where('email', $data['email'])->first();
        $rep = User::where('email', 'rep5@org.de')->first();

        // Verify representative has organisation_id set to created organisation
        $this->assertNotNull($rep->organisation_id);
        $this->assertEquals($org->id, $rep->organisation_id);
    }
}

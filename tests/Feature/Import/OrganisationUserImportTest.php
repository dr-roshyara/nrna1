<?php

namespace Tests\Feature\Import;

use Tests\TestCase;
use App\Models\User;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\UserOrganisationRole;
use App\Models\Member;
use App\Models\Voter;
use App\Models\Election;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class OrganisationUserImportTest extends TestCase
{
    use RefreshDatabase;

    protected Organisation $org;
    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->org = Organisation::factory()->create(['type' => 'tenant']);
        $this->admin = User::factory()->create(['email_verified_at' => now()]);

        // Create the organisation user (for three-tier hierarchy)
        OrganisationUser::create([
            'organisation_id' => $this->org->id,
            'user_id' => $this->admin->id,
            'role' => 'owner',
            'status' => 'active',
        ]);

        // Create the user-organisation role (for middleware access check)
        UserOrganisationRole::create([
            'user_id' => $this->admin->id,
            'organisation_id' => $this->org->id,
            'role' => 'owner',
        ]);

        session(['current_organisation_id' => $this->org->id]);
        $this->actingAs($this->admin);
    }

    /** @test */
    public function import_page_can_be_accessed()
    {
        // Sanity check: organisation exists
        $found = Organisation::find($this->org->id);
        $this->assertNotNull($found, 'Organisation not found in database');
        $this->assertEquals($this->org->id, $found->id);

        $url = route('organisations.users.import.index', $this->org);
        \Log::info('Test URL: ' . $url);
        \Log::info('Organisation ID: ' . $this->org->id);

        $response = $this->get($url);
        $response->assertOk();
    }

    /** @test */
    public function template_can_be_downloaded()
    {
        $response = $this->get(route('organisations.users.import.template', $this->org));
        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    /** @test */
    public function preview_shows_valid_rows()
    {
        Storage::fake('local');

        $content = "email,name,is_org_user,is_member,is_voter,election_id\n";
        $content .= "john@example.com,John Doe,YES,YES,YES,\n";
        $content .= "jane@example.com,Jane Smith,YES,YES,NO,\n";

        $file = UploadedFile::fake()->createWithContent('users.csv', $content);

        $response = $this->post(
            route('organisations.users.import.preview', $this->org),
            ['file' => $file]
        );

        $response->assertOk();
        $response->assertJsonStructure([
            'preview' => [
                '*' => ['row', 'email', 'name', 'is_org_user', 'is_member', 'is_voter', 'election_id', 'status', 'errors', 'action']
            ],
            'stats' => ['total', 'valid', 'invalid']
        ]);
    }

    /** @test */
    public function import_creates_users_and_hierarchy()
    {
        Storage::fake('local');

        $election = Election::factory()->create(['organisation_id' => $this->org->id]);

        $content = "email,name,is_org_user,is_member,is_voter,election_id\n";
        $content .= "john@example.com,John Doe,YES,YES,YES,{$election->id}\n";
        $content .= "jane@example.com,Jane Smith,YES,YES,NO,\n";

        $file = UploadedFile::fake()->createWithContent('users.csv', $content);

        $response = $this->post(
            route('organisations.users.import.process', $this->org),
            ['file' => $file, 'confirmed' => true]
        );

        $response->assertRedirect();

        // Verify John was created as voter
        $john = User::where('email', 'john@example.com')->first();
        $this->assertNotNull($john);

        $orgUser = OrganisationUser::where('user_id', $john->id)
            ->where('organisation_id', $this->org->id)
            ->first();
        $this->assertNotNull($orgUser);

        $member = Member::where('organisation_user_id', $orgUser->id)->first();
        $this->assertNotNull($member);

        $voter = Voter::where('member_id', $member->id)
            ->where('election_id', $election->id)
            ->first();
        $this->assertNotNull($voter);
        $this->assertEquals('eligible', $voter->status);

        // Verify Jane was created as member only
        $jane = User::where('email', 'jane@example.com')->first();
        $this->assertNotNull($jane);

        $janeOrgUser = OrganisationUser::where('user_id', $jane->id)->first();
        $this->assertNotNull($janeOrgUser);

        $janeMember = Member::where('organisation_user_id', $janeOrgUser->id)->first();
        $this->assertNotNull($janeMember);

        $janeVoter = Voter::where('member_id', $janeMember->id)->first();
        $this->assertNull($janeVoter);
    }

    /** @test */
    public function import_validates_required_fields()
    {
        Storage::fake('local');

        $content = "email,name,is_org_user,is_member,is_voter,election_id\n";
        $content .= ",,YES,YES,YES,\n"; // Missing email and name

        $file = UploadedFile::fake()->createWithContent('users.csv', $content);

        $response = $this->post(
            route('organisations.users.import.preview', $this->org),
            ['file' => $file]
        );

        $response->assertOk();
        $response->assertJsonFragment([
            'status' => '❌ Invalid',
        ]);
    }

    /** @test */
    public function non_owner_cannot_access_import()
    {
        $regularUser = User::factory()->create(['email_verified_at' => now()]);
        OrganisationUser::create([
            'organisation_id' => $this->org->id,
            'user_id' => $regularUser->id,
            'role' => 'member',
            'status' => 'active',
        ]);

        UserOrganisationRole::create([
            'user_id' => $regularUser->id,
            'organisation_id' => $this->org->id,
            'role' => 'member',
        ]);

        $this->actingAs($regularUser);

        $response = $this->get(route('organisations.users.import.index', $this->org));
        $response->assertStatus(403);
    }

    /** @test */
    public function export_downloads_current_users()
    {
        $response = $this->get(route('organisations.users.export', $this->org));
        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    /** @test */
    public function import_handles_existing_users()
    {
        Storage::fake('local');

        // Create existing user
        $existingUser = User::factory()->create(['email' => 'existing@example.com', 'email_verified_at' => now()]);

        $content = "email,name,is_org_user,is_member,is_voter,election_id\n";
        $content .= "existing@example.com,Updated Name,YES,YES,NO,\n";

        $file = UploadedFile::fake()->createWithContent('users.csv', $content);

        $response = $this->post(
            route('organisations.users.import.process', $this->org),
            ['file' => $file, 'confirmed' => true]
        );

        $response->assertRedirect();

        // Verify existing user was reused (no duplicate created)
        $this->assertEquals(1, User::where('email', 'existing@example.com')->count());

        // Verify organisation user was created for existing user
        $orgUser = OrganisationUser::where('user_id', $existingUser->id)
            ->where('organisation_id', $this->org->id)
            ->first();
        $this->assertNotNull($orgUser);
    }
}

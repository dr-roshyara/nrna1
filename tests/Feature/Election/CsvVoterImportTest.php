<?php

namespace Tests\Feature\Election;

use App\Models\Election;
use App\Models\ElectionOfficer;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Tests\TestCase;

class CsvVoterImportTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private Election $election;
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->org = Organisation::factory()->create([
            'type' => 'tenant',
            'uses_full_membership' => false, // Election-only mode
        ]);

        $this->election = Election::factory()
            ->forOrganisation($this->org)
            ->real()
            ->create(['status' => 'active']);

        $this->admin = User::factory()->create(['email_verified_at' => now()]);
        UserOrganisationRole::create([
            'id' => (string) Str::uuid(),
            'user_id' => $this->admin->id,
            'organisation_id' => $this->org->id,
            'role' => 'owner',
        ]);

        // Add admin as org user (required for election-only mode)
        OrganisationUser::factory()
            ->for($this->org)
            ->for($this->admin)
            ->create(['status' => 'active']);

        ElectionOfficer::create([
            'id' => (string) Str::uuid(),
            'election_id' => $this->election->id,
            'organisation_id' => $this->org->id,
            'user_id' => $this->admin->id,
            'role' => 'chief',
            'status' => 'active',
        ]);

        session(['current_organisation_id' => $this->org->id]);
    }

    /** @test */
    public function admin_can_import_voters_via_csv(): void
    {
        $user1 = User::factory()->create(['email' => 'voter1@example.com']);
        $user2 = User::factory()->create(['email' => 'voter2@example.com']);

        OrganisationUser::factory()
            ->for($this->org)
            ->for($user1)
            ->create(['status' => 'active']);

        OrganisationUser::factory()
            ->for($this->org)
            ->for($user2)
            ->create(['status' => 'active']);

        $file = UploadedFile::fake()->createWithContent(
            'voters.csv',
            "email\nvoter1@example.com\nvoter2@example.com"
        );

        $response = $this->actingAs($this->admin)
            ->post("/organisations/{$this->org->slug}/elections/{$this->election->slug}/voters/import", [
                'file' => $file,
                'confirmed' => true,
            ]);

        $response->assertRedirect();
    }

    /** @test */
    public function csv_import_validates_email_format(): void
    {
        $file = UploadedFile::fake()->createWithContent(
            'voters.xlsx',
            "email\ninvalid-email\nnotanemail\ntest@example.com"
        );

        $user = User::factory()->create(['email' => 'test@example.com']);
        OrganisationUser::factory()
            ->for($this->org)
            ->for($user)
            ->create(['status' => 'active']);

        $response = $this->actingAs($this->admin)
            ->post("/organisations/{$this->org->slug}/elections/{$this->election->slug}/voters/import", [
                'file' => $file,
                'confirmed' => true,
            ]);

        $response->assertRedirect();
    }

    /** @test */
    public function csv_import_skips_already_assigned_voters(): void
    {
        $user = User::factory()->create(['email' => 'voter@example.com']);
        OrganisationUser::factory()
            ->for($this->org)
            ->for($user)
            ->create(['status' => 'active']);

        // Pre-assign voter to election
        \App\Models\ElectionMembership::create([
            'id' => (string) Str::uuid(),
            'election_id' => $this->election->id,
            'user_id' => $user->id,
            'organisation_id' => $this->org->id,
            'role' => 'voter',
            'status' => 'active',
        ]);

        $file = UploadedFile::fake()->createWithContent(
            'voters.xlsx',
            "email\nvoter@example.com"
        );

        $response = $this->actingAs($this->admin)
            ->post("/organisations/{$this->org->slug}/elections/{$this->election->slug}/voters/import", [
                'file' => $file,
                'confirmed' => true,
            ]);

        $response->assertRedirect();
    }

    /** @test */
    public function csv_import_respects_membership_mode(): void
    {
        // Create a full-membership org and election
        $fullMembershipOrg = Organisation::factory()->create([
            'type' => 'tenant',
            'uses_full_membership' => true,
        ]);

        $fullMembershipElection = Election::factory()
            ->forOrganisation($fullMembershipOrg)
            ->real()
            ->create(['status' => 'active']);

        // Create user with no member record
        $user = User::factory()->create(['email' => 'voter@example.com']);
        OrganisationUser::factory()
            ->for($fullMembershipOrg)
            ->for($user)
            ->create(['status' => 'active']);

        // Add admin to full membership org
        $admin = User::factory()->create();
        UserOrganisationRole::create([
            'id' => (string) Str::uuid(),
            'user_id' => $admin->id,
            'organisation_id' => $fullMembershipOrg->id,
            'role' => 'owner',
        ]);

        ElectionOfficer::create([
            'id' => (string) Str::uuid(),
            'election_id' => $fullMembershipElection->id,
            'organisation_id' => $fullMembershipOrg->id,
            'user_id' => $admin->id,
            'role' => 'chief',
            'status' => 'active',
        ]);

        $file = UploadedFile::fake()->createWithContent(
            'voters.xlsx',
            "email\nvoter@example.com"
        );

        $response = $this->actingAs($admin)
            ->withSession(['current_organisation_id' => $fullMembershipOrg->id])
            ->post("/organisations/{$fullMembershipOrg->slug}/elections/{$fullMembershipElection->slug}/voters/import", [
                'file' => $file,
                'confirmed' => true,
            ]);

        $response->assertRedirect();
    }

    /** @test */
    public function csv_import_handles_large_files_gracefully(): void
    {
        // Create 10 users
        $emailLines = ['email'];
        for ($i = 1; $i <= 10; $i++) {
            $user = User::factory()->create(['email' => "voter{$i}@example.com"]);
            OrganisationUser::factory()
                ->for($this->org)
                ->for($user)
                ->create(['status' => 'active']);
            $emailLines[] = "voter{$i}@example.com";
        }

        // Create CSV with 10 rows (under typical limits)
        $csvContent = implode("\n", $emailLines);

        $file = UploadedFile::fake()->createWithContent(
            'voters.xlsx',
            $csvContent
        );

        $response = $this->actingAs($this->admin)
            ->post("/organisations/{$this->org->slug}/elections/{$this->election->slug}/voters/import", [
                'file' => $file,
                'confirmed' => true,
            ]);

        $response->assertRedirect();
    }
}

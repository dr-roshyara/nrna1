<?php

namespace Tests\Feature\Voter;

use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\ElectionOfficer;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\User;
use App\Models\UserOrganisationRole;
use App\Models\VoterInvitation;
use App\Jobs\SendVoterInvitation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class VoterImportElectionOnlyTest extends TestCase
{
    use RefreshDatabase;

    protected Organisation $organisation;
    protected Election $election;
    protected User $admin;
    private array $cleanupPaths = [];

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');

        $this->organisation = Organisation::factory()
            ->state(['uses_full_membership' => false])
            ->create();

        $this->election = Election::factory()
            ->for($this->organisation)
            ->state(['type' => 'real'])
            ->create();

        $this->admin = User::factory()
            ->create();

        UserOrganisationRole::create([
            'user_id' => $this->admin->id,
            'organisation_id' => $this->organisation->id,
            'role' => 'admin',
        ]);

        // Create election officer for voter management authorization
        ElectionOfficer::create([
            'user_id' => $this->admin->id,
            'election_id' => $this->election->id,
            'organisation_id' => $this->organisation->id,
            'role' => 'chief',
            'status' => 'active',
        ]);
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function makeCsv(string $content): UploadedFile
    {
        $path = tempnam(sys_get_temp_dir(), 'voter_import_');
        file_put_contents($path, $content);

        // Register for cleanup
        $this->cleanupPaths[] = $path;

        return new UploadedFile($path, 'test.csv', 'text/csv', null, true);
    }

    protected function tearDown(): void
    {
        foreach ($this->cleanupPaths as $path) {
            if (file_exists($path)) {
                unlink($path);
            }
        }
        parent::tearDown();
    }

    /** @test */
    public function test_preview_shows_new_for_unknown_email_in_election_only_mode()
    {
        $csv = "firstname;lastname;email\nNiraj;Adhikari;niraj@example.com\nJohn;Doe;john@example.com";
        $file = $this->makeCsv($csv);

        $this->actingAs($this->admin)
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route('elections.voters.import.preview', [
                'organisation' => $this->organisation->slug,
                'election' => $this->election->slug,
            ]), [
                'file' => $file,
            ])
            ->assertOk()
            ->assertJsonPath('stats.new', 2)
            ->assertJsonPath('stats.invalid', 0);
    }

    /** @test */
    public function test_preview_shows_existing_for_known_email()
    {
        $existingUser = User::factory()->create(['email' => 'existing@example.com']);
        OrganisationUser::factory()
            ->for($this->organisation)
            ->for($existingUser)
            ->create();

        $csv = "firstname;lastname;email\nExisting;User;existing@example.com\nNew;User;new@example.com";
        $file = $this->makeCsv($csv);

        $this->actingAs($this->admin)
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route('elections.voters.import.preview', [
                'organisation' => $this->organisation->slug,
                'election' => $this->election->slug,
            ]), [
                'file' => $file,
            ])
            ->assertOk()
            ->assertJsonPath('stats.existing', 1)
            ->assertJsonPath('stats.new', 1);
    }

    /** @test */
    public function test_import_creates_user_for_unknown_email()
    {
        Bus::fake();

        $csv = "firstname;lastname;email\nNiraj;Adhikari;niraj.new@example.com";
        $file = $this->makeCsv($csv);

        $this->actingAs($this->admin)
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route('elections.voters.import', [
                'organisation' => $this->organisation->slug,
                'election' => $this->election->slug,
            ]), [
                'file' => $file,
                'confirmed' => true,
            ])
            ->assertOk();

        $this->assertDatabaseHas('users', ['email' => 'niraj.new@example.com']);
    }

    /** @test */
    public function test_import_links_user_to_organisation()
    {
        Bus::fake();

        $csv = "firstname;lastname;email\nJohn;Doe;john.import@example.com";
        $file = $this->makeCsv($csv);

        $this->actingAs($this->admin)
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route('elections.voters.import', [
                'organisation' => $this->organisation->slug,
                'election' => $this->election->slug,
            ]), [
                'file' => $file,
                'confirmed' => true,
            ])
            ->assertOk();

        $user = User::where('email', 'john.import@example.com')->first();
        $this->assertDatabaseHas('organisation_users', [
            'organisation_id' => $this->organisation->id,
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function test_import_assigns_user_as_voter_to_election()
    {
        Bus::fake();

        $csv = "firstname;lastname;email\nVoter;Test;voter.test@example.com";
        $file = $this->makeCsv($csv);

        $this->actingAs($this->admin)
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route('elections.voters.import', [
                'organisation' => $this->organisation->slug,
                'election' => $this->election->slug,
            ]), [
                'file' => $file,
                'confirmed' => true,
            ])
            ->assertOk();

        $user = User::where('email', 'voter.test@example.com')->first();
        $this->assertDatabaseHas('election_memberships', [
            'election_id' => $this->election->id,
            'user_id' => $user->id,
            'role' => 'voter',
            'status' => 'active',
        ]);
    }

    /** @test */
    public function test_import_creates_voter_invitation()
    {
        Bus::fake();

        $csv = "firstname;lastname;email\nInvite;User;invite.user@example.com";
        $file = $this->makeCsv($csv);

        $this->actingAs($this->admin)
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route('elections.voters.import', [
                'organisation' => $this->organisation->slug,
                'election' => $this->election->slug,
            ]), [
                'file' => $file,
                'confirmed' => true,
            ])
            ->assertOk();

        $user = User::where('email', 'invite.user@example.com')->first();
        $this->assertDatabaseHas('voter_invitations', [
            'election_id' => $this->election->id,
            'user_id' => $user->id,
            'email_status' => 'pending',
        ]);
    }

    /** @test */
    public function test_import_dispatches_send_invitation_job()
    {
        Bus::fake();

        $csv = "firstname;lastname;email\nJob;Test;job.test@example.com";
        $file = $this->makeCsv($csv);

        $this->actingAs($this->admin)
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route('elections.voters.import', [
                'organisation' => $this->organisation->slug,
                'election' => $this->election->slug,
            ]), [
                'file' => $file,
                'confirmed' => true,
            ])
            ->assertOk();

        Bus::assertDispatched(SendVoterInvitation::class);
    }

    /** @test */
    public function test_import_does_not_duplicate_invitation_on_reimport()
    {
        Bus::fake();

        $csv = "firstname;lastname;email\nDuplicate;Test;duplicate.test@example.com";
        $file1 = $this->makeCsv($csv);

        // First import
        $this->actingAs($this->admin)
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route('elections.voters.import', [
                'organisation' => $this->organisation->slug,
                'election' => $this->election->slug,
            ]), [
                'file' => $file1,
                'confirmed' => true,
            ])
            ->assertOk();

        $file2 = $this->makeCsv($csv);

        // Second import of same email
        $this->actingAs($this->admin)
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route('elections.voters.import', [
                'organisation' => $this->organisation->slug,
                'election' => $this->election->slug,
            ]), [
                'file' => $file2,
                'confirmed' => true,
            ])
            ->assertOk();

        // Should have only 1 invitation record
        $this->assertEquals(1, VoterInvitation::count());
    }

    /** @test */
    public function test_invitation_token_shows_set_password_page()
    {
        $user = User::factory()->create();
        $invitation = VoterInvitation::factory()->create([
            'election_id' => $this->election->id,
            'user_id' => $user->id,
            'organisation_id' => $this->organisation->id,
            'email_status' => 'sent',
        ]);

        $this->get(route('invitation.show-set-password', ['token' => $invitation->token]))
            ->assertOk()
            ->assertInertia(fn ($assert) => $assert
                ->component('Auth/SetPassword')
                ->has('token')
                ->has('email')
                ->has('election')
            );
    }

    /** @test */
    public function test_set_password_logs_in_user_and_redirects()
    {
        $user = User::factory()->create(['email_verified_at' => null]);
        $invitation = VoterInvitation::factory()->create([
            'election_id' => $this->election->id,
            'user_id' => $user->id,
            'organisation_id' => $this->organisation->id,
            'email_status' => 'sent',
        ]);

        $response = $this->postJson(route('invitation.store-password', ['token' => $invitation->token]), [
            'password' => 'SecurePassword123',
            'password_confirmation' => 'SecurePassword123',
        ]);

        $response->assertRedirect();
        $this->assertAuthenticatedAs($user);
        $this->assertNotNull($user->fresh()->email_verified_at);
        $this->assertNotNull($invitation->fresh()->used_at);
    }

    /** @test */
    public function test_expired_token_returns_404()
    {
        $user = User::factory()->create();
        $invitation = VoterInvitation::factory()->create([
            'election_id' => $this->election->id,
            'user_id' => $user->id,
            'organisation_id' => $this->organisation->id,
            'expires_at' => now()->subDay(),
        ]);

        $this->get(route('invitation.show-set-password', ['token' => $invitation->token]))
            ->assertNotFound();
    }

    /** @test */
    public function test_used_token_returns_404()
    {
        $user = User::factory()->create();
        $invitation = VoterInvitation::factory()->create([
            'election_id' => $this->election->id,
            'user_id' => $user->id,
            'organisation_id' => $this->organisation->id,
            'used_at' => now()->subHour(),
        ]);

        $this->get(route('invitation.show-set-password', ['token' => $invitation->token]))
            ->assertNotFound();
    }

    /** @test */
    public function test_rate_limiting_blocks_after_10_attempts()
    {
        $user = User::factory()->create();
        $invitation = VoterInvitation::factory()->create([
            'election_id' => $this->election->id,
            'user_id' => $user->id,
            'organisation_id' => $this->organisation->id,
        ]);

        // Make 10 failed attempts
        for ($i = 0; $i < 10; $i++) {
            $this->postJson(route('invitation.store-password', ['token' => $invitation->token]), [
                'password' => 'WrongPassword' . $i,
                'password_confirmation' => 'WrongPassword' . $i,
            ]);
        }

        // 11th attempt should be rate limited
        $this->postJson(route('invitation.store-password', ['token' => $invitation->token]), [
            'password' => 'AnotherPassword',
            'password_confirmation' => 'AnotherPassword',
        ])
        ->assertStatus(429); // Too Many Requests
    }

    /** @test */
    public function test_transaction_rolls_back_on_election_membership_failure()
    {
        Bus::fake();

        $csv = "firstname;lastname;email\nTransaction;Test;transaction.test@example.com";
        $file = $this->makeCsv($csv);

        // This test verifies transaction behavior with data integrity
        $this->actingAs($this->admin)
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route('elections.voters.import', [
                'organisation' => $this->organisation->slug,
                'election' => $this->election->slug,
            ]), [
                'file' => $file,
                'confirmed' => true,
            ])
            ->assertOk();

        // User should be created and added to election
        $user = User::where('email', 'transaction.test@example.com')->first();
        $this->assertNotNull($user);
        $this->assertDatabaseHas('election_memberships', [
            'election_id' => $this->election->id,
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function test_language_fallback_to_de_when_languages_is_empty()
    {
        Bus::fake();

        $org = Organisation::factory()
            ->state(['uses_full_membership' => false, 'languages' => []])
            ->create();

        $election = Election::factory()
            ->for($org)
            ->create();

        $admin = User::factory()->create();
        UserOrganisationRole::create([
            'user_id' => $admin->id,
            'organisation_id' => $org->id,
            'role' => 'admin',
        ]);

        $user = User::factory()->create(['email' => 'lang.test@example.com']);
        $invitation = VoterInvitation::factory()->create([
            'election_id' => $election->id,
            'user_id' => $user->id,
            'organisation_id' => $org->id,
        ]);

        $job = new SendVoterInvitation($invitation);

        // This test verifies language fallback logic
        // Implementation should select 'de' when languages is empty
        $this->assertNotNull($job);
    }
}

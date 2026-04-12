<?php

/**
 * TDD — Phase 5: Participant bulk import via Excel
 *
 * Covers: template download, preview validation, import processing,
 * access control, and tenant isolation.
 *
 * All tests MUST FAIL before the controller/service are created (Red).
 */

namespace Tests\Feature\Membership;

use App\Models\Organisation;
use App\Models\OrganisationParticipant;
use App\Models\OrganisationUser;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Tests\TestCase;

class ParticipantImportTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private User $owner;
    private User $admin;
    private User $commission;

    protected function setUp(): void
    {
        parent::setUp();

        $this->org = Organisation::factory()->create(['type' => 'tenant']);
        session(['current_organisation_id' => $this->org->id]);

        $this->owner      = $this->makeOrgUser('owner');
        $this->admin      = $this->makeOrgUser('admin');
        $this->commission = $this->makeOrgUser('election_commission');
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function makeOrgUser(string $role): User
    {
        $user    = User::factory()->create();
        $orgUser = OrganisationUser::factory()->create([
            'user_id'         => $user->id,
            'organisation_id' => $this->org->id,
        ]);
        UserOrganisationRole::create([
            'id'              => (string) Str::uuid(),
            'user_id'         => $user->id,
            'organisation_id' => $this->org->id,
            'role'            => $role,
        ]);

        return $user;
    }

    /** Build an in-memory CSV UploadedFile for testing. */
    private function makeCsv(string $content): UploadedFile
    {
        $path = tempnam(sys_get_temp_dir(), 'participant_import_') . '.csv';
        file_put_contents($path, $content);

        return new UploadedFile($path, 'participants.csv', 'text/csv', null, true);
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  Group 1 — Template download
    // ══════════════════════════════════════════════════════════════════════════

    public function test_owner_can_download_template(): void
    {
        $response = $this->actingAs($this->owner)
            ->get(route('organisations.membership.participants.import.template', $this->org->slug));

        $response->assertOk();
        $this->assertStringContainsStringIgnoringCase(
            'spreadsheetml',
            $response->headers->get('Content-Type') ?? ''
        );
    }

    public function test_admin_can_download_template(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('organisations.membership.participants.import.template', $this->org->slug));

        $response->assertOk();
    }

    public function test_commission_member_cannot_download_template(): void
    {
        $this->actingAs($this->commission)
            ->get(route('organisations.membership.participants.import.template', $this->org->slug))
            ->assertForbidden();
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  Group 2 — Preview (validate without saving)
    // ══════════════════════════════════════════════════════════════════════════

    public function test_preview_returns_valid_rows_and_stats(): void
    {
        User::factory()->create(['email' => 'staff@example.com']);

        $csv  = "email,participant_type,role,expires_at,permissions\n";
        $csv .= "staff@example.com,staff,coordinator,,\n";

        $response = $this->actingAs($this->owner)
            ->withHeaders(['Accept' => 'application/json'])
            ->post(
                route('organisations.membership.participants.import.preview', $this->org->slug),
                ['file' => $this->makeCsv($csv)]
            );

        $response->assertOk()
            ->assertJsonPath('stats.total', 1)
            ->assertJsonPath('stats.valid', 1)
            ->assertJsonPath('stats.invalid', 0);
    }

    public function test_preview_flags_invalid_participant_type(): void
    {
        User::factory()->create(['email' => 'bad@example.com']);

        $csv  = "email,participant_type,role,expires_at,permissions\n";
        $csv .= "bad@example.com,janitor,,\n";   // invalid type

        $response = $this->actingAs($this->owner)
            ->withHeaders(['Accept' => 'application/json'])
            ->post(
                route('organisations.membership.participants.import.preview', $this->org->slug),
                ['file' => $this->makeCsv($csv)]
            );

        $response->assertOk()
            ->assertJsonPath('stats.invalid', 1);
    }

    public function test_preview_flags_nonexistent_email(): void
    {
        $csv  = "email,participant_type,role,expires_at,permissions\n";
        $csv .= "ghost@example.com,staff,,\n";   // user doesn't exist

        $response = $this->actingAs($this->owner)
            ->withHeaders(['Accept' => 'application/json'])
            ->post(
                route('organisations.membership.participants.import.preview', $this->org->slug),
                ['file' => $this->makeCsv($csv)]
            );

        $response->assertOk()
            ->assertJsonPath('stats.invalid', 1);
    }

    public function test_preview_flags_past_expires_at(): void
    {
        User::factory()->create(['email' => 'exp@example.com']);

        $csv  = "email,participant_type,role,expires_at,permissions\n";
        $csv .= "exp@example.com,guest,,2020-01-01,\n";   // past date

        $response = $this->actingAs($this->owner)
            ->withHeaders(['Accept' => 'application/json'])
            ->post(
                route('organisations.membership.participants.import.preview', $this->org->slug),
                ['file' => $this->makeCsv($csv)]
            );

        $response->assertOk()
            ->assertJsonPath('stats.invalid', 1);
    }

    public function test_preview_accepts_future_expires_at(): void
    {
        User::factory()->create(['email' => 'future@example.com']);

        $csv  = "email,participant_type,role,expires_at,permissions\n";
        $csv .= "future@example.com,guest,,2099-01-01,\n";

        $response = $this->actingAs($this->owner)
            ->withHeaders(['Accept' => 'application/json'])
            ->post(
                route('organisations.membership.participants.import.preview', $this->org->slug),
                ['file' => $this->makeCsv($csv)]
            );

        $response->assertOk()
            ->assertJsonPath('stats.valid', 1);
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  Group 3 — Import (save to database)
    // ══════════════════════════════════════════════════════════════════════════

    public function test_import_creates_participant_records(): void
    {
        $user = User::factory()->create(['email' => 'newstaff@example.com']);

        $csv  = "email,participant_type,role,expires_at,permissions\n";
        $csv .= "newstaff@example.com,staff,coordinator,,\n";

        $this->actingAs($this->owner)
            ->post(
                route('organisations.membership.participants.import', $this->org->slug),
                ['file' => $this->makeCsv($csv), 'confirmed' => '1']
            )
            ->assertRedirect();

        $this->assertDatabaseHas('organisation_participants', [
            'organisation_id'  => $this->org->id,
            'user_id'          => $user->id,
            'participant_type' => 'staff',
            'role'             => 'coordinator',
        ]);
    }

    public function test_import_updates_existing_participant_without_duplicate(): void
    {
        $user = User::factory()->create(['email' => 'existing@example.com']);

        // Pre-existing record
        OrganisationParticipant::create([
            'id'               => (string) Str::uuid(),
            'organisation_id'  => $this->org->id,
            'user_id'          => $user->id,
            'participant_type' => 'guest',
            'assigned_at'      => now(),
        ]);

        $csv  = "email,participant_type,role,expires_at,permissions\n";
        $csv .= "existing@example.com,staff,new-role,,\n";   // type changes

        $this->actingAs($this->owner)
            ->post(
                route('organisations.membership.participants.import', $this->org->slug),
                ['file' => $this->makeCsv($csv), 'confirmed' => '1']
            );

        // Should be exactly one record (updated, not duplicated)
        $this->assertEquals(
            1,
            OrganisationParticipant::withoutGlobalScopes()
                ->where('organisation_id', $this->org->id)
                ->where('user_id', $user->id)
                ->count()
        );

        $this->assertDatabaseHas('organisation_participants', [
            'user_id'          => $user->id,
            'participant_type' => 'staff',
            'role'             => 'new-role',
        ]);
    }

    public function test_import_skips_rows_with_invalid_email_format(): void
    {
        $csv  = "email,participant_type,role,expires_at,permissions\n";
        $csv .= "not-an-email,staff,,\n";

        $this->actingAs($this->owner)
            ->post(
                route('organisations.membership.participants.import', $this->org->slug),
                ['file' => $this->makeCsv($csv), 'confirmed' => '1']
            );

        $this->assertDatabaseCount('organisation_participants', 0);
    }

    public function test_import_skips_rows_where_user_does_not_exist(): void
    {
        $csv  = "email,participant_type,role,expires_at,permissions\n";
        $csv .= "nobody@nowhere.com,staff,,\n";

        $this->actingAs($this->owner)
            ->post(
                route('organisations.membership.participants.import', $this->org->slug),
                ['file' => $this->makeCsv($csv), 'confirmed' => '1']
            );

        $this->assertDatabaseCount('organisation_participants', 0);
    }

    public function test_import_returns_correct_counts(): void
    {
        $u1 = User::factory()->create(['email' => 'a@example.com']);
        $u2 = User::factory()->create(['email' => 'b@example.com']);

        $csv  = "email,participant_type,role,expires_at,permissions\n";
        $csv .= "a@example.com,staff,,,\n";        // valid → created
        $csv .= "b@example.com,guest,,,\n";         // valid → created
        $csv .= "bad-email,election_committee,,,\n"; // invalid → skipped

        $response = $this->actingAs($this->owner)
            ->post(
                route('organisations.membership.participants.import', $this->org->slug),
                ['file' => $this->makeCsv($csv), 'confirmed' => '1']
            )
            ->assertRedirect();

        $this->assertDatabaseCount('organisation_participants', 2);
    }

    public function test_import_requires_confirmed_flag(): void
    {
        User::factory()->create(['email' => 'confirm@example.com']);

        $csv  = "email,participant_type,role,expires_at,permissions\n";
        $csv .= "confirm@example.com,staff,,,\n";

        // No 'confirmed' field → validation fails (redirects back with errors for web requests)
        $this->actingAs($this->owner)
            ->post(
                route('organisations.membership.participants.import', $this->org->slug),
                ['file' => $this->makeCsv($csv)]
            )
            ->assertRedirect()
            ->assertSessionHasErrors(['confirmed']);

        $this->assertDatabaseCount('organisation_participants', 0);
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  Group 4 — Tenant isolation
    // ══════════════════════════════════════════════════════════════════════════

    public function test_owner_cannot_import_into_another_organisation(): void
    {
        $otherOrg = Organisation::factory()->create(['type' => 'tenant']);

        User::factory()->create(['email' => 'cross@example.com']);

        $csv  = "email,participant_type,role,expires_at,permissions\n";
        $csv .= "cross@example.com,staff,,,\n";

        // Acting as owner of $this->org but posting to $otherOrg.
        // Middleware is not a member of otherOrg → redirects (web request) or 403 (JSON).
        // Use JSON header so we can assert the exact 403 status.
        $this->actingAs($this->owner)
            ->withHeaders(['Accept' => 'application/json'])
            ->post(
                route('organisations.membership.participants.import', $otherOrg->slug),
                ['file' => $this->makeCsv($csv), 'confirmed' => '1']
            )
            ->assertForbidden();

        $this->assertDatabaseCount('organisation_participants', 0);
    }
}

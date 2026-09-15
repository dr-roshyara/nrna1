<?php

namespace Tests\Feature\Membership;

use App\Jobs\ProcessMemberImportJob;
use App\Models\MemberImportJob;
use App\Models\MembershipType;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class MemberImportTest extends TestCase
{
    use RefreshDatabase;

    private Organisation  $org;
    private MembershipType $membershipType;
    private User          $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->org = Organisation::factory()->create(['type' => 'tenant']);

        $this->membershipType = MembershipType::factory()->create([
            'organisation_id'      => $this->org->id,
            'grants_voting_rights' => true,
        ]);

        $this->admin = User::factory()->create([
            'organisation_id' => $this->org->id,
        ]);

        session(['current_organisation_id' => $this->org->id]);

        Storage::fake('local');
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    private function makeImportJob(string $csvContent): MemberImportJob
    {
        $path = "imports/{$this->org->id}/" . Str::uuid() . '_test.csv';
        Storage::disk('local')->put($path, $csvContent);

        return MemberImportJob::create([
            'organisation_id'   => $this->org->id,
            'initiated_by'      => $this->admin->id,
            'file_path'         => $path,
            'original_filename' => 'test.csv',
            'status'            => 'pending',
        ]);
    }

    private function runJob(MemberImportJob $importJob): MemberImportJob
    {
        (new ProcessMemberImportJob($importJob->id))->handle();
        return $importJob->fresh();
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  Tests
    // ══════════════════════════════════════════════════════════════════════════

    public function test_import_creates_user_with_first_and_last_name(): void
    {
        $csv = "email;firstname;lastname\njohn.doe@example.com;John;Doe\n";
        $job = $this->runJob($this->makeImportJob($csv));

        $this->assertDatabaseHas('users', [
            'email'      => 'john.doe@example.com',
            'first_name' => 'John',
            'last_name'  => 'Doe',
            'name'       => 'John Doe',
        ]);
        $this->assertEquals(1, $job->imported_count);
    }

    public function test_import_creates_organisation_user_record(): void
    {
        $csv = "email;firstname;lastname\nana.smith@example.com;Ana;Smith\n";
        $this->runJob($this->makeImportJob($csv));

        $user = User::where('email', 'ana.smith@example.com')->firstOrFail();

        $this->assertDatabaseHas('organisation_users', [
            'user_id'         => $user->id,
            'organisation_id' => $this->org->id,
        ]);
    }

    public function test_import_creates_member_record_with_all_fields(): void
    {
        $csv = implode("\n", [
            'email;firstname;lastname;membership_number;joined_at;status;fees_status;expires_at',
            'boris.jones@example.com;Boris;Jones;MEM-001;2024-01-15;active;paid;2025-12-31',
        ]);
        $this->runJob($this->makeImportJob($csv));

        $user = User::where('email', 'boris.jones@example.com')->firstOrFail();

        $this->assertDatabaseHas('organisation_users', [
            'user_id'         => $user->id,
            'organisation_id' => $this->org->id,
        ]);

        $orgUser = \App\Models\OrganisationUser::where('user_id', $user->id)->firstOrFail();

        $this->assertDatabaseHas('members', [
            'organisation_user_id' => $orgUser->id,
            'membership_number'    => 'MEM-001',
            'status'               => 'active',
            'fees_status'          => 'paid',
        ]);

        $member = \App\Models\Member::where('organisation_user_id', $orgUser->id)->firstOrFail();
        $this->assertEquals('2024-01-15', $member->joined_at->toDateString());
        $this->assertEquals('2025-12-31', $member->membership_expires_at->toDateString());
    }

    public function test_import_uses_defaults_when_optional_columns_missing(): void
    {
        $csv = "email;firstname;lastname\nclaudia.m@example.com;Claudia;Mueller\n";
        $this->runJob($this->makeImportJob($csv));

        $user    = User::where('email', 'claudia.m@example.com')->firstOrFail();
        $orgUser = \App\Models\OrganisationUser::where('user_id', $user->id)->firstOrFail();
        $member  = \App\Models\Member::where('organisation_user_id', $orgUser->id)->firstOrFail();

        $this->assertEquals('active',  $member->status);
        $this->assertEquals('unpaid',  $member->fees_status);
        $this->assertNull($member->membership_expires_at);
        $this->assertNotNull($member->joined_at);
    }

    public function test_import_skips_duplicate_emails(): void
    {
        User::factory()->create([
            'email'           => 'duplicate@example.com',
            'organisation_id' => $this->org->id,
        ]);

        $csv = "email;firstname;lastname\nduplicate@example.com;John;Doe\n";
        $job = $this->runJob($this->makeImportJob($csv));

        $this->assertEquals(0, $job->imported_count);
        $this->assertEquals(1, $job->skipped_count);
        $this->assertCount(1, User::where('email', 'duplicate@example.com')->get());
    }

    public function test_import_auto_generates_membership_number_when_blank(): void
    {
        $csv = "email;firstname;lastname;membership_number\nfrank.k@example.com;Frank;K;\n";
        $this->runJob($this->makeImportJob($csv));

        $user    = User::where('email', 'frank.k@example.com')->firstOrFail();
        $orgUser = \App\Models\OrganisationUser::where('user_id', $user->id)->firstOrFail();
        $member  = \App\Models\Member::where('organisation_user_id', $orgUser->id)->firstOrFail();

        $this->assertNotNull($member->membership_number);
        $this->assertMatchesRegularExpression('/^M[A-Z0-9]{8}$/', $member->membership_number);
    }

    // ── Default membership type selection (grants_voting_rights independence) ──
    //
    // getDefaultMembershipTypeId() must select any ACTIVE membership type, not
    // specifically a voting-enabled one. grants_voting_rights is a downstream
    // concern (Member::getVotingRightsAttribute(), VoterEligibilityService,
    // NewsletterService) — never consulted by MembershipTypePolicy::assertActive()
    // or EloquentMembershipTypeRepository, which only ever check is_active. Every
    // membership type created through the admin UI (MembershipTypeController) is
    // grants_voting_rights=false by construction (the form has no field for it),
    // so requiring grants_voting_rights=true here made import impossible for any
    // organisation that only used that UI — including election-only
    // organisations (uses_full_membership=false), where voting rights aren't
    // gated by membership type at all.

    public function test_import_succeeds_with_only_an_active_non_voting_membership_type(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        // MemberImportJob uses BelongsToTenant — must point the session at
        // this test's own org, not the shared $this->org from setUp().
        session(['current_organisation_id' => $org->id]);
        MembershipType::factory()->create([
            'organisation_id'      => $org->id,
            'grants_voting_rights' => false,
            'is_active'            => true,
        ]);
        $admin = User::factory()->create(['organisation_id' => $org->id]);

        $path = "imports/{$org->id}/" . Str::uuid() . '_test.csv';
        Storage::disk('local')->put($path, "email;firstname;lastname\nnonvoting@example.com;Non;Voting\n");
        $importJob = MemberImportJob::create([
            'organisation_id'   => $org->id,
            'initiated_by'      => $admin->id,
            'file_path'         => $path,
            'original_filename' => 'test.csv',
            'status'            => 'pending',
        ]);

        $job = $this->runJob($importJob);

        $this->assertEquals(1, $job->imported_count, implode(' ', array_column($job->error_log ?? [], 'message')));
        $this->assertDatabaseHas('users', ['email' => 'nonvoting@example.com']);
    }

    // NOTE: a "global fallback" test (organisation_id IS NULL membership type)
    // is deliberately NOT included here. Verification found that
    // membership_types.organisation_id is a NOT NULL foreign key at the
    // schema level (migration 2026_04_03_155706_create_membership_types_table),
    // so such a row can never exist — confirmed by an actual constraint
    // violation when the test attempted to insert one. The global-fallback
    // branch in getDefaultMembershipTypeId() is therefore dead code today,
    // independently of this fix. Reported to the user; not addressed as part
    // of this change (separate architectural decision, out of scope here).

    public function test_import_still_fails_clearly_when_no_active_membership_type_exists(): void
    {
        // Regression guard: removing the grants_voting_rights predicate must
        // not remove the "no usable type at all" failure — it must still be
        // reported clearly rather than importing members with no type.
        $org = Organisation::factory()->create(['type' => 'tenant']);
        session(['current_organisation_id' => $org->id]);
        $admin = User::factory()->create(['organisation_id' => $org->id]);

        $path = "imports/{$org->id}/" . Str::uuid() . '_test.csv';
        Storage::disk('local')->put($path, "email;firstname;lastname\nnotype@example.com;No;Type\n");
        $importJob = MemberImportJob::create([
            'organisation_id'   => $org->id,
            'initiated_by'      => $admin->id,
            'file_path'         => $path,
            'original_filename' => 'test.csv',
            'status'            => 'pending',
        ]);

        $job = $this->runJob($importJob);

        $job->refresh();
        $this->assertEquals('failed', $job->status);
        $this->assertStringContainsString(
            'No default membership type found',
            $job->error_log[0]['message'] ?? ''
        );
    }
}

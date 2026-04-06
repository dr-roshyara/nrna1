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
}

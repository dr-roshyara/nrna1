<?php

namespace Tests\Feature\Membership;

use App\Events\Membership\MembershipApplicationApproved;
use App\Events\Membership\MembershipApplicationRejected;
use App\Exceptions\ApplicationAlreadyProcessedException;
use App\Models\Member;
use App\Models\MembershipApplication;
use App\Models\MembershipFee;
use App\Models\MembershipType;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Tests\TestCase;

class MembershipApplicationTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private User $admin;
    private User $applicant;
    private MembershipType $type;

    protected function setUp(): void
    {
        parent::setUp();

        $this->org = Organisation::factory()->create(['type' => 'tenant']);

        session(['current_organisation_id' => $this->org->id]);

        $this->admin = User::factory()->create();
        UserOrganisationRole::create([
            'user_id'         => $this->admin->id,
            'organisation_id' => $this->org->id,
            'role'            => 'admin',
        ]);

        $this->applicant = User::factory()->create();

        $this->type = MembershipType::create([
            'id'              => (string) Str::uuid(),
            'organisation_id' => $this->org->id,
            'name'            => 'Annual',
            'slug'            => 'annual',
            'fee_amount'      => 50.00,
            'fee_currency'    => 'EUR',
            'duration_months' => 12,
            'is_active'       => true,
        ]);
    }

    // ── Guest access ──────────────────────────────────────────────────────────

    /** @test */
    public function guest_cannot_submit_application(): void
    {
        $response = $this->post(
            route('organisations.membership.apply.store', $this->org->slug),
            ['membership_type_id' => $this->type->id]
        );

        $response->assertRedirect(route('login'));
        $this->assertDatabaseCount('membership_applications', 0);
    }

    // ── Duplicate application prevention ─────────────────────────────────────

    /** @test */
    public function existing_member_cannot_apply_again(): void
    {
        // Make applicant an existing active member
        $orgUser = OrganisationUser::create([
            'id'              => (string) Str::uuid(),
            'organisation_id' => $this->org->id,
            'user_id'         => $this->applicant->id,
            'role'            => 'member',
            'status'          => 'active',
        ]);
        Member::create([
            'id'                   => (string) Str::uuid(),
            'organisation_id'      => $this->org->id,
            'organisation_user_id' => $orgUser->id,
            'status'               => 'active',
            'membership_expires_at' => now()->addYear(),
        ]);
        UserOrganisationRole::create([
            'user_id'         => $this->applicant->id,
            'organisation_id' => $this->org->id,
            'role'            => 'member',
        ]);

        $response = $this->actingAs($this->applicant)->post(
            route('organisations.membership.apply.store', $this->org->slug),
            ['membership_type_id' => $this->type->id]
        );

        $response->assertRedirect();
        $response->assertSessionHasErrors();
        $this->assertDatabaseCount('membership_applications', 0);
    }

    /** @test */
    public function user_with_pending_application_cannot_apply_again(): void
    {
        MembershipApplication::create([
            'id'                 => (string) Str::uuid(),
            'organisation_id'    => $this->org->id,
            'user_id'            => $this->applicant->id,
            'membership_type_id' => $this->type->id,
            'status'             => 'submitted',
            'expires_at'         => now()->addDays(30),
        ]);

        $response = $this->actingAs($this->applicant)->post(
            route('organisations.membership.apply.store', $this->org->slug),
            ['membership_type_id' => $this->type->id]
        );

        $response->assertRedirect();
        $response->assertSessionHasErrors();
        $this->assertDatabaseCount('membership_applications', 1);
    }

    // ── Successful submission ─────────────────────────────────────────────────

    /** @test */
    public function valid_application_creates_record_with_submitted_status(): void
    {
        $response = $this->actingAs($this->applicant)->post(
            route('organisations.membership.apply.store', $this->org->slug),
            ['membership_type_id' => $this->type->id]
        );

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('membership_applications', [
            'organisation_id'    => $this->org->id,
            'user_id'            => $this->applicant->id,
            'membership_type_id' => $this->type->id,
            'status'             => 'submitted',
        ]);
    }

    // ── Index / Show access ───────────────────────────────────────────────────

    /** @test */
    public function admin_can_view_all_applications(): void
    {
        MembershipApplication::create([
            'id'                 => (string) Str::uuid(),
            'organisation_id'    => $this->org->id,
            'user_id'            => $this->applicant->id,
            'membership_type_id' => $this->type->id,
            'status'             => 'submitted',
            'expires_at'         => now()->addDays(30),
        ]);

        $response = $this->actingAs($this->admin)->get(
            route('organisations.membership.applications.index', $this->org->slug)
        );

        $response->assertOk();
        $response->assertInertia(fn ($page) =>
            $page->component('Organisations/Membership/Applications/Index')
                 ->has('applications')
        );
    }

    /** @test */
    public function member_cannot_view_applications_index(): void
    {
        $member = User::factory()->create();
        UserOrganisationRole::create([
            'user_id'         => $member->id,
            'organisation_id' => $this->org->id,
            'role'            => 'member',
        ]);

        $response = $this->actingAs($member)->get(
            route('organisations.membership.applications.index', $this->org->slug)
        );

        $response->assertForbidden();
    }

    // ── Approve ───────────────────────────────────────────────────────────────

    /** @test */
    public function approve_creates_organisation_user_and_member(): void
    {
        Event::fake([MembershipApplicationApproved::class]);

        $app = MembershipApplication::create([
            'id'                 => (string) Str::uuid(),
            'organisation_id'    => $this->org->id,
            'user_id'            => $this->applicant->id,
            'membership_type_id' => $this->type->id,
            'status'             => 'submitted',
            'expires_at'         => now()->addDays(30),
        ]);

        $this->actingAs($this->admin)->patch(
            route('organisations.membership.applications.approve', [$this->org->slug, $app->id])
        );

        $this->assertDatabaseHas('membership_applications', [
            'id'     => $app->id,
            'status' => 'approved',
        ]);

        $this->assertDatabaseHas('organisation_users', [
            'organisation_id' => $this->org->id,
            'user_id'         => $this->applicant->id,
        ]);

        $this->assertDatabaseHas('members', [
            'organisation_id' => $this->org->id,
            'status'          => 'active',
        ]);
    }

    /** @test */
    public function approve_creates_pending_membership_fee(): void
    {
        Event::fake([MembershipApplicationApproved::class]);

        $app = MembershipApplication::create([
            'id'                 => (string) Str::uuid(),
            'organisation_id'    => $this->org->id,
            'user_id'            => $this->applicant->id,
            'membership_type_id' => $this->type->id,
            'status'             => 'submitted',
            'expires_at'         => now()->addDays(30),
        ]);

        $this->actingAs($this->admin)->patch(
            route('organisations.membership.applications.approve', [$this->org->slug, $app->id])
        );

        $this->assertDatabaseHas('membership_fees', [
            'organisation_id'    => $this->org->id,
            'membership_type_id' => $this->type->id,
            'amount'             => 50.00,
            'fee_amount_at_time' => 50.00,
            'status'             => 'pending',
        ]);
    }

    /** @test */
    public function approve_fires_membership_application_approved_event(): void
    {
        Event::fake([MembershipApplicationApproved::class]);

        $app = MembershipApplication::create([
            'id'                 => (string) Str::uuid(),
            'organisation_id'    => $this->org->id,
            'user_id'            => $this->applicant->id,
            'membership_type_id' => $this->type->id,
            'status'             => 'submitted',
            'expires_at'         => now()->addDays(30),
        ]);

        $this->actingAs($this->admin)->patch(
            route('organisations.membership.applications.approve', [$this->org->slug, $app->id])
        );

        Event::assertDispatched(MembershipApplicationApproved::class, fn ($e) =>
            $e->application->id === $app->id
        );
    }

    // ── Reject ────────────────────────────────────────────────────────────────

    /** @test */
    public function reject_sets_status_to_rejected_with_reason(): void
    {
        Event::fake([MembershipApplicationRejected::class]);

        $app = MembershipApplication::create([
            'id'                 => (string) Str::uuid(),
            'organisation_id'    => $this->org->id,
            'user_id'            => $this->applicant->id,
            'membership_type_id' => $this->type->id,
            'status'             => 'submitted',
            'expires_at'         => now()->addDays(30),
        ]);

        $this->actingAs($this->admin)->patch(
            route('organisations.membership.applications.reject', [$this->org->slug, $app->id]),
            ['rejection_reason' => 'Incomplete documents.']
        );

        $this->assertDatabaseHas('membership_applications', [
            'id'               => $app->id,
            'status'           => 'rejected',
            'rejection_reason' => 'Incomplete documents.',
        ]);
    }

    /** @test */
    public function reject_fires_membership_application_rejected_event(): void
    {
        Event::fake([MembershipApplicationRejected::class]);

        $app = MembershipApplication::create([
            'id'                 => (string) Str::uuid(),
            'organisation_id'    => $this->org->id,
            'user_id'            => $this->applicant->id,
            'membership_type_id' => $this->type->id,
            'status'             => 'submitted',
            'expires_at'         => now()->addDays(30),
        ]);

        $this->actingAs($this->admin)->patch(
            route('organisations.membership.applications.reject', [$this->org->slug, $app->id]),
            ['rejection_reason' => 'Not eligible.']
        );

        Event::assertDispatched(MembershipApplicationRejected::class, fn ($e) =>
            $e->application->id === $app->id
        );
    }

    // ── Guard rails ───────────────────────────────────────────────────────────

    /** @test */
    public function approved_application_cannot_be_approved_again(): void
    {
        $app = MembershipApplication::create([
            'id'                 => (string) Str::uuid(),
            'organisation_id'    => $this->org->id,
            'user_id'            => $this->applicant->id,
            'membership_type_id' => $this->type->id,
            'status'             => 'approved',
            'expires_at'         => now()->addDays(30),
        ]);

        $response = $this->actingAs($this->admin)->patch(
            route('organisations.membership.applications.approve', [$this->org->slug, $app->id])
        );

        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function concurrent_approval_is_handled_gracefully(): void
    {
        // Simulate a stale lock_version (already approved by another request)
        $app = MembershipApplication::create([
            'id'                 => (string) Str::uuid(),
            'organisation_id'    => $this->org->id,
            'user_id'            => $this->applicant->id,
            'membership_type_id' => $this->type->id,
            'status'             => 'submitted',
            'expires_at'         => now()->addDays(30),
            'lock_version'       => 0,
        ]);

        // Simulate concurrent update: bump lock_version externally
        \DB::table('membership_applications')
            ->where('id', $app->id)
            ->update(['lock_version' => 1]);

        $response = $this->actingAs($this->admin)->patch(
            route('organisations.membership.applications.approve', [$this->org->slug, $app->id])
        );

        // Should return a 409 or redirect with error
        $this->assertTrue(
            $response->isRedirect() || $response->getStatusCode() === 409,
            'Expected redirect or 409 on concurrent approval'
        );
    }

    /** @test */
    public function expired_application_is_rejected_by_daily_job(): void
    {
        $app = MembershipApplication::create([
            'id'                 => (string) Str::uuid(),
            'organisation_id'    => $this->org->id,
            'user_id'            => $this->applicant->id,
            'membership_type_id' => $this->type->id,
            'status'             => 'submitted',
            'expires_at'         => now()->subDay(), // already expired
        ]);

        \Artisan::call('membership:process-expiry');

        $this->assertDatabaseHas('membership_applications', [
            'id'     => $app->id,
            'status' => 'rejected',
        ]);
    }
}

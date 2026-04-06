<?php

namespace Tests\Feature\Membership;

use App\Models\MembershipApplication;
use App\Models\MembershipType;
use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Tests\TestCase;

class PublicApplicationApprovalTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private User $admin;
    private MembershipType $type;
    private MembershipApplication $application;

    protected function setUp(): void
    {
        parent::setUp();

        $this->org = Organisation::factory()->create(['slug' => 'test-org', 'type' => 'tenant']);

        session(['current_organisation_id' => $this->org->id]);

        $this->admin = User::factory()->create();
        UserOrganisationRole::create([
            'id'              => (string) Str::uuid(),
            'organisation_id' => $this->org->id,
            'user_id'         => $this->admin->id,
            'role'            => 'admin',
        ]);

        $this->type = MembershipType::factory()->create([
            'organisation_id' => $this->org->id,
            'is_active'       => true,
            'fee_amount'      => 50,
            'duration_months' => 12,
        ]);

        $this->application = MembershipApplication::create([
            'id'              => (string) Str::uuid(),
            'organisation_id' => $this->org->id,
            'user_id'         => null,
            'membership_type_id' => null,
            'applicant_email' => 'newmember@example.com',
            'source'          => 'public',
            'status'          => 'submitted',
            'submitted_at'    => now(),
            'application_data' => [
                'first_name'       => 'New',
                'last_name'        => 'Member',
                'email'            => 'newmember@example.com',
                'telephone_number' => '+49 111 222333',
                'education_level'  => "Master's Degree",
                'city'             => 'Munich',
                'country'          => 'Germany',
                'profession'       => 'Developer',
            ],
        ]);

        Mail::fake();
        Password::shouldReceive('sendResetLink')->andReturn(Password::RESET_LINK_SENT);
    }

    private function approveApplication(array $data = []): \Illuminate\Testing\TestResponse
    {
        return $this->actingAs($this->admin)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->patch("/organisations/{$this->org->slug}/membership/applications/{$this->application->id}/approve", array_merge([
                'membership_type_id' => $this->type->id,
            ], $data));
    }

    public function test_approving_public_application_creates_user(): void
    {
        $this->approveApplication();

        $this->assertDatabaseHas('users', [
            'email'      => 'newmember@example.com',
            'first_name' => 'New',
            'last_name'  => 'Member',
            'city'       => 'Munich',
            'country'    => 'Germany',
        ]);
    }

    public function test_approving_public_application_sets_education_and_profession(): void
    {
        $this->approveApplication();

        $user = User::withoutGlobalScopes()->where('email', 'newmember@example.com')->first();
        $this->assertEquals("Master's Degree", $user->education_level);
        $this->assertEquals('Developer',       $user->profession);
    }

    public function test_approving_public_application_creates_member_record(): void
    {
        $this->approveApplication();

        $user = User::withoutGlobalScopes()->where('email', 'newmember@example.com')->first();

        $this->assertDatabaseHas('organisation_users', [
            'organisation_id' => $this->org->id,
            'user_id'         => $user->id,
        ]);

        $this->assertDatabaseHas('members', [
            'organisation_id' => $this->org->id,
            'status'          => 'active',
        ]);
    }

    public function test_approving_public_application_requires_membership_type(): void
    {
        $response = $this->actingAs($this->admin)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->patch("/organisations/{$this->org->slug}/membership/applications/{$this->application->id}/approve", [
                // no membership_type_id
            ]);

        $response->assertSessionHasErrors('membership_type_id');

        $this->assertDatabaseMissing('users', ['email' => 'newmember@example.com']);
    }

    public function test_approving_public_application_updates_application_user_id(): void
    {
        $this->approveApplication();

        $user = User::withoutGlobalScopes()->where('email', 'newmember@example.com')->first();
        $this->application->refresh();

        $this->assertNotNull($this->application->user_id);
        $this->assertEquals($user->id, $this->application->user_id);
    }

    public function test_non_admin_cannot_approve_public_application(): void
    {
        $regularUser = User::factory()->create();

        $response = $this->actingAs($regularUser)
            ->patch("/organisations/{$this->org->slug}/membership/applications/{$this->application->id}/approve", [
                'membership_type_id' => $this->type->id,
            ]);

        // Non-member gets redirected by ensure.organisation middleware
        $response->assertRedirect();
        $this->assertDatabaseMissing('users', ['email' => 'newmember@example.com']);
    }
}

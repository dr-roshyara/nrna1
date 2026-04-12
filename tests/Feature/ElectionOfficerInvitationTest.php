<?php

namespace Tests\Feature;

use App\Models\ElectionOfficer;
use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;
use App\Notifications\OfficerAppointedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Tests\TestCase;

class ElectionOfficerInvitationTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private User $admin;
    private User $member;

    protected function setUp(): void
    {
        parent::setUp();

        $this->org = Organisation::factory()->create(['type' => 'tenant']);
        session(['current_organisation_id' => $this->org->id]);

        $this->admin = User::factory()->create([
            'organisation_id'   => $this->org->id,
            'email_verified_at' => now(),
        ]);
        UserOrganisationRole::create([
            'id'              => (string) Str::uuid(),
            'user_id'         => $this->admin->id,
            'organisation_id' => $this->org->id,
            'role'            => 'admin',
        ]);

        $this->member = User::factory()->create([
            'organisation_id'   => $this->org->id,
            'email_verified_at' => now(),
        ]);
        UserOrganisationRole::create([
            'id'              => (string) Str::uuid(),
            'user_id'         => $this->member->id,
            'organisation_id' => $this->org->id,
            'role'            => 'voter',
        ]);
    }

    // =========================================================================
    // TEST 1: Email is sent when officer is appointed
    // =========================================================================

    public function test_email_notification_is_sent_when_officer_appointed(): void
    {
        Notification::fake();

        $this->actingAs($this->admin)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->post(route('organisations.election-officers.store', $this->org->slug), [
                'user_id' => $this->member->id,
                'role'    => 'commissioner',
            ]);

        Notification::assertSentTo(
            $this->member,
            OfficerAppointedNotification::class,
            fn ($notification, $channels) => in_array('mail', $channels)
        );
    }

    // =========================================================================
    // TEST 2: Unsigned URL is rejected, signed URL succeeds
    // =========================================================================

    public function test_invitation_accept_url_requires_valid_signature(): void
    {
        $officer = ElectionOfficer::create([
            'organisation_id' => $this->org->id,
            'user_id'         => $this->member->id,
            'role'            => 'commissioner',
            'status'          => 'pending',
            'appointed_by'    => $this->admin->id,
            'appointed_at'    => now(),
        ]);

        // Unsigned URL must be rejected
        $unsignedUrl = route('organisations.election-officers.invitation.accept', [
            'organisation' => $this->org->slug,
            'officer'      => $officer->id,
        ]);
        $this->get($unsignedUrl)->assertStatus(403);

        // Valid signed URL must be accepted (guest → login page)
        $signedUrl = URL::signedRoute('organisations.election-officers.invitation.accept', [
            'organisation' => $this->org->slug,
            'officer'      => $officer->id,
        ]);
        $this->get($signedUrl)->assertStatus(200);
    }

    // =========================================================================
    // TEST 3: Authenticated officer can accept via invitation link
    // =========================================================================

    public function test_authenticated_user_can_accept_via_invitation_link(): void
    {
        $officer = ElectionOfficer::create([
            'organisation_id' => $this->org->id,
            'user_id'         => $this->member->id,
            'role'            => 'commissioner',
            'status'          => 'pending',
            'appointed_by'    => $this->admin->id,
            'appointed_at'    => now(),
        ]);

        $signedUrl = URL::signedRoute('organisations.election-officers.invitation.accept', [
            'organisation' => $this->org->slug,
            'officer'      => $officer->id,
        ]);

        $response = $this->actingAs($this->member)->get($signedUrl);

        $response->assertInertia(fn ($page) => $page->component('Organisations/ElectionOfficers/Accepted'));

        $officer->refresh();
        $this->assertEquals('active', $officer->status);
        $this->assertNotNull($officer->accepted_at);
    }

    // =========================================================================
    // TEST 4: Guest visiting invitation link is shown the login page
    // =========================================================================

    public function test_guest_visiting_invitation_link_is_shown_login_page(): void
    {
        $officer = ElectionOfficer::create([
            'organisation_id' => $this->org->id,
            'user_id'         => $this->member->id,
            'role'            => 'commissioner',
            'status'          => 'pending',
            'appointed_by'    => $this->admin->id,
            'appointed_at'    => now(),
        ]);

        $signedUrl = URL::signedRoute('organisations.election-officers.invitation.accept', [
            'organisation' => $this->org->slug,
            'officer'      => $officer->id,
        ]);

        $response = $this->get($signedUrl);

        $response->assertInertia(fn ($page) =>
            $page->component('Auth/Login')
                 ->has('message')
                 ->where('email', $this->member->email)
        );

        $this->assertEquals($officer->id, session('pending_acceptance.officer_id'));
    }

    // =========================================================================
    // TEST 5: Wrong logged-in user cannot accept another's invitation
    // =========================================================================

    public function test_cannot_accept_invitation_for_different_user(): void
    {
        $otherUser = User::factory()->create(['organisation_id' => $this->org->id]);
        UserOrganisationRole::create([
            'id'              => (string) Str::uuid(),
            'user_id'         => $otherUser->id,
            'organisation_id' => $this->org->id,
            'role'            => 'voter',
        ]);

        $officer = ElectionOfficer::create([
            'organisation_id' => $this->org->id,
            'user_id'         => $otherUser->id,
            'role'            => 'commissioner',
            'status'          => 'pending',
            'appointed_by'    => $this->admin->id,
            'appointed_at'    => now(),
        ]);

        $signedUrl = URL::signedRoute('organisations.election-officers.invitation.accept', [
            'organisation' => $this->org->slug,
            'officer'      => $officer->id,
        ]);

        // Login as a *different* user
        $response = $this->actingAs($this->member)->get($signedUrl);

        $response->assertStatus(403);

        $officer->refresh();
        $this->assertEquals('pending', $officer->status);
    }

    // =========================================================================
    // TEST 6: Expired invitation link is rejected
    // =========================================================================

    public function test_expired_invitation_link_is_rejected(): void
    {
        $officer = ElectionOfficer::create([
            'organisation_id' => $this->org->id,
            'user_id'         => $this->member->id,
            'role'            => 'commissioner',
            'status'          => 'pending',
            'appointed_by'    => $this->admin->id,
            'appointed_at'    => now(),
        ]);

        $expiredUrl = URL::temporarySignedRoute(
            'organisations.election-officers.invitation.accept',
            now()->subMinutes(5),
            [
                'organisation' => $this->org->slug,
                'officer'      => $officer->id,
            ]
        );

        $this->get($expiredUrl)->assertStatus(403);

        $officer->refresh();
        $this->assertEquals('pending', $officer->status);
    }

    // =========================================================================
    // TEST 7: Cannot accept already-accepted appointment
    // =========================================================================

    public function test_cannot_accept_already_accepted_appointment(): void
    {
        $officer = ElectionOfficer::create([
            'organisation_id' => $this->org->id,
            'user_id'         => $this->member->id,
            'role'            => 'commissioner',
            'status'          => 'active',
            'accepted_at'     => now(),
            'appointed_by'    => $this->admin->id,
            'appointed_at'    => now(),
        ]);

        $signedUrl = URL::signedRoute('organisations.election-officers.invitation.accept', [
            'organisation' => $this->org->slug,
            'officer'      => $officer->id,
        ]);

        $response = $this->actingAs($this->member)->get($signedUrl);

        $response->assertInertia(fn ($page) =>
            $page->component('Errors/Message')
                 ->where('title', 'Appointment Already Processed')
        );
    }
}

<?php

namespace Tests\Feature\Membership;

use App\Mail\Membership\PublicApplicationAdminNotificationMail;
use App\Mail\Membership\PublicApplicationConfirmationMail;
use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Tests\TestCase;

class PublicApplicationEmailTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;

    protected function setUp(): void
    {
        parent::setUp();
        $this->org = Organisation::factory()->create(['slug' => 'test-org']);
        Mail::fake();
    }

    private function postJoin(array $overrides = []): \Illuminate\Testing\TestResponse
    {
        return $this->post("/organisations/{$this->org->slug}/join", array_merge([
            'first_name' => 'Jane',
            'last_name'  => 'Smith',
            'email'      => 'jane@example.com',
            'website'    => '',
        ], $overrides));
    }

    public function test_confirmation_mail_sent_to_applicant(): void
    {
        $this->postJoin();

        Mail::assertQueued(PublicApplicationConfirmationMail::class, function ($mail) {
            return $mail->hasTo('jane@example.com');
        });
    }

    public function test_admin_notification_sent_to_org_admins(): void
    {
        $admin = User::factory()->create(['email' => 'admin@org.com']);
        UserOrganisationRole::create([
            'id'              => (string) Str::uuid(),
            'organisation_id' => $this->org->id,
            'user_id'         => $admin->id,
            'role'            => 'admin',
        ]);

        $this->postJoin();

        Mail::assertQueued(PublicApplicationAdminNotificationMail::class, function ($mail) {
            return $mail->hasTo('admin@org.com');
        });
    }

    public function test_admin_notification_sent_to_owner(): void
    {
        $owner = User::factory()->create(['email' => 'owner@org.com']);
        UserOrganisationRole::create([
            'id'              => (string) Str::uuid(),
            'organisation_id' => $this->org->id,
            'user_id'         => $owner->id,
            'role'            => 'owner',
        ]);

        $this->postJoin();

        Mail::assertQueued(PublicApplicationAdminNotificationMail::class, function ($mail) {
            return $mail->hasTo('owner@org.com');
        });
    }

    public function test_no_admin_notification_when_no_admins_exist(): void
    {
        $this->postJoin();

        // Should not throw — just no notification sent to admin
        Mail::assertNotQueued(PublicApplicationAdminNotificationMail::class);
    }

    public function test_honeypot_filled_silently_suppresses_emails(): void
    {
        $this->postJoin(['website' => 'http://spam.com']);

        Mail::assertNothingQueued();
    }
}

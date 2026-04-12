<?php

namespace Tests\Feature\Organisation;

use App\Models\Member;
use App\Models\NewsletterAuditLog;
use App\Models\NewsletterRecipient;
use App\Models\Organisation;
use App\Models\OrganisationNewsletter;
use App\Models\OrganisationUser;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use App\Jobs\SendNewsletterBatchJob;

class NewsletterKillSwitchTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $organisation;
    private User $admin;
    private OrganisationNewsletter $newsletter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->organisation = Organisation::factory()->create();
        $this->admin        = User::factory()->create();

        UserOrganisationRole::create([
            'organisation_id' => $this->organisation->id,
            'user_id'         => $this->admin->id,
            'role'            => 'admin',
        ]);

        $this->newsletter = OrganisationNewsletter::create([
            'organisation_id' => $this->organisation->id,
            'created_by'      => $this->admin->id,
            'subject'         => 'Test Newsletter',
            'html_content'    => '<p>Hello</p>',
            'status'          => 'processing',
        ]);
    }

    public function test_campaign_fails_when_failure_rate_exceeds_threshold(): void
    {
        // 40 sent + 11 failed = 51 total, 11/51 ≈ 21.5% — above 20% threshold
        $this->newsletter->update([
            'sent_count'   => 40,
            'failed_count' => 11,
        ]);

        // isKillSwitchTriggered() should now be true
        $this->newsletter->refresh();
        $this->assertTrue($this->newsletter->isKillSwitchTriggered());

        // Run the job — it checks kill switch and fails the campaign
        Mail::fake();
        $recipient = $this->createRecipient();
        (new SendNewsletterBatchJob($this->newsletter->id, [$recipient->id]))->handle();

        $this->newsletter->refresh();
        $this->assertEquals('failed', $this->newsletter->status);

        $this->assertDatabaseHas('newsletter_audit_logs', [
            'organisation_newsletter_id' => $this->newsletter->id,
            'action'                     => 'failed',
        ]);
    }

    public function test_campaign_does_not_trigger_below_threshold(): void
    {
        // 50 sent + 9 failed = 59 total, 9/59 ≈ 15% — below 20%
        $this->newsletter->update([
            'sent_count'   => 50,
            'failed_count' => 9,
        ]);

        $this->newsletter->refresh();
        $this->assertFalse($this->newsletter->isKillSwitchTriggered());
    }

    public function test_kill_switch_does_not_trigger_before_minimum_50_processed(): void
    {
        // 3 sent + 5 failed = 8 total — below the 50 minimum
        $this->newsletter->update([
            'sent_count'   => 3,
            'failed_count' => 5,
        ]);

        $this->newsletter->refresh();
        $this->assertFalse($this->newsletter->isKillSwitchTriggered());
        $this->assertEquals('processing', $this->newsletter->status);
    }

    private function createRecipient(): NewsletterRecipient
    {
        $user    = User::factory()->create();
        $orgUser = OrganisationUser::create([
            'organisation_id' => $this->organisation->id,
            'user_id'         => $user->id,
            'role'            => 'member',
            'status'          => 'active',
            'joined_at'       => now(),
        ]);
        $member = Member::create([
            'organisation_id'      => $this->organisation->id,
            'organisation_user_id' => $orgUser->id,
            'membership_number'    => 'M' . uniqid(),
            'status'               => 'active',
            'fees_status'          => 'unpaid',
        ]);

        return NewsletterRecipient::create([
            'organisation_newsletter_id' => $this->newsletter->id,
            'member_id'                  => $member->id,
            'email'                      => $user->email,
            'name'                       => $user->name,
            'status'                     => 'pending',
            'idempotency_key'            => hash('sha256', $this->newsletter->id . ':' . $member->id . ':' . uniqid()),
        ]);
    }
}

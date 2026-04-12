<?php

namespace Tests\Feature\Organisation;

use App\Events\Newsletter\NewsletterEmailFailed;
use App\Events\Newsletter\NewsletterEmailSent;
use App\Jobs\SendNewsletterBatchJob;
use App\Models\Member;
use App\Models\NewsletterRecipient;
use App\Models\Organisation;
use App\Models\OrganisationNewsletter;
use App\Models\OrganisationUser;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class NewsletterBatchJobTest extends TestCase
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
            'html_content'    => '<p>Hello members!</p>',
            'status'          => 'processing',
        ]);
    }

    public function test_batch_job_marks_recipients_sent(): void
    {
        Mail::fake();
        Event::fake([NewsletterEmailSent::class]);

        $recipient = $this->createRecipient();

        (new SendNewsletterBatchJob($this->newsletter->id, [$recipient->id]))->handle();

        $recipient->refresh();
        $this->assertEquals('sent', $recipient->status);
        $this->assertNotNull($recipient->sent_at);

        Event::assertDispatched(NewsletterEmailSent::class);
    }

    public function test_batch_job_marks_failed_recipient_on_exception(): void
    {
        Event::fake([NewsletterEmailFailed::class]);

        Mail::shouldReceive('to')->andThrow(new \Exception('SMTP error'));

        $recipient = $this->createRecipient();

        (new SendNewsletterBatchJob($this->newsletter->id, [$recipient->id]))->handle();

        $recipient->refresh();
        $this->assertEquals('failed', $recipient->status);
        $this->assertNotNull($recipient->error_message);

        Event::assertDispatched(NewsletterEmailFailed::class);
    }

    public function test_batch_job_skips_already_sent_recipient(): void
    {
        Mail::fake();

        $recipient = $this->createRecipient(status: 'sent');

        (new SendNewsletterBatchJob($this->newsletter->id, [$recipient->id]))->handle();

        Mail::assertNothingSent();
    }

    public function test_batch_job_skips_recipient_with_status_sending(): void
    {
        Mail::fake();

        $recipient = $this->createRecipient(status: 'sending');

        (new SendNewsletterBatchJob($this->newsletter->id, [$recipient->id]))->handle();

        Mail::assertNothingSent();
    }

    public function test_campaign_counts_updated_via_job(): void
    {
        Mail::fake();

        $r1 = $this->createRecipient();
        $r2 = $this->createRecipient();
        $r3 = $this->createRecipient();

        (new SendNewsletterBatchJob($this->newsletter->id, [$r1->id, $r2->id, $r3->id]))->handle();

        $this->newsletter->refresh();
        $this->assertEquals(3, $this->newsletter->sent_count);
        $this->assertEquals(0, $this->newsletter->failed_count);
    }

    private function createRecipient(string $status = 'pending'): NewsletterRecipient
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
            'status'                     => $status,
            'idempotency_key'            => hash('sha256', $this->newsletter->id . ':' . $member->id . ':' . uniqid()),
        ]);
    }
}

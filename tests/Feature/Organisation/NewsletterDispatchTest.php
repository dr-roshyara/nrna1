<?php

namespace Tests\Feature\Organisation;

use App\Models\Member;
use App\Models\Organisation;
use App\Models\OrganisationNewsletter;
use App\Models\OrganisationUser;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class NewsletterDispatchTest extends TestCase
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
            'status'          => 'draft',
        ]);
    }

    public function test_admin_can_dispatch_draft_newsletter(): void
    {
        Queue::fake();

        $this->actingAs($this->admin)
             ->patch(route('organisations.membership.newsletters.send', [
                 $this->organisation->slug,
                 $this->newsletter->id,
             ]))
             ->assertRedirect();

        $this->newsletter->refresh();
        $this->assertEquals('queued', $this->newsletter->status);
        $this->assertNotNull($this->newsletter->idempotency_key);
        $this->assertNotNull($this->newsletter->queued_at);

        $this->assertDatabaseHas('newsletter_audit_logs', [
            'organisation_newsletter_id' => $this->newsletter->id,
            'action'                     => 'dispatched',
        ]);
    }

    public function test_cannot_dispatch_already_queued_newsletter(): void
    {
        $this->newsletter->update(['status' => 'queued']);

        $this->actingAs($this->admin)
             ->patch(route('organisations.membership.newsletters.send', [
                 $this->organisation->slug,
                 $this->newsletter->id,
             ]))
             ->assertStatus(422);
    }

    public function test_cannot_dispatch_completed_newsletter(): void
    {
        $this->newsletter->update(['status' => 'completed']);

        $this->actingAs($this->admin)
             ->patch(route('organisations.membership.newsletters.send', [
                 $this->organisation->slug,
                 $this->newsletter->id,
             ]))
             ->assertStatus(422);
    }

    public function test_non_admin_cannot_dispatch(): void
    {
        $member = User::factory()->create();
        UserOrganisationRole::create([
            'organisation_id' => $this->organisation->id,
            'user_id'         => $member->id,
            'role'            => 'member',
        ]);

        $this->actingAs($member)
             ->patch(route('organisations.membership.newsletters.send', [
                 $this->organisation->slug,
                 $this->newsletter->id,
             ]))
             ->assertStatus(403);
    }

    public function test_admin_can_cancel_draft_newsletter(): void
    {
        $this->actingAs($this->admin)
             ->patch(route('organisations.membership.newsletters.cancel', [
                 $this->organisation->slug,
                 $this->newsletter->id,
             ]))
             ->assertRedirect();

        $this->newsletter->refresh();
        $this->assertEquals('cancelled', $this->newsletter->status);

        $this->assertDatabaseHas('newsletter_audit_logs', [
            'organisation_newsletter_id' => $this->newsletter->id,
            'action'                     => 'cancelled',
        ]);
    }

    public function test_cannot_cancel_completed_newsletter(): void
    {
        $this->newsletter->update(['status' => 'completed']);

        $this->actingAs($this->admin)
             ->patch(route('organisations.membership.newsletters.cancel', [
                 $this->organisation->slug,
                 $this->newsletter->id,
             ]))
             ->assertStatus(422);
    }
}

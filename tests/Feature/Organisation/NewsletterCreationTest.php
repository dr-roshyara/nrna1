<?php

namespace Tests\Feature\Organisation;

use App\Models\Member;
use App\Models\Organisation;
use App\Models\OrganisationNewsletter;
use App\Models\OrganisationUser;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsletterCreationTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $organisation;
    private User $admin;

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
    }

    public function test_admin_can_create_newsletter_draft(): void
    {
        $this->actingAs($this->admin)
             ->post(route('organisations.membership.newsletters.store', $this->organisation->slug), [
                 'subject'      => 'Monthly Update',
                 'html_content' => '<p>Hello members!</p>',
             ])
             ->assertRedirect();

        $this->assertDatabaseHas('organisation_newsletters', [
            'organisation_id' => $this->organisation->id,
            'subject'         => 'Monthly Update',
            'status'          => 'draft',
        ]);

        $this->assertDatabaseHas('newsletter_audit_logs', [
            'organisation_id' => $this->organisation->id,
            'action'          => 'created',
        ]);
    }

    public function test_owner_can_create_newsletter_draft(): void
    {
        $owner = User::factory()->create();
        UserOrganisationRole::create([
            'organisation_id' => $this->organisation->id,
            'user_id'         => $owner->id,
            'role'            => 'owner',
        ]);

        $this->actingAs($owner)
             ->post(route('organisations.membership.newsletters.store', $this->organisation->slug), [
                 'subject'      => 'Owner Message',
                 'html_content' => '<p>From the owner</p>',
             ])
             ->assertRedirect();

        $this->assertDatabaseHas('organisation_newsletters', [
            'organisation_id' => $this->organisation->id,
            'subject'         => 'Owner Message',
            'status'          => 'draft',
        ]);
    }

    public function test_non_admin_cannot_create_newsletter(): void
    {
        $member = User::factory()->create();
        UserOrganisationRole::create([
            'organisation_id' => $this->organisation->id,
            'user_id'         => $member->id,
            'role'            => 'member',
        ]);

        $this->actingAs($member)
             ->post(route('organisations.membership.newsletters.store', $this->organisation->slug), [
                 'subject'      => 'Spam',
                 'html_content' => '<p>Buy now!</p>',
             ])
             ->assertStatus(403);
    }

    public function test_html_content_is_sanitised_on_save(): void
    {
        $this->actingAs($this->admin)
             ->post(route('organisations.membership.newsletters.store', $this->organisation->slug), [
                 'subject'      => 'XSS Test',
                 'html_content' => '<p>Hello</p><script>alert(1)</script>',
             ]);

        $newsletter = OrganisationNewsletter::withoutGlobalScopes()
            ->where('organisation_id', $this->organisation->id)
            ->first();

        $this->assertNotNull($newsletter);
        $this->assertStringNotContainsString('<script>', $newsletter->html_content);
    }

    public function test_admin_can_view_newsletter_list(): void
    {
        $this->actingAs($this->admin)
             ->get(route('organisations.membership.newsletters.index', $this->organisation->slug))
             ->assertStatus(200);
    }

    public function test_preview_recipient_count_returns_active_member_count(): void
    {
        // Create 3 active members, 1 inactive, 1 unsubscribed
        $this->createMember(status: 'active');
        $this->createMember(status: 'active');
        $this->createMember(status: 'active');
        $this->createMember(status: 'inactive');
        $this->createMember(status: 'active', unsubscribed: true);

        $newsletter = OrganisationNewsletter::create([
            'organisation_id' => $this->organisation->id,
            'created_by'      => $this->admin->id,
            'subject'         => 'Test',
            'html_content'    => '<p>Hello</p>',
            'status'          => 'draft',
        ]);

        $response = $this->actingAs($this->admin)
             ->getJson(route('organisations.membership.newsletters.preview', [
                 $this->organisation->slug,
                 $newsletter->id,
             ]));

        $response->assertStatus(200)
                 ->assertJson(['count' => 3]);
    }

    private function createMember(string $status = 'active', bool $unsubscribed = false): Member
    {
        $user    = User::factory()->create();
        $orgUser = OrganisationUser::create([
            'organisation_id' => $this->organisation->id,
            'user_id'         => $user->id,
            'role'            => 'member',
            'status'          => 'active',
            'joined_at'       => now(),
        ]);

        return Member::create([
            'organisation_id'            => $this->organisation->id,
            'organisation_user_id'       => $orgUser->id,
            'membership_number'          => 'M' . uniqid(),
            'status'                     => $status,
            'fees_status'                => 'unpaid',
            'newsletter_unsubscribed_at' => $unsubscribed ? now() : null,
        ]);
    }
}

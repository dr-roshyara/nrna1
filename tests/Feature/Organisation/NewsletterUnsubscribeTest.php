<?php

namespace Tests\Feature\Organisation;

use App\Models\Member;
use App\Models\NewsletterRecipient;
use App\Models\Organisation;
use App\Models\OrganisationNewsletter;
use App\Models\OrganisationUser;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class NewsletterUnsubscribeTest extends TestCase
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

    public function test_member_can_unsubscribe_via_token(): void
    {
        $member = $this->createMember();
        $token  = Str::random(64);
        $member->update(['newsletter_unsubscribe_token' => $token]);

        $this->get(route('newsletter.unsubscribe', $token))
             ->assertStatus(200);

        $member->refresh();
        $this->assertNotNull($member->newsletter_unsubscribed_at);
    }

    public function test_invalid_token_returns_404(): void
    {
        $this->get(route('newsletter.unsubscribe', 'invalid-token-xyz'))
             ->assertStatus(404);
    }

    public function test_already_unsubscribed_member_handled_gracefully(): void
    {
        $member = $this->createMember();
        $token  = Str::random(64);
        $member->update([
            'newsletter_unsubscribe_token' => $token,
            'newsletter_unsubscribed_at'   => now(),
        ]);

        $this->get(route('newsletter.unsubscribe', $token))
             ->assertStatus(200);
    }

    public function test_unsubscribed_members_excluded_from_recipients_on_dispatch(): void
    {
        $activeUser    = User::factory()->create();
        $activeOrgUser = OrganisationUser::create([
            'organisation_id' => $this->organisation->id,
            'user_id'         => $activeUser->id,
            'role'            => 'member',
            'status'          => 'active',
            'joined_at'       => now(),
        ]);
        Member::create([
            'organisation_id'      => $this->organisation->id,
            'organisation_user_id' => $activeOrgUser->id,
            'membership_number'    => 'M' . uniqid(),
            'status'               => 'active',
            'fees_status'          => 'unpaid',
        ]);

        // Unsubscribed member
        $unsubMember = $this->createMember();
        $unsubMember->update(['newsletter_unsubscribed_at' => now()]);

        $newsletter = OrganisationNewsletter::create([
            'organisation_id' => $this->organisation->id,
            'created_by'      => $this->admin->id,
            'subject'         => 'Test',
            'html_content'    => '<p>Hi</p>',
            'status'          => 'draft',
        ]);

        $this->actingAs($this->admin)
             ->patch(route('organisations.membership.newsletters.send', [
                 $this->organisation->slug,
                 $newsletter->id,
             ]));

        // Only 1 recipient should be created (the active non-unsubscribed member)
        $this->assertEquals(1, NewsletterRecipient::where('organisation_newsletter_id', $newsletter->id)->count());
    }

    public function test_bounced_members_excluded_from_recipients_on_dispatch(): void
    {
        $activeUser    = User::factory()->create();
        $activeOrgUser = OrganisationUser::create([
            'organisation_id' => $this->organisation->id,
            'user_id'         => $activeUser->id,
            'role'            => 'member',
            'status'          => 'active',
            'joined_at'       => now(),
        ]);
        Member::create([
            'organisation_id'      => $this->organisation->id,
            'organisation_user_id' => $activeOrgUser->id,
            'membership_number'    => 'M' . uniqid(),
            'status'               => 'active',
            'fees_status'          => 'unpaid',
        ]);

        // Bounced member
        $bouncedMember = $this->createMember();
        $bouncedMember->update(['newsletter_bounced_at' => now()]);

        $newsletter = OrganisationNewsletter::create([
            'organisation_id' => $this->organisation->id,
            'created_by'      => $this->admin->id,
            'subject'         => 'Test',
            'html_content'    => '<p>Hi</p>',
            'status'          => 'draft',
        ]);

        $this->actingAs($this->admin)
             ->patch(route('organisations.membership.newsletters.send', [
                 $this->organisation->slug,
                 $newsletter->id,
             ]));

        $this->assertEquals(1, NewsletterRecipient::where('organisation_newsletter_id', $newsletter->id)->count());
    }

    private function createMember(): Member
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
            'organisation_id'      => $this->organisation->id,
            'organisation_user_id' => $orgUser->id,
            'membership_number'    => 'M' . uniqid(),
            'status'               => 'active',
            'fees_status'          => 'unpaid',
        ]);
    }
}

<?php

namespace Tests\Feature\Membership;

use App\Models\Election;
use App\Models\ElectionOfficer;
use App\Models\Organisation;
use App\Models\OrganisationNewsletter;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * An Election Chief/Deputy may send an election-wide notice to voters
 * using the existing Newsletter system's election audience support
 * (NewsletterService::AUDIENCE_TYPES — election_voters, election_all,
 * etc.), without needing org-admin/owner rights. Non-election audience
 * types (Membership, org-role) remain org-admin/owner-only. Existing
 * org-admin/owner access to every audience type is unchanged.
 */
class ElectionOfficerNewsletterTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private Election $election;
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->org = Organisation::factory()->create(['type' => 'tenant']);
        session(['current_organisation_id' => $this->org->id]);

        $this->election = Election::factory()->create(['organisation_id' => $this->org->id]);

        $this->admin = User::factory()->create(['organisation_id' => $this->org->id, 'email_verified_at' => now()]);
        $this->assignRole($this->admin, $this->org, 'owner');
    }

    private function makeOfficer(Election $election, string $role): User
    {
        $user = User::factory()->create(['organisation_id' => $this->org->id, 'email_verified_at' => now()]);
        ElectionOfficer::create([
            'organisation_id' => $this->org->id,
            'election_id'     => $election->id,
            'user_id'         => $user->id,
            'role'            => $role,
            'status'          => 'active',
        ]);

        return $user;
    }

    private function orgSession(): array
    {
        return ['current_organisation_id' => $this->org->id];
    }

    // ── create() — viewing the compose form ─────────────────────────────

    public function test_election_chief_can_view_the_create_form_for_their_own_election(): void
    {
        $chief = $this->makeOfficer($this->election, 'chief');

        $response = $this->actingAs($chief)
            ->withSession($this->orgSession())
            ->get(route('organisations.membership.newsletters.create', $this->org->slug) . '?election_id=' . $this->election->id);

        $response->assertOk();
    }

    public function test_election_deputy_can_view_the_create_form_for_their_own_election(): void
    {
        $deputy = $this->makeOfficer($this->election, 'deputy');

        $response = $this->actingAs($deputy)
            ->withSession($this->orgSession())
            ->get(route('organisations.membership.newsletters.create', $this->org->slug) . '?election_id=' . $this->election->id);

        $response->assertOk();
    }

    public function test_commissioner_cannot_view_the_create_form(): void
    {
        // manageVoters-equivalent authority is chief/deputy only, matching
        // the rest of this app's election-administration authority model.
        $commissioner = $this->makeOfficer($this->election, 'commissioner');

        $response = $this->actingAs($commissioner)
            ->withSession($this->orgSession())
            ->get(route('organisations.membership.newsletters.create', $this->org->slug) . '?election_id=' . $this->election->id);

        $response->assertStatus(403);
    }

    public function test_officer_of_a_different_election_cannot_view_the_create_form(): void
    {
        $otherElection = Election::factory()->create(['organisation_id' => $this->org->id]);
        $chiefOfOther  = $this->makeOfficer($otherElection, 'chief');

        $response = $this->actingAs($chiefOfOther)
            ->withSession($this->orgSession())
            ->get(route('organisations.membership.newsletters.create', $this->org->slug) . '?election_id=' . $this->election->id);

        $response->assertStatus(403);
    }

    public function test_chief_cannot_open_the_generic_create_form_without_an_election_id(): void
    {
        // The election-scoped entry point must always supply election_id.
        // Without one, a Chief/Deputy must not be able to reach the
        // generic organisation-wide newsletter composer at all.
        $chief = $this->makeOfficer($this->election, 'chief');

        $response = $this->actingAs($chief)
            ->withSession($this->orgSession())
            ->get(route('organisations.membership.newsletters.create', $this->org->slug));

        $response->assertStatus(403);
    }

    // ── store() — creating the draft ────────────────────────────────────

    public function test_election_chief_can_create_an_election_voters_draft_newsletter(): void
    {
        $chief = $this->makeOfficer($this->election, 'chief');

        $response = $this->actingAs($chief)
            ->withSession($this->orgSession())
            ->post(route('organisations.membership.newsletters.store', $this->org->slug), [
                'subject'                    => 'Election update',
                'html_content'               => '<p>Please vote.</p>',
                'audience_type'              => 'election_voters',
                'audience_meta'              => ['election_id' => $this->election->id],
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('organisation_newsletters', [
            'organisation_id' => $this->org->id,
            'created_by'      => $chief->id,
            'audience_type'   => 'election_voters',
        ]);
    }

    public function test_election_chief_cannot_create_a_non_election_audience_newsletter(): void
    {
        $chief = $this->makeOfficer($this->election, 'chief');

        $response = $this->actingAs($chief)
            ->withSession($this->orgSession())
            ->post(route('organisations.membership.newsletters.store', $this->org->slug), [
                'subject'      => 'Everyone',
                'html_content' => '<p>Hi</p>',
                'audience_type' => 'all_members',
            ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('organisation_newsletters', [
            'organisation_id' => $this->org->id,
            'created_by'      => $chief->id,
        ]);
    }

    // ── show()/send() — viewing and dispatching their own draft ─────────

    public function test_election_chief_can_view_and_send_their_own_election_newsletter(): void
    {
        $chief = $this->makeOfficer($this->election, 'chief');

        $this->actingAs($chief)
            ->withSession($this->orgSession())
            ->post(route('organisations.membership.newsletters.store', $this->org->slug), [
                'subject'       => 'Election update',
                'html_content'  => '<p>Please vote.</p>',
                'audience_type' => 'election_voters',
                'audience_meta' => ['election_id' => $this->election->id],
            ]);

        $newsletter = OrganisationNewsletter::where('organisation_id', $this->org->id)->firstOrFail();

        $this->actingAs($chief)
            ->withSession($this->orgSession())
            ->get(route('organisations.membership.newsletters.show', [$this->org->slug, $newsletter->id]))
            ->assertOk();

        // NOTE: asserting authorization only, not full delivery. Actual
        // dispatch hits a pre-existing, unrelated NewsletterService bug —
        // election_voters' audience query joins members on the wrong
        // column ("members.user_id does not exist") — confirmed via a
        // direct, isolated repro with no officer/authorization involved
        // at all. Per this task's explicit boundary not to touch
        // NewsletterService, that defect is left as-is and out of scope
        // here; this test only proves the chief is not blocked by 403.
        $sendResponse = $this->actingAs($chief)
            ->withSession($this->orgSession())
            ->patch(route('organisations.membership.newsletters.send', [$this->org->slug, $newsletter->id]));

        $this->assertNotEquals(403, $sendResponse->status());
    }

    public function test_chief_of_election_a_cannot_view_a_newsletter_belonging_to_election_b(): void
    {
        // Authorization for show()/send() must come from the persisted
        // newsletter's own audience_meta.election_id, never from a
        // client-supplied route/query parameter — otherwise a chief
        // authorized for Election A could act on Election B's newsletter
        // simply by knowing its ID.
        $electionB = Election::factory()->create(['organisation_id' => $this->org->id]);
        $chiefA    = $this->makeOfficer($this->election, 'chief');
        $chiefB    = $this->makeOfficer($electionB, 'chief');

        $this->actingAs($chiefB)
            ->withSession($this->orgSession())
            ->post(route('organisations.membership.newsletters.store', $this->org->slug), [
                'subject'       => 'Election B update',
                'html_content'  => '<p>Please vote in B.</p>',
                'audience_type' => 'election_voters',
                'audience_meta' => ['election_id' => $electionB->id],
            ]);

        $newsletterB = OrganisationNewsletter::where('organisation_id', $this->org->id)
            ->where('audience_type', 'election_voters')
            ->firstOrFail();

        $this->actingAs($chiefA)
            ->withSession($this->orgSession())
            ->get(route('organisations.membership.newsletters.show', [$this->org->slug, $newsletterB->id]))
            ->assertStatus(403);
    }

    public function test_chief_of_election_a_cannot_send_a_newsletter_belonging_to_election_b(): void
    {
        $electionB = Election::factory()->create(['organisation_id' => $this->org->id]);
        $chiefA    = $this->makeOfficer($this->election, 'chief');
        $chiefB    = $this->makeOfficer($electionB, 'chief');

        $this->actingAs($chiefB)
            ->withSession($this->orgSession())
            ->post(route('organisations.membership.newsletters.store', $this->org->slug), [
                'subject'       => 'Election B update',
                'html_content'  => '<p>Please vote in B.</p>',
                'audience_type' => 'election_voters',
                'audience_meta' => ['election_id' => $electionB->id],
            ]);

        $newsletterB = OrganisationNewsletter::where('organisation_id', $this->org->id)
            ->where('audience_type', 'election_voters')
            ->firstOrFail();

        $this->actingAs($chiefA)
            ->withSession($this->orgSession())
            ->patch(route('organisations.membership.newsletters.send', [$this->org->slug, $newsletterB->id]))
            ->assertStatus(403);

        $this->assertEquals('draft', $newsletterB->fresh()->status);
    }

    // ── regression: org admin/owner access is unchanged ─────────────────

    public function test_org_admin_access_to_every_audience_type_is_unchanged(): void
    {
        $response = $this->actingAs($this->admin)
            ->withSession($this->orgSession())
            ->post(route('organisations.membership.newsletters.store', $this->org->slug), [
                'subject'       => 'To everyone',
                'html_content'  => '<p>Hi</p>',
                'audience_type' => 'all_members',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('organisation_newsletters', [
            'organisation_id' => $this->org->id,
            'created_by'      => $this->admin->id,
            'audience_type'   => 'all_members',
        ]);
    }

    public function test_org_admin_can_still_create_election_audience_newsletter(): void
    {
        $response = $this->actingAs($this->admin)
            ->withSession($this->orgSession())
            ->post(route('organisations.membership.newsletters.store', $this->org->slug), [
                'subject'       => 'Election update',
                'html_content'  => '<p>Please vote.</p>',
                'audience_type' => 'election_voters',
                'audience_meta' => ['election_id' => $this->election->id],
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('organisation_newsletters', [
            'organisation_id' => $this->org->id,
            'created_by'      => $this->admin->id,
            'audience_type'   => 'election_voters',
        ]);
    }
}

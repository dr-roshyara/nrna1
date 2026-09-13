<?php

namespace Tests\Feature\Election;

use App\Models\Code;
use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;
use App\Models\Vote;
use App\Models\VoterSlug;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\ElectionScenarioFactory;
use Tests\TestCase;

/**
 * Ballot Preview: a read-only, interactive-but-non-submittable view of the
 * ballot, reachable via a shared (non-personal) link from ReadyForVoting
 * through VotingActive. Governing invariant (plan §Context): observational
 * only — must never create/modify voting-session, verification, draft,
 * code, voter-slug, or vote-persistence state.
 */
class BallotPreviewTest extends TestCase
{
    use RefreshDatabase;

    private function grantOrgRole(Organisation $org, User $user, string $role): void
    {
        // User::factory()->forOrganisation() already creates a 'voter' role row
        // (UserFactory::configure()) — update it rather than inserting a duplicate.
        UserOrganisationRole::updateOrCreate(
            ['user_id' => $user->id, 'organisation_id' => $org->id],
            ['role' => $role]
        );
    }

    private function previewUrl(Organisation $org, Election $election): string
    {
        return route('organisations.elections.ballot-preview', [
            'organisation' => $org->slug,
            'election' => $election->slug,
        ]);
    }

    // ── Reachability by lifecycle state ─────────────────────────────────────

    public function test_ballot_preview_blocked_before_ready_for_voting(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        $election = ElectionScenarioFactory::setupNomination($org);
        $user = User::factory()->forOrganisation($org)->create();
        $this->grantOrgRole($org, $user, 'owner');

        $response = $this->actingAs($user)->get($this->previewUrl($org, $election));

        $response->assertStatus(403);
    }

    public function test_ballot_preview_reachable_during_ready_for_voting(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        $election = ElectionScenarioFactory::configurationComplete($org);
        $user = User::factory()->forOrganisation($org)->create();
        $this->grantOrgRole($org, $user, 'owner');

        $response = $this->actingAs($user)->get($this->previewUrl($org, $election));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Vote/BallotPreview'));
    }

    public function test_ballot_preview_reachable_during_voting_active(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        $election = ElectionScenarioFactory::votingActive($org);
        $user = User::factory()->forOrganisation($org)->create();
        $this->grantOrgRole($org, $user, 'owner');

        $response = $this->actingAs($user)->get($this->previewUrl($org, $election));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Vote/BallotPreview'));
    }

    public function test_ballot_preview_blocked_once_counting_begins(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        $election = ElectionScenarioFactory::votingClosed($org);
        $user = User::factory()->forOrganisation($org)->create();
        $this->grantOrgRole($org, $user, 'owner');

        $response = $this->actingAs($user)->get($this->previewUrl($org, $election));

        $response->assertStatus(403);
    }

    // ── Authorization matrix — exhaustive ────────────────────────────────────

    public function test_ballot_preview_reachable_for_org_owner(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        $election = ElectionScenarioFactory::votingActive($org);
        $user = User::factory()->forOrganisation($org)->create();
        $this->grantOrgRole($org, $user, 'owner');

        $response = $this->actingAs($user)->get($this->previewUrl($org, $election));

        $response->assertStatus(200);
    }

    public function test_ballot_preview_reachable_for_org_admin(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        $election = ElectionScenarioFactory::votingActive($org);
        $user = User::factory()->forOrganisation($org)->create();
        $this->grantOrgRole($org, $user, 'admin');

        $response = $this->actingAs($user)->get($this->previewUrl($org, $election));

        $response->assertStatus(200);
    }

    public function test_ballot_preview_reachable_for_org_commission_role(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        $election = ElectionScenarioFactory::votingActive($org);
        $user = User::factory()->forOrganisation($org)->create();
        $this->grantOrgRole($org, $user, 'commission');

        $response = $this->actingAs($user)->get($this->previewUrl($org, $election));

        $response->assertStatus(200);
    }

    public function test_ballot_preview_reachable_for_active_voter_membership(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        $election = ElectionScenarioFactory::votingActive($org);
        $user = User::factory()->forOrganisation($org)->create();
        // No org role — access comes solely from an active election membership.
        ElectionMembership::create([
            'election_id' => $election->id,
            'organisation_id' => $org->id,
            'user_id' => $user->id,
            'role' => 'voter',
            'status' => 'active',
        ]);

        $response = $this->actingAs($user)->get($this->previewUrl($org, $election));

        $response->assertStatus(200);
    }

    public function test_ballot_preview_blocked_for_removed_or_inactive_membership(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        $election = ElectionScenarioFactory::votingActive($org);
        $user = User::factory()->forOrganisation($org)->create();
        ElectionMembership::create([
            'election_id' => $election->id,
            'organisation_id' => $org->id,
            'user_id' => $user->id,
            'role' => 'voter',
            'status' => 'removed',
        ]);

        $response = $this->actingAs($user)->get($this->previewUrl($org, $election));

        $response->assertStatus(403);
    }

    public function test_ballot_preview_blocked_for_non_member_with_no_org_role(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        $election = ElectionScenarioFactory::votingActive($org);
        $user = User::factory()->forOrganisation($org)->create();
        // No org role, no election membership at all.

        $response = $this->actingAs($user)->get($this->previewUrl($org, $election));

        $response->assertStatus(403);
    }

    // ── Data shape / region behavior ─────────────────────────────────────────

    public function test_ballot_preview_returns_national_and_viewers_region_posts_with_candidates(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        $election = ElectionScenarioFactory::votingActive($org);

        $nationalPost = \App\Models\Post::factory()->create([
            'election_id' => $election->id,
            'organisation_id' => $org->id,
            'name' => 'President',
            'is_national_wide' => true,
            'position_order' => 0,
        ]);
        $nationalCandidateUser = User::factory()->forOrganisation($org)->create(['name' => 'National Candidate']);
        \App\Models\Candidacy::factory()->create([
            'organisation_id' => $org->id,
            'post_id' => $nationalPost->id,
            'user_id' => $nationalCandidateUser->id,
            'status' => 'approved',
            'position_order' => 0,
        ]);

        $regionalPost = \App\Models\Post::factory()->create([
            'election_id' => $election->id,
            'organisation_id' => $org->id,
            'name' => 'Regional Rep',
            'is_national_wide' => false,
            'state_name' => 'Bagmati',
            'position_order' => 1,
        ]);
        $regionalCandidateUser = User::factory()->forOrganisation($org)->create(['name' => 'Regional Candidate']);
        \App\Models\Candidacy::factory()->create([
            'organisation_id' => $org->id,
            'post_id' => $regionalPost->id,
            'user_id' => $regionalCandidateUser->id,
            'status' => 'approved',
            'position_order' => 0,
        ]);

        $viewer = User::factory()->forOrganisation($org)->create(['region' => 'Bagmati']);
        $this->grantOrgRole($org, $viewer, 'owner');

        $response = $this->actingAs($viewer)->get($this->previewUrl($org, $election));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Vote/BallotPreview'));

        // votingActive() itself creates one national post/candidate as part of
        // its own factory contract (EM-VOT-002), with a randomized
        // position_order — assert containment by name, not index, so this
        // test doesn't couple to that fixture's internals or ordering.
        $props = $response->getOriginalContent()->getData()['page']['props'];
        $nationalNames = collect($props['national_posts'])->pluck('name');
        $this->assertContains('President', $nationalNames);
        $president = collect($props['national_posts'])->firstWhere('name', 'President');
        $this->assertSame('National Candidate', $president['candidates'][0]['user']['name']);

        $this->assertCount(1, $props['regional_posts']);
        $this->assertSame('Regional Rep', $props['regional_posts'][0]['name']);
        $this->assertSame('Regional Candidate', $props['regional_posts'][0]['candidates'][0]['user']['name']);
    }

    public function test_ballot_preview_renders_without_regional_posts_for_voter_with_no_region(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        $election = ElectionScenarioFactory::votingActive($org);

        \App\Models\Post::factory()->create([
            'election_id' => $election->id,
            'organisation_id' => $org->id,
            'is_national_wide' => false,
            'state_name' => 'Bagmati',
        ]);

        $viewer = User::factory()->forOrganisation($org)->create(['region' => null]);
        $this->grantOrgRole($org, $viewer, 'owner');

        $response = $this->actingAs($viewer)->get($this->previewUrl($org, $election));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->has('regional_posts', 0)
            ->where('election.has_regional_posts', true)
        );
    }

    public function test_ballot_preview_returns_404_for_demo_election(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        $election = ElectionScenarioFactory::votingActive($org);
        $election->update(['type' => 'demo']);
        $user = User::factory()->forOrganisation($org)->create();
        $this->grantOrgRole($org, $user, 'owner');

        $response = $this->actingAs($user)->get($this->previewUrl($org, $election));

        $response->assertStatus(404);
    }

    // ── Side-effect safety and submission-impossibility ──────────────────────

    public function test_ballot_preview_does_not_create_voter_slug_or_code_rows(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        $election = ElectionScenarioFactory::votingActive($org);
        $user = User::factory()->forOrganisation($org)->create();
        $this->grantOrgRole($org, $user, 'owner');

        $before = [
            'voter_slugs' => VoterSlug::count(),
            'codes' => Code::count(),
            'votes' => Vote::count(),
        ];

        $response = $this->actingAs($user)->get($this->previewUrl($org, $election));
        $response->assertStatus(200);

        $this->assertSame($before, [
            'voter_slugs' => VoterSlug::count(),
            'codes' => Code::count(),
            'votes' => Vote::count(),
        ]);
    }

    public function test_ballot_preview_get_is_idempotent_and_side_effect_free(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        $election = ElectionScenarioFactory::votingActive($org);
        $user = User::factory()->forOrganisation($org)->create();
        $this->grantOrgRole($org, $user, 'owner');

        $before = [
            'voter_slugs' => VoterSlug::count(),
            'codes' => Code::count(),
            'votes' => Vote::count(),
        ];

        for ($i = 0; $i < 3; $i++) {
            $this->actingAs($user)->get($this->previewUrl($org, $election))->assertStatus(200);
        }

        $this->assertSame($before, [
            'voter_slugs' => VoterSlug::count(),
            'codes' => Code::count(),
            'votes' => Vote::count(),
        ]);
    }

    public function test_preview_viewer_without_voter_slug_cannot_submit_a_vote_via_direct_post(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        $election = ElectionScenarioFactory::votingActive($org);
        $user = User::factory()->forOrganisation($org)->create();
        $this->grantOrgRole($org, $user, 'owner');

        // Viewer only ever GETs the preview — no VoterSlug is ever created for them.
        $this->actingAs($user)->get($this->previewUrl($org, $election))->assertStatus(200);
        $this->assertSame(0, VoterSlug::where('user_id', $user->id)->count());

        // A direct POST to the real vote-submission endpoint, using a slug value
        // that was never issued to this user, must be rejected before it can
        // reach any vote-persistence logic.
        $response = $this->actingAs($user)
            ->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class)
            ->post(route('slug.vote.submit', ['vslug' => 'nonexistent-preview-viewer-slug']));

        $this->assertNotEquals(200, $response->getStatusCode());
        $this->assertSame(0, Vote::count());
    }
}

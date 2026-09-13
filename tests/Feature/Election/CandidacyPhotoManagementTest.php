<?php

namespace Tests\Feature\Election;

use App\Models\Candidacy;
use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\ElectionOfficer;
use App\Models\Organisation;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\Support\ElectionScenarioFactory;
use Tests\TestCase;

/**
 * Candidate photo editing: only an active ElectionOfficer with role chief or
 * deputy (the existing ElectionPolicy::managePosts() boundary — commissioner
 * is view-only, not management) may replace a candidate's photo, and only
 * before voting starts. See .claude/plans/encapsulated-hopping-hamster.md for
 * the full behavioral contract and the one-sentence authorization rule.
 */
class CandidacyPhotoManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);
    }

    /**
     * A pre-voting (ReadyForVoting) election with one post + one candidacy
     * carrying a known, pre-existing image_path_1 file on the fake public disk.
     *
     * @return array{0: Election, 1: Organisation, 2: Candidacy, 3: string} [election, org, candidacy, oldPhotoPath]
     */
    private function buildReadyForVotingElectionWithCandidacy(?Organisation $org = null): array
    {
        $org = $org ?? Organisation::factory()->create(['type' => 'tenant']);
        $election = ElectionScenarioFactory::configurationComplete($org);

        $post = Post::factory()->create([
            'election_id' => $election->id,
            'organisation_id' => $org->id,
            'name' => 'President',
            'is_national_wide' => true,
        ]);
        $candidateUser = User::factory()->forOrganisation($org)->create(['name' => 'Jane Candidate']);

        $oldPhotoPath = "candidacies/{$org->id}/old-photo.jpg";
        Storage::disk('public')->put($oldPhotoPath, 'old-photo-content');

        $candidacy = Candidacy::factory()->create([
            'organisation_id' => $org->id,
            'post_id' => $post->id,
            'user_id' => $candidateUser->id,
            'status' => 'approved',
            'image_path_1' => $oldPhotoPath,
        ]);

        return [$election, $org, $candidacy, $oldPhotoPath];
    }

    private function appointOfficer(Organisation $org, Election $election, string $role): User
    {
        $officer = User::factory()->forOrganisation($org)->create();
        ElectionOfficer::create([
            'user_id' => $officer->id,
            'election_id' => $election->id,
            'organisation_id' => $org->id,
            'role' => $role,
            'status' => 'active',
            'appointed_by' => $officer->id,
            'appointed_at' => now(),
            'accepted_at' => now(),
        ]);
        // ChecksElectionAccess::canAccessElection() (used by the read-only
        // /candidates page) checks org-level role or ElectionMembership — it
        // does not know about ElectionOfficer at all. A real committee officer
        // is also typically a member; give them one so the "candidates page"
        // exposure tests can reach the page at all (an existing, separate,
        // pre-existing gap in that read-only page's access check — not this
        // feature's concern to fix).
        ElectionMembership::create([
            'election_id' => $election->id,
            'organisation_id' => $org->id,
            'user_id' => $officer->id,
            'role' => 'voter',
            'status' => 'active',
        ]);

        return $officer;
    }

    private function updatePhotoUrl(Organisation $org, Election $election, Candidacy $candidacy): string
    {
        return route('organisations.elections.candidates.update-photo', [
            'organisation' => $org->slug,
            'election' => $election->slug,
            'candidacy' => $candidacy->id,
        ]);
    }

    // ── Authorization ─────────────────────────────────────────────────────────

    public function test_chief_can_update_candidate_photo_before_voting_starts(): void
    {
        [$election, $org, $candidacy] = $this->buildReadyForVotingElectionWithCandidacy();
        $chief = $this->appointOfficer($org, $election, 'chief');

        $response = $this->actingAs($chief)
            ->withSession(['current_organisation_id' => $org->id])
            ->patch($this->updatePhotoUrl($org, $election, $candidacy), [
                'photo' => UploadedFile::fake()->image('new.jpg', 400, 400),
            ]);

        $response->assertRedirect();
        $this->assertNotEquals('candidacies/' . $org->id . '/old-photo.jpg', $candidacy->fresh()->image_path_1);
    }

    public function test_deputy_can_update_candidate_photo_before_voting_starts(): void
    {
        [$election, $org, $candidacy] = $this->buildReadyForVotingElectionWithCandidacy();
        $deputy = $this->appointOfficer($org, $election, 'deputy');

        $response = $this->actingAs($deputy)
            ->withSession(['current_organisation_id' => $org->id])
            ->patch($this->updatePhotoUrl($org, $election, $candidacy), [
                'photo' => UploadedFile::fake()->image('new.jpg', 400, 400),
            ]);

        $response->assertRedirect();
    }

    public function test_commissioner_cannot_update_candidate_photo(): void
    {
        [$election, $org, $candidacy, $oldPhotoPath] = $this->buildReadyForVotingElectionWithCandidacy();
        $commissioner = $this->appointOfficer($org, $election, 'commissioner');

        $response = $this->actingAs($commissioner)
            ->withSession(['current_organisation_id' => $org->id])
            ->patch($this->updatePhotoUrl($org, $election, $candidacy), [
                'photo' => UploadedFile::fake()->image('new.jpg', 400, 400),
            ]);

        $response->assertStatus(403);
        $this->assertSame($oldPhotoPath, $candidacy->fresh()->image_path_1);
    }

    public function test_ordinary_voter_cannot_update_candidate_photo(): void
    {
        [$election, $org, $candidacy, $oldPhotoPath] = $this->buildReadyForVotingElectionWithCandidacy();
        $voter = User::factory()->forOrganisation($org)->create();
        ElectionMembership::create([
            'election_id' => $election->id,
            'organisation_id' => $org->id,
            'user_id' => $voter->id,
            'role' => 'voter',
            'status' => 'active',
        ]);

        $response = $this->actingAs($voter)
            ->withSession(['current_organisation_id' => $org->id])
            ->patch($this->updatePhotoUrl($org, $election, $candidacy), [
                'photo' => UploadedFile::fake()->image('new.jpg', 400, 400),
            ]);

        $response->assertStatus(403);
        $this->assertSame($oldPhotoPath, $candidacy->fresh()->image_path_1);
    }

    public function test_officer_from_different_organisation_cannot_update_candidate_photo(): void
    {
        [$election, $org, $candidacy, $oldPhotoPath] = $this->buildReadyForVotingElectionWithCandidacy();

        $otherOrg = Organisation::factory()->create(['type' => 'tenant']);
        $otherElection = ElectionScenarioFactory::configurationComplete($otherOrg);
        $foreignChief = $this->appointOfficer($otherOrg, $otherElection, 'chief');

        $response = $this->actingAs($foreignChief)
            ->withSession(['current_organisation_id' => $org->id])
            ->patch($this->updatePhotoUrl($org, $election, $candidacy), [
                'photo' => UploadedFile::fake()->image('new.jpg', 400, 400),
            ]);

        $this->assertNotEquals(200, $response->getStatusCode());
        $this->assertSame($oldPhotoPath, $candidacy->fresh()->image_path_1);
    }

    public function test_committee_manager_cannot_update_candidacy_belonging_to_another_election(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);

        // Election A: the chief's own election, named in the URL.
        [$electionA, , ] = $this->buildReadyForVotingElectionWithCandidacy($org);
        $chiefOfA = $this->appointOfficer($org, $electionA, 'chief');

        // Election B: a second election in the SAME organisation, with its own
        // candidacy — the chief of A is not an officer of B at all.
        [$electionB, , $candidacyOfB, $oldPhotoPath] = $this->buildReadyForVotingElectionWithCandidacy($org);

        // URL names election A (where this user IS chief); the candidacy in
        // the URL actually belongs to election B.
        $response = $this->actingAs($chiefOfA)
            ->withSession(['current_organisation_id' => $org->id])
            ->patch($this->updatePhotoUrl($org, $electionA, $candidacyOfB), [
                'photo' => UploadedFile::fake()->image('new.jpg', 400, 400),
            ]);

        $response->assertStatus(403);
        $this->assertSame($oldPhotoPath, $candidacyOfB->fresh()->image_path_1);
    }

    // ── Lifecycle ─────────────────────────────────────────────────────────────

    public function test_photo_update_blocked_once_voting_active(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        $election = ElectionScenarioFactory::votingActive($org);
        $chief = $this->appointOfficer($org, $election, 'chief');

        $post = Post::factory()->create([
            'election_id' => $election->id,
            'organisation_id' => $org->id,
            'is_national_wide' => true,
        ]);
        $candidateUser = User::factory()->forOrganisation($org)->create();
        $oldPhotoPath = "candidacies/{$org->id}/old-photo.jpg";
        Storage::disk('public')->put($oldPhotoPath, 'old-photo-content');
        $candidacy = Candidacy::factory()->create([
            'organisation_id' => $org->id,
            'post_id' => $post->id,
            'user_id' => $candidateUser->id,
            'status' => 'approved',
            'image_path_1' => $oldPhotoPath,
        ]);

        $response = $this->actingAs($chief)
            ->withSession(['current_organisation_id' => $org->id])
            ->patch($this->updatePhotoUrl($org, $election, $candidacy), [
                'photo' => UploadedFile::fake()->image('new.jpg', 400, 400),
            ]);

        $response->assertStatus(403);
        $this->assertSame($oldPhotoPath, $candidacy->fresh()->image_path_1);
    }

    public function test_photo_update_blocked_during_counting(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        $election = ElectionScenarioFactory::votingClosed($org);
        $chief = $this->appointOfficer($org, $election, 'chief');

        $post = Post::factory()->create(['election_id' => $election->id, 'organisation_id' => $org->id]);
        $candidacy = Candidacy::factory()->create([
            'organisation_id' => $org->id,
            'post_id' => $post->id,
            'user_id' => User::factory()->forOrganisation($org)->create()->id,
        ]);

        $response = $this->actingAs($chief)
            ->withSession(['current_organisation_id' => $org->id])
            ->patch($this->updatePhotoUrl($org, $election, $candidacy), [
                'photo' => UploadedFile::fake()->image('new.jpg', 400, 400),
            ]);

        $response->assertStatus(403);
    }

    public function test_photo_update_blocked_once_results_published(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        $election = ElectionScenarioFactory::resultsPublished($org);
        $chief = $this->appointOfficer($org, $election, 'chief');

        $post = Post::factory()->create(['election_id' => $election->id, 'organisation_id' => $org->id]);
        $candidacy = Candidacy::factory()->create([
            'organisation_id' => $org->id,
            'post_id' => $post->id,
            'user_id' => User::factory()->forOrganisation($org)->create()->id,
        ]);

        $response = $this->actingAs($chief)
            ->withSession(['current_organisation_id' => $org->id])
            ->patch($this->updatePhotoUrl($org, $election, $candidacy), [
                'photo' => UploadedFile::fake()->image('new.jpg', 400, 400),
            ]);

        $response->assertStatus(403);
    }

    public function test_photo_update_blocked_while_suspended(): void
    {
        [$election, $org, $candidacy, $oldPhotoPath] = $this->buildReadyForVotingElectionWithCandidacy();
        $chief = $this->appointOfficer($org, $election, 'chief');
        $election->update(['suspended_at' => now()]);

        $response = $this->actingAs($chief)
            ->withSession(['current_organisation_id' => $org->id])
            ->patch($this->updatePhotoUrl($org, $election, $candidacy), [
                'photo' => UploadedFile::fake()->image('new.jpg', 400, 400),
            ]);

        $response->assertStatus(403);
        $this->assertSame($oldPhotoPath, $candidacy->fresh()->image_path_1);
    }

    // ── Election type ─────────────────────────────────────────────────────────

    public function test_photo_update_returns_404_for_demo_election(): void
    {
        [$election, $org, $candidacy] = $this->buildReadyForVotingElectionWithCandidacy();
        $chief = $this->appointOfficer($org, $election, 'chief');
        // Flip to demo AFTER the ReadyForVoting-satisfying facts are already
        // set, so the lifecycle-based route middleware still lets the request
        // through and the controller's own demo check is what's being proven
        // (same technique as BallotPreviewTest's demo-election test).
        $election->update(['type' => 'demo']);

        $response = $this->actingAs($chief)
            ->withSession(['current_organisation_id' => $org->id])
            ->patch($this->updatePhotoUrl($org, $election, $candidacy), [
                'photo' => UploadedFile::fake()->image('new.jpg', 400, 400),
            ]);

        $response->assertStatus(404);
    }

    // ── Photo persistence / ordering ────────────────────────────────────────

    public function test_new_photo_is_stored_and_image_path_1_is_updated(): void
    {
        [$election, $org, $candidacy] = $this->buildReadyForVotingElectionWithCandidacy();
        $chief = $this->appointOfficer($org, $election, 'chief');

        $this->actingAs($chief)
            ->withSession(['current_organisation_id' => $org->id])
            ->patch($this->updatePhotoUrl($org, $election, $candidacy), [
                'photo' => UploadedFile::fake()->image('new.jpg', 400, 400),
            ]);

        $newPath = $candidacy->fresh()->image_path_1;
        $this->assertNotNull($newPath);
        $this->assertNotSame('candidacies/' . $org->id . '/old-photo.jpg', $newPath);
        Storage::disk('public')->assertExists($newPath);
    }

    public function test_old_photo_is_deleted_only_after_successful_replacement(): void
    {
        [$election, $org, $candidacy, $oldPhotoPath] = $this->buildReadyForVotingElectionWithCandidacy();
        $chief = $this->appointOfficer($org, $election, 'chief');

        Storage::disk('public')->assertExists($oldPhotoPath);

        $this->actingAs($chief)
            ->withSession(['current_organisation_id' => $org->id])
            ->patch($this->updatePhotoUrl($org, $election, $candidacy), [
                'photo' => UploadedFile::fake()->image('new.jpg', 400, 400),
            ]);

        $newPath = $candidacy->fresh()->image_path_1;
        Storage::disk('public')->assertExists($newPath);
        Storage::disk('public')->assertMissing($oldPhotoPath);
    }

    public function test_original_photo_is_preserved_when_upload_is_invalid(): void
    {
        [$election, $org, $candidacy, $oldPhotoPath] = $this->buildReadyForVotingElectionWithCandidacy();
        $chief = $this->appointOfficer($org, $election, 'chief');

        $response = $this->actingAs($chief)
            ->withSession(['current_organisation_id' => $org->id])
            ->patch($this->updatePhotoUrl($org, $election, $candidacy), [
                'photo' => UploadedFile::fake()->create('document.pdf', 100),
            ]);

        $response->assertSessionHasErrors('photo');
        $this->assertSame($oldPhotoPath, $candidacy->fresh()->image_path_1);
        Storage::disk('public')->assertExists($oldPhotoPath);
    }

    public function test_non_image_upload_is_rejected(): void
    {
        [$election, $org, $candidacy] = $this->buildReadyForVotingElectionWithCandidacy();
        $chief = $this->appointOfficer($org, $election, 'chief');

        $response = $this->actingAs($chief)
            ->withSession(['current_organisation_id' => $org->id])
            ->patch($this->updatePhotoUrl($org, $election, $candidacy), [
                'photo' => UploadedFile::fake()->create('document.pdf', 100),
            ]);

        $response->assertSessionHasErrors('photo');
    }

    // ── Candidates page prop exposure ────────────────────────────────────────

    public function test_candidates_page_exposes_can_edit_true_for_committee_manager_before_voting(): void
    {
        [$election, $org] = $this->buildReadyForVotingElectionWithCandidacy();
        $chief = $this->appointOfficer($org, $election, 'chief');

        $response = $this->actingAs($chief)
            ->withSession(['current_organisation_id' => $org->id])
            ->get(route('organisations.elections.candidates', [
                'organisation' => $org->slug,
                'election' => $election->slug,
            ]));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Organisations/Candidates')
            ->where('can_edit_candidate_photos', true)
        );
    }

    public function test_candidates_page_exposes_can_edit_false_for_ordinary_voter(): void
    {
        [$election, $org] = $this->buildReadyForVotingElectionWithCandidacy();
        $voter = User::factory()->forOrganisation($org)->create();
        ElectionMembership::create([
            'election_id' => $election->id,
            'organisation_id' => $org->id,
            'user_id' => $voter->id,
            'role' => 'voter',
            'status' => 'active',
        ]);

        $response = $this->actingAs($voter)
            ->withSession(['current_organisation_id' => $org->id])
            ->get(route('organisations.elections.candidates', [
                'organisation' => $org->slug,
                'election' => $election->slug,
            ]));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->where('can_edit_candidate_photos', false)
        );
    }

    public function test_candidates_page_exposes_can_edit_false_once_voting_active(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        $election = ElectionScenarioFactory::votingActive($org);
        $chief = $this->appointOfficer($org, $election, 'chief');

        $response = $this->actingAs($chief)
            ->withSession(['current_organisation_id' => $org->id])
            ->get(route('organisations.elections.candidates', [
                'organisation' => $org->slug,
                'election' => $election->slug,
            ]));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->where('can_edit_candidate_photos', false)
        );
    }
}

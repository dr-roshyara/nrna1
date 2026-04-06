<?php

namespace Tests\Feature\Demo;

use Tests\TestCase;
use App\Models\User;
use App\Models\Election;
use App\Models\DemoPost;
use App\Models\DemoCandidacy;
use App\Models\DemoCode;
use App\Models\VoterSlug;
use App\Models\Organisation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

class DemoVoteControllerCreateTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Election $election;
    protected VoterSlug $slug;
    protected DemoPost $nationalPost;
    protected DemoPost $regionalPost;
    protected DemoCandidacy $candidate1;
    protected DemoCandidacy $candidate2;
    protected Organisation $organisation;

    protected function setUp(): void
    {
        parent::setUp();

        $this->organisation = Organisation::factory()->create(['type' => 'tenant']);
        session(['current_organisation_id' => $this->organisation->id]);

        $this->user = User::factory()->create(['region' => 'Test Region', 'email_verified_at' => now()]);
        $this->election = Election::factory()->create(['type' => 'demo', 'status' => 'active', 'organisation_id' => $this->organisation->id]);

        // ✅ FIX: Hard-delete any existing slugs for this user/election combination to avoid unique constraint violations
        VoterSlug::where('user_id', $this->user->id)
            ->where('election_id', $this->election->id)
            ->delete();

        DemoCode::factory()->create([
            'user_id' => $this->user->id,
            'election_id' => $this->election->id,
            'organisation_id' => $this->organisation->id,
            'has_agreed_to_vote' => true,
            'can_vote_now' => true,
        ]);

        $this->slug = VoterSlug::factory()->create([
            'user_id' => $this->user->id,
            'election_id' => $this->election->id,
            'organisation_id' => $this->organisation->id,
            'is_active' => true,
            'can_vote_now' => true,
            'status' => 'active',
        ]);

        $this->nationalPost = DemoPost::factory()->create([
            'election_id' => $this->election->id,
            'is_national_wide' => 1,
            'name' => 'President',
            'position_order' => 1,
            'required_number' => 1,
            'organisation_id' => $this->organisation->id,
        ]);

        $this->regionalPost = DemoPost::factory()->create([
            'election_id' => $this->election->id,
            'is_national_wide' => 0,
            'state_name' => 'Test Region',
            'name' => 'Regional Representative',
            'position_order' => 2,
            'required_number' => 1,
            'organisation_id' => $this->organisation->id,
        ]);

        // Create candidate users with proper names
        $candidateUser1 = User::factory()->create(['name' => 'Candidate 1', 'region' => 'Test Region', 'email_verified_at' => now()]);
        $candidateUser2 = User::factory()->create(['name' => 'Candidate 2', 'region' => 'Test Region', 'email_verified_at' => now()]);
        $regionalCandidateUser = User::factory()->create(['name' => 'Regional Candidate', 'region' => 'Test Region', 'email_verified_at' => now()]);

        // ✅ Create candidacies WITHOUT factory to ensure user_id override works
        $this->candidate1 = DemoCandidacy::create([
            'election_id' => $this->election->id,
            'organisation_id' => $this->organisation->id,
            'post_id' => $this->nationalPost->id,
            'user_id' => $candidateUser1->id,
            'name' => 'Demo Candidate 1',
            'candidacy_name' => 'Demo Candidate 1',
            'position_order' => 1,
        ]);

        $this->candidate2 = DemoCandidacy::create([
            'election_id' => $this->election->id,
            'organisation_id' => $this->organisation->id,
            'post_id' => $this->nationalPost->id,
            'user_id' => $candidateUser2->id,
            'name' => 'Demo Candidate 2',
            'candidacy_name' => 'Demo Candidate 2',
            'position_order' => 2,
        ]);

        DemoCandidacy::create([
            'election_id' => $this->election->id,
            'organisation_id' => $this->organisation->id,
            'post_id' => $this->regionalPost->id,
            'user_id' => $regionalCandidateUser->id,
            'name' => 'Regional Candidate 1',
            'candidacy_name' => 'Regional Candidate 1',
            'position_order' => 1,
        ]);
    }

    /**
     * RED TEST 1: Posts should be grouped into national/regional structure
     */
    public function test_posts_grouped_into_national_regional()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('slug.demo-vote.create', ['vslug' => $this->slug->slug]));

        $response->assertInertia(fn (Assert $page) => $page
            ->component('Vote/DemoVote/Create')
            ->has('posts', fn (Assert $posts) => $posts
                ->has('national')
                ->has('regional')
            )
        );
    }

    /**
     * RED TEST 2: National posts have correct data structure with candidates
     */
    public function test_national_posts_have_correct_structure()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('slug.demo-vote.create', ['vslug' => $this->slug->slug]));

        $response->assertInertia(fn (Assert $page) => $page
            ->where('posts.national.0.id', $this->nationalPost->id)
            ->where('posts.national.0.name', 'President')
            ->where('posts.national.0.required_number', 1)
            ->has('posts.national.0.candidates', 2)
        );
    }

    /**
     * RED TEST 3: Regional posts are filtered by user's region
     */
    public function test_regional_posts_filtered_by_user_region()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('slug.demo-vote.create', ['vslug' => $this->slug->slug]));

        $response->assertInertia(fn (Assert $page) => $page
            ->where('posts.regional.0.id', $this->regionalPost->id)
            ->where('posts.regional.0.name', 'Regional Representative')
            ->has('posts.regional.0.candidates', 1)
        );
    }

    /**
     * RED TEST 4: Candidates are ordered by position_order
     */
    public function test_candidates_ordered_by_position_order()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('slug.demo-vote.create', ['vslug' => $this->slug->slug]));

        $response->assertInertia(fn (Assert $page) => $page
            ->where('posts.national.0.candidates.0.id', $this->candidate1->id)
            ->where('posts.national.0.candidates.0.user_name', 'Candidate 1')
            ->where('posts.national.0.candidates.1.id', $this->candidate2->id)
            ->where('posts.national.0.candidates.1.user_name', 'Candidate 2')
        );
    }

    /**
     * RED TEST 5: Candidate has required fields
     */
    public function test_candidate_has_required_fields()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('slug.demo-vote.create', ['vslug' => $this->slug->slug]));

        $response->assertInertia(fn (Assert $page) => $page
            ->where('posts.national.0.candidates.0.id', $this->candidate1->id)
            ->where('posts.national.0.candidates.0.candidacy_id', $this->candidate1->id)
            ->where('posts.national.0.candidates.0.user_name', 'Candidate 1')
            ->where('posts.national.0.candidates.0.post_id', $this->candidate1->post_id)
        );
    }

    /**
     * RED TEST 6: No regional posts when user has no region
     */
    public function test_no_regional_posts_when_user_has_no_region()
    {
        $userWithoutRegion = User::factory()->create(['region' => 'Other Region', 'email_verified_at' => now()]);

        // ✅ FIX: Delete any existing slugs to avoid unique constraint violations
        VoterSlug::where('user_id', $userWithoutRegion->id)
            ->where('election_id', $this->election->id)
            ->delete();

        DemoCode::factory()->create([
            'user_id' => $userWithoutRegion->id,
            'election_id' => $this->election->id,
            'has_agreed_to_vote' => true,
            'can_vote_now' => true,
        ]);

        $slug = VoterSlug::factory()->create([
            'user_id' => $userWithoutRegion->id,
            'election_id' => $this->election->id,
            'is_active' => true,
            'can_vote_now' => true,
            'status' => 'active',
        ]);

        $this->actingAs($userWithoutRegion);

        $response = $this->get(route('slug.demo-vote.create', ['vslug' => $slug->slug]));

        $response->assertInertia(fn (Assert $page) => $page
            ->where('posts.regional', [])
        );
    }

    /**
     * RED TEST 7 (NEW): Kandidatenname kommt aus Kandidat, nicht vom Login-User
     *
     * Reproduziert den Bug: Alle Kandidaten zeigen den Login-User-Namen statt ihren eigenen
     * Root Cause: user() Relationship mit ->select() bricht Eager Loading
     */
    public function test_kandidatenname_kommt_aus_kandidat_nicht_vom_login_user(): void
    {
        // Precondition: Login-User hat anderen Namen als Kandidat 1
        $this->assertNotEquals('Candidate 1', $this->user->name,
            'Precondition: Login-User und Kandidat 1 müssen unterschiedliche Namen haben');

        $this->actingAs($this->user);

        $response = $this->get(route('slug.demo-vote.create', ['vslug' => $this->slug->slug]));

        // Assert: user_name muss "Candidate 1" sein (vom Kandidaten-User)
        // NICHT der Name des eingeloggten Users
        $response->assertInertia(fn (Assert $page) => $page
            ->where('posts.national.0.candidates.0.user_name', 'Candidate 1')
            ->where('posts.national.0.candidates.1.user_name', 'Candidate 2')
        );
    }

    /**
     * RED TEST 8 (NEW): Regionale Posts zeigen korrekten Kandidatennamen
     *
     * Reproduziert den regionalen Bug: Fehlt $c->user_name ?? in der Fallback-Kette
     */
    public function test_regionale_posts_zeigen_korrekten_kandidatennamen(): void
    {
        $this->actingAs($this->user);

        $response = $this->get(route('slug.demo-vote.create', ['vslug' => $this->slug->slug]));

        // Assert: Regionaler Kandidat zeigt seinen Namen "Regional Candidate"
        $response->assertInertia(fn (Assert $page) => $page
            ->where('posts.regional.0.candidates.0.user_name', 'Regional Candidate')
        );
    }

    /**
     * RED TEST 9 (NEW): Kandidat ohne user_id benutzt name-Spalte als Fallback
     *
     * Reproduziert Produktionsfall: Demo-Kandidaten haben user_id = NULL und name = "Mary Meyer"
     * Controller muss auf $c->name fallen wenn $c->user NULL ist
     */
    public function test_kandidat_ohne_user_id_benutzt_name_als_fallback(): void
    {
        // Arrange: Erstelle Kandidat ohne user_id, aber mit name-Feld
        $candidatOhneUser = DemoCandidacy::factory()->create([
            'post_id'        => $this->nationalPost->id,
            'user_id'        => null,
            'name'           => 'Mary Meyer – For a Stronger Future',
            'position_order' => 10,
        ]);

        $this->actingAs($this->user);

        $response = $this->get(route('slug.demo-vote.create', ['vslug' => $this->slug->slug]));

        // Assert: Kandidat ohne user_id zeigt seinen name-Wert
        // Da position_order=10 ist er letzter in der Liste (neben den beiden Kandidaten 1 und 2)
        $response->assertInertia(fn (Assert $page) => $page
            ->has('posts.national.0.candidates', 3) // 2 bestehende + 1 neuer
            ->where('posts.national.0.candidates.2.user_name', 'Mary Meyer – For a Stronger Future')
        );
    }
}

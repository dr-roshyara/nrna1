<?php

namespace Tests\Feature\Voting;

use App\Exceptions\Voting\ExpiredVoterSlugException;
use App\Exceptions\Voting\InvalidVoterSlugException;
use App\Exceptions\Voting\OrganisationMismatchException;
use App\Exceptions\Voting\SlugOwnershipException;
use App\Models\Election;
use App\Models\Organisation;
use App\Models\User;
use App\Models\VoterSlug;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * PBDIGIT-273: VerifyVoterSlug on the real {vslug} request path (binding → middleware chain).
 *
 * STATUS: written, NOT run. It needs a dedicated test database (Human PO decision 2026-10-11; the shared
 * nrna_test is wiped by RefreshDatabase). Run it only through a bootstrap that pins that database.
 */
class VerifyVoterSlugRequestPathTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private User $voter;
    private Election $election;

    protected function setUp(): void
    {
        parent::setUp();
        Carbon::setTestNow(Carbon::parse('2026-10-11 12:00:00', 'UTC'));
        RateLimiter::clear('slug-lookup:127.0.0.1');

        $this->org = Organisation::factory()->tenant()->create();
        $this->voter = User::factory()->forOrganisation($this->org)->create();
        $this->election = Election::factory()->forOrganisation($this->org)->real()->create();
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    private function slugFor(User $user, array $overrides = []): VoterSlug
    {
        return VoterSlug::create(array_merge([
            'user_id' => $user->id,
            'election_id' => $this->election->id,
            'organisation_id' => $this->org->id,
            'slug' => Str::random(32),
            'is_active' => true,
            'status' => 'active',
            'expires_at' => now()->addMinutes(30),
        ], $overrides));
    }

    private function codeCreate(VoterSlug $slug, string $route = 'slug.code.create')
    {
        return $this->getJson(route($route, ['vslug' => $slug->slug]));
    }

    public function test_a_guest_is_rejected_on_step_one(): void
    {
        $this->codeCreate($this->slugFor($this->voter))->assertStatus(401);
    }

    public function test_a_slug_exactly_at_its_deadline_is_rejected_by_the_middleware(): void
    {
        // The binding still admits it (isPast is strict); VerifyVoterSlug must reject now >= expires_at.
        $slug = $this->slugFor($this->voter, ['expires_at' => now()->addMinutes(30)]);
        Carbon::setTestNow(now()->addMinutes(30));

        $this->actingAs($this->voter)->codeCreate($slug)
            ->assertStatus(403)->assertJson(['code' => ExpiredVoterSlugException::class]);
    }

    public function test_another_voters_slug_is_rejected(): void
    {
        $other = User::factory()->forOrganisation($this->org)->create();

        $this->actingAs($other)->codeCreate($this->slugFor($this->voter))
            ->assertStatus(403)->assertJson(['code' => SlugOwnershipException::class]);
    }

    public function test_a_revoked_slug_is_rejected_even_though_lookups_bypass_global_scopes(): void
    {
        $slug = $this->slugFor($this->voter);
        $slug->delete(); // soft delete; the binding's withoutGlobalScopes() still resolves it

        $this->actingAs($this->voter)->codeCreate($slug)
            ->assertStatus(404)->assertJson(['code' => InvalidVoterSlugException::class]);
    }

    public function test_a_slug_whose_election_belongs_to_another_organisation_is_rejected(): void
    {
        $otherOrg = Organisation::factory()->tenant()->create();
        $slug = $this->slugFor($this->voter, ['organisation_id' => $otherOrg->id]);

        $this->actingAs($this->voter)->codeCreate($slug)
            ->assertJson(['code' => OrganisationMismatchException::class]);
    }

    public function test_a_real_slug_is_not_found_on_a_demo_route(): void
    {
        $this->actingAs($this->voter)->codeCreate($this->slugFor($this->voter), 'slug.demo-code.create')
            ->assertStatus(404);
    }

    public function test_a_valid_slug_passes_verifyvoterslug_on_step_one(): void
    {
        $response = $this->actingAs($this->voter)->codeCreate($this->slugFor($this->voter));

        // Later middleware (membership, eligibility) may still answer; VerifyVoterSlug itself must not reject.
        $this->assertNotContains($response->json('code'), [
            ExpiredVoterSlugException::class, InvalidVoterSlugException::class,
            SlugOwnershipException::class, OrganisationMismatchException::class,
        ]);
        $this->assertNotSame(401, $response->status());
    }
}

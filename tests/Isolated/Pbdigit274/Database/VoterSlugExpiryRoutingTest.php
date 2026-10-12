<?php

declare(strict_types=1);

namespace Tests\Isolated\Pbdigit274\Database;

use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\Isolated\Pbdigit274\IsolatedCommittedDatabaseTestCase;

/**
 * PBDIGIT-274 S7: what a voter sees at a voting step (plan section 7; PO decisions 2026-10-11 and O-5 =
 * "plain not found", 2026-10-13).
 * - The OWNER of an expired link is sent to the election's start page with "Your voting session has expired,
 *   please start again" (the organisation page if the election cannot be determined). JSON keeps 403.
 * - Someone else's link answers exactly like an unknown link: 404 "Voting link not found.".
 * - A closed election shows "voting is closed" and offers no restart (O-2), instead of "session expired".
 * - None of this writes to the voter slug.
 *
 * Isolated database only; committed data, removed afterwards.
 */
final class VoterSlugExpiryRoutingTest extends IsolatedCommittedDatabaseTestCase
{
    private Organisation $org;
    private Election $election;
    private User $voter;
    private User $other;

    /** @var list<string> */
    private array $users = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->org = Organisation::factory()->create(['type' => 'tenant', 'slug' => 'pbd274-s7-'.uniqid()]);
        $this->election = Election::factory()->forOrganisation($this->org)->real()->inVotingState()->create([
            'status' => 'active', 'slug' => 'pbd274-s7-election-'.uniqid(),
            'start_date' => now()->subDay(), 'end_date' => now()->addDay(),
            'voting_locked' => true, 'voting_starts_at' => now()->subHour(), 'voting_ends_at' => now()->addHours(2),
        ]);
        $this->voter = $this->member();
        $this->other = $this->member();
    }

    protected function tearDown(): void
    {
        try {
            DB::table('voter_slugs')->where('election_id', $this->election->id)->delete();
            DB::table('election_memberships')->where('election_id', $this->election->id)->delete();
            DB::table('user_organisation_roles')->whereIn('user_id', $this->users)->delete();
            DB::table('elections')->where('id', $this->election->id)->delete();
            DB::table('organisations')->where('id', $this->org->id)->delete();
            DB::table('users')->whereIn('id', $this->users)->delete();
        } catch (\Throwable) {
            // isolated, wipeable database
        }
        parent::tearDown();
    }

    private function member(): User
    {
        $u = User::factory()->create(['email_verified_at' => now(), 'onboarded_at' => now()]);
        $this->users[] = $u->id;
        DB::table('user_organisation_roles')->insert(['id' => (string) Str::uuid(), 'user_id' => $u->id,
            'organisation_id' => $this->org->id, 'role' => 'member', 'created_at' => now(), 'updated_at' => now()]);
        ElectionMembership::create(['user_id' => $u->id, 'organisation_id' => $this->org->id,
            'election_id' => $this->election->id, 'role' => 'voter', 'status' => 'active']);

        return $u;
    }

    private function slugFor(User $user, array $attributes = []): string
    {
        $slug = Str::random(32);
        DB::table('voter_slugs')->insert($attributes + [
            'id' => (string) Str::uuid(), 'organisation_id' => $this->org->id, 'election_id' => $this->election->id,
            'user_id' => $user->id, 'slug' => $slug, 'is_active' => true, 'has_voted' => false, 'status' => 'active',
            'current_step' => 1, 'created_at' => now(), 'updated_at' => now(), 'expires_at' => now()->addMinutes(30),
        ]);

        return $slug;
    }

    private function expiredSlugFor(User $user): string
    {
        return $this->slugFor($user, ['created_at' => now()->subMinutes(31), 'expires_at' => now()->subMinute()]);
    }

    private function row(string $slug): array
    {
        return (array) DB::table('voter_slugs')->where('slug', $slug)->first();
    }

    // ── expired link, owner ───────────────────────────────────────────────

    public function test_the_owner_of_an_expired_link_is_sent_to_the_election_start_page(): void
    {
        $slug = $this->expiredSlugFor($this->voter);
        $before = $this->row($slug);

        $response = $this->actingAs($this->voter)->get(route('slug.code.create', ['vslug' => $slug]));

        $response->assertRedirect(route('elections.show', $this->election->slug));
        $this->assertSame('Your voting session has expired, please start again.', session('error'));
        $this->assertSame($before, $this->row($slug), 'nothing written to the expired link');
    }

    public function test_json_requests_keep_the_403(): void
    {
        $slug = $this->expiredSlugFor($this->voter);

        $this->actingAs($this->voter)->getJson(route('slug.code.create', ['vslug' => $slug]))->assertStatus(403);
    }

    public function test_the_organisation_page_is_the_fallback_when_the_election_is_gone(): void
    {
        $slug = $this->expiredSlugFor($this->voter);
        DB::table('elections')->where('id', $this->election->id)->update(['deleted_at' => now()]);

        $response = $this->actingAs($this->voter)->get(route('slug.code.create', ['vslug' => $slug]));

        $response->assertRedirect(route('organisations.show', $this->org->slug));
        $this->assertSame('Your voting session has expired, please start again.', session('error'));
    }

    // ── O-5: someone else's link answers like an unknown link ─────────────

    public function test_someone_elses_link_answers_exactly_like_an_unknown_link(): void
    {
        $othersSlug = $this->slugFor($this->other);

        $foreign = $this->actingAs($this->voter)->get(route('slug.code.create', ['vslug' => $othersSlug]));
        $unknown = $this->actingAs($this->voter)->get(route('slug.code.create', ['vslug' => Str::random(32)]));

        $foreign->assertStatus(404);
        $unknown->assertStatus(404);
        $this->assertSame($unknown->getContent(), $foreign->getContent(), 'indistinguishable from an unknown link');
    }

    public function test_someone_elses_expired_link_also_answers_not_found_and_redirects_nowhere(): void
    {
        $othersSlug = $this->expiredSlugFor($this->other);
        $before = $this->row($othersSlug);

        $this->actingAs($this->voter)->get(route('slug.code.create', ['vslug' => $othersSlug]))->assertStatus(404);
        $this->assertSame($before, $this->row($othersSlug));
    }

    // ── O-2: closed election ──────────────────────────────────────────────

    public function test_a_closed_election_says_voting_is_closed_without_offering_a_restart(): void
    {
        $slug = $this->slugFor($this->voter);
        DB::table('elections')->where('id', $this->election->id)->update(['end_date' => now()->subDays(2)->toDateString()]);
        $before = $this->row($slug);

        $response = $this->actingAs($this->voter)->get(route('slug.code.agreement', ['vslug' => $slug]));

        $response->assertRedirect(route('elections.show', $this->election->slug));
        $this->assertSame('Voting in this election is closed.', session('error'));
        $this->assertStringNotContainsString('start again', (string) session('error'));
        $this->assertSame($before, $this->row($slug));
    }
}

<?php

namespace Tests\Feature\Election;

use App\Contexts\Elections\Application\Ports\VotingEntitlementQuery;
use App\Contexts\Elections\Domain\ValueObjects\VotingEntitlementReason;
use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\Organisation;
use App\Models\User;
use App\Models\VoterSlug;
use App\Services\TenantContext;
use App\Traits\EnsuresVoterMembership;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\RedirectResponse;
use Tests\Support\ElectionScenarioFactory;
use Tests\TestCase;

/**
 * PBDIGIT-213 C1 — one authoritative voting-entitlement decision, election-scoped.
 *
 * ADR_20261004_1549 §2.4: the in-transaction check used by vote.store/vote.verify
 * must derive scope from the ELECTION, never from the ambient tenant
 * (PBDIGIT-65/69, ADR-002 clarification (i)); every entitlement site must give
 * the same answer for the same (voter, election).
 *
 * Fixtures follow ElectionOnlyEntitlementPinTest / VotingEntitlementAmbientInvarianceTest:
 * ElectionMembership::create(), no $org->users()->attach() (UserFactory already
 * creates the organisation role), election derived via ElectionScenarioFactory.
 */
class VotingEntitlementUnificationTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $orgA;
    private Organisation $orgB;
    private User $voter;
    private Election $election;
    private ElectionMembership $membership;

    protected function setUp(): void
    {
        parent::setUp();

        Election::resetPlatformOrgCache();

        $this->orgA = Organisation::factory()->create(['type' => 'tenant', 'uses_full_membership' => false]);
        $this->orgB = Organisation::factory()->create(['type' => 'tenant', 'uses_full_membership' => false]);
        session(['current_organisation_id' => $this->orgA->id]);

        $this->voter = User::factory()->create([
            'email_verified_at' => now(),
            'organisation_id'   => $this->orgA->id,
        ]);

        $this->election = ElectionScenarioFactory::votingActive($this->orgA);

        $this->membership = ElectionMembership::create([
            'user_id'         => $this->voter->id,
            'organisation_id' => $this->orgA->id,
            'election_id'     => $this->election->id,
            'role'            => 'voter',
            'status'          => 'active',
        ]);
    }

    protected function tearDown(): void
    {
        TenantContext::clear();
        parent::tearDown();
    }

    /** The uncached, in-transaction check used by VoteController::store() and ::verify(). */
    private function inTransactionCheck(): ?RedirectResponse
    {
        $probe = new class {
            use EnsuresVoterMembership;

            public function check(Election $election, User $user): ?RedirectResponse
            {
                return $this->ensureVoterMembership($election, $user, false);
            }
        };

        return $probe->check($this->election, $this->voter);
    }

    /** @return array<string, array{string}> */
    public static function nonActiveStatuses(): array
    {
        return [
            'inactive (suspended)' => ['inactive'],
            'invited'              => ['invited'],
            'removed'              => ['removed'],
        ];
    }

    // ── The authoritative decision point ─────────────────────────────────────

    public function test_the_authoritative_entitlement_query_decides_for_an_active_voter(): void
    {
        $query = app(VotingEntitlementQuery::class);

        $this->assertSame(VotingEntitlementReason::Entitled, $query->standingOf($this->voter->id, $this->election->id));
        $this->assertSame(VotingEntitlementReason::Entitled, $query->decide($this->voter->id, $this->election->id));
    }

    /** @dataProvider nonActiveStatuses */
    public function test_the_authoritative_entitlement_query_denies_a_non_active_voter(string $status): void
    {
        $this->membership->update(['status' => $status]);

        $this->assertSame(
            VotingEntitlementReason::NotActive,
            app(VotingEntitlementQuery::class)->standingOf($this->voter->id, $this->election->id)
        );
    }

    // ── Security correction: election-derived scope at the in-transaction check ──

    public function test_in_transaction_check_admits_an_active_voter_under_the_matching_tenant(): void
    {
        TenantContext::set($this->orgA->id);

        $this->assertNull($this->inTransactionCheck());
    }

    public function test_in_transaction_check_does_not_depend_on_the_ambient_tenant(): void
    {
        TenantContext::set($this->orgB->id);

        $this->assertNull($this->inTransactionCheck(),
            'vote.store must decide entitlement from the election, not from the ambient tenant (PBDIGIT-65/69)');
    }

    // ── Every site gives the same answer ─────────────────────────────────────

    /** @dataProvider nonActiveStatuses */
    public function test_non_active_membership_is_denied_by_every_entitlement_site(string $status): void
    {
        $this->membership->update(['status' => $status]);

        $this->assertFalse($this->voter->isVoterInElection($this->election->id));
        $this->assertNotNull($this->inTransactionCheck());
    }

    public function test_soft_deleted_membership_is_denied_by_every_entitlement_site(): void
    {
        $this->membership->delete();

        $this->assertFalse($this->voter->isVoterInElection($this->election->id));
        $this->assertNotNull($this->inTransactionCheck());
    }

    public function test_membership_in_another_election_grants_nothing(): void
    {
        $other = ElectionScenarioFactory::votingActive($this->orgA);

        $this->assertFalse($this->voter->isVoterInElection($other->id));
        $this->assertSame(
            VotingEntitlementReason::NotRegistered,
            app(VotingEntitlementQuery::class)->standingOf($this->voter->id, $other->id)
        );
    }

    // ── Organisation membership ≠ election voter membership ──────────────────

    public function test_organisation_member_without_election_membership_is_not_registered(): void
    {
        // Case A: belonging to the election's organisation grants nothing by itself.
        $orgMember = User::factory()->create([
            'email_verified_at' => now(),
            'organisation_id'   => $this->orgA->id,
        ]);

        $this->assertSame(
            VotingEntitlementReason::NotRegistered,
            app(VotingEntitlementQuery::class)->standingOf($orgMember->id, $this->election->id)
        );
        $this->assertFalse($orgMember->isVoterInElection($this->election->id));
    }

    public function test_standing_is_anchored_on_the_election_not_on_the_ambient_tenant(): void
    {
        // Case E: ambient tenant is org B, the target election belongs to org A.
        TenantContext::set($this->orgB->id);

        $this->assertSame(
            VotingEntitlementReason::Entitled,
            app(VotingEntitlementQuery::class)->standingOf($this->voter->id, $this->election->id)
        );
        $this->assertTrue($this->voter->isVoterInElection($this->election->id));
    }

    public function test_a_membership_cannot_claim_an_organisation_other_than_the_elections(): void
    {
        // Case D: membership organisation ≠ election organisation is refused by the
        // composite FK (election_id, organisation_id) → elections(id, organisation_id);
        // the domain policy additionally returns OrganisationMismatch (unit test).
        $outsider = User::factory()->create(['organisation_id' => $this->orgB->id]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        // Raw insert: model hooks (BelongsToTenant) must not rewrite organisation_id.
        \Illuminate\Support\Facades\DB::table('election_memberships')->insert([
            'id'              => (string) \Illuminate\Support\Str::uuid(),
            'user_id'         => $outsider->id,
            'organisation_id' => $this->orgB->id,
            'election_id'     => $this->election->id,
            'role'            => 'voter',
            'status'          => 'active',
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);
    }

    // ── Entry projection (elections.show) ────────────────────────────────────

    public function test_entry_page_reports_an_active_voter_as_eligible_and_able_to_vote(): void
    {
        $this->actingAs($this->voter)
            ->get(route('elections.show', ['slug' => $this->election->slug]))
            ->assertInertia(fn ($page) => $page->where('isEligible', true)->where('canVote', true));
    }

    /** @return array<string, array{string}> */
    public static function inactiveOrInvited(): array
    {
        return ['inactive (suspended)' => ['inactive'], 'invited' => ['invited']];
    }

    /** @dataProvider inactiveOrInvited */
    public function test_entry_page_reports_a_non_active_voter_as_not_eligible(string $status): void
    {
        $this->membership->update(['status' => $status]);

        $this->actingAs($this->voter)
            ->get(route('elections.show', ['slug' => $this->election->slug]))
            ->assertInertia(fn ($page) => $page->where('isEligible', false)->where('canVote', false));
    }

    // ── Voting start (elections.start) ───────────────────────────────────────

    public function test_start_refuses_a_non_active_voter_and_issues_no_credential(): void
    {
        $this->membership->update(['status' => 'inactive']);

        $this->actingAs($this->voter)
            ->post(route('elections.start', ['slug' => $this->election->slug]))
            ->assertRedirect(route('elections.show', $this->election->slug))
            ->assertSessionHas('error');

        $this->assertSame(0, VoterSlug::withoutGlobalScopes()->where('user_id', $this->voter->id)->count());
    }

    public function test_start_does_not_depend_on_the_ambient_tenant(): void
    {
        $this->actingAs($this->voter)
            ->withSession(['current_organisation_id' => $this->orgB->id])
            ->post(route('elections.start', ['slug' => $this->election->slug]))
            ->assertSessionMissing('error');

        $this->assertSame(1, VoterSlug::withoutGlobalScopes()->where('user_id', $this->voter->id)->count(),
            'an entitled voter must receive a credential whatever the ambient organisation');
    }
}

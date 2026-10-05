<?php

namespace Tests\Feature\Election;

use App\Models\Code;
use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\ElectionOfficer;
use App\Models\Organisation;
use App\Models\User;
use App\Models\Vote;
use App\Models\VoterVerification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\Support\ElectionScenarioFactory;
use Tests\TestCase;

/**
 * Identity-verification evidence — TDD contract, gap cases only (2026-10-05).
 *
 * The C3/C4 suites (IdentityVerificationAuthorityTest, IdentityVerificationAdmissionTest,
 * BulkIdentityVerificationTest, SuspensionRevalidationTest, the unit/architecture tests)
 * already cover authority, the window, attestation, re-verification, revocation and the
 * admission interaction. This file adds only what no test asserts yet:
 *
 *  - target rule: a membership with a non-voter role (candidate, observer) is not verifiable;
 *  - revocation is bounded to the election in the URL (another election's evidence → 404);
 *  - anonymity boundary (interim rule, ADR_20261005_0950 I10): verify, verify-all and
 *    revoke touch no ballot-side or participation state — votes, results, codes
 *    (has_voted, can_vote_now) and voter_slugs are unchanged — and the evidence store
 *    has no column or foreign key that could link a voter to a ballot.
 *
 * Synthetic data only.
 */
class IdentityVerificationContractTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;

    protected function setUp(): void
    {
        parent::setUp();

        Election::resetPlatformOrgCache();

        $this->org = Organisation::factory()->create(['type' => 'tenant', 'uses_full_membership' => false]);
        session(['current_organisation_id' => $this->org->id]);
    }

    // ── Fixtures ─────────────────────────────────────────────────────────────

    private function chiefOf(Election $election): User
    {
        $user = User::factory()->create(['email_verified_at' => now(), 'organisation_id' => $this->org->id]);

        ElectionOfficer::create([
            'user_id'         => $user->id,
            'election_id'     => $election->id,
            'organisation_id' => $this->org->id,
            'role'            => 'chief',
            'status'          => 'active',
            'appointed_by'    => $user->id,
            'appointed_at'    => now(),
            'accepted_at'     => now(),
        ]);

        return $user;
    }

    private function member(Election $election, string $role = 'voter'): User
    {
        $user = User::factory()->create(['email_verified_at' => now(), 'organisation_id' => $this->org->id]);

        ElectionMembership::create([
            'user_id'         => $user->id,
            'organisation_id' => $this->org->id,
            'election_id'     => $election->id,
            'role'            => $role,
            'status'          => 'active',
        ]);

        return $user;
    }

    private function routeParams(Election $election, array $extra = []): array
    {
        return array_merge(['organisation' => $this->org->slug, 'election' => $election->slug], $extra);
    }

    private function as(User $actor)
    {
        return $this->actingAs($actor)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->from('/voters-manage');
    }

    private function verify(User $actor, Election $election, User $target)
    {
        return $this->as($actor)->post(
            route('elections.voters.verify', $this->routeParams($election)),
            ['user_id' => $target->id, 'attested' => true]
        );
    }

    private function evidenceOf(User $voter, Election $election): ?VoterVerification
    {
        return VoterVerification::where('election_id', $election->id)->where('user_id', $voter->id)->first();
    }

    /** A participation code for the voter (already through code entry), plus an unrelated cast ballot. */
    private function participationAndBallotState(Election $election, User $voter): void
    {
        Code::factory()->verified()->create([
            'organisation_id' => $this->org->id,
            'user_id'         => $voter->id,
            'election_id'     => $election->id,
        ]);

        Vote::factory()->create(['election_id' => $election->id]);
    }

    /** Full contents of every ballot-side and participation table, for before/after comparison. */
    private function ballotSideSnapshot(): array
    {
        $snapshot = [];
        foreach (['votes', 'results', 'codes', 'voter_slugs', 'voter_slug_steps'] as $table) {
            $snapshot[$table] = DB::table($table)->orderBy('id')->get()->map(fn ($row) => (array) $row)->all();
        }

        // Non-vacuous: the comparison must cover a real participation code and a real ballot.
        $this->assertCount(1, $snapshot['codes']);
        $this->assertCount(1, $snapshot['votes']);

        return $snapshot;
    }

    // ── Target rule: role voter only ─────────────────────────────────────────

    public function test_a_candidate_membership_cannot_be_identity_verified(): void
    {
        $election  = ElectionScenarioFactory::setupAdministration($this->org)->fresh();
        $chief     = $this->chiefOf($election);
        $candidate = $this->member($election, 'candidate');

        $this->verify($chief, $election, $candidate)->assertSessionHasErrors('user_id');

        $this->assertNull($this->evidenceOf($candidate, $election));
    }

    public function test_an_observer_membership_cannot_be_identity_verified(): void
    {
        $election = ElectionScenarioFactory::setupAdministration($this->org)->fresh();
        $chief    = $this->chiefOf($election);
        $observer = $this->member($election, 'observer');

        $this->verify($chief, $election, $observer)->assertSessionHasErrors('user_id');

        $this->assertNull($this->evidenceOf($observer, $election));
    }

    // ── Revocation is bounded to the election in the URL ─────────────────────

    public function test_revoking_evidence_of_another_election_is_not_found_and_changes_nothing(): void
    {
        $electionA = ElectionScenarioFactory::setupAdministration($this->org)->fresh();
        $electionB = ElectionScenarioFactory::setupAdministration($this->org)->fresh();
        $chiefA    = $this->chiefOf($electionA);
        $chiefB    = $this->chiefOf($electionB);
        $voterB    = $this->member($electionB);

        $this->verify($chiefB, $electionB, $voterB)->assertSessionHasNoErrors();
        $evidenceB = $this->evidenceOf($voterB, $electionB);
        $this->assertNotNull($evidenceB);

        // Chief of A, URL of A, evidence id of B.
        $this->as($chiefA)
            ->delete(route('elections.voters.verification.revoke', $this->routeParams($electionA, ['verification' => $evidenceB->id])))
            ->assertNotFound();

        $evidenceB->refresh();
        $this->assertSame('active', $evidenceB->status);
        $this->assertNull($evidenceB->revoked_at);
        $this->assertNull($evidenceB->revocation_reason);
    }

    // ── Anonymity boundary (interim rule, I10) ───────────────────────────────

    public function test_verifying_identity_during_voting_touches_no_ballot_or_participation_state(): void
    {
        $election = ElectionScenarioFactory::votingActive($this->org)->fresh();
        $chief    = $this->chiefOf($election);
        $voter    = $this->member($election);
        $this->participationAndBallotState($election, $voter);

        $before = $this->ballotSideSnapshot();

        $this->verify($chief, $election, $voter)->assertSessionHasNoErrors();

        $this->assertNotNull($this->evidenceOf($voter, $election));
        $this->assertEquals($before, $this->ballotSideSnapshot());
    }

    public function test_verify_all_during_voting_touches_no_ballot_or_participation_state(): void
    {
        $election = ElectionScenarioFactory::votingActive($this->org)->fresh();
        $chief    = $this->chiefOf($election);
        $voter    = $this->member($election);
        $this->participationAndBallotState($election, $voter);

        $before = $this->ballotSideSnapshot();

        $this->as($chief)
            ->post(route('elections.voters.verify-all', $this->routeParams($election)), ['attested' => '1', 'expected_count' => 1])
            ->assertSessionHasNoErrors();

        $this->assertNotNull($this->evidenceOf($voter, $election));
        $this->assertEquals($before, $this->ballotSideSnapshot());
    }

    public function test_revoking_identity_during_voting_touches_no_ballot_or_participation_state(): void
    {
        $election = ElectionScenarioFactory::votingActive($this->org)->fresh();
        $chief    = $this->chiefOf($election);
        $voter    = $this->member($election);
        $this->participationAndBallotState($election, $voter);

        $this->verify($chief, $election, $voter)->assertSessionHasNoErrors();
        $evidence = $this->evidenceOf($voter, $election);

        $before = $this->ballotSideSnapshot();

        $this->as($chief)
            ->delete(route('elections.voters.verification.revoke', $this->routeParams($election, ['verification' => $evidence->id])))
            ->assertSessionHasNoErrors();

        $this->assertSame('revoked', $evidence->fresh()->status);
        $this->assertEquals($before, $this->ballotSideSnapshot());
    }

    public function test_the_evidence_store_has_no_column_that_could_reference_a_ballot(): void
    {
        $columns = Schema::getColumnListing('voter_verifications');

        $ballotLike = array_values(array_filter(
            $columns,
            fn (string $column) => preg_match('/vote|ballot|receipt|slug|code|result|candida/i', $column) === 1
        ));

        $this->assertSame([], $ballotLike, 'voter_verifications must carry no ballot-side reference');
    }

    public function test_the_evidence_store_has_no_foreign_key_to_a_ballot_or_participation_table(): void
    {
        $referenced = array_map(
            fn (array $fk) => $fk['foreign_table'],
            Schema::getForeignKeys('voter_verifications')
        );

        $this->assertSame(
            [],
            array_values(array_intersect($referenced, ['votes', 'results', 'codes', 'voter_slugs', 'voter_slug_steps'])),
            'voter_verifications must not reference ballot-side or participation tables'
        );
    }
}

<?php

namespace Tests\Feature\Election;

use App\Contexts\Elections\Application\Ports\VotingEntitlementQuery;
use App\Contexts\Elections\Domain\ValueObjects\VotingEntitlementReason;
use App\Models\Code;
use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\Organisation;
use App\Models\User;
use App\Models\VoterSlug;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\Support\ElectionScenarioFactory;
use Tests\TestCase;

/**
 * PBDIGIT-213 C3 — legacy flags never compete with VotingEntitlement
 * (brief 20261004_1802 §2–§5, §14; ADR_20261004_1549 §2.2 inv. 8, 10).
 *
 * Terminology (kept distinct):
 *  - VotingEntitlement        authoritative election-scoped decision (VotingEntitlementQuery)
 *  - User.can_vote            legacy persisted approval state — never an independent grant
 *  - Code.can_vote_now        voting-PROCESS gate, set after a verified voting code;
 *                             no authority over admission (meaning unchanged by C3)
 *  - VoterSlug                technical access/session mechanism
 *  - has_voted                separate one-ballot condition, outside entitlement
 */
class LegacyVotingFlagsBoundaryTest extends TestCase
{
    // No class-level `use RefreshDatabase;`: repeating it re-enables a wrapping test transaction, inside which
    // VoterSlugIssuer (deliberately) refuses to run. Tests\TestCase already resets the database (PBDIGIT-274).

    private Organisation $org;
    private User $voter;
    private Election $election;
    private ElectionMembership $membership;
    private VoterSlug $slug;

    protected function setUp(): void
    {
        parent::setUp();

        Election::resetPlatformOrgCache();

        $this->org = Organisation::factory()->create(['type' => 'tenant', 'uses_full_membership' => false]);
        session(['current_organisation_id' => $this->org->id]);

        $this->voter    = User::factory()->create(['email_verified_at' => now(), 'organisation_id' => $this->org->id]);
        $this->election = ElectionScenarioFactory::votingActive($this->org);
        $this->membership = ElectionMembership::create([
            'user_id'         => $this->voter->id,
            'organisation_id' => $this->org->id,
            'election_id'     => $this->election->id,
            'role'            => 'voter',
            'status'          => 'active',
        ]);
        $this->slug = VoterSlug::factory()->create([
            'user_id'         => $this->voter->id,
            'organisation_id' => $this->org->id,
            'election_id'     => $this->election->id,
            'is_active'       => true,
            'expires_at'      => now()->addHours(2),
        ]);
    }

    private function requireIdentityVerification(): void
    {
        DB::table('elections')->where('id', $this->election->id)
            ->update(['voter_admission_policy' => json_encode(['requires' => ['OFFICER_IDENTITY_VERIFICATION']])]);
    }

    /**
     * users.can_vote / users.is_voter are OLD LEGACY columns that no longer exist in
     * the schema (PO, 2026-10-04). The legacy value can therefore only live on the
     * in-memory authenticated user — which is exactly what legacy code reads
     * (auth()->user()->can_vote). The authoritative decision must ignore it.
     */
    private function legacyFlags(bool $canVote): void
    {
        $this->voter->forceFill(['is_voter' => true, 'can_vote' => $canVote]);
    }

    private function standing(): VotingEntitlementReason
    {
        return app(VotingEntitlementQuery::class)->standingOf($this->voter->id, $this->election->id);
    }

    private function asVoter()
    {
        return $this->actingAs($this->voter)->withSession(['current_organisation_id' => $this->org->id]);
    }

    // ── User.can_vote is not an independent grant ────────────────────────────

    public function test_user_can_vote_true_cannot_bypass_an_unsatisfied_admission_requirement(): void
    {
        $this->legacyFlags(canVote: true);
        $this->requireIdentityVerification();

        $this->assertSame(VotingEntitlementReason::NotAdmitted, $this->standing());
        $this->asVoter()->getJson(route('slug.code.create', ['vslug' => $this->slug->slug]))->assertForbidden();
        $this->asVoter()->post(route('elections.start', ['slug' => $this->election->slug]))
            ->assertRedirect(route('elections.show', $this->election->slug))
            ->assertSessionHas('error');
    }

    public function test_user_can_vote_false_does_not_override_an_entitled_voter(): void
    {
        $this->legacyFlags(canVote: false);

        $this->assertSame(VotingEntitlementReason::Entitled, $this->standing());
        $this->asVoter()->get(route('slug.code.create', ['vslug' => $this->slug->slug]))->assertOk();
    }

    // ── Code.can_vote_now is a voting-process gate, not admission ────────────

    public function test_a_verified_voting_code_cannot_bypass_an_unsatisfied_admission_requirement(): void
    {
        Code::factory()->verified()->create([
            'user_id'         => $this->voter->id,
            'election_id'     => $this->election->id,
            'organisation_id' => $this->org->id,
        ]);
        $this->requireIdentityVerification();

        $this->assertSame(VotingEntitlementReason::NotAdmitted, $this->standing());
        $this->asVoter()->getJson(route('slug.vote.create', ['vslug' => $this->slug->slug]))->assertForbidden();
    }

    // ── VoterSlug is a technical mechanism, not a grant ──────────────────────

    public function test_a_valid_voter_slug_cannot_bypass_an_unsatisfied_admission_requirement(): void
    {
        $this->requireIdentityVerification();
        $this->assertTrue($this->slug->fresh()->is_active, 'Precondition: the credential itself is valid');

        $this->asVoter()->getJson(route('slug.code.create', ['vslug' => $this->slug->slug]))->assertForbidden();
    }

    // ── Legacy slug-issuing paths cannot hand out a credential past entitlement ──

    public function test_a_recovery_request_cannot_issue_a_credential_to_a_voter_who_is_not_entitled(): void
    {
        $this->requireIdentityVerification();
        $officer = User::factory()->create(['email_verified_at' => now(), 'organisation_id' => $this->org->id]);
        $officer->forceFill(['is_committee_member' => true]);   // committee.member reads the in-memory actor
        $idsBefore = VoterSlug::withoutGlobalScopes()->where('user_id', $this->voter->id)->pluck('id')->sort()->values()->all();

        // PBDIGIT-274 S4: recovery is per election and goes through VoterSlugIssuer.
        $this->actingAs($officer)->withSession(['current_organisation_id' => $this->org->id])
            ->post(route('admin.voting.security.recovery', ['user' => $this->voter->id, 'election' => $this->election->id]),
                ['reason' => 'lost link']);   // required by the action; without it validation fails before the issuer runs

        $this->assertSame($idsBefore,
            VoterSlug::withoutGlobalScopes()->where('user_id', $this->voter->id)->pluck('id')->sort()->values()->all(),
            'recovery must not mint a credential for a voter who is not entitled');
        // Decision-driven change (PO 2026-10-13, plan O-3 "refuse and retire"): the existing credential is retired,
        // i.e. soft-deleted and deactivated, kept as history. Previously this test expected it untouched.
        $current = VoterSlug::withoutGlobalScopes()->findOrFail($this->slug->id);
        $this->assertNotNull($current->deleted_at, 'the not-entitled voter\'s current link is retired (O-3)');
        $this->assertFalse((bool) $current->is_active);
        $this->assertSame(VotingEntitlementReason::NotAdmitted, $this->standing());
    }

    public function test_the_legacy_voter_start_path_cannot_issue_a_credential_to_a_voter_who_is_not_entitled(): void
    {
        $this->requireIdentityVerification();
        $this->legacyFlags(canVote: true);
        $slugCount = VoterSlug::withoutGlobalScopes()->where('user_id', $this->voter->id)->count();

        $this->actingAs($this->voter)
            ->withSession(['current_organisation_id' => $this->org->id, 'selected_election_id' => $this->election->id])
            ->get(route('voter.start'));

        $this->assertSame($slugCount, VoterSlug::withoutGlobalScopes()->where('user_id', $this->voter->id)->count());
    }

    // ── has_voted stays outside entitlement ──────────────────────────────────

    public function test_has_voted_does_not_change_voting_entitlement(): void
    {
        $this->membership->update(['has_voted' => true, 'voted_at' => now()]);

        $this->assertSame(VotingEntitlementReason::Entitled, $this->standing(),
            'entitlement to vote is not the ability to cast another ballot');
    }
}

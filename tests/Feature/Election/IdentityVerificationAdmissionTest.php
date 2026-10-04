<?php

namespace Tests\Feature\Election;

use App\Contexts\Elections\Application\Ports\VotingEntitlementQuery;
use App\Contexts\Elections\Domain\ValueObjects\VotingEntitlementReason;
use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\ElectionOfficer;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\User;
use App\Models\UserOrganisationRole;
use App\Models\VoterSlug;
use App\Models\VoterVerification;
use App\Services\TenantContext;
use App\Traits\EnsuresVoterMembership;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Tests\Support\ElectionScenarioFactory;
use Tests\TestCase;

/**
 * PBDIGIT-213 C3.1 + C3.5 — admission is enforced by the ONE entitlement decision,
 * and nothing but identity evidence can admit a voter.
 *
 * ADR_20261004_1549 §2.2 invariants 1, 3, 10, 11; §2.5 cache invariant:
 *  - policy OFF ⇒ today's behaviour; policy ON ⇒ an unverified voter is NotAdmitted
 *    at every voting path (code.create, vote.create, vote.store, the in-transaction check);
 *  - verification admits only when every other entitlement condition holds;
 *  - no stale entitlement survives a policy or evidence change, in either direction;
 *  - evidence for one election admits nothing in another, under any ambient tenant;
 *  - approve / assign-by-user-id / re-import cannot admit (admission is derived).
 */
class IdentityVerificationAdmissionTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private User $voter;
    private User $chief;
    private Election $election;
    private VoterSlug $slug;

    protected function setUp(): void
    {
        parent::setUp();

        Election::resetPlatformOrgCache();

        $this->org = Organisation::factory()->create(['type' => 'tenant', 'uses_full_membership' => false]);
        session(['current_organisation_id' => $this->org->id]);

        $this->voter = User::factory()->create(['email_verified_at' => now(), 'organisation_id' => $this->org->id]);
        $this->chief = User::factory()->create(['email_verified_at' => now(), 'organisation_id' => $this->org->id]);

        $this->election = ElectionScenarioFactory::votingActive($this->org);
        $this->register($this->voter, $this->election);

        $this->slug = VoterSlug::factory()->create([
            'user_id'         => $this->voter->id,
            'organisation_id' => $this->org->id,
            'election_id'     => $this->election->id,
            'is_active'       => true,
            'expires_at'      => now()->addHours(2),
        ]);
    }

    protected function tearDown(): void
    {
        TenantContext::clear();
        parent::tearDown();
    }

    // ── Fixtures ─────────────────────────────────────────────────────────────

    private function register(User $user, Election $election, string $status = 'active'): ElectionMembership
    {
        return ElectionMembership::create([
            'user_id'         => $user->id,
            'organisation_id' => $election->organisation_id,
            'election_id'     => $election->id,
            'role'            => 'voter',
            'status'          => $status,
        ]);
    }

    /** Policy set while the election was unlocked; written raw because voting is now active (locked). */
    private function requireIdentityVerification(Election $election): void
    {
        DB::table('elections')->where('id', $election->id)
            ->update(['voter_admission_policy' => json_encode(['requires' => ['OFFICER_IDENTITY_VERIFICATION']])]);
    }

    private function evidence(User $voter, Election $election, string $status = 'active'): VoterVerification
    {
        return VoterVerification::create([
            'election_id'     => $election->id,
            'user_id'         => $voter->id,
            'organisation_id' => $election->organisation_id,
            'verified_by'     => $this->chief->id,
            'verified_at'     => now(),
            'status'          => $status,
            'verifier_role'   => 'ELECTION_CHIEF',
        ]);
    }

    private function standing(User $voter, Election $election): VotingEntitlementReason
    {
        return app(VotingEntitlementQuery::class)->standingOf($voter->id, $election->id);
    }

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

    private function asVoter()
    {
        return $this->actingAs($this->voter)->withSession(['current_organisation_id' => $this->org->id]);
    }

    // ── Policy OFF: existing behaviour preserved ─────────────────────────────

    public function test_policy_off_preserves_existing_behaviour(): void
    {
        $this->assertSame(VotingEntitlementReason::Entitled, $this->standing($this->voter, $this->election));

        $this->asVoter()->get(route('slug.code.create', ['vslug' => $this->slug->slug]))->assertOk();
    }

    // ── Policy ON: an unverified voter cannot vote, at any path ──────────────

    public function test_policy_on_an_unverified_voter_is_not_admitted(): void
    {
        $this->requireIdentityVerification($this->election);

        $this->assertSame(VotingEntitlementReason::NotAdmitted, $this->standing($this->voter, $this->election));
        $this->assertFalse($this->voter->isVoterInElection($this->election->id));
    }

    public function test_policy_on_an_unverified_voter_is_refused_code_entry(): void
    {
        $this->requireIdentityVerification($this->election);

        $this->asVoter()->getJson(route('slug.code.create', ['vslug' => $this->slug->slug]))->assertForbidden();
    }

    public function test_policy_on_an_unverified_voter_is_refused_the_ballot(): void
    {
        $this->requireIdentityVerification($this->election);

        $this->asVoter()->getJson(route('slug.vote.create', ['vslug' => $this->slug->slug]))->assertForbidden();
    }

    public function test_policy_on_an_unverified_voter_cannot_store_a_vote(): void
    {
        $this->requireIdentityVerification($this->election);

        $this->asVoter()->postJson(route('slug.vote.store', ['vslug' => $this->slug->slug]), [])->assertForbidden();
    }

    public function test_policy_on_the_in_transaction_check_refuses_an_unverified_voter(): void
    {
        $this->requireIdentityVerification($this->election);

        $this->assertNotNull($this->inTransactionCheck(), 'vote.store must refuse a voter who is not admitted');
    }

    // ── Verification admits — only together with every other condition ───────

    public function test_policy_on_a_verified_voter_is_entitled_and_reaches_code_entry(): void
    {
        $this->requireIdentityVerification($this->election);
        $this->evidence($this->voter, $this->election);

        $this->assertSame(VotingEntitlementReason::Entitled, $this->standing($this->voter, $this->election));
        $this->assertNull($this->inTransactionCheck());
        $this->asVoter()->get(route('slug.code.create', ['vslug' => $this->slug->slug]))->assertOk();
    }

    public function test_revoked_evidence_does_not_admit(): void
    {
        $this->requireIdentityVerification($this->election);
        $this->evidence($this->voter, $this->election, 'revoked');

        $this->assertSame(VotingEntitlementReason::NotAdmitted, $this->standing($this->voter, $this->election));
    }

    public function test_verification_does_not_override_an_inactive_membership(): void
    {
        $this->requireIdentityVerification($this->election);
        $this->evidence($this->voter, $this->election);
        ElectionMembership::where('user_id', $this->voter->id)->where('election_id', $this->election->id)->first()
            ->update(['status' => 'inactive']);

        $this->assertSame(VotingEntitlementReason::NotActive, $this->standing($this->voter, $this->election));
    }

    // ── Cache: no stale entitlement survives, in either direction ────────────

    public function test_verification_takes_effect_immediately_despite_the_cache(): void
    {
        $this->requireIdentityVerification($this->election);
        $this->assertFalse($this->voter->isVoterInElection($this->election->id), 'warm: denied');

        $this->evidence($this->voter, $this->election);

        $this->assertTrue($this->voter->isVoterInElection($this->election->id), 'verified ⇒ allowed at once');
    }

    public function test_revocation_takes_effect_immediately_despite_the_cache(): void
    {
        $this->requireIdentityVerification($this->election);
        $evidence = $this->evidence($this->voter, $this->election);
        $this->assertTrue($this->voter->isVoterInElection($this->election->id), 'warm: allowed');

        $evidence->update(['status' => 'revoked', 'revoked_by' => $this->chief->id, 'revoked_at' => now()]);

        $this->assertFalse($this->voter->isVoterInElection($this->election->id), 'revoked ⇒ denied at once');
    }

    public function test_a_policy_change_takes_effect_immediately_despite_the_cache(): void
    {
        $this->assertTrue($this->voter->isVoterInElection($this->election->id), 'warm under policy OFF: allowed');

        $this->requireIdentityVerification($this->election);

        $this->assertFalse($this->voter->isVoterInElection($this->election->id), 'policy ON ⇒ denied at once');
    }

    // ── Election isolation ───────────────────────────────────────────────────

    public function test_evidence_in_one_election_admits_nothing_in_another_election_of_another_organisation(): void
    {
        $orgB = Organisation::factory()->create(['type' => 'tenant', 'uses_full_membership' => false]);
        UserOrganisationRole::create(['user_id' => $this->voter->id, 'organisation_id' => $orgB->id, 'role' => 'voter']);
        $electionB = ElectionScenarioFactory::votingActive($orgB);
        $this->register($this->voter, $electionB);

        $this->requireIdentityVerification($this->election);
        $this->requireIdentityVerification($electionB);
        $this->evidence($this->voter, $this->election);   // evidence for election A only

        foreach ([$this->org->id, $orgB->id] as $ambient) {
            TenantContext::set($ambient);
            $this->assertSame(VotingEntitlementReason::Entitled, $this->standing($this->voter, $this->election), "A, ambient {$ambient}");
            $this->assertSame(VotingEntitlementReason::NotAdmitted, $this->standing($this->voter, $electionB), "B, ambient {$ambient}");
        }
    }

    // ── Entry projection and voting start ────────────────────────────────────

    public function test_the_entry_page_reports_pending_admission(): void
    {
        $this->requireIdentityVerification($this->election);

        $this->asVoter()->get(route('elections.show', ['slug' => $this->election->slug]))
            ->assertInertia(fn ($page) => $page
                ->where('admissionPending', true)
                ->where('isEligible', false)
                ->where('canVote', false));
    }

    public function test_the_entry_page_reports_no_pending_admission_for_a_verified_voter(): void
    {
        $this->requireIdentityVerification($this->election);
        $this->evidence($this->voter, $this->election);

        $this->asVoter()->get(route('elections.show', ['slug' => $this->election->slug]))
            ->assertInertia(fn ($page) => $page->where('admissionPending', false)->where('canVote', true));
    }

    public function test_voting_start_refuses_an_unverified_voter_with_a_verification_message(): void
    {
        $this->requireIdentityVerification($this->election);
        $slugBefore = $this->slug->fresh()->slug;

        $this->asVoter()->post(route('elections.start', ['slug' => $this->election->slug]))
            ->assertRedirect(route('elections.show', $this->election->slug))
            ->assertSessionHas('error', fn (string $message) => str_contains(strtolower($message), 'verified'));

        $this->assertSame($slugBefore, $this->slug->fresh()->slug, 'no credential refreshed or issued');
    }

    // ── C3.5 Bypass protection: only evidence admits ─────────────────────────

    private function administrationElectionWithChief(): Election
    {
        $election = ElectionScenarioFactory::setupAdministration($this->org);
        ElectionOfficer::create([
            'user_id'         => $this->chief->id,
            'election_id'     => $election->id,
            'organisation_id' => $this->org->id,
            'role'            => 'chief',
            'status'          => 'active',
            'appointed_by'    => $this->chief->id,
            'appointed_at'    => now(),
            'accepted_at'     => now(),
        ]);
        $election->update(['voter_admission_policy' => ['requires' => ['OFFICER_IDENTITY_VERIFICATION']]]);

        return $election->fresh();
    }

    private function voterRoute(string $name, Election $election, array $extra = []): string
    {
        return route($name, array_merge(['organisation' => $this->org->slug, 'election' => $election->slug], $extra));
    }

    public function test_approving_a_voter_does_not_admit_them(): void
    {
        $election   = $this->administrationElectionWithChief();
        $membership = $this->register($this->voter, $election, 'inactive');

        $this->actingAs($this->chief)->withSession(['current_organisation_id' => $this->org->id])
            ->post($this->voterRoute('elections.voters.approve', $election, ['membership' => $membership->id]));

        $this->assertSame('active', $membership->fresh()->status, 'Precondition: approve reactivated the membership');
        $this->assertSame(VotingEntitlementReason::NotAdmitted, $this->standing($this->voter, $election));
    }

    public function test_assigning_a_voter_by_user_id_does_not_admit_them(): void
    {
        $election = $this->administrationElectionWithChief();
        $newcomer = User::factory()->create(['email_verified_at' => now(), 'organisation_id' => $this->org->id]);
        OrganisationUser::create(['organisation_id' => $this->org->id, 'user_id' => $newcomer->id, 'status' => 'active']);

        $this->actingAs($this->chief)->withSession(['current_organisation_id' => $this->org->id])
            ->post($this->voterRoute('elections.voters.store', $election), ['user_id' => $newcomer->id]);

        $this->assertSame('active', ElectionMembership::where('user_id', $newcomer->id)->where('election_id', $election->id)->value('status'),
            'Precondition: the voter was registered');
        $this->assertSame(VotingEntitlementReason::NotAdmitted, $this->standing($newcomer, $election));
    }

    public function test_re_importing_a_voter_does_not_admit_them(): void
    {
        Bus::fake();
        $election = $this->administrationElectionWithChief();
        $this->register($this->voter, $election);
        $csv = UploadedFile::fake()->createWithContent('voters.csv', "firstname;lastname;email\nRe;Import;{$this->voter->email}");

        $this->actingAs($this->chief)->withSession(['current_organisation_id' => $this->org->id])
            ->withHeaders(['Accept' => 'application/json'])
            ->post($this->voterRoute('elections.voters.import', $election), ['file' => $csv, 'confirmed' => true])
            ->assertOk();

        $this->assertSame(VotingEntitlementReason::NotAdmitted, $this->standing($this->voter, $election));
    }
}

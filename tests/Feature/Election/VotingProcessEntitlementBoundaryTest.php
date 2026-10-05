<?php

namespace Tests\Feature\Election;

use App\Models\Code;
use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\ElectionOfficer;
use App\Models\Organisation;
use App\Models\User;
use App\Models\VoterSlug;
use App\Models\VoterVerification;
use App\Services\TenantContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Support\ElectionScenarioFactory;
use Tests\TestCase;

/**
 * PBDIGIT-213 C4 correction, Slice 1 — characterization of the voting-process boundary.
 *
 * PO requirement: only an ENTITLED voter may call any step of the voting process
 * (code create, code entry, agreement, ballot, verification, final vote, completion).
 *
 *   ElectionMembership → VoterAdmissionPolicy → AdmissionEvidence → VotingEntitlement
 *                                                                        │ asked on EVERY request
 *   code create → code entered → code verified (can_vote_now) → agreement → ballot → vote
 *
 * Code.can_vote_now means only "the code-verification step has been completed" — a
 * voting-process state. It must never stand in for the CURRENT entitlement: a voter
 * whose entitlement ends after the code step must be refused at the next request.
 *
 * Characterization: these tests pin existing behaviour (route middleware
 * ensure.election.voter → VotingEntitlementQuery; final store re-checks fresh). They
 * are expected to pass without production changes; a failure is a real boundary gap.
 */
class VotingProcessEntitlementBoundaryTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private User $voter;
    private User $chief;
    private Election $election;
    private ElectionMembership $membership;
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

        ElectionOfficer::create([
            'user_id'         => $this->chief->id,
            'election_id'     => $this->election->id,
            'organisation_id' => $this->org->id,
            'role'            => 'chief',
            'status'          => 'active',
            'appointed_by'    => $this->chief->id,
            'appointed_at'    => now(),
            'accepted_at'     => now(),
        ]);

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

    protected function tearDown(): void
    {
        TenantContext::clear();
        parent::tearDown();
    }

    // ── Fixtures ─────────────────────────────────────────────────────────────

    /** Policy set while the election was unlocked; written raw because voting is active (locked). */
    private function requireIdentityVerification(): void
    {
        DB::table('elections')->where('id', $this->election->id)
            ->update(['voter_admission_policy' => json_encode(['requires' => ['OFFICER_IDENTITY_VERIFICATION']])]);
    }

    private function evidence(string $status = 'active'): VoterVerification
    {
        return VoterVerification::create([
            'election_id'     => $this->election->id,
            'user_id'         => $this->voter->id,
            'organisation_id' => $this->org->id,
            'verified_by'     => $this->chief->id,
            'verified_at'     => now(),
            'status'          => $status,
            'verifier_role'   => 'ELECTION_CHIEF',
        ]);
    }

    /** The code step completed: can_vote_now = true, agreement not yet given. */
    private function verifiedCode(): Code
    {
        return Code::factory()->verified()->create([
            'organisation_id'    => $this->org->id,
            'user_id'            => $this->voter->id,
            'election_id'        => $this->election->id,
            'has_agreed_to_vote' => 0,
        ]);
    }

    private function makeNotEntitled(string $kind): void
    {
        match ($kind) {
            'not_admitted'      => $this->requireIdentityVerification(),
            'evidence_revoked'  => [$this->requireIdentityVerification(), $this->evidence('revoked')],
            'membership_inactive' => $this->membership->update(['status' => 'inactive']),
            'membership_removed'  => $this->membership->update(['status' => 'removed']),
        };
    }

    private function asVoter()
    {
        return $this->actingAs($this->voter)->withSession(['current_organisation_id' => $this->org->id]);
    }

    private function step(string $method, string $routeName)
    {
        $url = route($routeName, ['vslug' => $this->slug->slug]);

        return $method === 'GET' ? $this->asVoter()->getJson($url) : $this->asVoter()->postJson($url, []);
    }

    private function codesOfVoter(): int
    {
        return Code::withoutGlobalScopes()->where('user_id', $this->voter->id)->count();
    }

    private function votesInElection(): int
    {
        return DB::table('votes')->where('election_id', $this->election->id)->count();
    }

    // ── B0: every voting-process step × every kind of non-entitled voter ──────

    public static function votingProcessSteps(): array
    {
        return [
            'code create (GET)'        => ['GET',  'slug.code.create'],
            'code entry (POST)'        => ['POST', 'slug.code.store'],
            'agreement (GET)'          => ['GET',  'slug.code.agreement'],
            'agreement submit (POST)'  => ['POST', 'slug.code.agreement.submit'],
            'ballot (GET)'             => ['GET',  'slug.vote.create'],
            'ballot submit (POST)'     => ['POST', 'slug.vote.submit'],
            'vote verify page (GET)'   => ['GET',  'slug.vote.verify'],
            'final vote (POST)'        => ['POST', 'slug.vote.store'],
            'completion page (GET)'    => ['GET',  'slug.vote.complete'],
        ];
    }

    public static function nonEntitledVoters(): array
    {
        return [
            'policy ON, no evidence'      => ['not_admitted'],
            'policy ON, evidence revoked' => ['evidence_revoked'],
            'membership inactive'         => ['membership_inactive'],
            'membership removed'          => ['membership_removed'],
        ];
    }

    public static function stepsForNonEntitledVoters(): array
    {
        $cases = [];
        foreach (self::votingProcessSteps() as $step => [$method, $route]) {
            foreach (self::nonEntitledVoters() as $who => [$kind]) {
                $cases["{$step} — {$who}"] = [$method, $route, $kind];
            }
        }

        return $cases;
    }

    #[DataProvider('stepsForNonEntitledVoters')]
    public function test_a_voter_who_is_not_entitled_is_refused_at_every_voting_step(string $method, string $route, string $kind): void
    {
        $this->makeNotEntitled($kind);

        $this->step($method, $route)->assertForbidden();

        $this->assertSame(0, $this->codesOfVoter(), 'no voting code is created or verified');
        $this->assertSame(0, $this->votesInElection(), 'no vote is written');
    }

    public function test_an_entitled_voter_is_not_refused_at_code_create(): void
    {
        $this->requireIdentityVerification();
        $this->evidence();

        $this->assertNotSame(403, $this->step('GET', 'slug.code.create')->status());
    }

    // ── B3/B4: entitlement revoked AFTER the code step (can_vote_now = true) ─

    /** Admitted, code verified, entitlement warmed into the cache — then revoked by the Chief. */
    private function revokeAfterCodeVerification(): Code
    {
        $this->requireIdentityVerification();
        $evidence = $this->evidence();
        $code = $this->verifiedCode();

        $this->assertNotSame(403, $this->step('GET', 'slug.code.agreement')->status(), 'Precondition: admitted voter reaches the agreement step');

        $this->actingAs($this->chief)->withSession(['current_organisation_id' => $this->org->id])
            ->delete(route('elections.voters.verification.revoke', [
                'organisation' => $this->org->slug,
                'election'     => $this->election->slug,
                'verification' => $evidence->id,
            ]))->assertSessionHasNoErrors();

        $this->assertSame('revoked', $evidence->fresh()->status, 'Precondition: revoked through the real endpoint during voting');

        return $code;
    }

    public function test_revocation_after_code_verification_refuses_the_agreement(): void
    {
        $code = $this->revokeAfterCodeVerification();

        $this->step('GET', 'slug.code.agreement')->assertForbidden();
        $this->step('POST', 'slug.code.agreement.submit')->assertForbidden();

        $this->assertFalse((bool) $code->fresh()->has_agreed_to_vote, 'the agreement is not recorded');
    }

    public function test_revocation_after_code_verification_refuses_the_ballot_and_the_final_vote(): void
    {
        $this->revokeAfterCodeVerification();

        $this->step('GET', 'slug.vote.create')->assertForbidden();
        $this->step('POST', 'slug.vote.store')->assertForbidden();

        $this->assertSame(0, $this->votesInElection(), 'no vote is written');
    }

    public function test_revocation_does_not_touch_the_code_process_state(): void
    {
        $code = $this->revokeAfterCodeVerification();

        $this->step('GET', 'slug.code.agreement');

        $this->assertTrue((bool) $code->fresh()->can_vote_now, 'can_vote_now stays a process fact; entitlement decides');
    }

    // ── B5: confirmed-suspension END STATE after the code step ───────────────
    //
    // The two-person suspension routes are open only before voting (import_voters),
    // while codes exist only during voting — so this sequence cannot occur through the
    // product flow. Its end state (membership inactive + evidence revoked with
    // suspension_confirmed) is applied directly: defence in depth, not a product path.

    public function test_a_confirmed_suspension_state_after_code_verification_refuses_agreement_and_final_vote(): void
    {
        $this->requireIdentityVerification();
        $evidence = $this->evidence();
        $code = $this->verifiedCode();
        $this->assertNotSame(403, $this->step('GET', 'slug.code.agreement')->status(), 'Precondition: admitted voter reaches the agreement step');

        $this->membership->update(['status' => 'inactive']);
        $evidence->update(['status' => 'revoked', 'revoked_by' => $this->chief->id, 'revoked_at' => now(), 'revocation_reason' => 'suspension_confirmed']);

        $this->step('GET', 'slug.code.agreement')->assertForbidden();
        $this->step('POST', 'slug.code.agreement.submit')->assertForbidden();
        $this->step('POST', 'slug.vote.store')->assertForbidden();

        $this->assertSame(0, $this->votesInElection(), 'no vote is written');
        $this->assertTrue((bool) $code->fresh()->can_vote_now, 'can_vote_now untouched');
    }
}

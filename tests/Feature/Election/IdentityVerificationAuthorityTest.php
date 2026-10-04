<?php

namespace Tests\Feature\Election;

use App\Application\Election\Facades\ElectionLifecycle;
use App\Domain\Election\Enum\ElectionLifecycleState;
use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\ElectionOfficer;
use App\Models\Organisation;
use App\Models\User;
use App\Models\VoterVerification;
use App\Services\ElectionAuditService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\ElectionScenarioFactory;
use Tests\TestCase;

/**
 * PBDIGIT-213 C3.2 + C3.3 — who may verify, whom, and when.
 *
 * ADR_20261004_1549 D1/D2/D3, C3-D2:
 *  - only the active Chief or Deputy Chief OF THIS ELECTION may verify (or revoke);
 *    the evidence records the verifier's role and the method;
 *  - only a registered voter of this election (voter role, not deleted, not removed)
 *    can be verified — an inactive (suspended) voter can, so re-verification works
 *    before or after reinstatement;
 *  - verification may be recorded in SetupAdministration · SetupNomination ·
 *    ReadyForVoting · VotingActive only (server-side);
 *  - the audit entry identifies the subject voter and the verifier role.
 */
class IdentityVerificationAuthorityTest extends TestCase
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

    private function officer(Election $election, string $role, ?string $electionId = 'same'): User
    {
        $user = User::factory()->create(['email_verified_at' => now(), 'organisation_id' => $this->org->id]);

        ElectionOfficer::create([
            'user_id'         => $user->id,
            'election_id'     => $electionId === 'same' ? $election->id : $electionId,
            'organisation_id' => $this->org->id,
            'role'            => $role,
            'status'          => 'active',
            'appointed_by'    => $user->id,
            'appointed_at'    => now(),
            'accepted_at'     => now(),
        ]);

        return $user;
    }

    private function voter(Election $election, string $status = 'active'): User
    {
        $user = User::factory()->create(['email_verified_at' => now(), 'organisation_id' => $this->org->id]);

        ElectionMembership::create([
            'user_id'         => $user->id,
            'organisation_id' => $this->org->id,
            'election_id'     => $election->id,
            'role'            => 'voter',
            'status'          => $status,
        ]);

        return $user;
    }

    private function electionIn(ElectionLifecycleState $state): Election
    {
        $election = match ($state) {
            ElectionLifecycleState::Draft => Election::factory()->forOrganisation($this->org)->create(['type' => 'real']),
            ElectionLifecycleState::SetupAdministration => ElectionScenarioFactory::setupAdministration($this->org),
            ElectionLifecycleState::SetupNomination => ElectionScenarioFactory::setupNomination($this->org),
            ElectionLifecycleState::VotingActive => ElectionScenarioFactory::votingActive($this->org),
            ElectionLifecycleState::Counting => ElectionScenarioFactory::votingClosed($this->org),
            ElectionLifecycleState::ResultsPublished => ElectionScenarioFactory::resultsPublished($this->org),
            ElectionLifecycleState::ReadyForVoting => tap(ElectionScenarioFactory::setupAdministration($this->org))->update([
                'administration_completed' => true,
                'nomination_completed'     => true,
                'voting_starts_at'         => now()->addDay(),
                'voting_ends_at'           => now()->addDays(2),
            ]),
            ElectionLifecycleState::Suspended => tap(ElectionScenarioFactory::setupAdministration($this->org))->update([
                'suspended_at' => now(),
            ]),
        };

        $election = $election->fresh();
        $this->assertSame($state, ElectionLifecycle::of($election)->state(), 'Precondition: derived lifecycle state');

        return $election;
    }

    private function verify(User $actor, Election $election, User $target, array $extra = [])
    {
        return $this->actingAs($actor)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->from('/voters-manage')
            ->post(route('elections.voters.verify', ['organisation' => $this->org->slug, 'election' => $election->slug]),
                array_merge(['user_id' => $target->id, 'verified_ip' => '10.0.0.1', 'notes' => 'video call'], $extra));
    }

    private function evidenceOf(User $voter, Election $election): ?VoterVerification
    {
        return VoterVerification::where('election_id', $election->id)->where('user_id', $voter->id)->first();
    }

    // ── Authority (D2) ───────────────────────────────────────────────────────

    public function test_the_chief_records_identity_evidence_with_role_and_method(): void
    {
        $election = $this->electionIn(ElectionLifecycleState::SetupAdministration);
        $chief    = $this->officer($election, 'chief');
        $voter    = $this->voter($election);

        $this->verify($chief, $election, $voter)->assertSessionHasNoErrors();

        $evidence = $this->evidenceOf($voter, $election);
        $this->assertNotNull($evidence);
        $this->assertSame('active', $evidence->status);
        $this->assertSame('ELECTION_CHIEF', $evidence->verifier_role);
        $this->assertSame('officer_attestation', $evidence->method);
        $this->assertSame($chief->id, $evidence->verified_by);
    }

    public function test_the_deputy_chief_records_identity_evidence_as_deputy(): void
    {
        $election = $this->electionIn(ElectionLifecycleState::SetupAdministration);
        $deputy   = $this->officer($election, 'deputy');
        $voter    = $this->voter($election);

        $this->verify($deputy, $election, $voter)->assertSessionHasNoErrors();

        $this->assertSame('ELECTION_DEPUTY_CHIEF', $this->evidenceOf($voter, $election)?->verifier_role);
    }

    /** @return array<string, array{string}> */
    public static function nonLeadership(): array
    {
        return [
            'commissioner of the election' => ['commissioner'],
            'organisation-wide chief'      => ['org-wide-chief'],
            'chief of another election'    => ['other-election-chief'],
            'voter of the election'        => ['voter'],
        ];
    }

    /** @dataProvider nonLeadership */
    public function test_only_the_chief_or_deputy_chief_of_this_election_may_verify(string $actor): void
    {
        $election = $this->electionIn(ElectionLifecycleState::SetupAdministration);
        $voter    = $this->voter($election);

        $user = match ($actor) {
            'commissioner'         => $this->officer($election, 'commissioner'),
            'org-wide-chief'       => $this->officer($election, 'chief', null),
            'other-election-chief' => $this->officer($election, 'chief', ElectionScenarioFactory::setupAdministration($this->org)->id),
            'voter'                => $this->voter($election),
        };

        $this->verify($user, $election, $voter)->assertForbidden();

        $this->assertNull($this->evidenceOf($voter, $election));
    }

    // ── Registered target (D3, C3-D2) ────────────────────────────────────────

    public function test_a_user_who_is_not_registered_in_the_election_cannot_be_verified(): void
    {
        $election = $this->electionIn(ElectionLifecycleState::SetupAdministration);
        $outsider = User::factory()->create(['organisation_id' => $this->org->id]);

        $this->verify($this->officer($election, 'chief'), $election, $outsider)->assertSessionHasErrors('user_id');

        $this->assertNull($this->evidenceOf($outsider, $election));
    }

    public function test_a_voter_of_another_election_cannot_be_verified_here(): void
    {
        $election = $this->electionIn(ElectionLifecycleState::SetupAdministration);
        $other    = $this->voter(ElectionScenarioFactory::setupAdministration($this->org));

        $this->verify($this->officer($election, 'chief'), $election, $other)->assertSessionHasErrors('user_id');

        $this->assertNull($this->evidenceOf($other, $election));
    }

    public function test_a_removed_voter_cannot_be_verified(): void
    {
        $election = $this->electionIn(ElectionLifecycleState::SetupAdministration);
        $removed  = $this->voter($election, 'removed');

        $this->verify($this->officer($election, 'chief'), $election, $removed)->assertSessionHasErrors('user_id');

        $this->assertNull($this->evidenceOf($removed, $election));
    }

    public function test_an_inactive_suspended_voter_can_be_verified(): void
    {
        $election = $this->electionIn(ElectionLifecycleState::SetupAdministration);
        $inactive = $this->voter($election, 'inactive');

        $this->verify($this->officer($election, 'chief'), $election, $inactive)->assertSessionHasNoErrors();

        $this->assertSame('active', $this->evidenceOf($inactive, $election)?->status);
    }

    public function test_identity_evidence_does_not_require_an_ip_address(): void
    {
        $election = $this->electionIn(ElectionLifecycleState::SetupAdministration);
        $voter    = $this->voter($election);

        $this->verify($this->officer($election, 'chief'), $election, $voter, ['verified_ip' => null])->assertSessionHasNoErrors();

        $this->assertSame('active', $this->evidenceOf($voter, $election)?->status);
    }

    // ── Verification window (D1), enforced server-side ───────────────────────

    /** @return array<string, array{ElectionLifecycleState}> */
    public static function openWindow(): array
    {
        return [
            'SetupAdministration' => [ElectionLifecycleState::SetupAdministration],
            'SetupNomination'     => [ElectionLifecycleState::SetupNomination],
            'ReadyForVoting'      => [ElectionLifecycleState::ReadyForVoting],
            'VotingActive'        => [ElectionLifecycleState::VotingActive],
        ];
    }

    /** @dataProvider openWindow */
    public function test_identity_verification_may_be_recorded_inside_the_window(ElectionLifecycleState $state): void
    {
        $election = $this->electionIn($state);
        $voter    = $this->voter($election);

        $this->verify($this->officer($election, 'chief'), $election, $voter)->assertSessionHasNoErrors();

        $this->assertSame('active', $this->evidenceOf($voter, $election)?->status);
    }

    /** @return array<string, array{ElectionLifecycleState}> */
    public static function closedWindow(): array
    {
        return [
            'Draft'            => [ElectionLifecycleState::Draft],
            'Suspended'        => [ElectionLifecycleState::Suspended],
            'Counting'         => [ElectionLifecycleState::Counting],
            'ResultsPublished' => [ElectionLifecycleState::ResultsPublished],
        ];
    }

    /** @dataProvider closedWindow */
    public function test_identity_verification_is_refused_outside_the_window(ElectionLifecycleState $state): void
    {
        $election = $this->electionIn($state);
        $voter    = $this->voter($election);

        $this->verify($this->officer($election, 'chief'), $election, $voter)->assertForbidden();

        $this->assertNull($this->evidenceOf($voter, $election));
    }

    // ── Audit, re-verification, revocation ───────────────────────────────────

    public function test_the_audit_entry_identifies_the_subject_voter_and_the_verifier_role(): void
    {
        $calls = [];
        $this->mock(ElectionAuditService::class, function ($mock) use (&$calls) {
            $mock->shouldReceive('log')->andReturnUsing(function (...$args) use (&$calls) {
                $calls[] = $args;
            });
        });
        $election = $this->electionIn(ElectionLifecycleState::SetupAdministration);
        $chief    = $this->officer($election, 'chief');
        $voter    = $this->voter($election);

        $this->verify($chief, $election, $voter);

        $verified = array_values(array_filter($calls, fn (array $args) => ($args[1] ?? null) === 'voter_verified'));
        $this->assertCount(1, $verified);
        $this->assertSame($chief->id, $verified[0][2]->id);
        $this->assertSame($voter->id, $verified[0][5]['subject_user_id']);
        $this->assertSame('ELECTION_CHIEF', $verified[0][5]['verifier_role']);
        $this->assertSame('OFFICER_IDENTITY_VERIFICATION', $verified[0][5]['requirement']);
    }

    public function test_re_verification_after_revocation_restores_the_current_admission_effect(): void
    {
        $election = $this->electionIn(ElectionLifecycleState::SetupAdministration);
        $chief    = $this->officer($election, 'chief');
        $deputy   = $this->officer($election, 'deputy');
        $voter    = $this->voter($election);
        $this->verify($chief, $election, $voter);
        $this->evidenceOf($voter, $election)->update([
            'status' => 'revoked', 'revoked_by' => $chief->id, 'revoked_at' => now(), 'revocation_reason' => 'suspension_confirmed',
        ]);

        $this->verify($deputy, $election, $voter)->assertSessionHasNoErrors();

        $evidence = $this->evidenceOf($voter, $election);
        $this->assertSame('active', $evidence->status);
        $this->assertNull($evidence->revoked_by);
        $this->assertNull($evidence->revocation_reason);
        $this->assertSame('ELECTION_DEPUTY_CHIEF', $evidence->verifier_role);
        $this->assertSame($deputy->id, $evidence->verified_by);
    }

    public function test_the_chief_can_revoke_the_current_admission_effect(): void
    {
        $election = $this->electionIn(ElectionLifecycleState::SetupAdministration);
        $chief    = $this->officer($election, 'chief');
        $voter    = $this->voter($election);
        $this->verify($chief, $election, $voter);
        $evidence = $this->evidenceOf($voter, $election);

        $this->actingAs($chief)->withSession(['current_organisation_id' => $this->org->id])
            ->delete(route('elections.voters.verification.revoke', [
                'organisation' => $this->org->slug, 'election' => $election->slug, 'verification' => $evidence->id,
            ]))
            ->assertSessionHasNoErrors();

        $evidence->refresh();
        $this->assertSame('revoked', $evidence->status);
        $this->assertSame('revoked_by_officer', $evidence->revocation_reason);
        $this->assertSame($chief->id, $evidence->revoked_by);
    }

    private function revokeAs(User $actor, Election $election, VoterVerification $evidence)
    {
        return $this->actingAs($actor)->withSession(['current_organisation_id' => $this->org->id])
            ->delete(route('elections.voters.verification.revoke', [
                'organisation' => $this->org->slug, 'election' => $election->slug, 'verification' => $evidence->id,
            ]));
    }

    private function activeEvidenceFor(User $voter, Election $election, User $verifier): VoterVerification
    {
        return VoterVerification::create([
            'election_id'     => $election->id,
            'user_id'         => $voter->id,
            'organisation_id' => $this->org->id,
            'verified_by'     => $verifier->id,
            'verified_at'     => now(),
            'status'          => 'active',
            'verifier_role'   => 'ELECTION_CHIEF',
        ]);
    }

    public function test_revocation_shares_the_verification_window_and_is_allowed_during_voting(): void
    {
        $election = $this->electionIn(ElectionLifecycleState::VotingActive);
        $chief    = $this->officer($election, 'chief');
        $evidence = $this->activeEvidenceFor($this->voter($election), $election, $chief);

        $this->revokeAs($chief, $election, $evidence)->assertSessionHasNoErrors();

        $this->assertSame('revoked', $evidence->fresh()->status);
    }

    public function test_revocation_is_refused_outside_the_verification_window(): void
    {
        $election = $this->electionIn(ElectionLifecycleState::Counting);
        $chief    = $this->officer($election, 'chief');
        $evidence = $this->activeEvidenceFor($this->voter($election), $election, $chief);

        $this->revokeAs($chief, $election, $evidence)->assertForbidden();

        $this->assertSame('active', $evidence->fresh()->status);
    }

    public function test_a_commissioner_cannot_revoke(): void
    {
        $election = $this->electionIn(ElectionLifecycleState::SetupAdministration);
        $voter    = $this->voter($election);
        $this->verify($this->officer($election, 'chief'), $election, $voter);
        $evidence = $this->evidenceOf($voter, $election);

        $this->actingAs($this->officer($election, 'commissioner'))->withSession(['current_organisation_id' => $this->org->id])
            ->delete(route('elections.voters.verification.revoke', [
                'organisation' => $this->org->slug, 'election' => $election->slug, 'verification' => $evidence->id,
            ]))
            ->assertForbidden();

        $this->assertSame('active', $evidence->fresh()->status);
    }
}

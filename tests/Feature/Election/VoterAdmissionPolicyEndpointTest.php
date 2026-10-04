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
use Illuminate\Support\Facades\DB;
use Tests\Support\ElectionScenarioFactory;
use Tests\TestCase;

/**
 * PBDIGIT-213 C2 — the Voter Admission Policy endpoint, lock, scope and impact.
 *
 * ADR_20261004_1549 D4 / R3-1 / R3-2 and C2-D1 / C2-D2:
 *  - only the active Chief or Deputy OF THIS ELECTION may change it;
 *  - editable in Draft · SetupAdministration · SetupNomination, locked from
 *    ReadyForVoting onward, at the application layer AND by the model guard;
 *  - Election-Only real elections only; demo → 404;
 *  - OFF is the default; a change writes no membership row (admission is derived);
 *  - every effective change is audited; the generic settings PATCH is no bypass.
 */
class VoterAdmissionPolicyEndpointTest extends TestCase
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

    private function patchPolicy(User $actor, Election $election, mixed $required)
    {
        return $this->actingAs($actor)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->from(route('elections.settings.edit', $election->slug))
            ->patch(route('elections.admission-policy.update', $election->slug), [
                'identity_verification_required' => $required,
            ]);
    }

    private function identityRequired(Election $election): bool
    {
        return $election->fresh()->admissionPolicy()->requires(
            \App\Contexts\Elections\Domain\ValueObjects\AdmissionRequirement::OfficerIdentityVerification
        );
    }


    // ── Default (C2-D1) ──────────────────────────────────────────────────────

    public function test_a_new_election_has_identity_verification_off_by_default(): void
    {
        $election = $this->electionIn(ElectionLifecycleState::SetupAdministration);

        $this->assertTrue($election->admissionPolicy()->isEmpty());
        $this->assertSame(
            ['requires' => []],
            json_decode(DB::table('elections')->where('id', $election->id)->value('voter_admission_policy'), true)
        );
    }

    // ── Authorization (D2 — election-scoped Chief or Deputy) ─────────────────

    public function test_the_chief_of_the_election_can_turn_identity_verification_on(): void
    {
        $election = $this->electionIn(ElectionLifecycleState::SetupAdministration);

        $this->patchPolicy($this->officer($election, 'chief'), $election, true)
            ->assertRedirect(route('elections.settings.edit', $election->slug))
            ->assertSessionHas('success');

        $this->assertTrue($this->identityRequired($election));
    }

    public function test_the_deputy_of_the_election_can_turn_identity_verification_on(): void
    {
        $election = $this->electionIn(ElectionLifecycleState::SetupAdministration);

        $this->patchPolicy($this->officer($election, 'deputy'), $election, true)->assertSessionHas('success');

        $this->assertTrue($this->identityRequired($election));
    }

    public function test_the_chief_can_turn_identity_verification_off_again(): void
    {
        $election = $this->electionIn(ElectionLifecycleState::SetupAdministration);
        $chief    = $this->officer($election, 'chief');
        $this->patchPolicy($chief, $election, true);

        $this->patchPolicy($chief, $election, false)->assertSessionHas('success');

        $this->assertFalse($this->identityRequired($election));
    }

    /** @return array<string, array{string}> */
    public static function unauthorisedActors(): array
    {
        return [
            'commissioner of the election' => ['commissioner'],
            'organisation-wide chief'      => ['org-wide-chief'],
            'chief of another election'    => ['other-election-chief'],
            'voter of the election'        => ['voter'],
        ];
    }

    /** @dataProvider unauthorisedActors */
    public function test_only_the_chief_or_deputy_of_this_election_may_change_the_policy(string $actor): void
    {
        $election = $this->electionIn(ElectionLifecycleState::SetupAdministration);

        $user = match ($actor) {
            'commissioner'         => $this->officer($election, 'commissioner'),
            'org-wide-chief'       => $this->officer($election, 'chief', null),
            'other-election-chief' => $this->officer($election, 'chief', ElectionScenarioFactory::setupAdministration($this->org)->id),
            'voter'                => $this->voter($election),
        };

        $this->patchPolicy($user, $election, true)->assertForbidden();

        $this->assertFalse($this->identityRequired($election));
    }

    // ── Lifecycle lock (D4) — application layer ──────────────────────────────

    /** @return array<string, array{ElectionLifecycleState}> */
    public static function editableStates(): array
    {
        return [
            'Draft'               => [ElectionLifecycleState::Draft],
            'SetupAdministration' => [ElectionLifecycleState::SetupAdministration],
            'SetupNomination'     => [ElectionLifecycleState::SetupNomination],
        ];
    }

    /** @dataProvider editableStates */
    public function test_the_policy_is_editable_before_the_election_is_ready_for_voting(ElectionLifecycleState $state): void
    {
        $election = $this->electionIn($state);

        $this->patchPolicy($this->officer($election, 'chief'), $election, true)->assertSessionHas('success');

        $this->assertTrue($this->identityRequired($election));
    }

    /** @return array<string, array{ElectionLifecycleState}> */
    public static function lockedStates(): array
    {
        return [
            'ReadyForVoting' => [ElectionLifecycleState::ReadyForVoting],
            'VotingActive'   => [ElectionLifecycleState::VotingActive],
            'Suspended'      => [ElectionLifecycleState::Suspended],
        ];
    }

    /** @dataProvider lockedStates */
    public function test_the_policy_is_locked_from_ready_for_voting_onward(ElectionLifecycleState $state): void
    {
        $election = $this->electionIn($state);

        $this->patchPolicy($this->officer($election, 'chief'), $election, true)
            ->assertSessionHasErrors('identity_verification_required');

        $this->assertFalse($this->identityRequired($election));
    }

    // ── Lifecycle lock (D4) — model guard (defence in depth) ─────────────────

    public function test_the_model_refuses_a_policy_change_on_a_locked_election(): void
    {
        $election = $this->electionIn(ElectionLifecycleState::VotingActive);

        $this->expectException(\LogicException::class);

        $election->update(['voter_admission_policy' => ['requires' => ['OFFICER_IDENTITY_VERIFICATION']]]);
    }

    public function test_the_model_accepts_a_policy_change_on_an_unlocked_election(): void
    {
        $election = $this->electionIn(ElectionLifecycleState::SetupAdministration);

        $election->update(['voter_admission_policy' => ['requires' => ['OFFICER_IDENTITY_VERIFICATION']]]);

        $this->assertTrue($this->identityRequired($election));
    }

    // ── Scope (C2-D2) ────────────────────────────────────────────────────────

    public function test_a_full_membership_election_cannot_use_the_admission_policy(): void
    {
        $fullOrg = Organisation::factory()->create(['type' => 'tenant', 'uses_full_membership' => true]);
        $this->org = $fullOrg;
        $election = $this->electionIn(ElectionLifecycleState::SetupAdministration);
        $this->assertSame('full_membership', $election->voter_source_strategy, 'Precondition');

        $this->patchPolicy($this->officer($election, 'chief'), $election, true)
            ->assertSessionHasErrors('identity_verification_required');

        $this->assertFalse($this->identityRequired($election));
    }

    public function test_a_demo_election_has_no_admission_policy_endpoint(): void
    {
        $election = $this->electionIn(ElectionLifecycleState::SetupAdministration);
        $chief    = $this->officer($election, 'chief');
        DB::table('elections')->where('id', $election->id)->update(['type' => 'demo']);

        $this->patchPolicy($chief, $election, true)->assertNotFound();
    }

    // ── Validation ───────────────────────────────────────────────────────────

    public function test_a_non_boolean_toggle_is_rejected(): void
    {
        $election = $this->electionIn(ElectionLifecycleState::SetupAdministration);

        $this->patchPolicy($this->officer($election, 'chief'), $election, 'sometimes')
            ->assertSessionHasErrors('identity_verification_required');

        $this->assertFalse($this->identityRequired($election));
    }

    // ── Audit and derived admission ──────────────────────────────────────────

    public function test_an_effective_change_is_audited_once_with_from_to_and_affected_voters(): void
    {
        $this->recordAuditCalls();
        $election = $this->electionIn(ElectionLifecycleState::SetupAdministration);
        $this->voter($election);
        $this->voter($election);

        $this->patchPolicy($this->officer($election, 'chief'), $election, true);

        $policyCalls = $this->policyAuditCalls();
        $this->assertCount(1, $policyCalls);
        $this->assertSame('committee', $policyCalls[0][3]);
        $this->assertSame(['requires' => []], $policyCalls[0][5]['from']);
        $this->assertSame(['requires' => ['OFFICER_IDENTITY_VERIFICATION']], $policyCalls[0][5]['to']);
        $this->assertSame(2, $policyCalls[0][5]['affected_voters']);
    }

    public function test_an_identical_proposal_is_not_audited(): void
    {
        $this->recordAuditCalls();
        $election = $this->electionIn(ElectionLifecycleState::SetupAdministration);

        $this->patchPolicy($this->officer($election, 'chief'), $election, false);

        $this->assertSame([], $this->policyAuditCalls());
    }

    public function test_changing_the_policy_writes_no_membership_row(): void
    {
        $election = $this->electionIn(ElectionLifecycleState::SetupAdministration);
        $this->voter($election);
        $this->voter($election, 'inactive');
        $snapshot = fn () => DB::table('election_memberships')->where('election_id', $election->id)
            ->orderBy('id')->get(['id', 'status', 'updated_at'])->toArray();
        $before = $snapshot();

        $this->travel(5)->minutes();
        $this->patchPolicy($this->officer($election, 'chief'), $election, true)->assertSessionHas('success');

        $this->assertEquals($before, $snapshot());
    }

    // ── The generic settings PATCH is no bypass ──────────────────────────────

    public function test_the_generic_settings_update_cannot_change_the_admission_policy(): void
    {
        $election = $this->electionIn(ElectionLifecycleState::SetupAdministration);

        $this->actingAs($this->officer($election, 'chief'))
            ->withSession(['current_organisation_id' => $this->org->id])
            ->patch(route('elections.settings.update', $election->slug), [
                'selection_constraint_type' => 'maximum',
                'voter_verification_mode'   => 'none',
                'settings_version'          => $election->settings_version,
                'voter_admission_policy'    => ['requires' => ['OFFICER_IDENTITY_VERIFICATION']],
            ]);

        $this->assertFalse($this->identityRequired($election));
    }

    // ── Settings page props and impact (R3-2) ────────────────────────────────

    public function test_the_settings_page_exposes_admission_state_and_affected_voters(): void
    {
        $election = $this->electionIn(ElectionLifecycleState::SetupAdministration);
        $chief    = $this->officer($election, 'chief');

        $this->voter($election);                                  // active, no evidence      → counted
        $this->voter($election);                                  // active, no evidence      → counted
        $verified = $this->voter($election);                      // active, active evidence  → not counted
        $revoked  = $this->voter($election);                      // active, revoked evidence → counted
        $this->voter($election, 'inactive');                      // not active               → not counted
        $this->voter($election, 'removed');                       // not active               → not counted
        $this->voter($this->electionIn(ElectionLifecycleState::SetupAdministration)); // other election → not counted

        foreach ([[$verified, 'active'], [$revoked, 'revoked']] as [$user, $status]) {
            VoterVerification::create([
                'election_id'     => $election->id,
                'user_id'         => $user->id,
                'organisation_id' => $this->org->id,
                'verified_by'     => $chief->id,
                'verified_at'     => now(),
                'status'          => $status,
            ]);
        }

        $this->actingAs($chief)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->get(route('elections.settings.edit', $election->slug))
            ->assertInertia(fn ($page) => $page
                ->where('admission.identityVerificationRequired', false)
                ->where('admission.isLocked', false)
                ->where('admission.available', true)
                ->where('admission.affectedVoters', 3));
    }

    public function test_the_settings_page_reports_a_locked_policy(): void
    {
        $election = $this->electionIn(ElectionLifecycleState::VotingActive);

        $this->actingAs($this->officer($election, 'chief'))
            ->withSession(['current_organisation_id' => $this->org->id])
            ->get(route('elections.settings.edit', $election->slug))
            ->assertInertia(fn ($page) => $page->where('admission.isLocked', true));
    }

    // ── helpers ──────────────────────────────────────────────────────────────

    /** @var list<array<int, mixed>> */
    private array $recordedAuditCalls = [];

    /** Record every ElectionAuditService::log() call made during the request. */
    private function recordAuditCalls(): void
    {
        $this->recordedAuditCalls = [];
        $this->mock(ElectionAuditService::class, function ($mock) {
            $mock->shouldReceive('log')->andReturnUsing(function (...$args) {
                $this->recordedAuditCalls[] = $args;
            });
        });
    }

    /** @return list<array<int, mixed>> */
    private function policyAuditCalls(): array
    {
        return array_values(array_filter(
            $this->recordedAuditCalls,
            fn (array $args) => ($args[1] ?? null) === 'admission_policy_changed'
        ));
    }
}

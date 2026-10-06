<?php

namespace Tests\Feature\Election;

use App\Application\Election\Facades\ElectionLifecycle;
use App\Domain\Election\Enum\ElectionLifecycleState;
use App\Models\Election;
use App\Models\ElectionOfficer;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\Support\ElectionScenarioFactory;
use Tests\TestCase;

/**
 * PBDIGIT-244 — RED: the election's security policy is immutable from ReadyForVoting (I9).
 *
 * ADR_20261005_0950 §5 I9: "The security policy is immutable from ReadyForVoting (like the
 * admission policy) — the policy boundary itself is Q3."
 *
 * Interim policy boundary (PO 2026-10-05, until Q3): three components —
 *   ip_restriction        ip_restriction_enabled + ip_restriction_max_per_ip
 *   ip_whitelist          ip_whitelist
 *   verification_mode     voter_verification_mode
 *
 * Contract (modelled on the admission-policy lock, VoterAdmissionPolicyEndpointTest):
 *   Draft · SetupAdministration · SetupNomination  → a change by the election's Chief is stored
 *   ReadyForVoting · VotingActive · Suspended      → the change is refused and the stored value is unchanged
 *
 * The refusal's exact shape (error key / message) is not fixed here — GREEN decides it; the
 * tests assert only that no success is reported and that the stored value did not change.
 * Synthetic data only. No production change.
 */
class SecurityPolicyLifecycleLockTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;

    /** Stored before each patch — every component starts here. */
    private const BASELINE = [
        'ip_restriction_enabled'    => false,
        'ip_restriction_max_per_ip' => 5,
        'ip_whitelist'              => [],
        'voter_verification_mode'   => 'none',
    ];

    /** The proposed change per component. */
    private const CHANGES = [
        'ip_restriction'    => ['ip_restriction_enabled' => true, 'ip_restriction_max_per_ip' => 3],
        'ip_whitelist'      => ['ip_whitelist' => ['10.0.0.1']],
        'verification_mode' => ['voter_verification_mode' => 'ip_only'],
    ];

    protected function setUp(): void
    {
        parent::setUp();

        Election::resetPlatformOrgCache();

        $this->org = Organisation::factory()->create(['type' => 'tenant', 'uses_full_membership' => false]);
        session(['current_organisation_id' => $this->org->id]);
    }

    // ── Fixtures ─────────────────────────────────────────────────────────────

    private function chief(Election $election): User
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

    /** Same lifecycle fixtures as the admission-policy lock tests. */
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

        // Known baseline, written with the query builder (fixture only — not through the endpoint
        // under test, and past the model guard, which refuses security-policy writes on a locked
        // election; PO-approved fixture adaptation 2026-10-05).
        DB::table('elections')->where('id', $election->id)->update(array_merge(self::BASELINE, [
            'ip_whitelist'           => json_encode(self::BASELINE['ip_whitelist']),
            'no_vote_option_enabled' => false,
        ]));

        $election = $election->fresh();
        $this->assertSame($state, ElectionLifecycle::of($election)->state(), 'Precondition: derived lifecycle state');

        return $election;
    }

    /** PATCH the settings form with the baseline plus one component's change. */
    private function patchSecuritySetting(Election $election, string $component)
    {
        $payload = array_merge(self::BASELINE, [
            'selection_constraint_type' => 'any',
            'settings_version'          => (int) $election->settings_version,
        ], self::CHANGES[$component]);

        return $this->actingAs($this->chief($election))
            ->withSession(['current_organisation_id' => $this->org->id])
            ->from(route('elections.settings.edit', $election->slug))
            ->patch(route('elections.settings.update', $election->slug), $payload);
    }

    /** The stored values of the component's fields. */
    private function stored(Election $election, string $component): array
    {
        $fresh = $election->fresh();

        return collect(self::CHANGES[$component])->keys()
            ->mapWithKeys(fn (string $field) => [$field => $fresh->{$field}])
            ->all();
    }

    private function baselineOf(string $component): array
    {
        return array_intersect_key(self::BASELINE, self::CHANGES[$component]);
    }

    // ── Data ─────────────────────────────────────────────────────────────────

    /** @return array<string, array{ElectionLifecycleState, string}> */
    public static function editable(): array
    {
        return self::cross([
            'Draft'               => ElectionLifecycleState::Draft,
            'SetupAdministration' => ElectionLifecycleState::SetupAdministration,
            'SetupNomination'     => ElectionLifecycleState::SetupNomination,
        ]);
    }

    /** @return array<string, array{ElectionLifecycleState, string}> */
    public static function locked(): array
    {
        return self::cross([
            'ReadyForVoting' => ElectionLifecycleState::ReadyForVoting,
            'VotingActive'   => ElectionLifecycleState::VotingActive,
            'Suspended'      => ElectionLifecycleState::Suspended,
        ]);
    }

    private static function cross(array $states): array
    {
        $cases = [];
        foreach ($states as $stateName => $state) {
            foreach (array_keys(self::CHANGES) as $component) {
                $cases["{$component} in {$stateName}"] = [$state, $component];
            }
        }

        return $cases;
    }

    // ── Before ReadyForVoting: editable ─────────────────────────────────────

    /** @dataProvider editable */
    public function test_the_security_policy_is_editable_before_ready_for_voting(ElectionLifecycleState $state, string $component): void
    {
        $election = $this->electionIn($state);

        $this->patchSecuritySetting($election, $component)->assertSessionHas('success');

        $this->assertEquals(self::CHANGES[$component], $this->stored($election, $component), "{$component}: the change is stored");
    }

    // ── From ReadyForVoting on: locked (I9) ─────────────────────────────────

    /** @dataProvider locked */
    public function test_the_security_policy_is_locked_from_ready_for_voting_onward(ElectionLifecycleState $state, string $component): void
    {
        $election = $this->electionIn($state);

        $response = $this->patchSecuritySetting($election, $component);

        $this->assertNotSame(500, $response->status());
        $this->assertEquals($this->baselineOf($component), $this->stored($election, $component), "{$component}: the stored value is unchanged (I9)");
        $response->assertSessionMissing('success');
    }

    // ── GREEN additions (PBDIGIT-244 plan §Tests) ───────────────────────────

    /** @return array<string, array{string}> */
    public static function components(): array
    {
        return array_map(fn (string $c) => [$c], array_combine(array_keys(self::CHANGES), array_keys(self::CHANGES)));
    }

    /** @dataProvider components */
    public function test_the_model_refuses_a_security_policy_change_on_a_locked_election(string $component): void
    {
        $election = $this->electionIn(ElectionLifecycleState::VotingActive);

        try {
            $election->update(self::CHANGES[$component]);
            $this->fail("{$component}: the model guard must refuse the change");
        } catch (\LogicException $e) {
            $this->assertStringContainsString('security policy', $e->getMessage());
        }

        $this->assertEquals($this->baselineOf($component), $this->stored($election, $component), "{$component}: nothing written");
    }

    /** @dataProvider components */
    public function test_the_model_accepts_a_security_policy_change_on_an_unlocked_election(string $component): void
    {
        $election = $this->electionIn(ElectionLifecycleState::SetupAdministration);

        $election->update(self::CHANGES[$component]);

        $this->assertEquals(self::CHANGES[$component], $this->stored($election, $component));
    }

    public function test_a_locked_election_saves_other_settings_when_the_security_policy_is_resubmitted_unchanged(): void
    {
        // PBDIGIT-254: VotingActive now freezes ALL settings; ReadyForVoting is locked but still saves other settings.
        $election = $this->electionIn(ElectionLifecycleState::ReadyForVoting);
        // Stored whitelist NULL, re-sent as [] — the same value (PO 2026-10-05).
        DB::table('elections')->where('id', $election->id)->update(['ip_whitelist' => null]);
        $election = $election->fresh();

        $response = $this->actingAs($this->chief($election))
            ->withSession(['current_organisation_id' => $this->org->id])
            ->from(route('elections.settings.edit', $election->slug))
            ->patch(route('elections.settings.update', $election->slug), array_merge(self::BASELINE, [
                'selection_constraint_type' => 'any',
                'settings_version'          => (int) $election->settings_version,
                'no_vote_option_enabled'    => true,
            ]));

        $response->assertSessionHas('success');
        $fresh = $election->fresh();
        $this->assertTrue((bool) $fresh->no_vote_option_enabled, 'the non-security setting is saved');
        $this->assertNull($fresh->ip_whitelist, 'the unchanged security field is not rewritten');
        $this->assertSame('none', $fresh->voter_verification_mode);
    }

    public function test_a_refused_security_change_writes_nothing_from_the_same_patch(): void
    {
        // PBDIGIT-254: tested on ReadyForVoting (locked); VotingActive is refused earlier by the settings freeze.
        $election = $this->electionIn(ElectionLifecycleState::ReadyForVoting);

        $response = $this->actingAs($this->chief($election))
            ->withSession(['current_organisation_id' => $this->org->id])
            ->from(route('elections.settings.edit', $election->slug))
            ->patch(route('elections.settings.update', $election->slug), array_merge(self::BASELINE, [
                'selection_constraint_type' => 'any',
                'settings_version'          => (int) $election->settings_version,
                'no_vote_option_enabled'    => true,
                'voter_verification_mode'   => 'ip_only',
            ]));

        $response->assertSessionHasErrors('voter_verification_mode');
        $response->assertSessionMissing('success');
        $fresh = $election->fresh();
        $this->assertSame('none', $fresh->voter_verification_mode, 'the security field is unchanged');
        $this->assertFalse((bool) $fresh->no_vote_option_enabled, 'the whole PATCH is refused — no other field written');
        $this->assertSame((int) $election->settings_version, (int) $fresh->settings_version, 'settings version unchanged');
    }
}

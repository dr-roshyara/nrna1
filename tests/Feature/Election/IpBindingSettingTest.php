<?php

namespace Tests\Feature\Election;

use App\Models\Election;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\Support\ElectionScenarioFactory;
use Tests\TestCase;

/**
 * PBDIGIT-247 — RED: the IP Binding setting. Plan: docs/plans/20261006-2125-pbdigit-247-ip-binding-plan.md.
 *
 * B2: elections.ip_binding_enabled (default false) is a SECURITY-POLICY field — in Election::SECURITY_POLICY_FIELDS,
 * locked from ReadyForVoting (PBDIGIT-244). It needs IP verification: binding on with verification off is refused,
 * and verification cannot be turned off while binding is on (re-capture must stay possible).
 * B4: the captured VALUE may still be re-captured by the Chief / Deputy after ReadyForVoting (audited).
 *
 *   B-S1 the Chief enables binding while IP verification is on → stored
 *   B-S2 enabling binding with IP verification off → refused, not stored
 *   B-S3 turning IP verification off while binding is on → refused, both unchanged
 *   B-S4 binding is a locked security-policy field: a change while voting is active is refused
 *   B-S5 with binding on and voting active, an officer can still re-capture a voter's IP
 *   B-S6 Voter Management: ipBinding.active follows the setting; captureIp stays driven by IP verification
 * Synthetic data only.
 */
class IpBindingSettingTest extends TestCase
{
    use RefreshDatabase;
    use C4Fixtures;

    protected function setUp(): void
    {
        parent::setUp();
        $this->makeOrganisation();
    }

    private function patchSettings(Election $election, User $chief, array $change)
    {
        $payload = array_merge([
            'ip_restriction_enabled'    => false,
            'ip_restriction_max_per_ip' => 5,
            'ip_whitelist'              => [],
            'selection_constraint_type' => 'any',
            'settings_version'          => (int) $election->fresh()->settings_version,
        ], $change);

        return $this->actingAs($chief)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->from(route('elections.settings.edit', $election->slug))
            ->patch(route('elections.settings.update', $election->slug), $payload);
    }

    private function stored(Election $election, string $field): bool
    {
        return (bool) DB::table('elections')->where('id', $election->id)->value($field);
    }

    public function test_b_s1_the_chief_enables_binding_while_ip_verification_is_on(): void
    {
        $election = ElectionScenarioFactory::setupAdministration($this->org);
        $chief = $this->officer($election, 'chief');

        $this->patchSettings($election, $chief, ['ip_verification_enabled' => true, 'ip_binding_enabled' => true])
            ->assertSessionHasNoErrors();

        $this->assertTrue($this->stored($election, 'ip_binding_enabled'));
        $this->assertContains('ip_binding_enabled', Election::SECURITY_POLICY_FIELDS, 'B2: a locked security-policy field');
    }

    public function test_b_s2_binding_cannot_be_enabled_while_ip_verification_is_off(): void
    {
        $election = ElectionScenarioFactory::setupAdministration($this->org);
        $chief = $this->officer($election, 'chief');

        $this->patchSettings($election, $chief, ['ip_verification_enabled' => false, 'ip_binding_enabled' => true])
            ->assertSessionHasErrors('ip_binding_enabled');

        $this->assertFalse($this->stored($election, 'ip_binding_enabled'));
    }

    public function test_b_s3_ip_verification_cannot_be_turned_off_while_binding_is_on(): void
    {
        $election = ElectionScenarioFactory::setupAdministration($this->org);
        $chief = $this->officer($election, 'chief');
        DB::table('elections')->where('id', $election->id)->update(['ip_verification_enabled' => true, 'ip_binding_enabled' => true]);

        $this->patchSettings($election, $chief, ['ip_verification_enabled' => false, 'ip_binding_enabled' => true])
            ->assertSessionHasErrors('ip_verification_enabled');

        $this->assertTrue($this->stored($election, 'ip_verification_enabled'));
        $this->assertTrue($this->stored($election, 'ip_binding_enabled'));
    }

    public function test_b_s4_binding_is_locked_while_voting_is_active(): void
    {
        $election = ElectionScenarioFactory::votingActive($this->org);
        $chief = $this->officer($election, 'chief');
        DB::table('elections')->where('id', $election->id)->update([
            'ip_restriction_enabled' => false, 'ip_restriction_max_per_ip' => 5, 'ip_whitelist' => json_encode([]),
            'ip_verification_enabled' => true, 'ip_binding_enabled' => false,
        ]);

        $response = $this->patchSettings($election, $chief, ['ip_verification_enabled' => true, 'ip_binding_enabled' => true]);

        $response->assertSessionHasErrors('ip_binding_enabled');
        $this->assertFalse($this->stored($election, 'ip_binding_enabled'), 'PBDIGIT-244 lock: unchanged');
    }

    public function test_b_s5_an_officer_can_recapture_a_voters_ip_while_voting_is_active_with_binding_on(): void
    {
        $election = ElectionScenarioFactory::votingActive($this->org);
        $chief = $this->officer($election, 'chief');
        DB::table('elections')->where('id', $election->id)->update(['ip_verification_enabled' => true, 'ip_binding_enabled' => true]);
        $voter = $this->registeredVoter($election);
        $voter->forceFill(['current_ip' => '198.51.100.20'])->save();

        $this->actingAs($chief)->withSession(['current_organisation_id' => $this->org->id])
            ->from('/voters-manage')
            ->post($this->voterRoute('elections.voters.capture-ip', $election), ['user_id' => $voter->id])
            ->assertSessionHasNoErrors();

        $this->assertSame('198.51.100.20', DB::table('voter_ip_verifications')
            ->where('election_id', $election->id)->where('user_id', $voter->id)->value('ip_address'),
            'B4: the captured value stays re-capturable while the policy is locked');
    }

    public function test_b_s6_voter_management_ip_binding_follows_the_setting(): void
    {
        $election = ElectionScenarioFactory::setupAdministration($this->org);
        $chief = $this->officer($election, 'chief');
        DB::table('elections')->where('id', $election->id)->update(['ip_verification_enabled' => true, 'ip_binding_enabled' => true]);

        $this->actingAs($chief)->withSession(['current_organisation_id' => $this->org->id])
            ->get($this->voterRoute('elections.voters.index', $election))
            ->assertInertia(fn ($p) => $p->where('ipBinding.active', true)->where('captureIp.active', true));
    }
}

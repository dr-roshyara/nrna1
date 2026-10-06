<?php

namespace Tests\Feature\Election;

use App\Models\Election;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\Support\ElectionScenarioFactory;
use Tests\TestCase;

/**
 * PBDIGIT-222 — "Voter's IP Verification" election setting (PO 2026-10-06) — RED.
 *
 * A dedicated Enabled / Disabled setting (elections.ip_verification_enabled, default false)
 * controls Capture-IP availability. It is evidence configuration, NOT security policy:
 * it is not one of Election::SECURITY_POLICY_FIELDS and is changeable at any time.
 * IP Binding (PBDIGIT-247) stays inactive and independent of it.
 * The legacy voter_verification_mode is non-authoritative and no longer required.
 *
 *   S1  the election's Chief enables IP verification → stored true
 *   S2  the election's Chief disables IP verification → stored false
 *   S3  the setting is changeable while VotingActive (not locked like the security policy)
 *   S4  a settings PATCH without voter_verification_mode succeeds
 *   S5  Voter Management: captureIp.active follows the setting; ipBinding.active stays false
 * Synthetic data only.
 */
class VoterIpVerificationSettingTest extends TestCase
{
    use RefreshDatabase;
    use C4Fixtures;

    protected function setUp(): void
    {
        parent::setUp();
        $this->makeOrganisation();
    }

    /** PATCH the settings form; voter_verification_mode is deliberately absent (the page no longer sends it). */
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

    private function storedSetting(Election $election): mixed
    {
        return DB::table('elections')->where('id', $election->id)->value('ip_verification_enabled');
    }

    public function test_s1_the_chief_enables_ip_verification(): void
    {
        $election = ElectionScenarioFactory::setupAdministration($this->org);
        $chief = $this->officer($election, 'chief');

        $this->patchSettings($election, $chief, ['ip_verification_enabled' => true])->assertSessionHasNoErrors();

        $this->assertTrue((bool) $this->storedSetting($election));
    }

    public function test_s2_the_chief_disables_ip_verification(): void
    {
        $election = ElectionScenarioFactory::setupAdministration($this->org);
        $chief = $this->officer($election, 'chief');
        DB::table('elections')->where('id', $election->id)->update(['ip_verification_enabled' => true]);

        $this->patchSettings($election, $chief, ['ip_verification_enabled' => false])->assertSessionHasNoErrors();

        $this->assertFalse((bool) $this->storedSetting($election));
    }

    public function test_s3_the_setting_is_changeable_while_voting_is_active(): void
    {
        $election = ElectionScenarioFactory::votingActive($this->org);
        $chief = $this->officer($election, 'chief');
        DB::table('elections')->where('id', $election->id)->update([
            'ip_restriction_enabled' => false, 'ip_restriction_max_per_ip' => 5, 'ip_whitelist' => json_encode([]),
        ]);

        $this->patchSettings($election, $chief, ['ip_verification_enabled' => true])->assertSessionHasNoErrors();

        $this->assertTrue((bool) $this->storedSetting($election), 'not a security-policy field — not locked from ReadyForVoting');
        $this->assertNotContains('ip_verification_enabled', Election::SECURITY_POLICY_FIELDS);
    }

    public function test_s4_a_settings_patch_without_voter_verification_mode_succeeds(): void
    {
        $election = ElectionScenarioFactory::setupAdministration($this->org);
        $chief = $this->officer($election, 'chief');
        $legacyBefore = DB::table('elections')->where('id', $election->id)->value('voter_verification_mode');

        $this->patchSettings($election, $chief, ['ip_verification_enabled' => false])
            ->assertSessionDoesntHaveErrors('voter_verification_mode');

        $this->assertSame($legacyBefore, DB::table('elections')->where('id', $election->id)->value('voter_verification_mode'),
            'the legacy field is left as it was');
    }

    public function test_s5_voter_management_capture_ip_follows_the_setting_and_ip_binding_stays_inactive(): void
    {
        $election = ElectionScenarioFactory::setupAdministration($this->org);
        $chief = $this->officer($election, 'chief');

        $page = fn () => $this->actingAs($chief)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->get($this->voterRoute('elections.voters.index', $election));

        $page()->assertInertia(fn ($p) => $p
            ->where('captureIp.active', false)
            ->where('ipBinding.active', false));

        DB::table('elections')->where('id', $election->id)->update(['ip_verification_enabled' => true]);

        $page()->assertInertia(fn ($p) => $p
            ->where('captureIp.active', true)
            ->where('ipBinding.active', false));
    }
}

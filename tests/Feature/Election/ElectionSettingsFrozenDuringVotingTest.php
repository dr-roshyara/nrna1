<?php

namespace Tests\Feature\Election;

use App\Models\Election;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\Support\ElectionScenarioFactory;
use Tests\TestCase;

/**
 * PBDIGIT-254 — RED: election settings are frozen during the voting period (PO 2026-10-06: "the button … must be
 * disabled … during voting period" → "Both": locked fields disabled AND Save disabled; the server refuses too).
 *
 * Voting period = VotingActive. ReadyForVoting keeps today's rule (security policy locked, other settings editable).
 * This supersedes "Voter's IP Verification changeable at any time" (PBDIGIT-222) for the voting period only.
 *
 *   V1 while voting, a non-security change (no-vote option) is refused with the voting-period message; nothing stored
 *   V2 while voting, Voter's IP Verification cannot be changed either
 *   V3 ReadyForVoting: a non-security change is still stored (unchanged)
 *   V4 the edit page tells the UI the settings are frozen: settingsFrozen true while voting, false before
 *   V5 PO: "Ip address can be verified but policy must remain same" — capturing a voter's IP still works while voting
 * Synthetic data only.
 */
class ElectionSettingsFrozenDuringVotingTest extends TestCase
{
    use RefreshDatabase;
    use C4Fixtures;

    private const FROZEN = 'Election settings cannot be changed while voting is in progress.';

    protected function setUp(): void
    {
        parent::setUp();
        $this->makeOrganisation();
    }

    private function patch_(Election $election, User $chief, array $change)
    {
        DB::table('elections')->where('id', $election->id)->update([
            'ip_restriction_enabled' => false, 'ip_restriction_max_per_ip' => 5, 'ip_whitelist' => json_encode([]),
            'no_vote_option_enabled' => false, 'ip_verification_enabled' => false, 'ip_binding_enabled' => false,
        ]);

        return $this->actingAs($chief)->withSession(['current_organisation_id' => $this->org->id])
            ->from(route('elections.settings.edit', $election->slug))
            ->patch(route('elections.settings.update', $election->slug), array_merge([
                'ip_restriction_enabled'    => false,
                'ip_restriction_max_per_ip' => 5,
                'ip_whitelist'              => [],
                'selection_constraint_type' => 'any',
                'confirmed_active_changes'  => true,
                'settings_version'          => (int) $election->fresh()->settings_version,
            ], $change));
    }

    private function readyForVoting(): Election
    {
        return tap(ElectionScenarioFactory::setupAdministration($this->org))->update([
            'administration_completed' => true,
            'nomination_completed'     => true,
            'voting_starts_at'         => now()->addDay(),
            'voting_ends_at'           => now()->addDays(2),
        ])->fresh();
    }

    public function test_v1_while_voting_a_non_security_change_is_refused(): void
    {
        $election = ElectionScenarioFactory::votingActive($this->org);
        $chief = $this->officer($election, 'chief');

        $this->patch_($election, $chief, ['no_vote_option_enabled' => true])->assertSessionHasErrors(['settings' => self::FROZEN]);

        $this->assertFalse((bool) DB::table('elections')->where('id', $election->id)->value('no_vote_option_enabled'));
    }

    public function test_v2_while_voting_ip_verification_cannot_be_changed(): void
    {
        $election = ElectionScenarioFactory::votingActive($this->org);
        $chief = $this->officer($election, 'chief');

        $this->patch_($election, $chief, ['ip_verification_enabled' => true])->assertSessionHasErrors('settings');

        $this->assertFalse((bool) DB::table('elections')->where('id', $election->id)->value('ip_verification_enabled'));
    }

    public function test_v3_ready_for_voting_a_non_security_change_is_still_stored(): void
    {
        $election = $this->readyForVoting();
        $chief = $this->officer($election, 'chief');

        $this->patch_($election, $chief, ['no_vote_option_enabled' => true])->assertSessionHasNoErrors();

        $this->assertTrue((bool) DB::table('elections')->where('id', $election->id)->value('no_vote_option_enabled'));
    }

    /** PO 2026-10-06: "Ip address can be verified but policy must remain same." */
    public function test_v5_while_voting_ip_addresses_can_still_be_captured_although_the_settings_are_frozen(): void
    {
        $election = ElectionScenarioFactory::votingActive($this->org);
        $chief = $this->officer($election, 'chief');
        DB::table('elections')->where('id', $election->id)->update(['ip_verification_enabled' => true]);
        $voter = $this->registeredVoter($election);
        $voter->forceFill(['current_ip' => '203.0.113.7'])->save();

        $this->actingAs($chief)->withSession(['current_organisation_id' => $this->org->id])->from('/voters-manage')
            ->post($this->voterRoute('elections.voters.capture-ip', $election), ['user_id' => $voter->id])
            ->assertSessionHasNoErrors();

        $this->assertSame('203.0.113.7', DB::table('voter_ip_verifications')
            ->where('election_id', $election->id)->where('user_id', $voter->id)->value('ip_address'));
        $this->assertTrue((bool) DB::table('elections')->where('id', $election->id)->value('ip_verification_enabled'),
            'the policy itself is unchanged');
    }

    public function test_v4_the_edit_page_says_whether_settings_are_frozen(): void
    {
        $voting = ElectionScenarioFactory::votingActive($this->org);
        $this->actingAs($this->officer($voting, 'chief'))->withSession(['current_organisation_id' => $this->org->id])
            ->get(route('elections.settings.edit', $voting->slug))
            ->assertInertia(fn ($p) => $p->where('settingsFrozen', true));

        $ready = $this->readyForVoting();
        $this->actingAs($this->officer($ready, 'chief'))->withSession(['current_organisation_id' => $this->org->id])
            ->get(route('elections.settings.edit', $ready->slug))
            ->assertInertia(fn ($p) => $p->where('settingsFrozen', false));
    }
}

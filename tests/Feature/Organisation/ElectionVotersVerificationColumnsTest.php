<?php

namespace Tests\Feature\Organisation;

use App\Models\Election;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\Feature\Election\C4Fixtures;
use Tests\Support\ElectionScenarioFactory;
use Tests\TestCase;

/**
 * PBDIGIT-250 — the read-only voter list shows verification STATES (PO 2026-10-06) — RED.
 *
 * Page: organisations.elections.voters (OrganisationController::voters). Its audience includes every
 * active voter of the election, so the page carries states only — never an IP address, capture notes
 * or who verified (VIS-D1 stays open).
 *
 * Contract:
 *   verificationColumns = { identity: bool, ip: bool }
 *     identity — the admission policy requires OFFICER_IDENTITY_VERIFICATION
 *     ip       — "Voter's IP Verification" is enabled (ip_verification_enabled, PBDIGIT-222)
 *   voters.data[*].identity_state  verified | verification_required | reverification_required, null when the column is off
 *   voters.data[*].ip_state        verified | not_verified, null when the column is off
 *
 *   V1  identity required → each row carries its identity evidence state
 *   V2  identity not required → no identity column, no identity state
 *   V3  IP verification enabled → each row carries verified / not_verified
 *   V4  IP verification disabled → no IP column, no IP state (even if a capture row exists)
 *   V5  no IP address, notes or verifier ever reaches the page
 *   V6  a plain voter of the election sees the same state-only columns
 *   V7  states are election-scoped (evidence in another election does not show)
 * Synthetic data only.
 */
class ElectionVotersVerificationColumnsTest extends TestCase
{
    use RefreshDatabase;
    use C4Fixtures;

    private Election $election;
    private User $chief;

    protected function setUp(): void
    {
        parent::setUp();
        $this->makeOrganisation();
        $this->election = ElectionScenarioFactory::setupAdministration($this->org);
        $this->chief = $this->officer($this->election, 'chief');
    }

    private function page(User $viewer, ?Election $election = null)
    {
        return $this->actingAs($viewer)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->get(route('organisations.elections.voters', [
                'organisation' => $this->org->slug,
                'election'     => ($election ?? $this->election)->slug,
            ]));
    }

    /** voters.data rows keyed by name (the page sends no user_id). */
    private function rows($response): array
    {
        return collect($response->viewData('page')['props']['voters']['data'])->keyBy('name')->all();
    }

    private function enableIpVerification(): void
    {
        DB::table('elections')->where('id', $this->election->id)->update(['ip_verification_enabled' => true]);
    }

    private function captureIp(User $voter, ?Election $election = null): void
    {
        DB::table('voter_ip_verifications')->insert([
            'id'              => (string) \Illuminate\Support\Str::uuid(),
            'election_id'     => ($election ?? $this->election)->id,
            'user_id'         => $voter->id,
            'organisation_id' => $this->org->id,
            'ip_address'      => '203.0.113.7',
            'captured_by'     => $this->chief->id,
            'captured_at'     => now(),
            'notes'           => 'home WiFi secret note',
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);
    }

    public function test_v1_identity_required_each_row_carries_its_identity_evidence_state(): void
    {
        $this->requireIdentityVerification($this->election);
        $unverified = $this->registeredVoter($this->election);
        $verified   = $this->registeredVoter($this->election);
        $revoked    = $this->registeredVoter($this->election);
        $this->evidence($verified, $this->election, $this->chief);
        $this->evidence($revoked, $this->election, $this->chief, 'revoked', 'suspension_confirmed');

        $response = $this->page($this->chief)->assertOk()
            ->assertInertia(fn ($p) => $p->where('verificationColumns.identity', true));
        $rows = $this->rows($response);

        $this->assertSame('verification_required', $rows[$unverified->name]['identity_state']);
        $this->assertSame('verified', $rows[$verified->name]['identity_state']);
        $this->assertSame('reverification_required', $rows[$revoked->name]['identity_state']);
    }

    public function test_v2_identity_not_required_no_identity_column_and_no_state(): void
    {
        $verified = $this->registeredVoter($this->election);
        $this->evidence($verified, $this->election, $this->chief);

        $response = $this->page($this->chief)->assertOk()
            ->assertInertia(fn ($p) => $p->where('verificationColumns.identity', false));

        $this->assertNull($this->rows($response)[$verified->name]['identity_state'] ?? null);
    }

    public function test_v3_ip_verification_enabled_each_row_is_verified_or_not_verified(): void
    {
        $this->enableIpVerification();
        $captured = $this->registeredVoter($this->election);
        $notYet   = $this->registeredVoter($this->election);
        $this->captureIp($captured);

        $response = $this->page($this->chief)->assertOk()
            ->assertInertia(fn ($p) => $p->where('verificationColumns.ip', true));
        $rows = $this->rows($response);

        $this->assertSame('verified', $rows[$captured->name]['ip_state']);
        $this->assertSame('not_verified', $rows[$notYet->name]['ip_state']);
    }

    public function test_v4_ip_verification_disabled_no_ip_column_and_no_state(): void
    {
        $captured = $this->registeredVoter($this->election);
        $this->captureIp($captured);

        $response = $this->page($this->chief)->assertOk()
            ->assertInertia(fn ($p) => $p->where('verificationColumns.ip', false));

        $this->assertNull($this->rows($response)[$captured->name]['ip_state'] ?? null);
    }

    public function test_v5_no_ip_address_notes_or_verifier_reaches_the_page(): void
    {
        $this->enableIpVerification();
        $this->requireIdentityVerification($this->election);
        $voter = $this->registeredVoter($this->election);
        $voter->forceFill(['current_ip' => '198.51.100.9'])->save();
        $this->captureIp($voter);
        $this->evidence($voter, $this->election, $this->chief);

        $props = json_encode($this->page($this->chief)->assertOk()->viewData('page')['props']);

        $this->assertStringNotContainsString('203.0.113.7', $props, 'no captured IP');
        $this->assertStringNotContainsString('198.51.100.9', $props, 'no last-login IP');
        $this->assertStringNotContainsString('home WiFi secret note', $props, 'no capture notes');
        $row = $this->rows($this->page($this->chief))[$voter->name];
        $this->assertArrayNotHasKey('ip_address', $row);
        $this->assertArrayNotHasKey('current_ip', $row);
        $this->assertArrayNotHasKey('verified_by', $row);
        $this->assertArrayNotHasKey('captured_by', $row);
    }

    public function test_v6_a_plain_voter_sees_the_same_state_only_columns(): void
    {
        $this->enableIpVerification();
        $this->requireIdentityVerification($this->election);
        $viewer = $this->registeredVoter($this->election);
        $other  = $this->registeredVoter($this->election);
        $this->captureIp($other);
        $this->evidence($other, $this->election, $this->chief);

        $response = $this->page($viewer)->assertOk()
            ->assertInertia(fn ($p) => $p->where('verificationColumns.identity', true)->where('verificationColumns.ip', true));
        $rows = $this->rows($response);

        $this->assertSame('verified', $rows[$other->name]['identity_state']);
        $this->assertSame('verified', $rows[$other->name]['ip_state']);
        $this->assertStringNotContainsString('203.0.113.7', json_encode($response->viewData('page')['props']));
    }

    public function test_v7_states_are_election_scoped(): void
    {
        $this->enableIpVerification();
        $this->requireIdentityVerification($this->election);
        $voter = $this->registeredVoter($this->election);
        $other = ElectionScenarioFactory::setupAdministration($this->org);
        $this->captureIp($voter, $other);
        $this->evidence($voter, $other, $this->chief);

        $row = $this->rows($this->page($this->chief))[$voter->name];

        $this->assertSame('verification_required', $row['identity_state'], 'evidence in another election does not show');
        $this->assertSame('not_verified', $row['ip_state'], 'a capture in another election does not show');
    }
}

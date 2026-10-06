<?php

namespace Tests\Feature\Election;

use App\Models\Election;
use App\Models\User;
use App\Models\VoterVerification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\Support\ElectionScenarioFactory;
use Tests\TestCase;

/**
 * CHARACTERIZATION ONLY — the legacy "Capture IP Address" flow (VoterVerificationModal).
 *
 * Record (PBDIGIT-222, sources 110387f63 / 622ee91e9): the legacy modal posted
 *   { user_id, verified_ip, verified_device_fingerprint_hash, notes }
 * to elections.voters.verify → VoterVerificationController::store, which upserted the SAME
 * voter_verifications row that is identity-admission evidence. No separate IP-capture path existed.
 *
 * These tests pin what remains of that legacy path after PBDIGIT-248 (ID-D2): the identity endpoint
 * refuses the legacy payload, and the identity controller offers no IP capture. IP capture now has its
 * own separate route and table (elections.voters.capture-ip → voter_ip_verifications, PBDIGIT-222),
 * covered by VoterIpCaptureTest — never through the identity endpoint.
 */
class LegacyCaptureIpCharacterizationTest extends TestCase
{
    use RefreshDatabase;
    use C4Fixtures;

    public function test_the_legacy_capture_ip_payload_is_refused_by_the_identity_endpoint(): void
    {
        $this->makeOrganisation();
        $election = ElectionScenarioFactory::setupAdministration($this->org);
        $chief = $this->officer($election, 'chief');
        $voter = $this->registeredVoter($election);

        // Exactly what VoterVerificationModal.vue sends (no `attested`; IP / device fields).
        $this->actingAs($chief)->withSession(['current_organisation_id' => $this->org->id])
            ->post($this->voterRoute('elections.voters.verify', $election), [
                'user_id'                          => $voter->id,
                'verified_ip'                      => '203.0.113.7',
                'verified_device_fingerprint_hash' => null,
                'notes'                            => 'Called on mobile, using home WiFi',
            ])
            ->assertSessionHasErrors(['attested', 'verified_ip']);

        $this->assertNull(
            VoterVerification::where('election_id', $election->id)->where('user_id', $voter->id)->first(),
            'nothing is recorded — neither IP nor identity evidence'
        );
    }

    public function test_the_identity_controller_offers_no_ip_capture_route(): void
    {
        // Legacy IP capture went through the identity controller; that path is closed. The separate capture
        // route belongs to VoterIpCaptureController (PBDIGIT-222) and is not part of this set.
        $verificationActions = collect(Route::getRoutes()->getRoutes())
            ->filter(fn ($r) => str_contains((string) $r->getActionName(), 'VoterVerificationController'))
            ->map(fn ($r) => $r->getName())
            ->sort()->values()->all();

        $this->assertSame(
            ['elections.voters.verification.revoke', 'elections.voters.verify', 'elections.voters.verify-all'],
            $verificationActions,
            'the identity controller offers only verify / verify-all / revoke — no IP capture'
        );
    }
}

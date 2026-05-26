<?php

namespace Tests\Unit\Application\Election\Security\Overlays;

use App\Application\Election\Security\Overlays\RegistrarAttestationElevation;
use App\Application\Election\Security\TrustCapabilityContext;
use App\Domain\Election\Security\DeviceTrustContext;
use App\Domain\Election\Security\FingerprintMatchType;
use App\Domain\Election\Security\NetworkTrustEvidence;
use App\Domain\Election\Security\OverlayInfluence;
use App\Domain\Election\Security\TrustLevel;
use App\Domain\Election\Security\TrustValidityScope;
use App\Domain\Election\Security\VerificationAttestationRecord;
use App\Domain\Election\Security\VotingSessionTrustContinuity;
use App\Models\Election;
use Tests\TestCase;

class RegistrarAttestationElevationTest extends TestCase
{
    use \Illuminate\Foundation\Testing\RefreshDatabase;

    private function makeContext(?Election $election = null, ?VerificationAttestationRecord $attestation = null): TrustCapabilityContext
    {
        if (is_null($attestation)) {
            $attestation = new VerificationAttestationRecord(
                false, false, null, null, 'none', null, null, false, TrustValidityScope::ElectionScoped
            );
        }

        return new TrustCapabilityContext(
            election: $election,
            user: null,
            network: new NetworkTrustEvidence('hash', null, null, 6, 1, true, 'ip_count'),
            device: new DeviceTrustContext(null, null, FingerprintMatchType::NotRequired, 'none', 'stable'),
            attestation: $attestation,
            sessionContinuity: new VotingSessionTrustContinuity('s1', 'h', 'h', false, 'continuous'),
        );
    }

    public function test_evaluate_returns_continue_when_no_registrar(): void
    {
        $overlay = new RegistrarAttestationElevation();
        $election = Election::factory()->create();
        $attestation = new VerificationAttestationRecord(
            true, true, null, new \DateTimeImmutable(), 'both', null, null, false, TrustValidityScope::ElectionScoped
        );
        $ctx = $this->makeContext($election, $attestation);

        $signal = $overlay->evaluate($ctx);

        $this->assertEquals(OverlayInfluence::CONTINUE_UNCHANGED, $signal->influence);
    }

    public function test_evaluate_returns_elevation_request_when_registrar_present(): void
    {
        $overlay = new RegistrarAttestationElevation();
        $election = Election::factory()->create();
        $attestation = new VerificationAttestationRecord(
            true, true, 'registrar_abc', new \DateTimeImmutable(), 'both', null, null, false, TrustValidityScope::ElectionScoped
        );
        $ctx = $this->makeContext($election, $attestation);

        $signal = $overlay->evaluate($ctx);

        $this->assertEquals(OverlayInfluence::TRUST_ELEVATION_REQUEST, $signal->influence);
        $this->assertEquals(TrustLevel::RegistrarAttested, $signal->suggestedElevatedTrustLevel);
    }

    public function test_evaluate_returns_continue_when_attestation_not_satisfied(): void
    {
        $overlay = new RegistrarAttestationElevation();
        $election = Election::factory()->create();
        $attestation = new VerificationAttestationRecord(
            true, false, 'registrar_abc', null, 'both', null, null, false, TrustValidityScope::ElectionScoped
        );
        $ctx = $this->makeContext($election, $attestation);

        $signal = $overlay->evaluate($ctx);

        $this->assertEquals(OverlayInfluence::CONTINUE_UNCHANGED, $signal->influence);
    }

    public function test_evaluate_returns_continue_when_attestation_revoked(): void
    {
        $overlay = new RegistrarAttestationElevation();
        $election = Election::factory()->create();
        $attestation = new VerificationAttestationRecord(
            true, true, 'registrar_abc', new \DateTimeImmutable(), 'both', null, null, true, TrustValidityScope::ElectionScoped
        );
        $ctx = $this->makeContext($election, $attestation);

        $signal = $overlay->evaluate($ctx);

        $this->assertEquals(OverlayInfluence::CONTINUE_UNCHANGED, $signal->influence);
    }

    public function test_identifier_matches_registry(): void
    {
        $overlay = new RegistrarAttestationElevation();

        $this->assertEquals('registrar_attestation_elevation', $overlay->identifier());
    }
}

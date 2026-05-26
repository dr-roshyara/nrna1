<?php

namespace Tests\Unit\Domain\Election\Security;

use App\Application\Election\Security\ConstitutionalTrustSnapshot;
use App\Domain\Election\Security\OverlayInfluenceContext;
use App\Domain\Election\Security\TrustEvaluationEnvelope;
use App\Domain\Election\Security\VotingTrustResult;
use PHPUnit\Framework\TestCase;

class TrustEvaluationEnvelopeTest extends TestCase
{
    public function test_envelope_bundles_result_and_overlay_influence(): void
    {
        $result = VotingTrustResult::allow(
            \App\Domain\Election\Security\TrustLevel::Attested,
            [],
            []
        );
        $overlay = OverlayInfluenceContext::noInfluence();
        $snapshot = new ConstitutionalTrustSnapshot(
            true,
            \App\Domain\Election\Security\TrustLevel::Attested,
            'single_code',
            false,
            false,
            true,
            'none',
            true,
            null,
            null,
            '',
            [],
        );

        $envelope = new TrustEvaluationEnvelope($result, $overlay, $snapshot);

        $this->assertSame($result, $envelope->result);
        $this->assertSame($overlay, $envelope->overlayInfluence);
        $this->assertSame($snapshot, $envelope->snapshot);
    }

    public function test_envelope_has_no_authority_deriving_methods(): void
    {
        $envelope = new TrustEvaluationEnvelope(
            VotingTrustResult::allow(
                \App\Domain\Election\Security\TrustLevel::Attested,
                [],
                []
            ),
            OverlayInfluenceContext::noInfluence(),
            new ConstitutionalTrustSnapshot(true, \App\Domain\Election\Security\TrustLevel::Attested, 'single_code', false, false, true, 'none', true, null, null, '', []),
        );

        $this->assertFalse(method_exists($envelope, 'canVote'));
        $this->assertFalse(method_exists($envelope, 'isAuthorized'));
        $this->assertFalse(method_exists($envelope, 'authorize'));
        $this->assertFalse(method_exists($envelope, 'grant'));
        $this->assertFalse(method_exists($envelope, 'deny'));
        $this->assertFalse(method_exists($envelope, 'recalculate'));
    }

    public function test_envelope_is_readonly(): void
    {
        $envelope = new TrustEvaluationEnvelope(
            VotingTrustResult::allow(
                \App\Domain\Election\Security\TrustLevel::Attested,
                [],
                []
            ),
            OverlayInfluenceContext::noInfluence(),
            new ConstitutionalTrustSnapshot(true, \App\Domain\Election\Security\TrustLevel::Attested, 'single_code', false, false, true, 'none', true, null, null, '', []),
        );

        $this->expectException(\Error::class);
        $envelope->result = VotingTrustResult::deny('test', [], []);
    }

    public function test_envelope_trust_result_and_overlay_accessible_independently(): void
    {
        $result = VotingTrustResult::allow(
            \App\Domain\Election\Security\TrustLevel::Attested,
            ['key' => 'value'],
            ['policy' => 'passed']
        );
        $overlay = OverlayInfluenceContext::noInfluence();
        $snapshot = new ConstitutionalTrustSnapshot(true, \App\Domain\Election\Security\TrustLevel::Attested, 'single_code', false, false, true, 'none', true, null, null, '', []);

        $envelope = new TrustEvaluationEnvelope($result, $overlay, $snapshot);

        $this->assertTrue($envelope->result->trusted);
        $this->assertEquals('value', $envelope->result->auditContext['key']);
        $this->assertFalse($envelope->overlayInfluence->hasInfluence);
    }
}

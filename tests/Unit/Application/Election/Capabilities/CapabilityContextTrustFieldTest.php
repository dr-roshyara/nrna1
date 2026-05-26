<?php

namespace Tests\Unit\Application\Election\Capabilities;

use App\Application\Election\Capabilities\CapabilityContext;
use App\Application\Election\Security\ConstitutionalTrustSnapshot;
use App\Domain\Election\Enum\ElectionLifecycleState;
use App\Domain\Election\Security\OverlayInfluenceContext;
use App\Domain\Election\Security\TrustEvaluationEnvelope;
use App\Domain\Election\Security\TrustLevel;
use App\Domain\Election\Security\VotingTrustResult;
use PHPUnit\Framework\TestCase;

class CapabilityContextTrustFieldTest extends TestCase
{
    public function test_trust_field_is_nullable_default_null(): void
    {
        $context = new CapabilityContext(
            election: null,
            user: null,
            action: 'view_election',
            actionMetadata: [],
            state: ElectionLifecycleState::VotingActive,
        );

        $this->assertNull($context->trust);
    }

    public function test_trust_field_accepts_trust_evaluation_envelope(): void
    {
        $envelope = new TrustEvaluationEnvelope(
            VotingTrustResult::allow(TrustLevel::Attested, [], []),
            OverlayInfluenceContext::noInfluence(),
            new ConstitutionalTrustSnapshot(true, TrustLevel::Attested, 'single_code', false, false, true, 'none', true, null, null, '', []),
        );

        $context = new CapabilityContext(
            election: null,
            user: null,
            action: 'vote',
            actionMetadata: [],
            state: ElectionLifecycleState::VotingActive,
            trust: $envelope,
        );

        $this->assertSame($envelope, $context->trust);
    }

    public function test_non_voting_context_has_null_trust(): void
    {
        $context = new CapabilityContext(
            election: null,
            user: null,
            action: 'view_results',
            actionMetadata: [],
            state: ElectionLifecycleState::ResultsPublished,
        );

        $this->assertNull($context->trust);
    }

    public function test_voting_context_carries_full_envelope(): void
    {
        $envelope = new TrustEvaluationEnvelope(
            VotingTrustResult::allow(TrustLevel::Attested, ['key' => 'value'], []),
            OverlayInfluenceContext::noInfluence(),
            new ConstitutionalTrustSnapshot(true, TrustLevel::Attested, 'single_code', false, false, true, 'none', true, null, null, '', []),
        );

        $context = new CapabilityContext(
            election: null,
            user: null,
            action: 'vote',
            actionMetadata: [],
            state: ElectionLifecycleState::VotingActive,
            trust: $envelope,
        );

        $this->assertNotNull($context->trust);
        $this->assertTrue($context->trust->result->trusted);
        $this->assertEquals('value', $context->trust->result->auditContext['key']);
    }
}

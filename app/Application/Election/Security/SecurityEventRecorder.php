<?php

namespace App\Application\Election\Security;

use App\Domain\Election\Security\OverlayInfluenceContext;
use App\Domain\Election\Security\VotingTrustResult;
use App\Models\ElectionSecurityEvent;
use Illuminate\Support\Facades\Log;

final class SecurityEventRecorder
{
    // Fire-and-forget audit recording
    // Invariant 8: This method returns void. Never throws. Never affects trust outcome.
    // DENY events: always record immediately
    // ALLOW events: ~10% sampled (or dispatch queue job for non-blocking)

    public function record(VotingTrustResult $result, TrustCapabilityContext $ctx, ?OverlayInfluenceContext $overlayInfluence = null): void
    {
        try {
            // Always record DENY events, sample ALLOW events at configured rate
            $sampleRate = (float) env('TRUST_EVENT_SAMPLE_RATE', 0.1);
            if (!$result->trusted || rand(0, 100) / 100 <= $sampleRate) {
                $this->writeEvent($result, $ctx, $overlayInfluence);
            }
        } catch (\Exception $e) {
            // Never propagate audit failures — log and continue
            Log::warning('ElectionSecurityEvent recording failed', [
                'election_id' => $ctx->election?->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function writeEvent(VotingTrustResult $result, TrustCapabilityContext $ctx, ?OverlayInfluenceContext $overlayInfluence = null): void
    {
        // Determine event type from result
        $eventType = match (true) {
            !$result->trusted && str_contains($result->reason, 'network') => 'network_limit_exceeded',
            !$result->trusted && str_contains($result->reason, 'device') => 'device_mismatch',
            !$result->trusted && str_contains($result->reason, 'verification') => 'verification_required',
            !$result->trusted && str_contains($result->reason, 'overlay') => 'overlay_activation',
            !$result->trusted => 'trust_denied',
            default => 'trust_allowed',
        };

        // Build transition description
        $trustStateTransition = sprintf(
            '%s → %s',
            'unverified', // assumed before evaluation
            $result->trustLevel->value,
        );

        // Build overlay causality chain
        $overlayInfluenceChain = [];
        $firstActiveOverlay = null;
        if ($overlayInfluence && count($overlayInfluence->signals) > 0) {
            foreach ($overlayInfluence->signals as $signal) {
                if ($signal->influence->value !== 'continue_unchanged') {
                    $overlayInfluenceChain[$signal->overlayIdentifier] = $signal->influence->value;
                    if (is_null($firstActiveOverlay)) {
                        $firstActiveOverlay = $signal->overlayIdentifier;
                    }
                }
            }
        }

        ElectionSecurityEvent::create([
            'event_type' => $eventType,
            'election_id' => $ctx->election?->id,
            'voter_slug_id' => null, // populated by controller
            'network_evidence' => $ctx->network ? [
                'current_ip_hash' => $ctx->network->currentIpHash,
                'restriction_enabled' => $ctx->network->restrictionEnabled,
                'votes_from_this_ip' => $ctx->network->votesFromThisIp,
            ] : [],
            'device_evidence' => $ctx->device ? [
                'fingerprint_match_type' => $ctx->device->matchType->value,
                'volatility' => $ctx->device->volatility,
            ] : [],
            'trust_level_before' => 'unverified',
            'trust_level_after' => $result->trustLevel->value,
            'policy_evaluated' => implode(', ', array_keys($result->policyOutcomeSequence)),
            'overlay_applied' => $firstActiveOverlay,
            'policy_evaluation_sequence' => $result->policyOutcomeSequence,
            'overlay_influence_chain' => $overlayInfluenceChain,
            'trust_state_transition' => $trustStateTransition,
            'final_constitutional_outcome' => $result->trusted ? 'allow' : 'deny',
            'recorded_at' => now(),
        ]);
    }
}

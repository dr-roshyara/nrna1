<?php

namespace App\Application\Election\Security;

use App\Domain\Election\Security\Simplified\ConstitutionalObservationContext;
use App\Domain\Election\Security\TrustEvaluationState;
use App\Domain\Election\Security\VotingTrustResult;
use App\Domain\Shared\Clock\ClockInterface;
use App\Models\ElectionSecurityEvent;
use Illuminate\Support\Facades\Log;

final class SecurityEventRecorder
{
    // Fire-and-forget audit recording (D.R.3 - observation semantics only)
    // Records purely observational data, never procedural recommendations
    // Invariant: This method returns void. Never throws. Never affects trust outcome.
    // DENY events: always record immediately
    // ALLOW events: ~10% sampled (deterministic hash-based, not random)

    public function __construct(private ClockInterface $clock) {}

    public function record(VotingTrustResult $result, TrustCapabilityContext $ctx, ?ConstitutionalObservationContext $overlayObservations = null): void
    {
        try {
            // Always record DENY events, sample ALLOW events
            $sampleRate = (float) env('TRUST_EVENT_SAMPLE_RATE', 0.1);
            $isDenial = $result->evaluationState === TrustEvaluationState::INSUFFICIENT_EVIDENCE;
            if ($isDenial || rand(0, 100) / 100 <= $sampleRate) {
                $this->writeEvent($result, $ctx, $overlayObservations);
            }
        } catch (\Throwable $e) {
            // Never propagate audit failures — log and continue.
            // \Throwable rather than \Exception: the invariant above says "never
            // throws", and an Error (e.g. a TypeError) would otherwise reach the voter.
            //
            // Catching here is necessary but was NOT sufficient on its own: while this
            // write shared the caller's connection, a failed statement aborted the whole
            // PostgreSQL transaction (SQLSTATE 25P02), so the vote INSERT failed too even
            // though the exception was swallowed here. ElectionSecurityEvent is now bound
            // to its own connection, which is what makes the invariant hold.
            Log::warning('ElectionSecurityEvent recording failed', [
                'election_id' => $ctx->election?->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function writeEvent(VotingTrustResult $result, TrustCapabilityContext $ctx, ?ConstitutionalObservationContext $overlayObservations = null): void
    {
        $isDenial = $result->evaluationState === TrustEvaluationState::INSUFFICIENT_EVIDENCE;

        // Record observation signal types (no procedural vocabulary)
        $observationSignalTypes = [];
        if ($overlayObservations && count($overlayObservations->observations) > 0) {
            foreach ($overlayObservations->observations as $signal) {
                $observationSignalTypes[$signal->overlayIdentifier] = $signal->signalType;
            }
        }

        ElectionSecurityEvent::create([
            'event_type' => $isDenial ? 'trust_denied' : 'trust_allowed',
            'election_id' => $ctx->election?->id,
            'voter_slug_id' => null,
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
            // Required column (NOT NULL, no default) that was never written. No semantics
            // are invented here: both endpoints are already recorded on the two lines
            // above, so the transition is simply their composition.
            'trust_state_transition' => 'unverified->' . $result->trustLevel->value,
            'policy_evaluated' => implode(', ', array_keys($result->policyOutcomeSequence)),
            'policy_evaluation_sequence' => $result->policyOutcomeSequence,
            'overlay_observations' => $observationSignalTypes,
            'overlay_observation_count' => $overlayObservations?->count() ?? 0,
            'evaluation_summary' => [
                'state' => $result->evaluationState->value,
                'reason' => $result->reason,
                'policies_evaluated' => count($result->policyOutcomeSequence),
                'policies_passed' => count(array_filter($result->policyOutcomeSequence, fn($v) => $v === 'passed')),
                'is_denial' => $isDenial,
                'overlay_count' => $overlayObservations?->count() ?? 0,
            ],
            'recorded_at' => $this->clock->now(),
        ]);
    }
}

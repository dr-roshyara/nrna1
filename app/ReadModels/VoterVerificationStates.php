<?php

namespace App\ReadModels;

use App\Models\Election;
use App\Models\VoterIpVerification;
use App\Models\VoterVerification;

/**
 * Per-voter verification STATES for display (PBDIGIT-213 C4-B, PBDIGIT-250) — never an
 * authorisation source (VotingEntitlementQuery decides). Election-scoped. States only:
 * no IP address, notes or verifier leave this class.
 */
final class VoterVerificationStates
{
    /**
     * Identity-verification evidence state, independent of the policy (PO, 2026-10-04):
     *   verified                active evidence
     *   reverification_required evidence revoked (e.g. after a confirmed suspension)
     *   verification_required   no evidence
     *
     * @param  list<string>  $userIds
     * @return array<string, string>  user_id → state
     */
    public function identity(Election $election, array $userIds): array
    {
        $evidenceStatus = VoterVerification::where('election_id', $election->id)
            ->whereIn('user_id', $userIds)
            ->pluck('status', 'user_id');

        $states = [];
        foreach ($userIds as $userId) {
            $states[$userId] = match ($evidenceStatus[$userId] ?? null) {
                'active'  => 'verified',
                'revoked' => 'reverification_required',
                default   => 'verification_required',
            };
        }

        return $states;
    }

    /**
     * IP-verification state (PBDIGIT-222 evidence): verified when this election holds a
     * capture for the voter, otherwise not_verified.
     *
     * @param  list<string>  $userIds
     * @return array<string, string>  user_id → verified | not_verified
     */
    public function ip(Election $election, array $userIds): array
    {
        $captured = array_flip(VoterIpVerification::where('election_id', $election->id)
            ->whereIn('user_id', $userIds)
            ->pluck('user_id')
            ->all());

        $states = [];
        foreach ($userIds as $userId) {
            $states[$userId] = isset($captured[$userId]) ? 'verified' : 'not_verified';
        }

        return $states;
    }
}

<?php

namespace App\Services;

use App\Models\User;
use App\Models\Code;
use Illuminate\Support\Facades\Log;

class VotingSecurityService
{
    /**
     * Check if IP address control is globally enabled
     *
     * @return bool
     */
    public static function isIpControlEnabled(): bool
    {
        return config('voting_security.control_ip_address', 1) == 1;
    }

    /**
     * Check if user's current IP differs from registered voting IP
     *
     * @param User $user
     * @param string $currentIp
     * @return array
     */
    public static function detectIpChange(User $user, string $currentIp): array
    {
        $result = [
            'ip_control_enabled' => self::isIpControlEnabled(),
            'user_has_ip_restriction' => !is_null($user->voting_ip),
            'ip_changed' => false,
            'is_violation' => false,
            'registered_ip' => $user->voting_ip,
            'current_ip' => $currentIp,
            'can_vote' => true,
            'error_message' => null,
        ];

        // If IP control is disabled globally, user can always vote
        if (!$result['ip_control_enabled']) {
            $result['can_vote'] = true;
            return $result;
        }

        // If user has no IP restriction (voting_ip is null), they can vote from anywhere
        if (!$result['user_has_ip_restriction']) {
            $result['can_vote'] = true;
            return $result;
        }

        // At this point: IP control is ON and user HAS voting_ip set
        // Check if IPs match
        $result['ip_changed'] = $user->voting_ip !== $currentIp;
        $result['is_violation'] = $result['ip_changed'];

        if ($result['is_violation']) {
            $result['can_vote'] = false;
            $result['error_message'] = sprintf(
                "IP mismatch detected. Registered: %s, Current: %s",
                $user->voting_ip,
                $currentIp
            );

            Log::warning('Voting IP violation detected', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'registered_ip' => $user->voting_ip,
                'current_ip' => $currentIp,
                'timestamp' => now(),
            ]);
        }

        return $result;
    }

    /**
     * Get comprehensive IP audit trail for a user
     *
     * @param User $user
     * @return array
     */
    public static function getIpAuditTrail(User $user): array
    {
        $code = $user->code;
        $currentIp = request()->ip();

        return [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_ip' => $user->user_ip,                    // IP at registration/login
            'voting_ip' => $user->voting_ip,                // IP restriction (if enabled)
            'code_client_ip' => $code->client_ip ?? null,   // IP when code created
            'current_request_ip' => $currentIp,             // Current request IP
            'ip_control_enabled' => self::isIpControlEnabled(),
            'user_has_ip_restriction' => !is_null($user->voting_ip),
            'ip_match_status' => self::getIpMatchStatus($user, $currentIp),
            'can_vote_from_current_ip' => self::canVoteFromIp($user, $currentIp),
        ];
    }

    /**
     * Check if user can vote from the given IP address
     *
     * @param User $user
     * @param string $ip
     * @return bool
     */
    public static function canVoteFromIp(User $user, string $ip): bool
    {
        // If IP control is disabled, anyone can vote from anywhere
        if (!self::isIpControlEnabled()) {
            return true;
        }

        // If user has no IP restriction, they can vote from anywhere
        if (is_null($user->voting_ip)) {
            return true;
        }

        // User has IP restriction - must match
        return $user->voting_ip === $ip;
    }

    /**
     * Get IP match status description
     *
     * @param User $user
     * @param string $currentIp
     * @return string
     */
    protected static function getIpMatchStatus(User $user, string $currentIp): string
    {
        if (!self::isIpControlEnabled()) {
            return 'IP_CONTROL_DISABLED';
        }

        if (is_null($user->voting_ip)) {
            return 'NO_RESTRICTION';
        }

        if ($user->voting_ip === $currentIp) {
            return 'MATCH';
        }

        return 'MISMATCH';
    }

    /**
     * Determine how a voter should be approved based on global IP control setting
     *
     * @param User $voter
     * @return array Configuration for approval
     */
    public static function getApprovalConfig(User $voter): array
    {
        $ipControlEnabled = self::isIpControlEnabled();

        return [
            'should_set_voting_ip' => $ipControlEnabled,
            'voting_ip_value' => $ipControlEnabled ? $voter->user_ip : null,
            'ip_control_enabled' => $ipControlEnabled,
            'message' => $ipControlEnabled
                ? 'Voter approved with IP restriction'
                : 'Voter approved without IP restriction',
        ];
    }

    /**
     * Log security event for voting
     *
     * @param User $user
     * @param string $event
     * @param array $context
     * @return void
     */
    public static function logSecurityEvent(User $user, string $event, array $context = []): void
    {
        if (!config('voting_security.logging.enabled', true)) {
            return;
        }

        Log::channel('security')->info("Voting Security Event: {$event}", array_merge([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'timestamp' => now(),
        ], $context));
    }

    /**
     * Get human-readable IP control status
     *
     * @return array
     */
    public static function getSystemStatus(): array
    {
        return [
            'ip_control_enabled' => self::isIpControlEnabled(),
            'validation_mode' => config('voting_security.ip_validation_mode', 'strict'),
            'mismatch_action' => config('voting_security.ip_mismatch_action', 'block'),
            'logging_enabled' => config('voting_security.logging.enabled', true),
            'status_message' => self::isIpControlEnabled()
                ? 'IP address validation is ENABLED. Voters with IP restrictions must vote from their registered IP.'
                : 'IP address validation is DISABLED. Voters can vote from any IP address.',
        ];
    }

    /**
     * Validate voter eligibility including IP check
     *
     * @param User $user
     * @param string|null $currentIp
     * @return array
     */
    public static function validateVoterEligibility(User $user, ?string $currentIp = null): array
    {
        $currentIp = $currentIp ?? request()->ip();

        $result = [
            'eligible' => true,
            'reasons' => [],
            'ip_check' => null,
        ];

        // Check basic voting eligibility
        if (!$user->is_voter) {
            $result['eligible'] = false;
            $result['reasons'][] = 'User is not a registered voter';
        }

        if (!$user->can_vote) {
            $result['eligible'] = false;
            $result['reasons'][] = 'User is not approved to vote';
        }

        // Check IP restriction if control is enabled
        if (self::isIpControlEnabled() && !is_null($user->voting_ip)) {
            $ipCheck = self::detectIpChange($user, $currentIp);
            $result['ip_check'] = $ipCheck;

            if (!$ipCheck['can_vote']) {
                $result['eligible'] = false;
                $result['reasons'][] = 'IP address mismatch - ' . $ipCheck['error_message'];
            }
        }

        return $result;
    }
}

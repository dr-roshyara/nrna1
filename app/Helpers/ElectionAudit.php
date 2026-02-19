<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

/**
 * Election Audit & Logging Helpers
 *
 * Provides voter activity logging for the election voting workflow.
 * Logs are written to per-person activity files for compliance and auditing.
 */

/**
 * Log voter activity to the voting_audit channel
 *
 * Logs per-person activity with full context for compliance auditing.
 * Used for tracking voting workflow steps, errors, and security events.
 *
 * @param string $action The action being logged (e.g., 'code_verification_started', 'vote_submitted')
 * @param array $context Additional context data
 * @return void
 *
 * @example
 * voter_log('code_verification_started', [
 *     'election_id' => $election->id,
 *     'voter_slug' => $voter->slug,
 *     'ip_address' => request()->ip(),
 * ]);
 *
 * @example
 * voter_log('vote_submitted', [
 *     'election_id' => $election->id,
 *     'voter_slug' => $voter->slug,
 *     'candidate_id' => $candidate->id,
 *     'organisation_id' => session('current_organisation_id'),
 * ]);
 */
function voter_log(string $action, array $context = []): void
{
    try {
        // Get voter identifier from context or session
        $voterId = $context['voter_slug'] ?? $context['user_id'] ?? null;
        $electionId = $context['election_id'] ?? null;
        $organisationId = $context['organisation_id'] ?? session('current_organisation_id');

        // Build base log context
        $logContext = array_merge([
            'timestamp' => Carbon::now()->toIso8601String(),
            'action' => $action,
            'ip_address' => request()->ip(),
            'user_agent' => substr(request()->userAgent() ?? 'unknown', 0, 255),
        ], $context);

        // Determine logging level based on action type
        $level = _getLogLevel($action);

        // Log to voting_audit channel with full context
        Log::channel('voting_audit')->log($level, "Voter Activity: {$action}", $logContext);

        // If it's a security-relevant action, also log to voting_security
        if (_isSecurityRelevant($action)) {
            Log::channel('voting_security')->warning("Security Event: {$action}", $logContext);
        }

    } catch (\Exception $e) {
        // Fail gracefully - don't break voting flow if logging fails
        \Log::error('Failed to log voter activity', [
            'error' => $e->getMessage(),
            'action' => $action,
        ]);
    }
}

/**
 * Determine log level for an action (internal helper)
 *
 * @param string $action
 * @return string
 */
function _getLogLevel(string $action): string
{
    $errorActions = [
        'code_verification_failed',
        'invalid_code_attempt',
        'rate_limit_exceeded',
        'duplicate_vote_attempt',
        'election_ended',
    ];

    $warningActions = [
        'code_verification_started',
        'agreement_accepted',
        'candidate_selected',
        'preview_shown',
    ];

    if (in_array($action, $errorActions)) {
        return 'error';
    }

    if (in_array($action, $warningActions)) {
        return 'warning';
    }

    // Default to info for successful actions
    return 'info';
}

/**
 * Check if an action is security-relevant (internal helper)
 *
 * Security-relevant actions are logged to both audit and security channels
 *
 * @param string $action
 * @return bool
 */
function _isSecurityRelevant(string $action): bool
{
    $securityActions = [
        'invalid_code_attempt',
        'rate_limit_exceeded',
        'duplicate_vote_attempt',
        'vote_submitted', // Important for compliance
        'election_ended_during_voting',
    ];

    return in_array($action, $securityActions);
}

/**
 * Log a vote submission with full context
 *
 * Convenience function for logging vote submissions with all required audit fields
 *
 * @param object $election The election object
 * @param object $voter The voter object
 * @param object $candidate The selected candidate
 * @param string|null $ipAddress Optional IP address
 * @return void
 */
function log_vote_submission($election, $voter, $candidate, ?string $ipAddress = null): void
{
    voter_log('vote_submitted', [
        'election_id' => $election->id,
        'election_title' => $election->title,
        'voter_slug' => $voter->slug,
        'candidate_id' => $candidate->id,
        'candidate_name' => $candidate->name,
        'organisation_id' => session('current_organisation_id'),
        'ip_address' => $ipAddress ?? request()->ip(),
        'timestamp' => Carbon::now()->toIso8601String(),
    ]);
}

/**
 * Log a code verification attempt
 *
 * Tracks code verification attempts for security auditing
 *
 * @param object $election The election object
 * @param string $voterId The voter identifier (slug)
 * @param bool $success Whether verification succeeded
 * @param string|null $reason Optional reason for failure
 * @return void
 */
function log_code_verification($election, string $voterId, bool $success, ?string $reason = null): void
{
    $action = $success ? 'code_verification_succeeded' : 'code_verification_failed';

    $context = [
        'election_id' => $election->id,
        'election_title' => $election->title,
        'voter_slug' => $voterId,
        'organisation_id' => session('current_organisation_id'),
    ];

    if ($reason) {
        $context['failure_reason'] = $reason;
    }

    voter_log($action, $context);
}

/**
 * Log rate limit exceeded event
 *
 * Security-critical logging for rate limit violations
 *
 * @param string $voterId The voter identifier
 * @param string $type The type of rate limit (e.g., 'votes_per_ip', 'code_attempts')
 * @param int $limit The limit that was exceeded
 * @param int $count The actual count
 * @return void
 */
function log_rate_limit_exceeded(string $voterId, string $type, int $limit, int $count): void
{
    voter_log('rate_limit_exceeded', [
        'voter_slug' => $voterId,
        'rate_limit_type' => $type,
        'limit' => $limit,
        'current_count' => $count,
        'organisation_id' => session('current_organisation_id'),
        'ip_address' => request()->ip(),
    ]);
}

/**
 * Log a duplicate vote attempt
 *
 * Security-critical logging for attempted duplicate votes
 *
 * @param object $election The election object
 * @param string $voterId The voter identifier
 * @param object|null $existingVote The existing vote object
 * @return void
 */
function log_duplicate_vote_attempt($election, string $voterId, $existingVote = null): void
{
    $context = [
        'election_id' => $election->id,
        'election_title' => $election->title,
        'voter_slug' => $voterId,
        'organisation_id' => session('current_organisation_id'),
    ];

    if ($existingVote) {
        $context['previous_vote_timestamp'] = $existingVote->created_at->toIso8601String();
    }

    voter_log('duplicate_vote_attempt', $context);
}

<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

/**
 * Election Audit & Logging Helpers
 *
 * Provides per-person voter activity logging organized by organisation and election.
 *
 * Log Structure:
 * storage/logs/organisation_{organisation_id}/{election_name}/{user_id}_{user_name}.log
 *
 * Example:
 * storage/logs/organisation_null/demo_election/10_nab_roshyara.log
 * storage/logs/organisation_1/presidential_2026/42_john_doe.log
 *
 * Each file contains the complete activity trail for one person in one election.
 */

/**
 * Log voter activity to per-person activity file
 *
 * Writes detailed activity logs to organisation/election/person structure
 * for easy auditing and compliance verification.
 *
 * @param string $action The action being logged (e.g., 'vote_submitted', 'code_verification_started')
 * @param array $context Additional context data:
 *     - user_id: The user's ID (required for filename)
 *     - user_name: The user's name for filename (optional, uses 'unknown' if missing)
 *     - election_id: Election ID
 *     - election_name: Election name for directory (required)
 *     - organisation_id: Organisation ID (optional, uses session value if not provided)
 * @return void
 *
 * @example
 * voter_log('vote_submitted', [
 *     'user_id' => 10,
 *     'user_name' => 'Nab Roshyara',
 *     'election_id' => 1,
 *     'election_name' => 'demo_election',
 *     'candidate_id' => 5,
 *     'organisation_id' => null,
 * ]);
 * // Creates: storage/logs/organisation_null/demo_election/10_nab_roshyara.log
 */
function voter_log(string $action, array $context = []): void
{
    try {
        // Extract identifiers
        $userId = $context['user_id'] ?? auth()->id() ?? 'unknown';
        $userName = $context['user_name'] ?? auth()->user()->name ?? 'unknown';
        $electionId = $context['election_id'] ?? 'unknown';
        $electionName = $context['election_name'] ?? 'election';
        $organisationId = $context['organisation_id'] ?? session('current_organisation_id') ?? 'null';

        // Sanitize names for filesystem safety
        $safeUserName = preg_replace('/[^a-zA-Z0-9_-]/', '_', strtolower($userName));
        $safeElectionName = preg_replace('/[^a-zA-Z0-9_-]/', '_', strtolower($electionName));

        // Build directory path: storage/logs/organisation_{org}/{election_name}/
        $logDir = storage_path(
            "logs/organisation_{$organisationId}/" .
            "{$safeElectionName}"
        );

        // Create directory if not exists
        if (!is_dir($logDir)) {
            @mkdir($logDir, 0755, true);
        }

        // Build person-specific log filename: {user_id}_{user_name}.log
        $logFile = "{$logDir}/{$userId}_{$safeUserName}.log";

        // Build log entry with all context
        $logContext = array_merge([
            'timestamp' => now()->format('Y-m-d H:i:s'),
            'action' => $action,
            'user_id' => $userId,
            'user_name' => $userName,
            'election_id' => $electionId,
            'election_name' => $electionName,
            'organisation_id' => $organisationId,
            'ip' => request()->ip(),
            'url' => request()->path(),
        ], $context);

        // Format log entry: [timestamp] ACTION {"context":json}
        $entry = sprintf(
            "[%s] %s %s\n",
            $logContext['timestamp'],
            strtoupper($action),
            json_encode($logContext, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT)
        );

        // Write to person-specific file (atomic write with lock)
        file_put_contents($logFile, $entry, FILE_APPEND | LOCK_EX);

        // Also log to central voting_audit for system-wide monitoring
        Log::channel('voting_audit')->info("Voter Activity: {$action}", [
            'user_id' => $userId,
            'user_name' => $userName,
            'election_id' => $electionId,
            'organisation_id' => $organisationId,
            'log_file' => "organisation_{$organisationId}/{$safeElectionName}/{$userId}_{$safeUserName}.log",
        ]);

    } catch (\Exception $e) {
        // Fail gracefully - don't break voting flow if logging fails
        \Log::error('Failed to write voter activity log', [
            'error' => $e->getMessage(),
            'action' => $action,
            'context' => $context ?? [],
        ]);
    }
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

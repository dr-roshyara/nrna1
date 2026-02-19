<?php

/**
 * Tenant Logging Helpers
 *
 * Provides simple helper functions for tenant-aware logging.
 */

/**
 * Log a message to the tenant-specific log file
 *
 * Creates separate log files for each organisation:
 *     storage/logs/tenant_1.log       # Organisation 1
 *     storage/logs/tenant_2.log       # Organisation 2
 *     storage/logs/tenant_default.log # Default platform users
 *
 * @param  string  $message  The log message
 * @param  array   $context  Additional context data (optional)
 * @return void
 *
 * Usage:
 *     tenant_log('User created', ['user_id' => 123]);
 *     tenant_log('Election updated', ['election_id' => 456]);
 */
function tenant_log(string $message, array $context = []): void
{
    // Get current organisation_id from session or use 'default'
    $organisationId = session('current_organisation_id') ?? 'default';

    // Build log filename
    $logFile = storage_path("logs/tenant_{$organisationId}.log");

    // Build context with user and organisation info
    $fullContext = array_merge($context, [
        'user_id' => auth()->id(),
        'org_id' => $organisationId,
        'timestamp' => now()->toDateTimeString(),
        'ip' => request()->ip(),
    ]);

    // Format log entry
    $entry = sprintf(
        "[%s] %s %s\n",
        now()->toDateTimeString(),
        $message,
        json_encode($fullContext)
    );

    // Ensure directory exists
    @mkdir(dirname($logFile), 0755, true);

    // Append to log file
    file_put_contents($logFile, $entry, FILE_APPEND | LOCK_EX);
}

/**
 * Get the current organisation ID from the session
 *
 * Returns the authenticated user's organisation_id or null
 * for default platform users.
 *
 * @return int|null
 *
 * Usage:
 *     $orgId = current_organisation_id();
 *     if ($orgId) {
 *         echo "Organisation: " . $orgId;
 *     } else {
 *         echo "Default platform user";
 *     }
 */
function current_organisation_id(): ?int
{
    return session('current_organisation_id');
}

/**
 * Get the log file path for the current organisation
 *
 * Useful for reading or analyzing logs for a specific org.
 *
 * @return string
 *
 * Usage:
 *     $logFile = current_tenant_log_file();
 *     $logs = file_get_contents($logFile);
 */
function current_tenant_log_file(): string
{
    $organisationId = session('current_organisation_id') ?? 'default';
    return storage_path("logs/tenant_{$organisationId}.log");
}

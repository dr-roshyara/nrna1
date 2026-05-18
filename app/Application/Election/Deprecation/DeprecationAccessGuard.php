<?php

namespace App\Application\Election\Deprecation;

use App\Exceptions\DeprecatedFieldException;
use Illuminate\Support\Facades\Log;

/**
 * DeprecationAccessGuard: Runtime Firewall
 *
 * Intercepts access to deprecated fields and enforces deprecation rules.
 * This is the enforcement mechanism of the Deprecation Enforcement Layer (DEL).
 *
 * Three modes:
 * - audit_only: Log silently, no blocking
 * - warning: Log as warning, allow usage
 * - strict: Block all access with exception
 */
final class DeprecationAccessGuard
{
    /**
     * Check if a field access should be allowed/warned/blocked.
     *
     * @param string $field Field name being accessed
     * @param string $context Where the access is happening (e.g., 'ElectionReadModel::status')
     * @param string $mode Override severity mode (optional)
     * @throws DeprecatedFieldException If strict mode and field is deprecated
     */
    public function checkFieldAccess(
        string $field,
        string $context,
        ?string $mode = null
    ): void {
        // If field is not deprecated, allow silently
        if (!DeprecationPolicy::isDeprecated($field)) {
            return;
        }

        // Determine effective severity
        $severity = $mode ?? DeprecationPolicy::getSeverity($field);
        $rule = DeprecationPolicy::getRule($field);

        // Log the access (except in strict mode where we'll throw)
        $this->logAccess($field, $context, $severity);

        // Enforce based on severity
        match ($severity) {
            'audit_only' => null,
            'warning' => null,
            'strict' => throw new DeprecatedFieldException(
                "Field '{$field}' is deprecated and cannot be accessed in strict mode. " .
                "Context: {$context}. " .
                "Replacement: {$rule['replacement']}"
            ),
            default => null,
        };
    }

    /**
     * Log deprecated field access for migration tracking.
     *
     * @param string $field
     * @param string $context
     * @param string $severity
     */
    private function logAccess(string $field, string $context, string $severity): void
    {
        $logData = [
            'field' => $field,
            'context' => $context,
            'severity' => $severity,
        ];

        Log::warning("Deprecated field accessed: {$field}", $logData);
    }
}

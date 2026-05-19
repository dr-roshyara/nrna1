<?php

namespace App\Application\Election\Deprecation;

use App\Exceptions\DeprecatedFieldException;
use App\Application\Election\Monitoring\DriftMonitorInterface;
use App\Application\Election\Monitoring\ConstitutionalMetricsContract;
use App\Application\Election\Monitoring\SSOTViolationEvent;
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
 *
 * Reports all violations to ConstitutionalMetrics for observability.
 */
final class DeprecationAccessGuard
{
    public function __construct(
        private readonly ?DriftMonitorInterface $monitor = null,
        private readonly ?ConstitutionalMetricsContract $metrics = null
    ) {}

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

        // Record to constitutional metrics (observability spine)
        // Always record — metrics layer is always safe
        $this->metrics?->recordDeprecatedFieldAccess($field, $context);

        // Fire to drift monitor (Stream 4)
        $this->monitor?->record(SSOTViolationEvent::make(
            'deprecated_field_access',
            'deprecation_access',
            $context
        ));

        // Enforce based on severity and graduated level
        // Level 4 = full strict (access guard enforces)
        $shouldThrow = $severity === 'strict' && DeprecationPolicy::isEnforcementActive(4);

        if ($shouldThrow) {
            throw new DeprecatedFieldException(
                "Field '{$field}' is deprecated and cannot be accessed in strict mode. " .
                "Context: {$context}. " .
                "Replacement: {$rule['replacement']}"
            );
        }
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

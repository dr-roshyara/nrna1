<?php

namespace App\Application\Election\Deprecation;

/**
 * DeprecationPolicy: Configuration Truth + Graduated Strict Activation
 *
 * Defines which fields are deprecated and supports graduated strict enforcement.
 * This is the single source of truth for deprecation rules and activation levels.
 *
 * Graduated activation (Phase 3.2 sensor calibration):
 * - Level 0: warning mode (current) — log violations, observe compliance
 * - Level 1: metrics strict — enforce metrics recording, guards log warnings
 * - Level 2: query guard strict — enforce query protection, lifecycle logs warnings
 * - Level 3: lifecycle strict — enforce lifecycle rules, access guard logs warnings
 * - Level 4: full strict — enforce everything, all violations throw exceptions
 *
 * Each level runs 12-24h observation before advancing to the next.
 */
final class DeprecationPolicy
{
    /**
     * Deprecated fields with replacement guidance.
     *
     * @var array<string, array{severity: string, replacement: string}>
     */
    public const FIELDS = [
        'status' => [
            'severity' => 'warning',
            'replacement' => 'ElectionLifecycleEngine::compute($election)->state->value',
        ],
        'is_active' => [
            'severity' => 'strict',
            'replacement' => 'ElectionLifecycleEngine::compute($election)->isActive()',
        ],
    ];

    /**
     * Graduated strict activation level (Phase 3.2 sensor calibration).
     *
     * Controls which enforcement layers are active:
     * - 0: warning mode (legacy)
     * - 1: metrics strict (safe baseline)
     * - 2: query guard strict (medium pressure)
     * - 3: lifecycle strict (domain boundary)
     * - 4: full strict (all enforcement active)
     *
     * Each level increment represents a ~24h observation window at the previous level.
     *
     * ACTIVATED: 2026-05-20 11:00 UTC (Phase 3.2 Level 1 - Metrics Strict)
     */
    public const STRICT_LEVEL = 1;

    /**
     * Get the deprecation rule for a field.
     *
     * @param string $field Field name
     * @return array|null Rule array with severity and replacement, or null if not deprecated
     */
    public static function getRule(string $field): ?array
    {
        return self::FIELDS[$field] ?? null;
    }

    /**
     * Check if a field is deprecated.
     *
     * @param string $field Field name
     * @return bool
     */
    public static function isDeprecated(string $field): bool
    {
        return isset(self::FIELDS[$field]);
    }

    /**
     * Get the effective severity for a field.
     *
     * Returns field-level severity, adjusted for graduated strict level.
     *
     * @param string $field Field name
     * @return string Severity level: audit_only|warning|strict
     */
    public static function getSeverity(string $field): string
    {
        if (self::STRICT_LEVEL === 0) {
            return 'warning'; // Level 0: warning mode
        }

        $rule = self::getRule($field);
        return $rule['severity'] ?? 'warning';
    }

    /**
     * Check if a specific enforcement layer is active.
     *
     * Used by guards to determine if they should enforce strict mode.
     *
     * @param int $requiredLevel The enforcement level this guard requires (1-4)
     * @return bool True if STRICT_LEVEL >= requiredLevel
     */
    public static function isEnforcementActive(int $requiredLevel): bool
    {
        return self::STRICT_LEVEL >= $requiredLevel;
    }

    /**
     * Get current activation level.
     *
     * @return int Current STRICT_LEVEL (0-4)
     */
    public static function getCurrentLevel(): int
    {
        return self::STRICT_LEVEL;
    }

    /**
     * Get descriptive name for a level.
     *
     * @param int $level
     * @return string
     */
    public static function getLevelName(int $level): string
    {
        return match ($level) {
            0 => 'warning mode',
            1 => 'metrics strict',
            2 => 'query guard strict',
            3 => 'lifecycle strict',
            4 => 'full strict',
            default => 'unknown',
        };
    }
}

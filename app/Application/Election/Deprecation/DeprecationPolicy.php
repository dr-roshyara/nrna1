<?php

namespace App\Application\Election\Deprecation;

/**
 * DeprecationPolicy: Configuration Truth
 *
 * Defines which fields are deprecated and at what severity level.
 * This is the single source of truth for deprecation rules.
 *
 * Severity levels:
 * - audit_only: Log usage, don't throw
 * - warning: Log usage as warning
 * - strict: Throw exception on any access
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
     * Global enforcement mode.
     *
     * Can be overridden per environment:
     * - audit_only: Collect metrics, don't block
     * - warning: Log warnings, allow usage
     * - strict: Block all deprecated usage
     */
    public const MODE = 'warning';

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
     * Uses field-level severity unless global MODE overrides (audit_only takes precedence).
     *
     * @param string $field Field name
     * @return string Severity level: audit_only|warning|strict
     */
    public static function getSeverity(string $field): string
    {
        if (self::MODE === 'audit_only') {
            return 'audit_only';
        }

        $rule = self::getRule($field);
        return $rule['severity'] ?? 'warning';
    }
}

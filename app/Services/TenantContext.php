<?php

declare(strict_types=1);

namespace App\Services;

/**
 * Pure in-memory tenant context holder.
 *
 * This class holds the current tenant ID for the request.
 * It has NO Laravel dependencies — session is only used in middleware.
 * Domain code reads from this, never from session directly.
 *
 * Architecture:
 *   Middleware → TenantContext::set($id) → BelongsToTenant reads via ::get()
 */
final class TenantContext
{
    private static ?string $tenantId = null;

    /**
     * Set the current tenant ID (called by middleware only)
     */
    public static function set(?string $tenantId): void
    {
        self::$tenantId = $tenantId;
    }

    /**
     * Get the current tenant ID
     */
    public static function get(): ?string
    {
        return self::$tenantId;
    }

    /**
     * Check if tenant context is set
     */
    public static function has(): bool
    {
        return self::$tenantId !== null;
    }

    /**
     * Get tenant ID or throw if not set
     */
    public static function require(): string
    {
        return self::$tenantId
            ?? throw new \RuntimeException('Tenant context not set');
    }

    /**
     * Clear the tenant context (for testing)
     */
    public static function clear(): void
    {
        self::$tenantId = null;
    }
}

<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * Resolves the current tenant identity for the executing context.
 *
 * Implementations:
 *   - TenantContext     HTTP: explicit setContext() → auth()->user() → session()
 *   - (future) SystemTenantContext   CLI/jobs: explicit only, no session/auth fallback
 *
 * Use cases MUST NOT depend on this interface — they receive TenantId as a
 * constructor/parameter. Only HTTP controllers and middleware should use this.
 */
interface TenantContextInterface
{
    /**
     * @throws \RuntimeException When no tenant context can be determined
     */
    public function currentTenantId(): TenantId;
}

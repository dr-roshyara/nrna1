<?php

namespace App\Http\Middleware;

use App\Models\UserOrganisationRole;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * TenantContext Middleware - IMPROVED VERSION
 *
 * Sets the current organisation context for the authenticated user.
 *
 * What it does:
 * 1. Retrieves user's organisation_id (with caching for performance)
 * 2. Validates user actually has access to this organisation
 * 3. Stores it in session and container for BelongsToTenant global scope
 * 4. Logs tenant context with safe channel fallback
 *
 * Security improvements over v1:
 * - Validates user->organisation_id relationship exists in user_organisation_roles
 * - Caches organisation context to reduce DB queries
 * - Checks logging channel before using (prevents silent failures)
 * - Type hints on all methods
 * - Comprehensive access validation prevents privilege escalation
 *
 * Usage:
 * This should be registered in app/Http/Kernel.php in the $middlewareGroups['web']
 *
 * Protected middleware will have the current tenant context available:
 *     session('current_organisation_id')  // Returns org_id or null
 */
class TenantContext
{
    /**
     * Handle the request - sets current organisation context.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (!auth()->check()) {
            return $next($request);
        }

        $userId = auth()->id();

        // Get organisation ID with caching (60 seconds) - reduces N+1 queries
        $organisationId = Cache::remember(
            "user.{$userId}.organisation_id",
            60,
            fn() => auth()->user()->organisation_id
        );

        // Critical: Verify user has access to this organisation
        // Prevents privilege escalation if organisation_id is tampered with
        if ($organisationId && !$this->userHasAccess($userId, $organisationId)) {
            Log::warning('Tenant access denied - user lacks organisation role', [
                'user_id' => $userId,
                'organisation_id' => $organisationId,
                'ip' => $request->ip(),
            ]);
            abort(403, 'You do not have access to this organisation.');
        }

        // Store in session and container for downstream use
        session(['current_organisation_id' => $organisationId]);
        app()->instance('current.organisation_id', $organisationId);

        // Log tenant context with safe channel fallback
        $this->logTenantContext($request, $userId, $organisationId);

        return $next($request);
    }

    /**
     * Verify user has a role in the organisation.
     *
     * This is the critical security check - ensures user is an actual member
     * of the organisation before allowing tenant-scoped queries.
     *
     * @param string $userId
     * @param string $organisationId
     * @return bool
     */
    private function userHasAccess(string $userId, string $organisationId): bool
    {
        return UserOrganisationRole::where('user_id', $userId)
            ->where('organisation_id', $organisationId)
            ->exists();
    }

    /**
     * Log tenant context with fallback channel.
     *
     * Prevents silent failures when logging channel is misconfigured.
     *
     * @param Request $request
     * @param string $userId
     * @param string|null $organisationId
     * @return void
     */
    private function logTenantContext(Request $request, string $userId, ?string $organisationId): void
    {
        $logData = [
            'user_id' => $userId,
            'mode' => $organisationId ? "ORG: {$organisationId}" : 'PUBLIC_DEMO',
            'url' => $request->url(),
            'ip' => $request->ip(),
        ];

        // Use configured log channel or fallback to default
        $channel = config('tenant.log_channel', 'voting_audit');

        if (config("logging.channels.{$channel}")) {
            Log::channel($channel)->info('Tenant context set', $logData);
        } else {
            Log::info('Tenant context set (fallback - channel not configured)', $logData);
        }
    }
}

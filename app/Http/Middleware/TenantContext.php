<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Auth\Middleware\Authenticate;

/**
 * TenantContext Middleware
 *
 * Sets the current organisation context for the authenticated user.
 *
 * What it does:
 * 1. Gets the authenticated user's organisation_id
 * 2. Stores it in the session for the request lifecycle
 * 3. Makes it available to the BelongsToTenant global scope
 * 4. Configures logging for the tenant
 *
 * The organisation_id is retrieved from:
 * - auth()->user()->organisation_id (can be null for default platform users)
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
     * Handle the request
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Only set tenant context for authenticated users
        if (auth()->check()) {
            $organisationId = auth()->user()->organisation_id;

            // Store in session for use throughout request
            session(['current_organisation_id' => $organisationId]);

            // Store in container for global access
            app()->instance('current.organisation_id', $organisationId);

            // Log which mode we're in
            try {
                \Log::channel('voting_audit')->info('Tenant context set', [
                    'user_id' => auth()->id(),
                    'user_name' => auth()->user()->name,
                    'mode' => $organisationId === null ? 'MODE 1 (No Org - Demo)' : 'MODE 2 (Org ' . $organisationId . ' - Live)',
                    'organisation_id' => $organisationId,
                    'url' => $request->url(),
                    'ip' => $request->ip(),
                ]);
            } catch (\Exception $e) {
                // Logging channel may not be configured yet, fail silently
            }
        }

        return $next($request);
    }
}

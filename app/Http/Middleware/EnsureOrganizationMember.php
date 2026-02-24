<?php

namespace App\Http\Middleware;

use App\Models\Organization;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * EnsureOrganization Middleware
 *
 * Generic middleware that validates organization context for all organization-scoped routes.
 *
 * Responsibilities:
 * 1. Extract organization slug from route parameter ({slug}, {organization}, etc.)
 * 2. Resolve organization from database by slug
 * 3. Validate user is a member via user_organization_roles pivot table
 * 4. Store organization in request attributes for controller use
 * 5. Set session context for downstream BelongsToTenant models
 * 6. Return 403 Forbidden for non-members
 * 7. Log unauthorized access attempts
 *
 * Usage in routes:
 *     Route::middleware(['auth', 'verified', 'ensure.organization'])
 *         ->group(function () { ... });
 *
 * Accessing organization in controller:
 *     $organization = $request->attributes->get('organization');
 *     // OR use the route parameter directly
 *     $organization = $request->route('organization') or $request->route('slug')
 *
 * Scopes handled:
 * - Organization pages (dashboard, settings)
 * - Voter management (voters list, approvals)
 * - Election management (create, edit elections)
 * - All organization-scoped resources
 *
 * Note: This middleware validates membership but NOT specific roles.
 * Use Laravel Policies or additional middleware for role-specific access.
 */
class EnsureOrganizationMember
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Ensure user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Try to extract organization slug from route parameters
        $organizationSlug = $request->route('organization') ?? $request->route('slug');

        if (!$organizationSlug) {
            Log::warning('EnsureOrganization: No organization slug found in route', [
                'user_id' => $user->id,
                'route' => $request->route()->getName(),
                'path' => $request->path(),
            ]);

            if ($request->expectsJson()) {
                return response()->json(['error' => 'Organization context not found'], 400);
            }

            return redirect()->route('dashboard')->withErrors(['error' => 'Organization not specified']);
        }

        // Resolve organization from database
        $organization = Organization::where('slug', $organizationSlug)->first();

        if (!$organization) {
            Log::warning('EnsureOrganization: Organization not found', [
                'user_id' => $user->id,
                'slug' => $organizationSlug,
            ]);

            if ($request->expectsJson()) {
                return response()->json(['error' => 'Organization not found'], 404);
            }

            return redirect()->route('dashboard')
                ->withErrors(['error' => __('organizations.messages.not_found')]);
        }

        // Validate user is a member of this organization
        $isMember = $user->organizationRoles()
            ->where('organizations.id', $organization->id)
            ->exists();

        if (!$isMember) {
            Log::warning('EnsureOrganization: Non-member access attempt', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'organization_id' => $organization->id,
                'organization_slug' => $organizationSlug,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            if ($request->expectsJson()) {
                return response()->json(
                    ['error' => 'Access denied: You are not a member of this organization'],
                    403
                );
            }

            return redirect()->route('dashboard')
                ->withErrors(['error' => __('organizations.messages.access_denied')]);
        }

        // Store organization in request attributes for controller use
        $request->attributes->set('organization', $organization);

        // Set session context for BelongsToTenant global scope
        session(['current_organisation_id' => $organization->id]);

        // Log successful access
        Log::channel('voting_audit')->info('Organization context validated', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'organization_id' => $organization->id,
            'organization_slug' => $organizationSlug,
            'path' => $request->path(),
            'ip_address' => $request->ip(),
        ]);

        return $next($request);
    }
}


<?php

namespace App\Http\Middleware;

use App\Models\Organisation;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * EnsureOrganization Middleware
 *
 * Generic middleware that validates organisation context for all organisation-scoped routes.
 *
 * Responsibilities:
 * 1. Extract organisation slug from route parameter ({slug}, {organisation}, etc.)
 * 2. Resolve organisation from database by slug
 * 3. Validate user is a member via user_organisation_roles pivot table
 * 4. Store organisation in request attributes for controller use
 * 5. Set session context for downstream BelongsToTenant models
 * 6. Return 403 Forbidden for non-members
 * 7. Log unauthorized access attempts
 *
 * Usage in routes:
 *     Route::middleware(['auth', 'verified', 'ensure.organisation'])
 *         ->group(function () { ... });
 *
 * Accessing organisation in controller:
 *     $organisation = $request->attributes->get('organisation');
 *     // OR use the route parameter directly
 *     $organisation = $request->route('organisation') or $request->route('slug')
 *
 * Scopes handled:
 * - organisation pages (dashboard, settings)
 * - Voter management (voters list, approvals)
 * - Election management (create, edit elections)
 * - All organisation-scoped resources
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

        // Try to extract organisation slug from route parameters
        $organisationSlug = $request->route('organisation') ?? $request->route('slug');

        if (!$organisationSlug) {
            Log::warning('EnsureOrganization: No organisation slug found in route', [
                'user_id' => $user->id,
                'route' => $request->route()->getName(),
                'path' => $request->path(),
            ]);

            if ($request->expectsJson()) {
                return response()->json(['error' => 'organisation context not found'], 400);
            }

            return redirect()->route('dashboard')->withErrors(['error' => 'organisation not specified']);
        }

        // Resolve organisation from database
        $organisation = Organisation::where('slug', $organisationSlug)->first();

        if (!$organisation) {
            Log::warning('EnsureOrganization: organisation not found', [
                'user_id' => $user->id,
                'slug' => $organisationSlug,
            ]);

            if ($request->expectsJson()) {
                return response()->json(['error' => 'organisation not found'], 404);
            }

            return redirect()->route('dashboard')
                ->withErrors(['error' => __('organisations.messages.not_found')]);
        }

        // Validate user is a member of this organisation
        $isMember = $user->organisationRoles()
            ->where('organisations.id', $organisation->id)
            ->exists();

        if (!$isMember) {
            Log::warning('EnsureOrganization: Non-member access attempt', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'organisation_id' => $organisation->id,
                'organisation_slug' => $organisationSlug,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            if ($request->expectsJson()) {
                return response()->json(
                    ['error' => 'Access denied: You are not a member of this organisation'],
                    403
                );
            }

            return redirect()->route('dashboard')
                ->withErrors(['error' => __('organisations.messages.access_denied')]);
        }

        // Store organisation in request attributes for controller use
        $request->attributes->set('organisation', $organisation);

        // Set session context for BelongsToTenant global scope
        session(['current_organisation_id' => $organisation->id]);

        // Log successful access
        Log::channel('voting_audit')->info('organisation context validated', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'organisation_id' => $organisation->id,
            'organisation_slug' => $organisationSlug,
            'path' => $request->path(),
            'ip_address' => $request->ip(),
        ]);

        return $next($request);
    }
}


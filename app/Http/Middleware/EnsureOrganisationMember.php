<?php

namespace App\Http\Middleware;

use App\Models\Organisation;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * EnsureOrganisation Middleware
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
class EnsureOrganisationMember
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

        // Try to extract organisation identifier from route parameters
        $organisationParam = $request->route('organisation') ?? $request->route('slug');

        // Handle both model instances (from implicit binding) and string identifiers
        if ($organisationParam instanceof Organisation) {
            $organisation = $organisationParam;
        } else {
            $organisationIdentifier = $organisationParam;

            if (!$organisationIdentifier) {
                Log::warning('EnsureOrganisation: No organisation identifier found in route', [
                    'user_id' => $user->id,
                    'route' => $request->route()->getName(),
                    'path' => $request->path(),
                ]);

                if ($request->expectsJson()) {
                    return response()->json(['error' => 'organisation context not found'], 400);
                }

                return redirect()->route('dashboard')->withErrors(['error' => 'organisation not specified']);
            }

            // Resolve organisation from database by UUID or slug
            $organisation = $this->resolveOrganisation($organisationIdentifier);
        }

        if (!$organisation) {
            Log::warning('EnsureOrganisation: organisation not found', [
                'user_id' => $user->id,
                'identifier' => $organisationIdentifier,
            ]);

            if ($request->expectsJson()) {
                return response()->json(['error' => 'organisation not found'], 404);
            }

            return redirect()->route('dashboard')
                ->withErrors(['error' => __('organisations.messages.not_found')]);
        }

        // Check if organisation has been soft-deleted
        if ($organisation->trashed()) {
            Log::warning('Attempt to access deleted organisation', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'organisation_id' => $organisation->id,
                'organisation_slug' => $organisation->slug,
            ]);

            if ($request->expectsJson()) {
                return response()->json(['error' => 'organisation no longer available'], 404);
            }

            return redirect()->route('dashboard')
                ->withErrors(['error' => __('organisations.messages.not_found')]);
        }

        // Validate user is a member of this organisation
        $isMember = $user->organisationRoles()
            ->where('organisation_id', $organisation->id)
            ->exists();

        if (!$isMember) {
            Log::warning('EnsureOrganisation: Non-member access attempt', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'organisation_id' => $organisation->id,
                'organisation_slug' => $organisation->slug,
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
            'organisation_slug' => $organisation->slug,
            'path' => $request->path(),
            'ip_address' => $request->ip(),
        ]);

        return $next($request);
    }

    /**
     * Resolve organisation by UUID (first) or slug (fallback)
     * Includes soft-deleted organisations so we can check trashed status
     *
     * @param string $identifier UUID or slug
     * @return Organisation|null
     */
    protected function resolveOrganisation(string $identifier): ?Organisation
    {
        // Try to resolve by UUID first
        if ($this->isValidUuid($identifier)) {
            return Organisation::withTrashed()->find($identifier);
        }

        // Fall back to slug resolution
        return Organisation::withTrashed()->where('slug', $identifier)->first();
    }

    /**
     * Check if string is valid UUID
     *
     * @param string $value
     * @return bool
     */
    protected function isValidUuid(string $value): bool
    {
        return preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $value) === 1;
    }
}


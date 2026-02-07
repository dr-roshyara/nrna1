<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Check if user has required dashboard roles
     * Works with both new pivot-based and legacy committee member system
     *
     * @param Request $request
     * @param Closure $next
     * @param string ...$roles Required roles to access this route
     * @return Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Check if user has ANY of the required dashboard roles
        $userRoles = $user->getDashboardRoles();
        $hasAccess = false;

        foreach ($roles as $requiredRole) {
            if (in_array($requiredRole, $userRoles)) {
                $hasAccess = true;
                break;
            }
        }

        // Special case: Legacy committee members can access commission dashboard
        if (!$hasAccess && in_array('commission', $roles) && $user->is_committee_member) {
            $hasAccess = true;
        }

        if (!$hasAccess) {
            return redirect()->route('role.selection')
                ->with('error', 'You do not have access to this dashboard. Required role(s): ' . implode(', ', $roles));
        }

        // Store current requested role for use in controller
        // (first matching role that user has)
        $currentRole = null;
        foreach ($roles as $requiredRole) {
            if (in_array($requiredRole, $userRoles)) {
                $currentRole = $requiredRole;
                break;
            }
        }

        if (!$currentRole && $user->is_committee_member && in_array('commission', $roles)) {
            $currentRole = 'commission';
        }

        $request->attributes->set('current_role', $currentRole);

        return $next($request);
    }
}

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

        \Log::info('🔐 CheckUserRole Middleware: Processing request', [
            'user_id' => $user?->id,
            'email' => $user?->email,
            'required_roles' => $roles,
            'route' => $request->getPathInfo(),
        ]);

        if (!$user) {
            \Log::warning('🔐 CheckUserRole: No authenticated user, redirecting to login', [
                'route' => $request->getPathInfo(),
            ]);
            return redirect()->route('login');
        }

        // Check if user has ANY of the required dashboard roles
        $userRoles = $user->getDashboardRoles();
        $hasAccess = false;

        \Log::debug('🔐 CheckUserRole: User roles retrieved', [
            'user_id' => $user->id,
            'user_roles' => $userRoles,
            'required_roles' => $roles,
        ]);

        foreach ($roles as $requiredRole) {
            if (in_array($requiredRole, $userRoles)) {
                $hasAccess = true;
                \Log::debug('🔐 CheckUserRole: User has required role', [
                    'user_id' => $user->id,
                    'role' => $requiredRole,
                ]);
                break;
            }
        }

        // Special case: Legacy committee members can access commission dashboard
        if (!$hasAccess && in_array('commission', $roles) && $user->is_committee_member) {
            $hasAccess = true;
            \Log::debug('🔐 CheckUserRole: User is legacy committee member', [
                'user_id' => $user->id,
            ]);
        }

        if (!$hasAccess) {
            \Log::warning('🔐 CheckUserRole: Access DENIED - redirecting to role selection', [
                'user_id' => $user->id,
                'email' => $user->email,
                'required_roles' => $roles,
                'user_roles' => $userRoles,
                'is_committee_member' => $user->is_committee_member ?? false,
            ]);
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

        \Log::info('🔐 CheckUserRole: Access GRANTED', [
            'user_id' => $user->id,
            'email' => $user->email,
            'required_roles' => $roles,
            'assigned_role' => $currentRole,
        ]);

        return $next($request);
    }
}

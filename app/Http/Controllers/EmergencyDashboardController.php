<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

/**
 * EmergencyDashboardController
 *
 * Serves as Level 2 fallback when normal dashboard resolution fails.
 * Provides minimal UI with basic navigation and logout functionality.
 *
 * This controller is designed to:
 * - Minimize database queries
 * - Provide critical functionality (logout, org switching)
 * - Work even when system is partially degraded
 * - Give operations team time to respond to issues
 */
class EmergencyDashboardController extends Controller
{
    /**
     * Show emergency dashboard
     *
     * Called when normal DashboardResolver fails.
     * Renders a minimal, safe dashboard with basic functionality.
     *
     * @param Request $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $emergencyId = uniqid('emerg_', true);

        // Log that emergency dashboard was accessed
        Log::channel(config('login-routing.analytics.channel', 'login'))
            ->warning('Emergency dashboard accessed - normal resolution failed', [
                'emergency_id' => $emergencyId,
                'user_id' => $user->id,
                'email' => $user->email,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'timestamp' => now()->toIso8601String(),
            ]);

        // Try to get basic user data (gracefully fails if DB is unavailable)
        $organisations = $this->getUserOrganisationsSafely($user);
        $basicActions = $this->getBasicActions($user, $organisations);

        return Inertia::render('Emergency/Dashboard', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'organisations' => $organisations,
            'basic_actions' => $basicActions,
            'emergency_id' => $emergencyId,
            'support_email' => config('app.support_email', 'support@publicdigit.com'),
            'timestamp' => now()->format('Y-m-d H:i:s'),
            'message' => 'System is currently in maintenance mode. Some features may be temporarily unavailable. Our team is working to restore normal service.',
        ]);
    }

    /**
     * Safely retrieve user organisations
     *
     * If the organisation query fails (database down, etc.),
     * returns empty array rather than throwing an exception.
     * This allows the emergency dashboard to still load.
     *
     * @param \App\Models\User $user
     * @return array
     */
    protected function getUserOrganisationsSafely($user): array
    {
        try {
            // Attempt to load user organisations (minimal columns)
            if (!method_exists($user, 'organisations')) {
                return [];
            }

            return $user->organisations()
                ->select(['id', 'name', 'slug'])
                ->get()
                ->map(fn($org) => [
                    'id' => $org->id,
                    'name' => $org->name,
                    'slug' => $org->slug,
                    'url' => route('organisations.show', $org->slug) ?? '#',
                ])
                ->toArray();

        } catch (\Throwable $e) {
            Log::warning('EmergencyDashboard: Could not load organisations', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'exception' => get_class($e),
            ]);

            // Return empty array - emergency dashboard will show without org switcher
            return [];
        }
    }

    /**
     * Get basic actions available in emergency mode
     *
     * These are critical functions that should work even in degraded mode:
     * - Logout (always works)
     * - Organisation selector (if orgs loaded successfully)
     * - Support contact link
     *
     * @param \App\Models\User $user
     * @param array $organisations
     * @return array
     */
    protected function getBasicActions($user, array $organisations): array
    {
        $actions = [
            [
                'label' => 'Logout',
                'url' => route('logout'),
                'method' => 'POST',
                'icon' => 'logout',
                'color' => 'red',
                'description' => 'Log out of your account',
            ],
        ];

        // Add organisation switcher if organisations are available
        if (!empty($organisations)) {
            $actions[] = [
                'label' => 'Switch Organisation',
                'url' => null,
                'icon' => 'building',
                'color' => 'blue',
                'description' => 'Switch to a different organisation',
                'organisations' => $organisations,
            ];
        }

        // Add support/contact
        $actions[] = [
            'label' => 'Contact Support',
            'url' => 'mailto:' . config('app.support_email', 'support@publicdigit.com'),
            'icon' => 'question',
            'color' => 'gray',
            'description' => 'Contact the support team',
            'external' => true,
        ];

        return $actions;
    }

    /**
     * Logout endpoint
     *
     * Simple logout that works even if database is partially down.
     * Clears session and logs the action.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Log::channel(config('login-routing.analytics.channel', 'login'))
            ->info('User logged out from emergency dashboard', [
                'user_id' => $request->user()?->id,
                'ip_address' => $request->ip(),
                'timestamp' => now()->toIso8601String(),
            ]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('status', 'You have been logged out.');
    }

    /**
     * Health check endpoint
     *
     * Can be called by monitoring services to check if system is responding.
     * Returns minimal information without accessing database.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function healthCheck()
    {
        return response()->json([
            'status' => 'degraded',
            'message' => 'System is in emergency mode',
            'timestamp' => now()->toIso8601String(),
            'uptime' => app()->uptimeSeconds() ?? null,
        ]);
    }
}

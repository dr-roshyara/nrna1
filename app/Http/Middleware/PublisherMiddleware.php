<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Publisher;
use App\Models\Election;
use Illuminate\Support\Facades\Auth;

class PublisherMiddleware
{
    /**
     * Handle an incoming request for publisher routes
     */
    public function handle(Request $request, Closure $next)
    {
        // First check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to access publisher area.');
        }

        $user = Auth::user();

        // Option 1: Check if user has publisher role (using Spatie Permission)
        if ($user->hasRole('publisher')) {
            // Get the publisher record for this user (lookup by user_id, not email)
            $publisher = Publisher::where('user_id', $user->id)->first();
            
            if (!$publisher) {
                return redirect()->route('dashboard')->with('error', 'Publisher account not found.');
            }

            if (!$publisher->is_active) {
                return redirect()->route('dashboard')->with('error', 'Publisher account is not active.');
            }

            // Get current election
            $election = Election::current();
            if (!$election) {
                return redirect()->route('dashboard')->with('error', 'No active election found.');
            }

            // Check if election is in sealing phase (ready for authorization)
            if ($election->getCurrentPhase() !== 'sealed') {
                return redirect()->route('dashboard')->with('error', 'Election is not ready for publisher authorization.');
            }

            // Add publisher and election to request for controller access
            $request->merge([
                'publisher' => $publisher,
                'current_election' => $election,
            ]);

            return $next($request);
        }

        // Option 2: Check if user has specific permission
        if ($user->hasPermissionTo('authorize-results')) {
            // Similar logic as above (lookup by user_id, not email)
            $publisher = Publisher::where('user_id', $user->id)->first();
            
            if (!$publisher || !$publisher->is_active) {
                return redirect()->route('dashboard')->with('error', 'Publisher access denied.');
            }

            $election = Election::current();
            if (!$election || $election->getCurrentPhase() !== 'sealed') {
                return redirect()->route('dashboard')->with('error', 'Election not ready for authorization.');
            }

            $request->merge([
                'publisher' => $publisher,
                'current_election' => $election,
            ]);

            return $next($request);
        }

        // If neither role nor permission exists, deny access
        return redirect()->route('dashboard')->with('error', 'Access denied. Publisher authorization required.');
    }
}
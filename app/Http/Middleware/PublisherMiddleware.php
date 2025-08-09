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
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to access this page.');
        }

        $user = Auth::user();

        // Check if user is a publisher
        $publisher = Publisher::where('user_id', $user->id)->first();

        if (!$publisher) {
            abort(403, 'Access denied. You are not authorized as a publisher.');
        }

        // Check if publisher is active
        if (!$publisher->is_active) {
            abort(403, 'Your publisher account is inactive. Please contact the election committee.');
        }

        // Check if publisher should participate in authorization
        if (!$publisher->should_agree) {
            return redirect()->route('dashboard')->with('info', 'You are not required to authorize results.');
        }

        // Check if there's an active authorization session
        $election = Election::current();
        
        if (!$election) {
            return redirect()->route('dashboard')->with('error', 'No active election found.');
        }

        // Check if authorization has started
        if (!$election->authorization_started) {
            return redirect()->route('dashboard')->with('info', 'Result authorization has not started yet.');
        }

        // Check if authorization has expired
        if ($election->isAuthorizationExpired()) {
            return redirect()->route('dashboard')->with('error', 'Authorization deadline has expired.');
        }

        // Check if authorization is already complete
        if ($election->authorization_complete) {
            return redirect()->route('dashboard')->with('info', 'Result authorization has been completed.');
        }

        // Add publisher data to request for use in controllers
        $request->merge(['publisher' => $publisher]);
        $request->merge(['current_election' => $election]);

        return $next($request);
    }
}
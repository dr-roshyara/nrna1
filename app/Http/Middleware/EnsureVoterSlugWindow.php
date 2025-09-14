<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\VoterSlug;

class EnsureVoterSlugWindow
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var VoterSlug|null $vslug */
        $vslug = $request->route('vslug');

        if (!$vslug || !$vslug->is_active || $vslug->expires_at->isPast()) {
            abort(403, 'Voting link has expired or is invalid. Please request a new voting link.');
        }

        // Make the user easily accessible to controllers/views
        $request->attributes->set('voter', $vslug->user);
        $request->attributes->set('voter_slug', $vslug);

        // Optional: Update last_accessed timestamp for analytics
        $vslug->touch();

        return $next($request);
    }
}

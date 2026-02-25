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

        // Log the incoming request for debugging
        \Log::info('EnsureVoterSlugWindow middleware check', [
            'url' => $request->url(),
            'vslug_param' => $request->route()->parameter('vslug'),
            'vslug_type' => gettype($vslug),
            'vslug_class' => is_object($vslug) ? get_class($vslug) : 'not_object',
        ]);

        // If vslug is a string, manually resolve it from the database
        // This handles cases where implicit route model binding doesn't work
        if (is_string($vslug)) {
            $vslug = VoterSlug::where('slug', $vslug)->first();
            if (!$vslug) {
                \Log::warning('VoterSlug not found in database', [
                    'url' => $request->url(),
                    'slug' => $request->route()->parameter('vslug'),
                ]);
                abort(403, 'Invalid voting link.');
            }
        }

        // Ensure we have a VoterSlug instance
        if (!$vslug instanceof VoterSlug) {
            \Log::warning('Invalid voting link - not a VoterSlug instance', [
                'url' => $request->url(),
                'vslug_type' => gettype($vslug),
                'vslug_value' => is_string($vslug) ? $vslug : 'not_string',
            ]);
            abort(403, 'Invalid voting link.');
        }

        // Check if slug is active
        if (!$vslug->is_active) {
            \Log::warning('Voting link is not active', [
                'slug' => $vslug->slug,
                'user_id' => $vslug->user_id,
                'is_active' => $vslug->is_active,
                'expires_at' => $vslug->expires_at,
            ]);
            abort(403, 'This voting link has been deactivated. Please request a new voting link.');
        }

        // Check if slug has expired
        if ($vslug->expires_at->isPast()) {
            \Log::warning('Voting link has expired', [
                'slug' => $vslug->slug,
                'user_id' => $vslug->user_id,
                'expires_at' => $vslug->expires_at,
                'now' => now(),
            ]);
            abort(403, 'Voting link has expired. Please request a new voting link.');
        }

        \Log::info('Voting link validated successfully', [
            'slug' => $vslug->slug,
            'user_id' => $vslug->user_id,
        ]);

        // Make the user easily accessible to controllers/views
        $request->attributes->set('voter', $vslug->user);
        $request->attributes->set('voter_slug', $vslug);

        // Optional: Update last_accessed timestamp for analytics
        $vslug->touch();

        return $next($request);
    }
}

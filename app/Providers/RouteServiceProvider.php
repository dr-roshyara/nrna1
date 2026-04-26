<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

/**
 * Senior Architect Note:
 * This provider handles the critical 'vslug' route model binding.
 * It differentiates between production VoterSlugs and DemoVoterSlugs dynamically,
 * validates expiration, and applies rate limiting.
 */
class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     * Used by Laravel authentication to redirect users after login.
     */
    public const HOME = '/dashboard/roles';

    /**
     * Define your route model bindings, pattern filters, etc.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

        // ✅ Election binding now uses scopeBindings() in routes — no custom override needed
        $this->registerVoterSlugBinding();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }


    /**
     * Register the {vslug} route model binding with support for both real and demo slugs.
     *
     * This binding:
     * 1. Detects route context (demo vs production)
     * 2. Resolves to correct model (DemoVoterSlug or VoterSlug)
     * 3. Validates expiration status
     * 4. Applies rate limiting to prevent slug guessing attacks
     * 5. Returns 404 for invalid slugs with security logging
     */
    protected function registerVoterSlugBinding(): void
    {
        Route::bind('vslug', function (string $value) {
            // Rate limit slug lookups to prevent brute force attacks
            $this->throttleSlugLookup();

            // Determine if this is a demo route by checking current route name
            $routeName = request()->route()?->getName();
            $isDemo = $routeName && str_contains($routeName, 'demo');

            // Select appropriate model based on route context
            $modelClass = $isDemo
                ? \App\Models\DemoVoterSlug::class
                : \App\Models\VoterSlug::class;

            // Query using current database connection
            $connection = config('database.default');
            $voterSlug = $modelClass::on($connection)
                ->withoutGlobalScopes()
                ->where('slug', $value)
                ->first();

            if (!$voterSlug) {
                Log::warning('Invalid voting slug lookup', [
                    'slug_prefix' => substr($value, 0, 10),
                    'ip' => request()->ip(),
                    'user_id' => auth()->id(),
                    'is_demo_route' => $isDemo,
                ]);
                abort(404, 'Voting link not found.');
            }

            // Check if slug has expired
            if ($voterSlug->expires_at && $voterSlug->expires_at->isPast()) {
                Log::info('Expired voting slug accessed', [
                    'slug_id' => $voterSlug->id,
                    'expires_at' => $voterSlug->expires_at,
                    'ip' => request()->ip(),
                ]);
                abort(410, 'This voting link has expired.');
            }

            return $voterSlug;
        });
    }

    /**
     * Throttle slug lookup attempts to prevent brute force attacks.
     * Allows 15 attempts per 60 seconds per IP address.
     */
    private function throttleSlugLookup(): void
    {
        $key = 'slug-lookup:' . request()->ip();

        if (RateLimiter::tooManyAttempts($key, 15)) {
            Log::warning('Slug lookup rate limit exceeded', [
                'ip' => request()->ip(),
                'user_id' => auth()->id(),
            ]);
            abort(429, 'Too many attempts. Please wait.');
        }

        RateLimiter::hit($key, 60);
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}

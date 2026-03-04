<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use App\Services\DashboardResolver;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Foundation\Application;
use Laravel\Fortify\Contracts\LoginResponse as FortifyLoginResponse;

/**
 * LoginResponse
 *
 * Implements Laravel Fortify's LoginResponse contract to handle post-login user routing with:
 * - Request ID tracking for audit trails
 * - 3-level fallback chain (Normal → Emergency Dashboard → Static HTML)
 * - Cache management with timeout protection
 * - Analytics logging and failure tracking
 * - Maintenance mode checking
 * - Rate limiting per user
 *
 * This response determines which dashboard/page each user is routed to
 * after successful login based on their roles, organisations, and voting status.
 */
class LoginResponse implements FortifyLoginResponse
{
    /**
     * Unique request ID for this login operation
     * Used to track the login flow through logs
     */
    protected string $requestId;

    /**
     * Timestamp when this response was created
     */
    protected \DateTime $startTime;

    /**
     * Application container
     */
    protected Application $app;

    /**
     * Create a new LoginResponse instance
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->requestId = Str::uuid()->toString();
        $this->startTime = now();
    }

    /**
     * Create an HTTP response that represents the object.
     *
     * Implements a 3-level fallback chain:
     *
     * LEVEL 1 (NORMAL): Try standard dashboard resolution
     *   - Check maintenance mode
     *   - Resolve user's dashboard via DashboardResolver
     *   - Cache the result
     *
     * LEVEL 2 (EMERGENCY): If normal resolution fails, use emergency dashboard
     *   - Shows minimal UI with basic navigation
     *   - Reduces database queries
     *   - Logs warning for ops team
     *
     * LEVEL 3 (FALLBACK): If database is completely down
     *   - Serves static HTML login success page
     *   - No database queries
     *   - Last resort for total outages
     *
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    public function toResponse(Request $request): Response
    {
        $user = $request->user();

        $this->trackLoginStart($user, $request);

        // Check rate limiting (prevent brute force after successful login)
        if (!$this->checkRateLimit($user)) {
            Log::warning('User login rate limit exceeded', [
                'request_id' => $this->requestId,
                'user_id' => $user->id,
                'email' => $user->email,
            ]);
            return redirect()->route('dashboard')->with('error', 'Too many login attempts. Please try again later.');
        }

        try {
            // CRITICAL: Check email verification FIRST
            // Newly registered users MUST verify email before accessing dashboard
            if ($user->email_verified_at === null) {
                Log::channel(config('login-routing.analytics.channel'))
                    ->info('User needs email verification', [
                        'request_id' => $this->requestId,
                        'user_id' => $user->id,
                        'email' => $user->email,
                    ]);

                return redirect()->route('verification.notice');
            }

            // Check maintenance mode
            if ($this->isInMaintenanceMode($user)) {
                return $this->redirectToMaintenanceMode();
            }

            // LEVEL 1: Normal dashboard resolution
            return $this->resolveNormalDashboard($user);

        } catch (\Throwable $e) {
            // Log the failure for debugging
            $this->logResolutionFailure($user, $e);

            // LEVEL 2: Try emergency dashboard
            try {
                return $this->resolveEmergencyDashboard($user);
            } catch (\Throwable $emergencyException) {
                $this->logEmergencyFailure($user, $emergencyException);

                // LEVEL 3: Fallback to static HTML
                return $this->resolveStaticHtmlFallback();
            }
        }
    }

    /**
     * LEVEL 1: Resolve user dashboard through normal routing logic
     *
     * Attempts to:
     * 1. Check cache first (performance)
     * 2. If not cached, resolve via DashboardResolver
     * 3. Cache the result for next login
     *
     * @param \App\Models\User $user
     * @return RedirectResponse
     * @throws \Throwable
     */
    protected function resolveNormalDashboard($user): RedirectResponse
    {
        $cacheKey = config('login-routing.cache.cache_key_prefix') . $user->id;
        $cacheTtl = config('login-routing.cache.dashboard_resolution_ttl', 300);

        // Check cache first
        if ($cached = Cache::get($cacheKey)) {
            Log::info('🎯 LoginResponse: Using cached dashboard URL', [
                'user_id' => $user->id,
                'cached_url' => $cached,
            ]);
            $this->trackCacheHit($user);
            return redirect($cached);
        }

        // Resolve via DashboardResolver
        $redirect = $this->app->make(DashboardResolver::class)->resolve($user);
        $targetUrl = $redirect->getTargetUrl();

        Log::info('🎯 LoginResponse: DashboardResolver returned redirect', [
            'user_id' => $user->id,
            'target_url' => $targetUrl,
            'status_code' => $redirect->getStatusCode(),
        ]);

        // Cache the result
        Cache::put($cacheKey, $targetUrl, $cacheTtl);

        $this->trackLoginSuccess($user, $targetUrl, 'normal');

        return $redirect;
    }

    /**
     * LEVEL 2: Emergency dashboard (reduced-load fallback)
     *
     * When normal resolution fails, shows minimal dashboard with:
     * - Basic navigation
     * - Logout button
     * - Organisation switcher (if available)
     *
     * Reduces database load during partial outages
     *
     * @param \App\Models\User $user
     * @return RedirectResponse
     * @throws \Throwable
     */
    protected function resolveEmergencyDashboard($user): RedirectResponse
    {
        Log::channel(config('login-routing.analytics.channel'))
            ->warning('Using emergency dashboard fallback', [
                'request_id' => $this->requestId,
                'user_id' => $user->id,
                'email' => $user->email,
            ]);

        $this->trackLoginSuccess($user, 'dashboard.emergency', 'emergency');

        return redirect()->route('dashboard.emergency');
    }

    /**
     * LEVEL 3: Static HTML fallback (complete outage)
     *
     * When both normal and emergency resolution fail,
     * serves a pre-rendered HTML success page.
     *
     * This requires NO database queries and works even if
     * database connection is completely down.
     *
     * @return Response
     */
    protected function resolveStaticHtmlFallback(): Response
    {
        Log::channel(config('login-routing.analytics.channel'))
            ->critical('Using static HTML fallback - system severely degraded', [
                'request_id' => $this->requestId,
                'timestamp' => now()->toIso8601String(),
            ]);

        // Return a pre-rendered HTML response
        // This is a simple success page with minimal dependencies
        return response(
            view('auth.login-success-fallback', [
                'message' => 'Login successful. System is performing maintenance.',
            ]),
            200
        );
    }

    /**
     * Check if application is in maintenance mode
     *
     * @param \App\Models\User $user
     * @return bool
     */
    protected function isInMaintenanceMode($user): bool
    {
        if (!config('login-routing.maintenance.check_enabled', true)) {
            return false;
        }

        if (!$this->app->isDownForMaintenance()) {
            return false;
        }

        // Allow specific users to bypass maintenance
        $allowList = config('login-routing.maintenance.allow_user_ids', []);
        return !in_array($user->id, $allowList);
    }

    /**
     * Redirect to maintenance mode page
     *
     * @return RedirectResponse
     */
    protected function redirectToMaintenanceMode(): RedirectResponse
    {
        Log::channel(config('login-routing.analytics.channel'))
            ->info('Redirecting user to maintenance page', [
                'request_id' => $this->requestId,
            ]);

        return redirect()->route(config('login-routing.maintenance.redirect_route', 'maintenance'));
    }

    /**
     * Check if user has exceeded login rate limit
     *
     * Prevents brute force attacks by limiting login attempts per user.
     * Allows 10 logins per hour per user.
     *
     * @param \App\Models\User $user
     * @return bool true if within limit, false if exceeded
     */
    protected function checkRateLimit($user): bool
    {
        if (!config('login-routing.rate_limiting.enabled', true)) {
            return true; // Rate limiting disabled
        }

        // Create cache key for this hour
        $key = 'login_attempts:' . $user->id . ':' . now()->format('Y-m-d-H');
        $maxAttempts = config('login-routing.rate_limiting.max_attempts', 10);
        $window = config('login-routing.rate_limiting.window_minutes', 60) * 60;

        // Increment attempt counter
        $attempts = Cache::increment($key);

        // Set expiry on first attempt
        if ($attempts === 1) {
            Cache::expire($key, $window);
        }

        return $attempts <= $maxAttempts;
    }

    /**
     * Track login start in analytics
     *
     * @param \App\Models\User $user
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    protected function trackLoginStart($user, $request): void
    {
        if (!config('login-routing.analytics.enabled', true)) {
            return;
        }

        Log::channel(config('login-routing.analytics.channel'))
            ->info('Login flow started', [
                'request_id' => $this->requestId,
                'user_id' => $user->id,
                'email' => $user->email,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'timestamp' => $this->startTime->toIso8601String(),
            ]);
    }

    /**
     * Track successful login in analytics
     *
     * @param \App\Models\User $user
     * @param string $targetUrl
     * @param string $resolutionLevel
     * @return void
     */
    protected function trackLoginSuccess($user, string $targetUrl, string $resolutionLevel): void
    {
        $duration = (int) $this->startTime->diffInMilliseconds(now());

        if (!config('login-routing.analytics.enabled', true)) {
            return;
        }

        $level = match($resolutionLevel) {
            'normal' => 'info',
            'emergency' => 'warning',
            default => 'debug',
        };

        Log::channel(config('login-routing.analytics.channel'))
            ->{$level}('Login successful - user routed', [
                'request_id' => $this->requestId,
                'user_id' => $user->id,
                'email' => $user->email,
                'target_url' => $targetUrl,
                'resolution_level' => $resolutionLevel,
                'duration_ms' => $duration,
                'timestamp' => now()->toIso8601String(),
            ]);

        // Check performance thresholds
        $this->checkPerformanceThresholds($duration);
    }

    /**
     * Track cache hit in analytics
     *
     * @param \App\Models\User $user
     * @return void
     */
    protected function trackCacheHit($user): void
    {
        if (!config('login-routing.analytics.track_cache_metrics', true)) {
            return;
        }

        Log::channel(config('login-routing.analytics.channel'))
            ->debug('Dashboard resolution cache hit', [
                'request_id' => $this->requestId,
                'user_id' => $user->id,
            ]);
    }

    /**
     * Track login resolution failure
     *
     * @param \App\Models\User $user
     * @param \Throwable $exception
     * @return void
     */
    protected function logResolutionFailure($user, \Throwable $exception): void
    {
        Log::channel(config('login-routing.analytics.channel'))
            ->error('Dashboard resolution failed (attempting fallback)', [
                'request_id' => $this->requestId,
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $exception->getMessage(),
                'exception' => get_class($exception),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'duration_ms' => $this->startTime->diffInMilliseconds(now()),
            ]);

        // Track failure count for alerting
        $this->trackFailureCount($user);
    }

    /**
     * Track emergency dashboard failure
     *
     * @param \App\Models\User $user
     * @param \Throwable $exception
     * @return void
     */
    protected function logEmergencyFailure($user, \Throwable $exception): void
    {
        Log::channel(config('login-routing.analytics.channel'))
            ->critical('Emergency dashboard also failed (using static fallback)', [
                'request_id' => $this->requestId,
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $exception->getMessage(),
                'exception' => get_class($exception),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'duration_ms' => $this->startTime->diffInMilliseconds(now()),
            ]);

        // Alert operations team immediately
        $this->alertOperationsTeam($user, $exception);
    }

    /**
     * Check if response time exceeds performance thresholds
     *
     * @param int $durationMs
     * @return void
     */
    protected function checkPerformanceThresholds(int $durationMs): void
    {
        if (!config('login-routing.analytics.enabled', true)) {
            return;
        }

        $thresholds = config('login-routing.analytics.performance_thresholds', [
            'warning_ms' => 2000,
            'critical_ms' => 5000,
        ]);

        if ($durationMs >= $thresholds['critical_ms']) {
            Log::channel(config('login-routing.analytics.channel'))
                ->critical('Login resolution took critical time', [
                    'duration_ms' => $durationMs,
                    'threshold_ms' => $thresholds['critical_ms'],
                ]);
        } elseif ($durationMs >= $thresholds['warning_ms']) {
            Log::channel(config('login-routing.analytics.channel'))
                ->warning('Login resolution took longer than expected', [
                    'duration_ms' => $durationMs,
                    'threshold_ms' => $thresholds['warning_ms'],
                ]);
        }
    }

    /**
     * Track login failure count for alerting
     *
     * Uses Redis to count failures in the past hour.
     * Triggers alert if exceeds threshold.
     *
     * @param \App\Models\User $user
     * @return void
     */
    protected function trackFailureCount($user): void
    {
        $failureKey = 'login_failures:' . now()->format('YmdH');
        $currentFailures = Cache::get($failureKey, 0);
        $newCount = $currentFailures + 1;

        // Store with 1-hour expiry
        Cache::put($failureKey, $newCount, 3600);

        $threshold = config('login-routing.fallback.alert_failures_per_hour', 100);

        if ($newCount >= $threshold) {
            $this->alertOperationsTeam(
                $user,
                new \RuntimeException("High login failure rate detected: {$newCount}/hour")
            );
        }
    }

    /**
     * Alert operations team of critical issues
     *
     * Called when:
     * - Both normal and emergency resolution fail
     * - Failure rate exceeds threshold
     *
     * @param \App\Models\User $user
     * @param \Throwable $exception
     * @return void
     */
    protected function alertOperationsTeam($user, \Throwable $exception): void
    {
        // This would typically trigger a notification/alert system
        // Examples: Slack, PagerDuty, email, etc.

        Log::channel('critical-alerts')
            ->critical('LOGIN SYSTEM ALERT - IMMEDIATE ATTENTION REQUIRED', [
                'request_id' => $this->requestId,
                'user_id' => $user->id,
                'error' => $exception->getMessage(),
                'timestamp' => now()->toIso8601String(),
                'action' => 'Check login routing service and database connectivity',
            ]);
    }
}


<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withEvents(discover: [])
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        then: function () {
            // Internal API Routes (v1) — Separated from Inertia web pipeline
            // Order matters: json.api → tenant.api → auth → verified
            // tenant.api MUST come BEFORE auth so context is set before auth checks
            \Illuminate\Support\Facades\Route::middleware(['web', 'json.api', 'tenant.api', 'auth', 'verified'])
                ->prefix('api/v1')
                ->name('api.v1.')
                ->group(base_path('routes/api_v1.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        /*
        |--------------------------------------------------------------------------
        | Global & Web Middleware
        |--------------------------------------------------------------------------
        */
        $middleware->trustProxies(at: '*');

        // ✅ CRITICAL FIX: Append custom global middleware (runs on all routes)
        $middleware->append([
            \App\Http\Middleware\TrackPerformance::class,
        ]);

        // ✅ CRITICAL FIX: TenantContext MUST run BEFORE route model binding
        // Use prependToGroup to insert BEFORE SubstituteBindings (route binding)
        // This ensures tenant context is set BEFORE election model is resolved
        $middleware->web(prepend: [
            \App\Http\Middleware\TenantContext::class,
        ]);

        // ✅ Then append other middleware (runs AFTER binding)
        $middleware->web(append: [
            \App\Http\Middleware\PreloadAssets::class,
            \App\Http\Middleware\SetLocale::class,
            \App\Http\Middleware\InjectPageMeta::class,
            \App\Http\Middleware\HandleInertiaRequests::class,
        ]);

        // ✅ CRITICAL: Session-based API middleware (session auth WITHOUT Inertia)
        // Use this for API endpoints that need session cookies but return JSON
        // Includes TenantContext like web middleware but EXCLUDES Inertia rendering
        $middleware->group('session-api', [
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\TenantContext::class,
            \App\Http\Middleware\SetLocale::class,
        ]);

        // ✅ Enable stateful API authentication (for Sanctum)
        $middleware->statefulApi();

        // ✅ CRITICAL: Exempt /api/v1/* routes from CSRF validation
        // These routes use X-Requested-With header (AJAX), not CSRF tokens
        $middleware->validateCsrfTokens(except: [
            'api/v1/*',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Middleware Aliases (Route Middleware)
        |--------------------------------------------------------------------------
        */
        $middleware->alias([
            // Spatie Permission (FIXED namespace: Middleware not Middlewares)
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,

            // Voting System - Business Logic
            'vote.eligibility' => \App\Http\Middleware\VoteEligibility::class,
            'voter.slug.verify' => \App\Http\Middleware\VerifyVoterSlug::class,
            'voter.slug.window' => \App\Http\Middleware\ValidateVoterSlugWindow::class,
            'voter.slug.consistency' => \App\Http\Middleware\VerifyVoterSlugConsistency::class,
            'voter.step.order' => \App\Http\Middleware\EnsureVoterStepOrder::class,
            'validate.voting.ip' => \App\Http\Middleware\ValidateVotingIp::class,
            'election' => \App\Http\Middleware\ElectionMiddleware::class,
            'election.demo' => \App\Http\Middleware\EnsureDemoElection::class,
            'voting.code.window' => \App\Http\Middleware\CheckVotingWindow::class,
            'vote.organisation' => \App\Http\Middleware\EnsureRealVoteOrganisation::class,

            // organisation & Multi-tenancy
            'committee.member' => \App\Http\Middleware\EnsureCommitteeMember::class,
            'ensure.organisation' => \App\Http\Middleware\EnsureOrganisationMember::class,
            'ensure.election.voter' => \App\Http\Middleware\EnsureElectionVoter::class,

            // Election State Machine
            'election.state' => \App\Http\Middleware\EnsureElectionState::class,

            // Election SSOT Interceptor (Phase 2.4 - Constitutional Stabilization)
            'election.ssot' => \App\Http\Middleware\ElectionSSOTInterceptor::class,

            // Utility
            'no.cache' => \App\Http\Middleware\NoCacheMiddleware::class,
            'dashboard.role' => \App\Http\Middleware\CheckUserRole::class,

            // API Response Handling
            'json.api' => \App\Http\Middleware\ForceJsonResponse::class,
            'tenant.api' => \App\Http\Middleware\IdentifyTenantFromHeader::class,

            // Platform Admin
            'platform_admin' => \App\Http\Middleware\EnsurePlatformAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->dontFlash([
            'current_password',
            'password',
            'password_confirmation',
        ]);

        // Force JSON responses for API endpoints (prevents Inertia HTML interception)
        $exceptions->shouldRenderJsonWhen(function ($request, $e) {
            // Internal API v1 routes ALWAYS return JSON, even on auth/validation failures
            if ($request->is('api/v1/*')) {
                return true;
            }
            // Legacy governance API routes (if still in use)
            if ($request->is('organisations/*/api/*')) {
                return true;
            }
            return $request->expectsJson();
        });

        /*
        |--------------------------------------------------------------------------
        | Multi-Tenant & API Hardening Layer
        | Ensure sensitive framework errors never leak as HTML to API clients
        |--------------------------------------------------------------------------
        */

        // 1. Authentication Failures: Clean 401 JSON
        $exceptions->renderable(function (\Illuminate\Auth\AuthenticationException $e, Request $request) {
            if ($request->is('api/v1/*')) {
                return response()->json([
                    'error' => 'Unauthenticated access. Please log in and include a valid session.',
                    'code' => 'UNAUTHENTICATED',
                ], 401);
            }
        });

        // 2. Model Not Found: Obscure internal schema, return clean 404
        $exceptions->renderable(function (\Illuminate\Database\Eloquent\ModelNotFoundException $e, Request $request) {
            if ($request->is('api/v1/*')) {
                // Extract resource name from exception if available, otherwise generic
                $resourceName = 'resource';
                if (preg_match('/No query results found for model \[([^\]]+)\]/', $e->getMessage(), $matches)) {
                    $resourceName = class_basename($matches[1]);
                }

                return response()->json([
                    'error' => "The requested {$resourceName} could not be found or is not accessible in your organization context.",
                    'code' => 'RESOURCE_NOT_FOUND',
                ], 404);
            }
        });

        // 3. Validation Failures: Structured field errors
        $exceptions->renderable(function (\Illuminate\Validation\ValidationException $e, Request $request) {
            if ($request->is('api/v1/*')) {
                return response()->json([
                    'error' => 'Validation failed.',
                    'code' => 'VALIDATION_ERROR',
                    'details' => $e->errors(),
                ], 422);
            }
        });

        // 4. Authorization Failures: Clean 403 JSON
        $exceptions->renderable(function (\Illuminate\Auth\Access\AuthorizationException $e, Request $request) {
            if ($request->is('api/v1/*')) {
                return response()->json([
                    'error' => 'You do not have permission to perform this action.',
                    'code' => 'FORBIDDEN',
                ], 403);
            }
        });

        /*
        |--------------------------------------------------------------------------
        | Voting Exceptions → user-friendly redirects
        |--------------------------------------------------------------------------
        */
        $exceptions->renderable(function (\App\Exceptions\Voting\VotingException $e, Request $request) {
            \Illuminate\Support\Facades\Log::error('Voting exception', [
                'exception' => get_class($e),
                'message'   => $e->getMessage(),
                'user_id'   => auth()->id(),
                'ip'        => $request->ip(),
                'url'       => $request->url(),
                'context'   => $e->getContext(),
            ]);

            if ($request->wantsJson() || $request->isJson()) {
                return response()->json([
                    'error'  => $e->getUserMessage(),
                    'code'   => get_class($e),
                    'status' => $e->getHttpCode(),
                ], $e->getHttpCode());
            }

            return redirect()->route('dashboard')
                ->with('error', $e->getUserMessage());
        });

        /*
        |--------------------------------------------------------------------------
        | CSRF / Session Expiration (419)
        |--------------------------------------------------------------------------
        */
        $exceptions->respond(function ($response, $e, Request $request) {
            if ($response->getStatusCode() === 419) {
                if ($request->is('api/v1/*')) {
                    return response()->json([
                        'error' => 'Session expired. Please log in again.',
                        'code' => 'SESSION_EXPIRED',
                    ], 419);
                }

                return back()->with([
                    'message' => 'Your session has expired. Please refresh and try again.',
                ]);
            }

            return $response;
        });

        /*
        |--------------------------------------------------------------------------
        | Generic Exception Catch-All for API Routes
        | Prevents stack traces and sensitive info from leaking to clients
        |--------------------------------------------------------------------------
        */
        $exceptions->renderable(function (\Throwable $e, Request $request) {
            if ($request->is('api/v1/*')) {
                // Log the full exception for debugging
                \Illuminate\Support\Facades\Log::error('Unhandled API exception', [
                    'exception' => get_class($e),
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                ]);

                // Return generic error to client (don't expose details)
                return response()->json([
                    'error' => 'An internal server error occurred.',
                    'code' => 'INTERNAL_SERVER_ERROR',
                ], 500);
            }
        });
    })
    ->create();

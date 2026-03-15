<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
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

        // ✅ CRITICAL FIX: Append to web middleware stack (preserves session, CSRF, binding, etc.)
        // MUST use append: [...] to add to existing Laravel 11 default web stack
        $middleware->web(append: [
            \App\Http\Middleware\PreloadAssets::class,
            \App\Http\Middleware\SetLocale::class,
            \App\Http\Middleware\InjectPageMeta::class,
            \App\Http\Middleware\HandleInertiaRequests::class,
            \App\Http\Middleware\TenantContext::class,
        ]);

        // ✅ Enable stateful API authentication (for Sanctum)
        $middleware->statefulApi();

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

            // Utility
            'no.cache' => \App\Http\Middleware\NoCacheMiddleware::class,
            'dashboard.role' => \App\Http\Middleware\CheckUserRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->dontFlash([
            'current_password',
            'password',
            'password_confirmation',
        ]);

        /*
        |--------------------------------------------------------------------------
        | CSRF / Session Expiration (419)
        |--------------------------------------------------------------------------
        */
        $exceptions->respond(function ($response, $e, Request $request) {
            if ($response->getStatusCode() === 419) {
                return back()->with([
                    'message' => 'Your session has expired. Please refresh and try again.',
                ]);
            }

            return $response;
        });
    })
    ->create();

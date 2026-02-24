<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
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
        | Global Middleware
        |--------------------------------------------------------------------------
        |
        | These middleware are run during every request to your application.
        |
        */
        $middleware->append([
            \App\Http\Middleware\TrustProxies::class,
            \App\Http\Middleware\TrackPerformance::class,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Web Middleware Additions
        |--------------------------------------------------------------------------
        |
        | These are appended AFTER Laravel's default web middleware.
        | Order matters - these must run in this specific sequence:
        | 1. SetLocale - must run after session is started
        | 2. HandleInertiaRequests - must run after locale is set
        | 3. TenantContext - must run last to access session data
        |
        */
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
            \App\Http\Middleware\HandleInertiaRequests::class,
            \App\Http\Middleware\TenantContext::class,
        ]);

        /*
        |--------------------------------------------------------------------------
        | API Middleware Configuration
        |--------------------------------------------------------------------------
        |
        | Configure stateful API for Sanctum authentication.
        |
        */
        $middleware->statefulApi();

        /*
        |--------------------------------------------------------------------------
        | Middleware Aliases (Route Middleware)
        |--------------------------------------------------------------------------
        |
        | These middleware may be assigned to groups or used individually.
        | All middleware from app/Http/Kernel.php $routeMiddleware has been migrated here.
        |
        */
        $middleware->alias([
            // Authentication (3 standard Laravel middleware)
            'auth' => \App\Http\Middleware\Authenticate::class,
            'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
            'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,

            // Authorization (4 standard Laravel middleware)
            'can' => \Illuminate\Auth\Middleware\Authorize::class,
            'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
            'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
            'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,

            // Spatie Permission (3 custom middleware)
            'role' => \Spatie\Permission\Middlewares\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middlewares\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middlewares\RoleOrPermissionMiddleware::class,

            // Voting System - Business Logic (7 critical custom middleware)
            'vote.eligibility' => \App\Http\Middleware\VoteEligibility::class,
            'voter.slug.window' => \App\Http\Middleware\EnsureVoterSlugWindow::class,
            'voter.step.order' => \App\Http\Middleware\EnsureVoterStepOrder::class,
            'validate.voting.ip' => \App\Http\Middleware\ValidateVotingIp::class,
            'election' => \App\Http\Middleware\ElectionMiddleware::class,
            'election.demo' => \App\Http\Middleware\EnsureDemoElection::class,
            'vote.organisation' => \App\Http\Middleware\EnsureRealVoteOrganisation::class,

            // Organization & Multi-tenancy (2 custom middleware)
            'committee.member' => \App\Http\Middleware\EnsureCommitteeMember::class,
            'ensure.organization' => \App\Http\Middleware\EnsureOrganizationMember::class,

            // Utility (2 custom middleware)
            'no.cache' => \App\Http\Middleware\NoCacheMiddleware::class,
            'dashboard.role' => \App\Http\Middleware\CheckUserRole::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        /*
        |--------------------------------------------------------------------------
        | Exception Handling
        |--------------------------------------------------------------------------
        |
        | Configure which inputs should never be flashed on validation exception.
        | The 'dontFlash' configuration from the old Handler.php is now here.
        |
        */
        $exceptions->dontFlash([
            'current_password',
            'password',
            'password_confirmation',
        ]);
    })
    ->create();

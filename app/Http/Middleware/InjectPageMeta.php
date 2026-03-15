<?php

namespace App\Http\Middleware;

use App\Services\SeoService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class InjectPageMeta
{
    public function __construct(private SeoService $seoService) {}

    public function handle(Request $request, Closure $next)
    {
        $routeName = $request->route()?->getName() ?? '';

        // noIndex sensitive voting routes (previously handled by SEOServiceProvider)
        $noIndexRoutes = ['vote.create', 'demo-vote.create', 'code.create', 'voter.index'];
        if (in_array($routeName, $noIndexRoutes, true)) {
            SeoService::noIndex();
        }

        $page = match (true) {
            in_array($routeName, ['welcome', 'home'], true)     => 'home',
            $routeName === 'about'                              => 'about',
            $routeName === 'faq'                                => 'faq',
            $routeName === 'security'                           => 'security',
            $routeName === 'pricing'                            => 'pricing',
            $routeName === 'login'                              => 'login',
            $routeName === 'register'                           => 'register',
            $routeName === 'profile.show'                       => 'profile',
            in_array($routeName, ['dashboard', 'dashboard.welcome'], true) => 'dashboard',
            $routeName === 'elections.show'                     => 'elections.show',
            $routeName === 'elections.index'                    => 'elections.index',
            $routeName === 'organisations.show'                 => 'organisations.show',
            in_array($routeName, ['election.result', 'demo.result'], true) => 'election.result',
            default                                             => 'home',
        };

        // Log unmapped routes using the default 'home' meta — helps identify missing SEO definitions
        if ($page === 'home' && !in_array($routeName, ['welcome', 'home', ''], true)) {
            Log::debug('InjectPageMeta: unmapped route using home meta', ['route' => $routeName]);
        }

        Inertia::share('meta', $this->seoService->getMeta($page));

        return $next($request);
    }
}

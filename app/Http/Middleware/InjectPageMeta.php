<?php

namespace App\Http\Middleware;

use App\Services\SeoService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
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
            $routeName === 'elections.voters.import.tutorial'   => 'elections.voters.import.tutorial',
            $routeName === 'organisations.members.import.tutorial' => 'organisations.members.import.tutorial',
            $routeName === 'election.result'                    => 'election.result',
            $routeName === 'demo.result'                        => 'demo.result',
            in_array($routeName, ['vereinswahlen.landing', 'wahlen.vereine'], true) => 'vereinswahlen',
            $routeName === 'wahlen.hybrid'                      => 'hybrid',
            $routeName === 'wahlen.sicherheit'                  => 'sicherheit',
            default                                             => 'home',
        };

        // Log unmapped routes using the default 'home' meta — helps identify missing SEO definitions
        if ($page === 'home' && !in_array($routeName, ['welcome', 'home', ''], true)) {
            Log::debug('InjectPageMeta: unmapped route using home meta', ['route' => $routeName]);
        }

        $additional = $page === 'vereinswahlen' ? ['vereinswahlen' => true] : [];
        $meta = $this->seoService->getMeta($page, [], false, $additional);

        // Share with Inertia (Vue page.props.meta)
        Inertia::share('meta', $meta);

        // Share with Blade views so meta-info.blade.php can render server-side HTML
        View::share('serverMeta', $meta);

        return $next($request);
    }
}

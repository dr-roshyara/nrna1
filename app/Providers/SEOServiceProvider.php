<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class SEOServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Share base SEO data with every Inertia page
        // Vue components can read this from usePage().props.seo
        Inertia::share('seo', function () {
            $route = optional(request()->route())->getName() ?? '';

            // Per-route robots overrides
            $noIndexRoutes = ['vote.create', 'demo-vote.create', 'code.create', 'voter.index'];
            if (in_array($route, $noIndexRoutes, true)) {
                \App\Services\SeoService::noIndex();
            }

            return [
                'title'       => config('meta.title'),
                'description' => config('meta.description'),
                'robots'      => config('meta.robots'),
                'og_image'    => config('meta.og_image'),
                'canonical'   => config('meta.canonical', url()->current()),
            ];
        });
    }
}

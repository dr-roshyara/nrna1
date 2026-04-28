<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use App\Helpers\BreadcrumbHelper;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request)
    {
        return parent::version($request);
    }

    /**
     * Generate breadcrumbs for current route
     *
     * @param Request $request
     * @return array
     */
    private function generateBreadcrumbs(Request $request): array
    {
        $route = $request->route();
        if (!$route) {
            return [['label' => 'Home', 'url' => url('/')]];
        }

        $routeName = $route->getName();

        // Handle null route name (e.g., on login, register pages)
        if (!$routeName) {
            return [['label' => 'Home', 'url' => url('/')]];
        }

        $params = [];

        // Extract model instances from route parameters
        if ($request->route('organisation')) {
            $params['organisation'] = $request->route('organisation');
        }
        if ($request->route('election')) {
            $params['election'] = $request->route('election');
        }

        return BreadcrumbHelper::generateBreadcrumbs($routeName, $params);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request)
    {
        return array_merge(parent::share($request), [
            'canLogin' => \Route::has('login'),
            'canRegister' => \Route::has('register'),
            'csrf_token' => csrf_token(), // Explicitly share CSRF token for Inertia forms
            'success' => $request->session()->get('success'),
            'error' => $request->session()->get('error'),
            'message' => $request->session()->get('message'),
            'locale' => app()->getLocale(), // Share current locale with Vue
            'jetstream' => [
                'hasTermsAndPrivacyPolicyFeature' => false,
                'hasProfilePhotoFeature' => false,
                'hasApiFeatures' => false,
                'hasTeamFeatures' => false,
                'canCreateTeams' => false,
            ],
            'user' => $request->user() ? [
                'id' => $request->user()->id,
                'name' => $request->user()->name,
                'email' => $request->user()->email,
                'user_id' => $request->user()->user_id,
                'is_voter' => $request->user()->is_voter,
                'can_vote' => $request->user()->can_vote,
                'is_platform_admin' => $request->user()->isPlatformAdmin(),
                'is_super_admin' => $request->user()->isSuperAdmin(),
            ] : null,
            /**
             * SEO Configuration
             *
             * NOTE: useMeta composable handles all meta tag generation.
             * It reads directly from i18n translations (en.json, de.json, np.json)
             * ensuring language-aware SEO tags that match page content locale.
             *
             * This prop is kept for fallback/debugging only.
             * useMeta is the single source of truth for all SEO data.
             */
            'canonicalUrl' => $request->url(),

            /**
             * Breadcrumbs for Navigation & Schema
             *
             * Provides breadcrumb data for:
             * - HTML breadcrumb navigation display
             * - JSON-LD BreadcrumbList schema for search engines
             */
            'breadcrumbs' => $this->generateBreadcrumbs($request),
        ]);
    }
}

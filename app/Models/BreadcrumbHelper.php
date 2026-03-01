<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Route;

/**
 * Breadcrumb Helper
 *
 * Generates breadcrumb data for:
 * - HTML navigation display
 * - JSON-LD schema markup
 *
 * Used across all pages for SEO and UX
 */
class BreadcrumbHelper
{
    /**
     * Generate breadcrumb array for current route
     *
     * @param string $routeName
     * @param array $params
     * @return array
     */
    public static function generateBreadcrumbs(string $routeName, array $params = []): array
    {
        $breadcrumbs = [
            [
                'label' => trans('seo.site.title') ?: 'Home',
                'url' => url('/')
            ]
        ];

        // Route-specific breadcrumbs
        switch (true) {
            case str_starts_with($routeName, 'organisations'):
                $breadcrumbs = self::organisationBreadcrumbs($breadcrumbs, $params);
                break;
            case str_starts_with($routeName, 'election'):
                $breadcrumbs = self::electionBreadcrumbs($breadcrumbs, $params);
                break;
            case $routeName === 'pricing':
                $breadcrumbs[] = [
                    'label' => trans('seo.pages.pricing.title') ?: 'Pricing',
                    'url' => route('pricing')
                ];
                break;
            default:
                break;
        }

        return $breadcrumbs;
    }

    /**
     * Generate breadcrumbs for organisation pages
     */
    private static function organisationBreadcrumbs(array $breadcrumbs, array $params): array
    {
        $breadcrumbs[] = [
            'label' => trans('sitemap.sections.organisations') ?: 'Organisations',
            'url' => url('/organisations') 
        ];

        if ($organisation = $params['organisation'] ?? null) {
            $breadcrumbs[] = [
                'label' => $organisation->name,
                'url' => route('organisations.show', ['slug' => $organisation->slug]),
                'current' => true
            ];
        }

        return $breadcrumbs;
    }

    /**
     * Generate breadcrumbs for election pages
     */
    private static function electionBreadcrumbs(array $breadcrumbs, array $params): array
    {
        $breadcrumbs[] = [
            'label' => trans('sitemap.sections.elections') ?: 'Elections',
            'url' => url('/elections')
        ];

        if ($election = $params['election'] ?? null) {
            $breadcrumbs[] = [
                'label' => $election->name,
                'url' => url('/election/' . $election->id),
                'current' => true
            ];
        }

        return $breadcrumbs;
    }

    /**
     * Generate JSON-LD BreadcrumbList schema
     *
     * @param array $breadcrumbs
     * @return array
     */
    public static function generateJsonLdSchema(array $breadcrumbs): array
    {
        $items = [];

        foreach ($breadcrumbs as $index => $breadcrumb) {
            $items[] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $breadcrumb['label'],
                'item' => $breadcrumb['url']
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $items
        ];
    }
}

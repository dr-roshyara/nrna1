<?php

namespace App\Services;

use App\Helpers\SeoHelper;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

/**
 * SEO Service
 *
 * Manages SEO meta tags dynamically for different pages
 */
class SeoService
{
    /**
     * Get complete meta data for a page.
     * Reads translations from resources/lang/{locale}/seo.php via trans().
     * Results are cached per locale+page key.
     *
     * @param string $page     Page identifier (home, about, login, elections.show, etc.)
     * @param array  $overrides Dynamic title/description replacements
     * @param bool   $skipCache Bypass cache (e.g. for fallbacks)
     */
    public function getMeta(string $page = 'home', array $overrides = [], bool $skipCache = false, array $additional = []): array
    {
        $startTime = microtime(true);
        $locale    = app()->getLocale();
        $cacheKey  = config('meta.cache.key_prefix', 'meta:') . $locale . ':' . $page;

        if (!$skipCache && config('meta.cache.enabled', true) && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        // Priority 1: seo section inside the page's JS locale JSON file
        // e.g. resources/js/locales/pages/DemoResult/de.json → { "seo": { "title": "..." } }
        $jsonSeo     = $this->getMetaFromJsonLocale($page, $locale);
        $title       = $jsonSeo['title']       ?? trans("seo.pages.{$page}.title");
        $description = $jsonSeo['description'] ?? trans("seo.pages.{$page}.description");
        $keywords    = $jsonSeo['keywords']    ?? trans("seo.pages.{$page}.keywords");
        $robots      = $jsonSeo['robots']      ?? trans("seo.pages.{$page}.robots");

        // Priority 2: PHP seo.php site-level fallback when page key is missing
        if ($title === "seo.pages.{$page}.title") {
            $t = trans('seo.site.title');
            $title = ($t === 'seo.site.title') ? (config('meta.title') ?? 'Public Digit') : $t;
        }
        if ($description === "seo.pages.{$page}.description") {
            $t = trans('seo.site.description');
            $description = ($t === 'seo.site.description') ? (config('meta.description') ?? '') : $t;
        }
        if ($keywords === "seo.pages.{$page}.keywords") {
            $t = trans('seo.site.keywords');
            $keywords = ($t === 'seo.site.keywords') ? (config('meta.keywords') ?? '') : $t;
        }
        if ($robots === "seo.pages.{$page}.robots") {
            $robots = 'index, follow';
        }

        // Apply string overrides
        $title       = $overrides['title']       ?? $title;
        $description = $overrides['description'] ?? $description;

        // Enforce optimal SEO lengths
        $title       = SeoHelper::optimizeTitle($title);
        $description = SeoHelper::optimizeDescription($description);

        $canonical    = URL::current();
        $ogLocale     = config('meta.og_locales', [])[$locale] ?? 'en_US';
        $siteUrl      = rtrim(config('meta.site_url', config('app.url')), '/');
        $ogImage      = $siteUrl . config('meta.og.image', '/images/og-home.png');
        $twitterImage = $siteUrl . config('meta.twitter.image', '/images/og-home.png');

        $meta = [
            'title'       => $title,
            'description' => $description,
            'keywords'    => $keywords,
            'robots'      => $robots,
            'canonical'   => $canonical,
            'og'          => [
                'type'        => config('meta.og.type', 'website'),
                'url'         => $canonical,
                'title'       => $title,
                'description' => $description,
                'image'       => $ogImage,
                'width'       => config('meta.og.width', 1200),
                'height'      => config('meta.og.height', 630),
                'alt'         => config('meta.og_image_alt', 'Public Digit — Secure Online Voting Platform'),
                'site_name'   => config('meta.og.site_name', 'Public Digit'),
                'locale'      => $ogLocale,
            ],
            'twitter'     => [
                'card'        => config('meta.twitter.card', 'summary_large_image'),
                'site'        => config('meta.twitter.site', '@publicdigit'),
                'creator'     => config('meta.twitter.creator', '@publicdigit'),
                'title'       => $title,
                'description' => $description,
                'image'       => $twitterImage,
                'alt'         => config('meta.og_image_alt', 'Public Digit — Secure Online Voting Platform'),
            ],
            'alternates'  => $this->buildHreflangUrls($canonical),
            'json_ld'     => $this->buildJsonLd($canonical, $additional),
        ];

        if (config('meta.cache.enabled', true)) {
            Cache::put($cacheKey, $meta, config('meta.cache.ttl', 3600));
        }

        $ms = (microtime(true) - $startTime) * 1000;
        if (config('meta.performance.log_slow_generation', false) && $ms > config('meta.performance.slow_threshold_ms', 200)) {
            Log::warning('Slow SEO meta generation', ['page' => $page, 'locale' => $locale, 'ms' => round($ms, 2)]);
        }

        return $meta;
    }

    private function buildHreflangUrls(string $canonical): array
    {
        $urls = [];
        foreach (config('meta.supported_locales', ['de', 'en', 'np']) as $loc) {
            $urls[$loc] = $canonical;
        }
        $urls['x-default'] = $canonical;
        return $urls;
    }

    private function buildJsonLd(string $canonical, array $additional = []): array
    {
        $siteUrl = config('meta.site_url', config('app.url'));

        $schemas = [
            'website' => [
                '@context' => 'https://schema.org',
                '@type'    => 'WebSite',
                'name'     => config('meta.site_name', 'Public Digit'),
                'url'      => $siteUrl,
                'description' => trans('seo.site.description'),
                'potentialAction' => [
                    '@type'       => 'SearchAction',
                    'target'      => $siteUrl . '/organisations?q={search_term_string}',
                    'query-input' => 'required name=search_term_string',
                ],
            ],
            'organization' => [
                '@context'    => 'https://schema.org',
                '@type'       => 'Organization',
                'name'        => config('meta.organisation.name'),
                'legalName'   => config('meta.organisation.legal_name'),
                'url'         => $siteUrl,
                'logo'        => config('meta.organisation.logo'),
                'foundingDate'=> config('meta.organisation.founding_date'),
                'email'       => config('meta.organisation.email'),
                'address'     => config('meta.organisation.address'),
                'sameAs'      => config('meta.organisation.same_as', []),
                'contactPoint' => [
                    '@type'             => 'ContactPoint',
                    'email'             => config('meta.organisation.email'),
                    'contactType'       => 'customer service',
                    'availableLanguage' => ['German', 'English', 'Nepali'],
                ],
            ],
            'service' => [
                '@context'    => 'https://schema.org',
                '@type'       => 'Service',
                'name'        => 'Digitale Online Wahlen',
                'description' => 'Sichere Online-Wahlen für Vereine, NGOs, Verbände und Diaspora-Organisationen',
                'provider'    => [
                    '@type' => 'Organization',
                    'name'  => config('meta.organisation.name'),
                ],
                'areaServed'  => ['DE', 'AT', 'CH', 'NP'],
                'serviceType' => 'Online Voting Service',
                'audience'    => [
                    '@type' => 'Audience',
                    'name'  => 'Vereine, NGOs und Mitgliedsorganisationen',
                ],
            ],
        ];

        // Vereinswahlen-specific rich Service schema (injected when page key is 'vereinswahlen')
        if (!empty($additional['vereinswahlen'])) {
            $schemas['vereinswahlen'] = $this->buildVereinswahlenSchema();
        }

        // Breadcrumb schema — injected from shared breadcrumbs when available
        if (!empty($additional['breadcrumbs'])) {
            $schemas['breadcrumb'] = $this->buildBreadcrumbSchema($additional['breadcrumbs']);
        }

        // Election-specific VoteAction schema
        if (!empty($additional['election'])) {
            $e = $additional['election'];
            $schemas['election'] = [
                '@context'    => 'https://schema.org',
                '@type'       => 'VoteAction',
                'name'        => $e['name'] ?? '',
                'description' => $e['description'] ?? '',
                'startTime'   => $e['start_date'] ?? null,
                'endTime'     => $e['end_date'] ?? null,
                'agent'       => [
                    '@type' => 'Organization',
                    'name'  => $e['organisation_name'] ?? config('meta.organisation.name'),
                ],
            ];
        }

        // FAQ schema — injected by controllers that have FAQ data
        if (!empty($additional['faq'])) {
            $schemas['faq'] = $additional['faq'];
        }

        return $schemas;
    }

    /**
     * Read the `seo` section from a page-specific JS locale JSON file.
     * Convention: page key "demo.result" → folder "DemoResult"
     *             page key "vereinswahlen" → folder "Vereinswahlen"
     *
     * Returns an array with title/description/keywords/robots, or empty array
     * when the file / seo key does not exist.
     */
    private function getMetaFromJsonLocale(string $page, string $locale): array
    {
        // Convert page key → PascalCase folder name: "demo.result" → "DemoResult"
        $folder = collect(explode('.', $page))
            ->map(fn($part) => ucfirst($part))
            ->implode('');

        $path = resource_path("js/locales/pages/{$folder}/{$locale}.json");

        if (!file_exists($path)) {
            return [];
        }

        $data = json_decode(file_get_contents($path), true);

        return $data['seo'] ?? [];
    }

    private function buildVereinswahlenSchema(): array
    {
        $siteUrl = config('meta.site_url', config('app.url'));

        return [
            '@context'    => 'https://schema.org',
            '@type'       => 'Service',
            'name'        => trans('seo.pages.vereinswahlen.title'),
            'description' => trans('seo.pages.vereinswahlen.description'),
            'provider'    => [
                '@type'      => 'Organization',
                'name'       => config('meta.organisation.name'),
                'areaServed' => [
                    ['@type' => 'Country', 'name' => 'Deutschland'],
                    ['@type' => 'Country', 'name' => 'Österreich'],
                    ['@type' => 'Country', 'name' => 'Schweiz'],
                ],
            ],
            'audience' => [
                '@type'          => 'Audience',
                'name'           => trans('seo.pages.vereinswahlen.keywords'),
                'geographicArea' => 'DE, AT, CH',
            ],
            'serviceType'    => 'Online Voting Service',
            'termsOfService' => $siteUrl . '/agb',
            'serviceOutput'  => [
                '@type'       => 'VoteAction',
                'name'        => 'Vorstandswahl',
                'description' => 'Digitale Vorstandswahl für Vereine',
            ],
            'offers' => [
                '@type'            => 'Offer',
                'name'             => 'Vereinswahlen-Paket',
                'description'      => 'Komplettlösung für digitale Vereinswahlen',
                'priceCurrency'    => 'EUR',
                'eligibleQuantity' => 'bis 500 Mitglieder',
            ],
        ];
    }

    private function buildBreadcrumbSchema(array $breadcrumbs): array
    {
        $items = [];
        $position = 1;
        foreach ($breadcrumbs as $crumb) {
            $items[] = [
                '@type'    => 'ListItem',
                'position' => $position++,
                'name'     => $crumb['label'] ?? $crumb['name'] ?? '',
                'item'     => $crumb['url'] ?? null,
            ];
        }
        return [
            '@context'        => 'https://schema.org',
            '@type'           => 'BreadcrumbList',
            'itemListElement' => $items,
        ];
    }

    /**
     * Set page title
     *
     * @param string $title
     * @param bool $includeAppName
     * @return void
     */
    public static function setTitle(string $title, bool $includeAppName = true): void
    {
        $separator = config('meta.title_separator', ' | ');
        $appName = config('meta.site_name', config('app.name'));

        $fullTitle = $includeAppName
            ? $title . $separator . $appName
            : $title;

        Config::set('meta.title', $fullTitle);
        Config::set('meta.og_title', $title);
        Config::set('meta.twitter_title', $title);
    }

    /**
     * Set page description
     *
     * @param string $description
     * @return void
     */
    public static function setDescription(string $description): void
    {
        Config::set('meta.description', $description);
        Config::set('meta.og_description', $description);
        Config::set('meta.twitter_description', $description);
    }

    /**
     * Set keywords
     *
     * @param array|string $keywords
     * @return void
     */
    public static function setKeywords($keywords): void
    {
        if (is_array($keywords)) {
            $keywords = implode(', ', $keywords);
        }

        Config::set('meta.keywords', $keywords);
    }

    /**
     * Set canonical URL
     *
     * @param string|null $url
     * @return void
     */
    public static function setCanonical(?string $url = null): void
    {
        $canonical = $url ?? URL::current();
        Config::set('meta.canonical', $canonical);
        Config::set('meta.og_url', $canonical);
    }

    /**
     * Set Open Graph image
     *
     * @param string $imageUrl
     * @param int $width
     * @param int $height
     * @param string|null $alt
     * @return void
     */
    public static function setImage(
        string $imageUrl,
        int $width = 1200,
        int $height = 630,
        ?string $alt = null
    ): void {
        Config::set('meta.og_image', $imageUrl);
        Config::set('meta.og_image_width', (string)$width);
        Config::set('meta.og_image_height', (string)$height);
        Config::set('meta.og_image_alt', $alt ?? config('meta.og_image_alt'));
        Config::set('meta.twitter_image', $imageUrl);
    }

    /**
     * Set robots meta tag
     *
     * @param string $robots
     * @return void
     */
    public static function setRobots(string $robots): void
    {
        Config::set('meta.robots', $robots);
    }

    /**
     * Disable indexing (for private pages)
     *
     * @return void
     */
    public static function noIndex(): void
    {
        self::setRobots('noindex, nofollow');
    }

    /**
     * Set all meta tags at once
     *
     * @param array $data
     * @return void
     */
    public static function set(array $data): void
    {
        if (isset($data['title'])) {
            self::setTitle($data['title'], $data['include_app_name'] ?? true);
        }

        if (isset($data['description'])) {
            self::setDescription($data['description']);
        }

        if (isset($data['keywords'])) {
            self::setKeywords($data['keywords']);
        }

        if (isset($data['canonical'])) {
            self::setCanonical($data['canonical']);
        }

        if (isset($data['image'])) {
            self::setImage(
                $data['image'],
                $data['image_width'] ?? 1200,
                $data['image_height'] ?? 630,
                $data['image_alt'] ?? null
            );
        }

        if (isset($data['robots'])) {
            self::setRobots($data['robots']);
        }

        if (isset($data['no_index']) && $data['no_index']) {
            self::noIndex();
        }
    }

    /**
     * Generate structured data for a user profile
     *
     * @param object $user
     * @return array
     */
    public static function generatePersonSchema(object $user): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Person',
            'name' => $user->name,
            'email' => $user->email ?? null,
            'url' => route('user.show', ['profile' => $user->user_id]),
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => $user->city ?? null,
                'addressRegion' => $user->region ?? null,
                'addressCountry' => $user->country ?? null,
            ],
        ];
    }

    /**
     * Generate breadcrumb structured data
     *
     * @param array $items
     * @return array
     */
    public static function generateBreadcrumbSchema(array $items): array
    {
        $itemListElement = [];

        foreach ($items as $index => $item) {
            $itemListElement[] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $item['name'],
                'item' => $item['url'] ?? null,
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $itemListElement,
        ];
    }

    /**
     * Generate event structured data
     *
     * @param array $event
     * @return array
     */
    public static function generateEventSchema(array $event): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Event',
            'name' => $event['name'],
            'description' => $event['description'] ?? null,
            'startDate' => $event['start_date'] ?? null,
            'endDate' => $event['end_date'] ?? null,
            'eventStatus' => 'https://schema.org/EventScheduled',
            'eventAttendanceMode' => 'https://schema.org/OnlineEventAttendanceMode',
            'location' => [
                '@type' => 'VirtualLocation',
                'url' => $event['url'] ?? config('app.url'),
            ],
            'organizer' => [
                '@type' => 'organisation',
                'name' => config('meta.organisation.name'),
                'url' => config('meta.organisation.url'),
            ],
        ];
    }
}

<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

/**
 * SEO Service
 *
 * Manages SEO meta tags dynamically for different pages
 */
class SeoService
{
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
                '@type' => 'Organization',
                'name' => config('meta.organization.name'),
                'url' => config('meta.organization.url'),
            ],
        ];
    }
}

<?php

namespace App\Helpers;

use App\Models\Election;
use App\Models\Organisation;

/**
 * Schema Generator
 *
 * Generates JSON-LD structured data for:
 * - Organizations
 * - Elections (as Events)
 * - Breadcrumbs
 *
 * Used by Vue components and Blade templates for SEO
 */
class SchemaGenerator
{
    /**
     * Generate Event schema for Election
     *
     * Marks elections as events in search results
     *
     * @param Election $election
     * @return array
     */
    public static function generateElectionEventSchema(Election $election): array
    {
        $organisation = $election->organisation;
        $now = now();
        $startDate = $election->start_date ? $election->start_date->toIso8601String() : null;
        $endDate = $election->end_date ? $election->end_date->toIso8601String() : null;

        // Determine event status
        $eventStatus = 'EventScheduled';
        if ($endDate && strtotime($endDate) < time()) {
            $eventStatus = 'EventCancelled';
        } elseif ($startDate && $endDate) {
            if (strtotime($startDate) <= time() && time() <= strtotime($endDate)) {
                $eventStatus = 'EventScheduled';
            }
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'Event',
            'name' => $election->name,
            'description' => $election->description ?? 'Election on Public Digit',
            'startDate' => $startDate,
            'endDate' => $endDate,
            'eventStatus' => $eventStatus,
            'eventAttendanceMode' => 'OnlineEventAttendanceMode',
            'url' => route('election.dashboard') . '?election=' . $election->id,
            'image' => $organisation?->logo_url ?? url('/images/og-default.jpg'),
            'organizer' => [
                '@type' => 'organisation',
                'name' => $organisation?->name ?? 'Public Digit',
                'url' => $organisation ? route('organisations.show', ['slug' => $organisation->slug]) : url('/')
            ],
            'offers' => [
                '@type' => 'Offer',
                'url' => route('election.dashboard') . '?election=' . $election->id,
                'price' => '0',
                'priceCurrency' => 'USD'
            ]
        ];
    }

    /**
     * Generate enhanced organisation schema
     *
     * Includes member count, election count, and contact info
     *
     * @param Organisation $organisation
     * @return array
     */
    public static function generateOrganizationSchema(Organisation $organisation): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'organisation',
            'name' => $organisation->name,
            'url' => route('organisations.show', ['slug' => $organisation->slug]),
            'description' => $organisation->description ?? 'organisation on Public Digit',
        ];

        // Add logo if available
        if ($organisation->logo_url) {
            $schema['logo'] = $organisation->logo_url;
        } else {
            $schema['logo'] = url('/images/logo-2.png');
        }

        // Add contact info if available
        if ($organisation->email) {
            $schema['email'] = $organisation->email;
        }

        if ($organisation->address) {
            $schema['address'] = $organisation->address;
        }

        // Add member count
        $memberCount = $organisation->members_count ?? 0;
        if ($memberCount > 0) {
            $schema['memberCount'] = $memberCount;
        }

        // Add election count
        $electionCount = $organisation->elections_count ?? 0;
        if ($electionCount > 0) {
            $schema['eventCount'] = $electionCount;
        }

        // Add social links if available
        $socialLinks = self::extractSocialLinks($organisation);
        if (!empty($socialLinks)) {
            $schema['sameAs'] = $socialLinks;
        }

        return $schema;
    }

    /**
     * Generate breadcrumb schema
     *
     * @param array $breadcrumbs
     * @return array
     */
    public static function generateBreadcrumbSchema(array $breadcrumbs): array
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

    /**
     * Extract social links from organisation
     *
     * @param Organisation $organisation
     * @return array
     */
    private static function extractSocialLinks(Organisation $organisation): array
    {
        $links = [];
        $settings = $organisation->settings ?? [];

        $platforms = ['website', 'facebook', 'twitter', 'instagram', 'linkedin', 'youtube'];

        foreach ($platforms as $platform) {
            if (isset($settings[$platform]) && !empty($settings[$platform])) {
                $url = $settings[$platform];

                // Ensure URL has protocol
                if (!str_starts_with($url, 'http')) {
                    $url = 'https://' . $url;
                }

                $links[] = $url;
            }
        }

        return $links;
    }

    /**
     * Generate WebSite schema
     *
     * @return array
     */
    public static function generateWebsiteSchema(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => 'Public Digit',
            'description' => trans('seo.site.description', 'Secure digital voting platform'),
            'url' => url('/'),
            'logo' => url('/images/logo-2.png'),
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => [
                    '@type' => 'EntryPoint',
                    'urlTemplate' => url('/search?q={search_term_string}')
                ],
                'query-input' => 'required name=search_term_string'
            ]
        ];
    }

    /**
     * Format schema as JSON string for embedding
     *
     * @param array $schema
     * @return string
     */
    public static function toJsonLd(array $schema): string
    {
        return json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    /**
     * Validate schema structure
     *
     * @param array $schema
     * @return bool
     */
    public static function isValid(array $schema): bool
    {
        return isset($schema['@context'], $schema['@type']);
    }
}

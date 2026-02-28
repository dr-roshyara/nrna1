<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

/**
 * Robots Controller
 *
 * Generates dynamic robots.txt for search engine crawlers
 * Balances crawlability with privacy protection
 */
class RobotsController extends Controller
{
    /**
     * Generate and return robots.txt
     *
     * @return Response
     */
    public function index(): Response
    {
        $robots = $this->generateRobots();

        return response($robots, 200)
            ->header('Content-Type', 'text/plain; charset=utf-8');
    }

    /**
     * Generate robots.txt content
     *
     * @return string
     */
    private function generateRobots(): string
    {
        $robots = "# robots.txt for Public Digit\n";
        $robots .= "# Generated dynamically for optimal SEO\n";
        $robots .= "\n";

        // Default rules for all crawlers
        $robots .= "User-agent: *\n";
        $robots .= "Allow: /\n";
        $robots .= "\n";

        // ============================================
        // PRIVATE & ADMIN AREAS (Disallow)
        // ============================================
        $robots .= "# Private Administration Areas\n";
        $robots .= "# These areas should not be indexed\n";
        $robots .= "Disallow: /admin/\n";
        $robots .= "Disallow: /dashboard/\n";
        $robots .= "Disallow: /commission/\n";
        $robots .= "\n";

        // ============================================
        // VOTING & VERIFICATION (Disallow for Privacy)
        // ============================================
        $robots .= "# Voting URLs & Verification (Privacy Protection)\n";
        $robots .= "# These are personal/sensitive URLs that must not be indexed\n";
        $robots .= "# to protect voter privacy and election security\n";
        $robots .= "Disallow: /vote/\n";
        $robots .= "Disallow: /v/\n";
        $robots .= "Disallow: /voter/\n";
        $robots .= "\n";

        // ============================================
        // API ENDPOINTS (Disallow)
        // ============================================
        $robots .= "# API Endpoints\n";
        $robots .= "# APIs should not be indexed as they are not meant for users\n";
        $robots .= "Disallow: /api/\n";
        $robots .= "Disallow: /mapi/\n";
        $robots .= "\n";

        // ============================================
        // QUERY PARAMETERS (Disallow to prevent duplication)
        // ============================================
        $robots .= "# Query Parameters to Prevent Duplicate Content\n";
        $robots .= "Disallow: /*?*sort=\n";
        $robots .= "Disallow: /*?*filter=\n";
        $robots .= "Disallow: /*?*search=\n";
        $robots .= "\n";

        // ============================================
        // FILE TYPES (Disallow unnecessary resources)
        // ============================================
        $robots .= "# Unnecessary Resources\n";
        $robots .= "Disallow: *.json$\n";
        $robots .= "Disallow: *.css$\n";
        $robots .= "Disallow: *.js$\n";
        $robots .= "\n";

        // ============================================
        // CRAWL DELAY & RATE LIMITING
        // ============================================
        $robots .= "# Crawl Settings\n";
        $robots .= "Crawl-delay: 1\n";
        $robots .= "\n";

        // ============================================
        // GOOGLE-SPECIFIC RULES
        // ============================================
        $robots .= "User-agent: Googlebot\n";
        $robots .= "Allow: /\n";
        $robots .= "Disallow: /admin/\n";
        $robots .= "Disallow: /dashboard/\n";
        $robots .= "Disallow: /vote/\n";
        $robots .= "Disallow: /v/\n";
        $robots .= "Disallow: /api/\n";
        $robots .= "Crawl-delay: 0\n";
        $robots .= "\n";

        // ============================================
        // BING-SPECIFIC RULES
        // ============================================
        $robots .= "User-agent: Bingbot\n";
        $robots .= "Allow: /\n";
        $robots .= "Disallow: /admin/\n";
        $robots .= "Disallow: /dashboard/\n";
        $robots .= "Disallow: /vote/\n";
        $robots .= "Disallow: /v/\n";
        $robots .= "Disallow: /api/\n";
        $robots .= "Crawl-delay: 1\n";
        $robots .= "\n";

        // ============================================
        // SITEMAPS
        // ============================================
        $robots .= "# Sitemaps for Search Engines\n";
        $robots .= "Sitemap: " . url('/sitemap.xml') . "\n";
        $robots .= "Sitemap: " . url('/sitemap/main.xml') . "\n";
        $robots .= "Sitemap: " . url('/sitemap/organisations.xml') . "\n";
        $robots .= "Sitemap: " . url('/sitemap/elections.xml') . "\n";
        $robots .= "Sitemap: " . url('/sitemap/results.xml') . "\n";

        return $robots;
    }
}

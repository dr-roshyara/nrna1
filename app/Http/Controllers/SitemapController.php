<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Candidacy;
use App\Models\Post;
use App\Models\Organisation;
use App\Models\Election;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\URL;

/**
 * Sitemap Controller
 *
 * Generates XML sitemaps for search engines
 * Supports:
 * - Main sitemap (pages, users, candidacies)
 * - Organisations sitemap
 * - Elections sitemap
 * - Results sitemap
 * - Sitemap index (aggregates all sitemaps)
 */
class SitemapController extends Controller
{
    /**
     * Sitemap Index - Aggregates all sitemaps
     *
     * @return Response
     */
    public function sitemapIndex(): Response
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // Main sitemap
        $xml .= '    <sitemap>' . "\n";
        $xml .= '        <loc>' . htmlspecialchars(URL::to('/sitemap/main.xml'), ENT_XML1) . '</loc>' . "\n";
        $xml .= '        <lastmod>' . now()->toAtomString() . '</lastmod>' . "\n";
        $xml .= '    </sitemap>' . "\n";

        // Organisations sitemap
        $xml .= '    <sitemap>' . "\n";
        $xml .= '        <loc>' . htmlspecialchars(URL::to('/sitemap/organisations.xml'), ENT_XML1) . '</loc>' . "\n";
        $xml .= '        <lastmod>' . now()->toAtomString() . '</lastmod>' . "\n";
        $xml .= '    </sitemap>' . "\n";

        // Elections sitemap
        $xml .= '    <sitemap>' . "\n";
        $xml .= '        <loc>' . htmlspecialchars(URL::to('/sitemap/elections.xml'), ENT_XML1) . '</loc>' . "\n";
        $xml .= '        <lastmod>' . now()->toAtomString() . '</lastmod>' . "\n";
        $xml .= '    </sitemap>' . "\n";

        // Results sitemap
        $xml .= '    <sitemap>' . "\n";
        $xml .= '        <loc>' . htmlspecialchars(URL::to('/sitemap/results.xml'), ENT_XML1) . '</loc>' . "\n";
        $xml .= '        <lastmod>' . now()->toAtomString() . '</lastmod>' . "\n";
        $xml .= '    </sitemap>' . "\n";

        $xml .= '</sitemapindex>';

        return response($xml, 200)
            ->header('Content-Type', 'application/xml');
    }

    /**
     * Generate and return the main sitemap
     *
     * @return Response
     */
    public function index(): Response
    {
        $sitemap = $this->generateSitemap();

        return response($sitemap, 200)
            ->header('Content-Type', 'application/xml');
    }

    /**
     * Generate organisations sitemap
     *
     * @return Response
     */
    public function organisations(): Response
    {
        try {
            $organisations = Organisation::orderBy('updated_at', 'desc')->get();

            $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
            $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' .
                    ' xmlns:xhtml="http://www.w3.org/1999/xhtml">' . "\n";

            foreach ($organisations as $org) {
                if ($org->slug) {
                    $url = route('organisations.show', ['slug' => $org->slug]);
                    $xml .= $this->addUrl($url, $org->updated_at, 'weekly', '0.8');
                }
            }

            $xml .= '</urlset>';

            return response($xml, 200)
                ->header('Content-Type', 'application/xml');
        } catch (\Exception $e) {
            \Log::error('Error generating organisations sitemap: ' . $e->getMessage());
            return response('Error generating sitemap', 500);
        }
    }

    /**
     * Generate elections sitemap
     *
     * @return Response
     */
    public function elections(): Response
    {
        try {
            // Get active and recent elections
            $elections = Election::where('is_active', true)
                ->orderBy('start_date', 'desc')
                ->get();

            $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
            $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' .
                    ' xmlns:xhtml="http://www.w3.org/1999/xhtml">' . "\n";

            foreach ($elections as $election) {
                // Active elections get higher priority and more frequent crawl
                $isUpcoming = strtotime($election->start_date) > time();
                $priority = $isUpcoming ? '0.8' : '0.7';
                $changefreq = $isUpcoming ? 'daily' : 'weekly';

                $url = route('election.dashboard') . '?election=' . $election->id;
                $xml .= $this->addUrl($url, $election->updated_at, $changefreq, $priority);
            }

            $xml .= '</urlset>';

            return response($xml, 200)
                ->header('Content-Type', 'application/xml');
        } catch (\Exception $e) {
            \Log::error('Error generating elections sitemap: ' . $e->getMessage());
            return response('Error generating sitemap', 500);
        }
    }

    /**
     * Generate results sitemap
     *
     * @return Response
     */
    public function results(): Response
    {
        try {
            // Get completed elections (past end_date) for results
            $elections = Election::whereNotNull('end_date')
                ->where('end_date', '<', now())
                ->orderBy('end_date', 'desc')
                ->limit(500)
                ->get();

            $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
            $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' .
                    ' xmlns:xhtml="http://www.w3.org/1999/xhtml">' . "\n";

            foreach ($elections as $election) {
                $url = route('election.dashboard') . '?election=' . $election->id . '&tab=results';
                $xml .= $this->addUrl($url, $election->updated_at, 'monthly', '0.6');
            }

            $xml .= '</urlset>';

            return response($xml, 200)
                ->header('Content-Type', 'application/xml');
        } catch (\Exception $e) {
            \Log::error('Error generating results sitemap: ' . $e->getMessage());
            return response('Error generating sitemap', 500);
        }
    }

    /**
     * Generate sitemap XML
     *
     * @return string
     */
    private function generateSitemap(): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        // Add homepage
        $xml .= $this->addUrl(URL::to('/'), now(), 'daily', '1.0');

        // Add static pages
        $staticPages = [
            '/candidacies/index' => ['priority' => '0.8', 'changefreq' => 'weekly'],
            '/users/index' => ['priority' => '0.7', 'changefreq' => 'weekly'],
            '/public-demo/guide' => ['priority' => '0.8', 'changefreq' => 'monthly'],
        ];

        foreach ($staticPages as $url => $config) {
            $xml .= $this->addUrl(
                URL::to($url),
                now(),
                $config['changefreq'],
                $config['priority']
            );
        }

        // Add user profiles (limit to recent public profiles)
        try {
            $users = User::where('is_voter', 1)
                ->orderBy('updated_at', 'desc')
                ->limit(1000)
                ->get();

            foreach ($users as $user) {
                if (!empty($user->user_id)) {
                    $xml .= $this->addUrl(
                        route('user.show', ['profile' => $user->user_id]),
                        $user->updated_at,
                        'monthly',
                        '0.6'
                    );
                }
            }
        } catch (\Exception $e) {
            \Log::error('Error generating user sitemap: ' . $e->getMessage());
        }

        // Add candidacy pages (if public)
        try {
            $candidacies = Candidacy::with('user')
                ->orderBy('updated_at', 'desc')
                ->limit(500)
                ->get();

            foreach ($candidacies as $candidacy) {
                if (!empty($candidacy->candidacy_id)) {
                    // You can add specific candidacy URLs if they exist
                    // $xml .= $this->addUrl(...);
                }
            }
        } catch (\Exception $e) {
            \Log::error('Error generating candidacy sitemap: ' . $e->getMessage());
        }

        $xml .= '</urlset>';

        return $xml;
    }

    /**
     * Add a URL entry to the sitemap
     *
     * @param string $loc
     * @param mixed $lastmod
     * @param string $changefreq
     * @param string $priority
     * @return string
     */
    private function addUrl(
        string $loc,
        $lastmod = null,
        string $changefreq = 'weekly',
        string $priority = '0.5'
    ): string {
        $xml = '<url>';
        $xml .= '<loc>' . htmlspecialchars($loc, ENT_XML1) . '</loc>';

        if ($lastmod) {
            $date = is_string($lastmod) ? $lastmod : $lastmod->toIso8601String();
            $xml .= '<lastmod>' . $date . '</lastmod>';
        }

        $xml .= '<changefreq>' . $changefreq . '</changefreq>';
        $xml .= '<priority>' . $priority . '</priority>';
        $xml .= '</url>';

        return $xml;
    }
}

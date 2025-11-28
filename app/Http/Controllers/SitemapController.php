<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Candidacy;
use App\Models\Post;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\URL;

/**
 * Sitemap Controller
 *
 * Generates XML sitemap for search engines
 */
class SitemapController extends Controller
{
    /**
     * Generate and return the sitemap
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

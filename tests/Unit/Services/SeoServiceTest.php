<?php

namespace Tests\Unit\Services;

use App\Services\SeoService;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class SeoServiceTest extends TestCase
{
    // These tests don't need a database — skip migrations
    public function refreshDatabase(): void {}

    private SeoService $seo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seo = app(SeoService::class);
        app()->setLocale('en');
    }

    public function test_getMeta_returns_required_keys(): void
    {
        $meta = $this->seo->getMeta('home', [], true);

        $this->assertIsArray($meta);
        $this->assertArrayHasKey('title', $meta);
        $this->assertArrayHasKey('description', $meta);
        $this->assertArrayHasKey('keywords', $meta);
        $this->assertArrayHasKey('robots', $meta);
        $this->assertArrayHasKey('canonical', $meta);
        $this->assertArrayHasKey('og', $meta);
        $this->assertArrayHasKey('twitter', $meta);
        $this->assertArrayHasKey('alternates', $meta);
        $this->assertArrayHasKey('json_ld', $meta);

        $this->assertArrayHasKey('title', $meta['og']);
        $this->assertArrayHasKey('image', $meta['og']);
        $this->assertArrayHasKey('locale', $meta['og']);

        $this->assertArrayHasKey('card', $meta['twitter']);
        $this->assertArrayHasKey('title', $meta['twitter']);

        $this->assertNotEmpty($meta['title']);
        $this->assertNotEmpty($meta['description']);
    }

    public function test_getMeta_caches_result(): void
    {
        Cache::flush();

        // First call — should cache
        $this->seo->getMeta('home', [], false);

        $cacheKey = config('meta.cache.key_prefix', 'meta:') . 'en:home';
        $this->assertTrue(Cache::has($cacheKey));

        // Second call — should return cached value
        $cached = Cache::get($cacheKey);
        $result = $this->seo->getMeta('home', [], false);

        $this->assertEquals($cached['title'], $result['title']);
    }

    public function test_getMeta_fallback_on_unknown_page(): void
    {
        $meta = $this->seo->getMeta('this_page_does_not_exist', [], true);

        $this->assertIsArray($meta);
        $this->assertNotEmpty($meta['title']);
        // Should fall back to site title or config default — not an empty string
        $this->assertGreaterThan(0, strlen($meta['title']));
    }

    public function test_getMeta_title_override_applied(): void
    {
        $meta = $this->seo->getMeta('home', ['title' => 'Custom Title Override'], true);

        $this->assertEquals('Custom Title Override', $meta['title']);
        $this->assertEquals('Custom Title Override', $meta['og']['title']);
        $this->assertEquals('Custom Title Override', $meta['twitter']['title']);
    }

    public function test_getMeta_dashboard_has_noindex_robots(): void
    {
        $meta = $this->seo->getMeta('dashboard', [], true);

        $this->assertEquals('noindex, nofollow', $meta['robots']);
    }

    public function test_getMeta_hreflang_has_all_locales(): void
    {
        $meta = $this->seo->getMeta('home', [], true);

        $this->assertArrayHasKey('de', $meta['alternates']);
        $this->assertArrayHasKey('en', $meta['alternates']);
        $this->assertArrayHasKey('np', $meta['alternates']);
        $this->assertArrayHasKey('x-default', $meta['alternates']);
    }

    public function test_getMeta_json_ld_contains_website_and_organization(): void
    {
        $meta = $this->seo->getMeta('home', [], true);

        $this->assertArrayHasKey('website', $meta['json_ld']);
        $this->assertArrayHasKey('organization', $meta['json_ld']);
        $this->assertEquals('https://schema.org', $meta['json_ld']['website']['@context']);
        $this->assertEquals('WebSite', $meta['json_ld']['website']['@type']);
        $this->assertEquals('Organization', $meta['json_ld']['organization']['@type']);
    }

    // ── JSON locale SEO reading ───────────────────────────────────────────────

    public function test_getMeta_reads_seo_from_json_locale_file(): void
    {
        // DemoResult/en.json has a "seo" section
        app()->setLocale('en');
        $meta = $this->seo->getMeta('demo.result', [], true);

        $this->assertStringContainsString('Demo Election Results', $meta['title']);
        $this->assertStringContainsString('demo', strtolower($meta['description']));
    }

    public function test_getMeta_reads_seo_from_german_json_locale_file(): void
    {
        app()->setLocale('de');
        $meta = $this->seo->getMeta('demo.result', [], true);

        $this->assertStringContainsString('Demo', $meta['title']);
        // German robots should be noindex from the JSON seo section
        $this->assertEquals('noindex, nofollow', $meta['robots']);
    }

    public function test_getMeta_demo_result_is_noindex(): void
    {
        $meta = $this->seo->getMeta('demo.result', [], true);

        $this->assertEquals('noindex, nofollow', $meta['robots']);
    }

    public function test_getMeta_json_locale_seo_overrides_php_translation(): void
    {
        // When JSON has a seo.title, it takes priority over PHP seo.php
        app()->setLocale('en');
        $jsonPath = resource_path('js/locales/pages/DemoResult/en.json');
        $data     = json_decode(file_get_contents($jsonPath), true);

        $meta = $this->seo->getMeta('demo.result', [], true);

        $this->assertEquals($data['seo']['title'], $meta['title']);
    }

    public function test_getMeta_falls_back_to_php_when_json_has_no_seo_section(): void
    {
        // 'home' page has no JSON locale file → falls back to PHP seo.php
        app()->setLocale('en');
        $meta = $this->seo->getMeta('home', [], true);

        $this->assertNotEmpty($meta['title']);
        $this->assertStringNotContainsString('seo.pages', $meta['title']);
    }

    // ── Vereinswahlen JSON-LD schema ─────────────────────────────────────────

    public function test_getMeta_vereinswahlen_injects_service_schema(): void
    {
        app()->setLocale('de');
        $meta = $this->seo->getMeta('vereinswahlen', [], true, ['vereinswahlen' => true]);

        $this->assertArrayHasKey('vereinswahlen', $meta['json_ld']);
        $this->assertEquals('Service', $meta['json_ld']['vereinswahlen']['@type']);
        $this->assertArrayHasKey('serviceOutput', $meta['json_ld']['vereinswahlen']);
    }

    public function test_getMeta_vereinswahlen_without_flag_has_no_service_schema(): void
    {
        $meta = $this->seo->getMeta('vereinswahlen', [], true);

        $this->assertArrayNotHasKey('vereinswahlen', $meta['json_ld']);
    }

    // ── Locale switching ─────────────────────────────────────────────────────

    public function test_getMeta_returns_german_title_for_de_locale(): void
    {
        app()->setLocale('de');
        $meta = $this->seo->getMeta('home', [], true);

        // German home title contains the primary keyword
        $this->assertStringContainsString('Verein', $meta['title']);
    }

    public function test_getMeta_og_locale_matches_app_locale(): void
    {
        app()->setLocale('de');
        $meta = $this->seo->getMeta('home', [], true);
        $this->assertEquals('de_DE', $meta['og']['locale']);

        app()->setLocale('en');
        $meta = $this->seo->getMeta('home', [], true);
        $this->assertEquals('en_US', $meta['og']['locale']);
    }

    public function test_getMeta_title_never_exceeds_60_chars(): void
    {
        foreach (['en', 'de'] as $locale) {
            app()->setLocale($locale);
            foreach (['home', 'login', 'register', 'about', 'faq', 'security', 'demo.result'] as $page) {
                $meta = $this->seo->getMeta($page, [], true);
                $this->assertLessThanOrEqual(
                    60,
                    mb_strlen($meta['title']),
                    "Title too long for page '{$page}' locale '{$locale}': {$meta['title']}"
                );
            }
        }
    }

    public function test_getMeta_description_never_exceeds_160_chars(): void
    {
        foreach (['en', 'de'] as $locale) {
            app()->setLocale($locale);
            foreach (['home', 'login', 'security', 'demo.result'] as $page) {
                $meta = $this->seo->getMeta($page, [], true);
                $this->assertLessThanOrEqual(
                    160,
                    mb_strlen($meta['description']),
                    "Description too long for page '{$page}' locale '{$locale}'"
                );
            }
        }
    }
}

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
}

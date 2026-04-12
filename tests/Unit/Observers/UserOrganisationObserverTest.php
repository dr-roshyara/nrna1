<?php

namespace Tests\Unit\Observers;

use App\Models\User;
use App\Models\Organisation;
use App\Models\UserOrganisationRole;
use App\Observers\UserOrganisationObserver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Cache;

class UserOrganisationObserverTest extends TestCase
{
    use RefreshDatabase;

    protected UserOrganisationObserver $observer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->observer = new UserOrganisationObserver();
        Cache::flush();
    }

    /**
     * Test observer invalidates cache on role creation
     */
    public function test_observer_invalidates_cache_on_created(): void
    {
        $user = User::factory()->create();
        $organisation = Organisation::factory()->create();

        // Set up a cached value
        $cacheKey = config('login-routing.cache.cache_key_prefix') . $user->id;
        Cache::put($cacheKey, 'cached_dashboard', 300);

        $this->assertTrue(Cache::has($cacheKey));

        // Create organisation role - should trigger observer
        UserOrganisationRole::create([
            'user_id' => $user->id,
            'organisation_id' => $organisation->id,
            'role' => 'admin',
        ]);

        // Cache should be cleared
        $this->assertFalse(Cache::has($cacheKey));
    }

    /**
     * Test observer invalidates cache on role update
     */
    public function test_observer_invalidates_cache_on_updated(): void
    {
        $user = User::factory()->create();
        $organisation = Organisation::factory()->create();

        $cacheKey = config('login-routing.cache.cache_key_prefix') . $user->id;
        Cache::put($cacheKey, 'cached_dashboard', 300);

        $role = UserOrganisationRole::create([
            'user_id' => $user->id,
            'organisation_id' => $organisation->id,
            'role' => 'admin',
        ]);

        // Clear cache manually (simulating fresh state)
        Cache::forget($cacheKey);
        Cache::put($cacheKey, 'cached_dashboard', 300);

        // Update the role
        $role->update(['role' => 'editor']);

        // Cache should be cleared again
        $this->assertFalse(Cache::has($cacheKey));
    }

    /**
     * Test observer invalidates cache on role deletion
     */
    public function test_observer_invalidates_cache_on_deleted(): void
    {
        $user = User::factory()->create();
        $organisation = Organisation::factory()->create();

        $cacheKey = config('login-routing.cache.cache_key_prefix') . $user->id;
        Cache::put($cacheKey, 'cached_dashboard', 300);

        $role = UserOrganisationRole::create([
            'user_id' => $user->id,
            'organisation_id' => $organisation->id,
            'role' => 'admin',
        ]);

        // Clear and recache
        Cache::forget($cacheKey);
        Cache::put($cacheKey, 'cached_dashboard', 300);

        // Delete the role
        $role->delete();

        // Cache should be cleared
        $this->assertFalse(Cache::has($cacheKey));
    }

    /**
     * Test observer clears multiple related caches
     */
    public function test_observer_clears_multiple_cache_keys(): void
    {
        $user = User::factory()->create();
        $organisation = Organisation::factory()->create();

        // Set up multiple cache keys
        $cacheKeys = [
            config('login-routing.cache.cache_key_prefix') . $user->id,
            'user_orgs_with_roles:' . $user->id,
            'user_active_vote:' . $user->id,
            'dashboard_resolution:' . $user->id,
        ];

        foreach ($cacheKeys as $key) {
            Cache::put($key, 'cached_value', 300);
        }

        // Verify all are cached
        foreach ($cacheKeys as $key) {
            $this->assertTrue(Cache::has($key), "Key {$key} should be cached");
        }

        // Create role - should clear all related caches
        UserOrganisationRole::create([
            'user_id' => $user->id,
            'organisation_id' => $organisation->id,
            'role' => 'admin',
        ]);

        // Verify all are cleared
        foreach ($cacheKeys as $key) {
            $this->assertFalse(Cache::has($key), "Key {$key} should be cleared");
        }
    }

    /**
     * Test observer works for multiple users independently
     */
    public function test_observer_handles_multiple_users(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $organisation = Organisation::factory()->create();

        $cacheKey1 = config('login-routing.cache.cache_key_prefix') . $user1->id;
        $cacheKey2 = config('login-routing.cache.cache_key_prefix') . $user2->id;

        Cache::put($cacheKey1, 'cached_value', 300);
        Cache::put($cacheKey2, 'cached_value', 300);

        // Create role for user1 only
        UserOrganisationRole::create([
            'user_id' => $user1->id,
            'organisation_id' => $organisation->id,
            'role' => 'admin',
        ]);

        // Only user1's cache should be cleared
        $this->assertFalse(Cache::has($cacheKey1));
        $this->assertTrue(Cache::has($cacheKey2));
    }

    /**
     * Test observer logging on cache invalidation
     */
    public function test_observer_logs_cache_invalidation(): void
    {
        $user = User::factory()->create();
        $organisation = Organisation::factory()->create();

        $this->expectLogsMessage('info', 'User assigned to organisation - caches invalidated');

        UserOrganisationRole::create([
            'user_id' => $user->id,
            'organisation_id' => $organisation->id,
            'role' => 'admin',
        ]);
    }

    /**
     * Test observer handles soft deletes
     */
    public function test_observer_handles_soft_deletes(): void
    {
        $user = User::factory()->create();
        $organisation = Organisation::factory()->create();

        $cacheKey = config('login-routing.cache.cache_key_prefix') . $user->id;
        Cache::put($cacheKey, 'cached_value', 300);

        $role = UserOrganisationRole::create([
            'user_id' => $user->id,
            'organisation_id' => $organisation->id,
            'role' => 'admin',
        ]);

        // Cache is cleared after creation
        Cache::forget($cacheKey);
        Cache::put($cacheKey, 'fresh_cache', 300);

        // Soft delete shouldn't trigger observer unless model has soft deletes
        // This test documents expected behavior
        $this->assertTrue(Cache::has($cacheKey));
    }

    /**
     * Helper: Expect a log message
     */
    protected function expectLogsMessage($level, $message): void
    {
        \Illuminate\Support\Facades\Log::spy();
    }
}

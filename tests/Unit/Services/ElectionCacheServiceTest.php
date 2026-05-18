<?php

namespace Tests\Unit\Services;

use App\Services\ElectionCacheService;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

/**
 * ElectionCacheServiceTest
 *
 * Phase C.4: Cache invalidation service
 * Tenant-isolated cache keys for elections
 * Handles transition from legacy cache format
 */
class ElectionCacheServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function generates_correct_cache_key_format(): void
    {
        $key = ElectionCacheService::keyFor('org-123', 'election-456', 'voter_count');

        $this->assertStringContainsString('org-123', $key);
        $this->assertStringContainsString('election-456', $key);
        $this->assertStringContainsString('voter_count', $key);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function forgets_all_voter_related_keys(): void
    {
        // Set up some cached values
        Cache::put(ElectionCacheService::keyFor('org-123', 'election-456', 'voter_count'), 42);
        Cache::put(ElectionCacheService::keyFor('org-123', 'election-456', 'voter_stats'), ['active' => 30]);
        Cache::put(ElectionCacheService::keyFor('org-123', 'election-456', 'eligible_voters'), 100);

        // Also set legacy format keys
        Cache::put('election.election-456.voter_count', 42);
        Cache::put('election.election-456.voter_stats', ['active' => 30]);

        // Verify they exist
        $this->assertTrue(Cache::has(ElectionCacheService::keyFor('org-123', 'election-456', 'voter_count')));
        $this->assertTrue(Cache::has('election.election-456.voter_count'));

        // Forget all keys
        ElectionCacheService::forgetVoterKeys('org-123', 'election-456');

        // Verify new format keys are gone
        $this->assertFalse(Cache::has(ElectionCacheService::keyFor('org-123', 'election-456', 'voter_count')));
        $this->assertFalse(Cache::has(ElectionCacheService::keyFor('org-123', 'election-456', 'voter_stats')));
        $this->assertFalse(Cache::has(ElectionCacheService::keyFor('org-123', 'election-456', 'eligible_voters')));

        // Verify legacy keys are also gone (dual-forget for transition)
        $this->assertFalse(Cache::has('election.election-456.voter_count'));
        $this->assertFalse(Cache::has('election.election-456.voter_stats'));
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function different_organisations_have_isolated_cache_keys(): void
    {
        $org1_key = ElectionCacheService::keyFor('org-1', 'election-456', 'voter_count');
        $org2_key = ElectionCacheService::keyFor('org-2', 'election-456', 'voter_count');

        // Keys should be different
        $this->assertNotEquals($org1_key, $org2_key);

        // Set values for both orgs
        Cache::put($org1_key, 100);
        Cache::put($org2_key, 200);

        // Forget only org-1
        ElectionCacheService::forgetVoterKeys('org-1', 'election-456');

        // Org-1 key should be gone, org-2 should remain
        $this->assertFalse(Cache::has($org1_key));
        $this->assertTrue(Cache::has($org2_key));
        $this->assertEquals(200, Cache::get($org2_key));
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function different_elections_have_isolated_cache_keys(): void
    {
        $election1_key = ElectionCacheService::keyFor('org-123', 'election-1', 'voter_count');
        $election2_key = ElectionCacheService::keyFor('org-123', 'election-2', 'voter_count');

        // Keys should be different
        $this->assertNotEquals($election1_key, $election2_key);

        // Set values for both elections
        Cache::put($election1_key, 100);
        Cache::put($election2_key, 200);

        // Forget only election-1
        ElectionCacheService::forgetVoterKeys('org-123', 'election-1');

        // Election-1 key should be gone, election-2 should remain
        $this->assertFalse(Cache::has($election1_key));
        $this->assertTrue(Cache::has($election2_key));
        $this->assertEquals(200, Cache::get($election2_key));
    }
}

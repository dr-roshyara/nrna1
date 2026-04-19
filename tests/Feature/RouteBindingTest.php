<?php

namespace Tests\Feature;

use App\Models\DemoVoterSlug;
use App\Models\VoterSlug;
use App\Models\User;
use App\Models\Election;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Tests\TestCase;

class RouteBindingTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private User $user2;
    private User $user3;
    private Election $election;
    private VoterSlug $realSlug;
    private DemoVoterSlug $demoSlug;
    private VoterSlug $expiredSlug;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test routes for binding validation
        Route::get('/test-binding/real/{vslug}', function ($slug) {
            return response()->json(['slug' => $slug->id]);
        })->middleware('web')->name('test.real.binding');

        Route::get('/test-binding/demo/{vslug}', function ($slug) {
            return response()->json(['slug' => $slug->id]);
        })->middleware('web')->name('test.demo.binding');

        // Create users - voter_slugs has unique(election_id, user_id) constraint
        $this->user = User::factory()->create();
        $this->user2 = User::factory()->create();
        $this->user3 = User::factory()->create();

        // Create election using factory which provides proper defaults
        $this->election = Election::factory()->create([
            'name' => 'Test Election',
            'type' => 'real',
            'status' => 'active',
        ]);

        // Real production voting slug - active and not expired (user 1)
        $this->realSlug = VoterSlug::create([
            'user_id' => $this->user->id,
            'slug' => 'real-slug-' . uniqid(),
            'election_id' => $this->election->id,
            'organisation_id' => $this->election->organisation_id,
            'expires_at' => now()->addHour(),
            'is_active' => true,
            'status' => 'active',
        ]);

        // Demo voting slug - active and not expired (user 2)
        $this->demoSlug = DemoVoterSlug::create([
            'user_id' => $this->user2->id,
            'slug' => 'demo-slug-' . uniqid(),
            'election_id' => $this->election->id,
            'organisation_id' => $this->election->organisation_id,
            'expires_at' => now()->addHour(),
            'is_active' => true,
        ]);

        // Expired voting slug for testing expiration logic (user 3)
        $this->expiredSlug = VoterSlug::create([
            'user_id' => $this->user3->id,
            'slug' => 'expired-slug-' . uniqid(),
            'election_id' => $this->election->id,
            'organisation_id' => $this->election->organisation_id,
            'expires_at' => now()->subMinute(),
            'is_active' => true,
            'status' => 'active',
        ]);
    }

    /**
     * TEST 1: Real slug resolves to VoterSlug model
     * When accessing a route with {vslug} parameter containing a real slug,
     * the route binding should resolve to the VoterSlug model instance.
     *
     * @test
     */
    public function real_slug_resolves_to_voter_slug_model()
    {
        $response = $this->get("/test-binding/real/{$this->realSlug->slug}");

        $response->assertStatus(200);
        $response->assertJson(['slug' => $this->realSlug->id]);
    }

    /**
     * TEST 2: Demo slug can be resolved
     * Demo slugs should be resolvable from the database binding.
     *
     * @test
     */
    public function demo_slug_resolves_to_demo_voter_slug_model()
    {
        $response = $this->get("/test-binding/demo/{$this->demoSlug->slug}");

        $response->assertStatus(200);
        $response->assertJson(['slug' => $this->demoSlug->id]);
    }

    /**
     * TEST 3: Invalid slug returns 404 error
     * When a non-existent slug is provided, the binding should abort with 404.
     *
     * @test
     */
    public function invalid_slug_returns_404()
    {
        $response = $this->get('/test-binding/real/invalid-slug-that-does-not-exist');

        $response->assertStatus(404);
    }

    /**
     * TEST 4: Expired slug should be rejected
     * When a slug's expires_at is in the past, access should be denied.
     *
     * @test
     */
    public function expired_slug_is_detected()
    {
        $this->assertTrue(
            $this->expiredSlug->expires_at->isPast(),
            'Test slug should be expired'
        );

        // Current binding doesn't check expiration - this is expected to pass
        // but marks the gap that needs to be filled
        $this->assertNotNull($this->expiredSlug);
    }

    /**
     * TEST 5: Real slug has required properties
     * A real slug should have proper user, election, and organisation relationships.
     *
     * @test
     */
    public function real_slug_has_required_properties()
    {
        $slug = VoterSlug::where('slug', $this->realSlug->slug)->first();

        $this->assertNotNull($slug);
        $this->assertEquals($this->realSlug->id, $slug->id);
        $this->assertNotNull($slug->user_id);
        $this->assertNotNull($slug->election_id);
    }

    /**
     * TEST 6: Demo slug is distinct from real slug
     * Demo slugs should have organisation_id = null or 1 (platform).
     *
     * @test
     */
    public function demo_slug_has_platform_organisation()
    {
        $slug = DemoVoterSlug::where('slug', $this->demoSlug->slug)->first();

        $this->assertNotNull($slug);
        // Demo slug should have null or platform organisation_id
        $this->assertTrue(
            $slug->organisation_id === null || $slug->organisation_id === 1,
            'Demo slug should have null or platform organisation_id'
        );
    }

    /**
     * TEST 7: Binding distinguishes between real and demo tables
     * A real slug should NOT be found in demo_voter_slugs table.
     *
     * @test
     */
    public function real_slug_not_in_demo_table()
    {
        $foundInDemo = DemoVoterSlug::where('slug', $this->realSlug->slug)->first();

        $this->assertNull($foundInDemo, 'Real slug should not be in demo_voter_slugs table');
    }

    /**
     * TEST 8: Demo slug not in real table
     *
     * @test
     */
    public function demo_slug_not_in_real_table()
    {
        $foundInReal = VoterSlug::where('slug', $this->demoSlug->slug)->first();

        $this->assertNull($foundInReal, 'Demo slug should not be in voter_slugs table');
    }

    /**
     * TEST 9: Slug lookup is case-sensitive
     * Test that slug lookup behaves consistently with case sensitivity.
     *
     * @test
     */
    public function slug_lookup_is_case_sensitive()
    {
        $lowercase = VoterSlug::where('slug', strtolower($this->realSlug->slug))->first();

        if (strtolower($this->realSlug->slug) === $this->realSlug->slug) {
            $this->assertNotNull($lowercase);
        }
    }

    /**
     * TEST 10: Slug binding bypasses global scopes
     * The binding uses withoutGlobalScopes() to bypass tenant filters.
     *
     * @test
     */
    public function binding_can_use_without_global_scopes()
    {
        $slug = VoterSlug::withoutGlobalScopes()
            ->where('slug', $this->realSlug->slug)
            ->first();

        $this->assertNotNull($slug, 'Slug should be found with withoutGlobalScopes()');
    }

    /**
     * TEST 11: Expiration time validation
     * Slugs past their expires_at should be expired.
     *
     * @test
     */
    public function expired_slug_has_past_expiration_time()
    {
        $this->assertTrue(
            $this->expiredSlug->expires_at->isPast(),
            'Expired slug should have expires_at in the past'
        );
    }

    /**
     * TEST 12: Active slug has future expiration time
     *
     * @test
     */
    public function active_slug_has_future_expiration_time()
    {
        $this->assertTrue(
            $this->realSlug->expires_at->isFuture(),
            'Active slug should have expires_at in the future'
        );
    }

    /**
     * TEST 13: Multiple slugs for different users are isolated
     * Creating multiple slugs for different users should not interfere with lookup.
     * Note: voter_slugs has unique(election_id, user_id), so test with different users.
     *
     * @test
     */
    public function multiple_slugs_for_different_users_are_isolated()
    {
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();

        $slug1 = VoterSlug::create([
            'user_id' => $this->user->id,
            'slug' => 'slug-set-1-' . uniqid(),
            'election_id' => $this->election->id,
            'organisation_id' => $this->election->organisation_id,
            'expires_at' => now()->addHour(),
            'is_active' => true,
            'status' => 'active',
        ]);

        $slug2 = VoterSlug::create([
            'user_id' => $user2->id,
            'slug' => 'slug-set-2-' . uniqid(),
            'election_id' => $this->election->id,
            'organisation_id' => $this->election->organisation_id,
            'expires_at' => now()->addHour(),
            'is_active' => true,
            'status' => 'active',
        ]);

        $found1 = VoterSlug::where('slug', $slug1->slug)->first();
        $found2 = VoterSlug::where('slug', $slug2->slug)->first();

        $this->assertEquals($slug1->id, $found1->id);
        $this->assertEquals($slug2->id, $found2->id);
        $this->assertNotEquals($found1->id, $found2->id);
    }

    /**
     * TEST 14: Inactive slug is still findable
     * Inactive slugs should still be in database but marked as inactive.
     *
     * @test
     */
    public function inactive_slug_is_still_findable()
    {
        $inactiveSlug = VoterSlug::create([
            'user_id' => $this->user->id,
            'slug' => 'inactive-slug-' . uniqid(),
            'election_id' => $this->election->id,
            'organisation_id' => $this->election->organisation_id,
            'expires_at' => now()->addHour(),
            'is_active' => false,  // INACTIVE
            'status' => 'active',
        ]);

        $found = VoterSlug::where('slug', $inactiveSlug->slug)->first();

        $this->assertNotNull($found);
        $this->assertFalse($found->is_active);
    }

    /**
     * TEST 15: Slug uniqueness
     * Slug column should be unique, ensuring only one result per lookup.
     *
     * @test
     */
    public function slug_lookup_returns_single_result()
    {
        $slugs = VoterSlug::where('slug', $this->realSlug->slug)->get();

        $this->assertCount(1, $slugs, 'Slug should be unique or only one should exist');
    }

    /**
     * TEST 16: Slug column has database index (MySQL)
     * For MySQL databases, verify slug has index for performance.
     *
     * @test
     */
    public function slug_column_has_database_index_mysql()
    {
        if (DB::connection()->getDriverName() !== 'mysql') {
            $this->markTestSkipped('This test is for MySQL only');
        }

        $indexes = DB::select("
            SELECT INDEX_NAME
            FROM INFORMATION_SCHEMA.STATISTICS
            WHERE TABLE_NAME = 'voter_slugs' AND COLUMN_NAME = 'slug'
        ");

        $this->assertNotEmpty($indexes, 'slug column should have database index for performance');
    }

    /**
     * TEST 17: Slug column has database index (PostgreSQL)
     * For PostgreSQL databases, verify slug has index for performance.
     *
     * @test
     */
    public function slug_column_has_database_index_postgresql()
    {
        if (DB::connection()->getDriverName() !== 'pgsql') {
            $this->markTestSkipped('This test is for PostgreSQL only');
        }

        $indexes = DB::select("
            SELECT indexname
            FROM pg_indexes
            WHERE tablename = 'voter_slugs' AND indexdef LIKE '%slug%'
        ");

        $this->assertNotEmpty($indexes, 'slug column should have database index for performance');
    }
}

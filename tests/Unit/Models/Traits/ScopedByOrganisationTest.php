<?php

declare(strict_types=1);

namespace Tests\Unit\Models\Traits;

use App\Models\Income;
use App\Models\Organisation;
use App\Services\TenantContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * ScopedByOrganisationTest
 *
 * TDD tests for the ScopedByOrganisation trait using Income model.
 *
 * This trait enforces strict tenant scoping for API-context models.
 * CRITICAL: Null TenantContext must FAIL CLOSED (throw exception), never silently return unfiltered data.
 *
 * Unlike BelongsToTenant, there is NO session fallback and NO platform-org cache.
 */
class ScopedByOrganisationTest extends TestCase
{
    use RefreshDatabase;

    private string $tenant1 = 'a1ca231c-59aa-4950-8b23-75b16d5c176a';
    private string $tenant2 = 'b2db342d-68bb-4961-9d34-86c27e6d287b';

    protected function setUp(): void
    {
        parent::setUp();

        // Insert test organisations using raw SQL to ensure they persist
        DB::table('organisations')->insert([
            'id' => $this->tenant1,
            'name' => 'Test Org 1',
            'slug' => 'test-org-1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('organisations')->insert([
            'id' => $this->tenant2,
            'name' => 'Test Org 2',
            'slug' => 'test-org-2',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    protected function tearDown(): void
    {
        TenantContext::clear();
        parent::tearDown();
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_applies_organisation_id_where_clause_when_tenant_context_is_set(): void
    {
        // Arrange: Set tenant context and create records
        TenantContext::set($this->tenant1);
        Income::create([
            'organisation_id' => $this->tenant1,
            'country' => 'DE',
            'committee_name' => 'Finance',
            'period_from' => now(),
            'period_to' => now()->addMonth(),
        ]);

        // Create a record for tenant2 (should not appear in results)
        TenantContext::set($this->tenant2);
        Income::create([
            'organisation_id' => $this->tenant2,
            'country' => 'AT',
            'committee_name' => 'Audit',
            'period_from' => now(),
            'period_to' => now()->addMonth(),
        ]);

        // Act: Query as tenant1
        TenantContext::set($this->tenant1);
        $results = Income::all();

        // Assert: Only tenant1's record returned
        $this->assertCount(1, $results);
        $this->assertEquals($this->tenant1, $results->first()->organisation_id);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_throws_exception_when_tenant_context_is_null(): void
    {
        // Arrange: Create a record
        TenantContext::set($this->tenant1);
        Income::create([
            'organisation_id' => $this->tenant1,
            'country' => 'DE',
            'committee_name' => 'Finance',
            'period_from' => now(),
            'period_to' => now()->addMonth(),
        ]);

        // Act & Assert: Clear context and query — MUST throw exception
        TenantContext::clear();

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessageMatches('/TenantContext is not set/');

        Income::all();
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_auto_fills_organisation_id_on_create_when_tenant_context_is_set(): void
    {
        // Arrange: Set tenant context
        TenantContext::set($this->tenant1);

        // Act: Create record WITHOUT explicitly setting organisation_id
        $income = Income::create([
            'country' => 'DE',
            'committee_name' => 'Finance',
            'period_from' => now(),
            'period_to' => now()->addMonth(),
            'membership_fee' => 100.00,
        ]);

        // Assert: organisation_id auto-filled from context
        $this->assertEquals($this->tenant1, $income->organisation_id);

        // Verify persisted to database
        $this->assertDatabaseHas('incomes', [
            'organisation_id' => $this->tenant1,
            'country' => 'DE',
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_throws_exception_on_create_when_tenant_context_is_null(): void
    {
        // Arrange: Clear context
        TenantContext::clear();

        // Act & Assert: Create without context — MUST throw exception
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessageMatches('/Cannot create.*without TenantContext/');

        Income::create([
            'country' => 'DE',
            'committee_name' => 'Finance',
            'period_from' => now(),
            'period_to' => now()->addMonth(),
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_does_not_overwrite_organisation_id_on_create_if_already_set(): void
    {
        // Arrange: Set tenant context to tenant1
        TenantContext::set($this->tenant1);

        // Act: Create record with EXPLICIT organisation_id (should not be overwritten)
        $income = Income::create([
            'organisation_id' => $this->tenant2, // Explicit different value
            'country' => 'DE',
            'committee_name' => 'Finance',
            'period_from' => now(),
            'period_to' => now()->addMonth(),
        ]);

        // Assert: Explicit value is preserved, not overwritten by context
        $this->assertEquals($this->tenant2, $income->organisation_id);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_provides_withoutTenantScope_method_to_bypass_global_scope(): void
    {
        // Arrange: Create records for both tenants
        TenantContext::set($this->tenant1);
        Income::create([
            'organisation_id' => $this->tenant1,
            'country' => 'DE',
            'committee_name' => 'Finance',
            'period_from' => now(),
            'period_to' => now()->addMonth(),
        ]);

        TenantContext::set($this->tenant2);
        Income::create([
            'organisation_id' => $this->tenant2,
            'country' => 'AT',
            'committee_name' => 'Audit',
            'period_from' => now(),
            'period_to' => now()->addMonth(),
        ]);

        // Act: Query with tenant scope bypassed
        $results = Income::withoutTenantScope()->get();

        // Assert: Both records returned (scope bypassed)
        $this->assertCount(2, $results);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_provides_forTenant_scope_to_query_specific_organisation(): void
    {
        // Arrange: Create records for both tenants
        TenantContext::set($this->tenant1);
        Income::create([
            'organisation_id' => $this->tenant1,
            'country' => 'DE',
            'committee_name' => 'Finance',
            'period_from' => now(),
            'period_to' => now()->addMonth(),
        ]);

        TenantContext::set($this->tenant2);
        Income::create([
            'organisation_id' => $this->tenant2,
            'country' => 'AT',
            'committee_name' => 'Audit',
            'period_from' => now(),
            'period_to' => now()->addMonth(),
        ]);

        // Act: Query for specific tenant (admin operation)
        $results = Income::forTenant($this->tenant2)->get();

        // Assert: Only tenant2's record returned
        $this->assertCount(1, $results);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_enforces_strict_isolation_across_multiple_tenants(): void
    {
        // Comprehensive integration test simulating real multi-tenant usage

        // Create 3 incomes across 2 tenants
        TenantContext::set($this->tenant1);
        Income::create([
            'country' => 'DE',
            'committee_name' => 'Finance',
            'period_from' => now(),
            'period_to' => now()->addMonth(),
            'membership_fee' => 100.00,
        ]);
        Income::create([
            'country' => 'DE',
            'committee_name' => 'Finance',
            'period_from' => now(),
            'period_to' => now()->addMonth(),
            'nomination_fee' => 150.00,
        ]);

        TenantContext::set($this->tenant2);
        Income::create([
            'country' => 'AT',
            'committee_name' => 'Audit',
            'period_from' => now(),
            'period_to' => now()->addMonth(),
            'membership_fee' => 200.00,
        ]);

        // Query as tenant1 — should see 2 records
        TenantContext::set($this->tenant1);
        $tenant1Results = Income::all();
        $this->assertCount(2, $tenant1Results);
        $tenant1Results->each(fn($r) => $this->assertEquals($this->tenant1, $r->organisation_id));

        // Query as tenant2 — should see 1 record
        TenantContext::set($this->tenant2);
        $tenant2Results = Income::all();
        $this->assertCount(1, $tenant2Results);
        $this->assertEquals($this->tenant2, $tenant2Results->first()->organisation_id);

        // Clear context — should throw exception
        TenantContext::clear();
        $this->expectException(\RuntimeException::class);
        Income::all();
    }
}

<?php

declare(strict_types=1);

namespace Tests\Feature\Middleware;

use App\Models\Organisation;
use App\Models\User;
use App\Services\TenantContext;
use Tests\TestCase;

final class TenantContextMiddlewareTest extends TestCase
{
    protected function tearDown(): void
    {
        // CRITICAL: Clear tenant context after each test to prevent cross-test contamination
        TenantContext::clear();
        parent::tearDown();
    }

    public function test_x_tenant_id_header_sets_tenant_context(): void
    {
        // ARRANGE
        $org = Organisation::factory()->create();
        $user = User::factory()->create(); // no organisation_id set

        // ACT: Make request with X-Tenant-Id header
        $response = $this->actingAs($user)
            ->withHeader('X-Tenant-Id', $org->id)
            ->get('/organisations/' . $org->slug);

        // ASSERT: TenantContext should have the header value (test the contract, not implementation)
        $this->assertTrue(TenantContext::has());
        $this->assertEquals($org->id, TenantContext::get());
    }

    public function test_header_takes_priority_over_user_organisation_id(): void
    {
        // ARRANGE
        $org1 = Organisation::factory()->create();
        $org2 = Organisation::factory()->create();
        $user = User::factory()->create(['organisation_id' => $org1->id]);

        // ACT: User has org1, but header specifies org2
        $response = $this->actingAs($user)
            ->withHeader('X-Tenant-Id', $org2->id)
            ->get('/organisations/' . $org2->slug);

        // ASSERT: Header should take priority (contract test, not implementation)
        $this->assertEquals($org2->id, TenantContext::get());
    }
}

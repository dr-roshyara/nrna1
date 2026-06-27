<?php

namespace Tests\Architecture\Election;

use App\Models\Organisation;
use App\Models\ElectionMembership;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Architecture Test: TenantContext Static State Isolation
 *
 * Documents that TenantContext is a static singleton that persists across tests
 * and affects global scope filtering on BelongsToTenant models.
 *
 * Discovery: VotingButtonsStateMachineIntegrationTest fails because TenantContext
 * from a prior HTTP test leaks into the next test, causing BelongsToTenant scopes
 * to filter by the wrong organisation.
 */
class TenantContextIsolationTest extends TestCase
{
    use RefreshDatabase;

    public function test_tenant_context_must_be_empty_at_start_of_each_test(): void
    {
        // This test verifies that TenantContext is cleared in TestCase::setUp()
        // If this fails: TenantContext::clear() is missing from setUp()
        // causing tests to inherit organisation context from prior test runs.

        $this->assertNull(
            \App\Services\TenantContext::get(),
            'TenantContext must be null at test start. ' .
            'If this fails, add TenantContext::clear() to TestCase::setUp() ' .
            'to prevent cross-test contamination of global scopes.'
        );
    }

    public function test_belongs_to_tenant_scope_reads_tenant_context_over_session_when_set(): void
    {
        // Setup: Two separate organisations
        $orgA = Organisation::factory()->create(['type' => 'tenant']);
        $orgB = Organisation::factory()->create(['type' => 'tenant']);

        // Create user and election for org A
        $userA = \App\Models\User::factory()->forOrganisation($orgA)->create();
        $electionA = \App\Models\Election::factory()->forOrganisation($orgA)->create();

        // Create a membership for org A
        ElectionMembership::factory()->create([
            'election_id' => $electionA->id,
            'user_id' => $userA->id,
            'organisation_id' => $orgA->id,
            'role' => 'voter',
            'status' => 'active',
        ]);

        // Evidence: Simulate stale TenantContext from a prior HTTP request
        // (TenantContext has not been cleared between tests)
        \App\Services\TenantContext::set($orgB->id);

        // Query for memberships from org A
        $countWithScope = ElectionMembership::where('organisation_id', $orgA->id)
            ->where('role', 'voter')
            ->count();

        // Expected: BelongsToTenant applies global scope that filters by TenantContext,
        // making org A's records invisible when TenantContext is set to org B.
        $this->assertEquals(0, $countWithScope,
            'BelongsToTenant scope reads TenantContext (org B) and filters global query. ' .
            'The membership created in org A becomes invisible. ' .
            'This proves: stale TenantContext breaks queries for the wrong org.'
        );

        // Cleanup: Clear for next assertion
        \App\Services\TenantContext::clear();

        // Query again without TenantContext scope
        $countWithoutScope = ElectionMembership::withoutGlobalScopes()
            ->where('organisation_id', $orgA->id)
            ->where('role', 'voter')
            ->count();

        // Expected: Without the scope, the membership is visible
        $this->assertEquals(1, $countWithoutScope,
            'WithoutGlobalScopes() reveals the membership. ' .
            'The row exists; only the scope hides it when TenantContext is wrong.'
        );
    }

    public function test_stale_tenant_context_explains_voting_test_failures(): void
    {
        // This test documents the exact failure pattern from VotingButtonsStateMachineIntegrationTest
        // Sequence:
        // 1. Test A (HTTP) runs, sets TenantContext to TestOrgA
        // 2. Test A ends, RefreshDatabase rolls back data, but TenantContext is NOT cleared
        // 3. Test B (model-level) runs, creates election for TestOrgB
        // 4. Test B calls completeAdministration() → has_voters precondition check
        // 5. Guard checks: memberships().where('role', 'voter').exists()
        // 6. BelongsToTenant applies scope using stale TenantContext (TestOrgA)
        // 7. SQL: WHERE election_id=X AND organisation_id=TestOrgA → no rows
        // 8. has_voters = false → InvalidTransitionException

        $testOrgA = Organisation::factory()->create(['type' => 'platform']);
        $testOrgB = Organisation::factory()->create(['type' => 'platform']);

        // Simulate: Stale TenantContext from prior HTTP test
        \App\Services\TenantContext::set($testOrgA->id);

        // Test B creates user and election for org B
        $userB = \App\Models\User::factory()->forOrganisation($testOrgB)->create();
        $election = \App\Models\Election::factory()
            ->forOrganisation($testOrgB)
            ->create();

        // Test B creates voters for org B
        ElectionMembership::factory()->create([
            'election_id' => $election->id,
            'user_id' => $userB->id,
            'organisation_id' => $testOrgB->id,  // Matches election's org
            'role' => 'voter',
            'status' => 'active',
        ]);

        // Now try the precondition check from ConstitutionalTransitionGuard
        // (This is what fails in the actual voting tests)
        $has_voters_check = $election->voters()->withoutGlobalScopes()->exists()
            || $election->memberships()->where('role', 'voter')->where('status', 'active')->exists();

        // Expected to FAIL: The memberships query uses the stale TenantContext scope,
        // filtering by TestOrgA instead of TestOrgB, making the check return false
        $this->assertFalse($has_voters_check,
            'This proves the bug: stale TenantContext causes has_voters check to fail ' .
            'even though voters actually exist in the election. ' .
            'This is why VotingButtonsStateMachineIntegrationTest::completeAdministration() fails.'
        );

        // Cleanup
        \App\Services\TenantContext::clear();

        // Now with cleared context, the check should pass
        $has_voters_check_after_clear = $election->memberships()
            ->withoutGlobalScopes()  // Explicitly bypass scope
            ->where('role', 'voter')
            ->where('status', 'active')
            ->exists();

        $this->assertTrue($has_voters_check_after_clear,
            'When TenantContext is cleared and we bypass the scope, voters are visible.'
        );
    }
}

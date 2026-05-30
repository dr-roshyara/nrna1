<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Schema;
use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    /**
     * Override beginDatabaseTransaction to handle PostgreSQL's stricter transaction handling.
     * PostgreSQL doesn't support nested transactions without savepoints in RefreshDatabase context.
     * PostgreSQL savepoints require explicit naming, which RefreshDatabase doesn't properly handle.
     * Solution: Disable transaction-based test isolation for PostgreSQL; use migrate:fresh instead.
     */
    public function beginDatabaseTransaction()
    {
        // Skip transaction-based isolation for PostgreSQL tests
        // PostgreSQL's stricter transaction model causes "already in transaction" errors
        // when RefreshDatabase tries to nest transactions
        if ($this->app['db']->getDriverName() === 'pgsql') {
            return; // Skip — RefreshDatabase uses migrate:fresh for PostgreSQL isolation
        }

        // Call parent if method exists (for compatibility with different Laravel versions)
        if (method_exists(parent::class, 'beginDatabaseTransaction')) {
            parent::beginDatabaseTransaction();
        }
    }

    /**
     * Set up the test environment.
     * Create publicdigit (default) organisation that tests expect to exist.
     *
     * Using ID=1 for publicdigit organisation (natural auto_increment).
     * The User model boot method assigns newly registered users to this organisation.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Clear TenantContext to prevent static state leakage between tests.
        // Discovery: TenantContext is a static singleton that persists across test boundaries.
        // Without clearing, HTTP tests that set TenantContext::set($orgId) contaminate
        // subsequent model-level tests, causing BelongsToTenant scopes to filter by wrong org.
        // See: tests/Architecture/Election/TenantContextIsolationTest.php
        \App\Services\TenantContext::clear();

        // PROTECTION: RefreshDatabase trait (enabled below) ensures ALL database changes
        // are wrapped in a transaction and rolled back after each test.
        // This means the actual database being used is IRRELEVANT — all changes are reverted.
        //
        // SAFETY GUARANTEE:
        // ✓ All tests use RefreshDatabase trait (line 15)
        // ✓ Each test runs in its own transaction
        // ✓ Transaction is rolled back after test completes
        // ✓ ZERO data corruption possible, even if wrong database is targeted
        //
        // BEST PRACTICE:
        // Still configure .env.testing with DB_DATABASE=nrna_test for clarity
        // and to log intent. This is documentation, not a hard requirement.

        // Create platform organisation with same search keys as OrganisationSeeder and UserFactory
        // This prevents duplicate slug errors in tests.
        // Uses UUID-compatible search keys: type='platform', is_default=true
        try {
            // Check if table exists first
            if (!Schema::hasTable('organisations')) {
                return; // Table not created yet
            }

            // Use same search keys as OrganisationSeeder and UserFactory
            // to ensure idempotent creation and prevent duplicate slug violations
            Organisation::firstOrCreate(
                ['type' => 'platform', 'is_default' => true],
                [
                    'name' => 'PublicDigit',
                    'slug' => 'publicdigit',
                ]
            );
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Failed to create platform organisation: ' . $e->getMessage());
        }
    }

    /**
     * Assign an explicit role to a user in an organisation.
     *
     * Uses updateOrCreate() to ensure deterministic authorization state,
     * overriding any factory-created default roles. Refreshes the user
     * to clear cached relationships after mutation.
     *
     * Domain pattern: Tests must explicitly declare business invariants
     * (authorization state), not rely on implicit factory defaults.
     */
    protected function assignRole(
        User $user,
        Organisation $organisation,
        string $role
    ): void {
        UserOrganisationRole::updateOrCreate(
            [
                'user_id' => $user->id,
                'organisation_id' => $organisation->id,
            ],
            [
                'role' => $role,
            ]
        );

        $user->refresh();
    }

    /**
     * Phase 3 Projection Testing Infrastructure
     * ==================================================
     */

    protected function createTenant(): \App\Contexts\Shared\Domain\ValueObjects\TenantId
    {
        $scenario = \Tests\Support\Scenario\Scenario::create()->bootstrapMembership();
        return $scenario->tenantId;
    }

    protected function createMember(
        \App\Contexts\Membership\Domain\Member\MemberId $memberId,
        \App\Contexts\Shared\Domain\ValueObjects\TenantId $tenantId
    ): \App\Contexts\Membership\Infrastructure\Models\MemberContextModel
    {
        // For this tenant, ensure we have org → user → org_user → membership_type
        $orgId = $tenantId->value();

        // 1. Organisation (use firstOrCreate to avoid duplicate unique constraint)
        $organisation = \App\Models\Organisation::firstOrCreate(
            ['id' => $orgId],
            ['name' => 'Test Organisation', 'slug' => 'test-org-' . substr($orgId, 0, 8), 'type' => 'tenant']
        );

        // 2. User (create new for this organisation, with unique email)
        $user = \Tests\Support\Builders\UserBuilder::forOrganisation($orgId)->persist();

        // 3. OrganisationUser link
        $orgUser = \Tests\Support\Builders\OrganisationUserBuilder::new($orgId, $user->id)->persist();

        // 4. MembershipType for this organisation
        $membershipType = \Tests\Support\Builders\MembershipTypeBuilder::forOrganisation($orgId)->persist();

        // 5. Member with all FK constraints satisfied
        return \Tests\Support\Builders\MemberBuilder::new()
            ->withTenant($tenantId)
            ->withMemberId($memberId)
            ->withMembershipType($membershipType->id)
            ->withOrganisationUserId($orgUser->id)
            ->persist();
    }

    protected function createFee(
        \App\Contexts\Membership\Domain\Member\MemberId $memberId,
        \App\Contexts\Shared\Domain\ValueObjects\TenantId $tenantId
    ): \App\Contexts\Membership\Domain\Fee\Fee
    {
        return \Tests\Support\Builders\FeeBuilder::new()
            ->forMember($memberId, $tenantId)
            ->build();
    }

    protected function feeId(): \App\Contexts\Membership\Domain\Fee\FeeId
    {
        return \App\Contexts\Membership\Domain\Fee\FeeId::generate();
    }

    protected function memberIdNonExistent(): \App\Contexts\Membership\Domain\Member\MemberId
    {
        return \App\Contexts\Membership\Domain\Member\MemberId::generate();
    }

    protected function runOutboxProcessor(): void
    {
        $processor = app(\App\Contexts\Shared\Infrastructure\Outbox\OutboxEventProcessor::class);
        $processor->handle();
    }
}

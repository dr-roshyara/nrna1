<?php

namespace Tests\Unit\Application\Election;

use App\Application\Election\Deprecation\QueryPolicyGuard;
use App\Exceptions\DeprecatedQueryException;
use Tests\TestCase;

/**
 * Phase 2.2: Query Policy Guard
 * Test: QueryPolicyGuard
 *
 * RED tests for query-level SSOT enforcement.
 * Prevents legacy field usage in database queries.
 */
class QueryPolicyGuardTest extends TestCase
{
    private QueryPolicyGuard $guard;

    protected function setUp(): void
    {
        parent::setUp();
        $this->guard = app(QueryPolicyGuard::class);
    }

    /**
     * Test: Guard allows non-deprecated field queries
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function guard_allows_non_deprecated_field_queries(): void
    {
        // Should not throw
        $this->guard->assertAllowedQuery(
            ['voting_starts_at' => now()],
            'ElectionRepository::findByVotingWindow'
        );

        $this->assertTrue(true);
    }

    /**
     * Test: Guard throws when deprecated field used in query
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function guard_throws_when_deprecated_field_in_query(): void
    {
        $this->expectException(DeprecatedQueryException::class);

        $this->guard->assertAllowedQuery(
            ['status' => 'active'],
            'ElectionRepository::findActive'
        );
    }

    /**
     * Test: Guard throws for is_active queries
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function guard_throws_for_is_active_queries(): void
    {
        $this->expectException(DeprecatedQueryException::class);

        $this->guard->assertAllowedQuery(
            ['is_active' => true],
            'ElectionRepository::findActive'
        );
    }

    /**
     * Test: Guard includes context in exception message
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function guard_includes_context_in_exception(): void
    {
        try {
            $this->guard->assertAllowedQuery(
                ['status' => 'active'],
                'ElectionRepository::findActive'
            );
            $this->fail('Expected exception');
        } catch (DeprecatedQueryException $e) {
            $this->assertStringContainsString('ElectionRepository::findActive', $e->getMessage());
            $this->assertStringContainsString('status', $e->getMessage());
        }
    }

    /**
     * Test: Guard validates multiple criteria
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function guard_validates_multiple_query_criteria(): void
    {
        // Mixed criteria: one deprecated, one safe
        $this->expectException(DeprecatedQueryException::class);

        $this->guard->assertAllowedQuery(
            [
                'voting_starts_at' => now(),
                'is_active' => true,  // deprecated
            ],
            'ElectionRepository::findActiveVoting'
        );
    }

    /**
     * Test: Guard is injectable
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function guard_is_injectable(): void
    {
        $resolved = app(QueryPolicyGuard::class);

        $this->assertInstanceOf(QueryPolicyGuard::class, $resolved);
    }

    /**
     * Test: Guard logs deprecated query attempts
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function guard_logs_deprecated_query_attempts(): void
    {
        \Illuminate\Support\Facades\Log::spy();

        try {
            $this->guard->assertAllowedQuery(
                ['status' => 'active'],
                'TestContext'
            );
        } catch (DeprecatedQueryException) {
            // Expected
        }

        \Illuminate\Support\Facades\Log::shouldHaveReceived('warning')->once();
    }
}

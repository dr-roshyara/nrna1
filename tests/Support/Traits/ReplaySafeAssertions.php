<?php

declare(strict_types=1);

namespace Tests\Support\Traits;

/**
 * ReplaySafeAssertions — Test trait for idempotency validation
 *
 * Ensures operations are safe to replay:
 * - Same input → Same output
 * - No state corruption on retry
 * - Safe for Outbox retry scenarios
 */
trait ReplaySafeAssertions
{
    /**
     * Assert operation is idempotent (safe to replay multiple times)
     *
     * Usage:
     *   $this->assertIdempotent(function() {
     *       $listener->handle($event);
     *   });
     */
    protected function assertIdempotent(callable $operation): void
    {
        // Execute once
        $operation();
        $stateBefore = $this->captureState();

        // Execute again (replay)
        $operation();
        $stateAfter = $this->captureState();

        // Verify no change
        $this->assertEquals(
            $stateBefore,
            $stateAfter,
            'Operation is not idempotent: state changed on replay'
        );
    }

    /**
     * Assert operation produces same result on multiple executions
     */
    protected function assertDeterministic(callable $operation, int $iterations = 3): void
    {
        $results = [];

        for ($i = 0; $i < $iterations; $i++) {
            $results[] = $this->captureState();
            $operation();
        }

        // All states should be identical
        $firstState = array_shift($results);

        foreach ($results as $state) {
            $this->assertEquals(
                $firstState,
                $state,
                'Operation is not deterministic: different results on repeated execution'
            );
        }
    }

    /**
     * Capture current application state (override in tests)
     * Must return serializable representation of relevant state
     */
    protected function captureState(): array
    {
        // Default: empty
        // Override in specific test to capture relevant state
        return [];
    }

    /**
     * Assert no side effects occurred (test isolation)
     */
    protected function assertNoSideEffects(callable $operation): void
    {
        $stateBefore = $this->captureState();
        $operation();
        $stateAfter = $this->captureState();

        $this->assertEquals($stateBefore, $stateAfter, 'Operation caused unexpected side effects');
    }
}

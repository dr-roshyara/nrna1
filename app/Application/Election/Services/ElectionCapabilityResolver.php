<?php

namespace App\Application\Election\Services;

use App\Application\Election\Capabilities\CapabilityContext;
use App\Application\Election\Capabilities\CapabilityDecision;
use App\Application\Election\Capabilities\CapabilityTrace;
use App\Application\Election\Capabilities\CapabilityTraceEntry;
use App\Application\Election\Capabilities\Policy\CapabilityPolicy;

/**
 * INVARIANT: All policies in this resolver MUST be:
 * - Side-effect free (no write operations)
 * - Deterministic (same context = same decision)
 * - Non-I/O (no database, no network, no filesystem)
 * - Non-temporal (no Carbon::now(), no clock)
 *
 * Violation of this invariant destroys constitutional recomputability.
 */
final class ElectionCapabilityResolver
{
    private ?CapabilityTrace $lastTrace = null;

    public function __construct(
        private readonly array $policies,
    ) {}

    public function evaluate(CapabilityContext $context): CapabilityDecision
    {
        $trace = new CapabilityTrace();

        // Sort policies by layer priority for deterministic evaluation order
        $sortedPolicies = $this->getSortedPolicies();

        foreach ($sortedPolicies as $policy) {
            $decision = $policy->evaluate($context);

            // null return and CapabilityDecision::abstain() are equivalent — continue to next policy
            if ($decision === null || $decision->isAbstain()) {
                $entry = CapabilityTraceEntry::abstained($this->getPolicyName($policy));
                $trace = $trace->add($entry);
                continue;
            }

            if ($decision->denies()) {
                if ($decision->isShortCircuit()) {
                    $entry = CapabilityTraceEntry::shortCircuit(
                        $this->getPolicyName($policy),
                        $decision->reason,
                        $decision->detail,
                    );
                } else {
                    $entry = CapabilityTraceEntry::denied(
                        $this->getPolicyName($policy),
                        $decision->reason,
                        $decision->detail,
                    );
                }
                $trace = $trace->add($entry);

                // Denial is final — stop immediately; a grant never overrides a deny
                $this->lastTrace = $trace;
                return $decision;
            }

            // Policy grants — record it and continue; a later policy may still deny
            $entry = CapabilityTraceEntry::granted($this->getPolicyName($policy));
            $trace = $trace->add($entry);
        }

        // All policies evaluated — none denied
        $this->lastTrace = $trace;
        return CapabilityDecision::authorized();
    }

    public function lastTrace(): ?CapabilityTrace
    {
        return $this->lastTrace;
    }

    private function getSortedPolicies(): array
    {
        $sorted = $this->policies;
        usort($sorted, fn(CapabilityPolicy $a, CapabilityPolicy $b) =>
            $a->layer()->priority() <=> $b->layer()->priority()
        );
        return $sorted;
    }

    private function getPolicyName(CapabilityPolicy $policy): string
    {
        $class = $policy::class;
        return substr($class, strrpos($class, '\\') + 1);
    }
}

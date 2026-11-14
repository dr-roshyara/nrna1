<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Application;

use App\Contexts\Platform\Application\Ports\OrganisationWorkDispatch;
use App\Contexts\Platform\Application\Ports\RegisteredOrganisations;

/**
 * A scheduled operation fanned out to the organisations (PBDIGIT-345 task 8; tactical design E-3, Q-8; T-5).
 * For every registered organisation, in registry order:
 * - not routable by the registry's own rule (ResolutionService: operational, schema current) → SKIPPED with its reason;
 * - otherwise exactly ONE dispatch; a dispatch that fails is RECORDED and the run continues with the next organisation.
 * No organisation database is read here. Delivery is at least once across runs: a repeated run dispatches again, so
 * the fanned-out jobs must be idempotent (design §3.4, Q-8).
 */
final class FanOutToOrganisations
{
    public function __construct(
        private readonly RegisteredOrganisations $organisations,
        private readonly ResolutionService $resolution,
    ) {
    }

    public function run(OrganisationWorkDispatch $work): FanOutReport
    {
        $dispatched = $skipped = $failed = [];
        foreach ($this->organisations->ids() as $id) {
            try {
                $this->resolution->resolve($id->value());
            } catch (OrganisationNotRoutable $e) {
                $skipped[$id->value()] = $e->reason->value;
                continue;
            }
            try {
                $work->dispatchFor($id);
                $dispatched[] = $id->value();
            } catch (\Throwable $e) {
                $failed[$id->value()] = $e::class;
            }
        }

        return new FanOutReport($dispatched, $skipped, $failed);
    }
}

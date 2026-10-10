<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Organisational;

/**
 * Capability of a Fact that reverses an earlier fact of the same Member by changing
 * its liveness: Annul and Void (correction contract §3.1 / §3.2; D-COR-5 (A)).
 * A reversal creates no consequences of its own.
 */
interface ReversalFact
{
    public function target(): FactId;
}

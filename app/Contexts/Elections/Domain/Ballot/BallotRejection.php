<?php

namespace App\Contexts\Elections\Domain\Ballot;

/**
 * BallotRejection — the outcome of validating an invalid submitted ballot (VE-01). The cast fails closed:
 * no CAS, no vote, no result rows (ADR_20261005_0950 rev 3 §3.4).
 */
final class BallotRejection
{
    public function __construct(public readonly BallotRejectionReason $reason) {}
}

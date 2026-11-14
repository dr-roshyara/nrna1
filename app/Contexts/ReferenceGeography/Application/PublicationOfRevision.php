<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Application;

use App\Contexts\ReferenceGeography\Domain\Revision;

/**
 * G-6c-3: a published revision as the ledger read it under the lock: its position, the revision (closed since
 * publication, G-4f: its state, approvals and excluded persons are those it was published with) and the roster version
 * its publication was judged at. Replaying PublicationGovernance::mayPublish on these gives the counted approver
 * (design §17).
 */
final readonly class PublicationOfRevision
{
    public function __construct(
        public int $position,
        public Revision $revision,
        public int $rosterVersion,
    ) {
    }
}

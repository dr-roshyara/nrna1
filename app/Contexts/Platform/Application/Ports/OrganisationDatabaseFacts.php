<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Application\Ports;

/**
 * L-1 (tactical design §3.7), as read from the organisation's OWN database at the `operational` transition:
 * - ledgerSchemaPresent: every migration of the organisation set is recorded there, and the ledger tables exist;
 * - identityRowMatches: its one `organisation_identity` row is the registry id;
 * - ledgerClockRowPresent: Membership's ledger clock row for that id exists.
 */
final readonly class OrganisationDatabaseFacts
{
    public function __construct(
        public bool $ledgerSchemaPresent,
        public bool $identityRowMatches,
        public bool $ledgerClockRowPresent,
    ) {
    }
}

<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Domain;

use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * The complete state of one registry entry, for storage (PBDIGIT-345 task 2). Produced only by
 * ServedOrganisation::snapshot(); turned back into an aggregate only by ServedOrganisation::fromSnapshot(), which
 * refuses any state the domain could never have produced. Holds a credential REFERENCE, never a password.
 */
final readonly class ServedOrganisationSnapshot
{
    public function __construct(
        public TenantId $id,
        public PlatformStatus $status,
        public ?SchemaState $schemaState,
        public ?DatabaseLocator $locator,
        public ?ServiceOperationGrant $grant,
        public ?CounterpartyVerification $counterparty,
        // task 5a: nullable only so that a stored row without it is REFUSED by ServedOrganisation::fromSnapshot
        public ?FoundingApplication $application = null,
    ) {
    }
}

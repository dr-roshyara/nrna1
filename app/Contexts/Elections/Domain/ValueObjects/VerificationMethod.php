<?php

namespace App\Contexts\Elections\Domain\ValueObjects;

/**
 * VerificationMethod — HOW officer identity evidence was produced (PBDIGIT-213 C4-D2).
 *
 * Both methods satisfy the SAME requirement (OFFICER_IDENTITY_VERIFICATION) with the
 * same admission effect; the method only keeps an individual check distinguishable
 * from a bulk attestation. It is never an input of the entitlement decision.
 */
enum VerificationMethod: string
{
    /** The Chief or Deputy Chief verified this voter individually ("Verify Identity"). */
    case OfficerAttestation = 'officer_attestation';

    /** The Chief or Deputy Chief attested a batch of voters at once ("Verify All"). */
    case OfficerBulkAttestation = 'officer_bulk_attestation';
}

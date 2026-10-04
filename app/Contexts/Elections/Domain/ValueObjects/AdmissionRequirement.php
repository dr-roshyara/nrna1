<?php

namespace App\Contexts\Elections\Domain\ValueObjects;

/**
 * AdmissionRequirement — WHAT a registered voter must satisfy before admission
 * (PBDIGIT-213, ADR_20261004_1549 §2.2 inv. 5).
 *
 * Mechanism-specific units: a later "identity by officer OR eID" is an any-of
 * clause inside VoterAdmissionPolicy, not a new concept here (G7). v1 has one case.
 */
enum AdmissionRequirement: string
{
    /** The Chief or Deputy Chief of the election has verified the voter's identity. */
    case OfficerIdentityVerification = 'OFFICER_IDENTITY_VERIFICATION';
}

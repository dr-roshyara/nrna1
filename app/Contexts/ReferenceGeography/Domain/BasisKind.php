<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/**
 * What a claim's basis is, as far as the G-1 kernel needs: I-4 distinguishes an establishing instrument and a
 * recorded duplicate-search assessment. The competence of a source for a claim kind (§3 "Basis" column, §5) is
 * judged by the Revision validator (G-2), not here.
 */
enum BasisKind: string
{
    case Instrument = 'instrument';
    case DuplicateSearchAssessment = 'duplicate-search-assessment';
    /** A correction of an erroneous earlier assertion (v3.11 §3 withdrawal row "instrument or correction"; D-228). */
    case Correction = 'correction';
    case Other = 'other';
}

<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/** What the assessing steward judges (v3.11 §5.3, §8; D-84): authenticity, competence, applicability, precedence. */
enum AssessmentKind: string
{
    case Authenticity = 'authenticity';
    case Competence = 'competence';
    case Applicability = 'applicability';
    case Precedence = 'precedence';
    /**
     * That the place is not already represented in the atlas (v3.11 I-4 as amended by D-229): supplementary identity
     * evidence, recorded where applicable; never a claim's basis. Not in required(): v3.11 does not define when it
     * applies, so it is recorded, not demanded.
     */
    case DuplicateSearch = 'duplicate-search';

    /** @return list<self> the assessments every submission needs; precedence only where a rule is applied */
    public static function required(): array
    {
        return [self::Authenticity, self::Competence, self::Applicability];
    }
}

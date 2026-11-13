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

    /** @return list<self> the assessments every submission needs; precedence only where a rule is applied */
    public static function required(): array
    {
        return [self::Authenticity, self::Competence, self::Applicability];
    }
}

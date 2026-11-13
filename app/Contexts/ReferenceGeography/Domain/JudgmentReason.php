<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/**
 * Why a matter involves a judgment between competing positions (D-84a's list; Principal Q-G3-1, D-230), when flagged
 * by a person involved. A flag is permanent: no operation removes it.
 */
enum JudgmentReason: string
{
    case Objection = 'objection';
    case MissingUnitSubmission = 'missing-unit-submission';
    case Dispute = 'dispute';
    case PrecedenceQuestion = 'precedence-question';
    case CompetingInstruments = 'competing-instruments';
    /** Reasonable doubt whether the matter is routine: it is a judgment matter (fail closed). */
    case UncertainClassification = 'uncertain-classification';
}

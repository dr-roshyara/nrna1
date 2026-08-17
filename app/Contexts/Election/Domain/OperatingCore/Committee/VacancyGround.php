<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain\OperatingCore\Committee;

/**
 * The CLOSED set of vacancy grounds (EM-GOV-064): a seat becomes vacant only upon a
 * recorded vacancy event with one of these three grounds. Temporary unavailability
 * is NOT a vacancy and has no representation here — deliberately.
 */
enum VacancyGround: string
{
    case ResignationWithReason = 'resignation_with_reason';
    case DeathOrPermanentIncapacity = 'death_or_permanent_incapacity';
    case LossOfEligibilityOrIndependence = 'loss_of_eligibility_or_independence';
}

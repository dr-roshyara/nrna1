<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain\OperatingCore\Exception;

use DomainException;

/**
 * P-6: expiry is a QUESTION, not an event the clock produces — the consequence fires
 * only when the Election Rule's facts hold: halt present ∧ governed period expired ∧
 * recovery not succeeded, and never while Inoperative (EM-GOV-063, 058, 062;
 * EM-OPEN-047 resolution: the clock decides nothing).
 */
final class ExpiryConsequencePreconditionNotMet extends DomainException
{
}

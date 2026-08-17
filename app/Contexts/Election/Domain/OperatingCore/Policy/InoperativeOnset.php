<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain\OperatingCore\Policy;

use App\Contexts\Election\Domain\OperatingCore\Committee\ElectionCommittee;
use App\Contexts\Election\Domain\OperatingCore\Gate\RequiredVotes;
use App\Contexts\Election\Domain\OperatingCore\Time\RecordedInstant;

/**
 * P-4 `InoperativeOnset` (stateless): Election Inoperative begins AT the causing
 * recorded vacancy event — no declaration, no determiner, no classifier
 * (EM-GOV-065; EM-ARCH-001 §2e). The onset is the EVENT's instant, never the
 * evaluation's; clock pause/start is computed from this recorded onset.
 */
final class InoperativeOnset
{
    private function __construct()
    {
    }

    /**
     * @return RecordedInstant|null the onset — the causing event's own instant —
     *                              or null while the Committee remains able to function
     */
    public static function onsetFor(
        ElectionCommittee $committee,
        RequiredVotes $required,
        RecordedInstant $vacancyEventInstant,
    ): ?RecordedInstant {
        return $committee->unableToFunction($required) ? $vacancyEventInstant : null;
    }
}

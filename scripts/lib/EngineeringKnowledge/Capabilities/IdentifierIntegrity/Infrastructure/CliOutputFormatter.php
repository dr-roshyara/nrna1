<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\IdentifierIntegrity\Infrastructure;

use EngineeringKnowledge\Shared\Domain\Assessment;
use EngineeringKnowledge\Shared\Domain\Verdict;

/**
 * Renders an Assessment for a terminal.
 *
 * ⛔ Presentation only. It adds no judgement, softens none, and invents no verdict —
 *    AP-8's vocabulary crosses unchanged.
 */
final readonly class CliOutputFormatter
{
    public function format(Assessment $assessment): string
    {
        $verdict = $assessment->verdict();
        $subject = $assessment->subject() ?? '(unspecified)';

        return sprintf(
            "%s  %s\n  %s\n",
            $this->badge($verdict),
            $subject,
            $assessment->evidence(),
        );
    }

    /** Exit code: 0 only for PASS. Anything an author must look at is non-zero. */
    public function exitCode(Assessment $assessment): int
    {
        return match ($assessment->verdict()) {
            Verdict::PASS => 0,
            Verdict::WARN, Verdict::INCONCLUSIVE => 1,
            default => 2,
        };
    }

    private function badge(Verdict $verdict): string
    {
        return match ($verdict) {
            Verdict::PASS => '[PASS]        ',
            Verdict::FAIL => '[FAIL]        ',
            Verdict::WARN => '[WARN]        ',
            Verdict::INCONCLUSIVE => '[INCONCLUSIVE]',
            default => '['.$verdict->value.']',
        };
    }
}

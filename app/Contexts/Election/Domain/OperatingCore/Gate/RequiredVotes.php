<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain\OperatingCore\Gate;

use InvalidArgumentException;

/**
 * Required Committee Votes: ⌈2·constituted/3⌉ — round UP, never down (round-down
 * would change what two-thirds means — EM-GOV-036, 038(a)), applied against the
 * CONSTITUTED denominator (EM-GOV-057) of a Committee of at least three
 * (EM-GOV-033). Verified sizes: 3→2 · 4→3 · 5→4 · 6→4 · 7→5. @immutable
 */
final readonly class RequiredVotes
{
    private function __construct(public int $count)
    {
    }

    public static function forConstitutedSize(int $constitutedSize): self
    {
        if ($constitutedSize < 3) {
            throw new InvalidArgumentException(
                'The rule applies to a Committee of at least three members (EM-GOV-033, 036).'
            );
        }

        // ⌈2n/3⌉ in integer arithmetic.
        return new self(intdiv(2 * $constitutedSize + 2, 3));
    }

    public function count(): int
    {
        return $this->count;
    }
}

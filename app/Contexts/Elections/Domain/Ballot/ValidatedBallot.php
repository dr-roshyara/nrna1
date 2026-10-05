<?php

namespace App\Contexts\Elections\Domain\Ballot;

/**
 * ValidatedBallot — a normalised SubmittedBallot proven valid against the AuthoritativeBallot and all
 * ballot-integrity rules (ADR_20261005_0950 rev 3 §3.0). The ONLY input real-election vote persistence
 * accepts (I12). Created by BallotValidator; persistence consumes it and never re-judges validity.
 */
final class ValidatedBallot
{
    /** @param list<ValidatedAnswer> $answers answered posts only, in submission order */
    public function __construct(public readonly array $answers) {}

    /** @return list<string> post ids the voter explicitly abstained on */
    public function abstainedPostIds(): array
    {
        return array_values(array_map(
            fn (ValidatedAnswer $a) => $a->post->postId,
            array_filter($this->answers, fn (ValidatedAnswer $a) => $a->abstained)
        ));
    }
}

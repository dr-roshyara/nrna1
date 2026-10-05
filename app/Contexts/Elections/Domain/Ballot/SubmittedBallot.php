<?php

namespace App\Contexts\Elections\Domain\Ballot;

/**
 * SubmittedBallot — the voter's proposed ballot, normalised from transport data (session / request).
 *
 * SESSION DATA IS UNTRUSTED INPUT (ADR_20261005_0950 I11). Only post ids, abstention flags and candidacy ids
 * are taken over; every display value the client sent (post names, required numbers, candidate names) is
 * ignored. A structurally malformed ballot is kept as malformed and is rejected by the BallotValidator (D3).
 */
final class SubmittedBallot
{
    /** @param list<SubmittedAnswer> $answers */
    private function __construct(
        public readonly array $answers,
        public readonly bool $malformed,
    ) {}

    /**
     * @param mixed $national the transport's national selections
     * @param mixed $regional the transport's regional selections
     */
    public static function fromTransport(mixed $national, mixed $regional): self
    {
        $answers = [];

        foreach ([$national ?? [], $regional ?? []] as $list) {
            if (! is_array($list)) {
                return new self([], true);
            }

            foreach ($list as $entry) {
                $answer = self::answerFrom($entry);
                if ($answer === null) {
                    return new self([], true);
                }
                $answers[] = $answer;
            }
        }

        return new self($answers, false);
    }

    /** One transport entry → SubmittedAnswer, or null when it is malformed. */
    private static function answerFrom(mixed $entry): ?SubmittedAnswer
    {
        if (! is_array($entry)) {
            return null;
        }

        $postId = $entry['post_id'] ?? null;
        if (! is_string($postId) || $postId === '') {
            return null;
        }

        $abstain = $entry['no_vote'] ?? false;
        if (! is_bool($abstain) && ! in_array($abstain, [0, 1, '0', '1'], true)) {
            return null;
        }

        $candidates = $entry['candidates'] ?? [];
        if (! is_array($candidates)) {
            return null;
        }

        $candidacyIds = [];
        foreach ($candidates as $candidate) {
            $candidacyId = is_array($candidate) ? ($candidate['candidacy_id'] ?? null) : null;
            if (! is_string($candidacyId) || $candidacyId === '') {
                return null;
            }
            $candidacyIds[] = $candidacyId;
        }

        return new SubmittedAnswer($postId, (bool) $abstain, $candidacyIds);
    }
}

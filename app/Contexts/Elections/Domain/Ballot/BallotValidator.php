<?php

namespace App\Contexts\Elections\Domain\Ballot;

/**
 * BallotValidator — pure comparison SubmittedBallot × AuthoritativeBallot × ballot-integrity rules
 * → ValidatedBallot | BallotRejection (VE-01, ADR_20261005_0950 rev 3 §3.0, I1, I11, I12).
 *
 * Domain-pure: no database, session, request, User or ElectionMembership access; no state change.
 * Rules (PO decisions 2026-10-05): D1 only selections from the authoritative ballot, each candidacy at most
 * once · D2 required_number is the maximum · D3 malformed fails closed · D5 only electable (approved)
 * candidacies — enforced by the authoritative ballot · N1 abstention with candidates is invalid · N2 an empty
 * list without abstention is Not answered · N3 an omitted post is Not answered · N4 abstention only when the
 * election allows it · N5 abstention only for a post on the voter's ballot. First failure wins.
 */
final class BallotValidator
{
    public function validate(SubmittedBallot $submitted, AuthoritativeBallot $authoritative): ValidatedBallot|BallotRejection
    {
        if ($submitted->malformed) {
            return new BallotRejection(BallotRejectionReason::Malformed);
        }

        $answers = [];
        $seenPosts = [];
        $seenCandidacies = [];

        foreach ($submitted->answers as $answer) {
            $post = $authoritative->post($answer->postId);
            if ($post === null) {
                return new BallotRejection(BallotRejectionReason::PostNotOnBallot);
            }
            if (isset($seenPosts[$post->postId])) {
                return new BallotRejection(BallotRejectionReason::DuplicatePost);
            }
            $seenPosts[$post->postId] = true;

            if ($answer->abstain) {
                if ($answer->candidacyIds !== []) {
                    return new BallotRejection(BallotRejectionReason::AbstentionWithCandidates);
                }
                if (! $authoritative->abstentionAllowed) {
                    return new BallotRejection(BallotRejectionReason::AbstentionNotAllowed);
                }
                $answers[] = new ValidatedAnswer($post, true, []);
                continue;
            }

            if ($answer->candidacyIds === []) {
                continue; // Not answered (N2)
            }

            foreach ($answer->candidacyIds as $candidacyId) {
                if (! $post->hasCandidacy($candidacyId)) {
                    return new BallotRejection(BallotRejectionReason::CandidacyNotOnPost);
                }
                if (isset($seenCandidacies[$candidacyId])) {
                    return new BallotRejection(BallotRejectionReason::DuplicateCandidacy);
                }
                $seenCandidacies[$candidacyId] = true;
            }

            if (count($answer->candidacyIds) > $post->maxSelections) {
                return new BallotRejection(BallotRejectionReason::OverMaximum);
            }

            $answers[] = new ValidatedAnswer($post, false, $answer->candidacyIds);
        }

        return new ValidatedBallot($answers);
    }
}

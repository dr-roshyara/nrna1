<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Organisational\Ports;

use App\Contexts\Membership\Domain\Organisational\PeriodId;
use App\Contexts\Membership\Domain\Organisational\PersonRef;
use App\Contexts\Membership\Domain\Organisational\TimePoint;

/**
 * What Membership asks Governance's AuthorityCheck (PBDIGIT-354 S2-3a; accepted D-208 §1–§2, C-1a; D-238): may this
 * actor perform this act, on this subject, at this date?
 *
 * - `actor`: the acting `PersonRef` supplied by the caller from Identity (R-1, C-2); Governance compares refs only.
 * - `subject` (`requestRef`): the member concerned. Null only when the member is unknown, so the answer cannot reveal
 *   whether a member exists (Membership then refuses).
 * - `period`: the period acted on, where the command names one (End).
 * - `at`: the decision date the authority is evaluated for (D-12).
 * - `declaredEffectiveAt`: a resignation's declared effective date (D-201); null for every other act.
 * - `requestId`: identifies the request; the command's idempotency key.
 * Built by the use cases; never from a caller-supplied authority.
 */
final readonly class AuthorityRequest
{
    public function __construct(
        public MembershipAct $act,
        public string $actor,
        public ?PersonRef $subject,
        public ?PeriodId $period,
        public TimePoint $at,
        public ?TimePoint $declaredEffectiveAt,
        public string $requestId,
    ) {
        if (trim($actor) === '' || trim($requestId) === '') {
            throw new \InvalidArgumentException('An authority request names its actor and its request id.');
        }
        if ($declaredEffectiveAt !== null && $act !== MembershipAct::RecordResignation) {
            throw new \InvalidArgumentException('Only a resignation declares an effective date.');
        }
    }
}

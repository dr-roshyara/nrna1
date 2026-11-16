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
 * - `evidenceRef`: for `record-death` only, and required there (D-208 §2: "evidence reference"; GC-4: refused without
 *   evidence). Added in S2-3b: S2-3a had omitted it (a gap against D-208, closed additively).
 * - `declarationRef`: `record-resignation` in its **withdraw shape** only (D-208 §2, D-204, GC-12): the reference of the
 *   scheduled resignation declaration being withdrawn. Never together with `declaredEffectiveAt` (that is the resign
 *   shape). Added in S2-4b, additively.
 * - `factRef`, `correctedValue` (S2-5a, additive; D-208 §2): `correct-membership-record` = (fact ref, corrected value,
 *   evidence ref); `void-membership-record` = (fact ref, evidence ref). Both require `evidenceRef` (D-206, GC-14).
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
        public ?string $evidenceRef = null,
        public ?string $declarationRef = null,
        public ?string $factRef = null,
        public ?TimePoint $correctedValue = null,
    ) {
        $correcting = $act === MembershipAct::CorrectMembershipRecord || $act === MembershipAct::VoidMembershipRecord;
        if ($correcting !== ($factRef !== null && trim($factRef) !== '')) {
            throw new \InvalidArgumentException('A Correct or a Void names the fact it acts on; no other act does (D-208 §2).');
        }
        if (($act === MembershipAct::CorrectMembershipRecord) !== ($correctedValue !== null)) {
            throw new \InvalidArgumentException('Only a Correct carries the corrected value, and it always does (D-208 §2).');
        }
        if ($correcting && ($evidenceRef === null || trim($evidenceRef) === '')) {
            throw new \InvalidArgumentException('A Correct or a Void cites its evidence (D-206, GC-14).');
        }
        if ($declarationRef !== null && ($act !== MembershipAct::RecordResignation || $declaredEffectiveAt !== null || trim($declarationRef) === '')) {
            throw new \InvalidArgumentException('Only withdrawing a scheduled resignation names a declaration, and without an effective date.');
        }
        if (trim($actor) === '' || trim($requestId) === '') {
            throw new \InvalidArgumentException('An authority request names its actor and its request id.');
        }
        if ($declaredEffectiveAt !== null && $act !== MembershipAct::RecordResignation) {
            throw new \InvalidArgumentException('Only a resignation declares an effective date.');
        }
        if ($act === MembershipAct::RecordDeath && ($evidenceRef === null || trim($evidenceRef) === '')) {
            throw new \InvalidArgumentException('Recording a death cites its evidence (GC-4).');
        }
        if ($act !== MembershipAct::RecordDeath && !$correcting && $evidenceRef !== null) {
            throw new \InvalidArgumentException('Only recording a death, a Correct or a Void carries an evidence reference.');
        }
    }
}

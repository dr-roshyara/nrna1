<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Organisational;

/**
 * Command: correct a membership record (PBDIGIT-354 S2-5a; D-206; D-208 §2 `correct-membership-record`). A real fact
 * recorded with a wrong date: the Correct is a NEW fact naming its target; the target is never rewritten (C-2).
 * A recorder with recording authority and evidence; no `ownAct`.
 */
final readonly class CorrectMembershipRecord
{
    public function __construct(
        public string $tenantId,
        public string $memberId,
        /** The id of the new Correct fact. */
        public string $factId,
        /** The fact being corrected: an Admit, an End, or an earlier Correct of one (C-2, D-COR-3). */
        public string $targetFactId,
        /** The corrected date; null with $openEnded (S2-5b, D-237: a Renew's `until` to open-ended). Exactly one is stated. */
        public ?int $correctedDate,
        /** The decision date (D-5(a) Z1, caller-supplied); the authority is evaluated for it. */
        public int $decisionDate,
        /** The evidence the correction rests on (D-206, GC-14: required). A reference only. */
        public ?string $evidenceRef,
        public string $actor,
        public ?int $decidedAt,
        public string $idempotencyKey,
        /** S2-5b (D-237): the corrected value is open-ended (∞); only a Renew's `until` may be. */
        public bool $openEnded = false,
    ) {
    }

    /** Everything except the key. */
    public function fingerprint(): string
    {
        $fields = get_object_vars($this);
        unset($fields['idempotencyKey']);

        return 'correct:' . hash('sha256', json_encode($fields, JSON_THROW_ON_ERROR));
    }
}

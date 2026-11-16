<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Organisational;

/**
 * Command: void a membership record (PBDIGIT-354 S2-5a; D-206; D-208 §2 `void-membership-record`). The record never
 * reflected reality: the Void is a NEW fact naming its target; the target stays in the log, inert (C-1). A recorder
 * with recording authority and evidence; reported to the organisation and challengeable (D-206; Q-8 open on the form
 * of the report).
 */
final readonly class VoidMembershipRecord
{
    public function __construct(
        public string $tenantId,
        public string $memberId,
        /** The id of the new Void fact. */
        public string $factId,
        /** The fact that never reflected reality. */
        public string $targetFactId,
        /** The decision date (D-5(a) Z1, caller-supplied); the authority is evaluated for it. */
        public int $decisionDate,
        /** The evidence the void rests on (D-206, GC-14: required). A reference only. */
        public ?string $evidenceRef,
        public string $actor,
        public ?int $decidedAt,
        public string $idempotencyKey,
    ) {
    }

    /** Everything except the key. */
    public function fingerprint(): string
    {
        $fields = get_object_vars($this);
        unset($fields['idempotencyKey']);

        return 'void:' . hash('sha256', json_encode($fields, JSON_THROW_ON_ERROR));
    }
}

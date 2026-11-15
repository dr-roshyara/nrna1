<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Organisational;

/**
 * Command: schedule a resignation from a LATER date (PBDIGIT-354 S2-4b; D-201, D-204; D-208 `record-resignation`).
 * The actor is the member (self-service, `ownAct`) or a recorder. The period is the member's current one at the
 * declaration date; the caller does not name it. The same day is "resign now" (ResignMembership, S2-3b).
 */
final readonly class ScheduleResignation
{
    public function __construct(
        public string $tenantId,
        public string $memberId,
        public string $declarationId,
        /** The declaration date: the decision date (D-5(a) Z1, caller-supplied). */
        public int $declarationDate,
        /** The declared effective date, after the declaration date (D-201). */
        public int $effectiveDate,
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

        return 'schedule-resignation:' . hash('sha256', json_encode($fields, JSON_THROW_ON_ERROR));
    }
}

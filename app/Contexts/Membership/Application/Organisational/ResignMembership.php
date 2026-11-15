<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Organisational;

/**
 * Command: resign now (PBDIGIT-354 S2-3b; D-194, D-201; D-208 `record-resignation`). The member's declaration takes
 * effect on its date (D-201: without a stated date, at once). The actor is the member (self-service, `ownAct`) or a
 * recorder. The period is the member's current one at the declaration date; the caller does not name it.
 * A resignation from a LATER date is S2-4 (scheduled resignation), not this command.
 */
final readonly class ResignMembership
{
    public function __construct(
        public string $tenantId,
        public string $memberId,
        public string $factId,
        /** The declaration date: the decision date and the effective date (D-5(a) Z1, caller-supplied). */
        public int $declarationDate,
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

        return 'resign:' . hash('sha256', json_encode($fields, JSON_THROW_ON_ERROR));
    }
}

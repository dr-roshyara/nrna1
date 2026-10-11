<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Organisational;

/** Command: end a membership period. */
final readonly class EndMembership
{
    public function __construct(
        public string $tenantId,
        public string $memberId,
        public string $factId,
        public string $periodId,
        public int $effectiveAt,
        /** D-5(a) Z1: the decision's calendar date, supplied by the caller (a UI default uses the organisation's time zone). */
        public int $decisionDate,
        public string $endKind,
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

        return 'end:' . hash('sha256', json_encode($fields, JSON_THROW_ON_ERROR));
    }
}

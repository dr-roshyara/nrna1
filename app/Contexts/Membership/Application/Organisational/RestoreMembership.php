<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Organisational;

/** Command: end one suspension at effectiveAt (PBDIGIT-354 S2-2; D-177 §4). It names the Suspend fact it ends. */
final readonly class RestoreMembership
{
    public function __construct(
        public string $tenantId,
        public string $memberId,
        public string $factId,
        public string $suspensionFactId,
        public int $effectiveAt,
        /** D-5(a) Z1: the decision's calendar date, supplied by the caller. D-205 (3): effectiveAt may not be after it. */
        public int $decisionDate,
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

        return 'restore:' . hash('sha256', json_encode($fields, JSON_THROW_ON_ERROR));
    }
}

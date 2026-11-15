<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Organisational;

/**
 * Command: renew a membership term (PBDIGIT-354 S2-1; D-163, D-177 §4). The period is not part of the command:
 * it is the one containing effectiveAt, decided by the Member aggregate.
 */
final readonly class RenewMembership
{
    public function __construct(
        public string $tenantId,
        public string $memberId,
        public string $factId,
        public int $effectiveAt,
        /** D-5(a) Z1: the decision's calendar date, supplied by the caller. D-205 (3): effectiveAt may not be after it. */
        public int $decisionDate,
        /** The new term's end (exclusive). Exactly one of `until` and `unlimited` states the term. */
        public ?int $until,
        public bool $unlimited,
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

        return 'renew:' . hash('sha256', json_encode($fields, JSON_THROW_ON_ERROR));
    }
}

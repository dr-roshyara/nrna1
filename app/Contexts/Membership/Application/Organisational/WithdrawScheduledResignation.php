<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Organisational;

/**
 * Command: withdraw a scheduled resignation before its date (PBDIGIT-354 S2-4b; D-201, D-204; D-208 §2
 * `record-resignation`, withdraw shape; GC-12). Names the declaration; by the member or a recorder.
 */
final readonly class WithdrawScheduledResignation
{
    public function __construct(
        public string $tenantId,
        public string $memberId,
        public string $declarationId,
        /** The withdrawal date (D-5(a) Z1, caller-supplied); before the declaration's effective date. */
        public int $withdrawalDate,
        public string $actor,
        public string $idempotencyKey,
    ) {
    }

    /** Everything except the key. */
    public function fingerprint(): string
    {
        $fields = get_object_vars($this);
        unset($fields['idempotencyKey']);

        return 'withdraw-resignation:' . hash('sha256', json_encode($fields, JSON_THROW_ON_ERROR));
    }
}

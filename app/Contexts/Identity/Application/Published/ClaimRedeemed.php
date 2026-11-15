<?php

declare(strict_types=1);

namespace App\Contexts\Identity\Application\Published;

/**
 * I-TDD-02 §12 C-I3 (published language): the person recorded as R in organisation O redeemed O's claim; R of O is
 * now bound. For O to mark its own record "claimed" (idempotent on `claimId`). It carries O's own R only: never the
 * PersonId, an Account or another organisation's reference (XD-3 §2.3). Access never depends on receiving it.
 */
final readonly class ClaimRedeemed
{
    public function __construct(
        public string $organisationId,
        public string $personRef,
        public string $claimId,
        public string $boundAt,
    ) {
    }
}

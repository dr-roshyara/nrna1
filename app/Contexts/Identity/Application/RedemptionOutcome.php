<?php

declare(strict_types=1);

namespace App\Contexts\Identity\Application;

/**
 * The result of a redemption (I-3): `bound` (a new binding), `already-bound` (the same Account re-presented the code
 * it redeemed, while the redeemed claim row exists: §14 case 3) or `refused`. `organisationId` is given only on
 * success: the person learns which organisation they are now linked to, nothing else.
 */
final readonly class RedemptionOutcome
{
    private function __construct(
        public string $status,
        public ?string $organisationId,
        public ?RedemptionRefusal $refusal,
    ) {
    }

    public static function bound(string $organisationId): self
    {
        return new self('bound', $organisationId, null);
    }

    public static function alreadyBound(string $organisationId): self
    {
        return new self('already-bound', $organisationId, null);
    }

    public static function refused(RedemptionRefusal $reason): self
    {
        return new self('refused', null, $reason);
    }
}

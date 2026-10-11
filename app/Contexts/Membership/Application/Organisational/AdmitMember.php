<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Organisational;

/**
 * Command: admit a person to an organisation (opens a membership period).
 * Ids are supplied by the caller; memberIdIfNew is used only if the person has no Member yet.
 */
final readonly class AdmitMember
{
    public function __construct(
        public string $tenantId,
        public string $personRef,
        public string $memberIdIfNew,
        public string $factId,
        public int $effectiveAt,
        /** D-5(a) Z1: the decision's calendar date, supplied by the caller (a UI default uses the organisation's time zone). */
        public int $decisionDate,
        public ?int $initialTermUntil,
        public bool $initialTermUnlimited,
        public string $actor,
        public ?string $declarationRef,
        public ?int $decidedAt,
        public string $idempotencyKey,
    ) {
    }

    /** Everything except the key: two commands with equal fingerprints are the same intended command. */
    public function fingerprint(): string
    {
        $fields = get_object_vars($this);
        unset($fields['idempotencyKey']);

        return 'admit:' . hash('sha256', json_encode($fields, JSON_THROW_ON_ERROR));
    }
}

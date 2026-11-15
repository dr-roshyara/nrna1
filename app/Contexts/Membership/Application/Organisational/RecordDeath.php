<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Organisational;

/**
 * Command: record a member's death (PBDIGIT-354 S2-3b; D-208 §2 `record-death`, GC-4; M-TDD-01 §5.1 `End(death)`).
 * The period is the member's current one at the date of death; the caller does not name it.
 */
final readonly class RecordDeath
{
    public function __construct(
        public string $tenantId,
        public string $memberId,
        public string $factId,
        /** The effective date: the date of death. */
        public int $dateOfDeath,
        /** The decision date: the recording date, which the authority is evaluated for (D-208 §2). */
        public int $recordingDate,
        /** The evidence the record rests on (GC-4: required). A reference only; no document content. */
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

        return 'death:' . hash('sha256', json_encode($fields, JSON_THROW_ON_ERROR));
    }
}

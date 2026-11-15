<?php

declare(strict_types=1);

namespace App\Contexts\Identity\Application;

use App\Contexts\Identity\Domain\LocalEntryRef;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * I-2 (I-TDD-02 §6.2, §12 C-I2): issue an ordinary claim for the person recorded as local entry E. Built by the
 * organisation's own server-side application layer, in its organisation context, with Governance's answer by value.
 * `requestedWindowDays`: the period the organisation's rules set; null = the rules are silent (D-199 default).
 */
final readonly class IssueClaimCommand
{
    public function __construct(
        public TenantId $organisation,
        public LocalEntryRef $entry,
        public string $issuanceId,
        public ?int $requestedWindowDays,
        public AuthorityEvidence $evidence,
    ) {
        self::assertId($issuanceId);
    }

    public static function assertId(string $id): void
    {
        if (preg_match('/^\S{1,128}$/', $id) !== 1) {
            throw new \InvalidArgumentException('An issuance id is 1 to 128 non-space characters.');
        }
    }
}

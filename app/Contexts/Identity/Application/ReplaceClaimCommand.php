<?php

declare(strict_types=1);

namespace App\Contexts\Identity\Application;

use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/** I-2: withdraw one active ordinary claim and give a new one, as ONE act (A1: one `manage-claims` answer, D-196). */
final readonly class ReplaceClaimCommand
{
    public function __construct(
        public TenantId $organisation,
        public string $oldClaimId,
        public string $newIssuanceId,
        public ?int $requestedWindowDays,
        public AuthorityEvidence $evidence,
    ) {
        IssueClaimCommand::assertId($newIssuanceId);
    }
}

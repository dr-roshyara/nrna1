<?php

declare(strict_types=1);

namespace App\Contexts\Identity\Application;

use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/** I-2: withdraw one active ordinary claim (an organisational act: one `manage-claims` answer, D-196). */
final readonly class RevokeClaimCommand
{
    public function __construct(
        public TenantId $organisation,
        public string $claimId,
        public AuthorityEvidence $evidence,
    ) {
    }
}

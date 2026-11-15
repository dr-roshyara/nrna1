<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Organisational\Ports;

use App\Contexts\Membership\Domain\Organisational\DeclarationId;
use App\Contexts\Membership\Domain\Organisational\MemberId;

/** What an idempotency key was used for in the declaration store: the command's fingerprint and the declaration it wrote. */
final readonly class AppliedDeclarationRequest
{
    public function __construct(public string $fingerprint, public MemberId $memberId, public DeclarationId $declarationId)
    {
    }
}

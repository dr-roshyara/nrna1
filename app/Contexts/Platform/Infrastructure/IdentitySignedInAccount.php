<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Infrastructure;

use App\Contexts\Identity\Application\Ports\CurrentAccount;
use App\Contexts\Platform\Application\Ports\SignedInAccount;

/**
 * PBDIGIT-345 task 6: Platform's SignedInAccount port, answered by Identity's CurrentAccount (D-226 §5a). One of the
 * two Platform files that touch Identity (PlatformIdentityBoundaryTest; the import is recorded in the ratchet baseline).
 */
final class IdentitySignedInAccount implements SignedInAccount
{
    public function __construct(private readonly CurrentAccount $current)
    {
    }

    public function accountId(): ?string
    {
        return $this->current->accountId()?->value();
    }
}

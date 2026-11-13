<?php

declare(strict_types=1);

namespace App\Contexts\Identity\Application\Ports;

use App\Contexts\Identity\Domain\AccountId;

/** Who is signed in for this operation, if anyone. */
interface CurrentAccount
{
    public function accountId(): ?AccountId;
}

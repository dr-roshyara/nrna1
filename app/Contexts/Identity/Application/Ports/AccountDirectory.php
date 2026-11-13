<?php

declare(strict_types=1);

namespace App\Contexts\Identity\Application\Ports;

use App\Contexts\Identity\Application\IdentityUnavailable;
use App\Contexts\Identity\Domain\AccountId;
use App\Contexts\Identity\Domain\PersonId;

interface AccountDirectory
{
    /**
     * The canonical Person of an ACTIVE Account (the survivor, if its Person was merged), creating the Person lazily
     * on first use (D-226 Q-G-2). Null if the Account is unknown or not active.
     *
     * @throws IdentityUnavailable
     */
    public function activePersonOf(AccountId $account): ?PersonId;
}

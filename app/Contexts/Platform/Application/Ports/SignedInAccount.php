<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Application\Ports;

/** Task 6: the account signed in for this request (an Identity account id), or null. Platform's own port. */
interface SignedInAccount
{
    public function accountId(): ?string;
}

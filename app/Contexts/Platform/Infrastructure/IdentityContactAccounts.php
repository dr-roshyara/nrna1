<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Infrastructure;

use App\Contexts\Identity\Application\IdentityUnavailable;
use App\Contexts\Identity\Application\Ports\AccountDirectory;
use App\Contexts\Identity\Domain\AccountId;
use App\Contexts\Platform\Application\Ports\ContactAccounts;
use App\Contexts\Platform\Application\Ports\ContactAccountsUnavailable;

/**
 * PBDIGIT-345 task 5c: Platform's ContactAccounts port, answered by Identity's AccountDirectory (D-226). The ONLY
 * place Platform touches Identity (PlatformIdentityBoundaryTest; each import recorded in the ratchet baseline).
 * Fail closed: Identity unavailable, or an id it cannot read, is reported, never counted.
 */
final class IdentityContactAccounts implements ContactAccounts
{
    public function __construct(private readonly AccountDirectory $directory)
    {
    }

    public function activeCount(array $accountIds): int
    {
        $active = 0;
        foreach ($accountIds as $id) {
            try {
                $person = $this->directory->activePersonOf(AccountId::fromString($id));
            } catch (IdentityUnavailable | \InvalidArgumentException $e) {
                throw new ContactAccountsUnavailable('The platform contacts\' accounts could not be checked.', 0, $e);
            }
            if ($person !== null) {
                $active++;
            }
        }

        return $active;
    }
}

<?php

declare(strict_types=1);

namespace App\Contexts\Identity\Application;

use App\Contexts\Identity\Application\Ports\AccountDirectory;
use App\Contexts\Identity\Application\Ports\ContextPersonRefs;
use App\Contexts\Identity\Application\Ports\CurrentAccount;
use App\Contexts\Identity\Domain\PlatformContext;

/**
 * D-225 IG-2: the signed-in Account's Person, as that context's opaque reference.
 * Refused when nobody is signed in, the Account is not active, or Identity cannot answer. Never guessed.
 */
final class ActingContextRef
{
    public function __construct(
        private readonly CurrentAccount $current,
        private readonly AccountDirectory $accounts,
        private readonly ContextPersonRefs $refs,
    ) {
    }

    public function for(PlatformContext $context): ActingContextRefAnswer
    {
        $account = $this->current->accountId();
        if ($account === null) {
            return ActingContextRefAnswer::refused(ActingContextRefRefusal::NotSignedIn);
        }
        try {
            $person = $this->accounts->activePersonOf($account);
            if ($person === null) {
                return ActingContextRefAnswer::refused(ActingContextRefRefusal::AccountNotActive);
            }

            return ActingContextRefAnswer::granted($this->refs->refFor($person, $context));
        } catch (IdentityUnavailable) {
            return ActingContextRefAnswer::refused(ActingContextRefRefusal::IdentityUnavailable);
        }
    }
}

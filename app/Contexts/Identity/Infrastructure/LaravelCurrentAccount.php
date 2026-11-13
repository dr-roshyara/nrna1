<?php

declare(strict_types=1);

namespace App\Contexts\Identity\Infrastructure;

use App\Contexts\Identity\Application\Ports\CurrentAccount;
use App\Contexts\Identity\Domain\AccountId;
use Illuminate\Contracts\Auth\Guard;

/** The signed-in legacy users row (G-0: the users row is the Account, D-226 §5a). */
final class LaravelCurrentAccount implements CurrentAccount
{
    public function __construct(private readonly Guard $guard)
    {
    }

    public function accountId(): ?AccountId
    {
        $id = $this->guard->id();

        return $id === null ? null : AccountId::fromString((string) $id);
    }
}

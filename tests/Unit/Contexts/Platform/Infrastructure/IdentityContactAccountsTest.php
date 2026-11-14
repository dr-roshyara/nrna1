<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Platform\Infrastructure;

use App\Contexts\Identity\Application\IdentityUnavailable;
use App\Contexts\Identity\Application\Ports\AccountDirectory;
use App\Contexts\Identity\Domain\AccountId;
use App\Contexts\Identity\Domain\PersonId;
use App\Contexts\Platform\Application\Ports\ContactAccountsUnavailable;
use App\Contexts\Platform\Infrastructure\IdentityContactAccounts;
use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-345 task 5c (D-193 F-07): the platform contacts' accounts are checked through Identity's AccountDirectory,
 * behind Platform's own port. Counted: the contacts whose account is known AND active. Identity unavailable (or an
 * id Identity cannot read) fails closed: never counted as active, never ignored.
 */
final class IdentityContactAccountsTest extends TestCase
{
    private const ACTIVE = '1a2b3c4d-5e6f-4a7b-8c9d-0e1f2a3b4c5d';
    private const INACTIVE = '2b3c4d5e-6f7a-4b8c-9d0e-1f2a3b4c5d6e';
    private const UNKNOWN = '3c4d5e6f-7a8b-4c9d-8e1f-2a3b4c5d6e7f';

    private static function directory(bool $available = true): AccountDirectory
    {
        return new class ($available) implements AccountDirectory {
            public function __construct(private readonly bool $available)
            {
            }

            public function activePersonOf(AccountId $account): ?PersonId
            {
                if (!$this->available) {
                    throw new IdentityUnavailable('simulated');
                }

                return $account->value() === '1a2b3c4d-5e6f-4a7b-8c9d-0e1f2a3b4c5d' ? PersonId::fromString('9a8b7c6d-5e4f-4a3b-9c2d-1e0f9a8b7c6d') : null;
            }
        };
    }

    public function test_only_known_active_accounts_are_counted(): void
    {
        $contacts = new IdentityContactAccounts(self::directory());

        $this->assertSame(1, $contacts->activeCount([self::ACTIVE, self::INACTIVE, self::UNKNOWN]));
        $this->assertSame(0, $contacts->activeCount([self::INACTIVE, self::UNKNOWN]));
        $this->assertSame(0, $contacts->activeCount([]));
    }

    public function test_identity_unavailable_fails_closed(): void
    {
        $this->expectException(ContactAccountsUnavailable::class);
        (new IdentityContactAccounts(self::directory(false)))->activeCount([self::ACTIVE]);
    }

    public function test_an_id_identity_cannot_read_fails_closed(): void
    {
        $this->expectException(ContactAccountsUnavailable::class);
        (new IdentityContactAccounts(self::directory()))->activeCount(['not-an-account-id']);
    }
}

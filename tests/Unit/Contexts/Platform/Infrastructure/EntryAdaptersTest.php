<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Platform\Infrastructure;

use App\Contexts\Identity\Application\Ports\CurrentAccount;
use App\Contexts\Identity\Domain\AccountId;
use App\Contexts\Platform\Infrastructure\IdentitySignedInAccount;
use App\Contexts\Platform\Infrastructure\NoIdentityBindingsYet;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-345 task 6: the production adapters behind E-1's ports.
 * - IdentitySignedInAccount: the signed-in account, as Identity's CurrentAccount reports it;
 * - NoIdentityBindingsYet: Identity's binding store (I-TDD-02, D-200) is not built (PBDIGIT-349), so NO account is
 *   bound to any organisation: every registry entry over HTTP is refused (fail closed) until it exists.
 */
final class EntryAdaptersTest extends TestCase
{
    public function test_the_signed_in_account_is_identitys_current_account(): void
    {
        $current = static fn (?string $id): CurrentAccount => new class ($id) implements CurrentAccount {
            public function __construct(private readonly ?string $id)
            {
            }

            public function accountId(): ?AccountId
            {
                return $this->id === null ? null : AccountId::fromString($this->id);
            }
        };

        $this->assertSame('5f4e3d2c-1b0a-4f9e-8d7c-6b5a4f3e2d1c', (new IdentitySignedInAccount($current('5f4e3d2c-1b0a-4f9e-8d7c-6b5a4f3e2d1c')))->accountId());
        $this->assertNull((new IdentitySignedInAccount($current(null)))->accountId());
    }

    public function test_until_identity_holds_bindings_nobody_is_bound(): void
    {
        $this->assertFalse((new NoIdentityBindingsYet())->isBound('5f4e3d2c-1b0a-4f9e-8d7c-6b5a4f3e2d1c', TenantId::fromString('0b9e7a52-6c1d-4f7e-9a3b-2d5c8e1f4a60')));
    }
}

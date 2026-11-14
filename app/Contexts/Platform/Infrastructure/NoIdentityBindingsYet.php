<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Infrastructure;

use App\Contexts\Platform\Application\Ports\IdentityBindings;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * PBDIGIT-345 task 6: the PRODUCTION answer to "is this account bound to this organisation?" until Identity's binding
 * store exists (I-TDD-02, accepted with conditions as D-200; implementation not authorised; backlog PBDIGIT-349).
 * Bindings are created only by claim redemption (D-113), which does not exist yet: so NOBODY is bound, and every
 * registry organisation is refused over HTTP. Fail closed by construction; replace it with Identity's adapter, never
 * with a lookup in the legacy user-organisation tables (D-160: the two paths never share an organisation).
 */
final class NoIdentityBindingsYet implements IdentityBindings
{
    public function isBound(string $accountId, TenantId $organisation): bool
    {
        return false;
    }
}

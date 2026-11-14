<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Application\Ports;

use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * ISO-3 (D-178 §5; tactical design E-1): is this account CURRENTLY bound to this organisation? Identity's binding
 * (D-113; I-TDD-02, D-200): created only by claim redemption, ended only by Identity's own act. Asked on EVERY request,
 * never cached by Platform. The binding grants routing only (ISO-7): never membership, access or authority.
 */
interface IdentityBindings
{
    /** @throws BindingsUnavailable Identity cannot answer: the request is refused (fail closed, D-178 §13.6) */
    public function isBound(string $accountId, TenantId $organisation): bool;
}

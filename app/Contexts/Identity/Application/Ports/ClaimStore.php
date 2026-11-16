<?php

declare(strict_types=1);

namespace App\Contexts\Identity\Application\Ports;

use App\Contexts\Identity\Application\ClaimOutcome;
use App\Contexts\Identity\Application\ClaimRefusal;
use App\Contexts\Identity\Application\IdentityUnavailable;
use App\Contexts\Identity\Application\RedemptionOutcome;
use App\Contexts\Identity\Domain\PersonId;
use App\Contexts\Identity\Domain\ClaimCode;
use App\Contexts\Identity\Domain\ClaimWindow;
use App\Contexts\Identity\Domain\OrganisationPersonRef;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * I-TDD-02 §6.2: ordinary claim operations, each ONE central transaction under the accepted locks (ClaimSlot (O, R),
 * then the claim row), with ONE expiry sample `t_check` = clock_timestamp() read after all locks. Every operation
 * checks idempotency first, then the state, then single use of the authority request (consumed in the same
 * transaction). Bootstrap claims are not handled here (I-6).
 */
interface ClaimStore
{
    /** @throws IdentityUnavailable */
    public function issue(TenantId $organisation, OrganisationPersonRef $ref, string $issuanceId, ClaimCode $code, ClaimWindow $window, string $authorityRequestId): ClaimOutcome;

    /** @throws IdentityUnavailable */
    public function replace(TenantId $organisation, string $oldClaimId, string $newIssuanceId, ClaimCode $code, ClaimWindow $window, string $authorityRequestId): ClaimOutcome;

    /** @throws IdentityUnavailable */
    public function revoke(TenantId $organisation, string $claimId, string $authorityRequestId): ClaimOutcome;

    /**
     * I-3, the ONE redemption path (§14, INV-CLM-6): ClaimSlot (O, R) → claim row → one t_check; binds R to the
     * Person, marks the claim redeemed and records ClaimRedeemed(O, R) in Identity's own outbox (identity_outbox_events,
     * central database), in one transaction. INV-BND-2 refusals are reported (BindingRefused), also when two
     * redemptions by one Person race (the losing one is decided again in a fresh transaction).
     *
     * @throws IdentityUnavailable
     */
    public function redeem(ClaimCode $code, PersonId $person): RedemptionOutcome;

    /** Content-free audit of a refusal decided before the store (§15: reason only). @throws IdentityUnavailable */
    public function recordRefusal(TenantId $organisation, ?string $claimId, ClaimRefusal $reason): void;
}

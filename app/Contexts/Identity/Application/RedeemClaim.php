<?php

declare(strict_types=1);

namespace App\Contexts\Identity\Application;

use App\Contexts\Identity\Application\Ports\AccountDirectory;
use App\Contexts\Identity\Application\Ports\ClaimStore;
use App\Contexts\Identity\Application\Ports\CurrentAccount;
use App\Contexts\Identity\Application\Ports\RedemptionThrottle;
use App\Contexts\Identity\Domain\ClaimCode;

/**
 * I-TDD-02 §14 "redeem while signed in" (C-I3): the ONE path that turns a claim code into a binding "R of O is
 * Person P". Order: signed in → the code parses → not throttled (INV-ID-9) → the Account's active Person → the shared
 * redemption in the store (ClaimSlot (O, R) → claim row → one t_check). A failed attempt on a code counts against the
 * code and the client; every refusal gives one neutral message (PI-8).
 * The binding grants NOTHING by itself: membership, office, voting and access are decided by their own contexts.
 */
final class RedeemClaim
{
    public function __construct(
        private readonly CurrentAccount $current,
        private readonly AccountDirectory $accounts,
        private readonly ClaimStore $store,
        private readonly RedemptionThrottle $throttle,
    ) {
    }

    /** @throws IdentityUnavailable Identity cannot answer: refused, never guessed */
    public function redeem(RedeemClaimCommand $command): RedemptionOutcome
    {
        $account = $this->current->accountId();
        if ($account === null) {
            return RedemptionOutcome::refused(RedemptionRefusal::NotSignedIn);
        }
        try {
            $code = ClaimCode::fromString($command->typedCode);
        } catch (\InvalidArgumentException) {
            $this->throttle->recordFailure(hash('sha256', 'malformed:' . $command->typedCode), $command->clientKey);

            return RedemptionOutcome::refused(RedemptionRefusal::NotRedeemable);
        }
        if ($this->throttle->isThrottled($code->hash(), $command->clientKey)) {
            return RedemptionOutcome::refused(RedemptionRefusal::Throttled);
        }
        $person = $this->accounts->activePersonOf($account);
        if ($person === null) {
            return RedemptionOutcome::refused(RedemptionRefusal::NoActivePerson);
        }
        $outcome = $this->store->redeem($code, $person);
        // A failed attempt counts against the code and the client (INV-ID-9). PersonAlreadyBound counts too (Principal,
        // review R-6): otherwise one person could repeat it and generate refusal events and audit rows without limit.
        if ($outcome->refusal === RedemptionRefusal::NotRedeemable || $outcome->refusal === RedemptionRefusal::PersonAlreadyBound) {
            $this->throttle->recordFailure($code->hash(), $command->clientKey);
        }

        return $outcome;
    }
}

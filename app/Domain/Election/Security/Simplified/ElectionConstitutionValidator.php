<?php

namespace App\Domain\Election\Security\Simplified;

final class ElectionConstitutionValidator
{
    public static function validate(ElectionConstitutionSnapshot $snapshot): void
    {
        // Validate maxVotesPerIp range (1-100)
        if ($snapshot->maxVotesPerIp < 1 || $snapshot->maxVotesPerIp > 100) {
            throw new \LogicException(
                'maxVotesPerIp must be between 1 and 100, got: ' . $snapshot->maxVotesPerIp
            );
        }

        // Validate network binding strategy
        if (!in_array($snapshot->networkBindingStrategy, ElectionConstitutionSchema::NETWORK_STRATEGIES)) {
            throw new \LogicException(
                'Invalid networkBindingStrategy: ' . $snapshot->networkBindingStrategy
            );
        }

        // Validate device binding strategy
        if (!in_array($snapshot->deviceBindingStrategy, ElectionConstitutionSchema::DEVICE_STRATEGIES)) {
            throw new \LogicException(
                'Invalid deviceBindingStrategy: ' . $snapshot->deviceBindingStrategy
            );
        }

        // Validate ballot authorization protocol
        if (!in_array($snapshot->ballotAuthorizationProtocol, ElectionConstitutionSchema::BALLOT_PROTOCOLS)) {
            throw new \LogicException(
                'Invalid ballotAuthorizationProtocol: ' . $snapshot->ballotAuthorizationProtocol
            );
        }
    }
}

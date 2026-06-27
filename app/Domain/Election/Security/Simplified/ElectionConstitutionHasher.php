<?php

namespace App\Domain\Election\Security\Simplified;

final class ElectionConstitutionHasher
{
    public static function hash(ElectionConstitutionSnapshot $snapshot): string
    {
        $data = [
            'networkBindingStrategy' => $snapshot->networkBindingStrategy,
            'maxVotesPerIp' => $snapshot->maxVotesPerIp,
            'deviceBindingStrategy' => $snapshot->deviceBindingStrategy,
            'ballotAuthorizationProtocol' => $snapshot->ballotAuthorizationProtocol,
            'verificationRequired' => $snapshot->verificationRequired,
        ];

        return hash('sha256', json_encode($data, JSON_THROW_ON_ERROR));
    }
}

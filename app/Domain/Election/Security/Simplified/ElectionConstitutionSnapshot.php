<?php

namespace App\Domain\Election\Security\Simplified;

readonly class ElectionConstitutionSnapshot
{
    public function __construct(
        public string $networkBindingStrategy,
        public int $maxVotesPerIp,
        public string $deviceBindingStrategy,
        public string $ballotAuthorizationProtocol,
        public bool $verificationRequired,
    ) {}
}

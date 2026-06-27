<?php

namespace App\Domain\Election\Security\Simplified;

readonly class SessionContinuity
{
    public function __construct(
        public string $sessionId,
        public string $ipHashAtStart,
        public string $ipHashCurrent,
        public bool $deviceChanged,
        public string $continuityState,  // 'continuous'|'interrupted'|'invalidated'
    ) {}
}

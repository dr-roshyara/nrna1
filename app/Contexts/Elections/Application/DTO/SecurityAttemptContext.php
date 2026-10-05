<?php

namespace App\Contexts\Elections\Application\DTO;

/**
 * SecurityAttemptContext — the security-attempt input of the one Security Verdict Authority
 * (VotingSecurityVerdictQuery; PBDIGIT-239, blueprint §3).
 *
 * Callers fill it from the same sources the inline Phase-4 path used in VoteController::store():
 * clientIp = request()->ip(), deviceFingerprint = request input 'device_fingerprint',
 * sessionId = the session id. The verdict is computed from these facts only.
 */
final readonly class SecurityAttemptContext
{
    public function __construct(
        public string $userId,
        public string $electionId,
        public string $clientIp,
        public ?string $deviceFingerprint,
        public string $sessionId,
    ) {}
}

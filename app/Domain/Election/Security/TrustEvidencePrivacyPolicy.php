<?php

namespace App\Domain\Election\Security;

final class TrustEvidencePrivacyPolicy
{
    public function hashIp(string $rawIp, string $electionSalt): string
    {
        return hash('sha256', $rawIp . $electionSalt);
    }

    public function hashFingerprint(string $fp, string $salt): string
    {
        return hash('sha256', $fp . $salt);
    }

    public function minimizeNetworkEvidence(array $evidence): array
    {
        return [
            'ip_hash' => $evidence['ip_hash'] ?? null,
            'votes_from_this_ip' => $evidence['votes_from_this_ip'] ?? null,
            'restriction_enabled' => $evidence['restriction_enabled'] ?? null,
        ];
    }

    public function minimizeDeviceEvidence(array $evidence): array
    {
        return [
            'fingerprint_hash' => $evidence['fingerprint_hash'] ?? null,
            'match_type' => $evidence['match_type'] ?? null,
        ];
    }

    public function canRetainRawIp(?\App\Models\Election $election): bool
    {
        return false;
    }
}

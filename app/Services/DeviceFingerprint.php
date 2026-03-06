<?php

namespace App\Services;

use App\Models\Code;
use App\Models\Organisation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class DeviceFingerprint
{
    /**
     * Generate device fingerprint from request
     */
    public function generate(Request $request, array $additional = []): string
    {
        $components = [
            'ip' => $request->ip(),
            'ua' => $request->userAgent(),
            'lang' => $request->header('Accept-Language'),
            'enc' => $request->header('Accept-Encoding'),
            'screen' => $request->input('screen_resolution'),
            'tz' => $request->input('timezone'),
            'salt' => config('app.fingerprint_salt', 'default-salt'),
        ];

        // Merge additional data
        $components = array_merge($components, $additional);

        // Sort for consistency
        ksort($components);

        // Create fingerprint string
        $fingerprintString = implode('|', array_filter($components));

        // Return SHA256 hash
        return hash('sha256', $fingerprintString);
    }

    /**
     * Check if device can vote in this election
     */
    public function canVote(
        string $deviceHash,
        string $electionId,
        ?Organisation $organisation = null
    ): array {
        $maxVotes = $this->getMaxVotesPerDevice($organisation);

        $existingCount = Code::withoutGlobalScopes()
            ->where('device_fingerprint_hash', $deviceHash)
            ->where('election_id', $electionId)
            ->count();

        $remaining = max(0, $maxVotes - $existingCount);

        return [
            'allowed' => $existingCount < $maxVotes,
            'used' => $existingCount,
            'max' => $maxVotes,
            'remaining' => $remaining,
            'message' => $this->getLimitMessage($organisation, $remaining),
        ];
    }

    /**
     * Detect anomalous voting patterns
     */
    public function detectAnomaly(
        string $deviceHash,
        string $electionId
    ): array {
        $windowMinutes = config('voting.device_time_window_minutes', 10);
        $threshold = config('voting.device_anomaly_threshold', 5);

        $recentCount = Code::withoutGlobalScopes()
            ->where('device_fingerprint_hash', $deviceHash)
            ->where('election_id', $electionId)
            ->where('created_at', '>=', now()->subMinutes($windowMinutes))
            ->count();

        $detected = $recentCount >= $threshold;

        if ($detected) {
            Log::warning('Anomalous device pattern detected', [
                'device_hash' => $deviceHash,
                'election_id' => $electionId,
                'count' => $recentCount,
                'threshold' => $threshold,
                'window' => $windowMinutes,
            ]);
        }

        return [
            'detected' => $detected,
            'count' => $recentCount,
            'threshold' => $threshold,
            'window_minutes' => $windowMinutes,
        ];
    }

    /**
     * Get max votes per device for organisation
     */
    protected function getMaxVotesPerDevice(?Organisation $organisation = null): int
    {
        if ($organisation && $organisation->voting_settings) {
            $orgLimit = $organisation->voting_settings['max_votes_per_device'] ?? null;
            if ($orgLimit !== null) {
                return (int) $orgLimit;
            }
        }

        return (int) config('voting.max_votes_per_device', 1);
    }

    /**
     * Get user-friendly limit message
     */
    public function getLimitMessage(?Organisation $organisation = null, int $remaining = null): string
    {
        $max = $this->getMaxVotesPerDevice($organisation);

        // Custom message from organisation
        if ($organisation && !empty($organisation->voting_settings['family_message'])) {
            return $organisation->voting_settings['family_message'];
        }

        if ($max === 1) {
            return "⚠️ One vote per device - each person needs their own device.";
        }

        if ($max > 10) {
            return "🧪 Test/Demo mode - unlimited votes per device.";
        }

        if ($remaining !== null) {
            return "👨‍👩‍👧 Family voting allowed - {$remaining} vote(s) remaining on this device.";
        }

        return "👨‍👩‍👧 This election allows {$max} votes per device for family members.";
    }

    /**
     * Get device usage statistics
     */
    public function getDeviceStats(string $deviceHash, string $electionId): array
    {
        $codes = Code::withoutGlobalScopes()
            ->where('device_fingerprint_hash', $deviceHash)
            ->where('election_id', $electionId)
            ->get();

        return [
            'total_codes' => $codes->count(),
            'used_codes' => $codes->where('is_used', true)->count(),
            'unused_codes' => $codes->where('is_used', false)->count(),
            'first_used' => $codes->min('created_at'),
            'last_used' => $codes->max('created_at'),
            'anomaly' => $this->detectAnomaly($deviceHash, $electionId),
        ];
    }
}

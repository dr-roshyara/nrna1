<?php

namespace App\Services\Voting;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Class DeviceFingerprintService
 *
 * Privacy-preserving device fingerprinting for fraud detection
 *
 * Creates a one-way hash of device characteristics WITHOUT storing PII:
 * - User Agent (browser, OS)
 * - Accept-Language header
 * - Timezone offset
 * - Platform
 *
 * The hash cannot be reversed to original data, but same device
 * will produce same hash, allowing duplicate vote detection.
 */
class DeviceFingerprintService
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * Constructor
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Generate privacy-preserving device fingerprint hash
     *
     * @return string|null SHA-256 hash of device characteristics
     */
    public function generateFingerprint(): ?string
    {
        try {
            // Collect anonymized device signals (no PII)
            $signals = [
                'user_agent' => $this->request->userAgent(),
                'accept_language' => $this->request->header('Accept-Language'),
                'platform' => php_uname('s'),  // OS name
            ];

            // Add timezone offset if available (privacy-preserving)
            $timezone = $this->request->header('X-Timezone-Offset');
            if ($timezone) {
                $signals['timezone_offset'] = (int) $timezone;
            }

            // Add screen resolution if available (via JavaScript in frontend)
            $screen = $this->request->header('X-Screen-Resolution');
            if ($screen) {
                $signals['screen'] = $screen;
            }

            // Create JSON string of signals
            $signalString = json_encode($signals, JSON_UNESCAPED_SLASHES);

            // Add application salt to prevent rainbow table attacks
            $salt = config('app.device_fingerprint_salt', config('app.key'));

            // Generate SHA-256 hash (one-way, cannot be reversed)
            $hash = hash('sha256', $salt . $signalString);

            Log::debug('Device fingerprint generated', [
                'hash_prefix' => substr($hash, 0, 8) . '...',
                'signals_count' => count($signals),
            ]);

            return $hash;

        } catch (\Exception $e) {
            Log::error('Failed to generate device fingerprint', [
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Generate anonymized metadata for audit/logging
     * Contains non-identifiable device info for debugging
     *
     * @return array
     */
    public function generateAnonymizedMetadata(): array
    {
        // Parse user agent for browser detection
        $userAgent = $this->request->userAgent() ?? '';
        $browser = $this->detectBrowser($userAgent);
        $isMobile = $this->isMobileUserAgent($userAgent);

        return [
            'browser' => $browser,
            'platform' => php_uname('s'),
            'user_agent_snippet' => substr($userAgent, 0, 50),  // Non-PII snippet only
            'is_mobile' => $isMobile,
            'accept_language' => $this->request->header('Accept-Language'),
            'fingerprint_time' => now()->toIso8601String(),
        ];
    }

    /**
     * Detect browser from user agent string (simplified)
     *
     * @param string $userAgent
     * @return string
     */
    protected function detectBrowser(string $userAgent): string
    {
        if (stripos($userAgent, 'firefox') !== false) {
            return 'Firefox';
        } elseif (stripos($userAgent, 'chrome') !== false) {
            return 'Chrome';
        } elseif (stripos($userAgent, 'safari') !== false) {
            return 'Safari';
        } elseif (stripos($userAgent, 'edge') !== false) {
            return 'Edge';
        } elseif (stripos($userAgent, 'opera') !== false) {
            return 'Opera';
        }
        return 'Unknown';
    }

    /**
     * Check if user agent is from mobile device
     *
     * @param string $userAgent
     * @return bool
     */
    protected function isMobileUserAgent(string $userAgent): bool
    {
        return (bool) preg_match(
            '/Mobile|Android|iPhone|iPad|iPod|BlackBerry|Windows Phone|IEMobile|Opera Mini/i',
            $userAgent
        );
    }

    /**
     * Check if this device has been seen before in this election
     * Used to detect multiple votes from same device
     *
     * @param string $fingerprintHash
     * @param string $electionId
     * @return bool
     */
    public function hasDeviceVotedInElection(string $fingerprintHash, string $electionId): bool
    {
        // Check DemoCode table for existing votes with same fingerprint
        $existingVote = \App\Models\Demo\DemoCode::where('device_fingerprint_hash', $fingerprintHash)
            ->where('election_id', $electionId)
            ->where('has_voted', true)
            ->exists();

        if ($existingVote) {
            Log::info('Duplicate device detected', [
                'election_id' => $electionId,
                'fingerprint_prefix' => substr($fingerprintHash, 0, 8) . '...',
            ]);
        }

        return $existingVote;
    }
}

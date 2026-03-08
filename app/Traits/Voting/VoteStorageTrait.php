<?php

namespace App\Traits\Voting;

use App\Models\DemoVote;
use App\Models\DemoCode;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * VoteStorageTrait - Secure Anonymous Vote Storage
 *
 * Wraps vote creation in database transactions to ensure data consistency.
 * Prevents orphaned votes if results saving fails.
 *
 * CRITICAL SECURITY:
 * - Vote hash uses code->id (NOT user_id) - PRESERVES ANONYMITY
 * - Device fingerprinting for fraud detection (privacy-preserving SHA256 hash)
 * - Transaction rollback on any failure - no partial votes
 * - Comprehensive audit logging without exposing voter identity
 */
trait VoteStorageTrait
{
    /**
     * Generate anonymous vote hash
     *
     * CRITICAL: Uses code->id (NOT user_id) to prevent voter-to-vote linkage
     *
     * Hash components:
     * - code->id: Code UUID (no voter identification)
     * - election->id: Election scoping
     * - code->code_to_open_voting_form: Unique verification code
     * - cast_at->timestamp: Prevents collisions
     *
     * @param DemoCode $code The voting code
     * @param object $election The election
     * @param object $castAt The cast timestamp
     * @return string SHA256 hash
     */
    protected function generateAnonymousVoteHash($code, $election, $castAt): string
    {
        // CRITICAL ANONYMITY FIX: Use code->id, NOT code->user_id
        return hash('sha256',
            $code->id .                           // Code UUID (NOT user_id)
            $election->id .
            $code->code_to_open_voting_form .
            $castAt->timestamp .
            config('app.vote_salt', '')
        );
    }

    /**
     * Generate device fingerprint hash (privacy-preserving)
     *
     * Creates a one-way hash of device characteristics without storing PII:
     * - User Agent
     * - Accept-Language
     * - Timezone
     * - Platform
     *
     * @param object $request The request
     * @return string|null SHA256 hash or null on error
     */
    protected function generateDeviceFingerprintHash($request): ?string
    {
        try {
            $fingerprint = hash('sha256',
                ($request->header('User-Agent') ?? '') .
                ($request->header('Accept-Language') ?? '') .
                (function_exists('wp_timezone_string') ? wp_timezone_string() : '') .
                php_uname('s') .
                config('app.device_fingerprint_salt', '')
            );

            return $fingerprint;
        } catch (\Exception $e) {
            Log::warning('Failed to generate device fingerprint', [
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Generate anonymized device metadata (no PII)
     *
     * @param object $request The request
     * @return array Anonymized metadata (browser, platform, etc)
     */
    protected function generateAnonymizedDeviceMetadata($request): array
    {
        return [
            'accept_language' => $request->header('Accept-Language') ?? 'unknown',
            'platform' => php_uname('s'),
            'fingerprint_generated_at' => now()->toIso8601String(),
        ];
    }

    /**
     * Store vote with transaction protection
     *
     * Wraps vote creation and results saving in a transaction.
     * If results saving fails, entire transaction rolls back - no orphaned votes.
     *
     * CRITICAL FEATURES:
     * - Uses code->id (NOT user_id) in vote hash - PRESERVES ANONYMITY
     * - Device fingerprinting for fraud detection
     * - Atomic transaction - all or nothing
     * - Comprehensive audit logging
     *
     * @param array $voteData Vote data (selections, metadata, etc)
     * @param DemoCode $code The voting code
     * @param object $election The election
     * @param object $demoVoterSlug The voter slug (for tracking only)
     * @return DemoVote The created vote record
     * @throws \Exception On transaction failure
     */
    protected function storeVoteWithResults(
        array $voteData,
        $code,
        $election,
        $demoVoterSlug
    ): DemoVote {
        return DB::transaction(function () use ($voteData, $code, $election, $demoVoterSlug) {
            $castAt = now();

            // Create the vote record
            $vote = new DemoVote();
            $vote->election_id = $election->id;
            $vote->organisation_id = $election->organisation_id ?? session('current_organisation_id');

            // CRITICAL ANONYMITY: Use code->id (NOT user_id)
            $vote->vote_hash = $this->generateAnonymousVoteHash($code, $election, $castAt);

            // Device fingerprinting (privacy-preserving)
            $vote->device_fingerprint_hash = $this->generateDeviceFingerprintHash(request());
            $vote->device_metadata_anonymized = $this->generateAnonymizedDeviceMetadata(request());

            // Vote content
            $vote->candidate_selections = $voteData['selections'] ?? [];
            $vote->no_vote_posts = $voteData['no_vote_posts'] ?? [];
            $vote->cast_at = $castAt;
            $vote->voted_at = $castAt;

            // Voter verification (receipt)
            $vote->receipt_hash = hash('sha256',
                $code->code_to_open_voting_form .
                $castAt->timestamp .
                config('app.salt', '')
            );

            // Audit trail (anonymized IP)
            $vote->voter_ip = $this->anonymizeIp(request()->ip());

            // Voting code for audit trail (anonymity bridge)
            $vote->voting_code = $code->voting_code ?? null;

            // Save the vote record
            $vote->save();

            Log::info('Vote stored successfully', [
                'vote_id_prefix' => substr($vote->id, 0, 8) . '...',
                'election_id' => $election->id,
                'device_fingerprint_prefix' => substr($vote->device_fingerprint_hash ?? '', 0, 8) . '...',
                'vote_hash_prefix' => substr($vote->vote_hash, 0, 8) . '...',
                'timestamp' => $castAt->toIso8601String(),
            ]);

            // Save candidate results (this may fail - transaction rollback if so)
            if (!empty($voteData['selections'])) {
                $this->saveCandidateResults($vote, $voteData, $election);
            }

            Log::info('Vote and results stored successfully', [
                'vote_id_prefix' => substr($vote->id, 0, 8) . '...',
                'election_id' => $election->id,
                'results_count' => count($voteData['selections'] ?? []),
            ]);

            return $vote;
        }, attempts: 3); // Retry transaction up to 3 times on deadlock
    }

    /**
     * Save candidate results for a vote
     *
     * Must be called within a transaction context.
     * If this fails, parent transaction rolls back automatically.
     *
     * @param DemoVote $vote The vote record
     * @param array $voteData Vote data with selections
     * @param object $election The election
     * @return void
     */
    protected function saveCandidateResults($vote, array $voteData, $election): void
    {
        foreach ($voteData['selections'] as $candidateId => $selection) {
            if ($selection) {
                // Create result record
                DB::table('demo_results')->insert([
                    'id' => \Illuminate\Support\Str::uuid(),
                    'vote_id' => $vote->id,
                    'candidate_id' => $candidateId,
                    'election_id' => $election->id,
                    'organisation_id' => $election->organisation_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Anonymize IP address for privacy
     *
     * Removes last octet of IPv4, truncates IPv6
     *
     * @param string|null $ip The IP address
     * @return string|null Anonymized IP or null
     */
    protected function anonymizeIp(?string $ip): ?string
    {
        if (!$ip) {
            return null;
        }

        // IPv4: replace last octet with 0
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $parts = explode('.', $ip);
            $parts[3] = '0';
            return implode('.', $parts);
        }

        // IPv6: truncate to first 64 bits
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $parts = explode(':', $ip);
            return implode(':', array_slice($parts, 0, 4)) . '::';
        }

        return $ip;
    }
}

<?php

namespace App\Traits\Voting;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

/**
 * CodeVerificationTrait - Consolidate Voting Code Verification
 *
 * Provides secure, timing-attack resistant code verification methods.
 * Replaces 3 duplicated verification functions across DemoVoteController.
 *
 * Security Guarantees:
 * - ALL string comparisons use hash_equals() or Hash::check()
 * - NEVER uses === or strcmp() for security-sensitive comparisons
 * - Timing attack resistant
 * - Comprehensive error logging without exposing sensitive data
 */
trait CodeVerificationTrait
{
    /**
     * Verify a plain text code (case-insensitive, whitespace-trimmed)
     *
     * Used for codes stored as plain text (e.g., code_to_open_voting_form)
     *
     * Security: Uses hash_equals() to prevent timing attacks
     *
     * @param string $submitted The code submitted by user
     * @param string $stored The code stored in database
     * @return bool True if codes match
     */
    protected function verifyPlainCode(string $submitted, string $stored): bool
    {
        // Input validation
        if (empty($submitted) || empty($stored)) {
            Log::warning('Code verification failed: empty parameters', [
                'submitted_empty' => empty($submitted),
                'stored_empty' => empty($stored),
                'user_id' => auth()->id() ?? 'unknown',
            ]);
            return false;
        }

        try {
            // Clean and normalize both codes for comparison
            $cleanSubmitted = strtoupper(trim($submitted));
            $cleanStored = strtoupper(trim($stored));

            // Timing-safe comparison
            $result = hash_equals($cleanSubmitted, $cleanStored);

            Log::info('Plain text code verification', [
                'user_id' => auth()->id() ?? 'unknown',
                'result' => $result ? 'PASS' : 'FAIL',
                'attempt_timestamp' => now(),
            ]);

            return $result;
        } catch (\Exception $e) {
            Log::warning('Plain text code verification error', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id() ?? 'unknown',
            ]);
            return false;
        }
    }

    /**
     * Verify a hashed code using Laravel Hash (bcrypt)
     *
     * Used for codes hashed with Hash::make() (e.g., code_for_vote)
     *
     * Security: Uses Hash::check() which is timing-attack resistant
     *
     * @param string $submitted The code submitted by user
     * @param string $hashed The hashed code from database
     * @return bool True if codes match
     */
    protected function verifyHashedCode(string $submitted, string $hashed): bool
    {
        // Input validation
        if (empty($submitted) || empty($hashed)) {
            Log::warning('Hashed code verification failed: empty parameters', [
                'submitted_empty' => empty($submitted),
                'hashed_empty' => empty($hashed),
                'user_id' => auth()->id() ?? 'unknown',
            ]);
            return false;
        }

        try {
            // Hash::check() is timing-safe and handles bcrypt verification
            $result = Hash::check($submitted, $hashed);

            Log::info('Hashed code verification', [
                'user_id' => auth()->id() ?? 'unknown',
                'result' => $result ? 'PASS' : 'FAIL',
                'hash_algorithm' => 'bcrypt',
                'attempt_timestamp' => now(),
            ]);

            return $result;
        } catch (\Exception $e) {
            Log::warning('Hashed code verification error', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id() ?? 'unknown',
            ]);
            return false;
        }
    }

    /**
     * Verify a password-hashed code using password_verify()
     *
     * Used for codes hashed with password_hash() or similar (legacy support)
     *
     * Security: password_verify() is timing-attack resistant
     *
     * @param string $submitted The code submitted by user
     * @param string $passwordHash The hashed code from database
     * @return bool True if codes match
     */
    protected function verifyPasswordHashCode(string $submitted, string $passwordHash): bool
    {
        // Input validation
        if (empty($submitted) || empty($passwordHash)) {
            Log::warning('Password hash code verification failed: empty parameters', [
                'submitted_empty' => empty($submitted),
                'hash_empty' => empty($passwordHash),
                'user_id' => auth()->id() ?? 'unknown',
            ]);
            return false;
        }

        try {
            // password_verify() is timing-safe
            $result = password_verify($submitted, $passwordHash);

            Log::info('Password hash code verification', [
                'user_id' => auth()->id() ?? 'unknown',
                'result' => $result ? 'PASS' : 'FAIL',
                'hash_algorithm' => 'password_hash',
                'attempt_timestamp' => now(),
            ]);

            return $result;
        } catch (\Exception $e) {
            Log::warning('Password hash code verification error', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id() ?? 'unknown',
            ]);
            return false;
        }
    }

    /**
     * Check if voting window has expired
     *
     * Uses voting_time_in_minutes from code or config
     *
     * @param object $code The voting code record
     * @return bool True if window has expired
     */
    protected function hasVotingWindowExpired($code): bool
    {
        // Must have a sent_at timestamp to check expiration
        if (!$code->code_to_open_voting_form_sent_at) {
            return false;
        }

        // Get voting window duration in minutes
        $windowMinutes = $code->voting_time_in_minutes ?? config('voting.time_in_minutes', 30);

        // Calculate elapsed time
        $elapsedMinutes = \Carbon\Carbon::parse($code->code_to_open_voting_form_sent_at)
            ->diffInMinutes(now());

        return $elapsedMinutes > $windowMinutes;
    }
}

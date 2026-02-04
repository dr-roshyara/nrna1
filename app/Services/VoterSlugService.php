<?php

namespace App\Services;

use App\Models\VoterSlug;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class VoterSlugService
{
    /**
     * Generate a new 30-minute voting slug for a user
     *
     * @param User $user
     * @param int|null $electionId - Election to associate with this slug. If null, uses demo election
     */
    public function generateSlugForUser(User $user, ?int $electionId = null): VoterSlug
    {
        return DB::transaction(function () use ($user, $electionId) {
            // Determine election if not provided
            if (!$electionId) {
                // Try to get from session (user may have selected an election)
                $sessionElectionId = session('selected_election_id');
                if ($sessionElectionId) {
                    $electionId = $sessionElectionId;
                } else {
                    // Default to demo election for testing
                    $election = \App\Models\Election::where('type', 'demo')->first();
                    $electionId = $election ? $election->id : null;
                }
            }

            // Revoke any existing active slugs for this user
            VoterSlug::where('user_id', $user->id)
                ->where('is_active', true)
                ->update(['is_active' => false]);

            // Generate URL-safe random slug
            $slug = $this->generateRandomSlug();

            // Ensure uniqueness (very unlikely collision, but safety first)
            while (VoterSlug::where('slug', $slug)->exists()) {
                $slug = $this->generateRandomSlug();
            }

            // Create new 30-minute slug
            return VoterSlug::create([
                'user_id' => $user->id,
                'slug' => $slug,
                'expires_at' => now()->addMinutes(30),
                'is_active' => true,
                'current_step' => 1,
                'step_meta' => [],
                'election_id' => $electionId,
            ]);
        });
    }

    /**
     * Get active slug for a user (if exists and valid)
     */
    public function getActiveSlugForUser(User $user): ?VoterSlug
    {
        return VoterSlug::where('user_id', $user->id)
            ->valid()
            ->first();
    }

    /**
     * Revoke a specific slug
     */
    public function revokeSlug(VoterSlug $slug): bool
    {
        return $slug->update(['is_active' => false]);
    }

    /**
     * Revoke all active slugs for a user
     */
    public function revokeAllSlugsForUser(User $user): int
    {
        return VoterSlug::where('user_id', $user->id)
            ->where('is_active', true)
            ->update(['is_active' => false]);
    }

    /**
     * Extend slug expiry by 30 minutes (sliding window)
     */
    public function extendSlugExpiry(VoterSlug $slug): bool
    {
        if (!$slug->is_active) {
            return false;
        }

        return $slug->update([
            'expires_at' => now()->addMinutes(30)
        ]);
    }

    /**
     * Cleanup expired slugs (for scheduled job)
     */
    public function cleanupExpiredSlugs(): int
    {
        return VoterSlug::where('expires_at', '<', now())->delete();
    }

    /**
     * Generate a URL-safe random slug with enhanced uniqueness
     */
    private function generateRandomSlug(): string
    {
        // Generate timestamp-based prefix for better uniqueness
        $timestamp = base_convert(time(), 10, 36);

        // Generate random suffix with higher entropy
        $randomBytes = rtrim(strtr(base64_encode(random_bytes(21)), '+/', '-_'), '=');

        // Combine timestamp + random for maximum uniqueness
        return $timestamp . '_' . $randomBytes;
    }

    /**
     * Build the voting link for a slug
     */
    public function buildVotingLink(VoterSlug $slug, string $routeName = 'slug.code.create'): string
    {
        return route($routeName, ['vslug' => $slug->slug]);
    }

    /**
     * Get or create a single active slug for a user (ensures one-slug-per-person)
     */
    public function getOrCreateActiveSlug(User $user): VoterSlug
    {
        return DB::transaction(function () use ($user) {
            // First check if user already has an active slug
            $existingSlug = VoterSlug::where('user_id', $user->id)
                ->where('is_active', true)
                ->where('expires_at', '>', now())
                ->first();

            if ($existingSlug) {
                // Extend the expiry by 30 minutes (sliding window)
                $existingSlug->update([
                    'expires_at' => now()->addMinutes(30)
                ]);

                return $existingSlug;
            }

            // No active slug exists, create a new one
            return $this->generateSlugForUser($user);
        });
    }

    /**
     * Validate slug belongs to user and is still active
     */
    public function validateSlugForUser(string $slug, User $user): ?VoterSlug
    {
        return VoterSlug::where('slug', $slug)
            ->where('user_id', $user->id)
            ->where('is_active', true)
            ->where('expires_at', '>', now())
            ->first();
    }
}
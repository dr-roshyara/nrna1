<?php

namespace App\Services;

use App\Models\VoterSlug;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VoterSlugService
{
    protected DemoElectionResolver $electionResolver;

    public function __construct(DemoElectionResolver $electionResolver)
    {
        $this->electionResolver = $electionResolver;
    }

    /**
     * Generate a new 30-minute voting slug for a user
     *
     * NOW USES DEMOELECTIONRESOLVER to ensure CORRECT election_id is saved!
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
                    // ✅ FIXED: Use DemoElectionResolver to get CORRECT demo election
                    $election = $this->electionResolver->getDemoElectionForUser($user);
                    if (!$election) {
                        throw new \Exception('No demo election available. Please create a demo election first.');
                    }
                    $electionId = $election->id;
                }
            }

            \Log::info('🔑 [VoterSlugService] Creating slug with election', [
                'user_id' => $user->id,
                'user_org_id' => $user->organisation_id,
                'election_id' => $electionId,
            ]);

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

            // Get the election to save its organisation_id
            $election = \App\Models\Election::withoutGlobalScopes()->find($electionId);
            if (!$election) {
                throw new \Exception('Election not found');
            }

            // Create new 30-minute slug
            // ✅ CRITICAL: Include organisation_id from election
            $voterSlug = VoterSlug::create([
                'user_id' => $user->id,
                'slug' => $slug,
                'expires_at' => now()->addMinutes(30),
                'is_active' => true,
                'current_step' => 1,
                'step_meta' => [],
                'election_id' => $electionId,
                'organisation_id' => $election->organisation_id,  // ✅ CRITICAL
            ]);

            \Log::info('✅ New voter slug created with correct election and org', [
                'user_id' => $user->id,
                'slug' => $voterSlug->slug,
                'election_id' => $voterSlug->election_id,
                'organisation_id' => $voterSlug->organisation_id,
            ]);

            return $voterSlug;
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
     *
     * NOW USES DEMOELECTIONRESOLVER to ensure CORRECT election_id!
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
            // ✅ Now uses DemoElectionResolver for correct election selection
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

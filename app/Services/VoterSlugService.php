<?php

namespace App\Services;

use App\Models\VoterSlug;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class VoterSlugService
{
    /**
     * Generate a new 30-minute voting slug for a user
     */
    public function generateSlugForUser(User $user): VoterSlug
    {
        return DB::transaction(function () use ($user) {
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
     * Generate a URL-safe random slug
     */
    private function generateRandomSlug(): string
    {
        return rtrim(strtr(base64_encode(random_bytes(18)), '+/', '-_'), '=');
    }

    /**
     * Build the voting link for a slug
     */
    public function buildVotingLink(VoterSlug $slug, string $routeName = 'voter.code.create'): string
    {
        return route($routeName, ['vslug' => $slug->slug]);
    }
}
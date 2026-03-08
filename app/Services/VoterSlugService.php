<?php

namespace App\Services;

use App\Models\VoterSlug;
use App\Models\DemoVoterSlug;
use App\Models\Election;
use App\Models\User;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

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

            // Revoke any existing active slugs for this user by soft-deleting them
            // (deactivating is not enough due to unique constraint on election_id + user_id)
            VoterSlug::where('user_id', $user->id)
                ->where('is_active', true)
                ->delete(); // Soft delete to respect unique constraint

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

            // Create slug using configured voting time
            // ✅ CRITICAL: Include organisation_id from election
            $voterSlug = VoterSlug::create([
                'user_id' => $user->id,
                'slug' => $slug,
                'expires_at' => now()->addMinutes(config('voting.time_in_minutes', 30)),
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

    /**
     * ✅ NEW: Validate that a slug belongs to the correct user and election
     *
     * SECURITY: This is the critical check that prevents unauthorized slug usage
     * - Prevents cross-election voting (slug from Election A used in Election B)
     * - Prevents vote theft (slug from User A used by User B)
     *
     * @param VoterSlug|DemoVoterSlug $slug
     * @param User $user
     * @param Election $election
     * @throws AccessDeniedHttpException
     * @return bool
     */
    public function validateSlugOwnership($slug, User $user, Election $election): bool
    {
        // ✅ SECURITY CHECK 1: Slug user must match request user
        if ($slug->user_id !== $user->id) {
            Log::warning('SECURITY: Slug user mismatch detected', [
                'slug_user_id' => $slug->user_id,
                'request_user_id' => $user->id,
                'slug_id' => $slug->id,
                'slug' => substr($slug->slug, 0, 10) . '...',
            ]);
            throw new AccessDeniedHttpException('This voting slug does not belong to you.');
        }

        // ✅ SECURITY CHECK 2: Slug election must match request election
        if ($slug->election_id !== $election->id) {
            Log::warning('SECURITY: Slug election mismatch detected', [
                'slug_election_id' => $slug->election_id,
                'request_election_id' => $election->id,
                'slug_id' => $slug->id,
                'slug' => substr($slug->slug, 0, 10) . '...',
            ]);
            throw new AccessDeniedHttpException('This voting slug is for a different election.');
        }

        return true;
    }

    /**
     * ✅ NEW: Get or create a voter slug with proper expiration handling
     *
     * BUSINESS LOGIC:
     * - Demo elections: Always create fresh slugs (no reuse)
     * - Real elections: Reuse active slugs, create new ones when expired
     * - Automatically cleans up expired slugs
     * - Validates slug belongs to correct user and election
     *
     * @param User $user
     * @param Election $election
     * @param bool $forceNew Force creation of new slug (used for demo restart)
     * @return VoterSlug|DemoVoterSlug
     */
    public function getOrCreateSlug(User $user, Election $election, bool $forceNew = false)
    {
        $model = $election->type === 'demo' ? DemoVoterSlug::class : VoterSlug::class;

        Log::debug('Voter slug service: getOrCreateSlug', [
            'user_id' => $user->id,
            'election_id' => $election->id,
            'election_type' => $election->type,
            'forceNew' => $forceNew,
        ]);

        // BUSINESS LOGIC: Demo elections always get fresh slugs
        if ($forceNew || $election->type === 'demo') {
            Log::info('Creating fresh voter slug', [
                'user_id' => $user->id,
                'election_id' => $election->id,
                'reason' => $forceNew ? 'forced' : 'demo_election',
            ]);
            return $this->createNewSlug($user, $election, $model);
        }

        // BUSINESS LOGIC: Real elections check for existing active slug
        // CRITICAL: WHERE user_id = $user->id AND election_id = $election->id
        $slug = $model::withoutGlobalScopes()
            ->where('user_id', $user->id)           // ✅ Must match user
            ->where('election_id', $election->id)             // ✅ Must match election
            ->where('expires_at', '>', Carbon::now())
            ->where('is_active', true)
            ->where('status', 'active')
            ->first();

        if ($slug) {
            Log::info('Returning existing active voter slug', [
                'slug_id' => $slug->id,
                'user_id' => $user->id,
                'election_id' => $election->id,
                'expires_at' => $slug->expires_at,
            ]);
            return $slug;
        }

        // BUSINESS LOGIC: No active slug found - check for expired and cleanup
        $this->cleanupExpiredSlugs($user, $election, $model);

        // Create new slug for this user in this election
        return $this->createNewSlug($user, $election, $model);
    }

    /**
     * ✅ NEW: Get slug by its string identifier with ownership validation
     *
     * SECURITY: Validates that slug belongs to the request user and election
     * This prevents attacks where someone tries to use another user's slug
     *
     * @param string $slugString
     * @param User $user
     * @param Election $election
     * @return VoterSlug|DemoVoterSlug|null
     * @throws AccessDeniedHttpException
     */
    public function getValidatedSlug(string $slugString, User $user, Election $election)
    {
        $model = $election->type === 'demo' ? DemoVoterSlug::class : VoterSlug::class;

        $slug = $model::where('slug', $slugString)->first();

        if (!$slug) {
            Log::warning('Slug not found', [
                'slug_requested' => substr($slugString, 0, 10) . '...',
                'user_id' => $user->id,
                'election_id' => $election->id,
            ]);
            return null;
        }

        // ✅ SECURITY: Validate ownership before returning
        $this->validateSlugOwnership($slug, $user, $election);

        return $slug;
    }

    /**
     * ✅ NEW: Create a new voter slug with auto-expiration
     *
     * @param User $user
     * @param Election $election
     * @param string $model VoterSlug or DemoVoterSlug
     * @return VoterSlug|DemoVoterSlug
     */
    protected function createNewSlug(User $user, Election $election, string $model)
    {
        // Delete old active slugs for this user/election (if any) - respects unique constraint
        $deleted = $model::where('user_id', $user->id)
            ->where('election_id', $election->id)
            ->delete();  // Soft delete if supported, hard delete otherwise

        if ($deleted > 0) {
            Log::info('Removed existing slugs before creating new one', [
                'user_id' => $user->id,
                'election_id' => $election->id,
                'deleted_count' => $deleted,
            ]);
        }

        // Generate unique slug
        $slug = $this->generateUniqueSlugForModel($model);

        // Set expiration (30 minutes by default)
        $expiresAt = Carbon::now()->addMinutes(
            config('voting.slug_expiration_minutes', 30)
        );

        // ✅ CREATE: Links to correct user and election
        $voterSlug = $model::create([
            'user_id' => $user->id,                          // ✅ Bind to user
            'election_id' => $election->id,                  // ✅ Bind to election
            'organisation_id' => $election->organisation_id,
            'slug' => $slug,
            'expires_at' => $expiresAt,
            'is_active' => true,
            'status' => 'active',
            'current_step' => 1,
            'can_vote_now' => true,
        ]);

        Log::info('Created new voter slug', [
            'slug_id' => $voterSlug->id,
            'slug' => substr($slug, 0, 10) . '...',
            'user_id' => $user->id,
            'election_id' => $election->id,
            'expires_at' => $expiresAt->toDateTimeString(),
        ]);

        return $voterSlug;
    }

    /**
     * ✅ NEW: Generate a unique slug with retry logic
     *
     * @param string $model
     * @return string
     */
    protected function generateUniqueSlugForModel(string $model): string
    {
        $maxAttempts = 10;
        $attempts = 0;

        do {
            $slug = 'tbj' . Str::random(30);
            $attempts++;

            if ($attempts >= $maxAttempts) {
                throw new \RuntimeException('Failed to generate unique slug after ' . $maxAttempts . ' attempts');
            }
        } while ($model::where('slug', $slug)->exists());

        return $slug;
    }

    /**
     * ✅ NEW: Delete expired slugs for cleanup
     *
     * BUSINESS LOGIC: When a user requests a new slug and has an expired one,
     * delete it to prevent unique constraint violations and ensure fresh start.
     *
     * @param User $user
     * @param Election $election
     * @param string $model
     * @return int Number of slugs cleaned up
     */
    protected function cleanupExpiredSlugs(User $user, Election $election, string $model): int
    {
        // Find and delete all expired slugs for THIS user/election
        // Using forceDelete() for soft-deletable models to bypass unique constraint issues
        $deleted = $model::where('user_id', $user->id)
            ->where('election_id', $election->id)
            ->where('expires_at', '<=', Carbon::now())
            ->forceDelete();  // Hard delete to respect unique constraints

        if ($deleted > 0) {
            Log::info('Cleaned up expired voter slugs', [
                'user_id' => $user->id,
                'election_id' => $election->id,
                'count' => $deleted,
            ]);
        }

        return $deleted;
    }

    /**
     * ✅ NEW: Force restart of demo voting session
     *
     * BUSINESS: Demo users can restart voting unlimited times
     *
     * @param User $user
     * @param Election $election
     * @return DemoVoterSlug
     */
    public function restartDemoSlug(User $user, Election $election): DemoVoterSlug
    {
        // Delete all old slugs for THIS user/election
        $deleted = DemoVoterSlug::where('user_id', $user->id)
            ->where('election_id', $election->id)
            ->delete();

        Log::info('Demo voting session restarted', [
            'user_id' => $user->id,
            'election_id' => $election->id,
            'deleted_slugs' => $deleted,
        ]);

        // Create fresh slug
        return $this->createNewSlug($user, $election, DemoVoterSlug::class);
    }
}

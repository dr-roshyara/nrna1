<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use App\Exceptions\Voting\InvalidVoterSlugException;
use App\Exceptions\Voting\ExpiredVoterSlugException;

class ValidateVoterSlugWindow
{
    /**
     * VERIFICATION LEVEL 2: Expiration
     *
     * Checks:
     * 1. Has the slug expired?
     * 2. Is the election still active?
     */
    public function handle($request, Closure $next)
    {
        $voterSlug = $request->attributes->get('voter_slug');

        if (!$voterSlug) {
            Log::critical('❌ [ValidateVoterSlugWindow] No voter slug in request');
            throw new InvalidVoterSlugException('Voting session context missing', [
                'middleware' => 'ValidateVoterSlugWindow',
            ]);
        }

        Log::info('⏰ [ValidateVoterSlugWindow] Checking expiration', [
            'slug_id' => $voterSlug->id,
            'expires_at' => $voterSlug->expires_at,
            'current_time' => now(),
        ]);

        // CHECK 1: Has slug expired?
        if ($voterSlug->expires_at->isPast()) {
            Log::warning('❌ [ValidateVoterSlugWindow] Slug expired', [
                'slug_id' => $voterSlug->id,
                'expires_at' => $voterSlug->expires_at,
            ]);

            // Deactivate expired slug
            $voterSlug->update(['is_active' => false]);

            throw new ExpiredVoterSlugException('Your voting session has expired', [
                'slug_id' => $voterSlug->id,
                'expires_at' => $voterSlug->expires_at,
            ]);
        }

        // CHECK 2: Is election still active? (if election has date range)
        if ($voterSlug->election && $voterSlug->election->end_date) {
            if ($voterSlug->election->end_date->isPast()) {
                Log::warning('❌ [ValidateVoterSlugWindow] Election ended', [
                    'slug_id' => $voterSlug->id,
                    'election_id' => $voterSlug->election_id,
                    'election_end' => $voterSlug->election->end_date,
                ]);

                throw new ExpiredVoterSlugException('This election has ended', [
                    'slug_id' => $voterSlug->id,
                    'election_id' => $voterSlug->election_id,
                    'election_end' => $voterSlug->election->end_date,
                ]);
            }
        }

        // Calculate and store time remaining for UI
        $minutesRemaining = now()->diffInMinutes($voterSlug->expires_at);
        $request->attributes->set('slug_minutes_remaining', $minutesRemaining);

        Log::info('✅ [ValidateVoterSlugWindow] Slug valid', [
            'slug_id' => $voterSlug->id,
            'minutes_remaining' => $minutesRemaining,
        ]);

        return $next($request);
    }
}

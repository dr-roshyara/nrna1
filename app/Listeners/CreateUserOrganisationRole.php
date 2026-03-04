<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Create User Organisation Role Listener
 *
 * When a new user registers, they are assigned to the platform organisation (id=1).
 * This listener ensures a corresponding entry is created in the user_organisation_roles
 * pivot table so that the EnsureOrganisationMember middleware can validate membership.
 *
 * Without this pivot entry, users cannot access any organisation-scoped routes.
 */
class CreateUserOrganisationRole
{
    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        $user = $event->user;

        try {
            // User should already have organisation_id set by the User model boot method
            if (!$user->organisation_id) {
                Log::warning('CreateUserOrganisationRole: User has no organisation_id', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                ]);
                return;
            }

            // Check if pivot entry already exists (shouldn't, but be safe)
            $exists = DB::table('user_organisation_roles')
                ->where('user_id', $user->id)
                ->where('organisation_id', $user->organisation_id)
                ->exists();

            if ($exists) {
                Log::debug('CreateUserOrganisationRole: Pivot entry already exists', [
                    'user_id' => $user->id,
                    'organisation_id' => $user->organisation_id,
                ]);
                return;
            }

            // Create pivot entry with default role
            DB::table('user_organisation_roles')->insert([
                'user_id' => $user->id,
                'organisation_id' => $user->organisation_id,
                'role' => 'member', // Default role for newly registered users
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Log::info('CreateUserOrganisationRole: Pivot entry created', [
                'user_id' => $user->id,
                'organisation_id' => $user->organisation_id,
                'email' => $user->email,
            ]);

        } catch (\Exception $e) {
            Log::error('CreateUserOrganisationRole: Error creating pivot entry', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage(),
            ]);
        }
    }
}

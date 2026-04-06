<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Models\Organisation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class LoginFlowDebugTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function trace_first_time_user_login_flow()
    {
        // Create a user like registration would
        $user = User::create([
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'john@example.com',
            'region' => 'Bayern',
            'password' => bcrypt('Password@123'),
            'name' => 'John Doe',
            'email_verified_at' => now(),  // Verified
            'onboarded_at' => null,         // NOT onboarded yet
        ]);

        echo "\n=== USER CREATED ===\n";
        echo "User ID: {$user->id}\n";
        echo "Organisation ID: {$user->organisation_id}\n";
        echo "Onboarded: " . ($user->onboarded_at ? 'YES' : 'NO') . "\n";

        // Create pivot record like RegisterController does
        DB::table('user_organisation_roles')->insertOrIgnore([
            'user_id' => $user->id,
            'organisation_id' => $user->organisation_id,
            'role' => 'member',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        echo "\n=== PIVOT RECORD CREATED ===\n";
        $pivot = DB::table('user_organisation_roles')
            ->where('user_id', $user->id)
            ->first();
        echo "Pivot exists: " . ($pivot ? 'YES' : 'NO') . "\n";

        // Check what getDashboardRoles returns
        $dashboardRoles = $this->getDashboardRoles($user);
        echo "\n=== DASHBOARD ROLES ===\n";
        echo "Roles: " . json_encode($dashboardRoles) . "\n";
        echo "Count: " . count($dashboardRoles) . "\n";

        // Check if isFirstTimeUser returns true
        $isFirstTime = $this->isFirstTimeUser($user);
        echo "\n=== FIRST TIME USER CHECK ===\n";
        echo "Is first time: " . ($isFirstTime ? 'YES' : 'NO') . "\n";

        // Now try to log in
        echo "\n=== ATTEMPTING LOGIN ===\n";
        $loginResponse = $this->post('/login', [
            'email' => 'john@example.com',
            'password' => 'Password@123',
        ]);

        echo "Login status: " . $loginResponse->getStatusCode() . "\n";
        echo "Location header: " . $loginResponse->headers->get('Location') . "\n";

        // If we got redirected, follow it
        if ($loginResponse->status() === 302) {
            $location = $loginResponse->headers->get('Location');
            echo "\nFollowing redirect to: $location\n";

            // Extract path from full URL
            $path = parse_url($location, PHP_URL_PATH);
            echo "Path: $path\n";

            $followResponse = $this->actingAs($user)
                ->get($path);

            echo "Follow response status: " . $followResponse->getStatusCode() . "\n";
            if ($followResponse->status() >= 400) {
                echo "ERROR: " . $followResponse->content() . "\n";
            }
        }
    }

    private function isFirstTimeUser(User $user): bool
    {
        if ($user->onboarded_at !== null) {
            return false;
        }

        $nonPlatformOrgs = DB::table('user_organisation_roles')
            ->where('user_id', $user->id)
            ->where('organisation_id', '!=', 1)
            ->count();

        return $nonPlatformOrgs === 0;
    }

    private function getDashboardRoles(User $user): array
    {
        $roles = [];

        // Check for admin roles in non-platform organisations
        $adminRoleExists = DB::table('user_organisation_roles')
            ->where('user_id', $user->id)
            ->where('role', 'admin')
            ->whereNot(function ($query) {
                $query->where('organisation_id', 1);
            })
            ->exists();

        if ($adminRoleExists) {
            $roles[] = 'admin';
        }

        // Check commission memberships
        if (DB::table('election_commission_members')
            ->where('user_id', $user->id)
            ->exists()) {
            $roles[] = 'commission';
        }

        // Check voter status
        if ($user->is_voter) {
            $roles[] = 'voter';
        }

        return array_unique($roles);
    }
}

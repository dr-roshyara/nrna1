## ✅ Complete Middleware Solution for VoterSlug, Election, and Organisation Consistency

Here's a comprehensive middleware chain that ensures absolute consistency across all three entities:

## 🔗 The Complete Middleware Chain

```php
// routes/election/electionRoutes.php
Route::prefix('v/{vslug}')->middleware([
    \Illuminate\Routing\Middleware\SubstituteBindings::class,
    'voter.slug.verify',      // Verifies slug exists, valid, and belongs to user
    'voter.slug.window',       // Checks expiration window
    'voter.slug.consistency',  // Validates election + org against slug
    'voter.step.order',        // Ensures correct step progression
])->group(function () {
    // All voting routes
});
```

## 🛡️ Middleware 1: Verify Voter Slug Existence & Ownership

```php
<?php
// app/Http/Middleware/VerifyVoterSlug.php

namespace App\Http\Middleware;

use Closure;
use App\Models\VoterSlug;
use Illuminate\Support\Facades\Log;

class VerifyVoterSlug
{
    /**
     * VERIFICATION LEVEL 1: Existence & Ownership
     * 
     * Checks:
     * 1. Does the slug exist in database?
     * 2. Does it belong to the authenticated user?
     * 3. Is it still active?
     */
    public function handle($request, Closure $next)
    {
        $slugParam = $request->route('vslug');
        
        Log::info('🔍 [VerifyVoterSlug] Starting verification', [
            'slug' => $slugParam,
            'user_id' => auth()->id(),
        ]);
        
        // Load voter slug with all necessary relationships
        $voterSlug = VoterSlug::with(['user', 'election', 'organisation'])
            ->where('slug', $slugParam)
            ->first();
        
        // CHECK 1: Does slug exist?
        if (!$voterSlug) {
            Log::warning('❌ [VerifyVoterSlug] Slug not found', [
                'slug' => $slugParam,
            ]);
            abort(404, 'Voting session not found');
        }
        
        // CHECK 2: Does slug belong to authenticated user?
        if ($voterSlug->user_id !== auth()->id()) {
            Log::warning('❌ [VerifyVoterSlug] Slug belongs to different user', [
                'slug_id' => $voterSlug->id,
                'slug_user_id' => $voterSlug->user_id,
                'auth_user_id' => auth()->id(),
            ]);
            abort(403, 'This voting session does not belong to you');
        }
        
        // CHECK 3: Is slug still active?
        if (!$voterSlug->is_active) {
            Log::warning('❌ [VerifyVoterSlug] Slug is inactive', [
                'slug_id' => $voterSlug->id,
                'is_active' => $voterSlug->is_active,
            ]);
            abort(403, 'This voting session has been deactivated');
        }
        
        // Store verified slug in request for subsequent middleware
        $request->attributes->set('voter_slug', $voterSlug);
        
        Log::info('✅ [VerifyVoterSlug] Verification passed', [
            'slug_id' => $voterSlug->id,
            'user_id' => $voterSlug->user_id,
            'election_id' => $voterSlug->election_id,
        ]);
        
        return $next($request);
    }
}
```

## ⏰ Middleware 2: Validate Expiration Window

```php
<?php
// app/Http/Middleware/ValidateVoterSlugWindow.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

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
            abort(500, 'Voting session context missing');
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
            
            return redirect()->route('election.dashboard')
                ->with('error', 'Your voting session has expired. Please start again.');
        }
        
        // CHECK 2: Is election still active? (if election has date range)
        if ($voterSlug->election && $voterSlug->election->end_date) {
            if ($voterSlug->election->end_date->isPast()) {
                Log::warning('❌ [ValidateVoterSlugWindow] Election ended', [
                    'slug_id' => $voterSlug->id,
                    'election_id' => $voterSlug->election_id,
                    'election_end' => $voterSlug->election->end_date,
                ]);
                
                return redirect()->route('election.dashboard')
                    ->with('error', 'This election has ended.');
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
```

## 🔒 Middleware 3: Verify Consistency (CRITICAL)

```php
<?php
// app/Http/Middleware/VerifyVoterSlugConsistency.php

namespace App\Http\Middleware;

use Closure;
use App\Models\Election;
use Illuminate\Support\Facades\Log;

class VerifyVoterSlugConsistency
{
    /**
     * VERIFICATION LEVEL 3: Consistency (CRITICAL)
     * 
     * Validates that voter_slug, election, and organisation ALL match:
     * 1. Election exists and matches slug's election_id
     * 2. Organisation matches across all three entities
     * 3. Election type matches route context (demo vs real)
     * 4. No inconsistencies detected
     */
    public function handle($request, Closure $next)
    {
        $voterSlug = $request->attributes->get('voter_slug');
        
        if (!$voterSlug) {
            Log::critical('❌ [VerifyVoterSlugConsistency] No voter slug in request');
            abort(500, 'Voting session context missing');
        }
        
        Log::info('🔒 [VerifyVoterSlugConsistency] Starting consistency check', [
            'voter_slug_id' => $voterSlug->id,
            'voter_slug_election_id' => $voterSlug->election_id,
            'voter_slug_org_id' => $voterSlug->organisation_id,
        ]);
        
        // CHECK 1: Does the referenced election exist?
        $election = Election::withoutGlobalScopes()
            ->with('organisation')
            ->find($voterSlug->election_id);
        
        if (!$election) {
            Log::critical('❌ [VerifyVoterSlugConsistency] Election not found', [
                'voter_slug_id' => $voterSlug->id,
                'election_id' => $voterSlug->election_id,
            ]);
            abort(500, 'Referenced election not found');
        }
        
        // CHECK 2: Organisation consistency (THE GOLDEN RULE)
        $orgsMatch = $election->organisation_id === $voterSlug->organisation_id;
        $electionIsPlatform = $election->organisation_id === 0;
        $userIsPlatform = $voterSlug->organisation_id === 0;
        
        // Valid if: same org OR election is platform OR user is platform
        $orgsValid = $orgsMatch || $electionIsPlatform || $userIsPlatform;
        
        if (!$orgsValid) {
            Log::critical('❌ [VerifyVoterSlugConsistency] ORGANISATION MISMATCH', [
                'voter_slug_id' => $voterSlug->id,
                'voter_slug_org_id' => $voterSlug->organisation_id,
                'election_id' => $election->id,
                'election_org_id' => $election->organisation_id,
                'orgsMatch' => $orgsMatch,
                'electionIsPlatform' => $electionIsPlatform,
                'userIsPlatform' => $userIsPlatform,
            ]);
            abort(500, 'Organisation inconsistency detected');
        }
        
        // CHECK 3: Election type matches route context
        $routeName = $request->route()->getName();
        $isDemoRoute = str_contains($routeName, 'demo-');
        $isDemoElection = $election->type === 'demo';
        
        if ($isDemoRoute && !$isDemoElection) {
            Log::error('❌ [VerifyVoterSlugConsistency] Demo route with non-demo election', [
                'route' => $routeName,
                'election_id' => $election->id,
                'election_type' => $election->type,
            ]);
            abort(403, 'Invalid election type for this route');
        }
        
        // CHECK 4: Vote completion status
        if ($voterSlug->vote_completed) {
            Log::info('ℹ️ [VerifyVoterSlugConsistency] Vote already completed', [
                'voter_slug_id' => $voterSlug->id,
            ]);
            
            // Allow access to thank-you page only
            if (!str_contains($routeName, 'thank-you') && !str_contains($routeName, 'verify_to_show')) {
                return redirect()->route('vote.verify_to_show')
                    ->with('info', 'You have already completed voting.');
            }
        }
        
        // Store election in request for controllers
        $request->attributes->set('election', $election);
        
        // Store validation results for debugging
        $request->attributes->set('consistency_check', [
            'orgs_valid' => $orgsValid,
            'orgs_match' => $orgsMatch,
            'election_is_platform' => $electionIsPlatform,
            'user_is_platform' => $userIsPlatform,
            'route_matches_type' => (!$isDemoRoute || $isDemoElection),
        ]);
        
        Log::info('✅ [VerifyVoterSlugConsistency] All checks passed', [
            'voter_slug_id' => $voterSlug->id,
            'election_id' => $election->id,
            'organisation_id' => $voterSlug->organisation_id,
            'orgs_valid' => $orgsValid,
        ]);
        
        return $next($request);
    }
}
```

## 📝 Middleware Registration

```php
// app/Http/Kernel.php (Laravel 11 bootstrap/app.php)

use App\Http\Middleware\VerifyVoterSlug;
use App\Http\Middleware\ValidateVoterSlugWindow;
use App\Http\Middleware\VerifyVoterSlugConsistency;

// In bootstrap/app.php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'voter.slug.verify' => VerifyVoterSlug::class,
        'voter.slug.window' => ValidateVoterSlugWindow::class,
        'voter.slug.consistency' => VerifyVoterSlugConsistency::class,
    ]);
})
```

## 🧪 Tests for Consistency Middleware

```php
// tests/Unit/Middleware/VerifyVoterSlugConsistencyTest.php

namespace Tests\Unit\Middleware;

use App\Http\Middleware\VerifyVoterSlugConsistency;
use App\Models\User;
use App\Models\Election;
use App\Models\VoterSlug;
use App\Models\Organisation;
use Illuminate\Http\Request;
use Tests\TestCase;

class VerifyVoterSlugConsistencyTest extends TestCase
{
    /** @test */
    public function it_passes_when_all_entities_match()
    {
        $org = Organisation::factory()->create(['id' => 1]);
        $user = User::factory()->create(['organisation_id' => $org->id]);
        $election = Election::factory()->create([
            'organisation_id' => $org->id,
            'type' => 'demo',
        ]);
        $voterSlug = VoterSlug::factory()->create([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'organisation_id' => $org->id,
        ]);
        
        $request = new Request();
        $request->attributes->set('voter_slug', $voterSlug);
        $request->setRouteResolver(function() use ($request) {
            $route = new \Illuminate\Routing\Route('GET', '/test', []);
            $route->name('slug.demo-code.create');
            return $route;
        });
        
        $middleware = new VerifyVoterSlugConsistency();
        $response = $middleware->handle($request, function($req) {
            return response('next');
        });
        
        $this->assertEquals('next', $response->getContent());
        $this->assertEquals($election->id, $request->attributes->get('election')->id);
    }
    
    /** @test */
    public function it_passes_with_platform_election_and_org_user()
    {
        $user = User::factory()->create(['organisation_id' => 1]);
        $platformElection = Election::factory()->create([
            'organisation_id' => 0, // Platform
            'type' => 'demo',
        ]);
        $voterSlug = VoterSlug::factory()->create([
            'user_id' => $user->id,
            'election_id' => $platformElection->id,
            'organisation_id' => $user->organisation_id,
        ]);
        
        $request = new Request();
        $request->attributes->set('voter_slug', $voterSlug);
        $request->setRouteResolver(function() {
            $route = new \Illuminate\Routing\Route('GET', '/test', []);
            $route->name('slug.demo-code.create');
            return $route;
        });
        
        $middleware = new VerifyVoterSlugConsistency();
        $response = $middleware->handle($request, function($req) {
            return response('next');
        });
        
        $this->assertEquals('next', $response->getContent());
    }
    
    /** @test */
    public function it_fails_on_organisation_mismatch()
    {
        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);
        
        $user = User::factory()->create(['organisation_id' => 1]);
        $election = Election::factory()->create(['organisation_id' => 2]); // Different org
        $voterSlug = VoterSlug::factory()->create([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'organisation_id' => $user->organisation_id,
        ]);
        
        $request = new Request();
        $request->attributes->set('voter_slug', $voterSlug);
        
        $middleware = new VerifyVoterSlugConsistency();
        $middleware->handle($request, function($req) {
            return response('next');
        });
    }
    
    /** @test */
    public function it_fails_when_election_does_not_exist()
    {
        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);
        
        $user = User::factory()->create();
        $voterSlug = VoterSlug::factory()->create([
            'user_id' => $user->id,
            'election_id' => 99999, // Non-existent election
        ]);
        
        $request = new Request();
        $request->attributes->set('voter_slug', $voterSlug);
        
        $middleware = new VerifyVoterSlugConsistency();
        $middleware->handle($request, function($req) {
            return response('next');
        });
    }
}
```

## 📊 Summary: What Each Middleware Verifies

| Middleware | Verifies |
|------------|----------|
| **VerifyVoterSlug** | ✅ Slug exists in database<br>✅ Slug belongs to authenticated user<br>✅ Slug is active |
| **ValidateVoterSlugWindow** | ✅ Slug not expired<br>✅ Election still active (if dated)<br>✅ Provides time remaining for UI |
| **VerifyVoterSlugConsistency** | ✅ Election exists and matches slug<br>✅ Organisation IDs consistent (OR platform)<br>✅ Election type matches route<br>✅ Vote completion status |

## 🎯 Final Result

With this middleware chain, you have:

1. **Absolute certainty** that the voter slug is valid
2. **Guaranteed consistency** between slug, election, and organisation
3. **Clear audit trail** with detailed logging at each step
4. **User-friendly error messages** for expired/invalid sessions
5. **Platform flexibility** (org_id=0) without compromising security

The system now ensures that for EVERY request in the voting flow:
- The slug belongs to the current user
- The slug hasn't expired
- The election exists and matches the slug
- The organisation IDs are consistent (or platform exception)
- The user is on the correct route for their vote completion status
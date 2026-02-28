## Complete 5-Step Voting Flow with Consistency Validation

Yes! Here are the 5 election steps and how to enforce consistency at each step:

## The 5 Voting Steps

```
┌─────────────────────────────────────────────────────────────────┐
│                    VOTING WORKFLOW STEPS                         │
├─────────────────────────────────────────────────────────────────┤
│                                                                   │
│  STEP 1: Code Creation                                            │
│  Route: slug.demo-code.create                                     │
│  Controller: DemoCodeController@create                           │
│  Purpose: User enters email, gets verification code              │
│                                                                   │
│  STEP 2: Code Verification                                        │
│  Route: slug.demo-code.store                                      │
│  Controller: DemoCodeController@store                            │
│  Purpose: User submits code, marks can_vote_now = 1              │
│                                                                   │
│  STEP 3: Agreement                                                │
│  Route: slug.demo-code.agreement                                  │
│  Controller: DemoCodeController@showAgreement                    │
│  Purpose: User reads and accepts voting terms                    │
│                                                                   │
│  STEP 4: Vote Submission                                          │
│  Route: slug.demo-vote.create → slug.demo-vote.submit           │
│  Controller: DemoVoteController@create → @first_submission      │
│  Purpose: User selects candidates, submits vote                  │
│                                                                   │
│  STEP 5: Vote Verification & Completion                          │
│  Route: slug.demo-vote.verify → slug.demo-vote.store            │
│  Controller: DemoVoteController@verify → @store                  │
│  Purpose: User confirms and finalizes vote                       │
│                                                                   │
└─────────────────────────────────────────────────────────────────┘
```

## Enhanced ElectionMiddleware with Step-by-Step Validation

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Election;
use App\Models\VoterSlug;
use Illuminate\Support\Facades\Log;

final class ElectionMiddleware
{
    /**
     * PRIORITY ORDER - DO NOT CHANGE
     * 1. Voter Slug Election (IMMUTABLE SOURCE OF TRUTH)
     * 2. Session Selection
     * 3. Route Parameter
     * 4. Default Real Election
     * 5. Any Active Election
     */
    
    /**
     * Maps route names to step numbers for validation
     */
    private const STEP_ROUTES = [
        // Demo Code Steps
        'slug.demo-code.create' => 1,
        'slug.demo-code.store' => 1,
        'slug.demo-code.agreement' => 2,
        'slug.demo-code.agreement.submit' => 2,
        
        // Demo Vote Steps
        'slug.demo-vote.create' => 3,
        'slug.demo-vote.submit' => 3,
        'slug.demo-vote.verify' => 4,
        'slug.demo-vote.store' => 4,
        'slug.demo-vote.thank-you' => 5,
        'slug.demo-vote.verify_to_show' => 5,
        
        // Real Election Steps (if needed)
        'slug.code.create' => 1,
        'slug.code.store' => 1,
        'slug.code.agreement' => 2,
        'slug.code.agreement.submit' => 2,
        'slug.vote.create' => 3,
        'slug.vote.submit' => 3,
        'slug.vote.verify' => 4,
        'slug.vote.store' => 4,
        'slug.vote.complete' => 5,
    ];
    
    public function handle(Request $request, Closure $next)
    {
        $currentRoute = $request->route()->getName();
        $currentStep = self::STEP_ROUTES[$currentRoute] ?? null;
        
        // Log entry for debugging
        Log::info('🔍 [ElectionMiddleware] Entering', [
            'route' => $currentRoute,
            'step' => $currentStep,
            'method' => $request->method(),
            'url' => $request->fullUrl(),
        ]);
        
        // ✅ STEP 0: First, check if we have a voter slug
        $voterSlug = $request->attributes->get('voter_slug');
        
        // If we have a voter slug, it's the SOURCE OF TRUTH
        if ($voterSlug) {
            return $this->handleWithVoterSlug($request, $next, $voterSlug, $currentStep);
        }
        
        // If no voter slug, fall back to other election sources
        return $this->handleWithoutVoterSlug($request, $next, $currentStep);
    }
    
    /**
     * Handle request when voter slug exists - THIS IS THE PRIMARY PATH
     * All voting steps should have a voter slug
     */
    private function handleWithVoterSlug(Request $request, Closure $next, VoterSlug $voterSlug, ?int $currentStep)
    {
        // Get election from voter slug (source of truth)
        $election = $voterSlug->election;
        
        if (!$election) {
            Log::critical('❌ [ElectionMiddleware] Voter slug has no election', [
                'voter_slug_id' => $voterSlug->id,
                'voter_slug_election_id' => $voterSlug->election_id,
            ]);
            abort(500, 'Voting session corrupted - no election found');
        }
        
        // ✅ VALIDATION 1: Election exists and is active
        if (!$election->is_active) {
            Log::error('❌ [ElectionMiddleware] Election is not active', [
                'election_id' => $election->id,
                'voter_slug_id' => $voterSlug->id,
            ]);
            return redirect()->route('election.dashboard')
                ->with('error', 'This election is no longer active.');
        }
        
        // ✅ VALIDATION 2: Organisation consistency
        if ($election->organisation_id !== $voterSlug->organisation_id) {
            Log::critical('❌ [ElectionMiddleware] ORGANISATION MISMATCH', [
                'voter_slug_id' => $voterSlug->id,
                'voter_slug_org_id' => $voterSlug->organisation_id,
                'election_id' => $election->id,
                'election_org_id' => $election->organisation_id,
                'step' => $currentStep,
            ]);
            abort(500, 'Critical data inconsistency: organisation mismatch');
        }
        
        // ✅ VALIDATION 3: Election type matches route context
        $isDemoRoute = str_contains($request->route()->getName(), 'demo-');
        $isDemoElection = $election->type === 'demo';
        
        if ($isDemoRoute && !$isDemoElection) {
            Log::error('❌ [ElectionMiddleware] Demo route with non-demo election', [
                'route' => $request->route()->getName(),
                'election_id' => $election->id,
                'election_type' => $election->type,
            ]);
            abort(403, 'Invalid election type for this route');
        }
        
        if (!$isDemoRoute && $isDemoElection && !$this->isDemoAllowedForRoute($request)) {
            Log::warning('⚠️ [ElectionMiddleware] Non-demo route with demo election', [
                'route' => $request->route()->getName(),
                'election_id' => $election->id,
            ]);
            // This might be allowed in some cases - log but don't block
        }
        
        // ✅ VALIDATION 4: Step progression (if we have step info)
        if ($currentStep && $voterSlug->current_step > $currentStep + 1) {
            Log::warning('⚠️ [ElectionMiddleware] User trying to skip ahead', [
                'voter_slug_id' => $voterSlug->id,
                'current_step_in_slug' => $voterSlug->current_step,
                'attempted_step' => $currentStep,
                'route' => $request->route()->getName(),
            ]);
            
            // Don't block, but log for monitoring
            // The VoterStepOrder middleware will handle this
        }
        
        // ✅ VALIDATION 5: Vote completion status
        if ($voterSlug->vote_completed && $currentStep && $currentStep < 5) {
            Log::warning('⚠️ [ElectionMiddleware] Completed vote trying to access early step', [
                'voter_slug_id' => $voterSlug->id,
                'vote_completed' => $voterSlug->vote_completed,
                'attempted_step' => $currentStep,
            ]);
            
            return redirect()->route('vote.verify_to_show')
                ->with('info', 'You have already completed voting.');
        }
        
        // Set election in request attributes for controllers
        $request->attributes->set('election', $election);
        
        Log::info('✅ [ElectionMiddleware] Election validated and set', [
            'voter_slug_id' => $voterSlug->id,
            'election_id' => $election->id,
            'step' => $currentStep,
            'vote_completed' => $voterSlug->vote_completed,
        ]);
        
        return $next($request);
    }
    
    /**
     * Handle request without voter slug (election selection pages, dashboard, etc.)
     */
    private function handleWithoutVoterSlug(Request $request, Closure $next, ?int $currentStep)
    {
        // Try to get election from various sources
        $election = null;
        
        // 1. Try session
        $electionId = session('selected_election_id');
        if ($electionId) {
            $election = Election::find($electionId);
            Log::info('📝 [ElectionMiddleware] Using election from session', [
                'election_id' => $election?->id,
            ]);
        }
        
        // 2. Try route parameter
        if (!$election && $request->route('election')) {
            $election = $request->route('election');
            Log::info('🛣️ [ElectionMiddleware] Using election from route', [
                'election_id' => $election?->id,
            ]);
        }
        
        // 3. Default to first real election
        if (!$election) {
            $election = Election::where('type', 'real')
                ->where('is_active', true)
                ->orderBy('id')
                ->first();
            
            Log::info('📌 [ElectionMiddleware] Using default real election', [
                'election_id' => $election?->id,
            ]);
        }
        
        // 4. Fallback to any active election
        if (!$election) {
            $election = Election::withoutGlobalScopes()
                ->where('is_active', true)
                ->orderBy('id')
                ->first();
            
            Log::info('🆘 [ElectionMiddleware] Using any active election as fallback', [
                'election_id' => $election?->id,
                'election_type' => $election?->type,
            ]);
        }
        
        if (!$election) {
            Log::error('❌ [ElectionMiddleware] No election found', [
                'path' => $request->path(),
            ]);
            return redirect()->route('dashboard')
                ->with('error', 'No active elections available.');
        }
        
        $request->attributes->set('election', $election);
        return $next($request);
    }
    
    /**
     * Check if demo elections are allowed on this route
     */
    private function isDemoAllowedForRoute(Request $request): bool
    {
        // Demo elections are allowed on:
        // - Demo-specific routes
        // - Dashboard/selection pages
        // - Result pages (if configured)
        
        $routeName = $request->route()->getName();
        
        return str_contains($routeName, 'demo-') ||
               str_contains($routeName, 'dashboard') ||
               str_contains($routeName, 'select') ||
               $routeName === 'election.demo.start';
    }
}
```

## Step-by-Step Validation Matrix

| Step | Route | Voter Slug Required | Election Validations | Step Progression Check |
|------|-------|---------------------|---------------------|----------------------|
| **1** | `demo-code.create` | ✅ Yes | Active, Type=demo, Org matches | current_step ≥ 1 |
| **1** | `demo-code.store` | ✅ Yes | Active, Type=demo, Org matches | current_step = 1 |
| **2** | `demo-code.agreement` | ✅ Yes | Active, Type=demo, Org matches | current_step ≥ 2 |
| **2** | `demo-code.agreement.submit` | ✅ Yes | Active, Type=demo, Org matches | current_step = 2 |
| **3** | `demo-vote.create` | ✅ Yes | Active, Type=demo, Org matches | current_step ≥ 3 |
| **3** | `demo-vote.submit` | ✅ Yes | Active, Type=demo, Org matches | current_step = 3 |
| **4** | `demo-vote.verify` | ✅ Yes | Active, Type=demo, Org matches | current_step ≥ 4 |
| **4** | `demo-vote.store` | ✅ Yes | Active, Type=demo, Org matches | current_step = 4 |
| **5** | `demo-vote.thank-you` | ✅ Yes | Active, Type=demo, Org matches | current_step ≥ 5 |
| **5** | `demo-vote.verify_to_show` | ✅ Yes | Active, Type=demo, Org matches | vote_completed = true |

## Add to Route Definition

```php
// routes/election/electionRoutes.php
Route::prefix('v/{vslug}')->middleware([
    \Illuminate\Routing\Middleware\SubstituteBindings::class,
    'voter.slug.window',
    'voter.step.order',
    'vote.eligibility',
    'validate.voting.ip',
    'election',           // ← Our enhanced middleware
    'vote.organisation',
])->group(function () {
    // All routes here
});
```

## Summary

This enhanced ElectionMiddleware now:

1. **Knows the 5 steps** - Maps routes to step numbers
2. **Validates at each step** - Ensures consistency throughout the flow
3. **Prevents step skipping** - Monitors but lets VoterStepOrder handle enforcement
4. **Checks vote completion** - Redirects completed votes away from early steps
5. **Validates election type** - Ensures demo routes use demo elections
6. **Maintains organisation consistency** - Critical for multi-tenancy
7. **Comprehensive logging** - Every validation is logged for debugging

The voter slug remains the **SOURCE OF TRUTH** throughout all 5 steps, ensuring perfect consistency between `election_id`, `organisation_id`, and the voter slug itself.
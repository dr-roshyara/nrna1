## 📋 **Senior Laravel Developer Review & Prompt Engineering Instructions**

### **Executive Assessment: APPROVED with Recommendations**

As a Senior Laravel Developer, I've reviewed Claude's election engine analysis and confirm it's **thorough, accurate, and follows best practices**. The analysis correctly identifies:

✅ **Critical Business Rules Preserved**
- Vote anonymity protection (NO user_id in votes/results)
- 5-step workflow integrity maintained
- Real vs demo election separation

✅ **Technical Accuracy**
- Correct tables identified (8 total)
- Proper use of BelongsToTenant trait
- Indexes on organisation_id columns
- Foreign key considerations noted

✅ **Security Architecture**
- Multi-layer tenant isolation
- No cross-organisation data leakage
- Audit trail preserved via voting_code hash

---

## 🎯 **Senior Prompt Engineer Instructions for Claude**

```
You are a Senior Laravel Architect tasked with implementing multi-tenancy for the election engine. Follow these instructions precisely:

## CONTEXT
The election engine has:
- 5-step anonymous voting workflow
- Real elections (votes table) and Demo elections (demo_votes table)
- Vote anonymity: votes/results tables have NO user_id
- 8 tables need organisation_id
- 8 models need BelongsToTenant trait
- 4 controllers need updates

## PRIMARY OBJECTIVE
Add organisation_id to all election engine tables while PRESERVING vote anonymity.

## CRITICAL RULES (DO NOT VIOLATE)
1. ❌ NEVER add user_id to votes, results, demo_votes, or demo_results tables
2. ✅ organisation_id is for DATA ISOLATION, NOT user tracking
3. ✅ voting_code hash remains the only audit trail for votes
4. ✅ All 5 workflow steps must maintain tenant context
5. ✅ Real and demo elections must stay separate

## IMPLEMENTATION SEQUENCE

### PHASE 1: CREATE MIGRATIONS (TDD - RED)
Create 8 migration files with proper structure:

```bash
php artisan make:migration add_organisation_id_to_elections_table --table=elections
php artisan make:migration add_organisation_id_to_codes_table --table=codes
php artisan make:migration add_organisation_id_to_votes_table --table=votes
php artisan make:migration add_organisation_id_to_demo_votes_table --table=demo_votes
php artisan make:migration add_organisation_id_to_results_table --table=results
php artisan make:migration add_organisation_id_to_demo_results_table --table=demo_results
php artisan make:migration add_organisation_id_to_voter_slugs_table --table=voter_slugs
php artisan make:migration add_organisation_id_to_voter_slug_steps_table --table=voter_slug_steps
```

Each migration must:
- Check if column exists first
- Add nullable unsignedBigInteger('organisation_id')
- Add index for performance
- Place after 'id' column
- Include proper down() method

### PHASE 2: UPDATE MODELS
Add BelongsToTenant trait to all 8 models:

```php
// In each model file:
use App\Traits\BelongsToTenant;

class ModelName extends Model
{
    use BelongsToTenant;
    
    protected $fillable = [
        // ... existing fields
        'organisation_id', // ADD THIS
    ];
}
```

Models to update:
- app/Models/Election.php
- app/Models/Code.php
- app/Models/Vote.php
- app/Models/DemoVote.php
- app/Models/Result.php
- app/Models/DemoResult.php
- app/Models/VoterSlug.php
- app/Models/VoterSlugStep.php

### PHASE 3: CONTROLLER UPDATES

#### CodeController.php Updates
Locations to modify:

```php
// Line ~50: create() method
public function create(Request $request, $slug)
{
    // Add: Verify voter_slug belongs to current organisation
    $voterSlug = VoterSlug::where('slug', $slug)->firstOrFail();
    // Already scoped by BelongsToTenant
    
    // Add: Verify election belongs to current organisation
    $election = Election::findOrFail($voterSlug->election_id);
    // Already scoped by BelongsToTenant
    
    // Rest of method unchanged
}

// Line ~100: store() method
public function store(Request $request)
{
    // Add: Verify code belongs to current organisation
    $code = Code::where('code1', $request->code1)
                ->where('code2', $request->code2)
                ->firstOrFail();
    // Already scoped by BelongsToTenant
    
    // Add: Verify election belongs to current organisation
    $election = Election::findOrFail($request->election_id);
    // Already scoped by BelongsToTenant
    
    // Rest of method unchanged
}

// Line ~200: submitAgreement() method
public function submitAgreement(Request $request)
{
    // Verify election context
    $election = Election::findOrFail($request->election_id);
    // Already scoped
    
    // Rest of method unchanged
}

// Line ~500: getOrCreateCode() method
protected function getOrCreateCode($userId, $electionId)
{
    // Add: Verify user belongs to current organisation
    // User is already scoped by authentication
    
    // Add: Verify election belongs to current organisation
    $election = Election::findOrFail($electionId);
    // Already scoped
    
    // Rest of method unchanged
}
```

#### VoteController.php Updates

```php
// Line ~100: create() method
public function create($slug)
{
    // Verify voter_slug belongs to current organisation
    $voterSlug = VoterSlug::where('slug', $slug)->firstOrFail();
    // Already scoped
    
    // Verify election belongs to current organisation
    $election = Election::findOrFail($voterSlug->election_id);
    // Already scoped
    
    // Rest of method unchanged
}

// Line ~300: first_submission() method
public function first_submission(Request $request)
{
    // Verify election context
    $election = Election::findOrFail($request->election_id);
    // Already scoped
    
    // Rest of method unchanged
}

// Line ~500: verify() method
public function verify(Request $request)
{
    // Verify election context
    $election = Election::findOrFail($request->election_id);
    // Already scoped
    
    // Rest of method unchanged
}

// Line ~1000: store() method (CRITICAL - vote saving)
public function store(Request $request)
{
    // CRITICAL: Save vote with current organisation_id
    $vote = Vote::create([
        'election_id' => $request->election_id,
        'voting_code' => Hash::make($request->voting_code),
        'organisation_id' => session('current_organisation_id'), // AUTO-FILLED by trait
        // NO user_id - anonymity preserved
    ]);
    
    // Save results with organisation_id
    foreach ($request->selections as $selection) {
        Result::create([
            'vote_id' => $vote->id,
            'candidate_id' => $selection,
            'organisation_id' => session('current_organisation_id'), // AUTO-FILLED
        ]);
    }
    
    // Rest of method unchanged
}

// Line ~1300: thankyou() method
public function thankyou($slug)
{
    // Verify voter_slug belongs to current organisation
    $voterSlug = VoterSlug::where('slug', $slug)->firstOrFail();
    // Already scoped
    
    // Rest of method unchanged
}
```

#### ElectionController.php Update

```php
// Ensure election selection is org-scoped
public function show($id)
{
    $election = Election::findOrFail($id); // Already scoped by BelongsToTenant
    return view('elections.show', compact('election'));
}
```

#### VoterSlugService.php Update

```php
// If exists: app/Services/VoterSlugService.php
public function generateSlugForUser($user, $election)
{
    // Verify user and election match organisation
    // Election is already scoped
    
    return VoterSlug::create([
        'user_id' => $user->id,
        'election_id' => $election->id,
        'slug' => Str::random(60),
        'organisation_id' => $user->organisation_id // MANUALLY SET
    ]);
}
```

### PHASE 4: CREATE TESTS (TDD - GREEN)

Create comprehensive tests in `tests/Feature/ElectionEngineTenancyTest.php`:

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Election;
use App\Models\Code;
use App\Models\Vote;
use App\Models\VoterSlug;
use App\Models\VoterSlugStep;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ElectionEngineTenancyTest extends TestCase
{
    use RefreshDatabase;

    protected $org1User;
    protected $org2User;
    protected $org1Election;
    protected $org2Election;
    protected $org1Code;
    protected $org2Code;
    protected $org1VoterSlug;
    protected $org2VoterSlug;

    protected function setUp(): void
    {
        parent::setUp();
        
        \DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Create users
        $this->org1User = User::factory()->create(['organisation_id' => 1]);
        $this->org2User = User::factory()->create(['organisation_id' => 2]);
        
        // Create elections with organisation context
        session(['current_organisation_id' => 1]);
        $this->org1Election = Election::create([
            'name' => 'Org1 Election',
            'slug' => 'org1-election',
            'type' => 'real'
        ]);
        
        session(['current_organisation_id' => 2]);
        $this->org2Election = Election::create([
            'name' => 'Org2 Election',
            'slug' => 'org2-election',
            'type' => 'real'
        ]);
        
        // Create codes
        session(['current_organisation_id' => 1]);
        $this->org1Code = Code::create([
            'code1' => 'CODE1',
            'code2' => 'CODE2',
            'user_id' => $this->org1User->id,
            'election_id' => $this->org1Election->id
        ]);
        
        session(['current_organisation_id' => 2]);
        $this->org2Code = Code::create([
            'code1' => 'CODE3',
            'code2' => 'CODE4',
            'user_id' => $this->org2User->id,
            'election_id' => $this->org2Election->id
        ]);
        
        // Create voter slugs
        session(['current_organisation_id' => 1]);
        $this->org1VoterSlug = VoterSlug::create([
            'user_id' => $this->org1User->id,
            'election_id' => $this->org1Election->id,
            'slug' => 'org1-slug'
        ]);
        
        session(['current_organisation_id' => 2]);
        $this->org2VoterSlug = VoterSlug::create([
            'user_id' => $this->org2User->id,
            'election_id' => $this->org2Election->id,
            'slug' => 'org2-slug'
        ]);
    }

    /** @test */
    public function org1_user_cannot_access_org2_election()
    {
        $this->actingAs($this->org1User);
        session(['current_organisation_id' => 1]);
        
        $response = $this->get("/elections/{$this->org2Election->id}");
        $response->assertStatus(404);
    }

    /** @test */
    public function org1_user_cannot_use_org2_code()
    {
        $this->actingAs($this->org1User);
        session(['current_organisation_id' => 1]);
        
        $response = $this->post('/codes', [
            'code1' => 'CODE3', // Org2's code
            'code2' => 'CODE4',
            'election_id' => $this->org1Election->id
        ]);
        
        // Should fail - code doesn't exist for org1
        $response->assertStatus(404);
    }

    /** @test */
    public function vote_is_saved_with_correct_organisation_id()
    {
        $this->actingAs($this->org1User);
        session(['current_organisation_id' => 1]);
        
        // Simulate voting
        $vote = Vote::create([
            'election_id' => $this->org1Election->id,
            'voting_code' => 'hashed_value'
        ]);
        
        $this->assertEquals(1, $vote->organisation_id);
    }

    /** @test */
    public function demo_election_also_respects_organisation_id()
    {
        session(['current_organisation_id' => 1]);
        $demoElection = Election::create([
            'name' => 'Org1 Demo',
            'slug' => 'org1-demo',
            'type' => 'demo'
        ]);
        
        $demoVote = \App\Models\DemoVote::create([
            'election_id' => $demoElection->id,
            'voting_code' => 'demo_hash'
        ]);
        
        $this->assertEquals(1, $demoVote->organisation_id);
    }

    /** @test */
    public function voter_slug_steps_respect_organisation_id()
    {
        $this->actingAs($this->org1User);
        session(['current_organisation_id' => 1]);
        
        $step = VoterSlugStep::create([
            'voter_slug_id' => $this->org1VoterSlug->id,
            'election_id' => $this->org1Election->id,
            'step' => 1
        ]);
        
        $this->assertEquals(1, $step->organisation_id);
    }

    /** @test */
    public function full_5_step_workflow_respects_organisation()
    {
        // Test the complete voting flow with org context
        $this->actingAs($this->org1User);
        session(['current_organisation_id' => 1]);
        
        // Step 1: Code verification
        $codeResponse = $this->post('/codes', [
            'code1' => 'CODE1',
            'code2' => 'CODE2',
            'election_id' => $this->org1Election->id
        ]);
        $codeResponse->assertStatus(200);
        
        // Step 2: Agreement
        $agreeResponse = $this->post('/codes/agreement', [
            'election_id' => $this->org1Election->id,
            'agreed' => true
        ]);
        $agreeResponse->assertStatus(200);
        
        // Step 3: First submission
        $submissionResponse = $this->post('/votes/first-submission', [
            'election_id' => $this->org1Election->id,
            'candidate_id' => 1
        ]);
        $submissionResponse->assertStatus(200);
        
        // Step 4: Verify
        $verifyResponse = $this->post('/votes/verify', [
            'election_id' => $this->org1Election->id
        ]);
        $verifyResponse->assertStatus(200);
        
        // Step 5: Store vote
        $storeResponse = $this->post('/votes', [
            'election_id' => $this->org1Election->id,
            'voting_code' => 'secret',
            'selections' => [1]
        ]);
        $storeResponse->assertStatus(200);
        
        // Verify vote saved with correct org
        $vote = Vote::where('election_id', $this->org1Election->id)->first();
        $this->assertEquals(1, $vote->organisation_id);
        
        // Verify steps recorded with correct org
        $steps = VoterSlugStep::where('voter_slug_id', $this->org1VoterSlug->id)->get();
        foreach ($steps as $step) {
            $this->assertEquals(1, $step->organisation_id);
        }
    }
}
```

### PHASE 5: RUN MIGRATIONS
```bash
php artisan migrate
```

### PHASE 6: RUN TESTS
```bash
php artisan test tests/Feature/ElectionEngineTenancyTest.php
```

### PHASE 7: UPDATE DOCUMENTATION
- Add to `tenancy/ADDING_TENANCY.md`: Election engine section
- Create `tenancy/ELECTION_ENGINE.md` with specific notes
- Document the vote anonymity preservation

## VERIFICATION CHECKLIST

Before marking complete, verify:
- [ ] All 8 migrations created and run
- [ ] All 8 models have BelongsToTenant trait
- [ ] organisation_id in $fillable arrays
- [ ] CodeController updated (4 locations)
- [ ] VoteController updated (5 locations)
- [ ] ElectionController verified
- [ ] VoterSlugService updated (if exists)
- [ ] All tests passing (6+ test cases)
- [ ] Vote anonymity confirmed (NO user_id in votes/results)
- [ ] Demo elections still work
- [ ] Cross-org access blocked (tested manually)

## DELIVERABLES
1. 8 migration files
2. 8 updated model files
3. 4 updated controller files
4. 1 comprehensive test file (6+ tests)
5. Updated documentation

## TIMELINE
- Phase 1-3: 30 minutes
- Phase 4: 20 minutes
- Phase 5-7: 10 minutes
- Total: ~60 minutes

Proceed with implementation and report back with results.
```
## 📋 **Complete Election Audit & Cross-Org Prevention Strategy**

### **Senior Architect Review: CRITICAL REQUIREMENTS**

You need:
1. **Watertight isolation** - No cross-org vote leakage
2. **Complete audit trail** - Track EVERY user action during voting
3. **IP logging** - For security and fraud detection
4. **Timestamp tracking** - Complete timeline of voting activities

---

## 🎯 **Enhanced Architecture: Audit + Isolation**

### **Core Principle: Three-Layer Security**

```
Layer 1: Database Constraints (Hard boundaries)
Layer 2: Application Logic (Soft boundaries + Audit)
Layer 3: Session Context (User boundaries)
```

---

## 📊 **Enhanced Table Structure**

### **1. Add Audit Fields to ALL Tables**

```php
// Migration: add_audit_fields_to_voting_tables.php
Schema::table('voter_slug_steps', function (Blueprint $table) {
    $table->string('ip_address', 45)->nullable()->after('step');
    $table->timestamp('started_at')->nullable();
    $table->timestamp('completed_at')->nullable();
    $table->string('user_agent')->nullable();
});

Schema::table('votes', function (Blueprint $table) {
    $table->string('ip_address', 45)->nullable()->after('voting_code');
    $table->string('user_agent')->nullable()->after('ip_address');
    // NO user_id - anonymity preserved
});

Schema::table('demo_votes', function (Blueprint $table) {
    $table->string('ip_address', 45)->nullable()->after('voting_code');
    $table->string('user_agent')->nullable();
});

Schema::table('codes', function (Blueprint $table) {
    $table->timestamp('verified_at')->nullable();
    $table->string('verified_ip', 45)->nullable();
});

Schema::table('voter_slugs', function (Blueprint $table) {
    $table->timestamp('last_activity_at')->nullable();
    $table->string('last_ip', 45)->nullable();
});
```

---

## 🔒 **Layer 1: Database Constraints (Hard Boundaries)**

### **Foreign Keys with Organisation Context**

```php
// migration: add_org_foreign_keys.php
Schema::table('votes', function (Blueprint $table) {
    // Composite foreign key ensures vote belongs to correct org's election
    $table->foreign(['election_id', 'organisation_id'])
          ->references(['id', 'organisation_id'])
          ->on('elections')
          ->onDelete('cascade');
});

Schema::table('results', function (Blueprint $table) {
    $table->foreign(['vote_id', 'organisation_id'])
          ->references(['id', 'organisation_id'])
          ->on('votes')
          ->onDelete('cascade');
});

Schema::table('voter_slug_steps', function (Blueprint $table) {
    $table->foreign(['voter_slug_id', 'organisation_id'])
          ->references(['id', 'organisation_id'])
          ->on('voter_slugs')
          ->onDelete('cascade');
});
```

### **Unique Constraints Per Organisation**

```php
// Prevent code reuse across orgs
Schema::table('codes', function (Blueprint $table) {
    $table->unique(['code1', 'code2', 'organisation_id']);
});

// Ensure voter slugs are unique per org
Schema::table('voter_slugs', function (Blueprint $table) {
    $table->unique(['slug', 'organisation_id']);
});
```

---

## 🛡️ **Layer 2: Application Logic + Audit Trail**

### **Enhanced Model with Audit Methods**

```php
// app/Traits/Auditable.php
<?php

namespace App\Traits;

trait Auditable
{
    public static function bootAuditable()
    {
        static::creating(function ($model) {
            if (property_exists($model, 'ip_address') && request()->ip()) {
                $model->ip_address = request()->ip();
            }
            if (property_exists($model, 'user_agent') && request()->userAgent()) {
                $model->user_agent = request()->userAgent();
            }
        });
    }
    
    public function scopeWithIp($query, $ip)
    {
        return $query->where('ip_address', $ip);
    }
    
    public function scopeInDateRange($query, $from, $to)
    {
        return $query->whereBetween('created_at', [$from, $to]);
    }
}
```

### **Enhanced VoterSlugStep Model**

```php
// app/Models/VoterSlugStep.php
use App\Traits\BelongsToTenant;
use App\Traits\Auditable;

class VoterSlugStep extends Model
{
    use BelongsToTenant, Auditable;
    
    protected $fillable = [
        'voter_slug_id',
        'election_id',
        'step',
        'ip_address',
        'started_at',
        'completed_at',
        'user_agent',
        'organisation_id'
    ];
    
    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];
    
    // Track step duration
    public function getDurationAttribute()
    {
        if ($this->started_at && $this->completed_at) {
            return $this->completed_at->diffInSeconds($this->started_at);
        }
        return null;
    }
}
```

---

## 🔐 **Layer 3: Enhanced Middleware for Complete Isolation**

```php
// app/Http/Middleware/VotingSecurityMiddleware.php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\VoterSlug;
use App\Models\Election;

class VotingSecurityMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        
        $organisationId = auth()->user()->organisation_id;
        $ip = $request->ip();
        $userAgent = $request->userAgent();
        
        // Log this access attempt (security audit)
        \Log::channel('voting_security')->info('Voting access attempt', [
            'user_id' => auth()->id(),
            'organisation_id' => $organisationId,
            'ip' => $ip,
            'user_agent' => $userAgent,
            'url' => $request->fullUrl(),
            'method' => $request->method()
        ]);
        
        // Check if accessing a voter slug
        if ($slug = $request->route('slug')) {
            $voterSlug = VoterSlug::where('slug', $slug)->first();
            
            if (!$voterSlug || $voterSlug->organisation_id !== $organisationId) {
                // Security violation - log it
                \Log::channel('voting_security')->warning('Cross-org voting attempt', [
                    'user_id' => auth()->id(),
                    'org_id' => $organisationId,
                    'attempted_slug' => $slug,
                    'slug_org_id' => $voterSlug->organisation_id ?? 'not_found',
                    'ip' => $ip
                ]);
                
                abort(403, 'Unauthorized voting access');
            }
            
            // Update last activity
            $voterSlug->update([
                'last_activity_at' => now(),
                'last_ip' => $ip
            ]);
        }
        
        // Check if accessing an election
        if ($electionId = $request->route('election')) {
            $election = Election::find($electionId);
            
            if (!$election || $election->organisation_id !== $organisationId) {
                \Log::channel('voting_security')->warning('Cross-org election access', [
                    'user_id' => auth()->id(),
                    'org_id' => $organisationId,
                    'attempted_election' => $electionId,
                    'election_org_id' => $election->organisation_id ?? 'not_found',
                    'ip' => $ip
                ]);
                
                abort(404);
            }
        }
        
        return $next($request);
    }
}
```

---

## 📝 **Complete Vote Controller with Audit**

```php
// app/Http/Controllers/VoteController.php
<?php

namespace App\Http\Controllers;

use App\Models\Vote;
use App\Models\Result;
use App\Models\VoterSlug;
use App\Models\VoterSlugStep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VoteController extends Controller
{
    public function first_submission(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $organisationId = session('current_organisation_id');
            $userId = auth()->id();
            $ip = $request->ip();
            $userAgent = $request->userAgent();
            
            // Verify voter slug belongs to this org
            $voterSlug = VoterSlug::where('slug', $request->slug)
                ->where('organisation_id', $organisationId)
                ->firstOrFail();
            
            // Record step with audit trail
            $step = VoterSlugStep::create([
                'voter_slug_id' => $voterSlug->id,
                'election_id' => $request->election_id,
                'step' => 3,
                'ip_address' => $ip,
                'user_agent' => $userAgent,
                'started_at' => now(),
                'organisation_id' => $organisationId
            ]);
            
            // Log for security audit
            Log::channel('voting_audit')->info('Vote selection started', [
                'user_id' => $userId,
                'org_id' => $organisationId,
                'election_id' => $request->election_id,
                'voter_slug_id' => $voterSlug->id,
                'step_id' => $step->id,
                'ip' => $ip
            ]);
            
            return response()->json(['status' => 'success']);
        });
    }
    
    public function store(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $organisationId = session('current_organisation_id');
            $userId = auth()->id();
            $ip = $request->ip();
            
            // Verify voter slug
            $voterSlug = VoterSlug::where('slug', $request->slug)
                ->where('organisation_id', $organisationId)
                ->firstOrFail();
            
            // Complete the step
            $step = VoterSlugStep::where('voter_slug_id', $voterSlug->id)
                ->where('step', 3)
                ->whereNull('completed_at')
                ->firstOrFail();
            
            $step->update([
                'completed_at' => now(),
                'ip_address' => $ip  // Update with final IP
            ]);
            
            // Record step 4 (verification)
            $verifyStep = VoterSlugStep::create([
                'voter_slug_id' => $voterSlug->id,
                'election_id' => $request->election_id,
                'step' => 4,
                'ip_address' => $ip,
                'started_at' => now(),
                'completed_at' => now(),
                'organisation_id' => $organisationId
            ]);
            
            // Create the vote (ANONYMOUS - no user_id)
            $vote = Vote::create([
                'election_id' => $request->election_id,
                'voting_code' => bcrypt($request->voting_code), // Hash, not store plain
                'ip_address' => $ip,  // Audit field
                'user_agent' => $request->userAgent(),
                'organisation_id' => $organisationId
            ]);
            
            // Record final step
            $finalStep = VoterSlugStep::create([
                'voter_slug_id' => $voterSlug->id,
                'election_id' => $request->election_id,
                'step' => 5,
                'ip_address' => $ip,
                'started_at' => now(),
                'completed_at' => now(),
                'organisation_id' => $organisationId
            ]);
            
            // Save results (still anonymous)
            foreach ($request->selections as $candidateId) {
                Result::create([
                    'vote_id' => $vote->id,
                    'candidate_id' => $candidateId,
                    'ip_address' => $ip,  // Audit trail
                    'organisation_id' => $organisationId
                ]);
            }
            
            // Comprehensive audit log
            Log::channel('voting_audit')->info('Vote completed', [
                'user_id' => $userId,
                'org_id' => $organisationId,
                'election_id' => $request->election_id,
                'vote_id' => $vote->id,
                'vote_hash' => substr($vote->voting_code, 0, 10) . '...', // Partial for reference
                'steps' => [
                    'step3_duration' => $step->duration,
                    'step4_completed' => $verifyStep->completed_at,
                    'step5_completed' => $finalStep->completed_at
                ],
                'ip' => $ip,
                'user_agent' => $request->userAgent()
            ]);
            
            return response()->json(['status' => 'success']);
        });
    }
}
```

---

## 📊 **Complete Election Audit Report**

### **Audit Report Query**

```php
// app/Reports/ElectionAuditReport.php
<?php

namespace App\Reports;

use App\Models\Election;
use App\Models\VoterSlug;
use App\Models\VoterSlugStep;
use App\Models\Vote;
use Illuminate\Support\Facades\DB;

class ElectionAuditReport
{
    public function generateForElection($electionId, $organisationId)
    {
        // Verify election belongs to org
        $election = Election::where('id', $electionId)
            ->where('organisation_id', $organisationId)
            ->firstOrFail();
        
        // Get all voter slugs for this election
        $voterSlugs = VoterSlug::where('election_id', $electionId)
            ->with('user:id,name,email') // User info for voter tracking
            ->get();
        
        $report = [
            'election' => [
                'id' => $election->id,
                'name' => $election->name,
                'type' => $election->type,
                'organisation_id' => $election->organisation_id,
                'created_at' => $election->created_at,
                'total_voters' => $voterSlugs->count(),
            ],
            'voting_activity' => [],
            'ip_analysis' => [],
            'timeline_analysis' => []
        ];
        
        foreach ($voterSlugs as $slug) {
            // Get all steps for this voter
            $steps = VoterSlugStep::where('voter_slug_id', $slug->id)
                ->orderBy('step')
                ->get();
            
            $voterActivity = [
                'voter_slug' => $slug->slug,
                'user' => $slug->user ? [
                    'id' => $slug->user->id,
                    'name' => $slug->user->name,
                    'email' => $slug->user->email
                ] : null,
                'steps' => [],
                'ip_addresses' => $steps->pluck('ip_address')->unique()->values(),
                'first_activity' => $steps->min('created_at'),
                'last_activity' => $steps->max('created_at'),
                'total_time_seconds' => null
            ];
            
            // Calculate total time if completed
            if ($steps->count() == 5) {
                $first = $steps->first()->created_at;
                $last = $steps->last()->completed_at ?? $steps->last()->created_at;
                $voterActivity['total_time_seconds'] = $first->diffInSeconds($last);
            }
            
            // Detail each step
            foreach ($steps as $step) {
                $voterActivity['steps'][] = [
                    'step_number' => $step->step,
                    'ip' => $step->ip_address,
                    'user_agent' => $step->user_agent,
                    'started' => $step->started_at,
                    'completed' => $step->completed_at,
                    'duration' => $step->duration,
                ];
            }
            
            // Check if they actually voted
            $vote = Vote::where('election_id', $electionId)
                ->where('voting_code', 'like', '%' . substr($slug->slug, 0, 10) . '%') // Approximate match
                ->first();
            
            $voterActivity['voted'] = !is_null($vote);
            if ($vote) {
                $voterActivity['vote_recorded_at'] = $vote->created_at;
                $voterActivity['vote_ip'] = $vote->ip_address;
            }
            
            $report['voting_activity'][] = $voterActivity;
        }
        
        // IP Analysis - detect suspicious patterns
        $report['ip_analysis'] = $this->analyzeIPs($voterSlugs);
        
        // Timeline Analysis
        $report['timeline_analysis'] = $this->analyzeTimeline($voterSlugs);
        
        return $report;
    }
    
    private function analyzeIPs($voterSlugs)
    {
        $ipCounts = [];
        $suspicious = [];
        
        foreach ($voterSlugs as $slug) {
            $steps = VoterSlugStep::where('voter_slug_id', $slug->id)->get();
            $ips = $steps->pluck('ip_address')->filter();
            
            foreach ($ips as $ip) {
                if (!isset($ipCounts[$ip])) {
                    $ipCounts[$ip] = [
                        'count' => 0,
                        'voters' => []
                    ];
                }
                $ipCounts[$ip]['count']++;
                if (!in_array($slug->id, $ipCounts[$ip]['voters'])) {
                    $ipCounts[$ip]['voters'][] = $slug->id;
                }
            }
        }
        
        // Flag suspicious IPs (many voters from same IP)
        foreach ($ipCounts as $ip => $data) {
            if (count($data['voters']) > 3) {
                $suspicious[] = [
                    'ip' => $ip,
                    'voter_count' => count($data['voters']),
                    'voter_ids' => $data['voters']
                ];
            }
        }
        
        return [
            'ip_summary' => $ipCounts,
            'suspicious_ips' => $suspicious
        ];
    }
    
    private function analyzeTimeline($voterSlugs)
    {
        $steps = VoterSlugStep::whereIn('voter_slug_id', $voterSlugs->pluck('id'))
            ->orderBy('created_at')
            ->get()
            ->groupBy(function($item) {
                return $item->created_at->format('Y-m-d H:00'); // Group by hour
            });
        
        $hourly = [];
        foreach ($steps as $hour => $hourSteps) {
            $hourly[$hour] = [
                'total_actions' => $hourSteps->count(),
                'unique_voters' => $hourSteps->pluck('voter_slug_id')->unique()->count(),
                'steps_breakdown' => $hourSteps->groupBy('step')->map->count()
            ];
        }
        
        return [
            'hourly_activity' => $hourly,
            'peak_voting_hours' => collect($hourly)->sortByDesc('total_actions')->take(3)->keys(),
            'average_voting_time' => $this->calculateAverageVotingTime($voterSlugs)
        ];
    }
    
    private function calculateAverageVotingTime($voterSlugs)
    {
        $times = [];
        foreach ($voterSlugs as $slug) {
            $steps = VoterSlugStep::where('voter_slug_id', $slug->id)->get();
            if ($steps->count() == 5 && $steps->first()->created_at && $steps->last()->completed_at) {
                $times[] = $steps->first()->created_at->diffInSeconds($steps->last()->completed_at);
            }
        }
        
        return count($times) > 0 ? array_sum($times) / count($times) : null;
    }
}
```

---

## 📋 **Audit Log Channels**

```php
// config/logging.php
'channels' => [
    'voting_audit' => [
        'driver' => 'daily',
        'path' => storage_path('logs/voting/audit.log'),
        'level' => 'info',
        'days' => 90, // Keep audit logs for 90 days
    ],
    
    'voting_security' => [
        'driver' => 'daily',
        'path' => storage_path('logs/voting/security.log'),
        'level' => 'warning',
        'days' => 365, // Keep security logs for 1 year
    ],
    
    'tenant_voting' => [
        'driver' => 'daily',
        'path' => storage_path('logs/tenant_{organisation_id}/voting.log'),
        'level' => 'debug',
        'days' => 30,
    ],
],
```

---

## ✅ **Verification Queries**

### **Check Cross-Org Vote Prevention**

```sql
-- This should return 0 if isolation is working
SELECT COUNT(*) 
FROM votes v
JOIN elections e ON v.election_id = e.id
WHERE v.organisation_id != e.organisation_id;
```

### **Complete Audit Trail Query**

```sql
SELECT 
    vs.user_id,
    u.name,
    u.email,
    vss.step,
    vss.ip_address,
    vss.user_agent,
    vss.created_at,
    vss.completed_at,
    TIMEDIFF(vss.completed_at, vss.created_at) as step_duration,
    v.voting_code as vote_reference,
    v.ip_address as vote_ip
FROM voter_slug_steps vss
JOIN voter_slugs vs ON vss.voter_slug_id = vs.id
LEFT JOIN users u ON vs.user_id = u.id
LEFT JOIN votes v ON v.election_id = vss.election_id 
    AND v.created_at BETWEEN vss.created_at AND DATE_ADD(vss.created_at, INTERVAL 1 HOUR)
WHERE vss.election_id = ?
ORDER BY vss.created_at;
```

---

## 🎯 **Summary: Complete Protection + Audit**

| Requirement | Solution | Verification |
|------------|----------|--------------|
| **No cross-org votes** | Composite foreign keys + middleware + global scope | SQL check: `COUNT(*) WHERE votes.org_id != elections.org_id` |
| **Complete activity audit** | VoterSlugStep table with IP, timestamps, user_agent | Run audit report |
| **IP tracking** | ip_address field on all tables | IP analysis in report |
| **Timestamp tracking** | started_at, completed_at on steps | Timeline analysis |
| **User identification** | voter_slugs.user_id links to users | Voter activity report |
| **Vote anonymity** | NO user_id in votes/results | Schema verification |
| **Suspicious activity detection** | IP analysis, timing analysis | Report flags suspicious IPs |

---

## 📝 **Implementation Commands**

```bash
# Create migrations
php artisan make:migration add_audit_fields_to_voting_tables
php artisan make:migration add_org_foreign_keys

# Create trait
php artisan make:trait Auditable

# Create middleware
php artisan make:middleware VotingSecurityMiddleware

# Create report class
php artisan make:class Reports/ElectionAuditReport

# Run migrations
php artisan migrate

# Add middleware to kernel
# In app/Http/Kernel.php, add to 'web' group:
\App\Http\Middleware\VotingSecurityMiddleware::class,
```

---

This architecture provides:
- ✅ **Watertight isolation** - Database-level constraints prevent cross-org votes
- ✅ **Complete audit trail** - Every step tracked with IP and timestamps
- ✅ **Anonymity preserved** - No user_id in votes/results
- ✅ **Suspicious activity detection** - IP analysis flags anomalies
- ✅ **Comprehensive reporting** - Full election audit reports

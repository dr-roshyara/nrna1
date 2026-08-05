## 📋 **SENIOR ENGINEER PROMPT INSTRUCTIONS: REAL VOTING SYSTEM TENANCY ENFORCEMENT**

```
## CONTEXT
We have a multi-tenant voting system with COMPLETE separation:
- **Demo System**: `demo_votes`, `demo_results` (organisation_id can be NULL for public testing)
- **REAL System**: `votes`, `results` (organisation_id MUST NOT be NULL)

## CRITICAL REQUIREMENT
For the REAL voting system, `organisation_id` is MANDATORY and must be enforced at EVERY level:

```
REAL VOTE REQUIREMENTS:
┌─────────────────────────────────────────────────────────┐
│  ✓ organisation_id MUST NOT be NULL                    │
│  ✓ Must match the voter's organisation                  │
│  ✓ Must match the election's organisation               │
│  ✓ Must be enforced at database, model, and app levels │
│  ✓ Without org_id, vote CANNOT be saved                │
└─────────────────────────────────────────────────────────┘
```

---

## 🎯 **ENFORCEMENT LAYERS**

### Layer 1: Database Constraints (HARD Boundary)
```sql
-- Migration: add_real_vote_constraints.php
Schema::table('votes', function (Blueprint $table) {
    // 1. NOT NULL constraint - organisation_id is REQUIRED
    $table->unsignedBigInteger('organisation_id')
          ->nullable(false)  // ← CRITICAL: Cannot be NULL
          ->change();
    
    // 2. Composite foreign key ensures vote belongs to correct org's election
    $table->foreign(['election_id', 'organisation_id'])
          ->references(['id', 'organisation_id'])
          ->on('elections')
          ->onDelete('cascade');
});

Schema::table('results', function (Blueprint $table) {
    // 1. NOT NULL constraint
    $table->unsignedBigInteger('organisation_id')
          ->nullable(false)  // ← CRITICAL: Cannot be NULL
          ->change();
    
    // 2. Composite foreign key ensures result belongs to correct org's vote
    $table->foreign(['vote_id', 'organisation_id'])
          ->references(['id', 'organisation_id'])
          ->on('votes')
          ->onDelete('cascade');
});
```

### Layer 2: Model-Level Enforcement (SOFT Boundary)
```php
// app/Models/Vote.php
class Vote extends Model
{
    use BelongsToTenant;
    
    protected $fillable = [
        'organisation_id',  // MUST be fillable
        // ... other fields
    ];
    
    // Add validation hook
    protected static function booted()
    {
        static::creating(function ($vote) {
            // CRITICAL: organisation_id MUST be present for real votes
            if (is_null($vote->organisation_id)) {
                throw new \Exception('REAL VOTE ERROR: organisation_id is required');
            }
            
            // Verify the election belongs to this org
            $election = Election::find($vote->election_id);
            if (!$election || $election->organisation_id !== $vote->organisation_id) {
                throw new \Exception('REAL VOTE ERROR: Election does not belong to this organisation');
            }
        });
    }
}
```

### Layer 3: Controller-Level Enforcement (Application Boundary)
```php
// app/Http/Controllers/VoteController.php

public function store(Request $request)
{
    // CRITICAL: Verify user has organisation
    $user = auth()->user();
    if (is_null($user->organisation_id)) {
        Log::channel('voting_security')->warning('REAL VOTE BLOCKED: User without organisation', [
            'user_id' => $user->id,
            'ip' => $request->ip()
        ]);
        
        return response()->json([
            'error' => 'You must belong to an organisation to vote in real elections'
        ], 403);
    }
    
    // CRITICAL: Verify election belongs to user's organisation
    $election = Election::where('id', $request->election_id)
        ->where('organisation_id', $user->organisation_id)
        ->first();
    
    if (!$election) {
        Log::channel('voting_security')->warning('REAL VOTE BLOCKED: Election not in user\'s organisation', [
            'user_id' => $user->id,
            'org_id' => $user->organisation_id,
            'election_id' => $request->election_id
        ]);
        
        return response()->json([
            'error' => 'Election not found in your organisation'
        ], 404);
    }
    
    // CRITICAL: Verify election is REAL type
    if ($election->type !== 'real') {
        return response()->json([
            'error' => 'This is not a real election'
        ], 400);
    }
    
    // Proceed with vote (organisation_id auto-filled by trait)
    DB::transaction(function () use ($request, $user, $election) {
        $vote = Vote::create([
            'election_id' => $election->id,
            'voting_code' => Hash::make($request->voting_code),
            'ip_address' => $request->ip(),
            // organisation_id auto-filled by trait from session
        ]);
        
        // Verify auto-fill worked
        if (is_null($vote->organisation_id)) {
            throw new \Exception('CRITICAL: organisation_id not set on vote');
        }
        
        // Save results
        foreach ($request->selections as $candidateId) {
            Result::create([
                'vote_id' => $vote->id,
                'candidate_id' => $candidateId,
                'organisation_id' => $vote->organisation_id
            ]);
        }
        
        // Log successful vote
        Log::channel('voting_audit')->info('REAL VOTE CAST', [
            'user_id' => $user->id,
            'org_id' => $vote->organisation_id,
            'election_id' => $election->id,
            'vote_id' => $vote->id
        ]);
    });
    
    return response()->json(['status' => 'success']);
}
```

### Layer 4: Middleware Enforcement (Pre-Request Boundary)
```php
// app/Http/Middleware/EnsureRealVoteOrganisation.php

public function handle($request, $next)
{
    // Only apply to real voting routes
    if ($request->routeIs('vote.*') && $request->has('election_id')) {
        $user = auth()->user();
        $election = Election::find($request->election_id);
        
        // CRITICAL CHECKS
        if (!$user || is_null($user->organisation_id)) {
            Log::channel('voting_security')->error('REAL VOTE PREVENTION: User without org', [
                'user_id' => $user->id ?? 'guest',
                'ip' => $request->ip(),
                'election_id' => $request->election_id
            ]);
            
            return redirect()->route('dashboard')
                ->with('error', 'You must create or join an organisation to vote in real elections.');
        }
        
        if (!$election || $election->type !== 'real') {
            abort(404);
        }
        
        if ($election->organisation_id !== $user->organisation_id) {
            Log::channel('voting_security')->warning('REAL VOTE PREVENTION: Org mismatch', [
                'user_id' => $user->id,
                'user_org' => $user->organisation_id,
                'election_org' => $election->organisation_id
            ]);
            
            abort(403, 'You cannot vote in this election');
        }
    }
    
    return $next($request);
}
```

---

## 🧪 **TESTS TO WRITE**

```php
// tests/Feature/RealVoteOrganisationEnforcementTest.php

public function test_real_vote_requires_organisation_id()
{
    // Create user WITHOUT organisation
    $user = User::factory()->create(['organisation_id' => null]);
    $election = Election::factory()->create(['type' => 'real', 'organisation_id' => 1]);
    
    $this->actingAs($user);
    
    // Attempt to vote
    $response = $this->post('/vote/store', [
        'election_id' => $election->id,
        'voting_code' => 'test',
        'selections' => [1]
    ]);
    
    // Should be blocked
    $response->assertStatus(403);
    $this->assertDatabaseMissing('votes', ['election_id' => $election->id]);
}

public function test_real_vote_requires_matching_organisation()
{
    // User from org 1
    $user = User::factory()->create(['organisation_id' => 1]);
    // Election from org 2
    $election = Election::factory()->create(['type' => 'real', 'organisation_id' => 2]);
    
    $this->actingAs($user);
    session(['current_organisation_id' => 1]);
    
    $response = $this->post('/vote/store', [
        'election_id' => $election->id,
        'voting_code' => 'test',
        'selections' => [1]
    ]);
    
    // Should be blocked
    $response->assertStatus(404); // Election not found (due to global scope)
}

public function test_real_vote_auto_fills_organisation_id_correctly()
{
    $user = User::factory()->create(['organisation_id' => 5]);
    $election = Election::factory()->create([
        'type' => 'real',
        'organisation_id' => 5
    ]);
    
    $this->actingAs($user);
    session(['current_organisation_id' => 5]);
    
    $this->post('/vote/store', [
        'election_id' => $election->id,
        'voting_code' => 'test',
        'selections' => [1, 2]
    ]);
    
    // Verify vote has correct org_id
    $vote = Vote::where('election_id', $election->id)->first();
    $this->assertEquals(5, $vote->organisation_id);
    
    // Verify results have correct org_id
    $results = Result::where('vote_id', $vote->id)->get();
    foreach ($results as $result) {
        $this->assertEquals(5, $result->organisation_id);
    }
}

public function test_cannot_override_organisation_id_in_real_vote()
{
    $user = User::factory()->create(['organisation_id' => 5]);
    $election = Election::factory()->create([
        'type' => 'real',
        'organisation_id' => 5
    ]);
    
    $this->actingAs($user);
    session(['current_organisation_id' => 5]);
    
    // Try to force different org_id
    $this->post('/vote/store', [
        'election_id' => $election->id,
        'voting_code' => 'test',
        'selections' => [1],
        'organisation_id' => 999 // Attempt to override
    ]);
    
    // Verify trait ignored the override
    $vote = Vote::where('election_id', $election->id)->first();
    $this->assertEquals(5, $vote->organisation_id); // Still 5, not 999
}

public function test_real_vote_count_only_includes_same_org()
{
    // Create votes for org 1
    $user1 = User::factory()->create(['organisation_id' => 1]);
    $election1 = Election::factory()->create(['type' => 'real', 'organisation_id' => 1]);
    
    $this->actingAs($user1);
    session(['current_organisation_id' => 1]);
    
    Vote::factory()->count(5)->create([
        'election_id' => $election1->id,
        'organisation_id' => 1
    ]);
    
    // Create votes for org 2
    $user2 = User::factory()->create(['organisation_id' => 2]);
    $election2 = Election::factory()->create(['type' => 'real', 'organisation_id' => 2]);
    
    $this->actingAs($user2);
    session(['current_organisation_id' => 2]);
    
    Vote::factory()->count(3)->create([
        'election_id' => $election2->id,
        'organisation_id' => 2
    ]);
    
    // Org 1 count
    $this->actingAs($user1);
    session(['current_organisation_id' => 1]);
    $this->assertEquals(5, Vote::count());
    
    // Org 2 count
    $this->actingAs($user2);
    session(['current_organisation_id' => 2]);
    $this->assertEquals(3, Vote::count());
}
```

---

## 🔒 **PRE-HOOKS CHECKLIST**

### Database Level (HARD)
- [ ] `votes.organisation_id` set to `NOT NULL`
- [ ] `results.organisation_id` set to `NOT NULL`
- [ ] Composite foreign key: `(election_id, organisation_id)` → `elections(id, organisation_id)`
- [ ] Composite foreign key: `(vote_id, organisation_id)` → `votes(id, organisation_id)`

### Model Level (SOFT)
- [ ] `Vote` model has `BelongsToTenant` trait
- [ ] `Result` model has `BelongsToTenant` trait
- [ ] `'organisation_id'` in `$fillable` arrays
- [ ] Validation hook in `booted()` to ensure org_id exists

### Controller Level (APPLICATION)
- [ ] Verify user has organisation_id
- [ ] Verify election belongs to user's org
- [ ] Verify election is 'real' type
- [ ] Log all attempts (success/failure)
- [ ] Atomic transaction for vote+results

### Middleware Level (PRE-REQUEST)
- [ ] Create `EnsureRealVoteOrganisation` middleware
- [ ] Register in Kernel.php
- [ ] Apply to real voting routes

---

## 📊 **VERIFICATION QUERIES**

```sql
-- 1. Check for any votes without organisation_id (SHOULD BE 0)
SELECT COUNT(*) FROM votes WHERE organisation_id IS NULL;

-- 2. Check for votes where org_id doesn't match election's org (SHOULD BE 0)
SELECT COUNT(*) 
FROM votes v
JOIN elections e ON v.election_id = e.id
WHERE v.organisation_id != e.organisation_id;

-- 3. Check for results without organisation_id (SHOULD BE 0)
SELECT COUNT(*) FROM results WHERE organisation_id IS NULL;

-- 4. Check for results where org_id doesn't match vote's org (SHOULD BE 0)
SELECT COUNT(*) 
FROM results r
JOIN votes v ON r.vote_id = v.id
WHERE r.organisation_id != v.organisation_id;

-- 5. List any anomalies
SELECT 
    v.id as vote_id,
    v.organisation_id as vote_org,
    e.organisation_id as election_org,
    e.name as election_name
FROM votes v
JOIN elections e ON v.election_id = e.id
WHERE v.organisation_id != e.organisation_id
   OR v.organisation_id IS NULL;
```

---

## 🚀 **IMPLEMENTATION COMMANDS**

```bash
# 1. Create migration for NOT NULL constraints
php artisan make:migration add_not_null_constraint_to_votes_organisation_id --table=votes
php artisan make:migration add_not_null_constraint_to_results_organisation_id --table=results

# 2. Create migration for composite foreign keys
php artisan make:migration add_composite_foreign_keys_to_voting_tables

# 3. Create middleware
php artisan make:middleware EnsureRealVoteOrganisation

# 4. Create tests
php artisan make:test RealVoteOrganisationEnforcementTest

# 5. Run migrations
php artisan migrate

# 6. Run tests
php artisan test tests/Feature/RealVoteOrganisationEnforcementTest.php
```

---

## ✅ **ACCEPTANCE CRITERIA**

- [ ] `votes.organisation_id` is NOT NULL
- [ ] `results.organisation_id` is NOT NULL
- [ ] Cannot insert vote without organisation_id
- [ ] Cannot insert vote where org_id ≠ election.org_id
- [ ] User without org cannot vote in real elections
- [ ] User can only vote in their org's elections
- [ ] All attempts logged in `voting_security` channel
- [ ] Tests pass with 100% coverage

---

## 📝 **DELIVERABLES**

1. ✅ 2 migration files (NOT NULL constraints)
2. ✅ 1 migration file (composite foreign keys)
3. ✅ 1 middleware file
4. ✅ Updated VoteController with enforcement
5. ✅ 1 test file (5-6 comprehensive tests)
6. ✅ Updated documentation

**The real voting system must be IMPENETRABLE. No vote should ever be saved without proper organisation context.**
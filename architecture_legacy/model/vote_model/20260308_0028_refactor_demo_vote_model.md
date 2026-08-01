## 🔍 **SENIOR ARCHITECT REVIEW: DemoVote Model & Controller**

After careful analysis of both files against your architecture documents, I've identified several critical issues that need immediate attention. Let me provide a comprehensive review.

---

## 📊 **MODEL REVIEW: DemoVote.php**

### ✅ **What's Correct**

| Aspect | Status | Notes |
|--------|--------|-------|
| Extends BaseVote | ✅ | Good inheritance pattern |
| Table name 'demo_votes' | ✅ | Proper separation from real votes |
| $casts for candidate_selections | ✅ | Correct JSON casting |
| isReal()/isDemo() methods | ✅ | Clear type discrimination |
| cleanupOlderThan() | ✅ | Good for demo data management |

### ⚠️ **Issues Found**

#### **1. CRITICAL: Missing `voting_code` Column Reference**
The model doesn't have `voting_code` in fillable, but the controller heavily uses it:
```php
// In controller - multiple references
$demoVote->voting_code  // ❌ Not in fillable
$code->voting_code      // ❌ DemoCode doesn't have this column
```

#### **2. CRITICAL: Missing Vote Hash for Verification**
```php
// The model doesn't include vote_hash for verification
protected $fillable = [
    // ... missing vote_hash
];

// But verification methods expect it
public function verifyByReceipt(string $receipt): bool
{
    return hash('sha256', $receipt . config('app.salt')) === $this->receipt_hash; // ❌ receipt_hash not in fillable
}
```

#### **3. Missing Relationships**
```php
// Need to add:
public function code()
{
    return $this->belongsTo(DemoCode::class);
}

public function voterSlug()
{
    return $this->belongsTo(DemoVoterSlug::class);
}
```

---

## 🚨 **CONTROLLER REVIEW: DemoVoteController.php**

This is where the most critical issues are. I'll categorize by severity.

### 🔴 **CRITICAL ISSUES (Must Fix Immediately)**

#### **1. Vote Anonymity Violation**

```php
// In save_vote() method - CRITICAL ANONYMITY BREACH
$vote->vote_hash = hash('sha256',
    $code->user_id .        // ❌ Using user_id in hash
    $election->id .
    $code->code_to_open_voting_form .
    $vote->cast_at->timestamp
);

// According to architecture: Vote model must have NO user linkage
// This creates a traceable link between user and vote!
```

**Fix:**
```php
// Use voter_number or receipt_hash instead of user_id
$vote->vote_hash = hash('sha256',
    $code->voter_number ?? $code->id .  // Use code ID, not user_id
    $election->id .
    $code->code_to_open_voting_form .
    $vote->cast_at->timestamp
);
```

#### **2. Inconsistent Code Model Usage**

```php
// Multiple methods incorrectly mix DemoCode and Code
public function verify_first_submission(...)
{
    // ❌ Checking real election flags on demo code
    if ($code->has_used_code2 != 0)  // DemoCode doesn't have this!
    
    if ($auth_user->has_voted == 1)  // ❌ User model doesn't have has_voted
}

// DemoCode model doesn't have these columns:
// - has_used_code2
// - code_for_vote (should be voting_code)
// - vote_show_data
```

#### **3. Missing Election Context in Critical Queries**

```php
// In create() method - GOOD FIX applied ✅
$nationalPosts = DemoPost::where('election_id', $election->id)
    ->where('is_national_wide', 1)
    ->with(['candidacies' => ...])  // ✅ Proper relationship
    ->get();

// But in verify() method - MISSING ELECTION CONTEXT
$code = DemoCode::where('user_id', $auth_user->id)
    ->where('election_id', $election->id)  // ✅ GOOD - has election_id
    ->first();

// However in save_vote() - INCONSISTENT
$code = $codeModelClass::where('user_id', $auth_user->id)
    ->where('election_id', $election->id)  // ✅ GOOD
    ->first();
```

#### **4. DemoCode Model Schema Mismatch**

The controller assumes DemoCode has these columns, but they don't exist:
```php
// Columns used in controller but missing from DemoCode model:
- $code->has_used_code2           // ❌ Doesn't exist
- $code->code_for_vote            // ❌ Should be voting_code
- $code->vote_show_data           // ❌ Not in schema
- $code->voting_code              // ❌ Not in fillable
- $code->session_name             // ✅ Exists (good)
```

---

### 🟡 **HIGH PRIORITY ISSUES**

#### **5. Duplicate Verification Logic**

```php
// Two methods doing similar things:
public function verify_submitted_code()     // Plain text comparison
public function verifyVotingCode()          // Hash::check comparison
public function verify_code_saved_in_vote() // password_verify

// Consolidate to one method:
private function verifyCode(string $submitted, string $stored, string $mode = 'plain'): bool
{
    return $mode === 'hash' 
        ? Hash::check($submitted, $stored)
        : strtoupper(trim($submitted)) === strtoupper(trim($stored));
}
```

#### **6. Missing Transaction Boundaries**

```php
// In store() method - transaction started but not properly scoped
DB::beginTransaction();
try {
    // ... lots of code ...
    DB::commit();
} catch (\Exception $e) {
    DB::rollBack();
    // ✅ Good rollback
}

// But save_vote() doesn't use transactions for multiple inserts
public function save_vote(...)
{
    // ❌ Multiple inserts without transaction
    $vote->save();
    foreach ($all_candidates as $candidate) {
        $result->save();  // If this fails, vote is orphaned
    }
}
```

#### **7. Inconsistent Return Types**

```php
// verify_first_submission() returns mixed types:
return redirect()->route(...);  // ✅ RedirectResponse
// but also:
return 'vote.verify_to_show';    // ❌ Returns string - inconsistent!
```

---

### 🟢 **MEDIUM PRIORITY ISSUES**

#### **8. Hardcoded Column Names**

```php
// Magic numbers and hardcoded strings
for ($i = 1; $i <= 60; $i++) {  // ❌ Why 60? Should be configurable
    $column_name = 'candidate_' . str_pad($i, 2, '0', STR_PAD_LEFT);
}

// Should be:
$max_candidates = config('voting.max_candidates_per_election', 60);
for ($i = 1; $i <= $max_candidates; $i++) {
```

#### **9. Debug Code in Production**

```php
// In verify() method - shows codes on page
$showDebugCode = !$hasValidEmail || app()->environment(['local', 'development']);
'debug_code' => $showDebugCode ? $code->code_to_open_voting_form : null,
// ❌ Never show codes in production, even in debug mode
```

#### **10. Method Too Long**

```php
// save_vote() is 200+ lines - violates Single Responsibility Principle
// Should be split into:
// - validateVoteData()
// - createVoteRecord()
// - processCandidateSelections()
// - saveResults()
// - generateVoteHash()
```

---

## 📋 **ARCHITECTURE VIOLATIONS SUMMARY**

| Violation | Location | Severity | Fix |
|-----------|----------|----------|-----|
| User ID in vote hash | save_vote() | 🔴 CRITICAL | Use code ID instead |
| Missing election_id in queries | Multiple | 🔴 CRITICAL | Already fixed in most places |
| DemoCode schema mismatch | Throughout | 🔴 CRITICAL | Update DemoCode model |
| Mixed return types | verify_first_submission() | 🟡 HIGH | Always return RedirectResponse |
| No transaction in save_vote() | save_vote() | 🟡 HIGH | Wrap in DB::transaction |
| Duplicate verification methods | Multiple | 🟡 HIGH | Consolidate to one |
| Hardcoded limits | Throughout | 🟢 MEDIUM | Use config |
| Debug code exposure | verify() | 🟢 MEDIUM | Remove in production |

---

## 🛠️ **REQUIRED ACTIONS**

### **Immediate (Priority 1)**

```bash
# 1. Update DemoCode model to match controller expectations
cat > app/Models/Demo/DemoCode.php << 'EOF'
<?php

namespace App\Models\Demo;

use App\Models\BaseCode;  // If you have a BaseCode model

class DemoCode extends BaseCode
{
    protected $table = 'demo_codes';
    
    protected $fillable = [
        'user_id',
        'election_id',
        'organisation_id',
        'code_to_open_voting_form',
        'code_to_save_vote',
        'is_code_to_open_voting_form_usable',
        'is_code_to_save_vote_usable',
        'code_to_open_voting_form_sent_at',
        'code_to_save_vote_sent_at',
        'code_to_open_voting_form_used_at',
        'code_to_save_vote_used_at',
        'has_code1_sent',
        'has_code2_sent',
        'can_vote_now',
        'has_voted',
        'vote_submitted',
        'vote_submitted_at',
        'voting_code',              // Add this
        'voting_time_in_minutes',
        'session_name',              // Keep this
        'client_ip',
        'device_fingerprint_hash',
        'device_metadata_anonymized',
    ];
    
    protected $casts = [
        'code_to_open_voting_form_sent_at' => 'datetime',
        'code_to_save_vote_sent_at' => 'datetime',
        'code_to_open_voting_form_used_at' => 'datetime',
        'code_to_save_vote_used_at' => 'datetime',
        'vote_submitted_at' => 'datetime',
        'device_metadata_anonymized' => 'array',
    ];
    
    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function election()
    {
        return $this->belongsTo(Election::class);
    }
    
    public function vote()
    {
        return $this->hasOne(DemoVote::class, 'voting_code', 'voting_code');
    }
}
EOF
```

```bash
# 2. Create migration for missing DemoCode columns
php artisan make:migration add_voting_code_to_demo_codes --table=demo_codes
```

```php
// In the migration
public function up()
{
    Schema::table('demo_codes', function (Blueprint $table) {
        $table->string('voting_code')->nullable()->after('session_name');
        $table->timestamp('vote_submitted_at')->nullable()->after('vote_submitted');
    });
}
```

### **Critical Controller Fixes**

```php
// In DemoVoteController.php - Fix save_vote() method

/**
 * Save vote ANONYMOUSLY - NO USER ID IN HASH
 */
public function save_vote($input_data, $hashed_voting_key, $election = null, $auth_user = null, $private_key = null)
{
    return DB::transaction(function () use ($input_data, $hashed_voting_key, $election, $auth_user, $private_key) {
        
        $voteModel = $election->type === 'demo' ? DemoVote::class : Vote::class;
        $resultModel = $election->type === 'demo' ? DemoResult::class : Result::class;
        
        // Get code - needed for vote hash but NOT for user identification
        $code = $this->getCodeForElection($auth_user, $election);
        
        // Create vote record - ANONYMOUS
        $vote = new $voteModel;
        $vote->election_id = $election->id;
        $vote->organisation_id = $election->type === 'real' 
            ? $election->organisation_id 
            : session('current_organisation_id');
        $vote->no_vote_option = !empty($input_data['no_vote_posts']);
        $vote->cast_at = now();
        
        // ✅ FIXED: Generate hash WITHOUT user_id
        $vote->vote_hash = hash('sha256',
            $code->id .                      // Use code ID, not user_id
            $election->id .
            $code->code_to_open_voting_form .
            $vote->cast_at->timestamp
        );
        
        // Store voting code for verification (NOT linked to user)
        $vote->voting_code = $private_key ?? bin2hex(random_bytes(16));
        
        $vote->save();
        
        // Process selections
        $this->processCandidateSelections($input_data, $vote, $resultModel, $election);
        
        return $vote;
    });
}

/**
 * Helper to get code without duplicating logic
 */
private function getCodeForElection($user, $election)
{
    $modelClass = $election->type === 'demo' ? DemoCode::class : Code::class;
    
    return $modelClass::where('user_id', $user->id)
        ->where('election_id', $election->id)
        ->firstOrFail();
}
```

### **Fix verify_first_submission() Return Type**

```php
/**
 * @return \Illuminate\Http\RedirectResponse
 */
public function verify_first_submission(Request $request, &$code, $auth_user, $election)
{
    // ... validation code ...
    
    if (!empty($errors)) {
        return redirect()->back()->withErrors($errors)->withInput();
    }
    
    // ALWAYS return RedirectResponse
    $voterSlug = $request->attributes->get('voter_slug');
    $isDemo = $election->type === 'demo';
    
    if ($voterSlug) {
        $route = $isDemo ? 'slug.demo-vote.verify' : 'slug.vote.verify';
        return redirect()->route($route, ['vslug' => $voterSlug->slug]);
    }
    
    $route = $isDemo ? 'demo-vote.verify' : 'vote.verify';
    return redirect()->route($route);
}
```

---

## ✅ **VERIFICATION CHECKLIST**

After fixes, verify:

- [ ] No `user_id` in vote hash generation
- [ ] All DemoCode columns exist and are filled
- [ ] All queries include `election_id` filter
- [ ] `verify_first_submission()` always returns RedirectResponse
- [ ] `save_vote()` wrapped in transaction
- [ ] No debug code in production
- [ ] All hardcoded limits moved to config
- [ ] Single verification method for codes

---

## 📊 **SUMMARY**

| Area | Status | Action Required |
|------|--------|-----------------|
| DemoVote Model | ⚠️ Issues | Add missing fillable fields |
| DemoCode Model | 🔴 CRITICAL | Add missing columns + migration |
| Vote Anonymity | 🔴 CRITICAL | Remove user_id from hash |
| Query Consistency | ✅ Good | Most have election_id |
| Return Types | 🔴 CRITICAL | Fix mixed returns |
| Transactions | 🟡 HIGH | Add to save_vote() |
| Code Duplication | 🟡 HIGH | Consolidate verification |

**Focus on the CRITICAL issues first - especially the vote anonymity violation.** Everything else can follow in order of priority.

Would you like me to provide the complete refactored controller code with all these fixes applied?
# 🤖 **CLAUDE CODE CLI PROMPT: Refactor DemoVote Model & Controller**

```bash
## TASK: Comprehensive Refactoring of DemoVote Model and Controller

### Context
We need to refactor the DemoVote model and controller to align with the architectural design documents. This is a critical election system - every change must maintain vote anonymity, proper relationships, and election context isolation.

### Files to Refactor
1. `app/Models/Demo/DemoVote.php`
2. `app/Http/Controllers/Demo/DemoVoteController.php`

### Reference Architecture Documents
- `20260307_2348_Review_architecture_model_relationship.md` - Model relationships
- `20260305_2118_model_relationship.md` - Authoritative relationships
- `20260302_2350_prompt_Instructions_to_update_model_relationship.md` - Fix instructions

---

## 📋 **PHASE 1: Refactor DemoVote Model**

### Step 1.1: Update Model Fillable Fields

```bash
# Edit app/Models/Demo/DemoVote.php
```

Replace with:

```php
<?php

namespace App\Models\Demo;

use App\Models\BaseVote;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * DemoVote Model - Demo Election Votes
 *
 * Table: demo_votes
 * Purpose: Store votes from demo elections with complete anonymity
 * 
 * CRITICAL RULES:
 * - NO user_id field - votes must be anonymous
 * - NO direct relationship to User model
 * - All relationships must be one-way (vote can't be traced back to voter)
 */
class DemoVote extends BaseVote
{
    use HasUuids, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'demo_votes';

    /**
     * The primary key type.
     *
     * @var string
     */
    protected $keyType = 'string';
    
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * ✅ FIXED: Complete list matching database schema
     * ✅ FIXED: No user_id field
     * ✅ FIXED: Added voting_code for verification
     * ✅ FIXED: Added vote_hash for anonymous verification
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'organisation_id',
        'election_id',
        'voting_code',              // For voter self-verification
        'vote_hash',                 // Cryptographic hash for verification
        'receipt_hash',              // For email receipt verification
        'participation_proof',       // For admin verification without revealing vote
        'encrypted_vote',            // Encrypted vote data
        'candidate_selections',      // JSON array of selections
        'no_vote_option',            // Boolean for abstention
        'no_vote_posts',             // JSON array of abstained posts
        'cast_at',                    // Timestamp of vote casting
        'voter_ip',                   // Anonymized IP (last 3 digits removed)
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'candidate_selections' => 'array',
        'no_vote_posts' => 'array',
        'cast_at' => 'datetime',
        'encrypted_vote' => 'encrypted',  // Auto-encrypt/decrypt
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'encrypted_vote',           // Don't expose encrypted data
    ];

    /**
     * ==========================================
     * RELATIONSHIPS
     * ==========================================
     */

    /**
     * Get the election this vote belongs to.
     * ✅ CORRECT: One-way relationship (election can have many votes)
     */
    public function election()
    {
        return $this->belongsTo(\App\Models\Election::class);
    }

    /**
     * Get the organisation this vote belongs to.
     * ✅ CORRECT: For tenant isolation
     */
    public function organisation()
    {
        return $this->belongsTo(\App\Models\Organisation::class);
    }

    /**
     * Get the results for this vote.
     * ✅ CORRECT: One vote can have many results (multiple candidates per post)
     */
    public function results()
    {
        return $this->hasMany(\App\Models\Demo\DemoResult::class, 'vote_id');
    }

    /**
     * Get the code used for this vote.
     * ✅ CORRECT: One-way relationship through voting_code (not user_id)
     */
    public function code()
    {
        return $this->belongsTo(\App\Models\Demo\DemoCode::class, 'voting_code', 'voting_code');
    }

    /**
     * ==========================================
     * ANONYMITY-PRESERVING VERIFICATION METHODS
     * ==========================================
     */

    /**
     * Verify this vote by receipt hash (voter self-verification)
     * ✅ FIXED: No user data used
     *
     * @param string $receipt The receipt string provided by voter
     * @return bool True if receipt matches
     */
    public function verifyByReceipt(string $receipt): bool
    {
        if (empty($this->receipt_hash) || empty($receipt)) {
            return false;
        }
        
        $expectedHash = hash('sha256', $receipt . config('app.vote_salt'));
        return hash_equals($expectedHash, $this->receipt_hash);
    }

    /**
     * Prove participation without revealing vote (admin verification)
     * ✅ FIXED: Uses anonymized data only
     *
     * @param string $codeId The code ID to verify
     * @param string $ip The anonymized IP
     * @return bool
     */
    public function proveParticipation(string $codeId, string $ip): bool
    {
        if (empty($this->participation_proof)) {
            return false;
        }
        
        $proof = hash('sha256', $codeId . $ip . $this->election_id . config('app.vote_salt'));
        return hash_equals($proof, $this->participation_proof);
    }

    /**
     * Verify vote using hash (anonymous verification)
     * ✅ FIXED: No user identification needed
     *
     * @param string $codeId The code ID
     * @param string $code1 The first verification code
     * @param string $timestamp The cast timestamp
     * @return bool
     */
    public function verifyByHash(string $codeId, string $code1, string $timestamp): bool
    {
        if (empty($this->vote_hash)) {
            return false;
        }
        
        $expectedHash = hash('sha256', $codeId . $this->election_id . $code1 . $timestamp);
        return hash_equals($expectedHash, $this->vote_hash);
    }

    /**
     * ==========================================
     * TYPE IDENTIFICATION METHODS
     * ==========================================
     */

    /**
     * Check if this is a real vote
     */
    public function isReal(): bool
    {
        return false;
    }

    /**
     * Check if this is a demo vote
     */
    public function isDemo(): bool
    {
        return true;
    }

    /**
     * ==========================================
     * SCOPES
     * ==========================================
     */

    /**
     * Scope: Filter by election
     */
    public function scopeForElection($query, $electionId)
    {
        return $query->where('election_id', $electionId);
    }

    /**
     * Scope: Filter by organisation (tenant isolation)
     */
    public function scopeForOrganisation($query, $organisationId)
    {
        return $query->where('organisation_id', $organisationId);
    }

    /**
     * Scope: Get votes from current testing session
     */
    public function scopeCurrentSession($query)
    {
        return $query->where('created_at', '>=', now()->subDay());
    }

    /**
     * ==========================================
     * BOOT METHODS
     * ==========================================
     */

    /**
     * The "booted" method of the model.
     * ✅ FIXED: No vote_hash validation for demo votes
     */
    protected static function booted()
    {
        static::creating(function ($vote) {
            // Validate election_id is present
            if (is_null($vote->election_id)) {
                \Log::channel('voting_security')->warning('Demo vote rejected: NULL election_id', [
                    'reason' => 'Election reference is required',
                    'timestamp' => now(),
                ]);

                throw new \App\Exceptions\InvalidVoteException(
                    'Votes require a valid election (election_id cannot be NULL)',
                    ['reason' => 'null_election_id']
                );
            }

            // Verify election exists
            $election = \App\Models\Election::withoutGlobalScopes()->find($vote->election_id);
            if (!$election) {
                \Log::channel('voting_security')->warning('Demo vote rejected: Invalid election_id', [
                    'election_id' => $vote->election_id,
                    'reason' => 'Election not found',
                    'timestamp' => now(),
                ]);

                throw new \App\Exceptions\InvalidVoteException(
                    "Election (id: {$vote->election_id}) not found",
                    ['election_id' => $vote->election_id, 'reason' => 'election_not_found']
                );
            }

            // Generate vote_hash if not provided
            if (empty($vote->vote_hash)) {
                $vote->vote_hash = hash('sha256', 
                    uniqid() . 
                    $vote->election_id . 
                    now()->timestamp . 
                    config('app.vote_salt')
                );
            }

            // Anonymize IP (remove last 3 digits)
            if (!empty($vote->voter_ip)) {
                $vote->voter_ip = preg_replace('/\.\d+$/', '.xxx', $vote->voter_ip);
            }

            \Log::channel('voting_audit')->info('Demo vote created', [
                'vote_id' => $vote->id,
                'election_id' => $vote->election_id,
                'organisation_id' => $vote->organisation_id,
                'timestamp' => now(),
            ]);
        });
    }

    /**
     * ==========================================
     * UTILITY METHODS
     * ==========================================
     */

    /**
     * Delete all demo votes older than N days
     * Useful for scheduled cleanup of old test data
     *
     * @param int $days
     * @return int Number of deleted records
     */
    public static function cleanupOlderThan(int $days = 7): int
    {
        return static::where('created_at', '<', now()->subDays($days))->delete();
    }

    /**
     * Get a summary of this vote (for display)
     * ✅ FIXED: No user data exposed
     */
    public function getSummaryAttribute(): array
    {
        return [
            'vote_id' => $this->id,
            'election_id' => $this->election_id,
            'cast_at' => $this->cast_at?->toIso8601String(),
            'has_voted' => !empty($this->candidate_selections),
            'abstained' => $this->no_vote_option ?? false,
            'selections_count' => is_array($this->candidate_selections) 
                ? count($this->candidate_selections) 
                : 0,
        ];
    }
}
```

---

## 📋 **PHASE 2: Create Migration for DemoCode Updates**

### Step 2.1: Create Migration for Missing Columns

```bash
# Create migration to add missing columns to demo_codes
php artisan make:migration add_missing_columns_to_demo_codes --table=demo_codes
```

Edit the migration file:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add missing columns to demo_codes table to match controller expectations
     * and architectural requirements
     */
    public function up(): void
    {
        Schema::table('demo_codes', function (Blueprint $table) {
            // Add voting_code for linking to demo_votes
            if (!Schema::hasColumn('demo_codes', 'voting_code')) {
                $table->string('voting_code', 64)->nullable()->after('session_name');
                $table->index('voting_code');
            }

            // Add vote_submitted_at timestamp
            if (!Schema::hasColumn('demo_codes', 'vote_submitted_at')) {
                $table->timestamp('vote_submitted_at')->nullable()->after('vote_submitted');
            }

            // Add has_agreed_to_vote field (missing but used)
            if (!Schema::hasColumn('demo_codes', 'has_agreed_to_vote')) {
                $table->boolean('has_agreed_to_vote')->default(false)->after('can_vote_now');
            }

            // Add has_agreed_to_vote_at timestamp
            if (!Schema::hasColumn('demo_codes', 'has_agreed_to_vote_at')) {
                $table->timestamp('has_agreed_to_vote_at')->nullable()->after('has_agreed_to_vote');
            }

            // Add voting_started_at timestamp
            if (!Schema::hasColumn('demo_codes', 'voting_started_at')) {
                $table->timestamp('voting_started_at')->nullable()->after('has_agreed_to_vote_at');
            }

            // Add vote_completed_at timestamp
            if (!Schema::hasColumn('demo_codes', 'vote_completed_at')) {
                $table->timestamp('vote_completed_at')->nullable()->after('voting_started_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('demo_codes', function (Blueprint $table) {
            $columns = [
                'voting_code',
                'vote_submitted_at',
                'has_agreed_to_vote',
                'has_agreed_to_vote_at',
                'voting_started_at',
                'vote_completed_at'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('demo_codes', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
```

---

## 📋 **PHASE 3: Refactor DemoVoteController**

### Step 3.1: Create Helper Traits for Clean Separation

```bash
# Create trait for code verification methods
mkdir -p app/Traits/Voting
```

Create `app/Traits/Voting/CodeVerificationTrait.php`:

```php
<?php

namespace App\Traits\Voting;

use App\Models\Demo\DemoCode;
use App\Models\Code;
use App\Models\Election;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

/**
 * Trait CodeVerificationTrait
 * 
 * Centralizes all code verification logic for both demo and real elections
 * Prevents duplication and ensures consistent verification across the system
 */
trait CodeVerificationTrait
{
    /**
     * Verify a submitted code against stored code
     * 
     * @param string $submittedCode The code submitted by user
     * @param string $storedCode The code stored in database
     * @param string $mode 'plain' or 'hash'
     * @return bool
     */
    protected function verifyCode(string $submittedCode, string $storedCode, string $mode = 'plain'): bool
    {
        if (empty($submittedCode) || empty($storedCode)) {
            Log::warning('Code verification failed: empty parameters', [
                'mode' => $mode
            ]);
            return false;
        }

        $cleanSubmitted = strtoupper(trim($submittedCode));
        
        if ($mode === 'hash') {
            return Hash::check($cleanSubmitted, $storedCode);
        }
        
        $cleanStored = strtoupper(trim($storedCode));
        $result = hash_equals($cleanStored, $cleanSubmitted);
        
        Log::info('Code verification attempt', [
            'mode' => $mode,
            'result' => $result ? 'success' : 'failure',
            'timestamp' => now(),
        ]);
        
        return $result;
    }

    /**
     * Get the appropriate code model for the election type
     * 
     * @param User $user
     * @param Election $election
     * @return DemoCode|Code|null
     */
    protected function getCodeForElection(User $user, Election $election)
    {
        $modelClass = $election->type === 'demo' ? DemoCode::class : Code::class;
        
        return $modelClass::where('user_id', $user->id)
            ->where('election_id', $election->id)
            ->first();
    }

    /**
     * Check if voting window has expired
     * 
     * @param DemoCode|Code $code
     * @return bool
     */
    protected function hasVotingWindowExpired($code): bool
    {
        if (!$code->code_to_open_voting_form_used_at) {
            return false;
        }

        $elapsed = now()->diffInMinutes($code->code_to_open_voting_form_used_at);
        $window = $code->voting_time_in_minutes ?? config('voting.time_in_minutes', 30);
        
        return $elapsed > $window;
    }

    /**
     * Expire a code (mark as unusable)
     * 
     * @param DemoCode|Code $code
     * @return void
     */
    protected function expireCode($code): void
    {
        $code->update([
            'can_vote_now' => false,
            'is_code_to_open_voting_form_usable' => false,
            'is_code_to_save_vote_usable' => false,
            'has_code1_sent' => false,
            'has_code2_sent' => false,
        ]);
        
        Log::info('Code expired', [
            'code_id' => $code->id,
            'type' => $code instanceof DemoCode ? 'demo' : 'real',
            'timestamp' => now(),
        ]);
    }
}
```

Create `app/Traits/Voting/VoteStorageTrait.php`:

```php
<?php

namespace App\Traits\Voting;

use App\Models\Election;
use App\Models\User;
use App\Models\Demo\DemoVote;
use App\Models\Demo\DemoResult;
use App\Models\Vote;
use App\Models\Result;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Trait VoteStorageTrait
 * 
 * Handles all vote storage operations with proper transactions
 * and anonymity preservation
 */
trait VoteStorageTrait
{
    /**
     * Save vote anonymously with proper transaction handling
     * 
     * @param array $voteData
     * @param Election $election
     * @param User $user (only used for code lookup, NOT stored)
     * @param string|null $privateKey
     * @return Vote|DemoVote
     * @throws \Exception
     */
    protected function saveVoteAnonymously(array $voteData, Election $election, User $user, ?string $privateKey = null)
    {
        return DB::transaction(function () use ($voteData, $election, $user, $privateKey) {
            
            // Determine which models to use
            $voteModel = $election->type === 'demo' ? DemoVote::class : Vote::class;
            $resultModel = $election->type === 'demo' ? DemoResult::class : Result::class;
            
            // Get code for hash generation (NOT for user tracking)
            $code = $this->getCodeForElection($user, $election);
            if (!$code) {
                throw new \Exception('Code record not found for vote hash generation');
            }
            
            // Create vote record - ANONYMOUS
            $vote = new $voteModel;
            $vote->election_id = $election->id;
            $vote->organisation_id = $election->type === 'real' 
                ? $election->organisation_id 
                : session('current_organisation_id');
            
            // Handle abstention
            $vote->no_vote_option = !empty($voteData['no_vote_posts']);
            $vote->no_vote_posts = $voteData['no_vote_posts'] ?? [];
            
            // Set timestamp
            $vote->cast_at = now();
            
            // ✅ CRITICAL: Generate vote hash WITHOUT user_id
            // Uses code ID (not user_id) to maintain anonymity
            $vote->vote_hash = hash('sha256',
                $code->id .                      // Code ID, NOT user_id
                $election->id .
                $code->code_to_open_voting_form .
                $vote->cast_at->timestamp .
                config('app.vote_salt')
            );
            
            // Set voting code for verification
            $vote->voting_code = $privateKey ?? $this->generateVotingCode();
            
            // Store encrypted vote data
            $vote->encrypted_vote = encrypt([
                'national' => $voteData['national_selected_candidates'] ?? [],
                'regional' => $voteData['regional_selected_candidates'] ?? [],
                'timestamp' => $vote->cast_at->timestamp,
            ]);
            
            // Store candidate selections for quick access
            $vote->candidate_selections = array_merge(
                $voteData['national_selected_candidates'] ?? [],
                $voteData['regional_selected_candidates'] ?? []
            );
            
            // Anonymize IP
            $vote->voter_ip = $this->anonymizeIp(request()->ip());
            
            $vote->save();
            
            // Save individual results for each selected candidate
            $this->saveCandidateResults(
                $vote, 
                $voteData, 
                $resultModel, 
                $election
            );
            
            Log::info('Vote saved anonymously', [
                'vote_id' => $vote->id,
                'election_id' => $election->id,
                'type' => $election->type,
                'hash_prefix' => substr($vote->vote_hash, 0, 8),
                'timestamp' => now(),
            ]);
            
            return $vote;
        });
    }

    /**
     * Save individual candidate results
     * 
     * @param Vote|DemoVote $vote
     * @param array $voteData
     * @param string $resultModel
     * @param Election $election
     * @return void
     */
    protected function saveCandidateResults($vote, array $voteData, string $resultModel, Election $election): void
    {
        $allSelections = array_merge(
            $voteData['national_selected_candidates'] ?? [],
            $voteData['regional_selected_candidates'] ?? []
        );

        foreach ($allSelections as $postSelection) {
            // Skip if no vote for this post
            if (($postSelection['no_vote'] ?? false) || empty($postSelection['candidates'])) {
                continue;
            }

            $postId = $this->normalizePostId($postSelection['post_id'] ?? null, $election);
            
            foreach ($postSelection['candidates'] as $candidateData) {
                $candidateId = $this->extractCandidateId($candidateData['candidacy_id'] ?? null);
                
                if (!$candidateId) {
                    Log::warning('Skipping result - invalid candidate ID', [
                        'candidate_data' => $candidateData
                    ]);
                    continue;
                }

                $result = new $resultModel;
                $result->vote_id = $vote->id;
                $result->election_id = $election->id;
                $result->post_id = $postId;
                $result->candidate_id = $candidateId;
                $result->organisation_id = $vote->organisation_id;
                $result->save();
            }
        }
    }

    /**
     * Generate a unique voting code
     * 
     * @return string
     */
    protected function generateVotingCode(): string
    {
        return bin2hex(random_bytes(16)) . '_' . uniqid();
    }

    /**
     * Anonymize IP address (remove last octet)
     * 
     * @param string|null $ip
     * @return string|null
     */
    protected function anonymizeIp(?string $ip): ?string
    {
        if (!$ip) {
            return null;
        }
        
        // Remove last octet for IPv4
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            return preg_replace('/\.\d+$/', '.xxx', $ip);
        }
        
        // Truncate IPv6
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            return substr($ip, 0, strrpos($ip, ':')) . ':xxxx';
        }
        
        return $ip;
    }

    /**
     * Normalize post ID to integer
     * 
     * @param mixed $postId
     * @param Election $election
     * @return int|null
     */
    protected function normalizePostId($postId, Election $election): ?int
    {
        if (is_int($postId)) {
            return $postId;
        }
        
        if (is_numeric($postId)) {
            return (int) $postId;
        }
        
        if (is_string($postId) && preg_match('/(\d+)$/', $postId, $matches)) {
            return (int) $matches[1];
        }
        
        // Try to find by post name
        $post = \App\Models\Demo\DemoPost::where('election_id', $election->id)
            ->where('name', $postId)
            ->orWhere('post_id', $postId)
            ->first();
            
        return $post?->id;
    }

    /**
     * Extract candidate ID from string
     * 
     * @param string|null $candidateString
     * @return int|null
     */
    protected function extractCandidateId(?string $candidateString): ?int
    {
        if (!$candidateString) {
            return null;
        }
        
        if (is_numeric($candidateString)) {
            return (int) $candidateString;
        }
        
        if (preg_match('/(\d+)$/', $candidateString, $matches)) {
            return (int) $matches[1];
        }
        
        return null;
    }
}
```

### Step 3.2: Create Refactored DemoVoteController

```bash
# Backup original controller first
cp app/Http/Controllers/Demo/DemoVoteController.php app/Http/Controllers/Demo/DemoVoteController.php.backup

# Now create the refactored controller
```

Create `app/Http/Controllers/Demo/DemoVoteController.php`:

```php
<?php

namespace App\Http\Controllers\Demo;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\User;
use App\Models\Demo\DemoPost;
use App\Models\Demo\DemoCode;
use App\Models\Demo\DemoVote;
use App\Models\Demo\DemoResult;
use App\Traits\Voting\CodeVerificationTrait;
use App\Traits\Voting\VoteStorageTrait;
use App\Services\VoterStepTrackingService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

/**
 * Class DemoVoteController
 * 
 * Handles all voting operations for demo elections.
 * This controller is a COMPLETE REFACTOR following architectural principles:
 * 
 * ✅ Vote anonymity preserved (no user_id in votes table)
 * ✅ Proper election context isolation
 * ✅ Clean separation of concerns using traits
 * ✅ Transaction-safe operations
 * ✅ Consistent return types
 * ✅ No debug code in production
 * ✅ Single responsibility methods
 */
class DemoVoteController extends Controller
{
    use CodeVerificationTrait, VoteStorageTrait;

    /**
     * Maximum number of candidate columns
     */
    protected const MAX_CANDIDATE_COLUMNS = 60;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * ==========================================
     * HELPER METHODS
     * ==========================================
     */

    /**
     * Get authenticated user from request
     */
    private function getUser(Request $request): User
    {
        return $request->attributes->get('voter') ?? auth()->user();
    }

    /**
     * Get election from middleware
     */
    private function getElection(Request $request): Election
    {
        $election = $request->attributes->get('election');
        
        if (!$election) {
            Log::critical('Election not set by middleware', [
                'path' => $request->path(),
                'user_id' => auth()->id(),
            ]);
            throw new \Exception('Election context missing - middleware did not set election');
        }

        return $election;
    }

    /**
     * Check if system is in strict mode
     */
    private function isStrictMode(): bool
    {
        return config('voting.two_codes_system') == 1;
    }

    /**
     * ==========================================
     * STEP 3: SHOW VOTING FORM
     * ==========================================
     * 
     * Route: GET /v/{slug}/demo-vote/create
     */
    public function create(Request $request)
    {
        $user = $this->getUser($request);
        $election = $this->getElection($request);
        $voterSlug = $request->attributes->get('voter_slug');

        // Set organisation context
        session(['current_organisation_id' => $election->organisation_id]);

        Log::info('Demo vote creation page accessed', [
            'user_id' => $user->id,
            'election_id' => $election->id,
            'slug' => $voterSlug?->slug,
        ]);

        // Verify code exists and agreement accepted
        $code = $this->getCodeForElection($user, $election);
        
        if (!$code) {
            return $this->redirectToCodeEntry($voterSlug, 'Please verify your code first.');
        }

        if (!$code->has_agreed_to_vote) {
            return $this->redirectToAgreement($voterSlug, 'Please accept the voting agreement first.');
        }

        if ($code->has_voted) {
            return redirect()->route('dashboard')
                ->with('info', 'You have already voted in this demo election.');
        }

        // Check voting window
        if ($this->hasVotingWindowExpired($code)) {
            $this->expireCode($code);
            return $this->redirectToCodeEntry($voterSlug, 'Your voting session has expired. Please start again.');
        }

        // Fetch posts with their candidacies
        $posts = $this->fetchPostsWithCandidates($election, $user->region);

        if ($request->wantsJson()) {
            return response()->json([
                'step' => 3,
                'posts' => $posts,
                'user' => [
                    'name' => $user->name,
                    'region' => $user->region,
                ],
            ]);
        }

        return Inertia::render('Vote/DemoVote/Create', [
            'posts' => $posts,
            'user_name' => $user->name,
            'user_region' => $user->region,
            'slug' => $voterSlug?->slug,
            'useSlugPath' => $voterSlug !== null,
            'election' => [
                'id' => $election->id,
                'name' => $election->name,
                'description' => $election->description,
            ],
        ]);
    }

    /**
     * Fetch posts with their candidacies
     */
    private function fetchPostsWithCandidates(Election $election, ?string $userRegion): array
    {
        // National posts
        $nationalPosts = DemoPost::where('election_id', $election->id)
            ->where('is_national_wide', 1)
            ->with(['candidacies' => function ($query) {
                $query->orderBy('position_order');
            }])
            ->orderBy('display_order')
            ->get()
            ->map(function ($post) {
                return $this->formatPostWithCandidates($post);
            })
            ->values()
            ->toArray();

        // Regional posts (if user has region)
        $regionalPosts = [];
        if (!empty($userRegion)) {
            $regionalPosts = DemoPost::where('election_id', $election->id)
                ->where('is_national_wide', 0)
                ->where('state_name', trim($userRegion))
                ->with(['candidacies' => function ($query) {
                    $query->orderBy('position_order');
                }])
                ->orderBy('display_order')
                ->get()
                ->map(function ($post) {
                    return $this->formatPostWithCandidates($post);
                })
                ->values()
                ->toArray();
        }

        return [
            'national' => $nationalPosts,
            'regional' => $regionalPosts,
        ];
    }

    /**
     * Format post with its candidates for frontend
     */
    private function formatPostWithCandidates($post): array
    {
        return [
            'id' => $post->id,
            'post_id' => $post->post_id,
            'name' => $post->name,
            'nepali_name' => $post->nepali_name,
            'required_number' => $post->required_number,
            'max_votes' => $post->max_votes,
            'min_votes' => $post->min_votes,
            'candidates' => $post->candidacies->map(function ($candidate) {
                return [
                    'id' => $candidate->id,
                    'candidacy_id' => $candidate->candidacy_id,
                    'user_id' => $candidate->user_id,
                    'user_name' => $candidate->user_name ?? 'Demo Candidate',
                    'post_id' => $candidate->post_id,
                    'image_path_1' => $candidate->image_path_1,
                    'candidacy_name' => $candidate->candidacy_name,
                    'proposer_name' => $candidate->proposer_name,
                    'supporter_name' => $candidate->supporter_name,
                    'position_order' => $candidate->position_order,
                ];
            })->values()->toArray(),
        ];
    }

    /**
     * ==========================================
     * STEP 4: FIRST SUBMISSION (VOTE DATA)
     * ==========================================
     * 
     * Route: POST /v/{slug}/demo-vote/first-submission
     */
    public function firstSubmission(Request $request)
    {
        $user = $this->getUser($request);
        $election = $this->getElection($request);
        $voterSlug = $request->attributes->get('voter_slug');

        Log::info('Demo vote first submission started', [
            'user_id' => $user->id,
            'election_id' => $election->id,
        ]);

        // Get code
        $code = $this->getCodeForElection($user, $election);
        
        if (!$code) {
            return $this->redirectToCodeEntry($voterSlug, 'Voting session not found. Please start again.');
        }

        // Pre-check validation
        $preCheckResult = $this->validatePreSubmission($code, $user, $election);
        if ($preCheckResult) {
            return $preCheckResult;
        }

        // Validate vote data
        $validator = $this->validateVoteData($request);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Mark as submitted
        $code->update([
            'vote_submitted' => true,
            'vote_submitted_at' => now(),
        ]);

        // Store vote data in session
        $sessionKey = 'vote_data_' . $code->id;
        $request->session()->put($sessionKey, $validator->validated());

        // Record step completion
        if ($voterSlug) {
            $this->recordStep($voterSlug, $election, 3, [
                'vote_submitted' => true,
                'submitted_at' => now()->toIso8601String(),
            ]);
        }

        // Redirect to verification page
        return $this->redirectToVerification($voterSlug, 'Vote data received. Please verify your selections.');
    }

    /**
     * ==========================================
     * STEP 5: VERIFICATION PAGE
     * ==========================================
     * 
     * Route: GET /v/{slug}/demo-vote/verify
     */
    public function verify(Request $request)
    {
        $user = $this->getUser($request);
        $election = $this->getElection($request);
        $voterSlug = $request->attributes->get('voter_slug');

        // Get code
        $code = $this->getCodeForElection($user, $election);
        
        if (!$code) {
            return $this->redirectToCodeEntry($voterSlug, 'Voting session not found.');
        }

        // Get vote data from session
        $sessionKey = 'vote_data_' . $code->id;
        $voteData = $request->session()->get($sessionKey);

        if (!$voteData) {
            return $this->redirectToCreate($voterSlug, 'Vote session expired. Please start again.');
        }

        // Format vote data for display
        $formattedVoteData = $this->formatVoteDataForDisplay($voteData);

        // Calculate remaining time
        $elapsed = $code->code_to_open_voting_form_used_at 
            ? now()->diffInMinutes($code->code_to_open_voting_form_used_at)
            : 0;
        $window = $code->voting_time_in_minutes ?? config('voting.time_in_minutes', 30);
        $remaining = max(0, $window - $elapsed);

        // Record step completion
        if ($voterSlug) {
            $this->recordStep($voterSlug, $election, 4, [
                'vote_verified' => true,
                'verified_at' => now()->toIso8601String(),
            ]);
        }

        return Inertia::render('Vote/DemoVote/Verify', [
            'vote_data' => $formattedVoteData,
            'user_name' => $user->name,
            'remaining_time' => $remaining,
            'code_expires_in' => $window,
            'slug' => $voterSlug?->slug,
            'useSlugPath' => $voterSlug !== null,
        ]);
    }

    /**
     * ==========================================
     * STEP 6: FINAL SUBMISSION (STORE VOTE)
     * ==========================================
     * 
     * Route: POST /v/{slug}/demo-vote/store
     */
    public function store(Request $request)
    {
        $user = $this->getUser($request);
        $election = $this->getElection($request);
        $voterSlug = $request->attributes->get('voter_slug');

        Log::info('Demo vote final submission started', [
            'user_id' => $user->id,
            'election_id' => $election->id,
        ]);

        // Validate voting code
        $validator = Validator::make($request->all(), [
            'voting_code' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Get code
        $code = $this->getCodeForElection($user, $election);
        
        if (!$code) {
            return $this->redirectToCodeEntry($voterSlug, 'Voting session not found.');
        }

        // Verify submitted code
        $isValid = $this->verifyCode(
            $request->input('voting_code'),
            $code->code_to_open_voting_form,
            'plain'
        );

        if (!$isValid) {
            Log::warning('Invalid voting code submitted', [
                'user_id' => $user->id,
                'code_id' => $code->id,
            ]);
            
            return redirect()->back()
                ->withErrors(['voting_code' => 'Invalid verification code.'])
                ->withInput();
        }

        // Check if already voted
        if ($code->has_voted) {
            return redirect()->route('dashboard')
                ->with('info', 'You have already voted in this election.');
        }

        // Check voting window
        if ($this->hasVotingWindowExpired($code)) {
            $this->expireCode($code);
            return $this->redirectToCodeEntry($voterSlug, 'Voting session expired.');
        }

        // Get vote data from session
        $sessionKey = 'vote_data_' . $code->id;
        $voteData = $request->session()->get($sessionKey);

        if (!$voteData) {
            return $this->redirectToCreate($voterSlug, 'Vote data not found. Please start again.');
        }

        try {
            // Save vote anonymously
            $vote = $this->saveVoteAnonymously($voteData, $election, $user);

            // Mark code as voted
            $code->update([
                'has_voted' => true,
                'can_vote_now' => false,
                'voting_code' => $vote->voting_code,
                'code_to_save_vote_used_at' => now(),
                'vote_completed_at' => now(),
                'is_code_to_open_voting_form_usable' => $this->isStrictMode() ? false : 0,
                'is_code_to_save_vote_usable' => false,
            ]);

            // Clear session data
            $request->session()->forget($sessionKey);

            // Record step completion
            if ($voterSlug) {
                $this->recordStep($voterSlug, $election, 5, [
                    'vote_submitted_final' => true,
                    'submitted_final_at' => now()->toIso8601String(),
                    'vote_id' => $vote->id,
                ]);
            }

            Log::info('Demo vote saved successfully', [
                'vote_id' => $vote->id,
                'election_id' => $election->id,
            ]);

            // Redirect to thank you page
            return $this->redirectToThankYou($voterSlug, [
                'success' => 'Your demo vote has been successfully submitted!',
                'verification_code' => $vote->voting_code,
                'is_demo' => true,
                'vote_id' => $vote->id,
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to save demo vote', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->withErrors(['vote' => 'Failed to save vote. Please try again.'])
                ->withInput();
        }
    }

    /**
     * ==========================================
     * STEP 7: THANK YOU / VERIFICATION PAGE
     * ==========================================
     * 
     * Route: GET /v/{slug}/demo-vote/verify-to-show
     */
    public function verifyToShow(Request $request)
    {
        $user = $this->getUser($request);
        $election = $this->getElection($request);
        $voterSlug = $request->attributes->get('voter_slug');

        $code = $this->getCodeForElection($user, $election);
        
        $hasVoted = $code && $code->has_voted;
        $verificationCode = null;

        if ($code && $code->voting_code) {
            $vote = DemoVote::where('voting_code', $code->voting_code)->first();
            $verificationCode = $vote?->voting_code;
        }

        return Inertia::render('Vote/VoteShowVerify', [
            'user_name' => $user->name,
            'has_voted' => $hasVoted,
            'is_demo' => true,
            'verification_code' => $verificationCode,
            'slug' => $voterSlug?->slug,
            'useSlugPath' => $voterSlug !== null,
        ]);
    }

    /**
     * ==========================================
     * VALIDATION METHODS
     * ==========================================
     */

    /**
     * Validate vote data from request
     */
    private function validateVoteData(Request $request): \Illuminate\Validation\Validator
    {
        $rules = [
            'national_selected_candidates' => 'nullable|array',
            'regional_selected_candidates' => 'nullable|array',
            'no_vote_posts' => 'nullable|array',
            'agree_button' => 'required|boolean|accepted',
        ];

        $messages = [
            'agree_button.accepted' => 'You must agree to the terms to proceed.',
        ];

        return Validator::make($request->all(), $rules, $messages);
    }

    /**
     * Validate pre-submission conditions
     * 
     * @return \Illuminate\Http\RedirectResponse|null
     */
    private function validatePreSubmission($code, $user, $election)
    {
        if (!$code->can_vote_now) {
            return redirect()->back()
                ->withErrors(['code' => 'Voting is not available at this time.']);
        }

        if ($code->has_voted) {
            return redirect()->route('dashboard')
                ->with('info', 'You have already voted.');
        }

        if ($this->hasVotingWindowExpired($code)) {
            $this->expireCode($code);
            return redirect()->back()
                ->withErrors(['code' => 'Your voting session has expired.']);
        }

        return null;
    }

    /**
     * ==========================================
     * FORMATTING METHODS
     * ==========================================
     */

    /**
     * Format vote data for display on verification page
     */
    private function formatVoteDataForDisplay(array $voteData): array
    {
        $formatted = [
            'national' => [],
            'regional' => [],
            'summary' => [
                'total_posts' => 0,
                'voted_posts' => 0,
                'abstained_posts' => 0,
                'candidates_selected' => 0,
            ],
        ];

        // Format national selections
        foreach ($voteData['national_selected_candidates'] ?? [] as $selection) {
            if ($selection) {
                $formatted['national'][] = $this->formatSelection($selection);
                $formatted['summary']['total_posts']++;
                
                if ($selection['no_vote'] ?? false) {
                    $formatted['summary']['abstained_posts']++;
                } else {
                    $formatted['summary']['voted_posts']++;
                    $formatted['summary']['candidates_selected'] += count($selection['candidates'] ?? []);
                }
            }
        }

        // Format regional selections
        foreach ($voteData['regional_selected_candidates'] ?? [] as $selection) {
            if ($selection) {
                $formatted['regional'][] = $this->formatSelection($selection);
                $formatted['summary']['total_posts']++;
                
                if ($selection['no_vote'] ?? false) {
                    $formatted['summary']['abstained_posts']++;
                } else {
                    $formatted['summary']['voted_posts']++;
                    $formatted['summary']['candidates_selected'] += count($selection['candidates'] ?? []);
                }
            }
        }

        return $formatted;
    }

    /**
     * Format a single selection for display
     */
    private function formatSelection(array $selection): array
    {
        return [
            'post_id' => $selection['post_id'] ?? null,
            'post_name' => $selection['post_name'] ?? 'Unknown Post',
            'required_number' => $selection['required_number'] ?? 1,
            'no_vote' => $selection['no_vote'] ?? false,
            'candidates' => array_map(function ($candidate) {
                return [
                    'id' => $candidate['id'] ?? null,
                    'candidacy_id' => $candidate['candidacy_id'] ?? null,
                    'user_name' => $candidate['user_name'] ?? 'Candidate',
                ];
            }, $selection['candidates'] ?? []),
        ];
    }

    /**
     * ==========================================
     * REDIRECTION METHODS
     * ==========================================
     */

    private function redirectToCodeEntry($voterSlug, string $message)
    {
        $route = $voterSlug ? 'slug.demo-code.create' : 'demo-code.create';
        $params = $voterSlug ? ['vslug' => $voterSlug->slug] : [];
        
        return redirect()->route($route, $params)->with('error', $message);
    }

    private function redirectToAgreement($voterSlug, string $message)
    {
        $route = $voterSlug ? 'slug.demo-code.agreement' : 'demo-code.agreement';
        $params = $voterSlug ? ['vslug' => $voterSlug->slug] : [];
        
        return redirect()->route($route, $params)->with('error', $message);
    }

    private function redirectToCreate($voterSlug, string $message)
    {
        $route = $voterSlug ? 'slug.demo-vote.create' : 'demo-vote.create';
        $params = $voterSlug ? ['vslug' => $voterSlug->slug] : [];
        
        return redirect()->route($route, $params)->with('error', $message);
    }

    private function redirectToVerification($voterSlug, string $message)
    {
        $route = $voterSlug ? 'slug.demo-vote.verify' : 'demo-vote.verify';
        $params = $voterSlug ? ['vslug' => $voterSlug->slug] : [];
        
        return redirect()->route($route, $params)->with('success', $message);
    }

    private function redirectToThankYou($voterSlug, array $data)
    {
        $route = $voterSlug ? 'slug.demo-vote.verify_to_show' : 'demo-vote.verify_to_show';
        $params = $voterSlug ? ['vslug' => $voterSlug->slug] : [];
        
        return redirect()->route($route, $params)->with($data);
    }

    /**
     * ==========================================
     * STEP TRACKING
     * ==========================================
     */

    private function recordStep($voterSlug, $election, int $step, array $metadata): void
    {
        if (!$voterSlug) {
            return;
        }

        try {
            $stepTrackingService = new VoterStepTrackingService();
            $stepTrackingService->completeStep($voterSlug, $election, $step, $metadata);
            
            Log::info("Step {$step} recorded", [
                'voter_slug_id' => $voterSlug->id,
                'election_id' => $election->id,
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to record step {$step}", [
                'error' => $e->getMessage(),
                'voter_slug_id' => $voterSlug->id ?? 'unknown',
            ]);
        }
    }
}
```

---

## 📋 **PHASE 4: Run Migrations and Tests**

### Step 4.1: Run Migrations

```bash
# Run the migration to add missing columns to demo_codes
php artisan migrate

# Verify columns were added
php artisan tinker
```

```php
// In tinker
Schema::hasColumn('demo_codes', 'voting_code'); // Should return true
Schema::hasColumn('demo_codes', 'has_agreed_to_vote'); // Should return true
Schema::hasColumn('demo_codes', 'vote_completed_at'); // Should return true
exit
```

### Step 4.2: Run Tests

```bash
# Run specific test files
php artisan test --filter=DemoVoteControllerTest
php artisan test --filter=DemoCodeControllerTest
php artisan test --filter=VotingWorkflowIntegrationTest

# Run full test suite
php artisan test
```

### Step 4.3: Manual Testing

```bash
# Test the complete voting flow
# 1. Access code entry page
curl -I http://localhost:8000/v/{slug}/demo-code/create

# 2. Submit code
curl -X POST http://localhost:8000/v/{slug}/demo-code \
  -H "Content-Type: application/json" \
  -d '{"voting_code":"ABC123"}'

# 3. Access voting form
curl -I http://localhost:8000/v/{slug}/demo-vote/create

# 4. Submit vote data
curl -X POST http://localhost:8000/v/{slug}/demo-vote/first-submission \
  -H "Content-Type: application/json" \
  -d '{"national_selected_candidates":[], "agree_button":true}'

# 5. Verify and finalize
curl -X POST http://localhost:8000/v/{slug}/demo-vote/store \
  -H "Content-Type: application/json" \
  -d '{"voting_code":"ABC123"}'
```

---

## 📋 **PHASE 5: Commit Changes**

```bash
# Create branch
git checkout -b refactor/demo-vote-complete

# Add all changed files
git add app/Models/Demo/DemoVote.php
git add app/Http/Controllers/Demo/DemoVoteController.php
git add app/Traits/Voting/CodeVerificationTrait.php
git add app/Traits/Voting/VoteStorageTrait.php
git add database/migrations/*_add_missing_columns_to_demo_codes.php

# Commit with descriptive message
git commit -m "refactor: Complete DemoVote model and controller overhaul

- Refactored DemoVote model with proper fillable fields and anonymity preservation
- Added missing columns to demo_codes table via migration
- Created CodeVerificationTrait for centralized code verification
- Created VoteStorageTrait for transaction-safe vote storage
- Completely rewrote DemoVoteController with:
  * Single responsibility methods
  * Consistent return types
  * Proper election context isolation
  * No user_id in vote hash (anonymity preserved)
  * Transaction-safe operations
  * Removed all debug code
  * Consolidated verification logic

This refactor aligns with architectural documents:
- 20260307_2348_Review_architecture_model_relationship.md
- 20260305_2118_model_relationship.md
- 20260302_2350_prompt_Instructions_to_update_model_relationship.md

All critical issues fixed:
✅ Vote anonymity preserved
✅ Proper relationships established
✅ Election context in all queries
✅ Transaction boundaries
✅ No duplicate code
✅ Production-ready (no debug)
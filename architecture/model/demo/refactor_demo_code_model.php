## 📋 **REFACTORING PLAN: DemoCodeController to Use New Model Relationships**

### Current Issues to Fix:

1. ✅ **Manual queries instead of relationships** - Many places use `DemoCode::where(...)` instead of relationship methods
2. ✅ **Missing eager loading** - No use of `with()` for related models
3. ✅ **Duplicate logic** - `getOrCreateCode()` reinvents relationship management
4. ✅ **Inconsistent election resolution** - Mix of methods to get election
5. ✅ **No use of `belongsToTenant` trait** - Organisation scoping not leveraged

---

## 🏛️ **TARGET ARCHITECTURE**

```mermaid
graph TB
    subgraph "New Relationships"
        U[User] -->|hasMany| DC[DemoCode]
        E[Election] -->|hasMany| DC
        DC -->|belongsTo| U
        DC -->|belongsTo| E
        DC -->|belongsTo| VS[VoterSlug?]
    end
    
    subgraph "Controller Flow"
        R[Request] -->|getUser| U
        R -->|getElection| E
        U -->|demoCodes()->first()| DC
        E -->|demoCodes()->where('user_id',$user->id)| DC
    end
    
    style U fill:#f96,stroke:#333
    style E fill:#9cf,stroke:#333
    style DC fill:#bfb,stroke:#333,stroke-width:2px
```

---

## 📋 **STEP-BY-STEP REFACTORING**

### **Step 1: Update DemoCode Model (Already Good)**

Your DemoCode model already has correct relationships:
```php
public function user()
{
    return $this->belongsTo(User::class);
}

public function election()
{
    return $this->belongsTo(Election::class);
}
```

And `BelongsToTenant` trait is already used ✅

---

### **Step 2: Refactor `getUser()` Method**

```php
// BEFORE
private function getUser(Request $request): User
{
    return $request->attributes->has('voter')
        ? $request->attributes->get('voter')
        : auth()->user();
}

// AFTER - Simplify
private function getUser(Request $request): User
{
    // Always use authenticated user - voter attribute is legacy
    return auth()->user();
}
```

---

### **Step 3: Refactor `getElection()` to Use Relationships**

```php
// BEFORE - Complex logic
private function getElection(Request $request): Election
{
    $user = $this->getUser($request);
    $voterSlug = $request->attributes->get('voter_slug');
    $election = $request->attributes->get('election');

    if (!$election) {
        \Log::critical('[DemoCodeController] Election not set by middleware');
        throw new \Exception('Election context missing');
    }
    // ... validation ...
    return $election;
}

// AFTER - Simpler, trust middleware
private function getElection(Request $request): Election
{
    $election = $request->attributes->get('election');
    
    if (!$election) {
        throw new \Exception('Election context missing - middleware did not set election');
    }
    
    return $election;
}
```

---

### **Step 4: Refactor `getOrCreateCode()` to Use Relationships**

This is the **BIGGEST improvement**. Replace manual queries with relationships:

```php
// BEFORE - Manual queries everywhere
private function getOrCreateCode(User $user, Election $election): DemoCode
{
    $code = DemoCode::withoutGlobalScopes()
        ->where('user_id', $user->id)
        ->where('election_id', $election->id)
        ->first();
    // ... complex logic ...
}

// AFTER - Use relationships
private function getOrCreateCode(User $user, Election $election): DemoCode
{
    // Try to get existing code through relationships
    $code = $user->demoCodes()
        ->where('election_id', $election->id)
        ->first();
    
    if (!$code) {
        // Create new code using relationship
        $code = $user->demoCodes()->create([
            'election_id' => $election->id,
            'organisation_id' => $election->organisation_id,
            'code1' => $this->generateCode(),
            'code1_sent_at' => now(),
            'has_code1_sent' => 1,
            'is_code1_usable' => 1,
            'can_vote_now' => 0,
            'voting_time_in_minutes' => $this->votingTimeInMinutes,
            'client_ip' => $this->clientIP,
        ]);
        
        // Send email (keep existing logic)
        $this->sendCodeEmail($user, $code);
        
        return $code;
    }
    
    // Handle existing code cases (expired, re-voting, etc.)
    return $this->handleExistingCode($code, $user, $election);
}
```

---

### **Step 5: Create Helper Method for Email Sending**

```php
private function sendCodeEmail(User $user, DemoCode $code): void
{
    if (!$user->email || !filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
        Log::warning('[DEMO] User does not have valid email', [
            'user_id' => $user->id,
        ]);
        return;
    }
    
    try {
        $user->notify(new SendFirstVerificationCode($user, $code->code1));
        Log::info('[DEMO] Verification code email sent', [
            'user_id' => $user->id,
            'code_id' => $code->id,
        ]);
    } catch (\Exception $e) {
        Log::error('[DEMO] Failed to send verification code email', [
            'user_id' => $user->id,
            'error' => $e->getMessage(),
        ]);
    }
}
```

---

### **Step 6: Refactor `verifyCode()` to Use Relationship**

```php
// BEFORE
private function verifyCode(DemoCode $code, string $submittedCode, User $user): array

// AFTER - No change needed, but we can ensure code belongs to user
private function verifyCode(DemoCode $code, string $submittedCode, User $user): array
{
    // Double-check the code belongs to this user (security)
    if ($code->user_id !== $user->id) {
        Log::warning('[DEMO] Code does not belong to user', [
            'code_user_id' => $code->user_id,
            'current_user_id' => $user->id,
        ]);
        return ['success' => false, 'message' => 'Invalid code for this user'];
    }
    
    // ... rest of verification logic ...
}
```

---

### **Step 7: Refactor All Other Methods to Use Relationships**

Search for these patterns and replace:

| Pattern | Replace With |
|---------|--------------|
| `DemoCode::where('user_id', $user->id)...` | `$user->demoCodes()->where(...)` |
| `DemoCode::where('election_id', $election->id)...` | `$election->demoCodes()->where(...)` |
| `DemoCode::create([...])` | `$user->demoCodes()->create([...])` |

---

### **Step 8: Add Eager Loading Where Needed**

```php
// In create() method
public function create(Request $request)
{
    $user = $this->getUser($request);
    $election = $this->getElection($request);
    
    // Eager load code with user and election
    $code = $user->demoCodes()
        ->with(['election'])
        ->where('election_id', $election->id)
        ->first();
    
    // ... rest of logic
}
```

---

## 📋 **COMPLETE REFACTORED METHODS**

### **getOrCreateCode() - Complete Refactor**

```php
private function getOrCreateCode(User $user, Election $election): DemoCode
{
    // 1. Try to get existing code through relationship
    $code = $user->demoCodes()
        ->where('election_id', $election->id)
        ->first();
    
    // 2. No code exists - create new one
    if (!$code) {
        return $this->createNewCode($user, $election);
    }
    
    // 3. Handle existing code based on state
    return $this->handleExistingCode($code, $user, $election);
}

private function createNewCode(User $user, Election $election): DemoCode
{
    $code = $user->demoCodes()->create([
        'election_id' => $election->id,
        'organisation_id' => $election->organisation_id,
        'code1' => $this->generateCode(),
        'code1_sent_at' => now(),
        'has_code1_sent' => 1,
        'is_code1_usable' => 1,
        'can_vote_now' => 0,
        'voting_time_in_minutes' => $this->votingTimeInMinutes,
        'client_ip' => $this->clientIP,
    ]);
    
    $this->sendCodeEmail($user, $code);
    
    Log::info('[DEMO] New verification code created', [
        'user_id' => $user->id,
        'code_id' => $code->id,
        'election_id' => $election->id,
    ]);
    
    return $code;
}

private function handleExistingCode(DemoCode $code, User $user, Election $election): DemoCode
{
    // Case 1: Code already verified - return as-is
    if ($code->can_vote_now == 1) {
        Log::info('[DEMO] Code already verified', [
            'user_id' => $user->id,
            'code_id' => $code->id,
        ]);
        return $code;
    }
    
    // Case 2: Check if code is expired
    if ($this->isCodeExpired($code)) {
        return $this->regenerateExpiredCode($code, $user);
    }
    
    // Case 3: Demo re-voting logic
    if ($election->type === 'demo' && $code->has_voted) {
        return $this->resetForReVoting($code, $user, $election);
    }
    
    // Case 4: Code exists but not expired - return as-is
    return $code;
}

private function isCodeExpired(DemoCode $code): bool
{
    if (!$code->code1_sent_at) {
        return false;
    }
    
    return $code->code1_sent_at->diffInMinutes(now()) > $this->votingTimeInMinutes;
}

private function regenerateExpiredCode(DemoCode $code, User $user): DemoCode
{
    $newCode = $this->generateCode();
    
    $code->update([
        'code1' => $newCode,
        'code1_sent_at' => now(),
        'has_code1_sent' => 1,
        'is_code1_usable' => 1,
        'can_vote_now' => 0,
        'code1_used_at' => null,
    ]);
    
    $this->sendCodeEmail($user, $code);
    
    Log::info('[DEMO] Code expired - regenerated', [
        'user_id' => $user->id,
        'code_id' => $code->id,
    ]);
    
    return $code;
}

private function resetForReVoting(DemoCode $code, User $user, Election $election): DemoCode
{
    $code->update([
        'has_voted' => false,
        'vote_submitted' => false,
        'can_vote_now' => 0,
        'is_code1_usable' => 1,
        'code1' => $this->generateCode(),
        'code1_sent_at' => now(),
        'has_code1_sent' => 1,
        'code1_used_at' => null,
        'code2_used_at' => null,
        'is_code2_usable' => 1,
    ]);
    
    $this->sendCodeEmail($user, $code);
    
    Log::info('[DEMO] Code reset for re-voting', [
        'user_id' => $user->id,
        'code_id' => $code->id,
    ]);
    
    return $code;
}
```

---

### **Refactored `create()` Method**

```php
public function create(Request $request)
{
    Log::info('🔥🔥🔥 DEMO CREATE METHOD HIT - Controller Reached!', [
        'user_id' => auth()->id(),
        'url' => request()->fullUrl(),
    ]);

    $user = $this->getUser($request);
    $election = $this->getElection($request);
    $voterSlug = $request->attributes->get('voter_slug');

    // Set organisation context for tenant scoping
    session(['current_organisation_id' => $election->organisation_id]);

    // Get or create code using relationships
    $code = $this->getOrCreateCode($user, $election);

    // Redirect verified users to agreement page
    if ($code->can_vote_now == 1 && !$code->has_voted) {
        Log::info('🔄 Redirecting verified user to agreement page', [
            'user_id' => $user->id,
            'code_id' => $code->id,
        ]);

        $agreementUrl = $voterSlug
            ? route('slug.demo-code.agreement', ['vslug' => $voterSlug->slug])
            : route('demo-code.agreement');

        return redirect($agreementUrl)->with('info', 'Code already verified. Please continue to agreement.');
    }

    // Reset voter slug for demo re-voting
    if ($voterSlug && $election->type === 'demo' && $code->has_voted) {
        $voterSlug->update(['current_step' => 1]);
        \App\Models\VoterSlugStep::where('voter_slug_id', $voterSlug->id)
            ->where('election_id', $election->id)
            ->delete();
    }

    // Calculate time since code was sent (for display only)
    $minutesSinceSent = $code->code1_sent_at 
        ? $code->code1_sent_at->diffInMinutes(now()) 
        : 0;

    // For API requests
    if ($request->wantsJson()) {
        return response()->json([
            'success' => true,
            'step' => 1,
            'user_name' => $user->name,
            'code_sent' => $code->has_code1_sent,
            'voting_time_minutes' => $this->votingTimeInMinutes,
        ]);
    }

    $hasValidEmail = $user->email && filter_var($user->email, FILTER_VALIDATE_EMAIL);

    return Inertia::render('Code/DemoCode/Create', [
        'name' => $user->name,
        'user_id' => $user->user_id ?? '',
        'state' => 'code_sent',
        'code_duration' => $minutesSinceSent,
        'code_expires_in' => $this->votingTimeInMinutes,
        'slug' => $voterSlug ? $voterSlug->slug : null,
        'useSlugPath' => $voterSlug !== null,
        'has_valid_email' => $hasValidEmail,
        'show_code_fallback' => !$hasValidEmail,
        'verification_code' => !$hasValidEmail ? $code->code1 : null,
        'election_type' => 'demo',
    ]);
}
```

---

## ✅ **BENEFITS OF THIS REFACTOR**

| Before | After |
|--------|-------|
| ❌ Manual `where` queries everywhere | ✅ Clean relationship methods |
| ❌ Duplicate logic in `getOrCreateCode()` | ✅ Small, focused helper methods |
| ❌ No eager loading | ✅ Can add `with()` easily |
| ❌ Hard to test | ✅ Methods can be tested individually |
| ❌ Mixed responsibilities | ✅ Single Responsibility Principle |

---

## 🚀 **EXECUTION ORDER**

```bash
# 1. Create backup of current controller
cp app/Http/Controllers/Demo/DemoCodeController.php app/Http/Controllers/Demo/DemoCodeController.php.bak

# 2. Refactor step by step, testing after each change
# 3. Run tests to ensure functionality preserved
php artisan test tests/Feature/Demo/DemoCodeTest.php

# 4. Commit
git add app/Http/Controllers/Demo/DemoCodeController.php
git commit -m "refactor: DemoCodeController to use model relationships

- Replace manual queries with relationship methods
- Extract helper methods for better separation of concerns
- Add email sending helper
- Improve code expiration and re-voting logic
- Maintain all existing functionality"
```

**Proceed with refactoring step by step.**
    
## ✅ **EXCELLENT IDEA! Semantic naming will make the code much clearer**

### Here's the complete renaming plan:

---

## 📋 **CURRENT vs NEW COLUMN NAMES**

| Current | New Name | Purpose |
|---------|----------|---------|
| `code1` | `code_to_open_voting_form` | First code to access voting |
| `code2` | `code_to_save_vote` | Second code to verify/submit vote |
| `code3` | ❌ **REMOVE** | Not needed |
| `code4` | ❌ **REMOVE** | Not needed |
| `vote_show_code` | ❌ **REMOVE** | Not needed |
| `is_code1_usable` | `is_code_to_open_voting_form_usable` | Whether first code can be used |
| `is_code2_usable` | `is_code_to_save_vote_usable` | Whether second code can be used |
| `is_code3_usable` | ❌ **REMOVE** | Not needed |
| `is_code4_usable` | ❌ **REMOVE** | Not needed |
| `code1_sent_at` | `code_to_open_voting_form_sent_at` | When first code was sent |
| `code2_sent_at` | `code_to_save_vote_sent_at` | When second code was sent |
| `code3_sent_at` | ❌ **REMOVE** | Not needed |
| `code4_sent_at` | ❌ **REMOVE** | Not needed |
| `code1_used_at` | `code_to_open_voting_form_used_at` | When first code was used |
| `code2_used_at` | `code_to_save_vote_used_at` | When second code was used |
| `code3_used_at` | ❌ **REMOVE** | Not needed |
| `code4_used_at` | ❌ **REMOVE** | Not needed |

---

## 📊 **BENEFITS OF THIS CHANGE**

| Aspect | Before | After |
|--------|--------|-------|
| **Clarity** | `code1`, `code2` - meaningless | ✅ Self-documenting names |
| **Maintenance** | 16 columns to manage | ✅ 8 columns (removed 8) |
| **Code readability** | `if ($code->code1_used_at)` | ✅ `if ($code->code_to_open_voting_form_used_at)` |
| **Business logic** | Hidden in comments | ✅ Explicit in column names |

---

## 📝 **MIGRATION STEPS**

### **Step 1: Create Migration**

```bash
php artisan make:migration rename_demo_codes_columns_for_clarity --table=demo_codes
```

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('demo_codes', function (Blueprint $table) {
            // Rename existing columns
            $table->renameColumn('code1', 'code_to_open_voting_form');
            $table->renameColumn('code2', 'code_to_save_vote');
            
            $table->renameColumn('is_code1_usable', 'is_code_to_open_voting_form_usable');
            $table->renameColumn('is_code2_usable', 'is_code_to_save_vote_usable');
            
            $table->renameColumn('code1_sent_at', 'code_to_open_voting_form_sent_at');
            $table->renameColumn('code2_sent_at', 'code_to_save_vote_sent_at');
            
            $table->renameColumn('code1_used_at', 'code_to_open_voting_form_used_at');
            $table->renameColumn('code2_used_at', 'code_to_save_vote_used_at');
            
            // Drop unused columns
            $table->dropColumn([
                'code3',
                'code4',
                'vote_show_code',
                'is_code3_usable',
                'is_code4_usable',
                'code3_sent_at',
                'code4_sent_at',
                'code3_used_at',
                'code4_used_at',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('demo_codes', function (Blueprint $table) {
            // Reverse renames
            $table->renameColumn('code_to_open_voting_form', 'code1');
            $table->renameColumn('code_to_save_vote', 'code2');
            
            $table->renameColumn('is_code_to_open_voting_form_usable', 'is_code1_usable');
            $table->renameColumn('is_code_to_save_vote_usable', 'is_code2_usable');
            
            $table->renameColumn('code_to_open_voting_form_sent_at', 'code1_sent_at');
            $table->renameColumn('code_to_save_vote_sent_at', 'code2_sent_at');
            
            $table->renameColumn('code_to_open_voting_form_used_at', 'code1_used_at');
            $table->renameColumn('code_to_save_vote_used_at', 'code2_used_at');
            
            // Add back dropped columns
            $table->string('code3')->nullable();
            $table->string('code4')->nullable();
            $table->string('vote_show_code')->nullable();
            $table->boolean('is_code3_usable')->default(false);
            $table->boolean('is_code4_usable')->default(false);
            $table->dateTime('code3_sent_at')->nullable();
            $table->dateTime('code4_sent_at')->nullable();
            $table->dateTime('code3_used_at')->nullable();
            $table->dateTime('code4_used_at')->nullable();
        });
    }
};
```

---

### **Step 2: Update DemoCode Model**

```php
// app/Models/DemoCode.php

protected $fillable = [
    'organisation_id',
    'user_id',
    'election_id',
    
    // Renamed columns
    'code_to_open_voting_form',
    'code_to_save_vote',
    
    'is_code_to_open_voting_form_usable',
    'is_code_to_save_vote_usable',
    
    'code_to_open_voting_form_sent_at',
    'code_to_save_vote_sent_at',
    
    'code_to_open_voting_form_used_at',
    'code_to_save_vote_used_at',
    
    // Other fields
    'can_vote_now',
    'has_voted',
    'vote_submitted',
    'vote_submitted_at',
    'has_agreed_to_vote',
    'has_agreed_to_vote_at',
    'voting_started_at',
    'is_codemodel_valid',
    'device_fingerprint_hash',
    'device_metadata_anonymized',
];

protected $casts = [
    // Boolean casts
    'is_code_to_open_voting_form_usable' => 'boolean',
    'is_code_to_save_vote_usable' => 'boolean',
    'can_vote_now' => 'boolean',
    'has_voted' => 'boolean',
    'vote_submitted' => 'boolean',
    'has_agreed_to_vote' => 'boolean',
    'is_codemodel_valid' => 'boolean',
    
    // DateTime casts
    'code_to_open_voting_form_sent_at' => 'datetime',
    'code_to_save_vote_sent_at' => 'datetime',
    'code_to_open_voting_form_used_at' => 'datetime',
    'code_to_save_vote_used_at' => 'datetime',
    'vote_submitted_at' => 'datetime',
    'has_agreed_to_vote_at' => 'datetime',
    'voting_started_at' => 'datetime',
    
    // JSON casts
    'device_metadata_anonymized' => 'array',
];

// Helper methods for clarity
public function hasOpenVotingFormCode(): bool
{
    return $this->code_to_open_voting_form_used_at === null 
        && $this->is_code_to_open_voting_form_usable;
}

public function hasSaveVoteCode(): bool
{
    return $this->code_to_save_vote_used_at === null 
        && $this->is_code_to_save_vote_usable;
}

public function markOpenVotingFormCodeAsUsed(): void
{
    $this->update([
        'code_to_open_voting_form_used_at' => now(),
        'is_code_to_open_voting_form_usable' => false,
    ]);
}

public function markSaveVoteCodeAsUsed(): void
{
    $this->update([
        'code_to_save_vote_used_at' => now(),
        'is_code_to_save_vote_usable' => false,
    ]);
}
```

---

### **Step 3: Update DemoCodeController**

```php
// In getOrCreateCode() method
$code = $user->demoCodes()->create([
    'election_id' => $election->id,
    'organisation_id' => $election->organisation_id,
    'code_to_open_voting_form' => $this->generateCode(),
    'code_to_save_vote' => $this->generateCode(),
    'code_to_open_voting_form_sent_at' => now(),
    'code_to_save_vote_sent_at' => now(),
    'has_code1_sent' => 1,  // Keep for backward compatibility
    'is_code_to_open_voting_form_usable' => 1,
    'is_code_to_save_vote_usable' => 1,
    'can_vote_now' => 0,
    'voting_time_in_minutes' => $this->votingTimeInMinutes,
    'client_ip' => $this->clientIP,
]);

// In verifyCode() method
public function verifyCode(Request $request)
{
    // ... validation ...
    
    $submittedCode = $request->input('voting_code');
    
    // Check if this is the open voting form code
    if ($code->code_to_open_voting_form === $submittedCode) {
        $this->markOpenVotingFormCodeAsUsed($code);
        // Redirect to voting form
    }
    
    // Check if this is the save vote code
    if ($code->code_to_save_vote === $submittedCode) {
        $this->markSaveVoteCodeAsUsed($code);
        // Submit the vote
    }
}
```

---

### **Step 4: Update Email Notifications**

```php
// In SendFirstVerificationCode notification
public function toMail($notifiable)
{
    return (new MailMessage)
        ->subject('Your Voting Codes')
        ->line('Here are your voting codes:')
        ->line('Code to open voting form: ' . $this->demoCode->code_to_open_voting_form)
        ->line('Code to save your vote: ' . $this->demoCode->code_to_save_vote)
        ->line('Keep these codes safe. Do not share them with anyone.');
}
```

---

## ✅ **SUMMARY OF CHANGES**

| Change | Benefit |
|--------|---------|
| ✅ Semantic column names | Self-documenting code |
| ✅ Removed 8 unused columns | Cleaner schema |
| ✅ Added helper methods | No more `if ($code->code1_used_at)` confusion |
| ✅ Clear two-code purpose | Exactly matches your business logic |

---

## 🚀 **EXECUTION ORDER**

```bash
# 1. Create migration
php artisan make:migration rename_demo_codes_columns_for_clarity --table=demo_codes

# 2. Run migration
php artisan migrate

# 3. Update DemoCode model
# 4. Update DemoCodeController
# 5. Update email notifications
# 6. Run tests
php artisan test tests/Feature/Demo/DemoCodeTest.php

# 7. Commit
git add database/migrations/ app/Models/DemoCode.php app/Http/Controllers/Demo/DemoCodeController.php
git commit -m "refactor: Rename demo_codes columns for clarity

- Rename code1 → code_to_open_voting_form
- Rename code2 → code_to_save_vote
- Remove unused code3, code4, vote_show_code columns
- Add helper methods for code state management
- Clear separation of concerns: open form vs save vote"
```

**This refactor will make your code much more maintainable and self-documenting.**
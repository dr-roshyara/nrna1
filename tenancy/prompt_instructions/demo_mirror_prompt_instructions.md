## 📋 **PROFESSIONAL PROMPT INSTRUCTIONS: Mirror Real Election System for Demo (1:1 Copy with Model Swap)**

```
## CONTEXT
We have a complete, working Real Election system with these components:

```
REAL ELECTION SYSTEM (PRODUCTION)
├── Controllers/
│   ├── CodeController.php (5 methods: create, store, showAgreement, submitAgreement)
│   ├── VoteController.php (5 methods: create, first_submission, verify, store, thankyou)
│   └── ResultController.php (index, show)
├── Models/
│   ├── Code.php (with BelongsToTenant)
│   ├── Vote.php (with BelongsToTenant) 
│   └── Result.php (with BelongsToTenant)
├── Vue Components (Pages/)
│   ├── Code/
│   │   ├── Create.vue
│   │   └── Agreement.vue
│   ├── Vote/
│   │   ├── Create.vue
│   │   ├── Verify.vue
│   │   └── Thankyou.vue
│   └── Result/
│       └── Show.vue
├── Routes (already exist in web.php)
└── Translations (in resources/js/i18n/*)
```

## REQUIREMENT
Create a **1:1 MIRROR COPY** of the entire Real Election system for Demo Elections, with these changes ONLY:

### WHAT TO COPY (KEEP 100% IDENTICAL)
- ✅ ALL controller logic (methods, validation, flow)
- ✅ ALL Vue component structure, styling, and behavior
- ✅ ALL translation keys and usage
- ✅ ALL route structures (just add `/demo` prefix)
- ✅ ALL service calls (VoterProgressService, VoterStepTrackingService, etc.)
- ✅ ALL middleware usage (ElectionMiddleware, etc.)

### WHAT TO CHANGE (MINIMAL DIFFERENCES)

| Real Election | Demo Election | Difference |
|--------------|---------------|------------|
| `Code.php` | **`DemoCode.php`** | Model name only |
| `Vote.php` | **`DemoVote.php`** | Model name only |
| `Result.php` | **`DemoResult.php`** | Model name only |
| `$user->can_vote` check | **REMOVE this check** | Demo allows all users |
| Organisation ID | **NULL for MODE 1, value for MODE 2** | Already handled by models |
| `has_voted` enforcement | **ALLOW re-voting** | Demo should reset |

## EXACT FILE MAPPING

### CONTROLLERS (Copy to app/Http/Controllers/Demo/)

| Source File | Destination File | Changes Needed |
|------------|-----------------|-----------------|
| `app/Http/Controllers/CodeController.php` | `app/Http/Controllers/Demo/DemoCodeController.php` | Replace all `Code::` with `DemoCode::`<br>Remove `$user->can_vote` checks<br>Add re-voting logic |
| `app/Http/Controllers/VoteController.php` | `app/Http/Controllers/Demo/DemoVoteController.php` | Replace all `Vote::` with `DemoVote::`<br>Replace `Result::` with `DemoResult::`<br>Remove `can_vote` checks<br>Add re-voting logic |
| `app/Http/Controllers/ResultController.php` | `app/Http/Controllers/Demo/DemoResultController.php` | Replace `Result::` with `DemoResult::` |

### VUE COMPONENTS (Copy to resources/js/Pages/Demo/)

| Source Path | Destination Path | Changes Needed |
|------------|-----------------|-----------------|
| `resources/js/Pages/Code/Create.vue` | `resources/js/Pages/DemoCode/Create.vue` | Change import paths<br>Add purple theme (optional)<br>Add mode indicator |
| `resources/js/Pages/Code/Agreement.vue` | `resources/js/Pages/DemoCode/Agreement.vue` | Change import paths<br>Add purple theme (optional) |
| `resources/js/Pages/Vote/Create.vue` | `resources/js/Pages/DemoVote/Create.vue` | Change import paths<br>Add purple theme (optional) |
| `resources/js/Pages/Vote/Verify.vue` | `resources/js/Pages/DemoVote/Verify.vue` | Change import paths<br>Add purple theme (optional) |
| `resources/js/Pages/Vote/Thankyou.vue` | `resources/js/Pages/DemoVote/Thankyou.vue` | Change import paths<br>Add purple theme (optional) |
| `resources/js/Pages/Result/Show.vue` | `resources/js/Pages/DemoResult/Show.vue` | Change import paths<br>Add purple theme (optional) |

### MODELS (Already exist or need creation)

| Real Model | Demo Model | Status |
|-----------|-----------|--------|
| `app/Models/Code.php` | `app/Models/DemoCode.php` | ✅ Create if not exists |
| `app/Models/Vote.php` | `app/Models/DemoVote.php` | ✅ Already exists |
| `app/Models/Result.php` | `app/Models/DemoResult.php` | ✅ Already exists |

## IMPLEMENTATION STEPS

### STEP 1: Create DemoCode Model (if not exists)
```php
// app/Models/DemoCode.php
// EXACT COPY of Code.php, just rename class and table

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class DemoCode extends Model
{
    use BelongsToTenant;
    
    protected $table = 'demo_codes';
    
    protected $fillable = [/* same as Code.php */];
    protected $casts = [/* same as Code.php */];
    
    // Relationships (same as Code.php)
    public function user() { return $this->belongsTo(User::class); }
    public function election() { return $this->belongsTo(Election::class); }
}
```

### STEP 2: Copy and Modify Controllers

```php
// app/Http/Controllers/Demo/DemoCodeController.php
// 1. Copy entire CodeController.php content
// 2. Replace all:
//    - `Code::` → `DemoCode::`
//    - `code` variable names → keep same
//    - `route('code.` → `route('demo.code.`
//    - `route('slug.code.` → `route('demo.slug.code.`
// 3. Remove ALL `$user->can_vote` checks
// 4. Add re-voting logic in getOrCreateCode()

// KEY DIFFERENCES TO ADD:

private function isUserEligible(User $user): bool
{
    // DEMO: All authenticated users are eligible
    return true;  // ← REMOVED can_vote check
}

private function getOrCreateCode(User $user, Election $election): DemoCode
{
    $code = DemoCode::withoutGlobalScopes()
        ->where('user_id', $user->id)
        ->where('election_id', $election->id)
        ->first();

    // DEMO: Allow re-voting by resetting
    if ($code && $code->has_voted) {
        Log::info('🔄 Demo election - resetting code for re-voting', [
            'user_id' => $user->id,
            'code_id' => $code->id,
        ]);

        $code->update([
            'has_voted' => false,
            'vote_submitted' => false,
            'can_vote_now' => 0,
            'is_code1_usable' => 1,
            'code1' => Str::random(6),
            'code1_sent_at' => now(),
            'has_code1_sent' => 1,
        ]);

        return $code;
    }

    // Rest of the method identical to CodeController...
}
```

### STEP 3: Copy and Modify Vue Components

For each Vue component:

```vue
<!-- resources/js/Pages/DemoCode/Create.vue -->
<!-- 1. Copy entire Code/Create.vue content -->
<!-- 2. Change import paths to use Demo components -->
<!-- 3. Add purple theme and mode indicator (optional) -->

<template>
    <election-layout>
        <!-- Add Demo Mode Indicator at top -->
        <div v-if="is_demo" class="bg-purple-100 border-l-4 border-purple-500 p-4 mb-4 max-w-3xl mx-auto rounded-lg">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                </svg>
                <div>
                    <p class="text-purple-900 font-medium">
                        {{ organisation_mode === 'org-specific' 
                            ? '🏢 Organisation-Specific Demo Mode' 
                            : '🌐 Public Demo Mode' }}
                    </p>
                    <p class="text-purple-700 text-sm mt-1">
                        {{ organisation_mode === 'org-specific'
                            ? 'Testing with your organisation\'s demo data'
                            : 'Testing with public demo data - visible to everyone' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Rest of the template EXACTLY as Code/Create.vue -->
        <!-- ... -->
    </election-layout>
</template>

<script>
import { useForm } from "@inertiajs/vue3-vue3";
import JetValidationErrors from "@/Jetstream/ValidationErrors";
import ElectionLayout from "@/Layouts/ElectionLayout";
import WorkflowStepIndicator from "@/Components/Workflow/WorkflowStepIndicator";

export default {
    props: {
        // Same props as Code/Create.vue PLUS:
        is_demo: Boolean,        // NEW
        organisation_mode: String, // NEW: 'public' or 'org-specific'
    },
    setup(props) {
        const form = useForm({ voting_code: "" });

        function submit() {
            let submitUrl;
            if (props.useSlugPath && props.slug) {
                submitUrl = `/demo/v/${props.slug}/code`;  // ← DEMO PREFIX
            } else {
                submitUrl = "/demo/codes";  // ← DEMO PREFIX
            }
            form.post(submitUrl);
        }

        return { form, submit };
    },
    // Rest of component EXACTLY as Code/Create.vue
};
</script>
```

### STEP 4: Create DemoVoteController (Copy from VoteController)

```php
// app/Http/Controllers/Demo/DemoVoteController.php
// 1. Copy entire VoteController.php
// 2. Replace:
//    - `Vote::` → `DemoVote::`
//    - `Result::` → `DemoResult::`
//    - `Code::` → `DemoCode::` (if referenced)
//    - `route('vote.` → `route('demo.vote.`
//    - `route('slug.vote.` → `route('demo.slug.vote.`
// 3. Remove ALL `$user->can_vote` checks
// 4. Add re-voting logic in appropriate places

// KEY DIFFERENCE in save_vote method:
public function save_vote($input_data, $hashed_voting_key, $election = null, $auth_user = null, $private_key = null)
{
    // Determine which model to use based on election type
    $voteModel = $election->type === 'demo' ? DemoVote::class : Vote::class;
    $resultModel = $election->type === 'demo' ? DemoResult::class : Result::class;
    
    // DEMO: Allow re-voting - delete previous votes for this user/election
    if ($election->type === 'demo') {
        $existingVotes = DemoVote::where('election_id', $election->id)
            ->where('user_id', $auth_user->id)
            ->get();
            
        foreach ($existingVotes as $existingVote) {
            DemoResult::where('vote_id', $existingVote->id)->delete();
            $existingVote->delete();
        }
        
        Log::info('🔄 Demo election - cleared previous votes for re-voting', [
            'user_id' => $auth_user->id,
            'election_id' => $election->id,
            'votes_deleted' => $existingVotes->count(),
        ]);
    }
    
    // Rest of method identical to VoteController...
}
```

### STEP 5: Create DemoResultController (Simple Copy)

```php
// app/Http/Controllers/Demo/DemoResultController.php
// 1. Copy ResultController.php
// 2. Replace `Result::` with `DemoResult::`
// 3. Replace `route('results.` with `route('demo.results.`
```

### STEP 6: Update Routes (Already Exist - Just Verify)

```php
// routes/web.php - These routes should already exist
// If not, add them:

Route::prefix('demo')->name('demo.')->group(function () {
    // Demo Code Routes
    Route::get('/v/{vslug}/code/create', [DemoCodeController::class, 'create'])->name('slug.code.create');
    Route::post('/v/{vslug}/code', [DemoCodeController::class, 'store'])->name('slug.code.store');
    Route::get('/v/{vslug}/vote/agreement', [DemoCodeController::class, 'showAgreement'])->name('slug.code.agreement');
    Route::post('/v/{vslug}/code/agreement', [DemoCodeController::class, 'submitAgreement'])->name('slug.code.agreement.submit');
    Route::get('/code/create', [DemoCodeController::class, 'create'])->name('code.create');
    Route::post('/codes', [DemoCodeController::class, 'store'])->name('code.store');
    Route::get('/code/agreement', [DemoCodeController::class, 'showAgreement'])->name('code.agreement');
    Route::post('/code/agreement', [DemoCodeController::class, 'submitAgreement'])->name('code.agreement.submit');

    // Demo Vote Routes
    Route::get('/v/{vslug}/vote/create', [DemoVoteController::class, 'create'])->name('slug.vote.create');
    Route::post('/v/{vslug}/vote/submit', [DemoVoteController::class, 'first_submission'])->name('slug.vote.submit');
    Route::get('/v/{vslug}/vote/verify', [DemoVoteController::class, 'verify'])->name('slug.vote.verify');
    Route::post('/votes', [DemoVoteController::class, 'store'])->name('vote.store');
    Route::get('/vote/create', [DemoVoteController::class, 'create'])->name('vote.create');
    Route::post('/vote/first-submission', [DemoVoteController::class, 'first_submission'])->name('vote.first_submission');
    Route::get('/vote/verify', [DemoVoteController::class, 'verify'])->name('vote.verify');
    Route::get('/vote/thankyou/{vote}', [DemoVoteController::class, 'thankyou'])->name('vote.thankyou');

    // Demo Result Routes
    Route::get('/results/{election}', [DemoResultController::class, 'index'])->name('results.index');
});
```

### STEP 7: Create Comprehensive Tests

```php
// tests/Feature/DemoMirrorTest.php

public function test_demo_code_flow_mirrors_real_code_flow()
{
    $user = User::factory()->create();
    $demoElection = Election::factory()->create(['type' => 'demo']);
    
    // Step 1: Access code page
    $response = $this->actingAs($user)->get('/demo/code/create');
    $response->assertStatus(200);
    
    // Get code from database
    $code = DemoCode::where('user_id', $user->id)
        ->where('election_id', $demoElection->id)
        ->first();
    $this->assertNotNull($code);
    
    // Step 2: Submit code
    $response = $this->actingAs($user)
        ->post('/demo/codes', ['voting_code' => $code->code1]);
    $response->assertRedirect();
    
    // Step 3: Access agreement page
    $response = $this->actingAs($user)->get('/demo/code/agreement');
    $response->assertStatus(200);
    
    // Step 4: Submit agreement
    $response = $this->actingAs($user)
        ->post('/demo/code/agreement', ['agreement' => true]);
    $response->assertRedirect();
}

public function test_demo_allows_revoting_while_real_does_not()
{
    $user = User::factory()->create();
    $demoElection = Election::factory()->create(['type' => 'demo']);
    
    // First vote
    $this->actingAs($user)->get('/demo/code/create');
    $code = DemoCode::where('user_id', $user->id)->first();
    $this->actingAs($user)->post('/demo/codes', ['voting_code' => $code->code1]);
    $this->actingAs($user)->post('/demo/code/agreement', ['agreement' => true]);
    $this->actingAs($user)->post('/demo/vote/first-submission', ['selections' => [1]]);
    $this->actingAs($user)->post('/demo/votes', ['selections' => [1]]);
    
    // Should be able to vote again
    $this->actingAs($user)->get('/demo/code/create');
    $newCode = DemoCode::where('user_id', $user->id)->first();
    $this->assertNotEquals($code->code1, $newCode->code1); // New code generated
    
    $response = $this->actingAs($user)->post('/demo/codes', ['voting_code' => $newCode->code1]);
    $response->assertRedirect(); // Success - allowed to vote again
}

public function test_demo_mode_indicator_appears_in_ui()
{
    $user = User::factory()->create();
    $demoElection = Election::factory()->create(['type' => 'demo']);
    
    $response = $this->actingAs($user)->get('/demo/code/create');
    
    $response->assertInertia(fn ($page) => $page
        ->component('DemoCode/Create')
        ->where('is_demo', true)
        ->has('organisation_mode')
    );
}

public function test_demo_uses_demo_models_not_real_models()
{
    $user = User::factory()->create();
    $demoElection = Election::factory()->create(['type' => 'demo']);
    
    $this->actingAs($user)->get('/demo/code/create');
    
    // Should create DemoCode, not Code
    $this->assertDatabaseHas('demo_codes', ['user_id' => $user->id]);
    $this->assertDatabaseMissing('codes', ['user_id' => $user->id]);
    
    // Complete voting flow
    $code = DemoCode::where('user_id', $user->id)->first();
    $this->actingAs($user)->post('/demo/codes', ['voting_code' => $code->code1]);
    $this->actingAs($user)->post('/demo/code/agreement', ['agreement' => true]);
    $this->actingAs($user)->post('/demo/vote/first-submission', ['selections' => [1]]);
    $this->actingAs($user)->post('/demo/votes', ['selections' => [1]]);
    
    // Should create DemoVote and DemoResult, not real ones
    $this->assertDatabaseHas('demo_votes', ['user_id' => $user->id]);
    $this->assertDatabaseMissing('votes', ['user_id' => $user->id]);
    $this->assertDatabaseHas('demo_results', []);
    $this->assertDatabaseMissing('results', []);
}

public function test_demo_removes_can_vote_check()
{
    // Create user with can_vote = 0
    $user = User::factory()->create(['can_vote' => 0]);
    $demoElection = Election::factory()->create(['type' => 'demo']);
    
    // Should still be able to access demo
    $response = $this->actingAs($user)->get('/demo/code/create');
    $response->assertStatus(200);
    
    // Real election would block this user
    $realElection = Election::factory()->create(['type' => 'real']);
    $response = $this->actingAs($user)->get('/code/create');
    $response->assertRedirect(); // Real election redirects
}

public function test_demo_mode2_respects_organisation_id()
{
    // MODE 2: User with organisation
    $organisation = Organisation::factory()->create();
    $user = User::factory()->create([
        'organisation_id' => $organisation->id,
        'can_vote' => 1
    ]);
    
    $orgDemoElection = Election::factory()->create([
        'type' => 'demo',
        'organisation_id' => $organisation->id
    ]);
    
    $this->actingAs($user);
    session(['current_organisation_id' => $organisation->id]);
    
    $this->get('/demo/code/create');
    
    // Code should have organisation_id = user's org
    $code = DemoCode::where('user_id', $user->id)->first();
    $this->assertEquals($organisation->id, $code->organisation_id);
}
```

## SUMMARY OF DIFFERENCES TABLE

| Aspect | Real Election | Demo Election | Implementation |
|--------|--------------|---------------|----------------|
| **Controllers** | CodeController | **DemoCodeController** | Copy + replace model names |
| | VoteController | **DemoVoteController** | Copy + replace model names |
| | ResultController | **DemoResultController** | Copy + replace model names |
| **Models** | Code | **DemoCode** | Create if not exists |
| | Vote | **DemoVote** | Already exists |
| | Result | **DemoResult** | Already exists |
| **Vue Pages** | Code/Create.vue | **DemoCode/Create.vue** | Copy + add purple theme + mode indicator |
| | Code/Agreement.vue | **DemoCode/Agreement.vue** | Copy + add purple theme |
| | Vote/Create.vue | **DemoVote/Create.vue** | Copy + add purple theme |
| | Vote/Verify.vue | **DemoVote/Verify.vue** | Copy + add purple theme |
| | Vote/Thankyou.vue | **DemoVote/Thankyou.vue** | Copy + add purple theme |
| | Result/Show.vue | **DemoResult/Show.vue** | Copy + add purple theme |
| **User Eligibility** | `$user->can_vote` check | **REMOVE check** | All users allowed |
| **Re-voting** | Blocked | **ALLOWED** | Reset code/vote records |
| **Routes** | /code/* | **/demo/code/*** | Already exist |
| | /vote/* | **/demo/vote/*** | Already exist |
| | /results/* | **/demo/results/*** | Already exist |
| **Translations** | Same keys | **SAME KEYS** | Use existing i18n |
| **Multi-tenancy** | organisation_id required | **NULL (MODE 1) or value (MODE 2)** | Handled by models |

## DELIVERABLES CHECKLIST

- [ ] `app/Models/DemoCode.php` created (copy of Code.php)
- [ ] `app/Http/Controllers/Demo/DemoCodeController.php` created
- [ ] `app/Http/Controllers/Demo/DemoVoteController.php` created
- [ ] `app/Http/Controllers/Demo/DemoResultController.php` created
- [ ] `resources/js/Pages/DemoCode/Create.vue` created
- [ ] `resources/js/Pages/DemoCode/Agreement.vue` created
- [ ] `resources/js/Pages/DemoVote/Create.vue` created
- [ ] `resources/js/Pages/DemoVote/Verify.vue` created
- [ ] `resources/js/Pages/DemoVote/Thankyou.vue` created
- [ ] `resources/js/Pages/DemoResult/Show.vue` created
- [ ] All `can_vote` checks removed from demo controllers
- [ ] Re-voting logic added to demo controllers
- [ ] Purple theme and mode indicators added to Vue components
- [ ] All 6 tests passing

## COMMAND SUMMARY

```bash
# 1. Create DemoCode model
cp app/Models/Code.php app/Models/DemoCode.php
# Edit to change class name and table

# 2. Create controller directory
mkdir -p app/Http/Controllers/Demo

# 3. Copy controllers
cp app/Http/Controllers/CodeController.php app/Http/Controllers/Demo/DemoCodeController.php
cp app/Http/Controllers/VoteController.php app/Http/Controllers/Demo/DemoVoteController.php
cp app/Http/Controllers/ResultController.php app/Http/Controllers/Demo/DemoResultController.php

# 4. Create Vue directories
mkdir -p resources/js/Pages/DemoCode
mkdir -p resources/js/Pages/DemoVote
mkdir -p resources/js/Pages/DemoResult

# 5. Copy Vue components
cp resources/js/Pages/Code/Create.vue resources/js/Pages/DemoCode/Create.vue
cp resources/js/Pages/Code/Agreement.vue resources/js/Pages/DemoCode/Agreement.vue
cp resources/js/Pages/Vote/Create.vue resources/js/Pages/DemoVote/Create.vue
cp resources/js/Pages/Vote/Verify.vue resources/js/Pages/DemoVote/Verify.vue
cp resources/js/Pages/Vote/Thankyou.vue resources/js/Pages/DemoVote/Thankyou.vue
cp resources/js/Pages/Result/Show.vue resources/js/Pages/DemoResult/Show.vue

# 6. Create tests
cp tests/Feature/CodeControllerTest.php tests/Feature/DemoCodeControllerTest.php
# Edit to test demo behavior

# 7. Run tests
php artisan test --filter=DemoMirrorTest
```

The goal is **1:1 mirroring** - the demo system should behave EXACTLY like the real system, with only the model names, eligibility checks, and re-voting behavior differing. Everything else (UI, translations, flow, validation) must be identical.
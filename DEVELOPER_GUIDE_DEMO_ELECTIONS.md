# 📚 Developer Guide: Demo Elections

**Version**: 1.1
**Last Updated**: 2026-02-20
**Status**: Complete

---

## 🔄 Recent Updates (2026-02-20)

### Fixed: Candidates Not Displaying on Voting Form

Three critical issues were fixed to ensure candidates display correctly:

#### 1. Vue Component Props Structure
- **File**: `resources/js/Pages/Vote/DemoVote/CreateVotingPage.vue`
- **Issue**: Component expected single `posts` prop but received `national_posts` and `regional_posts` separately
- **Fix**: Updated props to accept both arrays and added computed property to combine them
- **Impact**: ✅ Candidates now display correctly on voting form

#### 2. DemoVoteController Query
- **File**: `app/Http/Controllers/Demo/DemoVoteController.php` (Lines 248, 320)
- **Issue**: Querying `Post` table instead of `DemoPost` for demo elections
- **Fix**: Changed `Post::where('is_national_wide', 1)` to `DemoPost::where('election_id', $election->id)`
- **Impact**: ✅ Correct candidate matching with DemoCandidacy records

#### 3. Model Name Correction
- **File**: `app/Http/Controllers/VoteController.php` (Line 225)
- **Issue**: Using non-existent model `DemoCandidate::`
- **Fix**: Changed to correct model name `DemoCandidacy::`
- **Impact**: ✅ Prevents "Class not found" errors

### Tests Passing
- ✅ Demo candidate creation test
- ✅ Complete demo voting flow (Mode 1 & 2)
- ✅ 15/17 mirror system tests

### Documentation Updates
- Added detailed candidate fetching logic in Controllers section
- Added Vue component props documentation in new Vue Components section
- Added troubleshooting guide for "Candidates Not Displaying" issue
- Added complete data structure examples

---

## Table of Contents

1. [Overview](#overview)
2. [Architecture](#architecture)
3. [5-Step Voting Process](#5-step-voting-process)
4. [Key Differences from Real Elections](#key-differences)
5. [Database Structure](#database-structure)
6. [Controllers](#controllers)
7. [Models](#models)
8. [Vue Components](#vue-components)
9. [Routes](#routes)
10. [API Endpoints](#api-endpoints)
11. [Setting Up Demo Elections](#setting-up-demo-elections)
12. [Testing Demo Elections](#testing-demo-elections)
13. [Code Examples](#code-examples)
14. [Troubleshooting](#troubleshooting)

---

## Overview

### Purpose

The **Demo Election System** provides a safe, isolated environment for testing the election voting workflow without affecting real election data. It allows:

- Organizations to test voting interfaces before live elections
- Administrators to verify system functionality
- Users without voting privileges (`can_vote=0`) to participate
- Unlimited re-voting for testing different scenarios
- Complete data isolation from production elections

### Key Principles

1. **1:1 Mirror**: Demo system mirrors real election architecture exactly
2. **Isolation**: Demo data is completely separate from real elections
3. **Safety**: No voting restrictions or auditing requirements
4. **Flexibility**: Multiple demo elections can exist per organization
5. **Testability**: Designed for comprehensive testing workflows

---

## Architecture

### System Overview

```
Organization
    └── Demo Election (separate from Real Elections)
        ├── DemoPost (election positions)
        │   └── DemoCandidacy (candidates per position)
        ├── DemoCode (voting access codes - no can_vote check)
        ├── DemoVote (submitted votes - anonymous)
        └── DemoResult (vote tallies per candidate)
```

### Namespace Organization

```
app/
├── Http/Controllers/
│   ├── Demo/
│   │   ├── DemoCodeController.php
│   │   ├── DemoVoteController.php
│   │   └── DemoResultController.php
│   └── Api/
│       └── DemoSetupController.php
├── Models/
│   ├── DemoCode.php
│   ├── DemoVote.php
│   ├── DemoResult.php
│   ├── DemoCandidacy.php
│   └── DemoPost.php
└── Services/
    └── DemoVotingService.php (if custom logic needed)

resources/js/Pages/
├── Code/DemoCode/
│   ├── Create.vue (Step 1)
│   └── Agreement.vue (Step 2)
├── Vote/DemoVote/
│   ├── CreateVotingPage.vue (Step 3)
│   ├── CreateVotingform.vue (reusable component)
│   ├── Verify.vue (Step 4)
│   ├── ThankYou.vue (Step 5)
│   └── Agreement.vue (if needed)
└── Organizations/Partials/
    └── DemoSetupButton.vue (admin UI)
```

### Design Pattern: Separate Demo Namespace

**Why separate controllers in `Demo` namespace?**

- Prevents class name conflicts
- Makes it clear which code path is demo vs. real
- Easier to maintain independent feature sets
- Simplifies debugging and monitoring
- Allows different authorization logic

**Key Example**:
```php
// Real elections - in CodeController
if (!$user->can_vote) {
    abort(403, 'You cannot vote');
}

// Demo elections - in DemoCodeController
// No can_vote check - always allows
$code = DemoCode::create([...]);
```

---

## 5-Step Voting Process

The demo election follows the **exact same 5-step process** as real elections.

### Step 1: Code Verification (GET /demo/code/create)

**Controller**: `DemoCodeController::create()`
**Component**: `Code/DemoCode/Create.vue`
**Database**: Creates DemoCode record

**Flow**:
1. User visits `/demo/code/create`
2. System creates DemoCode with two generated codes (code1, code2)
3. code1 is displayed to user for verification
4. User enters code1 to proceed

**Key Code**:
```php
public function create(Request $request)
{
    $election = $this->getElectionOrRedirect();

    // Create or retrieve demo code
    $demoCode = DemoCode::firstOrCreate(
        [
            'user_id' => auth()->id(),
            'election_id' => $election->id
        ],
        [
            'code1' => $this->generateCode(),
            'code2' => $this->generateCode(),
            'can_vote_now' => 0,
            'is_code1_usable' => 1
        ]
    );

    return inertia('Code/DemoCode/Create', [
        'code1' => $demoCode->code1,
        'election_type' => 'demo'
    ]);
}
```

### Step 2: Agreement Acceptance (POST /demo/code/agreement)

**Controller**: `DemoCodeController::submitAgreement()`
**Component**: `Code/DemoCode/Agreement.vue`
**Database**: Updates DemoCode.has_agreed_to_vote

**Flow**:
1. User reviews voting agreement
2. User checks "I agree" checkbox
3. System marks code as agreed
4. User proceeds to voting

**Key Code**:
```php
public function submitAgreement(Request $request)
{
    $demoCode = $this->getDemoCodeOrFail();

    if (!$request->agreement) {
        return back()->withErrors(['agreement' => 'Required']);
    }

    $demoCode->update(['has_agreed_to_vote' => 1]);

    // Track step
    VoterStepTrackingService::recordStep(
        $request->voter_slug,
        2,
        'demo'
    );

    return redirect(route('demo-vote.create'));
}
```

### Step 3: Vote Submission (POST /demo/vote/submit)

**Controller**: `DemoVoteController::firstSubmission()`
**Component**: `Vote/DemoVote/CreateVotingPage.vue`
**Database**: Stored in session (not yet persisted)

**Flow**:
1. User selects candidates for all positions
2. Form validates selection count
3. Votes stored temporarily in session
4. User proceeds to verification

**Key Code**:
```php
public function firstSubmission(Request $request)
{
    $validated = $request->validate([
        'votes' => 'required|array',
        'votes.*' => 'required|exists:demo_candidacies,id'
    ]);

    // Store in session
    session(['demo_votes' => $validated['votes']]);

    VoterStepTrackingService::recordStep(
        $request->voter_slug,
        3,
        'demo'
    );

    return redirect(route('demo-vote.verify'));
}
```

### Step 4: Vote Verification (GET /demo/vote/verify)

**Controller**: `DemoVoteController::verify()`
**Component**: `Vote/DemoVote/Verify.vue`
**Database**: Reads from session

**Flow**:
1. System retrieves votes from session
2. Displays selected candidates with their details
3. User reviews before final submission
4. User can go back to edit or proceed to submit

**Key Code**:
```php
public function verify(Request $request)
{
    $votes = session('demo_votes', []);

    $selectedCandidates = DemoCandidacy::find($votes);

    VoterStepTrackingService::recordStep(
        $request->voter_slug,
        4,
        'demo'
    );

    return inertia('Vote/DemoVote/Verify', [
        'candidates' => $selectedCandidates,
        'election_type' => 'demo'
    ]);
}
```

### Step 5: Final Submission (POST /demo/vote/final)

**Controller**: `DemoVoteController::store()`
**Component**: Redirect to thank you
**Database**: Creates DemoVote and DemoResult records

**Flow**:
1. System persists votes to DemoVote table (ANONYMOUS - no user_id)
2. System updates DemoResult vote tallies
3. Session votes cleared
4. User redirected to thank you page

**Key Code**:
```php
public function store(Request $request)
{
    $votes = session('demo_votes', []);

    foreach ($votes as $candidacy_id) {
        DemoVote::create([
            'election_id' => $election->id,
            'candidacy_id' => $candidacy_id,
            // Note: NO user_id for anonymity
            'marked_at' => now(),
            'in_code' => $this->getInCode(),
            'out_code' => $this->getOutCode()
        ]);

        // Update result tallies
        DemoResult::updateOrCreate(
            ['candidacy_id' => $candidacy_id],
            ['votes' => DB::raw('votes + 1')]
        );
    }

    VoterStepTrackingService::recordStep(
        $request->voter_slug,
        5,
        'demo'
    );

    session()->forget('demo_votes');

    return redirect(route('demo-vote.thank-you'));
}
```

### Step 6: Thank You Page (GET /demo/vote/thank-you)

**Controller**: `DemoVoteController::thankYou()`
**Component**: `Vote/DemoVote/ThankYou.vue`
**Database**: Read-only

**Flow**:
1. Display success message
2. Show vote summary
3. Offer option to vote again or return to dashboard

---

## Key Differences

### Demo Election vs. Real Election

| Feature | Real Elections | Demo Elections |
|---------|---|---|
| **can_vote Check** | ✅ Required | ❌ **REMOVED** |
| **Re-voting** | ❌ Blocked | ✅ **ALLOWED** |
| **IP Rate Limiting** | ✅ Yes (7/IP) | ❌ **DISABLED** |
| **Database** | `codes`, `votes`, `results` | `demo_codes`, `demo_votes`, `demo_results` |
| **Controllers** | `CodeController`, `VoteController`, `ResultController` | `Demo/DemoCodeController`, `Demo/DemoVoteController`, `Demo/DemoResultController` |
| **Anonymous Voting** | ✅ Yes (no user_id) | ✅ Yes (no user_id) |
| **Email Notifications** | ✅ Sent | ✅ **Sent** (optional fallback) |
| **Step Tracking** | ✅ Via VoterStepTrackingService | ✅ **Same service** |
| **Audit Trail** | ✅ Voter audit log | ✅ Admin audit only |

### Implementation Locations

**can_vote Check Removed**:
```php
// REAL Elections - CodeController.php:539
if (!auth()->user()->can_vote) {
    abort(403, 'Not eligible');
}

// DEMO Elections - DemoCodeController.php:539
// Check REMOVED - always allows
```

**Re-voting Logic Added**:
```php
// DEMO Elections - DemoCodeController.php:568-604
if ($demoCode->has_voted) {
    // Reset code for re-voting
    $demoCode->update([
        'can_vote_now' => 0,
        'has_voted' => 0,
        'has_agreed_to_vote' => 0
    ]);
}
```

**IP Limiting Disabled**:
```php
// REAL Elections - CodeController.php:741
if (VoterIpLimit::isExceeded($ip)) {
    abort(429, 'Too many requests');
}

// DEMO Elections - DemoCodeController.php:741
// Check REMOVED - no IP limiting
```

---

## Database Structure

### DemoCode Table

```sql
CREATE TABLE demo_codes (
    id BIGINT PRIMARY KEY,
    user_id BIGINT FOREIGN KEY,
    election_id BIGINT FOREIGN KEY,
    code1 VARCHAR(255) UNIQUE,        -- Verification code 1
    code2 VARCHAR(255) UNIQUE,        -- Verification code 2
    code1_sent_at TIMESTAMP,
    code2_sent_at TIMESTAMP,
    can_vote_now BOOLEAN DEFAULT 0,   -- Code verified?
    is_code1_usable BOOLEAN DEFAULT 1,
    is_code2_usable BOOLEAN DEFAULT 1,
    has_agreed_to_vote BOOLEAN DEFAULT 0,
    has_voted BOOLEAN DEFAULT 0,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### DemoVote Table

```sql
CREATE TABLE demo_votes (
    id BIGINT PRIMARY KEY,
    election_id BIGINT FOREIGN KEY,
    candidacy_id BIGINT FOREIGN KEY,
    -- Note: NO user_id for anonymity
    marked_at TIMESTAMP,
    in_code VARCHAR(255),
    out_code VARCHAR(255),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### DemoResult Table

```sql
CREATE TABLE demo_results (
    id BIGINT PRIMARY KEY,
    election_id BIGINT FOREIGN KEY,
    candidacy_id BIGINT FOREIGN KEY,
    votes INT DEFAULT 0,              -- Vote count
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### DemoPost Table

```sql
CREATE TABLE demo_posts (
    id BIGINT PRIMARY KEY,
    election_id BIGINT FOREIGN KEY,
    post_id BIGINT FOREIGN KEY,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### DemoCandidacy Table

```sql
CREATE TABLE demo_candidacies (
    id BIGINT PRIMARY KEY,
    election_id BIGINT FOREIGN KEY,
    post_id BIGINT FOREIGN KEY,
    user_id BIGINT FOREIGN KEY,
    position_order INT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### Relationships

```
DemoCode
  ├── belongs_to User
  └── belongs_to Election

DemoVote
  ├── belongs_to Election
  ├── belongs_to DemoCandidacy
  └── has_many DemoResult

DemoResult
  ├── belongs_to Election
  ├── belongs_to DemoCandidacy
  └── belongs_to DemoPost

DemoPost
  ├── belongs_to Election
  ├── has_many DemoCandidacy
  └── has_many DemoResult

DemoCandidacy
  ├── belongs_to Election
  ├── belongs_to Post
  ├── belongs_to User
  ├── has_many DemoVote
  └── has_many DemoResult
```

---

## Controllers

### DemoCodeController

**File**: `app/Http/Controllers/Demo/DemoCodeController.php`
**Lines**: 618
**Purpose**: Manage demo voting code lifecycle

**Methods**:

| Method | Route | Purpose |
|--------|-------|---------|
| `create()` | GET `/demo/code/create` | Display code entry form |
| `store()` | POST `/demo/codes` | Verify code |
| `showAgreement()` | GET `/demo/code/agreement` | Display agreement |
| `submitAgreement()` | POST `/demo/code/agreement` | Accept agreement |

**Key Features**:
- Creates DemoCode on first access
- No can_vote eligibility check
- Allows re-voting by resetting code
- Email notifications with fallback display
- Step tracking integration

### DemoVoteController

**File**: `app/Http/Controllers/Demo/DemoVoteController.php`
**Purpose**: Manage demo vote submission and verification

**Methods**:

| Method | Route | Purpose |
|--------|-------|---------|
| `create()` | GET `/demo/vote/create` | Display voting form |
| `firstSubmission()` | POST `/demo/vote/submit` | Store vote selection |
| `verify()` | GET `/demo/vote/verify` | Review votes |
| `store()` | POST `/demo/vote/final` | Persist votes |
| `thankYou()` | GET `/demo/vote/thank-you` | Success page |

**Key Features**:
- 5-step process enforcement
- Session-based vote storage
- Vote anonymity (no user_id)
- Result tallying
- Bilingual support

**Candidate Fetching Logic** (CRITICAL for candidate display):

The `create()` method fetches candidates for the voting form:

```php
// DemoVoteController::create() - Lines 241-275

if ($election->isDemo()) {
    // ✅ CORRECT: Query DemoPost (NOT Post table)
    $demoCandidates = DemoCandidacy::where('election_id', $election->id)
        ->orderBy('position_order')
        ->get();
    $groupedCandidates = $demoCandidates->groupBy('post_id');

    // ✅ MUST use DemoPost, NOT Post table
    $national_posts = DemoPost::where('election_id', $election->id)
        ->orderBy('position_order')
        ->get()
        ->map(function ($post) use ($groupedCandidates) {
            $candidatesForPost = $groupedCandidates->get($post->post_id, collect());

            return [
                'post_id' => $post->post_id,
                'name' => $post->name,
                'required_number' => $post->required_number,
                'candidates' => $candidatesForPost->map(function ($c) {
                    return [
                        'candidacy_id' => $c->candidacy_id,
                        'user' => ['id' => $c->user_id, 'name' => $c->user_name],
                        'position_order' => $c->position_order,
                    ];
                })->values(),
            ];
        })->values();
}
```

**⚠️ CRITICAL NOTES**:
1. **Use `DemoPost` NOT `Post`**: Demo candidates are linked to DemoPost records only
2. **Group by post_id**: DemoCandidacy records are grouped by their post_id for matching
3. **orderBy position_order**: Ensures candidates display in correct order
4. **Both national and regional**: Same logic applies to regional posts (lines 312-347)

**Props passed to Vue component**:
```php
return Inertia::render('Vote/CreateVotingPage', [
    'national_posts' => $national_posts,    // Array of posts with candidates
    'regional_posts' => $regional_posts,    // Array of regional posts
    'user_name' => $auth_user->name,
    'user_id' => $auth_user->id,
    'user_region' => $auth_user->region,
    'slug' => $voterSlug ? $voterSlug->slug : null,
    'useSlugPath' => $voterSlug !== null,
    'election' => $election ? [...] : null,
]);
```

### DemoResultController

**File**: `app/Http/Controllers/Demo/DemoResultController.php`
**Purpose**: Display demo election results

**Methods**:

| Method | Route | Purpose |
|--------|-------|---------|
| `show()` | GET `/demo/results/{election}` | Display results page |
| `json()` | GET `/demo/results/{election}.json` | Return JSON results |
| `downloadPdf()` | GET `/demo/results/{election}/pdf` | Download PDF report |

---

## Models

### DemoCode Model

```php
class DemoCode extends Model {
    protected $fillable = [
        'user_id', 'election_id', 'code1', 'code2',
        'can_vote_now', 'is_code1_usable', 'is_code2_usable',
        'has_agreed_to_vote', 'has_voted'
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function election() { return $this->belongsTo(Election::class); }
}
```

### DemoVote Model

```php
class DemoVote extends Model {
    protected $fillable = [
        'election_id', 'candidacy_id', 'marked_at',
        'in_code', 'out_code'
    ];
    // Note: NO user_id for anonymity

    public function election() { return $this->belongsTo(Election::class); }
    public function candidacy() { return $this->belongsTo(DemoCandidacy::class); }
}
```

### DemoResult Model

```php
class DemoResult extends Model {
    protected $fillable = ['election_id', 'candidacy_id', 'votes'];

    public function election() { return $this->belongsTo(Election::class); }
    public function candidacy() { return $this->belongsTo(DemoCandidacy::class); }
}
```

---

## Vue Components

### CreateVotingPage Component

**File**: `resources/js/Pages/Vote/DemoVote/CreateVotingPage.vue`
**Purpose**: Main voting form displaying posts and candidates

**Props Structure** (IMPORTANT):

```javascript
props: {
    // Separate props for national and regional posts
    national_posts: {
        type: Array,
        default: () => []
    },
    regional_posts: {
        type: Array,
        default: () => []
    },
    // Other props...
    user_id: {
        type: Number,
        required: true
    },
    slug: {
        type: String,
        default: null
    },
    useSlugPath: {
        type: Boolean,
        default: false
    }
}
```

**Combined Posts Computed Property**:

```javascript
computed: {
    // Combine national and regional posts into single array
    posts() {
        return [...(this.national_posts || []), ...(this.regional_posts || [])];
    },

    votingProgress() {
        const completed = Object.keys(this.selectedVotes).length;
        const total = this.posts?.length || 0;
        const percentage = total > 0 ? Math.round((completed / total) * 100) : 0;

        return { completed, total, percentage };
    }
}
```

**Template Usage**:

```vue
<!-- Posts section renders combined posts array -->
<section v-if="posts && posts.length > 0">
    <h2>Positions to Vote For</h2>
    <div class="space-y-8">
        <div v-for="(post, postIndex) in posts" :key="`post-${post.post_id}`">
            <!-- Render each post with candidates -->
            <create-votingform
                :post="post"
                :postIndex="postIndex"
                :selectedVotes="selectedVotes"
                @update-votes="handleVoteUpdate"
            />
        </div>
    </div>
</section>
```

**Props Source from Controller**:

The controller passes data like this:

```php
return Inertia::render('Vote/CreateVotingPage', [
    'national_posts' => $national_posts,    // Array of posts with candidates
    'regional_posts' => $regional_posts,    // Array of regional posts
    'user_name' => $auth_user->name,
    'user_id' => $auth_user->id,
    'user_region' => $auth_user->region,
    'slug' => $voterSlug ? $voterSlug->slug : null,
    'useSlugPath' => $voterSlug !== null,
    'election' => $election ? [...] : null,
]);
```

**Each Post Object Structure**:

```javascript
{
    post_id: "president-123",
    name: "President",
    nepali_name: "राष्ट्रपति",
    required_number: 1,
    candidates: [
        {
            candidacy_id: "demo-president-123-1",
            user: {
                id: "demo-user-1",
                name: "Alice Johnson"
            },
            post_id: "president-123",
            image_path_1: "candidate_1.png",
            candidacy_name: "Alice Johnson - Progressive Platform",
            proposer_name: "John Doe",
            supporter_name: "Jane Smith",
            position_order: 1
        },
        // ... more candidates
    ]
}
```

---

## Routes

### Web Routes Configuration

**File**: `routes/web.php`

```php
use App\Http\Controllers\Demo\DemoCodeController;
use App\Http\Controllers\Demo\DemoVoteController;
use App\Http\Controllers\Demo\DemoResultController;

// Demo election voting routes
Route::middleware(['auth', 'election:demo'])->group(function () {

    // Code verification (Step 1)
    Route::get('/demo/code/create', [DemoCodeController::class, 'create'])
        ->name('demo-code.create');
    Route::post('/demo/codes', [DemoCodeController::class, 'store'])
        ->name('demo-code.store');

    // Agreement (Step 2)
    Route::get('/demo/code/agreement', [DemoCodeController::class, 'showAgreement'])
        ->name('demo-code.agreement');
    Route::post('/demo/code/agreement', [DemoCodeController::class, 'submitAgreement'])
        ->name('demo-code.submit-agreement');

    // Voting (Step 3)
    Route::get('/demo/vote/create', [DemoVoteController::class, 'create'])
        ->name('demo-vote.create');
    Route::post('/demo/vote/submit', [DemoVoteController::class, 'firstSubmission'])
        ->name('demo-vote.submit');

    // Verification (Step 4)
    Route::get('/demo/vote/verify', [DemoVoteController::class, 'verify'])
        ->name('demo-vote.verify');

    // Final submission (Step 5)
    Route::post('/demo/vote/final', [DemoVoteController::class, 'store'])
        ->name('demo-vote.store');

    // Thank you
    Route::get('/demo/vote/thank-you', [DemoVoteController::class, 'thankYou'])
        ->name('demo-vote.thank-you');

    // Results
    Route::get('/demo/results/{election}', [DemoResultController::class, 'show'])
        ->name('demo-results.show');
});
```

---

## API Endpoints

### Demo Setup API

**Endpoint**: `POST /api/organizations/{organization}/demo-setup`

**Authentication**: Requires authenticated user and organization membership

**Request**:
```json
{
    "force": false
}
```

**Parameters**:
- `force` (boolean, optional): Force recreate if demo exists

**Response** (Success):
```json
{
    "success": true,
    "message": "Demo election setup completed successfully!",
    "demoStatus": {
        "exists": true,
        "stats": {
            "posts": 3,
            "candidates": 7,
            "codes": 0,
            "votes": 0,
            "election_id": 15,
            "election_name": "Demo Election 2026"
        }
    }
}
```

**Response** (Error - Unauthorized):
```json
{
    "success": false,
    "message": "You do not have access to this organisation."
}
```

**Response** (Error - Server):
```json
{
    "success": false,
    "message": "Demo setup failed. Please check logs."
}
```

---

## Setting Up Demo Elections

### Method 1: Web Interface (Recommended)

1. Login to organization dashboard
2. Navigate to organization page
3. Find "Demo Election Testing" card
4. Click "Setup Demo Election" button
5. Wait for completion and success message

### Method 2: Artisan Command

```bash
php artisan demo:setup --org={organization_id} --force
```

**Options**:
- `--org`: Organization ID (required)
- `--force`: Recreate if exists (optional)

### Method 3: API Call

```bash
curl -X POST http://localhost/api/organizations/{org_id}/demo-setup \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{"force": false}'
```

### Method 4: Programmatic

```php
use App\Models\Organization;
use App\Http\Controllers\Api\DemoSetupController;

$organization = Organization::find(1);
$controller = new DemoSetupController();

// Note: Normally called via HTTP, but for testing:
// Call the underlying logic directly
```

---

## Testing Demo Elections

### Test 1: Complete Voting Workflow

```php
public function test_complete_demo_voting_workflow()
{
    $user = User::factory()->create(['can_vote' => 0]); // No voting permission!
    $election = Election::factory()->create(['type' => 'demo']);

    // Step 1: Code creation
    $response = $this->actingAs($user)->get('/demo/code/create');
    $response->assertStatus(200);

    $code = DemoCode::where('user_id', $user->id)->first();
    $this->assertNotNull($code);

    // Step 2: Code verification
    $response = $this->actingAs($user)->post('/demo/codes', [
        'voting_code' => $code->code1
    ]);
    $response->assertRedirect(route('demo-code.agreement'));

    // Step 3: Agreement
    $response = $this->actingAs($user)->post('/demo/code/agreement', [
        'agreement' => true
    ]);
    $response->assertRedirect(route('demo-vote.create'));

    // Step 4: Vote submission
    $candidates = DemoCandidacy::where('election_id', $election->id)->pluck('id');
    $response = $this->actingAs($user)->post('/demo/vote/submit', [
        'votes' => $candidates->take(3)->toArray()
    ]);
    $response->assertRedirect(route('demo-vote.verify'));

    // Step 5: Vote verification
    $response = $this->actingAs($user)->get('/demo/vote/verify');
    $response->assertStatus(200);

    // Step 6: Final submission
    $response = $this->actingAs($user)->post('/demo/vote/final');
    $response->assertRedirect(route('demo-vote.thank-you'));

    // Verify vote was recorded
    $votes = DemoVote::where('election_id', $election->id)->count();
    $this->assertGreaterThan(0, $votes);
}
```

### Test 2: Re-voting

```php
public function test_demo_allows_revoting()
{
    $user = User::factory()->create();
    $election = Election::factory()->create(['type' => 'demo']);

    // First vote
    $this->actingAs($user)->get('/demo/code/create');
    $code = DemoCode::where('user_id', $user->id)->first();

    // Complete voting
    $this->actingAs($user)->post('/demo/codes', ['voting_code' => $code->code1]);
    $this->actingAs($user)->post('/demo/code/agreement', ['agreement' => true]);
    // ... vote submission ...
    $this->actingAs($user)->post('/demo/vote/final');

    // Mark as voted
    $code->update(['has_voted' => true]);

    // Second vote - code should reset
    $response = $this->actingAs($user)->get('/demo/code/create');

    $freshCode = $code->fresh();
    $this->assertEquals(0, $freshCode->can_vote_now);
    $this->assertEquals(0, $freshCode->has_voted);
}
```

### Test 3: No can_vote Requirement

```php
public function test_demo_allows_users_without_can_vote()
{
    $user = User::factory()->create(['can_vote' => 0]); // Important!
    $election = Election::factory()->create(['type' => 'demo']);

    // Should succeed (no can_vote check)
    $response = $this->actingAs($user)->get('/demo/code/create');
    $response->assertStatus(200);

    // Real elections would block this user
}
```

### Test 4: Data Isolation

```php
public function test_demo_uses_separate_tables()
{
    $user = User::factory()->create();
    $election = Election::factory()->create(['type' => 'demo']);

    // Create demo code
    $this->actingAs($user)->get('/demo/code/create');

    // Should be in demo_codes, not codes
    $this->assertDatabaseHas('demo_codes', ['user_id' => $user->id]);
    $this->assertDatabaseMissing('codes', ['user_id' => $user->id]);
}
```

---

## Code Examples

### Example 1: Create Demo Code Programmatically

```php
use App\Models\DemoCode;
use App\Models\Election;
use App\Models\User;

$user = User::find(1);
$election = Election::where('type', 'demo')->first();

$demoCode = DemoCode::create([
    'user_id' => $user->id,
    'election_id' => $election->id,
    'code1' => str_random(8),
    'code2' => str_random(8),
    'can_vote_now' => 0,
    'is_code1_usable' => 1,
    'is_code2_usable' => 1
]);

echo "Created demo code: " . $demoCode->code1;
```

### Example 2: Submit Demo Vote Programmatically

```php
use App\Models\DemoVote;
use App\Models\DemoResult;
use App\Models\DemoCandidacy;
use App\Models\Election;

$election = Election::where('type', 'demo')->first();
$candidacies = DemoCandidacy::where('election_id', $election->id)
    ->limit(3)
    ->get();

foreach ($candidacies as $candidacy) {
    // Create vote (ANONYMOUS - no user_id)
    DemoVote::create([
        'election_id' => $election->id,
        'candidacy_id' => $candidacy->id,
        'marked_at' => now(),
        'in_code' => 'IN_' . str_random(16),
        'out_code' => 'OUT_' . str_random(16)
    ]);

    // Update result tally
    DemoResult::updateOrCreate(
        ['candidacy_id' => $candidacy->id],
        ['votes' => DB::raw('votes + 1')]
    );
}

echo "Submitted 3 votes";
```

### Example 3: Get Demo Election Results

```php
use App\Models\Election;
use App\Models\DemoResult;

$election = Election::where('type', 'demo')->first();

$results = DemoResult::where('election_id', $election->id)
    ->with('candidacy')
    ->orderByDesc('votes')
    ->get();

foreach ($results as $result) {
    echo $result->candidacy->user->name . ": " . $result->votes . " votes\n";
}
```

### Example 4: Check Demo Status

```php
use App\Models\Organization;
use App\Models\Election;

$organization = Organization::find(1);

$demoElection = Election::withoutGlobalScopes()
    ->where('type', 'demo')
    ->where('organisation_id', $organization->id)
    ->first();

if ($demoElection) {
    $voteCount = $demoElection->votes()->count();
    $codeCount = $demoElection->codes()->count();

    echo "Demo Election Exists\n";
    echo "Votes: $voteCount\n";
    echo "Codes: $codeCount\n";
} else {
    echo "No demo election for this organization\n";
}
```

---

## Troubleshooting

### Issue 1: "Class not found: DemoCandidate"

**Cause**: Incorrect model name in code

**Solution**:
```php
// WRONG
use App\Models\DemoCandidate;

// CORRECT
use App\Models\DemoCandidacy;
```

### Issue 2: Demo code created but can't verify

**Cause**: `can_vote_now` not set to 1 after verification

**Check**:
```php
$code = DemoCode::find(1);
echo "can_vote_now: " . $code->can_vote_now; // Should be 1
```

**Fix**: Ensure DemoCodeController::store() updates the code properly

### Issue 3: Votes not appearing in demo_votes table

**Cause**: Session votes not being persisted

**Debug**:
```php
// In DemoVoteController::store()
dd(session('demo_votes')); // Check if votes in session

// Ensure this runs:
foreach ($votes as $candidacy_id) {
    DemoVote::create([...]);
}
```

### Issue 4: Real election accidentally using demo models

**Cause**: Wrong controller/model import

**Check Routes**:
```php
// Verify real elections use CodeController
Route::post('/codes', [CodeController::class, 'store']);

// And demo uses DemoCodeController
Route::post('/demo/codes', [DemoCodeController::class, 'store']);
```

### Issue 5: can_vote check still enforcing for demo users

**Cause**: Code calling real CodeController for demo elections

**Fix**: Ensure middleware routes to correct controller
```php
// Middleware should set election context
session(['election_type' => 'demo']);

// Then routes should use demo controller
if (session('election_type') === 'demo') {
    // Use DemoCodeController
}
```

### Issue 6: Demo data appearing in real election reports

**Cause**: Missing WHERE type='demo' filter in queries

**Fix**: Always filter by election type
```php
// WRONG
$votes = Vote::all();

// CORRECT
$realVotes = Vote::whereHas('election', function ($q) {
    $q->where('type', 'real');
})->get();

$demoVotes = DemoVote::all(); // Separate table!
```

### Issue 7: Candidates Not Displaying on Voting Form

**Symptom**: User sees empty candidate list on `/demo/vote/create` page

**Root Causes** (Multiple - check in this order):

1. **Demo election not created**: Run setup command
   ```bash
   php artisan demo:setup --org={organization_id}
   ```

2. **Wrong table queried**: DemoVoteController querying `Post` instead of `DemoPost`
   ```php
   // WRONG - will not find demo candidates
   $posts = Post::where('is_national_wide', 1)->get();

   // CORRECT - must use DemoPost
   $posts = DemoPost::where('election_id', $election->id)->get();
   ```

3. **Props mismatch in Vue component**: Component expects single `posts` prop but receives `national_posts` and `regional_posts`
   ```javascript
   // WRONG in CreateVotingPage.vue
   props: {
       posts: { type: Array, default: () => [] }
   }

   // CORRECT
   props: {
       national_posts: { type: Array, default: () => [] },
       regional_posts: { type: Array, default: () => [] }
   }

   // Add computed property to combine
   computed: {
       posts() {
           return [...(this.national_posts || []), ...(this.regional_posts || [])];
       }
   }
   ```

**Debug Checklist**:
```php
// 1. Verify demo election exists
$election = Election::where('type', 'demo')->first();
dd('Election:', $election);

// 2. Check DemoPost records
$posts = DemoPost::where('election_id', $election->id)->get();
dd('Posts:', $posts); // Should show 3 posts

// 3. Check DemoCandidacy records
$candidates = DemoCandidacy::where('election_id', $election->id)->get();
dd('Candidates:', $candidates); // Should show 9 total (3 per post)

// 4. Check controller response
// Add dd() to DemoVoteController::create() before return
dd('national_posts' => $national_posts, 'regional_posts' => $regional_posts);
```

**Complete Fix Verification**:
- ✅ DemoVoteController uses `DemoPost::where('election_id', ...)`
- ✅ CreateVotingPage.vue accepts both `national_posts` and `regional_posts`
- ✅ Computed property combines them into `posts`
- ✅ Template loops through `posts` with `v-for="post in posts"`

---

## Best Practices

### 1. Always Use Separate Models

```php
// GOOD - Clear separation
$realVotes = Vote::all();
$demoVotes = DemoVote::all();

// AVOID - Ambiguous
$votes = Vote::where('is_demo', true)->get();
```

### 2. Test with can_vote=0

```php
// Always test demo with users lacking can_vote
$user = User::factory()->create(['can_vote' => 0]);

// This is the primary use case!
```

### 3. Document Demo-Only Features

```php
public function allowsRevoting()
{
    // DEMO ONLY: Allows unlimited re-voting
    // Real elections: Blocks after first vote
}
```

### 4. Log Demo Activity

```php
Log::channel('demo')->info('Demo setup triggered', [
    'organization_id' => $organization->id,
    'user_id' => auth()->id(),
    'timestamp' => now()
]);
```

### 5. Monitor Data Isolation

```php
// Periodic check that demo and real data don't mix
$demoCodesInRealElection = Code::where('election_id',
    Election::where('type', 'real')->pluck('id')
)->count();

if ($demoCodesInRealElection > 0) {
    alert('Data isolation breach!');
}
```

### 6. Clean Up Stale Demo Data

```bash
# Create periodic cleanup command
php artisan demo:cleanup --days=7 # Delete demo elections older than 7 days
```

---

## Additional Resources

### Documentation Files
- `/developer_guide/election_engine/DEMO_MIRROR_IMPLEMENTATION_COMPLETE.md`
- `/developer_guide/election_engine/DEMO_SETUP_INTERFACE_COMPLETE.md`
- `/developer_guide/election_engine/VOTING_ARCHITECTURE.md`

### Related Test Files
- `tests/Feature/DemoMirrorSystemTest.php` (15+ test cases)
- `tests/Feature/DemoSetupApiTest.php` (6 test cases)

### Source Files
- `app/Http/Controllers/Demo/` (Controllers)
- `app/Models/Demo*.php` (Models)
- `resources/js/Pages/Code/DemoCode/` (UI Components)
- `resources/js/Pages/Vote/DemoVote/` (UI Components)

---

## Summary

The **Demo Election System** is a fully-featured, production-ready testing environment that mirrors real elections while removing voting restrictions. It's designed for:

- ✅ Testing voting workflows
- ✅ User training and onboarding
- ✅ System verification before live elections
- ✅ Multiple test scenarios
- ✅ Re-voting and data reset

Use this guide to:
1. Understand the architecture
2. Set up demo elections
3. Test voting workflows
4. Integrate with existing systems
5. Debug issues

For questions or issues, refer to the [Troubleshooting](#troubleshooting) section or consult the related documentation files.

---

**Document Version**: 1.0
**Last Updated**: 2026-02-20
**Maintainer**: Development Team

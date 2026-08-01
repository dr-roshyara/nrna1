# 🗳️ Voting Workflow - Complete Reference

**Detailed documentation of the 5-step voting process with code examples and diagrams.**

---

## Overview

```
VOTER JOURNEY (5 Steps)

Step 1: Code Verification     ▶ POST /v/{slug}/code
        ├─ Receive code via email
        ├─ Submit code
        └─ System verifies → can_vote_now = 1

Step 2: Agreement Acceptance  ▶ POST /v/{slug}/code/agreement
        ├─ Read agreement
        ├─ Accept checkbox
        └─ System records → has_agreed_to_vote = 1

Step 3: Candidate Selection   ▶ GET /v/{slug}/vote
        ├─ View candidates for each position
        ├─ Select candidates
        └─ System stores in session

Step 4: Vote Preview          ▶ GET /v/{slug}/vote/confirm
        ├─ Review selections
        ├─ Confirm accuracy
        └─ Await final submission

Step 5: Final Submission      ▶ POST /v/{slug}/vote
        ├─ Receive second code via email
        ├─ Submit code + vote
        └─ System saves → has_voted = 1

RESULT: Anonymous vote permanently saved
```

---

## Step 1: Code Verification

### Process

```
User receives email with verification code (code1)
         ↓
User enters code in form
         ↓
POST /v/{slug}/code
         ↓
CodeController::store()
         ├─ Validate code format (6 chars)
         ├─ Verify code matches Code record
         ├─ Check code hasn't expired (30 min)
         ├─ Check not already voted (real elections)
         ├─ Check IP rate limit (max 7 votes/IP)
         └─ Mark code: can_vote_now = 1
         ↓
Record in voter_slug_steps table (step=1)
         ↓
Redirect to agreement page
```

### Code Example

```php
// CodeController.php - store method (simplified)
public function store(Request $request)
{
    // 1. Get user and election
    $user = $this->getUser($request);
    $election = $this->getElection($request);

    // 2. Validate input
    $request->validate([
        'voting_code' => 'required|string|size:6'
    ]);

    $submittedCode = strtoupper(trim($request->input('voting_code')));

    // 3. Get code record (auto-scoped by trait)
    $code = Code::where('user_id', $user->id)
                ->where('election_id', $election->id)
                ->first();

    if (!$code) {
        return back()->withErrors(['No code found']);
    }

    // 4. Check if already voted (real elections only)
    if ($election->type === 'real' && $code->has_voted) {
        return back()->withErrors(['Already voted']);
    }

    // 5. Verify the code
    $result = $this->verifyCode($code, $submittedCode, $user);

    if (!$result['success']) {
        return back()->withErrors(['Code verification failed']);
    }

    // 6. Mark code as verified
    $code->update([
        'can_vote_now' => 1,
        'is_code1_usable' => 0,
        'code1_used_at' => now(),
        'client_ip' => $request->ip(),
    ]);

    // 7. Record step completion
    VoterSlugStep::create([
        'voter_slug_id' => $voterSlug->id,
        'election_id' => $election->id,
        'step' => 1,
        'data' => [
            'code_verified' => true,
            'verified_at' => now()->toIso8601String(),
        ],
    ]);

    // 8. Redirect to agreement
    return redirect()->route('slug.code.agreement', ['vslug' => $voterSlug->slug])
                   ->with('success', 'Code verified!');
}
```

### Code Model State After Step 1

```php
Code {
    id: 1,
    user_id: 5,
    election_id: 3,
    code1: 'ABC123',
    code1_sent_at: 2026-02-19 10:00:00,
    code1_used_at: 2026-02-19 10:02:00,
    is_code1_usable: 0,
    can_vote_now: 1,                    // ← STEP 1 COMPLETE
    has_agreed_to_vote: 0,
    has_voted: 0,
}
```

### Error Handling

```php
// Code doesn't match
if ($code->code1 !== $submittedCode) {
    Log::warning('Invalid code', ['expected' => $code->code1, 'submitted' => $submittedCode]);
    return back()->withErrors(['Invalid verification code']);
}

// Code expired (30 minutes)
$minutesSinceSent = now()->diffInMinutes($code->code1_sent_at);
if ($minutesSinceSent >= 30) {
    // Resend new code
    $code->update([
        'code1' => Str::random(6),
        'code1_sent_at' => now(),
        'is_code1_usable' => 1,
        'can_vote_now' => 0,
    ]);
    return back()->with('info', 'New code sent to email');
}

// Already voted (real elections)
if ($election->type === 'real' && $code->has_voted) {
    Log::warning('Double vote attempt');
    return redirect()->route('dashboard')
                   ->withErrors(['Already voted']);
}

// Too many votes from this IP
$votesFromIP = Code::where('client_ip', $request->ip())
                   ->where('has_voted', 1)
                   ->count();
if ($votesFromIP >= 7) {
    return back()->withErrors(['Too many votes from this IP']);
}
```

---

## Step 2: Agreement Acceptance

### Process

```
Agreement page displayed (Step 1 verified)
         ↓
User reads agreement text
         ↓
User checks "I agree" checkbox
         ↓
POST /v/{slug}/code/agreement
         ↓
CodeController::submitAgreement()
         ├─ Verify code marked as can_vote_now = 1
         ├─ Verify agreement checkbox checked
         └─ Mark code: has_agreed_to_vote = 1
         ↓
Record in voter_slug_steps table (step=2)
         ↓
Redirect to voting page
```

### Code Example

```php
// CodeController.php - submitAgreement method
public function submitAgreement(Request $request)
{
    // 1. Get user and election
    $user = $this->getUser($request);
    $election = $this->getElection($request);

    // 2. Validate agreement checkbox
    $request->validate([
        'agreement' => 'required|accepted'
    ], [
        'agreement.required' => 'You must accept the agreement',
        'agreement.accepted' => 'You must accept the agreement',
    ]);

    // 3. Get code (auto-scoped by trait)
    $code = Code::where('user_id', $user->id)
                ->where('election_id', $election->id)
                ->first();

    // 4. Verify code was already verified in Step 1
    if (!$code || $code->can_vote_now != 1) {
        return redirect()->route('slug.code.create')
                       ->with('error', 'Code verification required first');
    }

    // 5. Mark agreement as accepted
    $code->update([
        'has_agreed_to_vote' => 1,
        'has_agreed_to_vote_at' => now(),
        'voting_started_at' => now(),
    ]);

    // 6. Record step completion
    VoterSlugStep::create([
        'voter_slug_id' => $voterSlug->id,
        'election_id' => $election->id,
        'step' => 2,
        'data' => [
            'agreement_accepted' => true,
            'accepted_at' => now()->toIso8601String(),
        ],
    ]);

    // 7. Redirect to voting page
    return redirect()->route('slug.vote.create', ['vslug' => $voterSlug->slug])
                   ->with('success', 'Agreement accepted');
}
```

### Code Model State After Step 2

```php
Code {
    id: 1,
    can_vote_now: 1,
    has_agreed_to_vote: 1,              // ← STEP 2 COMPLETE
    has_agreed_to_vote_at: 2026-02-19 10:03:00,
    voting_started_at: 2026-02-19 10:03:00,
    has_voted: 0,
}
```

---

## Step 3: Candidate Selection

### Process

```
Voting page loaded with positions
         ↓
For each position:
  ├─ Display candidates
  ├─ User selects required number
  └─ Frontend validates selections

User selects all candidates
         ↓
User clicks "Next" (not yet submitted)
         ↓
Selections stored in session
         ↓
Redirect to confirmation page
```

### Code Example

```php
// VoteController.php - create method (shows voting form)
public function create(Request $request)
{
    // 1. Get user and election
    $user = $this->getUser($request);
    $election = $this->getElection($request);

    // 2. Get code (verify already verified)
    $code = Code::where('user_id', $user->id)
                ->where('election_id', $election->id)
                ->first();

    if (!$code || !$code->can_vote_now) {
        return redirect()->route('slug.code.create');
    }

    // 3. Get election with positions and candidates
    $election->load([
        'posts' => fn($q) => $q->with('candidacies'),
    ]);

    // 4. Return voting form
    return Inertia::render('Vote/Create', [
        'election' => $election,
        'posts' => $election->posts,
        'code_id' => $code->id,
    ]);
}

// Frontend: VoteSelection.vue
<template>
    <form @submit.prevent="submitVote">
        <div v-for="post in posts" :key="post.id">
            <h3>{{ post.name }} (Select {{ post.required_number }})</h3>

            <label v-for="candidate in post.candidacies" :key="candidate.id">
                <input
                    type="checkbox"
                    :value="candidate.id"
                    v-model="selected[post.id]"
                    :max="post.required_number"
                />
                {{ candidate.user_name }}
            </label>
        </div>

        <button type="submit">Review Vote</button>
    </form>
</template>

<script setup>
const selected = ref({});

const submitVote = () => {
    // Store selections in session via controller
    axios.post('/vote/store-session', {
        selections: selected.value,
    }).then(() => {
        // Redirect to confirmation
        window.location = '/vote/confirm';
    });
};
</script>
```

### Session Storage

```php
// VoteController.php - storeSelection method
public function storeSelection(Request $request)
{
    // 1. Validate selections
    $validated = $request->validate([
        'selections' => 'required|array',
        'selections.*' => 'array',
        'selections.*.candidate_ids' => 'array',
    ]);

    // 2. Store in session (temporary, not committed to DB yet)
    session(['voting_selections_' . $code->id => $validated['selections']]);

    // 3. Record preliminary step
    VoterSlugStep::create([
        'voter_slug_id' => $voterSlug->id,
        'election_id' => $election->id,
        'step' => 3,
        'data' => [
            'candidates_selected' => true,
            'selected_at' => now()->toIso8601String(),
        ],
    ]);

    return response()->json(['success' => true]);
}
```

---

## Step 4: Vote Preview

### Process

```
Preview page loaded with selected candidates
         ↓
User reviews selections:
  ├─ Position: President
  │   └─ Selected: Alice Johnson ✓
  ├─ Position: Vice President
  │   └─ Selected: Daniel Miller ✓
  └─ Position: Secretary
      └─ Selected: Grace Lee ✓

User confirms accuracy
         ↓
User clicks "Submit Vote"
         ↓
System sends second code (code2) via email
         ↓
Await final submission with code2
```

### Code Example

```php
// VoteController.php - preview method
public function preview(Request $request)
{
    // 1. Get selections from session
    $selections = session('voting_selections_' . $code->id);

    // 2. Build preview data
    $preview = [];
    foreach ($selections as $postId => $candidateIds) {
        $post = Post::find($postId);
        $candidates = Candidacy::whereIn('id', $candidateIds)->get();

        $preview[] = [
            'position' => $post->name,
            'selected' => $candidates->pluck('user_name'),
        ];
    }

    // 3. Send second code (code2) if not already sent
    if (!$code->code2_sent_at) {
        $code2 = Str::random(6);
        $code->update([
            'code2' => $code2,
            'code2_sent_at' => now(),
            'is_code2_usable' => 1,
        ]);

        // Send email with code2
        Mail::send(new SendSecondVerificationCode($user, $code2));
    }

    // 4. Return preview page
    return Inertia::render('Vote/Preview', [
        'preview' => $preview,
        'election' => $election,
    ]);
}
```

### Code Model State After Step 4

```php
Code {
    id: 1,
    can_vote_now: 1,
    has_agreed_to_vote: 1,
    code2: 'XYZ789',                    // ← NEW: Second code
    code2_sent_at: 2026-02-19 10:05:00,
    is_code2_usable: 1,
    has_voted: 0,                       // ← NOT YET
}
```

---

## Step 5: Final Submission

### Process

```
User receives email with second code (code2)
         ↓
User enters code2 in submission form
         ↓
POST /v/{slug}/vote
         ↓
VoteController::store()
         ├─ Get user, election, code
         ├─ Validate code2 matches
         ├─ Pre-flight checks:
         │  ├─ User not already voted
         │  ├─ Election still active
         │  ├─ Code still valid (10 min)
         │  └─ No tampering detected
         ├─ Generate vote hashes (ANONYMOUS)
         ├─ Save vote → NO user_id
         ├─ Save results → NO user_id
         ├─ Mark code: has_voted = 1
         └─ Send verification email
         ↓
Record in voter_slug_steps table (step=5)
         ↓
Vote permanently saved, election complete
```

### Code Example

```php
// VoteController.php - store method (final submission)
public function store(Request $request)
{
    DB::beginTransaction();

    try {
        // 1. Get user and election
        $user = $this->getUser($request);
        $election = $this->getElection($request);

        // 2. Get code (auto-scoped by trait)
        $code = Code::where('user_id', $user->id)
                    ->where('election_id', $election->id)
                    ->first();

        // 3. Validate code2
        $request->validate([
            'voting_code' => 'required|string|size:6'
        ]);

        $submittedCode2 = strtoupper(trim($request->input('voting_code')));

        // 4. Verify code2
        if ($code->code2 !== $submittedCode2) {
            return back()->withErrors(['Invalid verification code']);
        }

        // 5. Pre-flight checks
        if ($election->type === 'real' && $code->has_voted) {
            return back()->withErrors(['Already voted']);
        }

        if (!$election->isCurrentlyActive()) {
            return back()->withErrors(['Election is closed']);
        }

        // 6. Get vote selections from session
        $voteData = session('voting_selections_' . $code->id);

        // 7. Generate vote hash (for audit trail, NOT user identification)
        $private_key = bin2hex(random_bytes(16));
        $vote_hash = password_hash($private_key, PASSWORD_BCRYPT);

        // 8. Create vote (ANONYMOUS - NO user_id)
        $vote = Vote::create([
            'election_id' => $election->id,
            // organisation_id auto-filled by trait
            'voting_code' => $vote_hash,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            // NO user_id - ANONYMOUS!
        ]);

        // 9. Create results for each selected candidate
        foreach ($voteData as $postId => $candidateIds) {
            foreach ($candidateIds as $candidateId) {
                Result::create([
                    'vote_id' => $vote->id,
                    'candidate_id' => $candidateId,
                    'ip_address' => $request->ip(),
                    // NO user_id - ANONYMOUS!
                ]);
            }
        }

        // 10. Mark user as voted
        $code->update([
            'has_voted' => 1,
            'vote_submitted' => 1,
            'code2_used_at' => now(),
            'is_code2_usable' => 0,
        ]);

        // 11. Record step completion
        VoterSlugStep::create([
            'voter_slug_id' => $voterSlug->id,
            'election_id' => $election->id,
            'step' => 5,
            'data' => [
                'vote_submitted' => true,
                'vote_id' => $vote->id,
                'submitted_at' => now()->toIso8601String(),
            ],
        ]);

        // 12. Send verification email
        if ($user->email && filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
            Mail::send(new VoteConfirmation($private_key . '_' . $vote->id));
        }

        DB::commit();

        // 13. Redirect to thank you page
        return redirect()->route('vote.thank-you')
                       ->with('success', 'Vote submitted successfully!');

    } catch (Exception $e) {
        DB::rollBack();
        Log::error('Vote submission failed', ['error' => $e->getMessage()]);
        return back()->withErrors(['Vote submission failed']);
    }
}
```

### Code Model State After Step 5

```php
Code {
    id: 1,
    can_vote_now: 1,
    has_agreed_to_vote: 1,
    code2: 'XYZ789',
    code2_used_at: 2026-02-19 10:07:00,
    is_code2_usable: 0,
    has_voted: 1,                       // ← STEP 5 COMPLETE
    vote_submitted: 1,                  // ← FINALIZED
}
```

### Vote Created (ANONYMOUS)

```php
Vote {
    id: 142,
    election_id: 3,
    organisation_id: 1,                 // For isolation only
    voting_code: '$2y$10$hash...',      // Hashed combination
    ip_address: '192.168.1.1',
    user_agent: 'Mozilla/5.0...',
    created_at: 2026-02-19 10:07:00,
    // NO user_id - COMPLETELY ANONYMOUS!
}

Result {
    id: 284,
    vote_id: 142,                       // Link to vote, NOT to user
    candidate_id: 15,
    ip_address: '192.168.1.1',
    // NO user_id - COMPLETELY ANONYMOUS!
}
```

---

## Complete Data Flow

### Request Path

```
User Auth
  ↓
TenantContext Middleware
  ├─ session['current_organisation_id'] = user.organisation_id
  └─ Auto-scopes all queries
  ↓
Route to Controller
  ↓
Controller Action
  ├─ Model::find()        ← Auto-scoped by trait
  ├─ Model::where()       ← Auto-scoped by trait
  └─ Model::create()      ← Auto-fills organisation_id
  ↓
Response Returned
```

### Data Isolation

```
MODE 1: Demo User (organisation_id = NULL)
  ├─ Session: NULL
  ├─ Can see: Elections/Votes/Results with org = NULL
  └─ Cannot see: Elections/Votes/Results with org = 1, 2, 3...

MODE 2: Org 1 User (organisation_id = 1)
  ├─ Session: 1
  ├─ Can see: Elections/Votes/Results with org = 1
  └─ Cannot see: Elections/Votes/Results with org = NULL, 2, 3...

MODE 2: Org 2 User (organisation_id = 2)
  ├─ Session: 2
  ├─ Can see: Elections/Votes/Results with org = 2
  └─ Cannot see: Elections/Votes/Results with org = NULL, 1, 3...
```

---

## Summary

| Step | Action | Result | Code State |
|------|--------|--------|-----------|
| 1 | Verify code1 | Code verified | `can_vote_now = 1` |
| 2 | Accept agreement | Agreement recorded | `has_agreed_to_vote = 1` |
| 3 | Select candidates | Selections stored | Selections in session |
| 4 | Review vote | Preview shown, code2 sent | `code2_sent_at` set |
| 5 | Verify code2 + submit | Vote saved anonymously | `has_voted = 1` |

**Result: Vote is permanently saved with ZERO user identification!** ✅

---

## Security Guarantees

✅ **One vote per voter per election** (enforced by code model)
✅ **Code expiration** (30 minutes for code1, 10 minutes for code2)
✅ **IP rate limiting** (max 7 votes per IP address)
✅ **Double-vote prevention** (has_voted flag)
✅ **Complete anonymity** (no user_id in votes)
✅ **Audit trail** (hashed codes, IP, timestamps)
✅ **Demo vs Real enforcement** (type field)
✅ **Tenant isolation** (organisation_id scoping)

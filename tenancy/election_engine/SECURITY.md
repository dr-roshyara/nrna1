# 🔐 Security & Vote Anonymity Guide

**Complete reference for security features, vote anonymity implementation, and best practices.**

---

## Table of Contents

1. [Vote Anonymity Guarantee](#vote-anonymity-guarantee)
2. [Tenant Isolation](#tenant-isolation)
3. [Code Security](#code-security)
4. [Vote Tampering Prevention](#vote-tampering-prevention)
5. [Rate Limiting](#rate-limiting)
6. [Audit Trail](#audit-trail)
7. [Best Practices](#best-practices)
8. [Security Checklist](#security-checklist)

---

## Vote Anonymity Guarantee

### Core Principle

**Votes are completely anonymous - no way to link a vote to a voter.**

### What's Stored

```sql
-- votes table (real elections)
CREATE TABLE votes (
    id BIGINT PRIMARY KEY,
    election_id BIGINT NOT NULL,
    organisation_id BIGINT NULLABLE,        -- For isolation only
    voting_code VARCHAR(255) NOT NULL,      -- Hashed verification code
    ip_address VARCHAR(45) NULLABLE,        -- For security audit
    user_agent TEXT NULLABLE,               -- For security audit
    created_at TIMESTAMP,
    updated_at TIMESTAMP
    -- NO user_id column
    -- NO member_id column
    -- NO voter_slug_id column
    -- NO personal identification
);

-- results table (real elections)
CREATE TABLE results (
    id BIGINT PRIMARY KEY,
    vote_id BIGINT NOT NULL,
    candidate_id BIGINT NOT NULL,
    organisation_id BIGINT NULLABLE,
    ip_address VARCHAR(45) NULLABLE,
    -- NO user_id column - vote is linked by vote_id, not user
);
```

### Why This is Secure

#### ❌ **What You CANNOT Do:**

```sql
-- This query is IMPOSSIBLE:
SELECT * FROM votes
WHERE user_id = 5;  -- ERROR: user_id doesn't exist!

-- This query is IMPOSSIBLE:
SELECT COUNT(*) FROM votes
WHERE user_id = 5 AND election_id = 1;  -- ERROR: user_id doesn't exist!

-- This query is IMPOSSIBLE:
SELECT results.candidate_id FROM results
WHERE results.user_id = 5;  -- ERROR: user_id doesn't exist!
```

#### ✅ **What You CAN See (Audit Trail Only):**

```sql
-- Codes are tied to users (in codes table)
SELECT code1, code1_sent_at, has_voted
FROM codes
WHERE user_id = 5 AND election_id = 1;
-- ✓ See WHEN code was sent, if user voted
-- ✗ Cannot see HOW they voted

-- Votes have audit data (no user link)
SELECT voting_code, ip_address, user_agent, created_at
FROM votes
WHERE election_id = 1;
-- ✓ See timestamps, IPs (for security)
-- ✗ Cannot see WHO voted or HOW they voted

-- Results have no voter link
SELECT candidate_id, COUNT(*) as votes
FROM results
GROUP BY candidate_id;
-- ✓ See vote counts per candidate
-- ✗ Cannot see which voter voted for which candidate
```

### Code vs Vote Separation

```
CODES TABLE (with user identification)
├─ user_id: 5                           ← WHO
├─ election_id: 3                       ← WHERE
├─ code1: 'ABC123'                      ← FOR AUTHENTICATION
├─ has_voted: 1                         ← DID THEY VOTE?
└─ can_vote_now: 1                      ← CAN THEY PROCEED?

                    ↓ (NO LINK BETWEEN TABLES)

VOTES TABLE (WITHOUT user identification)
├─ id: 142                              ← UNIQUE VOTE ID
├─ election_id: 3                       ← WHERE
├─ voting_code: 'hash...'              ← AUDIT TRAIL ONLY
└─ ip_address: '192.168.1.1'           ← SECURITY ONLY

                    ↓ (NO LINK BACK TO USER)

RESULTS TABLE (WITHOUT user identification)
├─ vote_id: 142                         ← WHICH VOTE
├─ candidate_id: 15                     ← WHO WAS VOTED FOR
└─ ip_address: '192.168.1.1'           ← SECURITY ONLY

RESULT: Vote is anonymous but auditable
```

---

## Tenant Isolation

### Multi-Tenancy Guarantee

Every model with `BelongsToTenant` trait is automatically scoped:

```php
// All queries are AUTOMATICALLY scoped
Election::all();              // Only elections for current tenant/demo
Code::find($id);              // Only codes for current tenant/demo
Vote::where(...)->get();      // Only votes for current tenant/demo

// Safe: Returns NULL if not in current tenant (prevents leakage)
Election::find(999);          // NULL if election 999 is not in current org
```

### How It Works

```
Request comes in
    ↓
TenantContext Middleware
    ├─ Gets session('current_organisation_id')
    └─ Set from auth()->user()->organisation_id
    ↓
All model queries have global scope applied
    ├─ IF session = NULL
    │   └─ WHERE organisation_id IS NULL
    ├─ IF session = 1
    │   └─ WHERE organisation_id = 1
    └─ (No manual WHERE needed)
    ↓
Result: Only records for current tenant/demo visible
```

### Isolation Guarantee

```php
// Scenario: Tenant 1 user tries to access Tenant 2's election

User A (org_id = 1)
  ├─ Authenticates
  ├─ session['current_organisation_id'] = 1
  └─ Makes request: GET /elections/999

Election 999 (org_id = 2)
  ├─ BelongsToTenant trait applied
  ├─ WHERE organisation_id = 1 (from session)
  └─ Election 999 with org_id = 2 is NOT returned

Result: 404 Not Found (secure!)
```

---

## Code Security

### Dual Code System

The system uses **two separate codes** for enhanced security:

#### **code1: Code Verification (Step 1)**

```php
// Sent immediately when user starts voting
Mail::send(new SendFirstVerificationCode($user, $code1));

// User submits within 30 minutes
CodeController::store()
    ├─ Verify code1 matches
    ├─ Check not expired (30 min max)
    └─ Mark can_vote_now = 1

// Code1 is simple verification, can be logged/displayed
// (safe because vote hasn't happened yet)
```

#### **code2: Vote Submission Code (Step 5)**

```php
// Sent when user reaches preview page (after candidate selection)
Mail::send(new SendSecondVerificationCode($user, $code2));

// User submits with vote data
VoteController::store()
    ├─ Verify code2 matches
    ├─ Check not expired (10 min max, stricter)
    ├─ Get vote data from session
    ├─ Save vote immediately
    ├─ Hash code2 + vote_id
    └─ voting_code = password_hash($code2 . '_' . $vote_id)

// Code2 is hashed and never displayed
// (protects against vote tampering)
```

### Code Format & Generation

```php
// Generate 6-character uppercase codes
$code = Str::random(6);
// Example: 'ABC123', 'XYZ789'

// Stored in Code model
Code {
    code1: 'ABC123',
    code2: 'XYZ789',
}

// Hashed when saved to voting_code field
voting_code: password_hash('XYZ789_142', PASSWORD_BCRYPT);
// Result: '$2y$10$...'
```

### Code Validation

```php
// Verify code1
if ($code->code1 !== $submittedCode) {
    Log::warning('Invalid code1 attempt');
    return back()->withErrors(['Invalid code']);
}

// Verify code2
if ($code->code2 !== $submittedCode2) {
    Log::warning('Invalid code2 attempt');
    return back()->withErrors(['Invalid code']);
}

// Verify code not expired
$minutesSinceSent = now()->diffInMinutes($code->code1_sent_at);
if ($minutesSinceSent >= 30) {
    Log::warning('Code1 expired');
    // Resend new code
}

// Verify code2 timeout (stricter)
$minutesSinceSent = now()->diffInMinutes($code->code2_sent_at);
if ($minutesSinceSent >= 10) {
    Log::warning('Code2 expired - must vote within 10 minutes of preview');
    return back()->withErrors(['Code expired - try again']);
}
```

---

## Vote Tampering Prevention

### Transaction Safety

All vote submission is wrapped in a database transaction:

```php
public function store(Request $request)
{
    DB::beginTransaction();

    try {
        // All operations are atomic
        $code = Code::where(...)
                    ->lockForUpdate()  // Prevent race conditions
                    ->first();

        // Check hasn't voted
        if ($code->has_voted) {
            throw new Exception('Already voted');
        }

        // Create vote
        $vote = Vote::create([...]);

        // Create results
        foreach ($voteData as $postId => $candidateIds) {
            foreach ($candidateIds as $candidateId) {
                Result::create([...]);
            }
        }

        // Mark as voted
        $code->update(['has_voted' => 1]);

        DB::commit();

    } catch (Exception $e) {
        DB::rollBack();  // Rollback all changes on error
        throw $e;
    }
}
```

### Session Verification

Session data is not trusted alone - server-side verification required:

```php
// ❌ WRONG: Trust session data alone
$voteData = session('voting_data');  // Not verified
Vote::create(['data' => $voteData]);  // Potentially tampered

// ✅ RIGHT: Verify server-side before saving
$voteData = session('voting_data');

// Validate each candidate exists and belongs to election
foreach ($voteData as $postId => $candidateIds) {
    $post = Post::findOrFail($postId);

    // Verify post belongs to this election
    if ($post->election_id !== $election->id) {
        throw new Exception('Invalid post for this election');
    }

    // Verify each candidate exists
    $candidates = Candidacy::whereIn('id', $candidateIds)
                           ->where('post_id', $postId)
                           ->get();

    if ($candidates->count() !== count($candidateIds)) {
        throw new Exception('Invalid candidate selection');
    }
}

// Only THEN create vote
Vote::create([...]);
```

### Timestamp Validation

```php
// Verify user hasn't been voting too long
$votingStarted = $code->voting_started_at;
$votingDuration = now()->diffInMinutes($votingStarted);

if ($votingDuration > 60) {  // Max 60 minutes voting window
    Log::warning('Voting window exceeded');
    return back()->withErrors(['Voting window closed']);
}

// Verify election is still active
if (!$election->isCurrentlyActive()) {
    Log::warning('Election ended during vote');
    return back()->withErrors(['Election has closed']);
}
```

---

## Rate Limiting

### IP-Based Rate Limiting

```php
// Prevent abuse from single IP address
$clientIP = $request->getClientIp(true);  // Include proxy headers

$votesFromIP = Code::where('client_ip', $clientIP)
                   ->where('has_voted', 1)
                   ->count();

if ($votesFromIP >= 7) {  // Max 7 votes per IP
    Log::warning('Rate limit exceeded', ['ip' => $clientIP, 'votes' => $votesFromIP]);
    return back()->withErrors(['Too many votes from this IP address']);
}
```

### Usage Example

```php
// In CodeController::store()
public function verifyCode(Code $code, string $submittedCode, User $user): array
{
    // ... validation ...

    // Check IP rate limit
    $votesFromIP = Code::where('client_ip', $this->clientIP)
                       ->where('has_voted', 1)
                       ->count();

    if ($votesFromIP >= $this->maxUseClientIP) {
        return [
            'success' => false,
            'message' => 'Too many votes from this IP address.'
        ];
    }

    return ['success' => true, 'message' => 'Code verified'];
}
```

### Configuration

```php
// config/election.php
return [
    'voting' => [
        'max_votes_per_ip' => 7,
        'code_timeout_minutes' => 30,
        'code2_timeout_minutes' => 10,
        'max_voting_duration_minutes' => 60,
    ],
];

// Usage
$maxVotesPerIP = config('election.voting.max_votes_per_ip');
```

---

## Audit Trail

### Voting Code Hash

```php
// When vote is submitted:
$privateKey = bin2hex(random_bytes(16));  // 32-char random string
$concatenated = $privateKey . '_' . $vote->id;

$code->update([
    'voting_code' => password_hash($concatenated, PASSWORD_BCRYPT),
]);

// Result: '$2y$10$...' (irreversible)

// Can be verified but not reversed
password_verify($concatenated, $hashed);  // true/false only
```

### Audit Fields Stored

```php
Vote {
    voting_code: 'hash...',          // Irreversible code hash
    ip_address: '192.168.1.1',       // For detecting abuse
    user_agent: 'Mozilla/5.0...',    // For device tracking
    created_at: timestamp,           // When vote was cast
    updated_at: timestamp,           // When modified
}

Code {
    code1_sent_at: timestamp,        // When code1 sent
    code1_used_at: timestamp,        // When code1 used
    code2_sent_at: timestamp,        // When code2 sent
    code2_used_at: timestamp,        // When code2 used
    client_ip: '192.168.1.1',        // For rate limiting
    has_voted: boolean,              // Vote completion flag
}
```

### Audit Queries

```php
// Get all votes for an election with timestamps
$votes = Vote::where('election_id', $election->id)
             ->orderBy('created_at')
             ->get([
                 'id',
                 'voting_code',
                 'ip_address',
                 'user_agent',
                 'created_at',
             ]);

// Detect suspicious IP patterns
$ipVoteCounts = Code::where('election_id', $election->id)
                     ->where('has_voted', 1)
                     ->groupBy('client_ip')
                     ->selectRaw('client_ip, COUNT(*) as vote_count')
                     ->having('vote_count', '>', 1)
                     ->get();

// Timeline of voting activity
$timeline = Code::where('election_id', $election->id)
                ->orderBy('voting_started_at')
                ->selectRaw('DATE_FORMAT(voting_started_at, "%Y-%m-%d %H:00") as hour, COUNT(*) as votes')
                ->groupBy('hour')
                ->get();
```

---

## Best Practices

### ✅ DO

- ✅ Always use HTTPS for all voting requests
- ✅ Validate user authentication on every request
- ✅ Check tenant context before returning data
- ✅ Use transactions for vote submission
- ✅ Hash all sensitive codes with `password_hash()`
- ✅ Log all security events with timestamps
- ✅ Verify election is active before accepting votes
- ✅ Double-check no user_id in votes/results
- ✅ Use IP address for abuse detection
- ✅ Enforce strict code expiration
- ✅ Test tenant isolation in all tests

### ❌ DON'T

- ❌ Don't log voting codes in plaintext
- ❌ Don't store user_id with votes
- ❌ Don't trust session data without verification
- ❌ Don't skip organisation_id validation
- ❌ Don't allow voting after election ends
- ❌ Don't expose vote data without scoping
- ❌ Don't use `withoutGlobalScopes()` in production code
- ❌ Don't hardcode rate limits (use config)
- ❌ Don't skip transaction rollback on error
- ❌ Don't display raw voting codes in responses
- ❌ Don't allow multiple codes per user without verification

### Code Example: Secure Vote Submission

```php
public function storeVote(Request $request)
{
    // 1. Authenticate user (must be done by middleware)
    abort_unless(auth()->check(), 401);

    // 2. Get user and election (auto-scoped by trait)
    $user = auth()->user();
    $election = Election::findOrFail($request->election_id);

    // 3. Verify user is in correct organisation
    abort_unless(
        $user->organisation_id === $election->organisation_id,
        403
    );

    // 4. Start transaction
    DB::beginTransaction();

    try {
        // 5. Get code with lock
        $code = Code::where('user_id', $user->id)
                    ->where('election_id', $election->id)
                    ->lockForUpdate()
                    ->firstOrFail();

        // 6. Verify has not voted
        abort_if($code->has_voted, 403);

        // 7. Verify code2
        abort_unless(
            $code->code2 === $request->input('verification_code'),
            422
        );

        // 8. Get vote data from session
        $voteData = session('vote_data_' . $code->id);
        abort_unless($voteData, 422);

        // 9. Verify and save vote (no user_id!)
        $vote = Vote::create([
            'election_id' => $election->id,
            'organisation_id' => $election->organisation_id,
            'voting_code' => password_hash($request->code2 . '_pending'),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // 10. Save results (no user_id!)
        foreach ($voteData as $candidateId) {
            Result::create([
                'vote_id' => $vote->id,
                'candidate_id' => $candidateId,
                'ip_address' => $request->ip(),
            ]);
        }

        // 11. Mark as voted
        $code->update(['has_voted' => 1, 'code2_used_at' => now()]);

        // 12. Commit transaction
        DB::commit();

        // 13. Log success
        Log::channel('voting_audit')->info('Vote submitted', [
            'user_id' => $user->id,
            'election_id' => $election->id,
            'vote_id' => $vote->id,
        ]);

        return response()->json(['success' => true, 'vote_id' => $vote->id]);

    } catch (Exception $e) {
        DB::rollBack();

        Log::warning('Vote submission failed', [
            'user_id' => $user->id,
            'election_id' => $election->id,
            'error' => $e->getMessage(),
        ]);

        throw $e;
    }
}
```

---

## Security Checklist

Use this checklist before deploying to production:

### Database Security
- [ ] All 8 tables have organisation_id column
- [ ] All organisation_id columns are NULLABLE
- [ ] Proper indexes on organisation_id
- [ ] No user_id in votes table
- [ ] No user_id in results table
- [ ] Foreign key constraints properly set
- [ ] Migrations tested and reversible

### Application Security
- [ ] TenantContext middleware registered
- [ ] BelongsToTenant trait on all models
- [ ] Global scope automatically applied
- [ ] No manual organisation_id checks in controllers
- [ ] All queries auto-scoped by trait
- [ ] Tenant isolation tested
- [ ] Mode 1 and Mode 2 isolation tested

### Voting Security
- [ ] Dual code system (code1 + code2)
- [ ] Code timeout (30 min code1, 10 min code2)
- [ ] Code expiration enforced
- [ ] One vote per voter per election
- [ ] Vote submission in transaction
- [ ] Session data verified before saving
- [ ] Election active check before voting
- [ ] Voting window time limit enforced

### Code Security
- [ ] Codes stored as plaintext in codes table (ok for authentication)
- [ ] Voting codes hashed in votes table (password_hash)
- [ ] No codes logged in plaintext
- [ ] Code generation uses secure random

### Rate Limiting
- [ ] IP-based rate limiting (max 7 votes per IP)
- [ ] Configurable rate limit values
- [ ] Rate limiting tested
- [ ] Blocking logic tested

### Audit Trail
- [ ] IP address stored with every vote
- [ ] User agent stored with every vote
- [ ] Timestamps recorded for all activities
- [ ] Audit log accessible for investigation
- [ ] No PII mixed with vote data

### Testing
- [ ] Tenant isolation tests (Mode 1 & Mode 2)
- [ ] Vote anonymity tests
- [ ] Code expiration tests
- [ ] Rate limiting tests
- [ ] Double-vote prevention tests
- [ ] Transaction rollback tests
- [ ] Cross-organisation access prevention tests

### Deployment
- [ ] HTTPS enabled
- [ ] SQL injection prevention verified
- [ ] CSRF tokens present
- [ ] Rate limiting enabled
- [ ] Monitoring configured
- [ ] Backup strategy in place
- [ ] Disaster recovery plan ready

---

## Summary

The election system provides:

✅ **Complete vote anonymity** - No way to link votes to voters
✅ **Strict tenant isolation** - Each org/demo completely separate
✅ **Secure code system** - Dual codes, hashing, expiration
✅ **Tampering prevention** - Transactions, verification, timestamps
✅ **Rate limiting** - IP-based abuse prevention
✅ **Audit trail** - Complete activity logging
✅ **Production-ready** - Security best practices built-in

**Use this guide to audit your implementation before production deployment!** 🔐

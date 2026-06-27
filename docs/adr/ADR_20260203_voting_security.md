# ADR: Voting System Security Architecture - Demo/Real Elections with Anonymity Preservation

**Date:** 2026-02-03
**Status:** Accepted
**Context:** Public Digit Platform - Multi-Tenant Election Management
**Affected Components:** VoteController, Models (Vote, DemoVote, Result, DemoResult), Services, Middleware

---

## 1. Problem Statement

The Public Digit platform requires a voting system that:

1. **Supports both demo and real elections** for testing and production
2. **Maintains strict vote anonymity** - no direct user-vote linkage
3. **Enforces eligibility rules** differently:
   - Demo elections: allow all voters (for testing)
   - Real elections: respect `can_vote_now` timing restrictions
4. **Enables backward compatibility** with existing voting routes
5. **Provides election-scoped voting** without creating separate codebases

The core tension: How to support multiple election types and contexts while preserving the fundamental security property that **votes are anonymous**.

---

## 2. Security Requirement: Vote Anonymity

### **The Threat Model**

Without anonymity guarantees:
- Election officials could link voters to their votes
- Vote coercion becomes possible (proof of how someone voted)
- Voter privacy is compromised
- Democratic principles are violated

### **Anonymity Principle**

```
The vote itself must NOT contain user_id.

Only the vote VERIFICATION CHAIN connects user → code → vote:
  users (user_id) ↔ codes (user_id + voting_code hash)
                       ↓
                     votes (NO user_id, only voting_code hash)
                       ↓
                    results (NO user_id, only vote_id)
```

**This ensures:**
- Election officials can verify "this code authorized a vote" (via voting_code)
- But cannot determine "this user cast this specific vote"
- Vote aggregation (results) is never linked to individual users

---

## 3. Decision: Dual Separation with Election-Aware Services

### **Architecture**

**Level 1: Physical Separation (Table Names)**
```
Real Elections:    votes table          → results table
Demo Elections:    demo_votes table     → demo_results table
```

**Level 2: Logical Separation (election_id Column)**
```
All four tables include:
  - election_id (foreign key to elections table)
  - Enables multi-tenant election support
  - Allows deletion of demo data without affecting real data
```

**Level 3: Service Abstraction (VotingServiceFactory)**
```php
$votingService = VotingServiceFactory::make($election);
$voteModel = $votingService->getVoteModel();    // Vote or DemoVote
$resultModel = $votingService->getResultModel(); // Result or DemoResult
```

**Level 4: Eligibility Enforcement (Election-Aware)**
```php
if ($election->isDemo()) {
    return true;  // Demo: allow all (for testing)
}
return $user->can_vote_now == 1;  // Real: respect timing
```

---

## 4. Implementation Details

### **Database Schema Principles**

#### **Votes Table (Real Elections)**
```sql
CREATE TABLE votes (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    election_id BIGINT NOT NULL,  -- Election scoping
    voting_code VARCHAR(255) NOT NULL,  -- Hash link to code
    -- NO user_id column (preserves anonymity)
    candidate_01 JSON,
    candidate_02 JSON,
    -- ... 60 candidate columns
    created_at TIMESTAMP,
    FOREIGN KEY (election_id) REFERENCES elections(id),
    INDEX idx_voting_code (voting_code),
    INDEX idx_election_id (election_id)
);
```

#### **Demo_Votes Table (Demo Elections)**
```sql
CREATE TABLE demo_votes (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    election_id BIGINT NOT NULL,  -- Election scoping
    voting_code VARCHAR(255) NOT NULL,  -- Hash link to code
    -- NO user_id column (preserves anonymity)
    candidate_01 JSON,
    candidate_02 JSON,
    -- ... 60 candidate columns
    created_at TIMESTAMP,
    FOREIGN KEY (election_id) REFERENCES elections(id),
    INDEX idx_voting_code (voting_code),
    INDEX idx_election_id (election_id)
);
```

#### **Results Tables**
```sql
CREATE TABLE results (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    vote_id BIGINT NOT NULL,  -- Link to vote
    election_id BIGINT NOT NULL,  -- Election scoping
    post_id VARCHAR(255) NOT NULL,
    candidacy_id VARCHAR(255) NOT NULL,
    -- NO user_id column (preserves anonymity)
    created_at TIMESTAMP,
    FOREIGN KEY (vote_id) REFERENCES votes(id),
    FOREIGN KEY (election_id) REFERENCES elections(id),
    INDEX idx_election_id (election_id),
    INDEX idx_post_id (post_id)
);
-- demo_results follows identical structure
```

### **Vote Authorization Chain (Anonymity Preserved)**

```
Step 1: User Authenticates
  ├─ Get user from request
  └─ Get code for (user_id, election_id)

Step 2: User Verifies Code
  ├─ Code.code1 matches (first verification)
  ├─ Code.code2 matches (second verification)
  └─ Generate voting_code hash

Step 3: Vote is Saved (ANONYMOUSLY)
  ├─ vote.voting_code = hash(generated_code)
  ├─ vote.election_id = election.id
  ├─ NO vote.user_id
  └─ Results created with vote_id (not user_id)

Step 4: Verification Only
  ├─ User gets vote receipt: voting_code_hash
  ├─ Can prove they voted (via voting_code)
  ├─ But CANNOT prove WHO they voted for
  └─ Officials cannot link voting_code to user_id
```

### **Code Implementation: save_vote() Method**

```php
public function save_vote(
    $input_data,
    $hashed_voting_key,
    $election = null,
    $auth_user = null
) {
    // Get correct service based on election type
    $votingService = $this->getVotingService($election);
    $voteModel = $votingService->getVoteModel();      // Vote or DemoVote
    $resultModel = $votingService->getResultModel();  // Result or DemoResult

    // Create vote - ANONYMOUSLY
    $vote = new $voteModel;
    $vote->no_vote_option = 0;
    $vote->voting_code = $hashed_voting_key;           // ← Authorization link
    $vote->election_id = $election->id;                // ← Scoping
    // ✅ NO user_id - preserves anonymity!
    $vote->save();

    // Save results for each candidate
    foreach ($candidates as $candidate) {
        $result = new $resultModel;
        $result->vote_id = $vote->id;                  // ← Link to vote
        $result->election_id = $election->id;          // ← Scoping
        $result->post_id = $post_id;
        $result->candidacy_id = $candidacy_id;
        // ✅ NO user_id - preserves anonymity!
        $result->save();
    }

    Log::info('Vote saved successfully (anonymously)', [
        'vote_id' => $vote->id,
        'election_id' => $election->id,
        'election_type' => $election->type,
        'via_voting_code' => 'hash_' . substr($hashed_voting_key, 0, 8) . '...',
    ]);
}
```

### **Election-Aware Eligibility Check**

```php
private function isUserEligibleToVote(User $user, Election $election): bool
{
    if ($election->isDemo()) {
        // DEMO: Always allow for testing
        return true;
    }

    // REAL: Must respect timing restrictions
    return $user->can_vote_now == 1;
}
```

### **ElectionMiddleware: Smart Election Resolution**

```php
public function handle(Request $request, Closure $next)
{
    $election = null;

    // 1. Check session for user-selected election
    $electionId = session('selected_election_id');
    if ($electionId) {
        $election = Election::find($electionId);
    }

    // 2. Check route parameter (direct access)
    if (!$election && $request->route('election')) {
        $election = $request->route('election');
    }

    // 3. DEFAULT: Use first REAL active election
    // (ensures backward compatibility - existing links work)
    if (!$election) {
        $election = Election::where('type', 'real')
            ->where('is_active', true)
            ->orderBy('id')
            ->first();
    }

    // Attach to request for controllers
    $request->attributes->set('election', $election);

    return $next($request);
}
```

---

## 5. Data Flow Example: Real Election Voting

### **User Journey**

```
┌─────────────────────────────────────────────────────────────┐
│ Step 1: Code Creation                                       │
├─────────────────────────────────────────────────────────────┤
│ POST /v/{vslug}/code                                        │
│ ├─ User authenticated (user_id extracted)                   │
│ ├─ Election resolved: Election.type = 'real'               │
│ ├─ Check eligibility: can_vote_now == 1 ✓                  │
│ ├─ Create Code record with (user_id, election_id)          │
│ └─ Send code1 to email                                      │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│ Step 2: Vote Creation (Candidates Selected)                │
├─────────────────────────────────────────────────────────────┤
│ GET /v/{vslug}/vote/create                                 │
│ ├─ User authenticated (user_id extracted)                   │
│ ├─ Election resolved: Election.type = 'real'               │
│ ├─ Eligibility verified: can_vote_now == 1 ✓               │
│ ├─ Show voting form with candidates                         │
│ └─ User selects candidates                                  │
│                                                              │
│ POST /v/{vslug}/vote/submit                                │
│ ├─ Validate candidate selections                            │
│ ├─ Store in session (still under user auth)                │
│ └─ Redirect to verification                                │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│ Step 3: Vote Verification (Before Anonymization)           │
├─────────────────────────────────────────────────────────────┤
│ GET /v/{vslug}/vote/verify                                 │
│ ├─ Load vote data from session                             │
│ ├─ Show candidate selections for review                     │
│ ├─ Verify code2 received from email                         │
│ └─ User submits code2                                       │
│                                                              │
│ POST /v/{vslug}/vote/verify (code submission)              │
│ ├─ Verify code2 matches                                     │
│ ├─ Generate voting_code hash                               │
│ ├─ ⚠️ ANONYMIZATION POINT:                                  │
│ │  └─ From now on, NO user_id stored with vote             │
│ ├─ Call save_vote(data, voting_code, election)             │
│ │  ├─ Create Vote (not DemoVote) - Real election           │
│ │  ├─ Vote.voting_code = hash (authorization link)         │
│ │  ├─ Vote.election_id = election.id (scoping)             │
│ │  ├─ Vote.user_id NOT SET (anonymity preserved)           │
│ │  ├─ Create Results for each candidate                    │
│ │  ├─ Result.election_id = election.id (scoping)           │
│ │  └─ Result.user_id NOT SET (anonymity preserved)         │
│ ├─ Mark Code.has_voted = 1                                 │
│ ├─ Send vote receipt (voting_code_hash only)               │
│ └─ Redirect to completion                                   │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│ Step 4: Completion                                          │
├─────────────────────────────────────────────────────────────┤
│ GET /v/{vslug}/vote/complete                               │
│ ├─ Show "Thank You" message                                │
│ ├─ Provide vote receipt (voting_code_hash)                 │
│ └─ Clear session data                                       │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│ ANONYMITY VERIFICATION                                      │
├─────────────────────────────────────────────────────────────┤
│ Database State AFTER voting:                                │
│                                                              │
│ codes table:                                                │
│   id | user_id | election_id | code1 | code2 | has_voted  │
│   42 |    123  |      1      | xxx  | yyy  |     1        │
│    └─ User linked to their code                           │
│                                                              │
│ votes table:                                                │
│   id | election_id | voting_code | candidate_01 | ...     │
│   98 |      1      | hash_xyz    | {…voted…}    |          │
│    └─ Vote is completely anonymous                         │
│       Only voting_code links it to a CODE (not user)       │
│                                                              │
│ results table:                                              │
│   id | vote_id | election_id | post_id | candidacy_id     │
│   150|    98   |      1      |  post_1 |  cand_1          │
│    └─ Results linked to vote only                          │
│       No trace of which user cast these votes              │
└─────────────────────────────────────────────────────────────┘
```

---

## 6. Demo Election Voting (Different Eligibility)

### **Key Difference: Eligibility Bypass**

```php
// In VoteController.isUserEligibleToVote()
if ($election->isDemo()) {
    return true;  // ← No timing restrictions
}
return $user->can_vote_now == 1;  // ← Real elections enforce timing
```

### **Impact on Workflow**

| Step | Real Election | Demo Election |
|------|---------------|---------------|
| **Code Creation** | Must verify `can_vote_now == 1` | Bypasses `can_vote_now` |
| **Vote Creation** | User must be eligible | Any user can vote |
| **Code Verification** | Normal flow | Normal flow |
| **Storage** | `votes` table | `demo_votes` table |
| **Anonymity** | ✅ Preserved (no user_id) | ✅ Preserved (no user_id) |
| **Cleanup** | Permanent | Can be deleted via `DemoVotingService::reset()` |

---

## 7. Backward Compatibility Strategy

### **Problem**
Existing voting routes don't explicitly select elections. They should continue to work.

### **Solution: Smart Defaults**

```php
// ElectionMiddleware resolution order:
1. Check session('selected_election_id')  // User explicitly selected
2. Check route parameter 'election'        // URL-based selection
3. DEFAULT: Use first REAL active election // Backward compatible default
```

**Consequence:**
- Old routes like `/vote/create` automatically use the REAL election
- Demo elections are accessible via `/election/demo/start` or explicit selection
- No breaking changes to existing links

---

## 8. Consequences

### **✅ Positive Consequences**

1. **Vote Anonymity Preserved**
   - No direct user-vote linkage in database
   - Election officials cannot determine who voted for whom
   - Voter privacy protected by design

2. **Dual Election Support**
   - Demo elections for testing (all users eligible)
   - Real elections for production (timing-restricted)
   - Both in same system, same codebase

3. **Backward Compatibility**
   - Existing routes continue to work unchanged
   - Demo elections via new `/election` routes
   - No migration required for existing voters

4. **Election Scoping**
   - election_id enables multi-tenant support
   - Easy deletion of demo data without affecting real data
   - Clear separation of concerns

5. **Type Safety**
   - VotingServiceFactory encapsulates model selection
   - No hardcoded Vote/DemoVote references throughout codebase
   - Easy to extend to new election types

### **⚠️ Negative Consequences / Tradeoffs**

1. **Complexity**
   - Four separate tables (votes, demo_votes, results, demo_results)
   - Dual separation approach requires understanding both physical and logical levels
   - Queries must specify election_id for correctness

2. **Vote Verification Limitations**
   - Users cannot retrieve their specific vote after submission
   - Can only prove they voted (via voting_code), not what they voted for
   - This is intentional (preserves anonymity), but may confuse users

3. **Reporting Challenges**
   - Cannot do per-user vote tracking
   - Audit trails must focus on process (code verification) not outcomes
   - Election officials need different reporting mindset

4. **Testing Complexity**
   - Must remember to create test elections or use demo elections
   - Tests need to verify both table separation and election scoping
   - Demo data cleanup must be explicit

### **✓ Resolved with Proper Documentation**
- ADR explains the tradeoffs
- Code comments clarify why user_id is NOT in votes/results
- Logging marks votes as "(anonymously)"

---

## 9. Security Audit Checklist

**Before production deployment, verify:**

- [ ] No `user_id` column in votes table
- [ ] No `user_id` column in demo_votes table
- [ ] No `user_id` column in results table
- [ ] No `user_id` column in demo_results table
- [ ] All votes have `election_id` set
- [ ] All results have `election_id` set
- [ ] ElectionMiddleware is applied to all voting routes
- [ ] isUserEligibleToVote() respects demo vs real election difference
- [ ] VotingServiceFactory used consistently (no hardcoded Vote/DemoVote)
- [ ] save_vote() does not accept or store user_id parameter
- [ ] Logging marks votes as "(anonymously)"
- [ ] Tests verify vote anonymity is maintained
- [ ] Tests verify election scoping works correctly
- [ ] Demo election reset/cleanup only affects demo_votes/demo_results

---

## 10. Monitoring & Alerting

### **Critical Logs to Monitor**

```
✅ "Vote saved successfully (anonymously)"
   - Confirms votes are being saved without user_id

⚠️  "User not eligible to vote in this election"
   - Tracks eligibility violations

⚠️  "Vote verification attempted without session data"
   - Tracks incomplete voting attempts
```

### **Metrics to Track**

```
- Real election: votes_submitted / codes_created (turnout)
- Demo election: votes_submitted / demo_codes_created (testing volume)
- Code verification failures (potential fraud attempt)
- Eligibility rejections by election type
```

---

## 11. Related Decisions

- **ADR: Multi-Tenant Election Architecture** - Foundation for election scoping
- **ADR: Code-Based Vote Authorization** - Explains voting_code hash design
- **ADR: DDD Architecture** - Why services/factories are used

---

## 12. References

- **Vote Anonymity Principle**: [EAC Voting System Standards]
- **Dual Separation Pattern**: Commonly used in financial systems (demo vs prod)
- **Election Middleware**: Implements middleware resolution pattern from Laravel routing

---

## 13. Questions & Answers

**Q: Can election officials determine who voted for whom?**
A: No. Votes have no user_id. Officials can only verify that a code authorized a vote (anonymously).

**Q: Can users prove who they voted for?**
A: No, intentionally. They receive a voting_code hash as receipt, proving they voted, but not how.

**Q: What if we need vote audit trail by user?**
A: Use the codes table (user-scoped) + voting_code links, but never link to actual vote content.

**Q: Can demo elections become real elections?**
A: No, by design. Create a new real election. Demo elections can be reset; real elections are permanent.

**Q: What happens if someone votes in both demo and real elections?**
A: Different tables, different codes. They're separate voting histories.

---

## 14. Sign-Off

**Proposed by:** Senior Architect
**Reviewed by:** Security Team
**Status:** ✅ **Accepted**
**Implementation Date:** 2026-02-03
**Next Review:** 2026-05-03 (post-production)

---

**Security Mantra:**
> *"Votes are anonymous by design, not by accident."*

The vote table contains no user_id because user privacy is a fundamental requirement, not a feature to be added later.

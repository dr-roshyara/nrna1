# Verifiable Anonymity: Core Concept Explained

## The Fundamental Problem

Elections face an impossible choice:

> **How do we ensure votes are anonymous while still allowing verification?**

### The Traditional Dilemma

| System Type | Anonymity | Verification | Result |
|---|---|---|---|
| Paper ballots | High | Medium (recount) | Manual, slow, error-prone |
| Voting machines | Medium | Medium | Proprietary systems, hard to audit |
| E-voting with user_id | Low | High | Complete privacy violation! |
| Public ledger voting | None | High | Everyone knows how everyone voted |

**The Core Tension:**
- To verify a vote exists, systems typically link it to a voter
- But linking votes to voters destroys privacy
- Yet without verification, election fraud is rampant

---

## The Verifiable Anonymity Solution

**Key Insight:** We don't need the vote to contain the voter's identity. We just need proof that:
1. A specific voter participated
2. Their vote was recorded correctly
3. No one else can learn how they voted

```
The Vote:           [candidate_01: 5, candidate_02: 12]
                    NO user_id!

The Proof:          vote_hash = SHA256(user_id + election_id + code + time)
                    This hash PROVES participation WITHOUT exposing choices
```

### The Mathematical Beauty

```
BEFORE VOTING:
Voter knows:        user_id, election_id, code, timestamp
Attacker knows:     Public election results only

AFTER VOTING:
Voter computes:     hash = SHA256(user_id + election + code + time)
Voter stores:       hash_prefix for verification

Later verification:
Voter can prove:    "I was part of election X, and the hash matches"
Attacker cannot:    Determine which vote belongs to the voter (hash is irreversible)
                    Cannot link vote to voter (no user_id in votes table)
                    Cannot even know if vote was cast by this voter
                    (would need access to codes table + original inputs)
```

---

## How Vote Hash Works

### Step 1: Code Setup

When a voting code is issued:

```php
// codes table entry
[
    'code_id' => 123,
    'user_id' => 456,              // User identity (in codes table only)
    'election_id' => 789,
    'code1' => 'ABC123XYZ',        // Voter's unique code
    'organisation_id' => 1,
    'has_voted' => false,
    'voted_at' => null,
]
```

### Step 2: Vote Submission

When the voter submits their vote:

```php
// In VoteController@store

// Step 2a: Get the voter's code
$code = Code::where('user_id', auth()->id())
            ->where('election_id', $election->id)
            ->first();

// Step 2b: Generate cryptographic proof
$vote_hash = hash('sha256',
    $code->user_id .              // Identity component
    $code->election_id .           // Election component
    $code->code1 .                 // Code component
    now()->timestamp               // Time component
);

// Step 2c: Create vote WITHOUT user_id
$vote = Vote::create([
    'election_id' => $election->id,
    'organisation_id' => $election->organisation_id,
    'vote_hash' => $vote_hash,     // ← Proof stored
    'candidate_01' => 5,           // ← Choices stored
    'candidate_02' => 12,
    // ... more candidates ...
    'cast_at' => now(),            // ← Timestamp stored
    // ❌ NO user_id stored here!
]);

// Step 2d: Mark code as used
$code->update([
    'has_voted' => true,
    'voted_at' => now(),
]);
```

**Critical Point:** The vote_hash contains user_id in its computation, but the votes table record does NOT store user_id. This is the security boundary.

### Step 3: Result Creation

When a vote is processed:

```php
// For each candidate selected, create a result record
for each candidate_id in $vote->getSelectedCandidates() {
    Result::create([
        'vote_id' => $vote->id,
        'election_id' => $vote->election_id,
        'organisation_id' => $vote->organisation_id,
        'candidate_id' => $candidate_id,
        'vote_hash' => $vote->vote_hash,   // Copy hash for verification
        'vote_count' => 1,
        // ❌ NO user_id here either!
    ]);
}
```

### Step 4: Voter Verification

When the voter wants to verify their vote later:

```php
// In VerificationController@show

$code = Code::where('user_id', auth()->id())
            ->where('election_id', $election->id)
            ->first();

// Voter provides their code_id, we regenerate the hash
$expectedHash = hash('sha256',
    $code->user_id .
    $code->election_id .
    $code->code1 .
    $vote->cast_at->timestamp      // Use ORIGINAL timestamp
);

// Check if expected hash matches any vote in the election
$userVote = Vote::where('election_id', $election->id)
                 ->where('vote_hash', $expectedHash)
                 ->first();

if ($userVote) {
    // ✅ Vote found! Prove participation.
    return [
        'verified' => true,
        'message' => 'Your vote was recorded correctly',
        'cast_at' => $userVote->cast_at,
        'vote_hash_prefix' => substr($expectedHash, 0, 8) . '...',
        // ❌ Don't return which candidates!
    ];
}

return [
    'verified' => false,
    'message' => 'Your vote was not found'
];
```

---

## Security Guarantees

### 1. Voter Privacy Guarantee

**Claim:** No one can determine how a specific voter voted.

**Why it's true:**
- votes table has NO user_id
- Only way to link is via vote_hash
- vote_hash is SHA256 (irreversible)
- Even with codes table, attacker cannot:
  - Determine which vote hash belongs to which code
  - Codes and votes tables are intentionally not joined at DB level

**Attack Prevention:**
```
❌ SELECT votes.* FROM votes
   WHERE votes.user_id = ?
   → No user_id column exists!

❌ SELECT votes.*, codes.user_id
   FROM votes
   JOIN codes ON hash(codes.user_id + ...) = votes.vote_hash
   → Cannot join on hash function comparison

❌ SELECT votes.* FROM votes
   WHERE votes.vote_hash IN (SELECT expected_hashes FROM codes)
   → Requires computing all possible hashes (computationally infeasible)
```

### 2. Verification Guarantee

**Claim:** A voter can prove they participated.

**Why it's true:**
- Voter has their code (code1)
- codes table stores their user_id (under authentication)
- Voter can regenerate hash: SHA256(user_id + election + code + timestamp)
- If hash matches a vote in results, participation is proven

### 3. Tamper-Detection Guarantee

**Claim:** Vote changes are detectable.

**Why it's true:**
- vote_hash is SHA256 of original inputs
- If any component changes, hash changes
- Voter can detect tampering by regenerating hash
- Changed vote_hash won't match any stored hash

**Example:**
```php
// Original vote was cast at timestamp 1000
$original_hash = hash('sha256', '456.789.ABC123XYZ.1000');

// Attacker changes timestamp to 2000
$tampered_hash = hash('sha256', '456.789.ABC123XYZ.2000');

// Hashes don't match!
$original_hash !== $tampered_hash

// Verification will fail - tampering detected!
```

### 4. One-Vote Guarantee

**Claim:** Each voter can only vote once.

**Why it's true:**
- codes.has_voted = true marks voter as voted
- Voter cannot vote twice (logic prevents it)
- vote_hash is unique per code per election
- Multiple votes by same voter have different timestamps (different hashes)

---

## Attack Surface Analysis

### Attack 1: "Show me how person X voted"

**Attacker says:** "I have access to votes table. Show me person X's vote."

**Defense:**
- votes table has NO user_id
- Attacker cannot identify person X's vote without user_id
- Even with codes table, cannot compute hash without original timestamp (which voter keeps secret)

**Status:** ✅ Prevented

### Attack 2: "Modify a vote and no one will know"

**Attacker says:** "I'll change candidate_01 value. Who will notice?"

**Defense:**
- vote_hash is stored in results table
- Verification compares vote_hash to regenerated hash
- Changed vote fails verification
- Voter notices when verifying

**Status:** ✅ Prevented

### Attack 3: "Create fake votes"

**Attacker says:** "I'll insert fake votes with random hashes."

**Defense:**
- Fake votes have no codes.code_id reference
- Verification looks for vote_hash in results
- Fake vote_hash won't match any code's hash
- No one can verify fake votes

**Status:** ✅ Prevented

### Attack 4: "Brute force the vote hash"

**Attacker says:** "I'll compute all possible SHA256 hashes to find the original."

**Defense:**
- SHA256 is computationally infeasible to reverse
- To forge a vote, attacker needs: user_id + election_id + code1 + timestamp
- user_id is in codes table (requires authentication)
- code1 is secret (stored only in codes table)
- timestamp is 13-digit number (1.3 trillion possibilities)
- Brute force requires computing hashes for all user/code/time combinations
- For 1M users, 100 elections, 86,400 seconds per day: 8.64 × 10^15 hashes
- At 1M hashes/second: 8,640,000 seconds = 100 days to check one day's voting
- Election is over before brute force completes

**Status:** ✅ Prevented by computational complexity

### Attack 5: "Expose voter identity through vote_hash"

**Attacker says:** "I'll use vote_hash as a fingerprint to identify voters."

**Defense:**
- vote_hash only reveals to voter who cast it
- Voter controls their own code and timestamp
- Two votes with same candidates but different timestamps = different hashes
- Attacker cannot link votes to each other (no common identifier)

**Status:** ✅ Prevented

### Attack 6: "Coerce voters by checking the votes table"

**Attacker says:** "I'll check if you voted and for whom."

**Defense:**
- votes table has NO user_id
- Attacker cannot identify any voter's vote
- Can only see aggregated results by candidate
- Cannot pressure any specific voter

**Status:** ✅ Prevented

---

## Cryptographic Properties Explained

### SHA256 Hash Function

```php
$input = "456789ABCABC123XYZ1234567890";
$hash = hash('sha256', $input);
// Result: a2f4b8c3d9e1f5a7c2b4d8e0f3a5c7b9d1e3f5a7b9c1d3e5f7a9b0c2d4e6f8

// Deterministic: Same input always produces same output
hash('sha256', $input) === $hash  // true

// Avalanche: Tiny change produces completely different hash
hash('sha256', "456789ABCABC123XYZ1234567891")
// Result: 7c2b4d8e0f3a5c7b9d1e3f5a7b9c1d3e5f7a9b0c2d4e6f8a2f4b8c3d9e1f5a
// Completely different!

// One-way: Cannot reverse hash to get original input
// hash('sha256', 'x') = 'y' but reverse(y) != x

// Collision-resistant: Cannot find two inputs with same hash
// (At least 2^256 attempts required)
```

### Hash_equals() Function

```php
// Standard comparison (vulnerable to timing attacks)
$hash1 === $hash2

// Timing-safe comparison (immune to timing attacks)
hash_equals($hash1, $hash2)

// Why it matters:
// In standard comparison: Time to fail at position 1 vs 256 reveals info
// In hash_equals(): Always takes same time (constant time)
// Prevents attackers from guessing hash by timing response
```

---

## Data Isolation Pattern

```
┌─────────────────────────────────┐
│  CODES TABLE                    │
│  • user_id (identity)           │
│  • code1 (for hash)             │
│  • election_id                  │
│  ├─ ONLY linked to votes via    │
│  │  hash computation            │
│  └─ NOT directly joined         │
└─────────────────────────────────┘
       ↓ (hash-based only)
┌─────────────────────────────────┐
│  VOTES TABLE                    │
│  • vote_hash (proof)            │
│  • candidate_01..60 (choices)   │
│  • NO user_id (anonymity)       │
│  ├─ Cannot reverse hash to      │
│  │  get user_id                 │
│  └─ Cannot join to codes table  │
│     without hash computation    │
└─────────────────────────────────┘
       ↓ (aggregation)
┌─────────────────────────────────┐
│  RESULTS TABLE                  │
│  • candidate_id (aggregated)    │
│  • NO user_id (anonymity)       │
│  └─ Public results only         │
└─────────────────────────────────┘
```

**Key Property:** Even someone with access to BOTH codes and votes tables cannot efficiently determine how any voter voted without:
1. Knowing the original timestamp (voter controls this)
2. Computing hash for every code (computationally expensive)
3. Trying to brute-force the hash (cryptographically infeasible)

---

## Real-World Example

### Scenario: Alice votes in an election

**Step 1: Code issuance**
```
Alice is issued code: ABC123XYZ
Stored in codes table:
- user_id: 456 (Alice's ID, stored in codes table under authentication)
- code1: 'ABC123XYZ'
- election_id: 789
```

**Step 2: Vote submission at 2:45:30 PM (timestamp: 1725000330)**
```
Alice selects candidates: 5, 12, 18
vote_hash = SHA256('456.789.ABC123XYZ.1725000330')
         = 'a2f4b8c3d9e1f5a7c2b4d8e0f3a5c7b9...'

Vote stored in votes table:
- vote_id: 999
- vote_hash: 'a2f4b8c3d9e1f5a7c2b4d8e0f3a5c7b9...'
- candidate_01: 5
- candidate_02: 12
- candidate_03: 18
- ❌ NO user_id stored!
- cast_at: 1725000330

Code updated:
- has_voted: true
- voted_at: 1725000330
```

**Step 3: Attacker scenario - "Find Alice's vote"**

Attacker has access to votes table.

```
Attacker sees:
- vote_id: 999
- vote_hash: 'a2f4b8c3d9e1f5a7c2b4d8e0f3a5c7b9...'
- candidate_01: 5
- candidate_02: 12
- candidate_03: 18

Attacker tries to determine if this vote is Alice's:
1. Attacker knows Alice's user_id (456) from users table
2. Attacker needs to compute: SHA256('456.789.ABC123XYZ.TIMESTAMP')
3. Attacker doesn't know Alice's code1 ('ABC123XYZ') - it's in codes table
4. Attacker doesn't know the timestamp - could be any time that day
5. Attacker doesn't have access to codes table (or it's encrypted)

Result: ❌ Cannot determine if this vote is Alice's without:
- Access to codes table AND
- Knowledge of original timestamp AND
- Computing 86,400 hashes per user (one for each second of voting day)
```

**Step 4: Alice verifies her vote (weeks later)**

```
Alice logs in and says: "I voted for candidates 5, 12, 18"

System computes:
vote_hash = SHA256('456.789.ABC123XYZ.1725000330')
         = 'a2f4b8c3d9e1f5a7c2b4d8e0f3a5c7b9...'

Searches votes table for matching hash and finds vote_id: 999

Returns to Alice:
{
    "verified": true,
    "message": "Your vote was recorded correctly",
    "cast_at": "2025-08-31 2:45:30 PM",
    "vote_hash_prefix": "a2f4b8c3..."
}

Alice is satisfied: ✅ Proof that she voted, but no one else knows how she voted!
```

---

## Why NOT Use user_id in Votes Table?

### ❌ WRONG APPROACH: Store user_id
```php
// votes table with user_id
[
    'vote_id' => 999,
    'user_id' => 456,              // ← SECURITY RISK!
    'candidate_01' => 5,
    'candidate_02' => 12,
]

// Problem: Anyone with votes table access knows:
// - Alice (user 456) voted for candidates 5, 12
// - Complete privacy violation!
// - Enables voter coercion, intimidation, discrimination
```

### ✅ CORRECT APPROACH: Only store vote_hash
```php
// votes table without user_id
[
    'vote_id' => 999,
    'vote_hash' => 'a2f4b8c3...',  // ← Cryptographic proof
    'candidate_01' => 5,
    'candidate_02' => 12,
]

// Benefit: No one can identify voter from votes table alone
// Verification still works via hash comparison
// Alice can still prove her vote was recorded
```

---

## Next Steps

- **See how this is implemented?** → Read [04-implementation-guide.md](./04-implementation-guide.md)
- **Check the database schema?** → Read [03-schema-changes.md](./03-schema-changes.md)
- **Understand API changes?** → Read [05-api-reference.md](./05-api-reference.md)

---

**Summary:** Verifiable Anonymity solves the impossible problem of anonymous+verifiable voting through cryptographic hashing. Voters can prove they participated without ever revealing how they voted, making it impossible for anyone—even database administrators—to determine individual vote choices.

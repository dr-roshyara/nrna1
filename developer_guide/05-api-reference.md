# API Reference: Verifiable Anonymity Implementation

This document describes API endpoint changes required for the Verifiable Anonymity implementation.

---

## Vote Submission Endpoint

### POST /vote/submit (Real Elections)

**Endpoint:** `POST /elections/{election}/vote/submit`

**Authentication:** Bearer token from voting code

**Purpose:** Submit a vote with candidate selections and abstention preferences

#### Request Payload Changes

**OLD REQUEST (Before Verifiable Anonymity)**
```json
{
    "national_selected_candidates": [
        {
            "post_id": 1,
            "candidacy_id": 5,
            "position_order": 1
        },
        {
            "post_id": 1,
            "candidacy_id": 12,
            "position_order": 2
        }
    ],
    "regional_selected_candidates": [
        {
            "post_id": 2,
            "candidacy_id": 18,
            "position_order": 1
        }
    ],
    "no_vote_option": false
}
```

**NEW REQUEST (Verifiable Anonymity)**
```json
{
    "national_selected_candidates": [
        {
            "post_id": 1,
            "candidacy_id": 5,
            "position_order": 1
        },
        {
            "post_id": 1,
            "candidacy_id": 12,
            "position_order": 2
        }
    ],
    "regional_selected_candidates": [
        {
            "post_id": 2,
            "candidacy_id": 18,
            "position_order": 1
        }
    ],
    "no_vote_posts": [3, 5, 7]
}
```

**Changes:**
- `no_vote_option` (boolean) → `no_vote_posts` (array of post IDs)
- Allows granular abstention from specific posts
- Array instead of all-or-nothing boolean

#### Request Validation

```php
$request->validate([
    'national_selected_candidates' => 'array',
    'national_selected_candidates.*.post_id' => 'required|integer|exists:posts,id',
    'national_selected_candidates.*.candidacy_id' => 'required|integer|exists:candidacies,id',
    'national_selected_candidates.*.position_order' => 'required|integer',

    'regional_selected_candidates' => 'array',
    'regional_selected_candidates.*.post_id' => 'required|integer|exists:posts,id',
    'regional_selected_candidates.*.candidacy_id' => 'required|integer|exists:candidacies,id',
    'regional_selected_candidates.*.position_order' => 'required|integer',

    'no_vote_posts' => 'array',
    'no_vote_posts.*' => 'integer|exists:posts,id',
]);
```

#### Response Payload Changes

**OLD RESPONSE**
```json
{
    "success": true,
    "vote_info": {
        "voted_at": "Mar 1, 2026 at 3:45 PM",
        "no_vote_option": false,
        "voting_code_used": "abc123def456ghi789"
    },
    "message": "Your vote has been recorded successfully"
}
```

**NEW RESPONSE**
```json
{
    "success": true,
    "vote_info": {
        "voted_at": "Mar 1, 2026 at 3:45 PM",
        "abstained_from_posts": [3, 5, 7],
        "vote_hash_prefix": "a2f4b8c3...",
        "can_verify": true
    },
    "message": "Your vote has been recorded successfully"
}
```

**Changes:**
- `no_vote_option` (boolean) → `abstained_from_posts` (array of post IDs)
- `voting_code_used` (unsafe) → `vote_hash_prefix` (safe cryptographic proof)
- Added `can_verify` flag to indicate voter can verify later

#### Controller Implementation

```php
public function store(Request $request, Election $election)
{
    $request->validate([...]);

    // Get voter's code
    $code = Code::where('user_id', auth()->id())
                ->where('election_id', $election->id)
                ->first();

    // Generate vote_hash cryptographic proof
    $vote_hash = hash('sha256',
        $code->user_id .
        $election->id .
        $code->code1 .
        now()->timestamp
    );

    // Create vote WITHOUT user_id
    $vote = Vote::create([
        'election_id' => $election->id,
        'organisation_id' => $election->organisation_id,
        'vote_hash' => $vote_hash,
        'no_vote_posts' => $request->no_vote_posts ?? [],
        'cast_at' => now(),
    ]);

    // Process candidate selections
    foreach (array_merge(
        $request->national_selected_candidates ?? [],
        $request->regional_selected_candidates ?? []
    ) as $selection) {
        // Map post_id and candidacy_id to candidate_01..60
        $columnName = 'candidate_' . str_pad($selection['position_order'], 2, '0', STR_PAD_LEFT);
        $vote->update([$columnName => $selection['candidacy_id']]);

        // Create result record
        Result::create([
            'vote_id' => $vote->id,
            'election_id' => $election->id,
            'organisation_id' => $election->organisation_id,
            'post_id' => $selection['post_id'],
            'candidate_id' => $selection['candidacy_id'],  // ← Uses candidate_id now!
            'vote_hash' => $vote->vote_hash,
            'vote_count' => 1,
        ]);
    }

    // Create abstention results
    foreach ($request->no_vote_posts ?? [] as $post_id) {
        Result::create([
            'vote_id' => $vote->id,
            'election_id' => $election->id,
            'organisation_id' => $election->organisation_id,
            'post_id' => $post_id,
            'candidate_id' => null,  // NULL indicates abstention
            'vote_hash' => $vote->vote_hash,
            'vote_count' => 1,
        ]);
    }

    // Mark code as voted
    $code->update(['has_voted' => true, 'voted_at' => now()]);

    return response()->json([
        'success' => true,
        'vote_info' => [
            'voted_at' => $vote->cast_at->format('M d, Y \a\t h:i A'),
            'abstained_from_posts' => $vote->no_vote_posts,
            'vote_hash_prefix' => substr($vote->vote_hash, 0, 8) . '...',
            'can_verify' => true,
        ],
        'message' => 'Your vote has been recorded successfully',
    ]);
}
```

---

## Vote Verification Endpoint

### GET /vote/verify

**Endpoint:** `GET /elections/{election}/vote/verify`

**Authentication:** Bearer token (authenticated user)

**Purpose:** Allow voter to verify their vote was recorded correctly

#### Request Format

```http
GET /elections/789/vote/verify
Authorization: Bearer {user_token}
```

#### Response Format

**Success Response (Verified):**
```json
{
    "verified": true,
    "vote_info": {
        "election_id": 789,
        "organisation_id": 1,
        "cast_at": "2026-03-01T15:45:30Z",
        "vote_hash_prefix": "a2f4b8c3...",
        "can_verify": true
    },
    "message": "Your vote was recorded correctly"
}
```

**Failure Response (Not Found):**
```json
{
    "verified": false,
    "message": "No vote found for this election"
}
```

#### Implementation

```php
public function verify(Request $request, Election $election)
{
    $user = auth()->user();

    // Get voter's code
    $code = Code::where('user_id', $user->id)
                ->where('election_id', $election->id)
                ->first();

    if (!$code) {
        return response()->json([
            'verified' => false,
            'message' => 'No voting code found for this election',
        ], 404);
    }

    // Search for vote by regenerating hash
    $expectedHash = hash('sha256',
        $code->user_id .
        $election->id .
        $code->code1 .
        $code->voted_at->timestamp  // Use original timestamp!
    );

    $vote = Vote::where('election_id', $election->id)
                ->where('vote_hash', $expectedHash)
                ->first();

    if (!$vote) {
        return response()->json([
            'verified' => false,
            'message' => 'Your vote was not found',
        ], 404);
    }

    return response()->json([
        'verified' => true,
        'vote_info' => $vote->getVerificationData(),
        'message' => 'Your vote was recorded correctly',
    ]);
}
```

**Key Points:**
- Uses regenerated vote_hash to find vote
- DOES NOT return who voter voted for
- DOES NOT expose user_id
- Returns vote_hash_prefix for audit trail

---

## Demo Vote Endpoint

### POST /demo/vote/submit

**Endpoint:** `POST /elections/{election}/demo/vote/submit`

**Purpose:** Submit a vote in demo mode (separate table, resettable)

#### Request/Response Format

**Request:**
```json
{
    "national_selected_candidates": [...],
    "regional_selected_candidates": [...],
    "no_vote_posts": [3, 5, 7]
}
```

**Response:**
```json
{
    "success": true,
    "vote_info": {
        "voted_at": "Mar 1, 2026 at 3:45 PM",
        "abstained_from_posts": [3, 5, 7],
        "vote_hash_prefix": "a2f4b8c3...",
        "can_verify": true
    },
    "message": "Demo vote recorded"
}
```

#### Implementation

```php
// In DemoVoteController
public function store(Request $request, Election $election)
{
    // Exact same logic as VoteController, but uses DemoVote model
    $code = Code::where('user_id', auth()->id())
                ->where('election_id', $election->id)
                ->where('organisation_id', null)  // Demo mode
                ->first();

    $vote_hash = hash('sha256',
        $code->user_id .
        $election->id .
        $code->code1 .
        now()->timestamp
    );

    $demoVote = DemoVote::create([
        'election_id' => $election->id,
        'organisation_id' => null,  // Demo mode marker
        'vote_hash' => $vote_hash,
        'no_vote_posts' => $request->no_vote_posts ?? [],
        'cast_at' => now(),
    ]);

    // ... create results ...
}
```

---

## Results Endpoint

### GET /election/{election}/results

**Endpoint:** `GET /elections/{election}/results`

**Purpose:** Retrieve aggregated election results

#### Response Format

**Response:**
```json
{
    "election": {
        "id": 789,
        "name": "Board Elections 2026",
        "status": "closed",
        "vote_count": 234
    },
    "posts": [
        {
            "post_id": 1,
            "post_name": "President",
            "is_national_wide": true,
            "candidates": [
                {
                    "candidate_id": 5,
                    "name": "Alice Johnson",
                    "organisation": "Engineering",
                    "vote_count": 95,
                    "percentage": 40.6
                },
                {
                    "candidate_id": 12,
                    "name": "Bob Smith",
                    "organisation": "Marketing",
                    "vote_count": 89,
                    "percentage": 38.0
                },
                {
                    "candidate_id": 18,
                    "name": "Charlie Brown",
                    "organisation": "Finance",
                    "vote_count": 50,
                    "percentage": 21.4
                }
            ],
            "abstention_count": 50,
            "abstention_percentage": 21.4
        }
    ]
}
```

#### Implementation

```php
public function index(Election $election)
{
    $votes = Vote::forElection($election)
                 ->with('results')
                 ->get();

    $totalVotes = $votes->count();

    $posts = Post::where('election_id', $election->id)->get();

    $results = [];
    foreach ($posts as $post) {
        $postResults = Result::forElection($election)
                            ->forPost($post->id)
                            ->selectRaw('candidate_id, COUNT(*) as vote_count')
                            ->groupBy('candidate_id')
                            ->orderByDesc('vote_count')
                            ->get();

        // Calculate abstentions for this post
        $abstentionCount = $votes->filter(function($vote) use ($post) {
            return in_array($post->id, $vote->no_vote_posts ?? []);
        })->count();

        $candidates = [];
        foreach ($postResults as $result) {
            if ($result->candidate_id) {
                $candidacy = Candidacy::find($result->candidate_id);
                $candidates[] = [
                    'candidate_id' => $result->candidate_id,
                    'name' => $candidacy->user->name,
                    'organisation' => $candidacy->user->organisation,
                    'vote_count' => $result->vote_count,
                    'percentage' => ($result->vote_count / $totalVotes) * 100,
                ];
            }
        }

        // Add abstention info
        $results[] = [
            'post_id' => $post->id,
            'post_name' => $post->name,
            'is_national_wide' => $post->is_national_wide,
            'candidates' => $candidates,
            'abstention_count' => $abstentionCount,
            'abstention_percentage' => ($abstentionCount / $totalVotes) * 100,
        ];
    }

    return response()->json([
        'election' => [
            'id' => $election->id,
            'name' => $election->name,
            'status' => $election->status,
            'vote_count' => $totalVotes,
        ],
        'posts' => $results,
    ]);
}
```

**Key Points:**
- Uses aggregated results (no user_id exposed)
- Includes abstention counts per post
- Shows vote percentages
- Completely anonymous data

---

## Backward Compatibility Notes

### Fields to REMOVE from API

If you still have these in responses, remove them immediately:

```php
// ❌ REMOVE these
'voting_code_used' => $code->code1,      // Replaced by vote_hash_prefix
'no_vote_option' => $vote->no_vote_option,  // Replaced by no_vote_posts
'user_id' => auth()->id(),               // Should NEVER be in API
'candidacy_id' => $result->candidacy_id,  // Replaced by candidate_id

// ✅ USE these instead
'vote_hash_prefix' => substr($vote->vote_hash, 0, 8) . '...',
'no_vote_posts' => $vote->no_vote_posts,  // Array of post IDs
// NO user_id in any response
'candidate_id' => $result->candidate_id,   // Direct reference to candidacies.id
```

### Migration Path for Clients

If you have a frontend client using the old API:

1. **Stop using `no_vote_option` boolean**
   ```js
   // OLD
   const noVote = true;

   // NEW
   const noVotePosts = [3, 5, 7];  // Array of specific post IDs
   ```

2. **Stop using `voting_code_used` for verification**
   ```js
   // OLD (UNSAFE)
   if (response.voting_code_used === submittedCode) {
       console.log("Vote confirmed");
   }

   // NEW (SAFE)
   if (response.vote_hash_prefix) {
       console.log("Vote confirmed - store hash for verification");
   }
   ```

3. **Update verification requests**
   ```js
   // OLD
   GET /vote/verify?voting_code=abc123

   // NEW
   GET /elections/{id}/vote/verify
   // (authentication token is enough)
   ```

---

## Error Responses

### Common Error Cases

**400 Bad Request - Invalid Candidates**
```json
{
    "success": false,
    "errors": {
        "national_selected_candidates.0.candidacy_id": [
            "The selected candidacy_id is invalid"
        ]
    }
}
```

**404 Not Found - Election Not Active**
```json
{
    "success": false,
    "message": "Election is not open for voting",
    "election_id": 789,
    "status": "closed"
}
```

**403 Forbidden - Already Voted**
```json
{
    "success": false,
    "message": "You have already voted in this election",
    "voted_at": "2026-03-01T15:30:00Z"
}
```

**403 Forbidden - No Voting Code**
```json
{
    "success": false,
    "message": "You do not have a voting code for this election"
}
```

---

## Example Client Implementation (JavaScript)

### Submitting a Vote

```javascript
async function submitVote(electionId, selectedCandidates, abstainedPostIds) {
    const response = await fetch(`/elections/${electionId}/vote/submit`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${authToken}`,
        },
        body: JSON.stringify({
            national_selected_candidates: selectedCandidates.national,
            regional_selected_candidates: selectedCandidates.regional,
            no_vote_posts: abstainedPostIds,  // Array of post IDs
        }),
    });

    const result = await response.json();

    if (result.success) {
        // Store vote hash for later verification
        localStorage.setItem(
            `vote_hash_${electionId}`,
            result.vote_info.vote_hash_prefix
        );

        console.log('Vote recorded successfully');
        console.log('Abstained from posts:', result.vote_info.abstained_from_posts);
    } else {
        console.error('Vote failed:', result.errors);
    }

    return result;
}
```

### Verifying a Vote

```javascript
async function verifyVote(electionId) {
    const response = await fetch(`/elections/${electionId}/vote/verify`, {
        headers: {
            'Authorization': `Bearer ${authToken}`,
        },
    });

    const result = await response.json();

    if (result.verified) {
        console.log('Vote verified!');
        console.log('Vote was recorded at:', result.vote_info.cast_at);
        console.log('Vote hash (prefix):', result.vote_info.vote_hash_prefix);
        return true;
    } else {
        console.log('Vote not found or not yet recorded');
        return false;
    }
}
```

---

## Next Steps

- **See how tests validate these changes?** → [06-testing-guide.md](./06-testing-guide.md)
- **Troubleshooting API issues?** → [07-troubleshooting.md](./07-troubleshooting.md)
- **Understand implementation?** → [04-implementation-guide.md](./04-implementation-guide.md)

---

**Summary:** The API now uses vote_hash for verification instead of voting_code, no_vote_posts array instead of no_vote_option boolean, and candidate_id instead of candidacy_id. All responses avoid exposing user_id or revealing voting choices, ensuring complete voter anonymity.

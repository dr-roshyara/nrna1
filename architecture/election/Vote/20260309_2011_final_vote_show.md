# 🤖 **CLAUDE CLI PROMPT: Fix Vote Display for Demo Elections**

```bash
## TASK: Fix Demo Vote Display in VoteController@show Method

### Current Problem
Demo votes are not displaying when accessing `/vote/show/{vote_id}`. The page shows "No vote data found" error.

### Root Cause Analysis
The `VoteController@show` method currently **only looks in the session** for vote data:

```php
public function show($vote_id)
{
    $sessionKey = 'vote_display_data_' . $vote_id;
    $voteDisplayData = session()->get($sessionKey);  // ❌ Only session!
    
    if (!$voteDisplayData) {
        return redirect()->route('vote.verify_to_show')
            ->withErrors(['session' => 'No vote data found.']);
    }
    // ...
}
```

**Real votes** store data in session, but **demo votes** need to be loaded from the database.

---

## 📋 **PHASE 1: Diagnose Current State**

```bash
# Check if demo vote exists in database
php artisan tinker
```

```php
$voteId = 'a14126b8-cfce-46b9-97b3-596f90f329cc'; // Replace with your vote_id
$vote = App\Models\DemoVote::find($voteId);

if ($vote) {
    echo "✅ Demo vote found in database!\n";
    echo "ID: {$vote->id}\n";
    echo "Election ID: {$vote->election_id}\n";
    echo "Created at: {$vote->created_at}\n";
    
    // Check if it has candidate data
    $hasCandidates = false;
    for ($i = 1; $i <= 60; $i++) {
        $col = 'candidate_' . str_pad($i, 2, '0', STR_PAD_LEFT);
        if (!empty($vote->$col)) {
            $hasCandidates = true;
            echo "✅ Has data in $col\n";
            break;
        }
    }
    
    if (!$hasCandidates) {
        echo "⚠️ No candidate data found in vote record\n";
    }
} else {
    echo "❌ Demo vote NOT found in database!\n";
}
```

---

## 📋 **PHASE 2: Implement the Fix in VoteController**

```bash
# Update VoteController.php with improved show method
Update(app/Http/Controllers/VoteController.php)
```

Replace the `show()` method with this enhanced version:

```php
/**
 * Display a specific vote record
 * 
 * Supports both:
 * - Real votes (loaded from session)
 * - Demo votes (loaded from database)
 * 
 * @param string $vote_id
 * @return \Inertia\Response|\Illuminate\Http\RedirectResponse
 */
public function show($vote_id)
{
    try {
        Log::info('🔍 VoteController@show called', [
            'vote_id' => $vote_id,
            'user_id' => auth()->id(),
        ]);

        // 1️⃣ Try to load from session first (for real votes)
        $sessionKey = 'vote_display_data_' . $vote_id;
        $voteDisplayData = session()->get($sessionKey);

        // 2️⃣ If not in session, try to load from database (for demo votes)
        if (!$voteDisplayData) {
            Log::info('📦 Vote not in session, trying database', [
                'vote_id' => $vote_id
            ]);

            $demoVote = DemoVote::find($vote_id);
            
            if ($demoVote) {
                Log::info('✅ Demo vote found in database', [
                    'vote_id' => $demoVote->id,
                    'election_id' => $demoVote->election_id,
                ]);

                $voteDisplayData = $this->prepareDemoVoteDisplayData($demoVote);
                
                // Mark as demo vote for frontend
                $voteDisplayData['is_demo_vote'] = true;
            }
        }

        // 3️⃣ If still not found, redirect with error
        if (!$voteDisplayData) {
            Log::warning('❌ Vote not found in session or database', [
                'vote_id' => $vote_id,
            ]);

            return redirect()->route('vote.verify_to_show')
                ->withErrors(['vote' => 'Vote record not found. Please verify your code again.']);
        }

        // 4️⃣ Validate data structure
        if (!$this->isValidVoteDisplayData($voteDisplayData)) {
            session()->forget($sessionKey);
            
            Log::warning('❌ Invalid vote display data', [
                'vote_id' => $vote_id,
            ]);

            return redirect()->route('vote.verify_to_show')
                ->withErrors(['data' => 'Invalid vote data. Please verify your code again.']);
        }

        Log::info('✅ Vote display data prepared successfully', [
            'vote_id' => $vote_id,
            'is_demo' => $voteDisplayData['is_demo_vote'] ?? false,
        ]);

        return Inertia::render('Vote/VoteShow', [
            'vote_data' => $voteDisplayData,
        ]);

    } catch (\Throwable $e) {
        Log::error('❌ Vote show page error', [
            'vote_id' => $vote_id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        return redirect()->route('vote.verify_to_show')
            ->withErrors(['system' => 'An error occurred while displaying the vote. Please try again.']);
    }
}

/**
 * Prepare demo vote data for display
 * 
 * @param \App\Models\DemoVote $vote
 * @return array
 */
private function prepareDemoVoteDisplayData($vote)
{
    // Process all 60 candidate columns
    $voteSelections = [];
    
    for ($i = 1; $i <= 60; $i++) {
        $column = 'candidate_' . str_pad($i, 2, '0', STR_PAD_LEFT);
        
        if (!empty($vote->$column)) {
            $selectionData = json_decode($vote->$column, true);
            
            if ($selectionData && is_array($selectionData)) {
                // Enrich with candidate information
                $enrichedSelection = $this->enrichSelectionData($selectionData);
                if ($enrichedSelection) {
                    $voteSelections[] = $enrichedSelection;
                }
            }
        }
    }

    // Get voter info from authenticated user
    $user = auth()->user();

    return [
        'vote_id' => $vote->id,
        'verification_successful' => true,
        'is_own_vote' => true, // Demo votes always belong to current user
        'voter_info' => [
            'name' => $user->name ?? 'Demo Voter',
            'user_id' => $user->user_id ?? 'DEMO',
            'region' => $user->region ?? 'Demo Region',
        ],
        'vote_info' => [
            'voted_at' => $vote->created_at ? $vote->created_at->format('M j, Y \a\t g:i A') : 'Unknown',
            'no_vote_option' => $vote->no_vote_option ?? false,
            'voting_code_used' => $vote->voting_code ?? 'N/A',
        ],
        'vote_selections' => $voteSelections,
        'summary' => [
            'total_positions' => count($voteSelections),
            'positions_voted' => count(array_filter($voteSelections, function($selection) {
                return !($selection['no_vote'] ?? false);
            })),
            'candidates_selected' => array_sum(array_map(function($selection) {
                return count($selection['candidates'] ?? []);
            }, $voteSelections)),
        ],
    ];
}

/**
 * Enrich selection data with candidate information
 * 
 * @param array $selectionData
 * @return array|null
 */
private function enrichSelectionData($selectionData)
{
    // If no candidates, return basic structure
    if (empty($selectionData['candidates'])) {
        return [
            'post_id' => $selectionData['post_id'] ?? null,
            'post_name' => $selectionData['post_name'] ?? 'Unknown Post',
            'required_number' => $selectionData['required_number'] ?? 1,
            'no_vote' => true,
            'candidates' => [],
        ];
    }

    $enrichedCandidates = [];
    
    foreach ($selectionData['candidates'] as $candidate) {
        $candidacyId = $candidate['candidacy_id'] ?? null;
        
        if ($candidacyId) {
            // Try to load from DemoCandidacy for demo votes
            $demoCandidacy = DemoCandidacy::withoutGlobalScopes()
                ->where('id', $candidacyId)
                ->orWhere('candidacy_id', $candidacyId)
                ->first();

            if ($demoCandidacy) {
                $enrichedCandidates[] = [
                    'candidacy_id' => $demoCandidacy->id,
                    'candidacy_name' => $demoCandidacy->name ?? 'Demo Candidate',
                    'user_name' => $demoCandidacy->user_name ?? $demoCandidacy->name,
                    'position_order' => $demoCandidacy->position_order ?? 0,
                ];
            } else {
                // Fallback with data from vote
                $enrichedCandidates[] = [
                    'candidacy_id' => $candidacyId,
                    'candidacy_name' => $candidate['user_name'] ?? 'Candidate',
                    'user_name' => $candidate['user_name'] ?? 'Candidate',
                    'position_order' => $candidate['position_order'] ?? 0,
                ];
            }
        }
    }

    return [
        'post_id' => $selectionData['post_id'] ?? null,
        'post_name' => $selectionData['post_name'] ?? 'Unknown Post',
        'required_number' => $selectionData['required_number'] ?? 1,
        'no_vote' => false,
        'candidates' => $enrichedCandidates,
    ];
}
```

---

## 📋 **PHASE 3: Update Frontend Component**

```bash
# Update the VoteShow.vue component to handle demo votes
Read(resources/js/Pages/Vote/VoteShow.vue)
```

Add this to the script section:

```javascript
// In props, add is_demo_vote flag
props: {
    vote_data: {
        type: Object,
        required: true
    },
},

// In methods, update navigation
methods: {
    goToVerifyAnother() {
        if (this.vote_data.is_demo_vote) {
            this.$inertia.visit(route('demo-vote.verify_to_show'));
        } else {
            this.$inertia.visit(route('vote.verify_to_show'));
        }
    },
    
    goToDashboard() {
        this.$inertia.visit(route('dashboard'));
    },
}
```

---

## 📋 **PHASE 4: Add Debug Logging**

```bash
# Add temporary debug logging to track the issue
Update(app/Http/Controllers/VoteController.php)
```

Add at the beginning of `show()`:

```php
public function show($vote_id)
{
    // 🔴 DEBUG
    Log::emergency('========== VOTE SHOW DEBUG ==========');
    Log::emergency('1. Called with vote_id: ' . $vote_id);
    Log::emergency('2. Session keys: ' . json_encode(array_keys(session()->all())));
    
    $sessionKey = 'vote_display_data_' . $vote_id;
    Log::emergency('3. Looking for session key: ' . $sessionKey);
    Log::emergency('4. Session has key: ' . (session()->has($sessionKey) ? 'YES' : 'NO'));
    
    // ... rest of method
}
```

---

## 📋 **PHASE 5: Verification**

```bash
# 1. Clear logs
echo "" > storage/logs/laravel.log

# 2. Run a test vote and view it
# Navigate to: /election/demo/start
# Complete voting
# Click to view vote

# 3. Check logs
tail -50 storage/logs/laravel.log | grep "VOTE SHOW DEBUG\|VoteController@show"
```

---

## 📋 **SUMMARY**

| Problem | Fix | Status |
|---------|-----|--------|
| ❌ Only looks in session | ✅ Check database for demo votes | Ready |
| ❌ No demo vote data | ✅ `prepareDemoVoteDisplayData()` method | Ready |
| ❌ Wrong return path | ✅ `is_demo_vote` flag + conditional redirect | Ready |

**Run these commands in order and report the results!** 🚀
```
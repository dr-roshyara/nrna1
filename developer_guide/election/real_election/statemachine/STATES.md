# Election States and Transitions

## State Overview

The election lifecycle consists of five states, each with distinct characteristics and allowed operations.

## 1. Administration ⚙️

**Identifier**: `administration`

**Description**: Initial setup phase where election structure is configured.

### Activation Conditions
- Election is just created (default state)
- OR `administration_completed = false` AND `nomination_suggested_start` is null

### Duration
- **Suggested Start**: `administration_suggested_start` (nullable, set by admin)
- **Suggested End**: `administration_suggested_end` (nullable, set by admin)
- **Actual Start**: Election creation time
- **Actual End**: `administration_completed_at` (when completed)

### Allowed Actions
- `manage_posts` - Create, edit, delete positions
- `import_voters` - Upload voter list
- `manage_committee` - Add/remove election officers
- `configure_election` - Change election settings

### Blocked Actions
- `apply_candidacy` - Cannot apply for positions yet
- `cast_vote` - Voting not open
- `publish_results` - No votes yet

### Requirements to Complete
```
✓ At least one post exists
✓ At least one voter imported
✓ (Optional) Committee members assigned
```

### Transition Trigger
Call `completeAdministration()` or wait for grace period auto-transition:

```php
$election->completeAdministration(
    reason: 'Manual completion by admin',
    actorId: auth()->id()
);
// Sets: administration_completed = true
//       administration_completed_at = now()
//       nomination_suggested_start = now() + 1 day (if not set)
//       nomination_suggested_end = now() + 3 days (if not set)
// Moves to: Nomination phase
```

### Auto-Transition (Grace Period)
If `allow_auto_transition = true` and `administration_suggested_end + auto_transition_grace_days < now()`:
- System automatically calls `completeAdministration()`
- Triggered by: `elections:process-grace-periods` command (daily)

### UI/Frontend Behavior
```vue
<StateMachinePhaseCard
  phase-name="Administration"
  status="In Progress"
  :show-complete-button="true"
  :show-update-dates-button="true"
  :metrics="{ posts: 5, voters: 120 }"
  :suggested-end="administration_suggested_end"
/>
```

### Example Timeline
```
Mon Jan 1   08:00  Election created
            08:00  Posts created (Req ✓)
            14:00  Voters imported (Req ✓)
Tue Jan 2   10:00  Admin clicks "Complete Administration"
            10:00  → Nomination phase begins
            →      Nomination suggested dates auto-set
```

---

## 2. Nomination 📋

**Identifier**: `nomination`

**Description**: Candidates apply and are approved for positions.

### Activation Conditions
- `administration_completed = true`
- AND NOT (`voting_starts_at` is set AND `now()` >= `voting_starts_at`)

### Duration
- **Suggested Start**: `nomination_suggested_start` (auto-set when admin completes)
- **Suggested End**: `nomination_suggested_end` (auto-set when admin completes)
- **Actual Start**: `administration_completed_at`
- **Actual End**: `nomination_completed_at` (when completed)

### Allowed Actions
- `apply_candidacy` - Submit candidate application
- `approve_candidacy` - Review and approve candidates
- `view_candidates` - See candidate list and applications
- `manage_committee` - Adjust committee if needed

### Blocked Actions
- `manage_posts` - Cannot change posts now
- `cast_vote` - Voting not open
- `publish_results` - No votes yet

### Requirements to Complete
```
✓ No pending candidacies (all approved or rejected)
✓ At least one candidate approved for each post
  (can force-close to auto-reject pending)
```

### Transition Triggers

**Manual Completion**:
```php
$election->completeNomination(
    reason: 'All candidates approved',
    actorId: auth()->id()
);
// Validates: no pending candidacies, at least one approved
// Sets: nomination_completed = true
//       nomination_completed_at = now()
//       voting_starts_at = now() + 1 day (if not set)
//       voting_ends_at = now() + 4 days (if not set)
// Moves to: Voting phase
```

**Force Close**:
```php
$election->forceCloseNomination(
    reason: 'Admin override',
    actorId: auth()->id()
);
// Auto-rejects all pending candidacies
// Sets: nomination_completed = true
//       Moves to: Voting phase
```

**Auto-Transition (Grace Period)**:
If `allow_auto_transition = true` and `nomination_suggested_end + auto_transition_grace_days < now()`:
- System auto-rejects pending candidacies
- Auto-completes nomination
- Triggered by: `elections:process-grace-periods` command

### UI/Frontend Behavior
```vue
<StateMachinePhaseCard
  phase-name="Nomination"
  status="In Progress"
  :show-complete-button="can_complete_nomination"
  :show-force-close-button="true"
  :metrics="{ 
    approved: 15, 
    pending: 3 
  }"
  :candidate-stats="true"
/>
```

### Example Timeline
```
Tue Jan 2   10:00  Nomination begins
Wed Jan 3   18:00  Candidate applications accepted
             09:00  5 applications received
Sat Jan 5   14:00  All applications approved
             14:00  Admin clicks "Complete Nomination"
             14:00  → Voting phase begins
             →      Voting dates auto-set (Jan 7 - Jan 10)
```

---

## 3. Voting 🗳️

**Identifier**: `voting`

**Description**: Election members cast their votes in a strict time window.

### Activation Conditions
- `nomination_completed = true`
- AND `voting_starts_at` <= `now()` <= `voting_ends_at`

### Duration
- **Voting Window**: `voting_starts_at` to `voting_ends_at` (STRICT, no manual override)
- **Auto-Lock**: Voting closes automatically when `voting_ends_at` is reached

### Allowed Actions
- `cast_vote` - Submit vote selections
- `verify_vote` - Check previous vote (after voting)
- `view_candidates` - See candidate details

### Blocked Actions
- `apply_candidacy` - No new candidates
- `complete_phase` - Cannot manually transition (strict time window)
- `update_dates` - Voting dates locked (cannot change)

### Requirements to Complete
```
✓ Must wait for voting_ends_at to be reached
✓ No manual completion allowed (integrity critical)
✓ Automatic transition to Results Pending when time expires
```

### Transition Trigger

**Automatic (No Manual Override)**:
- When `now()` > `voting_ends_at`:
  - State automatically becomes `results_pending`
  - No action needed
  - No logging (implicit in timestamp)

```php
// The state machine automatically transitions
// No method to call - it's based on time
if ($now->greaterThan($election->voting_ends_at)) {
    return 'results_pending';
}
```

**Why Automatic?**
- Ensures voting integrity
- Prevents manipulation of close time
- Cannot be overridden by admins or bugs
- Critical for democratic process

### Important Constraints

```
⚠️ CRITICAL: Voting dates CANNOT be changed once voting_starts_at is reached
⚠️ CRITICAL: Voting cannot be extended or shortened
⚠️ CRITICAL: Cannot manually transition out of voting
✓ Only forward progression allowed
✓ Results Pending reached automatically at voting_ends_at
```

### UI/Frontend Behavior
```vue
<StateMachinePhaseCard
  phase-name="Voting"
  status="In Progress"
  :show-countdown="true"
  :voting-end-time="voting_ends_at"
  :voting-start-time="voting_starts_at"
  :allow-manual-complete="false"
  :metrics="{ 
    voted: 87, 
    total: 120 
  }"
  :show-voting-live-counter="true"
/>
```

### Voting Flow (5 Steps)

Each voter goes through:

1. **Code Entry** - Enter unique voting code
2. **Agreement** - Accept terms and conditions
3. **Vote Selection** - Choose candidates
4. **Vote Review** - Verify selections
5. **Vote Confirmation** - Confirm and submit

### Example Timeline
```
Sun Jan 7   08:00  Voting begins (voting_starts_at)
Mon Jan 8   14:00  87 voters have cast votes
Tue Jan 9   23:59  Last vote accepted
Wed Jan 10  00:00  Voting window closes (voting_ends_at)
            00:00  → Results Pending phase (AUTOMATIC)
```

---

## 4. Results Pending ⏳

**Identifier**: `results_pending`

**Description**: Voting is complete, results can be verified, awaiting publication.

### Activation Conditions
- `nomination_completed = true`
- AND `voting_starts_at` and `voting_ends_at` are set
- AND `now()` > `voting_ends_at`
- AND `results_published_at` IS NULL

### Duration
- **Start**: Automatically when voting ends
- **End**: When admin publishes results

### Allowed Actions
- `verify_vote` - Members verify their vote was recorded
- `view_results` - See results (may or may not be public depending on publication)
- `download_receipt` - Get vote receipt

### Blocked Actions
- `cast_vote` - Voting is closed
- `apply_candidacy` - Election closed
- `complete_phase` - No manual completion needed

### Requirements to Complete
```
✓ Results must be manually published
✓ No automatic publication
✓ Admin explicitly triggers publication
```

### Transition Trigger

**Manual Publication**:
```php
// In controller or command
$election->results_published_at = now();
$election->save();
// Sets: results_published_at = now()
// Moves to: Results phase (automatically detected)
```

**Why Manual?**
- Results are sensitive
- Admin needs time to verify
- May coordinate with announcement
- Prevents accidental publication

### UI/Frontend Behavior
```vue
<StateMachinePhaseCard
  phase-name="Results Pending"
  status="Awaiting Publication"
  :show-publish-button="true"
  :show-verification-link="true"
  :results-verified="false"
  :results-count="87"
/>
```

### Example Timeline
```
Wed Jan 10  00:00  Voting closes
            00:00  → Results Pending phase
            09:00  Admin logs in
            09:30  Verifies vote counts
            09:45  Admin clicks "Publish Results"
            09:45  → Results phase
            10:00  Results page goes live
```

---

## 5. Results 📊

**Identifier**: `results`

**Description**: Results are published and final.

### Activation Conditions
- `results_published_at` IS NOT NULL (only way to enter)

### Duration
- **Start**: When `results_published_at` is set
- **End**: Never (final state)

### Allowed Actions
- `view_results` - Public results visible
- `verify_vote` - Members can still verify their vote
- `download_receipt` - Get vote receipts

### Blocked Actions
- `cast_vote` - Election finished
- `apply_candidacy` - Election finished
- `modify_anything` - Everything locked

### Requirements
```
✓ Results published (no way back)
✓ Election is final and immutable
```

### Transition Trigger

**No Transition Out**:
```php
// Results is final - no transition away
$election->results_published_at = now();
$election->save();
// Permanent state: 'results'
```

### UI/Frontend Behavior
```vue
<StateMachinePhaseCard
  phase-name="Results"
  status="Published"
  :show-results="true"
  :allow-modifications="false"
  :show-completed-checkmark="true"
  :published-at="results_published_at"
/>
```

### Example Timeline
```
Wed Jan 10  09:45  Results published
            10:00  Results page live
Fri Jan 12  Various  Voters verify votes
            Various  Results remain visible
∞           Forever  Election results archived
```

---

## State Transition Diagram

```
┌──────────────────────────────────────────────────────────┐
│                    ELECTION LIFECYCLE                    │
└──────────────────────────────────────────────────────────┘

  START
    ↓
┌─────────────────────┐
│  Administration ⚙️   │  (Manual or Grace Period)
│  (Setup Election)   │
└─────────────────────┘
    ↓ completeAdministration() / Auto-transition
┌─────────────────────┐
│   Nomination 📋     │  (Manual or Grace Period)
│  (Candidates Apply) │
└─────────────────────┘
    ↓ completeNomination() / forceCloseNomination() / Auto-transition
┌─────────────────────┐
│    Voting 🗳️        │  (STRICT TIME WINDOW)
│ (Cast Votes, Verify)│
└─────────────────────┘
    ↓ AUTOMATIC when voting_ends_at reached
┌─────────────────────┐
│ Results Pending ⏳  │  (Manual Publication)
│ (Verify, Wait)      │
└─────────────────────┘
    ↓ publishResults() [Manual Admin Action]
┌─────────────────────┐
│   Results 📊        │  (FINAL STATE)
│  (Published, Final) │
└─────────────────────┘
    ↓
   END
```

## Constraints and Rules

### Immutability
- ✅ Phases always progress forward
- ❌ Cannot go backward
- ❌ Cannot skip phases
- ✅ Can stay in same phase indefinitely

### Timing
```
administration_suggested_end  ≤  nomination_suggested_start
nomination_suggested_start    ≤  nomination_suggested_end
nomination_suggested_end      ≤  voting_starts_at
voting_starts_at              ≤  voting_ends_at
voting_ends_at                ≤  results_published_at (if published)
```

### Override Rules
```
Administration: Can override via completeAdministration()
Nomination:     Can override via completeNomination() or forceCloseNomination()
Voting:         NO OVERRIDE (integrity critical)
Results:        NO OVERRIDE (final state)
```

## References

- See [MODELS.md](MODELS.md) for method signatures
- See [DATABASE.md](DATABASE.md) for column details
- See [EXAMPLES.md](EXAMPLES.md) for code samples
- See [TESTING.md](TESTING.md) for test patterns

---

**Key Principle**: Elections progress through states sequentially based on time and completion. Voting window is strict and cannot be overridden.

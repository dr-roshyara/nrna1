```prompt
@claude I need you to fix the election state machine by aligning the SSOT engine (ElectionLifecycleEngine) with the actual business flow. The engine currently has a simplified state derivation that doesn't match reality.

## Current Engine Logic (What Exists)

The engine in ElectionLifecycleEngineImpl::deriveState() computes state in this order:
1. results_published_at → ResultsPublished
2. voting window active → VotingActive
3. voting ended → Counting
4. setup complete + candidates approved → ReadyForVoting
5. administration_completed → Setup
6. default → Draft

## Actual Business Flow (What Should Exist)

When an organisation owner creates an election:

1. **Draft** — election created, nothing configured
2. Owner specifies voter count — ≤40 voters = free plan, >40 voters = requires platform approval + payment
3. Owner sets timezone — all state machine times must be valid in that timezone
4. If >40 voters: election goes to **submitted_for_approval** → platform owner reviews → approves after payment → proceeds
5. If ≤40 voters: auto-approved → proceeds to Setup
6. **Setup** — administration configured, posts defined, committees assigned
7. **ReadyForVoting** — setup complete, candidates approved, voting window defined
8. **VotingActive** — within voting window
9. **Counting** — voting ended, results pending
10. **ResultsPublished** — results published
11. **Archived** — election complete

## Current Problem

Election test-election-1779056138 is stuck showing "403: Operation configure_election is not allowed during the Unknown phase" because:
- DB.state = "import_voters" (old state machine value not recognized by new engine)
- SSOT engine computes state = "draft"
- Transition matrix doesn't recognize "import_voters" so returns "Unknown"

## What To Implement

### 1. Add Missing States to ElectionLifecycleState Enum
File: app/Domain/Election/Enum/ElectionLifecycleState.php

Add these cases if they don't exist:
- SubmittedForApproval = 'submitted_for_approval'
- Approved = 'approved'  
- Rejected = 'rejected'

### 2. Update the Engine's deriveState() Logic
File: app/Application/Election/Services/ElectionLifecycleEngineImpl.php

Add checks BEFORE the current logic:
- If rejected_at is set → Rejected
- If approved_at is set but administration not started → Approved
- If submitted_for_approval_at is set but not yet approved/rejected → SubmittedForApproval
- Then continue with existing checks (ResultsPublished → VotingActive → Counting → ReadyForVoting → Setup → Draft)

### 3. Update ElectionConstitution Rules
File: app/Domain/Election/Constitution/ElectionConstitution.php

Add transition rules for the new states:
- configure_election: allowed from [draft, approved]
- submit_for_approval: allowed from [draft], precondition: voter_count > 40
- approve: allowed from [submitted_for_approval], allowed_roles: [platform_owner]
- reject: allowed from [submitted_for_approval], allowed_roles: [platform_owner]
- start_setup: allowed from [draft, approved], preconditions: [timezone_set, voter_count_defined, plan_approved]
- Auto-approval rule: if voter_count ≤ 40, transition from draft → approved is automatic

### 4. Add Missing Precondition Checks
The engine or guard should validate:
- timezone_set: election.timezone is not null
- voter_count_defined: expected_voter_count > 0
- plan_approved: either auto (≤40) or approved_at is set

### 5. Fix the Stuck Election
After implementing the above, repair test-election-1779056138:
- Compute correct state from business facts
- Update DB.state to match SSOT computed state
- Verify management page loads without 403

## Constraints

- Do NOT modify the existing transition rules for VotingActive, Counting, ResultsPublished
- Do NOT change the ConstitutionalTransitionGuard interface
- Keep backward compatibility with existing state values where possible
- The engine is authoritative — database columns are cached interpretations
- Write tests for any new states or transitions added

## Files You'll Need

- app/Domain/Election/Enum/ElectionLifecycleState.php
- app/Application/Election/Services/ElectionLifecycleEngineImpl.php
- app/Domain/Election/Constitution/ElectionConstitution.php
- app/Application/Election/Services/ConstitutionalTransitionGuard.php
- app/Models/Election.php (check which columns exist: submitted_for_approval_at, approved_at, rejected_at, expected_voter_count)

Start by reading these files, mapping the current state, then implementing changes in the order above.
```
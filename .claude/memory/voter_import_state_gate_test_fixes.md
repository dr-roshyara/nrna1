---
name: voter_import_state_gate_test_fixes
description: Fixed voter import test fixtures to work with state gate requiring setup_administration state (2026-05-31)
metadata: 
  node_type: memory
  type: feedback
  originSessionId: 2714c45f-1abf-48d7-a57a-061f406524d3
---

## Voter Import Test Fixes - State Gate Compatibility

**What:** Fixed CsvVoterImportTest and VoterImportElectionOnlyTest to work with the voter import state gate that requires elections in `setup_administration` state.

**Root Cause:** The state gate uses `ElectionLifecycle::of($election)->state()->value` (SSOT architecture) to COMPUTE state from constitutional facts, not read a cached `state` column. Tests were creating elections without setting these facts, so computed state was `draft`, causing 403 Forbidden.

**How to Apply:**
1. For election factories, set constitutional facts instead of relying on `state` column:
   - `submitted_for_approval_at` (NOW - 1 day)
   - `approved_at` (NOW - 1 day)  
   - `setup_started_at` (NOW - 6 hours)
   - `administration_completed` (false)
2. For file imports: use `.csv` extension, not `.xlsx` with CSV content
3. For tests making HTTP requests: add `withSession(['current_organisation_id' => ...])` to each request
4. For election-only mode tests: set `voter_source_strategy = 'election_only'`

**Files Modified:**
- `tests/Feature/Election/CsvVoterImportTest.php` - Updated election factory + fixed file extensions (5/5 passing)
- `tests/Feature/Voter/VoterImportElectionOnlyTest.php` - Added session context, set voter_source_strategy (14/15 passing, 1 pre-existing failure)
- `tests/Feature/Election/VoterImportStateGateTest.php` - Already correct (9/9 passing)

**Why SSOT Matters:**
- State column is a cache; constitutional facts are source of truth
- If someone updates `setup_started_at` or `administration_completed`, computed state changes immediately
- Using cached column would show stale state and allow imports at wrong times

**Test Results:**
- CsvVoterImportTest: 5/5 passing ✅
- VoterImportElectionOnlyTest: 14/15 passing (1 rate-limiting test pre-existing)
- VoterImportStateGateTest: 9/9 passing ✅

**Implementation Details (Commits e64e23fe3, cfb8065d1):**
- Election factories now include constitutional facts for setup_administration state
- CSV file handling fixed (fake files need proper extensions)
- Session context set via withSession() on HTTP requests
- voter_source_strategy explicitly set to election_only for test elections

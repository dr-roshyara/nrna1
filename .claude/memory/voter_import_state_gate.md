---
name: voter_import_state_gate
description: Voter import is gated to setup_administration state only (2026-05-31)
metadata: 
  node_type: memory
  type: feedback
  originSessionId: 2714c45f-1abf-48d7-a57a-061f406524d3
---

## Voter Import State Gate Implementation

**What:** Voter import operations (page access, preview, template download, import action) are now restricted to `setup_administration` state only.

**Why:** Business requirement - voter import should only be possible during the administration setup phase when governance infrastructure is being built. Prevents accidental/unauthorized voter imports in draft, voting, or results phases.

**How to apply:**
- Elections in any state except `setup_administration` receive HTTP 403 Forbidden when accessing import endpoints
- The gate is implemented via `assertAdministrationSetupState()` private method in `VoterImportController`
- Security logging captures state violation attempts with election ID, user ID, and IP address
- `publicTutorial()` is intentionally ungated as it's informational and has no election context

**Files modified:**
- `app/Http/Controllers/Election/VoterImportController.php` - added gate to create(), template(), preview(), import()
- `tests/Feature/Election/VoterImportStateGateTest.php` - new file with 9 comprehensive test cases
- `tests/Feature/Election/CsvVoterImportTest.php` - updated to use setup_administration state
- `tests/Feature/Voter/VoterImportElectionOnlyTest.php` - updated to use setup_administration state

**Implementation details:**
- Gate uses **ElectionLifecycle::of($election)->state()->value** (not direct $election->state)
- Complies with constitutional SSOT architecture: state is COMPUTED from facts, not stored
- Constitutional facts required for setup_administration state:
  - `setup_started_at IS NOT NULL`
  - `administration_completed = false`
- Aborts with 403 + security log entry if state doesn't match
- Exact state value is `'setup_administration'` (enum case: `SetupAdministration`)

**Why SSOT matters for this gate:**
- State column is a cache; constitutional facts are the source of truth
- If someone updates `setup_started_at` or `administration_completed`, the computed state changes immediately
- Using cached column would show stale state and allow imports at wrong times
- ElectionLifecycleEngine derives state from facts, ensuring consistency

# PBDIGIT-217 — A leftover demo session flag skips the lifecycle check on real-election slug routes (step 1)

**Type:** Defect (security, lifecycle) · **Epic:** `PBDIGIT-EPIC-05` Voting · **Created:** 2026-10-04
**Raised by:** `claude-code-session:9e4925bb-4151-4185-9e9c-5831184e54eb` (self-declared, not attestable)
**Placement derived:** `doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0)
**Found by:** the PBDIGIT-213 C3 `can_vote` classification and bypass analysis (2026-10-04). See `developer_guide/election/voter_admission/03_c3_identity_admission.md` §Legacy classification.

| | |
|---|---|
| **Status** | 🟠 **OPEN — not admission-related; admission is still enforced.** Not authorised. |
| **Problem** | `EnsureDemoElection` writes `session('selected_election_type','demo')` (:41) and nothing clears it. On real `v/{vslug}` routes `VoteEligibility.php:37` then returns before its lifecycle check, and `CodeController::isUserEligible` returns true (:686). |
| **Effect** | `slug.code.create`/`slug.code.store` (step 1) can run outside the voting window. `ensure.election.voter` (standing, incl. admission) still runs; steps 3–5 keep `voting.active`. |
| **Suggested test boundary** | Real slug at code.create with the demo session value set while voting is closed must be blocked. |

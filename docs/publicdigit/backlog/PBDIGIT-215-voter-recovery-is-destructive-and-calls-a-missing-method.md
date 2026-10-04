# PBDIGIT-215 — Voter recovery deactivates all slugs non-atomically, then calls a method that does not exist

**Type:** Defect (latent, destructive) · **Epic:** `PBDIGIT-EPIC-05` Voting · **Created:** 2026-10-04
**Raised by:** `claude-code-session:9e4925bb-4151-4185-9e9c-5831184e54eb` (self-declared, not attestable)
**Placement derived:** `doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0)
**Found by:** the PBDIGIT-213 C3 `can_vote` classification and bypass analysis (2026-10-04). See `developer_guide/election/voter_admission/03_c3_identity_admission.md` §Legacy classification.

| | |
|---|---|
| **Status** | 🟡 **OPEN — latent; currently unreachable.** Not authorised. |
| **Problem** | `admin.voting.security.recovery` (`Admin/VotingSecurityController.php:160–181`) and `election:recover-voter` (`GenerateRecoverySlug.php:59–102`) gate on the dropped legacy `users.can_vote`/`is_voter` columns, then deactivate **all** of the user's active slugs (every election, no transaction) and call `VotingSecurityService::secureSlugGeneration()` — **which does not exist** (Error, not caught by `catch (\Exception)`). |
| **Why unreachable today** | The dropped columns read `null`, so the gate refuses everyone before the destructive part (pinned by `LegacyVotingFlagsBoundaryTest::test_a_recovery_request_cannot_issue_a_credential…`). |
| **Business consequence** | If the gate is ever "fixed" naïvely, a recovery attempt would disrupt a voter's credentials and still issue nothing. |
| **Suggested fix** | Gate on `VotingEntitlementQuery`; make the deactivate+issue atomic; implement or remove the missing call. |

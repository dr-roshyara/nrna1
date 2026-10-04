# PBDIGIT-218 — Legacy `POST /votes` may evaluate entitlement against the wrong election

**Type:** Defect (latent) · **Epic:** `PBDIGIT-EPIC-05` Voting · **Created:** 2026-10-04
**Raised by:** `claude-code-session:9e4925bb-4151-4185-9e9c-5831184e54eb` (self-declared, not attestable)
**Placement derived:** `doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0)
**Found by:** the PBDIGIT-213 C3 `can_vote` classification and bypass analysis (2026-10-04). See `developer_guide/election/voter_admission/03_c3_identity_admission.md` §Legacy classification.

| | |
|---|---|
| **Status** | 🟡 **OPEN — latent; refused today.** Not authorised. |
| **Problem** | `POST /votes` (`vote.store`, routes/election/electionRoutes.php:169, middleware `vote.eligibility` only) has no slug; its legacy branch gates on the dropped `users.can_vote` (`User::isEligibleToVote`, VoteEligibility.php:107 → refuses everyone). If that gate were passed, `VoteController::getElection()` can fall back to `Election::where('type','real')->first()` (:109), so the in-transaction entitlement check could judge the wrong election. |
| **Suggested fix** | Retire the route, or resolve the election from the voter's code/slug and decide via `VotingEntitlementQuery`. |

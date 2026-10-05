# PBDIGIT-224 — The voter's own slug step record stores the vote id (stored voter → ballot link)

**Type:** Defect — **constitutional (ballot secrecy)** · **Severity:** Critical · **Epic:** `PBDIGIT-EPIC-05` Voting · **Created:** 2026-10-05
**Found by:** Phase 1 characterization · evidence report `docs/publicdigit/reviews/2026-10-05-phase1-characterization-evidence-report.md`
**Placement derived:** `doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0)

| | |
|---|---|
| **Status** | 🟡 **OPEN — decision pending (Phase 2.0 Anonymity ADR); mechanism kept unchanged until then** (PO, 2026-10-05; accepted interim risk, roadmap §2a). New finding of Phase 1 (A6). |
| **Defect** | `VoteController::store()` records Step 5 through `VoterStepTrackingService::completeStep($voterSlug, $election, 5, [..., 'vote_id' => $this->out_code])`. `voter_slug_steps.step_data` of the voter's slug therefore holds the vote id, and `voter_slugs.user_id` names the voter. This is a **stored** relation, not merely a computable one. |
| **Evidence (FACT, synthetic data)** | `BallotAnonymityCharacterizationTest::test_k9_known_gap_the_voters_own_slug_step_record_stores_the_vote_id` — step 5 row of the voter's slug contains the vote id; the slug's `user_id` is the voter. Source: `VoteController.php:1772` (`completeStep(…, 5, [..., 'vote_id' => $this->out_code])`), `:1776` (log). The same request also logs `voter_slug_id` with `vote_id` ("Step 5 recorded", see PBDIGIT-225). |
| **Affected boundary** | voting-process step tracking (`voter_slug_steps`) ↔ ballot |
| **Constitutional impact** | A direct, queryable voter → ballot mapping for every cast vote. |
| **Historical data** | All existing step-5 rows of real elections. |
| **Proposed phase** | Phase 2 (with PBDIGIT-223) · **ADR required** (Phase 2.0 — step tracking must not carry ballot identity; historical remediation). |

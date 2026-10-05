# PBDIGIT-226 — Voter-side timestamps join to exactly one ballot (A2)

**Type:** Defect — **constitutional (ballot secrecy)** · **Severity:** High · **Epic:** `PBDIGIT-EPIC-05` Voting · **Created:** 2026-10-05
**Found by:** Phase 1 characterization · evidence report `docs/publicdigit/reviews/2026-10-05-phase1-characterization-evidence-report.md`
**Placement derived:** `doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0)

| | |
|---|---|
| **Status** | 🟡 **OPEN — decision pending (Phase 2.0 Anonymity ADR); unchanged until then** (PO, 2026-10-05, roadmap §2a). Interim rule: no **new** voter-side timestamp in the cast transaction. |
| **Defect** | In the same request the voter's code gets `code_to_save_vote_used_at = now()` (and `updated_at`), and the vote gets `cast_at = now()` (and `created_at`). A join on the second links them. (Correction of the earlier SR claim: `codes` has **no** `vote_completed_at` column — `markUserAsVoted`'s `'vote_completed_at'` is silently dropped.) |
| **Evidence (FACT, synthetic data)** | `BallotAnonymityCharacterizationTest::test_k6_known_gap_…` — three voters cast 5 minutes apart: each code's `code_to_save_vote_used_at` matches exactly one `votes.cast_at` (±1 s), and each `codes.updated_at` exactly one `votes.created_at`. Sources: `VoteController::markUserAsVoted:1924-1941`, `BaseVote.php:219-220`. |
| **Affected boundary** | voter-side `codes` timestamps ↔ ballot timestamps |
| **Constitutional impact** | Practical correlation without any hash, IP or log. In low-traffic periods the match is unique. |
| **Proposed phase** | Phase 2.2 · **ADR required** (timestamp policy: voter-side participation time coarse or absent). Also constrains the PBDIGIT-229 fix. |

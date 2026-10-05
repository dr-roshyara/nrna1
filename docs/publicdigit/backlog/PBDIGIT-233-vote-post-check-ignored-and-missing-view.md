# PBDIGIT-233 — `vote_post_check` result ignored; its denials crash on a missing view; unset IP limit blocks every vote (D1, D10)

**Type:** Defect — robustness / availability · **Severity:** High · **Epic:** `PBDIGIT-EPIC-05` Voting · **Created:** 2026-10-05
**Found by:** Phase 1 characterization · evidence report `docs/publicdigit/reviews/2026-10-05-phase1-characterization-evidence-report.md`
**Placement derived:** `doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0)

| | |
|---|---|
| **Status** | 🟢 **RESOLVED for the final cast (2026-10-05)** — Phase 4 E3: real elections are decided by `VotingAuthorization::forCast()` with reasoned refusals (`BallotMissing`, `CodeMissing`, `AlreadyVotedPreCheck`); `vote_post_check()` is no longer on the real-election final-cast path (option A — demo keeps its unchanged call), so D1 (ignored result) and D10 (unset `MAX_USE_IP_ADDRESS` crashing every real vote) are gone **from the final cast**; the config-based IP count is not carried over (P4-D1 = B, the Trust → Legitimacy chain is the one authority). Contracts `CastDecisionEnforcementTest` R3-1…R3-7. **Still open:** the `MAX_USE_IP_ADDRESS` ops item (code entry still reads it — Phase 5/6); `vote_post_check()` copies in `DemoVoteController` / `DeligateVoteController` and the `VoteController::verify()` call (step 4) — unchanged. *(Original status: OPEN — not authorised, Phase 4.)* |
| **Defect** | (D1) `store()` assigns `$pre_check = $this->vote_post_check(...)` and never reads it. (D10) every error branch of `vote_post_check` renders `view('components.error_message')`, **which does not exist**, so it throws instead of returning; and `config('app.max_use_clientIP')` = `env('MAX_USE_IP_ADDRESS')` has **no default** — when unset, `0 >= null` is true and **every real vote** hits the IP branch, crashes, and the cast fails with a generic error. |
| **Evidence (FACT, synthetic data)** | `FinalDenialCharacterizationTest::test_i_known_gap_the_pre_check_result_is_never_read_by_store`, `::test_i_a_failing_pre_check_condition_still_blocks_through_a_missing_view_exception`; the Phase 1 fixture must set `app.max_use_clientIP` (test-only) or no cast succeeds (`CastFixtures::makeVotingElection`). Sources: `VoteController.php:1726`, `:3006-3104`; `config/app.php:149`. |
| **Affected boundary** | final cast boundary — pre-checks; deployment configuration |
| **Constitutional impact** | denials happen only by crashing (no reason shown); an environment without `MAX_USE_IP_ADDRESS` cannot accept any real vote. |
| **Proposed phase** | Phase 4 · ops: confirm `MAX_USE_IP_ADDRESS` is set in every environment now · overlaps the IP-density consolidation (PBDIGIT-45). |

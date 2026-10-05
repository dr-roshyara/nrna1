# PBDIGIT-225 — The cast logs correlate voter and ballot and log the receipt secret (A1)

**Type:** Defect — **constitutional (ballot secrecy, operational)** · **Severity:** High · **Epic:** `PBDIGIT-EPIC-05` Voting · **Created:** 2026-10-05
**Found by:** Phase 1 characterization · evidence report `docs/publicdigit/reviews/2026-10-05-phase1-characterization-evidence-report.md`
**Placement derived:** `doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0)

| | |
|---|---|
| **Status** | 🟡 **OPEN — decision pending (Phase 2.0 Anonymity ADR); unchanged until then** (PO, 2026-10-05, roadmap §2a). **Gates Phase 7:** IP binding may not add voter-side IP evidence while this log correlation exists (anonymity-boundary review). |
| **Defect** | During one cast the logs record (a) the vote id together with the client IP ("Vote and results saved successfully", `voting_audit`); (b) the vote id together with the voter slug id ("Step 5 recorded"); (c) the voter's receipt `private_key` ("private_key"); (d) the voter's user id in other entries of the same request. |
| **Evidence (FACT, synthetic data)** | `BallotAnonymityCharacterizationTest::test_k8_known_gap_…` (all four asserted). Sources: `VoteController.php:2816` ("Vote and results saved successfully", with `ip`), `:1776` ("Step 5 recorded", with `voter_slug_id` and `vote_id`), `:1755` (`Log::info('private_key', …)`); `codes.client_ip` holds the same IP per voter. |
| **Affected boundary** | logging / audit ↔ ballot |
| **Constitutional impact** | Log access (incl. log shipping and backups) yields voter → ballot correlation; a logged receipt secret allows opening the ballot via view-my-vote (PBDIGIT-228). |
| **Historical data** | Existing log files and archives. |
| **Proposed phase** | Phase 2.2 · **ADR required** (Phase 2.0 log separation: voter-side vs ballot-side events, never shared fields; retention of existing logs). |

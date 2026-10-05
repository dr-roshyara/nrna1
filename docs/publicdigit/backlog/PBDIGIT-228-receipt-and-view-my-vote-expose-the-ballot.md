# PBDIGIT-228 — Receipts and "view my vote" expose the ballot to any code holder (K2–K5)

**Type:** Defect — **constitutional (ballot secrecy, coercion)** · **Severity:** High · **Epic:** `PBDIGIT-EPIC-05` Voting · **Created:** 2026-10-05
**Found by:** Phase 1 characterization · evidence report `docs/publicdigit/reviews/2026-10-05-phase1-characterization-evidence-report.md`
**Placement derived:** `doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0)

| | |
|---|---|
| **Status** | 🟡 **OPEN — decision pending (Phase 2.0 Anonymity ADR: receipt / view-my-vote semantics, Model A vs B); unchanged until then** (PO, 2026-10-05, roadmap §2a). Feature **not** removed. |
| **Defect** | (K5) the e-mail sent to the voter carries `private_key_voteId` — the ballot id goes to the voter's mailbox and mail transport. (K3) `receipt_codes` stores that full secret in clear (no user column). (K2) `vote.submit_code_to_view_vote` returns the exact ballot selections to **any authenticated user** holding the code. (K4) the committee list/CSV (`VotingReceiptController::index/download`, `viewResults`, results published) shows every full secret, so a list holder can open every ballot. |
| **Evidence (FACT, synthetic data)** | `tests/Feature/Characterization/Phase1/ViewMyVoteReceiptCharacterizationTest` — k5, k3, k2 (×2), k4. Sources: `VoteController.php:1797-1822` (receipt, notification), `:3261-3330` (view), `:3427-3445` (receipt_hash check), `VotingReceiptController.php:20-60, 85-130`. |
| **Classification** | e-mail: **correlation bridge**; receipt row: participation-proof data held as a full secret; view-my-vote: **ballot retrieval**, not bound to the voter (coercion / vote-selling); committee list: **ballot retrieval for every ballot**. |
| **INFERENCE (not tested)** | with `MAIL_MAILER=log` the e-mail body (voter address + vote id) is written to the application log. |
| **Affected boundary** | receipt / verification feature ↔ ballot |
| **Proposed phase** | Phase 2 · **ADR required** (purpose of view-my-vote: A participation receipt · B ballot verification · C ballot retrieval). |

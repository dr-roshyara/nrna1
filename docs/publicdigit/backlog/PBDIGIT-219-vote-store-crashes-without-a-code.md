# PBDIGIT-219 — `VoteController::store` returns 500 when the voter has no Code

**Type:** Defect (robustness) · **Epic:** `PBDIGIT-EPIC-05` Voting · **Created:** 2026-10-04
**Raised by:** `claude-code-session:9e4925bb-4151-4185-9e9c-5831184e54eb` (self-declared, not attestable)
**Placement derived:** `doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0)
**Found by:** the PBDIGIT-213 C3 `can_vote` classification and bypass analysis (2026-10-04). See `developer_guide/election/voter_admission/03_c3_identity_admission.md` §Legacy classification.

| | |
|---|---|
| **Status** | 🟡 **OPEN.** Not authorised. Deliberately **not** fixed in PBDIGIT-213 (C3 boundary). |
| **Problem** | `VoteController.php:1652` calls `$code->fresh()` on a null Code → `Error` → HTTP 500. Observed in the PBDIGIT-213 C3 RED run when a (then not yet blocked) voter reached store without a Code. |
| **After PBDIGIT-213 C3** | An unadmitted voter is now refused (403) before reaching this code. An *entitled* voter without a Code can still hit it. |
| **Scope note** | `Code` / `Code.can_vote_now` are out of PBDIGIT-213 scope; this item owns the null guard. |

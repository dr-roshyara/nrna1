# PBDIGIT-216 — Emergency lockdown and slug enforcement call methods that do not exist

**Type:** Defect (broken feature) · **Epic:** `PBDIGIT-EPIC-05` Voting · **Created:** 2026-10-04
**Raised by:** `claude-code-session:9e4925bb-4151-4185-9e9c-5831184e54eb` (self-declared, not attestable)
**Placement derived:** `doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0)
**Found by:** the PBDIGIT-213 C3 `can_vote` classification and bypass analysis (2026-10-04). See `developer_guide/election/voter_admission/03_c3_identity_admission.md` §Legacy classification.

| | |
|---|---|
| **Status** | 🔴 **OPEN — feature non-functional.** Not authorised. |
| **Problem** | `admin.voting.security.lockdown` (`Admin/VotingSecurityController.php:122`) calls `VotingSecurityService::emergencyLockdown()` and :101 calls `enforceOneActiveSlugPerUser()` — neither exists. `getRecentLockdowns` reads a `step_meta.emergency_lockdown` value nothing writes. |
| **Scope note** | Emergency security lockdown is a **separate concept** from identity suspension (PBDIGIT-213 D5); not merged. |
| **Business consequence** | An operator expecting an emergency stop gets an error; nothing is locked. |

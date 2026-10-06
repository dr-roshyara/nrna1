# PBDIGIT-253 — Voter Management: filter by Voted (and keep filters on the page)

**Type:** Feature + defect · **Epic:** `PBDIGIT-EPIC-05` Voting · **Created:** 2026-10-06
**Raised by:** `claude-code-session:9e4925bb-4151-4185-9e9c-5831184e54eb` (self-declared, not attestable), at the PO's request: "there is a filter all statuses. can you also filter with voted" / "add another filteration with voted"
**Existing-story search (freeze rule):** the read-only list (`OrganisationController::voters`) already has `?voted=voted|not_voted` from `codes.has_voted`. No story owns a voted filter on Voter Management.

| | |
|---|---|
| **Status** | 🟢 **RESOLVED 2026-10-06** (GREEN, PO-approved; commit pending approval) |
| **Feature** | A **Voted** filter (All / Voted / Not voted) next to "All statuses" on Voter Management (`elections.voters.index`). `?voted=voted|not_voted` uses the **same parameter and semantics** as the read-only list, from `codes.has_voted` (the participation authority, PBDIGIT-252). It combines with the status, search and per-page filters, and `filters.voted` is echoed so the select is preselected |
| **Defect found [S]** | `Voters/Index.vue` `applyFilters()` navigates to `organisations.elections.voters` (the **read-only list**, `/voters`) instead of `elections.voters.index` (`/voters/manage`). Changing "All statuses" therefore leaves Voter Management. Fixed in this story, since the new filter depends on it |
| **Depends on** | PBDIGIT-252 (`codes.has_voted` authority, `votedUserIds`) |

**RED:**
- `tests/Feature/Election/VoterManagementVotedFilterTest.php` F1–F4: voted / not_voted, ignoring the dead column; echo; combines with status.
- `tests/Frontend/VoterManagementVotedFilter.spec.js` F-UI1–4: select options; reloads this page with `voted`; the status filter stays on this page; preselected.
- Frontend RED: all 4 fail. F-UI3 shows the navigation to the read-only list.

**Backend RED (run after the PBDIGIT-252 regression):** F1–F4 all fail: the filter is ignored and `filters.voted` is missing.

**GREEN:**
- `ElectionVoterController::index` reads `?voted` (allow-listed `voted` / `not_voted`) and applies `whereExists` / `whereNotExists` on a voted code (`votedCodeOfMembership`), before pagination. It echoes `filters.voted`.
- `Voters/Index.vue`:
  - the Voted select (All / Voted / Not voted) next to "All statuses";
  - `applyFilters()` now reloads `elections.voters.index` and sends `voted`;
  - Clear and the empty state include the voted filter.
- **Results:** F1–F4 and F-UI1–4 pass; the neighbouring Voter Management suites pass; frontend 315 passed / 3 skipped; design-check 91. The full regression is reported separately.

**Traceability:** PBDIGIT-252 · `OrganisationController::voters` (voted filter precedent) · ADR-T11 (participation state only).

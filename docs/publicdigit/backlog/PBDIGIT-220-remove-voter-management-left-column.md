# PBDIGIT-220 — Remove the outdated left column from Voter Management

**Type:** UI change (removal) · **Epic:** `PBDIGIT-EPIC-05` Voting · **Created:** 2026-10-04
**Raised by:** PO, during the PBDIGIT-213 C4 review of `/organisations/{org}/elections/{election}/voters/manage` (2026-10-04): *"remove the leftside column. it is outdated now"* · *"we need to remove this adding members to voters"*
**Recorded by:** `claude-code-session:9e4925bb-4151-4185-9e9c-5831184e54eb` (self-declared, not attestable)
**Placement derived:** `doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0)

| | |
|---|---|
| **Status** | 🟢 **IMPLEMENTED (2026-10-05)** — the PO asked for it during the C4 review; done TDD-first and committed **separately** from PBDIGIT-213 C4 (C4 = `9c482db85`). RED 5/5 → GREEN 5/5 (`tests/Frontend/VoterManagementLayout.spec.js`); design-check 92 → 91. Export CSV moved to the header actions; subtitle now "{election} — verify and manage election voters"; the column's script state and CSS removed; the empty-register text reworded from *"The register is empty — assign voters using the panel."* to **"The register is empty."** (PO-approved). Lost by PO choice: the stats block and the state/type badges. |
| **Page** | `resources/js/Pages/Elections/Voters/Index.vue` → `<aside class="voters-sidebar">` |
| **What the column holds today** | (1) back link + election name, state and type badges; (2) voter register stats (Active / Eligible / Suspended / Removed); (3) **Assign users as voters** — member search, bulk assign (`elections.voters.bulk`), assign by user ID (`elections.voters.store`); (4) **Export CSV** (`elections.voters.export`). |
| **Why** | PO: the column is outdated; adding organisation members as voters from this page is no longer wanted. |
| **Scope (UI only)** | Remove the column and its now-unused script state (`assignUserId`, `assigning`, `selectedMemberIds`, `memberSearch`, `filteredMembers`, `assignSingle`, `bulkAssign`) and styles. |
| **Must not be lost** | **Export CSV** — move to the main toolbar (the column is its only entry point). Election context (name/state) — confirm with PO whether the main area must show it. |
| **Out of scope** | Backend routes `elections.voters.store` / `elections.voters.bulk` and `ElectionVoterController@store/bulkStore` stay (removing endpoints is a separate decision); the `unassignedMembers` prop may become unused — removing it from the controller is a follow-up only if the PO wants it. PBDIGIT-213 admission semantics. |
| **Replacement** | Adding organisation members as election voters becomes its own story: `PBDIGIT-221`. |
| **Related** | `PBDIGIT-202` (Full Membership bulk voter import calls a non-existent `bulkAssignVoters()`) — a different defect in the same area; unaffected by a UI removal. |
| **Existing-story search** | `grep` of `docs/publicdigit/backlog/` for assign-as-voters / voters-manage / Voters/Index / bulk-assign: only PBDIGIT-213 (the investigation that found the page) and PBDIGIT-202 (different defect). No existing story covers this removal. |

## Acceptance (TDD — RED first)

1. A frontend spec mounting `Pages/Elections/Voters/Index.vue` proves: no `.voters-sidebar`; no "Assign users as voters" section; no member search, bulk-assign or assign-by-user-ID control.
2. Export CSV is still reachable from the page and links to `elections.voters.export`.
3. Existing PBDIGIT-213 C4 specs (`VoterIdentityEntryPoints`, `IdentityVerificationCell`, `BulkVerifyDialog`, `IdentityAttestationDialog`) stay green.
4. `npm run design-check` shows no new violations; layout remains usable at phone width.

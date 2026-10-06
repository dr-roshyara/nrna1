# PBDIGIT-252 — One participation authority: `codes.has_voted`, never `election_memberships.has_voted`

**Type:** Defect (authority inconsistency) · **Epic:** `PBDIGIT-EPIC-05` Voting · **Created:** 2026-10-06
**Raised by:** `claude-code-session:9e4925bb-4151-4185-9e9c-5831184e54eb` (self-declared, not attestable), from the PO's report "two persons have voted but the Voted column is not filled" (Voter Management)
**Existing-story search (freeze rule):** PBDIGIT-250 / the read-only list (`OrganisationController::voters`) already read `codes.has_voted`; no story owns the remaining consumers.

| | |
|---|---|
| **Status** | 🟢 **RESOLVED 2026-10-06** (GREEN; commit pending PO approval): all four consumers read `codes.has_voted`; the Voted column shows Yes / No |
| **Invariant (PO)** | **Participation completion is authoritative in `codes.has_voted`** (written in the vote's own transaction). No voting-related behaviour may use `election_memberships.has_voted` as an independent source of truth |
| **Finding [S]** | `election_memberships.has_voted` has **no production writer** (`ElectionMembership::markAsVoted()` has no caller; noted at `OrganisationController.php:746`) |
| **Consumers to migrate (all four, PO)** | **1** the Voter Management Voted column (`Voters/Index.vue` ← `ElectionVoterController::index` rows) · **2** Voter Management row-action visibility (`v-if="!membership.has_voted"`) · **3** the suspension proposal guard (`ElectionVoterController::proposeSuspension`, "Cannot suspend a voter who has already voted.") · **4** newsletter audiences `election_voted` / `election_not_voted` (`NewsletterService`) |
| **Not in scope** | Dropping the `election_memberships.has_voted` column (a separate decision, after its production usage is gone) |
| **Anonymity** | Participation state ("has voted"), never ballot content. No voter ↔ ballot link (ADR-T11) |

**RED:** `tests/Feature/Election/ParticipationAuthorityTest.php` P1–P4. The fixtures make the two sources disagree (voted in codes / flagged only in the dead column). All four fail today for the stated reasons.

**Added requirement (PO 2026-10-06, before GREEN):** the Voted column renders an unambiguous **Yes / No** derived from `codes.has_voted`; **No** is the fail-safe when no completed participation exists. RED: `tests/Frontend/VoterManagementVotedColumn.spec.js` V-UI1 (failed: "✓ Cast" / "—"), plus V-UI2 (a voter who has voted gets no row actions; a guard, passing).

**GREEN (PO-approved, 2026-10-06):**
- `ElectionVoterController`:
  - `votedUserIds()` (codes, election-scoped);
  - the rows' in-memory `has_voted` comes from it (never saved);
  - the `proposeSuspension` guard uses it.
- `NewsletterService`: `election_voted` / `election_not_voted` use a `codes` `EXISTS` / `NOT EXISTS` sub-query (`votedCodeFor`).
- `Voters/Index.vue`: the Voted cell shows Yes / No.

**Test impact (the actual result):**
- `ElectionVoterSuspensionTest::test_cannot_propose_suspension_for_voted_voter` built "voted" from the dead column. Its fixture now uses a voted code; the assertions are unchanged.
- `NewsletterAudienceSegmentationTest` was **already failing in full** at the earlier commit (26 failures identical before and after across the three suites compared). It is untouched; P4 covers the newsletter consumer.

**Results:**
- P1–P4, the suspension suite and V-UI1 / V-UI2 pass;
- frontend 311 passed / 3 skipped;
- design-check 91;
- the full regression is reported separately.

**Traceability:** `OrganisationController::voters` (codes authority) · PBDIGIT-250 · PBDIGIT-251 · ADR-T11.

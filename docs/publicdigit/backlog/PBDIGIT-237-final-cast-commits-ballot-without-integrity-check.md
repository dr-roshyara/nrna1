# PBDIGIT-237 — The final cast commits a ballot without checking it against the voter's ballot (VE-01)

**Type:** Defect (critical — ballot integrity) · **Epic:** `PBDIGIT-EPIC-05` Voting · **Created:** 2026-10-05
**Found by:** voting-engine verification gate T1 (`docs/publicdigit/reviews/2026-10-05-voting-engine-verification-gate-report.md`, §4–§5) after the complete voting-engine business audit (`2026-10-05-complete-voting-engine-business-audit.md`, VE-01)
**Raised by:** `claude-code-session:9e4925bb-4151-4185-9e9c-5831184e54eb` (self-declared, not attestable)
**Existing-story search (freeze rule):** `PBDIGIT-230`, `232`, `233`, `219` (Phase 4) concern authorization, fail-closed exits and participation consumption — not ballot content; `PBDIGIT-236` is the regional-authority decision (D6) that VE-01 consumes. No existing story covers ballot-content integrity at the final cast.

| | |
|---|---|
| **Status** | 🔴 **OPEN — RED contract written; GREEN not authorized.** Design approved as direction; **ADR revision 3 signed and applied 2026-10-05** (I1 amended, I11–I13; S2 = real elections only) — next gate: VE-01 GREEN authorization. |
| **Finding (FACT [D], synthetic data)** | `VoteController::store()` commits whatever ballot is in the voter's session. Selections from another election, another post, another region, duplicates, over the maximum, non-electable candidacies and malformed entries are committed (`Vote::createResultsFromCandidates()` takes `post_id` / `candidacy_id` from the stored payload), change the published tally, and malformed ballots consume participation with a void vote. Referential integrity ≠ ballot integrity. |
| **Decisions (PO, frozen)** | D1 only selections from the authoritative assembled ballot, each candidacy once · D2 `required_number` = maximum · D3 malformed → fail closed · D5 approved candidacies only (central, `BallotAssemblyService`) · N1–N5 abstention / not-answered rules |
| **Open** | D6 regional authority → `PBDIGIT-236` (independent) · D4 historical forensics — D4.0 approved, D4.1 execution not authorized |
| **Target (design)** | SubmittedBallot (untrusted) × AuthoritativeBallot (current ballot assembly authority) → pure `BallotValidator` → `ValidatedBallot` \| `BallotRejection`; authorization and ballot validation both before the CAS, from one CastContext; `save_vote(ValidatedBallot)`. ADR I1 amended, I11–I13 new |
| **Evidence** | `tests/Feature/Voting/BallotIntegrityCharacterizationTest.php` (T1) · `tests/Feature/Voting/FinalCastBallotInvariantTest.php` (RED contract: 4 GREEN, 16 RED) |
| **Out of scope** | A5 / A6, IP binding, receipt-hash, logging cleanup, code regeneration / validity window, demo architecture, `FinalVoteApplicationService`, `election_memberships.has_voted`, historical data repair |

**Traceability:** gate report §4–§18 · design proposal `docs/publicdigit/reviews/2026-10-05-ve01-ballot-integrity-design-and-adr-amendment-proposal.md` · D4.0 `docs/publicdigit/reviews/2026-10-05-d4-forensic-schema-verification-and-query-set.md` · ADR `docs/publicdigit/adr/ADR_20261005_0950_Voting_Authorization_Boundary.md` (I1) · guide `developer_guide/election/final_cast/05_ve01_ballot_integrity_red_and_design.md`.

# PBDIGIT-242 — A real-election ballot with nothing answered is accepted as a vote

**Type:** Decision needed (ballot semantics) · **Epic:** `PBDIGIT-EPIC-05` Voting · **Created:** 2026-10-05
**Found by:** VE-01 GREEN regression (PBDIGIT-237, `33bef9f7d`) — the Phase 1 B2 / Phase 4 R4-4 repair needed a well-formed empty ballot, which the validator accepts
**Raised by:** `claude-code-session:9e4925bb-4151-4185-9e9c-5831184e54eb` (self-declared, not attestable)
**Existing-story search (freeze rule):** `PBDIGIT-237` (VE-01) decided per-post semantics (N2 empty list = not answered, N3 omitted = not answered) and, PO 2026-10-05, *a missing regional list = empty*. It did not decide the whole-ballot case. No other story covers it.

| | |
|---|---|
| **Status** | 🟡 `OPEN` — decision needed; not authorised |
| **Fact [D] (synthetic data)** | A real-election final cast whose session ballot answers no post and abstains on none is validated as a `ValidatedBallot` with zero answers, consumes participation (`codes.has_voted = true`) and commits a vote with no result rows. Same outcome as before VE-01 whenever both transport lists were present |
| **Decision needed** | Is a fully empty ballot (a) a valid vote that only records participation, or (b) a refusal before the CAS (a new `BallotRejectionReason`)? |
| **If (b)** | RED in `FinalCastBallotInvariantTest` first; the Phase 1 B2 and Phase 4 R4-4 fixtures (`CastFixtures::wellFormedEmptyBallot()`) then need a post and an approved candidacy |
| **Out of scope** | per-post rules N1–N5 (frozen) · the UI's own "select all" constraints |

**Traceability:** guide `developer_guide/election/final_cast/07_ve01_ballot_integrity_green.md` §Still open · PBDIGIT-237.

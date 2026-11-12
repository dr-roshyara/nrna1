# IR-R1-02: independent review of the strategic baseline (through D-168) and two release-1 tactical designs

| | |
|---|---|
| **Commissioning authority** | The Principal (D-94), 2026-11-11 |
| **Status** | **COMMISSION, READY TO HAND OVER.** Supersedes IR-R1-01's package (its subjects were revised after a non-independent review; that report stays as history) |
| **Reviewer** | **Independent:** not Claude, **not DeepSeek**, **not run inside Claude Code**. A human architect, or another model family (e.g. the Principal DDD Architect in ChatGPT, Gemini, Mistral) in its own interface |
| **Why** | Everything in the package was written by Claude and checked only by Claude or by DeepSeek (which disclosed it was excluded). **No independent reviewer has examined the strategic baseline as a whole or the current revisions of the two designs** |
| **Not in scope** | Redesign; implementation; code. **A design review proves neither implementation correctness nor test execution** |

## 1. Files (SHA-256 at packaging; `MANIFEST.sha256` authoritative)

| # | File | Role |
|---|---|---|
| 01 | this commission | start here |
| 02 | `docs/publicdigit/reviews/2026-11-08-organisation-strategic-discovery-record-structure-mandates-conventions.md` | **decision record D-1 … D-168** (authoritative wording) |
| 03 | `docs/publicdigit/architecture/reviews/2026-11-10-strategic-baseline-consolidation-review.md` | whole-platform consolidation (parallel session), incl. H-1 §11.5 |
| 04 | `docs/publicdigit/architecture/reviews/2026-11-11-h1-silence-rules-and-strategic-baseline-completion.md` | **H-1 analysis, D-167 / D-168, D-166 controls, INV-ACCESS-1, baseline coherence through D-168** |
| 05 | `docs/publicdigit/architecture/reviews/2026-11-11-integrated-release-1-domain-design.md` | integrated release-1 flows (partly superseded, see its header) |
| 06 | `docs/publicdigit/architecture/reviews/2026-11-11-integrated-baseline-coherence-review-and-completion.md` | corrections to 05; Addendum M (multi-database) |
| 07 | **S1** `docs/publicdigit/reviews/2026-11-11-membership-kernel-renew-suspend-restore-design.md` | **revision 4; §16 and §17 govern** |
| 08 | **S2** `docs/publicdigit/architecture/reviews/2026-11-11-multi-database-boundary-tactical-design.md` | **§13 (revision 2) and §14 (revision 3) govern**, plus the closing D-165 … D-167 notes |
| 09 | `docs/publicdigit/reviews/2026-10-08-membership-architecture-ratified.md` | **RAT** Membership |
| 10 | `docs/publicdigit/reviews/2026-10-08-membership-architecture-final.md` | formal model, operation guards (§18) |
| 11 | `docs/publicdigit/reviews/2026-11-09-membership-change-request-cr-01-organisation-consumer-and-as-known-at-queries.md` | RAT contract |
| 12 | `docs/publicdigit/reviews/2026-11-10-membership-change-request-cr-02-standing-in-c1-c2.md` | RAT addition (D-161) |
| 13 | `docs/publicdigit/reviews/2026-11-10-membership-cr01-c1-c2-implementation-specification.md` | accepted (D-162) + lock-order note |
| 14 | `docs/publicdigit/architecture/reviews/2026-11-11-authoritycheck-organisation-governance-contract-review.md` | authority evidence (six fields + basis) |
| 15 | `docs/publicdigit/reviews/2026-11-10-governance-thin-slice-minimum-package.md` | accepted (D-157) + correction notes 1–4 |
| 16 | `docs/publicdigit/architecture/reviews/2026-11-09-i-tdd-01-identity-participant-access-tactical-design.md` | Identity `Claim` (P; **predates D-113**) |
| 17 | `docs/publicdigit/reviews/2026-11-09-identity-xd3-arb-act.md` | RAT identity rules |
| 18 | `docs/publicdigit/reviews/2026-11-08-strategic-context-map-and-governance-tactical-model.md` | Organisation §49–§51 (ACC D-156); Governance working model (§20.1 indivisible groups) |
| 19 | `docs/publicdigit/architecture/reviews/2026-11-11-ir-r1-01-independent-review.md` | the **non-independent** DeepSeek review (history; its findings were applied in 07 §17 and 08 §14) |

## 2. Part A: strategic baseline (decision record + files 03–06)

1. **Ownership:** does every business fact and decision have **exactly one** owning context, with **three kinds of ownership** kept apart (domain owner, owning organisation, physical store; 08 §2, D-164)? Name any double or missing owner.
2. **Authority:** after **D-167**, is there **any remaining place where PublicDigit assigns authority by default** (D-1a, D-1b)? Check D-53, D-64, D-145, D-115 as amended, and any ruling the H-1 analysis missed.
3. **Product defaults:** are the remaining defaults (D-52, D-55, D-56, D-114, D-140, D-152, D-153, D-161 / D-168) **authority-free, overridable and labelled**, as D-167 requires?
4. **Identity:** is **D-113** applied consistently (reference at import, Person at claim, claim tied to `PersonRef`, binding = routing only)? Is I-TDD-01's stale wording correctly treated as superseded?
5. **Multi-database:** is **D-164** reflected consistently? Is anything organisational stored centrally? Is the central binding ever used as authority?
6. **INV-ACCESS-1 (D-166):** is it a coherent cross-cutting rule? Are the §D controls in file 04 adequate? Is the **honest limit** (procedural, not cryptographic, in release 1) stated correctly?
7. **Release-1 scope:** are D-146, D-147, D-160 and D-163 consistent with the designs?
8. **Contradictions** between decisions, or **claims stronger than evidence.**

## 3. Part B: S1 Membership renew / suspend / restore (file 07, revision 4)

9. Does the decision table (§5, §16, §17) follow the ratified definitions (09 §2–§5) and **guards** (10 §18), including **in force** for Suspend / Restore and the row for a term lapsing during a suspension?
10. **Grouped renewals:**
    - does the **single lock order** (authority answer first, then ledger clock, then version compare-and-set, for all appends; 07 §17, 13 note) remove the deadlock?
    - do the **applied-group marker** and `RenewalGroupApplied` make retries safe?
11. Terminology: `IsMember`, lapsed membership, `InForceUnrestricted` (consumer-derived; **D-168: overridable default**).

## 4. Part C: S2 multi-database boundary (file 08, §13 and §14)

12. **Claim authority:** is the answer now **bound to its request** (`requestRef` = R, E, issuance id) so it cannot be replayed? Is anything still forgeable?
13. **Minimal central provenance:** with the full evidence in the organisation's database and a digest centrally, is D-164 respected?
14. **Recovery:** is every intermediate state harmless and recoverable (intent-first, atomic outbox, inbox deduplication, reconciliation, revoke-and-reissue)?
15. **Isolation:** are ISO-1 … ISO-10 sufficient, including connection handling, logs (ISO-10), privileged operations (ISO-8 / INV-ACCESS-1), and the registry locator?
16. **Availability:** is fail-closed stated precisely?

## 5. Report

Title: **"IR-R1-02 — Independent Review: Strategic Baseline and Release-1 Tactical Designs"**.
1. **Per question:** CONFIRMED · PARTIAL · MISSING · CONTRADICTORY · NOT ASSESSABLE, with **file, section and line** for each confirmation.
2. **Findings** (high, medium, low), each with:
   - evidence;
   - fact or inference;
   - impact;
   - **whether a Principal decision is needed**.
3. **Verdicts:**
   - **Part A:** baseline COHERENT · COHERENT AFTER CORRECTIONS · NOT COHERENT;
   - **S1 and S2:** READY FOR PRINCIPAL ACCEPTANCE · READY AFTER CORRECTIONS · NOT READY.
4. **Reviewer identity** (human, or model, version and interface), prior involvement with PublicDigit, integrity method.

**Rules:**
- Decide nothing for the Principal.
- Modify nothing.
- Claim no acceptance.
- Mark anything not in the package UNAVAILABLE.
- **Stop after the report.**

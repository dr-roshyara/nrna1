# c10-d5-establishment-criteria

**Scope(s):** OBJECT · **Row count:** 6 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `D5`, `E1..E10`, `L1/L2/L3` · **Aliases:** `establishment criteria proposal`
**Candidate group membership (NOT an identity claim):**
- **G0034** [`c10-d5-establishment-criteria` · `capability-c10-knowledge-distribution`] — explicit agent-stated uncertainty: 'c10-d5-establishment-criteria' POSSIBLY relates to 'capability-c10-knowledge-distribution' (batch B0004). Note: The adopted E1-E10 criteria and three-level assurance-establishment framework (L1 PROTOTYPE, L2 OPERATIONAL, L3 AUTHORITATIVE) for determining when C-10 may be considered established (S0127).
- **G0853** [`c10-d5-establishment-criteria` · `c10-establishment-criteria`] — labels share the notation 'E1..E10'
- **G0925** [`c10-d5-establishment-criteria` · `c10-establishment-criteria`] — working_label token overlap Jaccard=0.75 (shared tokens: ['c10', 'criteria', 'establishment'])
- **G1136** [`c10-d5-establishment-criteria` · `c10-e2-receipt-completeness-model`] — labels co-occur in the same contribution's labels[] 2 separate times across the corpus

## Sources (how this label entered the ledger)
- **PROPOSAL** batch `B0004`, scope `OBJECT`: The adopted E1-E10 criteria and three-level assurance-establishment framework (L1 PROTOTYPE, L2 OPERATIONAL, L3 AUTHORITATIVE) for determining when C-10 may be considered established (S0127).

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0124 §"the same process shaped both sides of the E2 ↔ D5 interface."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S0127 §"L1 · PROTOTYPE ESTABLISHMENT ... L2 · OPERATIONAL ESTABLISHMENT ... L3 · AUTHORITATIVE ESTABLISHMENT"]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S0124 §"the same process shaped both sides of the E2 ↔ D5 interface."]

## Lifecycle
last_seen: S0127. Candidate lifecycle: **DORMANT**. Evidence: no retraction/supersession/contradiction evidence recorded; the DORMANT classification is a heuristic based on how recently (by source_id ordering) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | PRESENT | S0127 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0127 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | PRESENT | S0124 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0126, S0127, S0127 |
| examples | PRESENT | S0127 |
| warnings | PRESENT | S0124 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
Argues that The full-conjunction Option A for establishment criteria is rejected as structurally unsatisfiable, because it requires a criterion (E10, distinct reason to change / history) that by definition cannot exist at the moment of establishment. [S0127]

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S0124] types=['GOVERNANCE', 'WARNING'] scope=CROSS-OBJECT — "INFO-2: the producing process of E2 also authored the D5 establishment-criteria proposal and the canonical C-10 analysis, meaning the same process shaped both sides of the E2↔D5 interface; recorded as non-blocking but as a residual for future acts to weigh." (anchor: "the same process shaped both sides of the E2 ↔ D5 interface.")
- [S0126] types=['PRINCIPLE', 'DISTINCTION'] scope=CROSS-OBJECT — "Adopting a completeness/design model for a capability is explicitly distinguished from establishing that the capability exists; such adoption is Class C (future/architectural) evidence under the D5 scheme and cannot by itself establish existence." (anchor: "Adopting a completeness model is not evidence of capability existence — under D5's own scheme a definition is Class C (future/architectural) evidence, which cannot establish existence by itself.")
- [S0127] types=['FORMALIZATION'] scope=OBJECT — "A three-level assurance framework for capability establishment: L1 (one realized instance, a named milestone that does NOT flip existence), L2 (≥2 independent governed acts showing the outcome is owned rather than incidental — the level at which D2/existence flips), L3 (L2 plus exercised claim-authority plus a change history — the level at which the capability may carry authoritative claims)." (anchor: "L1 · PROTOTYPE ESTABLISHMENT ... L2 · OPERATIONAL ESTABLISHMENT ... L3 · AUTHORITATIVE ESTABLISHMENT")
- [S0127] types=['ARGUMENT', 'COUNTEREXAMPLE'] scope=OBJECT — "The full-conjunction Option A for establishment criteria is rejected as structurally unsatisfiable, because it requires a criterion (E10, distinct reason to change / history) that by definition cannot exist at the moment of establishment." (anchor: "Option A is rejected — and on evidence, not preference: the full conjunction includes E10, which requires a history that cannot exist at establishment time. Adopting it would make C-10 permanently unestablishable.")
- [S0127] types=['PRINCIPLE', 'CORRECTION'] scope=METHODOLOGICAL — "An appended PO/ARB act resolves a divergence between a proposal's decided §4.2 text and the adoption act's own restated summary of L3, ruling the proposal's text governs; states the general principle that a summary must never silently replace the governing artifact, and closes the divergence in the same shape as the earlier Flag-O resolution." (anchor: "A summary must never silently replace the governing artifact.")
- [S0127] types=['DISTINCTION'] scope=THEORY-LEVEL — "Three distinct concepts are kept apart in the settled L1/L2/L3 model: capability existence, capability authority, and operational maturity; had L3 been read as maturity-only, the exercised-authority condition (E3) would have fallen out of the model entirely." (anchor: "Capability existence ≠ Capability authority ≠ Operational maturity")

## Notes for P3
- Connected to 4 candidate groups in P2a — worth checking for redundant/overlapping objects in P3.

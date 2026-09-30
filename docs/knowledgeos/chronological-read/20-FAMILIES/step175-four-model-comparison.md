# step175-four-model-comparison

**Scope(s):** THEORY-LEVEL · **Row count:** 1 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Model A state-only: FAIL`, `Model B versioned: partial (Versioning != Provenance)`, `Model C event history: passes if events carry semantic information`, `Model D snapshot+lineage: passes` · **Aliases:** `four candidate historical-reconstruction models`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0033, scope THEORY-LEVEL): Step 175 tests four candidate architectural models against consequential historical reconstruction: Model A (current state only) fails outright (overwriting Knowledge.status from Established to Superseded loses 'established based on what?'); Model B (versioned state, K1/K2/K3) is only partial because a version number alone does not reveal WHY a transition happened -- Versioning != Provenance, since the cause could be new evidence, a rule change, or a human correction; Model C (event history, state_t = fold of events) passes decision-reconstruction only if the events themselves carry sufficient semantic information (marked with an asterisk caveat), explicitly rejecting the conclusion 'therefore everything must be event-sourced' -- event sourcing is one implementation strategy among several (immutable versions, snapshots, append-only provenance, decision records, audit trails, temporal databases), chosen per HistoricalReconstructionRequirement; Model D (snapshot + lineage, e.g. K1--revisedBecause-->E2-->K2, with Decision1->K1) passes fully, preserving both historical state and causal/provenance relationships. Produces a comparison table (Current-state/History/Causality/Decision-reconstruction columns) and the conclusion 'the conclusion is not: event sourcing wins. The conclusion is: semantic temporal provenance is required where historical reconstruction matters.'

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1368] §"Model A ... FAIL for consequential historical reconstruction. ... Versioning ≠ Provenance. ... We should resist the temptation to conclude: Therefore everything must be event-sourced. ... Model D — Snapshot + lineage. This may be particularly suitable for KnowledgeOS. ... The conclusion is not: Event sourcing wins. The conclusion is: Semantic temporal provenance is required where historical reconstruction matters."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1368. Candidate lifecycle: DORMANT.
Evidence: none recorded (no retraction/supersession/contradiction signal) — this lifecycle label is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source ids |
|---|---|---|
| Purpose / rationale | PRESENT | S1368 |
| Informal meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| Formal definition | NOT-EVIDENCED-IN-CAPTURE | — |
| Type signature | NOT-EVIDENCED-IN-CAPTURE | — |
| Invariants | PRESENT | S1368 |
| Dependencies | PRESENT | S1368 |
| Assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| Semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| Examples | NOT-EVIDENCED-IN-CAPTURE | — |
| Warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| Experiments | PRESENT | S1368 |
| Open questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
Compares four candidate models against historical-reconstruction requirements: Model A (current state only) FAILS (overwriting Knowledge.status loses the basis for the prior status); Model B (versioned state) is only PARTIAL because a version number does not reveal why a transition occurred -- Versioning != Provenance; Model C (event history, state as fold of events) passes decision-reconstruction only if events carry sufficient semantic information, explicitly not concluding 'everything must be event-sourced' (event sourcing is one of several viable implementation strategies -- immutable versions, snapshots, append-only provenance, decision records, audit trails, temporal databases -- chosen per domain HistoricalReconstructionRequirement); Model D (snapshot+lineage, judged particularly suitable for KnowledgeOS) passes fully. Overall conclusion: not 'event sourcing wins' but 'semantic temporal provenance is required where historical reconstruction matters.' [S1368].

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1368] types=[EXPERIMENTAL-RESULT, ANALYSIS] scope=THEORY-LEVEL — "Compares four candidate models against historical-reconstruction requirements: Model A (current state only) FAILS (overwriting Knowledge.status loses the basis for the prior status); Model B (versioned state) is only PARTIAL because a version number does not reveal why a transition occurred -- Versioning != Provenance; Model C (event history, state as fold of events) passes decision-reconstruction only if events carry sufficient semantic information, explicitly not concluding 'everything must be event-sourced' (event sourcing is one of several viable implementation strategies -- immutable versions, snapshots, append-only provenance, decision records, audit trails, temporal databases -- chosen per domain HistoricalReconstructionRequirement); Model D (snapshot+lineage, judged particularly suitable for KnowledgeOS) passes fully. Overall conclusion: not 'event sourcing wins' but 'semantic temporal provenance is required where historical reconstruction matters.'" (anchor: "Model A ... FAIL for consequential historical reconstruction. ... Versioning ≠ Provenance. ... We should resist the temptation to conclude: Therefore everything must be event-sourced. ... Model D — Snapshot + lineage. This may be particularly suitable for KnowledgeOS. ... The conclusion is not: Event sourcing wins. The conclusion is: Semantic temporal provenance is required where historical reconstruction matters.")

## Notes for P3
None — this label's evidence is internally consistent within the rows captured for this batch.

# 11 — Theory → Architecture Traceability Plan (stage V15)

**Trace format:** `Theory Concept → DDD Concept → Architectural Component → Implementation Mechanism`, classified: DIRECTLY DERIVED / CONSISTENT-BUT-INDEPENDENTLY-DESIGNED / APPROXIMATED / UNSUPPORTED / CONTRADICTS THEORY / THEORY-DOES-NOT-DETERMINE. Architecture is never evidence for theory (standing rule; L4 is empty anyway — CF-015).

## Seed rows (from A8, to be completed after V14)

| Theory concept (verified status) | Expected architecture trace | Current classification |
|---|---|---|
| Ladder + A6 (PROVEN) | admission axis + governed acts in the FA layered model; session-bootstrap boundary check (nearest running relative per GN-46) | DIRECTLY DERIVED candidate — strongest row |
| Pre-aggregation normalization necessity (PROVEN, T-K6a) | a mandatory dedup/dependency stage in any evidence service | DIRECTLY DERIVED (the theorem *forces* the stage) |
| Underdetermined/NOT_IDENTIFIABLE outcomes (PROVEN, P-02) | first-class outcome values in assurance interfaces | currently **THEORY WITHOUT ARCHITECTURAL CARRIER** (positive-direction gap) |
| Structured-not-scalar assessments (PROVEN, qualified T-K7) | (S⁺,S⁻,Conflict,…) surfaces; scalar projections labeled | DERIVED at doctrine level; component mapping open |
| Replay classes (CONDITIONALLY PROVEN) | event store + recorded-oracle artifacts + version refs | CONSISTENT-BUT-INDEPENDENTLY-DESIGNED (event sourcing = choice; recording rule = governance) |
| Zero operator (CONDITIONALLY PROVEN) | gap-evaluation service over contract + evaluator plugins | DERIVED-modulo-η (contract input unconstructed) |
| Escalation law (sound) | human-governance escalation path | DIRECTLY DERIVED candidate |
| Federation of state spaces (corpus-argued, undisposed vs product) | bounded-context state ownership | THEORY-DOES-NOT-DETERMINE until C-027 disposition |
| Kernel(s) | — | must carry the 5-sense disambiguation; any "kernel service" naming = IMPLEMENTATION CHOICE |
| Invariant enforcement | per-KI enforcement locus (from DDD-6) | open until plan 08/10 |

## Verification procedure (when V14 done)
For each ratified architectural mechanism (RA v1.0 components; L1–L5 layers; operating-model machinery): identify the theory premise → DDD premise → consequence; absent a derivation, classify honestly (most expected: CONSISTENT-BUT-INDEPENDENTLY-DESIGNED — that is not a defect, it is a classification). Any CONTRADICTS-THEORY row becomes a TV-F finding, never an edit.

**PASS criterion for V15:** every major mechanism has exactly one classification with its trace; the UNSUPPORTED and CONTRADICTS sets are explicitly enumerated (possibly empty); the positive-direction gaps (theory without carrier) listed for governance.

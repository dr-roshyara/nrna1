# knowledgeos-theory-v1-2-simulation-experiment

**Scope(s):** METHODOLOGICAL · **Row count:** 5 · **Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** KR-SIM-2026-09-02-B, Model KnowledgeOS-Simulation-v1.2 · **Aliases:** none recorded

**Candidate group membership (NOT an identity claim):**
- **G1035** [`knowledgeos-theory-v1-1-simulation-experiment-protocol` · `knowledgeos-theory-v1-2-simulation-experiment`] — working_label token overlap Jaccard=0.83 (shared tokens: ['experiment', 'knowledgeos', 'simulation', 'theory', 'v1'])
- **G1087** [`knowledgeos-theory-v1-1-simulation-executed-results` · `knowledgeos-theory-v1-2-simulation-experiment`] — working_label token overlap Jaccard=0.57 (shared tokens: ['knowledgeos', 'simulation', 'theory', 'v1'])

## Sources (how this label entered the ledger)

- **OBJECT-INDEX**, batch B0058, scope METHODOLOGICAL: The v1.2 simulation lane (artifacts A-E plus FINAL-VERDICT), reusing the v1.1 pipeline unchanged and replacing only the evaluation layer with class-indexed three-valued Sat:K x R -> {T,F,U} over eight requirement classes, so that any v1.1/v1.2 difference is attributable to the theory rather than the simulator. Five experiments (E1-E5) test whether the weakened v1.2 theory repairs, worsens, or merely re-diagnoses the v1.1 failures.

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S2398 §"Every corroboration-dependent conclusion in this programme rests on a component of a concept v1.2 declares OPEN. ... Dropping the source component collapses corroboration and flips every downstream result."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: [S2400 §"E1 | Do the three Zero readings ever disagree? | Yes -- on the fully determined case. ... E5 | What kind of thing is Zero, of the seven v1.2 candidates? | 2 refuted, 1 partial, 4 surviving"]
- CANDIDATE-GOVERNANCE-BIRTH: [S2402 §"NOT TESTED -- and under v1.2, not testable. ... A kernel cannot be selected while its operation semantics are open, and E1 shows even the closure predicate itself is ambiguous. This is v1.2 behaving correctly, not a shortfall of the experiment."]

## Lifecycle

last_seen: S2402. Candidate lifecycle: ACTIVE.
Evidence: none recorded (no retraction/supersession/contradiction rows found). This ACTIVE classification is a heuristic based on how recently (by source_id, last_seen=S2402) this label was last used in the captured contribution set, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S2400, S2401 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S2398 |
| experiments | PRESENT | S2398, S2400 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale

Describes the controlled-experiment design: the v1.1 pipeline (world, state, transitions) is reused byte-identical, so any difference between v1.1 and v1.2 results is attributable to the theory change, not the simulator [S2400]. Delivers the comparison verdict: v1.2 repaired no v1.1 failure but improved diagnosis of two, exposed a hidden defect (Zero's ambiguity) v1.1's phrasing had concealed, and made 'unknown' a first-class result [S2401].

## Assumption register

NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)

- `[S2398]` types=[EXPERIMENTAL-RESULT, WARNING] scope=OBJECT — "Details result 5 (E4): dropping the observation model's source component collapses corroboration and flips determination/attribution/Zero, showing corroboration-dependent conclusions rest on a component the theory itself calls OPEN." (anchor: "Every corroboration-dependent conclusion in this programme rests on a component of a concept v1.2 declares OPEN. ... Dropping the source component collapses corroboration and flips every downstream result.")
- `[S2400]` types=[EXPLANATION] scope=METHODOLOGICAL — "Describes the controlled-experiment design: the v1.1 pipeline (world, state, transitions) is reused byte-identical, so any difference between v1.1 and v1.2 results is attributable to the theory change, not the simulator." (anchor: "The v1.2 run reuses the entire v1.1 pipeline unchanged, and replaces only the evaluation layer ... any difference between the v1.1 and v1.2 results is attributable to the theory, not to the simulator.")
- `[S2400]` types=[EXPERIMENT] scope=OBJECT — "Tabulates the five formal experiments (E1 Zero-reading disagreement, E2 equality substitutability, E3 CE-1/CE-3 persistence, E4 OPEN-observation dependence, E5 which of seven Zero readings survive) with their one-line results." (anchor: "E1 | Do the three Zero readings ever disagree? | Yes -- on the fully determined case. ... E5 | What kind of thing is Zero, of the seven v1.2 candidates? | 2 refuted, 1 partial, 4 surviving")
- `[S2401]` types=[ANALYSIS] scope=THEORY-LEVEL — "Delivers the comparison verdict: v1.2 repaired no v1.1 failure but improved diagnosis of two, exposed a hidden defect (Zero's ambiguity) v1.1's phrasing had concealed, and made 'unknown' a first-class result." (anchor: "The weakening was productive. v1.2 did not repair a single v1.1 failure -- but it made two of them better diagnosed, exposed one defect v1.1's phrasing had hidden (Zero's ambiguity), and gave the programme a vocabulary in which "we do not know" is a first-class result rather than a hedge.")
- `[S2402]` types=[GOVERNANCE] scope=THEORY-LEVEL — "Delivers a stronger kernel-status verdict than v1.1: not merely untested but not-yet-testable under v1.2's own declared openness of operation semantics and closure predicate." (anchor: "NOT TESTED -- and under v1.2, not testable. ... A kernel cannot be selected while its operation semantics are open, and E1 shows even the closure predicate itself is ambiguous. This is v1.2 behaving correctly, not a shortfall of the experiment.")

## Notes for P3

No internal tension or unusual evidentiary pattern noticed while assembling this file; the rows are mutually consistent at the level this pass can check.

# observed-unobserved-unknown-state-model

**Scope(s):** OBJECT · **Row count:** 2 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** Observed/Unobserved/Unknown · **Aliases:** absence as evidence
**Candidate group membership (NOT an identity claim):**
- **G0075** [`observed-unobserved-unknown-state-model` · `zero-neutral-state-lens`] — explicit agent-stated uncertainty: 'observed-unobserved-unknown-state-model' POSSIBLY relates to 'zero-neutral-state-lens' (batch B0012). Note: Proposed epistemic state model for any proposition -- Observed (supporting/contradicting/neutral), Unobserved (expected? absent? impossible?), Unknown (why unknown? not measured? inaccessible?) -- explicitly finer than a binary true/false, and explicitly states 'not observed' does not mean 'does not exist.'
- **G0082** [`missing-vs-negative-evidence-invariant` · `observed-unobserved-unknown-state-model`] — explicit agent-stated uncertainty: 'missing-vs-negative-evidence-invariant' POSSIBLY relates to 'observed-unobserved-unknown-state-model' (batch B0012). Note: HSMM missing-observation handling motivates the explicit invariant that O_t=empty must not imply P(H|O_t=empty)=0; called 'one of the most important consequences of the HSMM perspective', illustrated by runtime/repository/documentation/test/log unavailability that should not be read as negative evidence.

## Sources (how this label entered the ledger)
- **PROPOSAL** (batch B0012, scope OBJECT): Proposed epistemic state model for any proposition -- Observed (supporting/contradicting/neutral), Unobserved (expected? absent? impossible?), Unknown (why unknown? not measured? inaccessible?) -- explicitly finer than a binary true/false, and explicitly states 'not observed' does not mean 'does not exist.' [relation_to_existing: POSSIBLY:zero-neutral-state-lens]

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0452 §"What would we expect to see if the hypothesis were true, but do not see? ... Observed / Not observed / Expected but absent / Unexpectedly present / Unknown / Not measurable / Not collected ... Not observed does not mean Does not exist."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S0452 §"What would we expect to see if the hypothesis were true, but do not see? ... Observed / Not observed / Expected but absent / Unexpectedly present / Unknown / Not measurable / Not collected ... Not observed does not mean Does not exist."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0453. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction evidence recorded. This is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0452, S0453 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S0452 |
| dependencies | PRESENT | S0452 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- **[S0452]** types=[FORMALIZATION, INVARIANT] scope=OBJECT — "Absence becomes evidence: proposes recording Observed/Not observed/Expected-but-absent/Unexpectedly-present/Unknown/Not-measurable/Not-collected as non-equivalent states, with the invariant that 'not observed' does not mean 'does not exist'; further refined into an Observed/Unobserved/Unknown state model per proposition, explicitly contrasted with a true/false binary." (anchor: "What would we expect to see if the hypothesis were true, but do not see? ... Observed / Not observed / Expected but absent / Unexpectedly present / Unknown / Not measurable / Not collected ... Not observed does not mean Does not exist.")
- **[S0453]** types=[FORMALIZATION] scope=THEORY-LEVEL — "Proposes a five-state epistemic vocabulary (UNKNOWN/UNCERTAIN/SUPPORTED/CONTRADICTED/ESTABLISHED) with the caveat that even ESTABLISHED (a governance-permitted stronger status) must never imply metaphysical certainty." (anchor: "UNKNOWN / UNCERTAIN / SUPPORTED / CONTRADICTED / ESTABLISHED ... even 'established' should not mean metaphysical certainty. ... the book explicitly advocates remaining comfortable with uncertainty and not claiming absolute final answers.")

## Notes for P3
- Own observation: only 2 row(s) touch this label — a thin evidentiary base; treat any generalization from it with caution.
- Own observation: completeness is thin — only formal_definition, invariants, dependencies is PRESENT; most dimensions are NOT-EVIDENCED-IN-CAPTURE, consistent with a thin or narrowly-scoped source base rather than a claim that the object lacks these properties.
- Own observation: no rationale-bearing (EXPLANATION/ARGUMENT/ANALYSIS/ALTERNATIVE) row was found for this label — its purpose/motivation, if any, is carried only in DEFINITION/FORMALIZATION-typed rows.

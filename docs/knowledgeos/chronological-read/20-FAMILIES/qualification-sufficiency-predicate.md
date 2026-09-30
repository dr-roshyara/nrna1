# qualification-sufficiency-predicate

**Scope(s):** OBJECT · **Row count:** 3 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `SUFFICIENT: Evidence x Context x Constitution -> {True,False,Unknown}`, `Sufficient(e,ctx)` · **Aliases:** `G2 qualification predicate`, `TG-02`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0041, scope OBJECT): A DeepSeek-sourced (non-incorporated) principle that sufficiency is constitution-dependent; shown to fail for constitutions requiring relational sufficiency (independence/corroboration) because Evidence declares no independence relation.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1679] §"SUFFICIENT : Evidence x Context x Constitution -> {True, False, Unknown} is ABSENT from the corpus, and the argument that its absence is harmless is FALSIFIED by the corroboration case. Recorded as a remaining specification gap: TG-02."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1692. Candidate lifecycle: DORMANT.
Evidence: none recorded (no retraction/supersession/self-contradiction flagged in this label's rows). This DORMANT classification is a heuristic based on how recently (by source_id) this label was last used in the corpus, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S1679, S1690 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | PRESENT | S1679, S1692 |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | PRESENT | S1679, S1690, S1692 |
| assumptions | PRESENT | S1679 |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
G2's parametricity defence (sufficiency is a constitution-supplied parameter, hence not undefined) holds only for constitutions whose sufficiency is a per-item fold, but fails for constitutions requiring relational sufficiency (e.g. independence/corroboration across evidence items) because Evidence declares no independence relation and Policy has no accessor to cross-item structure; the source for the general principle is a non-incorporated DeepSeek answer, so the correct classification is SOURCE ESTABLISHES for the principle / PROPOSED for the interface, not CORPUS ESTABLISHES [S1679]. A ten-property audit (existence, well-formedness, identity, equality, transformation, composition, closure, determinism, observability, falsifiability) over Assertion, Evidence, K, Policy, Authority, AuthorityAct, Sigma, Gamma, Event finds Evidence has no identity of its own (only a pointer-typed ref field), so two distinct qualifications of the same observation under different methods are indistinguishable in the theory, which is shown to block the independence relation needed by G2's corroboration counterexample; Authority and AuthorityAct are found not to exist as objects at all (only string fields); Event has no schema; serialization is undefined for Evidence/Event and concurrency is undefined everywhere in the canonical theory (the corpus's step-059 concurrency calculus is not carried forward) [S1690].

## Assumption register

| Statement | Stated | Source | Anchor |
|---|---|---|---|
| a symbol bound at application time is not an undefined symbol | EXPLICIT | S1679 | "the audit did not test" |

## All rows (source_id order)
- [S1679] types=[ARGUMENT, CORRECTION, LIMITATION] scope=OBJECT — "G2's parametricity defence (sufficiency is a constitution-supplied parameter, hence not undefined) holds only for constitutions whose sufficiency is a per-item fold, but fails for constitutions requiring relational sufficiency (e.g. independence/corroboration across evidence items) because Evidence declares no independence relation and Policy has no accessor to cross-item structure; the source for the general principle is a non-incorporated DeepSeek answer, so the correct classification is SOURCE ESTABLISHES for the principle / PROPOSED for the interface, not CORPUS ESTABLISHES." (anchor: "SUFFICIENT : Evidence x Context x Constitution -> {True, False, Unknown} is ABSENT from the corpus, and the argument that its absence is harmless is FALSIFIED by the corroboration case. Recorded as a remaining specification gap: TG-02.")
- [S1690] types=[ARGUMENT, LIMITATION] scope=OBJECT — "A ten-property audit (existence, well-formedness, identity, equality, transformation, composition, closure, determinism, observability, falsifiability) over Assertion, Evidence, K, Policy, Authority, AuthorityAct, Sigma, Gamma, Event finds Evidence has no identity of its own (only a pointer-typed ref field), so two distinct qualifications of the same observation under different methods are indistinguishable in the theory, which is shown to block the independence relation needed by G2's corroboration counterexample; Authority and AuthorityAct are found not to exist as objects at all (only string fields); Event has no schema; serialization is undefined for Evidence/Event and concurrency is undefined everywhere in the canonical theory (the corpus's step-059 concurrency calculus is not carried forward)." (anchor: "Evidence has no identity -- the amended 9-field record carries ref, but ref is a pointer to the referent, not an identity of the evidence item. Two qualifications of the same observation under different methods are indistinguishable. This blocks the independence relation that G2's corroboration counterexample requires.")
- [S1692] types=[EXTENSION] scope=OBJECT — "Proposes (VERIFIER RECOMMENDS, TG-02) a typed three-valued Sufficient interface plus a required Independent relation on evidence items, matching the existing three-valued gate algebra (Unknown -> ResolutionBehavior); explicitly frames this as typing the socket only, leaving constitution-specific content (civil vs criminal sufficiency rules) unconstrained." (anchor: "Sufficient : Evidence x Context x Constitution -> {True, False, Unknown} / Independent subset-of Ev x Ev -- required for corroboration-style constitutions. Three-valued because the corpus's gate algebra is already three-valued ... Content stays constitutional -- only the socket is typed.")

## Notes for P3
Lifecycle is DORMANT on recency heuristics only — no explicit retraction/supersession was found in this label's own rows.

# capability-existence-vs-category-distinction

**Scope(s):** METHODOLOGICAL · **Row count:** 4 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** existence vs category · **Aliases:** AMD2 refinement 1
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0003, scope METHODOLOGICAL): The methodological refinement (AMD2) requiring existence (YES/NO/CONTESTED/NOT YET ESTABLISHED) and architectural category to be answered as two separate, non-derivable questions.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0096 §"Separate capability EXISTENCE from capability CATEGORY. Two distinct questions ... Does the capability exist? YES, NO, CONTESTED, NOT YET ESTABLISHED ... Do not derive one answer from the other."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S0116 §"C - FUTURE / ARCHITECTURAL ... an adopted invariant that has never been exercised ... MUST NOT establish existence by themselves. ... D1 is Class C -- an adopted semantic never exercised."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0168. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction evidence recorded. This is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0116, S0168 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S0096, S0116 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0096 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- **[S0096]** types=[DISTINCTION, PRINCIPLE] scope=METHODOLOGICAL — "A capability's EXISTENCE (admissible answers: YES, NO, CONTESTED, NOT YET ESTABLISHED) and its architectural CATEGORY (bounded context, cross-context capability, stewardship, control-plane function, or another justified category) are two orthogonal questions; a category verdict must never substitute for or be derived from an existence verdict, or vice versa." (anchor: "Separate capability EXISTENCE from capability CATEGORY. Two distinct questions ... Does the capability exist? YES, NO, CONTESTED, NOT YET ESTABLISHED ... Do not derive one answer from the other.")
- **[S0096]** types=[CORRECTION, PRINCIPLE] scope=OBJECT — "Refinement applied to C-5: absence of a C-5-owned authoritative aggregate/state disqualifies it from being its own bounded context but must not be read as evidence against the capability's existence -- an existence/category conflation the original analysis had committed for C-14 most clearly." (anchor: "Absence of a C-5-owned aggregate may disqualify a separate bounded context but must NOT be used as proof that the capability itself does not exist.")
- **[S0116]** types=[FORMALIZATION, CORRECTION] scope=OBJECT — "A third evidence class ('Class C: future/architectural') is formalised alongside realization and boundary evidence: proposed schemas, planned stores, future enforcement, and an adopted-but-never-exercised invariant must never by themselves establish capability existence -- and this class formally re-classifies D1 (a decided but never-performed receipt semantic) as contributing zero to C-10's establishment, correcting the earlier D2-prep's treatment of D1 as existence evidence." (anchor: "C - FUTURE / ARCHITECTURAL ... an adopted invariant that has never been exercised ... MUST NOT establish existence by themselves. ... D1 is Class C -- an adopted semantic never exercised.")
- **[S0168]** types=[DEFINITION, VALIDATION] scope=THEORY-LEVEL — "Cites Open Agile Architecture's capability/subdomain/bounded-context distinction as external validation for the platform's capability-first sequence, while cautioning that a capability does not automatically have one owner — some capabilities are inherently distributed (stewardship model, not simple ownership)." (anchor: "business capabilities belong to the problem space; subdomains delimit domain applicability; bounded contexts delimit the applicability of domain models in the solution space.")

## Notes for P3
- Own observation: completeness is thin — only formal_definition, invariants, semantics is PRESENT; most dimensions are NOT-EVIDENCED-IN-CAPTURE, consistent with a thin or narrowly-scoped source base rather than a claim that the object lacks these properties.
- Own observation: ungrouped in P2a — no co-occurrence or notation signal tied it to another label; may be a genuinely isolated object, or simply under-linked by the mechanical pass.
- Own observation: no rationale-bearing (EXPLANATION/ARGUMENT/ANALYSIS/ALTERNATIVE) row was found for this label — its purpose/motivation, if any, is carried only in DEFINITION/FORMALIZATION-typed rows.

# distinction-preservation-formalism

**Scope(s):** OBJECT · **Row count:** 3 · **Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** K preserves d iff forall s1,s2: s1 not-sim_d s2 => E(s1)!=E(s2), pi is R_req-adequate iff forall d in R_req: preserved through pi∘E · **Aliases:** distinction preservation / collapse / adequacy

**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)

- **OBJECT-INDEX**, batch B0061, scope OBJECT: Formal criteria: an encoding K collapses a distinction d if two d-inequivalent states map to the same encoding; a transformation/projection pi is R_req-adequate if it preserves every distinction in R_req through composition with the encoding E.

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S2551 §"A semantic distinction d is an equivalence relation \sim_d on S ... K preserves a distinction d ... iff forall s1,s2: (s1 not-sim_d s2) implies (E(s1) != E(s2)). If E(s1)=E(s2) when s1 not-sim_d s2, the language K collapses the distinction d. ... pi is R_req-adequate if forall d in R_req, forall s1,s2: (s1 not-sim_d s2) implies (pi(E(s1)) != pi(E(s2)))"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S2551 §"A semantic distinction d is an equivalence relation \sim_d on S ... K preserves a distinction d ... iff forall s1,s2: (s1 not-sim_d s2) implies (E(s1) != E(s2)). If E(s1)=E(s2) when s1 not-sim_d s2, the language K collapses the distinction d. ... pi is R_req-adequate if forall d in R_req, forall s1,s2: (s1 not-sim_d s2) implies (pi(E(s1)) != pi(E(s2)))"]
- CANDIDATE-OPERATIONAL-BIRTH: [S2558 §"1. Identify the set of values V_d 2. For each pair v1,v2 in V_d, v1!=v2: construct scenarios S1,S2 where d(S1)=v1, d(S2)=v2; verify R(S1)!=R(S2). 3. If any pair collapses, the representation is inadequate."]
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle

last_seen: S2558. Candidate lifecycle: ACTIVE.
Evidence: none recorded (no retraction/supersession/contradiction rows found). This ACTIVE classification is a heuristic based on how recently (by source_id, last_seen=S2558) this label was last used in the captured contribution set, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S2551, S2558 |
| type_signature | PRESENT | S2551 |
| invariants | PRESENT | S2558 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S2558 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S2558 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale

NOT-EVIDENCED-IN-CAPTURE

## Assumption register

NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)

- `[S2551]` types=[DEFINITION, FORMALIZATION] scope=THEORY-LEVEL — "Formalizes a semantic distinction as an equivalence relation ~_d on the state space S, defines when an encoding E:S->K preserves versus collapses a distinction, and defines R_req-adequacy of a projection pi:K->K' as preserving every distinction in R_req through the composition pi∘E." (anchor: "A semantic distinction d is an equivalence relation \sim_d on S ... K preserves a distinction d ... iff forall s1,s2: (s1 not-sim_d s2) implies (E(s1) != E(s2)). If E(s1)=E(s2) when s1 not-sim_d s2, the language K collapses the distinction d. ... pi is R_req-adequate if forall d in R_req, forall s1,s2: (s1 not-sim_d s2) implies (pi(E(s1)) != pi(E(s2)))")
- `[S2558]` types=[FORMALIZATION, RESTATEMENT] scope=THEORY-LEVEL — "Restates the Preserves/Adequate/Collapse formalism in slightly different but compatible terms to S2551 (function-based d(x)!=d(y)=>R(x)!=R(y) rather than equivalence-relation-based), and adds a fourth defining property not present in S2551: Minimality -- R_req should be the minimal set satisfying preservation, non-collapse, and compositionality (i.e. no redundant distinctions)." (anchor: "Preservation: forall d in R_req, any adequate representation R must preserve d. ... Non-collapse ... Compositionality ... Minimality: R_req is the minimal set satisfying 1-3. ... R preserves d iff forall x,y in Domain, d(x)!=d(y) => R(x)!=R(y). ... R is adequate for a question Q iff D_Q subseteq Preserved(R).")
- `[S2558]` types=[VALIDATION, EXPERIMENT] scope=METHODOLOGICAL — "Gives a concrete three-step adequacy verification procedure applied per distinction (enumerate values, construct scenario pairs per value pair, verify distinct representation, else the representation is inadequate) -- operationally very close to S2551's Separation Test but framed as a general per-distinction algorithm rather than a named test." (anchor: "1. Identify the set of values V_d 2. For each pair v1,v2 in V_d, v1!=v2: construct scenarios S1,S2 where d(S1)=v1, d(S2)=v2; verify R(S1)!=R(S2). 3. If any pair collapses, the representation is inadequate.")

## Notes for P3

No internal tension or unusual evidentiary pattern noticed while assembling this file; the rows are mutually consistent at the level this pass can check.

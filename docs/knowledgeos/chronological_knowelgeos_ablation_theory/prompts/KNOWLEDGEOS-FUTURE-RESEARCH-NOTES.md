# Future research notes — recorded, NOT applied (frozen experiments are not patched)

| | |
|---|---|
| Kind | research notes. ⚠ authority: generated. None of these changes Gate 2, Gate 2.5, H-F2-1-R, A6, D3 or T-A |
| Use | input to the L0 research-decision package **after** independent reproduction and the first targeted corpus pass |

## FRN-1 — semantic vs implementation-generated nondeterminism (review obligation after reproduction)
- Both gates decide universal properties on the **maximal** step relation. So every component an axiom leaves unconstrained changes **arbitrarily** in a step. That is legitimate only if "unconstrained" really means "arbitrarily changeable" in the intended semantics.
- Free components per kind under the base axioms:
  - GOV may change s and g (A2e fixes e only);
  - EVID/EVIDREF may change u unless A6 holds, and change e within ≤;
  - WORK may change u unless A6 holds.
- After reproduction, each FAILS or REACHABLE witness is to be checked for whether it **uses** such a free change. It is then marked "witness depends on free component X", which is an item for the L0 package. Nothing is re-classified.

## FRN-2 — the three separating statements (candidate central questions, not properties of the frozen spec)
1. Authorization(t_a) ⇏ EB(t_p): observed through T1–T6 (e, u, EB at t_a vs t_p).
2. PromotionEvent(t_p) ⇒ EB(t_p), if event-time eligibility is required: this is EVENT-ELIGIBILITY-SAFETY.
3. Adopted(t_p) ⇏ EB(t > t_p): the frozen PERSIST uses **EF**, not EB. An EB form (reachability of ad = 1 ∧ ¬EB) is a **future** property. Not added to r1.
- Whether any of the three implications is intended is a **corpus question** (OQ-T1…T4), not a model result.

## FRN-3 — DDD concepts vs formal state (the gaps are recorded, not modelled)

| Concept | Gate 2.5 encoding | Gap |
|---|---|---|
| Evidence | e | — |
| EvidenceAssessment | the predicates E0, EB, EF | EF is a modelling choice |
| Eligibility | EB | — |
| Authorization | au (0→1 = AuthEvent) | scope and authority (OQ-T1) not modelled |
| PromotionEvent | the step ad 0→1 | — |
| AdoptionState | ad | — |
| Reassessment | only as REVAL-a/b | no reassessment act |
| Revocation / Amendment | rv (P1) | amendment not modelled |
| HistoricalRecord | the trajectory | not a state object |

- These concepts stay separate. None may be collapsed into one Promotion aggregate or predicate.

## FRN-5 — TOCTOU-strict (from the Gate 2.5 prediction mismatch, F-LOG-0075)
- The frozen TOCTOU/T4 pattern asks only for a ¬EF state **between** authorization and promotion. Witnesses can restore the evidence before promoting.
- Two concepts, kept distinct:
  - **Weak TOCTOU** (what SPEC-G25-r1 tested): ∃t · t_a < t < t_p ∧ ¬EF(t).
  - **Strict TOCTOU:** ¬EF(t_p), after an AuthEvent at t_a with EF(t_a). This is the actual time-of-check/time-of-use question.
- EVENT-FLOOR-SAFETY already decides the event-time condition universally. The strict existential form would give a named witness.
- It would be a separate, pre-registered experiment only. Not applied.

## FRN-4 — comparator lesson
- Per-criterion **entry counts** of a canonical result are value-dependent where entries exist only conditionally (scenario sub-fields exist only when a scenario is reachable). Values-blind tooling must print booleans only. Evidence: F-LOG-0069.

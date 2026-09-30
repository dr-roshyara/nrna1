# derivability-status-dimension

**Scope(s):** OBJECT · **Row count:** 4 · **Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `DS_S(p) in {Provable,Refutable,Undecidable,Unestablished}` · **Aliases:** `formal derivability status`
**Candidate group membership (NOT an identity claim):**
- **G0578**: [`derivability-status-dimension` · `h-zero-001-unknown-valid-state`] — explicit agent-stated uncertainty: 'derivability-status-dimension' POSSIBLY relates to 'h-zero-001-unknown-valid-state' (batch B0061). Note: Candidate independent dimension DerivabilityStatus, distinguishing Unknown_S (current formal system cannot decide p) from Unknown_evidence/Unknown_access/Unknown_structural; labels must not be confused with truth or epistemic standing. [PROP].
- **G0592**: [`bounded-nonomniscient-reasoning-candidate` · `derivability-status-dimension`] — explicit agent-stated uncertainty: 'bounded-nonomniscient-reasoning-candidate' POSSIBLY relates to 'derivability-status-dimension' (batch B0061). Note: A four-way distinction (Derivable_S(p), Accessible_S(p), ExplicitlyRepresented(p), Determined(p)) explicitly named as connecting four independent research tracks in this project: Godel (derivability), Williamson (accessibility), Shieber (process/basing), Levesque-Lakemeyer (explicit/implicit/bounded reasoning via the B operator, which avoids logical omniscience by not closing beliefs under implication); explicitly keeps B != KnowledgeOS Standing and FDE != KnowledgeOS ontology even though Levesque-Lakemeyer independently confirms the ideal-consequence-vs-available-belief distinction.

## Sources (how this label entered the ledger)
- **PROPOSAL** batch `B0061`, scope `OBJECT`: Candidate independent dimension DerivabilityStatus, distinguishing Unknown_S (current formal system cannot decide p) from Unknown_evidence/Unknown_access/Unknown_structural; labels must not be confused with truth or epistemic standing. [PROP].

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2531 §"DerivabilityStatus ... DS(p)\in\{Provable,Refutable,Undecidable,Unestablished\} but these labels must not be confused with truth or epistemic standing. ... The reasoning system itself has a boundary. Representation Limit, Epistemic Access Limit, Formal Derivability Limit. These are different."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S2531 §"DerivabilityStatus ... DS(p)\in\{Provable,Refutable,Undecidable,Unestablished\} but these labels must not be confused with truth or epistemic standing. ... The reasoning system itself has a boundary. Representation Limit, Epistemic Access Limit, Formal Derivability Limit. These are different."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S2531. Candidate lifecycle: **ACTIVE**. Evidence: no retraction/supersession/contradiction evidence recorded; the ACTIVE classification is a heuristic based on how recently (by source_id ordering) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S2531 |
| type_signature | PRESENT | S2531 |
| invariants | PRESENT | S2531 |
| dependencies | PRESENT | S2531, S2531 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S2531, S2531 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S2531] types=['FORMALIZATION', 'DISTINCTION'] scope=CROSS-OBJECT — "Introduces DerivabilityStatus DS_S(p) in {Provable, Refutable, Undecidable, Unestablished} as a dimension independent of truth/epistemic standing, distinct from Unknown_evidence/Unknown_access/Unknown_structural (Williamson); argues the reasoning system itself has a boundary, distinguishing Representation Limit, Epistemic Access Limit, and Formal Derivability Limit as three different limits -- described as probably the biggest missing dimension revealed by Godel." (anchor: "DerivabilityStatus ... DS(p)\in\{Provable,Refutable,Undecidable,Unestablished\} but these labels must not be confused with truth or epistemic standing. ... The reasoning system itself has a boundary. Representation Limit, Epistemic Access Limit, Formal Derivability Limit. These are different.")
- [S2531] types=['EXTENSION', 'DISTINCTION'] scope=OBJECT — "Proposes folding the reasoning system S into the evaluation context Gamma=(S,C,T,P,...), formally justifying context-relative evaluation (Eval_c may differ purely because S differs); also splits 'theory incomplete' into two distinct causes -- merely missing axioms (resolvable by a modest extension S') versus Godel-type structural incompleteness -- giving TheoryIncomplete != SystematicallyUndecidable." (anchor: "Gamma=(S,C,T,P,...) ... Eval_c(K,r,Gamma_1) may differ from Eval_c(K,r,Gamma_2) because S_1\neq S_2. ... TheoryIncomplete \neq SystematicallyUndecidable")
- [S2531] types=['HYPOTHESIS', 'EXTENSION'] scope=CROSS-OBJECT — "Proposes extending the running FDE-factor series into a six-factor EVal = (Standing, Boundary, Derivability, Accessibility, Context, Provenance), building on the prior five-factor hypothesis (Standing x Boundary x Context x Provenance x Accessibility from S2528) by adding DerivabilityStatus; explicitly 'not adopted', flagged as the next representation hypothesis to test." (anchor: "EVal = (Standing, Boundary, Derivability, Accessibility, Context, Provenance) ... Gods suggests adding DerivabilityStatus as a separate factor. This is not adopted. It is the next representation hypothesis to test.")
- [S2531] types=['CORRECTION'] scope=CROSS-OBJECT — "Explicit rejection list: KnowledgeOS=formal-system, KnowledgeOS=theorem-prover, Contr=Inconsistency, Unknown=Undecidable, Truth=Provability, Verification=Consistency, and 'Godel incompleteness = AI limitation' -- all called category errors." (anchor: "Godel does not justify KnowledgeOS = formal system ... Contr = Inconsistency ... Unknown = Undecidable ... Truth = Provability ... Verification = Consistency ... Godel incompleteness = AI limitation. Those would all be category errors.")

## Notes for P3
(none beyond what is noted above)

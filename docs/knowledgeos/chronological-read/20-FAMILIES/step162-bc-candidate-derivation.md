# step162-bc-candidate-derivation

**Scope(s):** THEORY-LEVEL · **Row count:** 4 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** Authorization = boundary object between Governance and Operations, Decision -> Existing/Strong Governance Boundary, Determination = BC Candidate, Evidence -> Strong BC Candidate, Knowledge -> Strong BC Candidate · **Aliases:** DDD Boundary Test (5 questions), provisional bounded-context map (step 162)
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0033, scope THEORY-LEVEL): Step 162's invariant-driven bounded-context derivation: Evidence and Knowledge are judged strong BC candidates (each having its own lifecycle/invariants/ownership/language distinct from the other, related only by 'supports', not identity); Determination is a candidate pending further validation of its distinctness from Decision; Decision is folded into an existing/strong Governance boundary rather than becoming its own BC; Authorization is modelled as a boundary/interface object between Governance and Operations rather than forced into either; Action/Execution are likely one operational context; Observation is a probably-shared epistemic concept; Inquiry possibly belongs inside epistemic investigation rather than its own BC. Introduces the five-question 'DDD Boundary Test' for any proposed context split (does language change? invariants? ownership? lifecycle? consistency requirement? -- mostly-no answers mean no new context is needed), and a context-translation principle Translation_ij(M_i)->M_j across contexts (worked examples: Decision_G -> Command_O; Evidence_I vs Evidence_A) plus a warning against a 'UniversalKnowledgeObject' universal domain model, preferring 'canonical identity + explicit translation'.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1355 §"Evidence → Strong BC Candidate ... Knowledge → Strong BC Candidate ... The exact boundary with Evidence must be carefully designed. ... Determination = BC Candidate but we should verify whether it is genuinely distinct from Decision. ... Decision → Existing/Strong Governance Boundary rather than necessarily creating a brand-new 'Decision BC.' ... Authorization ... a boundary object between Governance and Operations."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1355 §"For each proposed split ask five questions: 1. Does the language change? 2. Do the invariants change? 3. Does ownership change? 4. Does lifecycle change? 5. Does consistency requirement change? If the answer is mostly no, we probably don't need another bounded context. This is our DDD Boundary Test. ... Translation_{ij}(M_i) → M_j. This is precisely why DDD context maps matter."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1358. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction evidence recorded. This is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S1355 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1355 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1358 |
| dependencies | PRESENT | S1355 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1355, S1358 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | PRESENT | S1355 |

## Rationale
- **[ANALYSIS/EXTENSION]** [S1355]: Derives concrete bounded-context candidacy from the invariant catalog: Evidence (having its own acquisition/provenance/integrity/source/observation-method/timestamp/relevance/immutability/verification concerns distinct from Knowledge) is now a Strong BC Candidate, revising the earlier weaker position; Knowledge (claim lifecycle, validity, authority, evidence relationships, supersession, contradiction, context) is also a Strong BC Candidate, related to Evidence only by 'supports', not identity; Determination is a BC Candidate pending verification of genuine distinctness from Decision; Decision is folded into an Existing/Strong Governance Boundary rather than becoming its own BC, given substantial existing governance/decision mechanisms; Authorization is modelled as a boundary object between Governance and Operations rather than assigned to either side.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- **[S1355]** types=[ANALYSIS, EXTENSION] scope=THEORY-LEVEL — "Derives concrete bounded-context candidacy from the invariant catalog: Evidence (having its own acquisition/provenance/integrity/source/observation-method/timestamp/relevance/immutability/verification concerns distinct from Knowledge) is now a Strong BC Candidate, revising the earlier weaker position; Knowledge (claim lifecycle, validity, authority, evidence relationships, supersession, contradiction, context) is also a Strong BC Candidate, related to Evidence only by 'supports', not identity; Determination is a BC Candidate pending verification of genuine distinctness from Decision; Decision is folded into an Existing/Strong Governance Boundary rather than becoming its own BC, given substantial existing governance/decision mechanisms; Authorization is modelled as a boundary object between Governance and Operations rather than assigned to either side." (anchor: "Evidence → Strong BC Candidate ... Knowledge → Strong BC Candidate ... The exact boundary with Evidence must be carefully designed. ... Determination = BC Candidate but we should verify whether it is genuinely distinct from Decision. ... Decision → Existing/Strong Governance Boundary rather than necessarily creating a brand-new 'Decision BC.' ... Authorization ... a boundary object between Governance and Operations.")
- **[S1355]** types=[PRINCIPLE, FORMALIZATION] scope=METHODOLOGICAL — "Defines the five-question 'DDD Boundary Test' for any proposed context split (does language change? invariants? ownership? lifecycle? consistency requirement?) -- mostly-no answers mean no new bounded context is warranted. Formalizes cross-context translation: for regions C_i, C_j with internally coherent models M_i, M_j, one must not assume M_i=M_j but instead define Translation_ij(M_i)->M_j (worked examples: Decision_G in Governance vs Command_O in Operations should not share a model; Evidence_I in an Investigation context vs Evidence_A in Assurance can share a conceptual superclass while differing contextually, potentially justifying a future Shared Kernel, 'but not yet'). Warns against building a 'UniversalKnowledgeObject' containing every possible property, preferring 'canonical identity + explicit translation'." (anchor: "For each proposed split ask five questions: 1. Does the language change? 2. Do the invariants change? 3. Does ownership change? 4. Does lifecycle change? 5. Does consistency requirement change? If the answer is mostly no, we probably don't need another bounded context. This is our DDD Boundary Test. ... Translation_{ij}(M_i) → M_j. This is precisely why DDD context maps matter.")
- **[S1355]** types=[RESTATEMENT, FUTURE-RESEARCH] scope=THEORY-LEVEL — "Step 162's major result: bounded contexts must not be created per concept; boundaries emerge from invariants, ownership, lifecycle, and consistency; current strongest BC candidates are Evidence, Knowledge, Governance, with Determination requiring further validation and Authorization acting as a governance/operations boundary. Exit criterion: every major concept needs Definition+Invariant+Owner+Lifecycle+Boundary+Evidence, else the architecture stays provisional. Proposes Step 163 = Context Map and Translation Boundaries, examining Evidence->Knowledge, Knowledge->Determination, Determination->Governance, Governance->Authorization, Authorization->Operations, Execution->Observation via Context->Contract->Translation->Context." (anchor: "We should NOT create a bounded context for every concept. Instead: Boundaries emerge from invariants, ownership, lifecycle, and consistency. Our current strongest candidates are: Evidence, Knowledge, Governance with: Determination requiring further validation. And: Authorization acting as a boundary between governance and operations. ... Definition + Invariant + Owner + Lifecycle + Boundary + Evidence. If one of these is missing, the architecture remains provisional.")
- **[S1358]** types=[DISTINCTION] scope=THEORY-LEVEL — "Aggregate != BoundedContext: a bounded context (e.g. Governance) can contain several aggregates (Policy, Decision, Authority, Delegation), so classifying Governance as a BC does not mean Governance itself is one aggregate." (anchor: "Aggregate ≠ BoundedContext. A bounded context can contain several aggregates. ... Governance BC ├── Policy ├── Decision ├── Authority └── Delegation. So our earlier candidate: Governance = BC does not imply: Governance = Aggregate.")

## Notes for P3
- Own observation: ungrouped in P2a — no co-occurrence or notation signal tied it to another label; may be a genuinely isolated object, or simply under-linked by the mechanical pass.

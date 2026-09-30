# v1-1-sim-factivity-impossibility-finding

**Scope(s):** OBJECT · **Row count:** 19 · **Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** CE-1, DEF-1, K = Gamma(E,Q,C,EC), TG-1 · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
- **G1773** [`knowledgeos-theory-v1-1-simulation-experiment-protocol` · `v1-1-sim-factivity-impossibility-finding`] — labels co-occur in the same contribution's labels[] 3 separate times across the corpus
- **G1918** [`theory-v1-0-def-register-findings-2026-09-10` · `v1-1-sim-factivity-impossibility-finding`] — labels co-occur in the same contribution's labels[] 2 separate times across the corpus

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0058, scope OBJECT): The v1.1 simulation's central negative result: DEF-1 factivity and the v1.1 attribution equation K=Gamma(E,Q,C,EC) are jointly unsatisfiable for any total attributing Gamma, established by an explicit witness (two worlds, same epistemic state, different truth, e.g. attributed os=RHEL9.8 vs true RHEL8.6) and confirmed at scale (420/10000 randomized trials, 414/665 violations when a policy-trusted source reports a falsehood, 0/2607 otherwise). Three unchosen repairs (R1 rename K_t, R2 externalize factivity, R3 partial Gamma) are registered in the theory gap register.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2390 §"19 PASS / 1 PARTIAL / 0 FAIL in the deterministic suite ... this is the *weakest* outcome, not the strongest."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S2482 §"(1)  Knows(a,p,c,t) → True(p,c,t)                     DEF-1, factivity
(2)  K_{a,c,t} = Γ(E_{a,c,t}, Q_t, C_t, EC_t)         the v1.1/v1.2 attribution equation

**They are jointly unsatisfiable for any total `Γ` that attributes anything.**"]
- CANDIDATE-OPERATIONAL-BIRTH: [S2482 §"Two worlds differ in the truth of `p`. A source the admission policy rates reliable reports the same value in both. The resulting epistemic states are **bit-identical**"]
- CANDIDATE-GOVERNANCE-BIRTH: [S2392 §"No total Gamma : (E,Q,C,EC) -> K that attributes anything is factive | DERIVED PROPOSITION, witness-supported ... not yet written as a theorem in the theory document"]

## Lifecycle
last_seen: S2923. Candidate lifecycle: ACTIVE.
Evidence: No retraction/supersession/contradiction evidence recorded. This is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S2390, S2392, S2393, S2394, S2482 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S2482 |
| type_signature | PRESENT | S2482 |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | PRESENT | S2482 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S2396, S2923 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S2390 |
| experiments | PRESENT | S2390, S2394, S2397, S2482, S2923 |
| open_questions | PRESENT | S2394, S2397 |

## Rationale
- **[ANALYSIS/EXPERIMENTAL-RESULT]** [S2390]: Factivity diagnosis: conditional on an attribution being made, violation rate is 0.6226 (414/665) when a policy-trusted source reported a false value versus 0/2607 otherwise, interpreted as 'the witness in A at scale' rather than a tuning problem.
- **[ARGUMENT/GOVERNANCE]** [S2392]: Grades the factivity-impossibility claim as a derived, witness-supported proposition whose general one-line argument (Gamma's domain excludes truth) has not yet been written as a formal theorem in the theory document.
- **[ANALYSIS]** [S2393]: CIRC-1 (Knowledge-via-Truth / Truth-via-Knowledge) is found NOT circular, and this non-circularity is identified as the same underlying fact that produces CE-1: World.truth is exogenous and no agent transition reads it, so Gamma structurally cannot be factive.
- **[ALTERNATIVE/OPEN-QUESTION]** [S2394]: Presents three unchosen repairs for TG-1 -- R1 rename K_t to attributed knowledge, R2 make factivity an externally-verified success condition no component may assert, R3 make Gamma partial (attribute only where externally verifiable) -- explicitly declaring the choice a theory/governance decision outside this experiment's scope.
- **[ARGUMENT/FORMALIZATION]** [S2482]: States the core formal impossibility result: DEF-1 (Knows(a,p,c,t)->True(p,c,t)) and the attribution equation K=Gamma(E,Q,C,EC) are jointly unsatisfiable for any total Gamma that attributes anything.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- **[S2390]** types=[EXPERIMENTAL-RESULT, WARNING] scope=CROSS-OBJECT — "Deterministic suite across 10 scenarios x 20 properties yields 19 PASS / 1 PARTIAL(CIRC-5)/2 DEFINITIONAL, but the report explicitly warns this is the weakest evidence in the paper because deterministic passes can be vacuous." (anchor: "19 PASS / 1 PARTIAL / 0 FAIL in the deterministic suite ... this is the *weakest* outcome, not the strongest.")
- **[S2390]** types=[EXPERIMENTAL-RESULT] scope=OBJECT — "Adversarial suite: AD-1 attributes os=RHEL9.8 against true RHEL8.6 (CE-1 factivity failure); AD-18's injected unsupported conclusion is caught by P10 yet still attributed (CE-2 positive-control failure, since attribution never checks provenance)." (anchor: "AD-1 highly probable but false claim ... FAIL — CE-1 ... AD-18 conclusion with no supporting evidence ... FAIL — CE-2 (positive control)")
- **[S2390]** types=[EXPERIMENTAL-RESULT, WARNING] scope=OBJECT — "Randomized layer (10,000 trials, 5 seeds x 2000): P11's guard activates in 33.94% of trials with 420 failures (conditional failure rate 0.1237); P3's guard never activates so its 100% pass rate is vacuous and excluded from evidence." (anchor: "P11 | 0.3394 ... 420 | 0.1237 ... P3's guard never activated ... Its 100% pass is vacuous and must not be cited")
- **[S2390]** types=[ANALYSIS, EXPERIMENTAL-RESULT] scope=OBJECT — "Factivity diagnosis: conditional on an attribution being made, violation rate is 0.6226 (414/665) when a policy-trusted source reported a false value versus 0/2607 otherwise, interpreted as 'the witness in A at scale' rather than a tuning problem." (anchor: "a source rated reliable reported a **false** value | **414 / 665** | **0.6226** ... Every violation is explained by the epistemic state being unable to distinguish a reliable-looking false report from a true one.")
- **[S2392]** types=[ARGUMENT, GOVERNANCE] scope=OBJECT — "Grades the factivity-impossibility claim as a derived, witness-supported proposition whose general one-line argument (Gamma's domain excludes truth) has not yet been written as a formal theorem in the theory document." (anchor: "No total Gamma : (E,Q,C,EC) -> K that attributes anything is factive | DERIVED PROPOSITION, witness-supported ... not yet written as a theorem in the theory document")
- **[S2393]** types=[ANALYSIS] scope=OBJECT — "CIRC-1 (Knowledge-via-Truth / Truth-via-Knowledge) is found NOT circular, and this non-circularity is identified as the same underlying fact that produces CE-1: World.truth is exogenous and no agent transition reads it, so Gamma structurally cannot be factive." (anchor: "The non-circularity and the impossibility are the same fact. Because truth never re-enters the agent's state, Gamma cannot consult it -- which is exactly why CE-1 occurs.")
- **[S2394]** types=[EXPERIMENTAL-RESULT] scope=OBJECT — "Registers TG-1 (CRITICAL, G3 epistemological class): the factivity/attribution-equation impossibility, evidenced by the CE-1 witness and 420/10000 randomized violations." (anchor: "TG-1 | DEF-1 factivity and the v1.1 equation K = Gamma(E,Q,C,EC) are jointly unsatisfiable for any attributing Gamma | G3 epistemological | CE-1; witness; 420/10000 randomized violations | CRITICAL")
- **[S2394]** types=[ALTERNATIVE, OPEN-QUESTION] scope=OBJECT — "Presents three unchosen repairs for TG-1 -- R1 rename K_t to attributed knowledge, R2 make factivity an externally-verified success condition no component may assert, R3 make Gamma partial (attribute only where externally verifiable) -- explicitly declaring the choice a theory/governance decision outside this experiment's scope." (anchor: "R1 -- Rename the object ... R2 -- Make factivity external ... R3 -- Make Gamma partial ... The choice is a theory decision, not an experimental one. This lane does not make it.")
- **[S2396]** types=[RESTATEMENT] scope=THEORY-LEVEL — "States the one-line headline result for the whole v1.1 simulation lane and its Theory Status verdict (B. PARTIALLY EXECUTABLE), restated from the FINAL-VERDICT artifact." (anchor: "The theory executes the whole epistemic lifecycle and keeps every distinction it declares -- except that DEF-1 (factivity) and the v1.1 attribution equation K = Gamma(E,Q,C,EC) cannot both hold. Theory status: B. PARTIALLY EXECUTABLE.")
- **[S2397]** types=[EXPERIMENTAL-RESULT] scope=OBJECT — "Restates the factivity-impossibility as established, adding that the only Gamma escaping the contradiction attributes nothing at all." (anchor: "DEF-1 (factivity) and the v1.1 attribution equation are jointly unsatisfiable for any total Gamma that attributes anything ... The only escaping policy attributes nothing.")
- **[S2397]** types=[FUTURE-RESEARCH] scope=METHODOLOGICAL — "Prescribes the Factivity Repair Experiment as the mandatory next step: run R1/R2/R3 as three arms over the identical 10,000 worlds, explicitly forbidding another large randomized run before this." (anchor: "The Factivity Repair Experiment. Implement all three repairs from L ... as three arms over the same scenarios and the same 10,000 worlds ... Do not run another large randomized simulation first.")
- **[S2404]** types=[CORRECTION] scope=OBJECT — "First self-review downgrades Sat from a proposed [DEF] to [PROP], citing the v1.1 factivity/Gamma-determinacy impossibility as the reason it cannot yet be mathematically closed." (anchor: "the review says: Strong candidate [DEF] Sat: K x R -> {top,bot,U}. I would change that to: [PROP] Candidate common satisfaction interface ... because our latest simulation has already exposed a fundamental issue: factivity vs Gamma-determinacy.")
- **[S2482]** types=[ARGUMENT, FORMALIZATION] scope=OBJECT — "States the core formal impossibility result: DEF-1 (Knows(a,p,c,t)->True(p,c,t)) and the attribution equation K=Gamma(E,Q,C,EC) are jointly unsatisfiable for any total Gamma that attributes anything." (anchor: "(1)  Knows(a,p,c,t) → True(p,c,t)                     DEF-1, factivity
(2)  K_{a,c,t} = Γ(E_{a,c,t}, Q_t, C_t, EC_t)         the v1.1/v1.2 attribution equation

**They are jointly unsatisfiable for any total `Γ` that attributes anything.**")
- **[S2482]** types=[EXPERIMENT, EXPERIMENTAL-RESULT] scope=OBJECT — "The witness proof: two possible worlds differing only in the truth of p, with an admission-policy-trusted source reporting identically in both, produce bit-identical epistemic states (verified by fingerprint over observations/evidence/interpretations/assessments/determinations/hypotheses/rejections); since Gamma is a function of E alone it returns an identical K, yet the attribution is true in one world and false in the other." (anchor: "Two worlds differ in the truth of `p`. A source the admission policy rates reliable reports the same value in both. The resulting epistemic states are **bit-identical**")
- **[S2482]** types=[EXPERIMENTAL-RESULT] scope=OBJECT — "Policy sweep: both attribution policies that attribute anything (justified-unique, justified-any) violate factivity; only the 'none' policy escapes violation, and only because it attributes nothing." (anchor: "| `justified-unique` | yes | **yes** |
| `justified-any` | yes | **yes** |
| `none` | **no** | no |")
- **[S2482]** types=[EXPERIMENTAL-RESULT] scope=OBJECT — "Quantifies the violation at scale: 420/10000 paired worlds violate factivity (conditional rate 0.1237 given an attribution is made); localization is near-total, 414 of 665 violations occur specifically where a policy-trusted source reported a falsehood, versus 0 of 2607 otherwise." (anchor: "**At scale:** 420 factivity violations in 10 000 paired worlds; conditional rate **0.1237** given an attribution was made. Diagnosis: **414 / 665** violations where a policy-trusted source reported a falsehood, **0 / 2 607** otherwise.")
- **[S2482]** types=[EXPERIMENTAL-RESULT, VALIDATION] scope=OBJECT — "Reports an independent confirmation from a completely different direction: no Sat_c class among the eight requirement classes requires factivity, visible from the specification alone before execution -- a state can satisfy all eight classes and still be false." (anchor: "**And the satisfaction layer confirms it independently:** **no `Sat_c` class in the eight-class family requires factivity.** A state can satisfy all eight and be false.")
- **[S2923]** types=[EXPERIMENTAL-RESULT, EXTENSION] scope=METHODOLOGICAL — "Establishes via content evidence (not internal timestamps, since none exist across all 14 files) that theory-v1.1-simulation postdates and directly simulates Theory v1.0 (matching experiment ID/scenario seed 20260902, exactly resolving citations to SS47's refined 7-state backbone and SS66's Preservation vector, using DEF numbers all within DEF-1..33). Classifies the package's own timestamp provenance as UNRECORDABLE, explicitly distinct from ABSENT." (anchor: "4. v1.1-simulation is identified, not inferred ... theory-v1.1-simulation simulates 20260902-004631, and postdates 00:46:31. Provenance of the document's own timestamp remains UNRECORDABLE, not ABSENT.")
- **[S2923]** types=[DISTINCTION, VALIDATION] scope=OBJECT — "Records two objects v1.1-simulation itself contributes: a typed EC=EpistemicContract(standard,requirements,attribution_policy), whose own 'standard' sub-field (EpistemicStandard(min_weight,require_independent_sources,require_corroboration)) is a DIFFERENT type from r's 'standard' field (an acceptance criterion) -- flagged as an unregistered standard/standard collision, not among v1.1's own ten registered collisions; and a 4-argument Gamma realized as K=Gamma(E,Q,C,EC)=knowledge_attribution(E,Q,C,EC), whose TG-1 proves jointly unsatisfiable with DEF-1 factivity for any attributing Gamma (the CE-1 finding, connecting directly to the prior batch's v1-1-sim-factivity-impossibility-finding object), with three unchosen repairs R1/R2/R3 explicitly framed as 'a theory decision, not an experimental one.' Verifies CE-1 is genuinely cross-cited by a separate package (satc_spec.py's factivity_requirement note), confirming real cross-package citation rather than coincidental naming." (anchor: "And it contributes two objects of its own: EC is typed ... EpistemicContract(standard, requirements, attribution_policy) ... A standard/standard collision on two carriers, and it is not among the 10 collisions v1.1 registers. ... Γ is 4-argument: K = Γ(E,Q,C,EC) ... TG-1 proves it jointly unsatisfiable with DEF-1 factivity ... CE-1 is born here and cited by satc_spec.py — a verified cross-package citation.")

## Notes for P3
- No unusual tension, evidentiary gap, or priority signal noticed beyond what is already recorded in the sections above.

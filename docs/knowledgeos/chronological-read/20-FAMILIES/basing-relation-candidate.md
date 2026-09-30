# basing-relation-candidate

**Scope(s):** OBJECT · **Row count:** 5 · **Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Base(e,d|Gamma)`, `Basing=(E,P,D,Gamma,Pi)` · **Aliases:** `evidence-grounds-determination relation`
**Candidate group membership (NOT an identity claim):**
- **G1820**: candidate group with `epistemic-lineage-provenance-candidate` — labels co-occur in the same contribution's labels[] 2 separate times across the corpus (mechanical signal only; relationship not yet decided, P3).
- **G1821**: candidate group with `epistemic-process-reliability-candidate` — labels co-occur in the same contribution's labels[] 3 separate times across the corpus (mechanical signal only; relationship not yet decided, P3).



## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0061, scope OBJECT): Candidate relation formalizing that a determination must actually be BASED ON (grounded/explained by) evidence, not merely co-occur with good evidence (the stopped-clock/Gettier phenomenon); distinguishes Evidence-exists != Evidence-supports != Determination-based-on-evidence != Determination-produced-by-reliable-process. Status: [STRONG DERIVATION CANDIDATE], not yet a Theory v1.3 primitive.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2533] §"Evidence \xrightarrow{Basing/Process} Determination \xrightarrow{Evaluation} Standing ... Reliability(Process,Environment,Context) ... Source\rightarrow Acquisition\rightarrow Evidence\rightarrow Process\rightarrow Determination\rightarrow Standing"
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S2533] §"merely possessing good evidence is not enough. A belief must be based on that evidence ... Base(e,d\mid\Gamma) ... Basing=(E,P,D,\Gamma,\Pi) ... Evidence exists \neq Evidence supports proposition \neq Determination was actually based on evidence \neq Determination was produced by a reliable process"
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S2533] §"Tier 1 — likely necessary to complete the theory: Basing relation, Epistemic process, Process reliability, Environment/context-relative reliability, Epistemic evidence lineage, Descriptive != normative, Standing != assurance ... Explicitly rejected: Foundationalism, Coherentism, Pragmatic encroachment, Contextualism, Knowledge=JTB, Bayes=kernel, Social externalism=ontology"

## Lifecycle
last_seen: S2533. Candidate lifecycle: ACTIVE. Evidence: none recorded (no retraction/supersession/contradiction signal) — this lifecycle label is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source ids |
|---|---|---|
| Purpose / rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| Informal meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| Formal definition | PRESENT | S2533 |
| Type signature | PRESENT | S2533 |
| Invariants | PRESENT | S2533 |
| Dependencies | PRESENT | S2533 |
| Assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| Semantics | PRESENT | S2533 |
| Examples | NOT-EVIDENCED-IN-CAPTURE | — |
| Warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| Experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| Open questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S2533] types=[EXTENSION] scope=THEORY-LEVEL — "Executive framing: Shieber's most important contribution is a missing middle layer between Evidence and Determination (Basing/Process) and between Determination and Standing (Evaluation), with process reliability relative to environment/context, and a full epistemic-lineage chain Source->Acquisition->Evidence->Process->Determination->Standing; claimed to strengthen Evaluation, Evidence, the succeq ordering, Contr, Zero, Determination, Lifecycle, Provenance, and delta without requiring a Theory v1.2 change." (anchor: "Evidence \xrightarrow{Basing/Process} Determination \xrightarrow{Evaluation} Standing ... Reliability(Process,Environment,Context) ... Source\rightarrow Acquisition\rightarrow Evidence\rightarrow Process\rightarrow Determination\rightarrow Standing")
- [S2533] types=[FORMALIZATION, DISTINCTION] scope=OBJECT — "Full formalization of the Basing relation Base(e,d|Gamma) / Basing=(E,P,D,Gamma,Pi), explicitly not importing 'belief', drawing the four-way distinction between evidence existing, evidence supporting, determination actually being based on evidence, and determination being produced by a reliable process -- called the 'biggest missing concept' from the book." (anchor: "merely possessing good evidence is not enough. A belief must be based on that evidence ... Base(e,d\mid\Gamma) ... Basing=(E,P,D,\Gamma,\Pi) ... Evidence exists \neq Evidence supports proposition \neq Determination was actually based on evidence \neq Determination was produced by a reliable process")
- [S2533] types=[FORMALIZATION, HYPOTHESIS] scope=CROSS-OBJECT — "Most precise candidate formal model from this file: D_t = Determine(E_t,B_t,P_t,Gamma_t,Pi_t) with a candidate seven-factor EVal = (Standing, Boundary, Reliability, Accessibility, Context, Provenance, Assurance), extending the running series of proposed FDE-factor extensions (from four to five to six to now seven factors across this batch's threads), explicitly flagged as a research target, NOT for adoption into Theory v1.3." (anchor: "D_t = Determine(E_t,B_t,P_t,\Gamma_t,\Pi_t) ... Eval_c(E_t,B_t,P_t,\Gamma_t,\Pi_t)\rightarrow EVal_c ... EVal=(Standing,Boundary,Reliability,Accessibility,Context,Provenance,Assurance). Important: this does not mean we should now add all seven factors to Theory v1.3.")
- [S2533] types=[GOVERNANCE] scope=THEORY-LEVEL — "Final three-tier ranking of 24 findings (Tier 1: Basing/Process/Reliability/context-relativity/lineage/descriptive-normative/standing-assurance -- 'likely necessary'; Tier 2: accessibility!=existence, generate/preserve/transform, distributed processes, source-reliability!=evidence-standing, know-that!=know-how, surprise->reassessment; Tier 3: Bayesian evaluation, deductive/inductive classification, internal/external process, social-network reliability) plus an explicit rejection list (Foundationalism, Coherentism, Pragmatic encroachment, Contextualism, Knowledge=JTB, Bayes=kernel, SocialExternalism=ontology); revises the critical path to Evidence->Basing->Process->Reliability->Evaluation->Standing/Boundary->Contr/Zero->Determination->Assurance->Decision->delta, and explicitly states Theory v1.2 should remain unchanged pending KR-SHIEBER-2026-09." (anchor: "Tier 1 — likely necessary to complete the theory: Basing relation, Epistemic process, Process reliability, Environment/context-relative reliability, Epistemic evidence lineage, Descriptive != normative, Standing != assurance ... Explicitly rejected: Foundationalism, Coherentism, Pragmatic encroachment, Contextualism, Knowledge=JTB, Bayes=kernel, Social externalism=ontology")
- [S2533] types=[RESTATEMENT, EXTENSION] scope=THEORY-LEVEL — "Final synthesis explicitly names a cross-session convergence spanning Plato, Davidson, Williamson, Godel, Description Logic and Shieber onto a ten-way distinctness chain (Representation!=Evidence!=Basing!=Process!=Derivation!=Determination!=Standing!=Truth!=Verification!=Decision), naming the Basing problem ('why does this determination count as based on this evidence') as one of the highest-value missing pieces in KnowledgeOS theory -- notable for citing Plato and Davidson as prior sources not otherwise seen in this batch's files." (anchor: "Across Plato \rightarrow Davidson \rightarrow Williamson \rightarrow Godel \rightarrow Description Logic \rightarrow and now Shieber ... Representation\neq Evidence\neq Basing\neq Process\neq Derivation\neq Determination\neq Standing\neq Truth\neq Verification\neq Decision ... Why does this determination count as being based on this evidence? That is the Basing problem.")

## Notes for P3
None — this label's evidence is internally consistent within the rows captured for this batch.

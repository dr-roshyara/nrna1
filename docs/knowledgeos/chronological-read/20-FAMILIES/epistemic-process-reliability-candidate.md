# epistemic-process-reliability-candidate

**Scope(s):** OBJECT · **Row count:** 6 ·
**Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** Process:Evidence->Determination, Reliable(P,Gamma), Reliable(P,Gamma1) not=> Reliable(P,Gamma2) · **Aliases:** process reliability, environment-relative
**Candidate group membership (NOT an identity claim):**
- G0955: links `epistemic-process-reliability-candidate` with `distributed-epistemic-process-candidate` — working_label token overlap Jaccard=0.60 (shared tokens: ['candidate', 'epistemic', 'process'])
- G1821: links `epistemic-process-reliability-candidate` with `basing-relation-candidate` — labels co-occur in the same contribution's labels[] 3 separate times across the corpus
- G1822: links `epistemic-process-reliability-candidate` with `epistemic-lineage-provenance-candidate` — labels co-occur in the same contribution's labels[] 2 separate times across the corpus

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0061, scope OBJECT): Candidate that knowledge depends on the process producing a belief/determination, not merely on evidence possessed; process P=(type,implementation,inputs,outputs,environment,provenance) with an evaluable Reliable(P,Gamma) property that is explicitly environment-relative (a process reliable in one environment may be unreliable in another); motivates Evaluation(P,E,Gamma) rather than Evaluation(P,E), relevant to but not resolving the open phi (frame) decision. Also separates EvidenceQuality(e,Gamma) from ProcessReliability(P,Gamma) from Basing(e,P,d,Gamma), giving DeterminationAdequacy = Evidence+Basing+Process+Context as distinct inspectable dimensions (not a value equation).

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2533] §"Evidence \xrightarrow{Basing/Process} Determination \xrightarrow{Evaluation} Standing ... Reliability(Process,Environment,Context) ... Source\rightarrow Acquisition\rightarrow Evidence\rightarrow Process\rightarrow Determination\rightarrow Standing"
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S2533] §"D_t = Determine(E_t,B_t,P_t,\Gamma_t,\Pi_t) ... Eval_c(E_t,B_t,P_t,\Gamma_t,\Pi_t)\rightarrow EVal_c ... EVal=(Standing,Boundary,Reliability,Accessibility,Context,Provenance,Assurance). Important: this does not mean we should now add all seven factors to Theory v1.3."
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S2533] §"Tier 1 — likely necessary to complete the theory: Basing relation, Epistemic process, Process reliability, Environment/context-relative reliability, Epistemic evidence lineage, Descriptive != normative, Standing != assurance ... Explicitly rejected: Foundationalism, Coherentism, Pragmatic encroachment, Contextualism, Knowledge=JTB, Bayes=kernel, Social externalism=ontology"

## Lifecycle
last_seen: S2533. Candidate lifecycle: ACTIVE.
Evidence: No retraction/supersession/contradiction evidence recorded. The ACTIVE label is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S2533 |
| type_signature | PRESENT | S2533 |
| invariants | PRESENT | S2533 |
| dependencies | PRESENT | S2533 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | PRESENT | S2533 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- `[S2533]` types=[EXTENSION] scope=THEORY-LEVEL — "Executive framing: Shieber's most important contribution is a missing middle layer between Evidence and Determination (Basing/Process) and between Determination and Standing (Evaluation), with process reliability relative to environment/context, and a full epistemic-lineage chain Source->Acquisition->Evidence->Process->Determination->Standing; claimed to strengthen Evaluation, Evidence, the succeq ordering, Contr, Zero, Determination, Lifecycle, Provenance, and delta without requiring a Theory v1.2 change." (anchor: "Evidence \xrightarrow{Basing/Process} Determination \xrightarrow{Evaluation} Standing ... Reliability(Process,Environment,Context) ... Source\rightarrow Acquisition\rightarrow Evidence\rightarrow Process\rightarrow Determination\rightarrow Standing")
- `[S2533]` types=[EXAMPLE/CORRECTION] scope=OBJECT — "The stopped-clock example (apparently correct evidence + accidentally true conclusion, produced by an unreliable process) is used to reject GoodEvidence(e)=>GoodDetermination(d), motivating the EvidenceQuality/ProcessReliability/Basing decomposition and DeterminationAdequacy=Evidence+Basing+Process+Context as separately inspectable dimensions." (anchor: "same apparent evidence + same true conclusion \neq knowledge ... because the process that produced the conclusion is unreliable in that environment [stopped-clock case] ... GoodEvidence(e)\Rightarrow GoodDetermination(d) [rejected]")
- `[S2533]` types=[EXTENSION] scope=OBJECT — "Argues evidence quality is not intrinsic but depends on its producing system: proposes a three-level chain Source->Process->Evidence with SourceSystem(e)=S and Reliability(S,Gamma) as the evaluable objects, rather than treating Evidence(e) as atomic." (anchor: "naive foundationalism cannot explain why one perception is reliable and another misleading; externalism can distinguish them by appeal to the reliable accuracy of the perceptual system ... SourceSystem(e)=S ... Source\rightarrow Process\rightarrow Evidence rather than treating Evidence as an atomic thing whose quality is intrinsic.")
- `[S2533]` types=[CORRECTION/EXTENSION] scope=OBJECT — "Bayesian reasoning is used to argue background/prior knowledge affects interpretation of new evidence, Evaluation(e,h,Gamma,K_background), but explicitly does NOT justify Knowledge=Probability or Confidence=Knowledge; Bayesian calculation kept as one candidate evaluation method, not a kernel primitive." (anchor: "new evidence is interpreted against what is already known—the prior probability ... does not justify Knowledge = Probability or Confidence = Knowledge. ... Evaluation(e,h,\Gamma,K_{background}) ... Bayesian calculation should remain an evaluation method, not a constitutional kernel primitive.")
- `[S2533]` types=[FORMALIZATION/HYPOTHESIS] scope=CROSS-OBJECT — "Most precise candidate formal model from this file: D_t = Determine(E_t,B_t,P_t,Gamma_t,Pi_t) with a candidate seven-factor EVal = (Standing, Boundary, Reliability, Accessibility, Context, Provenance, Assurance), extending the running series of proposed FDE-factor extensions (from four to five to six to now seven factors across this batch's threads), explicitly flagged as a research target, NOT for adoption into Theory v1.3." (anchor: "D_t = Determine(E_t,B_t,P_t,\Gamma_t,\Pi_t) ... Eval_c(E_t,B_t,P_t,\Gamma_t,\Pi_t)\rightarrow EVal_c ... EVal=(Standing,Boundary,Reliability,Accessibility,Context,Provenance,Assurance). Important: this does not mean we should now add all seven factors to Theory v1.3.")
- `[S2533]` types=[GOVERNANCE] scope=THEORY-LEVEL — "Final three-tier ranking of 24 findings (Tier 1: Basing/Process/Reliability/context-relativity/lineage/descriptive-normative/standing-assurance -- 'likely necessary'; Tier 2: accessibility!=existence, generate/preserve/transform, distributed processes, source-reliability!=evidence-standing, know-that!=know-how, surprise->reassessment; Tier 3: Bayesian evaluation, deductive/inductive classification, internal/external process, social-network reliability) plus an explicit rejection list (Foundationalism, Coherentism, Pragmatic encroachment, Contextualism, Knowledge=JTB, Bayes=kernel, SocialExternalism=ontology); revises the critical path to Evidence->Basing->Process->Reliability->Evaluation->Standing/Boundary->Contr/Zero->Determination->Assurance->Decision->delta, and explicitly states Theory v1.2 should remain unchanged pending KR-SHIEBER-2026-09." (anchor: "Tier 1 — likely necessary to complete the theory: Basing relation, Epistemic process, Process reliability, Environment/context-relative reliability, Epistemic evidence lineage, Descriptive != normative, Standing != assurance ... Explicitly rejected: Foundationalism, Coherentism, Pragmatic encroachment, Contextualism, Knowledge=JTB, Bayes=kernel, Social externalism=ontology")

## Notes for P3
- Participates in 3 candidate groups — a relatively dense cross-linkage; may deserve priority attention in reconciliation.

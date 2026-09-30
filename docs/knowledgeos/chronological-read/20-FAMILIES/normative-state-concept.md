# normative-state-concept

**Scope(s):** OBJECT · **Row count:** 11 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `N=(Roles,Duties,Obligations,Permissions,Prohibitions,Principles,Authority)`; `N_t`
**Aliases:** "Normative State"
**Candidate group membership (NOT an identity claim):**
- G0766: co-occurs with `gita-chapter3-karma-yoga-action-theory` — labels share the notation 'N_t'
- G0943: co-occurs with `actor-state-concept` — working_label token overlap Jaccard=0.50 (shared tokens: ['concept', 'state'])

## Sources (how this label entered the ledger)

- OBJECT-INDEX, batch B0020, scope OBJECT: "A first-class Normative State distinct from Ideal State, representing duties/permissions/prohibitions/obligations/principles/authority; introduces Role->Duty, Duty≠Goal, Authority≠Evidence, Source≠Authority."

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S0807 §"N_t = \text{Normative State}"]
- CANDIDATE-CONCEPTUAL-BIRTH: [S0807 §"N_t = \text{Normative State}"]
- CANDIDATE-FORMAL-BIRTH: [S0807 §"N_t = \text{Normative State}"]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S0808 §"Norm: \quad "Actor\ in\ Role\ R\ should\ perform\ D""]

## Lifecycle

last_seen: S0844. Candidate lifecycle: DORMANT.
Evidence: `lifecycle_evidence` is empty (retracted_by: [], superseded_by: [], contested_by_own_contradiction_type: false). DORMANT is a recency heuristic based on last use (S0844), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | PRESENT | S0844 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0807 (×2), S0808 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S0807 (×3), S0808 (×4) |
| dependencies | PRESENT | S0807 (×3), S0808 (×4), S0836, S0844 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0807, S0808 (×4) |
| examples | PRESENT | S0808 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale

Test Case 9 (a constitutional rule 'all production software must have an approved ADR') shows the pipeline must produce a NormativeProposition distinct in semantic type from a FactualProposition, exposing that a single undifferentiated Proposition model cannot serve both. Test Case 10 (a human instruction 'upgrade Nexus to 3.70') shows an instruction must NOT become Assertion:Nexus.version=3.70 directly — it produces Instruction:Upgrade(Nexus,3.70), which may later produce an Action, which may produce a new Observation, which may then produce an Assertion. This establishes two fundamentally different flows: Epistemic acquisition (Input->Observation->Interpretation->Assertion) and Action/effect (Instruction->Action->Observation->Knowledge) — flagged as important for later defining Sārathi [S0844].

## Assumption register

NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)

- [S0807] types=[CONCEPT, FORMALIZATION] scope=OBJECT — "Introduces Normative State N_t representing duties, permissions, prohibitions, obligations, principles, authoritative instructions and role-based norms, distinguished from Ideal State: IdealState ≠ NormativeState (Ideal = 'what should the desired state look like', Normative = 'what ought this actor do under these norms'), motivated by Chapter 3's repeated is/ought distinction (prescribed duty, action without attachment, acting per one's position). Called probably the single most important discovery from Chapter 3." (anchor: "N_t = \text{Normative State}")
- [S0807] types=[EXTENSION] scope=OBJECT — label_confidence UNCERTAIN — "Extends the discrepancy model to add Δ_N, normative discrepancy, with examples: unknown duty, conflicting duties, misunderstood obligation, action inconsistent with duty, insufficient authority, conflict between role and desired action — directly exposed by Arjuna's dilemma." (anchor: "\Delta_t = (\Delta_E, \Delta_U, \Delta_D, \Delta_N)")
- [S0807] types=[EXTENSION, FORMALIZATION] scope=OBJECT — label_confidence UNCERTAIN — "Chapter 3 grounds action in prescribed duty and position (verse 35: perform one's own duty rather than another's), requiring an explicit Role -> Duty relation, currently missing from the theory. Duty is then distinguished from Goal, IdealState and Constraint as four distinct semantic objects (Goal: achieve X; Duty: perform Y; Constraint: do not do Z; Ideal: state should become I), formalized as N=(Roles,Duties,Obligations,Permissions,Prohibitions,Principles,Authority) with NormativeAssessment(A,N,C) -> {Compliant,Violating,Undetermined,Conflicting}." (anchor: "Role \rightarrow Duty")
- [S0807] types=[DISTINCTION] scope=OBJECT — label_confidence UNCERTAIN — "Chapter 3's framing of authorized vs capricious action validates Authority ≠ Evidence and Source ≠ Authority — a source may provide evidence without normative authority; distinguishes Source, Evidence, Authority, Policy, Rule, Instruction as non-synonymous (Source->Evidence, Authority->Norm, Policy->DecisionRule), called crucial for KnowledgeOS governance." (anchor: "Authority \neq Evidence")
- [S0808] types=[CORRECTION, DISTINCTION] scope=OBJECT — label_confidence UNCERTAIN, `unknown_candidate.candidate_of`=[`normative-state-concept`] — "Retracts the overclaim 'Chapter 3 proves we need Normative State' as too strong; the chapter provides extensive normative language (prescribed duty v.8, regulated activity tied to Vedic direction v.15) from which the architectural inference is drawn that a NormativeModel is required to represent the domain, distinct from claiming the text itself defines 'NormativeState'. Reaffirms Authority -> NormativeDirection, Source ≠ Authority, Evidence ≠ Norm as one of the strongest architectural discoveries, but as an architectural inference rather than a direct textual claim." (anchor: "NormativeModel is required to represent this domain faithfully.") — lineage claim: SOURCE-CLAIMED-RETRACTION of S0807's claim 'Chapter 3 proves we need Normative State'.
- [S0808] types=[FORMALIZATION, DISTINCTION] scope=OBJECT — label_confidence UNCERTAIN, also labeled `actor-state-concept` — "Strengthens Role's centrality (verse 35, own duty vs another's): Duty=f(Role,Context,NormativeFramework) rather than Duty=f(Actor) — a person has duties in a role under a normative context, not directly. Models Actor--holds-->Role--activates-->Duty--constrained by-->Norm as a value-object chain, giving Actor ≠ Role ≠ Duty. Warns against generalizing sva-dharma (embedded in varṇa/āśrama) carelessly into 'every software actor has a role and therefore a duty' — the valid abstraction is narrower: when a normative system assigns responsibilities by role, role-context must participate in determining applicable duties." (anchor: "Actor \neq Role \neq Duty")
- [S0808] types=[CONCEPT, EXAMPLE] scope=THEORY-LEVEL — label_confidence UNCERTAIN — "Verse 21's exemplary-leader principle is mapped to a governance mechanism Behavior->Example->Social Standard, analogized to software-architecture practices (reference implementation, architectural exemplar, approved pattern, golden path, model implementation) with direct relevance to governance architecture. Verse 26 supports Guidance ≠ InformationDump and Guidance=f(Understanding,Readiness,Context), but 'LearningReady' is now explicitly demoted to a derived concept rather than a new fundamental state dimension." (anchor: "Behavior \rightarrow Example \rightarrow Social Standard") — lineage claim: SOURCE-CLAIMED-RETRACTION of S0807's proposal of LearningReadiness/LearningReady as a new state variable.
- [S0808] types=[DISTINCTION, GOVERNANCE] scope=THEORY-LEVEL — label_confidence UNCERTAIN — "Distinguishes four semantic objects that must not collapse: Norm ('Actor in Role R should perform D'), State ('Actor currently has Role R'), Observation ('Actor performed D'), Evaluation ('Action conforms to applicable duty') — classic DDD territory. Proposes avoiding one giant KnowledgeOS state Σ=(K,U,N,D,A,...) in favor of bounded semantic ownership across Knowledge/Understanding/Normative-Governance/Decision/Action/Observation contexts (each with its own owned concepts), with relationships crossing boundaries explicitly; maps Chapter 3's own sections onto these contexts (Arjuna's opening problem -> Understanding/Decision; prescribed duty -> Normative/Role; yajña cycle -> Action/Dependency/Outcome; exemplary leadership -> Governance/Social propagation; desire -> Agent condition/Cognition)." (anchor: "Norm: \quad "Actor\ in\ Role\ R\ should\ perform\ D"")
- [S0808] types=[PRINCIPLE, DISTINCTION] scope=OBJECT — label_confidence UNCERTAIN — "Verses 17-19 (the self-realized exception, then return to attachment-free action) yield NormApplicability=f(ActorCondition,Role,Context) — the applicability of a normative rule may depend on the actor's state/qualification — extracted as a general rule without encoding the theological exception directly. Verse 35 additionally yields Authority ≠ Qualification (an instruction may be authoritative while the actor may or may not be qualified to execute it), illustrated by a release-governance example (Rule/Authority/Actor/Qualification). Proposes CanAct=f(Role,Authority,Qualification,Context) distinct from ShouldAct and WillAct: Can ≠ Should ≠ Will, giving Actionability=(CanAct,ShouldAct,ReadyToAct), judged much stronger than the earlier 'DecisionReady'." (anchor: "ApplicableRules depend on State")
- [S0836] types=[CONCEPT, EXTENSION] scope=OBJECT — label_confidence UNCERTAIN, `unknown_candidate.candidate_of`=[`normative-state-concept`] — "KnowledgeOS needs to recognize statement modality: 'the constitution says all production changes require approval' (normative) is not the same epistemic object as 'production change X occurred' (observational/descriptive), giving a nine-value StatementType taxonomy. Source-type-specific pipelines: Constitution->Rules->Constraints->Validation; ADR->Decision->Rationale->Architectural constraint; Textbook->Domain propositions->Evidence/provenance->Candidate knowledge; Human instruction->Intent/instruction->Purpose->Task/desired state." (anchor: "StatementType \in \{ Descriptive, Normative, Directive, Definitional, Historical, Predictive, Hypothetical, Decision, Configuration \}")
- [S0844] types=[ARGUMENT, CORRECTION] scope=OBJECT — label_confidence UNCERTAIN, `unknown_candidate.candidate_of`=[`action-formal-model`], also labeled `action-formal-model` — "Test Case 9 (a constitutional rule 'all production software must have an approved ADR') shows the pipeline must produce a NormativeProposition distinct in semantic type from a FactualProposition, exposing that a single undifferentiated Proposition model cannot serve both. Test Case 10 (a human instruction 'upgrade Nexus to 3.70') shows an instruction must NOT become Assertion:Nexus.version=3.70 directly — it produces Instruction:Upgrade(Nexus,3.70), which may later produce an Action, which may produce a new Observation, which may then produce an Assertion. This establishes two fundamentally different flows: Epistemic acquisition (Input->Observation->Interpretation->Assertion) and Action/effect (Instruction->Action->Observation->Knowledge) — flagged as important for later defining Sārathi." (anchor: "Instruction \rightarrow Action \rightarrow Observation \rightarrow Knowledge")

## Notes for P3

- This is my own observation: this label's own rows carry a lineage claim of kind SOURCE-CLAIMED-RETRACTION, yet the mechanical CONTESTED flag did not fire — worth a manual look per the known false-negative limitation of that heuristic.
- This is my own observation: 10 of this label's 11 rows carry `label_confidence: UNCERTAIN` — the corpus itself is still negotiating whether these rows belong under this working label, so this looks like a reconciliation priority for P3.

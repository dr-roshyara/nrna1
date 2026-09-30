# multi-agent-trust-conflict-consensus-algebra

**Scope(s):** OBJECT · **Row count:** 51 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Competence(a,q)`; `ConflictType (9 values)`; `ResolveConflict(E1,E2,A,C)->Resolution`; `Trust(a,d,c,t)`
**Aliases:** Multi-Agent Knowledge, Conflict Resolution, Consensus, Trust and Distributed Epistemics
**Candidate group membership (NOT an identity claim):**
- G0168: `authority-trust-source-reliability-knowledge-commitment` · `multi-agent-trust-conflict-consensus-algebra` — explicit agent-stated uncertainty ("POSSIBLY relates to", batch B0022): this label's source (S0913, Step 25U) is explicitly described as extending "B0021's authority-trust-source-reliability-knowledge-commitment (S0876/Step 19) with a formal conflict taxonomy and resolution pipeline." Relationship not yet decided (P3) — the source's own language ("extends") suggests a build-on relationship, but no identity is asserted.

## Sources (how this label entered the ledger)
- PROPOSAL, batch B0022, scope OBJECT: "S0913's Step 25U: the fully worked multi-agent trust/conflict/consensus model (contextual trust, competence tables, evidence-clustered consensus, ByzantineConsensus distinction); extends B0021's authority-trust-source-reliability-knowledge-commitment (S0876/Step 19) with a formal conflict taxonomy and resolution pipeline." — `relation_to_existing`: "POSSIBLY:authority-trust-source-reliability-knowledge-commitment."

No `single_candidate_flags` recorded.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0913 §"A={Human,LLM,System,Sensor,Database,Agent,ExternalAuthority} ... What should KnowledgeOS do when credible sources disagree? ... not pick the source with the highest confidence"]
- CANDIDATE-CONCEPTUAL-BIRTH: [S0913 §"Agent A,B,C,D -> Evidence -> Assessment -> Knowledge -> Conflict? {No->Accept, Yes->Zero->Lord->New information} ... a very strong architecture"]
- CANDIDATE-FORMAL-BIRTH: [S0913, same anchor as lexical]
- CANDIDATE-OPERATIONAL-BIRTH: [S0913 §"Test A three agents one source -> IndependentClusters=1 not 3 PASS. Test B three independent measurements -> IndependentClusters=3 if justified PASS. Test C same claim different contexts -> NoConflict PASS. Test D same claim same time contradictory -> Conflict PASS. Test E trusted but unauthorized -"]
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0913. Candidate lifecycle: DORMANT.
Evidence: `lifecycle_evidence` is entirely empty. All 51 rows trace to a single document, S0913 ("Step 25U"). DORMANT reflects no further activity after this document in captured rows, not a confirmed retirement — the document's own self-verdict (see Theme 9 below) is PASS, i.e., a completed unit of work that explicitly transitions into the next step (Step 25V).

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S0913 (x5) |
| informal_meaning | PRESENT | S0913 (x11) |
| formal_definition | PRESENT | S0913 (x8) |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S0913 (x2) |
| dependencies | PRESENT | S0913 (x4) |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0913 (x17) |
| examples | PRESENT | S0913 (x24) |
| warnings | PRESENT | S0913 (x4) |
| experiments | PRESENT | S0913 |
| open_questions | PRESENT | S0913 (x2) |

## Rationale
`rationale_evidence` holds 5 entries (`rationale_truncated_count` 0), all from S0913: (1) the Conflict(A,E1,E2) definition requires an 8-question diagnostic checklist before resolution is attempted; (2) the 9-stage conflict-resolution pipeline is "much safer than weighted voting"; (3) EpistemicConflict is explicitly distinguished from Byzantine consensus — KnowledgeOS asks which proposition is epistemically justified, not merely whether nodes can agree ("a truthful minority with direct evidence may be more valuable than a majority repeating weak evidence"); (4) a worked human-disagreement example shows apparent 50/50 disagreement may really be ModelDifference, not FactDifference; (5) the layer's computational primitives are normal-PC computable, with entity resolution/dependency analysis/large graph inference as the scaling frontier, not a theoretical barrier. Together these explain the document's central thesis: naive vote-counting or trust-score-comparison approaches to multi-agent disagreement are unsound, and the correct replacement separates evidence quality, independence, trust, authority, and conflict type into distinct dimensions before any resolution is attempted.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)
All 51 rows share source_id S0913 (`docs/knowledgeos/brainstorming/phase_measure_theory/20260828-100905_step-025u-multi-agent-knowledge-conflict-resolution-consensus-trust-and-distributed-epistemics.md`, Step 25U). Given the volume, rows are grouped into 9 themes by content; each theme lists representative anchors. 51 rows condensed into these themes; full text in 03-CONTRIBUTIONS.jsonl.

**Theme 1 — Agent typing, Agent≠Evidence, and contextual Trust (5 rows).** Extends the model to a seven-type set of independent epistemic producers (Human/LLM/System/Sensor/Database/Agent/ExternalAuthority), framing the central question of what to do when credible sources disagree, rejecting "pick the source with the highest confidence"; states Agent≠Evidence (an agent produces evidence, ProducedBy(E)=Agent); requires every actor to carry stable AgentID/AgentType; rejects bare scalar Trust(Agent)=0.9 in favor of contextual Trust(a,d,c,t); distinguishes Expertise≠Authority, Trust≠Authority (a highly expert engineer may lack approval authority).

**Theme 2 — Reliability, independence, and evidence-lineage graphs (6 rows).** Distinguishes Reliability(Source) from Trust(Agent) (a trustworthy source can still be unreliable for a specific measurement); states AgentIndependence≠InformationIndependence, explicitly extending the double-counting principle from Step 25N (`evidence-aggregation-heterogeneous-algebra`); worked example (two agents reading one vendor document count as ~1 independent unit, not 2); defines a dependency graph G_D=(V,E_D) for detecting non-independence among common-source evidence.

**Theme 3 — Conflict diagnosis and the identity/temporal/context disambiguation sequence (5 rows).** Defines Conflict(A,E1,E2) with an 8-question diagnostic checklist (see Rationale); worked examples resolving apparent conflicts via temporal disambiguation (different times, Conflict=False) and context/entity disambiguation (production vs development, Conflict=False); a worked genuine-conflict example (same entity, same time, contradictory values) that must be preserved, not resolved away.

**Theme 4 — Conflict as first-class state and the ResolveConflict outcome space (4 rows).** Warns against silently choosing-and-deleting one side of a conflict (destroys epistemic history) — Conflict must be a FirstClassKnowledgeState; defines ResolveConflict(E1,E2,A,C)->Resolution with 5 outcomes (Confirmed(E1), Confirmed(E2), BothValid, BothInvalid, Unresolved); worked BothValid example (two sensors at different locations both correct — "a reported Conflict may actually indicate ContextMissing"); presents the 9-stage conflict-resolution pipeline (see Rationale).

**Theme 5 — Trust-score fallacies and authority-based resolution (3 rows).** Warns against naive trust-score comparison (TrustScore≠TruthSelector — a higher-trust source may merely repeat a lower-trust source's own contradictory evidence); worked authority-resolved example (an official decision record supersedes an engineer's claim for governance status, while the claim remains historical evidence); worked example distinguishing measurement-method suitability from universal source-type ranking.

**Theme 6 — Competence, reputation, and trust-as-evidence (6 rows).** Defines Competence(a,q) per question type via a worked six-row table, "much more meaningful than a global trust score"; defines reputation via historical Calibration/Accuracy/ErrorRate feeding Reliability(a,q,t), required to be evidence-based and versioned; warns of self-reinforcing FeedbackBias (an agent's own evidence inflating its own apparent reliability without independent validation); worked structured trust/reliability-provenance record (making trust itself an evidence-carrying object); defines TrustAssessment as a derived knowledge object with 6 required fields, "prevents trust from becoming magic metadata."

**Theme 7 — Consensus, quorum, and Byzantine-consensus distinction (6 rows).** Distinguishes 3 non-equivalent consensus definitions (MajorityAgreement, WeightedAgreement, IndependentEvidenceConvergence), preferring the latter over simple voting; contrasts weak corroboration (3 agents citing 1 source) with strong corroboration (3 genuinely independent paths); states consensus should be evaluated over independent evidence clusters, not raw agent counts; defines a quorum requirement (k independent confirmations) as domain policy, not universal law; explicitly distinguishes EpistemicConflict from Byzantine consensus (see Rationale).

**Theme 8 — Model disagreement, conflict typology, and the Zero/Lord escape path (8 rows).** Worked model-disagreement example (two models producing different risk numbers from identical evidence — "the conflict is not in the observations, it is in Models"); defines a 9-value ConflictType taxonomy (Identity/Temporal/Context/Measurement/Semantic/Evidence/Model/Policy/Authority); defines a 6-field Resolution object with a worked example retaining ResidualUncertainty; states Unresolved is a valid terminal state, not failure, feeding Zero=UnresolvedConflict; worked example of Lord resolving an unresolved conflict via targeted evidence acquisition (Conflict->Zero->Lord->Evidence->Resolution); presents the full multi-agent epistemic loop diagram (Agent A,B,C,D->Evidence->Assessment->Knowledge->Conflict?->Accept/Zero->Lord).

**Theme 9 — Actor model, capability/authority separation, trust invariants, falsification tests, and self-verdict (14 rows).** Rejects a generic AgentManager in favor of a bounded-context-neutral EpistemicActor plus domain-specific TrustPolicy/ConflictPolicy/AuthorityPolicy; defines a 5-field Actor object with explicit (not type-inferred) Capabilities; restates capability-vs-authority separation (CanExecute≠AuthorizedToExecute); defines 5 distinct epistemic permissions (CanObserve/CanAssert/CanDerive/CanApprove/CanExecute); states two invariants — "Trust never substitutes for authority" and "Trust modifies epistemic weight; it does not create truth"; rejects naive summed-trust probability P(H)=sum(Trust_i); worked example forbidding naive multiplicative LR combination when both ratios derive from the same document ("directly connects 25U to 25N"); states the key result "corroboration should be measured in independent information, not number of agents"; runs 8 falsification tests A-H (all PASS, full `experiment` record) covering shared-source vs independent-measurement clustering, apparent vs genuine conflict, unauthorized-trusted evidence, unsupported high-confidence LLM claims, and conflict-resolution updating; lists computational primitives as normal-PC computable with entity-resolution/dependency-analysis/graph-inference as the scaling frontier; gives the Step 25U self-verdict (PASS, six boxed conclusions: AgentCount≠EvidenceCount, Consensus≠MajorityVote, Trust≠Authority, Trust≠Truth, Conflict≠Failure, IndependentEvidenceConvergence>RawAgentAgreement); presents the consolidated 12-stage architecture loop with 6 cross-cutting dimensions (Time/Context/Provenance/Authority/Trust/Uncertainty); closes by posing the semantic-interoperability question (the same word "Approved" carrying different meanings across contexts) transitioning to Step 25V (also labeled implicitly via `lineage_claims`: SOURCE-CLAIMED-CONTINUATION of Step 25V/S0914).

## Notes for P3
This is a single-document (S0913), internally very well-organized and self-consistent formalization (Step 25U of the same step-numbered theory series as `evidence-aggregation-heterogeneous-algebra`'s Step 25N) — no internal contradiction detected; the document runs its own falsification tests and issues a self-verdict (PASS). Its repeatedly-restated central theses — "AgentCount≠EvidenceCount," "Trust≠Authority," "Trust≠Truth," "Conflict≠Failure," and "IndependentEvidenceConvergence>RawAgentAgreement" — are the load-bearing claims P3 should preserve above the individual formal definitions. This label explicitly builds on and cross-references `evidence-aggregation-heterogeneous-algebra` (Step 25N: the double-counting principle, LR combination) at least twice (Theme 2, Theme 9) — P3 should treat these two labels as sequential, tightly-coupled steps in the same theory, though per this batch's rule no identity is asserted between them. The G0168 relationship to `authority-trust-source-reliability-knowledge-commitment` deserves priority attention: the source's own language explicitly frames this label as extending that one with a formal conflict taxonomy and resolution pipeline. One open thread remains genuinely unresolved per the document's own admission: the semantic-interoperability question (identical words carrying different meanings across bounded contexts) explicitly deferred to "Step 25V" (S0914) — not part of this label's captured rows. `files_touching` includes S0921 in addition to S0913, which does not appear in `family.rows` — unexplained by data available to this label.

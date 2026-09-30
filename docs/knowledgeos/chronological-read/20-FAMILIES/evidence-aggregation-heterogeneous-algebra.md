# evidence-aggregation-heterogeneous-algebra

**Scope(s):** OBJECT · **Row count:** 47 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `A(E1,...,En;A,M)->Assessment(A)`; `LR(E)=P(E|H)/P(E|not H)`; `Odds(H|E)=Odds(H)*LR(E)`; `SupportState in {Strong,Moderate,Weak,Conflicted,Insufficient}`
**Aliases:** Evidence Aggregation Algebra
**Candidate group membership (NOT an identity claim):**
- G0163: `evidence-aggregation-algebra` · `evidence-aggregation-heterogeneous-algebra` — explicit agent-stated uncertainty ("POSSIBLY relates to", batch B0022): this label's source (S0907, Step 25N) is explicitly described as extending "B0021's evidence-aggregation-axioms (S0859) and S0892's evidence-aggregation-algebra (B0021) with a concrete operator, DDD service mapping, and eight falsification tests." Relationship not yet decided (P3) — the source's own language ("extends") suggests a build-on relationship, but no identity is asserted.
- G1046: `evidence-aggregation-algebra` · `evidence-aggregation-heterogeneous-algebra` — working_label token overlap Jaccard=0.75 (shared tokens: "aggregation", "algebra", "evidence"). Relationship not yet decided (P3), consistent with G0163's note above.

## Sources (how this label entered the ledger)
- PROPOSAL, batch B0022, scope OBJECT: "S0907's Step 25N: the fully worked statistical/qualitative evidence-aggregation model (likelihood ratios, evidence clusters, authority-vs-reliability separation, no-universal-aggregation-function conclusion); extends B0021's evidence-aggregation-axioms (S0859) and S0892's evidence-aggregation-algebra (B0021) with a concrete operator, DDD service mapping, and eight falsification tests." — `relation_to_existing`: "POSSIBLY:evidence-aggregation-algebra."

No `single_candidate_flags` recorded.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0907 §"E=(Content,Source,Provenance,Method,Time,Context,Reliability,Independence,Authority,Uncertainty) ... 0.87+0.76+0.91 has no generally valid statistical interpretation"]
- CANDIDATE-CONCEPTUAL-BIRTH: [S0907 §"EvidenceAssessmentService inside an appropriate bounded context ... should not be embedded into the generic Evidence Entity"]
- CANDIDATE-FORMAL-BIRTH: [S0907, same anchor as lexical]
- CANDIDATE-OPERATIONAL-BIRTH: [S0907 §"Test A duplicate: InformationGain=0. Test B derived duplicate: IndependentSupport(E2)=0 unless genuinely new information. Test C independent corroboration: Support(H) increases. Test D contradictory: Conflict(H). Test E irrelevant: Support=0/Irrelevant. Test F stale: CurrentApplicability=False, hist"]
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0907. Candidate lifecycle: DORMANT.
Evidence: `lifecycle_evidence` is entirely empty. All 47 rows trace to a single document, S0907 ("Step 25N"). DORMANT reflects no further activity after this document in captured rows, not a confirmed retirement — the document's own self-verdict (row 44 below) is PASS, i.e., a completed, not abandoned, unit of work.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S0907 (x5) |
| informal_meaning | PRESENT | S0907 (x17) |
| formal_definition | PRESENT | S0907 (x7) |
| type_signature | PRESENT | S0907 |
| invariants | PRESENT | S0907 |
| dependencies | PRESENT | S0907 (x5) |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0907 (x16) |
| examples | PRESENT | S0907 (x18) |
| warnings | PRESENT | S0907 (x6) |
| experiments | PRESENT | S0907 |
| open_questions | PRESENT | S0907 (x2) |

## Rationale
`rationale_evidence` holds 5 entries (`rationale_truncated_count` 0), all from S0907: (1) evidential Strength(E,H) is best captured by a likelihood ratio rather than a subjective score; (2) the SupportState qualitative alternative exists for the normal enterprise case lacking a valid probability model; (3) the ten-step Aggregate pipeline is "much safer than simply averaging scores"; (4) sensitivity/robustness analysis (varying the prior across a plausible range) distinguishes a robust conclusion from a fragile one; (5) a worked counterexample (high-authority/low-reliability vs low-authority/high-reliability) demonstrates why no universal aggregation function can exist — "EvidenceAggregation is domain/model dependent." Together these explain the document's central thesis: naive scalar evidence combination (summing or averaging confidence scores) is mathematically invalid, and the correct replacement is a model-dependent, multi-dimensional aggregation operator that keeps statistical rigor (likelihood ratios, conditional independence, Bayesian updating) separate from organizational concerns (authority, governance) and separate again from a qualitative fallback regime for when no valid probability model exists.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)
All 47 rows share source_id S0907 (`docs/knowledgeos/brainstorming/phase_measure_theory/20260828-094210_step-025n-evidence-aggregation-algebra.md`, Step 25N). Given the volume, rows are grouped into 9 themes by content; each theme lists representative anchors. 47 rows condensed into these themes; full text in 03-CONTRIBUTIONS.jsonl.

**Theme 1 — Rejecting scalar evidence and defining the Evidence tuple (2 rows).** Rejects summing bare confidence scalars (0.87+0.76+0.91 has no valid statistical interpretation); proposes a ten-field Evidence tuple E=(Content,Source,Provenance,Method,Time,Context,Reliability,Independence,Authority,Uncertainty).

**Theme 2 — Six separable evidential concepts: Support, Reliability, Authority, Probability, Confidence, Independence (6 rows).** States the six concepts answer different questions and must not be conflated; defines Support(E,A) in {Supports,Contradicts,Neutral,Undetermined}; Reliability(E,A,C) as context-dependent trustworthiness (production-API vs human-memory example); Authority as organizational/legal standing distinct from Reliability (worked example: an engineer's technically-reliable approval statement has Authority=False since only the Architecture Board can authorize); Probability P(H|E) distinct from Reliability/Authority/Confidence; Confidence flagged as dangerously overloaded, requiring Confidence=(value,model,interpretation) if used at all.

**Theme 3 — Independence, double-counting, and evidence lineage (6 rows).** States Independence as the most important statistical dimension (co-supporting evidence cannot be assumed independent without examining causal/provenance relationships); worked double-counting example (human+LLM summaries both derived from one vendor document, "EvidenceCount ≠ InformationCount," called "a fundamental principle"); proposes an evidence lineage graph G_E=(V,E) for double-counting detection; defines EvidenceClusters (a cluster of common-origin evidence contributes InformationUnits≈1); contrasts genuinely independent acquisition paths (API/filesystem/human inspection) against merely "different people"; generalizes to conditional independence E1⊥E2|H,C.

**Theme 4 — Likelihood ratios and Bayesian combination (5 rows).** Defines Polarity(E,H) in {Positive,Negative,Neutral,Unknown} as a deterministic pre-probabilistic layer; argues Strength(E,H) is best captured by a likelihood ratio, not a subjective score; formally defines LR(E)=P(E|H)/P(E|¬H); defines combination of conditionally-independent evidence via multiplied likelihood ratios (LR(E1,...,En)=prod LR(Ei), "a mathematically valid evidence-combination mechanism"); states the strict invariant that this product must never be applied without provenance/model justification for the independence assumption, or "severe overconfidence" results; defines Bayesian odds updating Odds(H|E)=Odds(H)*LR(E) requiring a valid prior/likelihoods/dependency model.

**Theme 5 — Qualitative aggregation regime and worked examples (5 rows).** Defines a qualitative SupportState (Strong/Moderate/Weak/Conflicted/Insufficient) alternative for the normal enterprise case lacking a valid probability model; defines two legitimate, non-mixable aggregation regimes (Statistical, Qualitative) — QualitativeSupport≠Probability; worked example showing an Architecture Board decision's authority can dominate a more technically-reliable engineer's statement ("EvidenceSupport does not imply GovernanceAuthority"); presents the ten-step Aggregate(E1..En,A) pipeline; gives worked structured qualitative and statistical aggregate-assessment output examples (assertion/support/evidence/clusters/authority/model fields; and prior/evidence/posterior/independence-model/sensitivity fields respectively).

**Theme 6 — Robustness, conflict handling, and history preservation (5 rows).** Introduces sensitivity/robustness analysis (varying the prior across a plausible range to test conclusion stability); argues decision-support systems (Sarathi) should see Robustness(H), not merely a point estimate ("DecisionQuality depends on robustness, not only point estimate"); classifies conflicting-evidence outcomes into Strong/Conflicted/Unresolved, explicitly rejecting naive polarity cancellation (+1-1=0); worked example distinguishing epistemic-model preference (telemetry over a manual note) from metaphysical truth-claims; states authority-resolved conflicts must preserve ConflictHistory rather than deleting dominated evidence.

**Theme 7 — The no-universal-aggregation-function conclusion and DDD mapping (4 rows).** Defines the abstract aggregation operator A(E1,...,En;A,M)->Assessment(A), explicitly model-dependent — "there is no universal evidence aggregation function... an important mathematical conclusion" (with a full `type_signature`: domain EvidenceSet x Assertion x AggregationModel, codomain Assessment, arity 3, total/deterministic UNSTATED); worked counterexample (high-authority/low-reliability vs low-authority/high-reliability source has no universal winner); maps evidence aggregation onto DDD as a dedicated EvidenceAssessmentService domain service, kept separate from the generic Evidence entity; worked example showing evidence has no universal support value independent of the assertion (Support(E,A1)≠Support(E,A2)).

**Theme 8 — Relevance, applicability, and the consolidated pipeline (3 rows).** Defines Relevance(E,A,C) in {Relevant,Irrelevant,Unknown} as a pre-aggregation gate; distinguishes Relevance from Applicability, including a required temporal dimension Applicable(E,A,t); presents the final 13-stage consolidated pipeline diagram (Evidence->Identity->Provenance->Dependency Analysis->Relevance->Applicability->Polarity->Reliability->Authority->Statistical/Qualitative Model->Conflict Analysis->Aggregate Assessment->Knowledge State) — "a robust architecture."

**Theme 9 — Falsification tests, final principles, and self-verdict (11 rows).** Runs 8 falsification tests A-H (all PASS: exact duplicate, derived duplicate, independent corroboration, contradiction, irrelevance, staleness, authority mismatch, valid-model posterior), with a full `experiment` record; formally rejects KnowledgeStrength=NumberOfEvidenceItems in favor of KnowledgeAssessment as a function of Evidence/Provenance/Dependency/Relevance/Applicability/Reliability/Authority/Model; identifies the genuinely unresolved open question of unknown dependency structure (5 candidate strategies, no universal answer); states the Conservative Evidence Principle (never claim more evidential strength than the dependency model justifies); argues aggregation can legitimately return "unresolved" with a recommended next action, feeding Zero's NeedIndependentEvidence and Lord's action selection; summarizes the closed epistemic feedback loop Evidence->Assessment->Knowledge, with Assessment->Zero->Lord->NewObservation->Evidence when insufficient; gives the Step 25N self-verdict (PASS, with an explicit proven/computable list vs. the one explicitly-not-universally-computable item: Universal evidence weight); states the major architectural result "evidence has no intrinsic universal weight; its epistemic effect is relational, Effect(E,A|C,M)"; closes by posing the truth/validity question (naming 7 concepts: WorldTruth, ObservedTruth, LogicalTruth, SupportedAssertion, ProbableAssertion, GovernanceTruth, Unknown) transitioning into Step 25O, noting this is "the same content already processed earlier in this batch as S0906" (also labeled `truth-validity-belief-epistemic-status-algebra`; `lineage_claims`: SOURCE-CLAIMED-CONTINUATION of Step 25O/S0906).

## Notes for P3
This is a single-document (S0907), internally very well-organized and self-consistent formalization (Step 25N of a step-numbered theory series) — no internal contradiction detected; the document even runs its own falsification tests and issues a self-verdict (PASS). Its central, repeatedly-restated thesis — "evidence has no intrinsic universal weight... its epistemic effect is relational" and "there is no universal evidence aggregation function" — is the load-bearing claim P3 should preserve above the individual formal definitions. The G0163/G1046 relationship to `evidence-aggregation-algebra` deserves priority attention: the source's own language explicitly frames this label as an extension of that one (with a concrete operator, DDD mapping, and falsification tests added), which is a stronger signal than a typical "possibly relates" flag. Two open threads remain genuinely unresolved per the document's own admission: (1) how to handle evidence combination under unknown dependency structure (Theme 9), and (2) the transition into "Step 25O" truth/validity work, which the document itself flags as already processed elsewhere as S0906 — P3 should check that label (`truth-validity-belief-epistemic-status-algebra`) for the continuation. `files_touching` includes S0913 and S0920 in addition to S0907, neither of which appears in `family.rows` — unexplained by data available to this label.

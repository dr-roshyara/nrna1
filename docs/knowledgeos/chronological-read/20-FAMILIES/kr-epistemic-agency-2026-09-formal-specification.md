# kr-epistemic-agency-2026-09-formal-specification

**Scope(s):** OBJECT · **Row count:** 47 · **Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** EA1..EA11, EU(a|K_t,Q_t,C_t,S_t,R_t), KR-EPISTEMIC-AGENCY-2026-09, NextEpistemicAct · **Aliases:** From Knowledge State to Next Epistemic Act
**Candidate group membership (NOT an identity claim):**
- **G0851** [`kr-epistemic-agency-2026-09-formal-specification` · `next-epistemic-act-function-and-gap-classification`] — labels share the notation 'KR-EPISTEMIC-AGENCY-2026-09'
- **G1887** [`kr-epistemic-agency-2026-09-formal-specification` · `zoom-in-as-inquiry-directed-dimension-discovery`] — labels co-occur in the same contribution's labels[] 2 separate times across the corpus
- **G1889** [`action-rationale-and-why-act-factfinding` · `kr-epistemic-agency-2026-09-formal-specification`] — labels co-occur in the same contribution's labels[] 5 separate times across the corpus
- **G1892** [`epistemic-value-anthology-lens-2026-09` · `kr-epistemic-agency-2026-09-formal-specification`] — labels co-occur in the same contribution's labels[] 3 separate times across the corpus
- **G1896** [`kr-epistemic-agency-2026-09-formal-specification` · `minica-del-questions-inquiry-lens-2026-09`] — labels co-occur in the same contribution's labels[] 3 separate times across the corpus


## Sources (how this label entered the ledger)
- **OBJECT-INDEX**, batch `B0068`, scope `OBJECT`: The fully worked-out KR-EPISTEMIC-AGENCY-2026-09 research artifact (status [PROP][OPEN], Theory v1.2 frozen, kernel untouched): a metro-encounter case study formalized as a four-layer execution loop (front-end interaction, gap detection/classification, epistemic agency selection, determination/output), hypotheses EA1-EA7 plus later EA8-EA11, and an expected-utility sub-specification EU(a|K_t,Q_t,C_t,S_t,R_t). Extends the candidate 'next-epistemic-act-function-and-gap-classification' object (S2833) into a full formal specification with a worked six-step human trace and a senior-review critique correcting K|=Q notation, gap taxonomy exhaustiveness, EA7's universality, and the Lord/Sarathi naming.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2835] §"NextEpistemicAct(K_t,Q_t,Delta_t,R_t,Pi) ... a_t in {Clarify, Search, Observe, ConsultResource, AssessEvidence, Determine, Defer, Act}. ... EA1..EA7"
- CANDIDATE-CONCEPTUAL-BIRTH: [S2835] §"Q_0 -> Clarification -> Q_1 -> Clarification -> Q_2 ... until WellFormed(Q_n,C) and only then Gap(Q_n,K_t). ... Inquiry Formation ... distinct from Inquiry Resolution."
- CANDIDATE-FORMAL-BIRTH: [S2835] §"NextEpistemicAct(K_t,Q_t,Delta_t,R_t,Pi) ... a_t in {Clarify, Search, Observe, ConsultResource, AssessEvidence, Determine, Defer, Act}. ... EA1..EA7"
- CANDIDATE-OPERATIONAL-BIRTH: [S2835] §"Observation equivalence not-implies Intent equivalence ... Good action selection not-implies Correct knowledge ... Resource availability not-implies Resource reliability."
- CANDIDATE-GOVERNANCE-BIRTH: [S2835] §"a metaphor can expose a structure without proving that the metaphor names an architectural component. ... [PROP] Lord/Sarathi is an interpretive naming lens for the two functions; not a canonical architectural name."

## Lifecycle
last_seen: S2860. Candidate lifecycle: ACTIVE.
Evidence: No retraction/supersession/contradiction evidence recorded. The ACTIVE classification is a heuristic based on how recently (by source_id) this label was last used (S2860), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S2835, S2835, S2836, S2837, S2857 |
| type_signature | PRESENT | S2835, S2836, S2837, S2857 |
| invariants | PRESENT | S2835, S2835, S2835, S2835, S2836, S2836, S2836, S2836, S2837, S2837, S2837, S2837, S2837, S2837, S2839, S2842, S2842, S2846, S2847, S2853, S2856, S2859, S2860 |
| dependencies | PRESENT | S2837, S2842, S2842, S2843, S2845, S2846, S2847, S2849, S2853, S2856, S2857, S2859, S2860 |
| assumptions | PRESENT | S2836, S2839 |
| semantics | PRESENT | S2835, S2835, S2836, S2836, S2837, S2837, S2837, S2837, S2837, S2838, S2839, S2842, S2842, S2845, S2846, S2847, S2853, S2856, S2860 |
| examples | PRESENT | S2836, S2860 |
| warnings | PRESENT | S2835, S2835, S2836, S2837, S2837, S2846 |
| experiments | PRESENT | S2835, S2836, S2837 |
| open_questions | PRESENT | S2835, S2849 |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
| Statement | Stated | Source ID | Anchor |
|---|---|---|---|
| Utility components combine additively | USED-UNSTATED | S2836 | "EU(a) = E[U_gap(a)] - C_resource(a) - Omega_risk(a) + U_task(a)" |
| Adeq is measurable, candidate dimensions have comparable expected value, costs are scalarizable, argmax exists | USED-UNSTATED | S2839 | "But it assumes: Adeq is measurable, candidate dimensions have comparable expected value, costs are scalarizable, an argmax exists." |

## All rows (source_id order)

This label has 47 rows drawn from 16 distinct source documents in the 2026-09-07
"metro encounter" / epistemic-agency thread. Rows are grouped below into 9 content
themes; full verbatim text for every row remains in `03-CONTRIBUTIONS.jsonl`.

### Theme 1 — Core v1 operational-loop specification and corrections (S2835; 12 rows condensed)
Specifies a four-layer Epistemic Agency Execution Loop (front-end interaction; gap
detection/classification via K_t|=Q; epistemic agency selection via
a*=argmax[ExpectedGapReduction/(Cost+Risk)]; downstream execution). Corrects the
overloaded logical-entailment notation K_t|=Q to the existing Adequacy/Determination
vocabulary. Rejects freezing the four-way gap taxonomy {Semantic,Epistemic,Resource,
Policy} as exhaustive, recommending a typed Delta_Q(K_t) collection with a
ClassifyGap:Delta_Q->G map instead. Separates four conflated concerns (what is missing,
what can be done, what should be done, what is allowed) into an explicit chain
Delta_t -> A_t^possible -> A_t^feasible -> a_t. Corrects the argmax[E[DeltaRed]/Cost]
decision rule as insufficient (pure info-gain/cost could select an unsafe action, or fail
to recognize that not acting is sometimes optimal). Corrects a simple resource-Filter
model to a richer per-resource state Resource_t(r)=(Availability,Quality,Cost,Risk,
Latency,Authority,...). Flags that a worked human trace contains invented (unobserved)
details treated as facts, violating the model's own discipline. Extends the pipeline with
a multi-cycle Inquiry Formation stage preceding gap analysis, distinguished from Inquiry
Resolution. Corrects a universal Determine-before-Act rule by distinguishing three action
classes (epistemic actions that may precede determination; operational actions requiring
it; and a third class). Demotes "Lord/Sarathi" naming to
CandidateGenerator/ActionSelector, tagging the narrative naming as merely [PROP]
interpretive framing. Reframes the kernel question from "should Epistemic Agency become a
kernel operator" to "does epistemic action selection expose a capability not already
semantically derivable." Proposes a controlled factorial benchmark varying resources and
then epistemic standards/risk/inquiry to test whether action selection changes as
expected.

### Theme 2 — Expected-utility formalization for action selection (S2836; 9 rows condensed)
Corrects an additive four-term utility decomposition EU(a)=E[U_gap(a)]-C_resource(a)-
Omega_risk(a)+U_task(a) to a general, unestablished-form specification EU=F(U_gap,C,
Omega,U_task). Rejects assuming a generic norm over the gap space, since typed gaps
(missing value, missing dimension, contradiction, insufficient evidence, unobservable
quantity, underdetermination, ...) are not commensurable by a single scalar. Argues raw
gap-cardinality reduction does not imply epistemic improvement, via a worked example (3
hypotheses reduced to 1 surviving hypothesis that is false). Corrects a reliability
multiplier rho(a)*E[DeltaRed] as risking double-counting reliability, recommending a
cleaner expected-utility formulation instead. Corrects treatment of safety/risk as a soft
utility penalty to a hard feasibility constraint where appropriate (Constraint !=
Preference). Corrects a latency tie-breaking rule from a "selection invariant" to a
"candidate deterministic tie-break policy." Adds a Stop/NoFurtherInvestigation candidate
action to the action set, so that once adequacy is reached, continuing investigation can
legitimately have negative utility. Proposes a counterfactual-action-quality experiment
using a candidate Regret(a)=U(a*)-U(a) measure. Reframes the kernel test as whether
NextEpistemicAct is semantically equivalent to a composition of existing capabilities
(Generate+Feasible+Evaluate+Select+Policy).

### Theme 3 — v0.2 review: Adequacy terminology, notation collisions, resource-state corrections (S2837; 11 rows condensed)
Publishes KR-EPISTEMIC-AGENCY-2026-09 v0.2, replacing K_t|=Q with contract-relative
Adeq(K_t,Q,C,EC,S,R) and a three-phase decoupled pipeline (Inquiry Formation with a branch
for underspecified questions; ...). Identifies a notation collision: R_t used both for
"risk policy" and previously for "reasoning regime," resolved by renaming risk to Risk_t.
Corrects a mislabeled "FACTORIAL EXPERIMENT" (cases A-E vary resources non-orthogonally)
to its correct design type, a resource-ablation/constrained-intervention study. Corrects
the proposed irreducibility test: observing a*=f(resources,...) across cases A-E only
shows the selected action depends on resources, not that NextEpistemicAct is derivable
from existing capabilities. Corrects the resource-state tuple's "Freshness Rate" as
conflating update frequency with actual age, recommending separate Age_t and update-rate
fields. Corrects a single scalar "trust/precision" field as conflating historical
accuracy, source reliability, precision, calibration, current validity, and relevance to
Q. Proposes the candidate invariant chain Available != Feasible != Useful != Sufficient,
illustrated by "Google Maps is available" not implying it can answer a given question.
Treats the fallback case (no feasible action; report incapability/defer) as a genuine
epistemic outcome, proposing NoFeasibleAct => NoFabricatedDetermination. Adds NoOp/Wait to
the candidate action set, since ExpectedValue(Wait) can exceed ExpectedValue(Search).
Further corrects the Determination->Action universal-loop assumption, proposing a parallel
protective-action path. States the sharpened research question: not "can KnowledgeOS
store knowledge" but "can KnowledgeOS select the next justified epistemic transition."

### Theme 4 — Architecture separation: ZoomIn vs Epistemic Agency vs Fact Finding (S2838, S2839; 2 rows condensed)
Separates four distinct research questions into a coherent architecture: ZoomIn (where
should I investigate), Epistemic Agency (what should I do next), Fact Finding (what does
the evidence establish), and a fourth distinguished question [S2838]. Corrects an earlier
dimension-selection formula (which assumed Adequacy is measurable, candidate dimensions
are comparably valued, costs are scalarizable, and an argmax exists) to an explicitly
candidate, not established, agency formulation [S2839].

### Theme 5 — Gita Chapter 3 extractions: action, decision, and utility distinctions (S2842, S2843; 4 rows condensed)
Extracts from Chapter 3's rejection of mere abstention the structural distinction
NoAction != NoDecision, with inaction itself a candidate action. Extracts from Chapter 3's
example-setting/systemic-consequence argument the candidate distinction Action Evaluation
!= Individual Utility Only, suggesting the existing EU(a) operator needs a systemic term.
Proposes the paired candidate invariants Determine(H) does not imply Select(Action) and
Select(Action) does not imply Determine(H), illustrated by three cases [S2842]. Rejects
assuming additive utility composition U(a)=U_local(a)+U_systemic(a), repeating the
already-rejected additive default from the Expected Utility work, requiring instead a
general composition function [S2843].

### Theme 6 — Ledger and governance corrections (S2845, S2846; 2 rows condensed)
Corrects the research ledger's compressed "KR-EPISTEMIC-AGENCY -> Decision ->
Authorization -> Action" arrow, which could wrongly imply Epistemic Agency owns
Authorization, to a clearer chain EpistemicAgency->Decision->... with Authorization kept
separate [S2845]. Extracts from the anthology's curiosity/attention and
pragmatic-encroachment chapters the candidate distinctions Attention != Value != Truth and
Epistemic Value != Practical Utility, warning against silently collapsing them [S2846].

### Theme 7 — Epistemic value: normativity and attention as prior stages (S2847, S2849; 2 rows condensed)
Extracts Grimm's argument that epistemic normativity is not reducible to teleological
goal-promotion, giving Epistemic Norm != Utility Function and Epistemic "Should" !=
Practical "Should" [S2847]. Extracts Brady's curiosity-as-selective-attention chapter to
propose a prior stage AttentionSelection != DimensionDiscovery != FactFinding, asking why
a dimension becomes worthy of investigation before any of the existing pipeline stages
engage [S2849].

### Theme 8 — Minica thesis extractions: source selection, protocol semantics (S2853, S2856, S2857; 3 rows condensed)
Extracts the thesis's source-selection and resource-limitation discussion to propose a
typed source set S_t^available={Human,Log,Instrument,Database,Network,Agent,Oracle,...}
and a SelectSource(Q,K,C,S,R) operator [S2853]. Extracts the thesis's procedural-
information-limitation discussion (instrument, source-availability, social, financial,
procedural constraints) into a distinction chain Possible Question != Available Question
!= Executable Investigation [S2856]. Extracts the thesis's protocol semantics (legal
sequences of inquiry actions, constraining availability at each stage) into a candidate
InquiryProtocol_t with AvailableActions_t=Protocol(K_t,Q_t,C_t) [S2857].

### Theme 9 — Consolidation review and design-pattern comparison (S2859, S2860; 2 rows condensed)
Reaffirms that the action space must not have universally mandatory members, correcting a
prior spec that incorrectly required a fixed action set unconditionally, to a candidate
A_Q=A_Q^do... formulation that is context/state-dependent [S2859]. Compares GoF's Chain of
Responsibility pattern (a request passes through potential handlers until one takes
responsibility, sender need not know the final receiver) to the metro-example
resource-selection problem, treating design patterns as an empirical catalogue rather than
a source of KnowledgeOS primitives [S2860].

## Notes for P3
- Agent observation: this label participates in 5 candidate groups (G0851, G1887, G1889, G1892, G1896); given the density of cross-links, P3 may want to prioritize this label's reconciliation.
- Agent observation: the "All rows" section above groups the 47 rows into 9 content themes by source document/sub-topic, per the P2b instruction for large labels, rather than listing all 47 individually.
- Agent observation: the label's own rows record a visible self-correction pattern typical of this batch's research style — an initial v1 spec (S2835) is progressively corrected by its own author across S2836, S2837, S2838, S2839, and further stress-tested against external reading (Gita ch.3, the Minica thesis, a Grimm/Brady epistemic-value anthology, GoF patterns) in S2842 through S2860. P3 may want to anchor lineage on this internal sequence rather than treating all 47 rows as co-equal.


# drift-detection-capability

**Scope(s):** OBJECT · **Row count:** 20 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** DriftDetection · Knowledge Validity Monitoring
**Aliases:** KNOWLEDGE_REVALIDATION_REQUIRED
**Candidate group membership (NOT an identity claim):**
- G0233: explicit agent-stated uncertainty (batch B0024) — `observability-runtime-empirical-assurance-model` POSSIBLY relates to this label; described as Step 99's continuously-evidenced correspondence model, extending "the pre-existing drift-detection-capability and architecture-health-dashboard objects" — relationship not yet decided (P3).
- G0245: explicit agent-stated uncertainty (batch B0024) — `runtime-architecture-conformance-model` POSSIBLY relates to this label; described as Step 106's runtime-layer conformance sequence, extending "architecture-conformance-engineering-model and drift-detection-capability" — relationship not yet decided (P3).
- G0247: explicit agent-stated uncertainty (batch B0024) — `architecture-drift-classification-model` POSSIBLY relates to this label; described as Step 107's six-category discrepancy classification, explicitly distinguished in its own source note from "drift-detection-capability (B0012, knowledge-item validity monitoring)" as covering causal explanation/resolution rather than drift types or knowledge staleness — relationship not yet decided (P3).
- G1479: co-occurrence signal — this label and `observability-runtime-empirical-assurance-model` co-occur in the same contribution's labels[] 3 separate times across the corpus.
- G1483: co-occurrence signal — this label and `runtime-architecture-conformance-model` co-occur in the same contribution's labels[] 8 separate times across the corpus.

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0012, scope OBJECT: "Proposed P0 capability tracking valid_from/valid_until/observed_environment/applicability_conditions/drift_status on knowledge items, triggering KNOWLEDGE_REVALIDATION_REQUIRED when the observed environment changes; framed as especially important for architecture, infrastructure, policy, security, and AI-agent-behavior knowledge."

No single_candidate_flags recorded for this label.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0448 §"training conditions may differ from future operating conditions ... population drift and sensor drift ... Validity: valid_from, valid_until, observed_environment, applicability_conditions, drift_status."]
- CANDIDATE-CONCEPTUAL-BIRTH: [S0997 §"99.29 — Runtime architecture discovery ... instead of trusting ArchitectureDocument, we can reconstruct actual dependencies from runtime evidence: ObservedCalls→RuntimeDependencyGraph."]
- CANDIDATE-FORMAL-BIRTH: [S0448 §same anchor as lexical]
- CANDIDATE-OPERATIONAL-BIRTH: [S0997 §same anchor as conceptual]
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1005 (family's `files_touching` also lists S1006). Candidate lifecycle: DORMANT.
Evidence: no retracted_by, no superseded_by, not contested_by_own_contradiction_type — all empty. DORMANT is a heuristic based on recency of source_id, not a confirmed retirement.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0448, S0997 (x3), S1005 (x3) |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0997 (x2), S1005 (x2) |
| examples | PRESENT | S0448, S0997 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S0997 (x5), S1005 (x3) |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE (no rows typed EXPLANATION/ARGUMENT/ANALYSIS/ALTERNATIVE). `rationale_truncated_count` is 0.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)
- `[S0448]` types=[FORMALIZATION, EXAMPLE] scope=OBJECT — "KOS-09 Drift Detection (P0): knowledge items carry a Validity record; example chain 'Nexus is our artifact repository' -> environment observation 'Nexus infrastructure changed' -> drift detector -> KNOWLEDGE_REVALIDATION_REQUIRED; flagged as especially important for architecture, infrastructure, policies, security, operational procedures, technology choices, and AI agent behavior." (anchor: "training conditions may differ from future operating conditions ... Validity: valid_from, valid_until, observed_environment, applicability_conditions, drift_status.")
- `[S0997]` types=[FORMALIZATION, PRINCIPLE] scope=OBJECT — "Defines RequiredObservability=f(Criticality,Risk): critical components require greater observability." (anchor: "99.27 — Observability is risk-dependent ... RequiredObservability=f(Criticality,Risk).")
- `[S0997]` types=[CONCEPT, IMPLEMENTATION] scope=OBJECT — "Proposes reconstructing a RuntimeDependencyGraph from ObservedCalls rather than trusting the ArchitectureDocument alone." (anchor: "99.29 — Runtime architecture discovery ... ObservedCalls→RuntimeDependencyGraph.")
- `[S0997]` types=[EXPERIMENT, EXPERIMENTAL-RESULT] scope=OBJECT — "Experiment 15: an observed extra dependency (A→C) beyond the declared graph (A→B) is detected architecture drift; result PASS." (anchor: "99.30 — Experiment 15 ... Declared graph A→B. Observed graph A→B and A→C. Expected: architecture drift detected. Result: PASS")
- `[S0997]` types=[DEFINITION] scope=OBJECT — "Extends drift detection to configuration: Compare(Config_approved, Config_actual)." (anchor: "99.31 — Configuration drift ... Compare(Config_approved,Config_actual).")
- `[S0997]` types=[EXPERIMENT, EXPERIMENTAL-RESULT] scope=OBJECT — "Experiment 16: a runtime timeout of 300s against an approved 30s is configuration drift; result PASS." (anchor: "99.32 — Experiment 16 ... Approved: Timeout=30s. Runtime: Timeout=300s. Result: PASS")
- `[S0997]` types=[EXAMPLE, EXTENSION] scope=OBJECT — "Extends drift detection to infrastructure (container runtime, OS distribution), noting effect on assurance." (anchor: "99.33 — Infrastructure drift ... Podman vs Docker, RHEL vs Ubuntu. This can affect assurance.")
- `[S0997]` types=[EXPERIMENT, EXPERIMENTAL-RESULT] scope=OBJECT — "Experiment 17: a runtime OS differing from the approved RHEL9 is environmental divergence; result PASS." (anchor: "99.34 — Experiment 17 ... Approved: RHEL9. Actual: DifferentOS. Result: PASS")
- `[S0997]` types=[PRINCIPLE] scope=OBJECT — "States Drift ≠ Violation: intentional evolution is not a violation unless the observed state fails the currently approved specification." (anchor: "99.37 — Drift is not necessarily failure ... Drift ≠ Violation unless the observed state violates the approved specification.")
- `[S0997]` types=[EXPERIMENT, EXPERIMENTAL-RESULT] scope=OBJECT — "Experiment 19: an intentional architecture update (A_1→A_2) matched by a compliant runtime change (R_2⊨A_2) is no violation; result PASS." (anchor: "99.38 — Experiment 19 ... Expected: if R_2⊨A_2, there is no architecture violation. Result: PASS")
- `[S0997]` types=[DEFINITION, FORMALIZATION] scope=OBJECT — "Defines ArchitectureDriftEvent creation when Runtime ⊭ Architecture, feeding Drift→Impact→Escalation." (anchor: "99.77 — Architecture drift becomes a knowledge event ... Drift→Impact→Escalation.")
- `[S0997]` types=[EXPERIMENT, EXPERIMENTAL-RESULT] scope=OBJECT — "Experiment 38: an unauthorized direct infrastructure change produces ArchitectureDrift and potentially GovernanceViolation; result PASS." (anchor: "99.78 — Experiment 38 ... Expected: ArchitectureDrift and potentially GovernanceViolation. Result: PASS")
- `[S1005]` types=[FORMALIZATION, DEFINITION] scope=OBJECT — "Defines the runtime architecture graph G_R(t)=(V_R,E_R), compared against the intended graph G_I." (anchor: "106.13 — Runtime architecture graph ... G_R(t)=(V_R,E_R) ... compare with G_I.")
- `[S1005]` types=[DEFINITION] scope=OBJECT — "Defines Drift(t)=Difference(G_I,G_R(t)), called one of the most valuable KnowledgeOS capabilities." (anchor: "106.14 — Architecture drift ... Drift(t)=Difference(G_I,G_R(t)).")
- `[S1005]` types=[EXPERIMENT, EXPERIMENTAL-RESULT] scope=OBJECT — "Experiment 7: an undocumented new external API appearing in production is a DriftCandidate; result PASS." (anchor: "106.15 — Experiment 7 ... Expected: DriftCandidate. Result: PASS")
- `[S1005]` types=[PRINCIPLE, DISTINCTION] scope=OBJECT — "States Drift ≠ Violation: a runtime difference may be an ApprovedException or ExpectedDynamicBehavior." (anchor: "106.16 — But drift is not automatically violation ... Drift ≠ Violation.")
- `[S1005]` types=[FORMALIZATION, DEFINITION] scope=OBJECT — "Defines Drift=(ExpectedState,ObservedState,Timestamp,Evidence,Impact,Status) with a six-value status lifecycle (New/Investigating/Accepted/Remediating/Resolved/Exception)." (anchor: "106.67 — Architecture drift becomes a first-class object ... Possible status: New, Investigating, Accepted, Remediating, Resolved, Exception.")
- `[S1005]` types=[EXPERIMENT, EXPERIMENTAL-RESULT] scope=OBJECT — "Experiment 31: an intentional, approved drift is DriftStatus=Accepted/Exception; result PASS." (anchor: "106.68 — Experiment 31 ... Expected: DriftStatus=Accepted/Exception. Result: PASS")
- `[S1005]` types=[PRINCIPLE] scope=OBJECT — "States even accepted drift must retain Reason/Authority/Validity, or it becomes undocumented architecture." (anchor: "106.69 — Drift should not disappear ... Even accepted drift should retain Reason, Authority, Validity.")
- `[S1005]` types=[EXPERIMENT, EXPERIMENTAL-RESULT] scope=OBJECT — "Experiment 32: a continuing deviation after exception expiry produces ExceptionExpired→GovernanceFinding; result PASS." (anchor: "106.70 — Experiment 32 ... Expected: ExceptionExpired→GovernanceFinding. Result: PASS")

## Notes for P3
This label's own node_metadata note explicitly distinguishes it (B0012, "knowledge-item validity monitoring") from two closely-related but content-distinct objects it shares group_ids with: `runtime-architecture-conformance-model` (B0024, the four-representation A_I->A_C->A_D->A_R conformance chain) and `architecture-drift-classification-model` (B0024, the six-category D1-D6 discrepancy taxonomy) — the source material itself treats these as related-but-separate objects that "extend" this one, not as the same object. Nineteen of this label's twenty rows actually come from the two Step-99/Step-106 files (S0997, S1005) which are the architecture/runtime-conformance material, while only the single opening row (S0448) is about the original knowledge-item Validity record the node_metadata description centers on — meaning the bulk of this label's captured evidence is arguably closer to the *architecture drift* sibling objects than to the *knowledge-validity* concept the label's own declared scope names. P3 should weigh whether this label's row set has drifted (no pun intended) from its own declared definition.

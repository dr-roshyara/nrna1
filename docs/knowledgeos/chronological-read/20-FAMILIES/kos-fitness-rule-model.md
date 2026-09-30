# kos-fitness-rule-model

**Scope(s):** THEORY-LEVEL · **Row count:** 11 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `F_i(S,C)->{PASS,FAIL,WARN,UNKNOWN}`; `FitnessResult`; `FitnessRule`; `Verdict+Severity->PipelineDisposition`
**Aliases:** "Architecture Fitness Model"; "Step 129"
**Candidate group membership (NOT an identity claim):**
- G0278: links this to `kos-architecture-fitness-rules` — explicit agent-stated uncertainty: 'kos-fitness-rule-model' POSSIBLY relates to 'kos-architecture-fitness-rules' (batch B0025). Note: Step 129's formal fitness-rule model: F_i(S,C)->verdict, governed FitnessRule/FitnessResult schemas and lifecycles, rule categories, three(+)-valued verdict states, severity/enforcement-policy mapping, and the Narrative Architecture -> Executable Architecture transformation, distinct from (but the formal home of) the AFR-nn rule catalogue.

## Sources (how this label entered the ledger)

- PROPOSAL, batch B0025, scope THEORY-LEVEL: "Step 129's formal fitness-rule model: F_i(S,C)->verdict, governed FitnessRule/FitnessResult schemas and lifecycles, rule categories, three(+)-valued verdict states, severity/enforcement-policy mapping, and the Narrative Architecture -> Executable Architecture transformation, distinct from (but the formal home of) the AFR-nn rule catalogue."

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S1029 §"F_i(S,C) → {PASS,FAIL,WARN,UNKNOWN}"]
- CANDIDATE-CONCEPTUAL-BIRTH: [S1029 §"AFR-01 ... AFR-10 dashboard with WARN on AFR-06"]
- CANDIDATE-FORMAL-BIRTH: [S1029 §"F_i(S,C) → {PASS,FAIL,WARN,UNKNOWN}"]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle

last_seen: S1029. Candidate lifecycle: DORMANT.
Evidence: `lifecycle_evidence` is empty (`retracted_by: []`, `superseded_by: []`, `contested_by_own_contradiction_type: false`). DORMANT is a heuristic based on how recently (by source_id) this label was last used (last_seen: S1029), not a confirmed retirement or confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1029 (×7) |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1029 (×4) |
| examples | PRESENT | S1029 |
| warnings | PRESENT | S1029 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale

NOT-EVIDENCED-IN-CAPTURE — `rationale_evidence` is empty for this label (no row is typed ARGUMENT/ANALYSIS/EXPLANATION/ALTERNATIVE). `rationale_truncated_count` is 0.

## Assumption register

NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)

- [S1029] types=[FORMALIZATION, DEFINITION] scope=THEORY-LEVEL — "Formalizes a fitness rule as a machine-checkable constraint F_i(S,C) -> {PASS,FAIL,WARN,UNKNOWN} over observed system state S and applicable constitutional/governance context C, whose result must itself become evidence; distinguishes architecture principle (what should be true) from fitness rule (makes it testable) from verification execution (produces evidence about whether it's true); worked example F01: forall k in AuthoritativeKnowledge: Valid(Provenance(k)), executed as 'check authoritative-knowledge-provenance' over 1,248 objects with 0 invalid, verdict PASS." (anchor: "F_i(S,C) → {PASS,FAIL,WARN,UNKNOWN}")
- [S1029] types=[FORMALIZATION] scope=OBJECT — "Defines a FitnessRule schema (id, name, principle, scope, predicate, severity, checker, checker_version, applicability, lifecycle) stating the rule itself is governed knowledge; requires rule provenance (FitnessRule->Principle->Authority, e.g. AFR-02->Constitutional Principle C1->Architecture Constitution->Approved Decision) and governed applicability (Applicable(F,S,t) evaluated via Scope+Policy+Authority, explicitly rejecting an ad hoc 'if inconvenient: rule.not_applicable=true' escape hatch)." (anchor: "FitnessRule ├── id ... └── lifecycle")
- [S1029] types=[DEFINITION] scope=THEORY-LEVEL — "Classifies fitness rules into eight categories: Structural (dependencies/package boundaries), Semantic (domain terminology/relationships), Governance (authority/decision/exception), Security (access/authorization boundaries), Evidence (provenance/traceability), Behavioral (runtime behavior), Assurance (deterministic verification), Agent (AI-specific constraints)." (anchor: "Structural / Semantic / Governance / Security / Evidence / Behavioral / Assurance / Agent")
- [S1029] types=[FORMALIZATION] scope=THEORY-LEVEL — "Assembles the architecture fitness pipeline diagram, then poses the recursive problem: what if someone changes AFR-19 itself to make a failing system pass? — concluding FitnessRule is governed knowledge with its own lifecycle (Proposed->Reviewed->Approved->Effective->Superseded) and RuleVersion tracked on every VerificationResult." (anchor: "Architecture Principle → Fitness Rule → Checker → Execution → Evidence → Verdict → {PASS, FAIL→Finding→Governance}")
- [S1029] types=[FORMALIZATION, DISTINCTION] scope=OBJECT — "Defines a FitnessResult schema (id, rule_id, rule_version, subject, execution, expected, actual, verdict, evidence, executed_by, timestamp) as a first-class assurance artifact; states FitnessResult ≠ AuthoritativeKnowledge (a result says 'at time T, checker C found condition X'; governance may subsequently interpret it); a FAIL verdict creates a Finding, but an UNKNOWN verdict must not automatically create a Violation — instead Finding(type=InsufficientEvidence)." (anchor: "FitnessResult ├── id ... └── timestamp")
- [S1029] types=[EXAMPLE, FORMALIZATION] scope=THEORY-LEVEL — "Worked example (checker cannot determine conformance because the runtime API is unavailable) establishing a three-valued-minimum assurance model {PASS | FAIL | UNKNOWN}, with NOT_APPLICABLE and EXCEPTION as additional needed states, and a fitness state machine NOT_APPLICABLE/EXECUTED->{PASS,FAIL->Finding,UNKNOWN}." (anchor: "Runtime API unavailable → UNKNOWN, not FAIL, and certainly not PASS.")
- [S1029] types=[CONCEPT, WARNING] scope=THEORY-LEVEL — "Presents an Architecture Fitness Dashboard worked example (per-AFR PASS/WARN statuses, actual statuses coming from execution not documentation) and a fitness-trend concept (Fitness(t1) vs Fitness(t2) detecting ArchitecturalDrift, e.g. 100%->98%->96% across releases 41-43); warns against reducing architecture health to a single percentage — CriticalFailures must be shown separately since one failed authority invariant can outweigh twenty cosmetic successes." (anchor: "AFR-01 ... AFR-10 dashboard with WARN on AFR-06")
- [S1029] types=[FORMALIZATION] scope=THEORY-LEVEL — "Defines a five-level governed Severity taxonomy per fitness rule and an enforcement-policy mapping Verdict+Severity->PipelineDisposition (e.g. FAIL+BLOCKER->BLOCK, FAIL+LOW->WARN); worked example — C1=FAIL (critical) flows KnowledgeOS constitutional check->C1 FAIL->Critical finding->Governance workflow->potential deployment block, 'how architecture becomes operational governance.'" (anchor: "Severity: BLOCKER, CRITICAL, HIGH, MEDIUM, LOW. Verdict+Severity → PipelineDisposition.")
- [S1029] types=[DISTINCTION] scope=THEORY-LEVEL — "Distinguishes FitnessRule from unit Test (does the system continue to conform to an architectural constraint? vs. does this implementation behavior work?), from Policy (what is required vs. how we can objectively test it, Policy->FitnessRule), and from a governance Decision (Decision->Knowledge->FitnessRule->Verification is the complete path from governance to executable assurance)." (anchor: "UnitTest ⊂ EngineeringAssurance; FitnessRule ⊂ ArchitectureAssurance. Policy → FitnessRule. Decision → Knowledge → FitnessRule → Verification.")
- [S1029] types=[PRINCIPLE] scope=THEORY-LEVEL — "States the 'most important transformation' — Narrative Architecture -> Executable Architecture, achieved not by eliminating documents but by attaching deterministic verification to objectively-testable parts; worked with three narrative-to-executable examples (DDD boundary: 'Governance owns decisions' -> OnlyGovernanceMayMutate(Decision), checked via dependency graph/API ownership/write access; Agent boundary: 'agents cannot independently create authoritative decisions' -> Agent ↛ AuthoritativeDecisionCreation without an approved promotion path, checked via API permissions/service roles/authorization policies/integration paths; Evidence: 'material changes must be traceable' -> forall x in MaterialActions: exists authorization(x) and exists evidence(x), continuously queryable)." (anchor: "Narrative Architecture → Executable Architecture.")
- [S1029] types=[RESTATEMENT] scope=THEORY-LEVEL — "Step 129 verdict: the KnowledgeOS Architecture Fitness Model's core pipeline, and the key insight that architecture fitness is the executable boundary between architecture governance and engineering reality." (anchor: "Principle → Rule → Checker → Evidence → Verdict → Finding → Governance")

## Notes for P3

- No internal tension, unknown-candidate marker, or contested-lifecycle discrepancy was observed in this label's own rows; evidentiary base is straightforward for its row count.

# eks-kernel-extraction-mapping

**Scope(s):** METHODOLOGICAL · **Row count:** 8 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `EKS→KnowledgeOS Kernel integration map`
**Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
- G1146: links this to `observation-runtime-changeset-pipeline` — labels co-occur in the same contribution's labels[] 2 separate times across the corpus

## Sources (how this label entered the ledger)

- OBJECT-INDEX, batch B0005, scope METHODOLOGICAL: "The repeatedly-revised proposal to classify existing EKS capabilities as kernel-candidate vs domain-layer and extract generic mechanics (Authority, Governance, Evidence, Work, ChangeSet) into a KnowledgeOS kernel while keeping EKS-specific rules/vocabulary above it; six kernel-worthiness criteria proposed, later superseded by a call for a broader EKS+PKS+KnowledgeOS convergence study."

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S0187 §"The kernel should not simply be 'the current EKS code moved into a kernel package.' We need to identify which EKS capabilities are kernel-worthy."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S0187 §"Kernel criterion 1: Would PKS need it? ... Kernel criterion 6: Can EKS depend on it without the kernel depending on EKS?"]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S0187 §"the current baseline explicitly says that bounded contexts are not yet sufficiently demonstrated as current implementation boundaries. Therefore I would not yet create KnowledgeOS::GovernanceContext ..."]

## Lifecycle

last_seen: S0191. Candidate lifecycle: DORMANT.
Evidence: `lifecycle_evidence` is empty (`retracted_by: []`, `superseded_by: []`, `contested_by_own_contradiction_type: false`). DORMANT is a heuristic based on how recently (by source_id) this label was last used (last_seen: S0191), not a confirmed retirement or confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | PRESENT | S0187, S0191 (×2) |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0187 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S0187 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0187, S0191 (×2) |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S0187 (×2) |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale

Recommends framing EKS not as becoming the kernel, but as the first domain module running on kernel primitives extracted from it, avoiding the terminology 'EKS becomes the KnowledgeOS kernel.' [S0187] Elevates ChangeSet to a strong kernel-integration candidate because it already decouples change detection from observation execution regardless of tool origin (Git/VS Code/Claude Code/CI), proposing it sit below both EKS and PKS collectors. [S0191] Argues the Recommendation Engine's domain-specific rule content should not move wholesale into the kernel; instead a generic rule-evaluation protocol belongs in the kernel while EKS/PKS supply their own concrete rules above it. [S0191]

## Assumption register

NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)

- [S0187] types=[WARNING, PRINCIPLE] scope=METHODOLOGICAL — "Argues against a naive lift-and-shift of EKS code into a kernel package, proposing instead a capability-by-capability kernel-worthiness classification." (anchor: "The kernel should not simply be 'the current EKS code moved into a kernel package.' We need to identify which EKS capabilities are kernel-worthy.")
- [S0187] types=[FORMALIZATION, CONSTRAINT] scope=METHODOLOGICAL — "Proposes six kernel-worthiness criteria (cross-domain need, future-domain need, own invariant, stable lifecycle, engineering-independence, and the dependency-direction test that the kernel must never depend on EKS)." (anchor: "Kernel criterion 1: Would PKS need it? ... Kernel criterion 6: Can EKS depend on it without the kernel depending on EKS?")
- [S0187] types=[EXTENSION] scope=THEORY-LEVEL, also labeled `human-act` — "Proposes generalizing the existing EKS Authority invariant (record ≠ authority) into a kernel-level Authority mechanism owning principal/scope/capability/grant/lifecycle/evidence, with domain-specific 'which architecture is correct' judgement left above the kernel." (anchor: "Artifact existence ≠ Authority ... Record existence ≠ Authority establishment. That is exactly the kind of invariant a kernel can protect.")
- [S0187] types=[ALTERNATIVE] scope=THEORY-LEVEL — "Recommends framing EKS not as becoming the kernel, but as the first domain module running on kernel primitives extracted from it, avoiding the terminology 'EKS becomes the KnowledgeOS kernel.'" (anchor: "EKS should initially become a Kernel Adapter / Domain Module ... EKS becomes the first major domain implementation running on KnowledgeOS kernel primitives.")
- [S0187] types=[WARNING, GOVERNANCE] scope=METHODOLOGICAL — "Warns against freezing final bounded contexts before an 'EKS → KnowledgeOS Kernel Extraction & Conformance Analysis' has been run, proposing that dedicated investigation as the next assignment instead." (anchor: "the current baseline explicitly says that bounded contexts are not yet sufficiently demonstrated as current implementation boundaries. Therefore I would not yet create KnowledgeOS::GovernanceContext ...")
- [S0191] types=[PRINCIPLE, CORRECTION] scope=METHODOLOGICAL — "Revises the kernel-design approach: extract kernel primitives from evidence of what EKS already implements, rather than designing a kernel independently and then retrofitting EKS." (anchor: "the existing EKS is evidence for the kernel design. We shouldn't invent a kernel first and then ask how to migrate EKS into it.") — lineage claim: SOURCE-CLAIMED-REFINEMENT of S0187's own prior kernel-extraction assessment
- [S0191] types=[EXTENSION, ARGUMENT] scope=OBJECT, also labeled `observation-runtime-changeset-pipeline` — "Elevates ChangeSet to a strong kernel-integration candidate because it already decouples change detection from observation execution regardless of tool origin (Git/VS Code/Claude Code/CI), proposing it sit below both EKS and PKS collectors." (anchor: "ChangeSet may be a particularly important kernel abstraction ... it potentially belongs below EKS ... EKS collectors / PKS collectors")
- [S0191] types=[DISTINCTION, ALTERNATIVE] scope=OBJECT, also labeled `observation-runtime-changeset-pipeline` — "Argues the Recommendation Engine's domain-specific rule content should not move wholesale into the kernel; instead a generic rule-evaluation protocol belongs in the kernel while EKS/PKS supply their own concrete rules above it." (anchor: "Measurement is not recommendation ... Kernel: Observation, Evidence, Rule evaluation protocol, Assessment, Result, Provenance / EKS: LCOM4 rule, Test-presence rule, Architecture rule, DDD rule, Engineering recommendation.")

## Notes for P3

- No internal tension, unknown-candidate marker, or contested-lifecycle discrepancy was observed in this label's own rows; evidentiary base is straightforward for its row count.

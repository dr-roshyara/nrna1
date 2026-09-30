# sensing-epistemic-update-candidate

**Scope(s):** OBJECT · **Row count:** 5 ·
**Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `K(s',s)` accessibility relation; `Sense(e,K)->EpistemicUpdate`; `WorldChange != KnowledgeChange`
**Aliases:** "knowledge-producing actions"
**Candidate group membership (NOT an identity claim):** Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)

- OBJECT-INDEX, batch B0061, scope OBJECT: "Candidate model of sensing/observation as restriction of an epistemic accessibility set (A_{t+1} subseteq A_t) that updates epistemic state without necessarily mutating the represented subject state; explicitly does not adopt the source's universal no-side-effects assumption for sensing actions, but treats it as a candidate model of 'epistemic update without subject-state mutation' to be tested against contradictory/stale observations, provenance, context, temporal separation, unobservable states, insufficient evidence."

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S2526 §"K(s',s) ... sensing actions filter the accessible situations ... WorldChange \\neq KnowledgeChange ... we should not adopt that [no-side-effects] assumption universally. Instead: Sensing provides a candidate model for epistemic update without subject-state mutation."]
- CANDIDATE-CONCEPTUAL-BIRTH: [S2527 §"Knows(\\phi,s)\\equiv(\\forall s').K(s',s)\\supset\\phi[s'] ... The No-Side-Effects Assumption ... If a fact is not known, assume it is known that it is not known"]
- CANDIDATE-FORMAL-BIRTH: [S2526 §"K(s',s) ... sensing actions filter the accessible situations ... WorldChange \\neq KnowledgeChange ..."] (same anchor as lexical birth)
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S2526 §"DK.1 State and History [DERIVED] ... DK.3 Successor-State Semantics [PROP — EXPERIMENT REQUIRED] ... DK.8 Epistemic Update [PROP] ... DK.9 Temporal and Concurrent Transitions [PROP — REQUIRES TIME/FRAME DECISION]"]

## Lifecycle

last_seen: S2527. Candidate lifecycle: ACTIVE.
Evidence: `lifecycle_evidence` is empty (`retracted_by: []`, `superseded_by: []`, `contested_by_own_contradiction_type: false`). ACTIVE is a recency heuristic (this is one of the latest-dated rows in the batch, 2026-09-02), not confirmation the candidate has been adopted — the source material itself repeatedly marks this as `[PROP]` (proposed, not yet tested) status.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S2526, S2527 (×2) |
| type_signature | PRESENT | S2526, S2527 |
| invariants | PRESENT | S2526 |
| dependencies | PRESENT | S2526 |
| assumptions | PRESENT | S2526, S2527 |
| semantics | PRESENT | S2526 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale

NOT-EVIDENCED-IN-CAPTURE — `rationale_evidence` is empty for this label (no row is typed ARGUMENT/ANALYSIS/EXPLANATION/ALTERNATIVE); the row set is FORMALIZATION/HYPOTHESIS/GOVERNANCE/CONCEPT material. `rationale_truncated_count` is 0.

## Assumption register

| Statement | Stated | source_id | Anchor |
|---|---|---|---|
| Source's no-side-effects assumption for sensing is not adopted universally by KnowledgeOS | EXPLICIT | S2526 | "we should not adopt that assumption universally" |
| Knowledge-producing actions affect only the K fluent (No-Side-Effects Assumption), imported as a candidate not a KnowledgeOS universal | EXPLICIT | S2527 | "The No-Side-Effects Assumption" |

## All rows (source_id order)

- [S2526] types=[FORMALIZATION, CORRECTION] scope=CROSS-OBJECT — "Adopts the accessibility-relation model of knowledge (K(s',s)) and sensing-as-filtering as a candidate for WorldChange != KnowledgeChange, explicitly declining to adopt the source's universal no-side-effects assumption for sensing actions, treating it instead as one candidate model of epistemic update without subject-state mutation." (anchor: "K(s',s) ... sensing actions filter the accessible situations ... WorldChange \\neq KnowledgeChange ...")
- [S2526] types=[HYPOTHESIS] scope=CROSS-OBJECT — "Extends the existing Observation-first pipeline with a new PROP-only hypothesis: Observation/Sensing = epistemic state-space restriction (accessibility set A_{t+1} subseteq A_t), to be tested against contradictory observations, stale observations, provenance, context changes, temporal separation, unobservable states, insufficient evidence, connecting to prior FDE/Boundary work." (anchor: "A_t \\rightarrow A_{t+1} where A_{t+1}\\subseteq A_t for pure information acquisition ... PROP only.") — completeness PARTIAL, missing: "test results against the seven listed stress conditions".
- [S2526] types=[GOVERNANCE, RESTATEMENT] scope=THEORY-LEVEL (also labeled `state-history-distinction`, `successor-state-semantics-candidate`, `executable-history-semantics`, `transition-composition-algebra`, `regression-progression-reasoning-mechanisms`) — "Proposes a 9-section 'KNOWLEDGEOS — DYNAMIC KNOWLEDGE AND TRANSITION SEMANTICS' research-draft addition (DK.1–DK.9), explicitly not yet called Theory v1.3, with per-section status tags distinct from the KR.1-KR.10 DL-thread tags in S2523/S2524." (anchor: "DK.1 State and History [DERIVED] ... DK.3 Successor-State Semantics [PROP — EXPERIMENT REQUIRED] ...")
- [S2527] types=[FORMALIZATION, CONCEPT] scope=OBJECT — "Gives the full situation-calculus knowledge apparatus: Knows(phi,s) via universal quantification over accessible s', KWhether, KRef, the successor-state axiom for the K-accessibility fluent under sensing actions, the No-Side-Effects Assumption, and a 'Dynamic Closed-World Assumption' — if a fact is not known, assume it is known that it is not known. Status: [PROP]." (anchor: "Knows(\\phi,s)\\equiv(\\forall s').K(s',s)\\supset\\phi[s'] ...")
- [S2527] types=[FORMALIZATION] scope=OBJECT — "Gives a concrete candidate epistemic-transition formula: K_{t+1}^epistemic = Filter(K_t^epistemic, SenseResult(e)), operationalizing the earlier 'epistemic update without subject-state mutation' hypothesis." (anchor: "K_{t+1}^{epistemic} = Filter(K_t^{epistemic}, SenseResult(e)) ... K_{t+1}(s',s) \\iff K_t(s',s) \\land SenseResult(e,s')=SenseResult(e,s)")

## Notes for P3

- This label is explicit, in its own row text, about being a candidate imported from external situation-calculus literature (Reiter's "Knowledge in Action") rather than a KnowledgeOS-native derivation, and about consciously declining to adopt one of that source's universal assumptions (the No-Side-Effects Assumption). P3 should treat the `[PROP]`/`[DERIVED]`/`[OPEN]` status tags cited in S2526's ninth row as load-bearing — several sibling sections (DK.3, DK.5, DK.8, DK.9) are explicitly still PROP or OPEN even within the source's own accounting, not just per this ledger's ACTIVE/DORMANT heuristic.
- The last row (S2527) is dated 2026-09-02, the latest date seen in this batch's rows overall — worth checking whether later corpus material (outside this batch) tested this candidate against the seven stress conditions named in row 2.

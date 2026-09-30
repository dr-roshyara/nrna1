# independent-reconstruction-dependency-cycle

**Scope(s):** OBJECT · **Row count:** 10 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Assertion→Evidence→Rule→Policy→Authority→Assertion`, `Evidence→Policy→K→Assertion→Evidence` · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0042, scope OBJECT): An independently reconstructed 24-26 node definitional dependency graph, executed by DFS with grey-node back-edge detection, contains 3 cycles: a 7-node SCC (Assertion→Evidence→Rule→Policy→Authority→Assertion, or equivalently Evidence→Policy→K→{Assertion,Relation}→Evidence) that is UNTERMINATED (via the undefined Qualify function), and a governance-recursion cycle Policy→K→...→Policy that IS terminated by v0.2's R-1/I-11 policy-as-content/policy-in-force stratification. A third cycle (Policy→K→Relation→Σ→Policy) exists because Σ is policy-relative but is stored as a bare field with no policy parameter — a defect in the corpus's own relation model, not only the claimed theory (G-04).

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1720 §"**G-04** | **The definitional dependency graph is cyclic**"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: [S1729 §"READING: a candidate whose removal destroys K is NECESSARY for K as defined."]
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1749. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction lineage found. This lifecycle value is a heuristic based on how recently (by source_id, last_seen=S1749) this label was last used in the ledger, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S1734, S1749 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1740 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S1720, S1729, S1734, S1735 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
The Qualify cycle (to qualify an observation into evidence you need a policy; the policy is knowledge content; that content is an assertion; that assertion is justified by evidence) is the single genuine unrecorded circularity in the theory — v0.2's R-1 stratification terminates the loop about *changing* policy but says nothing about *using* a policy inside qualification, and the human-act externalization does not help either since a human act authorises a policy, it does not qualify an observation. Whether it is a legitimate Knaster-Tarski fixed point cannot even be tested because Qualify has no body. [S1734] The dependency-graph cycle detector is implemented as a 24-node hand-reconstructed edge dictionary (from the corpus's own object definitions, e.g. Evidence:[Observation,Policy] encoding the Qualify dependency; Policy:[K] encoding policy-as-content) traversed by a grey/black DFS coloring algorithm that records a cycle whenever a grey (in-progress) node is revisited. [S1749]

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)

- `[S1720]` types=[EXPERIMENTAL-RESULT] scope=THEORY-LEVEL — "G-04 (CRITICAL): the definitional dependency graph is cyclic, containing a 7-node SCC (Assertion→Evidence→Rule→Policy→Authority→Assertion), broken only by Step 187's normative stipulation whose own recorded basis re-enters the cycle. Requires HUMAN DECISION D-2." (anchor: "**G-04** | **The definitional dependency graph is cyclic**")
- `[S1729]` types=[EXPERIMENTAL-RESULT] scope=THEORY-LEVEL — "EXP-16: over 26 nodes and 42 edges, exactly one strongly-connected component of size >1 exists: {Assertion, Assessment, Authority, EpistemicStatus, Evidence, Policy, Rule}, with witness path Assertion→Evidence→Rule→Policy→Authority→Assertion — the definitional dependency graph is confirmed cyclic, not a DAG." (anchor: "CYCLE: ['Assertion', 'Assessment', 'Authority', 'EpistemicStatus', 'Evidence', 'Policy', 'Rule']")
- `[S1729]` types=[EXPERIMENTAL-RESULT] scope=THEORY-LEVEL — "EXP-17: of 26 definitional nodes, 11 (including Assertion, K, History, KStar, Equivalence, Operation, T_algebra) transitively reach a Step-266-declared class-C (no decision procedure) object such as Policy or Authority, and are therefore transitively non-computable; 10 nodes (Context, Dimension, Entity, Observation, Proposition, Provenance, RelationType, Source, Time, Value) are not blocked." (anchor: "nodes whose definition transitively reaches a class-C object: 11/26")
- `[S1729]` types=[EXPERIMENT, EXPERIMENTAL-RESULT] scope=THEORY-LEVEL — "EXP-18 (removal test): systematically deleting each candidate primitive and checking which of the 18 downstream definitions survive shows Entity and Dimension are the most load-bearing for K (removal leaves only 6-7 of 18 nodes standing), while Observation, Proposition and Context are not load-bearing for K specifically (9 of 18 survive their removal) though they may be load-bearing elsewhere." (anchor: "READING: a candidate whose removal destroys K is NECESSARY for K as defined.")
- `[S1734]` types=[EXPERIMENTAL-RESULT] scope=THEORY-LEVEL — "Executed DFS over 24 reconstructed nodes finds exactly 3 cycles: Evidence→Policy→K→Assertion→Evidence and Evidence→Policy→K→Relation→Evidence (both via the undefined Qualify function, unterminated), and Policy→K→Relation→Σ→Policy (a semantic cycle created by storing the policy-relative value Σ as a bare field with no policy parameter — a defect in the corpus's own relation model)." (anchor: "CYCLE: Evidence -> Policy -> K -> Assertion -> Evidence")
- `[S1734]` types=[ARGUMENT] scope=THEORY-LEVEL — "The Qualify cycle (to qualify an observation into evidence you need a policy; the policy is knowledge content; that content is an assertion; that assertion is justified by evidence) is the single genuine unrecorded circularity in the theory — v0.2's R-1 stratification terminates the loop about *changing* policy but says nothing about *using* a policy inside qualification, and the human-act externalization does not help either since a human act authorises a policy, it does not qualify an observation. Whether it is a legitimate Knaster-Tarski fixed point cannot even be tested because Qualify has no body." (anchor: "> **This is the single genuine circularity in the theory, and no artifact in the corpus — research,
> ratified or verification — records it.**")
- `[S1734]` types=[CORRECTION] scope=METHODOLOGICAL — "The mandate's prescribed 8-stage pipeline (Primitive→Definition→Invariant→Transformation→Policy→Assessment→Authorization→Execution→New State) is falsified as a DEPENDENCY graph on two measured edges: Policy actually depends on K (via policy-as-content), the reverse of what the pipeline implies; and Assessment does not feed Authorization at all (the corpus's own governance law states Assessment→verdict→NEVER grants authority). The pipeline survives only as a description of execution sequence, not dependency order." (anchor: "**Two edges are wrong, as measured:**")
- `[S1735]` types=[EXPERIMENTAL-RESULT] scope=THEORY-LEVEL — "Structural closure is assessed NOT CLOSED: five primitives dangle with no defining body (Observation needs World, present in the corpus but absent from every claimed model; Evidence needs the undefined Qualify; Command needs the bodiless Authorize; a Γ-write has no carrier in K; Admissible is never defined as a term, only cited by two rival laws), and the dependency graph contains 3 cycles so it is not a DAG." (anchor: "| `Observation` | `World` | 🔴 **`W` is in the corpus (31.17) and in no claimed model** |")
- `[S1740]` types=[DISTINCTION] scope=THEORY-LEVEL — "The authority-to-change-Policy regress is answered twice already in the corpus (v0.2 R-1/I-11 stratification; externalization via humanActRef) with no invention required — but this closes policy CHANGE, not policy USE: the Qualify cycle (using a policy to qualify an observation) is untouched by either termination, since a human act authorises a policy but does not qualify an observation." (anchor: "⚠️ **But it is a policy-change closure, not a policy-use closure.**")
- `[S1749]` types=[EXPLANATION] scope=METHODOLOGICAL — "The dependency-graph cycle detector is implemented as a 24-node hand-reconstructed edge dictionary (from the corpus's own object definitions, e.g. Evidence:[Observation,Policy] encoding the Qualify dependency; Policy:[K] encoding policy-as-content) traversed by a grey/black DFS coloring algorithm that records a cycle whenever a grey (in-progress) node is revisited." (anchor: "EDGES = {
 "ValueSpace":[], "Entity":[], "Dimension":["ValueSpace"],")

## Notes for P3
NOT-EVIDENCED-IN-CAPTURE — no reviewer-added observation for this label beyond what appears above.

# step060-semilattice-overclaim-k-order

**Scope(s):** THEORY-LEVEL · **Row count:** 5 · **Lifecycle (candidate):** CONTESTED · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** (K,merge,∅) join-semilattice, PureClaimSetUnion, step-060 · **Aliases:** semilattice overclaim, the K-order semilattice overclaim
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX · batch B0052 · scope THEORY-LEVEL: A corpus-integrity finding from Step 289's hidden-order propagation audit: step-060 ('Epistemic Algebra and Knowledge State Ordering') proves the semilattice laws hold only for a restricted merge operator (PureClaimSetUnion, 60.39-41) and explicitly refuses to generalize this to the full knowledge state K (60.42, 60.71: 'we cannot yet assert KnowledgeOS is a semilattice'), yet five separate verification-lane artifacts (KNOWLEDGE-STATE-ALGEBRA.md, THEORY-CLOSURE-AUDIT.md, KNOWLEDGE-STATE-FINAL-AUDIT.md, step_282_theory-closure-decision.md, POLICY-EQUALITY-AND-COMPOSITION.md) assert (K,merge,empty-set) IS a join-semilattice for K generally -- an F5-error-class overclaim (since a join-semilattice structure induces a K-order) combining scope loss, verdict inversion, and an unclassified claim riding beside a genuinely proven Policy-level result. Independently refuted by executed evidence that retraction/withdrawal shrinks the state, contradicting the monotone-growth claim the semilattice framing implies.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2155 §"**`step-060` — *Epistemic Algebra and Knowledge State Ordering*:** ... §60.42 ... *"This works for `PureClaimSetUnion`. KnowledgeOS does more than set union… **Therefore the real merge operator may not satisfy the semilattice laws**"* ... §60.71 ... *"Does knowledge form a lattice? **Not proven.** … **we cannot yet assert: KnowledgeOS is a semilattice**"*"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S2155. Candidate lifecycle: CONTESTED.
Evidence: contested_by_own_contradiction_type=True

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S2155 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S2155 |
| dependencies | PRESENT | S2155 |
| assumptions | PRESENT | S2155 |
| semantics | PRESENT | S2155 |
| examples | PRESENT | S2155 |
| warnings | PRESENT | S2155 |
| experiments | PRESENT | S2155 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
Introduces step-060 ('Epistemic Algebra and Knowledge State Ordering') as the actual corpus source on whether knowledge forms a semilattice/lattice: it proves the semilattice laws PASS only for a restricted operator, PureClaimSetUnion (60.39-41), explicitly warns the real merge operator does more than set union so 'the real merge operator may not satisfy the semilattice laws' (60.42), and concludes at 60.71 'not proven ... we cannot yet assert: KnowledgeOS is a semilattice.' This is the corpus's own refusal of the very claim later found to be asserted elsewhere without qualification [S2155]. Diagnoses three distinct defects in the semilattice overclaim: (1) scope loss -- 060's result holds only for PureClaimSetUnion, but the five artifacts drop that restriction; (2) verdict inversion -- 060.71 explicitly says 'cannot yet assert', while the artifacts flatly assert the opposite; (3) an unclassified claim riding alongside a proven one -- POLICY-EQUALITY-AND-COMPOSITION section 3 states the K join-semilattice claim as an aesthetic 'pleasing duality' aside, and its own formal classification table never lists the K claim at all (only the separately PROVEN, executed (Policy,meet) meet-semilattice claim), so an unclassified and unexecuted K-order claim was smuggled in beside a genuinely proven Policy-level one [S2155]. Tests whether any of four policy-level equality notions (identifier, structural, extensional, semantic) transfer to K, finding none do: K_t has no identity rule (identifier fails), K's canonicalization is unbound (structural fails), extensional policy equality is undecidable in general even for Policy itself, and semantic policy equality is explicitly 'NOT COMPUTABLE' even for Policy. Concludes (Policy, meet) being a meet-semilattice establishes nothing about K -- they are separate mathematical objects -- while noting two Policy findings DO generalize as methodology rather than algebra: 'policy equality != equal output' (agreement on a finite sample is not evidence, independently reproduced for equality generally) and 'version must be part of identity' [S2155].

## Assumption register
| Statement | Stated | Source ID | Anchor |
|---|---|---|---|
| the semilattice laws hold for PureClaimSetUnion specifically | EXPLICIT | S2155 | "semilattice laws PASS — for PureClaimSetUnion" |

## All rows (source_id order)
- [S2155] types=[EXPLANATION, LIMITATION] scope=OBJECT — "Introduces step-060 ('Epistemic Algebra and Knowledge State Ordering') as the actual corpus source on whether knowledge forms a semilattice/lattice: it proves the semilattice laws PASS only for a restricted operator, PureClaimSetUnion (60.39-41), explicitly warns the real merge operator does more than set union so 'the real merge operator may not satisfy the semilattice laws' (60.42), and concludes at 60.71 'not proven ... we cannot yet assert: KnowledgeOS is a semilattice.' This is the corpus's own refusal of the very claim later found to be asserted elsewhere without qualification." (anchor: "**`step-060` — *Epistemic Algebra and Knowledge State Ordering*:** ... §60.42 ... *"This works for `PureClaimSetUnion`. KnowledgeOS does more than set union… **Therefore the real merge operator may not satisfy the semilattice laws**"* ... §60.71 ... *"Does knowledge form a lattice? **Not proven.** … **we cannot yet assert: KnowledgeOS is a semilattice**"*")
- [S2155] types=[CONTRADICTION, COUNTEREXAMPLE] scope=THEORY-LEVEL — "Discovers that five separate verification-lane artifacts (KNOWLEDGE-STATE-ALGEBRA.md:89, THEORY-CLOSURE-AUDIT.md:149, KNOWLEDGE-STATE-FINAL-AUDIT.md:29, step_282_theory-closure-decision.md:219, and POLICY-EQUALITY-AND-COMPOSITION.md:73) all assert (K,merge,empty-set) IS a join-semilattice for the full knowledge-state space K, directly contradicting step-060's own explicit refusal of that generalization. Notes formally that asserting a join-semilattice structure on K is itself asserting a K-order (since a join-semilattice induces a partial order K_A <= K_B iff K_A join K_B = K_B), making this instance of the F5-error-class (Sigma/K-order conflation) rather than a mere wording issue." (anchor: "### Five verification-lane artifacts assert it anyway ... *"`(𝕂, merge, ∅)` is a **join**-semilattice — **adding knowledge only grows the state**"* ... $$\boxed{\text{Asserting } (\mathbb K, merge, \emptyset) \text{ is a join-semilattice IS asserting a } K\textbf{-ORDER}.}$$")
- [S2155] types=[ANALYSIS, WARNING] scope=THEORY-LEVEL — "Diagnoses three distinct defects in the semilattice overclaim: (1) scope loss -- 060's result holds only for PureClaimSetUnion, but the five artifacts drop that restriction; (2) verdict inversion -- 060.71 explicitly says 'cannot yet assert', while the artifacts flatly assert the opposite; (3) an unclassified claim riding alongside a proven one -- POLICY-EQUALITY-AND-COMPOSITION section 3 states the K join-semilattice claim as an aesthetic 'pleasing duality' aside, and its own formal classification table never lists the K claim at all (only the separately PROVEN, executed (Policy,meet) meet-semilattice claim), so an unclassified and unexecuted K-order claim was smuggled in beside a genuinely proven Policy-level one." (anchor: "**Three separate defects:** 1. **Scope loss.** ... 2. **Verdict inversion.** `060.71` says *"cannot yet assert"*; the artifacts assert. 3. ⚠️ **`POLICY-EQUALITY-AND-COMPOSITION` §3 states it as a *"pleasing duality"* — an aesthetic aside ... An unclassified `K`-order claim rode in beside a proven `Policy` one.**")
- [S2155] types=[EXPERIMENTAL-RESULT, CORRECTION] scope=THEORY-LEVEL — "Refutes the monotone-growth claim underlying the semilattice overclaim ('adding knowledge only grows the state') by execution: the corpus has withdrawal/retraction (Article 8, invariant I-12), and the previously executed test (step-288 06 section H) already showed withdrawal re-keys the assertion id and dangles every relational edge -- retraction shrinks the state rather than only ever growing it, so monotone growth for K is REFUTED, not merely unproven." (anchor: "*"Adding knowledge only grows the state"* requires monotone growth. The corpus has **withdrawal and retraction** ... and **EXECUTED** (`step-288/06 §H`) withdrawal **re-keys the assertion id and dangles every `ℛ`-edge**. $$\boxed{\text{Retraction shrinks. Monotone growth is REFUTED for } \mathbb K.}$$")
- [S2155] types=[ARGUMENT, DISTINCTION] scope=OBJECT — "Tests whether any of four policy-level equality notions (identifier, structural, extensional, semantic) transfer to K, finding none do: K_t has no identity rule (identifier fails), K's canonicalization is unbound (structural fails), extensional policy equality is undecidable in general even for Policy itself, and semantic policy equality is explicitly 'NOT COMPUTABLE' even for Policy. Concludes (Policy, meet) being a meet-semilattice establishes nothing about K -- they are separate mathematical objects -- while noting two Policy findings DO generalize as methodology rather than algebra: 'policy equality != equal output' (agreement on a finite sample is not evidence, independently reproduced for equality generally) and 'version must be part of identity'." (anchor: "| **extensional** | 🔴 no — and note it is **undecidable in general even for Policy** (`POLICY-EQUALITY` §2) | **semantic** | 🔴 no — *"NOT COMPUTABLE"* even for Policy ... $$\boxed{(Policy, \wedge) \text{ being a meet-semilattice establishes NOTHING about } \mathbb K.}$$")

## Notes for P3
(none beyond what is captured above)

# Phase 5C — Kernel Object Register

Thirteen objects, extending Phase 5B's twelve (KERNEL-OBJ-01–12) with one new object (KERNEL-OBJ-13,
the earliest document in the population). Each entry uses the full required attribute set;
`NOT EVIDENCED` is used wherever this phase's own bounded investigation found no supporting evidence,
rather than inferring one.

---

## KERNEL-OBJ-01 — K-1 (content): KnowledgeAggregate+ConflictRecord (+VerificationPort, provenance disputed)

- **Object identifier**: KERNEL-OBJ-01 · **First evidenced occurrence**: seq 0167 (content) / seq
  0196 (label) · **Source documents**: 0167, 0196, 0216
- **Provenance relations**: `ORIGINATES` (content) at 0167 → label+full gloss applied at 0196 →
  `REJECTS` (literal implementation) at 0216
- **Definition**: a DDD aggregate decomposition — KnowledgeAggregate + ConflictRecord (+ a third,
  "VerificationPort," component whose raw-source origin is unresolved, per Phase 5B)
- **Intended purpose**: a candidate Kernel boundary for engineering KnowledgeOS
- **Representation**: DDD aggregate/bounded-context design, not a mathematical tuple
- **State variables**: `NOT EVIDENCED` (no formal state-variable list found in raw source)
- **Dimensions/components**: KnowledgeAggregate, ConflictRecord, VerificationPort (third component's
  origin unresolved)
- **Operators/transitions**: `NOT EVIDENCED`
- **Constraints**: `NOT EVIDENCED`
- **Admissibility conditions**: `NOT EVIDENCED`
- **Invariants**: seq 0216's own record implies an invariant-preservation intent ("identity, evidence,
  justification, epistemic state, confidence and history must remain coherent") but this is stated as
  the *reason* for the original God-Aggregate proposal, not as KERNEL-OBJ-01's own formally stated
  invariant list
- **Minimality claim**: `NOT EVIDENCED` as a formal, tested claim
- **Falsification/test status**: not tested as a whole; **rejected via architectural decomposition**
  (seq 0216), not an executed falsification test (kept distinct per Phase 4's own prior correction)
- **Rejection status**: rejected for literal implementation (seq 0216); the underlying invariants it
  aimed to protect are explicitly preserved in 0216's own decomposition
- **Successor/refinement relations**: decomposed (not superseded by one named object) at seq 0216
- **Unresolved ambiguities**: the raw-source origin of "VerificationPort" (Phase 5B finding, restated
  not re-derived)

## KERNEL-OBJ-02 — K-1 (label reuse, `phase_measure_theory/knowledgeos_kernel/`)

- **Object identifier**: KERNEL-OBJ-02 · **First evidenced occurrence**: seq 1008 · **Source
  documents**: 1008
- **Provenance relations**: none connecting it to KERNEL-OBJ-01 — `UNRESOLVED`
- **Definition**: "K-1 `K_t`, 8 primitives (ratified)" — a ratified 8-primitive state-space object,
  per a comparison table alongside K-2/K-3/K-6/K-7
- **Intended purpose**: `NOT EVIDENCED` beyond its role in a five-candidate consequence matrix
- **Representation**: mathematical (a `K_t`-typed object with 8 named primitives)
- **State variables**: 8 primitives, individually named — `NOT EVIDENCED` in this phase's own
  bounded check (the matrix cell was read; the primitives' own individual names were not independently
  re-verified here)
- **Dimensions/components**: `NOT EVIDENCED` beyond "8 primitives"
- **Operators/transitions**: `δ` referenced in the same matrix row ("`δ` over 8 primitives; replay
  expressible") — Level 1, raw-source confirmed
- **Constraints**: `NOT EVIDENCED`
- **Admissibility conditions**: "replay-capable · governance-anchored" (Level 1, raw-source confirmed)
- **Invariants**: "I-1…I-12 ratified" (Level 1, raw-source confirmed — a distinct, separately-numbered
  invariant catalog from KERNEL-OBJ-01's own unstated invariants)
- **Minimality claim**: not explicitly a minimality claim in this matrix row; "ratified" language
  suggests acceptance, not a tested-minimal claim
- **Falsification/test status**: `NOT EVIDENCED` within this phase's own bounded check (the matrix
  compares candidates; whether K-1 here was itself independently tested is not established)
- **Rejection status**: not rejected — "ratified"
- **Successor/refinement relations**: `NOT EVIDENCED`
- **Unresolved ambiguities**: whether this is the same referent as KERNEL-OBJ-01 (assessed likely
  homonym per Phase 5B, `UNRESOLVED` by this phase's own default)

## KERNEL-OBJ-03 — `ADR-KOS-KERNEL-001`

- **Identifier**: KERNEL-OBJ-03 · **First occurrence**: seq 0167 · **Source**: 0167
- **Provenance relations**: `ORIGINATES` the content later labeled K-1 (KERNEL-OBJ-01)
- **Definition**: a formal ADR-format governance artifact proposing Kernel scope/boundary
- **Purpose**: to formally scope the Kernel via governance process
- **Representation**: prose ADR, PROPOSED status
- **State variables / dimensions / operators / constraints / admissibility / invariants**:
  `NOT EVIDENCED` as independently-stated formal attributes distinct from KERNEL-OBJ-01's own content
- **Minimality claim**: `NOT EVIDENCED`
- **Falsification/test status**: not tested
- **Rejection status**: not formally accepted or rejected — remains PROPOSED (no later status-change
  record found in the 22-file K-1 population or the 116-file marker population)
- **Successor/refinement relations**: `NOT EVIDENCED`
- **Unresolved ambiguities**: whether PROPOSED status was ever resolved anywhere outside this phase's
  own bounded search

## KERNEL-OBJ-04 — The six-part aggregate invariant (falsified)

- **Identifier**: KERNEL-OBJ-04 · **First occurrence**: seq 0150 · **Source documents**: 0150, 0157
- **Provenance relations**: `ORIGINATES` at 0150 → `FALSIFIES` (of its own atomicity claim) at 0157
- **Definition**: a proposed single-aggregate boundary (Identity+EvidenceRefs+Justification+
  EpistemicState+Confidence+…)
- **Purpose**: a candidate Kernel consistency boundary
- **Representation**: DDD aggregate design
- **State variables/dimensions**: the six named parts (per its own name)
- **Operators/transitions**: `NOT EVIDENCED` beyond the stress-test's own many-to-many evidence-
  sharing scenario
- **Constraints/admissibility**: `NOT EVIDENCED`
- **Invariants**: the proposed atomicity invariant itself — this is precisely what was tested and
  falsified
- **Minimality claim**: `NOT EVIDENCED`
- **Falsification/test status**: **`TESTED → REJECTED`** — Level 1, "FALSIFIED. Semantic relatedness
  and traceability do NOT imply transactional atomicity" (seq 0157, raw-source confirmed, Phase 4)
- **Rejection status**: rejected, via genuine executed test (the only such executed falsification test
  found among the 116-document population, per Phase 4/5A/5B's own repeated finding)
- **Successor/refinement relations**: none named as a direct single successor
- **Unresolved ambiguities**: none beyond what is recorded

## KERNEL-OBJ-05 — `S_Kernel=(D,E,S,T,U)`

- **Identifier**: KERNEL-OBJ-05 · **First occurrence**: seq 0377 · **Source**: 0377, reconnected at
  0504
- **Definition**: a five-component mathematical tuple, derived via "intersection-of-regime-
  requirements"
- **Representation**: mathematical tuple · **Dimensions**: D, E, S, T, U (named, but their individual
  expansions are `NOT EVIDENCED` in this phase's own bounded re-check)
- **Operators/constraints/admissibility**: `NOT EVIDENCED`
- **Invariants**: `NOT EVIDENCED` as a separately-stated list
- **Minimality claim**: implicit in the "intersection-of-regime-requirements" derivation method (a
  minimality-seeking construction), but **not independently confirmed as a formally tested minimality
  proposition** in this phase's own bounded check
- **Falsification/test status**: seq 0377's own open question asks "whether the hypothesis survives
  contact with the six-regime comparison experiment proposed" — **no execution record found** (Phase
  4's own prior finding, restated)
- **Rejection status**: not rejected; not confirmed
- **Successor/refinement relations**: `REFINES`/reconnection to KERNEL-OBJ-06 at seq 0504 (Level 3)
- **Unresolved ambiguities**: whether the proposed six-regime test was ever executed

## KERNEL-OBJ-06 — Knowledge Ātma Kernel `𝒦_core`

- **Identifier**: KERNEL-OBJ-06 · **First occurrence**: seq 0504 · **Source**: 0504
- **Definition**: a persistence-invariant identity concept, distinct from the knowledge state itself
- **Representation**: conceptual/identity notion, not a formal tuple with stated operations
- **State variables/dimensions/operators/constraints/admissibility/invariants**: `NOT EVIDENCED` as
  independently formalized attributes beyond the identity-persistence claim itself
- **Minimality claim**: `NOT EVIDENCED`
- **Falsification/test status**: not tested
- **Rejection status**: not rejected
- **Successor/refinement relations**: `REFINES` from KERNEL-OBJ-05 (Level 3, per 0504's own `refines`
  field)
- **Unresolved ambiguities**: whether formally reconciled with KERNEL-OBJ-05's own tuple structure
  (seq 0504's own open question, unresolved)

## KERNEL-OBJ-07 — `K=(K_t,Ω_K,ℐ)` (Gita-lens culminating formalization)

- **Identifier**: KERNEL-OBJ-07 · **First occurrence**: seq 2260 · **Source**: 2260
- **Definition**: state + eleven-operation candidate algebra + transition-validity rule
- **Representation**: mathematical tuple/algebra · **Operators**: Observe, Discriminate, Qualify,
  Incorporate, Reject, Contradict, Revise, Supersede, Recall, Withdraw, Act (11 named, Level 3 — from
  the governed per-file record, not independently re-verified against raw prose word-for-word in this
  phase) · **Constraints/admissibility**: the transition-validity rule `I(K_t,ω,K_{t+1})=true`
- **Invariants**: `NOT EVIDENCED` as a separately-named list
- **Minimality claim**: described by its own record as "the most precise candidate produced across
  the entire Gītā-lens thread" — a comparative claim, not a formally tested minimality proposition
- **Falsification/test status**: not tested
- **Rejection status**: not rejected
- **Successor/refinement relations**: `POSSIBLE_RELATIONSHIP` to KERNEL-OBJ-08 (same thread,
  chronologically earlier; no explicit document-level statement connecting them found)
- **Unresolved ambiguities**: relationship to prior "less-precise candidates" in its own thread, not
  individually identified in this phase

## KERNEL-OBJ-08 — Nine-component "master kernel tuple" `K_t=(K,K_t,N,M_t,B_t,O,δ,Z_t,Γ_t)`

- **Identifier**: KERNEL-OBJ-08 · **First occurrence**: seq 2293 · **Source**: 2293
- **Definition**: a nine-component tuple, per-file record states it "unifies roughly thirteen prior
  competing tuple proposals" (Level 3 synthesis claim, **not** independently verified as a literal
  count in raw prose — Phase 5B's own correction, restated)
- **Representation**: mathematical tuple · **Operators**: `δ` (transition) named; others `NOT
  EVIDENCED` individually in this phase's own bounded check
- **Constraints/admissibility/invariants**: `NOT EVIDENCED`
- **Minimality claim**: `NOT EVIDENCED` as a formal proposition
- **Falsification/test status**: not tested
- **Rejection status**: not rejected
- **Successor/refinement relations**: `POSSIBLE_RELATIONSHIP` to KERNEL-OBJ-07 (see above)
- **Unresolved ambiguities**: the identity of the "thirteen prior" proposals it claims to unify — not
  individually identified in this phase

## KERNEL-OBJ-09 — State-transition algebra `(𝕂,𝒪)`

- **Identifier**: KERNEL-OBJ-09 · **First occurrence**: seq 0858 · **Source**: 0858
- **Definition**: explicitly "not assumed to be a classical ring/field/vector-space"; nine candidate
  operations
- **Representation**: algebraic structure · **Operators**: nine candidates named in the governed
  record, `NOT EVIDENCED` individually re-verified against raw prose in this phase
- **Constraints**: "governance-breaks-composition" correction — `MathematicalComposition ≠
  GovernedComposition` (Level 1, raw-source-adjacent per the governed record's own quoted definition)
- **Admissibility/invariants**: `NOT EVIDENCED` beyond the above
- **Minimality claim**: `NOT EVIDENCED`
- **Falsification/test status**: not tested
- **Rejection status**: not rejected
- **Successor/refinement relations**: `NOT EVIDENCED`
- **Unresolved ambiguities**: relationship to any other object in this register — none found

## KERNEL-OBJ-10 / KERNEL-OBJ-11 — D285-1 State Ontology Matrix / D285-7 Kernel Consequence Matrix

- **Identifiers**: KERNEL-OBJ-10 (seq 1006) / KERNEL-OBJ-11 (= KERNEL-OBJ-02, seq 1008, cross-
  referenced, not double-counted as a distinct object)
- **Definition**: comparison/adjudication matrices, not standalone Kernel proposals — they compare
  five candidates (K-1, K-2, K-3, K-6, K-7)
- **All other attributes**: `NOT EVIDENCED` for K-2/K-3/K-6/K-7 individually — **explicitly out of
  this phase's own scope**, named as a boundary, not investigated further

## KERNEL-OBJ-12 — Seq 2330's own DDD Kernel definition (the sole C2 candidate)

- **Identifier**: KERNEL-OBJ-12 · **First occurrence**: seq 2330 (also the **latest** document in the
  116-hit population) · **Source**: 2330 (already fully raw-source verified in Phase 4)
- **Definition**: "The KnowledgeOS Kernel is the smallest domain-independent bounded context that owns
  the identity, lifecycle and provenance of knowledge-bearing participants, content references,
  information histories, contexts, epistemic states, knowledge attributions and transitions" (Level 1,
  verbatim, raw-source-confirmed at line 1675 of the source file, per Phase 4's own spot-check)
- **Representation**: DDD-style prose definition embedded in a larger mathematical synthesis (the
  nine-component core `𝒞=(D,P,T,C,I,E,R,H,Θ)`)
- **Minimality claim**: the word "smallest" appears in the definition itself — a genuine, if informal,
  minimality *language* — but **no stated criterion, admissible-transformation set, or test result**
  accompanies it in this phase's own bounded check; recorded as an untested minimality *assertion*, not
  a formally established minimality *claim* in the Model-B sense
- **Falsification/test status**: not tested
- **Rejection status**: not rejected
- **Successor/refinement relations**: self-declared `POSSIBLE_RELATIONSHIP` to "file 2322/2323's C1
  kernel candidates" (the document's own words) — never carried out within this evidence base
- **Unresolved ambiguities**: relationship to KERNEL-OBJ-01 through -11 — none is established

## KERNEL-OBJ-13 — The 10-candidate Kernel-worthiness evaluation matrix (new this phase)

- **Identifier**: KERNEL-OBJ-13 · **First occurrence**: seq 0080 (the **earliest** document in the
  116-hit population) · **Source**: 0080
- **Definition**: a `Candidate × {External evidence, EKS, PKS, AIP, Cross-cutting?, Kernel
  candidate?}` matrix over 10 candidates (Identity, Provenance, Temporal validity, Authority,
  Evidence, Decision, Lineage, Lifecycle, Conflict, Assurance) — an **evaluation/adjudication
  artifact**, not itself a proposed Kernel structure
- **Representation**: comparison matrix, same general kind as KERNEL-OBJ-10/11 but much earlier in
  the corpus and structurally unconnected to them (no document found linking the two)
- **State variables/dimensions**: the 10 named candidates themselves (Level 1, raw-source confirmed)
- **Operators/transitions/constraints/admissibility/invariants**: `NOT EVIDENCED` — this is an
  evaluation exercise, not a formal structure
- **Minimality claim**: the file's own text references a "minimal trusted core" as a design ideal
  ("kernel analogy 'correctly contained': validated as inspiration... explicitly NOT a literal spec")
  — **explicitly disclaimed as inspirational language, not a formal minimality proposition**
- **Falsification/test status**: not tested (an evaluation exercise, not an executed experiment)
- **Rejection status**: not rejected
- **Successor/refinement relations**: `NOT EVIDENCED` — no later document in this phase's own
  investigation was found to explicitly build on this specific matrix
- **Unresolved ambiguities**: whether this evaluation exercise fed into any of KERNEL-OBJ-01 through
  -12 — no connecting document found

---

## Summary

Thirteen objects register, spanning 5 subdirectories and 5 object categories (DDD aggregate design ×2,
formal governance artifact ×1, mathematical tuple/algebra ×5, conceptual/identity notion ×2,
evaluation/adjudication matrix ×3 — KERNEL-OBJ-10/11/13). **Every attribute marked `NOT EVIDENCED`
above reflects an actual absence found by this phase's own investigation, not an unexamined gap** —
each `NOT EVIDENCED` entry was checked against the governed per-file record before being recorded as
absent.

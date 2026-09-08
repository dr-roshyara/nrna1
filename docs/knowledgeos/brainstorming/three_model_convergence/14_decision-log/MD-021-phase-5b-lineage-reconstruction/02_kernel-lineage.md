# Phase 5B — Kernel Lineage Reconstruction

**Does not start from Phase 4's "eight."** Starts from the 116-file corpus-wide Kernel-definition
text-pattern census (Phase 5A Part 2, re-used per `01_population-and-method.md`), broken down by
classification: 92 `engineering_knowledgeos` (already inside Model C1's 719-row population), 14
`meta_research`, 5 `cross_model`, 4 `gita` (already Model A's evidence), 1 `epistemic_knowledgeos`
(the sole C2 file). **The 12 objects graphed below are a disclosed, prioritized subset of this
116-file population, not an exhaustive per-object graph of all of it** — chosen because each has
either (a) already received a raw-source verification in a prior phase, (b) surfaced during this
phase's own K-1/completeness investigation, or (c) is required by the authorization's own §7/§8/§9
instructions.

---

## Object 1 — `K-1` construct (content), a.k.a. KnowledgeAggregate+ConflictRecord+VerificationPort

- **Object ID**: KERNEL-OBJ-01
- **First observed sequence (content, not label)**: 0167
- **Source file(s)**: 0167 (`kernel/20260823-232146-adr-kos-kernel-001-...md`); label first applied
  at 0196 (`kernel/20260824-020611-wave-1-kernel-extent-versus-contents-adjudication-status.md`);
  reviewed/rejected at 0216 (`kernel/20260824-111317-...md`)
- **Exact name(s)**: `ADR-KOS-KERNEL-001` (0167, unlabeled prose); "K-1" (0196 onward)
- **Object category**: DDD aggregate design
- **Origin evidence (Level 1, raw source checked)**: seq 0167's raw `.md` source contains
  "KnowledgeAggregate" and "ConflictRecord" as named architectural components (verified by direct
  `grep`). It does **not** contain the literal string "K-1" or "VerificationPort" anywhere.
- **Refinement evidence**: seq 0196 is the first file (of 22 `K-1`-string hits, in ascending sequence
  order) to state "K-1 = KnowledgeAggregate+ConflictRecord+VerificationPort" — but **raw-source
  checked**: the literal string "VerificationPort" does **not** appear in seq 0196's raw `.md` source
  either, only in its own governed per-file YAML record's synthesis. **This is a genuine, disclosed
  provenance gap**: the commonly-repeated three-part gloss compounds across at least two files'
  governed records (0167 supplying 2 of 3 components in raw source, the per-file record for 0196
  supplying the full three-part label), with the third component not independently verified in any
  raw source checked in this phase.
- **Rejection/falsification evidence**: seq 0216's raw source (already verified during Phase 4's own
  verification pass) explicitly rejects literal implementation: "do not implement the earlier 'God
  KnowledgeAggregate' literally."
- **Supersession evidence**: none found — 0216 decomposes rather than supersedes with a single named
  replacement.
- **Explicit predecessor**: 0167 (content), per Level 1 raw-source evidence.
- **Explicit successor**: none found within the `kernel/` directory beyond 0216's own decomposition.
- **Potential duplicate/homonym**: **yes — see Object 2.**
- **Relationship confidence**: Level 3 (reconstructed provenance) for the 0167→0196 content link;
  Level 4 (research interpretation) for treating the "VerificationPort" addition as a synthesis-layer
  addition rather than a raw-source omission on this phase's part (a third, unexamined source between
  0167 and 0196 cannot be ruled out).
- **Unresolved questions**: which specific file (if any) introduces "VerificationPort" in raw prose;
  whether one exists at all or the label was coined directly in the per-file synthesis layer.

## Object 2 — `K-1` label reused at seq 1008 (`phase_measure_theory/knowledgeos_kernel/`)

- **Object ID**: KERNEL-OBJ-02
- **First observed sequence**: 1008
- **Source file(s)**: 1008 (`phase_measure_theory/knowledgeos_kernel/research/D285-7-KERNEL-
  CONSEQUENCE-MATRIX.md`)
- **Exact name(s)**: "K-1 `K_t`, 8 primitives (ratified)"
- **Object category**: mathematical tuple / ratified formal object (distinct category from Object 1's
  DDD aggregate design)
- **Origin evidence (Level 1)**: raw source confirmed via direct `grep`: "K-1 `K_t`, 8 primitives
  (ratified)" appears verbatim in a consequence-matrix table alongside four sibling candidates
  (K-2, K-3, K-6, K-7).
- **Refinement/rejection/supersession evidence**: none examined beyond this single table row in this
  phase's own bounded investigation.
- **Explicit predecessor/successor**: none found connecting this occurrence to Object 1.
- **Potential duplicate/homonym with Object 1**: **assessed as a likely homonym, not the same
  referent** — the two glosses share nothing in common (DDD aggregate with three named components vs.
  a ratified `K_t`-based object with "8 primitives"), and the two occurrences sit in structurally
  distinct subdirectories (`kernel/` vs. `phase_measure_theory/knowledgeos_kernel/research/`) that
  Phase 5A already found to be substantially non-overlapping research regions.
- **Relationship confidence**: Level 4 (research interpretation) — moderate-to-high confidence in
  homonymy given the total absence of shared content, but not certain, since a connecting document
  might exist unread by this phase.
- **Unresolved questions**: whether any document explicitly connects the two "K-1" occurrences;
  recorded per `00_index.md`'s own instruction as `UNRESOLVED` for the relationship itself, while the
  *evidence bearing on* that relationship (near-total content dissimilarity) is reported plainly.

## Object 3 — `ADR-KOS-KERNEL-001`

- **Object ID**: KERNEL-OBJ-03 · **Source**: 0167 · **Category**: formal governance artifact (a
  prose ADR, PROPOSED status) · **Origin evidence**: Level 1, the ADR's own header states "PROPOSED."
  **Successor**: none found — no later file in the 22-hit K-1 population or the 116-hit marker
  population records a status change to ACCEPTED. **Relationship confidence**: Level 1 for PROPOSED
  status; Level 2 (absence of a later record) for "never formally accepted within this evidence base."

## Object 4 — Six-part aggregate invariant (falsified)

- **Object ID**: KERNEL-OBJ-04 · **Source**: proposed 0150, falsified 0157 (both already raw-source
  verified during Phase 4's own verification pass — re-cited, not re-checked, here) · **Category**:
  DDD aggregate design · **Rejection/falsification evidence**: Level 1 — "FALSIFIED. Semantic
  relatedness and traceability do NOT imply transactional atomicity" (seq 0157, verbatim, previously
  confirmed). **Relationship confidence**: Level 1 for the falsification itself.

## Object 5 — `S_Kernel=(D,E,S,T,U)`

- **Object ID**: KERNEL-OBJ-05 · **Source**: 0377 · **Category**: mathematical tuple · **Successor**:
  explicitly reconnected at 0504 (Knowledge Ātma Kernel), per 0504's own per-file `refines` field —
  already raw-source-adjacent-verified during Phase-4 verification (0504's title itself: "Reconnected
  to the Corpus's Original Kernel-Discovery Thread"). **Relationship confidence**: Level 3
  (reconstructed provenance — the governed per-file record states the connection explicitly; the raw
  prose of 0504 does not repeat the literal tuple notation `(D,E,S,T,U)`, per the earlier Phase-4
  spot-check).

## Object 6 — Knowledge Ātma Kernel `𝒦_core`

- **Object ID**: KERNEL-OBJ-06 · **Source**: 0504 · **Category**: conceptual/identity notion ·
  **Explicit predecessor**: KERNEL-OBJ-05 (per above). **Relationship confidence**: Level 1 for the
  concept's own existence and definition; Level 3 for its connection to KERNEL-OBJ-05.

## Object 7 — `K=(K_t,Ω_K,ℐ)`, the "culminating formalization" (Gita-lens thread)

- **Object ID**: KERNEL-OBJ-07 · **First observed sequence**: 2260 · **Source**:
  `phase_measure_theory/knowledgeos_kernel/prompts/20260901-005349_step_286_define-kernel-from-
  evidence-maths-and-ddd-with-gita-as-lens-only.md` · **Category**: mathematical tuple (state +
  eleven-operation algebra + transition-validity rule) · **Classification**: `meta_research` —
  outside every current model's evidence population. **Origin evidence**: Level 1, per-file record
  quotes the tuple and its eleven named operations directly from the governed synthesis; raw-source
  presence of the exact operator list was not independently re-verified in this phase (a scope
  limitation, disclosed). **Explicit predecessor**: the per-file record's own account names this "the
  most precise candidate produced across the entire Gītā-lens thread" — implying prior, less-precise
  candidates in the same thread, not individually identified in this phase.
- **Relationship confidence**: Level 3 for the tuple's existence and position in its own thread;
  Level 4 for any claim about its relationship to Objects 1–6 (none is asserted).

## Object 8 — Nine-component "master kernel tuple" `K_t=(K,K_t,N,M_t,B_t,O,δ,Z_t,Γ_t)`

- **Object ID**: KERNEL-OBJ-08 · **First observed sequence**: 2293 · **Source**:
  `phase_measure_theory/knowledgeos_kernel/prompts/20260901-021321_...md` · **Category**: mathematical
  tuple · **Classification**: `meta_research`. **Origin/refinement evidence**: Level 3 — the governed
  per-file record states this tuple "unifies roughly thirteen prior competing tuple proposals across
  the entire thread"; **raw-source checked (this phase): the literal count "thirteen" is not stated
  as an enumerated list in the raw prose** — the figure is the per-file record's own synthesis
  estimate, not an independently-verified literal count (consistent with, and now further confirmed
  beyond, the same finding already disclosed in Phase 5A's own completion report). **Explicit
  predecessor**: KERNEL-OBJ-07 is one plausible predecessor (same thread, same subdirectory,
  chronologically earlier) but **no explicit document-level statement connecting them specifically**
  was found in this phase's own bounded check — recorded as `POSSIBLE_RELATIONSHIP`, not `REFINES`.
- **Relationship confidence**: Level 3 for the "unifies prior proposals" claim as a per-file-record
  assertion; Level 5 (hypothesis) for any specific claim that KERNEL-OBJ-07 is among those "thirteen."

## Object 9 — `S_Kernel`-adjacent: State-transition algebra `(𝕂,𝒪)`

- **Object ID**: KERNEL-OBJ-09 · **Source**: 0858 (`phase_measure_theory/20260830-095110_step_232_
  the-knowledge-state-algebra.md`) · **Category**: mathematical algebra (nine candidate operations) ·
  **Classification**: `meta_research`. **Origin evidence**: Level 1, per-file record directly quotes
  "explicitly not assumed to be a classical ring/field/vector-space." **Explicit predecessor/
  successor**: none identified in this phase's own bounded check. **Relationship confidence**: Level
  4 for any claim connecting this to Objects 1–8 — none is asserted; recorded as an independent object
  pending further investigation.

## Object 10/11 — D285-1 State Ontology Matrix / D285-7 Kernel Consequence Matrix

- **Object IDs**: KERNEL-OBJ-10 (seq 1006) / KERNEL-OBJ-11 (seq 1008, = Object 2 above, cross-
  referenced) · **Category**: consequence/comparison matrices, not standalone Kernel definitions —
  these two documents *compare* five candidates (K-1, K-2, K-3, K-6, K-7) rather than propose a new
  one. **Relationship confidence**: Level 1 for the comparison structure's existence; the identity of
  K-2/K-3/K-6/K-7 (beyond K-1, already treated as Object 2) is **not investigated in this phase** —
  named here as an explicit scope boundary, not a finding.

## Object 12 — `ADR-KOS-KERNEL-001`'s sibling: the sole C2 candidate's own DDD Kernel definition (seq 2330)

- **Object ID**: KERNEL-OBJ-12 · **Source**: 2330 (already fully raw-source verified in Phase 4) ·
  **Category**: DDD-style prose definition, embedded inside a formal mathematical synthesis ·
  **Classification**: `epistemic_knowledgeos` (the sole C2 row). **Explicit predecessor**: the
  document's own text cites "file 2322/2323's C1 kernel candidates" as material it should be
  "reconciled against" (per Phase 4's own concept register §M) — a `POSSIBLE_RELATIONSHIP`
  (self-declared by the source, not yet demonstrated), never promoted to `REFINES` or `DERIVES_FROM`
  by this phase.

---

## Summary — what this graph does and does not establish

Twelve objects graphed, spanning at least 5 distinct subdirectories (`kernel/`, `kernel/
refinement_phase/`, `phase_measure_theory/`, `phase_measure_theory/knowledgeos_kernel/prompts/`,
`phase_measure_theory/knowledgeos_kernel/research/`) and 4 object categories (DDD aggregate design,
formal governance artifact, mathematical tuple/algebra, conceptual/identity notion). **Not one
`REFINES`, `DERIVES_FROM`, or `SUPERSEDES` relationship in this table crosses between the `kernel/`
subdirectory family and the `phase_measure_theory/` subdirectory family** — every connection found
that crosses that boundary is `POSSIBLE_RELATIONSHIP` or weaker. This is itself the clearest lineage
finding this artifact produces, carried forward to `05_non-lineages-and-unresolved-relationships.md`.

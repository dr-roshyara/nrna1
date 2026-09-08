# Phase 5C — Equivalence and Identity Adjudication

Six-level ladder (lexical → conceptual → functional → structural → formal equivalence → demonstrated
identity). Default state **UNRESOLVED**. No pair is promoted merely because both are called "Kernel,"
both contain similar tuples, both claim minimality, or one is chronologically later.

---

## Pair 1 — KERNEL-OBJ-01 (K-1, DDD aggregate) vs. KERNEL-OBJ-02 (K-1, `K_t`/8-primitives)

- **Domains/codomains**: OBJ-01's domain is a set of DDD aggregate/entity types (KnowledgeAggregate,
  ConflictRecord, VerificationPort); OBJ-02's domain is a state space `K_t` with 8 primitives.
  **These are not the same kind of mathematical/architectural object.**
- **Mappings**: none proposed by either source document.
- **Preserved structure/operations/invariants**: OBJ-01 has no stated operator set; OBJ-02 has `δ`
  and an "I-1…I-12 ratified" invariant catalog. No shared operator or invariant name found.
- **Assumptions/counterexamples**: assuming identity would require explaining why a DDD aggregate
  decomposition and an 8-primitive ratified state-space object share nothing but a two-character
  label. No such explanation was found.
- **Ladder level reached**: **1 (lexical similarity) only** — both are called "K-1."
- **Adjudication**: **UNRESOLVED**, tending toward `INCOMPATIBLE`/likely-homonym at Level 4 research-
  interpretation confidence (restated from Phase 5B, not upgraded here).

## Pair 2 — KERNEL-OBJ-05 (`S_Kernel=(D,E,S,T,U)`) vs. KERNEL-OBJ-06 (Knowledge Ātma Kernel `𝒦_core`)

- **Domains/codomains**: OBJ-05 is a five-component mathematical tuple; OBJ-06 is a persistence-
  invariant identity concept applied *to* a system, not itself a tuple.
- **Mappings**: seq 0504's own text explicitly connects them ("reconnected... the Kernel is now
  explicitly framed as the executable realization of the Ätma's conceptual identity/principle") —
  this is the strongest explicit connecting statement found anywhere in this register.
- **Preserved structure**: 0504's own record frames OBJ-06 as sitting *on top of* OBJ-05 (a
  conceptual layer over a mathematical substrate), not as the same object under a different name.
- **Ladder level reached**: **3 (functional analogy/correspondence)** — a genuine, source-stated
  functional relationship (identity-principle realized by a mathematical structure), short of
  structural correspondence (no component-by-component mapping from `𝒦_core` onto `(D,E,S,T,U)` is
  given) or formal equivalence.
- **Adjudication**: **PARTIAL CORRESPONDENCE at Level 3 (reconstructed provenance)** — the only pair
  in this register reaching above lexical similarity with source-level support; still short of
  structural correspondence or formal equivalence, and recorded as such rather than rounded up.

## Pair 3 — KERNEL-OBJ-07 (`K=(K_t,Ω_K,ℐ)`) vs. KERNEL-OBJ-08 (nine-component master tuple)

- **Domains/codomains**: both are `K_t`-involving mathematical tuples from the same thread
  (`phase_measure_theory/knowledgeos_kernel/prompts/`), chronologically adjacent (seq 2260, 2293).
- **Mappings**: none stated explicitly connecting the two by name.
- **Preserved structure**: OBJ-08's own record claims to "unify roughly thirteen prior competing
  tuple proposals" — OBJ-07 is a *plausible* member of that set (same thread, earlier), but is never
  individually named as one of the thirteen in any source this phase examined.
- **Ladder level reached**: **2 (conceptual similarity)** — both address "what is the Kernel state
  tuple" within the same research thread; no functional, structural, or formal claim is supported.
- **Adjudication**: **UNRESOLVED** (recorded as `POSSIBLE_RELATIONSHIP` in the provenance graph, not
  promoted here).

## Pair 4 — KERNEL-OBJ-01 (DDD aggregate) vs. KERNEL-OBJ-04 (falsified six-part aggregate)

- **Domains/codomains**: both are DDD aggregate designs, from the same `kernel/` directory family,
  addressing an overlapping concern (what invariants must a Kernel aggregate preserve).
- **Mappings**: OBJ-01's named components (KnowledgeAggregate, ConflictRecord, VerificationPort) do
  not match OBJ-04's six named parts (Identity+EvidenceRefs+Justification+EpistemicState+
  Confidence+…) one-for-one, though both include an evidence/justification-adjacent concern.
- **Preserved structure**: no explicit document connects the two as sequential proposals; both simply
  belong to the same broader multi-round Kernel-Boundary-Discovery cycle (Phase 4's own Cluster 2).
- **Ladder level reached**: **2 (conceptual similarity)** — same research programme, same general
  concern, no stated derivation between the two specific objects.
- **Adjudication**: **UNRESOLVED**.

## Pair 5 — KERNEL-OBJ-12 (seq 2330's DDD Kernel definition) vs. KERNEL-OBJ-01/-04 (C1's own DDD aggregate designs)

- **Domains/codomains**: KERNEL-OBJ-12 defines the Kernel as "the smallest domain-independent bounded
  context that owns the identity, lifecycle and provenance of knowledge-bearing participants, content
  references, information histories, contexts, epistemic states, knowledge attributions and
  transitions" — a DDD-style definition, the same general *kind* of object as OBJ-01/OBJ-04.
- **Mappings**: KERNEL-OBJ-12's own source text explicitly proposes reconciling itself against "file
  2322/2323's C1 kernel candidates" — **but 2322/2323 are `cross_model`-tagged bridge documents, not
  register objects in this phase (they name and frame C1/C2, they do not themselves propose a Kernel
  structure equivalent to OBJ-01/OBJ-04)** — so the self-declared reconciliation target does not
  actually point at OBJ-01 or OBJ-04 specifically.
- **Preserved structure**: no direct mapping between KERNEL-OBJ-12's participant/content-reference/
  information-history/context/epistemic-state/attribution/transition list and either OBJ-01's or
  OBJ-04's own named components was found.
- **Ladder level reached**: **1–2 (lexical/conceptual similarity)** — both are DDD-style Kernel
  boundary definitions; no functional or structural correspondence demonstrated.
- **Adjudication**: **UNRESOLVED**. This is, notably, the closest this register comes to a genuine
  C1↔C2 object-level comparison, and it does not clear even the functional-analogy bar.

## Pair 6 — KERNEL-OBJ-13 (evaluation matrix, seq 0080) vs. KERNEL-OBJ-10/-11 (D285 consequence matrices)

- **Domains/codomains**: all three are comparison/evaluation artifacts over candidate Kernel objects,
  not proposals themselves — the same *artifact type*.
- **Mappings**: OBJ-13 evaluates 10 named candidates (Identity, Provenance, Temporal validity,
  Authority, Evidence, Decision, Lineage, Lifecycle, Conflict, Assurance); OBJ-10/-11 evaluate 5 named
  candidates (K-1, K-2, K-3, K-6, K-7). **No shared candidate name across the two matrices.**
- **Preserved structure**: both use a candidate-by-criterion matrix design — a shared *methodological
  pattern*, not a shared *object*.
- **Ladder level reached**: **3 (functional analogy)** — both are the same kind of governance
  artifact (an adjudication matrix), performing the same function within their respective research
  programmes, with no shared subject matter.
- **Adjudication**: **FUNCTIONAL ANALOGY** (the artifact type, not any specific candidate within it) —
  the clearest positive finding in this register short of Pair 2.

---

## Summary tally

| Level reached | Count | Pairs |
|---|---:|---|
| 1 (lexical only) | 1 | Pair 1 |
| 1–2 | 1 | Pair 5 |
| 2 (conceptual) | 2 | Pairs 3, 4 |
| 3 (functional analogy/correspondence) | 2 | Pairs 2, 6 |
| 4 (structural correspondence) | 0 | — |
| 5 (formal equivalence) | 0 | — |
| 6 (demonstrated identity) | 0 | — |

**No pair in this register reaches structural correspondence, formal equivalence, or demonstrated
identity.** This is reported as the correct, evidence-bound outcome — consistent with every prior
phase's own finding that the corpus's Kernel proliferation is genuinely unresolved, not merely
under-investigated.

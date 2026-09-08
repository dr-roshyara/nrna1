# Phase 5C — Kernel Population and Method

## Population, with denominators kept separate (reused from Phase 5A/5B where already established; not re-derived)

| Population | Size | Source |
|---|---:|---|
| Main-corpus `PRIMARY`-tier rows | 1,185 | `reading-manifest.tsv` (Phase 5A/5B) |
| Math-lane rows | 401 | per-file-mathematical (Phase 5A/5B) |
| Kernel-definition text-pattern census hits (main corpus, complete population) | 116 | Phase 5A Part 2; re-confirmed this phase (`/tmp/kernel_hits2.txt`, identical count) |
| `phase_measure_theory/knowledgeos_kernel/` files on disk | 237 | Phase 5A/5B, re-confirmed |
| Literal-string `K-1` hits (main corpus, complete population) | 22 | Phase 5B |
| `meta_research` ∩ `kernel_content=true` (main corpus) | 306 | Phase 5A Census A |

**No new population is invented this phase.** Phase 5C's own work is to build the **object-level**
register and provenance graph over the 116-hit document population (and the 12-object graph Phase 5B
already produced within it), not to expand the document-level census further.

## Earliest and latest Kernel-marker document (mechanically determined, complete population)

- **Earliest**: seq 0080 (`cross_model` primary, `c1` secondary) — a 10-candidate Kernel-worthiness
  evaluation matrix (Identity, Provenance, Temporal validity, Authority, Evidence, Decision, Lineage,
  Lifecycle, Conflict, Assurance), explicitly marking its own candidate pipeline "hypothesis, not
  kernel design." **This is a new object for this phase's register (Object 13), not previously
  graphed in Phase 5B**, which started its own graph later in the corpus.
- **Latest**: seq 2330 (`epistemic_knowledgeos`) — the sole C2 candidate, already fully raw-source
  verified in Phase 4 and cross-referenced in Phase 5B (= Object 12 there, = Object 12 here).

## Document → object unit-of-analysis discipline

The 116-document population maps to **at least 13 distinct research objects** (Phase 5B's 12 plus
Object 13, newly added this phase) — **not** 116 objects, and **not** 1. Several documents describe
the *same* object at different stages of its own refinement (e.g. seq 0150→0157 both describe the
six-part aggregate, Object 4); several objects are described by exactly one document each (e.g.
Object 13, seq 0080 alone, within this phase's own bounded investigation). This mapping is made
explicit per-object in `02_kernel-object-register.md` and per-document in
`03_kernel-provenance-graph.md`.

## Census-vs-sample methodology (unchanged discipline from Phase 5A/5B)

The population and denominator tables above are **census** (complete-population, machine-observable).
The object-register profiles (`02`) and equivalence adjudications (`04`) are built from a **disclosed,
prioritized, non-exhaustive subset** of the 116-document/13-object population — the same 12 objects
Phase 5B already investigated in depth, plus Object 13 — chosen because each already has at least
Level-2 or Level-1 evidence gathered in a prior phase, not because they are a random or representative
sample. **This is not claimed to be exhaustive**; the remaining ~100 documents in the 116-hit
population have not been individually resolved to research objects in this phase.

## The minimality-word-vs-claim distinction (a finding made before drafting the register)

A mechanical check found the literal word "minimal" present in **116 of 116** files in the
Kernel-marker population (100%) — but inspection of the earliest hit (seq 0080: *"minimal trusted
core... explicitly NOT a literal spec"*) shows this is frequently **loose design-philosophy language**
("a minimal trusted core" as an architectural ideal), not a **formal minimality claim** (a proposition
with a stated criterion, representation, and admissible-transformation set, of the kind Model B's own
kernel-reduction experiment produced). **This phase does not treat "contains the word minimal" as
equivalent to "makes a minimality claim"** — `05_minimality-representation-and-invariants.md` restricts
its own analysis to the objects where a genuine, structured minimality proposition was found on
inspection, and records the 100%-word-match figure only as a caution against over-reading raw keyword
counts (exactly the lesson Phase 5B's own correction already established for a different term set).

# Phase 5H — Mathematical Closure and Source-Gap Resolution for the K-1 → K-2 Projection

**Status: COMPLETE.** Executed under the user's explicit, separate authorization of Phase 5H only
(2026-09-08), following the completed Phase 5G independent adversarial audit. **Phase 5I, global
reclassification, four-model convergence, unified/canonical Kernel construction, DDD context-map
adoption, and implementation remain unauthorized and untouched.**

## Central question, answered up front (full evidence in `12`)

*Can the K-1 → K-2 projection be mathematically completed and characterized from existing corpus
evidence, or are the remaining gaps genuine unresolved source-research gaps?*

**Answer: Mixed — some targets are reconstructable, one is a genuine source-research gap, and one
newly-discovered finding (an executable-code-level Assertion-unpacking conflict, `07`) makes the
overall picture more unsettled than Phase 5G's own prose-only audit found.** Overall classification:
**B — Projection partially closable; explicit source gaps remain** (not A: full closure is not
supported; not C: more was closed than "partial correspondence" alone implies; not D in the strong
sense, though a genuine internal contradiction was found and is disclosed, not papered over).

## Headline findings

1. **A genuinely important new primary source was found this phase**: `exec/t285_reconcile.py`,
   `exec/t285_equality.py`, and `exec/e_equality.py` — **actual executable Python scripts**, the
   corpus's own machine-verifiable implementation of the K-1/K-2 comparison. These are the strongest
   evidence type available anywhere in this reconstruction (machine-observable, not merely prose).
2. **The Assertion-unpacking conflict Phase 5G found in prose (D285-1 vs. D285-6) recurs, differently,
   inside the executable code itself**: `t285_reconcile.py`'s own `ASSERTION_CONTAINS` variable
   matches D285-1's prose list; `t285_equality.py`'s own `UNPACK` variable matches D285-6's prose list.
   **The two scripts disagree with each other, not just the two prose documents.** This is a **genuine
   source-research gap**, not a reconstruction gap — no amount of further reading resolves it, since
   the corpus's own authoritative artifacts (its own code) are internally inconsistent.
3. **`Qualify`'s type signature is well-evidenced and triangulated across two independent documents**
   (seq 0630 §49.76: `Evidence = QualifiedObservation`; seq 0795/step-170 §170.4: a `CaptureAndQualify`
   gate with named conditions) — **`Qualify: Observation → Evidence` is RECONSTRUCTABLE**. Its actual
   computable algorithm is **NOT EVIDENCED** anywhere — confirmed as a genuine, not merely
   under-searched, absence.
4. **`State`'s projection target ("the carrier") has NO independent formal definition anywhere in the
   corpus** — every other use of the word "carrier" in the corpus refers to unrelated concepts. This is
   a **SOURCE-RESEARCH GAP**, not a reconstruction gap.
5. **K-1's own operators (`𝒪_1`) are confirmed absent from their own origin document** — seq 0630, the
   very document that derives the 8 primitives, contains exactly one use of the word "operator" in
   2,232 lines, and it is a passing remark, not an enumeration. This independently confirms D285-7's
   own "new finding" from a second, earlier source.

## Frozen inputs

Model A, Model B, Phase 3, Phase 4, Phase 5A–5G — all read-only. D285-1, D285-6, D285-7 — read-only.
Any discovered issue is recorded as a Phase-5H finding, never repaired in place.

## Artifact map

| File | Contents |
|---|---|
| `00_index.md` | This file. |
| `01_phase-5g-baseline.md` | The 12 frozen Phase-5G findings, restated as the starting point. |
| `02_k1-operator-census.md` | Exhaustive search for K-1 operators; the census table. |
| `03_k1-operator-closure.md` | Case A–D classification for the operator question. |
| `04_qualify-reconstruction.md` | `Qualify`'s type signature, reconstructed from two independent sources. |
| `05_observation-to-e-mapping.md` | What `e` actually is, traced through the executable code. |
| `06_state-projection-audit.md` | The "carrier" source-research-gap finding. |
| `07_assertion-unpacking-reconciliation.md` | The code-level conflict — the phase's most important finding. |
| `08_projection-reconstruction.md` | The required per-primitive table, fully evidenced or `NOT EVIDENCED`. |
| `09_information-loss-classification.md` | D1–D7 classification per dropped/transformed primitive. |
| `10_semantic-relations-and-equivalence.md` | Structural/isomorphism/projection/observational, kept distinct. |
| `11_source-research-gaps.md` | The explicit list: what the corpus itself has not yet defined. |
| `12_final-closure-matrix.md` | The required final matrix and overall classification. |
| `13_open-questions.md` | What remains, and what new corpus research would be needed. |
| `14_verification-and-completion-report.md` | Verification suite; ≥20 raw-source checks; final report. |

## What this phase does NOT do

Does not modify Phase 5C/5D/5E/5F/5G or D285-1/D285-6/D285-7. Does not declare K-1/K-2 equivalent.
Does not declare a DDD context mapping. Does not invent State semantics or implement `Qualify`. Does
not perform four-model convergence. Does not begin Phase 5I.

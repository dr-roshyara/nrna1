# EXPERIMENTAL — DOES NOT ALTER KNOWLEDGEOS PRODUCTION TRUTH
# Derivation & Definition Discovery Audit (§18)

**Phase:** discovery/preservation prep, NOT canonicalization. **Date:** 2026-09-21.
**Status:** EXPERIMENTAL, small sample, not population-inferable. **Authoritative:**
NO. **Scope discipline maintained:** no relationship, basis, or canonical-status
decision was made for any candidate; `31-RECONCILIATION-PAIRS.jsonl`, P3b, P4-P7
untouched; `knowledgeos_theory_research/` not consulted.

## Sample (n=12, stratified by row_count, not random — chosen to cover the
plausible range where multiplicity would concentrate)

| Label | Rows | Stratum |
|---|--:|---|
| `knowledgeos-kernel-concept` | 385 | very large |
| `step-verify-programme` | 338 | very large |
| `kernel-review-session-findings` | 264 | very large |
| `formal-zero-algebra` | 95 | large (also a known EXACT-STRING-REUSE ×2 case) |
| `dimension-discovery-uncertainty` | 94 | large |
| `multi-dimensional-epistemic-state-model` | 39 | medium |
| `kr-rep-reduction-formal-corrections-2026-09` | 33 | medium |
| `step220-concept-genealogy-method` | 21 | medium |
| `kos-persistence-architecture` | 20 | medium |
| `decision-basis-object` | 5 | small/control |
| `decision-experiment-object` | 4 | small/control |
| `epistemic-lineage-provenance-candidate` | 3 | small/control |

**This is not a random sample and its findings are not extrapolated to the
2,497-label population without further, explicitly statistical work** — it is a
representativeness-by-design sample intended to answer one question: does the
phenomenon exist at all, and if so, what does it look like? (Per §18's own
instruction not to overclaim from a small sample.)

## Findings per §18's 8 questions

**1. How often do multiple definitions occur?** In this sample: **4 of 5
large/very-large families (80%) show genuine multiple, non-identical formal
definitions**; the 5th (`dimension-discovery-uncertainty`) shows many
*refinement* steps of essentially one evolving definition, not competing
alternatives. Of the 4 medium families, 2 show real multiplicity
(`multi-dimensional-epistemic-state-model`, and `kr-rep-reduction-formal-
corrections-2026-09` at the content level though uncaptured structurally); 1
(`step220-concept-genealogy-method`) is *about* the phenomenon rather than an
instance of it; 1 (`kos-persistence-architecture`) shows none (single source
document, thoroughly extracted). All 3 small controls (≤5 rows) show none — a
single, coherent, incrementally-built definition. **Pattern: multiplicity
correlates strongly with row_count in this sample** — plausible as a general
finding (sustained corpus attention → more chances for competing formulations)
but not proven at population scale by n=12.

**2. How often do multiple derivations occur?** Confirmed richly in the two
deepest cases: `knowledgeos-kernel-concept` (**9 distinct formal Kernel
definitions across 3 chronologically separate "waves"** — see below) and
`formal-zero-algebra`, whose own row S1433 states outright: *"025d Zero-algebra
register: nine distinct signatures of Zero on record."*

**3. How often do apparently different derivations share dependencies?** Every
multi-derivation case examined showed at least one unstated, shared premise:
- `knowledgeos-kernel-concept`'s entire Wave 1 (F4, F6, F7-Model-A, F9) silently
  assumes membership must be justified by one co-location/consistency-boundary
  argument — exactly what F8's own falsification attacks.
- Wave 3 (F12-F16) all assume `K_t` is a well-typed, existing object at every
  t — one row's own `type_signature` field records `"total": "UNSTATED",
  "deterministic": "UNSTATED"` for the central operator, flagging without
  closing this gap.
- `step-verify-programme`'s five competing status/evidence-classification
  schemes all assume verification status is expressible as a finite,
  mutually-exclusive taxonomy — untested by any of the five, even though the
  same label's own audit-output rows repeatedly find this exact assumption
  fails for the corpus it reviews.
- Two pairs in `knowledgeos-kernel-concept` (F4/F5, F5/F6) and the whole of
  Wave 3 share **prompt lineage** (the corpus's own reviewer explicitly says
  so) — meaning several "distinct" formulations are not independent arrivals
  at all, exactly the common-mode risk §16 warns about.

**4. How often do later formulations refine earlier ones?** Mixed, and
informatively so: `dimension-discovery-uncertainty` and `multi-dimensional-
epistemic-state-model` show clean, mostly SOURCE-CLAIMED refinement/extension
chains. But `knowledgeos-kernel-concept`'s Wave 2 (F10) proceeds with **zero
contact** with the entire preceding DDD wave (no aggregate/admission/invariant
vocabulary at all) — despite the SAME source document (`S0395`) stating a
governance rule that a brainstorming lens "does not create domain authority."
Later ≠ building-on; sometimes later means restarting.

**5. How often do formulations contradict each other?** Yes, extensively and
explicitly corpus-tagged: `formal-zero-algebra` carries 5 `CONTRADICTION`-typed
rows; `kernel-review-session-findings` and `knowledgeos-kernel-concept` both
carry multiple `SOURCE-CLAIMED-CONTRADICTION` rows. **Important nuance found**:
at least one claimed contradiction (`knowledgeos-kernel-concept` F5↔F6)
*dissolves on closer inspection* into a vocabulary collision, not a real
disagreement — confirmed by the corpus's own second-pass review (`S2-F017`).
Contradiction claims themselves need adjudication, not automatic acceptance.

**6. How often is the same conclusion reached through different paths?**
Found at least twice: `kernel-review-session-findings` row S0612 states
*"Three independent state-set derivations (from epistemic theory,
constitutional governance, evidence/justification theory) converge on
Admitted, Superseded, Reconciled..."*; `formal-zero-algebra`'s S1-F016 tracks
an explicit "arrival count" for its one genuinely corroborated finding
("atomicity not relatedness... the most repeatedly and independently reached
result in the entire corpus") — and the corpus itself distinguishes *counted*
multiplicity from *corroborated* multiplicity, exactly this task's
INDEPENDENT-vs-CORROBORATED distinction.

**7. Does the current P3a/P3b representation lose this distinction?**
**Yes, decisively and structurally.** P3a's candidate generation and
adjudication operate exclusively on *inter-label* pairs (is label A related to
label B). Nothing in the pipeline compares row_i vs row_j *within* one label's
own family. P2b's `derive_families.py` `candidate_births` field picks a single
"earliest matching row" per birth-kind and has no mechanism to flag, let alone
preserve, the existence of later, competing, non-identical formulations under
the same label. A label like `knowledgeos-kernel-concept` — with 9 mutually
inconsistent formal definitions across 3 waves — is currently represented in
the pipeline identically to a label with one clean, unrefined definition: as
one family, one `candidate_births.formal` pointer, one `lifecycle_candidate`.
**This is a real, confirmed representation gap, and it is intra-label, not the
inter-label gap P3a-V2 already repaired.**

**8. Can the existing schema represent the observed cases?** Partially, and
unevenly by case. The 11-value relationship vocabulary was **sufficient for
every pair evaluated in `knowledgeos-kernel-concept`** (0 ONTOLOGY-GAP needed)
but **insufficient at least 4 times in `step-verify-programme`**, for one
specific, recurring, unnamed pattern: *same-session, same-author,
functionally-identical-purpose reformulation, minutes-to-hours apart, partial
vocabulary overlap, zero citation, both left standing as if unrelated*. This is
not SAME, REFINEMENT, EXTENSION, REDEFINITION (no persistent name being
redefined), REPLACEMENT (no explicit claim), DERIVED-FROM, CONTINUATION (too
little continuity), or INDEPENDENT (too much overlap). **New candidate name
proposed** (not adopted, per §6 of the parent task — flagged as
`PROTOCOL-EXTENSION-CANDIDATE`): `PARALLEL-UNRECONCILED`. More fundamentally,
**no existing schema field records that multiple candidates exist for one
label at all** — this is a structural, not merely a vocabulary, gap.

## Two additional findings not asked for, but load-bearing

1. **P1 structural-field capture is wildly inconsistent even within one
   label's own row set.** `step-verify-programme`: 0 of 338 rows carry any
   `dependencies`/`assumptions`/`invariants`/`lineage_claims` at all, despite
   its content being obviously self-referential and self-correcting.
   `kernel-review-session-findings`: 50 of 264 rows (19%) carry a
   `lineage_claims` entry. `multi-dimensional-epistemic-state-model`: a much
   higher, richly-tagged rate (SOURCE-CLAIMED-CORRECTION/REFINEMENT/EXTENSION/
   RETRACTION/CONTINUATION all present). **This inconsistency is a P1
   extraction-completeness issue, distinct from and prior to P3a's candidate-
   generation gap** — no amount of P3a-V2-style repair recovers a claim P1
   never captured as structured data in the first place.
2. **The corpus has already, twice, tried to solve exactly this problem, on
   itself.** `step220-concept-genealogy-method` proposes a full "Concept
   Genealogy Ledger" methodology (first-mention/first-formalization/first-
   adoption tracking, `Convergence(I)` = count of independent discovery paths,
   "concept collision" = SameTerm+DifferentMeaning — a HOMONYM-equivalent
   concept). `knowledgeos-kernel-concept`'s own Session-2 review
   (`S2-F017`/`S2-FINAL-KERNEL-REVIEW.md`) independently re-derived and
   applied almost this exact methodology to the kernel-formulation question,
   correcting a naive "8 formulations" count down to "6 defensible positions"
   by exactly the moves this audit was asked to make (checking untested
   synonymy, vocabulary collisions vs. real disagreement, prompt-lineage-
   caused pseudo-independence). **Both are directly relevant prior art
   already sitting in the corpus, unused by any pipeline phase.**

# Phase 5I — K-2 Definition Integrity and Executable-Semantics Adjudication

**Status: COMPLETE.** Executed under the user's explicit, separate authorization of Phase 5I only
(2026-09-08), following the completed Phase 5H mathematical-closure phase. **Phase 5J, global
reclassification, four-model convergence, unified/canonical Kernel construction, DDD context-map
adoption, and implementation remain unauthorized and untouched.**

## Central question, answered up front (full evidence in `08`/`14`)

*Is K-2 a single mathematically identifiable object with multiple representations, or does the corpus
contain multiple competing K-2 definitions that cannot presently be identified?*

**Answer: K-2 is classified `5. COMPETING OBJECT DEFINITIONS` (see `08`)** — not `1` (single
well-defined object), not `2` (single object, multiple representations, which would require the
representations to be shown compatible), and not `6` (internally incoherent as a whole — most of the
*executable* machinery is internally sound; the incoherence is localized to the Assertion field-set
specifically, not to K-2's own top-level `(𝒜,ℛ)` structure).

## Two genuinely new findings this phase, beyond Phase 5H's own treatment

1. **`t285_reconcile.py`'s own computed output silently reproduces a pre-revision claim D285-1's own
   prose explicitly retracted.** Re-executing the script's own set arithmetic (`03`) shows it computes
   `{Observation, State}` as the "simply ABSENT" bucket — exactly the framing D285-1 §"the lanes agree"
   claim used **before** its own 2026-08-31 revision carved `Observation` out via the Sañjaya
   construction. The script was never updated to reflect the revision — a genuine, machine-observable
   inconsistency between the corpus's own code and its own later prose, not previously identified in
   Phase 5F/5G/5H.
2. **Not all of the executable evidence this reconstruction has relied on is equally computationally
   grounded.** `t285_equality.py`'s "semantic equality" test (`04`) is a genuine, computed subset
   check (`UNPACK ⊆ IMAGE`); its "observational equality" test is a **hardcoded boolean dictionary**,
   not a computed result — the code *asserts* `replay`/`policy-eval`/`authorize` are unanswerable
   rather than *deriving* that from executed behavior. This distinction (real computation vs.
   code-dressed assertion) had not been drawn anywhere in this reconstruction before this phase.

## Frozen starting point

Phase 5H's 9 findings (`01`) are preserved and neither strengthened nor weakened without independent
re-testing. Model A, Model B, Phase 3, Phase 4, Phase 5A–5H, D285-1/D285-6/D285-7, and the three
executable scripts are read-only.

## Artifact map

| File | Contents |
|---|---|
| `00_index.md` | This file. |
| `01_source-definition-register.md` | Every materially distinct Assertion definition, un-merged. |
| `02_assertion-ontology-reconstruction.md` | The A–G classification (§2 of the authorization) for the Assertion object itself. |
| `03_t285-reconcile-semantics.md` | Line-by-line executable-semantics audit of `t285_reconcile.py`. |
| `04_t285-equality-semantics.md` | Line-by-line audit of `t285_equality.py`, including the real-vs-hardcoded distinction. |
| `05_e-equality-semantics.md` | Line-by-line audit of `e_equality.py`. |
| `06_executable-equivalence-audit.md` | The 5 required equivalence relations, tested between the three scripts. |
| `07_qualify-evidence-adjudication.md` | The A–E classification for `Qualify`'s multiple variants. |
| `08_k2-ontology-status.md` | The required 1–7 classification, evidence-backed. |
| `09_projection-revision.md` | Multiple projections (`π₁`, `π₂`, ...), tested for compatibility. |
| `10_information-loss-reassessment.md` | D1–D7, re-checked only where new evidence bears on it. |
| `11_provenance-adjudication.md` | Every edge typed; `DERIVES_FROM` never inferred from sequence alone. |
| `12_mathematical-adjudication.md` | Equivalence-relation axioms (reflexivity/symmetry/transitivity) checked explicitly. |
| `13_ddd-implications-only.md` | Architectural implications named, no DDD pattern declared. |
| `14_final-k2-integrity-matrix.md` | The required 16-question matrix. |
| `15_open-questions-and-source-gaps.md` | What remains, what would close it. |
| `16_verification-and-completion-report.md` | Verification suite; ≥20 raw-source checks; overall status. |

## What this phase does NOT do

Does not modify any frozen artifact, including the three executable scripts (their contradictions are
recorded as evidence, not repaired). Does not declare a DDD context mapping. Does not perform
four-model convergence. Does not construct a unified Kernel. Does not implement anything. Does not
begin Phase 5J.

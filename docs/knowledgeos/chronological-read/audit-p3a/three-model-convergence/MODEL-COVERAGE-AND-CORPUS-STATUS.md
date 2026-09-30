# EXPERIMENTAL — DOES NOT ALTER KNOWLEDGEOS PRODUCTION TRUTH
# Model Coverage and Corpus-Status Report

**Purpose:** (1) establish the evidence-class of every load-bearing source used in this phase before
using it as evidence, per the explicit instruction not to infer corpus status from directory location
alone; (2) build a Model Coverage Matrix (dimensions, not percentages) for the targeted concepts —
Kernel, K_t/Δ_t, Zero, and the most load-bearing C1/C2 concepts. **Date:** 2026-09-20 (artifact-creation date; corrected from an earlier mislabel of 2026-09-21 — not a source-chronology date). **Status:**
EXPERIMENTAL. **Authoritative:** NO. Consolidates what the governing task specified as separate
"Model Coverage Report," "Model Coverage Matrix," and "C1-C2 Load-Bearing Concepts" documents, per
the instruction to use the smallest number of artifacts that preserve provenance and reviewability.

## Statement-type discipline (used throughout this document set)

- **[HISTORICAL FACT]** — the primary corpus explicitly states X (source_id/path cited).
- **[LATER RESEARCH RESULT]** — a `three_model_convergence` (or other post-hoc) research artifact
  established or proposed X, itself secondary/derived, not historical evidence.
- **[ANALYTICAL INFERENCE]** — this investigation's own reasoning, explicitly flagged as such.

Every claim below is additionally tagged `[REUSED-AS-IS]` / `[REUSED-CROSS-VALIDATED]` /
`[NEEDS-INDEPENDENT-VALIDATION]` / `[NEW-THIS-PASS]` per the established discipline from the prior
Three-Model Convergence pass.

**Standing attribution caveat (added on review):** §0 below establishes that
`classification-register.tsv` carries `PENDING_GLOBAL_RECLASS` on all 2,376 rows. Every "Model A/B/
C1/C2" label used in this document — including "C1's kernel candidates," "Model B's K_t/Δ_t thread"
— is therefore shorthand for **"classified by the previous three-model program's initial-only pass
as A/B/C1/C2,"** not an established fact about the source's intrinsic identity. Treat this as
implicit throughout rather than repeated at every occurrence; a future **Model Attribution
Confidence** dimension (`SOURCE-EXPLICIT` / `PROGRAM-CLASSIFIED` / `CORPUS-INFERRED` /
`MULTI-ATTRIBUTABLE` / `UNRESOLVED`) should replace this shorthand in the next phase.

## 0. Corpus-status verification (done before use, not assumed from location)

| Source | Evidence-class determination | Basis |
|---|---|---|
| `docs/knowledgeos/brainstorming/phase_measure_theory/` (569 files) | **`PRIMARY-HISTORICAL-CORPUS`** | **[HISTORICAL FACT]**, verified directly: 894/894 of its roadmap-listed lines appear in the admissible roadmap `20260909-185001_files-to-read-one-by-one.log.md`; 861/894 were actually P1-extracted into `02-FILES.jsonl`; a sampled row (S0760) carries `"provenance": "PRIMARY"`. **Not** part of `three_model_convergence/` (a different, firewalled top-level folder) — it is primary corpus material that `three_model_convergence` *reads and re-classifies*, exactly as `docs/knowledgeos/brainstorming/kernel/` was already confirmed to be for the earlier Derivation Discovery Audit. |
| `three_model_convergence/03_model-b_mathematical/*` (concept register, evidence base) | **`SECONDARY-RESEARCH`** | Self-declared: a later classification/synthesis pass over `phase_measure_theory/` (and other) primary files, using its own `M00xx` internal numbering scheme that does **not** correspond to the primary files' own filenames (verified: a targeted search for filenames matching M0037's claimed date/topic in `phase_measure_theory/` found topically-related `step_2xx`-numbered primary files, not an `M0037`-named file — the `M`-numbering is `three_model_convergence`'s own internal reading-order label, not a corpus artifact). |
| `three_model_convergence/04_model-c_kernel-ddd/*` | **`SECONDARY-RESEARCH`** | Same basis — a classification/synthesis pass over primary `seq`-numbered corpus files (0001–2377 range), citing `seq` IDs that are directly traceable to `classification-register.tsv` paths. |
| `three_model_convergence/00_control/classification-register.tsv` | **`DERIVED-ANALYSIS`** (a working index, not itself evidence of any concept) | Its own `final_primary`/`final_secondary` columns are **`PENDING_GLOBAL_RECLASS` for every single one of its 2,376 data rows**, confirmed by direct inspection — i.e. **the A/B/C1/C2 model attribution used throughout `three_model_convergence` rests entirely on `initial_primary` tags that the program's own control apparatus explicitly records as never finalized.** This is a load-bearing caveat for everything below, not a new problem introduced by this pass. |

**[NEW-THIS-PASS] — a finding not previously surfaced:** `classification-register.tsv` shows that of
the 894 `phase_measure_theory/` rows, **522 carry `initial_primary = engineering_knowledgeos` (C1)**
and only a handful carry other tags.

**CORRECTED on the follow-on BC-02 pass (`BC-02-KT-DELTA-CHRONOLOGICAL-RECONSTRUCTION.md`):** this
document originally stated that Model B's `M0005`–`M0132` material lives in the *same folder* as
C1's material, one week apart. **Direct verification of the actual primary paths behind
`three_model_convergence`'s `M`-numbering (via its own per-file `.yaml` records) shows this is
incorrect.** Model B's sources are in `docs/knowledgeos/brainstorming/mathematical_ideas_that_can_be_
implemented/`, dated 2026-09-01/02 — a **different top-level folder** from `phase_measure_theory/`.
A direct search of all 569 `phase_measure_theory/` files (including subfolders) for Model B's own
signature terms found zero matches, confirming the folders are genuinely distinct. The corrected,
narrower finding: two threads in **different folders**, roughly one week apart, with Model B's own
primary sources carrying **mixed provenance** (`M0005`'s file is `SECONDARY-SYNTHESIS`, `M0132`'s is
`PROVENANCE-UNRESOLVED`, only the separate kernel-operator sub-thread `M0037` is confirmed `PRIMARY`)
— itself a more consequential caveat than the folder question. See BC-02 for the full corrected
analysis; the "same folder" framing should not be reused from this document going forward.

## 1. Model Coverage Matrix — Kernel

Dimensions per the task's 8-state model (NOT PRESENT / MENTIONED / PARTIALLY DEFINED / DEFINED /
FORMALLY DERIVED / VALIDATED / COMPUTABLE-OPERATIONALIZED / IMPLEMENTED), with evidence-granularity
tags (`EXPLICIT` / `DERIVED` / `INFERRED` / `PARTIAL` / `NOT-FOUND-IN-SEARCH` /
`INSUFFICIENT-EVIDENCE` / `NOT-APPLICABLE`):

| Dimension | Model A | Model B | C1 | C2 |
|---|---|---|---|---|
| Concept present? | YES (`EXPLICIT`) | YES (`EXPLICIT`) | YES (`EXPLICIT`) | YES (`EXPLICIT`) |
| Definition status | PARTIALLY DEFINED — 4 mutually-`unresolved_equivalence` typed-schema candidates, no single stated definition (`PARTIAL`) | PARTIALLY DEFINED at the population level (4 kernels *exist*) but **not individually defined** in the secondary register (`INSUFFICIENT-EVIDENCE` for per-kernel membership; `EXPLICIT` for the count/conclusion) | DEFINED, richly — at least 8 named candidates *with stated membership* (K1-K8; Six Pillars; six-part aggregate; K-1 tuple; `S_Kernel=(D,E,S,T,U)`; `𝒦_core`; `K_t=(𝒜_t,ℛ_t,ℰ_t,ℋ_t,𝒵_t,ℒ_t)`) (`EXPLICIT`) | DEFINED — one candidate, `𝒞=(D,P,T,C,I,E,R,H,Θ)`, raw-source-verified (`EXPLICIT`) |
| Object identified? | YES, multiple non-identical objects (`EXPLICIT`) | YES, an operator-set object, population of 4 (`EXPLICIT`) | YES, multiple non-identical aggregate objects (`EXPLICIT`) | YES, one relational-temporal structure (`EXPLICIT`) |
| Derivation present? | NO ablation/irreducibility test in A's own evidence (`NOT-FOUND-IN-SEARCH`, not `NOT-APPLICABLE` — absence is evidenced, not assumed) | YES — a genuinely executed ~150,000-trial ablation (`EXPLICIT`, `[LATER RESEARCH RESULT]`) | Mixed: P-3 (six-part aggregate) has an executed falsification test; P-7 (Fagin/Kripke) has an executed demotion; most of the remaining 6+ candidates are `PROPOSED → UNTESTED` (`PARTIAL`) | Not an ablation/falsification-style derivation — a synthesis/definitional derivation from Roberts's measurement-governance gate and Shani's invariant (`EXPLICIT`, different *kind* of derivation than B's) |
| Conclusion/result | Multiple unresolved candidates, never converged (`EXPLICIT`) | "13-operator kernel is not uniquely minimal; four cardinality-8 kernels exist; minimality is representation-dependent" (`EXPLICIT`) | "At minimum eight independently-motivated Kernel definitions exist, unreconciled" (`EXPLICIT`) | One integrated candidate definition, explicitly left open re: reconciliation with C1 (`EXPLICIT`) |
| Dependencies known? | Each candidate's own admission-test lineage (`PARTIAL`) | Representation/semantic-algebra choice is explicit and load-bearing (`EXPLICIT`) | Per-candidate, uneven — some (K-1) have an ADR-level dependency trail; most do not (`PARTIAL`) | Explicit: Roberts's gate, Shani's invariant, four prior formal definitions (`EXPLICIT`) |
| Assumptions explicit? | Admission-test discipline stated (`EXPLICIT`) | Representation-dependence stated as the central finding (`EXPLICIT`) | Uneven, `PARTIAL` | Explicitly rejects an alternative ("original Rudin synthesis") — assumptions stated by exclusion (`EXPLICIT`) |
| Empirical validation | NONE found (`NOT-FOUND-IN-SEARCH`) | YES for the falsification of "13 is unique" (`EXPLICIT`); NO for validating any specific one of the 4 as canonical (`NOT-FOUND-IN-SEARCH`) | YES for P-3, P-7 (executed tests); NO for the majority (`PARTIAL`) | NONE found — `PROPOSED → UNTESTED` per the C1/C2 kernel-candidate table's own P-12 row (`NOT-FOUND-IN-SEARCH`) |
| Computational realization | NOT PRESENT (`NOT-FOUND-IN-SEARCH`) | NOT PRESENT beyond the ablation-test harness itself (`INSUFFICIENT-EVIDENCE` whether that harness constitutes "computational realization" of the kernel concept vs. a test of it) | NOT PRESENT in this register (`NOT-FOUND-IN-SEARCH`) | NOT PRESENT (`NOT-FOUND-IN-SEARCH`) |
| DDD/engineering realization | NOT APPLICABLE (A is not engineering-oriented) (`NOT-APPLICABLE`) | NOT APPLICABLE at this stage (`NOT-APPLICABLE`) | This IS C1's own domain — K-1's `ADR-KOS-KERNEL-001` is the closest to a formal engineering artifact, itself un-adopted (`PARTIAL`) | C2's candidate is itself framed as "the smallest domain-independent bounded context..." — a DDD-flavored definition, but untested (`PARTIAL`) |
| Provenance quality | Traceable to named seq IDs (`EXPLICIT`) | Traceable to `M`-numbered secondary labels, whose underlying primary filenames were **not** recoverable within this pass's time-box (`INSUFFICIENT-EVIDENCE` for the exact primary source of any one B-kernel) | Traceable to `seq` IDs directly matching `classification-register.tsv` paths (`EXPLICIT`) — **one entry (K-1/P-5) was itself already corrected once by the prior program's own 2026-09-07 raw-source spot-check**, a good discipline precedent | Raw-source-verified explicitly by the prior program's own account ("full raw-source read... single most consequential file") (`EXPLICIT`) |
| Missing pieces | A structure-preserving map to any other model's Kernel object | Individual membership for 3 of its own 4 discovered minimal kernels | A reconciliation across its own 8+ candidates | Reconciliation against C1's candidates (explicitly an open question in the source file itself) |

## 2. Model Coverage Matrix — K_t / Δ_t

| Dimension | Model A | Model B | C1 (`phase_measure_theory` Aug-tagged sub-thread) | C2 |
|---|---|---|---|---|
| Concept present? | NOT PRESENT (`NOT-FOUND-IN-SEARCH` — A's apparatus is pramāṇa/lens-based) | YES (`EXPLICIT`) | YES (`EXPLICIT`) | NOT independently confirmed in this pass (`INSUFFICIENT-EVIDENCE`) |
| Definition status | NOT APPLICABLE | DEFINED, FORMALLY DERIVED — frozen at M0132: `Δ_t = {r ∈ R_t : Sat(K_t,r)=0}` (`EXPLICIT`) | DEFINED, at least at S0760: `K_t=(D_t,V_t,R_t,E_t,Σ_t,τ_t)` — a **[HISTORICAL FACT]**, directly read from the primary file, not from the secondary register (`EXPLICIT`) | `INSUFFICIENT-EVIDENCE` |
| Derivation present? | NOT APPLICABLE | YES — iterative refinement chain M0005→...→M0132 (`EXPLICIT`, `[LATER RESEARCH RESULT]` characterization of a `[HISTORICAL FACT]` chain) | Present within S0760 itself (rejects a naive global-percentage completeness formula, replaces it with reference-relative `C(K_t\|R)`) — `[HISTORICAL FACT]` | `INSUFFICIENT-EVIDENCE` |
| Dependency identity vs. Model B | NOT APPLICABLE | **Zero citation link to the C1 thread, in either direction** — confirmed by the prior program's own raw-source check (`[LATER RESEARCH RESULT]`, `[REUSED-AS-IS]`) | Same | NOT APPLICABLE |
| Coverage relationship | NOT APPLICABLE | Both threads independently produce a `K_t`-typed tuple; **[NEW-THIS-PASS]** both live in the same primary-corpus folder, ~1 week apart | Same | NOT APPLICABLE |

## 3. Model Coverage Matrix — Zero

| Dimension | Model A | Model B | C1/C2 |
|---|---|---|---|
| Concept present? | YES (`EXPLICIT`) | YES, split into Zero Lens / Zero Closure (`EXPLICIT`) | `NOT-FOUND-IN-SEARCH` within this pass's time-box (not confirmed absent — simply not searched at this granularity) |
| Definition status | DEFINED as a "meta-principle," explicitly denied kernel-membership (`EXPLICIT`) | Zero Lens: DEFINED (`[PR]`); Zero Closure: PARTIALLY DEFINED, an open predicate (`[OP]`) (`EXPLICIT`) | `INSUFFICIENT-EVIDENCE` |
| Derivation present? | Yes, via the admission-test procedure (`EXPLICIT`) | Yes — Zero Closure's negative result is a formal impossibility argument (`EXPLICIT`) | `INSUFFICIENT-EVIDENCE` |
| Validation | Consistent, repeated application across 4 files (`EXPLICIT`) | Zero Closure: a proven impossibility result (`EXPLICIT`); Zero Lens: none found (`NOT-FOUND-IN-SEARCH`) | `INSUFFICIENT-EVIDENCE` |
| Coverage relationship A↔B | Different questions (architectural-admission attitude vs. computability of an operator) — see `TARGETED-BRIDGE-AND-KERNEL-ANALYSIS.md` | | |

**Separately, [REUSED-AS-IS] from this session's own prior Derivation Discovery Audit (not
`three_model_convergence`):** the corpus's own `formal-zero-algebra` register documents **9 distinct
signatures of Zero**, with 5 explicit CONTRADICTION rows — an intra-corpus finding orthogonal to the
A-vs-B axis above, not merged into it here (consistent with the disposition already recorded in
`THREE-MODEL-CONTRADICTION-REGISTER.md` CT-B2).

## 4. Most load-bearing C1 concepts, and why each was selected

Selection criterion (per the task): architectural importance, dependency centrality, downstream use,
role in the proposed theory — established from C1's own evidence structure, not from the prior
program's narrative framing.

- **The DDD Kernel-definition family (§L of C1's register)** — selected because it is the single
  largest, most-cited concept cluster in C1 (at least 8 independently-motivated candidates,
  `[CG]`/`unresolved_equivalence` throughout), and is the direct C1-side counterpart to Model A's and
  Model B's own kernel proliferation — the concept every cross-model kernel bridge in this document
  set must reference.
- **The kernel-candidate table (§N, P-1 through P-12)** — selected because it is the only place in
  C1's evidence that applies a disciplined, falsifiable **verification state** (not just an inventory)
  to those 8+ candidates, mirroring Model B's own §P table — directly comparable structure across
  models, useful for the bridge matrix.
- **K-1 = KnowledgeAggregate+ConflictRecord+VerificationPort (0165/0167, P-5)** — selected because it
  is the only C1 kernel candidate that reached a formal ADR (`ADR-KOS-KERNEL-001`), and because the
  prior program's own 2026-09-07 correction (originally misattributed origin, corrected via raw-source
  spot-check) makes it a documented instance of the self-correction discipline this whole audit chain
  values — worth carrying forward as a worked example, not just a data point.

## 5. Most load-bearing C2 concepts, and why each was selected

- **Seq 2330 — "Relational Structure as the Mathematical Core," `𝒞=(D,P,T,C,I,E,R,H,Θ)`** — selected
  because it is, by the prior program's own account, **the sole C2 candidate** and was subjected to a
  full raw-source read under an explicit heightened-verification requirement ("the single most
  consequential file in the entire phase"). There is no second C2 concept to compare it against
  within this evidence base — its load-bearing status is not a selection choice among alternatives,
  it is the entirety of what C2 currently contains at this level of maturity.
- Four sub-components worth separate tracking because they recur elsewhere in this document set:
  the four non-unifiable convergence notions (semantic/metric/probabilistic/logical-stabilization),
  Roberts's measurement-governance gate, Shani's invariant, and the file's own explicit,
  unresolved request to reconcile its DDD Kernel definition against C1's candidates — this last item
  is effectively a **self-declared candidate bridge**, already proposed by the primary research
  itself, not invented by this audit.

## 6. What this document does not do

Does not assign a completeness percentage to any model. Does not conclude that any dimension's
`NOT-FOUND-IN-SEARCH` state means the concept is absent from the corpus — only that it was not found
within this pass's bounded search. Does not treat C1's richer per-candidate membership detail as
evidence that C1 is "more complete" than B in any general sense — only that, *for the Kernel concept
specifically*, C1's secondary register happens to preserve individual candidate definitions where B's
happens to preserve only the population-level count and conclusion.

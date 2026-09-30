# EXPERIMENTAL — DOES NOT ALTER KNOWLEDGEOS PRODUCTION TRUTH
# Pre-BC-02.13 — Experimental Evidence Discovery and Audit

**Date:** 2026-09-21. **Status:** EXPERIMENTAL, bounded evidence-discovery pass. **Authoritative:**
NO. Companion document to `BC-02-KT-DELTA-CHRONOLOGICAL-RECONSTRUCTION.md` — kept separate because
it audits a **different evidence tier** (executable experimental code and results at the repo root:
`research/`, `verification/`, `review/`) rather than the historical brainstorming corpus. Per
explicit instruction: **BC-02.13 is not executed or re-executed here; this pass stops before it.**

**Provenance discipline used throughout**: `PRIMARY-HISTORICAL` (the brainstorming corpus) /
`EXPERIMENTAL-EXECUTED` (code + results with execution evidence) / `EXPERIMENTAL-DESCRIPTION`
(design docs, not run) / `SECONDARY-SYNTHESIS` / `ANALYST-INFERENCE` / `UNKNOWN`. Evidence-level
scale `E0`–`E5` per the commissioning's own hierarchy, applied per experiment.

## 1. Executive Finding

**PARTIAL — IMPORTANT NEW EVIDENCE, NO FULL RESOLUTION.**

**Corrected on review — terminology.** The experimental repositories contain a **real, executable,
deterministic, reproducible** kernel-reduction codebase (`research/kernel-reduction/`) that
provides **`E4` = repository-reproducible evidence** (reproducible from the repository, `python3
run_all.py`, ~30s, no dependencies) for the two most load-bearing narrative claims this whole BC-02
investigation has been carrying at only `E0`/`E1` confidence: the 13-operator `C0` baseline, and
**four cardinality-8 covering sets found within the tested `V0`–`V12` representation family**
(corrected from "exactly four cardinality-8 minimal kernels," which overstated scope — see §4 and
§9 below for the precise, scope-attached statement). **`E4`/repository-reproducible is not the same
claim as "independently validated"** — independent validation would require a separate
implementation, extractor, or verifier, none of which is established here. This is a genuine,
material upgrade for those two specific claims, precisely scoped.

It also surfaces a **profound, directly relevant, previously-unknown fact**: `research/
knowledgeos-sim/kos/types.py` contains an **executable notation-collision registry**
(`COLLISIONS = {k:v for k,v in NOTATION.items() if len(v)>1}`) that explicitly, programmatically
flags the symbol `"K"` as overloaded across three distinct definitions — `KnowledgeState K_t
(DEF-11)`, `Kernel 𝒦 (DEF-32)`, and `Knowledge Space 𝕂` — independently corroborating, via
executable code rather than historical-corpus archaeology, the exact "same symbol, different
concepts" problem this entire BC-02.5–BC-02.13 arc has been reconstructing by hand.

It does **not** resolve the `K_t¹¹`/`K_min⁴`/canonical-construction-`K`/ratified-8-primitive-`K_t`/
`K=(𝒜,ℛ)` relationship question — none of the five historical formulations investigated in BC-02.5–
13 appears anywhere in this codebase, and this codebase's own `K_t = Γ(E_t,…)` is an **eighth,
independently-defined shape**, unconnected by citation to any of them.

## 2. Experimental Repository Inventory

| Area | Files | Character | Priority (per commission) |
|---|---|---|---|
| `research/kernel-reduction/` | 39 (9 modules + `run_all.py` + `README.md` + 12 result JSON, plus `__pycache__`) | Real Python package, deterministic, documented, self-labeled `[EXP]` | Highest — fully audited this pass |
| `research/knowledgeos-sim/` | 214 (`kos/`, `kos12/` packages, 8 `run_*.py` entry points, large `results/` tree) | Real Python simulation suite with an explicit notation-collision registry | Second — structurally surveyed, one file read in depth |
| `verification/zero-algebra/` | 209 (13+ named `KR-*` campaigns, each with design/audit/results docs and some with `code/`) | **Mixed**: some campaigns executed (`KR-BRIDGE-01`, pre-registered, audited), at least one explicitly `[DESIGN] — NOT RUN, NOT AUTHORIZED` (`KR-STATE-01`) | Third — surveyed at the structural/title level only, two files read directly |
| `review/` | 5 | Confirmed unrelated: PB003 architecture certification (the Public Digit Voting Platform track, a different business domain in this same repo) | Confirmed unrelated, per §2D — no further effort spent |

## 3. Executed Experiments (evidence levels)

| Experiment | Evidence level | Basis |
|---|---|---|
| `kernel-reduction` baseline (`C0`, 13 ops, `minimal_kernels.json`) | **E4** | `run_all.py` regenerates deterministically; seeds `[1,7,13,101,2718]` stated in `README.md` and in the result file's own `_meta` |
| `kernel-reduction` extended search (`audit_extended_search.json`, `V0`–`V12`) | **E4** | Same package, same regeneration path, `_meta.addendum: "18-audit-response"` shows it is itself a response to a prior audit — a disclosed, not hidden, revision |
| `knowledgeos-sim` notation-collision registry (`kos/types.py`) | **E1–E2** for the registry mechanism itself (real code, structurally simple, no dedicated test file found in this pass); the *simulations that consume it* were not run in this pass | Read directly; not executed by this audit (per explicit instruction not to run experiments) |
| `KR-BRIDGE-01` (zero-algebra) | **E3** (audit + results documents describe pre-registration and execution; this pass did not re-execute or independently verify the persisted data) | `KR-BRIDGE-01-AUDIT-2026-09.md`, read directly |
| `KR-STATE-01` (zero-algebra) | **E0** — explicitly self-declared: `"[DESIGN] — NOT RUN, NOT AUTHORIZED. Design only."` | Read directly, source's own header |

## 4. Kernel-Reduction Reconstruction

**Mathematical object, exactly as implemented** (`kr/operators.py`, `kr/atoms.py`, `kr/reach.py`,
`kr/capabilities.py`): an `Op` is a named operator with a required-atom-set and a strength tag
("STRONG"/etc.); `C0` is a fixed dict of 13 such `Op` objects; `C0_PLUS = C0 ∪ {Qualify}` (14
operators) is the "repaired baseline." `Reach(S)` is an explicit **least-fixpoint** computation
over typed composition (per `README.md`'s own description) — i.e., which capabilities (`C1`–`C25`)
are reachable from a given operator subset, under a 14-atom "anti-smuggling" semantic vocabulary
(`atoms.py`) designed specifically to prevent capability requirements from silently encoding
operator identity (the exact "bijection-flaw" self-critique the brainstorming corpus's own `M0037`
narrative described — now confirmed to correspond to a real, named design safeguard in the actual
code, not merely a retrospective narrative claim).

**Algorithm, reconstructed from the code, per the commissioning's 16-point list**:
1. **Initial object**: `C0` (13 named operators) or `C0_PLUS` (14, with `Qualify` added).
2. **Operators**: named in `C0_NAMES`; not restated symbol-by-symbol here (14 total, see `README.md`
   Table + `atoms.py`).
3. **What each removes/changes**: an operator's absence removes its contribution to the required-
   atom coverage computed by `Reach`.
4. **Valid reduction**: a subset of operators whose `Reach` still covers all 25 capabilities
   (`C1`–`C25`) and all 16 deterministic scenarios (`kr/scenarios.py`).
5. **Failed reduction**: a subset whose `Reach` fails to cover some required capability/scenario.
6. **Candidate kernel**: any operator subset achieving full coverage.
7. **Minimality**: **cardinality-minimal** among covering subsets found by the search — **not**
   proven globally minimal over all conceivable operator sets, only minimal *within the searched
   representational variants* (`V0`–`V12`).
8. **Type of minimality**: cardinality-minimal, representation-relative (the code's own `V0`–`V12`
   variants are explicitly alternative *representations*, and minimality is shown to differ across
   them — this matches, and is the primary source for, the "representation-dependent minimality"
   claim already carried in the historical corpus narrative).
9. **Equivalence between kernels**: **not explicitly implemented as a formal equivalence relation**
   in the code read this pass — the four cardinality-8 kernels are reported as a list of four
   distinct sets, with no `equivalence_class` or similar structure found. **`EQUIVALENCE NOT
   DEFINED`**, per the commissioning's own required fallback.
10. **Invariance tested**: band/variant-invariance of *coverage*, not of kernel *identity*.
11. **Objective function**: none explicit — this is a covering-set search, not an optimization with
    a scalar objective.
12. **Search space**: the power set of the 13/14-operator universe, restricted by the `Reach`
    coverage constraint.
13. **Exhaustive or sampled**: `ablate.py`'s leave-one-out and pairwise tests are exhaustive over
    single/pairs; the full minimal-kernel search combines this with `V0`–`V12` representational
    enumeration (12 variants, not sampled) — the *variant* dimension is exhaustive-over-12, not a
    sample of a larger unstated variant space.
14. **Deterministic**: yes, confirmed by direct code reading (no unseeded randomness in the control
    flow read); the 5 named seeds are used for whatever randomized property checks exist in
    `kr/properties.py` (P1–P10), separate from the deterministic scenario/capability coverage logic.
15. **Randomness**: present in `kr/properties.py`'s randomized property checks; **not** present in
    the core minimal-kernel covering-set computation itself, which is deterministic combinatorics.
16. **Seed/control**: `[1, 7, 13, 101, 2718]`, stated in `README.md` and reproduced in every result
    file's own `_meta.seeds` field — `[SOURCE FACT]`, cross-checked directly.

## 5. Statistical / Reproducibility Audit

- **Trial count, precisely reconciled**: every one of the 9 primary result files (`baseline`,
  `variants`, `smuggling`, `ablation_loo`, `ablation_pairwise`, `ablation_atoms`, `randomized`,
  `minimal_kernels`, `information_causal`) states `"trials_per_seed": 2000` with 5 seeds — **10,000
  trials per file**, not "~150,000" for any single file. **`audit_extended_search.json`'s** `V0`–
  `V12` variant sweep is structured differently (a 12-variant × 14-operator derivability grid, not
  a seeded-trial count) and does not itself state a trial figure.
- **Reconciling the "~150,000 trials" narrative claim**: `9 files × 10,000 trials/file = 90,000` —
  close to but not equal to 150,000. **`ANALYST-INFERENCE`, not confirmed**: the historical
  narrative's "~150,000" figure is plausibly an aggregate across the whole campaign (including
  `audit_extended_search`'s own additional derivability checks across 12 variants × ~14–18
  operators × 16 scenarios, which were not counted here as discrete "trials" in the same sense) —
  but this reconciliation was not verified by re-running or by finding an explicit aggregate count
  anywhere in the repository. **The precise mapping from "~150,000" to a specific sum of these
  files' trial counts remains `UNRESOLVED`** — recorded honestly rather than forced.
- **Design properties**: exhaustive-over-12-variants and exhaustive-over-pairs/singles for the
  ablation tests (not a sampled experiment in that dimension); genuinely random-with-fixed-seeds for
  the property-check trials; no explicit control group in the conventional statistical sense (a
  closed combinatorial + property-check design, not a between-groups experiment); reproducible
  (`E4`, confirmed via `README.md`'s own one-line regeneration instruction and the `_meta` blocks
  present in every output file); no significance testing performed or claimed by the source itself.
- **What the experiment actually falsifies/supports**: it supports "the 13-operator `C0` baseline is
  not uniquely minimal once alternative representations are considered" and "exactly four
  cardinality-8 covering sets exist among the `V0`–`V12` representational variants tested." It does
  **not** support "8 is the globally minimal cardinality across all conceivable representations" —
  only 12 variants were tested, not an exhaustive representation space (which is not even well-
  defined as finite).

## 6. KnowledgeOS Simulation Audit (`research/knowledgeos-sim/`)

Structurally surveyed (8 `run_*.py` entry points: `run_comp`, `run_contr`, `run_contr2`, `run_fde`,
`run_reiter`, `run_satc`, `run_v12`, `run_experiment`; two packages `kos/` and `kos12/`; a large,
organized `results/` tree matching most `run_*` names plus `zero/`, `closure/`, `history/`,
`hilbert/`, `evaluation/`, `audit/`, `yz/`, `neff/`, `dist/`, `comp/`). **Not exhaustively read**,
per the commissioning's own instruction not to treat all 214 files equally and per this session's
time-boxing discipline. **One file read in full depth**, because it is directly, unexpectedly
relevant: `kos/types.py`.

**`kos/types.py`'s notation-collision registry** — `[EXPERIMENTAL-EXECUTED]`, read directly:

```python
NOTATION = {
  ...
  "K": ["KnowledgeState K_t (DEF-11)", "Kernel 𝒦 (DEF-32)", "Knowledge Space 𝕂"],
  ...
}
COLLISIONS = {k: v for k, v in NOTATION.items() if len(v) > 1}
```

This is a **real, executable data structure** whose explicit purpose is to flag symbol overload —
independently, and without reference to any of this BC-02 investigation's own findings, this
codebase already treats `"K"` as ambiguous across (at least) three named definitions. A separate
line defines this codebase's own `K_t`: `"KnowledgeState": (...,"K_t = Γ(E_t,…)", ...)` — an
**eighth distinct formal shape** for `K_t` across this whole investigation (alongside S0760's
tuples, `"M0005"`'s distance function, `"S2377"`'s set-builder, `K_t¹¹`'s 11-tuple, the ratified
8-primitive `K_t`, the canonical-construction `K=(D_t,A,R,Σ_c,E_L)`, and `K=(𝒜,ℛ)`). **No source
read in this pass connects this codebase's `K_t=Γ(E_t,…)` to any of the other seven.**

## 7. Zero / Algebra Verification Audit (`verification/zero-algebra/`)

**Surveyed at the structural/title level; two files read directly, per the explicit lower-priority
ranking for this area.** Confirmed: this is a **mixed** collection, not uniformly executed or
uniformly design-only:

- `KR-BRIDGE-01` — **executed** (`KR-BRIDGE-01-AUDIT-2026-09.md`, read directly): explicit pre-
  registration discipline ("labels... are pre-registered design classifications, not established
  facts, verified after execution"), a real `run_bridge.py`, an audit document written specifically
  to precede the results document. `E3` (execution evidence present; this pass did not
  independently re-verify the persisted data against the code).
- `KR-STATE-01` — **not executed**, `[DESIGN] — NOT RUN, NOT AUTHORIZED`, explicitly self-declared,
  and explicitly states "**kernel NOT SELECTED**" and "multiple non-equivalent zeros imply `K_t` is
  not scalar" as a **design hypothesis**, not a result. `E0`.
- Other campaigns (`KR-ZERO-ALGEBRA`, `KR-ZERO-GROUP`, `KR-ZERO-ORDER`, `KR-REP-REDUCTION`,
  `KR-BRIDGE-02`/`03`, `KR-ZOOM-01`/`02`/`03`, `KR-ZOOM-OUT-01`/`02`/`03`) were **not individually
  read this pass** — each has a design/audit/results document set suggesting the same disciplined
  pre-registration pattern as `KR-BRIDGE-01`, but this was not verified file-by-file. Recorded as
  `NOT-AUDITED-THIS-PASS`, not assumed either executed or not.
- **Bearing on "Kernel"**: `KR-STATE-01`'s own design note ("kernel NOT SELECTED... multiple non-
  equivalent zeros imply `K_t` is not scalar") is the only direct textual link found between this
  folder and the Kernel/K-state question — and it is explicitly a **design hypothesis, not a
  result**, since the experiment was never run.

## 8. Qualify Investigation

**Disposition: `QUALIFY-EXISTS-SAME` (name and signature) at the operator-contract level, `NOT-
COMPUTABLE-AS-A-REAL-FUNCTION` (consistent with, not contradicting, BC-02.13's `t285_reconcile.py`
finding).**

`kr/operators.py`, read directly:
```python
# The corpus records Qualify : Observation x Policy -> Evidence as an
# irreducible gap (G1).  C0 contains NO operator holding this atom.
QUALIFY = Op("Qualify", {A_QUALIFICATION}, "STRONG",
             "Qualify: Observation x Policy -> Evidence, recorded G1/irreducible in prior lane")
```

- **Exact signature match**: `Observation × Policy → Evidence` — identical to `t285_reconcile.py`'s
  missing function.
- **The comment explicitly cross-references "a prior lane"** — `[EXPERIMENTAL-EXECUTED, SOURCE
  FACT]` for the comment's existence; `[UNRESOLVED]` whether "prior lane" means the same
  `t285_reconcile.py`/verification-lane thread BC-02.13 investigated, or an independently-arrived
  same conclusion — no explicit citation (file path, source ID) is given.
- **What kind of "existence" is this?** `Qualify` here is a **typed operator contract** (a named
  node with a declared required-atom-set) consumed by the `Reach` fixpoint computation for
  capability-coverage purposes — **not** a concrete, callable `Observation → Evidence`
  transformation with real business logic. This is consistent with `t285_reconcile.py`'s own
  finding that `Qualify` has no body — here, too, it is a **declared contract, not an implemented
  function** — but the fact that *this* codebase independently arrived at the identical need to
  name and type this exact gap is itself new, corroborating evidence that the gap is real and
  recognized across at least two independent experimental threads, not a single tracer's artifact.
- `Qualify` appears in **all four** of the cardinality-8 minimal kernels found in
  `audit_extended_search.json`'s `v12_minimal_kernels` — i.e., this codebase's own executed search
  treats `Qualify` as **necessary** for minimal coverage, even though it cannot be executed as a
  real function. This is a notable, source-grounded tension worth naming: a component can be
  *necessary for a formal coverage result* while *not computable as an implementation* — exactly
  the "semantic necessity ≠ representation necessity" distinction already found in
  `03-CANONICAL-K.md` (BC-02.13), now independently echoed in a third source.

## 9. Kernel ↔ K_t Relationship

Evaluated against the seven hypotheses (§15 of the commissioning), for **the kernel-reduction
codebase's `C0`/`C0_PLUS` Kernel specifically** (the object with the strongest evidence base found
this pass):

- **A (same object as `K_t`)**: `UNSUPPORTED` — the codebase's own `README.md` explicitly states
  "Nothing here is canonical... not KnowledgeOS architecture," and no field/structural
  correspondence to any of the seven historical `K_t` shapes was found.
- **B (a reduced representation of `K_t`)**: `UNSUPPORTED` — no source connects them.
- **C (a minimal sufficient representation of `K_t` for a task)**: `UNSUPPORTED` — no named task
  linking this Kernel to any specific `K_t` formulation was found.
- **D (a quotient/equivalence class of `K_t`)**: `UNSUPPORTED` — no equivalence relation is even
  defined for the Kernel's own four minimal variants (§4, point 9), let alone one relating it to
  any `K_t`.
- **E (a different mathematical object)**: **`PARTIALLY SUPPORTED`** — the object is an *operator
  set* (a capability-coverage covering set), structurally the same *category* of object as `"S2377"`
  §2's "Model B" operator-set Kernel already found (BC-02.6-era work) to be categorically different
  from the *aggregate/tuple* `K_t` shapes — consistent with, and independently reinforcing, that
  categorical (noun-vs-verb) distinction from earlier in this investigation.
- **F (a computational artifact that should not be identified with the theoretical K-state)**:
  **`SUPPORTED`** — the source's own explicit self-labeling ("research instrument, not KnowledgeOS
  architecture... do not import from production code") is a direct, source-stated instance of
  exactly this disposition.
- **G (unresolved)**: not selected — the evidence for F is direct and source-stated, not merely a
  default.

**No ranking is implied by F being "supported" over the others** — F is supported because the
*source itself* makes this claim explicitly, which is the strongest form of evidence available
(a `SOURCE FACT`, not an analyst inference).

## 10. Cross-Source Comparison Matrix

| Object / Experiment | Source | Type | Definition | Implementation | Execution Evidence | Mathematical Result | Validation | Relation to K_t | Confidence |
|---|---|---|---|---|---|---|---|---|---|
| kernel-reduction Kernel (`C0`) | `research/kernel-reduction/` | Operator set | 13 named operators | Yes, `kr/operators.py` | `E4` | 13-op baseline falsified as uniquely minimal | Deterministic, reproducible | `UNSUPPORTED` (F: explicitly not architecture) | High (for what it claims) |
| Four cardinality-8 covering sets (within tested `V0`–`V12` representations) | Same | Operator sets | Explicit lists, `v12_minimal_kernels` | Yes | `E4` (repository-reproducible) | Confirmed by direct read of result JSON | Deterministic, reproducible within the tested family; global minimality not established; equivalence among the 4 `NOT DEFINED` | `UNSUPPORTED` | High (existence within tested scope); `EQUIVALENCE NOT DEFINED` (relatedness) |
| `Qualify` (kernel-reduction) | Same | Operator contract | `Observation×Policy→Evidence` | Contract only, no body | `E2` (typed, used in `Reach`, not itself executable as a transformation) | Included in all 4 minimal kernels | Not independently validated as computable | Same signature as `t285_reconcile.py`'s missing function — `UNRESOLVED` whether same lineage | Medium |
| `K_t¹¹` | `docs/knowledgeos/brainstorming/...` (M0125–M0132) | Flat 11-tuple | Stated, unelaborated | None found | `E0` (BC-02.11) | None | `NOT ESTABLISHED` (BC-02.11) | — | Low (per BC-02.5–11) |
| `K=(D_t,A,R,Σ_c,E_L)` | `verification/canonical-construction/` (repo-root, distinct from the "verification" subfolder inside `docs/knowledgeos/brainstorming/`) | Aggregate/tuple | Removal-tested | Yes (`bandtest.py`) | `E4` (BC-02.13) | Band-invariant necessity | Semantic-necessity only, self-disclosed | `UNSUPPORTED` to `K_t¹¹`; `UNRESOLVED` to this pass's kernel-reduction Kernel or `knowledgeos-sim`'s `K_t=Γ(E_t,…)` | Medium-high (scoped) |
| Ratified 8-primitive `K_t` / `K=(𝒜,ℛ)` | `t285_reconcile.py` (BC-02.13) | Aggregate vs. operator-pair | Model C/D/E hold | Yes | `E4` | Projection well-defined, not computable | Sufficiency tested, fails | `UNRESOLVED` to this pass's findings | Medium-high (scoped) |
| `knowledgeos-sim`'s `K_t=Γ(E_t,…)` | `research/knowledgeos-sim/kos/types.py` | Functional definition | Stated in a notation table | Package exists (`kos/state.py`, `kos/transitions.py`), not read in depth this pass | `E1`–`E2` (code exists; execution of the specific `K_t` transition logic not verified this pass) | Not evaluated this pass | Not evaluated | **Eighth distinct shape**, `UNWITNESSED` relative to all others | Low-medium (structurally noted only) |
| `knowledgeos-sim` notation-COLLISIONS registry | Same file | Executable data structure | `K` flagged as 3-way overloaded | Yes, read directly | `E1`–`E2` | Confirms symbol ambiguity is a recognized, coded concern in this codebase | Not itself a validated theorem, a design safeguard | Directly corroborates this whole investigation's central finding, independently | High (for the narrow claim: symbol ambiguity is explicitly modeled here) |

## 11. Provenance and Dependency Graph

```text
docs/knowledgeos/brainstorming/ (historical corpus)      research/, verification/ (repo root)
        │                                                          │
        │  K_t¹¹, K_min⁴, canonical-construction K,                │  kernel-reduction C0/C0+,
        │  ratified K_t, K=(A,R)  [narrative + some                │  4 cardinality-8 kernels,
        │  executed scripts, per BC-02.5–13]                       │  Qualify (contract),
        │                                                          │  knowledgeos-sim K_t=Γ(E_t,…),
        │                                                          │  notation-COLLISIONS registry
        ▼                                                          ▼
   [BC-02.5–13's own findings,                              [this pass's findings]
    frozen, not reopened]
        │                                                          │
        └──────────────────── UNWITNESSED ────────────────────────┘
              (no citation found connecting either side to the other;
               every apparent link above is symbol/letter resemblance only)
```

**Every edge crossing between the two columns is marked `UNWITNESSED`** — no source read in either
this pass or BC-02.5–13 explicitly connects the historical-corpus K-state formulations to the
repo-root experimental Kernel/K_t objects.

## 12. Mathematical Conclusion

What is established: the kernel-reduction codebase's `C0`/`C0_PLUS` Kernel-reduction result (13→8,
four cardinality-8 covering sets, representation-dependent) is now confirmed at `E4` — a genuine
upgrade from the `E0`/`E1` narrative-only confidence this investigation previously carried for that
specific claim. What is **not** established: any mathematical relationship (identity, projection,
quotient, refinement, or otherwise) between this Kernel and any of the seven other K_t/Kernel
shapes catalogued across BC-02.5–13 and this pass. Minimality here is explicitly cardinality-
minimal-relative-to-12-tested-representations, not global minimality, not sufficiency, not
canonicality (all separately, explicitly disclaimed by the source itself).

## 13. Statistical Conclusion

The "~150,000 trials" narrative figure is **plausible as a campaign-wide aggregate but not
confirmed as such** — each individual result file states 10,000 trials (2,000/seed × 5 seeds), and
9 such files exist, giving ~90,000 by simple multiplication, short of 150,000 without additional,
unquantified contribution from the non-seeded `audit_extended_search` variant sweep. This
discrepancy is reported precisely, not glossed over, and not resolved by inventing a reconciling
number.

## 14. DDD / Architecture Conclusion

**Corrected on review — a logical overreach.** An earlier draft of this section concluded the
evidence "supports multiple, currently unrelated objects, not one object with multiple
representations." **That overstates what `UNWITNESSED` actually establishes.** Absence of a known
mapping between two objects does not prove they are different objects — it only means no mapping
has yet been found. The corrected statement: **the current evidence requires the objects to be
treated as distinct/unrelated for provenance and analysis purposes (no citation connects any of the
newly-found experimental K/Kernel objects to any of the historically-traced ones); it does not yet
prove that they represent fundamentally different mathematical objects.** Separately, and on
firmer ground: the kernel-reduction Kernel is, by its own explicit self-classification, a research
instrument deliberately kept separate from KnowledgeOS architecture — a genuine, source-declared
bounded-context separation, not an unresolved ambiguity requiring further reconciliation effort at
this time. This second point rests on a direct source disclaimer, not on the absence-of-mapping
reasoning corrected above.

## 15. Impact on BC-02.13

**Disposition: D — Deferred, because another experimental investigation is logically prior, but
only partially: BC-02.13 as already executed remains valid for the sources it examined** (the
canonical-construction package and `t285_reconcile.py`, both already read directly in BC-02.13
itself). **This pass does not invalidate BC-02.13's findings** — it adds a *parallel*, previously-
unknown evidence stream (kernel-reduction, `knowledgeos-sim`) that BC-02.13 did not have access to
and that has no established connection to BC-02.13's own subject matter. **No modification to
BC-02.13 is required**; a **new, explicitly separate** investigation (not a BC-02.13 rewrite) would
be needed to determine whether any relationship exists between BC-02.13's four K-state objects and
this pass's kernel-reduction/`knowledgeos-sim` objects.

## 16. Recommended Next Step

**C — Qualify/projection investigation**, specifically: determine whether `kr/operators.py`'s
`Qualify` contract and `t285_reconcile.py`'s missing `Qualify` function share an actual documented
lineage ("prior lane") or are independently-arrived-at namings of the same real corpus gap — this
is the single most concrete, narrowly-scoped, evidence-grounded next question this pass produced,
directly extending BC-02.13's own recommended branch with new, corroborating material. **Not
executed in this pass, per explicit instruction.**

## Stop condition reached

**Stop F — the evidence is insufficient to determine the relationship** between the newly-found
experimental Kernel objects and the historical K_t/Kernel formulations already traced in BC-02.5–
13. This is reported as the honest outcome, not forced into a stronger conclusion.

---

# BC-02.13-Q — Qualify Semantic, Provenance and Computational-Role Audit

**Status:** EXPERIMENTAL addendum, same document. Commissioned in place of executing the original
BC-02.13 unchanged, per explicit instruction, to separate name/signature equality from semantic
identity, provenance, and computational role for the two `Qualify` occurrences.

## Q1 — Provenance: where did `Observation × Policy → Evidence` first appear?

A corpus-wide search for "Step 170" located `docs/knowledgeos/brainstorming/phase_measure_theory/
20260829-013526_step_170_the-end-to-end-knowledgeos-proof-chain.md` — `PRIMARY`, **dated
2026-08-29**, the earliest date found anywhere in this whole `Qualify` chain (predating `S2055`
08-31, the M0125–M0132 batch 09-02, and the `research/kernel-reduction/` codebase's own 2026-09-01
`_meta` dates). **Read directly**: Step 170 does **not** state the formula `Observation × Policy →
Evidence` verbatim — its only "Qualify"-adjacent text is a single composite function reference,
`CaptureAndQualify(O)` (line 187), with no accompanying signature.

**The explicit `Observation × Policy → Evidence` signature and the "G1, irreducible" gap
classification were formalized one source later**: `docs/knowledgeos/brainstorming/
phase_measure_theory/knowledgeos_kernel/research/D285-6-STATE-CANDIDATE-EVALUATION.md` (`S2097`,
`PRIMARY`, **dated 2026-08-31**) states, verbatim: *"`π(Observation) → e` requires `Qualify :
Observation × Policy → Evidence`, which **has no body** in the corpus (one undefined hit, Step
170). `G1`, irreducible."*

**This is a near-verbatim match to `t285_reconcile.py`'s own code comments and printed output**
("Qualify HAS NO BODY in the corpus (1 undefined hit, Step 170)"). **`D285-6` is the actual "prior
lane" `kr/operators.py`'s comment referred to** — confirmed by direct textual correspondence, not
inferred from proximity or symbol reuse alone.

**Chronology, `[SOURCE FACT]`**: Step 170 (08-29, informal "`CaptureAndQualify`") → `D285-6`/`S2097`
(08-31, formalizes the exact signature and the "`G1`, irreducible" classification, explicitly citing
Step 170) → `t285_reconcile.py` (08-31, same day, cites the same "Step 170"/"G1" finding
near-verbatim) and `kr/operators.py` (2026-09-01+, cites "recorded G1/irreducible in prior lane").

## Q2 — Semantic identity: do the two `Qualify` occurrences mean the same domain operation?

`kr/atoms.py` defines `A_QUALIFICATION = "evidential-qualification"` with the inline comment "admit
an observation AS evidence under a policy," and `kr/carriers.py` states the exact derivation rule
`({OBSERVATION, POLICY}, {A_QUALIFICATION}, EVIDENCE)` — **the same semantic content as `D285-6`'s
`Qualify : Observation × Policy → Evidence`**: transforming an observation into evidence, gated by
a policy. **Verdict: `SEMANTIC IDENTITY SUPPORTED`** — both occurrences describe the same domain
operation (evidence admission under policy), not merely the same name or type signature. This is a
stronger finding than Claim A/B alone (name, signature) — the *content* of what each does also
matches, based on direct reading of both sources' own descriptive text.

## Q3 — Computational role: what breaks when `Qualify` is removed from `Reach(C0)`?

Per `kr/carriers.py`'s derivation table, `A_QUALIFICATION` (held only by `Qualify`) is the **sole**
named atom producing the `EVIDENCE` carrier from `{OBSERVATION, POLICY}` inputs. Removing `Qualify`
therefore makes `EVIDENCE` **unreachable via this specific derivation path** in `Reach`'s fixpoint
computation — any capability (`C1`–`C25`) requiring `EVIDENCE` as an input would fail to be
reachable. This is precisely why `Qualify` appears in **all four** cardinality-8 covering sets found
in `audit_extended_search.json`: it is the unique, non-substitutable source of one required carrier
type within this model. **This is an experimental/formal-model finding about the specific `Reach`
computation as implemented — not, by itself, a claim about KnowledgeOS's actual theoretical
requirements**, per the distinction the review explicitly asked to preserve (formal necessity within
a model ≠ theoretical necessity for KnowledgeOS).

## Q4 — Relationship to `t285_reconcile.py`: same concept, same gap, or independent rediscovery?

**Answered directly, not by elimination**: **same formal gap, shared by explicit citation** — both
`t285_reconcile.py` and `kr/operators.py` cite the identical source finding (`D285-6`/`S2097`'s "`G1`,
irreducible," itself citing "Step 170"). This is **not** independent rediscovery, and **not** merely
shared notation — it is two separate experimental codebases drawing on the **same named,
`PRIMARY`-sourced corpus gap**, confirmed by near-verbatim textual correspondence in both. This
resolves the "prior lane" reference `kr/operators.py` left unattributed, and directly answers
BC-02.13's own open question (§P: "is `Qualify`'s missing body a corpus-wide gap or specific to this
projection context?") — **it is a corpus-wide, explicitly named gap (`G1`), not local to either
experimental context.**

## Disposition (per the four questions)

| Question | Answer | Evidence basis |
|---|---|---|
| Q1 Provenance | `Step 170` (informal), formalized in `D285-6`/`S2097` (both `PRIMARY`, 08-29/08-31) | Direct textual match, not inference |
| Q2 Semantic identity | `SUPPORTED` — same domain operation (evidence-admission-under-policy) | Direct reading of both sources' descriptive text |
| Q3 Computational role | `Qualify` is the sole source of the `EVIDENCE` carrier in this model; its removal makes `EVIDENCE` unreachable | Direct code reading (`carriers.py`), not executed afresh |
| Q4 Relationship to `t285` | Same named corpus gap (`G1`), shared by explicit citation — not independent rediscovery, not mere notation coincidence. **Corrected on review**: `t285_reconcile.py` and `kr/operators.py` are **two downstream witnesses of one primary-source finding** (`D285-6`/`S2097`), not independent confirmations — the correct structure is `Step 170 → D285-6/S2097 → {t285_reconcile.py, kr/operators.py}`, a single evidence event with two citing artifacts, not three independent events. | Near-verbatim textual correspondence traced to one common ancestor (`D285-6`) |

**What this does NOT establish**: that `Qualify`'s formal necessity within the `kernel-reduction`
model or the `t285_reconcile.py` projection implies it is theoretically necessary for KnowledgeOS
as a whole — that remains a separate, unaddressed question, per the explicit formal-necessity-≠-
theoretical-necessity distinction preserved throughout.

**No hybrid built. No model ranked. No production, historical corpus, or experimental repository
files modified. BC-02.13 not re-executed. No automatic continuation.**

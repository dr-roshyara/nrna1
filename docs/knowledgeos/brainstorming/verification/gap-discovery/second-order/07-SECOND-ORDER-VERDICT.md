# 07 — Second-Order Verdict

**Session:** second-order derivation attempt on D-1/D-2/D-3, 2026-08-30.
**Objective (mandate):** *"Find out whether D-1, D-2 and D-3 are truly irreducible normative choices,
or whether the corpus contains enough evidence to derive them."*

---

## THE VERDICT

$$\boxed{\textbf{ALL THREE ARE DERIVABLE. ZERO IRREDUCIBLE NORMATIVE CHOICES REMAIN.}}$$

$$\boxed{\textbf{The theory is NOT blocked on a human decision. It is blocked on ONE object: } \Sigma.}$$

| | Decision | Disposition | Decided by |
|---|---|---|---|
| **D-1** | the mandatory operation set `𝒪` | **DERIVED — RETIRED** | corpus (§256.2, §259.7–8) + execution (SO-EXP-01/03) |
| **D-2** | is authority exogenous | **DERIVED — RETIRED** | corpus (§187.28–29) + implementation (132/132 `humanActRef`) |
| **D-3** | is conditional determination in scope | **DERIVED — RETIRED** | corpus (§157.22 aggregate, §165.10 argument) |

`OPEN/NORMATIVE` nodes in the canonical dependency graph: **0 of 29.**

---

## 1. What produced the reversal

The first-order pass classified three questions as normative. **All three failures were the same
failure: reading terminal artifacts and inferring from silence.**

| Question | What the first-order pass read | What it missed |
|---|---|---|
| D-1 | Step 266's audit table (`operation registry 🟡 missing`) | Step 256 *"Formal Operation Signature Registry"* — nine typed operations; Step 259 §259.7–8 — five classes and the class-1 restriction |
| D-2 | Step 187's stipulation + `docs/knowledge/schema/` | `.claude/runtime/workflow/` — 22 records, 73 lanes, **132 grants, 132/132 `humanActRef`** |
| D-3 | Steps 262–267 (`Determination`: 0 occurrences) | Steps 157/165/173 — **1 165 occurrences**, an aggregate root, an invariant, a lifecycle, domain events |

**Three self-corrections, stated plainly:**

1. **G-01's premise is false.** *"`𝒪` is never enumerated"* — it is, twice. And §259.18 states the
   `∀T ∈ 𝒯` problem **verbatim**, so it was never an independent discovery.
2. **EXP-3's inference is invalid.** `ever_contested` is a class-4 audit operation; §259.8 excludes
   it from the congruence test and §257.32 says history-dependence of an *implementation* does not
   disprove state sufficiency. **The arithmetic was right; the conclusion was not.**
3. **G-38 is withdrawn.** The implementation does not contradict the authority stipulation — it
   implements it at 100 % coverage. The first-order pass conflated *where authority originates* with
   *which artifacts are governed*.

---

## 2. What the second-order pass adds

Three findings that are new, and one that is the frontier.

**SO-1 (`EXECUTION EVIDENCE`) — the congruence matrix is computed.**
Step 259 §259.9 tabulates 17 rows, every cell `UNRESOLVED`; §256.28 and §257.22 add two more matrices
of `?`. **~60 cells, uncomputed anywhere in the corpus or in any prior verification artifact.**
`exec/so_exp01` computes 96 of them. Result: **`K=(𝒜,ℛ)` is congruent for every class-1 operation
under both dependency variants over a 208-state domain**, and `F3` (`K` *without* provenance) fails
under origin-sensitive operations — which answers §256.29's own question
(*"Does provenance belong in `K`?"* → yes, and the terminal model already carries it).

**SO-2 (`DERIVATION`, new) — congruence is necessary but not sufficient.**
`exec/so_exp02` shows `F4` passes `Merge`/sensitive **only because it cannot see what the operation
preserves**. Congruence is satisfied vacuously. A second criterion is required and the corpus never
states it:

> **For every mandatory invariant `I`, `I` must be expressible as a predicate on the candidate state.**

**SO-3 (`EXECUTION EVIDENCE`) — the invariant audit.** `exec/so_exp04`: of six mandated invariants
locatable in the corpus, **`K=(𝒜,ℛ)` expresses five**. The single failure — §265.11's boxed
*"Merge preserves provenance association"* — has **one structural cause** (a single provenance slot
cannot record two sources) and **a corpus-supplied repair** (§265.19's `π` as a reference).

**SO-4 — the frontier.** After reclassification, **17 of 57 gaps are actual theoretical holes**, and
of the five remaining CRITICAL ones, **all five converge on `Σ`**.

---

## 3. The convergence, and the missing step

The corpus's own latest artifact agrees, independently of this analysis. Step 271 §271.35:

| `K` | State equality | History | Policy internals | **Σ** |
|---|---|---|---|---|
| 🟢 | 🟢 | 🟢 | 🔴 | **🔴** |

and §271.36 commissions:

> **STEP 272 — "Derive the minimum epistemic-status structure from distinguishability, contradiction,
> missingness, evidence assessment, supersession and inference — while explicitly separating
> epistemic state from lifecycle and governance state."**

**Step 272 does not exist.** (Highest step = 271; 217, 229 and **268** also absent.)

`exec/so_exp05` partially discharges it, using Step 272's own six named inputs and the
minimal-sufficient-statistic method §271.36 names:

```
coherent situations generated                     56
mandatory distinctions (class-1/2 operations)      9
coarsest partition preserving all distinctions    50 classes
irreducible sources                                6 of 6
epistemic-only projection                         14 states → 13 minimum values
```

**Structural result (robust to the value-set modelling): `Σ` is a product of independently varying
axes, not an enum — and two of Step 272's own six inputs (`supersession`, `inference`) are not
epistemic at all.** A `Σ` built from all six would re-conflate exactly what Step 272 demands be
separated.

---

## 4. The five closures

Computed separately, never averaged (`exec/so_exp06`):

| Closure | Value | Deficit localised to |
|---|---|---|
| Semantic | **69.0 %** (20/29) | `Context`, `Assertion`, `Relation`, `Policy`, `Rule`, `Evidence`, `Assessment`, `Σ`, `Determination` |
| Computational | **69.0 %** (20/29) | the same chain, plus `Measurement`, `K*` |
| Evidential | **55.2 %** (16/29) | — |
| Governance (regime level) | **66.7 %** (2/3) | `Policy` internals; **open at act level** (G-57) |
| Implementation correspondence | **51.7 %** (15/29) | — |

**The dependency graph is now acyclic** — the first-order 7-node cycle is broken because `Authority`
has no outgoing edge, which is §187.29 realised in code. **Two cautions stand:** acyclicity is
*purchased* by that termination and returns if authority is internalized; and acyclicity does **not**
imply semantic completeness — nine nodes are a DAG and still lack coherent meanings.

---

## 5. The smallest remaining frontier

> **`Evidence → Assessment → Σ`, with `Policy` and `Rule` as inputs.**

Everything else is closed, parametric, engineering, historical, or already resolved.

| # | Work | Closes | Kind |
|---|---|---|---|
| **1** | **Execute Step 272** — derive `Σ` by the minimal-sufficient-statistic method. `so_exp05` is a starting point, not an answer | G-06, G-25, G-27 | mathematics |
| **2** | Declare `𝒥_mandatory` — the invariant set. SO-2/SO-3 show this, not `𝒪`, determines state adequacy | G-55, G-56 | product declaration |
| **3** | Make `π` set-valued or relation-backed (§265.19's own repair) | G-55 | engineering |
| **4** | Type `Policy` / `Rule`; give `Relevant` a procedure or move it across the judgement boundary | G-11, G-14, DS-2 | modelling |
| **5** | Type `Context`; type `humanAct`; make `ℛ` edges first-class | G-15, G-57, G-24 | modelling |
| **6** | Record the `Determination` disposition, then reinstate §157.22 | DS-1, N-3 | one sentence, then modelling |
| **7** | Supply `Split`'s semantic-preservation invariant | G-54 | modelling |
| **8** | State the empirical relational structure for any quantity that survives; relabel the two formulas as heuristics | G-12, G-21 | mathematics |
| **9** | EKP repairs: `vocabulary-integrity.yaml`; cards on the schema files; covering relation for `statuses.yaml`; transition-legality rule; correct the "47 tests" figure in 14 artifacts | G-10, G-32–G-35 | engineering |
| **10** | Run the selection precision/recall experiment specified 2026-08-25 | G-36 | execution |

**Only item 1 is research. Items 2–10 are declaration, modelling and engineering.**

---

## 6. Fingerprint check (mandate: agreement is not corroboration)

The corpus is responding to verification work in real time, so provenance must be stated.

| Finding | Also appears in | Admissibility |
|---|---|---|
| `humanActRef` **132/132** | `verification/independent/14` (21:19) | **Corroborated as a MEASUREMENT** — two independent computations over the same files. The *inference* ("governance closed at regime, open at act") is **convergent, not independent**: both are Claude verifier sessions with mutual file visibility |
| `Graph independence ≠ Statistical independence` | corpus Step 271 §271.13 (20:49) vs first-order `07` MT-4 (20:26) | **Convergent, provenance indeterminate.** Step 271 does not cite the gap-discovery package; the files were on disk 23 minutes earlier. Cannot be claimed as independent corroboration in either direction |
| `∀T ∈ 𝒯` has no determinate meaning if `𝒯` is incomplete | corpus Step 259 §259.18 (19:20) | **NOT an independent discovery.** The corpus states it verbatim, before the first-order pass. G-01's novelty claim is **withdrawn** |
| Minimality is relative to `𝒯` | Step 254; `verification/CANONICAL-KNOWLEDGEOS-THEORY.md` | **Pre-existing.** Both threads had it |
| `Σ` is multi-axial | first-order `06` SG-2 (20:26); corpus Step 271 §271.36 commission (20:49) | **Convergent.** The method (`minimal sufficient statistic`) is the corpus's own |
| **Congruence ≠ invariant-expressibility (SO-2)** | nowhere located | **New so far as this session can determine.** Neither the corpus, nor `verification/`, nor `verification/independent/` composes §259.15 with §256.9/§265.11 |
| **The computed congruence matrix (SO-1)** | nowhere located | **New.** §259.9's 17 cells remain `UNRESOLVED` in the corpus |

**Environment note.** A third verifier package (`verification/independent/`, 15 documents) was written
**21:10–21:19, during this session**, and `THEORY-GAP-REGISTER.md` (20:51) and
`INDEPENDENT-CLOSURE-REVERIFICATION.md` (20:41) likewise postdate the first-order package. Documents
`01`–`05` of this second-order package were formed from primary sources before those were read;
`07` §6 is the only place they are used, and only for fingerprinting.

**G-13 is now worse, not better:** four verification threads and one research thread are writing into
one directory tree within the same hour, each able to read the others. **Freeze one tree before the
next pass.**

---

## 7. Answering the mandate's stated objective

> *"Determine exactly which parts of KnowledgeOS are mathematically derived, corpus-established,
> implementation-observed, intentionally parametric, genuinely normative, or still unresolved — and
> thereby identify the smallest remaining frontier."*

| Class | Count | Examples |
|---|---:|---|
| **Mathematically derived** | 10 nodes | `Proposition`, `Provenance`, `Lineage`, `History` |
| **Corpus-established** | — | `Evidence(O,P,C,R)`, admission ≠ truth, `P ≠ A`, `Provenance ≠ History` |
| **Implementation-observed** | 15 nodes | `K` (40 assertions / 59 relations), `Authority` (132/132), `Governance` |
| **Intentionally parametric** | 5 nodes + 7 parameters | `K`, `Operation`, `T_algebra`, `Equivalence`, `Value` |
| **Genuinely normative** | **0** | — |
| **Still unresolved** | 12 nodes | 10 `OPEN/DERIVABLE`, 2 `OPEN/EMPIRICAL` |

$$\boxed{\textbf{The smallest remaining frontier is } \Sigma \textbf{ — and the step to close it is already commissioned and unwritten.}}$$

---

## 8. What this verdict does NOT say

- It does **not** say the theory is complete. Semantic and computational closure are both 69 %.
- It does **not** say `K = (𝒜,ℛ)` is proven. It is `CLOSED/PARAMETRIC`: congruent over a **bounded**
  domain, minimal **relative to `𝒯`**, and per §259.16 a finite test yields `Congruent_tested`,
  never `Congruent_global`.
- It does **not** say the implementation validates the theory. Fifteen nodes have instances; `Σ`,
  `Determination`, `Evidence` and `Assessment` have **none**.
- It does **not** claim the three retirements are certain. Each rests on corpus sections and
  executed tests that are cited and attackable. **The fastest way to refute D-1's retirement is to
  exhibit a class-1 operation, sourced to the corpus, for which `K=(𝒜,ℛ)` fails congruence.**
  `exec/so_exp01` will accept it as a new row.

---

*End of the second-order package. Documents 01–07 plus `exec/` (6 programs).*

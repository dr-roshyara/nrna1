# 00 — Errata to the First-Order Package

**The first-order documents (`../00`–`../17`) are left unedited**, as a historical record. This file
records what the second-order pass found wrong in them. Where the two disagree, **this file wins.**

---

## 1. Withdrawn

| ID | First-order claim | Why withdrawn |
|---|---|---|
| **G-38** | *"The implementation contradicts the exogenous-authority stipulation."* | It implements it: 132/132 grants carry `humanActRef`, `registeredBy: governance` on 132/132, 0 typed acts inside, fail-closed resolver. The pass conflated *where authority originates* with *which artifacts are governed*. → `02` |
| **G-08 (as stated)** | *"`Determination` — the founding object — is absent from the theory."* | It occurs **1 165 times in 142 files**; §157.22 gives it an aggregate root, §165.8 a type. Only the *terminal six steps* omit it. → `03` |
| **G-01 (novelty claim)** | presented as an independent CRITICAL discovery | Step 259 §259.18 states it verbatim, and §254 already writes `Minimality(K\|𝒯)`. Not independent. → `01` §0 |

## 2. Corrected

| ID | First-order | Second-order |
|---|---|---|
| **G-01 premise** | *"`𝒪` is never enumerated."* | **False.** §256.2 enumerates nine operations; §259.7 adds six. 15 named, 5 classes. |
| **KG-4 / EXP-3 inference** | *"`ever_contested` refutes `K=(𝒜,ℛ)` sufficiency."* | **Invalid.** `ever_contested` is a class-4 audit operation; §259.8 restricts congruence to class-1, and §257.32: *"History dependence of implementation ≠ history dependence of state semantics."* The arithmetic was right; the inference was not. |
| **IE-4** | *"`≡` is unconstructed."* | Overstated. `≡` **is** constructible relative to `𝒯` (§254's own formulation), and SO-EXP-01 computes it for the class-1 set. |
| **ON-1** | *"the dependency graph is cyclic."* | True as drawn, but the `Authority → Assertion` edge does not survive the implementation evidence. Re-marked graph is **acyclic** (`05` §2) — at the price of the exogenous termination. |
| **G-31** | *"the aggregate boundary is never fixed."* | §157/§165/§173 fix it: `Knowledge`, `Determination` and `Decision` are three separate contexts, and §173.5 calls merging the last two *"another dangerous merge."* |
| **"53 gaps, 13 CRITICAL"** | headline count | Conflates ten different kinds of thing. **17 of 57 are actual theoretical holes; 5 remain CRITICAL; all 5 converge on `Σ`.** → `04` |
| **The three normative decisions** | D-1, D-2, D-3 put to the user | **All three derivable.** Zero `OPEN/NORMATIVE` nodes. → `06` |

## 3. Stands, unchanged

The following first-order findings survive the second-order pass intact:

`INV-9` (3 executable artifacts in the whole tree) · `EV-0`/`G-13` (corpus circularity — now worse) ·
`EV-A1`/`G-36` (the day-one empirical test never run) · `SG-2` (Σ is multi-axial — and Step 271 §271.36
independently commissions its derivation) · `MT-3` (ordinal averaging) · `MT-4`/`G-21` (the two
formulas refuted as measurements — and Step 271 §271.13 reaches the same conclusion) · `GR-5`/`G-10`
(schema vocabulary ungoverned) · `GR-6`/`G-33` (`vocabulary-integrity.yaml` missing) ·
`IR-1`/`G-22-corrective` (`K` **is** implemented — the EKP) · `IR-2`/`G-34` (vacuous invariants) ·
`PL-7`/`G-35` (the "47 tests" figure) · all sixteen `S-01…S-16` survivors.

## 4. The pattern behind all three errors

**Every one was inference from silence in a terminal artifact.**

- D-1: read Step 266's audit table, not Step 256 titled *"Formal Operation Signature Registry"*.
- D-2: read `docs/knowledge/`, not `.claude/runtime/workflow/`.
- D-3: read Steps 262–267, not Steps 157/165/173.

The first-order pass declared a reading discipline (`00` §9) that prioritised "foundational and
terminal steps" and concept-driven targeted reading. **The failure mode of that discipline is
precisely a concept that is fully specified in a middle band and silently dropped later** — which is
the same failure mode the pass itself identified three times in the corpus (`EV-A2` measure theory,
`UL-9` `Regime`, and now `DS-1` `Determination`).

**The corpus and its verifier failed in the same way, for the same reason.**

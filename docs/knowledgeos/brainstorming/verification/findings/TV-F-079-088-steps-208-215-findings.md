---
artifact: TV-F-079 … TV-F-088
track: A (verification)
phase: 2C
covers: raw-titled Steps 208–215 (8 files, 11,287 lines), written 2026-08-30 01:17–01:41
date: 2026-08-30
status: DELIVERED
authority: verifier session (adversarial, independent)
source: spec/STEP-VERIFY-208-215.md
discipline: |
  SOURCE RESULT / VERIFIER OBSERVATION / POSSIBLE REPAIR kept separate. NO SILENT REPAIR.
  POSITIVE findings are verified to the same standard as adverse ones — TV-F-079 and TV-F-080 were
  re-checked first-hand by the supervising verifier precisely because they favour the corpus.
---

# Findings TV-F-079 … TV-F-088

## TV-F-079 — **PATTERN BREAK (POSITIVE): the fabricated-result pattern stops** `[SUPERVISOR-VERIFIED]`

**This is the first material improvement the verification programme has found, and it is verified to the
same standard as the adverse findings.**

The corpus carried a documented pattern of synthetic tool output presented as measurement — nine artifacts
across steps 122, 123, 129, 153, 154, most sharply step-129 §129.2, headed literally `Execution:` → command
block → `Result:` → `Checked: 1,248 objects / Valid: 1,248 / Invalid: 0`.

**First-hand measurement across all eight files of this band:**

| Probe | Result |
|---|---|
| `Execution:` / `Result:` / `### Result` blocks | **0** |
| fabricated cardinalities (`Checked: N`, `N objects`, `PASS: N`, `commit <hex>`) | **0** |
| executable-language fences (bash/sh/console/json/php/python/sql) | **0** |

**And the corpus now actively rejects fabrication in its own voice.** Step 210 §210.29, verbatim:

> *"We should **not** yet turn this into a numerical score. A fake precision such as `Assurance = 97.3\%`
> would be misleading unless we have a justified statistical model."*

Step 215 §215.24: *"The exact numbers are unknown."* Step 212 §212.9 sets six preconditions before any
compliance percentage may be stated.

**VERDICT: `CORPUS ESTABLISHES` — the step-129 pattern is broken.** Every numeric token in the band is
either explicitly hypothetical or explicitly disavowed. **The corpus has learned not to fabricate.**

---

## TV-F-080 — **PATTERN BREAK (POSITIVE): the all-`?` matrix pattern stops** `[SUPERVISOR-VERIFIED]`

Steps 108, 112, 114, 116 and 121 each published a central matrix in which **every** analytical cell was `?`
(step-114's was 48 cells of `?`, captioned *"the central empirical artifact"*).

**Step 210's invariant verification matrix is populated:** 17 table rows, **0 `TBD`**, and only 12 `?`
characters in the entire file — in prose interrogatives, not matrix cells.

**VERDICT: `CORPUS ESTABLISHES` — the all-`?` pattern is broken.**

**The qualification, stated fairly and immediately.** The matrix is populated with *design intent*, not
*measurement*. It realizes 3 of the 6 components its own §210.1 tuple declares; it contains **15 test names
and 0 test results**; and only 4 of 15 invariants have a single named bounded-context owner — seven are owned
by a layer or a tactical pattern (`Domain`, `Persistence`, `Aggregate` ×2, `Contracts`, `Knowledge`), which
is *ownerless* under step-212 §212.15's own definition. **Row I11's owner is `Knowledge` — the exact generic
object step-208 §208.28 boxes a prohibition against.**

**So: a real improvement in form, not yet in evidential content.**

---

## TV-F-081 — The pattern that did NOT break: zero empirical acts, in the three files that announce them

**Class:** the corpus's most persistent property · **Severity:** CRITICAL · `[SUPERVISOR-VERIFIED]`

Across 11,287 lines and 26 fenced blocks: **0 executable fences · 0 shell commands · 0 repository paths ·
0 commit SHAs · 0 test-runner outputs · 0 directory listings.** All 26 fences are `text`-tagged ASCII.

**The three files that announce empirical work perform none:**

| Step | Title | Empirical acts | What it produced instead |
|---|---|---|---|
| 212 | Architecture **Gap Discovery** | **0** | 104 table cells, **0 statuses assigned, 0 gaps determined**; the `Status` column that is the point of the step exists in no table |
| 213 | **Evidence-First** Reconstruction | **0** | 0 artifacts opened |
| 214 | **Build** the Evidence Ledger | **0** | **0 ledger rows** — fewer than uncited step-109 §109.69, which at least produced four fabricated example rows |

**Step 214's boxed centrepiece is `EL = ⋃ᵢ ELᵢ` — a union over an empty index set. `EL = ∅`.**

**A four-to-five-deep deferral chain:** 212 → 213 → 214 → 215 → 216, each announcing that the *next* step
performs the empirical work. Step 216 defers again. **The corpus record now stands at 473 files, 218 steps,
and zero empirical acts.**

---

## TV-F-082 — Step 215 does not reconstruct Steps 1–182; it announces a method, and reaches three conclusions anyway

**Class:** the corpus attempting this programme's own task · **Severity:** high · `[BAND-REPORTED]`

Step 215 titles itself *"Reconstruct Steps 1–182"* and boxes the objective
`S₁…S₁₈₂ → Evidence → Knowledge → Architecture`.

**What it actually contains:** of 182 steps, **6 are named, 0 are quoted, 0 are given content, 0 receive a
substantive verdict, and 176 never appear at all.** Its only data matrix is 28 of 40 cells placeholder. Its
"artifact" is a 20-item column list. `Evidence` in the boxed objective is a **category name**, not evidence
gathered.

**It nonetheless reaches three conclusions about the 182 steps**, all asserted from recollection:
§215.8 (*"several themes repeatedly recur"*), §215.9 (*"Our architecture repeatedly responds by restoring
distinctions"*), and the boxed §215.10.

**The sharpest point:** Step 215 *defines the exact metric* that would substantiate the first two —
`Convergence(I) = NumberOfIndependentEvidencePaths(I)` — **and computes it for zero principles.**
`COMPUTABLE ≠ COMPUTED`, by the file's own construction.

**Two scope problems, unresolved.** Why 182 and not 215? The cut-off is never justified. And step-212 §212.5
states the derivation ran to **Step 211**, while 215 scopes reconstruction to **182** — a 29-step remainder
never reconciled. Step 215 then places Steps 183–215 — **all eight files of this band** — outside the
reconstruction, exempting the documents that mandate the discipline from the discipline.

---

## TV-F-083 — `SemanticIntegrity ∝ DistinctionPreservation` is ill-typed and unfalsifiable

**Class:** measurement-theoretic violation in a boxed headline claim · **Severity:** high · `[BAND-REPORTED]`

Step 215 §215.10 boxes `SemanticIntegrity ∝ DistinctionPreservation`.

**`∝` asserts proportionality, which demands a ratio scale on both sides.** Neither side has any scale
established anywhere. Worse, the nearest prior definition of semantic integrity is a **Boolean** —
step-208 §208.12's `SI(C,B) = True`. A proportionality between a Boolean and an unmeasured count is not
merely unproven; **it is not well-formed, and therefore cannot be tested.**

The file's hedge (*"not a proven theorem"*) covers provenness, not well-definedness. **The reconstruction
that §215.10 exists to motivate cannot test the hypothesis as written.**

**This is the fourth product-or-ratio over unscaled quantities the programme has found** — joining
step-124's `ActionRisk = Impact × Irreversibility × Scope`, step-184's `EpistemicDebt = Importance ×
Recoverability × Missingness`, and step-206's weighted-sum `B(i,j)`. **Four bands, four instances, none
aware of the others.**

---

## TV-F-084 — Step 209 is not an algebra, and acknowledges none of the 285+ prior invariant IDs

**Class:** naming without construction · **Severity:** high · `[BAND-REPORTED]`

Step 209 is titled *"Invariant Algebra"*. An algebra requires a carrier set, operations, and laws. It has:

- **no carrier set** — an invariant is used interchangeably as a predicate, a proposition, and a named object;
- **one undefined operation** (`∧`);
- **zero laws**.

`SAT(I₁ ∧ … ∧ Iₙ)` is declared and is **not constructible**: the `Iᵢ` are natural-language predicates in no
decidable logic. *(This programme's own `JOINT-SATISFIABILITY-REPORT.md` decides the question step-209
declares — by abstracting to propositional atoms — and finds the formalizable core **unsatisfiable**.)*

**It acknowledges zero prior invariant families** — not `I1–I20`, `INV-1–20`, `C1–C7`, `AFR-01–36`,
`GT/GG/GE/GC/GA`, `S1–S6`, `K1–K7`, `V1–V10`, `IR/CM/LA/AAI/PLP` — and **mints 162 new identifiers** that
collide at the glyph level with at least five of them.

---

## TV-F-085 — Symbol drift reaches twelve bindings on a single glyph

**Class:** ubiquitous-language failure · **Severity:** high · `[BAND-REPORTED]`

| Glyph | Bindings in this band |
|---|---|
| `E` | **12** — including two incompatible bindings of `E₁` **inside step 210 alone** (§210.20 an evidence item; §210.30 the documentation rung) |
| `A` | 8 |
| `S` | 7 |
| `G` | 6 |
| `C_n` | 5 in-band (contracts · contexts · criticality · claims · the Governance context) **plus** the prior-corpus `C1–C7` |
| `I` | rebound from *information measure* (208) to *invariant* (209+) **with no announcement** |

**A scale-integrity failure with a direct consequence:** the `E_n` evidence ladder has **two incompatible
calibrations** — step-210 §210.30 and step-212 §212.6 — differing on `E₂`, `E₃`, `E₄`. **Any statement of
the form "this has E₄ evidence" is uninterpretable without knowing which file it was written under.**
This is the same defect as the earlier three-way `E`-scale collision (C-084), recurring in a new band.

*"The ledger"* is specified at **five different arities** (10, 15, 8, 8, 20). `R(Sᵢ)` has **5 slots for 6
declared questions** — and the missing slot is question 1, *"What happened?"*, in the step whose entire
purpose is reconstructing what happened.

---

## TV-F-086 — Sixteen contradictions, six of them internal to a single file

**Class:** internal inconsistency · **Severity:** high · `[BAND-REPORTED]`

The load-bearing ones:

1. **§214.32 vs §214.33** — boxes *"No architectural claim should be stronger than the evidence supporting
   it"*, then **eight lines later** self-awards `Risk of overclaiming | Explicitly controlled`.
2. **§211.17 vs §211.6** — boxes *"Vector assurance is preferable to premature scalarization"* and applies
   `min{Assurance(…)}` — a scalarization — **in the same file**.
3. **§209.31 vs §209.32** — models invariants as a dependency graph (24-line ASCII), then as a flat
   conjunction `I_global = ⋀ Iᵢ` in the next section. **A conjunction cannot express dependency.**
4. **§208.10 vs §212.19** — §208.10 states `L(C) = I_required − I_preserved` and warns *"We should not treat
   this as a literal numeric quantity until we define an information measure."* §212.19 restates the
   subtraction **without the caveat and without citing §208.10**. No information measure is defined in the
   four intervening files.
5. **§209.21 vs §209.25/§210.9** — declines a universal total order on epistemic states, then writes
   `S(T(x)) > S(x)` four sections later.
6. **§215.30 vs §215.1–.32** — prohibits *"creat[ing] more theoretical formulas merely because we can"*,
   then mints four new formal objects, one of which is ill-typed (TV-F-083).

---

## TV-F-087 — The band fails every one of its own six standards

**Class:** self-application failure · **Severity:** CRITICAL · `[BAND-REPORTED]`

This is the sharpest way to state the band's status, because every criterion is the corpus's own:

| The band's own rule | Applied to the band |
|---|---|
| §210.0 `Architectural Principle ≠ Architectural Property` (until there is an enforceable mechanism **and** a verification method) | **0 of 15 invariants are properties** |
| §211.29 `Invariant → Owner` present but `Enforcement` missing = a design gap | **all 15 matrix rows are design gaps** |
| §212 boxed `No evidence ⇒ Unknown` | **all 15 invariants and all 15 gaps are `Unknown`** |
| §213.26 F/D/P/U labelling declared mandatory | **applied 0 times**, including in 213 itself |
| §214.32 `No claim stronger than its evidence` | **all seven verdict-bearing files exceed their evidence** |
| §215.32 `ClaimStrength ≤ EvidenceStrength` | with `EvidenceStrength = 0`, **every claim exceeds it** |

Meanwhile the band self-awards ~30 verdict labels: `STRONG`, `VERY STRONG`, `PASS`, `COHERENT`,
`DEEP CONSISTENCY`, and step-214's `Risk of overclaiming: Explicitly controlled`.

---

## TV-F-088 — Citation density confirms the corpus-wide baseline; and one deep citation is wrong

**Class:** provenance failure · **Severity:** medium-high · `[BAND-REPORTED]`

**7 individual prior-step citations across 11,287 lines** — one per ~1,600 lines. **Six of the seven are the
immediately preceding step.** Step 211 cites nothing at all; its §211.32, headed *"Connection to our earlier
work"*, names six *disciplines* and **zero steps**.

**Only step 215 reaches further back** — to Steps 73 and 91 — and **both reaches carry zero content, while
one (Step 91) mismatches the real step's own title.**

**Zero citations to any of:** Step 048 (`LocalCorrectness ⇏ GlobalCorrectness`), Step 050 (six break
attempts), Step 058 (`CorrectContexts ⇏ CorrectComposition`), **Step 082 (Uncertainty Propagation — an
explicit agenda item Step 207 set for Step 208)**, Step 092, Step 109 (the prior Evidence Ledger), Step 166,
Step 025N.

**Step 208 re-boxes `Component correctness ⇏ System correctness` for the fourth time in the corpus; step 211
does it a fifth. Every occurrence is uncited.** Two of Step 207's eight agenda items for Step 208 —
version compatibility and uncertainty propagation — are not delivered.

---

# What survives from this band

Roughly two dozen durable distinctions, and the band's method specification is of **consistently high
quality**. Worth preserving:

- **`AI Interpretation ≠ Engineering Evidence`** (213) — the single most important rule in the band, given
  that the corpus is itself AI-generated.
- **`No evidence ⇒ Unknown`**, with `Unknown ≠ Missing` and `Unknown ≠ Implemented` (212) — the correct
  default, and the rule that (correctly applied) renders the band's own claims Unknown.
- **`Semantic Laundering`** (209) — the best-defined coinage in the band.
- **`Determinism ≠ Correctness`** (209); **`Architectural Principle ≠ Architectural Property`** (210);
  **`Derived Architecture ≠ Current Architecture`** (212) — the correct guard against architectural
  hallucination, which the corpus names.
- **`Observed ≠ Intended ≠ Implemented ≠ Target`** (215); **`NegativeKnowledge`** (214), precisely scoped;
  **`Vector assurance is preferable to premature scalarization`** (211) as a principle.
- **§208's `The objective is not zero information loss. The objective is zero loss of relevant meaning.`** —
  the band's clearest and most durable architectural statement.
- **Five correct measurement-theoretic refusals** (§209.21, §210.29, §211.4, §212.9, §214.23, §215.7) —
  the corpus declining to scalarize, to fake precision, and to impose a linear lifecycle.
- **The philosophical layer is handled correctly throughout.** §215.19: *"We should **not** architecturally
  claim: 'Krishna is the system's memory.' That would trivialize the philosophical material."* §215.20 boxes
  `Philosophical Analogy ≠ Technical Proof`. **No overreach found in any of the eight files** — a genuine
  strength, and a reversal of the erosion found at steps 158 and 140338.

**Overall characterization, and it is the fair one: the band has learned not to fabricate. It has not yet
learned to gather.**

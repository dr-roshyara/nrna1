# 01 — Operation Universe Analysis (D-1)

**Mandate Part A.** Second-order derivation attempt on D-1: *"What is the mandatory operation set `𝒪`?"*

**Relied upon from the first-order pass:** `12` IE-4 (the `∀T ∈ 𝒯` problem), `04` KG-4 (EXP-3),
`09` TG-1/TG-5, `16` G-01.
**Executed here:** `exec/so_exp01`–`so_exp04`.

---

## 0. HEADLINE — a correction to my own first-order finding

**G-01 as stated in the first-order pass is wrong in its premise and wrong in its inference.**

| First-order claim | Second-order finding |
|---|---|
| *"`𝒪` is never enumerated"* | **False.** Step 256 §256.2 enumerates nine operations; Step 259 §259.7 adds six more. **15 named operations.** |
| *"`≡` is unconstructed — this is my independent discovery"* | **Not independent.** Step 259 §259.18 states it verbatim: *"We cannot perform a valid global congruence proof until the mandatory transformation family is itself sufficiently closed… the quantifier `∀T ∈ 𝒯` has no determinate meaning if `𝒯` is incomplete."* Step 254 already writes minimality as `Minimality(K\|𝒯)`. |
| *"EXP-3 refutes `K=(𝒜,ℛ)` sufficiency"* | **Invalid inference.** `ever_contested` is a **class-4 audit/history operation**. Step 259 §259.8 restricts the congruence test to **state-transforming** operations, and Step 257 §257.32 states *"History dependence of implementation ≠ history dependence of state semantics. Only the second disproves state sufficiency."* The arithmetic was right; the inference was not. |

The first-order pass reached Steps 256/257/259 only through their headings and Step 266's audit
table. Reading them in full reverses the conclusion.

---

## A1 — Archaeology: what the corpus actually defines

| Source | Operation / predicate | Formal definition given? | History-sensitive? | Required by theory? | Status |
|---|---|---|---|---|---|
| §256.2, §256.4 | `Add` | `K × X → K'`, precondition on identity | no | yes | 🟡 signature, no closed precondition |
| §256.5 | `Remove` | `K × ID → K'` | no | yes | 🟡 `Remove ≠ Withdraw` boxed |
| §256.6, §257.2 | `Revise` | `K × X × X × C → K'` | **conditional** (§259.11 origin-gated variant) | yes | 🟡 identity rule open |
| §256.7, §257.8 | `Transform` | `K × Q × C → K'` | conditional | yes — **8 of 9 kernel phases** | 🟢 strongest |
| §256.8, §257.13 | `Supersede` | `K × X × X × C → K'`, `x_new ≻ x_old` | **`✓?`** (§256.28) | yes | 🟡 |
| §256.9, §257.18 | `Merge` | `K × Xⁿ × C → K'` | **`✓?`** | yes | 🟡 provenance rule open |
| §256.10 | `Split` | `K × X → K'` | ? | yes | 🟡 **needs a semantic-preservation invariant the corpus never supplies** |
| §256.11 | `Reject` | `K × X × Reason → K'`; `Reject ≠ Remove` | no | yes | 🟡 |
| §256.12 | `Withdraw` | `K × X × Reason → K'`; `Withdraw ≠ Remove` | no | yes | 🟡 |
| §256.20 | `Validate` | `K × E → Result` — **boxed `Validate ≢ Transform`** | no | yes | 🟢 typed, not state-changing |
| §259.7 | `Assess` | `K × X → Assessment` | no | yes | 🟢 typed, not state-changing |
| §259.7 | `Authorize` | `Actor × Action × Policy → Decision` | no | yes | 🟢 typed, **not state closure** |
| §259.7 | `Promote` | — | no | yes | 🟡 |
| §259.7 | `Reintroduce` | — | no | yes | 🟡 |
| §259.7 | `Replay` | over `H` | **yes by construction** | yes | 🟡 |
| §256.21–22 | `Command`, `Event` | boxed **≠ Transformation** | — | — | 🟢 distinctions established |

**Mentions are not definitions**, per the mandate. On that test: **3 operations have a usable typed
signature** (`Validate`, `Assess`, `Authorize`), **11 have a candidate signature with a named
unresolved dependency**, and **`Split` has no preservation invariant at all**.

---

## A2 — The candidate universe, and the classification the corpus supplies

Step 259 §259.7 partitions operations into **five classes** — this is the single most consequential
thing the first-order pass missed:

| Class | Meaning | Members (this session's assignment, argued from corpus text) |
|---|---|---|
| **1** | knowledge-state transformations | `Add, Remove, Revise, Transform, Supersede, Merge, Split, Reject, Withdraw, Promote, Reintroduce` |
| **2** | epistemic assessments | `Validate` (§256.20), `Assess` (§259.7) |
| **3** | governance operations | `Authorize` (§259.7) |
| **4** | audit / history operations | `Replay` (§259.7); **`EverContested`, `RevisionCount`** (the first-order pass's predicates) |
| **5** | observation operations | (none named) |

And §259.8 supplies the rule:

> **"Only state-transforming operations enter the primary congruence test."**

with a *different* test for class 2 (`K₁ ≡ K₂ ⟹ Assess(K₁,x) = Assess(K₂,x)`) and for class 3
(`(K,p,a) ↦ Decision`, not state closure).

**This is a derivation, not a choice.** It follows from what congruence *is*: a property of an
algebra's operations on its carrier. A query that reads a different object (`H`) is not an operation
on `K`.

---

## A3 — Derivability classification of each candidate

| Operation | Class | Verdict | Ground |
|---|---|---|---|
| `Transform` | 1 | **DERIVED** | present in 8 of 9 kernel phases (§257.8); required by coherence |
| `Add`, `Remove` | 1 | **REQUIRED-BY-COHERENCE** | a state that cannot gain or lose members is not a knowledge state |
| `Revise`, `Supersede`, `Merge`, `Split` | 1 | **DERIVED** | §256.24–26 names the first three "the critical operations"; §257 formalizes them |
| `Reject`, `Withdraw` | 1 | **DERIVED** | §256.11–12 box them as distinct from `Remove`; the distinction is load-bearing for `Σ` |
| `Promote`, `Reintroduce` | 1 | **OPTIONAL** | named once (§259.7); `Promote` is `Transform` under another name |
| `Validate`, `Assess` | 2 | **DERIVED** | typed signatures given; §256.20 boxes the separation |
| `Authorize` | 3 | **IMPLEMENTATION-CONSTRAINED** | typed in §259.7 **and** running (see `02`, 132/132 grants) |
| `Replay` | 4 | **DERIVED** | required by §266.14's determinism argument |
| `EverContested`, `RevisionCount` | 4 | **OPTIONAL** | plausible audit queries; **excluded from the congruence test by §259.8** |

**Nothing in this table is `NORMATIVE-CHOICE`.** Every operation is either derived from a corpus
requirement, required by coherence, constrained by the implementation, or optional-and-non-binding.

---

## A4 — The minimality experiment, EXECUTED

Step 259 §259.9 tabulates a 17-row congruence matrix in which **every cell reads `UNRESOLVED`**.
Steps 256 §256.28 and 257 §257.22 tabulate two further dependency matrices filled with `?`.
**~60 cells; none computed anywhere in the corpus or in any prior verification artifact.**

`exec/so_exp01_congruence_matrix.py` computes them over a 208-state bounded domain, for six
candidate abstractions × eight class-1 operations × two dependency variants = **96 cells**.

### Result

```
                              Add   Remove   Revise  Transform  Supersede   Merge  Withdraw  Reject
BLIND (operations ignore provenance / supersession history)
 F1 content-only              PASS    FAIL     FAIL      FAIL       PASS     FAIL     PASS    PASS
 F2 content+status            PASS    FAIL     FAIL      FAIL       FAIL     FAIL     FAIL    FAIL
 F3 id+content+status         PASS    PASS     PASS      PASS       PASS     PASS     PASS    PASS
 F4 K=(A,R)  [TERMINAL]       PASS    PASS     PASS      PASS       PASS     PASS     PASS    PASS
 F5 K=(A,R)+mergesrc          PASS    PASS     PASS      PASS       PASS     PASS     PASS    PASS
 F6 full state  [control]     PASS    PASS     PASS      PASS       PASS     PASS     PASS    PASS

SENSITIVE (operations read provenance / supersession history)
 F1 content-only              PASS    FAIL     FAIL      FAIL       FAIL     FAIL     PASS    PASS
 F2 content+status            PASS    FAIL     FAIL      FAIL       FAIL     FAIL     FAIL    FAIL
 F3 id+content+status         PASS    PASS     FAIL      FAIL       FAIL     PASS     PASS    PASS
 F4 K=(A,R)  [TERMINAL]       PASS    PASS     PASS      PASS       PASS     PASS     PASS    PASS
 F5 K=(A,R)+mergesrc          PASS    PASS     PASS      PASS       PASS     PASS     PASS    PASS
 F6 full state  [control]     PASS    PASS     PASS      PASS       PASS     PASS     PASS    PASS
```

### The four universes the mandate asks for

| Universe | Contents | `K=(𝒜,ℛ)` sufficient? | Lineage in `K`? | History in `K`? | Equivalence changes? |
|---|---|---|---|---|---|
| **O₁** content-only ops | operations blind to origin | **YES** (F4 all PASS) | not required | no | — |
| **O₂** + provenance/lineage-sensitive ops | `Revise` origin-gated (§259.11), `Supersede` depth-aware (§259.12) | **YES** (F4 all PASS) | **YES — F3 FAILS here** | no | F3 ≢ F4 |
| **O₃** + history-sensitive **queries** | `EverContested`, `RevisionCount`, `Replay` | **YES** — §259.8 excludes them from the test | unchanged | **no** (§257.33: audit-only is admissible) | unchanged |
| **O₄** full candidate universe | all 15 named + both variants | **YES** (SO-EXP-03) | **YES** | no | unchanged |

**The decisive row is O₂.** `F3 = id + content + status` — i.e. `K` **without provenance** — passes
under blind operations and **fails on `Revise`, `Transform`, `Supersede`** under sensitive ones.
That is Step 256 §256.29's question answered by execution:

> *"Does provenance belong in `K`?"* becomes `∃T ∈ 𝒯 : ProvDep(T) = 1?`

**Answer: yes if any operation is origin-sensitive — and §259.11's origin-gated revision rule is
exactly such an operation. The terminal `K=(𝒜,ℛ)` already carries provenance, so it is congruent
either way. The model is ROBUST to the unresolved dependency.**

### A4b — But congruence is not the whole criterion

`exec/so_exp02_invariant_expressibility.py` found that **F4 passes `Merge`/sensitive only because F4
cannot see what the sensitive variant preserves.** The merge-provenance record is projected away, so
congruence is satisfied *vacuously*.

$$\boxed{\text{Congruence is NECESSARY but NOT SUFFICIENT for state adequacy.}}$$

A second criterion is required, and the corpus never states it:

> **For every mandatory invariant `I`, `I` must be expressible as a predicate on the candidate state.**

`exec/so_exp04_mandated_invariants.py` audits the invariants the corpus states with mandatory force:

| # | Invariant | Source | Expressible in `K=(𝒜,ℛ)`? |
|---|---|---|---|
| I1 | **Merge preserves provenance association** | §265.11 (boxed) | **NO** |
| I2 | `Reject` leaves the object in `K` | §256.11 | yes |
| I3 | `Withdraw` leaves the object in `K` | §256.12 | yes |
| I4 | Provenance is preserved (never empty) | §265 placement matrix | yes |
| I5 | Supersession is recorded in the state | §256.8 / §257.13 | yes |
| I6 | `Split` preserves lineage to the origin | §265.12 | yes |

**5 of 6 expressible. The single failure has one structural cause:** a merged or split object carries
**one** provenance slot and therefore cannot record that it derives from **two** sources.

**And the corpus supplies the repair itself** — §265.19: `A = (id, P, e, c, t, π)` where `π` is a
provenance **reference** with `ResolveProvenance : 𝒫ℐ → ProvenanceObject`. Make `π` set-valued (or
back it with `ℛ`-edges to the sources) and I1 becomes expressible with no other change.

**This is an engineering consequence of a corpus-stated invariant, not a normative choice.**

---

## A5 — Is there a canonical boundary?

The mandate asks whether the theory defines a *universal* Knowledge State or one *relative to a
declared judgement/operation boundary*.

**The corpus supports the second, and says so explicitly:**

- §254: `Minimality(K | 𝒯)` — minimality is written **relative to `𝒯` from the start**.
- §259.15–16: a candidate survives only as `Congruent_tested`, never `Congruent_global`.
- §266.31: declare a `ComputableCore` and a `JudgementBoundary` and make the line **formally visible**.
- §271.35: `K` 🟢 *strong candidate* — not *proven universal*.

**Recommended formulation** (`RECOMMENDATION`, not fact):

$$\boxed{\;K^{*}\;=\;\text{the coarsest abstraction congruent for }\mathcal T_{\text{class-1}}\text{ and expressive for }\mathcal I_{\text{mandatory}}\;}$$

with `𝒯_class-1` and `𝒥_mandatory` **declared per deployment**. This is *not* a weakening. It is
what §254 already wrote, made explicit, plus the second criterion SO-EXP-02 shows is missing.

---

## VERDICT ON D-1

$$\boxed{\textbf{D-1 IS SUBSTANTIALLY DERIVED — IT IS NOT AN IRREDUCIBLE NORMATIVE CHOICE}}$$

| Sub-question | Status |
|---|---|
| Is `𝒪` enumerated? | **`CORPUS EVIDENCE`** — 15 named operations (§256.2 + §259.7) |
| Is `𝒪` classified? | **`CORPUS EVIDENCE`** — five classes (§259.7) |
| Which class enters congruence? | **`DERIVATION`** — class 1 only (§259.8) |
| Do history-sensitive queries refute `K=(𝒜,ℛ)`? | **`DERIVATION` — NO** (§257.32–33, §259.8). *First-order EXP-3 inference withdrawn.* |
| Is `K=(𝒜,ℛ)` congruent for the class-1 set? | **`EXECUTION EVIDENCE` — YES**, all 8 implemented ops, both variants, 208-state domain |
| Does the open remainder of §256.32 change the answer? | **`EXECUTION EVIDENCE` — NO.** The answer is invariant across the open choice, so the choice is not load-bearing |
| Is congruence sufficient? | **`EXECUTION EVIDENCE` — NO.** Invariant-expressibility is a second, unstated criterion |
| Does `K=(𝒜,ℛ)` express the mandated invariants? | **`EXECUTION EVIDENCE` — 5 of 6.** The one failure has a corpus-supplied repair (§265.19) |

**What remains open is not D-1 but a smaller, non-normative successor:**

> **G-01′ — `Split` has no semantic-preservation invariant (§256.10), and the merge-provenance
> invariant I1 (§265.11) is not expressible in the terminal `A` as literally written.** Both are
> derivable/engineering work, not human decisions.

**A choice that cannot change the answer is not a decision that needs making.** D-1 is retired.

---

**Next:** `02-AUTHORITY-BOUNDARY-ANALYSIS.md`.

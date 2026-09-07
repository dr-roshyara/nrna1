# Validation of the Four Records Against Schema v2

**2026-09-07.** Deliverables 3–5. **No v1 evidence rewritten — every record is appended to.**
**No adjudication · no canonicalization · no carrier selection · no theory promotion.**

---

## 0. ⭐⭐⭐ The headline — applying Defect C cuts `K` from ten definitions to four

Applying the homonym dispositions to `K` produced a **decision criterion I did not have when I wrote
the rule**, and it settles six of the ten:

$$\boxed{\textbf{If } X\textbf{'s definition CONTAINS } Y \textbf{ as a component, then } X \neq Y.}$$

Two of `K`'s ten definitions define `𝒦` **in terms of `K`**:

```
D-03   𝒦 = (K, H)                 ← contains K
D-05   𝒦 = (K, C, T, E, A)        ← contains K
```

⭐ **So `𝒦` is not a variant spelling of `K`; it is a structure that HAS `K` as a component.** Those
two are **disposition 2 — a different candidate** — on positive evidence, not on the undeclared script
distinction.

| | v1 | **v2** |
|---|---:|---:|
| definitions of `K` (`disposition 1`) | 10 | ⭐ **4** — `D-01`, `D-02`, `D-06`, `D-08` |
| **different candidate** (`2`) | — | **2** — `D-03`, `D-05` → `𝒦`, ID reserved **`KOS-T-0006`** |
| **ambiguous identity** (`3`) | — | **4** — `D-04` (`𝒦`), `D-07`, `D-09`, `D-10` (`𝕂`) |
| unevidenced pairs to evaluate | **45** | **6** |

⚠️ **Nothing is deleted.** All ten entries remain in `KOS-T-0001` with their sources; **six now carry
a disposition instead of an implied claim of being definitions of `K`.** The v1 enumeration stands as
the evidence it was.

`[INF]` **This is Defect C paying for itself twice** — once by refusing `δ = 0.3099`, and once by
revealing that **60 % of `K`'s enumerated "definitions" were never shown to be definitions of `K`.**

---

## 1. Defect A — implementation provenance, per record

| record | level | **provenance** | **selection** | evidence for the qualifier |
|---|---|:--:|:--:|---|
| **`K` `D-01`** | `result-produced-on-it` | **`stipulated`** | **`stipulated`** | `FrozenSet`, the relation **triple** shape and `RelationType` are encoding choices the definition `(𝒜,ℛ)` does not fix; and **`D-01` was chosen from ten with no act** |
| **`K` `D-02`** | 🔴 **`none`** *(corrected — see §3)* | `n/a` | `n/a` | **no code builds `K` from the eight primitives**; the primitives exist as **contents of `𝒜`** |
| **`δ` `D-02`/`D-03`** | `result-produced-on-it` | **`stipulated`** | **`stipulated`** | the op-set `{assert, relate, retract}` is chosen — **`𝒪` membership is OPEN** |
| **`≡_sem` `D-02`** | `result-produced-on-it` | ⭐ **`stipulated`** | **`stipulated`** | **the code states it**: *"the ONLY reading the corpus offers is a NAME. Any concrete procedure must choose what to discard. **Decision 3 (254) is OPEN.**"* |
| **`Qualify` `D-01`** | `result-produced-on-it` | ⭐ **`stipulated`** | **`stipulated`** | **the code marks it**: `# ---- Qualification (279/278: policy-parametric) ----` — behaviour supplied by `policy_params`, **an input** |

$$\boxed{\begin{array}{c}\textbf{Every implementation in all four records is } \texttt{stipulated}.\\ \texttt{derived}\ \textbf{: ZERO instances.}\qquad \texttt{selection: forced}\ \textbf{: ZERO instances.}\end{array}}$$

⭐ **Two empty vocabulary values, recorded as empty.** `[INF]` **No implementation in the estate's four
most-cited objects has evidence of being derived from the theory.** That is a finding about the
estate, and it is exactly what the qualifier was added to make visible. **The rule holds in both
directions: none of this code ratifies anything, and none of it refutes `G1`, `CR-3` or `CR-4`.**

## 2. Defect B — candidate kind, per record

| record | v1 `Category` *(preserved)* | **`kind:`** | 4th axis check |
|---|---|:--:|---|
| `K` | `Concepts` — no protest | **`object`** | status `[UN]` · impl `stipulated` · grounding `mixed` — **all four axes differ** |
| `δ` | `Concepts` **under protest** *(preserved verbatim)* | **`operation`** | status `[UN]` · impl `stipulated` · grounding `mixed` |
| `≡_sem` | `Concepts` **under protest** | **`relation`** | status `[UN]` · impl `stipulated` · grounding `mixed` |
| `Qualify` | `Concepts` **under protest** | **`operation`** | status `[UN]` · impl `stipulated` · grounding **`architecturally-grounded`** ⭐ |

⭐ **`Qualify` is the axis-separation proof:** `grounding = architecturally-grounded` (the code is
exercised) while `implementation provenance = stipulated` and `status = [UN]`. **A single collapsed
field would have read "grounded and implemented, therefore settled" — which is false.**

**And the fourth kind is instantiated:** `δ = 0.3099` → **`KOS-T-0005`, `kind: constant`.**
**Three kinds were not sufficient; the evidence demanded four.**

## 3. Explicit reclassifications — recorded, not silent

| # | record | v1 said | v2 says | why |
|---|---|---|---|---|
| **R-1** | `K` `D-02` | `type-exists` (partial) | 🔴 **`none`** | no code builds `K` from the eight primitives — already flagged in `KOS-T-0001` §Implementation and now applied to the field |
| **R-2** | `K` `D-03`, `D-05` | definitions of `K` | **disposition 2 — `𝒦`, a different candidate** | **their definitions contain `K`** |
| **R-3** | `K` `D-04`, `D-07`, `D-09`, `D-10` | definitions of `K` | **disposition 3 — ambiguous** | `𝒦`/`𝕂` script undeclared; **no containment evidence either way** |
| **R-4** | `δ` `D-04` (*successor-state axioms*, *situation calculus*) | a definition | **disposition 4 — symbolic occurrence** | **names a framework; does not define `δ`** |
| **R-5** | `Qualify` `D-04` (`Φ : K̂_t → {Knowledge, Not-Knowledge}`) | a definition of `Qualify` | **disposition 3 — ambiguous** | **a different glyph (`Φ`)**; and `Φ` collides with `Φ : Π_t → K_t` → routed to `H1` |
| **R-6** | `≡_sem` — `=_str`, `≈_obs`, `SameId`, `≡_H`, `≡_P` | siblings in `D-02`'s 7-tuple | **disposition 2 — separate candidates** | distinct relations; IDs to be reserved when extracted |

**Definition counts after revalidation:** `K` **4** *(was 10)* · `δ` **3** *(was 4)* ·
`≡_sem` **3** *(unchanged; `D-03` remains `misattribution`)* · `Qualify` **3** *(was 4)*.

## 4. Newly exposed ambiguities and defects

| | finding | disposition |
|---|---|---|
| **E** ⭐ | **the containment criterion** — *if `X`'s definition contains `Y`, then `X ≠ Y`* — was **discovered while applying C**, not designed into it. It is what settled `D-03`/`D-05` on evidence rather than on the script | `[PROP]` **adopt as an explicit sub-rule of C.** Applied here; **recorded as an amendment discovered during validation** |
| **F** | **`disposition 3` is now the largest class** — 4 of `K`'s 10, 1 of `Qualify`'s 4. The homonym rule **converts unexamined enumeration into explicit ambiguity, and the ambiguity is large** | not a defect — **the honest consequence.** But it means *"how many definitions does `K` have?"* has **no answer** until the script question is settled |
| **G** | **derived counts must be recomputed, not carried.** `K`'s pair count fell 45 → 6; any figure computed from a definition count is invalidated by a disposition change | `[PROP]` mark all counts **derived** and recompute on every disposition change |
| **H** | **`δ = 0` (4 occurrences) is unclassified.** Neither confirmed as `FR-001`'s constant nor as a distinct object | recorded as **residue**; `[OPEN]` |
| **I** | **two reserved IDs now exist without records** — `KOS-T-0005` (`δ=0.3099`, `constant`), `KOS-T-0006` (`𝒦`) | reserved deliberately; **a reserved ID is not a claim that the candidate is real** |
| **J** | `Operations` and `Relations` remain **`[PROP]`** in the 19-category list while three records need them. **`kind:` carries the fact without pre-empting the decision** | by design — **but the 19-list is now demonstrably incomplete on three counts** |

## 5. PASS / FAIL

$$\boxed{\textbf{PASS — with two conditions and one adopted amendment.}}$$

| criterion | result |
|---|:--:|
| a record shape accommodating **object, operation, relation, constant** without forcing | ✅ **PASS** |
| implementation status **no longer implies** theoretical status | ✅ **PASS** — and it caught a wrong v1 field (`R-1`) |
| a repeated glyph **no longer becomes** a definition | ✅ **PASS** — and it caught **6 of `K`'s 10** |
| the four axes remain **independent** | ✅ **PASS** — `Qualify` proves it (`grounded` + `stipulated` + `[UN]`) |
| **no historical evidence rewritten** | ✅ **PASS** — all four records appended to; v1 protests preserved verbatim |
| **no adjudication / canonicalization / carrier / promotion** | ✅ **PASS** — 4 records, **4 empty governed-decision fields** |

### The two conditions

1. **Defect E (containment) must be written into C before the next run** — it was discovered *during*
   validation, so the rule as published in `04` is **incomplete as applied**. ⚠️ **I have applied it
   here; the rule text must catch up with the practice.**
2. **`Σ` will probably demand `kind: value-set`** — predicted in `04` §2 and **deliberately not
   pre-added**. If it does, that is a *fifth* kind and the schema takes another amendment. **PASS is
   on the machinery's ability to fail loudly, not on the vocabulary being complete.**

$[REC]$ **Ready for `Σ`, `Π`, `Zero`, `ℐ`** — after Defect E is folded into `04` §3.
**Stopping here as instructed: no further extraction in this run.**

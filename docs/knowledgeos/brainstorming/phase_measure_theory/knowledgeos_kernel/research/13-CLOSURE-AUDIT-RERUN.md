---
artifact: 13 · CLOSURE AUDIT — RE-RUN
date: 2026-08-31
scope: `REFINED-STEP-287.md` vNext (**equality**) · `REFINED-STEP-287-INVARIANTS.md` (**new, in scope for the first time**) · `08-FINDINGS…` · `00-INDEX`
verdict: **287-EQUALITY → READY-FOR-STEP-288** · **287-INVARIANTS → REQUIRES-CORRECTION (2 MATERIAL)**
constraint: no files modified during this audit
---

# 13 · Closure Audit, Re-run

## Part 1 · The previous 8 findings — all confirmed fixed

| Finding | Was | Now | ✅ |
|---|---|---|---|
| **B-1** | §3 heading *"the axes ARE the observations"* | *"the five axes bound a CANDIDATE observation space"* | **FIXED** |
| **B-2** | `08` *"partial order **by construction**"* | `CORRECTED` block; *"ONLY ONCE every component relation is independently established — and ZERO OF FIVE ARE"* | **FIXED** |
| **M-1** | flat lead-in ×2 | *"a product-order CONSTRUCTION … is available as a candidate, conditional on…"* | **FIXED** |
| **M-2** | §4 *"CANDIDATE product partial order"* | *"CONDITIONAL product-order CONSTRUCTION"* | **FIXED** |
| **M-3** | `08` *"`S` plainly ordinal, `V` arguably is"* | `SUPERSEDED caveats` — all five axes restated per P6 | **FIXED** |
| **M-4** | `00-INDEX` *"32 candidates … the product partial order"* | *"at most 32 candidate projections … conditional … over `Σ` — not over `K`"* | **FIXED** |
| **M-5** | `08` *"bounded to 32 options"* | *"at most 32 candidate PROJECTIONS; selection … NORMATIVE"* | **FIXED** |
| **m-1** | *"TWO specified (`=`, `≡_D`)"* | *"`=` (**state** level) and `≡_D` (**value** level)"* | **FIXED** |

**8 of 8 fixed. Residual grep hits are the audit record and the §12 discipline table — both intentional.**

## Part 2 · The 18 forbidden inferences, re-checked across both artifacts

| # | Inference | 287-EQUALITY | 287-INVARIANTS |
|---|---|---|---|
| 1 | Σ-order ⟹ K-order | ✅ clean | ✅ clean — not asserted |
| 2 | candidate = established relation | ✅ clean | ⚠️ **M-6** |
| 3 | 32 projections ⟹ relation selected | ✅ clean | n/a |
| 4 | math equivalence ⟹ architectural meaning | ✅ clean | ✅ clean |
| 5 | product order before components | ✅ clean | ✅ clean |
| 6 | Σ coordinate automatically ordered | ✅ clean | n/a |
| 7 | `≡_D` ⟹ `≡` | ✅ clean — now level-marked at the verdict line | n/a |
| 8 | *"under-specified"* hides normative in technical | ✅ clean | ✅ clean — six-way classification used |
| 9 | Decision 3 + `X` solve equality | ✅ clean | ✅ clean |
| 10 | `D285-5` ⟹ `δ` non-injective | ✅ clean | ✅ clean — `I-C` carries `≡`, quotient-scoped |
| 11 | `grantId` ⟹ authority-act identity | ✅ clean | ✅ clean |
| 12 | provenance identity ⟹ state identity | ✅ clean | ✅ clean — `I-Π` and `I-I` kept separate |
| 13 | `≅_λ` principle = predicate | ✅ clean | ✅ clean |
| 14 | Step 261 §261.23 bypassed | ✅ clean | ✅ clean — §5 invokes it for `∀o` |
| 15 | Step 288 solved | ✅ clean | ✅ clean — §9 explicit |
| 16 | philosophy as architectural evidence | ✅ clean | ✅ clean — §8 marks `I-𝒩`/`I-O` corpus-native |
| 17 | *available/bounded* as *established* | ✅ clean | ⚠️ **M-6** |
| 18 | *not established* as *impossible* | ✅ clean | ✅ clean |

**287-EQUALITY: 18/18 clean.** **287-INVARIANTS: 16/18 clean, 2 carrying one finding.**

---

## Part 3 · New findings — `REFINED-STEP-287-INVARIANTS.md`

### 🟠 M-6 · MATERIAL — §3's *"Vacuity: ✅ safe"* reads as *"in good standing"*

**Exact wording:** §3 register, column headed **`Vacuity`**, rows `I-𝒩` / `I-Π` / `I-V` / `I-O` → **"✅ safe"**.

**Inferred overclaim (#2, #17):** a reader scanning §3 sees `I-Π` = **`DERIVED`** + **"✅ safe"** and
concludes the candidate is in good standing. **§6, fifty lines later, verdicts `I-Π` as UNTESTABLE.**

**Why material:** the two columns measure **different properties** — §3 measures *vacuity-safety*, §6
measures *testability* — and **nothing in §3 says so.** *"✅ safe"* is the single most reassuring token
in the artifact and it attaches to a candidate that **cannot currently be tested at all.**

**Severity: MATERIAL** — not blocking, because §2's ceiling and §6 both state the truth; but §3 is the
table a reader consults first.

**Action: QUALIFY.** Rename the column and add a pointer:
> `| … | Equality used | Vacuity-safe? | Testable? (§6) |`
> with `I-𝒩` → `✅ / 🔴 UNTESTABLE`, `I-Π` → `✅ / 🔴 UNTESTABLE`, `I-V` → `✅ / ✅`, `I-O` → `✅ / ✅`.

---

### 🟠 M-7 · MATERIAL — a count contradiction inside the artifact

**Line 102** (§6, `I-V` row):
> *"✅ **the only one testable today**"*

**Line 156** (`ESTABLISHED` block):
> *"**`I-V` and `I-O` are the only candidates testable today**"*

**And line 104** (§6, `I-O` row) itself reads *"✅ **testable** — because it is NOT a transition
invariant."*

**Inferred overclaim:** none — but **the artifact contradicts itself on a count it states three times:
one vs two.**

**Why material:** this is the same defect class as the two BLOCKING findings of the first audit — **a
local statement contradicting the document's own conclusion.** It is the pattern the programme keeps
producing, and it appeared again in a brand-new artifact.

**Action: REPLACE** line 102 → *"✅ **testable today** (with `I-O`)"*.

---

## Part 4 · Second pass over the mandated blocks

| Block | 287-EQUALITY | 287-INVARIANTS |
|---|---|---|
| **headings** | ✅ both defects fixed | ✅ clean |
| **headline findings** | ✅ | ⚠️ **M-7** |
| **STATUS** | ✅ `OPEN` | ✅ `RESEARCH ARTIFACT — NOT NORMATIVE / NOT ARCHITECTURE` |
| **ESTABLISHED** | ✅ 8 items, level-marked | ⚠️ **M-7** (the count) |
| **BOUNDED** | ✅ *"at most 32"*, *"conditional"* | ✅ |
| **NORMATIVE** | ✅ 3 items | ✅ 3 items |
| **TECHNICALLY OPEN** | ✅ incl. *"`K`-order: no candidate structure at all"* | ✅ 6 items |
| **DEFERRED** | ✅ requires **this** version | ✅ requires **both** artifacts |
| **CHANGE CONTROL** | ✅ 10 changes + not-done list | n/a (new artifact) |
| **§0a numbering notice** | ✅ **NEW — collision recorded, not resolved** | ✅ mirrored |
| **§13 / §14** | ✅ | ✅ (§9 + closing line) |
| **`00-INDEX` refs** | ✅ M-4 fixed | ✅ |
| **`08-FINDINGS` refs** | ✅ B-2/M-1/M-3/M-5 fixed | ✅ |
| **closing line** | ✅ verbatim as mandated | ✅ parallel form |

---

## Part 5 · Verdict

> ## `REFINED-STEP-287.md` vNext (EQUALITY) → **READY-FOR-STEP-288**
> **18/18 forbidden inferences clean · all 8 prior findings fixed · every mandated block present and
> correctly scoped.**

> ## `REFINED-STEP-287-INVARIANTS.md` → **REQUIRES-CORRECTION**
> **2 MATERIAL, 0 BLOCKING.** Both are presentational; **neither touches an argument.** Exact
> replacement wording supplied.

### ⚠️ One caveat on the split verdict

**287-EQUALITY is ready as a document.** It is **not** ready as an *identifier* — the numbering
collision (§0a) is unresolved, and **Step 288's mandate names "the corrected 287"** without
distinguishing equality from invariants. **A registry decision should precede 288**, or 288 will
inherit an ambiguous input.

### The recurring pattern, now four instances

| # | Instance | Where |
|---|---|---|
| 1 | §4 heading vs its body | first audit, BLOCKING |
| 2 | `08` F5 body vs its own SUPERSEDED block | first audit, BLOCKING |
| 3 | `00-INDEX` 6th vs 11th headline | nine audits |
| 4 | **§6 *"the only one"* vs `ESTABLISHED` *"`I-V` and `I-O`"*** | **this re-run** |

> **All four are a local statement contradicting the document's own conclusion, and all four sat in a
> heading, a summary row, or a count — never in an argument.** The reasoning has been sound throughout;
> **the scaffolding is where this programme's errors live.** A standing check is warranted: **after any
> substantive edit, re-verify every count, heading and summary row against the body.**

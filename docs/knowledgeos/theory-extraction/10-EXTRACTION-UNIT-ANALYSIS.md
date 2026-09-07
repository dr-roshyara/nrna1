# The Unit of Extraction — `P-06` Analysis

**2026-09-07 · bounded analysis · existing evidence only.**

> ⛔ **Nothing is modified.** No schema field · no v3 · no 3MC · no MD-017/018 · no corpus · no
> merging · no equivalence adjudicated · no carrier · no kernel · `𝒪_core` not frozen · no `OQ-1`
> decision · no canonical theory. **`P-06` is analysed, NOT decided.**

**Legend used throughout:** `[EV]` observed evidence · `[DEF-X]` schema defect · `[PROP]` conceptual
proposal · `[DEP]` dependency · `[OPEN]` unresolved question.

---

## 1. Current extraction-unit model

**v2's record shape, as actually written:**

```
one record  ─────────  keyed by a NAME or GLYPH  ("K", "δ", "Σ", "Π", "Zero", "ℐ", "≡_sem", "Qualify")
   ├── kind                       ← asserted at RECORD level
   ├── status · grounding         ← asserted at RECORD level
   ├── Definitions  D-01 … D-0n   ← the only sub-level that exists
   ├── homonyms · ambiguous_identity · symbolic_occurrences
   ├── Implementation  level × provenance × selection   ← attached per D-nn
   ├── Latest ×4                  ← asserted at RECORD level
   └── Dependencies · Relationships · Conflicts · Gaps
```

$$\boxed{\begin{array}{c}\textbf{The record is KEYED BY A GLYPH and ASSERTS A CANDIDATE.}\\ \textbf{v2 has no place where those two come apart.}\end{array}}$$

`[EV]` **That identification is the whole of `P-06`.** It held for `K` in the pilot because `K` is one
object; it failed the moment a glyph carried more than one thing.

## 2. Observed level distinctions

**Levels the eight cases actually force apart — and one that is only suspected.**

| | level | forced by | status |
|---|---|---|:--:|
| **L1** | **glyph / surface symbol** | `Σ` 143 summation sites · `ℐ` vs `𝓘` two scripts · `𝒦`/`𝕂`/`K` three scripts | `[EV]` |
| **L1′** | **base glyph vs subscripted glyph** | `≡` → `≡_sem`, `≡_H`, `≡_P`, `≈_obs`, `=_str`, `SameId` — **siblings live at the base, the record at the subscript** | `[EV]` |
| **L2** | **occurrence** — one textual site | dispositions are assigned **per occurrence** (1/2/3/4); `Σ`'s 143 are occurrences, not readings | `[EV]` |
| **L3** | **reading / definition** | `D-nn` throughout | `[EV]` |
| **L4** | **candidate** — the thing a definition is *of* | `𝒦 ≠ K` by containment · `Π`'s 4 disposition-2 readings · `δ = 0.3099` · `0_i ∈ 𝒟_i` | `[EV]` |
| **L5** | **implementation artifact** | 8 records; every implementation is a distinct code artifact | `[EV]` |
| **L5′** | ⭐ **artifact TYPE — realization vs model-of** | `ℐ`: `"InvariantReg": (…, "NOT ENUMERATED", …)` is **a model OF the concept, not a realization of it** | `[EV]`, **1 instance** |
| **L6?** | **comparison arm** | `Zero`'s four functions | 🔴 **NOT a level — see §5 `P-14`** |
| **L7?** | **obligation** — above candidate | `ℐ` ~ `ℛ_req`: *"different formalisations of the same obligation"* | `[OPEN]`, **1 instance** |

⚠️ **`L6` is listed and then withdrawn** — the evidence shows it is a *grouping of `L5`s under sibling
`L3`s*, not a level. **Recorded because it was proposed, and refuted here.**

## 3. Evidence table — the eight concepts

| concept | record actually addresses | multiple **definitions** per glyph? | multiple **candidates** per glyph? | `kind` legitimate at glyph level? | implementation at a **different level** than the definition? |
|---|---|:--:|:--:|:--:|---|
| **`K`** | ⚠️ a **glyph family** `K`/`𝒦`/`𝕂` | ✅ 4 | 🔴 **yes** — `K`, `𝒦`, + 4 ambiguous | ✅ *(all confirmed candidates are `object`)* | no |
| **`δ`** | a glyph | ✅ 3 | 🔴 **yes** — `δ` + `δ=0.3099` | 🔴 **NO** — `operation` vs `constant` | no |
| **`≡_sem`** | ⚠️ a **subscripted** glyph; siblings live at the **base** | ✅ 3 | 🔴 **yes** — 5 siblings at `L1′` | ✅ *(`relation`)* | no |
| **`Qualify`** | a **name** | ✅ 3 | ⚠️ possibly — `D-04`'s `Φ` ambiguous | ✅ *(`operation`)* | ⭐ **no — 2 realizations of ONE definition**, both declaring `Observation × Policy ⇀ Evidence` |
| **`Σ`** | a glyph | ✅ 6 | 🔴 **yes** — `Σ`, `Σ_Λ`, + summation *(not a candidate)* | ✅ *(all six are value-domains)* | 🔴 ⭐ **YES — `D-05` exists ONLY as code**; an implementation fact was recorded as a definition |
| **`Π`** | 🔴 **a glyph, not a candidate** | ✅ 7 readings | 🔴 **yes — ≥5** | 🔴 **NO — 4 kinds** | no *(but `Latest` fields belong to different candidates)* |
| **`Zero`** | a **name** + `0` | ✅ 6 | 🔴 **yes** — `Zero` + `0_i` | 🔴 **NO** — `relation` ×5 + `constant` | 🔴 ⭐ **YES — `D-03` has FOUR implementations because it is FOUR definitions**; code comment: `# the four readings` |
| **`ℐ`** | ⚠️ **two glyphs** `ℐ`, `𝓘` | ✅ 4 | ⚠️ possibly — `ℛ_req` unresolved (`L7?`) | ⚠️ `register` proposed | 🔴 ⭐ **YES — the only artifact is a MODEL OF the concept (`L5′`), not a realization** |

**Tally:** multiple definitions **8/8** · multiple candidates **6/8 confirmed, 2 possible** ·
`kind` illegitimate at glyph level **3/8** · implementation at a different level **3/8**.

## 4. Failure cases caused by **record addressing** — not by a missing field

$$\boxed{\textbf{Seven failures. Six are addressing. ONE is a genuinely missing distinction.}}$$

| # | failure | case | cause |
|---|---|---|:--:|
| **F-1** | `kind` unassignable | `δ`, `Π`, `Zero` | **addressing** — one glyph, several candidates of different kinds |
| **F-2** | `status` unassignable | `Zero` — two `[RF]` readings beside four live | ⚠️ **NOT addressing** — same candidate, rival definitions, differing status. **A missing per-definition field** |
| **F-3** | `Latest` ×4 describe different objects | `Π` — R1's mention vs R4's implementation | **addressing** |
| **F-4** | ⭐ **an implementation fact became a definition** | `Σ` `D-05` — lifted from code, no textual source | **addressing** — no place for an *implementation-only reading* below `L3` |
| **F-5** | ⭐ **four definitions recorded as one** | `Zero` `D-03` — the code has four functions and the comment says *"the four readings"* | **addressing** — no sub-definition granularity |
| **F-6** | **a model-of mistaken for a realization** | `ℐ` — `"NOT ENUMERATED"` node | ⚠️ **partly a missing field** — artifact **type** (`L5′`) |
| **F-7** | the record asserts a candidate while keyed by a glyph | `K`, `Π`, `≡_sem`, `ℐ` | **addressing — the root cause of F-1, F-3, F-4, F-5** |

⭐ **`F-4` and `F-5` were not in the stress reports.** They surface only when the *levels* are laid out
— which is what this analysis was for.

⚠️ **`F-2` and `F-6` would survive any re-addressing.** They are genuine field gaps, not level errors.

## 5. Proposal dependency graph

```
                          ┌──────────────────────────────┐
                          │  P-06  UNIT OF EXTRACTION    │  [OPEN]
                          │  (F-1 F-3 F-4 F-5 F-7)       │
                          └───────────┬──────────────────┘
             ┌───────────────┬────────┴────────┬─────────────────┐
             ▼               ▼                 ▼                 ▼
      P-01 kind@def   P-15 Latest owner   P-14 comparison   P-05 placeholder
      MAY DISSOLVE      MAY DISSOLVE      MAY DISSOLVE       TRANSFORMS
                                          (Zero half only)

   ── independent of P-06 ─────────────────────────────────────────────────
   P-02 status@def [survives]   P-03 malformed        P-07/08/09 kinds
   P-10 co-obligation (L7?)     P-11 supersession     P-12 misattribution
   P-13 not-searched            P-16 scope exclusion  P-17 titled-unplaced

   ── other edges ───────────────────────────────────────────────────────
   P-11 ──gates──► P-04 selection:superseded
   P-16 ──gates──► P-05
```

### `[DEP]` Proposals that are **consequences of a wrong extraction unit**

| ID | why it exists | fate if `P-06` resolves to **candidate-addressing** |
|---|---|---|
| **`P-01`** kind@definition | only because one record spans several candidates | ⭐ **DISSOLVES** — split `δ`/`δ=0.3099`, `Π`'s five, `Zero`/`0_i`, and **every remaining record has ONE kind**: `δ` operation · `Zero` relation ×5 · `Π`'s five each one |
| **`P-15`** `Latest` ownership | only because `Π`'s readings are different candidates | ⭐ **DISSOLVES** — one candidate, one timeline |
| **`P-14`** comparison arms | ⭐ **splits in two** | **Zero half DISSOLVES** — `D-03` → `D-03a…d`, then each has **exactly one** implementation. **Qualify half is ALREADY REPRESENTABLE** — two realizations of `D-01`, both declaring `Observation × Policy ⇀ Evidence`; v2 never forbade two |
| **`P-05`** `modelled-as-placeholder` | conflates *realization* with *model-of* | **TRANSFORMS** — likely becomes an **artifact-type field (`L5′`)**, not a new level |

$$\boxed{\begin{array}{c}\textbf{Three of seventeen proposals may DISAPPEAR, and one may TRANSFORM,}\\ \textbf{if } P\text{-}06 \textbf{ is resolved before they are applied.}\end{array}}$$

### `[DEP]` Proposals that **survive `P-06` regardless**

`P-02` *(Zero's `[RF]` readings are one candidate's rival definitions — splitting by candidate does not
touch it)* · `P-03` *(malformed is malformed at any level)* · `P-07`/`P-08`/`P-09` *(the kind
vocabulary is demanded wherever kind attaches)* · `P-10` *(an `L7?` question, above candidate)* ·
`P-11`, `P-12` *(meta-level statements about definitions and names)* · `P-13` · `P-16` · `P-17`.

## 6. `P-06` open questions

**Four, and the decision must answer the first three.**

| | question | evidence bearing on it |
|---|---|---|
| **Q1** | **What is the record's unit — glyph, candidate, or both as separate record types?** | `Π` forces the distinction; `K` and `Zero` and `δ` confirm it; `Σ` shows the glyph layer is still needed *(143 symbolic occurrences must live somewhere)* |
| **Q2** | **Is `occurrence` (`L2`) a first-class record, or a field on a definition?** | dispositions are per-occurrence; `Σ`'s 143 are currently a **count**, not records. ⚠️ **A count cannot carry a citation per site** |
| **Q3** | **Does `L1′` matter — base glyph vs subscripted glyph?** | `≡`'s six siblings live at the base while the record sits at the subscript; `ℐ`/`𝓘` is the same shape in reverse |
| **Q4** | ⚠️ **Is there an `obligation` level above candidate (`L7`)?** | **one instance only** — `ℐ` ~ `ℛ_req`. `[OPEN]`, **and the decision may legitimately defer it for want of instances** |

⛔ **No preferred model is proposed.** A hierarchy `glyph → occurrence → candidate → definition →
implementation` is **one** shape consistent with the evidence; **it is not asserted, and Q2/Q3 are
exactly where it might be wrong.**

## 7. Upstream / downstream of `P-06`

| | proposals |
|---|---|
| **upstream** *(needed before `P-06` can be answered)* | ⭐ **none.** The eight cases already force the distinctions; `P-06` is answerable from present evidence |
| **downstream** *(should not be applied first)* | `P-01` · `P-15` · `P-14` · `P-05` |
| **independent** | `P-02` · `P-03` · `P-07` · `P-08` · `P-09` · `P-10` · `P-11` · `P-12` · `P-13` · `P-16` · `P-17` |
| **gated by something else** | `P-04` ← `P-11` · `P-05` ← `P-16` *(and `P-06`)* |

## 8. What can safely proceed before `P-06`

| | proposal | why safe |
|---|---|---|
| ⭐ **1** | **`P-16`** formalize the scope exclusion | **already applied in practice**; writing the rule down changes no record. ⚠️ **Highest urgency — it is the rule that prevented `G-1`'s manufactured claim** |
| 2 | **`P-13`** `not-searched` | a third empty-field value; **independent of addressing** |
| 3 | **`P-03`** `malformed` | a disposition value; malformed at any level |
| 4 | **`P-07`/`P-08`/`P-09`** the kind **vocabulary** | ⚠️ **the vocabulary can be settled without deciding where `kind` attaches** — the two questions are separable |
| 5 | **`P-17`** titled-but-unplaced | a record-keeping question |
| 6 | **`P-11`**, **`P-12`** | meta-level; `P-12` is already with 3MC |

## 9. What must wait

| | proposal | why |
|---|---|---|
| **`P-01`** | **may not be needed at all** — applying it would add a per-definition field that candidate-addressing makes redundant |
| **`P-15`** | same |
| **`P-14`** | its Zero half may dissolve; its Qualify half **needs nothing** |
| **`P-05`** | may become an artifact-type field instead of a level |
| **`P-02`** | ⚠️ **survives `P-06`, but its FIELD OWNER depends on Q1** — *"per definition"* means something different if records are per candidate |
| **`P-10`** | depends on **Q4**, the `L7` question |

$$\boxed{\begin{array}{c}\textbf{Applying } P\text{-}01, P\text{-}15, P\text{-}14 \textbf{ or } P\text{-}05 \textbf{ now risks building fields}\\ \textbf{that a correct } P\text{-}06 \textbf{ answer would delete.}\end{array}}$$

## 10. Explicit non-conclusions

**This analysis establishes NONE of the following:**

- ⛔ **no answer to `P-06`** — Q1–Q4 are posed, not answered; **no preferred model proposed**
- ⛔ **no schema change** — v2 is untouched; **v3 not begun**
- ⛔ **no proposal applied, promoted, or withdrawn** — 17 remain; **the "may dissolve" findings are
  PREDICTIONS about a decision not yet taken**, not retirements
- ⛔ **no definitions merged** · **no equivalence adjudicated** — `𝒦`/`K`, `ℐ`/`ℛ_req`, `Σ`/`Σ₀`,
  `Π`'s seven all remain `unresolved_equivalence` or ambiguous
- ⛔ **no candidate promoted · nothing canonicalized · no carrier · no kernel · `𝒪_core` not frozen ·
  no `OQ-1` decision · no canonical KnowledgeOS theory**
- ⛔ **3MC, `dimension-registry.md`, MD-017, MD-018 and the brainstorming corpus unmodified**
- ⛔ **`L6` is refuted as a level and `L7` rests on one instance** — neither is asserted
- ⛔ **`F-4` and `F-5` are newly identified defects, NOT repaired** — `Σ` `D-05` and `Zero` `D-03`
  remain exactly as recorded

---

## Verdict

$$\boxed{\textbf{P-06 READY FOR DECISION}}$$

**Why ready:**

1. **The distinctions are forced, not chosen.** Six of eight concepts carry multiple candidates under
   one glyph; three cannot take a record-level `kind`; three have implementation evidence at a
   different level.
2. **No upstream proposal blocks it** — nothing must be settled first.
3. **The decision's consequences are measurable in advance:** `P-01`, `P-15` and `P-14`'s Zero half
   **dissolve** under candidate-addressing; `P-02`, `P-03`, `P-07`–`P-13`, `P-16`, `P-17` **survive**.
   A decision-maker can see what the choice buys.
4. **The failure cases are enumerated with citations** — F-1…F-7, each traced to a case.

**Bounded by two caveats the decision must carry:**

- ⚠️ **Q4 (`obligation` level) rests on ONE instance** (`ℐ` ~ `ℛ_req`) and **may legitimately be
  deferred** — deciding it on one case would be the manufacturing the machinery exists to prevent.
- ⚠️ **Q2 and Q3 are genuinely open** — whether `occurrence` and `base-vs-subscripted glyph` are
  record levels or fields. **They are the content of the decision, not missing inputs.**

⛔ **This verdict concerns the readiness of `P-06` for a human decision. It is not the decision, it
recommends no model, and it says nothing about KnowledgeOS theory.**

**Stopping after this analysis.**

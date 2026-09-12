# Theory Inventory — **Assessment Before Construction**

**2026-09-07.** Commissioned: a Theory Archaeology / Extraction pass producing a Theory Inventory.

> ## ⛔ I did not build one, and the reason is the finding.
> $$\boxed{\textbf{The Theory Inventory already exists, and it is more mature than the artifact I was about to build.}}$$
> `CORPUS-SEARCH-RULE.md` and **ES-005.4** both bind: *search before modelling; if one exists, consume
> or extend it — **never create a second**.* Building a rival would have been the exact defect the
> estate keeps recording as `G-101` / `G-68`.

---

## 1. What exists — measured

**`brainstorming/three_model_convergence/`**

| artifact | scale |
|---|---|
| **`01_source-analysis/dimension-registry.md`** | **14 213 lines · ⚠️ 27 CONCEPT entries + 96 FILE-LANDMARK entries** (corrected 2026-09-07 — this paper said "123 concept entries"; see [`../registry-extension-proposal/`](../registry-extension-proposal/README.md) §0. **The concept-level inventory covers 27 concepts, so the coverage gap is 4× larger than stated here**) |
| `dimension-registry-mathematical.md` | 2 470 lines |
| `research-ledger.md` | 1 856 lines |
| `02_model-a_gita/02_concept-register.md` · `03_model-b_mathematical/02_concept-register.md` | 280 · 312 lines |
| `machine-record-schema.md` | 199 lines |
| **per-file extraction records** | **1 224 `.md` + 1 224 `.yaml`** |
| control registers | `reading-manifest.tsv` 2 376 · `progress.tsv` **3 449** · `classification-register.tsv` 2 376 · `mathematical-progress.tsv` **updated today 15:35** |
| **`12_canonical-theory/STATUS.md`** | **a guard file: *"NOT YET AUTHORIZED FOR CANONICAL CONTENT"*** |

**Coverage of the reading pass: 1 224 of 2 376 files ≈ 52 %** — not the 0.7 % I reported on
2026-09-02 from an artifact dated 09-01. **That figure was six days stale and I repeated it.**

## 2. It already runs the pipeline you drew

`12_canonical-theory/STATUS.md`, verbatim:

```
source reconstruction → three-model comparison → gap analysis →
formalization → falsification

- the corpus is EVIDENCE
- the three models are RECONSTRUCTIONS
- the comparison is ANALYSIS
- the formal Kernel is a HYPOTHESIS until validated
- the canonical theory is the FINAL RESULT, not the starting assumption
```

$$\boxed{\textbf{That is "extract first, classify second, compare third, gaps fourth, decide fifth" — already written, and already GUARDED by a file that refuses canonical content.}}$$

**And the prohibitions you specified are already binding there:**

| your instruction | the protocol's existing rule |
|---|---|
| don't choose between definitions | *"**No file is permanently classified during pass 1**… a provisional classification is **never erased**"* |
| preserve alternatives | **MD-017 six-way taxonomy**, with **`unresolved_equivalence` as the DEFAULT** — *"candidate IDs are never merged on resemblance alone"* |
| don't promote hypotheses | **MD-018 status chain** `candidate → supported → corroborated → formally_defined → operationally_defined → canonical`, and *"**no entry has left `candidate`**"* |
| don't collapse categories | ⛔ **the term-collision rule**: `Kernel_engineering ≠ Kernel_epistemic`; *"a document is **never** classified `epistemic_knowledgeos` merely because it contains the word KnowledgeOS or Kernel"* |
| distinguish theory from implementation | **`Grounding:`** — `architecturally-grounded` / `philosophical-correspondence-only` / `reframing` / `mixed` / `contested-ungrounded` |
| mark uncertainty | **13-value status vocabulary** `[SR][DR][DF][HP][CG][PR][TH][EX][AN][UN][OP][RF][CT]` + maturity ladder + confidence |
| don't normalize terminology | *"Differently-named dimensions are never merged… `possible_correspondence` records a **hypothesis of relatedness, not an identity**"* |

## 3. Your requested schema vs what the registry carries — field by field

Occurrences across the 123 entries. **Where a count is below 123, the field is declared but
unevenly populated** — that is a real finding, not a criticism.

| your field | registry field | occurrences | verdict |
|---|---|---:|:--:|
| Concept | `## <heading>` | **123** | ✅ complete |
| Source · First appearance | `First appearance:` | 24 | ⚠️ **20 %** |
| Alternatives | `Name variants:` · `possible_correspondence:` | 9 · 16 | ⚠️ partial |
| Conflicts | `Contradictions:` | 17 | ⚠️ partial |
| Evidence | `Independent corroboration:` | 11 | ⚠️ partial |
| Status | `Status:` + MD-018 chain | 28 | ⚠️ partial |
| *(extra, not in your schema)* | **`Grounding:`** | 32 | ⭐ **an axis you did not ask for and should keep** |
| Definition | `Definition` | 17 | ⚠️ **14 %** — definitions are **not** enumerated `D-01…D-0n` |
| Confidence | `Confidence` | 72 | ⚠️ partial |
| Implementation consequence | `consequence` | 46 | ⚠️ partial |
| **Implementation** | `Implementation` | 65 mentions · **only 18 reference code** | 🔴 **the biggest gap** |
| **Dependencies** | — | **3** | 🔴 essentially absent |
| **ID** | — | **0** — entries keyed by concept *name* | 🔴 absent |
| **Latest appearance** | — | **0** | 🔴 absent |
| **Open questions** | — | **0** *(they live in `03_contradictions-and-open-questions.md`)* | 🔴 absent from the entry |

$$\boxed{\textbf{Five genuine gaps: per-concept ID · latest appearance · IMPLEMENTATION · dependencies · open questions on the entry.}}$$

## 4. ⭐ And `V1`–`V4` fill exactly the largest of them

**The registry's weakest column is `Implementation` — 18 code references across 123 entries.**
That is precisely what this lane measured this week:

| lane artifact | supplies |
|---|---|
| **`V1` + the `OQ-1` brief** | **three implemented carrier estates** — `zero-algebra` 59 `.py` · `knowledgeos-sim` 55 · `verification/**/exec` 54 (5 723 LOC) — and **which concepts have code**: `class K`, `Assertion(id,P,e,c,t,σ)`, `Proposition(entity,dimension,value)`, `Observation`, `Evidence`, `Rep`, `Rec`, `Item`, `EVal`, `Arg`, `Boundary` |
| **`V3`** | the **name-variant / alternatives** column at glyph level — `𝒦/𝕂/K` **7 definitions**, `Π` 6, `Θ` 5, `Σ` 4 |
| **`V2`** | an **evidence** row: `Adequacy ≠ Realization`, two witnesses, full provenance |
| **`V4`** | the **conflict** column for the lane disagreement, with citation asymmetry measured |

`[INF]` **Two lanes ran in parallel for a week and are complementary rather than duplicative — and
nobody had connected them.** The registry has depth on *provenance and maturity* and is thin on
*implementation*; this lane has depth on *implementation* and no concept-level registry at all.

## 5. Where my own work IS duplicative — stated plainly

**My `gita-candidates/` register overlaps the existing registry on 9 of 12 sampled terms:**

| term | in `dimension-registry.md` | in Model-A concept-register | in my register |
|---|---:|---:|---:|
| `Sārathi` | **131** | 7 | 1 |
| `Buddhi` | **59** | 0 | 1 |
| `Kṣetra` | 18 · `Sañjaya` 14 · `Guṇa` 15 · `Vairāgya` 5 · `Māyā` 3 · `Dhyāna` 1 · `Smṛti` 1 | | 1 each |
| **`Yajña`** | **0** | **0** | **1** |
| **`Śraddhā`** | **0** | **0** | **1** |
| **`Kṣamā`** | **0** | **0** | **1** |

$$\boxed{\begin{array}{c}\textbf{The three terms absent from the estate's own registry are EXACTLY the three my } GR\text{-}4 \textbf{ flagged}\\ \textbf{as "unexamined — the only rows still worth examining."} \textbf{ Reached independently, from the other side.}\end{array}}$$

**So: nine rows of my Gītā register duplicate existing work and should be folded in as citations;
three are genuinely new.** ⚠️ **I did not know the registry existed when I built it.** That is the
same defect I have been recording in others all week, and it is mine here.

## 6. `[REC]` What to do instead of building a second inventory

**Extend the existing registry. Do not start a rival.** Four increments, in dependency order:

| | increment | why it is next |
|---|---|---|
| **T-1** | add an **`Implementation:`** line to each of the 123 entries, populated from `V1`/`V3` — *none · partial · which estate · which class · file* | the weakest column, and the evidence already exists |
| **T-2** | add **`ID`** and **`Latest appearance`** | makes entries citation-stable — the prerequisite Governance identified for `OQ-1` |
| **T-3** | enumerate **`Definition D-01…D-0n`** per concept instead of one `Definition` line — starting with `K` (**9 definitions**, `V3`) and `Π` (**6**) | this is the shape you specified, and `V3` supplies the first two entries |
| **T-4** | fold in the 3 genuinely-new Gītā rows; convert the other 9 to citations | removes my duplication |

⚠️ **All four are edits to another lane's governed artifact.** `dimension-registry.md` is under the
`three_model_convergence` protocol with its own two-stage rule and revision discipline
(`revisions.md`, `classification-register.tsv`). **I have not touched it.** Whether this lane may
write there is a governance question, and it is a small one.

## 7. What I did NOT do, and why

| | |
|---|---|
| **did not build a Theory Inventory** | one exists — §1 |
| **did not edit `dimension-registry.md`** | another lane's governed artifact under a two-stage protocol |
| **did not re-read the corpus** | 1 224 per-file extraction records already exist; re-reading would produce a second extraction layer |
| **did not classify anything into `12_canonical-theory/`** | its guard file forbids it, correctly |
| **did not resolve the duplication** | §5 reports it; folding is `T-4` and needs authorization |

## 8. Reproduce

```bash
cd docs/knowledgeos/brainstorming/three_model_convergence
wc -l 01_source-analysis/dimension-registry.md          # 14213
grep -cE '^## ' 01_source-analysis/dimension-registry.md # 123
ls -1 01_source-analysis/per-file/*.yaml | wc -l          # 1224
wc -l < 00_control/progress.tsv                           # 3450
for f in "First appearance" Implementation Dependencies "Latest appearance"; do
  echo "$f $(grep -c "$f" 01_source-analysis/dimension-registry.md)"; done
```

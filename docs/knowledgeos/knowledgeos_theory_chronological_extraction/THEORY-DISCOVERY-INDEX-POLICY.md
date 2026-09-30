# Theory Discovery Index — policy and first-run result

> **`P1-Q1` · Phase 1B.** Keyed to **content**. ⛔ `document_kind` is not an admissible field.

## 1 · First-run result — F0001–F0025

| | |
|---|---|
| Indexed | **25 / 25** |
| `candidate_theory_bearing: true` | **25** |
| `false` | **0** |
| Confidence HIGH / MEDIUM / LOW | **18 / 5 / 2** |
| `false` without a stated `why` | ✅ **none** — no `P1-Q1` violation |
| Entries carrying `document_kind` | ✅ **none** |

### ⭐ The test it was built to pass

| File | Result |
|---|---|
| **F0022** | `true` · **HIGH** · 6 signals |
| **F0023** | `true` · **HIGH** · 6 signals |

Both were previously excluded **by document kind** for three passes. F0023 holds the corpus's own ARB-accepted answer to *"what is KnowledgeOS for"* and the only pointer to the four partial orders. ⭐ **The index catches them.**

## 2 ⭐ Policy — the index is biased toward `true`, deliberately

> **`false` is the dangerous direction.** It is an exclusion, and exclusion is what cost five things.

| Situation | Entry |
|---|---|
| content clearly theory-bearing | `true`, HIGH |
| content thin, or not fully assessed | ⭐ **`true`, LOW confidence, with a note** — ⛔ **never `false` from a thin read** |
| genuinely not theory-bearing | `false` **with a content reason** |

⛔ **Inadmissible reasons for `false`:** *"it is a governance/process/administrative document"* · *"it is a log"* · *"it is a docket"* · *"it is a dashboard"* · *"it is in folder X"*. **All of these are document kind wearing a justification.**

⭐ **Consequence, visible in this run:** `F0007` and `F0021` are marked `true` at **LOW** confidence rather than `false`. `F0021` was previously excluded as *"governance process"* — ⛔ exactly the forbidden reason.

## 3 ⚠️ Honest limitation of this first run

> **An index that returns `true` for all 25 files performs no exclusion, and therefore its discriminating power is untested.**

Two readings, and I cannot yet separate them:

| | |
|---|---|
| ⭐ **The window is genuinely uniform** | 25 files from one theory-development programme over three days. Everything being theory-bearing may simply be **correct** |
| ⚠️ **The index is too permissive** | the `false` path — where the discipline actually lives — **has never been exercised** |

⛔ **The `false` branch and its `why` requirement remain untested.** They will first be exercised on files that are genuinely administrative, and none is in this window.

**What the index does deliver here:** not filtering, but ⭐ **signal typing, confidence, and a verbatim starting quotation per file** — which is what Phase 2 consumes.

## 4 · What `C2` now reads

| | |
|---|---|
| **Before** | ⛔ **unmeasurable — no index existed** |
| **Now** | ⭐ **25/25 files assessed for theory-bearing content** |
| ⚠️ **But** | this measures **assessment coverage**, ⛔ **not** whether the assessment is *correct*. A uniform `true` cannot be validated against itself |

**`C2` is measurable. It is not yet validated.**

# Retrofit Batch 2 — `F0007` · `F0009` · `F0011` · `F0012` · `F0013`

| | |
|---|---|
| **Authority** | L0 standing authorization — the 31 admissible files, batches of five |
| **Unit** | `U-0008-RETROFIT-B2` · 5 receipts |
| **Result** | ⭐ **all five 12/12 · `dossier_status: COMPLETE`** |
| **Backlog** | admissible **26 → 21** · `COMPLETE` **7 → 12** |

---

## 1 · The work

| File | lines | gate 9 satisfied by |
|---|---|---|
| `F0007` AI Workflow Observation Log | 33 | ⭐ **`T-0051`** automation must be earned |
| `F0009` Strategic Domain Discovery | 233 | ⭐ **`T-0052`** proposing before searching, n=5 |
| `F0011` Product Boundary Discovery | 332 | ⭐ verification — `T-0029`/`T-0003` already carry it |
| `F0012` Architecture Consolidation | 247 | ⭐ **`T-0053`** stable ids survive relocation |
| `F0013` Meta-Model | 177 | ⭐ **`T-0054`** the dimension admission test |

⛔ **0 existing Theory Registry rows modified.** 4 new objects, 6 registries appended, ledger reconciled.

---

## 2 · ⭐⭐ Three findings that bear directly on this research programme

### `C-0023` — **the corpus already had the remedy for the incident that stopped this research**

> **`F0012` §5, canonical position 12:** *"Components carry stable ids `CMP-nnn`, assets `AST-nnn`. ⭐ **Paths change; ids never do.** ADRs and reviews reference ids, not paths."* — called *"the single most important extraction-safety property, already built."*

⛔ **The F0031–F0040 provenance incident was exactly a path-versus-id failure:** research IDs were assigned from a filesystem glob instead of from the canonical registry. ⭐ **The principle that prevents it was already in the corpus, twelve files in.**
⛔ *A finding about the corpus. It makes no claim about the incident's remedy, which is `RC-H-04`'s.* → **`RO-0039`**

### `C-0024` — **the corpus diagnosed this research's own failure mode, at n=5, on 2026-08-02**

> **`F0009`:** *"Four searches for a missing decision; four decisions already on the record. ⭐ **The consistent failure mode is mine and procedural — PROPOSING BEFORE SEARCHING.**"* **Five, now.**

⭐ **The same failure this research recorded as `C-0014`** *(P1 proposed beside the ARB-authorized `Round38C-04`)*, and repeated in its own canonical-discovery lapses. → **`T-0052` · `RO-0040`**

### `C-0022` — **the `G-n` series shifted by one, and the cause is traceable**

| | `G-4` means |
|---|---|
| **`F0011`** | **No second adopting product** |
| **`F0001`** | **No second runtime adapter** |
| **`F0012`** | *(a third variant: `G-C1`–`G-C7`)* |

⭐ **Cause:** `F0011`'s `G-2` — *"is the MVK sufficient?"* — was **CLOSED** by the bootstrap test on 2026-08-02. `F0001` dropped the closed row and **every `G-n` below it moved up one.**

⛔ **REFERRED TO `B-12`.** This report does not claim which `G-4` any later citation meant, nor that a derivation is affected. ⭐ *It supplies the mechanism, which `B-12` did not previously have.* → **`RO-0038`**

---

## 3 · ⭐ A provenance pattern across the batch

**Three batch-2 files are earlier sources for objects currently attributed to later files:**

| Object | attributed to | but also stated in | position |
|---|---|---|---|
| `T-0029` *survival ≠ sufficiency* | `F0029` | ⭐ **`F0011` §6** | **11** |
| `T-0041` *artifact absence ≠ concept absence* | `F0036` | ⭐ **`F0013`** *(the **remedy**, not just the error)* | **13** |
| `T-0043` *EVIDENCE-STATUS / OPERATIONAL-STATE as **candidates*** | `F0039` | ⭐ **`F0013` — which **ADMITS** both against a stated test** | **13** |

⛔ **None written.** All three are other files' objects under `O-1`=A. → **`RO-0041` · `RO-0042`**

> ⭐ **The pattern matters more than any single row: retrofitting in canonical order is surfacing earlier provenance for objects the research first recorded from later files.** ⚠️ *Expect more of this as the backlog is worked.*

---

## 4 · ⭐ Method quality found in the batch

| File | What it does that the corpus does not always do |
|---|---|
| **`F0009`** | ⭐⭐ **Four declared epistemic tiers, maintained:** 24 Observations *(facts, no interpretation)* → 12 Interpretations *(each with an explicit **confidence** and a **From** column)* → Candidates → Supported Conclusions |
| **`F0013`** | ⭐⭐ **An admission test applied symmetrically** — the author's `INTENDED` withdrawn **and** the reviewer's `INTENT-STATUS` rejected, *"applying the rule to my error and not to the proposal would be selective"* |
| **`F0013`** | ⭐ **A success criterion actually executed** — six artifacts classified, and the test produced its own finding: `GOVERNANCE-STATUS` ⟂ `OPERATIONAL-STATE`, *"obtained by USING the model rather than by arguing for it"* |
| **`F0011`** | ⭐ **An executed falsification with the condition declared beforehand** — the MVK was given to an isolated session; verdict `FAIL`; `AR-1` realized |
| **`F0012`** | ⭐ **Every migration trigger quoted from canon; none invented** |
| **`F0007`** | ⭐ **An instrument that declares itself empty** rather than implying data |

---

## 5 · Ordering — a four-file chain, all dated 2026-08-02

```
F0009  →  F0012  →  F0011  →  F0001
  ↑         ↑          ↑         ↑
named    declared   named as   the synthesis
companion  input    a source
```

⭐ **And the monotone counter agrees:** CAP-001 executions **F0009 (0) · F0010 (0) · F0001 (1)**.
⚠️ *`F0009`'s "four rows" counts **evidence** rows — a different counter, not to be conflated with executions.*

⭐ **Two of `F0001`'s four `NOT_RECOVERABLE_WITHOUT_REREAD` source syntheses are now resolved** — `F0011` and `F0012`.

---

## 6 · New records

**Objects:** `T-0051` · `T-0052` · `T-0053` · `T-0054`
**Contradictions:** `C-0022` *(referred to B-12)* · `C-0023` · `C-0024` · `C-0025`
**Obligations:** `RO-0038` … `RO-0044`
**Ordering:** `OC-0021` … `OC-0024`

---

## 7 · Next

**Batch 3 — `F0014` · `F0015` · `F0016` · `F0017` · `F0018`**, next in canonical order among the 21 remaining admissible files.

⚠️ **Known and unchanged, owned elsewhere:** the `F0031`–`F0040` registry divergence *(`RC-H-04`)* · `B-12` · `m1`/`m2` for `T-0026`/`T-0027` · the governance control surface. ⛔ **None blocks batch 3**, and none of the ten files retrofitted so far falls in the `F0031`–`F0040` range.

---

*Batch 2 · 5 files · 5 receipts · 12/12 each · 4 new objects · ⛔ 0 existing rows modified · 0 held files touched · Candidate Theory v0.9 unchanged.*

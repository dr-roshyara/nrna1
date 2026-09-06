# PHASE 1A · Corpus Inventory

**Programme:** KnowledgeOS Systematic Synthesis · Phase 1 Historical Archaeology
**Executes:** `phase_measure_theory/how_to_combine/20260828-141333_master-prompt-…md` §1A
**Source corpus (READ-ONLY):** `docs/knowledgeos/brainstorming/phase_measure_theory/`
**Status:** analysis artifact. No architecture decided. Nothing in the corpus modified.

---

## 1 · Corpus shape

| | |
|---|---|
| Files | **408** (406 root + 2 in `how_to_combine/`) |
| Total lines | **565,263** |
| Total size | **8.7 MB** |
| Span | **2026-08-25 20:42:44 → 2026-08-28 14:13:33** (≈ 65½ hours) |
| Mean rate | ≈ 6.2 documents/hour across the active span |
| Byte-identical duplicate groups | **19** (marked `-duplicate`, none deleted) |

⚠ **Method note, stated for honesty.** Every filename in this corpus was derived from that file's
content during the renaming pass, so **topic-level inventory below is content-grounded, not
filename-guessed**. What has *not* been done is a full line-by-line read of 565,263 lines. Claim-level
and decision-level detail in the later registries is therefore marked by depth. Nothing is invented to
fill a gap; absent material is marked `NOT ESTABLISHED`.

---

## 2 · Chronological density

```
20260825-20   1  ▌
20260825-21   6  ███
20260825-22   9  ████
20260825-23  17  ████████
20260826-00  15  ███████
20260826-01   1  ▌
20260826-10  13  ██████
20260826-11  12  ██████
20260826-12   5  ██
20260826-13   4  ██
20260826-14   6  ███
20260826-15   7  ███
20260826-16  12  ██████
20260826-17  21  ██████████
20260826-18  16  ████████
20260826-22   2  █
20260826-23   2  █
20260827-08   6  ███
20260827-09  17  ████████
20260827-11   7  ███
20260827-12   4  ██
20260827-13   9  ████
20260827-14   9  ████
20260827-15   9  ████
20260827-16  12  ██████
20260827-18  16  ████████
20260828-09   9  ████
20260828-10  36  ██████████████████
20260828-11  19  █████████
20260828-12  76  ██████████████████████████████████████
20260828-13  27  █████████████
20260828-14   3  █
```

⟦OBSERVATION⟧ The distribution is **strongly right-skewed**: 2026-08-28 alone carries **170 of 408
files (42%)**, and the single hour `20260828-12` carries **76 files (19%)** — one document every 47
seconds. ⟦INTERPRETATION⟧ Rate that high is consistent with a *generative sequence* (each output
prompting the next mechanically) rather than deliberative reasoning. This is recorded because it bears
on how much independent evidential weight late-corpus documents can carry.

---

## 3 · Thematic epochs — term occurrence by day (content-derived slugs)

| term | 08-25 | 08-26 | 08-27 | 08-28 |
|---|---:|---:|---:|---:|
| measure | 3 | 1 | 0 | 1 |
| knowledge-space | 2 | 6 | 0 | 0 |
| **question** | 0 | **30** | 1 | 0 |
| **step** | 0 | 0 | **43** | **167** |
| closure | 0 | 0 | 9 | 4 |
| gita | 0 | 6 | 0 | 2 |
| lord | 0 | 8 | 1 | 0 |
| zero | 0 | 10 | 2 | 0 |
| atman | 0 | 0 | 3 | 0 |
| dimension | 0 | 7 | 0 | 0 |
| evidence | 2 | 4 | 19 | 7 |
| chapter | 0 | 6 | 1 | 3 |

⟦OBSERVATION⟧ The vocabulary **turns over almost completely** between days. `question` exists only on
08-26; `step` only on 08-27→28; `dimension`, `lord`, `zero`, `knowledge-space` peak on 08-26 and vanish;
`atman` exists only on 08-27. ⟦INTERPRETATION⟧ This is not a corpus circling one topic — it is a
**sequence of distinct methodological regimes**, each replacing the previous one's vocabulary.

---

## 4 · The five regimes identified

| # | Regime | Span | Files | Organising device |
|---|---|---|---:|---|
| **R1** | Measure theory → definition crisis | 08-25 20:42 → 08-26 01:59 | ~49 | prose argument; "where to concentrate" |
| **R2** | Numbered Question series + lens construction | 08-26 10:23 → 18:26 | ~90 | `Question 2 … 24` |
| **R3** | Gītā chapters + Ātman as Kernel | 08-26 22:30 → 08-27 09:14 | ~25 | scripture chapters as source |
| **R4** | Computational Closures | 08-27 11:26 → 13:50 | ~13 | `Closure 1 … 4B` |
| **R5** | Step series | 08-27 14:06 → 08-28 13:53 | **~210** | `Step 001 … 158` + `025a–z` |

**Boundaries of each regime, with the exact document:**
- R1 opens `20260825-204244_where-to-concentrate-measure-theory-vs-knowledgeos-definition`
- R1 closes `20260826-015913_conceptual-problems-solved-but-not-all`
- R2 opens `20260826-102337_most-important-correction-review-of-research-summary`
- R3 opens `20260826-223053_what-we-are-actually-validating` (Bhagavad-gītā As It Is)
- R4 opens `20260827-114304_phase-1-close-the-epistemic-primitives`
- R5 opens `20260827-140653_step-001-operational-independence`
- R5 closes `20260828-135842_step-158-preparation-gita-chapter-4-characters-and-their-roles`

---

## 5 · Series integrity (verified by enumeration)

| Series | Range | Gaps | Notes |
|---|---|---|---|
| **Step (main)** | `001` → `158` | **none in 001–156**; 157, 158 present | 3-digit padded for sort order |
| Step 25 sub-series | `025a` → `025z` | none | plus nested `025a-1…5`, `025c-1…3` |
| Late insertions | `155a`, `156a` | — | review + validation checkpoints |
| Question series | `2, 4, 4a, 5, 6, …, 24` | **1, 3, 21 absent** | one file named Q21 contains Q19 material |
| Computational Closure | `01` → `04b` | none | two documents both numbered "Evidence" (03 and 04) |

⟦OBSERVATION⟧ `Step 066` was saved **before** `Step 065`; `Step 010` after `Step 013`; `Step 024`
before `Step 023`. ⟦INTERPRETATION⟧ Content numbering and save order diverge in at least three places,
so **neither ordering alone is authoritative** — the timeline in 1B uses save order and flags content
order where they disagree.

---

## 6 · Duplicate register (19 groups)

⟦OBSERVATION⟧ 19 md5-identical groups, ~21 redundant copies. Two patterns worth recording:
1. **Untitled re-saves** — files named `Untitled-1`, `Untitled-3` proved byte-identical to `Step 127`
   and `Step 133`. Filename alone would not have detected them.
2. **Burst re-emission** — at `20260828-13:44:43–13:45:04` (21 seconds) three earlier steps (129, 144,
   150) were re-saved alongside one new document; at `13:50:17` Step 155A was re-saved 6 minutes after
   its original.

⟦INTERPRETATION⟧ These indicate export/re-save events, not intellectual repetition. Duplicates carry
**no independent evidential weight** and are excluded from all counts in the registries.

**Effective distinct documents: ≈ 387.**

---

## 7 · Documents that are not analysis

⟦OBSERVATION⟧ Identified during inventory:
- `how_to_combine/20260828-141333_master-prompt-…` — **the instruction that commissions this synthesis.**
  It is a method document, not evidence about KnowledgeOS.
- `how_to_combine/20260828-140743_…-duplicate` — copy of a root-folder document.
- Several `Untitled-*` files (now renamed by content) were duplicates.

---

## 8 · What this inventory does NOT establish

- Whether any claim in the corpus is **true**.
- Whether the Step series **converges** — enumeration shows completeness, not coherence.
- Whether R5's 210 documents contain 210 distinct ideas. ⟦NOT ESTABLISHED⟧ — the 47-second production
  rate in `20260828-12` makes that an open question, addressed in 1B and the claim registry.
- Any relationship to the separate `brainstorming/kernel/` corpus (158 documents, extracted elsewhere).
  ⟦NOT ESTABLISHED⟧ here; cross-corpus comparison is out of Phase 1A scope.

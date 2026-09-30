# Retrofit Batch 3 — `F0014` · `F0015` · `F0016` · `F0017` · `F0018`

| | |
|---|---|
| **Unit** | `U-0009-RETROFIT-B3` · 5 receipts, issued **before** any record referenced them |
| **Result** | ⭐ all five **12/12 · `COMPLETE`** |
| **Backlog** | admissible **21 → 16** · `COMPLETE` **12 → 17** |
| **New objects** | `T-0055` · `T-0056` · `T-0057` · `T-0058` — ⛔ 0 existing rows modified |

---

## 1 · ⛔⛔ A defect this batch found in **my own** batch-2 work

> ### **`T-0052` duplicates `T-0034`. I created it. And I created it by the exact failure both objects describe.**

| | |
|---|---|
| **`T-0034`** *(existing, source `F0034`)* | *"Model amnesia at n=11"* — **"Proposing-before-searching has ELEVEN recorded occurrences"** |
| ⛔ **`T-0052`** *(created by me, batch 2, source `F0009`)* | *"Proposing before searching — the corpus's own failure mode, **n=5**"* |

**Root cause, verified by re-running the probe:** batch 2's canonical-discovery used **space-separated substrings** — `"proposing before"`, `"search before"`, `"before searching"`, `"failure mode"`. `T-0034` writes it **hyphenated**: `Proposing-before-searching`. ⛔ **All four terms return `False`.**

> ⭐ **The duplicate is of the object that names *proposing before searching*, and it exists because the search was too weak to find it.** An `ES-005.4` violation by this session.

**Remedy applied immediately, and it changed results.** Batch 3's discovery was re-run with a **normalized** probe (punctuation and hyphens folded). Under it, *"four progression kinds"* and *"check-before-recording"* correctly returned **NONE** where the naive probe had produced false hits, and the blind-review instrument was correctly identified as absent.

⛔ **`T-0052` not revised** — an existing registry row, §5A blocked on `m1`/`m2`. Recorded append-only as **`C-0026` · `RO-0045`**. Merge, retain as an earlier-`n` sibling, or void is a later authorized act.

---

## 2 · ⭐⭐ The `B-12` referent, located

> **`F0014` §7:** *"`H-3` (resolved) — Knowledge Spaces NEST — **closed by `G-4`**."*
> **`F0014` §3:** its **own** `G-4` = *"PRODUCT vs DEPLOYMENT / INSTANTIATES"*, which dissolves — *"`G-4` answers `H-3` … using evidence rather than preference."*

⭐ **The `G-4` in that sentence is file-local, defined four sections earlier in the same document.** ⛔ Not `F0001`'s *(no second runtime adapter)*; not `F0011`'s *(no second adopting product)*.

**Together with batch 2's `C-0022`, `B-12` now has three referents and a mechanism for the divergence:**

| | `G-4` |
|---|---|
| `F0014` | ⭐ **PRODUCT vs DEPLOYMENT** — the one in *"closed by G-4"* |
| `F0011` | no second adopting product |
| `F0001` | no second runtime adapter *(the series shifted when `F0011`'s `G-2` closed)* |
| `F0012` | *(a fourth variant: `G-C1`–`G-C7`)* |

⛔ **`C-0027` · `RO-0046` — REFERRED.** `B-12` owns the adjudication; this supplies the referent and asserts no resolution.

---

## 3 · ⭐⭐⭐ Independence, located — and it is rare

**`F0015` is a blind pass:** an isolated session, no conversation context, the conclusion corpus **blocked at the path level**, because *"I could not be the reviewer — I authored every conclusion under test."*

**Scoreboard: 12 reproduced blind · 4 sharpened · ⛔ 3 corrected · 2 new.**

> ### ⭐ **Of everything audited in this programme, only `F0015`'s twelve convergences can support `RA-15` status D (INDEPENDENTLY CORROBORATED).** Same-programme agreement is transmission; a blind pass with blocked paths is not. → **`T-0055` · `C-0029` · `RO-0049`**

**And it corrected the track in three places** — *"0 traversals"* falsified (`n≈3`: `R-36` · `R-41` · `R-63`), occurrence **#11** (an entire domain-model track run without consulting the frozen six-context map), and `I-8` over-granted. ⭐ **It also audits its own reviewer**, flagging a quoted prompt sentence the prompt never contained.

---

## 4 · ⭐ The provenance pattern, now at five instances

| Object | attributed to | earlier source found |
|---|---|---|
| `T-0029` | `F0029` | `F0011` *(pos 11)* |
| `T-0041` | `F0036` | `F0013` *(pos 13, the remedy)* |
| `T-0043` | `F0039` | `F0013` *(admits what `T-0043` calls candidates)* |
| ⭐ `T-0033` `T-0034` `T-0038` | `F0034` | ⭐ **`F0015` (pos 15) states all three** |

> ⭐ **Retrofitting in canonical order keeps surfacing earlier provenance than the research first recorded.** ⛔ None written — all are other files' objects under `O-1`=A.

---

## 5 · Method quality found in the batch

| File | |
|---|---|
| **`F0014`** | ⭐⭐ **Check-first**: nine lifecycles found *before* any gap was claimed. *"A fifth error was avoided by checking: I was about to report 'there is no lifecycle.'"* |
| **`F0018`** | ⭐⭐ **A question dissolved, not answered** — and the prior question conceded as *solution design*, *"exactly what DDD forbids"* |
| **`F0017`** | ⭐⭐ **Novelty held to evidence** — *"novel AND undemonstrated; the two must never be confused"* |
| **`F0016`** | ⭐ **The check changed the proposal's shape** — a proposed "Provenance Domain" was found already dispersed across five homes |
| **`F0015`** | ⭐⭐⭐ **An independence instrument that audits its own reviewer** |

---

## 6 · Where the theory now stands on two long-running threads

**`I-4`.** `F0014` is where the falsification is **withdrawn**, with its mechanical cause; `F0018` then dissolves the repair it implied. ⭐ That completes the chain behind `T-0026`'s qualification — ⛔ still not written, `T-0026` being `F0027`'s object and held on `m1`/`m2`.

**The back-edge.** `F0015` falsifies *"0 traversals"* at `n≈3`; `F0004` separates it into three levels; `F0003` records the rejection. ⭐ **The corpus settled this before the research re-derived it.**

---

## 7 · Next

**Batch 4 — `F0019` · `F0020` · `F0021` · `F0022` · `F0023`**, next in canonical order among the 16 remaining admissible files.

⚠️ *`F0022` and `F0023` are the two files the architecture's `ACL-1` was derived from — excluded once by document kind and later found heavily theory-bearing. Worth reading with that in mind.*

---

*Batch 3 · 5 files · 5 receipts · 12/12 each · 4 new objects · ⛔ 0 existing rows modified · 1 self-inflicted defect found and recorded · 0 held files touched · Candidate Theory v0.9 unchanged.*

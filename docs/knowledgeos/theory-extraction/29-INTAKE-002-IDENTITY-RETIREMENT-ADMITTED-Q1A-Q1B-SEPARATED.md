# `INTAKE-002` — Identity Retirement ADMITTED · `Q1a` / `Q1b` Separated

**2026-09-07 · Lane T · the SECOND formal admission record.**
Source proposal: [`28` `PROPOSAL-Q1`](./28-PROPOSAL-Q1-IDENTITY-RETIREMENT-CANDIDATE-RULING.md) · request: [`27` `P-12` §11 `IR-Q1`](./27-P12-CROSS-LANE-KERNEL-RESOLUTION-AND-ADMISSION-PREPARATION.md).

> ⛔ **3MC / MD-018 untouched · Schema v2 unmodified · v3 not begun · `F-4`/`F-5` unrepaired ·
> `Q2`, `Q3w`/`W6`, `Q3`, `Q4`, kernel identity, equivalence NOT resolved · no new capability · no
> architecture, database representation, event sourcing, temporal tables, UUID, surrogate key, storage
> or aggregate · no frozen derivation reopened · ⛔ `P-13` NOT created.**

## Note on sequencing
⚠️ An **adjudication-request** artifact (`29-IR-Q1`) was prescribed on the assumption that `Q1` was
still open. **Lane M has now accepted, so a request for that decision is moot** and was not created.
⭐ **The refinement it carried — separating `Q1a` from `Q1b` — is applied here instead**, so no
substance is lost.

---

# 1. The admission record

| field | content |
|---|---|
| **source proposal** | `28-PROPOSAL-Q1` |
| **decided by** | ⭐ **Lane M / Governance** — ⛔ **not Lane T** |
| ⭐ **level occupied** *(of `P-12` §12's six)* | ⭐⭐ **ADJUDICATION → STIPULATION.** ⛔ **not source text, not measurement, not interpretation** |
| **corpus state** | ⚠️ **`n/a by kind`** — a ruling, not a measurement. ⭐ Why `W6` did not obstruct this intake |
| ⭐⭐ **this is a RULING, not corpus evidence** | ⭐ **The corpus still contains NO statement permitting or forbidding retirement. The ruling SUPPLIES the premise; it does not DISCOVER it.** `PROPOSAL-Q1`'s *"practice is not permission"* stands as recorded |
| **revocation condition** | ⭐ **`X3`** — no mechanism propagates a reversal to Lane T; re-confirmation would be required |

## The two propositions, decided separately

| | proposition | decision | epistemic status |
|---|---|---|---|
| ⭐ **`Q1a`** | **May an established KnowledgeOS candidate identity be retired?** | ⭐⭐ **YES** | ⭐ **`STIPULATED`** |
| ⭐ **`Q1b`** | **Is an explicit warrant mandatory for a permitted retirement?** | ⭐⭐ **CONFIRMED** | ⭐⭐⭐ **`[DERIVED]` — see §2** |

⭐ **Rationale on the record:** the estate has practised **retirement-with-retention five times and
deletion never**; `τ11` **split** is corpus-attested; **merge is scheduled** across **125**
`unresolved_equivalence` files. ⚠️ **These establish that the transition OCCURS — the permission is the
ruling's own contribution.**

---

# 2. ⭐⭐⭐ Why `Q1b` is `[DERIVED]` and not a second stipulation

`P-09.1` §5 **already** derived the retirement-warrant obligation and assigned it to **`K3b`** —
independently of, and prior to, any ruling.

$$\boxed{\begin{array}{c}\textbf{The warrant OBLIGATION is } \mathbf{[DERIVED]}\textbf{. Its APPLICABILITY is } \mathbf{[DER\text{-}S{:}Q1a]}.\\[4pt] \textbf{Before } Q1a\textbf{, it was a derived obligation with NO INSTANCES — vacuously true.}\\ \textbf{After } Q1a\textbf{, it acquires instances.}\end{array}}$$

⭐⭐ **Consequence of the split:** `Q1b` **creates no new `[DER-S]` dependency.** Only `Q1a` does.
⛔ **So the index is `[DER-S:Q1a]`, never `[DER-S:Q1]`** — had the two been bundled, the warrant
obligation would have been mislabelled as stipulation-dependent when it is not.

$$\boxed{\textbf{Lane M CONFIRMED an existing obligation rather than legislating a new one. ⛔ This is RATIFICATION, not kernel growth.}}$$

⛔ **`K3b` and `K4c` are NOT merged** merely because they now co-occur — co-occurrence is not
derivability (`P-09.2` §9).

---

# 3. Label indexing — now applied

⭐ `PROPOSAL-Q1` §3 prepared this and deferred it to the acceptance reconciliation. **This is that
moment.**

$$\boxed{\begin{array}{ll}\mathbf{[DER\text{-}S{:}R0]} & \textbf{from the MD-018 MONOTONICITY stipulation } (\textit{INTAKE-001})\\ \mathbf{[DER\text{-}S{:}Q1a]} & \textbf{from the IDENTITY-RETIREMENT stipulation } (\textit{this record})\end{array}}$$

**`INTAKE-001`'s four dependents are now indexed `[DER-S:R0]`** — `K3` exceptional scope · `K2`
independent necessity · `P-08` §17 restored redundancy · `P-08` §19 standing negative. ⛔ **A labelling
refinement, not a reopening.** ⭐ **Revoking either stipulation now touches only its own dependents.**

# 4. `R0` / `Q1a` consistency — carried forward

| | operates on | |
|---|---|---|
| **`R0`** | the **status chain** of an existing identity | $\text{status}(x,t)$ — no backward movement |
| **`Q1a`** | the **identity population** (`τ11`) | $x \in \mathcal{I}_t,\ x \notin \mathcal{I}_{t+1}$ — permitted, with warrant |

$$\boxed{\textbf{Different state dimensions } \Rightarrow \textbf{ INDEPENDENT and NON-CONFLICTING. Retirement is NOT a status regression. } \mathbf{[DERIVED]}\textbf{, acceptance-independent.}}$$

---

# 5. `K4c` activation — and the kernel does **not** grow

| cell | before | after | basis |
|---|:--:|:--:|---|
| **`K4c-i`** retired identity retained | ⚠️ CONDITIONALLY CLOSED | ⭐ **CLOSED** | ⭐ `[DER-S:Q1a]` |
| **`K4c-ii`** `retired-into` + cardinality | ⚠️ CONDITIONALLY CLOSED | ⭐ **CLOSED** | ⭐ `[DER-S:Q1a]` |

$$\boxed{\begin{array}{c}\textbf{KERNEL = 11 CELLS. NO cell added. NO cell removed. TWO changed STATUS.}\\[6pt] \boxed{\begin{array}{l}\textbf{This is ACTIVATION of an already-derived capability under a newly admitted premise —}\\ \textbf{⛔ NOT the discovery of a new capability. The distinction is load-bearing and is kept explicit.}\end{array}}\end{array}}$$

| | before | after |
|---|:--:|:--:|
| **CLOSED** | 3 — `K1`, `K4a-i`, `K5` | ⭐ **5** — `+ K4c-i`, `K4c-ii` |
| **CONDITIONALLY CLOSED** | 8 | ⭐ **6** — `K2`, `K3a`, `K3b`, `K4a-ii`, `K4b-i`, `K4b-ii` |
| **OPEN non-cells** | 2 | **2** — corpus-state identification, third axis |

## Obligations now in force — exactly `P-09.1` §5, nothing added

| arity | obligation |
|---|---|
| **`1→0`** retirement | retain the predecessor · record an **empty** successor set · ⭐ *retired* must be distinguishable from *never existed* |
| **`1→n`** split | retain the predecessor · record an **`n`-element** successor set · **per-claim warranted reassignment** |
| **`n→1`** merge | retain **every** predecessor, each recording a **1-element** successor set |

⭐ **And by `Q1b`: no `K4c` event may occur without a `K3b` warrant.**
⛔ **Deletion remains excluded by `K4c-i`, not by the ruling** — `K4c-i` requires the retired identity
to be **retained**, and the estate has practised retention **5×** and deletion **never**.

---

# 6. Consequences recorded — `P-13` deliberately not performed

⭐ **These are the intake schema's *downstream consequences* field, recorded. The fuller reconciliation
is `P-13`'s and is NOT created here.**

## Unblocked

| | was | now |
|---|---|---|
| ⭐ **`Q2`** semantic reinterpretation | **BLOCKED by `Q1`** — World II *(`A ≺ A′`, predecessor retired)* unavailable | ⭐⭐ **UNBLOCKED** — a live **two-way** identity adjudication. **Lane M** |
| ⭐ **`Q8`** `Q3` (`ℐ`/`𝓘`: one candidate or two?) | **not performable** | ⭐⭐ **PERFORMABLE** — answering *"one"* **exercises `K4c`** at `n→1` |

⭐ **`P-12`'s dependency graph is discharged of its one blocking edge.** ⛔ **Neither is answered.**

## Made live — not admitted

**`O-INT002-1`:** *may a retirement itself be withdrawn, and does `retired-into` then bear `K4a`?*
Retirement is now **performable**, so a merge later judged wrong would make that relation a **withdrawn
claim**. ⭐ Touches **`P-10` §3's `B-fail-1` stratification worry** — a second stratum now reachable in
practice. **Class `C1` (scope only)** — `K4a` already exists, **no witness exists**, and the stopping
rule therefore admits **no capability**. `[OPEN]`.

## Re-ranking

| rank | question | why |
|:--:|---|---|
| ⭐⭐ **1** | **`Q3w` — `W6` class (`C2` vs `C3`)** | ⭐ **the largest kernel effect available — it can ADD A 12TH CELL.** Independent · **decidable in principle** (`P-12` §6's `H1`/`H2` pair `K5` cannot express) · blocks Gates `D` **and** `E` |
| **2** | **`Q2`** | unblocked, but ⚠️ **cannot close `K2`-A alone** — one of two directions; the converse needs **model replacement** |
| **3** | **`Q8`** | performable; **exercises** `K4c` — class `C` |
| **4** | `Q5r` · `Q4a` | ⭐ **dormant — awaiting a witness, not a decision** |
| **5** | `Q6` · `Q7` | class `B` — interpretation and content only |

⭐ **`Q3w` overtakes `Q2`**, because `Q2` cannot finish `K2`-A alone while `Q3w` can change the cell
count.

## Gates

| gate | before | after |
|---|:--:|:--:|
| **A** semantic closure | ⚠️ CONDITIONAL (3/8) | ⭐ ⚠️ **CONDITIONAL — improved (5/6)**; `Q3w` could still add a 12th cell |
| **B** governance | ✅ PASS `[stipulated]` | ⭐ ✅ **PASS `[stipulated ×2, verified non-conflicting]`** |
| **C** transition closure | 🔴 BLOCKED | 🔴 **BLOCKED** — `Q2` unblocked but **unanswered**; model replacement unclassified |
| **D** corpus-state | 🔴 BLOCKED | 🔴 **BLOCKED** — `W6` live |
| **E** cross-lane | 🔴 BLOCKED 1/9 | ⭐ ⚠️ **PARTIAL — 2 of 9** |

$$\boxed{\textbf{ARCHITECTURE COMPARISON STILL NOT PERMITTED. ⛔ No gate lowered.}}$$

---

# 7. Decision boundary

**`[EMP]` — unchanged by the ruling** ⭐ **no corpus statement permits or forbids retirement** ·
retirement-with-retention **5×**, deletion **never** · `τ11` split attested · merge **scheduled**, 125
files · zero direction language for `status_chain`.

**`[DERIVED]` — stipulation-independent** ⭐ **`R0` and `Q1a` operate on different state dimensions and
do not conflict** · ⭐⭐ **the retirement-warrant obligation itself (`Q1b`), derived at `P-09.1` §5 and
merely CONFIRMED here** · **co-occurrence is not derivability, so `K3b` and `K4c` do not merge** ·
**deletion is excluded by `K4c-i`, not by any ruling** · ⭐ **practice is not permission**.

**`[DER-S:Q1a]`** `K4c-i` and `K4c-ii` **CLOSED and ACTIVE** · the three arity obligations **in force** ·
the warrant obligation's **applicability**.

**`[DER-S:R0]`** *(now indexed)* `K3` exceptional scope · `K2` independent necessity · `P-08` §17 ·
`P-08` §19.

**`[OPEN]`** ⭐ **`Q3w`/`W6` and its class — now rank 1** · **`Q2`** *(unblocked, Lane M)* · **`K2`-A**
*(needs `Q2` **and** model replacement)* · **`O-INT002-1`** *(live, `C1`)* · third axis *(witness
absent)* · role overload *(one direction)* · `Q3` · `Q4` · kernel identity · equivalence · Claim B ·
aggregate existence · ⭐ **`O-INT001-1`'s governance rule, still owed**.

**`[ARCH]`** everything mechanical. ⛔ **No label promoted for convenience.**

---

```
INTAKE-002 ADMITTED — IDENTITY RETIREMENT PERMITTED, WITH WARRANT

Q1a — May an established KnowledgeOS candidate identity be retired?
Decision: YES
Epistemic status: STIPULATED

Q1b — Is explicit warrant mandatory for permitted retirement?
Decision: CONFIRMED
Epistemic status: [DERIVED] — NOT a second stipulation

Authority: Lane M / Governance          Source proposal: 28-PROPOSAL-Q1
Level occupied: ADJUDICATION -> STIPULATION      Corpus-state field: n/a by kind
This is a RULING, not corpus evidence: the corpus still contains NO statement permitting or forbidding
retirement. The ruling SUPPLIES the premise; it does not DISCOVER it. "Practice is not permission"
stands as recorded — the five observed retirements establish that the transition OCCURS.

WHY THE SPLIT MATTERED:
  P-09.1 §5 had already derived the retirement-warrant obligation and assigned it to K3b. So the
  OBLIGATION is [DERIVED] and only its APPLICABILITY is [DER-S:Q1a] — before Q1a it was a derived
  obligation with NO INSTANCES, vacuously true; after Q1a it acquires instances. Q1b therefore
  creates NO new [DER-S] dependency, and the index is [DER-S:Q1a], never [DER-S:Q1]. Bundling the
  two would have mislabelled the warrant obligation as stipulation-dependent when it is not.
  Lane M CONFIRMED an existing obligation rather than legislating a new one — RATIFICATION, not
  kernel growth. K3b and K4c are NOT merged: co-occurrence is not derivability.

LABELS NOW INDEXED: [DER-S:R0] monotonicity · [DER-S:Q1a] retirement. INTAKE-001's four dependents
  indexed [DER-S:R0]. Revoking either stipulation now touches only its own dependents.

CONSISTENCY: R0 governs status(x,t); Q1a governs x in I_t. Different state dimensions, INDEPENDENT and
  NON-CONFLICTING. Retirement is NOT a status regression. [DERIVED], acceptance-independent.

K4c: ACTIVATED. K4c-i and K4c-ii CONDITIONALLY CLOSED -> CLOSED [DER-S:Q1a]. Obligations exactly as
  P-09.1 §5 derived them for 1->0, 1->n, n->1; nothing added. No K4c event without a K3b warrant.
  Deletion remains excluded by K4c-i, NOT by the ruling.

KERNEL: 11 CELLS — NO cell added, NO cell removed, TWO changed status.
  5 CLOSED (was 3) · 6 CONDITIONALLY CLOSED (was 8) · 2 OPEN non-cells.
  This is ACTIVATION of an already-derived capability under a newly admitted premise — NOT discovery
  of a new capability.

UNBLOCKED: Q2 semantic reinterpretation (World II now admissible — a live two-way identity
  adjudication for Lane M) · Q3 / ℐ vs 𝓘 now PERFORMABLE (answering "one" exercises K4c at n->1).
  P-12's dependency graph is discharged of its one blocking edge. Neither is answered here.

MADE LIVE, NOT ADMITTED: O-INT002-1 — may a retirement itself be withdrawn, and does `retired-into`
  then bear K4a? Class C1, witness absent, no capability admitted. Touches P-10's B-fail-1
  stratification worry, now reachable in practice.

RE-RANKED: Q3w (W6 class) overtakes Q2 — it can ADD A 12TH CELL, whereas Q2 cannot close K2-A alone.

GATES: A CONDITIONAL (improved 3/8 -> 5/6) · B PASS [stipulated x2, non-conflicting] · C BLOCKED ·
  D BLOCKED · E PARTIAL 2 of 9.   ARCHITECTURE COMPARISON STILL NOT PERMITTED · NO SCHEMA v3 · NO A/B/C/D

P-13 NOT CREATED — the fuller reconciliation is its job, not this record's.
NEXT ACTION: obtain the W6 class decision (C2 capability vs C3 mechanism) via IR-Q3w, prepared in
  P-12 §11.
```

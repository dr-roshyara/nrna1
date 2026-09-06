# 00 — Numbering Collision Report (mandate §22)

> **§22: *"Do NOT renumber or overwrite anything. Inspect the registry first. If there is a collision,
> create a collision report and leave numbering unchanged."*** Registry inspected; nothing renumbered.

## Registry as inspected (2026-08-31 23:2x)

| Slot | Occupant in `research/` | State |
|---|---|---|
| 285 | `REFINED-STEP-285.md` | accepted |
| 286 | `REFINED-STEP-286.md` | accepted |
| **287** | **`REFINED-STEP-287.md` (equality) AND `REFINED-STEP-287-INVARIANTS.md`** | 🔴 **COLLISION — standing** |
| 288 | `REFINED-STEP-288.md` + `step-288/` | v3, corrected by 290 |
| 289 | `REFINED-STEP-289.md` + `step-289/` | accepted 23:01; corrected by 290/291 |
| 290 | `REFINED-STEP-290.md` + `step-290/` | N-1 performed |
| **291** | **FREE** → occupied by this step | — |

## Collision 1 — `287` (standing, unresolved)

Two artifacts carry 287: the **equality/identity/observability** step and the **Invariants ℐ** step.
Three prompt sources place equality at 285 and Invariants at 287. **Recorded since 2026-08-31 21:1x;
never adjudicated.** **No renumbering performed.**

## Collision 2 — `289` (identified 23:03, unresolved)

| Prompt | mtime | Subject |
|---|---|---|
| `20260831-224400_step_289_…dependency-graph-bootstrap-boundary…` | 22:44 | **executed as Step 289** — accepted 23:01 |
| `20260831-225400_step_289_…operations-mandate…` | 22:54 | **OPERATIONS** — superseded by the 23:03 file |
| `20260831-230300_step_289_…operations-and-transformation-semantics-tightened…` | 23:03 | **OPERATIONS**, ~25 sections, six tightenings |

**Three prompts, one number, two entirely different subjects.**
⚠️ **The OPERATIONS mandate has no free slot.** Step 290 noted *"the natural slot is 291"* — **and 291 is
now taken by this `𝒪`/`𝒯` closure audit**, which the 23:17 mandate assigned there explicitly (its §18:
*"research/step-291/exec/ or the next correctly assigned research-step directory after checking the
registry"*).

$$\boxed{\textbf{The OPERATIONS mandate is UNSCHEDULED. Its natural slot is now 292. NOT assigned here.}}$$

## Overlap check — does 291 pre-empt the OPERATIONS mandate?

⚠️ **Partially, and this must be visible.** `step-291/03_operation-registry-audit.md` builds the
22-operation inventory that the OPERATIONS mandate's §4 also commissions. **They are not duplicates:**

| | Step 291 `03` | the OPERATIONS mandate |
|---|---|---|
| purpose | **is `𝒪` closed?** — a closure question | **what IS `𝒪`?** — a construction question |
| depth | 22 rows × 7 columns | ~25 sections: composition, idempotence, replay, determinism, partiality, operation↔event↔policy↔governance distinctions |
| output | *"22 named · 8 typed · 0 bodies · 0 ratified"* | typed operation semantics per operation |

**Step 291 measured the registry; it did not reconstruct it.** The OPERATIONS mandate remains
substantially unexecuted.

## Recommendation *(a registry recommendation only — no authority claimed)*

1. **Adjudicate the `287` collision** — trivial, and it has been carried for hours.
2. **Assign the OPERATIONS mandate a free slot (292)** — or explicitly retire it as superseded.
3. **Neither is performed here.** `NORMATIVE DECISION REQUIRED` · authority: **registry / ARB**.

---

## ⭐⭐ ROOT CAUSE IDENTIFIED (2026-09-01, `29-TWO-SYNTHESES-AND-THE-STEP-NUMBER-COLLISION.md`)

**This report treated the `287` and `289` collisions as local registry accidents. They are not.**

`external_research`'s complete Gītā synthesis closes with a **"Next Steps"** table assigning:

| Step | `external_research` assigns | the research lane holds |
|---|---|---|
| **286** | *"Canonical Type System"* | philosophical-source hypothesis programme |
| **287** | *"Canonical State Model — formal freeze"* | equality/identity/observability **+ Invariants** |
| **288** | *"Canonical Invariant Registry"* | equality decision-procedure reconciliation |
| **289** | *"Canonical Operation Derivation"* | dependency graph / bootstrap boundary **+ 2 OPERATIONS mandates** |
| **290** | *"Transformation Algebra"* | N-1 adjudication |

$$\boxed{\begin{array}{c}\textbf{FIVE consecutive step numbers, two lanes, disjoint content.}\\ \textbf{The } 287 \textbf{ and } 289 \textbf{ collisions are SYMPTOMS of one cause.}\end{array}}$$

⚠️ **And the same cause explains the five GLYPH collisions** (`𝒪` · `≅`/`≡` · `δ` · `ℐ` · `M`, `G-100`)
**and the seven operation vocabularies:** two lanes minting step numbers, symbols and operations in
parallel **with no shared registry**. ✅ Consistent with `G-99`'s **broken intake path** — the same
defect at three levels.

**`G-101` — a REGISTRY defect, not a theory defect. Nothing renumbered here; §22's discipline holds.**

---

## ⭐⭐ ESCALATION (2026-09-01, `34-THE-UNSCHEDULABLE-PROGRAMME-AND-THE-DIMENSION-MODEL.md`)

**The OPERATIONS programme has now been proposed FOUR times under THREE step numbers, and every number
it claims is occupied:**

| # | claims | that slot holds |
|---|---|---|
| 1 · 2 | **289** (×2) | dependency graph / bootstrap boundary |
| 3 | **286** | philosophical-source programme |
| 4 | **290** — header `artifact: REFINED-STEP-290 — OPERATION SEMANTICS` | **N-1 adjudication** |

⚠️ **#4 lists `depends on: Steps 261, 285–289`** — it knows 289 exists and still claims 290 unchecked.

$$\boxed{\begin{array}{c}\textbf{The estate's most-blocked item }(N\text{-}4)\textbf{ is blocked TWICE:}\\ \textbf{once on ratification, and once on a STEP NUMBER.}\end{array}}$$

**`G-106` — the second block is trivially fixable and nobody owns it.** **This report's recommendation
(assign a free slot, or explicitly retire the duplicates) is now URGENT. Nothing renumbered here.**

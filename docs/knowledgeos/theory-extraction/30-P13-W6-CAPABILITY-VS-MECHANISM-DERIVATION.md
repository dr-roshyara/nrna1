# `P-13` — `W6`: Capability vs Mechanism Derivation

**2026-09-07 · Lane T.** After [`29` `INTAKE-002`](./29-INTAKE-002-IDENTITY-RETIREMENT-ADMITTED-Q1A-Q1B-SEPARATED.md).

> **The question:** *is corpus-state identification an independent persistence capability (`C2`), or is
> it already contained in the existing kernel, with only its realization outstanding (`C3`)?*
>
> ⛔ **No architecture · no Schema v3 · no mechanism designed · `Q2` not resolved · no new capability
> discovered · `K5` not recursively decomposed · 3MC / MD-018 untouched · Schema v2 unmodified ·
> `F-4`/`F-5` unrepaired · `Q1b` not relitigated.**

# 1. Starting state — carried forward

`INTAKE-001`: **`R0` monotonicity `[STIPULATED]`** · `INTAKE-002`: **`Q1a` retirement = YES
`[STIPULATED]`**, **`Q1b` warrant `[DERIVED]`**, applicability `[DER-S:Q1a]` · **`K4c-i`/`K4c-ii`
CLOSED and ACTIVE** · **11 cells: 5 CLOSED · 6 CONDITIONALLY CLOSED · 2 OPEN non-cells** · `Q2`
unblocked, unanswered · `Q3`/`Q8` performable, an **exercise** of `K4c` · third axis `[OPEN]`, **no
witness** · role overload `[OPEN]`, one direction.

⭐ **A wording refinement accepted from review, applied from here on** — ⛔ `INTAKE-002` not reopened:

> `P-09.1` derived the warrant requirement **independently of `Q1a`**; `Q1a` determines whether the
> retirement transition is **admissible**, and therefore whether that requirement can have **admitted
> instances**.

⚠️ **"Vacuously true" is retired as a formulation** — it carried more architectural weight than the
argument needs.

---

# 2. The distinction, defined without the words *"corpus state"*

| | history |
|---|---|
| **`H1`** | a cited measurement was **true of state `S₁`**, and the corpus subsequently changed |
| **`H2`** | the cited measurement was **never true** |

$$\boxed{\textbf{The claimed distinction is the GROUND OF A MEASUREMENT CLAIM'S VALIDITY: } \textit{true-of-a-state} \textbf{ vs } \textit{never-true}.}$$

⭐ **And `K5` is genuinely blind to it** — in both histories the current ground is equally uncheckable.
⚠️ **But `P-12` §6 stopped there, and the criterion requires more.**

# 3. The capability criterion, applied strictly

| condition | |
|---|---|
| 1 | the histories differ in one **required** loss distinction |
| 2 | the distinction **can be lost** |
| 3 | ⭐⭐ **ALL existing closed capabilities are identical** |
| 4 | the outcome is one the current kernel **cannot represent** |
| 5 | it is not merely a field · mechanism · address · provenance role · representation choice · DDD category · implementation artifact |

⭐⭐⭐ **`P-12` §6 tested only `K5`. Condition 3 requires every capability.** That omission is what §4
now repairs.

---

# 4. ⭐⭐⭐ Reduction — Model A succeeds, and the `P-12` pair fails condition 3

**What each history actually produces in the estate:**

| | `H1` *(stale-but-valid)* | `H2` *(wrong)* |
|---|---|---|
| the later re-measurement | ⭐ **a DISTINCT OBSERVATION** — `P-09` §7: a measurement at a **different** corpus state is a new fact, **not** a supersession | ⭐ **a SUPERSESSION** of the earlier claim |
| **`K4a-i`** prior proposition | retained | retained |
| ⭐⭐ **`K4a-ii`** supersession relation | ⭐ **ABSENT** | ⭐ **PRESENT** |
| ⭐ **`K3b`** warrant | absent | ⭐ **present — naming the defect** |

$$\boxed{\begin{array}{c}\textbf{The two histories are NOT identical in } K4a\text{-ii} \textbf{ and } K3b. \textbf{ Condition 3 FAILS.}\\[6pt] \boxed{\textbf{MODEL A SUCCEEDS: } \textit{stale-but-valid} \textbf{ vs } \textit{wrong} \textbf{ is already borne by the SUPERSESSION RELATION plus its WARRANT.}}\end{array}}$$

⚠️ ⭐ **This corrects `P-12` §6, which concluded *"a genuine loss distinction `K5` cannot express"*.
That was true of `K5` and insufficient as a capability argument.** ⛔ **`P-12` is not rewritten; the
correction is recorded here.**

## The harder pair — Model B, constructed and then defeated

⭐ **Reduce further, as required.** Take the pair **before any adjudication**:

| | `H1′` | `H2′` |
|---|---|---|
| claim `m = 143` cited | ✅ | ✅ |
| corpus later differs on re-run | ✅ | ✅ |
| ⭐ **supersession recorded** | 🔴 **none — nobody has looked** | 🔴 **none — nobody has looked** |
| `K1` `K2` `K3a` `K3b` `K4a` `K4b` `K4c` `K5` | ⭐ **all identical** | ⭐ **all identical** |
| the truth of the matter | **true of `S₁`** | **false** |

⭐ **Condition 3 now holds.** So does the pair force `C2`?

$$\boxed{\begin{array}{c}\textbf{NO. } H_1' \textbf{ and } H_2' \textbf{ differ in a fact about the WORLD, not in anything the estate has RECORDED.}\\[4pt] \textbf{A persistence capability preserves what the estate has recorded — never what is true but undetermined.}\\[6pt] \boxed{\begin{array}{l}\textbf{Requiring persistence to separate } H_1' \textbf{ from } H_2' \textbf{ would require it to encode UNKNOWN TRUTH.}\\ \textbf{That is a category error, not a capability.}\end{array}}\end{array}}$$

⭐⭐ **The distinction is UNDETERMINED until adjudicated — and the moment it is adjudicated, §4's
reduction applies and `K4a-ii` + `K3b` bear it.** ⛔ **Model B fails at condition 1: the distinction is
not *required* to be preserved, because it is not yet a record.**

---

# 5. `A` / `B` / `C` kept apart

| | proposition | verdict |
|---|---|---|
| **`A`** | a measurement-grounded claim requires its **historical evaluation context to remain identifiable** | ⭐ **`[DERIVED]`** — without it, *"143"* is uninterpretable (`P-08` §13) |
| **`B`** | that context must be persisted as an **INDEPENDENT capability** | ⭐⭐ 🔴 **NOT independently required** — it is **content of a warrant**, and the warrant cell already exists |
| **`C`** | a concrete **mechanism** must exist for identifying corpus state | ⭐ **`[ARCH]`** |

$$\boxed{\mathbf{A = [DERIVED]} \qquad \mathbf{B = not\ independently\ required} \qquad \mathbf{C = [ARCH]}}$$

⛔ **`C2` is not forced merely because `C` is technically necessary for implementation** — that is the
inference this derivation exists to refuse.

---

# 6. Information-theoretic test

Let $\sim_K$ = *"all existing capabilities retain identical information"* and $\sim_{req}$ =
*"the estate is not required to distinguish"*.

| pair | $\sim_K$? | $\sim_{req}$? | verdict |
|---|:--:|:--:|---|
| **`H1` / `H2`** *(adjudicated)* | 🔴 **NO** — `K4a-ii`, `K3b` differ | 🔴 no | ⭐ **already separated by the kernel** |
| **`H1′` / `H2′`** *(unadjudicated)* | ✅ **yes** | ⭐ ✅ **YES — the estate has not determined which** | ⭐ **no required separation** |

$$\boxed{\textbf{There is no pair with } H_1 \sim_K H_2 \textbf{ and } H_1 \not\sim_{req} H_2. \textbf{ ⛔ No new semantic distinction survives.}}$$

⛔ **No field counting · no storage size · no implementation complexity.**

---

# 7. Statistical sanity check

$$\boxed{\textbf{measurement} \neq \textbf{measurement result} \neq \textbf{validity of the measurement} \neq \textbf{corpus state at measurement time}}$$

**Which is corpus-state-at-measurement-time?**

| candidate | verdict |
|---|---|
| 1 an **attribute of the measurement** | ⚠️ **derivatively yes** — via 2 |
| 2 ⭐⭐ **a component of its WARRANT** | ⭐⭐⭐ ✅ **THIS.** *"valid at `S₁`"* is a **warranted claim**, and `S₁` is a term inside the warrant |
| 3 a component of **`K5`** | 🔴 **no** — `K5` concerns the **current** ground's checkability; a **historical** state is not the current ground |
| 4 an **independent** persistence distinction | 🔴 **no** — §4, §6 |

⭐⭐ **A refinement of the `C3` wording, not a fourth category:** `C3` was phrased as *"a mechanism
supporting `K5`"*. **The bearer is `K3b`'s warrant content, not `K5`'s mechanism.** ⛔ **This remains
the "not an independent capability" branch — no category is invented.**

⭐ **Reproducibility** is separate again, and `P-09` §4.2 already settled it: **restorability is not part
of the minimum; distinguishability is.**

# 8. Mathematical sanity check

$$H_1 = (S_1,\ m = 143,\ \textbf{valid}) \qquad H_2 = (S_1,\ m = 143,\ \textbf{invalid})$$

| which retained proposition distinguishes them? | |
|---|---|
| ⭐ the **validity assertion** | an **assertion** ⇒ `K4a`, with the supersession relation `K4a-ii` marking the invalid case |
| ⭐ **`valid-at-S₁`** | a **warranted claim** whose warrant denotes `S₁` ⇒ `K3b` |

⛔ **`S₁` is NOT thereby an independent capability** — it is a **term inside a warrant**, in the same
way *"wrong scope"* and *"glyph-literal patterns"* are terms inside warrants.

---

# 9. DDD sanity check

⚠️ ⛔ **No DDD category assigned to corpus state.** The behavioural questions only:

| question | answer |
|---|---|
| part of the **semantic claim**? | ⚠️ **no — it QUALIFIES the claim** |
| part of the **warrant**? | ⭐ ✅ **yes** |
| part of **provenance**? | ⚠️ **overlapping** — `P-09` §9's *source-of-content* role |
| requires **independent lifecycle / continuity**? | ⭐ 🔴 **NO** — a state designator, once denoted, never changes |
| can it change **independently of the measurement claim**? | ⭐ 🔴 **no** — it is fixed by the act of measuring |
| does preserving it create a **new loss distinction**? | ⭐ 🔴 **no** — §4, §6 |

$$\boxed{\textbf{No independent lifecycle and no independent variation } \Rightarrow \textbf{ DDD CORROBORATES } C3\textbf{, without supplying the ontology.}}$$

---

# 10. ⭐⭐⭐ The `P-12` parity argument — tested, and REJECTED

`P-12` §6 argued: *recording "valid at `S₁`" requires `K3b`'s warrant to denote a corpus state,
analogous to `K1` being presupposed by every retention record.* ⛔ **Not accepted by analogy. The
logical forms are compared.**

| | `K1`'s presupposition | the proposed corpus-state analogue |
|---|---|---|
| **what presupposes it** | ⭐ **the FORM of every retention record** — `Changed(x,a,b)`, `Supersedes(p,q)`, `RetiredInto(x,Y)` | ⭐ **the CONTENT of one class of warrant** |
| **scope** | ⭐⭐ **universal and unconditional** — no record is well-formed without a denoting term | ⭐⭐ **particular and conditional** — only warrants asserting validity-at-a-state need it |
| **can records exist without it?** | 🔴 **none can** | ⭐⭐⭐ ✅ **YES — and it is `[EMP]`** |

## ⭐⭐ The measured refutation

`[EMP]` **All four of this lane's actual withdrawals were warranted with NO corpus-state designator:**
`INV-9` *("wrong scope")* · the `(Ω,𝓕,P)` zero *("glyph-literal patterns over a LaTeX corpus")* · the
`Θ` withdrawals · `TG-02` *("the relation exists as a six-component vector")*.

$$\boxed{\begin{array}{c}\textbf{UNIVERSAL-FORM presupposition } \neq \textbf{ PARTICULAR-CONTENT presupposition.}\\ \boxed{\textbf{THE PARITY ARGUMENT IS REJECTED — and refuted empirically, not merely disanalogized.}}\end{array}}$$

⭐ **`K1`'s independence stands undisturbed** — the rejection removes a borrowed argument, not `K1`'s
own.

---

# 11. Decomposition · refinement · mechanism · independent capability

| reading | verdict |
|---|---|
| **an independent capability** | 🔴 **refuted** — §4 Model A reduces it; §4 Model B is a category error; §6 finds no separating pair |
| **a decomposition of `K5`** | 🔴 **no** — §7: a historical state is not the **current** ground |
| **a refinement of `K5`** | 🔴 **no** — same reason. ⭐ **The bearer is `K3b`, not `K5`** |
| ⭐⭐ **content within an existing cell** | ⭐⭐⭐ ✅ **THIS — a VALUE inside `K3b`'s warrant** |
| **a mechanism** | ⭐ ✅ **for its realization — `[ARCH]`** |

⭐ **Applying this project's own model-integrity rule** — *new dimension, overloaded dimension, or merely
another value?*

$$\boxed{\textbf{MERELY ANOTHER VALUE. Third application of that rule in this programme, and the cleanest.}}$$

**Independence tests, for the record:** `C2 ⇏ K5` ✅ *(a state designator does not make an external
ground checkable)* · `K5 ⇏ C2` ✅ — ⛔ **but mutual non-implication does not make `C2` a capability**, since
§4 shows the distinction is already borne. ⭐ **`P-09.3` §13's lesson: pairwise non-derivability licenses
nothing.**

# 12. Identification ≠ storage

$$\boxed{\textbf{identify a state} \neq \textbf{store the entire corpus state} \qquad \textbf{historical reproducibility} \neq \textbf{event sourcing}}$$

⛔ **No storage mechanism selected, named or implied.**

---

# 13. `W6` propositions, classified separately

| proposition | class |
|---|---|
| the *stale-but-valid* vs *wrong* history pair **exists** | ⭐ `[DERIVED]` |
| **`K5` alone cannot express it** | ⭐ `[DERIVED]` — ⚠️ **true, and insufficient for a capability** |
| a measurement-grounded claim **requires an identifiable evaluation context** | ⭐ **`[DERIVED]`** — proposition `A` |
| **corpus-state identification is an independent capability** | ⭐⭐ 🔴 **REFUTED** |
| **it is content of `K3b`'s warrant** | ⭐⭐ `[DERIVED]` |
| **a mechanism is required for realization** | `[ARCH]` |
| ⭐ **`W6`'s empirical failure is LIVE** | ⭐⭐ **`[EMP]` — `τ7`'s 56 renames were uncommitted, so existing measurement claims cannot NOW be warranted this way** |
| any governance premise | ⭐ **NONE — this was settled by derivation, not by ruling** |

⛔ **Not collapsed into one `[DERIVED]` statement.**

# 14. Decision

$$\boxed{\mathbf{C3} \textbf{ — corpus-state identification is NOT an independent persistence capability.}}$$

⭐ **With the §7 refinement: it is borne by `K3b`'s warrant content, not by `K5`'s mechanism.** ⛔ **No
fourth category invented; this is the "not independent" branch.**

# 15. Kernel consequence

$$\boxed{\textbf{KERNEL REMAINS 11 CELLS. } K5 \textbf{ semantically intact. No cell added, none removed, none changed status.}}$$

| | |
|---|---|
| ⭐ **`K3b` gains an obligation on its CONTENT** | a warrant asserting *validity-at-a-state* **must denote that state**. ⭐ **`[DERIVED]`, and not a new cell** |
| **corpus-state identification** | ⭐ **`[ARCH]` mechanism obligation** — ⛔ **not designed here** |
| **closure matrix** | ⭐ **one OPEN non-cell resolves.** `2 OPEN non-cells → 1` *(the third axis alone)* |

# 16. Gate effect

| gate | before | after |
|---|:--:|:--:|
| **A** semantic closure | ⚠️ CONDITIONAL — 5/6, **2** OPEN non-cells | ⭐ ⚠️ **CONDITIONAL — improved: 5/6, `1` OPEN non-cell** |
| **B** governance | ✅ PASS `[stipulated ×2]` | ✅ **unchanged** |
| **C** transition closure | 🔴 BLOCKED | 🔴 **unchanged** — `Q2` unanswered, model replacement unclassified |
| **D** corpus-state | 🔴 **BLOCKED — class undecided** | ⭐⭐ 🔴 **BLOCKED — class RESOLVED, mechanism ABSENT.** A real narrowing, ⛔ **not a pass**: `W6`'s failure is live |
| **E** cross-lane | ⚠️ PARTIAL 2/9 | ⭐ ⚠️ **PARTIAL — 2 of 8.** ⭐⭐ **`Q3w` LEAVES the queue: it needed no Lane M decision** |

$$\boxed{\textbf{ARCHITECTURE COMPARISON STILL NOT PERMITTED. ⛔ } C3 \textbf{ is NOT permission to design the corpus-state mechanism.}}$$

## ⭐⭐ A correction to `P-12`'s own routing

`P-12` §11 assigned `Q3w` to **Lane M / Governance**, on the ground that *"the requirement is derived;
the CLASS is not."*

$$\boxed{\textbf{The class WAS derivable in Lane T. } P\text{-}12\textbf{'s routing of } Q3w \textbf{ was wrong, and } IR\text{-}Q3w \textbf{ is withdrawn as unnecessary.}}$$

⛔ **`P-12` is not rewritten.** ⭐ **And the cross-lane queue shrinks from 9 to 8 by a derivation rather
than by a ruling.**

# 17. Stopping rule — honoured

⛔ **No capability beyond `C2` was considered, and `C2` itself was refused.** ⭐ **`K5` was NOT
recursively decomposed** — §7 and §11 show the bearer is `K3b`, so no `K5` sub-part was proposed.
**Everything encountered was classified as already represented, mechanism, or unresolved.**

# 18. What was NOT decided

⛔ **`Q2` semantic reinterpretation** *(now rank 1)* · **`K2`-A** *(needs `Q2` **and** model
replacement)* · **`Q3`/`Q8`** *(performable; an exercise of `K4c`)* · **third axis** *(witness absent)* ·
**role overload** *(one direction)* · **`Q4`** · **kernel identity** · **equivalence** · **Claim B** ·
**aggregate existence** · **`O-INT002-1`** *(live, `C1`)* · **`O-INT001-1`'s governance rule** ·
**`F-4`/`F-5`** · ⭐ **the corpus-state MECHANISM** · **`W6`'s live empirical failure** ·
**architecture** · **Schema v3**.

---

```
P-13 W6 RESOLVED — CORPUS-STATE IDENTIFICATION IS A MECHANISM, NOT AN INDEPENDENT CAPABILITY —
KERNEL REMAINS 11 CELLS — NO MECHANISM DESIGNED — NO ARCHITECTURE SELECTED — SCHEMA v3 NOT BEGUN.

HOW IT WAS SETTLED — and P-12's own argument did not survive:
  P-12 §6 concluded "a genuine loss distinction K5 cannot express." True of K5, and INSUFFICIENT: the
  capability criterion requires ALL existing closed capabilities to be identical, and P-12 tested only
  K5. Reduced properly, the pair FAILS condition 3 — a later measurement at a CHANGED corpus state is
  a DISTINCT OBSERVATION (P-09 §7), whereas a WRONG one is a SUPERSESSION. So H1 and H2 differ in
  K4a-ii and K3b: the distinction is already borne by the supersession relation plus its warrant.
  MODEL A SUCCEEDS.
  The harder pair — before any adjudication — does satisfy condition 3, and then fails condition 1:
  H1' and H2' differ in a fact about the WORLD, not in anything the estate has RECORDED. Requiring
  persistence to separate them would require it to encode UNKNOWN TRUTH — a category error, not a
  capability. The distinction is UNDETERMINED until adjudicated, and once adjudicated K4a-ii + K3b
  bear it. MODEL B DEFEATED.

PARITY ARGUMENT: REJECTED, and refuted EMPIRICALLY rather than merely disanalogized. K1 is presupposed
  by the FORM of every retention record — universal and unconditional. Corpus-state denotation is
  presupposed by the CONTENT of one class of warrant — particular and conditional. [EMP]: all four of
  this lane's actual withdrawals were warranted with NO corpus-state designator ("wrong scope",
  "glyph-literal patterns over a LaTeX corpus", ...). K1's own independence stands undisturbed.

A / B / C:  A = [DERIVED] a measurement-grounded claim requires an identifiable evaluation context ·
  B = NOT independently required (it is warrant CONTENT) · C = [ARCH]. C2 is NOT forced merely because
  C is technically necessary for implementation — the inference this derivation existed to refuse.

REFINEMENT WITHIN C3 (no fourth category): the bearer is K3b's WARRANT CONTENT, not K5's mechanism —
  K5 concerns the CURRENT ground's checkability, and a HISTORICAL state is not the current ground.
  By the project's own model-integrity rule this is MERELY ANOTHER VALUE, not a new dimension.
  DDD corroborates: no independent lifecycle, no independent variation.

KERNEL: 11 CELLS — no cell added, removed or changed. K3b gains an obligation on its CONTENT (a warrant
  asserting validity-at-a-state must DENOTE that state) — [DERIVED], not a new cell. One OPEN non-cell
  resolves: 2 -> 1 (the third axis alone).

GATES: A CONDITIONAL (improved, 1 OPEN non-cell) · B PASS [stipulated x2] · C BLOCKED · D BLOCKED with
  the CLASS RESOLVED and the MECHANISM ABSENT — a real narrowing, NOT a pass, because W6's empirical
  failure is LIVE: tau-7's 56 renames were uncommitted, so existing measurement claims cannot now be
  warranted this way · E PARTIAL 2 of 8.
  CORRECTION TO P-12's ROUTING: Q3w was assigned to Lane M on the ground that "the class is not
  derived." The class WAS derivable in Lane T. IR-Q3w is WITHDRAWN as unnecessary, and the cross-lane
  queue shrinks from 9 to 8 by a DERIVATION rather than a ruling. P-12 not rewritten.

ARCHITECTURE COMPARISON NOT PERMITTED — C3 is NOT permission to design the corpus-state mechanism.
NEXT ACTION: Q2 — semantic reinterpretation (Lane M identity adjudication), now rank 1.
```

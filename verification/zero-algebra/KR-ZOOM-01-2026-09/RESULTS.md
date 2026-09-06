# `KR-ZOOM-01` — RESULTS

**300 roots × 2 independent splits · 42 071 / 44 511 generated nodes · 18 042 ledger rows**
**Seeds** `20260904` / `88020260904` · deterministic replay **byte-identical**
**Audit: 14/14 gates PASS — and 7 DEGENERATE METRICS found. Read `AUDIT.md` before citing anything below.**

---

## 1. Headline

> ### `[EXP]` An observed value **did** become a non-trivial knowledge state, and **traversal order changed the resulting state in every single admissible case** — while leaving the observable unchanged in ~30 % of them.

The second clause is the finding. The first is weaker than it looks (see `AUDIT-1`).

---

## 2. Hypothesis status

| | hypothesis | train | test | status |
|---|---|---|---|---|
| **H1** | Zoom exposes a non-trivial state | 844/1200 = **0.703** | 850/1200 = **0.708** | **SUPPORTED IN TESTED REGIME** ⚠️ *criterion non-discriminating — `AUDIT-1`* |
| **H2** | resolution-relative atomicity | 332/844 = **0.393** | 347/850 = **0.408** | **SUPPORTED IN TESTED REGIME** ⚠️ *representable, not discovered — `AUDIT-7`* |
| **H3** | Zero → non-Zero across resolution | — | — | **INCONCLUSIVE** — *the design cannot test it, `AUDIT-5`* |
| **H4** | Zoom changes determination | 606/844 = **0.718** | 589/850 = **0.693** | **SUPPORTED IN TESTED REGIME** |
| **H5** | recursive re-basing | 451/844 = **0.534** | 475/850 = **0.559** | **SUPPORTED IN TESTED REGIME** |
| **H6** | Q-reconstruction | 238/844 = **0.282** | 261/850 = **0.307** | **NOT SUPPORTED** ⚠️ *not independent of H4 — `AUDIT-4`* |

**H4 detail** (train / test):

| class | train | test |
|---|---|---|
| different observable | 550 | 531 |
| same observable | 238 | 261 |
| **determination lost** | **56** | **58** |
| determination gained | 0 | 0 |

`[EXP]` **Zoom lost determination in ~7 % of cases and gained it in none.** Refining a value can
make the contract observable *less* determinate — the finer state answered $Q$ **worse**.

---

## 3. The strongest result — traversal order (§12, four separate equalities)

**247 (train) / 271 (test) direction pairs where both orders were admissible.**

| equality | train | test |
|---|---|---|
| **final state equal** | **0/247 = 0.000** | **0/271 = 0.000** |
| observable equal | 76/247 = **0.308** | 78/271 = **0.288** |
| intermediate state equal | 0/247 | 0/271 | ⚠️ degenerate, `AUDIT-3` |
| relation set equal | 17/247 = **0.069** | 20/271 = **0.074** |

### The joint table is the result, not the marginals

| (final, observable, intermediate, relations) | train | test |
|---|---|---|
| different state · **same observable** · — · different relations | **68** | **70** |
| different state · **same observable** · — · **same relations** | **8** | **8** |
| different state · different observable · — · different relations | 162 | 181 |
| different state · different observable · — · same relations | 9 | 12 |

> ### `[EXP]` **Traversal order NEVER produced the same final state (0 of 518 across both splits), yet produced the same observable in ~30 % of cases.**
>
> **"Different state + same observable" is a real, replicating cell** — 76/247 and 78/271.

`[REC]` This is the `KR-ZERO-ALGEBRA` non-commutativity finding appearing in a second, independent
setting: **state-level order dependence does not imply observable-level order dependence.** A
system that tests commutativity on the observable alone **will conclude the operators commute
when they do not**. *(Second independent occurrence — `ES-006.1` requires exactly this before
promotion is even discussable. It is not proposed here.)*

Per-pair `observable_equal` ranged 0.244–0.441 (train); no pair reached equality on final state.

---

## 4. The counterfactual — and why only half of it counts

| | train | test |
|---|---|---|
| `NON_ANCHOR` — observable differs | 188/4368 = **0.043** | 152/4428 = **0.034** |
| `NON_ANCHOR` — **exposed structure differs** | **0/4368** | **0/4428** | ⚠️ **`AUDIT-2` — DEFINITIONAL** |
| `ANCHOR` — observable differs | 1052/1200 = 0.877 | 1032/1200 = 0.860 |
| `ANCHOR` — exposed structure differs | 511/1200 = 0.426 | 494/1200 = 0.412 |
| **Zero at $r$ AND structure differs** | **0/4180** | **0/4276** |

> ### `[EXP]` **All structural divergence in the counterfactual is confined to `ANCHOR` cases — i.e. to the bookkeeping trap that decision D2 was written to exclude.**

**Without D2 this experiment would have reported ~11 % "structural divergence" as a finding.**
It is an artifact of deleting the very dimension the zoom is anchored to.

⚠️ **But the structural half is `[DEFECT]`, not a negative result.** `AUDIT-2` shows the zoom
operator *cannot* expose a difference when a non-anchor dimension is removed. `0/4368` is
**forced**. The **observable** half remains informative: eliminating a non-anchor dimension
changed the observable in ~4 % of cases.

---

## 5. Zero and exclusion

| | train | test |
|---|---|---|
| Zero rate at $r=0$ | 1082/1392 = 0.777 | 1111/1407 = 0.790 |
| Zero rate at $r=1$ | 4806/5672 = 0.847 | 4977/5821 = 0.855 |
| excluded dimensions tested **Zero** | 780/780 | 800/800 |
| excluded dimensions tested **non-Zero** | **0** | **0** |

`[EXP]` **Projection ≠ Zero was never violated** — but see `AUDIT-6`: the design *cannot*
produce an excluded-but-non-Zero dimension, so this is the safe direction of a degenerate control.

---

## 6. Controls

| | train | test | |
|---|---|---|---|
| **A** terminal (no non-trivial zoom) | 356 | 350 | **PASS** |
| **B** structured (zoom succeeds) | 844 | 850 | **PASS** |
| **C** outward succeeds while inward terminates | **67** | **53** | **PASS** |
| **D** retro/prospective expose structure | 214 / 210 | 206 / 207 | **PASS** |
| **E** visible but non-operative | 780 all Zero | 800 all Zero | ⚠️ **degenerate** |
| **F** excluded becomes relevant after Zoom | — | — | **not exhibited** |

---

## 7. The contrast arm — kept separate on purpose

Varying $Q$/$S$ across resolution changed the observable in **606/844 = 0.718** of cases.

> ⚠️ `[REC]` **This is exactly why D1 exists.** Had the primary arm allowed $Q$ to vary, ~72 % of
> observable changes would have been attributable to the question moving rather than the
> resolution. **The contrast arm is never pooled with the primary arm.**

---

## 8. Zoom vs decomposition (§10)

Each direction exposes exactly one relation kind by construction (`INWARD`→STRUCTURAL,
`OUTWARD`→CONTEXTUAL, `RETROSPECTIVE`→HISTORICAL, `PROSPECTIVE`→CONSEQUENTIAL).

> `[NEG]` **This experiment CANNOT establish that Zoom differs from decomposition.** The relation
> kinds are generator-assigned, so finding non-structural relations confirms the mechanism works,
> not that the distinction is real. **Per spec §10 the claim is therefore not made.**
>
> The *non*-definitional evidence is §3: the four directions behave differently under
> composition — 0 % final-state agreement with ~30 % observable agreement — which a pure
> structural decomposition operator would not be expected to produce. **Suggestive, not decisive.**

---

# 9. ⚠️ AMENDMENT 2026-09-04 — **the operator was misidentified**

**Raised by the research owner. Accepted. No number above is altered; the SCOPE of every
number is.**

## 9.1 What `zoom()` actually did

```python
zoom(K, anchor, tau) -> the anchor's host node's tau-children,  AS THE NEW STATE
```

$K_{r+1}$ **replaced** $K_r$. Context was discarded. **The anchor became the information
boundary.**

$$\text{implemented: } \quad Zoom(D, d_i) = \text{keep } d_i\text{'s substructure, drop the rest}$$

$$\text{intended: } \quad Zoom(K_t, Q, A) \to K_t^{Q} \quad\text{— a VIEW over } K_t,\ \textbf{context preserved}$$

> ### `[NEG]` **The implemented operator is RESTRICTION-ZOOM (descent). It is not INQUIRY-ZOOM.**
> The governing principle it violates:
> $$\boxed{\text{Inquiry focus} \;\neq\; \text{Knowledge boundary}}$$
> The anchor determines **what is being investigated**, not **what may be seen.**

This is also the root cause of three audit findings that were reported as separate defects:

| audit finding | now explained by |
|---|---|
| `AUDIT-2` counterfactual structurally forced | the anchor **is** the boundary, so non-anchor dimensions cannot matter |
| `AUDIT-5` H3 untestable — "no dimension survives a zoom" | dimensions are discarded, so no $d_i$ persists to change Zero status |
| §8 "cannot establish Zoom ≠ decomposition" | **the operator WAS decomposition** |

## 9.2 What survives, and with what scope

| result | survives? | corrected scope |
|---|---|---|
| **Order dependence — 0/518 final-state equality, ~30 % observable equality** | **YES** | a property of **composing typed descent steps**. Real, replicated, and it does not depend on context being discarded. **Does not automatically transfer to inquiry-zoom** — that is `Z5` of KR-ZOOM-02. |
| **Determination lost ~7 %, gained 0 %** | **YES — but it measures something else than claimed** | it measures **what restricting the representation around an anchor costs**, not what inquiry costs |
| H1 / H2 | yes, with existing criterion warnings | statements about descent, not about inquiry |
| §13 counterfactual (structural) | already AUDIT INVALIDATED | unchanged |
| H3 | already INCONCLUSIVE | unchanged — and now **repairable**, see 9.4 |

## 9.3 The 7 % becomes evidence **for** the owner's principle, not against it

Under restriction-zoom, determination was **lost in ~7 % of cases and gained in 0 %**.

> ### `[EXP]` **Making the inquiry focus the knowledge boundary destroyed answerability in ~7 % of cases and never improved it, in the tested regime.**

That is not a result about inquiry. It is a **cost measurement for the design error itself** —
and it is the first quantitative support in this corpus for
*Inquiry focus $\neq$ Knowledge boundary.*

`[REC]` The 7 % must **not** be cited as evidence about epistemic zoom. It is evidence about
**restriction**.

## 9.4 What the correction unlocks

Under inquiry-zoom ($K_t$ preserved, working set expandable):

- **H3 becomes testable** — dimensions persist across the investigation, so
  $\mathrm{Zero}_{Q}(d)$ before evidence vs after evidence is a well-posed comparison with $Q$
  held fixed. *(The Nexus case is exactly this: the CI/CD configuration is Zero for the egress
  question until evidence makes it the cause.)*
- **The counterfactual becomes testable** — non-anchor dimensions remain in the working set and
  can influence what is discovered.
- **Zoom-vs-decomposition becomes decidable** — inquiry-zoom can expand *outward across
  dimensions*; decomposition cannot.

**`KR-ZOOM-02` is respecified accordingly** (`../KR-ZOOM-02-DESIGN-2026-09.md`). The set-anchored
operator proposed in `CONCLUSIONS.md` §10.1 was **necessary but not sufficient** — it widened the
anchor while still making it the boundary.

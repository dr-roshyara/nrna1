# `KR-ZOOM-03` — **DESIGN**
## The cross-dimension counterfactual: does inquiry evidence change `Zero` status?

**Experiment ID:** `KR-ZOOM-03-INQUIRY-ZERO-COUNTERFACTUAL-2026-09`
**Status:** `[DESIGN]` — **NOT RUN, NOT AUTHORIZED.**
**Date:** 2026-09-05 · Theory **v1.2 FROZEN** · **kernel NOT SELECTED** · no carrier declared
**Precondition met:** the conceptual correction is landed (theory doc 14) **before** this design,
per the research owner's sequencing.

---

## 1. The one question

> **Can inquiry-focused investigation discover a causally/evidentially relevant dimension that was
> not part of the initial focus, and can that new evidence change the `Zero` status of the
> inquiry?**

The Nexus instance:

```
Nexus state ──Observe──▶ egress = 70 GB/day ──Focus──▶ Q = Cause(70 GB/day)
                                                          │
                          ┌───────────────────────────────┴─────────────┐
                          ▼                                             ▼
              Zero_Q(ci_cd.config) = TRUE                   ...investigate all directions...
              "irrelevant to egress"                                    │
                          ▲                                             ▼
                          └──────────── evidence arrives ◀── ci_cd.config IS the cause
                                                          │
                                        Zero_Q(ci_cd.config) = FALSE
```

**`H3` at last.** `KR-ZOOM-01` could not pose it (dimensions did not survive a zoom).
`KR-ZOOM-02` posed it and **did not test it**.

---

## 2. ⚠️ The defect this design exists to repair

`KR-ZOOM-02`'s `zero_for_inquiry()` was a **reachability lookup**:

```python
def zero_for_inquiry(c, dim, reachable_working):
    if dim not in reachable_working: return True          # <-- not an intervention
    return not any(l.dst_dim == dim or l.src_dim == dim for l in c.chain)
```

It returned **0.87525 — identical to the generator's cross-dimension rate.** It measured the
generator, not the epistemics.

$$\boxed{0.87525 \;\neq\; \text{evidence that inquiry changes Zero status}}$$

> ### `[REC]` **`Zero` is an INTERVENTION predicate. Any implementation that answers it by looking up structure is not testing `Zero`.** This is the same standard `KR-ZERO` set and `KR-ZOOM-02` failed to meet.

---

## 3. The corrected predicate — `Zero` relative to an **inquiry determination**

`[DEF]` For inquiry $Q$, evidence state $E$, and dimension $d$:

$$\mathrm{Zero}_{Q}(d \mid E) \iff \mathrm{Determine}\!\left(Q \mid K_t, E\right) \;=\; \mathrm{Determine}\!\left(Q \mid E^-_d(K_t), E\right)$$

**Read it carefully.** The intervention removes $d$ **from the knowledge state** and **re-runs the
investigation** under the *same* inquiry, the *same* evidence-acquisition rule, the *same* budget
and the *same* exploration seed. `Zero` iff the **determination is unchanged.**

`[REC]` The comparison is on the **determination** — the contract observable of the inquiry — not
on the working set, not on reachability, not on which links exist.

## 4. The measurement

For each case, two evidence states under a fixed $Q$:

| | $E_0$ — **before** investigation | $E_1$ — **after** investigation |
|---|---|---|
| working set | $\{$anchor dim$\}$ | expanded by discovered evidence |
| the test | $\mathrm{Zero}_Q(d \mid E_0)$ | $\mathrm{Zero}_Q(d \mid E_1)$ |

$$\boxed{\text{ZERO-FLIP}(d) \iff \mathrm{Zero}_Q(d \mid E_0) \;\wedge\; \lnot\,\mathrm{Zero}_Q(d \mid E_1)}$$

**Reported per dimension class:** the true root-cause dimension · dimensions on the causal chain ·
decoy dimensions · dimensions untouched by the investigation.

`[REC]` **The decoy and untouched classes are the controls.** If *every* class flips, the test is
measuring investigation breadth, not relevance.

---

## 5. Hypotheses

| | | |
|---|---|---|
| **H3a** | ZERO-FLIP occurs for the **root-cause** dimension at a rate materially above the **decoy** rate | the real claim |
| **H3b** | ZERO-FLIP occurs for **chain** dimensions above the **untouched** rate | relevance propagates along the chain |
| **H3c** | The flip is **evidence-induced, not focus-induced** — holding $E$ fixed and only widening the working set produces **no** flip | **the attribution control** |

> `[REC]` **H3c is the load-bearing control.** Without it, a flip could be produced by *looking*
> rather than by *learning* — the direct analogue of the query-induced/state-induced attribution
> trap already recorded for `KR-STATE-01`, and of `KR-ZOOM-01`'s decision `D1`.

---

## 6. Controls declared before execution

| | | expected |
|---|---|---|
| **Z-A** | remove a dimension the chain never touches | `Zero` at $E_0$ **and** $E_1$ — no flip |
| **Z-B** | remove the root-cause dimension | **non-`Zero` at $E_1$** — determination lost |
| **Z-C** | remove the anchor dimension | non-`Zero` at both — the inquiry has no origin |
| **Z-D** | decoy dimensions | flip rate must be **materially below** the root-cause rate |
| **Z-E** | **paired seeds** — retain and intervened branches share the exploration RNG | any divergence is the intervention |
| **Z-F** | **null intervention** — remove nothing | flip rate exactly **0** |
| **Z-G** | budget held at a **binding** value (from `KR-ZOOM-02`: 3–5, not 40) | no saturation |

`[REC]` **Z-F and Z-G are the two `KR-ZOOM-02` lessons.** Z-F catches a harness that diverges on
its own; Z-G prevents the 1.000 saturation that made the first run uninformative.

---

## 7. Mandatory DEGENERATE METRICS section

Per the standing rule (`EPISTEMIC-STATUS-VOCABULARY.md` §3a), the results document **must** carry
a section naming every quantity that could not have come out differently, and the mechanism that
forces it. **Candidates known in advance:**

- any quantity equal to a generator parameter (the `0.87525` failure);
- `Zero` at $E_0$ for dimensions outside the anchor — check whether the design forces it;
- the null-intervention flip rate (forced to 0 — that is the point of Z-F, and it is `[DEF]`).

---

## 8. Adjudication

| | |
|---|---|
| **estimand** | root-cause flip rate **minus** decoy flip rate, **within** stratum (chain length, cross/same dimension) |
| **replication** | independent seed; sign **and** magnitude above a floor declared **before** inspection, on **both** splits |
| **attribution** | a flip that survives **H3c** only. Focus-induced flips are reported separately and **never pooled** |
| **strength** | `[EXP]` in the tested regime. **Never** "inquiry changes Zero" without the carrier, the generator and the budget attached |

## 9. What this experiment cannot do

- **Cannot** promote `P1`–`P3` to axioms, or `Zoom` to a kernel primitive.
- **Cannot** establish that `Zero` is inquiry-relative **in general** — one synthetic domain.
- **Cannot** settle the carrier (`OQ-1`) or `DECISION-02`.
- **Cannot** modify Theory v1.2, the kernel, or any prior experiment's results.

## 10. Governance

$$\text{EXPERIMENT} \to \text{AUDIT} \to \text{ADJUDICATION} \to \text{THEORY v1.3}$$

**DESIGN only. Not authorized, not run.** `KR-ZOOM-03 ⊥ KR-ZERO ⊥ KR-REP-REDUCTION`.
**Open before it can run:** the effect floor, and the concrete `Determine(Q | K, E)` contract.
**Neither may be settled after seeing results.**

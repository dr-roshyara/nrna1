# `KR-ZOOM-02` — RESULTS

4 000 cases × 2 independent splits · paired operators · budget sweep 1→40
**Five metrics are DEFINITIONAL and are labelled as such. Read §4 before citing anything.**

---

## 1. The headline

> ### `[EXP]` **Restriction-zoom plateaus at 7.9 % / 7.1 % determination and MORE BUDGET DOES NOT HELP IT.** Inquiry-zoom reaches 100 %. The binding constraint is **the boundary, not the effort.**

### Budget sweep — the repair that made this visible

| probe budget | restriction (train / test) | inquiry (train / test) | paired: inquiry-only |
|---|---|---|---|
| 1 | 0.036 / 0.025 | 0.036 / 0.025 | 0.000 |
| 2 | 0.075 / 0.068 | 0.254 / 0.240 | 0.182 / 0.175 |
| 3 | **0.079 / 0.071** | 0.511 / 0.497 | 0.433 / 0.427 |
| 5 | **0.079 / 0.071** | 0.901 / 0.893 | 0.821 / 0.822 |
| 8 | **0.079 / 0.071** | 0.999 / 0.998 | 0.920 / 0.927 |
| 13–40 | **0.079 / 0.071** | 1.000 / 1.000 | 0.921 / 0.929 |

> `[EXP]` **Restriction-zoom's curve is FLAT from budget 3 onward.** It is not
> under-resourced — it is **bounded**. Multiplying the investigation effort 13× bought it
> **exactly nothing**. `paired_restriction_only = 0.000 at every budget`: there is **no** case
> in 8 000 where restricting the view helped.

This is the quantitative form of the correction: **the anchor decided the answer before the
investigation began.**

---

## 2. Order dependence — measured with **state and observable separated**

`KR-ZOOM-01` found 0/518 final-state agreement with ~30 % observable agreement. Collapsing those
would have hidden the result, so they are separated again here — at **binding** budgets, since at
budget 40 everything saturates.

| budget | observable equal | **internal state equal** | path equal | different state · same observable |
|---|---|---|---|---|
| 2 | 0.959 / 0.961 | **0.911 / 0.923** | 0.886 / 0.902 | 0.080 / 0.070 |
| 3 | 0.922 / 0.925 | **0.912 / 0.902** | 0.831 / 0.820 | 0.070 / 0.081 |
| 5 | 0.937 / 0.939 | **0.949 / 0.946** | 0.771 / 0.766 | 0.030 / 0.038 |

### The comparison across operators is the finding

| operator | internal-state agreement under order swap |
|---|---|
| **descent** (`KR-ZOOM-01`) | **0 / 518 = 0.000** |
| **inquiry** (`KR-ZOOM-02`) | **~0.90 – 0.95** |

> ### `[EXP]` **Inquiry-zoom is dramatically more order-robust than descent-zoom.**
> Descent lands in a different subtree for every order. Inquiry **converges on the same working
> set** regardless of exploration order, because it is not choosing a subtree — it is
> accumulating a view over a state it never discarded.

`[EXP]` The residual **"different state, same observable"** cell survives at **3–8 %**, replicating
across splits — the same phenomenon `KR-ZOOM-01` found, at much lower magnitude. **Order still
matters; it matters far less.**

---

## 3. The other requirements

| requirement | result |
|---|---|
| **6.** discover dimensions **absent from the initial ontology** | inquiry **933/933 and 1028/1028 = 1.000**; restriction **0.000** ⚠️ definitional |
| **7.** return to the complete state | context preserved 4000/4000 ⚠️ definitional · **novel dimension absorbed into $K_{t+1}$ in 23.3 % / 25.7 %** |
| **8.** determination improves / unchanged / decreases | **improves** — 0.079 → 1.000 at equal budget. **No case decreased.** Contrast `KR-ZOOM-01`'s descent operator, which *lost* determination in ~7 % and gained in 0 % |
| **9.** different orders → different state and/or observable | §2 — measured separately |

> `[EXP]` **The two operators move determination in opposite directions.** Descent-zoom lost it
> (~7 %, never gained). Inquiry-zoom gained it (7.9 % → 100 %, never lost). Same domain family,
> same discipline, opposite sign.

---

## 4. ⚠️ The five definitional metrics — labelled, not celebrated

Same standard applied to `KR-ZOOM-01`: a metric that cannot take another value is not evidence.

| metric | value | why it is definitional |
|---|---|---|
| restriction on cross-dimension causes | **0/3501, 0/3479** | a restricted operator cannot cross the boundary that defines it. **Forced.** |
| restriction on novel-dimension causes | **0/933, 0/1028** | same |
| `context_preserved` | **1.000** | the operator never deletes. This *is* the design, not a result. |
| `Z4_zero_before_evidence` | 0.87525 | **identical to the generator's cross-dimension rate** (0.87525). It is that rate, relabelled. |
| `determination_added` at budget 40 | 1.000 | saturated — superseded by the budget sweep |

### `[NEG]` H3 is well-posed now, and still not tested

The correction **does** make H3 well-posed: dimensions persist and $Q$ is fixed, so
"$\mathrm{Zero}_Q(d)$ before evidence vs after evidence" is a legitimate comparison — exactly the
Nexus story where the CI/CD configuration is irrelevant to egress until evidence makes it the
cause.

**But my `zero_for_inquiry()` is a reachability lookup, not an intervention.** It returns the
generator's cross-dimension rate by construction. **H3 remains untested**; it now requires the
real $E^-$ intervention test against the inquiry's determination, which is straightforward and
was not done here.

---

## 5. What survives, stated at the right strength

| | status |
|---|---|
| Restriction plateaus while inquiry rises; **no budget compensates** | **`[EXP]` SUPPORTED IN TESTED REGIME** — the load-bearing result |
| Inquiry-zoom is far more order-robust than descent-zoom (~0.92 vs 0.000 state agreement) | **`[EXP]` SUPPORTED IN TESTED REGIME** |
| "Different state, same observable" persists at 3–8 % | **`[EXP]`** — third independent occurrence in this corpus |
| Investigation absorbs dimensions outside the initial ontology (23–26 %) | **`[EXP]`** |
| Determination moves in **opposite directions** under the two operators | **`[EXP]`** |
| *Inquiry focus $\neq$ Knowledge boundary* | **`[EXP]` supported** — and it is a **design principle vindicated by measurement**, not yet a law |
| H3 (Zero → non-Zero with $Q$ fixed) | **`[OPEN]`** — well-posed for the first time, still untested |

> `[REC]` **No kernel modification. No algebra. No promotion.** The principle is now supported by
> a paired measurement in one synthetic domain — one carrier, one generator, one anomaly type.

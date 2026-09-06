# `KR-ZOOM-OUT-01` — **PRE-REGISTRATION**

**Status:** **RATIFIED AND FROZEN 2026-09-05** by the research owner (all five decisions, with two wording corrections applied below). **READY FOR EXECUTION — NOT RUN.**
**Date:** 2026-09-05 · Theory v1.2 FROZEN · kernel NOT SELECTED
**Design:** `KR-ZOOM-OUT-01-DESIGN-2026-09.md` · **Canonical scenario:** Nexus 70 GB/day → GitLab Runner

> Everything below must be ratified **before execution** and **none of it may be settled
> afterwards.** `KR-ZOOM-03` is the reason this is a separate artifact: I froze the wrong estimand
> there and it cost the result.

---

## 1. ⚠️ The `KR-ZOOM-03` lesson, applied — **every estimand is DIRECTED**

`KR-ZOOM-03` froze an **undirected** flip rate. Decoy removal destroyed determination 0.118 of the
time and *created* it 0.131 — symmetric. **The undirected count cancelled the signal**
(Δ = 0.058, borderline). The directed quantity was +0.123 / +0.134 and would have cleared the
floor, **and could not be adopted.**

$$\boxed{\text{No undirected estimand is proposed here.}}$$

---

## 2. The estimands — proposed, each with its direction

**Amended 2026-09-05** after the design corrections: comparison is by the declared availability
relation $\preceq_{\mathcal O}$, **never by set inclusion on $K_t$** — the carrier (`OQ-1`)
stays open and is not decided here.

| | estimand | direction | separates |
|---|---|---|---|
| **E1** | $P\!\left(K_t \preceq_{\mathcal O} K_{t+1}\right)$ | **answerability retained** | **`M0` information loss** from everything else |
| **E2** | $P\!\left(K_t \cong_{\mathcal O} K_{t+1}\right)$ | **observably unchanged** | `M1` from `M2`/`M3` |
| **E3** | $P\!\left(\text{detail falls} \mid K_t \preceq_{\mathcal O} K_{t+1}\right)$ | **conditional on distinctions surviving** | `M2` from `M3` |
| **E4** | $P\!\left(\Delta^\circ \neq \varnothing \mid \text{`M3`}\right)$ | **existing material qualified** | `M3a` from `M3b` |
| **E5** | $P(\text{gain}) - P(\text{gain} \mid \text{null round-trip})$ | **determination GAINED at the broader level** | **Q8 — the load-bearing question** |

> ⚠️ **`E3` is CONDITIONAL by construction.** An unconditional "detail falls" rate would classify
> information loss as abstraction. **Detail count is a diagnostic inside the
> $\preceq_{\mathcal O}$-preserving branch and nowhere else.**

**`E5` is the primary.** `E1`–`E4` classify the operation; **`E5` asks whether it matters** —
*can a focused investigation change what we can conclude about Nexus as a whole?*

`[REC]` `E5`'s control is the **null round-trip** (`O-A`: zoom in, investigate nothing, zoom out),
whose four equalities are measured separately (design §5.1) — **only the observable and
contract-semantic rows may support an `M0` verdict.**

---

## 3. Meaning assignment — declared before execution

Each case is classified `M0 / M1 / M2 / M3a / M3b / MX` by the design §2.2 witness. **The full
distribution is reported.**

`[REC]` **No single-winner claim** unless one class exceeds **0.60** of all cases **on both
splits**. `MX` — *no category fits, or several do* — **is a first-class outcome and counts toward
that threshold like any other.** A high `MX` share would be a more interesting result than a clean
winner, and the design must not be able to avoid it.

`[REC]` **`M0` is reported first, always.** If zoom-out destroys answerability at a material rate,
that dominates every other classification question.

---

## 4. Effect floor — proposed

| tier | floor | |
|---|---|---|
| **material** | $\lvert\Delta_{E5}\rvert \ge 0.10$ **on both splits** | claimed |
| borderline | $0.05 \le \lvert\Delta_{E5}\rvert < 0.10$ | reported, never claimed |
| negligible | $< 0.05$ | regardless of significance |

Unchanged from `KR-ZOOM-03`, where it held under pressure and was not moved. `E1`–`E4` are
**classification proportions**, not effects, and carry no floor — they are reported with
numerator and denominator.

---

## 5. The `Determine(Q_broad)` contract — **reused, not reinvented**

`[REC]` **Use the frozen `KR-ZOOM-03` Option 3 contract verbatim** — supported, terminal, **a rival
actually assessed**, and every assessed rival beaten by $\tau_m$. Rules live once (`ES-005.4`);
inventing a second determination contract would make the two experiments incomparable.

`Q_broad` = *"what is the operational state of Nexus?"* — a system-level inquiry, distinct from
`Q_focus` = *"what causes the 70 GB/day egress?"*

**Calibration gate: base $\mathrm{Determine}(Q_{\text{broad}})$ must land in 0.30–0.80** before any
analysis. Reported whatever it is.

---

## 6. Control `O-F` — the `H3c` repair, institutionalised

`KR-ZOOM-03`'s attribution control returned **exactly** the base `Determine` rate — degenerate by
construction, and it did nothing.

> ### `[REC]` **Before the run, for EVERY metric, name a concrete case that would give it a different value.** A metric with no such case is **dropped before execution**, not explained afterwards.

`O-F` is a **gate**, not a report. Its output is a table of metric → witness case, produced and
checked **before** any result is computed.

---

## 7. What ratifying this does and does not do

| | |
|---|---|
| **does** | fix the four estimands with their directions, the classification rule, the floor, the `Determine` contract and its gate; unblock execution |
| **does not** | add any definition of Zoom-out to the theory · promote `P1`–`P3` · adopt the `Zoom` research definition · touch Theory v1.2 or the kernel · authorize the paired `KR-ZOOM-INOUT-01` lifecycle experiment |

---

## 8. Decisions required

1. **`E5` as primary**, with the null round-trip as its control — or a different primary?
2. **The 0.60 single-winner threshold**, with **`MX` counting like any other class**.
3. **Floor 0.10 / 0.05** — carry over from `KR-ZOOM-03`, or change?
4. **Reuse the `KR-ZOOM-03` Option 3 `Determine` contract** for `Q_broad` — or specify a separate one?

5. **The declared observable family $\mathcal O$** that defines $\preceq_{\mathrm{obs}}$ — proposed: the frozen `KR-ZOOM-03` contract applied to $Q_{\text{focus}}$ **and** $Q_{\text{broad}}$.

**My recommendation: ratify all five as proposed.** The estimands are directed, the contract is
reused rather than reinvented, and the one genuinely new instrument — `O-F` — exists specifically
to prevent the failure that degraded `KR-ZOOM-03`.


---

# 9. RATIFICATION RECORD — 2026-09-05

**All five §8 decisions ratified**, with two wording corrections applied **before freezing**.

| # | decision | status |
|---|---|---|
| 1 | **`E5` primary**, null round-trip as its control | ✅ ratified |
| 2 | **0.60 single-winner threshold, `MX` first-class** | ✅ ratified |
| 3 | **floor 0.10 material / 0.05 borderline, both splits** | ✅ ratified |
| 4 | **reuse the `KR-ZOOM-03` Option 3 `Determine` contract verbatim** for **both** $Q_{\text{focus}}$ and $Q_{\text{broad}}$ — only the inquiry target differs | ✅ ratified |
| 5 | **declared observable family $\mathcal O$** | ✅ ratified **with correction** |

## 9.1 Correction to `≼` — *"determinate on"* replaced by an availability predicate

*"Determinate on"* could be read as requiring the **same answer**, or as invoking the still-open
determination semantics. Replaced:

$$\boxed{\;K \preceq_{\mathcal O} K' \iff \forall o \in \mathcal O:\; \mathrm{Avail}(o,K) \Rightarrow \mathrm{Avail}(o,K')\;}$$

where $\mathrm{Avail}(o,K)$ = *the observable can still be evaluated under the declared contract*.
$\mathrm{Value}(o,K)$ is measured **separately**, because **preserving answerability does not mean
preserving the same answer.**

### `E2b` — added as a consequence, declared before execution

$$E_{2b} = P\!\left(\exists o \in \mathcal O:\ \mathrm{Value}(o,K_t) \neq \mathrm{Value}(o,K_{t+1}) \;\middle|\; K_t \preceq_{\mathcal O} K_{t+1}\right)$$

**The answer-change rate among questions that remained askable.** This is `M3b`'s *observable*
signature, complementing $\Delta^\circ \neq \varnothing$, its *structural* one.
**Without separating `Avail` from `Value`, `M3b` would be unobservable.**

## 9.2 The four equality dimensions, named

**1 Structural · 2 Observable · 3 Contract-semantic · 4 Historical/provenance** — reported
separately, never collapsed. **Only 2 and 3 may support an `M0` verdict.**

## 9.3 Standing constraint

$$\boxed{\text{Zoom-out phenomenon} \;\neq\; \text{Zoom-out definition}}$$

The experiment may discover a behaviour **without canonizing it.** No result of this run adds a
definition of Zoom-out to Theory v1.2, promotes `P1`–`P3`, or touches the kernel.

**Status: pre-registered and ready for execution. NOT RUN.**

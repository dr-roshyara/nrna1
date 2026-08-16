# Preparatory analysis for the `EM-OPEN-074` + `EM-OPEN-076` act — where each rule becomes an individual veto

**Type:** Governance analysis (Session 2) · **Date:** 2026-08-16 · **Arithmetic, not policy. No rule chosen.**
**⛔ Nothing adopted. Architecture ⏸️ · Session 3 🛑.**

**Why this exists:** `EM-GOV-034` excludes unanimity **by name**. A permitted rule must not recreate it **by arithmetic**. Whether it does is **computable**, not a matter of preference — so Governance computed it.

---

## The table

**Votes required, and the population size at which a single participant becomes able to block:**

| Rule | N=2 | N=3 | N=4 | N=5 | N=20 | **Individual veto when** |
|---|---|---|---|---|---|---|
| at least half, **rounded up** | 1 | 2 | 2 | 3 | 10 | **never** |
| **more than half** | **2** | 2 | 3 | 3 | 11 | **N ≤ 2** |
| at least two-thirds, **rounded up** | **2** | 2 | 3 | 4 | 14 | **N ≤ 2** |
| at least two-thirds, **rounded down** | 1 | 2 | 2 | 3 | 13 | **never** |
| at least three-quarters, **rounded up** | **2** | **3** | 3 | 4 | 15 | **N ≤ 3** |

## Three results that bear on the decision

**① A simple majority is NO SAFER than a supermajority at N=2.** *"More than half"* and *"at least two-thirds, rounded up"* **both require 2 of 2**. **Choosing a lower threshold does not avoid the problem** — which is the opposite of what one would expect, and it means the menu cannot be made safe merely by omitting the demanding rules.

**② The ROUNDING DIRECTION, not the fraction, decides safety at small N.** Two-thirds **rounded up** is a veto at N≤2; two-thirds **rounded down** never is. ⚠️ **But rounding down distorts the rule elsewhere: at N=4 it requires 2 — a bare half, not two-thirds.** **A rule that is safe everywhere is not a two-thirds rule everywhere.** *(At N=20 it requires 13, i.e. 65% — close; at N=4 it is 50%.)*

**③ ⚠️ CORRECTION to Governance's own note of 2026-08-16.** I wrote that restricting the menu *"would make `EM-OPEN-075` moot."* **That is too strong.** **Restricting the menu can only remove the problem if the permitted rules are ones that round DOWN or use *at least half* — and those change what the threshold means at EVERY population, not only small ones.** **So `074` and `076` are not merely conveniently combined: the menu cannot be made safe without deciding the rounding, and the rounding cannot be decided without accepting a distortion at some population. That is the actual trade-off.**

## What this does NOT decide

**Which rules are permitted · which rounding each carries · whether the distortion at small N is acceptable · whether the representative side takes a different rule set from the Committee side** *(the Committee has a guaranteed minimum of three, so `N ≤ 2` cannot arise there — **the two populations do not face the same problem, and may not need the same menu**)*.

> ⚠️ **That last point may be the most useful: with `EM-GOV-033`'s minimum of three, "at least two-thirds, rounded up" is SAFE for the Committee at every possible size. The N≤2 problem is a REPRESENTATIVE-SIDE problem only.**

**Traceability.** `EM-GOV-022` · `EM-GOV-033` · `EM-GOV-034` · `EM-GOV-035` · `EM-OPEN-074`/`075`/`076`.

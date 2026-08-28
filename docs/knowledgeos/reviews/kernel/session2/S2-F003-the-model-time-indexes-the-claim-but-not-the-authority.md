# S2-F003 · The model time-indexes the *claim* but not the *authority* that admitted it

**Class:** NEW FINDING
**Status:** OPEN · review record only · **not adjudicated, not architecture**

---

| | |
|---|---|
| **Source Session-1 artifact** | `session1/S1-F001-authority-temporal-semantics-gap.md` |
| **What it corrects** | S1-F001's classification of the v1.1 comparison as **NOT ADDRESSED** |
| **Lens** | Temporal · Authority · Identity |

---

## FINDING

S1-F001 compares its finding to v1.1 and concludes: *"**Classification: NOT ADDRESSED** — v1.1 does not speak to authority *validity intervals*."*

**That is right about authority and misses the asymmetry that makes it interesting.** v1.1 *does* carry temporal validity — for the **claim**:

> `TemporalValidity` — *valid-from · valid-until · superseded-by* — with *"freshness never truth, expiry never absence"* (Article 11).

So the model provides a validity interval for **what is asserted**, and none for **the authority under which it was asserted**.

> **A claim can be asked "were you valid at T?" The authority that admitted it cannot.**

## EVIDENCE

- `TemporalValidity` is an aggregate member with an explicit interval (`valid-from`, `valid-until`).
- `Authority` is a **reference** — *"a recorded reference to a human act; held by the Authority context, referenced by the aggregate"* — carrying no interval.
- S1-F001's measurement (`0/20` validity information on grants) is the **baseline system exhibiting the same asymmetry** the target model has.

## WHY IT MATTERS

The gap is therefore **not a baseline defect that the target model fixes**. It is present in both, which changes its status: from *"the current system is behind"* to *"neither the current system nor the target model time-indexes authority."* S1-F001's `NOT ADDRESSED` reads as *the question is out of scope*; the asymmetry shows it is *in scope and unanswered*.

The asymmetry also gives the finding a **shape**: temporal validity exists as a modelled concept in the architecture. The question is not *whether* the model can express intervals — it demonstrably can — but *why the concept is applied to one side of an admission and not the other*.

## POSSIBLE IMPACT

⚠ **Research relevance only. No element is proposed.** Recorded as bearing on the same family S1-F001 names (`W:C-18`, `W:C-3`) — and, if anything, sharpening why they are hard: the asymmetry is a property of the model, not an omission in a baseline.

**This finding must not be routed into the active adjudication.** The corpus is sealed for that purpose; this is a review record.

## PROVENANCE

Derived from `session1/S1-F001` plus v1.1 law already in this reviewer's authorized set (Article 11, `TemporalValidity`, `Authority` member). **No corpus document was read.**

## STATUS

**OPEN — reclassification proposed from `NOT ADDRESSED` to `ADDRESSED FOR THE CLAIM, NOT FOR THE AUTHORITY`.** Session-1's document is not modified.

# EKS-56 — Two workstreams gave one issue number to two different issues, and one of them was already closed

**Raised:** 2026-09-10 · **Source:** `G-37` disposition
**Evidence:** `docs/knowledgeos/brainstorming/verification/gap-discovery/theory-v1-0-def-register/00-FINDINGS.md`
**Status:** OPEN · **Severity:** HIGH · **Class:** knowledge management — **not** a theory defect

---

## The problem, in business terms

The programme numbers its open issues `G-1`, `G-2`, … Two workstreams independently reached the
number **`G-67`** and gave it to **two entirely different issues**:

| workstream | what `G-67` means there | where it stands |
|---|---|---|
| kernel research | a suspected contradiction between two equality symbols | **closed on 2026-08-31**, and closed as a *mistake* — the record says the severity was *"mis-scored critical on a false claim"* |
| readiness / planning | *"the mandatory invariant register was never written down"* | **open**, and the current forward plan calls it **"the single most evidenced blocker in the estate"** |

Neither workstream knows the other uses the number.

## Why it matters commercially

Two distinct failures follow, and both are live:

**1. A closed issue can be reopened by accident.** Anyone searching the estate for `G-67` finds a
withdrawal notice and a critical open blocker under one label. The next person to reconcile them
can as easily resurrect the closed one as retire the open one.

**2. The open one was planned as the wrong kind of work.** The forward plan schedules it as a
**derivation** — work an analyst performs alone. The evidence says it is a **decision** — the
register was in fact written down **three times, by three teams, in three incompatible shapes**
(a ten-field empty template; seven named invariants with experiments; nine named invariants), and
**no rule says which one governs**. Scheduling a decision as a derivation means the analyst
produces a fourth version instead of the organisation choosing among the three it has.

The claim that it was "never written down" was made on **2026-09-06 and 2026-09-07**, four and
five days **after** the third version was written.

## What is being asked for

1. **A single issue-number registry across workstreams.** One number, one issue, estate-wide.
   `G-67` to be split and both halves renumbered.
2. **A rule that an issue's premise is re-read before the issue is scheduled** — this one was
   carried into a plan without its premise being checked against the corpus.
3. **A check on computed evidence:** the "blocked in 66 of 66 worlds" figure that made this the
   most-evidenced blocker comes from a program in which *"NOT ENUMERATED"* is **typed in as an
   input**. The arithmetic is correct; it measures something else. Any figure quoted as evidence
   should name which of its inputs are assumptions.

## Related

`EKS-49` (workstreams do not tell each other what they commissioned) · `EKS-51` (one symbol reused
for a different concept) · `EKS-52` (workstreams do not enumerate each other). **This is the same
family, at the level of issue identifiers rather than mathematical symbols** — and it is the first
in the family where a **closed** item and an **open** item share the label.

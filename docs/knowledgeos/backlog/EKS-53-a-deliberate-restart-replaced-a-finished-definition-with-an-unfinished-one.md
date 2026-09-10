# EKS-53 — A deliberate restart replaced a finished definition with an unfinished one, and nobody noticed the trade

**Raised:** 2026-09-10 · **Source:** `EvalReq`/`Sat` birth-point reconstruction
**Evidence:** `docs/knowledgeos/brainstorming/verification/gap-discovery/concept-family-birth-census/02-EVALREQ-SAT-BIRTH-TO-PRESENT.md`
**Status:** OPEN · **Severity:** HIGH · **Class:** research-process economics — **not** a theory defect

---

## The problem, in business terms

On the night of 6 September the programme **restarted its theory from scratch** — twenty-three
documents in eight hours, a complete twenty-one-part rewrite. The opening document says why, in its
own words:

> *"I will not treat an attractive formulation as a theorem merely because it appeared in an earlier
> document."*

**That is a defensible policy.** A theory that inherits every earlier formulation inherits every
earlier error, and this corpus has demonstrably produced errors worth not inheriting.

**But the policy was applied without measuring what it discarded.** The rewrite cites the earlier
work **zero times** — and in at least three places it replaced a **finished** definition with an
**unfinished** one:

| what existed before | what the rewrite produced | gap between them |
|---|---|---|
| a requirement-evaluation function **with a stated output type** (27 August) | the same function **with no output type** | 10 days |
| a satisfaction rule **with a complete decision procedure** — three outcomes, each with a stated condition (2 September) | a satisfaction rule that **defers its procedure** to a component described as *"contract-specific"* and never supplied | 4 days |
| a conclusion the earlier work had reached and boxed — *"satisfaction is contract-specific"* (27 August) | **the same conclusion, re-derived from scratch** | 10 days |

**None of these were corrections.** The earlier definitions were not shown to be wrong. They were
simply not consulted.

## Why this is expensive, and why it is not the same as the tickets already filed

`EKS-49` and `EKS-52` record work being duplicated because **separate teams cannot see each other's
output**. This is different and, for a research programme, worse:

**This is one workstream not reading its own output from four days earlier — as a matter of stated
policy.** The information was not hidden, not filed elsewhere, and not hard to find. It was
deliberately set aside, and the cost of setting it aside was never counted.

The concrete cost:

1. **A finished piece of work is now unreachable.** The most completely specified version of the
   satisfaction rule anywhere in the corpus — the only one that says *how* to compute the answer,
   not merely what type the answer has — appears in **exactly one document and is referenced by
   nothing**. It is not wrong; it is orphaned.
2. **The programme is now carrying four incompatible answers to one question** — the satisfaction
   result has been given nine values, then eleven, then three, then four, in four documents, and
   **no document relates any pair**. Choosing between them later will require redoing the
   comparison that was skipped.
3. **The restart re-derived a conclusion it already owned.** Ten days of work were repeated to
   reach a sentence that had already been written and boxed.
4. **Everything downstream now has unstated ancestry.** Every object in the current evaluation
   chain is born inside the rewrite. Nobody can say which of them are new results and which are
   re-derivations of settled ones, because the rewrite does not say.

## What is NOT being claimed

* **Not** that the restart was wrong. Refusing to inherit unexamined formulations is sound practice,
  and this corpus gives reasons for it.
* **Not** that the new definitions are inferior *as mathematics*. Two of them are cleaner than what
  they replaced.
* **Not** that anyone was careless. The policy was stated openly in the first document.
* **Not** a theory defect. Every definition involved is internally coherent.

**The defect is that a trade was made without being priced.**

## What would close this

| | requirement |
|---|---|
| **1** | **A restart states what it is NOT inheriting, and why.** *"I will not inherit unexamined formulations"* is a policy; *"I am not inheriting these four specific results, for these reasons"* is a decision. Only the second can be reviewed |
| **2** | **Before replacing a definition, check whether the existing one is more complete.** In all three cases here the earlier version specified more, not less. A two-minute check would have caught it |
| **3** | **An orphan check.** Any definition that is complete and referenced by nothing should be flagged — either it is superseded (say so) or it was lost (recover it). At present nothing detects this |
| **4** | **When one question acquires a second answer, record the relationship or record that none exists.** Four answers with no stated relationships is not four options; it is an unpaid debt |

## Related

| | |
|---|---|
| `EKS-49` · `EKS-52` | duplication **between** workstreams. This is duplication **within** one, by policy |
| `EKS-51` | one symbol re-used for a different concept. Here, one *question* re-answered without reference to the existing answer |

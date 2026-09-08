# EKS-20 — A research result that does not record which sources it used cannot later be shown to be independent, original, or free of duplication

**Status:** **BACKLOG · OPERATIONAL EXPOSURE (UNAUDITABLE PROVENANCE)** — registered from `P-57` (`docs/knowledgeos/theory-extraction/75-P57-…`). ⛔ **Not commissioned; activation requires a Human/PO-ARB authorization act.**
**Class:** operating-model problem — provenance recording in research work.
**Registered by:** Lane T (theory extraction), 2026-09-09.

> ### ⛔ **This item records a problem and a candidate requirement. It commissions nothing, proposes no format, and questions no finding.**

---

## 1 · The problem in plain terms

A question was asked that ought to have a factual answer: **did this research result draw on the same
material an earlier authorized review had already examined?** It matters because the answer decides
whether the two are independent corroboration, a duplication, or one quietly built on the other.

⭐ **One side of the comparison could be reconstructed completely.** The earlier review wrote down what
it examined — how many documents, from which folders, over which dates, and, unusually honestly, **how
deeply each part was actually read**.

⭐⭐⭐ **The other side could not be reconstructed at all.** The research result names **no source
material whatsoever** — only three of its own earlier documents and one observation about a code
folder. Its predecessors name none either.

$$\boxed{\textbf{The comparison did not fail because the answer was hard. } \mathbf{It\ failed\ because\ half\ the\ record\ was\ never\ written\ down.}}$$

## 2 · Why this is a business problem

**a. Independence cannot be claimed, and cannot be disclaimed.** ⭐⭐ Without a source list, *"this was
derived independently"* is not a modest claim or an immodest one — **it is not a checkable claim**.
Neither is the opposite. The record supports nothing.

**b. Duplication cannot be detected.** ⚠️ If the same conclusion already exists somewhere in the
estate, there is no way to find out whether this work reached it afresh or read it and forgot. Both
have happened in this programme.

**c. It blocks exactly the reviews the estate wants to run.** ⭐ Every serious question now being asked
— is this original, does it conflict with existing theory, can it be promoted — needs the source list
as an input. **Each of those reviews will hit the same wall.**

**d. The cost grows and cannot be paid down later.** ⭐⭐ Sources are cheap to note **while the work is
being done** and effectively unrecoverable afterwards: nobody can reliably reconstruct months later
what they read. **Every additional document written without a source list permanently enlarges the
unauditable region.**

**e. It weakens work that is otherwise strong.** The result in question is heavily reviewed and
carefully qualified. ⚠️ **None of that rigour is visible to a reader asking where it came from**, and
a reader who cannot see the sources is entitled to discount the conclusion.

## 3 · ⭐⭐ The estate already solved this, and the solution is not being used

The same repository contains a **provenance register**: for each of its determinations it records the
evidence chain, with each link graded by strength — and one link explicitly marked *weak, flagged*.
It is a good, cheap, working practice.

$$\boxed{\textbf{This is not a missing capability. } \mathbf{It\ is\ an\ existing\ practice\ that\ one\ work\ stream\ does\ not\ follow.}}$$

⭐ That also makes the remedy space small: **adopt what already exists, rather than design something.**

## 4 · What is *not* the problem

⛔ **No finding is being questioned.** Nothing here says the result is wrong, unoriginal, or derivative
— **saying that would require exactly the record that is missing.**

⛔ **This is not a request for exhaustive citation.** The useful record is short: what was read, at what
depth, and what was searched for and not found. **The earlier review's own one-paragraph coverage
statement is the model, and it fits in a paragraph.**

⛔ **Not a documentation-formatting concern.** The issue is that a specific, decision-relevant fact is
absent, not that it is presented inconsistently.

⛔ **No format, template, tool or checker is proposed here.**

## 5 · Candidate requirement (a direction, not a design)

> ⭐ **A research derivation should record what it actually consulted — including what it searched for
> and did not find, and how deeply each part was read — so that a later reader can test its
> independence rather than take it on trust.**

⭐⭐ **The honesty of the depth statement matters as much as the list.** The earlier review's value came
from saying *"this part was only 3–5% read"*; a source list that implies uniform thorough reading would
be worse than none.

## 6 · Relationship to existing items (`ES-005.4` — never a copy)

| item | overlap | why this is separate |
|---|---|---|
| `EKS-16` — derivation without consulting existing theory | both concern sources | ⛔ `EKS-16` is about **what was not read**. This is about **not recording what was read**. A work stream could fix `EKS-16` completely — search perfectly every time — and still be entirely unauditable |
| `EKS-17` — duplicate research roots | both affect discoverability | ⛔ that is about **finding** material; this is about **recording** it afterwards |
| `EKS-18` — open questions with no route to the work | both are about two things not meeting | ⛔ that is **routing questions to answers**; this is **the answer not saying where it came from** |
| `EKS-13` — cross-lane dependency without change notification | both concern dependencies | ⛔ `EKS-13` presumes a **known, recorded** dependency that may go stale. Here the dependencies were **never recorded**, so there is nothing to notify about |

⭐ **Checked and distinct on all four counts.**

## 7 · Urgency

⭐⭐ **Higher than the neighbouring items, and rising fastest.** The other three describe failures that
can be repaired later; this one describes information that is **destroyed by the passage of time**.
Roughly thirty research documents have now been produced in this pattern, and each week of further
work enlarges the region no future review can examine.

## 8 · Evidence

* `docs/knowledgeos/theory-extraction/75-P57-…` §5, §6 — the exhaustive reference extraction and the
  failed set intersection.
* `docs/knowledgeos/theory-extraction/18-P08-…` — references: three lane predecessors and one git
  observation; no corpus artifact.
* `docs/knowledgeos/reviews/synthesis/analysis/phase-archaeology-inventory.md` §1, §4 — the model to
  follow: census, date range, per-subcorpus read depth, and a stated coverage residue.
* `docs/knowledgeos/reviews/synthesis/final-architecture/FA-7-provenance-register.md` — the existing
  provenance practice, with graded evidence classes.

---

⛔ **Registered under the operating model's §37 — *"when a deeper requirement is discovered: record it as a follow-up and STOP."* No remedy is designed and no work is commissioned.**

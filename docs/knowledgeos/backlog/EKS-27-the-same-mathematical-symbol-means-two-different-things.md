# EKS-27 — The same mathematical symbol denotes two different things in two work streams, and a formula cannot be disambiguated

**Status:** **BACKLOG · NOTATION-COLLISION EXPOSURE** — registered from `P-77` (`docs/knowledgeos/theory-extraction/95-P77-…`). ⛔ **Not commissioned; activation requires a Human/PO-ARB authorization act.**
**Class:** operating-model problem — shared notation across work streams.
**Registered by:** Lane T (theory extraction), 2026-09-09.

> ### ⛔ **This item records a problem and a candidate requirement. It commissions nothing, proposes no renaming, and questions no result.**

---

## 1 · The problem in plain terms

Two work streams both describe how things change over time, and both label their changes with **the same
Greek letter, using the same numbering** — first change, second change, and so on.

⭐⭐⭐ **They are not the same kind of thing.**

| | one stream | the other |
|---|---|---|
| **what a numbered change is** | a **function** that takes a system state and produces the next one | a **kind of event that was observed happening** to records |
| **is it a function?** | yes, with stated preconditions and postconditions | **no** — it is a category of observed occurrence |
| **what it applies to** | a claim moving between named states | files, entries and associations in a working archive |

⭐ A reader who takes *the third change* from one stream and treats it as *the third change* from the
other has produced nonsense — **and has made no visible mistake.** Both are written identically.

## 2 · Why this is worse than the two naming problems already on this list

⭐⭐ Two overloaded **words** are already recorded here. This is different in a way that matters
practically:

**a. A word can be disambiguated. A symbol in a formula cannot.** ⭐⭐⭐ For an overloaded word you can
write a register saying *"this term has five senses; here is which."* **For a symbol inside a formula,
the formula is the definition** — there is nowhere to put the disclaimer, and a reader reading the
formula is not reading the register.

**b. The error is silent and produces confident output.** ⚠️ Substituting one meaning for the other does
not fail, warn, or look odd. **It yields a sentence that reads as a finding.**

**c. It is most likely to occur exactly where it matters most.** ⭐⭐ Anyone asked to compare the two
streams' accounts of change is holding both notations at once — **and that is the task where a false
equivalence would be most consequential.**

**d. It already came close.** ⭐⭐⭐ The review filing this item was asked to establish whether the two
streams' change-accounts correspond. **The shared notation was the single most natural route to a false
positive**, and avoiding it required going back to each stream's own primary documents and comparing
what a numbered change *is* in each — not what it is called.

## 3 · What is *not* the problem

⛔ **Neither stream chose badly.** The letter is a conventional choice for a transition in both
mathematics and modelling; **each usage is entirely standard in isolation.** ⛔ **No renaming is
proposed** — renaming would invalidate formulas in both streams, which is a larger act than this record
should imply. ⛔ **And no result on either side is questioned**: both accounts are internally coherent.
**The exposure exists only where the two are read together.**

## 4 · Candidate requirement (a direction, not a design)

> ⭐ **Where two work streams share notation for different objects, a comparison between them should
> begin by restating what each symbol denotes in its own stream, from that stream's primary source —
> before any correspondence is proposed.**

⭐⭐ **That is a procedural safeguard, not a notation change**, and it is cheap: it costs one paragraph at
the top of any cross-stream comparison, and it is the step that caught the problem this time.

## 5 · Relationship to existing items (`ES-005.4` — never a copy)

| item | overlap | why this is separate |
|---|---|---|
| `EKS-14` — construct named as a product component *(and its overloaded-word instances)* | both concern shared labels | ⛔ those are **words**, and the recorded remedy is a **disambiguation register**. **A register cannot reach inside a formula**, so the remedy that fits there does not fit here |
| `EKS-21` — searches that misreport | both produce false findings | ⛔ `EKS-21` is a **measurement** failure — the search reports wrongly. Here the **search is correct** and the **interpretation** collapses two objects |
| `EKS-24` — thread conclusions in unranked files | both are about missing something | ⛔ that is **not reading far enough**; this is **reading correctly and mapping wrongly** |
| `EKS-23` — two capability vocabularies never reconciled | closest neighbour | ⛔ that concerns **two vocabularies left unreconciled**; this concerns **one symbol that looks reconciled already** — the opposite failure |

⭐ **Checked and distinct on all four counts.**

## 6 · Urgency

⭐⭐ **Low frequency, high severity, and rising with the work's direction.** It bites only during
cross-stream comparison — but cross-stream comparison is precisely what the programme is now doing, and
a false equivalence there would propagate into any subsequent claim built on it.

## 7 · Evidence

* `docs/knowledgeos/theory-extraction/95-P77-…` §9 — the collision stated, with both usages quoted from
  their own primary sources.
* The same review's §5 type table — what a numbered change *is* in each stream, and why the two cannot be
  related as they stand.
* The state-and-transition register in `docs/knowledgeos/brainstorming/verification/spec/`, §3, which
  records the one stream's usage.

---

⛔ **Registered under the operating model's §37 — *"when a deeper requirement is discovered: record it as a follow-up and STOP."* No remedy is designed and no work is commissioned.**

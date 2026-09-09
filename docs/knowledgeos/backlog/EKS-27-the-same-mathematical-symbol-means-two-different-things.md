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

---

## ⚠️ Provenance correction, same day — this was already recorded, and more broadly

⭐⭐⭐ **This item was filed as a new discovery. It is not one.** A complete reading of the verification
lane's state-and-transition register — performed within an hour of filing, under a strengthened rule
requiring forward documents to be read **in full** rather than searched — found the problem already
recorded there, in a numbered list of contradictions, dated **2026-08-29**:

> **"Systematic glyph collisions"** — with the offending symbols enumerated, including **the transition
> symbol used for both a state transition and a decision function**, a provenance symbol also used for a
> process, and a witness symbol also used for a world state.

⭐⭐ **So the estate had not only noticed the class of problem; it had catalogued the specific symbols,
one of which is the very symbol this item was filed about.**

### Why the item still stands, and what changes

⛔ **The item is not withdrawn.** Its substance was never *"this can happen"* — it is that **a symbol
inside a formula cannot be disambiguated the way a word can**, and that a cross-stream comparison is
where the collapse occurs. ⭐⭐⭐ **The estate's record makes that case stronger, not weaker:** the
collisions were catalogued eleven days ago, **and nothing has been done about them**, which is exactly
what an item on this list is for.

⚠️ **What does change:**

**a. The count is larger than filed.** ⭐ This item described **one** symbol shared by two streams. The
register lists **nine** collision families, several with three or more meanings each.

**b. The framing was too narrow.** ⭐⭐ Filed as *"two work streams share one symbol."* The record shows
**collisions internal to single documents** as well — a symbol carrying two meanings in the same file.

**c. And the register carries the remedy this item asked for.** ⭐⭐⭐ Its closing note observes that
because the source documents have a three-layer structure — **definition, then rebuttal, then revision**
— any consolidated record must carry **per-claim layer provenance**, because *"a claim's status depends
on which layer asserts it."* **That is a sharper statement of the requirement in §4 above than §4
manages.**

### The filing error itself

⭐ Filed as novel while the evidence sat in a 136-line register the filer had already searched twice —
⛔ **but had never read completely.** ⚠️ **That is the failure the strengthened reading rule exists to
prevent, and it happened once more before the rule was applied.** Recorded here rather than on a
separate item.

**Evidence:** `docs/knowledgeos/theory-extraction/96-P78-…` · the state-and-transition register's
contradiction list, item on glyph collisions, and its closing note on layer provenance.

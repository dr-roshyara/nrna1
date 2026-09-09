# EKS-40 — Documents written in the same batch give contradictory dispositions of the same item — one calls it open, another calls it ratified — and nothing reconciles them

**Status:** **BACKLOG · DISPOSITION-INTEGRITY EXPOSURE** — registered from `P-93` (`docs/knowledgeos/theory-extraction/114-P93-…`). ⛔ **Not commissioned; activation requires a Human/PO-ARB authorization act.**
**Class:** operating-model problem — what the record's own position *is*.
**Registered by:** Lane T (theory extraction), 2026-09-09.

> ### ⛔ **This item records a problem and a candidate requirement. It commissions nothing, adjudicates nothing, and says nothing about which disposition is correct.**

---

## 1 · The problem in plain terms

A research record is expected to say where things stand: what is settled, what is proposed, what is
still open. ⭐ **This one says several things at once.**

Within **a single batch of twenty-seven documents written the same afternoon**, the same items receive
**opposite dispositions**, and no document mentions the other:

| the item | one document says | another says |
|---|---|---|
| the central specification | ⭐ *"**READY FOR RATIFICATION** — every component complete"* | ⭐ *"**not yet ratifiable**"* — rejecting completeness and minimality by name |
| a core semantic operator | ⭐ *"**no formal specification exists**"* | ⭐⭐ **the specification, marked `[RATIFIED]`** |
| the transition operator | *"only a signature exists"* | **a full specification — included three times over** |
| how many items are being ratified | **twelve** | **eleven** · and, in a third document, **ten** |

$$\boxed{\begin{array}{c}\textbf{⭐⭐⭐ Two documents } \mathbf{two\ files\ apart} \textbf{: one says an operator has no specification;}\\ \textbf{the other } \mathbf{is} \textbf{ the specification, and is marked ratified.}\end{array}}$$

## 2 · Why this is different from work being duplicated

The programme already tracks efforts that repeat each other without cross-citing. ⭐ **Duplication
wastes effort; the record still says one thing.**

⭐⭐⭐ **Here the record says two incompatible things, and both are quotable.** Anyone can cite the
position they prefer, accurately, with a reference — and so can anyone who disagrees.

## 3 · Why this is a business problem

**a. There is no answer to *"where do we stand?"*** ⛔ Not because nobody wrote it down, but because
**several people did, differently, at the same time.** ⭐ A reader cannot resolve it from the record
alone, and neither can a reviewer, a successor, or a governance body.

**b. Work gets done twice, or not at all.** ⭐⭐ An operator declared *unspecified* invites someone to
specify it — **while a ratified specification of it already sits two files away.** The opposite failure
is equally available: a genuinely open item can be waved through by citing a document that calls it
closed.

**c. It is invisible to every safeguard the programme has.** Each document is internally coherent,
carefully written, and correctly stamped. ⛔ **The contradiction exists only between them**, and nothing
compares documents from the same batch.

**d. The status vocabulary itself has fragmented.** ⭐ Five different status words are in use across one
package — proposal, advisory, final register, ratified, and a word embedded in a document identifier —
⚠️ **so even *reading* the dispositions requires knowing which vocabulary each document is using.**

**e. It is not an isolated event.** ⭐⭐ **Three separate instances have now been observed**, in two
different lanes: two on the specification's ratifiability, and one where a decision register retires a
question that another register carries as its principal open item. ⛔ **A pattern, not an accident.**

## 4 · What is *not* the problem

⛔ **No document is careless.** Several are unusually rigorous — one goes out of its way to strip four
items from a decision list to avoid manufacturing decisions; another corrects an over-strong definition
in the same breath as closing it.

⛔ **Disagreement is not the problem.** ⭐⭐ **Disagreement is how this programme works, and it has
repeatedly caught real errors.** The problem is that a disagreement was never *recorded as one* — each
side simply states its position as the position.

⛔ **This is not about which side is right.** That is precisely what is not being decided here.

⛔ **No adjudication procedure, status vocabulary or register format is proposed.**

## 5 · Candidate requirement (a direction, not a design)

> ⭐ **When two documents give an item different dispositions, the disagreement should itself become
> part of the record — visible from the item, not discoverable only by reading both documents.**

⭐⭐ Two properties matter more than the format:
1. **A contradiction is information, not a defect to be tidied away.** ⭐ *"Two documents disagree on
   whether this is ratifiable, here is each"* is more useful than either statement alone — **and far
   more useful than silently keeping the later one.**
2. ⭐ **The check must run over a batch, not over a document.** ⛔ Every document here passes review on
   its own. **The only way to see the problem is to compare siblings.**

## 6 · Relationship to existing items (`ES-005.4` — never a copy)

| item | overlap | why this is separate |
|---|---|---|
| `EKS-36` — two same-day efforts never cross-cite | ⭐ **closest; same batch, same silence** | ⛔ that is **duplicated work** — two efforts doing the same thing. This is **contradictory dispositions** — two documents saying incompatible things. Duplication wastes effort; contradiction destroys the answer |
| `EKS-38` — two registers share one ID space | both concern reading the governance record | ⛔ that is **one name, two items**. This is **one item, two verdicts** |
| `EKS-31` — files enter without provenance; repeats not countable | both concern batch hygiene | ⛔ that is about **counting** documents; this is about **believing** them |
| `EKS-30` — no register of a lane's own settled questions | both concern *"what is settled?"* | ⛔ there the answer exists and is unfindable. ⭐ **Here the answer does not exist — two were written** |
| `EKS-35` — timestamps do not encode argument order | ⭐ both concern reading a batch | ⛔ that is about **order**; this is about **content**. Perfect ordering would not resolve two opposite verdicts |

⭐ **Checked and distinct on all five counts.**

## 7 · Urgency

⚠️ **High, and it compounds with the programme's stage.** ⭐⭐ The material in question is precisely what
a governance body would be asked to ratify — ⛔ **and it currently supports both *"ratify this"* and
*"this is not ratifiable"*, with citations.**

⭐ **Detection is cheap:** the three known instances were all found by reading sibling documents from
one batch side by side.

## 8 · Evidence

| | |
|---|---|
| all three instances, with both sides quoted | `docs/knowledgeos/theory-extraction/114-P93-…` §§2–3 · `111-P90-…` §0.1 |
| *"READY FOR RATIFICATION"* vs *"not yet ratifiable"* | `…/mathematical_ideas_that_can_be_implemented/20260902-182013…` vs `…-182008…` |
| *"no formal specification exists"* vs the ratified specification | `…-182019…` `O1` vs `…-182017…` |
| ten / eleven / twelve | `…-182011…` §2 · `…-182013…` Part 3 · `…-182016…` §2 |
| the third instance, another lane | a decision register retiring a question another register carries as its principal open item — `111-P90-…` §0.1 |

⛔ **No claim is made about which disposition is correct in any of the three cases.**

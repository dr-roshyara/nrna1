# EKS-24 — The corpus is a saved conversation, so a discussion's conclusion sits in a later, smaller file that no search will rank

**Status:** **BACKLOG · DISCOVERABILITY EXPOSURE (STRUCTURAL)** — registered from `P-71` (`docs/knowledgeos/theory-extraction/89-P71-…`). ⛔ **Not commissioned; activation requires a Human/PO-ARB authorization act.**
**Class:** operating-model problem — how the research record must be read.
**Registered by:** Lane T (theory extraction), 2026-09-09.

> ### ⛔ **This item records a problem and a candidate requirement. It commissions nothing, proposes no tooling, and criticises no document.**

---

## 1 · The problem in plain terms

Much of the research record was produced as a working conversation and saved one file per turn, named by
the moment it was saved. ⭐ **That is a sound way to keep a record — nothing is lost and the order is
exact.**

⭐⭐⭐ **But it means a discussion's *conclusion* is a separate file from the discussion.** And
conclusions are short. A long, substantial document is followed by a brief one saying *"accepted, with
one correction"*, and then a briefer one saying *"do not proceed to the next version yet."*

$$\boxed{\textbf{The } \mathbf{substance} \textbf{ is in the long file. The } \mathbf{decision} \textbf{ is in the short ones that follow it.}}$$

⚠️ **Every way of finding documents that anyone actually uses ranks the long file first and the short
ones nowhere.** They contain few keywords and no distinctive vocabulary — because their job is to
*dispose of* something argued elsewhere.

## 2 · It happened, and the numbers are stark

A review had read a **3 054-line** technical document **in full**, and drawn on it across **four**
successive reviews. It never read the next three files. Those were **708, 201 and 70 lines**, and between
them they held:

* ⭐ the **status** of the long document — a specification for future work, **not** a theory;
* ⭐ an explicit ruling that the **current theory version stays frozen**;
* ⭐⭐ **four principles frozen as method**, two of which directly governed the reviewing work;
* ⭐⭐⭐ **a twelve-step order of dependencies**, showing the reviews had been working on steps 10–12
  while steps 1–4 were undone;
* ⭐⭐ and **two conclusions the reviewer later reported as original findings** — five days after the
  record already contained them.

⭐ **Reading the three files took a fraction of the effort already spent on the one.**

## 3 · Why this is a business problem, not a reading preference

**a. It hides exactly the most decisive content, and always in the same direction.** ⭐⭐ Dispositions,
acceptances, rejections and *"do not proceed"* rulings are the highest-value statements in a research
record — **and structurally the least findable.** The bias is not random.

**b. Searching well does not help.** ⚠️ **A reader can search perfectly and still miss it.** Nothing is
mis-filed, mis-named or duplicated — the file simply gives a searcher no reason to open it. **This is a
different failure from every other on this list.**

**c. It produces confident work on superseded material.** ⭐⭐⭐ The worst case is not ignorance but
**building carefully on a document whose own thread later reclassified it.** Four reviews treated a
specification as though its status were open; it had been settled in a seventy-line file.

**d. Rediscovery arrives weaker.** ⭐ Conclusions reached again independently were stated **less
precisely** than the record already had them, and were briefly presented as new.

**e. The remedy is small and already demonstrated.** ⭐⭐ **On finding something relevant, read the next
few files in save order until the subject changes.** In the case above the subject changed after four
files, so the stopping point was obvious. **No tool, index or reorganisation is required.**

## 4 · What is *not* the problem

⛔ **The naming convention is not at fault** — timestamped names are what made the fix possible at all.
⛔ **Nothing is misplaced.** ⛔ **The short files are not deficient** — a two-line disposition is the
right length for a disposition. ⛔ **And this is not a call to read exhaustively**; it is the opposite —
**a few files forward, then stop.**

## 5 · Candidate requirement (a direction, not a design)

> ⭐ **When a document in the saved record is used as evidence, the following files in save order should
> be read until the subject changes — and any claim about a document's status should cite that forward
> read.**

⭐⭐ **The second half matters as much as the first.** A statement that a document *"is the current
specification"* or *"remains open"* is a claim about the **thread**, not the file, and cannot be
supported from the file alone.

## 6 · Relationship to existing items (`ES-005.4` — never a copy)

| item | overlap | why this is separate |
|---|---|---|
| `EKS-16` — derivation without consulting existing theory | both end in relevant material unused | ⛔ `EKS-16` is **the search was not run**. Here the search **was run, succeeded, and returned the right document** — the conclusion was in the next one |
| `EKS-17` — duplicate research roots | both are discoverability | ⛔ `EKS-17` is **two folders sharing a name**; here the file was unambiguous and correctly found |
| `EKS-18` — open questions with no route to the work | both concern information not reaching a reader | ⛔ `EKS-18` is about **separate work streams**; this happens **inside one thread, to a reader already holding it** |
| `EKS-21` — negative findings with no positive control | both concern search reliability | ⛔ `EKS-21` is **the search misreported**; here it reported correctly and the reader stopped one file early |
| `EKS-23` — two capability vocabularies never reconciled | both concern the record's coherence | ⛔ that is **two bodies of work not compared**; this is **one thread not read to its end** |

⭐ **Checked and distinct on all five counts.**

## 7 · Urgency

⭐⭐ **Moderate, and it decays only if the convention changes.** Every future use of the saved record is
exposed, and the exposure is largest for the most consequential statements *(§3a)*. ⭐ Against that, the
remedy is the cheapest on this list — **a few extra files per citation** — and requires nothing built.

## 8 · Evidence

* `docs/knowledgeos/theory-extraction/89-P71-…` §2, §3, §5, §6, §8 — the forward read, the three files,
  and what each contained.
* The four reviews that used the long document without its successors: `83-P65-…`, `84-P66-…`,
  `86-P68-…`; and the two whose findings the record already held, `87-P69-…` and `88-P70-…`.

---

⛔ **Registered under the operating model's §37 — *"when a deeper requirement is discovered: record it as a follow-up and STOP."* No remedy is designed and no work is commissioned.**

---

## Corroboration from the estate's own verification, 2026-09-09

⭐⭐⭐ **The independent verification programme recorded this exact failure as one of its findings, in a
single sentence that is better evidence for this item than anything written above:**

> **"A genuine mathematical error at [one step], correctly repaired eight steps later without anyone
> noticing."**

⭐⭐ **Read what that says.** A real error was made. It was **fixed** — correctly — **eight documents
later.** And **nobody knew**, in either direction: the reader of the error did not see the repair, and
the record does not connect them.

$$\boxed{\textbf{The error and its repair are } \mathbf{eight\ files\ apart,\ and\ nothing\ links\ them.}}$$

### Why this matters for this item specifically

**a. It is the failure mode, witnessed by an independent party.** ⭐ This item argues that a thread's
later content is structurally hard to find. **Here the estate's own verification found a case where the
resolution sat eight files downstream and had gone unnoticed** — including by whoever wrote it.

**b. It shows the cost runs both ways.** ⭐⭐ Stopping early leaves you with **an error you think is
current**; it also leaves you unable to credit **a repair that was actually made.** The second is the
more damaging for a research record, because it makes careful work look careless.

**c. A review filing this item stopped one file past the error and seven short of the repair.** ⚠️ That
is not offered as an excuse but as a measurement: **the distance was eight, and stopping at one was not
obviously insufficient at the time.**

⛔ **No remedy is added here.** The direction in §5 above already covers it — read forward until the
subject changes — and this entry simply raises its evidence from *one reviewer's experience* to *a
finding recorded independently by the estate's verification programme.*

**Evidence:** `docs/knowledgeos/theory-extraction/93-P75-…` §4.2 · the finding's own title in the
verification lane's findings register.

# EKS-35 — File timestamps do not encode argument order, so the prescribed way of reading the record can reconstruct a discussion backwards

**Status:** **BACKLOG · RESEARCH-METHOD CORRECTNESS EXPOSURE** — registered from `106` (`docs/knowledgeos/theory-extraction/106-READ-RECORD-2026-09-04-…`). ⛔ **Not commissioned; activation requires a Human/PO-ARB authorization act.**
**Class:** operating-model problem — how the research record must be read.
**Registered by:** Lane T (theory extraction), 2026-09-09.

> ### ⛔ **This item records a problem and a candidate requirement. It commissions nothing, proposes no tooling, and criticises no document.**

---

## 1 · The problem in plain terms

Much of the research record is a working conversation saved one file per turn, each named by the moment
it was saved. ⭐ **The programme therefore reads it chronologically** — find a document, then read the
ones saved after it, and the argument unfolds: proposal, objection, correction, conclusion.

⭐⭐ **That method is sound, it is written down, and it is used constantly.** It rests on one assumption
nobody has checked:

$$\boxed{\textbf{that the order files were } \mathbf{saved} \textbf{ is the order the argument was } \mathbf{made}.}$$

**On at least one whole block of one day, it is not.**

## 2 · What was found

A block of about a dozen documents from one morning was **saved again that afternoon** — and the
afternoon copies appear in **exactly the reverse order**:

| the morning, in order | 1st | 2nd | 3rd | … | 11th | 12th |
|---|---|---|---|---|---|---|
| ⭐ **the afternoon re-save** | **12th** | **11th** | **10th** | … | **2nd** | **1st** |

⭐⭐ **Eleven of twelve pairs match. The afternoon timestamps count down exactly as the morning ones
count up.**

## 3 · Why this is a business problem

**a. The prescribed method produces a backwards reading, silently.** Anyone following the rule on the
afternoon block reads **conclusions before the premises**, **corrections before the thing corrected**,
and treats an **earlier draft as a later refinement**. ⛔ **Every judgement about *"what the thread
concluded"* is then inverted.**

**b. The method cannot detect its own failure.** ⭐⭐ Timestamps alone show a perfectly clean ascending
sequence. ⛔ **Nothing in the filenames, the lengths, or a keyword search reveals the reversal.** It was
found only by comparing content between the two blocks.

**c. New work is mixed in with the reversal.** About a dozen genuinely new documents sit **interleaved**
among the re-saves. ⚠️ **So a reader cannot even quarantine the block** — separating new material from
reversed copies requires checking content, document by document.

**d. It undermines conclusions already drawn from that region.** Any earlier reading of that block
followed the same rule and had the same exposure. ⭐ **Nobody knows which conclusions, if any, were
affected** — and that question cannot be answered from the timestamps either.

**e. The rate is not negligible.** Two consecutive days were measured: **roughly a tenth of each day's
material is a byte-identical copy of something else in the same folder.** ⚠️ **Where copies are that
common, the assumption that save-order equals argument-order is doing a great deal of unexamined work.**

## 4 · What is *not* the problem

⛔ **The chronological reading method is not wrong and should not be abandoned.** ⭐ **It is the right
method and it has repeatedly found things no keyword search would.** The defect is a **precondition it
depends on and does not check**.

⛔ **Nobody mis-saved anything deliberately.** Re-saving a block is an ordinary thing to do; recording
the order in reverse is an artefact, not a fault.

⛔ **This is not the duplicate-marking problem already registered elsewhere.** That item concerns
**counting** — whether repeated work can be recognised as one. ⭐⭐ **This concerns *ordering* — whether
the sequence can be trusted at all.** A perfectly marked set of copies would still be reversed.

⛔ **No tool, hashing scheme, naming convention or index is proposed here.**

## 5 · Candidate requirement (a direction, not a design)

> ⭐ **Before a stretch of the record is read as a developing argument, it should be possible to
> establish that its save order is its argument order — and where it is not, the reader should be able
> to find that out cheaply.**

⭐⭐ Two properties matter more than the format:
1. **The check must be cheap and routine.** ⛔ A check that costs as much as the reading will not be
   done. **A content comparison across a block is cheap; reading it twice is not.**
2. ⭐ **A negative answer must be recordable.** *"This block is a reversed re-save of that one"* is a
   useful, permanent fact about the record — ⛔ **and today there is nowhere to write it down.**

## 6 · Relationship to existing items (`ES-005.4` — never a copy)

| item | overlap | why this is separate |
|---|---|---|
| ⭐⭐ `EKS-24` — a discussion's conclusion sits in a later, smaller file no search will rank | ⭐ **closest neighbour; same reading discipline** | ⛔ `EKS-24` **assumes timestamps are reliable** and says *search* is not — its remedy is to follow the chronology. ⭐⭐ **This says the chronology itself can be wrong**, which would defeat `EKS-24`'s own remedy |
| ⭐ `EKS-31` — files enter without provenance markers; repeats not countable as one | ⭐ same directory, same copies | ⛔ that concerns **counting** — can repeated work be recognised as one? This concerns **ordering** — can the sequence be trusted? **Perfectly marked copies would still be reversed** |
| `EKS-30` — no register of a lane's own settled questions | both concern navigating a large record | ⛔ that is about **which questions are closed**; this is about **whether a thread's direction is real** |
| `EKS-26` — completed coverage claims decay as the corpus grows | both weaken past conclusions | ⛔ that is about a claim going **stale**; this is about a reading being **inverted at the time it was made** |
| `EKS-29` — a construct credited to the wrong document | both are provenance errors | ⛔ that is **attribution of a result**; this is **order of an argument** |

⭐ **Checked and distinct on all five counts.**

## 7 · Urgency

⚠️ **Immediate, because the method is in active use.** ⭐ **Every commission in this programme currently
instructs the reader to follow timestamps forward.** Until the precondition can be checked, each such
reading carries an unquantified risk of inversion.

⭐ **And the cost of checking is small** relative to the cost of a reversed reading: comparing content
across a block takes seconds; re-deriving a thread whose direction was wrong does not.

## 8 · Evidence

| | |
|---|---|
| the reversed block, pair by pair | `docs/knowledgeos/theory-extraction/106-…` §1 |
| morning block | `…/mathematical_ideas_that_can_be_implemented/20260904-031628` … `20260904-042545` |
| afternoon re-save | `…/20260904-154817` … `…/20260904-160323` |
| interleaved new work | `20260904-154129`…`154743`, `155011`, `155536`, `155611`, `155938`/`160019`, `160223` |
| duplication rate, two days | `106` §2 (~11 %, 93 files) · `105` §1 (~10 %, 159 files) |
| the reading rule this affects | the standing methodological instruction used in every commission |

⛔ **No claim is made that any specific conclusion was in fact inverted** — ⭐ **only that the method
provides no way to know.**

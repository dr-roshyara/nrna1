# EKS-52 — Lanes do not enumerate each other, so the same work is done three and four times

**Raised:** 2026-09-10 · **Source:** `G-00` disposition
**Evidence:** `docs/knowledgeos/brainstorming/verification/gap-discovery/g-00-register-reconciliation/01-G-00-RECONCILIATION.md`
**Status:** OPEN · **Severity:** HIGH · **Class:** knowledge management — **not** a theory defect

---

## The problem, in business terms

The programme runs several parallel workstreams over the same material. **Each keeps its own list of
open questions, and none of them can see the others' lists.** So a question gets answered in one
stream, stays on the open list in another, and is investigated again from scratch.

This is not a hypothesis. It has now happened **four times, and been diagnosed independently each
time — by three different workstreams, one of them about itself.**

| # | who did not look where | when it was found |
|---|---|---|
| 1 | The theory workstream ordered an investigation; a second workstream ran it within 74 minutes; the first recorded it as "not tested" | 2026-09-10 |
| 2 | The reconstruction registered a naming collision as new; the verification workstream had registered it eleven days earlier as *"the most dangerous naming collision found"* | 2026-09-10 |
| 3 | ⭐ The reconstruction did not search **its own parent folder** — 121 documents, of which 101 are prior audits — where **four** of its open questions were already answered | 2026-09-10 |
| 4 | ⭐ The corpus, about itself, a fortnight earlier: *"Step 288 was written without consulting the 025i–025z seam. **This is not a failure of the corpus. It is a failure of my search.**"* | 2026-08-31 |

## What it costs, concretely

Of **12** questions on the reconstruction's open list:

* **4 were already answered elsewhere** and can be closed outright;
* **5 were framed on a premise that other material had already corrected** — they were being
  investigated in the wrong shape;
* only **3** were genuinely open as stated.

That is **three quarters of an open list that was wrong**, and every hour spent on those questions
was spent on a question that no longer existed or had never existed.

One example is worth stating plainly, because it shows the shape of the waste. A question had been
open for weeks: *why do two documents disagree about whether a quantity may be a single number?*
Reading both originals showed they were talking about **two different quantities measured over two
different things**. They never disagreed. The question existed only because the two were compared as
if they were one.

## The mechanical root cause, and it is small

Each workstream keeps a coverage ledger listing which bodies of material it has and has not read.
**The reconstruction's ledger listed every other workstream — 480 documents here, 411 there, 181
there — and had no line at all for the folder it lives in.** What is not listed is not searched.
A one-line omission produced a systematic blind spot.

*(That line has been added. The general problem has not been fixed.)*

## What would close this

| | requirement |
|---|---|
| **1** | **Every coverage ledger lists every lane, including its own.** What is not enumerated is not searched — and the omission is invisible, because an unlisted lane raises no flag |
| **2** | **One shared index of open questions**, or at minimum a rule that a question is not registered as new until the other lanes' registers have been checked. Four diagnoses is enough evidence |
| **3** | **Answers are routed by what they contain, not by where they were filed.** Each of the four cases was found in a single search once someone thought to look |
| **4** | **A question's premise is re-read before the question is worked.** Five of the twelve were mis-framed, not unanswered — and re-reading the two source documents was cheaper than the investigation would have been |

## What is NOT being claimed

* **Not** that any workstream did poor work. Every one of the duplicated findings was competent; two
  independent passes reaching the same answer is corroboration, and was treated as such.
* **Not** that the workstreams should be merged. Their independence is what makes convergent findings
  worth something.
* **Not** that the theory is affected. Every finding here is about **where knowledge is filed**, not
  about what is true.

## ⭐ Amendment, same day: the problem is bigger, and it has a second cause

A systematic count of one workstream's registers found **~46 separate lists of open items**, using
**at least ten different identifier prefixes**. Two of those prefixes are **used by two different
lists at once**:

* one prefix numbers a 21-item adjudicated list **and** a 7-item list about a different subject;
* another numbers a 17-item adjudicated list **and** an 88-item list explicitly marked *"none is
  reconciled; none is adjudicated."*

**The consequence is worse than the first cause.** With one shared identifier space, a diligent
person who cross-references before opening a question will still miss the answer, because the same
identifier points at two things and the same finding carries two identifiers.

The naming collision this ticket's sibling (`EKS-51`) reports was in fact **already recorded twice**,
under two identifiers, in two lists — and a **third** list ranks the severity of that same class of
problem and **does not include that symbol at all**. Three lists, one finding, three identifiers,
disagreeing severities.

**So requirement 2 above is not sufficient on its own.** Checking other lists only works if
identifiers mean one thing. Add:

| | requirement |
|---|---|
| **5** | **One identifier space, or explicit namespacing.** An identifier that names two things names neither — the same defect already recorded twice in the ticket numbering itself |
| **6** | **A single index of the lists**, before any attempt at a single index of the items. Nobody currently knows how many open-item registers exist; the count above took a dedicated search |

## Related

| | |
|---|---|
| `EKS-49` | a commissioning lane and an executing lane never tell each other — **the same defect, first instance** |
| `EKS-51` | one symbol re-used for a different concept — **found by re-deriving an instrument that already existed** one folder up |

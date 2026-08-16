# Decision Summary — Independent Review of the Stage-1 Attribution Architecture

**To:** Human / PO / ARB · **From:** Governance (translation and recommendation per ES-001.3 — the findings are the reviewer's; this summary decides nothing)
**Source:** the independent architecture review (`a8d607a0` + erratum `3ff6b67a`), produced by a fresh terminal under the satisfied performer clause. The reviewer correctly declined to write this summary itself — a producer recommending the disposition of its own findings would make the review its own advocate.

---

## What is happening

The independent reviewer examined the Stage-1 domain design and returned a clear overall verdict: **the architecture is sound in its main decisions, with two significant gaps in its reasoning and bookkeeping, and six small repairs.** Nothing was found that overturns a design decision; what was found is that one key justification proves the wrong thing, and the event bookkeeping lost track of three items.

## Why it matters

The two major findings sit exactly where this programme has been burned before:

- **F-1 (major):** the aggregate boundary is *right*, but the argument written for it doesn't actually justify it — the real justification is an unstated rule (*at most one established assessment per claim*) that the approved model presupposes but never says. **Approving the boundary on the current text would approve a correct conclusion on an unsound argument.** The repair is one section plus **one ARB interpretive sentence that only you can give** — stating the uniqueness rule as your reading of the approved model.
- **F-2 (major):** the event disposition miscounted — thirteen events proposed, ten dispositioned, "ten" itself a miscount, three events silently dropped, one of them likely a **fifth domain fact** required by the approved lifecycle. Repair: one table. *(The reviewer then committed the same class of error — a count — in its own projection, found it itself, and appended an erratum rather than rewriting. The finding stands; the erratum strengthens rather than weakens the report's credibility.)*
- **F-3…F-8 (moderate/minor):** each repairs in a paragraph, a rule, a row, or a recorded constraint — the largest being F-5's architectural constraint that *the input set of every workflow gate is closed*, which converts INV-ATTR-1's protection from a property of today's code into a property of the architecture.

## The reviewer's recommendations on your open questions, translated

**Approve now, as precised:** Q-A1 (vocabulary, carrying F-8's rename) · Q-B2 (one *current* claim per act and dimension) · Q-B3 (outcome as derived value) · Q-B4 (with F-5's constraint) · Q-B5 (with F-2's disposition) · Q-B7 (with F-6's rule and F-7's row) · Q-B8 (epistemic wording).
**Defer:** Q-A2 to Stage 2 — canonical vocabulary should not outrun unapproved architecture.
**Approve only after repair:** Q-B1 (after F-1 — on the recorded uniqueness interpretation, not the current text) · Q-B6 (after F-2 — expected result five domain facts, not four).
**Yours alone:** Q-C1 — where Stage 2 lives. *(Governance recommendation: a new assignment in the same work item, the precedent now twice applied; the record's role set already excludes implementation.)*
**Closed:** Q-D1 — the review itself discharges it.

## My recommendation

Take this in three acts, in order: **accept the review and conclude the verification** (the lane is done; its verdict is delivered); **decide the question package** per the reviewer's §7, including your F-1 interpretive sentence; **hand the work back to Architecture for the repair pass** — the architecture lane was deliberately left open for exactly this (finished its part, not formally closed), so the repairs go back to it without any new work item. Re-verification of the repaired design is a fresh decision at that gate — the first reviewer has already flagged its own future entanglement, honestly and early.

## Human action required

> **RECORD: I accept the independent review as delivered.**
> **COMPLETE: the independent verification session.**

*(then, when ready:)*

> **RECORD: [your decisions on Q-A1…Q-B8, and the F-1 uniqueness sentence in your words]**
> **RECORD: Hand the work back to Architecture for the F-1…F-8 repair pass.** → **START: the repair work.**

*(Vocabulary per the ARB's same-day correction: RECORD for events and decisions · START/COMPLETE for opening and formally closing a session.)*

## Evidence

Findings register §6 and recommendations §7 of `2026-08-16-KOS-ATTR-ARCH-001-rev3-independent-architecture-review.md` (`a8d607a0`, erratum `3ff6b67a`) · repair sizes as stated per finding · the architecture lane HANDED_OFF-not-COMPLETED (seq 5, Stage 2 and repairs remain its future work) · the verification lane ACTIVE, its closure awaiting your RECORD · the reviewer's forward entanglement flag for the re-verification gate.

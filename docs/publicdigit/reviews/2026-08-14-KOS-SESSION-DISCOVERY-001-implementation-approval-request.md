# Approval Request — Build the Session Assignment Lookup (implementation plan)

**Prepared by Governance (Session 2) · 2026-08-14 · Business-language format per the adopted convention · One decision, plus two small amendments recommended.**

---

## Purpose

Build the read-only lookup you already approved in design: a tool that tells a terminal **which piece of governed work it is assigned to**, and stops rather than guesses when it cannot tell safely.

## Why it matters

This is the plan for the thing itself. You approved the *direction*; this is **exactly what would be built**, down to the file list, so that nothing appears later that you did not see now.

## What has been produced

An implementation plan — **no code was written.** It commits to:

- **Three things get created:** the lookup tool, its tests, and one entry in the platform's asset register. Nothing else. No second script, no shared library, no new folders.
- **The existing, just-qualified workflow tool is not touched at all.** The new tool asks it for every answer rather than working anything out itself — so the two can never disagree, because only one of them ever decides. *(Governance verified independently that asking it questions cannot change any records.)*
- **The strongest test proves it cannot write:** the records are checked byte-for-byte before and after every run, on every path, including the broken-record path.
- **It never claims someone is allowed to do something.** It reports what the records show, and says **"unknown"** for the two things the records genuinely cannot establish.
- **Four answers only:** found · none · more than one · unreadable. When more than one applies, it lists them all and **chooses none**.

## What I am being asked to approve

**The plan to build exactly this — and only this.** After approval, the team writes failing tests first, then the tool, then hands it to independent verification.

## What this approval does NOT authorize

Changing the existing workflow tool · running the lookup automatically at session start · deciding whether anyone is allowed to act · the six related record improvements · anything on the Election side.

## Two amendments Governance recommends

**① Settle what the tool's exit signal means.** The approved design said the tool should signal "success" whenever it produced an answer — including the answers *"no assignment"* and *"more than one."* The plan instead signals failure for those two. That matters: tools and wrappers routinely discard "failures", so the two answers most needing a human's attention could be silently swallowed. **Recommendation: keep the design's version** — success whenever an answer was produced, with the answer itself carried in the report.

**② Add one test for the rule you made binding.** You ruled that the existing workflow tool is the authoritative interpretation and the new one must not compete with it. The plan's tests prove the new tool cannot *write*, but nothing proves it does not quietly *work things out for itself*. **Recommendation: add a test that the new tool's answers match the existing tool's, and that it cannot answer at all without it.**

Three smaller confirmations the team asked for: the tool's name (`session-resolve.php`), no developer-guide page (the register entry plus the file's own header is the documentation), and that the "separate tool" decision you already made stands. **No objection from Governance on any of the three.**

## Technical reference

Implementation plan: `docs/publicdigit/reviews/2026-08-14-KOS-SESSION-DISCOVERY-001-implementation-boundary-proposal.md` · Governance's full twelve-point review, including how each finding was classified: `…-boundary-governance-review.md`.

## Decision

**APPROVE / APPROVE WITH AMENDMENTS / AMEND / DECLINE** — and, if approving, whether amendments ① and ② are included.

*Nothing is built on this approval alone until Governance registers it and issues the build permission.*

---

## DECISION RECORDED — BOUNDARY APPROVED WITH AMENDMENTS ① AND ② (PO/ARB, 2026-08-14, verbatim)

> *"approve the implementation boundary with amendments ① and ②.*
>
> *Preserve the architecture's success semantics: UNASSIGNED and AMBIGUOUS are valid answers, not command failures.*
> *Add the test proving that the resolver delegates interpretation to the existing qualified workflow mechanism and cannot independently determine workflow state.*
>
> *The approved architecture remains unchanged. Implementation may proceed only within this amended boundary.*
>
> *— PO/ARB"*

### Amendment ① — success semantics (binding, resolves the unresolved question)

**UNASSIGNED and AMBIGUOUS are valid answers, not command failures.** The architecture's convention is restored: **the command succeeds whenever a ResolutionReport was produced**, with the verdict carried *in the report*; non-zero exit is reserved for usage error and refusal, never for a successfully-produced verdict. **Boundary §2's verdict-dependent mapping is superseded on this point, and T-12 must pin the amended contract** (a report on every verdict path → success; UNRESOLVABLE remains STOP-shaped **in the report's verdict and operability**, not by pretending the command failed).

### Amendment ② — the precedence test (binding, becomes T-13)

**A test must prove the resolver delegates interpretation to the qualified mechanism and cannot independently determine workflow state.** Two assertions satisfy it: the resolver's state values **equal** the qualified mechanism's own output for the same record, **and** the resolver **cannot answer** when the mechanism is unavailable. This is the binding precedence rule's own test — the constraint is now enforced by the suite, not only by design intent.

### What is now authorized

**Implementation, strictly within the amended boundary** — the three capability paths (resolver · contract tests T-1…T-13 · one registry entry) plus standing bookkeeping, RED before GREEN, no self-certification, handoff to independent verification. **The approved architecture is unchanged.** All §6 exclusions stand unchanged: no `workflow-state.php` modification · no second interpretation · no authorization engine · no automatic activation · no startup wiring · no hooks/locks/leases · no D-1…D-6 · no Increment-2 · no Election work · no registry schema change.

### Governance's three carried confirmations

Script name `session-resolve.php` ✅ · no developer-guide entry (register entry + file header is the documentation surface) ✅ · "separate tool" reading of Q-B stands ✅ — all confirmed by the approval's *"only within this amended boundary"*, which adopts the boundary as presented except where amended.

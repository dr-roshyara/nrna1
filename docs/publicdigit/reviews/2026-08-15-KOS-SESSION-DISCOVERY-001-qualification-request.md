# Approval Request — Does the finished Session Assignment Lookup meet the requirement?

**Prepared by Governance (Session 2) · 2026-08-15 · Business-language format · One decision, with one small optional condition.**

---

## Purpose

Decide whether the finished tool meets what you approved — the last decision before it becomes a live part of the platform.

## What was built

The read-only lookup you approved: it tells a terminal which piece of governed work it is assigned to, and says **found · none · more than one · unreadable** — never a guess.

## What the independent verifier did — and it attacked, not just re-ran

The verifier tried to break the two safeguards you made binding:

- **It removed the existing qualified tool.** The lookup **refused to answer** rather than working things out itself.
- **It replaced that tool with one that deliberately lied.** The lookup **relayed the lie** rather than secretly consulting the records behind its back — proving it genuinely delegates and has no interpretation of its own.
- **It checked your success rule behaviourally:** all four answers report success; only a genuine misuse reports failure. So *"no assignment"* and *"more than one"* can no longer be silently discarded.
- **It ran the tool eight times across every path** and confirmed the records were left **byte-for-byte identical**.
- **It confirmed the tool grants nobody anything** — no permission, no ownership, no state change.

**Result: verified, with findings — no defect, no scope violation.** The verifier changed nothing: its only file is its own report, and the implementation is untouched since it was built.

## Two findings, and what Governance makes of them

**① A rough edge, not a fault.** Pointing the tool at a folder that doesn't exist looks the same as an empty one, so it answers *"no assignment."* That answer is safe — it means *stop and ask Governance* — so this is a diagnostics nicety, not a problem with what you approved.

**② One genuine question worth your attention.** The tool can be pointed at a *different* interpreter through a technical switch. Three things bound it: the switch exists **because you required a test proving the tool cannot answer without the real interpreter** — you cannot prove that without being able to take it away; the code declares it a test-only seam that nothing else depends on; and it cannot grant anyone anything — at worst it produces a *misleading report*, and a report was never permission.

**But Governance found one gap while checking it: the report doesn't say which interpreter produced its answers**, so a substitution would be silent. **A one-line improvement — the report names the interpreter it used — would make it visible without removing the test.** Your choice whether that is a condition now or a follow-up.

## What I am being asked to decide

**Does the finished capability meet the requirement?**

## What this decision does NOT authorize

Automatic use at session start · the six related record improvements · changes to the existing qualified tool · anything on the Election side.

## Technical reference

Verifier's report: `…-session1-verification.md` · Governance's review of it, including the full reasoning on finding ②: `…-verification-governance-review.md`

## Decision

**QUALIFIED / QUALIFIED WITH CONDITION (add the interpreter-name line before adoption) / NOT QUALIFIED**

*After this, Governance marks the tool adopted in the platform register and closes the work item — the last step.*

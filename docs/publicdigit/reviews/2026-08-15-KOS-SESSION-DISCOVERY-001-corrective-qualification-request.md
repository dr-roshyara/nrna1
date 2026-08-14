# Qualification Request — Session Assignment Discovery

**To:** Product Owner / Architecture Review Board · **From:** Governance · **Date:** 2026-08-15
**Technical evidence:** `2026-08-15-KOS-SESSION-DISCOVERY-001-corrective-governance-review.md`

---

## What was built

A small **read-only lookup tool**. An assistant session working on this platform can ask it a single question — *"which piece of work am I assigned to, and am I cleared to act?"* — and get an answer taken from the official record rather than from whatever it was told in conversation.

It exists because a session previously had no reliable way to answer that question about itself, and conversational claims are not evidence.

## What it is intended to do

- **Report** who is assigned to what, and whether that assignment is currently live.
- **Refuse to guess.** If nothing is assigned, if two things match, or if the record cannot be read, it says exactly that. It never picks one and never treats an absent record as permission.
- **Report facts, not permission.** It never tells anyone they are authorised. It surfaces the facts and marks plainly what it cannot know.
- **Change nothing, ever.** It only reads.

It was also recently corrected, at your instruction, so that every answer now names **which system supplied it** — closing the transparency gap you identified at the last review.

## Independent verification

A separate verification session, not the one that built it, checked it against the **six** conditions you set. **All six passed.** We re-derived every one of them from the code and the official record rather than accepting the report, and re-ran the full test suite: **17 tests, 170 checks, all green.**

We also confirmed the builder stayed inside its authorised boundary: two files changed, and the underlying record-keeping system was left **byte-for-byte untouched**.

**No defect was found in what was built.**

## The one limitation we did find

Before a session may begin work, **two** things must have happened: the previous session must have formally passed the work over, and you must have given the go-ahead. Both must be on the record.

When the tool reports that a session is not yet cleared to begin, it lists **both** of these as outstanding.

**It can verify only one of them.**

- *"You have not yet given the go-ahead"* — the tool can always confirm this correctly.
- *"The previous session has not passed the work over"* — **the tool cannot check this at all.** It says it every time, whether or not the hand-over actually happened.

The reason is a boundary between two components. The official record-keeping system **does know** whether the hand-over happened — it uses that fact itself to allow or block a session from starting, and we demonstrated it doing so correctly. But it **does not pass that fact on** to the lookup tool. And the lookup tool is deliberately forbidden from working it out for itself: that rule exists so there is only ever **one** authority on what the record means. Reading the record independently is exactly the mistake the design was built to prevent.

So the tool is obeying its instructions correctly, and the answer is still not fully truthful.

**This has already cost us once:** a session read that line, believed the hand-over was missing, and raised a fault against a record that was in fact perfectly sound. The record was right; the report was wrong. One working cycle was lost.

**Importantly, the error only ever runs in the safe direction.** The tool may say someone is *not* ready when they are. It **cannot** say someone is ready, cleared or authorised when they are not. The worst outcome is an unnecessary pause or an unnecessary question — never someone acting without your approval.

Two further points that bound the risk today: the tool is only run **on request** — it is not yet wired into how sessions start — and it has **not yet been taken into service**.

## Why this is not a defect in what was built

The approved design set two requirements that, as things stand, **cannot both be met**:

1. Tell people **everything** that is outstanding before work may begin.
2. State **only** what the official record actually confirms.

Meeting the first means mentioning the hand-over, which the tool cannot confirm. Meeting the second means staying silent about it, which hides a genuine requirement. The design never settled which of the two should give way — the question simply never came up when it was written.

The builder took the cautious route: mention it. That is a reasonable reading, not a mistake.

**Correcting this means deciding how the official record-keeping system should share this information, or changing what the tool is asked to report.** Those are two different changes to two different components, and picking between them is an architectural decision in its own right. **We are deliberately not asking you to make that choice today** — it needs its own evidence and its own commission.

---

## Decision

Please choose one.

### **A — QUALIFY**  *(Governance recommends this)*
Accept the capability for service. The limitation above is recorded as a known architectural limitation and carried forward as a separate piece of work.

*We recommend A because: everything you asked to be verified was verified; nothing is broken; the limitation is a design question rather than a fault; it can only ever fail in the cautious direction; and it can be resolved on its own timetable without holding back a working tool.*

*If you choose A, we would attach two conditions — neither of them a technical choice: the follow-up item is opened before the qualification is recorded, and the limitation is resolved before this tool is ever wired into how sessions start automatically. That second condition matters: today the misleading line is seen occasionally by someone who asked for it; wired into startup, it would be shown to every session every time.*

### **B — AMEND**
Do not qualify yet. Commission an architectural decision first on how the official record-keeping system should expose whether work has been handed over, and qualify only once that is settled.

### **C — DECLINE**
Reject the result and require a different direction.

---

## Two housekeeping matters, whichever you choose

1. **The verification is not formally closed on the record.** The report says it finished, but the closing entry was never made. Until it is, the work cannot mechanically move to a next stage. This is a one-line correction and we have deliberately not made it ourselves.
2. **The official record is not backed up or version-controlled.** The written history around it is; the record itself is not, so it cannot be audited as it stood at any past date. This does not affect anything above, but we recommend it become its own piece of work.

---

**Nothing has been implemented, changed, taken into service, qualified or closed. We are waiting on your decision.**

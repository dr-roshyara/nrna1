# Decision Request — Start of Voting: Governance Gate

**Prepared by:** Engineering (Election lifecycle investigation, Slices A/B/C1/C2a–C2e) · **2026-09-13**
**For:** Product Owner / ARB
**Format:** Business-language decision request, per the adopted convention (see `2026-08-15-tier1-decision-request-official-election-time.md`).
**Commission scope:** the seven decisions below only. No implementation, architecture, test, or lifecycle proposal is made here. Nothing in this document is enacted by its own existence — enactment is a separate act, as with every prior decision in this program.

---

## Purpose

An engineering investigation into a reported election-lifecycle defect (an election appeared to reach voting automatically, without anyone opening it) led to two already-committed, narrow fixes, and then surfaced that a broader question this program already adopted a rule about — `EM-VOT-004`, "the Chief must explicitly decide when voting starts, a clock alone must not" — has never been implemented. Implementation was deliberately not authorized when `EM-VOT-004` was adopted (2026-08-15); it was explicitly left for a later architecture pass and a further, separate authorization.

That later architecture pass happened the same week (`2026-08-15-session4-election-lifecycle-architecture-adaptation.md`) and named a small set of business questions that must be answered before implementation can proceed. Those questions were never brought back to the Product Owner as a decision request. This document does that.

**Nothing here changes system behavior.** The system today still allows the automatic clock-driven mechanism to start voting once a grace period elapses (with one narrow correction already made — see "Already committed," below). That is unchanged by this document; only your answers to the questions below can authorize a change.

---

## Already committed (narrow, evidence-based, does not require a decision here)

Two small corrections have already been made and merged, neither of which required a new business decision — both apply rules you already adopted:

1. **Counting can no longer be derived for an election that never legitimately had voting opened** (an existing gap where an expired, never-opened voting window could still reach "Counting"). Fixed by requiring the voting-locked fact for that state, matching what "Voting Active" already required.
2. **The automatic clock-driven mechanism can no longer lock voting for an election with zero approved candidates** — this already directly applies `EM-VOT-002`'s adopted "at least one approved candidate" rule, which the automatic path had been skipping.

Neither of these implements `EM-VOT-004`. Both remain in effect regardless of what you decide below.

---

## The central issue, in plain terms

Today, if an officer turns on "automatic transitions" for an election and sets a grace period, the system can start voting on its own once that grace period passes — with nobody present to make that decision. `EM-VOT-004`, which you already adopted, says a clock must not be able to do that; only the Chief Election Officer's explicit, in-the-moment decision may start voting.

The gap between what's adopted and what the system does is real and currently unresolved — not because engineering disagrees with the adopted rule, but because implementing it correctly requires you to first answer the questions below. Answering them out of order, or letting engineering guess at them, risks either re-breaking something for existing elections or building the wrong thing cleanly.

---

## Decision 1 — Which time is the official voting schedule? (`G-1`/`G-2`)

**Why this comes first:** `EM-VOT-004` requires evaluating whether "the scheduled voting start time has been reached." Today the system keeps two different times that can each answer that question differently, and on every real election checked, they disagreed.

**The two times:**
- The time the officer entered when they scheduled voting.
- The time the system currently uses to decide whether voting has actually opened — which today gets silently overwritten to "right now" the moment anyone (or anything) opens voting.

**Question:** When we say "this election is scheduled to open voting at 10:00," which of these is the true answer?

| Option | Meaning | What it would mean in practice |
|---|---|---|
| **A** | The system's enforced time is official; the officer's entered time is a display/historical note | Matches today's behavior, but a few existing elections have no enforced time at all and could never become eligible |
| **B** | The officer's entered time is official; the system's enforced time is derived from it | Matches what officers already see and expect, but every existing election's stored time would need re-examining |
| **C** | These are two different, separately meaningful facts — "what was planned" and "what actually happened" — and both must be shown, clearly labeled, wherever either appears | Today's disagreement stops being treated as a data problem, but every screen showing a time must say which one it is |

*No option is recommended — this is a business decision about what "the official schedule" means, not a technical one.*

---

## Decision 2 — Does an undecided candidate application block voting from starting? (`G-3`)

**Context:** you've already adopted that voting can't start with zero approved candidates. This is a narrower, separate question: what if there's already one approved candidate, but another application is still sitting undecided (neither approved nor rejected)?

**Question:** Should an undecided candidacy application, by itself, prevent voting from starting — even when at least one other candidate has already been approved?

- **Yes** — an undecided application blocks progression until it's resolved one way or the other.
- **No** — only the approved-candidate count matters; undecided applications don't block anything.

**Consequence either way:** "Yes" protects an applicant whose case simply hasn't been decided yet from being shut out by a vote that starts around them. "No" means voting can proceed while someone's application is still pending a decision.

---

## Decision 3 — What may the automatic (clock-driven) mechanism still do? (`G-6`)

**This is the most consequential question in this set**, because `EM-VOT-004` already establishes that a clock may not exercise the Chief's authority to start voting — so this decision is really asking how far that exclusion reaches for the specific mechanism that exists today.

**Context:** a background process exists that, once a configured "grace period" elapses after nomination is completed, can currently lock voting open on its own, without anyone present. It was built before `EM-VOT-004` was adopted, and its authority to do this was never separately granted or reviewed at adoption time — it simply predates the rule.

**Question:** What should this mechanism be allowed to do going forward?

1. **Retain no authority to affect voting status.** It may still run for unrelated purposes (e.g., closing an already-open, already-expired voting window is a separate, already-preserved rule, not in question here), but it must stop being able to start voting on its own.
2. **Retain a narrowed, non-authoritative role** — for example, marking an election "eligible" or notifying the Chief that a decision is now due, without itself producing the fact that voting has started.
3. **Retain its current authority**, explicitly re-confirmed as an intentional exception to `EM-VOT-004`.

*No option is recommended.* Note: a related, already-committed correction (Decision-history item above) narrowed one specific defect in this mechanism (it can no longer lock voting for a zero-candidate election) — that correction stands regardless of what you decide here, and does not answer this broader question.

---

## Decision 4 — What should happen to an election whose voting window has passed but which never got any approved candidates? (`EM-OPEN-021`)

This exact question was raised and explicitly left open by the Product Owner/ARB on 2026-08-13, described at the time as "an unresolved domain decision, explicitly not an implementation defect." It is repeated here only because it sits directly adjacent to Decision 3 and may be easier to answer alongside it — it is not a new question.

**Question:** When an election's voting window has opened or passed, but it has zero approved candidates, what state should the election be shown as being in?

- Still waiting in the nomination stage (as if voting never became relevant).
- A distinct, clearly-named "cannot proceed" state, naming the missing condition.
- Some other state you specify.

---

## Decision 5 — Does completing nomination, by itself, gate the start of voting? (`EM-OPEN-025`)

**Context:** the adopted rules require at least one approved candidate and at least one admitted voter before voting starts. This question is narrower: does the nomination phase itself need to have been formally marked complete, as its own separate requirement — and does that interact with Decision 2 above?

**Question:** Is "nomination has been completed" itself a required condition for starting voting, separate from simply having an approved candidate — and if so, does an undecided candidacy count against that completion?

*This is linked to Decision 2 — you may prefer to answer them together.*

---

## Decision 6 — What does the voting-start time itself mean? (`R-3`, business half only)

This is a narrower companion to Decision 1, worth separating explicitly so the business meaning isn't silently assumed by whatever gets built:

**Question:** When the Chief acts to start voting, should the system honor the voting window the committee already scheduled, or should acting to start voting always define a brand-new window beginning at that exact moment?

- **Honor the scheduled window** — the committee's planned start/end times stand; the Chief's act only confirms/authorizes them.
- **Always start fresh** — the act of starting voting itself defines when the window begins, regardless of what was scheduled earlier.

*How this gets represented in the system, once you've answered it, is engineering's decision to make — not asked of you here.*

---

## A separate note, not a decision requested here

Engineering also flagged a technical ambiguity in one internal fact the system tracks ("voting is locked/sealed") — it currently gets set for two different reasons that mean opposite things ("the Chief opened it" vs. "the window sealed shut after expiring"), with no way to tell which one happened after the fact. This is an engineering representation problem, not a business question, and is not included in the decisions above. It will need to be resolved by engineering once Decisions 1–6 give it something solid to build on, and does not require your input beyond the decisions above.

---

## Retrospective-review question (not a decision, a flag)

One of the two already-committed corrections (item 2 under "Already committed") modified the automatic mechanism that Decision 3 above is about — before this decision request existed. It was a narrow, defensible fix on its own terms (applying a rule you'd already adopted, `EM-VOT-002`), but it touched a mechanism whose broader authority is exactly what Decision 3 asks about.

**Question, for your awareness, no action requested:** do you want that already-committed correction reviewed again once Decision 3 is answered, in case your answer implies it should have been done differently? Engineering takes no position and has made no argument either way.

---

## What happens after you decide

Your answers to Decisions 1–6 become the business basis for an architecture design (representation, exact system changes). That design will come back to you or your delegate for a **separate, explicit authorization to implement** — your answers here do not themselves authorize any code change. This mirrors exactly how `EM-VOT-002` and `EM-VOT-003` were handled: business decision first, implementation authorization as its own later act.

---

## For the record — decision log

| Decision | Your answer | Date | Notes |
|---|---|---|---|
| 1 — Official schedule (`G-1`/`G-2`) | | | |
| 2 — Undecided candidacy blocks? (`G-3`) | | | |
| 3 — Automatic mechanism's authority (`G-6`) | | | |
| 4 — Zero-candidate elapsed-window state (`EM-OPEN-021`) | | | |
| 5 — Nomination-completion gating (`EM-OPEN-025`) | | | |
| 6 — Voting-start time meaning (`R-3`, business half) | | | |
| Retrospective review of the committed correction? | | | optional; no action if left blank |

*Signature / adoption block, per the established convention:*

> *"I formally adopt the answers recorded above as the governing business decisions for the start-of-voting boundary."* — **PO/ARB, [date]**

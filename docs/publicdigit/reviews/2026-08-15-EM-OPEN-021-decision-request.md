# Decision Request — What does an election *mean* when voting opens with no approved candidate?

**Prepared by Governance (Session 2) · 2026-08-15 · Business-language format per the adopted convention · One decision. Governance recommends none of the options — the meaning is the PO's to set.**

---

## Purpose

Give a name and a meaning to a real state that an election can reach today, and currently has none.

## Why it matters — this is reachable, not theoretical

An administrator using **permitted actions only** can produce it: nomination is closed (`forceCloseNomination()` — a legitimate administrative action, and **no lock prevents it**), the voting window opens, and **no candidate was ever approved.**

What happens then, all measured:

- **Voters hitting the election get a server error** (HTTP 500) — proven, with no graceful page anywhere.
- **Administrators cannot close the election** to escape — closing was tested and **disproven** as a way out.
- **Re-approving the candidates does not work** — closing nomination **auto-rejected** them, and that destruction is what blocks recovery.
- **The condition lasts the whole voting window.** It resolves only when the window expires, at which point the election moves to counting — with nothing to count.

So the system currently answers the question *"what is this election?"* with **an error page**. That is a technical accident, not a decision. **The risk of leaving it undecided is that the accident hardens into the rule.**

## What I am being asked to decide

**Not** *"how do we fix the error."* The question is:

> **What should such an election BE?**

The engineering follows the meaning, not the other way round.

## The options — with what each would mean for a real administrator

*(Each is listed with whether the system already has something like it. **None is recommended.** Nothing here is a fix proposal.)*

| | Meaning | What it would mean in practice | Already exists in some form? |
|---|---|---|---|
| **1** | **It is still in nomination** — an open window with no candidate does not make it a voting election | The election stays where work remains; the window is treated as not yet meaningful | Yes — pre-voting states already exist |
| **2** | **It is in a defined "blocked" condition** — a named, visible abnormal state with a way out for administrators | Administrators see something explicit and can act on it deliberately | Partly — a suspended state and suspend/resume actions exist, though reaching suspension from this condition was **not** proven |
| **3** | **It is recoverable** — approving or adding a candidate while the window is open restores a normal election | The administrator repairs it in place and voting proceeds | Untested — the "add a new candidate" route was deliberately **not measured**, because its expected outcome *is* this decision |
| **4** | **The window is invalid** — a voting window without a candidate is not a legitimate voting window at all | The election is refused entry to voting and must be corrected before any window counts | Yes, halfway — the command path already refuses to open voting without a candidate; the automatic path has no way to express refusal |
| **5** | **Today's behaviour is the intended meaning** — "there is no derivable state" is a real answer, just badly presented | Same behaviour, but as a defined, graceful, explained outcome rather than a crash | The behaviour exists; the error page is not deliberate |

## What this decision does NOT authorize

Any implementation · a new lifecycle state unless the chosen meaning genuinely requires one · touching the approved-candidate rule (settled, verified) · the voting-page defect repair (A-2 — a separate, independent decision) · the admitted-voter rule's implementation.

## What happens after

Your ruling fixes the meaning. Governance then records it, identifies where it belongs, and — separately — a bounded implementation is proposed for your approval. If the meaning you choose needs one more measurement first (option 3 in particular), that verification is commissioned before any implementation.

## Technical reference

Full evidence and the same five options with their precedents: the 65/69 disposition package §4 · the runtime traces establishing reachability, the error, and the disproven escapes: Session 1's P2/P3 reports.

## Decision

**Choose the meaning** — one of the five above, a variant, or another meaning you name. *(There is no APPROVE/DECLINE here: this is not approval of a proposal, it is you setting what the system means.)*

# Tier-1 Decision Request — What is the official election time?

**Prepared by Governance (Session 2) · 2026-08-15 · Business-language format per the adopted convention.**
**Two decisions: `G-1` and `G-2`. Governance recommends neither answer — both are the Product Owner's to set.**
**Commission scope, as authorized:** `G-1` and `G-2` **only**. `G-3`…`G-8` are **not** answered here. No implementation, architecture, test or lifecycle proposal is made.

---

## Purpose

`EM-VOT-004` — adopted 2026-08-15 — makes this a mandatory condition for starting voting:

> *"the scheduled voting start time has been reached"*

**The system cannot currently tell whether that condition is true**, for two independent reasons. Until both are settled, the adopted rule cannot be enforced, and an authorization mechanism built on top of it would be correct in form and wrong in fact.

**This is also live customer harm, independent of the new rule.** It is not a theoretical prerequisite.

---

## Why it matters — both problems are measured, not suspected

### An election opened two hours after the officer scheduled it

`test election 1` was created with the election's timezone explicitly set to Berlin. The officer entered a **79-minute** voting window. Measured outcome:

| | |
|---|---|
| Officer entered | 13:10 – 14:29 |
| System will open voting at | **15:10 – 16:29 Berlin** |
| Consequence | **The entire advertised window elapses before voting opens.** Voters arriving when told to vote find voting closed |

The drift is **60 minutes in winter and 120 in summer** — so an officer who learns to compensate in August is wrong in January. **No mental workaround is reliable.**

### The record disagrees with the officer about when voting ended

For `namaste 2026`, the officer's stated close was 18:00 Berlin. One record agrees. The other says voting ended **31 minutes earlier** — because that second record was **overwritten by the act of closing** rather than describing the schedule at all.

> **So today the system holds two different answers to "when is this election's voting period", and on the four real elections examined, all four disagree.**

---

## Decision `G-1` — Which record is the official published schedule?

**In business terms:** when we say *"this election is scheduled to open at 10:00"*, **which recorded time is that?**

Two sets of times exist. One is what the officer set and what most screens show. The other is what the system actually enforces — and it is **overwritten when voting is opened or closed**, so it ends up describing *what happened* rather than *what was planned*.

| | Answer | What it would mean in practice |
|---|---|---|
| **A** | **The enforced times are official.** The times the officer set become a display or historical leftover | Matches how the system behaves today — **but three demo elections have no enforced times at all and so can never reach voting**, and they would need to be given some |
| **B** | **The times the officer set are official.** The enforced times are derived from them | Matches what the officer intended and what most screens already show — **and would change what the system enforces**, so every existing election's window must be re-examined |
| **C** | **They are two different things** — one is the *published schedule*, the other is the *record of what actually happened* | Then today's disagreement is **not a fault**; both must be named and explained, and **every screen must make clear which one it is showing or editing** |

**None is recommended.** Recorded for completeness: **`C` is the only answer under which the present data is not evidence of a defect** — that is a statement about the evidence, not a preference between the options.

### A constraint any answer must satisfy — stated because the adopted rule creates it

`EM-VOT-004` distinguishes *"the scheduled start"* from the officer's act of proceeding. **The business therefore needs to be able to say both "when was this planned" and "when did it actually begin", whichever option is chosen.** Under `A` or `B` that means the official record must stop being overwritten by the acts; under `C` it means both must be named. **This constrains the work each option implies; it does not favour any of them.**

### Sub-question — 🆕 newly surfaced by this commission, not registered on the ticket

`EM-GOV-004` allows the Chief to **correct** a schedule. Corrections create **revisions**.

> **When a schedule has been corrected, which revision is "the schedule" — for the purpose of saying the scheduled start has been reached?**

Not previously asked, because before `EM-GOV-004` corrections were not a governed act. **It needs an answer under any of A, B or C.**

---

## Decision `G-2` — What does the time the officer enters mean?

**In business terms:** the Chief types **10:00** into the schedule. **10:00 where?**

Today the system stores that as **10:00 UTC** regardless of where the officer is or what timezone the election declares. Nobody decided this; it is a consequence of how the value is handled, and it is the direct cause of the two-hour drift above.

| | Answer | What it would mean in practice |
|---|---|---|
| **A** | **The officer's own local time**, as their device reports it | Consistent with the already-accepted decision on how times are *displayed*. An officer in Munich scheduling a Kathmandu election gets **Munich** time |
| **B** | **The election's own timezone** | The election owns its schedule. Requires every election to carry a timezone — **today only 2 of 10 do**, and the check that was supposed to require one never runs on the live path |
| **C** | **The organisation's timezone** | The organisation owns time. **Currently unusable as evidence** — all six organisations hold the value the system filled in by default, so no organisation has actually chosen one |
| **D** | **UTC, stated plainly in the interface** | The product declares itself UTC-native and converts nowhere. Cheapest — and **contradicts the accepted decision on display** |

**None is recommended.** Note that **`A` and `B` disagree precisely when an officer is travelling or is in a different country from the election** — that case is where the answer actually matters.

### Sub-questions that `G-2` must also settle

| | Question | Status |
|---|---|---|
| **a** | Is the entered value a **civil ("wall clock") time** — *10:00 in some place* — or an **absolute instant**? | Registered in substance |
| **b** | **Which place or timezone governs an election**, and who sets it? | Registered |
| **c** | **How do daylight-saving changes apply**, given the same entered value means different instants in summer and winter? | Registered as an effect; **not decided** |
| **d** | 🆕 **What happens when a local time is ambiguous or does not exist?** On the clocks-forward night some times never occur; on clocks-back night some occur twice. **A voting window can be scheduled across exactly those moments.** | **Newly surfaced by this commission.** Not on the ticket |
| **e** | **What should happen when the officer's location cannot be determined?** | Registered as required, still undecided |

---

## The two decisions are coupled — please take `G-1` first

`G-2` asks how to interpret a value; `G-1` asks which record holds it. **Answering `G-2` first risks deciding the meaning of the wrong field.**

```
G-1  which record is the official schedule
        │
        ▼
G-2  what the value in that record means
        │
        ▼
"the scheduled start has been reached"  becomes answerable
        │
        ▼
EM-VOT-004 becomes enforceable
```

---

## What must NOT be inferred from an answer here

Recorded so the scope cannot quietly widen:

- **Existing values must not be migrated on the strength of these rulings alone.** The stored times **contradict themselves** — two real elections declare Berlin intent beside a value that was treated as UTC, while five test elections are known-UTC. **A blanket shift would corrupt the ones that are already right.** What existing values mean is its own question.
- **No answer here decides** whether a pending candidacy blocks voting (`G-3`), the limits on schedule correction (`G-4`, `G-5`, `G-5a`), what the automatic-transition job may still do (`G-6`), counting conditions (`G-7`), or the zero-candidate question (`G-8`).
- **No answer here authorizes implementation.** Session 3 remains stopped.
- **The convenience of an existing field, code path or historical behaviour is not a reason to choose an option.** The implementation must conform to the ruling, never supply it.

## Where the evidence does not justify a single ruling

Stated plainly rather than papered over:

- **`G-2` option `C` cannot be assessed on current evidence** — no organisation has made an actual timezone choice, so there is nothing to observe.
- **What the officer *intended* on the two real elections is strongly supported but not proven.** The rows carry a deliberately-set Berlin timezone beside times treated as UTC; the inference is reasonable and remains an inference.
- **Sub-questions `G-1`(revisions) and `G-2`(d) have no registered options at all** — they are surfaced here for the first time and may need the PO to formulate the answer rather than pick from a list.

---

**Traceability:** `PBDIGIT-59` (which timestamps are constitutional) · `PBDIGIT-67` (entered local time stored as UTC) · `PBDIGIT-50` (display decision — **not reopened**) · `EM-VOT-004`, `EM-GOV-004` (`3d31df2b`) · Architecture Adaptation Report `2026-08-15-session4-election-lifecycle-architecture-adaptation.md` §6, §19 Tier 1 · measured evidence `2026-08-12-time-value-trace-browser-to-display.md`.

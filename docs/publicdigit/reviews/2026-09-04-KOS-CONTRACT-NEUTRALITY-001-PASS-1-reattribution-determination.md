# `KOS-CONTRACT-NEUTRALITY-001` — Pass-1 re-attribution: **THE MODEL DOES NOT PERMIT IT** · returned to the PO/ARB

**Work item:** `KOS-CONTRACT-NEUTRALITY-001` · **Date:** 2026-09-04
**Recorded by:** Governance — `claude-code-session:5928b9f9-b4d5-46e9-8c71-c295dace18f8` *(governance-recording; holds no lane here)*
**Question put by the PO/ARB:** *"Register the fresh session as the current performer/process for the already-authorized `S4` lane, **if the governance model permits that** … If the canonical mechanism instead requires creating a new lane or changing the authorized performer in a way that alters the original grant, **stop and bring that back to you for a separate governance decision.**"*

> ### ⛔ **ANSWER: THE MODEL DOES NOT PERMIT IT.** The conditional failed, so this is the **stop-and-return** branch. **No `REGISTER` was written.** Pass 1 remains authorized and unstarted.

---

## 1 · The determination, with its evidence

**A fresh process cannot be attributed to the existing `S4-architecture-v3-determination` lane.** Three independent mechanism facts, each read at source:

1. **`REGISTER` refuses a lane that already exists.** `workflow-state.php:189-194` — *"session assignment already registered — role is immutable; a role change is a new assignment (`R8`)"*. There is no second `REGISTER` onto an existing key.
2. **The transition vocabulary contains no re-attribution edge.** The complete set is `REGISTER` · `HANDOFF` · `START` · `CONTINUATION` · `STOP` · `COMPLETE` · `FAIL` · `CANCEL`. **None of them changes who a lane is attributed to.** The machine has no such edge, by design (`R1`: no `CLAIM_OWNERSHIP`).
3. **Attribution lives in immutable text.** A lane's process attribution is carried in its `executionContext`, which sits inside the `seq 38` `REGISTER` transition. `G-KOS-CONTRACT-V3-ARCH-AMD1` states the point itself: *"the assignment's executionContext lives inside the seq-38 REGISTER transition and is **IMMUTABLE BY CONSTRUCTION** — AST-015 refuses…"*. That is precisely why that amendment was registered as a **grant** amendment rather than an edit.

**So the only governed path is a NEW lane** — which changes the performer the grant names, which is the condition the PO/ARB reserved to themselves.

## 2 · A distinction that makes the options better than they first look

Lanes on **this** work item are keyed by **descriptive assignment names** (`S4-architecture-v3-determination`, `S1-verification-track1-php-adapter`), **not** by session UUIDs — unlike `KOS-OPERATING-MODEL-001-AMENDMENT-001`, whose lanes are keyed by process ids.

**Consequence:** here, a "lane" is an **assignment slot**, not a process identity. A new lane can therefore be created **for Pass 1 specifically** — leaving the V-3 assignment untouched and still bounded to its two questions. The grant's **scope** would not change at all; only the **named performer** clause would.

## 3 · ⚠️ The cost the PO/ARB should know before choosing — one mutation owner at a time

**Activating a new Pass-1 lane would take mutation ownership away from the still-open V-3 lane.**

`HANDOFF` requires `from === mutationOwner` (`Inv C`), and `START` requires a recorded handoff to the starting session (`Inv F`/`G-3`). The current mutation owner **is** `S4-architecture-v3-determination`, which is `ACTIVE` and has never closed its two-question determination. So bringing a Pass-1 lane to `ACTIVE` necessarily moves ownership off the V-3 lane, marking it `HANDED_OFF`.

**Pass 1 and the V-3 determination cannot both hold this work item at the same time.**

This is exactly why **Option C worked**: one actor holding one lane under two grants needed no second lane and no ownership transfer. Losing that actor is what turned a free choice into a costly one. **This is a consequence to weigh, not an argument against any option.**

## 4 · The fresh performer, and that it behaved correctly

`claude-code-session:e8f324f1-25ea-4281-a95a-483401312e3e` attempted onboarding, ran this repo's own read-only resolver against itself rather than trusting its brief, got **`UNRESOLVED`**, and **stopped** — writing a document and **no transition**. The workflow record is still at **40 transitions**, which is the correct outcome: it had no authority to write one.

**Its declared identity is on record and is what any future `REGISTER` would name.** Governance has verified only that the document exists and reports a stop; it has **not** assessed that process's independence, because no appointment is on the table until the PO/ARB decides §5.

**One substantive fact it surfaced, reported here because it narrows Pass 1 and was independently obtained:** the `v3-decisions-registration` states that *"the delivered V-3 architecture determination is **neither accepted nor amended** here."* If that holds on reading, **neither V-3 determination has been accepted** — which is consistent with what Governance recorded on 2026-08-24 as `UNKNOWN`. **This is reported as evidence, not adjudicated**; confirming it is Pass 1's work.

## 5 · The decision, returned to the PO/ARB

Each option is a human authorization act. **Governance recommends none and has written nothing.**

| | Option | Consequence |
|---|---|---|
| **1** | **New Pass-1 lane, performer `e8f324f1`, with a grant amendment naming it** — the V-3 *assignment* and its grants stay untouched and unchanged in scope | Pass 1 proceeds now. **Cost:** the V-3 lane yields mutation ownership and goes `HANDED_OFF` while still open on its two questions. |
| **2** | **Close the V-3 lane first** (`STOP`/`COMPLETE`), then create the Pass-1 lane | Cleanest ownership story. **Cost:** requires deciding the V-3 lane's disposition, which is a substantive question nobody has been asked to answer. |
| **3** | **Recover the original `S4` process** | No governance change at all. **Cost:** unlikely after 11 days, and unverifiable from here. |
| **4** | **Leave Pass 1 authorized and idle** | No cost, no progress. |

**If option 1 or 2 is chosen, the appointment itself is straightforward** and follows the path already proven on this repo: the declared identity is appointed through the canonical mechanism, never by hand-composed transitions, and the human `START` is a recorded act.

**Nobody has touched the V-3 substantive question, and nothing in this record does either.**

## 6 · Non-actions

No `REGISTER` · no `HANDOFF` · no `START` · no lane · no grant · no grant amendment · **no transition (still 40)** · V-3 grants and lane unchanged · Pass-1 grant unchanged and still `AUTHORIZED` · no Pass-1 work · no V-3 adjudication · nothing in the do-not-modify list touched · the fresh performer's stop document not altered.

**Traceability:** PO/ARB direction 2026-09-04 · fresh performer's stop record `2026-09-04-…-PASS-1-fresh-performer-registration-attempt-STOP.md` · `workflow-state.php:189-194` (`R8`), transition vocabulary, `Inv C`/`Inv F` · `G-KOS-CONTRACT-V3-ARCH-AMD1` (immutability of `seq 38`) · Pass-1 authorization `2026-08-24-…-PASS-1-AUTHORIZATION.md` · authority verification `2026-08-24-…-PASS-1-performer-authority-verification.md` · `EKS-07`

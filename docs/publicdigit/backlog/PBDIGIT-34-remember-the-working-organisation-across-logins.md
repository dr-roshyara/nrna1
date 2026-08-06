# PBDIGIT-34 — Should the platform remember the Working Organisation across logins?

**Type:** Product enhancement — **question, not requirement** · **Epic:** `PBDIGIT-EPIC-01` Organisation Management
**Created:** 2026-08-06 · **Origin:** withdrawn from `PBDIGIT-30` §B4

| | |
|---|---|
| **Status** | **OPEN — unprioritised** |
| **Blocks** | **nothing** |
| **Blocked by** | nothing — but **should not be considered** until B1–B3 are implemented and verified (`PBDIGIT-32`, `PBDIGIT-00`) |
| **Nature** | **A capability the product does not have and has never been asked for.** Not a defect, not a gap in an approved rule |

---

## Why this is a separate story

`PBDIGIT-30` discovered the business rules the Working Organisation concept **already implies** — identity (B1), lifecycle (B2), authority (B3), and the election-day exception (B10).

Cross-login memory is different: **nothing in the approved rules requires it.** B4 began as discovery and drifted into design, inventing requirements nobody had asked for — no expiry · per person rather than per device · memory carrying its reason across logout. Each is defensible; **none was discovered.** So it was withdrawn and moved here.

> **A Product Owner should never be obliged to invent features because engineering found a gap.**

## The question — genuinely open

> **Should a returning user be resumed into the organisation they last worked in?**

**No answer is assumed.** Reasonable products differ:

| Possible answer | Would suit |
|---|---|
| Remember **forever**, until changed or invalid | users who work in one organisation for months |
| Remember **for a period** (30 days, a week) | shared or occasional-access contexts |
| Remember **until logout only** | high-sensitivity or shared-device use |
| **Never** remember — always apply B1.3 | maximum explicitness; the user always confirms |

**All four are compatible with B1–B3.** Choosing between them requires knowing how customers actually work, which has not been established.

## Secondary questions — only if the answer is "yes"

* For how long, and what ends the memory besides a change or invalidity?
* Per **person**, or per **device/browser**? *(Enterprise products deliberately differ; neither is inherently right.)*
* What happens across **concurrent sessions** if the user switches in one place?
* Must the memory carry **why** the organisation was established (`PBDIGIT-30` B3 traceability), and does that obligation survive logout?

**None of these should be answered before the primary question is.**

## The one piece of reasoning worth keeping

From the withdrawn draft, retained as reasoning — **not as a rule**:

> **Remembering is not guessing.**

If the platform is ever asked to resume a previous choice, this resolves the apparent tension between **B1.3** (*ask when there are several*) and **B1.4** (*never guess*): B1.4 forbids the platform from **choosing among alternatives**; reproducing the user's **own** explicit decision is the opposite of a guess. *(Same shape as B3's "determinism is not discretion".)*

**It is recorded because the argument is sound, not because the feature is wanted.**

## Observation from `PBDIGIT-29` — context, not justification

The four stores that hold organisation context all hold *an* organisation; **none records the user's *choice* as such.** So today the platform could not distinguish *"never chose"* from *"chose, and we forgot"*.

**Stated as an observation, not an argument for building this.** If the answer to the primary question is *"never remember"*, the observation becomes irrelevant rather than a debt.

## What must be true before this story is considered

* [ ] B1–B3 (+B10) implemented — `PBDIGIT-32`
* [ ] The journey verified at runtime — `PBDIGIT-00`
* [ ] Evidence about how customers actually work — *not yet collected*

---

**Traceability:** `PBDIGIT-30` §B4 (withdrawn draft + reason) · `PBDIGIT-30` B1.3 · B1.4 · B1.5 · B3 traceability · `PBDIGIT-29` Q2 (four stores) · `PBDIGIT-31` BR-4 (the 300 s routing-decision cache — a mechanism artefact, not a remembered choice)

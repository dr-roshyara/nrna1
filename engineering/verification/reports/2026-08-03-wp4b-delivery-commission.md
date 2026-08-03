# Implementation Delivery Commission — WP-4B

**Date:** 2026-08-03 · **Prepared by:** Recording Architect
**Question:** **what is the smallest implementation change that satisfies the already-adopted governing model?**
**Framing:** delivery, not architecture. **No architecture selected · no ADR reinterpreted · no option-selection exercise reopened (R-78).**

---

## 1. Executive Summary

> ### The adopted model names its own precondition. It is not an amendment and not a redesign.
>
> **ADR-PL-01 — the same accepted artifact that classifies `ChallengeRaised` internal — states the mechanism for changing that classification:**
>
> > *"An internal domain event **may evolve in place until explicitly PROMOTED to a published integration event**; only then does the version-never-mutate rule bind it."*
>
> **So publication of `ChallengeRaised` is not prohibited by accepted architecture. It is CONDITIONED on an explicit promotion — a step the accepted architecture already anticipates and names.**

**My earlier report said the path was *"prohibited."* That was too strong, and the correction is in ADR-PL-01's own sentence.** **What is prohibited is publishing it *without* promotion — which is exactly what WP-5's keystone was written to prevent.**

## 2. What each guard actually forbids

| Guard | What it forbids | Does an explicit promotion satisfy it? |
|---|---|---|
| **ADR-UL-01 / ADR-PL-01** — *"internal domain event"* | treating it as published **while it is classified internal** | ✅ **yes — ADR-PL-01 supplies the promotion clause itself** |
| **WP-5 plan** — *"intentionally unpublished… so a future engineer cannot **silence an exception by adding a mapping**"* | a **silent, unauthorized** mapping | ✅ **yes — an authorized promotion is the opposite of silencing.** The guard protects against undeclared change, not against declared change |
| **`ChallengeRaisePathTest.php:153`** — *"routing must write exactly one outbox…"* | a **second outbox row appearing unannounced** | ⚠️ **the test would change.** Under promotion that is an **authorized amendment to an accepted keystone**, recorded as such — not a silenced assertion |
| **ADR-T5** — version, never mutate | mutating a **published** contract | ✅ **not engaged.** Promotion makes it published *going forward*; there is no prior published version to break |
| **ADR-T16 · TP-1** | importing another context's types · non-event collaboration | ✅ **satisfied** — an event crossing with primitives is exactly the sanctioned form |

## 3. The smallest conforming change

**Stated as delivery scope, in dependency order. This is what the adopted model requires, not a proposal among alternatives:**

| # | Change | Layer |
|---|---|---|
| **1** | **The promotion act** — `ChallengeRaised` reclassified from *internal domain event* to *published integration event* | **governance** — ADR-PL-01's clause requires it to be **explicit** |
| **2** | `ChallengeOutboxAdapter` gains a `writeRaised()` mapping (payload: `challengeId · contestedOutcome{electionId,type,targetId} · occurredAt`, `schema_version 1`) | Contestation Infrastructure |
| **3** | `ChallengeRaisedHydrator` + registration — **publication and registration together, per the WP-3A precedent** | Contestation Infrastructure |
| **4** | Canonical catalog: `ChallengeRaised`'s row gains published status | documentation governance |
| **5** | Adjudication consumes it and **retains the ref on the process record** so issuance can read it | Adjudication Application |
| **6** | `ChallengeRaisePathTest` amended — **one authorized keystone change, recorded** | Contestation tests |
| **7** | **Then** WP-4B: the conclude→issue seam, with the command now constructible | Adjudication Application |

**Items 2–5 are the pattern WP-3A executed once and WP-4A consumed once. Nothing here is new architecture.**

## 4. What this commission does not do

**It does not select among the four candidate paths** — R-75 already did that, and R-78 forbids reopening it. **It reports what R-75's adopted path requires in order to be delivered.**

**It does not perform the promotion.** **ADR-PL-01 requires that act to be explicit, and an explicit act is a governance act.**

**It does not allocate items 1–6 to a work package.** **§WP-3's scope was `ChallengeRouted`'s publication; §WP-4B's is the seam. Items 2–6 belong to neither as recorded** — an allocation the Board owns.

## 5. Correction carried forward

**Two of my earlier statements are narrowed by ADR-PL-01's promotion clause:**

| Was | Now |
|---|---|
| *"R-75's adopted path is **prohibited** by accepted architecture"* | **conditioned on an explicit promotion the accepted architecture itself provides for** |
| *"implementing it would require **amending two ADRs** and **overturning** an accepted keystone"* | **no ADR requires amendment** — ADR-PL-01 anticipates promotion. **One accepted keystone requires an authorized amendment**, which is a recorded act rather than an overturning |

**The `Architecture_Release_1.1` review question (R-77, open) is untouched by this and remains open.**

---

**Traceability:** **R-72 · R-73–R-76 · R-77 · R-78** · `docs/adr/ADR-PL-01-DeterminationIssued-v2.md` (**the promotion clause**) · `docs/adr/ADR-UL-01-ContestedOutcome.md` · `docs/adr/ADR-T-LOG-Tactical-Implementation.md` **ADR-T5 · T16** · `.claude/plans/WP-5-raise-path.md` · `tests/Feature/Contexts/Contestation/ChallengeRaisePathTest.php:153` · `app/Contexts/Contestation/Infrastructure/Outbox/ChallengeOutboxAdapter.php` · `docs/implementation/Canonical_Event_Catalog_v1.0.md` · `engineering/verification/reports/2026-08-03-contestedoutcomeref-completeness-and-allocation.md` (**narrowed by §5**). **Delivery scope only — no architecture selected, no promotion performed, no work package allocated, no code written.**

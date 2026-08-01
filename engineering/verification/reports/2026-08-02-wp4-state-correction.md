# ⛔ WP-4 — State Correction

**Date:** 2026-08-02 · **Prepared by:** Recording Architect
**Trigger:** the ARB instruction to complete WP-3A's Definition of Done. Reading WP-3A's implementation surfaced this.
**Effect:** **the WP-4 Authorization Commission asks a question the programme has already passed.**

---

> # FINDING — **WP-4 is not awaiting authorization. It is implemented, gated, and awaiting slice acceptance.**
>
> **`c3165ee88` (2026-07-31) — `feat(adjudication): WP-4 GREEN -- Adjudication consumes ChallengeRouted, loop head fires`.**
>
> **`ChallengeRoutedReactionHandler` exists in `app/Contexts/Adjudication/Application/` and is registered in `AdjudicationServiceProvider::boot()`.** The correction loop's head is wired **in the repository, today**.

---

## 1. What the record actually shows

| Act | Recorded |
|---|---|
| WP-4 opened, **G-2 decided by analysis** | 2026-07-31 · session log · plan §G-2 |
| **RED written and confirmed** | 2026-07-31 · plan §"WP-4 RED WRITTEN + CONFIRMED" |
| **ARB accepted RED and authorized GREEN** | 2026-07-31 · session log: *"ARB accepted RED and authorized GREEN"*, with a precision correction adopted verbatim |
| **GREEN complete — first run, no iteration** | `Tests: 6, Assertions: 10, OK` |
| **Gates** | PHPStan max **no errors** · Deptrac **0 violations** · Architecture suite **146/626** |
| **Developer guide (DoD)** | `developer_guide/adjudication/04_challenge_routed_consumption.md` + index |
| **Post-implementation contract validation** (ARB commission) | 2026-07-31 · produced finding **D-1** |
| **Status** | **⏳ slice acceptance (STOP)** |

**Confirmed independently today: `composer merge-gate` → PASS · 266 tests · 665 assertions · 101 pre-existing risky notices.**

## 2. How the commission got it wrong — my error, stated plainly

**The WP-4 plan's status header, line 3, still reads:**

```
**Created:** 2026-07-31 · **Status:** OPEN — G-2 DECIDED (below); next step is RED
```

**I quoted that line into the commission as evidence that no authorization exists — and never read the plan body, where RED acceptance, GREEN completion and the gates are all recorded.** `.claude/CONTEXT.md`'s status table repeats the same stale word, *"WP-4 open"*.

> **This is the ninth recorded occurrence of one pattern: reading the artifact I already knew instead of the one underneath it.** WP-8's definition was in the roadmap the plan cited. WP-4's true state was in the plan whose header I quoted. **Both times the evidence was one level below the line I read.**

## 3. The scope point that follows from it

**The commission's §2 reproduced roadmap §WP-4's four scope items as though all four were pending. Three of them were never in the executed slice, and were deferred *before* RED with a recorded reason:**

| Roadmap §WP-4 item | Actual state |
|---|---|
| `(Adjudication, ChallengeRouted)` inbox handler + registry | ✅ **implemented** — `c3165ee88` |
| crash-safe conclude→issue seam | ⏸️ **deferred to its own slice** — depends on the authority-decision intake, *"a recorded external outside this authorization"* |
| `AdjudicationFailureDeclared` event + counterpart + hydrator + catalog entry | ⏸️ **deferred, same dependency.** No file of that name exists in the repository |
| authority-decision intake port + interim adapter | ⏸️ **the recorded external itself** |

**The deferral was flagged in the plan before RED — *"flagged now rather than discovered mid-slice"* — so this is disciplined scoping that the commission failed to read, not scope silently dropped.**

## 4. What is actually true, and what is actually missing

| | WP-3A | WP-4 (as executed) |
|---|---|---|
| RED | ✅ | ✅ **accepted by ARB** |
| GREEN | ✅ 11/11 | ✅ 6 tests / 10 assertions |
| `composer merge-gate` | ✅ | ✅ |
| Developer guide | ✅ | ✅ |
| **Triple qualification** | ⛔ **absent** | ⛔ **absent** |
| **Acceptance review artifact** | ⛔ **absent** | ⛔ **absent** |
| **Ruling in the register** | ⛔ **absent** | ⛔ **absent** |
| **Status** | awaiting acceptance | awaiting acceptance |

> **The two packages are in the same state, with the same two gaps, for the same reason.** **The programme's actual backlog is not one authorization — it is two acceptances.**

**On authorization: the register has no WP-4 row, but the session log records the ARB accepting RED and authorizing GREEN.** **Absence from the register is a recording gap, not absence of an ARB act** — and it is the same class of defect R-62 corrected for R-43 and R-48.

## 5. Consequence for R-67

**R-67 was reserved for *WP-4 authorization*. Authorization is moot — the work is built.**

**The instrument the programme needs is an acceptance, and acceptance requires the two artifacts neither package has.** **What R-67 should say is the ARB's to decide; that it cannot say "authorized to implement" is a matter of fact.**

## 6. Recommendation

**Nothing about the corrected sequence is architectural. It is the same Definition-of-Done gap, twice:**

1. **Triple qualification** — Architecture · DDD · Trustworthiness — for **WP-3A and WP-4**, per the WP-1/WP-2 precedent (WP-2 plan §15).
2. **Acceptance review** for each.
3. **Dispose of the frozen-catalog discrepancy** (`ChallengeRouted` still marked `internal` in the FROZEN `Canonical_Event_Catalog_v1.0.md`) — material or deferred, the ARB's call.
4. **Then two acceptance rulings**, in dependency order: WP-3A, then WP-4.
5. **Reconcile the stale artifacts** — the WP-4 plan header and CONTEXT's *"WP-4 open"* — per ES-004.3.

**No architectural act is required. No new work package is proposed. No ruling is issued here.**

---

**Traceability:** `c3165ee88` · `.claude/plans/WP-4-apm-wiring.md` §G-2, §RED, §"WP-4 GREEN COMPLETE" · `.claude/plans/WP-3-challengerouted-published-language.md` · `.claude/sessions/2026-07-31.md` · `docs/implementation/EPIC-004_Architecture_to_Implementation_Roadmap.md` §WP-4 · `engineering/verification/commissions/2026-08-02-wp4-authorization-commission.md` (**corrected by this report**) · **R-62** (register-recording precedent) · `composer merge-gate` PASS 2026-08-02. **No implementation · no architecture changed · no ruling minted.**

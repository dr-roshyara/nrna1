# PublicDigit — The Remaining TODOs

**Date:** 2026-07-10 18:52 · **Derived from:** git log (`feature/pb003`), session logs 07-08..07-10, EPIC-001 board, BACKLOG, PROGRAM_STATUS, PB-00x IDDs, `app/Contexts/` tree, ADR-AIP-LOG (R-37) — not hand-estimated except where marked *(est.)*.
**Companion:** `20260710_1849_how_far_we_are.md` (the three-project separation + realistic product assessment). This document is the actionable complement: **what exactly is left, in order.**

> ⚠️ **The tracking boards are stale.** `BACKLOG.md` (07-06), `PROGRAM_STATUS.md` (07-06) and `EPIC-001` board (07-07) still show PB-004 at 0% and PB-007 unstarted. The session logs and commits show reality far ahead (PB-004 Step 4B done, PB-005 5D done, PB-006 6B-1 done, PB-007 7C done). Housekeeping TODO H-1 fixes this first.

---

## 0. Verified current state (from logs/commits, 2026-07-10)

| Ticket | Verified state | Evidence |
|---|---|---|
| PB-001/002/003 | ✅ Verified/Certified | EPIC-001 board + DoD evidence |
| PB-004 Election Reaction | Steps 1–3 ✔ (Step 3 GREEN, ARB round-3) · 4A temporal ✔ · 4A.3 infra ✔ · 4B messaging ✔ — **Step 4C (Architecture Qualification + Completion Review) is the open closing step** | session 07-08 §Step 4B ("Next authorized: Step 4C — closes PB-004") |
| PB-005 Contestation Reaction | 5A/5B/5C ✔ · **5D qualification executed — closure pending ARB acceptance** | session 07-09 (236✔ regression; two ARB questions answered) |
| PB-006 Integration Validation | Discovery found the missing outbox→inbox bridge · 6A `IntegrationEventDispatcher` ✔ (ADR-MP-06 Accepted) · 6B-1 correlation chain ✔ (F-PB006-2: outbox migration + producer stamping) — **6B-2 (IT-1..IT-8) + 6C qualification remain** | sessions 07-09/07-10; ADR-MP-06 in `docs/adr/README.md` |
| PB-007 Merge Gate | IDD **FROZEN** (ARB 10/10) · 7A Deptrac ✔ (report mode, 0 violations, 440 allowed/90 uncovered) · 7B CorrelationId fitness ✔ · 7C Infection baseline ✔ (**MSI 75 · MCC 78 · Test Strength 96 · 892 mutants**, ARB-accepted) — **7D + 7E remain** | session 07-10 + commits `18dac93ff`/`baaded576`/`c3104b644` |
| Engineering Platform | Baseline v1.0 frozen (R-37 + burden of proof) · EM-001 executed (`engineering/` namespace) — **C3/OQ-1 cold-boot qualification NOT yet run** | ADR-AIP-LOG R-30..R-37; `engineering/MIGRATION_REPORT.md` |

Note the healthy deviation from the strict PB-004→005→006→007 chain: the closing/qualification steps of 004/005 are still open while 006/007 advanced. **Closing them is cheap and unblocks everything.**

---

## 1. TRACK A — Finish EPIC-001 (the correction loop) — days, not weeks

Ordered; each item is small because the hard work is done.

- [ ] **A-1 · PB-004 Step 4C** — Architecture Qualification: extend `GreenfieldCoreArchitectureTest` to Election as a complete hexagonal context · full regression · dev-guide finalization · EP-02 Completion Review → **PB-004 CLOSED**. *(Confirm first whether 4C already ran in an unlogged session — check `GreenfieldCoreArchitectureTest` for Election coverage before starting.)*
- [ ] **A-2 · PB-005 closure** — present 5D evidence, obtain ARB acceptance → **PB-005 CLOSED** (all implementation + qualification already executed).
- [ ] **A-3 · PB-006 6B-2** — IT-1..IT-8 end-to-end over the REAL delivery path (dispatcher, not hand-stitched): cross-context flow · replay · parking/redrive · idempotency · IT-8 correlation chain (uses the F-PB006-2 stamped columns).
- [ ] **A-4 · PB-006 6C** — Architecture + DDD + Trustworthiness qualification + EP-02 → **PB-006 CLOSED**.
- [ ] **A-5 · PB-007 7D** — stable gate entry points: `composer merge-gate` (blocking tier — includes the **Deptrac fail-mode flip**, a governance change pre-approved at 7A acceptance) + `composer quality-gate` (two-step Infection invocation per the 7C-proven sequence). Single entry point also fixes the recorded cross-session DB-collision race.
- [ ] **A-6 · PB-007 7E** — `greenfield-merge-gate.yml` CI workflow → triple qualification → EP-02 → **PB-007 CLOSED = M3 complete = EPIC-001 complete**.

**Exit state:** the constitutional correction loop (`ChallengeRaised → … → ChallengeResolved`) fully built, integration-proven on the real messaging path, guarded by a blocking merge gate with a mutation baseline.

---

## 2. TRACK B — Engineering Platform qualification (parallel, small, frozen scope)

- [ ] **B-1 · C3 / OQ-1 — cold-boot qualification.** MUST run in a **fresh session** (execution-integrity clause: no warm session qualifies). Scope frozen: implement AST-010 `run-gates.sh` exactly as registered (greenfield PHPStan → capture → PASS/FAIL stop-on-fail → Architecture suite; measuring instrument, NO interpretation) · falsifiability check · EP-02 · registry `planned→adopted`, CMP-005 0.1→1.0 · STOP. Plan: `.claude/plans/AIP-iteration-1-construction.md`.
- [ ] **B-2 · PB-004-as-workload evidence** — capture RED/GREEN gate evidence via AST-010 during remaining Track A steps (fills the Platform Value ledger rows).
- [ ] **B-3 · The retrospective** (after EPIC-001 closes) — the most loaded decision point of the program. Inbox (all Class A/B, decided here and only here): coupled tree decision (capability layer + Platform≙Adoption + DDD-aggregate folders + lifecycle categories — **one decision, not four reorgs**) · four-way dossier split · EPC priorities (001 · 002 · 011 · 012 · 014 · 017) · F1–F4 + PI-1 findings (F3 Standard-promotion event = HIGH) · F-7C-1..6 debt · metrics-as-evidence pipeline (phpmetrics; disposition attached) · dashboard (generated-only) · rename bundle (R-35 "PublicDigit EP" vs "Evidence-Driven EP" — decide once) · Engineering Standards doc (R-32) · v1.1 process ratification · deletion goal (remove ≥1 thing) · "what surprised us?"
- [ ] **B-4 · C2 cleanup** (post-qualification) — likely shrinks to unwire + deprecate AST-008 (already scheduled: remove next release, R-36).

**Standing constraint (R-37 + Principal Architect instruction, until B-3):** no architectural expansion; every change produces or consumes executable evidence; `engineering/` accepts only bugfix/link/typo.

---

## 3. TRACK C — The actual remaining product (the big one)

This is the honest gap from `20260710_1849`: the correction loop is one capability of a constitutional election platform. Everything below is **architected but unimplemented** in the greenfield. Epics EPIC-002..005 exist in the backlog but are unopened; the rest is not yet ticketed at all.

**Sequencing rule (ARB, recorded):** `Implement capability → Qualify → next capability`. Architecture changes only on demonstrated insufficiency. Each epic opens with Discovery → IDD → ARB → RED → GREEN → Qualification, through the (by then proven) merge gate.

### C-1 · Open EPIC-002 — Election lifecycle (greenfield migration)
- [ ] Election creation/scheduling lifecycle on `ElectionLifecycleEngine` semantics (engine is sovereign — `Election.state` stays a cache)
- [ ] Candidate registration/candidacy in the greenfield model
- [ ] Election close/count/publish events into the canonical catalog
- *(Legacy `app/Contexts/Elections/` + the working demo/live voting app remain the behavioral reference — migration, not invention.)*

### C-2 · Open EPIC-004 — Voting context *(largest remaining context — rivals everything built so far)*
- [ ] Ballot model · anonymous vote recording (**constitutional invariant: NO user_id linkage — ADR-T11/CI-5/Q7**; the two-use code system is the proven legacy mechanism)
- [ ] Vote storage + integrity (hashed codes; replay-safe events)
- [ ] Counting + validation + result publication (events → Read Models)
- [ ] Regional/national post filtering (proven in legacy; re-model in greenfield)

### C-3 · Open EPIC-003 — Evidence context
- [ ] Evidence aggregate + retention (feeds Contestation/Adjudication, which already consume references)

### C-4 · Open EPIC-005 — Read Models · Audit · Replay
- [ ] Election overview · challenge/determination overview · member/committee views · legitimacy + audit dashboards
- [ ] Replay tooling productized (replay exists in tests; needs operator-grade entry points)

### C-5 · Foundational contexts *(not yet epic-ed — ticket before touching)*
- [ ] **Membership (greenfield):** aggregate/lifecycle/events/policies — legacy `Membership` context exists but carries debt (AD-006, F-7C-5); decide migrate-vs-rebuild at Discovery
- [ ] **Identity & Authorization:** eligibility/roles as first-class (legacy: Fortify + Spatie; greenfield needs the *domain* model — VERIFIED/ELIGIBLE/AUTHORIZED chain per ADR-002)
- [ ] **Appointment** (per ARB capability map)
- [ ] **Notification** (integration events → member communication; anonymity-preserving)

### C-6 · Frontend & workflows
- [ ] Vue 3 / Inertia 2.0 surfaces for: election administration · challenge submission · determination views · correction-loop status · results (design system + `npm run design-check` binding)
- [ ] Legacy voting UI remains production for MODE-1/MODE-2 until greenfield voting replaces it — **explicit cutover decision needed per capability**

### C-7 · Production readiness (currently 🔴 by design)
- [ ] Deployment doc + environment story (the RED flag in program health)
- [ ] Monitoring/alerting for outbox/inbox/relay lag + dead-letter/parking queues
- [ ] Operational runbooks (redrive, replay, election-day procedures)

---

## 4. Engineering workstream (EPIC-000 — never mixed into PB commits)

- [ ] **ENG-004 · Mutation Ratchet 1** *(opened 2026-07-10)* — extend coverage into the uncovered 22%, then hunt the 22 escapees, **first target `ChallengeReactionOutcomeTranslator` (4 escaped)**; raise `min-msi` only at completion
- [ ] **ENG-002 · Shared Infrastructure PHPStan-max alignment** (OutboxEvent + InboxEvent + siblings, together)
- [ ] **ENG-003 · `*Ref` VO naming sweep** (post ADR-UL-01)
- [ ] **ENG-001 · Process Learning System** (PI-xxx log, estimate calibration) — feeds the retrospective
- [ ] **Debt:** AD-006 FeeTestFactory tenant mismatch · F-7C-1..3 (test-artifact repairs) · F-7C-4 (`ValidateVotingIpTest` standalone failure) · F-7C-5 (legacy context test debt: 69E/5F) · F-7C-6 (57 risky teardown warnings — framework-level)

---

## 5. Housekeeping (cheap, do soon)

- [ ] **H-1 · Re-derive the boards:** update `EPIC-001` ticket rows, `BACKLOG.md` milestones (M2 is NOT 0%), `PROGRAM_STATUS.md` (dated 07-06) from actual WBS state — the derived-percentage rule requires it, and the current boards misinform every reader
- [ ] **H-2 · NEXT-ACTION correction:** BACKLOG still names PB-003 as next — false since 07-07
- [ ] **H-3 · `architecture/ai_architecture/` disposition:** one orphaned CHILI Publisher screenshot remains (EM-001 §5 ambiguous row) — ARB removes or relocates
- [ ] **H-4 · Root `CLAUDE.md` tech-table refresh** (says Laravel 9/MySQL; actual Laravel 11/PostgreSQL) — known stale, queued
- [ ] **H-5 · Domain Model Catalogue** (docs candidate on record)

---

## 6. Explicitly NOT to-do (frozen — burden of proof applies)

No new platform principles/ADR-AIP (R-27) · no folder reorgs, capability hierarchies, domain-object renames (R-37) · no `projects/`-per-adoption split (trigger: real second adopter) · no dashboard building (post-PB-004, generated-only) · no custom metrics engine (phpmetrics when triggered) · no threshold enforcement on Infection/metrics (baseline → deliberate ratchet only) · no speculative epics before their Discovery.

---

## 7. The order, in one picture

```text
NOW           A-1..A-6  Close PB-004 → 005 → 006 → 007   (EPIC-001 done)
PARALLEL      B-1       C3 cold-boot qualification        (fresh session only)
THEN          B-3       THE retrospective                 (the loaded inbox decides once)
THEN          C-1..C-7  Capability → Qualify → Capability (Election lifecycle → Voting → Evidence
                                                           → Read Models → Membership/Identity →
                                                           Frontend → Production)
ALWAYS        EPIC-000  Engineering workstream in the gaps; boards kept derived (H-1)
```

*Progress claims in this file are derived from the cited evidence; capability-size judgments (e.g. "Voting rivals everything built so far") are ARB assessments from `20260710_1849`, not measurements.*

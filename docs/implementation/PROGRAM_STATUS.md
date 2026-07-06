# Program Status — Health Dashboard & One-Page Report

**Date:** 2026-07-06 · **Audience:** first thing anyone opens · detail: `backlog/BACKLOG.md`

## Program Health Dashboard

```text
Architecture        100%   (Blueprint v1.0 FROZEN)      Current Ticket    PB-003
Research            98%    (38C closed, 38D-01 in)      Current Commit    PB-003-C1
DDD Discovery       100%   (Rounds 17-50 complete)      Current Risk      LOW
Implementation      17%    (EPIC-001, 17/103 WBS)       Blocked           NO
Quality Gates       72%    (Arch+PHPStan ✔ · Deptrac/   Next Milestone    M1 close (PB-003)
                            Mutation pending F-1/F-2)    Then              PB-004 (needs IDD)
Documentation       96%    (Domain Model Catalogue +
                            deploy doc outstanding)
Technical Debt      97%    (AD-006 open, quarantined)
```

*(All derived: Implementation = epic WBS · Quality Gates = passing gate types / required gate types (5/7: tests, arch suite, PHPStan, purity, review process ✔; Deptrac, Mutation ✘) · Debt = closed debt items / total raised (33/34).)*

```text
Current milestone      M1 — Messaging Infrastructure          49%  (derived: 17/35 WBS)
Current ticket         PB-003 — Inbox / Deduplication         Approved · 0/18
Current branch         feature/pb003

Architecture           100%   (Blueprint v1.0 FROZEN, ARB gate passed)
Research/Governance     98%   (38C closed · 38D-01 accepted)
Implementation (EPIC-001)  17%   (derived: 17/103 WBS)
Migration                0%   (by design — after Push B)

Program burn-up
  Architecture     ██████████████████████████ 100%
  Infrastructure   ████████████░░░░░░░░░░░░░░  49%   (M1: registry+relay done, inbox next)
  Domain           ██████████████░░░░░░░░░░░░  ~55%  (Challenge+Determination done; reactions pending)
  Application      ██████░░░░░░░░░░░░░░░░░░░░  ~25%  (Adjudication service done; handlers pending)
  Integration      ░░░░░░░░░░░░░░░░░░░░░░░░░░   0%   (M3)
  Migration        ░░░░░░░░░░░░░░░░░░░░░░░░░░   0%

Health   Architecture 🟢 · Tests 🟢 · Debt 🟢 · Governance 🟢 · Migration 🟡 · Production 🔴 · Research 🟢

Remaining work (EPIC-001)   PB-003 → PB-004 → PB-005 → PB-006 → PB-007
Next milestone              M2 — Correction Loop (opens when PB-003 Verified)
Risks in focus              PB-004/005 are the two XL/High-risk tickets; both require IDDs first
Gates outstanding           F-1 Deptrac · F-2 Infection (both M3 conditions, not blockers now)
```

*Rule: every number above is derived (WBS or gate results) — nothing hand-estimated except layer bars marked "~", which become derived when their epics open.*

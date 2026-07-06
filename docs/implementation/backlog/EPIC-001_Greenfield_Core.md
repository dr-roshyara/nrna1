# EPIC-001 — Greenfield Core (correction loop) — Ticket Backlog

**Goal:** the complete constitutional correction loop `ChallengeRaised → … → ChallengeResolved` per Push B Blueprint v1.0.
**Process:** every ticket follows `../Implementation_Process_v1.0.md` (frozen 15-step workflow + DoD). Percentages DERIVED from WBS only.
**Milestones:** M1 = PB-001..003 · M2 = PB-004..005 · M3 = PB-006..007

## Ticket Board

| Ticket | Story | Lifecycle | Progress (derived) | WBS | Size | Risk | Depends on | Branch | Last updated |
|--------|-------|-----------|--------------------|-----|------|------|-----------|--------|--------------|
| PB-001 | Event Registry | **Verified** | `██████████` 100% | 8/8 | S | Low | — | election-audit | 2026-07-06 |
| PB-002 | Relay Registry | **Verified** | `██████████` 100% | 9/9 | M | Low | PB-001 | election-audit | 2026-07-06 |
| PB-003 | Inbox / Deduplication | **In Development** (C3 ✔ 8930f47fd) | `██████░░░░` 56% (Port✔ Infra✔) | 10/18 | L | Medium (no arch uncertainty) | PB-001 ✔ PB-002 ✔ | feature/pb003 | 2026-07-06 |
| PB-004 | Election Reaction | Designed | 0% | 0/22 *(est.)* | XL | High | PB-003 | — | 2026-07-06 |
| PB-005 | Contestation Reaction + infra | Designed | 0% | 0/24 *(est.)* | XL | High | PB-003, PB-004 | — | 2026-07-06 |
| PB-006 | Integration Tests IT-1..IT-8 | Designed | 0% | 0/12 *(est.)* | L | Medium | PB-004, PB-005 | — | 2026-07-06 |
| PB-007 | Merge Gate (+F-1, F-2) | Designed | 0% | 0/10 *(est.)* | M | Low | PB-006 | — | 2026-07-06 |

**Blocked flag:** none currently. (A ticket is *Blocked* only when work SHOULD proceed but cannot — unmet dependency at its turn, or external blocker. PB-004..007 are simply not yet at their turn.)

**Derived epic progress: 27 / 103 WBS items = 26%** *(estimated counts marked; recomputed as each IDD replaces its estimate)*

## Dependency chain (strict)

```text
PB-001 ✔ → PB-002 ✔ → PB-003 → PB-004 → PB-005 → PB-006 → PB-007
└────── M1 ──────────────────┘  └── M2 ─────────┘  └── M3 ───────┘
```

## Closed tickets — DoD evidence

**PB-001** (8/8): IDD-equivalent design in Blueprint §6 · RED→GREEN 12 tests · PHPStan max · Arch 131/131 · Event_Registry.md · Matrix ✓ · D-08 · gates PASS (mutation PENDING-F-2).
**PB-002** (9/9): delegation-once + dead-letter tests · match/hydrateFeePaid deleted · FeePaidHydrator→Membership · greenfield PHPStan ✓ · Arch 133/133 · Matrix ✓ · D-09 · AD-006 quarantined.

## Open tickets — pointers

**PB-003:** IDD `PB-003_Inbox_Implementation_Design.md` (Approved) · tracker `PB-003_PROGRESS.md` · 6 commits planned.
**PB-004..007:** IDD required before implementation (Process step 2); WBS estimates replaced on IDD approval.

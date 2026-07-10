# EPIC-001 — Greenfield Core (correction loop) — Ticket Backlog

**Goal:** the complete constitutional correction loop `ChallengeRaised → … → ChallengeResolved` per Push B Blueprint v1.0.
**Status:** **COMPLETE — ready for formal closure pending retrospective** (ARB final ruling 2026-07-10, verbatim in `PB-007_Merge_Gate_Implementation_Design.md` §6.5).
**Process:** PB-001..003 followed the original implementation process (`../Implementation_Process_v1.0.md`); PB-004..007 were completed under the evolved implementation discipline documented in the approved IDDs and engineering governance (Discovery → IDD → ARB → RED → GREEN → qualification → Completion Review).
**Milestones:** M1 = PB-001..003 ✔ · M2 = PB-004..005 ✔ · M3 = PB-006..007 ✔
**Synchronized:** 2026-07-10 (one-time, evidence-derived — ARB-amended execution order step 5; nothing hand-estimated).

## Ticket Board

| Ticket | Story | Lifecycle | Progress (derived) | Size | Risk | Closed | Evidence source |
|--------|-------|-----------|--------------------|------|------|--------|-----------------|
| PB-001 | Event Registry | **Verified** | `██████████` 100% (8/8 WBS) | S | — | 2026-07-06 | Board DoD record below · D-08 |
| PB-002 | Relay Registry | **Verified** | `██████████` 100% (9/9 WBS) | M | — | 2026-07-06 | Board DoD record below · D-09 |
| PB-003 | Inbox / Deduplication | **Verified / CERTIFIED** | `██████████` 100% (18/18 WBS) | L | — | 2026-07-07 | ARR `../PB-003_Architecture_Readiness_Report.md` · C6B `528619ba6` |
| PB-004 | Election Reaction | **CLOSED (ARB)** | `██████████` 100% (slices 4A.1–4C all accepted) | XL | — | 2026-07-08 | `../PB-004_Retrospective.md` · session logs 07-07/08 · Architecture Handover 2.0 |
| PB-005 | Contestation Reaction + infra | **CLOSED (ARB)** | `██████████` 100% (slices 5A–5D all accepted) | XL | — | 2026-07-09 | IDD `PB-005_Contestation_Reaction_Implementation_Design.md` · session log 07-09 |
| PB-006 | Integration Validation IT-1..IT-8 + IntegrationEventDispatcher | **CLOSED (ARB)** | `██████████` 100% (6A · 6B-1 · 6B-2 · 6C) | L | — | 2026-07-10 | IDD `PB-006_Integration_Validation_Implementation_Design.md` · ADR-MP-06 · `CorrectionLoopIntegrationTest` 4✔/39 |
| PB-007 | Greenfield Merge Gate (F-1 Deptrac · F-2 Infection · CI) | **CLOSED (ARB)** | `██████████` 100% (7A–7E + qualification + EP-02) | M | — | 2026-07-10 | IDD `PB-007_Merge_Gate_Implementation_Design.md` §6 (qualification §6.1–6.4 · closure ruling §6.5) |

**Implementation progress: 7 / 7 planned tickets completed = 100%.** *(The implementation is finished; the epic's governance is not — retrospective and formal closure remain.)*
*Derivation note: PB-001..003 used per-ticket WBS trackers (`PB-003_PROGRESS.md`); PB-004..007 tracked WBS as IDD slices + session-log gate records (the frozen IDDs carry the per-slice evidence). The original 103-item WBS estimate is superseded by those actual slice records — closure is derived from ARB rulings + executed gate evidence, not estimates.*

## Dependency chain (all satisfied)

```text
PB-001 ✔ → PB-002 ✔ → PB-003 ✔ → PB-004 ✔ → PB-005 ✔ → PB-006 ✔ → PB-007 ✔
└────── M1 ✔ ────────────────┘  └── M2 ✔ ────────┘  └── M3 ✔ ──────┘
```

## Closed tickets — DoD evidence

**PB-001** (8/8): IDD-equivalent design in Blueprint §6 · RED→GREEN 12 tests · PHPStan max · Arch 131/131 · Event_Registry.md · Matrix ✓ · D-08 · gates PASS.
**PB-002** (9/9): delegation-once + dead-letter tests · match/hydrateFeePaid deleted · FeePaidHydrator→Membership · greenfield PHPStan ✓ · Arch 133/133 · Matrix ✓ · D-09 · AD-006 quarantined.
**PB-003** (18/18): Inbox/dedup platform capability · ARR CERTIFIED 2026-07-07 · matrix `../Messaging_Architecture_Verification.md`.
**PB-004:** Election Reaction closed by ARB 2026-07-08 — Aggregate Reconstruction (ACL/Strangler over legacy `elections`), `AppliedDeterminationLedger`, messaging integration, architecture qualification (Election = complete hexagonal context); first ticket deemed *architecturally complete*; retrospective produced.
**PB-005:** Contestation Reaction closed by ARB 2026-07-09 — two reactions (adjudication w/ Dismissed short-circuit · resolution w/ parking), single-source repository, messaging integration (F-2 seam: minimal domain event + Application-supplied `resolution`), Architecture+DDD qualification.
**PB-006:** closed by ARB 2026-07-10 — `IntegrationEventDispatcher` (ADR-MP-06, R-29 escape F-PB006-1), `EventProvenance` correlation/causation (Constitutional Audit Invariant), IT-1..IT-8 over the REAL delivery path; platform now auditable event-driven.
**PB-007:** closed by ARB 2026-07-10 — Deptrac (architecture-derived, 0 violations, fail mode) · CorrelationId-minting fitness guard · Infection baseline with **F-7D-2 evidence validation** (8-thread measurement REJECTED; validated 1-thread baseline MSI 50% · MCC 77% · Covered-Code MSI 65%) · stable `composer merge-gate`/`quality-gate` interface · CI workflows · triple qualification + EP-02 (IDD §6).

## Post-closure

Reopen only for: defects · compatibility fixes · retrospective promotions.

**Next (ARB sequence, in order):**
1. **EPIC-001 Retrospective** (ARB-led; input pack: `../EPIC-001_Retrospective_Input.md`)
2. **Formal EPIC-001 Closure**
3. **EPIC-002 (Evidence) Discovery**

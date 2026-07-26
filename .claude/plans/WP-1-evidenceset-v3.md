# Work Plan — WP-1: EvidenceSet / DeterminationIssued v3 (first implementation slice)

**Created:** 2026-07-26 (handoff session) · **Status:** AUTHORIZED — execution begins in a fresh session, RED first
**Authority:** WP-1 authorization (ARB 2026-07-26) · `docs/implementation/EPIC-004_Architecture_to_Implementation_Roadmap.md` §WP-1 · ADR-T22
**Mode ruling (chair):** Auto mode — the discovery/planning work is done; this is disciplined execution of an approved slice. Plan mode returns for: architectural contradictions · missing invariants exposed by RED · ADR conflicts · EG-004/EG-005 · any later slice needing real design decisions.

> **Implementation objective: satisfy the existing architecture through executable evidence — not reinterpret or redesign it.**
> (The lens for every implementation decision. Its complement: do not revisit platform architecture unless new evidence requires it.)

## Commission (chair, verbatim in substance — the opening instruction)

> Engineering Platform status: EG-001, EG-002a, EG-003 are complete. Do not revisit platform architecture unless new evidence requires it. Begin WP-1 under the approved implementation protocol. Execute RED first. Work only within WP-1 scope. Any newly discovered platform defects are to be logged as separate engineering work (EG-xxx in `docs/plans/20260726-2056-engineering-platform-repair-plan.md`) and must NOT be repaired as part of WP-1 unless explicitly authorized.

## Scope (from the approved roadmap — immutable input)

- `EvidenceSet` VO (Adjudication local, the considered-set carrier — its frozen deferral path ends here)
- `IssueDeterminationCommand` field for the considered set
- `DeterminationIssued` payload **schema v3** (additive)
- Hydrator window shifts to **(v3, v2) — v1 RETIRED** (vCurrent+vPrevious rule, `docs/implementation/Event_Registry.md`)
- Aggregate `issue()` **fixes the set at issuance** (R-4-expanded seat per ADR-T22)
- Pre-deploy check rides the slice: **zero pending v1 outbox rows**

OUT: everything else — WP-2..WP-8 unopened; no PM code; no platform/gate changes (log as EG instead).

## Keystone RED tests (named in the authorization — write these FIRST, confirm RED)

1. v3 round-trip (payload ↔ domain event, EvidenceSet intact)
2. v2 tolerance (hydrator still accepts vPrevious)
3. v1 rejection (retired version refused)
4. set-fixed-and-immutable-in-event (issuance fixes the considered set; event carries it; no later mutation)

## Gates (every one, before ARB slice acceptance)

TDD RED confirmed before GREEN · `composer merge-gate` PASS · triple qualification (Architecture / DDD / Trustworthiness) · measurable conformance gate (*no deviation from a frozen ADR/invariant without a recorded ARB decision*) · dev guide (`developer_guide/adjudication/`) · STOP for ARB slice acceptance. WP-2 opens only on that acceptance.

## Automatic STOP conditions (chair's Auto-mode safeguard — halt and report, do not proceed)

- All four RED tests written and confirmed failing → report RED evidence before GREEN.
- GREEN implementation passes → report, run gates, stop for ARB slice acceptance.
- `composer merge-gate` fails for an unexpected reason → stop, diagnose, report — never force past.
- An ADR appears inconsistent with what implementation requires → stop, record evidence, request authorization.
- Implementation requires ANY change outside WP-1 scope (incl. platform/gates → log as EG-xxx) → stop, request authorization.

These exist so Auto mode cannot wander into WP-2 or platform work.

## Boundary rules (the handoff's substance)

- Engineering-platform repair ≠ product implementation. EG-002b / EG-004 / EG-005 are tracked, non-blocking under current ARB decisions, and continue independently.
- Pushes require the user (SSH key is passphrase-protected) — ask, don't attempt.

## Progress (2026-07-26)

- ✔ Inputs read · downstream v3-tolerance verified (no consumer gates on schema_version)
- ✔ RED written + confirmed (10 failures, all expected reasons) — reported at STOP #1
- ✔ RED→work-item mapping verified one-to-one (chair's pre-GREEN check) — GREEN authorized
- ✔ GREEN minimal: EvidenceSet VO · event `?evidenceSet` · `issue()` succession · command field · adapter v3 · hydrator window (v3,v2) v1 rejected · call sites updated · real-wire round-trip pinned in the integration test
- ✔ Gates: Adjudication unit 52✔ · loop Feature 7✔ (IT-1..4 green ON v3 payloads) · **`composer merge-gate` PASS** · pre-deploy check EXECUTED: 0 pending DeterminationIssued rows (any version)
- ✔ Dev guide `developer_guide/adjudication/02_determination_evidence_set_schema_v3.md` (+ index)

## Next action (exactly one)

**STOP #2 — awaiting ARB slice acceptance of WP-1.** WP-2 opens only on that acceptance.

# Work Plan — WP-1: EvidenceSet / DeterminationIssued v3 (first implementation slice)

**Created:** 2026-07-26 (handoff session) · **Status:** AUTHORIZED — execution begins in a fresh session, RED first
**Authority:** WP-1 authorization (ARB 2026-07-26) · `docs/implementation/EPIC-004_Architecture_to_Implementation_Roadmap.md` §WP-1 · ADR-T22

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

## Boundary rules (the handoff's substance)

- Engineering-platform repair ≠ product implementation. EG-002b / EG-004 / EG-005 are tracked, non-blocking under current ARB decisions, and continue independently.
- Pushes require the user (SSH key is passphrase-protected) — ask, don't attempt.

## Next action (exactly one)

Read ADR-T22 + roadmap §WP-1 + current `app/Contexts/Adjudication` schema-v2 implementation → write the four keystone tests → **confirm RED** → stop for nothing less than GREEN by the approved design.

# ADR-T Log — Tactical Implementation Decisions

**Scope:** tactical/implementation ADRs for the greenfield Core (distinct from domain ADR-001…008). Each promotes a decision validated in **LIT-A** (Decision Traceability Matrix D1–D12) and recorded in Round 50-xx. **Status:** Accepted unless noted. Implementation slices reference these by ID.
**Date:** 2026-06-26 · Conformance enforced by ADR-T7 toolchain.

| ADR | Decision | Context / why | Consequence | Source |
|-----|----------|---------------|-------------|--------|
| **ADR-T1** | **One aggregate per transaction** (consistency boundary) | true invariants must be transactional; cross-aggregate consistency is eventual | no txn writes two roots; fitness-tested | D1 · 50-08 · Vernon |
| **ADR-T2** | **Cross-aggregate collaboration via domain events only** (TP-1) | aggregates must not reach across boundaries | event is the seam; no foreign repo calls | D2 · 50-04 |
| **ADR-T3** | **Reliable publishing via transactional outbox; at-least-once** | event + state in one txn; no 2PC | existing `ProcessOutboxEvents`; consumers must dedupe | D3 · 50-04/05 · Richardson |
| **ADR-T4** | **Idempotent consumers via Inbox/dedupe table (key=`EventId`)** | at-least-once ⇒ duplicates possible | inbox table; no ad-hoc dedupe | D4 · R-1 · Richardson |
| **ADR-T5** | **Events version, never mutate; new version convertible-from-old else NEW event; never rename/repurpose a property** (TP-3) | downstream consumers read old + new | vN+1 for breaking; old retained until migrated | D5 · R-2 · Young |
| **ADR-T6** | **One repository per aggregate root; non-root reads via read models** | repo = aggregate roots only (DDD), persistence ignorance | no repo-per-table; no generic repo | D6 · 50-08 · Evans/Fowler |
| **ADR-T7** | **Executable architecture conformance**: Deptrac (boundaries/layers) + PHPStan + Pest/PHPUnit + `tests/Architecture` (constitutional rules) | manual review insufficient | drift fails CI | D7 · R-3 · Ford/Parsons/Kua |
| **ADR-T8** | **Correction loop without saga**: thin `AdjudicationService` coordinator; forward-only `ContainedOnly` correction | compensation assumes reversibility; anonymity forbids un-casting | no compensating txns; eventual consistency via events | D8 · 50-03/04 |
| **ADR-T9** | **Replay = projection-rebuild, not re-execution**; Replay as Application capability | Decision/side-effecting events are one-time acts | replay rebuilds read models only | D9 · 50-05 · BDR-06 |
| **ADR-T10** | **EvidenceEnvelope immutable + content hash** | tamper-evident audit trail | freeze-then-immutable; idempotent on `envelopeHash` | D10 · 50-01/06 |
| **ADR-T11** | **Anonymity invariant (Q7)** — no voter↔vote linkage in any aggregate, event payload, or projection; hashes only | constitutional, build-breaking | fitness test asserts no linkage/`user_id` reconstruction | D11 · 50-04/05 |
| **ADR-T12** | **Contestation + Adjudication = binding finality** (greenfield Core) | dispute resolution is an open problem in the voting literature | the trustworthiness differentiator; candidate contribution | D12 · 50-03 |
| **ADR-T13** | **Cryptographic E2E verifiability — DEFERRED (Proposed)** | current integrity = hash + audit-trail, weaker than voter-verifiable crypto proof | recorded **known limitation**; revisit in Part B | R-4 · S17–S20 |

## ADR template (for any new tactical ADR)
`Title · Status (Proposed/Accepted/Superseded) · Context · Decision · Consequences · Quality attribute · Source (Dxx/Round 50-xx/literature) · Conformance test`

---
*ADR-T Log — Tactical Implementation — ADR-T1..T12 Accepted; ADR-T13 Deferred. Every greenfield-Core slice references these IDs; ADR-T7 toolchain enforces ADR-T1/T2/T5/T6/T11 mechanically.*

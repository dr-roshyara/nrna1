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
| **ADR-T15** | **`EventOutbox` is an Application Port** (not an infra service) | the app service enqueues events; dispatch/publication is hidden behind the port | service never publishes directly; aggregate→pullEvents()→enqueue()→commit | R1.1 review · hexagonal/TP-1 |
| **ADR-T16** | **Cross-context references are local opaque VOs** (`ChallengeRef`, `EvidenceEnvelopeRef`) — never import another context's aggregate types | bounded-context isolation; the id string crosses the boundary, not the type | fitness test asserts no cross-context Domain import; canonical pattern for all contexts | R1.1 review · TP-1 |
| **ADR-T17** | **LegitimacyDecision placement — RESOLVED: Adjudication DOMAIN SERVICE** | legitimacy depends on evidence beyond one entity → a domain service, NOT the aggregate, NOT the application service (no constitutional reasoning in orchestration) | for now legitimacy remains a command input produced by that service at the boundary; real rules deferred (TDD); a `LegitimacyDecision` domain-service seam may be created | R1.1 review Q3 · 50-06 · Push B |
| **ADR-T20** | **Challenge lifecycle: `Adjudicated` ≠ `Resolved`** (ARB domain decision) — `Raised→Admitted→Investigating→Routed→Adjudicated→Resolved` | legal finality (binding determination exists, on `DeterminationIssued`) ≠ operational completion (consequences executed, on `ElectionCorrectionApplied`); avoids cross-context lifecycle coupling — Contestation records two independent facts, never waits synchronously | NEW event **`ChallengeAdjudicated`** (Contestation); `adjudicate()` Routed→Adjudicated (Push B); `resolve()` Adjudicated→Resolved (Push C). Updates 50-07 + Catalog. | ARB · 50-07 |
| **ADR-T14** | **Adjudication interaction = Option A: Challenge READ-ONLY** during `IssueDetermination`; **Determination is the sole aggregate written** in the transaction | one-aggregate-per-transaction (ADR-T1) must not be violated; Challenge is resolved **asynchronously later** by reacting to `DeterminationIssued` + `ElectionCorrectionApplied` (50-07 Routed→Resolved) | `AdjudicationService` loads Challenge (read), verifies `canProceedToAdjudication()` (state=Routed), creates+saves Determination, enqueues `DeterminationIssued` to outbox in the same txn; never writes Challenge | 50-03 · 50-07 · 50-08 |

| **ADR-T18** | **AggregateVersion optimistic concurrency — POSTPONED** | current aggregate semantics don't require it; the one contended invariant is enforced by `UNIQUE(organisation_id, challenge_ref)` + service guard | revisit when concurrent single-aggregate mutation becomes real | R1.1 · 50-08 |
| **ADR-T19** | **Determination persistence model = Model B (state-based aggregate + event-as-ruling-record)** *(the R1.1 review called this "ADR-T17"; T17 is taken → recorded as T19)* | **Aggregate state:** identity, challengeRef, issuedByAuthority, jurisdiction, evidenceEnvelopeRef, lifecycle state. **Event-only immutable history:** outcome, legitimacy, reason (in `DeterminationIssued`). **Audit/historical queries:** served by a read projection built from the event (outbox), not the write row. **Reconstruction:** `reconstitute()` restores lifecycle state; ruling content read from the event. | **Model A rejected** (aggregate holds ruling content): duplicates immutable data into mutable state + would touch the frozen aggregate. Matches the shipped Push A implementation. | R1.1 review Q3 · Push A |
| **ADR-T21** | **`ChallengeRouted` becomes PUBLISHED LANGUAGE** (integration event) — the correction loop's head trigger, consumed by the Adjudication Process Manager | TP-2's request-shaped crossing realized as event-consumption (choreography, consistent with every other loop hop); a cross-context command was rejected (would invent a new coupling style — recorded return condition in EPIC-004K §9); `CoordinatesAdjudication`'s frozen comment anticipated exactly this consumption | Contestation-side evolution: hydrator + Canonical Event Catalog entry; **the correlation mint relocates to the true chain head** (Contestation's raise path) with `CoordinatesAdjudication` moving to `EventProvenance::fromConsumed`; `CorrelationIdMintingTest` allowlist updates accordingly. Implementation NOT yet authorized (awaits the Q-2 interim values + refinement authorization) *(status at issuance — since AUTHORIZED as WP-3 on WP-2 slice acceptance, ARB 2026-07-30; annotation per ES-004.3, decision text unchanged)* | ARB issuance 2026-07-26 · EPIC-004K §9 (APPROVED) |
| **ADR-T22** | **`DeterminationIssued` additive payload schema evolution** — the `EvidenceSet` considered-set carrier enters the issuance command and the ruling-bearing event | R-4-expanded's permanent fixation seat is the aggregate's issuance (INV-4's single fixation fact; its frozen rider anticipated the expansion); the PM's event-logged conclusion remains the working record — one truth, two records, one authoritative | Additive schema version via the proven v1→v2 mechanism (hydrator tolerates vPrevious per the versioning rule); `EvidenceSet` VO enters through artifact №5's frozen deferral path. Implementation NOT yet authorized *(status at issuance — since REALIZED by WP-1, ARB-accepted 2026-07-27; annotation per ES-004.3, decision text unchanged)* | ARB issuance 2026-07-26 · EPIC-004K §11 (APPROVED) · EPIC-004E INV-4 rider |
| **ADR-T23** | **SUPERSEDES ADR-T17.** No Adjudication domain service decides legitimacy/sufficiency — **the constitutional authority decides (Q-1; K1 structural); the Adjudication Process Manager receives** | The ground moved under ADR-T17's anticipated "LegitimacyDecision domain service": Q-1 rules the authority decides and Governance owns validity; the Candidate-2 ruling gives judgment orchestration to the PM; a deciding domain service would contradict K1 | ADR-T17 → Status: **Superseded** (remains valid history; never edited). A future **advisory** computation (a recommendation an authority weighs, explicitly non-binding) may return as application-layer support through ARB review **with a consumer as evidence** | ARB issuance 2026-07-26 · EPIC-004K §13 (APPROVED) · Q-1 Resolution · EPIC-004J finding |

## ADR dependency graph (prevents conflicting future ADRs)
```mermaid
flowchart TD
    T11[ADR-T11 Anonymity Q7\nconstitutional] --> T3
    T11 --> T5
    T11 --> T8
    T11 --> T10
    T1[ADR-T1 one-agg-per-txn] --> T2[ADR-T2 events-are-seam]
    T1 --> T6[ADR-T6 one-repo-per-root]
    T2 --> T3[ADR-T3 outbox at-least-once]
    T3 --> T4[ADR-T4 inbox/idempotent]
    T3 --> T9[ADR-T9 replay=rebuild]
    T5[ADR-T5 versioning] --> T9
    T10[ADR-T10 evidence immutable] --> T12[ADR-T12 contestation+adjudication]
    T1 --> T8[ADR-T8 no-saga correction]
    T2 --> T8
    T8 --> T12
    T7[ADR-T7 conformance toolchain] -. enforces .-> T1
    T7 -. enforces .-> T2
    T7 -. enforces .-> T5
    T7 -. enforces .-> T6
    T7 -. enforces .-> T11
    T10 --> T13[ADR-T13 E2E crypto DEFERRED]
    T11 --> T13
```
**Rule:** a new ADR that contradicts an upstream node (esp. **T11 anonymity** or **T1 one-txn**) is rejected; superseding requires an explicit ADR + Architecture Review Gate.

## ADR template (for any new tactical ADR)
`Title · Status (Proposed/Accepted/Superseded) · Context · Decision · Consequences · Quality attribute · Source (Dxx/Round 50-xx/literature) · Conformance test`

---
*ADR-T Log — Tactical Implementation — ADR-T1..T12 Accepted; ADR-T13 Deferred. Every greenfield-Core slice references these IDs; ADR-T7 toolchain enforces ADR-T1/T2/T5/T6/T11 mechanically.*

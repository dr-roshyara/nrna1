# LIT-ARCH-TACTICAL — High-Assurance Tactical Architecture Literature Review (CHARTER, FROZEN)

**Program:** NRNA DDD Trustworthiness Research Program · **Phase III boundary (Round 50 → Implementation)**
**Status:** 🧊 CHARTER FROZEN — the **single** pre-implementation literature review. Scope/structure/grading fixed here; execution fills the slots. **No further strategic or governance literature reviews at this stage.**
**Date:** 2026-06-26

> **Framing (use verbatim in the intro).** *This review evaluates tactical implementation patterns for a high-assurance constitutional voting system. DDD is treated as one ingredient within a broader architectural stack that includes event-driven design, distributed consistency, reliability engineering, high-assurance/secure design, and architecture conformance controls.*

## Research objective (evaluative, not passive)
> **Evaluate alternative tactical implementation strategies, justify the architectural decisions adopted for this platform, and identify where the proposed architecture extends, combines, or intentionally departs from existing practice.**

## Why now / why this is the last pre-code review
Strategic discovery (BCs, BDR, aggregates) is **frozen**; no significant implementation has started, so the review can still influence tactical decisions; the remaining design surface (events, repositories, transactions, policies, consistency, state, conformance) is exactly where literature adds most value; cheaper to adopt well-supported patterns now than refactor later. *(Supersedes "no major review during Round 50" for this tactical-architecture review only; LIT-METHOD / LIT-4 / LIT-5 remain deferred per the milestone schedule.)*

## Evaluative research questions
- **RQ-T1** — Which aggregate consistency / transactional-boundary strategies are most appropriate for high-assurance constitutional voting systems?
- **RQ-T2** — Which event-design practices best preserve **determinism, replayability, and auditability** (design, versioning, ordering, idempotency)?
- **RQ-T3** — Which consistency mechanisms are most appropriate for **immutable evidence** workflows?
- **RQ-T4** — How should state transitions be modeled for **decisions, challenges, and corrections** (guards, immutability, supersession)?
- **RQ-T5** — Which **architectural conformance / fitness** techniques most effectively prevent tactical drift from the certified architecture?

## Structure (8 parts)
| Part | Title | Topics |
|------|-------|--------|
| **I** | Tactical DDD Foundations | aggregate consistency & transactional boundaries · business-rule modeling (policies/specifications/services/invariants/placement) · persistence boundaries (repositories/UoW/persistence-ignorance/optimistic concurrency) · domain-event design (contracts/granularity/evolution/ordering/identity) |
| **II** | Event-Driven Architecture | choreography vs orchestration · replay · duplicate handling · idempotency · at-least-once · ordering · **outbox/inbox** · sagas · process managers · compensation · retry |
| **III** | State Modeling | FSM · State pattern · workflow engines · temporal constraints · transition guards · **decision/correction loops** (reversal/amendment/supersession; immutable decisions; replayable transitions) |
| **IV** | High-Assurance Architecture | immutability · auditability · traceability · deterministic replay/computation · evidence records · non-repudiation · cryptographic verifiability · **Byzantine assumptions · tamper-evidence · trust boundaries · security invariants · cryptographic domain models** · secure/forbidden transitions · tamper resistance |
| **V** | Architecture Fitness **&** Recovery | *(a) Fitness:* fitness functions · architecture testing · continuous verification · dependency constraints · drift detection. *(b) Recovery:* **Reflexion Model** · architecture recovery/reconstruction · conformance recovery — *bridges to LIT-METHOD (EBSD ≈ architecture recovery, not pure DDD)* |
| **VI** | Implementation Architecture | dependency injection / composition root / DIP · **hexagonal** (ports/adapters/app/domain/infra) · **CQRS** (command/query separation, projections, read models) · **modular monolith** (module boundaries, internal APIs, dependency rules) · package organization (feature/layer/context) |
| **VII** | Gap Analysis vs Current System | compare target architecture · Laravel impl · BDR · aggregate/event/repository/policy/state models · conformance checks |
| **VIII** | Critical Evaluation | anonymous-reviewer critique of the *proposed tactical architecture*: unsupported assumptions · weakly-evidenced choices · terminological ambiguity · consistency-strategy gaps · missing high-assurance controls · overgeneralization from DDD to architecture |

## Grading conventions (reproducible — replaces High/Med/Low)
**Evidence grade:** E1 systematic-review/meta-analysis · E2 peer-reviewed empirical · E3 foundational book · E4 industrial practice · E5 expert opinion.
**Relevance:** R1 directly-applicable · R2 adaptable · R3 background.
**Relation:** Supports · Refines · Contradicts · Competes · Independent.
**Gap type:** Missing Pattern · Stronger Alternative · Implementation Risk · Research Opportunity · Already Satisfied.

## Templates
**Source entry** — Citation · DOI · Publisher · Year · Summary · Relation · Evidence grade (E) · Relevance (R) · Concepts to adopt · Concepts that challenge our work · Alternative terminology.

**Decision Traceability Matrix** *(the culminating deliverable — links every major decision to literature)*
| Architecture Decision | Literature Support | Competing Alternative | Evidence (E/R) | Final Decision |
|-----------------------|--------------------|-----------------------|----------------|----------------|
| *e.g.* Replay = Application Capability over Evidence | Evans; MS DDD guidance | Replay-as-Aggregate | E3/R1 | Capability (BDR-06) |
| One aggregate per transaction | … | shared txn | … | adopted (50-08) |
| Outbox + at-least-once + idempotent | … | exactly-once transport | … | adopted (50-05) |

**Gap matrix** — Area · Literature coverage · Current implementation · Gap type · Notes.

**Research Opportunities** *(separates engineering choices from research contributions)*
| Decision | Existing practice | Our position | Validation needed |
|----------|-------------------|--------------|-------------------|
| Governance → Software translation pipeline | none found | candidate contribution | empirical validation (→ LIT-METHOD) |

## Source budget (~35–45 high-quality sources)
Tactical DDD 8–10 · Event-driven 8–10 · State modeling 4–6 · Consistency/reliability 6–8 · High-assurance 6–8 · Fitness/conformance + recovery 4–6.

## Execution rules (honesty — per prior feedback)
- Sources must be **genuinely retrieved**, not recalled; each entry's provenance is explicit (retrieved vs canonical-known-but-unverified). No fabricated DOIs.
- The review **does not reopen** strategic architecture, bounded contexts, certified vocabulary, or governance theory (informs tactical realization only).

## Three purposes of the Traceability Matrix
1. Academic justification for implementation decisions. 2. Practical design reference for implementation. 3. Bridge to **LIT-METHOD** (positioning EBSD ≈ architecture recovery as a candidate contribution).

---
*LIT-ARCH-TACTICAL Charter — FROZEN.*
*Single pre-implementation review; title de-DDD'd (DDD = one ingredient); evaluative objective + RQ-T1..T5; 8 parts (adds Part VI Implementation Architecture; splits Fitness from Architecture Recovery→LIT-METHOD bridge; expands High-Assurance with Byzantine/tamper/crypto-domain/trust-boundaries); E1–E5 + R1–R3 grading; gap taxonomy; culminates in Decision Traceability Matrix + Research Opportunities. Genuine retrieval required. LIT-METHOD/4/5 stay deferred.*

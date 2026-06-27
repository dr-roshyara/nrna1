# LIT-ARCH-TACTICAL · Part A — Decision-Critical Review (EBTAE)

**Program:** NRNA DDD Trustworthiness Research Program · **Round 50 → Implementation boundary**
**Status:** 📚 PART A EXECUTED & FROZEN — decision-organized; ~21 sources genuinely retrieved (web, 2026-06-26). Part B (high-assurance/recovery/gap/critical-eval + cryptographic E2E) deferred to post-first-implementation.
**Method:** organized by **architecture decision** (Decision → Alternatives → Trade-offs → Evidence → Quality attribute → ADR → Verdict). Grades: E1–E5 evidence / R1–R3 relevance.
**Provenance discipline:** every source typed **RES** (peer-reviewed/academic research) · **IND** (industry/authored-book guidance) · **COM** (community practice). *Industry/community sources support **implementation**, not scientific validation.*

## Headline findings
1. Retrieved literature **supports** every tactical decision in 50-03…50-09 — none contradicted; refinements not redesign.
2. **Voting-domain literature corroborates the domain decisions**: immutable audit trail / bulletin-board (integrity), receipt-freeness/coercion-resistance (our no-linkage anonymity), and — crucially — **"better end-to-end dispute resolution remains an open challenge"** → our binding-finality **Contestation + Adjudication is a recognized open problem**, strengthening it as a candidate contribution.
3. **Honest gap surfaced:** our integrity model is hash + audit-trail, **not** cryptographic E2E verifiability (homomorphic tally, Benaloh cast-or-challenge, mixnets). That is a **deferred Part B decision**, not a current capability — recorded, not glossed.

## Source register (E=evidence · R=relevance · Type=RES/IND/COM · Rel=relation)
| # | Source | E | R | Type | Rel |
|---|--------|---|---|------|-----|
| S1 | Vernon, *Effective Aggregate Design* I–III (2011) | E3 | R1 | IND | Supports |
| S2 | Evans, *Domain-Driven Design* (2003) | E3 | R1 | IND | Supports |
| S3 | Fowler, *PoEAA* — Repository | E3 | R1 | IND | Supports |
| S4 | Microsoft, *.NET microservices persistence layer* | E4 | R1 | IND | Supports |
| S5 | Young, *Versioning in an Event Sourced System* | E3 | R1 | IND | Refines |
| S6 | InfoQ, *Versioning of Events* (2017) | E4 | R2 | COM | Supports |
| S7 | Richardson, *Transactional Outbox* (microservices.io) | E4 | R1 | IND | Supports |
| S8 | Richardson, *Idempotent Consumer* (microservices.io) | E4 | R1 | IND | Refines |
| S9 | Ford/Parsons/Kua, *Building Evolutionary Architectures* (2017/2023) | E3 | R1 | IND | Supports |
| S10 | ArchUnit / NetArchTest / deptrac (tooling) | E4 | R2 | COM | Supports |
| S11 | Cosmic Python, *Aggregates & Consistency Boundaries* | E4 | R2 | COM | Supports |
| S12 | Saga / Process-Manager (orchestration vs choreography) | E3 | R2 | IND | Competes |
| S13 | event-driven.io, *Saga & Process Manager* | E4 | R2 | COM | Competes |
| S14 | event-driven.io, *Event versioning* | E4 | R2 | COM | Refines |
| S15 | Conduktor/DZone, *Outbox + Inbox table* | E4 | R2 | COM | Refines |
| **S17** | *An Overview of End-to-End Verifiable Voting Systems* (arXiv 1605.08554) | **E2** | R1 | RES | Supports/Gap |
| **S18** | *Election Verifiability: Cryptographic Definitions* (IACR eprint 2015/233) | **E2** | R1 | RES | Supports/Gap |
| **S19** | *Towards end-to-end verifiable online voting* (arXiv 1912.00288) | **E2** | R2 | RES | Gap |
| **S20** | *E2E Verifiable Internet Voting w/ (Partially) Private Bulletin Boards* (Springer) | **E2** | R2 | RES | Supports |
| **S21** | Rivest, *Auditability & Verifiability of Elections*; RLA literature | **E2** | R2 | RES | Supports |

## Decision Traceability Matrix (central artifact)
*ADR column = authoritative decision record; **ADR-Txx** proposed for promotion to a formal tactical ADR at implementation.*

| # | Decision (ours) | Quality attribute | Literature (type) | Competing alternative | E/R | ADR | Verdict |
|---|-----------------|-------------------|-------------------|-----------------------|-----|-----|---------|
| D1 | One aggregate per transaction | **Consistency** | S1,S11,S16 (IND/COM) | multi-aggregate txn | E3/R1 | 50-08 →ADR-T1 | **Keep** |
| D2 | Cross-aggregate via events, no shared txn | **Autonomy/Decoupling** | S1-II,S7 (IND) | 2PC/XA | E3/R1 | 50-04 →ADR-T2 | **Keep** |
| D3 | Transactional outbox → at-least-once | **Reliability** | S7 (IND) | exactly-once transport | E4/R1 | 50-04/05 →ADR-T3 | **Keep** |
| D4 | Idempotent consumers + **inbox** | **Fault tolerance** | S8,S15 (IND/COM) | broker exactly-once | E4/R1 | 50-05 →ADR-T4 | **Keep + R-1** |
| D5 | Events version, never mutate (vN+1) | **Modifiability** | S5,S14 (IND/COM) | mutate-in-place | E3/R1 | 50-04 →ADR-T5 | **Keep + R-2** |
| D6 | One repo per aggregate root | **Maintainability/Integrity** | S2,S3,S4 (IND) | repo-per-table/generic | E3/R1 | 50-08 →ADR-T6 | **Keep** |
| D7 | Fitness tests enforce TP-1/2/3+Q7 | **Maintainability (conformance)** | S9,S10 (IND/COM) | manual review | E3/R1 | 50-06/09 →ADR-T7 | **Keep + R-3** |
| D8 | No-saga correction (thin coordinator) | **Simplicity/Auditability** | S12,S13 (IND/COM) | full saga + compensation | E3/R2 | 50-03/04 →ADR-T8 | **Keep** |
| D9 | Replay = projection-rebuild | **Auditability/Recoverability** | S5 (IND) | replay-as-aggregate | E3/R2 | 50-05 →ADR-T9 | **Keep** (BDR-06) |
| **D10** | EvidenceEnvelope immutable + hash | **Integrity** | S20,S21 (RES) — audit trail/bulletin board | mutable evidence | E2/R1 | 50-01/06 →ADR-T10 | **Keep (RES-backed)** |
| **D11** | Anonymity invariant — no voter↔vote linkage (Q7) | **Privacy/Coercion-resistance** | S17,S18 (RES) — receipt-freeness | store linkage for "verifiability" | E2/R1 | 50-04/05 →ADR-T11 | **Keep (RES-backed)** |
| **D12** | Contestation + Adjudication = binding finality (greenfield) | **Correctness/Dispute-resolution** | S17,S19 (RES) — *dispute resolution = OPEN problem* | none (gap) | E2/R2 | 50-03 →ADR-T12 | **Keep — candidate contribution** |

## Rejected alternatives & trade-offs (negative evidence — we searched for the opposite)
| Rejected | Why rejected (evidence) | Trade-off accepted |
|----------|-------------------------|--------------------|
| **Exactly-once messaging** | No true exactly-once over a broker; consensus (S7,S8) → **business idempotency superior** | needs dedupe/inbox store |
| **2PC / XA across aggregates** | blocking, SPOF, partition-intolerant (S1,S7,S12) | eventual consistency across aggregates |
| **Replay-as-Aggregate** | replay is a read-side rebuild; aggregating it conflates concerns (S5) | replay = Application capability (BDR-06) |
| **Repository-per-table / generic repo** | DDD repo = aggregate roots only; per-table leaks persistence (S2,S3) | non-root queries go via read models |
| **Full saga w/ compensation** | compensation assumes **reversibility**; anonymity forbids un-casting votes (S12,S13) | corrections forward-only → `ContainedOnly` |
| **Cryptographic E2E verifiability now** | *not rejected — **deferred*** (S17–S20): homomorphic tally / Benaloh challenge / mixnets are a major design axis out of Part A scope | current model = hash + audit-trail integrity; **E2E is a Part B decision** |

## Architecture Decision Confidence (literature × empirical → confidence)
*Empirical = None pre-implementation; confidence is therefore literature-bounded and rises after the first slice.*

| Decision | Literature | Empirical (now) | Confidence | Watch at implementation |
|----------|-----------|-----------------|------------|--------------------------|
| D1, D2, D6 | High | None | **High** | low risk |
| D3, D4 | High (IND) | None | **Med-High** | duplicate-delivery behaviour |
| D5 | Medium | None | **Medium** | versioning under real schema change |
| D7 | Medium | None | **Medium** | PHP tooling maturity (R-3) |
| D8 | Medium | None | **Medium** | multi-step correction → may need Option C coordinator |
| D9 | Low-Med | None | **Medium** | Replay placement (BDR-06) |
| D10, D11 | High (RES) | None | **High** | integrity/anonymity invariants under load |
| D12 | Med (RES; open problem) | None | **Med (novel)** | the genuine research surface |

## Refinements & gaps (apply with first slice — none a redesign)
- **R-1 (Missing Pattern → adopt):** explicit **inbox/dedupe table** keyed by `EventId` (S8,S15).
- **R-2 (Refine TP-3):** *a new event version must be convertible from the prior; else it is a **NEW event**; never rename/repurpose a property* (S5,S14).
- **R-3 (Implementation Risk → decide):** PHP conformance tool (deptrac/phpat/PHPArkitect) to enforce TP-1/2/3+Q7 (S9,S10).
- **R-4 (Domain gap → Part B):** decide whether/where to add **cryptographic E2E verifiability** (S17–S20); today's integrity = hash+audit-trail, which is weaker than voter-verifiable cryptographic proof. **Record as a known limitation**, not a silent omission.

## Research opportunities (candidate contributions — separated from established practice)
| Opportunity | Existing practice | Our position | Validation route |
|-------------|-------------------|--------------|------------------|
| **Governance → Software translation (EBSD)** | none found | candidate contribution | LIT-METHOD |
| **Anonymity-bounded correction (`ContainedOnly`)** | saga compensation assumes reversibility | forward-only correction under anonymity | impl + Part B |
| **Boundary Decision Register as a governed artifact** | informal context maps | versioned, append-only BDR | LIT-METHOD |
| **Knowledge Certification Pipeline** | ad-hoc domain capture | certified Knowledge 1.0 lifecycle | LIT-METHOD |
| **Binding-finality dispute resolution for online voting** | *open problem (S17,S19)* | Contestation+Adjudication correction loop | impl + LIT-4 (voting) |

## Outcome
Part A clears the implementation-critical decisions: **D1–D12 confirmed against literature** (D10–D12 now research-backed); apply **R-1/R-2/R-3** in the first slice; record **R-4** (E2E) as a deferred Part B decision and known limitation. Proceed to greenfield-Core implementation, then Part B against empirical findings.

## Sources
Tactical/EDA: [Vernon I](https://www.dddcommunity.org/wp-content/uploads/files/pdf_articles/Vernon_2011_1.pdf) · [II](https://kalele.io/wp-content/uploads/2019/01/DDD_COMMUNITY_ESSAY_AGGREGATES_PART_2.pdf) · [III](https://www.dddcommunity.org/wp-content/uploads/files/pdf_articles/Vernon_2011_3.pdf) · [MS Learn persistence](https://learn.microsoft.com/en-us/dotnet/architecture/microservices/microservice-ddd-cqrs-patterns/infrastructure-persistence-layer-design) · [Young Versioning](https://www.goodreads.com/en/book/show/34327067-versioning-in-an-event-sourced-system) · [InfoQ Versioning](https://www.infoq.com/news/2017/07/versioning-event-sourcing/) · [event-driven.io versioning](https://event-driven.io/en/how_to_do_event_versioning/) · [Outbox](https://microservices.io/patterns/data/transactional-outbox.html) · [Idempotent Consumer](https://microservices.io/patterns/communication-style/idempotent-consumer.html) · [Building Evolutionary Architectures ch.4](https://www.oreilly.com/library/view/building-evolutionary-architectures/9781492097532/ch04.html) · [Saga & Process Manager](https://event-driven.io/en/saga_process_manager_distributed_transactions/) · [Cosmic Python ch.7](https://www.cosmicpython.com/book/chapter_07_aggregate.html)
Voting domain (RES): [Overview of E2E Verifiable Voting](https://arxiv.org/pdf/1605.08554) · [Election Verifiability: Cryptographic Definitions (IACR 2015/233)](https://eprint.iacr.org/2015/233.pdf) · [Towards E2E verifiable online voting](https://arxiv.org/pdf/1912.00288) · [E2E Verifiable Internet Voting w/ Partially Private Bulletin Boards](https://link.springer.com/chapter/10.1007/978-3-032-05036-6_5)

---
*LIT-ARCH-TACTICAL Part A — Decision-Critical Review — EXECUTED & FROZEN.*
*~21 retrieved sources (typed RES/IND/COM); Decision Traceability Matrix D1–D12 (adds quality attribute + ADR linkage + domain decisions D10–D12 RES-backed); rejected-alternatives/trade-offs table (negative evidence); Architecture Decision Confidence (lit×empirical); research/industry/community distinction explicit. Voting literature corroborates integrity (D10) + anonymity/coercion-resistance (D11) and confirms dispute-resolution is an OPEN problem (D12 = candidate contribution). Honest gap R-4: no cryptographic E2E verifiability yet (deferred Part B, recorded limitation). 5 research opportunities. Carry-forwards R-1/R-2/R-3. No redesign → proceed to greenfield Core.*

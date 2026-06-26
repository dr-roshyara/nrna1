# LIT-ARCH-TACTICAL · Part A — Decision-Critical Review

**Program:** NRNA DDD Trustworthiness Research Program · **Round 50 → Implementation boundary**
**Status:** 📚 PART A EXECUTED — decision-organized; ~16 sources genuinely retrieved (web, 2026-06-26). Part B (high-assurance/recovery/gap/critical-eval) deferred to post-first-implementation per charter.
**Method:** organized by **architecture decision** (Decision → Alternatives → Evidence → Recommendation). Grades: E1–E5 evidence / R1–R3 relevance (charter §grading). **Provenance:** retrieved this session; foundational books cited as canonical (edition/year from retrieval, not page-verified).

## Headline finding
The retrieved literature **supports the tactical decisions already made** (50-03…50-09). No decision is contradicted. Three items are **refinements/gaps** (R-1…R-3 below), none requiring redesign — only sharpening before/with the first slice.

## Source register (E = evidence grade · R = relevance · Rel = relation to our work)
| # | Source | E | R | Rel |
|---|--------|---|---|-----|
| S1 | Vernon, *Effective Aggregate Design* I–III (dddcommunity.org, 2011) | E3 | R1 | Supports |
| S2 | Evans, *Domain-Driven Design* (2003) — repositories/aggregates | E3 | R1 | Supports |
| S3 | Fowler, *PoEAA* — Repository | E3 | R1 | Supports |
| S4 | Microsoft, *.NET microservices: infrastructure persistence layer design* | E4 | R1 | Supports |
| S5 | Young, *Versioning in an Event Sourced System* | E3 | R1 | Refines |
| S6 | InfoQ, *Versioning of Events in Event Sourcing* (2017) | E4 | R2 | Supports |
| S7 | Richardson, *Transactional Outbox* (microservices.io) | E4 | R1 | Supports |
| S8 | Richardson, *Idempotent Consumer* (microservices.io) | E4 | R1 | Refines |
| S9 | Ford/Parsons/Kua, *Building Evolutionary Architectures* (O'Reilly 2017; 2nd ed. 2023) | E3 | R1 | Supports |
| S10 | ArchUnit / NetArchTest / dependency-cruiser (tooling) | E4 | R2 | Supports |
| S11 | Cosmic Python, *Aggregates & Consistency Boundaries* (ch.7) | E4 | R2 | Supports |
| S12 | Saga / Process-Manager literature (orchestration vs choreography; compensation) | E3 | R2 | Competes |
| S13 | event-driven.io, *Saga & Process Manager* | E4 | R2 | Competes |
| S14 | event-driven.io, *How to (not) do event versioning* | E4 | R2 | Refines |
| S15 | Conduktor / DZone, *Outbox + Inbox table* | E4 | R2 | Refines |
| S16 | James Hickey, *DDD Aggregates: Consistency Boundary* | E4 | R3 | Supports |

## Decision Traceability Matrix (the central artifact)
| # | Architecture Decision (ours) | Literature support | Competing alternative | Evidence | Verdict |
|---|------------------------------|--------------------|-----------------------|----------|---------|
| D1 | **One aggregate per transaction** = consistency boundary (50-08) | S1, S11, S16 (Vernon's primary rule) | multi-aggregate txn (Vernon's *bounded* exceptions; user-affinity) | E3/R1 | **Keep** — mainstream; exceptions don't apply (anonymity + cross-context) |
| D2 | **Cross-aggregate via events, no shared txn** (TP-1) | S1-II, S7 | 2PC / XA transactions | E3/R1 | **Keep** |
| D3 | **Transactional outbox → at-least-once** (50-04/05; existing `ProcessOutboxEvents`) | S7 (outbox guarantees at-least-once, not exactly-once) | exactly-once transport | E4/R1 | **Keep** — matches existing infra |
| D4 | **Idempotent consumers, dedupe on EventId** (50-05) | S8 (consumers MUST be idempotent; **inbox table**) | trust broker exactly-once | E4/R1 | **Keep + refine R-1** |
| D5 | **Events version, never mutate; breaking → vN+1** (TP-3) | S5, S14 (**new version must be convertible from old, else it is a NEW event**; never rename props) | mutate-in-place | E3/R1 | **Keep + refine R-2** |
| D6 | **One repository per aggregate root; repo owns root only** (50-08) | S2, S3, S4 (DDD repo = aggregate roots only; 1:1 repo↔root) | repository-per-table / generic repo | E3/R1 | **Keep** |
| D7 | **Fitness tests enforce TP-1/2/3 + Q7** (50-06…09) | S9 (fitness functions = objective integrity checks), S10 | manual review / architced docs only | E3/R1 | **Keep + gap R-3 (tooling)** |
| D8 | **Correction loop = events + light `AdjudicationService` coordinator; NO saga/compensation** (50-03/04) | S12, S13 (saga = eventual consistency via *compensation*; process-manager = coordinator) | full saga w/ compensating txns; central orchestrator | E3/R2 | **Keep** — compensation needs reversibility; anonymity forbids un-cast → `ContainedOnly`. Our `AdjudicationService` = thin coordinator, not a stateful saga |
| D9 | **Replay = projection-rebuild, not re-execute** (50-05) | S5 (events replayable for read-model rebuild) | replay-as-aggregate | E3/R2 | **Keep** (BDR-06 confirm at impl) |

## Refinements & gaps (apply before/with first slice — none is a redesign)
- **R-1 (Missing Pattern → adopt): explicit Inbox table.** 50-08 left consumer-dedupe shape open; S8/S15 prescribe an **inbox/dedupe table** keyed by `EventId`. **Adopt** an inbox table for consumer idempotency rather than ad-hoc dedupe.
- **R-2 (Refine TP-3): convertibility rule.** S5/S14: *a new event version must be convertible from the prior version; if not, it is a **new event**, not a version.* **Refine TP-3** to state this explicitly (and "never rename a property; never change a property's semantic meaning").
- **R-3 (Implementation Risk → decide): PHP fitness tooling.** S9/S10 tooling is JVM/.NET (ArchUnit/NetArchTest). We have `tests/Architecture/`; **choose a PHP conformance tool** (e.g. deptrac / phpat / PHPArkitect) to mechanically enforce TP-1/2/3 + Q7, not bespoke tests alone.

## Research opportunities (candidate contributions — separate from engineering choices)
| Decision | Existing practice | Our position | Validation needed |
|----------|-------------------|--------------|-------------------|
| **Anonymity-bounded correction (`ContainedOnly`)** | saga compensation assumes *reversibility* | corrections that **cannot** reverse the act (votes immutable) → restore-forward only | empirical (impl) + Part B high-assurance lit |
| **Governance → software translation (EBSD)** | none found in Part A scope | candidate contribution | LIT-METHOD (post-impl) |

## Outcome
Part A clears the implementation-critical decisions: **D1–D9 confirmed against literature; apply R-1 (inbox table) and R-2 (TP-3 convertibility) in the first slice; resolve R-3 (PHP fitness tool) as a setup task.** Proceed to greenfield-Core implementation (Challenge + Determination + Evidence), then Part B against empirical findings.

## Sources
[Vernon — Effective Aggregate Design I](https://www.dddcommunity.org/wp-content/uploads/files/pdf_articles/Vernon_2011_1.pdf) · [II](https://kalele.io/wp-content/uploads/2019/01/DDD_COMMUNITY_ESSAY_AGGREGATES_PART_2.pdf) · [III](https://www.dddcommunity.org/wp-content/uploads/files/pdf_articles/Vernon_2011_3.pdf) · [dddcommunity index](https://www.dddcommunity.org/library/vernon_2011/) · [Aggregate Design Rules (ArchiLab)](https://www.archi-lab.io/infopages/ddd/aggregate-design-rules-vernon.html) · [Cosmic Python ch.7](https://www.cosmicpython.com/book/chapter_07_aggregate.html) · [Hickey — Consistency Boundary](https://www.jamesmichaelhickey.com/consistency-boundary/) · [MS Learn — persistence layer design](https://learn.microsoft.com/en-us/dotnet/architecture/microservices/microservice-ddd-cqrs-patterns/infrastructure-persistence-layer-design) · [Young — Versioning (Goodreads)](https://www.goodreads.com/en/book/show/34327067-versioning-in-an-event-sourced-system) · [InfoQ — Versioning of Events](https://www.infoq.com/news/2017/07/versioning-event-sourcing/) · [event-driven.io — event versioning](https://event-driven.io/en/how_to_do_event_versioning/) · [Richardson — Transactional Outbox](https://microservices.io/patterns/data/transactional-outbox.html) · [Richardson — Idempotent Consumer](https://microservices.io/patterns/communication-style/idempotent-consumer.html) · [Conduktor — Outbox](https://www.conduktor.io/glossary/outbox-pattern-for-reliable-event-publishing) · [Building Evolutionary Architectures — ch.4 (O'Reilly)](https://www.oreilly.com/library/view/building-evolutionary-architectures/9781492097532/ch04.html) · [InfoQ — Fitness Functions](https://www.infoq.com/articles/fitness-functions-architecture/) · [event-driven.io — Saga & Process Manager](https://event-driven.io/en/saga_process_manager_distributed_transactions/)

---
*LIT-ARCH-TACTICAL Part A — Decision-Critical Review — EXECUTED.*
*~16 retrieved sources, decision-organized; Decision Traceability Matrix D1–D9 all KEEP (literature supports current design). 3 refinements/gaps: R-1 adopt inbox table (idempotency), R-2 refine TP-3 with Young's convertibility rule, R-3 choose PHP fitness tool (deptrac/phpat). 2 research opportunities (ContainedOnly correction; EBSD→LIT-METHOD). Part B deferred to post-first-slice. No redesign required → proceed to greenfield Core.*

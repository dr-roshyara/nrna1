# LIT-ARCH-TACTICAL — Evidence-Based Tactical Architecture Evaluation (EBTAE) (CHARTER, FROZEN)

**Program:** NRNA DDD Trustworthiness Research Program · **Phase III boundary (Round 50 → Implementation)**
**Status:** 🧊 CHARTER FROZEN v1.0 — the **single** pre-implementation architecture **evaluation**. Scope/structure/grading fixed; execution fills the slots. **No further strategic or governance literature reviews at this stage.**
**Date:** 2026-06-26 · Code: **LIT-ARCH-TACTICAL** · Tactical analogue of **EBSD** (the strategic instrument).

> **Reframing (binding).** This is an **evaluation**, not a survey. A survey asks *"what exists?"*; an evaluation asks *"which architectural alternative should we adopt, why, and what evidence supports it?"* Every retrieved source is used to **justify or challenge a concrete decision** — captured in the Decision Traceability Matrix. DDD is **one ingredient** within software architecture, distributed systems, event-driven design, high-assurance/secure design, architecture conformance, and security engineering.

## Research objective (evaluative)
> **Evaluate alternative tactical implementation strategies, justify the architectural decisions adopted for this platform, and identify where the proposed architecture extends, combines, or intentionally departs from existing practice.**

## Decision categories (so no decision class disappears)
Every evaluated decision is tagged: **Domain · Application · Infrastructure · Security · Integration · Deployment · Operations.** A decision that fits no category is a coverage gap.

## Evaluation discipline (ATAM-style trade-off, per decision)
For each decision record **Alternatives → Advantages / Disadvantages → Quality-attribute impact → Evidence → Decision**, plus **ADR mapping** (which ADR/BDR/ARB record this source strengthens or weakens). Literature rarely says "best"; it states trade-offs.

## Why now / why this is the last pre-code review
Strategic discovery (BCs, BDR, aggregates) is **frozen**; no significant implementation has started, so the review can still influence tactical decisions; the remaining design surface (events, repositories, transactions, policies, consistency, state, conformance) is exactly where literature adds most value; cheaper to adopt well-supported patterns now than refactor later. *(Supersedes "no major review during Round 50" for this tactical-architecture review only; LIT-METHOD / LIT-4 / LIT-5 remain deferred per the milestone schedule.)*

## Evaluative research questions
- **RQ-T1** — Which aggregate consistency / transactional-boundary strategies are most appropriate for high-assurance constitutional voting systems?
- **RQ-T2** — Which event-design practices best preserve **determinism, replayability, and auditability** (design, versioning, ordering, idempotency)?
- **RQ-T3** — Which consistency mechanisms are most appropriate for **immutable evidence** workflows?
- **RQ-T4** — How should state transitions be modeled for **decisions, challenges, and corrections** (guards, immutability, supersession)?
- **RQ-T5** — Which **architectural conformance / fitness** techniques most effectively prevent tactical drift from the certified architecture?
- **RQ-T6** — Which **implementation patterns best preserve architectural integrity over long-term evolution?** *(the question the whole methodology exists to answer)*

## Structure (8 parts)
| Part | Title | Topics |
|------|-------|--------|
| **I** | Tactical DDD Foundations | aggregate consistency & transactional boundaries · business-rule modeling (policies/specifications/services/invariants/placement) · persistence boundaries (repositories/UoW/persistence-ignorance/optimistic concurrency) · domain-event design (contracts/granularity/evolution/ordering/identity) |
| **II** | Event-Driven Architecture | choreography vs orchestration · replay · duplicate handling · idempotency · at-least-once · ordering · **outbox/inbox** · sagas · process managers · compensation · retry |
| **III** | State Modeling | FSM · State pattern · workflow engines · temporal constraints · transition guards · **decision/correction loops** (reversal/amendment/supersession; immutable decisions; replayable transitions) |
| **IV** | High-Assurance Architecture | immutability · auditability · traceability · deterministic replay/computation · evidence records · non-repudiation · cryptographic verifiability · **Byzantine assumptions · tamper-evidence · trust boundaries · security invariants · cryptographic domain models** · secure/forbidden transitions · tamper resistance |
| **V** | Architecture Fitness **&** Recovery | *(a) Fitness:* fitness functions · architecture testing · continuous verification · dependency constraints · drift detection. *(b) Recovery:* **Reflexion Model** · architecture recovery/reconstruction · conformance recovery — *bridges to LIT-METHOD (EBSD ≈ architecture recovery, not pure DDD)* |
| **VI** | Implementation Architecture | DI / composition root / DIP · **hexagonal** (ports/adapters) · **anti-corruption layers** · **module APIs / internal contracts** · **CQRS** (projections, read models) · **modular monolith** (boundaries, dependency rules) · packaging (feature/layer/context) · **cross-cutting: configuration, observability, feature flags, background workers** |
| **VII** | **Security Architecture** *(own section)* | threat modeling · secure-by-design · defense-in-depth · least-privilege · zero-trust · attack surface · privilege escalation · key management · identity boundaries |
| **VIII** | **Testing & Verification** *(own section)* | architecture tests · **property-based** · invariant · mutation · **event-contract** · **replay/deterministic** · integration · chaos · fitness functions |
| **IX** | Gap Analysis vs Current System | compare target architecture · Laravel impl · BDR · aggregate/event/repository/policy/state models · conformance checks |
| **X** | Critical Evaluation | anonymous-reviewer critique: unsupported assumptions · weakly-evidenced choices · terminological ambiguity · consistency-strategy gaps · missing high-assurance controls · DDD→architecture overgeneralization |

## Quality Attribute Evaluation (every decision scored against these)
Each decision records its impact (+/0/−) on: **performance · scalability · availability · reliability · security · maintainability · modifiability · auditability · testability · recoverability · portability.** This makes alternatives objectively comparable rather than asserted. *(Auditability, reliability, security, recoverability dominate for a voting system.)*

## Architecture Evaluation Methods (positions EBSD/EBTAE)
Compare our approach against established methods — **ATAM · SAAM · CBAM · Architecture Recovery · Reflexion Model · Continuous Architecture.** EBSD/EBTAE most resembles **architecture recovery + continuous (fitness-driven) conformance**; this comparison feeds **LIT-METHOD** positioning (candidate contribution vs prior art).

## Empirical Validation Plan (bridges literature → implementation)
For each adopted pattern, record what evidence implementation will produce:
| Pattern | Metric | Expected | Observed | Decision | Follow-up |
|---------|--------|----------|----------|----------|-----------|
*(e.g. outbox → duplicate-delivery rate → "deduped, zero double-effects"; replay → determinism contract pass; aggregate txn → zero multi-root commits.)* Populated during/after the first slice (Part B input).

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

## Execution split (refined — decision-organized, two milestones)
Execution is organized **by architecture decision** (Decision → Alternatives → Evidence → Recommendation → Adopted), not chapter-by-chapter, and split into two milestones:
- **Part A — Decision-Critical Review (NOW, ~15–20 sources):** only decisions that immediately gate implementation — (1) Aggregate design/transaction boundaries, (2) Domain events/contracts/versioning, (3) Event reliability (outbox/inbox/idempotency/ordering/retry), (4) Repository ownership/persistence boundaries, (5) Architecture fitness/conformance. → `Round50-LIT-A_Decision_Critical_Review.md`.
- **Part B — Extended Tactical Review (AFTER first implementation slice):** Parts IV (High-Assurance), V-recovery, VII Gap, VIII Critical Evaluation — now answerable against *empirical* implementation findings rather than hypotheticals. Feeds LIT-METHOD.

## Source budget (~35–45 total; Part A ≈ 15–20) — rebalanced for program maturity
Tactical DDD 7–8 · Event-Driven 7–8 · Distributed Systems & Consistency 6–7 · High-Assurance / Security 7–8 · Architecture Conformance & Recovery 6–7 · Architecture Evaluation Methods 4–5 · Testing & Fitness 4–5. *(Governance, evaluation, and verification now weighted at least as heavily as tactical DDD.)*

## Execution rules (honesty — per prior feedback)
- Sources must be **genuinely retrieved**, not recalled; each entry's provenance is explicit (retrieved vs canonical-known-but-unverified). No fabricated DOIs.
- The review **does not reopen** strategic architecture, bounded contexts, certified vocabulary, or governance theory (informs tactical realization only).

## Three purposes of the Traceability Matrix
1. Academic justification for implementation decisions. 2. Practical design reference for implementation. 3. Bridge to **LIT-METHOD** (positioning EBSD ≈ architecture recovery as a candidate contribution).

---
*LIT-ARCH-TACTICAL — Evidence-Based Tactical Architecture Evaluation (EBTAE) — CHARTER FROZEN v1.0.*
*Reframed survey→evaluation (every source justifies/challenges a decision); evaluative objective + RQ-T1..T6; decision categories (Domain/App/Infra/Security/Integration/Deployment/Operations); ATAM-style trade-off + ADR mapping per decision; Quality-Attribute scoring (11 attributes); Architecture-Evaluation-Methods comparison (ATAM/SAAM/CBAM/Recovery/Reflexion/Continuous → EBSD positioning); Empirical Validation Plan; 10 parts (adds Security + Testing/Verification as own sections; expands Implementation Architecture); E1–E5 + R1–R3 grading; Decision Traceability Matrix is the central artifact; rebalanced ~35–45 budget. Genuine retrieval required. Part A executed (LIT-A). LIT-METHOD/4/5 deferred. STRUCTURE FROZEN — no further expansion.*

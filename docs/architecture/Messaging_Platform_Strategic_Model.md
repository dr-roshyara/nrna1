# Messaging Platform — Strategic DDD Model (pre-PB-004)

**Status:** DRAFT for ARB approval · **2026-07-07** · reviewer role: Strategic DDD Architect (not implementer)
**Scope:** model the platform that PB-001 (Event Registry), PB-002 (Relay), PB-003 (Inbox) collectively produced. **No implementation. No executable architecture. No tests.** Deliverables 1–5 below; §6 lists open questions the model cannot settle alone.
**Discipline honored:** Business → DDD → Architecture → Tests → Implementation. This document is the DDD layer that must precede any platform-wide fitness test or the `Messaging_Platform_Architecture.md` contract (AKB Release 1.2).

> Correction carried into this model: an earlier draft decision (withdrawn) asserted "Messaging **owns** anonymity, enforced platform-wide." The modeling below shows that is wrong — anonymity is a **constitutional** invariant that Messaging **preserves**. That distinction is the reason this step exists.

---

## Deliverable 1 — Strategic Model

### What it is
The **Messaging Platform** is the capability that moves **domain/integration events reliably between bounded contexts**, decoupling producers from consumers in **time** (asynchrony) and in **failure** (one context's crash never corrupts another). It provides at-least-once delivery made **effectively-once** by an idempotent consumer, with recovery.

### Why it exists
The constitutional correction loop — `Challenge → Determination → Correction → Resolution` — spans Contestation, Adjudication, and Election. Those contexts must collaborate **without direct calls and without a shared database** (context autonomy; forward-only, no saga — ADR-T8). Reliable asynchronous integration is therefore a prerequisite, and it is a **cross-cutting** concern: every context needs it, none should reinvent it.

### The architectural problem it solves
Exactly-once *delivery* is impossible over an unreliable substrate; the industry answer is at-least-once delivery + idempotent consumption + bounded recovery. The platform packages that answer once, so consumers write business handlers, not plumbing.

### DDD classification (justified)
| Question | Answer | Why |
|----------|--------|-----|
| Bounded context? | **No** | It has no business language of its own; it carries other contexts' events. |
| Core/business subdomain? | **No** | It is non-differentiating; it encodes no competitive rule. |
| Generic / technical subdomain? | **Yes** | Eventing/messaging is a solved, standard capability (Evans: generic subdomain). |
| Realized as? | **Platform Capability** = **Shared Infrastructure** + a thin **Shared Kernel / Published Language** | Adapters are Laravel; the port contracts + the integration-event envelope are the shared, pure-PHP language. |

### Integration pattern with bounded contexts
**Customer/Supplier + Published Language.** Consumers are customers; the platform is the supplier. The **published language** is the *event envelope* (produced side) and the `InboxHandler` port (consumed side). Anti-corruption stays inside each consumer's handler — it reconstructs its **local** value objects from string identifiers (ADR-T16). The platform never speaks a context's business language.

---

## Deliverable 2 — Ownership Matrix

Disposition legend: **Owns** (implements & is accountable) · **Coordinates** (owns the mechanism, another party owns the policy) · **Preserves** (does not own; must not violate) · **Observes** (emits signals only) · **Does NOT own**. Every responsibility has exactly one owner.

| Capability | Messaging disposition | Owner (if not Messaging) |
|------------|----------------------|--------------------------|
| Event registration / hydration (Registry) | **Owns** | — |
| Delivery / Relay (outbox → consumer) | **Owns** | — |
| Deduplication / idempotency | **Owns** | — |
| Consumption orchestration (Inbox) | **Owns** | — |
| Execution (run handler + classify outcome) | **Owns** | — |
| Retry **policy** (bounded → dead-letter) | **Owns** | — |
| Retry **numbers** (counts, windows) | **Coordinates** | Operations (config) |
| Recovery / re-drive | **Owns** | — |
| Scheduling **cadence** | **Coordinates** | Operations (schedule) |
| Dead-letter **mechanism** | **Owns** | — |
| Dead-letter **remediation decision** | **Does NOT own** | Operations |
| Transport audit ledger (`inbox_events`/`outbox_events`) | **Owns** | — |
| Business/constitutional audit trail | **Does NOT own** | Election / Contestation / Adjudication |
| Correlation / causation **propagation** | **Owns** (carries the ids) | semantics: business |
| Tenant / election id propagation | **Preserves** (propagates; never crosses tenants) | isolation invariant: constitution/Election |
| **Anonymity** (no voter↔vote linkage) | **Preserves** | **Constitution / Election** (ADR-T11 · Q7 · CI-5) |
| Ordering within a consistency boundary | **Does NOT own** | (out of scope — per-message idempotency only; future ADR if needed) |
| Replay (future) — **mechanism** | **Owns** (engine seam reusable) | — |
| Replay (future) — **policy/what to replay** | **Does NOT own** | Operations / owning context |
| Monitoring / metrics / alerting (future) | **Observes** | Operations (alerting policy) |
| Business decisions / policy | **Does NOT own** | each bounded context |

---

## Deliverable 3 — Platform Invariant Catalog

Classes: **Platform** (Messaging owns & enforces) · **Infrastructure** (property of the delivery substrate) · **Constitutional** (business/constitution owns; Messaging **preserves**) · **Operational** (tunable) · **Business** (none, by design). Column "M." = Messaging role: **E**nforce or **P**reserve.

| ID | Invariant | Class | Owner | M. | Why |
|----|-----------|-------|-------|----|-----|
| P1 | Single writer per store (each of `inbox_events`/`outbox_events` mutated only by its package) | Platform | Messaging | E | prevents rogue writers corrupting the ledger |
| P2 | Exactly one execution owner (handler-outcome classification) | Platform | Messaging | E | no duplicated classify logic (God-Service guard) |
| P3 | Exactly one recovery owner (retry scheduling) | Platform | Messaging | E | deterministic recovery (D-10) |
| P4 | Transaction ownership (one aggregate + outbox per txn; one row per inbox txn) | Platform | Messaging | E | ADR-T1 |
| P5 | Clock ownership on decision paths (time injected) | Platform | Messaging | E | temporal determinism / replay safety |
| P6 | Messaging ownership (Shared messaging references no bounded context) | Platform | Messaging | E | infra must not embed business policy |
| P7 | Registry is the ONLY hydration path (no hardcoded `match`) | Platform | Messaging | E | D-04 / D-09 |
| P8 | Port purity (Application port framework-free) | Platform | Messaging | E | hexagonal inward dependency |
| P9 | Message immutability (readonly carriers) | Platform | Messaging | E | events/messages are facts |
| P10 | Dedupe-key uniqueness `(event_id, consumer_context)` | Platform | Messaging | E | consumer idempotency (D-03) |
| I1 | At-least-once delivery | Infrastructure | substrate | E | relay re-delivers on failure |
| I2 | Effectively-once = at-least-once + idempotent consumer | Infrastructure | Messaging+substrate | E | the platform's core promise |
| I3 | Bounded retry → dead-letter | Infrastructure | Messaging | E | no infinite retry (D-05 policy) |
| I4 | Absolute park deadline (never slides) | Infrastructure | Messaging | E | recovery terminates |
| **C1** | **Anonymity** — no voter↔vote linkage in any event/payload/ledger/log | **Constitutional** | **Constitution / Election** | **P** | Messaging must never introduce, require, or log linkage |
| C2 | Tenant isolation — `organisation_id` scoping; no cross-tenant delivery | Constitutional | Constitution / Election | P | Messaging propagates and must not cross tenants |
| O1 | Retry numbers (`park_retry_minutes`, `park_deadline_minutes`) | Operational | Operations | — | tunable without ADR (D-05) |
| O2 | Redrive / outbox cadence (`everyMinute`) | Operational | Operations | — | tunable |
| O3 | Dead-letter alerting / observability | Operational | Operations | — | future adapter |
| — | Business invariants inside Messaging | Business | (n/a) | — | **none, by design** |

---

## Deliverable 4 — Candidate Architecture Fitness Functions

Only invariants that are **platform-owned (E)** or a **transport preservation obligation (P)** are candidates for a *platform-wide* test. Inbox-only mechanics stay in `InboxMessagingArchitectureTest`.

| Candidate | Maps to | Ownership justification | Platform-wide vs Inbox-specific | Verdict |
|-----------|---------|--------------------------|--------------------------------|---------|
| Messaging ownership across Outbox+Inbox+port | P6 | Messaging owns; it is a whole-Shared property | Platform-wide (spans both halves) | **ADOPT** (after approval) |
| Anonymity preservation across the messaging surface | C1 | Constitution owns; Messaging **preserves** → the transport must introduce no linkage token | Platform-wide, framed as **preservation** (see Q2 on where it should live) | **ADOPT, reframed** |
| Single writer for `outbox_events` | P1 | Messaging owns | Platform-wide (P1 already covered for inbox) | **ADOPT** |
| Registry is the only hydration path (no hardcoded `match`) | P7 | Messaging owns | Platform-wide; complements `EventRegistryCompletenessTest` (which checks completeness, not exclusivity) | **CANDIDATE** (confirm scope) |
| Producer-side port purity (Outbox published-language types) | P8 | depends on whether the Outbox has a formal port | needs Q5 resolved first | **DEFER** |
| Ordering guard | — | not owned | n/a | **REJECT** (explicitly not owned) |

Already in force (Inbox scope, `InboxMessagingArchitectureTest`, 11 properties): P1–P10 + C1 for the consumer half. This deliverable only concerns **extending** the genuinely platform-spanning ones to the producer half.

---

## Deliverable 5 — Recommendation: should `MessagingPlatformArchitectureTest` exist?

**Yes — but narrower and reframed, and only after this model is approved.**

- **Adopt** it for the invariants that genuinely span the whole platform and are Messaging-owned: **P6 (messaging ownership)** and **P1 (single writer)** across Outbox + Inbox + port.
- **Include anonymity (C1) as a PRESERVATION guard**, explicitly labeled "Messaging preserves the constitutional anonymity invariant" — *not* "Messaging owns anonymity." (Open question Q2: it may be better hosted by extending the existing constitutional linkage guard to scan the messaging surface, rather than a Messaging-owned test — because the *owner* is the constitution.)
- **Keep Inbox-specific invariants** (P2/P3/P4/P5/P8/P9 mechanics) in `InboxMessagingArchitectureTest`. They are not platform-spanning.
- **Do not** add an ordering guard (not owned) or a producer-port-purity guard (undecided — Q5).
- **Sequencing:** the earlier attempt to write this test now was premature. Correct order: approve this model → record the resulting decision(s) → RED (falsifiable) → GREEN (minimal guard) → certify → fold into `Messaging_Platform_Architecture.md`.

---

## §6 — Open questions for the ARB (model cannot settle these alone)

- **Q1.** Confirm the anonymity classification: **constitutional** (owner = constitution/Election), **preserved** (not owned) by Messaging. If agreed, all "Messaging owns anonymity" language is corrected to "preserves."
- **Q2.** Where should the anonymity **preservation** guard live — a Messaging platform test, or an *extension of the constitutional linkage guard* (`GreenfieldCoreArchitectureTest`'s `FORBIDDEN_LINKAGE_TOKENS`) to also scan `app/Contexts/Shared` messaging? (Owner-hosts-the-test vs transport-hosts-the-preservation.)
- **Q3.** Confirm **ordering is explicitly not owned** (per-message idempotency only). If a future context needs in-order delivery within a consistency boundary, that is a new ADR, not a silent Messaging feature.
- **Q4.** Is **tenant isolation** constitutional (owned by Election/constitution, preserved by Messaging) as modeled, or should it be a platform invariant? (Proposed: constitutional, preserved.)
- **Q5.** Should the **Outbox have a formal Application port** (hexagonal symmetry with the Inbox), or is `OutboxWriterInterface` its port and `IntegrationEvent` an acceptable infrastructure type? This determines whether a producer-side port-purity fitness function is even meaningful.

**Next step:** on approval of this model (and answers to Q1–Q5), record the derived decision(s) in the Decision Log, then produce `Messaging_Platform_Architecture.md` (AKB Release 1.2) and only then any platform-wide fitness test — RED-first. **PB-004 remains not started.**

# Messaging Platform — Strategic DDD Model (pre-PB-004)

**Status:** REVIEWED & revised (RC-1…RC-6 applied) · pending final ARB sign-off before promotion to AKB 1.2 · **2026-07-07** · reviewer role: Strategic DDD Architect
**Review record:** `Messaging_Platform_Strategic_Model_Review.md` (independent ARB synthesis; Q1–Q5 resolved/deferred there).
**Scope:** model the platform that PB-001 (Event Registry), PB-002 (Relay), PB-003 (Inbox) collectively produced. **No implementation. No executable architecture. No tests.** Deliverables 1–5 below; §6 records the resolved questions.
**Discipline honored:** Business → DDD → Architecture → Tests → Implementation. This document is the DDD layer that must precede any platform-wide fitness test or the `Messaging_Platform_Architecture.md` contract (AKB Release 1.2).

> Correction carried into this model: an earlier draft decision (withdrawn) asserted "Messaging **owns** anonymity, enforced platform-wide." The modeling below shows that is wrong — anonymity is a **constitutional** invariant that Messaging **preserves**. That distinction is the reason this step exists.

---

## §0 — The Business Problem (RC-1: start here, not with Messaging)

The NRNA platform must run **constitutionally trustworthy elections**, which means it must **constitutionally close** them — handle challenges, adjudicate disputes, apply corrections, and reach binding finality — while keeping each part of the system **autonomous and independently trustworthy**.

That requires **constitutionally trustworthy collaboration between autonomous bounded contexts** (Contestation, Adjudication, Election): they must act on each other's outcomes **without** direct calls, shared databases, or any coupling that could let one context corrupt another's integrity or leak a voter's identity. Collaboration must be reliable even when parts fail, and every step must be auditable.

**Messaging is not the business goal — it is the architectural solution to that collaboration problem.** The rest of this document models that solution.

---

## Deliverable 1 — Strategic Model

### What it is
The **Messaging Platform** is the architectural solution to §0: it enables reliable, decoupled collaboration by moving **domain/integration events between bounded contexts**, decoupling producers from consumers in **time** (asynchrony) and in **failure** (one context's crash never corrupts another). It provides at-least-once delivery made **effectively-once** by an idempotent consumer, with recovery — so contexts collaborate through facts (events), never through coupling.

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
| Realized as? | **Shared Infrastructure** + a thin **Shared Kernel / Published Language** | Adapters are Laravel; the port contracts + the integration-event envelope are the shared, pure-PHP language. |

**RC-2 — role vs classification (do not conflate):**
> **Messaging is a *Platform Capability* (its architectural role) implemented using a *Generic Technical Subdomain* (its DDD classification).**

"Platform Capability" says *what it is to the system* — a reusable capability many bounded contexts consume. "Generic Technical Subdomain" says *what it is in DDD terms* — a standard, non-differentiating problem space. The two are orthogonal and must be stated separately.

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

**RC-4 — host of executable architecture (owner-hosts-the-guard):**
- **Platform invariants (P1–P10):** verified & **hosted by the Messaging suite** (Messaging owns → Messaging hosts).
- **Constitutional invariants (C1 anonymity, C2 tenant isolation):** **hosted by the Constitutional suite** — the *owner* hosts the guard. Messaging may *contribute the scan surface* but must not own the test (Q2/Q4).
- **Infrastructure invariants (I1–I4):** exercised by integration/feature tests.
- **Operational invariants (O1–O3):** config/ops, not fitness tests.

> This is why the anonymity guard added during C6B (property #11 in `InboxMessagingArchitectureTest`) is mis-hosted: its owner is the constitution, so it belongs in the constitutional suite. Tracked as **AD-M1** in the review report (documented, not fixed here).

---

## Deliverable 4 — Candidate Architecture Fitness Functions

Only invariants that are **platform-owned (E)** or a **transport preservation obligation (P)** are candidates for a *platform-wide* test. Inbox-only mechanics stay in `InboxMessagingArchitectureTest`.

| Candidate | Maps to | Ownership justification | Platform-wide vs Inbox-specific | Verdict |
|-----------|---------|--------------------------|--------------------------------|---------|
| Messaging ownership across Outbox+Inbox+port | P6 | Messaging owns; it is a whole-Shared property | Platform-wide (spans both halves) | **ADOPT** (after approval) |
| Anonymity across the messaging surface | C1 | Constitution owns; Messaging **preserves** | **Host in the CONSTITUTIONAL suite** (owner-hosts-the-guard), scanning the messaging surface as one of its inputs | **RE-HOST (not a Messaging test) — Q2** |
| Single writer for `outbox_events` | P1 | Messaging owns | Platform-wide (P1 already covered for inbox) | **ADOPT** |
| Registry is the only hydration path (no hardcoded `match`) | P7 | Messaging owns | Platform-wide; complements `EventRegistryCompletenessTest` (which checks completeness, not exclusivity) | **CANDIDATE** (confirm scope) |
| Producer-side port purity (Outbox published-language types) | P8 | depends on whether the Outbox has a formal port | needs Q5 resolved first | **DEFER** |
| Ordering guard | — | not owned | n/a | **REJECT** (explicitly not owned) |

Already in force (Inbox scope, `InboxMessagingArchitectureTest`, 11 properties): P1–P10 + C1 for the consumer half. This deliverable only concerns **extending** the genuinely platform-spanning ones to the producer half.

---

## Deliverable 5 — Recommendation: should `MessagingPlatformArchitectureTest` exist?

**Yes — but narrower and reframed, and only after this model is approved.**

- **Adopt** it for the invariants that genuinely span the whole platform and are Messaging-owned: **P6 (messaging ownership)** and **P1 (single writer)** across Outbox + Inbox + port.
- **Do NOT host anonymity (C1) in a Messaging test.** Per Q2, the constitutional suite owns and hosts that guard (extend the existing constitutional linkage scan to include the messaging surface). Messaging *contributes the surface*; it does not own the guarantee. The C6B property #11 must be **relocated** accordingly (AD-M1).
- **Keep Inbox-specific invariants** (P2/P3/P4/P5/P8/P9 mechanics) in `InboxMessagingArchitectureTest`. They are not platform-spanning.
- **Do not** add an ordering guard (not owned) or a producer-port-purity guard (undecided — Q5).
- **Sequencing:** the earlier attempt to write this test now was premature. Correct order: approve this model → record the resulting decision(s) → RED (falsifiable) → GREEN (minimal guard) → certify → fold into `Messaging_Platform_Architecture.md`.

---

## §6 — Resolutions (RC-6 — ARB-ruled; full reasoning in the review report)

- **Q1 — RESOLVED.** Anonymity is **constitutional** (owner = constitution/Election); Messaging **preserves** it. All "owns anonymity" language corrected to "preserves."
- **Q2 — RESOLVED.** The anonymity guard is **hosted by the constitutional suite** (owner-hosts-the-guard) — extend the constitutional linkage scan to include the messaging surface. Messaging contributes the surface, not the test. → **AD-M1** (relocate C6B property #11).
- **Q3 — RESOLVED.** Ordering is **explicitly not owned**. Any future in-order need within a consistency boundary is a **new ADR**, never a hidden Messaging feature.
- **Q4 — RESOLVED.** Tenant isolation is **constitutional** — Election owns, Messaging **preserves**; its guard is hosted by the constitutional/Election suite.
- **Q5 — DEFERRED (Architecture Debt AD-M2 / Future ADR).** Outbox is stable and PB-004 does not require changing it; do not add a formal producer port without business pressure.

**Next step:** on final ARB sign-off, record the derived decision(s) in the Decision Log, then produce `Messaging_Platform_Architecture.md` (AKB Release 1.2), and only then any executable architecture — RED-first, hosted by the correct owner. Architecture debt: **AD-M1** (relocate anonymity guard), **AD-M2** (outbox port symmetry). **PB-004 remains not started.**

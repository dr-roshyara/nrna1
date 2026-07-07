# Messaging Platform — Architecture

**AKB layer:** L-Platform-Capabilities (first instance) · **Release tag:** *pending ARB ratification* — "AKB 1.2" collides with the AKB roadmap §4; see Governance Integration Report **G-1**.
**Status:** Instance #1 of the Platform Capability pattern (`patterns/Platform_Capability_Pattern.md`) · **FROZEN** (D-13; change only via ADR + review) · **2026-07-07**. Executable architecture now proceeds implementation-driven (AD-M1 first).
**Traceability:** `Messaging_Platform_Strategic_Model.md` · `..._Review.md` · principles `principles/Platform_Governance_Principles.md` (PGP-01…05) · decisions `../adr/ADR-MP-Messaging-Platform.md` (ADR-MP-01…05) · Blueprint §6/§7/§8 · ADR-T1/T3/T4/T5/T8/T11/T16.
**Constraint:** this document is design + planning only. No production code, no tests. §9 *plans* the executable architecture; it does not implement it.

---

## 1. Purpose
The **Messaging Platform** is the reusable Platform Capability that lets NRNA's autonomous bounded contexts collaborate through events — reliably, decoupled, and auditable — so no context reinvents delivery and none can corrupt or de-anonymise another.

## 2. Business Problem
NRNA must run **constitutionally trustworthy elections**, which means constitutionally *closing* them (challenge → adjudicate → correct → resolve) while every context stays **autonomous and independently trustworthy**. That demands **trustworthy collaboration between autonomous bounded contexts** with no direct calls, no shared database, no coupling that could leak a voter's identity or let one context corrupt another. **Messaging is the architectural solution to that problem — not the business goal.**

## 3. Strategic Context
Consumers: **Contestation, Adjudication, Election** (and any future context). Integration pattern: **Customer/Supplier + Published Language** — consumers are customers; the platform is the supplier; the *event envelope* (produced) and the `InboxHandler` port (consumed) are the published language. Each consumer performs its own anti-corruption inside its handler, reconstructing **local** value objects from string identifiers (ADR-T16).

## 4. DDD Classification
**Platform Capability** (architectural role) **implemented using a Generic Technical Subdomain** (DDD classification) — stated separately, never conflated (ADR-MP-01). Realized as **Shared Infrastructure** + a thin **Shared Kernel / Published Language**. Not a bounded context; not a core/business subdomain; no business decisions.

## 5. Architectural Role & Boundaries
**Owns:** registration/hydration (Registry), delivery (Relay), deduplication/consumption (Inbox), execution, recovery, transport audit ledger, retry policy, id propagation mechanism.
**Never owns:** business decisions, constitutional invariants (anonymity, tenant isolation — *preserved*), cross-message ordering, operational values, remediation/replay policy.

## 6. Ownership Matrix
Authoritative assignment (ADR-MP-02); disposition vocabulary per template §3.

| Capability | Disposition | Owner (if not Messaging) |
|------------|-------------|--------------------------|
| Registry / Relay / Inbox / Execution / Recovery | **Owns** | — |
| Retry policy (bounded→dead-letter) | **Owns** | — |
| Retry numbers · scheduling cadence | **Coordinates** | Operations |
| Transport audit ledger (`inbox_events`/`outbox_events`) | **Owns** | — |
| Correlation/causation propagation | **Owns** (carriage) | semantics: business |
| Tenant id propagation | **Preserves** | Election / constitution |
| **Anonymity** | **Preserves** | Constitution / Election (ADR-MP-04) |
| Cross-message ordering | **Does NOT own** | (future ADR — ADR-MP-05) |
| Replay mechanism | **Owns** | policy: Operations/context |
| Monitoring / alerting | **Observes** | Operations |
| Business decisions/policy | **Does NOT own** | each bounded context |

## 7. Invariant Catalog
Class · owner · Messaging role (E=enforce / P=preserve) · **host of executable architecture** (owner-hosts-the-guard, ADR-MP-03).

| ID | Invariant | Class | Owner | Role | Host |
|----|-----------|-------|-------|------|------|
| P1 | Single writer per store | Platform | Messaging | E | Messaging suite |
| P2 | Exactly one execution owner | Platform | Messaging | E | Messaging suite |
| P3 | Exactly one recovery owner | Platform | Messaging | E | Messaging suite |
| P4 | Transaction ownership (ADR-T1) | Platform | Messaging | E | Messaging suite |
| P5 | Clock ownership on decision paths | Platform | Messaging | E | Messaging suite |
| P6 | Messaging references no bounded context | Platform | Messaging | E | Messaging suite |
| P7 | Registry is the only hydration path | Platform | Messaging | E | Messaging suite |
| P8 | Port purity | Platform | Messaging | E | Messaging suite |
| P9 | Message immutability | Platform | Messaging | E | Messaging suite |
| P10 | Dedupe-key uniqueness (D-03) | Platform | Messaging | E | Messaging suite |
| I1–I4 | At-least-once · effectively-once · bounded-retry · absolute deadline | Infrastructure | Messaging/substrate | E | integration/feature tests |
| C1 | Anonymity (no voter↔vote linkage) | Constitutional | Constitution/Election | P | **Constitutional suite** |
| C2 | Tenant isolation | Constitutional | Election | P | **Constitutional/Election suite** |
| O1–O3 | Retry numbers · cadence · alerting | Operational | Operations | — | config/ops |

## 8. Extension Model (Open/Closed)
A new consumer is added **without modifying Shared**: implement an `InboxHandler` in the consuming context, register it into the `InboxHandlerRegistry` from that context's own service provider (keyed by `(consumer_context, event_type)`). A new producer registers an `EventHydrator` into the `EventHydratorRegistry` likewise. The capability is closed for modification, open for registration. Adding a fifth or sixth context requires **registration only**.

## 9. Architecture Fitness Functions — TDD plan (Step 4; plan only, no code)
Each executable guarantee: **owner · host · RED scenario · GREEN expectation · regression suite.** Existing = live in `InboxMessagingArchitectureTest` (inbox scope). Planned = to be written RED-first *after* sign-off, hosted per ADR-MP-03.

| Guarantee (invariant) | Host | RED scenario | GREEN expectation | Status |
|-----------------------|------|--------------|-------------------|--------|
| Messaging references no bounded context (P6) — **platform-wide** (Outbox+Inbox+port) | Messaging suite | a Shared messaging file imports/references `App\Contexts\<X>\` (X≠Shared) | no such reference across the whole surface | **Planned** (inbox-only today) |
| Single writer per store (P1) — `outbox_events` too | Messaging suite | a writer of the store outside its package | store mutated only within its package | **Planned** (inbox covered) |
| Exactly one execution owner (P2) — app-wide | Messaging suite | a 2nd marker-classifier anywhere | exactly one classifier | Existing (app-wide) |
| Exactly one recovery owner (P3) — app-wide | Messaging suite | a 2nd retry scheduler | exactly one | Existing (app-wide) |
| Transaction ownership (P4) | Messaging suite | executor opens a transaction | executor opens none | Existing |
| Clock ownership (P5) | Messaging suite | ambient time on a decision path | injected time only | Existing |
| Registry is only hydration path (P7) | Messaging suite | a hardcoded hydration `match` reappears | registry is sole path | **Planned** (complements `EventRegistryCompletenessTest`) |
| Port purity (P8) / Message immutability (P9) | Messaging suite | framework import in port / mutable carrier | pure / readonly | Existing (inbox port) |
| Dedupe-key uniqueness (P10) | Messaging suite | duplicate `(event_id, consumer_context)` accepted | UNIQUE rejects it | Existing (persistence test) |
| **Anonymity (C1)** across the messaging surface | **Constitutional suite** | a linkage token appears in any messaging file | none | **Planned — via AD-M1** (relocate C6B property #11 here) |
| **Tenant isolation (C2)** | **Constitutional/Election suite** | cross-tenant delivery / missing tenant id | isolation preserved | **Planned** |

Regression suite for every guarantee: full Architecture Fitness suite + greenfield PHPStan + the relevant Inbox/Outbox feature tests, all green, before certification.

## 10. Operational Model
**Failure model:** transient → transaction rollback, retried next tick; causal-precondition-missing → **park** (retry with an **absolute, preserved deadline**); permanent → **dead-letter** with reason; unregistered handler/type → dead-letter. **Recovery:** scheduled re-drive re-invokes the same handler with the same message (recovery can only reach outcomes normal delivery can). **Scheduling:** re-drive + outbox processing on a cadence (Operational, tunable). **Observability:** status/attempt columns + structured dead-letter logs are queryable per tenant; active metrics/alerting is future (Observes). **Tunable vs architectural:** policy (bounded-retry→dead-letter, absolute deadline) is architectural; counts/windows/cadence are operational.

## 11. ADR Traceability
Produces: **ADR-MP-01…05**. Realizes: ADR-T1 (txn), ADR-T3/T5 (outbox/versioning), ADR-T4 (inbox idempotency), ADR-T8 (forward-only, no saga), ADR-T11 (anonymity — preserved), ADR-T16 (identities cross as strings). Decision-Log D-03/D-05/D-06/D-08/D-09/D-10/D-11 realized; D-12 → split into ADR-MP series.

## 12. Evolution Model
- **New consumers/producers:** registration only (§8) — no redesign.
- **Replay:** reuses the execution seam (`execute(row, message, handler)`); add a replay driver + policy owner; no platform change.
- **Monitoring/metrics/alerting:** add an observability adapter reading the ledger/logs; business code untouched.
- **Deferred (tracked debt):** **AD-M1** relocate the anonymity guard to the constitutional suite (first consumer of ADR-MP-03 governance); **AD-M2** decide the Outbox formal Application port (ADR-MP-05) — only under business pressure.

---

## §13 — Exit Review (Step 5)
| Exit criterion | Status |
|----------------|--------|
| Platform Capability pattern is reusable (capability-agnostic) | ✔ `patterns/Platform_Capability_Pattern.md` |
| Messaging is the first specialization (instance of the template) | ✔ this document |
| Every invariant has exactly one owner | ✔ §7 |
| Every executable guard has exactly one host (owner-hosts-the-guard) | ✔ §7/§9 (ADR-MP-03) |
| Every deferred decision is tracked | ✔ AD-M1, AD-M2 (ADR-MP-05) |
| No implementation assumptions remain in the strategic layer | ✔ (RC-1…RC-6 applied) |
| Decisions are single-question ADRs, not bundled | ✔ ADR-MP-01…05 |

**Gate:** on final ARB sign-off, executable architecture may begin — **RED-first**, hosted per ADR-MP-03, starting with **AD-M1** (relocate the anonymity guard to the constitutional suite) as the *first consumer of this governance*. **PB-004 remains not started** and, when it begins, needs its own IDD.

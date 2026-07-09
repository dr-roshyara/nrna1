# ADR-MP — Messaging Platform (decision series)

**Status:** Accepted · **2026-07-07** · supersedes the bundled Decision-Log entry D-12 (now a pointer).
**Sources (traceability):** `../architecture/Messaging_Platform_Strategic_Model.md` · `..._Review.md` · pattern `../architecture/patterns/Platform_Capability_Pattern.md` · Blueprint §6/§7/§8 · ADR-T3/T4/T5/T8/T11/T16.
**Rule honored:** one architectural question per ADR (D-12 was too large; split here).
**Principles applied:** each ADR below *applies* a **Platform Governance Principle** (`../architecture/principles/Platform_Governance_Principles.md`). Principles endure; these ADRs are their first application (to Messaging).

---

## ADR-MP-01 — Messaging Platform definition

**Applies:** PGP-01 (Platform Capability: role vs classification).
**Question:** What is the messaging subsystem, architecturally and in DDD terms?
**Decision:** Messaging is a **Platform Capability** (architectural role) **implemented using a Generic Technical Subdomain** (DDD classification), realized as Shared Infrastructure + a thin Shared Kernel / Published Language. It is **not** a bounded context, **not** a core/business subdomain, and holds **no business decisions**. PB-001 (Registry) + PB-002 (Relay) + PB-003 (Inbox) are its constituent parts, governed henceforth as one capability.
**Consequences:** PB-004+ reference the platform document, not three ticket docs. Role and classification are stated separately, never conflated.

---

## ADR-MP-02 — Ownership model

**Applies:** PGP-02 (Single ownership).
**Question:** How is responsibility for the platform's capabilities assigned?
**Decision:** Every responsibility has **exactly one owner**, expressed with the disposition vocabulary **Owns / Coordinates / Preserves / Observes / Does-NOT-own** (see pattern `../architecture/patterns/Platform_Capability_Pattern.md` §3). The authoritative assignment is the Ownership Matrix in `../architecture/Messaging_Platform_Architecture.md`.
**Consequences:** ambiguous ownership is a modeling defect that blocks promotion. Operations owns tunable values (Coordinates); business semantics never enter the platform.

---

## ADR-MP-03 — Executable-architecture ownership (owner-hosts-the-guard)

**Applies:** PGP-03 (Owner-hosts-the-guard).
**Question:** Which test suite hosts the executable guard for a given invariant?
**Decision:** **The guard is hosted by the suite owned by the invariant's owner.** Platform/Infrastructure invariants → the Messaging fitness suite. Constitutional invariants → the constitutional suite. Operational → config/ops. A preserver must never host an owner's guarantee.
**Consequences:** the anonymity guard added during C6B (property #11 in `InboxMessagingArchitectureTest`) is mis-hosted and must be relocated to the constitutional suite → tracked as **AD-M1**. This principle is generalizable beyond Messaging (candidate to hoist into the AKB as a global rule).

---

## ADR-MP-04 — Constitutional preservation (anonymity, tenant isolation)

**Applies:** PGP-04 (Constitutional preservation).
**Question:** Does Messaging own anonymity and tenant isolation, or preserve them?
**Decision:** Both are **Constitutional invariants owned by the constitution / Election**; Messaging **preserves** them — it must never introduce, require, or log voter↔vote linkage, and must never cross tenants. Their executable guards are hosted by the constitutional suite (per ADR-MP-03).
**Consequences:** all "Messaging owns anonymity" language is corrected to "preserves." The owner can evolve these invariants without touching the platform. Traceability: ADR-T11 (anonymity), multi-tenancy (tenant isolation).

---

## ADR-MP-05 — Deferred decisions

**Applies:** PGP-05 (Deferred evolution is explicit).
**Question:** Which platform questions are consciously left open?
**Decision:** (a) **Cross-message ordering is explicitly NOT owned** — delivery is per-message idempotent only; any future in-order-within-a-consistency-boundary need is a **new ADR**, never a hidden feature. (b) **Outbox formal Application port / hexagonal symmetry is deferred** (Outbox is stable; PB-004 does not require changing it) → tracked as **AD-M2**; revisit only under business pressure.
**Consequences:** no un-sanctioned guarantees; no speculative redesign. Both items are tracked debt, not silent gaps.

---

## ADR-MP-06 — Integration Event Dispatcher (delivery capability)

**Status:** Accepted (ARB, 2026-07-10 — authorized under the R-29 escape clause). · **Applies:** PGP-01/PGP-02 (platform capability; single ownership) · ADR-T1/T4/T16 · D-06.
**Question:** How does a published integration event travel from a producer's outbox to every consuming context's inbox?
**Evidence (F-PB006-1, PB-006 Discovery):** it doesn't — the relay dispatches `IntegrationEvent` onto the Laravel event bus and stops; `Inbox::consume` has **zero** production callers; handler **registration ≠ delivery**. PB-006 (a feature) thereby demonstrated the frozen platform insufficient — precisely the sanctioned evolution path.
**Decision:** Add **`IntegrationEventDispatcher`** — a **Shared Messaging Platform capability** (owner: Messaging Platform; the **Inbox does NOT own delivery — it is one consumer**). **Design principle (permanent): Registration ≠ Delivery.** It closes the loop with four explicit, separately-testable responsibilities:
1. **Receive** the relay's output (`IntegrationEvent`).
2. **Consumer Discovery** — the dispatcher depends on a **`ConsumerResolver`** capability (Messaging-owned port), NOT on `InboxHandlerRegistry` directly; the registry is the implementation *behind* the resolver. Discovery returns **all** consumers for the event type.
3. **Inbox Message Creation** — one `InboxMessage` per consumer, propagating event identity, type, payload, **organisation** (ADR-T16), **correlation/causation** (D-06) from the outbox record.
4. **Delivery** — `Inbox::consume($message, $handler)` per consumer, with **consumer isolation**: one consumer's park/dead-letter/failure never blocks another consumer or the relay.
**Deterministic routing (explicit definition):** given an identical `IntegrationEvent` and identical registry state, discovery yields the **identical ORDERED consumer set**. **Ordering rule:** consumers are ordered by a **stable consumer identifier** — the architectural contract is *stability*, not any particular comparison. The current implementation uses `consumerContext()` ascending; if the stable identifier ever changes, the contract survives. **Ordering is deterministic but carries NO business meaning — consumers must never depend on execution order** (each consumer is causally independent; ordering exists only for reproducibility).
**Transient-failure rationale (explicit):** a transient Throwable is re-thrown only **after all consumers have been attempted**, because the relay's retry exists to guarantee **eventual delivery for the failed consumer**, while **successfully completed consumers remain protected by inbox idempotency** (dedupe) on redelivery. Failing fast on the first consumer would deny the remaining consumers their delivery attempt for no gain.
**Consumer atomicity (explicit):** each consumer delivery executes **independently in its own atomic inbox transaction** (`Inbox::consume`'s transaction). Failure during one consumer's inbox-message creation or consumption must not affect any other consumer; **no partially-created inbox state may leak across consumers**.
**Invariants (Trustworthiness — the dispatcher joins the trusted computing base):** deterministic ordered routing (above) · replay safety (redelivery lands on inbox dedupe; the dispatcher holds no state) · tenant propagation/isolation · consumer isolation · causal-ordering preservation (premature → park, via the inbox's own semantics) · correlation/causation propagation · event-identity propagation (dedupe preserved) · **audit continuity: every consumed `InboxMessage` is traceable to exactly ONE originating `OutboxEvent` (shared `event_id`)** — the constitutional audit chain has no discontinuity. No business decisions (ADR-MP-01); no changes to Outbox/Relay/Inbox contracts; the registry gains only an **additive** discovery query.
**Constitutional Audit Invariant (ARB, 2026-07-10 — realized by `EventProvenance`):** a constitutional decision is not represented by a single event but by a chain of causally related events. Exactly ONE producer mints a CorrelationId per constitutional conversation; every subsequent producer propagates it UNCHANGED (no second mint within a conversation); every publication after the first records its IMMEDIATE causal predecessor as the CausationId. **Correlation identifies the conversation, not the event; Causation identifies the direct parent, not the chain.** Together they create a replay-safe, end-to-end audit chain independent of any bounded context. Reacting producers use a deterministic fallback (absent incoming correlation ⇒ correlation := triggering event id) to preserve replay safety.
**D-1 (approved):** the `IntegrationEvent` envelope is **additively** extended with `CorrelationId`/`CausationId` (they already exist on `outbox_events`; messaging concerns, never domain-event fields).
**D-2 (approved):** the dispatcher is wired as a **listener** on the relay's dispatched `IntegrationEvent` — the relay remains **byte-identical**; the dispatcher is simply another consumer of relay output.
**Consequences:** IT-1/IT-2 validate the REAL production path; hand-stitched test delivery (rejected Option B) is retired for E2E tests. An empty consumer set is a no-op, not an error. Dispatcher-run failure is retried by the relay's existing redelivery semantics.

---

### Relationship to the Decision Log
Decision-Log **D-12** is retained as a **pointer** to this ADR-MP series (the log records that the decision was made and split; the ADRs hold the authoritative, single-question records).

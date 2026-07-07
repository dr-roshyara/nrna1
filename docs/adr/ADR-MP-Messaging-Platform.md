# ADR-MP — Messaging Platform (decision series)

**Status:** Accepted · **2026-07-07** · supersedes the bundled Decision-Log entry D-12 (now a pointer).
**Sources (traceability):** `Messaging_Platform_Strategic_Model.md` · `Messaging_Platform_Strategic_Model_Review.md` · `Platform_Capability_Template.md` · Blueprint §6/§7/§8 · ADR-T3/T4/T5/T8/T11/T16.
**Rule honored:** one architectural question per ADR (D-12 was too large; split here).

---

## ADR-MP-01 — Messaging Platform definition

**Question:** What is the messaging subsystem, architecturally and in DDD terms?
**Decision:** Messaging is a **Platform Capability** (architectural role) **implemented using a Generic Technical Subdomain** (DDD classification), realized as Shared Infrastructure + a thin Shared Kernel / Published Language. It is **not** a bounded context, **not** a core/business subdomain, and holds **no business decisions**. PB-001 (Registry) + PB-002 (Relay) + PB-003 (Inbox) are its constituent parts, governed henceforth as one capability.
**Consequences:** PB-004+ reference the platform document, not three ticket docs. Role and classification are stated separately, never conflated.

---

## ADR-MP-02 — Ownership model

**Question:** How is responsibility for the platform's capabilities assigned?
**Decision:** Every responsibility has **exactly one owner**, expressed with the disposition vocabulary **Owns / Coordinates / Preserves / Observes / Does-NOT-own** (see `Platform_Capability_Template.md` §3). The authoritative assignment is the Ownership Matrix in `Messaging_Platform_Architecture.md`.
**Consequences:** ambiguous ownership is a modeling defect that blocks promotion. Operations owns tunable values (Coordinates); business semantics never enter the platform.

---

## ADR-MP-03 — Executable-architecture ownership (owner-hosts-the-guard)

**Question:** Which test suite hosts the executable guard for a given invariant?
**Decision:** **The guard is hosted by the suite owned by the invariant's owner.** Platform/Infrastructure invariants → the Messaging fitness suite. Constitutional invariants → the constitutional suite. Operational → config/ops. A preserver must never host an owner's guarantee.
**Consequences:** the anonymity guard added during C6B (property #11 in `InboxMessagingArchitectureTest`) is mis-hosted and must be relocated to the constitutional suite → tracked as **AD-M1**. This principle is generalizable beyond Messaging (candidate to hoist into the AKB as a global rule).

---

## ADR-MP-04 — Constitutional preservation (anonymity, tenant isolation)

**Question:** Does Messaging own anonymity and tenant isolation, or preserve them?
**Decision:** Both are **Constitutional invariants owned by the constitution / Election**; Messaging **preserves** them — it must never introduce, require, or log voter↔vote linkage, and must never cross tenants. Their executable guards are hosted by the constitutional suite (per ADR-MP-03).
**Consequences:** all "Messaging owns anonymity" language is corrected to "preserves." The owner can evolve these invariants without touching the platform. Traceability: ADR-T11 (anonymity), multi-tenancy (tenant isolation).

---

## ADR-MP-05 — Deferred decisions

**Question:** Which platform questions are consciously left open?
**Decision:** (a) **Cross-message ordering is explicitly NOT owned** — delivery is per-message idempotent only; any future in-order-within-a-consistency-boundary need is a **new ADR**, never a hidden feature. (b) **Outbox formal Application port / hexagonal symmetry is deferred** (Outbox is stable; PB-004 does not require changing it) → tracked as **AD-M2**; revisit only under business pressure.
**Consequences:** no un-sanctioned guarantees; no speculative redesign. Both items are tracked debt, not silent gaps.

---

### Relationship to the Decision Log
Decision-Log **D-12** is retained as a **pointer** to this ADR-MP series (the log records that the decision was made and split; the ADRs hold the authoritative, single-question records).

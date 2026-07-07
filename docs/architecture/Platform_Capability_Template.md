# Platform Capability — Meta-Model & Template

**Status:** AKB artifact (Release 1.2 candidate) · **2026-07-07** · reusable governance pattern
**Purpose:** define what a **Platform Capability** *is* and how one is documented, governed, and evolved — **before** any specific capability is written. Messaging is the first instantiation (`Messaging_Platform_Architecture.md`); future capabilities (Replay, Monitoring, Notifications, Identity, Search, …) reuse this same template.

> **Why this exists (generalize before specializing).** The NRNA program repeatedly produces reusable infrastructure by completing several tickets. Left as tickets, that infrastructure has the wrong abstraction level and no stable contract. This template captures the *enduring concept* (a Platform Capability) so every such capability gets a consistent, DDD-sound governance frame — architecture describing lasting concepts, not individual implementations.

---

## 1. What a Platform Capability is

A **Platform Capability** is a **reusable architectural capability consumed by multiple bounded contexts**, owned centrally so no context reinvents it. It is an **architectural role**, stated independently of its **DDD classification**:

- **Architectural role:** Platform Capability (what it is to the system).
- **DDD classification:** usually a **Generic / Supporting Technical Subdomain** (what it is in DDD terms), realized as **Shared Infrastructure** + a thin **Shared Kernel / Published Language**.

These two axes are **orthogonal — never conflate them.** A capability is *a Platform Capability implemented using a {classification}*.

A Platform Capability is **not** a bounded context, **not** a core/business subdomain, and holds **no business decisions**.

---

## 2. Required sections (every capability instance MUST fill these)

Each instance document (`<Name>_Platform_Architecture.md`) has exactly these sections, in order:

1. **Purpose** — one paragraph: what the capability is, for whom.
2. **Business Problem** — the *business/constitutional* need it serves. Start here, never with the technology. The capability is the *solution*, not the goal.
3. **Strategic Context** — where it sits in the domain landscape; which contexts consume it; the integration pattern (typically Customer/Supplier + Published Language).
4. **DDD Classification** — bounded context? core/generic/supporting subdomain? realized as? (state role vs classification separately, per §1).
5. **Architectural Role** — Platform Capability; its boundaries (§6 below).
6. **Ownership Matrix** — one owner per responsibility, using the disposition vocabulary (§3).
7. **Invariant Catalog** — every invariant classified (§4), each with owner · preserver · verifier · **host of executable architecture** (§5).
8. **Extension Model** — how a new consumer is added **without modifying the capability** (Open/Closed): the extension point(s) and the registration mechanism.
9. **Architecture Fitness Functions** — the executable guarantees, each mapped to an invariant and hosted by its owner (§5); with RED/GREEN/regression planning.
10. **Operational Model** — failure model (retry/park/dead-letter/replay), recovery, scheduling, observability; what is tunable vs architectural.
11. **ADR Traceability** — every ADR the capability realizes or produces.
12. **Evolution Model** — how the capability grows (new consumers, replay, monitoring, metrics, alerting) **without redesign**; deferred decisions tracked as debt/future ADRs.

---

## 3. Ownership disposition vocabulary (use in every Ownership Matrix)

| Disposition | Meaning |
|-------------|---------|
| **Owns** | implements it and is accountable for it |
| **Coordinates** | owns the *mechanism*; another party owns the *policy/values* |
| **Preserves** | does not own it; must never violate it (it belongs to another owner) |
| **Observes** | emits signals only; owns no decision |
| **Does NOT own** | explicitly out of scope; named owner elsewhere (or "none") |

**Rule:** every responsibility has **exactly one owner**. Ambiguity is a modeling defect.

---

## 4. Invariant classification taxonomy (use in every Invariant Catalog)

| Class | Owned by | The capability's role |
|-------|----------|-----------------------|
| **Platform** | the capability | **Enforces** |
| **Infrastructure** | the capability / substrate | **Enforces** |
| **Constitutional** | the constitution / a business context | **Preserves** (must not violate) |
| **Business** | a bounded context | **none inside the capability, by design** |
| **Operational** | Operations | tunable; not a fitness test |

---

## 5. Executable-architecture governance: **owner-hosts-the-guard**

> **The suite that hosts an invariant's executable guard is the suite owned by the invariant's owner.**

- **Platform / Infrastructure invariants** → hosted by the **capability's own** fitness suite (it owns them).
- **Constitutional invariants** → hosted by the **constitutional suite** (the owner hosts the guard). The capability may *contribute the scan surface* but must **not** host a guarantee it merely preserves.
- **Operational invariants** → config/ops, not fitness tests.

A preserver hosting an owner's guarantee is an **ownership mismatch** (architecture debt) — record it, then relocate the guard to the owner's suite via the discipline in §6.

---

## 6. Process discipline (applies to every capability)

- **Ordering:** Business → DDD → Architecture → Tests → Implementation. Model before test; test before code.
- **For a discovered gap:** `Finding → Architecture Decision (ADR) → RED → GREEN → Certification`. The decision is a **separate artifact** and **precedes** the architecture doc and the test.
- **One decision per ADR** — never bundle independent governance decisions into one record.
- **Generalize before specializing** — capture the pattern (this template), then instantiate.
- **Architecture produces tickets; tickets never accrete into architecture.**

---

## 7. How to instantiate

1. Copy §2's twelve sections into `<Name>_Platform_Architecture.md`.
2. Fill each from the capability's **Strategic Model** + **Architecture Review** (do not invent — everything traces to model/review/ADR/Blueprint).
3. Record the capability's decisions as one-question ADRs (see the Messaging series `ADR-MP-01…05` as the reference example).
4. Plan (do not write) the fitness functions per §5, then — only after exit review — implement RED → GREEN.

**Reference instance:** `Messaging_Platform_Architecture.md` (AKB Release 1.2), the first Platform Capability.

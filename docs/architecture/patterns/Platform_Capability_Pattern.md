# Platform Capability — Architecture Pattern

**AKB layer:** L-Patterns (Architecture Patterns) · **Status:** reusable pattern · **2026-07-07**
**Purpose:** define what a **Platform Capability** *is* and how one is documented, governed, and evolved — **capability-agnostic**. Any platform capability (Messaging, Replay, Monitoring, Notifications, Identity, Search, …) instantiates this pattern. The first instance is the Messaging Platform.

> **Why this exists (generalize before specializing).** The program repeatedly produces reusable infrastructure by completing several tickets. Left as tickets, that infrastructure sits at the wrong abstraction level with no stable contract. This pattern captures the *enduring concept* so every capability gets a consistent, DDD-sound governance frame — architecture describing lasting concepts, not individual implementations.

**Governing principles:** this pattern operationalizes the **Platform Governance Principles** (`../principles/Platform_Governance_Principles.md`, PGP-01…05), under the **Architecture Principles (Release 1.0)** constitution.

---

## 1. What a Platform Capability is

A **Platform Capability** is a **reusable architectural capability consumed by multiple bounded contexts**, owned centrally so no context reinvents it. State it on two orthogonal axes — **never conflate them** (PGP-01):

- **Architectural role:** *Platform Capability* (what it is to the system).
- **DDD classification:** what it is in DDD terms (typically a Generic/Supporting Technical Subdomain), realized as Shared Infrastructure + a thin Shared Kernel / Published Language.

A Platform Capability is **not** a bounded context, **not** a core/business subdomain, and holds **no business decisions**.

---

## 2. Required sections (every capability instance MUST fill these)

Each instance document (`<Name>_Platform_Architecture.md`) contains exactly these sections, in order. Section titles are **normative**; the parenthetical prompts are informative.

1. **Purpose** — what the capability is, for whom.
2. **Business Problem** — the business/constitutional need it serves. Start here, never with the technology; the capability is the *solution*, not the goal.
3. **Strategic Context** — where it sits in the domain landscape; which contexts consume it; the integration pattern.
4. **DDD Classification** — role vs classification, stated separately (§1).
5. **Architectural Role & Boundaries** — what it owns and what it never owns.
6. **Ownership Matrix** — one owner per responsibility, using the disposition vocabulary (§3).
7. **Invariant Catalog** — every invariant classified (§4), each with owner · preserver · verifier · **host of executable architecture** (§5).
8. **Extension Model** — how a new consumer is added **without modifying the capability** (Open/Closed): the extension point(s) and registration mechanism.
9. **Architecture Fitness Functions** — the executable guarantees, each mapped to an invariant and hosted by its owner (§5); planned RED/GREEN/regression before implementation.
10. **Operational Model** — the capability's **Failure Model**, **Recovery Model**, **Scheduling/Execution Model**, and **Observability Model** — described as *categories*; state what is architectural vs tunable. *(Informative note: capabilities fill these differently — a delivery capability may use retry/park/dead-letter/replay; a query capability may use timeout/fallback/cache-invalidation; a notification capability may use fan-out/suppression. The pattern mandates the categories, not any specific semantics.)*
11. **ADR Traceability** — every ADR the capability realizes or produces, and the PGP principles it applies.
12. **Evolution Model** — how it grows without redesign; deferred decisions tracked as debt/future ADRs.

---

## 3. Ownership disposition vocabulary (every Ownership Matrix) — PGP-02

| Disposition | Meaning |
|-------------|---------|
| **Owns** | implements it and is accountable for it |
| **Coordinates** | owns the *mechanism*; another party owns the *policy/values* |
| **Preserves** | does not own it; must never violate it (belongs to another owner) |
| **Observes** | emits signals only; owns no decision |
| **Does NOT own** | explicitly out of scope; named owner elsewhere (or "none") |

**Rule:** every responsibility has **exactly one owner**. Ambiguity is a modeling defect.

---

## 4. Invariant classification taxonomy (every Invariant Catalog)

| Class | Owned by | The capability's role |
|-------|----------|-----------------------|
| **Platform** | the capability | **Enforces** |
| **Infrastructure** | the capability / substrate | **Enforces** |
| **Constitutional** | the constitution / a business context | **Preserves** (must not violate) |
| **Business** | a bounded context | **none inside the capability, by design** |
| **Operational** | Operations | tunable; not a fitness test |

---

## 5. Executable-architecture governance: **owner-hosts-the-guard** (PGP-03)

> **The suite that hosts an invariant's executable guard is the suite owned by the invariant's owner.**

- **Platform / Infrastructure invariants** → hosted by the **capability's own** fitness suite.
- **Constitutional invariants** → hosted by the **owner's** (constitutional) suite. The capability may *contribute the scan surface* but must **not** host a guarantee it merely preserves.
- **Operational invariants** → config/ops, not fitness tests.

A preserver hosting an owner's guarantee is an **ownership mismatch** (architecture debt) — record it, then relocate via §6.

---

## 6. Process discipline (every capability)

- **Ordering:** Business → DDD → Architecture → Tests → Implementation. Model before test; test before code.
- **For a discovered gap:** `Finding → Architecture Decision (ADR) → RED → GREEN → Certification`. The decision is a **separate artifact** and **precedes** the architecture doc and the test.
- **One decision per ADR.** **Generalize before specializing.** **Architecture produces tickets; tickets never accrete into architecture** (PGP-01/PGP-05).

---

## 7. How to instantiate

1. Copy §2's twelve sections into `<Name>_Platform_Architecture.md` under the AKB **Platform Capabilities** layer.
2. Fill each from the capability's **Strategic Model** + **Architecture Review** — invent nothing; trace to model/review/ADR/Blueprint.
3. Record the capability's decisions as one-question ADRs that reference the PGP principles (see the Messaging series `ADR-MP-01…05`).
4. Plan (do not write) the fitness functions per §5, then — only after exit review — implement RED → GREEN.

**Reference instance:** `../Messaging_Platform_Architecture.md`, the first Platform Capability.

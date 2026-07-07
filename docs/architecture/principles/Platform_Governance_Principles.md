# Platform Governance Principles (PGP)

**AKB layer:** L-Principles (Architecture Principles) · **Status:** enduring principles (Living register) · **2026-07-07**
**Scope:** governance of **Platform Capabilities** (reusable infrastructure consumed by multiple bounded contexts).
**Relationship to existing principles:** these operate *under* the **Architecture Principles (Release 1.0)** software-architecture constitution (principles 1–10). They are **namespaced `PGP-nn`** deliberately, to avoid collision with that numbered list (see Governance Integration Report, finding G-2). They are **principles**, not decisions — enduring rules that individual **ADRs reference** (an ADR records a decision *in context*; a principle endures across decisions).

> **Principle vs Decision.** A *principle* states an enduring architectural rule. A *decision (ADR)* applies a principle to a specific context. The Messaging `ADR-MP` series *applies* these principles to the Messaging Platform; future capabilities apply the same principles via their own ADRs.

---

## PGP-01 — Platform Capability (role vs classification)
A reusable capability consumed by multiple bounded contexts is modelled on **two orthogonal axes, never conflated**: its **architectural role** (*Platform Capability*) and its **DDD classification** (e.g. Generic/Supporting Technical Subdomain). A Platform Capability is not a bounded context, not a core/business subdomain, and holds no business decisions. **Architecture produces tickets; tickets never accrete into architecture** — capture the capability as a first-class concept, do not let tickets silently become it.

## PGP-02 — Single ownership
Every responsibility of a capability has **exactly one owner**, expressed with the disposition vocabulary **Owns / Coordinates / Preserves / Observes / Does-NOT-own**. Ambiguous or shared ownership is a modeling defect that blocks promotion.

## PGP-03 — Owner-hosts-the-guard
The suite that hosts an invariant's **executable guard** is the suite owned by the invariant's **owner**. A capability enforces the invariants it owns and hosts their guards; for invariants it merely **preserves** (owned elsewhere), the **owner's** suite hosts the guard — the capability may contribute the scan surface but must not host a guarantee it does not own. A preserver hosting an owner's guard is an ownership mismatch (architecture debt).

## PGP-04 — Constitutional preservation
Constitutional invariants (e.g. anonymity, tenant isolation) are owned by the constitution / a business context and are **preserved**, never owned, by a Platform Capability. A capability must never introduce, require, or leak what a constitutional invariant forbids, and must never let a preserved invariant's evolution depend on infrastructure.

## PGP-05 — Deferred evolution is explicit
A capability makes **no un-sanctioned guarantees**. Any capability question left open (e.g. an ordering guarantee, a structural symmetry) is either a new ADR when needed or **tracked architecture debt** — never a hidden feature and never a speculative redesign. Evolution happens under pressure and governance, not aesthetics.

---

### Applied by
- Messaging Platform → `ADR-MP-01…05` (`../../adr/ADR-MP-Messaging-Platform.md`) applies PGP-01…05.
- Pattern that operationalizes these principles → `../patterns/Platform_Capability_Pattern.md`.
- Future Platform Capabilities reference this register from their own ADRs.

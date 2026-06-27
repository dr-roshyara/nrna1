# Round 46-KRG — Knowledge Release Governance

**Program:** NRNA DDD Trustworthiness Research Program · **Under MB-39.1 (frozen)**
**Status:** 🏛️ GOVERNANCE POLICY — defines the **lifecycle, versioning, compatibility, and change protocol** for Certified Domain Knowledge Releases. Complements MB-39.1 (methodology baseline) the way semantic versioning complements software releases.
**Date:** 2026-06-26

> **Why.** Certification (46B) froze **Release v1.0**. But knowledge *will* evolve over a multi-year program. Without lifecycle/versioning rules we would accumulate Ontology 1.1 / Package 2.0 / Vocabulary 2.1 with no governance. This policy makes evolution **controlled**, not forbidden — and keeps the Research↔DDD boundary intact as releases change.

---

## 1. Knowledge Release Lifecycle

```
Draft → Candidate → Certified → Published → Superseded → Deprecated → Archived
```

| State | Meaning | DDD-consumable? |
|-------|---------|-----------------|
| **Draft** | under active governance research | ❌ |
| **Candidate** | assembled; under readiness + assurance review (46A/46C) | ❌ |
| **Certified** | passed certification (46B); content frozen | ❌ (not yet released) |
| **Published** | the **active** release for DDD consumption | ✅ |
| **Superseded** | a newer Published release exists; kept for traceability | read-only |
| **Deprecated** | scheduled for removal; DDD must migrate off it | migrating |
| **Archived** | retained for audit only | ❌ |

**Current state:** **Domain Knowledge Release v1.0 = Certified + Published** (the active release Round 47 consumes).

## 2. Versioning & compatibility policy (semantic-versioning analog)

| Bump | Scope of change | Strategic DDD impact |
|------|-----------------|----------------------|
| **PATCH** `v1.0.x` | vocabulary clarification, allowed-synonym addition, typo, example | none — DDD unaffected |
| **MINOR** `v1.x` | terminology improvement; a *Held* concept stabilizes; non-breaking addition | DDD **informed**; no rework |
| **MAJOR** `v2.0` | ontology change (new/changed primitive or axiom); admissibility change | requires **Strategic DDD review** |
| **BREAKING** | change that invalidates existing contexts/aggregates (e.g. a **Blocked** concept becomes admitted → new context; an Independence facet changes) | requires **DDD re-review + migration plan** |

*Examples mapped to the backlog:* resolving **RQ-EL-01** (Eligibility admitted) = **BREAKING** (new context). Resolving **RQ-ANCHOR-01** (Pettit contestability) if it altered the anchor = **MAJOR**. A LIT-2 synonym tweak = **PATCH**.

## 3. Certified Vocabulary Governance *(renames "Vocabulary Freeze")*

Vocabulary is **not frozen forever** — it is governed against **uncontrolled** evolution. Terms change **only** through the release lifecycle + compatibility policy above. During DDD, a proposed new term is **not** named in code; it is raised as a governance item and lands (if accepted) in a future Published release. *("No uncontrolled evolution," not "no evolution.")*

## 4. Architecture Change Protocol (closes the final governance loop)

> **Any change affecting Ontology · Vocabulary · Package · Admissibility MUST originate in Governance Research — NOT in Strategic DDD.**

- Strategic DDD may **request** a change (raise a governance item); it may **never enact** one. *(Mirrors the methodology rule "families propose, governance enacts," and the boundary contract Software ↛ Governance / MC-06.)*
- Flow: `DDD raises governance item → Draft → Candidate → Certification → Published release → DDD consumes new release`.
- A bounded context, aggregate, or vocabulary term in DDD that contradicts the Published release is **void to the extent of the conflict** until reconciled via a release.

This is the inverse of consumption: **DDD consumes releases downward; it proposes governance changes upward but cannot write them.**

## 5. Traceability Version Matrix (binding for Strategic DDD)

Every Strategic DDD artifact **MUST declare the certified-release versions it was built against** — so each architectural decision is auditable to the exact knowledge that produced it (essential for election-system auditability).

| DDD Artifact | Package ver | Vocabulary ver | Ontology ver |
|--------------|-------------|----------------|--------------|
| *(template — filled from Round 47 on)* | 1.0.0 | 1.0.0 | 1.0.0 |
| Context Map | 1.0.0 | 1.0.0 | 1.0.0 |
| Bounded Context defs | 1.0.0 | 1.0.0 | 1.0.0 |
| Aggregate designs | 1.0.0 | 1.0.0 | 1.0.0 |
| Domain Events/Policies | 1.0.0 | 1.0.0 | 1.0.0 |

**Rule:** when a release bumps (Patch/Minor/Major/Breaking), the matrix shows which DDD artifacts are **stale** and must be reviewed/migrated. A Major/Breaking bump flags every artifact built on the prior Ontology version for re-review.

## 6. Publication structure (forward note — recorded, not now)

The program separates naturally into **four publishable contributions**:
1. **Governance Discovery Method** (Rounds 38–41).
2. **Governance Knowledge Translation Pipeline** (Rounds 42–46: ontology → semantic projection → ownership → translation assurance → certified contract).
3. **Strategic DDD from Certified Governance Knowledge** (Round 47+).
4. **Implementation & Empirical Validation** (after implementation).

*Scholarly attributions (LIT-2) are `[verify]` against primary sources before submission.*

---

*Round 46-KRG — Knowledge Release Governance — ISSUED.*
*Lifecycle Draft→Candidate→Certified→Published→Superseded→Deprecated→Archived; SemVer-style compatibility (Patch/Minor/Major/Breaking); Certified Vocabulary Governance (controlled, not frozen-forever); Architecture Change Protocol (changes originate in Governance Research, never in DDD). Release v1.0 = Certified+Published. 4-paper publication plan. MB-39.1 FROZEN · Strategic DDD UNGATED (47+).*

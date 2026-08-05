# Architecture Knowledge Base (AKB) v1.0

**Status:** 🗺️ LIVING (index) · the organizing overlay for all project documentation. Defines documentation levels, lifecycle classification, the knowledge map (reading order), the maturity model, and the traceability chain. The **Handbook** is the central navigation document *within* this AKB.
**Date:** 2026-06-27 · Evolve via architecture review + versioned AKB release.

> **Design choice:** the AKB is a **logical overlay + registry**, not a physical re-foldering. Existing artifacts stay at their paths (dense cross-references + memory pointers depend on them). A physical reorganization into `constitution/ handbook/ ddd/ quality/ research/ roadmap/ appendix/` is a **recommended AKB v1.1 step**, executed as one reviewed migration — not piecemeal.

## 1. Documentation levels
| Level | Role | Members |
|-------|------|---------|
| **L1 — Constitution** *(immutable principles)* | what must never change | Implementation Architecture Constitution v1.0 · 10 Architecture Principles (Release 1.0) · Strategic DDD Constitution (SD-1..7) · ARB Decision Template (38C-16) |
| **L2 — Architecture Handbook** *(complete knowledge + navigation)* | the map + synthesis | **Project Constitution & Knowledge Transfer Handbook v1.0** (central nav) · this AKB |
| **L3 — Implementation Playbooks** *(developer guidance)* | how to build | Greenfield Core Playbook · Implementation Coding Standard (forthcoming) · Canonical Event Catalog · Failure Strategy · Architecture Overview |
| **L4 — Living documents** *(evolve continuously)* | current truth | ADR-T log · Implementation Traceability Matrix · Readiness Audit · Roadmap/Progress · Research (EBTAE/LIT) |

## 2. Lifecycle classification (every doc declares one)
**Immutable** (changes only via new version: Constitution, Architecture Release 1.0, BDR v1.1, ARB rulings) · **Frozen** (stable; change via ADR + review: 50-04…50-09, Event Catalog v1.0, Coding Standard) · **Living** (evolves freely: ADR-T log, Traceability Matrix, Roadmap, Readiness Audit) · **Generated** (produced from code/tests: metrics dashboards) · **Historical** (superseded, retained for audit: BDR v1.0 table, 50-07 v1.0/v1.1).

## 3. Document registry (level · lifecycle)
| Document | Level | Lifecycle |
|----------|-------|-----------|
| Implementation Architecture Constitution v1.0 | L1 | Immutable |
| Architecture Release 1.0 (+ Notes) | L1 | Immutable |
| BDR v1.1 | L1/L4 | Immutable (table) + append-only history |
| Round 38C-15 ruling / 38C-16 template | L1 | Immutable |
| Project Constitution & KT Handbook v1.0 | L2 | Living (versioned) |
| Architecture Knowledge Base v1.0 (this) | L2 | Living |
| Round 50-04…50-09 | L3 | Frozen |
| Canonical Event Catalog v1.0 | L3 | Frozen |
| Failure Strategy · Architecture Overview | L3 | Frozen |
| Greenfield Core Playbook · Coding Standard | L3 | Frozen / forthcoming |
| ADR-T log | L4 | Living |
| Implementation Traceability Matrix · Readiness Audit | L4 | Living |
| EBTAE charter · LIT-A | L4 | Frozen charter / Living evidence |

## 4. AKB versioning (evolve without rewriting history)
```
AKB v1.0  Constitution + Architecture Release + DDD Discovery + Research + Tactical design + impl governance  (NOW)
AKB v1.1  Greenfield Core implemented + physical doc reorg + LIT-METHOD
AKB v1.2  Operational BCs migrated + production readiness
AKB v2.0  Cryptographic E2E verification (ADR-T13 lifted)
```

## 5. Architecture Maturity Model (0–10) — current placement
| Lvl | Stage | Status |
|-----|-------|--------|
| 0 Idea / 1 Vision / 2 Discovery | — | ✔ |
| 3 Strategic DDD | — | ✔ |
| 4 Architecture Baseline (Release 1.0) | — | ✔ |
| 5 Tactical Design (50-01..09) | — | ✔ |
| **6 Implementation** | greenfield Core | **◐ ~10–15% (Challenge done)** |
| 7 Verification (CI gates, mutation, deptrac wired) | — | ✖ |
| 8 Production | — | ✖ |
| 9 Research Validation (LIT-METHOD/empirical) | — | ✖ |
| 10 Reference Architecture | — | ✖ |

## 6. Traceability chain (governance intent → executable software)
```
Vision → Problem → Research(EBSD) → Strategic DDD(BDR) → Architecture Principle
   → DDD decision(50-0x) → ADR-T → Implementation rule(Constitution) → Source code
   → Test(unit+arch+mutation) → Evidence(metrics/Readiness Audit)
```
Every implemented class cites its lineage (e.g. `Challenge → 50-01/02/07 → ADR-T1/T11/T12 → BDR-05 → Catalog`); the Traceability Matrix is the live ledger.

## 7. Knowledge map (reading order for a new architect)
```
Handbook (this AKB's L2 center)
   → Project Constitution & KT Handbook v1.0
   → Architecture Release 1.0  → BDR v1.1
   → Round 50-01 … 50-09
   → Implementation Architecture Constitution v1.0
   → Canonical Event Catalog · Failure Strategy · Architecture Overview · Coding Standard
   → ADR-T log · Traceability Matrix · Readiness Audit
   → (research) EBTAE · LIT-A · 38C-15 ruling
```

## 8. Architectural Knowledge Layers (governance overlay — 2026-07-07)

An explicit **knowledge hierarchy** overlaid on the document *levels* of §1 (§1 classifies a document's *role*; this classifies its *altitude*, vision → implementation). It organizes existing knowledge — it invents none.

| Layer | What lives here | Existing anchors | New (this release) |
|-------|-----------------|------------------|--------------------|
| **L0 Vision** | what the platform is | Portal one-liner · Handbook Part I | — |
| **L1 Strategic DDD** | boundaries, subdomains, ubiquitous language | Certified Strategic Architecture Landscape · BDR v1.1 · Round 47–50 | Messaging Strategic Model + Review |
| **L2 Architecture Principles** | enduring rules ADRs reference | Architecture Principles (Release 1.0) 1–10 | **Platform Governance Principles** `principles/Platform_Governance_Principles.md` (PGP-01…05) |
| **L3 Architecture Patterns** | reusable structural templates | (implicit before now) | **Platform Capability Pattern** `patterns/Platform_Capability_Pattern.md` |
| **L4 Platform Capabilities** | reusable infra consumed by many BCs | (none before now) | **Messaging Platform** `Messaging_Platform_Architecture.md` (first instance) |
| **L5 Bounded Contexts** | business contexts | Contestation · Adjudication · Election · Evidence · Appointment · Voting | — |
| **L6 Implementation** | code, tests, playbooks | `app/Contexts/**` · fitness suites · Playbooks | — |

**Distinction made explicit (this release):** *Architecture Principles* (L2, enduring) are separate from *Architecture Decisions* (ADRs, context-specific). ADRs **reference** principles. The Messaging `ADR-MP` series *applies* PGP-01…05.

**Every future Platform Capability** has a defined home: L1 model+review → L2/L3 (reuse PGP + the pattern) → **L4 as a new instance** → L5 consumers register against it → L6 executable architecture (hosted per PGP-03).

### Registry additions
| Document | Layer | Level | Lifecycle |
|----------|-------|-------|-----------|
| Architecture Principles — Platform Capabilities **chapter** (PGP-01…05) | L2 | L1 (a chapter of the one Architecture Principles constitution — not a separate constitution) | **FROZEN** (D-13) |
| Platform Capability Pattern | L3 | L3 (pattern) | **FROZEN** (D-13); maturity **Provisionally Stable** (validated vs Notification + Identity) |
| Messaging Platform Architecture | L4 | L3 | **FROZEN** (D-13); first validated specialization |
| ADR-MP-01…05 | (decisions) | L4 | Living (decision records) |

### Governance rules for this layer
- **Architecture Convergence → see ER-05** (canonical home: `../implementation/Implementation_Process_v1.1_Draft.md`; adopted via D-13). Every iteration reduces/maintains complexity. *(Single source of truth — not restated here, per ER-05 itself.)*
- **ARR Gate** (consume-vs-change a frozen Platform Capability) → same Process v1.1 draft.
- **Governance lifecycle for L2–L4 (authoritative docs):** change via **Proposal → Review → Ratification → Publication** (not propose→merge).
- **One constitution, many chapters:** new principle areas (security, testing, ops) become *chapters* of the Architecture Principles constitution — never new constitutions.

> **Open (G-1), pending ARB:** "AKB Release 1.2" on the Messaging docs collides with §4's roadmap (v1.2 = operational-BC migration); release numbering for this governance addition is **pending ratification** (tag neutralized meanwhile). *(The transition-only Governance Integration Report has been retired; its durable knowledge is folded here, into the PGP chapter, the Pattern §8, and ADR-MP.)*

---
*Architecture Knowledge Base v1.0 — logical overlay: 4 documentation levels (Constitution/Handbook/Playbooks/Living); lifecycle classification (Immutable/Frozen/Living/Generated/Historical) with registry; AKB versioning; maturity model (currently Level 6 ◐); traceability chain; knowledge-map reading order. Handbook is the L2 navigation center. Physical re-foldering deferred to AKB v1.1 (single reviewed migration).*

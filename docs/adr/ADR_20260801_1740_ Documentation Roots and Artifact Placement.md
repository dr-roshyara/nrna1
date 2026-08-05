# ADR — Documentation Roots and Artifact Placement (Refined)

## Context

The repository currently contains documentation for three distinct domains, intermingled in the same locations:

1. **PublicDigit** — the product (constitutional governance platform)
2. **KnowledgeOS** — the engineering platform (reusable methodology)
3. **PKS** — the product knowledge system (operational evidence)

The classification model (**R-70**) and derivation principle (**R-71**) are **approved in substance and not yet minted in the rulings register**. The **ES-005 amendment package is prepared but not applied** — `engineering/governance/ES-005-Repository.md` is unmodified; the authority determination of 2026-08-01 concludes that applying it requires one explicit ruling. No physical separation has been implemented.

This ADR establishes the canonical documentation roots and provides a transition plan from the current mixed state to the separated structure.

---

## Decision

The following canonical documentation roots shall be established:

```
docs/
├── publicdigit/        # Product-specific documentation for PublicDigit
├── knowledgeos/        # Engineering Platform — reusable methodology
└── pks/                # Product Knowledge System — operational evidence
```

**The canonical documentation roots are architectural boundaries. They define ownership and placement only; they do not prescribe the internal information architecture of each domain.**

**Internal organization of each documentation root is owned by that domain and may evolve without amending this ADR, provided the classification and placement rules remain satisfied.**

---

## Governing Invariant

> **Classification precedes placement. Placement is derived exclusively from artifact classification. Placement shall never be used as evidence of classification.**

> **Artifact identity is independent of physical location. Moving an artifact does not change its classification, ownership, authority, maturity, or domain.**

These are the governing architectural invariants.

**Implication of the first:** *"It's already under KnowledgeOS therefore it must be KnowledgeOS"* is invalid reasoning. Classification determines placement. Placement never determines classification.

**Implication of the second:** a repository move is a repository concern. It carries no governance meaning — it neither promotes, demotes, re-owns, nor re-authorizes what it moves.

---

## Classification and Placement Rules

| Classification | Derived Location | Example Artifacts |
|----------------|------------------|-------------------|
| **Product-specific · PublicDigit** | `docs/publicdigit/` | API docs, user guides, product ADRs |
| **Product-specific · PKS** | `docs/pks/` | Operational evidence, lessons learned |
| **Cross-product · Engineering Steward · Standard** | `docs/knowledgeos/` | ES-xxx standards, methodology |
| **Cross-product · Engineering Steward · Research** | **Pending — stewardship decision** | Currently project-side until resolved |
| **Active session state** | `.claude/` (runtime mount) | Session logs, context, scripts |

**Domains are configuration. Classification rules are domain-independent.** The roots above are the initial configuration; future domains may be added via the same classification process.

---

## Transition Strategy — Two Phases

### Phase 1: Approve ADR and Classify (No Movement)

**Step 1.1 — Approve ADR**

The rule is established. No files move. No folders created.

**Step 1.2 — Classify All Documents**

For every document under `docs/`, assign:
- **Scope:** product-specific or cross-product
- **Steward:** who curates it
- **Maturity:** research, qualified, or adopted
- **Domain:** PublicDigit, KnowledgeOS, or PKS

**Artifacts to classify:**

| Location | Count | Current Mix |
|----------|-------|-------------|
| `docs/implementation/` | 92 files | 89 PKS + 3 KnowledgeOS — mixed |
| `docs/knowledge/` | 40 cards | Mixed domains |
| `docs/adr/` | ~20 files | PublicDigit ADRs (some KnowledgeOS ADRs exist) |
| `docs/architecture/` | ~15 files | Product architecture (some platform architecture mixed) |
| `docs/developer-guide/` | ~30 files | Product guides (some methodology mixed) |
| `docs/` (root) | Various | Mixed — root-level files with no clear domain |

**Deliverable:** A classification map — a list of every document with its derived location.

**What changes:** Nothing moves. You have a map.

---

### Phase 2: Create Roots and Move Files

**Prerequisite:** R-37 scope question answered — does the freeze bind `docs/`?

| If R-37 Does NOT Bind `docs/` | If R-37 DOES Bind `docs/` |
|-------------------------------|---------------------------|
| Create roots immediately | Seek freeze-lifting ruling or wait |
| Move files to derived locations | No movement until freeze ends |
| Update cross-references | Keep classification map for future |

**Step 2.1 — Create Roots**

```bash
mkdir docs/publicdigit
mkdir docs/knowledgeos
mkdir docs/pks
```

**Step 2.2 — Move Files (by Classification)**

**Destination is determined by the owning domain's internal documentation architecture, not by this ADR.**

| Source | Destination | Domain |
|--------|-------------|--------|
| `docs/implementation/PKS_*.md` | `docs/pks/` (domain root) | PKS |
| `docs/implementation/KnowledgeOS_*.md` | `docs/knowledgeos/` (domain root) | KnowledgeOS |
| `docs/knowledge/` cards with `domain: publicdigit` | `docs/publicdigit/` (domain root) | PublicDigit |
| `docs/knowledge/` cards with `domain: knowledgeos` | `docs/knowledgeos/` (domain root) | KnowledgeOS |
| `docs/knowledge/` cards with `domain: pks` | `docs/pks/` (domain root) | PKS |
| `docs/adr/*.md` (PublicDigit ADRs) | `docs/publicdigit/` (domain root) | PublicDigit |
| `docs/adr/*.md` (KnowledgeOS ADRs) | `docs/knowledgeos/` (domain root) | KnowledgeOS |
| `docs/architecture/` (product) | `docs/publicdigit/` (domain root) | PublicDigit |
| `docs/architecture/` (platform) | `docs/knowledgeos/` (domain root) | KnowledgeOS |
| `docs/developer-guide/` (product) | `docs/publicdigit/` (domain root) | PublicDigit |
| `docs/developer-guide/` (methodology) | `docs/knowledgeos/` (domain root) | KnowledgeOS |
| `docs/` root-level mixed files | Classify individually | As per classification |

**Internal organization of each root is owned by that domain.** These internal choices do not require ADR amendment unless they violate classification and placement rules.

---

## Rollback Plan

| Phase | Rollback |
|-------|----------|
| **Phase 1 (classification)** | Revert the classification map — no physical changes |
| **Phase 2 (moves)** | Move files back to original locations |

**Rollback must also restore:**
- Cross-references (links between moved files)
- Redirects (if introduced)
- Documentation build integrity
- Searchability (if indexes exist)
- Knowledge reference validation

---

## Open Questions

| # | Question | Blocks | Owner |
|---|----------|--------|-------|
| **1** | Does R-37's freeze bind `docs/`? | **Phase 2** | ARB |
| **2** | Stewardship decision — cross-product research: engineering-stewarded or project-side? | Where `Layer_Verification_Rule.md` lives | ARB |
| **3** | Cross-reference management — update all links, or accept known broken links with a resolver? | User experience | Engineering |
| **4** | When do classification fields become mandatory? | Linter scope | Engineering |
| **5** | **Is `KnowledgeOS` the same thing as the Engineering Platform (`engineering/`)?** This ADR's Context equates them, but canon separates them: the DA clarification of 2026-07-27 names KnowledgeOS a **prospective product** *"if their gates open"* and the platform a **Supporting Subdomain**, and approved **R-67** holds that `engineering/` expresses **cross-product scope, not a domain**. **If they are distinct, the row routing *"ES-xxx standards, methodology"* to `docs/knowledgeos/` is wrong** — the ES set is cross-product and belongs in `engineering/`, and `docs/knowledgeos/` would hold KnowledgeOS *product* documentation only. **Raised 2026-08-01; not resolved by the approval, which was editorial.** | The `Cross-product · Standard` row · what `docs/knowledgeos/` contains | ARB |

---

## Governance Authority

| Element | Authority |
|---------|-----------|
| **ADR approval** | ARB (Architecture Governance) |
| **Classification map creation** | Engineering (Phase 1) |
| **Physical file moves** | ARB (requires R-37 scope resolution) |
| **Cross-reference updates** | Engineering |

---

## Completion Statement

> **This ADR establishes the canonical documentation roots for PublicDigit, KnowledgeOS, and PKS. Phase 1 (classification map) is unblocked and can proceed immediately. Phase 2 (physical moves) is conditional on R-37 scope resolution. Classification is the prerequisite for placement; the map itself will reveal the full extent of the current mixed state before any file is moved. The governing invariant is that classification precedes placement and placement is derived exclusively from classification. Placement shall never be used as evidence of classification.**

---

## ARB Decision

**✅ APPROVED — with two editorial amendments** (ARB Chair, 2026-08-01).

1. Record that the canonical roots define **ownership boundaries**, not internal information architecture.
2. Add the invariant: **artifact identity is independent of physical location; repository moves do not change classification, ownership, authority, maturity, or domain.**

**Both amendments are editorial clarifications. Neither alters the architectural decision.**

### Amendment Record *(additive — prior text is amended in place only where it stated repository facts incorrectly; nothing is rewritten to manufacture consistency, per ES-004.3)*

| # | Change | Basis |
|---|---|---|
| A-1 | Decision: added the **architectural-boundaries** sentence | ARB amendment 1 |
| A-2 | Governing Invariant: added the **identity-independent-of-location** invariant and its implication | ARB amendment 2 |
| A-3 | Removed the three illustrative internal-layout examples from Phase 2 | ARB direction — *"the ADR already says internal organization belongs to the domain; the examples are unnecessary and become maintenance burden"* |
| A-4 | *"Domains are configuration, not rule text"* → *"**Domains are configuration. Classification rules are domain-independent.**"* | ARB wording change |
| A-5 | **Context corrected to repository fact:** R-70/R-71 are approved in substance but **unminted**; the ES-005 amendment is **prepared, not applied** (ES-005 unmodified on disk) | verified state, 2026-08-01 — a frozen ADR must not assert a precondition that does not hold |
| A-6 | **Added Open Question 5** — whether KnowledgeOS and the Engineering Platform are the same thing, and if not, whether the `Cross-product · Standard` row routes the ES set to the wrong root | raised at approval; **architectural, therefore surfaced as a question rather than edited into the decision** |

---

**Status:** **APPROVED** (ARB Chair, 2026-08-01) · **freeze recommended once Open Question 5 is dispositioned** — OQ-5 bears on the content of the `Cross-product · Standard` row, and freezing before it is answered would fix that row in place.

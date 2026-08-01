# KnowledgeOS — Documentation Root

**Canonical documentation root for the KnowledgeOS domain.** Established by `docs/adr/ADR_20260801_1740_ Documentation Roots and Artifact Placement.md` (APPROVED, 2026-08-01).

## ⚠️ Open Question 5 — read before placing anything here

**Whether KnowledgeOS and the Engineering Platform (`engineering/`) are the same thing is UNRESOLVED.** Canon separates them:

- the Decision Authority clarification of 2026-07-27 names **KnowledgeOS a prospective product** — *"future products **if their gates open**"* — and the platform a **Supporting Subdomain**;
- approved **R-67** holds that **`engineering/` expresses cross-product *scope*, not a domain**.

**Consequence, in force until OQ-5 is dispositioned: Engineering Standards (ES-001…ES-006) and cross-product methodology do NOT belong here.** They are cross-product and derive to `engineering/` by the `cross-product-qualified` rule. **This root holds documentation classified `domain: knowledgeos` — not everything that sounds platform-shaped.**

## What belongs here

Documentation classified **`scope: product-specific` · `domain: knowledgeos`** — KnowledgeOS product discovery, its charter and constitution, its governance execution plan, its own domain documentation.

## Placement is derived, not chosen

```bash
php scripts/doc-placement.php --scope=product-specific --domain=knowledgeos
```

**The registry is the single source of truth:** `docs/knowledge/schema/documentation-placement.yaml`. Never hard-code this path — resolve it.

## Internal organization

**This root is an architectural boundary. It defines ownership and placement only** — the internal information architecture is owned by the domain and needs no ADR amendment to evolve.

## Invariants

> **Classification precedes placement.** *"It is already under KnowledgeOS, therefore it must be KnowledgeOS"* is invalid reasoning.
>
> **Artifact identity is independent of physical location.** A move changes no classification, ownership, authority, maturity, or domain.

**Nothing has been migrated into this root**, and nothing should be until OQ-5 is answered — it decides what this root is for.

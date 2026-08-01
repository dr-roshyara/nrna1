# PKS — Product Knowledge System — Documentation Root

**Canonical documentation root for the PKS domain.** Established by `docs/adr/ADR_20260801_1740_ Documentation Roots and Artifact Placement.md` (APPROVED, 2026-08-01).

## What belongs here

Documentation classified **`scope: product-specific` · `domain: pks`** — strategic discovery artifacts, phase records, concept registers, collision resolution reports, operational evidence, lessons learned.

**PKS is a domain in its own right**, with its own candidate bounded contexts discovered in Phase II M6 (Knowledge Assessment · Knowledge Projection · Normative Governance · Work Management-as-adjacent). **This root groups them; it does not flatten them.**

## ⚠️ Domain membership is not ruled

**`EKA → KnowledgeOS` is a confirmed invariant; `PKS → KnowledgeOS` is not.** This root names a home for artifacts *already classified* as PKS. **It does not decide whether PKS is a constituent of another domain or a peer of it** — that remains an open architectural question.

## Placement is derived, not chosen

```bash
php scripts/doc-placement.php --scope=product-specific --domain=pks
```

**The registry is the single source of truth:** `docs/knowledge/schema/documentation-placement.yaml`. Never hard-code this path — resolve it.

## Internal organization

**This root is an architectural boundary. It defines ownership and placement only** — internal structure is owned by the domain and may evolve without amending the ADR.

## Invariants

> **Classification precedes placement.** Placement is derived exclusively from classification and is never evidence of it.
>
> **Artifact identity is independent of physical location.** A move changes no classification, ownership, authority, maturity, or domain.

**Nothing has been migrated into this root.** The 89 `PKS_*` documents currently under `docs/implementation/` stay where they are: migration requires a classification map, a resolution of whether R-37's reorganization freeze binds `docs/`, and separate authorization.

# PublicDigit — Documentation Root

**Canonical documentation root for the PublicDigit domain.** Established by `docs/adr/ADR_20260801_1740_ Documentation Roots and Artifact Placement.md` (APPROVED, 2026-08-01).

## What belongs here

Documentation classified as **`scope: product-specific` · `domain: publicdigit`** — product architecture, API documentation, user and developer guides, product ADRs.

**PublicDigit is a domain, not a bounded context.** It contains many bounded contexts (see `app/Contexts/` — Adjudication, Committee, Contestation, Election, Finance, Geography, Governance, Membership, Trust). **This root groups all of them; it does not replace per-context organization inside it.**

## What does not belong here

**Cross-product artifacts** — anything another project could adopt unchanged. Those derive to `engineering/` once qualified, regardless of which product first needed them.

## Placement is derived, not chosen

```bash
php scripts/doc-placement.php --scope=product-specific --domain=publicdigit
```

**The registry is the single source of truth:** `docs/knowledge/schema/documentation-placement.yaml`. Never hard-code this path in a template or script — resolve it.

## Internal organization

**This root is an architectural boundary. It defines ownership and placement only.** The internal information architecture of PublicDigit's documentation is owned by the domain and may evolve without amending the ADR, provided classification and placement rules remain satisfied.

## Invariants

> **Classification precedes placement.** Placement is derived exclusively from classification and is never evidence of it.
>
> **Artifact identity is independent of physical location.** Moving a document changes no classification, ownership, authority, maturity, or domain.

**Nothing has been migrated into this root.** Existing documentation stays where it is until a classification map is produced and physical migration is separately authorized.

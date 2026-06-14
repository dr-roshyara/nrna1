# ADR-003: PublicDigit Shell Visual Identity

**Status:** Accepted  
**Date:** 2026-06-13  
**Context:** PublicDigit is a digital governance platform serving NGOs, associations, clubs, foundations, political parties, and election management — not only an election commission system. Final header styling validated through iterative design review.

## Decision

The application shell (Header) communicates the **PublicDigit platform brand** with a dark blue identity conveying trust, authority, and premium quality.

| Layer | Identity | Token |
|-------|----------|-------|
| **Shell** (Header) | PublicDigit platform | Deep blue (`primary-800/950/900`) |
| **Content** (Pages) | Feature-specific | Neutral/functional |
| **Election pages** (ElectionLayout) | Governance context | TBD — separate ADR |

## Final Specification

### Header Gradient
```css
bg-gradient-to-br from-primary-800 via-primary-950 to-primary-900
text-neutral-100
border-b border-brand-gold-500/40
```

### Color Scheme

| Element | Token |
|---------|-------|
| Primary text | `text-neutral-100` |
| Navigation links | `text-neutral-200 hover:text-primary-200` |
| Secondary text | `text-neutral-300` / `text-neutral-400` |
| Login button | `bg-white text-primary-900 hover:bg-primary-100` |
| Logout button | `border-brand-gold-500 text-brand-gold-400 hover:bg-brand-gold-500 hover:text-neutral-900` |
| Brand gold accents | `text-brand-gold-400` / `border-brand-gold-500/40` |
| Demo CTA | `bg-success-600 text-neutral-50` |

### Footer
Footer retains its existing `slate-800/900` gradient — header and footer are visually cohesive at similar darkness levels.

## Consequences

**Positive:**
- Deep blue communicates trust, authority, and premium quality
- Maximum contrast for brand-gold accents (gold on dark blue)
- White login button stands out clearly on dark header
- Semantic tokens used consistently throughout
- Gold hover on navigation links creates strong discoverability
- WCAG AA compliant across all text/background combinations

**Negative:**
- Stronger institutional tone may feel less approachable for some community organizations
- Brand-gold accessibility must be monitored if future shades change
- Shell identity is now visually stronger than some existing page designs and may require future alignment
- Navigation hover (gold) differs from page content hover patterns (primary) — minor inconsistency

## Related

- [ADR-001: Reuse Before Create](ADR-001-Reuse-Before-Create.md)
- [Discovery: Shell Token Assessment](../discoveries/20260613-shell-token-assessment.md)

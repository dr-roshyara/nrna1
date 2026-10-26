# PBDIGIT-311: The mobile menu is white on white, and Logout cannot be seen

**Type:** UI bug · **Epic:** none · **Created:** 2026-10-26 · **Raised by:** the PO, on the mobile view of `…/voters/manage`.

| | |
|---|---|
| **Status** | `IMPLEMENTED` · approved 2026-10-26 (revised plan); awaiting PO acceptance and frontend release |
| **Classification** | **observed:** the panel that opens from ☰ in `PublicDigitHeader` (used by `PublicDigitLayout`, 15 importers) shows white text on a white background |
| **Scope** | the mobile panel only (`resources/js/Components/Jetstream/PublicDigitHeader.vue` lines 222–296) |

## Cause
- The panel was `bg-white`, but its contents use the header's dark-background colours: links `text-neutral-200`, which is near-white.
- **Logout** used `text-brand-gold-500`. The live theme (`resources/css/app.css` `@theme`, Tailwind 4) has **no `brand-gold` colours** (gold is `accent`), and `tailwind.config.js` is not loaded. So no colour was generated, and the button inherited the header's white `text-neutral-100`.
- Logout's hover `hover:bg-gold` did not exist either.

## Change (12 lines)
| Element | From | To |
|---|---|---|
| panel | `bg-white`, `border-brand-gold-500/20` | `bg-primary-900` (#1e3a8a), `border-accent-400/20` |
| 8 links (hover) | `hover:text-brand-gold-400` | `hover:text-accent-400` |
| 2 dividers | `border-brand-gold-500/20` | `border-accent-400/20` |
| Logout | `border-brand-gold-500 text-brand-gold-500 hover:bg-gold` | `border-accent-400 text-accent-400 hover:bg-accent-400` (#fbbf24, about 7:1 on the panel) |

## Evidence
- Every new class is present in the built CSS; `bg-primary-900` → `#1e3a8a`, `accent-400` → `#fbbf24`.
- `npm run design-check`: 91 before and after (threshold 150).
- `npm run prod` builds.
- Rendered at 390 px with the built CSS (headless Chrome): links and Logout readable.

## Process note
The first approved plan used `bg-primary-950` and `brand-gold-*`. Neither exists in the live theme, which the build and `design-check` did not reveal. The plan was stopped, reverted and re-approved. Check for compiled classes in the built CSS, not only a passing build.

## Developer guide
Folded here (small UI fix). The general lesson is in PBDIGIT-312.

## Related
- **PBDIGIT-312**: `brand-gold-*` and `primary-950` are used elsewhere but generate nothing.

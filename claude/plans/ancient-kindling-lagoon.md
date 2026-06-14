# Plan: Finalize Header Dark Blue Theme — Text, Hover & Button Colors

**Date:** 2026-06-13  
**Context:** The PublicDigitHeader gradient was iteratively darkened to `from-primary-800 via-primary-950 to-primary-900`. Now the text colors, hover states, and button colors need to be adjusted for contrast and cohesion on this dark blue background.

## Target State

### Header Gradient (already applied)
```
bg-gradient-to-br from-primary-800 via-primary-950 to-primary-900
text-neutral-100
border-b border-brand-gold-500/40
```

### Color Scheme for Dark Blue Background

| Element | Token | Rationale |
|---------|-------|-----------|
| Primary text | `text-neutral-100` | White on dark navy |
| Medium emphasis | `text-neutral-300` | For nav links, secondary text |
| Muted text | `text-neutral-400` | For breadcrumbs, helpers |
| Nav links default | `text-neutral-200 hover:text-primary-200` | Light on dark, hover lifts to brighter blue |
| Language select text | `text-neutral-200` | Readable on dark |
| Language select options | `bg-white text-neutral-800` | White dropdown, dark text |
| Login button (desktop) | `bg-white text-primary-900 hover:bg-primary-100` | White button stands out on dark blue, subtle hover |
| Logout button | `border-brand-gold-500 text-brand-gold-400 hover:bg-brand-gold-500 hover:text-neutral-900` | Gold border, gold text, filled gold on hover |
| Mobile menu toggle | `text-neutral-200 hover:bg-neutral-50/10` | Light icon, subtle hover |
| Mobile menu panel | `bg-white` with standard dark text | Same as before — white panel with dark text |
| Mobile nav links | `text-neutral-700 hover:text-primary-700` | Dark text on white bg (mobile panel is white) |
| Demo CTA | Keep `bg-success-600 text-neutral-50` | Green on dark header pops well |
| Breadcrumb links | `text-brand-gold-400 hover:text-brand-gold-500` | Gold on dark works |

### Login Button Change

The login button on the dark blue header should be a **white button with dark text** — like a typical "Sign In" CTA on a dark navbar:
```
bg-white text-primary-900 font-semibold
hover:bg-primary-100
```

This avoids the "blue on blue" problem of using `bg-primary-600` on `bg-primary-800`.

## Files to Modify

1. **`resources/js/Components/Jetstream/PublicDigitHeader.vue`** — Adjust all text/hover/button tokens for dark blue background
2. **`architecture/frontend/decisions/ADR-003-Shell-Visual-Identity.md`** — Update gradient to `primary-800/950/900`, document the final color strategy

## What to Keep Unchanged

- **`PublicDigitFooter.vue`** — Not in scope for this increment
- All structural/behavioral code
- Brand gold tokens
- Success/danger semantic tokens
- Navigation link structure

## Verification

- `bash scripts/design-check.sh` — must pass
- `bash scripts/component-audit.sh` — must pass
- Visual consistency across dark blue header with all element types

# Migration: PublicDigit Shell — Semantic Token Migration

**Date:** 2026-06-13  
**Discovery Reference:** `discoveries/20260613-shell-token-assessment.md`  
**Design Reference:** `tailwind.config.js` — `primary=blue`, `success=green`, `neutral=slate`

## Scope

Migrate raw Tailwind colors in the application shell (Header + Footer) to semantic tokens. Brand-gold tokens preserved unchanged. Opacity overlays preserved.

## Header Gradient

**Before:** `from-slate-900 via-slate-800 to-slate-900`
**After:**  `from-neutral-950 via-primary-950 to-neutral-900`

Rationale: Dark shell with subtle primary-blue injection for product identity. Maintains governance/trust feel while tying shell to design system.

## Header Token Map

| Before | After | Occurrences |
|--------|-------|-------------|
| `from-slate-900 via-slate-800 to-slate-900` | `from-neutral-950 via-primary-950 to-neutral-900` | 1 (line 3) |
| `from-slate-900 to-slate-950` | `from-neutral-950 to-neutral-950` | 1 (line 222) |
| `text-white` | `text-neutral-100` | ~6 |
| `text-white/80` | `text-neutral-300` | ~10 |
| `text-white/50` | `text-neutral-500` | 1 |
| `text-slate-900` | `text-neutral-900` | 2 (auth button, mobile login) |
| `text-slate-50` | `text-neutral-50` | 1 (demo CTA) |
| `bg-slate-800` | `bg-neutral-800` | 3 (select options) |
| `bg-white` | `bg-neutral-50` | 1 (auth button) |
| `hover:bg-white/10` | `hover:bg-neutral-50/10` | ~4 |
| `hover:bg-white/5` | `hover:bg-neutral-50/5` | ~4 |
| `active:bg-white/10` | `active:bg-neutral-50/10` | ~2 |
| `bg-green-600` / `hover:bg-green-700` | `bg-success-600` / `hover:bg-success-700` | 1 (demo CTA) |
| `focus:ring-green-400` | `focus:ring-success-400` | 1 |
| `focus:ring-offset-slate-900` | `focus:ring-offset-neutral-950` | 3 |

**Preserved:** `brand-gold-*`, `focus:ring-brand-gold-*`, `border-brand-gold-*`, `border-purple-*`, `text-purple-*`

## Footer Token Map

| Before | After | Occurrences |
|--------|-------|-------------|
| `bg-footer` CSS gradient (#1e293b / #0f172a) | `bg-gradient-to-b from-neutral-950 to-neutral-950` | Replace CSS class with Tailwind |
| `text-white` | `text-neutral-100` | 2 |
| `text-white/90` | `text-neutral-200` | 1 |
| `text-white/80` | `text-neutral-300` | ~4 |
| `text-white/70` | `text-neutral-400` | 1 |
| `text-white/60` | `text-neutral-400` | ~6 |
| `text-white/50` | `text-neutral-500` | ~3 |
| `text-white/40` | `text-neutral-500/60` | 1 |
| `text-slate-900` | `text-neutral-900` | 1 (PD logo initials) |
| `text-brand-gold-*` | **KEEP** | All branding preserved |

## Files

1. `resources/js/Components/Jetstream/PublicDigitHeader.vue` — ~40 token replacements
2. `resources/js/Components/Jetstream/PublicDigitFooter.vue` — ~20 token replacements + CSS update

## Validation

- `bash scripts/design-check.sh` — must pass with same token count
- `bash scripts/component-audit.sh` — must pass
- Visual: header/footer appearance unchanged (neutral-950 ≈ slate-900)

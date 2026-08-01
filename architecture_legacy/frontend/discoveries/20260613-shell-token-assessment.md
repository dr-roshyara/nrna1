# Discovery: PublicDigit Shell Token Assessment

**Date:** 2026-06-13  
**Status:** Complete — ready for migration plan  
**ADR Reference:** ADR-001 (Reuse Before Create), ADR-002 (Incremental DDD Adoption)  
**Files analysed:** `PublicDigitHeader.vue` (493 lines), `PublicDigitFooter.vue` (339 lines)

## 1. Full Color Inventory — PublicDigitHeader.vue

### Background / Surface Colors

| Raw Token | Location | Classification | Action |
|-----------|----------|----------------|--------|
| `from-slate-900 via-slate-800 to-slate-900` | Header gradient (line 3) | **Semantic** — shell background | → `from-neutral-950 via-neutral-800 to-neutral-950` |
| `from-slate-900 to-slate-950` | Mobile menu gradient (line 222) | **Semantic** — shell background | → `from-neutral-950 to-neutral-950` |
| `bg-slate-800` | Select option (lines 46-48) | **Semantic** — dropdown surface | → `bg-neutral-800` |
| `bg-white` | Login button (line 74) | **Semantic** — button surface | → `bg-neutral-50` |
| `bg-green-600` / `hover:bg-green-700` | Demo CTA (line 183) | **Semantic** — action CTA | → `bg-success-600` / `hover:bg-success-700` |
| `bg-white/5` | Language select (line 43) | **Opacity overlay** — transparent bg | Keep |
| `hover:bg-white/10` / `hover:bg-white/5` / `active:bg-white/10` | Hover/active states (multiple) | **Opacity overlay** | → `hover:bg-neutral-50/10` etc. |

### Text Colors

| Raw Token | Location | Classification | Action |
|-----------|----------|----------------|--------|
| `text-white` | Headings, nav labels, tagline (multiple) | **Semantic** — primary text on dark | → `text-neutral-100` |
| `text-white/80` | Nav links, "Try Demo" (multiple) | **Semantic** — medium emphasis | → `text-neutral-300` |
| `text-white/70` | Breadcrumb current (line 208) | **Semantic** — medium emphasis | → `text-neutral-300` |
| `text-slate-900` | Login button text (line 74) | **Semantic** — dark text on light | → `text-neutral-900` |
| `text-slate-50` | Demo CTA text (line 183) | **Semantic** — near-white | → `text-neutral-50` |
| `text-purple-300` / `hover:text-purple-100` | Platform Admin link (line 174) | **Status** — admin indicator | → Keep or `text-accent-300` |
| `text-white/90` | Footer heading in header (future) | — | Not used in header |

### Border / Ring Colors

| Raw Token | Location | Classification | Action |
|-----------|----------|----------------|--------|
| `border-brand-gold-500/20` | Header bottom border (line 3) | **Branding** — gold separator | **KEEP** — brand identity |
| `border-brand-gold-500/30` | Language select border (line 43) | **Branding** — gold border | **KEEP** |
| `border-brand-gold-500` | Logout button border (line 88) | **Branding** — gold border | **KEEP** |
| `border-purple-400/30` | Admin link separator (line 174) | **Status** — admin indicator | Keep |
| `border-t border-brand-gold-500/20` | Section separators (lines 116, 196, 222, 271, 282) | **Branding** — gold separators | **KEEP** |

### Focus Rings

| Raw Token | Location | Classification | Action |
|-----------|----------|----------------|--------|
| `focus:ring-brand-gold-500` | Focus rings (multiple) | **Branding** — focus indicator | **KEEP** |
| `focus:ring-green-400` | Demo CTA focus (line 183) | **Semantic** — success focus | → `focus:ring-success-400` |
| `focus:ring-purple-400/50` | Admin link focus (line 174) | **Status** — admin focus | Keep |
| `focus:ring-offset-slate-900` | Focus offset (lines 74, 88, 183) | **Semantic** — matches dark bg | → `focus:ring-offset-neutral-950` |

### Misc

| Raw Token | Location | Classification | Action |
|-----------|----------|----------------|--------|
| `bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900` | Header | **Semantic** | → `from-neutral-950 via-neutral-800 to-neutral-950` |
| `bg-gradient-to-b from-slate-900 to-slate-950` | Mobile menu | **Semantic** | → `from-neutral-950 to-neutral-950` |

## 2. Full Color Inventory — PublicDigitFooter.vue

### Background / Surface Colors

| Raw Token | Location | Classification | Action |
|-----------|----------|----------------|--------|
| `bg-gradient(180deg, #1e293b 0%, #0f172a 100%)` — CSS `bg-footer` | Footer background (CSS line 284) | **Semantic** — matches header | → Replace CSS with `bg-neutral-950` or `bg-gradient-to-b from-neutral-900 to-neutral-950` |
| `bg-white/5` | Social icons background (lines 27, 32, 37) | **Opacity overlay** | Keep |
| `border-white/10` / `hover:border-brand-gold-500/40` | Social icon borders (lines 27, 32, 37) | **Opacity overlay + branding** | Keep `border-white/10` as overlay |
| `bg-gray-100` | Quick links hover (back to top line 208) | **Semantic** | → `bg-neutral-100` |
| `bg-brand-gold-500` / `hover:bg-brand-gold-500-dark` | Newsletter button (line 158) | **Branding** — gold CTA | **KEEP** |

### Text Colors

| Raw Token | Location | Classification | Action |
|-----------|----------|----------------|--------|
| `text-white` | Footer heading (line 14) | **Semantic** | → `text-neutral-100` |
| `text-white/90` | Subheading (line 18) | **Semantic** | → `text-neutral-200` |
| `text-white/60` | Description, footer links (multiple) | **Semantic** — medium emphasis | → `text-neutral-400` |
| `text-white/80` | Address text, contact details (multiple) | **Semantic** — medium emphasis | → `text-neutral-300` |
| `text-white/70` | Social icon default (line 28) | **Semantic** — medium emphasis | → `text-neutral-400` |
| `text-white/50` | Label text, section headers (multiple) | **Semantic** — low emphasis | → `text-neutral-500` |
| `text-white/40` | Copyright text (line 198) | **Semantic** — lowest emphasis | → `text-neutral-500/60` |
| `text-slate-900` | Newsletter button text (line 158) | **Semantic** — dark text on brand | → `text-neutral-900` |
| `text-brand-gold-500` | Section headings (lines 48, 87, 140) | **Branding** — gold section titles | **KEEP** |
| `hover:text-brand-gold-500` | Link hovers (lines 53, 116, 130, 208) | **Branding** — gold hover | **KEEP** |
| `text-brand-gold-500/70` | Contact icons (lines 92, 109, 123) | **Branding** | **KEEP** |

### Border Colors

| Raw Token | Location | Classification | Action |
|-----------|----------|----------------|--------|
| `border-white/20` | Newsletter input (line 154) | **Opacity overlay** | Keep |
| `border-white/10` | Footer section separator (line 193) | **Opacity overlay** | Keep |

### Focus Rings

| Raw Token | Location | Classification | Action |
|-----------|----------|----------------|--------|
| `focus:ring-brand-gold-500/50` | Newsletter input focus (line 154) | **Branding** | **KEEP** |

### Logo / Brand Elements

| Raw Token | Location | Classification | Action |
|-----------|----------|----------------|--------|
| `from-gold to-gold-dark` | PD logo gradient (line 11) | **Branding** | **KEEP** |
| `text-slate-900` | "PD" initials (line 12) | **Semantic** — dark text on gold | → `text-neutral-900` |

## 3. Summary

| Category | Count | Action |
|----------|-------|--------|
| **Semantic tokens** to replace | ~38 across both files | All use `neutral-*` / `success-*` / `danger-*` equivalents |
| **Branding tokens** to keep | ~17 across both files | All `brand-gold-*`, `gold-*`, `from-gold` — brand identity |
| **Opacity overlays** to keep | ~8 | `bg-white/5`, `border-white/10`, `hover:bg-white/10` etc. |
| **Status tokens** to keep or migrate | ~4 | Admin purple is an indicator pattern |

## 4. Architectural Conclusion

| Question | Answer |
|----------|--------|
| Can header/footer be migrated to semantic tokens? | **Yes** — 38 semantic token replacements identified |
| Should brand-gold be migrated? | **No** — 17 branding tokens preserved. `brand-gold` is organizational identity, not `accent`. |
| Will the migration change appearance? | **No** — `neutral-950` is visually equivalent to `slate-900`, `neutral-100` to `white` |
| How many lines changed? | ~60 lines in Header, ~30 in Footer |
| Risk | Very low — color-only changes, no structural or behavioral changes |

## 5. Next Step

Create migration plan with exact before/after token mappings and apply edits. ADR-003 to be written only after migration is validated.

## Related

- [ADR-001: Reuse Before Create](../decisions/ADR-001-Reuse-Before-Create.md)
- [ADR-002: Incremental Frontend DDD Adoption](../decisions/ADR-002-Incremental-Frontend-DDD-Adoption.md)
- Tailwind config: `primary=blue`, `success=green`, `neutral=slate`, `accent=amber`

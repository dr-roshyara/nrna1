# PBDIGIT-312: Colour classes that generate no CSS (`brand-gold-*`, `primary-950`)

**Type:** UI / design system · **Epic:** none · **Created:** 2026-10-26 · **Raised by:** found while fixing PBDIGIT-311.

| | |
|---|---|
| **Status** | `OPEN` · needs a design decision |
| **Classification** | **observed in code and build output** |
| **Scope** | the Tailwind 4 theme (`resources/css/app.css`) and the components that use the missing colours |

## Finding
- The build uses Tailwind 4 (`@tailwindcss/vite`, `tailwindcss` 4.3.0). Colours come from the `@theme` block in `resources/css/app.css`. That block defines `primary` 50–900, `accent` (gold), `success`, `danger`, `warning` and `neutral`.
- `tailwind.config.js` defines `brand-gold` and more, but **it is not loaded** (no `@config`).
- So these classes produce **no CSS**:
  - every `*-brand-gold-*` class: 20 in `PublicDigitHeader.vue` (after PBDIGIT-311), 20 in `PublicDigitFooter.vue`, 7 in each of `Pages/Articles/DddArticlePartOne.vue`, `WhoWatchesTheWatchmen.vue` and `FromTraitsToResponsibilitySpace.vue`;
  - `via-primary-950` in the header's gradient (line 3).
- Gold borders, gold text and the middle of the header gradient are therefore not shown as written. Elements fall back to inherited colours.
- `npm run design-check` and the build both pass with these classes, so neither catches it.

## Options (decision needed)
1. **Map to existing tokens** (`brand-gold-*` → `accent-*`; `primary-950` → `primary-900`) in each component. This keeps the theme small and changes how those components look.
2. **Add `brand-gold` and `primary-950` to `@theme`.** This fixes all components at once, but gold appears on several pages where it is invisible today.
3. Either way: make `design-check` (or a build check) fail on classes that compile to nothing.

## Next
- PO / design decision between 1 and 2.
- Then one plan covering the 5 files.

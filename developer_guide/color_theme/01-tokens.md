# Design Tokens

**File:** `resources/css/tokens.css`
**Registered in:** `resources/css/app.css` (`@theme` block)

---

## What Are Tokens?

Tokens are named values that replace magic numbers. Instead of writing `#2563eb` in five components, you write `bg-primary-600`. When the brand color changes, you update one line.

In this project tokens are defined in two places that work together:

| File | Purpose |
|------|---------|
| `resources/css/tokens.css` | CSS custom properties — available as `var(--color-primary)` in any CSS |
| `resources/css/app.css` (`@theme` block) | Registers tokens as Tailwind utility classes — `bg-primary-600`, `text-neutral-700`, etc. |

---

## Color Palette

### Primary (Blue)

Used for: main CTAs, active states, links, focus rings.

| Token | Hex | Tailwind class | Contrast on white |
|-------|-----|----------------|-------------------|
| `--color-primary-50` | `#eff6ff` | `bg-primary-50` | — |
| `--color-primary-100` | `#dbeafe` | `bg-primary-100` | — |
| `--color-primary-200` | `#bfdbfe` | `bg-primary-200` | — |
| `--color-primary-400` | `#60a5fa` | `text-primary-400` | — |
| `--color-primary-500` | `#3b82f6` | `text-primary-500` | 3.3:1 (large text only) |
| **`--color-primary-600`** | **`#2563eb`** | **`bg-primary-600`** | **4.56:1 ✅ AA** |
| `--color-primary-700` | `#1d4ed8` | `bg-primary-700` | 5.9:1 ✅ |
| `--color-primary-800` | `#1e40af` | `bg-primary-800` | 7.6:1 ✅ |
| `--color-primary-900` | `#1e3a8a` | `bg-primary-900` | 9.8:1 ✅ |

**Default button background:** `bg-primary-600` → hover `bg-primary-700`

---

### Accent (Gold)

Used for: election editorial pages, badges, highlights that need a warm tone.

| Token | Hex | Tailwind class | Contrast on white |
|-------|-----|----------------|-------------------|
| `--color-accent-50` | `#fffbeb` | `bg-accent-50` | — |
| `--color-accent-100` | `#fef3c7` | `bg-accent-100` | — |
| `--color-accent-400` | `#fbbf24` | `text-accent-400` | 1.9:1 ❌ (decorative only) |
| `--color-accent-500` | `#f59e0b` | `text-accent-500` | 2.3:1 ❌ (decorative only) |
| **`--color-accent-600`** | **`#a0742a`** | **`bg-accent-600`** | **4.51:1 ✅ AA** |
| `--color-accent-700` | `#92400e` | `bg-accent-700` | 6.1:1 ✅ |
| `--color-accent-800` | `#78350f` | `bg-accent-800` | 8.0:1 ✅ |

> **Note:** `accent-400` and `accent-500` (bright yellow-gold) must only be used decoratively — as borders, backgrounds, or icons — never as text on white. Use `accent-600` or darker for readable text.

---

### Semantic Colors

| Purpose | CSS var | Hex | Tailwind class |
|---------|---------|-----|----------------|
| Success background | `--color-success-bg` | `#f0fdf4` | `bg-green-50` |
| Success action | `--color-success-600` | `#059669` | `bg-success-600` |
| Danger background | `--color-danger-bg` | `#fef2f2` | `bg-red-50` |
| Danger action | `--color-danger-600` | `#dc2626` | `bg-danger-600` |
| Warning background | `--color-warning-bg` | `#fffbeb` | `bg-amber-50` |
| Warning action | `--color-warning-600` | `#d97706` | `bg-warning-600` |

---

### Neutral Scale

| Token | Hex | Common use |
|-------|-----|------------|
| `--color-neutral-50` | `#f9fafb` | Page background |
| `--color-neutral-200` | `#e5e7eb` | Borders, dividers |
| `--color-neutral-500` | `#6b7280` | Secondary text, placeholders |
| `--color-neutral-700` | `#374151` | Body text |
| `--color-neutral-900` | `#111827` | Headings, high-emphasis text |

Tailwind classes: `bg-neutral-50`, `text-neutral-700`, `border-neutral-200`, etc.

---

## Shadows

Defined as CSS custom properties and usable directly in `style` or via Tailwind:

```css
var(--shadow-sm)   /* subtle lift — cards at rest */
var(--shadow-md)   /* cards on hover */
var(--shadow-lg)   /* modals, dropdowns */
```

Tailwind equivalents: `shadow-sm`, `shadow-md`, `shadow-lg` (Tailwind's defaults).

---

## Transitions

```css
var(--transition-fast)    /* 150ms — buttons, toggles */
var(--transition-normal)  /* 200ms — cards, panels */
```

Tailwind equivalents: `duration-150`, `duration-200` with `transition-all`.

---

## How Tokens Become Tailwind Utilities

Tailwind v4 uses CSS-first configuration. In `resources/css/app.css`:

```css
@theme {
  --color-primary-600: #2563eb;
  /* ... */
}
```

This tells Tailwind v4 to generate:
- `bg-primary-600`
- `text-primary-600`
- `border-primary-600`
- `ring-primary-600`
- `fill-primary-600`
- `stroke-primary-600`

You do **not** need to add anything to `tailwind.config.js` for colors. Font families are the exception — they stay in `tailwind.config.js`.

---

## Adding a New Color

1. Add the CSS variable to `resources/css/tokens.css`:

```css
:root {
  --color-brand-info: #0891b2; /* cyan-600 */
}
```

2. Register it in the `@theme` block in `resources/css/app.css`:

```css
@theme {
  --color-info-600: #0891b2;
  --color-info-700: #0e7490;
}
```

3. Use it immediately in any component:

```html
<div class="bg-info-600 text-white">Info banner</div>
```

4. Run `npm run build` to verify.

---

## What NOT to Do

```css
/* ❌ Don't hard-code hex values in components */
background-color: #2563eb;

/* ❌ Don't use Tailwind's built-in blue instead of primary */
class="bg-blue-600"  /* use bg-primary-600 */

/* ❌ Don't use accent-400/500 as text on white (fails WCAG) */
class="text-accent-500"

/* ✅ Do use semantic token names */
class="bg-primary-600 hover:bg-primary-700"
class="text-accent-600"  /* passes AA */
```

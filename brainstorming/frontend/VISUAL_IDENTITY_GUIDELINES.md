# Visual Identity Guidelines

**Date:** 2026-06-14  
**Status:** Draft — derived from ADR-003 implementation  
**Applies to:** All public-facing pages using `PublicDigitLayout`

## Hero Section Gradients

| Pattern | Gradient | Blur Elements | Intended Use | Pages |
|---------|----------|---------------|--------------|-------|
| **Hero-Light** | `from-primary-100 via-primary-200 to-primary-50` | ✅ 2× blur circles | Informational, welcoming pages | About |
| **Hero-Standard** | `from-primary-200 via-primary-300 to-primary-100` | ✅ 2× blur circles | Guides, documentation, architecture | ElectionArchitecture, Security, Guide |
| **Hero-Strong** *(future)* | `from-primary-300 via-primary-400 to-primary-200` | ✅ 2× blur circles | Critical governance pages | — |

### Implementation template

```vue
<section class="relative bg-gradient-to-br from-primary-200 via-primary-300 to-primary-100 text-neutral-900 py-20 px-4 overflow-hidden">
  <div class="absolute inset-0 opacity-20">
    <div class="absolute top-0 left-1/4 w-96 h-96 bg-primary-500 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-primary-400 rounded-full blur-3xl"></div>
  </div>
  <div class="relative max-w-4xl mx-auto text-center">
    <!-- hero content -->
  </div>
</section>
```

## Section Background Alternation

Sections below the hero should alternate to create visual rhythm:

- `bg-white`
- `bg-primary-50`
- `bg-neutral-50`

No other background colors should be used for full-page sections.

## Typography

- **H1:** `text-4xl sm:text-5xl md:text-6xl font-bold text-neutral-900`
- **H2:** `text-3xl font-bold text-neutral-900`
- **Body:** `text-neutral-600` or `text-neutral-700`
- **Accent text:** `text-primary-600`

## Color Token Usage

| Token | When to Use |
|-------|-------------|
| `primary` | Action buttons, links, section highlights, branding |
| `neutral` | Backgrounds, body text, borders, secondary elements |
| `success` | Confirmations, verified states, positive outcomes |
| `warning` | Alerts, pending states, attention calls |
| `danger` | Errors, destructive actions |
| `accent` | Highlights, secondary brand elements |

**Reference:** ADR-003 Shell Visual Identity, Architecture Baseline v1.0

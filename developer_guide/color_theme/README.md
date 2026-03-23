# Design System — Developer Guide

**Branch:** `multitenancy`
**Implemented:** 2026-03-23
**Status:** Foundation complete — tokens, Button, Card

---

## Contents

| File | What it covers |
|------|----------------|
| [01-tokens.md](./01-tokens.md) | CSS custom properties, color palette, how tokens map to Tailwind utilities |
| [02-button.md](./02-button.md) | `Button.vue` — all variants, sizes, link mode, loading state |
| [03-card.md](./03-card.md) | `Card.vue` — modes (editorial/admin/default), variants, padding |
| [04-typography.md](./04-typography.md) | Font families, when to use serif vs sans, Tailwind utilities |
| [05-migration-guide.md](./05-migration-guide.md) | How to migrate existing pages — replacing raw divs and buttons |

---

## Quick Start

```bash
# Assets are built via Vite — no separate install needed
npm run dev    # development with HMR
npm run build  # production build
```

```vue
<!-- In any Vue component -->
<script setup>
import Button from '@/Components/Button.vue'
import Card   from '@/Components/Card.vue'
</script>

<template>
  <Card>
    <p>Some content</p>
    <Button variant="primary" @click="doSomething">Save</Button>
  </Card>
</template>
```

---

## Design Principles

1. **Incremental, not a rewrite.** Existing page-level designs (editorial Show.vue, dark Voters sidebar) are preserved. Only shared chrome (buttons, cards) is unified.
2. **Mode-aware components.** The same `Card` component behaves differently in editorial vs admin context via a `mode` prop — no need for separate components per page type.
3. **Token-first.** Colors live in `resources/css/tokens.css` as CSS custom properties and are registered with Tailwind v4's `@theme` block. Never hard-code hex values in components.
4. **WCAG AA by default.** All brand colors in the token file have been verified to meet 4.5:1 contrast on white backgrounds.

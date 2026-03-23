# Migration Guide — Existing Pages

How to update existing pages to use the unified design system components without breaking their visual identity.

---

## The Rule

**Only replace shared chrome — buttons and cards. Leave page-level design intact.**

| What | Action |
|------|--------|
| Raw `<button>` elements | Replace with `<Button>` |
| Raw `<div class="bg-white border...">` cards | Replace with `<Card>` |
| Page-level CSS (`esp-*`, BEM, scoped styles) | Leave alone |
| Dark sidebar on Voters/Index | Leave alone |
| Hard-coded `gray-*` colours in page text | Leave alone for now |

---

## Step-by-Step: Migrating a Page

### 1. Import the components

Add to `<script setup>`:

```js
import Button from '@/Components/Button.vue'
import Card   from '@/Components/Card.vue'
```

### 2. Identify raw `<button>` elements

Search for:
```
<button class="
```

For each one, map the intent to a variant:

| Old class pattern | Variant |
|-------------------|---------|
| `bg-blue-*`, `bg-indigo-*`, `bg-primary-*` | `primary` |
| `bg-red-*`, `bg-danger-*` | `danger` |
| `bg-green-*`, `bg-success-*` | `success` |
| `bg-gray-*`, `bg-neutral-*` (dark) | `secondary` |
| `border border-gray-*` or `bg-white` | `outline` |
| No background, hover only | `ghost` |

Replace:
```html
<!-- Before -->
<button
  class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg"
  @click="save"
>
  Save
</button>

<!-- After -->
<Button variant="primary" @click="save">Save</Button>
```

### 3. Identify raw card divs

Search for:
```
<div class="bg-white border
```
or:
```
<div class="rounded-xl
```

For each one, pick the mode:

| Page type | Mode |
|-----------|------|
| Election public page (`Show.vue`) | `editorial` |
| Management, settings, admin | `admin` |
| Dashboard, overview | `default` |

Replace:
```html
<!-- Before -->
<div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
  <h3 class="text-lg font-semibold">Election Settings</h3>
</div>

<!-- After -->
<Card mode="admin">
  <h3 class="text-lg font-semibold">Election Settings</h3>
</Card>
```

### 4. Build and visually check

```bash
npm run build
# or in dev mode:
npm run dev
```

Compare the before/after visually. The goal is a look that's identical or improved, never broken.

### 5. Commit per page

```bash
git add resources/js/Pages/Election/Management.vue
git commit -m "feat: apply unified Button and Card to Management.vue"
```

One commit per page makes it easy to roll back if a specific page has issues.

---

## Pages — Current Status

| Page | Status | Notes |
|------|--------|-------|
| `Election/Show.vue` | ✅ Partial | Stats section migrated; editorial hero CSS left intact |
| `Election/Management.vue` | ⬜ Pending | Target: Tasks 8 in implementation plan |
| `Organisations/Show.vue` | ⬜ Pending | Target: Task 9 in implementation plan |
| `Elections/Voters/Index.vue` | 🚫 Leave | Dark sidebar is intentional UX — do not change |
| `Dashboard/ElectionDashboard.vue` | ⬜ Future | |
| `Dashboard/MainDashboard.vue` | ⬜ Future | |

---

## Handling Inline Styles and Scoped CSS

Some pages use `<style scoped>` blocks with custom classes (e.g. `esp-*` in Show.vue, `voters-*` in Voters/Index). **Do not touch these.** They define the visual identity of those pages.

Only replace interactive elements (buttons) and container elements (cards) that have no semantic CSS class of their own.

---

## Handling Jetstream Components

The Jetstream components (`Jetstream/Button.vue`, `Jetstream/DangerButton.vue`, `Jetstream/SecondaryButton.vue`) are **not deleted** — they are used by Jetstream's own auth pages and may be referenced in older parts of the codebase.

Migration strategy:
1. When touching a page, replace Jetstream button usage with the unified Button
2. Do not do a global search-and-replace — it risks breaking auth flows
3. Once all non-Jetstream usage is migrated, the Jetstream files can eventually be removed

---

## Finding What Still Needs Migration

```bash
# Find raw button elements in Vue pages
grep -rn "<button " resources/js/Pages/ --include="*.vue"

# Find raw card-style divs
grep -rn 'class="bg-white border' resources/js/Pages/ --include="*.vue"

# Find Jetstream button usage in Pages (not in Jetstream/ folder itself)
grep -rn "jet-button\|JetButton\|Jetstream/Button" resources/js/Pages/ --include="*.vue"
```

---

## What Counts as Done

A page is "migrated" when:

1. No raw `<button class="bg-...">` patterns remain for standard actions
2. No raw `<div class="bg-white border ...rounded">` patterns remain for content cards
3. `<Button>` and `<Card>` are used for those elements instead
4. `npm run build` passes
5. Visual regression check passes (page looks correct in browser)
6. Committed with a descriptive message

Page-level CSS, scoped styles, and the overall layout of the page remain untouched.

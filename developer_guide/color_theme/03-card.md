# Card Component

**File:** `resources/js/Components/Card.vue`

A container for grouping related content. Supports three design modes to match the visual language of different page types without requiring separate components.

---

## Basic Usage

```vue
<script setup>
import Card from '@/Components/Card.vue'
</script>

<template>
  <Card>
    <h2>Election Summary</h2>
    <p>Total voters: 142</p>
  </Card>
</template>
```

Renders a white card with a neutral border, medium padding, and `rounded-xl`.

---

## Props

| Prop | Type | Default | Options |
|------|------|---------|---------|
| `mode` | String | `'default'` | `default` `editorial` `admin` |
| `variant` | String | `'default'` | `default` `primary` `success` `warning` `danger` |
| `padding` | String | `'md'` | `none` `sm` `md` `lg` |
| `hover` | Boolean | `false` | Adds lift + shadow on hover |

All other attributes pass through via `v-bind="$attrs"` (useful for `class`, `data-*`, `@click`).

---

## Modes

Modes express the design context of the page, not the status of the content. Pick the mode based on where the card lives.

### `default` — standard admin/data pages

```vue
<Card>
  <p class="text-sm text-neutral-500">Total Voters</p>
  <p class="text-2xl font-bold text-neutral-900">142</p>
</Card>
```

White background, neutral border, `shadow-sm`. Use on dashboards, settings pages, and anywhere with no strong visual identity.

---

### `editorial` — election public pages

```vue
<Card mode="editorial">
  <h2 class="text-2xl">Presidential Election 2026</h2>
  <p>Voting is open until 17:00</p>
</Card>
```

Warm amber-tinted background (`bg-amber-50`), gold border, `shadow-md`, `font-serif`. Matches the editorial design language of `Election/Show.vue`. Use on voter-facing election pages.

---

### `admin` — management and configuration pages

```vue
<Card mode="admin">
  <h3 class="text-lg font-semibold">Election Settings</h3>
  <!-- form fields -->
</Card>
```

White background, neutral border, `shadow-sm`, `font-sans`. Identical appearance to `default` but semantically scoped to admin contexts. Use on `Management.vue`, voter lists, settings forms.

---

## Variants

Variants express the status or meaning of the card's content. Combine with any mode.

```vue
<Card variant="primary">   <!-- light blue bg + blue border -->
<Card variant="success">   <!-- light green bg + green border -->
<Card variant="warning">   <!-- light amber bg + amber border -->
<Card variant="danger">    <!-- light red bg + red border -->
```

### When to use each variant

| Variant | Use case |
|---------|----------|
| `default` | Neutral information |
| `primary` | Highlighted or selected state |
| `success` | Completed action confirmation, positive status |
| `warning` | Pending action required, time-sensitive info |
| `danger` | Error state, destructive context |

---

## Padding

```vue
<Card padding="none">  <!-- p-0 — for cards with custom internal layout -->
<Card padding="sm">    <!-- p-4 — stat cards, compact displays -->
<Card padding="md">    <!-- p-6 — default, general content -->
<Card padding="lg">    <!-- p-8 — hero cards, forms -->
```

---

## Hover

```vue
<Card :hover="true" @click="openElection">
  <h3>{{ election.name }}</h3>
</Card>
```

Adds `hover:shadow-lg hover:-translate-y-0.5 cursor-pointer`. Use when the entire card is clickable (list items, navigation cards). When using `hover`, always add a click handler or wrap content in a link.

---

## Real Examples

### Stat card (compact, no hover)

```vue
<div class="grid grid-cols-3 gap-4">
  <Card padding="sm" :hover="false">
    <p class="text-sm text-neutral-500">Total Voters</p>
    <p class="text-2xl font-bold text-neutral-900">{{ stats.total }}</p>
  </Card>

  <Card padding="sm" variant="success" :hover="false">
    <p class="text-sm text-neutral-500">Voted</p>
    <p class="text-2xl font-bold text-neutral-900">{{ stats.voted }}</p>
  </Card>

  <Card padding="sm" variant="warning" :hover="false">
    <p class="text-sm text-neutral-500">Remaining</p>
    <p class="text-2xl font-bold text-neutral-900">{{ stats.remaining }}</p>
  </Card>
</div>
```

---

### Election list card (clickable, hover enabled)

```vue
<Card
  v-for="election in elections"
  :key="election.id"
  :hover="true"
  padding="md"
  @click="router.visit(route('election.show', election.slug))"
>
  <h3 class="font-semibold text-neutral-900">{{ election.name }}</h3>
  <p class="text-sm text-neutral-500 mt-1">{{ election.start_date }}</p>
</Card>
```

---

### Form section card (admin mode)

```vue
<Card mode="admin" padding="lg">
  <h2 class="text-lg font-semibold text-neutral-900 mb-4">Election Settings</h2>
  <form @submit.prevent="save">
    <!-- inputs -->
    <div class="mt-6 flex gap-3 justify-end">
      <Button variant="outline" @click="cancel">Cancel</Button>
      <Button variant="primary" type="submit" :loading="saving">Save</Button>
    </div>
  </form>
</Card>
```

---

### Editorial voter-facing card

```vue
<Card mode="editorial" padding="lg">
  <p class="text-sm font-mono text-accent-600 uppercase tracking-widest mb-2">
    Presidential Election
  </p>
  <h1 class="text-3xl font-serif text-neutral-900">Cast Your Vote</h1>
  <p class="mt-2 text-neutral-600">
    Voting closes at 17:00 on 25 March 2026
  </p>
  <Button variant="primary" size="lg" class="mt-6">Vote Now</Button>
</Card>
```

---

## Adding Extra Classes

Since `v-bind="$attrs"` is set, you can add any Tailwind class directly:

```vue
<!-- Extra top margin -->
<Card class="mt-8">...</Card>

<!-- Custom max-width -->
<Card class="max-w-lg mx-auto">...</Card>

<!-- No override of internal classes — they compose -->
```

---

## Common Mistakes

```vue
<!-- ❌ Don't create raw divs for cards in new code -->
<div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
  Content
</div>

<!-- ✅ Use Card -->
<Card>Content</Card>

<!-- ❌ Don't use mode="editorial" on admin management pages -->
<Card mode="editorial">  <!-- wrong — admin page doesn't use serif font -->
  <h3>Voter Management</h3>
</Card>

<!-- ✅ Match mode to page context -->
<Card mode="admin">
  <h3>Voter Management</h3>
</Card>

<!-- ❌ Don't set hover on non-clickable cards — misleads users -->
<Card :hover="true">
  <p>Just some information</p>
</Card>
```

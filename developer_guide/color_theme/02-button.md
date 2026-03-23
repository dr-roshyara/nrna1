# Button Component

**File:** `resources/js/Components/Button.vue`
**Replaces:** `Jetstream/Button.vue`, `Jetstream/DangerButton.vue`, `Jetstream/SecondaryButton.vue` (at usage sites — Jetstream files are not deleted)

---

## Basic Usage

```vue
<script setup>
import Button from '@/Components/Button.vue'
</script>

<template>
  <Button @click="save">Save Changes</Button>
</template>
```

Renders a `<button type="button">` with `variant="primary"` and `size="md"` by default.

---

## Props

| Prop | Type | Default | Options |
|------|------|---------|---------|
| `variant` | String | `'primary'` | `primary` `secondary` `outline` `ghost` `danger` `accent` `success` |
| `size` | String | `'md'` | `sm` `md` `lg` |
| `loading` | Boolean | `false` | Shows spinner, disables button |
| `disabled` | Boolean | `false` | Greyed out, not clickable |
| `type` | String | `'button'` | `button` `submit` `reset` |
| `as` | String | `'button'` | `'button'` or `'a'` — renders as anchor when `'a'` |
| `href` | String | `undefined` | Only used when `as="a"` |

All other attributes (e.g. `class`, `@click`, `data-*`) pass through via `v-bind="$attrs"`.

---

## Variants

### Primary — default CTA

```vue
<Button variant="primary">Cast Your Vote</Button>
```

Blue background (`bg-primary-600`). Use for the single main action on a page.

---

### Secondary

```vue
<Button variant="secondary">Export CSV</Button>
```

Neutral dark background. Use for secondary actions that are still prominent.

---

### Outline

```vue
<Button variant="outline">Cancel</Button>
```

White background with neutral border. Use for cancel/back actions alongside a primary button.

---

### Ghost

```vue
<Button variant="ghost">View Details</Button>
```

No background, no border. Appears on hover only. Use in dense tables or toolbars.

---

### Danger

```vue
<Button variant="danger">Delete Election</Button>
```

Red background (`bg-danger-600`). Use for irreversible destructive actions. Always pair with a confirmation dialog.

---

### Accent

```vue
<Button variant="accent">Download Results</Button>
```

Gold background (`bg-accent-600`). Use sparingly — editorial or brand-highlighted actions only.

---

### Success

```vue
<Button variant="success">Confirm Selection</Button>
```

Green background (`bg-success-600`). Use for completion or confirmation actions.

---

## Sizes

```vue
<Button size="sm">Small</Button>   <!-- px-3 py-1.5 text-sm -->
<Button size="md">Medium</Button>  <!-- px-4 py-2 text-base (default) -->
<Button size="lg">Large</Button>   <!-- px-6 py-3 text-lg -->
```

---

## Loading State

```vue
<Button variant="primary" :loading="isSubmitting" @click="submit">
  Save
</Button>
```

When `loading` is `true`:
- A spinner SVG appears to the left of the slot content
- The button is disabled (no click)
- `cursor-wait` is applied
- `aria-busy="true"` is set for screen readers

```vue
<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import Button from '@/Components/Button.vue'

const saving = ref(false)

function save() {
  saving.value = true
  router.post('/endpoint', data, {
    onFinish: () => { saving.value = false },
  })
}
</script>

<template>
  <Button variant="primary" :loading="saving" @click="save">
    Save Election
  </Button>
</template>
```

---

## Link Mode (`as="a"`)

Use `as="a"` when the button is a navigation link, not an action. This renders an `<a>` element with button styling — correct semantics, correct keyboard behaviour.

```vue
<!-- Internal Inertia link -->
<Button as="a" :href="route('election.show', election.id)" variant="outline">
  View Election
</Button>

<!-- External link -->
<Button as="a" href="https://example.com" variant="ghost" target="_blank">
  Documentation
</Button>
```

> **When to use `as="a"` vs `@click`:**
> - Navigation to another page → `as="a"` with `href`
> - Triggering an action (API call, modal open, form submit) → `@click`

---

## Form Submit Button

```vue
<form @submit.prevent="submit">
  <!-- form fields -->
  <Button type="submit" variant="primary" :loading="form.processing">
    Submit
  </Button>
</form>
```

---

## With Icon

```vue
<script setup>
import { PlusIcon } from '@heroicons/vue/24/outline'
import Button from '@/Components/Button.vue'
</script>

<template>
  <Button variant="primary">
    <PlusIcon class="h-4 w-4 mr-2" />
    Add Voter
  </Button>
</template>
```

---

## Accessibility

The Button component sets:

| Attribute | When |
|-----------|------|
| `aria-busy="true"` | `loading` is true |
| `aria-disabled="true"` | `loading` or `disabled` is true |
| `disabled` attribute | `loading` or `disabled` (on `<button>` only) |
| Focus ring | Always — `focus:ring-2 focus:ring-offset-2` with variant-matching ring colour |

---

## Common Mistakes

```vue
<!-- ❌ Don't use Jetstream buttons for new UI -->
<jet-button>Save</jet-button>

<!-- ❌ Don't use raw <button> with manual Tailwind for standard actions -->
<button class="bg-blue-600 text-white px-4 py-2 rounded">Save</button>

<!-- ✅ Use the unified Button -->
<Button variant="primary">Save</Button>

<!-- ❌ Don't use <a> tags styled as buttons -->
<a href="/election" class="btn btn-primary">View</a>

<!-- ✅ Use as="a" -->
<Button as="a" :href="route('election.show', id)" variant="outline">View</Button>
```

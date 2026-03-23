# Typography

**Configured in:** `tailwind.config.js` (font families), `resources/css/tokens.css` (CSS vars)
**Fonts loaded in:** `resources/views/app.blade.php` (Google Fonts, single global request)

---

## Font Families

Three fonts are available as Tailwind utilities:

| Utility | Font | CSS var | Use |
|---------|------|---------|-----|
| `font-sans` | Inter → Nunito → system-ui | `--font-sans` | All UI — body text, labels, buttons, navigation |
| `font-serif` | Cormorant Garamond → Georgia | `--font-serif` | Editorial — election public pages, hero headings |
| `font-mono` | DM Mono → JetBrains Mono | `--font-mono` | Code, vote codes, timestamps, monospaced data |

---

## When to Use Each Font

### `font-sans` (Inter)

Everything by default. The `<body>` has `font-sans antialiased` set in `app.blade.php`. You do not need to add it unless overriding a different font on a child element.

```html
<!-- Always visible — nav, tables, buttons, forms, dashboards -->
<p class="text-neutral-700">42 voters have cast their ballots.</p>
<label class="font-medium text-neutral-700">Election Name</label>
```

---

### `font-serif` (Cormorant Garamond)

Use only on the voter-facing election public pages (`Election/Show.vue` and its components). It signals "this is important, civic, human" — the editorial voice of the platform.

```html
<!-- Election Show page headings -->
<h1 class="font-serif text-4xl font-semibold text-neutral-900">
  Presidential Election 2026
</h1>

<!-- Subheading with weight variation -->
<h2 class="font-serif text-2xl text-neutral-700">
  Cast your vote before 17:00
</h2>
```

**Do not use `font-serif` on:**
- Management dashboards
- Voter lists or admin tables
- Form labels or buttons
- Error messages

---

### `font-mono` (DM Mono)

Use for data that benefits from fixed-width rendering: vote codes, IDs, timestamps, election results numbers.

```html
<!-- Vote verification code -->
<span class="font-mono text-sm tracking-widest text-neutral-700">
  {{ voteCode }}
</span>

<!-- Election result percentage -->
<span class="font-mono text-2xl font-medium">73.4%</span>

<!-- Timestamp -->
<time class="font-mono text-xs text-neutral-500">2026-03-23 14:37:02</time>
```

---

## Type Scale

Use Tailwind's built-in type scale. No custom sizes are defined — the standard scale covers all cases:

| Class | Size | Line height | Typical use |
|-------|------|-------------|-------------|
| `text-xs` | 12px | 16px | Captions, timestamps, fine print |
| `text-sm` | 14px | 20px | Secondary labels, helper text |
| `text-base` | 16px | 24px | Body text (default) |
| `text-lg` | 18px | 28px | Section subheadings |
| `text-xl` | 20px | 28px | Card headings |
| `text-2xl` | 24px | 32px | Page section headings |
| `text-3xl` | 30px | 36px | Page titles (admin) |
| `text-4xl` | 36px | 40px | Hero headings (editorial) |

---

## Common Text Colour Patterns

```html
<!-- High emphasis — headings, primary content -->
<h1 class="text-neutral-900">Election Name</h1>

<!-- Medium emphasis — body text -->
<p class="text-neutral-700">Description or instruction text.</p>

<!-- Low emphasis — secondary / helper text -->
<p class="text-neutral-500">Last updated 10 minutes ago.</p>

<!-- Placeholder / disabled -->
<span class="text-neutral-400">No results yet.</span>

<!-- Brand coloured — links, active states -->
<a class="text-primary-600 hover:text-primary-700">View details</a>

<!-- Editorial accent — labels on election pages -->
<span class="text-accent-600 font-mono uppercase tracking-widest text-xs">
  Live Election
</span>
```

---

## Loading

Fonts are loaded once globally in `resources/views/app.blade.php`:

```html
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=DM+Mono:wght@400;500&family=Inter:wght@300;400;500;600;700&family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
```

**Do not add font `<link>` tags inside Vue components or page `<Head>` blocks.** This was the previous pattern (in `Election/Show.vue`) and caused fonts to load twice.

---

## Adding a New Font

If a new font is needed:

1. Add it to the single `<link>` in `app.blade.php`
2. Add a `fontFamily` entry in `tailwind.config.js`
3. Document the use case here

Do not add a new font without a clear use case — every font adds a network request.

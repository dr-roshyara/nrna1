# SEO in Translation Files

## Overview

Public Digit uses a **single source of truth** model: every page-specific JSON locale file can contain both its **UI translations** and its **SEO meta tags** inside a `"seo"` section.

This means you only ever touch **one file per page per language** — no separate PHP `seo.php` entries needed.

---

## How It Works

```
resources/js/locales/pages/DemoResult/de.json
├── "page_title"    ← UI string used by Vue component ($t)
├── "stats"         ← UI strings
├── "actions"       ← UI strings
└── "seo"           ← Read by PHP SeoService on the server
    ├── "title"
    ├── "description"
    ├── "keywords"
    └── "robots"
```

### Priority Chain

When `SeoService::getMeta('demo.result')` is called, it checks in this order:

```
1. resources/js/locales/pages/DemoResult/{locale}.json  → seo.title   ✅ highest priority
2. resources/lang/{locale}/seo.php                      → pages.demo.result.title
3. resources/lang/{locale}/seo.php                      → site.title  (site default)
4. config/meta.php                                      → title       (last resort)
```

If the JSON `seo` section exists → it wins. The PHP `seo.php` file becomes an optional fallback only.

---

## Step-by-Step: Adding SEO to a New Page

### Step 1 — Add a `seo` section to each locale JSON file

**File:** `resources/js/locales/pages/MyPage/de.json`

```json
{
  "title": "Meine Seite",
  "subtitle": "Willkommen auf meiner Seite.",

  "seo": {
    "title": "Meine Seite | Public Digit",
    "description": "Eine kurze, prägnante Beschreibung dieser Seite für Suchmaschinen. Maximal 160 Zeichen.",
    "keywords": "schlüsselwort1, schlüsselwort2, schlüsselwort3",
    "robots": "index, follow"
  }
}
```

**File:** `resources/js/locales/pages/MyPage/en.json`

```json
{
  "title": "My Page",
  "subtitle": "Welcome to my page.",

  "seo": {
    "title": "My Page | Public Digit",
    "description": "A short, compelling description of this page for search engines. Maximum 160 characters.",
    "keywords": "keyword1, keyword2, keyword3",
    "robots": "index, follow"
  }
}
```

**File:** `resources/js/locales/pages/MyPage/np.json`

```json
{
  "title": "मेरो पृष्ठ",
  "subtitle": "मेरो पृष्ठमा स्वागत छ।",

  "seo": {
    "title": "मेरो पृष्ठ | Public Digit",
    "description": "खोज इन्जिनका लागि यस पृष्ठको छोटो विवरण।",
    "keywords": "कुञ्जी शब्द१, कुञ्जी शब्द२",
    "robots": "index, follow"
  }
}
```

---

### Step 2 — Register the locale file in `i18n.js`

**File:** `resources/js/i18n.js`

```javascript
// Import
import myPageDe from './locales/pages/MyPage/de.json';
import myPageEn from './locales/pages/MyPage/en.json';
import myPageNp from './locales/pages/MyPage/np.json';

// Register under pages.my-page (kebab-case key)
const messages = {
  de: {
    pages: {
      'my-page': myPageDe,
      // ...existing entries
    }
  },
  en: {
    pages: {
      'my-page': myPageEn,
    }
  },
  np: {
    pages: {
      'my-page': myPageNp,
    }
  },
}
```

---

### Step 3 — Map the route in `InjectPageMeta`

**File:** `app/Http/Middleware/InjectPageMeta.php`

```php
$page = match (true) {
    // ...existing mappings
    $routeName === 'my-page.show' => 'my.page',   // page key
    default => 'home',
};
```

> **Folder name rule:** The page key is converted to a PascalCase folder name automatically.
> `'my.page'` → `MyPage` → looks for `resources/js/locales/pages/MyPage/{locale}.json`

---

### Step 4 — Use translations in the Vue component

**File:** `resources/js/Pages/MyPage/Index.vue`

```vue
<script setup>
// No SEO imports needed — handled server-side via InjectPageMeta
</script>

<template>
  <div>
    <h1>{{ $t('pages.my-page.title') }}</h1>
    <p>{{ $t('pages.my-page.subtitle') }}</p>
  </div>
</template>
```

The `seo` section is **invisible to Vue** — it is only read by `SeoService` on the server. Your component uses all other keys normally with `$t()`.

---

## Page Key → Folder Name Mapping

`SeoService` converts the page key to a folder name using this rule:

| Page key | Folder name |
|----------|------------|
| `demo.result` | `DemoResult` |
| `my.page` | `MyPage` |
| `vereinswahlen` | `Vereinswahlen` |
| `elections.show` | `ElectionsShow` |
| `vote.create` | `VoteCreate` |

Each dot-separated segment is PascalCased and joined. The folder must match exactly (case-sensitive on Linux servers).

---

## SEO Field Reference

| Field | Max length | Purpose | Example |
|-------|-----------|---------|---------|
| `title` | **60 chars** | Browser tab + Google headline | `"Demo Results | Public Digit"` |
| `description` | **160 chars** | Google snippet below title | `"View vote counts and candidate rankings..."` |
| `keywords` | 5–10 phrases | Meta keywords (minor signal) | `"voting results, election, candidates"` |
| `robots` | — | Crawler instruction | `"index, follow"` or `"noindex, nofollow"` |

> `SeoHelper::optimizeTitle()` and `SeoHelper::optimizeDescription()` automatically truncate at word boundaries if limits are exceeded. Write concise descriptions to avoid truncation.

### `robots` values

| Value | Use case |
|-------|---------|
| `"index, follow"` | Public pages — allow Google to index and follow links |
| `"noindex, nofollow"` | Private/authenticated pages — dashboard, profile, demo results |
| `"noindex, follow"` | Transitional pages — allow link following but skip indexing |

---

## Real Example: DemoResult Page

**`resources/js/locales/pages/DemoResult/de.json`** (excerpt)

```json
{
  "page_title": "Demo-Wahlergebnisse",
  "subtitle": "Sehen Sie umfassende Ergebnisse der Demo-Wahl...",

  "seo": {
    "title": "Demo-Wahlergebnisse | Public Digit",
    "description": "Sehen Sie umfassende Ergebnisse der Public Digit Demo-Wahl — Stimmzahlen, Kandidaten-Rankings und vollständige Wahlstatistiken.",
    "keywords": "Demo-Wahlergebnisse, Online-Wahlresultate, Kandidatenranking",
    "robots": "noindex, nofollow"
  },

  "stats": {
    "total_votes": "Abgegebene Stimmen",
    ...
  }
}
```

The `page_title` key is used by Vue (`$t('pages.demo-result.page_title')`).
The `seo.title` key is read by PHP and injected into the initial HTML `<head>`.

---

## When to Use Each Approach

| Situation | Use JSON `seo` section | Use PHP `seo.php` |
|-----------|----------------------|-------------------|
| New page with its own locale folder | ✅ Preferred | Optional fallback |
| Page without a locale folder | ❌ No JSON file | ✅ Required |
| Overriding site-level defaults | ✅ | ✅ |
| Dynamic meta (election name in title) | ❌ | Pass `$overrides` to `getMeta()` |

---

## Testing

After adding a `seo` section, verify with the artisan command:

```bash
# Check all pages for length compliance
php artisan seo:optimize-meta --all-locales

# Check keyword presence for a specific page
php artisan seo:check-vereinswahlen --locale=de

# Verify the title appears in the raw HTML (before JS runs)
curl -H "Cookie: locale=de" http://localhost:8000/demo/result | grep -i "<title>"
```

Run the unit test suite to confirm nothing is broken:

```bash
php artisan test --filter=SeoServiceTest
```

Expected output: **18 tests, 94 assertions, all passing.**

---

## Common Mistakes

### ❌ Wrong: `seo` key at wrong level

```json
{
  "title": "My Page",
  "seo_title": "My Page | Public Digit"
}
```

### ✅ Correct: nested under `"seo"`

```json
{
  "title": "My Page",
  "seo": {
    "title": "My Page | Public Digit"
  }
}
```

---

### ❌ Wrong: folder name does not match page key conversion

```
Page key:   "vote.create"
Folder:     vote-create/      ← wrong (kebab-case)
Correct:    VoteCreate/       ← PascalCase, no separator
```

---

### ❌ Wrong: title too long — will be auto-truncated

```json
"seo": {
  "title": "Willkommen bei Public Digit — der sicheren Plattform für digitale Online-Wahlen"
}
```

This is 83 characters — `SeoHelper` truncates it to 57 + `...`. Write it under 60 chars from the start.

---

## File Checklist

When adding SEO to a new page, tick each item:

- [ ] `seo` section added to `de.json`
- [ ] `seo` section added to `en.json`
- [ ] `seo` section added to `np.json`
- [ ] All titles ≤ 60 characters
- [ ] All descriptions ≤ 160 characters
- [ ] `robots` set correctly (`noindex` for auth pages)
- [ ] Route mapped in `InjectPageMeta.php`
- [ ] `php artisan seo:optimize-meta --all-locales` shows no warnings
- [ ] `php artisan test --filter=SeoServiceTest` passes

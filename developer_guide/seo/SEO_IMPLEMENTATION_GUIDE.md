# Public Digit - SEO Implementation Guide

**Version:** 1.0.0
**Last Updated:** February 2026
**Status:** Phase 1 Complete ✅

---

## Overview

This guide documents the SEO implementation for Public Digit using the **useMeta composable** and **translation-driven meta tags**.

### What's Been Implemented (Phase 1)

✅ **Phase 1A:** SEO keys added to all 3 language files (en.json, de.json, np.json)
✅ **Phase 1B:** useMeta composable created (`resources/js/composables/useMeta.js`)
✅ **Phase 1C:** MetaTag debug component created (`resources/js/components/MetaTag.vue`)
✅ **Phase 1D:** HandleInertiaRequests middleware updated with SEO props
✅ **Phase 1E:** Organizations/Show page implemented as working example

---

## Architecture

### Translation-First SEO

All SEO meta tags are **stored in translation files** (en.json, de.json, np.json), making them:
- **Language-aware:** Different meta tags for each language
- **Content-driven:** Marketers can update SEO without touching code
- **Maintainable:** Centralized source of truth for all pages
- **Scalable:** Easy to add new pages and languages

### SEO Translation Structure

```json
{
  "seo": {
    "site": {
      "title": "Public Digit",
      "description": "...",
      "keywords": "..."
    },
    "pages": {
      "home": { "title": "...", "description": "...", "keywords": "..." },
      "pricing": { "title": "...", "description": "...", "keywords": "..." },
      "organizations.show": {
        "title": "{organizationName} | Public Digit",
        "description": "{organizationName}: {memberCount} members, {electionCount} elections",
        "keywords": "..."
      }
    }
  }
}
```

### Data Flow

```
Translation Files (en.json, de.json, np.json)
    ↓
useMeta Composable (reads translations)
    ↓
Document Head (title, meta tags, canonical URL)
    ↓
Search Engine Indexing + Social Sharing
```

---

## Using useMeta Composable

### Basic Usage

Import and use in any Vue component:

```javascript
import { useMeta } from '@/composables/useMeta'

// Simple - uses default site meta
useMeta()

// Use page-specific translations
useMeta({ pageKey: 'home' })

// With parameters (for dynamic pages)
useMeta({
  pageKey: 'organizations.show',
  params: {
    organizationName: organisation.name,
    memberCount: organisation.members_count,
    electionCount: organisation.elections_count
  }
})
```

### Full Options

```javascript
useMeta({
  // Translation key (e.g., 'home', 'pricing', 'organizations.show')
  pageKey: 'organizations.show',

  // Parameter substitution for template variables
  params: {
    organizationName: 'NRNA Germany',
    memberCount: 1250,
    electionCount: 5
  },

  // Override specific fields (optional)
  title: 'Custom Title',
  description: 'Custom Description',
  keywords: 'custom, keywords',

  // Image for social sharing
  image: 'https://example.com/custom-image.jpg',

  // Custom canonical URL
  url: 'https://example.com/custom-url',

  // Prevent search engine indexing (sensitive pages)
  noindex: false,
  nofollow: false,

  // Page type for Open Graph
  type: 'website' // or 'article', 'product', etc.
})
```

### Return Values

The composable returns computed properties for use in templates:

```javascript
const { seoData, title, description, keywords, url, image } = useMeta({...})

// Access in template
<p>{{ title }}</p>
<p>{{ description }}</p>
```

---

## Adding SEO to New Pages

### Step 1: Add Translation Keys

Edit `resources/js/locales/en.json`, `de.json`, and `np.json`:

```json
{
  "seo": {
    "pages": {
      "your-page-name": {
        "title": "Page Title | Public Digit",
        "description": "Page description (max 160 chars, includes {variables} if dynamic)",
        "keywords": "keyword1, keyword2, keyword3"
      }
    }
  }
}
```

### Step 2: Use useMeta in Component

```vue
<script setup>
import { useMeta } from '@/composables/useMeta'

const props = defineProps({
  // Your component props
  data: Object
})

// For static pages
useMeta({ pageKey: 'your-page-name' })

// For dynamic pages with parameters
useMeta({
  pageKey: 'your-page-name',
  params: {
    variableName: props.data?.name
  }
})
</script>
```

### Step 3: Remove Old Meta Management

If the page previously used Inertia's `<Head>` component, remove it:

```vue
<!-- Remove this -->
<Head title="Old Title" />
```

---

## Template Variables (Parameter Substitution)

For dynamic pages, use `{variableName}` in your translation strings:

```json
{
  "title": "{organizationName} - Elections | Public Digit",
  "description": "{organizationName}: {memberCount} members, {electionCount} elections"
}
```

Then provide the values:

```javascript
useMeta({
  pageKey: 'organizations.show',
  params: {
    organizationName: 'NRNA Germany',
    memberCount: 1250,
    electionCount: 5
  }
})
```

The composable will replace `{organizationName}`, `{memberCount}`, and `{electionCount}` with the actual values.

---

## Meta Tags Generated

### Document Title

```html
<title>Page Title | Public Digit</title>
```

### Basic Meta Tags

```html
<meta name="description" content="Page description...">
<meta name="keywords" content="keyword1, keyword2">
<meta name="robots" content="index, follow">
```

### Open Graph (Social Sharing)

```html
<meta property="og:title" content="Page Title | Public Digit">
<meta property="og:description" content="Page description...">
<meta property="og:image" content="https://example.com/og-image.jpg">
<meta property="og:url" content="https://publicdigit.com/page">
<meta property="og:locale" content="en_US">
<meta property="og:type" content="website">
```

### Twitter Cards

```html
<meta name="twitter:title" content="Page Title | Public Digit">
<meta name="twitter:description" content="Page description...">
<meta name="twitter:image" content="https://example.com/og-image.jpg">
<meta name="twitter:card" content="summary_large_image">
```

### Canonical URL

```html
<link rel="canonical" href="https://publicdigit.com/page">
```

---

## Special Cases

### Sensitive Pages (Voting, Admin)

Use `noindex` to prevent search engine indexing:

```javascript
useMeta({
  pageKey: 'voting.start',
  noindex: true // Hide from search results
})
```

This sets:
```html
<meta name="robots" content="noindex, follow">
```

### Custom Overrides

Override specific fields without changing translations:

```javascript
useMeta({
  pageKey: 'home',
  title: 'Special Promotion - Limited Time',
  description: 'Updated description for this visit'
})
```

### Multi-Language Parameter Substitution

If organisation names vary by language, handle in the composable:

```javascript
const orgNameKey = `organizations.${organisation.slug}.name`
useMeta({
  pageKey: 'organizations.show',
  params: {
    organizationName: t(orgNameKey, organisation.name) // Fallback to English
  }
})
```

---

## Debug & Testing

### Development Debug Panel

The `MetaTag.vue` component shows current meta tags in development mode:

```vue
<template>
  <MetaTag />
</template>
```

Shows:
- ✓ Title (with character count)
- ✓ Description (with character count and warnings if too long)
- ✓ Keywords
- ✓ OG Image preview
- ✓ Robots setting
- ✓ Current URL
- ✓ Locale

### Character Count Warnings

- **Title:** ⚠️ Warn if > 60 characters (Google displays ~60)
- **Description:** ⚠️ Warn if > 160 characters (Google displays 155-160)

### Browser DevTools

Check meta tags in browser DevTools:

```
Right-click → Inspect → Head section
```

Look for:
- `<title>` tag
- `<meta name="description">`
- `<meta property="og:*">`
- `<meta name="twitter:*">`
- `<link rel="canonical">`

### Google Search Console

Test meta tags and preview:

1. Open [Google Search Console](https://search.google.com/search-console)
2. Go to **URL Inspection**
3. Enter your page URL
4. Check **Indexed** tab to see how Google sees your page

---

## Best Practices

### ✅ DO

- ✅ Keep titles under 60 characters
- ✅ Keep descriptions between 155-160 characters
- ✅ Use relevant keywords in description
- ✅ Include brand name in titles for recognition
- ✅ Use unique meta tags for each page
- ✅ Update translations when content changes
- ✅ Test with MetaTag component in dev mode
- ✅ Use `noindex` for user dashboards and voting pages

### ❌ DON'T

- ❌ Keyword stuffing (overuse keywords)
- ❌ Duplicate meta tags across pages
- ❌ Misleading titles or descriptions
- ❌ Leave default meta tags on public pages
- ❌ Forget translations for new languages
- ❌ Use images < 1200x630px for OG
- ❌ Set `noindex` on pages you want indexed

---

## Common Pages Needing SEO Updates

### Static Pages

- [ ] Homepage (`home`)
- [ ] Pricing page (`pricing`)
- [ ] About page (`about`) - *needs page translations*
- [ ] FAQ page (`faq`) - *needs page translations*
- [ ] Contact page (`contact`) - *needs page translations*
- [ ] Blog/Resources (`blog`) - *needs page translations*

### Dynamic Pages

- [x] organisation Show (`organizations.show`) ✅ **Done**
- [ ] Elections Index (`elections.index`)
- [ ] Elections Show (`elections.show`)
- [ ] Candidate Show (`candidates.show`) - *if public*
- [ ] Results Show (`results.show`) - *after election*

### Pages to Hide from Search (noindex)

- [ ] Vote Dashboard (`vote.dashboard`)
- [ ] Admin Dashboard (`admin.dashboard`)
- [ ] Commission Dashboard (`commission.dashboard`)
- [ ] Voting Pages (`voting.*`)
- [ ] Verification Pages (`verify.*`)
- [ ] User Profile (`profile.*`)

---

## Next Steps (Phase 2+)

1. **Implement on remaining static pages** (about, FAQ, contact)
2. **Add page translations** for pages without i18n keys
3. **Create election-specific pages** with dynamic titles/descriptions
4. **Implement hreflang tags** for multi-language support
5. **Generate XML sitemap** with priority/changefreq
6. **Add robots.txt** with rules for sensitive pages
7. **Implement breadcrumb schema** for site navigation
8. **Add JSON-LD structured data** for rich snippets
9. **Set up Google Search Console** monitoring
10. **Monitor search rankings** for target keywords

---

## Troubleshooting

### Meta tags not updating?

1. Check browser cache (Cmd+Shift+R to hard refresh)
2. Verify translation key exists in i18n files
3. Check console for JavaScript errors
4. Inspect DevTools to see current meta tags

### Character counts off?

The composable truncates at word boundaries to avoid cutting words in half:
- Title: Auto-formatted with "| Public Digit" suffix
- Description: Truncated to 160 chars at last space

### Translation not loading?

Make sure all 3 language files have the same `seo.pages.*` keys:
- `en.json` ✓
- `de.json` ✓
- `np.json` ✓

If missing, it falls back to `seo.site` defaults.

---

## Contact & Support

For questions about SEO implementation:
- Review this guide
- Check useMeta.js inline documentation
- Examine Organizations/Show.vue as working example
- Check existing translation files for patterns

---

**Happy SEO-ing! 🚀**

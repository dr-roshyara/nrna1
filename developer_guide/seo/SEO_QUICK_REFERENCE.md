# SEO Implementation - Quick Reference Card

## 🚀 Quick Start

### Add SEO to Your Page (3 Steps)

#### 1️⃣ Add Translation Keys

**Files:** `resources/js/locales/en.json`, `de.json`, `np.json`

```json
{
  "seo": {
    "pages": {
      "your-page": {
        "title": "Your Page Title | Public Digit",
        "description": "Your page description (max 160 chars)",
        "keywords": "keyword1, keyword2, keyword3"
      }
    }
  }
}
```

#### 2️⃣ Import & Use Composable

```javascript
import { useMeta } from '@/composables/useMeta'

useMeta({ pageKey: 'your-page' })
```

#### 3️⃣ Test It

Inspect DevTools (F12 → Head) or use `<MetaTag />` debug component

---

## 📝 Common Patterns

### Static Page (No Parameters)

```javascript
useMeta({ pageKey: 'pricing' })
```

### Dynamic Page (With Parameters)

```javascript
useMeta({
  pageKey: 'organizations.show',
  params: {
    organizationName: organisation.name,
    memberCount: organisation.members_count
  }
})
```

### Page You Want Hidden from Search

```javascript
useMeta({
  pageKey: 'voting.start',
  noindex: true  // ← Hides from Google
})
```

### Custom Override

```javascript
useMeta({
  pageKey: 'home',
  title: 'Special Promo - Limited Time!', // Override title
  description: 'Time-sensitive offer...'    // Override description
})
```

---

## 🔑 Available Page Keys (Phase 1)

| Page | Key | Has Parameters |
|------|-----|--------|
| Homepage | `home` | No |
| Pricing | `pricing` | No |
| organisation Show | `organizations.show` | Yes: `{organizationName}`, `{memberCount}`, `{electionCount}` |
| Elections Index | `elections.index` | No |
| Elections Show | `elections.show` | Yes: `{electionName}`, `{organizationName}` |

---

## ✍️ Translation Template

Copy-paste this into all 3 language files (`en.json`, `de.json`, `np.json`):

```json
{
  "seo": {
    "pages": {
      "your-new-page": {
        "title": "Your Page Title | Public Digit",
        "description": "Short description (max 160 chars, write naturally with keywords)",
        "keywords": "keyword1, keyword2, keyword3"
      }
    }
  }
}
```

**Notes:**
- Keep title under 60 characters (Google shows ~60)
- Keep description 155-160 characters (Google shows this)
- Avoid keyword stuffing, write naturally
- Translate for all languages (EN, DE, NP)

---

## 🔍 SEO Best Practices

| Do ✅ | Don't ❌ |
|------|---------|
| Unique title per page | Duplicate titles |
| Keywords in description | Keyword stuffing |
| 155-160 char descriptions | Too long/short |
| Relevant keywords | Unrelated keywords |
| Brand name in title | Generic titles |
| Use `noindex` for dashboards | Index private pages |
| Test with MetaTag component | Ship without testing |

---

## 🧪 Testing Meta Tags

### In Development

Add to any page:
```vue
<template>
  <MetaTag />  <!-- Shows all meta tags + character counts -->
</template>
```

### In Browser DevTools

```
F12 → Elements → Head → Check <title> and <meta> tags
```

### With Google Tools

1. [Rich Results Test](https://search.google.com/test/rich-results)
2. [Page Speed Insights](https://pagespeed.web.dev/)
3. [Search Console](https://search.google.com/search-console)

---

## 📊 Character Limits

| Meta Tag | Limit | Warning |
|----------|-------|---------|
| Title | 60 chars | 70+ is risky |
| Description | 160 chars | 170+ gets cut off |
| Keywords | No strict limit | 5-10 relevant ones |

*Character counts include spaces*

---

## 🚫 Special Cases

### Hide Page from Google Search
```javascript
useMeta({ pageKey: 'page-name', noindex: true })
```

### Don't Follow Links on This Page
```javascript
useMeta({ pageKey: 'page-name', nofollow: true })
```

### Custom Open Graph Image
```javascript
useMeta({
  pageKey: 'page-name',
  image: 'https://example.com/custom-image.jpg'
})
```

### Custom Canonical URL
```javascript
useMeta({
  pageKey: 'page-name',
  url: 'https://example.com/canonical-url'
})
```

---

## 📚 Full Docs

See `SEO_IMPLEMENTATION_GUIDE.md` for:
- Detailed usage examples
- Advanced parameters
- Troubleshooting
- Best practices
- Next steps (Phase 2+)

---

## ❓ FAQ

**Q: Do I need to translate SEO keys for all languages?**
A: Yes! Add the keys to `en.json`, `de.json`, AND `np.json`

**Q: Can I use HTML in meta descriptions?**
A: No, keep text plain (no `<b>`, `<em>`, etc.)

**Q: What's the `pageKey` format?**
A: Use kebab-case: `home`, `pricing`, `organizations.show` (dot = section)

**Q: How do I know if it's working?**
A: Use the `<MetaTag />` component or check DevTools

**Q: Can I use variables in translations?**
A: Yes! Use `{variableName}` and pass `params: { variableName: value }`

**Q: What if a translation is missing?**
A: Falls back to `seo.site` defaults

**Q: Do I need to remove `<Head>` components?**
A: Yes, `useMeta` replaces them - remove old `<Head title="..." />`

---

**Need help?** Check `SEO_IMPLEMENTATION_GUIDE.md` or examine `/resources/js/Pages/Organizations/Show.vue` as a working example.

---

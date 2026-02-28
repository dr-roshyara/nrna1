# SEO Verification & Testing Checklist

**Purpose:** Verify Phase 1 implementation + language mismatch fix are working correctly
**Status:** Ready to test
**Date:** February 16, 2026

---

## 🧪 Quick Verification (5 minutes)

### Step 1: Clear Browser Cache
```
Chrome: Cmd+Shift+R (Mac) or Ctrl+Shift+R (Windows)
Firefox: Cmd+Shift+Delete or Ctrl+Shift+Delete
Safari: Develop → Empty Caches
```

### Step 2: Test German Page
1. Visit homepage with German locale: `/?locale=de`
2. Press F12 to open DevTools
3. Go to **Elements** → **Head** section
4. Look for these meta tags:

```html
<!-- SHOULD BE GERMAN -->
<html lang="de">
<meta name="description" content="Ermöglichen Sie Ihrer Organisation sichere, transparente Online-Wahlen...">
<meta property="og:locale" content="de_DE">
<meta property="og:title" content="Sichere Online-Wahlen | Public Digit Elections">
```

✅ If you see German text → **Language fix is working!**
❌ If you see English text → Issue remains

### Step 3: Test English Page
1. Visit homepage with English locale: `/?locale=en`
2. Check meta tags in DevTools

```html
<!-- SHOULD BE ENGLISH -->
<html lang="en">
<meta name="description" content="Empower your organisation with secure, transparent online voting...">
<meta property="og:locale" content="en_US">
```

### Step 4: Test Nepali Page
1. Visit homepage with Nepali locale: `/?locale=np`
2. Check meta tags in DevTools

```html
<!-- SHOULD BE NEPALI -->
<html lang="np">
<meta name="description" content="आपको संस्थालाई सुरक्षित, पारदर्शी अनलाइन मतदान सक्षम गर्नुहोस्...">
<meta property="og:locale" content="ne_NP">
```

---

## ✅ Full Verification Checklist

### A. Meta Tags Present & Correct

- [ ] **Title tag**
  - [ ] Includes "| Public Digit" suffix
  - [ ] Length: 60-70 characters
  - [ ] Language matches page locale

- [ ] **Description meta tag**
  - [ ] Present in all 3 languages
  - [ ] Length: 155-160 characters
  - [ ] Language matches page locale

- [ ] **Keywords meta tag**
  - [ ] Present and relevant
  - [ ] 5-10 keywords
  - [ ] Language-specific

- [ ] **OG:Locale tag**
  - [ ] German page: `<meta property="og:locale" content="de_DE">`
  - [ ] English page: `<meta property="og:locale" content="en_US">`
  - [ ] Nepali page: `<meta property="og:locale" content="ne_NP">`

### B. Open Graph Tags (Social Sharing)

- [ ] `og:title` - Present and language-correct
- [ ] `og:description` - Present and language-correct
- [ ] `og:image` - Valid image URL
- [ ] `og:url` - Current page URL
- [ ] `og:type` - "website" or appropriate type
- [ ] `og:locale` - Matches page language

### C. Twitter Card Tags

- [ ] `twitter:title` - Present
- [ ] `twitter:description` - Present
- [ ] `twitter:image` - Valid image
- [ ] `twitter:card` - "summary_large_image"

### D. Canonical URL

- [ ] `<link rel="canonical" href="...">` present
- [ ] Points to current page
- [ ] No parameters included (clean URL)

### E. Language Consistency

- [ ] `<html lang="de">` matches German content
- [ ] `<html lang="en">` matches English content
- [ ] `<html lang="np">` matches Nepali content
- [ ] Meta tags language matches HTML lang
- [ ] No mismatches between page content and meta tags

### F. useMeta Composable

- [ ] Composable imports correctly: `import { useMeta } from '@/composables/useMeta'`
- [ ] No console errors when using useMeta
- [ ] Meta tags update when locale changes
- [ ] Meta tags update when page data changes

### G. Debug Component

- [ ] `<MetaTag />` component shows in dev mode
- [ ] Shows accurate character counts
- [ ] Warns when title > 60 chars
- [ ] Warns when description > 160 chars
- [ ] Shows OG image preview
- [ ] Hidden in production mode

---

## 🔍 Page-Specific Tests

### Homepage Test

**Test URL:** `/?locale=de`

| Check | Expected | Status |
|-------|----------|--------|
| Title | "Sichere Online-Wahlen..." (German) | ✅/❌ |
| Description | German text (155-160 chars) | ✅/❌ |
| og:locale | de_DE | ✅/❌ |
| html lang | de | ✅/❌ |
| useMeta called | pageKey: 'home' | ✅/❌ |

### organisation Page Test

**Test URL:** `/organizations/nrna-germany?locale=de`

| Check | Expected | Status |
|-------|----------|--------|
| Title | "NRNA Germany \| Wahlen..." (German) | ✅/❌ |
| Parameters | organizationName, memberCount, electionCount | ✅/❌ |
| Description | Includes org name + member count (German) | ✅/❌ |
| og:locale | de_DE | ✅/❌ |
| useMeta called | pageKey: 'organizations.show' | ✅/❌ |

### Pricing Page Test

**Test URL:** `/pricing?locale=en`

| Check | Expected | Status |
|-------|----------|--------|
| Title | "Pricing Plans..." (English) | ✅/❌ |
| Description | English text (155-160 chars) | ✅/❌ |
| og:locale | en_US | ✅/❌ |
| html lang | en | ✅/❌ |

---

## 🔗 External Tools Testing

### Google Rich Results Tester

1. Go to https://search.google.com/test/rich-results
2. Enter your page URL (with ?locale=de for German)
3. Check for:
   - ✅ Structured data passes validation
   - ✅ No errors or warnings
   - ✅ Meta tags visible

### Google PageSpeed Insights

1. Go to https://pagespeed.web.dev/
2. Enter your page URL
3. Check metrics:
   - ✅ Largest Contentful Paint (LCP) < 2.5s
   - ✅ First Input Delay (FID) < 100ms
   - ✅ Cumulative Layout Shift (CLS) < 0.1
   - ✅ Mobile score > 80

### Facebook Debugger

1. Go to https://developers.facebook.com/tools/debug/
2. Enter your German page URL
3. Verify:
   - ✅ Title shows in German
   - ✅ Description shows in German
   - ✅ Image preview correct
   - ✅ OG tags parsed correctly

### Twitter Card Validator

1. Go to https://cards-dev.twitter.com/validator
2. Enter your German page URL
3. Verify:
   - ✅ Card type: summary_large_image
   - ✅ Title in German
   - ✅ Description in German
   - ✅ Image displays correctly

---

## 🧪 Browser DevTools Inspection

### How to Inspect Meta Tags

```
1. Right-click on page → Inspect
2. In Elements panel, press Ctrl+F (Cmd+F on Mac)
3. Search for "meta"
4. Look for each tag:
   - <meta name="description" content="...">
   - <meta property="og:title" content="...">
   - <meta property="og:locale" content="...">
   - <link rel="canonical" href="...">
```

### Expected Output (German Page)

```html
<head>
  <title>Sichere Online-Wahlen | Public Digit Elections</title>
  <meta name="description" content="Ermöglichen Sie Ihrer Organisation sichere, transparente Online-Wahlen...">
  <meta name="keywords" content="Online-Wahlen, digitale Abstimmungen, Diaspora-Wahlen...">
  <meta property="og:title" content="Sichere Online-Wahlen | Public Digit Elections">
  <meta property="og:description" content="Ermöglichen Sie Ihrer Organisation...">
  <meta property="og:locale" content="de_DE">
  <meta name="twitter:title" content="Sichere Online-Wahlen...">
  <meta name="twitter:description" content="Ermöglichen Sie...">
  <link rel="canonical" href="https://publicdigit.com/">
</head>
```

---

## 📋 Language Mismatch Check

### Critical: Language Consistency Test

For each locale, verify **no language mismatches**:

#### German (locale=de)
```html
<html lang="de"> ← German
<meta name="description" content="Sichere Wahlplattform..."> ← German ✅
```
✅ **Match!** No penalty

#### English (locale=en)
```html
<html lang="en"> ← English
<meta name="description" content="Secure voting platform..."> ← English ✅
```
✅ **Match!** No penalty

#### Nepali (locale=np)
```html
<html lang="np"> ← Nepali
<meta name="description" content="सुरक्षित मतदान..."> ← Nepali ✅
```
✅ **Match!** No penalty

---

## 🚨 Common Issues & Fixes

### Issue 1: Meta Tags Still Show English

**Symptom:** German page shows English description

**Check:**
1. Did you clear browser cache? (Cmd+Shift+R)
2. Is locale being set correctly? Check console: `console.log(i18n.locale)`
3. Are translations in de.json? Check file has `seo.pages` section

**Fix:**
- Clear cache
- Check browser console for errors
- Verify de.json has `seo` section

### Issue 2: MetaTag Component Not Showing

**Symptom:** No debug panel visible in dev mode

**Check:**
1. Add to template: `<MetaTag />`
2. Check if in dev mode (not production)
3. Look for JS errors in console

**Fix:**
- Import: `import MetaTag from '@/components/MetaTag.vue'`
- Add to template
- Check console for errors

### Issue 3: Locale Not Changing

**Symptom:** Changing locale doesn't update meta tags

**Check:**
1. Is useMeta watching locale? Should be reactive
2. Are you changing locale correctly?
3. Check i18n locale value in console

**Fix:**
- useMeta has watchers, should update automatically
- Verify locale change is working in app
- Check browser console for i18n errors

### Issue 4: Parameters Not Substituting

**Symptom:** `{organizationName}` appears in title instead of actual name

**Check:**
1. Are parameters passed to useMeta?
2. Check spelling of parameter names
3. Are values actually available in props?

**Fix:**
```javascript
// Check parameters are being passed
useMeta({
  pageKey: 'organizations.show',
  params: {
    organizationName: organisation?.name || 'organisation', // Add fallback
    memberCount: stats?.members_count || '0'
  }
})
```

---

## ✅ Sign-Off Checklist

Before considering Phase 1 complete, verify:

- [ ] All 3 languages show language-correct meta tags
- [ ] No language mismatches (html lang matches meta tags)
- [ ] OG:Locale correct for each language (de_DE, en_US, ne_NP)
- [ ] Organizations/Show page works with dynamic parameters
- [ ] MetaTag debug component shows in dev mode
- [ ] No console errors when loading pages
- [ ] Meta tags update when locale changes
- [ ] Character counts correct (title 60, desc 160)
- [ ] Google Rich Results Tester passes
- [ ] Facebook Debugger shows correct language
- [ ] Twitter Card Validator shows correct language

---

## 📊 Success Criteria

| Criterion | Target | Status |
|-----------|--------|--------|
| German pages in German | 100% | ✅/❌ |
| English pages in English | 100% | ✅/❌ |
| Nepali pages in Nepali | 100% | ✅/❌ |
| No language mismatches | 0 cases | ✅/❌ |
| og:locale correct | 100% | ✅/❌ |
| Dynamic parameters work | 100% | ✅/❌ |
| No console errors | 0 errors | ✅/❌ |
| External tools pass | All pass | ✅/❌ |

---

## 🎉 Once Tests Pass

1. ✅ Mark Phase 1 as production-ready
2. ✅ Deploy to staging first
3. ✅ Run tests on staging
4. ✅ Deploy to production
5. ✅ Monitor Google Search Console for re-crawling
6. ✅ Proceed to Phase 2

---

**Last Updated:** February 16, 2026
**Next Review:** After deployment + 1 week


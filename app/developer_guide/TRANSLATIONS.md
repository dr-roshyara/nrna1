# Translation System Developer Guide

## Overview

The PUBLIC-DIGIT platform uses **Vue-i18n** for multi-language support with a **translation-first architecture**. This means all user-facing text is externalized into JSON locale files, organized hierarchically by feature/page.

### Supported Languages
- **de** — German (Deutsch) — default locale
- **en** — English
- **np** — Nepali (नेपाली)

---

## Architecture

### File Structure

```
resources/js/
├── i18n.js                          # Vue-i18n configuration & setup
├── locales/
│   ├── en.json                      # English root locale (global keys)
│   ├── de.json                      # German root locale (global keys)
│   ├── np.json                      # Nepali root locale (global keys)
│   └── pages/
│       ├── pricing/
│       │   ├── en.json              # Pricing page English
│       │   ├── de.json              # Pricing page German
│       │   └── np.json              # Pricing page Nepali
│       ├── voting-start/            # Voting start page
│       ├── voting-election/         # Voting election page
│       └── Welcome/                 # Welcome landing page
│           ├── en.json
│           ├── de.json
│           └── np.json
└── Pages/
    ├── Pricing.vue
    ├── Welcome.vue
    ├── VotingStart.vue
    └── VotingElection.vue
```

### Configuration: `resources/js/i18n.js`

The i18n instance is created with these settings:

```javascript
const i18n = createI18n({
  legacy: false,                    // Vue 3 Composition API mode
  locale: initialLocale,            // User's preferred locale (see Priority below)
  fallbackLocale: 'en',             // Fallback if translation missing
  messages: { ... },                // Merged locale files
  globalInjection: true,            // Makes $t() available globally
  missingWarn: false,               // Suppress warnings for missing keys
  fallbackWarn: false,              // Suppress fallback warnings
});
```

#### Locale Priority

When the app boots, it determines the user's locale in this order:

1. **localStorage** — User's saved preference (`localStorage.getItem('preferred_locale')`)
2. **Environment variable** — `MIX_DEFAULT_LOCALE` from `.env`
3. **Hard default** — `'de'` (German)

Users can switch languages via the `ElectionHeader` language selector, which updates both `$i18n.locale` and `localStorage`.

---

## Usage in Vue Components

### Pattern 1: Global Strings via `$t()`

For scalar values (strings, numbers, booleans), use `$t()` directly in templates:

```vue
<template>
  <div>
    <!-- Root locale keys -->
    <h1>{{ $t('platform.name') }}</h1>
    <p>{{ $t('platform.tagline') }}</p>

    <!-- Page-specific keys -->
    <h2>{{ $t('pages.welcome.hero.title') }}</h2>
    <button>{{ $t('pages.welcome.hero.cta_primary') }}</button>
  </div>
</template>

<script>
export default {
  // $t() is available globally; no import needed
  methods: {
    showMessage() {
      alert(this.$t('pages.welcome.hero.cta_primary'));
    }
  }
};
</script>
```

**Key Points:**
- `$t()` works in templates and methods
- Access nested keys with dot notation: `$t('pages.welcome.hero.title')`
- `$t()` is reactive — changes when user switches language
- Fallback to `fallbackLocale` ('en') if translation missing

### Pattern 2: Array Data via Direct Import

`$t()` cannot render arrays or complex objects. For arrays, import JSON files directly and use computed properties to select by locale:

```vue
<template>
  <div>
    <div v-for="item in featureList" :key="item.id">
      {{ item.title }}
      {{ item.description }}
    </div>
  </div>
</template>

<script>
import pricingDe from '../locales/pages/pricing/de.json';
import pricingEn from '../locales/pages/pricing/en.json';
import pricingNp from '../locales/pages/pricing/np.json';

export default {
  data() {
    return {
      localeData: { de: pricingDe, en: pricingEn, np: pricingNp }
    };
  },
  computed: {
    currentLocale() {
      return this.$i18n.locale;  // Reactive: updates on language switch
    },
    pricing() {
      return this.localeData[this.currentLocale] || this.localeData.de;
    },
    featureList() {
      return this.pricing.features || [];
    }
  }
};
</script>
```

**Key Points:**
- Import all three locale files
- Store in `data()` as `{ de: ..., en: ..., np: ... }`
- Computed `currentLocale` reads `this.$i18n.locale` — it is reactive
- Computed selector (`pricing`) returns the correct object for current locale
- Use `|| []` guards to prevent undefined errors
- This pattern is used by `Pricing.vue`, `Welcome.vue`, and similar pages with arrays

### Pattern 3: Root Locale vs Page Locale

**Root locale** — shared across the app:

```javascript
// resources/js/locales/en.json
{
  "platform": {
    "name": "PUBLIC-DIGIT",
    "tagline": "Digital Democracy Platform",
    "description": "Secure, GDPR-compliant voting for organizations"
  },
  "trust": {
    "gdpr": "GDPR Compliant",
    "german_hosting": "German Data Hosting",
    "encryption": "End-to-End Encryption"
  },
  "buttons": { ... },
  "navigation": { ... }
}
```

Access in any component:

```vue
{{ $t('platform.name') }}
{{ $t('trust.gdpr') }}
```

**Page locale** — specific to a page, namespaced under `pages.{slug}`:

```javascript
// resources/js/locales/pages/Welcome/en.json
{
  "hero": {
    "title": "...",
    "subtitle": "...",
    "badges": [ ... ]
  },
  "ngo_features": { ... }
}
```

Access in the page component:

```vue
{{ $t('pages.welcome.hero.title') }}
```

**Rule:** Reuse root locale keys for shared content (platform name, trust badges, CTA buttons already in root). Put only page-specific content in page locale files to avoid duplication.

---

## Adding a New Page with Translations

### Step 1: Create the Page Component

```vue
<!-- resources/js/Pages/MyNewPage.vue -->
<template>
  <div>
    <ElectionHeader />
    <MySection :items="items" />
    <PublicDigitFooter />
  </div>
</template>

<script>
import myPageDe from '../locales/pages/MyNewPage/de.json';
import myPageEn from '../locales/pages/MyNewPage/en.json';
import myPageNp from '../locales/pages/MyNewPage/np.json';

export default {
  data() {
    return {
      pageData: { de: myPageDe, en: myPageEn, np: myPageNp }
    };
  },
  computed: {
    currentLocale() { return this.$i18n.locale; },
    page() { return this.pageData[this.currentLocale] || this.pageData.de; },
    items() { return this.page.section?.items || []; }
  }
};
</script>
```

### Step 2: Create Locale Files

Create the directory structure:

```
resources/js/locales/pages/MyNewPage/
├── en.json
├── de.json
└── np.json
```

English locale (`en.json`):

```json
{
  "title": "My New Page",
  "subtitle": "This is my new page",
  "section": {
    "heading": "Section Heading",
    "items": [
      { "label": "Item 1", "description": "..." },
      { "label": "Item 2", "description": "..." }
    ]
  }
}
```

German locale (`de.json`) — same structure, German content:

```json
{
  "title": "Meine neue Seite",
  "subtitle": "Dies ist meine neue Seite",
  "section": {
    "heading": "Abschnitt Überschrift",
    "items": [
      { "label": "Element 1", "description": "..." },
      { "label": "Element 2", "description": "..." }
    ]
  }
}
```

Nepali locale (`np.json`) — same structure, Nepali content:

```json
{
  "title": "मेरो नयाँ पृष्ठ",
  "subtitle": "यो मेरो नयाँ पृष्ठ हो",
  "section": {
    "heading": "खण्ड शीर्षक",
    "items": [
      { "label": "वस्तु १", "description": "..." },
      { "label": "वस्तु २", "description": "..." }
    ]
  }
}
```

### Step 3: Wire into `i18n.js`

Add imports at the top:

```javascript
import myPageDe from './locales/pages/MyNewPage/de.json';
import myPageEn from './locales/pages/MyNewPage/en.json';
import myPageNp from './locales/pages/MyNewPage/np.json';
```

Add to the `messages` object (use lowercase/kebab-case slug):

```javascript
const messages = {
  de: {
    ...de,
    pages: {
      'voting-start': votingStartDe,
      'voting-election': votingElectionDe,
      pricing: pricingDe,
      'my-new-page': myPageDe,      // ← Add here
    },
  },
  en: {
    ...en,
    pages: {
      'voting-start': votingStartEn,
      'voting-election': votingElectionEn,
      pricing: pricingEn,
      'my-new-page': myPageEn,      // ← Add here
    },
  },
  np: {
    ...np,
    pages: {
      'voting-start': votingStartNp,
      'voting-election': votingElectionNp,
      pricing: pricingNp,
      'my-new-page': myPageNp,      // ← Add here
    },
  },
};
```

### Step 4: Use in Component

```vue
<template>
  <div>
    <h1>{{ $t('pages.my-new-page.title') }}</h1>
    <div v-for="item in items" :key="item.label">
      {{ item.label }} — {{ item.description }}
    </div>
  </div>
</template>
```

---

## Best Practices

### 1. **Always Use `$t()` for User-Visible Text**

❌ **WRONG:**

```vue
<p>Welcome to PUBLIC-DIGIT</p>
```

✅ **RIGHT:**

```vue
<p>{{ $t('platform.name') }}</p>
```

### 2. **Use Dot Notation for Nested Keys**

✅ **CORRECT:**

```vue
{{ $t('pages.welcome.hero.title') }}
```

❌ **AVOID:**

```javascript
const key = 'pages';
const subkey = 'welcome';
const title = $t(`${key}.${subkey}.hero.title`);  // Harder to debug
```

### 3. **Organize Keys Hierarchically**

❌ **POOR:**

```json
{
  "welcome_hero_title": "...",
  "welcome_hero_subtitle": "...",
  "welcome_cta": "..."
}
```

✅ **GOOD:**

```json
{
  "hero": {
    "title": "...",
    "subtitle": "..."
  },
  "cta_section": {
    "title": "..."
  }
}
```

### 4. **Include `|| []` Guards for Array Access**

```javascript
computed: {
  items() {
    return this.pageData[this.locale]?.section?.items || [];  // Safe!
  }
}
```

### 5. **Consistent Locale File Structure**

Every locale file must have the **exact same key structure** across all three language files. Missing keys cause silent `$t()` fallbacks.

❌ **WRONG:**

```javascript
// en.json
{ "title": "...", "items": [ ... ] }

// de.json
{ "title": "...", "description": "..." }  // Missing 'items'!
```

✅ **RIGHT:**

```javascript
// en.json
{ "title": "...", "items": [ ... ] }

// de.json
{ "title": "...", "items": [ ... ] }

// np.json
{ "title": "...", "items": [ ... ] }
```

### 6. **Use Reactive Computed Properties**

The `currentLocale` computed property must read `this.$i18n.locale` directly so it updates when language changes:

```javascript
computed: {
  currentLocale() {
    return this.$i18n.locale;  // ✅ Reactive
  }
}
```

❌ **WRONG:**

```javascript
computed: {
  currentLocale() {
    return localStorage.getItem('preferred_locale');  // Not reactive!
  }
}
```

### 7. **Namespacing Convention**

- Root locale keys: lowercase with dots — `platform.name`, `trust.gdpr`
- Page locale keys: `pages.{slug}.{section}.{key}` — `pages.welcome.hero.title`
- Page slug: lowercase/kebab-case (no PascalCase) — `welcome`, not `Welcome`

---

## Language Switching

Users switch languages via the `ElectionHeader` component. The header has a `<select>` dropdown with options: `de`, `en`, `np`.

**What happens on switch:**

1. User selects new language from dropdown
2. `ElectionHeader` updates `this.$i18n.locale = selectedLocale`
3. All components' `currentLocale` computed properties update (reactive)
4. All `$t()` calls re-render with new language
5. Persisted to localStorage: `localStorage.setItem('preferred_locale', selectedLocale)`

**On page reload:**

The `getInitialLocale()` function in `i18n.js` restores the saved locale from localStorage.

---

## Common Issues & Troubleshooting

### Issue: Translation key returns the key itself

**Symptom:**

```
{{ $t('pages.welcome.hero.title') }}
// Output: "pages.welcome.hero.title"
```

**Causes:**
- Key is not defined in the locale file
- Locale file was not imported in `i18n.js`
- Typo in the key path

**Solution:**
1. Check the locale file exists at `resources/js/locales/pages/{slug}/en.json`
2. Verify the key path matches exactly (case-sensitive)
3. Run `npm run dev` to rebuild with new locale imports

### Issue: Array data not updating when language switches

**Symptom:**

```
Array is English, but stays English after switching to German
```

**Cause:**

The `currentLocale` computed is not reading `this.$i18n.locale` directly, or the page locale data is not reactive.

**Solution:**

Ensure `currentLocale` reads the global i18n state:

```javascript
computed: {
  currentLocale() {
    return this.$i18n.locale;  // Must be direct!
  },
  pageData() {
    return this.localeData[this.currentLocale] || this.localeData.de;
  }
}
```

### Issue: `np.json` file exists but page crashes in Nepali

**Symptom:**

Page works in German/English but crashes when switching to Nepali.

**Cause:**

`np.json` file is not imported/wired in `i18n.js`, or it's missing keys that exist in `en.json`.

**Solution:**

1. Ensure `welcomeNp` is imported in `i18n.js`
2. Verify the file exists at the correct path
3. Check that `np.json` has all the same keys as `en.json` and `de.json`

---

## Example: Adding a New String to an Existing Page

### Scenario: Add a new CTA button label to the Welcome page

**Step 1:** Edit `resources/js/locales/pages/Welcome/en.json`

```json
{
  "cta_section": {
    "title": "...",
    "btn_register": "Create Free Account",
    "btn_demo": "Schedule a Demo",
    "btn_contact": "Contact Us"    // ← NEW
  }
}
```

**Step 2:** Edit `resources/js/locales/pages/Welcome/de.json`

```json
{
  "cta_section": {
    "title": "...",
    "btn_register": "Kostenloses Konto erstellen",
    "btn_demo": "Demo vereinbaren",
    "btn_contact": "Kontaktieren Sie uns"    // ← NEW (German)
  }
}
```

**Step 3:** Edit `resources/js/locales/pages/Welcome/np.json`

```json
{
  "cta_section": {
    "title": "...",
    "btn_register": "निःशुल्क खाता बनाएँ",
    "btn_demo": "डेमो शेड्युल गर्नुहोस्",
    "btn_contact": "हामीलाई सम्पर्क गर्नुहोस्"    // ← NEW (Nepali)
  }
}
```

**Step 4:** Use in component

```vue
<button>{{ $t('pages.welcome.cta_section.btn_contact') }}</button>
```

✅ Done! No code changes needed in `i18n.js` or components.

---

## Deployment Checklist

Before deploying a new page with translations:

- [ ] All three locale files (`en.json`, `de.json`, `np.json`) created
- [ ] All three files have **identical key structures**
- [ ] Locale files imported in `i18n.js`
- [ ] Page slug added to `messages` object in `i18n.js` (lowercase/kebab-case)
- [ ] Component uses correct `$t('pages.{slug}.{key}')` paths
- [ ] Array data uses direct import pattern with `|| []` guards
- [ ] Language switcher tested — all three languages render correctly
- [ ] No hard-coded English text in templates
- [ ] No console warnings about missing translation keys
- [ ] Locale files pushed to repository with code changes

---

## Reference

- **Vue-i18n Docs:** https://vue-i18n.intlify.dev/
- **i18n Configuration:** `resources/js/i18n.js`
- **Root Locales:** `resources/js/locales/{en,de,np}.json`
- **Page Locales:** `resources/js/locales/pages/{PageName}/{en,de,np}.json`
- **Language Switcher:** `resources/js/Components/Header/ElectionHeader.vue`


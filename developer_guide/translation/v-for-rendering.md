# Rendering Translations with v-for: Best Practices Guide

## Overview

When rendering arrays of translated content in Vue 3 with vue-i18n, there is a **critical pattern difference** between rendering single string values versus rendering arrays of objects.

This guide explains the correct and incorrect approaches, why they matter, and provides real examples from the Public Digit codebase.

---

## The Problem: Character Iteration

### What Goes Wrong (❌ INCORRECT PATTERN)

When you use `$t()` directly on an array in a v-for loop, you get character-by-character iteration:

```vue
<!-- ❌ WRONG: Iterating over $t() result -->
<ul>
  <li v-for="point in $t('gdpr_banner.points')" :key="point.id">
    {{ point.text }}
  </li>
</ul>
```

**Result in browser:** Each character appears on a new line
```
g
d
p
r
_
b
a
n
n
e
r
.
p
o
i
n
t
s
```

### Why This Happens

When `$t('gdpr_banner.points')` returns an array, Vue's `v-for` iterates over the array. However, if the translation system returns the key as a string (fallback behavior), it iterates over **each character** of the string.

Even when it returns an actual array, the `$t()` function is designed for **single string values**, not for returning complex data structures. This creates a mismatch between what the template expects and what vue-i18n provides.

---

## The Solution: Import JSON Directly (✅ CORRECT PATTERN)

### Core Principle

**Import locale JSON files directly as JavaScript objects.** Do NOT use `$t()` to access arrays. Reserve `$t()` for **single string translations only**.

### Step 1: Import Locale Files

In your Vue component's `<script>` section, import the locale JSON files:

```javascript
// resources/js/Pages/Dashboard/Welcome.vue

import dashboardWelcomeDe from '@/locales/pages/Dashboard/welcome/de.json';
import dashboardWelcomeEn from '@/locales/pages/Dashboard/welcome/en.json';
import dashboardWelcomeNp from '@/locales/pages/Dashboard/welcome/np.json';
```

### Step 2: Store in data() with Locale Mapping

Create a `data()` property that maps locales to their JSON objects:

```javascript
export default {
  name: 'DashboardWelcome',

  data() {
    return {
      // Map each locale code to its imported JSON data
      dashboardWelcomeData: {
        de: dashboardWelcomeDe,
        en: dashboardWelcomeEn,
        np: dashboardWelcomeNp,
      },
    };
  },

  // ... rest of component
}
```

### Step 3: Create Computed Properties

Add computed properties to:
1. Get the current locale
2. Get the welcome data for that locale
3. Extract specific sections (arrays)

```javascript
computed: {
  /**
   * Get current locale from vue-i18n
   */
  currentLocale() {
    return this.$i18n.locale;
  },

  /**
   * Get dashboard welcome data for current locale
   * Falls back to German if locale not found
   */
  dashboardWelcome() {
    return this.dashboardWelcomeData[this.currentLocale] || this.dashboardWelcomeData.de;
  },

  /**
   * Extract GDPR banner points array
   * Safe extraction with fallback to empty array
   */
  gdprBannerPoints() {
    return this.dashboardWelcome.gdpr_banner?.points || [];
  },

  /**
   * Extract user cases - German organizations points
   */
  userCasesGermanOrgPoints() {
    return this.dashboardWelcome.user_cases?.german_orgs?.points || [];
  },

  /**
   * Extract user cases - Diaspora communities points
   */
  userCasesDiasporaPoints() {
    return this.dashboardWelcome.user_cases?.diaspora?.points || [];
  },

  /**
   * Extract value propositions
   */
  valuePropsPointsArray() {
    return this.dashboardWelcome.value_props?.points || [];
  },

  /**
   * Extract tips points array
   */
  tipsPointsArray() {
    return this.dashboardWelcome.tips?.points || [];
  },
}
```

### Step 4: Use Computed Properties in Templates

In your template, use the computed properties directly (not `$t()`):

```vue
<!-- ✅ CORRECT: Using computed property -->
<ul class="points-list">
  <li v-for="(point, index) in gdprBannerPoints" :key="index">
    {{ point.text }}
  </li>
</ul>
```

**Result in browser:**
```
• ✓ GDPR-compliant data processing
• ✓ Encrypted data storage (German servers)
• ✓ End-to-end security certification
```

---

## Pattern Comparison

### ❌ INCORRECT PATTERN

```javascript
// Script
export default {
  // No imports, no data, no computed properties
}
```

```vue
<!-- Template -->
<ul>
  <!-- Uses $t() for array - WRONG -->
  <li v-for="point in $t('gdpr_banner.points')" :key="point.id">
    {{ point.text }}
  </li>
</ul>
```

**Problem:** Vue iterates character-by-character over the returned value.

---

### ✅ CORRECT PATTERN

```javascript
// Script
import dashboardWelcomeDe from '@/locales/pages/Dashboard/welcome/de.json';
import dashboardWelcomeEn from '@/locales/pages/Dashboard/welcome/en.json';
import dashboardWelcomeNp from '@/locales/pages/Dashboard/welcome/np.json';

export default {
  data() {
    return {
      dashboardWelcomeData: {
        de: dashboardWelcomeDe,
        en: dashboardWelcomeEn,
        np: dashboardWelcomeNp,
      },
    };
  },

  computed: {
    currentLocale() {
      return this.$i18n.locale;
    },

    dashboardWelcome() {
      return this.dashboardWelcomeData[this.currentLocale] || this.dashboardWelcomeData.de;
    },

    gdprBannerPoints() {
      return this.dashboardWelcome.gdpr_banner?.points || [];
    },
  },
}
```

```vue
<!-- Template -->
<ul>
  <!-- Uses computed property - CORRECT -->
  <li v-for="(point, index) in gdprBannerPoints" :key="index">
    {{ point.text }}
  </li>
</ul>
```

**Result:** Arrays iterate correctly, showing full content.

---

## Use Cases for Each Approach

### Use `$t()` FOR:

- **Single string values**
  ```vue
  <h3>{{ $t('gdpr_banner.title') }}</h3>
  <p>{{ $t('gdpr_banner.description') }}</p>
  ```

- **String substitution with placeholders**
  ```vue
  <p>{{ $t('header.greeting', { name: user.name }) }}</p>
  ```

- **Fallback text** (when locale string is needed)
  ```vue
  <button>{{ $t('actions.create_organization.cta') }}</button>
  ```

### Use Computed Properties FROM Imported JSON FOR:

- **Arrays of objects** (especially for v-for loops)
  ```javascript
  gdprBannerPoints() {
    return this.dashboardWelcome.gdpr_banner?.points || [];
  }
  ```

- **Complex nested objects** (with multiple properties)
  ```javascript
  valuePropsPointsArray() {
    return this.dashboardWelcome.value_props?.points || [];
  }
  ```

- **Data that changes based on state** (locale switching)
  ```javascript
  dashboardWelcome() {
    return this.dashboardWelcomeData[this.currentLocale] || this.dashboardWelcomeData.de;
  }
  ```

---

## JSON Translation File Structure

### ✅ CORRECT Structure

```json
{
  "gdpr_banner": {
    "title": "🇩🇪 GDPR Compliant",
    "subtitle": "PublicDigit is built with German privacy standards",
    "description": "All data is processed securely and transparently.",
    "points": [
      {"id": "gdpr", "text": "✓ GDPR-compliant data processing"},
      {"id": "servers", "text": "✓ Encrypted data storage (German servers)"},
      {"id": "certification", "text": "✓ End-to-end security certification"}
    ]
  },
  "user_cases": {
    "title": "Who Uses PublicDigit?",
    "german_orgs": {
      "title": "German Organizations",
      "description": "Trade unions, works councils...",
      "points": [
        {"id": "works_council", "text": "Works council elections"},
        {"id": "association", "text": "Association votes"}
      ]
    },
    "diaspora": {
      "title": "Diaspora Communities",
      "description": "Diaspora associations...",
      "points": [
        {"id": "leadership", "text": "Community leadership elections"},
        {"id": "referendums", "text": "Member votes"}
      ]
    }
  },
  "value_props": {
    "title": "Why Choose PublicDigit?",
    "subtitle": "We bring enterprise-grade security",
    "points": [
      {
        "id": "transparency",
        "icon": "🔍",
        "title": "Complete Transparency",
        "description": "Every vote is verifiable..."
      },
      {
        "id": "security",
        "icon": "🔒",
        "title": "Bank-Grade Security",
        "description": "End-to-end encryption..."
      }
    ]
  }
}
```

### ❌ INCORRECT Structure (Flat Keys)

```json
{
  "gdpr_banner_title": "🇩🇪 GDPR Compliant",
  "gdpr_banner_subtitle": "PublicDigit is built with German privacy standards",
  "gdpr_banner_description": "All data is processed securely and transparently.",
  "gdpr_banner_points": [
    {"id": "gdpr", "text": "✓ GDPR-compliant data processing"},
    ...
  ]
}
```

**Problem:** This flattens the structure, making it harder to organize and work with.

---

## Real-World Example: Dashboard Welcome Page

### File Structure

```
resources/js/
├── Pages/
│   └── Dashboard/
│       └── Welcome.vue           ← Vue component
└── locales/
    └── pages/
        └── Dashboard/
            └── welcome/
                ├── de.json       ← German translations
                ├── en.json       ← English translations
                └── np.json       ← Nepali translations
```

### Complete Component Example

```vue
<template>
  <div class="welcome-content">
    <!-- Single string: Use $t() -->
    <h2>{{ $t('gdpr_banner.title') }}</h2>

    <!-- Array: Use computed property -->
    <ul class="points-list">
      <li v-for="(point, index) in gdprBannerPoints" :key="index">
        {{ point.text }}
      </li>
    </ul>

    <!-- Another array section -->
    <div class="cases-grid">
      <div class="case-card">
        <h3>{{ $t('user_cases.german_orgs.title') }}</h3>
        <p>{{ $t('user_cases.german_orgs.description') }}</p>
        <ul>
          <li v-for="(point, index) in userCasesGermanOrgPoints" :key="index">
            {{ point.text }}
          </li>
        </ul>
      </div>
    </div>

    <!-- Value propositions with multiple properties -->
    <div class="props-grid">
      <div v-for="(prop, index) in valuePropsPointsArray" :key="index" class="prop-card">
        <div class="prop-icon">{{ prop.icon }}</div>
        <h4>{{ prop.title }}</h4>
        <p>{{ prop.description }}</p>
      </div>
    </div>
  </div>
</template>

<script>
import dashboardWelcomeDe from '@/locales/pages/Dashboard/welcome/de.json';
import dashboardWelcomeEn from '@/locales/pages/Dashboard/welcome/en.json';
import dashboardWelcomeNp from '@/locales/pages/Dashboard/welcome/np.json';

export default {
  name: 'DashboardWelcome',

  data() {
    return {
      dashboardWelcomeData: {
        de: dashboardWelcomeDe,
        en: dashboardWelcomeEn,
        np: dashboardWelcomeNp,
      },
    };
  },

  computed: {
    currentLocale() {
      return this.$i18n.locale;
    },

    dashboardWelcome() {
      return this.dashboardWelcomeData[this.currentLocale] || this.dashboardWelcomeData.de;
    },

    gdprBannerPoints() {
      return this.dashboardWelcome.gdpr_banner?.points || [];
    },

    userCasesGermanOrgPoints() {
      return this.dashboardWelcome.user_cases?.german_orgs?.points || [];
    },

    userCasesDiasporaPoints() {
      return this.dashboardWelcome.user_cases?.diaspora?.points || [];
    },

    valuePropsPointsArray() {
      return this.dashboardWelcome.value_props?.points || [];
    },
  },
}
</script>
```

---

## Debugging Checklist

If translations aren't rendering correctly:

### ✅ Check 1: Import Path Matches Actual Directory

```javascript
// ❌ WRONG: Wrong casing or path
import welcomeDe from '@/locales/pages/dashboard/welcome/de.json';

// ✅ CORRECT: Exact path with correct casing
import welcomeDe from '@/locales/pages/Dashboard/welcome/de.json';
```

### ✅ Check 2: Computed Property Returns Array, Not String

```javascript
// ❌ WRONG: Returns string, not array
gdprBannerPoints() {
  return this.$t('gdpr_banner.points'); // Returns string!
}

// ✅ CORRECT: Returns array from imported data
gdprBannerPoints() {
  return this.dashboardWelcome.gdpr_banner?.points || [];
}
```

### ✅ Check 3: Template Uses Computed Property, Not $t()

```vue
<!-- ❌ WRONG -->
<li v-for="point in $t('gdpr_banner.points')" :key="point.id">

<!-- ✅ CORRECT -->
<li v-for="(point, index) in gdprBannerPoints" :key="index">
```

### ✅ Check 4: Use Index-Based Keys

```vue
<!-- ❌ WRONG: point.id may not exist -->
<li v-for="point in items" :key="point.id">

<!-- ✅ CORRECT: Use array index -->
<li v-for="(point, index) in items" :key="index">
```

### ✅ Check 5: Webpack Cache Cleared

When updating translation files, clear webpack cache:

```bash
rm -rf public/js
rm -rf public/css
rm -rf node_modules/.cache
rm -rf node_modules/.vite
npm run dev
```

Then hard-refresh browser: **Ctrl+Shift+R** (Windows) or **Cmd+Shift+R** (Mac)

---

## Performance Considerations

### Why Computed Properties Are Better

1. **Reactivity:** Automatically updates when `$i18n.locale` changes
2. **Caching:** Vue caches the result until dependencies change
3. **Type Safety:** You know you're getting an array, not a string
4. **Clarity:** Template clearly shows what data is being used

### Locale Switching

When user switches language, the computed property reacts automatically:

```javascript
// User switches language
this.$i18n.locale = 'en';

// All computed properties re-evaluate:
// - currentLocale changes → 'en'
// - dashboardWelcome updates → English data
// - All array computed properties update → English arrays
// - Template re-renders with English text
```

---

## Summary: The Golden Rule

| Situation | Use | Example |
|-----------|-----|---------|
| Single string value | `$t()` | `{{ $t('gdpr_banner.title') }}` |
| Array of objects in v-for | Computed property from imported JSON | `v-for="(item, index) in myArrayProperty"` |
| Complex nested data | Computed property from imported JSON | Extract and return specific section |
| String substitution | `$t()` with parameters | `{{ $t('greeting', { name: user.name }) }}` |

---

## Related Documentation

- [Vue I18n Official Guide](https://vue-i18n.intlify.dev/)
- [Vue 3 Composition API - Computed Properties](https://vuejs.org/guide/extras/reactivity-in-depth.html#computed-caching)
- [Public Digit Architecture Guide](../architecture.md)
- [Public Digit Welcome Page Example](./welcome-page.md)

---

## Contributing

When adding new translated content:

1. **Add to JSON** with proper nesting
2. **Create computed property** to extract the array
3. **Use in template** with v-for and index key
4. **Test all locales** (German, English, Nepali)
5. **Clear webpack cache** before testing

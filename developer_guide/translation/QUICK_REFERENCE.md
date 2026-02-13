# Translation Rendering: Quick Reference

## 🚀 TL;DR - The Golden Rule

```javascript
// ❌ NEVER use $t() for arrays
<li v-for="item in $t('array.path')">{{ item }}</li>

// ✅ ALWAYS import JSON and use computed properties
<li v-for="(item, index) in myArrayProperty" :key="index">{{ item }}</li>
```

---

## Quick Implementation Template

### 1. Import JSON Files

```javascript
import dataDe from '@/locales/pages/MyPage/de.json';
import dataEn from '@/locales/pages/MyPage/en.json';
import dataNp from '@/locales/pages/MyPage/np.json';
```

### 2. Store in data()

```javascript
data() {
  return {
    pageData: {
      de: dataDe,
      en: dataEn,
      np: dataNp,
    },
  };
}
```

### 3. Add Computed Properties

```javascript
computed: {
  currentLocale() {
    return this.$i18n.locale;
  },

  page() {
    return this.pageData[this.currentLocale] || this.pageData.de;
  },

  myArray() {
    return this.page.section?.array || [];
  },
}
```

### 4. Use in Template

```vue
<div v-for="(item, index) in myArray" :key="index">
  {{ item.text }}
</div>
```

---

## Common Mistakes & Fixes

### ❌ Mistake 1: Using $t() for arrays

```javascript
// WRONG
gdprPoints() {
  return this.$t('gdpr_banner.points');
}
```

```vue
<!-- Results in character iteration: g d p r _ b... -->
<li v-for="point in gdprPoints">{{ point.text }}</li>
```

### ✅ Fix: Import and extract from JSON

```javascript
// CORRECT
gdprPoints() {
  return this.dashboardWelcome.gdpr_banner?.points || [];
}
```

```vue
<!-- Renders full list items correctly -->
<li v-for="(point, index) in gdprPoints" :key="index">
  {{ point.text }}
</li>
```

---

### ❌ Mistake 2: Wrong import path (case sensitivity)

```javascript
// WRONG - lowercase 'dashboard'
import dashDe from '@/locales/pages/dashboard/welcome/de.json';

// WRONG - file doesn't exist at this path
import dashDe from '@/locales/pages/Dashboard/welcome/DE.json';
```

### ✅ Fix: Exact path with correct casing

```javascript
// CORRECT
import dashDe from '@/locales/pages/Dashboard/welcome/de.json';
import dashEn from '@/locales/pages/Dashboard/welcome/en.json';
import dashNp from '@/locales/pages/Dashboard/welcome/np.json';
```

---

### ❌ Mistake 3: Using .id as key when not guaranteed to exist

```javascript
// WRONG - point.id might be undefined
<li v-for="point in myArray" :key="point.id">
```

### ✅ Fix: Use array index

```javascript
// CORRECT
<li v-for="(point, index) in myArray" :key="index">
```

---

### ❌ Mistake 4: Missing optional chaining or fallback

```javascript
// WRONG - crashes if section doesn't exist
myArray() {
  return this.page.section.array;
}
```

### ✅ Fix: Use optional chaining and fallback

```javascript
// CORRECT
myArray() {
  return this.page.section?.array || [];
}
```

---

## Pattern Comparison

### For Single Strings

```vue
<!-- ✅ Use $t() -->
<h3>{{ $t('section.title') }}</h3>
<p>{{ $t('section.description') }}</p>
<button>{{ $t('section.cta') }}</button>
```

### For Arrays

```vue
<!-- ✅ Use imported JSON + computed property + v-for -->
<ul>
  <li v-for="(item, index) in myArrayProperty" :key="index">
    {{ item.text }}
  </li>
</ul>
```

### For Dynamic Text

```vue
<!-- ✅ Use $t() with parameters -->
<p>{{ $t('greeting', { name: user.name }) }}</p>
```

### For Complex Objects

```vue
<!-- ✅ Use imported JSON + computed property -->
<div v-for="(prop, index) in valueProps" :key="index">
  <h4>{{ prop.title }}</h4>
  <p>{{ prop.description }}</p>
  <span>{{ prop.icon }}</span>
</div>
```

---

## Debugging Checklist

- [ ] JSON file exists at import path (check casing!)
- [ ] Import statement is correct and matches file location
- [ ] `data()` contains `pageData` mapping
- [ ] Computed property returns array, not string
- [ ] Template uses computed property, not `$t()`
- [ ] v-for uses `(item, index)` not just `item`
- [ ] `:key="index"` is used (not `:key="item.id"`)
- [ ] Optional chaining used: `this.page.section?.array || []`
- [ ] Webpack cache cleared: `rm -rf public/js node_modules/.cache`
- [ ] Browser cache cleared: **Ctrl+Shift+R**

---

## Real Example: Dashboard Welcome

### File: `resources/js/Pages/Dashboard/Welcome.vue`

```javascript
// ✅ Imports at top
import dashDe from '@/locales/pages/Dashboard/welcome/de.json';
import dashEn from '@/locales/pages/Dashboard/welcome/en.json';
import dashNp from '@/locales/pages/Dashboard/welcome/np.json';

export default {
  // ✅ Store in data
  data() {
    return {
      dashboardWelcomeData: {
        de: dashDe,
        en: dashEn,
        np: dashNp,
      },
    };
  },

  // ✅ Computed properties
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

    valuePropsPointsArray() {
      return this.dashboardWelcome.value_props?.points || [];
    },
  },
}
```

```vue
<!-- ✅ Template -->
<template>
  <!-- Single strings: Use $t() -->
  <h3>{{ $t('gdpr_banner.title') }}</h3>

  <!-- Arrays: Use computed property -->
  <ul>
    <li v-for="(point, index) in gdprBannerPoints" :key="index">
      {{ point.text }}
    </li>
  </ul>

  <!-- Complex objects: Use computed property -->
  <div v-for="(prop, index) in valuePropsPointsArray" :key="index">
    <span>{{ prop.icon }}</span>
    <h4>{{ prop.title }}</h4>
    <p>{{ prop.description }}</p>
  </div>
</template>
```

---

## JSON Structure Example

```json
{
  "gdpr_banner": {
    "title": "🇩🇪 GDPR Compliant",
    "subtitle": "Description here",
    "description": "More info here",
    "points": [
      {"id": "gdpr", "text": "✓ Point 1"},
      {"id": "servers", "text": "✓ Point 2"},
      {"id": "cert", "text": "✓ Point 3"}
    ]
  },
  "value_props": {
    "title": "Why Choose Us?",
    "subtitle": "Subtitle here",
    "points": [
      {
        "id": "trans",
        "icon": "🔍",
        "title": "Transparency",
        "description": "Details..."
      },
      {
        "id": "sec",
        "icon": "🔒",
        "title": "Security",
        "description": "Details..."
      }
    ]
  }
}
```

---

## Testing All Languages

After implementing, test with all locales:

```bash
# In browser console
i18n.locale = 'de'  // German
i18n.locale = 'en'  // English
i18n.locale = 'np'  // Nepali

# Verify each locale loads correct translations
```

---

## Cache Clearing Commands

```bash
# Clear webpack cache
rm -rf public/js
rm -rf public/css
rm -rf public/mix-manifest.json
rm -rf node_modules/.cache
rm -rf node_modules/.vite

# Restart dev server
npm run dev
```

Then hard-refresh browser: **Ctrl+Shift+R** (Windows) or **Cmd+Shift+R** (Mac)

---

## When to Use What

| Need | Use | Example |
|------|-----|---------|
| Single translated string | `$t()` | `{{ $t('title') }}` |
| Array in v-for | Computed property | `v-for="(i, idx) in array"` |
| String with variables | `$t()` with params | `$t('hello', {name})` |
| Conditional text | `$t()` | `{{ condition ? $t('yes') : $t('no') }}` |
| Object properties | Computed property | `{{ myObj.prop }}` |

---

## See Also

- Full Guide: `v-for-rendering.md`
- Vue I18n Docs: https://vue-i18n.intlify.dev/
- Vue Computed Guide: https://vuejs.org/guide/extras/reactivity-in-depth.html

# Translation System - Developer Guide

This directory contains comprehensive documentation on how to work with translations in Public Digit, particularly focusing on rendering translated content with `v-for` loops correctly.

---

## 📚 Documentation Files

### 1. **QUICK_REFERENCE.md** ⭐ START HERE
**For:** Developers who need a quick reminder
- Quick implementation template
- Common mistakes and fixes
- Pattern comparison chart
- Debugging checklist
- Real examples

**Read this first if you:**
- Need to refresh your memory on the pattern
- Are implementing a new translated section
- Are debugging translation issues

### 2. **v-for-rendering.md**
**For:** Comprehensive understanding
- The problem: why character iteration happens
- The solution: importing JSON directly
- Step-by-step implementation guide
- Use cases for each approach
- Real-world example (Dashboard Welcome Page)
- Performance considerations
- Complete code examples

**Read this for:**
- Deep understanding of the pattern
- Learning why this approach is correct
- Implementing new translation sections
- Onboarding new team members

---

## 🎯 Core Concept

### The Golden Rule

```
❌ WRONG:   Use $t() for arrays in v-for
✅ CORRECT: Import JSON, use computed properties for arrays
```

### The Pattern (In 4 Steps)

1. **Import** locale JSON files
2. **Store** them in `data()` with locale mapping
3. **Extract** arrays via computed properties
4. **Use** computed properties in v-for loops

---

## 📖 Quick Navigation

### I want to...

**Implement translations for a new page:**
→ Read: QUICK_REFERENCE.md (Pattern Implementation Template)
→ Then: v-for-rendering.md (Real-World Example section)

**Fix character iteration in my v-for:**
→ Read: QUICK_REFERENCE.md (Common Mistakes & Fixes)
→ Reference: QUICK_REFERENCE.md (Debugging Checklist)

**Understand why this pattern works:**
→ Read: v-for-rendering.md (The Problem section)
→ Then: v-for-rendering.md (The Solution section)

**Debug my translations not loading:**
→ Reference: QUICK_REFERENCE.md (Debugging Checklist)
→ Check: v-for-rendering.md (Debugging Checklist section)

**Add a new translation section:**
→ Reference: QUICK_REFERENCE.md (JSON Structure Example)
→ Implement: QUICK_REFERENCE.md (Real Example: Dashboard Welcome)

---

## 🏗️ Translation File Organization

### Directory Structure

```
resources/js/
├── locales/
│   ├── de.json                    ← Base German translations
│   ├── en.json                    ← Base English translations
│   ├── np.json                    ← Base Nepali translations
│   └── pages/
│       ├── Welcome/               ← Main landing page
│       │   ├── de.json
│       │   ├── en.json
│       │   └── np.json
│       └── Dashboard/
│           └── welcome/           ← Dashboard welcome page
│               ├── de.json
│               ├── en.json
│               └── np.json
├── Pages/
│   ├── Welcome.vue                ← Main landing page component
│   └── Dashboard/
│       └── Welcome.vue            ← Dashboard welcome component
└── i18n.js                        ← i18n configuration
```

### Key Principles

1. **Location:** Translation files live in `resources/js/locales/pages/{Page}/`
2. **Naming:** Files use locale codes: `de.json`, `en.json`, `np.json`
3. **Structure:** Use nested objects, not flat keys
4. **Organization:** Group related translations together

---

## 🔄 How Translations are Loaded

### 1. Boot Phase (i18n.js)

```javascript
// Import all locale files
import welcomeDe from './locales/pages/Welcome/de.json';
import welcomeEn from './locales/pages/Welcome/en.json';

// Merge into i18n messages
app.use(i18n, {
  messages: {
    de: { ...welcomeDe },
    en: { ...welcomeEn },
  }
});
```

### 2. Runtime Phase (Vue Component)

```javascript
// Component imports its own translation files
import dashDe from '@/locales/pages/Dashboard/welcome/de.json';

// Stores in data()
data() {
  return {
    dashboardWelcomeData: { de: dashDe }
  }
}

// Accesses via computed property
computed: {
  dashboardWelcome() {
    return this.dashboardWelcomeData[this.$i18n.locale];
  }
}
```

### 3. Template Phase (v-for loop)

```vue
<!-- Uses computed property, not $t() -->
<li v-for="(item, idx) in computedArrayProperty" :key="idx">
  {{ item.text }}
</li>
```

---

## 🌍 Supported Languages

- **German (de)** - Primary market
- **English (en)** - International audience
- **Nepali (np)** - Diaspora communities

All three languages must be implemented in every translation file.

---

## ✅ Best Practices

### DO:
✅ Import JSON files directly in components
✅ Create computed properties to extract data
✅ Use `$t()` for single string values only
✅ Use optional chaining: `this.data.section?.array || []`
✅ Use index-based keys: `:key="index"`
✅ Test all three languages before committing
✅ Clear webpack cache after translation changes

### DON'T:
❌ Use `$t()` for arrays in v-for
❌ Use flat JSON structure for complex data
❌ Mix `$t()` and imported JSON in the same section
❌ Use `:key="item.id"` without checking if id exists
❌ Forget to update all three languages
❌ Use tenancy package in domain layer (unrelated, but important!)

---

## 🧪 Testing Translations

### In Browser Console

```javascript
// Check current locale
i18n.locale

// Switch locale
i18n.locale = 'de'  // German
i18n.locale = 'en'  // English
i18n.locale = 'np'  // Nepali

// Verify computed property loads correct data
console.log(this.dashboardWelcome)  // Should show current locale's data
```

### Before Committing

- [ ] All three languages implemented
- [ ] No console errors
- [ ] Arrays render full content (no character iteration)
- [ ] Text displays in correct language
- [ ] Webpack build succeeds
- [ ] Browser hard-refresh shows correct content

---

## 🐛 Common Issues & Solutions

### Issue: Character Iteration (g d p r...)

**Cause:** Using `$t()` for array
**Solution:** See QUICK_REFERENCE.md → Mistake 1

### Issue: Translations Not Loading

**Cause:** Wrong import path casing or file not found
**Solution:** See QUICK_REFERENCE.md → Mistake 2 & Debugging Checklist

### Issue: Webpack Build Error

**Cause:** Syntax error in JSON file
**Solution:** Validate JSON syntax at jsonlint.com

### Issue: Switching Language Doesn't Update Content

**Cause:** Computed property not watching `currentLocale`
**Solution:** Ensure computed property returns `this.dashboardWelcomeData[this.currentLocale]`

---

## 📋 Implementation Checklist

When adding a new translated page section:

- [ ] Create locale files: `de.json`, `en.json`, `np.json`
- [ ] Use nested structure (not flat keys)
- [ ] Place in correct directory: `resources/js/locales/pages/{Page}/`
- [ ] Import in component: `import dataDe from '@/locales/...'`
- [ ] Store in `data()` with locale mapping
- [ ] Create `currentLocale` computed property
- [ ] Create main data computed property: `dashboardWelcome()`
- [ ] Create array extraction computed properties
- [ ] Update template: replace `$t()` with computed properties
- [ ] Test all three languages
- [ ] Clear webpack cache: `rm -rf public/js node_modules/.cache`
- [ ] Hard-refresh browser: `Ctrl+Shift+R`
- [ ] Verify arrays render correctly (no character iteration)

---

## 🔗 Related Documentation

### Internal
- [Welcome Page Architecture](./welcome-page.md)
- [Dashboard Implementation Guide](../dashboard/)
- [Public Digit Architecture](../architecture.md)

### External
- [Vue I18n Official Documentation](https://vue-i18n.intlify.dev/)
- [Vue 3 Composition API Guide](https://vuejs.org/guide/)
- [JSON Formatting Guide](https://www.json.org/)

---

## 👥 Common Scenarios

### Scenario 1: Adding a new list section

1. Add array to JSON: `"points": [{"id": "x", "text": "y"}]`
2. Create computed property: `myPoints() { return this.data.section?.points || [] }`
3. Use in template: `v-for="(item, idx) in myPoints" :key="idx"`

### Scenario 2: Updating existing translations

1. Edit the JSON file (all three languages)
2. Clear webpack cache: `rm -rf public/js node_modules/.cache`
3. Restart dev server: `npm run dev`
4. Hard-refresh browser: `Ctrl+Shift+R`
5. Verify in browser

### Scenario 3: Adding new language

1. Create new JSON file (e.g., `fr.json` for French)
2. Update `i18n.js` to import and register new language
3. Update all components to import new language
4. Test language switching in browser

---

## 📞 Getting Help

### Read the Guides
- Quick answer? → QUICK_REFERENCE.md
- Need details? → v-for-rendering.md
- Still stuck? → Check debugging checklist

### Check Examples
- Dashboard Welcome: Real working example
- Main Welcome: Another complete example
- Compare with your code

### Debugging Steps
1. Check import path (file exists?)
2. Validate JSON syntax
3. Check computed property returns array
4. Clear webpack cache
5. Hard-refresh browser
6. Check browser console for errors

---

## 🎓 Learning Path

**Beginner:**
1. Read QUICK_REFERENCE.md
2. Follow the 4-step pattern
3. Test with all three languages

**Intermediate:**
1. Read v-for-rendering.md
2. Understand why the pattern works
3. Debug issues using checklist

**Advanced:**
1. Add multi-language support
2. Optimize performance
3. Contribute improvements to this guide

---

## 📝 Contributing

When updating this guide:
1. Keep QUICK_REFERENCE.md concise and scannable
2. Use v-for-rendering.md for detailed explanations
3. Add real examples from the codebase
4. Test all code examples
5. Keep examples for all three languages (de, en, np)

---

## Version History

- **v1.0** (2026-02-11): Initial release
  - QUICK_REFERENCE.md: Quick lookup guide
  - v-for-rendering.md: Comprehensive guide
  - README.md: Navigation and overview

---

**Last Updated:** 2026-02-11
**Maintained by:** Public Digit Development Team

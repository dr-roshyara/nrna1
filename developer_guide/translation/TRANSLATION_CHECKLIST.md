# Translation-First Strategy Checklist

Quick reference for implementing translations in a new feature.

---

## Pre-Development Checklist

- [ ] Identify all user-facing text in the feature
- [ ] Create a content outline (what text appears where)
- [ ] Review terminology with non-English speakers if possible
- [ ] Check if similar features already have translations to maintain consistency

---

## Create Locale Files (Phase 1)

### For Each Language (en, de, np)

- [ ] Create file: `resources/js/locales/pages/{PageName}/{language}.json`
- [ ] Use **NO wrapper** in the file (no `pages` or `{PageName}` keys)
- [ ] Structure: `{ "key": "value", "section": { "key": "value" } }`
- [ ] Use kebab-case for multi-word keys: `voting_page`, `demo_badge`
- [ ] All keys are semantic and meaningful
- [ ] All user-facing text extracted (buttons, labels, errors, tooltips)

### Validation

- [ ] File contains valid JSON (test with `json-lint` or IDE)
- [ ] All translations are actually translated (not English in all 3 files)
- [ ] Key names are identical across all 3 language files
- [ ] No hardcoded dates or names (use parameterization if needed)

---

## Register in i18n.js (Phase 2)

### File: `resources/js/i18n.js`

#### Step 1: Add Imports (Lines 2-29)

```javascript
import yourPageDe from './locales/pages/YourPage/de.json';
import yourPageEn from './locales/pages/YourPage/en.json';
import yourPageNp from './locales/pages/YourPage/np.json';
```

- [ ] Imports added for all 3 languages
- [ ] Import paths are correct (case-sensitive!)
- [ ] Import names follow convention: `{pageName}De`, `{pageName}En`, `{pageName}Np`

#### Step 2: Add to Messages Object (Lines 54-88)

For **German** section:
```javascript
de: {
  ...de,
  pages: {
    // ... other pages
    yourPage: yourPageDe,  // ← ADD THIS
  },
}
```

For **English** section:
```javascript
en: {
  ...en,
  pages: {
    // ... other pages
    yourPage: yourPageEn,  // ← ADD THIS
  },
}
```

For **Nepali** section:
```javascript
np: {
  ...np,
  pages: {
    // ... other pages
    yourPage: yourPageNp,  // ← ADD THIS
  },
}
```

- [ ] Entry added to `de.pages`
- [ ] Entry added to `en.pages`
- [ ] Entry added to `np.pages`
- [ ] Key name matches locale file import name (lowercase)
- [ ] NO extra nesting (i18n.js handles it automatically)

---

## Use in Components (Phase 3)

### In Templates

- [ ] All user-facing text uses `{{ $t('pages.{yourPage}.{key}') }}`
- [ ] Dynamic values use parameterization: `$t('key', { param: value })`
- [ ] Pluralization uses `$tc()` where needed
- [ ] Tooltips and aria-labels are translated
- [ ] Error messages are translated

### In JavaScript

- [ ] Dynamic text in methods uses `this.$t()`
- [ ] Error messages are translated
- [ ] Validation messages are translated
- [ ] No hardcoded strings (check `grep` for English text)

### Example Usage

✅ **DO:**
```vue
<h1>{{ $t('pages.yourPage.title') }}</h1>
<button>{{ $t('pages.yourPage.submit_button') }}</button>
<p>{{ $t('pages.yourPage.message', { date: today }) }}</p>
```

❌ **DON'T:**
```vue
<h1>Your Page Title</h1>
<button>Submit</button>
<p>Today is {{ today }}</p>
```

---

## Build and Deploy (Phase 4)

### Build Assets

```bash
npm run build
```

- [ ] Build completes without errors
- [ ] No "Cannot find module" errors
- [ ] Check output for warnings

### Clear Caches

```bash
php artisan config:clear
php artisan cache:clear
```

- [ ] Config cache cleared
- [ ] Application cache cleared

### Test Translations

- [ ] Open browser to feature page
- [ ] Hard refresh (Ctrl+Shift+R or Cmd+Shift+R)
- [ ] Verify English translations display (not placeholder keys)
- [ ] Switch to German language and verify translations
- [ ] Switch to Nepali language and verify translations
- [ ] Check all text on page is translated (no English fallback)
- [ ] Test dynamic content with parameters
- [ ] Test error messages and validation

### Verify Translation Files

- [ ] Check browser Network tab → app.js (should be freshly rebuilt)
- [ ] Verify no 404 errors for JSON files
- [ ] Browser console has no i18n warnings

---

## Common Issues & Fixes

### Issue: Translations Show as Placeholder Keys

**Example:** Page shows `pages.yourPage.title` instead of "Your Page Title"

**Fix Checklist:**
- [ ] Verify imports exist in i18n.js
- [ ] Verify translations registered in messages object
- [ ] Rebuild: `npm run build`
- [ ] Clear cache: `php artisan config:clear && php artisan cache:clear`
- [ ] Hard refresh browser (Ctrl+Shift+R)
- [ ] Check browser console for errors

### Issue: JSON Syntax Error

**Example:** Build fails with "Unexpected token" in locale file

**Fix Checklist:**
- [ ] Check JSON is valid (use online validator)
- [ ] Verify all quotes are double quotes `"`, not single `'`
- [ ] Verify all commas between properties
- [ ] Verify no trailing commas after last property
- [ ] Verify all braces `{}` and brackets `[]` are balanced

### Issue: Missing Translations in One Language

**Example:** German translations work, but English shows placeholders

**Fix Checklist:**
- [ ] Verify all 3 language files exist in directory
- [ ] Check English filename is `en.json` (not `eng.json`)
- [ ] Verify English import in i18n.js
- [ ] Verify English registered in `messages.en.pages`
- [ ] Check all keys match across all 3 language files

### Issue: Build Error "Cannot find module"

**Example:** `Cannot find module './locales/pages/yourpage/en.json'`

**Fix Checklist:**
- [ ] Check file path capitalization (case-sensitive on Linux/Mac)
- [ ] Verify folder name matches: `YourPage` vs `yourpage`
- [ ] Verify filename: `en.json` vs `En.json`
- [ ] Verify file actually exists on disk

---

## Before Pushing to Git

- [ ] All 3 language files committed
- [ ] i18n.js changes committed
- [ ] Component changes committed
- [ ] No hardcoded strings in component code
- [ ] Build passes: `npm run build`
- [ ] No console warnings
- [ ] Tested in all 3 languages
- [ ] Peer review includes translation verification

---

## Performance Notes

### File Size Impact

- Each new page translation adds ~3-5 KB to bundle (uncompressed)
- With gzip compression, typically ~1-2 KB
- i18n.js itself is ~50 KB (shared across all features)

### Best Practices for Performance

- [ ] Don't create duplicate keys across page files
- [ ] Group related translations in sections (use nesting)
- [ ] Keep key names concise but semantic
- [ ] Avoid unnecessary nesting levels

---

## Translation Workflow for Teams

### Step 1: Developer Creates English Locale File
- [ ] Create `{PageName}/en.json` with all English text
- [ ] Register in i18n.js
- [ ] Use in component with `$t()` calls
- [ ] Commit to feature branch

### Step 2: Translator Provides German Translation
- [ ] Receive `{PageName}/de.json` from translator
- [ ] Add to commit
- [ ] Verify keys match English file

### Step 3: Translator Provides Nepali Translation
- [ ] Receive `{PageName}/np.json` from translator
- [ ] Add to commit
- [ ] Verify keys match English file

### Step 4: Developer Finalizes
- [ ] Register all files in i18n.js
- [ ] Build and test all 3 languages
- [ ] Commit ready for review

---

## Key Commands

```bash
# Validate JSON files
npm install -g jsonlint
jsonlint resources/js/locales/pages/YourPage/en.json

# Build and clear caches (one command)
npm run build && php artisan config:clear && php artisan cache:clear

# Search for hardcoded strings in components
grep -r "Voting\|Election\|Submit" resources/js/Pages/

# List all translation files
find resources/js/locales/pages -name "*.json" | sort

# Check i18n.js syntax
node -c resources/js/i18n.js
```

---

## Review Checklist for Code Reviewer

When reviewing a feature with translations:

- [ ] All 3 language files present (`en.json`, `de.json`, `np.json`)
- [ ] Imports added to i18n.js
- [ ] Translations registered in messages object (all 3 languages)
- [ ] No `pages` wrapper in locale files
- [ ] Component uses `$t()` for all text
- [ ] No hardcoded strings in component
- [ ] Key names are semantic and lowercase
- [ ] Build passes: `npm run build`
- [ ] Tested manually in all 3 languages
- [ ] No console errors or warnings

---

## Template: Starting a New Page

When starting a new page feature:

1. **Create files:**
   ```bash
   mkdir -p resources/js/locales/pages/YourPage
   touch resources/js/locales/pages/YourPage/{en,de,np}.json
   ```

2. **Copy template to each file:**
   ```json
   {
     "title": "[Your page title in appropriate language]",
     "subtitle": "[Optional subtitle]",
     "description": "[Optional description]",

     "section_name": {
       "label": "[Label text]",
       "help_text": "[Help or explanation]"
     },

     "buttons": {
       "submit": "[Submit button text]",
       "cancel": "[Cancel button text]"
     },

     "messages": {
       "success": "[Success message]",
       "error": "[Error message]"
     }
   }
   ```

3. **Add to i18n.js:**
   ```javascript
   import yourPageDe from './locales/pages/YourPage/de.json';
   import yourPageEn from './locales/pages/YourPage/en.json';
   import yourPageNp from './locales/pages/YourPage/np.json';

   // Add to messages object for each language
   ```

4. **Create component with translations**

5. **Build and test**

---

## Questions to Ask Before Starting

- ❓ Are there similar pages with existing translations I should match?
- ❓ Should this use core translations (common.back, common.submit) or page-specific?
- ❓ Are there abbreviations or acronyms that need explanation for translators?
- ❓ Should parameter values be formatted (dates, numbers, currency)?
- ❓ Are there culturally specific terms that need special handling?

---

## Resources

- [Complete Translation-First Strategy Guide](./TRANSLATION_FIRST_STRATEGY.md)
- [Vue i18n Docs](https://vue-i18n.intlify.dev/)
- Current i18n setup: `resources/js/i18n.js`
- Example pages: `resources/js/locales/pages/Election/`


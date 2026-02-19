# Translation System Troubleshooting Guide

Debugging guide for common translation and i18n issues.

---

## Issue 1: Translations Show as Placeholder Keys

**Symptom:**
```
Page displays: "pages.election.voting_page.title"
Expected: "Active Election - Voting Page"
```

**Root Causes & Solutions:**

### Cause 1.1: i18n.js Not Importing Translations

**Check:**
```bash
# Open resources/js/i18n.js and search for your imports
grep -n "import.*election" resources/js/i18n.js
```

**Expected Output:**
```javascript
3: import electionDe from './locales/pages/Election/de.json';
4: import electionEn from './locales/pages/Election/en.json';
5: import electionNp from './locales/pages/Election/np.json';
```

**Fix:**
If imports are missing, add them after line 25 in i18n.js:
```javascript
import electionDe from './locales/pages/Election/de.json';
import electionEn from './locales/pages/Election/en.json';
import electionNp from './locales/pages/Election/np.json';
```

### Cause 1.2: Translations Not Registered in Messages Object

**Check:**
```bash
# Search for your page in messages object
grep -A 20 "const messages = {" resources/js/i18n.js | grep -i election
```

**Expected Output:**
```javascript
election: electionDe,
```

**Fix:**
Add to each language section in messages object (around lines 54-88):

**For German (de section):**
```javascript
de: {
  ...de,
  pages: {
    'voting-start': votingStartDe,
    'voting-election': votingElectionDe,
    pricing: pricingDe,
    welcome: welcomeDe,
    auth: authDe,
    election: electionDe,  // ← ADD THIS LINE
  },
},
```

**For English (en section):**
```javascript
en: {
  ...en,
  pages: {
    'voting-start': votingStartEn,
    'voting-election': votingElectionEn,
    pricing: pricingEn,
    welcome: welcomeEn,
    auth: authEn,
    election: electionEn,  // ← ADD THIS LINE
  },
},
```

**For Nepali (np section):**
```javascript
np: {
  ...np,
  pages: {
    'voting-start': votingStartNp,
    'voting-election': votingElectionNp,
    pricing: pricingNp,
    welcome: welcomeNp,
    auth: authNp,
    election: electionNp,  // ← ADD THIS LINE
  },
},
```

### Cause 1.3: Assets Not Rebuilt

**Check:**
```bash
# Check if public/js/app.js is recent
ls -lh public/js/app.js

# If timestamp is old, rebuild
npm run build
```

**Expected:**
- Timestamp should be very recent (within last few minutes)
- File size should be large (typically 500KB+)

**Fix:**
```bash
npm run build
```

### Cause 1.4: Browser Cache Not Cleared

**Check:**
- Open browser DevTools (F12)
- Go to Network tab
- Reload page
- Check if `app.js` is served from cache (status 304)

**Fix:**
Hard refresh browser:
- **Windows/Linux:** `Ctrl + Shift + R`
- **Mac:** `Cmd + Shift + R`

Or clear cache completely:
1. Open DevTools (F12)
2. Right-click refresh button
3. Select "Empty cache and hard refresh"

### Cause 1.5: Wrong Locale File Structure

**Check:**
Open your locale file and look at the structure:
```bash
head -20 resources/js/locales/pages/Election/en.json
```

**Wrong Structure:**
```json
{
  "pages": {
    "election": {
      "title": "Active Election - Voting Page",
      "voting_page": { ... }
    }
  }
}
```

**Correct Structure:**
```json
{
  "title": "Active Election - Voting Page",
  "voting_page": { ... }
}
```

**Why?** The i18n.js file adds the `pages.election` wrapper automatically. If you add it in the file too, you get double nesting: `pages.election.pages.election.*`

**Fix:**
Edit the locale file and remove the outer `"pages"` and `"election"` wrappers. Keep only the content.

---

## Issue 2: Build Fails with "Cannot find module"

**Symptom:**
```
ERROR in ./resources/js/i18n.js
Module not found: Error: Can't resolve './locales/pages/election/en.json'
```

**Root Causes & Solutions:**

### Cause 2.1: Wrong File Path Capitalization

**Check:**
```bash
# List actual folder names (case-sensitive)
ls -la resources/js/locales/pages/
```

**Problem:**
- Import says `./locales/pages/election/en.json` (lowercase)
- Actual folder is `./locales/pages/Election/` (PascalCase)

**Fix:**
Update i18n.js import to match actual folder name:
```javascript
// ✓ Correct (matches actual folder "Election")
import electionDe from './locales/pages/Election/de.json';

// ✗ Wrong (folder is "Election", not "election")
import electionDe from './locales/pages/election/de.json';
```

**Note:** On Windows, paths are case-insensitive, but on Linux/Mac they're case-sensitive. Always match the actual folder name!

### Cause 2.2: File Doesn't Exist

**Check:**
```bash
# List files in directory
ls -la resources/js/locales/pages/Election/

# Check if all 3 files exist
[ -f resources/js/locales/pages/Election/de.json ] && echo "de.json exists"
[ -f resources/js/locales/pages/Election/en.json ] && echo "en.json exists"
[ -f resources/js/locales/pages/Election/np.json ] && echo "np.json exists"
```

**Fix:**
Create missing files:
```bash
touch resources/js/locales/pages/Election/de.json
touch resources/js/locales/pages/Election/en.json
touch resources/js/locales/pages/Election/np.json
```

Add minimal content:
```json
{}
```

### Cause 2.3: Invalid JSON Syntax

**Check:**
```bash
# Validate JSON with Node.js
node -e "console.log(JSON.parse(require('fs').readFileSync('resources/js/locales/pages/Election/en.json')))"
```

**Output if valid:**
```javascript
{ ... object contents ... }
```

**Output if invalid:**
```
SyntaxError: Unexpected token
```

**Fix:**
Check for common JSON errors:
1. Missing commas between properties
2. Trailing commas after last property
3. Single quotes instead of double quotes
4. Unescaped special characters
5. Unmatched braces or brackets

Use online JSON validator: https://jsonlint.com/

---

## Issue 3: Missing Translations in One Language

**Symptom:**
- English translations display correctly
- German translations display correctly
- Nepali shows placeholder keys like `pages.election.title`

**Root Causes & Solutions:**

### Cause 3.1: Nepali File Not Imported

**Check:**
```bash
grep -n "import.*Np\|import.*np" resources/js/i18n.js | grep -i election
```

**Expected:** Should find import for Nepali
```javascript
import electionNp from './locales/pages/Election/np.json';
```

**Fix:**
Add missing import:
```javascript
import electionNp from './locales/pages/Election/np.json';
```

### Cause 3.2: Nepali Not Registered in Messages

**Check:**
```bash
grep -A 15 "np: {" resources/js/i18n.js | grep -A 10 "pages:"
```

**Expected:**
```javascript
np: {
  ...np,
  pages: {
    election: electionNp,  // ← Should be here
  },
}
```

**Fix:**
Add to `np.pages` object:
```javascript
np: {
  ...np,
  pages: {
    'voting-start': votingStartNp,
    'voting-election': votingElectionNp,
    pricing: pricingNp,
    welcome: welcomeNp,
    auth: authNp,
    election: electionNp,  // ← ADD THIS
  },
},
```

### Cause 3.3: Nepali File Missing Content

**Check:**
```bash
cat resources/js/locales/pages/Election/np.json
```

**If output is empty or `{}`:**
```bash
wc -l resources/js/locales/pages/Election/np.json
```

If only 1-2 lines, the file needs content.

**Fix:**
Add Nepali translations to the file.

---

## Issue 4: Locale File Syntax Errors

**Symptom:**
```
The text "pages.election.voting_page.title" is not defined
```

**Or build error:**
```
SyntaxError in locale file
```

**Root Causes & Solutions:**

### Cause 4.1: Invalid JSON

**Check:**
```bash
# Use Node.js to parse the JSON
node -e "require('fs').readFile('resources/js/locales/pages/Election/en.json', (e, d) => console.log(JSON.parse(d)))"
```

**Common errors:**
```json
// ✗ Missing comma
{
  "key1": "value1"
  "key2": "value2"  // ← SyntaxError: no comma after key1
}

// ✗ Trailing comma
{
  "key1": "value1",
  "key2": "value2",  // ← Not allowed in JSON
}

// ✗ Single quotes instead of double quotes
{
  'key': 'value'  // ← Must be double quotes
}

// ✗ Unescaped quotes
{
  "message": "He said "Hello""  // ← Quote not escaped
}

// ✗ Unescaped backslash
{
  "path": "C:\Users\name"  // ← Backslash must be \\
}

// ✗ Unmatched braces
{
  "key": "value"
// ← Missing closing }
```

**Fix:**
Use online JSON validator to identify errors:
- https://jsonlint.com/
- https://jsonformatter.org/

### Cause 4.2: Missing Required Keys

**Check:**
Compare all 3 language files have identical keys:

```bash
# Get keys from English file
jq 'keys' resources/js/locales/pages/Election/en.json | sort

# Get keys from German file
jq 'keys' resources/js/locales/pages/Election/de.json | sort

# Get keys from Nepali file
jq 'keys' resources/js/locales/pages/Election/np.json | sort
```

**Expected:** All three lists should be identical

**Fix:**
Add missing keys to files that don't have them.

---

## Issue 5: Translations Work in Dev, Not in Production

**Symptom:**
- `npm run dev` works fine
- `npm run build` and deployed, translations break

**Root Causes & Solutions:**

### Cause 5.1: Laravel Config Cache Not Cleared

**Check:**
```bash
# Check if config cache exists
ls -la bootstrap/cache/config.php
```

**Fix:**
```bash
php artisan config:clear
php artisan cache:clear
```

### Cause 5.2: Asset Mix Manifest Not Updated

**Check:**
```bash
# Check if public/mix-manifest.json is updated
cat public/mix-manifest.json | grep app.js
```

**Expected:**
Timestamp should be recent, pointing to current build.

**Fix:**
```bash
npm run build
```

### Cause 5.3: Old Assets Cached by Browser

**Check:**
- Open DevTools Network tab
- Reload page
- Check `app.js` response header `Cache-Control`

**Fix:**
Force browser to fetch new assets:
- Hard refresh: `Ctrl+Shift+R`
- Clear browser cache completely

### Cause 5.4: Old Assets Served by Server

**Check:**
```bash
# Check timestamp of public/js/app.js
ls -lh public/js/app.js

# If old, rebuild
npm run build
```

**Fix:**
```bash
npm run build
rm -rf node_modules/.cache  # Clear build cache if it exists
npm run build
php artisan config:clear
php artisan cache:clear
```

---

## Issue 6: Parameterized Translations Not Working

**Symptom:**
```
Code: {{ $t('pages.election.voting_ends', { date: '2024-12-10' }) }}
Display: "pages.election.voting_ends"
Expected: "Voting ends: 2024-12-10"
```

**Root Causes & Solutions:**

### Cause 6.1: Parameter Not in Locale File

**Check:**
```bash
# Search for the key with parameter placeholder
grep "voting_ends" resources/js/locales/pages/Election/en.json
```

**Current Content:**
```json
{
  "voting_ends": "Voting ends"  // ← Missing {date} placeholder
}
```

**Fix:**
Update locale file to include placeholder:
```json
{
  "voting_ends": "Voting ends: {date}"
}
```

### Cause 6.2: Wrong Parameter Name

**Check:**
Locale file says:
```json
{
  "voting_ends": "Voting ends: {end_date}"  // ← Parameter is "end_date"
}
```

Component says:
```vue
{{ $t('pages.election.voting_ends', { date: '2024-12-10' }) }}
<!-- ← But passing "date", not "end_date" -->
```

**Fix:**
Use matching parameter name:
```vue
{{ $t('pages.election.voting_ends', { end_date: '2024-12-10' }) }}
```

---

## Issue 7: Language Switching Not Working

**Symptom:**
- User selects German language
- Page still shows English

**Root Causes & Solutions:**

### Cause 7.1: localStorage Not Persisting

**Check:**
```javascript
// In browser console:
localStorage.getItem('preferred_locale')
```

**If empty or null:**
```javascript
// Manually set language
localStorage.setItem('preferred_locale', 'de')
location.reload()
```

**Fix:**
Verify localStorage is not disabled in browser settings.

### Cause 7.2: Language Selector Not Updating i18n

**Check:**
In language selector component:
```javascript
// When language button clicked, check if i18n is updated:
this.$i18n.locale = 'de'
```

**Expected:**
Page content updates to German immediately.

**Fix:**
Ensure language selector component has:
```javascript
data() {
  return {
    currentLocale: localStorage.getItem('preferred_locale') || 'de'
  }
},
methods: {
  switchLanguage(locale) {
    this.$i18n.locale = locale
    localStorage.setItem('preferred_locale', locale)
    // Force re-render if needed
    this.$forceUpdate()
  }
}
```

### Cause 7.3: Missing Language in i18n Configuration

**Check:**
```bash
# Check supported locales in i18n.js
grep -A 2 "locale:" resources/js/i18n.js
grep -A 2 "fallbackLocale:" resources/js/i18n.js
```

**Expected:**
```javascript
locale: initialLocale,
fallbackLocale: 'en',
```

**Fix:**
Ensure configuration includes all 3 languages in messages object.

---

## Issue 8: Performance Issues with Translations

**Symptom:**
- Page loads slowly
- Large file sizes
- Memory issues

**Solutions:**

### Solution 8.1: Bundle Size Too Large

**Check:**
```bash
# Check built app.js size
ls -lh public/js/app.js

# Check uncompressed size
gzip -cd public/js/app.js.gz | wc -c
```

**If very large (>1MB uncompressed):**
- Consider code-splitting
- Lazy-load translations for less-used features

### Solution 8.2: Too Many Translation Files

**Check:**
```bash
# Count translation files
find resources/js/locales/pages -name "*.json" | wc -l

# If > 20 pages, consider consolidation
```

**Fix:**
- Group related pages
- Use smaller, focused translation scopes

---

## Debug Commands Reference

```bash
# Validate all JSON locale files
for file in resources/js/locales/pages/**/*.json; do
  echo "Validating: $file"
  node -e "JSON.parse(require('fs').readFileSync('$file'))" && echo "✓ Valid" || echo "✗ Invalid"
done

# Check i18n.js syntax
node -c resources/js/i18n.js

# Find missing imports
grep -h "^import" resources/js/i18n.js | grep -c "from './locales/pages"

# Count total translation keys
find resources/js/locales -name "*.json" -exec grep -oh '"[^"]*":' {} \; | sort | uniq | wc -l

# Search for hardcoded strings (potential missed translations)
grep -r "Voting\|Election\|Submit" resources/js/Pages/ | grep -v ".json" | grep -v "$t("

# Check build output size
npm run build 2>&1 | grep -i "asset\|size"

# Monitor file changes during build
npm run build -- --watch
```

---

## Quick Recovery Checklist

If translations completely broken:

1. [ ] Check i18n.js file is syntactically correct:
   ```bash
   node -c resources/js/i18n.js
   ```

2. [ ] Verify all locale files are valid JSON:
   ```bash
   find resources/js/locales/pages -name "*.json" -exec node -e "JSON.parse(require('fs').readFileSync('{}'))" \;
   ```

3. [ ] Rebuild everything:
   ```bash
   npm run build
   php artisan config:clear
   php artisan cache:clear
   ```

4. [ ] Hard refresh browser:
   - Ctrl+Shift+R (Windows/Linux)
   - Cmd+Shift+R (Mac)

5. [ ] Check browser console for errors (F12)

6. [ ] Test with simple locale:
   - Create minimal test file
   - Test if it appears

---

## Getting Help

If you can't resolve the issue:

1. **Collect information:**
   ```bash
   npm run build 2>&1 | tee build-error.log
   node -c resources/js/i18n.js 2>&1 | tee syntax-check.log
   ```

2. **Provide:**
   - Error message text
   - Output of build command
   - List of locale files affected
   - Steps to reproduce

3. **Consult:**
   - [Translation-First Strategy Guide](./TRANSLATION_FIRST_STRATEGY.md)
   - [Vue i18n Docs](https://vue-i18n.intlify.dev/guide/)
   - Current working example: `resources/js/locales/pages/Election/`


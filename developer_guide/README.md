# Developer Guide

Documentation and best practices for the Public Digit platform.

---

## Contents

### 📚 TRANSLATION_FIRST_STRATEGY.md

**Comprehensive guide to implementing translations using the Translation-First Strategy.**

- How the i18n translation system works
- File structure and organization
- Complete step-by-step workflow (4 phases)
- Common translation patterns and best practices
- Real-world examples and migration checklist

### ✅ TRANSLATION_CHECKLIST.md

**Quick reference checklist for implementing translations.**

- Pre-development checklist
- Phase-by-phase implementation checklist
- Build and deployment checklist
- Issue quick fixes and common problems
- Team workflow and code review checklist

### 🔧 TRANSLATION_TROUBLESHOOTING.md

**Debug guide for when translations aren't working.**

- 8 major issues with root causes and fixes
- Build errors and import problems
- Language-specific issues
- Performance troubleshooting
- Debug commands reference

---

## Quick Start (5 Minutes)

### For a New Feature

1. Create locale files: `resources/js/locales/pages/YourPage/{en,de,np}.json`
2. Update `resources/js/i18n.js` with imports and registration
3. Add `$t()` calls to your component
4. Build: `npm run build && php artisan config:clear && php artisan cache:clear`
5. Test in browser with hard refresh (Ctrl+Shift+R)

### When Troubleshooting

1. Describe your symptom in TRANSLATION_TROUBLESHOOTING.md
2. Find the matching "Issue #" section
3. Follow the diagnosis steps
4. Apply the fix and rebuild

---

## Key Principles

1. **Translation First** - Create locale files BEFORE writing components
2. **Three Languages Always** - English (en), German (de), Nepali (np)
3. **No Hardcoded Text** - All user-facing text in JSON files
4. **Semantic Keys** - `voting_page.title`, not `label1`
5. **No Double Wrapping** - Locale files don't include "pages" wrapper
6. **Rebuild After Changes** - `npm run build` then clear caches

---

## Essential Commands

```bash
# Build frontend assets
npm run build

# Clear Laravel caches
php artisan config:clear && php artisan cache:clear

# Build and clear (combined)
npm run build && php artisan config:clear && php artisan cache:clear

# Validate JSON
node -e "console.log(JSON.parse(require('fs').readFileSync('resources/js/locales/pages/Election/en.json')))"
```

---

## Common Mistakes

| ❌ Mistake | ✅ Solution |
|-----------|-----------|
| Forgetting to build after changes | Run `npm run build` |
| Adding "pages" wrapper in locale file | Don't - i18n.js adds it automatically |
| Using single quotes in JSON | Use double quotes: `"key"` not `'key'` |
| Trailing comma in JSON | Remove comma before closing brace |
| Not clearing browser cache | Hard refresh: Ctrl+Shift+R |
| Hardcoding text in component | Use `{{ $t('pages.page.key') }}` |

---

For detailed information, see the individual guide files.

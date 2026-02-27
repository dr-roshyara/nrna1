# 🚀 Translation Quick Reference — Cheat Sheet

**For developers working with localization on Public Digit**

---

## 📋 Checklist: Adding a New Page

When creating a new page that needs translation:

```vue
<!-- resources/js/Pages/MyPage.vue -->
<template>
  <div>
    <!-- 1. ADD ELECTION HEADER WITH LOCALE PROP -->
    <ElectionHeader :isLoggedIn="true" :locale="$page.props.locale" />

    <!-- 2. USE TRANSLATION KEYS (NOT HARDCODED TEXT) -->
    <h1>{{ $t('pages.mypage.title') }}</h1>
    <p>{{ $t('pages.mypage.description') }}</p>

    <!-- 3. FOR DYNAMIC DATA, STILL TRANSLATE LABELS -->
    <button @click="submit">{{ $t('buttons.submit') }}</button>
  </div>
</template>

<script setup>
import ElectionHeader from '@/Components/Header/ElectionHeader.vue';

// Import translation files if needed
import { usePage } from '@inertiajs/vue3-vue3';
const page = usePage();
// Now can access: $page.props.locale
</script>
```

---

## 🎯 Required Translation Keys

For **EVERY new page**, add keys to all three locale files:

**resources/js/locales/de.json:**
```json
{
  "pages": {
    "mypage": {
      "title": "Seitentitel",
      "description": "Seitenbeschreibung"
    }
  }
}
```

**resources/js/locales/en.json:**
```json
{
  "pages": {
    "mypage": {
      "title": "Page Title",
      "description": "Page Description"
    }
  }
}
```

**resources/js/locales/np.json:**
```json
{
  "pages": {
    "mypage": {
      "title": "पृष्ठ शीर्षक",
      "description": "पृष्ठ विवरण"
    }
  }
}
```

**⚠️ CRITICAL:** All three files MUST have identical key structure!

---

## 🔄 Language Changing Flow

```
User clicks language selector
    ↓
handleLanguageChange() fires
    ↓
switchLanguage('en') executes:
    1. this.$i18n.locale = 'en'         ← Frontend update
    2. localStorage.setItem(...)         ← Browser memory
    3. document.cookie = 'locale=en'    ← Browser cookie
    4. window.location.reload()          ← Reload page
    ↓
Laravel receives request with Cookie: locale=en
    ↓
SetLocale middleware reads cookie
    ↓
app()->setLocale('en')
    ↓
HandleInertiaRequests shares: $page.props.locale = 'en'
    ↓
Vue receives locale prop
    ↓
ElectionHeader updates
    ↓
Page renders in English
```

---

## ⚡ Common Code Patterns

### Pattern 1: Simple Translation

```vue
<!-- Bad ❌ -->
<h1>Welcome</h1>

<!-- Good ✅ -->
<h1>{{ $t('pages.welcome.title') }}</h1>
```

### Pattern 2: Translation with Variables

```vue
<!-- Bad ❌ -->
<p>Hello John, welcome!</p>

<!-- Good ✅ -->
<p>{{ $t('greeting.personal', { name: userName }) }}</p>
```

**In locale file:**
```json
{
  "greeting": {
    "personal": "Hello {name}, welcome!"
  }
}
```

### Pattern 3: Conditional Translation

```vue
<!-- Bad ❌ -->
<p v-if="isNew">New user</p>
<p v-else>Returning user</p>

<!-- Good ✅ -->
<p>{{ $t(isNew ? 'status.new_user' : 'status.returning') }}</p>
```

### Pattern 4: List Items

```vue
<!-- Template -->
<ul>
  <li v-for="item in items" :key="item.id">
    {{ $t(`options.${item.key}`) }}
  </li>
</ul>

<!-- Locale file -->
{
  "options": {
    "option1": "First Option",
    "option2": "Second Option",
    "option3": "Third Option"
  }
}
```

### Pattern 5: Multi-line Text

```vue
<!-- Bad ❌ -->
<div>
  <p>First line of text</p>
  <p>Second line of text</p>
</div>

<!-- Good ✅ -->
<div v-html="$t('pages.mypage.detailed_text')"></div>

<!-- CAUTION: Only use v-html if you control the content! -->
```

---

## 🛠️ Middleware Stack (Critical Order)

**File:** `app/Http/Kernel.php`

```php
'web' => [
    EncryptCookies::class,              // Line 1: Decrypt
    AddQueuedCookiesToResponse::class,  // Line 2: Queue for response
    StartSession::class,                // Line 3: Start session
    AuthenticateSession::class,         // Line 4: Auth session
    ShareErrorsFromSession::class,      // Line 5: Share errors
    VerifyCsrfToken::class,            // Line 6: CSRF check
    SubstituteBindings::class,         // Line 7: Route bindings
    SetLocale::class,                   // Line 8: ⭐ SET LOCALE HERE
    HandleInertiaRequests::class,      // Line 9: Share with Vue
],
```

**⚠️ SetLocale MUST be:**
- ✅ AFTER EncryptCookies (cookies readable)
- ✅ AFTER StartSession (session available)
- ✅ BEFORE HandleInertiaRequests (share before Inertia)

---

## 🔍 Debugging Commands

### Check Locale in Page Props

```javascript
// In browser console
console.log('Current locale:', this.$page.props.locale);
console.log('I18n locale:', this.$i18n.locale);
console.log('Cookie:', document.cookie);
```

### Check Middleware Order

```bash
grep -n "SetLocale\|EncryptCookies\|StartSession\|HandleInertia" app/Http/Kernel.php
```

### Verify Translation File

```bash
# Check keys exist
grep "pages.welcome.title" resources/js/locales/*.json

# Count keys
grep -o '"[^"]*":' resources/js/locales/en.json | wc -l
```

### Check Cookie Persistence

```bash
# View all cookies
console.log(document.cookie);

# Extract locale cookie
console.log(document.cookie.match(/locale=([^;]+)/)?.[1]);
```

---

## ❌ Common Mistakes

### ❌ Mistake 1: Hardcoded Text

```vue
<!-- WRONG -->
<h1>Welcome to Public Digit</h1>

<!-- RIGHT -->
<h1>{{ $t('pages.welcome.title') }}</h1>
```

### ❌ Mistake 2: Missing ElectionHeader Prop

```vue
<!-- WRONG: Language doesn't persist -->
<ElectionHeader :isLoggedIn="true" />

<!-- RIGHT -->
<ElectionHeader :isLoggedIn="true" :locale="$page.props.locale" />
```

### ❌ Mistake 3: v-model on Language Selector

```vue
<!-- WRONG: Causes circular reactivity -->
<select v-model="currentLocale" @change="handleChange">

<!-- RIGHT -->
<select :value="currentLocale" @change="handleLanguageChange">
```

### ❌ Mistake 4: SetLocale in Wrong Middleware Group

```php
// WRONG: Cookies still encrypted
protected $middleware = [
    SetLocale::class,  // TOO EARLY
];

// RIGHT
protected $middlewareGroups = [
    'web' => [
        EncryptCookies::class,   // Decrypt first
        ...
        SetLocale::class,        // Then read
        HandleInertiaRequests::class,
    ]
];
```

### ❌ Mistake 5: Missing Keys in Locale Files

```json
// en.json has 50 keys
// de.json has 45 keys ← MISSING 5 KEYS
// np.json has 48 keys ← MISSING 2 KEYS

// This causes: {{ $t('pages.welcome.missing_key') }}
// Shows: "pages.welcome.missing_key" (raw key text)
```

### ❌ Mistake 6: Using Global Helper Instead of Prop

```javascript
// WRONG: Doesn't sync with frontend
const locale = app()->getLocale();

// RIGHT: Use the prop passed from Laravel
const locale = props.locale;

// Or in component context:
{{ $page.props.locale }}
```

---

## ✅ Verification Checklist

Before declaring a feature complete:

```
[ ] All pages have <ElectionHeader :locale="$page.props.locale" />
[ ] No hardcoded English/German/Nepali text in templates
[ ] All translation keys exist in all 3 locale files
[ ] Keys follow structure: pages.featurename.keyname
[ ] SetLocale middleware is in 'web' group
[ ] SetLocale is after EncryptCookies, before HandleInertia
[ ] No circular reactivity errors in console
[ ] Language persists when navigating pages
[ ] Language persists when refreshing page (F5)
[ ] Cookies show: locale=de (or en/np)
[ ] $page.props.locale matches selected language
[ ] No translation keys showing as raw text
[ ] Tested in all 3 languages (de, en, np)
```

---

## 🚨 When Something Breaks

### "Language resets after navigation"

1. Check Kernel.php middleware order
2. Verify SetLocale middleware exists
3. Check cookie in DevTools: Application → Cookies
4. Run: `php artisan cache:clear`

### "Show translation key instead of text"

1. Check locale file has the key
2. Verify key exists in ALL three files
3. Check $i18n.locale matches selected language
4. Check console for errors (F12)

### "Language selector doesn't appear"

1. Verify ElectionHeader component is imported
2. Check component is used in template
3. Check for console errors (F12)
4. Run: `npm run dev` if using Vite

### "Middleware error: Session store not set"

1. Remove SetLocale from global `$middleware`
2. Add to `web` middleware group instead
3. Add safety checks: `if ($request->hasSession())`

---

## 📁 File Locations Quick Map

| Purpose | File | Action |
|---------|------|--------|
| **Language Options** | `resources/js/Components/Header/ElectionHeader.vue` | Add language to dropdown |
| **German Translations** | `resources/js/locales/de.json` | Add keys here |
| **English Translations** | `resources/js/locales/en.json` | Add keys here |
| **Nepali Translations** | `resources/js/locales/np.json` | Add keys here |
| **Vue I18n Setup** | `resources/js/i18n.js` | Add new language import |
| **Read Cookies** | `app/Http/Middleware/SetLocale.php` | Logic here |
| **Share Locale** | `app/Http/Middleware/HandleInertiaRequests.php` | Share method here |
| **Middleware Order** | `app/Http/Kernel.php` | Check order here |
| **App Default** | `.env` | `APP_LOCALE=de` |

---

## 🧪 One-Minute Test

Quick sanity check for any page:

```
1. Open http://localhost:8000/mypage
2. Open DevTools (F12) → Console
3. Run: this.$page.props.locale
   → Should return: 'de' (or 'en'/'np')
4. Change language selector
5. Run same command
   → Should return new language
6. Refresh page (F5)
   → Language should persist
7. Check: console.log(document.cookie)
   → Should include: locale=de (or en/np)
```

If ANY step fails, see **"When Something Breaks"** section above.

---

## 🎓 Learning Path

1. **Day 1:** Read main [TRANSLATION_ARCHITECTURE.md](./TRANSLATION_ARCHITECTURE.md)
2. **Day 2:** Try adding a simple page with translations
3. **Day 3:** Test language persistence across pages
4. **Day 4:** Debug a real issue using troubleshooting section
5. **Day 5:** Add a new language following "Adding New Languages" section

---

## 📚 Key Concepts

| Concept | Explanation |
|---------|-------------|
| **Vue I18n** | Frontend translation system — translates text in Vue components |
| **Locale** | Language code: 'de', 'en', 'np' |
| **Translation Key** | Unique identifier for a string: 'pages.welcome.title' |
| **SetLocale Middleware** | Backend — reads cookie and sets app locale |
| **HandleInertiaRequests** | Backend — shares locale with Vue via props |
| **ElectionHeader** | Frontend component — language selector |
| **Persistence** | Keeping language choice across page navigations |
| **Cookie** | Browser storage for locale preference |
| **Fallback** | Default language if none specified |

---

## 🎯 Remember

```
Frontend: Vue I18n + ElectionHeader + Cookies
Backend: SetLocale Middleware + Inertia Props
Glue: HTTP Cookies ← The bridge between them

When in doubt: Check middleware order!
```

---

**Version:** 1.0
**For:** Public Digit Developers
**Updated:** February 2026


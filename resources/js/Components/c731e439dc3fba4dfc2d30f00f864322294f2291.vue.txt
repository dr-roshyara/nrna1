# Translation-First Architecture Guide
## Understanding Multi-Language Support in Public Digit

**Document Purpose:** Comprehensive guide to the translation-first architecture implemented in Public Digit, including the login route's language handling strategy.

**Date:** March 2, 2026
**Status:** Production Implementation

---

## Table of Contents

1. [Overview](#overview)
2. [Core Concepts](#core-concepts)
3. [Architecture Design](#architecture-design)
4. [Login Route Language Handling](#login-route-language-handling)
5. [Implementation Patterns](#implementation-patterns)
6. [Best Practices](#best-practices)
7. [Troubleshooting](#troubleshooting)

---

## Overview

Public Digit uses a **translation-first architecture** where:

- **Backend:** Returns translation **keys** (e.g., `trust_signals.compliance.message`)
- **Frontend:** Uses Vue i18n to translate keys to actual text (e.g., "DSGVO-konform seit 2024")
- **Language Selection:** Determined by cookie → session → config, with user-preferred language applied globally

**Result:** Language is decoupled from routing - users get translated content regardless of URL structure.

---

## Core Concepts

### 1. Backend Separation

The backend **never returns user-facing text**. Instead, it returns structured data with translation keys:

**Wrong Pattern (Old):**
```php
// app/Services/TrustSignalService.php
return [
    'message' => 'DSGVO-konform seit 2024',  // ❌ Hard-coded German text
    'tooltip' => 'Alle Daten werden...',     // ❌ No way to translate
];
```

**Correct Pattern (Current):**
```php
// app/Services/TrustSignalService.php
return [
    'id' => 'compliance',
    'message_key' => 'trust_signals.compliance.message',  // ✅ Localization key
    'tooltip_key' => 'trust_signals.compliance.tooltip',  // ✅ Can be translated
];
```

**Benefits:**
- Backend is 100% language-agnostic
- Same API works for all languages
- Translators only work with JSON files, not code

### 2. Frontend Translation

The frontend imports locale JSON files and uses `$t()` to translate keys:

**Locale File:**
```json
// resources/js/locales/pages/Dashboard/trust_signals/de.json
{
  "trust_signals": {
    "compliance": {
      "message": "DSGVO-konform seit 2024",
      "tooltip": "Alle Daten werden DSGVO-konform verarbeitet"
    }
  }
}
```

**Vue Component:**
```vue
<template>
  <div v-for="signal in signals" :key="signal.id">
    <!-- Use translation key returned by backend -->
    <p>{{ $t(signal.message_key) }}</p>
    <span :title="$t(signal.tooltip_key)">ℹ️</span>
  </div>
</template>

<script>
export default {
  async mounted() {
    // Backend returns keys, frontend translates them
    const response = await fetch('/api/dashboard/trust-signals');
    this.signals = await response.json();
  }
}
</script>
```

### 3. Locale Determination (SetLocale Middleware)

The `SetLocale` middleware determines the application locale **without URL prefixes**:

```php
// app/Http/Middleware/SetLocale.php

public function handle(Request $request, Closure $next): Response
{
    $locale = null;

    // Priority 1: User's preferred language (cookie)
    if ($request->hasCookie('locale') && $this->isValidLocale($request->cookie('locale'))) {
        $locale = $request->cookie('locale');
    }
    // Priority 2: Session language
    elseif ($request->hasSession() && $request->session()->has('locale')) {
        $locale = $request->session()->get('locale');
    }
    // Priority 3: Config default
    else {
        $locale = config('app.locale', 'en');
    }

    app()->setLocale($locale);
    return $next($request);
}
```

**Decision Flow:**
```
User visits /login
    ↓
SetLocale middleware runs
    ↓
1. Check for locale cookie (user's preferred language)
    ↓
   [If found and valid] → Use that locale
    ↓
2. Check session (previous page's language)
    ↓
   [If found and valid] → Use that locale
    ↓
3. Use config default (app.locale)
    ↓
Login page displays in user's preferred language
```

---

## Architecture Design

### System Components

```
┌──────────────────────────────────────────────────────────────┐
│                    REQUEST FLOW                              │
└──────────────────────────────────────────────────────────────┘

1. USER REQUEST
   GET /login  (no language prefix needed)
        ↓
2. SetLocale MIDDLEWARE (runs globally)
   Determines locale from: cookie → session → config
   app()->setLocale('de')  or  'en' or 'np'
        ↓
3. CONTROLLER
   return Inertia::render('Login', [
       'user' => auth()->user(),
   ]);
        ↓
4. FRONTEND (Vue Component)
   - Receives data from controller
   - Uses app()->getLocale() from i18n
   - Translates all keys using $t()
        ↓
5. BROWSER DISPLAYS
   Content in user's preferred language
   (German, English, or Nepali)
```

### Language Storage

| Level | Storage | Priority | Lifetime |
|-------|---------|----------|----------|
| **User Preference** | Cookie `locale` | 1 (Highest) | 1 year |
| **Session Context** | Session `locale` | 2 | Until session expires |
| **System Default** | Config `app.locale` | 3 (Lowest) | Application-wide |

---

## Login Route Language Handling

### Current Implementation (Correct)

**Route Definition:**
```php
// routes/web.php
Route::middleware('guest')->group(function () {
    Route::get('/login', [\App\Http\Controllers\Auth\LoginController::class, 'show'])->name('login');
});
```

**No language prefix:** The login route is accessible at `/login` (not `/de/login` or `/en/login`)

**Why This is Correct:**

1. **Authentication is Language-Neutral**
   - Login process is same for all languages
   - Only the UI text changes

2. **SetLocale Handles Language**
   - Middleware sets correct locale globally
   - Works for any URL without prefix

3. **Better User Experience**
   - Users don't need to know their language code
   - Bookmarks work regardless of language preference
   - Shared links work for everyone

### Flow for Login Page

```
User clicks "Log in" link with preferred language (de)
        ↓
Browser sets cookie: locale=de
        ↓
User navigates to: GET /login
        ↓
SetLocale middleware detects cookie (locale=de)
        ↓
app()->setLocale('de')
        ↓
LoginController returns Inertia::render('Login')
        ↓
Frontend i18n reads app locale: 'de'
        ↓
Form labels, buttons, messages render in German
```

### Example: Login Form Text Translation

**Backend Controller:**
```php
// app/Http/Controllers/Auth/LoginController.php
public function show()
{
    return Inertia::render('Auth/Login', [
        // Backend returns NO text, just data
        'canResetPassword' => Route::has('password.request'),
    ]);
}
```

**Frontend Component:**
```vue
<!-- resources/js/Pages/Auth/Login.vue -->
<template>
  <form @submit.prevent="submit">
    <!-- Use $t() for all user-facing text -->
    <label>{{ $t('pages.auth.login.email') }}</label>
    <input v-model="form.email" type="email" />

    <label>{{ $t('pages.auth.login.password') }}</label>
    <input v-model="form.password" type="password" />

    <button type="submit">{{ $t('pages.auth.login.submit') }}</button>
  </form>
</template>

<script>
export default {
  data() {
    return {
      form: { email: '', password: '' },
    };
  },
  methods: {
    submit() {
      // Form submission logic
    }
  }
}
</script>
```

**Locale Files:**
```json
// resources/js/locales/pages/Auth/de.json
{
  "login": {
    "email": "E-Mail-Adresse",
    "password": "Passwort",
    "submit": "Anmelden"
  }
}

// resources/js/locales/pages/Auth/en.json
{
  "login": {
    "email": "Email Address",
    "password": "Password",
    "submit": "Sign In"
  }
}

// resources/js/locales/pages/Auth/np.json
{
  "login": {
    "email": "ईमेल ठेगाना",
    "password": "पासवर्ड",
    "submit": "साइन इन गर्नुहोस्"
  }
}
```

**Result:** Login form automatically displays in German, English, or Nepali based on user's locale cookie.

---

## Implementation Patterns

### Pattern 1: Backend Service Returns Keys

**Service Layer:**
```php
// app/Services/ElectionService.php
class ElectionService
{
    public function getElectionDetails($electionId)
    {
        $election = Election::find($electionId);

        return [
            'id' => $election->id,
            'name' => $election->name,           // Always return field names in English
            'status' => $election->status,
            'voting_message_key' => 'election.voting.active',  // NOT "Abstimmung aktiv"
            'vote_button_key' => 'election.vote.submit',       // NOT "Abstimmen"
        ];
    }
}
```

**Key Principle:** Return field names in English, translation keys for UI text.

### Pattern 2: Frontend Translates Keys

**Controller:**
```php
// app/Http/Controllers/ElectionController.php
public function show($id)
{
    $electionService = app(ElectionService::class);
    return Inertia::render('Election/Show', [
        'election' => $electionService->getElectionDetails($id),
    ]);
}
```

**Vue Component:**
```vue
<template>
  <div class="election">
    <h1>{{ election.name }}</h1>

    <!-- Translate key from backend -->
    <p class="status">{{ $t(election.voting_message_key) }}</p>

    <!-- Translate key from backend -->
    <button>{{ $t(election.vote_button_key) }}</button>
  </div>
</template>

<script>
export default {
  props: {
    election: Object,
  }
}
</script>
```

**Locale File:**
```json
// resources/js/locales/de.json
{
  "election": {
    "voting": {
      "active": "Die Abstimmung ist aktiv"
    },
    "vote": {
      "submit": "Jetzt abstimmen"
    }
  }
}
```

### Pattern 3: Language Switching Without Page Reload

**Language Selector Component:**
```vue
<template>
  <select @change="changeLanguage" :value="currentLocale">
    <option value="de">Deutsch</option>
    <option value="en">English</option>
    <option value="np">नेपाली</option>
  </select>
</template>

<script>
export default {
  computed: {
    currentLocale() {
      return this.$i18n.locale;
    }
  },
  methods: {
    changeLanguage(event) {
      const locale = event.target.value;

      // 1. Set i18n locale (immediate UI update)
      this.$i18n.locale = locale;

      // 2. Save to cookie (persists across sessions)
      document.cookie = `locale=${locale}; path=/; max-age=31536000`;

      // 3. Save to session (for current session)
      // This happens via API call or form submission
    }
  }
}
</script>
```

**Result:** Page content updates instantly without reload, cookie ensures preference persists.

---

## Best Practices

### 1. Semantic Translation Keys

✅ **DO:**
```json
{
  "pages": {
    "election": {
      "voting_page": {
        "status_active": "The election is active"
      }
    }
  }
}
```

❌ **DON'T:**
```json
{
  "label1": "The election is active",
  "msg2": "Voting started"
}
```

### 2. Consistent Key Structure

✅ **DO:**
```
pages.election.voting_page.status_active
pages.election.voting_page.submit_button
pages.election.results.title
```

❌ **DON'T:**
```
election_voting_page_status
electionStatus
voting_active
```

### 3. Translate Everything User-Facing

This includes:
- ✅ Button labels
- ✅ Form labels
- ✅ Error messages
- ✅ Validation messages
- ✅ Tooltips
- ✅ Help text
- ✅ Status messages
- ✅ Success/failure messages

❌ **Don't translate:**
- Field names in API
- Database column names
- Programming identifiers

### 4. Handle Special Characters

German umlauts, accents, and special characters work fine in JSON:
```json
{
  "message": "Überprüfung erfolgreich",
  "email": "E-Mail-Adresse",
  "description": "Führerschaft & Transparenz"
}
```

### 5. Context for Translators

Add comments explaining context:
```json
{
  "verification_code": "Verification Code",
  "_verification_code_context": "Label for 6-digit code sent via email",

  "submit": "Submit",
  "_submit_context": "Button label - appears in form footer"
}
```

---

## Troubleshooting

### Issue 1: Login Page Shows Wrong Language

**Symptom:** User navigates to `/login` but sees English instead of German

**Diagnosis:**
```bash
# Check browser cookies
open DevTools → Application → Cookies
# Look for: locale=de (or en, np)

# Check localStorage
open DevTools → Application → LocalStorage
# Look for i18n locale setting
```

**Solution:**
1. Verify cookie was set: `document.cookie` in console
2. If missing, user needs to select language first
3. Or manually set: `document.cookie = 'locale=de; path=/; max-age=31536000'`

### Issue 2: Language Changes But Page Doesn't Update

**Symptom:** User changes language in dropdown, but content doesn't translate

**Diagnosis:**
```javascript
// In browser console
console.log(this.$i18n.locale);  // Check current locale
console.log(this.$t('pages.auth.login.email'));  // Try translating
```

**Solution:**
1. Verify computed property watches `$i18n.locale`
2. Check locale files exist and have all keys
3. Clear webpack cache: `rm -rf public/js node_modules/.cache`
4. Hard refresh: `Ctrl+Shift+R`

### Issue 3: Translations Missing for One Language

**Symptom:** German works, English shows placeholder keys

**Diagnosis:**
```bash
# Check if file exists
ls resources/js/locales/pages/YourPage/en.json

# Check if imported in i18n.js
grep "yourPage" resources/js/i18n.js
```

**Solution:**
1. Create missing locale file
2. Add import to i18n.js
3. Register in messages object
4. Rebuild: `npm run build`

### Issue 4: Special Characters Display Wrong

**Symptom:** Umlauts appear as mojibake: "ÃberprÃ¼fung"

**Solution:**
1. Ensure JSON file uses UTF-8 encoding
2. Verify HTML has: `<meta charset="UTF-8">`
3. Check Laravel response header: `Content-Type: text/html; charset=utf-8`

---

## Supported Languages

| Code | Language | Region | Status |
|------|----------|--------|--------|
| **de** | Deutsch | Germany, Austria, Switzerland | ✅ Full |
| **en** | English | International | ✅ Full |
| **np** | नेपाली | Nepal | ✅ Full |

### Adding New Language

1. **Create locale files:**
   ```bash
   mkdir -p resources/js/locales/pages/YourPage
   echo '{ "title": "Your Title in French" }' > resources/js/locales/pages/YourPage/fr.json
   ```

2. **Update i18n.js:**
   ```javascript
   import frenchDe from './locales/pages/YourPage/fr.json';

   const messages = {
     fr: { ...french, pages: { yourPage: frenchDe } }
   }
   ```

3. **Update language selector:**
   ```vue
   <option value="fr">Français</option>
   ```

---

## Performance Considerations

### 1. Locale JSON File Size

- Single locale file: ~3-5 KB (uncompressed)
- With gzip: ~1-2 KB
- Multiple locales: Linear scaling

**Optimization:** Only load current locale, defer others.

### 2. Translation Lookup Time

- `$t('key')` lookup: < 1ms
- No performance penalty for translation

### 3. Caching

- Locale files bundled with app.js
- No additional HTTP requests
- Browser caches entire bundle

---

## Integration with Login Route

### Recommended Implementation

**Current Setup (Correct):**
```php
// ✅ No language prefix
Route::get('/login', [LoginController::class, 'show'])->name('login');
```

**Why It Works:**
1. SetLocale middleware runs for ALL requests (global)
2. Locale determined from cookie/session/config
3. Frontend translates all text using current locale
4. Users see login in their preferred language

### What NOT to Do

❌ **Language-Prefixed Login:**
```php
Route::get('/{locale}/login', [LoginController::class, 'show']);
```

**Problems:**
- Users need to know their language code
- Bookmarks break when language changes
- Extra route complexity
- Shared links don't work for other languages

---

## Summary

The translation-first architecture ensures:

✅ **Backend Independence** - Services are language-agnostic
✅ **Frontend Control** - Vue components handle all text translation
✅ **User Preference** - Language determined by cookie, not URL
✅ **Scalability** - Adding new language only requires JSON files
✅ **Performance** - No API overhead for translations
✅ **Consistency** - Single source of truth for each translation key

**For Login Route Specifically:**
- `/login` works without language prefix
- SetLocale applies correct locale globally
- Frontend translates all text to user's preferred language
- No URL rewrites or redirects needed

---

**Document Version:** 1.0
**Last Updated:** March 2, 2026
**References:**
- `developer_guide/translation/` - Detailed implementation guides
- `app/Http/Middleware/SetLocale.php` - Locale determination logic
- `resources/js/i18n.js` - Vue i18n configuration

# 🌍 Translation Architecture & Localization Guide

**Public Digit Platform** — Multi-Language Support for Vue 3 + Laravel + Inertia.js

---

## Table of Contents

1. [Overview](#overview)
2. [System Architecture](#system-architecture)
3. [Technology Stack](#technology-stack)
4. [Setup & Configuration](#setup--configuration)
5. [Language Persistence Flow](#language-persistence-flow)
6. [Frontend Implementation](#frontend-implementation)
7. [Backend Implementation](#backend-implementation)
8. [Adding New Languages](#adding-new-languages)
9. [Best Practices](#best-practices)
10. [Troubleshooting](#troubleshooting)

---

## Overview

The Public Digit platform supports **three languages**:
- 🇩🇪 **German (de)** — Default
- 🇬🇧 **English (en)**
- 🇳🇵 **Nepali (np)**

### Core Principle

**Language selection persists across all pages and routes** — whether user is on public pages, login, registration, dashboard, or election pages.

### Architecture Philosophy

```
User selects language on ANY page
    ↓
Frontend (Vue I18n) updates immediately
    ↓
Frontend sets persistent cookie
    ↓
Page reloads/navigates
    ↓
Laravel middleware reads cookie
    ↓
Backend applies locale to all responses
    ↓
Inertia.js shares locale prop with Vue
    ↓
All pages render in selected language
```

---

## System Architecture

### High-Level Flow

```
┌─────────────────────────────────────────────────────────────────┐
│                     USER SELECTS LANGUAGE                        │
│                    (ElectionHeader Component)                     │
└──────────────────────────┬──────────────────────────────────────┘
                           │
                           ↓
        ┌──────────────────────────────────────────┐
        │   FRONTEND (Vue 3 + vue-i18n)            │
        │  ─────────────────────────────────────  │
        │  1. Update $i18n.locale                 │
        │  2. Save to localStorage                │
        │  3. Set cookie: locale=en               │
        │  4. Reload page or navigate             │
        └──────────────────┬───────────────────────┘
                           │
                           ↓ HTTP Request
        ┌──────────────────────────────────────────┐
        │   LARAVEL MIDDLEWARE STACK               │
        │  ─────────────────────────────────────  │
        │  1. EncryptCookies (decrypt)            │
        │  2. StartSession                        │
        │  3. SetLocale (read cookie) ←CRITICAL   │
        │  4. HandleInertiaRequests (share prop)  │
        └──────────────────┬───────────────────────┘
                           │
                           ↓ Response with locale prop
        ┌──────────────────────────────────────────┐
        │   INERTIA.JS PROPS                       │
        │  ─────────────────────────────────────  │
        │  $page.props.locale = 'en'              │
        │  Available to all Vue components        │
        └──────────────────┬───────────────────────┘
                           │
                           ↓
        ┌──────────────────────────────────────────┐
        │   VUE COMPONENTS                         │
        │  ─────────────────────────────────────  │
        │  1. ElectionHeader receives locale prop │
        │  2. Initializes with correct language   │
        │  3. Renders translations                │
        │  4. Dashboard stays in selected language│
        └──────────────────────────────────────────┘
```

### Persistence Mechanism

**Primary Method:** HTTP Cookies
- Set by: Frontend JavaScript
- Read by: Laravel SetLocale middleware
- Scope: Browser domain (`localhost:8000` or your domain)
- Expiry: 1 year
- **Key:** `locale`
- **Values:** `de`, `en`, `np`

**Fallback Methods** (in priority order):
1. Cookie: `locale=en` ← User's choice
2. Session: `locale` key ← Server-side storage
3. Config: `APP_LOCALE` from `.env` ← Application default
4. Hardcoded default: `de` ← Last resort

---

## Technology Stack

### Frontend

| Technology | Role | Version |
|-----------|------|---------|
| **Vue 3** | Component framework | 3.x |
| **vue-i18n** | Translation management | 10.x |
| **Inertia.js** | Server-side prop sharing | 1.x |
| **Vite** | Build tool | Latest |

### Backend

| Technology | Role | Version |
|-----------|------|---------|
| **Laravel** | Web framework | 12.x |
| **PHP** | Language | 8.2+ |
| **Inertia** | Response bridge | 1.x |
| **Middleware** | Request pipeline | Native Laravel |

### Storage

| Storage | Purpose | Persistence |
|---------|---------|-------------|
| **Browser Cookie** | User's language choice | ~1 year |
| **localStorage** | Backup/fallback | Until cleared |
| **Session** | Server-side backup | Until session expires |

---

## Setup & Configuration

### 1. Vue I18n Configuration

**File:** `resources/js/i18n.js`

```javascript
import { createI18n } from 'vue-i18n';

// Import locale files
import de from './locales/de.json';
import en from './locales/en.json';
import np from './locales/np.json';

const i18n = createI18n({
  legacy: false,
  locale: localStorage.getItem('preferred_locale') || 'de',
  fallbackLocale: 'de',
  globalInjection: true,
  messages: {
    de,
    en,
    np
  }
});

export default i18n;
```

**Key Points:**
- `legacy: false` — Use Composition API (Vue 3)
- `locale` — Read from localStorage as startup default
- `fallbackLocale` — Fallback if translation missing
- Messages object contains all translations

### 2. Laravel Configuration

**File:** `.env`

```env
APP_LOCALE=de
APP_FALLBACK_LOCALE=de
```

**File:** `config/app.php`

```php
'locale' => env('APP_LOCALE', 'de'),
'fallback_locale' => env('APP_FALLBACK_LOCALE', 'de'),
'supported_locales' => ['de', 'en', 'np'],
```

### 3. Locale Directory Structure

```
resources/
├── js/
│   ├── i18n.js                          # Vue I18n config
│   ├── locales/                         # Translation files
│   │   ├── de.json                      # German (default)
│   │   ├── en.json                      # English
│   │   └── np.json                      # Nepali
│   ├── Components/
│   │   └── Header/
│   │       └── ElectionHeader.vue       # Language selector
│   ├── Pages/
│   │   ├── Welcome.vue                  # Public page
│   │   ├── Auth/
│   │   │   ├── Login.vue               # Has ElectionHeader
│   │   │   └── Register.vue            # Has ElectionHeader
│   │   └── Election/
│   │       ├── ElectionPage.vue        # Has ElectionHeader
│   │       └── SelectElection.vue      # Has ElectionHeader
│   └── app.js                          # Main entry with i18n
├── lang/                               # Laravel translations (backup)
│   ├── de/                             # German (Laravel)
│   ├── en/                             # English (Laravel)
│   └── np/                             # Nepali (Laravel)
```

### 4. Middleware Configuration

**File:** `app/Http/Kernel.php`

**Middleware Stack Order (CRITICAL):**

```php
protected $middlewareGroups = [
    'web' => [
        \App\Http\Middleware\EncryptCookies::class,           // 1. Decrypt cookies
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class, // 2. Queue cookies
        \Illuminate\Session\Middleware\StartSession::class,   // 3. Start session
        \Laravel\Jetstream\Http\Middleware\AuthenticateSession::class, // 4. Auth
        \Illuminate\View\Middleware\ShareErrorsFromSession::class, // 5. Errors
        \App\Http\Middleware\VerifyCsrfToken::class,          // 6. CSRF
        \Illuminate\Routing\Middleware\SubstituteBindings::class, // 7. Bindings
        \App\Http\Middleware\SetLocale::class,                 // 8. ← SET LOCALE HERE
        \App\Http\Middleware\HandleInertiaRequests::class,    // 9. Share with Vue
    ],
];
```

**⚠️ CRITICAL ORDER:** SetLocale MUST come:
- ✅ AFTER `EncryptCookies` (so cookies are readable)
- ✅ AFTER `StartSession` (so session is accessible)
- ✅ BEFORE `HandleInertiaRequests` (so locale is set before sharing props)

---

## Language Persistence Flow

### Complete Lifecycle

#### Step 1: Initial Page Load

```
User visits: http://localhost:8000/
    ↓
Browser has no cookie
    ↓
SetLocale middleware checks:
  - Cookie 'locale'? No
  - Session 'locale'? No (first request)
  - .env APP_LOCALE? Yes → 'de'
    ↓
app()->setLocale('de')
    ↓
HandleInertiaRequests shares: $page.props.locale = 'de'
    ↓
Vue receives locale prop
    ↓
ElectionHeader initializes with locale='de'
    ↓
Page renders in German
```

#### Step 2: User Changes Language

```
User clicks language selector in ElectionHeader (on landing page)
    ↓
ElectionHeader.vue handleLanguageChange() fires
    ↓
switchLanguage('en') method executes:

    1. this.$i18n.locale = 'en'           // Update Vue I18n immediately
    2. localStorage.setItem('preferred_locale', 'en')  // Save for next page
    3. Set cookie:
       document.cookie = 'locale=en; expires=<+1 year>; path=/; SameSite=Lax'
    4. setTimeout(() => window.location.reload(), 100)  // Reload page
    ↓
Page reloads with: GET / (includes Cookie: locale=en)
    ↓
SetLocale middleware:
  - Reads cookie: locale=en
  - app()->setLocale('en')
  - Stores in session: session()->put('locale', 'en')
    ↓
HandleInertiaRequests shares: $page.props.locale = 'en'
    ↓
Vue initializes with locale prop 'en'
    ↓
Page renders in English
```

#### Step 3: Navigate to Different Page

```
User on landing page (English)
    ↓
User clicks "Login" button
    ↓
Navigation event: GET /login (includes Cookie: locale=en)
    ↓
SetLocale middleware:
  - Reads cookie: locale=en (still set!)
  - app()->setLocale('en')
    ↓
HandleInertiaRequests shares: $page.props.locale = 'en'
    ↓
ElectionHeader in Login.vue receives :locale="$page.props.locale" = 'en'
    ↓
Login page renders in English (NOT German)
```

#### Step 4: Page Refresh

```
User on login page (English)
    ↓
User presses F5 (refresh)
    ↓
Browser sends: GET /login (includes Cookie: locale=en)
    ↓
SetLocale reads cookie 'locale=en'
    ↓
Page reloads in English (language persists)
```

---

## Frontend Implementation

### ElectionHeader Component

**File:** `resources/js/Components/Header/ElectionHeader.vue`

**Purpose:** Language selector available on all pages

#### Template Structure

```vue
<template>
  <header class="bg-white shadow-xs border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
      <div class="flex justify-between items-center">
        <!-- Logo -->
        <div class="flex items-center">
          <h1 class="text-2xl font-bold">Public Digit</h1>
        </div>

        <!-- Language Selector -->
        <div class="flex items-center gap-4">
          <!-- Desktop Language Selector -->
          <div class="hidden md:block">
            <select
              :value="currentLocale"
              @change="handleLanguageChange"
              class="px-3 py-2 border border-gray-300 rounded-md"
            >
              <option value="de">🇩🇪 Deutsch</option>
              <option value="en">🇬🇧 English</option>
              <option value="np">🇳🇵 नेपाली</option>
            </select>
          </div>

          <!-- Mobile Menu Toggle -->
          <button
            @click="showMobileMenu = !showMobileMenu"
            class="md:hidden"
          >
            ☰
          </button>
        </div>
      </div>

      <!-- Mobile Language Menu -->
      <div v-if="showMobileMenu" class="md:hidden mt-4 border-t pt-4">
        <select
          :value="currentLocale"
          @change="handleLanguageChange"
          class="w-full px-3 py-2 border border-gray-300 rounded-md"
        >
          <option value="de">🇩🇪 Deutsch</option>
          <option value="en">🇬🇧 English</option>
          <option value="np">🇳🇵 नेपाली</option>
        </select>
      </div>
    </div>
  </header>
</template>
```

#### Script Implementation

```javascript
<script setup>
import { ref, watch } from 'vue';
import { usePage } from '@inertiajs/vue3-vue3';

// Props
const props = defineProps({
  locale: {
    type: String,
    default: 'de',
    validator: (value) => ['de', 'en', 'np'].includes(value)
  },
  isLoggedIn: {
    type: Boolean,
    default: false
  }
});

// Data
const currentLocale = ref(props.locale);
const showMobileMenu = ref(false);
const page = usePage();

// Watchers
watch(
  () => props.locale,
  (newLocale) => {
    currentLocale.value = newLocale;
  }
);

// Methods
const handleLanguageChange = (event) => {
  const newLocale = event.target.value;
  if (['de', 'en', 'np'].includes(newLocale)) {
    currentLocale.value = newLocale;
    switchLanguage(newLocale);
  }
};

const switchLanguage = (locale) => {
  if (this.$i18n && locale) {
    // Update Vue I18n immediately (frontend sync)
    this.$i18n.locale = locale;

    // Save to localStorage as backup
    localStorage.setItem('preferred_locale', locale);

    // Set cookie for backend
    const date = new Date();
    date.setFullYear(date.getFullYear() + 1);
    document.cookie = `locale=${locale}; expires=${date.toUTCString()}; path=/; SameSite=Lax`;

    // Reload after short delay to ensure cookie is set
    setTimeout(() => {
      window.location.reload();
    }, 100);
  }
};
</script>
```

#### Key Features

| Feature | Purpose |
|---------|---------|
| `:value="currentLocale"` | One-way binding (avoids circular reactivity) |
| `@change="handleLanguageChange"` | Manual event handling |
| `watch(props.locale)` | Sync when backend sends new locale |
| `this.$i18n.locale = locale` | Immediate frontend translation |
| `localStorage.setItem()` | Fallback persistence |
| `document.cookie` | Backend-readable persistence |
| `window.location.reload()` | Reload to apply server-side locale |

### Using ElectionHeader in Pages

**File:** `resources/js/Pages/Welcome.vue`

```vue
<template>
  <div class="min-h-screen bg-gray-50">
    <!-- CRITICAL: Pass locale prop from Laravel -->
    <ElectionHeader
      :isLoggedIn="false"
      :locale="$page.props.locale"
    />

    <!-- Rest of page content -->
    <main>
      <h1>{{ $t('pages.welcome.title') }}</h1>
      <p>{{ $t('pages.welcome.description') }}</p>
    </main>
  </div>
</template>

<script setup>
import ElectionHeader from '@/Components/Header/ElectionHeader.vue';
</script>
```

**REQUIRED in ALL pages:**
- ✅ Import ElectionHeader component
- ✅ Add `:locale="$page.props.locale"` to ElectionHeader tag
- ✅ Use `$t('key')` for all user-facing text

### Translation Key Structure

**File:** `resources/js/locales/en.json`

```json
{
  "pages": {
    "welcome": {
      "title": "Welcome to Public Digit",
      "description": "Secure digital voting platform",
      "hero": {
        "badges": [
          { "text": "Secure", "icon": "🔐" },
          { "text": "Anonymous", "icon": "👤" },
          { "text": "Transparent", "icon": "👁️" }
        ]
      }
    },
    "auth": {
      "login": {
        "heading": "Sign In",
        "fields": {
          "email": {
            "label": "Email Address",
            "placeholder": "you@example.com"
          },
          "password": {
            "label": "Password",
            "placeholder": "Enter your password"
          }
        }
      },
      "register": {
        "title": "Create Account",
        "fields": {
          "firstName": {
            "label": "First Name",
            "placeholder": "John"
          }
        }
      }
    }
  },
  "components": {
    "header": {
      "language": "Language",
      "logout": "Sign Out"
    }
  }
}
```

**Structure Rules:**
- Use dot notation: `pages.welcome.title`
- Group by feature/page
- Keep keys lowercase_with_underscores
- Maintain parallel structure across all locale files

---

## Backend Implementation

### SetLocale Middleware

**File:** `app/Http/Middleware/SetLocale.php`

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Determines the application locale with the following priority:
     * 1. Locale cookie set by frontend (when user changes language)
     * 2. Session locale (if set by previous request)
     * 3. Laravel .env APP_LOCALE setting
     * 4. Default to 'de'
     *
     * @param \Closure(Request): Response $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Priority 1: Check for locale cookie (set by frontend)
        if ($request->hasCookie('locale')) {
            $locale = $request->cookie('locale');
            if ($this->isValidLocale($locale)) {
                app()->setLocale($locale);

                // Store in session for fallback
                if ($request->hasSession()) {
                    $request->session()->put('locale', $locale);
                }

                return $next($request);
            }
        }

        // Priority 2: Check session (for returning users)
        if ($request->hasSession() && $request->session()->has('locale')) {
            $locale = $request->session()->get('locale');
            if ($this->isValidLocale($locale)) {
                app()->setLocale($locale);
                return $next($request);
            }
        }

        // Priority 3: Use .env APP_LOCALE
        $locale = config('app.locale', 'de');
        if ($this->isValidLocale($locale)) {
            app()->setLocale($locale);
        }

        return $next($request);
    }

    /**
     * Validate that the locale is supported
     *
     * @param string $locale
     * @return bool
     */
    private function isValidLocale(string $locale): bool
    {
        $supported = ['de', 'en', 'np'];
        return in_array($locale, $supported);
    }
}
```

**Key Points:**
- ✅ Validates locale before applying
- ✅ Checks `hasSession()` before accessing session (prevents errors on API routes)
- ✅ Falls back gracefully through 4 levels
- ✅ Uses private validation method

### HandleInertiaRequests Middleware

**File:** `app/Http/Middleware/HandleInertiaRequests.php`

**Critical Section:**

```php
public function share(Request $request): array
{
    return array_merge(parent::share($request), [
        // ... other shares ...

        'locale' => app()->getLocale(),  // ← SHARE LOCALE WITH VUE

        // ... other shares ...
    ]);
}
```

**Why this matters:**
- Makes `$page.props.locale` available to all Vue components
- Allows components to know the current server-side locale
- Enables ElectionHeader to initialize with correct language
- Keeps frontend and backend in sync

### Authentication Pages (Special Handling)

For pages that render **before** user authentication (Login, Register, Password Reset):

**File:** `routes/web.php`

```php
Route::middleware('guest')->group(function () {
    // These routes get locale from SetLocale middleware
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::get('/forgot-password', [AuthController::class, 'forgot'])->name('password.request');
});
```

**Each page controller:**

```php
class AuthController extends Controller
{
    public function login()
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
        // 'locale' is automatically shared by HandleInertiaRequests
    }
}
```

### Dashboard/Authenticated Routes

For pages that render **after** user authentication:

```php
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
});
```

**Important:** These pages **inherit** the locale from the ElectionHeader component. The locale selector appears in the header and applies to the entire authenticated context. No separate language selector needed in dashboard.

---

## Adding New Languages

### Step 1: Create Translation File

**File:** `resources/js/locales/fr.json`

```json
{
  "pages": {
    "welcome": {
      "title": "Bienvenue sur Public Digit",
      "description": "Plateforme de vote numérique sécurisée"
    },
    "auth": {
      "login": {
        "heading": "Se Connecter"
      }
    }
  }
}
```

### Step 2: Update Vue I18n Configuration

**File:** `resources/js/i18n.js`

```javascript
import fr from './locales/fr.json';

const i18n = createI18n({
  locale: localStorage.getItem('preferred_locale') || 'de',
  messages: {
    de,
    en,
    np,
    fr  // ← Add new language
  }
});
```

### Step 3: Update ElectionHeader Component

```vue
<select :value="currentLocale" @change="handleLanguageChange">
  <option value="de">🇩🇪 Deutsch</option>
  <option value="en">🇬🇧 English</option>
  <option value="np">🇳🇵 नेपाली</option>
  <option value="fr">🇫🇷 Français</option>  <!-- New -->
</select>
```

### Step 4: Update Backend Configuration

**File:** `.env`

```env
APP_LOCALE=de
SUPPORTED_LOCALES=de,en,np,fr
```

**File:** `config/app.php`

```php
'supported_locales' => explode(',', env('SUPPORTED_LOCALES', 'de,en,np')),
```

### Step 5: Update SetLocale Middleware

```php
private function isValidLocale(string $locale): bool
{
    $supported = config('app.supported_locales', ['de', 'en', 'np']);
    return in_array($locale, $supported);
}
```

### Step 6: Create Laravel Backup Translations

**File:** `resources/lang/fr/messages.php`

```php
return [
    'welcome' => 'Bienvenue',
    'hello' => 'Bonjour',
];
```

### Step 7: Test

```bash
# Change language in UI to French
# Verify cookie is set: locale=fr
# Refresh page
# Should display in French
```

---

## Best Practices

### ✅ DO

1. **Always pass locale prop to ElectionHeader**
   ```vue
   <ElectionHeader :locale="$page.props.locale" />
   ```

2. **Use translation keys for ALL user text**
   ```vue
   <!-- Good -->
   <h1>{{ $t('pages.welcome.title') }}</h1>

   <!-- Bad -->
   <h1>Welcome to Public Digit</h1>
   ```

3. **Group translations by page/feature**
   ```json
   {
     "pages": {
       "welcome": { ... },
       "auth": { ... }
     }
   }
   ```

4. **Maintain consistent key structure**
   ```
   ✅ pages.auth.login.heading
   ❌ auth.heading
   ❌ auth_heading
   ```

5. **Test language persistence**
   - Change language
   - Reload page
   - Navigate to different pages
   - Clear cookies and test again

6. **Use SameSite=Lax for cookies**
   ```javascript
   document.cookie = `locale=${locale}; SameSite=Lax`;
   ```

7. **Keep translations in sync across locales**
   - If you add a key to `en.json`, add it to `de.json` and `np.json`
   - Use the same structure in all files

8. **Use environment variables for config**
   ```env
   APP_LOCALE=de
   SUPPORTED_LOCALES=de,en,np
   ```

### ❌ DON'T

1. **Don't hardcode text in components**
   ```vue
   <!-- Bad -->
   <p>Welcome to our site</p>

   <!-- Good -->
   <p>{{ $t('pages.welcome.intro') }}</p>
   ```

2. **Don't use v-model for language selector**
   ```javascript
   // Bad - causes circular reactivity
   v-model="currentLocale"

   // Good - one-way binding
   :value="currentLocale"
   @change="handleLanguageChange"
   ```

3. **Don't forget to include SetLocale middleware**
   - Missing middleware = locale resets on navigation
   - Check Kernel.php order if experiencing issues

4. **Don't set locale before cookies are decrypted**
   ```php
   // Bad - SetLocale in global middleware (before EncryptCookies)
   protected $middleware = [
       ...
       SetLocale::class,  // TOO EARLY
   ];

   // Good - SetLocale in web group (after EncryptCookies)
   protected $middlewareGroups = [
       'web' => [
           EncryptCookies::class,
           ...
           SetLocale::class,  // CORRECT POSITION
           HandleInertiaRequests::class,
       ];
   ];
   ```

5. **Don't use global helpers for locale in components**
   ```javascript
   // Bad
   const locale = app().getLocale();

   // Good
   const locale = props.locale;
   ```

6. **Don't forget to share locale in Inertia responses**
   ```php
   // Bad - locale not available in Vue
   return Inertia::render('MyPage', [
       'data' => $data
   ]);

   // Good - HandleInertiaRequests automatically shares it
   // through the share() method
   ```

7. **Don't trust user input for locale**
   ```php
   // Bad - no validation
   app()->setLocale($request->get('lang'));

   // Good - validate first
   if (in_array($locale, ['de', 'en', 'np'])) {
       app()->setLocale($locale);
   }
   ```

---

## Troubleshooting

### Issue 1: Language Resets to German After Navigation

**Symptoms:**
- Change to English on home page ✅
- Navigate to login page → German ❌

**Root Causes & Solutions:**

```
CAUSE 1: SetLocale middleware not in correct position
├─ Check: app/Http/Kernel.php line 40
├─ Must be: After EncryptCookies (line 33), Before HandleInertiaRequests
├─ Fix: Move to web middleware group
└─ Verify:
   grep -n "SetLocale\|EncryptCookies\|HandleInertia" app/Http/Kernel.php

CAUSE 2: Cookie not being set
├─ Check: Open browser DevTools (F12) → Application tab
├─ Look for: Cookie named 'locale' with value 'en'
├─ If missing: ElectionHeader.switchLanguage() not executing
├─ Fix: Check browser console for errors
└─ Verify:
   document.cookie; // Should include locale=en
   document.cookie.match(/locale=([^;]+)/)?.[1]; // Should be 'en'

CAUSE 3: SetLocale not reading cookie
├─ Check: Laravel log file (storage/logs/laravel.log)
├─ Add: Debug logging to SetLocale.php
├─ Verify: $request->hasCookie('locale') returns true
└─ Fix:
   public function handle(Request $request, Closure $next): Response
   {
       \Log::debug('SetLocale - Cookie check', [
           'hasCookie' => $request->hasCookie('locale'),
           'cookieValue' => $request->cookie('locale'),
       ]);
   }

CAUSE 4: ElectionHeader not receiving locale prop
├─ Check: Does your page component have <ElectionHeader>?
├─ Verify: Does it include :locale="$page.props.locale"?
├─ If missing: Page always uses component default (usually 'de')
└─ Fix:
   <!-- Before -->
   <ElectionHeader :isLoggedIn="true" />

   <!-- After -->
   <ElectionHeader :isLoggedIn="true" :locale="$page.props.locale" />
```

### Issue 2: Locale Shows Correct on First Page, Wrong on Reload

**Symptoms:**
- Home page: English ✅
- Press F5 (refresh): German ❌

**Debugging:**

```bash
# 1. Check .env default
grep APP_LOCALE .env

# 2. Check if SetLocale exists
ls app/Http/Middleware/SetLocale.php

# 3. Check if cookie is persistent
# In browser console after changing to English:
console.log(document.cookie);

# 4. Check Laravel configuration
grep -n "locale" config/app.php

# 5. Clear cache and try again
php artisan cache:clear
php artisan config:clear
```

### Issue 3: Language Options Not Appearing in Selector

**Symptoms:**
- ElectionHeader component renders
- Language dropdown is empty

**Solution:**

```vue
<!-- Check ElectionHeader select element -->
<select>
  <option value="de">🇩🇪 Deutsch</option>    <!-- These must be here -->
  <option value="en">🇬🇧 English</option>
  <option value="np">🇳🇵 नेपाली</option>
</select>

<!-- If not showing, check: -->
<!-- 1. Component is imported -->
<script setup>
import ElectionHeader from '@/Components/Header/ElectionHeader.vue';
</script>

<!-- 2. Component is used in template -->
<ElectionHeader :locale="$page.props.locale" />

<!-- 3. No console errors in F12 DevTools -->
```

### Issue 4: Translations Show Keys Instead of Text

**Symptoms:**
- Page displays: `pages.welcome.title` instead of "Welcome to Public Digit"

**Causes & Fixes:**

```
CAUSE 1: Translation key doesn't exist
├─ Check: resources/js/locales/en.json
├─ Verify: Key 'pages.welcome.title' exists
├─ If missing: Add it to all locale files
└─ Fix:
   {
     "pages": {
       "welcome": {
         "title": "Welcome to Public Digit"  ← Add this
       }
     }
   }

CAUSE 2: Vue I18n not installed
├─ Check: resources/js/i18n.js exists
├─ Verify: It's imported in resources/js/app.js
└─ Fix:
   // In app.js
   import i18n from './i18n';
   createApp(App)
     .use(i18n)
     .mount('#app');

CAUSE 3: Wrong locale being used
├─ Check: app()->getLocale() in Laravel
├─ Verify: $page.props.locale in Vue
├─ Debug in browser console:
   this.$page.props.locale
   this.$i18n.locale
   this.$t('test.key')
```

### Issue 5: Cookie Not Persisting

**Symptoms:**
- Set language to English
- Cookie shows in DevTools
- After page reload: gone

**Debug Steps:**

```bash
# 1. Check cookie attributes
# DevTools → Application → Cookies → localhost:8000
# Look for:
#   - Name: locale
#   - Value: en
#   - Domain: localhost
#   - Path: /
#   - Expires: (future date)
#   - SameSite: Lax

# 2. Check SetLocale reads it
grep "hasCookie\|cookie" app/Http/Middleware/SetLocale.php

# 3. Test with curl (backend test)
curl -H "Cookie: locale=en" http://localhost:8000/login -v

# 4. Check for cookie-clearing code
grep -r "Cookie\|cookie\|setcookie" app/ --include="*.php"
```

### Issue 6: Circular Reactivity Errors in Console

**Symptoms:**
- Console error: "Too much recursion" or "Maximum update depth exceeded"
- Language selector stops working

**Solution:**

```vue
<!-- Bad - Causes circular reactivity -->
<select v-model="currentLocale" @change="handleLanguageChange">

<!-- Good - One-way binding with manual handler -->
<select :value="currentLocale" @change="handleLanguageChange">

<!-- The script: -->
<script setup>
const currentLocale = ref(props.locale);

// Watcher to sync when prop changes
watch(
  () => props.locale,
  (newLocale) => {
    currentLocale.value = newLocale;
  }
);

// Manual handler to avoid recursion
const handleLanguageChange = (event) => {
  const newLocale = event.target.value;
  switchLanguage(newLocale);
};
</script>
```

### Diagnostic Checklist

Use this when experiencing ANY locale issue:

```
[ ] 1. Cookie exists in browser
    → DevTools > Application > Cookies > Check 'locale' key exists

[ ] 2. Cookie value is correct
    → Should be one of: de, en, np

[ ] 3. Cookie has correct attributes
    → Domain: localhost (or your domain)
    → Path: /
    → Expires: future date
    → SameSite: Lax

[ ] 4. SetLocale middleware exists
    → php artisan tinker
    → app('\\App\\Http\\Middleware\\SetLocale')

[ ] 5. SetLocale is in correct middleware group
    → grep -A 10 "'web'" app/Http/Kernel.php
    → SetLocale should be between EncryptCookies and HandleInertiaRequests

[ ] 6. All pages have ElectionHeader
    → grep -r "ElectionHeader" resources/js/Pages/
    → All should include :locale="$page.props.locale"

[ ] 7. Vue I18n is configured
    → Check resources/js/i18n.js exists
    → Check it imports locale JSON files
    → Check app.js imports and uses i18n

[ ] 8. Locale is shared by Inertia
    → Check HandleInertiaRequests.php
    → Look for: 'locale' => app()->getLocale()

[ ] 9. No translation key errors
    → Check resources/js/locales/*.json
    → All keys exist in all three files

[ ] 10. Caches are cleared
     → php artisan cache:clear
     → php artisan config:clear
     → npm run dev (if using Vite)
```

---

## Testing Language Persistence

### Automated Test Example

**File:** `tests/Feature/LocalizationTest.php`

```php
<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocalizationTest extends TestCase
{
    public function test_locale_persists_via_cookie()
    {
        // First request - set German (default)
        $response = $this->get('/');
        $this->assertResponseOk();

        // Change to English via cookie
        $response = $this->withCookie('locale', 'en')
            ->get('/login');

        // Assert response includes English locale
        $this->assertInertiaHas('locale', 'en');
    }

    public function test_locale_falls_back_to_default()
    {
        // No locale set
        $response = $this->get('/');

        // Should default to 'de'
        $this->assertInertiaHas('locale', 'de');
    }

    public function test_invalid_locale_ignored()
    {
        // Try to set invalid locale
        $response = $this->withCookie('locale', 'invalid')
            ->get('/');

        // Should fall back to default
        $this->assertInertiaHas('locale', 'de');
    }
}
```

### Manual Testing Checklist

```
1. Test Initial Page Load
   [ ] Visit http://localhost:8000/
   [ ] Should display in German (default)
   [ ] Check DevTools: $page.props.locale = 'de'

2. Test Language Change
   [ ] Click language selector
   [ ] Choose English
   [ ] Page reloads
   [ ] All text in English
   [ ] Cookie shows: locale=en

3. Test Navigation Persistence
   [ ] On English page, click Login
   [ ] Login page also in English
   [ ] Navigate to Register - still English

4. Test Page Refresh
   [ ] On English page, press F5
   [ ] Page reloads in English
   [ ] Language persists

5. Test Cookie Expiry
   [ ] Set language to English
   [ ] Check cookie expiry date
   [ ] Should be ~1 year from now
   [ ] Close and reopen browser
   [ ] Cookie still there, language still English

6. Test Fallback
   [ ] Manually delete locale cookie
   [ ] Refresh page
   [ ] Should fall back to German (default)

7. Test Multiple Languages
   [ ] Change between de, en, np
   [ ] Each should persist correctly
   [ ] Translations should be complete for each
```

---

## Summary

### Key Takeaways

| Aspect | Critical Detail |
|--------|-----------------|
| **Frontend** | ElectionHeader selector + Vue I18n |
| **Backend** | SetLocale middleware reads cookie |
| **Sync** | Inertia.js shares locale prop |
| **Persistence** | HTTP Cookie (1 year) |
| **Fallback Chain** | Cookie → Session → Config → Default |
| **Middleware Order** | SetLocale AFTER EncryptCookies, BEFORE Inertia |
| **All Pages** | Must receive :locale="$page.props.locale" |
| **Valid Locales** | 'de' (German), 'en' (English), 'np' (Nepali) |

### Quick Reference

**If language resets on navigation:**
```bash
1. Check Kernel.php middleware order
2. Verify cookie is being set (DevTools)
3. Check SetLocale middleware exists
4. Verify all pages have :locale prop
```

**If translations show keys instead of text:**
```bash
1. Check resource/js/locales/*.json have the keys
2. Verify Vue I18n is installed (resources/js/i18n.js)
3. Check Vue I18n is used in app.js
```

**If language won't change:**
```bash
1. Check ElectionHeader @change handler fires
2. Check document.cookie is being set
3. Check switchLanguage() method executes
4. Clear browser cache and try again
```

---

## Additional Resources

- [Vue I18n Documentation](https://vue-i18n.intlify.dev/)
- [Laravel Localization](https://laravel.com/docs/localization)
- [Inertia.js Props](https://inertiajs.com/responses#props)
- [HTTP Cookies (MDN)](https://developer.mozilla.org/en-US/docs/Web/HTTP/Cookies)
- [Laravel Middleware](https://laravel.com/docs/middleware)

---

**Document Version:** 1.0
**Last Updated:** February 2026
**Author:** Public Digit Architecture Team

---

# Multilingual SEO Architecture

**Date:** 2026-03-15
**Branch:** `multitenancy`
**Author:** Implementation via Claude Code

---

## Overview

Public Digit serves three locales — **German (`de`)**, **English (`en`)**, and **Nepali (`np`)** — and requires that search engine crawlers receive the correct language meta tags in the **initial HTML response**, before any JavaScript executes.

This document covers the complete server-side multilingual SEO pipeline implemented on 2026-03-15.

---

## The Problem This Solves

| Approach | Crawler sees correct language? | User sees correct language? |
|---|---|---|
| Client-side only (`useMeta.js`) | ❌ No — JS runs after crawl | ✅ Yes |
| Server-side only (static config) | ❌ No — always one language | ✅ Partial |
| **This implementation** | ✅ **Yes — in initial HTML** | ✅ **Yes** |

Search engine bots (Googlebot, Bingbot, etc.) and social media link scrapers (Twitter, Facebook, LinkedIn) do not execute JavaScript. They read the raw HTML response. If the `<title>` and `<meta>` tags are in English when the page is in German, the indexed content is wrong.

---

## Architecture Diagram

```
Browser / Crawler
      │
      │  GET / with Cookie: locale=de
      ▼
┌─────────────────────────────────────────────────────────┐
│                  Laravel Web Middleware Stack             │
│                                                          │
│  1. EncryptCookies   (skips 'locale' cookie)             │
│  2. StartSession                                         │
│  3. SetLocale        ← reads $_COOKIE['locale']          │
│                        sets app()->setLocale('de')       │
│  4. InjectPageMeta   ← calls SeoService::getMeta('home') │
│                        locale is now 'de'                │
│                        shares $meta with:                │
│                          • Inertia::share('meta', ...)   │
│                          • View::share('serverMeta', ...) │
│  5. HandleInertiaRequests                                │
└─────────────────────────────────────────────────────────┘
      │
      │  PHP renders app.blade.php
      ▼
┌─────────────────────────────────────────────────────────┐
│  app.blade.php                                           │
│    <title>{{ $serverMeta['title'] }}</title>             │
│    @include('meta.meta-info')  ← uses $serverMeta        │
│                                                          │
│  Output (German):                                        │
│    <title>Sichere Online-Wahlen | Public Digit</title>   │
│    <meta name="description" content="Ermöglichen...">    │
│    <meta property="og:locale" content="de_DE">           │
└─────────────────────────────────────────────────────────┘
      │
      │  Vue/Inertia boots with data-page JSON
      ▼
┌─────────────────────────────────────────────────────────┐
│  app.js — sets i18n locale from page.props.locale        │
│    i18n.global.locale.value = 'de'                       │
│                                                          │
│  MetaTags.vue reads page.props.meta (also German)        │
│  useMeta.js reads from Vue i18n de.json (also German)    │
└─────────────────────────────────────────────────────────┘
```

---

## Components

### 1. Locale Detection — `SetLocale` Middleware

**File:** `app/Http/Middleware/SetLocale.php`

Detects the user's locale in priority order:

1. **`$_COOKIE['locale']`** — plain cookie set by JavaScript when the user clicks the language switcher. Read directly from the PHP superglobal, **not** via `$request->cookie()`, because Laravel's encryption pipeline nullifies unencrypted cookies even when they are listed in `EncryptCookies::$except`.
2. **Session** — locale stored in a previous request.
3. **Config fallback** — `config('app.locale')`, defaults to `'de'`.

```php
// Correct — reads raw cookie bypassing Laravel's pipeline
$cookieLocale = $_COOKIE['locale'] ?? null;

// Wrong — returns null for JS-set plain cookies
$cookieLocale = $request->cookie('locale');
```

The `locale` cookie must remain in `EncryptCookies::$except`:

```php
// app/Http/Middleware/EncryptCookies.php
protected $except = [
    'locale',
];
```

**Frontend cookie write** (in `PublicDigitHeader.vue`):
```javascript
document.cookie = `locale=${newLocale}; expires=${date.toUTCString()}; path=/`;
localStorage.setItem('preferred_locale', newLocale);
```

---

### 2. Meta Generation — `SeoService::getMeta()`

**File:** `app/Services/SeoService.php`

Instance method that builds the full meta array for a given page and the current locale.

```php
public function getMeta(string $page = 'home', array $overrides = [], bool $skipCache = false): array
```

**Returns:**
```php
[
    'title'       => 'Sichere Online-Wahlen | Public Digit Elections',
    'description' => 'Ermöglichen Sie Ihrer Organisation...',
    'keywords'    => 'Online-Wahlen, digitale Abstimmungen...',
    'robots'      => 'index, follow',
    'canonical'   => 'https://publicdigit.com',
    'og'          => [
        'type'        => 'website',
        'url'         => 'https://publicdigit.com',
        'title'       => '...',
        'description' => '...',
        'image'       => 'https://publicdigit.com/images/og-home.png',
        'width'       => 1200,
        'height'      => 630,
        'alt'         => 'Public Digit — Secure Online Voting Platform',
        'site_name'   => 'Public Digit',
        'locale'      => 'de_DE',   // ← locale-aware OG locale
    ],
    'twitter'     => [ 'card' => 'summary_large_image', ... ],
    'alternates'  => [ 'de' => '...', 'en' => '...', 'np' => '...', 'x-default' => '...' ],
    'json_ld'     => [
        'website'      => [ '@context' => 'https://schema.org', '@type' => 'WebSite', ... ],
        'organization' => [ '@context' => 'https://schema.org', '@type' => 'Organization', ... ],
    ],
]
```

**Translation source:** `resources/lang/{locale}/seo.php`

```php
// resources/lang/de/seo.php
return [
    'site' => [
        'title'       => 'Public Digit',
        'description' => 'Sichere digitale Wahlplattform...',
    ],
    'pages' => [
        'home' => [
            'title'       => 'Sichere Online-Wahlen | Public Digit Elections',
            'description' => 'Ermöglichen Sie Ihrer Organisation...',
            'keywords'    => 'Online-Wahlen, digitale Abstimmungen...',
            'robots'      => 'index, follow',
        ],
        'dashboard' => [
            'robots' => 'noindex, nofollow',   // ← authenticated pages
            ...
        ],
        // login, register, about, faq, security, demo, profile, pricing,
        // elections.index, elections.show, organisations.show, election.result
    ],
];
```

**Caching:** Results are cached per `{locale}:{page}` key (default TTL: 3600 s). Controlled via `.env`:

```
META_CACHE_ENABLED=true
META_CACHE_TTL=3600
```

**Fallback chain** when a page key is missing:
1. `trans("seo.pages.{$page}.title")` — page-specific translation
2. `trans('seo.site.title')` — site default translation
3. `config('meta.title')` — static config fallback

---

### 3. Middleware Injection — `InjectPageMeta`

**File:** `app/Http/Middleware/InjectPageMeta.php`

Maps the current route name to a page key, calls `SeoService::getMeta()`, then shares the result with **both** Inertia and Blade:

```php
$meta = $this->seoService->getMeta($page);

// For Vue (page.props.meta)
Inertia::share('meta', $meta);

// For Blade (server-rendered HTML)
View::share('serverMeta', $meta);
```

**Route → page key mapping:**

| Route name(s) | Page key |
|---|---|
| `welcome`, `home` | `home` |
| `about` | `about` |
| `faq` | `faq` |
| `security` | `security` |
| `pricing` | `pricing` |
| `login` | `login` |
| `register` | `register` |
| `profile.show` | `profile` |
| `dashboard`, `dashboard.welcome` | `dashboard` |
| `elections.index` | `elections.index` |
| `elections.show` | `elections.show` |
| `organisations.show` | `organisations.show` |
| `election.result`, `demo.result` | `election.result` |
| *(anything else)* | `home` (+ debug log) |

**Middleware order** (in `bootstrap/app.php`):
```php
$middleware->web(append: [
    \App\Http\Middleware\SetLocale::class,        // 1. detect locale
    \App\Http\Middleware\InjectPageMeta::class,   // 2. build meta in detected locale
    \App\Http\Middleware\HandleInertiaRequests::class,
]);
```

Order is critical — `SetLocale` must run before `InjectPageMeta` so `app()->getLocale()` returns the correct locale when `getMeta()` is called.

---

### 4. Blade Output — `meta-info.blade.php`

**File:** `resources/views/meta/meta-info.blade.php`

Reads `$serverMeta` (set by `InjectPageMeta` via `View::share`) and writes all meta tags into the initial HTML:

```blade
@php
    $m = $serverMeta ?? null;
    $metaTitle = $m['title'] ?? config('meta.title');
    // ... etc
@endphp

<title>{{ $metaTitle }}</title>
<meta name="description" content="{{ $metaDescription }}">
<meta property="og:title" content="{{ $ogTitle }}">
<meta property="og:locale" content="{{ $ogLocale }}">
...

@foreach($m['alternates'] as $loc => $url)
<link rel="alternate" hreflang="{{ $loc }}" href="{{ $url }}">
@endforeach

@foreach($m['json_ld'] as $schema)
<script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endforeach
```

This is what crawlers read. No JavaScript needed.

---

### 5. Vue Side — `MetaTags.vue` + `app.js`

**File:** `resources/js/Components/SEO/MetaTags.vue`

Reads `page.props.meta` and writes it into `<Head>` for SPA navigation (Inertia page transitions after the initial load):

```vue
<script setup>
const m = computed(() => usePage().props.meta ?? {})
const title = computed(() => props.title ?? m.value.title ?? null)
// ...
</script>

<template>
    <Head>
        <title v-if="title">{{ title }}</title>
        <meta v-if="description" name="description" :content="description">
        <meta v-if="og.locale" property="og:locale" :content="og.locale">
        <link v-for="[loc, url] in alternates" :key="loc" rel="alternate" :hreflang="loc" :href="url">
        <component v-for="(schema, i) in jsonLds" :key="i" :is="'script'"
            type="application/ld+json" v-text="JSON.stringify(schema)" />
    </Head>
</template>
```

**File:** `resources/js/app.js`

Sets the i18n locale **before** Vue mounts, from the server-provided `page.props.locale`:

```javascript
setup({ el, App, props, plugin }) {
    // Must happen before mount — i18n is already created at import time
    const serverLocale = props.initialPage.props.locale;
    if (serverLocale && ['de', 'en', 'np'].includes(serverLocale)) {
        i18n.global.locale.value = serverLocale;
    }
    app.use(plugin).use(i18n).use(ZiggyVue).mount(el);
}
```

> **Why not `window.__initialLocale`?** `i18n.js` is imported at the top of `app.js` and executes immediately — `getInitialLocale()` runs before `setup()` fires, so `window.__initialLocale` is always undefined at that point. Setting `i18n.global.locale.value` inside `setup()` (before `mount()`) is the correct approach.

---

## Translation Files

### PHP (server-side — used by `SeoService`)

**Location:** `resources/lang/{locale}/seo.php`

All three locale files must have the same page keys. Current pages:

```
home, login, register, about, faq, security, pricing, demo,
dashboard, profile, elections.index, elections.show,
organisations.show, election.result
```

Adding a new page:
1. Add the page key to all three lang files (`en/seo.php`, `de/seo.php`, `np/seo.php`)
2. Add the route → key mapping in `InjectPageMeta::handle()`
3. Clear the cache: `php artisan cache:clear`

### JSON (client-side — used by `useMeta.js`)

**Location:** `resources/js/locales/{locale}.json`

Key path: `seo.pages.{pageKey}.title` / `.description` / `.keywords`

These are read by the `useMeta` composable for **client-side** SPA navigation (after the initial page load). They do not affect the server-rendered HTML.

---

## Configuration

### `config/meta.php`

```php
// OG locale mapping (server-side og:locale meta tag)
'og_locales' => [
    'de' => 'de_DE',
    'en' => 'en_US',
    'np' => 'ne_NP',
],

// All supported locales (used to build hreflang alternates)
'supported_locales' => ['de', 'en', 'np'],

// Cache settings
'cache' => [
    'enabled'    => env('META_CACHE_ENABLED', true),
    'ttl'        => env('META_CACHE_TTL', 3600),
    'key_prefix' => 'meta:',
],

// Performance monitoring
'performance' => [
    'log_slow_generation' => env('LOG_SLOW_META', false),
    'slow_threshold_ms'   => env('META_SLOW_THRESHOLD', 200),
],
```

### `.env`

```
META_CACHE_ENABLED=true
META_CACHE_TTL=3600
LOG_SLOW_META=false
META_SLOW_THRESHOLD=200
```

---

## Adding a New Language

1. **Create PHP lang file:** `resources/lang/{code}/seo.php` — copy `en/seo.php` and translate all values.

2. **Create JSON locale file:** `resources/js/locales/{code}.json` — copy `en.json` and translate.

3. **Register locale in three places:**

   ```php
   // config/meta.php
   'supported_locales' => ['de', 'en', 'np', 'xx'],
   'og_locales'        => [..., 'xx' => 'xx_XX'],
   ```

   ```php
   // app/Http/Middleware/SetLocale.php
   private function isValidLocale(string $locale): bool
   {
       return in_array($locale, ['de', 'en', 'np', 'xx']);
   }
   ```

   ```javascript
   // resources/js/app.js
   if (serverLocale && ['de', 'en', 'np', 'xx'].includes(serverLocale)) {
   ```

   ```javascript
   // resources/js/i18n.js  — import and register messages for 'xx'
   ```

4. Clear caches:
   ```bash
   php artisan cache:clear
   php artisan config:clear
   ```

---

## Adding SEO for a New Page

1. **Add translation keys** to all three PHP lang files:

   ```php
   // resources/lang/de/seo.php
   'my-new-page' => [
       'title'       => 'Mein Titel | Public Digit',
       'description' => 'Beschreibung der Seite...',
       'keywords'    => 'schlüsselwort1, schlüsselwort2',
       'robots'      => 'index, follow',
   ],
   ```

2. **Add route mapping** in `InjectPageMeta`:

   ```php
   $routeName === 'my.route.name' => 'my-new-page',
   ```

3. **Clear cache:**
   ```bash
   php artisan cache:clear
   ```

4. **Verify:**
   ```bash
   curl -s -H "Cookie: locale=de" http://localhost:8000/my-page | grep "<title>"
   ```

---

## Debugging

### Test locale switching with curl

```bash
# German
curl -s -H "Cookie: locale=de" http://localhost:8000 | grep -E "<title>|og:locale"

# English
curl -s -H "Cookie: locale=en" http://localhost:8000 | grep -E "<title>|og:locale"

# Nepali
curl -s -H "Cookie: locale=np" http://localhost:8000 | grep -E "<title>|og:locale"
```

### Check a translation is loading

```bash
php artisan tinker --execute="
app()->setLocale('de');
echo trans('seo.pages.home.title');
"
```

### Clear all relevant caches

```bash
php artisan cache:clear    # clears meta cache (Redis/file)
php artisan config:clear   # clears config cache
php artisan view:clear     # clears compiled blade views
```

### Run unit tests

```bash
php artisan test tests/Unit/Services/SeoServiceTest.php
```

Tests cover: required keys, caching, fallback on unknown page, title override, noindex for dashboard, hreflang alternates, JSON-LD schema.

---

## Known Limitations

| Limitation | Notes |
|---|---|
| Hreflang URLs are identical for all locales | The app does not use locale URL prefixes (`/de/`, `/en/`). All hreflang links point to the same URL. This is valid but not ideal. |
| Cache is per locale+page | If a page has dynamic content (e.g. election name in title), cache must be disabled or TTL kept short for that page. Use `getMeta($page, ['title' => $dynamic], skipCache: true)`. |
| `useMeta.js` and `SeoService` are separate | Server-side uses PHP lang files; client-side uses Vue i18n JSON. Both must be kept in sync. |
| Cookie set by JS, not Laravel | The locale cookie bypasses Laravel's signed cookie system. It cannot be trusted for security decisions — only for locale preference. |

---

## File Index

| File | Purpose |
|---|---|
| `app/Http/Middleware/SetLocale.php` | Detects locale from cookie / session / config |
| `app/Http/Middleware/InjectPageMeta.php` | Maps route → page key, shares meta with Inertia + Blade |
| `app/Http/Middleware/EncryptCookies.php` | Excludes `locale` cookie from encryption |
| `app/Services/SeoService.php` | Builds locale-aware meta array, caches results |
| `config/meta.php` | Site-wide SEO config (og, twitter, cache, locales) |
| `resources/lang/de/seo.php` | German page-level SEO translations |
| `resources/lang/en/seo.php` | English page-level SEO translations |
| `resources/lang/np/seo.php` | Nepali page-level SEO translations |
| `resources/views/app.blade.php` | Root blade template — `<title>` from `$serverMeta` |
| `resources/views/meta/meta-info.blade.php` | Outputs all meta tags from `$serverMeta` |
| `resources/js/app.js` | Sets i18n locale from `page.props.locale` before mount |
| `resources/js/Components/SEO/MetaTags.vue` | Vue `<Head>` component reading `page.props.meta` |
| `resources/js/composables/useMeta.js` | Client-side meta composable (reads Vue i18n JSON) |
| `tests/Unit/Services/SeoServiceTest.php` | Unit tests for `SeoService::getMeta()` |

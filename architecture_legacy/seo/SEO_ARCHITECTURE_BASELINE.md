# SEO Architecture Baseline

**Date:** 2026-06-14  
**Status:** Revised — reflects existing dual-source multilingual architecture  
**Reference:** `developer_guide/seo/MULTILINGUAL_SEO_ARCHITECTURE.md`  
**Architectural Principle:** APR-SEO-01 — Translation-First SEO

## Authority Chain

```
Translation Files (PHP lang + Vue i18n JSON)
        ↓
SeoService::getMeta()  (locale-aware, cached)
        ↓
InjectPageMeta middleware → shares with Inertia + Blade
        ↓
Server: meta-info.blade.php (crawler-safe, no JS needed)
Client: MetaTags.vue + useMeta.js (SPA navigation)
```

## Component Ownership

| Component | Location | Role | Authority |
|-----------|----------|------|-----------|
| **Translation Catalog** | `resources/lang/{de,en,np}/seo.php` + `resources/js/locales/*.json` | Source of truth for all SEO text | ✅ **Source of Truth** |
| `SeoService::getMeta()` | `app/Services/SeoService.php` | Builds locale-aware meta array with fallback chain. Cached per `{locale}:{page}`. | **Meta Builder** |
| `SetLocale` middleware | `app/Http/Middleware/SetLocale.php` | Detects locale from cookie/session/config. Must run before `InjectPageMeta`. | **Locale Provider** |
| `InjectPageMeta` middleware | `app/Http/Middleware/InjectPageMeta.php` | Maps route → page key. Calls `SeoService::getMeta()`. Shares with Inertia (`meta` prop) + Blade (`serverMeta`). | **SEO Provider** |
| `meta-info.blade.php` | `resources/views/meta/meta-info.blade.php` | Server-side. Renders title, OG, Twitter, hreflang, JSON-LD from `$serverMeta`. Visible to all crawlers without JS. | **Server Renderer** |
| `useMeta` composable | `resources/js/composables/useMeta.js` | Client-side. Reads from Vue i18n JSON. Updates `<Head>` during SPA navigation. | **Client Renderer** |
| `MetaTags.vue` | `resources/js/Components/SEO/MetaTags.vue` | Vue component reading `page.props.meta`. Used for Inertia `<Head>` during SPA transitions. | **Client Component** |
| `config/meta.php` | `config/meta.php` | Default values, OG locale mapping, supported locales, cache TTL, hreflang config. | **Configuration** |
| `SitemapController` | `app/Http/Controllers/SitemapController.php` | Generates XML sitemap. Missing some static pages (see baseline audit). | **Crawl authority** |
| `RobotsController` | `app/Http/Controllers/RobotsController.php` | Generates robots.txt. | **Index authority** |
| JSON-LD | Server-side via `SeoService::getMeta()` | Organization + WebSite schemas already injected in `meta-info.blade.php`. Additional page schemas pending. | **Partially implemented** |

## Data Flow

```
Browser/Crawler → GET /page
        ↓
SetLocale middleware (reads cookie, sets app locale)
        ↓
InjectPageMeta middleware → SeoService::getMeta('page_key')
                ↓
         ┌── SeoService reads: resources/lang/{locale}/seo.php
         │   Falls back: site defaults → config('meta.*')
         │   Caches: meta:{locale}:{page_key}
         │   Returns: title, description, OG, Twitter, hreflang, JSON-LD
         │
         ├──→ View::share('serverMeta', ...) → meta-info.blade.php
         │       Renders <title>, <meta>, <link hreflang>, <script ld+json>
         │       NO JavaScript required — crawler-safe
         │
         └──→ Inertia::share('meta', ...) → page.props.meta
                 → MetaTags.vue reads page.props.meta for SPA <Head>
                 → useMeta.js reads Vue i18n JSON for dynamic updates
```

## Known Gaps Violating This Architecture

| Page | Current Mechanism | Violation |
|------|------------------|-----------|
| `ElectionArchitecture.vue` | Standalone `<Head>` with i18n keys | Bypasses `SeoService`, `InjectPageMeta`, `meta-info.blade.php` |
| `ElectionSecurity.vue` | Standalone `<Head>` with **hardcoded English** | No i18n, hardcoded text violates Translation-First rule |
| `VotingSecurity.vue` | `useMeta()` + manual DOM script overwriting OG/Twitter | Manual DOM creates duplicate conflicting tags |
| `Header/Welcome.vue` | Duplicate `useMeta({ pageKey: 'home' })` call | Component owns SEO — should be page-scoped only |

## Rules

1. **Every public page uses `useMeta` or `MetaTags.vue`.** No standalone `<Head>` blocks. No manual DOM manipulation for OG/Twitter tags.
2. **One page = one `useMeta` call.** Components must not own SEO. Only page-level `.vue` files in `Pages/`.
3. **All SEO text must be translatable.** No hardcoded text. Sources: `resources/lang/*/seo.php` (PHP) or `resources/js/locales/*.json` (Vue).
4. **`meta-info.blade.php` remains the server-side authority.** It provides crawler-safe localized tags without JS.
5. **Sitemap includes all public routes.** Static pages must be listed with appropriate change frequency.
6. **SEO ownership is page-scoped.** Reusable components must never create, modify, or remove SEO metadata.
7. **Adding a language means adding translation files only.** No code changes required.

## Language Expansion Rule

**Principle:** Adding a language to the platform requires translation files only — no code changes.

### Required Additions

| Artifact | Location | Example |
|----------|----------|---------|
| PHP lang file | `resources/lang/{locale}/seo.php` | `resources/lang/fr/seo.php` |
| Vue i18n JSON | `resources/js/locales/{locale}.json` | `resources/js/locales/fr.json` |
| Hreflang registration | `config/meta.php` → `supported_locales` array | `'fr'` in array |
| OG locale mapping | `config/meta.php` → `og_locales` array | `'fr' => 'fr_FR'` |
| Sitemap inclusion | Automatic via `SitemapController` | Route entries already locale-agnostic |

### What Doesn't Change

- No new routes or controllers
- No middleware changes
- No Vue component changes
- No database migrations
- No sitemap structure changes

### Implementation Sequence

1. Add translation files (PHP + JSON) for the new locale
2. Register locale code in `config/meta.php` (`supported_locales` + `og_locales`)
3. Verify via browser: navigate to every public page in the new locale
4. Verify via crawler: `curl -H "Accept-Language: fr" https://example.com/` returns French meta tags

---

## Locale Governance: `np` vs `ne`

### Decision: Legacy Compatibility Decision

The current platform uses **`np`** as the internal locale code for Nepali. This is a **legacy compatibility decision**, not a canonical best practice.

ISO standards specify:
- Language code: **`ne`** (ISO 639-1)
- Country code: **`NP`** (ISO 3166-1 alpha-2)
- Full locale: **`ne-NP`**

The platform's use of `np` predates this architectural baseline and is deeply embedded across layers. A migration to `ne` would provide standards compliance but carries **significant risk** with no immediate user-facing benefit.

### Locale Code Map

| Layer | Current Code | ISO Standard | Migration Viability |
|-------|-------------|--------------|-------------------|
| Internal code | `np` | `ne` | ❌ High risk — embedded everywhere |
| PHP lang directory | `np` (`resources/lang/np/seo.php`) | `ne` | ❌ Would break all lang lookups |
| Vue i18n files | `np` (`resources/js/locales/np.json`) | `ne` | ❌ Would break all imports |
| HTML `lang` attribute | `ne` | `ne` | ✅ Already compliant |
| OG locale (Facebook) | `ne_NP` | `ne_NP` | ✅ Already compliant |
| JS Intl locale | `ne-NP` | `ne-NP` | ✅ Already compliant |
| Cookie / session | `np` | `ne` | ⚠️ Possible but no current need |

### Audit: `ne` References

| Location | Reference | Verdict |
|----------|-----------|---------|
| `config/meta.php:54` | `'np' => 'ne_NP'` | ✅ Correct — maps internal `np` to standard OG locale |
| Compiled JS `app.js` | `locale === 'np' ? 'ne-NP' : ...` | ✅ Correct — maps to JS Intl format |
| `developer_guide/PHASE_3_READY.md:168` | `ne.json` (TODO mention) | ⚠️ Stale reference — actual file is `np.json`. Should be updated when that doc is next revised. |

**Verdict:** No systemic `ne`/`np` inconsistency. One stale comment in a development guide that poses no runtime risk.

### Compatibility Rules

**Use `np` in these contexts (non-negotiable — legacy compatibility):**
- Directory names: `resources/lang/np/`
- File names: `np.json`, `common/np.json`
- Middleware validation arrays: `['de', 'en', 'np']`
- Route or controller logic
- Cookie and session values

**Use `ne` in these contexts (ISO standard — no change needed):**
- HTML `lang` attribute: `<html lang="ne">`
- OG locale: `og:locale: ne_NP`
- JS `Intl` API calls: `Intl.DateTimeFormat('ne-NP')`

**Future recommendation:** A dedicated migration sprint could align the internal code with ISO `ne`. This is a mechanical find-and-replace across translation files, imports, and validation arrays — not architecturally complex, but tedious. Prioritize only when adding more languages reveals actual friction.

---

## SEO Namespace Governance

### Principle

**Only the `seo` translation namespace is authoritative for SEO metadata.**

### Scope

| Namespace | SEO Authority | Purpose |
|-----------|---------------|---------|
| `resources/lang/*/seo.php` | ✅ **Authoritative** | Page titles, descriptions, OG text |
| `resources/lang/*/*.php` (other) | ❌ Not used for SEO | UI text, form labels, validation |
| `resources/js/locales/*.json` | ✅ **Client-side mirror** | `useMeta` reads from here for SPA nav |
| `resources/js/locales/*/*.json` (other) | ❌ Not used for SEO | Component text, page content |

### Why This Matters

- Prevents accidental SEO text changes when editing non-SEO translation files
- Enables independent review of SEO translations (separate from UI translations)
- Clear failure mode: if `seo.php` is missing a key → fallback to English → gap is visible in meta tags
- The Vue i18n JSON files mirror `seo.php` content for client-side `useMeta` — they are the **only** non-PHP source of SEO text

### Enforcement

- SEO text changes **must** update both `resources/lang/*/seo.php` and `resources/js/locales/*.json`
- A missing SEO key in `seo.php` falls back: current locale → English → `config('meta.*')` default
- Non-SEO translation namespaces must never contain title, description, OG, or Twitter card values

---

## Translation Governance

### Principle

**Every public page must have complete SEO metadata for every supported locale.**  
A missing SEO key is a defect, not a fallback scenario.

### Required Keys Per Page

Every page key in `seo.php` must define:

| Key | Purpose | Example |
|-----|---------|---------|
| `seo.title` | Browser tab title | `"Election Architecture \| Public Digit"` |
| `seo.description` | Meta description | `"Learn how our state machine..."` |
| `seo.og_title` | Open Graph title | `"Election Architecture Explained"` |
| `seo.og_description` | Open Graph description | `"Tamper-proof state machine..."` |

Additional (recommended):
| Key | Purpose | Example |
|-----|---------|---------|
| `seo.keywords` | Meta keywords (deprecated but populated) | `"election, voting, security"` |

### Dual-Source Sync

The same four keys must exist in both sources:
- **PHP:** `resources/lang/{locale}/seo.php` (server-side `SeoService`)
- **Vue:** `resources/js/locales/{locale}.json` under `seo.{pageKey}` (client-side `useMeta`)

### Build-Time Enforcement (Required)

The CI pipeline or build step **must fail** if:

1. A page's SEO key is missing from any locale's `seo.php`
2. A page's SEO key is missing from any locale's Vue JSON file
3. The four required keys (title, description, og_title, og_description) are incomplete for any page+locale combination
4. A locale is registered in `config.meta.php.supported_locales` but missing a `seo.php` file or Vue JSON file

### Enforcement Script Specification

A future audit script should:
1. Parse all page keys from a reference locale (e.g., English `seo.php`)
2. For each locale: verify all keys exist in both PHP and Vue sources
3. Verify four required sub-keys per page
4. Report: missing locales, missing keys, missing sub-keys
5. Exit non-zero on any gap

This is the only way to maintain SEO quality as more languages are added.

---

## Backlog: SEO-0.1 Language Audit

**Status:** 📋 Created — not started  
**Sprint:** Precedes SEO-1 (ownership cleanup)

### Acceptance Criteria

- [ ] Verify all translation files exist for all supported locales
- [ ] Verify all page keys have the four required SEO sub-keys in every locale
- [ ] Verify hreflang generation includes all supported locales
- [ ] Verify canonical URLs are correct for all public pages
- [ ] Verify sitemap includes all public pages in all locales
- [ ] Verify `ne`/`np` locale code consistency (as documented above)
- [ ] Document any gaps in `SEO_BASELINE_AUDIT.md`
- [ ] Fix gaps or create backlog stories for each

### Risk Note

This audit must complete **before** SEO-1 ownership cleanup. Fixing a missing key and removing a `<Head>` block in the same edit creates ambiguity — is the missing key a regression from the cleanup, or was it missing before? The audit establishes the baseline truth.

---

## References

- `developer_guide/seo/MULTILINGUAL_SEO_ARCHITECTURE.md` — full architecture documentation
- `developer_guide/seo/DUAL_SOURCE_SEO_ARCHITECTURE.md` — implementation details
- `architecture/seo/SEO_BASELINE_AUDIT.md` — pre-cleanup state

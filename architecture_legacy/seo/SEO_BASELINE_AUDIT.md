# SEO Baseline Audit

**Date:** 2026-06-14  
**Purpose:** Record pre-Sprint-1 state of all SEO mechanisms before cleanup begins.

## Page-by-Page SEO Mechanism

| Page | Mechanism | Issues |
|------|-----------|--------|
| `Pages/Welcome.vue` | `useMeta({ pageKey: 'home' })` | ⚠️ Duplicate — `Header/Welcome.vue` also calls it |
| `Pages/About.vue` | `useMeta({ pageKey: 'about' })` | ✅ Clean |
| `Pages/FAQ.vue` | `useMeta({ pageKey: 'faq' })` | ✅ Clean |
| `Pages/Public/Security.vue` | `useMeta({ pageKey: 'security' })` | ✅ Clean |
| `Pages/Public/ElectionArchitecture.vue` | **Standalone `<Head>`** (i18n keys) | ❌ Not using `useMeta`. Has fallback locale cookie logic inline. |
| `Pages/Public/ElectionSecurity.vue` | **Standalone `<Head>`** (hardcoded English) | ❌ Not using `useMeta`. Hardcoded text — no i18n. Also has manual DOM script overwriting OG tags. |
| `Pages/Public/VotingSecurity.vue` | `useMeta({ pageKey: 'votingSecurity' })` | ❌ Plus manual DOM script at lines 619-631 overwriting OG/Twitter tags with different values. |
| `Pages/Vote/DemoVote/Guide.vue` | `useMeta({ pageKey: 'demo-guide' })` | ✅ Has manual JSON-LD injection (HowTo + FAQ + Breadcrumb + SoftwareApplication) — intentional |
| `Components/Header/Welcome.vue` | `useMeta({ pageKey: 'home' })` | ❌ Duplicate — `Pages/Welcome.vue` also calls this |
| All other Pages/ | `useMeta()` or no SEO | ✅ Various pageKeys |

## Duplicate Sources Found

| Conflict | Files | Resolution |
|----------|-------|------------|
| `<Head>` + no `useMeta` | ElectionArchitecture.vue, ElectionSecurity.vue | Migrate to `useMeta` |
| Manual DOM + `useMeta` | VotingSecurity.vue (lines 619-631) | Remove manual DOM |
| Duplicate `useMeta` call | Welcome.vue + Header/Welcome.vue | Remove from Header |
| Hardcoded English + no i18n | ElectionSecurity.vue | Migrate to `useMeta` (i18n-driven) |

## Sitemap Coverage

| Route | In Sitemap? |
|-------|-------------|
| `/` | ✅ |
| `/about` | ❌ Missing |
| `/faq` | ❌ Missing |
| `/security` | ❌ Missing |
| `/election-architecture` | ❌ Missing |
| `/election-security` | ❌ Missing |
| `/voting/security` | ❌ Missing |
| `/public-demo/guide` | ❌ Missing |
| `/public-demo/results` | ❌ Missing |
| `/tutorials/hub` | ❌ Missing |
| `/tutorials/election-settings` | ❌ Missing |
| `/vereinswahlen` | ❌ Missing |
| `/wahlen/vereine` | ❌ Missing |
| `/wahlen/hybrid` | ❌ Missing |
| `/wahlen/sicherheit` | ❌ Missing |

**Baseline:** 3+ conflicting SEO mechanisms, 2 pages using a different SEO ownership mechanism than the project standard, 14 routes missing from sitemap.

---

## Language Audit (SEO-0.1) — 2026-06-14

### Translation File Parity

| Artifact | de | en | np |
|----------|----|----|----|
| `resources/lang/*/seo.php` | ✅ | ✅ | ✅ |
| `resources/js/locales/*.json` | ✅ | ✅ | ✅ |

All three locale files exist for both PHP and Vue sources.

### Page Key Coverage: `seo.php` (PHP)

| Page Key | en | de | np |
|----------|----|----|----|
| `home` | ✅ | ✅ | ✅ |
| `pricing` | ✅ | ✅ | ✅ |
| `organisations.show` | ✅ | ✅ | ✅ |
| `elections.index` | ✅ | ✅ | ✅ |
| `elections.show` | ✅ | ✅ | ✅ |
| `election.result` | ✅ | ✅ | ✅ |
| `login` | ✅ | ✅ | ✅ |
| `register` | ✅ | ✅ | ✅ |
| `about` | ✅ | ✅ | ✅ |
| `faq` | ✅ | ✅ | ✅ |
| `security` | ✅ | ✅ | ✅ |
| `demo` | ✅ | ✅ | ✅ |
| `dashboard` | ✅ | ✅ | ✅ |
| `profile` | ✅ | ✅ | ✅ |
| `demo.result` | ✅ | ✅ | ✅ |
| `vereinswahlen` | ✅ | ✅ | ✅ |
| `hybrid` | ✅ | ✅ | ✅ |
| `sicherheit` | ✅ | ✅ | ✅ |
| `organisation-create-tutorial` | ✅ | ✅ | ✅ |
| `governance-levels-tutorial` | ✅ | ✅ | ✅ |
| `election-architecture` | ❌ Missing | ❌ Missing | ❌ Missing |
| `election-security` | ❌ Missing | ❌ Missing | ❌ Missing |
| `votingSecurity` | ❌ Missing | ❌ Missing | ❌ Missing |

### Page Key Coverage: `locales/*.json` (Vue i18n)

| Page Key | en | de | np |
|----------|----|----|----|
| `votingSecurity` | ✅ | ✅ | ✅ |
| `demo-guide` | ✅ | ✅ | ✅ |
| `tutorials.election-settings` | ✅ | ✅ | ✅ |
| `tutorials.membership-modes` | ✅ | ✅ | ✅ |
| `election-architecture` | ❌ Missing | ❌ Missing | ❌ Missing |
| `election-security` | ❌ Missing | ❌ Missing | ❌ Missing |

### Required Sub-Key Audit

For all page keys that exist: all have `title`, `description`, `keywords` defined.

Missing `og_title` and `og_description` — these are not currently standard in the `seo.php` schema. The `useMeta` composable generates OG tags from `title` and `description` automatically, so dedicated OG keys are not strictly required. However, for pages where OG text should differ from meta description, dedicated keys would be needed.

**Verdict:** No missing sub-keys for existing entries. Three page keys entirely absent (see gaps above).

### hreflang Verification

- `config/meta.php` → `supported_locales`: `['de', 'en', 'np']` ✅
- `config/meta.php` → `og_locales`: `['de' => 'de_DE', 'en' => 'en_US', 'np' => 'ne_NP']` ✅
- `meta-info.blade.php` renders hreflang for all supported locales ✅
- `useMeta` composable has `getOGLocale()` mapping ✅

**Verdict:** hreflang infrastructure is correct.

### Canonical URL Verification

- `useMeta` sets canonical via `updateCanonical()` ✅
- ElectionArchitecture.vue sets hardcoded `https://publicdigit.com/election-architecture` — hardcoded URL, not dynamic ⚠️
- ElectionSecurity.vue sets hardcoded `https://publicdigit.com/election-security` — same issue ⚠️
- VotingSecurity.vue sets canonical via `useMeta` and also has redundant fallback in manual DOM ✅ (redundant)

**Verdict:** Hardcoded canonical URLs should use `window.location.href` to respect environment.

### Sitemap Coverage

(Unchanged from baseline — 14 static pages missing from sitemap.)

### `ne`/`np` Consistency

| Location | Code | Standard | Verdict |
|----------|------|----------|---------|
| `config/meta.php` → `supported_locales` | `np` | `ne` | ⚠️ Legacy — documented as compatibility decision |
| `config/meta.php` → `og_locales` | `np => ne_NP` | `ne_NP` | ✅ Correct mapping |
| `SetLocale.php` | `np` | `ne` | ⚠️ Legacy — consistent with other internal code |
| `resources/lang/np/` | `np` | `ne` | ⚠️ Legacy — directory name |
| `resources/js/locales/np.json` | `np` | `ne` | ⚠️ Legacy — filename |
| `<html lang="ne">` | `ne` | `ne` | ✅ ISO compliant |
| `useMeta` OG locale | `np => ne_NP` | `ne_NP` | ✅ Correct mapping |
| Compiled JS | `np => ne-NP` | `ne-NP` | ✅ Correct mapping |

No runtime inconsistency. Internal `np` is mapped correctly at every output boundary.

### Blocker for SEO-1

The missing `election-architecture`, `election-security`, and `votingSecurity` keys must be added to both PHP `seo.php` and Vue locale JSON **before** the ownership cleanup can proceed. Without these keys, calling `useMeta({ pageKey: 'election-architecture' })` would fall back to site defaults, losing page-specific SEO metadata.

---

## Architectural Questions

1. Should `useMeta` become the single frontend SEO authority?
2. Should standalone `<Head>` usage be permitted?
3. What responsibilities remain in `meta-info.blade.php`?
4. Where should JSON-LD structured data ownership live (composable vs middleware)?

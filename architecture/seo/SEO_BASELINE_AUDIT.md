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

## Architectural Questions

1. Should `useMeta` become the single frontend SEO authority?
2. Should standalone `<Head>` usage be permitted?
3. What responsibilities remain in `meta-info.blade.php`?
4. Where should JSON-LD structured data ownership live (composable vs middleware)?

# Plan: SEO Phase 1 — Increment 1 (Ownership Cleanup)

**Date:** 2026-06-14  
**Context:** SEO exploration revealed 3 co-existing meta mechanisms (useMeta, server-side blade, Inertia `<Head>`) that can conflict. Sprint 1 cleans up duplication and standardizes ownership before any new features are added. JSON-LD support and rollout deferred to Sprint 2.

## Sprint SEO-1 Scope

Start with a baseline audit before any code changes. Then cleanup. No framework changes to `useMeta`. No JSON-LD.

### Story SEO-0 — Baseline Audit

Create `architecture/seo/SEO_BASELINE_AUDIT.md` documenting current state of every public page's SEO mechanism — which uses `useMeta`, which uses `<Head>`, which has manual DOM conflicts, and sitemap coverage counts. This is the evidence record against which Sprint 1 success is measured.

Only ownership cleanup, baseline documentation, and sitemap fixes. No framework changes to `useMeta`. No JSON-LD rollout.

### Story SEO-1 — Standardize on useMeta

Remove standalone `<Head>` blocks and conflicting manual DOM manipulation. Add `useMeta()` calls.

**Files:**
- `resources/js/Pages/Public/ElectionArchitecture.vue` — remove `<Head>` block (lines 2-15), add `useMeta({ pageKey: 'election-architecture' })`
- `resources/js/Pages/Public/ElectionSecurity.vue` — remove `<Head>` block (lines 2-12), add `useMeta({ pageKey: 'election-security' })`
- `resources/js/Pages/Public/VotingSecurity.vue` — remove manual OG/Twitter DOM script (lines 619-631), `useMeta` already covers these

**Risk:** Low — `useMeta` already handles all the tags these pages set manually. Removing `<Head>` eliminates the duplicate-source conflict.

### Story SEO-2 — Remove duplicate useMeta call

`Welcome.vue` (line 135) and `Components/Header/Welcome.vue` (line 98) both call `useMeta({ pageKey: 'home' })`. The component should not own SEO — only the page should.

**File:** `resources/js/Components/Header/Welcome.vue` — remove `useMeta({ pageKey: 'home' })` call

**Risk:** Very low — the page component's call still runs.

### Story SEO-3 — Sitemap enhancement

Add missing static public pages to `SitemapController.php`:
- `/about`, `/faq`, `/security`, `/election-architecture`, `/election-security`, `/voting/security`
- `/public-demo/guide`, `/public-demo/results`
- `/tutorials/hub`, `/tutorials/election-settings`
- `/vereinswahlen`, `/wahlen/vereine`, `/wahlen/hybrid`, `/wahlen/sicherheit`

Each with appropriate change frequency (monthly/static) and priority (0.6-0.8).

**File:** `app/Http/Controllers/SitemapController.php`

**Risk:** Low — additive only, existing entries unchanged.

### Story SEO-4 — SEO Architecture Baseline

Create `architecture/seo/SEO_ARCHITECTURE_BASELINE.md` documenting:

| Component | Owner | Authority |
|-----------|-------|-----------|
| `useMeta` composable | Frontend pages | ✅ Primary SEO authority |
| `InjectPageMeta` middleware | Backend | Provides metadata to views |
| `meta-info.blade.php` | Server render | Fallback/default meta tags |
| `SitemapController` | Backend | Crawl authority |
| `robots.txt` | Backend | Index authority |
| JSON-LD | Future | Deferred to Sprint 2 |

Documents the rule: one page = one SEO owner. No duplicate `<Head>` + `useMeta`.

## Deferred to Sprint 2

- JSON-LD support in `useMeta` — framework change affecting 20+ pages
- JSON-LD pilot on Home, About, FAQ — depends on framework support
- JSON-LD rollout to remaining pages

## Verification

- View page source on `/election-architecture`, `/election-security`, `/voting/security` — no duplicate OG/Twitter tags
- `useMeta` is the single mechanism for all public pages
- Sitemap includes all static pages listed above
- `SEO_ARCHITECTURE_BASELINE.md` documents ownership model

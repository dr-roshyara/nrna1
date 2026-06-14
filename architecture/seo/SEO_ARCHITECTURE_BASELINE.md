# SEO Architecture Baseline

**Date:** 2026-06-14  
**Status:** Established  
**Rule:** One page = one SEO owner. No duplicate `<Head>` + `useMeta`.

## Component Ownership

| Component | Location | Role | Authority |
|-----------|----------|------|-----------|
| `useMeta` composable | `resources/js/composables/useMeta.js` | Client-side SEO for all public pages. Reads from i18n. Sets title, OG, Twitter, robots, canonical. | **Primary frontend SEO renderer** |
| `InjectPageMeta` middleware | `app/Http/Middleware/InjectPageMeta.php` | Server-side. Maps route → page key. Shares `meta` prop via Inertia + Blade. | **Provider** — supplies metadata to views |
| `meta-info.blade.php` | `resources/views/meta/meta-info.blade.php` | Server-side fallback. Renders OG/Twitter/hreflang/PWA tags from middleware data. | **Fallback** — server-rendered defaults |
| `config/meta.php` | `config/meta.php` | Default values when no page-level SEO is set. | **Default** — baseline configuration |
| `SitemapController` | `app/Http/Controllers/SitemapController.php` | Generates XML sitemap and index. | **Crawl authority** — tells search engines what exists |
| `robots.txt` controller | `app/Http/Controllers/RobotsController.php` | Generates robots.txt with disallow rules for auth/sensitive routes. | **Index authority** — controls what gets crawled |
| JSON-LD | Future — deferred to Sprint 2 | Structured data injection via `useMeta` extension. | Pending |

## Rules

1. **Every public page uses `useMeta`.** No standalone `<Head>` blocks. No manual DOM manipulation for OG/Twitter tags.
2. **One page = one `useMeta` call.** Components must not call `useMeta`. Only page-level `.vue` files in `Pages/` own SEO.
3. **`useMeta` reads from i18n.** No hardcoded English text in SEO meta tags. All text goes through `$t()` or locale JSON files.
4. **`meta-info.blade.php` remains as fallback.** It provides server-rendered defaults for pages not yet migrated and for social media crawlers that don't execute JavaScript.
5. **Sitemap includes all public routes.** Static pages (about, faq, security, architecture, guides) must be listed with appropriate change frequency.
6. **SEO ownership is page-scoped.** Reusable components must never create, modify, or remove SEO metadata.

## Open Questions

1. Should all metadata originate from `InjectPageMeta` middleware rather than being duplicated in i18n?
2. Should `useMeta` eventually become a thin renderer that reads from the shared `meta` prop instead of i18n?
3. How should JSON-LD structured data ownership be governed (composable vs middleware)?
4. Should sitemap generation be route-driven (auto-discover) or manually curated?

## References

- `architecture/seo/SEO_BASELINE_AUDIT.md` — pre-cleanup state
- `resources/js/composables/useMeta.js` — SEO implementation
- `app/Http/Middleware/InjectPageMeta.php` — middleware metadata provider

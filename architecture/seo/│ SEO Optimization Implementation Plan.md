│ SEO Optimization Implementation Plan for Public Digit                                                                │
│                                                                                                                      │
│ Project: Public Digit - Multi-tenant Digital Democracy Platform                                                      │
│ Date: February 2026                                                                                                  │
│ Status: Planning Phase                                                                                               │
│ Stack: Laravel 12 + Mix (Webpack) + Vue 3 + Inertia.js                                                               │
│                                                                                                                      │
│ ---                                                                                                                  │
│ Executive Summary                                                                                                    │
│                                                                                                                      │
│ This plan implements comprehensive SEO optimization for the Public Digit election platform while maintaining         │
│ security, vote anonymity, and multi-language support (EN, DE, NP). The solution leverages existing infrastructure    │
│ (Laravel Mix, Inertia.js, Vue 3) and adds:                                                                           │
│                                                                                                                      │
│ 1. Dynamic Per-Page Meta Management via composable system                                                            │
│ 2. Enhanced Multi-Language SEO with hreflang tags                                                                    │
│ 3. Expanded Sitemap Generation for all content types                                                                 │
│ 4. Structured Data Implementation for elections, organizations, breadcrumbs                                          │
│ 5. Performance Monitoring & Analytics setup                                                                          │
│ 6. Security-Conscious SEO balancing indexing with vote privacy                                                       │
│                                                                                                                      │
│ ---                                                                                                                  │
│ Current State Assessment                                                                                             │
│                                                                                                                      │
│ Existing SEO Foundation ✅                                                                                           │
│                                                                                                                      │
│ - Config-based meta tags: config/meta.php with static site-wide meta                                                 │
│ - Blade template meta: resources/views/meta/meta-info.blade.php (comprehensive)                                      │
│ - Sitemap generation: SitemapController (basic - missing organizations/elections)                                    │
│ - Multi-language support: SetLocale middleware + i18n.js (3 languages: DE, EN, NP)                                   │
│ - Inertia props sharing: HandleInertiaRequests middleware (sends locale to all pages)                                │
│ - Structured data: JSON-LD organisation + Website schemas                                                            │
│                                                                                                                      │
│ SEO Gaps ❌                                                                                                          │
│                                                                                                                      │
│ - No per-page meta customization: Uses static config fallback                                                        │
│ - No dynamic hreflang tags: Multi-language support exists but not SEO-implemented                                    │
│ - Limited sitemap: Missing organisation pages, elections, results pages                                              │
│ - No breadcrumb schema: JSON-LD breadcrumbs not implemented                                                          │
│ - No robots.txt file: Rely on meta robots tag only                                                                   │
│ - Static canonical URLs: Not dynamic per page/language                                                               │
│ - No per-page structured data: Election pages could use Event schema                                                 │
│ - No head management package: Blade templating only, no @vueuse/head or Unhead                                       │
│                                                                                                                      │
│ Architecture Notes                                                                                                   │
│                                                                                                                      │
│ - Build Tool: Laravel Mix (Webpack), not Vite - no SSR setup                                                         │
│ - Meta Management: Currently Blade-based, needs Vue/Composable layer                                                 │
│ - Routing: Slug-based organizations (/organizations/{slug}), not parameterized {tenant}                              │
│ - Authentication: Inertia Props shared via HandleInertiaRequests middleware                                          │
│ - Sensitive Pages: Voting/verification pages should use noindex to protect privacy                                   │
│                                                                                                                      │
│ ---                                                                                                                  │
│ Implementation Strategy                                                                                              │
│                                                                                                                      │
│ Phase 1: Foundation (Weeks 1-2)                                                                                      │
│                                                                                                                      │
│ 1.1 Create useMeta Composable                                                                                        │
│                                                                                                                      │
│ File: resources/js/composables/useMeta.js                                                                            │
│                                                                                                                      │
│ Create a Vue 3 composable that:                                                                                      │
│ - Manages page-level meta tags                                                                                       │
│ - Supports title, description, keywords, image                                                                       │
│ - Generates OG tags                                                                                                  │
│ - Adds Twitter Card tags                                                                                             │
│ - Injects canonical URLs                                                                                             │
│ - Handles hreflang tags for multi-language pages                                                                     │
│ - Compatible with existing Inertia Blade template (non-SSR)                                                          │
│                                                                                                                      │
│ Key Properties:                                                                                                      │
│ - Dynamic title format: "{Page Title} | Public Digit"                                                                │
│ - Auto-generated OG image based on page type                                                                         │
│ - Locale-aware hreflang links                                                                                        │
│ - Security-aware: optional noindex flag for sensitive pages                                                          │
│                                                                                                                      │
│ 1.2 Enhance Inertia Props                                                                                            │
│                                                                                                                      │
│ File: app/Http/Middleware/HandleInertiaRequests.php                                                                  │
│                                                                                                                      │
│ Update shared props to include:                                                                                      │
│ - seoData: Base SEO metadata per page (title, description)                                                           │
│ - currentLanguage: Current locale with label (de, en, np)                                                            │
│ - altLanguages: Array of alternate language URLs for hreflang                                                        │
│ - breadcrumbs: Breadcrumb structure per page                                                                         │
│                                                                                                                      │
│ 1.3 Create Page Meta Helper                                                                                          │
│                                                                                                                      │
│ File: app/Helpers/SeoHelper.php                                                                                      │
│                                                                                                                      │
│ Static helper class:                                                                                                 │
│ - generateTitle(page, separator = '|') - Format page titles consistently                                             │
│ - generateDescription(text, maxLength = 160) - Truncate descriptions                                                 │
│ - getBreadcrumbs(page, ...args) - Generate breadcrumb arrays                                                         │
│ - getAlternateLanguageUrls(route, locale) - Create hreflang URLs                                                     │
│ - getOgImage(pageType, fallback) - Determine OG image per content type                                               │
│                                                                                                                      │
│ 1.4 Update View Component                                                                                            │
│                                                                                                                      │
│ File: resources/views/app.blade.php                                                                                  │
│                                                                                                                      │
│ Modify blade template to:                                                                                            │
│ - Accept dynamic props from Inertia                                                                                  │
│ - Remove hardcoded meta tags that will be dynamic                                                                    │
│ - Keep fallback meta tags for non-Inertia routes                                                                     │
│ - Improve canonical URL handling                                                                                     │
│                                                                                                                      │
│ ---                                                                                                                  │
│ Phase 2: Multi-Language & Sitemap (Weeks 2-3)                                                                        │
│                                                                                                                      │
│ 2.1 Implement Hreflang Tags                                                                                          │
│                                                                                                                      │
│ File: resources/js/composables/useMeta.js (extension)                                                                │
│                                                                                                                      │
│ Add hreflang link generation:                                                                                        │
│ For /de/about → Generate hreflang links:                                                                             │
│   <link rel="alternate" hreflang="en" href="/en/about">                                                              │
│   <link rel="alternate" hreflang="de" href="/de/about">                                                              │
│   <link rel="alternate" hreflang="np" href="/np/about">                                                              │
│   <link rel="canonical" href="/de/about">                                                                            │
│                                                                                                                      │
│ Considerations:                                                                                                      │
│ - URL structure: /{locale}/route vs route (query param)                                                              │
│ - Self-referential hreflang (x-default)                                                                              │
│ - Dynamic route parameter replacement                                                                                │
│                                                                                                                      │
│ 2.2 Enhance Sitemap Generation                                                                                       │
│                                                                                                                      │
│ File: app/Http/Controllers/SitemapController.php                                                                     │
│                                                                                                                      │
│ Expand sitemap to include:                                                                                           │
│ - Static pages (about, pricing, blog)                                                                                │
│ - organisation pages (indexed? security consideration)                                                               │
│ - Active elections (indexed? depends on privacy requirements)                                                        │
│ - Public candidate listings per election                                                                             │
│ - Result pages (post-election)                                                                                       │
│ - User profiles (with robots noindex option)                                                                         │
│                                                                                                                      │
│ Structure:                                                                                                           │
│ - Homepage (daily, 1.0)                                                                                              │
│ - Public info pages (weekly, 0.9)                                                                                    │
│ - Organizations (weekly, 0.8)                                                                                        │
│ - Elections (weekly, 0.8)                                                                                            │
│ - Candidate listings (weekly, 0.7)                                                                                   │
│ - Results pages (monthly, 0.6)                                                                                       │
│                                                                                                                      │
│ Security: Add flag to exclude sensitive pages (voting URLs, verification)                                            │
│                                                                                                                      │
│ 2.3 Create Robots.txt                                                                                                │
│                                                                                                                      │
│ File: public/robots.txt                                                                                              │
│                                                                                                                      │
│ User-agent: *                                                                                                        │
│ Allow: /                                                                                                             │
│ Disallow: /admin                                                                                                     │
│ Disallow: /dashboard                                                                                                 │
│ Disallow: /vote                                                                                                      │
│ Disallow: /v/                                                                                                        │
│ Disallow: /mapi                                                                                                      │
│ Disallow: /api                                                                                                       │
│ Disallow: /*.json$                                                                                                   │
│ Disallow: /*?*sort=                                                                                                  │
│ Disallow: /search                                                                                                    │
│                                                                                                                      │
│ Sitemap: {{ url('/sitemap.xml') }}                                                                                   │
│ Sitemap: {{ url('/sitemap-organizations.xml') }}                                                                     │
│ Sitemap: {{ url('/sitemap-elections.xml') }}                                                                         │
│                                                                                                                      │
│ Note: Generate dynamically via route for language support                                                            │
│                                                                                                                      │
│ ---                                                                                                                  │
│ Phase 3: Structured Data & Content (Weeks 3-4)                                                                       │
│                                                                                                                      │
│ 3.1 Implement Breadcrumb Schema                                                                                      │
│                                                                                                                      │
│ File: resources/js/components/BreadcrumbSchema.vue                                                                   │
│                                                                                                                      │
│ Create Vue component that:                                                                                           │
│ - Receives breadcrumb array from props                                                                               │
│ - Generates JSON-LD BreadcrumbList schema                                                                            │
│ - Renders semantic HTML breadcrumb                                                                                   │
│ - Supports multi-language labels                                                                                     │
│                                                                                                                      │
│ Example output:                                                                                                      │
│ {                                                                                                                    │
│   "@context": "https://schema.org",                                                                                  │
│   "@type": "BreadcrumbList",                                                                                         │
│   "itemListElement": [                                                                                               │
│     {                                                                                                                │
│       "@type": "ListItem",                                                                                           │
│       "position": 1,                                                                                                 │
│       "name": "Home",                                                                                                │
│       "item": "https://publicdigit.com"                                                                              │
│     },                                                                                                               │
│     {                                                                                                                │
│       "@type": "ListItem",                                                                                           │
│       "position": 2,                                                                                                 │
│       "name": "Elections",                                                                                           │
│       "item": "https://publicdigit.com/elections"                                                                    │
│     }                                                                                                                │
│   ]                                                                                                                  │
│ }                                                                                                                    │
│                                                                                                                      │
│ 3.2 Election Event Schema                                                                                            │
│                                                                                                                      │
│ File: app/Helpers/SchemaGenerator.php                                                                                │
│                                                                                                                      │
│ Create election-specific schema:                                                                                     │
│ - Type: Event (Schema.org)                                                                                           │
│ - Properties: name, description, startDate, endDate, image, organizer, location (virtual)                            │
│ - Dynamic fields: From Election model                                                                                │
│ - Security note: Don't include voter participation data                                                              │
│                                                                                                                      │
│ Usage in ElectionPage.vue:                                                                                           │
│ <StructuredData                                                                                                      │
│   :type="'Event'"                                                                                                    │
│   :data="electionEventData"                                                                                          │
│ />                                                                                                                   │
│                                                                                                                      │
│ 3.3 organisation Schema Enhancement                                                                                  │
│                                                                                                                      │
│ File: resources/js/Pages/Organizations/Show.vue                                                                      │
│                                                                                                                      │
│ Add organisation schema for each organisation page:                                                                  │
│ - Type: organisation                                                                                                 │
│ - Properties: name, description, address, email, url, logo, sameAs (social links)                                    │
│ - Relationships: organisation member count, election count                                                           │
│                                                                                                                      │
│ ---                                                                                                                  │
│ Phase 4: Per-Page Implementation (Weeks 4-5)                                                                         │
│                                                                                                                      │
│ 4.1 Public-Facing Pages                                                                                              │
│                                                                                                                      │
│ Welcome Page (resources/js/Pages/Welcome.vue):                                                                       │
│ - Title: "Public Digit - Secure Digital Elections for Diaspora Communities"                                          │
│ - Description: Diaspora voting platform description                                                                  │
│ - Keywords: diaspora, elections, voting, online voting, NRNA                                                         │
│ - OG Image: Homepage hero image                                                                                      │
│ - Schema: WebSite + organisation                                                                                     │
│                                                                                                                      │
│ About Page (create if missing):                                                                                      │
│ - Title: "How Public Digit Works"                                                                                    │
│ - Description: Election process explanation                                                                          │
│ - Schema: HowTo (5-step voting process)                                                                              │
│                                                                                                                      │
│ Pricing Page:                                                                                                        │
│ - Title: "Public Digit Pricing"                                                                                      │
│ - Keywords: election pricing, voting platform cost                                                                   │
│ - Schema: PricingInfo (if available)                                                                                 │
│                                                                                                                      │
│ 4.2 organisation Pages                                                                                               │
│                                                                                                                      │
│ Organizations/Show.vue (/organizations/{slug}):                                                                      │
│ - Dynamic title: "{organisation Name} - Elections | Public Digit"                                                    │
│ - Description: organisation + member count + election count                                                          │
│ - Canonical: /organizations/{slug}                                                                                   │
│ - Hreflang: If organisation pages are language-specific                                                              │
│ - Schema: organisation                                                                                               │
│ - Optional: noindex if organisation pages are private                                                                │
│                                                                                                                      │
│ 4.3 Election Pages                                                                                                   │
│                                                                                                                      │
│ Election Result Page (/election/result):                                                                             │
│ - Dynamic title: "{Election Name} - Results"                                                                         │
│ - Description: Election type + date + result summary                                                                 │
│ - OG Image: organisation logo                                                                                        │
│ - Schema: Event (completed state)                                                                                    │
│ - Status: Published (searchable)                                                                                     │
│                                                                                                                      │
│ Candidacy Pages (if public):                                                                                         │
│ - Title: "{Candidate Name} - {Position} | {organisation}"                                                            │
│ - Description: Candidate bio/statement excerpt                                                                       │
│ - OG Image: Candidate photo                                                                                          │
│ - Schema: Person + Candidacy                                                                                         │
│                                                                                                                      │
│ 4.4 Admin Pages (noindex)                                                                                            │
│                                                                                                                      │
│ Dashboard Pages:                                                                                                     │
│ - Set robots: noindex via useMeta                                                                                    │
│ - Prevents indexing of private admin areas                                                                           │
│ - Affects: /dashboard, /admin, /commission, /vote/*                                                                  │
│                                                                                                                      │
│ Voting Pages (security):                                                                                             │
│ - Set robots: noindex via useMeta                                                                                    │
│ - Protects voter privacy                                                                                             │
│ - Affects: /v/{slug}/*, /vote/*, any user-specific voting URLs                                                       │
│                                                                                                                      │
│ ---                                                                                                                  │
│ Phase 5: Integration & Testing (Weeks 5-6)                                                                           │
│                                                                                                                      │
│ 5.1 Create SEO Page Templates                                                                                        │
│                                                                                                                      │
│ Template locations:                                                                                                  │
│                                                                                                                      │
│ 1. Public Page Template (resources/js/Layouts/PublicSeoLayout.vue):                                                  │
│   - Includes BreadcrumbSchema                                                                                        │
│   - Dynamic meta handling                                                                                            │
│   - Open Graph integration                                                                                           │
│ 2. Election Page Template (resources/js/Layouts/ElectionSeoLayout.vue):                                              │
│   - Event schema integration                                                                                         │
│   - Election-specific meta                                                                                           │
│   - Breadcrumb: Home > Elections > {Election}                                                                        │
│ 3. organisation Page Template (resources/js/Layouts/OrganizationSeoLayout.vue):                                      │
│   - organisation schema                                                                                              │
│   - Canonical URL handling                                                                                           │
│   - Breadcrumb: Home > Organizations > {Org}                                                                         │
│                                                                                                                      │
│ 5.2 SEO Checklist Component                                                                                          │
│                                                                                                                      │
│ File: resources/js/components/SeoChecklist.vue                                                                       │
│                                                                                                                      │
│ Development tool (disabled in production) showing:                                                                   │
│ - ✓ Title tag (< 60 chars)                                                                                           │
│ - ✓ Meta description (155-160 chars)                                                                                 │
│ - ✓ H1 tag present                                                                                                   │
│ - ✓ Image alt text                                                                                                   │
│ - ✓ Internal links count                                                                                             │
│ - ✓ Page speed estimate                                                                                              │
│ - ✓ Mobile responsiveness                                                                                            │
│                                                                                                                      │
│ 5.3 Analytics Setup                                                                                                  │
│                                                                                                                      │
│ Google Search Console:                                                                                               │
│ - Submit sitemaps for verification                                                                                   │
│ - Monitor indexing errors                                                                                            │
│ - Track search queries                                                                                               │
│ - Monitor CTR and average position                                                                                   │
│ - Set site-preferred domain                                                                                          │
│                                                                                                                      │
│ Google Analytics 4:                                                                                                  │
│ - Already integrated (GTM-MH39X8L)                                                                                   │
│ - Track page views with custom SEO metadata                                                                          │
│ - Monitor Core Web Vitals                                                                                            │
│ - Set up goal tracking for key actions                                                                               │
│                                                                                                                      │
│ 5.4 Performance Monitoring                                                                                           │
│                                                                                                                      │
│ Core Web Vitals:                                                                                                     │
│ - Largest Contentful Paint (LCP): < 2.5s                                                                             │
│ - First Input Delay (FID): < 100ms                                                                                   │
│ - Cumulative Layout Shift (CLS): < 0.1                                                                               │
│                                                                                                                      │
│ Tools:                                                                                                               │
│ - Lighthouse reports                                                                                                 │
│ - Web Vitals JavaScript library                                                                                      │
│ - Custom performance tracking                                                                                        │
│                                                                                                                      │
│ ---                                                                                                                  │
│ File Modification Summary                                                                                            │
│                                                                                                                      │
│ New Files (Create)                                                                                                   │
│ ┌──────────────────────────────────────────────┬────────────────────────────────────────────┬──────────┐             │
│ │                  File Path                   │                  Purpose                   │ Priority │             │
│ ├──────────────────────────────────────────────┼────────────────────────────────────────────┼──────────┤             │
│ │ resources/js/composables/useMeta.js          │ Page-level meta management                 │ P0       │             │
│ ├──────────────────────────────────────────────┼────────────────────────────────────────────┼──────────┤             │
│ │ app/Helpers/SeoHelper.php                    │ SEO utility functions                      │ P0       │             │
│ ├──────────────────────────────────────────────┼────────────────────────────────────────────┼──────────┤             │
│ │ app/Helpers/SchemaGenerator.php              │ JSON-LD schema generation                  │ P1       │             │
│ ├──────────────────────────────────────────────┼────────────────────────────────────────────┼──────────┤             │
│ │ public/robots.txt                            │ robots.txt generation (via route)          │ P0       │             │
│ ├──────────────────────────────────────────────┼────────────────────────────────────────────┼──────────┤             │
│ │ resources/js/components/BreadcrumbSchema.vue │ Breadcrumb JSON-LD                         │ P1       │             │
│ ├──────────────────────────────────────────────┼────────────────────────────────────────────┼──────────┤             │
│ │ resources/js/components/SeoChecklist.vue     │ Dev SEO audit tool                         │ P2       │             │
│ ├──────────────────────────────────────────────┼────────────────────────────────────────────┼──────────┤             │
│ │ resources/js/Layouts/PublicSeoLayout.vue     │ Public page template                       │ P1       │             │
│ ├──────────────────────────────────────────────┼────────────────────────────────────────────┼──────────┤             │
│ │ routes/seo.php                               │ SEO-specific routes (robots.txt, sitemaps) │ P0       │             │
│ └──────────────────────────────────────────────┴────────────────────────────────────────────┴──────────┘             │
│ Modified Files (Update)                                                                                              │
│ ┌────────────────────────────────────────────────┬───────────────────────────────────────────┬──────────┐            │
│ │                   File Path                    │                  Changes                  │ Priority │            │
│ ├────────────────────────────────────────────────┼───────────────────────────────────────────┼──────────┤            │
│ │ app/Http/Controllers/SitemapController.php     │ Expand to include all content types       │ P0       │            │
│ ├────────────────────────────────────────────────┼───────────────────────────────────────────┼──────────┤            │
│ │ app/Http/Middleware/HandleInertiaRequests.php  │ Add SEO props (breadcrumbs, altLanguages) │ P0       │            │
│ ├────────────────────────────────────────────────┼───────────────────────────────────────────┼──────────┤            │
│ │ resources/views/app.blade.php                  │ Dynamic meta tag handling                 │ P0       │            │
│ ├────────────────────────────────────────────────┼───────────────────────────────────────────┼──────────┤            │
│ │ config/meta.php                                │ Add per-page meta config                  │ P1       │            │
│ ├────────────────────────────────────────────────┼───────────────────────────────────────────┼──────────┤            │
│ │ resources/js/Pages/Welcome.vue                 │ Implement useMeta composable              │ P1       │            │
│ ├────────────────────────────────────────────────┼───────────────────────────────────────────┼──────────┤            │
│ │ resources/js/Pages/Organizations/Show.vue      │ Implement useMeta + organisation schema   │ P1       │            │
│ ├────────────────────────────────────────────────┼───────────────────────────────────────────┼──────────┤            │
│ │ resources/js/Pages/Election/ElectionResult.vue │ Implement useMeta + Event schema          │ P1       │            │
│ └────────────────────────────────────────────────┴───────────────────────────────────────────┴──────────┘            │
│ ---                                                                                                                  │
│ Implementation Details                                                                                               │
│                                                                                                                      │
│ 1. useMeta Composable (Most Critical)                                                                                │
│                                                                                                                      │
│ // resources/js/composables/useMeta.js                                                                               │
│                                                                                                                      │
│ import { usePage } from '@inertiajs/vue3-vue3'                                                                    │
│ import { computed } from 'vue'                                                                                       │
│                                                                                                                      │
│ export function useMeta(config = {}) {                                                                               │
│   const page = usePage()                                                                                             │
│                                                                                                                      │
│   const defaults = {                                                                                                 │
│     title: page.props.seoData?.title || 'Public Digit',                                                              │
│     description: page.props.seoData?.description || 'Secure elections for diaspora communities',                     │
│     keywords: page.props.seoData?.keywords || [],                                                                    │
│     image: page.props.seoData?.image || '/images/og-default.jpg',                                                    │
│     noindex: false,                                                                                                  │
│     nofollow: false,                                                                                                 │
│     type: 'website',                                                                                                 │
│     ...config                                                                                                        │
│   }                                                                                                                  │
│                                                                                                                      │
│   // Generate title with suffix                                                                                      │
│   const fullTitle = computed(() => {                                                                                 │
│     if (defaults.title.includes('|')) return defaults.title                                                          │
│     return `${defaults.title} | Public Digit`                                                                        │
│   })                                                                                                                 │
│                                                                                                                      │
│   // Generate canonical URL                                                                                          │
│   const canonical = computed(() => {                                                                                 │
│     return defaults.canonical || window.location.href                                                                │
│   })                                                                                                                 │
│                                                                                                                      │
│   // Update document head                                                                                            │
│   updateHead(fullTitle.value, defaults)                                                                              │
│                                                                                                                      │
│   return {                                                                                                           │
│     fullTitle,                                                                                                       │
│     description: defaults.description,                                                                               │
│     canonical                                                                                                        │
│   }                                                                                                                  │
│ }                                                                                                                    │
│                                                                                                                      │
│ function updateHead(title, config) {                                                                                 │
│   // Update title                                                                                                    │
│   document.title = title                                                                                             │
│                                                                                                                      │
│   // Meta tags                                                                                                       │
│   setMetaTag('description', config.description)                                                                      │
│   setMetaTag('keywords', Array.isArray(config.keywords) ? config.keywords.join(', ') : config.keywords)              │
│                                                                                                                      │
│   // OG Tags                                                                                                         │
│   setMetaTag('og:title', title, 'property')                                                                          │
│   setMetaTag('og:description', config.description, 'property')                                                       │
│   setMetaTag('og:image', config.image, 'property')                                                                   │
│   setMetaTag('og:type', config.type, 'property')                                                                     │
│                                                                                                                      │
│   // Twitter                                                                                                         │
│   setMetaTag('twitter:title', title)                                                                                 │
│   setMetaTag('twitter:description', config.description)                                                              │
│   setMetaTag('twitter:image', config.image)                                                                          │
│                                                                                                                      │
│   // Robots                                                                                                          │
│   if (config.noindex || config.nofollow) {                                                                           │
│     const robots = []                                                                                                │
│     if (config.noindex) robots.push('noindex')                                                                       │
│     if (config.nofollow) robots.push('nofollow')                                                                     │
│     setMetaTag('robots', robots.join(', '))                                                                          │
│   }                                                                                                                  │
│ }                                                                                                                    │
│                                                                                                                      │
│ function setMetaTag(name, content, attribute = 'name') {                                                             │
│   let tag = document.querySelector(`meta[${attribute}="${name}"]`)                                                   │
│   if (!tag) {                                                                                                        │
│     tag = document.createElement('meta')                                                                             │
│     tag.setAttribute(attribute, name)                                                                                │
│     document.head.appendChild(tag)                                                                                   │
│   }                                                                                                                  │
│   tag.setAttribute('content', content)                                                                               │
│ }                                                                                                                    │
│                                                                                                                      │
│ 2. Enhanced HandleInertiaRequests                                                                                    │
│                                                                                                                      │
│ // app/Http/Middleware/HandleInertiaRequests.php                                                                     │
│                                                                                                                      │
│ protected function share(Request $request)                                                                           │
│ {                                                                                                                    │
│     return array_merge(parent::share($request), [                                                                    │
│         'seoData' => [                                                                                               │
│             'title' => 'Public Digit',                                                                               │
│             'description' => 'Secure elections for diaspora communities',                                            │
│             'image' => url('/images/og-default.jpg'),                                                                │
│         ],                                                                                                           │
│         'altLanguages' => $this->getAlternateLanguages($request),                                                    │
│         'breadcrumbs' => $this->generateBreadcrumbs($request),                                                       │
│         'locale' => app()->getLocale(),                                                                              │
│     ]);                                                                                                              │
│ }                                                                                                                    │
│                                                                                                                      │
│ private function getAlternateLanguages(Request $request)                                                             │
│ {                                                                                                                    │
│     $locales = ['en', 'de', 'np'];                                                                                   │
│     $routes = [];                                                                                                    │
│                                                                                                                      │
│     foreach ($locales as $locale) {                                                                                  │
│         $routes[$locale] = $this->buildUrl($request, $locale);                                                       │
│     }                                                                                                                │
│                                                                                                                      │
│     return $routes;                                                                                                  │
│ }                                                                                                                    │
│                                                                                                                      │
│ private function generateBreadcrumbs(Request $request)                                                               │
│ {                                                                                                                    │
│     // Return breadcrumb structure based on route                                                                    │
│     // E.g., ["Home", "Elections", "Results"]                                                                        │
│ }                                                                                                                    │
│                                                                                                                      │
│ 3. Enhanced Sitemap Controller                                                                                       │
│                                                                                                                      │
│ // app/Http/Controllers/SitemapController.php                                                                        │
│                                                                                                                      │
│ public function index()                                                                                              │
│ {                                                                                                                    │
│     // Include:                                                                                                      │
│     // - Homepage (always)                                                                                           │
│     // - Public pages                                                                                                │
│     // - Organizations (if public)                                                                                   │
│     // - Elections (if completed + public)                                                                           │
│     // - Candidate pages (if approved)                                                                               │
│     // Exclude: User dashboards, voting pages, verification URLs                                                     │
│ }                                                                                                                    │
│                                                                                                                      │
│ public function organizations()                                                                                      │
│ {                                                                                                                    │
│     // Separate sitemap for organizations                                                                            │
│ }                                                                                                                    │
│                                                                                                                      │
│ public function elections()                                                                                          │
│ {                                                                                                                    │
│     // Separate sitemap for elections + results                                                                      │
│ }                                                                                                                    │
│                                                                                                                      │
│ ---                                                                                                                  │
│ Security Considerations                                                                                              │
│                                                                                                                      │
│ Vote Privacy & SEO Balance                                                                                           │
│                                                                                                                      │
│ Pages that MUST have noindex:                                                                                        │
│ - /v/{vslug}/* - Voting flows (personal voting URLs)                                                                 │
│ - /vote/* - User-specific voting pages                                                                               │
│ - /dashboard/commission - Private committee pages                                                                    │
│ - /dashboard/admin - Admin dashboard                                                                                 │
│ - User profile pages (if private)                                                                                    │
│                                                                                                                      │
│ Pages that SHOULD have noindex:                                                                                      │
│ - /vote/verify/* - Vote verification (personal)                                                                      │
│ - Election admin pages (during voting)                                                                               │
│                                                                                                                      │
│ Pages that SHOULD be indexed:                                                                                        │
│ - Public election info pages                                                                                         │
│ - Candidate profiles (public)                                                                                        │
│ - Results pages (after election closes)                                                                              │
│ - organisation public pages                                                                                          │
│ - Landing/marketing pages                                                                                            │
│                                                                                                                      │
│ Implementation Strategy                                                                                              │
│                                                                                                                      │
│ - Use noindex flag in useMeta() for sensitive pages                                                                  │
│ - Server-side: Set robots meta tag in Inertia props                                                                  │
│ - Verify no user data exposed in URLs                                                                                │
│ - Use 404 for private pages instead of 403 (security through obscurity)                                              │
│                                                                                                                      │
│ ---                                                                                                                  │
│ Monitoring & Success Metrics                                                                                         │
│                                                                                                                      │
│ SEO KPIs                                                                                                             │
│                                                                                                                      │
│ 1. Organic Search Traffic: Track growth month-over-month                                                             │
│ 2. Indexed Pages: Monitor via Google Search Console (target: 80%+ of public pages)                                   │
│ 3. Keyword Rankings: Top 10 keywords tracked for improvement                                                         │
│ 4. CTR (Click-Through Rate): Target average > 3%                                                                     │
│ 5. Average Position: Monitor ranking improvements                                                                    │
│ 6. Page Speed: Core Web Vitals compliance                                                                            │
│                                                                                                                      │
│ Tools Setup                                                                                                          │
│                                                                                                                      │
│ 1. Google Search Console: Verify domain, submit sitemaps, monitor errors                                             │
│ 2. Google Analytics 4: Track goal conversions, user journey                                                          │
│ 3. Lighthouse: Monthly audits for performance scoring                                                                │
│ 4. SEMRush/Ahrefs: (Optional) Competitive keyword analysis                                                           │
│                                                                                                                      │
│ Reporting                                                                                                            │
│                                                                                                                      │
│ - Monthly SEO dashboard (organic traffic, rankings, errors)                                                          │
│ - Quarterly strategy review                                                                                          │
│ - Quarterly content optimization cycle                                                                               │
│                                                                                                                      │
│ ---                                                                                                                  │
│ Phase Timeline                                                                                                       │
│ ┌─────────┬─────────┬──────────────────────┬─────────────────────────────────────────────────────────┐               │
│ │  Phase  │  Week   │        Focus         │                      Deliverables                       │               │
│ ├─────────┼─────────┼──────────────────────┼─────────────────────────────────────────────────────────┤               │
│ │ Phase 1 │ 1-2     │ Foundation           │ useMeta composable, SEO helper, Inertia props           │               │
│ ├─────────┼─────────┼──────────────────────┼─────────────────────────────────────────────────────────┤               │
│ │ Phase 2 │ 2-3     │ Multi-lang & Sitemap │ Hreflang, enhanced sitemap, robots.txt                  │               │
│ ├─────────┼─────────┼──────────────────────┼─────────────────────────────────────────────────────────┤               │
│ │ Phase 3 │ 3-4     │ Structured Data      │ Breadcrumbs, Event schema, enhanced organisation schema │               │
│ ├─────────┼─────────┼──────────────────────┼─────────────────────────────────────────────────────────┤               │
│ │ Phase 4 │ 4-5     │ Per-Page             │ Welcome, Org, Election pages updated                    │               │
│ ├─────────┼─────────┼──────────────────────┼─────────────────────────────────────────────────────────┤               │
│ │ Phase 5 │ 5-6     │ Integration          │ Testing, monitoring setup, documentation                │               │
│ ├─────────┼─────────┼──────────────────────┼─────────────────────────────────────────────────────────┤               │
│ │ Total   │ 6 weeks │                      │ Production-ready SEO                                    │               │
│ └─────────┴─────────┴──────────────────────┴─────────────────────────────────────────────────────────┘               │
│ ---                                                                                                                  │
│ Testing & Verification Checklist                                                                                     │
│                                                                                                                      │
│ Before Implementation                                                                                                │
│                                                                                                                      │
│ - Backup current SEO config                                                                                          │
│ - Document current sitemap structure                                                                                 │
│ - Note current Google Search Console status                                                                          │
│                                                                                                                      │
│ During Implementation                                                                                                │
│                                                                                                                      │
│ - Unit test useMeta composable                                                                                       │
│ - Test hreflang tag generation for all locales                                                                       │
│ - Verify sitemap XML validity                                                                                        │
│ - Test breadcrumb schema JSON-LD                                                                                     │
│ - Test noindex pages using robots analyzer                                                                           │
│                                                                                                                      │
│ Post-Implementation                                                                                                  │
│                                                                                                                      │
│ - Submit updated sitemaps to Google Search Console                                                                   │
│ - Run Lighthouse audit (target >90 on all metrics)                                                                   │
│ - Verify no indexing errors in GSC                                                                                   │
│ - Test all public pages for proper title/description                                                                 │
│ - Verify alternate hreflang links work                                                                               │
│ - Check Core Web Vitals pass (green)                                                                                 │
│ - Monitor organic traffic for 2 weeks                                                                                │
│ - Verify ranking improvements for target keywords                                                                    │
│                                                                                                                      │
│ ---                                                                                                                  │
│ Dependencies & Compatibility                                                                                         │
│                                                                                                                      │
│ No New Package Installation Required                                                                                 │
│                                                                                                                      │
│ - Composables: Pure Vue 3 (already installed)                                                                        │
│ - Meta tags: Native DOM APIs (no package needed)                                                                     │
│ - Structured data: JSON.stringify (native)                                                                           │
│ - Schema generation: PHP classes (native)                                                                            │
│                                                                                                                      │
│ Compatibility                                                                                                        │
│                                                                                                                      │
│ - Laravel 12: ✅ Full compatibility                                                                                  │
│ - Vue 3: ✅ Full compatibility                                                                                       │
│ - Inertia.js 0.11: ✅ Tested pattern                                                                                 │
│ - Tailwind CSS v3: ✅ No CSS changes needed                                                                          │
│ - Multi-language: ✅ Uses existing i18n infrastructure                                                               │
│ - Multi-tenant: ✅ Works with existing slug-based routing                                                            │
│                                                                                                                      │
│ ---                                                                                                                  │
│ Known Limitations & Future Enhancements                                                                              │
│                                                                                                                      │
│ Current Limitations                                                                                                  │
│                                                                                                                      │
│ 1. No SSR setup: Meta tags set client-side (acceptable for SPA, not ideal for initial render)                        │
│ 2. No @vueuse/head: Using DOM API instead (sufficient for current use)                                               │
│ 3. Fixed URL structure: Assumes /{locale}/route pattern (needs validation)                                           │
│ 4. No image optimization: Assumes pre-optimized images (future: consider Cloudinary integration)                     │
│                                                                                                                      │
│ Future Enhancements (Post-MVP)                                                                                       │
│                                                                                                                      │
│ 1. Migrate to Vite with SSR: For better initial render time                                                          │
│ 2. Implement dynamic image generation: OG images with election data                                                  │
│ 3. Add API routes for structured data: Separate JSON endpoints for crawling                                          │
│ 4. Implement caching: Cache sitemap generation for large datasets                                                    │
│ 5. Add Progressive Web App: Service worker for offline support                                                       │
│                                                                                                                      │
│ ---                                                                                                                  │
│ Success Definition                                                                                                   │
│                                                                                                                      │
│ ✅ SEO Optimization Complete When:                                                                                   │
│                                                                                                                      │
│ 1. Technical SEO:                                                                                                    │
│   - All public pages have unique, optimized titles                                                                   │
│   - All pages have meta descriptions (155-160 chars)                                                                 │
│   - Sitemap includes all public content types                                                                        │
│   - Hreflang tags implemented for all languages                                                                      │
│   - robots.txt properly configured                                                                                   │
│   - Canonical URLs correctly set                                                                                     │
│   - No indexing errors in GSC                                                                                        │
│ 2. Content SEO:                                                                                                      │
│   - 3+ target keywords ranking in top 10                                                                             │
│   - Organic traffic increased 30%+ month-over-month                                                                  │
│   - Click-through rate > 3% average                                                                                  │
│   - No 404 errors from search traffic                                                                                │
│ 3. Structured Data:                                                                                                  │
│   - All pages pass Schema.org validation                                                                             │
│   - Rich snippets showing in search results                                                                          │
│   - Event schema working for elections                                                                               │
│   - Breadcrumbs visible in search results                                                                            │
│ 4. Performance:                                                                                                      │
│   - Core Web Vitals: All green                                                                                       │
│   - Lighthouse score: >90                                                                                            │
│   - Page load time: <2.5s LCP                                                                                        │
│   - Mobile friendly: 100% pages                                                                                      │
│ 5. User Engagement:                                                                                                  │
│   - Increased demo election participation                                                                            │
│   - More organizations signing up                                                                                    │
│   - Higher organic to paid traffic ratio                                                                             │
│   - Improved brand awareness metrics                                                                                 │
│                                                                                                                      │
│ ---                                                                                                                  │
│ Plan Created: February 16, 2026                                                                                      │
│ Next Step: User approval via ExitPlanMode                                                                            │
│ Questions: None - comprehensive plan ready for implementation                                                        │
╰──────────────────────────────────────────────────────────────────────────────────────────────────────────────────────╯

● I've reviewed the comprehensive SEO optimization plan for Public Digit. This is a well-structured, phased approach
  that balances SEO goals with the platform's security and multi-tenancy requirements.

  However, before I proceed with implementation, I need to clarify a few critical architectural points to ensure the
────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────
←  [ ] URL Architecture  [ ] Indexing Strategy  [ ] Implementation Scope  √ Submit  →

What is your current URL structure for language routing and tenant organizations?

  1. /{locale}/path structure (e.g., /de/organizations/{slug})
     Language as first path segment, then content routes
  2. /path?lang=locale or cookie-based language selection
     Language via query param or cookie, not in URL
  3. I need to clarify the current routing structure first
     Requires investigation of routes/web.php and how orgs are currently served
> Now i want a publically available user guide for newsletter given in /newsletter-guide . and a navigation button in http://localhost:8000/organisations/namaste-nepal-gmbh/membership/newsletters page. So
  based on the developer guide you wrote, you need to write an extensive user guide for an organisation , election committee  to explain how and when to write newsletter . Write step by step . Also use SEO
  for the page. For this first plan and then implement . To write user guide for newsletter, you can read C:\Users\nabra\OneDrive\Desktop\roshyara\xamp\nrna\nrna-eu\developer_guide\newsletter\ folder files.
  it should be in translate first strategy. For translate first strategy , you can read "C:\Users\nabra\OneDrive\Desktop\roshyara\xamp\nrna\nrna-eu\developer_guide\translation\translate-first-architecture.md"
   and for SEO "C:\Users\nabra\OneDrive\Desktop\roshyara\xamp\nrna\nrna-eu\developer_guide\translation\SEO_IN_TRANSLATION_FILES.md"

● Explore(Explore newsletter developer guides, translation architecture, and SEO patterns)
  ⎿  Done (16 tool uses · 54.5k tokens · 2m 50s)
  (ctrl+o to expand)

● Explore(Explore routing, public pages, and translation patterns in the codebase)
  ⎿  Done (26 tool uses · 53.6k tokens · 2m 39s)
  (ctrl+o to expand)

> also use your fronendtechnology

> skill(frontend-design:frontend-design)

> use your skill front end technology

● Skill(frontend-design)
  ⎿  Successfully loaded skill

● Now let me explore the exact Vue patterns and i18n.js structure to plan precisely.

● Explore(Read key files for exact implementation patterns)
  ⎿  Done (8 tool uses · 54.7k tokens · 4m 8s)
  (ctrl+o to expand)

● Read(C:\Users\nabra\OneDrive\Desktop\roshyara\xamp\nrna\nrna-eu\resources\js\Pages\Tutorials\ElectionSettings.vue · lines 1-50)
  ⎿  Read 50 lines

● Read(C:\Users\nabra\OneDrive\Desktop\roshyara\xamp\nrna\nrna-eu\resources\js\i18n.js · lines 1-80)
  ⎿  Read 80 lines

● Read(C:\Users\nabra\OneDrive\Desktop\roshyara\xamp\nrna\nrna-eu\routes\web.php · lines 1-60)
  ⎿  Read 60 lines

● Search(pattern: "pages\s*=|pageKey|newsletter-guide|tutorials\.election|help/election", path: "C:\Users\nabra\OneDrive\Desktop\roshyara\xamp\nrna\nrna-eu\app\Http\Middleware\InjectPageMeta.php")
  ⎿  Found 0 lines

● Search(pattern: "help/|tutorial|newsletter-guide|about|faq", path: "C:\Users\nabra\OneDrive\Desktop\roshyara\xamp\nrna\nrna-eu\routes\web.php")
  ⎿  Found 30 lines
     266-// About page
     267:Route::get('/about', function () {
     268-    return Inertia::render('About');
     269:})->name('about');
     270-
     271-// FAQ page
     272:Route::get('/faq', function () {
     273-    return Inertia::render('FAQ');
     274:})->name('faq');
     275-
     276:// Election settings tutorial — public, no auth required
     277:Route::get('/help/election-setup', function () {
     278-    return Inertia::render('Tutorials/ElectionSettings');
     279:})->name('tutorials.election-settings');
     280-
     281:// Voter verification tutorial — public, no auth required
     282:Route::get('/help/voters-verification_guide', function () {
     283-    return Inertia::render('Tutorials/VotersManagement');
     284:})->name('tutorials.voters-verification-guide');
     285-
     286:// Membership modes tutorial — public, no auth required
     287:Route::get('/help/membership-modes', function () {
     288-    return Inertia::render('Tutorials/MembershipModes');
     289:})->name('tutorials.membership-modes');
     290-
     --
     497-             ->name('organisations.members.import');
     498:        Route::get('/organisations/{slug}/members/import/tutorial', [MemberImportController::class, 'tutorial'])
     499:             ->name('organisations.members.import.tutorial');
     500-        Route::get('/organisations/{slug}/members/import/template', [MemberImportController::class, 'template'])

● Now I have everything needed. Let me write the plan.

● Reading Plan(C:\Users\nabra\.claude\plans\optimized-moseying-sutherland.md)
  ⎿  Read 353 lines

● Now I'll write the new plan, overwriting the old completed one.

● Updated plan
  ⎿  Added 1 line, removed 1 line
      1 -# Architecture Plan: Newsletter Audience Segmentation (v2 — Architect-Reviewed)
      1 +# Plan: Public Newsletter User Guide Page (`/newsletter-guide`)
      2
      3  ## Context
      4

● Updated plan
  ⎿  Added 158 lines, removed 290 lines
        2
        3  ## Context
        4
        5 -The existing newsletter system (`OrganisationNewsletter` / `NewsletterService`) sends to **all active members** of an organisation — no segmentation exists. The request is to extend it so adm
          -ins can target specific subgroups: election voters, committees, non-voters, participants, members by tier or fee status, etc.
        5 +Organisation admins and election committees need a clear, publicly accessible reference guide for the newsletter feature. Currently there is no user-facing documentation — only developer guid
          +es in `developer_guide/newsletter/`. The task is to:
        6
        7 -This v2 incorporates a full architect review: TDD compliance, GDPR/DSGVO, accessibility (BITV 2.0 / WCAG 2.1), rate limiting, performance (caching + batch), and a redesigned step-by-step audi
          -ence builder UI.
        7 +1. Create a beautifully designed, fully translated public guide page at `/newsletter-guide`
        8 +2. Add a "Guide" navigation button to the newsletter management page (`Newsletter/Index.vue`)
        9
        9 ----
       10 +Source content comes from `developer_guide/newsletter/user_guide.md` and `tutorial_how_to_send_a_newsletter.md`. The page must follow **Translate-First architecture** (all strings in locale J
          +SON, no hard-coded text) and include a **SEO section** in each locale file.
       11
       11 -## Current Architecture (Reference)
       12 -
       13 -```
       14 -OrganisationNewsletter  (status, subject, html_content, …)
       15 -    └── NewsletterRecipient[]  (member_id FK NOT NULL, email, name, status, sent_at)
       16 -
       17 -NewsletterService::dispatch() — queries Member where status=active only
       18 -```
       19 -
       20 -`member_id` is currently NOT NULL — blocks non-member election participants from receiving newsletters.
       21 -
       12  ---
       13
       24 -## Chosen Approach: Audience Type + Meta JSON + GDPR columns + Consent tracking
       14 +## Aesthetic Direction — "Refined Academic Editorial"
       15
       26 -### Schema changes
       16 +**Not** a generic purple-gradient AI-slop page. This is a documentation page that feels like an authoritative, well-designed publication.
       17
       28 -**`organisation_newsletters`** — add:
       29 -- `audience_type` string, default `all_members`
       30 -- `audience_meta` JSON nullable (holds `election_id` etc.)
       31 -- `scheduled_for` timestamp nullable (future: schedule send)
       18 +- **Background**: Warm cream `#FDFAF6` with subtle paper texture via CSS noise
       19 +- **Primary text**: Deep prussian blue `#1B2E4B`
       20 +- **Accent**: Warm amber/gold `#D97706` — used for step numbers, highlights, section dividers
       21 +- **Typography**:
       22 +  - Display/headings: `Crimson Pro` (serif, Google Font) — literary, trustworthy
       23 +  - Body: `DM Sans` — clean, readable, modern sans-serif
       24 +- **Layout**: Two-column (sticky ToC sidebar left + scrollable content right) on desktop; single column mobile
       25 +- **Visual elements**:
       26 +  - Lifecycle timeline: horizontal flow diagram (SVG) with animated connectors (Draft → Queued → Processing → Completed/Failed/Cancelled)
       27 +  - Step cards: large amber-circle step numbers with vertical connector lines
       28 +  - Audience badges: pill chips (Organisation Admin / Election Committee / Organisation Owner)
       29 +  - Warning/tip callout boxes with left border accents
       30 +  - Scroll-spy ToC with active link highlighting
       31 +  - Section reveal animations on scroll (`IntersectionObserver`)
       32 +  - A "back to newsletters" sticky link that appears after scrolling past hero
       33
       33 -**`newsletter_recipients`** — change + add:
       34 -- `member_id` → make nullable (was NOT NULL)
       35 -- `user_id` uuid nullable FK → users (for non-member participants)
       36 -- `opened_at` timestamp nullable
       37 -- `clicked_at` timestamp nullable
       38 -- `consent_given_at` timestamp nullable
       39 -- `consent_source` string nullable (`member_agreement`, `election_participation`, `explicit_opt_in`)
       40 -
       34  ---
       35
       43 -## Audience Segments Catalogue
       36 +## Files to Create
       37
       45 -| `audience_type`              | Source table(s)                                  | Key filter                                          |
       46 -|------------------------------|--------------------------------------------------|-----------------------------------------------------|
       47 -| `all_members`                | `members`                                        | `status=active` (current behaviour, default)        |
       48 -| `members_full`               | `members` + `membership_types`                   | `grants_voting_rights=true`, `status=active`        |
       49 -| `members_associate`          | `members` + `membership_types`                   | `grants_voting_rights=false`, `status=active`       |
       50 -| `members_overdue`            | `members`                                        | `fees_status IN (unpaid,partial)`, `status=active`  |
       51 -| `election_voters`            | `election_memberships`                           | `election_id=X`, `role=voter`, `status=active`      |
       52 -| `election_not_voted`         | `election_memberships`                           | `role=voter`, `has_voted=false`, `status=active`    |
       53 -| `election_voted`             | `election_memberships`                           | `role=voter`, `has_voted=true`                      |
       54 -| `election_candidates`        | `election_memberships`                           | `election_id=X`, `role=candidate`                   |
       55 -| `election_observers`         | `election_memberships`                           | `election_id=X`, `role=observer`                    |
       56 -| `election_committee`         | `election_officers`                              | `election_id=X`, `status=active`                    |
       57 -| `election_all`               | `election_memberships` UNION `election_officers` | all roles in election                               |
       58 -| `org_participants_staff`     | `organisation_participants`                      | `participant_type=staff`                            |
       59 -| `org_participants_guests`    | `organisation_participants`                      | `participant_type=guest`                            |
       60 -| `org_admins`                 | `user_organisation_roles`                        | `role IN (admin, owner, commission)`                |
       38 +### 1. Vue Page
       39 +**`resources/js/Pages/Guides/NewsletterGuide.vue`**
       40
       62 -All queries include `organisation_id` scoping and respect `newsletter_unsubscribed_at` / `newsletter_bounced_at`.
       41 +Pattern: mirrors `resources/js/Pages/Tutorials/ElectionSettings.vue`
       42 +- `PublicDigitHeader` + `PublicDigitFooter` (no layout wrapper — same as tutorial pages)
       43 +- `useMeta({ pageKey: 'newsletter-guide', url: '/newsletter-guide', type: 'article' })`
       44 +- Locale loaded via `import.meta.glob('../../locales/pages/NewsletterGuide/*.json', { eager: true })`
       45 +- Computed `t` ref resolves to current locale JSON
       46 +- `useI18n()` for `locale` reactive value
       47 +- Sections: Hero, Audience Tags, Who Can Use, Lifecycle Timeline, Step-by-Step (compose → send → monitor), Election Committee section, Unsubscribe, Troubleshoot/FAQ, CTA
       48
       64 ----
       49 +### 2. Locale Files (Translate-First)
       50
       66 -## Implementation Plan (TDD — tests FIRST)
       51 +All three files must have **identical key structure**. Every string comes from the locale file — zero hard-coded text in Vue.
       52
       68 -### Step 1 — Tests First (MANDATORY)
       53 +**`resources/js/locales/pages/NewsletterGuide/en.json`**
       54 +**`resources/js/locales/pages/NewsletterGuide/de.json`**
       55 +**`resources/js/locales/pages/NewsletterGuide/np.json`**
       56
       70 -**`tests/Feature/Newsletter/NewsletterAudienceSegmentationTest.php`**
       71 -
       72 -**Positive paths:**
       57 +JSON structure:
       58 +```json
       59 +{
       60 +  "seo": {
       61 +    "title": "Newsletter Guide — Public Digit",
       62 +    "description": "Step-by-step guide for organisation admins and election committees on how to write and send newsletters.",
       63 +    "keywords": "newsletter, organisation, election committee, email, admin guide",
       64 +    "robots": "index, follow"
       65 +  },
       66 +  "meta": {
       67 +    "badge_label": "User Guide",
       68 +    "read_time": "8 min read",
       69 +    "audience_admin": "Organisation Admin",
       70 +    "audience_owner": "Organisation Owner",
       71 +    "audience_committee": "Election Committee"
       72 +  },
       73 +  "hero": {
       74 +    "title": "Newsletter Guide",
       75 +    "subtitle": "Everything you need to know about sending newsletters to your organisation",
       76 +    "description": "..."
       77 +  },
       78 +  "toc": { ... },
       79 +  "who_can_use": { "title": "...", "roles": [...] },
       80 +  "lifecycle": { "title": "...", "stages": { "draft": {...}, "queued": {...}, ... } },
       81 +  "compose": { "title": "...", "steps": [...], "tips": [...] },
       82 +  "send": { "title": "...", "steps": [...], "warning": "..." },
       83 +  "monitor": { "title": "...", "counters": {...}, "delivery_time": "..." },
       84 +  "election_committee": { "title": "...", "intro": "...", "use_cases": [...] },
       85 +  "unsubscribe": { "title": "...", "description": "...", "note": "..." },
       86 +  "faq": { "title": "...", "items": [ {"question": "...", "answer": "..."}, ... ] },
       87 +  "cta": { "title": "...", "button": "...", "link_text": "..." }
       88 +}
       89  ```
       74 -test_dispatch_all_members_targets_active_members_only()
       75 -test_dispatch_full_members_excludes_associate_members()
       76 -test_dispatch_election_voters_uses_election_membership_table()
       77 -test_dispatch_election_not_voted_excludes_already_voted()
       78 -test_dispatch_election_committee_uses_election_officers_table()
       79 -test_preview_count_returns_correct_recipient_count()
       80 -test_non_member_election_participant_receives_newsletter_without_member_id()
       81 -test_store_creates_newsletter_with_audience_type_and_meta()
       82 -```
       90
       84 -**Negative / edge-case paths (required by TDD discipline):**
       85 -```
       86 -test_dispatch_respects_newsletter_unsubscribe_even_in_election_segments()
       87 -test_dispatch_respects_hard_bounce_exclusion()
       88 -test_store_validates_election_id_required_for_election_audience_types()
       89 -test_store_rejects_unknown_audience_type()
       90 -test_store_rate_limits_max_10_newsletters_per_hour()
       91 -test_preview_count_rate_limits_30_requests_per_minute()
       92 -test_dispatch_handles_audience_with_zero_recipients_gracefully()
       93 -test_dispatch_does_not_duplicate_recipients_across_overlapping_segments()
       94 -test_audience_resolution_result_is_cached_for_5_minutes()
       95 -test_concurrent_newsletter_dispatches_do_not_duplicate_recipients()
       96 -test_gdpr_consent_source_stored_per_recipient()
       97 -test_dispatch_ignores_cache_to_ensure_fresh_data_at_send_time()
       98 -test_multi_segment_selection_removes_duplicate_emails()
       99 -```
       91 +Content sourced from:
       92 +- `developer_guide/newsletter/user_guide.md` — all sections
       93 +- `developer_guide/newsletter/tutorial_how_to_send_a_newsletter.md` — 10 send steps, troubleshooting
       94 +- `developer_guide/newsletter/03-state-machine.md` — lifecycle states
       95 +- `developer_guide/newsletter/07-unsubscribe-bounce.md` — unsubscribe details
       96
       97  ---
       98
      103 -### Step 2 — Migrations
       99 +## Files to Edit
      100
      105 -**File 1:** `…_add_audience_to_organisation_newsletters.php`
      101 +### 3. Route
      102 +**`routes/web.php`** — add after the existing tutorial routes (~line 290):
      103  ```php
      107 -$table->string('audience_type')->default('all_members')->after('status');
      108 -$table->json('audience_meta')->nullable()->after('audience_type');
      109 -$table->timestamp('scheduled_for')->nullable()->after('queued_at');
      104 +// Newsletter user guide — public, no auth required
      105 +Route::get('/newsletter-guide', function () {
      106 +    return Inertia::render('Guides/NewsletterGuide');
      107 +})->name('guides.newsletter-guide');
      108  ```
      109
      112 -**File 2:** `…_update_newsletter_recipients_for_segmentation.php`
      113 -```php
      114 -$table->uuid('member_id')->nullable()->change();
      115 -$table->uuid('user_id')->nullable()->after('member_id');
      116 -$table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
      117 -$table->timestamp('opened_at')->nullable();
      118 -$table->timestamp('clicked_at')->nullable();
      119 -$table->timestamp('consent_given_at')->nullable();
      120 -$table->string('consent_source')->nullable();
      121 -```
      110 +### 4. Newsletter Index — Navigation Button
      111 +**`resources/js/Pages/Organisations/Membership/Newsletter/Index.vue`**
      112
      123 ----
      113 +Add a "User Guide" button next to the existing "Compose" button in the page header. Uses existing inline translations object pattern.
      114
      125 -### Step 3 — Model Updates
      126 -
      127 -**`app/Models/OrganisationNewsletter.php`**
      128 -- Add `audience_type`, `audience_meta`, `scheduled_for` to `$fillable`
      129 -- Cast `audience_meta` → `array`
      130 -- Add `audienceLabel()` accessor
      131 -
      132 -**`app/Models/NewsletterRecipient.php`**
      133 -- Add `user_id`, `opened_at`, `clicked_at`, `consent_given_at`, `consent_source` to `$fillable`
      134 -- Add nullable `belongsTo(User::class)` relationship
      135 -
      136 ----
      137 -
      138 -### Step 4 — Service Layer
      139 -
      140 -**`app/Services/NewsletterService.php`**
      141 -
      142 -```php
      143 -const AUDIENCE_TYPES = [ /* 14 keys listed above */ ];
      144 -const CACHE_TTL = 300;   // 5 min audience cache (previewCount only)
      145 -const BATCH_SIZE = 500;  // recipients per insert chunk
      115 +Add to translations object (en/de/np):
      116 +```js
      117 +guide_button: 'User Guide',  // de: 'Benutzerhandbuch', np: 'प्रयोगकर्ता गाइड'
      118  ```
      119
      148 -**`resolveAudience()` — with `forceRefresh` flag:**
      149 -```php
      150 -public function resolveAudience(Organisation $org, string $type, array $meta = [], bool $forceRefresh = false): Collection
      151 -{
      152 -    $cacheKey = "audience:{$org->id}:{$type}:" . md5(json_encode($meta));
      153 -
      154 -    if ($forceRefresh) {
      155 -        Cache::forget($cacheKey);
      156 -    }
      157 -
      158 -    return Cache::remember($cacheKey, self::CACHE_TTL, fn() =>
      159 -        $this->buildAudienceQuery($org, $type, $meta)->get()
      160 -    );
      161 -}
      120 +Add button markup near the "Compose" button:
      121 +```html
      122 +<a href="/newsletter-guide" target="_blank"
      123 +   class="inline-flex items-center gap-2 rounded-lg border border-amber-300 bg-amber-50 px-4 py-2.5 text-amber-700 text-sm font-medium hover:bg-amber-100 transition-colors">
      124 +  <BookOpenIcon class="w-4 h-4" />
      125 +  {{ t.guide_button }}
      126 +</a>
      127  ```
      128
      164 -**`buildAudienceQuery()` — always applies DISTINCT on email:**
      165 -```php
      166 -// Final step in every query path:
      167 -return $query->distinct('email');
      168 -// Prevents duplicate delivery when Candidate is also a Voter
      169 -// or when multi-segment results overlap
      170 -```
      129 +Also add `BookOpenIcon` import from `@heroicons/vue/24/outline`.
      130
      172 -**`dispatch()` — bypasses cache + batch-inserts with deadlock protection:**
      173 -```php
      174 -public function dispatch(OrganisationNewsletter $newsletter): void
      175 -{
      176 -    // ALWAYS bypass cache on actual send — must use fresh data
      177 -    $audience = $this->resolveAudience(
      178 -        $newsletter->organisation,
      179 -        $newsletter->audience_type,
      180 -        $newsletter->audience_meta ?? [],
      181 -        forceRefresh: true    // ← cache bypass
      182 -    );
      183 -
      184 -    foreach ($audience->chunk(self::BATCH_SIZE) as $chunk) {
      185 -        $rows = $chunk->map(fn($r) => [
      186 -            'id'         => Str::uuid(),   // PHP-generated UUID avoids index contention
      187 -            'newsletter_id' => $newsletter->id,
      188 -            'email'      => $r->email,
      189 -            'name'       => $r->name,
      190 -            'member_id'  => $r->member_id,
      191 -            'user_id'    => $r->user_id,
      192 -            'status'     => 'pending',
      193 -            'consent_source' => $r->consent_source ?? 'member_agreement',
      194 -            'consent_given_at' => now(),
      195 -            'created_at' => now(),
      196 -            'updated_at' => now(),
      197 -        ])->toArray();
      198 -
      199 -        // Deadlock-safe: retry once with small backoff
      200 -        retry(2, fn() => NewsletterRecipient::insert($rows), 100);
      201 -    }
      202 -}
      203 -```
      204 -
      205 -**`previewCount()` uses cache** (fast UI feedback, staleness is acceptable here).
      206 -
      131  ---
      132
      209 -### Step 5 — Controller Updates
      133 +## Vue Page Structure (Detailed)
      134
      211 -**`app/Http/Controllers/Membership/OrganisationNewsletterController.php`**
      135 +```vue
      136 +<script setup>
      137 +import { useMeta } from '@/composables/useMeta'
      138 +import { computed, onMounted, ref } from 'vue'
      139 +import { useI18n } from 'vue-i18n'
      140 +import PublicDigitHeader from '@/Components/Jetstream/PublicDigitHeader.vue'
      141 +import PublicDigitFooter from '@/Components/Jetstream/PublicDigitFooter.vue'
      142
      213 -**`create()`** — pass to Inertia:
      214 -- `elections` (id, name, status)
      215 -- `audienceTypes` (NewsletterService::AUDIENCE_TYPES)
      143 +useMeta({ pageKey: 'newsletter-guide', url: '/newsletter-guide', type: 'article' })
      144
      217 -**`store()`** — add:
      218 -- Rate limit: max 10 newsletters/hour per organisation (Cache::increment key)
      219 -- Validation: `audience_type` in AUDIENCE_TYPES, `audience_meta.election_id` required_if election segment
      145 +const { locale } = useI18n()
      146 +const localeModule = import.meta.glob('../../locales/pages/NewsletterGuide/*.json', { eager: true })
      147 +const t = computed(() => {
      148 +  const key = `../../locales/pages/NewsletterGuide/${locale.value}.json`
      149 +  return localeModule[key]?.default || localeModule['../../locales/pages/NewsletterGuide/en.json'].default
      150 +})
      151 +// activeSection ref for scroll-spy ToC
      152 +// IntersectionObserver for section reveal animations
      153 +</script>
      154 +```
      155
      221 -**New `previewCount()`:**
      222 -- Rate limit: 30 req/min per user via `RateLimiter`
      223 -- Returns `{count: int, sample: [{name, email}]}`
      224 -- Caches via `resolveAudience()` (same 5 min cache)
      156 +**Section order in template:**
      157 +1. `PublicDigitHeader` (no breadcrumbs)
      158 +2. **Hero** — title, subtitle, audience badge pills, read-time, description
      159 +3. **Two-column layout starts**: sticky ToC sidebar | content
      160 +4. **Who Can Use** — role cards with icons
      161 +5. **Campaign Lifecycle** — SVG timeline: Draft → Queued → Processing → Completed / Failed / Cancelled (with colours)
      162 +6. **Step-by-Step: Write Your Newsletter** (steps 1–3: navigate, compose, preview count)
      163 +7. **Step-by-Step: Send and Monitor** (steps 4–6: review, send, watch progress)
      164 +8. **For Election Committees** — dedicated section with election-specific use cases
      165 +9. **Unsubscribes & Bounces** — what members see, what admins should know
      166 +10. **FAQ** — collapsible accordion items
      167 +11. **CTA** — "Ready? Go to your newsletters →" with link to org dashboard
      168 +12. `PublicDigitFooter`
      169
      170  ---
      171
      228 -### Step 6 — Routes
      172 +## SEO Wiring
      173
      230 -**`routes/organisations.php`** — add before existing newsletter resource routes:
      231 -```php
      232 -Route::get('/newsletters/preview-count', [OrganisationNewsletterController::class, 'previewCount'])
      233 -     ->name('newsletters.previewCount');
      234 -```
      174 +The `useMeta()` composable reads the locale JSON's `seo` section server-side via `InjectPageMeta` middleware. No additional PHP changes needed — this is the same mechanism used by `Tutorials/
          +ElectionSettings`.
      175
      236 ----
      176 +**SEO values (all locales, ≤60 title, ≤160 description):**
      177
      238 -### Step 7 — Vue: Audience Builder (Step-by-Step Wizard)
      178 +| Field | EN | DE |
      179 +|-------|----|----|
      180 +| title | `Newsletter Guide — Public Digit` (35 chars) | `Newsletter-Handbuch — Public Digit` (36 chars) |
      181 +| description | `Step-by-step guide for organisation admins and election committees on writing and sending newsletters effectively.` (115 chars) | (German equivalent) |
      182 +| robots | `index, follow` | `index, follow` |
      183
      240 -**`resources/js/Pages/Organisations/Membership/Newsletter/Create.vue`** (and Edit)
      241 -
      242 -Replace single dropdown with a **3-step accessible wizard**:
      243 -
      244 -**Step 1 — Choose category (radio group):**
      245 -```
      246 -○ Organisation Members
      247 -○ Election Participants       [reveals step 2]
      248 -○ Staff / Guests
      249 -```
      250 -
      251 -**Step 2 — Election picker** (only when election category):
      252 -```
      253 -Select election: [ dropdown with election.name + status ]
      254 -```
      255 -
      256 -**Step 3 — Participant type checkboxes** (multi-select within election):
      257 -```
      258 -☑ Voters (124)          ☑ Who haven't voted (89)
      259 -☐ Who already voted (35) ☐ Candidates (12)
      260 -☐ Observers (8)          ☐ Committee members (5)
      261 -```
      262 -
      263 -**Accessibility requirements (BITV 2.0 / WCAG 2.1):**
      264 -```vue
      265 -<form @submit.prevent="handleSubmit" aria-labelledby="newsletter-form-title">
      266 -  <fieldset>
      267 -    <legend>{{ t.select_audience }}</legend>
      268 -    <div role="radiogroup" aria-label="Audience category">
      269 -      <!-- radio inputs with :aria-describedby -->
      270 -    </div>
      271 -  </fieldset>
      272 -
      273 -  <!-- Live region for count updates -->
      274 -  <div role="status" aria-live="polite" aria-atomic="true">
      275 -    {{ recipientCount }} {{ t.recipients_will_receive }}
      276 -  </div>
      277 -
      278 -  <!-- Confirmation modal for > 1,000 recipients -->
      279 -  <ConfirmSendModal v-if="showConfirm" :count="recipientCount" @confirm="doSend" @cancel="showConfirm = false" />
      280 -</form>
      281 -```
      282 -
      283 -**Focus management when election picker appears (BITV 2.0 requirement):**
      284 -```javascript
      285 -// Move focus to election picker when category changes to 'election'
      286 -watch(category, (newVal) => {
      287 -  if (newVal === 'election') {
      288 -    nextTick(() => {
      289 -      document.getElementById('election-select')?.focus();
      290 -    });
      291 -  }
      292 -});
      293 -```
      294 -
      295 -**Other UI features:**
      296 -- **Confirmation modal** when recipient count > 1,000: "You are about to email 12,347 people. Continue?"
      297 -- **Preview panel**: shows 5 sample recipient names/emails before sending
      298 -- Keyboard shortcuts: `Ctrl+Enter` save draft, `Ctrl+Shift+Enter` send
      299 -
      300 -**`Index.vue`** — add audience type badge per newsletter row
      301 -**`Show.vue`** — show audience details + election name from `audience_meta`
      302 -
      184  ---
      185
      305 -## Critical Files
      186 +## Critical Files Summary
      187
      188  | Action | File |
      189  |--------|------|
      309 -| CREATE | `database/migrations/…_add_audience_to_organisation_newsletters.php` |
      310 -| CREATE | `database/migrations/…_update_newsletter_recipients_for_segmentation.php` |
      311 -| EDIT   | `app/Models/OrganisationNewsletter.php` |
      312 -| EDIT   | `app/Models/NewsletterRecipient.php` |
      313 -| EDIT   | `app/Services/NewsletterService.php` |
      314 -| EDIT   | `app/Http/Controllers/Membership/OrganisationNewsletterController.php` |
      315 -| EDIT   | `resources/js/Pages/Organisations/Membership/Newsletter/Create.vue` |
      316 -| EDIT   | `resources/js/Pages/Organisations/Membership/Newsletter/Edit.vue` |
      190 +| CREATE | `resources/js/Pages/Guides/NewsletterGuide.vue` |
      191 +| CREATE | `resources/js/locales/pages/NewsletterGuide/en.json` |
      192 +| CREATE | `resources/js/locales/pages/NewsletterGuide/de.json` |
      193 +| CREATE | `resources/js/locales/pages/NewsletterGuide/np.json` |
      194 +| EDIT   | `routes/web.php` |
      195  | EDIT   | `resources/js/Pages/Organisations/Membership/Newsletter/Index.vue` |
      318 -| EDIT   | `resources/js/Pages/Organisations/Membership/Newsletter/Show.vue` |
      319 -| CREATE | `tests/Feature/Newsletter/NewsletterAudienceSegmentationTest.php` |
      196
      321 ----
      197 +**Source content files** (read-only reference):
      198 +- `developer_guide/newsletter/user_guide.md`
      199 +- `developer_guide/newsletter/tutorial_how_to_send_a_newsletter.md`
      200 +- `developer_guide/newsletter/03-state-machine.md`
      201 +- `developer_guide/newsletter/07-unsubscribe-bounce.md`
      202
      323 -## Architecture Decisions
      203 +**Pattern reference** (read-only):
      204 +- `resources/js/Pages/Tutorials/ElectionSettings.vue` — locale glob pattern
      205 +- `resources/js/locales/pages/Tutorials/ElectionSettings/en.json` — JSON structure
      206
      325 -**Why inline audience type (not a separate `NewsletterAudience` model)?**
      326 -Inline JSON metadata delivers the same power with less friction. A named reusable `NewsletterAudience` model can be layered on top later without changing this design.
      327 -
      328 -**Why make `member_id` nullable?**
      329 -`ElectionMembership` + `ElectionOfficer` + `OrganisationParticipant` link via `user_id`, not `member_id`. Non-member participants must be reachable. `email` + `name` are already stored on `Ne
          -wsletterRecipient`, so delivery works regardless.
      330 -
      331 -**Why a dedicated `previewCount` endpoint?**
      332 -Audience resolution can be expensive for large orgs. Returning only `count` + 5-row sample gives instant UI feedback without transmitting large lists to the browser.
      333 -
      334 -**Why batch insert in chunks of 500?**
      335 -Prevents memory exhaustion for large newsletters (10,000+ recipients). Each `INSERT` stays under MySQL's `max_allowed_packet`.
      336 -
      337 -**Why radio group + checkboxes instead of a single dropdown?**
      338 -A grouped dropdown is inaccessible for screen readers and visually confusing when subcategories depend on a separate election picker. A step-by-step wizard is clearer, accessible, and allows
          -multi-select within an election (e.g. voters + committee in one send).
      339 -
      207  ---
      208
      209  ## Verification
      210
      344 -1. `php artisan test tests/Feature/Newsletter/` — all 19 tests pass, zero existing regressions
      345 -2. Create newsletter → pick Election Participants → select election → choose "Voters who haven't voted" → badge shows correct count (matches DB query)
      346 -3. Count > 1,000 → confirmation modal appears before send
      347 -4. Send → `newsletter_recipients` rows show `user_id` (null `member_id`) for non-member participants + `consent_source` populated
      348 -5. Existing "All Members" newsletters still work unchanged (backward-compatible default)
      349 -6. `Index.vue` shows coloured audience badge per newsletter row
      350 -7. Unsubscribe still honoured for all member-based segments
      351 -8. Rate limit: create 11 newsletters in 1 hour → 12th returns 429
      352 -9. Preview endpoint: 31 rapid requests → 31st returns 429
      211 +1. Visit `http://localhost:8000/newsletter-guide` → page loads without auth, correct language
      212 +2. Switch language (de/en/np) → all text updates, no hard-coded strings visible
      213 +3. Check page source: `<title>Newsletter Guide — Public Digit</title>`, correct meta description
      214 +4. Check `robots` meta tag: `index, follow`
      215 +5. Visit `http://localhost:8000/organisations/namaste-nepal-gmbh/membership/newsletters` → "User Guide" button visible in header, opens `/newsletter-guide` in new tab
      216 +6. Scroll down the guide page → ToC highlights active section, sections animate in
      217 +7. Test all 3 locales match — no missing keys (de/np fall back to en if key missing)
      218 +8. Responsive: mobile view collapses sidebar ToC to top accordion
      219 +9. Lifecycle diagram renders correctly with all 6 states
      220 +

  10 tasks (9 done, 1 open)
  √ Write 21 NewsletterAudienceSegmentationTest test cases (TDD)
  √ Create migrations for audience segmentation
  √ Update OrganisationNewsletter & NewsletterRecipient models
  √ Implement NewsletterService::resolveAudience() with caching
  √ Update OrganisationNewsletterController with segmentation
  √ Add previewCount route
  √ Build 3-step audience wizard Vue component
  √ Update Newsletter Edit/Index/Show Vue components
  √ Run full test suite & verify all 21 tests pass
  □ Manual UAT: test audience segmentation end-to-end

─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────
 Ready to code?

 Here is Claude's plan:
╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌
 Plan: Public Newsletter User Guide Page (/newsletter-guide)

 Context

 Organisation admins and election committees need a clear, publicly accessible reference guide for the newsletter feature. Currently there is no user-facing documentation — only developer guides in
 developer_guide/newsletter/. The task is to:

 1. Create a beautifully designed, fully translated public guide page at /newsletter-guide
 2. Add a "Guide" navigation button to the newsletter management page (Newsletter/Index.vue)

 Source content comes from developer_guide/newsletter/user_guide.md and tutorial_how_to_send_a_newsletter.md. The page must follow Translate-First architecture (all strings in locale JSON, no hard-coded text)
  and include a SEO section in each locale file.

 ---
 Aesthetic Direction — "Refined Academic Editorial"

 Not a generic purple-gradient AI-slop page. This is a documentation page that feels like an authoritative, well-designed publication.

 - Background: Warm cream #FDFAF6 with subtle paper texture via CSS noise
 - Primary text: Deep prussian blue #1B2E4B
 - Accent: Warm amber/gold #D97706 — used for step numbers, highlights, section dividers
 - Typography:
   - Display/headings: Crimson Pro (serif, Google Font) — literary, trustworthy
   - Body: DM Sans — clean, readable, modern sans-serif
 - Layout: Two-column (sticky ToC sidebar left + scrollable content right) on desktop; single column mobile
 - Visual elements:
   - Lifecycle timeline: horizontal flow diagram (SVG) with animated connectors (Draft → Queued → Processing → Completed/Failed/Cancelled)
   - Step cards: large amber-circle step numbers with vertical connector lines
   - Audience badges: pill chips (Organisation Admin / Election Committee / Organisation Owner)
   - Warning/tip callout boxes with left border accents
   - Scroll-spy ToC with active link highlighting
   - Section reveal animations on scroll (IntersectionObserver)
   - A "back to newsletters" sticky link that appears after scrolling past hero

 ---
 Files to Create

 1. Vue Page

 resources/js/Pages/Guides/NewsletterGuide.vue

 Pattern: mirrors resources/js/Pages/Tutorials/ElectionSettings.vue
 - PublicDigitHeader + PublicDigitFooter (no layout wrapper — same as tutorial pages)
 - useMeta({ pageKey: 'newsletter-guide', url: '/newsletter-guide', type: 'article' })
 - Locale loaded via import.meta.glob('../../locales/pages/NewsletterGuide/*.json', { eager: true })
 - Computed t ref resolves to current locale JSON
 - useI18n() for locale reactive value
 - Sections: Hero, Audience Tags, Who Can Use, Lifecycle Timeline, Step-by-Step (compose → send → monitor), Election Committee section, Unsubscribe, Troubleshoot/FAQ, CTA

 2. Locale Files (Translate-First)

 All three files must have identical key structure. Every string comes from the locale file — zero hard-coded text in Vue.

 resources/js/locales/pages/NewsletterGuide/en.json
 resources/js/locales/pages/NewsletterGuide/de.json
 resources/js/locales/pages/NewsletterGuide/np.json

 JSON structure:
 {
   "seo": {
     "title": "Newsletter Guide — Public Digit",
     "description": "Step-by-step guide for organisation admins and election committees on how to write and send newsletters.",
     "keywords": "newsletter, organisation, election committee, email, admin guide",
     "robots": "index, follow"
   },
   "meta": {
     "badge_label": "User Guide",
     "read_time": "8 min read",
     "audience_admin": "Organisation Admin",
     "audience_owner": "Organisation Owner",
     "audience_committee": "Election Committee"
   },
   "hero": {
     "title": "Newsletter Guide",
     "subtitle": "Everything you need to know about sending newsletters to your organisation",
     "description": "..."
   },
   "toc": { ... },
   "who_can_use": { "title": "...", "roles": [...] },
   "lifecycle": { "title": "...", "stages": { "draft": {...}, "queued": {...}, ... } },
   "compose": { "title": "...", "steps": [...], "tips": [...] },
   "send": { "title": "...", "steps": [...], "warning": "..." },
   "monitor": { "title": "...", "counters": {...}, "delivery_time": "..." },
   "election_committee": { "title": "...", "intro": "...", "use_cases": [...] },
   "unsubscribe": { "title": "...", "description": "...", "note": "..." },
   "faq": { "title": "...", "items": [ {"question": "...", "answer": "..."}, ... ] },
   "cta": { "title": "...", "button": "...", "link_text": "..." }
 }

 Content sourced from:
 - developer_guide/newsletter/user_guide.md — all sections
 - developer_guide/newsletter/tutorial_how_to_send_a_newsletter.md — 10 send steps, troubleshooting
 - developer_guide/newsletter/03-state-machine.md — lifecycle states
 - developer_guide/newsletter/07-unsubscribe-bounce.md — unsubscribe details

 ---
 Files to Edit

 3. Route

 routes/web.php — add after the existing tutorial routes (~line 290):
 // Newsletter user guide — public, no auth required
 Route::get('/newsletter-guide', function () {
     return Inertia::render('Guides/NewsletterGuide');
 })->name('guides.newsletter-guide');

 4. Newsletter Index — Navigation Button

 resources/js/Pages/Organisations/Membership/Newsletter/Index.vue

 Add a "User Guide" button next to the existing "Compose" button in the page header. Uses existing inline translations object pattern.

 Add to translations object (en/de/np):
 guide_button: 'User Guide',  // de: 'Benutzerhandbuch', np: 'प्रयोगकर्ता गाइड'

 Add button markup near the "Compose" button:
 <a href="/newsletter-guide" target="_blank"
    class="inline-flex items-center gap-2 rounded-lg border border-amber-300 bg-amber-50 px-4 py-2.5 text-amber-700 text-sm font-medium hover:bg-amber-100 transition-colors">
   <BookOpenIcon class="w-4 h-4" />
   {{ t.guide_button }}
 </a>

 Also add BookOpenIcon import from @heroicons/vue/24/outline.

 ---
 Vue Page Structure (Detailed)

 <script setup>
 import { useMeta } from '@/composables/useMeta'
 import { computed, onMounted, ref } from 'vue'
 import { useI18n } from 'vue-i18n'
 import PublicDigitHeader from '@/Components/Jetstream/PublicDigitHeader.vue'
 import PublicDigitFooter from '@/Components/Jetstream/PublicDigitFooter.vue'

 useMeta({ pageKey: 'newsletter-guide', url: '/newsletter-guide', type: 'article' })

 const { locale } = useI18n()
 const localeModule = import.meta.glob('../../locales/pages/NewsletterGuide/*.json', { eager: true })
 const t = computed(() => {
   const key = `../../locales/pages/NewsletterGuide/${locale.value}.json`
   return localeModule[key]?.default || localeModule['../../locales/pages/NewsletterGuide/en.json'].default
 })
 // activeSection ref for scroll-spy ToC
 // IntersectionObserver for section reveal animations
 </script>

 Section order in template:
 1. PublicDigitHeader (no breadcrumbs)
 2. Hero — title, subtitle, audience badge pills, read-time, description
 3. Two-column layout starts: sticky ToC sidebar | content
 4. Who Can Use — role cards with icons
 5. Campaign Lifecycle — SVG timeline: Draft → Queued → Processing → Completed / Failed / Cancelled (with colours)
 6. Step-by-Step: Write Your Newsletter (steps 1–3: navigate, compose, preview count)
 7. Step-by-Step: Send and Monitor (steps 4–6: review, send, watch progress)
 8. For Election Committees — dedicated section with election-specific use cases
 9. Unsubscribes & Bounces — what members see, what admins should know
 10. FAQ — collapsible accordion items
 11. CTA — "Ready? Go to your newsletters →" with link to org dashboard
 12. PublicDigitFooter

 ---
 SEO Wiring

 The useMeta() composable reads the locale JSON's seo section server-side via InjectPageMeta middleware. No additional PHP changes needed — this is the same mechanism used by Tutorials/ElectionSettings.

 SEO values (all locales, ≤60 title, ≤160 description):

 ┌─────────────┬────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┬───────────────────────────────────────────────┐
 │    Field    │                                                               EN                                                               │                      DE                       │
 ├─────────────┼────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┼───────────────────────────────────────────────┤
 │ title       │ Newsletter Guide — Public Digit (35 chars)                                                                                     │ Newsletter-Handbuch — Public Digit (36 chars) │
 ├─────────────┼────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┼───────────────────────────────────────────────┤
 │ description │ Step-by-step guide for organisation admins and election committees on writing and sending newsletters effectively. (115 chars) │ (German equivalent)                           │
 ├─────────────┼────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┼───────────────────────────────────────────────┤
 │ robots      │ index, follow                                                                                                                  │ index, follow                                 │
 └─────────────┴────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┴───────────────────────────────────────────────┘

 ---
 Critical Files Summary

 ┌────────┬──────────────────────────────────────────────────────────────────┐
 │ Action │                               File                               │
 ├────────┼──────────────────────────────────────────────────────────────────┤
 │ CREATE │ resources/js/Pages/Guides/NewsletterGuide.vue                    │
 ├────────┼──────────────────────────────────────────────────────────────────┤
 │ CREATE │ resources/js/locales/pages/NewsletterGuide/en.json               │
 ├────────┼──────────────────────────────────────────────────────────────────┤
 │ CREATE │ resources/js/locales/pages/NewsletterGuide/de.json               │
 ├────────┼──────────────────────────────────────────────────────────────────┤
 │ CREATE │ resources/js/locales/pages/NewsletterGuide/np.json               │
 ├────────┼──────────────────────────────────────────────────────────────────┤
 │ EDIT   │ routes/web.php                                                   │
 ├────────┼──────────────────────────────────────────────────────────────────┤
 │ EDIT   │ resources/js/Pages/Organisations/Membership/Newsletter/Index.vue │
 └────────┴──────────────────────────────────────────────────────────────────┘

 Source content files (read-only reference):
 - developer_guide/newsletter/user_guide.md
 - developer_guide/newsletter/tutorial_how_to_send_a_newsletter.md
 - developer_guide/newsletter/03-state-machine.md
 - developer_guide/newsletter/07-unsubscribe-bounce.md

 Pattern reference (read-only):
 - resources/js/Pages/Tutorials/ElectionSettings.vue — locale glob pattern
 - resources/js/locales/pages/Tutorials/ElectionSettings/en.json — JSON structure

 ---
 Verification

 1. Visit http://localhost:8000/newsletter-guide → page loads without auth, correct language
 2. Switch language (de/en/np) → all text updates, no hard-coded strings visible
 3. Check page source: <title>Newsletter Guide — Public Digit</title>, correct meta description
 4. Check robots meta tag: index, follow
 5. Visit http://localhost:8000/organisations/namaste-nepal-gmbh/membership/newsletters → "User Guide" button visible in header, opens /newsletter-guide in new tab
 6. Scroll down the guide page → ToC highlights active section, sections animate in
 7. Test all 3 locales match — no missing keys (de/np fall back to en if key missing)
 8. Responsive: mobile view collapses sidebar ToC to top accordion
 9. Lifecycle diagram renders correctly with all 6 states
╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌

 Claude has written up a plan and is ready to execute. Would you like to proceed?

   1. Yes, auto-accept edits
 > 2. Yes, manually approve edits
   3. No, refine with Ultraplan on Claude Code on the web
   4. Tell Claude what to change
      shift+tab to approve with this feedback
please analyse this plan as senior software architect critically and write prompt instructions to improve and to full fill the missing part .

## Senior Software Architect: Critical Review of Newsletter Guide Plan

### Overall Assessment: **7/10 - Good Foundation, Missing Critical Elements** ⚠️

The plan is aesthetically well-designed but has **significant gaps** in functionality, accessibility, and maintainability.

---

## 🔴 Critical Missing Elements

### 1. **No Dynamic Organisation Context** 🔴

The guide is static, but newsletter features depend on the user's organisation. Users need to know:
- Which organisation they're currently managing
- Whether their org uses **Full Membership** or **Election-Only** mode
- Available audience types based on their org's configuration

**Missing:**
- No way to pass current organisation slug to the guide
- No contextual help based on user's actual org settings
- No link back to specific org's newsletter page

**Fix Required:**
```php
// Route should accept optional org slug
Route::get('/newsletter-guide/{organisation?}', function ($slug = null) {
    $org = $slug ? Organisation::where('slug', $slug)->first() : null;
    return Inertia::render('Guides/NewsletterGuide', [
        'organisation' => $org ? $org->only('id', 'name', 'slug') : null,
        'usesFullMembership' => $org?->uses_full_membership ?? null,
    ]);
})->name('guides.newsletter-guide');
```

Then in Newsletter/Index.vue:
```vue
<a :href="route('guides.newsletter-guide', organisation.slug)" target="_blank">
```

---

### 2. **Missing Interactive Elements** 🔴

The plan describes a static page, but user guides need interactive elements:

| Missing Element | Why Needed |
|----------------|------------|
| **Audience type selector demo** | Users need to understand which audience type to choose |
| **Live preview simulation** | Show how recipient count updates |
| **Role-based content filtering** | Show different content for admins vs committee members |
| **Copy-paste templates** | Email templates for common scenarios |

**Fix:** Add an interactive widget showing audience type selection:

```vue
<div class="interactive-demo bg-white rounded-lg border p-6 my-8">
  <h4>Try it: See how audience selection works</h4>
  <select v-model="demoAudienceType" class="border rounded px-3 py-2">
    <option v-for="type in demoAudienceTypes" :value="type.value">
      {{ type.label }}
    </option>
  </select>
  <div class="mt-4 p-4 bg-gray-50 rounded">
    <p>This would send to: <strong>{{ getDemoRecipientCount(demoAudienceType) }}</strong> people</p>
  </div>
</div>
```

---

### 3. **No Search Functionality** 🟡

The guide has 10+ sections. Users need to find specific information quickly.

**Missing:**
- Search input with client-side filtering
- Keyboard shortcut (Ctrl+K) for search
- Highlighted search results

**Fix:** Add a search component:

```vue
<div class="search-bar sticky top-4 z-10">
  <input 
    type="search" 
    v-model="searchQuery"
    placeholder="Search guide..."
    class="w-full px-4 py-2 border rounded-lg"
  />
  <div v-if="searchQuery" class="search-results">
    <div v-for="result in searchResults" @click="scrollTo(result.id)">
      {{ result.title }}
    </div>
  </div>
</div>
```

---

### 4. **Missing Accessibility Features** 🔴

The plan mentions "BITV 2.0" but provides no implementation details.

**Required additions:**

```vue
<!-- Skip navigation -->
<a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:px-4 focus:py-2 focus:bg-amber-600 focus:text-white focus:rounded">
  Skip to main content
</a>

<!-- ARIA landmarks -->
<main id="main-content" role="main" aria-label="Newsletter Guide">
  <nav aria-label="Table of contents" role="navigation">
  <section aria-labelledby="lifecycle-heading">
    <h2 id="lifecycle-heading" tabindex="0">
```

---

### 5. **No Video or Visual Tutorials** 🟡

Text-only guides are less effective. The plan should include:

- **Loom-style embedded video** or GIF screencasts
- **Step-by-step screenshots** with annotations
- **Interactive tooltips** for first-time users

**Fix:** Add a video section:

```vue
<div class="video-tutorial my-8">
  <div class="aspect-video bg-gray-100 rounded-lg flex items-center justify-center">
    <button @click="showVideo = true" class="flex items-center gap-2 px-6 py-3 bg-amber-600 text-white rounded-lg">
      <PlayIcon class="w-5 h-5" />
      Watch video tutorial (5 min)
    </button>
  </div>
  <!-- Modal with YouTube/Vimeo embed -->
</div>
```

---

### 6. **Missing Print/Save as PDF Functionality** 🟢

Users may want to save the guide for offline reference.

**Fix:** Add print styles and PDF export:

```vue
<button @click="window.print()" class="print-button">
  <PrinterIcon class="w-4 h-4" />
  Print / Save as PDF
</button>
```

```css
@media print {
  .no-print, .toc-sidebar, .sticky-cta { display: none; }
  body { background: white; color: black; }
}
```

---

### 7. **No Feedback Mechanism** 🟡

How do users report issues or suggest improvements?

**Fix:** Add feedback section:

```vue
<div class="feedback-section bg-gray-50 rounded-lg p-6 text-center">
  <p>Was this guide helpful?</p>
  <div class="flex justify-center gap-4 mt-3">
    <button @click="feedback('yes')" class="px-4 py-2 bg-green-100 rounded">Yes 👍</button>
    <button @click="feedback('no')" class="px-4 py-2 bg-red-100 rounded">No 👎</button>
  </div>
  <div v-if="showFeedbackForm" class="mt-4">
    <textarea v-model="feedbackText" placeholder="How can we improve?" class="w-full border rounded p-2"></textarea>
    <button @click="submitFeedback" class="mt-2 px-4 py-2 bg-blue-600 text-white rounded">Submit</button>
  </div>
</div>
```

---

### 8. **Missing Analytics and Tracking** 🟡

No way to measure guide effectiveness.

**Fix:** Add event tracking:

```javascript
// Track section views
onMounted(() => {
  trackPageView('newsletter-guide', locale.value)
  
  // Track time on page
  const startTime = Date.now()
  window.addEventListener('beforeunload', () => {
    trackTimeOnPage(Date.now() - startTime)
  })
})

// Track helpfulness
const feedback = (helpful) => {
  trackEvent('guide_feedback', { helpful, section: currentSection.value })
}
```

---

### 9. **Missing Versioning and Changelog** 🟢

Users need to know when the guide was last updated.

**Fix:**
```vue
<div class="last-updated text-sm text-gray-500 mt-8 pt-4 border-t">
  Last updated: {{ lastUpdatedDate }} | 
  <a href="/changelog" class="text-amber-600">See what's new →</a>
</div>
```

---

## 📋 Improved Prompt Instructions for Claude

```
Claude, enhance the Newsletter Guide plan with these critical additions:

### P0 - Must Have (Before Deployment)

1. **Dynamic Organisation Context**
   - Make route accept optional organisation slug
   - Pass organisation data to Vue component
   - Add "Back to your newsletters" link with org context
   - Show org-specific audience types based on uses_full_membership

2. **Accessibility (BITV 2.0 / WCAG 2.1 AA)**
   - Add skip navigation link
   - Add proper ARIA landmarks (main, nav, region)
   - Ensure all interactive elements are keyboard accessible
   - Add focus indicators for all focusable elements
   - Test with screen reader (NVDA/VoiceOver)

3. **Search Functionality**
   - Add search input with real-time filtering
   - Implement keyboard shortcut (Ctrl+K / Cmd+K)
   - Highlight search results in content
   - Show search result count and navigation

### P1 - High Priority

4. **Interactive Demo Widget**
   - Create audience type selector simulation
   - Show dynamic recipient count based on selection
   - Explain each audience type with examples
   - No API calls needed - use mock data

5. **Video Tutorial Section**
   - Add placeholder for embedded video (YouTube/Vimeo)
   - Include transcript for accessibility
   - Add step-by-step screenshot carousel alternative

6. **Feedback Mechanism**
   - Add "Was this helpful?" thumbs up/down
   - Optional text feedback form
   - Track feedback events (can be console.log for now)

### P2 - Nice to Have

7. **Print/PDF Export**
   - Add print stylesheet
   - Add print button
   - Hide navigation and interactive elements when printing

8. **Last Updated & Changelog**
   - Display last updated date from git
   - Link to changelog page

9. **Analytics Events**
   - Track page view
   - Track section visibility (scroll depth)
   - Track feedback submissions

### Implementation Order

1. First implement the route with optional org slug
2. Then create all 3 locale JSON files (en/de/np) with complete structure
3. Build Vue component with all accessibility features
4. Add search functionality
5. Add interactive demo widget
6. Add feedback mechanism
7. Test all 3 languages
8. Deploy

### Verification Checklist (Updated)

- [ ] /newsletter-guide loads without auth
- [ ] /newsletter-guide/{org-slug} loads with org context
- [ ] "Back to newsletters" link uses correct org
- [ ] Skip navigation works (Tab from URL bar)
- [ ] All headings have proper ARIA labels
- [ ] Search finds content across all sections
- [ ] Keyboard shortcut Ctrl+K opens search
- [ ] Interactive demo updates without page reload
- [ ] Feedback buttons trigger console events
- [ ] Print view hides navigation and shows clean layout
- [ ] All 3 languages have complete translations
- [ ] Page passes Lighthouse accessibility audit (score > 90)

Do not mark the feature complete until all P0 and P1 items are implemented.
```

---

## Summary Table

| Priority | Missing Item | Effort | Status |
|----------|--------------|--------|--------|
| P0 | Dynamic org context | 1 hour | ❌ Missing |
| P0 | Accessibility (BITV 2.0) | 2 hours | ❌ Missing |
| P0 | Search functionality | 2 hours | ❌ Missing |
| P1 | Interactive demo widget | 3 hours | ❌ Missing |
| P1 | Video tutorial section | 1 hour | ❌ Missing |
| P1 | Feedback mechanism | 1 hour | ❌ Missing |
| P2 | Print/PDF export | 1 hour | ❌ Missing |
| P2 | Last updated & changelog | 30 min | ❌ Missing |
| P2 | Analytics events | 1 hour | ❌ Missing |

**Total additional effort:** 12.5 hours

---

## Final Verdict

**Do NOT proceed with current plan.** The plan is incomplete. Add the P0 and P1 items before implementation. The guide will be much more useful and accessible with these additions.
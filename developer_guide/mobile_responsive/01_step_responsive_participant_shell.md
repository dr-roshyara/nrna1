# 01 · Responsive participant shell (PBDIGIT-338)

## Purpose
Give members a usable header and menu on phones: no sideways scrolling, touch-sized controls, and links to their own tasks. Business behaviour is unchanged.

## Where it fits
Frontend only (Vue 3 + Inertia 2, Tailwind 4 theme in `resources/css/app.css`).
- `resources/js/Components/Jetstream/PublicDigitHeader.vue`: shared by `PublicDigitLayout` and `ElectionLayout`, so by participant **and** admin pages.
- `resources/js/Layouts/PublicDigitLayout.vue` (Membership pages) and `resources/js/Layouts/ElectionLayout.vue` (voting steps and some admin pages).
- The other seven layouts are deliberately untouched.

## Design decisions
- **Colours come only from the live theme.** `tailwind.config.js` is not loaded, so a class like `brand-gold-500` compiles to nothing (PBDIGIT-312). Use `accent-*` for gold; the brand decision in `.claude/UI_GUIDELINES.md` maps gold `#a0742a` to `accent-600`. The theme has `success-500`/`600` only.
- **Breakpoint `lg` (1024 px)** switches between the ☰ menu and the desktop row. At `md` the desktop row overflowed (839 px at a 768 px window).
- **Member links never grant access.** They are a convenience; every destination keeps its route middleware (`EnsureOrganisationMember`, etc.).
- **Organisation comes from the URL only** (D-75: organisation selection belongs to the Identity design). The user record's `organisation` is not used for member links.

## How it works
```js
const currentOrganisationSlug = computed(() => {
  void page.url // re-evaluate after every Inertia visit
  try {
    const params = route().params || {}
    if (typeof params.organisation === 'string' && params.organisation !== '') return params.organisation
    if (route().current('organisations.show') && typeof params.slug === 'string') return params.slug
  } catch (e) { /* URL matches no named route */ }
  return null
})
```
`memberLinks` then yields *My organisations* always, plus *My membership*, *Elections* and *Candidacy* for that slug. Both templates render the list with `data-nav="member"`: the desktop group in the top bar (`hidden lg:flex`), and the phone group at the top of the ☰ panel.

Menu closing:
```js
removeNavigateListener = router.on('navigate', () => closeMobileMenu()) // also back/forward
```
Escape closes it and returns focus to the ☰ button (`ref="menuToggle"`). Widening to 1024 px closes it too. The panel has `id="pd-mobile-menu"` and the toggle has `aria-controls`.

The layouts use `min-h-dvh`: on phones, `100vh` includes the area behind the browser's address bar.

## How to extend
- **Add a member destination:** add an entry in `memberLinks` using an existing route name, plus `navigation.*` keys in `en`, `de` and `np`. Do not add a route for the menu's sake.
- **"My membership"** (`organisations.membership.dashboard`) depends on `PBDIGIT-339`: the self-view must never come from the shared role cache. Keep it that way when touching `MembershipDashboardController`.
- **Page-level overflow** (for example `Welcome.vue` cards at 360/390 px) is fixed in the page, not by clipping the layout.

## Testing
- Unit: `npx vitest run resources/js/Components/Jetstream/PublicDigitHeader.spec.js` (member links, URL-only organisation, close on navigate/Escape/resize, listener cleanup). jsdom has no layout engine, so it cannot prove widths.
- Browser: headless Chrome at 360/390/430/768/1024/1440. Check that `document.documentElement.scrollWidth` is not greater than `clientWidth`, and that tap targets are at least 44 px. Use synthetic data only.
- Colours: build, then search the compiled `app-*.css` for each new class. A passing build does not prove that a class exists.
- **Signed-in pages (synthetic data only)**, as done for PBDIGIT-338:
  1. `git worktree add --detach <tmp>/wt HEAD`, apply the uncommitted diff, then `composer install --no-scripts` (no config cache in a fresh tree).
  2. Start a throwaway PostgreSQL from the pinned image of guide `membership/11`, published on `127.0.0.1` with a random port, `--rm`.
  3. Write the worktree's **own** `.env` pointing **all three** connections (`DB_*`, `DB_AUDIT_*`, `DB_PGSQL_GEO_*`) at it. `pgsql_geo` otherwise defaults to the development database.
  4. Before migrating, check `current_database()` on each connection.
  5. Migrate, seed made-up users, and serve.
  6. Use one Chrome browser context per person, so cookies are separate.
  7. Remove everything afterwards.

## Pitfalls
- `npm run build` writes to `public/build`, which is tracked. For checks, build into a scratch directory: `npx vite build --outDir <tmp>`.
- Never use a test that submits a form against the development database.
- Hiding a link is not access control.

## Traceability
PBDIGIT-338 · PBDIGIT-312 · PBDIGIT-339 · DD-13, DD-15, PD-E · D-75 · plan `docs/plans/20261109-1653-responsive-participant-foundation-plan.md`

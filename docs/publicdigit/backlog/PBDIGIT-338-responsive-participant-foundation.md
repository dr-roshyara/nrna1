# PBDIGIT-338: Responsive participant foundation (mobile stage 1)

**Type:** UI foundation · **Epic:** none · **Created:** 2026-11-09 · **Raised by:** the Principal, conditional approval of the first mobile slice (staged direction: responsive web → PWA → evaluate app; DD-13, DD-15, PD-E).

| | |
|---|---|
| **Status** | `IMPLEMENTED` (working tree, not committed) · shell checks pass · **signed-in member journey verified** (synthetic, throwaway database) · awaiting PO acceptance |
| **Plan** | `docs/plans/20261109-1653-responsive-participant-foundation-plan.md` |
| **Guide** | `developer_guide/mobile_responsive/01_step_responsive_participant_shell.md` |
| **Scope** | `PublicDigitHeader.vue`, `PublicDigitLayout.vue`, `ElectionLayout.vue`, `navigation.*` keys in `locales/{en,de,np}.json`, one new spec. No routes, controllers, permissions, business rules, voting code or admin pages changed. The other seven layouts untouched. |

## Starting state (verified, HEAD `89db1a811`, branch `election-flow-updated`)
- PBDIGIT-311 already fixed and in HEAD (`976724abb`). Not repeated.
- Header: 37 undefined `brand-gold-*` classes on 20 lines, plus `via-primary-950`, `hover:from-gold-gold hover:to-white` and `hover:bg-success-700` (theme has `success-500`/`600` only). All generated no CSS (PBDIGIT-312).
- Header at 768 px overflowed on every page (desktop row ended at 839 px). ☰ button 40×40 px.
- Neither the phone nor the desktop menu contained any member destination.

## Changes
1. **PBDIGIT-312 in the header only:** `brand-gold-N` → `accent-N` (same shade; 400 and 500 both exist). Colour evidence: `.claude/UI_GUIDELINES.md` "Brand Color Architecture (Architectural Decision, 2026-05-29)" names gold `#a0742a` the "Governance accent"; the theme defines it as `--color-accent-600` (`resources/css/app.css:26`). So `accent` is the documented brand gold. Dead gradient/hover classes removed or replaced by existing tokens. Every new class verified in the compiled CSS.
2. **Breakpoint:** desktop row from `lg` (1024 px), ☰ menu below it; row may wrap instead of overflowing.
3. **Member navigation** (signed-in only; server authorisation unchanged): *My organisations* (`organisations.index`) always; *My membership* (`organisations.membership.dashboard`), *Elections* (`organisations.elections.index`) and *Candidacy* (`organisations.candidacy.create`) for the organisation in the **current URL** only. No organisation taken from the user record, no switcher (D-75 Identity commission). *My membership* was added after `PBDIGIT-339` was fixed and the journey below passed.
4. **Menu behaviour:** closes on Inertia `navigate` (includes back/forward), on link click, on Escape (focus returns to ☰), on widening to 1024 px; `aria-controls`; 44×44 px toggle; panel scrolls inside the viewport (`max-h` with `dvh`).
5. **Layouts:** `min-h-screen` (100vh) → `min-h-dvh` in both participant layouts. No layout-level content container added: pages own their containers, and adding one would double the padding on ~100 pages.

## Evidence
- Headless Chrome (DevTools protocol, script outside the repo), widths 360/390/430/768/1024/1440:
  - `/`, `/faq`, `/security`, `/election-architecture`, `/organisations/{org}/join` as guest; `/faq`, `/security` with a **synthetic** signed-in user injected client-side (no login, no session, no database record).
  - Before: all four public pages overflowed at 768 px (scrollWidth 839). After: no overflow at any width on any probed page, except `/` at 360/390 caused by `Welcome.vue` cards (page-level, unchanged).
  - Member links present at all six widths; phone menu lists them first; no tap target under 44 px.
- `PublicDigitHeader.spec.js`: 12 tests pass; against the original header 10 fail (the 2 that pass assert absence).
- Full vitest: 12 failed / 592 passed (baseline 12 failed / 580 passed); no newly failing file. Pre-existing failures are election tests and `.claude/worktrees/` copies.
- `npm run design-check`: 91 before and after (threshold 150).
- `vite build` (to a scratch directory; `public/build` untouched): passes.

## Open
- Public application → **outcome and account access** not browser-verified: approval still creates or reuses accounts by e-mail (`PBDIGIT-341`); the claim-code flow is Identity's tactical design.
- The membership page's "Jetzt verlängern" triggers the **legacy** immediate renewal (PD-M3 open). Observation only.
- `ElectionLayout` was not rendered in a browser: every page using it needs sign-in. It shares the verified header; its own change is one class.
- Remaining PBDIGIT-312 scope: 45 `brand-gold-*` uses in 4 other files (20 in `PublicDigitFooter.vue`).
- Gold usage: the brand rule says gold "should feel important and uncommon — reserved for governance/officer actions". The header uses it for decorative borders, the tagline and hover states (the authored intent, now visible). Design call for the Principal; not blocking.

## Related
`PBDIGIT-311` · `PBDIGIT-312` · `PBDIGIT-339` · `PBDIGIT-337` (why voting is not the pilot) · mobile baseline `docs/publicdigit/architecture/reviews/2026-11-09-mobile-application-strategic-ddd-baseline.md` (PD-PWA-1 deferred to stage 2).

## Signed-in journey evidence (2026-11-09, authorised by the Principal)
- **Environment:** a clean worktree at `e2051cbbe` plus the uncommitted 338/339 changes; its **own synthetic `.env`**; a throwaway PostgreSQL 15.19 container (pinned image, 127.0.0.1 only, random port, database `pd_journey_synthetic`). All three connections (`pgsql`, `pgsql_audit`, `pgsql_geo`) were checked to reach it before migrating; file cache, so the shared cache was really in use. Removed afterwards (container stopped with `--rm`, worktree removed).
- **Data:** one synthetic organisation; members A (200 days left) and B (30 days); person C with the member role and no membership. No real data.
- **Run** (headless Chrome, 390×844, one isolated browser context per person; real login form):

| Order | Person | Lands after login | Own status shown | Other person's data |
|---|---|---|---|---|
| 1 | A | organisation page | membership, 199 days, A's id | none |
| 2 | B (immediately after A) | organisation page | membership, 29 days, B's id | none |
| 3 | A again | organisation page | A's own | none |
| 4 | C | organisation page | no membership | none |

- After the run, the shared member-role cache existed and held **no** `memberSelf`.
- **Via the menu** (second run): ☰ → *Meine Mitgliedschaft* → own status, same results for all four; no horizontal overflow at 390 px; member links first in the menu.
- **Afterwards:** header spec 12/12; full vitest 12 failed / 592 passed (baseline 12 / 580; no new failing file); design-check 91.

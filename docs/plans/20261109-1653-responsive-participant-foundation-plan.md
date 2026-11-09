# Responsive participant foundation (PBDIGIT-338) — plan

## Objective
Make participant journeys usable on phones through the shared header and the two participant layouts, without changing business behaviour. Stage 1 of the staged direction (responsive web → PWA → evaluate app).

## Background
- Principal direction (2026-11-09): staged; Membership is the pilot, voting is not (PBDIGIT-337).
- Plan approved conditionally by the Principal (2026-11-09) with the reviewer's safeguards: no layout merge; navigation is not access control; voting only as a shell smoke check; real browser evidence; synthetic data only.
- Evidence and story: `docs/publicdigit/backlog/PBDIGIT-338-responsive-participant-foundation.md`.

## Scope
`PublicDigitHeader.vue`, `PublicDigitLayout.vue`, `ElectionLayout.vue`, `navigation.*` locale keys, one spec. Excluded: PWA, routes, controllers, permissions, voting/ballot code, admin layouts, other seven layouts, forum.

## Design decisions
- D1 `brand-gold-N` → `accent-N` (UI_GUIDELINES brand decision 2026-05-29: gold `#a0742a` = `accent-600`).
- D2 desktop row from `lg`; wraps rather than overflows.
- D3 member links from existing routes; organisation only from the current URL; "My membership" withheld (PBDIGIT-339).
- D4 menu closes on Inertia `navigate`, link click, Escape (focus back), resize ≥ 1024.
- D5 `min-h-dvh`; no layout-level container (pages own containers).

## Task checklist
- [x] Verify starting state (HEAD, 311, 312, layouts, routes)
- [x] Baselines: design-check 91; vitest 12 failed / 580 passed; browser probe before
- [x] Header colours, breakpoint, member links, menu behaviour
- [x] Layout `dvh`
- [x] Unit tests (12; 10 fail on the original header)
- [x] Browser probe after: six widths, guest + synthetic signed-in, public application page
- [x] design-check, full vitest, build (scratch output)
- [x] Story, security item PBDIGIT-339, developer guide, session log
- [ ] Member journey with signed-in pages (needs throwaway DB authorisation; step 5 needs PBDIGIT-339)
- [ ] Commit (held until the Principal decides how to treat the blocked journey check)

## Progress
Implementation complete in the working tree; not committed.

## Risks
- Gold now visible on header borders/hover where it rendered nothing before (design intent restored; brand rule says gold is for governance actions).
- At 1024 px the "Try Demo" button wraps to a second row.

## Open questions
- Authorise a throwaway-database browser run for the signed-in journey?
- Fix PBDIGIT-339 before or together with the pilot?

## Next actions
Principal decisions above → journey verification → one commit `(PBDIGIT-338)`.

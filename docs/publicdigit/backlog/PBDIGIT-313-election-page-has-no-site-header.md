# PBDIGIT-313: The election page has no site header (no ☰ menu, no Logout)

**Type:** UI bug · **Epic:** none · **Created:** 2026-10-26 · **Raised by:** the PO, on `https://publicdigit.com/elections/onf-east-asia-real-election-a3d254a0`.

| | |
|---|---|
| **Status** | `IMPLEMENTED` · approved 2026-10-26; awaiting PO acceptance and frontend release |
| **Classification** | **observed:** `/elections/{slug}` (`elections.show` → `Election/Show`) renders no `PublicDigitHeader` and no footer, on any screen size |
| **Scope** | `resources/js/Pages/Election/Show.vue`, lines 2 and 288 |

## Cause
- `Show.vue` used to be wrapped in `ElectionLayout`, which renders `PublicDigitHeader` (logo, menu, ☰ panel, Logout) and `PublicDigitFooter`.
- Commit `6025b5633` (2026-03-29) commented out the wrapper (`<!-- <ElectionLayout> -->`). Its message gives no reason. Since then the page has shown only its own hero banner.

## Change (2 lines)
- `<!-- <ElectionLayout> -->` → `<ElectionLayout>` and `<!-- </ElectionLayout> -->` → `</ElectionLayout>`.

## Evidence
- `npm run design-check`: 91 before and after (threshold 150).
- `npm run prod` builds; the built `Show-*.js` chunk imports `ElectionLayout-*.js`.
- The header flows in the document (`sticky`, not `fixed`) and the page has no fixed elements, so the header sits above the hero and does not overlap it.
- **Not rendered in the running app:** the route needs a logged-in, verified voter. Visual check pending on the deployed page (390 px and desktop).

## Not changed
The hero banner (its own logo and "Go to organisation" button), the voting states, the unused `PublicDigitHeader`/`PublicDigitFooter` imports in `Show.vue`.

## Developer guide
Folded here (2-line UI fix).

## Related
- **PBDIGIT-311**: the ☰ panel this restores.
- **PBDIGIT-312**: the header's `via-primary-950` and `brand-gold-*` classes still generate no CSS.

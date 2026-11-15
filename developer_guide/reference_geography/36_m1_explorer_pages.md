# 36. Frontend M1, step 2: the explorer pages

**Story:** `PBDIGIT-355` · **Builds on:** guide 34 (the web adapter), guide 35 (the hierarchy queries) · **Design system:** `.claude/UI_GUIDELINES.md` (semantic tokens, standard components).

## Purpose
Two read-only pages in the responsive `PublicDigitLayout`:
- **Explorer** (`/geography/k/{position}`): the officially published top-level places, or the empty state "No official geography has been published yet."
- **Place** (`/geography/k/{position}/places/{unitId}`):
  - names (the CLDR display name with its release, and attributed source names);
  - classifications and identifiers;
  - "contained in" and "contains", grouped by scheme;
  - warnings for disputed facts or a place that doesn't exist on the date;
  - a link to the place it was merged into;
  - a link back to the same pinned explorer.

**Every page shows "as officially known at publication N · valid on D"** and a link to the latest publication.

## Files
| File | Role |
|---|---|
| `resources/js/types/referenceGeography.ts` | the typed page-data contract (mirrors guide 34's page data) |
| `resources/js/Pages/ReferenceGeography/Explorer.vue` | the top-level list, or the empty state |
| `resources/js/Pages/ReferenceGeography/Place.vue` | one place |
| `resources/js/Components/ReferenceGeography/KnownAtBanner.vue` | the "as officially known at" banner |
| `resources/js/Components/ReferenceGeography/PlaceLabelText.vue` | a label exactly as the backend chose it, with the fallback or source note |
| `resources/js/locales/pages/reference-geography/{en,de,np}.json` | texts, registered in `resources/js/i18n.js` under `pages['reference-geography']` |
| `resources/js/Pages/ReferenceGeography/ReferenceGeography.spec.js` | Vitest (10 tests) |

**Reused, not duplicated:** `PublicDigitLayout`, `Card` (variants `warning` and `primary`), `EmptyState`, Inertia `Link` and `Head`.

## Design decisions
1. **The page decides nothing.**
   - Labels, the label kind, the top level, the children and their order (scheme, then label: `ExplorerPageData::byScheme`) all come from the backend.
   - Vue only groups children by scheme for display.
2. **A fallback is never a translation.** `PlaceLabelText` adds "Platform default language (en)" to a `display_fallback` label and "Source name (ne)" to a source name, and sets `lang` on every name.
3. **The back link stays pinned:** `knownAt.explorerUrl` (new in this step) points to the same publication position, so a visit never mixes publications.
4. **Props are declared at runtime with `PropType<…>` from the typed contract.** `defineProps<ImportedType>()` can't be resolved by the SFC compiler in this build (no TypeScript type resolution is configured). Declaring the props at runtime keeps the types and builds in Vite and in Vitest.
5. **Design-system compliance:**
   - semantic tokens only (`neutral-*`, `primary-*`, `warning-*`);
   - `font-bold` only, no shadows;
   - the design check stays at **91** (the baseline). No new violation and no exception.
6. **Responsive by construction:**
   - the layout uses `min-h-dvh` (PBDIGIT-338);
   - content sits in `max-w-4xl` with `px-4` side gutters;
   - lists are one column on phones and two from `sm`/`md`;
   - the banner wraps (`flex-wrap`).

   **This wasn't measured in a browser** (jsdom has no layout, and the worktree has no running app), so it remains an acceptance check for the Principal.

## Testing
```bash
npx vitest run resources/js/Pages/ReferenceGeography
```

**What the tests cover:**
- the empty state;
- the list, with pinned links;
- the fallback note;
- Nepali texts;
- the banner;
- the profile (names, classifications, identifiers);
- parents;
- children by scheme;
- the back link;
- disputed facts and merged places;
- a place that doesn't exist, and one with no children;
- the CLDR release;
- **identical locale keys in en, de and np.**

**Mutants:** H (the fallback note never shown) and I (the empty state never shown), both killed.

**Backend:** `GeographyExplorerHttpTest` now also requires the pages to exist (Inertia's page check) and checks `knownAt.explorerUrl`.

**Full Vitest suite:** 6 files and 4 tests fail. They are Election tests and fail **identically without this step**, as shown by a run of the committed baseline (`vitest-full-summary.txt`).

Evidence: `docs/publicdigit/reference-geography/evidence/2026-11-15-pbdigit355-step2/`.

## Administrator notes (deployment)
No configuration is needed. After deploying:
1. **Rebuild the frontend assets** (`npm run build`).
2. **Refresh Laravel's caches:** `php artisan optimize`. The new routes are in `routes/reference-geography.php`.

Production shows the empty state until officially approved data is published through the accepted process (G-8 data, D-84's owner). **Never load test fixtures into production.**

## Pitfalls
- `public/build` is **tracked in git**. A local `vite build` changes tracked files, so for verification build to another directory (`--outDir`).
- Don't add a fact in Vue (for example "type" or a guessed name). If a page needs something new, it belongs in the published contract (guide 35).

## Traceability
PBDIGIT-355 step 2 · guides 34 and 35 · UI guidelines (MUST: semantic tokens, design check) · D-87, D-231, D-232 (names and languages).

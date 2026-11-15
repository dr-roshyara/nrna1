# 34. Frontend M1, step 1: the read-only web adapter for the explorer

**Story:** `PBDIGIT-355` · **Plan:** `docs/plans/20261115-1200-frontend-m1-reference-geography-explorer-plan.md` · **Consumes:** the published contract `GeographyReadModel` (G-5a, guide 18 onwards); display names (G-7, guide 32).

## Purpose
Signed-in users can browse **officially published** places. This step adds the web adapter: routes, a controller and page data. The Vue pages are step 2. **Nothing here writes a Geography fact.**

## Where it sits
- **Delivery layer:** `app/Http/Controllers/ReferenceGeography/`.
  - It depends **only** on `App\Contexts\ReferenceGeography\Application\Published\*`, never on Geography's ports or infrastructure.
  - The no-bypass guard (rule 6) and the published-contract guard check this.
- **Routes:** `routes/reference-geography.php`, included from `routes/web.php` and protected by `auth`.

| Route | Name | Does |
|---|---|---|
| `GET /geography?date=` | `geography.entry` | `knowledgeAtTime(now)`, then redirects to the current position |
| `GET /geography/k/{position}?date=` | `geography.explorer` | the top-level places at that position |
| `GET /geography/k/{position}/places/{unitId}?date=` | `geography.place` | one place: profile, parents, children |

## Design decisions
1. **Close once, then pin.**
   - `knowledgeAtTime` closes the publication boundary, a short write the contract allows the read side. Only the entry calls it.
   - The pinned pages use `knowledgeAt(position)`, which only reads.
   - So one visit always shows one publication, and browsing never writes. That is proven by `test_the_pinned_pages_only_read`.
2. **Language:** the frontend locale `np` (the code `SetLocale` uses) is the language `ne`, and the controller maps it (`LANGUAGES`).
3. **Labels** (`ExplorerPageData::label`), in order:
   - `display`: the CLDR display name in the user's language;
   - `display_fallback`: the platform default language's name, **never presented as a translation**;
   - `source`: an attributed source name, preferably in the user's language;
   - `none`.

   PublicDigit invents no name.
4. **Only contract fields reach a page.** There is no "type" field; the read model has `classifications` per scheme.
5. **Refusals:**
   - a position beyond what is published → 404;
   - an unknown place → 404;
   - a bad `date` → a validation error;
   - the boundary lock busy at entry → 503.

## Page data (step 2 types it)
- **Common to both pages:** `knownAt {position, latestUrl}` · `validDate` · `language`.
- **Explorer:** `places: PlaceSummary[]` · `empty`.
  - `PlaceSummary` = `{unitId, label {value, kind, language}, classifications, url}`.
- **Place:** `place {unitId, validAt, exists, label, displayName|null, sourceNames, classifications, identifiers, disputedKinds, mergedInto|null, parents[], children[]}`.
  - Each entry in `parents[]` and `children[]` is `{scheme} + PlaceSummary`.

## How to extend
Add only what the published contract answers. Anything new about Geography belongs in Reference Geography's contract, decided by its owner, never computed here.

## Testing
`tests/Feature/Contexts/ReferenceGeography/GeographyExplorerHttpTest.php`: 10 tests, on the isolated cluster.

```bash
MEMBERSHIP_PG_IMAGE=docker.io/library/postgres:15@sha256:7da5d534f1ffcdb2f69efa6e466a7a29ff89eefd611c7b50c4d69aa77fd4a822 \
  bash scripts/testing/membership-pg.sh --filter GeographyExplorerHttpTest
```

**What they cover:**
- published only;
- an unpublished revision is invisible;
- position 0 gives the empty state;
- the top level and the hierarchy;
- names (np → ne, fallback marked);
- the pinned pages only read;
- 404s and the date check.

**Mutants:** four, all killed: pinning replaced by "now"; every place counted as top level; no `ne` mapping; the fallback not marked. Evidence: `docs/publicdigit/reference-geography/evidence/2026-11-15-pbdigit355-step1/`.

## Known limitation (the plan's stop condition was reached)
- The contract has **no top-level query**, and children are known only per named scheme. So both pages read **every** place at the date (`placesAt` plus `place` for each).
- **Measured:** 1,250 places take about 6 s per page (`timing.txt`), and the cost is linear.
- Production holds 0 published places today, so nothing is slow now. Real volumes (thousands of municipalities) would not be usable.
- **Resolving it needs a decision by the Principal** (see the story). Do not add caching or SQL here to work around the contract.

## Pitfalls
- **Worktree runs:** a symlinked `vendor/` makes the autoloader load `App\` from the **main** tree, which is the other session's working copy. Use a real `vendor/` copy and run `composer dump-autoload` in the worktree.
- **Never call `knowledgeAtTime` from a pinned page.**

## Traceability
PBDIGIT-355 step 1 · G-5a contract · D-87, D-231, D-232 (display names) · no-bypass guard rule 6 · Hexagonal DDD baseline §4.

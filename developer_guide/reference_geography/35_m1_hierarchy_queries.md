# 35. Frontend M1, step 1b: the published hierarchy queries `topLevelAt` and `childrenInAnySchemeOf`

**Story:** `PBDIGIT-355` · **Decision:** the Principal, 2026-11-15: "Extend the contract", after step 1's stop condition (guide 34) · **Changes:** the published contract `GeographyReadModel` (G-5a), by **addition only**.

## Purpose
A consumer can browse the hierarchy without reading every place:
- **`topLevelAt(validDate, knowledge)`**: the places existing at the date with **no parent in any scheme** at that date. A `list<string>`, sorted by id.
- **`childrenInAnySchemeOf(unitId, validDate, knowledge)`**: `childrenOf` across every scheme. A `list<array{scheme, unitId}>`, sorted by scheme, then id.

Both are published-only, refused beyond the boundary (`KnowledgeNotAvailable`), and **bounded in queries**.

## Where it sits
| Layer | File | Change |
|---|---|---|
| Published contract | `Application/Published/GeographyReadModel.php` | the two method signatures (strings, ints and arrays only, so the published-language guard is unchanged) |
| Application | `Application/PublishedGeography.php` | the two queries |
| Port | `Application/Ports/GeographyReadSource.php` | `containedInAnyScheme(parent, p): list<SlotKey>` |
| Infrastructure | `Infrastructure/PostgresGeographyReadSource.php` | `containedInAnyScheme`; `slotsOfKind` now calls the atlas |
| Infrastructure | `Infrastructure/PostgresPublishedAtlas.php` | `historiesOfKind(kind)`: every history of a kind in **one** query |

## Design decisions
1. **The same meaning as the existing queries, not a new one.**
   - **Top level:**
     - existence counts as in `placesAt` (`resolve(t, p) !== null`);
     - a parent counts as `Place::$parents` does (`PlaceBelief`: the claim in force at t whose value isn't "none").
   - **Children:** as in `childrenOf` (`resolve(t, p)` names the parent), for every scheme.
   - **Proven against the old answers:**
     - `topLevelAt` = `placesAt` filtered by `place()->parents === []`;
     - for each scheme, `childrenInAnySchemeOf` = `childrenOf`.
2. **The cost was one query per slot, and that's what changed.**
   - `slotsOfKind` used to load each history with its own query. That cost also hit `lookup` and the CLDR parent map.
   - `PostgresPublishedAtlas::historiesOfKind` now loads them all in one ordered query and builds **exactly** the histories `history()` builds: the same published claims, in the same order of position then claim id.
   - **`topLevelAt` therefore takes a fixed number of queries** (at most 6 in the test with 240 places, against 435 before).
3. **The guards stand unchanged.**
   - The first attempt put the batched load in the read source, which the **no-bypass guard, rule 2**, refused: only the atlas may map stored claims (`PostgresRevisionRepository::claimOf`).
   - The load moved into the atlas. No allow-list was widened.

## Measured (the same fixture as step 1: 1,250 places)
| Page | Before (step 1) | After |
|---|---|---|
| place (profile, parents, children) | 5.96 s | **0.06 s** |
| explorer (250 top-level places) | 5.93 s | **1.79 s** |

The explorer now costs in proportion to the places it **shows** (a profile and a display name each), no longer to every place. With around 250 countries at the top, that's acceptable for M1. **If the top level grows, paginate the page rather than widening the contract further.**

## Testing
- **Unit:** `tests/Unit/Contexts/ReferenceGeography/Application/PublishedGeographyTest.php`.
  - Two new tests.
  - Both queries were added to "refused beyond the boundary".
  - The fake source implements `containedInAnyScheme`.
- **PostgreSQL:** `tests/Feature/Contexts/ReferenceGeography/PublishedHierarchyQueriesTest.php`.
  - containment over time;
  - an unpublished containment is ignored;
  - two schemes;
  - an ended containment;
  - the equivalences above;
  - **the query-count bounds**, which are the performance guard and don't depend on timing.
- **Fixtures:** `DirectPublishing` (a test trait) now publishes for both PBDIGIT-355 test files.
- **Mutants:** E (the parent taken from the latest claim, not the one in force at t), F (the child's parent not checked), G (one query per slot again). All three killed; G with 435 queries against the bound of 6.

Evidence: `docs/publicdigit/reference-geography/evidence/2026-11-15-pbdigit355-step1b/`.

## Pitfalls
- Don't add a consumer-side cache or SQL to make browsing faster. Add a published query here, decided by Reference Geography's owner.
- `historiesOfKind` loads a whole kind. Use it for the kinds that are small per place (existence, containment, CLDR inheritance), not as a general export.

## Traceability
PBDIGIT-355 step 1b · Principal decision 2026-11-15 · G-5a contract (additive) · no-bypass guard rules 2 and 6 · published-contract guard.

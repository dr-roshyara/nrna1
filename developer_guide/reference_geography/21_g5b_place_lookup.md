# 21: G-5b, PlaceLookup (PBDIGIT-346)

**Design:** `docs/publicdigit/architecture/reviews/2026-11-13-reference-geography-g5-read-models-design.md` §4. **Evidence:** `docs/publicdigit/reference-geography/evidence/2026-11-13-pbdigit346-g5b/`.

## In business terms

When someone types a place, for example "Kathmandu", "Patan" or a code like "NP-P3", **PublicDigit can suggest which official places they may mean, and how good each match is.**
- **It only suggests.** Whoever stores the answer stores what the person chose. No match leaves the record unresolved.
- **A lookup never places a member anywhere** (D-79, D-80), and it never parses addresses (D-83).

## How it works

```php
$geo = app(GeographyReadModel::class);
$k = $geo->knowledgeAt($position);                 // or knowledgeAtTime($tau)
$matches = $geo->lookup('Kathmandu', '2026-01-01', $k);                        // anywhere
$within  = $geo->lookup('Kathmandu', '2026-01-01', $k, $nepalId, 'admin', 5);  // only below Nepal in the admin scheme
// LookupMatch: unitId, quality (exact|very_high|high|medium|low), score, matchedOn (name|identifier),
//              matchedValue, detail ("en/official" or the code's namespace), partial, mergedInto
```

**What's searched:** for places existing at the date, as known at p:
- every published **source name**: official, short, alternative and former, in every language;
- every published **code**.

| Element | Layer | Role |
|---|---|---|
| `Domain\PlaceMatcher` (`normalise`, `similarity`, `best`), `MatchQuality`, `LookupCandidate`, `PlaceMatch` | Domain (pure) | the matching rules |
| `PublishedGeography::lookup` | Application | eligible places at (t, p); optional transitive scope; candidates; DTOs |
| `GeographyReadSource::slotsOfKind` → `PostgresGeographyReadSource` | port, adapter | published names and codes, each history through `PostgresPublishedAtlas` |
| `Published\LookupMatch` | published language | the result |

## Design decisions

- **The legacy matcher's algorithm is copied, its data is not** (design §4.2).
  - Copied: NFKC, lower case, punctuation as space; `pg_trgm` trigram similarity, reimplemented in pure PHP; the legacy bands (1.0 / 0.95 / 0.85 / 0.70 / 0.50).
  - Not copied: the hand-written `NEPAL_VARIATIONS` and the designation stripping. **Variants are published alternative or former names, with evidence (G-8).**
- **A partial name** ("Kathmandu" in "Kathmandu Metropolitan City") scores through its best run of words, and says `partial: true`.
- **Codes match only exactly** (ignoring case and surrounding spaces). Never fuzzy.
- **One result per place, its best.** Order: score, then whole before partial, then place id. **Never ordered by the matched text.** An early version did that; the design-derived test caught it.
- **A merged identity is still offered,** with `mergedInto`, so the consumer can store the canonical one.

## Testing

- `tests/Unit/Contexts/ReferenceGeography/Domain/PlaceMatcherTest.php`: the `pg_trgm` value (`similarity('word', 'two words')` = 0.363636), the bands, partial, codes, no variants.
- `tests/Unit/Contexts/ReferenceGeography/Application/PublishedGeographyTest.php`: valid time, knowledge, scope, refusals, forged position.
- `tests/Feature/Contexts/ReferenceGeography/PostgresGeographyReadModelTest.php` (`test_lookup_…`): unpublished never found; former name; code; scope; merge.

## Closure verification (2026-11-13)

- **`pg_trgm` compatibility, proven against PostgreSQL:** `tests/Feature/Contexts/ReferenceGeography/PlaceMatcherPgTrgmDifferentialTest.php`, 20 pairs equal to 1e-6 (including Unicode and band edges). The intentional difference from `word_similarity` is in design §4.2.
- **Mutation:** R4 (codes matched fuzzily), R5 (lookup ignoring existence at t) and R9 (ordering by text) are each killed by their intended test (evidence `…-g5-closure/`).
- **When you change `similarity` or `normalise`:** add pairs to the differential test first, then run it on the isolated cluster.

## Pitfalls

- **The Domain's purity guard forbids a `$name` variable,** as a personal-data safeguard. Use another word (`$placeText`); don't weaken the guard.
- **Cost:** every published name and code at p is matched in PHP. Fine before G-8; add a `pg_trgm` prefilter when measured.
- **Scope is transitive** over `childrenOf` at t. It needs both the place and the scheme.

**Traceability:** PBDIGIT-346 G-5b · v3.11 §10 · D-79, D-80, D-83, D-227, D-229 · completion §2 (copy, never import).

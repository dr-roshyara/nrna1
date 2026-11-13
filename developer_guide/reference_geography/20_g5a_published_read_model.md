# 20: G-5a, the published read model (PBDIGIT-346)

**Design:** `docs/publicdigit/architecture/reviews/2026-11-13-reference-geography-g5-read-models-design.md`; v3.11 §4 (belief, disputes), §6 (boundaries, query rules, PO-5), §10, §12 (reconciliation).
**Evidence:** `docs/publicdigit/reference-geography/evidence/2026-11-13-pbdigit346-g5a/`.

## In business terms

**Other parts of PublicDigit can now ask Reference Geography questions:**
- which places existed on a date;
- what a place is (its parent, class, codes and names);
- what lies below it;
- whether an identity was merged;
- whether a fact is contested.

**Every answer is "as officially known at publication p".** The same question at the same p always gives the same answer, so a decision can be explained years later. **Unpublished work is never visible.** A question about the future, or beyond what is published, is refused, never half-answered.

## Where it fits

| Layer | Class | Role |
|---|---|---|
| Application, **published language** | `Application\Published\GeographyReadModel`, `Knowledge`, `Place`, `Resolution`, `KnowledgeNotAvailable` | the **only** thing another context may import (strings, ints, these DTOs) |
| Application | `PublishedGeography` | the boundary (re-checked on every call); time → position; assembling the DTOs |
| Application ports | `GeographyReadSource`, **`TimeBoundary`** | published claims only; `closeThrough` alone (`PublicationLedger` extends it) |
| Domain | `PlaceBelief`, `PlaceFacts`, `IdentityResolution`; `SlotHistory::latest/positionOf/slot` | belief at (t, p), dispute flags, merges: the business rules of reading |
| Infrastructure | `PostgresGeographyReadSource` | SQL over `refgeo_claims ⋈ refgeo_publications`, position ≤ p; slot histories via `PostgresPublishedAtlas` |

## How to use it (a future consumer, after D-147)

```php
$geo = app(GeographyReadModel::class);
$k = $geo->knowledgeAtTime($tau);              // D-73: one shared τ; or $geo->knowledgeAt($recordedPosition)
$place = $geo->place($unitId, '2020-01-01', $k);
$children = $geo->childrenOf($unitId, 'admin', '2020-01-01', $k);
$canonical = $geo->resolve($oldUnitId, $k)->canonical;
// store $k->position with the decision: asking again at it gives the same answer
```

**A consumer defines its own port, and an adapter calls this contract.** It never imports Geography's Domain or Infrastructure: A-3 and `ReferenceGeographyPublishedContractTest` fail if it does.

## Design decisions

- **The read side holds `TimeBoundary`, not `PublicationLedger`.** It can close through τ, but can't reach `withLock`. The accepted no-bypass guard flagged the first version that used the ledger.
- **A dispute flags only the claim in force.** A superseded claim keeps its marker; the new value isn't flagged (v3.11 §4).
- **A merge holds for all time and changes only by knowledge,** so the latest reconciliation claim ≤ p decides it (v3.11 §12).
- **PO-5 by immutability:** positions ≤ the committed one never change (G-4a), so several statements at one p need no held snapshot.
- **`RevisionPublished` is not here.** It is a G-5c pull feed (design §3.4): the shared outbox is organisation-scoped.

## Testing

| Level | Test |
|---|---|
| Domain | `tests/Unit/Contexts/ReferenceGeography/Domain/PlaceBeliefTest.php` |
| Application | `tests/Unit/Contexts/ReferenceGeography/Application/PublishedGeographyTest.php` (fakes) |
| PostgreSQL | `tests/Feature/Contexts/ReferenceGeography/PostgresGeographyReadModelTest.php` (isolated cluster, `--filter PostgresGeographyReadModelTest`) |
| Architecture | `tests/Architecture/ReferenceGeographyPublishedContractTest.php`; the ratchet's published-language list |

## Pitfalls

- **Answers are sorted lists of unit ids;** names come back in a fixed order. Don't rely on insertion order.
- **`place()` returns null only when nothing about the unit is published at p.** An ended place returns `exists: false`.
- **`subjectSlots` scans the slot keys with `LIKE`.** Fine before G-8's data; add an index when measured.
- **Source names only.** CLDR display names are G-7.

**Traceability:** PBDIGIT-346 G-5a · v3.11 §4, §6, §10, §12 · D-73, D-147, D-225 · Hexagonal DDD baseline §4.

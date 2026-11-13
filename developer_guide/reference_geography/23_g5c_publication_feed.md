# 23: G-5c, the publication feed (PBDIGIT-346)

**Design:** `docs/publicdigit/architecture/reviews/2026-11-13-reference-geography-g5-read-models-design.md` §3.4, §5. **Evidence:** `docs/publicdigit/reference-geography/evidence/2026-11-13-pbdigit346-g5c/`.

## In business terms

**Other parts of PublicDigit can learn what official geography changed, and when.**
- **Each change is listed once, in order,** with when it was recorded, which revision made it, under which governance rule, what kinds of fact it touched, which places it concerns, and any identities merged.
- **A reader remembers where it stopped and asks for what came after.** It never misses or repeats a change.
- **A reader may raise a review signal; it must never move members or change belonging by itself** (context map).

## How to use it (a future consumer, after D-147)

```php
$geo = app(GeographyReadModel::class);
$k = $geo->knowledgeAt($geo->knowledgeAtTime($now)->position);   // or any recorded knowledge
$last = $myStoredLastPosition;                                  // 0 the first time
do {
    $page = $geo->publicationsSince($last, $k, 100);
    foreach ($page as $entry) {
        // $entry->position, ->recordedAt, ->kind (revision|notice), ->revisionId, ->contentVersion,
        // ->governanceRule, ->claimKinds, ->subjects (unit ids), ->merges ([identity, canonical|null])
        raiseReviewSignalsFor($entry);           // never an automatic change
        $last = $entry->position;                // store it in the SAME transaction as your signals
    }
} while ($page !== []);
```

**These consumer obligations are also written in the contract** (`GeographyReadModel::publicationsSince` docblock). The contract can't enforce them, so a consumer's own tests must: an atomic last-position store, and review signals only.

**Idempotency comes from the stream itself.** Positions are gap-free, unique and immutable (G-4a), so storing the last position processed is enough. Store it in the same transaction as the work it caused.

## Where it fits

| Element | Layer | Role |
|---|---|---|
| `Domain\PublicationSummary::of` | Domain | what a publication concerns: kinds, subjects, merges (sorted, distinct) |
| `PublishedGeography::publicationsSince` | Application | boundary, limits (1 to 500), negative refused, empty past the knowledge |
| `Ports\PublicationChanges`, `GeographyReadSource::publicationsBetween` | port | one row's provenance plus its claims' slot keys and values |
| `PostgresGeographyReadSource::publicationsBetween` | adapter | two queries; no claim mapping needed |
| `Published\PublicationEntry` | published language | the entry |

## Design decisions

- **Pull, not the shared outbox:** that outbox is organisation-scoped, and Reference Geography is central. The stream already *is* the ordered, immutable event log (§3.4). A push is added only for a real consumer.
- **Provenance without persons:** the stewards' references stay internal.
- **A notice (D-90, G-6) appears as `kind: notice`,** with empty lists.

## Testing

- `PublicationSummaryTest`: domain.
- `PublishedGeographyTest` (`test_the_feed_…`): order, paging, refusals, reproducibility.
- `PostgresGeographyReadModelTest::test_the_feed_…`: gap-free; never unpublished work; provenance equals the stored row; paging loses and repeats nothing; a later publication doesn't change a feed read at the same knowledge.

**Traceability:** PBDIGIT-346 G-5c · v3.11 §6, §10, §12 · context map ("review signals only") · D-147.

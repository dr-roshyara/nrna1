# 25: G-6b, objections (PBDIGIT-346)

**Design:** strategic baseline S1.1; D-84; D-84a; v3.11 §15, §16. **Evidence:** `docs/publicdigit/reference-geography/evidence/2026-11-13-pbdigit346-g6b/`.

## In business terms

**An objection is an organisation saying "this official fact is wrong, and here is the instrument that shows it."**
- **It changes nothing by itself:** the published fact stays as it is until an outcome is recorded, so lodging an objection can't be used to unsettle official geography.
- **A steward assesses it:** is the instrument authentic, did its issuer have the mandate, does it apply?
- **Then exactly one outcome:**
  - **corrected:** a new revision is proposed that corrects the fact;
  - **disputed:** a new revision is proposed that records a dispute marker. The value stays; consumers see it is contested;
  - **rejected:** with the reason. **Only possible if a finding was negative.**
- **The steward who recorded the objection on the organisation's behalf may not assess or decide it** (D-84a).
- **Asking again needs new evidence.**

## Where it fits

| Class | Layer | Role |
|---|---|---|
| `Objection` (+ `ObjectionId`, `ObjectionState`, `ObjectionAssessment`, `ObjectionSnapshot`, `ObjectionRefused`) | Domain | lifecycle; outcome versus findings; D-84a (recorder never assesses or decides); only rebuilt via `fromSnapshot` |
| `ObjectionService` | Application | `submitOnBehalf`, `assess`, `correct(proposedRevisionId)`, `dispute(proposedRevisionId)`, `reject(reason)` |
| `Ports\ObjectionRepository` (`evidenceCitedAgainst`), `Ports\PublishedClaims`, `StoredObjection`, `ObjectionChangedConcurrently` | Application | storage; the published-claim check |
| `PostgresObjectionRepository`, `PostgresPublishedClaims`, `refgeo_objections` (central migration) | Infrastructure | CHECKs mirror the lifecycle, including recorder ≠ assessor or decider; one objection per revision |

## How to use it

```php
$objections = app(ObjectionService::class);                    // the actor is the signed-in steward
$objections->submitOnBehalf($id, $organisationId, $publishedClaimId, 'The 2026 gazette moves Ward 33', ['sha256:…']);
$objections->assess($id, authentic: true, mandate: true, applicable: true);   // by ANOTHER steward (D-84a)
$objections->correct($id, $newRevisionId);   // or dispute($id, $newRevisionId): PREPARES that revision; fill, approve, publish it
// or, if a finding was negative: $objections->reject($id, 'The issuer had no mandate for ward boundaries');
```

## Design decisions

- **Outcomes propose revisions** (as candidates do since the G-6a provenance fix): the lifecycle is checked first (nothing is prepared if refused), then `RevisionService::prepare`, then the objection is saved.
- **New evidence:** the application refuses an objection whose evidence is all already cited against that claim.
- **Recording on an organisation's behalf:** direct organisation submission waits for the Organisation integration (D-147).
- **Open item (TECHNICAL, recorded, not built):** the roster's conflict declarations are keyed by revision. So a steward who belongs to the objecting organisation, *other than the recorder*, isn't detected for a **rejection**. Corrections and disputes are covered, because their revisions pass full D-84a governance. The remedy is conflict declarations keyed by objection, in a later slice.

## Testing

- **Domain:** `ObjectionTest` (6 tests). **Application:** `ObjectionServiceTest` (5). **PostgreSQL:** `PostgresObjectionRepositoryTest` (6).
- **Mutation:** M1 to M8 killed, covering the G-6a and G-6b invariants (`…-g6b/g6b-07-mutation-check.out`).

**Traceability:** PBDIGIT-346 G-6b · strategic baseline S1.1 · D-84 · D-84a · D-147 · v3.11 §16.

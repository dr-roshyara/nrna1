# 24: G-6a, candidates (PBDIGIT-346)

**Design:** v3.11 §15, §16; strategic baseline (D-31; Q-1 (a)+(c); "Submit: Organisations; steward (imports)"); completion §3. **Evidence:** `docs/publicdigit/reference-geography/evidence/2026-11-13-pbdigit346-g6a/`.

## In business terms

**A candidate is a request: "this place is missing, or has changed; here is the evidence."**
- **A steward checks it, then either:**
  - accepts it, which **creates a new draft revision** ("Accept → ProposeRevision"). That revision goes through normal governance and publication, and belongs to this candidate only;
  - links it to an earlier candidate that asks the same thing;
  - or rejects it, with a reason.
- **A candidate never becomes official geography by itself.**
- **Who did what is recorded** (as platform person references, never names).
- **Today only stewards can record candidates** (for example, data imports). Organisations will submit through the Organisation integration, deferred by D-147.

## Where it fits

| Class | Layer | Role |
|---|---|---|
| `Candidate` (+ `CandidateId`, `CandidateState`, `CandidateSubmitter`, `CandidateSnapshot`, `CandidateRefused`) | Domain | lifecycle submitted → under verification → accepted · linked · rejected; final once decided |
| `CandidateService` | Application | `submitImport`, `startVerification`, **`accept(proposedRevisionId)`: prepares that new revision through `RevisionService`**, `linkTo(primary)`, `reject(reason)`. Actor from `ActingPerson`; a steward at the current roster version |
| `Ports\CandidateRepository`, `StoredCandidate`, `CandidateChangedConcurrently` | Application | storage with optimistic concurrency |
| **`Ports\RosterReader`** | Application | a **read-only** view of the roster; `StewardRosterStore` extends it |
| `PostgresCandidateRepository`, `refgeo_candidates` (central migration) | Infrastructure | rows → aggregate only via `fromSnapshot`; the table's CHECKs mirror the lifecycle |

## How to use it

```php
$candidates = app(CandidateService::class);                    // the actor is the signed-in steward
$candidates->submitImport($id, 'Ward 33 created by the 2026 act', null, ['sha256:…']);
$candidates->startVerification($id);
$candidates->accept($id, $newRevisionId);                      // PREPARES this new draft revision; fill, approve and publish it on its own path
// or $candidates->linkTo($duplicateId, $primaryId); or $candidates->reject($id, 'No competent instrument');
```

## Design decisions

- **A request, not a fact** (Q-1 (a)+(c)): acceptance **proposes a new revision** (closure design: "Accept (→ ProposeRevision)"). That revision is validated, approved and published under G-2 to G-4.
- **Provenance (fixed after the Principal review of 2026-11-13):** the first version accepted by naming any *existing* revision, so an unrelated one could be attached. Now:
  - acceptance prepares the revision itself (an existing id is refused);
  - **a unique index** (`2026_11_13_181000`) ensures one revision belongs to at most one candidate;
  - the order is: check the lifecycle (nothing is prepared if acceptance is refused) → prepare → save the candidate. If the final save loses a race, an unpublished draft remains, which is not a fact.
- **Links point at a primary, never at another link:** no duplicate chains.
- **No write-side ports.** The accepted no-bypass guard refused `CandidateService` holding `RevisionRepository` and `StewardRosterStore`. It now reads the roster through `RosterReader` and touches revisions only through the authorised `RevisionService`. **The guard was not widened.** (A read-only `RevisionCatalogue` existed briefly and was removed once unused.)
- **No organisation submissions yet** (D-147). `CandidateSubmitter::organisation(id)` exists for the domain and storage.

## Testing

- `tests/Unit/Contexts/ReferenceGeography/Domain/CandidateTest.php`: lifecycle, refusals, rebuild.
- `tests/Unit/Contexts/ReferenceGeography/Application/CandidateServiceTest.php`: steward-only, actor, existing revision, links.
- `tests/Feature/Contexts/ReferenceGeography/PostgresCandidateRepositoryTest.php`: round trips, stale save refused, the table's refusal, a corrupt row, wiring.

**Traceability:** PBDIGIT-346 G-6a · v3.11 §15, §16 · D-31 · Q-1 · D-147 · D-225.

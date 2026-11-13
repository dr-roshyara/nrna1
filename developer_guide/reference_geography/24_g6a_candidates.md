# 24: G-6a, candidates (PBDIGIT-346)

**Design:** v3.11 §15, §16; strategic baseline (D-31; Q-1 (a)+(c); "Submit: Organisations; steward (imports)"); completion §3. **Evidence:** `docs/publicdigit/reference-geography/evidence/2026-11-13-pbdigit346-g6a/`.

## In business terms

**A candidate is a request: "this place is missing, or has changed; here is the evidence."**
- **A steward checks it, then either:**
  - accepts it, by preparing a revision that goes through normal governance and publication;
  - links it to an earlier candidate that asks the same thing;
  - or rejects it, with a reason.
- **A candidate never becomes official geography by itself.**
- **Who did what is recorded** (as platform person references, never names).
- **Today only stewards can record candidates** (for example, data imports). Organisations will submit through the Organisation integration, deferred by D-147.

## Where it fits

| Class | Layer | Role |
|---|---|---|
| `Candidate` (+ `CandidateId`, `CandidateState`, `CandidateSubmitter`, `CandidateSnapshot`, `CandidateRefused`) | Domain | lifecycle submitted → under verification → accepted · linked · rejected; final once decided |
| `CandidateService` | Application | `submitImport`, `startVerification`, `accept(revision)`, `linkTo(primary)`, `reject(reason)`. Actor from `ActingPerson`; a steward at the current roster version |
| `Ports\CandidateRepository`, `StoredCandidate`, `CandidateChangedConcurrently` | Application | storage with optimistic concurrency |
| **`Ports\RosterReader`, `Ports\RevisionCatalogue`** | Application | **read-only** views of the roster and of revisions. `StewardRosterStore` and `RevisionRepository` extend them |
| `PostgresCandidateRepository`, `refgeo_candidates` (central migration) | Infrastructure | rows → aggregate only via `fromSnapshot`; the table's CHECKs mirror the lifecycle |

## How to use it

```php
$candidates = app(CandidateService::class);                    // the actor is the signed-in steward
$candidates->submitImport($id, 'Ward 33 created by the 2026 act', null, ['sha256:…']);
$candidates->startVerification($id);
$candidates->accept($id, $revisionId);                         // the revision must exist; publish it on its own path
// or $candidates->linkTo($duplicateId, $primaryId); or $candidates->reject($id, 'No competent instrument');
```

## Design decisions

- **A request, not a fact** (Q-1 (a)+(c)): acceptance only names a revision. That revision is validated, approved and published under G-2 to G-4.
- **Links point at a primary, never at another link:** no duplicate chains.
- **Read-only ports.** The accepted no-bypass guard refused `CandidateService` holding `RevisionRepository` and `StewardRosterStore`, the write-side ports. **Interface segregation fixed it; the guard was not widened.** Use `RosterReader` and `RevisionCatalogue` whenever a use case only *checks*.
- **No organisation submissions yet** (D-147). `CandidateSubmitter::organisation(id)` exists for the domain and storage.

## Testing

- `tests/Unit/Contexts/ReferenceGeography/Domain/CandidateTest.php`: lifecycle, refusals, rebuild.
- `tests/Unit/Contexts/ReferenceGeography/Application/CandidateServiceTest.php`: steward-only, actor, existing revision, links.
- `tests/Feature/Contexts/ReferenceGeography/PostgresCandidateRepositoryTest.php`: round trips, stale save refused, the table's refusal, a corrupt row, wiring.

**Traceability:** PBDIGIT-346 G-6a · v3.11 §15, §16 · D-31 · Q-1 · D-147 · D-225.

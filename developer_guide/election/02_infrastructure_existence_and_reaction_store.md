# 02 — Infrastructure: existence ACL + reaction-state store (PB-004 Step 4A.3)

## Purpose

Give the Election reaction a real persistence backing **without** the Election context owning election lifecycle yet. The domain still only sees `ElectionRepository::find()`; this slice supplies the concrete realization, composed from two independent sources.

## Where it fits (layer / namespace)

```
app/Contexts/Election/
  Application/Port/
    ElectionExistencePort.php          # "does this election exist?" (Strangler seam)
    AppliedDeterminationLedger.php      # greenfield reaction-state ledger (idempotency set)
  Infrastructure/
    Repository/CompositeElectionRepository.php     # implements Domain ElectionRepository
    Acl/LegacyElectionExistenceAdapter.php         # reads legacy `elections` (read-only)
    Persistence/EloquentAppliedDeterminationLedger.php
    Models/ElectionAppliedDeterminationModel.php   # BelongsToTenant
    Database/Migrations/Tenant/..._create_election_applied_determinations_table.php
    Providers/ElectionServiceProvider.php          # bindings + migration loading
```
`ElectionServiceProvider` is registered in `config/app.php` beside the sibling greenfield providers.

## Design decisions (traceable to the approved IDD)

- **Aggregate Reconstruction Invariant.** `CompositeElectionRepository::find()` reconstructs the aggregate from **existence** (`ElectionExistencePort`) **+** **reaction state** (`AppliedDeterminationLedger`). `Election ≠ legacy row`; no single source is authoritative for the whole aggregate.
- **Existence via ACL (Strangler).** `LegacyElectionExistenceAdapter` reads the legacy `elections` table — the *current operational source of truth* — read-only, tenant-scoped, soft-delete aware, identity-only. It is the only code that knows the legacy schema. Swapping the `ElectionExistencePort` binding is the entire Strangler exit; the domain never changes.
- **Business absence ≠ infrastructure failure.** `exists()` returns `false` only for a successfully-answered "no such election in this org" → `null` → `CannotApplyDeterminationToUnknownElection` (permanent). A query/connection error **propagates** (never becomes `false`), so a transient fault is retried by the inbox relay, not dead-lettered. This is the load-bearing failure rule (Blueprint §8 taxonomy; IDD-level, no ADR).
- **Tenant scope is infrastructure.** The domain and ports express only `ElectionId`; the adapter and the `BelongsToTenant` model resolve the ambient organisation. Cross-org → not found.
- **Reaction store is greenfield-owned** and named `election_applied_determinations` (NOT `elections`, which the legacy platform owns). Append-only idempotency ledger, tenant-scoped unique `(organisation_id, election_id, determination_id)`.

## How it works (code)

```php
// CompositeElectionRepository
public function find(ElectionId $id): ?Election
{
    if (!$this->existence->exists($id)) {   // legacy ACL, tenant-scoped; throws on infra failure
        return null;                        // business absence → unknown election
    }
    return Election::reconstitute($id, $this->corrections->appliedDeterminations($id));
}

public function save(Election $election): void
{
    $this->corrections->remember($election->id(), ...$election->appliedDeterminationIds());
}
```
`remember()` is idempotent (`updateOrCreate` on election+determination within the tenant), so re-persisting an already-recorded correction keeps a single row.

## How to use / extend

- The reaction handler depends only on `ElectionRepository`; resolve it from the container — the provider wires `CompositeElectionRepository`.
- **Strangler exit (future):** when a greenfield Election-lifecycle capability exists, bind `ElectionExistencePort` to a greenfield adapter in `ElectionServiceProvider`. Nothing else changes.

## Testing

- Unit (`tests/Unit/Contexts/Election/CompositeElectionRepositoryTest.php`): composition logic over in-memory doubles — existence/absence, cross-reload idempotency, **infra-failure propagation** (not swallowed to null), save persists the applied set.
- Feature/DB (`tests/Feature/Contexts/Election/`): `LegacyElectionExistenceAdapterTest` (exists / absent / cross-org / soft-deleted) and `EloquentAppliedDeterminationLedgerTest` (empty / round-trip / idempotent single row / tenant-scoped).
- **Harness note:** this pgsql suite disables per-test transaction rollback (`Tests\TestCase::beginDatabaseTransaction` is a no-op for pgsql; isolation is `migrate:fresh` once per process). Feature tests therefore **create a unique organisation per test** and use **tenant-scoped assertions** — never global `assertDatabaseCount`, which is unsafe when rows accumulate across methods.

## Pitfalls

- **Never catch the adapter's query error and return `false`** — that would misclassify a transient infra fault as a permanent unknown-election and wrongly dead-letter the message.
- **Do not name the greenfield table `elections`** — that name belongs to the legacy platform; greenfield does not own election lifecycle yet.
- **Do not import legacy models into the domain.** Even the ACL adapter reads the legacy table via a raw tenant-scoped query, not `App\Models\Election`, to minimise coupling.
- **Anonymity:** the adapter must only ever read `elections` (no vote/voter columns); never `votes`/`results` (ADR-T11).

## Traceability

PB-004 Step 4A.3 · IDD `.claude/plans/PB-004-step4-election-infrastructure.md` (4A.2 design + Architectural Invariants incl. #9 Aggregate Reconstruction) · ADR-T16 (tenant-free domain, local VOs) · ADR-T11 (anonymity) · ADR-T1 (one aggregate per transaction) · Blueprint §8 (transient vs permanent failure) · Strangler pattern · ER-03/04 (reuse: ClockInterface, BelongsToTenant, sibling persistence pattern).

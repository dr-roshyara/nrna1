# 03 — Challenge persistence (PB-005 Step 5B)

## Purpose
Give the Challenge aggregate a real persistence backing so the reaction can load, transition, and save it. Contestation **owns** the Challenge, so this is a **normal single-source repository** — no ACL, no multi-source reconstruction (contrast PB-004's Election, which reacted to a legacy-owned aggregate).

## Where it fits (layer / namespace)
```
app/Contexts/Contestation/Infrastructure/
  Repositories/EloquentChallengeRepository.php   # implements Domain ChallengeRepository
  Persistence/ChallengeMapper.php                # SOLE row<->aggregate translation
  Models/ChallengeModel.php                       # BelongsToTenant
  Database/Migrations/Tenant/..._create_challenges_table.php
  Providers/ContestationServiceProvider.php       # repo binding + migrations (config/app.php)
```

## Design decisions
- **The table carries the FULL aggregate shape** (ARB ruling): the repository persists the *aggregate*, not the current backlog scope. Raise-time columns (`raiser_standing_ref`, `contested_election_id/type/target_id`, `submitted_content`) exist now but are **nullable**; a later backlog item populates them **additively** — no future migration/mapper redesign.
- **The reaction slice writes only reaction-relevant state** (`state`, `determination_id`, timestamps). The mapper safely leaves future-owned columns null; the aggregate stays **persistence-ignorant**; `Challenge::reconstitute(id, state, ?determinationId)` is unchanged from 5A.
- **`findByDeterminationId` is a correlation capability, not an identity.** `ChallengeId` remains the sole aggregate identity; `determination_id` is a lookup index (a Challenge holds one determination once adjudicated). Used by the resolution reaction because `ElectionCorrectionApplied` carries no challengeId.
- **Tenant scope is infrastructure** — `ChallengeModel` uses `BelongsToTenant` (global scope + auto-fill); the domain and repository ask purely intra-tenant questions.
- **The mapper is the single translation point** (row↔aggregate, ADR-T16 string↔VO). The repository makes no business decisions.

## How it works (code)
```php
// EloquentChallengeRepository
public function save(Challenge $c): void {
    $this->model->newQuery()->updateOrCreate(['id' => $c->id()->toString()], $this->mapper->toRow($c));
}
public function find(ChallengeId $id): ?Challenge { /* whereKey -> mapper->toAggregate */ }
public function findByDeterminationId(DeterminationId $id): ?Challenge { /* where determination_id -> toAggregate */ }
```
`toRow()` maps only `id`, `state`, `determination_id`; `toAggregate()` reconstitutes via `Challenge::reconstitute(...)`.

## Testing
- Feature/DB (`tests/Feature/Contexts/Contestation/EloquentChallengeRepositoryTest`): save+reconstitute state · reaction transition across reload · `findByDeterminationId` correlation · tenant scoping.
- **Harness note:** pgsql has no per-test rollback (see `docs/architecture/Architecture_Handover_Release_2.0.md` §11). `challenges.id` is a global PK → tests use **unique challenge/determination ids per test** + tenant-scoped assertions.

## Pitfalls
- Don't add raise-time columns to the mapper in this slice; they are owned by a later ticket (the migration already has them, nullable).
- Never let persistence leak into the aggregate; the mapper is the only translation point.
- `determination_id` is nullable and indexed for correlation — do not treat it as the primary/identity key.

## Traceability
PB-005 Step 5B · IDD §6 (persistence) · ARB ruling (full aggregate schema; repository persists the aggregate, not the backlog scope) · ADR-T16 (tenant-free domain, local VOs) · ER-03 (reuse the Adjudication persistence pattern).

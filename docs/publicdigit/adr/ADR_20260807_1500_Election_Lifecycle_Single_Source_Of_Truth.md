# ADR — Election Lifecycle as the Single Source of Truth for Election State

**Date:** 2026-08-07 · **Status:** ACCEPTED — records decisions made by the Product Owner 2026-08-06/07 (`PBDIGIT-48` Option B approval and implementation review); written at the Product Owner's request as the durable record
**Deciders:** Product Owner · **Recorded by:** Engineering
**Traceability:** `PBDIGIT-48` (discovery rev 2 + completion report) · `PBDIGIT-58` (epic; `58A` observation, `58B` first migration) · `PBDIGIT-47` (the customer-visible symptom) · `PBDIGIT-59` (open successor decision) · `docs/pks/2026-08-06-legacy-consumer-migration-pattern-candidate.md`

---

## Decision

> **`ElectionLifecycle` — the facade over `ElectionLifecycleEngineImpl` — is the single source of truth for election state. Production code makes election-state business decisions only by asking it. The legacy columns `status`, `is_active` and `state` are compatibility artifacts: written by the bridge, rendered by displays, decided on by nothing.**

The consumer-facing contract is the facade's *business questions*, not its state vocabulary:

```php
ElectionLifecycle::of($election)->canVote()          // is this election accepting votes?
ElectionLifecycle::of($election)->isActive()         // is voting open now?
ElectionLifecycle::of($election)->isVotingPhase()    // voting now, or upcoming with setup complete?
ElectionLifecycle::of($election)->canManageVoters()  // may the voter roll change?
ElectionLifecycle::of($election)->state()            // the state itself, when a consumer truly needs it
```

Consumers ask questions; only `derivePermissions()` knows which states answer them. A future state (`voting_paused`, an emergency stop) changes one method, not every caller.

## Why the legacy fields existed — three generations in one model

| Generation | Fields | What they were |
|---|---|---|
| 1 | `status`, `is_active`, `results_published` | hand-maintained projections from the original platform: a coarse label, an "on" flag, a visibility flag |
| 2 | `state` | an explicitly persisted state written by `transitionTo()` |
| 3 | engine + snapshot + facade | state **computed** from constitutional facts: `suspended_at`, `archived_at`, `results_published_at`, the voting-window clock, setup completion |

Each generation was added beside its predecessor and **consumers were never moved** — the platform's recurring migration failure (authority moves, consumers stay; observed in election state, votes-per-IP, voter eligibility, results publication, demo availability).

## Why the lifecycle is authoritative — the argument that decided it

**Election state is a function of time, and nothing writes columns when time passes.** A voting window opens at 10:00 because the clock reached 10:00; there is no scheduler, so no event exists that could set `status='active'` at that moment. A persisted column is therefore *structurally* incapable of answering "is voting open now?" — it is stale by construction, not by negligence. Only computation over timestamps can answer a time-dependent question, and `getState()` is that computation (suspension checked first, then archival, publication, counting-with-setup-legitimacy, the clock).

This was not theoretical. `namaste 2026` sat at `status='planned'` while its voting window was open, and the login router — a Generation-1 consumer — sent a voter to the organisation homepage instead of the ballot (`PBDIGIT-47`). The same election later showed `is_active=true` while `results_published`. **Both directions of staleness occurred in one election's lifetime.**

The repository had, in fact, already decided this: `DeprecationPolicy` names the replacement for each legacy field, the backfill command calls the engine the "SSOT engine", and the facade documents itself as the sole consumption point. `PBDIGIT-48`'s discovery finding was that **the authority was declared but the migration was unfinished** — not that a decision was missing.

## Option B — complete migration, not permanent synchronisation

Two strategies were on the table. **Option A (transitional synchronisation):** keep legacy fields correct forever by syncing them on every transition. **Option B (complete migration):** move every consumer to the authority, then retire the fields. **The Product Owner approved Option B (2026-08-06)** with a Migration Invariant — during migration, legacy fields may be maintained *only* as a temporary compatibility bridge, and the **Definition of Done is zero production readers**, at which point the fields (and the bridge) are removed. Option A was rejected because a permanently synchronised copy is a second authority waiting for the first missed sync — and because new features demonstrably get built against whichever representation is writable (`PBDIGIT-60`).

## The Legacy Compatibility Adapter — why the bridge writes are not a violation

The state machine's side effects still write legacy fields (e.g. `applySideEffectsForCloseVoting`, `applySideEffectsForPublishResults`). This is the **bounded, intentional bridge**: it keeps unmigrated readers and UI projections fresh *while the migration is in flight*. It is legitimate exactly as long as it is (a) the only writer of those fields outside creation defaults and (b) scheduled for removal with the fields themselves. Writing the fields from anywhere else — a settings form, a feature — is the defect class this ADR exists to end.

## What PBDIGIT-48 implemented (2026-08-06/07)

Every business decision found on the deprecated fields was classified into the Product Owner's four buckets; Bucket 1 was migrated:

| Capability | Consumer | Now asks |
|---|---|---|
| Election Entry Resolution | `User::getActiveElection()` / `countActiveElections()` | `canVote()` |
| Election Context Resolution | `ElectionMiddleware` (real branch) | `isActive()` |
| Public Discoverability | `SitemapController::elections()` | `isVotingPhase()` |
| Voter Import Targeting | `OrganisationUserImportController` | `canManageVoters()` |
| Organisation Reporting | `OrganisationController` (3 stat blocks) | `isActive()` · terminal states |

Kept deliberately: the **demo family** (Bucket 2 — see below), **UI projections** (Bucket 3 — render, never branch), and one deferred one-liner behind `PBDIGIT-61`. Dead code (Bucket 4) goes to cleanup, not migration. Each migration shipped with behaviour evidence (tests, before/after HTTP comparison, or both) and the login-routing fix was browser-verified end-to-end.

## The demo exception — a different concept, not an unfinished migration

For **demo** elections, `is_active` does not mean "voting is open"; it means **"the demo platform is switched on."** Demo runs outside the constitutional lifecycle by design (demo/production policy contexts ADR), and demo elections have `NULL` voting windows, so the engine reports `draft` forever — migrating those reads today would take the demo platform down. Those sites carry `LEGACY COMPATIBILITY` markers in code. **This is one field carrying two business concepts** — the same conflation as `results_published` (publication vs visibility) — and its honest resolution is a demo-availability concept of its own, not a lifecycle read.

## Removal — deferred, sequenced, not hesitant

The columns are **not** dropped by this ADR. The order:

1. **`PBDIGIT-59`** decides which timestamps are constitutional and what demo windows mean — the gate on everything below.
2. The demo family migrates to a demo-availability concept.
3. Bucket 3 display fields retire from payloads.
4. `DeprecationPolicy` enforcement escalates level by level **with zero violations** — the mechanism proves the migration, rather than an inventory asserting it (the static inventory mis-attributed 19 of 46 hits; the `58A` runtime observer exists because text search cannot name a query's subject).
5. The columns and the compatibility bridge are removed together.

## Consequences

* **New code never reads `status` / `is_active` / `state` for a decision.** If a needed business question has no facade method, the method is added to the lifecycle — the vocabulary does not leak.
* **New code never writes those fields.** Only the bridge writes them, until step 5 removes both.
* `canVote()` is **election-level** ("is this election accepting votes?"), not voter-level; voter eligibility is a separate concern with its own unresolved authority question (MB-5).
* SQL narrows candidates (organisation, type, not-already-voted); the lifecycle decides state — time-dependent predicates cannot live in `WHERE` clauses.
* Tests and fixtures express the constitutional model (voting windows), not legacy persistence — a fixture that sets `status='active'` instead of a window is describing the world this ADR retires.

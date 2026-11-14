# 07: The organisation queue E-2 (background work in the right organisation), PBDIGIT-345 task 7

**Design:** platform routing tactical design E-2, §3.10 (Q-1 … Q-8), §3.10.1, §3.10.2; C-2; D-178 ISO-6.
**Proofs:** T-3, T-4, T-19 (twice), T-20 … T-23, T-25. **T-24 is carried to task 9** (see below).
**Evidence:** `docs/publicdigit/platform/evidence/2026-11-14-pbdigit345-task7/`.

## Business purpose

**Background work must reach the right organisation's database, exactly like a web request.** Examples are a newsletter delivery, or a scheduled membership step. **One organisation's failing job must never stop the others.**

**The old (legacy) background work is untouched:** it still runs immediately, inside the request (`sync`).

## Two queue connections, never one

| Connection | Who uses it | Storage | Worker |
|---|---|---|---|
| the default `sync` (**unchanged**) | the 25 legacy `ShouldQueue` classes (none sets its own connection) | none: runs inline; a failure throws to the caller; no `failed_jobs` row | none |
| **`organisation`** (new) | **only** subclasses of `OrganisationJob` | `organisation_jobs` on the **central** database; failures in the standard `failed_jobs` with `connection = 'organisation'` | `php artisan queue:work organisation` |

## Components (`app/Contexts/Platform/Infrastructure/Queue/`)

| Class | Role |
|---|---|
| `OrganisationJob` (abstract) | the envelope: `organisationId` (a UUID); the connection is fixed to `organisation` at construction; **`final middleware()`** = `[EstablishOrganisationContext]`; `organisation()` gives the context in `handle()`. No `Dispatchable` (no `X::dispatch()`), no `SerializesModels` |
| `OrganisationJobDispatcher` | **the only dispatch path.** Refuses (`OrganisationDispatchRefused`): a non-organisation job; a connection override; another organisation than the current `OrganisationContext` (when one is bound); **a dispatch while that organisation's connection is inside a transaction** (`DispatchInsideOrganisationTransactionRefused`, Q-7) |
| `EstablishOrganisationContext` | runs on **every attempt** (see the flow below) |
| `OrganisationJobRefused` | `reason`: `wrong-connection`, `not-an-organisation-job`, `no-organisation-id`, `unknown`, `not-operational`, `schema-not-current` |

## Flow of one attempt (`EstablishOrganisationContext`)

```
arrived on connection `organisation`?   no → throw OrganisationJobRefused(wrong-connection)   (inline / sync / another connection)
organisation id present (UUID)?        no → $job->fail(no-organisation-id)          (no retry, never runs)
ResolutionService (the REGISTRY again)    → not routable → $job->fail(<reason>)     (a retry re-resolves: Q-5)
OrganisationConnectionFactory::establish → bind OrganisationContext for this attempt
handle()
finally: forget the context, release (purge) the connection   (C-2: nothing survives into the next job)
```

**Registry or cluster unavailable:** the exception propagates and the worker retries later (fail closed).

## Writing an organisation job

```php
final class SendNewsletterIssue extends OrganisationJob      // identifiers only (Q-6)
{
    public function __construct(TenantId $organisation, public readonly string $issueId)
    {
        parent::__construct($organisation);
    }

    public function handle(): void
    {
        $db = $this->organisation()->connection;   // that organisation's database, never DB::
        // …
    }
}

app(OrganisationJobDispatcher::class)->dispatch(new SendNewsletterIssue($organisationId, $issueId));
```

**Rules (architecture-tested in `OrganisationQueueArchitectureTest`):**
- never `X::dispatch()`, `Bus::`, `Queue::` or `->onConnection(` for an organisation job: the dispatcher only;
- never name the `organisation` connection anywhere else;
- every queued class that uses `OrganisationContext` must extend `OrganisationJob`;
- **no Eloquent models in the payload.** `SerializesModels` would restore a model with its stored connection name, which is a leak path;
- **work that must follow an organisation commit goes through that organisation's outbox, never a direct dispatch** (Q-7). Dispatching inside the transaction is refused.

## T-24 is carried to task 9 (disagreement with the plan's test list)

T-24 needs:
- the **organisation's outbox** (a rolled-back transaction leaves no outbox row, so no job);
- the **relay**, which dispatches only committed rows, outside any transaction.

**The organisation database has no outbox yet** (Membership guide 23), and the per-organisation relay is task 9 (E-5). Task 7 proves the dispatch side of Q-7 (T-25). **T-24 runs in task 9**, where the outbox and relay are built.

## Testing

```
vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Unit/Contexts/Platform tests/Architecture/OrganisationQueueArchitectureTest.php
MEMBERSHIP_PG_IMAGE=<pinned> MEMBERSHIP_PG_EVIDENCE_DIR=<dir> bash scripts/testing/membership-pg.sh --testsuite PlatformPostgres --filter 'OrganisationQueueTest|LegacyQueueCharacterisationTest'
```

- **`LegacyQueueCharacterisationTest`: T-19.**
  - Written and run **before** any queue change (commit `668469223`), then re-run **unchanged**.
  - A legacy-shaped job and queued mail run inline; a failing one throws with no `failed_jobs` row.
- **`OrganisationQueueTest`:**
  - **T-20:** `organisation_jobs`, never `jobs`; dispatcher refusals.
  - **T-25:** the open transaction.
  - **T-21:** inline, sync, another connection.
  - **T-4:** no organisation id.
  - **T-3 / T-22:** a **real** `queue:work organisation` child process runs A, B, A, B. Each job sees only its own database, on a **new** PostgreSQL backend each time, with no other organisation connection open.
  - **T-23:** a **real** worker. A fails and is released; A becomes `migration-failed`; the retry is refused with `schema-not-current` into `failed_jobs` (`connection = organisation`, identifier-only). B completes throughout.
  - **T-19 step 2:** the default is still `sync`; a legacy job never reaches `organisation_jobs`.
- **`OrganisationJobEnvelopeTest` (unit):** the envelope, the final middleware, the required valid id.

**Worker child processes in tests** get an explicit environment that points every connection at the isolated cluster, as in guide 05.

**Traceability:** PBDIGIT-345 task 7 · E-2, §3.10, §3.10.1, §3.10.2, C-2 · ISO-6 · T-3, T-4, T-19 … T-23, T-25.

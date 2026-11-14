# 08: Fan-out E-3 (one scheduled operation, every organisation), PBDIGIT-345 task 8

**Design:** platform routing tactical design E-3, Q-8, §3.4 (at least once). **Proof:** T-5.
**Evidence:** `docs/publicdigit/platform/evidence/2026-11-14-pbdigit345-task8/`.

## Business purpose

**Some work must happen for every organisation on a schedule.** Examples are a nightly membership step, or a periodic newsletter run. PublicDigit gives **each operational organisation its own job**, through the organisation queue of guide 07:
- an organisation that isn't operational (pending, provisioning, a failed migration) gets **nothing**, and that is reported;
- **one organisation failing never stops the others**, neither when the jobs are queued nor when they run;
- the scheduled command **never opens an organisation database**; only the jobs do, each in its own.

## Ownership and components

**Platform owns the fan-out** (routing). **The operation itself belongs to the context that owns the work:** a future Membership or Communication job class.

| Layer | Component | Role |
|---|---|---|
| Application | `FanOutToOrganisations::run(OrganisationWorkDispatch): FanOutReport` | for every registered organisation, in registry order: ask **`ResolutionService`** (the registry's own rule); not routable → **skipped** with its reason; otherwise **one** dispatch; a failing dispatch is **recorded** and the run continues |
| Application port | `RegisteredOrganisations::ids()` | every registry id, stable order (a read model; eligibility is not decided here) |
| Application port | `OrganisationWorkDispatch::dispatchFor(TenantId)` | hand one organisation its share |
| Application | `FanOutReport` | `dispatched` (ids), `skipped` (id → reason), `failed` (id → error class only) |
| Infrastructure | `PostgresRegisteredOrganisations` | ids from the central registry, `ORDER BY id` |
| Infrastructure | `Queue\FanOutJobDispatch` | builds `new JobClass($organisationId)` and queues it through `OrganisationJobDispatcher`. Refuses a class that isn't an `OrganisationJob`, or whose constructor takes anything but the organisation id |
| Delivery | `Console\FanOutCommand`: `php artisan platform:fan-out <operation>` | the operation must be listed in `config('organisation_routing.fan_out')`; prints skips, failures and a summary; logs counts only; **exit 1** on an unknown or refused operation, or on any failed dispatch |

**No domain object is added:** fan-out is orchestration, not business policy.

## Adding a periodic operation (in the owning context's slice)

```php
// 1. an idempotent organisation job, constructed with the organisation id only (guide 07)
final class SendRenewalReminders extends OrganisationJob { public function handle(): void { /* … */ } }

// 2. config/organisation_routing.php
'fan_out' => ['membership.renewal-reminders' => SendRenewalReminders::class],

// 3. routes/console.php: one line per periodic task
Schedule::command('platform:fan-out membership.renewal-reminders')->dailyAt('02:00')->withoutOverlapping();
```

**Idempotency is the job's duty:**
- a run dispatches **exactly once per organisation**;
- a repeated or overlapping run dispatches **again** (at least once across runs, design §3.4);
- **so the job must make a second execution harmless**, for example by checking whether the period's work is already done in its organisation's database.

There is **no** central dedupe store: the accepted design does not have one.

## Testing

```
vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Unit/Contexts/Platform tests/Architecture/OrganisationQueueArchitectureTest.php
MEMBERSHIP_PG_IMAGE=<pinned> MEMBERSHIP_PG_EVIDENCE_DIR=<dir> bash scripts/testing/membership-pg.sh --testsuite PlatformPostgres --filter OrganisationFanOutTest
```

- **`FanOutToOrganisationsTest` (unit):** T-5's rules (one each, in order; pending and failed-schema skipped with reasons; a failing dispatch recorded while the rest continue; an unreadable registry fails the run).
- **`OrganisationFanOutTest` (isolated cluster, three organisation databases):**
  - **T-5 end to end:** the command queues A, B and C, and skips the pending one. A **real** `queue:work organisation` worker completes A and B; C's job fails in C's own database and lands in `failed_jobs`.
  - **Rejects:** an unknown operation, a non-organisation class, a wrong constructor.
  - **A failing dispatch:** exit 1 and a report per organisation.
  - **A repeated run:** one per organisation again.
  - **Production wiring:** checked.
- **`OrganisationQueueArchitectureTest`:** the fan-out code never names `OrganisationConnectionFactory`, `OrganisationContext` or the middleware.
  - **The T-25 rule is refined in this task, and decided per call** (Principal's review):
    - `->dispatch(` is allowed only on a receiver declared `OrganisationJobDispatcher $name` in the same file (constructor, property, parameter), or on `app(OrganisationJobDispatcher::class)`;
    - an undeclared receiver, any other object, or a raw Bus dispatcher import is a violation, **even if the approved class is named elsewhere in the file**;
    - Laravel's own routes stay forbidden: the `dispatch()` / `dispatch_sync()` helpers, `X::dispatch`, `Bus::`, `Queue::`, `->onConnection(`;
    - planted cases prove each allowed and forbidden route.

**Mutation evidence:** 7 mutants of the fan-out code.
- **6 KILLED by assertions:** F1 unit, F1 db, F3, F4, F5, F6.
- **F2 KILLED by an error, not an assertion:** the rethrown dispatch failure escapes the run, and that escape is itself the defect.
- **No survivors.**

**Pitfall:** `config("organisation_routing.fan_out.{$operation}")` would read `membership.renewal-reminders` as nested keys. The command indexes the list directly (found by the test).

**Traceability:** PBDIGIT-345 task 8 · E-3, Q-8, §3.4 · T-5.

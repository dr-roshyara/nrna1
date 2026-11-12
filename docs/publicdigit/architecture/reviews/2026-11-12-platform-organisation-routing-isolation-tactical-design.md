# Slice 2: Platform organisation routing and isolation — tactical design (integration delta) — PROPOSED

| | |
|---|---|
| **Status** | **PROPOSED, revision 3 (2026-11-12).** The Principal answered Q-S2-1 … Q-S2-4 in **D-219**. Revision 2 records them and adds §3.7 … §3.9, the failure rows 9–10 and tests T-13 … T-18. **Revision 3 adds the explicit queue separation (§3.10), tests T-19 … T-25 and question Q-S2-5. Its reconciliation (same revision, after the Principal's review) adds §3.10.1 (failed jobs, verified against Laravel 12.71.0: the separate failed table is withdrawn) and §3.10.2 (enforcing Q-7), re-sequences T-19, and makes acceptance T-1 … T-25.** **ACCEPTED by the Principal on 2026-11-12, as the design of the approved slice 2 plan** (`docs/plans/20261112-1844-…`). Q-S2-5 is decided by D-221. Implementation follows that plan only |
| **Commission** | Principal DDD Architect instruction, 2026-11-12, Part B ("Close Membership Slice 1 acceptance evidence and prepare Slice 2 tactical design") |
| **Consumes (does not restate or change)** | **D-178** multi-database boundary design rev 5 (`2026-11-11-multi-database-boundary-tactical-design.md`: lifecycle §3, ISO-1 … ISO-9 §5/§13.5, availability §13.6) · **D-193** Platform tenancy design rev 11 (`2026-11-11-platform-tenancy-tactical-design.md`: `ServedOrganisation`, `ResolutionService`, `OrganisationContext`, provisioning, operator channel ISO-11, registrar bootstrap F-03/M-3) and its 53-item proof register · **D-160** (release 1 serves only ledger organisations; flag at joining) · **slice 1 plan §12** (the slice 1 / slice 2 boundary) · D-147 |
| **Scope** | **Only what slice 2 must add to make those accepted designs run in this codebase:** the execution channels, the connection lifecycle, the per-organisation relay, the Membership bindings, the organisation-database schema (F-13) and the founding sequence |
| **Not in scope** | Organisation structure (slice 3) · Governance AuthorityCheck (slice 4) · Membership lifecycle (slice 5) · Forum · Newsletter · Reference Geography (D-147) · the emergency route (D-193 (5), open) |

## 0. Disagreement first: this is a delta, not a second Platform design

**The commission asks for a full tactical design of routing and isolation. Most of it already exists, accepted:**
- the routing model, the registry aggregate and the fail-closed resolver (D-193 Part 4);
- the isolation invariants ISO-1 … ISO-11 with their proofs (D-178 §5, §13.5; D-193 H-3);
- the organisation lifecycle and the creation flow (D-178 §3);
- the registrar bootstrap (D-193 F-03, M-3; D-148);
- the availability rule: fail closed when the centre is down (D-178 §13.6).

**Writing them again would create a second design** that drifts from the accepted one (ES-005.4: consume or extend, never copy). **So this document:**
1. **maps every subject of the commission to the accepted text that answers it** (§2);
2. **designs only what is genuinely new** (§3);
3. names the failure scenarios, tests and Principal questions (§4 to §6).

**Where this delta and an accepted design differ, the accepted design governs** until the Principal decides otherwise.

## 1. Business explanation

PublicDigit will serve many organisations. **Each one keeps its records in its own database**: its members, its units, its decisions. The platform keeps only a **directory**: which organisations exist, how to reach each one's database, and whether it is ready.

**Every piece of work must happen in exactly one organisation's database, and the right one.** That holds for:
- a member clicking in the browser;
- a newsletter being sent in the background;
- a nightly task;
- an event passed from Membership to Organisation.

**If the system cannot be sure which organisation a piece of work belongs to, it does nothing.** It never "guesses" and never falls back to another organisation or to a shared database.

Slice 1 built the Membership ledger so that it already works this way: every operation takes the database connection it is given. **Slice 2 builds the part that hands it the right one.**

## 2. Coverage map: commission subject → accepted answer → what this delta adds

| # | Commission subject | Already answered (accepted) | Added here |
|---|---|---|---|
| 1 | Organisation identity and trusted routing | `ResolutionService`: hostname or selected organisation → `ServedOrganisation` → connection; refused unless `operational` and `schemaState = current` (D-193 Part 4). Locator never from a request (ISO-2). Context ∈ the account's bindings; conflicting ids refused (ISO-3) | **E-1:** the HTTP entry in this codebase (replacing today's path/session/header resolution) |
| 2 | Execution channels | ISO-1 "one context per operation, set at entry (request, job, inbox, relay)"; ISO-6 job envelope and connection reset; ISO-9 inbox; operator channel (ISO-11) | **E-2 … E-5:** queue, scheduler, CLI/operator, relay, as concrete mechanisms |
| 3 | Database isolation | ISO-2, ISO-5 (no second organisation connection); "no transaction across the two" (D-193 Part 4) | **C-1 … C-3:** connection handle, lifecycle and cleanup; transaction ownership |
| 4 | Membership integration | slice 1 plan §12 (what slice 2 must do); D-160 | **M-1 … M-5:** the three port adapters, the per-organisation relay, F-13 |
| 5 | Founding organisation and first registrar | D-178 §3 creation flow; D-193 §3.3 grant before provisioning, F-03, M-3; D-148 (founding decision recorded from evidence, asserted) | **F-1:** the ordered, resumable sequence, with the Membership-specific steps |
| 6 | Failure and security scenarios | D-178 §13.5 failure column; D-193 R-F matrix | **§4:** the eight scenarios of the commission, each with its guard |
| 7 | Testing obligations | D-178 §5 tests; D-193 proof register (53, NOT EXECUTED) | **§5:** which obligations slice 2 executes, plus new ones |

## 3. The delta

### 3.1 The one routing object: `OrganisationContext`

**Accepted (D-193):** `OrganisationContext` = organisation id + resolved connection, one per operation (ISO-1).

**Added:**
- **It is the only way code obtains an organisation connection.** It is created only by `ResolutionService` from a registry entry (ISO-2) and is **immutable** for the operation.
- **Organisation-side code receives the connection explicitly**, as Membership already does (slice 1: `PostgresMemberRepository(Connection, …)`, `LedgerClockCloser(Connection, …)`). **No organisation-context code reads Laravel's default connection.**
- **The default connection stays the central database.** It is never repointed at an organisation (C-1).

### 3.2 Entry channels

| # | Channel | Mechanism | Fail-closed behaviour |
|---|---|---|---|
| **E-1** | **HTTP (member and workspace requests)**: **one shared hostname** (D-219 (2)) | **Selection:** after login, the person chooses from the organisations their account is currently bound to; the choice is kept in the session as a **request to work in** an organisation, never as proof.<br><br>**One entry middleware, on every request:**<br>- re-checks the selection against Identity's **current** bindings (ISO-3);<br>- resolves it through the registry;<br>- builds the `OrganisationContext`;<br>- binds it **for this request only** (request-scoped container binding, never a static or global). | unknown, not operational, wrong schema, **binding revoked since selection**, or a conflicting id in route/body/header → **refused**, and the selection is cleared (no page from any database) |
| **E-2** | **Queue jobs and retries**: **a separate queue connection, §3.10** | **Every organisation job extends one base class** that carries `organisationId` in its envelope and **fixes its queue connection to `organisation`**. **One job middleware** checks the connection, resolves the context at each attempt (through the registry again, never from a cached connection) and purges the connection at job end, success or failure. **A retry resolves again**, so a retry for an organisation that is no longer routable (e.g. schema `migration-failed`) is refused | no organisation id, or the wrong connection → refused and failed, never run "centrally" or inline (ISO-6). Architecture tests: §3.10 |
| **E-3** | **Scheduled tasks** | **No scheduled task touches an organisation database directly.** A scheduled task is either central, or a **fan-out**: one central command reads the registry's `operational` organisations and dispatches **one E-2 job per organisation** | an organisation that is not operational is skipped and logged; one organisation's failure never stops the others |
| **E-4** | **CLI and operator operations** | Operator commands take an explicit organisation id, resolve through the registry, and go through the operator channel: the joining grant for technical operations, a case grant for content (ISO-8, ISO-11) | no id, no grant → refused. **No command iterates organisations "for convenience" outside E-3** |
| **E-5** | **Outbox relay and inbox** | §3.4 | event in database A naming organisation B → rejected and alerted (ISO-9) |

**Today's code, for scale (fact):**
- `TenantContext` resolves from path, session or header;
- the queue default is `sync`, and no `jobs` table exists;
- `routes/console.php` holds 13 scheduled entries, none of which sets a context (multi-database design 08 §1);
- `outbox:process` runs every minute on the default connection.

**Release-1 rule (D-160): legacy organisations stay on the legacy path until their cutover.** The new entry serves only registry organisations. **The two paths never share an organisation:** a registry organisation is never resolved by `TenantContext`. An architecture test enforces this, and nothing in the legacy path is changed by slice 2.

### 3.3 Connections and transactions

| # | Rule | How |
|---|---|---|
| **C-1** | **One named connection per organisation**, configured at runtime from the registry locator; the name is derived from the organisation id. The default connection is never changed | a `OrganisationConnectionFactory` (Infrastructure) |
| **C-2** | **Cleanup:** the connection is purged (`DB::purge`) at the end of every job (E-2) and is request-scoped for HTTP. **In a long-running worker no connection survives from one job to the next** | job middleware; the ISO-6 reuse test |
| **C-3** | **Transactions belong to the operation on its own connection.** Nothing spans the central and an organisation database. Cross-database steps are separate, idempotent, resumable steps (D-193 Part 4) | architecture test: no `DB::transaction` (default connection) in organisation-context code |

**Credentials:** locators hold a reference to the credential (environment or secret store), **never the password itself** in the registry row.

### 3.4 The per-organisation relay (M-4)

**Why it changes:** C1 is written to `outbox_events` **in the organisation's database**, in the same transaction as the fact (slice 1, D-5). **The relay today reads the default connection (F-10), so in production it would never see those rows.**

**Design:**
- `outbox:process` becomes an **E-3 fan-out**: one relay job per operational organisation;
- **the processor takes the organisation's connection** (today it uses Eloquent's default and `DB::transaction`). Its claim, hydrate, dispatch and complete steps all run on that connection;
- **ISO-9:** before dispatch, the event's `organisation_id` must equal the context's. On a mismatch the event is dead-lettered and alerted, never dispatched;
- **consumers run inside the same organisation context.** Organisation, the first C1 consumer, lives in the same organisation database, so delivery never leaves it;
- **at least once, as today.** Consumers are idempotent and order by `(membershipId, seq)` (CR-01).

**Central events** (Platform, Identity) keep a central relay on the central connection. **Two relays, never one relay reading both.**

### 3.5 Membership bindings (M-1 … M-3, M-5)

| # | Port (slice 1) | Production adapter |
|---|---|---|
| **M-1** | `WorkingOrganisationPort::current()` | returns the `OrganisationContext`'s organisation id, or `null` outside one. **C2 already refuses** `CROSS_ORGANISATION` on a mismatch |
| **M-2** | `MembershipSourceOfTruthPort::isLedger(org)` | **true only when the organisation equals the current `OrganisationContext`'s, and that context was resolved through the registry.** Derived; no stored flag (D-219 (1); §3.7). `FailClosedMembershipSourceOfTruth` stays the binding everywhere else, including outside any context |
| **M-3** | `LedgerBoundaryPort` | the existing `LedgerClockCloserBoundary`, constructed with the **context's** connection |
| **M-5** | **F-13:** `org_memberships.organisation_id → organisations` foreign key | **In the organisation-database schema** there is no central `organisations` table. **Replace it with a one-row `organisation_identity` table** created at provisioning, and point the foreign key at it. **The database itself then refuses a row for any other organisation.** The legacy database is not touched |

**Two migration sets (consequence of M-5 and D-178 §3):**
- **central migrations** run on the central database;
- **organisation migrations** run on each organisation database through provisioning (`schemaState`).

**Every migration file belongs to exactly one set.** An architecture test checks that no organisation migration references a central table.

### 3.6 Founding an organisation and its first registrar (F-1)

**Consumes D-178 §3, D-193 §3.3, F-03, M-3 and D-148. No new authority model.**

| Step | Where | What | Resumable by |
|---|---|---|---|
| 1 | central | registry entry **`pending`** (D-185): the application with the founding evidence, which names the registrar **and the initial platform contacts** (D-148, D-174). **No technical operation** *(corrected 2026-11-12, F-T1-2: revision 3 wrongly began at `provisioning`)* | registry status |
| 2 | central | counterparty verified (D-175, D-193 R-C); **service-operation grant recorded** (D-166, D-193 §3.3); **only then `provisioning`** (D-185, F-02) | the verification and grant records; status |
| 3 | organisation | database created; **organisation migrations** applied; `organisation_identity` row; the ledger clock row ensured (slice 1 `LedgerClockRow::ensure`) | `schemaState` |
| 4 | organisation | **founding decision recorded from evidence** (asserted, D-148); **the registrar's and initial contacts' local person entries and `PersonRef`s** (D-193 F-02 step 6); the registrar's claim issued **before** `operational` (D-193 F-03). **A platform contact receives no membership, workspace access or authority** (D-193 R-B) *(added 2026-11-12, F-T1-1)* | the founding record |
| 5 | central | registry `operational`, **only if the §3.7 preconditions hold** (checked in the organisation database at this step, never assumed from earlier steps) | status |

**Membership is on the ledger from step 3** (D-160: new organisations start on the ledger, no data migration).

**The registrar may admit members only once Governance's AuthorityCheck exists (slice 4).** Until then every authority-requiring command is `Undetermined` and refused (D-178 §3: business readiness is derived, not a status). **So slice 2 founds an organisation, but nobody can admit a member in it until slice 4.** That is the intended fail-closed state, not a defect.

### 3.7 The derived ledger rule and its enforcement (D-219 (1))

**Rule:** in release 1, **an organisation is a ledger organisation if and only if it is served through the Platform registry.** No flag is stored.

**Enforced at three points:**

| # | Point | Guard |
|---|---|---|
| **L-1** | **`operational` transition** (§3.6 step 5) | refused unless, in that organisation's database:<br>- the organisation migration set is at the current version, **including the Membership ledger tables**;<br>- the `organisation_identity` row equals the registry id;<br>- the ledger clock row exists;<br>- **at least one platform contact with an active account** (D-193 F-07; *added 2026-11-12, F-T1-1*). |
| **L-2** | **Registry entry creation** (§3.6 step 1) | refused if the id is a **legacy organisation** (present in the legacy `organisations` table). **Legacy organisations cannot enter the registry in release 1;** their cutover (C-1 … C-10) is a later, separate plan |
| **L-3** | **Every C2 call** | M-2 answers true only for the current registry-resolved context. Without one, or for a different organisation, **`SOURCE_NOT_LEDGER`**. **Fail closed** whenever L-1 or L-2 cannot be shown |

**Reconciliation with slice 1 plan §12** (row "source flag: set 'ledger' at joining (D-160); bind the real adapter") and with the C1/C2 specification ("gate C-1 flag"):
- **D-160's requirement is "new organisations start on the ledger from joining".** Under D-219 (1), **joining is registry creation, so a joined organisation is a ledger organisation by construction.** "Set at joining" is met without a stored value.
- **"Bind the real adapter" stays a slice 2 task** (M-2).
- **The word "flag" in §12 and in the specification described a mechanism, not a decided data model.** No accepted decision requires stored state; D-160 decides the rule, not its storage.
- **Not a conflict, recorded as an interpretation.** If the cutover plan later needs a registry organisation that is *not yet* on the ledger, the rule stops holding. A stored value (option B) is then decided at that point, and L-3 changes.
- §12 gets an appended note pointing here; its text stays as approved.

### 3.8 Technical recovery is kept; emergency content access is deferred (D-219 (3))

| | Technical recovery (**release 1, required**) | Emergency access to content (**deferred**) |
|---|---|---|
| **What** | backups, monitoring, restore of an organisation database, migration repair, failover of the cluster | platform staff reaching an organisation's **business content** while its database or its own authority is unreachable (D-170, D-174 … D-188) |
| **Authority** | the **joining grant** for technical operations (D-166 (1), ISO-8), through the operator channel (ISO-11) | would need case grants via the platform (D-170) |
| **Reads business content** | **never** | yes, within a grant |
| **Release 1 behaviour** | runs; every operation recorded and reported to the organisation | **not offered:** while an organisation's database is unreachable, its users and jobs are refused until recovery (fail closed) |

**Launch obligations (operational, not features):**
- a per-organisation backup with a **rehearsed restore** into a scratch database;
- monitoring of reachability, clock regressions (slice 1 risk) and replication;
- a written recovery runbook.

### 3.9 One cluster, one database per organisation (D-219 (4))

- **Shared failure domain:** a cluster outage refuses every organisation at once (D-178 §13.6: fail closed; nothing proceeds on stale routing). **Accepted for release 1.**
- **Blast-radius limits that still hold:**
  - credentials are per organisation database, so one leaked credential opens one organisation;
  - backups are per database, so one organisation can be restored alone;
  - no connection crosses databases (ISO-5).
- **Locator format** names cluster and database, so moving one organisation to its own cluster later is a locator change (provisioning-only, ISO-2), with no model change.
- **Restore rule:** a restored organisation database returns to routing only through `schemaState` and the L-1 checks, never by hand.

### 3.10 Two queue connections, never one (revision 3, after the Principal's review)

**The reviewer is right: a named queue is not a separate connection.** Jobs on two queue names of one connection share the driver, the table, `retry_after`, the worker settings and the failure handling. Switching the default connection from `sync` would change **every** queued class at once.

**Facts (Laravel 12):**
- `config/queue.php:16`: the default is `env('QUEUE_CONNECTION', 'sync')`;
- the `database` connection exists (table `jobs`), but no `jobs` table is migrated;
- `app/` has **55 `ShouldQueue` classes**, which today run **inline**, inside the request, because the default is `sync`;
- failed jobs go to `failed_jobs` on the default database connection.

**Design:**

| # | Rule | Mechanism |
|---|---|---|
| **Q-1** | **The legacy connection is unchanged.** The default stays `sync`; no environment value or legacy class changes. **Legacy mail and jobs keep running inline, exactly as today** | nothing edited in the legacy path. A characterisation test pins it (T-19) |
| **Q-2** | **A second, separate connection `organisation`:**<br>- driver `database` on the **central** database connection;<br>- its own table `organisation_jobs` (central migration), its own `retry_after`, `after_commit = false` (see Q-7).<br>**Failures: the standard `failed_jobs` table, not a separate one (§3.10.1).**<br>Only a dedicated worker processes it: `php artisan queue:work organisation` | new entry in `config/queue.php`; **one** central migration (`organisation_jobs`); `failed_jobs` already exists (`2026_03_05_000014_…:43`) |
| **Q-3** | **Dispatch selects the connection explicitly, never by default.** `OrganisationJob` (abstract base class) sets `$this->connection = 'organisation'` in its constructor and requires an `OrganisationId`. **Organisation code dispatches only through `OrganisationJobDispatcher`**, which: <br>- refuses a job that isn't an `OrganisationJob`;<br>- refuses one whose organisation differs from the current `OrganisationContext`;<br>- refuses any connection override. | Infrastructure class; architecture tests T-20 |
| **Q-4** | **Context per attempt:** the job middleware `EstablishOrganisationContext`:<br>1. refuses unless the job arrived on connection `organisation` (so a misrouted or inline run of an organisation job is refused);<br>2. resolves the organisation through `ResolutionService` **on every attempt**;<br>3. binds the context as a scoped instance;<br>4. in `finally`: forgets the context and `DB::purge`s the organisation connection. | Laravel job middleware |
| **Q-5** | **Retries re-resolve.** No routing fact is serialised into the payload. The registry is read again at each attempt, so a `migration-failed` (or otherwise unroutable) organisation's retry is refused and released to the failed table with its reason | Q-4 step 2 |
| **Q-6** | **Payloads carry identifiers only:** organisation id, operation key, aggregate ids. **No Eloquent models** (`SerializesModels` would restore a model with its *stored connection name*, a leak path), no business content, no personal data | architecture test: `OrganisationJob` subclasses neither use `SerializesModels` nor hold `Model` properties |
| **Q-7** | **Work that must follow an organisation commit goes through that organisation's outbox, never a direct dispatch.** Laravel's `afterCommit` follows the default connection's transactions, not the organisation connection's, and the central jobs table is a different database (no atomic dispatch, C-3). **Enforced, not just documented: §3.10.2** | dispatcher refusal + architecture test + T-24, T-25 |
| **Q-8** | **Isolation of failure:** one job per organisation and operation (E-3 fan-out). A failing organisation's jobs fail, retry or land in the failed table **without blocking the worker or other organisations' jobs** | standard worker behaviour; tested (T-23) |

### 3.10.1 Failed-job storage: verified against the installed Laravel (12.71.0)

**Evidence (source and a runtime probe, read-only; no repository change):**

| Fact | Where |
|---|---|
| **One failed-job store for the whole application:** the singleton `queue.failer` is built once from `config('queue.failed')`, **not per queue connection** | `vendor/laravel/framework/src/Illuminate/Queue/QueueServiceProvider.php:296-322` |
| This repository configures `driver = database-uuids`, `table = failed_jobs` on the default database connection | `config/queue.php:87-91` |
| Runtime probe: default connection `sync`; failer `Illuminate\Queue\Failed\DatabaseUuidFailedJobProvider`; one instance | probe script in the session scratchpad, output recorded in the handover |
| **Failures are written only by the `queue:work` command**, which listens for `JobFailed` and calls `queue.failer->log($connectionName, …)` | `Queue/Console/WorkCommand.php:200-203, 338-345` |
| **A `sync` job that fails rethrows to its caller** (`SyncQueue::handleException`), so nothing is logged to the failed table | `Queue/SyncQueue.php:220-227` |
| `failed_jobs` already exists, with a `connection` column | `database/migrations/2026_03_05_000014_create_standard_laravel_tables.php:43-47` |

**Consequence (correction of revision 3's first draft):**
- **A separate `organisation_failed_jobs` table cannot be selected per connection with standard Laravel.** It would need a custom `queue.failer` that routes by connection name.
- **It is also unnecessary.** Use the standard `failed_jobs`: organisation failures are the rows with `connection = 'organisation'`.
- **Legacy behaviour is unchanged by construction,** because legacy jobs run on `sync` and never reach the failer. T-19 pins that.
- `queue:retry` and `queue:failed` work unchanged.
- **Payloads in `failed_jobs` are identifier-only (Q-6),** so the centre holds no business data there either.

**Remaining uncertainty:** none in the mechanism. **T-23 is the executable proof** and runs in task 7. **Nothing above has been run as a test;** the probe only read configuration.

### 3.10.2 Enforcing Q-7 (no premature dispatch)

| Layer | Rule | Proof |
|---|---|---|
| **Dispatcher** | `OrganisationJobDispatcher` refuses while the organisation connection is inside a transaction (`transactionLevel() > 0`) | T-25 |
| T-26 | **Held connection (D-222):** a `Connection` object obtained from organisation A's `OrganisationContext`, used after organisation B's context has been established (and A's purged), reaches A's database only, never B's | D-222; C-1, C-2 |
| **Single path** | organisation jobs are dispatched only by the dispatcher; architecture test forbids direct dispatch elsewhere; Domain and Application cannot dispatch at all (no Laravel) | T-25 |
| **Outbox** | work that must follow a commit is an outbox row written **in** the organisation transaction (as slice 1's C1). A rollback removes it | T-24 |
| **Relay** | reads only committed rows; commits its claim, **then** dispatches through the dispatcher (outside any transaction), then marks the row. A crash between dispatch and mark re-dispatches. The job is idempotent by the event id (at least once, Q-8 / §3.4) | T-24, T-6 |

**Why the central database holds the job table (for confirmation, Q-S2-5):**
- One worker and one table are far simpler for one developer than a queue per organisation database.
- **It is consistent with D-164 only if payloads stay identifier-only (Q-6).** The centre then holds transport metadata, never an organisation's business data.
- **The alternative,** a jobs table in each organisation database with a worker per database, keeps even identifiers out of the centre, but multiplies workers. It is not recommended for release 1.

**Can Laravel do this as written? Yes.** Per-job connection (`$connection` / `onConnection`), job middleware, a database driver per connection, and a separate worker per connection are standard Laravel 12 features. **No framework change is needed.**

**The legacy path is not migrated off `sync` in slice 2.** Moving the 55 legacy classes to a real queue is a separate decision with its own characterisation tests, outside this slice.

## 4. Failure and security scenarios

| # | Scenario | Guard | Result |
|---|---|---|---|
| 1 | Missing organisation context | E-1 … E-5 entry; M-1 returns `null` | refused; C2 refuses; nothing written |
| 2 | Forged or stale organisation identity (id in URL/body/header; old session) | ISO-3 against current bindings at every request; locator never from input (ISO-2) | refused, logged content-free (ISO-10) |
| 3 | Queue job executed under the wrong organisation | the context comes from the job envelope only, resolved at job start; ISO-9 for events | the job acts only on its envelope's organisation; a mismatch is refused |
| 4 | Connection reused after a switch | C-2 purge after every job; request-scoped for HTTP; no static connection | the alternating A/B reuse test |
| 5 | Outbox event processed against the wrong database | per-organisation relay (§3.4) + ISO-9 + the `organisation_identity` foreign key (M-5) | dead-lettered and alerted; cannot be written into B |
| 6 | Source-of-truth configuration missing | M-2 defaults to `FailClosedMembershipSourceOfTruth` | C2 `SOURCE_NOT_LEDGER`; never "not a member" |
| 7 | Database or registry unavailable | D-178 §13.6: the centre is needed for every new request and job start; health is not a status | refused; resumes after recovery; nothing proceeds on stale routing |
| 8 | Duplicate or retried jobs | jobs idempotent by an operation key; the relay at least once with idempotent consumers; Membership commands already idempotent (slice 1 idempotency keys) | the second run has no effect |
| 9 | A legacy organisation is registered, or an unready one marked operational | L-2, L-1 (§3.7) | refused; the organisation never routes; C2 `SOURCE_NOT_LEDGER` |
| 10 | Cluster outage, or a restored database | §3.9; `schemaState` + L-1 on return | every organisation refused until recovery; a restored database routes only after the checks |

## 5. Acceptance criteria and test obligations

**Test environment:** two (and for E-3, three) organisation databases on the isolated PostgreSQL cluster (PBDIGIT-324), plus a central database. **RED first for every row.**

| # | Obligation | Proves |
|---|---|---|
| T-1 | Two organisations, one account bound to both: each request sees only its own organisation; switching the id is refused | ISO-1, ISO-3 |
| T-2 | A request for an organisation not `operational`, or with `schemaState ≠ current`, is refused | ISO-2, D-193 |
| T-3 | One long-running worker processes A, B, A, B jobs: no read or write crosses; the connection is new each job | ISO-6, C-2 |
| T-4 | A job without an organisation id is refused; an architecture test finds no organisation job without the marker | ISO-6, E-2 |
| T-5 | A fan-out over three organisations, one failing: the other two complete; a non-operational one is skipped | E-3 |
| T-6 | **Relay:** C1 written in A is delivered in A and never seen by B's relay; an event naming B found in A is dead-lettered | §3.4, ISO-9 |
| T-7 | **C2 end to end through routing:** answered for a ledger organisation; `SOURCE_NOT_LEDGER` without the store fact; `CROSS_ORGANISATION` when the context is A and the query names B | M-1 … M-3 |
| T-8 | **F-13:** inserting an `org_memberships` row for another organisation into A's database fails at the database | M-5 |
| T-9 | **Founding:** a crash after each step of §3.6 resumes to the same end state; no request is routed before step 5 | F-1, D-178 §3 |
| T-10 | Architecture: organisation-context code never uses the default connection or `DB::transaction`; organisation migrations never reference central tables; no registry organisation is resolvable by `TenantContext` | C-1, C-3, §3.5, §3.2 |
| T-11 | Registry or organisation database down: requests and jobs refused; after recovery they resume; nothing ran on stale routing | §13.6 |
| T-12 | The slice 1 suite (135 PostgreSQL tests) still passes, now also with the connection supplied by the factory | compatibility |

**Added by the D-219 decisions:**

| # | Obligation | Proves |
|---|---|---|
| T-13 | **`operational` is refused** when any L-1 precondition is missing, one at a time: migrations behind, ledger tables absent, identity row absent or different, clock row absent | L-1 |
| T-14 | A registry entry for an id present in the legacy `organisations` table is refused; an architecture test asserts M-2 has no other true path | L-2, L-3 |
| T-15 | **Shared hostname:** a selection of an organisation the account is not bound to is refused; **a binding revoked after selection refuses the next request** and clears the selection; a forged session value is refused | E-1, ISO-3 |
| T-16 | The operator channel performs a technical operation (migration repair) **without reading business tables**; a content read without a case grant is refused | §3.8, ISO-8 |
| T-17 | **Restore drill (scripted):** an organisation database restored to a scratch database routes only after `schemaState` and L-1 pass | §3.9 |
| T-18 | With the cluster unreachable, requests and jobs for every organisation are refused; after recovery they resume | §3.9, D-178 §13.6 |

**Added by the queue separation (§3.10):**

| # | Obligation | Proves |
|---|---|---|
| T-19 | **Legacy characterisation (behaviour, not configuration):**<br>- **Step 1, before any queue change:** written and run against the **untouched** configuration. A representative legacy queued mail and a representative legacy job execute **inline during dispatch** (the side effect is observable before dispatch returns), and **a failing legacy job throws to its caller and writes no `failed_jobs` row**.<br>- **Step 2, after the `organisation` connection is added:** the **same test, unchanged**, is run again and must pass. Additionally, nothing is written to `organisation_jobs`, and the default connection is still `sync`. | Q-1 |
| T-20 | **Separation:** an `OrganisationJob` dispatched through `OrganisationJobDispatcher` is stored in `organisation_jobs`, never in `jobs`, and processed by `queue:work organisation`. The dispatcher refuses: a non-organisation job; a job for an organisation other than the current context; a connection override. Architecture test: every `OrganisationJob` subclass, and no legacy class, uses connection `organisation` | Q-2, Q-3 |
| T-21 | **Envelope and connection:** an organisation job with no organisation id cannot be constructed; one forced onto `sync` (run inline) or another connection is refused by the middleware | Q-3, Q-4 (ISO-6) |
| T-22 | **No leak:** one worker runs A, B, A, B jobs. Each sees only its own database; after each job the organisation connection is purged and the context forgotten; the architecture test finds no `SerializesModels` or `Model` property in organisation jobs | Q-4, Q-6, C-2 |
| T-23 | **Retries and failure isolation:** A's job fails and is retried; between attempts A is set to `migration-failed`, so the retry is refused with that reason. **Run by a real `queue:work organisation` worker** (only the worker logs failures, §3.10.1): the failure lands in `failed_jobs` with `connection = 'organisation'` and an identifier-only payload. B's jobs in the same worker complete throughout | Q-5, Q-8, §3.10.1 |
| T-24 | **After-commit:** a job intended to follow an organisation commit is produced through the outbox. **A rolled-back organisation transaction leaves no outbox row and so produces no job.** The relay dispatches only rows it read as committed, and dispatches **outside** any organisation transaction | Q-7, §3.10.2 |
| T-25 | **Bypass refused:** `OrganisationJobDispatcher::dispatch` called while the organisation connection has an open transaction (`transactionLevel() > 0`) throws `DispatchInsideOrganisationTransactionRefused`, and no `organisation_jobs` row exists. **Architecture test:** outside the dispatcher and the relay, no class under `app/Contexts` calls `dispatch(`, `Bus::`, `Queue::`, `->onConnection(` or `::dispatch` for an `OrganisationJob` (Domain and Application are already Laravel-free) | §3.10.2 |

**Of D-193's 53 proof obligations,** slice 2 executes those for routing, the locator, provisioning resume and isolation. **The contact, case-grant and emergency-route obligations stay NOT EXECUTED** until those features are scheduled. The slice 2 plan will list each obligation by number with its status.

**Acceptance criteria for slice 2:**
- **T-1 … T-26** green on the isolated cluster, with raw output and checksums (**T-19 run twice**, before and after the queue change);
- the in-scope D-193 obligations listed and passed;
- the legacy path unchanged (its suites no worse than the slice 1 baseline);
- developer guides per step;
- **the Principal accepts.**

## 6. Questions for the Principal — answered by D-219

**Decided 2026-11-12 (D-219):**
- Q-S2-1 **(a) derived**, with enforcement (§3.7);
- Q-S2-2 **(b) shared hostname** (E-1);
- Q-S2-3 **defer**, with technical recovery kept (§3.8);
- Q-S2-4 **(a) one cluster** (§3.9).

The table below is kept as the record of the options.

**Open, revision 3:**
- **Q-S2-5: may the organisation job queue's table live in the central database, with identifier-only payloads (§3.10 Q-2, Q-6)?**
- **Recommendation: yes.** It is consistent with D-164 because the centre holds transport metadata, never business data; and one worker is simpler than one per organisation database.
- **Alternative:** a jobs table in each organisation database, with a worker per database.

| # | Question | Options | Recommendation |
|---|---|---|---|
| **Q-S2-1** | **Where does "this organisation's membership store is the ledger" (D-160, gate C-1) live?** | **(a) Derived:** in release 1, every registry organisation is a ledger organisation (D-160 serves only those), so "resolved through the registry" *is* the fact. No new field. **(b) A registry field** `membershipStore`, set at joining | **(a) for release 1.** It adds no dimension (model integrity: the "flag" is not orthogonal to "is a registry organisation" while D-160 holds). Revisit with (b) only when a legacy organisation's cutover (C-1 … C-10) needs a registry entry before its ledger is live |
| **Q-S2-2** | **Release-1 entry: hostname per organisation, or one shared hostname with organisation selection after login?** D-193 allows both | **(a) Hostname per organisation** (e.g. `partei-x.publicdigit…`): clearest isolation, needs DNS and TLS per organisation. **(b) Shared hostname + selection**: simpler operations; ISO-3 does the work. **(c) Both** | **(b) first, (a) later.** One hostname and one certificate is far easier for one developer to operate; ISO-3 already guarantees the isolation; per-organisation hostnames can be added without changing the model |
| **Q-S2-3** | **D-193 (5): does the emergency route (D-170, D-174 … D-188) ship in release 1?** | ship it, or defer it | **Defer.** It is the most complex part of Platform; without it a database outage simply means "wait" (fail closed), which is acceptable for a first release |
| **Q-S2-4** | **Physical layout of organisation databases in release 1** | **(a)** one PostgreSQL cluster, one database per organisation; **(b)** a cluster per organisation | **(a).** One cluster to back up, monitor and upgrade. D-178's model holds either way; (b) can serve a large customer later |

**Conditions to confirm, not decide:**
- **D-193's conditions X-2 and X-3** appear to be met at the design level, by D-200 (Identity I-TDD-02) and by D-202 and D-208 (Governance decision records), but neither is built.
- They guard contact promotion and normal-route grants, **which slice 2 does not need**. Slice 2 needs only the joining grant (step 2).

## 7. Consequences for a solo developer

| Choice | What it costs you | What it saves you |
|---|---|---|
| Explicit connection everywhere (§3.1) instead of switching Laravel's default | every organisation-side class takes a `Connection`; slightly more wiring | no hidden global state, so the class of bug "a job used the previous organisation's database" cannot occur. Slice 1 already pays this cost |
| Fan-out scheduling (E-3) | one dispatcher command per periodic task; a **second** queue connection (`organisation`, its own table and worker) beside the unchanged `sync` (§3.10) | one organisation's failure never blocks the others; work spreads over time |
| Two relays (§3.4) | two scheduled commands instead of one | no relay ever needs credentials for every database at once |
| Two migration sets (§3.5) | one more `migrate` path and a provisioning runner | an organisation migration can't touch central data, and the reverse |
| `organisation_identity` foreign key (M-5) | one small table per organisation database | the database itself enforces isolation, beyond application checks |
| Q-S2-2 (b), Q-S2-3 defer, Q-S2-4 (a) | some features later | much less to operate on day one |

**Biggest single effort:** the `organisation` queue connection, its worker and the E-2 middleware (§3.10). The legacy `sync` path is left alone. **Biggest single risk:** a scheduled or queued job in the legacy path being pointed at a registry organisation by mistake. T-10's architecture test exists for that.

## 8. How slice 2 serves the four product areas

| Area | What slice 2 gives it |
|---|---|
| **Membership** | the ledger becomes reachable in production: the right connection, `SOURCE_NOT_LEDGER` lifted for ledger organisations, C1 actually delivered, C2 usable by consumers. No ratified rule changes |
| **Forum** (slice 6) | posts, moderation and read/write checks will run inside the organisation context, against the same database as C2, so membership checks never cross organisations |
| **Newsletter** (slice 7) | dispatch is a queued E-2 job per organisation; the recipient snapshot is taken in that organisation's database; delivery retries can't leak to another organisation (D-178 §13.6 already lists newsletter dispatch) |
| **Reference Geography** | **nothing (D-147).** Slice 2 introduces no geographic dependency. **Follow-on boundary:**<br>- Reference Geography's strategic baseline and tactical design v3.11 are **already ratified** (D-93); no new design is needed.<br>- **Implementing it is its own later slice:** central, shared, read-only to organisations, reached by Organisation only through its published contract (coverage references, `RevisionPublished`). It needs no change to this routing.<br>- **Its use by Organisation (coverage, allocation rules §51) stays deferred** unless the Principal changes D-147.<br>- **It never decides belonging** (D-79, D-80). |

## 9. Proposed slice 2 build outline (for the EP-01 plan, after approval of this design)

1. `OrganisationContext`, `OrganisationConnectionFactory`, `ResolutionService` against a registry table (T-2).
2. HTTP entry middleware + ISO-3 (T-1).
3. Queue (§3.10): **T-19 first, on the untouched configuration**; then the separate `organisation` connection (`organisation_jobs`, standard `failed_jobs`), `OrganisationJob`, `OrganisationJobDispatcher` (transaction refusal), `EstablishOrganisationContext`; T-19 re-run (T-3, T-4, T-19 … T-25).
4. Fan-out dispatcher (T-5).
5. Organisation migration set + `organisation_identity` + F-13 replacement (T-8, T-10).
6. Per-organisation relay; processor on a given connection; ISO-9 (T-6).
7. Membership adapters M-1 … M-3 (T-7, T-12).
8. Founding sequence F-1, resumable (T-9).
9. Outage behaviour (T-11), architecture tests (T-10), the D-193 obligation list, evidence and developer guides.

**Each step: RED → GREEN → guide, one story ID** (to be created on approval of the plan).

## Corrections after task 1 (2026-11-12, Principal's task 1 review; consistency only, no new decision)

| # | Correction |
|---|---|
| **F-T1-1** | **The `operational` step needs at least one platform contact with an active account** (D-193 F-07). This is part of L-1 and of founding step 4, which records the initial contacts named in the founding evidence. Implemented in `OperationalReadiness`. A contact has no constitutional or governance authority (D-193 R-B) |
| **F-T1-2** | **Founding begins at `pending`, not `provisioning`** (D-185). §3.6 steps 1–2 corrected |
| **F-T1-3** | **`suspended` and `closed` are deferred** to a later release; their business triggers are undecided (D-193 §3.2). **A failed migration is schema state, not a status.** E-2 and Q-5 no longer use `suspended` as an example; tests use `migration-failed` |
| **F-T1-4** | **An absent schema state** (no migration result recorded yet) **is not evidence of a current schema and never permits routing.** Fail closed (`ServedOrganisation::isRoutable()`) |

## Traceability

- **Consumes:** D-148, D-160, D-164, D-166, D-170, D-173, D-175, D-178, D-193 (with its proof register), D-200, D-202, D-208, D-147; **decided by D-219**;
- **slice 1:** plan `docs/plans/20261112-1251-…-plan.md` §12 and §13, PBDIGIT-343 (code `58e8bb26d`);
- **code facts:** `app/Http/Middleware/TenantContext.php`, `app/Services/TenantContext.php`, `config/queue.php:16` (`sync`), `routes/console.php:55` (`outbox:process`), `app/Contexts/Shared/Infrastructure/Outbox/OutboxEventProcessor.php` (default connection), multi-database design 08 §1;
- **changes no accepted decision.**

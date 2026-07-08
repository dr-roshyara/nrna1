# PB-004 Step 4 — Election Infrastructure (sliced) · Step 4A.1 = Temporal Correction

## Context — why this change

PB-004 Step 3 (Election reaction: Domain + Application) is GREEN and passed EP-02 Completion Review. EP-02 recorded **one deviation**: `ElectionCorrectionApplied.appliedAt` is currently sourced from the inbound determination's `occurredAt`, but the ARB Q1 ruling is that it must record the **application timestamp** — when the Election context applies the correction — because `DeterminationIssued` (10:00) and `ElectionCorrectionApplied` (10:04) are *different facts* with *different times* (needed for latency/replay/audit).

Planning the Infrastructure work surfaced a **plan-invalidating finding** (EP-01 step 6): the greenfield `App\Contexts\Election` has **no election-provisioning slice**, and the table name `elections` is already owned by the legacy voting app. So a real `ElectionRepository::find()` has **no source of truth** for "this election exists in this org" — the "unknown election → reject" ruling depends on it. That is a **DDD ownership decision** ("who owns Election existence?"), not a storage problem.

## ARB rulings (this planning round)
- **Clock:** reuse the existing, bound `App\Domain\Shared\Clock\ClockInterface` (→ `SystemClock`, test double `FrozenClock`). No new clock/namespace (ER-03/ER-04, evidence-backed). **Approved.**
- **Existence source:** PB-004 implements *Election Reaction*, not *Election lifecycle*. Until an Election-lifecycle capability exists, election existence is obtained from the **legacy system via an Anti-Corruption Layer** (Strangler pattern). Do **not** create a temporary greenfield registry; do **not** create a new canonical `elections` table yet. **Design the ACL before implementing it.**
- **Slice split:** 4A → **4A.1** (temporal) · **4A.2** (ACL design, no persistence) · **4A.3** (persistence, only after existence source established).
- **ER-08** — to be added to the process draft (wording below).
- **Three execution refinements** (applied below): keep parsing `occurredAt` but never assign it to `appliedAt`; use "application timestamp" in the guide; assert both timestamps independently in RED.

## Scope of THIS slice (Step 4A.1 only) — then STOP
1. **Governance (paired docs commit):** add **ER-08** to `docs/implementation/Implementation_Process_v1.1_Draft.md` (§ER additions) + fold-in queue. Wording: *"**ER-08 — Reviews Record, Implementations Repair.** Completion Reviews document implementation conformance and discovered deviations. Behavioral changes belong exclusively to subsequent approved implementation slices (RED→GREEN)."*
2. **Temporal correction (RED→GREEN):** inject `ClockInterface` into `DeterminationIssuedReactionHandler`; `appliedAt = $clock->now()`.

**Out of scope (later slices):** ACL design (4A.2), any migration / Eloquent repository / model / mapper / service-provider binding / outbox adapter / inbox wiring / hydrator (4A.3, 4B), fitness-scan extension (4C).

## Step 4A.1 — files & changes

**RED first** — `tests/Unit/Contexts/Election/DeterminationIssuedReactionHandlerTest.php`:
- Construct the handler with a third arg `FrozenClock::at('2026-07-08T10:04:00+00:00')` (application timestamp), while the payload keeps `occurredAt = 2026-07-08T10:00:00+00:00` (determination time). A small private `handler(...)` helper keeps the 3-arg ctor DRY across tests.
- Extend the happy-path test to assert **both timestamps independently** (they coexist, no mutation):
  - `$message->payload['occurredAt'] === '2026-07-08T10:00:00+00:00'` (DeterminationIssued time unchanged), and
  - `$outbox->events[0]->appliedAt->format(DATE_ATOM) === '2026-07-08T10:04:00+00:00'` (application timestamp = the clock, not 10:00).
- Confirm RED: the `appliedAt` assertion fails because the current handler uses the payload `occurredAt`.

**GREEN** — `app/Contexts/Election/Application/DeterminationIssuedReactionHandler.php`:
- Add ctor param `private readonly \App\Domain\Shared\Clock\ClockInterface $clock`.
- `$appliedAt = $this->clock->now();`
- **Keep** parsing the determination's `occurredAt` into a clearly-named local, with a comment that it is the determination's *issuance* time, intentionally **not** used as `appliedAt` (retained for future latency/audit/replay; if later needed it is carried as a *separate* attribute — never overloading `appliedAt`).
- Keep `ElectionCorrectionApplied.appliedAt` (name already correct).
- No service-provider binding needed yet — the handler is not wired to the inbox until Step 4B; unit tests inject `FrozenClock` directly (Application layer stays independently testable).

**Docs (DoD):** update `developer_guide/election/01_election_reaction.md` — `appliedAt` is the **application timestamp** via the injected `ClockInterface` (note the 10:00 vs 10:04 distinction, and that the determination's `occurredAt` coexists, unmutated); update the EP-02 recorded deviation to "resolved in Step 4A.1".

## Reused existing code (do not recreate)
- `App\Domain\Shared\Clock\ClockInterface` (`now(): DateTimeImmutable`) — bound in `app/Providers/AppServiceProvider.php:155-158`.
- `App\Infrastructure\Shared\Clock\FrozenClock` (`::at(iso8601)`) — test double.

## Verification
- `php artisan test tests/Unit/Contexts/Election` — RED shows the new appliedAt assertion failing; after GREEN, 14/14 pass.
- Regression: `php artisan test tests/Architecture tests/Unit/Contexts/Contestation tests/Unit/Contexts/Adjudication tests/Unit/Contexts/Election` — no regression (expect 213✔/1 skip).
- `vendor/bin/phpstan analyse -c phpstan-greenfield.neon` — clean.
- Commits: `docs(process): ER-08 …` + `PB-004 Step 4A.1: appliedAt = application timestamp via injected ClockInterface` (+ paired dev-guide update). STOP for ARB review.

## Architectural Note
Step 4A.1 intentionally introduces **no persistence concerns**. The Election bounded context remains persistence-agnostic until Election existence has an approved source of truth (Step 4A.2, the ACL design).

## Step 4A.2 — Election Existence (IDD design — ARB-refined, awaiting review; NO code, NO ADR)

**This is an IDD design**, not an ADR: it introduces no new architectural decision — it *applies* already-approved principles (DDD, Hexagonal, ACL, Strangler). Recorded in the IDD (this plan), per ARB ruling.

### Business Question
When Adjudication issues a binding determination on a contested election outcome, the Election context must apply a correction **to an election that already exists**. What does "this election exists" mean, and **who owns that answer**?

### Ownership (business concept vs. operational source of truth — the crucial distinction)
- **The Election bounded context owns the business concept of *Election*** — its lifecycle, existence, and identity. This never changes. Election is *not* owned by legacy.
- **The legacy voting platform is only the *current operational source of truth* for election existence**, until the greenfield Election-lifecycle capability is built. That is a temporary implementation fact, not ownership. (*Avoid the phrase "legacy owns existence" — legacy is a source, not an owner.*)
- Other responsibilities: identity → Election's own local `ElectionId` VO (ADR-T16); reaction state (applied-determination idempotency set) → Election greenfield (4A.3); publication (`ElectionCorrectionApplied`) → Election (4B); translation legacy↔greenfield → the ACL only; tenant scope → infrastructure boundary; consistency → no cross-system transaction (ADR-T1); anonymity → constitutional, preserved by the ACL.

### Strategic Decision
Source election existence from the legacy `elections` table through a **read-only Anti-Corruption Layer**, expressed as a **business-oriented port the Election context depends on** — `ElectionExistencePort` — and hidden behind the unchanged **Domain Port** `ElectionRepository::find(ElectionId): ?Election` (a repository *is* a Domain Port; Hexagonal).

**Tenant scoping is an infrastructure concern.** The Domain (and the port) express only Election *identity* (`ElectionId`); the Domain never passes an organisation/tenant id into the port. The adapter resolves the ambient organisation transparently (TenantContext), so cross-org existence resolves to "not found" without the Domain ever knowing tenancy exists.

**Composition insight** (resolves "happy-path always rejects"): `find()` composes two sources — (1) **existence** via `ElectionExistencePort` (legacy today, tenant-scoped), and (2) **applied-determinations** (idempotency set) via the greenfield corrections store (4A.3). An existing-but-never-corrected election reconstitutes with an empty set → the first determination applies; an unknown/cross-org election → `null` → `CannotApplyDeterminationToUnknownElection`.

### Interfaces (design shape — not code)
```
// App\Contexts\Election\Application\Port\ElectionExistencePort
//   The business dependency: "does this Election exist (within the ambient organisation)?"
//   NOT a "directory"/"knowledge base" — it expresses the business invariant, not a lookup mechanism.
interface ElectionExistencePort {
    public function exists(ElectionId $id): bool;   // read-only; identity only; never vote/voter data (ADR-T11)
}

// find() composition, in the 4A.3 concrete repository:
public function find(ElectionId $id): ?Election {
    if (!$this->existence->exists($id)) {           // legacy today, tenant-scoped — SOURCE, not owner
        return null;                                //   unknown / cross-org → reject
    }
    $applied = $this->corrections->appliedDeterminations($id);   // greenfield reaction state (4A.3)
    return Election::reconstitute($id, $applied);
}
```
- Adapter (4A.3, Infrastructure): `LegacyElectionExistenceAdapter implements ElectionExistencePort` — queries `elections` by `id`, scoped to the ambient organisation, `whereNull('deleted_at')`, selecting identity only. The **only** code that knows the legacy schema; read-only.
- **Status-agnostic** (ARB-ruled): existence = "row present in this org, not soft-deleted." Whether a `completed`/`archived` election *may be corrected* is **business validity**, owned by future **Election Policy** — NOT the ACL. `Exists ≠ MayBeCorrected`.

### Architectural Invariants (the non-negotiable contract for 4A.3 implementation)
1. The Election **domain never imports legacy** (no `App\Models\Election`, no legacy namespace).
2. The ACL is **read-only** — it never writes to legacy.
3. The ACL is the **only translator** between legacy and greenfield identity.
4. **Tenant isolation preserved** — existence is scoped to the ambient organisation (cross-org → not found).
5. **Anonymity preserved** — existence reads election identity only, never `votes`/`results` (ADR-T11).
6. **No cross-system transaction** — existence is a read; the correction write is Election's own transaction (ADR-T1).
7. The **domain never knows** whether existence is provided by legacy or by a future greenfield implementation — it only sees `ElectionRepository::find()`.
8. The **ACL is removable without domain change** — the Strangler exit is a single binding swap (legacy adapter → greenfield adapter).
9. **Aggregate Reconstruction Invariant** — the Election aggregate is reconstructed from **multiple persistence sources** (legacy *existence* + greenfield *reaction state*); **no single source is individually authoritative for the aggregate as a whole**. Concretely: `Election ≠ legacy row`; `Aggregate = legacy existence + greenfield reaction state`. Future developers must not assume `Election == the legacy elections row`.

### ARB rulings folded in
- Port is a **business-oriented `ElectionExistencePort`** with `exists()` (not `ElectionDirectory`/`knows()`).
- Ownership language corrected: Election BC owns the *concept*; legacy is the *current operational source of truth*.
- Strangler invariant made explicit (#7 above).
- **No ADR** — kept as IDD.

### Closing questions
- **Improve PublicDigit delivery?** Yes — a correct, existence-grounded reaction without prematurely building Election lifecycle.
- **Architectural entropy?** Reduced — legacy coupling contained behind one explicit, removable port; domain stays autonomous; ownership made explicit.

## Step 4A.3 — RED Test Matrix + failure ownership (IDD; awaiting ARB approval BEFORE any RED)

### Who owns the failure? (closing the IDD gap the ARB flagged)
The IDD defined *exists* and *doesn't exist*; it did not define *cannot determine existence*. Decision (an application of the frozen messaging failure taxonomy, Blueprint §8 — **IDD-level, not a new ADR**):

| Situation | Classification | Handled where | Message outcome |
|---|---|---|---|
| Election exists | — | apply correction | Processed |
| Election **absent** (queried OK, no row / other org / soft-deleted) | **business absence** — domain/application | `CannotApplyDeterminationToUnknownElection` (business exception) | permanent → dead-letter/incident (never retried as transient) |
| Existence **cannot be determined** (legacy DB down, query error) | **infrastructure failure** | infra exception **propagates unchanged**; Application does NOT translate it to "unknown"; Domain never sees it | transient → inbox wrapper rolls back, relay redelivers (F5) |

**Load-bearing invariant:** the ACL adapter returns `false` **only** for a *successful* query with no matching row. On a query/connection failure it **throws** (never returns `false`) — otherwise a transient infra fault would be misclassified as a permanent business absence and wrongly dead-lettered. *Business absence ≠ infrastructure failure.*

### RED Test Matrix (behaviour → expected; write RED from THIS, not from implementation structure)
**Composition logic — unit, in-memory doubles (fast, no DB; ER-07 behaviour):**
| # | Scenario | Expected |
|---|---|---|
| 1 | existence=true, no prior corrections | `find()` → Election; `applyDetermination` emits 1 `ElectionCorrectionApplied` |
| 2 | existence=false | `find()` → null → handler raises `CannotApplyDeterminationToUnknownElection`; no event |
| 3 | existence=true, determination already applied (prior in store) | reconstituted with prior set → reapply idempotent; no event |
| 4 | existence port **throws** (infra) | exception **propagates** (NOT converted to unknown-election); no event |

**Legacy ACL adapter — Feature/DB (`LegacyElectionExistenceAdapter` over real `elections`):**
| # | Scenario | Expected |
|---|---|---|
| 5 | legacy row present in ambient org | `exists()` → true |
| 6 | legacy row present, **different** org | `exists()` → false (tenant isolation) |
| 7 | legacy row **soft-deleted** | `exists()` → false |
| 8 | no legacy row | `exists()` → false |

*(Anonymity is structural, not a runtime assertion: the adapter queries `elections` only — a table with no vote/voter columns — so no anonymity data is reachable. Invariant #5.)*

**Corrections store + composite repository — Feature/DB (end-to-end reconstruction):**
| # | Scenario | Expected |
|---|---|---|
| 9 | `save(Election)` with an applied determination | row persisted, org-scoped; `appliedDeterminations(id)` returns it |
| 10 | save same applied determination twice | single row (tenant-scoped unique) — persistence idempotent |
| 11 | legacy election exists + store has prior det → `find()` | Election reconstituted with prior applied set (cross-reload idempotency) |
| 12 | legacy election exists + no corrections → `find()` then apply + `save()` | reconstitutes empty set → first correction applies and persists |

### Conventions to mirror — and debt to NOT inherit
Mirror: pgsql `nrna_test` harness · `Tests\TestCase` base · real `Organisation` + `TenantContext::set()` in `setUp()` · migration loading via a context `ElectionServiceProvider` registered in `config/app.php` · `updateOrCreate`/tenant-trait repository style.
Do **not** inherit: the belt-and-suspenders duplicate `use RefreshDatabase` (base already has it) · reliance on the platform-org-slug fallback (`'platform'` vs `'publicdigit'` mismatch) — always set `TenantContext` explicitly · any naming/shortcut that doesn't fit the Election ubiquitous language.

**Do NOT test `TenantContext` internals** (verified elsewhere): tests observe only externally-visible tenant *behaviour* — same tenant → found, different tenant → not found — never *how* tenant resolution is implemented.

**STOP — awaiting ARB approval of this matrix + failure decision before writing RED.**

## Step 4B — Messaging Integration (IDD; awaiting ARB approval BEFORE any RED)

Consume-only against the frozen Messaging Platform (ARR PASS — no platform change). Reuse the Adjudication pattern; invent nothing.

### DDD Ownership Check
| Concern | Owner | Note |
|---|---|---|
| `ElectionCorrectionApplied` (the event) | **Election** | Catalog: Election/Lifecycle · Integration · Core; ownership map Election→ElectionCorrectionApplied. Permanent. |
| Publication **decision** ("this event occurred") | **Election (domain)** | The aggregate decides the correction happened and records the event. |
| Publication **mechanism** (serialize · enqueue · relay) | **Shared Messaging Platform** | Infrastructure serializes, enqueues the outbox row, and the relay re-emits downstream. |
| Transport `event_id` (outbox row identity) | **Outbox Adapter** | Generated (`Str::uuid()`) when mapping domain event → `OutboxEvent`. NOT domain identity — the domain event carries only business identity (electionId, determinationId). |
| Outbox **mechanism** | **Shared platform** (frozen) | Election owns only its *adapter* mapping its event → an `outbox_events` row. |
| Inbox **mechanism + registry** | **Shared platform** (frozen) | Election owns only *registering* its handler. |
| Transaction boundary (ADR-T1) | **Inherited from `Inbox::consume()`** | apply→enqueue→save run inside the inbox's `DB::transaction`; one aggregate + its outbox row(s) per txn. **No Election TransactionManager.** |

### Implementation Design (3 artifacts, mirror Adjudication)
1. **Outbox adapter** `App\Contexts\Election\Infrastructure\Outbox\ReactionOutboxAdapter implements ReactionEventOutbox` — `enqueue()` uses `match(true)` (explicit per-event writer, no reflection) → save Shared `OutboxEvent`: `event_id`=uuid · `organisation_id`=**`TenantContext::require()`** (never `get()` — the S7/FK lesson) · `aggregate_type`='Election' · `aggregate_id`=electionId->toString() · `event_type`='ElectionCorrectionApplied' · `payload`={`schema_version`:1, electionId, determinationId, correctionType(`->value`='contained_only'), appliedAt(ATOM)} · status='pending' · attempts:0 · available_at=now(). Bind `ReactionEventOutbox → ReactionOutboxAdapter` in `ElectionServiceProvider::register()`.
2. **Inbox registry wiring** — `ElectionServiceProvider::boot()`: `InboxHandlerRegistry::register($app->make(DeterminationIssuedReactionHandler::class))` (deps already resolvable: ElectionRepository, ClockInterface, ReactionEventOutbox via #1).
3. **Hydrator** `App\Contexts\Election\Infrastructure\Outbox\ElectionCorrectionAppliedHydrator implements EventHydrator` — `eventType()`='ElectionCorrectionApplied'; `hydrate(payload)`→ `new ElectionCorrectionApplied(ElectionId::fromString, DeterminationId::fromString, CorrectionType::from, new DateTimeImmutable(appliedAt))` with a `required()` guard + schema_version(v1) dispatch. Register via `EventHydratorRegistry` in `boot()`. Required: `ElectionCorrectionApplied` is a published Integration event (downstream consumer: Contestation, PB-005) — also satisfies `EventRegistryCompletenessTest`.

Adapter + hydrator define the wire contract together and evolve together (ER-07: transport is tested by their round-trip, not by domain behaviour tests).

**Composition-seam note (ARB):** `ElectionExistencePort` and `AppliedDeterminationLedger` are **implementation-composition seams**, NOT part of the Election domain's ubiquitous language; when the Strangler migration completes they may change or disappear without affecting the domain model — only `ElectionRepository` (the Domain Port) is permanent.

**What the hydrator hydrates (ARB):** `ElectionCorrectionAppliedHydrator` reconstructs Election's **domain event** (`ElectionCorrectionApplied`) from the **published payload** (the cross-context wire contract). The Shared relay (`OutboxEventProcessor`) then wraps that domain event in the `IntegrationEvent` envelope that actually crosses the bounded-context boundary. So: payload schema = the published contract (owned jointly by adapter + hydrator, producer side); domain event = the local reconstruction; envelope = platform-owned.

**Messaging ownership (ARB — made explicit):**
- The **Outbox Adapter translates, serializes, and enqueues** the published Integration Event (maps the Domain Event → outbox representation and writes the `OutboxEvent`). It does **not** publish and does **not** republish Domain Events directly; the **Shared Relay** performs the eventual publication. Domain↔Integration translation is an **Infrastructure** responsibility. Chain: `Domain → Domain Event → Infrastructure translate·serialize·enqueue Integration Event → Shared Relay publishes`.
- The transport **`EventId`** is owned by the **Outbox / Shared Messaging layer** (adapter-assigned `Str::uuid()`), never the Domain — the domain event carries only business identity (electionId, determinationId).

### RED Behaviour Matrix (write RED from this)
| # | Scenario | Level | Expected |
|---|---|---|---|
| 1 | `ElectionCorrectionApplied` written atomically with the reaction (via `Inbox::consume`) | Feature/DB | after consuming an Upheld `DeterminationIssued` for an existing election: exactly 1 `outbox_events` row (event_type=ElectionCorrectionApplied, aggregate_type=Election, aggregate_id=electionId, org-scoped, status=pending) AND the reaction-state ledger row persisted — in one txn |
| 2 | exactly one outbox record per successful correction | Feature/DB | one row, no duplicates |
| 3 | duplicate determination → no duplicate event | Feature/DB | re-consume (same event_id) → inbox dedupe / aggregate idempotency → still exactly 1 outbox row |
| 4 | infrastructure failure rolls back BOTH aggregate state and outbox | Feature/DB | a throw mid-consume → 0 outbox rows AND 0 ledger rows (inbox txn rollback) |
| 5 | hydration preserves all business data | Unit | `hydrator.hydrate(adapter payload)` round-trips to an `ElectionCorrectionApplied` with identical electionId/determinationId/correctionType/appliedAt |
| 6 | inbox registry resolves the correct handler | Feature | after provider boot, `InboxHandlerRegistry::handlerFor('Election','DeterminationIssued')` returns the reaction handler |
| 7 | adapter requires a tenant | Unit | `enqueue` with no ambient tenant → throws (mirror Adjudication `TenantContext::require()` guard) |
| 8 | unsupported `schema_version` is rejected, never silently hydrated | Unit | `hydrate()` of a payload with an unknown `schema_version` (e.g. 99) → throws (rejected per platform policy); it never returns a silently-hydrated event. (Election supports only v1; absent → treated as v1.) |

### Divergences flagged (no approved-artifact change required — per instruction 6)
- **CorrectionType**: code intentionally has only `ContainedOnly` (ADR-T8 forward-only, approved in Step 3); the catalog lists `ReRun|Invalidate|Accept|ContainedOnly`. Consistent with the approved decision; hydrator maps `'contained_only'` ↔ `ContainedOnly`. No change.
- **`outbox_events.aggregate_id` is `uuid`**: in production the electionId is a legacy uuid (resolved via the ACL), so consistent. Feature tests must use uuid-shaped electionIds. `ElectionId` VO does not enforce uuid (accepts any non-empty string) — **observation** (retrospective), not a change.

### Reuse (not reinvent)
Shared `OutboxEvent` · `EventHydrator`/`EventHydratorRegistry` · `InboxHandlerRegistry` · `Inbox::consume` txn. Mirror `OutboxEventAdapter` + `DeterminationIssuedHydrator` + provider registration. Test templates: `AdjudicationServiceIntegrationTest` (outbox atomicity), `InboxConsumeTest` (rollback/dedupe), `OutboxEventProcessorRegistryTest` (hydration), `OutboxEventAdapterTest` (`TenantContext::require` guard).

**STOP — awaiting ARB approval of this design + matrix (and the `Store`→`Ledger` rename decision) before writing RED.**

## Next (not authorized yet)
- **Step 4B** — *(design + matrix above; RED begins on ARB approval)*. Then **4C** qualification.
- **Step 4A.3** — persistence for the reaction (corrections/idempotency store) once existence has a source.
- **Step 4B** — messaging integration (outbox adapter · inbox registry wiring · `ElectionCorrectionApplied` hydrator).
- **Step 4C** — architecture qualification (extend `GreenfieldCoreArchitectureTest` to Election as a complete hexagonal context · full regression · docs · Completion Review).

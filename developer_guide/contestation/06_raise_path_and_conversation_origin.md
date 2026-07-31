# WP-5 — The raise path, and where the integration conversation begins

**Step:** WP-5 (raise → admit → route) + WP-3B (Correlation Origin Relocation, ARB Option B) · **Status:** GREEN, awaiting slice acceptance

Before this step the `Challenge` aggregate could do everything and *nobody could call it*: `raise`, `admit` and `route` existed and were test-pinned, with no application service. WP-3B was blocked on exactly that absence. This step supplies the callers — and with them, the place where the correction loop's conversation actually begins.

---

## The distinction this step introduces

> **Business process origin and integration conversation origin are intentionally different concepts.**
> The **business process** begins at `raise` — a standing-holder's act.
> The **integration conversation** begins at `route`, when the first published event is emitted.

That is not wordplay; it decides where the correlation id is minted, and it was settled by evidence:

- `raise` **publishes nothing** — so a correlation minted there would have to *survive* until routing;
- the `challenges` table has **no provenance column** — so surviving would mean **persisting provenance as challenge state**, making an audit concern part of the business model.

Minting at `route` costs neither. The ARB approved the mint at `route` on that basis (2026-07-31).

## Where it fits

```
raise    ── Challenge::raise()   → save            NO outbox write   (business process origin)
admit    ── Challenge::admit()   → save            NO outbox write
route    ── Challenge::route()   → save            → outbox: ChallengeRouted, schema v1
                                                     EventProvenance::start(…)   ← integration
                                                                                    conversation ORIGIN
                                        │
                                        ▼  relay → dispatcher → inbox
                                   Adjudication opens exactly one process (PM-1)
```

**TP-2 is visible in that last arrow:** Contestation *published*; Adjudication *decided* to open. Contestation never creates an adjudication.

## Key files

| File | Role |
|---|---|
| `Application/Service/ContestationService.php` | The port: `raise` / `admit` / `route` |
| `Application/Service/CoordinatesContestation.php` | The coordinator — orchestration only, plus the mint at `route` |
| `Application/Service/TransactionalContestationService.php` | ADR-T1 boundary (decorator) |
| `Application/Command/RaiseChallengeCommand.php` | VOs only; time is **not** a caller parameter |
| `Application/Port/IdentityGenerator.php` + `Infrastructure/Identity/UuidIdentityGenerator.php` | Correlation id source, usable inside Application |
| `Application/Port/TransactionManager.php` + `Infrastructure/Transaction/LaravelTransactionManager.php` | Transaction mechanics |
| `Infrastructure/Providers/ContestationServiceProvider.php` | Wiring (`register()`) |
| `tests/Architecture/Messaging/CorrelationIdMintingTest.php` | Allowlist — now names **two** originators |

## Why Contestation has its own copies of two ports

Adjudication already owns an identical `IdentityGenerator` and `TransactionManager`. **Importing them would be the codebase's first cross-context Application dependency** and would fail Deptrac — the Cross-Context Integration Contract's R-1/R-2. So the **pattern** is reused and the **classes** are not. If that feels like duplication, it is the boundary doing its job: two contexts that must be independently deployable cannot share application plumbing.

Note also *why* the port exists at all rather than a facade call: the mint happens in the **Application** layer, where facades are banned (house Rule 2). `Str::uuid()` lives behind the port, in Infrastructure.

## How it works

`handle`-style thinness, deliberately — the aggregate owns every rule:

```php
public function route(ChallengeId $id, string $routedTo): void
{
    $challenge = $this->challenges->get($id);
    $challenge->route($routedTo, $this->clock->now());   // guards live HERE

    $this->challenges->save($challenge);

    $this->outbox->enqueue(
        EventProvenance::start($this->identities->next()),   // the conversation ORIGIN
        ...$this->routedEventsOf($challenge),
    );
}
```

### Publication policy: only integration events reach the outbox

`routedEventsOf()` selects `ChallengeRouted` **by type** instead of draining `pullEvents()`:

```php
foreach ($challenge->pullEvents() as $event) {
    if ($event instanceof ChallengeRouted) { $routed[] = $event; }
}
```

`ChallengeRaised` and `ChallengeAdmitted` are Contestation's internal record of its own process — no consumer, no hydrator, no authority to cross a boundary. Selecting by type makes that a **decision this class owns**, rather than a side effect of whatever the outbox adapter happens to map. The corresponding test asserts the invariant, not the symptom: *after a full raise→admit→route, exactly one outbox row exists and it is `ChallengeRouted`.*

## The mint allowlist now has two entries — on purpose

```php
private const CHAIN_ORIGIN_ALLOWLIST = [
    'app/Contexts/Contestation/Application/Service/CoordinatesContestation.php',  // correction-loop origin
    'app/Contexts/Adjudication/Application/Service/CoordinatesAdjudication.php',  // authority-decision origin
];
```

WP-5 relocated the **correction-loop** origin. Adjudication's `issueDetermination` still mints because the authority's decision **does not yet arrive as a message** — it has nothing to derive provenance from; it becomes a reacting producer when that path is wired (WP-6). **Two originators serving two different paths is by design:** the one-mint rule is per *conversation*, not per codebase.

## Testing

`tests/Feature/Contexts/Contestation/ChallengeRaisePathTest.php` — 9 keystones, 25 assertions:

| Keystone | Proves |
|---|---|
| raise / admit | the capability exists and persists |
| illegal admit | the aggregate refuses **without mutating** |
| route publishes one v1 `ChallengeRouted` | ADR-T21 + the frozen payload contract |
| refused route publishes nothing | ADR-T1 atomicity |
| **only integration events are published** | the publication policy above |
| route mints; `causation_id` is null | ADR-MP-06 chain start |
| **production raise path opens an adjudication** | the loop head from a real mint — replaces WP-4's test-seeded provenance |
| allowlist names the routing service | the one-mint rule stays machine-enforced |

**Gates:** PHPStan max — no errors · Deptrac — **0 violations** · Architecture suite **146 green** (including the relocated minting guard) · Contestation + Adjudication feature suites **33 tests, 95 assertions green**.

## Pitfalls

- **Don't mint in `raise`.** It publishes nothing, so the correlation would have to be persisted — turning provenance into challenge state. See the distinction at the top.
- **Don't drain `pullEvents()` into the outbox.** The adapter maps only integration events and throws `LogicException` on anything else; more importantly, publishing an internal event would be a crossing without authority.
- **Don't import Adjudication's ports** — R-1/R-2, Deptrac will fail. Mirror the pattern.
- **Don't add guards to the service.** Every legality question belongs to `Challenge`; the service would only be able to disagree with it.
- **Don't pass a timestamp in from the caller.** The service owns time via `ClockInterface`, so a caller cannot forge a raise or routing time.

---

**Traceability:** roadmap §WP-5 + WP-3B (ARB Option B, 2026-07-31) · TP-2 (*Contestation requests, never creates*) · ADR-T21 · ADR-MP-06 · ADR-T1 · ADR-T16 · frozen `docs/architecture/Cross_Context_Integration_Contract.md` (R-1/R-2) · plan `.claude/plans/WP-5-raise-path.md` · previous step `05_challenge_routed_published_language.md` (whose deferred *Correlation Origin Relocation* this step completes for the loop head).

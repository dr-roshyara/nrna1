# WP-4C-1 — `AdjudicationFailureDeclared`: announcing that no ruling can issue

**Step:** WP-4C-1 · **Status:** **implemented for its authorized scope** — the event, its hydrator, its outbox writer and its registration. **Two deliverables are carried, not done** — see [Carried work](#carried-work).

WP-2 gave the process manager PM-7: `receiveInsufficiencyDecision()` records that the authority found the evidence insufficient. Its docblock has always said what was missing — *"the failure is recorded; **announcing it is WP-4's wiring**."* This step supplies the announcement.

---

## The distinction that governs everything here

> **This is a failure of EVIDENCE. `AdjudicationExpired` is a failure of TIME.**

Both are terminal facts and neither is a verdict, so both carry **no outcome and no legitimacy** — nothing was ruled *on the contested outcome*, only that it could not be ruled. **But only this one carries an authority and a stated ground, because a person decided it.** A horizon decides nothing (Constitutional Policy 4); an authority declaring insufficiency decides something, and its identity and reason are part of the fact.

**If you are ever tempted to merge the two events, that is the difference to weigh.**

## Where it fits

```
authority's insufficiency decision
        │
        ▼
AdjudicationProcessManager::receiveInsufficiencyDecision()
        └─ state->concludeFailureDeclared()   →  status = ConcludedFailureDeclared   (WP-2)
                                                 records reason · authority · concludedAt
        ▼
   ⛔ NOT YET WIRED — the publication call site is carried work (D4)
        ▼
OutboxEventAdapter::enqueue(provenance, AdjudicationFailureDeclared)
        └─ writeAdjudicationFailureDeclared()  →  outbox_events row, payload v1
                                                          │
                                                          ▼
                          AdjudicationFailureDeclaredHydrator::hydrate()  →  the event again
```

## Key files

| File | Role |
|---|---|
| `Domain/Events/AdjudicationFailureDeclared.php` | the announced fact — four non-nullable fields |
| `Infrastructure/Outbox/AdjudicationFailureDeclaredHydrator.php` | reconstruction; v1-only window |
| `Infrastructure/Outbox/OutboxEventAdapter.php` | the `enqueue()` dispatch arm + `writeAdjudicationFailureDeclared()` |
| `Infrastructure/Providers/AdjudicationServiceProvider.php` | registry registration (line ~81) |

## The aggregate's invariant, and why the event does not defend it

`concludeFailureDeclared()` sets `reason`, `concludedByAuthority` and `concludedAt` **together**. Their accessors are nevertheless nullable, because the process record legitimately has none of them before conclusion — **the type system cannot express "these three are set together after this transition."**

**So the event's four constructor parameters are non-nullable, deliberately:**

```php
final readonly class AdjudicationFailureDeclared implements DomainEvent
{
    public function __construct(
        public ChallengeRef $challengeRef,
        public Reason $reason,
        public IssuedByAuthority $declaredByAuthority,
        public DateTimeImmutable $declaredAt,
    ) {}
}
```

**The invariant belongs to the aggregate; the event only carries it.** The absent case is made *unrepresentable* here rather than *defended* here — and whatever constructs the event **fails closed** (AP-1): if a source is null the process did not conclude a failure, so no event is produced and none is guessed at. **An event that could hold a null `reason` would be an event that could announce an insufficiency nobody stated.**

A reflection-based keystone pins this: every constructor parameter must be typed and non-nullable.

## What the payload deliberately omits

**`consideredEvidence` is not on the event.** EPIC-004K §10 states the occurrence as insufficiency, not as an evidence manifest, and **no consumer need has been demonstrated.** Under ADR-T11 every payload addition is a question about what crosses a boundary — never a convenience. **If a consumer later needs the considered set, that is a payload version, not an edit** (ADR-T5).

## Hydrator contract and versioning

```
schema_version : 1     ← the only version that exists
challengeRef           reason           declaredByAuthority           declaredAt
```

**v1-only window.** Any other version throws `InvalidArgumentException` rather than being guessed at (ADR-T5), and a **missing required field throws too** — **two distinct failures, tested separately.** An unsupported version and an incomplete payload have different causes, and one combined "invalid input" test would let either regress unnoticed.

**The hydrator returns value objects, not strings.** A hydrator handing back primitives satisfies every value-equality assertion and breaks every consumer — `assertInstanceOf` exists for exactly that.

## Registration — the half that is easy to forget

```php
$registry->register(new AdjudicationFailureDeclaredHydrator());   // AdjudicationServiceProvider::boot()
```

**Publication alone is not published language: it requires publication AND registration** (the WP-3A rule). An unregistered hydrator fails *nowhere* until a consumer tries to hydrate — by which time the payload is already in the outbox. The wiring assertion lives in the **existing** `EventHydratorRegistryWiringTest`; **extend that file, do not add a second.**

## Adapter responsibilities — as they are, not as they should be

`OutboxEventAdapter` combines **dispatch · payload mapping · provenance stamping · persistence**. `enqueue()` dispatches on event type; the private writer builds the row, stamps `correlation_id`/`causation_id` from the supplied `EventProvenance`, and saves it.

**This is an implementation characteristic and a candidate future refactoring — not an architectural principle, and not something to fix here.** If the adapter is ever decomposed into dispatcher / payload mapper / persistence writer, **all events migrate together; never this one alone.** Consistency with the family beats a local improvement.

**The writer decides no provenance.** It stamps whatever its caller supplies, exactly as its siblings do — so it neither answers nor prejudices the open question of what provenance a real declaration should carry.

## The round-trip contract

`tests/Feature/…/AdjudicationFailureDeclaredOutboxRoundTripTest.php` writes through the real adapter, reads the stored row, and hydrates it.

**Why it exists, in one sentence: a hydrator checked only against a fixture agrees with itself.** Writer and hydrator are a *contract* only once one produces what the other consumes — and this test fails if they ever disagree on a field name, a format, or the version, where the unit tests do not.

**It proved that on its first run**, failing with `invalid input syntax for type uuid`. **`outbox_events.aggregate_id` is a UUID column; `ChallengeRef::fromString()` does not enforce that shape**, so unit fixtures like `ch-4c1-1` pass happily with no database. **Recorded, not repaired** — whether `ChallengeRef` should validate its own shape is a value-object question outside this slice.

## Testing

| Test | Covers |
|---|---|
| `tests/Unit/…/Events/AdjudicationFailureDeclaredTest.php` | K1 — the four facts unchanged · VO types · **non-nullable constructor by reflection** |
| `tests/Unit/…/Outbox/AdjudicationFailureDeclaredHydratorTest.php` | K2 — event type, reconstruction, VO types · K3 — unsupported version **and** missing field, separately |
| `tests/Feature/…/Outbox/EventHydratorRegistryWiringTest.php` | K4 — the registry resolves the hydrator |
| `tests/Feature/…/AdjudicationFailureDeclaredOutboxRoundTripTest.php` | writer → persistence → hydrator, and the version window against the **real** payload |

**Use `#[DataProvider]`, not `@dataProvider`.** Doc-comment metadata is deprecated in PHPUnit 11 and removed in 12; the first gate run of this slice reported a deprecation for exactly that, **and the gate still said PASS** — a green gate is not a clean one.

---

## Carried work

**These are not implemented and this guide does not describe them as if they were.**

| Item | State |
|---|---|
| **D3 — the catalog deliverable** | **held.** The catalog is FROZEN (*"changes follow ADR-T5: version, never mutate"*), and **`AdjudicationExpired` is absent from it although WP-6 shipped it hydrated and accepted.** Whether this slice must produce a **new catalog version** is an open governance question. **No catalog file is touched.** |
| **D4 — the publication call site** | **held.** Nothing in production enqueues this event yet. The open question is provenance: an insufficiency decision *continues* the conversation the challenge began, so minting a new correlation would breach the one-mint invariant, while `fromConsumed()` needs the authority-decision intake (WP-4D, unbuilt). |
| **WP-4C-2 — Contestation's reaction** | **blocked.** EPIC-004K §15.3 carries *"challenge disposition on declared failure"* as an open **Contestation-side** design question. |

> **State plainly what this slice delivers: a registered, hydratable, writable event that no production path yet enqueues.** That is the WP-4B position — *buildable while nothing feeds it* — and it must be described that way, never as a completed announcement.

**How this guide evolves:** it is a **Reference artifact** and therefore living (ES-004.3) — when D3/D4 land, **this file is revised in place.** Do not create `08_…_v2.md`.

## Traceability

EPIC-004K **§10** (messages produced) · **§15.3** · PM-7 · **R-88** (subdivision) · **R-89** (authorization + the E1 obligation) · ADR-T5 (version, never mutate) · ADR-T3 · ADR-T11 · AP-1 · AP-2 · Constitutional Policy 4 · the WP-3A rule (publication **and** registration) · WP-6 precedent (`AdjudicationExpired`) · plan `docs/plans/20260804-1900-wp4c1-adjudicationfailuredeclared-plan.md` §6/E1, §6/E2, §17.

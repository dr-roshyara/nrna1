# 04 — Messaging integration (PB-005 Step 5C)

## Purpose
Connect the Contestation reaction to the frozen Messaging Platform: consume `DeterminationIssued` / `ElectionCorrectionApplied` through the Inbox, and publish `ChallengeAdjudicated` / `ChallengeResolved` through the Outbox — atomically, and re-emittable to downstream consumers. Consume-only against the platform (ARR PASS; no platform modification).

## Where it fits (layer / namespace)
```
app/Contexts/Contestation/
  Application/
    Resolution.php                       # integration-only outcome (Upheld/Dismissed) — NOT domain
    ChallengeResolvedIntegration.php      # the F-2 enrichment seam: minimal domain event + Resolution
  Infrastructure/Outbox/
    ChallengeOutboxAdapter.php            # implements ChallengeEventOutbox → writes outbox_events
    ChallengeAdjudicatedHydrator.php      # payload → minimal domain event
    ChallengeResolvedHydrator.php         # payload → minimal domain event (ignores resolution)
  Infrastructure/Providers/ContestationServiceProvider.php   # bind adapter; register handlers + hydrators
```

## The `resolution` enrichment seam (emergent from RED — F-2)
The RED asserted only the *outcome*: the published `ChallengeResolved` payload must carry `resolution`, while the domain event stays minimal. The **simplest** implementation that satisfied it:
- A tiny Application carrier **`ChallengeResolvedIntegration`** = the unchanged `ChallengeResolved` domain event **+** the `Resolution` the Application derives (`Upheld` on the correction path; `Dismissed` on the short-circuit).
- The reactions `enqueue(...)` that carrier for the resolved event (and the raw `ChallengeAdjudicated` for adjudication); the adapter's `match(true)` maps the carrier to a `ChallengeResolved` outbox row whose payload includes `resolution`.
- **No envelope, no DTO hierarchy, no domain change.** The domain event is never touched; `resolution` exists only on the published payload. The hydrator reconstructs the minimal domain event and *ignores* `resolution`.

This did refactor the accepted 5A reactions' outbox interaction (they now attach `resolution` for the resolved event) — their unit tests were updated to assert the carrier. Documented as the emergent-seam change.

## How it works (code)
```php
// Reaction supplies resolution explicitly; domain event stays minimal.
$this->outbox->enqueue(...$this->enrich($challenge->pullEvents(), Resolution::Upheld));

// ChallengeOutboxAdapter maps the carrier → ChallengeResolved outbox row WITH resolution:
'payload' => ['schema_version'=>1, 'challengeId'=>..., 'determinationId'=>..., 'resolution'=>$integration->resolution->value, 'occurredAt'=>...]
```
Ownership chain: `Domain Event → Application (attaches resolution) → Integration Event → Outbox Adapter → Shared Relay publishes`. Atomicity is inherited from `Inbox::consume()`'s transaction (ADR-T1); the transport `event_id` is adapter-assigned; `organisation_id` via `TenantContext::require()`.

## Testing
- Feature/DB (`ContestationReactionMessagingTest`): consuming an Upheld loop publishes `ChallengeAdjudicated` then `ChallengeResolved` with `resolution='upheld'`; a Dismissed determination publishes both with `resolution='dismissed'`; the registry resolves both handlers. Reuses the PB-004 convention (assert against `outbox_events`).
- Unit (`ChallengeEventHydratorsTest`): hydrators reconstruct the minimal domain events and **do not** put `resolution` on them; unsupported `schema_version` rejected.
- Harness: raw-uuid ids (outbox `aggregate_id` + inbox `event_id` are uuid columns); pgsql has no per-test rollback (unique ids per test).

## Why `ChallengeResolvedIntegration` is a carrier, not a pattern (ARB Q1)
It is a **temporary implementation carrier**, created solely because RED required the published payload to carry `resolution` while the domain event stayed minimal. It is **not** an architectural pattern and introduces no framework: a plain, immutable pair (minimal domain event + `Resolution`), scoped to Contestation's publish path (the reactions build it; the outbox adapter maps it). It is not reused by any other context. If a genuinely generic "integration enrichment" abstraction is ever justified by repeated evidence across contexts, this carrier would be replaced by it — evidence-first (ER-02); until then it stays deliberately specific and minimal.

## Why `Resolution` lives in the Application, not the Domain (ARB Q2)
- **The Domain event stays minimal:** once a Challenge is `Resolved` (terminal), no aggregate behaviour or reconstitution branches on *how* it resolved. The Challenge's own history is complete without the outcome flag (F-2 analysis: aggregate behaviour = no, reconstitution = no).
- **The Integration event is enriched:** downstream consumers (AdjudicationService, Audit, Legitimacy projections) need to know whether the challenge was **upheld** (a correction was applied) or **dismissed** — that is a cross-context reporting need, not an intra-Contestation one.
- **Downstream needs it; the Domain does not** → by the Domain-Event ≠ Integration-Event separation (established in PB-004), the fact belongs **outside** the Domain, on the published payload only.
- **It is derived at reaction time** from the trigger (`ElectionCorrectionApplied` ⇒ Upheld; `DeterminationIssued outcome=Dismissed` ⇒ Dismissed) — an Application orchestration concern, not a domain invariant. Hence `Resolution` is an Application enum and the reactions supply it explicitly at publish time.

## Pitfalls
- `resolution` is integration-only — never add it to the `ChallengeResolved` domain event (F-2).
- The adapter maps the **carrier** for resolved, the **raw event** for adjudicated — keep the `match(true)` arms explicit.
- Outbox `aggregate_id` and inbox `event_id` are uuid columns — ids used there must be uuids.

## Traceability
PB-005 Step 5C · IDD §3/§4/§7 + F-2 · Canonical Event Catalog (`ChallengeAdjudicated`/`ChallengeResolved` — Contestation producer; consumers AdjudicationService + Audit) · ADR-T1 (inbox-inherited atomicity) · ADR-T11 (anonymity) · ADR-T16 (local VOs) · ER-03 (reuse the Adjudication/Election messaging pattern) · ER-07 (Domain Event ≠ Integration Event).

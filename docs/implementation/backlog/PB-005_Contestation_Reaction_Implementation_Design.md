# PB-005 (Contestation Reaction) — Implementation Design Document (IDD)

**Status:** ARB-approved design (Phase 2). Awaiting ARB rulings on open findings F-1…F-5 before RED (Phase 3). **2026-07-08.**

## Context
Phase 1 Discovery approved. ARB rulings: PB-005 = **Reaction + Infrastructure only**; out-of-order → **park**; **application timestamp**; **Dismissed short-circuit**; frozen-catalog inconsistencies **recorded, implement against ADRs**, correct docs separately after the IDD. Q3 (replay classification) and Q7 (`ChallengeResolved` payload) are **analysed and recommended here, not pre-decided**. Reuse is justified per-pattern, not by precedent. No RED until this IDD is approved.

## 1. Strategic ownership
Contestation **owns** the `Challenge` aggregate (concept, lifecycle, identity, events, and — new here — its persistence). It **consumes** `DeterminationIssued` (Adjudication) and `ElectionCorrectionApplied` (Election); it **produces** `ChallengeAdjudicated` and `ChallengeResolved`. Because Contestation owns the aggregate there is **no legacy source** → **no ACL, no existence port, no multi-source Aggregate Reconstruction** (the PB-004 Strangler pattern is explicitly NOT reused; §7).

## 2. Aggregate lifecycle (PB-005 touches only the tail)
`Routed → Adjudicated → Resolved▣` via `adjudicate(DeterminationId,$at)` and `resolve(DeterminationId,$at)`; the domain guards `resolve` to require `Adjudicated` (throws `IllegalChallengeTransition`). Per Q1, PB-005 **assumes the Challenge is already persisted and `Routed`** (raise→admit→route is a separate backlog item; tests seed a Routed Challenge directly).
**Domain addition needed for persistence:** the aggregate has only `raise()` + transitions; the mapper needs `Challenge::reconstitute(id, state, contestedOutcome, raiser, content, ?determinationId)` to rehydrate a `Routed`/`Adjudicated` Challenge (mirrors `Election`/`Determination` reconstitute). Minimal, no behaviour change.

## 3. Event flow
- **Inbound** `DeterminationIssued` (schema v2; carries `challengeId`, `determinationId`, `outcome`) → `AdjudicateChallengeHandler`.
- **Inbound** `ElectionCorrectionApplied` (schema v1; carries `electionId`, `determinationId`, `correctionType`, `appliedAt` — **no `challengeId`**) → `ResolveChallengeHandler`.
- **Outbound** `ChallengeAdjudicated`, `ChallengeResolved` → Contestation outbox → relay → consumers AdjudicationService + Audit.
- **Upheld:** `adjudicate()` on `DeterminationIssued` → later `resolve()` on `ElectionCorrectionApplied`.
- **Dismissed (short-circuit, ARB-approved):** on `DeterminationIssued` `outcome=Dismissed`, one reaction does `adjudicate()` **then** `resolve()` (Election stays silent), emitting both events in one inherited transaction. Handler branches on `outcome`.
- Timestamps = **application time** (injected `ClockInterface`), per PB-004 Q1.

## 4. Inbox behaviour
**Two `InboxHandler`s** (recommended over one multi-type handler — single responsibility, distinct registry keys `(Contestation, DeterminationIssued)` and `(Contestation, ElectionCorrectionApplied)`):
- `AdjudicateChallengeHandler` (`consumerContext='Contestation'`, `eventTypes=['DeterminationIssued']`).
- `ResolveChallengeHandler` (`eventTypes=['ElectionCorrectionApplied']`).
Atomicity **inherited** from `Inbox::consume()`'s `DB::transaction` (no Contestation TransactionManager). Transport-duplicate dedupe is the platform's (`(event_id, consumer_context)`).

## 5. Parking behaviour (new business use of an existing capability)
`ResolveChallengeHandler` finds the Challenge (§6) and inspects state:
- `Adjudicated` → `resolve()` (happy path).
- `Routed` (`ElectionCorrectionApplied` arrived before `DeterminationIssued`) → throw **`CausalPreconditionMissing`** → inbox **parks + re-drives** (temporally premature, NOT invalid). *Contestation is the first reacting BC to use parking as business workflow.*
- terminal / conflicting → §9 F-1.

## 6. Persistence strategy (single-source greenfield — Contestation owns the Challenge)
- `EloquentChallengeRepository implements ChallengeRepository` — normal single-source repo (contrast PB-004's composite). `challenges` table (greenfield migration, `Infrastructure/Database/Migrations/Tenant/`), `ChallengeModel` (`BelongsToTenant`), `ChallengeMapper` (row ↔ aggregate; reconstruct state enum + VOs from strings, ADR-T16; persists `determination_id` set at `adjudicate`).
- **Resolution-key finding:** `ElectionCorrectionApplied` carries **no `challengeId`**, so `ResolveChallengeHandler` must locate the Challenge **by `determinationId`** → repository needs `findByDeterminationId(DeterminationId): ?Challenge`. `AdjudicateChallengeHandler` locates by `challengeId`. (§9 F-3.)
- **Invariant (ARB):** `ChallengeRepository::findByDeterminationId()` is a **correlation capability only** — it is NOT an alternative aggregate identity. The Challenge's sole identity remains `ChallengeId`; `DeterminationId` is merely a lookup index (a Challenge holds *one* determination once adjudicated). This will be documented on the repository method itself.
- Optimistic concurrency: interface mentions it, aggregate has no version field → recommend **defer** (single-writer per inbox-serialized reaction) — §9 F-4.

**Architectural invariant (ARB — distinguishes PB-005 from PB-004):** *Messaging correlates to an **existing** Challenge aggregate; messaging never reconstructs or creates Challenge identity.* The handler looks up an already-owned Challenge (by `challengeId` or `determinationId`) and reacts; an unresolvable correlation is a business/park/dead-letter condition (§9 F-1), never an implicit "provision a Challenge." (Contrast PB-004, where the reacting context had no aggregate of its own and reconstructed one from a legacy source + reaction state.)

## 7. Infrastructure reuse (justified per pattern; NOT by precedent)
| Pattern | Reuse? | Why it fits / what differs | Evidence |
|---|---|---|---|
| Outbox adapter (`match(true)`) + hydrators | **Reuse** | Same mechanism; **two** events vs PB-004's one | Adjudication + Election |
| Inbox handler(s) + registry wiring in `ContestationServiceProvider` (+ `config/app.php`) | **Reuse** | Same consume mechanism; **two** handlers | Election provider |
| Inbox-inherited atomicity (no TransactionManager) | **Reuse** | One aggregate + its outbox rows in the inbox txn (ADR-T1) | PB-004 rollback test |
| `BelongsToTenant`, injected `ClockInterface` | **Reuse** | Tenant-free domain; application-time events | ER-03/04 |
| **ACL / existence port / multi-source reconstruction / Strangler** | **DO NOT reuse** | **Contestation OWNS the Challenge** — no legacy source, single-source repo | §1 |
| Idempotency **ledger** | **DO NOT reuse** | Challenge has intrinsic **state** → idempotency via inbox dedupe + state guards (§9 F-1) | Discovery |
| Parking | **Reuse (new business use)** | Out-of-order is temporally premature, not invalid | §5 |

## 8. Behavioural RED Matrix (write RED from this)
| # | Scenario | Level | Expected |
|---|---|---|---|
| 1 | `DeterminationIssued` Upheld, Challenge Routed | unit+feature | `adjudicate()` → Adjudicated; emits `ChallengeAdjudicated`; 1 outbox row |
| 2 | `DeterminationIssued` Dismissed | feature | `adjudicate()`+`resolve()` one txn; emits both events |
| 3 | `ElectionCorrectionApplied`, Challenge Adjudicated (found by determinationId) | feature | `resolve()` → Resolved; emits `ChallengeResolved` |
| 4 | `ElectionCorrectionApplied` before adjudication (Routed) | feature | `CausalPreconditionMissing` → parked; no event |
| 5 | atomic + rollback | feature | handler failure mid-consume → 0 outbox rows; aggregate unchanged |
| 6 | both handlers resolve from the registry | feature | `handlerFor('Contestation', <type>)` returns each |
| 7 | hydrator round-trip (both events) | unit | payload ↔ domain event preserved; unsupported schema_version rejected |
| 8 | timestamps = injected clock | unit/feature | event `occurredAt` = clock, not inbound event time |
| 9 | tenant scoping | feature | another org sees no Challenge |
| 10 | replay categories (per §9 F-1, once ruled) | unit/feature | behaviour per approved classification |

## 9. Open architectural findings requiring ARB review
**F-1 (Q3 — replay classification; ARB-approved with terminology refinement).** Four categories. **Boundary (ARB):** the *Domain* expresses **business conditions**; the *Inbox / Messaging Platform* translates them into operational outcomes (park / dead-letter / incident). The Domain never names a messaging outcome.

| Category | Business condition (Domain) | Handler → Inbox marker | Operational outcome (platform) |
|---|---|---|---|
| Transport duplicate (same `event_id`) | — (never reaches the aggregate) | platform dedupe on `(event_id, consumer_context)` | `Duplicate` |
| Semantic identical replay (diff `event_id`, **same** determination, already in/past target state) | "this fact is already recorded" | `IdempotentReplay` | Processed, no-op |
| Temporally premature (`resolve` before `adjudicate`) | Challenge not yet `Adjudicated` | `CausalPreconditionMissing` | Parked + re-driven |
| **Conflicting determination** (**different** `determinationId` on an already-adjudicated Challenge) | **business-invariant violation** — one binding determination per challenge (domain raises e.g. `ConflictingDetermination`) | handler maps → permanent classification | dead-letter + incident |
| **Impossible replay** (illegal terminal transition, no matching prior fact) | `IllegalChallengeTransition` (existing domain exception) | handler maps → permanent classification | dead-letter + incident |

So: the **aggregate** raises *business-semantic* exceptions (`ConflictingDetermination`, `IllegalChallengeTransition`); the **Application handler** translates each business condition into the Inbox contract marker; the **Inbox/Platform** decides the operational action. Requires the handler to compare incoming vs recorded `determinationId`. *Approved (terminology refined).*

**F-2 (Q7 — `ChallengeResolved.resolution`; deeper analysis per ARB — domain event NOT modified pending approval).** The catalog specifies `resolution` (Upheld/Dismissed); the domain event lacks it. The ARB's decisive test is *who needs it, inside vs outside the bounded context*:

| Question | Answer | Evidence |
|---|---|---|
| Does the **aggregate's behaviour** need `resolution`? | **No** | `Resolved` is terminal; no method branches on Upheld vs Dismissed after `resolve()`. |
| Does **reconstitution / replay** need it? | **No** | Rehydration needs `state` + `determinationId`; no operation consumes the outcome. |
| Is it part of **Contestation's ubiquitous language**? | **Plausibly yes** | "A challenge is resolved *as upheld or dismissed*" — the outcome is arguably intrinsic to the challenge's own conclusion, not merely downstream reporting. This is the genuine (and only) argument for a domain change; it is a UL judgment the ARB owns. |
| Required **only** by downstream integration (Audit, AdjudicationService)? | **Also yes** | Those consumers clearly want the outcome. |

**Implementation reality:** the outbox adapter maps the **domain event** to the published payload; it has no other source for `resolution`. So "enrich only the Integration Event" is architecturally awkward here — the adapter would need the outcome from outside the domain event (breaking the aggregate-records-events → adapter-maps pattern). Integration-only enrichment is therefore only clean if we accept a handler→adapter side-channel.

**Recommendation (rejected by ARB) → ARB RULING (binding):** **Keep the Domain Event minimal; enrich only the published Integration Event.** My analysis's own findings — aggregate behaviour: no; reconstitution: no; replay: no — outweigh the convenience argument; only downstream consumers need `resolution`, so it does **not** belong in the Domain Event. This preserves the Domain-Event ≠ Integration-Event separation established in PB-004.
- `ChallengeResolved` **domain event** stays `(ChallengeId, DeterminationId, occurredAt)` — **unchanged**. `resolve(DeterminationId, $at)` unchanged.
- `resolution` (Upheld/Dismissed) is added **only** to the published **Integration Event** payload.
- **Mechanism (no hidden coupling):** the Application reaction handler *derives* `resolution` from the reaction context — Upheld ⇐ triggered by `ElectionCorrectionApplied`; Dismissed ⇐ triggered by `DeterminationIssued` `outcome=Dismissed` — and supplies it to the outbox adapter **explicitly at publish time** (an explicit, typed publication argument), never by expanding the domain event and never via ambient state. The adapter stamps `resolution` onto the published `ChallengeResolved` payload. The exact publish-seam signature is settled in RED (5C); the principle is fixed: **enrichment is explicit and application-supplied, the Domain stays pure.**

**F-3 (resolution key).** `ResolveChallengeHandler` finds the Challenge by `determinationId` (no `challengeId` on `ElectionCorrectionApplied`) → add `ChallengeRepository::findByDeterminationId`. Confirm intended correlation key.

**F-4 (optimistic concurrency).** No version field though the interface mentions it → recommend **defer** (single-writer reaction). Confirm.

**F-5 (documentation corrections, post-IDD per Q6).** Separate proposal to correct the `DeterminationIssued` consumer row (add Contestation) and add the `ChallengeAdjudicated` payload contract — versioned re-issue + ADR, after IDD acceptance. Not during the IDD.

## Verification (for the eventual GREEN, not now)
Unit (aggregate `reconstitute` + handlers, in-memory doubles) → Feature (real inbox `consume`, outbox rows, parking, tenant) → greenfield PHPStan → Architecture suite (Contestation already scanned) → regression. Reuse the PB-004 Feature harness discipline (unique org per test; tenant-scoped assertions).

## 10. Slicing (PB-005 is XL — sub-slices mirror PB-004, each with its own RED→GREEN→stop)
- **5A — Domain + Application reaction (unit; no DB):** `Challenge::reconstitute(...)`; `ConflictingDetermination` domain exception; `AdjudicateChallengeHandler` + `ResolveChallengeHandler` over in-memory `ChallengeRepository` + outbox doubles; Dismissed short-circuit; replay classification (F-1) + parking + application-time clock. Domain event `ChallengeResolved` unchanged (F-2 ruling).
- **5B — Infrastructure persistence:** `EloquentChallengeRepository` (+ `findByDeterminationId`), `ChallengeModel` (`BelongsToTenant`), `ChallengeMapper`, `challenges` migration, `ContestationServiceProvider` (repo binding) + `config/app.php`. Feature/DB.
- **5C — Messaging:** outbox adapter (`ChallengeAdjudicated`; `ChallengeResolved` **with the explicit `resolution` enrichment seam**, F-2), hydrators, inbox-registry wiring for **both** handlers. Feature/DB (atomic, parking end-to-end, dedupe).
- **5D — Architecture qualification:** confirm Contestation (now complete hexagonal) still passes `GreenfieldCoreArchitectureTest` (already in `CONTEXTS`; ownership `Contestation⇒['Challenge']` already present); full regression; docs; Completion Review → closes PB-005.
- **F-5 documentation corrections** — separate proposal after 5D.

## STATUS: IDD FROZEN (ARB-approved 2026-07-08). Proceed to Phase 3 (RED), slice 5A first.
No architectural redesign during implementation unless RED reveals the approved design is insufficient.

# Push B Architecture Blueprint — v1.0

**Status:** ✅ **FROZEN v1.0 (2026-07-06)** — Architecture Review Gate passed: **Approved with minor recommendations, all incorporated** (§20 gate record). This document is now IMMUTABLE — any change requires Blueprint v1.1; history is never edited in place.
**Governance rule (binding):** **No Pull Request may be merged unless every changed class can be traced to one or more sections of this blueprint, the applicable ADR(s), and a Traceability Matrix row.** During implementation, every new class, event, handler, repository, migration, and test must include traceability references (Blueprint §, ADR-T, CI/BI). If any implementation cannot be traced to this approved blueprint, STOP and request an architecture review — do not introduce a new design decision. Per-PR conformance is checked against `Architecture_Review_Checklist.md`.
**Scope:** The complete correction loop: `ChallengeRaised → DeterminationIssued → Election reacts → ElectionCorrectionApplied → Challenge.resolve() → ChallengeResolved`.
**Date:** 2026-07-06 · **Grounding:** Canonical Event Catalog v1.0 · Round50-03/05/07/08 · ADR-T1/T3/T4/T5/T8/T11/T14/T16/T17/T20 · verified code inventory (2026-07-06)
**Companion deliverable:** `PushB_Decision_Log.md` — every implementation decision made during Push B records {Decision · Reason · Related ADR · Alternative rejected}. Started empty with this blueprint; no undocumented design drift.
**Push boundary note:** `Architecture_Release_1.1_Readiness_Review.md` scopes Push B through `ChallengeResolved`; `Round50-07` labels `Adjudicated→Resolved` as "Push C". Per Chief Architect directive (2026-07-06), Push B covers the FULL loop; the 50-07 label is superseded. Flagged for Architecture Review Gate confirmation.

---

## 0. Gap Register — what this blueprint exists to close

Verified against code on 2026-07-06. Each gap maps to the section that closes it.

| # | Gap | Evidence | Closed by |
|---|-----|----------|-----------|
| G-1 | Outbox relay cannot deliver loop events: `OutboxEventProcessor::hydrateDomainEvent()` hardcodes `match('FeePaid')`; a `DeterminationIssued` row throws on every attempt and ends `failed` | `app/Contexts/Shared/Infrastructure/Outbox/OutboxEventProcessor.php` | §5 (Relay Registry) |
| G-2 | No Inbox exists anywhere — no dedupe store, no consumer idempotency (ADR-T4 unimplemented) | grep `Inbox` in `app/` = 0 files | §5 (Inbox) |
| G-3 | No Election reaction: no `ApplyCorrection` handler, no `ElectionCorrectionApplied` event class (docblock mentions only) | grep outside Adjudication = docblocks only | §1 step 3b, §2 |
| G-4 | Contestation has NO Application/Infrastructure layer: `ChallengeRepository` port unbound; no `EloquentChallengeRepository`, no `challenges` migration, no `ContestationServiceProvider`, no application service | `app/Contexts/Contestation/` contains `Domain/` only | §4 (T1/T4/T5), §16 step 7 |
| G-5 | Cross-context VO identity: Contestation `DeterminationId` ≠ Adjudication `DeterminationId`; Adjudication `ChallengeRef` ≠ Contestation `ChallengeId` — consumers must translate | two distinct VO classes per name | §3 (translation rule) |

What already EXISTS and is NOT rebuilt: Challenge aggregate (all transitions incl. `adjudicate()`/`resolve()`, unit-tested), Determination aggregate + full Adjudication write side (Push A, commit 78aab60d7), `outbox_events` table + `outbox:process` scheduler, `OutboxEventAdapter` (producer side).

---

## 1. Constitutional and Business Invariants

Separated per ARB review (2026-07-06): **constitutional invariants almost never change** — weakening one is a constitutional incident, not a design choice. **Business invariants may evolve** — changing one requires an ADR, not a constitutional amendment.

### 1.A Constitutional Invariants (permanent)

| CI | Invariant |
|----|-----------|
| **CI-1** | Every Challenge MUST either be dismissed/lapsed OR produce a binding Determination. No challenge disappears without a terminal legal outcome. |
| **CI-3** | Election correction must NEVER modify anonymous votes. Only election state may change. Corrections are forward-only (`ContainedOnly` bound; no un-casting; ADR-T8/VO-1). |
| **CI-4** | Challenge resolution must NEVER re-open a finalized Determination. `Resolved` consumes the ruling; it cannot amend it. |
| **CI-5** | No consumer may infer voter identity from any event payload. Opaque/hashed refs only; zero voter↔vote linkage (Q7, ADR-T11 — always-invariant, never "eventual"). |

*(CI-2 was reclassified by the ARB into two distinct BUSINESS invariants below; the CI number is retired, not reused.)*

### 1.B Business Invariants (may evolve via ADR)

| BI | Invariant | Current realization |
|----|-----------|---------------------|
| **BI-1** | Every Challenge may produce **at most one** binding Determination. | `DeterminationAlreadyIssued` service precondition |
| **BI-2** | Every Determination must belong to **exactly one** Challenge. | `UNIQUE(organisation_id, challenge_ref)` |

These are two different invariants (cardinality from the Challenge side vs. ownership from the Determination side); the constraint column is merely today's realization — the invariant is the contract.

---

## 2. End-to-End Sequence

```text
── Phase 1: Challenge intake (Contestation) ────────────────────────────────
  Actor (standing S-1/S-2/S-3)
    → RaiseChallenge command
    → [TXN T1a] Challenge::raise() → state Raised → ChallengeRaised → outbox
    → [TXN T1b] Challenge::admit() → Admitted → ChallengeAdmitted → outbox
         (or dismiss() → Dismissed ▣ / lapse() → Lapsed ▣ — loop ends, CI-1 satisfied)
    → [TXN T1c] Challenge::route(jurisdiction) → Routed → ChallengeRouted → outbox

── Phase 2: Adjudication rules (EXISTS — Push A) ───────────────────────────
  ChallengeRouted ⇒ relay ⇒ Adjudication
    → IssueDetermination command (AdjudicationService)
         · loads Challenge READ-ONLY, verifies canProceedToAdjudication()   [ADR-T14]
         · LegitimacyDecision domain service produces legitimacy input      [ADR-T17]
    → [TXN T2] Determination::prepare()->issue() → Issued
         → DeterminationIssued → outbox                                     [EXISTS]

── Phase 3: The fork on outcome ────────────────────────────────────────────
  DeterminationIssued ⇒ relay ⇒ TWO consumers (independent inboxes):

  (3a) Contestation inbox — ALWAYS, regardless of outcome:
    → [TXN T4] Challenge::adjudicate(determinationId)
         → Adjudicated → ChallengeAdjudicated → outbox                      [ADR-T20]

    outcome = Dismissed:  no correction is owed; required consequences = none
    → [TXN T5'] Challenge::resolve(determinationId)
         → Resolved ▣ → ChallengeResolved → outbox
         (same handler invocation, sequential local transactions T4 then T5';
          resolve()'s guard requires Adjudicated — satisfied by T4)

  (3b) Election/Lifecycle inbox — ONLY when outcome = Upheld:
    → ApplyCorrection command
         guards: CorrectionTypeDecision + ContainedCorrectionInvariant      [50-07, CI-3]
    → [TXN T3] Election correction state write
         → ElectionCorrectionApplied {correctionType} → outbox              [NEW]
    outcome = Dismissed: inbox records the event (dedupe row), handler no-op.
         Grounding: Round50-03 — "Upheld drives ElectionCorrectionApplied;
         Dismissed → no correction."   ← DESIGN CLARIFICATION, review-gate item

── Phase 4: Operational completion (Upheld path only) ──────────────────────
  ElectionCorrectionApplied ⇒ relay ⇒ Contestation inbox
    → [TXN T5] Challenge::resolve(determinationId)
         → Resolved ▣ → ChallengeResolved → outbox

── Terminal states ──────────────────────────────────────────────────────────
  Both paths end: Challenge = Resolved ▣ · Determination = Issued (→ Final)
  Upheld additionally: Election correction recorded, forward-only            [ADR-T8]
```

Rules visible in the diagram: Adjudication never writes Challenge (ADR-T14). Election never mutates Challenge; Adjudication never mutates Election (Round50-03 Option A). No compensation, no un-casting (CI-3). Contestation never waits synchronously on Election (ADR-T20).

---

## 3. Event Ownership Table

All events carry the 8-field envelope: `EventId · EventType · AggregateId · AggregateVersion · OccurredAt · CorrelationId · CausationId · SchemaVersion`. Single producer per event (fitness AT-EVT-001). Audit consumes all.

| Event | v | Sole producer | Payload (v1) | Consumers | Status |
|-------|---|---------------|--------------|-----------|--------|
| `ChallengeRaised` | 1 | Contestation | challengeId · raiserStandingRef · targetRef · submittedContentRef · window · raisedAt | Adjudication · Audit | class EXISTS |
| `ChallengeAdmitted` / `ChallengeDismissed` | 1 | Contestation | challengeId · verdict/reason · at | Adjudication · Audit | classes EXIST |
| `ChallengeRouted` | 1 | Contestation | challengeId · routedTo · at | Adjudication · Audit | class EXISTS |
| `ChallengeAdjudicated` | 1 | Contestation | challengeId · determinationId · at | Audit | class EXISTS (ADR-T20) |
| `ChallengeResolved` | 1 | Contestation | challengeId · resolution · determinationId · at | Audit | class EXISTS, never produced at runtime |
| `DeterminationIssued` | 1 | Adjudication | determinationId · challengeRef · outcome · legitimacy · reason · evidenceEnvelopeRef · issuedByAuthority · jurisdiction · occurredAt | Election/Lifecycle · Contestation · Legitimacy(proj) · Audit — **NOT Voting** | EXISTS + outbox-wired |
| `ElectionCorrectionApplied` | 1 | Election/Lifecycle | electionId · determinationId · correctionType{ReRun\|Invalidate\|Accept\|ContainedOnly} · appliedAt | Contestation · Legitimacy(proj) · Audit | **MISSING — build (G-3)** |

Anonymity (CI-5): no payload carries voter↔vote linkage; opaque/hashed refs only. Q7 fitness test guards this on every new payload.

---

## 4. Aggregate Interaction Table

| Step | Writes (exactly one root) | Reads | Never touches |
|------|---------------------------|-------|---------------|
| RaiseChallenge / admit / route | Challenge | — | Determination, Election |
| IssueDetermination | Determination | Challenge (READ-ONLY, `canProceedToAdjudication()`) [ADR-T14] · EvidenceEnvelope (ref) | Challenge write, Election |
| ApplyCorrection | Election | DeterminationIssued payload (from inbox) | Challenge, Determination |
| AdjudicateChallenge (reaction) | Challenge | DeterminationIssued payload | Determination, Election |
| ResolveChallenge (reaction) | Challenge | ElectionCorrectionApplied payload | Determination, Election |

**VO translation rule (closes G-5):** cross-context identity crosses ONLY as the envelope/payload string. Each consumer reconstructs its own local VO: Contestation builds `Contestation\...\DeterminationId::fromString(payload.determinationId)`; Election builds its own local ref. No context imports another context's VO class (Deptrac/fitness enforced; ADR-T16 local opaque VOs).

---

## 5. Transaction Boundaries

Rule (ADR-T1): **one transaction = one aggregate root + its outbox row(s)**. The loop is causally-linked transactions — never one distributed transaction, never a saga (ADR-T8).

| Txn | Context | Aggregate write | Outbox append (same txn) | Status |
|-----|---------|-----------------|--------------------------|--------|
| T1 (a/b/c) | Contestation | Challenge raise/admit/route | ChallengeRaised/Admitted/Routed | **MISSING (G-4)** |
| T2 | Adjudication | Determination issue | DeterminationIssued | EXISTS (Push A) |
| T3 | Election/Lifecycle | correction state | ElectionCorrectionApplied | **MISSING (G-3)** |
| T4 | Contestation | Challenge adjudicate | ChallengeAdjudicated | **MISSING** |
| T5 / T5' | Contestation | Challenge resolve | ChallengeResolved | **MISSING** |

Fixed internal ordering per 50-07 (fitness AT-TXN-001): `guard → state mutation (×1) → event creation → outbox append → COMMIT → async dispatch`. Guard failure aborts before any mutation. The Dismissed path runs T4 and T5' as two sequential local transactions in one handler invocation (same aggregate, two transitions — never merged into one txn).

Inbox rows commit **inside** the consumer's transaction (T3/T4/T5): dedupe-insert + aggregate write + outbox append are atomic — a crash before COMMIT leaves no trace; redelivery is then safe.

---

## 6. Outbox / Inbox Boundaries

### Outbox (producer side — per context)
- Producers append typed rows to `outbox_events` in the aggregate's transaction via a context-local `EventOutbox` port adapter (pattern: Adjudication's `OutboxEventAdapter` — explicit mapping, no reflection). Contestation and Election each get their own adapter. The reflection-based shared `OutboxWriter` is NOT used for loop events.

### Relay Registry (fixes G-1)
- Replace `OutboxEventProcessor::hydrateDomainEvent()`'s hardcoded `match` with an **event-type registry**: `event_type string → hydrator`, entries registered by each context's service provider. Unknown types → dead-letter with `UNREGISTERED_EVENT_TYPE` (never silent, never infinite retry).
- Registry entries required for Push B: `DeterminationIssued`, `ElectionCorrectionApplied`, `ChallengeRaised/Admitted/Dismissed/Routed/Adjudicated/Resolved`. `FeePaid` migrates into the registry unchanged.
- Delivery remains at-least-once via existing `outbox:process` (every minute). **Retry policy is configurable (config key, operational ownership); default = 5 attempts with backoff.** The POLICY (bounded retries → dead-letter) is architectural and frozen; the NUMBERS are operational and tunable without an ADR.

### Inbox (fixes G-2 — ADR-T4)
- New table `inbox_events`: `event_id · consumer_context · event_type · payload · status{processed|parked|dead} · parked_until · processed_at · organisation_id`, uniqueness `(event_id, consumer_context)` — the same event may be legitimately consumed by BOTH Election and Contestation.
- Consumer wrapper (one shared implementation, used by every handler): begin txn → `INSERT inbox_events` (duplicate key → already processed → commit no-op & ack) → invoke handler (aggregate write + outbox) → mark processed → COMMIT.
- **Park rule** (out-of-order, 50-05 §3): if the handler's causal precondition is absent, mark `parked` with `parked_until`; an inbox re-drive command retries parked rows; park-timeout → dead-letter.
- Inboxes required: **Election/Lifecycle** (consumes DeterminationIssued) and **Contestation** (consumes DeterminationIssued + ElectionCorrectionApplied).

---

## 7. Failure Model

Covers retries, recovery, timeout, parking, dead-letter, ordering, and duplicates — the complete failure model of Push B.

| # | Scenario | Detection | Behaviour | Terminal state |
|---|----------|-----------|-----------|----------------|
| F1 | Relay delivery fails (transient) | processor exception | retry per configurable policy (default 5, backoff) | processed or dead-letter |
| F2 | Unregistered event type | registry miss | immediate dead-letter, no retry | dead-letter (`UNREGISTERED_EVENT_TYPE`) |
| F3 | Duplicate delivery | inbox `(event_id, consumer_context)` collision | drop silently, ack | processed (first delivery's result stands) |
| F4 | Out-of-order: `ElectionCorrectionApplied` before T4 processed | resolve() guard precondition absent | **park**, re-drive later | processed after cause arrives; dead-letter on park-timeout |
| F5 | Handler crash mid-transaction | txn rollback | inbox row rolled back too → redelivery retries cleanly | processed on retry |
| F6 | `DeterminationIssued` for unknown challenge | Contestation handler lookup miss | park (row may lag) → dead-letter on timeout; operator alert | dead-letter |
| F7 | Correction for unknown determination | Election handler lookup miss | same as F6 | dead-letter |
| F8 | Guard rejection — idempotent replay (e.g. already-Adjudicated receives second adjudicate) | domain exception classified "already done" | mark processed, log | processed |
| F9 | Guard rejection — constitutional violation (e.g. correction on archived election; anything touching CI-1..CI-5) | domain exception classified "permanent" | dead-letter + operator alert + governance escalation; NEVER auto-retry | dead-letter |

No compensating transactions anywhere (ADR-T8): a failure after `ElectionCorrectionApplied` commits cannot "un-correct" — recovery is forward-only.

### 7.1 Recovery Policy (closing the operational lifecycle)

Retry, parking, and dead-letter describe how failure is CONTAINED; recovery describes how the loop RESUMES. Fixed sequence:

```text
Operator fixes root cause (register hydrator / repair data / deploy fix)
  → Re-drive:  dead-letter or parked rows re-enqueued (relay re-drive command / inbox re-drive command)
  → Inbox:     dedupe guarantees re-driven rows are safe even if partially processed before
  → Resume:    loop continues from exactly where it stopped — never restarted from the top
```

Rules: recovery NEVER bypasses the inbox (no manual handler invocation, no direct aggregate mutation); recovery NEVER re-emits an already-committed event (the stored row is re-driven, not re-created — EventId is preserved); a recovery that would require violating a CI is not a recovery — it is a constitutional incident and escalates to governance.

---

## 8. Idempotency Strategy

Three layers; effectively-once = at-least-once delivery + idempotent consumption. Never assume exactly-once.

| Layer | Mechanism | Guards against |
|-------|-----------|----------------|
| Producer | `outbox_events.event_id` UNIQUE · `determinations UNIQUE(organisation_id, challenge_ref)` + `DeterminationAlreadyIssued` precondition (BI-1/BI-2) | double-issue, double-append |
| Consumer | inbox `(event_id, consumer_context)` dedupe inside handler txn | duplicate delivery |
| Aggregate | state-machine guards: illegal transitions throw with NO mutation and NO event (50-07 invariant) | replays that slip past dedupe |

**Handler exception classification (binding for all Push B handlers):**
- *Already-done* (`IllegalChallengeTransition` from a state at-or-past the target; duplicate-key on business uniqueness) → mark inbox row processed; no retry.
- *Transient* (DB deadlock, connection loss, lock timeout) → rethrow; relay/inbox retry with backoff.
- *Permanent* (unknown refs after park-timeout, constitutional guard rejection) → dead-letter + operator alert.

---

## 9. Integration Test Plan

| Test | Asserts |
|------|---------|
| IT-1 E2E Upheld | raise→admit→route→issue(Upheld)→relay→correction→relay→adjudicated→resolved; DB: challenge `Resolved`, determination `Issued`, correction row, ChallengeResolved in outbox; CorrelationId identical on all rows |
| IT-2 E2E Dismissed | issue(Dismissed) → Contestation adjudicates then resolves (T4+T5'); Election emits NOTHING; challenge `Resolved` (CI-1) |
| IT-3 Idempotency per consumer | deliver DeterminationIssued twice to each inbox → exactly one T3/T4 effect; second delivery = inbox drop |
| IT-4 Out-of-order | inject ElectionCorrectionApplied before T4 → row `parked`; process T4; re-drive → `Resolved` |
| IT-5 Relay registry | DeterminationIssued row processed (regression on G-1); unregistered type → dead-letter |
| IT-6 Failure injection | F5 crash mid-handler → clean redelivery; F9 permanent → dead-letter, no retry |
| IT-7 Fitness | AT-EVT-001 single-producer; AT-TXN-001 one root+outbox per txn; Q7/CI-5 zero voter↔vote linkage in all new payloads |
| IT-8 Observability | full CorrelationId chain queryable; CausationId links each event to its cause; TenantId present on every row |

Failure Model cases F1–F9 each get at least one test. RefreshDatabase per CLAUDE.md — no destructive commands against the dev DB.

---

## 10. Context Boundary Matrix (authoritative ownership)

| Context | Responsibility | Owns aggregate(s) | Publishes | Consumes | Relationship |
|---------|---------------|-------------------|-----------|----------|--------------|
| **Contestation** | legal lifecycle of challenges | Challenge | ChallengeRaised · Admitted · Dismissed · Routed · Adjudicated · Resolved | DeterminationIssued · ElectionCorrectionApplied | upstream of Adjudication (supplies routed challenges); downstream of Adjudication + Election (reacts) |
| **Adjudication** | legal ruling | Determination | DeterminationIssued | ChallengeRouted (trigger) · Challenge read-model (read-only) | downstream of Contestation; upstream of Election + Contestation reactions |
| **Election/Lifecycle** | operational correction of election state | Election (existing lifecycle) | ElectionCorrectionApplied | DeterminationIssued | downstream of Adjudication; upstream of Contestation resolution |
| **Audit** | immutable observation | — | none | ALL loop events | pure downstream; never publishes |

Drift rule: any change to a cell in this table = boundary change = BDR review + Architecture Review Gate. This table is the single source for the no-foreign-consumer fitness test.

### 10.1 Decision Authority (beyond producer/consumer)

Publishing an event says who SPEAKS; this table says who DECIDES. A context may consume an event without gaining any authority over the decision it records.

| Decision | Sole authority | Others may |
|----------|---------------|------------|
| Challenge admissibility (admit/dismiss/lapse) | **Contestation** | observe |
| Routing / jurisdiction assignment | **Contestation** | observe |
| Legitimacy + outcome of the ruling | **Adjudication** (LegitimacyDecision domain service, ADR-T17) | consume the ruling; never re-judge it |
| Correction type + application to election state | **Election/Lifecycle** (CorrectionTypeDecision policy) | consume the fact; never dictate the type |
| Operational completion of a challenge (resolve) | **Contestation** | trigger via events; never call resolve() |
| Audit history | **Audit** (append-only) | read |

---

## 11. Consistency Boundaries

| Interaction | Consistency |
|-------------|-------------|
| Within any single transaction T1–T5 (aggregate + its outbox rows + its inbox row) | **Strong / transactional** |
| Contestation → Adjudication (ChallengeRouted) | **Eventual** (outbox → relay → inbox) |
| Adjudication → Election (DeterminationIssued) | **Eventual** |
| Adjudication → Contestation (DeterminationIssued) | **Eventual** |
| Election → Contestation (ElectionCorrectionApplied) | **Eventual** |
| Anonymity (CI-5) | **Always-invariant** — holds at every point, never eventually |

**Binding rule: there is NO synchronous cross-context call anywhere in the loop.** No context blocks on another; Contestation never waits on Election (ADR-T20 — `Adjudicated` is legally final the moment T4 commits, regardless of when/whether T3 completes). Transactional boundaries end at each COMMIT; everything between COMMITs is event-driven eventual consistency. Read models may lag; the events are the legal record.

---

## 12. Event Versioning Policy

| Event | Version | Owner (= sole producer) | Evolution rule |
|-------|---------|------------------------|----------------|
| All 7 loop events (§3) | **v1** (`SchemaVersion: 1`) | per §3/§10 | below |

- **Additive change** (new optional field): bump `SchemaVersion`; consumers tolerate unknown fields; upcast test required (ADR-T5).
- **Breaking change**: new event name `EventName v2`; v1 and v2 coexist during migration; consumers upgrade independently; v1 retired only via Architecture Change Protocol.
- **Never** mutate the meaning of an emitted event or edit a stored row's semantics (events are the legal record).
- `DeterminationIssued` and `ElectionCorrectionApplied` are **Core-class** events: any evolution requires Architecture Review Gate **and** Security Review Gate sign-off (CI-5 re-check, ADR-T11).
- Catalog v1.0 is the registry of record; this blueprint adds no event names beyond it.

---

## 13. Retry & Failure Ownership Matrix

| Failure class | Kind | Responsible component | Retry owner & policy | Dead-letter owner | Operator duty |
|---------------|------|----------------------|----------------------|-------------------|---------------|
| Outbox delivery failure (F1) | infrastructure | Relay (`OutboxEventProcessor`) | Relay — configurable, default 5 attempts + backoff | Relay writes; **Operations** owns queue | triage dead-letter queue; re-drive after fix |
| Unregistered event type (F2) | infrastructure (config) | Relay registry | none — immediate dead-letter | Operations | register hydrator, re-drive |
| Duplicate delivery (F3) | infrastructure | Consumer inbox | n/a (drop) | n/a | none |
| Out-of-order (F4) | infrastructure (timing) | Consumer inbox | Inbox re-drive command — until `parked_until` timeout (configurable) | Operations | investigate stuck parks |
| Handler crash / transient (F5) | infrastructure | Consumer handler txn | Relay redelivery (same policy as F1) | Operations | none unless recurring |
| Unknown refs (F6/F7) | business (timing) → permanent | Consuming context handler | park first; NO blind retry after timeout | Operations + owning-context team | data investigation |
| Already-done guard (F8) | business (idempotent) | Consuming context handler | none — mark processed | n/a | none |
| Constitutional guard rejection (F9) | business (permanent) | Consuming context handler | **never auto-retried** | Operations + Governance escalation | mandatory review — potential constitutional incident (CI-1..CI-5) |
| Replay/verification | capability | Replay capability (existing) | n/a | n/a | audit support |

Principles: **infrastructure failures are retried by infrastructure; business failures are decided by the owning context; nothing business-permanent is ever retried blindly. Retry POLICIES are architectural and frozen; retry NUMBERS are operational configuration.**

---

## 14. Observability

Identifier set — the first five are Catalog v1.0 envelope fields; the tenant identifiers are payload/row-level and REQUIRED on every event, outbox row, inbox row, and dead-letter row:

- **EventId** — UUID, unique per event; the idempotency key (outbox UNIQUE, inbox key).
- **CorrelationId** — minted ONCE at `ChallengeRaised` (= the loop's identity); **copied unchanged** into every subsequent event of that loop. One challenge = one CorrelationId, end to end.
- **CausationId** — the `EventId` of the event that directly caused this one. Commands triggered by inbox delivery carry the consumed event's EventId; the resulting event's CausationId = that EventId.
- **AggregateId / AggregateVersion** — the written root; version per ADR-T18 status (postponed for Determination; uniqueness constraints substitute).
- **TenantId / OrganisationId** — propagated through the entire chain (already NOT NULL on `outbox_events`; equally required on `inbox_events` and every event payload's scoping ref). Incident investigation filters by tenant FIRST.
- **ElectionId** — carried wherever the loop touches an election (`targetRef`, `ElectionCorrectionApplied.electionId`) so an incident can be scoped to one election without joining through aggregates.

Propagation chain (Upheld path):

```text
ChallengeRaised        {corr=X, caus=∅,                    tenant=T, election=E}
  → ChallengeRouted    {corr=X, caus=ChallengeRaised.id,   tenant=T, election=E}
  → DeterminationIssued{corr=X, caus=ChallengeRouted.id,   tenant=T, election=E}
  → ElectionCorrectionApplied {corr=X, caus=DeterminationIssued.id, tenant=T, election=E}
  → ChallengeAdjudicated      {corr=X, caus=DeterminationIssued.id, tenant=T, election=E}
  → ChallengeResolved         {corr=X, caus=ElectionCorrectionApplied.id, tenant=T, election=E}
```

Operational queries this must support: (1) `WHERE correlation_id = X ORDER BY occurred_at` = the complete legal history of one challenge across all contexts, outbox + inbox + dead-letter included; (2) causation walk = why did this event happen; (3) `WHERE organisation_id = T AND election_id = E` = every correction-loop action for one election; (4) dead-letter rows retain the full envelope so a dead loop is diagnosable without code access. Metrics feed (Playbook dashboard): determination latency, corrections applied, duplicate-event rate, dead-letter depth per event_type.

---

## 15. Responsibility Matrix

Exactly one primary responsibility per component. Overlap = design error.

| Component | Single responsibility | Explicitly NOT responsible for |
|-----------|----------------------|-------------------------------|
| **Challenge** (aggregate) | legal lifecycle of a challenge | rulings, corrections, delivery |
| **Determination** (aggregate) | the legal ruling (issued once, final) | enforcing consequences (Round50-03) |
| **Election/Lifecycle** | operational correction of election state | legal judgments, challenge lifecycle |
| **Relay** (OutboxEventProcessor + registry) | delivery (at-least-once) | business decisions, dedupe |
| **Inbox** | idempotency + ordering (dedupe, park) | handling logic |
| **Projection** | reporting / read models | writes, decisions |
| **Repository** | persistence of exactly one aggregate root | cross-root queries, orchestration (ADR-T6) |
| **Application Service** | orchestration of one use case | business logic, constitutional reasoning (ADR-T17) |
| **LegitimacyDecision** (domain service) | constitutional legitimacy input to `issue()` | orchestration, persistence |

---

## 16. Implementation Order (post-approval — revised per Chief Architect)

```text
1. Blueprint v1.0  ← THIS DOCUMENT
2. Architecture Review (complete-document review against all frozen artifacts) ⛔ STOP
3. Freeze blueprint · open PushB_Decision_Log.md
4. Event Registry        events + hydrators registered per context
5. Relay Registry        OutboxEventProcessor registry refactor (G-1); FeePaid migrated; tests
6. Inbox                 inbox_events migration + consumer wrapper + park/re-drive (G-2)
7. Election Reaction     ElectionCorrectionApplied event + ApplyCorrection handler + guards (G-3)
8. Contestation Reaction Contestation infra (repo/migration/provider — G-4) + adjudicate/resolve handlers (T4/T5/T5')
9. Integration           IT-1 … IT-8
10. Merge Gate           Architecture Review Gate + Security Review Gate + F-1 Deptrac + F-2 Infection
```

Rationale for 4–6 before 7–8: relay + inbox ARE the messaging infrastructure; both reactions depend on them (Infrastructure → Business, not Business → Infrastructure). Contestation persistence (G-4) lands with its reaction step (8), where it is first needed at runtime.

---

## 17. Push B Success Criteria (ARB acceptance checklist)

Push B is complete ONLY if every box is checked — architecture success, not merely test success:

- ☐ every event has exactly one producer (AT-EVT-001)
- ☐ every event has at least one documented consumer (§10 matrix)
- ☐ every transaction owns exactly one aggregate root (AT-TXN-001)
- ☐ every async boundary documented (§11) and none replaced by a synchronous call
- ☐ replay succeeds (deterministic re-derivation from events)
- ☐ duplicate delivery succeeds (IT-3)
- ☐ ordering failures handled (IT-4 park/re-drive)
- ☐ constitutional invariants CI-1..CI-5 hold in every test path
- ☐ architecture tests green (131+ suite, incl. greenfield fitness)
- ☐ integration tests green (IT-1..IT-8)
- ☐ mutation tests green (Infection wired — F-2)
- ☐ PHPStan green (max level, greenfield contexts)
- ☐ Deptrac green (F-1 installed; boundaries per §10)
- ☐ no ADR violated; Decision Log complete (no undocumented drift)
- ☐ Traceability Matrix updated; Architecture + Security Review Gates signed

---

## 18. Security Considerations

Push B is election software; every loop component inherits these obligations:

| Concern | Push B treatment |
|---------|------------------|
| **Authentication** | Loop commands originate from authenticated constitutional actors only (standing classes S-1/S-2/S-3 for RaiseChallenge; authority roles for adjudication). Relay/inbox are internal — never exposed as endpoints. |
| **Authorization** | Standing verified at challenge reception (ADR-5 classes); adjudication authority via `IssuedByAuthority`; correction only by Election/Lifecycle's own handler. Decision Authority table (§10.1) is the authorization map. |
| **Integrity** | Events are the legal record: append-only, never mutated (§12); `EventId` uniqueness; hash + audit trail per ADR-T13 (crypto E2E verifiability explicitly DEFERRED — limitation recorded, not silently assumed). |
| **Confidentiality / anonymity** | CI-5: zero voter↔vote linkage in any payload, envelope, log line, or dead-letter row. Q7 fitness test enforces on every new event. |
| **Replay attacks** | Inbox dedupe rejects re-submitted EventIds; re-drive preserves EventId so legitimate recovery is distinguishable from injection; outbox rows are tenant-scoped (`organisation_id` NOT NULL). |
| **Message tampering** | Loop events never transit outside the application boundary in Push B (same DB, same process family). If a broker is ever introduced, that is a Blueprint v1.1 + Security Gate decision, not an implementation choice. |
| **Audit trail** | Audit consumes ALL loop events (§10); dead-letter rows retain full envelopes; CorrelationId chain (§14) makes the complete legal history of any challenge reconstructible. |

## 19. Performance Assumptions

Recorded so future developers know what this design was sized for — not SLAs.

| Assumption | Value |
|-----------|-------|
| Expected loop throughput | LOW — challenges are exceptional legal events, not transactional traffic (tens per election, not per second) |
| Relay cadence | `outbox:process` every minute; end-to-end loop latency target: **minutes, not seconds** — legal finality (T4) and operational completion (T5) tolerate scheduler-cadence delay by design (ADR-T20) |
| Ordering | NO global ordering assumed; causal ordering handled by park/re-drive (§6), not by broker guarantees |
| Eventual-consistency window | Read models and cross-context state may lag by up to a few relay cycles; anything reading the loop must tolerate this (§11) |
| Scaling posture | Single-DB polling outbox is sufficient at assumed volume; queue/broker introduction = Blueprint v1.1 decision |

## 20. Architecture Review Gate Record

**Held:** 2026-07-06 · **Reviewer:** Chief Architect (ARB role) · **Artifacts reviewed together:** PushB_Architecture_Blueprint.md · PushB_Decision_Log.md · Implementation_Traceability_Matrix.md

| Gate question | Answer |
|---------------|--------|
| Does the blueprint conform to all frozen ADRs and BDR decisions? | **YES** — encodes ADR-T1/T3/T4/T5/T8/T11/T14/T16/T17/T20, Round50-03/05/07/08, Catalog v1.0; re-decides nothing |
| Does every implementation step have traceability? | **YES** — Implementation Order §16 ↔ Gap Register §0 ↔ Traceability Matrix Push B rows |
| Are all remaining architectural decisions explicitly documented? | **YES** — Decision Log D-01..D-07; three review-gate items ruled (below) |
| Any remaining ambiguity that could lead two developers to implement different systems? | **NO** — Dismissed path, dedupe key, VO translation, exception classification, and identifier propagation are all specified |

**Ruled review-gate items:** (1) Push B/C boundary — full-loop scope CONFIRMED; (2) Dismissed-path — Contestation self-resolves, Election silent, `Accept` reserved for Upheld-but-accept rulings — CONFIRMED; (3) inbox dedupe key `(event_id, consumer_context)` — CONFIRMED (closes the 50-08 open question).

**Decision: APPROVED WITH MINOR RECOMMENDATIONS — all incorporated in this v1.0:** constitutional/business invariant separation + CI-2 split (§1), Decision Authority (§10.1), Recovery Policy (§7.1), Security (§18), Performance (§19), strengthened PR-traceability governance rule (header), Decision Log Impact column, Traceability Matrix maturity scale, `Architecture_Review_Checklist.md` created.
**Risk level:** LOW — remaining work is implementation of a fully specified architecture, not architectural discovery.
**Standing rule going forward:** new architectural discovery is EXCEPTIONAL. Every discussion either implements this approved design or produces a narrowly scoped ADR. Blueprint evolution only via v1.1 — never in-place edits.

---

*Push B Architecture Blueprint v1.0 — FROZEN 2026-07-06 (Architecture Review Gate PASSED, §20). Encodes (does not re-decide) Round50-03/05/07/08 and ADR-T1/T3/T4/T5/T8/T11/T14/T16/T17/T20. Closes gaps G-1…G-5. Constitutional Invariants CI-1/3/4/5 + Business Invariants BI-1/BI-2. Companions: PushB_Decision_Log.md · Architecture_Review_Checklist.md. Changes only via Blueprint v1.1.*

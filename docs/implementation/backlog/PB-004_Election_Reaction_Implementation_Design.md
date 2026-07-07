# PB-004 — Election Reaction — Implementation Design Document (IDD)

**Status:** DRAFT for ARB review (Process step 2) · **2026-07-07** · precedes RED.
**Traceability:** every element traces to `PB-004_Event_Storming.md` (ARB-approved with rulings) · Catalog 50-05/06 · ADR-T1/T5/T8/T11/T14/T16/T20 · D-01/D-02/D-06 · frozen Messaging Platform (`Messaging_Platform_Architecture.md`).
**ARR Gate (re: frozen Messaging Platform):** **consume-only** — registers an `InboxHandler` + emits via the existing Outbox; **no Shared change**. (One cross-context contract point re: Adjudication's event — DD-4 below — is *not* a Messaging change.)

---

## 1. Objective & scope
Introduce a **greenfield `Election` bounded context** (OQ-1) whose **minimum reaction behavior** is: consume `DeterminationIssued`, decide the `correctionType` (Election owns), and — for upholding rulings — apply a correction and emit `ElectionCorrectionApplied`; for **Dismissed**, do nothing but mark the message Processed (OQ-2).
**IN:** `app/Contexts/Election/{Domain,Application,Infrastructure}`; the reaction aggregate + decision policy + handler + registration + persistence + tests.
**OUT:** legacy Lifecycle engine changes; Contestation resolution (PB-005); any Shared/Messaging change; vote/ballot behavior.

## 2. Context structure (hexagonal; ADR-T-layered)
```
app/Contexts/Election/
  Domain/            (pure PHP — no framework)
    Election.php                 # reaction aggregate root
    ValueObjects/ ElectionId.php · DeterminationId.php · CorrectionType.php · Ruling.php
    Policy/ CorrectionTypeDecision.php     # ruling → correctionType | none  (Election owns, OQ-3)
    Events/ ElectionCorrectionApplied.php  # domain/integration event (readonly)
    Repository/ ElectionRepository.php     # interface
    Exception/ ...
  Application/
    Reaction/ ApplyDeterminationToElection.php   # command/handler orchestration (DTO in, no arrays)
  Infrastructure/
    Inbox/ DeterminationIssuedHandler.php  # implements Shared InboxHandler (the seam)
    Persistence/Eloquent/ ElectionCorrectionRecord.php + repository impl
    Providers/ ElectionReactionServiceProvider.php  # registers handler into InboxHandlerRegistry
```
Greenfield anonymity/purity fitness applies (see §9).

## 3. Domain model (traces to Event Storming)
- **Aggregate `Election`** (root, keyed by `ElectionId`): owns the set of applied corrections. Invariant: **at most one correction per `(ElectionId, DeterminationId)`** (OQ-4 domain idempotency). Method: `reactToDetermination(DeterminationId, Ruling, ClockInterface): void` → runs `CorrectionTypeDecision`; if a correction applies and not already applied, records it and **records** the domain event `ElectionCorrectionApplied`; if Dismissed → no state change, no event.
- **`CorrectionType`** enum: `ReRun | Invalidate | Accept | ContainedOnly` (50-05). `ContainedOnly` = anonymity-bounded (cannot un-cast; ADR-T8/T11).
- **`Ruling`** VO: `outcome` + `legitimacy` (from `DeterminationIssued`) — the inputs to the decision.
- **`CorrectionTypeDecision`** (pure policy, Election owns, OQ-3): maps `Ruling → CorrectionType | none`. **Dismissed → none** (D-02). The mapping table is authored in Election, referencing D-02 (`Accept` = Upheld-but-accept). *(Exact non-Dismissed rows confirmed against 50-06/D-02 in the decision's tests — DD-3.)*
- **`ElectionCorrectionApplied`** (readonly): `electionId · determinationId · correctionType · appliedAt`. **Anonymity:** no voter linkage — payload is election/determination ids + type + timestamp only (ADR-T11). ✔

## 4. Application / reaction flow (one transaction — the consistency boundary)
`DeterminationIssuedHandler` (implements Shared `InboxHandler`; `consumerContext()='Election'`, `eventTypes()=['DeterminationIssued']`):
1. Reconstruct **local** VOs from the `InboxMessage` payload/envelope strings (ADR-T16): `DeterminationId`, `Ruling`, and `ElectionId` (see **DD-4**).
2. Load/derive the `Election` aggregate via `ElectionRepository`.
3. `election.reactToDetermination(...)` → decision + (maybe) record correction + event.
4. Persist the aggregate (correction record) **and** enqueue `ElectionCorrectionApplied` to the **Outbox** — **in the same transaction** the Inbox handler runs inside (ADR-T1; the frozen platform's Inbox owns that transaction). Return normally → Inbox marks **Processed**.
- **Dismissed:** steps 1–2 run, decision = none, no write, no event → handler returns → **Processed** (OQ-2).
- **Classification markers:** if the aggregate/repository signals a genuinely-unprocessable determination (e.g. unknown election that can never resolve) → `PermanentInboxFailure`; transient infra errors propagate (rollback + retry). Out-of-order (e.g. election context not yet aware of the election) → `CausalPreconditionMissing` (park/redrive) — **only if** DD-4 resolution can be transiently unavailable.

## 5. Consistency & transaction boundary (explicit — ARB refinement)
```
Inbox Message ─► Election Aggregate ─► ElectionCorrectionApplied ─► Outbox   = ONE txn
beyond ─► relay ─► Contestation inbox (PB-005 resolve)                        = eventual
```

## 6. Idempotency (both levels — OQ-4)
- **Infra:** Messaging Inbox dedupe `(event_id, consumer_context)` — same delivered message not reprocessed.
- **Domain:** `UNIQUE(election_id, determination_id)` on the correction record + an aggregate guard — the **same determination can never yield two corrections**, even across replay/redrive. On a duplicate determination the aggregate no-ops (idempotent replay → Processed).

## 7. Invariants (from Event Storming)
Anonymity preserved (no un-casting; `ContainedOnly`) · forward-only, no saga (ADR-T8) · one aggregate + outbox per txn (ADR-T1) · idempotent at both levels · **Dismissed ⇒ no correction/event** · tenant/election scoping preserved (D-06).

## 8. Design decisions (DD)
- **DD-1 — Greenfield `Election` context** (OQ-1). Minimum reaction behavior only; no legacy coupling.
- **DD-2 — Aggregate owns idempotency** via `(election_id, determination_id)` uniqueness (OQ-4 domain half).
- **DD-3 — `CorrectionTypeDecision` owned by Election** (OQ-3); Dismissed→none (D-02). Full non-Dismissed mapping pinned by unit tests against 50-06/D-02.
- **DD-4 — electionId resolution (the one real modeling point).** `DeterminationIssued` (50-05) lists `determinationId · challengeId · outcome · legitimacy · reason · evidenceEnvelopeRef · issuedByAuthority · jurisdiction · finalizedAt` — **no explicit `electionId`**, yet D-06 mandates ElectionId propagation on every event. **Options:** (a) read electionId from the event **envelope** (D-06 propagation) if present on the inbox row/message; (b) resolve via a challenge→election correlation read model owned by Election; (c) add `electionId` to `DeterminationIssued` (an **Adjudication event version** per ADR-T5 → cross-context contract change → **ARB review**, *not* consume-only). **Recommendation:** (a) if the envelope already carries it (preferred, zero contract change); else (b). **This IDD does not assume (c).** → **ARB ruling requested (DD-4).**

## 9. Test plan (RED-first; TDD)
- **Unit (Domain, pure):** `CorrectionTypeDecision` — Dismissed→none; each upholding outcome→expected CorrectionType (pinned to D-02/50-06); `Election.reactToDetermination` idempotency (same determination twice → one correction); `ElectionCorrectionApplied` readonly + no linkage token.
- **Feature (RefreshDatabase):** handler consumes `DeterminationIssued` → correction recorded + `ElectionCorrectionApplied` in outbox, message Processed; **Dismissed** → no record/event, Processed; **duplicate delivery/redrive** → exactly one correction (both idempotency levels); unknown/transient resolution → park or permanent per DD-4.
- **Architecture (fitness):** Election context hexagonal purity (Domain framework-free); handler registered in `InboxHandlerRegistry`; **extend the constitutional anonymity scan (`GreenfieldCoreArchitectureTest`) to include `app/Contexts/Election`** (owner-hosts-the-guard — Election is a Core context; this is an Architecture Fitness addition, not a Messaging change).
- **Regression:** full Architecture Fitness suite + greenfield PHPStan + Inbox/Outbox suites green.

## 10. Commit slices (code C + docs DOC; mirrors PB-003 discipline)
- C1 Domain: VOs + `CorrectionType` + `Ruling` + `CorrectionTypeDecision` + `ElectionCorrectionApplied` (+ unit tests RED-first).
- C2 Persistence: `election_corrections` migration (`UNIQUE(election_id, determination_id)`) + record/repository.
- C3 Application/Infra: `DeterminationIssuedHandler` + `ApplyDeterminationToElection` + write+outbox in txn (+ feature tests).
- C4 Registration: `ElectionReactionServiceProvider` registers the handler (+ wiring test).
- C5 Architecture fitness: Election purity + anonymity-scan extension + handler-registered test.
- Each slice: RED evidence → GREEN → regression → DOC.

## 11. Exit criteria (ticket → Verified)
Greenfield Election context exists (hexagonal) · consumes `DeterminationIssued` unchanged from the frozen platform · correctionType decision owned by Election (Dismissed→none) · both idempotency levels enforced · anonymity preserved + scanned · one-txn boundary honored · all tests + PHPStan + architecture suite green · DD-4 resolved by ARB · Ready-for-PB-005 (Contestation reacts to `ElectionCorrectionApplied`).

---
**Awaiting ARB:** review of aggregate/invariant/txn boundaries + **ruling on DD-4** (electionId resolution). No code until IDD approved. PB-004 does not modify the frozen Messaging Platform.

# Round 50-05 — Event Catalogue (technical contracts)

**Phase III (Tactical Realization) · Built against Release 1.0 · Design → contract · 2026-06-26**
**Status:** 📑 EVENT CATALOGUE — full per-event **contracts**: payload · **delivery** · **ordering** · **replay** · **security** · **authorization**. `Event = Envelope(50-04 §1) + Payload + ContractMeta`. Becomes the event classes + fitness tests at implementation. Concise.

> **Binding:** every payload obeys **Anonymity (Q7)** — no voter↔vote linkage; hashed/opaque ids only. Every event carries `SchemaVersion` (TP-3). Producer = single (50-04 §2).

## 1. Payload contracts (Envelope + Payload, v1)

| Event | Producer | Payload (v1) | Anonymity note |
|-------|----------|--------------|----------------|
| **VoteAccepted** | Voting | `voteId · electionId · voteHash · receiptHash · dataChecksum · castAt` | **no voterId/userId** — hashes only |
| **EvidenceRecorded** | Evidence | `envelopeHash · electionId · hashedVoterId · evidenceSchemaVersion · frozenAt` | **hashed** voter id only (non-reversible) |
| **MandateGranted** | Appointment | `mandateId · holderId · scope · termRef · grantedAt` | holderId = official (not a voter) — OK |
| **MandateRevoked** | Appointment | `mandateId · reason · revokedAt` | — |
| **ChallengeRaised** | Contestation | `challengeId · raiserStandingRef · targetRef · submittedContentRef · window · raisedAt` | raiser = standing-holder (not a secret ballot) — OK |
| **ChallengeAdmitted / Dismissed** | Contestation | `challengeId · verdict · at` | — |
| **ChallengeRouted** | Contestation | `challengeId · routedTo · at` | — |
| **ChallengeResolved** | Contestation | `challengeId · resolution · determinationId · at` | — |
| **DeterminationIssued** | Adjudication | `determinationId · challengeId · outcome · legitimacy · reason · evidenceEnvelopeRef · issuedByAuthority · jurisdiction · finalizedAt` | references evidence by `envelopeHash` |
| **ElectionCorrectionApplied** | Election/Lifecycle | `electionId · determinationId · correctionType{ReRun\|Invalidate\|Accept\|ContainedOnly} · appliedAt` | `ContainedOnly` = anonymity-bounded (cannot un-cast) |

## 2. Delivery · Ordering · Replay · Security · Consumer-authorization

| Event | Delivery | Ordering dependency | Replay | Security | **Consumers (allowed)** · **NOT** |
|-------|----------|---------------------|--------|----------|-----------------------------------|
| **VoteAccepted** | at-least-once + idempotent → *effectively-once* | none (root cause) | projection-rebuild ✔ · re-execute �’✘ | **restricted** | Evidence · Results(proj) · Audit · **NOT** Adjudication, Contestation |
| **EvidenceRecorded** | at-least-once + idempotent | **after** `VoteAccepted` (CausationId) | rebuild ✔ · re-execute ✔ (deterministic, immutable) | **restricted** | Adjudication · Audit · **NOT** Voting |
| **MandateGranted/Revoked** | at-least-once + idempotent | granted **before** revoked (per `mandateId` version) | rebuild ✔ · re-execute ✘ | **internal** | Authorization · Audit · **NOT** Voting, Adjudication |
| **ChallengeRaised…Resolved** | at-least-once + idempotent | Raised→Admitted/Dismissed→Routed→Resolved (per `challengeId` version) | rebuild ✔ · re-execute ✘ | **internal** | AdjudicationService · Audit · **NOT** Voting |
| **DeterminationIssued** | at-least-once + idempotent → *effectively-once* | **after** `ChallengeRouted` (CausationId) | rebuild ✔ · **re-execute ✘** (issuing is a one-time act) | **restricted** | Election/Lifecycle · Legitimacy(proj) · Audit · **NOT** Voting |
| **ElectionCorrectionApplied** | at-least-once + idempotent | **after** `DeterminationIssued` (CausationId) | rebuild ✔ · **re-execute ✘** (side-effecting) | **internal** | Contestation(resolve) · Legitimacy(proj) · Audit · **NOT** Voting |

**Audit** consumes *all* (fire-and-forget; **secret**-cleared sink) and **replays for reconstruction only** (read-only).

## 3. Cross-cutting rules
- **Ordering model.** *Within* an aggregate: total order by `AggregateVersion`. *Across* aggregates: **no global order**; causal order via `CausationId`. The "after" dependencies above are **causal** — each downstream event is produced *only after consuming* its cause, so reversal cannot occur. Consumers still assert causal precondition (e.g. Adjudication ignores `EvidenceRecorded` it cannot correlate) and **buffer/park** an out-of-order arrival rather than fail.
- **Delivery.** Default = **at-least-once + idempotent (dedupe on `EventId`) = effectively-once**. *No* exactly-once transport assumed (none exists over the outbox). *At-most-once* is never used (would lose decisions).
- **Replay.** Two distinct meanings, never conflated: **projection-rebuild** (safe for *all* events — read models reconstructed from the log) vs **re-execution** (forbidden for Decision + side-effecting events — `DeterminationIssued`, `ElectionCorrectionApplied`, `MandateGranted/Revoked` are one-time acts). Evidence is the only family re-executable (deterministic + immutable) → feeds the Replay hypothesis.
- **Security classification.** `public < internal < restricted < secret`. `restricted` = vote-/ruling-related (need-to-know); `internal` = governance lifecycle; Audit sink alone holds the full (effectively `secret`) stream. No `public` events (a public results *projection* is derived downstream, not an event).
- **Authorization is two-sided:** single allowed **producer** (50-04 §2) **and** an explicit allowed-**consumer** set with named exclusions (col above). A fitness test asserts no context subscribes outside its allowed set (esp. **Voting never consumes** Adjudication/Contestation/Mandate events).
- **Refs not entities** (TP-1/TP-2); payloads carry references, never embedded aggregates.

## 4. Versioning (→ 50-04 §4 / KRG-aligned; expanded in Event Version Governance)
- Additive field → bump `SchemaVersion`; breaking → `EventName vN+1` (TP-3).
- **v1 and v2 coexist** during migration; **consumers upgrade independently**; producer emits the newest; **old version supported until all consumers migrate**, then retired via Architecture Change Protocol. (Full governance table deferred to Event Version Governance section of 50-06/implementation, per review.)

## 5. Fitness tests (from contracts)
- No event schema contains `userId`/voter PII or any field enabling voter↔vote reconstruction (Q7).
- Each event has exactly one producer; no consumer subscribes outside its allowed set (Voting-consumes-nothing-foreign).
- Every consumer is idempotent on `EventId`; out-of-order arrival is parked, not failed.
- No Decision/side-effecting event is re-executed on replay (only projection-rebuilt).

## 6. Open (unchanged)
- Replay event surface (`ReplaySessionOpened`/`…CertificationIssued`/`…DivergenceDetected`) — placement → confirm at implementation (BDR-06).
- Mandate `scope`/`holderId` shape depends on the **Mandate-vs-Committee** open question.

## Roadmap (reordered per review — State Machines before Repositories)
```
50-05 Event Catalogue (this) ✓
   → 50-06 Policy Catalogue         (Invariant/Decision/Authorization/Validation/Calculation)
   → 50-07 Aggregate State Machines (transitions/terminal/recovery/timeouts) — BEFORE repos
   → 50-08 Repository & Transaction Design
   → 50-09 Architecture Decision Verification (one-page checklist; not a design doc)
   → Implementation (greenfield Core: Contestation + Adjudication, TDD + fitness tests)
```

---
*Round 50-05 — Event Catalogue — ISSUED (full contracts; design).*
*Adds delivery (at-least-once+idempotent=effectively-once; no exactly-once/at-most-once), ordering (intra=version, inter=causal via CausationId, out-of-order parked), replay (projection-rebuild all ✔ / re-execute forbidden for Decision+side-effecting), security (public<internal<restricted<secret; no public events), two-sided authorization (allowed-consumers + exclusions; Voting consumes nothing foreign). Anonymity enforced. Fitness tests derived. Roadmap reordered: State Machines (50-07) before Repositories (50-08); +50-09 verification checklist. No code.*

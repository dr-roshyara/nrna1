# Round 50-05 — Event Catalogue (technical contracts)

**Phase III (Tactical Realization) · Built against Release 1.0 · Design → contract · 2026-06-26**
**Status:** 📑 EVENT CATALOGUE — per-event **payload contracts** (50-04 = relationships; this = contracts). `Event = Envelope(50-04 §1) + Payload`. Becomes the event classes at implementation. Concise.

> **Binding:** every payload obeys **Anonymity (Q7)** — no voter↔vote linkage; hashed/opaque ids only. Every event carries `SchemaVersion` (TP-3). Producer = single (50-04 §2).

## Contracts (Envelope + Payload, v1)

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
| **DeterminationIssued** | Adjudication | `determinationId · challengeId · outcome · legitimacy · reason · evidenceEnvelopeRef · issuedByAuthority · jurisdiction · finalizedAt` | references evidence by `envelopeHash` (no linkage) |
| **ElectionCorrectionApplied** | Election/Lifecycle | `electionId · determinationId · correctionType{ReRun\|Invalidate\|Accept\|ContainedOnly} · appliedAt` | `ContainedOnly` = anonymity-bounded (cannot un-cast) |

## Contract rules
- **Refs not entities:** payloads carry **references** (`evidenceEnvelopeRef`, `targetRef`, `submittedContentRef`) — never embedded aggregates (TP-1/TP-2).
- **Idempotency:** consumers dedupe on `EventId` (50-04 §5).
- **Versioning:** additive field → bump `SchemaVersion`; breaking → `EventName vN+1` (TP-3); old retained until consumers migrate.
- **Correction types** are a closed set; `ContainedOnly` encodes the anonymity limit (restore-forward only).

## Fitness tests (from contracts)
- No event schema contains `userId`/voter PII or any field enabling voter↔vote reconstruction (Q7).
- Each event has exactly one producer (no foreign emitter).
- Every consumer is idempotent on `EventId`.

## Open (unchanged)
- Replay event surface (`ReplaySessionOpened`/`ReplayCertificationIssued`/`ReplayDivergenceDetected`) — placement as Evidence App-Service vs own contracts → confirm at implementation (BDR-06).
- Mandate `scope`/`holderId` shape depends on the **Mandate-vs-Committee** open question.

## Next
```
50-05 Event Catalogue (this) ✓ → 50-06 Policy Catalogue → 50-07 Repository & Transaction Design
   → Implementation (greenfield Core: Challenge + Determination)
```

---
*Round 50-05 — Event Catalogue — ISSUED (technical contracts; design).*
*10 event payload contracts (Envelope+Payload v1); refs-not-entities; idempotent on EventId; SchemaVersion/TP-3 versioning; Anonymity enforced (no voter↔vote linkage — hashes only; ContainedOnly correction = anonymity limit). Fitness tests derived. Replay/Mandate-scope open. Next: 50-06 Policy Catalogue. No code.*

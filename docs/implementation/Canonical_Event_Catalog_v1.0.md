# Canonical Event Catalog v1.0 (FROZEN)

**Status:** 🧊 FROZEN registry — the single authoritative list of domain events. **Every context uses these names; no synonyms.** Full payload/delivery/security contracts live in **Round 50-05** (authoritative); this is the one-page registry. Changes follow ADR-T5 (version, never mutate).
**Date:** 2026-06-26 · Envelope: `EventId · EventType · AggregateId · AggregateVersion · OccurredAt · CorrelationId · CausationId · SchemaVersion` (50-04 §1).

| Event | Producer (only) | Category | Security | SchemaVer |
|-------|-----------------|----------|----------|-----------|
| `VoteAccepted` | Voting | Decision | restricted | 1 |
| `EvidenceRecorded` | Evidence | Evidence | restricted | 1 |
| `MandateGranted` | Appointment | Lifecycle | internal | 1 |
| `MandateRevoked` | Appointment | Lifecycle | internal | 1 |
| `ChallengeRaised` | Contestation | Process | internal | 1 |
| `ChallengeAdmitted` | Contestation | Process | internal | 1 |
| `ChallengeDismissed` | Contestation | Process | internal | 1 |
| `ChallengeRouted` | Contestation | Process | internal | 1 |
| `ChallengeResolved` | Contestation | Process | internal | 1 |
| `DeterminationIssued` | Adjudication | Decision | restricted | 1 |
| `ElectionCorrectionApplied` | Election/Lifecycle | Lifecycle | internal | 1 |

**Rules:** single producer each (50-04 §2); **Audit consumes all**; no payload carries voter↔vote linkage (ADR-T11); refs-not-entities (TP-1/T2). New event or version → add a row + bump `SchemaVersion`; **never edit a frozen row's meaning** (ADR-T5).

---
*Canonical Event Catalog v1.0 — FROZEN. 11 canonical events; authoritative contracts in 50-05; evolution governed by ADR-T5.*

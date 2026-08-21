---
source:
  original_name: "Inspection complete.md"
  original_path: "docs/knowledgeos/brainstorming/Inspection complete.md"
  detected_timestamp: "2026-08-19 10:48:02"
  timestamp_source: filesystem-mtime
  timestamp_confidence: medium
classification:
  theme: "off-topic-publicdigit"
  type: noise
  note: "PublicDigit Election OperatingCore delegation map — off-topic for the KnowledgeOS architecture corpus"
status:
  authoritative: false
  proposed: false
---

Inspection complete. Before writing any code, here is the delegation map the instruction requires — every handler's decision already has a domain owner:

┌────────┬─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┐
│   UC   │                                                                              Domain operations that own the decisions                                                                               │
├────────┼─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┤
│ UC-1   │ AG-2::expressPosition() (returns the fact; I-7 refusal) · AG-2::intervalState($committee) re-derivation before/after (the I-11 door — consequence facts follow a changed classification, which also │
│        │  gives Q-2 non-reissue for free) · RecoveryProcess::start() with snapshotFor(HaltedElectionRecovery)                                                                                                │
├────────┼─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┤
│ UC-2   │ AG-1::recordVacancy() (fact; I-2 refusal) · AG-1::unableToFunction(AG-2::requiredVotes()) before/after for onset · RecoveryProcess::start/resume/pause (I-15: existing paused restoration → resume, │
│        │  no second start, no re-snapshot)                                                                                                                                                                   │
├────────┼─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┤
│ UC-3   │ AG-1::fillSeat() (fact; refusal) · same onset door for restoration · RecoveryProcess::pause/resume remaining-portion semantics live in AG-3, not the handler                                        │
├────────┼─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┤
│ UC-4   │ ExpiryConsequence::onHaltedRecoveryExpiry / onRestorationExpiry — P-6 is the only consequence door (not on W-3's forbidden raw-policy list, deliberately); isExpiredAt stays inside AG-3            │
├────────┼─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┤
│ UC-5   │ AG-1::constitute() (I-1 refusal) · repository find for Q-2 · ⚠️ the constitution fact type is the named open point (Q-UC5) — a scheduled STOP at GREEN-6, not today                                 │
├────────┼─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┤
│ UQ-1…4 │ pure delegations: intervalState, intervalState+unableToFunction, readingAt, isExpiredAt                                                                                                             │
└────────┴─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┘

Key confirmations from the inspection: consequence facts (GateSatisfied, GateFailedByDecision, ElectionBecameInoperative, RecoveryPeriodStarted, ElectionRestored) have no domain producer — the handler constructs them from domain-derived values, and G-4's primary-fact ban deliberately names only the three aggregate-produced facts. The guard vocabulary is strict enough that even comments must avoid trigger tokens (W-2 bans three strings from the UC-1 handler file entirely).

Now GREEN-1: the granted surface only — 5 command DTOs, 5 handlers with the pinned constructor orders, 4 queries. Bodies throw BadMethodCallException so no behavioral test can pass vacuously (a silent no-op body could fake-pass the zero-mutation pins — throwing keeps them loudly RED).

● Write(app/Contexts/Election/Application/OperatingCore/Command/ExpressCommitteePositionCommand.php)

● Write(app/Contexts/Election/Application/OperatingCore/Command/RecordVacancyEventCommand.php)

● Write(app/Contexts/Election/Application/OperatingCore/Command/FillCommitteeSeatCommand.php)

● Write(app/Contexts/Election/Application/OperatingCore/Command/ReportPeriodExpiryCommand.php)

● Write(app/Contexts/Election/Application/OperatingCore/Command/RecordCommitteeConstitutionCommand.php)

───────────────────────────────────────────────────────────────────────────────────────────────────────────────
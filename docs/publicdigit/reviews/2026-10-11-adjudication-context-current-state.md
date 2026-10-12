# Adjudication context: current state (snapshot 2026-10-11)

**Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable) · **Kind:** state snapshot. It is taken from code reading and three read-only exploration passes, with the decisive claims re-checked in code. **No test was run for this snapshot; nothing here is a decision.**

**Basis:**
- branch `knowelegeos-modelling`, HEAD `5797ded2f`;
- the last code change in `app/Contexts/{Adjudication,Contestation,Election}` is `216552aa7` (2026-08-19).

**Companion:** the readiness review, D.0.3 scope and plan are in [`2026-10-11-adjudication-readiness-review-and-d03-scope.md`](2026-10-11-adjudication-readiness-review-and-d03-scope.md). This document holds only the current state.

**How to read it:**
- **Built:** the code exists.
- **Tested:** test files exist. U = no database, D = needs a database. None re-run here.
- **Ran:** recorded run evidence.
- **Not built:** documented or ruled, but not in code.
- **Open:** a decision owned by a human or a body.

## In one paragraph

The adjudication core is **built and covered by tests, but it does not run end to end in production**:
- **No entry point creates challenges.** No HTTP or UI path files a challenge (PBDIGIT-196).
- **No production caller** delivers rulings, evidence, redrives or the horizon to the process manager (177, 198).
- **Authority is not checked against Governance.** The Q-1 contract is unbuilt (191).
- **Issuance from the process manager is not atomic with its outbox record** (178).
- **The scheduled message relay runs without a tenant** (T-1), and a cross-tenant write was observed under an ambient tenant (T-4). Both wait for an ARB ruling on Q1–Q7 (211).

The domain rules themselves (state machines, one determination per challenge, idempotent and forward-only reactions) are implemented and guarded by database constraints where it matters.

## The loop as built

```
Challenge raised → admitted → routed ──ChallengeRouted──▶ AdjudicationProcessManager.openFor
        (Contestation)                                    (no production caller beyond this point)
                                     receiveRulingDecision → requestIssuanceFor → Determination.issue
                                                                    │ DeterminationIssued (schema v3)
                              ┌─────────────────────────────────────┴───────────────┐
                              ▼                                                     ▼
        Contestation: AdjudicateChallengeHandler                Election: DeterminationIssuedReactionHandler
        (Dismissed → resolved in the same step)                 Upheld → ElectionCorrectionApplied (ledger row)
                              ▲                                                     │
                              └──────────── ResolveChallengeHandler ◀────────────────┘
                                         (resolution Upheld)
Messaging: outbox:process / inbox:redrive every minute (routes/console.php:55,58) — no tenant is set (T-1)
```

## By component

### Contestation (`app/Contexts/Contestation`)
- **Built:**
  - **`Challenge` states:** Raised → Admitted → [Investigating] → Routed → Adjudicated → Resolved; Raised → Dismissed; Raised/Admitted → Lapsed. An illegal step throws without a change (`Challenge.php:187-192`).
  - **Events:** ChallengeRaised, ChallengeAdmitted, ChallengeDismissed, ChallengeRouted, ChallengeAdjudicated, ChallengeResolved. **Only `ChallengeRouted` is published** (`CoordinatesContestation.php:95-127`).
  - **Reactions:** to `DeterminationIssued` (a replay is detected; a conflicting determination is rejected) and to `ElectionCorrectionApplied` (parked until adjudicated). Outcomes are translated correctly to park, replay or dead.
- **Tested:** U ≈34 · D ≈20.
- **Not built:**
  - filing a challenge (196);
  - callers for dismiss and investigate (205);
  - a lapse trigger (206).
- **Defects and conflicts:**
  - **T-2:** the raiser, contested outcome and content aren't persisted (`ChallengeMapper.php:28-35`).
  - **181:** publishing `ChallengeRaised` (R-75) conflicts with ADR-UL-01 / PL-01.

### Adjudication: Determination (`app/Contexts/Adjudication/Domain`)
- **Built:**
  - Draft → Issued → Final.
  - `issue()` emits `DeterminationIssued` (schema **v3**; the hydrator accepts v2–3 and rejects v1) and fixes the `EvidenceSet`.
  - The outcome (Upheld / Dismissed) and the legitimacy (Legitimate / Illegitimate) live only in the event (Model B, ADR-T19).
  - Adjudication computes no legitimacy itself (ADR-T23).
  - **One determination per challenge:** a service check (`CoordinatesAdjudication.php:41-52`) **and** `unique(organisation_id, challenge_ref)`.
- **Tested:** U ≈49 · D 4.
- **Not built:**
  - `finalize()` has no caller, so no determination ever becomes Final (197);
  - no audit read model (T19);
  - `AdjudicationFailureDeclared` is never published (175).

### Adjudication: process manager (`Application/Process/AdjudicationProcessManager.php`)
- **Built:**
  - open (idempotent; partial unique index allowing one active process per challenge);
  - ruling decision (concluded once, then a separate issuance step);
  - R-84 reconcile (same authority = redelivery, different = `ConflictingDeterminationForChallenge`);
  - redrive per process;
  - horizon expiry publishing `AdjudicationExpired`.
- **Tested:** U 31 (incl. `ConcludeToIssuanceSeamTest`) · D 24.
- **Not wired in production:** only `openFor` has a caller (`ChallengeRoutedReactionHandler`). Nothing feeds the contested outcome or the evidence envelope (because `ChallengeRaised` isn't published), so live issuance would stop silently.
- **Defects:**
  - **R-91 / PBDIGIT-178:** `CoordinatorIssuanceRequest` takes the bare `CoordinatesAdjudication`, so issuance from the manager isn't atomic with its outbox record. Decisions D-1…D-4 pending.
  - **PBDIGIT-174:** the issuance mints a new provenance chain.

### Authority (Q-1)
- **Decided:** Governance owns authority, and Adjudication depends on it through a published contract (`EPIC-004_Q1_Authority_Resolution.md:4,14-15,25`, ARB 2026-07-26, frozen).
- **Built:** **nothing on the Adjudication side.**
  - No import from Governance.
  - `IssuedByAuthority` only checks the value isn't empty.
  - Governance's `AuthorityAssignmentRepository` has no implementation and no binding.
- **Open:** the contract's name (AuthorityDecision / Assertion / Grant), the delegate model, and the owner (PBDIGIT-191, after 189).

### Election reaction and correction (`app/Contexts/Election`)
- **Built:**
  - Upheld → `ContainedOnly`; Dismissed → nothing.
  - A single forward-only `CorrectionType`.
  - An idempotent ledger `election_applied_determinations` (unique key).
  - Cross-organisation rejection, via a tenant-filtered existence check.
- **What it changes:** **no result or tally is changed**. The "correction" is a ledger row.
- **Tested:** U 13 · D 8 (`CorrectionLoopIntegrationTest` is not a true end-to-end: the challenge is preset to Routed, issuance is direct, the tenant is set in `setUp`).
- **Defect T-3:** `DeterminationLacksElectionScope` and `CannotApplyDeterminationToUnknownElection` extend plain `DomainException`, so they're retried, not dead-lettered.
- **History:** rulings are never deleted. But the state rows are overwritten (`updateOrCreate`), so "history never rewritten" holds for ruling content (the outbox event), not for the state rows.

### Messaging (`app/Contexts/Shared/Infrastructure/{Outbox,Inbox,Messaging}`)
- **Built:**
  - the outbox relay: at least once, fixed 5-minute retry, 5 attempts, **no row lock**;
  - the inbox: dedup on (`event_id`, `consumer_context`), park / replay / dead, redrive;
  - registries.
- **Tested:** architecture guards (`InboxMessagingArchitectureTest`, `CorrelationIdMintingTest`, `EventRegistryCompletenessTest`, `GreenfieldCoreArchitectureTest`); D `RelayTenantContextTest` (deliberately failing).
- **Ran:** **historical only.** 3 fail, 1 pass, 1 incomplete (2026-10-09, on `nrna_test_adjudication`).
- **Defects:**
  - **T-1:** no tenant is set in the relay or inbox path.
  - **T-4:** a cross-tenant write under an ambient tenant.
  - **T-5:** `outbox_processing_errors` has no migration, but the relay writes to it.
- **Missing:** a replay tool for `failed` rows.
- **Open:** **ARB Q1–Q7 PENDING** (incl. Q3 "a foreign tenant is already set", Q4 "soft-deleted organisation").

## Invariants: where they stand

| Holds by database constraint | Backed by tests only | Violated / not enforced |
|---|---|---|
| one determination per challenge · one active process per challenge · Election applies a determination once | Challenge state machine · R-84 redelivery / conflict · Dismissed → no correction · `DeterminationIssued` versions 2–3 only | authority via Governance (191) · atomic issuance (178) · tenant per message (211) · dead-lettering in Election (T-3) · relay error records (T-5) |

## Blocking order (from `docs/publicdigit/backlog/README.md:273-305`)

`211` (after the ARB tenant ruling) → `178` → `177` + the Governance contract `191` → `174` → `182` end-to-end.

- **Drafts awaiting IDs:** T-3, T-5.
- **Unallocated:** 179, 205, 206.
- **Plan only:** 180.

## Documentation that is out of date
- **`docs/knowledge/domains/adjudication/`:**
  - it has no process manager;
  - it shows `issue()` without the evidence set;
  - it claims an outcome "read row";
  - "override via amendment" conflicts with EPIC-004H.

  Correcting it needs its own reviewed knowledge change (not done here).
- **`docs/implementation/PROGRAM_STATUS.md`** (2026-08-04) is out of date and cites the withdrawn R-90.
- **The comment at `CoordinatesAdjudication.php:73-74`** ("raise path not yet implemented") is out of date.

## Not verified by this snapshot
- Test results: nothing was run.
- T-3's runtime behaviour, which T-1 masks.
- The 2026-06/07 research and certification history, taken from the reports.
- Whether production cron actually runs `schedule:run`.

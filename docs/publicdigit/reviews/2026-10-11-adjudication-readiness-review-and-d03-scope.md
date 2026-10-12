# Adjudication: readiness review, invariant traceability and D.0.3 scope

**Date:** 2026-10-11 · **Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable), on the commission "PublicDigit Adjudication — Next-Step Readiness Review and Implementation Planning" · **Mode:** read-only investigation (three exploration agents; the decisive claims re-checked in code). **No files were changed, no tests run, no database touched; nothing was decided.**
**Placement:** `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0), under `reviews/` by convention.
**Baseline:**
- branch `knowelegeos-modelling`, HEAD `5797ded2f` at the time of investigation;
- the last code change in the Adjudication / Contestation / Election folders is `216552aa7` (2026-08-19);
- the latest status documents (2026-10-09) still match the code.

**Labels:**
- **S** = guaranteed by a database constraint (once the schema is applied)
- **T** = backed by tests (not re-run in this review)
- **C** = from reading the code only
- **✗** = violated or not enforced
- **U / D** = tests without / with a database

## 0. Corrections to the commission's premises
1. **D.0.3 is not an Adjudication step.**
   - It is the Election voting-security plan "Controlled Sovereign Enforcement Transfer", steps D.0.3a–e (`claude/audits/C5e_ConstitutionalStabilizationCertificate.md:258-263, :284`; `claude/plans/fancy-tinkering-newell.md:1240-1245`).
   - `ConstitutionalLegitimacyDecision` (`app/Application/Election/Security/ConstitutionalLegitimacyDecision.php`) decides whether a **voter is trusted at vote time**. Its only production caller is `TrustLegitimacySecurityVerdict` → `VoteController::store()`.
   - EPIC-003 (`docs/implementation/EPIC-003_Tactical_DDD_Entry_Assessment.md:95`) and EPIC-004A (`…EPIC-004A_Adjudication_Tactical_Discovery.md:19`) keep it explicitly apart from Adjudication's "legitimacy of a contested outcome".
   - **No document makes D.0.3 a prerequisite for Adjudication.**
2. **The challenge event is `ChallengeRaised`.** There is no `ChallengeFiled`.
3. **There is no `RecordRuling` command.** Rulings are recorded by `AdjudicationProcessManager::receiveRulingDecision`.
4. **`DeterminationIssued` is stamped schema version 3** (`OutboxEventAdapter.php:124`). The hydrator accepts versions 2–3 and rejects version 1.
5. **Shared messaging lives under `app/Contexts/Shared/Infrastructure/{Outbox,Inbox,Messaging}` and `Shared/Application/Inbox`.** There is no `Shared/Messaging` folder.
6. **The "ARB Q1–Q7" in the Adjudication status are about tenant context outside HTTP requests.** They are unrelated to EPIC-004's **Q-1** (authority).

## 1. Current state

| Area | Code (C) | Tests | Run evidence | Documented only / open |
|---|---|---|---|---|
| **Contestation** | `Challenge` state machine Raised → Admitted → [Investigating] → Routed → Adjudicated → Resolved; Raised → Dismissed; Raised/Admitted → Lapsed (`ChallengeState.php`; guard `Challenge.php:187-192`). Only `ChallengeRouted` is published (`CoordinatesContestation.php:95-127`). Reacts to `DeterminationIssued` (`AdjudicateChallengeHandler`; a Dismissed ruling resolves in the same call) and `ElectionCorrectionApplied` (`ResolveChallengeHandler`) | U ≈34 (`ChallengeTest` 12, reactions, hydrators) · D ≈20 | none in this review | no way to file a challenge (PBDIGIT-196); dismiss / investigate have no callers (205); no lapse trigger (206); publishing `ChallengeRaised` (R-75) conflicts with ADR-UL-01 / PL-01 (181, blocked). **T-2:** `ChallengeMapper::toRow` keeps only id, state and determination_id (`:28-35`) |
| **Adjudication / Determination** | Draft → Issued → Final (`DeterminationState.php`); `issue()` emits `DeterminationIssued` and fixes the `EvidenceSet` (`Determination.php:95-117`); the outcome lives only in the event (Model B, ADR-T19); one per challenge: service check (`CoordinatesAdjudication.php:41-52`) **and** `unique(organisation_id, challenge_ref)` (migration `2026_06_27_000001…:28`); no legitimacy computation (ADR-T23; `Domain/Policies` holds only `.gitkeep`) | U ≈49 · D 4 | none | `finalize()` has no caller (197); no audit read model (T19); `AdjudicationFailureDeclared` never published (175) |
| **Process manager** | `AdjudicationProcessManager`: `openFor` (only production caller: `ChallengeRoutedReactionHandler`), `receiveRulingDecision` (conclusion written once, then a separate issuance step), R-84 reconcile (`:305-334`), `redriveIssuance`, `enforceHorizon`; partial unique index, one active process per challenge (migration `2026_07_30_000001…:122-126`) | U 31 (incl. `ConcludeToIssuanceSeamTest`) · D 24 | none | **no production caller** for decision, evidence, redrive, horizon (WP-4D / PBDIGIT-177 not authorised; horizon trigger PBDIGIT-198). **R-91 / PBDIGIT-178:** `CoordinatorIssuanceRequest` receives the bare `CoordinatesAdjudication`, not the transactional wrapper, so issuance isn't atomic with the outbox (decisions D-1…D-4 pending) |
| **Governance authority (Q-1)** | Governance context exists (89 files). **Adjudication imports nothing from Governance.** `IssuedByAuthority` only checks the value isn't empty. `AuthorityAssignmentRepository` has no implementation or binding | Governance unit tests only; none at issuance | none | Q-1 **decided and frozen** (`docs/implementation/EPIC-004_Q1_Authority_Resolution.md:4,14-15,25`: Governance owns authority; Adjudication depends on it via a published contract). **The contract isn't built** (PBDIGIT-191 not opened, after 189); its name and delegate model are open |
| **Correction loop (ADR-T8)** | `DeterminationIssuedReactionHandler` → `Election::applyDetermination`; Upheld → `ContainedOnly`, Dismissed → nothing; one forward-only `CorrectionType`; idempotent ledger `election_applied_determinations` (unique key); no deletes of rulings | U 13 · D 8 (`CorrectionLoopIntegrationTest` IT-1…IT-4: not a true end-to-end; tenant set in `setUp`) | none | the "correction" changes **no result or tally**. State rows are overwritten (`updateOrCreate` / `updateOrInsert`), so "history never rewritten" holds for ruling content (outbox event), not for state rows. End-to-end validation deferred (182). The "five transactions" wording isn't in ADR-T8 itself (it's in EPIC-004K / Blueprint) |
| **Messaging** | outbox relay `outbox:process` (at least once; fixed 5-min retry, 5 attempts; **no row lock / SKIP LOCKED**); inbox dedup unique (`event_id`, `consumer_context`), park / replay / dead (`InboxExecutionEngine`), `inbox:redrive`; both scheduled every minute (`routes/console.php:55,58`) | architecture tests (`InboxMessagingArchitectureTest`, `CorrelationIdMintingTest`, `EventRegistryCompletenessTest`, `GreenfieldCoreArchitectureTest`); D `RelayTenantContextTest` (deliberately failing) | **historical only:** 3 fail, 1 pass, 1 incomplete (2026-10-09, on `nrna_test_adjudication`); not re-run | **T-1:** the relay sets no tenant (verified: no `TenantContext` call in the relay or inbox path). **T-4:** a cross-tenant write under an ambient tenant. **T-3:** Election's rejection exceptions extend plain `DomainException`, so they're retried, not dead-lettered. **T-5:** `outbox_processing_errors` has no migration. ARB request **PENDING** (`2026-10-09-arb-decision-request-tenant-context-non-request-execution.md:4`). Nothing in the HTTP path produces loop events today |
| **D.0.3 / legitimacy** | D.0.3a gate in `VoteController::store` (`:1508-1551`); `ConstitutionalLegitimacyDecision` maps four trust states to four outcomes | U `D0_3a_ConstitutionalEnforcementTest` (it doesn't exercise the controller) · D 2 | none; the plan's claim "50/50 verification tests passing" can't be traced to any test file | D.0.3b–e exist on paper only; the plan contradicts itself (D.0.3a both ✅ and "⏳ NEXT", `fancy-tinkering-newell.md:1240` vs `:1324`); deleting the class is proposed, not decided (reality audit L-2 / P0-D) |
| **`VOTING_CONSTITUTIONAL_MODE`** | `config/voting_security.php:108`, default **true** (flipped in `89914ce3a`, 2026-05-29); read **only** in `ValidateVotingIp.php:29` (fallback false) | `ValidateVotingIpTest` (`Tests\TestCase`; whether it needs the database is unverified) | none | **no practical effect:** `users.voting_ip` doesn't exist, so the mismatch branch never runs (static reasoning). The config comment (`:104`) is stale ("false (default)") |
| **Procedural retirement** | `constitutional:authorize-retirement` (`D02AuthorizeRetirement.php`) only records an authorisation and prints "Run D.0.3" | none | whether it was ever run is unknown | no defined "procedural-retirement mechanism"; `docs/publicdigit/adr/ADR_20260806_1620_Constitutional_Rule_Ownership_Migration.md` **PROPOSED** (PBDIGIT-45) |

## 2. Invariants and traceability

| # | Invariant | Owner | Enforced by | Tests | Status |
|---|---|---|---|---|---|
| I-1 | at most one determination per challenge | Adjudication | service check plus unique index | U, D | **S** |
| I-2 | at most one active process per challenge | Adjudication | manager idempotency plus partial unique index | U, D | **S** |
| I-3 | Challenge moves only along the defined state machine | Contestation | aggregate guard | U (12) | T |
| I-4 | a redelivered ruling is idempotent; a conflicting one is rejected (R-84) | Adjudication | manager reconcile | U (seam) | T |
| I-5 | Election applies a determination at most once | Election | aggregate set, unique ledger, inbox dedup | U, D | **S** + T |
| I-6 | Dismissed produces no correction | Election | `ElectionCorrectionPolicy` | U, D (IT-2) | T |
| I-7 | correction is forward-only and never rewrites rulings | Election / Adjudication | a single correction type; no deletes | — | C (state rows are overwritten) |
| I-8 | authority is decided only by Governance (Q-1) | Governance | — (non-empty string only) | none | **✗ not enforced** (PBDIGIT-191) |
| I-9 | issuance and its outbox record are atomic | Adjudication | transactional wrapper, not on the manager path | U | **✗** (PBDIGIT-178) |
| I-10 | message handling runs under the message's own tenant | Platform (ARB) | — | D (deliberately failing) | **✗** (T-1 / T-4, PBDIGIT-211) |
| I-11 | messages that can never succeed are dead-lettered | each consumer | `PermanentInboxFailure` | none for Election | **✗** (T-3) |
| I-12 | relay errors are recorded | Messaging | the table is missing | — | **✗** (T-5) |
| I-13 | only `DeterminationIssued` schema versions 2–3 are accepted | Adjudication | hydrator | U (8) | T |
| I-14 | concurrent relays don't cause double processing | Messaging | inbox dedup per (event, consumer); the relay has no lock | none | C (inference) |

**Formal standing:**
- Nothing is formally proven. S means a database guarantee under the applied schema; T means tests exist but were not re-run.
- **One method adds measurable value:** an exhaustive state-space check of Challenge × Process × Determination × delivery patterns (duplicate, reordered), on the model of the Membership C-1 differential. The space is small and finite, and it would make I-3 … I-6 exhaustively checked within a stated bound.
- Machine learning has no place in these deterministic rules.

## 3. D.0.3 readiness: not applicable as framed

**Why readiness can't be shown:**
- D.0.3b–e have no code, no defined acceptance evidence and no authorisation.
- Their precondition ("D.0.3a stable") can't be checked: no test backs the "50/50" claim.
- `C5e` itself "grants authorization to review, not automatic progression".

**Evidence needed before `VOTING_CONSTITUTIONAL_MODE` may be changed:**
1. Acceptance of ADR 2026-08-06 "Constitutional Rule Ownership Migration" (PBDIGIT-45).
2. A decision on L-2 / P0-D (keep, fold into the resolver, or delete `ConstitutionalLegitimacyDecision`).
3. `users.voting_ip` actually existing, because otherwise the flag has no effect.
4. Run evidence for the D.0.3a gate through `VoteController`, on an isolated database.

**Changing the flag to `false` is the documented rollback,** not a step forward.

**The real Adjudication blocker:** ARB Q1–Q7 (the tenant model for messaging), then PBDIGIT-178 → 177 (plus the Governance contract, 191) → 174 → 182 (`docs/publicdigit/backlog/README.md:273-305`).

## 4. Smallest prerequisite plan (no implementation plan: the domain path is blocked)

| Step | Who | Result | Unblocks |
|---|---|---|---|
| P-0 | Human PO | **scope:** (a) the Adjudication chain, or (b) the D.0.3 voting-security track? | everything |
| P-1 (a) | ARB | ruling on Q1–Q7, incl. Q3 (a foreign tenant is already set) and Q4 (soft-deleted organisation) | PBDIGIT-211 |
| P-2 (a) | Human PO | IDs for the drafted items T-3 and T-5 | small, independent fixes |
| P-3 (a) | Decision Authority | PBDIGIT-178 decisions D-1…D-4 (atomic issuance) | 178 |
| P-4 (a) | Governance / ARB | the authority contract: name, delegate model, owner (191 after 189) | 177 |
| P-1 (b) | ARB / PO | accept ADR 2026-08-06 (PBDIGIT-45), decide L-2 / P0-D, define the acceptance evidence for D.0.3b–e | the D.0.3 track |

**After P-1 (a):** a test-first plan for PBDIGIT-211. It would cover:
- the relay, inbox, redrive and dispatcher, plus a tenant scope with guaranteed restore;
- acceptance criteria AC-R1…R4, with `RelayTenantContextTest` as the RED baseline;
- an architecture rule for tenant scoping in commands;
- run evidence on an isolated, identity-checked database;
- rollback: code only.

**Can start early (no database, independent of the ruling):** T-3, where both Election exceptions implement `PermanentInboxFailure`. That is a unit test plus a few lines of code, and it needs P-2.

## 5. Risks, assumptions, open decisions
- **Test database:** `nrna_test` is shared and wiped on every run. Whether `nrna_test_adjudication` is free for this workstream needs confirming.
- **T-4 becomes reachable** as soon as an in-process worker, or a fix that doesn't restore the tenant, appears.
- **Concurrent relays** dispatch the same rows twice. Inbox dedup keeps processing to once per consumer, but that's an inference with no test.
- **While R-91 is open,** the decision path can produce a determination with no outbox record.
- **Assumption:** the run results reported on 2026-10-09 are historical; none was re-run here.
- **Open decisions:** P-0…P-4; PBDIGIT-181 (the `ChallengeRaised` contradiction); a replay and retention policy for `failed` and `dead` messages; the stale knowledge domain `docs/knowledge/domains/adjudication/` (no process manager; "override via amendment" conflicts with EPIC-004H).

## 6. TODO list (prioritised)
1. **PO:** scope decision P-0. *Blocks everything.*
2. **ARB:** Q1–Q7 ruling. *Blocks 211, and so the whole Adjudication chain.*
3. **PO:** IDs for T-3 and T-5; confirm an isolated test database.
4. **Engineering, once 3 is done:** T-3 test-first, without a database.
5. **Engineering, once 2 is done:** the PBDIGIT-211 plan → principal review → approval → test-first on the isolated database.
6. **Engineering:** a T-5 migration plan (needs migration approval).
7. **Decision Authority:** PBDIGIT-178 D-1…D-4; then implement 178.
8. **Governance:** the authority contract (191) → 177 decision intake → 174 → 182 end-to-end.
9. **Only if track (b) is chosen:** ADR 2026-08-06 / PBDIGIT-45 and L-2 / P0-D; then define the D.0.3b–e evidence.
10. **Optional:** an exhaustive state-space check of the loop (I-3 … I-6).

## 7. Recommendation
- **NO GO** for D.0.3 implementation or any change of `VOTING_CONSTITUTIONAL_MODE`: the premise doesn't hold, D.0.3b–e are undefined, and nothing is authorised.
- **NO GO** for Adjudication implementation until the ARB rules on Q1–Q7.
- **GO** for principal review of this report and for decision P-0. T-3 can be the first small, database-free step once its ID is approved.

**Traceability:**
- status records `2026-10-09-adjudication-programme-status-recovery.md`, `2026-10-09-adjudication-remediation-gate.md` (§10.6, §11), `2026-10-09-arb-decision-request-tenant-context-non-request-execution.md`;
- `.claude/CONTEXT-publicdigit.md` (2026-10-09 Adjudication block);
- backlog PBDIGIT-174…198, 211.

Nothing in this document authorises a change.

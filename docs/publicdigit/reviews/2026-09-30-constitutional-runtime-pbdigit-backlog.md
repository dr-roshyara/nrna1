# Constitutional Governance Runtime — PBDIGIT Migration Backlog

| | |
|---|---|
| **Date** | 2026-09-30 |
| **Kind** | ⚠ authority: generated. **Repository-side ticket specifications**; no issue tracker was used, and no Jira ticket exists. The PO creates tracker items from this document. **Nothing here is authorised**: every ticket is `TODO — NOT AUTHORISED` until the PO authorises it. |
| **Sources** | `2026-09-30-constitutional-runtime-reality-audit.md` (historical evidence, `4afd43481` + additive correction) · `2026-09-30-constitutional-runtime-migration-readiness.md` (**current baseline**) |
| **Settled architecture (not reopened)** | The sovereign participation boundary is **`ElectionCapabilityResolver` together with its registered `CapabilityPolicy` implementations** (readiness review §3) |
| **Readiness verdict** | **READY FOR CHARACTERIZATION** |
| **Prepared by** | `claude-code-session:42e0287e-e0e2-4638-8165-cd5d5862c578` (self-declared) |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0). The ticket index is added to `docs/publicdigit/backlog/README.md` |

---

## 1. Executive summary

- **74 tickets, `PBDIGIT-76` … `PBDIGIT-149`**. They take the repository from today's distributed voting authority to a frozen v1.0 Constitutional Governance Runtime.
  - `133`–`138` were added on 2026-10-01 for tasks that had been identified but not yet ticketed (§4g).
  - `139`–`149` come from the governance-capability story review, which covers admin lifecycle actions (§4h).
  - Earlier numbers were never renumbered.
- **13 are HUMAN DECISION tickets**: `PBDIGIT-90`…`100` (H-1…H-11), `PBDIGIT-138` (H-12) and `PBDIGIT-141` (H-13). One more existing decision governs legacy retirement: **`PBDIGIT-45`**, with its PROPOSED ADR `ADR_20260806_1620_Constitutional_Rule_Ownership_Migration`.
- **Characterization comes first and needs no decision.** It is C-1 (`76`–`78`), then C-2 (`79`–`80`), then C-3 (`81`–`84`).
- **Four tracks can start in parallel with characterization, also without any decision:**
  - the resolver trace fix (`109`);
  - dead-code removal (`122`);
  - the CI job (`123`);
  - documentation corrections (`130`).
- **The five security fixes** (`85`–`89`) depend only on their own C-3 pins. They are scheduled by the PO.
- **Implementation of constitutional law, evidence and evaluators is blocked by the H decisions.** Authority does not move before runtime parity (`116`). Legacy authority is not deleted before the switch (`117`/`118`) and `PBDIGIT-45`.
- **NEXT IMPLEMENTATION TICKET: `PBDIGIT-76` — C-1: repair voting feature-test fixtures for the current schema.** `PBDIGIT-77` and `PBDIGIT-78` complete C-1 and may run in parallel with it. **Governing rule:** C-1 repairs the test environment's representation of the current production contract. It never repairs, restores, reinterprets or redesigns production voting authority. A discovered production defect becomes a new PBDIGIT ticket.
- **Correction pass (2026-10-01).** An external review of this backlog led to these changes, with no renumbering:
  - the critical path is corrected (§6);
  - the status of `PBDIGIT-45`'s PROPOSED ADR is clarified (§4c);
  - a one-to-one defect matrix is added (§4d);
  - an architecture gate is added to every ticket (§4e);
  - the C-1 guardrail is added to `76`–`78`;
  - the resolver tickets are labelled R-1a (mechanics) and R-1b (CAST_VOTE integration);
  - progressive CI enforcement is stated (§6);
  - the git-index incident is recorded (§4f).

  Two review points were **not** applied, because the backlog already met them:
  - each H decision already gated only its own dependents;
  - resolver mechanics (`109`) were already separate from CAST_VOTE integration (`110`/`111`).

## 2. Current state (from the two source documents; not re-derived here)

Participation is decided in **≥17 places**:

| Where | What decides |
|---|---|
| `VoteController::store` | the trust pipeline `PolicySequence → ConstitutionalLegitimacyDecision` |
| lifecycle | `canVote()` in 4+ gates |
| membership | 5 sites using 4 different predicates |
| per-IP limits | 4 implementations |
| code state | `can_vote_now` treated as authority |
| middleware | four participation middlewares |

- `ElectionCapabilityResolver` is used only for admin lifecycle actions.
- The frozen constitution is written but never read. It has two incompatible representations and hashes, and it cannot be authored.
- Several rules are inert: device binding, officer verification, and IP binding (whose source column `users.voting_ip` does not exist).
- There are 11 pinned-to-be defects, X-1…X-11, plus X-12 found during this decomposition.
- All 218 failing real-election voting feature tests are test-infrastructure defects.

## 3. Target state

```
Frozen constitution (one representation, one hash, verified on load, immutable)
  → typed evidence with provenance (no interpretation)
  → pure evaluators (findings only)
  → ElectionCapabilityResolver + registered CapabilityPolicy set for cast_vote
       (evaluates every policy · returns decision + complete trace · stateless)
  → persisted decision record (every decision; anonymity preserved) → replay
  → controllers act on the decision; middleware = transport/abuse only
```

## 4. Ticket numbering convention

- **Discovered range.** The highest existing ticket number is `PBDIGIT-75` (file `PBDIGIT-75-newsletter-authorization-omits-election-tenancy-check.md`; commit `30fb47468`). The flat sequence convention is documented in `docs/publicdigit/backlog/README.md` §"ID conventions": one sequence, never reuse a number.
- **Allocated here:** `PBDIGIT-76` … `PBDIGIT-132` (2026-09-30) and `PBDIGIT-133` … `PBDIGIT-149` (2026-10-01), consecutively. Every number was checked for zero references in the repository before allocation.
- **Anomalies observed, not repaired** (they are not this backlog's to fix):
  - **`PBDIGIT-69` is already used twice.** The two files are `PBDIGIT-69-operating-core-post-verification-items.md` and `PBDIGIT-69-voter-eligibility-cache-is-tenant-unaware.md`, both committed in `50d1ff1b1`. This violates the README's never-reuse rule, and the PO should rename one of them.
  - **`PBDIGIT-52` … `57` are unused gaps.** They were deliberately **not** back-filled, so that the sequence stays chronological.
  - **`PBDIGIT-6569`** appears only as a work-item string in `tests/Unit/Platform/WorkflowEngine/Pbdigit6569ReplayTest.php`. It is not a ticket.
- **Existing tickets reused, not duplicated** (canonical discovery, ES-005.4):

| Existing | Relationship to this backlog |
|---|---|
| `PBDIGIT-45` + `ADR_20260806_1620` (PROPOSED) | owns the per-IP rule's **ownership and retirement** decision → gates `PBDIGIT-117`, `121`. H-3/H-4 (`92`/`93`) are its sub-questions and do not reopen it |
| `PBDIGIT-35` | the retired `is_voter`/`can_vote` production references (X-9). Not duplicated; `PBDIGIT-84` only pins it |
| `PBDIGIT-37` | committed test credentials (reality audit G-20). Not duplicated |
| `PBDIGIT-49` · `65` · `68` · `69` | membership/eligibility, coupled to H-7 (`96`) |
| `PBDIGIT-42` | overlay audit semantics, related to H-8 (`97`) |
| `PBDIGIT-41` · `66` | demo provisioning / election-only mode, related to H-9 (`98`) |
| `PBDIGIT-64` | the approved-candidate rule that C-1 lifecycle fixtures must satisfy |

## 4a. What deliberately did NOT become a ticket

| Item | Class | Where it is handled |
|---|---|---|
| X-1 ignored post-check, X-2 bypassed pre-check | known defect | pinned (`81`); removed by the switch (`117`/`118`). Fixing them in the controller would add authority that D-1 deletes |
| X-7 no-op `vote.organisation` | known defect / future deletion | pinned (`83`), deleted (`120`) |
| X-8 dual-code env comparison | known defect | pinned (`84`); resolved by law (`101`, `107`). An interim fix is a PO option, not a ticket |
| X-9 `is_voter` | existing ticket | `PBDIGIT-35` |
| X-11 unauthorable law | missing capability | `102` |
| X-12 `users.voting_ip` absent | known defect / decision input | pinned (`84`); H-1 (`90`) |
| resolver sovereignty boundary | architectural invariant | readiness review §3, enforced by `124` |
| anonymity (no voter↔vote link) | architectural invariant | constraint on `112`, `114` |
| 3 failing Architecture tests | triage item | inside `123` |
| committed credentials | existing ticket | `PBDIGIT-37` |
| first-audit percentages / phase names | historical | `130` corrects the claims |

## 4b. Record of this session's scope change

C-1 work was begun and then stopped at the PO's redirection to ticket decomposition. The **four test-file edits made (duplicate-role fix) were reverted with `git restore`**, and `tests/` is byte-identical to `HEAD`. The observations made during that attempt are captured in `PBDIGIT-76`/`77`:
- `ElectionMembership::assignVoter()` was removed in `ba4cfa9c1`;
- `users.is_voter`, `can_vote` and `voting_ip` exist in no active migration.

## 4c. Status of `PBDIGIT-45` and its ADR (clarified 2026-10-01)

There are four levels here, and they must not be collapsed into one another:

| Level | Artifact | Status | Effect in this backlog |
|---|---|---|---|
| Historical evidence | `PBDIGIT-45` discovery + `2026-08-06-who-owns-the-votes-per-ip-rule.md` | recorded | evidence for H-3/H-4 only |
| Proposal | `ADR_20260806_1620_Constitutional_Rule_Ownership_Migration` | **PROPOSED, not accepted** | **none**: a proposal is not law and authorises nothing |
| Human decision | PO/ARB acceptance (or rejection) of that ADR | **not taken** | **this act** gates `117` and `121` |
| Implemented law | the canonical constitution (`101`) | not built | derives from H-1…H-4 decisions, **never** from the PROPOSED ADR's text |

**No ticket may cite `ADR_20260806_1620` as authority while it is PROPOSED.**

## 4d. Defect traceability matrix (X-1 … X-12)

One defect, one pin test (X-id in the test name), one resolution owner.

| Defect | Class | Evidence | Pin (C-3) | Resolution owner | Parity path (§11) |
|---|---|---|---|---|---|
| X-1 `store` ignores `vote_post_check` result | control flow | `VoteController:1726` | `81` | resolved by authority switch `117` (the check becomes resolver evidence) | 7, 10 |
| X-2 `first_submission` pre-check bypassed | control flow | `VoteController:538-545, 2944-2947` | `81` | `118` | 8 |
| X-3 lock/transaction leak on store IP denial | security / persistence | `VoteController:1640-1673` | `81` | fix `85` | 11 |
| X-4 plaintext code logging | security | `VoteController:4087-4150`; `CodeController:881-885` | `82` | fix `86` | — |
| X-5 `debug_code` exposure | security | `VoteController:2426, 2442` | `82` | fix `87` | — |
| X-6 session demo-flag bypass on real routes | security / authority | `VoteEligibility:36`; `CodeController:683-700` | `83` | fix `88` | X-6 cases |
| X-7 `vote.organisation` checks nothing | dead gate | `EnsureRealVoteOrganisation:109` (never called) | `83` | deleted in `120` (isolation stays in `voter.slug.consistency`) | — |
| X-8 dual-code mode cannot activate (string vs `=== 1`) | configuration / law | `VoteController:1936, 2970, 3112`; `CodeController:917` | `84` | protocol becomes law: `101` + `107`. An interim config fix is a PO option, not a ticket | 2, 9 |
| X-9 `User::is_voter/can_vote` read non-existent columns | schema / data model | `User.php:111, 415, 433, 463` | `84` | existing **`PBDIGIT-35`** | — |
| X-10a whitelist `json_decode(array)` TypeError | runtime defect | `TrustPolicyEvaluator:140` | `84` | fix `89` | 12 |
| X-10b `?int $userId` vs UUID TypeError | runtime defect | `VoteController:1370` | `84` | fix `89` | 3–14 (denial branches) |
| X-11 per-election law cannot be authored | constitutional design | source columns neither fillable nor written | `84` | `102` (authoring), with `101` | all (law fixtures) |
| X-12 `users.voting_ip` read but never created | schema / constitutional design | no migration; `ValidateVotingIp`, `VoteController::store` | `84` | decision H-1 `90` → evidence `106` | 11 |

## 4e. Architecture gate (every ticket)

Each implementation ticket declares which boundary it touches:

| Code | Boundary |
|---|---|
| **A** | sovereign authority |
| **B** | constitutional law |
| **C** | evidence |
| **D** | evaluator output |
| **E** | resolver authority |
| **F** | transport-only behaviour |
| **G** | persistence invariants |
| **—** | none (test-only, documentation, CI scaffold) |

**A, B or E ⇒ explicit ARB architecture approval is required in addition to PO authorisation.** The gate is a row in every ticket specification below.

The sequence for a gated change is **decision → recorded ADR/decision → architecture acceptance → PO authorisation → implementation**. These are five separate events:
- a **signed H decision does not authorise** any dependent ticket;
- **"all dependencies done" does not authorise** a ticket.

## 4f. Repository process record (2026-10-01)

**What happened.** This backlog, the readiness review, the reality-audit correction, the README index and the session log were staged here, then committed by a **concurrent KnowledgeOS session** in `4c69fa9ab` ("exec(knowledgeos): 1bb-r2 run 2 …"). They sit beside 6 KnowledgeOS files under an unrelated subject. The same thing happened earlier with `a72528b29` (the UC-4 decision record).

**Verified from git.** `git show --stat 4c69fa9ab` lists exactly 5 files from this work:
- `.claude/sessions/2026-10-01.md`
- `docs/publicdigit/backlog/README.md`
- `…migration-readiness.md`
- `…pbdigit-backlog.md`
- `…reality-audit.md` (the 11-line additive correction)

It also lists 6 `LOOP-1BBR2/A2/*` KnowledgeOS files that are not from this work. No unintended file from this work was included.

**History was not rewritten.** It is another session's commit.

**Cause.** Concurrent sessions share one git index.

**Rule from now on (process, not code).** Sessions working concurrently use **separate git worktrees**, or do not run at the same time. A session commits with `git commit -- <paths>`, which commits only the named paths, and never with a broadly staged index.

## 4g. Tasks added as tickets on 2026-10-01

Every task identified in the audits, the readiness review or the backlog reviews but not yet numbered is now either a ticket or an amendment to one:

| Task | Now |
|---|---|
| Temporal-determinism scanner false positive (`date(` matches `validate(`) | **`PBDIGIT-133`** |
| `PBDIGIT-69` numbering collision | **`PBDIGIT-134`** |
| Non-slug `POST /votes` route reaching `store` with first-election fallback; possible double route loading | **`PBDIGIT-135`** (gates the authority switch `117`) |
| `voter_slugs.vote_completed` has no writer | **`PBDIGIT-136`** (input to H-7; gates `105`) |
| Unwired divergence-observation infrastructure, missing from the deletion manifest | **`PBDIGIT-137`** |
| Whether voting codes are stored hashed | **`PBDIGIT-138`** (H-12, HUMAN DECISION) |
| Evidence builders use the injected clock and record the lifecycle state they observed | amendment to `105`, `106` |
| Serializer and hash-algorithm versions in the decision record | amendment to `112` |
| The reality audit's `date()` claim | additive correction in the audit; the test fix is `133` |

**Still not tickets, deliberately:**
- **Separate git worktrees for concurrent sessions.** This is a repository process rule (§4f), not a product story. It belongs in the engineering process, not the PBDIGIT backlog.
- **The X-8 interim configuration fix.** It is a PO option, superseded by law (`101`/`107`).

## 4h. Governance-capability story review (2026-10-01): implemented or not?

**The story reviewed.** The story is "constitutionally governed election execution". It is about **admin lifecycle actions**: lifecycle derivation, the suspension overlay, capability policies, the action registry, frontend rendering and governance E2E. Each claim was checked against code and tests on 2026-10-01.

| Story claim / TODO | Verdict | Evidence | Ticket |
|---|---|---|---|
| Lifecycle state derived from facts | ✅ mostly | `ElectionLifecycleEngineImpl::getState` (`:64-176`); also queries candidacies and reads `now()` | `148` |
| Eleven business-progression states | ✅ | `ElectionLifecycleState` (`:40-50`) | — |
| Suspension is an overlay (VotingActive + Suspended), not a state | ❌ by design today | `Suspended` is an enum case (`:76`) returned first by `getState` (`:68-71`), masking the underlying stage. The enum names dual-axis as the long-term target | **not ticketed**: the story itself defers the dual-axis rewrite, and its transitional conditions hold (next row) |
| Suspend preserves facts; resume clears the overlay and re-derives | ✅ | `ElectionManagementController::suspend/resume` (`:1007-1072`); time-passes case tested at model level (`ElectionSuspensionTest:186-228`) | HTTP-level E2E: `149` |
| Recomputability for the same facts and time | ⚠️ | there is no way to supply the instant: ambient `now()` in the engine and `ElectionClockService` | `148` |
| Capability foundation "100 tests / 254 assertions GREEN" | ❌ today | 168 tests, **6 failures**: `CanEditTimelineCapabilityTest` ×3 (fixture gap), `CapabilityPolicyLayerTest` ×3 (stale since `e697ffac6`); `ElectionSuspensionTest` 1 fixture failure | `139` |
| `OverlayCapabilityPolicy` | ✅ exists, registered | hard-codes `resume` | `146` |
| `RoleCapabilityPolicy` | ❌ missing | roles are decided in `ConstitutionalTransitionGuard` (RULES roles) **and** `ElectionPolicy` (`is_super_admin` / chief), with different vocabularies | H-13 `141`, `144` |
| `PreconditionsCapabilityPolicy` ("unknown ≠ true") | ❌ in the resolver; ✅ in the guard | the guard throws `LogicException` on an unknown precondition (`:236`); the resolver has no preconditions policy | `144` |
| `ElectionCapabilityResolver` as single runtime authority | ⚠️ | exists; considers only state + suspension; four authorities disagree | `140`, `144`, `145` |
| `ElectionConstitutionRegistry` | ⚠️ partial | 18-line pass-through over `RULES`; no overlay behaviour; placeholder `resume.target_state` | `146` |
| `LegacyAllowedActionsProjector` | ❌ missing | `ElectionLifecycleEngineImpl::derivePermissions` / `getAllowedActions` are a second, divergent capability matrix (no `suspend`; nothing in `submitted_for_approval`) | `145` |
| Suspend/resume governed by the constitution | ❌ | both call `ElectionStateWriteContext::authorize` directly, bypassing the guard; an archived election can be suspended | `142` |
| Governance acts auditable | ⚠️ | suspend audited, resume not | `143` |
| Frontend renders capabilities only | ⚠️ | raw state checks `Management.vue:925,939`, `SubmitForApproval.vue:151`; 36 legacy-prop uses | `147` |
| E2E governance scenarios | ⚠️ | suspend role tests exist; no HTTP journey Draft→Archived, no HTTP suspend-during-voting→resume→Counting | `149` |

**Unmeasured percentages.** The story's "~70–80%" and the maturity bars are its own estimates, so they are not adopted.

---

## 5. Complete ticket inventory

| Ticket | Stage | Title | Depends on | Blocks |
|---|---|---|---|---|
| `PBDIGIT-76` | C-1 | Repair voting feature-test fixtures for current schema (retired columns) | — | PBDIGIT-79 · PBDIGIT-80 · PBDIGIT-109 · PBDIGIT-122 · PBDIGIT-123 |
| `PBDIGIT-77` | C-1 | Repair voting feature-test tenancy, factory, constructor and route fixtures | — | PBDIGIT-79 · PBDIGIT-80 · PBDIGIT-109 · PBDIGIT-122 · PBDIGIT-123 |
| `PBDIGIT-78` | C-1 | Repair voting-active lifecycle fixtures to current lifecycle rules | — | PBDIGIT-79 · PBDIGIT-80 · PBDIGIT-109 · PBDIGIT-122 · PBDIGIT-123 |
| `PBDIGIT-79` | C-2 | Characterize the voting entry path at the HTTP boundary | PBDIGIT-76 · PBDIGIT-77 · PBDIGIT-78 | PBDIGIT-83 · PBDIGIT-84 · PBDIGIT-101 · PBDIGIT-105 · PBDIGIT-106 · PBDIGIT-107 · PBDIGIT-136 |
| `PBDIGIT-80` | C-2 | Characterize the ballot, verify and store path at the HTTP boundary | PBDIGIT-76 · PBDIGIT-77 · PBDIGIT-78 | PBDIGIT-81 · PBDIGIT-82 · PBDIGIT-84 · PBDIGIT-101 · PBDIGIT-105 · PBDIGIT-106 · PBDIGIT-107 · PBDIGIT-135 |
| `PBDIGIT-81` | C-3 | Pin voting-flow control defects X-1, X-2, X-3 | PBDIGIT-80 | PBDIGIT-85 |
| `PBDIGIT-82` | C-3 | Pin voting-code secrecy defects X-4, X-5 | PBDIGIT-80 | PBDIGIT-86 · PBDIGIT-87 |
| `PBDIGIT-83` | C-3 | Pin participation-bypass defects X-6, X-7 | PBDIGIT-79 | PBDIGIT-88 |
| `PBDIGIT-84` | C-3 | Pin configuration and schema defects X-8…X-12 | PBDIGIT-79 · PBDIGIT-80 | PBDIGIT-89 |
| `PBDIGIT-85` | X | X-3 — Release the vote lock and roll back on the store IP-denial branch | PBDIGIT-81 | PBDIGIT-117 |
| `PBDIGIT-86` | X | X-4 — Stop logging voting codes and compare codes in constant time | PBDIGIT-82 | PBDIGIT-132 |
| `PBDIGIT-87` | X | X-5 — Remove plaintext debug_code exposure outside local environments | PBDIGIT-82 | PBDIGIT-132 |
| `PBDIGIT-88` | X | X-6 — Remove the session demo-flag bypass from real-election gates | PBDIGIT-83 | PBDIGIT-132 |
| `PBDIGIT-89` | X | X-10 — Fix the two voting-path TypeErrors (whitelist decode, UUID user id) | PBDIGIT-84 | PBDIGIT-132 |
| `PBDIGIT-90` | H | H-1 — Which IP is the canonical 'verified IP' (rules 1 and 4)? | — | PBDIGIT-101 · PBDIGIT-106 |
| `PBDIGIT-91` | H | H-2 — What is the registered device fingerprint (rule 5)? | — | PBDIGIT-101 · PBDIGIT-106 |
| `PBDIGIT-92` | H | H-3 — Does trust level scale the per-IP vote limit as law, or is the limit exactly N? | — | PBDIGIT-101 · PBDIGIT-108 |
| `PBDIGIT-93` | H | H-4 — Rule 7 semantics: which record counts prior votes per IP, and is the N-th or the (N+1)-th vote refused? | — | PBDIGIT-101 · PBDIGIT-106 · PBDIGIT-108 |
| `PBDIGIT-94` | H | H-5 — Where does the participation action registry live? | — | PBDIGIT-110 · PBDIGIT-146 |
| `PBDIGIT-95` | H | H-6 — What is the single platform-organisation rule? | — | PBDIGIT-120 |
| `PBDIGIT-96` | H | H-7 — Which record is the canonical 'already voted' fact, and which membership predicate is canonical? | — | PBDIGIT-105 |
| `PBDIGIT-97` | H | H-8 — Do overlays ever influence CAST_VOTE, or remain audit-only observations? | — | PBDIGIT-111 |
| `PBDIGIT-98` | H | H-9 — Is demo voting in scope for CAST_VOTE? | — | PBDIGIT-111 · PBDIGIT-113 |
| `PBDIGIT-99` | H | H-10 — Is ORM-level constitutional immutability sufficient, or is DB-level protection required? | — | PBDIGIT-103 |
| `PBDIGIT-100` | H | H-11 — How are existing elections (frozen with DB-default law) treated? | — | PBDIGIT-104 |
| `PBDIGIT-101` | L-1 | One canonical constitution representation, schema version and hash | PBDIGIT-79 · PBDIGIT-80 · PBDIGIT-90 · PBDIGIT-91 · PBDIGIT-92 · PBDIGIT-93 | PBDIGIT-102 · PBDIGIT-103 · PBDIGIT-104 · PBDIGIT-107 · PBDIGIT-108 |
| `PBDIGIT-102` | L-1 | Author constitutional law at election creation and freeze it | PBDIGIT-101 | PBDIGIT-132 |
| `PBDIGIT-103` | L-1 | Enforce constitutional immutability and verify the hash on load | PBDIGIT-101 · PBDIGIT-99 | PBDIGIT-129 |
| `PBDIGIT-104` | L-1 | Establish constitutional law for existing elections | PBDIGIT-101 · PBDIGIT-100 | PBDIGIT-132 |
| `PBDIGIT-105` | E-1 | Typed evidence: membership, lifecycle and already-voted facts | PBDIGIT-79 · PBDIGIT-80 · PBDIGIT-96 · PBDIGIT-136 | PBDIGIT-110 |
| `PBDIGIT-106` | E-1 | Typed evidence: network, device and officer-verification facts with provenance | PBDIGIT-79 · PBDIGIT-80 · PBDIGIT-90 · PBDIGIT-91 · PBDIGIT-93 | PBDIGIT-108 · PBDIGIT-110 |
| `PBDIGIT-107` | E-1 | Typed evidence: ballot/code session facts under the constitutional protocol | PBDIGIT-79 · PBDIGIT-80 · PBDIGIT-101 | PBDIGIT-110 |
| `PBDIGIT-108` | V-1 | Evaluators: network (composable binding + limit), device comparison, verification | PBDIGIT-101 · PBDIGIT-106 · PBDIGIT-92 · PBDIGIT-93 | PBDIGIT-111 · PBDIGIT-125 · PBDIGIT-126 |
| `PBDIGIT-109` | R-1 | R-1a — resolver mechanics: Resolver evaluates every policy and returns a stateless trace | PBDIGIT-76 · PBDIGIT-77 · PBDIGIT-78 · PBDIGIT-140 | PBDIGIT-110 · PBDIGIT-144 |
| `PBDIGIT-110` | R-1 | R-1b — CAST_VOTE integration: Typed CAST_VOTE context and participation action registry | PBDIGIT-109 · PBDIGIT-94 · PBDIGIT-105 · PBDIGIT-106 · PBDIGIT-107 | PBDIGIT-111 |
| `PBDIGIT-111` | R-1 | R-1b — CAST_VOTE integration: Register the CAST_VOTE resolver policy set | PBDIGIT-110 · PBDIGIT-108 · PBDIGIT-97 · PBDIGIT-98 | PBDIGIT-112 · PBDIGIT-124 |
| `PBDIGIT-112` | S-1 | Persist a CAST_VOTE decision record for every evaluation | PBDIGIT-111 | PBDIGIT-113 · PBDIGIT-114 |
| `PBDIGIT-113` | S-1 | Shadow-evaluate CAST_VOTE in the live voting path | PBDIGIT-112 · PBDIGIT-98 | PBDIGIT-115 |
| `PBDIGIT-114` | S-1 | Replay harness: reproduce stored decisions with DB access disabled | PBDIGIT-112 | PBDIGIT-129 |
| `PBDIGIT-115` | P-1 | Runtime parity harness at the HTTP boundary with persisted divergences | PBDIGIT-113 | PBDIGIT-116 |
| `PBDIGIT-116` | P-1 | Execute the 14 critical parity paths and obtain approval of expected divergences | PBDIGIT-115 | PBDIGIT-117 · PBDIGIT-137 |
| `PBDIGIT-117` | D-1 | Switch the vote-submission decision to CAST_VOTE | PBDIGIT-116 · PBDIGIT-85 · PBDIGIT-45 · PBDIGIT-135 | PBDIGIT-118 |
| `PBDIGIT-118` | D-1 | Drive the entry and ballot pages from the CAST_VOTE decision | PBDIGIT-117 | PBDIGIT-119 · PBDIGIT-120 · PBDIGIT-121 |
| `PBDIGIT-119` | D-2 | Delete the legacy trust-decision pipeline | PBDIGIT-118 | PBDIGIT-131 |
| `PBDIGIT-120` | D-2 | Delete legacy participation middleware | PBDIGIT-118 · PBDIGIT-95 | PBDIGIT-127 · PBDIGIT-131 |
| `PBDIGIT-121` | D-2 | Delete duplicate controller participation gates and per-IP authorities | PBDIGIT-118 · PBDIGIT-45 | PBDIGIT-131 |
| `PBDIGIT-122` | D-2 | Delete dead legacy code and retire tests of non-existent classes | PBDIGIT-76 · PBDIGIT-77 · PBDIGIT-78 | PBDIGIT-132 |
| `PBDIGIT-123` | CI | Bring election security zones under a blocking CI job | PBDIGIT-76 · PBDIGIT-77 · PBDIGIT-78 | PBDIGIT-124 · PBDIGIT-125 · PBDIGIT-126 · PBDIGIT-127 · PBDIGIT-128 · PBDIGIT-129 |
| `PBDIGIT-124` | CI | CI rule 1 — Only the resolver and registered policies may create CapabilityDecision | PBDIGIT-123 · PBDIGIT-111 | PBDIGIT-132 |
| `PBDIGIT-125` | CI | CI rule 2/4 — Canonical zones cannot import legacy authority; policies cannot consume policy outcomes | PBDIGIT-123 · PBDIGIT-108 | PBDIGIT-132 |
| `PBDIGIT-126` | CI | CI rule 3 — Canonical zones cannot read mutable configuration or live models | PBDIGIT-123 · PBDIGIT-108 | PBDIGIT-132 |
| `PBDIGIT-127` | CI | CI rule 5 — Snapshots cannot evaluate; middleware cannot derive participation | PBDIGIT-123 · PBDIGIT-120 | PBDIGIT-132 |
| `PBDIGIT-128` | CI | CI rule 7 — Forbidden procedural vocabulary in canonical governance code | PBDIGIT-123 | PBDIGIT-132 |
| `PBDIGIT-129` | CI | CI rule 8 — Constitutional immutability and replay sufficiency run in CI | PBDIGIT-123 · PBDIGIT-103 · PBDIGIT-114 | PBDIGIT-132 |
| `PBDIGIT-130` | DOC | Correct false completion claims in historical documents | — | PBDIGIT-132 |
| `PBDIGIT-131` | DOC | Consolidated Constitutional Governance Runtime developer guide | PBDIGIT-121 · PBDIGIT-119 · PBDIGIT-120 | PBDIGIT-132 |
| `PBDIGIT-132` | V1 | Constitutional Governance Runtime v1.0 freeze | PBDIGIT-131 · PBDIGIT-124 · PBDIGIT-125 · PBDIGIT-126 · PBDIGIT-127 · PBDIGIT-128 · PBDIGIT-129 · PBDIGIT-104 · PBDIGIT-102 · PBDIGIT-86 · PBDIGIT-87 · PBDIGIT-88 · PBDIGIT-89 · PBDIGIT-122 · PBDIGIT-130 · PBDIGIT-133 · PBDIGIT-134 · PBDIGIT-137 · PBDIGIT-138 · PBDIGIT-142 · PBDIGIT-143 · PBDIGIT-146 · PBDIGIT-147 · PBDIGIT-148 · PBDIGIT-149 | — |
| `PBDIGIT-133` | CI | Temporal-determinism audit scanner flags `validate(` as a `date()` call | — | PBDIGIT-132 |
| `PBDIGIT-134` | DOC | Resolve the `PBDIGIT-69` numbering collision | — | PBDIGIT-132 |
| `PBDIGIT-135` | C-3 | Characterize the non-slug `POST /votes` route and route-file loading | PBDIGIT-80 | PBDIGIT-117 |
| `PBDIGIT-136` | C-3 | Characterize `voter_slugs.vote_completed`, which no code writes | PBDIGIT-79 | PBDIGIT-105 |
| `PBDIGIT-137` | D-2 | Dispose of the unwired divergence-observation infrastructure | PBDIGIT-116 | PBDIGIT-132 |
| `PBDIGIT-138` | H | H-12 — Should voting codes be stored hashed at rest? | — | PBDIGIT-132 |
| `PBDIGIT-139` | GOV | Repair capability-suite and suspension-suite test drift | — | PBDIGIT-140 · PBDIGIT-149 |
| `PBDIGIT-140` | GOV | Characterize the four admin-action authorities at the HTTP boundary | PBDIGIT-139 | PBDIGIT-109 · PBDIGIT-142 · PBDIGIT-143 · PBDIGIT-144 · PBDIGIT-146 · PBDIGIT-148 |
| `PBDIGIT-141` | H | H-13 — Who owns governance role authority, and what is the one role vocabulary? | — | PBDIGIT-142 · PBDIGIT-144 · PBDIGIT-149 |
| `PBDIGIT-142` | GOV | Suspend and resume bypass ConstitutionalTransitionGuard | PBDIGIT-140 · PBDIGIT-141 | PBDIGIT-132 |
| `PBDIGIT-143` | GOV | Resume writes no audit record | PBDIGIT-140 | PBDIGIT-132 |
| `PBDIGIT-144` | GOV | Resolver role and precondition policies for admin actions | PBDIGIT-109 · PBDIGIT-141 · PBDIGIT-140 | PBDIGIT-145 |
| `PBDIGIT-145` | GOV | Remove the lifecycle engine's second capability matrix | PBDIGIT-144 | PBDIGIT-147 |
| `PBDIGIT-146` | GOV | Declare overlay behaviour in the constitution and fix the placeholder resume target | PBDIGIT-140 · PBDIGIT-94 | PBDIGIT-132 |
| `PBDIGIT-147` | GOV | Frontend renders governance capabilities only | PBDIGIT-145 | PBDIGIT-132 |
| `PBDIGIT-148` | GOV | Lifecycle derivation cannot be recomputed for a given instant | PBDIGIT-140 | PBDIGIT-132 |
| `PBDIGIT-149` | GOV | Governance lifecycle end-to-end suite through HTTP | PBDIGIT-139 · PBDIGIT-141 | PBDIGIT-132 |

---

## 6. Dependency graph

```
                         ┌──────────────── parallel, no decision needed ────────────────┐
C-1  76 · 77 · 78 ───────┼─► 109 resolver: full evaluation + stateless trace            │
        │                ├─► 122 delete dead code / retire phantom-class tests          │
        │                └─► 123 blocking CI job for election zones ──► CI rules 124-129│
        ▼
C-2  79 (entry) · 80 (ballot/store)
        │
C-3  81 · 82 · 83 · 84 ──► X fixes 85 · 86 · 87 · 88 · 89   (PO-scheduled, parallel)
        │
H    90 91 92 93 (IP, device, limit)   94 (registry)  95 (platform org)
     96 (membership/voted)  97 (overlays)  98 (demo)  99 (immutability)  100 (existing)
     ── all decidable now, in parallel; none blocks C-1…C-3 ──
        │
L-1  101 one law model ──► 102 authoring · 103 immutability+hash check (99) · 104 existing (100)
        │
E-1  105 membership/lifecycle/voted (96) · 106 network/device/verification (90 91 93) · 107 ballot (101)
        │
V-1  108 evaluators (101 106 92 93)
        │
R-1  109 ──► 110 typed context + registry (94 105 106 107) ──► 111 policy set (108 97 98)
        │
S-1  112 decision record ──► 113 shadow evaluation (98) · 114 replay harness
        │
P-1  115 parity harness ──► 116 14 paths + approved divergences
        │
D-1  117 switch store (85, PBDIGIT-45) ──► 118 entry/ballot pages
        │
D-2  119 trust pipeline · 120 middleware (95) · 121 controller gates + per-IP (PBDIGIT-45)
        │
DOC  130 claim corrections (parallel, any time) · 131 consolidated guide
        │
V1   132 freeze (after all CI rules, X fixes, 102, 104, 122, 130, 131, 133, 134, 137, 138)

Added 2026-10-01:
  133 scanner false positive · 134 PBDIGIT-69 collision · 138 H-12 code hashing   (parallel, any time)
  80 ──► 135 POST /votes characterization ──► 117 authority switch
  79 ──► 136 vote_completed characterization ──► 105 already-voted evidence
  116 ──► 137 divergence-observation disposal
```

**Safe parallel tracks:**
- **Track A** (critical path): C-1 → C-2 → C-3 → L/E/V/R/S/P/D.
- **Track B** (independent after C-1): `109`, `122`, `123`.
- **Track C** (after their pins): X fixes `85`–`89`.
- **Track D** (any time): the H decisions `90`–`100` and DOC `130`.
- **Inside E-1:** `105`, `106` and `107` are mutually independent.
- **Inside D-2:** `119`, `120` and `121` are mutually independent.
- **CI rules** `124`–`129` each start as soon as their target exists.

**Critical path** *(corrected 2026-10-01; the earlier one-line version omitted H-5, H-7, H-8 and H-9)*:

```
76/77/78 → 79/80 → [90,91,92,93] → 101 → 106 → 108 ─┐
                   [96] → 105 ──────────────────────┤
                   101 → 107 ───────────────────────┤
76/77/78 → 109 ─────────────────────────────────────┤
                   [94] ────────────────────────────┴→ 110 → [97,98] → 111 → 112 → 113 → 115 → 116
       → [85, PBDIGIT-45 accepted] → 117 → 118 → 119/120[95]/121[PBDIGIT-45] → 131 → 132
```

Every H decision gates only its real dependents. This was already so in the generated graph (§5), and only the summary line was wrong:

| Decision | Gates |
|---|---|
| H-1 `90` | law `101`, evidence `106` |
| H-2 `91` | law `101`, evidence `106` |
| H-3 `92` | law `101`, evaluator `108` |
| H-4 `93` | law `101`, evidence `106`, evaluator `108` |
| H-5 `94` | typed context / registry `110` |
| H-6 `95` | middleware deletion `120` |
| H-7 `96` | membership/already-voted evidence `105` |
| H-8 `97` | CAST_VOTE policy set `111` |
| H-9 `98` | policy set `111`, shadow `113` |
| H-10 `99` | immutability `103` |
| H-11 `100` | existing-election law `104` |

**"No cycles" is not "correct semantics".** The consistency check proves the graph is acyclic and that Blocks is the inverse of Depends-on. It does **not** prove each edge is architecturally right. Each edge is reviewed by a human at the architecture gate (§4e) before the dependent ticket is authorised.

**CI is enforced progressively.** `123` is a scaffold: it makes the suites run. Each rule ticket (`124`–`129`) is advisory until the architecture it protects exists (its dependencies), then becomes blocking. All rules are blocking by `132`. A rule must never block the migration it is meant to protect.

---

## 7. Human decision tickets — HUMAN DECISION REQUIRED

Engineering does not decide these. Each is decidable **now**, in parallel. **None blocks C-1, C-2, C-3, the X fixes, `PBDIGIT-109`, `122`, `123` or `130`.** H-3/H-4 are sub-questions of the existing `PBDIGIT-45`; that ticket's own ownership/retirement decision (ADR `ADR_20260806_1620`, PROPOSED) gates `PBDIGIT-117` and `121`.

### `PBDIGIT-90` — H-1 · HUMAN DECISION REQUIRED

| | |
|---|---|
| **Question** | Which IP is the canonical 'verified IP' (rules 1 and 4)? |
| **Evidence** | Four stores compete: `users.voting_ip` (column does not exist — X-12), `codes.client_ip` (set at code-1 verification), `voter_verifications.verified_ip` (officer), `voter_slugs.step_1_ip`. |
| **Architectural layer** | Human decision (constitutional semantics) |
| **Decision ≠ authorisation** | a signed decision does not authorise any dependent ticket; each dependent still needs its own architecture acceptance (if gated A/B/E) and PO authorisation |
| **Depends on** | — (can be decided now) |
| **Blocks** | PBDIGIT-101 · PBDIGIT-106 |
| **Not blocked by it** | C-1 · C-2 · C-3 · X fixes · `PBDIGIT-109` · `PBDIGIT-122` · `PBDIGIT-123` · `PBDIGIT-130` |
| **Acceptance** | decision block filled and signed by the PO/ARB; options and consequences stated from evidence; engineering does not choose |
| **Evidence required** | the signed decision record |

### `PBDIGIT-91` — H-2 · HUMAN DECISION REQUIRED

| | |
|---|---|
| **Question** | What is the registered device fingerprint (rule 5)? |
| **Evidence** | No registered fingerprint is acquired anywhere; candidates are the officer-captured `verified_device_fingerprint_hash` or the first fingerprint seen at code verification. |
| **Architectural layer** | Human decision (constitutional semantics) |
| **Decision ≠ authorisation** | a signed decision does not authorise any dependent ticket; each dependent still needs its own architecture acceptance (if gated A/B/E) and PO authorisation |
| **Depends on** | — (can be decided now) |
| **Blocks** | PBDIGIT-101 · PBDIGIT-106 |
| **Not blocked by it** | C-1 · C-2 · C-3 · X fixes · `PBDIGIT-109` · `PBDIGIT-122` · `PBDIGIT-123` · `PBDIGIT-130` |
| **Acceptance** | decision block filled and signed by the PO/ARB; options and consequences stated from evidence; engineering does not choose |
| **Evidence required** | the signed decision record |

### `PBDIGIT-92` — H-3 · HUMAN DECISION REQUIRED

| | |
|---|---|
| **Question** | Does trust level scale the per-IP vote limit as law, or is the limit exactly N? |
| **Evidence** | `NetworkTrustEvidence::effectiveLimit` scales N by trust level (×2 registrar, ×1.5 continuity, ×1 attested, ÷2 unverified); attestation is never populated, so today every voter gets ⌊N/2⌋. The Simplified evaluator uses N unscaled. Sub-question of the rule owned by `PBDIGIT-45`; it does not reopen `PBDIGIT-45`'s ownership/retirement question. |
| **Architectural layer** | Human decision (constitutional semantics) |
| **Decision ≠ authorisation** | a signed decision does not authorise any dependent ticket; each dependent still needs its own architecture acceptance (if gated A/B/E) and PO authorisation |
| **Depends on** | — (can be decided now) |
| **Blocks** | PBDIGIT-101 · PBDIGIT-108 |
| **Not blocked by it** | C-1 · C-2 · C-3 · X fixes · `PBDIGIT-109` · `PBDIGIT-122` · `PBDIGIT-123` · `PBDIGIT-130` |
| **Acceptance** | decision block filled and signed by the PO/ARB; options and consequences stated from evidence; engineering does not choose |
| **Evidence required** | the signed decision record |

### `PBDIGIT-93` — H-4 · HUMAN DECISION REQUIRED

| | |
|---|---|
| **Question** | Rule 7 semantics: which record counts prior votes per IP, and is the N-th or the (N+1)-th vote refused? |
| **Evidence** | Four implementations count different stores (`codes.client_ip`, `voter_slugs.step_1_ip`) with `>=`, `>` and `<=`; `PBDIGIT-45` records the same off-by-one (`helpers.php:112`) and six readers with four defaults. Sub-question of `PBDIGIT-45`. |
| **Architectural layer** | Human decision (constitutional semantics) |
| **Decision ≠ authorisation** | a signed decision does not authorise any dependent ticket; each dependent still needs its own architecture acceptance (if gated A/B/E) and PO authorisation |
| **Depends on** | — (can be decided now) |
| **Blocks** | PBDIGIT-101 · PBDIGIT-106 · PBDIGIT-108 |
| **Not blocked by it** | C-1 · C-2 · C-3 · X fixes · `PBDIGIT-109` · `PBDIGIT-122` · `PBDIGIT-123` · `PBDIGIT-130` |
| **Acceptance** | decision block filled and signed by the PO/ARB; options and consequences stated from evidence; engineering does not choose |
| **Evidence required** | the signed decision record |

### `PBDIGIT-94` — H-5 · HUMAN DECISION REQUIRED

| | |
|---|---|
| **Question** | Where does the participation action registry live? |
| **Evidence** | `ElectionConstitution::RULES` holds lifecycle transitions with target states; `cast_vote` is not a transition and must not be added there. |
| **Architectural layer** | Human decision (constitutional semantics) |
| **Decision ≠ authorisation** | a signed decision does not authorise any dependent ticket; each dependent still needs its own architecture acceptance (if gated A/B/E) and PO authorisation |
| **Depends on** | — (can be decided now) |
| **Blocks** | PBDIGIT-110 · PBDIGIT-146 |
| **Not blocked by it** | C-1 · C-2 · C-3 · X fixes · `PBDIGIT-109` · `PBDIGIT-122` · `PBDIGIT-123` · `PBDIGIT-130` |
| **Acceptance** | decision block filled and signed by the PO/ARB; options and consequences stated from evidence; engineering does not choose |
| **Evidence required** | the signed decision record |

### `PBDIGIT-95` — H-6 · HUMAN DECISION REQUIRED

| | |
|---|---|
| **Question** | What is the single platform-organisation rule? |
| **Evidence** | Three rules exist: literal `=== 1` (`VerifyVoterSlugConsistency:59-60`), `config('app.platform_organisation_id')` / 'public-digit' slug (`VoteController:122-123`), and `CodeController::getElection`. |
| **Architectural layer** | Human decision (constitutional semantics) |
| **Decision ≠ authorisation** | a signed decision does not authorise any dependent ticket; each dependent still needs its own architecture acceptance (if gated A/B/E) and PO authorisation |
| **Depends on** | — (can be decided now) |
| **Blocks** | PBDIGIT-120 |
| **Not blocked by it** | C-1 · C-2 · C-3 · X fixes · `PBDIGIT-109` · `PBDIGIT-122` · `PBDIGIT-123` · `PBDIGIT-130` |
| **Acceptance** | decision block filled and signed by the PO/ARB; options and consequences stated from evidence; engineering does not choose |
| **Evidence required** | the signed decision record |

### `PBDIGIT-96` — H-7 · HUMAN DECISION REQUIRED

| | |
|---|---|
| **Question** | Which record is the canonical 'already voted' fact, and which membership predicate is canonical? |
| **Evidence** | `has_voted` lives in codes, voter_slugs and election_memberships; membership is checked by 4 predicates that disagree on org, expiry, `suspension_status='proposed'` and inactive status. Coupled with PBDIGIT-49/65/68/69. |
| **Architectural layer** | Human decision (constitutional semantics) |
| **Decision ≠ authorisation** | a signed decision does not authorise any dependent ticket; each dependent still needs its own architecture acceptance (if gated A/B/E) and PO authorisation |
| **Depends on** | — (can be decided now) |
| **Blocks** | PBDIGIT-105 |
| **Not blocked by it** | C-1 · C-2 · C-3 · X fixes · `PBDIGIT-109` · `PBDIGIT-122` · `PBDIGIT-123` · `PBDIGIT-130` |
| **Acceptance** | decision block filled and signed by the PO/ARB; options and consequences stated from evidence; engineering does not choose |
| **Evidence required** | the signed decision record |

### `PBDIGIT-97` — H-8 · HUMAN DECISION REQUIRED

| | |
|---|---|
| **Question** | Do overlays ever influence CAST_VOTE, or remain audit-only observations? |
| **Evidence** | Overlay observations reach no decision today; a dormant path (`EvidenceCapabilityPolicy:112-125`) would HardBlock on one signal. Related: PBDIGIT-42. |
| **Architectural layer** | Human decision (constitutional semantics) |
| **Decision ≠ authorisation** | a signed decision does not authorise any dependent ticket; each dependent still needs its own architecture acceptance (if gated A/B/E) and PO authorisation |
| **Depends on** | — (can be decided now) |
| **Blocks** | PBDIGIT-111 |
| **Not blocked by it** | C-1 · C-2 · C-3 · X fixes · `PBDIGIT-109` · `PBDIGIT-122` · `PBDIGIT-123` · `PBDIGIT-130` |
| **Acceptance** | decision block filled and signed by the PO/ARB; options and consequences stated from evidence; engineering does not choose |
| **Evidence required** | the signed decision record |

### `PBDIGIT-98` — H-9 · HUMAN DECISION REQUIRED

| | |
|---|---|
| **Question** | Is demo voting in scope for CAST_VOTE? |
| **Evidence** | Demo is exempt from the constitutional gate (`type !== 'demo'`); the documented demo model (`organisation_id = NULL`) contradicts the NOT NULL schema. Related: PBDIGIT-41/66. |
| **Architectural layer** | Human decision (constitutional semantics) |
| **Decision ≠ authorisation** | a signed decision does not authorise any dependent ticket; each dependent still needs its own architecture acceptance (if gated A/B/E) and PO authorisation |
| **Depends on** | — (can be decided now) |
| **Blocks** | PBDIGIT-111 · PBDIGIT-113 |
| **Not blocked by it** | C-1 · C-2 · C-3 · X fixes · `PBDIGIT-109` · `PBDIGIT-122` · `PBDIGIT-123` · `PBDIGIT-130` |
| **Acceptance** | decision block filled and signed by the PO/ARB; options and consequences stated from evidence; engineering does not choose |
| **Evidence required** | the signed decision record |

### `PBDIGIT-99` — H-10 · HUMAN DECISION REQUIRED

| | |
|---|---|
| **Question** | Is ORM-level constitutional immutability sufficient, or is DB-level protection required? |
| **Evidence** | Probe: the guard is bypassed by `saveQuietly`/`updateQuietly`/`withoutEvents`/query-builder `update()`/`DB::table`; the model itself uses `updateQuietly` ×5 and `DB::table` ×9. Engineering recommendation on record: DB-level. |
| **Architectural layer** | Human decision (constitutional semantics) |
| **Decision ≠ authorisation** | a signed decision does not authorise any dependent ticket; each dependent still needs its own architecture acceptance (if gated A/B/E) and PO authorisation |
| **Depends on** | — (can be decided now) |
| **Blocks** | PBDIGIT-103 |
| **Not blocked by it** | C-1 · C-2 · C-3 · X fixes · `PBDIGIT-109` · `PBDIGIT-122` · `PBDIGIT-123` · `PBDIGIT-130` |
| **Acceptance** | decision block filled and signed by the PO/ARB; options and consequences stated from evidence; engineering does not choose |
| **Evidence required** | the signed decision record |

### `PBDIGIT-100` — H-11 · HUMAN DECISION REQUIRED

| | |
|---|---|
| **Question** | How are existing elections (frozen with DB-default law) treated? |
| **Evidence** | Every production election froze defaults because law cannot be authored (X-11). Keeping, re-authoring or re-freezing is itself a law decision. |
| **Architectural layer** | Human decision (constitutional semantics) |
| **Decision ≠ authorisation** | a signed decision does not authorise any dependent ticket; each dependent still needs its own architecture acceptance (if gated A/B/E) and PO authorisation |
| **Depends on** | — (can be decided now) |
| **Blocks** | PBDIGIT-104 |
| **Not blocked by it** | C-1 · C-2 · C-3 · X fixes · `PBDIGIT-109` · `PBDIGIT-122` · `PBDIGIT-123` · `PBDIGIT-130` |
| **Acceptance** | decision block filled and signed by the PO/ARB; options and consequences stated from evidence; engineering does not choose |
| **Evidence required** | the signed decision record |

### `PBDIGIT-138` — H-12 · HUMAN DECISION REQUIRED

| | |
|---|---|
| **Question** | Should voting codes be stored hashed at rest (with verification by hash), or remain plaintext? |
| **Evidence** | Codes are stored and compared in plaintext (`VoteController::verify_submitted_code:4087-4150`, `CodeController::verifyCode`); `PBDIGIT-86` removes code logging and adds constant-time comparison but does not change storage. Hashing changes code delivery/resend flows and the dual-code protocol. |
| **Architectural layer** | Human decision (constitutional semantics) |
| **Decision ≠ authorisation** | a signed decision does not authorise any dependent ticket; each dependent still needs its own architecture acceptance (if gated A/B/E) and PO authorisation |
| **Depends on** | — (can be decided now) |
| **Blocks** | PBDIGIT-132 |
| **Not blocked by it** | C-1 · C-2 · C-3 · X fixes · `PBDIGIT-109` · `PBDIGIT-122` · `PBDIGIT-123` · `PBDIGIT-130` |
| **Acceptance** | decision block filled and signed by the PO/ARB; options and consequences stated from evidence; engineering does not choose |
| **Evidence required** | the signed decision record |

### `PBDIGIT-141` — H-13 · HUMAN DECISION REQUIRED

| | |
|---|---|
| **Question** | Which component owns role authority for admin lifecycle actions — Laravel `ElectionPolicy` gates, `ConstitutionalTransitionGuard`, or a resolver Authorization policy — and which role vocabulary is canonical? |
| **Evidence** | Roles are decided in two places with two vocabularies: `ElectionPolicy::suspendElection` allows `is_super_admin` or an active `chief` officer (`:74-86`); `ElectionConstitution::RULES['suspend']` allows `chief` and `platform_admin`; the guard checks RULES roles. The resolver checks no roles at all, so the UI capability snapshot can say 'allowed' for a user the guard/policy then refuses. |
| **Architectural layer** | Human decision (constitutional semantics) |
| **Decision ≠ authorisation** | a signed decision does not authorise any dependent ticket; each dependent still needs its own architecture acceptance (if gated A/B/E) and PO authorisation |
| **Depends on** | — (can be decided now) |
| **Blocks** | PBDIGIT-142 · PBDIGIT-144 · PBDIGIT-149 |
| **Not blocked by it** | C-1 · C-2 · C-3 · X fixes · `PBDIGIT-109` · `PBDIGIT-122` · `PBDIGIT-123` · `PBDIGIT-130` |
| **Acceptance** | decision block filled and signed by the PO/ARB; options and consequences stated from evidence; engineering does not choose |
| **Evidence required** | the signed decision record |


## 8. Implementation tickets — constitution, evidence, evaluation, authority, shadow, switch

All are blocked by the H decisions they list. Until `PBDIGIT-117`, the legacy path keeps deciding.

### `PBDIGIT-101` — One canonical constitution representation, schema version and hash

| | |
|---|---|
| **Stage** | L-1 |
| **Architecture gate** | B → **ARB architecture approval required in addition to PO authorisation** |
| **Purpose** | Make one representation of election security law and one hash the only ones in the codebase. |
| **Problem** | `ConstitutionalArticlesSnapshot` (persisted, 7 fields, snake_case JSON) and Simplified `ElectionConstitutionSnapshot` (5 fields incl. `verificationRequired`, `ElectionConstitutionHasher` camelCase) produce hashes that can never match; version is the phase label `'D.2.5'`. |
| **Scope** | Consolidate to one value object + one canonical hash function + a `law_version` for the article schema; articles per readiness review §5 (composable network articles; no `trust_overlay_*`), using the H decisions available; adapters for the second representation's callers. |
| **Out of scope** | No authority switch (legacy path keeps deciding until D-1). No deletion of legacy code. No semantics beyond the recorded human decisions. No authoring UI, no immutability mechanism, no runtime consumption yet. |
| **Architectural layer** | Constitutional Law |
| **Depends on** | PBDIGIT-79 · PBDIGIT-80 · PBDIGIT-90 · PBDIGIT-91 · PBDIGIT-92 · PBDIGIT-93 |
| **Blocks** | PBDIGIT-102 · PBDIGIT-103 · PBDIGIT-104 · PBDIGIT-107 · PBDIGIT-108 |
| **Files / areas likely affected** | app/Domain/Election/Constitution/; app/Domain/Election/Security/Simplified/ElectionConstitution*.php |
| **Acceptance criteria** | Exactly one constitution class and one hash function referenced in `app/`; golden-hash test; round-trip test. |
| **Tests required** | unit + architecture (single-representation check) |
| **Architectural invariants** | One canonical constitutional representation, one canonical hash. |
| **Definition of Done** | RED→GREEN; developer guide; ADR for the article schema. |
| **Risk** | Changing hash input changes every stored hash → coupled to PBDIGIT-104 (existing elections). |
| **Evidence required** | Golden-hash test output; grep showing one representation. |

### `PBDIGIT-102` — Author constitutional law at election creation and freeze it

| | |
|---|---|
| **Stage** | L-1 |
| **Architecture gate** | B → **ARB architecture approval required in addition to PO authorisation** |
| **Purpose** | Let each election actually carry its own law. |
| **Problem** | X-11: the source columns are neither fillable nor written anywhere; every election freezes DB defaults. |
| **Scope** | Creation-time authoring of the articles (validated by the canonical schema) and freeze into the snapshot + hash. |
| **Out of scope** | No authority switch (legacy path keeps deciding until D-1). No deletion of legacy code. No semantics beyond the recorded human decisions. No post-creation amendment mechanism. |
| **Architectural layer** | Constitutional Law |
| **Depends on** | PBDIGIT-101 |
| **Blocks** | PBDIGIT-132 |
| **Files / areas likely affected** | election creation request/handler; app/Models/Election.php created hook |
| **Acceptance criteria** | A created election's snapshot reflects the authored articles; defaults only when explicitly chosen. |
| **Tests required** | feature/HTTP + unit |
| **Architectural invariants** | Election-specific law, frozen at creation. |
| **Definition of Done** | RED→GREEN; guide. |
| **Risk** | UI/UX scope creep — authoring surface is minimal. |
| **Evidence required** | Feature test output. |

### `PBDIGIT-103` — Enforce constitutional immutability and verify the hash on load

| | |
|---|---|
| **Stage** | L-1 |
| **Architecture gate** | B, G → **ARB architecture approval required in addition to PO authorisation** |
| **Purpose** | Make the law immutable at the level H-10 decides and detect tampering at runtime. |
| **Problem** | The Eloquent guard is bypassable by 5 mechanisms; nothing verifies the hash at runtime; the immutability test's name contradicts its body. |
| **Scope** | The H-10-selected protection; a load-time hash verification that refuses evaluation on mismatch; rename/replace the misleading test. |
| **Out of scope** | No authority switch (legacy path keeps deciding until D-1). No deletion of legacy code. No semantics beyond the recorded human decisions. |
| **Architectural layer** | Constitutional Law |
| **Depends on** | PBDIGIT-101 · PBDIGIT-99 |
| **Blocks** | PBDIGIT-129 |
| **Files / areas likely affected** | database/migrations (if DB-level), app/Models/Election.php, constitution loader |
| **Acceptance criteria** | Every write mechanism in the probe list fails to change law (at the chosen level); tampered hash → evaluation refused. |
| **Tests required** | integration (DB) + unit |
| **Architectural invariants** | Constitutional law is immutable after freeze; runtime verifies the hash. |
| **Definition of Done** | RED→GREEN; guide. |
| **Risk** | DB-level protection interacts with the created-hook raw update; must allow the single freeze write. |
| **Evidence required** | Integration run. |

### `PBDIGIT-104` — Establish constitutional law for existing elections

| | |
|---|---|
| **Stage** | L-1 |
| **Architecture gate** | B, G → **ARB architecture approval required in addition to PO authorisation** |
| **Purpose** | Apply the H-11 decision to elections created before authoring existed. |
| **Problem** | All existing elections froze defaults; the canonical hash change (PBDIGIT-101) invalidates stored hashes. |
| **Scope** | A migration/command implementing H-11, with an audit record per election. |
| **Out of scope** | No authority switch (legacy path keeps deciding until D-1). No deletion of legacy code. No semantics beyond the recorded human decisions. No change to votes. |
| **Architectural layer** | Constitutional Law |
| **Depends on** | PBDIGIT-101 · PBDIGIT-100 |
| **Blocks** | PBDIGIT-132 |
| **Files / areas likely affected** | database/migrations or app/Console |
| **Acceptance criteria** | Every existing election has a canonical, verified snapshot per H-11; a report lists each. |
| **Tests required** | integration |
| **Architectural invariants** | No election without verifiable law. |
| **Definition of Done** | Dry-run output reviewed by PO before real run. |
| **Risk** | Production data change — requires explicit PO authorization at execution time. |
| **Evidence required** | Dry-run and run reports. |

### `PBDIGIT-105` — Typed evidence: membership, lifecycle and already-voted facts

| | |
|---|---|
| **Stage** | E-1 |
| **Architecture gate** | C |
| **Purpose** | One acquisition point for the participation facts that are checked in 10+ places today. |
| **Problem** | Membership: 5 sites, 4 predicates; already-voted: 3 stores; lifecycle consumed as a gate in 4+ places. |
| **Scope** | Evidence builder producing typed facts (`ParticipationEligibilityEvidence`, lifecycle state, already-voted) per H-7, with provenance (source table/column, acquired-at). No interpretation. |
| **Out of scope** | No authority switch (legacy path keeps deciding until D-1). No deletion of legacy code. No semantics beyond the recorded human decisions. Existing gates untouched. |
| **Architectural layer** | Evidence |
| **Depends on** | PBDIGIT-79 · PBDIGIT-80 · PBDIGIT-96 · PBDIGIT-136 |
| **Blocks** | PBDIGIT-110 |
| **Files / areas likely affected** | app/Application/Election/ (evidence builder), app/Domain/Election/Security/Simplified/ evidence types |
| **Acceptance criteria** | Builder returns identical facts for the C-2 fixtures as the canonical predicate; no `CapabilityDecision`, no boolean 'allowed'. **Clock and replay:** the builder takes the injected `ClockInterface` (no `now()`), and records the acquisition instant and the lifecycle state it observed, so replay never re-derives a fact from the live clock. |
| **Tests required** | unit + integration |
| **Architectural invariants** | Evidence acquisition contains no constitutional interpretation. |
| **Definition of Done** | RED→GREEN; guide. |
| **Risk** | Tenant-scope interactions (PBDIGIT-65/69). |
| **Evidence required** | Test run; provenance sample. |

### `PBDIGIT-106` — Typed evidence: network, device and officer-verification facts with provenance

| | |
|---|---|
| **Stage** | E-1 |
| **Architecture gate** | C |
| **Purpose** | Replace fabricated and missing trust evidence with recorded facts. |
| **Problem** | `TrustPolicyEvaluator` hard-codes `registeredFingerprintHash=null`, `ExactMatch`, `attested=false`, `deviceChanged=false`; no registered-IP source exists (X-12); three prior-vote counters. |
| **Scope** | Adapt `TrustPolicyEvaluator`'s hashing/acquisition into typed `NetworkEvidence` (current + registered IP hash, one prior-vote counter per H-4), `DeviceEvidence` (current + registered hash per H-2), `VerificationEvidence` from `voter_verifications` (attested, officer, revoked), all with provenance. |
| **Out of scope** | No authority switch (legacy path keeps deciding until D-1). No deletion of legacy code. No semantics beyond the recorded human decisions. No policy execution inside the builder; legacy evaluator still runs for the live gate. |
| **Architectural layer** | Evidence |
| **Depends on** | PBDIGIT-79 · PBDIGIT-80 · PBDIGIT-90 · PBDIGIT-91 · PBDIGIT-93 |
| **Blocks** | PBDIGIT-108 · PBDIGIT-110 |
| **Files / areas likely affected** | app/Application/Election/Security/TrustPolicyEvaluator.php (adapt), Simplified evidence types |
| **Acceptance criteria** | No hard-coded evidence values remain in the builder; each fact carries provenance; raw IP/fingerprint never leave the builder. **Clock and replay:** the builder takes the injected `ClockInterface` (no `now()`), and records the acquisition instant and the lifecycle state it observed, so replay never re-derives a fact from the live clock. |
| **Tests required** | unit + integration |
| **Architectural invariants** | Evidence is recorded fact, never default-fabricated. |
| **Definition of Done** | RED→GREEN; guide. |
| **Risk** | Changes what the live legacy gate sees if shared — keep the legacy call path intact until D-1. |
| **Evidence required** | Test run. |

### `PBDIGIT-107` — Typed evidence: ballot/code session facts under the constitutional protocol

| | |
|---|---|
| **Stage** | E-1 |
| **Architecture gate** | C |
| **Purpose** | Feed the existing `BallotAuthorizationPolicy` from recorded code state and frozen law. |
| **Problem** | Code protocol comes from global env with broken comparisons (X-8); `can_vote_now` is used as authority; `BallotAuthorizationPolicy` reads an untyped `actionMetadata['ballot_session']`. |
| **Scope** | Build `BallotSession` from `codes` rows with protocol and session minutes from the constitution; `can_vote_now` becomes the fact 'code 1 verified'. |
| **Out of scope** | No authority switch (legacy path keeps deciding until D-1). No deletion of legacy code. No semantics beyond the recorded human decisions. |
| **Architectural layer** | Evidence |
| **Depends on** | PBDIGIT-79 · PBDIGIT-80 · PBDIGIT-101 |
| **Blocks** | PBDIGIT-110 |
| **Files / areas likely affected** | app/Domain/Election/Security/BallotAuthorization/; code evidence builder |
| **Acceptance criteria** | Single- and dual-code sessions built correctly for C-2 fixtures; protocol never read from config. |
| **Tests required** | unit + integration |
| **Architectural invariants** | Ballot protocol is law, not configuration. |
| **Definition of Done** | RED→GREEN; guide. |
| **Risk** | Coexistence with the env-driven legacy flow until D-1. |
| **Evidence required** | Test run. |

### `PBDIGIT-108` — Evaluators: network (composable binding + limit), device comparison, verification

| | |
|---|---|
| **Stage** | V-1 |
| **Architecture gate** | D |
| **Purpose** | Produce correct findings for rules 4–7 from law and evidence. |
| **Problem** | Simplified `NetworkBindingPolicy` allows the (max+1)th vote (`<=` on prior count), compares strict binding against the session-start IP, cannot combine binding and limit, has no `whitelist_only` case; `DeviceBindingPolicy` trusts a precomputed `matchType`. |
| **Scope** | Adapt the three Simplified evaluators to the canonical law and E-1 evidence per H-1/H-2/H-3/H-4; findings only (`passed`, basis, facts). |
| **Out of scope** | No authority switch (legacy path keeps deciding until D-1). No deletion of legacy code. No semantics beyond the recorded human decisions. Evaluators never return `CapabilityDecision`. |
| **Architectural layer** | Evaluation |
| **Depends on** | PBDIGIT-101 · PBDIGIT-106 · PBDIGIT-92 · PBDIGIT-93 |
| **Blocks** | PBDIGIT-111 · PBDIGIT-125 · PBDIGIT-126 |
| **Files / areas likely affected** | app/Application/Election/Security/Simplified/Policies/ |
| **Acceptance criteria** | Table-driven unit tests for every article combination, incl. exactly-N prior votes; purity test (no config/env/Eloquent/clock). |
| **Tests required** | unit + architecture (purity) |
| **Architectural invariants** | Evaluators produce findings, not authority. |
| **Definition of Done** | RED→GREEN; guide. |
| **Risk** | Semantics must match the recorded decisions exactly. |
| **Evidence required** | Test run. |

### `PBDIGIT-109` — R-1a — resolver mechanics: Resolver evaluates every policy and returns a stateless trace

| | |
|---|---|
| **Stage** | R-1 |
| **Architecture gate** | E → **ARB architecture approval required in addition to PO authorisation** |
| **Purpose** | Remove the two resolver properties that contradict the target, independently of voting. |
| **Problem** | The resolver stops at the first deny (`ElectionCapabilityResolver:45-63`; `isShortCircuit()` true for every HardBlock) and stores `lastTrace` on a container singleton (`:22`, `AppServiceProvider:284`), read by `ElectionManagementController:1384`. |
| **Scope** | Evaluate the complete policy set, derive the decision from all outcomes (deny-monotone), return decision + trace together; adapt the one caller. |
| **Out of scope** | No new actions, no policy changes, no voting path change. |
| **Architectural layer** | Authority |
| **Depends on** | PBDIGIT-76 · PBDIGIT-77 · PBDIGIT-78 · PBDIGIT-140 |
| **Blocks** | PBDIGIT-110 · PBDIGIT-144 |
| **Files / areas likely affected** | app/Application/Election/Services/ElectionCapabilityResolver.php; app/Application/Election/Capabilities/Capability{Decision,Trace}.php; ElectionManagementController::resolveCapabilities |
| **Acceptance criteria** | Admin-path capability outcomes unchanged (characterized first); trace contains every registered policy; no mutable state on the resolver. |
| **Tests required** | unit + feature (admin capability page) |
| **Architectural invariants** | The resolver contains no mutable per-request state and evaluates the complete policy set. |
| **Definition of Done** | Admin-path characterization before, RED→GREEN, guide. |
| **Risk** | Reported denial reason for admin actions may change (order no longer decides it) — characterize first. |
| **Evidence required** | Before/after admin capability characterization. |

### `PBDIGIT-110` — R-1b — CAST_VOTE integration: Typed CAST_VOTE context and participation action registry

| | |
|---|---|
| **Stage** | R-1 |
| **Architecture gate** | E → **ARB architecture approval required in addition to PO authorisation** |
| **Purpose** | Give the resolver a typed, model-free input for voting. |
| **Problem** | `CapabilityContext` carries live `Election`/`User` Eloquent models and an untyped `actionMetadata` used for both rules and evidence; no `cast_vote` action exists; denial reasons are too coarse to replay. |
| **Scope** | Typed context fields per readiness review §10; `cast_vote` in the registry chosen by H-5; denial reasons `NotAMember`, `AlreadyVoted`, `BallotProtocolUnmet`, `NetworkBindingViolated`, `NetworkLimitReached`, `DeviceBindingViolated`, `VerificationMissing`. |
| **Out of scope** | No authority switch (legacy path keeps deciding until D-1). No deletion of legacy code. No semantics beyond the recorded human decisions. Admin actions keep working (adapter for their context). |
| **Architectural layer** | Authority |
| **Depends on** | PBDIGIT-109 · PBDIGIT-94 · PBDIGIT-105 · PBDIGIT-106 · PBDIGIT-107 |
| **Blocks** | PBDIGIT-111 |
| **Files / areas likely affected** | app/Application/Election/Capabilities/ |
| **Acceptance criteria** | The CAST_VOTE context contains no Eloquent model and no untyped metadata; architecture test enforces it. |
| **Tests required** | unit + architecture |
| **Architectural invariants** | The canonical context carries facts, not live models or policy outcomes. |
| **Definition of Done** | RED→GREEN; guide. |
| **Risk** | Admin path regression — covered by PBDIGIT-109's characterization. |
| **Evidence required** | Test run. |

### `PBDIGIT-111` — R-1b — CAST_VOTE integration: Register the CAST_VOTE resolver policy set

| | |
|---|---|
| **Stage** | R-1 |
| **Architecture gate** | A, E → **ARB architecture approval required in addition to PO authorisation** |
| **Purpose** | Make the resolver able to decide CAST_VOTE (not yet authoritative). |
| **Problem** | `BallotAuthorizationPolicy` and `VoterVerificationPolicy` exist but are unregistered; membership and already-voted have no policy; `EvidenceCapabilityPolicy` maps legacy trust states and has a dormant overlay HardBlock. |
| **Scope** | Register Lifecycle, Overlay(suspension), Membership, AlreadyVoted, BallotAuthorization, VoterVerification and the findings-interpreting `EvidenceCapabilityPolicy` (redesigned) for `cast_vote`; overlay handling per H-8; demo per H-9. |
| **Out of scope** | No authority switch (legacy path keeps deciding until D-1). No deletion of legacy code. No semantics beyond the recorded human decisions. |
| **Architectural layer** | Authority |
| **Depends on** | PBDIGIT-110 · PBDIGIT-108 · PBDIGIT-97 · PBDIGIT-98 |
| **Blocks** | PBDIGIT-112 · PBDIGIT-124 |
| **Files / areas likely affected** | app/Application/Election/Capabilities/Policies/, Policy/; AppServiceProvider resolver binding |
| **Acceptance criteria** | Unit matrix over the 14 parity paths yields the expected decisions; only registered policies emit `CapabilityDecision`. |
| **Tests required** | unit + architecture |
| **Architectural invariants** | Only the resolver + registered policies produce CAST_VOTE authority. |
| **Definition of Done** | RED→GREEN; guide. |
| **Risk** | Registering for admin actions by accident — policies scoped by action. |
| **Evidence required** | Test run. |

### `PBDIGIT-112` — Persist a CAST_VOTE decision record for every evaluation

| | |
|---|---|
| **Stage** | S-1 |
| **Architecture gate** | G |
| **Purpose** | Make every decision reconstructible without live state. |
| **Problem** | Security events are sampled with `rand()` (~90% of allows lost), carry no constitution hash or outcome, and version columns are static defaults. |
| **Scope** | Decision-record table and writer per readiness review §12 (every decision, constitution hash + law_version, evidence + provenance, findings, observations, decision + trace, evaluator version, decided_at, random decision id). Record also the **serializer version** and the **hash-algorithm version** used for every hash in the record. |
| **Out of scope** | No authority switch (legacy path keeps deciding until D-1). No deletion of legacy code. No semantics beyond the recorded human decisions. Never store vote_id, selections, codes, raw IP or raw fingerprint; never joinable to `votes`. |
| **Architectural layer** | Persistence / Replay |
| **Depends on** | PBDIGIT-111 |
| **Blocks** | PBDIGIT-113 · PBDIGIT-114 |
| **Files / areas likely affected** | database/migrations (new table), app/ persistence adapter |
| **Acceptance criteria** | Anonymity test: no column/value links a record to a vote; completeness test against the contract. Serializer and hash-algorithm versions present on every record. |
| **Tests required** | integration + architecture (anonymity) |
| **Architectural invariants** | Every CAST_VOTE decision is persisted; voter↔vote unlinkability preserved (ADR-T11). |
| **Definition of Done** | RED→GREEN; guide. |
| **Risk** | Anonymity leakage via timestamps/correlation — review with ADR-T11 owner. |
| **Evidence required** | Test run; schema review. |

### `PBDIGIT-113` — Shadow-evaluate CAST_VOTE in the live voting path

| | |
|---|---|
| **Stage** | S-1 |
| **Architecture gate** | G |
| **Purpose** | Run the resolver beside the legacy authority on real requests without changing outcomes. |
| **Problem** | There is no way to observe what the resolver would decide on real traffic. |
| **Scope** | At each legacy decision point, build the CAST_VOTE context, evaluate, write the decision record; legacy outcome remains authoritative. |
| **Out of scope** | No authority change; no user-visible behaviour change (C-2 suite stays green unchanged). |
| **Architectural layer** | Parity |
| **Depends on** | PBDIGIT-112 · PBDIGIT-98 |
| **Blocks** | PBDIGIT-115 |
| **Files / areas likely affected** | VoteController, CodeController, ElectionVotingController (call sites only) |
| **Acceptance criteria** | C-2 characterization suite unchanged and green; a decision record per evaluation. |
| **Tests required** | feature/HTTP |
| **Architectural invariants** | Authority unchanged while evidence of the new path accumulates. |
| **Definition of Done** | Guide; C-2 run before/after. |
| **Risk** | Shadow failure must never break voting — isolated, failures recorded. |
| **Evidence required** | C-2 run; records produced. |

### `PBDIGIT-114` — Replay harness: reproduce stored decisions with DB access disabled

| | |
|---|---|
| **Stage** | S-1 |
| **Architecture gate** | — |
| **Purpose** | Prove the replay property. |
| **Problem** | `ReplayDeterminismAuditTest` re-runs evaluators in memory and never reads stored data. |
| **Scope** | Load a decision record, rebuild inputs from it alone, evaluate the resolver with DB access blocked, compare. |
| **Out of scope** | No change to decision logic. |
| **Architectural layer** | Replay |
| **Depends on** | PBDIGIT-112 |
| **Blocks** | PBDIGIT-129 |
| **Files / areas likely affected** | tests/Replay/ (GreenfieldCore already includes tests/Replay) |
| **Acceptance criteria** | Replay equality over the 14 parity paths' records; the test fails if the resolver touches the DB. |
| **Tests required** | replay |
| **Architectural invariants** | Same constitution + same evidence = same constitutional outcome. |
| **Definition of Done** | In a blocking CI suite. |
| **Risk** | Hidden inputs (clock, config) surface here — that is the point. |
| **Evidence required** | Replay run. |

### `PBDIGIT-117` — Switch the vote-submission decision to CAST_VOTE

| | |
|---|---|
| **Stage** | D-1 |
| **Architecture gate** | A, E → **ARB architecture approval required in addition to PO authorisation** |
| **Purpose** | Make the resolver the voting authority at `store`. |
| **Problem** | `VoteController::store` decides via `ConstitutionalLegitimacyDecision` + `canVote()` + membership. |
| **Scope** | `store` acts on the CAST_VOTE decision only; lock, in-lock has-voted persistence invariant and credential check remain. |
| **Out of scope** | Legacy code not yet deleted (D-2). |
| **Architectural layer** | Authority |
| **Depends on** | PBDIGIT-116 · PBDIGIT-85 · PBDIGIT-45 · PBDIGIT-135 |
| **Blocks** | PBDIGIT-118 |
| **Files / areas likely affected** | VoteController::store |
| **Acceptance criteria** | Parity paths green with the new expectations; legacy gates in `store` unreachable. |
| **Tests required** | feature/HTTP + parity |
| **Architectural invariants** | Controllers do not independently decide participation. |
| **Definition of Done** | Release note; guide. |
| **Risk** | Live-election behaviour change — release outside an open election window. |
| **Evidence required** | Parity + C-2 runs. |

### `PBDIGIT-118` — Drive the entry and ballot pages from the CAST_VOTE decision

| | |
|---|---|
| **Stage** | D-1 |
| **Architecture gate** | A, E → **ARB architecture approval required in addition to PO authorisation** |
| **Purpose** | Remove participation decisions from the entry path and page projections. |
| **Problem** | `elections.start`, code create/store, agreement, `create`, `first_submission`, `verify` each decide participation independently. |
| **Scope** | These sites project/act on the CAST_VOTE decision; transport and flow redirects remain. |
| **Out of scope** | No deletion yet. |
| **Architectural layer** | Authority |
| **Depends on** | PBDIGIT-117 |
| **Blocks** | PBDIGIT-119 · PBDIGIT-120 · PBDIGIT-121 |
| **Files / areas likely affected** | ElectionVotingController, CodeController, VoteController (create, first_submission, verify) |
| **Acceptance criteria** | Each C-2 gate now reads the CAST_VOTE decision (architecture test); parity green. |
| **Tests required** | feature/HTTP + architecture |
| **Architectural invariants** | As PBDIGIT-117. |
| **Definition of Done** | Guide. |
| **Risk** | As PBDIGIT-117. |
| **Evidence required** | As PBDIGIT-117. |


## 9. Security-fix tickets

Defects under any architecture (readiness review §18 step 4). Each depends only on its C-3 pin; **scheduling is the PO's**. Not merged into the migration.

### `PBDIGIT-85` — X-3 — Release the vote lock and roll back on the store IP-denial branch

| | |
|---|---|
| **Stage** | X |
| **Architecture gate** | G |
| **Purpose** | Remove defect X-3, which is a defect under any architecture. |
| **Problem** | Denied `store` requests leave an open transaction and a held 10 s lock (`VoteController:1640-1673`). |
| **Scope** | RED→GREEN: flip the X-3 pin (the pin's expectation is replaced by the corrected expectation in the same commit, labelled), minimal production change. |
| **Out of scope** | Only the named defect. No sovereignty migration, no resolver change, no refactor of neighbouring code. Other X-defects stay pinned. |
| **Architectural layer** | Security defect |
| **Depends on** | PBDIGIT-81 |
| **Blocks** | PBDIGIT-117 |
| **Files / areas likely affected** | app/Http/Controllers/VoteController.php (store IP-denial branch only) |
| **Acceptance criteria** | The X-3 pin now asserts the corrected behaviour and passes; all C-2 characterization tests unchanged and green except where the ticket names an intended change. |
| **Tests required** | feature/HTTP (+unit where the defect is below HTTP) |
| **Architectural invariants** | Voting-code secrecy / transactional integrity / gate integrity (as applicable). |
| **Definition of Done** | RED commit then GREEN commit; developer-guide note; PO scheduling recorded (readiness review §18 step 4). |
| **Risk** | Low; narrowly scoped. |
| **Evidence required** | RED and GREEN runs; production diff limited to the named site. |

### `PBDIGIT-86` — X-4 — Stop logging voting codes and compare codes in constant time

| | |
|---|---|
| **Stage** | X |
| **Architecture gate** | F |
| **Purpose** | Remove defect X-4, which is a defect under any architecture. |
| **Problem** | Expected and submitted voting codes are written to logs in plaintext (`VoteController:4087-4150`, `CodeController:881-885`). |
| **Scope** | RED→GREEN: flip the X-4 pin (the pin's expectation is replaced by the corrected expectation in the same commit, labelled), minimal production change. |
| **Out of scope** | Only the named defect. No sovereignty migration, no resolver change, no refactor of neighbouring code. Other X-defects stay pinned. |
| **Architectural layer** | Security defect |
| **Depends on** | PBDIGIT-82 |
| **Blocks** | PBDIGIT-132 |
| **Files / areas likely affected** | VoteController::verify_submitted_code, CodeController::verifyCode (log statements, comparison) |
| **Acceptance criteria** | The X-4 pin now asserts the corrected behaviour and passes; all C-2 characterization tests unchanged and green except where the ticket names an intended change. |
| **Tests required** | feature/HTTP (+unit where the defect is below HTTP) |
| **Architectural invariants** | Voting-code secrecy / transactional integrity / gate integrity (as applicable). |
| **Definition of Done** | RED commit then GREEN commit; developer-guide note; PO scheduling recorded (readiness review §18 step 4). |
| **Risk** | Low; narrowly scoped. |
| **Evidence required** | RED and GREEN runs; production diff limited to the named site. |

### `PBDIGIT-87` — X-5 — Remove plaintext debug_code exposure outside local environments

| | |
|---|---|
| **Stage** | X |
| **Architecture gate** | F |
| **Purpose** | Remove defect X-5, which is a defect under any architecture. |
| **Problem** | `debug_code` sends the plaintext code to the UI whenever the user has no valid email (`VoteController:2426,2442`). |
| **Scope** | RED→GREEN: flip the X-5 pin (the pin's expectation is replaced by the corrected expectation in the same commit, labelled), minimal production change. |
| **Out of scope** | Only the named defect. No sovereignty migration, no resolver change, no refactor of neighbouring code. Other X-defects stay pinned. |
| **Architectural layer** | Security defect |
| **Depends on** | PBDIGIT-82 |
| **Blocks** | PBDIGIT-132 |
| **Files / areas likely affected** | VoteController::verify (debug_code prop) |
| **Acceptance criteria** | The X-5 pin now asserts the corrected behaviour and passes; all C-2 characterization tests unchanged and green except where the ticket names an intended change. |
| **Tests required** | feature/HTTP (+unit where the defect is below HTTP) |
| **Architectural invariants** | Voting-code secrecy / transactional integrity / gate integrity (as applicable). |
| **Definition of Done** | RED commit then GREEN commit; developer-guide note; PO scheduling recorded (readiness review §18 step 4). |
| **Risk** | Low; narrowly scoped. |
| **Evidence required** | RED and GREEN runs; production diff limited to the named site. |

### `PBDIGIT-88` — X-6 — Remove the session demo-flag bypass from real-election gates

| | |
|---|---|
| **Stage** | X |
| **Architecture gate** | A → **ARB architecture approval required in addition to PO authorisation** |
| **Purpose** | Remove defect X-6, which is a defect under any architecture. |
| **Problem** | A stale `selected_election_type=demo` session value skips lifecycle/eligibility gates on real routes (`VoteEligibility:36`, `CodeController:683-700`). |
| **Scope** | RED→GREEN: flip the X-6 pin (the pin's expectation is replaced by the corrected expectation in the same commit, labelled), minimal production change. |
| **Out of scope** | Only the named defect. No sovereignty migration, no resolver change, no refactor of neighbouring code. Other X-defects stay pinned. |
| **Architectural layer** | Security defect |
| **Depends on** | PBDIGIT-83 |
| **Blocks** | PBDIGIT-132 |
| **Files / areas likely affected** | app/Http/Middleware/VoteEligibility.php; CodeController::isUserEligible |
| **Acceptance criteria** | The X-6 pin now asserts the corrected behaviour and passes; all C-2 characterization tests unchanged and green except where the ticket names an intended change. |
| **Tests required** | feature/HTTP (+unit where the defect is below HTTP) |
| **Architectural invariants** | Voting-code secrecy / transactional integrity / gate integrity (as applicable). |
| **Definition of Done** | RED commit then GREEN commit; developer-guide note; PO scheduling recorded (readiness review §18 step 4). |
| **Risk** | X-6: removing the bypass may block demo users who reach real routes; C-2 tells which paths are affected. |
| **Evidence required** | RED and GREEN runs; production diff limited to the named site. |

### `PBDIGIT-89` — X-10 — Fix the two voting-path TypeErrors (whitelist decode, UUID user id)

| | |
|---|---|
| **Stage** | X |
| **Architecture gate** | A, C, F → **ARB architecture approval required in addition to PO authorisation** |
| **Purpose** | Remove defect X-10, which is a defect under any architecture. |
| **Problem** | A non-empty whitelist throws in `TrustPolicyEvaluator:140`; `trackSovereigntyDivergence(?int $userId)` throws on UUID users (`VoteController:1370`). |
| **Scope** | RED→GREEN: flip the X-10 pin (the pin's expectation is replaced by the corrected expectation in the same commit, labelled), minimal production change. |
| **Out of scope** | Only the named defect. No sovereignty migration, no resolver change, no refactor of neighbouring code. Other X-defects stay pinned. |
| **Architectural layer** | Security defect |
| **Depends on** | PBDIGIT-84 |
| **Blocks** | PBDIGIT-132 |
| **Files / areas likely affected** | TrustPolicyEvaluator::buildNetworkEvidence; VoteController::trackSovereigntyDivergence signature |
| **Acceptance criteria** | The X-10 pin now asserts the corrected behaviour and passes; all C-2 characterization tests unchanged and green except where the ticket names an intended change. |
| **Tests required** | feature/HTTP (+unit where the defect is below HTTP) |
| **Architectural invariants** | Voting-code secrecy / transactional integrity / gate integrity (as applicable). |
| **Definition of Done** | RED commit then GREEN commit; developer-guide note; PO scheduling recorded (readiness review §18 step 4). |
| **Risk** | X-10: fixing the whitelist decode activates whitelist logic that was effectively dead (hash-vs-raw comparison stays a V-1/E-1 matter). |
| **Evidence required** | RED and GREEN runs; production diff limited to the named site. |


## 10. Characterization tickets (C-1, C-2, C-3)

**C-1 explicitly prohibits:** production changes · weakening assertions · deleting, skipping or marking tests incomplete · changing expected behaviour to make tests green. **C-1 completion condition:** the 38 affected real-election voting feature-test files execute without infrastructure errors, with remaining failures classified as genuine behavioural observations.

### `PBDIGIT-76` — Repair voting feature-test fixtures for current schema (retired columns)

| | |
|---|---|
| **Stage** | C-1 |
| **Architecture gate** | — |
| **Purpose** | Make the real-election voting feature tests execute against the current schema so they can observe current behaviour. |
| **Problem** | 109 of 218 failing tests error before asserting: fixtures write `users.is_voter`/`users.can_vote` (no active migration creates them; they exist only in `database/migrations_backup_*`) and `posts.post_id` (the posts table has only `id`). Voter status is now an `election_memberships` row (role voter, status active). |
| **Scope** | Test code and factories only. Translate each fixture's intent: 'user is a voter' → an active voter `ElectionMembership` for the election (current model); 'not a voter' → no membership. Replace `post_id` fixture keys with the current `posts` columns. |
| **Out of scope** | No production code (`app/`, `routes/`, `config/`, `database/migrations/`). No assertion weakened, deleted, skipped or marked incomplete. No change to expected behaviour. No real test converted to demo. No session flags added to pass. Production references to `is_voter` (`User.php:111,415,433,463,723-825`) are `PBDIGIT-35`, not this ticket. **C-1 governing rule:** C-1 repairs the test environment's representation of the CURRENT production contract; it must not repair, restore, reinterpret or redesign production voting authority. A removed production API (e.g. `ElectionMembership::assignVoter()`, removed in `ba4cfa9c1`) is never restored. Each broken fixture is classified as (a) translation to the current contract, (b) obsolete test → recorded disposition, or (c) genuine production defect → a NEW PBDIGIT ticket; C-1 never decides (c) by changing code. |
| **Architectural layer** | Test infrastructure |
| **Depends on** | — |
| **Blocks** | PBDIGIT-79 · PBDIGIT-80 · PBDIGIT-109 · PBDIGIT-122 · PBDIGIT-123 |
| **Files / areas likely affected** | tests/Feature/{VoteSubmissionWorkflowTest, RealElection/EndpointRoutingTest, Phase2VoteValidationTest, Phase5ErrorHandlingTest, Phase6EndToEndIntegrationTest, ExistingVotingControllersIntegrationTest, VoterStepWorkflowTest, VoterWorkflowIntegrationTest, MultipleVotePreventionTest, DoubleVoteDebugTest}.php; possibly database/factories (only if a shared fixture is the source) |
| **Acceptance criteria** | Zero `Undefined column` errors for `is_voter`, `can_vote` or `post_id` across the 38 files. Every test in the 10 files reaches its assertions. Each fixture translation is listed (old attribute → current fact). |
| **Tests required** | feature/HTTP (existing tests only) |
| **Architectural invariants** | The test suite observes the production schema as it is; production eligibility semantics are not altered to fit tests. |
| **Definition of Done** | Run of the 38-file set recorded before/after with exact totals; changed-file list; remaining failures classified BEHAVIOURAL with cause. |
| **Risk** | Translating `is_voter` to a membership can silently change a scenario's meaning (e.g. tests of the legacy `POST /votes` path that relies on `User::isEligibleToVote`). Mitigation: per-test note; such tests become BEHAVIOURAL observations, not rewrites. |
| **Evidence required** | JUnit before/after, per-category counts, `git diff --stat -- app routes config database` empty. |

### `PBDIGIT-77` — Repair voting feature-test tenancy, factory, constructor and route fixtures

| | |
|---|---|
| **Stage** | C-1 |
| **Architecture gate** | — |
| **Purpose** | Remove the non-schema infrastructure errors so the tests reach their assertions. |
| **Problem** | 84 factory/tenancy errors: duplicate `user_organisation_roles` (UserFactory::configure() already creates the row, tests `attach()` again, 42); demo election with `organisation_id = null` against a NOT NULL column (20); memberships/votes created without organisation or tenant session (12); tenant-scoped `find`/`update` returning nothing (10). Plus `ElectionMembership::assignVoter()` removed in `ba4cfa9c1` (VoteControllerTest), `new VoteController()` with 0 of 2 constructor args (4+ sites), stale route name `voter.code.create` and web-group expectation for `TenantContext` (2). |
| **Scope** | Test code/factories only: set existing pivot roles instead of inserting; give fixtures the organisation and tenant session current tenancy rules require; replace `assignVoter` with an equivalent membership fixture; resolve controllers through the container; use current route names; update the web-group expectation only because `bootstrap/app.php:38-43` documents the intentional move. |
| **Out of scope** | No production code (`app/`, `routes/`, `config/`, `database/migrations/`). No assertion weakened, deleted, skipped or marked incomplete. No change to expected behaviour. No real test converted to demo. No session flags added to pass. No weakening of `BelongsToTenant`, `BaseVote` organisation checks or the users↔organisation invariant. **C-1 governing rule:** C-1 repairs the test environment's representation of the CURRENT production contract; it must not repair, restore, reinterpret or redesign production voting authority. A removed production API (e.g. `ElectionMembership::assignVoter()`, removed in `ba4cfa9c1`) is never restored. Each broken fixture is classified as (a) translation to the current contract, (b) obsolete test → recorded disposition, or (c) genuine production defect → a NEW PBDIGIT ticket; C-1 never decides (c) by changing code. |
| **Architectural layer** | Test infrastructure |
| **Depends on** | — |
| **Blocks** | PBDIGIT-79 · PBDIGIT-80 · PBDIGIT-109 · PBDIGIT-122 · PBDIGIT-123 |
| **Files / areas likely affected** | tests/Feature/{VoteControllerTest, VoteVerifyTest, Election/ElectionOnlyEntitlementPinTest, Integration/VotingMembershipIntegrationTest, Audit/VoteControllerAuditTest, RealElection/RoutingTest, IpRestriction/ElectionIpRestrictionTest, VoteVerificationTest, RealVoteVerificationTest, VoteSubmissionRedirectTest, VoterStepWorkflowTest, Bootstrap/BootstrapAppConfigurationTest}.php |
| **Acceptance criteria** | Zero unique-violation, NOT-NULL `organisation_id`, `OrganisationMismatchException`, null-model, `ArgumentCountError` and undefined-route errors across the 38 files. |
| **Tests required** | feature/HTTP (existing tests only) |
| **Architectural invariants** | Tenant isolation rules remain as enforced in production; tests satisfy them rather than bypass them. |
| **Definition of Done** | As PBDIGIT-76. |
| **Risk** | Demo fixtures: the documented demo model (`organisation_id = NULL`) no longer matches the schema. Choosing a demo organisation for fixtures must not decide H-9 (PBDIGIT-98); fixtures use whatever current production demo creation does. |
| **Evidence required** | As PBDIGIT-76. |

### `PBDIGIT-78` — Repair voting-active lifecycle fixtures to current lifecycle rules

| | |
|---|---|
| **Stage** | C-1 |
| **Architecture gate** | — |
| **Purpose** | Let tests that need an election in `voting_active` actually get one. |
| **Problem** | 19 tests assume voting is open with only `status`/`start_date`/`end_date` or `state='voting'`; the engine (`ElectionLifecycleEngineImpl.php:111`) requires `voting_locked` + open window + an approved candidacy (rules from `f2c2cc4ee` PBDIGIT-64 and `606c362aa`). Probe: adding both yields `voting_active`. |
| **Scope** | Test fixtures/factory states only: construct elections satisfying the current rules through real data (no mocking the engine). |
| **Out of scope** | No production code (`app/`, `routes/`, `config/`, `database/migrations/`). No assertion weakened, deleted, skipped or marked incomplete. No change to expected behaviour. No real test converted to demo. No session flags added to pass. No change to the lifecycle engine, `ElectionFactory` semantics used by unrelated tests, or `VotingActive` conditions. **C-1 governing rule:** C-1 repairs the test environment's representation of the CURRENT production contract; it must not repair, restore, reinterpret or redesign production voting authority. A removed production API (e.g. `ElectionMembership::assignVoter()`, removed in `ba4cfa9c1`) is never restored. Each broken fixture is classified as (a) translation to the current contract, (b) obsolete test → recorded disposition, or (c) genuine production defect → a NEW PBDIGIT ticket; C-1 never decides (c) by changing code. |
| **Architectural layer** | Test infrastructure |
| **Depends on** | — |
| **Blocks** | PBDIGIT-79 · PBDIGIT-80 · PBDIGIT-109 · PBDIGIT-122 · PBDIGIT-123 |
| **Files / areas likely affected** | tests/Feature/{CodeControllerTest, ExpiredCodeAutoRegenerationTest, Election/ElectionShowControllerTest, Election/VotingEntitlementAmbientInvarianceTest, Vote/VoteControllerConstitutionalTest, VoteSubmissionRedirectTest}.php; possibly a new opt-in `ElectionFactory` state |
| **Acceptance criteria** | The 19 tests reach their assertions with the engine reporting `voting_active` where the scenario requires it; any remaining failure is BEHAVIOURAL. |
| **Tests required** | feature/HTTP (existing tests only) |
| **Architectural invariants** | `VotingActive` is derived only by the lifecycle engine from recorded facts. |
| **Definition of Done** | As PBDIGIT-76; plus: the 38 files run with 0 infrastructure errors (the C-1 exit condition). |
| **Risk** | A shared factory state could change other suites. Mitigation: opt-in state, run the full Feature suite before/after. |
| **Evidence required** | As PBDIGIT-76; full Feature-suite before/after comparison. |

### `PBDIGIT-79` — Characterize the voting entry path at the HTTP boundary

| | |
|---|---|
| **Stage** | C-2 |
| **Architecture gate** | — |
| **Purpose** | Record what the system does today at every gate a voter crosses before the ballot. |
| **Problem** | The entry gates (`elections.start` membership/has-voted/canVote/resolveIpBlock; code create/store: membership skip-with-slug, has_voted, `isUserEligible`, code expiry, IP cap `>= config(app.max_use_clientIP)`; agreement: can_vote_now/has_voted; middleware `voter.slug.*`, `ensure.election.voter`, `vote.eligibility`, `validate.voting.ip`, `vote.organisation`) have no HTTP-level test of current accept/reject behaviour. |
| **Scope** | One `@characterization` HTTP test per gate listed in the readiness review §7/§8 (entry half), asserting today's outcome for its triggering fact. |
| **Out of scope** | No production fix. Tests pin CURRENT behaviour (label `@characterization`), including defects. No redesign, no CAST_VOTE, no resolver change. |
| **Architectural layer** | Characterization |
| **Depends on** | PBDIGIT-76 · PBDIGIT-77 · PBDIGIT-78 |
| **Blocks** | PBDIGIT-83 · PBDIGIT-84 · PBDIGIT-101 · PBDIGIT-105 · PBDIGIT-106 · PBDIGIT-107 · PBDIGIT-136 |
| **Files / areas likely affected** | new tests under tests/Feature/Characterization/Voting/ (placement per test conventions) |
| **Acceptance criteria** | Each entry gate has one passing test that fails if the gate's current outcome changes. Gate list ↔ test list mapping in the ticket. |
| **Tests required** | feature/HTTP |
| **Architectural invariants** | Knowledge of current participation authority is preserved before any of it moves. |
| **Definition of Done** | Mapping table gate→test; run recorded; no production diff. |
| **Risk** | Tests that encode a defect look like endorsement. Mitigation: `@characterization` + a pointer to the X-id or later ticket that will change it. |
| **Evidence required** | Run output; gate→test table; empty production diff. |

### `PBDIGIT-80` — Characterize the ballot, verify and store path at the HTTP boundary

| | |
|---|---|
| **Stage** | C-2 |
| **Architecture gate** | — |
| **Purpose** | Record current behaviour of every participation gate from ballot page to persisted vote. |
| **Problem** | `VoteController::create/first_submission/verify/store` gates (canVote ×2, membership cached/fresh, `isUserEligibleToVote` via can_vote_now, agreement, `validateVotingIpWithResponse`, has_voted, trust→`ConstitutionalLegitimacyDecision` gate, lock + in-lock has_voted, credential match) and `voting.active` lack current-behaviour HTTP tests. |
| **Scope** | One `@characterization` HTTP test per gate (readiness review §8, ballot half), including the trust gate's current outcomes for the reachable states. |
| **Out of scope** | No production fix. Tests pin CURRENT behaviour (label `@characterization`), including defects. No redesign, no CAST_VOTE, no resolver change. |
| **Architectural layer** | Characterization |
| **Depends on** | PBDIGIT-76 · PBDIGIT-77 · PBDIGIT-78 |
| **Blocks** | PBDIGIT-81 · PBDIGIT-82 · PBDIGIT-84 · PBDIGIT-101 · PBDIGIT-105 · PBDIGIT-106 · PBDIGIT-107 · PBDIGIT-135 |
| **Files / areas likely affected** | tests/Feature/Characterization/Voting/ |
| **Acceptance criteria** | As PBDIGIT-79 for the ballot half. |
| **Tests required** | feature/HTTP |
| **Architectural invariants** | As PBDIGIT-79. |
| **Definition of Done** | As PBDIGIT-79. |
| **Risk** | Trust-gate tests depend on hard-coded evidence (`attested=false`, `ExactMatch`); they must pin that, not work around it. |
| **Evidence required** | As PBDIGIT-79. |

### `PBDIGIT-81` — Pin voting-flow control defects X-1, X-2, X-3

| | |
|---|---|
| **Stage** | C-3 |
| **Architecture gate** | — |
| **Purpose** | Make the three flow defects explicit and detectable before anyone changes the flow. |
| **Problem** | X-1 `store` ignores `$pre_check = vote_post_check(...)` (`VoteController:1726`); X-2 `first_submission` sets `vote_submitted` before `vote_pre_check`, which then returns early (`:538-545`, `:2944-2947`); X-3 the IP-denial branch in `store` returns without `$lock->release()`/`DB::rollBack()` (`:1640-1673`). |
| **Scope** | One pinning test per defect demonstrating the current behaviour. |
| **Out of scope** | No production fix. Tests pin CURRENT behaviour (label `@characterization`), including defects. No redesign, no CAST_VOTE, no resolver change. |
| **Architectural layer** | Characterization |
| **Depends on** | PBDIGIT-80 |
| **Blocks** | PBDIGIT-85 |
| **Files / areas likely affected** | tests/Feature/Characterization/Voting/Defects/ |
| **Acceptance criteria** | Three tests, each failing if its defect disappears (so the later fix is a visible RED→GREEN flip). **One test per X-id, the X-id in the test name**, so each defect stays individually traceable (matrix §4d). |
| **Tests required** | feature/HTTP (X-3 may need an integration test observing lock/transaction state) |
| **Architectural invariants** | Known defects cannot vanish or change unnoticed during migration. |
| **Definition of Done** | Tests committed with the X-id in the name; run recorded. |
| **Risk** | X-3 is timing/state dependent; the pin must observe lock ownership deterministically (lock key from the code). |
| **Evidence required** | Run output showing the three pins passing on current code. |

### `PBDIGIT-82` — Pin voting-code secrecy defects X-4, X-5

| | |
|---|---|
| **Stage** | C-3 |
| **Architecture gate** | — |
| **Purpose** | Document code-secrecy exposure precisely before it is fixed. |
| **Problem** | X-4 codes compared in plaintext and expected/submitted codes logged (`VoteController::verify_submitted_code:4087-4150`, `CodeController:881-885`); X-5 `debug_code` exposes the plaintext code whenever the user lacks a valid email (`VoteController:2426,2442`). |
| **Scope** | Pinning tests: log capture proves codes are logged; Inertia prop assertion proves `debug_code` exposure for a no-email user outside local. |
| **Out of scope** | No production fix. Tests pin CURRENT behaviour (label `@characterization`), including defects. No redesign, no CAST_VOTE, no resolver change. Test output must not print real codes beyond the fixture's own. |
| **Architectural layer** | Characterization |
| **Depends on** | PBDIGIT-80 |
| **Blocks** | PBDIGIT-86 · PBDIGIT-87 |
| **Files / areas likely affected** | tests/Feature/Characterization/Voting/Defects/ |
| **Acceptance criteria** | Two pins passing on current code. **One test per X-id, the X-id in the test name**, so each defect stays individually traceable (matrix §4d). |
| **Tests required** | feature/HTTP |
| **Architectural invariants** | As PBDIGIT-81. |
| **Definition of Done** | As PBDIGIT-81. |
| **Risk** | Low. |
| **Evidence required** | As PBDIGIT-81. |

### `PBDIGIT-83` — Pin participation-bypass defects X-6, X-7

| | |
|---|---|
| **Stage** | C-3 |
| **Architecture gate** | — |
| **Purpose** | Document the two gates that do not do what their names say. |
| **Problem** | X-6 session `selected_election_type === 'demo'` bypasses `VoteEligibility` (`:36`) and `CodeController::isUserEligible` (`:683-700`) on real routes; X-7 `EnsureRealVoteOrganisation::handleOrganisationMismatch` is never called — the middleware checks no organisation. |
| **Scope** | Pins: a real-election request with the demo session flag passes the lifecycle gate it should fail; a cross-organisation request is not rejected by `vote.organisation` (tenant isolation still enforced by `voter.slug.consistency`). |
| **Out of scope** | No production fix. Tests pin CURRENT behaviour (label `@characterization`), including defects. No redesign, no CAST_VOTE, no resolver change. |
| **Architectural layer** | Characterization |
| **Depends on** | PBDIGIT-79 |
| **Blocks** | PBDIGIT-88 |
| **Files / areas likely affected** | tests/Feature/Characterization/Voting/Defects/ |
| **Acceptance criteria** | Two pins passing on current code. **One test per X-id, the X-id in the test name**, so each defect stays individually traceable (matrix §4d). |
| **Tests required** | feature/HTTP |
| **Architectural invariants** | As PBDIGIT-81. |
| **Definition of Done** | As PBDIGIT-81. |
| **Risk** | Low. |
| **Evidence required** | As PBDIGIT-81. |

### `PBDIGIT-84` — Pin configuration and schema defects X-8…X-12

| | |
|---|---|
| **Stage** | C-3 |
| **Architecture gate** | — |
| **Purpose** | Document the defects rooted in config types and schema before the law migration. |
| **Problem** | X-8 `config('voting.two_codes_system')` is a string compared `=== 1`/`=== true`; X-9 `User::is_voter/can_vote` read columns no migration creates (`PBDIGIT-35`); X-10 whitelist `json_decode(array)` TypeError (`TrustPolicyEvaluator:140`) and `trackSovereigntyDivergence(?int $userId)` with UUIDs (`VoteController:1370`); X-11 per-election security law cannot be authored (source columns not fillable, never written); X-12 `users.voting_ip` is read (`ValidateVotingIp`, `VoteController::store`) but no migration creates it. |
| **Scope** | One pin each (X-10 two). Pins observe current behaviour (e.g. strict mode never activates with env `"1"`; a whitelisted election errors in `store`). |
| **Out of scope** | No production fix. Tests pin CURRENT behaviour (label `@characterization`), including defects. No redesign, no CAST_VOTE, no resolver change. |
| **Architectural layer** | Characterization |
| **Depends on** | PBDIGIT-79 · PBDIGIT-80 |
| **Blocks** | PBDIGIT-89 |
| **Files / areas likely affected** | tests/Feature/Characterization/Voting/Defects/; tests/Unit where a pin is below HTTP |
| **Acceptance criteria** | Six pins passing on current code. **One test per X-id, the X-id in the test name**, so each defect stays individually traceable (matrix §4d). |
| **Tests required** | feature/HTTP + unit |
| **Architectural invariants** | As PBDIGIT-81. |
| **Definition of Done** | As PBDIGIT-81. |
| **Risk** | X-10 pins exercise a crash; ensure the test DB transaction is cleaned up. |
| **Evidence required** | As PBDIGIT-81. |

### `PBDIGIT-135` — Characterize the non-slug `POST /votes` route and route-file loading

| | |
|---|---|
| **Stage** | C-3 |
| **Architecture gate** | — |
| **Purpose** | Establish whether a second entry point can cast a vote outside the slug voting chain. |
| **Problem** | `POST /votes` (`routes/election/electionRoutes.php:168`) reaches `VoteController::store` with only `auth:sanctum, verified, vote.eligibility` — none of the slug, step-order, IP, organisation or `voting.active` middleware — and `getElection` falls back to the first real election (`VoteController:108-110`). Separately, `routes/web.php` may be loaded twice (`bootstrap/app.php:11` and `RouteServiceProvider` `:63-64`); effect UNKNOWN. |
| **Scope** | HTTP pins of what `POST /votes` does today for a real voter (does it persist a vote? into which election?) and a route-list check of duplicate registration. Findings only. |
| **Out of scope** | No production fix. Tests pin CURRENT behaviour (label `@characterization`), including defects. No redesign, no CAST_VOTE, no resolver change. Removing or guarding the route is a separate, authorised ticket if the pin shows a defect. |
| **Architectural layer** | Characterization |
| **Depends on** | PBDIGIT-80 |
| **Blocks** | PBDIGIT-117 |
| **Files / areas likely affected** | tests/Feature/Characterization/Voting/Defects/ |
| **Acceptance criteria** | Pins state the current outcome; a written finding classifies the route as harmless, dead, or a participation bypass. **One test per X-id, the X-id in the test name**, so each defect stays individually traceable (matrix §4d). |
| **Tests required** | feature/HTTP |
| **Architectural invariants** | Every path to vote persistence is known before authority moves. |
| **Definition of Done** | Pins + finding; a defect ticket raised if a bypass is confirmed. |
| **Risk** | If it is a live bypass, it is a security defect under any architecture — escalate immediately, do not fix inside this ticket. |
| **Evidence required** | Pin run; `php artisan route:list` output. |

### `PBDIGIT-136` — Characterize `voter_slugs.vote_completed`, which no code writes

| | |
|---|---|
| **Stage** | C-3 |
| **Architecture gate** | — |
| **Purpose** | Find out whether an 'already voted' gate is dead. |
| **Problem** | `VerifyVoterSlugConsistency` redirects when `vote_completed` is set (`:105-115`), but no write of `vote_completed` exists in `app/`; `store` sets `has_voted` and `status='voted'` instead (`VoteController:1794-1797`). Whether the check ever fires is UNKNOWN. |
| **Scope** | Pin current behaviour after a completed vote (which gate actually stops a second attempt); record the finding as input to H-7. |
| **Out of scope** | No production fix. Tests pin CURRENT behaviour (label `@characterization`), including defects. No redesign, no CAST_VOTE, no resolver change. |
| **Architectural layer** | Characterization |
| **Depends on** | PBDIGIT-79 |
| **Blocks** | PBDIGIT-105 |
| **Files / areas likely affected** | tests/Feature/Characterization/Voting/Defects/ |
| **Acceptance criteria** | Pin passes on current code; finding attached to `PBDIGIT-98` (H-7). **One test per X-id, the X-id in the test name**, so each defect stays individually traceable (matrix §4d). |
| **Tests required** | feature/HTTP |
| **Architectural invariants** | The canonical already-voted fact is chosen on evidence. |
| **Definition of Done** | Pin + finding. |
| **Risk** | Low. |
| **Evidence required** | Pin run. |


## 11. Parity tickets

Comparison at the real HTTP boundary; divergences persisted; approvals are human acts.

### `PBDIGIT-115` — Runtime parity harness at the HTTP boundary with persisted divergences

| | |
|---|---|
| **Stage** | P-1 |
| **Architecture gate** | — |
| **Purpose** | Compare real legacy outcomes with shadow resolver outcomes. |
| **Problem** | Existing parity is unit-level with test-local re-implementations (`tests/Support/LegacyVotingBehavior.php`); the ledger is in-memory and empty. |
| **Scope** | HTTP-level fixtures over the readiness-review dimensions; legacy outcome vs decision record; divergence table (path, legacy, resolver, article, approved-by, rationale). |
| **Out of scope** | No approvals decided by engineering. |
| **Architectural layer** | Parity |
| **Depends on** | PBDIGIT-113 |
| **Blocks** | PBDIGIT-116 |
| **Files / areas likely affected** | tests/Feature/Parity/, divergence persistence |
| **Acceptance criteria** | Harness runs one path end to end and records its divergence row. |
| **Tests required** | parity (HTTP) |
| **Architectural invariants** | Legacy vs resolver compared at the real HTTP boundary. |
| **Definition of Done** | Guide. |
| **Risk** | Fixture realism. |
| **Evidence required** | Harness run. |

### `PBDIGIT-116` — Execute the 14 critical parity paths and obtain approval of expected divergences

| | |
|---|---|
| **Stage** | P-1 |
| **Architecture gate** | — |
| **Purpose** | Establish that every divergence is either absent or an approved constitutional correction. |
| **Problem** | Expected divergences exist by construction (paths 10–14, X-6 cases). |
| **Scope** | Run paths 1–14 (readiness review §13); present divergences to the PO; record approvals. |
| **Out of scope** | Engineering does not approve divergences. |
| **Architectural layer** | Parity |
| **Depends on** | PBDIGIT-115 |
| **Blocks** | PBDIGIT-117 · PBDIGIT-137 |
| **Files / areas likely affected** | parity results document |
| **Acceptance criteria** | All 14 paths: equal, or divergent with a signed approval. |
| **Tests required** | parity (HTTP) |
| **Architectural invariants** | No authority switch without parity. |
| **Definition of Done** | Signed divergence approvals. |
| **Risk** | Unexpected divergence → back to V-1/R-1. |
| **Evidence required** | Parity report. |


## 12. Deletion tickets

Readiness review §14 deletion manifest. `PBDIGIT-122` (dead code) is independent; the rest wait for the switch.

### `PBDIGIT-119` — Delete the legacy trust-decision pipeline

| | |
|---|---|
| **Stage** | D-2 |
| **Architecture gate** | A → **ARB architecture approval required in addition to PO authorisation** |
| **Purpose** | Remove the second authority. |
| **Problem** | Readiness review §14: `ConstitutionalLegitimacyDecision`, `PolicySequence`, legacy Verification/Network/Device policies, `SimplifiedPolicySequence`, `TrustSnapshotAssembler`, `NetworkTrustEvidence::effectiveLimit` (unless H-3 keeps it as law) are superseded. |
| **Scope** | Delete them and their tests; `TrustPolicyEvaluator` remainder stays only as the E-1 builder. |
| **Out of scope** | No behaviour change (parity green before and after). |
| **Architectural layer** | Deletion |
| **Depends on** | PBDIGIT-118 |
| **Blocks** | PBDIGIT-131 |
| **Files / areas likely affected** | app/Application/Election/Security/; app/Domain/Election/Security/ |
| **Acceptance criteria** | Symbols absent; parity + C-2 green. |
| **Tests required** | parity + architecture |
| **Architectural invariants** | One authority engine. |
| **Definition of Done** | Guide. |
| **Risk** | Hidden callers — grep + static analysis. |
| **Evidence required** | Runs; grep. |

### `PBDIGIT-120` — Delete legacy participation middleware

| | |
|---|---|
| **Stage** | D-2 |
| **Architecture gate** | A, F → **ARB architecture approval required in addition to PO authorisation** |
| **Purpose** | Middleware returns to transport and abuse protection only. |
| **Problem** | `EnsureElectionVoter`, `VoteEligibility`, `EnsureVotingActive`, `ValidateVotingIp` decide participation; `EnsureRealVoteOrganisation` is a no-op (X-7); `ValidateVoterSlugWindow` also enforces election end_date. |
| **Scope** | Remove them from routes and code; keep slug, ownership, step order, throttle, CSRF, consistency (one platform-org rule per H-6). |
| **Out of scope** | Transport middleware unchanged. |
| **Architectural layer** | Deletion |
| **Depends on** | PBDIGIT-118 · PBDIGIT-95 |
| **Blocks** | PBDIGIT-127 · PBDIGIT-131 |
| **Files / areas likely affected** | app/Http/Middleware/, bootstrap/app.php aliases, routes/election/electionRoutes.php |
| **Acceptance criteria** | Architecture test: no middleware derives participation; parity green. |
| **Tests required** | feature/HTTP + architecture |
| **Architectural invariants** | Middleware cannot derive participation legality. |
| **Definition of Done** | Guide. |
| **Risk** | Route-group ordering. |
| **Evidence required** | Runs. |

### `PBDIGIT-121` — Delete duplicate controller participation gates and per-IP authorities

| | |
|---|---|
| **Stage** | D-2 |
| **Architecture gate** | A → **ARB architecture approval required in addition to PO authorisation** |
| **Purpose** | Remove the remaining duplicated authority. |
| **Problem** | `ElectionVotingController::resolveIpBlock/evaluateIpCount`, `CodeController` IP cap, `vote_post_check` IP count, controller `canVote()`/membership gates, unrouted `second_submission`/`validateVotingEligibility`/`verifyVoteSubmit`. |
| **Scope** | Delete them; keep flow redirects, credential check and persistence. |
| **Out of scope** | No behaviour change beyond approved divergences. |
| **Architectural layer** | Deletion |
| **Depends on** | PBDIGIT-118 · PBDIGIT-45 |
| **Blocks** | PBDIGIT-131 |
| **Files / areas likely affected** | ElectionVotingController, CodeController, VoteController |
| **Acceptance criteria** | Symbols absent; parity + C-2 green. |
| **Tests required** | feature/HTTP + parity |
| **Architectural invariants** | Controllers orchestrate; they do not decide. |
| **Definition of Done** | Guide. |
| **Risk** | As PBDIGIT-119. |
| **Evidence required** | Runs; grep. |

### `PBDIGIT-122` — Delete dead legacy code and retire tests of non-existent classes

| | |
|---|---|
| **Stage** | D-2 |
| **Architecture gate** | — |
| **Purpose** | Remove code that cannot run and tests that target classes that do not exist. |
| **Problem** | `SnapshotAssembler` (0 callers, broken refs), `TrustCapabilityPolicy` (unregistered, references non-existent `RecommendedProceduralPath`), unused overlay ordering metadata; live tests reference `OverlayInfluenceContext`, `App\Domain\Election\Security\OverlaySignal`, `VotingTrustResult::allow()`. |
| **Scope** | Delete the dead production classes; for each broken test, a recorded disposition (re-scope to the current class or retire) approved in the ticket. |
| **Out of scope** | No live code path changes. |
| **Architectural layer** | Deletion |
| **Depends on** | PBDIGIT-76 · PBDIGIT-77 · PBDIGIT-78 |
| **Blocks** | PBDIGIT-132 |
| **Files / areas likely affected** | app/Application/Election/Security/SnapshotAssembler.php, Capabilities/Policies/TrustCapabilityPolicy.php, Overlay* metadata; affected unit tests |
| **Acceptance criteria** | 0 references to phantom symbols in `app/` and `tests/`; unit Security suites error-free. |
| **Tests required** | unit + architecture |
| **Architectural invariants** | Code compiles against real types only. |
| **Definition of Done** | Disposition table; guide. |
| **Risk** | Retiring a test hides an intent — disposition per test, approved. |
| **Evidence required** | Runs; grep. |

### `PBDIGIT-137` — Dispose of the unwired divergence-observation infrastructure

| | |
|---|---|
| **Stage** | D-2 |
| **Architecture gate** | — |
| **Purpose** | Remove observation code that cannot run, once real runtime parity replaces it. |
| **Problem** | `LogDivergenceObservations` is wired nowhere; its window is never active (`config/constitutional.php` absent); `DivergenceLogger` inserts a null into a NOT NULL FK and queries a non-existent column; its log channel is undefined. `ConstitutionalDivergenceLedger`/`CapabilityParitySnapshot` are in-memory, test-only; `tests/Support/{Legacy,Resolver}VotingBehavior.php` re-implement behaviour instead of calling it. None of it appears in the readiness review's deletion manifest. |
| **Scope** | After `PBDIGIT-118` proves HTTP-level parity: delete or replace each item, with a recorded disposition; keep the `ValidateVotingIp` JSONL telemetry only until `PBDIGIT-122` removes that middleware. |
| **Out of scope** | No behaviour change; no deletion before the replacement parity evidence exists. |
| **Architectural layer** | Deletion |
| **Depends on** | PBDIGIT-116 |
| **Blocks** | PBDIGIT-132 |
| **Files / areas likely affected** | app/Http/Middleware/LogDivergenceObservations.php, app/Services/Constitutional/, app/Domain/Election/Security/{ConstitutionalDivergence*,CapabilityParitySnapshot}.php, tests/Support/ |
| **Acceptance criteria** | Symbols absent or re-homed; parity suite green. |
| **Tests required** | parity + architecture |
| **Architectural invariants** | One parity mechanism, at the real HTTP boundary. |
| **Definition of Done** | Disposition table; guide. |
| **Risk** | Hidden test dependants. |
| **Evidence required** | Runs; grep. |


## 13. CI tickets

`PBDIGIT-123` makes the checks run and block; each rule ticket then adds one mechanical rule (readiness review §15).

### `PBDIGIT-123` — Bring election security zones under a blocking CI job

| | |
|---|---|
| **Stage** | CI |
| **Architecture gate** | — |
| **Purpose** | Prerequisite for every enforcement rule: the checks must actually run and block. |
| **Problem** | `greenfield-merge-gate` covers only the Architecture suite (3 failing locally) and `app/Contexts/*` tooling; `tests/Unit/{Application,Domain}/Election`, `tests/Unit/Audit` run nowhere; `ConstitutionalSemanticRegressionTests.php` is never collected. |
| **Scope** | A blocking job running the election security unit/feature/architecture suites; triage of the 3 failing Architecture tests (fix or disposition); collect the misnamed test file. |
| **Out of scope** | No new rules yet. |
| **Architectural layer** | CI Architecture |
| **Depends on** | PBDIGIT-76 · PBDIGIT-77 · PBDIGIT-78 |
| **Blocks** | PBDIGIT-124 · PBDIGIT-125 · PBDIGIT-126 · PBDIGIT-127 · PBDIGIT-128 · PBDIGIT-129 |
| **Files / areas likely affected** | .github/workflows/, composer.json scripts, phpunit.xml |
| **Acceptance criteria** | Job runs on PRs and fails on a seeded violation. |
| **Tests required** | CI |
| **Architectural invariants** | Architectural rules are executable, not documented. |
| **Definition of Done** | Green job on main. |
| **Risk** | Flaky DB-backed suites. |
| **Evidence required** | CI run link. |

### `PBDIGIT-124` — CI rule 1 — Only the resolver and registered policies may create CapabilityDecision

| | |
|---|---|
| **Stage** | CI |
| **Architecture gate** | — |
| **Purpose** | Prevent the corrected architecture from regressing. |
| **Problem** | No mechanical enforcement exists for this rule today (reality audit §14). |
| **Scope** | PHPStan custom rule on `CapabilityDecision` static factories + registered-set allow-list test (replaces the vacuous `new CapabilityDecision` check) |
| **Out of scope** | No production refactor beyond what the rule flags as already-fixed. |
| **Architectural layer** | CI Architecture |
| **Depends on** | PBDIGIT-123 · PBDIGIT-111 |
| **Blocks** | PBDIGIT-132 |
| **Files / areas likely affected** | phpstan config/rules, deptrac.yaml, tests/Architecture |
| **Acceptance criteria** | A seeded violation fails the blocking job; current code passes. **Progressive enforcement:** the rule is advisory until its target architecture exists (its dependencies), then blocking; all rules blocking by V1. |
| **Tests required** | architecture / static analysis |
| **Architectural invariants** | As the rule title. |
| **Definition of Done** | Rule documented in the guide; job green. |
| **Risk** | False positives on transport code — explicit allow-lists. |
| **Evidence required** | CI run with seeded violation. |

### `PBDIGIT-125` — CI rule 2/4 — Canonical zones cannot import legacy authority; policies cannot consume policy outcomes

| | |
|---|---|
| **Stage** | CI |
| **Architecture gate** | — |
| **Purpose** | Prevent the corrected architecture from regressing. |
| **Problem** | No mechanical enforcement exists for this rule today (reality audit §14). |
| **Scope** | deptrac layers for `app/Domain/Election/Security`, `app/Application/Election/{Security,Capabilities}` (rules 2 and 4) |
| **Out of scope** | No production refactor beyond what the rule flags as already-fixed. |
| **Architectural layer** | CI Architecture |
| **Depends on** | PBDIGIT-123 · PBDIGIT-108 |
| **Blocks** | PBDIGIT-132 |
| **Files / areas likely affected** | phpstan config/rules, deptrac.yaml, tests/Architecture |
| **Acceptance criteria** | A seeded violation fails the blocking job; current code passes. **Progressive enforcement:** the rule is advisory until its target architecture exists (its dependencies), then blocking; all rules blocking by V1. |
| **Tests required** | architecture / static analysis |
| **Architectural invariants** | As the rule title. |
| **Definition of Done** | Rule documented in the guide; job green. |
| **Risk** | False positives on transport code — explicit allow-lists. |
| **Evidence required** | CI run with seeded violation. |

### `PBDIGIT-126` — CI rule 3 — Canonical zones cannot read mutable configuration or live models

| | |
|---|---|
| **Stage** | CI |
| **Architecture gate** | — |
| **Purpose** | Prevent the corrected architecture from regressing. |
| **Problem** | No mechanical enforcement exists for this rule today (reality audit §14). |
| **Scope** | PHPStan rule forbidding `config()`, `env()`, settings services, Eloquent imports in evaluators and resolver policies |
| **Out of scope** | No production refactor beyond what the rule flags as already-fixed. |
| **Architectural layer** | CI Architecture |
| **Depends on** | PBDIGIT-123 · PBDIGIT-108 |
| **Blocks** | PBDIGIT-132 |
| **Files / areas likely affected** | phpstan config/rules, deptrac.yaml, tests/Architecture |
| **Acceptance criteria** | A seeded violation fails the blocking job; current code passes. **Progressive enforcement:** the rule is advisory until its target architecture exists (its dependencies), then blocking; all rules blocking by V1. |
| **Tests required** | architecture / static analysis |
| **Architectural invariants** | As the rule title. |
| **Definition of Done** | Rule documented in the guide; job green. |
| **Risk** | False positives on transport code — explicit allow-lists. |
| **Evidence required** | CI run with seeded violation. |

### `PBDIGIT-127` — CI rule 5 — Snapshots cannot evaluate; middleware cannot derive participation

| | |
|---|---|
| **Stage** | CI |
| **Architecture gate** | — |
| **Purpose** | Prevent the corrected architecture from regressing. |
| **Problem** | No mechanical enforcement exists for this rule today (reality audit §14). |
| **Scope** | architecture tests (reflection + call-graph scan) for rules 5 and 6 |
| **Out of scope** | No production refactor beyond what the rule flags as already-fixed. |
| **Architectural layer** | CI Architecture |
| **Depends on** | PBDIGIT-123 · PBDIGIT-120 |
| **Blocks** | PBDIGIT-132 |
| **Files / areas likely affected** | phpstan config/rules, deptrac.yaml, tests/Architecture |
| **Acceptance criteria** | A seeded violation fails the blocking job; current code passes. **Progressive enforcement:** the rule is advisory until its target architecture exists (its dependencies), then blocking; all rules blocking by V1. |
| **Tests required** | architecture / static analysis |
| **Architectural invariants** | As the rule title. |
| **Definition of Done** | Rule documented in the guide; job green. |
| **Risk** | False positives on transport code — explicit allow-lists. |
| **Evidence required** | CI run with seeded violation. |

### `PBDIGIT-128` — CI rule 7 — Forbidden procedural vocabulary in canonical governance code

| | |
|---|---|
| **Stage** | CI |
| **Architecture gate** | — |
| **Purpose** | Prevent the corrected architecture from regressing. |
| **Problem** | No mechanical enforcement exists for this rule today (reality audit §14). |
| **Scope** | token-based scanner (`PhpToken`) over canonical zones for priority/strongest/recommend/escalat/influence/elevat identifiers |
| **Out of scope** | No production refactor beyond what the rule flags as already-fixed. |
| **Architectural layer** | CI Architecture |
| **Depends on** | PBDIGIT-123 |
| **Blocks** | PBDIGIT-132 |
| **Files / areas likely affected** | phpstan config/rules, deptrac.yaml, tests/Architecture |
| **Acceptance criteria** | A seeded violation fails the blocking job; current code passes. **Progressive enforcement:** the rule is advisory until its target architecture exists (its dependencies), then blocking; all rules blocking by V1. |
| **Tests required** | architecture / static analysis |
| **Architectural invariants** | As the rule title. |
| **Definition of Done** | Rule documented in the guide; job green. |
| **Risk** | False positives on transport code — explicit allow-lists. |
| **Evidence required** | CI run with seeded violation. |

### `PBDIGIT-129` — CI rule 8 — Constitutional immutability and replay sufficiency run in CI

| | |
|---|---|
| **Stage** | CI |
| **Architecture gate** | — |
| **Purpose** | Prevent the corrected architecture from regressing. |
| **Problem** | No mechanical enforcement exists for this rule today (reality audit §14). |
| **Scope** | DB integration test (all probe write paths) + replay harness in the blocking job (rules 8 and 9) |
| **Out of scope** | No production refactor beyond what the rule flags as already-fixed. |
| **Architectural layer** | CI Architecture |
| **Depends on** | PBDIGIT-123 · PBDIGIT-103 · PBDIGIT-114 |
| **Blocks** | PBDIGIT-132 |
| **Files / areas likely affected** | phpstan config/rules, deptrac.yaml, tests/Architecture |
| **Acceptance criteria** | A seeded violation fails the blocking job; current code passes. **Progressive enforcement:** the rule is advisory until its target architecture exists (its dependencies), then blocking; all rules blocking by V1. |
| **Tests required** | architecture / static analysis |
| **Architectural invariants** | As the rule title. |
| **Definition of Done** | Rule documented in the guide; job green. |
| **Risk** | False positives on transport code — explicit allow-lists. |
| **Evidence required** | CI run with seeded violation. |

### `PBDIGIT-133` — Temporal-determinism audit scanner flags `validate(` as a `date()` call

| | |
|---|---|
| **Stage** | CI |
| **Architecture gate** | — |
| **Purpose** | Remove a false positive that misreports a determinism violation in the canonical zone. |
| **Problem** | `TemporalDeterminismAuditTest` searches source for the substring `date(` (`:137`), which matches `validate(` in `ElectionConstitutionValidator.php:7`. The validator never reads the clock; the reality audit's §8/§13 claim of a `date()` violation is therefore wrong. |
| **Scope** | Make the scanner token-based (`PhpToken`: a `T_STRING` named `date` followed by `(`), so it detects real calls only; keep every forbidden function. |
| **Out of scope** | No production change. No forbidden function removed from the list. No exemption added for the validator. |
| **Architectural layer** | Test infrastructure |
| **Depends on** | — |
| **Blocks** | PBDIGIT-132 |
| **Files / areas likely affected** | tests/Unit/Audit/TemporalDeterminismAuditTest.php |
| **Acceptance criteria** | The test passes on current code; a seeded `date('Y')` call in the canonical zone makes it fail. **Progressive enforcement:** the rule is advisory until its target architecture exists (its dependencies), then blocking; all rules blocking by V1. |
| **Tests required** | unit (audit) |
| **Architectural invariants** | Determinism audits report real violations only. |
| **Definition of Done** | Test-only commit; correction note on the reality audit. |
| **Risk** | Low. |
| **Evidence required** | Run before/after plus the seeded-violation run. |


## 13a. Governance-capability tickets (admin lifecycle actions)

From the 2026-10-01 review of the governance-capability story (§4h). These consolidate the four admin-action authorities into the resolver; they do not touch the voting path.

### `PBDIGIT-139` — Repair capability-suite and suspension-suite test drift

| | |
|---|---|
| **Stage** | GOV |
| **Architecture gate** | — |
| **Purpose** | Make the capability and suspension suites report real behaviour again. |
| **Problem** | The capability-foundation unit set runs 168 tests with 6 failures (the story's '100 tests / 254 assertions GREEN' no longer holds). (a) `CanEditTimelineCapabilityTest` ×3 and `ElectionSuspensionTest::test_suspend_captures_lifecycle_context` ×1 build elections that never reach VotingActive/Counting — the same lifecycle-fixture gap as C-1 (no `voting_locked`/approved candidacy, rules since PBDIGIT-64 and `606c362aa`). (b) `CapabilityPolicyLayerTest` ×3 expects Lifecycle=2, Preconditions=3, Authorization=4; commit `e697ffac6` (Phase D.5) deliberately inserted `Trust = 2` and shifted the others, and the test was never updated. |
| **Scope** | (a) fixture repair only, as in C-1. (b) a recorded disposition for the three priority tests: update the expected values because the production change was intentional, OR retire them if the resolver's evaluate-every-policy change makes layer priority non-semantic — the PO/ARB chooses, the ticket does not. |
| **Out of scope** | No production code (`app/`, `routes/`, `config/`, `database/migrations/`). No assertion weakened, deleted, skipped or marked incomplete. No change to expected behaviour. No real test converted to demo. No session flags added to pass. The C-1 governing rule applies (no production API restored; defects → new tickets). |
| **Architectural layer** | Test infrastructure |
| **Depends on** | — |
| **Blocks** | PBDIGIT-140 · PBDIGIT-149 |
| **Files / areas likely affected** | tests/Unit/Application/Election/Capabilities/CanEditTimelineCapabilityTest.php, tests/Unit/Application/Election/CapabilityPolicyLayerTest.php, tests/Feature/Election/ElectionSuspensionTest.php |
| **Acceptance criteria** | The capability-foundation set and `ElectionSuspensionTest` run with 0 infrastructure failures; any remaining failure classified BEHAVIOURAL. |
| **Tests required** | unit + feature (existing tests only) |
| **Architectural invariants** | Tests observe the lifecycle engine's current rules. |
| **Definition of Done** | Before/after runs; disposition recorded for the layer-priority tests. |
| **Risk** | Updating expected values is an assertion change — allowed only with the recorded disposition. |
| **Evidence required** | Runs; disposition note. |

### `PBDIGIT-140` — Characterize the four admin-action authorities at the HTTP boundary

| | |
|---|---|
| **Stage** | GOV |
| **Architecture gate** | — |
| **Purpose** | Record, before any consolidation, where the four sources that decide admin lifecycle actions agree and disagree. |
| **Problem** | Four components each decide what an admin may do: (1) `ElectionCapabilityResolver` → the `capabilities` prop (`ElectionManagementController::resolveCapabilities:1288`) — lifecycle state + suspension only; (2) `ConstitutionalTransitionGuard::assertAllowed` — state + roles + preconditions at execution; (3) Laravel `ElectionPolicy` gates (e.g. `suspendElection`, `manageSettings`); (4) `ElectionLifecycleEngineImpl::derivePermissions` + `getAllowedActions` — a hard-coded state matrix. Known divergences: the engine never lists `suspend` (RULES allows it in 10 states) and lists nothing in `submitted_for_approval` (RULES allows `approve`/`reject`). |
| **Scope** | `@characterization` HTTP tests per action × representative state × role (chief, deputy, ordinary user, platform admin) asserting what each source says today and what the executed request does. |
| **Out of scope** | No production fix. Tests pin CURRENT behaviour (label `@characterization`), including defects. No redesign, no CAST_VOTE, no resolver change. |
| **Architectural layer** | Characterization |
| **Depends on** | PBDIGIT-139 |
| **Blocks** | PBDIGIT-109 · PBDIGIT-142 · PBDIGIT-143 · PBDIGIT-144 · PBDIGIT-146 · PBDIGIT-148 |
| **Files / areas likely affected** | tests/Feature/Characterization/Governance/ |
| **Acceptance criteria** | A table action × state × role → (resolver, guard, policy, engine, executed outcome), each cell pinned by a test. |
| **Tests required** | feature/HTTP |
| **Architectural invariants** | Every admin authority disagreement is known before it is removed. |
| **Definition of Done** | Table + pins; serves as the admin-path characterization required before the resolver-mechanics ticket. |
| **Risk** | Large matrix — limit to the actions in `ElectionConstitution::RULES` and the four roles. |
| **Evidence required** | Pin run; table. |

### `PBDIGIT-142` — Suspend and resume bypass ConstitutionalTransitionGuard

| | |
|---|---|
| **Stage** | GOV |
| **Architecture gate** | A → **ARB architecture approval required in addition to PO authorisation** |
| **Purpose** | Make suspension obey the constitution's own action rules. |
| **Problem** | `ElectionStateWriteContext` documents that ONLY `ConstitutionalTransitionGuard` may call `authorize()` (`ElectionStateWriteContext.php:9,21,40`), but `ElectionManagementController::suspend` (`:1022`) and `::resume` (`:1059`) call it directly after a Laravel policy check. Consequences: `RULES['suspend']['allowed_states']` (which excludes `archived`) is never checked — the controller only refuses double suspension — so an archived election can be suspended; and the role rule actually applied is `ElectionPolicy`'s, not RULES'. The other direct callers are console commands (`ActivateElectionCommand:29` — self-labelled as a bypass, `BackfillElectionState:66`). |
| **Scope** | Route suspend/resume through the guard under the H-13 decision; keep the fact-preserving behaviour (no voting-window mutation; resume re-derives). Disposition for the two console commands recorded. |
| **Out of scope** | No change outside the named site. No new lifecycle state (the story itself forbids Cancelled/Paused/LegalHold/… as states). No dual-axis lifecycle rewrite. Voting-path tickets are not affected. |
| **Architectural layer** | Authority |
| **Depends on** | PBDIGIT-140 · PBDIGIT-141 |
| **Blocks** | PBDIGIT-132 |
| **Files / areas likely affected** | app/Http/Controllers/Election/ElectionManagementController.php (suspend, resume) |
| **Acceptance criteria** | Suspending an archived election is refused; role outcome matches H-13; the characterization pins change only where this ticket names it. |
| **Tests required** | feature/HTTP |
| **Architectural invariants** | Every lifecycle-affecting act passes the constitutional transition guard. |
| **Definition of Done** | RED→GREEN; guide. |
| **Risk** | Changes who can suspend if H-13 differs from today's policy — that is the decision's consequence, recorded in the RED test. |
| **Evidence required** | Runs. |

### `PBDIGIT-143` — Resume writes no audit record

| | |
|---|---|
| **Stage** | GOV |
| **Architecture gate** | G |
| **Purpose** | Make the audit trail of a governance hold complete. |
| **Problem** | `suspend` records `ElectionAuditLog::record($election, 'suspend', …)` (`ElectionManagementController:1033-1040`); `resume` (`:1055-1072`) records nothing, so the audit shows a hold that never ended. |
| **Scope** | Record a `resume` audit entry with actor, time and the re-derived lifecycle state. |
| **Out of scope** | No change outside the named site. No new lifecycle state (the story itself forbids Cancelled/Paused/LegalHold/… as states). No dual-axis lifecycle rewrite. Voting-path tickets are not affected. |
| **Architectural layer** | Persistence / Audit |
| **Depends on** | PBDIGIT-140 |
| **Blocks** | PBDIGIT-132 |
| **Files / areas likely affected** | ElectionManagementController::resume |
| **Acceptance criteria** | A resume produces exactly one audit entry; pinned by a feature test. |
| **Tests required** | feature/HTTP |
| **Architectural invariants** | Every governance act is auditable. |
| **Definition of Done** | RED→GREEN. |
| **Risk** | Low. |
| **Evidence required** | Run. |

### `PBDIGIT-144` — Resolver role and precondition policies for admin actions

| | |
|---|---|
| **Stage** | GOV |
| **Architecture gate** | A, E → **ARB architecture approval required in addition to PO authorisation** |
| **Purpose** | Make the capability snapshot agree with what the guard will enforce. |
| **Problem** | The resolver has policies only for suspension and lifecycle state; the `Preconditions` and `Authorization` layers exist in `CapabilityPolicyLayer` but have no admin-action implementation. `RULES` declares `allowed_roles` and `preconditions` for every action, enforced only at execution by the guard. So the snapshot rendered in the UI can show an action as allowed that is then refused (story question 5: another layer reaches a contradictory decision). |
| **Scope** | Two `CapabilityPolicy` implementations consuming `RULES` roles (per H-13) and preconditions (reusing the guard's predicate set, incl. 'unknown precondition fails visibly'); one shared predicate source for guard and policy. |
| **Out of scope** | No change outside the named site. No new lifecycle state (the story itself forbids Cancelled/Paused/LegalHold/… as states). No dual-axis lifecycle rewrite. Voting-path tickets are not affected. The guard keeps enforcing at execution. |
| **Architectural layer** | Authority |
| **Depends on** | PBDIGIT-109 · PBDIGIT-141 · PBDIGIT-140 |
| **Blocks** | PBDIGIT-145 |
| **Files / areas likely affected** | app/Application/Election/Capabilities/Policy/; app/Application/Election/Services/ConstitutionalTransitionGuard.php (predicate extraction only) |
| **Acceptance criteria** | For every action × state × role in the governance characterization table, the snapshot's `allowed` equals the guard's verdict; an unknown precondition denies with a visible reason. |
| **Tests required** | unit + feature |
| **Architectural invariants** | One answer per (action, state, role): the snapshot never contradicts execution. |
| **Definition of Done** | RED→GREEN; guide. |
| **Risk** | Duplicating predicates between guard and policy — must share one implementation. |
| **Evidence required** | Runs; table. |

### `PBDIGIT-145` — Remove the lifecycle engine's second capability matrix

| | |
|---|---|
| **Stage** | GOV |
| **Architecture gate** | A, E → **ARB architecture approval required in addition to PO authorisation** |
| **Purpose** | Leave one source of admin capabilities. |
| **Problem** | `ElectionLifecycleEngineImpl::derivePermissions` (`:205`) and `::getAllowedActions` hard-code what is allowed per state into `ElectionLifecycleSnapshot` (`canEdit`, `canManageVoters`, `canPublishResults`, `canEditTimeline`, `canVote`, `allowedActions`). They duplicate `ElectionConstitution::RULES` and disagree with it (no `suspend` in any state; nothing in `submitted_for_approval`, where RULES allows `approve`/`reject`). Frontend pages consume these props (36 uses). |
| **Scope** | Lifecycle engine derives state only; the snapshot's capability fields become a projection of the resolver (the story's 'LegacyAllowedActionsProjector'), keeping prop names for compatibility until the frontend ticket. |
| **Out of scope** | No change outside the named site. No new lifecycle state (the story itself forbids Cancelled/Paused/LegalHold/… as states). No dual-axis lifecycle rewrite. Voting-path tickets are not affected. No voting-path change (`canVote` is owned by the CAST_VOTE migration). |
| **Architectural layer** | Authority |
| **Depends on** | PBDIGIT-144 |
| **Blocks** | PBDIGIT-147 |
| **Files / areas likely affected** | app/Application/Election/Services/ElectionLifecycleEngineImpl.php; app/Domain/Election/ValueObjects/ElectionLifecycleSnapshot.php |
| **Acceptance criteria** | No capability matrix remains in the engine; projected values equal the resolver for every characterized cell; recorded divergences (suspend, approve/reject) resolved in favour of RULES. |
| **Tests required** | unit + feature + architecture (engine contains no capability rule) |
| **Architectural invariants** | Lifecycle derivation and capability resolution have separate, single owners. |
| **Definition of Done** | RED→GREEN; guide. |
| **Risk** | Pages relying on the old matrix's divergent answers change behaviour — that is the intended correction, pinned first. |
| **Evidence required** | Runs. |

### `PBDIGIT-146` — Declare overlay behaviour in the constitution and fix the placeholder resume target

| | |
|---|---|
| **Stage** | GOV |
| **Architecture gate** | B → **ARB architecture approval required in addition to PO authorisation** |
| **Purpose** | Make the action registry the one place an action's governance is defined. |
| **Problem** | `OverlayCapabilityPolicy` hard-codes that `resume` alone survives suspension (`:84-88` of the policy); `RULES['resume']['target_state']` is a placeholder `'suspended'` (comment: 'placeholder'); `ElectionConstitutionRegistry` is an 18-line pass-through. The story's registry (role, lifecycle applicability, preconditions, overlay behaviour, metadata) is therefore partial. |
| **Scope** | Declare per-action overlay behaviour in `RULES` (consumed by `OverlayCapabilityPolicy`), give `resume` a truthful target semantics, keep the registry the only reader. Coordinated with H-5 (the participation-action registry) so admin and participation actions do not get two registries. |
| **Out of scope** | No change outside the named site. No new lifecycle state (the story itself forbids Cancelled/Paused/LegalHold/… as states). No dual-axis lifecycle rewrite. Voting-path tickets are not affected. |
| **Architectural layer** | Constitutional Law |
| **Depends on** | PBDIGIT-140 · PBDIGIT-94 |
| **Blocks** | PBDIGIT-132 |
| **Files / areas likely affected** | app/Domain/Election/Constitution/ElectionConstitution.php; app/Application/Election/Capabilities/{ElectionConstitutionRegistry.php, Policy/OverlayCapabilityPolicy.php} |
| **Acceptance criteria** | No action-specific constant left in `OverlayCapabilityPolicy`; no placeholder in RULES. |
| **Tests required** | unit + architecture |
| **Architectural invariants** | One concept → one authoritative owner (the constitution registry). |
| **Definition of Done** | RED→GREEN; guide. |
| **Risk** | Low. |
| **Evidence required** | Runs. |

### `PBDIGIT-147` — Frontend renders governance capabilities only

| | |
|---|---|
| **Stage** | GOV |
| **Architecture gate** | F |
| **Purpose** | Stop the UI from deciding authority. |
| **Problem** | `Management.vue:925,939` branch on `election.state === 'results_published'`; `SubmitForApproval.vue:151` on `state === 'draft'`; Election pages use legacy `can*`/`allowedActions` props 36 times. Only `StateMachinePanel.vue` / `useElectionCapabilities.ts` consume the resolver's `capabilities`. |
| **Scope** | Replace those checks with `capabilities.<action>.allowed` / denial reason; display-only state labels may remain. |
| **Out of scope** | No change outside the named site. No new lifecycle state (the story itself forbids Cancelled/Paused/LegalHold/… as states). No dual-axis lifecycle rewrite. Voting-path tickets are not affected. Follows `.claude/UI_GUIDELINES.md`; `npm run design-check` before/after. |
| **Architectural layer** | Projection |
| **Depends on** | PBDIGIT-145 |
| **Blocks** | PBDIGIT-132 |
| **Files / areas likely affected** | resources/js/Pages/Election/{Management,SubmitForApproval}.vue and other Election pages using legacy props |
| **Acceptance criteria** | 0 authority decisions on raw state or legacy props in Election pages (scanner/grep test); UI behaviour equals the snapshot. |
| **Tests required** | frontend unit + feature/HTTP |
| **Architectural invariants** | The frontend may explain a decision; it may not replace it. |
| **Definition of Done** | Design-check report; guide. |
| **Risk** | Visual regressions. |
| **Evidence required** | Grep/scanner output; design-check. |

### `PBDIGIT-148` — Lifecycle derivation cannot be recomputed for a given instant

| | |
|---|---|
| **Stage** | GOV |
| **Architecture gate** | C |
| **Purpose** | Make 'same facts + same time → same lifecycle state' testable and true. |
| **Problem** | `ElectionLifecycleEngineImpl::getState` reads `now()` (`:66`, `:189`) and `ElectionClockService` wraps `now()` (`:27,38,53`); the engine has no clock dependency (`__construct()` takes nothing, `:39`) and also queries candidacies inside derivation (`hasCandidatesApproved`). The story's recomputability invariant therefore cannot be exercised for a past or future instant without global time travel. |
| **Scope** | Inject `ClockInterface` (exists: `app/Domain/Shared/Clock/`) into the clock service/engine; make the approved-candidate fact an input rather than a query inside derivation. |
| **Out of scope** | No change outside the named site. No new lifecycle state (the story itself forbids Cancelled/Paused/LegalHold/… as states). No dual-axis lifecycle rewrite. Voting-path tickets are not affected. No change to lifecycle rules. |
| **Architectural layer** | Evidence |
| **Depends on** | PBDIGIT-140 |
| **Blocks** | PBDIGIT-132 |
| **Files / areas likely affected** | app/Application/Election/Services/ElectionLifecycleEngineImpl.php; app/Services/ElectionClockService.php |
| **Acceptance criteria** | A unit test derives the state for an arbitrary instant from facts alone, with no DB and no ambient clock. |
| **Tests required** | unit + architecture (no `now()` in lifecycle derivation) |
| **Architectural invariants** | Lifecycle truth is recomputable from facts and an explicit instant. |
| **Definition of Done** | RED→GREEN; guide. |
| **Risk** | Many callers construct the engine via the facade — keep the facade signature. |
| **Evidence required** | Runs. |

### `PBDIGIT-149` — Governance lifecycle end-to-end suite through HTTP

| | |
|---|---|
| **Stage** | GOV |
| **Architecture gate** | — |
| **Purpose** | Prove the whole admin journey, not its parts. |
| **Problem** | Suspension, role and resume behaviour are tested per unit/model (e.g. the time-passes-during-suspension case is a model-level test, `ElectionSuspensionTest:186-228`); no suite walks Draft → Submitted → Approved → SetupAdministration → SetupNomination → ReadyForVoting → VotingActive → Counting → ResultsPublished → Archived through the real routes, nor suspend-during-voting → window expires → resume → Counting via HTTP, nor the role matrix end to end. `PBDIGIT-36` covers the voting journey only. |
| **Scope** | HTTP E2E tests for the three scenarios in the story (§15), with role expectations from H-13. |
| **Out of scope** | No production fix. Tests pin CURRENT behaviour (label `@characterization`), including defects. No redesign, no CAST_VOTE, no resolver change. Assertions follow current behaviour until the consolidation tickets change it. |
| **Architectural layer** | Characterization |
| **Depends on** | PBDIGIT-139 · PBDIGIT-141 |
| **Blocks** | PBDIGIT-132 |
| **Files / areas likely affected** | tests/Feature/Governance/ |
| **Acceptance criteria** | All three scenarios run green through HTTP; each state transition asserted. |
| **Tests required** | feature/HTTP (E2E) |
| **Architectural invariants** | The governance journey works end to end. |
| **Definition of Done** | In the blocking CI job. |
| **Risk** | Long fixtures — reuse factories repaired in the test-drift ticket. |
| **Evidence required** | Run. |


## 14. Documentation and finalization tickets

### `PBDIGIT-130` — Correct false completion claims in historical documents

| | |
|---|---|
| **Stage** | DOC |
| **Architecture gate** | — |
| **Purpose** | Stop documentation from asserting protections the runtime does not provide. |
| **Problem** | Reality audit §16: D.5, D.R.3, D.2.5 'LIVE', 'Phase D complete', 'Parity complete', E.1 'verified', D.5.5 Audit 1's false 'ValidateVotingIp not registered'; CLAUDE.md demo model `organisation_id = NULL` vs NOT NULL schema. |
| **Scope** | Additive correction notes (ES-004.3) in `claude/audits/*`, `claude/plans/PHASE_1_COMPLETE.md`, `.claude/memory/*`, `developer_guide/election/real_election/security/*`; a flagged note for the CLAUDE.md demo statement (owner decides the edit). |
| **Out of scope** | History not rewritten; no code. |
| **Architectural layer** | Documentation |
| **Depends on** | — |
| **Blocks** | PBDIGIT-132 |
| **Files / areas likely affected** | claude/, .claude/memory/, developer_guide/ |
| **Acceptance criteria** | Each listed claim carries a dated correction pointing to the reality audit. |
| **Tests required** | none (`npm run knowledge-lint` where applicable) |
| **Architectural invariants** | The strongest statement never exceeds the evidence. |
| **Definition of Done** | Corrections committed. |
| **Risk** | Low. |
| **Evidence required** | Diff. |

### `PBDIGIT-131` — Consolidated Constitutional Governance Runtime developer guide

| | |
|---|---|
| **Stage** | DOC |
| **Architecture gate** | — |
| **Purpose** | One accurate how-to for the finished runtime. |
| **Problem** | Per-slice guides will exist; no consolidated description of the final architecture. |
| **Scope** | Guide covering law, evidence, evaluators, resolver, decision record, replay, CI rules. |
| **Out of scope** | No code. |
| **Architectural layer** | Documentation |
| **Depends on** | PBDIGIT-121 · PBDIGIT-119 · PBDIGIT-120 |
| **Blocks** | PBDIGIT-132 |
| **Files / areas likely affected** | developer_guide/election/ |
| **Acceptance criteria** | Every snippet grounded in committed code. |
| **Tests required** | none |
| **Architectural invariants** | Documentation reflects runtime. |
| **Definition of Done** | Guide + index updated. |
| **Risk** | Low. |
| **Evidence required** | Diff. |

### `PBDIGIT-132` — Constitutional Governance Runtime v1.0 freeze

| | |
|---|---|
| **Stage** | V1 |
| **Architecture gate** | A, B, E → **ARB architecture approval required in addition to PO authorisation** |
| **Purpose** | Declare and freeze v1.0 on evidence. |
| **Problem** | — |
| **Scope** | Verify the readiness-review §19 Definition of Done item by item; record the freeze. |
| **Out of scope** | No new scope. |
| **Architectural layer** | Governance |
| **Depends on** | PBDIGIT-131 · PBDIGIT-124 · PBDIGIT-125 · PBDIGIT-126 · PBDIGIT-127 · PBDIGIT-128 · PBDIGIT-129 · PBDIGIT-104 · PBDIGIT-102 · PBDIGIT-86 · PBDIGIT-87 · PBDIGIT-88 · PBDIGIT-89 · PBDIGIT-122 · PBDIGIT-130 · PBDIGIT-133 · PBDIGIT-134 · PBDIGIT-137 · PBDIGIT-138 · PBDIGIT-142 · PBDIGIT-143 · PBDIGIT-146 · PBDIGIT-147 · PBDIGIT-148 · PBDIGIT-149 |
| **Blocks** | — |
| **Files / areas likely affected** | docs/publicdigit/reviews/ (freeze record) |
| **Acceptance criteria** | Every DoD item evidenced by a CI-run test. |
| **Tests required** | full suite + CI |
| **Architectural invariants** | All §7 invariants hold. |
| **Definition of Done** | PO/ARB acceptance (engineering never accepts its own work). |
| **Risk** | Declaring on documentation rather than evidence. |
| **Evidence required** | CI run; DoD table. |

### `PBDIGIT-134` — Resolve the `PBDIGIT-69` numbering collision

| | |
|---|---|
| **Stage** | DOC |
| **Architecture gate** | — |
| **Purpose** | Restore the never-reuse rule of the PBDIGIT sequence. |
| **Problem** | Two different tickets carry `PBDIGIT-69`: `PBDIGIT-69-operating-core-post-verification-items.md` and `PBDIGIT-69-voter-eligibility-cache-is-tenant-unaware.md` (both `50d1ff1b1`). References to `PBDIGIT-69` are ambiguous. |
| **Scope** | The PO chooses which keeps `69`; the other gets the next free number; every reference is re-pointed; a note records the old ID. |
| **Out of scope** | No content change to either ticket beyond the ID and a renumbering note. |
| **Architectural layer** | Documentation |
| **Depends on** | — |
| **Blocks** | PBDIGIT-132 |
| **Files / areas likely affected** | docs/publicdigit/backlog/, references across docs/ |
| **Acceptance criteria** | Exactly one file per PBDIGIT number; every former reference resolves to one ticket. |
| **Tests required** | none (`npm run knowledge-lint` if applicable) |
| **Architectural invariants** | One ID names one thing permanently. |
| **Definition of Done** | Committed rename with reference updates. |
| **Risk** | Missed references — grep the whole repository. |
| **Evidence required** | Grep before/after. |


---

## 15. Definition of Done — applies to every implementation ticket

A ticket is done only when all of the following hold:
1. It was authorised by the PO before work started (EP-01).
2. RED preceded GREEN, in separate commits where production code changes.
3. The change stays inside the ticket's scope and out-of-scope list.
4. The C-2 characterization suite is unchanged and green, except for changes the ticket names.
5. There is a developer guide under `developer_guide/<area>/` (CLAUDE.md standing rule).
6. Commit subject: `(PBDIGIT-nn)`. One story, one commit (or one paired `-DOC` commit).
7. The ticket's "Evidence required" is attached: exact commands, totals and the diff scope.
8. Session log and CONTEXT are updated.
9. Acceptance is by the PO/ARB. **Engineering supplies evidence and never accepts its own work.**

## 16. Overall v1.0 completion criteria

These are the readiness review §19 criteria, each owned by a ticket.

| # | Criterion | Owned by |
|---|---|---|
| 1 | Characterization complete: 0 infrastructure errors; every gate and defect pinned | `76`–`84` |
| 2 | One constitution, one hash, verified on load, immutable at the H-10 level | `101`–`104` |
| 3 | The CAST_VOTE decision is produced only by the resolver + registered policies from a typed context; no middleware or controller decides participation | `109`–`111`, `117`–`121`, `124`, `127` |
| 4 | All 7 rules are decided by an evaluator + a resolver policy under explicit law | `105`–`108`, `111` |
| 5 | Every decision is persisted; replay reproduces it with DB access disabled | `112`, `114`, `129` |
| 6 | 14 parity paths green or approved-divergent | `115`, `116` |
| 7 | Deletion manifest executed | `119`–`122` |
| 8 | CI rules 1–9 enforced in a blocking job | `123`–`129` |
| 9 | Documentation matches runtime | `130`, `131` |
| 10 | Freeze accepted by the PO/ARB | `132` |

---

**Traceability:**
- **Sources:** `2026-09-30-constitutional-runtime-reality-audit.md` and `2026-09-30-constitutional-runtime-migration-readiness.md`.
- **Numbering:** `docs/publicdigit/backlog/README.md` §"ID conventions".
- **Related existing tickets:** `PBDIGIT-35/37/41/42/45/49/64/65/66/68/69`.
- **ADR:** `ADR_20260806_1620` (PROPOSED).
- **Commits:** `ba4cfa9c1` (`assignVoter` removed), `f2c2cc4ee`, `606c362aa` (lifecycle rules), `50d1ff1b1` (the `PBDIGIT-69` collision).
- **Tooling:** the ticket data and consistency check (74 tickets, no dependency cycles; "Blocks" computed as the inverse of "Depends on") were generated from one source file in the session scratchpad.

# Plan — CodeController refactoring: R0 blueprint → R1 real-election boundary (PBDIGIT-238)

## Context

`app/Http/Controllers/CodeController.php` (1080 lines in the repository) serves the real-election code steps (`v/{vslug}/code/create`, `POST code`, agreement GET/POST). It interweaves context resolution, membership, legacy eligibility (`users.can_vote`, voter-slug shortcut, demo session bypass), participation, IP policy, code lifecycle (issue / verify / regenerate / notify), voter-slug lifecycle (expiry extension), two workflow trackers (`VoterStepTrackingService` + legacy `VoterProgressService`), agreement, audit and HTTP. It carries demo behaviour — including a block that **resets a consumed participation record** — and logs plaintext codes (VE-06).

Two principal-architect suggestions drive this plan:
- `docs/publicdigit/brainstorming/voting_engine/refactor_code_controller.md` — approve a narrow first gate *"CodeController Cleanup — Real Election Boundary"*; PreVotingGate / CodeWorkflow later; no `PrecheckController`.
- `docs/publicdigit/brainstorming/voting_engine/refactor_controller1.md` — capabilities (VoterSlug journey · PreVotingGate with policies → `PreVotingDecision` · VotingCode lifecycle · VotingJourney · Agreement use case); use cases, not god services; sequence **R0 → R7**; **next step R0: Responsibility Discovery Matrix → "CodeController Refactoring Blueprint v1.0" before touching production code**. *(Note: that document cites a 2,161-line controller; the repository file has 1,080 lines — the blueprint is built from the repository file.)*

**This plan authorises only R0 and R1**, each ending in a STOP. R2–R7 are listed as a roadmap and each needs its own plan and approval.

**Finding that shapes R1 (FACT, source):** demo elections **do reach** the real CodeController — `VerifyVoterSlugConsistency` (84–102) refuses only *demo route + real election*, not the reverse; `ensure.election.voter` bypasses demo; `DashboardResolver::getActiveVoterSlug()` has no type filter and redirects in-progress slugs to `slug.code.create` (93–94). Removing demo branches is therefore a behaviour change.

**PO decisions (2026-10-05):** demo election on the real code routes → **refused, fail closed** · logging cleanup in R1 = **plaintext codes only**.

## Approval amendments (principal architect, 2026-10-05 — binding)

1. **Election types:** R0 establishes from the repository that `real` is the only election type legitimately allowed through these routes; if another legitimate non-demo type exists → STOP and report before R1.
2. **No authorization redesign in R1:** `users.can_vote`, the voter-slug shortcut, VotingEntitlement, PreVotingGate, voting period, IP semantics, `can_vote_now`, regeneration — all unchanged unless directly required by the boundary or the secret-logging cleanup.
3. **Characterization ≠ approval:** pinned behaviour that conflicts with the target is labelled **CURRENT BEHAVIOUR + LEGACY/DEFECT** (slug presence ≠ entitlement; `users.can_vote` legacy; `can_vote_now` = process state; `has_voted` = participation; 30-minute window ≠ voting period).
4. **VoterSlug:** discovery only — creation / resolution / lifecycle mutation / owning authority traced; no move into CodeController, no new slug service.
- R0 findings carry: location · responsibility · authority · observed behaviour · defect ref · target responsibility · target owner · future phase.
- R1 boundary runs before Code reads/writes, session mutation, code generation, notification, workflow mutation. RED proves 403 + no Code created/modified + **no voter progress modified**; secret-logging test checks the **secret value** is absent. Final verification list and "not fully refactored" wording as stated in the approval. **No commit without explicit approval.**

## Governance (first)

1. Story `docs/publicdigit/backlog/PBDIGIT-238-codecontroller-real-election-boundary.md` (existing-story search: Track B report, VE-06, VE-09, F-C2) + README row.
2. Plan copy per ES-004.2: `docs/plans/20261005-<HHMM>-codecontroller-refactoring-r0-r1-plan.md`.

## R0 — Responsibility Discovery & characterization (no production change)

**R0.1 Blueprint** `docs/publicdigit/reviews/2026-10-05-codecontroller-refactoring-blueprint-v1.md` (placement via `php scripts/doc-placement.php`):
- Method-by-method (`create`, `store`, `showAgreement`, `submitAgreement`; helpers `getUser`, `getElection`, `isUserEligible`, `getOrCreateCode`, `verifyCode`, `markCodeAsVerified`, `advanceSlugStep`, `handleAlreadyVerified`, `generateCode`, `generateUniqueCodeForOrganisation`, `redirectToDashboard`, `handleValidationError`, `jsonOrRedirect`; inline membership checks and step recording) — **every operation** classified into: HTTP · Context Resolution · VoterSlug · Membership · Entitlement · Voting Period · Identity · IP/Security · Participation · Code Lifecycle · Voting Journey · Agreement · Audit · Legacy · Demo — and marked **KEEP / MOVE / DELETE / REPLACE / DEFER**, with current line, current authority, defect ref (F-C1…F-C15, VE-06, PBDIGIT-231/235, C1, R1-F6a) and target owner.
- **Overlapping authorities** made explicit: `users.can_vote`, voter-slug presence (`isUserEligible` shortcut — *slug ≠ entitlement*), `Code.can_vote_now` (= code/process verification state, not authorization), `has_voted`, step tracking vs legacy progress, the 30-minute value used as code lifetime **and** slug window (≠ election voting period).
- **VoterSlug creation traced** (read-only): CodeController does **not** create slugs; `VoterSlugService::getOrCreateSlug()` is called by `VoterSlugController` (139, 252), `ElectionManagementController` (421, 643 — demo start), `ElectionController` (129 `storeElection` — unrouted, 236); `ElectionVotingController` (191, 213) obtains its slug by another path — to be traced in R0; CodeController mutates slug lifecycle in `create` (163–169).
- Target mapping to the R2–R7 capabilities; decisions that need the PO listed, none taken.

**R0.2 Characterization tests** — new `tests/Feature/Voting/CodeControllerRealElectionCharacterizationTest.php` (synthetic data, `RefreshDatabase`, fixtures as in `tests/Feature/Voting/AtomicParticipationConsumptionTest.php` / `ElectionScenarioFactory`). Real elections only; pins today's behaviour, **all GREEN at HEAD**: slug voter first visit creates one Code · missing membership refused · consumed participation → `create` redirects "already voted", `POST code` refused, agreement GET/POST refused · wrong code refused · expired code refused · valid code → `can_vote_now = true`, redirect to agreement · already-verified voter short-circuits · IP limit refusal · agreement accepted → `has_agreed_to_vote`, redirect to ballot.

**R0.3 Baseline** — run in isolation and record pass/fail at HEAD: `tests/Feature/CodeControllerTest.php`, `CodeControllerSlugRedirectTest.php`, `ExpiredCodeAutoRegenerationTest.php`, `Characterization/Phase1/CodeRegenerationCharacterizationTest.php`, `Election/VotingProcessEntitlementBoundaryTest.php`, `Election/LegacyVotingFlagsBoundaryTest.php`, `Election/ElectionOnlyEntitlementPinTest.php`, `Election/ElectionShowControllerTest.php`, `Integration/VotingMembershipIntegrationTest.php`, `VoterWorkflowIntegrationTest.php`, `VoterStepWorkflowTest.php`, `PreventMultipleVotingTest.php`, `MultipleVotePreventionTest.php`, `Demo/*`, `tests/Feature/Voting/*`.

**STOP → PO review of blueprint + characterization.**

## R1 — Real-election boundary (CodeController only)

| # | Change | Site |
|---|---|---|
| 1 | Real-only guard in `getElection()`: `type !== 'real'` → `abort(403, …)` — verified: `create` (54), `store` (231), `showAgreement` (406), `submitAgreement` (528) call `getElection()` before any Code read/write or session write | ≈655 |
| 2 | Remove demo session bypass from `isUserEligible()` (neutral on slug routes) | 685–688 |
| 3 | Remove demo re-vote reset block in `getOrCreateCode()` | 719–756 |
| 4 | `real && has_voted` → unconditional `has_voted` | 108, 285, 428, 558 |
| 5 | Remove demo comments / class-doc demo text | 49, 712–713, 761 |
| 6 | Drop plaintext code values from log contexts (keep the events): `'code'` (181, 799), `'input' => voting_code` (245), `'new_code'` (863), `'expected'`/`'submitted'` (885–886) | listed |

**RED first** — new `tests/Feature/Voting/CodeControllerRealElectionBoundaryTest.php`: (a) demo election on `slug.code.create` / `slug.code.store` / agreement → 403, no Code created or modified; (b) demo code with `has_voted = true` is never reset; (c) `Log` spy: no log context contains the plaintext code on create, verify (right / wrong) and regeneration. **STOP → PO review of RED.**

**GREEN** — items 1–6 only, Read → Edit (no sed). **STOP → diff.**

**Verify** — RED file green; R0.2 characterization still green; baseline suites: 0 previously passing tests fail; any demo test that used the real code routes is **reported, not silently adapted** (PO decides); grep: no `demo` / `selected_election_type` / `type === 'real'` in CodeController, no code value in a `Log::` context.

**Explicitly unchanged in R1:** `withoutGlobalScopes()` queries (PBDIGIT-235), `users.can_vote` and the voter-slug shortcut in `isUserEligible` (R3 / Phase E), `can_vote_now` + regeneration (PBDIGIT-231), IP limit (C1, F-C7), both step trackers, GET mutation in `showAgreement`, unused imports, `VoteController`, Demo controllers, middleware, `DashboardResolver`, VE-01, ADR / S2. No migration, no route change.

**Follow-up backlog items (docs only, with the story):** DashboardResolver sends demo slugs to real routes + missing reverse type check in `VerifyVoterSlugConsistency` (dead end after R1) · `VoteEligibility` demo-session early return skips the lifecycle (F-C2) · other sensitive logging (`$request->all()`, e-mails).

## Roadmap after R1 (not authorised by this plan; each its own plan + approval)

R2 `VotingRequestContext` (User · Election · VoterSlug?) · R3 `PreVotingGate` orchestrating policies (Membership, VotingPeriod, Participation, Identity, Network) → `PreVotingDecision` with reasons; removes the slug shortcut and `users.can_vote` · R4 VotingCode use cases (Issue / Verify / Regenerate; IP policy out of `verifyCode`; DB-enforced code uniqueness) · R5 VotingJourney (retire `VoterProgressService`; no state repair on GET) · R6 `AcceptVotingAgreement` · R7 thin CodeController (context → use case → HTTP). Rejected: `PrecheckController`, `CodeManager`, `VoterManager`, authorization in VoterSlug, IP policy in Code, ballot validation in CodeController, simultaneous VoteController refactor.

## Close-out (per gate)

Developer guide `developer_guide/election/code_step/00_index.md` + one file per gate · session log · `CONTEXT-publicdigit.md` · commits only after PO approval: R0 `docs/test(voting): … (PBDIGIT-238)`, R1 RED `test(voting): …`, R1 GREEN `refactor(voting): …`.

## Risks

Demo journey via the dashboard hits a 403 until the routing follow-up lands (accepted by the fail-closed decision) · tests that drove demo elections through real code routes would fail — reported, not adapted · characterization may expose behaviour the blueprint classifies as defects — recorded, not fixed in R0/R1.

## Verification (end-to-end)

R0: `php artisan test tests/Feature/Voting/CodeControllerRealElectionCharacterizationTest.php` green at HEAD; blueprint covers every method/operation (cross-checked against the file). R1: boundary test RED before / GREEN after; characterization + baseline unchanged except decided changes; grep checks; manual — real voter `v/{slug}/code/create` unchanged, demo slug on the same route → 403.

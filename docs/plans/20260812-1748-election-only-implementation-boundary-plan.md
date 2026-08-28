# Election-Only Mode — Implementation Boundary & Slice Plan (Session 3)

**Date:** 2026-08-12 · **Stream:** Session 3 (Implementation) · **Status:** ✅ **EP-01 APPROVED WITH CONDITIONS (PO, 2026-08-12).** Slice 1 execution is GATED on canonical Election Manifesto rule IDs (condition 2). No production change has been made.

> **The current implementation target is Election-Only Mode only. Full Membership Mode is deferred.**

**EP-01 conditions (binding on every slice):**
1. No Organisation Membership → ElectionMembership cascade/suspension in this phase (Slice 1 boundary corrected below).
2. Slice 1 does not execute until the Election Manifesto carries canonical stable rule IDs for its invariants. Minting IDs is **Session 2's responsibility**; Session 3 must not invent them.
3. Slice 1 stays protective/TDD-first: RED → STOP → report → authority check; RED never auto-authorises repair.
4. Slices 2–4 proceed only where the invariant is already authorised and independent of unresolved governance questions.
5. Full Membership remains completely outside Session 3.
6. No production rule may be created merely to satisfy a test. Direction: business authority → Manifesto rule → TDD test → minimal implementation → verification; Session 1 verifies independently.

**Authority consumed (and nothing else):** Election Manifesto rule IDs, whose underlying business decisions have already been adopted *(IDs pending — Session 2)*. The documents below remain **traceability evidence**, not the canonical runtime/design authority:
- The 11 adopted D-ENT-1 clauses (`docs/publicdigit/reviews/2026-08-12-adr-002-amendment-proposal-v2-from-adopted-d-ent-1.md` §0 — the clause list is adopted; the surrounding proposal is NOT).
- Approved business rules A-1…A-6 (`docs/publicdigit/reviews/2026-08-12-election-membership-governance-decision-package.md:18-23`).
- PO sequencing decision *"IMPLEMENT ELECTION-ONLY MODE FIRST"* + guardrails G-1…G-4 (`docs/publicdigit/reviews/2026-08-12-election-only-first-governance-baseline.md`).
- ADR-T11 (no voter↔vote linkage) — constitutional, build-breaking.
- The Session 3 commission + EP-01 conditional approval (PO, 2026-08-12, this session).

**Explicitly NOT consumed as authority:** ADR-002 amendment proposal v2 (proposed only) · BR-1.1 Option B (recommended only) · Q3 candidates (none selected) · Officer Guide / `docs/election_management/*` (class D) · any failing test · any implementation behaviour.

---

## 1 · Answers A–J (commission §5)

### A. What is the current Election-Only workflow?

Traced end-to-end (evidence: two read-only code traces, this session):

```
Election creation (ElectionManagementController:163 snapshots voter_source_strategy from org)
  → Chief opens import (routes/organisations.php:259-266; gates: election.state:import_voters + manageVoters policy)
  → VoterImportService::importElectionOnly() (app/Services/VoterImportService.php:237-345)
      creates: User (email_verified_at=now) · UserOrganisationRole (vestigial) ·
               OrganisationUser(active) · ElectionMembership(role=voter, status='active') ·
               VoterInvitation(token, 7d) + SendVoterInvitation job
  → invitee sets password via /invitation/{token} (VoterInvitationController) → logged in → elections.show
  → entry surface eligibility (ElectionVotingController:42-44: role=voter AND status!=='removed')
  → elections.start issues/refreshes VoterSlug (same weak predicate; lifecycle canVote(); IP check)
  → slug routes gated by ensure.election.voter → User::isVoterInElection()
      (role='voter' AND status='active', 300s tenant-unaware cache) — the ONLY enforced voter gate
  → code entry → agreement → ballot (voting.active middleware: ElectionLifecycle::canVote())
  → VoteController::store(): trust evaluator → lifecycle gate → fresh membership check →
      lock → exhaustion check (codes.has_voted) → persist vote (NO user_id) → mark codes/slug voted
```

### B. What business behaviour is already implemented?

| Adopted rule | Implemented? | Evidence |
|---|---|---|
| Clause 9 — org membership (Member aggregate) not required | ✅ | import creates no `members` row; `ElectionOnlyModeTest:142` asserts eligibility without Member record |
| Clause 10 — person becomes ElectionMember directly for the specific election | ✅ | `VoterImportService.php:302-312` writes `(election_id, user_id, role=voter)` |
| A-1 — entitlement is election-specific | ✅ (predicate level) | every gate filters `election_id` (`User.php:315-328`; `EnsuresVoterMembership:34-39`) |
| Clause 5 — Chief may suspend for election-specific reasons | ✅ (two paths) | one-click `ElectionVoterController:222-241`; two-actor propose/confirm `:290-360` |
| Suspended member cannot vote | ✅ but INCIDENTAL | only via `status='inactive'` hitting `isVoterInElection()`; nothing enforces `suspension_status` (G-2 load-bearing) |
| One-vote / exhaustion | ✅ | `VoteController:1705-1716` on `codes.has_voted`; slug `status='voted'` |
| ADR-T11 anonymity | ✅ | `votes` has no `user_id` (migration `2026_03_05_000011:14`) |
| Lifecycle governs voting window | ✅ | `EnsureVotingActive:49`; `VoteController:220,1610` |
| Invitation workflow | ⚠️ partial | delivery job exists; failures invisible to Chief; no resend; row errors swallowed |

### C. What exact behaviour is missing or incorrect (within adopted scope)?

**C-blocked — defect exists but its correct behaviour depends on an OPEN decision (must NOT fix):**
1. Suspended voter reported eligible + issued a fresh VoterSlug at entry (`ElectionVotingController:42-44,111,163-191`) → **Q-E1/Q-E2**.
2. Suspension enforcement incidental; `suspension_status` enforced nowhere; restore doesn't reset it → **Q3, BR-1.8, BR-1.13**.
3. `invited` status has no producer; import writes `active` pre-activation → **BR-1.12**.
4. Removal semantics/reversibility → **BR-1.1/1.2**.
5. Tenant-unaware voter cache + tenant-scoped membership lookup (PBDIGIT-65/-69) → tenant-boundary ownership is an undecided architecture question (PBDIGIT-69 D-1).

**C-open — defect inside adopted scope, NOT gated by any open business rule (implementation candidates):**
6. Import row-level errors are swallowed — `$results['errors']` never surfaced (`VoterImportController:142-147`); a failed import reads as success.
7. Import writes are non-atomic — 5 writes/row, no `DB::transaction` (`VoterImportService`, whole file).
8. `assigned_by`/`assigned_at` never set on import (`VoterImportService:302-312`) — audit default (BR-1.5/1.6 safe default: mandatory audit).
9. `VoterImportController::resolveElection():168-177` never verifies `election->organisation_id === $organisation->id` (sibling controller does).
10. Invitation `email_status`/failures invisible to the Chief; no resend.
11. **Test-estate gap (the largest):** no test anywhere asserts a suspended voter cannot reach the ballot; none pins credential-possession-does-not-override-suspension; none pins election-specificity negatively (membership in X grants nothing in Y); none pins clause 9 at the import boundary.

### D. Which domain object owns each business decision?

| Decision | Owner today (measured) |
|---|---|
| Entitlement existence | `ElectionMembership` row (election_id, user_id, role=voter) |
| Exercisability | **no dedicated home (Q3 open)** — incidentally `ElectionMembership.status` |
| Voting window | `ElectionLifecycle` snapshot (`canVote`) |
| Vote consumption | `codes.has_voted` + `voter_slugs.status` (NOT `election_memberships.has_voted` — never written) |
| Voter-management authority | `ElectionOfficer` (role chief/deputy, election-scoped) via `ElectionPolicy::manageVoters` |

### E. Which application/use-case operation executes it?

Import: `VoterImportService::importElectionOnly()`. Assignment: `AssignVoterHandler`/`BulkAssignVotersHandler`. Suspension: `ElectionMembership::proposeSuspension/confirmSuspension` + `ElectionVoterController`. Ballot: `VoteController` + middleware chain.

### F. Which authorization boundary protects it?

Import/manage: `election.state:import_voters` middleware + `manageVoters` policy (server-side, election-scoped). Ballot: `ensure.election.voter` + `voting.active` + `vote.eligibility` middleware; re-checked in-controller and again inside the store transaction.

### G. What persistence represents the resulting state?

`election_memberships` (entitlement; partial unique `(user_id, election_id) WHERE deleted_at IS NULL`; FK to org-roles deliberately dropped 2026-05-19 for Election-Only) · `voter_invitations` (delivery) · `voter_slugs` + `codes` (credential/exhaustion) · `votes` (anonymous).

### H. What HTTP/interface behaviour exposes it?

Import screens (Inertia `Elections/Voters/Import`), voter list (`Voters/Index` — renders `invited` state that cannot occur), entry page `elections.show` (projects `isEligible`/`canVote`), slug voting routes. Interface currently *projects* predicates — except `elections.show`, which projects a DIFFERENT (weaker) predicate than the gate enforces.

### I. What tests will prove the workflow? → §3 slices.

### J. What is explicitly NOT being implemented? → §4.

---

## 2 · Backlog classification

*(To be appended when the backlog-inventory sweep completes; tickets are inputs classified against this workflow, never the driver.)*

Known so far: PBDIGIT-64/65/67/69 = defects whose correct behaviour depends on open decisions or separate streams — NOT fixed here. PBDIGIT-66/68 = governance (Session 2 / PO). Full-Membership import breakage (`bulkAssignVoters` missing) = FM register, deferred.

---

## 3 · Vertical slices (strict TDD; dependency order)

Every slice: business invariant → smallest failing test (RED) → minimal production change → focused run → wider feature run → evidence. Tests target the **adopted business outcome**, never a storage mechanism (Q3-safe).

### Slice 1 — Pin the adopted entitlement surface (tests only; protective)
**⛔ GATED (EP-01 condition 2): does not execute until Session 2 mints canonical Election Manifesto rule IDs for these invariants.** Tests are drafted (`tests/Feature/Election/ElectionOnlyEntitlementPinTest.php`) with interim `@see` traceability to the adopted decisions; the `@see` references switch to Manifesto IDs before the first run.
**Invariant set (corrected per EP-01 condition 1):** no `members` row required — at the *ballot gate*, not just assignment · election-specificity (membership in election X grants NOTHING in election Y — negative test at the gate) · **an Election-Only ElectionMember in a non-active/suspended state cannot exercise the ballot, regardless of credential possession** (the two existing *Election-Chief* suspension mechanisms — two-actor workflow and single-actor state effect — are pinned separately; the Organisation-Membership-removal cascade is Full Membership behaviour and is EXCLUDED from this phase) · one-vote rule.
**Production change: none intended.** These are RED-if-broken pins that establish the approved behaviour and protect guardrail G-2. **If any pin REDs: STOP → report the finding → establish whether existing authority actually authorises repair. RED never auto-authorises a fix.**
**Why first:** the estate's biggest measured gap; every later slice stands on these assertions.

### Slice 2 — Import integrity (Election-Only import path)
**Invariant:** an Election Chief's import either admits a person verifiably or reports failure — silent partial success is not admission.
RED: feature tests — import with an invalid row surfaces the row error to the Chief; import result message includes failures; per-person write group is atomic (membership without invitation impossible for new users).
GREEN (minimal): surface `$results['errors']` in `VoterImportController::import()`; wrap per-row writes in `DB::transaction`; set `assigned_by`/`assigned_at` (BR-1.5/1.6 safe default — recorded as default, not decision).
**Does NOT touch:** status value written at admission (`active` stays — BR-1.12 is PO's), eligibility policy wiring, `organisation_users` fabrication (recorded as question Q-S3-1, not changed).

### Slice 3 — Route/organisation coherence (hardening)
**Invariant:** an import request addressed to organisation O for election E is valid only if E belongs to O.
RED: cross-org route test (404 expected, currently passes through). GREEN: mirror the sibling controller's `abort_if` in `resolveElection()`.

### Slice 4 — Invitation observability
**Invariant (commission point 11):** the invitation workflow is consistent — the Chief can see delivery state.
RED: voter list exposes `email_status` per imported voter. GREEN: query + project `voter_invitations` in `ElectionVoterController::index`. Resend action deferred (scope guard) unless approved.

**Deliberately NO slice for:** suspension mechanics, removal, restore, credential invalidation, entry-surface predicate alignment, `invited` producer, tenant cache — all blocked (§4).

---

## 4 · Explicitly NOT implemented (commission §3 + §4.1 blocks)

Full Membership Mode entirely (incl. the broken `bulkAssignVoters` path, org→election cascade, FM-1…FM-15) · anything deciding BR-1.12 / BR-1.13 / Q3 / BR-1.1/1.2 / Q-E1 / Q-E2 / BR-1.8 · ADR/Constitution changes · Master-Matrix-driven test repair · `scopeEligible()` activation (G-1) · vacating `status='inactive'` (G-2) · legacy cleanup (`ElectionUser.php` scaffold, `User.can_vote`, broken `VoterStrategyMigrationIntegrityTest`) — recorded, untouched.

## 5 · Questions surfaced for PO/ARB (not answered by this plan)

- **Q-S3-1:** Election-Only import *fabricates* an `organisation_users` row (`VoterImportService:293-299`). Clause 9 speaks of Organisation Membership (Member aggregate) — is the fabricated org-user linkage consistent with "may become an ElectionMember directly", or should Election-Only admission create no organisation linkage at all? (Blocks nothing in slices 1–4; recorded.)
- **Q-S3-2:** `email_verified_at = now()` at import (`:277`) marks unverified addresses verified and suppresses invitations for pre-existing unverified users — intended?
- The seven §4.1 questions remain with PO/ARB; slices 1–4 are chosen precisely because none depends on them.

## 6 · Risks

Slice-1 pins may expose real defects (then: report, don't fix without authority) · concurrent sessions share the repo (only `app/`, `tests/`, `database/`, implementation docs touched; `ElectionUser.php` working-tree change left alone) · sqlite-vs-pgsql schema drift on `election_memberships` constraints (tests will run on the pgsql `nrna_test` DB per phpunit.xml).

## 7 · Completion statement form (commission §16)

Each slice reports: IMPLEMENTED · VERIFIED BY IMPLEMENTATION TESTS · **NOT YET INDEPENDENTLY VERIFIED** (Session 1) · OUT OF SCOPE · OPEN GOVERNANCE QUESTIONS.

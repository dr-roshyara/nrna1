# Membership bounded context: DDD status (evidence-based)

**Date:** 2026-11-02 · **Author:** Claude (DDD architect review; authority: generated)
**Status:** REPORT. It records evidence. It accepts, decides and authorises nothing.
**Authority used:** the ratified Membership architecture, `docs/publicdigit/reviews/2026-10-08-membership-architecture-ratification-act.md` (the act).

**Git state (verified):**
- branch `election-flow-updated`;
- HEAD `85f3bb746`;
- 27 commits ahead of `origin`, none behind;
- the working tree holds other sessions' uncommitted changes, none under `app/Contexts/Membership`.
- **Membership code is unchanged since the previous report** (`docs/publicdigit/membership/reports/20261101-1642-membership-current-status-report.md`). The only later commits on Membership-related paths are `17ef51c5e` (S1 static guards, test-only) and documentation.

**Method:**
- Every claim below was **re-checked at HEAD**, not carried over.
- Tests were run only where they are database-free: plain `PHPUnit\Framework\TestCase`, no Laravel boot, run with `--do-not-cache-result`. The working tree was unchanged by the runs.
- No migration, database connection, artisan command or production access was used.
- **No completion percentage is given:** there is no complete, agreed inventory against which one could be justified.

**Labels:**
- **[V]** verified at HEAD (file, line, or test run);
- **[REC]** stated in a repository record, not re-verified here;
- **[O]** open.

---

## 1. Bottom line

1. The Membership **architecture is ratified** (2026-10-08).
2. The **new kernel is implemented and passes its tests**:
   - 188 database-free unit tests;
   - 51 architecture tests.
3. **No increment has a formal acceptance act.**
4. The kernel is **not wired to anything**: no binding, no caller, no route.
5. Production still runs on the **legacy Membership representation**, which has verified defects and four verified boundary violations against Election.
6. The **correction capability** that ARB-MEM-01 requires before cutover is only partly built:
   - Annul / Void exist in the kernel only;
   - there is no Correct, and no persistence of corrections.
7. The next step is **acceptance decisions, not more code**. The smallest authorisable code step is the V-2 fix (§7).

## 2. Ratified (binding)

| Item | Evidence |
|---|---|
| Ratification act: architecture, fact algebra, correction and annulment semantics, temporal, period and lifetime models, invariants | act §2–§8 (`:46-262`) [V] |
| Aggregate boundary D1: the Membership aggregate must not absorb Person identity, Governance competence, Election entitlement, accounting, committee participation | act §10 (`:283-307`) [V] |
| Context boundaries: Membership owns organisational membership truth; Governance owns competence, organs, authority; Election owns ElectionMembership, VotingEntitlement, eligibility | act §11 (`:309-350`) [V] |
| Election boundary: Election-only organisations need no Membership; "Membership itself never becomes the VotingEntitlement engine" | act §12 (`:353-395`) [V] |
| Concurrency requirement; frozen policy parameters **BD-I5** (no overlapping periods per person and organisation), **BD-REV** (trim), **BD-SYNC** (transactional) | act §13–§14 (`:396`, `:423-425`) [V] |
| Human ARB: **ARB-MEM-01** correction before cutover; **ARB-SEC-01** PBDIGIT-266 tests only on an isolated database; **ARB-DEC-01** D-1 = INV-FID; **ARB-MEM-02** Slice 1b conditionally accepted only; **ARB-TEST-01** TG-1…TG-5 separate | `2026-10-09-membership-human-arb-decision-record.md:11-15` [V] |
| Vocabulary EM-VOC-001 / 002 (Member aggregate; affiliation ≠ Membership) | register R-4 `2026-11-02-organisation-governance-strategic-design-status-assessment.md` [REC] |

## 3. Implemented and verified (tests pass at HEAD; **not** accepted)

| Component | Code | Test evidence (run 2026-11-02) |
|---|---|---|
| Semantic kernel (facts, Admit / End, intervals, resolution, invariants, Annul / Void decisions) | `app/Contexts/Membership/Domain/Organisational/` (27 files) | `tests/Unit/Contexts/Membership/Domain/Organisational`: **OK, 114 tests, 112,541 assertions** [V] |
| Application layer: Admit / End handlers, `DecideAndAppend`, idempotency, ports | `app/Contexts/Membership/Application/Organisational/` (18 files) | `tests/Unit/Contexts/Membership/Application/Organisational`: **OK, 61 tests** [V] |
| Infrastructure: `PostgresMemberRepository`, `InterimRoleAuthority`, date / time mapping | `app/Contexts/Membership/Infrastructure/Organisational/` (7 files) | `tests/Unit/Contexts/Membership/Infrastructure/Organisational`: **OK, 13 tests** (database-free parts only) [V] |
| Architecture fitness (purity, ledger isolation, Slice 1a / 1b boundaries, decision creation, valid-time coverage, Membership–Election boundary) | `tests/Architecture/Membership*.php` (7 files) | **all OK, 51 tests** [V] |
| Interim authority: Admit allowed to owner / admin; **End denied for every actor** pending G-1 | `InterimRoleAuthority.php:17-19, 40-41` [V] | covered by `InterimRoleAuthorityTest` (in the infrastructure run) [V] |

**Not verified here:**
- The four PostgreSQL feature tests (`tests/Feature/Contexts/Membership/Organisational/Postgres*.php`) touch a database. They were **not run**: there is no isolated test database (ARB-SEC-01; Q6).
- **There is no PostgreSQL evidence for Slice 1b at HEAD** [V: not run]; [REC: never produced on an isolated database].

## 4. Implemented, awaiting acceptance

| Story | Increment | Recorded status | Acceptance act |
|---|---|---|---|
| PBDIGIT-258 | Slice 0 semantic kernel | GREEN WITH CONDITIONS (`PBDIGIT-258…md:10`) [V] | **none.** Proposed disposition ACCEPT; "Human PO / ARB verdict: [to be completed]" (`2026-10-10-membership-g3-and-slice-dispositions.md` §2) [V] |
| PBDIGIT-259 | Slice 1a repository port, concurrency | GREEN WITH CONDITIONS (`:9`); S1-1 / S1-3 confirmed by the Principal Reviewer (ARB-MEM-04) [V] | **none.** Verdict blank (same record §3) [V] |
| PBDIGIT-260 | Slice 1b PostgreSQL adapter | GREEN; review ACCEPT WITH CONDITIONS (`:8`) [V] | **DEFERRED**; conditionally accepted only (ARB-MEM-02; dispositions §4) [V] |
| PBDIGIT-264 | Slice 1b follow-ups | IN PROGRESS: F-1, F-3 on the test double; F-2, F-4, F-5 held; Part 2 PostgreSQL held (`:7`) [V] | none |
| PBDIGIT-268 | Correction C-1 (Annul / Void, kernel only) | IMPLEMENTED, AWAITING REVIEW; five rules had no RED phase, accepted as a deviation (H-2) (`:7`) [V] | **none.** G3 decision blank (dispositions §1, `:86`) [V]. Recommendation: defer until the V-2 fix (decision paper 2026-10-16, PROPOSED) [V] |
| PBDIGIT-271 | Slice 2a Admit / End command rules | IN REVIEW (`:7`); commits `834a4f65c`, `14f89d18e` [V] | none |
| PBDIGIT-318 S1 | static Membership–Election guards | implemented `17ef51c5e`; `MembershipElectionBoundaryTest` OK [V] | **not accepted** (`PBDIGIT-318…md:25`) [V]. The story's own status line (`:7`, "no slice authorised") is stale against its progress section (`:20-28`) |

**Known open defect inside an implemented increment:**
- **V-2:** `decideAnnul` checks the **target's** decision provenance (`Member.php:156`), but not the **Annul's own** provenance.
- The contract requires that the Annul itself carries decision provenance (BR-9a; verification rev1 `:132, :151`).
- Still present at HEAD [V].

## 5. Not implemented

| Item | Evidence |
|---|---|
| **Wiring:** no container binding of `MemberRepositoryPort` / `AuthorityCheckPort`; no caller of the handlers; no route or UI | no reference outside the kernel folders in `app/` or `routes/` [V] |
| **Correct** fact (C-2) | no `Correct` class in `app/Contexts/Membership` [V] |
| **Persistence of Annul / Void** (C-3 / C-4) | `MembershipAct` has only `Admit`, `End` (`Ports/MembershipAct.php:10-11`); the PostgreSQL repository has no Annul / Void [V] |
| Slice 2b (AC-11…13) | held until the PostgreSQL hold is lifted and G-1 is decided (`PBDIGIT-271…md:14, 33`) [V] |
| Ending authority (G-1) | End denied by design (`InterimRoleAuthority.php:18-19`) [V] |
| Cutover from legacy (D-6 / R10) | gated by ARB-MEM-01 and C-0…C-10 [REC] |
| Published contracts for other contexts (`isMember`, PersonRef, version token) | proposals only (previous report §7) [REC] |
| Kernel test gaps TG-1…TG-5 | PBDIGIT-267 OPEN, not authorised (`:7`) [V] |

### 5a. Legacy live Membership: verified defects at HEAD

| # | Finding | Evidence |
|---|---|---|
| **V1** | `Member::endMembership` writes `election_memberships` (Membership → Election write). Scope comes from `BelongsToTenant` (**request context**, `TenantContext` or session; `app/Traits/BelongsToTenant.php:44-46`), not from the member's own organisation. **Correction to the previous report's L-1:** it is not unfiltered, but it is filtered by the wrong source. No production caller | `app/Models/Member.php:184-189`; `ElectionMembership.php:32` [V] |
| **V1b** | `Member::canVoteInElection`, a Membership → entitlement decision (contradicts act §12). No production caller | `Member.php:142-149` [V] |
| **V2** | the Election eligibility adapter reads `organisation_users` / `members` directly | `app/Contexts/Elections/Infrastructure/Policies/EloquentVoterEligibilityQueryService.php:91, 108-109` [V] |
| **V3′** | `voters.member_id` → `members` ON DELETE CASCADE | `database/migrations/2026_03_07_145529_create_voters_table.php:30` [V] |
| L-3 / G-9 | committee save overwrites `left_date` of every assignment not in the aggregate | `app/Contexts/Membership/Infrastructure/Repositories/EloquentCommitteeRepository.php:143-150` [V] |
| L-5 | web committee assignment passes a string where `AssignMemberDto` expects `MemberId`; this is a TypeError, which is caught and shown as a flash error | `app/Http/Controllers/Committee/CommitteeMemberController.php:37-56`; `AssignMemberDto.php:21` [V] |
| L-7 | `getDashboardRoles()` reads `user_organisation_roles` across organisations and caches per user | `app/Models/User.php:1080-1094` [V] |

**Guard status:**
- V1, V1b, V2 and V3′ are the recorded baseline of the S1 guards. The guards stop new violations; they do not remove existing ones.
- Removal is S2–S4, each gated (plan `docs/plans/20261102-0339-membership-election-boundary-remediation-plan.md:64-110`) [V].

## 6. Open decisions

| # | Decision | Owner | Source |
|---|---|---|---|
| D-a | Acceptance verdicts for 258, 259; G3 for 268; formal act for 260 | PO / ARB | dispositions record §1–§4 [V] |
| D-b | Q1 (C-1 defer + V-2), Q2 (G-6 lost outputs), Q3, Q6 (Membership test database), G-5 (wider suite) | PO / ARB | decision paper 2026-10-16 (PROPOSED) [V] |
| D-c | **G-1:** who may end a membership | authority owner | `InterimRoleAuthority.php:18-19` [V] |
| D-d | BD-REV-ER: may a correction absorb a whole period (recommended: refuse) | PO | act note N-4 (`:17`) [V] |
| D-e | S2-D1…D6 identifiers | ARB | ARB-DEC-01 (proposal only) [V] |
| D-f | PersonRef (XD-3a, PROPOSED); the `isMember` contract semantics | PO + ARB; Membership owner | register P-1 [REC] |
| D-g | Membership ↔ tenure (BQ-P2 / P3, O-9) | PO (statutes) | register O-9 [REC] |
| D-h | OQ-ME-1…4: Election's reaction to membership end; V3′ retention; foreign keys vs "shared persistence"; contract semantics | Election owner, PO, ARB | Membership–Election decision record §4 [REC] |
| D-i | DR-07a: the committee write model in the Membership folder moves to Governance? Membership owner's answers M-1…M-5; OQ-DR07-1 (`CommitteeAssociation`) | Membership owner, ARB | DR-07 paper §16–§17 [V] |
| D-j | OQ-01: which migrations, including the kernel tables, ran in production | Ops / PO | previous report §5 [REC] |

## 7. Blocked by dependencies

| Work | Blocked by |
|---|---|
| PostgreSQL evidence (Slice 1b, 264 Part 2); PBDIGIT-266 tests; S2, S3 | an **isolated test database** (ARB-SEC-01 gate FAIL; Q6; register O-4) |
| C-1 acceptance | the V-2 fix (if Q1 = defer) and the G3 decision |
| C-2…C-4 (Correct, persistence of corrections) | C-1 acceptance (CC §14.5: C-2 depends on C-1) [REC] |
| Slice 2b | PostgreSQL hold lifted, G-1 |
| Live writes / cutover | ARB-MEM-01 (verified correction capability), C-0…C-10, OQ-01 |
| Published contracts to Election / Governance | kernel accepted and bound; D-f; D-h |
| S2 (V1, V1b) | OQ-ME-1; approval to change `MemberTest` / `MemberCanVoteInElectionTest`; isolated database |
| Committee-code changes (L-3, L-5) | DR-07a (ownership), Membership-owner coordination |

## 8. Recommended next step

**First, a decision step (no code):** the PO / ARB record the verdicts in `2026-10-10-membership-g3-and-slice-dispositions.md`:
- Slice 0 and Slice 1a have no open condition (proposed disposition: ACCEPT).
- Q1 for C-1.

That converts four "implemented, awaiting" increments into a decided state. It is the cheapest progress available.

**Next implementation step, subject to its own authorisation: the V-2 fix inside PBDIGIT-268.**
- **What:** a test-first change. The Annul must carry decision provenance, otherwise it is refused (BR-9a).
- **Why this one:**
  - it is kernel-only and database-free, so it needs no isolated database;
  - it unblocks C-1 acceptance, which is on the critical path to ARB-MEM-01 (C-1 → C-2…C-4 → cutover);
  - it touches no other context and no gated item.
- **Ownership:** the code is owned by the Membership developer, so the authorisation must come through them.

**Not recommended now:**
- binding or cutover;
- Slice 2b;
- S2–S4;
- changes to committee code (DR-07a pending).

## 9. Limitations

- Production schema and data were not inspected; OQ-01 and the prevalence of the legacy defects are unknown.
- Passing tests show behaviour at HEAD; they are **not** acceptance.
- The previous report's statuses were not inherited: each was re-read from its source.
- Decisions made outside the repository are not reflected.

## 10. Decisions taken after this report (2026-11-02, PO)

This report is a snapshot; its §4 and §6 describe the state before these decisions.

| Item | Decision | Recorded in |
|---|---|---|
| PBDIGIT-258 (Slice 0) | **ACCEPTED**: the formal acceptance act | dispositions record §2; backlog 258; README |
| PBDIGIT-259 (Slice 1a) | **ACCEPTED** (option (a)) | dispositions §3; backlog 259; README |
| PBDIGIT-268 (C-1), G3 | **DEFERRED FOR EVIDENCE** (V-2) | dispositions §1; backlog 268; README |
| V-2 fix (story draft MEM-K1) | **authorised**, inside PBDIGIT-268 | same |

**Roadmap:** the delivery roadmap is `docs/publicdigit/membership/architecture/brainstorming/cross_domain_brainstorming/20261016_2219_membership_architecture_verification_and_completion_plan.md` (W0–W9). **ADOPTED by the PO on 2026-11-02, with corrections R-1 to R-6** (header status update in that file).

**Test run caveat:** the test run in §3 used the shared working tree. It does not satisfy the roadmap's W0 clean-copy evidence requirement.

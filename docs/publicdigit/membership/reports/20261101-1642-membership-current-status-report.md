# Membership: current status report

**Date:** 2026-11-01 · **Author:** Claude (research and reporting; authority: generated)
**Status:** REPORT. It records the state; it decides, accepts and authorises nothing. Every recommendation below is a recommendation only.
**Baseline:** branch `election-flow-updated`, HEAD `d7223d92e`. The three commits since `02fbb59af` are KnowledgeOS documents only and touch no Membership path.
**How it was produced:** read-only inspection of source, backlog, review records and the Round-2 cross-context review (2026-11-01, reported in conversation, not stored as a file). No test, migration, artisan command or database connection was run.

**Evidence labels:**
- **[S]** verified from current source or backlog files at the baseline;
- **[R2]** verified in the Principal Reviewer Round-2 review of 2026-11-01;
- **[REC]** recorded in a project record, but not re-verified here;
- **[INF]** inference;
- **[REC-ONLY]** recommendation;
- **[OPEN]** undecided.

---

## 1. Bottom line

1. **Two different things are called "Membership"** in this repository, and their status is very different:
   - **The new Membership kernel** (the ratified architecture, an append-only Admit / End fact ledger). It is implemented in slices, tested database-free, and **not formally accepted, not bound to any caller, and not live**.
   - **The legacy live Membership** (`members`, `organisation_users`, `user_organisation_roles`, plus the committee code that physically sits under `app/Contexts/Membership/`). It is what production runs on, and it carries the defects found in the cross-context reviews.
2. **No Membership increment has a formal acceptance act.** Slices 0, 1a, 1b, the correction slice C-1 and Slice 2a are all implemented and waiting for G3 / PO / ARB dispositions [S].
3. **Membership work has been dormant since 2026-10-17.** Every session log since then (2026-10-19 … 2026-11-01) is about Election, UI, IP security or KnowledgeOS [S].
4. **The decisions the rest of the platform needs from Membership are still open:** PersonRef (XD-3), the `isMember` query contract, and whether Membership conditions committee tenure (BQ-P2/P3) [OPEN].
5. **Cutover is gated:** a verified correction / annulment capability is required before any live Membership write (ARB-MEM-01) [S].

---

## 2. Two meanings of "Membership"

| | **New Membership kernel** | **Legacy live Membership** |
|---|---|---|
| What it is | Ratified architecture (2026-10-08): fact / correction algebra, temporal model, concurrency requirement | `members` (status active / expired / suspended / ended), `organisation_users`, `user_organisation_roles`, the application approval flow |
| Code | `app/Contexts/Membership/Domain/Organisational/`, `Application/Organisational/`, `Infrastructure/Organisational/` | `app/Models/Member.php`, legacy controllers, plus the committee aggregate in `app/Contexts/Membership/Domain/Committee/` |
| Tables | `org_memberships`, `org_membership_facts`, `org_membership_commands` (migration `2026_10_09_100000`) | the legacy tables above, plus `committee_assignments` and related tables |
| Live writer | **none.** No binding in `MembershipServiceProvider`; no HTTP or UI entry point [REC, verification rev1 §1.2] | yes |
| Status | implemented in slices; none formally accepted | in production; defects listed in §6 |

**Consequence [INF]:** the committee aggregate and its repositories live in the Membership folder. Any fix to the committee defects (C8, C11) therefore touches code under `app/Contexts/Membership/`, which a separate developer owns. Who owns committee facts (DR-07) is undecided. Until it is decided, every committee fix needs coordination with the Membership owner.

---

## 3. New-kernel delivery status

| Story | Increment | Implementation | Acceptance |
|---|---|---|---|
| `PBDIGIT-258` | Slice 0: semantic kernel | done | **GREEN WITH CONDITIONS**; awaiting Principal Reviewer approval (condition: confirm D-1) [S] |
| `PBDIGIT-259` | Slice 1a: repository port and concurrency | done | **GREEN WITH CONDITIONS**; awaiting acceptance (S1-1, S1-3) [S] |
| `PBDIGIT-260` | Slice 1b: PostgreSQL adapter | done; **not verified on PostgreSQL** (no isolated database) | GREEN; independent review ACCEPT WITH CONDITIONS; **conditionally accepted only** (ARB-MEM-02) [S] |
| `PBDIGIT-264` | Slice 1b follow-ups F-1…F-5 | F-1 (Option E) and F-3 done on the test double; F-2, F-4, F-5 held | IN PROGRESS, not accepted [S] |
| `PBDIGIT-268` | Correction slice C-1 (annul / void, kernel only) | done; some rules had no RED phase (disclosed) | **awaiting review / G3**. The 2026-10-16 decision paper recommends **deferring** acceptance until a small fix (V-2) is made (PROPOSED) [S] |
| `PBDIGIT-271` | Slice 2a: Admit / End command rules (database-free) | done and hardened (H1, H1-b, H2); commits `834a4f65c`, `14f89d18e` | **IN REVIEW**: Principal Reviewer review → PO / ARB acceptance [S] |

**Test evidence:** the A1 evidence refresh (`docs/publicdigit/membership/evidence/2026-10-16-a1-evidence-refresh/`, commit `f54037f96`) re-ran the kernel, application, infrastructure, C-1 and architecture suites database-free; all runs exited 0 [REC]. **There is no PostgreSQL evidence** for Slice 1b or PBDIGIT-264, and the wider Membership suites were not run [REC].

---

## 4. Approved decisions that bind Membership

| Decision | Content | Record |
|---|---|---|
| Ratification (2026-10-08) | Architecture D + B′, D1, fact / correction algebra, CFC+, temporal model, concurrency. Frozen: BD-I5 (no overlap per person and organisation), BD-REV = trim, BD-SYNC = transactional | `docs/publicdigit/reviews/2026-10-08-membership-architecture-ratification-act.md` |
| ARB-MEM-01 | **Correction before cutover** is a mandatory gate | `docs/publicdigit/reviews/2026-10-09-membership-human-arb-decision-record.md` |
| ARB-SEC-01 | `PBDIGIT-266` security tests only after an isolated test database is confirmed. The gate was **FAIL** when recorded | same |
| ARB-DEC-01 | D-1 = INV-FID is kept. S2-D1…D6 are a proposal only | same |
| ARB-MEM-02 | Slice 1b is conditionally accepted, not formally accepted | same |
| ARB-TEST-01 | Kernel test gaps TG-1…TG-5 = `PBDIGIT-267`, separate and not authorised | same |
| DB-1 / EP-Q6 | PostgreSQL only; calendar dates (T1) | `PBDIGIT-260` |
| EM-VOC-001 / 002 | Membership = the Member aggregate; a role assignment is not Membership | vocabulary records |
| Working principle | Lean loop (one decision → one plan → one review → one approval → one increment) | `.claude/MEMORY.md`; `MEMBERSHIP_DEVELOPMENT_STRATEGY.md` |

**Still open in the ratified model:** BD-REV-ER (absorbing correction; recommended: refuse) and the other policy-only BD / LQ / DS items [S].

---

## 5. Gates, held work and open questions

**Held** (nothing starts without separate authorisation) [S]:
- PostgreSQL Part 2;
- Slice 2b;
- provisioning and binding;
- HTTP / UI;
- the D-6 / R10 cutover;
- the ending authority (G-1, "who may end a membership"). Until then, the interim authority denies End.

**Cutover gate:** C-0…C-10 (`docs/publicdigit/reviews/2026-10-09-membership-pbdigit264-plan-ratification-pack-and-cutover-gate.md`) plus ARB-MEM-01.

**Production-schema question OQ-01 [INF, high probability, not confirmed]:**
- After the Laravel 12 deployment, seven migrations were run manually. The repository holds exactly seven migrations dated 2026-10-04 or later, including `2026_10_09_100000_create_org_membership_tables`.
- If the kernel tables are in production, gate C-10 was passed out of order. Every later schema change (C-4, the `end_kind` CHECK, F-4b) then becomes a forward migration against a released table.
- **Owner:** Operations / PO, who must confirm with `migrate:status` in production. (Verification rev1 §1.2.)

**Pending PO / ARB decisions (decision paper 2026-10-16, all PROPOSED):**

| ID | Question | Recommendation |
|---|---|---|
| Q1 | accept C-1 now or defer | defer and authorise the small V-2 fix |
| Q2 | close G-6 (lost test outputs) | record the evidence disposition |
| Q3 | preserve the reviewer's recommendation as a tracked, labelled record and fix its citation | yes |
| Q6 | dedicated Membership test database | request it |
| G-5 | run the wider Membership suite | a database-free investigation |

**Open backlog [S]:**
- `PBDIGIT-265`: the bulk and single eligibility predicates differ. OPEN, not authorised.
- `PBDIGIT-266`: live routes with no authorisation guard found. OPEN, **high priority until disproven**, tests gated by ARB-SEC-01.
- `PBDIGIT-267`: TG-1…TG-5, not authorised.
- `PBDIGIT-269`: runner hardening, not authorised.

**Related progress elsewhere:** `PBDIGIT-283` (test-database reset guard) is DONE (2026-10-14). It protects the shared test database. **It does not provide the dedicated isolated database** that ARB-SEC-01 and Q6 require [S].

---

## 6. Legacy live Membership: verified risks (from Round 2)

These are code findings. **How many real records they affect is UNKNOWN**: no authorised read-only database profiling has been done.

| # | Finding | Evidence | Kind |
|---|---|---|---|
| L-1 | `Member::endMembership` sets the user's `ElectionMembership` rows to `removed` filtered by `user_id` only, with **no organisation filter**. Ending a membership in organisation A removes the user from active elections in organisation B. It is also a Membership → Election cross-context write | `app/Models/Member.php:184-189` [R2] | security / integrity (election-adjacent) |
| L-2 | Ending a membership does not affect committee assignments, and assigning a member does not check Membership (C3) | `Member.php:172-191`; `Committee.php:503-593` [R2] | missing business rule (BQ-P2/P3 open) |
| L-3 | Saving a committee overwrites `left_date` on **every** ended assignment, and the save path has no organisation predicate (C8) | `Infrastructure/Repositories/EloquentCommitteeRepository.php:52-54,120-149` [R2] | **active history loss** |
| L-4 | `member_id` has four meanings: user id (A), user id in a 26-character column (B), `members.id` (C), and the party member number in Membership's `MemberId` value object, which uppercases its input (C9) | migrations; `Domain/ValueObjects/MemberId.php` [R2] | identity (XD-3) |
| L-5 | The web assignment writer always fails with a `TypeError` (string passed to an object-typed DTO), and the error is shown as a flash message | `Http/Controllers/Committee/CommitteeMemberController.php:37-42,54` [R2] | route health |
| L-6 | Role limits are never enforced in any of the six committee structures: limits are keyed by role name, lookups use a role path (C11) | `Committee.php:769-797`; `RolePath.php:66` [R2] | rule not enforced (BQ-P1) |
| L-7 | `getDashboardRoles()` merges roles from **all** organisations and caches them per user. The committee flag maps to `commission` (C12) | `app/Models/User.php:1080-1102`; `CheckUserRole.php:60` [R2] | authorisation (XD-4) |
| L-8 | `MemberStatus::canParticipateInForums()` exists but has no callers | `Domain/ValueObjects/MemberStatus.php:216` [R2] | dormant rule (Forum not built) |
| L-9 | The approval flow writes `members`, `organisation_users` and `user_organisation_roles` in one action | `MembershipApplicationController.php:253-282` [REC, not re-verified] | triple representation |

---

## 7. What other contexts need from Membership (contract proposals only)

| Contract | Consumers | Status |
|---|---|---|
| **MembershipQueryPort**: `isMember(org, person, asOf) → TRUE / FALSE / UNKNOWN + version` | Governance, Authorization, Forum, Adjudication | text proposed. Semantics of suspension and future-dated facts to be agreed with the Membership owner. **No code change requested** [REC-ONLY] |
| **PersonRef**: one person reference across stores | all | **XD-3 OPEN.** It does not imply a new table [REC-ONLY] |
| **Version / footprint token** (kernel version or `seq`) | write-guard consumers | to be confirmed with the Membership owner [REC-ONLY] |

The kernel can serve as the provider of these contracts only after it is accepted, bound and cut over. Until then, any consumer can read only the legacy representation [INF].

---

## 8. Recommended next steps (order of priority) [REC-ONLY]

1. **Protect.** Open separately authorised items for L-3 (stop the history rewrite) and L-1 (cross-organisation election removal), and route L-7 through `PBDIGIT-266`. Coordinate with the Membership owner, because the L-3 code sits in the Membership folder.
2. **Close the acceptance backlog.** The PO / ARB decide Q1, Q2, Q3, Q6 and G-5, then issue dispositions for 258, 259, 268 and 271. An increment that has been "implemented, awaiting review" for three weeks is a planning risk.
3. **Confirm OQ-01** (which migrations ran in production) before planning any further kernel schema change.
4. **Provision the dedicated Membership test database** (Q6). It unblocks Slice 1b and PBDIGIT-264 PostgreSQL evidence and the ARB-SEC-01 gate.
5. **Decide the cross-context items:** XD-3 (PersonRef), BQ-P2/P3 (Membership ↔ tenure), and the semantics of `isMember`.
6. **Then the correction capability required by ARB-MEM-01 (C-2…C-4), G-1, Slice 2b, and only after that the cutover.**

**Do not do yet:**
- start live Membership writes or cutover;
- cascade a Membership End into committee assignments;
- introduce a second Membership model or dual writes;
- repair historical assignment dates by assumption;
- treat any implemented slice as accepted.

---

## 9. Readiness test

Membership is ready to serve other contexts when all of the following hold:
1. formal acceptance acts exist for the kernel increments;
2. PostgreSQL evidence exists on an isolated database;
3. OQ-01 is resolved;
4. the correction capability required by ARB-MEM-01 is verified;
5. G-1 is decided;
6. the `isMember` contract and PersonRef are agreed with their consumers;
7. the legacy history and cross-organisation defects (L-1, L-3) are stopped.

---

## 10. Limitations

- The production schema and data were not inspected. Every prevalence figure is UNKNOWN.
- Statuses come from backlog files and records at the baseline. A decision made outside the repository is not reflected here.
- Findings marked [R2] were verified on 2026-11-01 at HEAD `02fbb59af`. Membership code has not changed since then.

**Traceability:**
- the Round-2 Principal Reviewer review (2026-11-01, in conversation);
- `docs/publicdigit/membership/architecture/brainstorming/cross_domain_brainstorming/20261011_0900_cross_context_consistency_and_simulation_study.md`;
- `…/20261016_2257_membership_architecture_verification_rev1.md`;
- `…/20261016_2325_membership_decision_paper_q1_q2_q3_q6_g5_principal_review.md`;
- `docs/publicdigit/reviews/2026-10-09-membership-human-arb-decision-record.md`;
- `docs/publicdigit/reviews/2026-10-11-cross-domain-decision-round-1-sheet.md`;
- backlog `PBDIGIT-258`…`271`, `283`;
- `.claude/CONTEXT-publicdigit.md`.

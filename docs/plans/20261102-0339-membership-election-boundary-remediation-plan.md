# Membership–Election boundary remediation plan

**Status:** **PROPOSED.**
- Accepted by the PO as a proposal on 2026-11-02 (decision record `docs/publicdigit/reviews/2026-11-02-membership-election-separation-decision-record.md`, Decision 2).
- **No slice may start without its own explicit approval** (EP-01).
- S1 is the first candidate.

**Backlog:** `PBDIGIT-318`. **Baseline:** branch `election-flow-updated`, HEAD `ce84016a6`.

## Objective

Enforce the ratified rule "no shared persistence and no Membership → entitlement shortcut" (`docs/publicdigit/reviews/2026-10-08-membership-final-ratification-verdict.md:46`) between Membership and Election, **within one application** (Decision 1, option A).
- Existing Election behaviour is preserved unless a separate decision authorises a change.
- **No Election redesign.**

## Background

Three verified violations (decision record §3):

| # | Kind of coupling | Finding |
|---|---|---|
| V1 | write coupling | Membership writes `election_memberships`. No production caller. A unit test asserts the write. Organisation filter by request context only. |
| V2 | read coupling | Election adapter reads `members`, `organisation_users`, `membership_types` directly (5 sites) |
| V3 | integrity coupling | foreign key `election_memberships` → `user_organisation_roles` with ON DELETE CASCADE (PostgreSQL); deployed state unknown |

**Correction (2026-11-02):** V3 as listed is **removed** in the migration source by `2026_05_19_000001_harden_election_memberships_for_election_only_mode.php` `up()`; the deployed state is unknown. The integrity coupling actually present is **V3′: `voters.member_id` → `members` ON DELETE CASCADE** (`2026_03_07_145529_create_voters_table.php:30`). S4 now targets V3′ (and verifies the deployed state of both).

## Scope

**In:**
- architecture guard rules;
- V1, V2, V3;
- *(updated 2026-11-02:* the known violations are now V1, V1b, V1c, V1d, V2 and V3′; V3 was corrected to V3′*)*;
- the tests that prove the rule.

**Out:**
- Election design (ElectionMembership roles, voter-source behaviour, the two Election folders, CQ-1);
- D-1 tenancy;
- changes to the ratified Membership kernel without its owner.

## Design decisions (already taken)

- **Option A:** one application; separate persistence ownership; contracts between contexts (decision record, Decision 1).
- **Membership supplies facts; Election decides eligibility and participation consequences.**
- **Tenant scope:** every cross-context operation must establish and validate its organisation explicitly, never relying on an ambient context. This is INV-ORG-SCOPE-1, an approved principle; it is not a new invariant.

## Slices

**Each slice has one backlog story, one commit and one developer guide, and needs its own approval.**

### S1: guard rules (first candidate)

| Rule | Enforcement |
|---|---|
| Membership does not depend on Election classes or write Election-owned tables | deptrac layer rule (`deptrac.yaml`) + static scan for writes to Election tables |
| Election infrastructure does not query Membership- or Organisation-owned tables | static scan covering `DB::table(...)`, raw SQL, query builder and Eloquent model references (deptrac sees classes only) |
| No new foreign key from Election tables to tables owned by other contexts | migration scan test |
| Tenant scope of cross-context operations | covered by INV-ORG-SCOPE-1 tests (separate track) |

**Completion criteria:**
- the checks **fail against V1–V3 today** (recorded as known baseline exceptions, so the suite stays meaningful);
- the checks **prevent new violations** of the same three kinds;
- static only; no database.

### S1b: hardened guard rules (authorised by the PO, 2026-11-02; test-only)

**Why:** the independent review of S1 found two shortfalls:
- the "static scan for writes to Election tables" covered only `election_memberships`;
- new violations could hide in baselined files.

The PO deferred S1 acceptance and authorised S1b.

**Scope:**
- write and reference detection for all Election-owned models and tables, using anchored patterns;
- a per-file, per-target ratchet;
- detector self-tests;
- more foreign-key forms, including landlord migrations;
- the out-of-scope coupling inventory (recorded in PBDIGIT-318);
- PHP 8.3.

**Deptrac:** the deptrac rule in the S1 row above was not delivered (guide 01 gives the reason). **PO decision 2026-11-02, option (b):** deviation acknowledged; deptrac layers are the later item PBDIGIT-321, not authorised.

**Exclusions:**
- no new scan paths;
- no automatic baseline growth;
- OQ-ME-3 and OQ-ME-4 are not resolved;
- no runtime change.

**Acceptance:** an independent review of S1b, then the PO.
### S2: remove V1 (pending; gated)

**Gates:**
- documented caller search (done statically: no production caller);
- confirmation that no supported workflow relies on it;
- **Election decision OQ-ME-1**;
- **PO approval to change `tests/Unit/Models/MemberTest.php:149-162`**, which currently specifies the violation;
- the isolated test database (the test is database-touching).

**Completion criteria:**
- Membership no longer writes Election data;
- a regression test for membership termination;
- Election's reaction, if any, implemented and owned by Election under its own approval.

### S3: V2 behind an approved contract (pending; gated)

**Gates:**
- **ARB approval of the contract semantics** (OQ-ME-4): fact vs decision; current vs as-of; unknown vs negative; unavailable source (never silently "not a member" or "eligible"); explicit organisation scope;
- agreement with the Membership owner (the contract should later read from the ratified kernel, not the legacy `members` table);
- the isolated test database.

**Approach:**
- Keep the existing Election port `VoterEligibilityPolicy`.
- Its adapter consumes Membership- and Organisation-published contracts instead of their tables.

**Completion criteria:**
- behaviour-locking tests of current eligibility outcomes run **before** the switch; any current behaviour that conflicts with ratified rules is recorded as a defect, not kept as the specification;
- tests show that **Election, not Membership, makes the eligibility decision**.

### S4: resolve V3 (pending; gated)

**Gates:**
- a read-only catalog inspection of the deployed constraint per environment (needs separate authorisation);
- **OQ-ME-2** (retention: which facts must survive to explain an election);
- **OQ-ME-3** (the ARB's reading of "shared persistence");
- an integrity design that prevents orphans under concurrency, if the foreign key is replaced;
- a rollout and rollback plan; no change during an active election.

**Completion criteria:**
- a forward-only migration;
- no deletion of election history caused by another context's deletion, unless explicitly decided.

## Task checklist

- [x] Verify V1–V3 (static)
- [x] Record Decision 1 and Decision 2
- [x] PO approval of S1 (baseline V1, V1b, V2, V3′)
- [x] S1 implemented
- [x] S1: **CLOSED as superseded by S1b** (PO, 2026-11-02). Not accepted on its own; its scope is covered by the accepted S1b. *(History: deferred by the PO, 2026-11-02, pending S1b.)*
- [x] S1b authorised (PO, 2026-11-02, test-only)
- [x] S1b implemented (`3aad77d20`)
- [x] S1b independently reviewed: recommendation (3), further changes (B2, `foreignIdFor`, `foreign(col, name)`, documentation)
- [x] S1b follow-up authorised (PO, 2026-11-02) and implemented: occurrence counts, foreign-key forms, self-tests, documentation corrections, PBDIGIT-320
- [x] S1b follow-up re-reviewed (same reviewer): recommendation (2), accept with the residual risks documented
- [x] S1b accepted (PO, 2026-11-02, with documented residual risks)
- [x] PO acknowledgement of the deptrac deviation: **option (b), 2026-11-02**. Acknowledged: the static test is the enforcement for this boundary. Deptrac layers recorded as a later, separately authorised item, PBDIGIT-321
- [ ] S2 scope extended to V1c and V1d (decided by the PO 2026-11-02; design needs approval)
- [ ] S2 scope extended to V1b: remove `Member::canVoteInElection`; move the voting-rights rule to Election; change `MemberCanVoteInElectionTest` (needs approval)
- [ ] OQ-ME-1…4 answered
- [ ] Isolated test database available (T-13)
- [ ] S2, S3, S4 each approved and done

## Progress

2026-11-02:
- plan created as PROPOSED;
- nothing implemented.

2026-11-02 (later):
- **The PO approved S1** with a baseline of "exactly V1–V3".
- **The pre-implementation scan found the V3 correction** (above). Membership → Election references: only V1. Election → Membership / affiliation data: only V2.
- **S1 is paused, not started:** its approved baseline names the wrong V3. It needs re-approval with the baseline V1, V2, V3′.
- **The PO re-approved S1 with the baseline V1, V2, V3′ (2026-11-02).** S1 may start; S2–S4 remain unapproved.
- **S1 RED run** (static test written with empty baselines) reports exactly:
  - V1 (`app/Models/Member.php`);
  - V2 (the Election adapter);
  - V3′ (`voters(member_id) -> members`).
  - The dropped `election_memberships` key is correctly not reported.
- **The same run found V1b:**
  - `Member::canVoteInElection(Election $election)` (`app/Models/Member.php:142-149`), a **Membership → entitlement shortcut**;
  - no production caller;
  - specified by `tests/Feature/Membership/Domain/MemberCanVoteInElectionTest.php`.
  - **Not in the approved baseline:** S1 paused again; the unfinished test was moved out of the repository (not committed). It needs a PO decision on V1b.
- **The PO added V1b to the baseline (V1, V1b, V2, V3′) and extended S2's scope to V1b (2026-11-02).**
- **S1 implemented:** `tests/Architecture/MembershipElectionBoundaryTest.php`.
  - GREEN: 3 tests, 6 assertions; no database.
  - Mutation checks passed.
  - Guide: `developer_guide/membership_election_boundary/01_s1_static_boundary_guards.md`.
  - **Status: implemented, awaiting acceptance** (engineering does not accept its own work).
  - Deviation from the plan: no deptrac rule, because deptrac's layers do not cover Membership, `app/Contexts/Elections` or `app/Models`. The static test covers all three rules (reason recorded in the guide).
- **2026-11-02, S1 review:**
  - evidence pack (author) and an independent review (fresh session) both found that S1 does not meet the write-detection promise;
  - the "Mutation checks passed" line above covers only the baseline ratchet, not detection coverage.
- **PO decision:** S1 deferred; S1b authorised (scope in "S1b" above).
- **S1b implemented:**
  - RED: 28 of 38 against stubs. GREEN: 41 tests and 52 assertions under PHP 8.5 and PHP 8.3.
  - New V1c and V1d were baselined by PO decision.
  - Guide 02; inventory in PBDIGIT-318.
  - A project-wide PHP-version mismatch was found and recorded as PBDIGIT-319.
- **2026-11-02, S1b independent review:** recommendation (3), further changes. B2 (a repeated use of a baselined target) did not meet the acceptance condition; `foreignIdFor` and `foreign(col, name)` were missed; the documentation overclaimed.
- **PO decision:** a narrow follow-up was authorised; baseline governance documented; X-8 → PBDIGIT-320.
- **Follow-up implemented:**
  - occurrence-count ratchet; the two foreign-key forms; self-tests; corrected guide 02 with known limits and false positives.
  - RED: 7 of 49. GREEN: 49 tests and 64 assertions under PHP 8.5 and 8.3.
  - Next: re-review by the same reviewer, then the PO.
- **Strategic criterion "No context decides for another": remains partly met.** S1 and S1b are regression guards; they remove no decision site.

## Risks

- callers that exist only dynamically or outside `app/` (V1);
- subtle eligibility changes (V2), mitigated by behaviour-locking tests;
- the deployed schema differing from migrations (V3);
- changes landing during an active election;
- false positives or negatives in the static scans (S1).

## Open questions

OQ-ME-1…4 (decision record §4).

## Next actions

PO decision on S1.

## Traceability

- Decision record `docs/publicdigit/reviews/2026-11-02-membership-election-separation-decision-record.md`.
- Governance map §5.
- Principal Reviewer review (2026-11-02).

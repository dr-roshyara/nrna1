# PBDIGIT-318: Membership–Election boundary remediation (ratified "no shared persistence")

**Type:** Architecture remediation · **Epic:** none · **Created:** 2026-11-02 · **Raised by:** strategic DDD discovery (Membership–Election separation).

| | |
|---|---|
| **Status** | `OPEN` · S1 (`17ef51c5e`) **CLOSED as superseded by S1b** (PO 2026-11-02; earlier deferred); **S1b ACCEPTED by the PO 2026-11-02 with documented residual risks** (after two independent reviews); S2–S4 gated. *(Superseded wording, kept as history: "S1b authorised (test-only) and implemented, awaiting independent review and PO acceptance".)* *(Superseded wording, kept as history: "plan PROPOSED; no slice authorised (S1 is the first candidate)".)* |
| **Classification** | **observed in code** (static, HEAD `ce84016a6`) |
| **Scope** | V1 Membership writes Election data; V2 Election reads Membership / Organisation tables; V3 cross-context foreign key |

## Rule

"There is no shared persistence and no Membership → entitlement shortcut" (`docs/publicdigit/reviews/2026-10-08-membership-final-ratification-verdict.md:46`, ratified).

## Plan and decisions

- Plan: `docs/plans/20261102-0339-membership-election-boundary-remediation-plan.md` (slices S1–S4).
- Decision record: `docs/publicdigit/reviews/2026-11-02-membership-election-separation-decision-record.md`.

## Correction and S1 status (2026-11-02)

- **V3 corrected:** the `election_memberships` foreign key is dropped by `2026_05_19_000001_…` `up()`. The real integrity coupling is **V3′ `voters.member_id` → `members` ON DELETE CASCADE** (`2026_03_07_145529_create_voters_table.php:30`).
- **S1:** approved by the PO with the baseline V1–V3; paused; **re-approved by the PO with the baseline V1, V2, V3′ (2026-11-02).**
- **V1b found by the S1 RED run:** `Member::canVoteInElection` (`app/Models/Member.php:142-149`), a Membership → entitlement shortcut. **The PO added it to the baseline** (V1, V1b, V2, V3′) and to S2's scope.
- **S1 implemented**, awaiting acceptance:
  - `tests/Architecture/MembershipElectionBoundaryTest.php` (GREEN, no database);
  - guide `developer_guide/membership_election_boundary/`.

## S1 review and S1b (2026-11-02)

**S1 acceptance evidence:**
- prepared by the implementing session;
- **independently reviewed** by a fresh session.
- Reviewer's verdict: defer S1 and do a test-only S1b. S1 does not meet the plan's promise to detect writes to Election tables, and new violations can hide in baselined files.

**PO decision (2026-11-02):**
- **S1 acceptance DEFERRED**, not rejected.
- **S1b AUTHORISED**, test-only, minimum scope:
  - complete the write and reference checks with anchored patterns;
  - a per-file, per-table ratchet;
  - record the out-of-scope coupling inventory;
  - preserve the existing protections;
  - pass under PHP 8.3, or investigate.
- **Exclusions:**
  - no new scan paths;
  - no automatic baseline growth;
  - OQ-ME-3 and OQ-ME-4 are not resolved by inference;
  - the strategic criterion is not "met" because S1b passes.

**S1b implemented** (committed as `3aad77d20`), developer guide `developer_guide/membership_election_boundary/02_s1b_hardened_boundary_guards.md`:
- **RED:** 28 of 38 tests failed against stubs.
- **GREEN:** 41 tests and 52 assertions under PHP 8.5, and under **PHP 8.3** (official `php:8.3-cli` image, platform check disabled in a scratch copy; see PBDIGIT-319).

**V1c and V1d, found by the S1b RED run; the PO added both to the baseline and to S2's scope (2026-11-02):**
- **V1c:** `Member::voters()` → `hasMany(Voter::class)` (`app/Models/Member.php:82-85`), the code side of V3′.
- **V1d:** the Membership job `CleanupExpiredInvitations` soft-deletes Election-owned `VoterInvitation` records (`app/Contexts/Membership/Infrastructure/Jobs/CleanupExpiredInvitations.php:38-43`).

**The S1 probes rerun against S1b:**
- D1–D4: caught.
- B1 (writes to `voters`), B5 (landlord FK) and B6 (`Voter` model): **now caught**.
- E7 (a new table in a baselined file): caught.
- **Still not caught:**
  - B2: a repeated use of an already-baselined target. Tracking is per file and target, not per occurrence.
  - B3 and B4: paths outside the scan, excluded by the PO.

## Out-of-scope coupling inventory (recorded 2026-11-02; NOT compliant, NOT baselined)

This is code outside S1's scan paths that touches the other context's data. It was found by the S1 evidence pack and the independent review at `17ef51c5e` / HEAD; the list is a minimum.

**Classification is open.** It depends on ownership (the Election and Membership owners) and on the ARB's OQ-ME-3 / OQ-ME-4: is a direct read "shared persistence"? Are `organisation_users` and `user_organisation_roles` Membership, Organisation or Governance data? Is `membership_types.grants_voting_rights` a fact or an entitlement decision?

| # | Site | What it does |
|---|---|---|
| X-1 | `app/Models/ElectionMembership.php:136-143` (`scopeEligible`) | reads `organisation_users` and `members.status` / `membership_expires_at` |
| X-2 | `app/Services/VoterEligibilityService.php:63-68, 76-85` | reads `organisation_users`, `members`, `membership_types.grants_voting_rights`, `fees_status` |
| X-3 | `app/Http/Controllers/ElectionOfficerController.php:73` | reads `user_organisation_roles` |
| X-4 | `app/Http/Controllers/Election/CandidacyManagementController.php:68` | `Rule::exists('user_organisation_roles', …)` |
| X-5 | `app/Models/Voter.php:41` | `belongsTo(Member::class)` |
| X-6 | `app/Models/Election.php:1860`; `app/Http/Controllers/Election/ElectionManagementController.php:91`; `app/Policies/ElectionPolicy.php:123`; `app/Traits/ChecksElectionAccess.php:16`; `app/Http/Controllers/CandidacyApplicationController.php:23` | `UserOrganisationRole::` reads |
| X-7 | `app/Policies/ElectionOfficerPolicy.php:17` | `DB::table('user_organisation_roles')` |
| **X-8** | **`app/Services/VoterImportService.php:284-299`** | **writes** `user_organisation_roles` and `organisation_users` (`firstOrCreate`) during voter import: an Election → affiliation **write**, a kind outside V1–V3′ |
| X-9 | `app/Http/Controllers/Membership/OrganisationRoleController.php:6`; `…/OrganisationNewsletterController.php:7` | Membership-folder controllers import `App\Models\Election` (probably read-only listings; ownership open) |

**Recommended, not decided:**
- a separate backlog item for X-8 rather than extending this story (**done:** PBDIGIT-320, by PO decision of 2026-11-02);
- a path-scope decision before any widening of the scan.

## S1b independent review and follow-up (2026-11-02)

**Independent review of S1b** (`3aad77d20`, fresh session): recommendation **(3) further changes required**. Findings:
- **B2:** a repeated use of a baselined target could still hide new writes, against the acceptance condition;
- `foreignIdFor` and `foreign(col, name)` were missed;
- the documentation overclaimed coverage.

**PO decision (2026-11-02):** a narrow follow-up was authorised:
- an occurrence-count ratchet, with explicit baseline changes on increase or decrease;
- the two foreign-key forms;
- self-tests, including the `$table` property detector;
- documentation corrections;
- the residual limits recorded;
- X-8 as backlog item PBDIGIT-320;
- the baseline governance documented (PO for routine baseline decisions; ARB for architectural policy and open ownership questions; a baseline entry is a known condition, not compliance).

**Follow-up implemented** (committed together with this record):
- RED: 7 of 49 against the old API.
- GREEN: 49 tests and 64 assertions under PHP 8.5 and PHP 8.3.
- The baseline counts match hand-counted occurrences.
- Probes:
  - B2a–B2d (one more occurrence) now **fail**, as they should;
  - removing an occurrence fails until the baseline is lowered;
  - `foreignIdFor` and `foreign(col, name)` are detected;
  - the shrink, landlord and foreign-key checks are unchanged.
- **Residual limits:** guide 02, "Known limits and false positives".

**Re-review of the follow-up** (same reviewer, 2026-11-02): recommendation **(2) accept with the residual risks documented**. A recommendation, not acceptance.
- Reproduced: 49 tests and 64 assertions on PHP 8.5.11 and 8.3.36.
- B2a–d caught; every test mutation killed; baseline counts verified by hand.
- Residuals added to guide 02: C15 (a count-preserving substitution), and the false positives C09 and F19.
- Reviewed test file sha256: `d7cb86f4ba0ec23018f4ca7c87fbff1055cc75740ae85bad8eada31be27c5ca1`.

**Status:**
- S1 **closed as superseded by S1b** (PO, 2026-11-02). Not accepted on its own; its guarantees are the accepted S1b's. *(History: deferred earlier the same day.)*
- S1b **ACCEPTED by the PO on 2026-11-02, with the documented residual risks** (guide 02, "Known limits and false positives"). The acceptance covers the reviewed test file (sha256 `d7cb86f4…`). It does not make any baselined dependency compliant, and it resolves no ARB question;
- deptrac deviation **acknowledged by the PO (option (b), 2026-11-02)**: the static test is the enforcement for this boundary; deptrac layers are the later item PBDIGIT-321 (not authorised);
- OQ-ME-3 and OQ-ME-4 open;
- the strategic criterion "No context decides for another" **remains partly met**.

## Not authorised

- runtime, schema or data changes;
- S2–S4;
- widening the scan paths;
- resolving OQ-ME-3 or OQ-ME-4.

*(Superseded wording, kept as history: "Any code, test or schema change, until the PO approves a slice.")*

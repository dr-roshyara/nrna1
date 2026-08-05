# PBDIGIT-EPIC-02 — Membership Management

**Journey segment:** `Create Committees → Import Members → Approve Members` — everything that must exist **before** an election is possible
**Epic status:** `IMPLEMENTED — NOT VERIFIED` · **Gated by** `PBDIGIT-00`
**Baseline:** `main` @ `9158ef11` · **Created:** 2026-08-05

---

## PBDIGIT-04 — Create committees

| | |
|---|---|
| **Customer goal** | *"Our organisation is governed by committees. I want to define them."* |
| **Business steps** | create committee → set type, code, geography, term → attach to parent committee (hierarchy) |
| **Route → code** | `routes/committee/committeeRoutes.php:48` create form · `:50` `POST /committees` · `:52,54` edit/update · `:42` index · `:44` committee dashboard · `:34,38` code/slug uniqueness APIs → `Committee/CommitteeManagementController` · `CommitteeController` → `app/Contexts/Membership/Domain/Committee/` aggregate → events `CommitteeCreated` · `CommitteeEstablished` · `CommitteeParentAttached` · `CommitteeTermUpdated` · `CommitteeLifecycleChanged` (`Contexts/Membership/Domain/Committee/Events/`) |
| **Business rules evidenced** | committees form a **hierarchy** (parent attachment is its own event); committees carry a **term** (time-bounded); code and slug are checked for uniqueness before creation; committee read/write separation is an accepted architecture decision (`docs/adr/ADR-0001-committee-read-write-separation.md`) |
| **Verification** | create a parent and a child committee → confirm hierarchy renders → confirm duplicate code/slug rejected → confirm term dates enforced |
| **Known findings** | this is the **most architecturally mature non-certified area** (dedicated context, aggregate, events, ADR). ⚠️ **~21 pre-existing Membership unit-test failures** exist on `main` (constructor drift, debt `L-7`) — they pre-date PB003 and are **not** a product defect, but they mean *"the tests pass"* cannot be claimed for this epic |
| **Status** | `IMPLEMENTED — NOT VERIFIED` |

## PBDIGIT-05 — Import members

| | |
|---|---|
| **Customer goal** | *"I have our member list in a spreadsheet. I want it in the platform."* |
| **Business steps** | upload the file → map columns → import → review failures |
| **Route → code** | `Import/OrganisationUserImportController` → `app/Imports/OrganisationUserImport.php` (Laravel Excel); participants/members export `routes/organisations.php:182,186`; sample data exists in-repo (`voter_list.csv`, `voter_list_20250810_0252.csv`) |
| **Business rules evidenced** | import is **organisation-scoped** (tenant isolation applies to bulk operations too); members carry a `region`, which later drives regional-post filtering (root `CLAUDE.md`) |
| **Verification** | import a 50-row CSV → confirm row count, rejected rows reported, no cross-tenant leakage; re-import the same file → confirm duplicate handling (`Evidence not found`: is it idempotent?) |
| **Known findings** | `Evidence not found`: import validation rules and duplicate policy |
| **Status** | `IMPLEMENTED — NOT VERIFIED` |

## PBDIGIT-06 — Members apply and are approved

| | |
|---|---|
| **Customer goal** | *"People should be able to apply to join, and we decide who is admitted."* |
| **Business steps** | applicant submits an application → officer reviews → approve or reject → applicant notified |
| **Route → code** | `routes/organisations.php:85` apply form · `:86` `POST /apply` → `MembershipApplicationController` · `Membership/PublicMembershipApplicationController` → `Contexts/Membership/Domain/Application/` → events `ApplicationSubmitted` · `ApplicationApproved` · `ApplicationRejected`; member aggregate events `MemberRegistered` · `MemberApproved` · `MemberRejected` · `MemberActivated` · `MemberSuspended` · `MemberArchived` (`Contexts/Membership/Domain/Member/Events/`); invitations `routes/organisations.php:63` → `OrganisationMemberInvitationController` |
| **Business rules evidenced** | a full **member lifecycle** exists (registered → approved → activated → suspended → archived), each transition with its own event; membership **types** are configurable (`MembershipTypeController`, `PBDIGIT-02`); fees exist as a separate concern (`Fee` aggregate: `FeePaid` · `FeeOverdue` · `FeeWaived`) |
| **Verification** | apply → approve → confirm member becomes active and appears in the electorate pool; apply → reject → confirm exclusion; confirm notification/email sent |
| **Known findings** | `Evidence not found`: whether unpaid fees block election eligibility (a `FullMembershipPolicy` exists per recent commits — relationship to voting eligibility not traced) |
| **Status** | `IMPLEMENTED — NOT VERIFIED` |

## PBDIGIT-07 — Assign members to committees and roles

| | |
|---|---|
| **Customer goal** | *"I want to record who holds which position in which committee."* |
| **Business steps** | select committee → assign member → assign role (chief, deputy, …) → remove/replace when terms end |
| **Route → code** | `routes/committee/committeeRoutes.php:58` `POST /committees/{committee}/members` · `:61` `DELETE /committees/{committee}/members/{…}` → `Committee/CommitteeMemberController` · `Api/MemberCommitteesController` · `Membership/CommitteeMembershipApplicationController` → events `CommitteeMemberAssigned` · `CommitteeMemberRemoved` · `CommitteeMemberRoleUpdated` · `MemberAssignedToCommittee` · `MemberRemovedFromCommittee`; roles via Spatie Permission |
| **Business rules evidenced** | **this story is the precondition for the entire election lifecycle** — the constitution states *"Only committees (chief, deputy) can administer elections"* and *"Only chief can open voting or publish results"* (`ElectionConstitution`). Without a correctly staffed committee, no election can be opened |
| **Verification** | assign a chief and a deputy → confirm the chief can reach `open-voting`/`publish` and the deputy cannot (this is the single most consequential authorization check in the product) |
| **Known findings** | `L-7` Membership test failures (above) touch committee handlers specifically (`AssignMemberToCommitteeHandler` constructor drift) — pre-existing, not a product defect, but it removes test assurance from exactly this story |
| **Status** | `IMPLEMENTED — NOT VERIFIED` · **highest-consequence story in this epic** |

## PBDIGIT-08 — Determine who is eligible to vote

| | |
|---|---|
| **Customer goal** | *"Only our eligible members should be able to vote — and I want to be able to explain why each person was or wasn't."* |
| **Business steps** | derive the electorate from membership + rules → snapshot it for the election |
| **Route → code** | `app/Services/VoterEligibilityService.php` · `app/Domain/Voting/Service/EligibilityEvaluator.php` · `app/Domain/Voting/ValueObject/EligibilitySnapshot.php` · `app/Contexts/Elections/Infrastructure/Policies/EloquentVoterEligibilityQueryService.php` · middleware `app/Http/Middleware/VoteEligibility.php` (route middleware `vote.eligibility`) · `app/Domain/Election/Security/Simplified/ParticipationEligibilityEvidence.php` |
| **Business rules evidenced** | eligibility is **evaluated and snapshotted** (a value object, not a live query at vote time) and produces **evidence** (`ParticipationEligibilityEvidence`) — i.e. the product can already explain a decision, which is exactly what a disputed election requires |
| **Verification** | make one member ineligible → confirm refusal at the code step **and** that the reason is recorded; confirm the snapshot does not change mid-election |
| **Known findings** | eligibility logic exists in **four** homes (service · domain evaluator · query service · middleware). `Evidence not found`: which is authoritative — a real risk for a rule this consequential, recorded here as a **product** concern (a wrong eligibility answer is customer-visible), not merely architecture debt |
| **Status** | `IMPLEMENTED — NOT VERIFIED` |

---

## Epic summary

| ID | Story | Status | Risk |
|---|---|---|---|
| `PBDIGIT-04` | Create committees | `IMPLEMENTED — NOT VERIFIED` | low — most mature non-certified area |
| `PBDIGIT-05` | Import members | `IMPLEMENTED — NOT VERIFIED` | low |
| `PBDIGIT-06` | Apply / approve members | `IMPLEMENTED — NOT VERIFIED` | low |
| `PBDIGIT-07` | Committee & role assignment | `IMPLEMENTED — NOT VERIFIED` | **HIGH — gates all election authority** |
| `PBDIGIT-08` | Voting eligibility | `IMPLEMENTED — NOT VERIFIED` | **HIGH — four homes, authority unclear** |

**Recommended order within the epic:** `PBDIGIT-07` and `PBDIGIT-08` first — both gate the election epics, and both are the stories where a defect is customer-visible rather than developer-visible.

**Nothing is authorized by this epic.**

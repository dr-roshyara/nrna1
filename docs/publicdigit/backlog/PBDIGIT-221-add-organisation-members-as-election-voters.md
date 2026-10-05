# PBDIGIT-221 — Add organisation members as voters of an election

**Type:** Story (product capability) · **Epic:** `PBDIGIT-EPIC-05` Voting · **Created:** 2026-10-04
**Raised by:** PO, 2026-10-04: *"we can write a separate story for adding organisation members to election voters"* — the replacement for the "Assign users as voters" sidebar section that `PBDIGIT-220` removes.
**Recorded by:** `claude-code-session:9e4925bb-4151-4185-9e9c-5831184e54eb` (self-declared, not attestable)
**Placement derived:** `doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0)

| | |
|---|---|
| **Status** | 🟡 **OPEN — not authorised.** Needs the PO decisions below before a plan (EP-01). |
| **Business need** | An election officer can register existing organisation members as voters of **one** election, as a deliberate, findable action — not as a side panel of the voter table. |
| **Domain language** | Registration ≠ Voter Admission ≠ Voting Entitlement (ADR_20261004_1549). This story owns **registration only** (an `ElectionMembership` with role `voter`, status `active`). It must **not** admit anyone: with the Voter Admission Policy ON, a newly added voter stays *NotAdmitted* until identity verification (PBDIGIT-213). |
| **What exists today (backend, reusable)** | `ElectionVoterController@store` (single, by user id) and `@bulkStore` (many), both `authorize('manageVoters')`; eligibility via `VoterEligibilityService::isEligibleVoter` / `unassignedEligibleQuery` with `VoterSourceStrategy::fromElection`; write via `AssignVoterHandler` / `AssignVoterCommand` (single) and `BulkAssignVotersHandler` / `BulkAssignVotersCommand` (bulk); routes `elections.voters.store`, `elections.voters.bulk`. The candidate list is the page prop `unassignedMembers`. |
| **What goes away** | The sidebar UI for this (member search, bulk assign, assign by user ID) — removed by `PBDIGIT-220`. Order: `PBDIGIT-220` and this story can ship together or this one first; the capability must not be absent longer than the PO accepts. |
| **Invariants (must hold)** | Only members eligible for **this** election's voter source can be added · tenant isolation (`organisation_id`) · lifecycle: only while the election allows voter changes (`canManageVoters`) · adding is **not** admitting (pinned today by `IdentityVerificationAdmissionTest::test_assigning_a_voter_by_user_id_does_not_admit_them`) · every addition is audited. |
| **Related** | `PBDIGIT-220` (removes the sidebar) · `PBDIGIT-202` (Full Membership voter import calls a non-existent `bulkAssignVoters()`) · `PBDIGIT-203` (voter import integrity: swallowed row errors, non-atomic writes, no assignment audit) · `PBDIGIT-213` (admission) · product backlog "Story 3.4 Voter Assignment" (placeholder only). |
| **Existing-story search** | `grep` of `docs/publicdigit/backlog/` for assign-as-voter / members-as-voters / voter assignment / `bulkStore` / `unassignedMembers`: PBDIGIT-202/203 are import **defects**, PBDIGIT-35 is legacy user flags, Story 3.4 is an empty placeholder. No story owns the member-to-voter assignment flow. |

## PO decisions needed (before EP-01 plan)

1. **Where:** a dedicated "Add voters" page, or a dialog launched from the Voter Management toolbar?
2. **Who:** the `manageVoters` ability as today, or only the Chief / Deputy Chief of the election (as for identity verification)?
3. **Which organisations:** Election-Only and Full Membership both, or one first (Full Membership import is currently broken — `PBDIGIT-202`)?
4. **Audit:** is an audit entry per added voter required in this story (overlaps `PBDIGIT-203`)?

## Acceptance (TDD — RED first; refined after the decisions)

1. An officer with the right authority sees eligible, not-yet-registered members of the organisation and can add one or many as voters of this election.
2. Ineligible users, users of another organisation and already-registered voters cannot be added (server-side; the UI is not the authority).
3. Outside the voter-management window the action is refused.
4. With the Voter Admission Policy ON, an added voter is registered but **not entitled** until identity verification.
5. Each addition is audited (if decision 4 = yes).

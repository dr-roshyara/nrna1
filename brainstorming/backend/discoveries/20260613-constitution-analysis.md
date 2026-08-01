# Discovery: ElectionConstitution Analysis

**Date:** 2026-06-13  
**Phase:** Phase 2 — Backend Domain Discovery (Round 4B)  
**Status:** Complete — evidence collected

## File Analyzed

`app/Domain/Election/Constitution/ElectionConstitution.php` — 237 lines, 1 class, 1 constant array (`RULES`)

## 1. Constitutional Action Inventory

| # | Action | Group | Role | Preconditions | State Change | Frontend Matches? |
|---|--------|-------|------|--------------|--------------|-------------------|
| 1 | `submit_for_approval` | Approval | chief, deputy | timezone_set | draft → submitted | ✅ `ElectionActions.SUBMIT_FOR_APPROVAL` |
| 2 | `approve` | Approval | platform_admin | capacity_eligibility | submitted → approved | ✅ |
| 3 | `reject` | Approval | platform_admin | — | submitted → rejected | ✅ |
| 4 | `auto_submit` | Approval | system | capacity_eligibility | draft → approved | ✅ |
| 5 | `begin_setup` | Setup | chief, deputy | — | approved → setup_administration | ✅ `BEGIN_SETUP` |
| 6 | `revise_and_resubmit` | Approval | chief, deputy | — | rejected → submitted | ✅ |
| 7 | `complete_administration` | Setup | chief, deputy | has_posts, has_voters, has_chief | setup_admin → setup_nomination | ✅ |
| 8 | `complete_nomination` | Setup | chief, deputy | has_approved_candidates | stays setup_nomination | ✅ |
| 9 | `apply_candidacy` | Nomination | voter, member | — | no state change (cap check only) | ⚠️ NOT in backend `ElectionAction` enum |
| 10 | `open_voting` | Voting | chief | voting_window_defined, timezone_set | setup_nomination/ready → voting_active | ✅ |
| 11 | `close_voting` | Voting | chief, deputy | — | voting_active → counting | ✅ |
| 12 | `publish_results` | Results | chief | — | counting → results_published | ✅ |
| 13 | `archive` | Results | chief, deputy | — | results_published → archived | ✅ |
| 14 | `suspend` | Overlay | chief, platform_admin | — | any → suspended | ✅ |
| 15 | `resume` | Overlay | chief, platform_admin | — | suspended → suspended (re-derived) | ✅ |

## 2. Language Gap: Backend Enum vs. Constitution

The backend `ElectionAction` enum (app/Domain/Election/Enum/ElectionAction.php) has **only 10 cases**:

```
SubmitForApproval, AutoSubmit, Approve, Reject,
CompleteAdministration, OpenVoting, CloseVoting,
PublishResults, Suspend, Resume
```

**Missing from enum (but present in Constitution):**
- `begin_setup`
- `revise_and_resubmit`
- `complete_nomination`
- `apply_candidacy`
- `archive`

**The frontend `ElectionActions` constant has all 14.** This means the frontend's language is currently more complete than the backend's typed enum.

## 3. Action Group Analysis

| Group | Actions | Shared Role | Shared Invariants | Event |
|-------|---------|-------------|-------------------|-------|
| **Approval** | submit_for_approval, approve, reject, auto_submit, revise_and_resubmit | mixed (committee + admin) | capacity_eligibility, timezone_set | `ElectionSubmittedForApproval`, `ElectionApproved`, `ElectionRejected` |
| **Setup** | begin_setup, complete_administration, complete_nomination | chief, deputy | has_posts, has_voters, has_chief, has_approved_candidates | `AdministrationCompleted`, `NominationCompleted` |
| **Nomination** | apply_candidacy | voter, member | — | None in Constitution (handled elsewhere) |
| **Voting** | open_voting, close_voting | chief (+ deputy) | voting_window_defined, timezone_set | `VotingOpened`, `VotingClosed` |
| **Results** | publish_results, archive | chief (+ deputy) | — | `ResultsPublished` |
| **Overlay** | suspend, resume | chief, platform_admin | — | None directly |

These 6 groups are **candidate bounded context boundaries**.

## 4. Invariant Centralization

| Invariant | Where Defined | Where Enforced |
|-----------|--------------|----------------|
| Action → allowed states | `Constitution::RULES` | `Guard::assertAllowed()` check 2 |
| Action → allowed roles | `Constitution::RULES` | `Guard::assertAllowed()` check 3 |
| Preconditions | `Constitution::RULES` | `Guard::validatePreconditions()` |
| has_posts | Precondition string | `Guard::isPreconditionMet()` |
| has_voters | Precondition string | `Guard::isPreconditionMet()` |
| has_chief | Precondition string | `Guard::isPreconditionMet()` |
| has_approved_candidates | Precondition string | `Guard::isPreconditionMet()` |
| voting_window_defined | Precondition string | `Guard::isPreconditionMet()` |
| timezone_set | Precondition string | `Guard::isPreconditionMet()` |
| capacity_eligibility | Precondition string | `Guard::isCapacityEligible()` |

**Finding:** Invariants are centralized in the Constitution (names) and the Guard (evaluation). No invariant checks are scattered in controllers or Vue components.

## 5. Action → Event Mapping

| Action | Event Dispatched |
|--------|-----------------|
| `open_voting` | `VotingOpened` |
| `close_voting` | `VotingClosed` |
| `approve` | `ElectionApproved` |
| `submit_for_approval` | `ElectionSubmittedForApproval` |
| `reject` | `ElectionRejected` |
| `complete_administration` | `AdministrationCompleted` |
| `complete_nomination` | `NominationCompleted` |
| Other | `ElectionStateChangedEvent` (generic) |

**Finding:** Most actions trigger explicit domain events. Some actions (suspend, resume, archive, begin_setup) use a generic fallback event — candidate for improvement.

## 6. Structural Language Gaps

| Artifact | Action Count | Source of Truth? |
|----------|-------------|------------------|
| `ElectionConstitution::RULES` | **14** (or 15 counting resume) | ✅ Yes — defined as complete |
| `ElectionAction` enum (PHP) | 10 | ❌ Incomplete — missing 4-5 actions |
| `ElectionActions` constant (frontend TS) | 14 | ✅ Matches Constitution |
| `StateMachineContract` (frontend TS) | 15 | ✅ Most complete |

**The `ElectionAction` PHP enum is lagging behind.** It should be regenerated from `ElectionConstitution::RULES` or maintained as a mirror.

## 7. Architectural Conclusion

| Question | Answer |
|----------|--------|
| Is Constitution the SSOT? | ✅ Yes — for lifecycle governance rules |
| Are constitutional actions the ubiquitous language? | ✅ Strong candidate — 14 verbs shared by backend + frontend |
| Are bounded contexts visible? | ✅ 6 candidate groups (Approval, Setup, Nomination, Voting, Results, Overlay) |
| Is the backend enum complete? | ❌ No — `ElectionAction` PHP enum is missing begin_setup, revise_and_resubmit, complete_nomination, apply_candidacy, archive |
| Is the frontend language aligned? | ✅ Yes — `ElectionActions` matches Constitution exactly |

## 8. Recommended Next Discovery

Investigate `app/Domain/Voting/` — the most likely separate bounded context with its own aggregate, invariants, and language.

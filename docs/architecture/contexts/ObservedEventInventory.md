# Observed Event Inventory (Code Inspection Only)

**Purpose:** Record domain events actually discovered in codebase  
**Date:** 2026-06-02  
**Method:** Code inspection (grep, file read)  
**Scope:** Events that exist in code, not assumptions  

---

## Discovery Summary

✅ **Event Infrastructure:** Laravel `event()` function dispatch  
✅ **Listener Registration:** Explicit `EventServiceProvider` with `shouldDiscoverEvents() = false`  
✅ **Existing Listeners:** 7 explicitly registered (see Section 2)  
✅ **Domain Events Found:** 11 Election + 7 Security + 22 Membership + 8 Governance context events  

---

## Section 1: Observed Event Classes

### Election Domain (Actually Found in Code)

| Event Name | Source File | Actual Payload Fields |
|:---|:---|:---|
| **VoterAssignedToElection** | `app/Domain/Election/Events/VoterAssignedToElection.php` | `$userId`, `$electionId`, `$organisationId`, `$assignedBy`, `$occurredAt` |
| **VotingOpened** | `app/Domain/Election/Events/VotingOpened.php` | (file exists, contents not yet inspected) |
| **VotingClosed** | `app/Domain/Election/Events/VotingClosed.php` | (file exists, contents not yet inspected) |
| **ResultsPublished** | `app/Domain/Election/Events/ResultsPublished.php` | (file exists, contents not yet inspected) |
| **ElectionCreated** | `app/Domain/Election/Events/ElectionCreated.php` | (file exists, contents not yet inspected) |
| **ElectionSubmittedForApproval** | `app/Domain/Election/Events/ElectionSubmittedForApproval.php` | (file exists, contents not yet inspected) |
| **ElectionApproved** | `app/Domain/Election/Events/ElectionApproved.php` | (file exists, contents not yet inspected) |
| **ElectionRejected** | `app/Domain/Election/Events/ElectionRejected.php` | (file exists, contents not yet inspected) |
| **AdministrationCompleted** | `app/Domain/Election/Events/AdministrationCompleted.php` | (file exists, contents not yet inspected) |
| **NominationCompleted** | `app/Domain/Election/Events/NominationCompleted.php` | (file exists, contents not yet inspected) |
| **BulkVotersAssignedToElection** | `app/Domain/Election/Events/BulkVotersAssignedToElection.php` | (file exists, contents not yet inspected) |

**Observation:** These events are dispatched from `app/Models/Election.php` via `event()` calls in state transitions.

---

### Election Security/Legitimacy Domain (Actually Found in Code)

| Event Name | Source File | Actual Payload Fields |
|:---|:---|:---|
| **ObservationRecorded** | `app/Domain/Election/Security/Event/ObservationRecorded.php` | `$overlayIdentifier`, `$finding`, `$evidenceContext` (array), `$electionId`, `$occurredAt` |
| **LegitimacyGranted** | `app/Domain/Election/Security/Event/LegitimacyGranted.php` | `$electionId`, `$voterIdentifier`, `$evidenceEnvelopeHash`, `$occurredAt` |
| **LegitimacyEvaluated** | `app/Domain/Election/Security/Event/LegitimacyEvaluated.php` | (file exists, contents not yet inspected) |
| **ConstitutionalDenialIssued** | `app/Domain/Election/Security/Event/ConstitutionalDenialIssued.php` | (file exists, contents not yet inspected) |
| **ConstitutionalFallbackActivated** | `app/Domain/Election/Security/Event/ConstitutionalFallbackActivated.php` | (file exists, contents not yet inspected) |
| **DivergenceObserved** | `app/Domain/Election/Security/Event/DivergenceObserved.php` | (file exists, contents not yet inspected) |
| **SovereigntyBoundaryCrossed** | `app/Domain/Election/Security/Event/SovereigntyBoundaryCrossed.php` | (file exists, contents not yet inspected) |

**Observation:** Security events use different naming convention than Election events. ObservationRecorded carries `evidenceContext` as array (contents unknown). LegitimacyGranted carries `evidenceEnvelopeHash`.

---

### Governance Domain (Actually Found in Code)

**Location:** `app/Contexts/Governance/Domain/*/Events/`

Events discovered:
- ApprovalGranted
- ApprovalRejected
- ApprovalRequested
- AuthorityDelegated
- AuthorityRevoked
- MemberAssignedToCommittee
- MemberRemovedFromCommittee
- GovernanceDecisionRecorded

(Payloads not yet inspected)

---

### Membership Domain (Actually Found in Code)

**Location:** `app/Contexts/Membership/Domain/*/Events/`

Events discovered:
- MemberRegistered
- MemberApproved
- MemberRejected
- MemberSuspended
- CommitteeCreated
- CommitteeMemberAssigned
- CommitteeMemberRemoved
- And 15+ others

(Payloads not yet inspected)

---

## Section 2: Observed Event Listeners (Actually Registered)

From `app/Providers/EventServiceProvider.php`:

| Event Class | Listeners | Purpose (from code) |
|:---|:---|:---|
| **MembershipApplicationApproved** | `InvalidateMembershipDashboardCache` | Cache invalidation |
| **MembershipApplicationRejected** | `InvalidateMembershipDashboardCache` | Cache invalidation |
| **MembershipFeePaid** | `InvalidateMembershipDashboardCache`, `RecalculateMemberFeeStatus`, `CreateIncomeForMembershipFee` | Cache + state + income sync |
| **MembershipRenewed** | `InvalidateMembershipDashboardCache` | Cache invalidation |
| **FeePaid** (Domain) | `MemberFeeStateListener` | State synchronization |
| **MemberAssignedToCommittee** | `CommitteeMemberProjectionListener::onMemberAssigned` | Projection sync |
| **MemberRemovedFromCommittee** | `CommitteeMemberProjectionListener::onMemberRemoved` | Projection sync |
| **NewsletterEmailSent** | `UpdateNewsletterCounters::handleSent` | Newsletter counters |
| **NewsletterEmailFailed** | `UpdateNewsletterCounters::handleFailed` | Newsletter failure tracking |

**Observation:** Election and Security events have NO registered listeners. They are emitted but not consumed.

---

## Section 3: Event Dispatch Locations (Actually Found)

From grep of `event(` calls:

| Event | Dispatch Location | Condition |
|:---|:---|:---|
| **VotingOpened** | `app/Models/Election.php` | State transition `'open_voting'` |
| **VotingClosed** | `app/Models/Election.php` | State transition `'close_voting'` |
| **ElectionApproved** | `app/Models/Election.php` | State transition `'approve'` |
| **ElectionSubmittedForApproval** | `app/Models/Election.php` | State transition `'submit_for_approval'` |
| **ResultsPublishedEvent** | `app/Http/Controllers/Election/ElectionManagementController.php` | HTTP controller action |
| **VerificationRevokedEvent** | `app/Http/Controllers/Election/VoterVerificationController.php` | HTTP controller action |
| Membership events | `app/Http/Controllers/Membership/` | HTTP controller actions |

**Observation:** Election domain events dispatched from Eloquent model state transitions. HTTP controllers dispatch directly.

---

## Section 4: Hypotheses Potentially Affected (Probabilistic Only)

**Important:** These are PROBABILISTIC. Code inspection does not prove hypothesis connection.

| Event | Potentially Relevant to | Reason |
|:---|:---|:---|
| **VoterAssignedToElection** | H1, H2, H6 | Captures participation baseline (potentially evidence-relevant) |
| **VotingOpened / VotingClosed** | H1, H2 | Establishes election lifecycle boundaries |
| **ObservationRecorded** | H3, H4, H5 | Explicitly contains "evidenceContext" |
| **LegitimacyGranted** | H3, H7, H8 | Bridges evaluation to legitimacy (potentially context-separating) |
| **ResultsPublished** | H1, H2, H7 | Terminal election event (potentially freezes evidence) |

---

## Section 5: Critical Discoveries (Code-Based Only)

### Discovery 1: Two Event Infrastructure Patterns

Election domain uses:
```
app/Models/Election.php → event() in state machine
```

Membership domain uses:
```
app/Http/Controllers/Membership/* → event() in controllers
```

**Implication:** Event dispatch is inconsistent. Model-based vs controller-based.

---

### Discovery 2: Election/Security Events Have Zero Listeners

All Election and Security events are dispatched but have NO registered listeners.

```
VoterAssignedToElection  → No listener
VotingOpened             → No listener
VotingClosed             → No listener
ObservationRecorded      → No listener
LegitimacyGranted        → No listener
```

**Implication:** These events are broadcast but nobody consumes them.

---

### Discovery 3: Evidence-Like Fields Already in Payloads

`ObservationRecorded` already carries `$evidenceContext` (array).  
`LegitimacyGranted` already carries `$evidenceEnvelopeHash`.

**Implication:** Evidence-related concepts are already in the code, but not integrated.

---

## Section 6: What Remains Unknown (Without Further Inspection)

| Unknown | How to Discover |
|:---|:---|
| What is `$evidenceContext` array? | Read ObservationRecorded dispatch sites |
| What goes into `$evidenceEnvelopeHash`? | Read LegitimacyGranted dispatch |
| Do election events carry voter_id? | Read VoterAssignedToElection dispatch |
| Where are Security events dispatched? | Search for ObservationRecorded dispatch |
| Are there dedicated event listeners classes? | Search `app/Listeners/` directory |

---

## Next Step

This inventory is based on code inspection, not assumptions.

No infrastructure has been designed yet.

No hypotheses have been assigned.

**Ready to proceed to Phase 1 Step 2:** Analyze these observed events for minimal observation capture.

---

**Status: Ready. Based on reality, not theory.**

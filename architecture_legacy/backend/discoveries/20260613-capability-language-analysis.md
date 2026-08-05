# Discovery: Capability Language Analysis

**Date:** 2026-06-13  
**Phase:** Phase 2 — Backend Domain Discovery (Round 4A)  
**Status:** Complete — evidence collected

## 1. The Ubiquitous Language

The 14 constitutional actions defined in `ElectionConstitution::RULES` and resolved by `ElectionCapabilityResolver` form the **ubiquitous language of election governance**:

### Approval Workflow (3 actions)
| Action | Allowed States | Allowed Roles | Precondition |
|--------|---------------|---------------|--------------|
| `submit_for_approval` | `draft` | chief, deputy | timezone_set |
| `approve` | `submitted_for_approval` | platform_admin | capacity_eligibility |
| `reject` | `submitted_for_approval` | platform_admin | — |
| `auto_submit` | `draft` | system | capacity_eligibility |
| `revise_and_resubmit` | `rejected` | chief, deputy | — |

### Setup Workflow (2 actions)
| Action | Allowed States | Allowed Roles | Precondition |
|--------|---------------|---------------|--------------|
| `begin_setup` | `approved` | chief, deputy | — |
| `complete_administration` | `setup_administration` | chief, deputy | has_posts, has_voters, has_chief |

### Nomination Workflow (1 action)
| Action | Allowed States | Allowed Roles | Precondition |
|--------|---------------|---------------|--------------|
| `apply_candidacy` | `setup_nomination` | voter, member | — |
| `complete_nomination` | `setup_nomination` | chief, deputy | has_approved_candidates |

### Voting Workflow (2 actions)
| Action | Allowed States | Allowed Roles | Precondition |
|--------|---------------|---------------|--------------|
| `open_voting` | `setup_nomination`, `ready_for_voting` | chief | voting_window_defined, timezone_set |
| `close_voting` | `voting_active` | chief, deputy | — |

### Results Workflow (2 actions)
| Action | Allowed States | Allowed Roles | Precondition |
|--------|---------------|---------------|--------------|
| `publish_results` | `counting` | chief | — |
| `archive` | `results_published` | chief, deputy | — |

### Governance Overlay (2 actions)
| Action | Allowed States | Allowed Roles | Precondition |
|--------|---------------|---------------|--------------|
| `suspend` | All except `suspended`, `archived` | chief, platform_admin | — |
| `resume` | `suspended` | chief, platform_admin | — |

## 2. Capability Resolution Chain

```
Controller::getStateMachineData()
    ↓
ElectionLifecycleEngineImpl::compute()
    → Derives canonical lifecycle state
    → Derives 5 permission booleans (canEdit, canVote, etc.)
    ↓
ElectionLifecycleSnapshot              ← Immutable read model
    ↓
Controller::resolveCapabilities()
    → Iterates ALL 14 actions from Constitution
    → For each action: creates CapabilityContext
    → Feeds through ElectionCapabilityResolver
    ↓
ElectionCapabilityResolver::evaluate()
    → Policy chain (priority order):
      1. LifecycleCapabilityBaselinePolicy  — is action allowed in current state?
      2. OverlayCapabilityPolicy            — is election suspended?
      3. TrustCapabilityPolicy              — (future) trust evaluation
      4. EvidenceCapabilityPolicy           — (future) evidence check
    → First denial is final (short-circuit)
    ↓
CapabilityDecision                        ← allowed + reason + detail
    ↓
StateMachineContract                      ← Frontend receives:
    → currentState
    → capabilities[action].allowed
    → capabilities[action].denial_reason
    → capabilities[action].denial_detail
    → capabilities_metadata.resolver_version
    → capabilities_metadata.constitution_hash
```

## 3. Capability Language vs. Lifecycle States

| Lifecycle State | Available Actions | Domain Meaning |
|----------------|-------------------|----------------|
| `draft` | `submit_for_approval` | Election is being created |
| `submitted_for_approval` | (none — awaiting admin) | Under platform review |
| `approved` | `begin_setup` | Approved, ready for setup |
| `rejected` | `revise_and_resubmit` | Rejected, can resubmit |
| `setup_administration` | `complete_administration` | Configuring posts/voters |
| `setup_nomination` | `complete_nomination`, `open_voting` | Candidate nomination |
| `ready_for_voting` | `open_voting` | Setup done, waiting to open |
| `voting_active` | `close_voting` | Voting in progress |
| `counting` | `publish_results` | Voting closed, tallying |
| `results_published` | `archive` | Results published |
| `archived` | (none) | Final history state |
| `suspended` | `resume` | Governance hold |

**Key finding:** The capability language (14 actions) is richer than the lifecycle language (12 states). Capabilities are what the frontend actually consumes. The lifecycle states are intermediate derivation artifacts.

## 4. Evidence of Ubiquitous Language Alignment

| Backend Concept | Frontend Constant | Match? |
|----------------|-------------------|--------|
| `ElectionConstitution::RULES` 14 actions | `ElectionActions` (14 constants) | ✅ Exact match |
| `ElectionLifecycleState` enum (12 states) | `ElectionLifecycleStates` (11 constants + 1 `suspended`) | ✅ Nearly exact |
| `CapabilityDecision::denialReason` | `CapabilityDenialReason` enum | ✅ Mapped in `useElectionCapabilities` |
| Policy chain layers | `StateMachineContract.ts` types | ✅ `CapabilitiesMap` matches all 14 actions |

## 5. Architectural Conclusion

| Question | Answer |
|----------|--------|
| What is the real ubiquitous language? | **14 constitutional actions** — they are what the frontend consumes and what the backend enforces |
| Are lifecycle states domain concepts? | Derivatives — useful for navigation but capabilities are the authoritative language |
| Is the frontend using capabilities correctly? | **Yes** — `useElectionCapabilities(capabilities).canDo(action)` pattern matches the backend's constitutional model |
| Is there language drift between backend and frontend? | **No** — the `ElectionActions` constants are an exact mirror of `ElectionConstitution::RULES` keys |
| Would bounded contexts split along these lines? | Likely: **Election Governance** (approval + setup + nomination + overlay) and **Voting** (voting + results) appear as distinct workflow groups |

## 6. Recommended Next Investigation

The 14 constitutional actions are a strong candidate for the ubiquitous language of election governance. The next question is whether **Voting** (actions: open_voting, close_voting, publish_results) and **Election Governance** (the remaining 10 actions) are candidate bounded contexts requiring further discovery — specifically, independent invariants, transaction boundaries, lifecycle ownership, and ubiquitous language must be established before concluding they are separate. Investigate `app/Domain/Voting/` for aggregate candidates.

---
name: phase-4-0-capability-audit
description: Phase 4.0 Capability Audit - Complete dispersion mapping for governance consolidation
metadata: 
  node_type: memory
  type: project
  updated: 2026-05-20
  status: audit-complete
  originSessionId: c58c462c-c198-465e-89b3-69a6bfe193fb
---

# Phase 4.0: Election Capability Audit Results

## Executive Summary

The election system has ~**1,000+ authorization decision points** scattered across the codebase. While the state machine is unified (ElectionLifecycle is SSOT), **capability logic remains highly dispersed**, creating drift risk and maintenance burden.

---

## Audit Findings

### 1. Legacy Field Usage (CRITICAL)

| Field | Count | Risk | Notes |
|-------|-------|------|-------|
| `is_active` | **620** | CRITICAL | Boolean voting state - scattered across controllers, views, services |
| `->status` | **103** | HIGH | Old election lifecycle field - still checked in 15+ locations |
| `->state` | **96** | HIGH | New field but still read directly in 10+ locations outside SSOT |
| `voting_locked` | ~50 | MEDIUM | Controls whether voting can be adjusted |
| `administration_completed` | ~30 | MEDIUM | Controls phase transitions |

**Total Legacy Field Reads: ~900 direct accesses**

### 2. Authorization Decision Points (DISPERSED)

| Location | Type | Count | Examples |
|----------|------|-------|----------|
| **Policies** | Role-based | 116 | ElectionPolicy::manageSettings, ElectionOfficerPolicy::update |
| **Controllers** | Direct checks | 32 | abort(403), auth()->user()->can() |
| **Middleware** | State-based | 7 | election.state:manage_posts, election.state:configure |
| **Lifecycle Snapshot** | Capability | 5 | canEdit, canVote, canManageVoters |
| **allowsAction() Bridge** | Compatibility | 39 | Called by middleware + old code |
| **Business Logic** | Conditional | ~20 | Scattered in handlers, commands, services |

**Total Authorization Points: ~200+ explicit checks**

### 3. Capability Topology - Current State (FRAGMENTED)

```
┌─ is_active (620 reads)
│  ├─ Controllers (voting decision)
│  ├─ Services (election active check)
│  ├─ Middleware (eligibility)
│  └─ Views (UI visibility)
│
├─ status (103 reads)
│  ├─ Controllers (planned → active)
│  ├─ Policies (can manage)
│  └─ Commands (state tracking)
│
├─ state (96 reads)
│  ├─ ElectionLifecycle (sovereign)
│  ├─ Controllers (legacy fallback)
│  └─ Policies (deprecated)
│
└─ Role checks (116 locations)
   ├─ ElectionPolicy (chief, deputy, commissioner)
   ├─ ElectionOfficerPolicy (status checks)
   ├─ Controllers (direct auth()->user()->can())
   └─ Middleware (policy-based gating)
```

---

## Capability Dispersion Map

### Current Flow (Before Consolidation)

```
Request
  ├→ Middleware (election.state:action)
  │   └→ allowsAction() [bridge]
  │       └→ ElectionLifecycle [SSOT]
  │
  ├→ Controller
  │   ├→ Policy (ElectionPolicy)
  │   │   └→ Role checks + state reads
  │   │
  │   ├→ Direct $election->is_active check
  │   │
  │   └→ Business logic capability check
  │       ├→ Timeline edit check (canEditTimeline)
  │       ├→ Voter import check (canManageVoters)
  │       └→ Voting open check (canOpenVoting)
  │
  ├→ Handler
  │   ├→ Direct state field check
  │   └→ Role-based eligibility
  │
  └→ Service
      └→ is_active boolean check
```

### Consolidation Target (After Phase 4)

```
Request
  ├→ Middleware (generic capability middleware)
  │   └→ ElectionCapabilities [UNIFIED]
  │       └→ ElectionLifecycleSnapshot
  │           └→ ElectionConstitution
  │
  ├→ Controller
  │   ├→ ElectionCapabilities
  │   │   └→ canEditTimeline()
  │   │   └→ canManageVoters()
  │   │   └→ canOpenVoting()
  │   │
  │   └→ Orchestration (no auth logic)
  │
  ├→ Handler
  │   └→ ElectionCapabilities [SAME SOURCE]
  │
  └→ Service
      └→ ElectionCapabilities [SAME SOURCE]
```

---

## Capability Concepts - Inventory

### Currently Scattered Semantics

| Business Capability | Current Checks | Locations | SSOT Source |
|-------------------|-----------------|-----------|------------|
| **canEditTimeline** | TimelineEdit check + state check | Controller (2) + Lifecycle (1) | ElectionLifecycle |
| **canManageVoters** | Policy check + is_active check | Policy (1) + Controller (3) | ElectionPolicy + Lifecycle |
| **canApproveCandidate** | status check + role check | Policy (1) + Handler (1) | ElectionPolicy + Constitution |
| **canOpenVoting** | state check + role check | Controller (2) + Constitution | ElectionLifecycle + Constitution |
| **canPublishResults** | state check + role check | Controller (1) + Policy (1) | ElectionLifecycle + Policy |
| **canActivateElection** | status check + role check | Controller (1) + Policy (1) | ElectionPolicy + Lifecycle |
| **canVote** | is_active check + membership check | Middleware (3) + Service (4) | ElectionLifecycle + Membership |
| **canApplyCandidacy** | state check + membership check | Middleware (1) + Handler (2) | ElectionLifecycle + Membership |

### Duplication Patterns Found

**Pattern 1: Role + State Fusion**
```php
// Found in multiple locations:
if (!auth()->user()->isChief() || $election->state !== 'setup') {
    abort(403);
}
// Should be unified to:
if (!$capabilities->canManageElection()) { ... }
```

**Pattern 2: is_active Boolean Scatter**
```php
// Found 620 times across codebase:
if ($election->is_active) { ... }
if (!$vslug->is_active) { ... }
if ($code->is_active) { ... }
// Each is a different capability check
```

**Pattern 3: State Name Repetition**
```php
// Found in controllers, policies, middleware:
case 'approved': ...
case 'setup': ...
case 'voting_active': ...
// Semantics hardcoded in multiple places
```

---

## Key Findings

### 1. Highest Impact Consolidation Targets

**Priority 1:** `is_active` field (620 reads)
- Needs clear semantic mapping to ElectionCapabilities
- Currently means different things in different contexts (voting active, membership active, code active)

**Priority 2:** Role-based checks (116 instances)
- Can be unified into ElectionCapabilities methods
- Currently scattered across policies and controllers

**Priority 3:** State-based authorization (199 reads)
- Can delegate to ElectionLifecycleSnapshot
- Once unified, all state checking becomes read-only

### 2. Semantic Gaps Identified

| Gap | Current State | Required |
|-----|---------------|----------|
| Voting window | `is_active` boolean | `votingStartsAt` + `votingEndsAt` derived state |
| Election lifecycle | Mix of `state`, `status`, `is_active` | Single ElectionLifecycleState enum |
| Membership active | Separate status on ElectionMembership | Derived from lifecycle + membership |
| Code active | `is_active` on Code model | Derived from usage window |

### 3. Middleware Logic Risk

Current:
- 7 routes use `election.state:action` middleware
- Middleware calls `allowsAction()` which reads state
- Each middleware rule has state semantics hardcoded

Risk:
- If capabilities change, middleware rules become silent failures
- New capabilities can't be easily added to middleware
- Tests bypass middleware, revealing hidden dependencies

---

## Phase 4.1 Blueprint: ElectionCapabilities Object

### Proposed Structure

```php
final readonly class ElectionCapabilities
{
    // Timeline management
    public function canEditTimeline(): bool;
    public function canEditDates(): bool;
    
    // Voter management
    public function canManageVoters(): bool;
    public function canImportVoters(): bool;
    public function canApproveVoters(): bool;
    
    // Candidate management
    public function canApplicantsApply(): bool;
    public function canApproveCandidacies(): bool;
    public function canViewCandidates(): bool;
    
    // Voting control
    public function canOpenVoting(): bool;
    public function canCloseVoting(): bool;
    public function canLockVoting(): bool;
    public function canVote(): bool;
    
    // Results
    public function canPublishResults(): bool;
    public function canUnpublishResults(): bool;
    public function canViewResults(): bool;
    
    // Admin
    public function canActivateElection(): bool;
    public function canManageCommittee(): bool;
}
```

### Sources of Truth per Capability

| Method | Derives From |
|--------|-------------|
| canOpenVoting | ElectionLifecycleSnapshot.state + Role |
| canVote | ElectionLifecycleSnapshot.canVote + UserMembership |
| canPublishResults | ElectionLifecycleSnapshot.state + UserRole:chief |
| canEditTimeline | ElectionLifecycleSnapshot.canEditTimeline + Role |

---

## Dependency Graph

### What Depends on What Currently

```
Controllers
    ↓↓↓
├─ ElectionPolicy (role-based)
├─ Direct is_active check
├─ Direct state read
└─ allowsAction() bridge
    ↓
ElectionLifecycle [SSOT]
    ↑
Middleware
    ↓
ElectionOfficer table (role check)

Services
    ↓
├─ Direct is_active check
├─ Direct state read
└─ ElectionLifecycle
```

---

## Next Steps

1. **Use this map** to design ElectionCapabilities in Phase 4.1
2. **Track adoption** of each new capability method
3. **Deprecate** old patterns as adoption completes
4. **Metrics** every use of old authorization patterns

---

## Audit Statistics

- **Total authorization decision points:** ~1,000+
- **Direct field reads:** ~900
- **Role/policy checks:** 116
- **State-specific middleware:** 7
- **Controllers using non-unified checks:** 10
- **Files needing migration:** ~30
- **Estimated consolidation effort:** 3-4 phases

---

**Status:** Phase 4.0 (Audit) COMPLETE  
**Next:** Phase 4.1 (ElectionCapabilities Design)

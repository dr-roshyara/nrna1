# Governance Context: What It Is & How It Relates to Other Contexts

## Executive Summary

The **Governance Context** is one of multiple bounded contexts in the NRNA platform. It manages **committee structures and membership assignments** while remaining independent from the broader organization and membership systems.

- **Governance Context** = What committees exist, who's assigned to them, and their hierarchical relationships
- **Membership Context** = Who people are, their member status, fees, eligibility
- **Committee Context** (Legacy) = Older system being replaced by Governance Context
- **Organization Context** = Multi-tenant container; all contexts operate within an org

---

## What is the Governance Context?

### Core Responsibility

The Governance Context **manages the organizational structure** — specifically:

✅ What it owns:
- Committee definitions (name, type, level)
- Geographic hierarchy (central, state, district, ward)
- Committee membership assignments
- Temporal lineage of member assignments

❌ What it DOESN'T own:
- User accounts (Organization Context)
- Member details (Membership Context)
- Member eligibility rules (Membership Context)
- Member fees (Membership Context)
- Elections (Elections Context)

### Mental Model

Think of Governance as an **organizational chart:**

```
Organization
├── Governance Hierarchy
│   ├── Central Committee
│   │   ├── Member A (since 2026-01-15)
│   │   └── Member B (since 2026-02-01)
│   ├── State Committees (multiple)
│   │   ├── Karnataka Committee
│   │   │   ├── Member C
│   │   │   └── Member D
│   │   └── Maharashtra Committee
│   │       └── Member E
│   └── District Committees (many)
│       └── ...
│
└── Membership System (separate)
    ├── Member A (profile, fees, status)
    ├── Member B (profile, fees, status)
    ├── Member C (profile, fees, status)
    └── ...
```

---

## Three Contexts & Their Interactions

### 1. Governance Context (NEW)

**What:** Committee structure and assignments  
**Phase:** 3 (Production Ready)  
**Repository:** `app/Contexts/Governance/`  

**Owns:**
- Committees (what they are, hierarchy)
- Committee memberships (who's assigned)
- Geographic scope (which region each committee serves)

**Example Domain Model:**
```php
Committee
├── id: CommitteeId
├── name: CommitteeName
├── type: CommitteeType (central, provincial, district)
├── geoUnitId: GeoUnitId (which region)
└── members: List<MemberId>  // Who's assigned
```

**Key Invariant:** A committee is an **organizational structure**, independent of member details.

---

### 2. Membership Context (EXISTING)

**What:** Member profiles, eligibility, fees, status  
**Phase:** Stable  
**Repository:** `app/Contexts/Membership/`  

**Owns:**
- Member profiles (name, contact info)
- Membership status (active, suspended, terminated)
- Membership fees (payment status, renewal)
- Voting eligibility
- Membership lineage (history of membership changes)

**Example Domain Model:**
```php
Member
├── id: MemberId
├── name: Name
├── email: Email
├── membershipStatus: MembershipStatus
├── residenceGeoUnit: GeoUnitId
└── fees: List<Fee>

Membership Lineage
├── id: LineageId
├── memberId: MemberId
├── episodes: List<Episode>
│   ├── status: active/suspended/terminated
│   └── transitionedAt: timestamp
```

**Key Invariant:** A member's **validity doesn't depend on committee assignment**. Members exist independently; committees just reference them.

---

### 3. Committee Context (LEGACY)

**What:** Older system being refactored  
**Phase:** Deprecating  
**Repository:** `app/Contexts/Membership/Application/Committee/` (being migrated)  

**Status:** Most functionality moved to Governance Context. Being phased out.

**What's Left:**
- `GetCommitteeDashboard` (aggregates data from Governance + Membership)
- Legacy committee assignment logic (being replaced by Phase 4)

---

## How Do They Interact?

### Scenario 1: Assigning a Member to a Committee

```
User Action: "Assign Member A to Finance Committee"
                     ↓
        ┌────────────┴─────────────┐
        ▼                          ▼
   Governance Context      Membership Context
   ─────────────────      ──────────────────
   
   1. Load Committee
      aggregate from
      repository
      
   2. Call addMember(
      memberId=A
   )
   
   3. Record event:
      MemberAssigned
      ToCommittee
      
   4. Dispatch event
      ↓
   
   Projection Updates:
   INSERT INTO 
   committee_member_projection
   
   But NEVER validates:
   "Is Member A eligible?"
   "Are their fees paid?"
   "Are they active?"
   
   That's Membership's job!
```

### Scenario 2: Removing a Member from a Committee

```
User Action: "Remove Member A from Finance Committee"
                     ↓
        ┌────────────┴─────────────┐
        ▼                          ▼
   Governance Context      Membership Context
   ─────────────────      ──────────────────
   
   1. Load Committee
      from repository
      
   2. Call removeMember(
      memberId=A
   )
   
   3. Record event:
      MemberRemoved
      FromCommittee
      
   4. Dispatch event
      ↓
   
   Projection Updates:
   DELETE FROM 
   committee_member_projection
   
   But DOESN'T affect:
   - Member's profile
   - Member's status
   - Member's fees
   - Member's voting rights
   
   Those remain unchanged!
```

### Scenario 3: Computing Voting Eligibility

This is where contexts collaborate:

```
Question: "Can Member A vote in this election?"
          (Requires BOTH Governance + Membership)
                     ↓
        ┌────────────┴──────────────────┐
        ▼                               ▼
   Governance Context          Membership Context
   ─────────────────          ──────────────────
   
   Query:                      Query:
   "Is Member A assigned       "Is Member A:
    to a committee that        - Active?"
    covers this election?"     - Fees paid?"
                               - Not suspended?"
   
   ↓                           ↓
   Returns: YES/NO             Returns: YES/NO
   
   ↓
   Application Policy Layer:
   "Can vote IF governance says YES AND membership says YES"
```

---

## Bounded Context Boundaries

### What Governance CAN Access

- ✅ Its own domain (Committees, assignments)
- ✅ MemberId (value object — just an ID, no details)
- ✅ GeoUnitId (geographic reference)
- ✅ Events from Membership (to react)

```php
// ✅ ALLOWED: Reference member by ID only
$committee->addMember(MemberId::fromString('...'));

// ✅ ALLOWED: React to membership events
Event::listen(MembershipStatusChanged::class, function ($event) {
    // Maybe disable committees if member becomes inactive?
});
```

### What Governance CANNOT Access

- ❌ Member::class (full aggregate)
- ❌ Member's profile details (name, email, etc.)
- ❌ Member's fee status
- ❌ Member's eligibility rules

```php
// ❌ WRONG: Trying to load Member aggregate
$member = $memberRepository->find($memberId);
if ($member->isActive()) {  // ← WRONG! Crosses boundary
    // ...
}

// ✅ RIGHT: Check through your own data
if ($this->committeeHasMember($memberId)) {
    // Member is assigned to committee
    // (But we don't know if they're eligible!)
}

// ✅ RIGHT: Query Membership Context (if available)
$isEligible = $membershipService->isEligibleForVoting($memberId);
```

---

## Data Ownership Rules

### Governance Owns

| Data | Source | Governance Can Modify? |
|------|--------|----------------------|
| Committee name | Domain aggregate | ✅ Yes |
| Committee type | Domain aggregate | ✅ Yes |
| Committee geographic scope | Domain aggregate | ✅ Yes |
| Committee members list | Domain events | ✅ Yes (via events) |
| Member assignment dates | Domain events | ✅ Yes (via events) |

### Membership Owns

| Data | Source | Governance Can Modify? |
|------|--------|----------------------|
| Member profile | Member aggregate | ❌ No |
| Membership status | Member aggregate | ❌ No |
| Fee status | Fee aggregate | ❌ No |
| Member eligibility | Policy engine | ❌ No |

### What About Shared Data?

| Data | Owner | Usage |
|------|-------|-------|
| MemberId | Membership | Governance references it (ID only) |
| GeoUnitId | Geography | Both use it (shared reference) |
| TenantId | Organization | Both use it (shared context) |

---

## Communication Patterns

### Pattern 1: Direct Query (One-Way)

Governance queries Membership to **fetch information** (no mutation):

```php
// ✅ ALLOWED: Read-only queries
$memberStatus = $membershipService->getStatus($memberId);
$isPaid = $membershipService->isFeesPaid($memberId);

// Then decide: "Should this committee member stay assigned?"
```

### Pattern 2: Event Reaction (Async)

Governance listens to Membership events and reacts:

```php
// In Governance Context
Event::listen(MembershipTerminated::class, function ($event) {
    // React: Maybe remove from committees?
    $this->removeFromAllCommittees($event->memberId);
});
```

### Pattern 3: Cross-Context Policy (Read Both)

Application layer reads from both contexts for decisions:

```php
// NOT in either context, but at application/policy level
class VotingEligibilityPolicy
{
    public function canVote(MemberId $memberId): bool
    {
        // Read from Governance
        $inCommittee = $governanceService->isInVotingCommittee($memberId);
        
        // Read from Membership
        $isEligible = $membershipService->isEligible($memberId);
        
        // Combine: Both must be true
        return $inCommittee && $isEligible;
    }
}
```

---

## Real-World Scenarios

### Scenario A: Annual Committee Restructuring

**Action:** "Remove Member A from Finance Committee, add Member B"

**Governance does:**
1. Load Finance Committee aggregate
2. Call removeMember(A), addMember(B)
3. Dispatch events

**Membership is unaffected:**
- Both members' profiles unchanged
- Both members' fees unchanged
- Both members' status unchanged

**Result:** Committee looks different, but member records are identical.

---

### Scenario B: Member Suspension

**Action:** "Member A is suspended for non-payment"

**Membership does:**
1. Load Member A aggregate
2. Mark as status=suspended
3. Dispatch MembershipSuspended event

**Governance reacts:**
```php
Event::listen(MembershipSuspended::class, function ($event) {
    // Option 1: Remove from committees
    $this->removeFromAllCommittees($event->memberId);
    
    // Option 2: Just log it
    Log::info("Member suspended: " . $event->memberId);
});
```

**Choice depends on business rules** — Governance is flexible about how to react.

---

### Scenario C: Committee Geographic Scope Change

**Action:** "Finance Committee now serves both Karnataka and Maharashtra"

**Governance does:**
1. Update committee's geoUnit reference
2. Dispatch event

**Membership is unaffected:**
- No members removed
- No member data changed
- Member eligibility rules unchanged

**Result:** Same committee members, larger geographic scope.

---

## Why This Separation?

### Problem Without Separation

If all features were in one context:

```php
// BAD: Everything tangled
class Member {
    public function assignToCommittee($committee) {
        // Check fee status
        // Check membership status
        // Check geographic eligibility
        // Check committee rules
        // Update committee
        // Update member
        // Update assignments
        // Trigger elections?
        // Send emails?
        // Update statistics?
        // ... 50 reasons to change
    }
}
```

**Issues:**
- ❌ Hard to test (all dependencies)
- ❌ Changes in one area break others
- ❌ Can't reuse independently
- ❌ Unclear ownership

### Benefits With Separation

```php
// GOOD: Clear boundaries
class Committee {
    public function addMember(MemberId $id) {
        // Does ONE thing: Record membership
        $this->members[] = $id;
    }
}

class Member {
    public function suspend() {
        // Does ONE thing: Change status
        $this->status = 'suspended';
    }
}

class VotingPolicy {
    public function canVote(MemberId $id) {
        // Combines: "Ask both contexts"
        return governance->hasVotingCommittee($id)
            && membership->isEligible($id);
    }
}
```

**Benefits:**
- ✅ Easy to test (each context is independent)
- ✅ Changes isolated (modify one, others unaffected)
- ✅ Reusable (Governance can be used without Membership)
- ✅ Clear ownership (each context owns its data)

---

## Architecture Diagram

```
┌────────────────────────────────────────────────────────────┐
│                    ORGANIZATION CONTEXT                     │
│  (Tenants, users, roles)                                   │
└───┬──────────────────────────────────────────────────────┬─┘
    │                                                       │
    ▼                                                       ▼
┌─────────────────────────┐                    ┌──────────────────────────┐
│   GOVERNANCE CONTEXT    │                    │   MEMBERSHIP CONTEXT      │
│  ─────────────────────  │                    │  ─────────────────────── │
│  • Committees           │  ◄──────Event─────► • Members               │
│  • Hierarchy            │    Reaction         • Fees                  │
│  • Assignments          │                     • Status                │
│  • Geographic scope     │   ◄────Query────────• Eligibility          │
│                         │   (read-only)       • Lineage              │
└────────┬────────────────┘                    └────────┬───────────────┘
         │                                              │
         │ ProjectionListener                         │
         │ (Event→DB)                                  │
         │                                              │
         ▼                                              ▼
    ┌─────────────────────┐                    ┌───────────────┐
    │ Projection Table    │                    │ Member Table  │
    │ (committee_members) │                    │ (members)     │
    └─────────────────────┘                    └───────────────┘

         ▼                                              ▼
    ┌─────────────────────────────────────────────────────────┐
    │        APPLICATION LAYER                                 │
    │  ─────────────────────────────────────────────────────  │
    │  • VotingEligibilityPolicy                              │
    │  • CommitteeManagementService                           │
    │  • ReportsService                                       │
    │  (Uses both Governance + Membership)                    │
    └─────────────────────────────────────────────────────────┘
         ▼
    ┌─────────────────────────────────────────────────────────┐
    │        API & PRESENTATION LAYER                          │
    │  ─────────────────────────────────────────────────────  │
    │  • CommitteeMemberController                            │
    │  • CommitteeMemberManager.vue                           │
    │  • Dashboards & Reports                                 │
    └─────────────────────────────────────────────────────────┘
```

---

## How to Think About It

### Old Mental Model (❌ Avoid)

"Everything about a member is in one place"

```
Member
├── Profile (name, email)
├── Membership fees
├── Eligibility status
├── Committee assignments          ← ❌ WRONG PLACE
├── Voting history
└── Role assignments
```

### New Mental Model (✅ Correct)

"Contexts are like people with specialties"

```
Membership Context = "Member Specialist"
├── "Here's who Member A is"
├── "Here's their fee status"
├── "Here's their eligibility"
└── "Are they active?"

Governance Context = "Organization Structure Specialist"
├── "Here are our committees"
├── "Here's who's assigned to each"
├── "Here's the hierarchy"
└── "This is the geographic scope"

Application Policy = "Consultant"
├── "I need to answer: Can Member A vote?"
├── Asks Membership: "Are they eligible?"
├── Asks Governance: "Are they in a voting committee?"
└── Combines answers
```

---

## Migration Path: Old Committee Context → Governance

### What Changed

| Aspect | Old (Committee Context) | New (Governance Context) |
|--------|---------------------------|--------------------------|
| Location | `app/Contexts/Membership/Application/Committee/` | `app/Contexts/Governance/` |
| Aggregates | Mixed with Membership | Pure Governance domain |
| Events | Not event-sourced | Full event sourcing |
| Read Model | Database queries | Projection + CQRS |
| API | None | REST API (Phase 3B) |
| Vue | AssignMemberModal (old) | CommitteeMemberManager (new) |

### Backward Compatibility

- ✅ Old `GetCommitteeDashboard` still works
- ✅ Old `AssignMemberModal` still available
- ⚠️ Being phased out — new code should use Governance
- 📅 Deprecation timeline: TBD

---

## When to Use Each Context

### Use Governance When...

- ✅ Managing committee structure
- ✅ Assigning/removing members
- ✅ Working with geographic hierarchies
- ✅ Querying "Which committees does member X belong to?"

### Use Membership When...

- ✅ Managing member profiles
- ✅ Tracking membership status
- ✅ Processing member fees
- ✅ Computing eligibility
- ✅ Querying "What is member X's status?"

### Use Both When...

- ✅ Deciding voting rights (needs structure + eligibility)
- ✅ Generating reports (needs all member data + organization)
- ✅ Member onboarding (register in Membership + assign to committees in Governance)
- ✅ Member removal (remove from committees + change status)

---

## Testing Across Contexts

### Example: Test That Depends on Both

```php
public function test_suspended_member_is_removed_from_committee(): void
{
    // Setup: Create member in Membership
    $member = Member::create(name: 'John');
    
    // Setup: Create committee in Governance
    $committee = Committee::create(name: 'Finance');
    $committee->addMember($member->id);
    
    // Action: Suspend member in Membership
    $member->suspend();
    Event::dispatch(new MembershipSuspended($member->id));
    
    // Assert: Member removed from committee (if policy enforces it)
    // OR: Member still in committee (if we allow it)
    // Depends on business rule!
}
```

---

## References

- **Governance Context:** `app/Contexts/Governance/`
- **Membership Context:** `app/Contexts/Membership/`
- **Architecture Decision Record:** `docs/adr/PHASE-3-COMPLETE.md`
- **Developer Guide:** `developer_guide/governence_contexts/README.md`

---

**Last Updated:** 2026-05-16  
**Author:** Dr. Nab Raj Roshyara  
**Status:** Approved for Production

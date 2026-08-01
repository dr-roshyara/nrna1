## Excellent Question — Let's Clarify the Two Committee Contexts

You're asking about the **CQRS (Command Query Responsibility Segregation)** pattern you've been building.

---

## What Are the Two Contexts?

```yaml
WRITE CONTEXT (Command):
  Location: Governance Domain (Committee Aggregate)
  Purpose: Handle commands that CHANGE state
  Examples:
    - Assign member to committee
    - Remove member from committee
    - Change member's role
  Characteristics:
    - Enforces business rules (no duplicate assignments)
    - Emits domain events
    - Source of truth for mutations
    - Slower, validation-heavy

READ CONTEXT (Query):
  Location: Committee Projection (Read Model)
  Purpose: Serve data for DISPLAY
  Examples:
    - List committee members
    - Get member's role in committee
    - Dashboard statistics
  Characteristics:
    - No business rules
    - Fast, optimized queries
    - Denormalized data
    - Can be rebuilt from events
```

---

## Why Separate Them?

### Problem Without Separation (Traditional Approach)

```yaml
ONE TABLE FOR BOTH:
  committees table with members JSON column
  Problem:
    - Every read loads the whole aggregate
    - Complex queries become slow
    - Lock contention between reads/writes
    - Hard to scale
```

### With Separation (CQRS)

```yaml
WRITE TABLE (Aggregate):
  - committees table (core data)
  - Optimized for validation
  - Few indexes, normalized

READ TABLE (Projection):
  - committee_member_projection
  - Optimized for queries
  - Many indexes, denormalized
  - Can have different structure
```

---

## Real Example from Your System

### WRITE Flow (What Happens)

```php
// User clicks "Assign Member"
POST /api/committees/123/members

// Write context handles:
1. Load Committee aggregate
2. Check if member already assigned
3. Add member (business rule)
4. Emit MemberAssignedToCommittee event
5. Save to committees table (write-optimized)

// Response: 202 Accepted
```

### READ Flow (What UI Sees)

```php
// UI loads member list
GET /api/committees/123/members

// Read context handles:
1. Query committee_member_projection table
   (already denormalized, no business logic)
2. Return JSON immediately

// Response: [{ memberId, name, role, assignedAt }]
```

---

## Why This Matters for Your System

### Benefits You Already Have

```yaml
1. PERFORMANCE:
   - UI doesn't wait for domain validation
   - Read queries are simple SELECTs
   - No locking between reads/writes

2. SCALABILITY:
   - Can add read replicas for projections
   - Write side remains small/focused

3. TESTABILITY:
   - Domain tests don't need complex queries
   - Projection tests are simple data assertions

4. FLEXIBILITY:
   - Projection can have different structure
   - Can rebuild projection from events
   - Can add new read models without changing domain
```

### Example from Your Fee Payment

```yaml
WRITE: Fee aggregate (financial authority)
  - Validates payment methods
  - Prevents duplicate payments
  - Emits FeePaid event

READ: Member fee state (projection)
  - Just shows paid/unpaid status
  - No validation logic
  - Fast for UI display
```

---

## When Would You NOT Separate?

```yaml
Simple CRUD apps:
  - No complex business rules
  - Low read/write volume
  - Small team

But your system has:
  ✅ Complex governance rules
  ✅ Constitutional constraints
  ✅ Audit requirements
  ✅ Event-driven architecture
```

---

## Visual Diagram

```yaml
┌─────────────────────────────────────────────────────────────┐
│                         USER ACTION                          │
│                   (Admin assigns member)                     │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                      WRITE CONTEXT                           │
│  ┌─────────────────────────────────────────────────────────┐│
│  │  Committee Aggregate                                    ││
│  │  - Check: Already assigned? → Reject if yes            ││
│  │  - Add member (business rule)                          ││
│  │  - Emit MemberAssignedToCommittee event                ││
│  └─────────────────────────────────────────────────────────┘│
│                                                             │
│  committees table (write-optimized, normalized)             │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼ (Event)
┌─────────────────────────────────────────────────────────────┐
│                      READ CONTEXT                            │
│                                                             │
│  Event Listener → Updates committee_member_projection       │
│                                                             │
│  committee_member_projection table (read-optimized)         │
│  - member_id, name, email, role, assigned_at               │
│  - Denormalized, many indexes                               │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                            UI                               │
│                   (Displays member list)                     │
└─────────────────────────────────────────────────────────────┘
```

---

## The Bottom Line

```yaml
WHY SEPARATE?
  - Write side: Enforces rules, slow but safe
  - Read side: Fast display, no rules
  - Different optimization goals
  - Can scale independently

YOUR SYSTEM:
  ✅ Already has separation (Committee aggregate + Projection)
  ✅ Events keep them in sync
  ✅ This enables role-based features

Without separation:
  - Complex queries slow down assignments
  - Business rules get bypassed
  - Hard to add role-based permissions
```

**The separation is what makes your event-driven DDD architecture powerful.**
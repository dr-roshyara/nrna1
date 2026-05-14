what is membership ? 
## What Is "Membership" Now?

In your DDD system, **membership** has evolved into a **temporal constitutional relationship** between a person and a committee. It is not a row in a table. It is not a boolean flag.

---

### The Story Book Model

```
📖 MembershipLineage (Aggregate Root)
   └── "The story of Ramu's relationship with NCC-DE over time"
       ├── Page 1: Joined Jan 2026 (ACTIVE)
       ├── Page 2: Suspended Mar 2026 (SUSPENDED)
       ├── Page 3: Restored Apr 2026 (ACTIVE)
       └── Page 4: Terminated Jun 2026 (TERMINATED)
```

---

### What Membership IS

| Concept | Meaning |
|---------|---------|
| **Membership** | A governed relationship with a lifecycle |
| **LineageId** | The permanent identity of this relationship |
| **Episode** | A snapshot of state at a point in time (CommitteeAssociation) |
| **Status** | The current constitutional state: ACTIVE, SUSPENDED, or TERMINATED |

---

### What Membership IS NOT

| Old Thinking | New Reality |
|-------------|-------------|
| A row in `committee_members` | A lineage with history |
| `is_member = true/false` | `currentStatus() = ACTIVE/SUSPENDED/TERMINATED` |
| CRUD: create/delete | Lifecycle: establish/suspend/restore/terminate/reapply |
| Snapshot of now | Complete history over time |

---

### Why This Matters

```
OLD WAY:
  "Is Ramu a member?" → Check one row → Yes/No
  Problem: Can't answer "Was Ramu a member in March?"

NEW WAY:
  "Is Ramu a member?" → Look at book cover → ACTIVE/SUSPENDED/TERMINATED
  "Was Ramu a member in March?" → Look at pages → Yes, ACTIVE in March
  "When was Ramu suspended?" → Look at pages → March to April
```

---

### The Three Readers of Membership

```
📖 MembershipLineage
    ├── Elections reads:  "Can Ramu vote now?"       → isEligibleForVoting()
    ├── Finance reads:    "Does Ramu owe fees?"       → isActiveDuring(period)
    └── Admin reads:      "What's Ramu's history?"    → episodes()
```

**Same book. Different questions. One source of truth.**
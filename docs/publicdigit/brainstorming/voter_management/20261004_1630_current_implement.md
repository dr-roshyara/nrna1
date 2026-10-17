Yes. Let me explain the **replacement of the current application step by step**, because Claude is not simply “changing a query.” It is gradually moving the application from **many independent voting-eligibility implementations to one authoritative entitlement decision**.

### 1. How the application works today

Currently, the application has several places that independently answer:

> “Is this user allowed to vote in this election?”

Conceptually:

```text
                    CURRENT APPLICATION

                         User
                          │
             ┌────────────┼─────────────┐
             │            │             │
             ▼            ▼             ▼
     User::isVoter    Controller     EnsuresVoter
       InElection       checks        Membership
             │            │             │
             ▼            ▼             ▼
        own query     own query      own query
             │            │             │
             └────────────┼─────────────┘
                          ▼
                    Database
```

The problem is that these implementations are **not guaranteed to mean exactly the same thing**.

For example, one path may check:

```text
role = voter
status = active
```

while another also checks:

```text
membership organisation = election organisation
```

and another may accidentally use the **ambient tenant context**.

That is the duplication C1 is fixing.

---

# 2. What C1 is doing

C1 introduces a single conceptual question:

> **What is this voter's current voting entitlement for this election?**

Instead of every caller implementing the rules itself, they ask:

```text
VotingEntitlementQuery
```

The new architecture is:

```text
                     NEW APPLICATION

 Controllers
 Middleware
 User model
 Voting checks
      │
      │
      ▼
┌──────────────────────────────┐
│ VotingEntitlementQuery       │
│                              │
│ "Is this voter entitled?"    │
└──────────────┬───────────────┘
               │
               ▼
     EloquentVotingEntitlementQuery
               │
               ▼
      VotingEntitlementContext
               │
               ▼
      VotingEntitlementPolicy
               │
               ▼
     VotingEntitlementReason
```

This is the key architectural change.

---

# 3. The database is NOT being replaced

This is important.

Claude is **not replacing `ElectionMembership`**.

It is not replacing the database model.

It is replacing the **duplicated decision logic around the model**.

The database still contains something like:

```text
ElectionMembership
------------------
user_id
election_id
role
status
organisation_id
...
```

That remains the registration/membership fact.

The new domain policy interprets those facts.

---

# 4. What `EloquentVotingEntitlementQuery` does

This class becomes the infrastructure adapter.

It takes:

```text
userId
electionId
```

and retrieves the relevant facts.

For example:

```text
Election
   │
   └── organisation_id

ElectionMembership
   │
   ├── user_id
   ├── election_id
   ├── role
   ├── status
   └── organisation_id
```

It then creates:

```text
VotingEntitlementContext
```

roughly:

```text
isRegistered = true
role = voter
status = active
organisationMatches = true
```

The important security correction is that the **election itself determines the organisation**.

It does not say:

> “What organisation is currently active in TenantContext?”

Instead:

> “What organisation owns this particular election?”

That is why `withoutGlobalScope('tenant')` belongs in the **Eloquent infrastructure implementation**, not in the application port.

---

# 5. Then the domain decides

The infrastructure has gathered facts.

It now hands them to:

```text
VotingEntitlementPolicy
```

The policy evaluates them in a fixed order:

```text
1. Registered?
       ↓ yes
2. Voter role?
       ↓ yes
3. Organisation matches?
       ↓ yes
4. Membership active?
       ↓ yes
5. Election votable?
       ↓ yes
6. ENTITLED
```

If something fails:

```text
NotRegistered
NotVoterRole
OrganisationMismatch
NotActive
ElectionNotVotable
```

So instead of returning random booleans from different places, the application has one deterministic domain decision.

---

# 6. What happens to `User::isVoterInElection()`?

This is a very important part of the migration.

### Before

`User` contained the actual database query:

```text
User
 │
 └── isVoterInElection()
       │
       ├── query membership
       ├── check role
       ├── check status
       ├── check organisation
       └── return boolean
```

### After

`User` becomes a **delegating compatibility API**:

```text
User
 │
 └── isVoterInElection()
       │
       ▼
VotingEntitlementQuery
       │
       ▼
VotingEntitlementPolicy
```

So existing application code can continue calling:

```php
$user->isVoterInElection($electionId)
```

without keeping the old decision logic.

This is a **strangler-style refactoring**: callers can remain stable while the decision mechanism underneath is replaced.

---

# 7. What happens to `EnsuresVoterMembership`?

Same principle.

### Before

It had its own query:

```text
EnsuresVoterMembership
        │
        └── ElectionMembership query
              ├── voter
              └── active
```

### After

It becomes:

```text
EnsuresVoterMembership
        │
        ▼
VotingEntitlementQuery
        │
        ▼
authoritative entitlement decision
```

And importantly, Claude preserves two modes:

```text
Normal/cached path
       ↓
VotingEntitlementQuery
       ↓
CachedVotingEntitlementQuery


Security-critical transactional path
       ↓
EloquentVotingEntitlementQuery
       ↓
fresh database decision
```

That is a good distinction.

---

# 8. Why the cache is also being replaced

The old application had a cache such as:

```text
user.{id}.voter.v2.{election}
```

associated with the old implementation.

C1 introduces:

```text
VotingEntitlementCacheKey
```

and:

```text
CachedVotingEntitlementQuery
```

So:

```text
              CachedVotingEntitlementQuery
                         │
                         ▼
               VotingEntitlementCacheKey
                         │
                         ▼
                  cached standing
```

But the important rule is:

> **The final voting decision is not allowed to depend on a stale cached lifecycle decision.**

Therefore:

```text
standingOf()
   → may use cache

decide()
   → fresh
```

And the actual transaction-critical voting check uses the uncached implementation.

---

# 9. What happens when membership changes?

Previously:

```text
ElectionMembership changed
        │
        ▼
old voter cache invalidated
```

Now:

```text
ElectionMembership changed
        │
        ▼
VotingEntitlementCacheKey
        │
        ▼
standing cache invalidated
```

So the new cache belongs to the new decision architecture.

---

# 10. What is NOT being replaced yet

This is equally important.

C1 is **not implementing the new identity-admission system yet**.

It does **not** yet introduce:

```text
VoterAdmissionPolicy
AdmissionRequirement
AdmissionEvidence
AdmissionDecision
VerificationAuthority
```

Those are C2/C3.

So currently the decision is still essentially:

```text
Registration
    +
Voter role
    +
Organisation match
    +
Active membership
    +
Election votable
    =
Voting Entitlement
```

Identity verification will later become an additional admission condition, but **not inside the C1 `VotingEntitlementPolicy`**.

---

# 11. The final architecture after C1

The most useful way to understand the whole replacement is:

```text
                         APPLICATION

 ┌───────────────────────────────────────────────────────┐
 │                    Callers                            │
 │                                                       │
 │ User / Controllers / Middleware / Voting checks       │
 └──────────────────────────┬────────────────────────────┘
                            │
                            ▼
              ┌──────────────────────────┐
              │ VotingEntitlementQuery   │
              │       APPLICATION PORT   │
              └─────────────┬────────────┘
                            │
                 ┌──────────┴───────────┐
                 │                      │
                 ▼                      ▼
       CachedVoting...          EloquentVoting...
                 │                      │
                 │                      │
                 └──────────┬───────────┘
                            ▼
                VotingEntitlementContext
                            │
                            ▼
                VotingEntitlementPolicy
                            │
                            ▼
                VotingEntitlementReason
                            │
             ┌──────────────┴──────────────┐
             │                             │
         Entitled                       Denied
```

And the **database remains below the infrastructure layer**:

```text
                 EloquentVotingEntitlementQuery
                           │
                 ┌─────────┴─────────┐
                 ▼                   ▼
             Election        ElectionMembership
```

---

## The most important architectural change

The application is moving from:

> **“Every component decides whether someone can vote.”**

to:

> **“Every component asks one authoritative domain decision whether someone is entitled to vote.”**

That is why Claude is deleting the old queries from `User`, `EnsuresVoterMembership`, controllers, etc.

It is **not deleting the functionality**.

It is **moving ownership of the decision**.

And this is exactly what we want from **C1 — Voting Entitlement Unification**.

The next major architectural change will be C2, where we extend the model from:

```text
Membership → Voting Entitlement
```

toward:

```text
Membership
    +
Voter Admission Policy
    +
Admission Evidence
    +
Admission Decision
    ↓
Voting Entitlement
```

But **we should not let Claude mix those two stages yet.**
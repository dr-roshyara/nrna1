That changes the strategy significantly.

You are **not in a greenfield constitutional-design phase**. You are in a **constitutional migration / convergence phase**:

> **An Election Manifesto already exists as the intended business authority, parts of the legacy implementation have been migrated to it, and other parts still operate through legacy mechanisms.**

So I would **not** ask Claude to redesign the voting architecture or immediately implement missing constitutional rules.

The immediate objective should be:

> **Discover exactly where the implemented Manifesto is authoritative, where legacy code still owns decisions, and where the two disagree — without changing code.**

### The correct DDD framing

Think of the current system as:

```text
                    ELECTION MANIFESTO
                    intended authority
                           │
             ┌─────────────┼─────────────┐
             │             │             │
        implemented    partially      not yet
        constitution   migrated       migrated
             │             │             │
             ▼             ▼             ▼
          new code     mixed paths    legacy code
             │             │             │
             └─────────────┼─────────────┘
                           ▼
                    Runtime behaviour
```

The dangerous situation is the middle:

> **A business decision may have two competing authorities.**

For example, your current findings suggest:

```text
Voter entitlement
       │
       ├── ElectionMembership      ← newer/current mechanism
       │
       └── legacy fields           ← historical mechanism

One-vote invariant
       │
       ├── Code credential          ← current enforcement
       │
       └── ElectionMembership.has_voted ← legacy/inert consumer
```

That is exactly what you need to discover **before completing the Manifesto implementation**.

---

# What Claude should do next

I would change the previous prompt substantially.

Claude should **not primarily ask "can the election run?"**

It should ask:

> **For every business decision in the Election Manifesto, what is currently authoritative in production code?**

Then runtime verification is used to validate the answer.

## Recommended next investigation

### 1. Build a Manifesto Decision Map

For every constitutional/business rule already defined in the Manifesto:

| Business decision      | Manifesto authority | Current implementation authority | Legacy authority    | Runtime verified | Status   |
| ---------------------- | ------------------- | -------------------------------- | ------------------- | ---------------- | -------- |
| Election lifecycle     | Manifesto           | Lifecycle engine                 | old state fields    | Yes/No           | Partial  |
| Voter entitlement      | ?                   | ElectionMembership               | `is_voter` etc.     | Yes/No           | Partial  |
| Voting access          | ?                   | Code / lifecycle                 | legacy fields       | Yes/No           | Partial  |
| One vote               | ?                   | Code exhaustion                  | `has_voted`         | Yes/No           | Partial  |
| Candidate completeness | ?                   | transition guard                 | temporal derivation | Yes/No           | Conflict |
| Election-Only          | ?                   | `voter_source_strategy`          | old voter model     | Yes/No           | Unclear  |
| Results                | ?                   | ?                                | ?                   | Yes/No           | Unclear  |

The important column is:

### **Current decision authority**

Not "which class contains the field."

---

# 2. Identify competing authorities

For every Manifesto rule ask:

> Is there exactly **one** authoritative decision path?

If yes:

**Converged.**

If no:

**Competing authority.**

If the Manifesto says X but production code does Y:

**Constitutional implementation gap.**

If production code implements X but the Manifesto does not define X:

**Undocumented business behaviour.**

If an old field is read but does not affect the final decision:

**Legacy consumer / inert representation.**

This classification is much more useful than simply calling something "legacy."

---

# 3. Then investigate the Election-Only voter path

Your specific question about Election-Only is now very important.

Claude should determine:

```text
Election-Only mode
       ↓
voter import
       ↓
ElectionMembership
       ↓
Manifesto-defined entitlement?
       ↓
voting access
       ↓
credential
       ↓
vote
```

The key question is not:

> "Does Election-Only work?"

It is:

> **"What business decision grants an imported Election-Only voter the right to vote, and does that decision come from the Election Manifesto or from legacy/application code?"**

That is the architectural question.

---

# 4. Do not remove legacy columns yet

Given your current migration phase, I would explicitly forbid Claude from doing this:

```text
"old field found"
       ↓
"legacy"
       ↓
"delete it"
```

Instead:

```text
old field
   ↓
who reads it?
   ↓
does it influence a business decision?
   ↓
does the Manifesto define that decision?
   ↓
is another authority already responsible?
   ↓
classify
```

Only after that should you decide whether the field is:

* transitional;
* compatibility-only;
* redundant;
* dead;
* dangerous legacy authority;
* legitimate persistence;
* projection.

---

# 5. The runtime election experiment is still useful

But now it has a different purpose.

You should run the five-voter election **after the static authority map**.

The experiment becomes a validation of the migration:

> **Does the partially constitutionalized system actually execute the business rules that the Manifesto claims to own?**

That makes the runtime experiment much more valuable.

---

# Prompt I would give Claude now

```text
You are working as a Senior Principal Architect and DDD Architect.

We are NOT designing a new Election architecture.

We are currently implementing the Election Manifesto/Constitution into an
existing legacy Election system.

The implementation is PARTIALLY constitutionalized and PARTIALLY legacy.

Therefore the objective of this investigation is:

    DISCOVER THE CURRENT AUTHORITY MAP

not:

    redesign the architecture
    repair legacy code
    remove legacy fields
    make tests green
    complete the Manifesto
    invent missing constitutional rules

============================================================
ABSOLUTE MODE
============================================================

READ-ONLY INVESTIGATION.

You may inspect the entire repository.

You may inspect database schema and existing runtime data.

You may execute existing application paths where necessary for verification.

You may create a controlled test election only if required for verification
and only through existing supported application behaviour.

You MUST NOT:

- modify production code;
- modify tests;
- modify fixtures;
- modify migrations;
- modify the database schema;
- restore legacy fields as authority;
- remove legacy fields;
- refactor;
- implement missing Manifesto rules;
- modify the Election Manifesto;
- invent business rules;
- silently repair defects.

If a defect is found, record it and stop at the boundary.

============================================================
PRIMARY QUESTION
============================================================

For every important Election business decision:

    What does the Manifesto say?

    What currently decides it in production?

    Is there one authority or several competing authorities?

    Is the current implementation consistent with the Manifesto?

============================================================
DDD ORDER OF INVESTIGATION
============================================================

Always investigate in this order:

    BUSINESS INVARIANT
          ↓
    DOMAIN MEANING
          ↓
    MANIFESTO / CONSTITUTIONAL AUTHORITY
          ↓
    AUTHORITATIVE APPLICATION DECISION
          ↓
    POLICY / AUTHORIZATION
          ↓
    APPLICATION USE CASE
          ↓
    PERSISTENCE
          ↓
    INTERFACE / PROJECTION
          ↓
    TEST

Never reverse this order.

Do not infer business meaning from database structure.

Do not infer authority from a field name.

Do not infer constitutional ownership from a class name.

============================================================
LEGACY / NEW IMPLEMENTATION CLASSIFICATION
============================================================

For every legacy-looking mechanism classify it as exactly one of:

1. CURRENT AUTHORITATIVE MECHANISM
2. CONSTITUTIONAL IMPLEMENTATION
3. PARTIAL CONSTITUTIONAL IMPLEMENTATION
4. LEGACY COMPATIBILITY MECHANISM
5. LEGACY CONSUMER BUT NOT AUTHORITATIVE
6. DEAD / INERT MECHANISM
7. COMPETING AUTHORITY
8. UNDOCUMENTED BUSINESS BEHAVIOUR
9. UNDETERMINED

Never call something "legacy" merely because it is old.

============================================================
ELECTION MANIFESTO AUTHORITY MATRIX
============================================================

Build a matrix for the existing Manifesto rules.

At minimum investigate:

- Election lifecycle
- Administration phase
- Nomination phase
- Candidate eligibility
- Candidate approval
- Voting phase
- Voting-window access
- Voter entitlement
- ElectionMembership
- Election-Only mode
- Full-membership mode
- Voter import
- Voting credential issuance
- First-vote authorization
- One-vote-per-election
- Anonymous ballot
- Vote persistence
- Vote closing
- Counting
- Results
- Audit/evidence
- Tenant/organisation boundary

For every rule record:

BUSINESS RULE
MANIFESTO SOURCE
DOMAIN MEANING
CURRENT AUTHORITY
LEGACY AUTHORITY
APPLICATION ENFORCEMENT
PERSISTENCE REPRESENTATION
PROJECTION
RUNTIME EVIDENCE
STATUS

============================================================
ELECTION-ONLY MODE
============================================================

Investigate specifically:

    Organisation election mode
        ↓
    Election creation
        ↓
    voter_source_strategy
        ↓
    Election-Only import
        ↓
    ElectionMembership
        ↓
    voter entitlement
        ↓
    voting access
        ↓
    credential creation
        ↓
    first vote

Determine:

1. Is Election-Only explicitly defined by the Manifesto?
2. If yes, what business rule does it establish?
3. If no, is it merely implementation-established behaviour?
4. Does Election-Only and full-membership mode converge at the same
   authoritative voter representation?
5. What event grants voting entitlement?
6. What event grants actual voting access?
7. Are those two decisions deliberately separate?
8. Which component owns each decision?

Do not answer these questions from code shape alone.

============================================================
VOTER ENTITLEMENT
============================================================

Investigate the difference between:

- voter registration;
- eligibility;
- entitlement;
- voting access;
- credential validity;
- vote execution;
- vote history.

Do not collapse these concepts.

Determine whether:

    ElectionMembership

is intended to represent:

    membership
    eligibility
    entitlement

or only one of them.

If the Manifesto does not answer this, record:

    BUSINESS DECISION REQUIRED

============================================================
ONE-VOTE INVARIANT
============================================================

Investigate:

    ElectionMembership.has_voted
    Code.has_voted
    Code.can_vote_now
    VoterSlug.can_vote_now
    votes
    unique database constraints

The question is NOT:

    "Which has_voted field exists?"

The question is:

    "Where is the one-vote-per-election business invariant actually
     owned and enforced?"

Preserve the distinction between:

    entitlement
    execution permission
    exercise history

============================================================
COMPETING AUTHORITIES
============================================================

Pay particular attention to situations like:

    guarded lifecycle transition
            VS
    temporal lifecycle projection

or:

    ElectionMembership
            VS
    legacy voter fields

or:

    Code.can_vote_now
            VS
    VoterSlug.can_vote_now

If two mechanisms can independently determine the same business outcome,
record them as:

    COMPETING AUTHORITY

unless evidence proves that one is merely a projection or compatibility
mechanism.

============================================================
RUNTIME VERIFICATION
============================================================

After the authority map is established, perform only the minimum runtime
experiments necessary to validate it.

Do not start by creating another election merely because the previous
experiment encountered a problem.

First establish the mechanism statically.

Then create one controlled election if runtime evidence is required.

The runtime experiment should ultimately determine:

    Can an entitled Election-Only voter legitimately reach the ballot?

    Can the voter cast the first vote?

    Is the vote persisted?

    Is the credential exhausted?

    Is the second vote rejected?

But these are verification questions, not repair tasks.

============================================================
EVIDENCE DISCIPLINE
============================================================

Every finding must distinguish:

OBSERVED FACT
INTERPRETATION
HYPOTHESIS
CONCLUSION

If the mechanism has not been measured:

    MECHANISM NOT ESTABLISHED

If a business decision is missing:

    BUSINESS DECISION REQUIRED

If two authorities exist:

    COMPETING AUTHORITY

If the implementation exists but the Manifesto does not define it:

    IMPLEMENTATION-ESTABLISHED / CONSTITUTIONALLY UNDEFINED

If the Manifesto defines it but implementation does not:

    CONSTITUTIONAL IMPLEMENTATION GAP

============================================================
FINAL DELIVERABLE
============================================================

Produce a durable architectural discovery report containing:

1. Executive summary
2. Manifesto authority matrix
3. Election lifecycle authority map
4. Voter entitlement authority map
5. Election-Only authority map
6. Voting-access authority map
7. One-vote authority map
8. Legacy authority register
9. Competing-authority register
10. Constitutional implementation gaps
11. Undocumented business behaviour
12. Runtime verification evidence
13. Defects discovered
14. Business decisions still required
15. Recommended next discovery sequence

DO NOT implement any recommendation.

============================================================
MOST IMPORTANT RULE
============================================================

We are currently migrating a legacy Election implementation toward the
Election Manifesto.

Therefore:

    DISCOVER FIRST.
    CLASSIFY SECOND.
    VERIFY THIRD.
    DECIDE FOURTH.
    IMPLEMENT LAST.

Do not skip the decision-authority step merely because a legacy mechanism
already works.

Do not break working legacy behaviour until the Manifesto's intended
authority and the migration boundary are understood.

At the end, STOP and await Product Owner / Principal Architect review.
```

This is the prompt I would use **now**, rather than the previous "build the five-voter runtime experiment" prompt.

The five-voter experiment remains important, but **the authority map should come first** because you are actively migrating the legacy system toward the Manifesto. Otherwise Claude may correctly prove that something works while still failing to answer the more important architectural question: **whether the thing that works is now being decided by the correct authority.**

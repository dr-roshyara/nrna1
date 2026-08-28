This is a **good result**, and importantly, Claude has now done exactly what you wanted: it moved from hypothesis to controlled evidence.

### What has actually been proven

The important experiment is:

```text
Same user
Same election
Same ElectionMembership row
Same database
Same application code

Only change:
    session / TenantContext organisation
```

Result:

```text
TenantContext = wrong organisation
        ↓
ElectionMembership global scope hides the valid membership
        ↓
isEligible = false

TenantContext = Experiment B organisation
        ↓
same membership becomes visible
        ↓
isEligible = true
```

That is a very strong causal experiment.

### Architectural interpretation

I agree with Claude's DDD classification:

**This is not primarily an Election business-rule defect.**

The business rule appears to be:

> An `ElectionMembership` with `role = voter` and `status != removed` makes the person eligible for that election.

That representation was found and validated.

The defect is at the **context/infrastructure boundary**:

```text
                    DOMAIN
                      │
             ElectionMembership
                      │
              "is this person
               a voter?"
                      │
                      ▼
              correct answer
                      │
                      │
        ┌─────────────┴─────────────┐
        │                           │
 correct tenant context       wrong tenant context
        │                           │
        ▼                           ▼
 membership visible          membership invisible
        │                           │
        ▼                           ▼
   eligible = true          eligible = false
```

The particularly serious architectural statement is:

> **A voter's right to vote currently depends on which organisation page they visited last.**

That is not merely a UI inconvenience. It means **request context is influencing a business authorization/eligibility decision indirectly**.

### And this is exactly where we should be careful

I would **not yet tell Claude where to fix it**.

Claude correctly stopped before saying:

* change `ElectionMembership` scope;
* remove tenant context;
* change the election route;
* establish tenant context from the election;
* modify `isEligible`;
* introduce a new application service.

Those are **solution decisions**, not findings.

The next architectural question is:

> **Who owns the tenant boundary for an election-specific operation?**

That needs to be answered before implementation.

For example, there are several possible designs:

1. **Election route establishes tenant context**

   * `/elections/{election}` resolves the election first and establishes its organisation.

2. **ElectionMembership query derives context from the election**

   * potentially safer for election-specific eligibility, but changes infrastructure semantics.

3. **Election eligibility is explicitly scoped by election**

   * rather than relying on ambient organisation context.

4. **TenantContext is required for all election operations**

   * and the route is considered invalid without it.

These are materially different architectural choices.

### More importantly: don't "work around" it

Claude says:

> With tenant context set, voter 1 shows `isEligible: true` ... the rest of the journey is reachable.

I would **not continue the voting experiment using that workaround yet** if the purpose of the programme is to verify the real user journey.

Because then the experiment becomes:

> "Can voting work if we manually repair the infrastructure context first?"

rather than:

> "Can an organisation run an election through the intended product journey?"

The workaround is useful as a **secondary controlled experiment**, but it should not be confused with successful end-to-end verification.

### What I would record now

The result should be classified approximately as:

| Dimension                     | Finding                                          |
| ----------------------------- | ------------------------------------------------ |
| Election lifecycle            | ✅ `voting_active`                                |
| Candidate                     | ✅ approved President candidate                   |
| Election membership           | ✅ valid voter membership exists                  |
| Eligibility business rule     | ✅ correct when membership is visible             |
| Tenant resolution             | ❌ wrong ambient tenant can hide valid membership |
| Root cause                    | ✅ proven                                         |
| Business rule defect          | ❌ not established                                |
| Infrastructure/context defect | ✅ established                                    |
| Vote persistence              | ⏳ not tested                                     |
| One-vote enforcement          | ⏳ not tested                                     |
| Ineligible-voter rejection    | ⏳ not tested                                     |
| Results                       | ⏳ not tested                                     |

And importantly:

**Experiment B is not "failed election verification."**

It has discovered a **pre-voting infrastructure defect** that prevents the intended journey from reliably reaching the voting decision.

### I would make the next session do one thing

Before any code change, ask Claude to perform a **read-only architectural trace of tenant ownership**:

> Where should the organisation context for an election-specific request come from?

That investigation should trace:

```text
login
  ↓
current organisation
  ↓
organisation page
  ↓
election creation
  ↓
election identity
  ↓
/elections/{election}
  ↓
TenantContext
  ↓
ElectionMembership
  ↓
eligibility
```

The key question is not *"how do we make this pass?"*

It is:

> **What is the authoritative source of organisation identity for an election request, and why is an ambient session organisation allowed to override it?**

That is the right **DDD / separation-of-concerns question**.

Only after that is answered should you decide whether to continue Experiment B, create another controlled election, or authorize a repair.

And I would keep the two programmes separate: this independent runtime Election Verification programme is producing exactly the kind of evidence that can later feed PBDIGIT-48, without allowing PBDIGIT-48's migration work to distort the runtime experiment.

#
Yes — your concern is justified. There are **three different concepts that have been mixed together in the history of this project**, and that is exactly why the current situation is confusing.

I checked the material you previously provided rather than assuming the current code tells us what the Constitution means.

## 1. There really is an Election-only concept

The architecture/discovery material explicitly distinguishes:

* **Full Membership**
* **Election-Only**

and describes the relationship between Membership and Election as **mode-dependent**:

> Election-Only mode: Membership is optional
> Full Membership mode: Membership is required. 

There is also a persisted `voter_source_strategy` on the Election.

The design material says that this is intended to be an **election-specific constitutional snapshot**, created when the election is born and then frozen as the election progresses. 

So conceptually:

```text
Organisation
     │
     │ default policy
     ▼
Election created
     │
     ▼
voter_source_strategy
     │
     ├── Full Membership
     │
     └── Election Only
```

The important idea is:

> **The organisation's policy should not dynamically determine the voter source of an already-created election.**

The election has its own snapshot.

---

# 2. ElectionMembership is also a real concept

This is where your memory is correct.

The architecture material distinguishes **organisation membership** from **election enrollment/registration**.

The discovery material explicitly identifies:

> **ElectionEnrollment** — the participant's enrollment in a specific election, tracking whether a voter is registered for a particular election. 

And the Election context is described as owning:

> **Voter registration for THIS election**. 

That is essentially what the current `ElectionMembership` represents.

So we need to distinguish:

```text
Organisation Membership
        │
        │ "Is this person a member of the organisation?"
        ▼
      Member


Election Membership / Enrollment
        │
        │ "Is this person registered as a voter
        │  for THIS particular election?"
        ▼
ElectionMembership
```

Those are **not the same business concept**.

---

# 3. And yes — there was an older/database-based voter system

This is the really important part.

Your memory that we previously had a database-driven voter management mechanism is correct.

The historical material shows several older mechanisms involving things like:

* `User.can_vote`
* election membership status
* `VoterSlug`
* procedural `vote_pre_check()`
* `ensureVoterMembership()`
* other legacy checks.

One earlier audit explicitly described the voting path as having split sovereignty, with `ElectionLifecycle::canVote()`, `ensureVoterMembership()`, and legacy checks all participating in enforcement. 

So the system did **not** start with the clean constitutional model we are discussing now.

It evolved.

That is why PBDIGIT-48 exists in the first place.

---

# 4. Where the new model was supposed to take us

The newer architecture tries to separate **three different questions**.

### Question A — Is the election open?

This belongs to the Election Lifecycle.

```text
ElectionLifecycle::canVote()
```

It answers:

> "Is voting temporally open?"

It should know nothing about whether Nab, Alice or Bob is eligible.

The architecture explicitly says these two gates must not be merged. 

---

### Question B — Is this person eligible for THIS election?

This is the participation/voter eligibility question.

Conceptually:

```text
Election
   +
voter source strategy
   +
person
   ↓
Eligibility decision
```

For ElectionMembership-based participation, the current representation is roughly:

```text
ElectionMembership
    user_id
    election_id
    organisation_id
    role = voter
    status = active
```

The code/material explicitly describes active election voters as `role = voter` and `status = active`. 

So yes:

> **ElectionMembership is now being treated as the election-specific voter registration/participation record.**

---

### Question C — Is the request actually allowed to perform the action?

That is authorization/policy.

For example:

```text
Is this person an Election Chief?
```

is different from:

```text
Is this person a voter?
```

and different again from:

```text
Is the election currently open?
```

This separation is extremely important.

---

# 5. So what went wrong in Experiment B?

Now the current bug becomes much easier to understand.

We had:

```text
Election 2026
    │
    ├── voter source strategy
    ├── candidate
    ├── voting window
    └── ElectionMembership
             │
             └── Voter 1
```

The database contained the correct ElectionMembership row.

Claude proved that.

But `ElectionMembership` is subject to a tenant/global scope which effectively says:

```text
organisation_id == current TenantContext organisation
```

And the `/elections/{election}` request did **not reliably establish its organisation context from the election**.

So suppose:

```text
Election belongs to Organisation A

Voter belongs to Election A
```

but the browser's current tenant context is:

```text
Organisation B
```

The query effectively becomes:

```text
Find ElectionMembership
WHERE
    election_id = A
AND user_id = voter
AND organisation_id = B
```

Result:

```text
NOT FOUND
```

Even though the real row is:

```text
election_id = A
user_id     = voter
organisation_id = A
```

Therefore:

```text
database:
    voter IS registered

application query:
    voter appears NOT registered

eligibility:
    false
```

And Claude proved the causal relationship by changing **only the tenant context**:

```text
wrong tenant → isEligible = false

correct tenant → isEligible = true
```

That is a very good experiment.

---

# 6. This is why I would NOT yet say "ElectionMembership is the problem"

This distinction is critical.

The evidence currently says:

### Business concept

**ElectionMembership / election enrollment exists.**

### Data

**The correct membership row exists.**

### Eligibility rule

**The row satisfies the currently implemented election-voter condition.**

### Infrastructure

**The row can become invisible because the ambient tenant context is wrong.**

Therefore the current finding is:

> **The infrastructure context can cause a correct election-voter registration to be interpreted as absent.**

That is different from:

> "ElectionMembership is the wrong constitutional model."

We haven't proven that.

---

# 7. But there is a deeper question we should now ask

And this is where your instinct about the Constitution is important.

We should **not assume that the current implementation of `ElectionMembership` automatically means the Constitution has declared it sovereign.**

The discovery documents contain architectural assertions such as:

> Membership manages eligibility, while Election owns voter registration for the particular election. 

But some of the material is **architectural discovery/design**, not necessarily the final Constitutional authority.

And another document explicitly describes eligibility as a separate concept whose boundary was accepted in Round 25. It says eligibility is computed at action time and includes membership status and election enrollment. 

So we should distinguish:

| Question                                                                | Current evidence                                                                                 |
| ----------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------ |
| Does Election-only exist?                                               | **Yes, strongly evidenced**                                                                      |
| Does ElectionMembership exist?                                          | **Yes**                                                                                          |
| Does it represent election-specific voter enrollment?                   | **Yes, explicitly described**                                                                    |
| Is it the current implementation source for voter registration?         | **Yes**                                                                                          |
| Is it constitutionally sovereign?                                       | **Needs careful confirmation**                                                                   |
| Is voter eligibility itself constitutionally defined?                   | **There are constitutional/design rules, but we should verify the exact authoritative contract** |
| Was there a previous DB/legacy voter system?                            | **Yes**                                                                                          |
| Is the current tenant bug proof that the constitutional model is wrong? | **No**                                                                                           |
| Is it proof of an infrastructure/context defect?                        | **Yes**                                                                                          |

---

# 8. The historical transition is probably what is confusing you

Think of the system as having gone through this evolution:

```text
OLD SYSTEM
──────────

User
 ├── can_vote
 ├── is_voter
 └── other voter flags

Election
 └── status / legacy lifecycle

VoterSlug
ElectionMembership
procedural checks
etc.

        ↓
    PBDIGIT-48
        ↓

NEW CONSTITUTIONAL MODEL
────────────────────────

ElectionLifecycle
       │
       └── election lifecycle

Election voter-source strategy
       │
       ├── Full Membership
       │
       └── Election Only

ElectionMembership
       │
       └── voter enrollment for THIS election

Eligibility
       │
       └── determine participation eligibility

Authorization
       │
       └── determine whether actor may perform operation
```

The problem is that **the repository is in transition between these worlds**.

That is exactly why your independent runtime verification is valuable.

You are not merely asking:

> "Does the code work?"

You are asking:

> **"Does the business process described by the new constitutional architecture actually work end-to-end, or are remnants of the old model still influencing it?"**

That is an excellent verification objective.

---

# 9. And this changes how I would continue the Election experiment

I would **not immediately repair the tenant bug**.

First, we should establish the business model precisely.

For the Election 2026 experiment, the next read-only investigation should answer:

### A. What does Election-only actually mean?

Does it mean:

> Anyone explicitly enrolled into `ElectionMembership` can vote, regardless of organisation membership?

or does it mean something else?

### B. What makes someone an eligible voter?

Is it exactly:

```text
ElectionMembership
role = voter
status = active
```

or are there additional constitutional conditions?

### C. What is the authoritative source?

For Election-only:

```text
Election
  voter_source_strategy = ElectionOnly
       ↓
ElectionMembership
       ↓
Eligibility
```

For Full Membership:

```text
Election
  voter_source_strategy = FullMembership
       ↓
Membership
       ↓
Eligibility
```

### D. Where does organisation identity come from?

This is the bug Claude just found.

It must not silently become:

```text
"whatever organisation the browser visited last"
```

if the business operation is actually:

```text
"this specific election"
```

---

## My conclusion

Your memory is essentially correct:

**There was an older database-driven voter-management model, and the project has been moving toward a more explicit constitutional model involving Election-specific voter sourcing and ElectionMembership.**

The current Experiment B finding does **not** prove that the new model is wrong.

It proves something more specific:

> **The new ElectionMembership-based eligibility mechanism can be made to return the wrong business answer because an infrastructure-level tenant context hides the correct election membership.**

And that is precisely the sort of boundary violation your independent Election Verification programme should discover.

I would therefore keep the experiment **read-only** for now and have Claude verify the constitutional/business meaning of **Election-only → ElectionMembership → voter eligibility** before deciding whether the tenant-context problem is a defect in infrastructure, in the application boundary, or evidence of an incomplete migration from the old voter-management model.

That distinction will be very important later when this evidence feeds into PBDIGIT-48.
#
Yes. The attached Claude result actually gives us a **much stronger architectural finding** than simply "there is a tenant bug."

The crucial discovery is this:

> There is currently **no authoritative Election Manifesto defining Election-Only mode or voter eligibility**. The code calls `ElectionConstitution.php` the single source of truth, but that constitution does not define those concepts. 

Even more importantly, the current constitutional predicate still combines **two voter authorities**:

```php
$election->voters()->withoutGlobalScopes()->exists()
||
$election->memberships()
    ->where('role', 'voter')
    ->where('status', 'active')
    ->exists()
```

So we should **not jump directly to fixing PBDIGIT-65**. We have uncovered a strategic DDD/domain-governance question first.

## The DDD question

Your business scenario is:

> Organisation owner creates Election 2026 → appoints himself Election Chief → imports 5 voters → grants them the right to vote → creates/approves candidate → opens voting → voters enter voting window → eligible voters cast votes → results are produced.

That is a **business process**, not merely a database workflow.

DDD therefore asks:

> **Which bounded context owns the business decision "this person has the right to participate in this election"?**

Not:

> Which Eloquent model currently contains `role = voter`?

Those are completely different questions.

---

# Who should own what?

I would establish this conceptual separation before any implementation work:

| Business decision                                                | Likely owner                                   | Responsibility                             |
| ---------------------------------------------------------------- | ---------------------------------------------- | ------------------------------------------ |
| What election phase are we in?                                   | **Election Domain / Lifecycle**                | nomination, voting, results                |
| Is there a valid candidate?                                      | **Election Domain**                            | candidacy/nomination rules                 |
| Who is registered for this election?                             | **Election Domain / Voter Participation**      | election-specific enrollment               |
| Does this person have voting rights in this election?            | **Election Domain / Eligibility Policy**       | eligibility decision                       |
| Is voting currently open?                                        | **Election Lifecycle**                         | temporal access                            |
| Is this actor allowed to administer voters?                      | **Application Authorization / Policy**         | chief/deputy permissions                   |
| How are voters stored?                                           | **Infrastructure/Persistence**                 | database representation                    |
| Which organisation is currently selected in browser?             | **Interface/Infrastructure**                   | tenant context                             |
| Does the browser's current organisation determine voting rights? | **No, unless explicitly made a business rule** | should not leak infrastructure into domain |

The important architectural statement is:

> **Tenant context must not silently become a business authority.**

Claude's runtime finding already demonstrates why. The voter was correctly enrolled, but a previous organisation page changed the tenant context and caused the voter to appear ineligible. 

---

# The bigger problem: "grant voting right" is not yet constitutionally defined

This is the part I would investigate next.

Your phrase:

> "after importing voters in election-only mode, the voters must be granted right to vote"

contains **two potentially different business events**:

```text
Import voter
      ↓
Register/enroll voter
      ↓
Grant voting eligibility/right
      ↓
Voting window opens
      ↓
Voter may cast ballot
```

But perhaps the business model actually says:

```text
Import voter
      ↓
Automatically becomes eligible
      ↓
Voting window opens
      ↓
May vote
```

Those are not equivalent.

We need the business owner to decide whether **importing a voter itself grants the right to vote**, or whether there is a separate authorization/activation step.

And this is exactly why I would now send Claude a **Principle Architect investigation prompt**, not a repair prompt.

---

# Prompt I would give Claude

```text
# PRINCIPAL ARCHITECT INVESTIGATION
## Election-Only → Voter Enrollment → Voting Right → Voting Access

This is a READ-ONLY DOMAIN / DDD / CONSTITUTIONAL INVESTIGATION.

DO NOT change production code.
DO NOT change tests.
DO NOT change fixtures.
DO NOT change the Election Constitution.
DO NOT create another election.
DO NOT repair PBDIGIT-65.
DO NOT repair PBDIGIT-64.
DO NOT choose a business rule on behalf of the Product Owner.

You may search and inspect the entire repository, source code, tests, ADRs,
discovery documents, architecture documents, database schema and existing
runtime evidence.

The objective is to establish the BUSINESS MODEL and DECISION OWNERSHIP
before any implementation decision is made.

---

# BUSINESS SCENARIO TO TRACE

The intended business journey is:

Organisation owner
    ↓
creates Election 2026
    ↓
becomes / appoints Election Chief
    ↓
chooses Election-Only voter management
    ↓
imports 5 people as voters
    ↓
those people receive voting rights / become eligible
    ↓
candidate is established
    ↓
voting window opens
    ↓
eligible voters access the ballot
    ↓
eligible voters cast votes
    ↓
votes are persisted
    ↓
results are produced

Do NOT assume that every arrow above is currently a valid business rule.

The purpose of this investigation is to discover which arrows are actually
defined by the authoritative business model and which are merely assumptions
from the current implementation.

---

# 1. FIND THE AUTHORITATIVE BUSINESS AUTHORITY

Establish what currently defines the Election business rules.

Investigate:

- Election Manifesto
- Election Constitution
- constitutional source code
- ADRs
- approved discovery decisions
- Product Owner decisions
- bounded-context definitions
- PBDIGIT-49
- PBDIGIT-48
- relevant prior Election discovery rounds

Do NOT assume that `ElectionConstitution.php` is the business Constitution
simply because the source code calls itself "THE SINGLE SOURCE OF TRUTH".

Determine:

A. Is there an actual ratified Election Manifesto?
B. Is there a constitutional specification outside source code?
C. Is `ElectionConstitution.php` the authoritative implementation of an
   externally defined Constitution?
D. Or is the PHP class currently serving as an implementation-defined
   pseudo-constitution?

If the answer is unclear, report:

    CONSTITUTIONAL AUTHORITY NOT ESTABLISHED

Do not fill the gap with engineering judgement.

---

# 2. DEFINE THE BUSINESS VOCABULARY

Establish the actual business meaning of each term:

- voter
- voter registration
- voter enrollment
- ElectionMembership
- voter eligibility
- voting right
- voting access
- voter authorization
- Election-Only
- Full Membership
- candidate
- approved candidate
- voting window

For every term classify:

    DOMAIN CONCEPT
    APPLICATION CONCEPT
    POLICY/AUTHORIZATION CONCEPT
    INFRASTRUCTURE CONCEPT
    INTERFACE CONCEPT
    UNDEFINED / AMBIGUOUS

Do not treat database table names as domain concepts automatically.

For example:

    election_memberships

does not by itself prove that "ElectionMembership" is the
constitutional business concept.

---

# 3. THE MOST IMPORTANT QUESTION:
# WHO OWNS THE DECISION "THIS PERSON MAY VOTE"?

Trace the decision from the business question downward:

    "May person X vote in Election Y?"

Determine which layer owns the decision:

    Domain
    Application
    Policy/Authorization
    Infrastructure
    Interface
    Unknown

Then identify the authoritative source.

Do NOT accept an answer such as:

    ElectionMembership.role = voter

unless you can establish that this field represents a business rule
rather than merely persistence state.

---

# 4. DISTINGUISH FOUR DIFFERENT DECISIONS

Do not collapse these:

### Decision A — Enrollment

    "Is this person registered/enrolled as a voter
     for this particular election?"

### Decision B — Voting right / eligibility

    "Does this person have the right to participate
     in this election?"

### Decision C — Voting-window access

    "Is the election currently in a phase where
     this eligible voter may access the ballot?"

### Decision D — Vote submission authorization

    "May this particular request submit a vote now?"

These may have different owners.

Establish whether they are currently:

    one decision
    or
    four separate decisions.

If they are separate, document the relationship.

---

# 5. INVESTIGATE ELECTION-ONLY MODE

Determine what Election-Only actually means.

Do not infer it from `uses_full_membership`,
`voter_source_strategy`, or database columns.

Establish whether Election-Only means:

    organisation membership is NOT required
    AND
    election-specific voter enrollment IS required

or something else.

Answer explicitly:

1. Who can be imported?
2. What does "import" mean in business terms?
3. Does import create enrollment?
4. Does import grant voting rights?
5. Is there a separate approval/activation step?
6. Can an Election-Only voter exist without organisation membership?
7. Can a person be enrolled but not eligible?
8. Can a person be eligible but voting still be unavailable because
   the voting window is closed?

If the repository does not establish these answers:

    BUSINESS RULE NOT ESTABLISHED

Do not invent them.

---

# 6. INVESTIGATE "GRANT THE RIGHT TO VOTE"

This phrase is intentionally under-specified.

Determine whether the current business model contains a distinct transition such as:

    imported
       ↓
    enrolled
       ↓
    approved/activated
       ↓
    eligible

or whether:

    imported
       ↓
    eligible

is the intended model.

Search for:

- voter activation
- voter approval
- eligibility status
- membership status
- voter status
- invitation acceptance
- ElectionMembership status
- voter role
- can_vote
- is_voter
- eligibility services/policies
- constitutional guards
- commands
- events

For every discovered mechanism state whether it is:

    authoritative
    derived
    procedural
    legacy
    persistence-only
    interface-only
    unknown

---

# 7. TRACE THE LEGACY MODEL

There is evidence that the system previously had database-based voter
management and multiple voter authorities.

Investigate:

- `Election::voters()`
- legacy voter tables
- `User.can_vote`
- `User.is_voter`
- ElectionMembership
- VoterSlug
- old voter eligibility services
- `has_voters`
- other voter-related predicates

Determine:

    Which authority was historically used?

    Which authority is intended to replace it?

    Has that replacement actually been constitutionally established?

    Is the old authority still participating in current business decisions?

Do not label something "legacy" merely because it is old.
Establish whether it has actually been superseded.

---

# 8. CRITICAL CURRENT FINDING: TWO AUTHORITIES

The current constitutional code contains:

    election.voters().withoutGlobalScopes()->exists()
    OR
    election.memberships()
        ->where('role','voter')
        ->where('status','active')
        ->exists()

Investigate this carefully.

Question:

    Does the business model intentionally define two valid voter
    authorities?

OR

    Is this an unfinished migration where two historical models have
    accidentally been combined?

Do not decide.

Report the evidence and the Product Owner decision required.

---

# 9. TENANT CONTEXT

Investigate whether tenant context is part of the business rule.

The runtime investigation already established:

    same voter
    same election
    same membership row
    different current organisation
    different eligibility result

Do not repeat the experiment unless necessary.

Instead determine architecturally:

    Is organisation context a DOMAIN INPUT to voter eligibility?

or

    Is organisation context merely infrastructure used to isolate data?

If it is infrastructure, determine whether it is currently leaking into
the domain decision.

Classify the finding as:

    DOMAIN RULE
    APPLICATION BOUNDARY
    INFRASTRUCTURE LEAK
    PERSISTENCE SCOPING
    INTERFACE/ROUTING
    UNKNOWN

Do not choose a repair location.

---

# 10. BUILD THE BUSINESS DECISION CHAIN

Produce this explicitly:

    Business invariant
          ↓
    Domain concept
          ↓
    Domain authority
          ↓
    Application capability
          ↓
    Authorization
          ↓
    HTTP/interface entry
          ↓
    Persistence
          ↓
    Test

For the specific question:

    "Can voter X vote in Election Y?"

fill every layer with the actual evidence.

If a layer has no established owner:

    OWNER NOT ESTABLISHED

---

# 11. BUILD THE RESPONSIBILITY MATRIX

Produce:

| Decision | Business meaning | Owning BC/layer | Authority | Current implementation | Status |
|---|---|---|---|---|---|
| Election-Only mode | | | | | |
| voter enrollment | | | | | |
| voting right | | | | | |
| voter eligibility | | | | | |
| voting window | | | | | |
| ballot access | | | | | |
| vote submission | | | | | |
| candidate eligibility | | | | | |
| result eligibility | | | | | |

Use only:

    ESTABLISHED
    PARTIAL
    CONTRADICTED
    UNDEFINED
    UNDETERMINED

---

# 12. DO NOT CONFUSE THESE THREE THINGS

This is a mandatory architectural rule:

    COVERAGE ≠ AUTHORITY ≠ COMPLETENESS

The fact that code contains a check does not prove:

    the business rule exists

The fact that a test passes does not prove:

    the correct authority owns the decision

The fact that a database row exists does not prove:

    the row represents the business concept

The fact that a constitutional predicate exists does not prove:

    it reflects the intended business Constitution.

---

# 13. OUTPUT

Produce one investigation report.

It must contain:

1. Authoritative business source
2. Business vocabulary
3. Election-Only semantics
4. Enrollment semantics
5. Voting-right semantics
6. Eligibility semantics
7. Voting-window semantics
8. Responsibility/ownership matrix
9. Legacy voter authority analysis
10. ElectionMembership analysis
11. Tenant-context analysis
12. Current constitutional contradictions
13. Open Product Owner decisions
14. Architectural questions
15. Evidence gaps

Every statement must be classified internally as:

    OBSERVED FACT
    INTERPRETATION
    HYPOTHESIS
    CONCLUSION

Where evidence is missing, say:

    MECHANISM NOT ESTABLISHED
    or
    BUSINESS RULE NOT ESTABLISHED

Never convert an engineering interpretation into a business rule.

---

# 14. ABSOLUTE STOP CONDITIONS

STOP immediately if you discover that:

- the business meaning of voting rights is not established
- Election-Only semantics are not established
- there is no authoritative constitutional source
- two voter authorities conflict
- the ownership of eligibility is ambiguous

Do NOT solve these yourself.

Record them as Product Owner / Architecture decisions.

---

# 15. NO IMPLEMENTATION

This commission is investigation only.

Forbidden:

- production code changes
- test changes
- fixture changes
- migrations
- schema changes
- refactoring
- changing ElectionConstitution.php
- changing ElectionMembership
- fixing tenant context
- fixing PBDIGIT-65
- fixing PBDIGIT-64
- creating a second election
- changing tickets to mark defects fixed

Only documentation of the investigation is permitted.

Do not reopen or reinterpret existing Product Owner decisions.

STOP after the report and await Product Owner review.
```

### Why I would use this prompt

The attached investigation has already established that the immediate PBDIGIT-65 problem is **not that the eligibility rule itself is wrong**. The membership row is valid, the rule is currently `role=voter && status!=removed`, and the wrong result is produced because tenant context makes the row invisible. 

But the more serious architectural finding is that the **business ownership of voter eligibility has not actually been established**. Claude found no constitutional definition of Election-Only, `ElectionMembership`, voter source, or the `role=voter/status=active` eligibility rule. 

And the existing `has_voters` predicate still combines the old `voters()` authority with the newer membership authority. 

So as a Principal Architect, I would **not let Claude "fix voter eligibility" yet**.

First establish the business model:

> **Who owns the decision that a person has the right to vote in a particular Election, what business event grants that right, and how that right interacts with the Election lifecycle and voting window?**

Only after that answer exists should we decide whether `ElectionMembership`, a dedicated Eligibility Policy, the Election Lifecycle, or another domain concept should own the behavior.
#
Yes — **we can continue the runtime verification before the Constitution is fully developed**, but I would draw a hard architectural boundary:

> **We may use the existing implementation as an observation surface; we must not promote a legacy column such as `is_eligible`, `can_vote`, `is_voter`, etc. into a new business authority merely to make the journey work.**

That distinction is especially important given what Claude just discovered: the repository currently has **two voter authorities inside `has_voters`**, and the constitutional ownership of eligibility has not yet been established. 

### What we can do now

We can continue the Election runtime journey using the **currently implemented behavior**, while recording exactly what happens.

For example:

```text
Election
   ↓
Election-Only mode
   ↓
import voter
   ↓
current implementation creates/updates ElectionMembership
   ↓
current eligibility mechanism
   ↓
voting window
   ↓
ballot access
   ↓
vote submission
   ↓
result
```

At each step Claude should report:

* what business behavior was intended;
* what the current system actually does;
* which mechanism made the decision;
* whether that mechanism is legacy, current, or unknown;
* whether the result is usable evidence for the future Constitution.

### What we should NOT do

I would **not** say:

> "Set `isEligible = true` so the voter can continue."

That would be dangerous.

It would transform an implementation field into an implicit constitutional rule:

```text
legacy column
     ↓
test fixture
     ↓
"voter is eligible"
     ↓
apparently successful election
```

We would then have no idea whether the business process actually works.

Instead, if the application currently exposes something like:

```php
$isEligible = ...
```

Claude should **observe why it became true or false**.

For example:

```text
isEligible = false

Why?
 └── ElectionMembership not found
      └── because tenant scope hid it
```

That is useful evidence.

If we deliberately manipulate a legacy field for an experiment, it must be labelled:

> **EXPERIMENTAL OVERRIDE — NOT BUSINESS AUTHORITY**

and the result must not be accepted as proof that the business journey works.

---

## There is an even better approach

I would let the runtime programme proceed in **two parallel tracks**.

### Track A — Business journey verification

Continue asking:

> Can the organisation actually conduct Election 2026?

Verify:

1. Election created.
2. Election Chief assigned.
3. Election-Only selected.
4. Five voters imported.
5. Voters become eligible according to **current implementation**.
6. Candidate established.
7. Voting opens.
8. Voters access ballot.
9. Voters vote.
10. Vote uniqueness enforced.
11. Results produced.

But every step gets an authority label.

### Track B — Constitutional discovery

At the same time:

```text
Observed runtime behavior
        ↓
What business rule does this appear to represent?
        ↓
Who currently owns that decision?
        ↓
Is that ownership constitutionally established?
        ↓
If not → OPEN BUSINESS DECISION
```

This means we **don't have to wait for the Constitution to be completely written** before learning how the existing product behaves.

---

## The important distinction

There are actually three possible states:

| State                            | Meaning                                                                                         |
| -------------------------------- | ----------------------------------------------------------------------------------------------- |
| **Constitutionally established** | We know the business rule and its owner                                                         |
| **Implementation-established**   | The current code does something consistently, but the business authority is not yet established |
| **Legacy/unknown**               | The mechanism exists but we don't know whether it still represents the intended business        |

Your `isEligible=true` example belongs in the **second or third category**, unless the Constitution eventually establishes it.

So I would tell Claude:

> **Do not block the runtime verification merely because the Constitution is incomplete. But do not use an unratified/legacy field as a substitute for the missing Constitution. Continue by observing the existing implementation and explicitly classify every decision as constitutional, implementation-defined, legacy, or unknown.**

That lets us move forward **without freezing the programme and without corrupting the architectural model**.

And importantly, the current PBDIGIT-65 evidence already demonstrates why this discipline matters: the existing eligibility rule may be perfectly reasonable, while infrastructure context causes it to produce the wrong answer. 

So **yes, continue the journey — but don't "make eligibility true." Measure what currently makes it true.**

#
Yes. The attached Claude result actually gives us a **much stronger architectural finding** than simply "there is a tenant bug."

The crucial discovery is this:

> There is currently **no authoritative Election Manifesto defining Election-Only mode or voter eligibility**. The code calls `ElectionConstitution.php` the single source of truth, but that constitution does not define those concepts. 

Even more importantly, the current constitutional predicate still combines **two voter authorities**:

```php
$election->voters()->withoutGlobalScopes()->exists()
||
$election->memberships()
    ->where('role', 'voter')
    ->where('status', 'active')
    ->exists()
```

So we should **not jump directly to fixing PBDIGIT-65**. We have uncovered a strategic DDD/domain-governance question first.

## The DDD question

Your business scenario is:

> Organisation owner creates Election 2026 → appoints himself Election Chief → imports 5 voters → grants them the right to vote → creates/approves candidate → opens voting → voters enter voting window → eligible voters cast votes → results are produced.

That is a **business process**, not merely a database workflow.

DDD therefore asks:

> **Which bounded context owns the business decision "this person has the right to participate in this election"?**

Not:

> Which Eloquent model currently contains `role = voter`?

Those are completely different questions.

---

# Who should own what?

I would establish this conceptual separation before any implementation work:

| Business decision                                                | Likely owner                                   | Responsibility                             |
| ---------------------------------------------------------------- | ---------------------------------------------- | ------------------------------------------ |
| What election phase are we in?                                   | **Election Domain / Lifecycle**                | nomination, voting, results                |
| Is there a valid candidate?                                      | **Election Domain**                            | candidacy/nomination rules                 |
| Who is registered for this election?                             | **Election Domain / Voter Participation**      | election-specific enrollment               |
| Does this person have voting rights in this election?            | **Election Domain / Eligibility Policy**       | eligibility decision                       |
| Is voting currently open?                                        | **Election Lifecycle**                         | temporal access                            |
| Is this actor allowed to administer voters?                      | **Application Authorization / Policy**         | chief/deputy permissions                   |
| How are voters stored?                                           | **Infrastructure/Persistence**                 | database representation                    |
| Which organisation is currently selected in browser?             | **Interface/Infrastructure**                   | tenant context                             |
| Does the browser's current organisation determine voting rights? | **No, unless explicitly made a business rule** | should not leak infrastructure into domain |

The important architectural statement is:

> **Tenant context must not silently become a business authority.**

Claude's runtime finding already demonstrates why. The voter was correctly enrolled, but a previous organisation page changed the tenant context and caused the voter to appear ineligible. 

---

# The bigger problem: "grant voting right" is not yet constitutionally defined

This is the part I would investigate next.

Your phrase:

> "after importing voters in election-only mode, the voters must be granted right to vote"

contains **two potentially different business events**:

```text
Import voter
      ↓
Register/enroll voter
      ↓
Grant voting eligibility/right
      ↓
Voting window opens
      ↓
Voter may cast ballot
```

But perhaps the business model actually says:

```text
Import voter
      ↓
Automatically becomes eligible
      ↓
Voting window opens
      ↓
May vote
```

Those are not equivalent.

We need the business owner to decide whether **importing a voter itself grants the right to vote**, or whether there is a separate authorization/activation step.

And this is exactly why I would now send Claude a **Principle Architect investigation prompt**, not a repair prompt.

---

# Prompt I would give Claude

```text
# PRINCIPAL ARCHITECT INVESTIGATION
## Election-Only → Voter Enrollment → Voting Right → Voting Access

This is a READ-ONLY DOMAIN / DDD / CONSTITUTIONAL INVESTIGATION.

DO NOT change production code.
DO NOT change tests.
DO NOT change fixtures.
DO NOT change the Election Constitution.
DO NOT create another election.
DO NOT repair PBDIGIT-65.
DO NOT repair PBDIGIT-64.
DO NOT choose a business rule on behalf of the Product Owner.

You may search and inspect the entire repository, source code, tests, ADRs,
discovery documents, architecture documents, database schema and existing
runtime evidence.

The objective is to establish the BUSINESS MODEL and DECISION OWNERSHIP
before any implementation decision is made.

---

# BUSINESS SCENARIO TO TRACE

The intended business journey is:

Organisation owner
    ↓
creates Election 2026
    ↓
becomes / appoints Election Chief
    ↓
chooses Election-Only voter management
    ↓
imports 5 people as voters
    ↓
those people receive voting rights / become eligible
    ↓
candidate is established
    ↓
voting window opens
    ↓
eligible voters access the ballot
    ↓
eligible voters cast votes
    ↓
votes are persisted
    ↓
results are produced

Do NOT assume that every arrow above is currently a valid business rule.

The purpose of this investigation is to discover which arrows are actually
defined by the authoritative business model and which are merely assumptions
from the current implementation.

---

# 1. FIND THE AUTHORITATIVE BUSINESS AUTHORITY

Establish what currently defines the Election business rules.

Investigate:

- Election Manifesto
- Election Constitution
- constitutional source code
- ADRs
- approved discovery decisions
- Product Owner decisions
- bounded-context definitions
- PBDIGIT-49
- PBDIGIT-48
- relevant prior Election discovery rounds

Do NOT assume that `ElectionConstitution.php` is the business Constitution
simply because the source code calls itself "THE SINGLE SOURCE OF TRUTH".

Determine:

A. Is there an actual ratified Election Manifesto?
B. Is there a constitutional specification outside source code?
C. Is `ElectionConstitution.php` the authoritative implementation of an
   externally defined Constitution?
D. Or is the PHP class currently serving as an implementation-defined
   pseudo-constitution?

If the answer is unclear, report:

    CONSTITUTIONAL AUTHORITY NOT ESTABLISHED

Do not fill the gap with engineering judgement.

---

# 2. DEFINE THE BUSINESS VOCABULARY

Establish the actual business meaning of each term:

- voter
- voter registration
- voter enrollment
- ElectionMembership
- voter eligibility
- voting right
- voting access
- voter authorization
- Election-Only
- Full Membership
- candidate
- approved candidate
- voting window

For every term classify:

    DOMAIN CONCEPT
    APPLICATION CONCEPT
    POLICY/AUTHORIZATION CONCEPT
    INFRASTRUCTURE CONCEPT
    INTERFACE CONCEPT
    UNDEFINED / AMBIGUOUS

Do not treat database table names as domain concepts automatically.

For example:

    election_memberships

does not by itself prove that "ElectionMembership" is the
constitutional business concept.

---

# 3. THE MOST IMPORTANT QUESTION:
# WHO OWNS THE DECISION "THIS PERSON MAY VOTE"?

Trace the decision from the business question downward:

    "May person X vote in Election Y?"

Determine which layer owns the decision:

    Domain
    Application
    Policy/Authorization
    Infrastructure
    Interface
    Unknown

Then identify the authoritative source.

Do NOT accept an answer such as:

    ElectionMembership.role = voter

unless you can establish that this field represents a business rule
rather than merely persistence state.

---

# 4. DISTINGUISH FOUR DIFFERENT DECISIONS

Do not collapse these:

### Decision A — Enrollment

    "Is this person registered/enrolled as a voter
     for this particular election?"

### Decision B — Voting right / eligibility

    "Does this person have the right to participate
     in this election?"

### Decision C — Voting-window access

    "Is the election currently in a phase where
     this eligible voter may access the ballot?"

### Decision D — Vote submission authorization

    "May this particular request submit a vote now?"

These may have different owners.

Establish whether they are currently:

    one decision
    or
    four separate decisions.

If they are separate, document the relationship.

---

# 5. INVESTIGATE ELECTION-ONLY MODE

Determine what Election-Only actually means.

Do not infer it from `uses_full_membership`,
`voter_source_strategy`, or database columns.

Establish whether Election-Only means:

    organisation membership is NOT required
    AND
    election-specific voter enrollment IS required

or something else.

Answer explicitly:

1. Who can be imported?
2. What does "import" mean in business terms?
3. Does import create enrollment?
4. Does import grant voting rights?
5. Is there a separate approval/activation step?
6. Can an Election-Only voter exist without organisation membership?
7. Can a person be enrolled but not eligible?
8. Can a person be eligible but voting still be unavailable because
   the voting window is closed?

If the repository does not establish these answers:

    BUSINESS RULE NOT ESTABLISHED

Do not invent them.

---

# 6. INVESTIGATE "GRANT THE RIGHT TO VOTE"

This phrase is intentionally under-specified.

Determine whether the current business model contains a distinct transition such as:

    imported
       ↓
    enrolled
       ↓
    approved/activated
       ↓
    eligible

or whether:

    imported
       ↓
    eligible

is the intended model.

Search for:

- voter activation
- voter approval
- eligibility status
- membership status
- voter status
- invitation acceptance
- ElectionMembership status
- voter role
- can_vote
- is_voter
- eligibility services/policies
- constitutional guards
- commands
- events

For every discovered mechanism state whether it is:

    authoritative
    derived
    procedural
    legacy
    persistence-only
    interface-only
    unknown

---

# 7. TRACE THE LEGACY MODEL

There is evidence that the system previously had database-based voter
management and multiple voter authorities.

Investigate:

- `Election::voters()`
- legacy voter tables
- `User.can_vote`
- `User.is_voter`
- ElectionMembership
- VoterSlug
- old voter eligibility services
- `has_voters`
- other voter-related predicates

Determine:

    Which authority was historically used?

    Which authority is intended to replace it?

    Has that replacement actually been constitutionally established?

    Is the old authority still participating in current business decisions?

Do not label something "legacy" merely because it is old.
Establish whether it has actually been superseded.

---

# 8. CRITICAL CURRENT FINDING: TWO AUTHORITIES

The current constitutional code contains:

    election.voters().withoutGlobalScopes()->exists()
    OR
    election.memberships()
        ->where('role','voter')
        ->where('status','active')
        ->exists()

Investigate this carefully.

Question:

    Does the business model intentionally define two valid voter
    authorities?

OR

    Is this an unfinished migration where two historical models have
    accidentally been combined?

Do not decide.

Report the evidence and the Product Owner decision required.

---

# 9. TENANT CONTEXT

Investigate whether tenant context is part of the business rule.

The runtime investigation already established:

    same voter
    same election
    same membership row
    different current organisation
    different eligibility result

Do not repeat the experiment unless necessary.

Instead determine architecturally:

    Is organisation context a DOMAIN INPUT to voter eligibility?

or

    Is organisation context merely infrastructure used to isolate data?

If it is infrastructure, determine whether it is currently leaking into
the domain decision.

Classify the finding as:

    DOMAIN RULE
    APPLICATION BOUNDARY
    INFRASTRUCTURE LEAK
    PERSISTENCE SCOPING
    INTERFACE/ROUTING
    UNKNOWN

Do not choose a repair location.

---

# 10. BUILD THE BUSINESS DECISION CHAIN

Produce this explicitly:

    Business invariant
          ↓
    Domain concept
          ↓
    Domain authority
          ↓
    Application capability
          ↓
    Authorization
          ↓
    HTTP/interface entry
          ↓
    Persistence
          ↓
    Test

For the specific question:

    "Can voter X vote in Election Y?"

fill every layer with the actual evidence.

If a layer has no established owner:

    OWNER NOT ESTABLISHED

---

# 11. BUILD THE RESPONSIBILITY MATRIX

Produce:

| Decision | Business meaning | Owning BC/layer | Authority | Current implementation | Status |
|---|---|---|---|---|---|
| Election-Only mode | | | | | |
| voter enrollment | | | | | |
| voting right | | | | | |
| voter eligibility | | | | | |
| voting window | | | | | |
| ballot access | | | | | |
| vote submission | | | | | |
| candidate eligibility | | | | | |
| result eligibility | | | | | |

Use only:

    ESTABLISHED
    PARTIAL
    CONTRADICTED
    UNDEFINED
    UNDETERMINED

---

# 12. DO NOT CONFUSE THESE THREE THINGS

This is a mandatory architectural rule:

    COVERAGE ≠ AUTHORITY ≠ COMPLETENESS

The fact that code contains a check does not prove:

    the business rule exists

The fact that a test passes does not prove:

    the correct authority owns the decision

The fact that a database row exists does not prove:

    the row represents the business concept

The fact that a constitutional predicate exists does not prove:

    it reflects the intended business Constitution.

---

# 13. OUTPUT

Produce one investigation report.

It must contain:

1. Authoritative business source
2. Business vocabulary
3. Election-Only semantics
4. Enrollment semantics
5. Voting-right semantics
6. Eligibility semantics
7. Voting-window semantics
8. Responsibility/ownership matrix
9. Legacy voter authority analysis
10. ElectionMembership analysis
11. Tenant-context analysis
12. Current constitutional contradictions
13. Open Product Owner decisions
14. Architectural questions
15. Evidence gaps

Every statement must be classified internally as:

    OBSERVED FACT
    INTERPRETATION
    HYPOTHESIS
    CONCLUSION

Where evidence is missing, say:

    MECHANISM NOT ESTABLISHED
    or
    BUSINESS RULE NOT ESTABLISHED

Never convert an engineering interpretation into a business rule.

---

# 14. ABSOLUTE STOP CONDITIONS

STOP immediately if you discover that:

- the business meaning of voting rights is not established
- Election-Only semantics are not established
- there is no authoritative constitutional source
- two voter authorities conflict
- the ownership of eligibility is ambiguous

Do NOT solve these yourself.

Record them as Product Owner / Architecture decisions.

---

# 15. NO IMPLEMENTATION

This commission is investigation only.

Forbidden:

- production code changes
- test changes
- fixture changes
- migrations
- schema changes
- refactoring
- changing ElectionConstitution.php
- changing ElectionMembership
- fixing tenant context
- fixing PBDIGIT-65
- fixing PBDIGIT-64
- creating a second election
- changing tickets to mark defects fixed

Only documentation of the investigation is permitted.

Do not reopen or reinterpret existing Product Owner decisions.

STOP after the report and await Product Owner review.
```

### Why I would use this prompt

The attached investigation has already established that the immediate PBDIGIT-65 problem is **not that the eligibility rule itself is wrong**. The membership row is valid, the rule is currently `role=voter && status!=removed`, and the wrong result is produced because tenant context makes the row invisible. 

But the more serious architectural finding is that the **business ownership of voter eligibility has not actually been established**. Claude found no constitutional definition of Election-Only, `ElectionMembership`, voter source, or the `role=voter/status=active` eligibility rule. 

And the existing `has_voters` predicate still combines the old `voters()` authority with the newer membership authority. 

So as a Principal Architect, I would **not let Claude "fix voter eligibility" yet**.

First establish the business model:

> **Who owns the decision that a person has the right to vote in a particular Election, what business event grants that right, and how that right interacts with the Election lifecycle and voting window?**

Only after that answer exists should we decide whether `ElectionMembership`, a dedicated Eligibility Policy, the Election Lifecycle, or another domain concept should own the behavior.
#
Yes — **we can continue the runtime verification before the Constitution is fully developed**, but I would draw a hard architectural boundary:

> **We may use the existing implementation as an observation surface; we must not promote a legacy column such as `is_eligible`, `can_vote`, `is_voter`, etc. into a new business authority merely to make the journey work.**

That distinction is especially important given what Claude just discovered: the repository currently has **two voter authorities inside `has_voters`**, and the constitutional ownership of eligibility has not yet been established. 

### What we can do now

We can continue the Election runtime journey using the **currently implemented behavior**, while recording exactly what happens.

For example:

```text
Election
   ↓
Election-Only mode
   ↓
import voter
   ↓
current implementation creates/updates ElectionMembership
   ↓
current eligibility mechanism
   ↓
voting window
   ↓
ballot access
   ↓
vote submission
   ↓
result
```

At each step Claude should report:

* what business behavior was intended;
* what the current system actually does;
* which mechanism made the decision;
* whether that mechanism is legacy, current, or unknown;
* whether the result is usable evidence for the future Constitution.

### What we should NOT do

I would **not** say:

> "Set `isEligible = true` so the voter can continue."

That would be dangerous.

It would transform an implementation field into an implicit constitutional rule:

```text
legacy column
     ↓
test fixture
     ↓
"voter is eligible"
     ↓
apparently successful election
```

We would then have no idea whether the business process actually works.

Instead, if the application currently exposes something like:

```php
$isEligible = ...
```

Claude should **observe why it became true or false**.

For example:

```text
isEligible = false

Why?
 └── ElectionMembership not found
      └── because tenant scope hid it
```

That is useful evidence.

If we deliberately manipulate a legacy field for an experiment, it must be labelled:

> **EXPERIMENTAL OVERRIDE — NOT BUSINESS AUTHORITY**

and the result must not be accepted as proof that the business journey works.

---

## There is an even better approach

I would let the runtime programme proceed in **two parallel tracks**.

### Track A — Business journey verification

Continue asking:

> Can the organisation actually conduct Election 2026?

Verify:

1. Election created.
2. Election Chief assigned.
3. Election-Only selected.
4. Five voters imported.
5. Voters become eligible according to **current implementation**.
6. Candidate established.
7. Voting opens.
8. Voters access ballot.
9. Voters vote.
10. Vote uniqueness enforced.
11. Results produced.

But every step gets an authority label.

### Track B — Constitutional discovery

At the same time:

```text
Observed runtime behavior
        ↓
What business rule does this appear to represent?
        ↓
Who currently owns that decision?
        ↓
Is that ownership constitutionally established?
        ↓
If not → OPEN BUSINESS DECISION
```

This means we **don't have to wait for the Constitution to be completely written** before learning how the existing product behaves.

---

## The important distinction

There are actually three possible states:

| State                            | Meaning                                                                                         |
| -------------------------------- | ----------------------------------------------------------------------------------------------- |
| **Constitutionally established** | We know the business rule and its owner                                                         |
| **Implementation-established**   | The current code does something consistently, but the business authority is not yet established |
| **Legacy/unknown**               | The mechanism exists but we don't know whether it still represents the intended business        |

Your `isEligible=true` example belongs in the **second or third category**, unless the Constitution eventually establishes it.

So I would tell Claude:

> **Do not block the runtime verification merely because the Constitution is incomplete. But do not use an unratified/legacy field as a substitute for the missing Constitution. Continue by observing the existing implementation and explicitly classify every decision as constitutional, implementation-defined, legacy, or unknown.**

That lets us move forward **without freezing the programme and without corrupting the architectural model**.

And importantly, the current PBDIGIT-65 evidence already demonstrates why this discipline matters: the existing eligibility rule may be perfectly reasonable, while infrastructure context causes it to produce the wrong answer. 

So **yes, continue the journey — but don't "make eligibility true." Measure what currently makes it true.**
#
Yes — **that is a very important distinction**, and I think your instinct is correct.

You are not asking Claude to prematurely finish the Constitution. You are asking whether there is a **safe transitional architecture** that preserves the previously working Election-Only behavior while the constitutional/domain discovery is still incomplete.

The key is: **do not resurrect the legacy code as the authority.** Instead, discover whether the legacy behavior can be treated as a **temporary compatibility mechanism whose semantics are explicitly bounded**.

### The architectural question

We effectively have three things:

```text
OLD
Legacy Election-Only voter management
        ↓
worked operationally
        ↓
unknown / partially understood business semantics


CURRENT
New ElectionLifecycle + ElectionMembership + capabilities
        ↓
some parts are authoritative
some parts are not yet constitutionally defined


TARGET
Election Manifesto / Constitution
        ↓
explicitly defines
- Election-Only
- Membership mode
- voter registration
- eligibility
- voting entitlement
- ballot access
```

The dangerous move would be:

```text
Old legacy field
      ↓
"Let's make it true again"
      ↓
new authority
```

The potentially sound move is:

```text
Legacy behavior
      ↓
investigate exactly what business behavior it represented
      ↓
define a bounded compatibility mechanism
      ↓
new architecture consumes it through a controlled boundary
      ↓
later replace compatibility mechanism with constitutional authority
```

That is a **migration strategy**, not a domain decision.

---

# What I would tell Claude

I would give Claude a new, very explicit investigation prompt:

```text
# Election-Only Transitional Compatibility Investigation

## Role

Act as Senior Principal Architect, DDD Architect and Migration Architect.

We are currently discovering the constitutional/domain model for:

1. Election-Only voter management
2. Membership-based voter management
3. Voter eligibility
4. Voting entitlement
5. Ballot access

The constitutional model is NOT yet complete.

The purpose of this investigation is therefore NOT to prematurely design or implement the final Constitution.

The purpose is to determine whether the previously working legacy Election-Only behavior can be preserved through a SAFE, EXPLICIT, TEMPORARY COMPATIBILITY BOUNDARY while constitutional discovery continues.

---

# 1. ABSOLUTE RULE

READ-ONLY INVESTIGATION ONLY.

Do not modify:

- production code
- tests
- migrations
- database schema
- Constitution / Manifesto
- lifecycle
- policies
- models
- controllers
- services
- fixtures

Do not repair anything.

Do not restore legacy fields as authoritative.

Do not invent constitutional rules.

Do not declare a final domain model.

---

# 2. BUSINESS QUESTION

The historical system supported Election-Only elections operationally.

The current system has introduced newer concepts such as:

- ElectionLifecycle
- ElectionMembership
- VoterSourceStrategy
- capabilities
- voter eligibility services
- voting access

But the complete constitutional meaning of Election-Only and membership-based eligibility is still under discovery.

The business question is:

> Can we preserve the previously working Election-Only voter-to-voting journey safely until the constitutional model is completely discovered?

More precisely:

> What was the LEGACY business behavior, what mechanism implemented it, which parts of that behavior remain valid under the current model, and is there a bounded compatibility boundary that could preserve the behavior without making legacy state authoritative?

---

# 3. FIRST PRINCIPLE

Do NOT start with:

"Which legacy column should we set?"

Start with:

"What business capability did the legacy mechanism provide?"

For example, distinguish:

- identifying a voter
- registering a voter for an election
- determining voter eligibility
- granting voting entitlement
- granting ballot access
- determining whether voting is currently open

These are different decisions.

---

# 4. RECONSTRUCT THE LEGACY JOURNEY

Find the previous working Election-Only implementation.

Trace it end-to-end:

organisation
→ election
→ Election-Only mode
→ voter import
→ voter registration
→ voter eligibility
→ voting entitlement
→ ballot access
→ vote submission
→ results

For every step record:

| Step | Legacy mechanism | Business behavior | Data used | Writer | Reader | Current equivalent |
|---|---|---|---|---|---|---|

Do not assume that the legacy mechanism was wrong merely because it is legacy.

Likewise, do not assume that it was correct merely because it worked.

---

# 5. IDENTIFY WHAT "WORKING" ACTUALLY MEANT

Determine exactly what the previous implementation allowed.

For example:

Did importing a voter:

A. merely create a voter record?

B. create ElectionMembership?

C. make the person eligible?

D. grant voting entitlement?

E. make the ballot accessible?

F. do several of these simultaneously?

Do not collapse these into "isEligible".

Establish the actual behavior from code, tests, historical fixtures, migrations, and runtime evidence.

---

# 6. COMPARE LEGACY WITH CURRENT MODEL

Create a mapping:

| Legacy concept | Current concept | Relationship |
|---|---|---|
| legacy voter record | ElectionMembership | equivalent / partial / unknown |
| isEligible | current eligibility calculation | equivalent / different / unknown |
| can_vote | current capability | equivalent / different / unknown |
| can_vote_now | ballot access | equivalent / different / unknown |
| Election-Only voter source | VoterSourceStrategy | equivalent / partial / unknown |

Do NOT decide that two things are equivalent merely because their names look similar.

---

# 7. FIND THE CURRENT AUTHORITATIVE CHAIN

Determine what currently happens after voter import.

Trace:

Election-Only mode
→ voter source
→ membership
→ eligibility
→ capability
→ authorization
→ ballot access

For each step identify the actual decision owner.

Use:

Domain
Application
Policy/Authorization
Infrastructure
Interface/Projection
Unknown

---

# 8. SPECIAL QUESTION: WHAT DOES ELECTION MEMBERSHIP MEAN?

This is critical.

Determine whether ElectionMembership currently means:

1. "this person is known to this election";
2. "this person is an eligible voter";
3. "this person has voting entitlement";
4. "this person may access the ballot";
5. something else;
6. not yet defined.

Do not invent the answer.

If the code uses ElectionMembership as an eligibility source but the Constitution does not define that meaning, record:

> Implementation behavior established; constitutional meaning not yet established.

---

# 9. SPECIAL QUESTION: LEGACY ELIGIBILITY FIELDS

Investigate fields such as:

- isEligible
- can_vote
- can_vote_now
- is_voter

For each:

1. who writes it?
2. who reads it?
3. when is it calculated?
4. is it persisted?
5. is it authoritative?
6. is it derived?
7. is it a projection?
8. is it merely legacy residue?
9. what business behavior did it historically enable?

Do not modify any of them.

---

# 10. FIND A POSSIBLE TRANSITION BOUNDARY

This is the most important part.

Investigate whether a compatibility boundary can exist like:

Legacy Election-Only registration
        ↓
Compatibility Adapter / Translation
        ↓
Current ElectionMembership / eligibility representation
        ↓
Current voting authorization
        ↓
Current ballot access

The objective is NOT to preserve the old field.

The objective is to preserve the old BUSINESS BEHAVIOR while translating it into the current model.

Ask:

> Can the old Election-Only voter registration semantics be translated into the current authoritative representation without introducing a new domain rule?

If yes, describe the boundary.

If no, explain exactly which missing business decision prevents it.

---

# 11. THREE POSSIBLE OUTCOMES

Classify the result into one of these:

### A — Safe compatibility path

The legacy behavior has a clear business meaning and can be translated into an already existing current representation.

Example:

legacy Election-Only voter registration
→ ElectionMembership
→ current eligibility
→ current ballot authorization

No legacy field becomes authoritative.

### B — Compatibility possible but business meaning incomplete

The technical translation is possible, but one or more business decisions still require Product Owner / Constitution clarification.

Identify precisely which decisions.

### C — Unsafe compatibility

The old behavior depends on semantics that are no longer represented or cannot be mapped without inventing a business rule.

Then STOP.

Do not propose implementation.

---

# 12. DO NOT CONFUSE TRANSITION WITH FINAL ARCHITECTURE

A compatibility mechanism must NOT become the permanent architecture merely because it allows the system to work.

Explicitly distinguish:

CURRENT
what exists now

TRANSITIONAL
what could safely bridge old and new

TARGET
what the completed Constitution should define

These must remain separate.

---

# 13. BUSINESS VALUE FIRST

The business capability we are trying to preserve is:

> An organisation running an Election-Only election must be able to register its intended voters and, once they satisfy the authoritative election rules and the voting window is open, those voters must be able to access and submit a ballot.

Do not reduce this to:

"make isEligible=true."

That is an implementation mechanism, not the business requirement.

---

# 14. VOTING WINDOW IS A SEPARATE DECISION

Do not confuse:

Voter eligibility

with:

Voting availability.

A person may be eligible but unable to vote because:

- voting has not opened;
- voting has closed;
- ballot access conditions are not satisfied.

Therefore establish:

Eligibility authority
AND
Voting-window authority
AND
Ballot-access authority

separately.

---

# 15. ELECTION-ONLY VS MEMBERSHIP MODE

Investigate whether these are actually two different business concepts:

### Election-Only

Voter participation is established specifically for an election.

### Membership-based

Voter participation derives from organisation membership.

Determine whether the current VoterSourceStrategy already represents this distinction.

If it does, investigate whether the missing part is simply:

> the downstream voter-entitlement/eligibility contract.

Do not create a new abstraction if an existing one already expresses it.

---

# 16. TENANT CONTEXT MUST BE INCLUDED

The previous runtime investigation established that ElectionMembership can exist while being invisible because tenant context affects the global scope.

Therefore explicitly verify:

organisation
→ TenantContext
→ ElectionMembership
→ eligibility query
→ voter capability
→ ballot access

A technically correct membership row is not sufficient evidence if the request cannot see it.

---

# 17. REQUIRED REPORT

Produce:

## 1. Legacy Business Journey

What the old system actually did.

## 2. Current Business Journey

What the new system actually does.

## 3. Semantic Mapping

Legacy concept → current concept.

## 4. Decision Ownership

Who currently owns:

- voter registration
- eligibility
- voting entitlement
- ballot access
- voting window

## 5. Constitutional Coverage

For each decision:

CONSTITUTIONALLY DEFINED
IMPLEMENTED BUT NOT CONSTITUTIONALLY DEFINED
LEGACY
UNKNOWN

## 6. Compatibility Assessment

A / B / C.

## 7. Safe Transitional Boundary

If A or B, describe the smallest possible boundary.

## 8. Remaining Business Decisions

Only decisions that require Product Owner / Constitutional authority.

## 9. Runtime Consequence

Explain whether the existing Election-Only journey can continue safely before constitutional completion.

## 10. Recommendation

Recommend the next discovery step.

Do NOT implement.

---

# 18. FINAL PRINCIPLE

The governing principle is:

> We may preserve existing business capability during architectural migration, but we must not preserve an obsolete implementation mechanism merely because it previously made the tests pass.

And:

> A transitional adapter may translate an old representation into the current authoritative model; it must not become a second business authority.

And:

> Until the Constitution is complete, explicitly distinguish "what the product currently does", "what the business has decided", and "what we believe the final architecture should be."

---

# STOP CONDITION

End with exactly one of:

SAFE TRANSITION IDENTIFIED

or

TRANSITION POSSIBLE — BUSINESS DECISIONS REQUIRED

or

NO SAFE TRANSITION IDENTIFIED

Then stop.

NO CODE CHANGES.
NO TEST CHANGES.
NO DATABASE REPAIRS.
NO LEGACY AUTHORITY RESTORED.
```

### Why I think this is the right next step

Your concern is legitimate: **if Election-Only worked before, we should not casually interpret the current breakage as evidence that the old behavior was conceptually wrong.**

The investigation should answer whether the migration has created a **semantic gap**:

```text
Old system
"import voter"
      ↓
voter could participate


New system
"import voter"
      ↓
ElectionMembership exists
      ↓
??? 
      ↓
isEligible = false
      ↓
cannot vote
```

That `???` is exactly what needs to be discovered.

And importantly, **we don't need to wait for the entire Constitution to be finished before understanding the transition path**. We can establish whether an existing business meaning can safely be mapped into the current model.

If the answer is, for example:

```text
Election-Only voter import
        ↓
ElectionMembership
        ↓
eligibility
        ↓
ballot access
```

and that semantic chain is already supported by existing business behavior, then a **compatibility bridge** may be appropriate.

But if the investigation discovers:

```text
ElectionMembership
      ≠
voting entitlement
```

and nobody has yet decided what grants voting entitlement, then Claude must **not invent that rule** merely to get the Election 2026 experiment working.

That distinction is the DDD boundary I would protect most strongly.
#
Claude's result is actually **useful, but it did not complete the commission you gave it**. The most important thing is not to treat its heading

> **ELECTION RUNTIME / ELIGIBILITY DISCOVERY COMPLETE**

as meaning the entire investigation is complete. Claude itself immediately qualifies that statement: it completed only **one new section**, the Legacy Authority Register.

### What Claude actually established

There are four valuable findings.

#### 1. The old `can_vote` / `is_voter` model is genuinely legacy

Claude found that:

* `can_vote`
* `is_voter`
* `can_vote_now`
* `is_eligible`

do **not exist** on `users` or `election_memberships`.

Yet production console/debug code still references `can_vote` and `is_voter`.

That is strong evidence of **legacy consumers**, but it does **not** yet tell us what the replacement business rule is.

This distinction is important:

```text
Legacy field exists in old code
        ≠
Legacy field represents the current business authority
        ≠
Current system has completely replaced its business meaning
```

Claude correctly did not repair those references.

---

#### 2. `isEligible` is a projection, not the authority

This is probably the most important finding for your current question.

Claude says `isEligible` is **computed per request**, rather than persisted.

Therefore:

```text
isEligible
    ↓
computed projection
```

not:

```text
isEligible
    ↓
authoritative voter entitlement
```

And the previous PBDIGIT-65 observation that the value can change simply because the session organisation changes is especially significant.

So we should **not** solve the current problem by saying:

> "Just make `isEligible = true`."

That would be treating a projection as the business authority.

---

#### 3. Election-Only is real behaviour, but its constitutional ownership is missing

This is the finding I think you were looking for.

Claude established this current chain:

```text
organisation flag
        ↓
Election.voter_source_strategy snapshot
        ↓
importElectionOnly()
        ↓
User + ElectionMembership
        ↓
Member context bypassed
```

And importantly:

> **Election-Only is implementation-established but not constitutionally established.**

That does **not** mean Election-Only should be removed.

It means:

> **The product already has a business behaviour, but the constitutional/domain contract describing who owns that behaviour has not yet been established.**

That's exactly the distinction we need.

---

#### 4. `has_voted` is potentially another semantic duplication

Claude found `has_voted` in:

* `election_memberships`
* `codes`

But it did **not** establish whether these represent the same business fact, which one is authoritative, or whether one is merely a projection.

That investigation should remain open.

---

# What Claude did NOT establish

This is where I would push back on the "complete" wording.

It did **not** yet answer the most important question:

> **After an Election-Only voter is imported, what exact business decision grants that person the right to vote?**

We still need the chain:

```text
Election-Only voter import
        ↓
ElectionMembership
        ↓
eligibility
        ↓
voting entitlement
        ↓
ballot access
        ↓
vote submission
```

And for each arrow:

```text
Who owns this decision?
What is the authoritative representation?
What does the Constitution say?
What does the current code do?
```

That is still missing.

---

# And there is an important DDD implication

I would **not** immediately conclude:

> "ElectionMembership is the replacement for `is_voter`."

We need evidence first.

It could turn out to be:

```text
ElectionMembership
= registration/participation relationship
```

while:

```text
Eligibility
= separate domain decision
```

and:

```text
Voting entitlement
= another decision
```

and:

```text
Ballot access
= lifecycle + eligibility + authorization
```

Or the business may intentionally define ElectionMembership itself as the voter entitlement.

**We don't know yet.**

That is precisely why the next investigation needs to trace the whole chain rather than repair `isEligible`.

---

# I would therefore give Claude a much narrower next prompt

Claude's context is nearly exhausted, so don't give it another huge 18-section commission.

Give it **one investigation only**:

````text
# NEXT DISCOVERY — ELECTION-ONLY VOTER ENTITLEMENT CHAIN

## Role

Act as Senior Principal Architect / DDD Architect.

Continue the Election Runtime Verification programme.

This is READ-ONLY DISCOVERY.

NO code changes.
NO test changes.
NO fixture changes.
NO database repairs.
NO legacy-field restoration.
NO constitutional decisions.

---

## Objective

The previous investigation established:

1. Election-Only is an existing implementation behaviour.
2. Election-Only is NOT yet explicitly defined by the Election Constitution.
3. `isEligible` is a request-time projection, not persisted authority.
4. Legacy `can_vote`, `is_voter`, `can_vote_now`, and `is_eligible` columns are absent from the current relevant schema, although legacy code still references some of them.
5. Election-Only voter import creates Users and ElectionMemberships.
6. `has_voted` exists in more than one persistence location, but its ownership is not yet established.

Do NOT rediscover these findings. Carry them forward.

---

# PRIMARY QUESTION

Determine:

> After a voter is imported into an Election-Only election, what CURRENT BUSINESS DECISION gives that person the right to vote?

Trace one complete voter through:

Election-Only import
→ ElectionMembership
→ eligibility
→ voting entitlement
→ ballot access
→ vote submission authorization

For each boundary identify:

- business meaning;
- decision owner;
- authoritative source;
- application mechanism;
- authorization mechanism;
- persistence representation;
- UI projection;
- constitutional definition;
- evidence.

---

# IMPORTANT DDD DISTINCTION

Do NOT assume these are the same:

- voter registration;
- membership;
- eligibility;
- voting entitlement;
- ballot access;
- voting-window availability.

Determine whether the current model treats them as separate decisions or one decision.

---

# REQUIRED INVESTIGATION

## 1. Import

Trace `importElectionOnly()` completely.

Establish exactly what it creates.

Especially determine:

- User;
- ElectionMembership;
- voter source;
- roles;
- status;
- invitation/credential;
- any eligibility state.

---

## 2. Eligibility

Trace the actual eligibility calculation.

Find:

- service;
- policy;
- query;
- lifecycle capability;
- controller;
- API response.

Establish exactly why `isEligible` becomes true or false.

Do not treat `isEligible` itself as authority.

---

## 3. Voting entitlement

Find the exact server-side decision that permits a voter to vote.

Do not stop at UI capability.

Find the actual enforcement boundary.

Answer:

> What condition must be true for the server to accept a ballot from this person?

---

## 4. Ballot access

Determine whether ballot access requires:

- ElectionMembership;
- eligibility;
- capability;
- invitation;
- code;
- IP;
- device;
- voting lifecycle;
- some combination.

Map each condition to its owner.

---

## 5. Election-Only versus membership mode

Compare:

Election-Only voter

versus

organisation-membership voter.

Determine precisely where their paths converge.

The key question is:

> Do both modes eventually enter the same authoritative voter-entitlement model?

If yes, identify the convergence point.

If no, identify the divergence.

---

# 6. CONSTITUTIONAL STATUS

For every decision found, classify:

A. Explicitly constitutional

B. Implemented business behaviour but not constitutionally defined

C. Legacy implementation

D. Infrastructure concern

E. Interface/projection

F. Unknown

Do NOT invent constitutional meaning.

---

# 7. LEGACY TRANSITION QUESTION

Only after the current chain is established, answer:

> Can the historical Election-Only behaviour be preserved through the CURRENT model without restoring legacy fields as authority?

Possible result:

### SAFE TRANSITION

Legacy registration can be translated into an existing current authoritative representation.

### TRANSITION REQUIRES BUSINESS DECISION

Technical path exists, but the business meaning of eligibility/entitlement has not yet been decided.

### NO SAFE TRANSITION

The old behaviour depends on a business rule that the current model does not represent.

Do not implement any of these.

---

# 8. TENANT CONTEXT

Explicitly include tenant context in the trace.

We already know from PBDIGIT-65 that a valid ElectionMembership can become invisible because of organisation context.

Therefore establish:

User
→ current organisation
→ TenantContext
→ ElectionMembership scope
→ eligibility query
→ voting authorization.

Do not classify a membership as invalid merely because the current request cannot see it.

---

# 9. RUNTIME

Do NOT create another election.

Do NOT create voters.

Do NOT vote.

Use existing runtime evidence and read-only inspection.

If additional runtime execution is necessary, stop and report exactly what experiment is required before executing it.

---

# FINAL OUTPUT

Produce ONLY:

## A. Current voter-entitlement chain

```text
Import
→ Membership
→ Eligibility
→ Entitlement
→ Ballot access
→ Vote
````

## B. Decision ownership table

| Decision | Authority | Owner | Evidence | Constitutional? |

## C. Election-Only vs Membership convergence

Show where the two paths converge/diverge.

## D. Legacy transition assessment

SAFE TRANSITION /
TRANSITION REQUIRES BUSINESS DECISION /
NO SAFE TRANSITION

with evidence.

## E. Open business decisions

Only decisions that genuinely require Product Owner / constitutional authority.

## F. Next discovery

One precise next step.

STOP.

NO IMPLEMENTATION.

````

### My architectural expectation

I suspect the most valuable outcome will be something like:

```text
Election-Only
     │
     │ import
     ▼
ElectionMembership
     │
     │ current eligibility calculation
     ▼
Eligible voter
     │
     │ voting window + access rules
     ▼
Ballot access
     │
     ▼
Vote
````

If that is what the code actually proves, then **you may already have most of the transition architecture**.

The missing part may not be a missing database column at all. It may be that the **Manifesto has not yet declared the semantic relationship between ElectionMembership, voter eligibility, and voting entitlement**.

That is a much healthier problem to have than "we need to restore `isEligible=true`."

And it gives you a clean path:

**discover → establish business ownership → define constitutional rule → then decide whether any compatibility adapter is needed.**

Do not let Claude jump directly from the legacy fields to implementation. The next investigation should establish that semantic chain first.
#
This is a **very important result**, and I think the investigation has now reached a much clearer architectural position.

Claude's latest finding validates the transition hypothesis **technically**, but it also identifies exactly what is still missing before we should call the model constitutionally complete.

## What has now been established

The critical convergence is:

```text
                    ┌──────────────────────┐
                    │   Election-Only      │
                    │   voter import       │
                    └──────────┬───────────┘
                               │
                               │ creates
                               ▼
                    ┌──────────────────────┐
                    │ ElectionMembership   │
                    └──────────┬───────────┘
                               │
                    ┌──────────┴───────────┐
                    │                      │
                    ▼                      ▼
             Eligibility             Vote enforcement
                                      ElectionVotingController
```

And full-membership mode reaches the **same `ElectionMembership` representation**.

So:

```text
Full Membership
       │
       ▼
ElectionMembership
       ▲
       │
Election-Only
```

That is excellent evidence for a **single downstream voter model**.

### This is the key transition finding

Election-Only does **not** need to remain dependent on the old:

* `is_voter`
* `can_vote`
* `can_vote_now`
* `is_eligible`

columns.

The current schema doesn't even contain those columns in the relevant places.

Instead, Election-Only can already enter the current model through:

> **ElectionMembership**

That means the migration problem is considerably smaller than it initially appeared.

---

# But there is an important DDD caveat

Claude says:

> "ElectionMembership is the voter-entitlement model in the current implementation."

I would slightly tighten that wording.

**Implementation-wise:** yes, the evidence strongly supports it.

**Domain-wise:** not yet proven.

Because these are potentially distinct concepts:

```text
Person registered for election
        ≠
Person eligible to vote
        ≠
Person entitled to vote
        ≠
Person currently allowed to access ballot
```

The current implementation may intentionally collapse these into one ElectionMembership row.

But that is a **business decision**, not something we should infer from the database.

So the proper architectural statement is:

> **ElectionMembership is currently the authoritative enforcement representation for voter participation in the implementation. Whether the business domain intentionally defines registration, eligibility and voting entitlement as one concept remains a constitutional decision.**

That is much safer.

---

# The most important finding: enforcement

This is even more significant than the UI `isEligible` finding.

Claude established that the actual enforcement occurs in:

> `ElectionVotingController::start()`

and it checks:

1. `ElectionMembership`
2. `has_voted`
3. lifecycle `canVote()`
4. IP restriction

Therefore:

```text
isEligible
```

is **not the ultimate authority for voting**.

The real enforcement chain is closer to:

```text
ElectionMembership
       +
ElectionLifecycle
       +
vote uniqueness
       +
access restrictions
       ↓
Ballot access
```

That is exactly the kind of separation we want to expose through the DDD discovery.

---

# This also changes how I would interpret PBDIGIT-65

The tenant-context problem is now clearly more serious.

It is not merely:

```text
UI says:
"You are not eligible."
```

It is:

```text
TenantContext
      ↓
ElectionMembership global scope
      ↓
membership cannot be found
      ↓
ElectionVotingController::start()
      ↓
ballot refused
```

Therefore it is a **server-side authorization/enforcement defect**, not merely a projection/UI defect.

That's a strong architectural finding.

And Claude was right not to repair it during this discovery.

---

# What I would do next

I agree with Claude that `has_voted` should be investigated next.

But I would make the question more precise.

The question is **not simply**:

> "Why are there two `has_voted` fields?"

The DDD question is:

> **What business fact does `has_voted` represent, and which component owns that fact?**

Investigate:

```text
ElectionMembership.has_voted
        │
        ├── writers
        ├── readers
        ├── transaction boundary
        └── business meaning

codes.has_voted
        │
        ├── writers
        ├── readers
        ├── transaction boundary
        └── business meaning
```

Then determine whether they represent:

### A. The same business fact

If so, there is potentially a **duplicated representation / consistency problem**.

### B. Different business facts

For example:

```text
ElectionMembership.has_voted
= voter has exercised voting right

codes.has_voted
= voting credential has been consumed
```

That could be perfectly legitimate.

### C. One is legacy

Then determine which is current authority.

### D. Meaning cannot be established

Then leave it **Unknown**.

Don't "clean it up" yet.

---

# Where we are now

I would record the Election discovery status like this:

| Area                                                       | Status                   |
| ---------------------------------------------------------- | ------------------------ |
| Election-Only exists in implementation                     | ✅ Proven                 |
| Election-Only voter import                                 | ✅ Proven                 |
| Full-membership voter path                                 | ✅ Proven                 |
| Both paths converge on ElectionMembership                  | ✅ Proven                 |
| Legacy `isEligible` as authority                           | ❌ disproven              |
| Legacy `is_voter` / `can_vote` as current schema authority | ❌ disproven              |
| Server-side ballot enforcement                             | ✅ identified             |
| ElectionMembership participates in enforcement             | ✅ proven                 |
| Tenant-context defect                                      | ✅ proven                 |
| Constitutional definition of Election-Only                 | ❌ missing                |
| Constitutional meaning of ElectionMembership               | ❌ not established        |
| Eligibility vs entitlement semantics                       | ⚠️ unresolved            |
| Ballot access semantics                                    | ⚠️ partially established |
| `has_voted` ownership                                      | ⚠️ unresolved            |
| Full runtime vote journey                                  | ❌ not yet verified       |

So I would **not** say:

> "Election discovery is complete."

I'd say:

> **The core implementation convergence has been established. The remaining work is primarily business/constitutional clarification and targeted verification, rather than discovering an alternative technical voter model.**

That is a much stronger position.

---

## And this answers your earlier transition question

You can probably proceed with the **current ElectionMembership-based implementation** while the Constitution is being completed, provided we explicitly treat it as:

> **the current implementation authority, not yet a fully ratified constitutional definition.**

In other words:

```text
              NOW
               │
               ▼
       ElectionMembership
       current enforcement
               │
               │
       ┌───────┴────────┐
       │                │
Election-Only      Membership mode
       │                │
       └───────┬────────┘
               ▼
       SAME VOTER MODEL
               │
               ▼
        ballot enforcement
               │
               ▼
          vote process
```

Then later:

```text
Current implementation
        ↓
Business decision
        ↓
Election Manifesto / Constitution
        ↓
Ratified domain meaning
        ↓
Implementation aligned to it
```

That avoids both extremes:

* **Don't resurrect the legacy columns.**
* **Don't pretend the Constitution is already complete.**

That is the proper DDD migration posture.

### One thing I would *not* let Claude do yet

I would not let it "fix" the Election-Only path merely because it now knows `ElectionMembership` is the convergence point.

The next step should be **`has_voted` ownership + exact ballot entitlement semantics**, then we can decide whether the existing implementation is already sufficient for the transitional period or whether a narrowly bounded compatibility change is actually required.
#
This is a **very useful result**, and I would **not let Claude classify the whole one-vote mechanism as broken yet**.

The current evidence establishes something narrower and important:

### What is now proven

```text
ElectionMembership
    └── has_voted
         └── READ by ElectionVotingController::start()
```

but:

```text
ElectionMembership::markAsVoted()
    └── no live caller found
```

Meanwhile the actual execution path is:

```text
ElectionVotingController::start()
        │
        ├── checks ElectionMembership entitlement
        │
        ├── reads election_memberships.has_voted
        │
        └── creates/refreshes VoterSlug
                    │
                    ▼
             Code / Vote flow
                    │
                    └── writes legacy voting state
```

So **C is justified for the specific question Claude was asked**: the current enforcement path still *consults* a legacy `has_voted` representation.

But there is an important distinction:

> **We have not yet proven that the legacy mechanism is the mechanism that actually prevents a second vote.**

Claude itself correctly says that.

That is the next investigation.

---

# What I would do next

I would authorize **one very narrow read-only investigation**:

> Trace the Code/Vote flow from the moment the voter receives the code until the vote is persisted, and determine exactly what prevents a second vote.

Do **not** create another election yet.
Do **not** change code.
Do **not** remove or bypass `has_voted`.

The question is:

> **What is the authoritative enforcement mechanism for the business invariant "one voter may cast at most one vote per election"?**

That is a DDD question, not a legacy-column question.

### Give Claude this prompt

```text
# Targeted Investigation — One-Vote Invariant / Code Flow

## Role

Act as Senior Principal Architect and DDD Architect.

READ-ONLY INVESTIGATION ONLY.

No production code changes.
No test changes.
No fixtures changes.
No migrations.
No database writes.
No new election.
No vote casting.
No deletion or modification of legacy fields.

You may inspect the entire repository, call graph, routes, controllers,
models, services, policies, database schema, tests, configuration and
documentation.

---

# Established evidence

The previous investigation established:

ElectionVotingController::start()

    1. reads ElectionMembership
    2. checks election_memberships.has_voted
    3. creates or refreshes a VoterSlug
    4. redirects into the Code/Vote flow

The current path is therefore:

ElectionVotingController::start()
    → VoterSlug
    → Code
    → VoteController / vote persistence

Also established:

- ElectionMembership is the current representation of voter entitlement.
- ElectionMembership::markAsVoted() has no live caller identified.
- election_memberships.has_voted is therefore not currently proven to
  be the mechanism that records the vote.
- The Code/VoterSlug flow writes the legacy voting state.
- We have NOT yet established whether that legacy state actually prevents
  a second vote.
- No vote has been cast in this programme.

Do not revisit these findings unless new evidence directly contradicts them.

---

# Business invariant

Investigate exactly this invariant:

> A voter may cast at most one valid vote in an election.

Do not start from database columns.

Start from the business invariant and trace how the running system enforces it.

---

# Investigation

## 1. Trace the complete Code/Vote path

Starting from the redirect produced by:

ElectionVotingController::start()

trace:

VoterSlug
→ Code
→ route
→ controller
→ service/use case
→ validation
→ persistence
→ post-vote state

Identify every decision that can prevent a second vote.

---

## 2. Find the actual enforcement point

Answer precisely:

> What prevents the same voter from successfully submitting a second
> valid vote?

Look for:

- code consumption;
- code invalidation;
- VoterSlug state;
- `can_vote_now`;
- expiry;
- `has_voted`;
- vote existence checks;
- unique constraints;
- transaction rules;
- database constraints;
- controller guards;
- application services;
- domain rules;
- policies;
- any other mechanism.

Do not assume that a field named `has_voted` is authoritative.

---

# 3. Distinguish three different facts

Keep these separate:

### A. Voting entitlement

> Is this person allowed to vote in this election?

### B. Voting execution

> Is this request currently allowed to submit a vote?

### C. One-vote invariant

> Has this voter already exercised their voting right in this election?

These may have different decision owners.

Do NOT collapse them merely because they are represented by the
same model/table.

---

# 4. Determine authority

For the one-vote invariant identify:

**Business invariant:**
A voter may cast at most one valid vote per election.

**Decision owner:**
Domain / Application / Authorization / Infrastructure /
Interface / Unknown

**Authoritative representation:**
...

**Enforcement mechanism:**
...

**Legacy representation(s):**
...

**Current live consumers of legacy representation:**
...

**Persistence guarantee:**
...

**Server-side guarantee:**
...

---

# 5. Test the "code exhaustion" hypothesis by inspection

Claude previously identified code exhaustion as a possible mechanism.

Investigate it.

Determine whether:

> after a successful vote, the credential/code becomes unusable for
> another successful vote

and exactly why.

Trace:

successful vote
→ code state
→ VoterSlug state
→ subsequent request
→ validation
→ rejection/acceptance

Do NOT actually cast a vote.

Use existing tests and code-path analysis.

If the mechanism cannot be established without runtime execution,
state:

`Mechanism not established by static evidence.`

---

# 6. Check whether the mechanism is election-scoped

This is critical.

The invariant is not merely:

> "this user cannot vote again."

It is:

> "this voter cannot cast more than one vote in THIS election."

Establish whether the enforcement key is correctly scoped to the election.

Look for:

- election_id;
- membership;
- voter slug ownership;
- code ownership;
- vote relationship;
- unique database constraints.

If the mechanism is user-global rather than election-specific,
record that explicitly.

---

# 7. Check failure modes

Determine what happens if:

1. the same code is submitted twice;
2. the same VoterSlug is reused;
3. the same voter starts voting again;
4. a second voting request is made after the first vote;
5. a second credential is somehow available;
6. the first vote exists but legacy `has_voted` is false;
7. legacy `has_voted` is true but the current mechanism says the
   voter has not voted.

Do not manufacture data to test these.

Use existing tests and code.

Mark anything not proven as:

`UNDETERMINED`.

---

# 8. DDD classification

Produce a final distinction between:

### Business meaning

What does "one vote" mean in the Election domain?

### Domain authority

Which domain concept owns that invariant?

### Application enforcement

Where is the invariant enforced during the use case?

### Persistence enforcement

Does the database independently guarantee it?

### Legacy mechanism

Which old fields/models implement or duplicate it?

### Projection

Which values merely expose the current state?

---

# 9. Final classification

Return exactly one:

### A — CURRENT MECHANISM FULLY ENFORCES THE INVARIANT

The current Code/Vote mechanism independently prevents a second valid
vote, and legacy `has_voted` is unnecessary for correctness.

### B — CURRENT MECHANISM ENFORCES IT BUT LEGACY STATE IS STILL CONSULTED

The invariant is safe, but legacy state remains unnecessarily coupled
to the current path.

### C — LEGACY STATE IS STILL REQUIRED FOR CORRECTNESS

The current mechanism cannot guarantee one-vote-per-election without
the legacy representation.

### D — INVARIANT NOT PROVEN

The repository evidence is insufficient to establish whether a second
vote can be prevented.

---

# 10. Architectural restraint

Do NOT:

- propose a migration;
- remove `has_voted`;
- restore old fields;
- introduce a new aggregate;
- introduce a repository;
- redesign the voting model;
- amend the Election Manifesto;
- declare a constitutional rule;
- create a compatibility layer.

Those are subsequent architectural decisions.

This investigation only establishes:

> how the current system actually enforces the one-vote invariant.

---

# Output

Produce:

1. Execution path
2. Enforcement points
3. Authoritative mechanism
4. Legacy mechanisms
5. Election-scoping analysis
6. Existing test evidence
7. Gaps / unknowns
8. DDD decision-ownership classification
9. Final A/B/C/D classification
10. Implications for the Election Manifesto discovery

Then STOP.

Use:

OBSERVED FACT
INTERPRETATION
HYPOTHESIS
CONCLUSION

Never promote a hypothesis to a conclusion without evidence.
```

## One architectural point I would emphasize

The previous result **does not yet mean we should repair `has_voted`**.

In fact, we may eventually discover something like:

```text
                   ONE-VOTE INVARIANT
                          │
              ┌───────────┴───────────┐
              │                       │
        ElectionMembership        Code/Vote
        = entitlement             = execution
              │                       │
          "may vote"            "vote was exercised"
                                      │
                                      ▼
                              credential exhausted
```

That would be a perfectly reasonable separation of concerns.

The problem would instead be that `ElectionVotingController::start()` currently asks the **entitlement model** for a fact (`has_voted`) that belongs to the **exercise-of-vote history/state**.

That is exactly the kind of distinction your Election Manifesto discovery needs to establish **before anyone starts changing the code**.

And importantly, this investigation can proceed **without waiting for the entire Constitution/Manifesto to be completed**. We can discover the current business semantics and implementation ownership first, then let the Product Owner decide which of those semantics become constitutional.
#

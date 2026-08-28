Yes. At this point I would stop treating the findings as isolated bugs and turn them into an **architectural discovery baseline**. The important thing is to distinguish what is now *proven*, what is *strongly indicated*, and what is still a *business/constitutional decision*.

# Election Runtime & Voting Architecture — Current Findings

**Purpose:** establish the current architectural understanding before deciding further discovery, constitutional amendments, compatibility work, or implementation.

**Status:** Discovery / verification — **no implementation change should be inferred from this document.**

---

## 1. Executive architectural conclusion

The investigation has uncovered a much more coherent model than the legacy columns initially suggested.

The current implementation appears to distinguish at least three different business concerns:

```text
                    ELECTION
                       │
          ┌────────────┼─────────────┐
          │            │             │
          ▼            ▼             ▼
     ENTITLEMENT    EXECUTION     EXERCISE
      "May vote"   "Can vote now" "Has voted"
          │            │             │
          ▼            ▼             ▼
 ElectionMembership   Code        Credential
 role/status       can_vote_now   exhaustion
```

This is important because the historical implementation appears to have attempted to represent several of these concerns through fields such as:

* `is_voter`
* `can_vote`
* `can_vote_now`
* `is_eligible`
* `has_voted`

The current schema and execution paths no longer support treating those legacy fields as a single authoritative model.

The strongest finding so far is:

> **The current voting architecture is not simply a legacy voter system with new names. It contains a newer separation of entitlement, voting execution, and one-vote enforcement. However, the constitutional/business ownership of those concepts has not yet been fully established.**

---

# 2. Business invariant

The central invariant established during the investigation is:

> **A voter may cast at most one valid vote in an election.**

This should be kept separate from the implementation mechanism.

The invariant is business meaning.

`has_voted`, `can_vote_now`, `Code`, `VoterSlug`, etc. are implementation representations.

That distinction is essential for the remaining discovery.

---

# 3. Voter entitlement

## Current evidence

The current voting path uses:

```text
ElectionMembership
```

with relevant concepts including:

```text
role = voter
status != removed
```

The membership is therefore the current representation used to establish that the person belongs to the voting population of an election.

The investigation established that both Election-Only and full-membership modes eventually converge on this representation.

Conceptually:

```text
Full membership mode
        │
        └──────┐
               ▼
       ElectionMembership
               ▲
               │
Election-only ──┘
```

The difference is primarily **how the membership is created**, not how the later voting path consumes it.

### Important finding

`voter_source_strategy` does not determine whether the voter can ultimately submit a ballot.

The actual voting path consults `ElectionMembership`.

Therefore:

> **Election-Only mode appears to be an alternative voter-provisioning mechanism, not a separate voting mechanism.**

That is a strong architectural simplification.

---

# 4. Election-Only mode

The investigation established that Election-Only mode exists as a functioning implementation mechanism.

The observed chain is approximately:

```text
Organisation configuration
        │
        ▼
Election creation
        │
        ▼
voter_source_strategy
        │
        ▼
Election-Only voter import
        │
        ▼
User + ElectionMembership
```

The important point is that the current Constitution/Manifesto does **not yet clearly establish Election-Only mode as a constitutional business concept**.

Therefore we must distinguish:

### Implementation fact

Election-Only mode exists and executes.

### Business interpretation

The product deliberately supports an election-specific voter population independent of the organisation's general membership population.

### Constitutional status

**Not yet established.**

That is a discovery question, not something engineering should silently decide.

---

# 5. The `isEligible` finding

`isEligible` should **not be treated as a persisted authority**.

The investigation established that it is computed.

Therefore:

```text
isEligible
    ↓
projection / runtime result
```

not:

```text
isEligible
    ↓
authoritative stored business decision
```

This distinction became particularly important during the tenant-context investigation.

The same voter/election/membership data could produce different `isEligible` results depending on request/session tenant context.

That demonstrated that:

> `isEligible` is a projection of underlying decisions; it is not itself the authority.

This is exactly the sort of distinction we want the DDD discovery to preserve.

---

# 6. Tenant-context problem

A separate runtime defect was discovered.

The membership row existed and was correct:

```text
user
election
organisation
role = voter
status = active
```

Yet the voter could be considered ineligible depending on the current organisation context.

The controlled experiment demonstrated:

```text
same user
same election
same membership row
same database
different session organisation
        ↓
different isEligible result
```

The cause was traced to tenant scoping.

The membership model's scope uses:

```text
TenantContext::get()
    ??
session('current_organisation_id')
```

Therefore the voter eligibility lookup can become dependent on which organisation the browser last established as its tenant context.

This is **not a business-rule conclusion**.

The business rule:

> voter membership grants voting entitlement

may be correct.

The problem is that infrastructure context can prevent the system from seeing the correct membership.

This was correctly classified as an infrastructure/application integration problem rather than a new constitutional voter rule.

---

# 7. Voting execution

The actual voting path is different from the entitlement path.

The relevant boundary discovered is:

```text
ElectionVotingController::start()
```

The controller checks the election membership and then enters the credential-based voting flow.

The flow is approximately:

```text
ElectionMembership
       │
       │ entitlement
       ▼
ElectionVotingController::start()
       │
       ▼
VoterSlug
       │
       ▼
Code
       │
       ▼
VoteController
       │
       ▼
anonymous Vote
```

This is an important separation.

The voter does not simply submit:

```text
User → Vote
```

Instead there is an intermediate voting credential.

---

# 8. Why the Vote itself cannot enforce one-vote-per-user

The database investigation produced an especially important architectural fact.

`votes` does **not** contain `user_id`.

That appears intentional because the vote is anonymous.

Therefore the database cannot simply enforce:

```text
UNIQUE(election_id, user_id)
```

on the ballot.

The anonymity boundary prevents that.

This means the one-vote invariant has to be enforced **before/around the anonymous ballot**, rather than by attaching voter identity to the vote.

That is why the credential mechanism is architecturally significant.

---

# 9. Current one-vote mechanism

The current mechanism is based on the voting credential.

The database has:

```text
codes
UNIQUE(election_id, user_id)
```

and:

```text
voter_slugs
UNIQUE(election_id, user_id)
```

The actual voting guard checks:

```text
codes.can_vote_now = true
```

not:

```text
voter_slugs.can_vote_now
```

This distinction was initially easy to miss because both tables contain a `can_vote_now` field.

The actual path is:

```text
Code
  │
  ├── belongs to election
  ├── belongs to voter
  └── can_vote_now
          │
          ▼
      VoteController
          │
          ▼
       Vote
```

---

# 10. One-vote invariant — current conclusion

The investigation initially classified the situation as:

> C — current enforcement still depends on legacy state.

That classification was subsequently refined.

The broader and more correct conclusion is:

> **The one-vote invariant is independently enforced by the current election-scoped credential mechanism, although the entry path still consults the legacy `election_memberships.has_voted` representation.**

This is a crucial distinction.

The current invariant does **not** depend on `ElectionMembership.has_voted`.

---

# 11. Why re-arming does not defeat the invariant

A potentially dangerous implementation detail was discovered.

`ElectionVotingController::start()` can re-arm:

```text
voter_slugs.can_vote_now
```

However, the actual vote guard reads:

```text
codes.can_vote_now
```

Therefore:

```text
voter_slugs.can_vote_now = true
```

does **not** restore:

```text
codes.can_vote_now = true
```

The two mechanisms are separate.

This means the apparent re-arming vulnerability does not currently defeat the one-vote invariant.

---

# 12. Code regeneration does not re-arm an exhausted credential

Another possible bypass was investigated.

`CodeController` can regenerate expired credentials.

However, its regeneration path is guarded by the existing vote state, beginning with a condition equivalent to:

```text
!$code->has_voted
```

and the regeneration logic preserves the existing `can_vote_now` state rather than simply setting it to `true`.

Therefore a credential that has already been consumed cannot be promoted back into a valid voting credential through that regeneration path.

---

# 13. Structural reason the mechanism is strong

The most important structural combination is:

```text
codes
UNIQUE(election_id, user_id)
```

plus:

```text
Code.can_vote_now
```

plus terminal exhaustion after voting.

Therefore there is:

> **one credential per voter per election**

and that credential cannot simply be replaced with another credential for the same voter/election.

This makes credential exhaustion a plausible and structurally sound enforcement boundary for the one-vote invariant.

---

# 14. Election scoping

The investigation also established that the mechanism is election-scoped.

The relevant components are all associated with the election:

```text
code
   └── election_id + user_id

voter slug
   └── election_id + user_id

vote
   └── belongs to election
```

Therefore the rule is not:

> "A person can only vote once in their lifetime."

It is:

> **A person can exercise their voting credential at most once for a particular election.**

That distinction is important.

A person participating in Election A should not become permanently unable to participate in Election B.

---

# 15. `has_voted` — legacy status

We should now classify the different `has_voted` representations carefully.

The investigation found legacy representations in more than one place.

The important conclusion is **not**:

> "`has_voted` exists in several tables, therefore there are several authorities."

Instead:

> **The business invariant is one-vote-per-election; we must determine which representation actually owns/enforces that invariant.**

Current evidence says the credential mechanism does.

Therefore the `ElectionMembership.has_voted` field appears to be a **legacy/inert representation that remains consulted by the entry path but is not the authoritative one-vote mechanism**.

That makes it a candidate for the later legacy-consumer audit.

It should **not** be removed merely because we now understand it better.

---

# 16. Three distinct concepts

This is probably the most important architectural finding to carry forward.

We should not collapse:

### A. Entitlement

> **May this person vote in this election?**

Current implementation representation:

```text
ElectionMembership
```

### B. Execution permission

> **Can this person currently enter/execute the voting operation?**

Current representation involves:

```text
Code
VoterSlug
can_vote_now
voting window
IP restrictions
```

### C. Exercise history

> **Has this voter already exercised the voting right?**

Current enforcement is expressed through:

```text
credential exhaustion
```

while the old:

```text
ElectionMembership.has_voted
```

is not the current authority.

This separation is strongly supported by the current implementation.

---

# 17. What is NOT yet established

There are still important gaps.

### First vote

We have **not yet proven end-to-end** that:

```text
eligible voter
      ↓
credential correctly armed
      ↓
voting window open
      ↓
first vote
      ↓
vote persisted
```

works correctly.

No vote has been cast during this discovery.

Therefore:

> **First-vote execution remains NOT VERIFIED.**

### Initial credential arming

We have not yet fully traced:

> What exact business/application process sets `codes.can_vote_now = true` for a newly imported voter?

This is particularly important for your Election-Only question.

### Manifesto ownership

We have not yet established whether the Election Manifesto explicitly defines:

* who grants voting entitlement;
* how imported voters become eligible;
* who opens access to the voting window;
* what conditions must exist before voting starts;
* how Election-Only mode is governed.

Those remain constitutional/business discovery questions.

---

# 18. Candidate lifecycle defect

A separate but very significant finding was already established.

An election with:

```text
0 approved candidates
```

could become:

```text
voting_active
```

merely because the voting window opened.

The lifecycle engine's temporal branch takes precedence over nomination completeness.

Conceptually:

```text
voting window open
        │
        ▼
VotingActive
```

even when:

```text
approved candidates = 0
```

The existing candidate guard works when `complete_nomination` is explicitly invoked, but the temporal lifecycle calculation can bypass that guarded transition.

Therefore this is a **lifecycle-authority defect**, not merely a missing UI button or old fixture problem.

This finding should remain separate from the voter-entitlement investigation.

---

# 19. The architectural picture now

The current implementation can be represented approximately as:

```text
                    ORGANISATION
                         │
                         ▼
                  ELECTION CONFIG
                         │
             ┌───────────┴───────────┐
             │                       │
      Full Membership          Election-Only
             │                       │
             └───────────┬───────────┘
                         ▼
                ElectionMembership
                         │
                         │
                  voter entitlement
                         │
                         ▼
                ElectionVotingController
                         │
                         ▼
                    VoterSlug
                         │
                         ▼
                       Code
                         │
              can_vote_now = true
                         │
                         ▼
                   VoteController
                         │
                         ▼
                  Anonymous Vote
                         │
                         ▼
                credential exhausted
                         │
                         ▼
                second vote refused
```

Meanwhile the Election lifecycle is a separate authority:

```text
ElectionLifecycle
       │
       ├── administration
       ├── nomination
       ├── voting
       └── results
```

And the investigation has already found that lifecycle currently has an important competing temporal derivation that can enter `voting_active` without candidate completeness.

---

# 20. What this means for the Election Manifesto

I would **not write the Manifesto yet based solely on these implementation findings**.

Instead, use these findings to formulate the business questions.

### Decision 1 — Voter entitlement

What does the organisation mean when it says:

> "This person is allowed to vote in this election"?

Is `ElectionMembership(role=voter,status=active)` the intended business representation?

### Decision 2 — Election-Only

Is Election-Only an official business mode?

If yes:

> What business rule grants voting entitlement to an imported person?

### Decision 3 — Voting access

What conditions must hold before an entitled voter receives access to the voting operation?

For example:

```text
Election is in voting phase
AND
voter has entitlement
AND
voting window is open
AND
credential is valid
```

But those conditions should be **discovered/decided**, not invented by architecture.

### Decision 4 — One-vote invariant

The business rule appears straightforward:

> One voter may exercise their voting right at most once per election.

The implementation has a strong credential-based enforcement mechanism.

The constitutional question is whether and how that invariant should be explicitly expressed in the Manifesto.

### Decision 5 — Anonymity

The lack of `user_id` in the Vote model appears to preserve anonymity.

That creates an architectural constraint:

> The system cannot enforce one-vote-per-voter by attaching voter identity directly to the anonymous ballot.

This is an important constraint for any future constitutional design.

---

# 21. Recommended next discovery sequence

I would **not start refactoring legacy columns now**.

I would proceed in this order:

### Step 1 — First-vote credential path

Trace:

```text
Election-Only import
→ ElectionMembership
→ credential creation
→ codes.can_vote_now
→ voting window
→ first vote
```

**Question:** Can an imported voter legitimately reach the first-vote operation?

---

### Step 2 — Runtime first-vote verification

Only after Step 1 establishes the mechanism:

* use a controlled test election;
* use five test voters;
* one candidate;
* voting window;
* verify each voter can reach the ballot;
* verify first vote;
* verify persistence;
* verify second-vote rejection.

This would answer your original practical question.

---

### Step 3 — Election lifecycle

Separately resolve:

> Can voting become active without an approved candidate?

This should be treated as a lifecycle authority issue, not mixed into voter eligibility.

---

### Step 4 — Manifesto/Constitution discovery

Once the implementation facts are known, ask the Product Owner to decide:

* Election-Only as a business mode;
* voter entitlement;
* voting access;
* candidate completeness;
* one-vote invariant;
* anonymity constraints.

---

### Step 5 — Legacy audit

Only after the current authoritative model is understood:

```text
has_voted
is_voter
can_vote
is_eligible
```

can be classified as:

* authoritative;
* transitional;
* projection;
* dead;
* legacy consumer;
* compatibility requirement.

**Do not delete or restore anything merely because it is old.**

---

# 22. Current status matrix

| Area                                                         | Status                   |
| ------------------------------------------------------------ | ------------------------ |
| Election-Only implementation exists                          | ✅ Established            |
| Election-Only → ElectionMembership convergence               | ✅ Established            |
| `isEligible` is computed/projection                          | ✅ Established            |
| Tenant context can corrupt eligibility lookup                | ✅ Established            |
| ElectionMembership is current entitlement representation     | ✅ Strongly established   |
| `ElectionMembership.has_voted` is current one-vote authority | ❌ Not supported          |
| Current one-vote mechanism exists                            | ✅ Established            |
| One-vote mechanism is election-scoped                        | ✅ Established            |
| Used credential can be re-armed through `start()`            | ❌ Proven impossible      |
| Code regeneration can re-arm used credential                 | ❌ Proven impossible      |
| First vote can successfully complete                         | ⚠️ **Not established**   |
| Election-Only imported voter can successfully vote           | ⚠️ **Not established**   |
| Election Manifesto defines Election-Only semantics           | ❓ Unknown                |
| Manifesto defines voter entitlement                          | ❓ Needs explicit review  |
| Manifesto defines voting-access conditions                   | ❓ Needs explicit review  |
| Lifecycle can enter voting without candidate                 | ❌ **Defect established** |
| Legacy fields should now be removed                          | ❌ No basis yet           |

---

## My Principal Architect assessment

The discovery has reached a **useful architectural checkpoint**.

We should **not rush to "fix legacy code."** We now have evidence that some things that looked like legacy problems are actually manifestations of a newer architecture:

* ElectionMembership provides entitlement.
* Credential state provides voting execution.
* Credential exhaustion provides one-vote enforcement.
* Anonymous Vote persistence preserves voter anonymity.
* Election-Only and full-membership provisioning converge at the election voter relationship.

The remaining problem is therefore no longer:

> *"How do we make the old voter system work again?"*

It is:

> **"Can we prove that the current Election domain correctly turns an entitled voter into an authorised voting opportunity, while preserving anonymity and the one-vote invariant, and does the Election Manifesto actually define those business responsibilities?"**

That is the right question for the next phase.

**I would make the next action the first-vote credential/arming discovery, followed by the controlled five-voter runtime verification — still without changing production code.**

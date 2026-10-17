# PublicDigit — Deep Voting Engine Verification
## CodeController + Complete Voting Journey + Hook Integrity Audit

### PURPOSE

The PublicDigit voting process is a high-reputation-risk operation.

Once a real election has started, correcting a business-rule mistake can be extremely difficult and can damage trust even when the mistake is technically small.

Therefore, **do NOT implement anything in this task**.

Your task is to reconstruct and verify the **entire voting engine from a business perspective**, starting with `CodeController` and following every relevant hook into the voting process.

The objective is not merely to find bugs.

The objective is to answer:

> **Does the implemented voting engine actually enforce the intended business process from voter entry through final vote, and are all relevant hooks implemented, connected, ordered and authoritative?**

We need to understand the engine deeply enough that we can confidently make future PO/ARB decisions.

---

# 1. ABSOLUTE RULES

## 1.1 READ-ONLY

This is a **READ-ONLY AUDIT**.

You MUST NOT:

- modify production code
- modify tests
- modify configuration
- modify routes
- modify migrations
- modify database schema
- modify Vue/frontend code
- modify middleware
- modify documentation
- create new files
- delete files
- rename files
- refactor anything
- "fix" anything
- commit anything

If you discover a defect, document it only.

If you believe a change is necessary, describe the required change but DO NOT make it.

---

## 1.2 NO ASSUMPTIONS

Do not assume that:

- a method name describes what it really does
- a middleware name describes its authority
- a validation is actually executed
- a check protects the final vote
- a test proves the production path
- a configuration value has the meaning its name suggests
- a business rule exists merely because code appears to implement it
- a step-4 check protects step-5/final casting
- an earlier validation remains true later

Trace the actual execution path.

---

## 1.3 BUSINESS LANGUAGE FIRST

The final report must primarily describe the system in **business language**.

For example, do not merely write:

> `CodeController::verifyCode()` sets `can_vote_now=true`.

Translate that into:

> "The system records that the voter has successfully completed the voting-code verification step and subsequently permits the voter to continue into the voting journey."

Then separately provide the technical evidence.

---

# 2. PRIMARY QUESTION

Answer this question:

> **Can we explain the complete PublicDigit voting process as a sequence of business decisions, and can we prove from the implementation that every required business decision is actually enforced at the correct point?**

Specifically determine:

1. What must be true before a voter may start?
2. What must happen before a voter receives/uses a voting code?
3. What does successful code verification actually establish?
4. What does it NOT establish?
5. What happens when a code is regenerated?
6. What happens when a voter re-enters the process?
7. What must be true before the ballot form is shown?
8. What must be true before the final vote is accepted?
9. What happens during final vote submission?
10. What makes the vote irreversible/committed?
11. What proves that a voter cannot vote twice?
12. What proves that an unauthorized person cannot vote?
13. What protects ballot anonymity?
14. What happens when any prerequisite is missing?
15. Can any request path bypass an important business rule?

---

# 3. START WITH CODECONTROLLER

Perform a complete audit of:

```text
app/Http/Controllers/CodeController.php
```

Do NOT review only the methods that appear to be related to the current issue.

Inventory **every method** that can influence voting.

For each method determine:

- business purpose
- caller(s)
- route(s)
- middleware
- inputs
- database reads
- database writes
- session writes
- code-state changes
- voter-state changes
- election-state changes
- security checks
- eligibility checks
- step progression
- redirects
- error handling
- side effects
- whether it can influence final voting
- whether it can create a state that later enables voting

Create a table:

| Technical Hook | Business Purpose | Trigger | Reads | Writes | Decision | Authority | Later Effect | Risk |
|---|---|---|---|---|---|---|---|---|

---

# 4. TRACE EVERY CALLER OF CODECONTROLLER

Do not stop at CodeController.

For every relevant CodeController method, find:

- route
- controller caller
- middleware
- service caller
- model/event/listener interaction
- frontend entry point
- redirect target
- next voting step

Construct the actual journey.

Example:

```text
Election entry
   ↓
Eligibility
   ↓
Code creation
   ↓
Code delivery
   ↓
Code verification
   ↓
Voting session creation
   ↓
Ballot preparation
   ↓
Voting form
   ↓
Final cast
   ↓
Participation consumption
   ↓
Anonymous vote persistence
   ↓
Commit
```

Replace this example with the **actual implemented journey**.

Do not assume this sequence is correct.

---

# 5. BUILD THE COMPLETE VOTING BUSINESS STATE MACHINE

Reconstruct the real voting lifecycle from source code.

Use business states, not controller methods.

For example, investigate whether the actual system has states resembling:

```text
Not eligible
    ↓
Eligible
    ↓
Voting access initiated
    ↓
Code issued
    ↓
Code verified
    ↓
Voting session active
    ↓
Ballot prepared
    ↓
Ready to vote
    ↓
Vote submitted
    ↓
Participation consumed
    ↓
Vote committed
```

But DO NOT assume these states exist.

Derive the real state machine from the implementation.

For every state identify:

- entry condition
- exit condition
- authoritative data
- allowed transitions
- forbidden transitions
- who/what performs the transition
- whether the transition is atomic
- whether it can be repeated
- whether it can be bypassed
- whether regeneration/re-entry changes the state

Produce:

| Business State | Entry Condition | Transition | Authority | Persistent Evidence | Can Repeat? | Can Bypass? | Risk |
|---|---|---|---|---|---|---|---|

---

# 6. AUDIT ALL VOTING HOOKS

Search the repository for all code that can influence voting authorization, voting progression, or vote persistence.

At minimum investigate:

### Eligibility / entitlement

- `VotingEntitlementQuery`
- `forEntry()`
- `forCast()`
- membership checks
- voter-role checks
- admission checks
- organisation checks
- election lifecycle
- election votability

### Code

- `CodeController`
- code creation
- code regeneration
- code verification
- `can_vote_now`
- `has_voted`
- `vote_submitted`
- `code_to_open_voting_form_used_at`
- `voting_time_in_minutes`
- `code_to_save_vote`
- code expiry
- second-code logic
- code usability flags
- code timestamps

### Voting journey

- `VoteController`
- `verify()`
- `first_submission()`
- `vote_pre_check()`
- `second_code_check()`
- `vote_post_check()`
- `store()`
- `save_vote()`
- `markUserAsVoted()`

### Middleware

Inventory every middleware on:

- election entry
- code creation
- code verification
- voting form
- final cast

For each middleware answer:

> Does this middleware merely guide the voter, or does it provide an authoritative business/security decision?

### Routing

Trace every route that can eventually reach the final vote.

Especially identify:

- normal browser journey
- direct POST
- retry
- refresh
- back button
- regenerated code
- expired code
- missing code
- missing ballot
- already-voted voter
- malformed request

---

# 7. AUTHORITATIVE DECISION AUDIT

For every important business rule identify exactly ONE authoritative decision point.

Create:

| Business Rule | Current Check(s) | Authoritative Check | Duplicate Checks | Can Disagree? | Final-Cast Protected? | Status |
|---|---|---|---|---|---|---|

At minimum audit:

1. voter entitlement
2. voter admission
3. election lifecycle
4. election votability
5. voter role
6. organisation membership
7. security/trust
8. IP density
9. voting-code validity
10. voting-code expiry
11. code regeneration
12. voting-session validity
13. ballot presence
14. already-voted state
15. participation consumption
16. vote persistence
17. transaction atomicity
18. anonymity
19. receipt/confirmation
20. demo-vs-real election separation

For each rule answer:

> **Where is this rule authoritative?**

If the answer is "several places", explain whether those are:

- duplicate defensive checks
- navigation checks
- advisory checks
- conflicting authorities

---

# 8. FINAL CAST DEEP AUDIT

Treat final cast as the most important transaction in the system.

Reconstruct the exact sequence from request arrival to transaction commit.

Document:

```text
HTTP request
 ↓
middleware
 ↓
controller
 ↓
authorization
 ↓
security
 ↓
process validation
 ↓
transaction
 ↓
lock
 ↓
CastDecision
 ↓
participation CAS
 ↓
anonymous vote persistence
 ↓
other side effects
 ↓
commit
 ↓
response
```

Replace this with the actual sequence.

For each step state:

- business purpose
- authority
- failure behavior
- transaction status
- whether anything has already been written
- whether rollback occurs
- whether the voter can retry
- whether a vote can accidentally be committed

---

# 9. ATOMICITY AUDIT

Verify the exact invariant:

> A successful final vote requires both:
>
> `participation CAS succeeds`
> AND
> `anonymous vote persistence succeeds`
> AND
> `transaction commits`.

Verify:

- CAS placement
- CAS condition
- CAS affected-row handling
- vote insert ordering
- transaction boundaries
- rollback behavior
- lock ownership
- lock release
- exception handling
- `Throwable` vs `Exception`
- post-commit failures

Do not claim "race-safe" merely because a test exists.

Distinguish:

- tested database primitive
- tested same-connection interleaving
- true concurrent behavior
- theoretical database semantics

---

# 10. ANONYMITY AUDIT

This is a separate critical business invariant.

Verify that no newly introduced mechanism creates:

```text
Voter → Vote
Voter → Vote ID
Voter → Ballot
Voter → Vote hash
Code → Vote
Membership → Vote
```

Audit:

- vote table
- Vote model
- save_vote()
- logs
- audit logs
- receipts
- sessions
- cache
- events
- telemetry
- exception messages
- database writes

Also identify existing accepted risks:

- A1
- A5
- A6

Do not silently redesign them.

State clearly:

> Existing risk

versus:

> Newly introduced risk.

---

# 11. ERROR AND FAILURE AUDIT

For every important failure scenario determine what the voter experiences.

At minimum:

- not eligible
- not admitted
- election not active
- code missing
- code wrong
- code not verified
- code expired
- code regenerated
- session expired
- ballot missing
- already voted
- security denied
- security review required
- participation CAS loses
- vote persistence fails
- unexpected exception
- transaction failure

For each:

| Failure | Business Meaning | HTTP/Redirect | User Message | Vote Created? | Participation Consumed? | Transaction State |
|---|---|---|---|---|---|---|

Look especially for:

> technically safe but business-confusing

and:

> technically unsafe

These are different categories.

---

# 12. BYPASS AUDIT

This is mandatory.

Try to identify every way a voter could reach a later state without completing an earlier intended state.

Explicitly investigate:

- direct final POST
- direct URL access
- skipping code verification
- regenerated code
- stale code
- expired code
- browser back
- browser refresh
- duplicate submission
- concurrent submission
- session manipulation
- missing session data
- missing Code record
- stale `can_vote_now`
- stale `vote_submitted`
- stale `has_voted`

For each bypass answer:

> Is this an actual business bypass, a harmless navigation bypass, or intentionally allowed?

---

# 13. CONFIGURATION AUDIT

Find every configuration variable affecting voting.

For each:

| Configuration | Default | Runtime Type | Reader(s) | Business Meaning | Security Meaning | Consistency |
|---|---|---|---|---|---|---|

Pay special attention to:

- environment strings vs integer/boolean comparisons
- missing defaults
- different defaults in different readers
- configuration interpreted differently by different components

Do not change configuration.

---

# 14. TEST COVERAGE AUDIT

Do not simply count tests.

Map tests to business invariants.

Create:

| Business Invariant | Production Enforcement | Test Evidence | Test Type | Gap |
|---|---|---|---|---|

Distinguish:

- unit test
- feature test
- characterization test
- database behavior test
- integration test
- actual concurrency test
- static architecture test

Identify business rules that appear protected in code but have no meaningful test.

---

# 15. "HOOK IMPLEMENTED CORRECTLY?" AUDIT

For every important hook answer five questions:

### A. Does the hook exist?

### B. Is it actually reachable?

### C. Is it called at the intended point?

### D. Does it make the intended business decision?

### E. Is its result actually enforced?

This is extremely important.

A method that exists but whose result is ignored is **not an implemented business rule**.

Use:

| Hook | Exists | Reachable | Correct Position | Correct Decision | Result Enforced | Verdict |
|---|---|---|---|---|---|---|

---

# 16. BUSINESS PROCESS RECONSTRUCTION

After the technical investigation, write the voting process as if explaining it to:

- Product Owner
- Election Committee
- Auditor
- Domain Architect
- Security Architect

WITHOUT code terminology.

Example structure:

## Phase A — Voter qualification

What must be true?

## Phase B — Voting access

How does the voter obtain access?

## Phase C — Code verification

What does verification establish?

## Phase D — Voting preparation

What happens before the ballot is shown?

## Phase E — Final vote

What exactly makes a vote valid?

## Phase F — Participation consumption

What prevents a second vote?

## Phase G — Anonymous ballot persistence

How is the ballot stored without voter identity?

## Phase H — Completion

What does the system finally know?

---

# 17. CRITICAL BUSINESS INVARIANTS

Derive the actual invariants from the code.

Do NOT invent them.

For every invariant write:

> **Business invariant:** ...
>
> **Where enforced:** ...
>
> **When enforced:** ...
>
> **Authoritative:** YES/NO
>
> **Evidence:** ...
>
> **Test evidence:** ...
>
> **Gap:** ...

At minimum investigate whether these are actually true:

1. Only entitled voters can vote.
2. Only voters admitted to the election can vote.
3. A voter can cast at most one committed vote.
4. A vote cannot be committed without consuming participation.
5. Participation cannot be consumed without a committed vote.
6. Anonymous ballot data contains no voter identity.
7. A direct final POST cannot bypass required final-cast conditions.
8. Code verification has a clearly defined meaning.
9. Code validity has a clearly defined authority.
10. Security decisions have one authority.
11. Election lifecycle has one authority.
12. Failure paths fail closed.
13. Demo elections cannot accidentally alter real-election rules.
14. Retry behavior is deterministic and safe.

If any invariant cannot be proven, say:

> **NOT PROVEN**

Do not convert uncertainty into PASS.

---

# 18. RED / GREEN / YELLOW CLASSIFICATION

Every finding must receive one of:

### GREEN
Implemented and evidence is sufficient.

### YELLOW
Implemented but duplicated, unclear, weakly tested, or architecturally questionable.

### RED
Missing, bypassable, contradictory, or unsafe.

### UNKNOWN
Insufficient evidence to conclude.

Do not use percentages.

Do not say "95% complete".

---

# 19. CRITICAL DISTINCTION

Separate findings into:

### A. Existing defects

Already present before Phase 4.

### B. Phase 4 changes

Introduced or resolved by Phase 4.

### C. Phase 5 findings

Discovered during this audit.

### D. Accepted interim risks

Explicitly accepted by architecture/PO.

### E. Design debt

Not currently a live production defect.

### F. Unknown

Requires further investigation.

This prevents historical defects from being incorrectly attributed to the current implementation.

---

# 20. REQUIRED FINAL REPORT

Create ONE read-only audit report:

```text
docs/publicdigit/reviews/
YYYY-MM-DD-complete-voting-engine-business-audit.md
```

Do not modify existing reports.

The report must contain:

1. Executive Summary
2. Audit Scope
3. Source Files / Routes / Middleware Reviewed
4. Complete Business Voting Journey
5. CodeController Business Responsibilities
6. Voting State Machine
7. Complete Hook Inventory
8. Authority Matrix
9. Final-Cast Sequence
10. Atomicity Audit
11. Anonymity Audit
12. Failure Semantics
13. Bypass Analysis
14. Configuration Audit
15. Test-to-Invariant Matrix
16. Business Invariants
17. Findings
18. Existing vs New Findings
19. Critical Unknowns
20. Recommended Decisions
21. Recommended Future Work
22. Evidence Appendix
23. Audit Limitations

---

# 21. MANAGEMENT SUMMARY

End the report with a one-page management summary.

It must answer exactly:

### Can we currently trust the voting engine?

Choose one:

- YES
- YES, WITH EXPLICIT CONDITIONS
- NO
- NOT YET PROVEN

### What is definitely safe?

### What is definitely unsafe?

### What is not yet proven?

### Which business decisions are still required?

### Which technical fixes are required?

### Which issues are merely design debt?

### What must be completed before a real election?

### What must NOT be changed without PO/ARB approval?

---

# 22. MOST IMPORTANT RULE

Do not optimize for finding many issues.

Optimize for **confidence**.

The objective is to reach a state where we can say:

> "We understand what the voting engine does, why it does it, which business rules are authoritative, which rules are merely navigation aids, which invariants are proven by tests, which risks are accepted, and which decisions must be made before the next production election."

If that statement cannot yet be made, identify precisely what evidence is missing.

---

# 23. STOP CONDITION

After producing the report:

STOP.

Do not:

- fix findings
- create tests
- modify architecture
- create ADRs
- modify the existing decision brief
- modify the Round 1 report
- modify Developer Guide
- update CONTEXT.md
- commit anything

Report only the findings and evidence.

Then wait for manual review and approval.

**This audit is discovery only.**
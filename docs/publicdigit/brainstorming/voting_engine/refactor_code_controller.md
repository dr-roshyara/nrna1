Yes. After reviewing the **complete `CodeController`**, I would now make a fairly strong architectural recommendation:

> **Do not rewrite it. Do not create `PrecheckController` yet. First extract and formalize the pre-voting gate from this controller, because the current controller contains a mixture of prechecks, code lifecycle, workflow progression, audit, and presentation concerns.**

The controller is currently doing much more than code verification. The source confirms that it handles membership, election context, already-voted checks, eligibility, code generation/expiry/verification, IP limits, agreement state, slug progression, audit, and HTTP rendering/redirects. Eingefügtes Markdown

## 1. My architectural assessment

I would describe the current controller as:

```text
                    CodeController
                         │
       ┌─────────────────┼──────────────────┐
       │                 │                  │
       ▼                 ▼                  ▼
  PRE-VOTING         CODE LIFECYCLE      WORKFLOW
    GATE                                  STATE
       │                 │                  │
       ├ Membership      ├ create           ├ Step 1
       ├ Eligibility     ├ verify           ├ Step 2
       ├ Already voted   ├ expire           └ redirect
       ├ IP security    └ regenerate
       └ Election state
       │
       └──────────────┐
                      ▼
                 HTTP response
```

This is the main architectural problem.

**The problem is not that CodeController has many responsibilities.**

The problem is that **business decisions, persistence, security checks, workflow state transitions, notifications, audit and HTTP presentation are interwoven inside the same methods.**

---

# 2. What is already good

I don't want to over-refactor something that already contains useful protections.

### Election context is explicitly established

`getElection()` refuses to continue when middleware did not provide election context and additionally checks voter-slug/election consistency. Eingefügtes Markdown

That is good.

Conceptually:

```text
Request
  ↓
ElectionMiddleware
  ↓
Election context
  ↓
CodeController
```

I would preserve this architecture.

### Election-scoped Code lookup is correct direction

The code is looked up by:

```text
user_id
election_id
organisation_id
```

in `store()`. Eingefügtes Markdown

That's much better than treating a verification code as globally meaningful.

### Code expiry is actually checked

`verifyCode()` checks the code timestamp against the configured window. Eingefügtes Markdown

That is an important existing control.

### IP limit is at least election-scoped

The current query counts:

```text
client_ip
+
election_id
+
has_voted = 1
```

before accepting verification. Eingefügtes Markdown

Whether this is the **right authority** is another question, but the existing intent is clear.

---

# 3. The biggest problem: `can_vote_now`

This is the first thing I would correct conceptually.

The class documentation says:

> `can_vote_now` is the single source of truth.

But that is not consistent with our current architecture.

The controller itself uses `can_vote_now` for:

- whether code verification is complete,
- whether to redirect from code entry,
- whether the voter may proceed to agreement,
- whether an existing code should be regenerated,
- whether agreement may be shown. Eingefügtes Markdown Eingefügtes Markdown

That means:

```text
Code.can_vote_now
```

has effectively become:

```text
"voter is verified enough to continue"
```

That is **not equivalent to VotingEntitlement**.

We already established:

```text
ElectionMembership
        ↓
Admission
        ↓
VotingEntitlement
        ↓
VotingAuthorization
```

Therefore I would redefine its meaning as:

> `Code.can_vote_now` = **code/process verification state**, not voting authorization.

This is a **semantic correction**, not necessarily a database change.

---

# 4. Second major problem: eligibility is duplicated and legacy-driven

This method is a major architectural smell:

```php
private function isUserEligible(...)
```

It currently says:

```text
demo → true

voter_slug → true

otherwise → users.can_vote
```

The source explicitly describes the final branch as legacy. Eingefügtes Markdown

That means we currently have:

```text
ElectionMembership
       +
voter_slug
       +
users.can_vote
       +
demo session
```

as competing ways to establish eligibility.

That's precisely what our architecture was supposed to eliminate.

### Target

The eventual model should be:

```text
PreVotingGate
       │
       ▼
ElectionVoter / VotingEntitlement
       │
       ▼
Allowed / Denied
```

not:

```text
if demo
else if slug
else users.can_vote
```

I would classify this as **P1 architectural cleanup**.

But don't delete `users.can_vote` blindly. First characterize every route that still depends on it.

---

# 5. Membership checking is duplicated

`ensureVoterMembership()` appears in:

- `create()`
- `store()`
- `showAgreement()`
- `submitAgreement()`

with different skip behavior for slug requests. Eingefügtes Markdown Eingefügtes Markdown Eingefügtes Markdown

This is a sign that the controller is compensating for the fact that the request pipeline does not yet have one authoritative pre-voting context.

Eventually I would like:

```text
Middleware / application boundary
          ↓
CastContext / PreVotingContext
          ↓
CodeController
```

rather than every method independently rebuilding:

```text
user
election
membership
slug
code
```

---

# 6. `create()` is doing far too much

`create()` currently performs approximately:

```text
1. Resolve user
2. Resolve election
3. Resolve voter slug
4. Set organisation session
5. Membership check
6. Load Code
7. Already verified check
8. Already voted check
9. Eligibility check
10. Create Code
11. Calculate expiry
12. Regenerate Code
13. Modify voter slug
14. Send notification
15. Build API response
16. Build Inertia response
```

The source demonstrates all of this in one method. Eingefügtes Markdown

That's the clearest candidate for extraction.

I would eventually want:

```text
create()
   │
   ├── context = preVotingContext.resolve()
   │
   ├── gate = preVotingGate.check(context)
   │
   ├── code = codeService.prepare(context)
   │
   └── presenter.render(...)
```

But **not yet**.

---

# 7. `store()` mixes verification and workflow

`store()` currently does:

```text
membership
↓
input validation
↓
eligibility
↓
code lookup
↓
already voted
↓
already verified
↓
verify code
↓
update code
↓
step tracking
↓
legacy progress
↓
audit
↓
redirect
```

That's too much for one application operation.

Especially these two:

```php
VoterStepTrackingService
VoterProgressService
```

are both being updated.

The source itself calls the second one **legacy/deprecated**. Eingefügtes Markdown

This is a classic migration problem:

```text
new workflow state
       +
legacy workflow state
       +
controller synchronization
```

I would make this a separate cleanup story.

---

# 8. There are two workflow-state systems

This is particularly important.

We have:

```text
VoterStepTrackingService
```

and:

```text
VoterProgressService
```

The controller writes to both.

For agreement, the same thing happens: the new step tracker is updated and then the legacy progress service is advanced. Eingefügtes Markdown

This means the controller is currently responsible for maintaining **two representations of the same voter workflow**.

That is dangerous.

### Eventually

There should be one authoritative process state.

Something like:

```text
VotingProcessState
    CODE_VERIFIED
        ↓
    AGREEMENT_ACCEPTED
        ↓
    BALLOT_READY
        ↓
    CAST
```

The old progress mechanism should eventually disappear.

---

# 9. `showAgreement()` is doing mutation during a GET

This is one of the things I would definitely fix eventually.

When agreement has already been accepted, `showAgreement()` can call:

```text
completeStep(...)
```

to repair step tracking. Eingefügtes Markdown

So a GET request is doing a state mutation.

Conceptually:

```text
GET /agreement
      ↓
discover inconsistent state
      ↓
modify database
```

I would avoid this.

A GET should normally be:

```text
read state
→ determine next state
→ render/redirect
```

If state repair is necessary, it should be an explicit command/use case.

This is a **real architectural improvement**, not merely style.

---

# 10. Demo logic is too deeply embedded

This is one of the biggest structural problems.

We have demo-specific behavior in:

```text
isUserEligible()
getOrCreateCode()
create()
store()
```

and particularly:

```php
if ($code && $code->has_voted && $election->type === 'demo')
```

which resets participation state. Eingefügtes Markdown

This makes the controller operate like:

```text
if real
    security behavior A

if demo
    security behavior B

if slug
    behavior C

if legacy
    behavior D
```

That's why our S2/ADR discussion became important.

The eventual architecture should make demo isolation explicit:

```text
Real Voting Path
       │
       ▼
RealElectionVotingPolicy
```

and:

```text
Demo Path
       │
       ▼
DemoVotingPolicy
```

rather than spreading `if ($election->type === 'demo')` throughout the controller.

**But don't refactor this until we prove whether demo can reach real persistence.**

---

# 11. There is a serious logging/security problem

This is not merely architectural.

The controller logs actual verification codes.

For example:

```text
'code' => $code->code_to_open_voting_form
```

and also logs expected/submitted verification codes in `verifyCode()`. Eingefügtes Markdown Eingefügtes Markdown

It also logs email addresses and other voter-identifying information in several places.

Given that this is an election system, I would classify:

> **Plaintext verification code logging = security finding.**

The code itself should never appear in normal application logs.

We previously identified this as VE-06, and this controller confirms the finding.

---

# 12. `verifyCode()` is conceptually too broad

Today:

```text
verifyCode()
```

does:

```text
code usable?
code matches?
code expired?
IP limit?
```

So its name suggests:

```text
"verify code"
```

but it actually means:

```text
"verify code AND security preconditions"
```

That's another reason your **Pre-Voting Gate** idea is useful.

Eventually:

```text
PreVotingGate
    ├── membership
    ├── participation
    ├── election period
    ├── IP policy
    └── identity requirements

CodeVerifier
    ├── code exists
    ├── code matches
    └── code validity window
```

Then:

```text
PreVotingGate
       +
CodeVerifier
       ↓
PreVotingDecision
```

That's much cleaner.

---

# 13. Voting period is missing as a proper explicit authority

You identified this responsibility correctly:

> voting period has started or not

But in this controller I don't see a proper election-level voting-period authority being used.

Instead, `votingTimeInMinutes` is primarily the **code/session validity window**.

Those are different:

```text
Election voting period
        ≠
Verification-code lifetime
        ≠
Voter session lifetime
```

For example:

```text
Election:
18:00 ─────────────────────── 22:00
          voting permitted

Code:
19:12 ─────── 19:42
          valid

Session:
19:20 ───────── 19:50
          active
```

We should not let one `30 minutes` configuration represent all three.

I would make **VotingPeriodPolicy** an explicit precheck.

---

# 14. IP checking needs architectural clarification

Currently IP logic lives inside `verifyCode()`:

```text
Code
  +
current IP
  +
election
  +
has_voted
  →
count
```

This is not necessarily wrong, but we need to answer:

> Is IP binding a constitutional security requirement, an anti-abuse policy, or merely an operational control?

And separately:

> Is `MAX_USE_OF_IP` supposed to count verified codes, votes, or successful participation?

The current implementation counts:

```text
Code.has_voted = 1
```

which means it is actually a **post-participation count**, despite being evaluated during code verification.

That semantic mismatch should be documented.

---

# 15. Code regeneration has a dangerous semantic issue

This part is especially important:

```php
'can_vote_now' => $code->can_vote_now
```

when regenerating an expired code. Eingefügtes Markdown

The code is being replaced while preserving:

```text
can_vote_now = true
```

So:

```text
old code expired
        ↓
new code generated
        ↓
verification state preserved
```

That might be intentional, but it means:

> **Code credential expiry does not necessarily mean identity verification expiry.**

That's actually a valid architecture **if intentional**.

But it must be explicitly named.

We need to distinguish:

```text
Credential validity
        ≠
Identity verification state
```

Otherwise developers will interpret regeneration inconsistently.

---

# 16. Code generation should eventually be a service

This:

```php
generateCode()
generateUniqueCodeForOrganisation()
```

belongs conceptually outside the controller.

Especially:

```text
generate
→ query uniqueness
→ fallback
```

is domain/application infrastructure.

And the fallback:

```text
generateCode() . Str::random(4)
```

is suspicious because it changes the format and does not perform another uniqueness check.

I would make uniqueness a database-enforced invariant eventually.

For example:

```text
UNIQUE(
    organisation_id,
    code
)
```

then generate and retry on constraint collision.

Do not rely exclusively on:

```text
SELECT exists()
then INSERT
```

because two requests can race.

---

# 17. There is also obvious dead/legacy baggage

The imports include things such as:

```text
DB
Hash
VotingServiceFactory
Validator
```

which appear unnecessary in the controller as provided.

There is also:

```text
$voteNotSubmitted
```

which is calculated but not used in the resend decision.

And the comment says:

```text
reason => 'restart_after_use'
```

although the condition requires:

```text
!$codeWasUsed
```

So that reason is internally inconsistent.

These are not architectural blockers, but they indicate that this controller has accumulated historical fixes.

---

# 18. My target architecture

I would **not** target:

```text
PrecheckController
```

as the first extraction.

I would target:

```text
                         HTTP
                          │
                          ▼
                  CodeController
                          │
             ┌────────────┴────────────┐
             ▼                         ▼
      PreVotingGate              CodeWorkflow
             │                         │
      ┌──────┼──────┐                  │
      │      │      │                  ├─ issue
      │      │      │                  ├─ verify
      │      │      │                  └─ regenerate
      ▼      ▼      ▼
 Membership Security Participation
      │      │      │
      └──────┼──────┘
             ▼
       VotingPeriod
             │
             ▼
      PreVotingDecision
```

Then:

```text
PreVotingDecision
    ├── Allowed
    └── Denied(reason)
```

The controller merely translates that into HTTP.

---

# 19. What I would NOT move into PreVotingGate

Very important.

I would **not** put these into the gate:

```text
Generate verification code
Send email
Render Code/CreateCode
Render Agreement
Record UI step
Redirect to route
```

Those are workflow/application concerns.

The gate should answer:

> **Can this voter proceed into the voting workflow right now?**

It should not send emails or render pages.

---

# 20. Recommended refactoring sequence

I would do this incrementally.

### Phase A — Characterization

Before modifying anything:

```text
CodeController characterization tests
```

Cover:

- membership
- election context
- already voted
- code missing
- code invalid
- code expired
- IP limit
- verified code
- agreement state
- demo
- slug
- real election

No refactoring yet.

### Phase B — Fix obvious security defects

First:

1. Remove plaintext code logging.
2. Remove sensitive request logging.
3. Confirm voting-period check.
4. Confirm IP policy semantics.
5. Confirm code expiry semantics.

### Phase C — Extract PreVotingGate

Then extract:

```text
PreVotingGate
```

with explicit checks:

```text
ElectionMembership
VotingPeriod
Participation
Identity/verification
IP policy
IP usage
```

But the gate should **not mutate state**.

### Phase D — Extract Code lifecycle

Then:

```text
CodeService
```

or more explicit application services:

```text
IssueVotingCode
VerifyVotingCode
RegenerateVotingCode
```

### Phase E — Remove legacy eligibility

Only after characterization:

```text
isUserEligible()
        ↓
VotingEntitlement
```

and eventually eliminate the controller's dependency on:

```text
users.can_vote
```

### Phase F — Remove duplicate workflow tracking

Choose one:

```text
VoterStepTrackingService
```

or the legacy progress system.

Do not maintain both indefinitely.

---

# 21. Most important: don't mix this with VE-01 yet

I would keep **VE-01 ballot validation completely separate**.

The architectural boundary should become:

```text
                    PRE-VOTING
                        │
                 PreVotingGate
                        │
                     allowed
                        │
                 VoteController
                        │
              ┌─────────┴─────────┐
              ▼                   ▼
       CastAuthorization     BallotValidator
              │                   │
              └─────────┬─────────┘
                        ▼
                 ValidatedBallot
                        │
                        ▼
                    CAS
                        │
                        ▼
                     Vote
```

That gives us a very clean separation.

---

## My priority ranking

| Priority | Finding | Action |
|---|---|---|
| 🔴 P0 | Plaintext verification codes in logs | Remove |
| 🔴 P0 | `can_vote_now` treated as broad voting authority | Correct semantic boundary |
| 🔴 P0 | Eligibility has legacy `users.can_vote` path | Characterize → remove |
| 🔴 P1 | Voting period not an explicit authority | Introduce explicit check |
| 🔴 P1 | Demo/real behavior intertwined | Establish structural boundary |
| 🟠 P1 | `create()` is overloaded | Extract workflow services |
| 🟠 P1 | `verifyCode()` mixes code + IP policy | Separate |
| 🟠 P1 | Two workflow tracking systems | Consolidate |
| 🟠 P1 | GET performs step-state repair | Remove mutation |
| 🟡 P2 | Code generation/uniqueness | Extract + DB invariant |
| 🟡 P2 | Logging/legacy/dead code | Clean up |
| 🟡 P2 | HTTP rendering mixed with application logic | Gradual extraction |

### Principal-architect conclusion

**Yes, the idea of a Pre-Voting Gate is now justified.**

But I would **not create a `PrecheckController`**.

I would first refactor toward:

```text
CodeController
      │
      ├── PreVotingGate
      │       ├── Membership
      │       ├── VotingPeriod
      │       ├── Participation
      │       ├── Identity
      │       ├── IP Binding
      │       └── IP Usage
      │
      └── Code Workflow
              ├── Issue
              ├── Verify
              └── Regenerate
```

Then `VoteController` starts **after the pre-voting gate** and owns the actual voting/casting concerns.

That gives us a much stronger architecture than simply splitting one large controller into two large controllers.

**I would make no code changes yet.** The next sensible step is to turn this review into a **CodeController Responsibility & Precheck Matrix**, with each current check mapped to its authoritative source, current implementation, defect, target authority, and proposed extraction.
#
Yes. **I agree with this refactoring direction.** If this `CodeController` is now the real-election voting path, demo behavior should not be mixed into it at all.

The goal should be:

```text
CodeController
    ↓
REAL ELECTION ONLY
    ↓
Pre-voting checks
    ↓
Code verification
    ↓
Agreement
    ↓
VoteController
```

No:

```text
if ($election->type === 'demo')
if (session('selected_election_type') === 'demo')
demo reset
demo re-voting
demo-specific code creation
```

### What I would remove

#### 1. Remove demo from `isUserEligible()`

Current:

```php
if (session('selected_election_type') === 'demo') {
    return true;
}
```

Delete it.

The method should no longer know anything about demo:

```php
private function isUserEligible(User $user, ?Request $request = null): bool
{
    if ($request && $request->attributes->get('voter_slug')) {
        return true;
    }

    return $user && $user->can_vote === true;
}
```

However, **I would not keep this final version long-term**. We already know `users.can_vote` is legacy. The eventual target is `VotingEntitlement`, not `users.can_vote`.

---

#### 2. Remove demo reset from `getOrCreateCode()`

This entire block should disappear:

```php
if ($code && $code->has_voted && $election->type === 'demo') {
    ...
    'has_voted' => false,
    'vote_submitted' => false,
    'can_vote_now' => false,
    ...
}
```

This is particularly important.

A `CodeController` serving real elections should **never reset an already-consumed participation record**.

After removal:

```text
existing Code
     │
     ├── has_voted = true
     │       ↓
     │    blocked
     │
     └── has_voted = false
             ↓
          continue
```

---

#### 3. Remove demo-specific organisation comments/logic

This comment:

```php
// CRITICAL: Use withoutGlobalScopes() because demo elections have organisation_id=NULL
```

should disappear.

We should not design the real-election controller around demo data.

The real-election assumption becomes:

```php
$code = Code::withoutGlobalScopes()
    ->where('user_id', $user->id)
    ->where('election_id', $election->id)
    ->first();
```

Or, preferably, once tenancy is properly established, use the correct tenant-scoped query rather than `withoutGlobalScopes()`.

That is a separate cleanup decision.

---

#### 4. Remove all `election->type === 'demo'` branches

For example:

```php
if ($election->type === 'real' && $code->has_voted)
```

becomes simply:

```php
if ($code->has_voted)
```

This is a very important cleanup.

The controller should no longer say:

> "If real election, enforce one-vote rule."

It should simply say:

> **A voter cannot enter the voting workflow after participation has been consumed.**

That is the real-election invariant.

Same change applies in:

- `create()`
- `store()`
- `showAgreement()`
- `submitAgreement()`

---

### 5. Remove demo from the class documentation

Current documentation says:

```text
Election context:
- Defaults to first REAL active election
- Or uses election_id from session (if demo/test)
```

That should become simply:

```text
Election context is established by ElectionMiddleware.
The controller requires an election context and operates on real elections.
```

No demo explanation inside this controller.

---

### 6. Remove demo-specific code-generation behavior

The normal code creation path should be the only path:

```php
$code = Code::create([
    'user_id' => $user->id,
    'election_id' => $election->id,
    'organisation_id' => $election->organisation_id,
    ...
]);
```

There should be no alternate:

```text
demo → reset → regenerate → allow revote
```

path.

---

# The resulting `getOrCreateCode()` becomes much cleaner

Conceptually:

```php
private function getOrCreateCode(User $user, Election $election): Code
{
    $code = Code::withoutGlobalScopes()
        ->where('user_id', $user->id)
        ->where('election_id', $election->id)
        ->first();

    if (!$code) {
        $code = Code::create([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'organisation_id' => $election->organisation_id,
            'code_to_open_voting_form' =>
                $this->generateUniqueCodeForOrganisation(
                    $election->organisation_id
                ),
            'code_to_open_voting_form_sent_at' => now(),
            'has_code1_sent' => true,
            'client_ip' => $this->clientIP,
            'voting_time_in_minutes' => $this->votingTimeInMinutes,
            'is_code_to_open_voting_form_usable' => true,
            'can_vote_now' => false,
        ]);

        $this->sendVerificationCode($user, $code);
    }

    return $code;
}
```

Notice something important:

**I would also extract `sendVerificationCode()` next.**

Currently `getOrCreateCode()` is doing database persistence **and** notification delivery. Removing demo code makes that smell much more visible.

---

# 7. Remove demo condition from already-voted checks

Instead of:

```php
if ($election->type === 'real' && $existingCode && $existingCode->has_voted === true)
```

use:

```php
if ($existingCode && $existingCode->has_voted) {
    return $this->redirectToDashboard(
        'You have already voted in this election. Each voter can only vote once.'
    );
}
```

Same principle in `store()`:

```php
if ($code->has_voted) {
    return back()->withErrors([
        'voting_code' =>
            'You have already voted in this election. Each voter can only vote once.'
    ]);
}
```

And `showAgreement()` / `submitAgreement()`.

This makes the invariant much clearer.

---

# 8. Remove demo-related logging

These fields should disappear wherever they exist only to distinguish demo:

```php
'election_type' => $election->type,
```

if they are only diagnostic for demo/real branching.

More importantly, we should **simultaneously remove the plaintext code logging**:

```php
'code' => $code->code_to_open_voting_form
```

and:

```php
'expected' => $code->code_to_open_voting_form,
'submitted' => $submittedCode,
```

I would do that in this refactoring because this controller is a security boundary.

---

# 9. Do NOT remove `Election::type` from the domain yet

This distinction is important.

I recommend:

```text
REMOVE demo behavior from CodeController
```

but **not**:

```text
REMOVE Election.type from database/domain
```

There may still be a separate demo subsystem.

The architectural boundary should become:

```text
Real Election
     │
     ▼
CodeController
     │
     └── real voting workflow
```

and, if demo is still needed:

```text
Demo
     │
     ▼
separate demo application/workflow
```

That is much cleaner than:

```text
CodeController
    ├── real
    └── demo
```

---

# 10. This also resolves part of our S2 problem

This is actually significant for the ADR discussion.

Previously we had:

> Does demo reach the real voting persistence path?

If we remove demo behavior from the real `CodeController`, we move toward a much stronger structural separation.

But **this alone does not prove S2**.

We still need to verify:

```text
Demo route
   ↓
Demo controller/service
   ↓
?
   ↓
real Vote persistence?
```

If demo can still reach the real `save_vote()` path, we have not solved S2.

So the proper sequence is:

```text
1. Remove demo behavior from CodeController
2. Identify demo entry points
3. Trace demo → VoteController → persistence
4. Prove whether real vote persistence can be reached
5. Then resolve ADR S2
```

---

# 11. What the cleaned CodeController should look like conceptually

After this refactoring:

```text
CodeController
│
├── create()
│     ├── resolve voter
│     ├── resolve election
│     ├── membership/prechecks
│     ├── participation check
│     ├── obtain code
│     └── render
│
├── store()
│     ├── resolve voter/election
│     ├── prechecks
│     ├── participation check
│     ├── verify code
│     ├── mark verified
│     ├── record workflow state
│     └── redirect
│
├── showAgreement()
│     ├── prechecks
│     ├── code verification state
│     └── render/redirect
│
├── submitAgreement()
│     ├── prechecks
│     ├── code verification state
│     ├── accept agreement
│     └── redirect
│
└── helpers
      ├── getUser()
      ├── getElection()
      ├── getOrCreateCode()
      ├── verifyCode()
      ├── markCodeAsVerified()
      └── ...
```

And **zero demo branches**.

---

## My recommendation: do this as a separate clean-up gate

I would make this:

**PBDIGIT / CodeController Cleanup — Real Election Boundary**

Scope:

1. Remove all demo-specific branches.
2. Remove demo-specific comments.
3. Remove demo reset/re-voting behavior.
4. Convert `real && has_voted` to unconditional `has_voted`.
5. Remove demo session eligibility bypass.
6. Remove demo-specific organisation assumptions.
7. Remove plaintext verification-code logging.
8. Do **not** change ballot validation.
9. Do **not** change `VoteController`.
10. Do **not** introduce `PrecheckController` yet.
11. Characterization tests first.
12. Refactor.
13. Run CodeController regression tests.
14. STOP.

That gives us a clean foundation before we touch `VoteController`.

**I would approve this refactoring direction.** The next step should be to produce the **exact CodeController diff/rewritten version with demo code removed**, while deliberately leaving the precheck architecture unchanged for now.

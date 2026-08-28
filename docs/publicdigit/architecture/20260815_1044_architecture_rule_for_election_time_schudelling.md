Yes. I would make this a **developer guide about the business philosophy and engineering consequences**, not an implementation recipe. That way Session 3 can later implement it without the guide itself becoming an accidental architecture decision.

Below is a document you can give Claude to place under the Election developer-guide area.

---

# Election Voting Opportunity & Protocol Philosophy

**Document type:** Developer Guide / Domain Implementation Guidance
**Domain:** Election
**Status:** Governed design guidance — implementation must conform to adopted business rules and approved architecture
**Audience:** Developers, architects, reviewers, testers, AI engineering agents
**Scope:** Scheduled voting opportunities, Chief Election Officer authorization, schedule changes, expiry/cancellation, protocol recording
**Primary concern:** Election integrity, authority separation, auditability, non-reuse of authorization

---

# 1. Purpose

This guide defines how developers must understand and implement the business concept of a **voting opportunity**.

The purpose is not merely to control a button labelled **Proceed**.

The purpose is to ensure that the system can answer, after the election:

1. What was the published voting schedule?
2. Which voting opportunity did that schedule create?
3. Was the opportunity still valid?
4. Did the Chief Election Officer explicitly request voting?
5. Were all mandatory election conditions satisfied?
6. Did voting actually start?
7. If voting did not start, why not?
8. Was the opportunity still waiting, expired, cancelled, or superseded?
9. Was a later schedule created?
10. Can the complete history be reconstructed without relying on mutable current-state fields?

The system must never allow today's implementation behaviour to become the definition of the election rules.

The governing principle is:

> **The Chief Election Officer decides when to request progression; the Election Rules decide whether progression is permitted; the system enforces the result.**

---

# 2. Core Business Philosophy

The Election system must distinguish three fundamentally different concepts:

```text
                 SCHEDULE
                    │
                    ▼
          VOTING OPPORTUNITY
                    │
                    ▼
              AUTHORIZATION
                    │
                    ▼
          ELECTION RULES
                    │
                    ▼
              ACTUAL EVENT
```

These concepts must not be collapsed into one flag or one timestamp.

## 2.1 Schedule

The schedule answers:

> **When was voting planned to take place?**

Example:

> 20 August 2026, 10:00–12:00 Berlin time.

The schedule is a business fact.

It represents the intended voting opportunity.

---

## 2.2 Voting opportunity

A schedule creates a specific opportunity to conduct voting.

An opportunity is not simply:

> `voting_start_at = 10:00`

It represents a particular occurrence of the voting window.

For example:

> **Voting Opportunity 1**
> Schedule: 20 August 2026, 10:00–12:00 Berlin

If that opportunity later expires and a new schedule is created:

> **Voting Opportunity 2**
> Schedule: 21 August 2026, 11:00–13:00 Berlin

Opportunity 2 is **not** the same opportunity as Opportunity 1.

---

## 2.3 Authorization

Authorization answers:

> **Did the Chief Election Officer explicitly request that voting begin for this particular opportunity?**

Authorization is therefore contextual.

It is not:

> `chief_authorized = true`

It is conceptually:

> **The Chief authorized voting for Opportunity X.**

This distinction is fundamental.

---

## 2.4 Actual execution

Actual execution answers:

> **What actually happened?**

For example:

```text
Scheduled:
20 Aug 2026 10:00–12:00

Chief authorization:
20 Aug 2026 10:07

Actual voting start:
20 Aug 2026 10:07

Actual voting end:
20 Aug 2026 11:52
```

The actual start does not rewrite the published schedule.

---

# 3. The Fundamental Authority Model

The Election system must implement this authority chain:

```text
Chief Election Officer
        │
        │ requests progression
        ▼
Election Rules
        │
        │ evaluate mandatory conditions
        ▼
   ┌───────────────┐
   │ Permitted?    │
   └───────┬───────┘
       YES │ NO
           │
     ┌─────┴─────┐
     ▼           ▼
   START        REFUSE
  VOTING       + EXPLAIN
```

The Chief has **operational authority**.

The Chief does **not** have authority to override the Election Rules.

Therefore neither of the following models is acceptable:

```text
Clock → Voting
```

or:

```text
Chief → Voting
```

The correct model is:

```text
Time eligibility
       +
Chief authorization
       +
mandatory conditions
       +
no prohibition
       =
Voting may begin
```

This reflects the adopted business principle:

> **Time makes progression possible; authority and eligibility make progression valid.**

---

# 4. The Chief Is Not Sovereign

The Chief Election Officer has an important role, but that role must not become a monopoly over election legitimacy.

The Chief may:

* schedule;
* request progression;
* perform explicitly authorized administrative actions;
* correct the schedule where the Election Rules permit it;
* provide reasons where required;
* initiate a new voting opportunity where permitted.

The Chief may **not**:

* start voting merely because they press a button;
* bypass a mandatory condition;
* revive an expired voting opportunity;
* reuse authorization from a previous opportunity;
* use schedule manipulation to bypass an election condition;
* redefine what the Election Rules mean;
* erase or rewrite election history.

The system must enforce this separation.

---

# 5. Time Does Not Start Voting

The passage of time does not itself constitute authorization.

Suppose:

> Voting is scheduled for 20 August 2026 at 10:00.

At 10:00, the system may determine that the opportunity is now **eligible for consideration**.

It must not conclude:

> “Voting has started.”

Instead:

```text
Scheduled time reached
        ↓
Opportunity eligible for consideration
        ↓
Chief must request progression
        ↓
Election Rules evaluate
```

Therefore:

[
\text{scheduled time reached}
\not\Rightarrow
\text{Voting Active}
]

Instead:

[
\text{scheduled time reached}
+
\text{valid authorization}
+
\text{mandatory conditions}
\Rightarrow
\text{Voting Active}
]

---

# 6. Authorization Is Bound to the Opportunity

This is one of the most important integrity properties.

Authorization must never become a reusable global fact.

The system must preserve the conceptual invariant:

[
Authorization(O_1)
\not\Rightarrow
Authorization(O_2)
]

Example:

### Opportunity 1

> 20 August 2026, 10:00–12:00

The Chief does not successfully start voting.

The opportunity ends.

### Opportunity 2

> 21 August 2026, 11:00–13:00

The Chief must make a new decision.

The system must **not** reason:

> “The Chief was already authorized yesterday.”

That would create an authority-reuse vulnerability.

The new opportunity requires a new authorization.

---

# 7. Example: Failed Voting Start

Consider:

> Scheduled voting: 20.08.2026, 10:00 Berlin time.

The Chief cannot proceed because a mandatory condition is not satisfied.

The system must not silently wait forever.

The system must record the event.

Conceptually:

```text
Schedule created
        ↓
Opportunity available
        ↓
10:00 reached
        ↓
Chief cannot validly proceed
        ↓
Reason recorded
        ↓
Opportunity remains governed
        ↓
Eventually:
    ├── Voting started
    ├── Expired unused
    ├── Cancelled
    └── Superseded
```

The exact point at which an opportunity becomes expired or cancelled is a **Governance rule**, not something developers may invent.

---

# 8. Expired Is Not the Same as Cancelled

These concepts must remain distinct.

## Expired

Means:

> The permitted opportunity ended without valid voting progression.

Example:

> Voting opportunity was available until the governed deadline, but voting did not start.

This is an outcome of the opportunity reaching its temporal boundary.

---

## Cancelled

Means:

> An authorized decision or rule deliberately terminated the opportunity.

For example:

> The election was cancelled because a governing condition made continuation impossible.

Cancellation is an explicit business event.

It must not be used merely because a developer does not know what else to call a situation.

---

# 9. Superseded Is Different Again

Suppose:

```text
Opportunity 1
20 Aug 10:00–12:00
```

is replaced by:

```text
Opportunity 2
21 Aug 11:00–13:00
```

The first opportunity should not disappear.

Its history should show that it was **superseded**, according to the applicable governance rules.

The system must preserve the relationship:

```text
Opportunity 1
      │
      │ superseded by
      ▼
Opportunity 2
```

This is preferable to simply changing:

```text
20 Aug → 21 Aug
```

inside one mutable record and pretending the original schedule never existed.

---

# 10. Temporarily Blocked Is Not Automatically Expired

A missing condition does not necessarily mean that the opportunity has expired.

For example:

> Voting scheduled for 10:00–12:00.

At 10:15:

> Chief requests progression.

The system discovers:

> No approved candidate.

The system must refuse progression.

It must **not automatically invent**:

> “Cancelled.”

Nor:

> “Expired.”

Nor:

> “Voting blocked forever.”

The correct outcome depends on the Election Rules.

Therefore developers must distinguish:

```text
NOT CURRENTLY PERMITTED
```

from:

```text
TERMINATED OPPORTUNITY
```

This distinction is essential.

---

# 11. Every Opportunity Must Reach a Governed Outcome

The system should never leave the historical meaning of a voting opportunity ambiguous.

A conceptual lifecycle is:

```text
SCHEDULED
    │
    ▼
AVAILABLE
    │
    ├───────────────┐
    │               │
    ▼               ▼
AUTHORIZED       NOT AUTHORIZED
    │               │
    ▼               │
RULES CHECK         │
    │               │
 ┌──┴───┐            │
 │      │            │
PASS   FAIL           │
 │      │            │
 ▼      ▼            │
VOTING  WAITING       │
STARTED              │
                       │
                       ▼
              EXPIRED / CANCELLED /
              SUPERSEDED
```

The exact transitions must follow the approved business rules.

This diagram is **conceptual**, not a request to create these exact technical states.

---

# 12. Schedule Correction Does Not Start Voting

This is a critical anti-circumvention rule.

Suppose:

> 20 August, 10:00

has passed.

The Chief corrects the schedule to:

> 21 August, 11:00.

The schedule correction itself must **not** mean:

> “Voting is now authorized.”

Instead:

```text
Schedule correction
        ↓
New/current voting opportunity
        ↓
Opportunity becomes eligible at its governed time
        ↓
Chief must explicitly request progression
        ↓
Election Rules evaluate
```

Therefore:

[
ScheduleCorrection
\not\Rightarrow
VotingAuthorization
]

and:

[
ScheduleCorrection
\not\Rightarrow
VotingActive
]

This prevents schedule manipulation from becoming an indirect voting authority.

---

# 13. Historical Schedule Must Not Be Silently Overwritten

The system must distinguish:

> **What was scheduled**

from:

> **What actually happened**

For example:

```text
Published schedule:
20 Aug 10:00–12:00

Actual authorization:
20 Aug 10:14

Actual voting start:
20 Aug 10:14

Actual voting end:
20 Aug 11:48
```

The actual events do not rewrite the schedule.

This is important for post-election reconstruction.

Election event logging standards similarly emphasize recording significant election events, timestamps, success/failure where applicable, and the responsible user/process. NIST's election-event logging specification specifically treats events such as opening/closing voting, administrator actions, and errors as relevant election-log material. ([NIST Seiten][1])

---

# 14. Protocol Is Part of Election Truth

The election protocol is not merely a debugging log.

It is part of the evidence needed to reconstruct the election's conduct.

An audit trail is intended to allow later reconstruction of steps followed and verification of actions taken. ([NIST Seiten][2])

Therefore developers must think in terms of:

> **What would an independent reviewer need to know six months later?**

rather than:

> “What log message helps me debug this controller?”

---

# 15. What Should Be Recorded

For each material voting-opportunity event, the protocol should conceptually preserve:

* election identity;
* voting opportunity identity;
* schedule/revision identity;
* event type;
* business event time;
* system-recorded time where relevant;
* actor or responsible process, where applicable;
* success/failure;
* reason;
* relevant failed condition;
* resulting business outcome;
* relationship to a previous or subsequent opportunity.

NIST's election logging specification identifies event identity/type, timestamp, success/failure, triggering user where applicable, and requested resources among useful audit-event characteristics. It also emphasizes precise, ordered timestamps. ([NIST Seiten][1])

---

# 16. Protocol Must Preserve History

A correction should not erase the original event.

For example, do not turn:

```text
20 Aug — scheduled
```

into:

```text
21 Aug — scheduled
```

without preserving the fact that the 20 August schedule existed.

Instead, the history should conceptually show:

```text
20 Aug — Schedule created
20 Aug — Opportunity not used
20 Aug — Reason recorded
21 Aug — New schedule created
21 Aug — New opportunity
```

The exact technical representation belongs to Architecture.

The business requirement is:

> **An authorized correction may change what is currently valid, but it must not erase what previously happened.**

This principle aligns with the broader election-audit emphasis on preserving evidence and maintaining documented chains of custody and auditability. ([Bipartisan Policy Center][3])

---

# 17. Failed Attempts Are Also Evidence

A failed attempt to start voting is not equivalent to “nothing happened.”

For example:

> Chief pressed Proceed.

The system determined:

> No admitted voters.

The system refused the request.

The protocol should preserve that fact.

Conceptually:

```text
Event:
Chief requested voting progression

Result:
Refused

Reason:
No admitted voters

Opportunity:
20 Aug 10:00–12:00

Outcome:
Voting did not start
```

This makes the system explainable.

It also prevents the later historical question:

> “Why didn't voting start?”

from having no answer.

---

# 18. Do Not Log Sensitive Voting Information

Auditability does not mean logging everything.

Protocol records must not expose:

* ballot selections;
* vote contents;
* secret voting credentials;
* unnecessary voter information;
* information that could compromise ballot secrecy.

The principle is:

> **Record enough to reconstruct election administration without compromising voter secrecy.**

Election audit guidance emphasizes the value of event logs while also recognizing privacy and security constraints. ([votingsystems.cdn.sos.ca.gov][4])

---

# 19. Do Not Use Mutable State as Historical Truth

A current status answers:

> **What is true now?**

The protocol answers:

> **What happened?**

These are different questions.

A developer must not assume that a mutable field can serve both purposes.

For example, a field representing the current voting window should not automatically be treated as the complete historical record of every schedule that existed.

Likewise:

```text
voting_active = true
```

does not prove:

* who authorized it;
* for which schedule;
* when authorization occurred;
* which conditions were evaluated;
* whether the schedule was later changed.

Those facts require appropriate evidence.

---

# 20. Anti-Patterns

## 20.1 Clock starts voting

```text
if (now >= votingStart) {
    state = VotingActive;
}
```

**Forbidden unless explicitly authorized by Election Rules.**

The adopted model requires explicit Chief authorization for starting voting.

---

## 20.2 Chief button starts voting unconditionally

```text
if (chiefPressedProceed) {
    state = VotingActive;
}
```

**Forbidden.**

The Chief's request must pass the Election Rules.

---

## 20.3 Reusable authorization flag

```text
chiefAuthorized = true
```

followed by:

```text
if (windowIsOpen && chiefAuthorized) {
    startVoting();
}
```

**Dangerous.**

It risks allowing an authorization from one voting opportunity to authorize another.

---

## 20.4 Schedule correction creates authorization

```text
changeSchedule(...)
→ startVoting()
```

**Forbidden.**

Schedule correction and voting authorization are separate business actions.

---

## 20.5 Overwriting history

```text
voting_start = newDate;
```

with no preservation of the previous schedule.

**Forbidden where the field represents a historical/business record.**

---

## 20.6 Treating every refusal as cancellation

```text
if (!conditionsSatisfied) {
    cancelOpportunity();
}
```

**Forbidden unless the Election Rules explicitly require cancellation.**

Refusal, blocking, expiry and cancellation have different meanings.

---

## 20.7 Making the Chief the exception path

Never implement:

```text
normal_user → rules
chief → bypass
```

The Chief's authority is itself governed.

---

# 21. Domain Invariants

The following invariants should guide implementation and review.

### Invariant 1 — No clock-only activation

[
ScheduledTimeReached
\not\Rightarrow
VotingActive
]

---

### Invariant 2 — No Chief-only activation

[
ChiefAuthorization
\not\Rightarrow
VotingActive
]

The rules must still pass.

---

### Invariant 3 — Valid activation requires all required authority

[
VotingActive
\Rightarrow
ValidSchedule
\land
ValidAuthorization
\land
MandatoryConditionsSatisfied
]

---

### Invariant 4 — Authorization is opportunity-specific

[
Authorization(O_1)
\not\Rightarrow
Authorization(O_2)
]

---

### Invariant 5 — Schedule correction does not authorize

[
ScheduleCorrection
\not\Rightarrow
VotingAuthorization
]

---

### Invariant 6 — Expired opportunities cannot be reused

[
Expired(O_1)
\Rightarrow
CannotStartVotingUsing(O_1)
]

---

### Invariant 7 — Historical events survive later correction

[
Correction
\not\Rightarrow
DeletionOfHistoricalEvent
]

---

### Invariant 8 — Protocol explains material outcomes

Every material voting-opportunity outcome must have reconstructable evidence.

---

### Invariant 9 — Chief cannot override mandatory rules

[
ChiefRequest \land MandatoryRuleFailure
\Rightarrow
Refused
]

---

### Invariant 10 — Schedule correction cannot circumvent a refusal

A Chief must not be able to change a schedule merely to turn an otherwise prohibited progression into an allowed one.

This is especially important because the adopted governance principle explicitly prevents administrative authority or schedule manipulation from bypassing mandatory election conditions.

---

# 22. Developer Decision Rule

When implementing any Election lifecycle behaviour, ask these questions in order:

### Question 1

**What business event is occurring?**

Not:

> Which controller method am I in?

But:

> Is this scheduling, authorization, correction, expiry, cancellation, or actual voting?

---

### Question 2

**Who has authority to request it?**

Identify the responsible business role.

---

### Question 3

**What rules decide whether it is permitted?**

Do not let the requesting actor answer this question.

---

### Question 4

**Which voting opportunity does the action belong to?**

Never assume the current schedule is sufficient.

---

### Question 5

**Does this action change the future, or record the past?**

A schedule correction may affect the future.

A protocol event records the past.

Do not confuse them.

---

### Question 6

**Can this action accidentally revive an old authorization?**

If yes, stop.

---

### Question 7

**Can an administrator use this action to bypass a mandatory Election Rule?**

If yes, stop.

---

### Question 8

**Could an independent reviewer reconstruct what happened?**

If no, the implementation is not yet adequate.

---

# 23. Testing Philosophy

Tests must test **business invariants**, not merely implementation branches.

Examples:

### Scenario A — clock reaches scheduled time

Expected:

> Voting does not start without valid Chief authorization.

---

### Scenario B — Chief requests before mandatory conditions are satisfied

Expected:

> Request refused.

---

### Scenario C — Chief requests after conditions become satisfied

Expected:

> Voting may start if the schedule is still valid.

---

### Scenario D — old opportunity expires

Expected:

> Old authorization cannot start voting later.

---

### Scenario E — new schedule created

Expected:

> New opportunity requires new authorization.

---

### Scenario F — schedule corrected

Expected:

> Correction does not itself start voting.

---

### Scenario G — failed progression

Expected:

> Refusal and reason are recorded.

---

### Scenario H — cancellation

Expected:

> Cancellation is distinguishable from expiry.

---

### Scenario I — supersession

Expected:

> Previous opportunity remains historically reconstructable.

---

# 24. Architecture Boundary

This guide defines **business meaning and implementation constraints**.

It does **not** prescribe:

* database tables;
* Laravel models;
* state-machine classes;
* event names;
* repository interfaces;
* cache structures;
* cron implementation;
* queue implementation;
* exact aggregate boundaries;
* exact technical representation of schedule revisions.

Those belong to Architecture.

Architecture must take the business rules and produce a representation that makes prohibited behaviour structurally difficult or impossible.

---

# 25. Governance Boundary

Developers must not invent answers to unresolved business questions.

If implementation encounters a question such as:

> “When exactly does an opportunity expire?”

and Governance has not decided it:

**stop and escalate.**

Do not decide:

```text
12:00 → expired
```

merely because `voting_ends_at` exists.

Similarly, do not decide:

> “A schedule correction automatically cancels the previous authorization”

unless Governance has adopted that rule.

Architecture may propose safe structural defaults, but a structural default must not silently become business policy.

---

# 26. Verification Boundary

Verification must independently establish that the implementation respects the business invariants.

A green implementation test written by the implementation session is not sufficient evidence by itself.

The independent verification session should challenge at least:

* clock-only activation;
* late reuse of authorization;
* schedule correction;
* old/new opportunity separation;
* Chief override attempts;
* missing mandatory conditions;
* failed progression;
* expiry;
* cancellation;
* supersession;
* protocol completeness.

The goal is not merely:

> “Does the code work?”

It is:

> **“Can the system be demonstrated not to violate the Election's authority model?”**

---

# 27. Why This Philosophy Matters

Election systems are different from ordinary workflow applications.

In an ordinary business application, it may be acceptable to say:

> “The administrator changed the date, so the process continues.”

In an election system, that can alter the conditions under which citizens are allowed to vote.

Therefore:

> **Authority must be explicit.**

> **Conditions must be enforceable.**

> **Time must have a defined business meaning.**

> **Past events must remain reconstructable.**

> **Previous authorization must not silently become future authorization.**

> **Administrative convenience must never become election authority.**

Election audit guidance similarly treats election-event records as evidence for reconstructing how election operations were conducted, and NIST's election-event logging work emphasizes significant events, timestamps, outcomes, and responsible actors. ([NIST Seiten][1])

---

# 28. The Mental Model Developers Should Remember

Do not think:

```text
DATE/TIME
    ↓
STATE
```

Do not think:

```text
CHIEF BUTTON
    ↓
STATE
```

Think:

```text
                 BUSINESS SCHEDULE
                        │
                        ▼
                VOTING OPPORTUNITY
                        │
                 time becomes relevant
                        │
                        ▼
                CHIEF REQUESTS
                        │
                        ▼
                ELECTION RULES
                        │
              ┌─────────┴─────────┐
              │                   │
           PERMITTED            REFUSED
              │                   │
              ▼                   ▼
        VOTING STARTS       REASON RECORDED
              │
              ▼
        ACTUAL EXECUTION
              │
              ▼
        PROTOCOL / HISTORY
```

And if the opportunity ends without voting:

```text
VOTING OPPORTUNITY
        │
        ▼
   no valid start
        │
        ▼
governed terminal outcome
        │
   ┌────┼────────┐
   ▼    ▼        ▼
EXPIRED CANCELLED SUPERSEDED
        │
        ▼
   protocol records it
```

The central principle is:

> **The schedule creates an opportunity. The Chief requests progression. The Election Rules determine whether progression is permitted. The system enforces that decision. The protocol preserves what happened.**

That is the philosophy I would want every developer, architect, tester, and AI coding agent to understand before touching the Election lifecycle.

[1]: https://pages.nist.gov/ElectionEventLogging/?utm_source=chatgpt.com "NIST SP 1500-101 Election Event Logging Common Data Format Specification Draft Version 1.0"
[2]: https://pages.nist.gov/ElectionGlossary/?utm_source=chatgpt.com "NIST SP 1500-10x NIST Election Glossary Draft Version 1.0"
[3]: https://bipartisanpolicy.org/report/bipartisan-principles-for-election-audits/?utm_source=chatgpt.com "Bipartisan Principles for Election Audits • Bipartisan Policy Center"
[4]: https://votingsystems.cdn.sos.ca.gov/oversight/directives/audit-log-report.pdf?utm_source=chatgpt.com "Voting Systems Audit Log Study"

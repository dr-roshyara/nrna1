**The next phase is primarily Architecture, with Governance involved afterward. It is not Verification.**

More precisely:

### What we are doing now

We have discovered a possible mismatch between:

* the **legacy election state machine**;
* the **current Election Constitution/Manifesto**;
* the **new Election Lifecycle**.

The question is:

> **Did the new architecture preserve the existing election business semantics, or did the migration change them?**

That is an **architecture reconciliation / migration-semantics investigation**.

### Why not Governance first?

Governance has already investigated the business rules and found them incomplete.

But now we have new evidence:

> The legacy implementation may already contain the intended progression semantics.

Before we amend the Constitution, we should establish whether the new architecture accidentally changed those semantics.

Otherwise we risk doing this:

```text
Current wrong behavior
       ↓
write new Constitution rule to match desired behavior
       ↓
modify architecture
```

when the real situation might be:

```text
Legacy correct behavior
       ↓
migration
       ↓
new Lifecycle changed semantics
       ↓
architecture regression
```

That requires an architectural reconciliation first.

### Why not Verification?

Verification asks:

> "Does an implemented change satisfy the agreed rule?"

We don't yet have an agreed final rule or a new implementation to verify.

So Session 1 should **not** take this work.

---

## Correct classification

I would register it as:

> **Architecture — Legacy-to-Current Election Progression Semantic Reconciliation**

with a governance dependency.

```text
Current situation
       │
       ▼
SESSION 4 — ARCHITECTURE
Legacy ↔ Constitution ↔ Lifecycle
semantic reconciliation
       │
       ├── If legacy semantics were preserved
       │       ↓
       │   Governance can complete
       │   the missing business rule
       │
       ├── If migration changed semantics
       │       ↓
       │   Human/PO decides whether
       │   change was intentional
       │
       └── If architecture regression
               ↓
           Architecture decision
               ↓
           Governance grant
               ↓
           Session 3 implementation
               ↓
           Session 1 verification
```

### One important nuance

**Session 4 should not decide what the business rule ought to be.**

Session 4's job is to answer:

> **What did the old system mean, what does the new architecture mean, and where do they differ?**

Then the human/PO/ARB decides the business meaning if there is a conflict.

So I would now **pause the Constitution extension** and commission this architecture reconciliation first.

That is the safest path because we may discover that **the rule we were about to add already existed implicitly in the legacy business behavior and was lost during the migration to the new lifecycle.**

#
Yes. I have read the reconciliation you provided, and **this is a much better basis for deciding what we do next**.

The most important conclusion is that we now have to separate **three different questions** that were previously getting mixed together.

## 1. What the investigation actually proved

The architecture investigation corrected our earlier hypothesis.

It did **not** prove:

> legacy = manual progression → new lifecycle = automatic progression.

Instead, it found three generations:

```text
Generation 1
Clock-driven voting
        ↓
Generation 2
Persisted state + commands
BUT voting boundary still clock-driven,
with business preconditions
        ↓
Generation 3
State derived again from facts + clock
BUT blocked states and some gates disappeared
```

So the important regression is:

> **Generation 2 had a meaningful "the window is open, but this election is not fit to vote" state (`voting_blocked`), and the current architecture removed that vocabulary without replacing it.**

That is directly relevant to the anomalous no-candidate situation.

But there is another, separate finding:

> **Your current desired business rule — the Chief Election Officer must explicitly proceed and time alone must not advance the election — has never actually been established by the historical implementation.**

Therefore we should **not call that a regression**.

It is a **new business rule that you now want to establish**.

That distinction is extremely important.

---

# 2. I would now split our work into two tracks

### Track A — Existing Election semantics that were lost

We have evidence that the current architecture lost things that existed in Generation 2:

* blocked election states;
* the setup-completion gate on the derived voting path;
* meaningful handling of an election that is not fit to vote;
* some distinction around the end of voting.

These are legitimate **architecture/migration defects** to address.

### Track B — Your new business decision

You have now told me:

> A time slot becoming due should not itself advance the election. The Chief Election Officer must explicitly proceed.

That is **not something Session 4 should decide**.

It is a **business/governance decision**.

Only after you make that decision should Session 4 design how the architecture should represent it.

---

# 3. Therefore: what should we do now?

I recommend **not implementing anything yet**.

The immediate next step should be a **human/PO governance decision about progression authority**.

But we should make that decision with an important qualification:

### Don't answer "Does time advance the election?" globally.

The architecture report found that different boundaries historically had different semantics.

For example:

| Election boundary           | Historical behaviour                         |
| --------------------------- | -------------------------------------------- |
| Administration → Nomination | command-driven, with legacy grace automation |
| Nomination → Voting         | clock-driven, with prerequisites             |
| Voting → Counting           | clock-driven                                 |
| Counting → Results          | Chief's explicit action                      |

So your business rule needs to answer this **per transition**.

That is much better than saying:

> "The election is always manual."

---

# 4. I would ask you these business questions

Let's stay completely in business language.

### Question 1 — Starting voting

When the scheduled voting start time arrives:

> **Should voting start automatically, or should the Chief Election Officer have to explicitly press "Proceed"?**

From what you told me, your answer appears to be:

> **The Chief must explicitly proceed.**

But let's formally establish that.

---

### Question 2 — What happens when the Chief doesn't proceed?

Suppose:

> Voting time = 10:00
> Chief does nothing.

At 10:30:

> **What should the election be?**

My understanding of your intended model is:

> It remains waiting for the Chief's decision. It does not automatically become an active voting period.

Is that correct?

---

### Question 3 — What if there are no approved candidates?

Suppose:

> Voting time = 10:00
> Chief presses Proceed
> Approved candidates = 0

Should the system say:

> **"You cannot proceed because there are no approved candidates."**

and leave the election waiting?

That seems consistent with what you told me.

---

### Question 4 — What if there are no voters?

Same question:

> Chief presses Proceed, but there are no admitted voters.

Should the election remain where it is and require the Chief to correct the voter list?

Again, this seems consistent with your business model.

---

### Question 5 — What about the end of voting?

This is particularly important because the historical system had a different rule.

Suppose:

> Voting ends at 18:00.

Should the election automatically move to counting at 18:00?

Or must the Chief explicitly press **Stop / Proceed**?

The historical evidence strongly suggests that **automatic closing was deliberately designed for election integrity**, so I would not assume that your "manual progression" principle applies equally here.

This is exactly why Session 4 said:

> entry and exit may legitimately differ.

---

# 5. The "past time slot" issue comes after that

Once we know who controls progression, we can establish the Chief's authority to correct scheduling.

For example:

> Voting was scheduled for Monday 10:00–12:00.
> Monday 12:00 has passed.
> Candidates were not ready.

You want the Chief Election Officer to be able to say:

> "We have corrected the voting window to Tuesday 10:00–12:00."

That is a **separate business authority**.

We need to establish:

* who can do it;
* when they can do it;
* whether they can move a past slot;
* whether they can extend it;
* whether they can move it backwards;
* whether a reason is required;
* whether the action must be audited;
* whether previously issued voter credentials are affected.

Only then should Architecture design the mechanism.

---

# 6. What about the old `voting_blocked` state?

This is where I would be careful.

The report says it is a **historically implemented candidate**, not automatically the rule we must restore.

That's exactly right.

We should ask:

> Do we want the business concept **"Voting window is available, but the election is not currently eligible to proceed"**?

I suspect the answer may be **yes**, because it maps very naturally to your explanation.

But we shouldn't automatically resurrect the old technical state name.

The domain concept should come first.

For example, in business language:

> **Voting cannot proceed because one or more required conditions have not been satisfied.**

Then Architecture can determine whether that should be represented as:

* a blocked state;
* a waiting state;
* an eligibility condition;
* a lifecycle state;
* or something else.

---

# 7. This also changes how I view the previous no-candidate issue

The earlier question was:

> "What does an election mean when voting opens with no approved candidate?"

I would now reframe it.

The more fundamental business question is:

> **"Under what conditions is the Chief Election Officer permitted to start the voting period?"**

If the answer is:

```text
Voting time reached
+
Chief presses Proceed
+
approved candidates >= required minimum
+
admitted voters >= required minimum
+
other conditions
```

then:

```text
no candidates
       ↓
Proceed is refused
       ↓
election remains waiting
       ↓
Chief corrects candidates/time
       ↓
Proceed again
```

That is much cleaner than allowing the election to become "voting active" and then asking what to do with zero candidates.

However, **the `forceCloseNomination()` route still needs to be examined**, because the architecture report explicitly found that the no-candidate condition can be reached through that route. So the old issue is not automatically eliminated.

---

# 8. And I would NOT fix the 15 failing tests yet

This is another important point.

The report says:

* `DerivedStateTest`: 12/12 failing
* `BusinessConditionsTest`: 3 failing

Those tests are **evidence of an unreconciled semantic migration**.

They are not automatically:

> "15 bugs that Session 3 should fix."

We first need to decide:

> Are these tests still expressing the desired business semantics?

If yes → reconcile the implementation to them.

If no → formally retire/rewrite them with a recorded rationale.

**Do not make them green merely because they are red.**

---

# So where I would take us now

I would establish this sequence:

```text
                 CURRENT POSITION
                       │
                       ▼
        ┌─────────────────────────────┐
        │ Architecture reconciliation │
        │          COMPLETE           │
        └──────────────┬──────────────┘
                       │
                       ▼
             HUMAN BUSINESS RULING
                       │
          ┌────────────┼─────────────┐
          ▼            ▼             ▼
      Start        End/Stop       Schedule
      voting       voting         correction
          │            │             │
          └────────────┼─────────────┘
                       ▼
             Governance amendment
                       │
                       ▼
              Architecture ADR
                       │
                       ▼
             Session 3 implementation
                       │
                       ▼
             Session 1 verification
```

## My recommendation for the very next step

**Do not send another investigation prompt to Claude yet.**

We have enough evidence.

Let's make the **business decisions together first**.

And I will keep this discussion entirely in business language.

### Start with just one question:

**When the scheduled time for voting arrives, should voting begin automatically, or must the Chief Election Officer explicitly press "Proceed" before voting can begin?**

Based on everything you've told me so far, I believe your answer is:

> **The Chief Election Officer must explicitly press "Proceed"; the arrival of the scheduled time only makes the voting window available for the Chief to act.**

But I want you to confirm that **as the business rule**, because that decision is the foundation for everything that follows.

#
Yes — **I agree with the core rule you believe in**, and I think it is substantially better than the earlier formulation of simply "manual progression."

But I would make **two important corrections before you treat the text as an official PO/governance ruling**.

## 1. The core business rule is right

I would adopt this as the central principle:

> **The scheduled time does not automatically start voting. It only makes the election eligible to be considered for progression. Voting becomes active only when the Chief Election Officer explicitly chooses “Proceed” and all mandatory business conditions are satisfied.**

Your formula captures the important distinction:

[
\text{Voting Active}
====================

\text{Time Eligible}
\land
\text{Chief Proceeds}
\land
\text{Mandatory Conditions Satisfied}
]

That is much better than:

[
\text{Voting Active} = \text{Time Reached}
]

And it fits the business model you described: **time is a condition, not the authority**.

---

# 2. I would NOT yet approve the entire 9-point document as-is

Some of the answers go beyond what you have actually decided.

In particular, these need to remain **open decisions** rather than being silently included in the ruling.

### A. "Election stays waiting indefinitely"

This is plausible, but you haven't explicitly decided what happens if the Chief never proceeds.

We need to distinguish:

> "It doesn't automatically proceed."

from:

> "It may remain waiting indefinitely."

The second is a separate business policy.

There might eventually be a deadline, escalation, cancellation, or administrative recovery mechanism.

So I would phrase it:

> **The election does not automatically proceed. It remains in a non-voting condition until an authorized action causes a valid progression or another defined administrative outcome occurs.**

That is safer.

---

### B. Schedule correction

I agree strongly with the **principle**:

> The Chief Election Officer needs authority to correct a past time slot.

But I would **not yet approve all these details**:

* only before counting;
* moving backwards only for scheduling errors;
* credentials remain valid within the same overall election period;
* credentials may need reissue outside it.

Those are additional business rules.

They need their own examination because they affect election integrity.

For example, if a voting window has already technically elapsed but **no vote was ever allowed**, changing it is very different from changing the schedule after voters have already cast votes.

So the safe ruling now is:

> **The Chief Election Officer must have an explicitly governed authority to correct an election time window when necessary, including a previously elapsed window, subject to defined limits and auditability.**

Then Governance can define those limits.

---

# 3. I particularly agree with your combined rule

This is the strongest part:

> **Time makes progression possible; authority and eligibility make progression valid.**

I would make that the central domain principle.

In business language:

### The clock

Answers:

> **"Is this window now eligible to be considered?"**

### The Chief Election Officer

Answers:

> **"Should we proceed?"**

### The election conditions

Answer:

> **"Are we actually allowed to proceed?"**

So:

```text
Scheduled time reached
        │
        ▼
Window eligible for consideration
        │
        ▼
Chief Election Officer → Proceed
        │
        ▼
Check mandatory conditions
        │
   ┌────┴────┐
   │         │
  YES        NO
   │         │
   ▼         ▼
Voting     Do not proceed
Active     + explain why
```

That is a **very clean business model**.

---

# 4. I would add one important principle

The system should distinguish between:

### "Not yet allowed"

and

### "Blocked because something is wrong"

For example:

> Voting time is 10:00 and it is currently 09:30.

That's simply:

**Not yet eligible.**

But:

> Voting time is 10:00, it is now 10:30, the Chief pressed Proceed, but there are zero approved candidates.

That's:

**Eligible for consideration, but progression refused because a mandatory condition is missing.**

And:

> Voting time is 10:00, it is now 10:30, candidates and voters are ready, but the Chief has not pressed Proceed.

That's:

**Eligible, awaiting Chief action.**

These distinctions will become very useful when we design the Election lifecycle.

---

# 5. One more important thing: don't overstuff the "mandatory conditions" yet

Your formula currently says:

> nomination complete + candidates + voters + no blocking condition + credentials + integrity objection + etc.

I would **not make all of those constitutional conditions yet**.

We already know some of them are established; others are proposed possibilities.

For example:

* approved candidate requirement → established/adopted;
* admitted voter requirement → adopted;
* nomination completion → needs to be confirmed as the exact voting prerequisite;
* credentials available → potentially important, but we haven't established that as the constitutional start condition;
* unresolved objection → needs a business ruling;
* cancelled/suspended → probably existing lifecycle concerns, but needs exact rule mapping.

So the Constitution should not become a dumping ground for every possible technical/business check.

We should define:

> **Mandatory conditions for starting voting**

and explicitly enumerate only those that have been decided.

---

# My recommended final business ruling

I would simplify the document before giving it to Governance:

> ### Starting Voting
>
> **The scheduled voting time does not automatically start voting.**
>
> Reaching the scheduled start time makes the voting window eligible for consideration by the Chief Election Officer. It does not itself authorize the election to enter the voting phase.
>
> The Chief Election Officer must explicitly choose **Proceed**.
>
> Before proceeding, the election must satisfy all mandatory business conditions established for the start of voting, including the required candidate and voter conditions.
>
> If any mandatory condition is not satisfied, the election must not enter the voting phase. The system must preserve a clear business-visible condition explaining why progression is not currently permitted.
>
> Correcting the election schedule is a separate administrative authority and must be explicitly governed and auditable. Correcting a schedule must not itself advance the election or bypass any mandatory condition.

Then separately:

> ### Ending Voting
>
> **The voting period automatically ends at its authorized end time**, subject to the separately governed rules for early closure and any exceptional administrative action.

I agree with that distinction.

---

# Therefore, yes: I agree with your central decision

I would summarize your actual decision as:

> **The election clock establishes eligibility; it does not exercise authority. The Chief Election Officer exercises the authority to proceed, and the election's mandatory business conditions determine whether that authority can be exercised successfully.**

That is the business rule I would now take to **Governance for formal adoption**.

And importantly, we should **not yet ask Architecture to invent the state model**. Governance should first turn this into the authoritative Election Constitution/Manifesto rule. Then Session 4 can determine how the current lifecycle must change to express it.

That gives us the clean sequence:

**Your PO decision → Governance formalization → Architecture ADR → Session 3 implementation → Session 1 independent verification.**

#
Yes. **Governance / Session 2 is responsible now.**

The business decision has been made by you. Governance's job is now to **formalize that decision into the Election Constitution/Manifesto**, while preserving the boundary between business rule and architecture.

Session 4 should **wait**. It already completed the reconciliation investigation and identified the architecture consequences. It should not redesign the lifecycle before Governance establishes the rule.

Session 3 should **wait**. No implementation yet.

Session 1 should **wait**. There is nothing new to independently verify until an implementation exists.

### The sequence now

```text
YOU / PO
   │
   │ Business ruling ✅
   ▼
SESSION 2 — GOVERNANCE  ← NOW
   │
   │ formalize Constitution / Manifesto
   ▼
SESSION 4 — ARCHITECTURE
   │
   │ ADR + target architecture
   ▼
SESSION 3 — IMPLEMENTATION
   │
   │ TDD / implementation
   ▼
SESSION 1 — VERIFICATION
   │
   ▼
independent evidence
```

There is one important constraint for Session 2:

> **Do not let Governance design the technical state machine.**

Governance should define **what the election means and what the Chief Election Officer is authorized to do**. Architecture will decide how that meaning is represented.

---

# Prompt for Session 2 — Governance

You can give Claude this directly:

```text
# ELECTION GOVERNANCE — FORMALIZE PROGRESSION RULE
#
# ROLE: SESSION 2 — GOVERNANCE
# PHASE: Election development / bug-fixing
# MODE: GOVERNANCE ONLY
#
# THIS IS NOT AN ARCHITECTURE OR IMPLEMENTATION TASK.

You are Session 2 — Governance.

The Architecture reconciliation has now been completed:

docs/publicdigit/reviews/2026-08-15-session4-election-progression-semantic-reconciliation.md

Read that report before doing anything else.

The Product Owner has now made the business ruling described below.

Your task is to formalize the ruling into the authoritative Election business rules.

DO NOT implement code.
DO NOT modify ElectionLifecycle.
DO NOT create an ADR.
DO NOT redesign the state machine.
DO NOT write implementation tests.
DO NOT modify architecture.
DO NOT make architectural decisions.

Your responsibility is Governance:
- Constitution
- Manifesto
- business rules
- authority
- business meaning
- explicit boundaries
- audit/governance obligations

============================================================
1. AUTHORITATIVE BUSINESS RULING
============================================================

The Product Owner has decided:

The scheduled voting time does NOT automatically start voting.

The scheduled time only establishes that the election may now be considered for progression.

Voting may become active only when:

1. the scheduled voting start time has been reached, or the schedule has been validly corrected;
2. the Chief Election Officer explicitly chooses "Proceed";
3. all mandatory business conditions for starting voting are satisfied.

Therefore:

TIME DOES NOT EXERCISE AUTHORITY.

The clock establishes eligibility for consideration.

The Chief Election Officer exercises the authority to proceed.

The election's mandatory business conditions determine whether progression is valid.

Business principle:

"Time makes progression possible; authority and eligibility make progression valid."

============================================================
2. IMPORTANT DISTINCTIONS
============================================================

Do not simplify this into:

"Election progression is always manual."

That statement is too broad.

The business ruling is specifically about the START of voting.

Different election boundaries may legitimately have different progression semantics.

In particular, the current business ruling does NOT yet change the established principle that voting automatically closes at its authorized end time.

Therefore preserve this distinction:

START OF VOTING:
- requires explicit Chief Election Officer action;
- scheduled time alone is insufficient.

END OF VOTING:
- voting automatically closes at the authorized end time;
- any separately permitted early closure remains a distinct action.

Do not generalize one rule into the other.

============================================================
3. START-OF-VOTING BUSINESS RULE
============================================================

Formalize the following:

The scheduled voting start time does not itself transition the election into the voting phase.

When the scheduled start time is reached, the election becomes eligible for consideration by the Chief Election Officer.

The Chief Election Officer must explicitly choose "Proceed".

Before progression is accepted, all mandatory business conditions for starting voting must be satisfied.

If any mandatory condition is not satisfied:

- voting must not become active;
- the election remains in a non-voting condition;
- the business reason for refusal must be visible to the authorized officer;
- the officer may correct the relevant business condition or schedule where authorized;
- the officer may attempt progression again.

Do NOT prescribe technical implementation terminology.

============================================================
4. MANDATORY CONDITIONS
============================================================

The following conditions are already part of the current business ruling:

- required approved candidate condition;
- required admitted voter condition;
- nomination must have reached the required completed/closed business condition;
- no other already-established constitutional prohibition may be violated.

However, do NOT invent additional constitutional conditions.

In particular, do not automatically constitutionalize:

- credential availability;
- a particular database state;
- a particular technical flag;
- a particular implementation mechanism;
- a particular cache;
- a particular lifecycle enum;
- "voting_locked";
- any implementation detail discovered by Session 4.

If a potentially necessary condition is not currently established as a business rule, identify it as:

"REQUIRES SEPARATE BUSINESS DECISION"

Do not silently adopt it.

============================================================
5. BUSINESS CONCEPT FOR "CANNOT PROCEED"
============================================================

The Architecture investigation recovered the historical concept:

"voting_blocked"

Do NOT simply restore that technical name as constitutional language.

The desired business concept is:

"The election is eligible to be considered for voting, but one or more mandatory conditions have not been satisfied, so voting cannot currently proceed."

Use business language such as:

- "Voting not eligible"
- "Waiting for required conditions"
- "Waiting for Chief Election Officer action"

Choose terminology only if it is consistent with the existing Constitution/Manifesto vocabulary.

The important point is the business meaning, not the technical state name.

The business rules should make it possible to distinguish:

A. The voting window has not yet become eligible.

B. The voting window is eligible, but the Chief has not yet chosen Proceed.

C. The Chief attempted to proceed, but a mandatory condition is missing.

Do not decide how these are represented technically.

============================================================
6. NO-CANDIDATE CONDITION
============================================================

Formalize:

If the Chief attempts to start voting and the required candidate condition is not satisfied:

- progression is refused;
- voting does not become active;
- the reason is communicated;
- the election remains in its current valid non-voting condition;
- corrective action may be taken where authorized.

Do not assume that this automatically resolves every previously identified
EM-OPEN-021 reachability path.

The Architecture report specifically found that forceCloseNomination()
can still produce a configuration requiring a separate business ruling.

Therefore:

RECORD THAT THE GENERAL START-OF-VOTING RULE IS NOW ESTABLISHED.

BUT DO NOT CLAIM THAT EVERY POSSIBLE ZERO-CANDIDATE CONFIGURATION HAS BEEN RESOLVED.

If EM-OPEN-021 still requires a separate ruling, leave it explicitly open.

============================================================
7. NO-VOTER CONDITION
============================================================

Formalize:

If the required admitted-voter condition is not satisfied when the Chief attempts to start voting:

- progression is refused;
- voting does not become active;
- the reason is communicated;
- the election remains in a valid non-voting condition;
- corrective action may be taken where authorized.

This implements the already-adopted voter requirement.

Do not expand this into technical implementation details.

============================================================
8. SCHEDULE CORRECTION AUTHORITY
============================================================

The Product Owner has decided that the Chief Election Officer must have
a governed authority to correct an election time window when necessary,
including a previously elapsed window.

However, DO NOT invent the complete schedule-correction policy.

The following principle is established:

The Chief Election Officer may correct the election schedule under
explicitly governed conditions.

A schedule correction:

- must not itself advance the election;
- must not bypass mandatory progression conditions;
- must be attributable to the authorized officer;
- must be auditable;
- must include the reason/justification.

The exact boundaries for:
- moving a window forward;
- extending a window;
- correcting a past window;
- moving a window backward;
- impact on previously issued voting credentials;
- impact after votes have already been cast;

must be identified as either already governed or requiring separate
business decisions.

Do NOT silently invent those rules.

============================================================
9. AUTOMATIC END OF VOTING
============================================================

Preserve the business decision:

The authorized voting period automatically ends at its scheduled/valid
end time.

The election then proceeds according to the established closing/counting
rules.

The Chief may have a separately governed authority to close voting early,
if that authority already exists.

Do not alter the meaning of automatic closure merely because the start
rule is now manual.

============================================================
10. HISTORICAL LEGACY BEHAVIOUR
============================================================

Use the Session 4 report as historical evidence only.

Do not describe the new Product Owner ruling as:

"restoring the legacy command-driven voting boundary."

The Architecture investigation explicitly established that this would
be historically false.

Instead record:

The Product Owner has adopted a new business rule for the start of voting:
scheduled time establishes eligibility for consideration, while explicit
Chief Election Officer action and mandatory conditions are required for
progression.

The historical Generation-2 "blocked" concept may inform the domain
vocabulary, but it is not automatically authoritative.

============================================================
11. CONSTITUTION VS MANIFESTO
============================================================

Determine carefully where each rule belongs.

Use the existing governance separation.

Business meaning belongs in the appropriate Manifesto/business-rule
location.

Authority and permitted election actions belong in the Constitution where
appropriate.

Do not duplicate the same rule unnecessarily.

Do not rewrite unrelated constitutional articles.

Do not modify unrelated election rules.

============================================================
12. DO NOT RESOLVE ARCHITECTURE
============================================================

The Architecture investigation identified several consequences:

- current lifecycle derives voting from the clock;
- the Chief's authority is currently pre-empted by the derived state;
- the Chief's current action writes an implementation fact that the
  lifecycle does not use;
- the scheduled window is currently overwritten by the current action;
- historical blocked states disappeared;
- current lifecycle and historical semantics differ.

DO NOT fix or resolve these.

Your task is only to establish the business rule that Architecture must
later implement.

============================================================
13. REQUIRED OUTPUT
============================================================

Produce a Governance Decision Report containing:

1. Executive business ruling
2. Formal start-of-voting rule
3. Meaning of scheduled time
4. Chief Election Officer authority
5. Mandatory start conditions
6. Candidate requirement
7. Voter requirement
8. Meaning of "cannot proceed"
9. Schedule correction principle
10. Automatic end-of-voting rule
11. Items still requiring separate business decisions
12. Constitution changes required
13. Manifesto changes required
14. Existing rules that must remain unchanged
15. Explicitly out-of-scope architecture/implementation matters
16. Traceability to the Architecture reconciliation report
17. Proposed governance amendment text

============================================================
14. CRITICAL GOVERNANCE DISCIPLINE
============================================================

Do not use implementation behaviour as the source of business truth.

Do not modify the business rule merely to make the current Lifecycle pass.

Do not retrofit the Constitution to justify current code.

Do not resurrect legacy technical states merely because they existed.

Do not declare Election-Only complete.

Do not close EM-OPEN-021 unless the existing evidence and the new ruling
actually resolve it.

Do not implement anything.

If the current Constitution/Manifesto cannot express the ruling cleanly,
state exactly what amendment is required.

If a question remains unresolved, label it:

"OPEN — REQUIRES PO/GOVERNANCE DECISION"

rather than guessing.

============================================================
EXIT CONDITION
============================================================

This Governance task is complete only when:

- the start-of-voting business rule is formally expressed;
- the distinction between time eligibility and officer authority is explicit;
- mandatory conditions are clearly identified;
- automatic closing remains separately defined;
- schedule-correction authority is captured at the agreed level;
- unresolved questions remain explicitly open;
- the Constitution/Manifesto amendments are identified;
- no architecture or implementation work has been performed.

After this report is accepted, hand the resulting business rule to
Session 4 Architecture.

Session 4 will then determine how the current Election Lifecycle must
change to conform to the approved business meaning.

Do not proceed to implementation.
```

### One final architectural discipline

After Session 2 returns its report, **don't immediately let Session 3 code it**.

The proper next gate is:

**Session 2 Governance → accepted ruling → Session 4 Architecture ADR/design → Session 3 implementation.**

That gives us a clean chain of authority and prevents the current lifecycle's behavior from becoming the accidental definition of the Election's business rules.

#
Yes. **This is a good Governance result, and I agree with the discipline Claude used.**

But there is one thing I would **not do yet**: I would not simply tell Governance to enact the whole §17 amendment without reviewing the exact amendment text. The summary is sound, but the actual constitutional wording matters.

### Where we are now

The work has progressed cleanly:

```text
Architecture investigation
        ✅
        │
        ▼
Business decision by you
        ✅
        │
        ▼
Governance formalization
        ✅ d50ad638
        │
        ▼
Governance adoption
        ⏳  ← we are here
        │
        ▼
Architecture design / ADR
        ⏳
        │
        ▼
Implementation
        ⏳
        │
        ▼
Independent verification
        ⏳
```

So **Session 2 has done its job**, but the new rules are not yet authoritative until you adopt them.

---

## There are three particularly good things in this report

### 1. It correctly separates business authority from the clock

The key sentence is excellent:

> **"Time makes progression possible; authority and eligibility make it valid."**

That should become one of the central principles of the Election model.

It prevents this dangerous situation:

```text
10:00 arrives
   ↓
software decides
   ↓
Voting Active
   ↓
Chief is informed afterwards
```

Instead:

```text
10:00 arrives
   ↓
Election becomes eligible for consideration
   ↓
Chief reviews readiness
   ↓
Chief → Proceed
   ↓
mandatory conditions checked
   ↓
Voting Active
```

That is much closer to the trust model you are building.

---

### 2. It did not overreach on schedule correction

This is especially important.

You told us:

> the Chief must be able to correct a time slot, including a slot that has already passed.

Governance correctly **did not invent the rest of the policy**.

For example, it has left open:

* whether a window can be moved backwards;
* what happens after votes have been cast;
* whether already-issued credentials remain valid;
* how far a window can be moved;
* whether an elapsed window can always be corrected.

That is good governance discipline.

We should answer those questions **before implementation**, because they can have direct consequences for voting integrity.

---

### 3. It did not resurrect `voting_blocked` as a technical state

Also correct.

The historical implementation gives us evidence of a useful concept, but we should not automatically say:

> "The new architecture must have an enum called `voting_blocked`."

The business meaning comes first:

> **The election is eligible for consideration, but voting cannot currently proceed because a mandatory condition is not satisfied.**

Architecture can later decide how to represent that.

---

# One thing I want to flag

Governance says:

> "open_voting as the sole means into voting"

That is potentially stronger than:

> "Voting cannot become active without explicit Chief action."

I prefer the second formulation.

Why?

Because "sole means" is already getting close to an architectural/command-model statement.

The business rule should be:

> **No election may enter the voting phase unless the Chief Election Officer has explicitly authorized progression and all mandatory conditions are satisfied.**

Architecture can then determine whether the implementation uses one command, an authorization record, a transition, or something else.

That keeps the Constitution focused on **authority**, not implementation mechanics.

---

# The next step is NOT Session 3

I strongly agree with Claude's sequence:

> **After adoption → Session 4 determines how the lifecycle must change → implementation → verification.**

In other words:

### Session 2 now needs your adoption decision.

Once adopted, Session 4 should receive the resulting authoritative business rule.

Session 4's job will then be something very specific:

> **Design how the current Election Lifecycle can faithfully express the newly adopted business meaning.**

It should not reopen the business decision.

---

## But before adopting §17

I would like to see **the actual §17 amendment text**, not only Claude's summary.

Because the difference between:

> "The Chief must explicitly authorize voting"

and:

> "open_voting is the sole means into voting"

could matter significantly later.

If you paste the **§17 Constitution amendment + Manifesto amendment** here, I can review it with you as Principal Architect/DDD expert and tell you:

1. whether it faithfully captures your business decision;
2. whether it accidentally makes an architectural decision;
3. whether it conflicts with existing constitutional articles;
4. whether "nomination completed" is correctly positioned;
5. whether the voter requirement is correctly expressed;
6. whether schedule correction is safely bounded;
7. whether anything important has been silently added;
8. whether it is ready for you to formally adopt.

**I would do that review before you tell Governance to enact it.**

#
Yes. **That is an important correction to the governance model.**

The Chief Election Officer has exclusive authority to *decide whether to proceed*, but that does **not** mean the Chief has discretionary power to override the election rules.

The governance report actually captures this very well:

> “Authority to proceed is not authority to override the conditions.” 

That should be a fundamental principle.

### The business model should therefore be

**Time → eligibility**

The scheduled time makes the election eligible for consideration.

**Chief → decision**

The Chief must explicitly choose **Proceed**.

**Rules → constraint**

The Chief can proceed **only if all mandatory conditions are satisfied**.

So:

```text
Scheduled time reached
        ↓
Election is eligible for consideration
        ↓
Chief reviews election
        ↓
Chief chooses "Proceed"
        ↓
┌─────────────────────────────────┐
│ Mandatory conditions checked    │
│                                 │
│ ✓ nomination completed          │
│ ✓ approved candidates           │
│ ✓ admitted voters               │
│ ✓ other constitutional rules   │
└─────────────────────────────────┘
        ↓
   ALL satisfied?
      /       \
    YES        NO
     ↓          ↓
 Voting      Refused
 Active      + reason shown
              ↓
        Chief must correct
        the condition
        and try again
```

The crucial point is that **the Chief is an authority inside the Constitution, not above the Constitution**.

The report explicitly says that the Chief's authority is *“capable of being refused by the election's own conditions.”* 

That is exactly the model I would want for this system.

---

## I would slightly refine the wording

I would avoid saying:

> “The Chief has control over whether voting starts.”

That can accidentally imply monopoly.

Instead:

> **The Chief Election Officer has the exclusive authority to request the start of voting, but does not have the authority to override the mandatory conditions governing that decision.**

Or, even simpler for business documentation:

> **The Chief decides when to proceed; the Election Rules decide whether proceeding is permitted.**

I think that is an excellent constitutional principle.

---

## And this changes how we should think about Architecture

Once you formally adopt the Governance ruling, Architecture **must not design a mechanism where**:

```text
Chief clicks Proceed
        ↓
Voting starts
```

without the rules being evaluated.

It must be:

```text
Chief clicks Proceed
        ↓
Election evaluates mandatory conditions
        ↓
conditions satisfied?
        ↓
YES → Voting starts
NO  → Voting remains inactive + reason
```

And importantly, the **clock cannot bypass that path**.

The governance report already states that the scheduled time confers eligibility but no authority. 

---

### One more important distinction

The Chief also **cannot use schedule correction as a backdoor**.

For example:

> Voting time passed → Chief changes the schedule → system automatically starts voting.

That would violate your model.

Schedule correction means:

> **Correct the circumstances under which the election may later be considered.**

It does **not** mean:

> **Use schedule manipulation to force the election into voting.**

The report explicitly establishes that a schedule correction must not itself advance the election or bypass mandatory conditions. 

That is exactly the safeguard against a Chief's authority becoming a monopoly.

---

## So I agree with Governance's current boundary

At this moment:

**Governance has defined the business authority.**

**Architecture's job is next.**

Architecture now needs to answer:

> **How should the Election Lifecycle represent these three business situations without allowing the clock, a stale lifecycle calculation, or the Chief's command to bypass the Constitution?**

Those three situations are already clearly identified:

1. **Not yet eligible**
2. **Eligible and waiting for the Chief**
3. **Cannot proceed because a mandatory condition is missing**

The Governance report deliberately leaves their technical representation to Architecture. 

So **do not let Session 3 implement yet**.

The clean handoff is:

**You adopt Governance → Session 4 designs the lifecycle → you approve Architecture → Session 3 implements → Session 1 independently verifies.**

That is the correct DDD/governance sequence for the bug-fixing phase.

#
Yes. I would make the authorization **explicitly an adaptation of the Governance ruling**, not a new business decision. That distinction is important for the audit trail.

You can give the following to **Session 2 / Governance**:

---

# Authorization — Adoption of Governance Ruling: Start of Voting

**Type:** PO / ARB authorization
**Scope:** Election lifecycle — start-of-voting progression
**Date:** 2026-08-15

## Authorization

I formally **adopt and authorize the Governance Decision Report — Start of Voting: Progression Authority** as the governing business decision for the start of voting.

The adopted principle is:

> **Time makes progression possible; authority and eligibility make progression valid.**

More precisely:

> Reaching the scheduled voting start time does **not** automatically start voting. It makes the election eligible for consideration. The Chief Election Officer must explicitly choose **Proceed**, and the election may enter voting only when all mandatory business conditions are satisfied.

The Chief Election Officer has the **exclusive authority to initiate the decision to proceed**, but this authority is **not authority to override the Election's mandatory rules or conditions**.

Therefore:

> **The Chief decides when to request progression; the Election Rules decide whether progression is permitted.**

If a mandatory condition is not satisfied, the Chief's request must be refused. The Chief must not be able to bypass, suppress, or override that refusal through administrative authority or schedule manipulation.

This authorization also adopts the principle that a schedule correction:

* does not itself start voting;
* does not bypass mandatory conditions;
* must be attributable to the authorized officer;
* must have a stated reason; and
* must be auditable.

The detailed boundaries of schedule correction remain **separate open business decisions** and are not authorized by this act.

Automatic closing at the valid end of the voting period remains unchanged.

## Scope of this authorization

This authorization applies **only to the start-of-voting boundary**.

It does **not** decide:

* the semantics of other election phase transitions;
* the final meaning of the zero-candidate configuration;
* the detailed rules for correcting/moving/extending a past time slot;
* credential consequences of schedule changes;
* voting-after-votes-have-been-cast schedule changes;
* the technical representation of the lifecycle;
* the architecture of the state machine;
* implementation details.

The historical technical term `voting_blocked` is **not adopted as business vocabulary**. Architecture may determine the appropriate representation of the business situations identified by Governance.

## Mandatory conditions

The following are authorized as conditions for starting voting:

1. The scheduled start time has been reached, or the schedule has been validly corrected.
2. The Chief Election Officer explicitly chooses **Proceed**.
3. At least one approved candidate exists.
4. At least one admitted voter exists.
5. Nomination has reached its required completed/closed condition.
6. No other established constitutional prohibition prevents progression.

These conditions must be evaluated **at the time the Chief requests progression**.

## Authority boundary

The following principle is explicitly authorized:

> **No clock-driven lifecycle mechanism may exercise the Chief Election Officer's authority.**
>
> **No Chief Election Officer action may override a mandatory Election condition.**

This is the central constitutional boundary that Architecture must preserve.

## Handoff to Architecture

**Session 4 is now authorized to begin architecture work.**

Session 4 shall determine:

1. how the lifecycle represents:

   * not yet eligible;
   * eligible and awaiting Chief action;
   * unable to proceed because a mandatory condition is unmet;
2. how the scheduled clock interacts with lifecycle state without exercising constitutional authority;
3. how the Chief's `Proceed` action becomes the authoritative transition into voting;
4. how the mandatory conditions are evaluated consistently on every path into voting;
5. how schedule correction interacts with lifecycle state without becoming a progression mechanism;
6. how the existing lifecycle, Constitution, and legacy state-machine remnants are reconciled;
7. what existing technical concepts must be retired, separated, or replaced;
8. what ADR(s) are required.

**Architecture must not invent additional business rules.**
Where the implementation requires a business decision not covered by this authorization, Session 4 must stop at that boundary and return the question to Governance.

**Session 4 must not implement production code.**

After Architecture produces an approved design, the work may proceed to **Session 3 — Implementation**, followed by **Session 1 — independent verification**.

---

### The sequence is now

**Governance ruling → PO/ARB adoption → Session 4 Architecture → Architecture approval → Session 3 Implementation → Session 1 Verification**

That is the cleanest separation of concerns. The Governance report itself explicitly says its proposed amendments are not architecture or implementation work and that Session 4 follows adoption.  

I would now give **exactly this authorization to Governance**, let Session 2 record the adoption, and then give Session 4 a separate architecture investigation/design prompt.

I think Claude did a **very good discovery**, but I would separate **facts**, **analysis**, and **recommendations** more strictly.

Overall I'd give it **9/10**.

## What Claude did well

### 1. It stayed in Discovery mode

This is the biggest success.

It did **not** start refactoring.

It did **not** invent a new architecture.

It searched first.

✅ Exactly what you wanted.

---

### 2. It treated Organisation Context as a business concept

Instead of asking

> "Where is the redirect?"

it asked

> "What is Organisation Context?"

That is a DDD mindset.

---

### 3. It found the real problem

Your symptom was

> login redirects to the wrong place

Claude discovered the deeper issue:

> Organisation Context has no explicit owner.

That is a much stronger discovery than fixing a redirect.

---

### 4. It collected evidence

Almost every conclusion points to specific classes and files.

That's excellent engineering.

---

## Where I disagree

### 1. It is already drifting into architecture

For example:

> Organisation Context has no type.

That is **not** a fact.

The fact is:

> No class named OrganisationContext exists.

Those are different.

"No type" is already an architectural interpretation.

I'd write:

> **Evidence**

No OrganisationContext class, interface, VO or Aggregate was found.

Then later:

> **Interpretation**

The business concept appears to be represented implicitly.

Much cleaner.

---

### 2. Recommendation #1 is too strong

Claude says:

> Name the concept.

Maybe.

Maybe not.

Suppose the business rules are actually simple.

Maybe you don't need a new concept.

Maybe you only need

```
Active Organisation
```

as one field.

Discovery shouldn't decide that yet.

---

### 3. The biggest missing discovery

This surprised me.

Your business rule is

```
Organisation Created
↓

Active Organisation changes
```

Claude never asked

> **Who is actually responsible for changing active organisation?**

Instead it searched

```
session
column
cache
```

I would go one level deeper.

Search for the **business event**

```
Organisation Created
```

Then ask

```
What should happen afterwards?
```

That is event-driven discovery.

---

## 4. The story isn't finished

I think another discovery story is needed.

Current story:

```
How does Organisation Context work?
```

Next story:

```
What is the business lifecycle of Active Organisation?
```

Notice the difference.

One studies implementation.

The other studies the business.

---

## The most important discovery

This sentence:

> The platform answers "Which organisation scopes my queries?" but not "Which organisation is the user working in?"

I think this is the key insight of the review.

I would elevate it into the executive summary because it explains almost every symptom you've observed.

---

## What I would do next

I would **not implement anything** yet.

I would open **PBDIGIT-30**:

> **Active Organisation Business Lifecycle Discovery**

Questions:

* When does an Active Organisation begin?
* When does it change?
* Who may change it?
* Is it remembered between logins?
* What happens after organisation creation?
* What happens if the user has one organisation?
* What happens if the user has multiple organisations?
* What happens if the current organisation is deleted?
* What happens if membership is revoked?

These are **business questions**, not technical ones.

Only after answering them would I allow Claude to design or implement a solution.

---

Overall, I think this discovery is exactly the kind of work you want Claude doing. It searched broadly, gathered evidence, and identified the real area of instability. The next step is to complete the business lifecycle before making any architectural decisions. 

#

I think this is a **much stronger document** than the previous version. It has evolved from "code inspection" into an actual **DDD discovery report**. 

If I were reviewing it as a principal architect, I'd score it around **9.5/10**.

Here are the strengths and the remaining improvement opportunities.

---

# What became significantly better

## 1. Facts vs Interpretation vs Decisions

This is probably the biggest improvement.

Instead of mixing observations and opinions, it now has three clear layers:

* **Facts** (supported by evidence)
* **Interpretation** (analysis based on facts)
* **Open questions** (business decisions)

That makes the report much more trustworthy.

---

## 2. Event-driven thinking

This was the biggest improvement.

Instead of asking:

> Where is organisation_id changed?

it asked:

> After OrganisationCreated, who is responsible?

That is exactly how business processes should be reviewed.

---

## 3. It found a genuine architectural smell

This section is excellent:

> OrganisationCreated is dispatched but has zero listeners.

That is not automatically a bug.

It is an architectural observation.

The report correctly avoids saying:

> this must become event-driven

Instead it asks:

> Is this event intended to own behaviour?

That is the right question.

---

## 4. Open questions instead of solutions

Very good.

Instead of saying

> introduce ActiveOrganisationService

it asks

> Is active organisation a concept or just a field?

That keeps discovery separate from design.

---

# One thing I would improve

The report is still very implementation-centric.

Notice the order:

```
Facts

↓

Controllers

↓

Events

↓

Session

↓

Cache

↓

Questions
```

I would reverse it.

---

Instead I'd start with

# Business lifecycle

```
Customer registers

↓

Default organisation assigned

↓

Customer creates organisation

↓

Customer becomes owner

↓

New organisation becomes active

↓

Customer logs out

↓

Customer logs in

↓

Organisation selected

↓

Customer works
```

Only after that would I ask

```
Which code implements each business event?
```

This sounds subtle, but it's a major difference.

---

# I would also introduce a new review template

You've now developed a review style that is much stronger than route tracing.

I'd formalize it as the standard product review process.

Something like:

```
Phase 1
Business lifecycle

↓

Phase 2
Business events

↓

Phase 3
Route map

↓

Phase 4
Controller map

↓

Phase 5
DDD analysis

↓

Phase 6
Code quality

↓

Phase 7
Runtime verification

↓

Phase 8
Improvement backlog
```

That process can be reused not only for Elections, but also for Membership, Finance, Appointments, etc.

---

# The biggest lesson I see

A few days ago, your reviews started with:

```
Which controller?

↓

Which route?

↓

Which method?
```

Now they start with:

```
What business event happened?

↓

Who owns it?

↓

How is it implemented?
```

That is a significant shift in how you and Claude collaborate.

It moves the review from **framework-first** to **business-first**.

---

# I would make one more addition

Every discovery report should end with a "Business Rule Matrix."

For example:

| Business Rule                            | Implemented | Verified | Evidence       |
| ---------------------------------------- | ----------- | -------- | -------------- |
| Every user has one default organisation  | ✅           | ⬜        | Migration      |
| Creating an organisation makes it active | ✅           | ⬜        | Controller     |
| Login restores active organisation       | ⚠️          | ⬜        | Discovery      |
| Multiple organisations require selection | ❌           | ⬜        | No route found |

This gives you a bridge between discovery and testing.

You can immediately see:

* implemented but not tested,
* missing,
* ambiguous.

---

## Overall

I think you've arrived at a review methodology that fits your long-term goal of shipping a product:

1. **Understand the business process.**
2. **Discover the business events.**
3. **Map events to implementation.**
4. **Verify the runtime behaviour.**
5. **Only then improve the design or code.**

That sequence is scalable across the entire PublicDigit product. It also matches the way experienced architects tend to investigate complex systems: they start with the business flow, then trace how the software realizes it, rather than starting from controllers or framework components. 
#

All three applied — pushed as 9346f5e6. No code changed.

1. Reordered business-first. The report now opens with the intended lifecycle (register → default org → create org → become owner → new org becomes active → work → logout → login → context re-established → work), then business events, then implementation. Rev 2 really was implementation-centric, as you said.

One guard I kept deliberately: §1 is labelled the intended lifecycle derived from rules R1–R6, never inferred from code — because the commission says "do not assume this is correct; discover the real lifecycle." Intent and implementation stay in separate sections so neither can quietly stand in for the other. Leading with intent is right; leading with an assumed-discovered lifecycle would have violated the commission.

2. Business Rule Matrix added (§5), and it earns its place — it exposes something the prose buried:

┌────────────────────────────┬───────────────────────────────────┬──────────┐
│            Rule            │            Implemented            │ Verified │
├────────────────────────────┼───────────────────────────────────┼──────────┤
│ R1 default organisation    │ ✅                                │ ⬜       │
├────────────────────────────┼───────────────────────────────────┼──────────┤
│ R2 creator becomes owner   │ ✅                                │ ⬜       │
├────────────────────────────┼───────────────────────────────────┼──────────┤
│ R3 created → active        │ ⚠️ in effect, not by design       │ ⬜       │
├────────────────────────────┼───────────────────────────────────┼──────────┤
│ R4 single org → auto-enter │ ⚠️ conditional (P2/P3 outrank it) │ ⬜       │
├────────────────────────────┼───────────────────────────────────┼──────────┤
│ R5 multi-org → selection   │ ❌ not implemented                │ ⬜       │
├────────────────────────────┼───────────────────────────────────┼──────────┤
│ R6 explicit, never guessed │ ❌                                │ ⬜       │
└────────────────────────────┴───────────────────────────────────┴──────────┘

The Verified column is empty for every row — so even the ✅ rows are claims about code, not behaviour. That's the bridge to testing you wanted, and it makes PBDIGIT-00 look less optional.                       
3. Phase-2 event table now shows per event: class exists? · dispatched? · listened to? · who actually carries the consequence. That last column is where the finding lives — OrganisationCreated reads "exists ✅ · dispatched ✅ · listeners ❌ · consequence: the controller, inline at :338".

The 8-phase review is now the standard in the backlog README — business lifecycle → eventsDDD → code quality → runtime verification → backlog, with the Business Rule Matrix as themandatory closing artifact. I replaced the 6-phase version in place rather than adding a second home, and carried forward the two rules that make it work: the route is a consequence of the business step, and
facts, interpretation and decisions stay in separate sections.

It's reusable as-is for Membership, Finance and Appointments — which is the real payoff: trts from a template instead of from scratch.
Stop says: Project-state sync (CLAUDE.md):
  x CONTEXT.md not updated today - refresh active ticket / next action if they changed
  o no plan file touched today (fine for small fixes; update the active plan for ticket work)
  (non-blocking; modified files: .claude/runtime/2026-08-06-files.log)

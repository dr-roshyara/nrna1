---
source:
  original_name: "track-2-eks-semantic-discovery.md"
  original_path: "docs/knowledgeos/brainstorming/track-2-eks-semantic-discovery.md"
  detected_timestamp: "2026-08-16 20:47:14"
  timestamp_source: filesystem-mtime
  timestamp_confidence: medium
classification:
  theme: "03-evidence-assurance-governance"
  type: analysis
status:
  authoritative: false
  proposed: false
---

Yes. Let me explain **Track 2 itself**, rather than individual decisions, because Track 2 can otherwise look like a collection of unrelated documents.

## What is Track 2?

**Track 2 is an exploratory DDD/architecture discovery track for the future EKS architecture.**

Its purpose is:

> **Discover what the future Engineering Knowledge System must mean, before deciding how it should be built.**

It is intentionally **outside the governed architecture lane**. The current documents explicitly say that Track 2 may become architectural input only after the current Architecture Baseline has been reconstructed, independently verified, and accepted. Until then, it governs nothing. 

So Track 2 is **not building EKS**.

It is asking:

> **"What must be true about EKS as a business/domain system?"**

---

# Why do we need Track 2?

We discovered that the existing system already contains many mechanisms, but the underlying business concepts are not consistently defined.

For example, the organization has many things called "rules":

```text
Engineering standard
Ruling
Architecture principle
Design policy
Recommendation rule
Quality-gate rule
Document-validation rule
Permission rule
```

But they are not the same business concept. 

So if we start coding now, developers will make their own interpretations.

Track 2 prevents that.

---

# Track 2 is doing semantic discovery

The sequence so far is:

```text
RULE
  ↓
AUTHORITY
  ↓
SCOPE / APPLICABILITY
```

This is not accidental.

Each step exposed something that the previous step needed.

---

## Step 1 — Rule

We asked:

> **What is a Rule?**

The ruled definition is:

> **A Rule is a standing, authoritative obligation governing behaviour within a defined scope and period. A deviation is non-conformance unless an authorized exception permits it.**



That gives EKS a proper business concept.

We also separated:

```text
Rule
≠
Decision
≠
Recommendation
≠
Permission
```

And we established that Rule includes concepts such as:

```text
obligation
scope
validity
binding strength
authority
evidence
exceptions
supersession
```



But we did **not** build a Rule class.

---

# Step 2 — Authority

Then we asked:

> **Who is allowed to make a Rule authoritative?**

The evidence was unusually strong.

There are 20 live grants, and all 20 point to a human act. Governance registers them. 

So we found the principle:

```text
Human act
   ↓
Governance records reference
   ↓
Grant
   ↓
Permission
```

rather than:

```text
Software
   ↓
creates authority
```

The architectural principle is:

> **The mechanism records authority; it does not create authority.**

Then we discovered gaps:

* authority has no temporal ending
* delegation is observed but not explicitly represented
* ownership and authority are different
* provenance needs stronger immutable addressing
* Rule changes need to connect to the existing authority mechanism

These became the Authority semantics. 

Again:

**No Authority aggregate was built.**

---

# Step 3 — Scope / Applicability

Then we reached the next problem:

> **How do we know whether a Rule applies to this developer's situation?**

This is extremely important for EKS.

The analysis found three different meanings of "scope":

### Subject scope

> What does this Rule apply to?

Example:

> Production Payment APIs.

### Authority scope

> What may this person authorize?

Example:

> Architecture work for the Payment Platform.

### Evaluation scope

> What did the checker actually inspect?

Example:

> These 132 documents.

The session correctly says these must not be conflated. 

---

# Then you made SC-2

You ruled:

> **Missing scope must not silently broaden a Rule.**

So:

```text
Specified
→ know it

Unspecified
→ cannot determine

Not applicable
→ this dimension doesn't make sense for this subject
```

The important principle is:

> **Unknown applicability must never be treated as non-applicability.**

The session explicitly captured:

```text
UNKNOWN ≠ DOES NOT APPLY
UNKNOWN ≠ EMPTY SCOPE
```

as a candidate invariant. 

Then you ruled SC-1 and established four dimensions:

```text
System location
Environment
Artifact kind
Lifecycle phase
```

with Audience explicitly excluded from applicability. 

---

# So what is Track 2 really building?

Not software.

It is building **semantic foundations**.

Think of it like this:

```text
                    TRACK 2

              "What does EKS mean?"

                         │
          ┌──────────────┼──────────────┐
          ▼              ▼              ▼

        Rule          Authority        Scope
          │              │              │
          └──────────────┼──────────────┘
                         │
                         ▼
                Applicability
                         │
                         ▼
                 Future Conflict
                  / Reasoning
```

This is **domain discovery**.

---

# What comes after Scope?

We are not finished.

The next semantic questions are likely to be:

### Environment

We have already declared Environment to be a scope dimension.

But today there is **no governed Environment vocabulary**.

So the next question is:

> What does "production", "testing", "staging", etc. mean for EKS?

The recent preparation found that the other scope dimensions already have controlled vocabularies, while Environment has none. 

---

### Rule relationships

Then eventually:

> What is the difference between:

```text
supersedes
refines
exception-to
contradicts
duplicates
supports
```

That is necessary before conflict detection.

---

### Lifecycle

We know rules and authority can change.

We still need to define the business meaning of:

```text
effective
expired
revoked
suspended
superseded
```

---

### Knowledge vocabulary

Then we need to reconcile concepts such as:

```text
Claim
Observation
Evidence
Rule
Decision
Finding
Assessment
Verdict
Exception
Risk
Question
```

without forcing them into one giant generic `KnowledgeItem`.

---

# What Track 2 deliberately does NOT do

This is probably the most important thing to understand.

Track 2 does **not** yet decide:

```text
❌ Laravel
❌ PHP
❌ Python
❌ PostgreSQL
❌ Graph database
❌ API design
❌ aggregates
❌ repositories
❌ events
❌ service decomposition
❌ C4 Level 4
❌ microservices
```

It also does not authorize changes to the existing system.

The Rule Model explicitly states that its rulings do not authorize classes, aggregates, schemas, APIs, conflict engines, or changes to running mechanisms. 

---

# Why this is useful for you

Imagine we eventually ask:

> "Should EKS detect conflicting engineering rules automatically?"

Without Track 2 we might immediately build:

```text
RuleConflictService
```

But Track 2 forces us to first answer:

```text
What is a Rule?
        ↓
What does it oblige?
        ↓
Where does it apply?
        ↓
When is it valid?
        ↓
Who authorized it?
        ↓
How strong is it?
        ↓
What is an exception?
        ↓
Only then:
What does "conflict" mean?
```

That is the difference between **building a feature** and **building a domain correctly**.

---

# And how Track 2 relates to the current architecture baseline

This is the critical governance relationship:

```text
CURRENT REALITY
KOS-ARCH-BASELINE-001
        │
        │ reconstruct
        ▼
What exists today
        │
        ▼
Verify
        │
        ▼
Accept
        │
        ▼
TARGET ARCHITECTURE
Track 2 provides candidate semantic input
        │
        ▼
ARB adopts/rejects
        │
        ▼
Formal architecture work item
```

So Track 2 is **research for the future architecture**, not the architecture itself.

---

# Where we are in Track 2

The first semantic chain is now quite mature:

```text
Rule
  ↓
Authority
  ↓
Scope
  ↓
Applicability
```

And several important business decisions have already been made.

The next major semantic gap is **Environment**, followed by the remaining scope/application semantics, lifecycle/relationship semantics, and eventually knowledge vocabulary.

Only after those are sufficiently stable should we create:

```text
Bounded Contexts
        ↓
Context Map
        ↓
Target C4
        ↓
Tactical DDD
        ↓
Implementation
```

So, in one sentence:

> **Track 2 is the domain-language laboratory for EKS: it discovers and validates the business meaning and invariants that the eventual architecture must implement, while deliberately refusing to become the architecture itself.**

This is a very important clarification, and it changes the business rule significantly.

The "PublicDigit" organisation is **not a real working organisation**. It is a **bootstrap/default organisation** required by the platform. Users should never be considered to be "working" in it.

I would rewrite B1 like this.

---

# B1 — When does an Active Organisation begin?

## Business Intent

Every user belongs to **at least one organisation**.

The **PublicDigit** organisation exists only to satisfy platform bootstrap and public capabilities. It is **not** considered a user's working organisation.

A user's **Active Organisation** is always one of the organisations they actually work in.

---

## Business Rules

### B1.1 Bootstrap

When a user registers,

* the user automatically belongs to the **PublicDigit** organisation.
* this organisation is **not** treated as an Active Organisation.

It is an infrastructure/bootstrap organisation.

---

### B1.2 First real organisation

When the user creates or joins their first real organisation,

* they become a member (and creator if applicable),
* that organisation immediately becomes their Active Organisation.

Example

```text
Register

↓

PublicDigit (bootstrap only)

↓

Create "Namaste Nepal GmbH"

↓

Active Organisation = Namaste Nepal GmbH
```

---

### B1.3 Login behaviour

After authentication the platform ignores the bootstrap organisation when determining where the user should work.

Decision table:

| Real organisations (excluding PublicDigit) | Behaviour                                 |
| ------------------------------------------ | ----------------------------------------- |
| 0                                          | Redirect to "Create or Join Organisation" |
| 1                                          | Automatically enter that organisation     |
| 2 or more                                  | Show Organisation Selection page          |

---

### B1.4 Organisation Selection

If the user belongs to multiple real organisations, the system must never guess.

Instead, it displays:

> **Which organisation would you like to work in?**

Example

```
Choose Organisation

○ Namaste Nepal GmbH

○ NRNA Germany

○ DG Nexolution

[Continue]
```

---

### B1.5 Remembering the choice

Once the user selects an organisation, that organisation becomes the Active Organisation for future work until they explicitly change it or another business rule changes it.

(This overlaps with B4, so we can refine the persistence details later.)

---

# What I like about this rule

It is deterministic.

```text
Login
      │
      ▼
Ignore PublicDigit
      │
      ▼
How many real organisations?
      │
      ├── 0 → Create/Join page
      │
      ├── 1 → Enter automatically
      │
      └── 2+ → Organisation Selection
```

There is no ambiguity and no "last writer wins."

---

## Proposed decision for B1

> **Approved Business Rule:** The PublicDigit organisation is a bootstrap organisation only and is never treated as the user's Active Organisation. After login, the system ignores it when determining the working context. If the user has one real organisation, they are redirected there automatically. If they have multiple real organisations, they are shown an Organisation Selection page and choose explicitly.
#
You're now wearing the **Product Owner hat**, not the architect or developer hat.

The goal of PBDIGIT-30 is to produce a **Business Decision Record**. Engineering stays blocked until that record is complete.

## Step 1 — Record the decision for B1

Update the story with something like:

---

### B1 — When does an Active Organisation begin?

**Decision:** ✅ Approved

#### Business Rule

The **PublicDigit** organisation is a **bootstrap organisation**.

Its purpose is to provide the platform's public capabilities and satisfy the requirement that every user belongs to at least one organisation.

It is **not** considered a user's working organisation.

#### Login behaviour

When a user logs in:

| Number of real organisations (excluding PublicDigit) | Behaviour                                   |
| ---------------------------------------------------- | ------------------------------------------- |
| 0                                                    | Redirect to **Create or Join Organisation** |
| 1                                                    | Redirect automatically to that organisation |
| 2 or more                                            | Show the **Organisation Selection** page    |

---

## Step 2 — Continue to B2

Now answer only **one** question.

> **When does the Active Organisation change?**

This is completely separate from login.

For example:

* User creates a new organisation.
* User joins another organisation.
* User explicitly switches organisation.
* Administrator transfers ownership.
* Membership revoked.

We decide **when** the active organisation changes—not how to implement it.

---

## Step 3 — Do not discuss code

If Claude starts saying things like:

* "We should use events..."
* "We need a domain service..."
* "Let's add ActiveOrganisation..."

stop it.

Reply:

> "This is PBDIGIT-30. Business decisions only."

---

## Step 4 — Finish B1–B9

Only after all nine questions have answers should you continue.

The sequence becomes:

```text
PBDIGIT-30
Business Decisions
      ↓
Approved
      ↓
New Story
"Tactical Modelling of Active Organisation"
      ↓
DDD
      ↓
Implementation
      ↓
Tests
```

---

## My advice

Don't rush into implementation after B1.

Spend another hour and finish **all nine business questions** while you're in Product Owner mode.

Once B1–B9 are complete, you'll have something much more valuable than code: a stable product specification. At that point, Claude can model and implement it with far fewer iterations because the business intent is no longer ambiguous.
#
I think this is where DDD can add real value—not by introducing aggregates or patterns, but by helping you **name the business responsibility correctly**.

From what you've described, the problem isn't really "Active Organisation."

The real business capability is:

> **Determine the user's working context.**

Everything else (redirects, sessions, URLs, middleware) is a consequence of that decision.

## I would review it like this

Instead of starting with redirects:

```text
Login

↓

Redirect

↓

Dashboard
```

Start with the business question:

```text
User authenticated

↓

Which organisation should the user work in?

↓

Working Context determined

↓

Redirect

↓

Load organisation
```

Notice that **redirection is the last step**, not the first.

---

# The business lifecycle

I'd model the business decisions like this.

```text
User logs in
        │
        ▼
Collect all organisations
(excluding PublicDigit)
        │
        ▼
How many?
        │
        ├── 0
        │      │
        │      ▼
        │ Create / Join Organisation
        │
        ├── 1
        │      │
        │      ▼
        │ Working Context = that organisation
        │
        └── >1
               │
               ▼
      Ask the user
               │
               ▼
      Working Context chosen
               │
               ▼
      Redirect
```

This is the business process.

---

# Then review the current implementation

I'd ask Claude to discover:

### Step 1

Where is the working context determined?

Not

> Where is the redirect?

Instead

> **Who decides which organisation the user should work in?**

---

### Step 2

List every writer.

For example

```text
Login

Controller

Middleware

Organisation creation

Organisation join

Organisation delete

Organisation switch

Invitation acceptance
```

Who changes the working context?

---

### Step 3

List every reader.

Who depends on it?

```text
Dashboard

Election

Membership

Finance

Committee

Audit
```

---

### Step 4

Draw one lifecycle.

```text
Organisation Created

↓

Organisation Joined

↓

Organisation Selected

↓

Organisation Revoked

↓

Organisation Deleted
```

---

### Step 5

Only then discuss redirects.

Because redirect becomes

```text
Working Context determined

↓

Redirect to

/organisations/{slug}
```

---

# The biggest improvement I would make

Today your rule says

> redirect according to organisation count.

I would strengthen it slightly.

Instead say

> **Every authenticated request must execute inside exactly one Working Organisation.**

Then define how that is achieved.

For example:

| Number of real organisations | Behaviour                                                   |
| ---------------------------: | ----------------------------------------------------------- |
|                            0 | User must create or join an organisation                    |
|                            1 | That organisation becomes the Working Context automatically |
|                           >1 | User explicitly chooses the Working Context                 |

That becomes a **business invariant**.

Everything else is implementation.

---

# What Claude should review

I'd give Claude a discovery commission like this:

> **Review the Working Organisation determination process.**
>
> Do **not** start with routes or redirects.
>
> Start with the business question:
>
> **"How is the user's Working Organisation determined?"**
>
> Then:
>
> 1. Identify every place where the Working Organisation is determined.
> 2. Identify every place where it is changed.
> 3. Identify every place where it is read.
> 4. Identify every redirect that depends on it.
> 5. Draw one end-to-end business lifecycle.
> 6. Identify duplicated decision logic.
> 7. Determine whether there is a single business authority or multiple competing authorities.
> 8. Recommend improvements **only after** the discovery is complete.

---

## One naming suggestion

I would seriously consider avoiding the term **"Active Organisation."**

It can be confused with an organisation's own lifecycle (active, suspended, archived).

Your business is really talking about:

* **Working Organisation**
* **Current Organisation**
* **Selected Organisation**

I personally prefer **Working Organisation** because it describes the user's context rather than the organisation's state.

For example:

> "The Working Organisation is the organisation within which the authenticated user is currently operating."

That terminology is much less ambiguous than "active organisation," especially in a system where organisations themselves may later have states like *Active*, *Suspended*, or *Archived*.
#
I actually think this is an important business rule, and it belongs in **PBDIGIT-30** because it answers one of the unanswered business questions rather than describing an implementation.

I would write it as a business rule, not as a routing algorithm.

---

# Business Rule B10 — Election-Day Exception

**Name:** Election-Day Direct Entry

**Business intent**

When a member logs into the platform, the platform should minimise unnecessary navigation while ensuring the member never enters the wrong election.

---

## Rule

When a user signs in:

1. Determine all **elections that are active today**.
2. Determine which of those elections the user is **eligible to participate in**.
3. Apply the following rules:

| Eligible elections today | Behaviour                                             |
| ------------------------ | ----------------------------------------------------- |
| **0**                    | Continue with the normal organisation routing rules.  |
| **1**                    | Redirect the user directly to that election.          |
| **2 or more**            | Do **not** guess. Present an election selection page. |

---

## Important constraint

An election is considered **eligible** only if the user is allowed to participate in it.

The platform must never redirect merely because an election exists.

Examples of eligibility include:

* member belongs to the organisation
* member has voting rights
* election is currently open
* any additional constitutional eligibility rules

The implementation should use the existing election eligibility rules rather than creating a second eligibility mechanism.

---

# Relationship to Organisation Routing

This is **an exception**, not a replacement.

Normal routing remains:

```text
Login

↓

Working Organisation
```

The exception becomes:

```text
Login

↓

Exactly one eligible election today?

        │
   Yes  ▼
Election page

        │
   No   ▼
Working Organisation routing
```

---

# Priority

The routing priority becomes:

```text
Login

↓

Exactly one eligible election today?

        │
   Yes
        ▼
Election

        │
   No
        ▼
How many real organisations?

0
→ Create / Join Organisation

1
→ Redirect to that organisation

2+
→ Organisation Selection
```

---

## Why I like this rule

This is not a technical optimization.

It reflects the user's intent.

On election day, the user's goal is usually **to vote**, not to navigate through organisations and dashboards first. At the same time, the rule is safe because it only auto-redirects when there is exactly one eligible election. As soon as there is any ambiguity (zero or multiple eligible elections), the platform asks the user instead of guessing.

I would add one refinement to keep the behaviour predictable:

> **Only apply the election-day exception immediately after login (or when entering the platform root).** Once the user is already working inside an organisation, changing pages should not unexpectedly redirect them into an election. That keeps the exception limited to the initial entry into the platform.

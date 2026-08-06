# PBDIGIT-30 — Active Organisation Business Lifecycle Discovery

**Type:** Discovery (business) · **Epic:** `PBDIGIT-EPIC-01` Organisation Management
**Created:** 2026-08-06 · **Predecessor:** `PBDIGIT-29` (implementation discovery — complete)

| | |
|---|---|
| **Status** | **BLOCKED** |
| **Reason** | **Business decision required.** B1–B9 are questions about how the product should behave; none is answerable from the repository |
| **Owner** | **Product Owner** — not engineering |
| **Engineering** | **cannot continue.** No organisation-context design, modelling or implementation may begin until B1–B9 are answered |
| **Unblocks** | a tactical-modelling story *(only if the answers show a concept is warranted)*, then implementation, then verification |

---

## Why this story exists

`PBDIGIT-29` asked **"how does Organisation Context work?"** — an *implementation* question, and it is answered.

This story asks **"what is the business lifecycle of an Active Organisation?"** — a *business* question, and it is **not** answered anywhere in the repository.

The distinction matters because `PBDIGIT-29` produced open questions (its Q1–Q3) that **cannot be answered by reading code**: whether "active organisation" is a concept or a field, and whether a selection page should exist, are decisions about how the product should behave. Answering them from code would be inventing business rules from implementation accidents — the exact failure mode that produced the current instability.

**Nothing in this story is answerable by search. Every question below needs a human decision.**

---

## The nine business questions

| # | Question | Why it matters (grounded in `PBDIGIT-29`) |
|---|---|---|
| **B1** | **When does an Active Organisation begin?** At registration? At first login? At first organisation membership? | today it begins at registration by database constraint (`users.organisation_id` is `NOT NULL`), which is a schema decision, not a business one |
| **B2** | **When does it change?** | today it changes in ≥15 places, including while merely *viewing* a page (`PBDIGIT-29` I-1, I-5) |
| **B3** | **Who may change it?** The user? An admin? The system? | no authority exists today; the last writer wins (`PBDIGIT-29` I-1) |
| **B4** | **Is it remembered between logins?** | today: partly — the column persists, the session does not, and a cached *routing decision* may outlive both (`PBDIGIT-29` I-4) |
| **B5** | **What happens after "Organisation Created"?** | the event is dispatched with **no listeners**; the consequence is hard-coded in the controller (`PBDIGIT-29` §2, I-8). **This is the question that produced the story** |
| **B6** | **What happens when the user has exactly one organisation?** | R4 says enter it automatically; today voting sessions and elections take precedence over organisation context (`PBDIGIT-29` I-7) |
| **B7** | **What happens when the user has multiple organisations?** | R5 says show a selection page; **no such page or route exists** (`PBDIGIT-29` I-3). Is organisation selection wanted, or is role selection the intended experience? |
| **B8** | **What happens if the current organisation is deleted?** | **Evidence not found** in `PBDIGIT-29`. `users.organisation_id` is `NOT NULL` with a foreign key, so deletion has a schema consequence that must match a business intent |
| **B9** | **What happens if the user's membership is revoked?** | today `TenantContext` aborts **403** when membership is missing (`TenantContext:73,79`). Is a hard 403 the intended experience, or should the user fall back to another organisation? |

---

## Deliverable

**Answers, recorded here, in business language** — not code, not design. Each answer becomes the reference the next story is measured against.

## What this story must NOT do

* no code
* no architecture
* no naming of concepts (that is a modelling decision *after* these answers)
* no choosing a source of truth
* **no inferring answers from current behaviour** — the current behaviour is the thing under question

## Sequence after this story

```
PBDIGIT-29  implementation discovery   ✅ complete
PBDIGIT-30  business lifecycle          ← this story (needs human answers)
   ↓
tactical modelling story (only if B1–B9 show a concept is warranted)
   ↓
implementation story
   ↓
verification
```

**Note the conditional:** if the answers turn out simple, the outcome may be *one well-owned field*, not a new domain concept. `PBDIGIT-29` rev 2 was corrected specifically to stop presupposing otherwise.

---

**Traceability:** `PBDIGIT-29` (§0 key insight, §2 event trace, §5 inconsistencies I-1…I-9, §6 Q1–Q6) · business rules R1–R6 as stated in the `PBDIGIT-29` commission

---

# BUSINESS DECISION RECORD

**Product Owner decisions, recorded verbatim in business language.** Engineering remains blocked on the unanswered questions. Source: `docs/publicdigit/business_rules/20260806_0818_how_many_organisation.md`.

## Terminology decision (applies to B1–B9)

**The term is "Working Organisation", not "Active Organisation".**

> *"The Working Organisation is the organisation within which the authenticated user is currently operating."*

**Reason:** "active" collides with an *organisation's own* lifecycle states (active · suspended · archived). "Working" describes the **user's context**, not the organisation's state. **All later stories and reviews use "Working Organisation".**

## B1 — When does a Working Organisation begin? — ✅ **APPROVED**

### The bootstrap organisation

The **PublicDigit** organisation is a **bootstrap organisation**. It exists to provide the platform's public capabilities and to satisfy the requirement that every user belongs to at least one organisation. **It is never a user's working organisation.**

### B1.1 · Bootstrap
On registration the user automatically belongs to **PublicDigit**. That membership is **infrastructure**, not a working context.

### B1.2 · First real organisation
When the user creates or joins their first **real** organisation they become a member (and creator, if applicable), and **that organisation immediately becomes their Working Organisation**.

### B1.3 · Login behaviour — the bootstrap organisation is ignored when determining where the user works

| Real organisations (excluding PublicDigit) | Behaviour |
|---:|---|
| **0** | Redirect to **Create or Join Organisation** |
| **1** | **Automatically enter** that organisation |
| **2 or more** | Show the **Organisation Selection** page |

### B1.4 · Organisation Selection
With multiple real organisations the system **must never guess**. It asks: *"Which organisation would you like to work in?"* — and the user chooses explicitly.

### B1.5 · Remembering the choice
The chosen organisation remains the Working Organisation until the user explicitly changes it, or another business rule changes it. *(Persistence detail overlaps B4 — refined there.)*

### 🔒 B1 business invariant (the strengthened form)

> **Every authenticated request must execute inside exactly one Working Organisation.**

Everything else — redirects, sessions, URLs, middleware — is a *consequence* of that invariant, never a substitute for it.

**Why this rule is strong:** it is **deterministic**. `Login → ignore PublicDigit → count real organisations → 0 / 1 / 2+ → act.` No ambiguity, and **no "last writer wins."**

---

## B2 — When does the Working Organisation change? — ⬜ **NEXT**

One question only, separate from login. Candidate triggers to rule on (**not** decided): the user creates a new organisation · the user joins another organisation · the user explicitly switches · an administrator transfers ownership · membership is revoked.

**B3–B9 remain unanswered.** Engineering stays blocked; see the status block at the top of this story.

**Implementation note (evidence only, decides nothing):** `PBDIGIT-31` reviews how the *current* redirection mechanism measures against B1. Its finding in one line: **B1's bootstrap-exclusion rule is already partly implemented, while B1.3's 0-org and 2+-org destinations do not exist.**

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

---

## B10 — Election-Day Direct Entry — ✅ **APPROVED**

*(Added to the record after B1. Numbered B10 because it is a new business question, not a revision of B1–B9.)*

### Business intent

When a member signs in, the platform should **minimise unnecessary navigation while never entering the wrong election.**

### Rule

On sign-in:

1. Determine all elections **active today**.
2. Determine which of those the user is **eligible to participate in**.
3. Then:

| Eligible elections today | Behaviour |
|---:|---|
| **0** | continue with the normal Working Organisation routing (B1.3) |
| **1** | **redirect directly to that election** |
| **2 or more** | **do not guess** — present an **election selection** page |

### Constraint

An election counts only if the user **may participate**: belongs to the organisation · has voting rights · the election is open · plus any constitutional eligibility rules.

> **The platform must never redirect merely because an election exists.**
> **Reuse the existing election-eligibility rules — do not create a second eligibility mechanism.**

### Relationship to Working Organisation routing — an **exception**, not a replacement

```
Login
  ↓
Exactly one eligible election today?
  ├── yes → that election
  └── no  → Working Organisation routing (B1.3)
                0  → Create or Join
                1  → enter it
                2+ → ask
```

### 🔒 B10 scope refinement (part of the approved rule)

**The exception applies only immediately after login, or when entering the platform root.** Once the user is already working inside an organisation, ordinary navigation must **never** divert them into an election. This keeps the exception limited to platform entry and the behaviour predictable.

### Why this rule is safe

It auto-redirects **only** when there is exactly one eligible election. At any ambiguity — zero or several — the platform **asks** instead of guessing. It reflects the member's actual intent on election day (*to vote*), without ever choosing a ballot on their behalf.

---

### Implementation note for B10 (evidence only — decides nothing, and is not authorisation)

`PBDIGIT-31`'s evidence shows **B10 is largely implemented already**:

| B10 step | Existing implementation | Status |
|---|---|---|
| elections **active today** | `User::getActiveElection()` — `status='active'` · `type='real'` (demo excluded) · `start_date <= now()` · `end_date >= now()` | ✅ `User.php:1301-1306` |
| **organisation-scoped** to the user, bootstrap excluded | `->where('type','tenant')` on the user's organisations | ✅ `:1289-1292` |
| **not already voted** | `whereDoesntHave('voterSlugs', status='voted')` | ✅ `:1307-1310` |
| **0 eligible → organisation routing** | P3 is skipped when the count is 0 | ✅ `DashboardResolver:~104` |
| **1 eligible → that election** | `route('elections.show', $activeElection->slug)` | ✅ `:120` |
| **2+ eligible → election selection** | currently routes to `organisations.show` | ❌ **the one gap** — `:130` |
| an election **selection page** to route to | `GET /election/select` → `ElectionController@selectElection` **already exists** *(and is declared twice — `routes/web.php:205` and `routes/election/electionRoutes.php:41`)* | ✅ exists |
| exception applies **only at login/root** | `DashboardResolver` runs from `LoginResponse` and after email verification — not on ordinary page loads | ✅ already login-scoped |

**So B10 reduces to one branch change plus verification.**

**One open point, deliberately not answered here:** `getActiveElection()`'s filter covers membership, status, date window and already-voted — but the platform also has `VoterEligibilityService` / `EligibilityEvaluator` / `EligibilitySnapshot`. **Whether that filter *is* the eligibility mechanism or a second one alongside it is unresolved** (`PBDIGIT-EPIC-02` MB-5 records the same concern). B10's constraint forbids creating a second mechanism — so this must be settled before implementation, and it is **not** settled by this record.

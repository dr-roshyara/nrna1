# PBDIGIT-30 — Active Organisation Business Lifecycle Discovery

**Type:** Discovery (business) · **Epic:** `PBDIGIT-EPIC-01` Organisation Management
**Created:** 2026-08-06 · **Predecessor:** `PBDIGIT-29` (implementation discovery — complete)

| | |
|---|---|
| **Status** | ✅ **The discovery of the Working Organisation business concept is COMPLETE** — closed 2026-08-06. **The implementation is not complete, and the enhancements are not begun. That is intentional** |
| **Delivered** | **B1** identity · **B2** lifecycle · **B3** authority · **B10** election-day exception — the core business rules of the Working Organisation |
| **Closed by** | Product Owner. **Rationale:** the remaining topics — persistence across logins, expiry, per-device vs per-person, concurrent sessions — describe **additional product capabilities**, not the discovery of the existing Working Organisation concept |
| **Withdrawn** | **B4** — drifted into product design (see §B4) → `PBDIGIT-34` |
| **Reclassified** | **B5 · B6 · B7** require **no additional business rules — they are derived consequences of B1–B3** (see §Closure) · **B8 · B9** retain only an *experience* question, which is design, not discovery |
| **Unblocks** | **`PBDIGIT-32`** — implement and verify B1–B3 (+B10) |

> **The purpose of this story was to discover the business rules the product already implies — not to design every future enhancement before the current product is made robust.** It stopped at the point where the two diverge.

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

---

## B2 — When does the Working Organisation change? — ✅ **APPROVED, WITH AMENDMENTS**

> **⚠️ Read the authoritative text first: §B2 · As approved, at the end of this section.** The material below is the **engineering draft** that produced it, retained as the reasoning trail. Where the two differ, **the approved wording governs.**
>
> **Provenance.** The draft was written by engineering and marked PROPOSED; the Product Owner **amended and approved** it (2026-08-06). The amendments are recorded in §B2 · Amendments — they are not silently folded in.
>
> **It is derived from B1/B10's own principles — deterministic, never guess, never divert mid-work — and NOT from current behaviour.** What the code does today is the thing under question, so it is cited below only to show why the question matters, never as an answer.

### Proposed rule

> **The Working Organisation changes on exactly two occasions: when the user performs an act that says "I am working here now", or when the current one stops being valid for them. It never changes as a side effect of anything else.**

### B2.1 · Triggers that DO change it

| # | Trigger | Changes it? | Reasoning |
|---|---|---|---|
| **a** | **The user creates an organisation** | **Yes** — it becomes the Working Organisation | Creating is an unambiguous statement of intent; already established by **B1.2** |
| **b** | **The user explicitly selects/switches** organisation | **Yes** | The explicit act B1.4 exists to provide |
| **c** | **The user joins their *first* real organisation** | **Yes** | **B1.2** — they had no working context; now they do |
| **d** | **Membership in the current one is revoked** | **Yes — forced** | The context is no longer valid, so it cannot remain. *The resulting **experience** is **B9**, not this rule* |
| **e** | **The current organisation stops being available** (deleted · suspended · archived) | **Yes — forced** | Same reasoning as (d). *Detail belongs to **B8*** |

**When a change is *forced* (d, e), the destination follows B1.3 deterministically:** 0 remaining real organisations → *Create or Join* · 1 → enter it · 2+ → **ask**. **A forced change must never silently pick one.**

### B2.2 · Triggers that DO **NOT** change it

*(The more important half of this rule.)*

| # | Trigger | Changes it? | Reasoning |
|---|---|---|---|
| **f** | **The user joins an *additional* organisation** while already working in one | **No** | Joining grants *membership*; it does not say *"move me"*. The user may switch (b) whenever they wish. Yanking them out of their current work would be a guess |
| **g** | **Viewing, browsing or rendering any page** — including another organisation's page | **No** | A page is something you *look at*; changing context by looking is the definition of a side effect |
| **h** | **Election activity** — opening a ballot, voting, being routed to an election by **B10** | **No** | **B10 is a *routing* exception, not a context change.** The user is *sent to* an election; their Working Organisation is untouched |
| **i** | **Ownership transfer, or any role change** within the current organisation | **No** | A different *role* in the same organisation is still the same working context. *(Unless it removes membership entirely — then (d))* |
| **j** | **Anything done by another person or by the system** — admin action on another user, background job, import, projection | **No** | Only the user's own act, or invalidity, may move them |

### 🔒 B2 business invariant

> **The Working Organisation changes only by an explicit act of the user, or because it has become invalid for them. Never as a side effect.**

### Why this proposal is shaped this way

- It keeps B1's determinism: every change has a **named trigger** and, when forced, a **defined destination**.
- It preserves B1.4's *"never guess"* — including in the forced case, which is where guessing is most tempting.
- It extends B10's refinement (*"don't divert a user who is already working"*) from navigation to context.
- It draws the line at **explicit act vs. side effect**, which is a line a Product Owner can apply to a trigger nobody has thought of yet.

### What B2 deliberately does not decide

| Question | Belongs to |
|---|---|
| **Who** may change it (user? admin? system?) | **B3** |
| Whether the choice survives logout, and where it is remembered | **B4** |
| What the user *experiences* when their organisation is deleted | **B8** |
| What the user *experiences* when membership is revoked | **B9** |
| Any implementation, mechanism, source of truth, or naming | not a business question — `PBDIGIT-32` / `PBDIGIT-29` Q1–Q2 |

### Why this question was worth asking (context only — not evidence for the answer)

`PBDIGIT-29` recorded that the working context is currently written in **15+ places**, including while *rendering* an organisation page (I-1, I-5). If B2 is approved as drafted, those writes are not merely untidy — **(g) makes most of them contrary to a business rule**, which turns a technical observation into a product requirement.

**Decision needed:** approve, amend, or reject. Engineering will not treat this as settled until it is marked ✅ APPROVED.

---

### B2 · As approved — the authoritative text

**Approved by the Product Owner, 2026-08-06. This wording governs.**

> **The platform maintains exactly one Working Organisation for every authenticated session.**
>
> **The Working Organisation changes only when:**
>
> * **the user performs an explicit business action indicating they intend to work in another organisation; or**
> * **the current Working Organisation is no longer valid.**
>
> **Navigation, page rendering, routing, elections, caching, middleware, or any other technical mechanism must never change the Working Organisation as a side effect.**
>
> **When a forced change occurs, the platform applies the deterministic B1 routing rules (0 / 1 / many) rather than guessing.**

### B2 · Change classes — voluntary vs forced (first-class, for auditability)

| Class | Meaning | Triggers |
|---|---|---|
| **Voluntary** | expresses **user intent** | the user creates an organisation · the user selects/switches to another organisation · the user joins their **first** real organisation |
| **Forced** | preserves **platform consistency** | membership revoked · organisation deleted · organisation suspended |

**These are different business events with different meanings, and they are recorded as different classes** — the distinction matters for auditability. A voluntary change answers *"who chose this?"*; a forced change answers *"why was this necessary?"*

### B2 · Explicit clarification — accepting an invitation

**Accepting an invitation to an additional organisation does NOT change the Working Organisation.** The user stays where they were working.

Stated explicitly because the product flow may say *"Welcome to Organisation X"*, which reads like an entry. It is not: joining grants **membership**, not **context**. The user may switch whenever they wish (a voluntary change). **Consistent with B1.4's "never guess."**

### B2 · Amendments to the engineering draft (recorded, not folded in silently)

| # | Amendment | Effect |
|---|---|---|
| **1** | **Navigation and Working Context are separated as concepts.** *"Navigation must never be interpreted as a Working Organisation change."* | B10 already established that a user can be **routed to an election without changing their Working Organisation**. The amended wording makes that a general rule, protecting against a future regression where someone updates the context because a page was visited |
| **2** | **Voluntary vs forced becomes a first-class distinction**, not merely two lists | different business meanings — intent vs consistency — and it will matter for audit |
| **3** | **B2 is expressed as an extension of B1**, opening with B1's invariant rather than standing alone | B2 is no longer an independent rule that could drift from B1 |
| **4** | **Invitation acceptance clarified explicitly** rather than left implicit | the draft assumed *"stay where I am"*; the approved text says so out loud |
| **5** | Wording kept **entirely in business language** — no controllers, sessions, middleware or mechanisms | engineering receives an unambiguous rule without being told how to build it |

### B2 · Consequence for existing work

`PBDIGIT-29` recorded that the working context is written in **15+ places, including while rendering an organisation page** (I-1, I-5). **Under B2 as approved, those writes are contrary to a business rule** — not merely untidy. They move from *architecture debt* into `PBDIGIT-32`'s scope.

**B3–B9 remain unanswered.** `PBDIGIT-32` stays blocked.

---

## B3 — Who has the authority to establish or change the Working Organisation? — ✅ **APPROVED**

> **Provenance.** The Product Owner supplied the rulings below and reframed the question from *"who may change it?"* to **"who has the authority to establish or change it?"** — which leads to business authority rather than implementation. Both items engineering flagged were then settled by the Product Owner: the A-3 hedge was **removed** (business rules do not hedge), and Clarification 2 was **simplified** — there is no contradiction to resolve. A **traceability requirement** was added to the invariant. Approved 2026-08-06.

### B3 · The authorities

| # | Authority | May establish or change the Working Organisation? | Scope |
|---|---|---|---|
| **A-1** | **The user, for themselves** | ✅ **Yes — the primary authority** | This is *their* working context. Every voluntary change under B2 originates here |
| **A-2** | **The platform** | ⚠️ **Only when the current Working Organisation is no longer valid** — organisation deleted · suspended · membership revoked | The platform acts to preserve consistency, never to express a preference. Forced changes follow B1.3 deterministically |
| **A-3** | **An administrator, acting on another person** | ❌ **No** | **An administrator may change memberships and permissions. An administrator may not choose another person's Working Organisation.** |
| **A-4** | **Anyone or anything else** | ❌ **Never** | See the general principle below |

### 🔒 B3 business invariant

> **The Working Organisation is personal execution context. Only the user may choose it. The platform may replace it only when it has become invalid. No one may choose it on another person's behalf.**
>
> **The platform shall always be able to explain why the current Working Organisation was established.**

**Every Working Organisation must be attributable to exactly one of three causes — and to nothing else:**

| Attribution | Authority |
|---|---|
| **the user selected it** | A-1 |
| **the user created the organisation** | A-1 |
| **the platform replaced one that had become invalid** | A-2 |

**"Nothing else" is the operative half.** If the current Working Organisation cannot be attributed to one of these three, it was established without authority — which is a defect by definition, not a state to be interpreted. *(This makes B3 auditable rather than merely declarative: the rule can be checked against reality instead of trusted.)*

### B3 · The general principle — why no mechanism is named here

**Nothing that is not a named authority may establish a Working Organisation. Anything else may only validate or consume the one that already exists.**

Stated as a principle rather than a list, because *background jobs · routing · caching · request handling · page rendering* are **mechanisms, not authorities** — and a business rule that enumerates today's mechanisms cannot rule on tomorrow's. The principle covers them all by construction:

| A mechanism may… | A mechanism may never… |
|---|---|
| **validate** the Working Organisation | **choose** one |
| **consume** it | **create** one |
| **store** a previously determined answer | **become** the authority for it |

*(This is the same design property as B2's explicit-act-vs-side-effect line: it rules on cases nobody has thought of yet.)*

### B3 · Clarification 1 — the administrator boundary *(settled: no hedge)*

> An administrator changes **memberships and permissions**, not contexts. If an administrator's action **invalidates** the organisation a user is currently working in — by revoking membership, or by suspending or deleting the organisation — that is **not the administrator choosing a new context**. It is an **A-2 forced change**, and the destination follows B1.3 deterministically.

So an administrator has real power over *what a user may access*, and none over *where that user is working*. **A-3 is "No" without qualification, and nothing is lost.**

### B3 · Clarification 2 — B1.3 does not conflict with B3

> **Where exactly one valid outcome exists, no choice is being made. The platform is applying a deterministic business rule. Business authority is only exercised where alternatives exist. B1.3 therefore does not conflict with B3.**

### B3 · Open edge, deliberately unanswered

**Support or impersonation:** may a support agent, acting *as* a user with permission, establish that user's Working Organisation? Not covered by A-1–A-4, because such an actor is neither "the user themselves" nor "an administrator acting on another person". **Left open — no such capability is assumed to exist, and inventing a rule for a capability that may not exist would be guessing.**

### B3 · What B3 does not decide

**Where** the choice is remembered and whether it survives logout → **B4**. The **experience** of a forced change → **B8** (deletion) and **B9** (revocation). Any mechanism, storage or naming → not a business question.

### B3 · Consequence for existing work

`PBDIGIT-29` recorded **15+ writers** of the working context, including request handling and page rendering. Under **B2** those writes are contrary to a business rule; under **B3** they are also **unauthorised** — none of them is a named authority. Together the two rules mean `PBDIGIT-32` is not tidying a mechanism, it is **restoring an authority**.

---

**Business policy now complete for identity · lifecycle · authority:** **B1** what exists → **B2** when it changes → **B3** who may change it. **B4–B9 remain open** (B10 approved separately). `PBDIGIT-32` stays blocked.

---

## B4 — Is the Working Organisation remembered between logins? — ❌ **WITHDRAWN — NOT A DISCOVERY QUESTION**

> **⛔ Withdrawn by the Product Owner, 2026-08-06. It binds nothing and no part of it is approved.** The draft is retained below **only** as the reasoning trail; it must not be cited as a rule.
>
> **Why withdrawn — engineering drifted from discovery into design.** B1–B3 *discovered* rules the product already implies. B4 began that way and then **invented product requirements nobody had asked for**: no expiry · per person rather than per device · memory that carries its reason across logout. Each is a defensible design; **none was discovered.** Customers might legitimately want *remember for 30 days*, *remember until logout*, or *remember forever* — that has not been established, and choosing among them is **product design**.
>
> **The clearest symptom** was the draft's own sentence *"that is a gap B4 creates work for."* A business rule had begun driving architecture. **Architecture supports the business; a Product Owner should never be obliged to invent features because engineering found a gap.**
>
> **Where it goes instead:** `PBDIGIT-34` — a product **enhancement** story, unblocked and unprioritised, to be considered *after* B1–B3 are implemented and verified.
>
> **The one part worth carrying forward** *(as reasoning, not as a rule)*: **"remembering is not guessing."** If the platform is ever asked to resume a previous choice, that resolves the apparent tension between B1.3 (*ask when there are several*) and B1.4 (*never guess*) — reproducing a user's own explicit decision is the opposite of a guess. **Recorded in `PBDIGIT-34`.**
>
> **The question as originally phrased asks two things.** *"Is it remembered?"* is a business question and is answered below. ***"Where is it remembered?"* is not a business question** — storage is a mechanism, and B3 already forbids any mechanism from becoming the authority. It is left to `PBDIGIT-32` / `PBDIGIT-29` Q2.

### B4.1 · Is it remembered? — **Yes**

> **A user's Working Organisation is remembered across logins. When a returning user signs in, the platform resumes the organisation they last worked in.**

**Grounded in the customer's own expectation**, recorded in `PBDIGIT-29` §0: *"I log out and come back tomorrow — I expect to land where I left off."*

### B4.2 · The crux — memory versus B1.3's "ask when there are several"

**The apparent conflict:** B1.3 says a user with two or more real organisations is **shown the selection page**. B1.5 says the chosen organisation **persists**. For a returning user with three organisations, which governs?

**Proposed resolution:**

> **Remembering is not guessing.**
>
> **B1.3 governs when there is no valid remembered choice** — first entry, or the remembered organisation is gone. **A valid remembered choice governs otherwise**, and the user is resumed into it without being asked again.

B1.4 forbids the platform from **guessing** among alternatives. Reproducing the user's **own** explicit decision is not a guess — it is the opposite of one. *(The same reasoning the Product Owner applied in B3: authority is exercised where a choice exists; here the choice was already made, by the user.)*

**Consequence:** a user with several organisations is asked **once**, not on every login. Being asked repeatedly after having answered would be the platform forgetting, not the platform being careful.

### B4.3 · When memory ends

| Memory ends when… | Then |
|---|---|
| the user **explicitly chooses** a different organisation | that becomes the remembered choice (B2 voluntary) |
| the remembered organisation is **no longer valid** — deleted · suspended · membership revoked | **forced change** (B2 forced, B3 A-2); destination follows B1.3 |
| the user **creates** an organisation | that becomes the remembered choice (B1.2) |

### B4.4 · Memory does not expire with time

> **A remembered Working Organisation has no expiry. It ends because it was changed or became invalid — never because time passed.**

A business fact about *where a person works* does not become false after an interval. **Any timeout is a mechanism artefact, and B3 forbids a mechanism from becoming the authority** — so a stored copy that ages out may cause the platform to *re-derive* the answer, but it may never cause the platform to *forget the user's choice*.

*(Recorded because the platform currently keeps a **routing decision** for 300 s — see the consequence note below. A cached routing decision and a remembered choice are different things: one is an implementation artefact, the other a business fact.)*

### B4.5 · Memory carries its reason

> **Whatever remembers the choice must also remember why it was established** — user selected it · user created the organisation · platform replaced an invalid one.

Direct consequence of **B3's traceability requirement**: an unattributable Working Organisation is a defect by definition, and that must remain true after a logout, not only during the session that created it.

### B4.6 · Whose memory — the person, not the device

> **The Working Organisation is remembered per person, not per device or per browser.**

It is the user's working context (B3: *personal execution context*), so signing in elsewhere resumes the same organisation. **Flagged for the Product Owner:** some products deliberately scope this per device. If that is preferred here, B4.6 is the sentence to change — nothing else in B4 depends on it.

### 🔒 B4 business invariant

> **A user's Working Organisation persists until they change it or it becomes invalid. The platform asks which organisation to work in only when it has no valid remembered choice — never to re-confirm one the user has already made.**

### B4 · Open edge, deliberately unanswered

**Concurrent sessions.** If the same person is signed in twice and switches organisation in one place, what happens in the other? Not answered — no assumption is made about whether concurrent sessions are supported, and inventing a rule for a capability that may not exist would be guessing.

### B4 · What B4 does not decide

**Where** the memory lives, and in which of the four stores `PBDIGIT-29` found → **mechanism**, not business. The **experience** of returning to find the organisation deleted → **B8**, or membership revoked → **B9**. Whether a *routing* decision may be cached at all → an implementation question, deferred by `PBDIGIT-31` §10 to the implementation review.

### B4 · Consequence for existing work

If B4 is approved as drafted, two things follow for `PBDIGIT-32`:

1. **Resuming a remembered organisation must not be confused with re-deriving a destination.** The platform currently keeps a **routing decision** for 300 s (`PBDIGIT-31` BR-4); under B4.4 that artefact may expire freely, but the user's **choice** must not.
2. **A returning user with several organisations should not meet the selection page again.** Whether today's mechanism can distinguish *"never chose"* from *"chose, and we forgot"* is **not established** — `PBDIGIT-29` found no store that records the user's *choice* as such, only stores that hold *an* organisation. **That is a gap B4 creates work for, and it is stated as a question, not a finding.**

**Decision needed:** approve, amend, or reject. **B5–B9 remain open.** `PBDIGIT-32` stays blocked.

---

# CLOSURE RECORD — the discovery of the Working Organisation business concept is complete

**Closed by the Product Owner, 2026-08-06 after B1, B2, B3 and B10.** The story achieved its purpose: it discovered the business rules the Working Organisation concept already implies.

**What is complete and what is not — stated so the closure cannot be misread:**

| | |
|---|---|
| the **business concept** | ✅ complete |
| the **implementation** | ⬜ not begun — `PBDIGIT-32` |
| **runtime verification** | ⬜ not begun — `PBDIGIT-00` |
| **enhancements** | ⬜ not begun — `PBDIGIT-34`, unprioritised |

**All three gaps are intentional.** Closing a discovery story says the rules are known, not that the product is finished.

## What was delivered

| Rule | Question | Status |
|---|---|---|
| **B1** | **Identity** — what is a Working Organisation, and when does one begin? | ✅ APPROVED |
| **B2** | **Lifecycle** — when does it change? | ✅ APPROVED |
| **B3** | **Authority** — who may establish or change it (+ traceability)? | ✅ APPROVED |
| **B10** | **Exception** — election-day direct entry | ✅ APPROVED |

**Identity → lifecycle → authority → auditability.** Together these are sufficient to implement and verify the concept.

## Disposition of B4–B9 — nothing dropped silently

| # | Original question | Disposition |
|---|---|---|
| **B4** | Is it remembered between logins? | ❌ **WITHDRAWN** — product design, not discovery → **`PBDIGIT-34`** |
| **B5** | What happens after *Organisation Created*? | ✅ **no additional rule required — a derived consequence** of **B1.2** (it becomes the Working Organisation) + **B2.1a** (a voluntary change) + **B3 A-1** (the user's own authority) |
| **B6** | What happens with exactly one organisation? | ✅ **no additional rule required — a derived consequence** of **B1.3** (enter it automatically) + **B3 Clarification 2** (one valid outcome is not a choice) |
| **B7** | What happens with multiple organisations? | ✅ **no additional rule required — a derived consequence** of **B1.3** (show selection) + **B1.4** (never guess) + **B3 A-1** (only the user may choose) |
| **B8** | What if the current organisation is deleted? | ⚠️ **rule answered, experience open** — **B2.1e** (a forced change) + **B3 A-2** (platform authority on invalidity) + **B1.3** (deterministic destination). What the user *sees* is **UX design**, not business discovery |
| **B9** | What if membership is revoked? | ⚠️ **rule answered, experience open** — identical treatment to B8 |

### Why B5–B7 needed no ruling — the lesson worth carrying forward

**B5, B6 and B7 were never answered directly. They stopped being questions.**

Nobody ruled on *"what happens when the user has exactly one organisation?"* What was ruled on was **what a Working Organisation is** (B1), **when it changes** (B2), and **who may change it** (B3). B6 then follows with no further decision — as do B5 and B7.

> **Discover the fundamental rules first, and many scenario questions disappear on their own.**

That is the distinction between *"already answered"* and *"a derived consequence"*, and it is worth stating precisely: a derived consequence needs **no ruling, no owner and no maintenance** — it cannot drift from B1–B3, because it *is* B1–B3 applied to a case. An independently answered question could drift.

**Practical implication:** the original nine questions overestimated how many decisions were needed. **Three fundamental rules retired five scenario questions.** A future discovery story should expect the same and resist answering scenarios one by one.

B8 and B9 have their *rule* decided by the same derivation; only the *experience* remains open, and an experience is designed, not discovered.

## What this closure deliberately does **not** claim

- **It does not claim the product is complete.** Cross-login memory, expiry policy, per-device behaviour and concurrent sessions are **real product questions** — they are simply **enhancements**, and they belong after the current rules are implemented and verified.
- **It does not claim B8/B9's experience is unimportant** — only that designing it is not this story's job.
- **It does not resolve `PBDIGIT-29` Q1/Q2** (is the concept a field or a type; which store is authoritative). Those remain open and are **not** prerequisites for implementing B1–B3.

## The discipline this closure protects

> **Business discovery finds the rules a product already implies. It does not design the product's future.** When a discovery story begins answering *"what would be a good product?"* instead of *"what does this product already require?"*, it has become a design activity and should be split.

**Recorded because engineering crossed that line in B4 and the Product Owner caught it.** The sequence this restores:

```
Business discovery → business rules → implementation review → implementation
        → verification → enhancement stories
```

**Enhancements come last, not during discovery.**

## Next

**`PBDIGIT-32` is unblocked:** implement and verify B1–B3 (+B10). **`PBDIGIT-34`** holds the cross-login memory question as an unprioritised enhancement.

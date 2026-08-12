# Model B — ElectionMembership governance analysis (Domain Decision Record)

**Type:** Governance / domain-modelling analysis · **Date:** 2026-08-12 · **Programme:** IERVP (Session 2)
**Status:** **MODEL B DOMAIN ANALYSIS COMPLETE — AWAITING PRODUCT OWNER / ARB REVIEW**
**Mode:** analysis only. **No production code, test, fixture, configuration, migration or database change. Constitution not amended. Nothing renamed or refactored.**
**Reads only:** authoritative sources + read-only DB queries.
**Not an ADR.** Promoting any part of this to an ADR is a governance act, not an engineering one.

---

## The one-line result

> **Almost every concept Model B needs already exists — but it exists for the *organisation* `Member`, not for the election voter.** The Membership context has a guarded suspend/restore/terminate state machine and a policy that calls itself *"the authoritative eligibility engine"* for all Elections voting decisions. **That policy has zero production callers.** So the question is not *"what vocabulary must we invent?"* but **"does the election entitlement extend the model that already exists, or is it a separate lifecycle?"** — and that is the decision I am handing back.

---

## A · Product Owner ruling (recorded, not reinterpreted)

> **`ElectionMembership` is an election-specific, durable entitlement created when a person is admitted to an election.** In Full Membership mode, organisation membership is an **admission prerequisite**, not a continuously evaluated prerequisite for retaining the entitlement. In Election-Only mode, no organisation `Member` is required. **The Election Chief has authority to suspend/revoke** that entitlement; **suspension is an election-level decision and must not be conflated with organisation membership.**

**BUSINESS DECISION.** Approved 2026-08-12. Not re-litigated below.

---

## B · Business concepts — what exists, and where

| Concept | Named in an authoritative source? | Home | Durable or derived | Authority | Label |
|---|---|---|---|---|---|
| **Organisation membership** (`Member`) | ✅ yes | `Contexts/Membership/Domain/Member` · `members` table | durable, episode-based | Organisation | **OBSERVED FACT** |
| **Tenant/role linkage** | ⚠️ only as a column | `user_organisation_roles.role ∈ {member, owner}` | durable | Infrastructure | **OBSERVED FACT** |
| **Election admission** | ❌ **no named concept** | import / assign application code | — | *unowned* | **BUSINESS RULE NOT SPECIFIED** |
| **Election entitlement** | ❌ **not named anywhere** | — | Model B says durable | Election | **BUSINESS RULE NOT SPECIFIED** — Model B introduces the concept |
| **Suspension** | ✅ **twice, for two different objects** | (a) `Member::suspend()`, `MembershipLineage` ACTIVE→SUSPENDED (b) `ElectionConstitution` `suspend` = *the whole election* | (a) durable state (b) election state | (a) Organisation (b) Chief/platform admin | **OBSERVED FACT** |
| **Restoration** | ✅ organisation only | `Member::reactivate()`, `MembershipLineage` SUSPENDED→ACTIVE (guarded, throws otherwise) | durable | Organisation | **OBSERVED FACT** |
| **Revocation** | ⚠️ exists, **different object** | `ADR-003` revokes **verification**; `MembershipLineage` TERMINATED (terminal, irreversible) | durable | Trust / Governance | **OBSERVED FACT** |
| **Voting eligibility** | ✅ yes | `ADR-002`, `UBIQUITOUS_LANGUAGE.md`, `VotingEligibilityPolicy` | *"assessed at action time"* | Eligibility context | **OBSERVED FACT** |
| **Voting access** | ✅ implicitly | `ElectionLifecycle::canVote()` + `VoterSlug.can_vote_now` + `EnsureVotingActive` | derived | Election lifecycle | **OBSERVED FACT** |
| **Vote authority** | ✅ yes | `ADR-002` *Authorized* = role + permission + scope | derived | Authorization | **OBSERVED FACT** |
| **Having voted** | ✅ as data | `has_voted`, `markAsVoted()` | durable | Voting | **OBSERVED FACT** |

### 🔴 The word "member" denotes three different things

| # | Thing | Rows measured | Is it the business `Member`? |
|---|---|---|---|
| 1 | `Member` aggregate — `membership_number`, `membership_type_id`, `fees_status`, `membership_expires_at` | **0 platform-wide** | ✅ yes |
| 2 | `user_organisation_roles.role = 'member'` | **20** | ❌ no — a tenant/role linkage |
| 3 | `ElectionMembership` | **20** | ❌ no — an election entitlement |

**OBSERVED FACT.** And the `election_memberships` migration asserts its composite FK ensures *"user is actually a member of this organisation"* — **that comment is false as a business statement**: it guarantees a row in (2), while (1) is empty.

---

## C · Organisation-mode model — verified, not assumed

**The Product Owner's diagram is confirmed by the implementation's own declared semantics** (`VoterSourceStrategy`):

| | Election-Only (`ImportedVoterRegistry`) | Full Membership (`MembershipRegistry`) |
|---|---|---|
| **Declared source** | *"direct org enrollment (OrganisationUser table)… no membership filtering"* | *"formal membership governance (Member table)… requires member status, paid/exempt fees, active membership type"* |
| **Declared admission rule** | active org user, not soft-deleted | active `Member` + fees paid/exempt + not deleted |
| **Measured on live data** | 5/5 voters have a `user_organisation_roles` row; **0 have a `Member` row** | **no data exists — `members` has 0 rows** |
| **Reachable?** | ✅ exercised | ❌ **REACHABILITY GAP** |

**CONCLUSION:** Model B's *"in Election-Only mode, no organisation `Member` is required"* is **ALIGNED** with the implementation. **OBSERVED FACT.**

**Two qualifications, both recorded rather than resolved:**
1. Election-Only voters *are* given `user_organisation_roles.role = 'member'` — so at the persistence level they are literally called members. **INTERPRETATION:** this is a tenant linkage the FK demands, not the business `Member`. It is nonetheless a live vocabulary collision.
2. **Full Membership is unreachable for a deeper reason than tooling:** the `Member` aggregate has **no instances at all**. **So the mode is not merely un-exercised — its source of truth is empty.** That strengthens `IERVP-2`/`IERVP-3` from "UI blockers" to "no data exists to admit from." **OBSERVED FACT.**

---

## D · Model B made precise — and *not* reduced to persistence

**HYPOTHESES TESTED** (the PO's four):

| Hypothesis | Verdict | Basis |
|---|---|---|
| `ElectionMembership` = entitlement | **Supported as a business reading; NOT expressed in code.** No type, state machine, event or constitutional action names an entitlement. The row is a record; nothing declares what it *confers* | **INTERPRETATION** |
| suspension = restriction on entitlement | **Supported, and an existing constitutional principle already says exactly this** — see below | **OBSERVED FACT** + **INTERPRETATION** |
| revocation = termination of entitlement | **Not expressed for this object.** `remove()` → `status='removed'` is the closest, and it is described as removal, not revocation. The Membership context's `TERMINATED` (terminal, irreversible) is the nearest *modelled* equivalent | **BUSINESS RULE NOT SPECIFIED** |
| having voted = consumption of voting capability | **Supported by behaviour, contradicted by representation.** `markAsVoted()` writes `has_voted=true` **and** `status='inactive'` — so consumption is expressed by mutating the *entitlement's* state field | **OBSERVED FACT** |

### 🔑 The principle Model B should extend rather than invent

`ElectionConstitution.php:126-128` states, as an architectural principle:

> *"Suspension is an operational governance overlay, orthogonal to lifecycle progression. **It freezes capabilities only. Does NOT mutate business facts** or advance the election through phases."*

**CONCLUSION:** that is precisely Model B's *"suspension restricts, it does not destroy the entitlement."* The principle exists and is accepted — **for the election as object.** Applying it to the voter is an **extension of existing constitutional language, not a new concept** (ES-005.4: extend, never copy).

> ⚠️ **And current behaviour violates it.** Voter suspension **does** mutate the entitlement's own state field (`status: active → inactive`), which is a business fact, rather than freezing a capability beside it. **INTERPRETATION**, resting on the measured write in `confirmSuspension()`.

---

## E · Entitlement vs eligibility vs access vs vote authority — ownership map

**The system already has a three-way separation, accepted in `ADR-002`: Verified ≠ Eligible ≠ Authorized**, with the formula `CanPerformAction = Verified && Eligible && Permission`.

**Model B adds a fourth axis — Entitlement — that `ADR-002` does not have.** `ADR-002` folds admission into eligibility (*"Election assignment (enrolled for specific election)"* is listed as an **eligibility dimension**).

| Decision | Question | Declared owner | Actual runtime owner (measured) | Ownership label |
|---|---|---|---|---|
| **Entitlement** | admitted to this election? | *nobody* | `election_memberships` row existence | **Unknown** |
| **Eligibility** | currently satisfies conditions? | Eligibility context — `VotingEligibilityPolicy`, *"the authoritative eligibility engine. All Elections voting decisions flow through this policy"* | ❌ **`User::isVoterInElection()`** — `role='voter' AND status='active'` | **Application** (declared Domain) |
| **Voting access** | may they enter the ballot now? | `ElectionLifecycle::canVote()` | same, plus credential arming | **Application** |
| **Vote authority** | may they cast it? | `ADR-002` Authorized = role+permission+scope | credential exhaustion + step order | **Application / Infrastructure** |
| **Verified** | identity trusted? | Trust Attestation (`ADR-001/002`) | not on the measured path | **Domain** |

### 🔴 The central implementation gap

**`VotingEligibilityPolicy` — which declares itself authoritative for all Elections voting decisions, and which already returns `SUSPENDED`, `TERMINATED`, `NOT_ACTIVE`, `NO_MEMBERSHIP` reason codes — has ZERO production callers.** Its only production mentions are docblocks telling other code to use it (*"Never use this for authorization — use `VotingEligibilityPolicy` instead"*).

**OBSERVED FACT** (repo-wide grep). **CLASSIFICATION: B — IMPLEMENTATION GAP**, and the most consequential one in this analysis: the architecture names an authority, and the ballot is protected by a different predicate.

> **Notable precedent, `MembershipLineage.php:223`:** *"Bug fix: Previously returned `!isTerminated()`, making SUSPENDED members appear active."* **That is exactly the G-1 defect shape — allow-by-exclusion instead of deny-by-default — already found and fixed on the organisation side, and still present on the election side.** **OBSERVED FACT.**

---

## F · Suspension semantics — what is supported, what is not

**Runtime evidence is treated as established and not re-run** (per commission): the ballot is refused via `status='active'`; the entry surface is not; nothing reads `suspension_status`.

| Question | Answer | Label |
|---|---|---|
| Preserves `ElectionMembership`? | **Yes** — the row persists; only fields change | **OBSERVED FACT** |
| Prevents ballot access? | **Yes**, incidentally | **OBSERVED FACT** |
| Prevents vote submission? | Same middleware; not separately exercised | **MECHANISM NOT ESTABLISHED** for submission specifically |
| Affects entitlement? | **It mutates the entitlement's own state field** — contradicting the "freezes capabilities, does not mutate business facts" principle | **INTERPRETATION** |
| Can be reversed? | ⚠️ **Only by an ungoverned path** — see below | **OBSERVED IN CODE** |
| Requires an audit event? | **Partially** — propose/confirm log to `voting_security`; **single-actor `suspend` and `approve` log nothing**; **no domain events exist on `ElectionMembership` at all** | **OBSERVED FACT** |
| Requires four-eyes? | **On the confirm path yes. The outcome is reachable single-handedly** — see below | **OBSERVED IN CODE** |
| Temporary or indefinite? | **BUSINESS RULE NOT SPECIFIED** — no duration, expiry or review obligation anywhere | — |

### 🔴 Two governance asymmetries found in this analysis

**1. Suspension takes two people; undoing it takes one.**
`POST /voters/{m}/approve` sets `status='active'` from any non-active status and **does not clear `suspension_status`**. A single `manageVoters` holder can therefore restore a confirmed-suspended voter to voting — leaving the row in the contradictory state **`status='active'`, `suspension_status='confirmed'`**, which passes `isVoterInElection()` and votes while the record still says *suspended*.

**2. The four-eyes control is bypassable by a parallel route.**
`POST /voters/{m}/suspend` sets `status='inactive'` directly, with only `manageVoters` and **no second actor**, achieving the same ballot-blocking effect as the governed two-actor flow while leaving `suspension_status='none'`.

**Both are `OBSERVED IN CODE`. Neither endpoint was executed** — this commission forbids further experiments. **Whether either constitutes a violation depends on a business rule that does not yet exist**, so both are **PRODUCT OWNER / ARB DECISION REQUIRED**, not defects I may declare.

> **Consequence for my earlier G-6 withdrawal:** four-eyes **is** genuinely enforced on `confirm-suspension` — that withdrawal stands. **But the control it provides is weaker than it appeared**, because the protected outcome is reachable by two ungoverned single-actor paths. **I under-scoped the question the first time: I asked "is the confirm guarded?" and not "is the outcome reachable another way?"**

---

## G · Revocation semantics

**BUSINESS RULE NOT SPECIFIED** for revoking an election entitlement.

* `revoke` appears **0 times** in `ElectionConstitution`.
* `ADR-003` *"Governance-Driven Revocation"* (**Accepted**) governs revoking **verification**, a different object. **Third vocabulary collision.**
* Its reusable and directly applicable principle: revocation **does not** automatically invalidate past votes, change results, reopen audits or void certifications — *"these are governance decisions, made by policy, not by verification logic."*
* The nearest **modelled** equivalent is `MembershipLineage` `TERMINATED` — terminal and irreversible, with guarded transitions from ACTIVE or SUSPENDED.
* Today the closest election-side operation is `remove()` → `status='removed'`, which **is** honoured by both voting predicates.

---

## H · Having-voted semantics

**OBSERVED FACT:** `markAsVoted()` writes `has_voted=true`, `voted_at`, **and `status='inactive'`**.

**So `status='inactive'` now has three distinct producers:**

| Producer | `suspension_status` | `has_voted` | Business meaning |
|---|---|---|---|
| `markAsVoted()` | `none` | `true` | consumed their vote |
| `suspend` (single actor) | `none` | `false` | suspended, ungoverned |
| `confirmSuspension()` | `confirmed` | `false` | suspended, governed |

**INTERPRETATION:** *having voted* and *being suspended* are different business facts sharing one representation. They remain distinguishable **only** by reading `has_voted`/`suspension_status` alongside — which the enforcing predicate does not do. **ADR-003's principle protects the vote itself:** a cast vote is not undone by a later status change.

---

## I · Business decision ownership

| Decision | Owner | Evidence |
|---|---|---|
| Whether an entitlement survives organisation change | **Product Owner** — ✅ decided (Model B) | this record |
| What `admit` / `suspend` / `revoke` / `restore` mean for a voter | **Product Owner / ARB** | absent from the Constitution |
| Whether suspension is temporary or indefinite | **Product Owner / ARB** | **BUSINESS RULE NOT SPECIFIED** |
| Whether restoring requires the same authority as suspending | **Product Owner / ARB** | asymmetry above |
| Which component is the authoritative eligibility engine | **ARB** | `ADR-002` + `VotingEligibilityPolicy` vs measured runtime |
| Whether `ElectionMembership` extends `MembershipLineage`'s model | **ARB** | **the pivotal question — §O** |
| Persistence representation (`status`, `suspension_status`, other) | **Engineering — but only after the four above** | commission §6 |

---

## J · Constitution / authoritative-source alignment

| # | Model B rule | Article / source | Verdict |
|---|---|---|---|
| 1 | Election owns its voter rules; org mutations don't retroactively change them | `VoterSourceStrategy` — *"Election snapshot is sovereign runtime authority… IMMUTABLE… org mutations don't retroactively change them"* | **A — ALIGNED.** Model B extends rule-level sovereignty to the individual entitlement |
| 2 | Entitlement is **retained** despite later org membership change | `ADR-002` + `UBIQUITOUS_LANGUAGE`: eligibility is *"assessed at action time"*, with *"Membership status (Active/Expired/Suspended)"* as a **dimension** | 🔴 **D — AMBIGUOUS / CONFLICT.** Both are coherent; they answer retention **differently**. See below |
| 3 | Election-Only needs no org `Member` | `VoterSourceStrategy` + measured data | **A — ALIGNED** |
| 4 | Chief may suspend a voter | Constitution: voter-level `suspend` **absent** (all 6 are election-level); authority exists only in application routes + `manageVoters` | **B — IMPLEMENTATION GAP** (authority real, constitutionally unnamed) |
| 5 | Suspension restricts, does not destroy | `ElectionConstitution:126-128` — capability freeze, no fact mutation | **A in principle · B in practice** — behaviour mutates `status` |
| 6 | Entitlement can be restored | no `restore` concept; `approve` is a de facto ungoverned restore | **C — CONSTITUTIONAL GAP** + implementation gap |
| 7 | Entitlement can be revoked | `revoke` absent for this object; `ADR-003` revokes verification | **C — CONSTITUTIONAL GAP** |
| 8 | Suspended ≠ has voted | one `status` value, three producers | **B — IMPLEMENTATION GAP** |
| 9 | Eligibility has one authority | `VotingEligibilityPolicy` declared authoritative, **0 callers** | **B — IMPLEMENTATION GAP** (the largest) |

### 🔴 Rule 2 — the conflict I must not resolve

`ADR-002` (**Accepted**, 2026-05-30) presents as its **"✅ Good"** model:

```
eligibility_status (computed from membership at check time)
```

and its worked eligibility example checks *"Active membership? Paid fees? Full member type? Enrolled? Window open?"*

**That is continuous evaluation** — the model the Product Owner rejected in favour of Model B for the *retention* question. **Two accepted sources now disagree**, and the disagreement is narrow and precise:

* **`ADR-002`** — eligibility, **including** organisation membership status, is re-evaluated at every action.
* **`Model B`** — organisation membership is checked **at admission**; the entitlement then persists.

**They can be reconciled two ways, and choosing is a governance act:**
1. **Read `ADR-002`'s "membership" as `ElectionMembership`** — then eligibility re-checks the *election* entitlement each time, and both hold. Requires confirming that reading against `ADR-002`'s own words *"Active membership? … Full member type?"*, which are organisation concepts.
2. **Model B amends `ADR-002`** — the entitlement axis is added and eligibility no longer re-evaluates organisation membership for retention. **`ADR-002` would need a superseding decision.**

**PRODUCT OWNER / ARB DECISION REQUIRED.** I have deliberately **not** classified this as A, B or C.

---

## K · Implementation gaps

| ID | Gap |
|---|---|
| **IG-1** | 🔴 `VotingEligibilityPolicy`, the declared authoritative eligibility engine, has **0 production callers** |
| **IG-2** | Voter suspension is enforced **incidentally** via `status='active'`; nothing reads `suspension_status` |
| **IG-3** | The entry surface (`elections.show`, `elections.start`) reports a suspended voter as **eligible** and **issues a credential** |
| **IG-4** | `approve` restores a confirmed-suspended voter **single-handedly**, leaving `status='active'` + `suspension_status='confirmed'` |
| **IG-5** | Single-actor `suspend` reaches the same outcome as the two-actor flow, **bypassing four-eyes** |
| **IG-6** | `suspend` / `approve` write no `voting_security` audit record; **`ElectionMembership` emits no domain events** |
| **IG-7** | `status='inactive'` has three producers (voted / two suspension paths) |
| **IG-8** | Admission policies `ElectionOnlyPolicy::isEligible()` / `FullMembershipPolicy::isEligible()` are **stubs returning `true`**; the real logic sits in `decideForContext()`, reachable only via infrastructure |
| **IG-9** | The `election_memberships` FK comment claims it proves organisation **membership**; it proves a role row, and the `Member` table is empty |

## L · Constitutional gaps

| ID | Gap |
|---|---|
| **CG-1** | **`admit`** — no constitutional action; admission is the act that creates the entitlement Model B rests on |
| **CG-2** | **`restore`** — no concept at all |
| **CG-3** | **`revoke`** — absent for entitlement (`ADR-003` revokes verification) |
| **CG-4** | **`suspend` collision** — election-level constitutionally, voter-level in the application |
| **CG-5** | `ElectionMembership` appears **0 times** in the Constitution — the entitlement has no constitutional existence |
| **CG-6** | Suspension **duration/review** obligation unspecified |
| **CG-7** | Whether restoring requires the same authority as suspending is unspecified |

## M · Reachability gaps

| ID | Gap |
|---|---|
| **RG-1** | **Full Membership mode cannot be exercised** — and the `Member` aggregate has **0 rows platform-wide**, so there is nothing to admit from (`IERVP-2`, `IERVP-3`) |
| **RG-2** | **Restoration cannot be exercised as a governed act** — no restore path exists (only ungoverned `approve`) |
| **RG-3** | **Revocation-as-entitlement-termination cannot be exercised** — only `remove()` |
| **RG-4** | Election-Only: admit → suspend → **restore** → vote is **not** end-to-end reachable, because the restore leg does not exist |

## N · Open business decisions

1. **Does Model B amend `ADR-002`, or is `ADR-002`'s "membership" to be read as `ElectionMembership`?** (§J rule 2)
2. **Is voter suspension temporary or indefinite** — and does it carry a review obligation?
3. **Does restoring require the same authority and actor count as suspending?**
4. **Is revocation terminal and irreversible** (as `TERMINATED` is organisation-side)?
5. **What is the authoritative eligibility engine** — `VotingEligibilityPolicy`, or the predicate the ballot actually uses?
6. **Must the entry surface refuse a suspended voter**, or is refusing at the ballot sufficient?
7. **May a credential be issued to a suspended voter?** (Today it is.)
8. **Do voter-level entitlement changes require domain events / audit records?**

## O · Recommended next governance decision — one question, first

> **Does the election entitlement EXTEND the Membership context's already-modelled lifecycle (`ACTIVE → SUSPENDED → ACTIVE`, `→ TERMINATED` terminal, guarded transitions, policy-interpreted, `Never use aggregate methods for authorization`), or is it a genuinely separate lifecycle owned by the Election context?**

**Why this one first — everything else is downstream of it:**

* If it **extends**, then `suspend`/`restore`/`terminate` are **already defined**, `ADR-002`'s separation already applies, `VotingEligibilityPolicy` is already the intended engine (**IG-1** becomes the fix, not a new design), and **`CG-1`…`CG-3` shrink to naming the election-scoped counterparts.** Per **ES-005.4 — consume or extend, never create a second** — this is the default an existing model earns.
* If it is **separate**, the Election context needs its own state model, its own policy and its own constitutional actions — **and the ARB must say so explicitly**, because that is a decision to hold two lifecycles for two objects that behave alike.

**Second recommendation — route the vocabulary through the review that already exists.** `VoterSourceStrategy` marks its own case names `@deprecated`, *"TRANSITIONAL SEMANTIC BRIDGE VOCABULARY"*, pending a **"Phase 4 governance-language review"** to fix final constitutional names. **The voter-level verbs (`admit`/`suspend`/`restore`/`revoke`) belong in that same review rather than a parallel naming exercise** — one governance-language decision, not two.

**Third — do not touch `status` yet.** The representation chain, in order, and only the first four are in scope before implementation:

```
CURRENT REPRESENTATION  ->  BUSINESS MEANING  ->  DESIRED AUTHORITATIVE MODEL
     ->  APPLICATION ENFORCEMENT  ->  [then, and only then]  POSSIBLE PERSISTENCE REPRESENTATION
```

`status='inactive'` is currently the **only** enforcement of the Chief's authority. **It must not be vacated before its replacement enforces.**

---

## Stream and scope boundaries

* **`PBDIGIT-69` is kept strictly separate.** The tenant-unaware eligibility cache is a **TECHNICAL / APPLICATION DEFECT**. It is **not** evidence for or against Model B and is not cited in any alignment verdict above. It appears here only in this sentence.
* **Session 1's work is untouched** — no change to the Master Matrix, denominator, `SD-1`, `SD-2`, Slice 1 classification or the `PBDIGIT-48` execution plan, and none of them is treated as authority for this analysis.
* **No runtime experiment was repeated.** The G-1 result is consumed as established evidence.
* **Nothing was named, renamed, refactored, migrated or amended.** No test was changed or made green.

**Traceability:** `docs/adr/ADR-002-verified-eligible-authorized.md` (Accepted) · `docs/adr/ADR-003-governance-driven-revocation.md` (Accepted) · `docs/architecture/trust-domain/UBIQUITOUS_LANGUAGE.md:74-110,274-300` · `app/Domain/Election/Constitution/ElectionConstitution.php:23-147` (esp. `126-128`) · `app/Domain/Election/Enum/VoterSourceStrategy.php:10-60` · `app/Contexts/Membership/Domain/Voting/VotingEligibilityPolicy.php:20-75` · `app/Contexts/Membership/Domain/Membership/MembershipLineage.php:153-169,223,297,334-345,367-372` · `app/Contexts/Membership/Domain/Member/{Member.php:90-127,MemberStatus.php:9-120}` · `app/Contexts/Elections/Domain/Policies/{ElectionOnlyPolicy,FullMembershipPolicy}.php` · `app/Contexts/Elections/Infrastructure/Policies/EloquentVoterEligibilityQueryService.php:57-61` · `app/Models/ElectionMembership.php:164-171,202-224` · `app/Models/User.php:315-328` · `app/Http/Controllers/ElectionVoterController.php:196-216,222-240,290-356` · `routes/organisations.php:272-277` · `database/migrations/2026_03_17_213212_create_election_memberships_table.php:24-58` · measured read-only 2026-08-12: `members` 0 rows · `user_organisation_roles` 20 rows (`member`, `owner`) · `VotingEligibilityPolicy` 0 production callers.

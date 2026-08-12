# Election-Only Admission — Governance Gate

**Type:** Governance decision package · **Date:** 2026-08-12 · **Programme:** IERVP (Session 2)
**Verdict (UPDATED 2026-08-12):** ## 🟡 **READY WITH ONE EXPLICIT BLOCKER — `BR-1.12`**
*(`Q-B1` is now **CLOSED** at the business level by the Product Owner — see §0. The original verdict below, `NOT READY — BUSINESS DECISION REQUIRED`, stood on two open decisions; one has been answered.)*

**⛔ NO PRODUCTION CODE CHANGED · NO SCHEMA CHANGED · NO MIGRATION CHANGED · NO TEST CHANGED · NO FULL-MEMBERSHIP DESIGN AUTHORIZED.**
`ADR-002` not amended · Constitution untouched · Officer Guide not promoted · no FK repaired · no `Member` created · `FullMembershipPolicy` not activated · `scopeEligible()` not implemented · suspension, voting gates and credential behaviour untouched · **Session 1's Master Matrix not read, classified, consumed or modified.**

---

## 0 · CLOSURE UPDATE — `Q-B1` closed · `CG-1`…`CG-4` reclassified · and a correction I owe

### 0.1 🔴 I was wrong about `CG-3`, and the Product Owner was right

**The Product Owner stated that the FK to `user_organisation_roles` was *"deliberately removed for Election-Only support."* I replied that my investigation had NOT established that and that the reason was unknown. That reply was WRONG — I had not searched the migration history.**

**Found: `database/migrations/2026_05_19_000001_harden_election_memberships_for_election_only_mode.php` — "Phase C.1":**

> *"**Drop composite FK to `user_organisation_roles`** — Root bug: **FK blocks election-only mode** (users only have `OrganisationUser`, not `UserOrganisationRole`) — **Application-level `VoterQualificationPolicy` takes over this validation.**"*
>
> *"**WARNING:** After this migration, application code MUST validate that users exist in: `organisation_users` (election-only mode) · `members` with paid/exempt fees (full membership mode). **The database FK is gone. Integrity is now application responsibility.**"*

**The removal was deliberate, documented, and explicitly FOR Election-Only support.** And my earlier characterisation of the create-table migration's comment as *"false as a business statement"* is **superseded**: the guarantee **was** enforced, then **intentionally dropped**, with responsibility **explicitly transferred to the application layer.** **A documented handover, not a silent gap.**

> **Following this up produced two better findings — which is the argument for checking rather than deferring:**
>
> 1. 🔑 **The documented design premise is that Election-Only users have `OrganisationUser` but NOT `UserOrganisationRole`.** **Measured: the 5 Election-Only voters have BOTH (5/5 each).** **So the `user_organisation_roles` rows contradict the stated design intent** — which sharpens `CG-2`, and *supports* the adopted business rule.
> 2. ⚠️ **The designated successor `VoterQualificationPolicy` DOES NOT EXIST** — the name appears nowhere in `app/`. The validation it was to perform **is** carried out by differently-named components (`EloquentVoterEligibilityQueryService`, which *"queries `organisation_users` table only"*, and `VoterEligibilityService`). **So the responsibility was picked up, but not by the named policy — and whether it is actually wired remains `MECHANISM NOT ESTABLISHED`, because the domain policies are stubs returning `true`.**

### 0.2 `Q-B1` — CLOSED at the business level

**Adopted Product Owner decision:**

> **Election-Only admission does NOT create Organisation Membership.**

**The semantic distinction, recorded as authoritative:**

| Term | Means |
|---|---|
| **Organisation Membership** | **the `Member` aggregate** |
| **`ElectionMembership`** | **election-specific election participation** |
| `organisation_users` | **technical organisation/tenant association**, unless separately established otherwise |
| `user_organisation_roles` | **organisation role assignment**, unless separately established otherwise |

**`Q-B1` is therefore no longer posed as *"which of three concepts does Organisation Membership mean?"*** — the Product Owner has answered: **the `Member` aggregate.** **Measured `members` = 0 of 5 → the system CONFORMS to the adopted rule.**

**I do not infer that the technical rows may be removed, design no replacement schema, and do not decide whether admission should create them.** That is implementation conformance work for Session 3.

### 0.3 `CG-1`…`CG-4` reclassified

| # | Finding | **Classification** | Basis |
|---|---|---|---|
| **CG-1** | `ElectionOnlyPolicy` requires `organisation_users`; admission creates it | **IMPLEMENTATION CONFORMANCE QUESTION** | **Not a business violation.** The May migration's WARNING explicitly requires application code to *validate* `organisation_users` for Election-Only, so a tenant-enrolment **prerequisite** is the documented design. Whether admission should **create** it or require it to **pre-exist** is for Session 3 |
| **CG-2** | `user_organisation_roles.role = 'member'` | **IMPLEMENTATION CONFORMANCE QUESTION** *(strengthened)* | **The literal role value is NOT equated with the `Member` aggregate.** Whether the role carries organisation-membership semantics or is merely an authorisation/linkage role is **`SEMANTIC NOT ESTABLISHED`** — no authoritative source defines it. ⚠️ **But the May migration's premise says Election-Only users have *only* `OrganisationUser`, and they also have the role row — so its presence contradicts the documented design.** **Not renamed, not removed** |
| **CG-3** | Absence of the FK | ✅ **NO LONGER RELEVANT — BUSINESS-CONFORMANT** | **Deliberate and documented** (§0.1). **Closed; not reopened** |
| **CG-4** | Officer Guide and tests encode the organisation linkage | **OBSERVED LEGACY / AMBIGUOUS BEHAVIOUR** | Retained as **evidence** about existing implementation and documentation. **Not authority** |

**No repair proposed for any of them.**

### 0.4 Full Membership — not reopened

**`FM-1`…`FM-15` remain frozen.** Nothing here investigates or designs Full Membership admission, the `Member`→`ElectionMember` lifecycle, organisation-driven suspension, Full Membership restoration or Full Membership import.

### 0.5 What remains

> **`BR-1.12` is the ONLY remaining Election-Only admission business decision.** §4 below states it; §0.6 puts it as a single question.

### 0.6 The question for the Product Owner / ARB

> ## `BR-1.12`
> **In Election-Only Mode, when the Election Chief imports a person as an ElectionMember, is that ElectionMembership immediately `ACTIVE`, or is it initially `INVITED` and requiring explicit Election Chief approval before becoming `ACTIVE`?**

| | **Option A — immediately `ACTIVE`** | **Option B — `INVITED` → approval → `ACTIVE`** |
|---|---|---|
| **Consequence: officer workload** | Import is one step; no approval pass needed | Every imported voter needs an explicit approval before they can vote |
| **Consequence: risk** | A mis-imported person is **immediately** a voter | A mis-imported person **cannot vote** until approved — an error-catching gate |
| **Consequence: existing estate** | Tests that construct `invited` assert a state the business has abolished; the UI's `invited` pill and `invited → active` action become dead; documentation must change **twice** *(guide + dashboard)* | An approval step must be built that **has never operated**; the four layers that already expect it become correct |
| **Consequence: the voter's experience** | Appears on the roll able to vote as soon as import completes | Appears on the roll unable to vote until an officer acts — **and the officer guide already tells officers to "approve voters before opening voting"** |
| **Evidence for** | **Production behaviour only** — import writes `'active'`; assignment uses the model default `'active'`; **no production path writes `'invited'`** | **Schema** (enum value) · **UI** (label, blue pill, `invited → active` action) · **Tests** (`makeMembership('invited')`, one asserting persistence) · **Documentation** (twice) |

**Authority: `BUSINESS RULE NOT SPECIFIED.`** `ElectionConstitution` is silent (`admit` = **0** occurrences) · `ADR-002` is silent · clause 10's *"directly"* addresses the **absence of an organisation prerequisite**, not an approval step · the Officer Guide's claim is **`CONTRADICTED`** and carries **no authority**.

> **I do not choose. Note the direction the discipline cuts here: production is the ONLY evidence for Option A, so deferring to the implementation would select A by default — which is exactly what must not happen.**

---

## ⚠️ Earlier in this package — a correction to my own previous report

**The Product Owner challenged my classification of `BR-1.13` (suspension actor count) as an unconditional blocker "before ANY Election-Only work". The challenge is correct, and my report was internally inconsistent:** it listed `BR-1.13` in Tier 1 as blocking *any* work, then said Tier 1 lets Session 3 *"begin, starting with admission"*. **Both cannot be true.**

**`BR-1.13` blocks the SUSPENSION slice. It does not block ADMISSION.** Reclassified:

| Gate | Decisions |
|---|---|
| **Election-Only ADMISSION** *(this package)* | **`Q-B1`** · **`BR-1.12`** |
| Election-Only SUSPENSION *(later)* | `BR-1.13` · `Q3` |
| Election-Only REMOVAL / RESTORE *(later)* | `BR-1.1`/`1.2` · `BR-1.8` |
| Coherence *(schedulable)* | `Q-E1` · `Q-E2` |

**My earlier Tier-1 framing over-scoped the gate. The admission gate is two decisions, not two-plus-suspension.**

---

## 1 · Scope

**This package covers ELECTION-ONLY ADMISSION ONLY.**

```
Person ──► Election-Only admission ──► ElectionMembership ──► election-specific voter
```

**Not in scope:** suspension · restoration · removal · exercisability representation · credential control · every Full Membership question (`FM-1`…`FM-15`, frozen).

## 2 · Adopted business rules

| # | Rule | Status |
|---|---|---|
| **1** | `ElectionMember` is **election-specific** | ADOPTED |
| **2** | `Organisation Member` is **organisation/tenant-specific** | ADOPTED |
| **3** | **Election-Only admission does not create Organisation Membership** | ADOPTED *(Product Owner, this commission)* |
| **4** | **Full Membership is deferred** | ADOPTED |
| **5** | `ElectionMember` **≠** `OrganisationMember` | ADOPTED |
| **6** | Removing `ElectionMembership` **never** removes Organisation Membership | ADOPTED *(clause 6)* |

---

## 3 · `Q-B1` — does Election-Only admission create, require or imply Organisation Membership?

**Business decision:** **NO** *(Product Owner)*.
**Authority:** Product Owner, this commission · consistent with clause 9 *(no Organisation Membership required)* and `VoterSourceStrategy` *(Election-Only draws from org enrolment, not the `Member` table)*.

### 3.1 🔑 The question has three answers, because "Organisation Membership" denotes three things

**Measured on the live Election-Only election (5 admitted voters):**

| Concept | Table | Platform rows | Created for the 5 voters? | Carries membership semantics? |
|---|---|---|---|---|
| **Business `Member` aggregate** | `members` | **0** | ❌ **0 / 5** | ✅ **yes** — `membership_number`, `membership_type_id`, `fees_status`, `membership_expires_at`, `joined_at` |
| **Organisation enrolment** | `organisation_users` | 14 | ✅ **5 / 5** | ❌ a tenant link |
| **Organisation role** | `user_organisation_roles` | 20 | ✅ **5 / 5**, `role = 'member'` | ❌ a role assignment — **but the value is literally the word `member`** |

> ### The finding, stated precisely
>
> **The adopted rule HOLDS for the concept that carries membership semantics — no `Member` aggregate is created — and DOES NOT HOLD for the two linkage tables, both of which admission creates.**
>
> 🔴 **So Election-Only admission makes a person an organisation `member` BY ROLE while not making them a `Member` BY AGGREGATE.** Whether that satisfies *"does not create Organisation Membership"* is **the decision the Product Owner must make**, and it cannot be settled from the code: **the word `member` appears on one side of it.**

### 3.2 Conformance investigation

| Question | Finding | Class |
|---|---|---|
| **Does the schema REQUIRE organisation membership?** | **No.** The migration declares a composite FK to `user_organisation_roles`, **but it is ABSENT from the live schema** — only `users`(SET NULL) and `elections`(CASCADE) exist. **So no persistence rule forces it** | **MEASURED** |
| **Does the admission implementation create it?** | ⚠️ **Yes — both linkages.** `organisation_users` 5/5 and `user_organisation_roles` 5/5 | **MEASURED** |
| **Does the import path create it?** | ⚠️ **Yes** — the import creates users and their organisation linkage as part of admission | **MEASURED** |
| **Does any FK force it?** | **No** — see above. The declared constraint is unenforced | **MEASURED** |
| **Does the UI imply it?** | ⚠️ **Yes** — the voter list surfaces an error *"not a member of this organisation"* | **OBSERVED IN CODE** |
| **Do existing tests encode it?** | ⚠️ **Yes** — a voter-eligibility test asserts assignment is rejected for a non-member, and the suspension test helper notes *"Factory already creates UserOrganisationRole"* | **OBSERVED IN CODE** |
| **Legacy mechanisms conflating the two?** | ⚠️ **Yes, three.** The migration comment asserts the FK proves *"the user is actually a member of this organisation"* *(false — the FK is absent)*; `ElectionOnlyPolicy`'s declared rule is *"User must be in `organisation_users`"*; and `VoterSourceStrategy` describes Election-Only as *"direct org enrollment"* | **OBSERVED IN CODE** |

### 3.3 Conformance gaps — **reported, NOT repaired**

| # | Gap |
|---|---|
| **CG-1** | 🔴 **Admission CREATES the organisation linkage it is designed merely to REQUIRE.** `ElectionOnlyPolicy` states the user *"must be in `organisation_users`"* — a **precondition** — yet the import **creates** that row as part of admitting the voter. **A precondition that the act itself satisfies is not a precondition** |
| **CG-2** | 🔴 **`user_organisation_roles.role = 'member'`** — admission assigns a role whose **name is the very concept the adopted rule says must not be created** |
| **CG-3** | **The declared FK is absent from the live schema**, so the migration comment's guarantee is unenforced, and an `ElectionMembership` can exist with no organisation linkage at all |
| **CG-4** | **The Officer Guide and existing tests encode the organisation-linkage requirement**, so a decision that the linkage must *not* be created would put both out of conformance |

**No repair proposed. No cascade/restrict/null choice made. No architecture designed.**

> **IMPLEMENTATION IS NOT AUTHORISED BY THIS INVESTIGATION.**

### 3.4 The decision required

> **`Q-B1`(a): Does *"Organisation Membership"* in the adopted rule mean (i) the business `Member` aggregate only, (ii) the aggregate plus the role assignment, or (iii) any organisation linkage at all?**
>
> * Under **(i)** the system **already conforms** — nothing to change; `CG-1`/`CG-2` become vocabulary matters.
> * Under **(ii)** or **(iii)** the system **does not conform**, and Election-Only admission must change — **which is a later authorised slice, not this package.**

**PRODUCT OWNER DECISION REQUIRED.** I have not chosen, because the answer determines whether current behaviour is conformant or defective, and the word `member` sits on both sides.

---

## 4 · `BR-1.12` — the lifecycle state immediately after Election-Only admission

**Exact question:** **After Election-Only admission, is the `ElectionMembership` `active`, or `invited` pending Election Chief approval?**

| | **Option A** — `active` directly | **Option B** — `invited` → approval → `active` |
|---|---|---|
| **Evidence for** | **The only behaviour production exhibits.** Import writes `'active'` explicitly; assignment relies on the model default `'active'`; **no production path anywhere writes `'invited'`** | **Schema** — `invited` is an enum value · **UI** — status label *"Invited"*, dedicated blue status pill, and an action commented `<!-- Approve (invited → active) -->` · **Tests** — `makeMembership('invited')` used repeatedly, one asserting it persists · **Documentation** — twice: *"The voter appears in the table with `invited` status"* and *"Voters with 'Invited' or 'Suspended' status cannot vote"* |
| **Evidence against** | Four layers expect an approval gate that would not exist | No production writer; the gate has **never operated** |

**Contradiction:** 🔴 **Schema, UI, tests and documentation all expect `invited`; production never produces it.** Four layers describe a gate; one omits it.

**Authoritative sources that settle it:** **NONE.**
* `ElectionConstitution` — **silent**; no voter-level action, and `admit` appears **0 times**.
* `ADR-002` — **silent** on admission state.
* Clause 10 — *"may become an ElectionMember directly"* speaks to **no organisation prerequisite**, **not** to an approval step. **Reading it as Option A would be an over-reading, and I decline to make it.**
* Officer Guide — makes the claim, but **classified `CONTRADICTED`** (§6) and it carries **no authoritative status**.

> **`BUSINESS RULE NOT SPECIFIED.`**
>
> **I do not choose between A and B, and I do not promote `invited` merely because the code contains the value.** **Note the direction of that discipline here: the code is the ONLY evidence for Option A, so deferring to the code would choose A by default. That is precisely what must not happen.**

**PRODUCT OWNER / ARB DECISION REQUIRED.** *(One consequence worth knowing: under **A**, existing tests that construct `invited` assert a state the business has abolished; under **B**, an approval step must exist that never has.)*

---

## 5 · Mode boundary — what Session 3 may implement

**Established previously and unchanged: `VoterSourceStrategy` is consulted at admission and nowhere else.**

| | |
|---|---|
| ✅ **May proceed** *(demonstrably Election-Only scoped)* | `ElectionOnlyPolicy` · `EligibilityContext` · `VoterImportService::previewElectionOnly` / `importElectionOnly` · admission paths **where the mode is already a parameter** (`ElectionVoterController:74,113,126,157`; `VoterEligibilityService::isEligibleVoter($org,$user,$mode)`) |
| 🔴 **Must NOT be changed under this gate** | Every **mode-blind** post-admission path: `suspend` · `approve` · `propose/confirmSuspension` · `cancelProposal` · `destroy` · `ElectionMembership`'s own methods · `User::isVoterInElection()` · `EnsureElectionVoter` · `VoteEligibility` · `ElectionVotingController::show/start` · the `EnsuresVoterMembership` trait *(3 consumers)* |
| ⛔ **Deferred** | `FullMembershipPolicy` · `scopeEligible()` · `members` · the `MembershipSuspended`/`Terminated`/`Restored` subscription |

**Governance rule (restated):**

> **Election-Only implementation may proceed only where the changed behaviour is demonstrably Election-Only scoped. If a proposed change would simultaneously define Full Membership behaviour: STOP · REPORT THE CROSS-MODE IMPACT · DO NOT IMPLEMENT.**

**Favourable fact for this gate:** **admission is the one area where the mode is already a parameter**, so an Election-Only admission change is separable **by construction** — unlike suspension or removal. **The gate and the seam coincide.**

## 6 · Officer Guide statements bearing on admission

| Statement | Class |
|---|---|
| *"Only organisation members can be assigned as voters"* | 🔴 **UNSUPPORTED** — the FK it would rest on is **absent**; and ⚠️ it is **self-fulfilling**, since admission creates the linkage (`CG-1`) |
| Assignment yields **`invited`**; approval then required | 🔴 **CONTRADICTED** — no production writer |
| *"Approving changes a voter's status from `invited` or `inactive` to `active`"* | **OPERATIONAL GUIDANCE** — describes Option B's mechanics |
| *"Approve voters before opening voting"* | **OPERATIONAL GUIDANCE** *(advisory)* |
| *"Available to Chief and Deputy Election Officers only"* | **SUPPORTED** — agrees with `manageVoters` and with tests |

**Recorded as evidence. Not promoted. No governance status conferred.**

## 7 · Dependencies — genuine only

| Dependency | Genuine? |
|---|---|
| **`Q-B1`(a)** — which concept *"Organisation Membership"* denotes | ✅ **YES** — determines whether current admission conforms or must change |
| **`BR-1.12`** — resulting lifecycle state | ✅ **YES** — determines what admission *does* |
| `BR-1.13` suspension actor | ❌ **NO** — different workflow *(my earlier classification corrected)* |
| `Q3` exercisability | ❌ **NO** for admission — no admission change alters non-exercisability |
| `Q-E1` / `Q-E2` credential & distinguishability | ❌ **NO** — post-admission concerns |
| `BR-1.1`/`1.2`, `BR-1.8` removal & restore | ❌ **NO** — later slices |
| `FM-1`…`FM-15` | ❌ **NO** — frozen, and **no dependency discovered** |

**Two dependencies. I have not inflated the list, and I found no hidden dependency on the deferred set.**

## 8 · `ADR-002` assessment for Election-Only admission

**Verdict: `ADR-002` is SILENT on both admission questions — therefore APPLICABLE and NOT BLOCKING. NO AMENDMENT REQUIRED for this gate.**

| Aspect | Assessment |
|---|---|
| Does it address whether admission creates Organisation Membership? | **Silent** |
| Does it address the post-admission lifecycle state? | **Silent** |
| Does anything in it **contradict** either candidate answer? | **No** |
| Is its *"eligibility computed from membership at check time"* an obstacle? | **No** — for admission it is simply not engaged |

**Recommendation: leave `ADR-002` unchanged.** *(The separate v2 amendment proposal remains open and is not required by this gate.)*

## 9 · Decisions required from ARB / Product Owner

| # | Decision | Cost |
|---|---|---|
| **1** | **`Q-B1`(a)** — does *"Organisation Membership"* mean the **`Member` aggregate only** (i), **aggregate + role** (ii), or **any organisation linkage** (iii)? | **Cheap** — a definitional choice; no analysis needed |
| **2** | **`BR-1.12`** — **Option A** (`active` directly) or **Option B** (`invited` → approval)? | **Cheap** — a workflow choice; evidence for both is already laid out |

**Both are business definitions, not investigations. Once answered, Election-Only admission is authority-complete.**

## 10 · Explicit statement

> **NO PRODUCTION CODE CHANGED · NO SCHEMA CHANGED · NO MIGRATION CHANGED · NO TEST CHANGED · NO FULL-MEMBERSHIP DESIGN AUTHORIZED.**

Additionally: no FK repaired · no `Member` created · `FullMembershipPolicy` not activated · `scopeEligible()` not implemented · suspension, voting-gate and credential behaviour untouched · Constitution not amended · `ADR-002` not amended · Officer Guide not promoted · `BR-1.13`, `Q3`, `Q-E1`, `Q-E2`, `BR-1.1`/`1.2`, `BR-1.8` and `FM-1`…`FM-15` **not resolved** · **Session 1's Master Matrix not read, classified, consumed or modified.**

---

**Traceability:** `app/Contexts/Elections/Domain/Policies/ElectionOnlyPolicy.php:14-18,74-80` · `app/Services/VoterImportService.php:35-37,194-240,298,310` · `app/Services/VoterEligibilityService.php:14-37` · `app/Http/Controllers/ElectionVoterController.php:74,98,113,126,140,157` · `app/Models/ElectionMembership.php:67` *(default `active`)* · `database/migrations/2026_03_17_213212_create_election_memberships_table.php:25,36-45` · `resources/js/Pages/Elections/Voters/Index.vue:301,465,985-986` · `tests/Feature/Election/ElectionVoterManagementTest.php:49,65,81,92` · `tests/Feature/Election/VoterEligibilityTest.php:335` · `docs/election_management/04-voter-list.md:59,111,119,130,133` · `docs/election_management/03-management-dashboard.md:97` · `app/Domain/Election/Enum/VoterSourceStrategy.php:10-60` · measured 2026-08-12: `organisation_users` **14 rows / 5-of-5** · `user_organisation_roles` **20 rows / 5-of-5, `role='member'`** · `members` **0 rows / 0-of-5** · live FKs on `election_memberships` = `users`(SET NULL), `elections`(CASCADE) **only** · **no production writer of `invited`**.

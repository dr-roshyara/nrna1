# `D-ENT-1` APPROVED — consequences package

**Type:** Governance package (approval record · ADR amendment **proposal** · domain consequences · `Q3` options · constitutional alignment **proposal**)
**Date:** 2026-08-12 · **Programme:** IERVP (Session 2)

| Item | Status |
|---|---|
| **`D-ENT-1`** | ✅ **APPROVED — Model B** (Product Owner, 2026-08-12) |
| **`D-ENT-1a`** — ADR-002 treatment | ✅ **APPROVED: amend explicitly** · amendment **PROPOSED below, ADR-002 NOT edited** |
| **`D-ENT-1b`** / **`Q3`** — where exercisability lives | 🔓 **OPEN — not decided here** |
| **Constitutional alignment** | **PROPOSED** — Constitution **not** edited |
| **Implementation** | ⛔ **NOT AUTHORISED** |

**Mode:** governance/architecture only. **No production code, `ElectionMembership`, `status` field, migration, schema, test, credential, repository, entity or aggregate changed. No dormant code activated. No suspension or restore implemented. `ADR-002` and the Constitution are untouched.**
**Input:** [`2026-08-12-arb-decision-package-election-entitlement-retention.md`](2026-08-12-arb-decision-package-election-entitlement-retention.md) · [`2026-08-12-model-b-election-membership-domain-analysis.md`](2026-08-12-model-b-election-membership-domain-analysis.md) · [`2026-08-12-g1-voter-suspension-enforcement.md`](2026-08-12-g1-voter-suspension-enforcement.md)

---

## 1 · `D-ENT-1` approval record

**APPROVED BUSINESS RULE** (Product Owner, 2026-08-12), in the corrected wording:

> **`ElectionMembership` is the election-specific entitlement record.**
> **Admission creates an election-specific entitlement. That entitlement remains associated with the election unless a defined election-level revocation/removal rule terminates it.**
> **Whether that entitlement is currently *exercisable* is governed by the election's voting rules and voter-level suspension/governance state.**

**Mandatory distinctions (approved):**

| Concept | Meaning |
|---|---|
| `Member` | organisation/tenant-specific membership |
| `ElectionMembership` | **election-specific entitlement** |
| **Exercisability** | whether that entitlement may **currently** be exercised |
| **Suspension** | a **governance decision affecting exercisability** — **not** destruction of the entitlement |

**Explicitly NOT the approved rule:** *"`ElectionMembership` is always a durable right to vote."* **Too broad.** Also **not** approved: *"entitlement existence is durable"* **unqualified** — the Product Owner corrected this so Model B cannot drift into `import voter → permanent unconditional right to vote`.

> ⚠️ **Consequence of the correction, and it is load-bearing: the approved rule REQUIRES a defined revocation/removal rule in order to be complete.** Without one there is no termination condition, and the entitlement becomes unconditional by omission — the exact failure the correction prevents. **Revocation/removal is therefore promoted from "open question" to BLOCKING PREREQUISITE (`P-3`, §10).**

**Status discipline:** **Model B is the APPROVED BUSINESS MODEL. Model B is not "proven."** Full Membership runtime verification remains separate and outstanding. **And the current implementation is not asserted to implement Model B** — it demonstrably collapses several concerns into `status` and contains dormant, competing eligibility definitions.

---

## 2 · `ADR-002` amendment **proposal** (not applied)

### 2.1 What in `ADR-002` remains valid and must be preserved

| Preserved | Why |
|---|---|
| The **three-axis orthogonality** — Verified ≠ Eligible ≠ Authorized | Model B does not challenge it; it **adds** an axis |
| Each axis's **responsible context** and *"changes are localized to their domain"* | unaffected |
| **Example 1** (verification expires → cannot vote until re-verified) | concerns the **Verified** axis only |
| **Example 2** (fees unpaid → *"Cannot vote in Election **B**"*) | ⚠️ **compatible, and worth stating precisely: `ADR-002`'s own worked example is about admission to a FUTURE election, which Model B also gates. It is not a retention example.** |
| *"Revocation of verification doesn't automatically block all future participation"* | consistent with `ADR-003` and with Model B |
| The rejection of a single conflated `can_vote` boolean | Model B strengthens this |

### 2.2 The exact statements that become incompatible

**Three, and only three:**

| # | Location | Text | Why incompatible |
|---|---|---|---|
| **I-1** | `:161` | `eligibility_status (computed from membership at check time)` | If *"membership"* is organisation membership, retention is continuously re-evaluated — **Model B says it is evaluated at admission** |
| **I-2** | `:78` | *"Check: Active membership? Paid fees? Full member type? Enrolled? Window open?"* | Mixes **organisation** dimensions with **election enrolment** in one check, so enrolment appears as a dimension **of** eligibility rather than a **prior** axis |
| **I-3** | `:71` | *"Enabled By: Process-specific rules (membership status, fees, timing, scope)"* | Same conflation, in the definition of `Eligible` |

**Nothing else in `ADR-002` requires change.**

### 2.3 Proposed amendment

**Add a fourth axis, ahead of Eligible:**

> **0. Entitled** — **Definition:** the participant has been **admitted** to a specific election, and that admission has not been revoked or removed.
> **Responsibility:** Election context. **Scope:** election-specific. **Enabled by:** an admission decision.
> **Lifespan:** persists from admission until a defined revocation/removal rule terminates it. **It is NOT re-derived from organisation membership.**

**Amend `I-1` to:**

> `entitlement` — established at admission, election-specific, durable until revoked/removed
> `eligibility_status` — computed **at check time from the entitlement and the election's own rules**, **not** from organisation membership

**Amend `I-2`/`I-3` to separate the two questions:**

> **Admission-time** (does this person qualify to be admitted?) — governed by the election's `VoterSourceStrategy`: organisation membership status, fees and membership type apply **here**, and **only** here.
> **Check-time** (may this entitled person act now?) — entitlement exists · not suspended/revoked · election window open · scope correct.

**Amended authorization formula** — expressed as **decisions**, deliberately naming no storage, class or mechanism:

```
CanCastVote(actor, election) =
      Entitled(actor, election)        // admitted, not revoked/removed   [NEW AXIS]
   && Exercisable(actor, election)     // not suspended; election rules permit now
   && Verified(actor)                  // unchanged
   && Permission(actor, action, scope) // unchanged
```

**Explicitly out of scope for this amendment:** *how* `Exercisable` is represented or stored (**that is `Q3`**), and any tactical class, entity, repository or aggregate. **The amendment states meaning and ownership only.**

**Also proposed:** a note that `ADR-002`'s *"Current Implementation Evidence"* section is **stale** — it cites `Member voting_rights` and `EloquentVoterEligibilityQueryService` as the eligibility implementation, whereas the live gate is `User::isVoterInElection()`. **Recording staleness is not amending the decision.**

**⛔ `ADR-002` is NOT edited by this document.** Applying it requires explicit Product Owner authorisation after reviewing this proposal.

---

## 3 · Domain model consequences

**Labels:** `OBSERVED FACT` · `APPROVED BUSINESS RULE` · `ARCHITECTURAL CONSEQUENCE` · `BUSINESS RULE NOT SPECIFIED` · `MECHANISM NOT ESTABLISHED`. **No business rule is inferred from current code behaviour.**

| # | Question | Answer | Label |
|---|---|---|---|
| 1 | **What creates `ElectionMembership`?** | Today: voter import, or `store()` assignment | `OBSERVED FACT` |
| 2 | **What constitutes admission?** | **An admission decision by election authority.** No such act is named in the Constitution or in code; row creation is its de facto proxy | `ARCHITECTURAL CONSEQUENCE` — the act must be **named** (`CG-1`) |
| 3 | **What makes entitlement exist?** | Admission, and nothing else. **Not** organisation membership | `APPROVED BUSINESS RULE` |
| 4 | **What can terminate entitlement?** | **A defined election-level revocation/removal rule — which does not yet exist.** `remove()` → `status='removed'` is the only current mechanism and is *called* removal, never *defined* as it | `BUSINESS RULE NOT SPECIFIED` — **blocking (`P-3`)** |
| 5 | **What makes entitlement exercisable?** | Entitlement exists · not suspended · election rules permit now · credential valid | `APPROVED BUSINESS RULE` (composition) · **representation is `Q3`** |
| 6 | **What suspends exercisability?** | A Chief/committee governance decision | `APPROVED BUSINESS RULE`; **today it mutates `status`, which the Constitution's own principle forbids** — `OBSERVED FACT` |
| 7 | **What restores exercisability?** | **Undefined.** No `restore` concept; `approve` acts as an ungoverned single-actor restore | `BUSINESS RULE NOT SPECIFIED` |
| 8 | **What consumes entitlement?** | Casting a vote | `APPROVED BUSINESS RULE` |
| 9 | **What happens after a vote?** | `has_voted=true`, `voted_at`, **and `status='inactive'`** — consumption written into the entitlement's state field | `OBSERVED FACT`; **that entitlement is consumed but NOT terminated is** `ARCHITECTURAL CONSEQUENCE` |
| 10 | **What happens when organisation membership later changes?** | **Nothing happens to the entitlement.** Exercisability is unaffected unless an election-level decision changes it | `APPROVED BUSINESS RULE` |
| 11 | **Election-Only mode?** | Admission without any organisation `Member`; entitlement identical in meaning | `APPROVED BUSINESS RULE` · consistent with `OBSERVED FACT` |
| 12 | **Full Membership mode?** | Organisation membership is an **admission prerequisite** only | `APPROVED BUSINESS RULE`; enforcement `MECHANISM NOT ESTABLISHED` (`FullMembershipPolicy::isEligible()` is a stub returning `true`) |

**Derived invariants** — `ARCHITECTURAL CONSEQUENCE`, offered for ratification, not assumed:

* **INV-1** Entitlement existence is independent of organisation membership after admission.
* **INV-2** Suspension changes exercisability and **never** entitlement existence.
* **INV-3** A consumed entitlement (voted) is not a terminated entitlement.
* **INV-4** Entitlement terminates **only** by a defined revocation/removal act.
* **INV-5** Exercisability is **derived**, never authored directly — it is a function of facts.
* **INV-6** Every change to exercisability has a **named actor** and an **audit record**.

---

## 4 · `Q3` — exercisability: the right question first

**Not** *"where can we store suspension?"* The four questions in order:

**Q3.1 — What is the domain concept representing current exercisability?**
`ARCHITECTURAL CONSEQUENCE`: a **derived capability decision** over an entitlement, computed from (a) entitlement existence, (b) a voter-level governance overlay, (c) election lifecycle, (d) credential validity, (e) prior consumption. **Derived — INV-5.** Today no such concept exists at the voter level; the nearest is `isVoterInElection()`, which is a single `status` comparison.

**Q3.2 — Who may change it?** The Election Chief/committee, under the election's constitution, with four-eyes for withdrawal of the franchise. `APPROVED BUSINESS RULE` in outline; **actor counts for restore/revoke are `BUSINESS RULE NOT SPECIFIED`.**

**Q3.3 — What invariant must always hold?** `INV-1`…`INV-6`. Chiefly: **a suspension decision must be capable of being made, reversed and audited without mutating the entitlement's existence.**

**Q3.4 — What audit/governance evidence must exist?** Actor, timestamp, reason, and reversal — for suspend, restore **and** revoke. **Today: propose/confirm log to `voting_security`; the single-actor `suspend` and `approve` log nothing; `ElectionMembership` emits no domain events at all.** `OBSERVED FACT`.

### 4.1 Candidate models

**Candidate D was discovered in the domain, and it is the strongest — the pattern is already in force one level up.**

**Measured contrast:**

| | **Election-level suspension (in force)** | **Voter-level suspension (today)** |
|---|---|---|
| Recorded as | **`elections.suspended_at` + `suspended_by`** — a timestamped **fact** | `suspension_status` enum **+ mutation of `status`** |
| State | **derived** by `ElectionLifecycleEngineImpl:68-70` as an override **checked first** | none — the mutation *is* the state |
| Reversal | **null the flag; the engine re-derives** (*"no lifecycle state is directly mutated"*) | **no restore path** |
| Denial vocabulary | `CapabilityDenialReason::Suspended` | none |
| Constitutional principle | **honoured** — *"freezes capabilities only… does NOT mutate business facts"* | **violated** |

**And measured: `election_memberships` has NO `suspended_at`, `suspended_by`, `suspension_reason` or capability column of any kind.** So today there is genuinely nothing to freeze — which is *why* `status` is mutated.

| Candidate | Sketch | Assessment |
|---|---|---|
| **A · capability/state on `ElectionMembership`** | add an exercisability field the Chief sets | Simple. ⚠️ **Risks repeating the original error** — an authored capability field beside an authored entitlement field, inviting a second overload. Violates `INV-5` (derived, not authored) unless carefully constrained |
| **B · separate voter-governance record** | a child record of governance decisions | Clean separation; supports history and audit natively. Heavier; needs a derivation step to answer "may they vote now?" |
| **C · explicit suspension decision/record** | an event/decision log; exercisability derived by replay | Strongest auditability; fits `ADR-003`'s *"Trust decides what happened, Governance decides what to do"*. **Heaviest**, and `ElectionMembership` emits no events today |
| **D · ⭐ voter-level governance overlay, mirroring the election-level one** | `suspended_at` / `suspended_by` / reason as **facts** on the entitlement; exercisability **derived** by a policy that checks the overlay first, exactly as the lifecycle engine does; reversal = clear the fact | **Already the in-force, constitutional pattern one level up.** Satisfies `INV-2`/`INV-5`, reversible by construction, no state to restore, and **extends existing language rather than inventing** (**ES-005.4**). Nearest to `B`/`C` in effect, far cheaper, and the precedent is auditable |

**Evaluation against the Product Owner's twelve criteria:**

| Criterion | A | B | C | **D** |
|---|:--:|:--:|:--:|:--:|
| DDD ownership | ⚠️ | ✅ | ✅ | ✅ |
| Election sovereignty | ✅ | ✅ | ✅ | ✅ |
| Chief authority | ✅ | ✅ | ✅ | ✅ |
| Four-eyes governance | ⚠️ | ✅ | ✅ | ✅ |
| Auditability | ❌ | ✅ | ⭐ | ✅ |
| Entitlement durability | ⚠️ | ✅ | ✅ | ⭐ |
| Election-Only | ✅ | ✅ | ✅ | ✅ |
| Full Membership | ✅ | ✅ | ✅ | ✅ |
| Vote consumption kept distinct | ⚠️ | ✅ | ✅ | ✅ |
| Constitutional semantics | ❌ | ⚠️ | ✅ | ⭐ |
| Reversibility / restoration | ⚠️ | ✅ | ✅ | ⭐ |
| Temporal behaviour | ❌ | ✅ | ⭐ | ✅ |

**No candidate is selected here.** `D` is recommended for the ARB's consideration **because the domain already runs it**, not because it is easiest — and note the election-level precedent is itself **incomplete**: `elections.suspension_reason` does **not exist** (measured), so *why* is unrecorded. **A voter-level overlay should not copy that omission.**

---

## 5 · Constitutional alignment **proposal** (Constitution not edited)

| Concept | Finding | Class |
|---|---|---|
| **Election suspension** | Fully defined: 6 rules, `suspend`/`resume`, capability-freeze principle, `suspended_at` derivation | **A — already covered** |
| **Capability freezing** | The governing principle exists (`:126-128`) and is **reusable at voter level** | **A — already covered**, extend it |
| **Election sovereignty** | *"Elections own their voter participation rules; org mutations don't retroactively change them"* — Model B's foundation | **A — already covered** |
| **Chief authority** | Constitutionally real for elections (`open_voting`, `publish_results`, `suspend`) | **B — requires clarification** at voter level |
| **Voter-level governance** | `ElectionMembership` appears **0 times**; no rule takes a person as its object | **C — requires a new constitutional rule** |
| **`admit`** | 0 occurrences; admission is the constitutive act of the approved rule | **C — requires a new constitutional rule** |
| **`restore`** | 0 occurrences; no concept | **C — requires a new constitutional rule** |
| **`revoke` / `remove`** | 0 for entitlement; `ADR-003` revokes **verification** (different object) | **C — requires a new constitutional rule** · **blocking (`P-3`)** |
| **Four-eyes requirements** | Real on `confirm-suspension`; **the outcome is reachable single-handedly** via `suspend`, and `approve` reverses it single-handedly | **D — conflicts with an existing rule**, in effect if not in text |
| **Suspension duration / review** | nothing anywhere | **E — not yet specified** |

### 🔴 The distinction that must never be collapsed

> **Election suspension** — object: the election · effect: freezes the whole process · actors: chief, platform_admin · state: `Suspended`, derived from `suspended_at`.
> **Voter suspension** — object: one entitlement · effect: one voter cannot exercise · actors: committee, four-eyes · state: **no constitutional existence.**

**They share a verb and share nothing else.** The proposal is that the Constitution **name them differently**, and that this naming go into the **Phase 4 governance-language review that `VoterSourceStrategy` already designates** (its case names are `@deprecated`, *"transitional bridge vocabulary"*) — **one governance-language decision, not two** (**ES-005.4**).

---

## 6 · Full Membership implications

**Nothing here is inferred from Election-Only evidence.**

* **MODEL CONSISTENT BUT RUNTIME NOT YET VERIFIED.**
* `members` has **0 rows platform-wide** → **no Full Membership admission has ever occurred**, and no retention scenario has ever been exercised. `OBSERVED FACT`.
* **`D-ENT-1` is a Full Membership question, and Full Membership is the mode with zero runtime evidence.** This remains the package's central epistemic limit.
* The declared admission rule (active `Member` + fees paid/exempt + not deleted) sits in `FullMembershipPolicy::isEligible()`, **a stub returning `true`**. Whether admission would enforce its own rule is `MECHANISM NOT ESTABLISHED`.
* **Under the approved rule, Full Membership acquires a new obligation**: because organisation membership is checked **only** at admission, **the admission check becomes the single point of enforcement** — and it is currently a stub. `ARCHITECTURAL CONSEQUENCE`.

## 7 · Election-Only implications

* **MODEL CONSISTENT AND PARTIALLY RUNTIME-VERIFIED** (admission and entitlement observed; suspension observed; **restore and revoke never exercised because they do not exist**).
* Model B is coherent here and `Model A` is not — `scopeEligible()` demands a `Member` this mode never creates, and returns **0 of 20** rows. `OBSERVED FACT`.
* **`D-ENT-1` is largely moot for Election-Only**: with no organisation `Member`, there is no organisation membership to lose. **Election-Only therefore cannot validate the approved rule's core claim.** `INTERPRETATION`.
* Election-Only voters are given `user_organisation_roles.role='member'` — a tenant linkage the FK demands, **not** the business `Member`. **The vocabulary collision is live and should be resolved in the same Phase 4 review.**

---

## 8 · Open business rules

| # | Rule | State |
|---|---|---|
| **BR-1** | **What terminates an entitlement (revocation/removal)?** | **NOT SPECIFIED — BLOCKING.** The approved wording depends on it |
| **BR-2** | Is voter suspension temporary or indefinite; is there a review obligation? | NOT SPECIFIED |
| **BR-3** | Does restoring require the same authority and actor count as suspending? | NOT SPECIFIED |
| **BR-4** | Is revocation terminal and irreversible (as `TERMINATED` is organisation-side)? | NOT SPECIFIED |
| **BR-5** | Must a mid-election loss of standing (fees lapse) have **any** automatic effect? | NOT SPECIFIED |
| **BR-6** | Must the entry surface refuse a suspended voter, and may a credential be issued to one? *(Today: no, and yes.)* | NOT SPECIFIED |
| **BR-7** | May a voter who has already voted be suspended, and to what effect? *(Today `proposeSuspension` refuses.)* | NOT SPECIFIED |
| **BR-8** | Is `invited` a real business state? *(No producer exists; it is displayed.)* | NOT SPECIFIED |

## 9 · Open architecture decisions

| # | Decision | Owner |
|---|---|---|
| **`Q3`/`D-ENT-1b`** | Exercisability model — A/B/C/**D** | **ARB** |
| **AD-2** | Which component is the authoritative eligibility engine — `VotingEligibilityPolicy` (declared, **0 callers**) or the live predicate? | **ARB** |
| **AD-3** | Does `ElectionMembership` extend the Membership context's lineage model or own a separate lifecycle? | **ARB** |
| **AD-4** | Do entitlement/exercisability changes emit domain events? | **ARB** |
| **AD-5** | Disposition of the two dormant definitions (`scopeEligible`, `isEligible`) and the legacy `isEligibleToVote()` | **ARB → engineering** |
| **AD-6** | Where does admission-time enforcement live, now that it is the single point of Full Membership enforcement? | **ARB** |

## 10 · Implementation prerequisites

**None of the following is authorised; all must be satisfied before implementation may be discussed.**

| # | Prerequisite |
|---|---|
| **P-1** | **`ADR-002` amendment approved and applied** by an authorised act |
| **P-2** | **`Q3` decided** — exercisability has a named model and owner |
| **P-3** | 🔴 **`BR-1` decided** — a defined revocation/removal rule. **Without it the approved rule is incomplete and drifts to "unconditional right".** |
| **P-4** | **Constitutional amendments approved** for the `C` items in §5, with **distinct names** for election vs voter suspension |
| **P-5** | **`AD-2` decided** — one authoritative eligibility engine, so a third predicate is not added to four |
| **P-6** | **Enforcement replacement designed before `status` is vacated** — `status='inactive'` is currently the **only** thing preventing a suspended voter from voting |
| **P-7** | **The §5 four-eyes conflict resolved** — while `suspend` and `approve` remain single-actor, any new control is bypassable |
| **P-8** | **`scopeEligible()` must not be activated** while `members` is empty — it returns 0/20 and would deny every voter |

## 11 · Tests that will eventually be required

**Written as business behaviours. None is created now.**

1. **Retention** — admit under Full Membership → organisation membership lapses → **entitlement remains and is exercisable.** *(The decisive Model B test. Cannot be written until Full Membership is reachable.)*
2. **Suspension blocks** — admit → suspend → **refused at the enforcement boundary**, and refused at the **entry surface**.
3. **Suspension preserves entitlement** — after suspension the entitlement still **exists**; only exercisability changed.
4. **Restore** — suspend → restore → exercisable again, with the authority `BR-3` defines.
5. **Revocation** — revoke → entitlement terminated, per `BR-1`/`BR-4`, and **past votes unaffected** (`ADR-003`).
6. **Consumption ≠ suspension** — a voter who has voted is distinguishable from a suspended voter at every gate.
7. **Four-eyes cannot be bypassed** — no single actor can achieve suspension or restoration.
8. **Admission enforcement per mode** — Full Membership admission refuses a non-member/unpaid member; Election-Only admits without a `Member`.
9. **Audit completeness** — every exercisability change yields actor, timestamp, reason.
10. **Tenant isolation** — an eligibility answer computed in one organisation context is never served for another (`PBDIGIT-69`).
11. **Cold/warm parity** — a cache may make an answer faster, never different.

## 12 · Explicit non-goals

* ❌ **`ADR-002` is not edited** — §2 is a proposal awaiting authorisation.
* ❌ **The Constitution is not edited** — §5 is a proposal.
* ❌ **`Q3` is not decided**, and was deliberately **not** solved while drafting the amendment.
* ❌ **No implementation**: no production code, no `ElectionMembership` change, no `status` change, no migration, no schema change, no test created or weakened, no repository/entity/aggregate introduced, no suspension or restore implemented, no credential change, **no dormant code activated**.
* ❌ **Not renaming anything** — naming goes to the Phase 4 review.
* ❌ **Not fixing the §5 four-eyes conflict** — `P-7`, a separate authorised slice.
* ❌ **Not asserting the current implementation implements Model B** — it does not.
* ❌ **Not claiming Model B is proven** — it is **approved**.
* ❌ **Not repairing the two unparseable files** (below) — recorded, not actioned.

---

## Repository-hygiene observation (outside this commission)

Two files under `app/` **cannot be parsed by PHP**:

| File | Error | Live? |
|---|---|---|
| `app/Domain/Election/Models/ElectionUser.php` | duplicate `<?php` at line 11 | **no production callers** |
| `app/Http/Middleware/VoterSlugStep.php` | stray backtick, line 279 | **not registered in any middleware stack** |

**No runtime impact** — both are unreachable. But **any full-autoload or static-analysis pass over `app/` fails on them**, and `ElectionUser` is a legacy voter model carrying `is_voter`, `suspended_at` and `suspension_reason` that could easily be **mistaken for a precedent** for `Q3`. **It is not one: it is dead and unparseable.** **Recommend a separate backlog item; deliberately not fixed here.**

---

## Boundaries

* **`PBDIGIT-69`** (tenant-unaware eligibility cache) is a **technical/application defect** and is cited in no governance verdict above; it appears only as a future test (§11.10) and in this sentence.
* **Session 1 is not authority here and nothing of Session 1's is modified** — Master Matrix, denominator, `SD-1`, `SD-2`, Slice 1, `PBDIGIT-48` plan. **Conversely this package is not Session 1's specification:** it records an approved business model plus **open** architecture decisions.
* **Runtime evidence consumed, not re-generated.** No experiment repeated; no data mutated.

**Report:** **`D-ENT-1` — APPROVED · `ADR-002` AMENDMENT — PROPOSED · `Q3` — OPEN · CONSTITUTIONAL ALIGNMENT — PROPOSED · IMPLEMENTATION — NOT AUTHORISED**

**Traceability:** `docs/adr/ADR-002-verified-eligible-authorized.md:71,78,161,198-204,208-227` · `docs/adr/ADR-003-governance-driven-revocation.md:62-71` · `app/Domain/Election/Constitution/ElectionConstitution.php:126-146` · `app/Application/Election/Services/ElectionLifecycleEngineImpl.php:22,68-70,273-326` · `app/Application/Election/Capabilities/CapabilityDenialReason.php:7,18` · `app/Http/Controllers/Election/ElectionManagementController.php:959,969,981-982,1006,1016` · `app/Models/ElectionMembership.php:126-146,157-171,173-197,202-224` · `app/Models/User.php:315-328,401-405` · `app/Http/Controllers/ElectionVoterController.php:196-216,222-241,290-356` · `app/Contexts/Elections/Domain/Policies/FullMembershipPolicy.php` · `app/Contexts/Membership/Domain/Voting/VotingEligibilityPolicy.php` · `app/Domain/Election/Enum/VoterSourceStrategy.php:10-60` · measured read-only 2026-08-12: `elections.suspended_at`/`suspended_by` **present**, `elections.suspension_reason` **absent**, 0 of 10 suspended · `election_memberships` has **no** `suspended_at`/`suspended_by`/`suspension_reason`/capability column · `members` **0** rows · `scopeEligible()` **0/20** · 2 unparseable files under `app/`.

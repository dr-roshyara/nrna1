# PBDIGIT-68 — `ElectionMembership` is a durable election entitlement (ruling + consequences)

**Type:** Business decision **RECORDED** + consequence analysis · **Epic:** `PBDIGIT-EPIC-03` Election Management
**Created:** 2026-08-12 · **Ruling by:** Product Owner, 2026-08-12
**Evidence:** [`../reviews/2026-08-09-iervp-election-runtime-verification.md`](../reviews/2026-08-09-iervp-election-runtime-verification.md) — Appendices **D** (two-mode entitlement) and **E** (semantics verdict) · [`../reviews/2026-08-09-election-constitution-voter-eligibility-traceability.md`](../reviews/2026-08-09-election-constitution-voter-eligibility-traceability.md)
**Status:** ✅ **DECISION RECORDED** · ⬜ **consequences NOT IMPLEMENTED** · **the Constitution is NOT amended by this ticket**

---

## The ruling — Model B, approved

> **`ElectionMembership` is an election-specific, durable entitlement created when a person is admitted to an election.**
>
> **In Full Membership mode**, organisation membership is an **admission prerequisite** — *not* a continuously evaluated prerequisite for retaining the entitlement.
> **In Election-Only mode**, no organisation `Member` is required.
>
> **The Election Chief possesses explicit authority to suspend or revoke an `ElectionMembership`** according to the Election Constitution. **Such suspension is an election-level decision and must not be conflated with changes to organisation membership.**

**This closes Appendix E's `NOT SPECIFIED` verdict.** The programme asked which of two franchise rules applied; **the Product Owner has answered.** Engineering's remaining job is consequences and gaps — **not to invent or revise the decision.**

### What the ruling settles

| Concept | Meaning | Authority |
|---|---|---|
| `Member` | belongs to the organisation/tenant | **Organisation** |
| Admission eligibility | *may* this person become a voter? | **`VoterSourceStrategy`** (mode-dependent) |
| **`ElectionMembership`** | **has been admitted to *this* election** | **Election** |
| **Suspension / revocation** | participation withdrawn | **Election Chief** |
| Voting access | may they vote **now**? | lifecycle + credential |
| Vote | the act | Voting domain |

**Architecturally attractive consequence, and worth stating because it simplifies the model:**

> **`ElectionMembership` always means the same thing in both modes.** `VoterSourceStrategy` governs **how someone qualifies to enter** the entitlement — **never what it means afterwards.** Two modes, one entitlement concept.

**And the ruling confirms the separation the runtime already showed:** `ElectionMembership = EXISTS` while `can_vote_now = false` is **a valid state**, not a bug. Entitlement and voting access are different decisions.

---

## Consequence analysis — what the ruling requires vs what exists

The ruling depends on four voter-level concepts: **admit · suspend · revoke · restore**.

### Measured in `ElectionConstitution.php`

| Concept | Hits | Reality |
|---|---:|---|
| `admit` | **0** | absent |
| `suspend` | **6** | ⚠️ **all six are ELECTION-level** — *"Suspend election for governance hold (fraud/legal/operational)"*, `target_state: suspended`, plus `resume`. **None concerns a voter** |
| `revoke` | **0** | absent |
| `restore` | **0** | absent |

> 🔴 **A vocabulary collision, and the second of its kind.** `suspend` constitutionally means *hold the whole election*; in the application it **also** means *suspend one voter*. **Same verb, two subjects, one of them unconstitutional.**
> *(The first collision: `capacity_eligibility` — a billing concept — versus voter eligibility. Recorded in the constitutional review, Finding 7.6.)*

### Measured in the application — the machinery already exists, and is better than expected

`ElectionMembership` carries a **governance-grade** flow:

```
suspension_status:  none  ->  proposed  ->  confirmed
                      ^                        |
                      +---- cancelSuspensionProposal
proposeSuspension(User $proposer)   confirmSuspension(User $confirmer)
```

Plus routes: `propose-suspension` · `confirm-suspension` · `cancel-proposal` · `suspend` · `approve` · `DELETE`.

**Two distinct named actors imply a four-eyes control** — exactly the shape a franchise-withdrawal power should have.

---

## ✅ `D-ENT-1` APPROVED — Model B (Product Owner, 2026-08-12)

**Consequences package: [`../reviews/2026-08-12-d-ent-1-approval-and-consequences-package.md`](../reviews/2026-08-12-d-ent-1-approval-and-consequences-package.md)**

| Item | Status |
|---|---|
| **`D-ENT-1`** | ✅ **APPROVED — Model B** |
| **`D-ENT-1a`** ADR-002 | ✅ **approved to amend explicitly** · amendment **PROPOSED, `ADR-002` NOT edited** |
| **`D-ENT-1b`** / **`Q3`** | 🔓 **OPEN** |
| Constitutional alignment | **PROPOSED**, Constitution not edited |
| **Implementation** | ⛔ **NOT AUTHORISED** |

**The approved rule, in the Product Owner's corrected wording** — note it is *not* "durable" unqualified:

> **`ElectionMembership` is the election-specific entitlement record. Admission creates an election-specific entitlement. That entitlement remains associated with the election unless a defined election-level revocation/removal rule terminates it. Whether that entitlement is currently *exercisable* is governed by the election's voting rules and voter-level suspension/governance state.**

> ⚠️ **The correction has teeth: the approved rule REQUIRES a defined revocation/removal rule to be complete.** Without one there is no termination condition and the entitlement becomes unconditional by omission. **`BR-1`/revocation is therefore a BLOCKING PREREQUISITE (`P-3`), not an open question.**

**`Q3` gained a domain-derived candidate (D).** Measured: election-level suspension is recorded as **`elections.suspended_at` + `suspended_by`** — a timestamped **fact** — with state **derived** by `ElectionLifecycleEngineImpl:68-70` and reversal by clearing the flag, so *"no lifecycle state is directly mutated."* **The voter level has no such column at all** (`election_memberships` has no `suspended_at`, `suspended_by`, `suspension_reason` or capability field). **So the constitutional principle is honoured one level up and violated one level down — because there is nothing to freeze.** Mirroring the in-force overlay is candidate **D**, recommended for ARB consideration **because the domain already runs it**, not because it is easiest.

**Status discipline:** **Model B is the APPROVED BUSINESS MODEL, not "proven"** — Full Membership has zero runtime evidence (`members` = 0 rows). **And the current implementation is not asserted to implement it.**

---

## ⚖️ ARB DECISION PACKAGE — `D-ENT-1`, awaiting decision

**[`../reviews/2026-08-12-arb-decision-package-election-entitlement-retention.md`](../reviews/2026-08-12-arb-decision-package-election-entitlement-retention.md)** · **Status: MODEL B DOMAIN ANALYSIS COMPLETE — AWAITING PRODUCT OWNER / ARB DECISION. No implementation may begin until `D-ENT-1` is decided.**

**Model B is recorded there as the Product Owner's PREFERRED CANDIDATE — not as an implemented or constitutionally ratified rule.** Its refined wording, adopted verbatim: *"`ElectionMembership` is the election-specific entitlement record. Its existence and identity are election-specific. Whether that entitlement is currently **exercisable** is governed by the election's voting rules and suspension state."*

**Three findings there change what this ticket's gaps mean:**

1. 🔑 **Four eligibility definitions exist for this one concept.** `scopeEligible()` implements **Model A** (requires an active `members` row); `isEligible()` implements **Model B**; both have **0 callers**. The live gate is a third, weaker predicate; a fourth reads columns that exist in no database. **The ARB is not choosing in a vacuum — both models are already written, and the live path implements neither faithfully.** Measured: `scopeEligible()` returns **0 of 20** rows, because `members` is empty.
2. 🔑 **`status` is an accidental mixture of three axes** — entitlement existence, current capability, and vote consumption — plus `invited`, which **has no producer** yet is filtered and counted in the UI.
3. 🔑 **Why the code mutates the entitlement:** `can_vote_now` lives on `VoterSlug`, a 30-minute credential the voter re-creates on demand. **There is no durable capability field on the entitlement, so a Chief's decision has nowhere to live but `status`.** That is why `ElectionConstitution:126-128` ("freezes capabilities only… does NOT mutate business facts") is violated — **the structure offers no capability to freeze.** Recorded as **`Q3`, the true blocker: an architecture decision, not a refactor.**

**`D-ENT-1` is a Full Membership question, and Full Membership is the one mode with zero runtime evidence — `members` has 0 rows, so no admission or retention scenario has ever occurred. Election-Only evidence CANNOT validate Model B's core claim.**

---

## 📘 Model B domain analysis — COMPLETE, awaiting ARB

**[`../reviews/2026-08-12-model-b-election-membership-domain-analysis.md`](../reviews/2026-08-12-model-b-election-membership-domain-analysis.md)** — the governance/domain-modelling analysis the Product Owner commissioned before any implementation. **Status: MODEL B DOMAIN ANALYSIS COMPLETE — AWAITING PRODUCT OWNER / ARB REVIEW.**

**It changes the question this ticket asks.** The vocabulary gaps recorded below (`admit`/`revoke`/`restore` absent) are real for the *Election* Constitution — **but the concepts already exist, fully modelled, in the Membership context** (`MembershipLineage`: `ACTIVE → SUSPENDED → ACTIVE`, `→ TERMINATED` terminal, guarded transitions; `Member::suspend()`/`reactivate()`), together with `VotingEligibilityPolicy`, which declares itself *"the authoritative eligibility engine — all Elections voting decisions flow through this policy"* and has **zero production callers.**

> **So the pivotal decision is not what to name, but whether the election entitlement EXTENDS that existing lifecycle or is a separate one.** Per **ES-005.4** (consume or extend, never create a second) the analysis recommends the ARB answer that **first** — every other gap is downstream of it.

**Also surfaced there (not in this ticket, and not defects I may declare):** suspension takes two people while `approve` undoes it single-handedly and leaves `suspension_status='confirmed'`; a parallel single-actor `suspend` route reaches the same outcome as the governed flow; `ADR-002` (Accepted) and Model B **disagree** on whether organisation membership is re-evaluated at every action — recorded as **D — AMBIGUOUS**, deliberately unresolved.

---

## ⚠️ G-1 HAS BEEN MEASURED — the section below is superseded

**Runtime verification, 2026-08-12, authorised by the Product Owner: [`../reviews/2026-08-12-g1-voter-suspension-enforcement.md`](../reviews/2026-08-12-g1-voter-suspension-enforcement.md).**

> **G-1 was half right, and the wrong half was the headline. A confirmed-suspended voter IS refused the ballot — HTTP 403 at every real slug route — but not by the gate this ticket examined, and not because anything reads `suspension_status`.**

| This ticket claimed | Measured |
|---|---|
| A suspended voter *"appears eligible and passes the server-side entry check"* | ✅ **CONFIRMED** — and is additionally **issued a fresh `VoterSlug`** |
| *"Suspension may not block voting"* | ❌ **DISPROVEN** — `EnsureElectionVoter` → `User::isVoterInElection()` (`status === 'active'`) refuses them |

**The voting path has two predicates of opposite polarity; this ticket found only one:**

```
ElectionVotingController   status !== 'removed'   ALLOW unless removed   -> suspended PASSES
User::isVoterInElection()  status === 'active'    DENY unless active     -> suspended FAILS
```

**What survives of G-1, narrower and still real:** the **entry surface does not enforce suspension** — it reports `isEligible: true` to a suspended voter and issues a voting credential. **`OBSERVED AT RUNTIME`.**

🔴 **And the consequence that now dominates: enforcement is INCIDENTAL.** No code expresses *"a suspended voter must not vote"*; it works only because suspension writes `status='inactive'` and one predicate demands `'active'`. **So `G-5` is load-bearing: "clean up the `status` overload" would, if `status` were left `'active'`, silently delete the only enforcement of the Chief's authority.** See the review before touching either.

**Also corrected by measurement:** **`G-6` is WITHDRAWN** — four-eyes **is** enforced at `ElectionVoterController:342` via `canConfirmSuspension()`; measured proposer-refused / second-actor-allowed. My original claim was true of the domain method and false of the system, because I read the method and not its caller.

**Also raised:** **`PBDIGIT-69`** — the voter predicate's cache key is tenant-unaware, poisoning in both directions for 300s. Found while restoring state; it is not G-1.

---

## 🔴 The finding that most affects the ruling — AS ORIGINALLY WRITTEN (superseded above, retained for the record)

**The Election Chief's suspension authority — the safeguard this ruling explicitly relies on — may not prevent voting.**

| Fact | Evidence |
|---|---|
| `confirmSuspension()` sets **`status = 'inactive'`**, `suspension_status = 'confirmed'` | `ElectionMembership.php:211-217` |
| The eligibility predicate rejects **only** `status === 'removed'` | `ElectionVotingController.php:41-44` |
| The **enforcement boundary** uses the same predicate | `ElectionVotingController.php:112` |
| The voting path references `suspension_status` **zero times** | measured across the controller and `ElectionClockService` |

> **A confirmed-suspended voter has `status='inactive'`, which is not `'removed'` — so they appear eligible and pass the server-side entry check.**

**Calibration, stated plainly:** `OBSERVED IN CODE`. **NOT runtime-verified** — all 20 existing memberships are `none / active`, so no suspended voter exists to measure against, and creating one is a data change outside this commission.

**Two aggravating details:**

* **`'inactive'` is overloaded.** `markAsVoted()` also sets `status = 'inactive'` (`:164-171`). **The same value means "has voted" and "is suspended"** — so the predicate cannot distinguish them even if it wanted to.
* **The four-eyes control is implied, not enforced.** `confirmSuspension(User $confirmer)` **does not check that the confirmer differs from the proposer.** The dual-actor signature suggests separation of duties; nothing enforces it.

---

## Gaps the ruling now makes visible

| # | Gap | Class |
|---|---|---|
| **G-1** | ✅ **MEASURED 2026-08-12.** The ballot **is** protected (403). **Narrowed to:** the entry surface reports a suspended voter as eligible **and issues them a credential**, and **enforcement is incidental** — nothing reads `suspension_status` | **CONFIRMED (entry surface)** · original claim **DISPROVEN (ballot)** |
| **G-2** | **`admit` has no name.** The ruling makes admission a distinct authoritative act; the Constitution has no such action, and import/assign is application-level only | **Constitutional gap** |
| **G-3** | **`revoke` / `restore` have no names**, though `DELETE {membership}` and `approve` exist in the application | **Constitutional gap** |
| **G-4** | **`suspend` is ambiguous across layers** — election-level constitutionally, voter-level in the application | **Vocabulary collision** |
| **G-5** | 🔴 **`status` is overloaded** (`inactive` = voted *or* suspended) — **and measurement showed the overload is LOAD-BEARING: it is the only thing enforcing suspension.** Do not "clean it up" without replacing the enforcement first | **ESCALATED** — model defect **with a live dependency** |
| **G-6** | ~~Four-eyes not enforced on suspension confirmation~~ | ❌ **WITHDRAWN** — enforced at `ElectionVoterController:342`; measured. Residual: compares `->name` not `->id`, and the domain method itself is unguarded |
| **G-7** | **Full Membership mode remains unreachable** (`IERVP-2`, `IERVP-3`), so the ruling's admission-prerequisite half **cannot be exercised** | **Reachability gap** |

---

## Acceptance criteria — business behaviour

> ⚠️ **These are CONSEQUENCES OF THE RULING proposed as acceptance criteria. They are NOT established constitutional rules.** The ruling grants the Election Chief suspension/revocation authority; **it does not yet specify the enforcement semantics.** The criteria below are engineering's reading of what that authority implies — **a reading the Product Owner/ARB must confirm before it binds anything.** *(Labelled at the Product Owner's instruction, 2026-08-12, to stop an engineering interpretation quietly becoming constitutional authority.)*

* [ ] **A voter admitted to an election stays entitled when their organisation membership later lapses** — the ruling's core statement, demonstrated.
* [ ] **A voter the Election Chief suspends cannot reach or submit a ballot** — enforced **server-side**, not only hidden in the UI. *(Measurement 2026-08-12: the ballot is already protected. What is unmet is that the entry surface still calls them eligible and issues a credential — and that the protection is incidental rather than expressed.)*
* [ ] **Suspension is distinguishable from having voted** — one stored value cannot mean both.
* [ ] **The vocabulary is unambiguous**: whatever names the four voter-level acts, *suspending an election* and *suspending a voter* are not the same word at the same layer.
* [ ] **Both modes are exercisable**, so the admission-prerequisite rule can actually be tested (`G-7`).
* [ ] **A regression test covers admit → organisation membership lapses → voter is still entitled**, and **admit → suspend → voter is refused at the enforcement boundary.** *(Neither case exists today — Appendix E found three assignment-gate tests and none on retention.)*

## Explicit non-goals

* ❌ **Not amending the Constitution.** The Product Owner directed that the ruling be translated into domain vocabulary and the gaps identified **first**. `G-2`, `G-3`, `G-4` name what would need defining — **they do not define it.**
* ❌ **Not implementing suspension enforcement** — `G-1` needs runtime confirmation before it is called a defect.
* ❌ **Not changing the `status` enum** or the four-eyes flow.
* ❌ **Not running the `voter_source_strategy` backfill** (three demo elections carry `NULL`; a business-data mutation needing its own authorisation).
* ❌ **Not choosing the names** for admit/revoke/restore — naming a constitutional action is a governance act.

## Related

* **Appendix E** — the `NOT SPECIFIED` verdict this ruling closes, with the five silent sources.
* **Appendix D** — the two-mode entitlement model, confirmed at data level for Election-Only.
* **`IERVP-2` / `IERVP-3`** — the two blockers making Full Membership unreachable. **The ruling raises their priority: half of it cannot be tested until they are resolved.**
* **`PBDIGIT-65`** — tenant context hiding a valid membership; **independent of entitlement semantics**, and the actual cause of the reported "not eligible".
* **`PBDIGIT-66`** — establish Election-Only constitutionally. **This ruling supplies the entitlement half of what that ticket needs.**
* **Constitutional review, Finding 3** — `ElectionMembership` appears **0 times** in the Constitution, which is why this ruling had nowhere to live.

---

**Traceability:** `app/Domain/Election/Constitution/ElectionConstitution.php:129-145` (election-level `suspend`/`resume`) · `app/Models/ElectionMembership.php:49,61,70,164-171,202-224` · `app/Http/Controllers/ElectionVotingController.php:41-44,112` · voter-suspension routes under `organisations/{organisation}/elections/{election}/voters/{membership}` · measured 2026-08-12: constitution hits `admit 0 · suspend 6 (all election-level) · revoke 0 · restore 0`; all 20 `election_memberships` rows `suspension_status=none, status=active`.

# PBDIGIT-68 — `ElectionMembership` is a durable election entitlement (ruling + consequences)

**Type:** Business decision **RECORDED** + consequence analysis · **Epic:** `PBDIGIT-EPIC-03` Election Management
**Created:** 2026-08-12 · **Ruling by:** Product Owner, 2026-08-12
**Evidence:** [`../reviews/2026-08-09-iervp-election-runtime-verification.md`](../reviews/2026-08-09-iervp-election-runtime-verification.md) — Appendices **D** (two-mode entitlement) and **E** (semantics verdict) · [`../reviews/2026-08-09-election-constitution-voter-eligibility-traceability.md`](../reviews/2026-08-09-election-constitution-voter-eligibility-traceability.md)
**Status:** ⚠️ **`D-ENT-1` — `Q-A0` OPEN: two PO formulations disagree on whether organisation membership is continuously required** · ✅ **Model B approved in principle** · **`BR-1` PARTIALLY RESOLVED — Option B recommended; 11 PO decisions await** · **`ADR-002` amendment PROPOSED (not applied)** · **`Q3`/`D-ENT-1b` OPEN** · ⛔ **IMPLEMENTATION NOT AUTHORISED** · **Constitution NOT amended**

> ### ⚠️ Read the approved wording first — the ruling below was CORRECTED
>
> **The wording in "The ruling" section immediately below is the ORIGINAL, and the Product Owner has since narrowed it.** It is retained as the record of what was first recorded. **The binding wording is:**
>
> > **`ElectionMembership` is the election-specific entitlement record. Admission creates an election-specific entitlement. That entitlement remains associated with the election unless a defined election-level revocation/removal rule terminates it. Whether that entitlement is currently *exercisable* is governed by the election's voting rules and voter-level suspension/governance state.**
>
> **Why the correction matters:** *"durable entitlement"* unqualified would drift into `import voter → permanent unconditional right to vote`, which is **not** what was decided. See [`the D-ENT-1 approval package`](../reviews/2026-08-12-d-ent-1-approval-and-consequences-package.md) — and note the consequence that **a defined revocation/removal rule is now a BLOCKING PREREQUISITE**, not an open question.

---

## The ruling — Model B, as ORIGINALLY recorded (superseded in wording by the block above)

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

## 🔴 `Q-A0` — TWO PRODUCT OWNER FORMULATIONS DIFFER ON CONTINUITY

**[`../reviews/2026-08-12-d-ent-1-two-mode-domain-decision-report.md`](../reviews/2026-08-12-d-ent-1-two-mode-domain-decision-report.md)**
**Report: `D-ENT-1` INVESTIGATION COMPLETE — `ADR-002` AMENDMENT PROPOSED — IMPLEMENTATION NOT AUTHORISED.**

**The two questions are now kept strictly apart, per the Product Owner's correction:**

* **Question A** — must a person remain an organisation `Member` **throughout** the election? *(Full Membership only)*
* **Question B** — may the Election Chief suspend the election-specific entitlement? *(both modes)*

**They are independent:** no suspension or removal path in the system branches on organisation membership or on `voter_source_strategy`. **A "yes" to B says nothing about A.**

> 🔴 **But two Product Owner formulations now disagree on Question A, and I have not chosen between them:**
>
> * **`F1`** (approved 2026-08-12, recorded above as binding): organisation membership is an **admission prerequisite — NOT continuously evaluated.** → **Question A ANSWERED.**
> * **`F2`** (this commission): *"organisation membership **remains relevant**"* and *"whether it is **continuously required remains to be established**."* → **Question A DECLARED OPEN.**
>
> **This blocks the `ADR-002` amendment.** The amendment's whole substance is an **Entitled** axis whose defining property is that it is *not* re-derived from organisation membership — **correct under `F1`, premature under `F2`.** **`Q-A0` is the decision everything downstream waits on.**

**Two new measured results on suspension:**

* 🔴 **Suspension does NOT prevent credential issuance** — a confirmed-suspended voter was **issued a fresh `VoterSlug`** at runtime; and **no suspension or removal path touches `VoterSlug` or `Code` at all**, so an existing credential is never invalidated. **The system issues an instrument it will not honour.**
* **Of the five required distinguishability cases, two fail.** The consequential one: **suspended vs already-voted is indistinguishable at the enforcing predicate** — both are `status='inactive'`, and `isVoterInElection()` reads only `role` + `status`.

**Also established:** the two mode formulae are **identical in every component except one** — *"organisation `Member` required"*. **So the modes differ only at admission**, which is the strongest available argument that `ElectionMembership` means the same thing in both. And *"credential requirements"* fails a business test as an entitlement component: **the credential is voter-refreshable**, so it is an authentication step, not an entitlement condition.

**`PBDIGIT-49`: concept-level mapping offered as evidence only — no row classified, Session 1's artifacts untouched.** The discriminator offered: **a test is affected only if it asserts a *voting-time* consequence of *organisation* status**; an admission-time assertion is unaffected under either formulation.

---

## 🔍 GOVERNANCE WEIGHT ASSESSMENT — the Officer Guide: **D, mixed authority**

**[`../reviews/2026-08-12-officer-guide-governance-weight-assessment.md`](../reviews/2026-08-12-officer-guide-governance-weight-assessment.md)** · **Recommendation: `D` — MIXED AUTHORITY; SECTION-LEVEL REVIEW REQUIRED.** **Nothing promoted; the guide was not edited.**

**By the repository's own executable test of governedness it is NOT a governed document:** no knowledge card (*"every governed doc needs a knowledge card"*), **not a registered documentation root**, and **cited as authoritative by nothing** — not the Constitution, not an ADR, not a test, not a code comment. Provenance: **one commit, 2026-03-22, bundled into a feature commit as "docs"**, never revised, no steward, no change control.

🔑 **The provenance chain decides its character.** An architecture document (`architecture_legacy/.../Voterlist.md`) **specified dedicated `approved_at`/`approved_by`/`suspended_at`/`suspended_by` columns and voter-suspension audit logging.** The developer guide records that its *"errors were corrected before implementation"* — the decision being *"None exist → **used existing `status` column**."* **So:**

1. **The guide is DESCRIPTIVE of an implementation that deliberately deviated from its own architecture document.**
2. 🔑 **The `status` overload was a deliberate, recorded decision — not drift.** Rationale: *"None exist"* — **availability, not modelling. `Q3` candidate D is the ORIGINAL design, rejected on cost grounds.**
3. 🔑 **The audit gap is a deviation from documented design intent**, not an omission — the audit logging was specified and dropped.

*(And `Voterlist.md` is not an authored spec either: it opens "✅ You're Absolutely Right!" — an assistant-conversation transcript, in a folder an ADR declares legacy.)*

**Impact on `BR-1`: the guide speaks to 6 of 13 questions and authoritatively settles 0.** It would specify `BR-1.2` and `BR-1.8` *as statements awaiting ratification*. **Recognition alone would settle nothing** — every business rule in it still needs constitutional/ADR ratification.

**Two contradictions must be resolved before any recognition:** 🔴 **`invited`** is documented as a real state with **no implementation**; 🔴 **"permanent" removal is defeated** by `approve()` having no guard against a `removed` row. Plus the guide twice describes suspension as an *undo/ineligibility device*, which conflicts with the approved separation of entitlement from eligibility.

**Proposed (not performed):** recognise **only** the business statements as the *operational expression* of rules ratified elsewhere; recognise **none** of the UI content — otherwise badge colours, button labels and a CSV filename become business rules. **The guide should derive authority, never hold it.**

---

## 🏛️ GOVERNANCE DECISION PACKAGE — `BR-1` partially resolved, 11 decisions await

**[`../reviews/2026-08-12-election-membership-governance-decision-package.md`](../reviews/2026-08-12-election-membership-governance-decision-package.md)**
**Report: `D-ENT-1` APPROVED · `BR-1` PARTIALLY RESOLVED · `ADR-002` UNCHANGED · CONSTITUTION UNCHANGED · IMPLEMENTATION NOT AUTHORISED.**

**`BR-1.1`/`BR-1.2` — recommendation, for ratification or rejection:**

> **Termination withdraws the exercisability of an `ElectionMembership` permanently by officer authority. The entitlement remains historically associated with the election, together with the actor, reason and time of termination. Reversal is not available to election officers and requires a separately authorised administrative act.**

**Option B, and every layer already behaves this way:** removal **retains** the row and writes actor/reason/timestamp (`destroy()` → `remove()`, **no delete**, though `SoftDeletes` is available and deliberately unused); `destroy()` takes a **row lock explicitly to avoid racing a live vote**; the officer guide says *permanent* **and** *"contact your system administrator if a removal was made in error"*; the organisation side retains terminated episodes. **The strongest argument is integrity: under Option A, erasing an entitlement retroactively changes the denominator of a possibly-published result**, and it would break replay-divergence detection, which needs the record to persist.

**Two findings that are governance questions, not defects I may declare:**

* 🔴 **The gradient of authority does not follow the gradient of consequence.** All six voter-governance operations share **one** check (`manageVoters` = active chief|deputy). **Permanent removal needs the same authority as suspension and FEWER actors than the governed suspension path** (1 vs 2).
* 🔴 **No voter-governance act reaches the election's own audit trail** — and `ElectionAuditService` exists with `'voters'` as its **default category**, unused. Four of six acts log nothing at all; removal's log is **conditional on a legacy column** `PBDIGIT-58` is migrating away from. `ElectionMembership` emits no domain events.

**Also:** *"permanent" is not enforced* — `approve` has no guard against a `removed` row, so permanence rests on a hidden button (`MECHANISM NOT ESTABLISHED`; read, not executed). And the Product Owner's draft suspension rule says *"**temporarily** non-exercisable"* — **that word asserts a time bound nothing specifies** (`BR-1.7`), so it is flagged rather than silently kept or dropped.

**Consumption is the one non-exercisable cause no officer authorises** — the voter causes it. That alone distinguishes it from suspension and termination, and is why sharing `status='inactive'` is a modelling problem rather than a cosmetic one. **Deliberately not fixed.**

**11 Product Owner decisions listed, `BR-1.1`/`BR-1.2` first.** One ruling would close more of `BR-1` than any other: **whether the officer guide carries governance weight.**

---

## 📗 `BR-1` INVESTIGATED — the entitlement lifecycle

**[`../reviews/2026-08-12-election-membership-entitlement-lifecycle.md`](../reviews/2026-08-12-election-membership-entitlement-lifecycle.md)**
**Report: `D-ENT-1` APPROVED · `BR-1` INVESTIGATED · `Q3` UNDECIDED · `ADR-002` UNCHANGED · CONSTITUTION UNCHANGED · IMPLEMENTATION NOT AUTHORISED.**

**`BR-1` has no ratified rule, but it is not a blank slate — three authoritative constraints already bound the answer:**

1. **A documented officer-facing lifecycle exists** — `docs/election_management/04-voter-list.md`: four states, suspension **reversible by re-approval**, removal **"permanent"** at officer level but administratively recoverable. **Class E — documented intent, never ratified.** *(Found only this commission; I had never searched that folder — see the review's self-audit.)*
2. 🔴 **`revoke` is already taken, with an INVERTED meaning.** The governing vocabulary defines Revocation as withdrawing *identity trust* and says it **does NOT** *"block future voting"*. Entitlement revocation would exist precisely to block voting. **Recommend the naming decision not reuse `revoke`** — fourth collision.
3. 🔴 **`ADR-T11` (constitutional, build-breaking) makes one answer impossible.** *"No voter↔vote linkage in any aggregate, event payload, or projection."* **So terminating an entitlement after the voter has voted CANNOT reach that ballot** — the system cannot identify which vote was theirs. Post-vote termination has **election-level governance consequences only** (`ADR-003`). **A hard boundary, not a gap.**

**And the organisation side supplies a complete template** (class D): `SUSPENDED` reversible vs `TERMINATED` terminal and irreversible, **mandatory actor and mandatory reason**, guarded transitions, domain events.

**Two findings that change earlier characterisations of this ticket's own gaps:**

* **The single-actor `suspend` is the DOCUMENTED path; the four-eyes flow is UNDOCUMENTED** in the officer guide. So *"four-eyes protects the franchise"* is not yet true — which is intended is **`BR-1.13`**, a Product Owner question.
* **No test anywhere asserts that a suspended VOTER cannot vote.** Every such test concerns the *election* being suspended or the *organisation member* being suspended. **So the test estate would not notice if voter-suspension enforcement disappeared** — confirming the `G-5` load-bearing warning independently, from the test side.

**Open:** `BR-1.1`…`BR-1.13`, with **termination semantics (`BR-1.1`/`BR-1.2`) first — everything else hangs off it.**

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

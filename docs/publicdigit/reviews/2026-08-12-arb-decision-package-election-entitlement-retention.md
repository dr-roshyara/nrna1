# ARB Decision Package — Election entitlement retention (`D-ENT-1`)

**Type:** ARB decision package · **Date:** 2026-08-12 · **Programme:** IERVP (Session 2)
**Status:** **MODEL B DOMAIN ANALYSIS COMPLETE — AWAITING PRODUCT OWNER / ARB DECISION**
**Mode:** analysis only. **No production code, test, fixture, configuration, migration, database, Constitution or ADR changed.** Nothing renamed. No experiment repeated.
**Supersedes nothing.** Extends [`2026-08-12-model-b-election-membership-domain-analysis.md`](2026-08-12-model-b-election-membership-domain-analysis.md) with the decision framing the Product Owner requested.
**Not an ADR.** `ADR-002` is **Accepted** and is **not amended by this document.**

---

## 1 · The decision question

> **`D-ENT-1` — When a person has been admitted as a voter to an election under Full Membership mode, and subsequently loses or has suspended their organisation membership, what happens to the election-specific entitlement?**

**Two sub-questions ride on it and cannot be answered separately:**

* **`D-ENT-1a`** — Does `ADR-002`'s *"eligibility computed from membership at check time"* survive, get re-read, or get amended?
* **`D-ENT-1b`** — Where does a Chief's suspension decision **live**, given that today it has nowhere to go but the entitlement's own `status` field?

---

## 2 · The Product Owner's refined formulation (adopted verbatim)

The Product Owner narrowed Model B, and the narrowing matters:

> **Not:** *"`ElectionMembership` is always a durable right to vote."* — too broad.
>
> **But:** **"`ElectionMembership` is the election-specific entitlement record. Its existence and identity are election-specific. Whether that entitlement is currently *exercisable* is governed by the election's voting rules and suspension state."**

**PROPOSED DECISION.** This wording is used throughout below. It separates **existence** (durable) from **exercisability** (governed) — which is precisely the axis the current implementation collapses.

---

## 3 · Observed facts

**All `OBSERVED FACT` unless marked. Measured read-only, 2026-08-12.**

### 3.1 There are FOUR eligibility definitions for this one concept

| # | Definition | Model it encodes | Callers | Live? |
|---|---|---|---|---|
| 1 | `ElectionMembership::scopeEligible()` — `status='active'` + not expired + **`EXISTS` an active `members` row, not expired** | **Model A** (continuous org evaluation) | **0** | ❌ dormant |
| 2 | `ElectionMembership::isEligible()` — `status='active'` + not expired, **no org check** | **Model B-shaped** | **0** | ❌ dormant |
| 3 | `User::isVoterInElection()` — `role='voter' AND status='active'` | Model B-shaped, **weaker** (no expiry check) | `EnsureElectionVoter`, `VoteEligibility` | ✅ **the live gate** |
| 4 | `User::isEligibleToVote()` — `is_voter && can_vote` | legacy | `VoteEligibility:114` (non-slug branch) | ⚠️ reads columns that **exist in no database** (`PBDIGIT-35`) |

> 🔑 **The ARB is not choosing in a vacuum. Both candidate models are ALREADY WRITTEN in the codebase, and the live path implements neither faithfully.**

**And `scopeEligible()` — the Model A implementation — would return `0` of `20` rows if it were used**, because its `EXISTS` clause requires a `members` row and `members` is empty. **Measured.**

### 3.2 `status` is an accidental mixture — the answer to item 7

| Value | Producers | What it means | Axis |
|---|---|---|---|
| `active` | default · import · `approve` | admitted **and** exercisable | **both** |
| `inactive` | `markAsVoted()` · single-actor `suspend` · `confirmSuspension()` | **three different business facts** | **capability + consumption + governance** |
| `removed` | `remove()` | entitlement ended | **entitlement** |
| `invited` | **NO PRODUCER** — only a UI filter whitelist and a dashboard count | — | **unreachable** |

**CONCLUSION: `status` is neither entitlement nor capability. It is an accidental mixture of three axes** — entitlement existence, current capability, and vote consumption — **plus one value that can never occur but is still displayed.**

### 3.3 Why the implementation mutates the entitlement — there is nowhere else to write

**`can_vote_now` does not exist on `ElectionMembership`. It lives on `VoterSlug`** — a short-lived credential refreshed to `now()->addMinutes(30)` by every `start()` call.

> **INTERPRETATION, and it explains rather than merely criticises:** the Product Owner's proposed clean model — *entitlement exists; `can_vote_now = false` carries the suspension* — **cannot currently be expressed.** The only capability field belongs to a 30-minute credential that the voter themselves re-creates on demand. **A Chief's governance decision has no durable capability field to live in, so it was written into `status`.** That is why `ElectionConstitution:126-128` (*"freezes capabilities only… does NOT mutate business facts"*) is violated: **the structure offers no capability to freeze.**

### 3.4 Suspension / restore paths — ownership and four-eyes (item 8)

| Path | Actors | Guard | Writes | Audit | Business-rule owner |
|---|---|---|---|---|---|
| `propose-suspension` | 1 | `manageVoters` · must be `active` · must not have voted | `suspension_status='proposed'` | ✅ `voting_security` | Application |
| `confirm-suspension` | **2** | `canConfirmSuspension()` — `proposed_by !== confirmer->name` | `status='inactive'`, `suspension_status='confirmed'` | ✅ `voting_security` | Application |
| `cancel-proposal` | 1 | — | back to `none` | ❌ | Application |
| **`suspend`** | **1** | `manageVoters` only | `status='inactive'` (**leaves `suspension_status='none'`**) | ❌ | **unowned** |
| **`approve`** | **1** | `manageVoters` only | `status='active'` (**leaves `suspension_status='confirmed'`**) | ❌ | **unowned** |
| `DELETE` | 1 | `manageVoters` | `status='removed'` | ⚠️ only if `election->status==='active'` | Application |

**Consequences, all `OBSERVED IN CODE` — these endpoints were NOT executed:**

* **Suspension needs two people; undoing it needs one** (`approve`).
* **The four-eyes outcome is reachable single-handedly** (`suspend`) — so the control is real on its own path but **not on the outcome**.
* **`approve` on a suspended voter produces `status='active'` + `suspension_status='confirmed'`** — a row that **votes while recorded as suspended**.
* **`ElectionMembership` emits no domain events at all.** The two ungoverned paths write no security audit.
* **No `restore` concept exists** — `approve` is a de facto restore with a different name and no governance.

### 3.5 The Constitution

`admit` **0** · `revoke` **0** · `restore` **0** · `suspend` **6, every one election-level** · `ElectionMembership` **0**. Every constitutional rule's `target_state` is an **election** state; **no rule takes a person as its object.** Voters appear only as the countable precondition `has_voters`.

---

## 4 · Current authoritative sources

| Source | Status | What it says that bears on `D-ENT-1` |
|---|---|---|
| **`ADR-002`** Verified ≠ Eligible ≠ Authorized | **Accepted** 2026-05-30 | `eligibility_status` is *"computed from membership at check time"*; eligibility dimensions include *"Membership status (Active/Expired/Suspended)"* and *"Election assignment (enrolled…)"*. **Enrolment is a dimension OF eligibility, not a separate axis** |
| **`UBIQUITOUS_LANGUAGE.md`** | governing vocabulary | *"Eligibility is current condition (assessed at action time)"*; `Eligible` **persists** *"Per-process"*, changed by *"Status changes, fees, time"* |
| **`ADR-003`** Governance-Driven Revocation | **Accepted** | Revokes **verification**, a different object. Reusable principle: revocation does **not** auto-invalidate past votes — governance decides consequences |
| **`ElectionConstitution:126-128`** | in force | Suspension *"freezes capabilities only. Does NOT mutate business facts"* |
| **`VoterSourceStrategy`** | in force, **case names `@deprecated`** | *"Election snapshot is sovereign runtime authority… IMMUTABLE… org mutations don't retroactively change them."* Names await a **"Phase 4 governance-language review"** |
| **`VotingEligibilityPolicy`** | implemented | *"the authoritative eligibility engine. All Elections voting decisions flow through this policy"* — **0 production callers** |
| **`Model B`** | **Product Owner preferred candidate** — not ratified, not implemented | §2 above |

---

## 5 · Model A — Projection

**Current organisation membership is continuously authoritative.**

```
Member ACTIVE      -> election eligibility
Member SUSPENDED   -> no election eligibility
Member TERMINATED  -> no election eligibility
ElectionMembership = an enrolment record; eligibility is recomputed per check
```

**Already implemented** as `scopeEligible()`. **Endorsed by** `ADR-002` and `UBIQUITOUS_LANGUAGE` as written.

## 6 · Model B — Durable election entitlement

**Admission creates an election-specific business fact.**

```
Member ACTIVE
   -> admission decision
   -> ElectionMembership created            (entitlement EXISTS - durable)
   -> later Member suspension               (entitlement UNAFFECTED)
   -> exercisability governed by election rules + election-level suspension
```

**Endorsed by** the Product Owner; **consistent with** `VoterSourceStrategy`'s sovereignty clause; **partially implemented** as the dormant `isEligible()` and, weakly, by the live `isVoterInElection()`.

---

## 7 · Consequences of each

| Dimension | **Model A** | **Model B** |
|---|---|---|
| **Chief's independence** | ⚠️ Weakened — org actions silently change election participation | ✅ Chief's decision is the only thing that changes election participation |
| **Fits `ADR-002` as written** | ✅ yes | ❌ needs `D-ENT-1a` resolved |
| **Fits `VoterSourceStrategy` sovereignty** | ⚠️ tension — org mutations *do* reach the election | ✅ direct extension |
| **Auditability of a franchise change** | ⚠️ diffuse — no election-side event; cause lies in another context | ✅ localised to an election act |
| **Behaviour today if activated** | 🔴 **Nobody could vote** — `scopeEligible()` returns 0/20 because `members` is empty | ✅ matches observed behaviour |
| **Election-Only mode** | ⚠️ **Incoherent** — requires a `Member` that the mode does not create | ✅ coherent; one entitlement concept for both modes |
| **Late-breaking ineligibility** (fees lapse mid-election) | ✅ automatic | ⚠️ requires an explicit Chief act — **`BUSINESS RULE NOT SPECIFIED`: is that acceptable?** |
| **Cross-context coupling** | high — Elections depends on Membership state continuously | low — dependency only at admission |
| **Work implied** | wire `scopeEligible()`, populate `members`, make Full Membership reachable | define `admit`/`suspend`/`restore`/`revoke`; give suspension a home that is not `status` |

---

## 8 · The `ADR-002` conflict (`D-ENT-1a`)

**Precisely stated, so it is not overstated:** Model B does **not** contradict `ADR-002`'s *orthogonality*. It contradicts one clause — **which membership is re-evaluated at check time.**

**Three dispositions. All are legitimate; only the ARB may choose.**

| | Disposition | Effect on `ADR-002` |
|---|---|---|
| **A1** | **Re-read** — `ADR-002`'s "membership" means **`ElectionMembership`**. Eligibility then re-checks the *election* entitlement at each action, and both hold | ✅ unchanged; a clarifying note recorded. **Cost:** must reconcile with its own words *"Active membership? … Full member type?"*, which are organisation concepts |
| **A2** | **Amend** — Model B adds **Entitlement** as a fourth axis; eligibility no longer re-evaluates organisation membership for retention | ⚠️ **`ADR-002` needs a superseding ADR.** Cleanest conceptually; heaviest governance |
| **A3** | **Scope** — `ADR-002` governs Full Membership; Model B governs Election-Only | ❌ **Not recommended** — produces two meanings for one concept, the opposite of the Product Owner's *"two modes, one entitlement concept"* |

**PRODUCT OWNER / ARB DECISION REQUIRED.** No classification of A/B/C is offered — this is **D — AMBIGUOUS** by design.

---

## 9 · Constitutional implications

| If **Model A** | If **Model B** |
|---|---|
| No new constitutional action strictly required — eligibility stays derived | **`admit` must be named** — admission becomes a constitutive act with legal effect |
| Suspension stays an organisation concept | **Voter-level `suspend` / `restore` / `revoke` must be named**, and distinguished from election-level `suspend` (**`CG-4` collision**) |
| `ElectionMembership` may remain constitutionally absent | **`ElectionMembership` must acquire constitutional existence** — it currently appears **0 times** |
| — | **Suspension must be expressible without mutating a business fact** (`ElectionConstitution:126-128`) — today impossible (§3.3) |
| — | **`restore` authority must be specified** — today `approve` restores single-handedly |

**Either way:** the voter-level verbs should be settled inside the **Phase 4 governance-language review that `VoterSourceStrategy` already designates**, not a parallel exercise (**ES-005.4** — extend, never create a second).

---

## 10 · Full Membership implications — kept strictly separate (item 6)

**No statement here is inferred from Election-Only evidence.**

* **`members` has 0 rows platform-wide.** **OBSERVED FACT.** The mode's source of truth is empty.
* **Therefore no Full Membership admission has ever occurred**, and **no retention scenario has ever been exercised.** **CONCLUSION.**
* **`D-ENT-1` is a Full Membership question, and Full Membership is the mode we have zero runtime evidence for.** **This is the central epistemic limit of this package.**
* The declared admission rule (`FullMembershipPolicy`: active `Member` + fees paid/exempt + not deleted) is a **stub returning `true`**; the real logic sits in `decideForContext()`, reached only via `EloquentVoterEligibilityQueryService`. **Whether Full Membership admission would enforce its own declared rule is `MECHANISM NOT ESTABLISHED`.**
* **What must NOT be concluded:** that Full Membership behaves like Election-Only. **They differ precisely where the decision bites.**

## 11 · Election-Only implications

* **Model B is consistent with all Election-Only evidence.** The mode creates no `Member`, and `Member` is not consulted on the live path. **OBSERVED FACT.**
* **Under Model A, Election-Only is incoherent** — `scopeEligible()` demands a `Member` the mode never creates. **OBSERVED FACT** (0/20 measured).
* **`D-ENT-1` is largely moot for Election-Only**: with no organisation `Member`, there is no organisation membership to lose. **The entitlement is durable here by absence of any competing authority, not by decision.** **INTERPRETATION** — and worth stating, because Election-Only evidence therefore **cannot** validate Model B's core claim.
* Suspension/restore asymmetries (§3.4) apply to **both** modes — they are independent of `D-ENT-1`.

---

## 12 · Unresolved questions

| # | Question | Type |
|---|---|---|
| **Q1** | `D-ENT-1` itself — Model A or Model B? | **PRODUCT OWNER / ARB** |
| **Q2** | `D-ENT-1a` — re-read, amend or scope `ADR-002`? (A1/A2/A3) | **ARB** |
| **Q3** | `D-ENT-1b` — where does a suspension live, if not in `status`? A capability field on the entitlement, a separate governance record, or events? | **ARB → then engineering** |
| **Q4** | Is voter suspension temporary or indefinite; is there a review obligation? | **BUSINESS RULE NOT SPECIFIED** |
| **Q5** | Must `restore` require the same authority and actor count as `suspend`? | **BUSINESS RULE NOT SPECIFIED** |
| **Q6** | Is revocation terminal and irreversible, as `TERMINATED` is organisation-side? | **BUSINESS RULE NOT SPECIFIED** |
| **Q7** | Under Model B, must a mid-election loss of standing (fees lapse) have **any** automatic effect? | **PRODUCT OWNER** |
| **Q8** | Which component is the authoritative eligibility engine — `VotingEligibilityPolicy` or the live predicate? | **ARB** |
| **Q9** | Must the entry surface refuse a suspended voter, and may a credential be issued to one? (Today: no, and yes.) | **PRODUCT OWNER** |
| **Q10** | Do entitlement changes require domain events / audit records? | **ARB** |
| **Q11** | Should the unreachable `invited` status be given a producer, or removed from the enum? | **Engineering, after Q1** |

---

## 13 · Recommended decision

**RECOMMENDATION — offered as a recommendation, and engineering does not decide it.**

1. **Adopt Model B**, in the Product Owner's refined wording (§2). **Rationale:** it is the only model coherent across **both** modes; it is a direct extension of `VoterSourceStrategy`'s already-in-force sovereignty clause; it preserves the Chief's independent authority, which is the capability the ruling exists to protect; and Model A's own implementation returns **0 of 20** rows today.
2. **Resolve `ADR-002` via A2 (amend), not A1 (re-read).** **Rationale:** A1 requires reading *"Active membership? … Full member type?"* as `ElectionMembership`, which those words do not support. **An honest amendment is preferable to a strained reading** — and `ADR-002`'s orthogonality survives either way; only the retention clause changes.
3. **Answer `Q3` before any code.** Model B is unimplementable cleanly while suspension has nowhere to live but `status` (§3.3). **This is the true blocker, and it is an architecture decision, not a refactor.**
4. **Treat the §3.4 asymmetries as a separate authorised slice.** They are **defects of the mechanism, not of the model**, and they exist under Model A too. **Do not bundle them into the `D-ENT-1` decision.**
5. **Do not vacate `status='inactive'`** until its replacement enforces. It is currently the **only** enforcement of the Chief's authority.
6. **Do not activate `scopeEligible()`** under any disposition without first populating `members` — it would deny every voter in the system.

**What must NOT be concluded from this package:**

* ❌ that Model B is *proven* — **Full Membership composition remains unverified, and it is the mode the decision concerns**;
* ❌ that the existing suspension mechanism is adequate — **the business model may be right while its implementation is wrong**;
* ❌ that `ADR-002` is superseded — **it is Accepted and untouched by this document**.

---

## Boundaries

* **Nothing changed:** no production code, test, fixture, configuration, migration, database write, Constitution edit or ADR edit. Nothing renamed or refactored. No runtime experiment repeated.
* **`PBDIGIT-69` is excluded from every verdict above** — a technical/application defect, not evidence for or against a business model. It appears only in this sentence.
* **Session 1 is not authority here**, and nothing of Session 1's — Master Matrix, denominator, `SD-1`, `SD-2`, Slice 1 classification, `PBDIGIT-48` plan — is read as specification or modified. **Conversely, this package must not be used as Session 1's specification**: it records a *candidate* model awaiting decision.
* **The git incident of 2026-08-12 is closed** — repaired, recorded, control adopted; no further effort spent.

**Traceability:** `app/Models/ElectionMembership.php:126-146` (`scopeEligible` — Model A) `:157-162` (`isEligible`) `:164-171` `:173-197` `:202-224` `:243-270` · `app/Models/User.php:315-328` (live gate) `:401-405` (legacy flags) · `app/Http/Controllers/ElectionVoterController.php:196-216,222-241,290-356` · `app/Http/Controllers/ElectionVotingController.php:41-44,111,156-191` (`can_vote_now` on `VoterSlug`) · `app/Domain/Election/Constitution/ElectionConstitution.php:23-147` · `app/Domain/Election/Enum/VoterSourceStrategy.php:10-60` · `app/Contexts/Membership/Domain/Voting/VotingEligibilityPolicy.php` · `app/Contexts/Elections/Domain/Policies/FullMembershipPolicy.php` · `docs/adr/ADR-002-verified-eligible-authorized.md` · `docs/adr/ADR-003-governance-driven-revocation.md` · `docs/architecture/trust-domain/UBIQUITOUS_LANGUAGE.md:74-110,274-300` · `database/migrations/2026_03_17_213212_create_election_memberships_table.php:24-58` · measured read-only 2026-08-12: `members` **0** rows · `election_memberships` **20**, `expires_at` NULL on all · `scopeEligible()` **0/20** · `scopeEligible`/`isEligible` **0 callers** · `VotingEligibilityPolicy` **0 production callers** · `invited` **no producer**.

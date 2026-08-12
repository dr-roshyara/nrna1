# Session 3 governance readiness report — Election-Only

**Type:** Readiness report · **Date:** 2026-08-12 · **Programme:** IERVP (Session 2)
**Question:** **Can Session 3 begin Election-Only implementation now?**
**⛔ No implementation. No production code, test, fixture, schema or migration change. `ADR-002` not amended. No technical solution designed. No decision option chosen. Session 1's Master Matrix not consumed, read as authority, or modified; no row classified.**

---

## Verdict

> ## ⚠️ **NOT READY as a whole — but the blocking set is TWO, not seven.**
>
> **Minimum to begin anything:** **`BR-1.12`** (admission gate) and **`BR-1.13`** (suspension actor). **Both are cheap business answers, neither needs analysis.**
>
> **Then `Q3` before any change to how non-exercisability is represented.**
>
> **The remaining four are slice-scoped** — each blocks only its own slice, not the start of work. **And `Q-E1`'s core question is already answered by adopted authority** (§2.5).

**I applied the instruction not to treat a question as blocking merely because it is interesting.** Of the seven I previously listed, **two are unconditional blockers, one is triggered by scope, four are slice-scoped, and one of those four is already half-answered.**

---

## 1 · 🔑 The mode boundary — measured

**This determines everything about cross-mode risk, so it was measured rather than assumed.**

> **The mode boundary sits exactly at ADMISSION, and nowhere else.**

In `ElectionVoterController`, `VoterSourceStrategy` is consulted at **four** places — lines **74** (eligible-but-unassigned query), **113** (assign validation), **126**, **157** (bulk assign). **All four are admission.** The suspension, restoration and removal methods — `destroy` (171), `approve` (196), `suspend` (222), `proposeSuspension` (290), `confirmSuspension` (330) — **consult the mode nowhere.**

**This is consistent with the adopted model** (`A-6`: the modes differ only in how someone is admitted). **But it means every post-admission behaviour is shared.**

### Classification of implementation paths

| Path | Class | Why |
|---|---|---|
| `ElectionOnlyPolicy` · `EligibilityContext` | ✅ **SAFE** — Election-Only-specific | mode-scoped by construction |
| `VoterImportService::previewElectionOnly` / `importElectionOnly` | ✅ **SAFE** | explicitly branched on the mode |
| `VoterEligibilityService::isEligibleVoter($org,$user,$mode)` | ⚠️ **SHARED (mode-parameterised)** | the mode is **passed in**, so behaviour is separable — **but the service is one object** |
| `ElectionVoterController` **admission** methods (`index`, `store`, `bulkStore`) | ⚠️ **SHARED (mode-aware)** | consult the mode; changes are separable with care |
| 🔴 `ElectionVoterController` **suspension/restore/removal** (`suspend`, `approve`, `propose/confirmSuspension`, `cancelProposal`, `destroy`) | 🔴 **SHARED — mode-blind** | **no mode branch. Any change here defines Full Membership behaviour too** |
| 🔴 `ElectionMembership` methods (`markAsVoted`, `remove`, `propose/confirmSuspension`, `cancelSuspensionProposal`) | 🔴 **SHARED — mode-blind** | the aggregate is common to both modes |
| 🔴 `User::isVoterInElection()` — **the live ballot gate** | 🔴 **SHARED — mode-blind** | one predicate for both modes |
| 🔴 `EnsureElectionVoter` · `VoteEligibility` middleware | 🔴 **SHARED — mode-blind** | on every real slug route |
| 🔴 `ElectionVotingController::show/start` (entry surface, credential issuance) | 🔴 **SHARED — mode-blind** | — |
| 🔴 `EnsuresVoterMembership` trait — used by `OrganisationController`, `CodeController`, `VoteController` | 🔴 **SHARED — mode-blind** | a **third** consumer set beyond the obvious two |
| `ElectionLifecycle` · `ElectionClockService` | ✅ **SAFE** (mode-irrelevant) | election-level, not voter-level |
| `VoterSlug` · `Code` credential | ✅ **SAFE** (mode-irrelevant) | — |
| `FullMembershipPolicy` · `scopeEligible()` · `members` table | ⛔ **FULL-MEMBERSHIP — defer** | `FullMembershipPolicy` is a **stub**; `scopeEligible()` is dormant and would return **0/20** |
| `MembershipSuspended` / `Terminated` / `Restored` events · any subscriber | ⛔ **FULL-MEMBERSHIP — defer** | `FM-11`; **nothing subscribes today** |

> ### 🔴 The consequence, stated plainly
>
> **Everything Session 3 would need to touch to build Election-Only suspension, restoration or removal is MODE-BLIND SHARED code.** There is currently **no seam** at which Election-Only behaviour can be changed without also defining Full Membership behaviour.
>
> **So "we are implementing Election-Only" cannot be true of those changes as the code stands.** *(How to create a seam is Session 3's and the ARB's to decide — I prescribe nothing.)*

### Proposed hard stop — governance rule, not implementation design

> **If an implementation change affects both Election-Only and Full Membership semantics, Session 3 must STOP and report the cross-mode impact rather than silently accepting it.**

**Given §1, this will trigger on the first suspension, restoration or removal change.** **That is the rule working, not the rule failing.**

---

## 2 · The seven questions, in priority order

### 2.1 `Q3` — Exercisability · **BLOCKING when scope includes changing non-exercisability**

| | |
|---|---|
| **Exact question** | **What is the named concept that answers *"may this ElectionMember vote now?"*, and where does a suspension decision live, given it must not mutate the entitlement?** |
| **Adopted authority** | `A-2` existence ≠ exercisability · `ElectionConstitution:126-128` — suspension *"freezes capabilities only… does NOT mutate business facts"* |
| **Contradiction** | Suspension writes `status='inactive'` — the entitlement's own field. **No capability field exists on `election_memberships`**; `can_vote_now` belongs to a **30-minute, voter-refreshable credential** |
| **Genuinely blocking?** | ⚠️ **Scope-triggered.** **NOT blocking** for admission work, or for leaving current behaviour intact — the ballot is already refused today. **BLOCKING** for any change to how non-exercisability is represented, and for `Q-E2` |
| **Blast radius** | 🔴 **Largest of the seven.** Every mode-blind path in §1: the live gate, both middlewares, the entry surface, the aggregate's methods, the `status` semantics, and the shared trait's three consumers |
| **Options** | **A** capability field on the entitlement · **B** separate governance record · **C** decision/event log · **D** voter-level overlay mirroring the in-force election-level one (`suspended_at` + engine-derived state) |
| **Recommendation** | **None chosen.** *Context only:* `D` matches the pattern already in force one level up — **and was the original architecture proposal, rejected on availability grounds** (*"None exist → used existing `status`"*) |

> **The concern the Product Owner raised is exactly right, and it is the reason `Q3` is first:** without a named concept, Session 3 would reconstruct exercisability inline as `$isEligible && !$hasVoted && $lifecycle->canVote()`. **That expression is already in the codebase at `ElectionVotingController:51`, and it is precisely what made the earlier findings hard to reason about.**

### 2.2 `BR-1.12` — Admission gate · 🔴 **UNCONDITIONALLY BLOCKING**

| | |
|---|---|
| **Exact question** | **Does admitting a person to an election produce `invited` (a distinct approval step then required), or `active` directly?** |
| **Adopted authority** | **None.** Clause 10 says a person *"may become an ElectionMember directly"* — which speaks to **no organisation prerequisite**, not to an approval step |
| **Contradiction** | 🔴 **Schema, UI, tests and documentation all expect `invited`; no production path writes it.** *(UI has a label, a status pill and an `invited → active` action; tests construct it directly; the guide says it twice)* |
| **Genuinely blocking?** | ✅ **YES, unconditionally.** Admission is the **first** Election-Only slice; Session 3 cannot build it without knowing whether an approval gate exists |
| **Blast radius** | Assignment and import paths, the voter-list UI, the four-state model, existing tests that construct `invited` |
| **Options** | **A** implement the gate *(assignment yields `invited`; approval activates)* · **B** remove the state *(assignment yields `active`; the enum value and its UI go)* · **C** keep as display-only |
| **Recommendation** | **None.** *Relevant fact only:* under **B**, existing tests that construct `invited` would be asserting a state the business has abolished |

### 2.3 `BR-1.13` — Suspension actor · 🔴 **UNCONDITIONALLY BLOCKING**

| | |
|---|---|
| **Exact question** | **Is suspending an ElectionMember a ONE-actor or a TWO-actor (four-eyes) act?** |
| **Adopted authority** | Clause 5 — *"The Election Chief may independently suspend"*. **"Independently" distinguishes it from the organisation cause; it does not state an actor count** |
| **Contradiction** | 🔴 **Two mechanisms exist and both are live.** A one-actor `suspend` route, and a two-actor `propose → confirm` flow (proposer ≠ confirmer enforced). **Tests endorse both. The officer guide documents only the one-actor path.** Only the two-actor path writes an audit record |
| **Genuinely blocking?** | ✅ **YES, unconditionally.** Session 3 cannot implement, extend or retire a suspension path without knowing which is canonical — **and either is mode-blind (§1)** |
| **Blast radius** | Both route sets, `manageVoters` authority, the audit story, the officer guide, existing tests on both paths |
| **Options** | **A** four-eyes only *(retire the one-actor route)* · **B** one-actor only *(retire propose/confirm)* · **C** both, with **explicitly different meanings** |
| **Recommendation** | **None** — this is a governance-strength choice, not a technical one. *Fact:* while both exist, **"four-eyes protects the franchise" is not true**, because the outcome is reachable single-handedly |

### 2.4 `BR-1.1` / `BR-1.2` — Removal semantics · **SLICE-SCOPED**

| | |
|---|---|
| **Exact question** | **When the Election Chief removes an ElectionMembership, what happens to the entitlement, and is it reversible — and by whom?** |
| **Adopted authority** | Clause 6 — removal **does not** remove Organisation Membership *(direction settled)* · `A-5` — persists *"unless a defined election-level rule terminates it"*, **and that rule is still unratified** |
| **Contradiction** | The officer guide calls removal *"permanent"*, but **`approve()` has no guard against a `removed` row** — permanence rests on a hidden button. A second guide statement says an administrator may *"manually restore the membership record"* |
| **Genuinely blocking?** | ⚠️ **Only for the removal slice.** Not for admission or suspension |
| **Blast radius** | `destroy()` / `remove()`, `approve()`'s missing guard, audit *(currently conditional on a legacy column)*, the record-retention story |
| **Options** | **A** entitlement ceases permanently · **B** record retained, exercisability withdrawn, administratively reversible |
| **Recommendation** | **B** — *evidence:* every layer already behaves this way; `SoftDeletes` is available and deliberately unused; `destroy()` row-locks against a live vote; **and under A, erasing an entitlement would retroactively change the denominator of a possibly-published result** |

### 2.5 `Q-E1` — Credential control · **PARTLY ALREADY ANSWERED**

| | |
|---|---|
| **The Product Owner's framing** | *"Does possession of a valid credential make an otherwise non-exercisable ElectionMember exercisable?"* |
| **✅ Answer from ADOPTED authority — not from code** | **NO.** `A-2` separates existence from exercisability, and credential possession is **not a term** in the adopted decision set. **A credential is proof of possession, not a grant of entitlement.** *Corroborated by measurement, not derived from it:* a suspended voter **was** issued a fresh credential **and was still refused the ballot** |
| **What remains open** | The **narrower, converse** question: **should suspension PREVENT issuance, and REVOKE an existing credential?** *(Today: neither.)* |
| **Genuinely blocking?** | ❌ **NOT blocking.** The vote gate is already correct on this axis. **This is a coherence and security defect — the system issues an instrument it will not honour — and it can be scheduled** |
| **Blast radius** | Credential issuance in the entry surface; `VoterSlug`/`Code` lifecycle. **Note: no governance path touches either today** |
| **Options** | refuse issuance · revoke on suspension · both · neither *(status quo, but documented as intentional)* |
| **Recommendation** | **Decide explicitly at some point** — silence here produced the incoherence. **Not a blocker** |

### 2.6 `Q-E2` — Distinguishability · **DEPENDENT ON `Q3`, not independently blocking**

| | |
|---|---|
| **Exact question** | **Must a suspended ElectionMember be distinguishable from one who has already voted, at the enforcing gate?** |
| **Adopted authority** | Implied by `A-2` and by clause 4's insistence that causes stay distinct |
| **Contradiction** | Both are `status='inactive'`; `isVoterInElection()` reads only `role` + `status`; **`markAsVoted()` also writes `inactive`** |
| **Genuinely blocking?** | ❌ **Not independently.** It is **answered by whatever `Q3` decides** — a representation that distinguishes causes satisfies it automatically |
| **Blast radius** | Same as `Q3` |
| **Options** | distinguishable **at the gate** · distinguishable **only in the record** · not required |
| **Recommendation** | **At the gate** — *reason:* the two facts have **different authors** (an officer vs the voter). A gate that cannot tell them apart cannot report or audit either correctly |

### 2.7 `BR-1.8` — Restoration authority · **SLICE-SCOPED**

| | |
|---|---|
| **Exact question** | **Who may restore a suspended ElectionMember, and with how many actors?** |
| **Adopted authority** | **None** |
| **Contradiction** | ⚠️ **Two actors to suspend via the governed path; ONE to restore** (`approve`) — and `approve` **leaves `suspension_status='confirmed'`**, so a restored voter votes while recorded as suspended |
| **Genuinely blocking?** | ⚠️ **Only for the restore slice.** Restoration already exists as documented behaviour, so Session 3 can defer it |
| **Blast radius** | `approve()`, the suspension record's residue, audit, the officer guide |
| **Options** | symmetric with suspension · one actor · a distinct authority |
| **Recommendation** | **None** — both directions are defensible *(withdrawal deserves more control; restoration is the safe direction)* |

---

## 3 · Minimum decision set

| Tier | Decisions | Effect |
|---|---|---|
| **Tier 1 — before ANY Election-Only work** | **`BR-1.12`** · **`BR-1.13`** | Unblocks the admission and suspension slices. **Both are business answers requiring no further analysis** |
| **Tier 2 — before changing non-exercisability** | **`Q3`** *(and `Q-E2` follows from it)* | Unblocks suspension enforcement, the entry surface, and the gate |
| **Tier 3 — per slice** | **`BR-1.1`/`1.2`** *(removal)* · **`BR-1.8`** *(restore)* · **`Q-E1`** *(credential coherence)* | Each blocks only its own slice |

**Plus one non-decision prerequisite, which is Session 3's to resolve, not a question for the ARB:** **a mode seam.** Per §1, no seam exists — every post-admission path is mode-blind, so the hard stop will trigger immediately on suspension/removal/restore work.

> **If the ARB answers Tier 1 only, Session 3 can begin — starting with admission, and stopping at the first mode-blind change per the hard stop.** **That is a legitimate, useful start, and it does not require Tier 2 or 3.**

## 4 · Guardrails — no decision needed, must not be violated

| # | Guardrail |
|---|---|
| **G-1** | **`scopeEligible()` must NOT be activated** — 0 callers, returns **0/20**, and it is a Full Membership mechanism in a phase that has none |
| **G-2** | **`status='inactive'` must not be vacated before its replacement enforces** — it is currently the only thing preventing a suspended voter from voting |
| **G-3** | **`ADR-T11`** — nothing may identify or mutate a cast ballot |
| **G-4** | **The hard stop (§1)** — cross-mode impact must be reported, never silently accepted |
| **G-5** | **`ElectionUser` must not be cited as `Q3` prior art** — a dead legacy model built on a retired flag, and **now parseable**, which makes the mistake easier |

## 5 · Boundaries

* **Nothing implemented.** No production code, test, fixture, schema or migration change. **No technical solution designed, and no decision option chosen** — §2 states options and, where evidence permits, gives a reason without selecting.
* **No implementation preference turned into a business decision.** Where the only available answer came from adopted authority (`Q-E1`, §2.5), that is labelled as such and distinguished from the corroborating measurement.
* **`ADR-002` not amended.** Constitution untouched. **No Full Membership question resolved** — `FM-1`…`FM-15` remain frozen.
* **Session 1's Master Matrix not consumed, read as authority, or modified. No row classified.**
* **Runtime residue untouched** — IERVP test data · working-organisation restoration · `voter_source_strategy` backfill · `VoterSlugStep.php` (`PBDIGIT-70`, still unparseable).

**Traceability:** `app/Http/Controllers/ElectionVoterController.php:74,113,126,157` *(mode-aware — all admission)*, `:171,196,222,290,330` *(mode-blind)* · `app/Models/ElectionMembership.php:126-146,164-171,173-197,202-224` · `app/Models/User.php:315-328` · `app/Http/Middleware/{EnsureElectionVoter,VoteEligibility}.php` · `app/Http/Controllers/ElectionVotingController.php:41-44,51,111,156-191` · `app/Traits/EnsuresVoterMembership.php` *(consumed by `OrganisationController`, `CodeController`, `VoteController`)* · `app/Contexts/Elections/Domain/Policies/{ElectionOnlyPolicy,FullMembershipPolicy}.php` · `app/Services/{VoterEligibilityService,VoterImportService}.php` · `app/Domain/Election/Constitution/ElectionConstitution.php:126-146` · measured this session: `members` **0 rows** · `scopeEligible()` **0/20** · **the mode is consulted at admission only**.

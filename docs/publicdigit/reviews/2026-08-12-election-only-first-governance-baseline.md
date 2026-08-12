# Election-Only first — governance baseline and Full Membership deferral

**Type:** Governance baseline + deferred-decision register · **Date:** 2026-08-12 · **Programme:** IERVP (Session 2)
**Sequencing decision (Product Owner):** **IMPLEMENT ELECTION-ONLY MODE FIRST. FULL MEMBERSHIP IS A SEPARATE LATER PHASE.**
**⛔ No implementation. No production code, test, fixture, schema or migration change. `ADR-002` not amended. Constitution untouched. No mechanism prescribed to Session 3. Session 1's Master Matrix not consumed, read as authority, or modified.**

---

## 0 · Why the deferral is architecturally clean, not merely convenient

**Clause 11 of the adopted decision does the work:**

> *"Organisation-membership removal therefore does **not** automatically suspend ElectionMembership in Election-Only Mode."*

**Consequence:** in Election-Only mode there is **no organisation→election causal edge at all.** The organisation-dependency branch is not "deferred pending a decision" — **it is absent from the model by decision.** So Election-Only can be specified, built and verified without any Full Membership answer.

**Corroborating evidence** *(measured, not inferred):* `members` has **0 rows**; `FullMembershipPolicy::isEligible()` is a **stub returning `true`**; no organisation→election trigger exists at any layer. **Full Membership is dormant in fact as well as deferred by decision.**

> ### ⚠️ But the deferral has one condition, and it is the most important thing in this document
>
> **Measured: no suspension, restoration or removal path branches on `voter_source_strategy`. The implementation is MODE-BLIND.**
>
> **Therefore Election-Only work will, by default, also become Full Membership behaviour** — and would **silently pre-empt the very decisions being deferred** (`W-1`…`W-8`).
>
> **Governance guardrail (not an implementation instruction):** whatever Session 3 builds must either be **scoped to Election-Only**, or its application to Full Membership must be **explicitly recognised as a decision taken**. **"We only built Election-Only" will not be true of a mode-blind change.** *(How to scope it is Session 3's and the ARB's to decide; I prescribe no mechanism.)*

---

## 1 · Election-Only governance baseline

### 1.1 Authority that already exists and is sufficient

| # | Rule | Source | Status |
|---|---|---|---|
| **EO-1** | `ElectionMembership` is an **election-specific entitlement** | `A-1` | ✅ **ADOPTED** |
| **EO-2** | **Existence ≠ exercisability** | `A-2` | ✅ **ADOPTED** |
| **EO-3** | **No Organisation Membership is required** | clause 9 · `VoterSourceStrategy` | ✅ **ADOPTED + AUTHORITATIVE** |
| **EO-4** | A person may be **admitted directly** to a specific election | clause 10 | ✅ **ADOPTED** |
| **EO-5** | **No organisation-driven suspension in this mode** | clause 11 | ✅ **ADOPTED** |
| **EO-6** | The **Election Chief may suspend** an ElectionMember for an election-specific reason | clause 5 | ✅ **ADOPTED** |
| **EO-7** | Removing an `ElectionMembership` **does not** affect organisation membership | clause 6 | ✅ **ADOPTED** *(trivially satisfied here)* |
| **EO-8** | `ElectionMembership` is governed by the **Election context / Election Chief** | clause 8 | ✅ **ADOPTED** |
| **EO-9** | One entitlement concept across both modes; only admission differs | `A-6` | ✅ **ADOPTED** |
| **EO-10** | The election's **lifecycle** governs whether voting is permitted | `ElectionConstitution` | ✅ **AUTHORITATIVE** |
| **EO-11** | **No voter↔vote linkage**; a cast ballot cannot be reached | `ADR-T11` — build-breaking | ✅ **AUTHORITATIVE** |
| **EO-12** | Authority for voter management = **active chief/deputy, election-scoped** | `ElectionPolicy::manageVoters` | ⚠️ **application-level only** — the Constitution names no voter-level action |

### 1.2 The Election-Only decision surface

```
Election Chief
      │  admit  (EO-3, EO-4 — no organisation prerequisite)
      ▼
ElectionMembership ──────────── entitlement EXISTS (EO-1, EO-2)
      │
      ├── Chief suspension (EO-6)        ─┐
      ├── ElectionMembership removal      ├─ causes of NON-exercisability
      ├── lifecycle not open (EO-10)      │  each retaining its own authority
      └── already voted                  ─┘
      │
      ▼
exercisable?  →  verified  →  authorized  →  vote (EO-11)
```

**Everything in this graph is either adopted or authoritative. No Full Membership concept appears in it.**

### 1.3 What is NOT settled inside the Election-Only surface

**These are Election-Only questions, not Full Membership ones. They are in §4 because they block Session 3.**

`BR-1.12` admission gate · `BR-1.13` suspension actor count · `BR-1.1`/`1.2` removal semantics · `BR-1.8` restoration authority · `Q-E1` credential control · `Q-E2` distinguishability · `Q3` exercisability representation · *(`BR-1.5`/`1.6` reason and audit — defaultable, §4.2)*

---

## 2 · Full Membership deferred-decision register — **FROZEN, NOT LOST**

**Recorded as OPEN and explicitly deferred. None is to be resolved now, and none may become a dependency of Election-Only implementation.**

| # | Deferred item | Origin |
|---|---|---|
| **FM-1** | Organisation Membership as **superior prerequisite** | clauses 1, 2 |
| **FM-2** | **Automatic suspension** following organisation-membership removal | clause 3 |
| **FM-3** | That automatic suspension is **distinct from** Chief suspension | clause 4 |
| **FM-4** | **Organisation Chief** authority — ⚠️ **the term has no current referent** (org roles are `member`, `owner`; `chief` exists only as an `ElectionOfficer` role) | clause 7 · `W-8` |
| **FM-5** | **What act constitutes "Organisation Membership removed"** — deleting the linkage row, or terminating the `Member` aggregate? | `W-7` |
| **FM-6** | Does **expiry** or organisation-side **suspension** also trigger it? *(clause 2 says "active"; clause 3 says "removed" — not complements)* | `W-5` |
| **FM-7** | Does organisation **restoration** lift the automatic suspension automatically? | `W-1` |
| **FM-8** | May the Election Chief **restore** a voter suspended by an organisation cause? *(is "superior" hierarchy or override?)* | `W-2` |
| **FM-9** | If **both** suspension causes hold, does clearing one restore exercisability? | `W-3` |
| **FM-10** | Does automatic suspension apply to a voter who **already voted**? | `W-4` |
| **FM-11** | **Trigger mechanics** — the org side already emits `MembershipSuspended`/`Terminated`/`Restored`; **nothing subscribes** | conformance finding |
| **FM-12** | **`FullMembershipPolicy::isEligible()` is a stub returning `true`** — admission unenforced | conformance finding |
| **FM-13** | **`FK-1`** — the declared FK to `user_organisation_roles` is **absent from the live schema**, so the *"is actually a member"* guarantee is unenforced | measured |
| **FM-14** | **`A-9`** — whether the `ADR-002` amendment may be applied with `FM-5`/`FM-6` open | proposal v2 |
| **FM-15** | Full Membership **reachability** — `members` has 0 rows; the mode has never been exercised (`IERVP-2`, `IERVP-3`) | measured |

**Retained in full in [`ADR-002 amendment proposal v2`](2026-08-12-adr-002-amendment-proposal-v2-from-adopted-d-ent-1.md) and the [decision register](2026-08-12-election-governance-decision-register.md). Nothing is discarded.**

---

## 3 · Does `ADR-002` need an immediate Election-Only amendment?

### 3.1 Assessment

**I tested each problematic `ADR-002` clause against Election-Only specifically:**

| Clause | Effect **in Election-Only** |
|---|---|
| `:161` *"eligibility computed from membership at check time"* | ⚠️ **Ambiguous but not wrong.** In Election-Only the **only** membership is the `ElectionMembership`, and re-evaluating **it** at check time is exactly what suspension requires. **Not an obstacle** |
| `:78` *"Active membership? Paid fees? Full member type? Enrolled?"* | ⚠️ **Partly inapplicable** — fees and membership type are Full Membership concepts. **Inapplicable ≠ contradictory.** Not an obstacle |
| `:71` same conflation | as above |
| Verified ≠ Eligible ≠ Authorized | ✅ **Valid and useful as-is** |
| Authorization formula | ⚠️ **Missing an entitlement term** — but the missing concept is supplied by **adopted decisions `A-1`/`A-2`** |

> **Finding: `ADR-002` as currently Accepted is NOT an obstacle to Election-Only.** Its problematic clauses are *ambiguous* or *inapplicable* in this mode, not contradictory.
>
> **What Election-Only genuinely needs — the `Entitled` / `Exercisable` distinction — is already ADOPTED as business authority (`A-1`, `A-2`), and precedent for consuming adopted decisions while `ADR-002` is unamended is already established in the Session-1 handover.**

### 3.2 Recommendation

> **RECOMMEND: do NOT amend `ADR-002` now. Proceed on the adopted business decisions, with the two-layer discrepancy recorded.**

**Reasons:**
1. **No Election-Only rule is blocked** by the current `ADR-002` text (§3.1).
2. **Amending now would mean amending twice** — once minimally for Election-Only, again for Full Membership. **A single amendment after Election-Only stabilises is cheaper and less likely to be self-contradictory.**
3. **A minimal amendment cannot avoid touching the shared axes.** Adding `Entitled` and `Exercisable` **necessarily** defines concepts both modes use — so a *"smallest possible Election-Only-only"* amendment would still pre-commit the structure the Full Membership decisions must fit. **That is precisely the contamination the sequencing decision exists to prevent.**
4. **The discrepancy is already managed**: the handover requires it be recorded and not erased.

**⚠️ The cost of this recommendation, stated plainly:** the two authority layers stay divergent for longer — **adopted business decision ≠ amended ADR ≠ implemented system**, all three visible at once. **That is a real governance cost, and it is the price of not amending twice.**

**If the ARB prefers architectural authority to be current instead:** the minimal Election-Only-scoped amendment is **already drafted** — proposal v2 **§2.1** (axis 0 `Entitled`), **§2.2 rows 2–4 only** *(excluding the organisation-driven cause)*, and **§2.4** (formula). **That subset contains no Full Membership rule.** **Either course is legitimate; the choice is the ARB's.**

---

## 4 · Business questions that BLOCK Session 3

**The smallest set. Each blocks a specific Election-Only behaviour. I recommend where evidence permits and decide nothing.**

### 4.1 Blocking

| # | Question | Blocks | Owner | Recommendation |
|---|---|---|---|---|
| **`BR-1.12`** | Does admission yield **`invited`** (approval then required) or **`active`** directly? | the **admission flow** | **PO** | *(none)* — schema, UI, tests and docs expect `invited`; **no production path writes it** |
| **`BR-1.13`** | Is suspension **one-actor** or **two-actor** (four-eyes)? | the **suspension flow** | **PO** | *(none)* — both exist; tests endorse both; the guide documents only the one-actor path |
| **`Q3`** | **Where does exercisability live**, given suspension must not mutate the entitlement? | **suspension enforcement** | **ARB** | *(no candidate selected)* — **the primary architectural blocker** |
| **`BR-1.1`/`1.2`** | What does **removal** mean, and is it reversible? | the **removal flow** | **PO** | **Option B** — retain the record; permanent to officers; administratively reversible |
| **`Q-E1`** | Must suspension **prevent credential issuance** and **invalidate** an existing credential? | **coherent suspension** | **PO + security** | **Decide explicitly** — today **neither** happens, so the system issues an instrument it will not honour |
| **`Q-E2`** | Must **suspended** be distinguishable from **already-voted** at the enforcing gate? | the **gate's design** | **PO** | **Yes** — the two have **different authors** (officer vs voter); a gate that cannot tell them apart cannot report or audit either |
| **`BR-1.8`** | Who may **restore**, and with how many actors? | the **restore flow** | **PO** | *(none)* — currently two actors to suspend, **one** to restore |

### 4.2 Defaultable — not blocking unless the ARB says otherwise

| # | Question | Safe default | Basis |
|---|---|---|---|
| **`BR-1.5`/`1.6`** | Mandatory reason; unconditional audit | **Both mandatory** | The organisation side already requires both (an empty reason **throws**); `ElectionAuditService` exists with `'voters'` as its **default category**, unused |
| **`BR-1.7`** | Suspension temporary or indefinite | **Indefinite until reversed** | No source specifies a duration; "indefinite until reversed" adds no unstated obligation |

### 4.3 Guardrails — no decision needed, but must not be violated

| # | Guardrail |
|---|---|
| **G-1** | **`scopeEligible()` must NOT be activated.** It would return **0 of 20** rows and deny every voter, and it enforces an organisation condition by query filtering — **a Full Membership mechanism, in a phase that has none** |
| **G-2** | **`status='inactive'` must not be vacated before its replacement enforces** — it is currently the **only** thing preventing a suspended voter from voting |
| **G-3** | **`ADR-T11`** — nothing may identify or mutate a cast ballot |
| **G-4** | **Mode-blindness** (§0) — Election-Only work must be scoped, or its extension to Full Membership recognised as a decision |

---

## 5 · Ownership and boundaries

| Stream | Now |
|---|---|
| **Session 1** | Master Matrix / independent verification — **not blocked, and not waiting on Full Membership** |
| **Session 2** | **This baseline. Full Membership questions FROZEN.** |
| **Session 3** | **Build Election-Only** — subject to §4.1 being answered |
| **ARB / PO** | §4.1 decisions · §3.2 amendment choice |

* **Nothing implemented.** No production code, test, fixture, schema or migration change. **No mechanism prescribed to Session 3** — §4 states *what must be decided*, never *how to build it*.
* **`ADR-002` not amended.** Constitution untouched. Officer Guide not promoted. `G-PUB` not investigated *(an independent ARB finding, unrelated to either mode)*.
* **Full Membership questions are FROZEN, not resolved and not discarded** — `FM-1`…`FM-15`, with their originating artifacts intact.
* **Session 1's Master Matrix not consumed, read as authority, or modified.** No row count cited.
* **Runtime residue untouched** — IERVP test data · working-organisation restoration · `voter_source_strategy` backfill · another author's `ElectionUser.php` / `PBDIGIT-70`.

**Traceability:** `app/Domain/Election/Enum/VoterSourceStrategy.php:10-60` · `app/Contexts/Elections/Domain/Policies/FullMembershipPolicy.php` *(stub)* · `app/Models/ElectionMembership.php:126-146,164-171,202-224` · `app/Models/User.php:315-328` · `app/Policies/ElectionPolicy.php:91-98` · `app/Http/Controllers/ElectionVoterController.php:171-190,196-216,222-241,290-356` · `app/Services/ElectionAuditService.php:28-46` · `app/Contexts/Membership/Domain/Membership/Events/*` *(no subscriber)* · `app/Domain/Election/Constitution/ElectionConstitution.php:126-146` · `docs/adr/ADR-002-verified-eligible-authorized.md:71,78,161,198-204` · `docs/adr/ADR-T-LOG-Tactical-Implementation.md:18` · measured this session: `members` **0 rows** · `scopeEligible()` **0/20** · **no path branches on `voter_source_strategy`** · live FKs on `election_memberships` = `users`(SET NULL), `elections`(CASCADE) only.

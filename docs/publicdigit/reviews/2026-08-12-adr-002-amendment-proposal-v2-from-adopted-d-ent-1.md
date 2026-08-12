# `ADR-002` amendment proposal **v2** — from the adopted `D-ENT-1` decision

**Type:** ADR amendment proposal · **Date:** 2026-08-12 · **Programme:** IERVP (Session 2)
**Status:** **`ADR-002` AMENDMENT PROPOSED — AWAITING ARB APPROVAL**
**⛔ `ADR-002` IS NOT AMENDED BY THIS DOCUMENT.** It remains **Accepted** in its current form.
**No production code, test, fixture, migration, schema or Constitution change. No database mechanism decided. Officer Guide not promoted. `G-PUB` not investigated. Session 1's Master Matrix not consumed or modified.**

**Supersedes** [`amendment proposal v1`](2026-08-12-adr-002-amendment-proposal.md) — v1 was written before the hierarchy refinement and **over-generalised** *"not re-derived from organisation membership"* from existence to exercisability. **v1 is superseded, not deleted.**

---

## 0 · The adopted business decision (authority for this proposal)

**FULL MEMBERSHIP MODE**
1. Organisation Membership is a **superior prerequisite** for ElectionMembership.
2. A person **must have active Organisation Membership** to be an ElectionMember in Full Membership Mode.
3. If Organisation Membership is **removed**, the ElectionMembership is **automatically suspended**.
4. This automatic suspension is **distinct from** Election Chief suspension.
5. The Election Chief may **independently** suspend an ElectionMember for an election-specific reason.
6. **Removing ElectionMembership does not remove Organisation Membership.**
7. Organisation Membership belongs to the **Organisation context**, governed by the Organisation Chief.
8. ElectionMembership belongs to the **Election context**, governed by the Election Chief, **subject to the Full Membership prerequisite**.

**ELECTION-ONLY MODE**
9. Organisation Membership is **not required**.
10. A person may become an ElectionMember **directly** for the specific Election.
11. **Organisation-membership removal therefore does NOT automatically suspend ElectionMembership in Election-Only Mode.**

**This proposal expresses only the above. It invents no business rule, and it derives no business meaning from the schema or the implementation.**

---

## 1 · Current `ADR-002` vs the adopted decision

| Existing clause | Verdict |
|---|---|
| The **three-axis orthogonality** — Verified ≠ Eligible ≠ Authorized | ✅ **REMAINS VALID** — the decision adds a concern, it does not merge the axes |
| Each axis's **responsible context**; *"changes are localized to their domain"* | ✅ **REMAINS VALID** — and clauses 7–8 strengthen it |
| **Authorization formula** `Verified && Eligible && Permission` | ⚠️ **REQUIRES AMENDMENT** — an entitlement term is missing |
| **Example 1** — verification expiry blocks voting until re-verified | ✅ **REMAINS VALID** — the Verified axis only |
| **Example 2** — fees unpaid ⇒ *"cannot vote in Election **B**"* | ✅ **REMAINS VALID** — it concerns **admission to a future election**, which clause 2 also gates. **Not a retention example** |
| *"Revocation of verification doesn't automatically block all future participation"* | ✅ **REMAINS VALID** |
| Rejection of a single conflated `can_vote` boolean | ✅ **REMAINS VALID**, and reinforced |
| **`:161`** `eligibility_status (computed from membership at check time)` | ⚠️ **REQUIRES AMENDMENT** — ambiguous between organisation and election membership; and it omits the entitlement/exercisability split |
| **`:78`** *"Check: Active membership? Paid fees? Full member type? Enrolled? Window open?"* | ⚠️ **REQUIRES AMENDMENT** — conflates admission-time and check-time conditions in one list |
| **`:71`** *"Enabled By: Process-specific rules (membership status, fees, timing, scope)"* | ⚠️ **REQUIRES AMENDMENT** — same conflation |
| **"Current Implementation Evidence"** section | 🔴 **OBSOLETE** — cites `Member voting_rights` and `EloquentVoterEligibilityQueryService`; **the live gate is `User::isVoterInElection()`.** *(Recording staleness is not amending the decision)* |

**Nothing else in `ADR-002` is affected. No clause becomes obsolete on the strength of the business decision alone — only the implementation-evidence section, which is obsolete on the facts.**

---

## 2 · The amendment proposal

### 2.1 Add axis 0 — **Entitled**

> **0. Entitled**
> **Definition:** the person has been **admitted** to a specific election, and that admission has not been **removed**.
> **Responsibility:** **Election context** (clause 8). **Scope:** election-specific.
> **Enabled by:** an admission decision.
> **Lifespan:** persists from admission until an election-level removal rule ends it.
> **Not re-derived from Organisation Membership.** *(Existence only — see 2.2, which governs exercisability.)*
> **Does not imply:** exercisability, credential possession, verification, or permission.

### 2.2 Add — **Exercisability and its causes**

> **Exercisability** is a **derived decision**: whether an existing entitlement may be exercised **now**. It is not a property authored directly.
>
> **An entitlement is non-exercisable while any of the following holds**, and each cause retains its own authority and audit identity:

| Cause | Authority | Mode | Effect on the entitlement's **existence** |
|---|---|---|---|
| **Organisation Membership removed** | **Organisation context** (clause 7) | **Full Membership only** (clauses 3, 11) | **none** — the entitlement is **suspended, not removed** |
| **Election Chief suspension** | **Election context** (clause 5) | **both** | **none** |
| **ElectionMembership removed** | **Election context** | **both** | exercisability withdrawn; **Organisation Membership is unaffected** (clause 6) |
| **Election lifecycle / credential / prior consumption** | Election lifecycle · credential · Voting | both | **none** |

> **The causes are independent and must remain distinguishable. A single "non-exercisable" flag that loses the cause would not satisfy this ADR.**

### 2.3 Amend the eligibility clauses

| Location | Proposed |
|---|---|
| **`:161`** | `entitlement` — established at admission; election-specific; persists until an election-level removal rule ends it · `exercisability` — derived **at check time** from the entitlement **plus its suspension causes (2.2)** and the election's own rules |
| **`:78`/`:71`** | **Split into two questions.** **Admission-time:** *may this person be admitted?* — in **Full Membership Mode**, requires **active Organisation Membership**, plus fees and membership type as the mode requires; in **Election-Only Mode**, no Organisation Membership is required. **Check-time:** *may this entitled person act now?* — entitlement exists · no suspension cause holds · window open · scope correct |

### 2.4 Amend the authorization formula

```
CanCastVote(actor, election) =
      Entitled(actor, election)          // admitted; not removed                [NEW]
   && Exercisable(actor, election)       // no suspension cause holds (2.2)      [NEW]
   && Verified(actor)                    // unchanged
   && Permission(actor, action, scope)   // unchanged
```

**Credential possession is deliberately not a term.** It is an authentication step preceding the act.

### 2.5 Wording explicitly avoided

> **NOT proposed:** *"An ElectionMember may exist only while the person is an Organisation Member."*
>
> **Rejected because it implies the record must be deleted.** The adopted decision says **suspended** (clause 3), and clause 6 shows the two memberships have independent lifetimes. **The proposed wording is: *"requires active Organisation Membership; if that Organisation Membership is removed, the ElectionMembership is automatically suspended."*** **This distinction is load-bearing for auditability.**

---

## 3 · Clause-by-clause rationale

| Clause | Where it lands | Rationale |
|---|---|---|
| **1** superior prerequisite | 2.3 admission-time | *"Superior"* is expressed as **prerequisite ordering**, not as override authority — the decision does not say the Organisation may act on the entitlement directly *(see `W-2`)* |
| **2** must have **active** Organisation Membership | 2.3 admission-time | *"Active"* is carried verbatim. **What breaks "active" other than removal is not stated — `W-5`** |
| **3** removal ⇒ **automatic suspension** | 2.2 row 1 | Recorded as a **cause of non-exercisability**, with **no effect on existence** |
| **4** distinct from Chief suspension | 2.2 (separate rows) | Two rows, two authorities. **The ADR would be violated by a representation that merges them** |
| **5** Chief may independently suspend | 2.2 row 2 | *"Independently"* — not conditioned on organisation state |
| **6** removing ElectionMembership ≠ removing Organisation Membership | 2.2 row 3 | Directionality stated explicitly; it is the only clause about propagation **upward**, and it is negative |
| **7** Organisation context / Organisation Chief | 2.2 authority column | Preserves `ADR-002`'s *"changes are localized to their domain"* |
| **8** Election context / Election Chief, subject to prerequisite | 2.1 responsibility | Explains why the entitlement is Election-owned yet admission-gated by the Organisation |
| **9** no Organisation Membership required | 2.3 admission-time | — |
| **10** direct admission | 2.3 admission-time | — |
| **11** no automatic suspension in Election-Only | 2.2 row 1 **mode column** | 🔑 **This ANSWERS `W-6`, which I had raised as open.** The mode column exists solely to carry clause 11 |

---

## 4 · Vocabulary impact

| Term | Impact |
|---|---|
| **`Entitled`** | New axis name. **Distinct from `ElectionMembership`**: the record is a fact, the axis is the decision that reads it *(`V-3`, still awaiting ARB acceptance)* |
| **`Exercisable`** | New derived decision, with **named causes** |
| **`revocation`** | ✅ **`V-2` IS RESOLVED BY THE NEW WORDING.** The 11 clauses use **"removed"** and **"suspended"** throughout and **never use *revocation***. The reserved trust-domain meaning (*withdrawal of identity-trust attestation, which does not block voting*) is therefore **not disturbed.** **The earlier corrected wording did say *"revocation/removal rule"*; this statement does not.** ⚠️ **`ADR-002` should still carry a disambiguation note**, because `ADR-001`/`ADR-003` use the term for the other concept |
| **`suspended`** | Now has **two causes** at voter level, plus the pre-existing **election-level** `suspend` (a governance hold on the whole election). **Three subjects share one verb.** **Naming remains a Phase 4 governance-language matter; this proposal introduces no new name** |
| **`removed`** | Used for **both** Organisation Membership removal and ElectionMembership removal — **different objects, same verb.** The proposal keeps them apart by naming the object every time |
| 🔴 **"Organisation Chief"** (clause 7) | **The term has no current referent.** Measured earlier this session: organisation-level roles are **`member`** and **`owner`** only; `chief` exists solely as an **`ElectionOfficer`** role. **Recorded as a vocabulary gap — I have not invented a mapping.** `W-8` |

---

## 5 · Consequences for existing architectural rules

| Rule | Consequence |
|---|---|
| `ADR-002` orthogonality | **Preserved**, extended by one axis |
| `ADR-001`/`ADR-003` revocation semantics | **Undisturbed** — the adopted wording avoids the term |
| `ADR-T11` (no voter↔vote linkage) | **Undisturbed** — no proposed mechanism reaches a cast ballot |
| `ElectionConstitution:126-128` — suspension *"freezes capabilities only… does NOT mutate business facts"* | **Now doubly relevant**: with two voter-level suspension causes, neither may mutate the entitlement's existence. **`Q3` must satisfy this** |
| `VoterSourceStrategy` sovereignty — *"org mutations don't retroactively change"* election rules | ⚠️ **Requires a reading.** It concerns the **rule set** (the snapshot), not individual entitlements. Clause 3 makes an organisation act affect an individual entitlement's **exercisability** — **consistent, because the election's *rules* are unchanged; only a person's standing changes.** **Recorded as an interpretation for ARB to confirm** |
| `VotingEligibilityPolicy` (declared authoritative, **0 callers**) | Its `ReasonCode`s already include `SUSPENDED`, `TERMINATED`, `NOT_ACTIVE`, `NO_MEMBERSHIP` — **strikingly close to 2.2's causes.** Whether it becomes the engine is **`AD-2`**, unchanged |
| Constitution | **Untouched.** Voter-level governance still has **no** constitutional action; the `C`-class gaps stand |

---

## 6 · Unresolved questions

| # | Question | Status |
|---|---|---|
| **`W-6`** | Does organisation-membership removal suspend in Election-Only mode? | ✅ **ANSWERED — clause 11: no** |
| **`V-2`** | *revocation* terminology | ✅ **RESOLVED by the new wording** *(disambiguation note still recommended)* |
| **`W-7`** | 🔴 **Which act IS "removal of Organisation Membership"** — deleting the organisation-role linkage, or terminating the `Member` aggregate? | **OPEN — still first.** Nothing can subscribe to, enforce or test clause 3 until the act is named |
| **`W-5`** | Clause 2 requires **active** Organisation Membership; clause 3 triggers on **removal**. **Does expiry (or organisation-side suspension) also trigger automatic suspension?** | **OPEN — sharpened by the new wording**, because "active" and "removed" are not complements |
| **`W-1`** | If Organisation Membership is **restored**, does the automatic suspension lift automatically? | **OPEN** |
| **`W-2`** | May the Election Chief restore a voter suspended by an organisation cause? *(Does "superior" mean hierarchy or override?)* | **OPEN** |
| **`W-3`** | If both causes hold, does clearing one restore exercisability? | **OPEN** |
| **`W-4`** | Does automatic suspension apply to a voter who has already voted? | **OPEN** *(`ADR-T11` bounds the answer)* |
| **`W-8`** | 🆕 **"Organisation Chief" has no current referent** — which organisation role holds this authority? | **OPEN** |
| **`V-1`/`BR-1.1`** | The removal rule the `Entitled` lifespan cites is **still unratified** | **OPEN** |
| **`V-3`** | Record-vs-decision clarification | **OPEN — recommended for acceptance** |
| **`Q3`** | How exercisability is represented | **OPEN — deliberately untouched here** |

## 7 · Conformance implications — **FINDINGS ONLY, no solutions**

**Offered so ARB can judge the distance between the rule and the system. None is a repair proposal, and no mechanism is chosen.**

| # | Finding |
|---|---|
| 1 | **No organisation→election trigger exists at any layer** — verified: no event subscription, and **no foreign key** to `user_organisation_roles` in the live schema. **So clause 3 is currently unenforced** |
| 2 | The organisation side **already emits** `MembershipSuspended` / `MembershipTerminated` / `MembershipRestored`; **nothing subscribes.** The Published Language clause 3 needs **exists on the publishing side** |
| 3 | **Clause 2 is unenforced at admission** — `FullMembershipPolicy::isEligible()` is a **stub returning `true`** |
| 4 | **`members` has 0 rows** — Full Membership has never been exercised, so clauses 1–8 have an **empty domain of application** today |
| 5 | The declared FK asserting *"user is actually a member of this organisation"* is **absent from the live schema** — that guarantee is **not enforced** |
| 6 | Suspension currently writes `status='inactive'` — **the entitlement's own field**, contrary to the capability-freeze principle |
| 7 | **The two suspension causes could not be distinguished today** — one `status` value, and `suspension_status` is not read by the voting path |
| 8 | Chief suspension is enforced **incidentally**; a suspended voter is still **issued a credential** |
| 9 | **No test asserts that a suspended voter cannot vote**; none exercises clause 3 at all |
| 10 | `scopeEligible()` enforces an organisation-membership condition **by query filtering** — directionally aligned with clause 2 but **the wrong mechanism** for clause 3, and it would return **0/20** |

## 8 · Required ARB approval points

| # | Approval point |
|---|---|
| **A-1** | Adopt **axis 0 `Entitled`** (2.1) |
| **A-2** | Adopt **Exercisability with named, authority-bearing causes** (2.2) — including that **merging the causes would violate the ADR** |
| **A-3** | Adopt the **admission-time / check-time split** (2.3) |
| **A-4** | Adopt the **amended formula** (2.4) |
| **A-5** | Accept the **`V-3`** record-vs-decision clarification |
| **A-6** | Accept the ***revocation* disambiguation note**, given `ADR-001`/`ADR-003` |
| **A-7** | Mark the **"Current Implementation Evidence"** section **obsolete** |
| **A-8** | Confirm the **`VoterSourceStrategy` sovereignty reading** in §5 |
| **A-9** | Note that **`W-7`, `W-5`, `W-8`** remain open, and decide whether the amendment may be applied with them open |

> **`A-9` is the one I would flag hardest.** The amendment can be applied with `W-1`…`W-4` open — they refine behaviour. **But `W-7` decides which real-world act clause 3 responds to**, and `W-5` decides whether *"active"* or *"removed"* is the trigger. **An ADR that states a rule whose triggering act is unnamed is authoritative in form and inert in substance.**

---

## Boundaries

* **`ADR-002` NOT amended.** Constitution untouched. **No database mechanism decided.** No production code, test, fixture, migration or schema change. Officer Guide not promoted. `G-PUB` not investigated.
* **No business rule invented**, and **no business meaning inferred from schema or implementation** — §7 is segregated as findings, and §5's sovereignty point is labelled an interpretation for ARB.
* **The three transitions and the two modes are preserved throughout.**
* **Session 1's Master Matrix not consumed, read as authority, or modified.**
* ⚠️ **A prior commit is pending** — the `D-ENT-2` reconciliation artifacts are written to disk but uncommitted, due to a tooling outage during the previous turn.

**Traceability:** `docs/adr/ADR-002-verified-eligible-authorized.md:71,78,161,198-204,208-227` · `docs/adr/ADR-001-trust-attestation-domain.md:40,73-85` · `docs/adr/ADR-003-governance-driven-revocation.md:62-71` · `docs/adr/ADR-T-LOG-Tactical-Implementation.md:18` · `docs/architecture/trust-domain/UBIQUITOUS_LANGUAGE.md:74-110,222-270` · `app/Domain/Election/Constitution/ElectionConstitution.php:126-146` · `app/Domain/Election/Enum/VoterSourceStrategy.php:10-60` · `app/Contexts/Membership/Domain/Membership/Events/{MembershipSuspended,MembershipTerminated,MembershipRestored}.php` · `app/Contexts/Membership/Domain/Voting/VotingEligibilityPolicy.php` · `app/Contexts/Elections/Domain/Policies/FullMembershipPolicy.php` · `app/Models/ElectionMembership.php:126-146,164-171,202-224` · `app/Models/User.php:315-328` · measured this session: live FKs on `election_memberships` = `users`(SET NULL), `elections`(CASCADE) only · `user_organisation_roles.role` ∈ {`member`,`owner`} · `members` 0 rows.

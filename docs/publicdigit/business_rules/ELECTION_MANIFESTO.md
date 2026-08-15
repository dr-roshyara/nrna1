# Election Manifesto — canonical Election business rules

**Type:** Canonical business-rule artifact · **Created:** 2026-08-12 · **Prepared by:** Session 2 (governance canonicalization)
**Status:** **BUSINESS-RULE CATALOGUE — PROPOSED canonical home; NOT YET RATIFIED (`EM-OPEN-017`)** *(the ARB ruled 2026-08-12: useful, non-redundant, deliberately unratified — an earlier header here said "ACTIVE — canonical home", which contradicted `EM-OPEN-017` and is corrected)*
**Placement:** derived — `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0, ruled); `business_rules/` is the existing sub-root for business rules in that root.

> ## What this document is, and is not
>
> **This artifact contains ONLY business rules that the Product Owner / ARB has explicitly adopted.** Every rule carries a stable ID and a traceable prior authority.
>
> **Nothing here was invented, reinterpreted or extended during canonicalization.** Where a rule's wording was corrected by the Product Owner, the corrected wording is used and the correction is noted.
>
> **This is a canonicalization of existing decisions — not a new decision.**

### Authority relationships

| Artifact | Purpose |
|---|---|
| **Election Constitution** (`ElectionConstitution` + accepted ADRs) | **canonical implementation home for constitutional election WORKFLOW rules** *(established — ADR-001)* |
| **Election Manifesto** *(this document)* | **catalogue of adopted Election BUSINESS rules** *(proposed canonical home — unratified, `EM-OPEN-017`)* |
| **ADRs** | architectural decisions |
| **Review reports** | investigation evidence and historical reasoning — **not authority** |
| Implementation code | implements rules; **never their canonical home** |
| Verification artifacts | evidence of whether rules hold |

**Rules that already have a canonical home are REFERENCED here, never copied** — see §7. Duplication is the failure mode this artifact exists to end.

### Manifesto ≠ Constitution — standing separation (Principal Architect ruling, 2026-08-13)

> **Manifesto = the governed home for adopted business rules and invariants, when ratified by ARB** *(ratification is `EM-OPEN-017`, open — corrected 2026-08-13 from "adopted business rules and invariants", which let the document declare its own authority through its definitions)*.
> **`ElectionConstitution` = existing canonical implementation home for constitutional election workflow rules** *(established by ADR-001; valuable — to be extended carefully, never redesigned)*.
> **Manifesto rules may be enforced through the Constitution where appropriate, but the Manifesto must not duplicate the Constitution's implementation structure.**
> **Entitlement, ElectionMembership, suspension, credentials and voter-participation rules must remain OUTSIDE `ElectionConstitution` unless a future architectural decision explicitly establishes otherwise.**

**A rule expressed at both levels is NOT duplication — it is the same rule at two architectural levels**, and the traceability between them is what makes the system auditable:

```
ELECTION MANIFESTO            EM-VOT-002  "voting requires an approved candidate"   (business language)
        │
        ▼
ELECTION CONSTITUTION         open_voting → has_approved_candidates                 (constitutional enforcement)
        │
        ▼
CONSTITUTIONAL GUARD          the enforcement mechanism                             (application)
        │
        ▼
TEST                          zero approved candidates → open_voting rejected       (verification)
```

**Each artifact has one job. Not every Constitution entry needs a Manifesto rule** — workflow mechanics can be purely constitutional — **and no Manifesto rule prescribes implementation.**

### The direction of authority — a standing clause

> **The implementation must conform to the applicable Manifesto rules. It must never become the source from which the Manifesto is derived.**
> *(Product Owner, 2026-08-12.)*

**Corollary, and the question every session should ask:**

| Session | The question | **NOT** the question |
|---|---|---|
| **Session 3** *(implementation)* | *"What does the Manifesto require, and does the implementation satisfy it?"* | ❌ *"What does the implementation do, and shall I record that as a rule?"* |
| **Session 1** *(verification)* | *"What does the test estate actually verify against the Manifesto?"* | ❌ *"What do the tests assert, and is that therefore the rule?"* |
| **Session 2** *(governance)* | *"What has been adopted, and where is it recorded?"* | ❌ *"What does the code imply the rule must be?"* |

### Two independent status dimensions — and only one of them lives here

**A rule's GOVERNANCE status and its CONFORMANCE status are different things, and conflating them was the error this clause prevents.**

| Dimension | Values | **Owner** | Recorded in this Manifesto? |
|---|---|---|---|
| **Governance status** | `ADOPTED` · `DEFERRED` · `OPEN` | **Product Owner / ARB**, recorded by Session 2 | ✅ **Yes — §1–§9** |
| **Conformance status** | *implemented* · *partially implemented* · *not implemented* · *verified* | **Session 3** *(implementation)* and **Session 1** *(verification)* | ❌ **No — deliberately** |

**Why conformance status is deliberately absent:**

1. **A specification that tracks code becomes derived from code.** Per-rule implementation status would have to be updated on every change, and the artifact would drift toward describing the system instead of governing it — **the exact direction the standing clause forbids.**
2. **Session 2 is not the authority on it.** *Implemented* is Session 3's determination; *verified* is Session 1's. **Stamping either here would be one session performing another's job.**
3. **`ADOPTED` already carries what implementers need:** the rule is in force. **A rule does not become less authoritative for being unimplemented** — an adopted-but-unimplemented rule is a **conformance gap**, not a weaker rule.

> **So: an adopted rule may be fully implemented, partly implemented, or not implemented at all, and its authority is identical in each case.** Where a rule must **not yet** be implemented, that is stated as **`DEFERRED`** — a governance status, which *is* Session 2's to set.
>
> **`VERIFIED` ≠ `IMPLEMENTED` ≠ `CONFORMANT` ≠ `TESTED`.** These are distinct determinations belonging to Sessions 1 and 3; **this Manifesto asserts none of them about any rule.**

---

## 1 · Adopted rules — entitlement (both modes)

| ID | Rule | Status |
|---|---|---|
| **EM-ENT-001** | **`ElectionMembership` is the election-specific entitlement record.** | ADOPTED |
| **EM-ENT-002** | **The existence of the entitlement is distinct from whether it is currently exercisable.** | ADOPTED |
| **EM-ENT-003** | **Admission creates an election-specific entitlement. That entitlement remains associated with the election unless a defined election-level removal rule terminates it.** | ADOPTED ⚠️ **the termination rule it refers to is NOT YET RATIFIED** — see `EM-OPEN-004` |
| **EM-ENT-004** | **Changes to a person's Organisation Membership do not automatically destroy the election entitlement.** | ADOPTED |
| **EM-ENT-005** | **There is one entitlement concept across both organisation modes. Only the route to admission differs.** | ADOPTED |
| **EM-ENT-006** | **Removing an `ElectionMembership` never removes Organisation Membership.** | ADOPTED |
| **EM-ENT-007** | **`ElectionMembership` belongs to the Election context and is governed by the Election Chief, subject to the mode's admission prerequisite.** | ADOPTED |

## 2 · Adopted rules — Election-Only mode

| ID | Rule | Status |
|---|---|---|
| **EM-EO-001** | **In Election-Only Mode, Organisation Membership is not required.** | ADOPTED |
| **EM-EO-002** | **In Election-Only Mode, a person may become an ElectionMember directly for a specific election.** | ADOPTED |
| **EM-EO-003** | **Election-Only admission does not create Organisation Membership.** | ADOPTED |
| **EM-EO-004** | **In Election-Only Mode, removal of Organisation Membership does not automatically suspend the `ElectionMembership`.** | ADOPTED |

## 3 · Adopted rules — election governance (both modes)

| ID | Rule | Status |
|---|---|---|
| **EM-GOV-001** | **The election holds its own governance over whether an entitlement may currently be exercised.** | ADOPTED |
| **EM-GOV-002** | **The Election Chief may independently suspend an ElectionMember for an election-specific reason.** | ADOPTED |
| **EM-GOV-003** | **Election Chief suspension and organisation-driven suspension are distinct governance acts and must not be treated as one rule.** | ADOPTED |
| **EM-GOV-004** | **The Chief Election Officer may correct an election's schedule under governed conditions, including a window whose time has already elapsed, where necessary for the election to continue legitimately.** A schedule correction **never by itself advances the election · never bypasses a mandatory condition for progression · is attributable to the officer who made it · carries a stated reason · is auditable.** | ADOPTED *(principle only — PO/ARB authorization, **2026-08-15**)* |

| **EM-GOV-005** | **Every material event in an election's progression is recorded in the election protocol** — including each attempt to proceed, each refusal and its reason, and each termination, cancellation, expiry or supersession of a voting opportunity — **and the original history is preserved.** A later event never rewrites or erases an earlier one. | ADOPTED *(PO clarification, **2026-08-15**)* |

**`EM-GOV-005`** is what makes the distinctions of `EM-VOC-004` durable: outcomes that are distinguishable in the moment but not recorded become indistinguishable afterwards. **What must be recorded is adopted; the form of the record is not decided here.**

**`EM-GOV-004` boundaries are deliberately NOT adopted and require separate business decisions:** moving a window forward · extending a window · correcting a window already elapsed · moving a window backward · the effect on voting credentials already issued · permissibility once votes have been cast. **An implementation that assumed any of these would be inventing policy.** **Unchanged by the `EM-OPEN-022` ruling (2026-08-15):** that ruling settled **the identity of the opportunity after a correction**, not **the extent of the correction authority**. **Every boundary in this list remains open.**

## 4 · Adopted vocabulary

| ID | Rule | Status |
|---|---|---|
| **EM-VOC-001** | **"Organisation Membership" means the `Member` aggregate** — the organisation-side membership concept that carries membership identity, type, fees and term. | ADOPTED |
| **EM-VOC-002** | **A technical organisation association or role assignment is not Organisation Membership**, whatever its name or role value. | ADOPTED |
| **EM-VOC-003** | **`ElectionMember` and `Organisation Member` are different concepts. `ElectionMembership` does not imply Organisation Membership.** | ADOPTED |
| **EM-VOC-004** | **A published voting schedule defines a specific *voting opportunity*.** A voting opportunity is **not reusable** once it has been terminated or has expired. A voting opportunity may end in one of several ways, and **these outcomes must remain distinguishable because they carry different business meanings**: **voting started** · **temporarily unable to proceed while the opportunity is still valid** · **expired unused** · **explicitly cancelled** · **superseded by a later governed schedule**. | ADOPTED *(PO clarification, **2026-08-15**)* |

**Why the outcomes must stay distinguishable:** an election that never voted because nobody was ready is not the same as one that was cancelled, nor one whose schedule was replaced, nor one that was merely waiting and still could have proceeded. **Collapsing them into a single "did not vote" would destroy information the election's own record must carry.** *(How they are represented is not decided here.)*

| **EM-VOC-005** | **A correction to a published voting schedule creates a NEW voting opportunity.** The previous opportunity becomes **superseded** and remains permanently recorded in the election protocol. The new opportunity has its own schedule and **must receive its own progression decision**. **A schedule correction does not itself start voting, does not extend an existing authorization, and does not bypass a mandatory Election Rule.** | ADOPTED *(PO/ARB ruling — `EM-OPEN-022` = **Reading A**, **2026-08-15**)* |

**Stated purpose, adopted with the rule:** *"to prevent schedule manipulation from becoming a means of circumventing an earlier progression refusal."* The forbidden path — **refused → change the date → reuse the authorization → voting** — **must be impossible by business rule, not merely by a technical check.**

**Consequence for `EM-VOT-005` (SCOPE EXTENDED by this ruling, 2026-08-15):** the adopted text spoke of *authorization*; the ruling extends the non-carry-over to **every progression decision** — **no authorization, refusal, eligibility assessment, or other progression decision associated with the previous opportunity carries over to the new one.** The Chief must decide again, and the Election Rules must independently permit that decision.

**No "minor vs material" threshold exists — this was considered and deliberately rejected.** The PO examined the harmless-correction objection (`10:00 → 10:05`) and ruled the clean line anyway: *"a published schedule is an immutable historical commitment; changing it creates a new opportunity."* Reason recorded verbatim in substance: an exception would force **Architecture** to decide which changes are minor and which are material (`10:00→10:05` · `10:00→11:00` · `20 Aug→21 Aug` · `10:00–12:00→10:00–14:00`), **creating a second policy surface the Chief could exploit.** ⛔ **Architecture and Implementation must not reintroduce a significance threshold, tolerance window, or "no-op correction" shortcut.**

**The differentiated rule Governance surfaced (unopened window / open window / expired window treated differently) was considered and NOT adopted** — the uniform rule was chosen deliberately, with the boundary problem as the stated reason. It is recorded as a rejected alternative so it is not silently reinvented.

**Two separate acts, never one:** *schedule correction* → **a new voting opportunity comes into being** → *the Chief requests progression* → *the Election Rules evaluate* → *voting starts or is refused*. **Correction ≠ authorization to vote. Correction ≠ continuation of the old opportunity.**

**What this ruling does NOT decide** *(unchanged, still open)*: **the limits of the Chief's schedule-correction authority itself** — the ruling states only that it *"may be exercised only within the limits separately established by Governance"*, and the `EM-GOV-004` boundary list below stands open in full · when an opportunity expires · who may cancel one · correction while an opportunity is active · correction after votes are cast · credential consequences · technical representation.

## 4a · Adopted rules — voting phase

| ID | Rule | Status |
|---|---|---|
| **EM-VOT-001** | **Without a candidate, an election must not proceed to the next phase. Voting must not begin unless the election has at least one valid (approved) candidate.** | ADOPTED *(Product Owner, **2026-08-08**)* |

| **EM-VOT-002** | **An election must have at least one approved candidate before voting may be opened.** *(`open_voting` must not succeed with zero approved candidates.)* | ADOPTED *(ARB/PO ruling **`SD-14` = YES**, 2026-08-13)* |

| **EM-VOT-003** | **An election may enter Voting Active only when it has at least one approved candidate and at least one admitted voter.** | ADOPTED *(PO ruling, verbatim, **2026-08-13**)* |

**Note on `EM-VOT-003`:** the candidate half restates `EM-VOT-002`'s requirement at the same boundary (same rule at two granularities, not duplication — see §9); the **voter half is new**: no earlier adopted rule required ≥ 1 admitted voter at voting start. Evidence at adoption: `has_voters` exists as a `complete_administration` precondition only (an upstream gate, not a boundary invariant), and **neither path into `voting_active` checks voters** (guard preconditions and computed derivation both candidate-and-window-only). Per the `EM-VOT-002` precedent, **`ElectionConstitution` is the identified authoritative expression home**, with the both-paths lesson applying symmetrically. **Implementation is NOT authorised by this adoption** — the ruling closes the business question only. This rule does **not** resolve `EM-OPEN-021`, and the zero-voter analog question remains open with no semantics chosen.

| **EM-VOT-004** | **An election does not enter its voting phase by the passage of time.** Reaching the scheduled voting start time — or a valid schedule correction — makes the election **eligible for the Chief Election Officer's consideration**; it confers no authority. **The Chief Election Officer must explicitly decide to proceed**, and progression is valid only if, at that moment, every mandatory condition holds: at least one approved candidate · at least one admitted voter · nomination completed · no other established constitutional prohibition. If any is unmet, **voting does not begin**, the election remains in its current valid non-voting condition, the officer is told which condition is unmet, may correct it where authorized, and may decide to proceed again. | ADOPTED *(PO/ARB authorization, **2026-08-15**)* |

**The governing principle, verbatim:** ***"Time makes progression possible; authority and eligibility make progression valid."*** and ***"The Chief decides when to request progression; the Election Rules decide whether progression is permitted."***

**The authority boundary — authorized verbatim, and the central constitutional boundary Architecture must preserve:**

> **No clock-driven lifecycle mechanism may exercise the Chief Election Officer's authority.**
> **No Chief Election Officer action may override a mandatory Election condition.**

The Chief **must not be able to bypass, suppress, or override a refusal through administrative authority or schedule manipulation.**

**Notes on `EM-VOT-004`, recorded so nothing is mistaken for something it is not:**
1. **This is a NEW rule, not a restoration.** Architecture evidence (2026-08-15 semantic reconciliation) established that **no generation of this system ever required the Chief's act for an election to become voting-active** — the voting boundary has been clock-driven since the first generation.
2. **"Nomination completed" is NEW as a condition at this boundary** — it exists today only as an input to the automatic derivation, never as a stated requirement of opening voting.
3. **Scope: the START of voting only.** It must **not** be generalized into *"progression is always manual"*. **Automatic closing at the valid end of the voting period is unchanged**; any separately governed early-closure authority is a distinct act. Every other phase boundary remains undecided.
4. **The historical technical name `voting_blocked` is NOT adopted as business vocabulary.** Three business situations must be distinguishable — *not yet eligible* · *eligible, awaiting the officer's decision* · *cannot proceed, with the unmet condition named*. **Their representation is Architecture's to determine.**
5. **`ElectionConstitution` is the identified expression home; implementation is NOT authorized by this adoption.** Conditions are evaluated **at the moment the Chief requests progression**, on **every** path into voting (the `EM-VOT-002` both-paths lesson applies).
6. **This rule does NOT resolve `EM-OPEN-021`** — the zero-candidate configuration is also reachable without the clock (`forceCloseNomination()`), so that question survives and remains open.

| **EM-VOT-005** | **Authorization to proceed belongs to one voting opportunity and never to another.** If a later voting opportunity comes into being, it is a **new opportunity** and requires a **new Chief Election Officer authorization**. **An authorization given for a previous opportunity must never authorize a later one.** | ADOPTED *(PO clarification, **2026-08-15**)* |

**`EM-VOT-005` completes the anti-circumvention intent of `EM-VOT-004`.** Without it, an officer refused on one opportunity could obtain a later one and rely on the earlier decision — reaching by two steps what neither step permits. **Authorization is spent on the opportunity it was given for.** **Scope extended 2026-08-15 by the `EM-OPEN-022` ruling: not only authorization but *every* progression decision — refusal and eligibility assessment included — is spent on its own opportunity (see `EM-VOC-005`).**

**Note on canonical overlap:** the Constitution **partially** expresses `EM-VOT-001`, as the `has_approved_candidates` precondition on the nomination transition; the precondition itself remains canonical in the Constitution and is not restated. **`SD-14` = YES (ARB/PO, 2026-08-13) resolved the boundary question:** *"the next phase"* **includes the voting phase**, so the requirement binds at `open_voting` as well — adopted as **`EM-VOT-002`**, deliberately phrased with the Constitution's own precise vocabulary (*"at least one **approved** candidate"*) so business rule and implementation vocabulary stay aligned. **`ElectionConstitution` is the authoritative implementation home for this precondition. Implementation is NOT yet authorised** — the ruling closes the business question only; implementation authorization is a separate act.

## 5 · Adopted sequencing

| ID | Rule | Status |
|---|---|---|
| **EM-SEQ-001** | **Election-Only Mode is the first implementation phase. Full Membership Mode is deferred to a separate later phase.** | ADOPTED |
| **EM-SEQ-002** | **Full Membership rules must not be inferred from, or established as a side effect of, Election-Only implementation.** | ADOPTED |

## 6 · Adopted rules whose SCOPE is Full Membership — **DEFERRED, not in force for the current phase**

**Recorded here because they are adopted. They are NOT Election-Only rules and must not be implemented in the current phase (`EM-SEQ-001`, `EM-SEQ-002`).**

| ID | Rule | Status |
|---|---|---|
| **EM-FM-001** | In Full Membership Mode, Organisation Membership is a **superior prerequisite** for `ElectionMembership`. | ADOPTED · **DEFERRED** |
| **EM-FM-002** | In Full Membership Mode, a person **must have active Organisation Membership** to be an ElectionMember. | ADOPTED · **DEFERRED** |
| **EM-FM-003** | In Full Membership Mode, **removal of Organisation Membership automatically suspends** the `ElectionMembership`. | ADOPTED · **DEFERRED** |
| **EM-FM-004** | That automatic suspension is **distinct from** Election Chief suspension. | ADOPTED · **DEFERRED** |
| **EM-FM-005** | Organisation Membership belongs to the **Organisation context**, governed by the Organisation Chief. | ADOPTED · **DEFERRED** ⚠️ *"Organisation Chief" has no established referent — see `EM-OPEN-014`* |
| **EM-FM-006** | In Full Membership Mode, Organisation Membership is an **admission prerequisite** — **not** continuously required for the entitlement to **exist**; it **is** continuously relevant to whether the entitlement is **exercisable** (`EM-FM-003`). | ADOPTED · **DEFERRED** |

## 7 · Referenced authority — canonical elsewhere, deliberately NOT duplicated here

**These bind Election behaviour and already have a canonical home. They are cited, not copied.**

| Referenced rule | Canonical home |
|---|---|
| **No voter↔vote linkage** in any aggregate, event payload or projection | **`ADR-T11`** *(constitutional, build-breaking)* |
| **Election lifecycle states, transitions, allowed roles and preconditions** — all transitions defined there **and nowhere else** | **`ElectionConstitution`** |
| **Only committees (chief, deputy) may administer elections** | **`ElectionConstitution`** |
| **Only the chief may open voting or publish results** | **`ElectionConstitution`** |
| **The approval workflow** — `draft → submitted → approved/rejected → setup` | **`ElectionConstitution`** |
| **Capacity-based approval** — a free plan auto-approves below a voter threshold; above it, manual review / payment authorisation is required ⚠️ **the threshold itself is disputed — see `EM-OPEN-019`** | **`ElectionConstitution`** |
| **Every action carries preconditions that must be verified** | **`ElectionConstitution`** |
| **Suspension freezes capabilities only; it does not mutate business facts** | **`ElectionConstitution`** *(architectural principle)* |
| **Verified ≠ Eligible ≠ Authorized** | **`ADR-002`** *(Accepted; a v2 amendment is proposed and NOT applied)* |
| **Revocation** = withdrawal of **identity-trust attestation**, and does not itself block voting | **`ADR-001`, `ADR-003`** |
| Consequences of a withdrawal are **governance decisions**; past votes are not automatically invalidated | **`ADR-003`** |
| The election's voter-participation rule set is **snapshotted and immutable** after creation; organisation mutations do not retroactively change it | **`VoterSourceStrategy`** |

---

## 8 · Traceability

| Manifesto ID | Prior authority | Adopted | Scope | Status |
|---|---|---|---|---|
| EM-ENT-001 | `D-ENT-1` / Model B; `A-1` | 2026-08-12 | both modes | ADOPTED |
| EM-ENT-002 | `A-2`; `D-ENT-1` corrected wording | 2026-08-12 | both modes | ADOPTED |
| EM-ENT-003 | `A-5`; `D-ENT-1` corrected wording | 2026-08-12 | both modes | ADOPTED *(forward reference to an unratified rule)* |
| EM-ENT-004 | `A-3` | 2026-08-12 | both modes | ADOPTED |
| EM-ENT-005 | `A-6` | 2026-08-12 | both modes | ADOPTED |
| EM-ENT-006 | hierarchy clause 6 | 2026-08-12 | both modes | ADOPTED |
| EM-ENT-007 | hierarchy clause 8 | 2026-08-12 | both modes | ADOPTED |
| EM-EO-001 | hierarchy clause 9 | 2026-08-12 | Election-Only | ADOPTED |
| EM-EO-002 | hierarchy clause 10 | 2026-08-12 | Election-Only | ADOPTED |
| EM-EO-003 | `Q-B1` closure | 2026-08-12 | Election-Only | ADOPTED |
| EM-EO-004 | hierarchy clause 11 | 2026-08-12 | Election-Only | ADOPTED |
| EM-GOV-001 | `A-4` | 2026-08-12 | both modes | ADOPTED |
| EM-GOV-002 | hierarchy clause 5 | 2026-08-12 | both modes | ADOPTED |
| EM-GOV-003 | hierarchy clause 4 *(as a conceptual distinction)* | 2026-08-12 | both modes | ADOPTED |
| EM-VOC-001 | `Q-B1` closure | 2026-08-12 | global | ADOPTED |
| EM-VOC-002 | `Q-B1` closure | 2026-08-12 | global | ADOPTED |
| EM-VOC-003 | admission-gate adopted rules 1, 2, 5 | 2026-08-12 | global | ADOPTED |
| EM-VOT-001 | `PBDIGIT-64` — *"Without a candidate an election must not go into the next phase"*, stated by the Product Owner | **2026-08-08** | election lifecycle | ADOPTED |
| EM-VOT-002 | **`SD-14` = YES** ruling — *"next phase"* includes voting; vocabulary aligned to the Constitution's `has_approved_candidates` | **2026-08-13** | `open_voting` boundary | ADOPTED |
| EM-VOC-005 | PO/ARB ruling on `EM-OPEN-022` (Reading A): schedule correction creates a NEW voting opportunity; previous superseded and permanently recorded; no minor/material threshold | **2026-08-15** | voting opportunity identity | ADOPTED |
| EM-VOC-004 | PO clarification: a published schedule defines a voting opportunity; not reusable; five outcomes must stay distinguishable | **2026-08-15** | voting schedule | ADOPTED |
| EM-VOT-005 | same clarification: authorization is opportunity-bound and never carries forward | **2026-08-15** | start-of-voting authority | ADOPTED |
| EM-VOT-005 *(scope extended)* | `EM-OPEN-022` ruling: non-carry-over covers **every** progression decision — authorization, refusal, eligibility assessment | **2026-08-15** | start-of-voting authority | ADOPTED |
| EM-GOV-005 | same clarification: material events recorded in the election protocol, original history preserved | **2026-08-15** | election protocol | ADOPTED |
| EM-VOT-004 | PO/ARB authorization adopting the Governance Decision Report — Start of Voting: Progression Authority (2026-08-15); new rule, not a restoration | **2026-08-15** | start-of-voting boundary, all paths | ADOPTED |
| EM-GOV-004 | same authorization — schedule-correction principle; boundaries left open | **2026-08-15** | election schedule | ADOPTED *(principle)* |
| EM-VOT-003 | PO ruling, verbatim: *"Adopted: an election may enter Voting Active only when it has at least one approved candidate and at least one admitted voter"* — recorded per the decision package §5b/§5c determination (voter half new; expression home identified; implementation not authorised) | **2026-08-13** | `voting_active` boundary, both paths | ADOPTED |
| EM-SEQ-001 | Election-Only-first sequencing decision | 2026-08-12 | programme | ADOPTED |
| EM-SEQ-002 | Election-Only-first sequencing decision | 2026-08-12 | programme | ADOPTED |
| EM-FM-001…005 | hierarchy clauses 1, 2, 3, 4, 7 | 2026-08-12 | Full Membership | ADOPTED · DEFERRED |
| EM-FM-006 | `Q-A0` = `F1`, as refined by `D-ENT-2` | 2026-08-12 | Full Membership | ADOPTED · DEFERRED |

**Adoption source for every row: explicit Product Owner decision, recorded in `PBDIGIT-68` and its linked governance artifacts.** Those artifacts remain the **historical record of how each decision was reached**; **this Manifesto is now the authority for what the rules are.**

---

## 9 · Governance section — OPEN and DEFERRED items

> **⛔ Nothing in this section is a business rule. None may be implemented, and none may be resolved by implementation.**

| ID | Open question | Blocks |
|---|---|---|
| **EM-OPEN-001** | **`BR-1.12`** — after Election-Only admission, is the `ElectionMembership` immediately **`ACTIVE`**, or initially **`INVITED`** requiring explicit Election Chief approval? | 🔴 **the Election-Only ADMISSION slice** |
| **EM-OPEN-002** | **`BR-1.13`** — is Election Chief suspension a one-actor or two-actor act? | the suspension slice |
| **EM-OPEN-003** | **`Q3`** — what concept represents current exercisability, and where does a suspension decision live? | changes to non-exercisability |
| **EM-OPEN-004** | **`BR-1.1`/`BR-1.2`** — what does removal mean, and is it reversible? *(`EM-ENT-003` forward-references this)* | the removal slice |
| **EM-OPEN-005** | **`BR-1.8`** — who may restore, and with how many actors? | the restore slice |
| **EM-OPEN-006** | **`Q-E1`** — must suspension prevent credential issuance and invalidate an existing credential? | coherence |
| **EM-OPEN-007** | **`Q-E2`** — must a suspended member be distinguishable from one who has voted, at the enforcing gate? | follows `Q3` |
| **EM-OPEN-008** | **`BR-1.5`/`BR-1.6`** — is a reason mandatory; must every act be audited unconditionally? | defaultable |
| **EM-OPEN-009** | **`BR-1.7`** — is suspension temporary or indefinite? | defaultable |
| **EM-OPEN-010** | **`Q-D1`** — disposition of the dormant organisation-membership eligibility query | — |
| **EM-OPEN-011** | **`D-APPLY`** (+`V-1`, `V-2`, `V-3`) — apply the proposed `ADR-002` amendment? | — |
| **EM-OPEN-012** | **`G-REC`** — section-level recognition of the Officer Guide *(assessed `D` — mixed authority; not promoted)* | — |
| **EM-OPEN-013** | **`G-PUB`** — may a published result be unpublished? *(no constitutional `unpublish` action exists)* ⚠️ **ANSWERED-BY-EXISTING-AUTHORITY CANDIDATE (2026-08-13):** a **PO domain ruling recorded in `PBDIGIT-60` (2026-08-06)** predates this question and substantially answers it — *publication is an immutable constitutional fact; visibility is a separate toggleable control (hide/show, never "unpublish")*. **Awaiting one-line PO confirmation that the ruling disposes of this item; not closed by Session 2** | — |
| **EM-OPEN-014** | **`W-8`** — which organisation role is the "Organisation Chief" of `EM-FM-005`? | Full Membership |
| **EM-OPEN-015** | **`W-1`…`W-7`** — Full Membership trigger, restoration, expiry-vs-removal, override and post-vote questions | Full Membership |
| **EM-OPEN-016** | **`FM-1`…`FM-15`** — the frozen Full Membership register | Full Membership |
| **EM-OPEN-019** | 🔴 **What is the voter threshold below which an election is auto-approved without administrator review?** **The implementation and the Constitution's own docblock both say ≤ 40** *(`ConstitutionalTransitionGuard`: "Free plan (≤40 voters) always eligible")*. **The Product Owner stated ≤ 30 during runtime verification on 2026-08-09** — *"election with voters under 30 can be accepted automatically."* **Two figures, two sources. NOT resolved here: I cannot choose between a Product Owner statement and the implementation, and the statement may have been operational rather than a rule declaration** | election approval |
| **EM-OPEN-018** | **`PBDIGIT-50` — in which timezone are election times displayed, and what is the fallback when detection fails?** *(A narrow Product Owner steer is on record — **device timezone, not residence** — but the ticket is **`OPEN — not authorised`** with the fallback undecided, so it is **NOT migrated as an adopted rule**.)* | election display |
| **EM-OPEN-017** | **`D-MANIFEST`** — is this artifact, at this location and name, the ratified canonical home? **ARB 2026-08-12: deliberately NOT ratified yet.** Ruled useful and non-redundant *(it holds rule kinds the Constitution must not absorb — see the authority investigation)*, but its canonical status and its **name** (*"Manifesto" risks reading as a second Constitution*) await an explicit decision | 🟡 **this document's own status** |
| ~~**EM-OPEN-022**~~ | ✅ **CLOSED — READING A** *(PO/ARB ruling, 2026-08-15)*: **a correction to a published voting schedule creates a NEW voting opportunity; the previous one is superseded and permanently recorded; no progression decision carries over.** Adopted as **`EM-VOC-005`** (§4), extending **`EM-VOT-005`**. The interim binding constraint is **superseded by the ruling, which is at least as strict.** **Architecture may now incorporate it; implementation remains separately gated.** | ✅ resolved |
| **EM-OPEN-023** | **Does the rule attach only to a *published* schedule?** `EM-VOC-005` is worded — consistently, in the ruling — around a **published** voting schedule. **Whether changing a schedule that was never published is a "correction" at all is not decided.** *Governance notes the risk here is low in either reading* (no progression decision can exist for an unpublished schedule, so nothing could carry forward), **but the boundary should be stated rather than assumed.** ⛔ Architecture and Implementation must not settle it by default. | voting opportunity identity |
| **EM-OPEN-021** | **What state is an election in when its voting window is open but it has no approved candidates?** `EM-VOT-002` (correctly) forbids `VotingActive`; the computed lifecycle then falls through to whatever the derivation order yields. **ARB qualification (2026-08-13): this is an UNRESOLVED DOMAIN DECISION — explicitly NOT an implementation defect.** Session 3 did the right thing by refusing to invent the answer. **Binding constraints until decided:** ① **the technical fall-through state is NOT a business rule and must never be read as one** *(an implementation must not accidentally turn a technical fallback into a business rule)* · ② **Session 3 must not resolve this implicitly** — no new lifecycle state, no fallback selection, without explicit authorization · ③ **Session 2 recommends no fallback state.** **NOT ACTIONABLE until the PO makes the lifecycle-state decision** *(candidates named by the ARB, none endorsed: `setup_nomination` · a holding state · another defined state · other)* | election lifecycle semantics |
| ~~**EM-OPEN-020**~~ | ✅ **CLOSED — `SD-14` = YES** *(ARB/PO, 2026-08-13)*: *"the next phase"* **includes the voting phase**. Adopted as **`EM-VOT-002`** (§4a). **Implementation remains separately gated** — the ruling explicitly says *"do not implement yet"* | ✅ resolved |

**`EM-OPEN-017` is stated plainly:** this Manifesto was created under a Product-Owner instruction to canonicalize, and **its ratification as *the* canonical artifact is itself a governance decision that has not been separately recorded.** **It is offered as the canonical home, not declared to be one by its own authority.**

---

## 9a · Coverage of this artifact — stated honestly

**A backlog sweep was performed on 2026-08-12 to test this Manifesto's completeness, after it was first written.**

| | |
|---|---|
| **Swept** | every ticket in `docs/publicdigit/backlog/` for explicit business-rule statements, *"stated by the Product Owner"*, *"(PO decision)"* and approval markers |
| **Gap found and closed** | **`EM-VOT-001`** — the candidate rule from `PBDIGIT-64`, adopted **2026-08-08**. **It was missing from the first version of this Manifesto**, which had canonicalized only the entitlement/admission decision thread |
| **Candidate found and NOT migrated** | **`EM-OPEN-018`** — the timezone display decision (`PBDIGIT-50`), because the ticket is **`OPEN — not authorised`** |
| **Also swept** | **`ElectionConstitution`** itself, at the Product Owner's prompting. It **homes six business rules** — now enumerated in §7 rather than referenced as one line, so a reader of this Manifesto can see that those rules exist and where they live. **They are NOT restated here** — the Constitution is their canonical home. **The sweep also surfaced `EM-OPEN-019`, a disputed numeric threshold** |
| **Correctly out of scope** | `PBDIGIT-30`'s approved **organisation** lifecycle rules (`B1`, `B2`, `B3`, `B10`). They are **Organisation-scope, not Election**, and they **already have a canonical home** in this same folder — `20260806_0818_how_many_organisation.md` |

> ⚠️ **Coverage limit, stated rather than implied: this artifact is complete for the ENTITLEMENT, ADMISSION and VOTING-PHASE rules it lists, plus the sequencing decisions. It is NOT proven to contain every adopted Election business rule.** The sweep raised confidence but is not a guarantee — decisions stated in conversation and recorded only in narrative reviews may still be unmigrated. **Two known candidates of that kind, needing Product Owner confirmation before migration:** whether a voter must be routed directly to the election during the voting period, and whether elections below a voter threshold are auto-accepted without administrator approval. **Neither is migrated on my reading of a conversation.**

## 10 · Rules deliberately NOT migrated

**Everything below was considered and excluded. Recorded so the exclusions are auditable.**

| Not migrated | Why |
|---|---|
| Production behaviour *(e.g. that admission currently yields `active`)* | **Implementation, not authority.** It is the only evidence for one side of `EM-OPEN-001` — migrating it would decide that question by default |
| Test names, test comments, fixtures | Not authority |
| Officer Guide statements | **Assessed `D` — mixed authority, not promoted.** Retained as evidence only |
| Model docblocks; `architecture_legacy/` documents | Not authority *(one is an assistant-conversation transcript in a folder an ADR declares legacy)* |
| Schema and column semantics; table and field names | **Implementation detail.** A Manifesto rule states **what the business rule is** |
| Proposed decisions, recommendations, options | Not adopted |
| Unresolved questions | §9, explicitly not rules |
| `FM-1`…`FM-15` | Deferred register — **not migrated as Election-Only rules** |
| Session 1's verification findings | Another stream's evidence; **not read as authority** |
| `PBDIGIT-30`'s approved organisation-lifecycle rules (`B1`, `B2`, `B3`, `B10`) | **Organisation scope, not Election** — and they already have a canonical home in this folder |
| The timezone display decision (`PBDIGIT-50`) | Ticket is **`OPEN — not authorised`**; recorded as `EM-OPEN-018` |
| Anything in Session 2's own review reports that was a *finding* rather than an adopted decision | **The distinction this artifact exists to enforce** |

---

## 11 · Rule → capability → verification responsibility

**For Session 3 and Session 1. This states responsibility only; it classifies nothing and prescribes no mechanism.**

| Manifesto rule | Intended capability | Verification responsibility |
|---|---|---|
| `EM-EO-001`, `EM-EO-002` | Admit a person to an election without Organisation Membership | Session 1 |
| `EM-EO-003` | Admission leaves Organisation Membership uncreated | Session 1 |
| `EM-EO-004` | Organisation change has no automatic election effect in this mode | Session 1 |
| `EM-ENT-001`, `EM-ENT-005` | One election-specific entitlement concept | Session 1 |
| `EM-ENT-002`, `EM-GOV-001` | Existence and exercisability are separately observable | Session 1 |
| `EM-ENT-003` | Entitlement persists until a defined removal act | **blocked by `EM-OPEN-004`** |
| `EM-ENT-004` | Organisation change does not destroy the entitlement | Session 1 |
| `EM-ENT-006` | Election-side removal leaves Organisation Membership intact | Session 1 |
| `EM-GOV-002`, `EM-GOV-003` | Chief suspension, distinct from organisation-driven suspension | **partly blocked by `EM-OPEN-002`** |
| `EM-VOC-001`…`003` | Vocabulary discipline | Sessions 1 and 3 |
| `EM-SEQ-001`, `EM-SEQ-002` | Phase discipline | Session 2 |
| `EM-FM-001`…`006` | — | **DEFERRED — not to be verified or implemented in this phase** |

---

**Change control:** amendments to this Manifesto require an explicit Product Owner / ARB decision. **No session may add, alter or remove a rule to make an implementation or a test pass.** A missing rule is a question for governance, not a gap to fill.

**Historical record of how these decisions were reached:** `PBDIGIT-68` and the Session 2 governance artifacts under `docs/publicdigit/reviews/` (2026-08-09 … 2026-08-12). **Those record the reasoning; this document records the rules.**

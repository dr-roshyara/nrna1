# E0a-2 Membership Decision Pack

**Type:** Decision preparation for the **Principal Reviewer / PO**. **Nothing here is decided, implemented or migrated.** · **Date:** 2026-10-08
**Commission:** *"E0a-2 Decision Engineering Phase: Membership Identity, Lifecycle and Business Rules"* (2026-10-08)
**Baseline:**
- `…-e0a2-membership-development-report.md` (**DR**)
- `…-e0a2-membership-identity-and-lifecycle-falsification.md` (**FR**)
- reconciliation / register / discovery (**R / Q / D**)

**Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable)
**Terminology:** "Organisation Membership" = the `Member` aggregate (**EM-VOC-001, DECIDED**). This pack does **not** use the V4 proposition "Membership ≠ Member" as canonical. Where an alternative needs a new term (for example "membership episode"), that is reported as a **terminology decision requirement** (TDR).

| Decision | Status | Evidence strength | Decision owner | Blocks |
|---|---|---|---|---|
| **D-ID.1:** identity continuity across re-entry | OPEN | balanced. No DIRECT evidence; implementation split 3:3; analogies favour new identity | PO / business (platform canonical model) | lifecycle model, multiplicity form, legacy UNIQUE disposition, re-entry repairs, Q-FM-MAP options |
| **D-ID.2:** membership-number continuity across re-entry | OPEN | weak. No source speaks to re-entry | PO (model); possibly a **tenant parameter** | whether a relationship-level concept (Model C) is needed; number generation on re-entry |
| **D-TERM:** meaning and finality of ending acts (incl. appeal, withdrawn resignation) | OPEN | moderate for "ending is final" (guide, no code reversal); none for appeal/withdrawal | PO (model) + tenant (grounds, appeal organ) | the termination commands, EM-FM-003 trigger mapping (FM-5) |
| **D-SUSP:** suspension = lifecycle state or restrictions | OPEN | weak. Only incidental implementation effects | PO + Principal Reviewer | the state set; restriction model; FM-6 |
| **EQ-2′:** recognition by import / invitation / registry entry | OPEN | moderate (paths without a decision exist) | PO (model) + tenant (organ) | `InForce` definition; the import/invitation repairs |
| **Multiplicity:** ≤ 1 non-terminal membership per person+organisation | CANDIDATE | moderate (no counterexample found) | PO / ARB (confirm) | admission guard; persistence mechanism |
| **Number uniqueness scope:** per organisation | HYPOTHESIS (strongly supported) | moderate (organisations import their own numbers) | PO (confirm) | the number namespace; migration |

---

## 1. Executive Decision Summary

- **Self-correction first.** FR §1.3 and §11 called Model B "falsified as sole identity". **That overstated the result.** The exploration's ambiguity property counted *undated* references. Real PublicDigit artefacts carry their own time:
  - fees have `period_label` and `due_date`;
  - renewals have old and new expiry dates;
  - election registrations and evaluations have timestamps.

  With dated artefacts and a dated membership history, **B can identify the period an artefact refers to**. **B is therefore not falsified.** It is weaker only for undated references. **The D-ID question is genuinely open between A and B.**
- **D-ID factorises into two independent questions.** Identity continuity (D-ID.1) and number continuity (D-ID.2) can be answered separately. Their 2×2 combinations reproduce Models A, B and C exactly (§4). **Model C is needed only in one cell:** *new membership per admission, but the number is retained*.
- **The smallest logically sufficient decision set is five business answers plus two confirmations** (§16 B). Everything else, including authority, the election mapping, the published facts, the aggregate and persistence, is derivable from them or can safely wait.
- **Five conformance defects can be fixed before any decision** (fail-closed or model-neutral). The rest must wait (§15). None is authorised by this pack.

## 2. Decisions already adopted (inputs, not reopened)

| ID | Content | Relevance |
|---|---|---|
| EM-VOC-001 | "Organisation Membership" = the `Member` aggregate: identity, type, fees, term | terminology of every alternative |
| EM-VOC-002 | an affiliation or role is not membership | the affiliation is not the membership |
| EM-ENT-001…007, EM-EO-001…004 | the election entitlement belongs to Election; Election-Only needs no membership | Membership never grants voting |
| EM-FM-001…006 (DEFERRED) | Full Membership: "removal" auto-suspends the ElectionMembership; exercisability only | Q-FM-MAP (§12) |
| EM-SEQ-001/002 | Full Membership is a later phase | no Membership implementation now |
| SM2 (2026-09-16) | ElectionMembership lifecycle; REMOVED final | analogy only |
| ADR_20261004_1549 | Registration → ElectionMembership → derived Admission → VotingEntitlement (`canVote`) | preserved (§12) |
| ADR-T11 | anonymity | Membership facts never reach the ballot |

## 3. Decisions still open

These are D-ID.1, D-ID.2, D-TERM (with D-TERM.a appeal and D-TERM.w withdrawn resignation), D-SUSP, EQ-2′, the multiplicity confirmation and the number-uniqueness confirmation.

Separate tracks, not in the minimum set:
- EQ-5 authority / EM-OPEN-014;
- Q-FM-MAP;
- type-change-as-new-membership;
- the `grants_voting_rights` disposition;
- the tenant time zone.

---

## 4. Membership Identity Models

The **anchor** question comes first: does the domain need a relationship identity beyond `(Person, OrganisationAffiliation)`?

- The affiliation (`organisation_users`) is **soft-deletable and can end** when a person leaves the organisation entirely (FACT).
- A membership history must survive that (FR §6). So the only *always-available* anchor is the **value** `(personId, organisationId)`, **not** the affiliation entity.
- **A value pair needs no identity of its own.** A distinct relationship identity (Model C) is required **only if** something must be **stored** at relationship level that is neither a membership attribute nor derivable from the set of memberships of the pair.

| Candidate relationship-level datum | Derivable from the memberships of (p,o)? | Needs C? |
|---|---|---|
| "member since" / total tenure (`CommitteeLevel.minMembershipYears`) | **yes**: a sum or min over the memberships' effective intervals | no |
| historical list of periods | yes | no |
| **a membership number retained across re-entry** | **no** under Model A: it would have to be stored somewhere spanning memberships | **yes** (or store it on the pair as a register entry) |
| disciplinary record spanning memberships | belongs to Governance (restrictions), not Membership | no |

**The 2×2 that replaces A/B/C:**

| | **D-ID.2: number re-issued on re-entry** | **D-ID.2: number retained on re-entry** |
|---|---|---|
| **D-ID.1: new identity per admission** | **Model A** | **Model C** (the number lives on a per-(p,o) register entry; memberships are episodes) |
| **D-ID.1: one identity continues** | **Model B′**: one identity, but a new number per period. **Internally strange**: one membership with several numbers. Admissible only if numbers are period credentials | **Model B** |

## 5. Membership Number Semantics

**Evidence → interpretation:**

| Evidence | Category | Supports |
|---|---|---|
| approval was documented to generate `M`+8 (`membership_lifecycle_implementation.md:35-85`); the test pins it; **regressed** (FR: 2 failed) | INDIRECT | N2 or N3 (assigned at admission) |
| organisations import **their own** numbers (tutorial: *"eigene Mitgliedsnummer … wird automatisch generiert, wenn leer"*) | INDIRECT | **N3** (organisation-issued); in practice usually stable per person (N1-like). No source says so |
| `unique` across **all tenants** | IMPLEMENTATION | N4 (technical); **in tension with N3** |
| generators differ per path (`M`+8 random; `M`+`uniqid`; `{SLUG}-{YEAR}-{SEQ}` in the unrouted Desktop/Mobile service; CSV custom) | IMPLEMENTATION | **N4** (no single concept) |
| legacy design `FM-…` / `AM-…` by type | INDIRECT (legacy) | **type-namespaced** numbers → N2-like (tied to the kind of membership) |
| **not displayed** on any Vue page; not in any export class (`git grep` in `resources/js` and `app/Exports`: only the import tutorial) | FACT | weakens "visible credential to members" **today** |
| no source on re-entry | UNKNOWN | — |

**Competing meanings:** N1 (identity of the person-in-organisation membership), N2 (per admission), N3 (organisation-issued credential), N4 (technical), N5 (N3 combined with N1 or N2).

**Classification:**
- **N3 is STRONGLY SUPPORTED** (organisation-issued and importable).
- N4 describes the **current implementation**, not intent.
- **N1 vs N2 is OPEN.** That is exactly D-ID.2.
- **Organisation-scoped uniqueness:** a **HYPOTHESIS** (strongly supported by N3). Global uniqueness is IMPLEMENTATION behaviour. The actual invariant is **OPEN until confirmed** (§16 B).
- Type namespaces (FM-/AM-): **OPEN** and **deferred** (only relevant if numbers encode type).

**Business questions (for officers, not architects):**
1. *"When a former member is re-admitted, do they get their old membership number back?"*
2. *"May two organisations on the platform use the same number for different people?"* (expected: yes)
3. *"Does a member's number change if they move from associate to full membership?"*

## 6. D-ID Decision

| | **D-ID-A: a returning person gets a new Organisation Membership** | **D-ID-B: the existing Organisation Membership continues** | **D-ID-C: the relationship persists; each admission is a distinct membership (episode)** |
|---|---|---|---|
| Domain definition | an Organisation Membership = one admission's relationship from admission to end | an Organisation Membership = the person's membership relationship with the organisation, across all periods | an Organisation Membership = one admission (as in A), **plus** a per-(person, org) register entry that carries the retained number |
| Lifecycle | admitted → … → ended (final) | admitted → … → ended → **re-admitted** (ending is final only per period) | as A, per membership |
| Historical | one effective interval per membership; history = all memberships of (p,o) | **multiple effective intervals** in one membership | as A |
| Identity | `MembershipId` per admission | one `MembershipId` per (p,o) | `MembershipId` per admission + a register-entry identity per (p,o) |
| Number | new per admission (or N3 custom) | one, stable | one, stable (on the register entry) |
| Audit | an artefact → MembershipId (unambiguous) | an artefact → MembershipId + its own date (unambiguous only if dated) | as A |
| Election (§12) | registration can name *the* membership that qualified | a qualifying membership = the same id before and after a gap; Q-FM-MAP option A collapses into person-level evaluation | as A |
| Migration from legacy | UNIQUE(`organisation_user_id`) contradicts the rule (it blocks re-admission); `ended` rows stay as history | **UNIQUE fits**; `ended` rows are re-activated with a new interval; needs an interval history table | as A + a number register |
| Aggregate | small: one interval, a simple state machine | larger: interval collection; a "re-admit" command; ending not terminal | as A + one small register entity |
| Advantages | clean, final ends; matches the approval path's intent, the guide (*"new application required"*), both analogies; simplest invariants | matches current schema and 3 of 6 legacy paths; one member record per person (familiar to officers); a stable number for free | gives A's clean history **and** B's stable number |
| Disadvantages | number continuity needs a separate decision; legacy UNIQUE must change | ending is not final at identity level; multiple intervals needed; undated references are ambiguous | one more concept (TDR: name for the register entry) |
| Contradictions | approval-path crash today (CX-1) | guide *"cannot be undone"* holds only per period | none found |
| Required business assumptions | re-entry is a new admission, not a continuation | re-entry continues the same membership | re-entry is a new admission **and** the organisation keeps the person's number |

**Recommendation (not a decision):**
- **D-ID-A: SUPPORTED.**
- **D-ID-B: SUPPORTED.**
- **D-ID-C: OPEN.** It is chosen **only if** the answers are D-ID.1 = new **and** D-ID.2 = retained.
- **No option is RECOMMENDED.** The evidence is balanced, and the two business answers decide between them directly.
- One consideration for the Principal Reviewer: **A or C keeps invariants I3/I4 simple and ending final**, whereas B requires the multiple-interval machinery. If the business is indifferent on D-ID.1, A/C is the lower-complexity choice. This is **not** a business argument.

## 7. D-TERM Decision (separate from D-ID)

| Act | Same membership continues | Membership ends | New admission needed to return | Evidence |
|---|---|---|---|---|
| **lapse** (term passes) | **✓** (renewal resumes) | — | no (within the window) | FACT + INDIRECT (STRONGLY SUPPORTED) |
| lapse beyond the renewal window | ❓ | ❓ | ❓ (today: only an admin may renew) | IMPLEMENTATION (`canSelfRenew`) → **OPEN** |
| **suspension** | ✓ | — | — | all sources |
| **resignation** | — | ✓ at the effective date | ✓ | guide (*"Resignation"* as an end reason) |
| **termination** (organ-decided, e.g. non-payment) | — | ✓ | ✓ | guide (*"Non-payment"* as an end reason, **distinct from lapse**) |
| **expulsion** | — | ✓ | ✓ (and possibly barred) | no evidence → **OPEN** |
| **administrative correction** (the record was wrong) | n/a: the corrected record **never was** a membership (void) or is the same one corrected | — | — | no evidence → **OPEN** (D-TERM.c) |
| **withdrawal of resignation** | see D-TERM.w | | | **no evidence** |
| **appeal against termination/expulsion** | see D-TERM.a | | | **no evidence** |
| **reinstatement** (generic) | depends on D-ID.1: under B, re-activation; under A/C, a new admission | | | guide: *"new application"* |

**D-TERM.w (withdrawn resignation):**
- **(w1)** the resignation never becomes effective, so the same membership continues and only the resignation decision is cancelled;
- **(w2)** withdrawal is allowed only before the effective date; after it, the person must re-apply.

**A formal note:** (w1) requires that resignation be a **future-effective decision** (decidedAt < effectiveAt). That is the only evidenced need for separating `decidedAt` and `effectiveAt` in termination.

**D-TERM.a (successful appeal):**
- **(a1)** the expulsion decision is **annulled**: the same membership continues and was never ended (history shows the annulled decision);
- **(a2)** the membership is **restored** from the ended state, contradicting finality (only coherent under B);
- **(a3)** a **new admission** is granted.

(a1) keeps finality intact (an annulled decision never took effect) and works under every D-ID option. It requires decision records that can be annulled (an audit need; **OPEN**).

## 8. D-SUSP Decision

**Formalisation:** `S1: State = {IN_FORCE, SUSPENDED, TERMINATED}`. `S2: State = {IN_FORCE, TERMINATED}` and `Suspended(m,t) ≡ ∀r ∈ Rights: Restricted(m,r,t)`. **S2 is sufficient iff a suspension has no consequence outside rights.**

| # | Dimension | Under S1 | Under S2 | Evidence | Forces S1 if… |
|---|---|---|---|---|---|
| 1 | effective period | unchanged or extended ❓ | unchanged | none | **the suspended time does not count toward the term** (it is extended) |
| 2 | term | as 1 | as 1 | none | same |
| 3 | dues | may pause | continue | **incidental:** the annual-fee job filters `status='active'`, so suspended members get no fees (IMPLEMENTATION, inert under `PBDIGIT-211`) | **dues stop during suspension** |
| 4 | member counts | excluded | counted | incidental: dashboards count by status | **suspended persons are not counted as members** |
| 5 | quorum | excluded | counted (no right to attend?) | none | as 4 |
| 6 | services | withdrawn | a restriction | none | — (expressible by S2) |
| 7 | committee participation | withdrawn | a restriction; the committee lineage has its **own** suspension (FACT) | FACT | — |
| 8 | election participation | not "removal" unless FM-6 says so | a restriction | EM-FM-003 (removal only) | — |
| 9 | eligibility | false | false (via the restriction) | eligibility reads `status='active'` (IMPLEMENTATION) | — |
| 10 | standing | ❓ | unaffected unless dues lapse | none | — |

**A business requirement forces S1 iff any of rows 1–5 holds.** Otherwise S2 suffices, and it composes with participation restrictions (PI-5), which are needed in every case. **No model is selected.** The implementation's incidental effects (rows 3, 4, 9) are **not** business evidence; they follow from `WHERE status='active'` filters.

## 9. Recognition / EQ-2′

| Path | Exists? | Recognised? | Effective? | In force? | Who authorised? | Evidence today |
|---|---|---|---|---|---|---|
| application approval | ✓ | ✓ by the approval decision | ❓ start not written (`joined_at` null on this path) | ✓ (status active) | owner/admin (a role, not an organ) | application status; `reviewed_by` **not written** |
| direct admission (`addMember`) | ✓ | ❓ (no decision record) | ✓ `joined_at=now` | ✓ | owner/admin | none |
| invitation acceptance | ✓ (or the **old row returned**) | ❓: the inviter's act or the invitee's acceptance? | ✓ `joined_at=now` (new rows only) | ✓ (new) / ✗ (old ended row) | inviter owner/admin + invitee | invitation row |
| current CSV import (DDD) | ✓ (new e-mails only) | ❓ | ❓ (the CSV `joined_at` / number are **ignored** by `MemberImportService`) | ✓ | **anyone with any org role** | import job |
| legacy participant import | ✓ / overwritten | ❓ | ✓ | ✓ | (not traced) | none |
| migration from a prior register | ✓ | ❓: recognised by the **prior** organ's historical decision? | historical dates | ✓ | the prior register | the file |
| administrative correction | — | — | — | — | ❓ | none |

**EQ-2′, precisely (for the PO):**

> *"For a membership entered by import, migration or invitation, is the entry act itself the organisation's recognition, provided it records its basis (who, when, the source register or invitation), or must an authorised organ confirm the membership before it is in force?"*

The alternatives:
- **(r1)** the entry act recognises, with the basis recorded;
- **(r2)** the membership exists but is not in force until confirmed;
- **(r3)** per path (import = r1 with basis; invitation = r2).

**Recognised and Exists are separate predicates only under (r2) or (r3).**

## 10. Multiplicity

**Candidate domain invariant:** `∀p,o,t: |{m : holder(m)=(p,o) ∧ life(m,t) ∉ {⊥, TERMINATED}}| ≤ 1`.

| Counterexample searched | Evidence | Result |
|---|---|---|
| multiple membership types concurrently | `members.membership_type_id` single-valued; no source asks for two | not found |
| associate + full concurrently | legacy docs treat them as alternatives (an associate → full upgrade) | not found |
| youth + full | youth / women / student are **committee types or wings** (`CommitteeType`), not membership types | not found (category error) |
| honorary | honorary = fees waived (legacy doc `1736:282`), a **type** of one membership | not found |
| organisational wings | committee association (Governance), not organisation membership | not found |
| concurrent legal relationships (e.g. member + employee) | an affiliation or other context, not membership | not found |
| imported historical records | historical rows are terminated, so they do not count | consistent |

**Status: CANDIDATE DOMAIN INVARIANT** (no counterexample; no DIRECT confirmation). Under D-ID-B it is trivially true (one identity). Under A/C it is a real cross-membership rule. **No persistence recommendation** (§14 order).

## 11. Temporal Model: reclassification of FR's formal claims

| # | Statement | Premises + definitions → derivation | Classification |
|---|---|---|---|
| **I1** | `holder(m)` constant | P: an Organisation Membership is a relationship of **one** person with **one** organisation (EM-VOC-001 + R O-1). D: changing a relatum yields a different relationship. ⇒ the holder is constant. **Counterexample tested:** a data correction (wrong person recorded): either a *void + new* membership (I1 holds), or the *same membership with the corrected person* (I1 fails) | **STRONGLY SUPPORTED** (DERIVED except for corrections; the correction policy is D-TERM.c) |
| **I2** (FR) | termination closes the effective interval | FR claimed "required". **Re-examined:** if `InForce(m,t) := Effective(m,t) ∧ life(m,t)=IN_FORCE`, then `life=TERMINATED` already makes InForce false. Closing the interval is **one of two equivalent representations** | **downgraded to IMPLEMENTATION CHOICE** (D) |
| **I2′** | `t ≥ effectiveAt(termination(m)) ⇒ ¬InForce(m,t)` | P: "termination" means the membership ends (the meaning of the word in the guide). D: InForce. ⇒ immediate | **DERIVED** (with the caveat that D-TERM.w/a may annul a termination before it takes effect, so the premise is about *effective* terminations) |
| **I3** | termination is final | not derivable: depends on D-TERM.a/w and D-ID.1 (B re-activates) | **BUSINESS DECISION** |
| **I4** | rejoin ⇒ a distinct identity | = D-ID.1 | **BUSINESS DECISION** |
| **I5** | ≤ 1 non-terminal | §10 | **CANDIDATE** |
| **I6** | an artefact identifies its membership period | holds for **dated** artefacts under all models (§1 self-correction). For undated references only under A/C | **STRONGLY SUPPORTED** in its dated form; the undated form is a **CANDIDATE requirement** |
| **L1** | lapse is derived from effectiveness, not a stored state | P1: lapse happens when the term passes (a date event). P2: suspension and termination are decisions, independent of dates. ⇒ (lapsed × suspended) combinations exist (FR §11, reachable). D: an enum holds one value. ⇒ storing lapse as a lifecycle value loses information or needs composite values. Also: the legacy contradiction (`expired` ∧ effective after renewal) | **DERIVED** |
| **L2** | lapse is resumable within the same membership | FACT (renewal extends the same row; self-renewal 90 days after expiry) + INDIRECT (guide) | **STRONGLY SUPPORTED** |
| **T1** | `[from, until)` half-open intervals | convention (V4 TC-01); no business source | **IMPLEMENTATION CHOICE / architecture convention** (adopt as a convention, not as domain truth) |
| **T2** | `decidedAt ≠ effectiveAt` needed | only for future-effective resignations (D-TERM.w1) or backdated admissions (no evidence) | **CANDIDATE**, conditional on D-TERM.w |
| **N-U** | number unique per organisation | §5 | **HYPOTHESIS (strongly supported)** |

## 12. Election Dependency (Election unchanged)

Preserved: Registration → ElectionMembership → derived Admission → VotingEntitlement (`canVote`); ADR-T11. The dependency analysed is *Organisation Membership → Full Membership eligibility at registration → ElectionMembership*, plus EM-FM-003.

**Q-FM-MAP options:**
- **(A) admission-bound:** the election registration records *which* membership satisfied the criteria. EM-FM-003 fires when **that** membership is "removed".
- **(B) continuous person-level:** EM-FM-003 evaluates `IsMember(p,o,t)` continuously.
- **(C) a hybrid:** the trigger is membership-bound, and lifting is person-level (re-entry lifts it automatically). This is W-1.

| D-ID option | Q-FM-MAP (A) | (B) | (C) |
|---|---|---|---|
| A | well defined: re-entry = a new membership, does not lift | well defined | well defined |
| B | **collapses into (B)** (same identity across periods; needs dated evaluation) | well defined | well defined |
| C | as A | as A | as A |

**Does Membership identity need Q-FM-MAP to be resolved first? No.** Every D-ID option supports at least two Q-FM-MAP options. Only **D-ID-B removes option (A)**, and that consequence must be **stated to the PO** when D-ID.1 is decided. Q-FM-MAP stays in the **deferred Full Membership election track**.

## 13. Requirement Cards

| REQ-ID | Title | Question | Business motivation | Actors | Preconditions | Required behaviour | Postconditions | Business invariant | Evidence | Decision owner | Affected contexts | Dependencies | Acceptance criteria | Category | Status |
|---|---|---|---|---|---|---|---|---|---|---|---|---|---|---|---|
| **REQ-DEC-01** | Re-entry identity | is a returning person's membership new or continuing? | history, numbers, finality | PO; membership organ; former member | a terminated membership exists for (p,o) | re-admission creates a new membership **or** re-activates the existing one | exactly one non-terminal membership for (p,o) | I4 / D-ID.1 | §6; FR §7 | PO | Membership; (Election via Q-FM-MAP) | — | after a decision: re-admission of a former member yields the decided identity on **every** entry path | Canonical PublicDigit model | **OPEN** |
| **REQ-DEC-02** | Number continuity | does a returning person keep the membership number? | external credential continuity | PO; tenant | as 01 | keep the old number **or** issue a new one (possibly per tenant) | the number follows the decided rule | D-ID.2 | §5 | PO (model) / tenant (parameter?) | Membership | 01 (for the C cell) | a returning member's number equals or differs from the old one, as decided | Canonical model with a tenant parameter (candidate) | **OPEN** |
| **REQ-DEC-03** | Meaning of ending acts | which acts end a membership; is ending final; can resignations be withdrawn; can appeals annul? | legal certainty | PO; tenant (grounds, appeal organ) | membership in force | resignation / termination / expulsion end it at the effective date; withdrawal and appeal per decision | not in force after an effective end (I2′) | I2′ (DERIVED); I3 (decision) | §7 | PO + tenant | Membership; Election (FM-5) | — | for each act: post-state, reversibility and the record are as decided | Canonical model + tenant statutory rule | **OPEN** |
| **REQ-DEC-04** | Suspension semantics | does suspension pause the term or dues, or exclude from counts? | correct dues, counts, quorum | PO; tenant | in force | suspension restricts rights; ❓ also pauses the term/dues | as decided | S1 iff rows 1–5 | §8 | PO + Principal Reviewer | Membership; Governance | — | a suspended member's term, dues and counts behave as decided | Canonical model | **OPEN** |
| **REQ-DEC-05** | Recognition of entries | does import, migration or invitation recognise a membership? | trust in the register | PO; tenant organ | an entry act | (r1) / (r2) / (r3) | recognised per decision; the basis recorded | — | §9 | PO + tenant | Membership | EQ-5 for (r2) | an imported membership is in force or pending as decided | Canonical model + tenant rule | **OPEN** |
| **REQ-INV-01** | One non-terminal membership | ≤ 1 non-terminal per (p,o) | prevent double membership | system | — | refuse a second admission while one is non-terminal | — | I5 | §10 | PO / ARB (confirm) | Membership | 01 | a second admission is refused while one is in force or suspended | Platform invariant (candidate) | **CANDIDATE** |
| **REQ-INV-02** | No membership after an effective end | — | correct historical answers | system | an effective termination | — | `IsMember` false after the effective end | I2′ | §11 | Principal Reviewer (adopt) | Membership; consumers | — | `IsMember(p,o,t)` false for t ≥ the effective end | Platform invariant | **DERIVED** (adoptable) |
| **REQ-INV-03** | Lapse resumable; lapse not stored as an end | — | renewal works | system | lapsed, within the window | renewal restores in-force | the same membership | L1, L2 | §11 | Principal Reviewer | Membership | — | renewing a lapsed membership makes it in force; no "expired-but-effective" state is possible | Canonical model | **DERIVED / STRONGLY SUPPORTED** |
| **REQ-INV-04** | History is never physically destroyed by affiliation changes | — | answer "was member at t" | system | — | ending or deleting an affiliation never deletes membership history | history retained | P: the domain must answer historical membership (commission premise; I6) | FR §6 | Principal Reviewer | Membership; Organisation | — | after the affiliation is removed, the membership history is queryable | Platform invariant | **STRONGLY SUPPORTED** |
| **REQ-NUM-01** | Number namespace | is the number unique per organisation? | organisations bring their own numbers | PO | — | uniqueness within the organisation | — | N-U | §5 | PO (confirm) | Membership | — | two organisations can import the same number | Canonical model | **HYPOTHESIS** |
| **REQ-FM-MAP** | Election trigger binding | Q-FM-MAP | EM-FM-003 | PO | Full Membership phase | (A) / (B) / (C) | — | EM-FM-003…006 | §12 | PO | Election ← Membership | 01, 03 | — | Canonical model (Election) | **DEFERRED** |

## 14. Decision Dependency Graph

```text
BUSINESS DECISIONS ─────────────────────────────────────────────────────────────────────────────
  D-ID.1 (re-entry identity)   D-ID.2 (number continuity)   D-TERM(.w,.a,.c)   D-SUSP   EQ-2′
        │                             │                          │               │        │
        └──────────┬──────────────────┘                          │               │        │
                   ▼                                             │               │        │
DOMAIN DEFINITIONS │  Model A / B / C (§4 2×2) · termination kinds · suspension S1|S2 · recognition r1|r2|r3
                   ▼                                             ▼               ▼        ▼
INVARIANTS         I1 (strongly supp.) · I2′ (derived) · L1/L2 (derived/strong) · I3/I4 (from D-TERM/D-ID.1)
                   · I5 (candidate; confirm) · I6 · N-U (confirm)
                   ▼
AGGREGATE CONSISTENCY   which invariants are intra-membership (I1, I2′, L1, transitions) vs cross-membership (I5; C register)
                   ▼                                  ◄── AUTHORITY (EQ-5, EM-OPEN-014): parallel; must close before commands
COMMAND SEMANTICS       Admit · Renew · Suspend/Restrict · Restore · Resign · Terminate · Annul (D-TERM.a1) · Re-admit (B only)
                   ▼
CONCURRENCY             I5 at Admit/Re-admit; renewal vs termination races
                   ▼
PERSISTENCE             (deliberately not chosen: UNIQUE / partial unique / check / serialised)
                   ▼
MIGRATION               legacy UNIQUE, global number uniqueness, cascade delete, 3 status vocabularies, re-entry paths

ELECTION TRACK (deferred, EM-SEQ-001):  D-ID.1 + D-TERM ──► Q-FM-MAP ──► FM-5…10 ──► EM-FM-003 implementation
```

## 15. Defect Classification (conformance / legacy; none fixed)

| # | Defect | Fix before D-ID? | Wait D-ID? | Wait D-TERM? | Wait D-SUSP? | Note |
|---|---|---|---|---|---|---|
| F-1 | approval does not generate the documented number (test fails) | **partly:** generating a per-organisation-unique number for **first** admissions is model-neutral | the re-entry case and the format/namespace wait for D-ID.2 + N-U | — | — | do not hard-code global uniqueness |
| F-2 | six re-entry paths behave inconsistently | no | **yes** | yes (withdrawal/appeal) | — | the fix is "all paths implement the decided re-entry rule" |
| F-3 | UNIQUE(`organisation_user_id`) blocks re-admission | no | **yes** (correct under B, wrong under A/C) | — | — | |
| F-4 | invitation acceptance returns an ended membership unchanged and marks the invitation accepted | **yes, fail-closed:** refuse with a clear message when a non-in-force membership exists (wrong in every model) | the full behaviour waits | — | — | model-neutral |
| F-5 | the DDD import ignores the CSV `membership_number` / `joined_at` and skips existing members, contrary to the tutorial | **partly:** importing first-time members **with** their own number and join date is model-neutral (subject to N-U) | the existing-member behaviour waits | — | — | |
| F-6 | renewal leaves `expired` while effective | **yes:** under every model renewal resumes a lapse (L2) | — | — | — | model-neutral; L1 (derived lapse) is the full fix (G4) |
| F-7 | global `unique(membership_number)` | no | — | — | — | waits for **N-U** confirmation |
| F-8 | `ON DELETE CASCADE` from `organisation_users` hard-deletes membership history | **yes** (REQ-INV-04, model-neutral) | — | — | — | a schema change; needs authorisation |
| F-9 | `Member::endMembership` (dormant) removes ElectionMemberships | no | — | **yes** | — | + Q-FM-MAP |
| F-10 | `manageMembers` undefined (suspend/archive 403) | no | — | — | **yes** (suspend) | + EQ-5 authority |
| F-11 | approval does not write `reviewed_by` / `reviewed_at` | **yes** (model-neutral evidence) | — | — | — | |
| F-12 | `joined_at` / effective start not written on approval | **yes** (every model needs a start) | — | — | — | |

**Fixable before any decision:** F-4, F-6, F-8, F-11, F-12, and the first-admission parts of F-1 and F-5. **None is authorised by this pack.** They map to EPIC-02 `PBDIGIT-05`/`06`. **No new IDs.**

## 16. Recommendation

### A. Safe to adopt now (derived or strongly supported only)

1. **I2′:** no membership is in force from the effective time of its termination (DERIVED).
2. **L1 + L2:** lapse is derived from effectiveness and is resumable within the same membership; it is not an end (DERIVED + STRONGLY SUPPORTED).
3. **I1:** a membership's person and organisation never change, except through the correction policy (D-TERM.c) (STRONGLY SUPPORTED).
4. **REQ-INV-04:** membership history is never physically destroyed by affiliation changes (STRONGLY SUPPORTED, given the historical-query requirement).
5. **N3:** the membership number is an organisation-issued credential, distinct from the technical identity (STRONGLY SUPPORTED). **Its continuity is not included.**
6. **Lifecycle ⟂ effectiveness** as dimensions, which follows from L1 (DERIVED).

### B. Requires an explicit business decision (the minimum set)

1. **D-ID.1:** is re-entry a new membership, or the same one continuing?
2. **D-ID.2:** does the returning member keep the membership number? (Model C arises only from new + kept.)
3. **D-TERM:** the ending kinds and their finality, including **withdrawn resignation** (w1/w2) and **successful appeal** (a1/a2/a3).
4. **D-SUSP:** does suspension pause the term, stop dues, or exclude from member counts or quorum? (Yes to any → S1; else S2.)
5. **EQ-2′:** do import, migration and invitation recognise a membership by the entry act (with its basis recorded), or must an organ confirm it?

Plus **two confirmations**: I5 (≤ 1 non-terminal per person+organisation) and N-U (number unique per organisation).

### C. Must remain deferred

- Authority / organs (EQ-5, EM-OPEN-014): needed before commands, not before definitions.
- Q-FM-MAP and FM-5…10 (Full Membership election track).
- Type change as a new membership; number type-namespaces.
- `grants_voting_rights` disposition (options in FR §13).
- Published membership facts.
- Aggregate design.
- Persistence mechanisms.
- Tenant time zone.
- Retroactive and backdated admissions.
- TDR naming for the register entry (only if Model C).

## 17. Principal Reviewer Decision Points

1. **Accept the self-correction:** Model B is **not** falsified (§1). D-ID is open between A and B.
2. **Accept the D-ID factorisation** into D-ID.1 × D-ID.2 (§4), and Model C as the "new + kept" cell only.
3. **Adopt the §16 A principles,** or name which to downgrade.
4. **Route §16 B to the PO** in business wording (the questions in §5, §7, §8, §9).
5. **Confirm I2's downgrade** to an implementation choice, and I2′ as the derived rule.
6. **Decide whether the model-neutral defects** (F-4, F-6, F-8, F-11, F-12, first-admission F-1/F-5) may be authorised **before** the business decisions.
7. **Note for the PO with D-ID.1:** choosing B removes Q-FM-MAP option (A).

## 18. Traceability

- FR §§2–20 (CX-1…CX-10, the exploration).
- DR §§0–19.
- R §1 (H-05 EM-VOC-001, H-09 SM2, H-10 ADR, H-12 ADR-T11).
- Q (EQ-1…16).
- Evidence new to this phase:
  - `git grep membership_number -- resources/js app/Exports` (only the import tutorial);
  - `docs/publicdigit/membership/20260403_0003_membership_management.md:211-225` (*"Resignation", "Non-payment"* as end reasons; *"cannot be undone … new application"*);
  - no `appeal` / `withdraw` / `expel` evidence in the Membership code or docs;
  - `GenerateAnnualMembershipFees` and eligibility queries filter `status='active'` (incidental suspension effects);
  - `membership_fees.period_label` / `due_date`, `membership_renewals` dates (dated artefacts, §1).

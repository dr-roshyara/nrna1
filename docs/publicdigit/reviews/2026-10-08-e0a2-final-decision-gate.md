# E0a-2 Final Decision Gate: Membership business decisions

**Type:** Decision Gate (**not** research, **not** implementation) · **Date:** 2026-10-08
**Commission:** *"E0a-2 Final Decision Gate: Membership Business Decisions and Architecture Gate Preparation"* (2026-10-08)
**Prepared by:** `claude-code-session:42e0287e…` (self-declared, not attestable), as execution architect.
**Decides nothing.** Every new business rule below carries **`OPEN — awaiting decision`**. Recommendations are labelled **CLAUDE RECOMMENDATION — NOT DECISION**.
**Based on (not repeated):** `2026-10-08-e0a2-membership-decision-pack.md` (**DP**), `…-identity-and-lifecycle-falsification.md` (**FR**), `…-membership-development-report.md` (**DR**).

---

## 1. Executive Gate Summary

- **Six answers move E0a-2 into canonical Membership modelling** (§13):
  1. D-ID.1 (re-entry identity)
  2. D-ID.2 (membership number on re-entry)
  3. D-TERM-1 (which acts end a membership)
  4. D-SUSP (suspension: a state or restrictions)
  5. REC-0 (can a membership exist before it is recognised?)
  6. I5 (one non-terminal membership per person and organisation)
- **D-TERM-2 (finality) needs no separate decision.** It follows from D-ID.1 together with D-TERM-4 (§7).
- **Four questions can be answered later**, before commands are designed but not before the core model: D-TERM-3 (withdrawn resignation), D-TERM-4 (appeal), D-TERM-5 (correction), and the per-path recognition questions (import, invitation).
- **N-U (number uniqueness) is a later confirmation.** It affects persistence and migration, not identity.
- **Deferred:** authority/organs, the Full Membership election mapping (Q-FM-MAP), MembershipType evolution (incl. `grants_voting_rights`), published contracts, persistence, migration, tenant time zone, backdating.
- **No decision is taken here. No code, schema or defect is touched.**

## 2. What is already decided (inputs; not reopened)

| Item | Source | Status |
|---|---|---|
| "Organisation Membership" = the `Member` aggregate | EM-VOC-001 | **DECIDED** |
| Affiliation / role ≠ membership | EM-VOC-002 | **DECIDED** |
| Election-Only requires no Organisation Membership | EM-EO-001…004 | **DECIDED** |
| Membership grants no voting entitlement; Election owns the entitlement | EM-ENT-001/007 | **DECIDED** |
| Registration → ElectionMembership → derived Admission → VotingEntitlement / `canVote` | ADR_20261004_1549 | **DECIDED** |
| Anonymity | ADR-T11 | **DECIDED** |
| Full Membership: "removal" auto-suspends the ElectionMembership | EM-FM-003…006 | **DECIDED · DEFERRED** |

**Working results** carried from DP §16 A. These are **proposed for adoption by the Principal Reviewer and are not business decisions**:
- I2′: not in force from the termination's effective time;
- L1/L2: lapse is derived and resumable; it is not an ending;
- I1: the holder is constant (subject to D-TERM-5);
- REQ-INV-04: history is never destroyed by affiliation changes;
- N3: the number is organisation-issued;
- `[from, until)` is a convention.

## 3. What is genuinely open

| Group | Decisions |
|---|---|
| Identity | D-ID.1, D-ID.2 (with the conditional Model C) |
| Ending | D-TERM-1, D-TERM-3, D-TERM-4, D-TERM-5 (D-TERM-2 is derived) |
| Suspension | D-SUSP |
| Recognition | REC-0, REC-IMP, REC-INV (REC-AUTH deferred) |
| Constraints | I5, N-U |

---

## 4. D-ID.1: re-entry identity

> **When a former member returns to the same organisation after their previous membership has ended, is this a new Organisation Membership, or does the existing Organisation Membership continue?**

| | **Option A: new membership** | **Option B: the same membership continues** |
|---|---|---|
| Meaning | a new admission creates a new Organisation Membership; the previous one stays historical | the same Organisation Membership becomes in force again after an ended period |
| Termination | can remain final | final for a **period**, not for the identity |
| History | periods are naturally separate memberships | one membership holds **several effective periods**; history needs explicit periods |
| Artefacts | name the membership | name the membership **and** a date (or a period reference) |
| Entry paths | every path must create a new membership on re-entry | re-admission is a **lifecycle transition** (re-activation), not creation |
| Legacy fit | `UNIQUE(organisation_user_id)` cannot remain the domain rule; the approval path's intent fits | the one-row-per-affiliation design, invitation reuse and the import tutorial fit |
| Election | handled later in Q-FM-MAP; every option stays available | Q-FM-MAP option "bound to the specific membership" collapses into person-level evaluation |

**Evidence:**
- no DIRECT evidence either way;
- the legacy paths split 3 : 3 (FR §7);
- the guide's *"a new application is required"* fits both;
- the analogies (committee lineage, election SM2) favour A, but they are not proof.

**CLAUDE RECOMMENDATION — NOT DECISION:** no business recommendation; the evidence is balanced. As an architecture observation only: A keeps ending final and the invariants simpler.

**Decision owner:** PO / business. **Decision:** `OPEN — awaiting decision`.

## 5. D-ID.2: membership number on re-entry

> **When a former member returns, does the person keep the previous Membership Number or receive a new one?**

| Option | Meaning | Evaluation |
|---|---|---|
| **N1: retain** | the number belongs to the person-in-this-organisation and survives re-entry | common in association registers (general knowledge, **not repository evidence**) |
| **N2: reissue** | each admission gets a new number | fits the legacy "number generated at approval" intent and the type-prefixed numbers (FM-/AM-) |
| **N3: tenant-defined** | PublicDigit supports both; each organisation chooses | justified **only if** organisations on the platform actually differ. **No evidence of variation exists.** Not recommended as a default "flexibility" choice |

**Evidence:**
- the number is organisation-issued (organisations import their own: STRONGLY SUPPORTED);
- it is not shown in any UI or export today;
- nothing says what happens on re-entry.

**CLAUDE RECOMMENDATION — NOT DECISION:** no option; the repository holds no evidence. Ask the business. **N3 only if the business states that organisations differ.**

**Decision owner:** PO / business. **Decision:** `OPEN — awaiting decision`.

## 6. Identity × Number matrix

| Re-entry identity | Number | Result | Domain semantics | Complexity | History | Audit | Migration impact | Election | Contradiction |
|---|---|---|---|---|---|---|---|---|---|
| New membership | New number | **Model A** | one membership = one admission, one number | lowest | separate memberships | membership id | UNIQUE must change; numbers per admission | all Q-FM-MAP options open | none |
| New membership | Same number | **Model C** (register-entry concept) | memberships per admission; the number lives on a per-(person, organisation) register entry | +1 small concept | as A | membership id; the number spans memberships | as A + a number register | as A | none (needs a name: a terminology decision requirement) |
| Same membership | New number | **Model B′** | one membership, a new number per period | medium + odd | periods inside one membership | membership + period + number | multiple periods + numbers per period | as B | **unusual:** one membership carrying several numbers |
| Same membership | Same number | **Model B** | one membership, one number, several periods | medium | periods inside one membership | membership + date/period | fits current one-row design; needs a period history | the "bound" option collapses | none |

- **Natural cells:** A, B.
- **Unusual but valid:** C. It is valid **only** if the business answers "new membership" **and** "same number".
- **Unnecessary unless a business requirement exists:** B′.

## 7. D-TERM: ending a membership (decomposed)

### D-TERM-1: which acts end a membership?

| Act | Ends the membership? | Evidence | Option to confirm |
|---|---|---|---|
| lapse (term passes) | **no**: resumable by renewal | FACT + INDIRECT (established, DP §11 L2) | — (not asked again) |
| lapse beyond the renewal window | ❓ ends, or stays lapsed indefinitely | implementation only (`canSelfRenew` window) | **ask** |
| resignation | yes, at its effective date | guide (*"Resignation"*) | confirm |
| organisation-initiated termination (e.g. non-payment) | yes | guide (*"Non-payment"* as an end reason, distinct from lapse) | confirm |
| expulsion | yes (possibly with a ban on rejoining) | no evidence | **ask** |
| administrative correction | **not an ending**: see D-TERM-5 | — | — |

**CLAUDE RECOMMENDATION — NOT DECISION:** confirm that resignation, organisation-initiated termination and expulsion are three distinct ending acts. Lapse is not an ending. Correction is outside the ending model.

**Owner:** PO (model) + tenant (grounds). **Decision:** `OPEN — awaiting decision`.

### D-TERM-2: finality (derived; no separate decision)

Once an ending is effective, can **the same** membership be in force again?
- Under **D-ID.1 = A**: **no**, by construction. A returning person gets a new membership.
- Under **D-ID.1 = B**: yes, but **only** as a new period (re-activation). The ended period itself is final.
- In both cases a **successfully appealed** ending is governed by D-TERM-4. Under A1 the ending **never took effect**, so finality is not breached.

**Therefore D-TERM-2 = f(D-ID.1, D-TERM-4). No separate decision is needed.**

### D-TERM-3: withdrawal of a resignation

> If a resignation has been decided but takes effect at a future date, may it be withdrawn before it becomes effective?

- **W1:** yes, the pending ending is cancelled.
- **W2:** no, once decided it takes effect.
- **W3:** a tenant/statutory rule.

**Evidence:** none.

**CLAUDE RECOMMENDATION — NOT DECISION:** W1 or W3. W1 only changes a *pending* decision, so no ending ever took effect and no model conflicts with it.

**Owner:** PO / tenant. **Decision:** `OPEN — awaiting decision` (can be decided later).

### D-TERM-4: successful appeal

> If an effective termination or expulsion is successfully appealed, what is the effect?

- **A1, annulment:** the original decision is void, and the membership is treated as **never having ended**. The record keeps the annulled decision.
- **A2, restoration:** the membership **did** end, and is restored after an ended period. That leaves a gap in its history.
- **A3, new admission:** a new admission is required.

**A1 ≠ A2:**
- A1 rewrites the *validity* of the ending decision. The period is continuous, and finality is not breached.
- A2 accepts that the ending happened and re-opens the membership. That breaches finality, and it is coherent only under D-ID.1 = B.

**Evidence:** none.

**CLAUDE RECOMMENDATION — NOT DECISION:** A1. It works under every identity option and keeps endings final.

**Owner:** PO / tenant (appeal organ). **Decision:** `OPEN — awaiting decision` (can be decided later).

### D-TERM-5: administrative correction

There are two **different** questions:
- **(c1) was the membership ever valid?** If not, the record is **void** (it never existed in the domain), which is not an ending.
- **(c2) is a valid membership's record merely wrong** (dates, type, typo)? Then the record is **corrected**, and the membership continues unchanged.

A wrong *person* is a (c1) case: void + a correct new membership. That keeps I1.

**CLAUDE RECOMMENDATION — NOT DECISION:** treat both as corrections, never as endings, and record them as corrections with a reason.

**Owner:** PO. **Decision:** `OPEN — awaiting decision` (can be decided later).

## 8. D-SUSP: suspension

> **Does suspension change the membership's lifecycle or effectiveness, or is it purely a restriction of rights?**

| # | Dimension | **S1: suspension is a lifecycle state** | **S2: in force + restrictions** |
|---|---|---|---|
| 1 | term clock | may pause (the term is extended) | runs |
| 2 | dues | may stop | continue |
| 3 | member counts | may exclude | counted |
| 4 | quorum | may exclude | counted (attendance is a right that can be restricted) |
| 5 | services | withdrawn | restricted |
| 6 | committee participation | withdrawn | restricted (the committee also has its own suspension) |
| 7 | election participation | withdrawn; whether it counts as EM-FM-003 "removal" is FM-6 (deferred) | restricted |
| 8 | eligibility | false | false (via the restriction) |

**Decision rule:**
- **S1 is required iff the answer to any of rows 1–4 is "yes"** (term pauses, dues stop, not counted, not in quorum).
- Otherwise **S2** suffices, and it composes with the participation bans needed anyway (a disciplinary ban from one election).
- **The current `status='active'` filters are not business evidence.**

**CLAUDE RECOMMENDATION — NOT DECISION:** none until rows 1–4 are answered.

**Owner:** PO / business. **Decision:** `OPEN — awaiting decision`.

## 9. Recognition decisions

The four concepts stay separate: **Entry** (a record is created) · **Recognition** (the organisation accepts it) · **Effectiveness** (in force from or to a date) · **Authority** (who may recognise).

| ID | Question | Options | CLAUDE RECOMMENDATION — NOT DECISION | Owner | Decision |
|---|---|---|---|---|---|
| **REC-0** | Can a membership **exist without yet being recognised** (a pending membership)? | yes (a "pending" condition exists) / no (entry = recognition, or nothing exists yet) | **no:** an unconfirmed entry stays an application or an import *candidate*, not a membership. That keeps the lifecycle small | PO | `OPEN — awaiting decision` (**MUST NOW**: it fixes whether the state set needs "pending") |
| **REC-IMP** | Does importing a prior register recognise the imported memberships? | yes, if the import records its basis (source register, date, who) / no, confirmation is needed | yes, with the basis recorded | PO + tenant | `OPEN — awaiting decision` (later) |
| **REC-INV** | Does accepting an invitation recognise the membership? | the invitation (issued by someone authorised) is the recognition and acceptance is consent / acceptance needs a further confirmation | the invitation is the recognition; the membership is effective from acceptance | PO + tenant | `OPEN — awaiting decision` (later) |
| **REC-AUTH** | Which organisational authority is required? | — | **deferred:** the authority track (EQ-5, EM-OPEN-014) | PO + tenant | **DEFERRED** |

## 10. Multiplicity (I5)

> **Can one person have more than one non-terminal Organisation Membership with the same organisation at the same time?**

| Option | Evidence |
|---|---|
| **M1: no, at most one** | no counterexample found: the type is single-valued; associate/full are alternatives; youth/women wings are committees, not membership types; honorary is a type |
| **M2: yes, concurrent memberships allowed** | none |
| **M3: depends on type or statutes** | **none.** Not supported by any source; it is not recommended as generic flexibility |

**CLAUDE RECOMMENDATION — NOT DECISION:** M1.

**Status:** **CANDIDATE.** **Owner:** PO / ARB. **Decision:** `OPEN — awaiting decision`.

## 11. Membership number uniqueness (N-U)

> **Is the Membership Number unique within an organisation, or across all of PublicDigit?**

| Option | Evidence |
|---|---|
| **U1: per organisation** | organisations import their own numbers. Two associations both starting at 1001 is normal |
| **U2: global** | only the current implementation (`unique` across tenants) |
| **U3: configurable** | no evidence of need |

**CLAUDE RECOMMENDATION — NOT DECISION:** U1.

**Status:** **HYPOTHESIS, strongly supported. Not derived.** **Owner:** PO. **Decision:** `OPEN — awaiting decision` (later: it affects persistence and migration, not identity).

---

## 12. Decision Impact Matrix

| Decision | Identity | Lifecycle | History | Election | Migration | Aggregate | Persistence |
|---|---|---|---|---|---|---|---|
| D-ID.1 | HIGH | HIGH | HIGH | MEDIUM (Q-FM-MAP options) | HIGH | HIGH | DEFERRED |
| D-ID.2 | MEDIUM (C exists or not) | NONE | LOW | NONE | MEDIUM | LOW | DEFERRED |
| D-TERM-1 | NONE | HIGH | MEDIUM | DEFERRED (FM-5) | MEDIUM (`ended`, `archived` mapping) | MEDIUM | DEFERRED |
| D-TERM-2 | (derived) | (derived) | (derived) | (derived) | (derived) | (derived) | (derived) |
| D-TERM-3 | NONE | LOW | LOW | NONE | NONE | LOW | DEFERRED |
| D-TERM-4 | NONE | MEDIUM | MEDIUM | DEFERRED | NONE | LOW | DEFERRED |
| D-SUSP | NONE | HIGH | MEDIUM | DEFERRED (FM-6) | MEDIUM (`suspended` mapping) | MEDIUM | DEFERRED |
| Recognition (REC-0) | LOW | MEDIUM | LOW | NONE | MEDIUM (imported rows) | LOW | DEFERRED |
| I5 | MEDIUM | LOW | NONE | NONE | MEDIUM (legacy UNIQUE) | MEDIUM (cross-membership rule) | DEFERRED |
| N-U | NONE | NONE | NONE | NONE | MEDIUM | NONE | DEFERRED |

## 13. Minimum Decision Set

**MUST DECIDE NOW** (they block the canonical Membership identity/lifecycle model):
1. **D-ID.1:** re-entry identity.
2. **D-ID.2:** number on re-entry (it decides whether Model C exists).
3. **D-TERM-1:** which acts end a membership.
4. **D-SUSP:** suspension as a state or as restrictions.
5. **REC-0:** can a membership exist before recognition (is "pending" a state)?
6. **I5:** one non-terminal membership per person and organisation (a confirmation).

**CAN DECIDE LATER** (before commands are designed; they do not change the core model): D-TERM-3, D-TERM-4, D-TERM-5, REC-IMP, REC-INV, N-U.

**DEFER:** REC-AUTH / authority (EQ-5, EM-OPEN-014) · Q-FM-MAP + FM-5…10 (the Full Membership election phase) · MembershipType evolution (type change as a new membership; number type namespaces; `grants_voting_rights`) · published contracts · persistence · migration implementation · tenant time zone · backdating.

## 14. E0a-2 Exit Criteria

E0a-2 is complete when **all** of these hold:

1. **D-ID.1 decided** (identity semantics).
2. **Re-entry semantics decided:** D-ID.1, plus D-TERM-2 derived and recorded.
3. **D-ID.2 decided** sufficiently to say whether Model C exists.
4. **D-TERM-1 decided** (the core ending semantics).
5. **D-SUSP decided** sufficiently to fix the lifecycle state set.
6. **REC-0 decided** (whether a pending/unrecognised state exists).
7. **I5 adopted**, or **explicitly deferred** with a recorded reason.
8. **The DP §16 A working principles** adopted, amended or rejected by the Principal Reviewer.
9. **No unresolved contradiction remains** between the adopted decisions (EM-VOC-001, EM-ENT/EO/FM, SM2, ADR_20261004, ADR-T11) and the decisions above.
10. **Each decision is traced** by a requirement card (DP §13), updated with the decision record.
11. **Every legacy behaviour in DP §15 (F-1…F-12) is classified** against the decisions as one of: *conforming*, *defect*, *obsolete*, *awaiting a decision*.
12. **Only then** does canonical Membership modelling begin. The "can decide later" items must close before command design, and the "defer" items close in their own tracks.

---

## 15. Decision Cards

> Every card: **Status `OPEN — awaiting decision`**. Recommendations are **CLAUDE RECOMMENDATION — NOT DECISION**.

| Field | **DC-ID1** | **DC-ID2** | **DC-TERM1** | **DC-SUSP** | **DC-REC0** | **DC-I5** |
|---|---|---|---|---|---|---|
| Title | re-entry identity | number on re-entry | ending acts | suspension semantics | pending membership | one non-terminal membership |
| Business question | §4 | §5 | §7 D-TERM-1 | §8 | §9 REC-0 | §10 |
| Why it matters | fixes identity, history and finality | decides whether a relationship-level concept exists | fixes the transitions that end a membership | fixes the state set | fixes whether "pending" is a state | the cross-membership rule |
| Current status | OPEN | OPEN | OPEN | OPEN | OPEN | CANDIDATE |
| Evidence | FR §7; DP §6 | DP §5 | guide `0003:211-225`; DP §7 | none (implementation side effects excluded) | entry paths without a decision (DP §9) | no counterexample (DP §10) |
| Options | A / B | N1 / N2 / N3 | per act | S1 / S2 | yes / no | M1 / M2 / M3 |
| Recommended | none (architecture leans A) | none | three distinct ending acts; lapse is not one | none until rows 1–4 are answered | no | M1 |
| Consequences | §4 table | §6 matrix | lifecycle transitions; FM-5 later | state set; FM-6 later | the state set; import/invitation handling | admission guard; legacy UNIQUE |
| Dependencies | — | D-ID.1 (for the C cell) | — | — | — | D-ID.1 (trivial under B) |
| Affected contexts | Membership (+ Election later) | Membership | Membership (+ Election later) | Membership, Governance | Membership | Membership |
| Decision owner | PO / business | PO / business | PO + tenant | PO / business | PO | PO / ARB |
| Required input | §16 Q1 | §16 Q2 | §16 Q3 | §16 Q4 | §16 Q5 | §16 Q6 |
| Acceptance criteria | a re-entry rule stated in one sentence and applicable to every entry path | a number rule for re-entry (and whether it is uniform across organisations) | a list of ending acts, each with its effective-date rule | rows 1–4 answered | a yes/no with the treatment of unconfirmed entries | M1/M2/M3 chosen, with any exception named |
| Status | **OPEN — awaiting decision** | **OPEN — awaiting decision** | **OPEN — awaiting decision** | **OPEN — awaiting decision** | **OPEN — awaiting decision** | **OPEN — awaiting decision** |

**The later cards** follow the same form:
- DC-TERM3, DC-TERM4 (§7), DC-TERM5 (§7);
- DC-RECIMP, DC-RECINV (§9);
- DC-NU (§11).

All are **OPEN — awaiting decision**.

---

## 16. Questions for PO / Business

*(Plain language; no technical knowledge needed.)*

**Must answer now:**

1. **Coming back:** *"If someone's membership ended and they later join the same organisation again, is that the same membership continuing, or a new membership?"*
2. **Membership number:** *"When a former member joins again, do they get their old membership number back, or a new one? Is that the same for every organisation using PublicDigit, or do organisations handle it differently?"*
3. **Ways a membership ends:** *"Which of these end a membership: the member resigns; the organisation ends it (for example for unpaid fees); the member is expelled? Are these different from simply not renewing on time? And if someone doesn't renew for a long time, does their membership eventually end, or does it just stay 'not renewed'?"*
4. **Suspension:** *"While a membership is suspended: (a) does the membership period keep running, or is it paused? (b) does the member still owe fees? (c) do they still count as a member in member numbers? (d) do they count for a quorum?"*
5. **Not yet confirmed:** *"Can someone be 'a member, but not yet confirmed', or is a person either a confirmed member or not a member at all (only an applicant)?"*
6. **One membership at a time:** *"Can the same person hold two memberships in the same organisation at the same time (for example two different membership types), or always at most one?"*

**Can answer later:**

7. *"If a member announces a resignation for a future date, can they take it back before that date?"*
8. *"If an expulsion is overturned on appeal, is it as if the person was never expelled, or did their membership have a gap?"*
9. *"If a membership was recorded by mistake (the wrong person, or never valid), should it be removed as 'never valid' rather than 'ended'?"*
10. *"When you import your existing member list, are those people members immediately, or must someone confirm them?"*
11. *"When someone accepts an invitation to become a member, are they a member immediately?"*
12. *"Can two different organisations on PublicDigit both have a member number '1001'?"*

## 17. Deferred Decisions

| Deferred item | Track | Why it can wait |
|---|---|---|
| Authority / organs (REC-AUTH, EQ-5, EM-OPEN-014) | authority track | needed before commands, not before definitions |
| Q-FM-MAP, FM-5…10 | Full Membership election phase (EM-SEQ-001) | every D-ID option supports it; B narrows it (to be stated at D-ID.1) |
| Type change as a new membership; number type namespaces; `grants_voting_rights` | MembershipType evolution | does not change the identity core |
| Published membership facts | contract track | derived after the decisions |
| Persistence (constraints, indexes) | after the aggregate | the business rule comes first |
| Migration implementation | after the target model | needs all of the above |
| Tenant time zone; backdating / retroactivity | temporal track | no requirement yet |
| Model-neutral defect fixes (DP §15: F-4, F-6, F-8, F-11, F-12, first-admission F-1/F-5) | separate authorisation | **not authorised here** |

## 18. Traceability

- DP §§1–17 (decision pack);
- FR §§2–24 + correction;
- DR §§0–19;
- R §1;
- Q (EQ-1…16 ↔ the cards here):

| EQ | Card(s) here |
|---|---|
| EQ-1 / 2 | REC-0, REC-IMP, REC-INV |
| EQ-3 | I5 |
| EQ-4 | D-TERM-1, D-SUSP |
| EQ-5 | REC-AUTH (deferred) |
| EQ-7 | D-TERM-1…5 |
| EQ-8 | D-ID.1 |
| EQ-9 | D-SUSP |

- Adopted: EM-VOC-001/002, EM-EO-001…004, EM-ENT-001/007, EM-FM-003…006, EM-SEQ-001, SM2, ADR_20261004_1549, ADR-T11;
- guide `docs/publicdigit/membership/20260403_0003_membership_management.md:211-225`.

---

**STOP.** Decision Gate returned to the Principal Reviewer and the PO. Nothing implemented, migrated, repaired or committed.

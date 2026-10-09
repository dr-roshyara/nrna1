# Organisational Participation: Semantic Contract, DRAFT (gaps G-1 … G-5)

**Date:** 2026-10-09 · **Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable)
**Instruction:** `docs/publicdigit/membership/architecture/brainstorming/20261009_2104_implementation.md` §7
**Status:** **DRAFT, research. Not approved and not implementable.** It ratifies nothing and creates no Governance code.
**Baseline (reused, not repeated):** `2026-10-09-organisational-participation-domain-research.md`: Person ≠ Member; Position ≠ Tenure ≠ Appointment; authority derived; the derived eligibility intersection; RD-1 … RD-5; BQ-P1 … BQ-P7.
**Conditional on:** the *recommended* (unratified) answers RD-1 = Person, RD-2 = M2 (tenures inside participation), RD-3 = derived intersection, RD-4 = G-A. A different ratified answer invalidates the affected clauses, which are marked ⟨RD-n⟩.

## 1. Vocabulary (and one collision resolved)

| Term | Meaning | Owner |
|---|---|---|
| **Participation** | a Person's episode of belonging to a Committee ⟨RD-1, RD-2⟩ | Governance |
| **Position** | an office of a Committee with capacity and an eligibility rule ε(s); it outlives its holders | Governance |
| **Tenure** | a Person holding a Position over an interval; opened by an Appointment act, closed by an end-of-tenure act or by its term | Governance |
| **Mandate** | the scope of authority conferred on a tenure or a delegation | Governance |
| **Authority** (permission) | what a person *may* do at t: `HasAuthority(p, a, t)` (baseline §12) | Governance |
| **Duty** (responsibility) | what a person *must* do or answer for. It attaches to a tenure, but its extent differs from the tenure's (§3, G-1) | Governance |
| **Charge** (financial obligation) | an amount due from a Member for a billing period under a fee rule | Finance |
| **Payment** | a settlement fact; never derived and never altered by other contexts | Finance |
| **Access** | a service's permission to read or act (forum, documents, meetings, tasks, notifications); a **projection** | each service context |

**Collision:** "obligation" was used both for duties (Governance) and for money owed (Finance). **Proposal:** use *Duty* and *Charge*; never use the bare word "obligation" in code.

## 2. Hypothesis under test

`Membership → Participation → Position / Tenure / Mandate → Authority and Duties → Policy evaluation → Service access`, with charges as a separate Finance branch from Membership predicates.

**Result:** the hypothesis survives with two corrections:
- **Duties do not follow the tenure's interval** (G-1).
- **Charges must be derived targets reconciled into an append-only ledger.** They are neither written once nor silently recomputed (G-2).

## 3. Findings per gap

### G-1: Duty vs authority

**Requirement question:** are duty and authority one relation?

**Counterexamples (scenarios; an argument, no computation needed):**
- **S-D1, delegation.** The chair delegates signing authority to the secretary for one month. During the month the secretary *may* sign. Under the common accountability principle the chair still *answers for* it. Authority moved, duty did not. ⇒ One relation cannot hold both "Auth(secretary)" and "¬Duty(secretary)".
- **S-D2, duty without authority.** The treasurer must prepare the budget, but only the assembly may approve it. Duty(treasurer, budget) ∧ ¬Auth(treasurer, approve).
- **S-D3, duty outlives the tenure.** The treasurer resigns in March. The year's accounts are still theirs to answer for until the assembly grants discharge in June. Authority ends in March; the duty ends at discharge.

**Consequence (temporal extents):**

```text
Authority(p, a, t)   ⊆  Effective(p, s, t)                                   (or a valid delegation)
Duty(p, d, t)        ⊇  Held(p, s, t),  and may continue after the tenure until a discharge act
Delegation           transfers Authority, never Duty                          (IP-2)
```

**Open question BQ-P8:** does a duty end at the tenure's end, or only by a discharge act (or a rule)? The answer is statute-specific.

### G-2: Charge vs payment

**Model** (scratchpad `participation2/g2_obligations.py`):
- months t ∈ [0, 6);
- one member: Admit@a, an optional End@e, and at most one later correction recorded at r (annul the End, or annul the Admit);
- fee rule = 1 unit per month in force;
- payments are made in the month charged, or never.

**Compared:**
- **STORED:** the legacy shape. A monthly job charges if the member is in force *as known at t*; an End waives unpaid charges (`app/Models/Member.php:182`).
- **DERIVED target:** the rule applied to the final facts.

**Results:**

| Property | Result (222 histories) |
|---|---|
| STORED charges equal the final truth | **wrong in 140 / 222**: 85 overcharge, 55 undercharge. Smallest: Admit@0 annulled @1 leaves 1 charge vs truth 0 |
| DERIVED equals truth | 222 / 222 **by construction**, so this is not evidence of anything beyond the definition |
| payments exceed the corrected charge (credit) | **70 / 222**: credit must be representable; a refund is a Finance decision |

**Bounds and assumptions:**
- one member, T = 6, a single correction, a linear fee rule;
- no pro-rata, waivers or levies.
- **Not represented:** accounting periods closing; partial payments.

**Design consequence:** accounting forbids silently recomputing *posted* history. So the correct shape repeats Membership's own pattern:
- the target charge is **derived** from Membership predicates and the fee rule;
- Finance's ledger is **append-only**;
- when the target changes (for example after an annulment), Finance posts an **adjustment** (target − posted). It never mutates or deletes a posting.

**Legacy counterexample:** `Member::endMembership()` waiving fees is a **cascade write from Membership into Finance state**. It does not revive when the End is annulled. Its disposition is already DELETE in the blueprint.

**Open questions:** BQ-P10 (credit and refund policy: Finance / PO) · BQ-P11 (fee basis: per started month or year, pro-rata, the waiver act).

### G-3: Service access

**Evidence:**
- No forum, document or meeting service exists in `app/`.
- The only access rule is the legacy `MemberStatus::canParticipateInForums()` (`app/Contexts/Membership/Domain/ValueObjects/MemberStatus.php:216`). It treats access as a **membership-status attribute**, which is the shape rejected here.
- The newsletter reads `members.status='active'` (`NewsletterService.php:143`).

**Model** (scratchpad `participation2/g3_g4_access_vacancy.py`):
- months [0, 6);
- one tenure [x, y) in a position with ε = "holder is a member";
- membership End@e, optionally annulled at r.

**Compared:**
- a **stored ACL** maintained by event handlers: grant at tenure start; revoke on tenure end and on the membership End; no annulment handler;
- a **derived** access = Held ∧ ε.

| Property | Result (432 histories) |
|---|---|
| stored ACL equals derived access at every month | **diverges in 180 / 432**, 476 wrong months in total |

These are counts of enumerated histories in a deliberately small, uniform domain. They are not frequencies of real events.

**Contract clause IP-4 (rebuild property):** deleting every access projection and recomputing it from Membership predicates, effective tenures and the service policy yields the same access at every t. **An access store is a cache, never evidence of authority.**

**Open question BQ-P9:** may former members or former office holders read historical content (minutes, posts)? The access policy can express either answer; the projection does not decide it.

### G-4: Succession and vacancy

**Two vacancy notions:**
- **legal:** `¬∃ Held tenure`;
- **functional:** `¬∃ Effective tenure`.

They differ in 100 / 432 histories of the G-3 model: exactly where a holder is ineligible but still holds the office.

**BQ-P3 (open; lapse vs suspend) is observable only with re-entry.**
- In the extension `g4_reentry.py` (End@e then re-admission@g ≥ e, a new period under the same Member as ratified), LAPSE and SUSPEND differ in **110 / 540** histories. Smallest: tenure open from 0, End@1, re-admit@2. SUSPEND revives the office; LAPSE does not.
- Without re-entry they never differ (0 / 432), because ineligibility is then permanent.
- **Both are derivable without any write.** LAPSE is "the first ineligible month ends the tenure". A recorded vacancy act is needed only to appoint a successor.

**Evidence when a tenure ends (requirement):** an end-of-tenure fact carries its kind (resignation, removal, term expiry, death), its effectiveAt, and its provenance (actor and authority evidence). That is the same fact discipline as Membership. Ineligibility is **never** recorded as a tenure end; it is derived.

**Succession:** a Position may name an automatic successor rule (for example, the deputy acts). That rule yields an *acting* authority, derived, and is distinct from a new Appointment.

### G-5: Ownership and published language

| Concept | Owner | Published to | Form |
|---|---|---|---|
| InForce, Suspended, type, admission dates (Membership predicates) | Membership | Governance, Finance, Election (Full mode), services | **query port** (MembershipQueryPort), never events-as-truth |
| Participation, Tenure, Mandate, Authority, Duty, vacancy | Governance | Election (`OrganisationalAppointmentAuthority`), services, Finance (approver evidence) | query port: `EffectiveTenures(p, t)`, `HasAuthority(p, a, t)` |
| Charge target, ledger, payment, credit, fee standing | Finance | Election (the Full-mode "fees paid or exempt" rule, DS-5 standing), services | query port: `FeeStanding(m, t)` |
| Access policy and projection | each service context | — | consumes the three ports; owns nothing upstream |

**No context writes into another (IP-7).** Events between contexts are workflow triggers only, such as "re-evaluate access" or "post an adjustment".

## 4. Invariants (hypotheses; status in brackets)

| ID | Invariant | Status |
|---|---|---|
| IP-1 | No authority without an effective tenure or a valid delegation | baseline §12; supported |
| IP-2 | Delegation transfers authority, never duty | G-1 S-D1; business confirmation |
| IP-3 | A duty ends by a discharge act or rule, not by the tenure's end | **BQ-P8 open** |
| IP-4 | Access projections are rebuildable (no authority in an access store) | G-3; supported |
| IP-5 | Payments are never altered by other contexts; corrections produce adjusting postings; credit is representable | G-2; supported |
| IP-6 | A Position outlives its holders; legal and functional vacancy are both derived | G-4; supported |
| IP-7 | No cross-context cascade writes | baseline §14 (140 / 476) + G-2 + G-3; supported |

## 5. Not decided here

- RD-1 … RD-5;
- BQ-P1 … BQ-P11 (P8 … P11 are new, raised by these gaps);
- the aggregate design of Governance;
- any persistence;
- the migration of the four legacy committee models;
- ML: only advisory, for example clustering the seven legacy role vocabularies for a migration mapping. It never decides authority or access.

## 6. Exit

This draft becomes a contract only after:
- RD-1 … RD-4 are ratified;
- BQ-P1 … P3 and P8 are answered (the Governance owner through the PO);
- the Principal Reviewer approves it.

Until then: **no Governance or Participation implementation.**

## 7. Revision 1 (2026-10-09, after the Principal Reviewer's 2122 review)

**Models retained** in `docs/publicdigit/membership/architecture/models/` (README states domains, assumptions and limits).

**G-2 strengthened** (`g2_ledger.py`): an **independent** interval-set oracle; ≤ 2 corrections; waivers; accounting close; partial payments.
- **Design H** (derived target + append-only ledger + adjusting postings dated in the open period) matched the oracle **per service month** in all 42,075 generated histories, and never mutated a closed posting.
- STORED (legacy shape) was wrong in 38,106. A naive in-place recompute would mutate closed months in 14,433. Credit arose in 14,883.
- **This is evidence about H's reconciliation mechanism, not a tautology:** H accumulates adjustments over successive knowledge states, while the oracle is a separately written resolver over final facts.
- **The oracle found one semantic point:** a correction of an annulled End. Ratified **BD-REV** (inert) decides it, and the oracle now follows it.

**Classification of results:**

| Class | Items |
|---|---|
| Demonstrated counterexamples | S-D1 … S-D3 (G-1); the stored-charge, ACL and naive-recompute divergences (G-2, G-3) |
| Bounded-model results | H matches the oracle per month (G-2); lapse ≠ suspend only with re-entry (G-4) |
| Hypotheses | IP-1 … IP-7; design H for Finance; access as a projection |
| Ratified rules used | re-entry = a new period under the same Member; BD-REV; INV-EP |
| Unanswered | RD-1 … RD-5; BQ-P1 … BQ-P11 |

**Remaining falsification questions (not yet modelled):**
1. **Refund versus carried credit:** does H stay correct when a refund settles a credit and a later correction reinstates the charge?
2. **Pro-rata and partial-month fees under date corrections:** does per-month equality still hold under a non-linear rule?
3. **Delegation chains:** does duty retention survive re-delegation (A → B → C), and what happens if A's tenure ends mid-delegation (IP-2 / IP-3)?
4. **Discharge refused or deferred:** is a duty open indefinitely? BQ-P8 needs a statute.
5. **Former-member access to historical content:** a policy decision (BQ-P9). A model can only show both policies are expressible.
6. **Succession under simultaneous vacancies** in a position with capacity > 1 (BQ-P1).
7. **Admissibility filter:** rerun every model on kernel-admissible histories only, using the PHP kernel as the generator's filter, so that no result depends on a history the domain refuses.

## 8. Revision 2 (2026-10-09, `20261009_2145…`): admissibility and falsification

Details are in `docs/publicdigit/membership/architecture/models/README.md`, Revision 2.

- **G-4 (lapse vs suspend) holds on kernel-admissible histories:** all 540 were accepted by the real kernel; the kernel's membership equals the model's in 540 / 540; the two policies differ in 110. BQ-P3 remains a real decision.
- **G-2 holds after filtering.** Two new design rules for any future Finance contract (hypotheses, Finance-owned):
  - **IP-5a:** adjusting postings must carry an idempotency key made of **(service month, Membership log version)**. A content-based key fails under BD-REV; there is a counterexample.
  - **IP-5b:** reconciliation must be serialised per member, or keyed (IP-5a). Unkeyed concurrent reconciliation double-posts.
- **BQ-P10 sharpened:** "refund credit immediately" leads to money being owed again after a reinstating correction in 486 bounded histories. A carry-forward credit policy avoids the round trip. This is a business choice.
- **G-3:** the access model depends on annulment, so it is UNCHK until Annul is implemented in the kernel. Its conclusion rests on ratified-but-unimplemented semantics, and is labelled so.
- **Admissibility limit:** every result that depends on Annul / Correct is classified UNCHK. Promoting it requires those fact kinds in the kernel (a future lifecycle slice), not a change to the models.

## 9. Pointer (2026-10-09)

Committee responsibility, organ mandates, decision procedures and the Membership boundary are researched in `2026-10-09-committee-responsibility-authority-and-membership-boundary-discovery.md`. It adds CI-1 … CI-9 and CQ-1 … CQ-10. This draft remains conditional on those questions as well.

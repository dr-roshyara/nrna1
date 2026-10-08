# E0a-2 Question Register: consolidated (MQ · EQ · SQ · FM)

**Type:** Question register + decision gates · research · **decides nothing** · **Date:** 2026-10-08
**Companion:** `2026-10-08-membership-architecture-reconciliation.md` (the decision hierarchy H-/I-/P- and the conflicts C-)
**Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable)

> **Why this register exists:** three question sets used colliding IDs.
> - Discovery Part 1 **MQ-0…MQ-10**;
> - V4 **MQ-1…MQ-4**, with different meanings (for example V4 MQ-3 = multiplicity, Part 1 MQ-3 = authority);
> - brainstorm 0041 **SQ-1…SQ-6**.
>
> From now on, **E0a-2 questions use `EQ-n`**. Old IDs are **kept** and mapped, never deleted. **FM-1…FM-15 / W-1…W-8** (the Full Membership register, 2026-08-12) remain the canonical IDs for the *election effects* of membership changes.
>
> **Decision classes (mandatory):**
> - **A:** platform invariant (PO / ARB / architecture governance);
> - **B:** platform canonical model with tenant parameters (the platform defines the model; the tenant supplies values);
> - **C:** tenant statutory rule (the organisation defines it; PublicDigit stores it as evidence/configuration);
> - **D:** implementation choice (engineering, after the domain decision).
>
> **Answer forms:** for class B and C questions the PO decides **the model and the parameter space**, never a single organisation's answer (the discovery is generic, per the PO).

---

## 1. The register

| ID | Question | Prior IDs (mapped) | Class | Owner | Prio | Exit criterion | Gate |
|---|---|---|---|---|---|---|---|
| **EQ-1** | **When does an Organisation Membership come into existence?** (application submitted · admission decision · first effective date · first payment · registry entry) | ← V4 MQ-1 · ← Part 1 **MQ-1** (approval vs approval+payment) · ← V4 G1 · ← V4 OC-1, OC-9 | **A** (the canonical existence event) + **B** (whether payment is a precondition is a tenant parameter ❓) | PO | **1** | A testable rule *"m exists iff …"* is written, with one requirement card, and the PO act is recorded | G1 |
| **EQ-2** | **Are existence, recognition and effectiveness one act or separable?** (can m exist before it is recognised, or before its effective date?) | ← V4 MQ-2 · ← V4 OC-2 | **A** | PO + ARB | **1** | The validity matrix (V4 §6.6) is filled for {exists, recognised, effective} with a ✓/✗ per cell | G1 |
| **EQ-3** | **May one person hold several Organisation Memberships in the same organisation, sequentially or overlapping?** If overlap is forbidden, which consistency applies (synchronous vs reconciled)? | ← V4 MQ-3 · ← V4 M-7 / MP-3 · ← V4 G3 | **A** (overlap rule) + **D** (enforcement mechanism) | PO (rule), engineering (mechanism) | 2 | Rule + permitted violation window + override authority recorded | G3 |
| **EQ-4** | **What is the canonical Membership lifecycle:** which states exist, which transitions, what is automatic, what is final? | ← V4 MQ-4 · ← V4 OC-7 · ← Part 1 **MQ-6** (expiry) · ← Part 1 **MQ-7** (ending final?) · ← Part 1 X-8 (three vocabularies) | **B** (the platform fixes the states; the tenant sets parameters such as grace days) | PO | **1** | A state machine (states, transitions, automatic vs decided, final states) with one card per transition | G4 |
| **EQ-5** | **Who has authority over membership acts** (admit, recognise, suspend, end, renew, correct)? Who is the "Organisation Chief"? | ← Part 1 **MQ-3** · ← **EM-OPEN-014 / W-8 / FM-4** · ← 0041 A1 | **C** (who in the tenant) on an **A** frame (every act needs an authorised actor, a decision and evidence) | PO (frame); tenant (assignment) | **1** | An authority model: the act types × the role or organ that may perform each, with how the tenant configures it, and the evidence recorded | G5 |
| **EQ-6** | **How is a person admitted to membership?** Is an application always required, or configurable per type? Can an admin add directly? Do invitations count? | ← Part 1 **MQ-2** · ← V4 OC-3 · ← `requires_approval` (unused) | **B** | PO | 2 | Admission paths enumerated, each with its authority (EQ-5) and evidence | G1 |
| **EQ-7** | **How does a membership end?** Resignation vs expulsion vs lapse vs death; reason required; appeal? | ← V4 OC-4 · ← Part 1 **MQ-7** · ← **FM-5 / W-7** (which act is "removal") | **B** (end kinds) + **C** (the grounds, the appeal body) | PO | **1** | End kinds defined. **Each is mapped to FM-5** (is it "removal" in the EM-FM-003 sense?) | G4 |
| **EQ-8** | **Rejoin:** is re-joining the same membership resumed or a new one? Does tenure carry over? | ← V4 OC-5 · ← Part 1 MQ-7 | **A** (identity of m) + **B** | PO | 3 | Rule recorded; consistent with EQ-3 | G3 |
| **EQ-9** | **Suspension on the membership side:** a lifecycle **state** or a time-bounded **restriction**? Who imposes it? Is organisation-side suspension "removal" for EM-FM-003? | ← V4 OC-8 · ← **FM-6 / W-5** · ← 0041 SQ-4 (disciplinary) | **A** (representation) + **C** (grounds) | PO + ARB | **1** | Representation chosen. **The FM-6 answer is derivable from it** | G4 |
| **EQ-10** | **Is a membership's term derived from its assigned type** (duration at grant/renewal)? May a member's type change mid-term, and with what effect on the term? | new (discovery §3.3) · ← I-06 (approval sets no expiry) · ← Part 1 MQ-6 | **B** | PO | 2 | An invariant stated or rejected, with one card | G4 |
| **EQ-11** | **Amend EM-VOC-001?** The adopted text puts *type, fees and term* inside the Member concept. Keep it, or split (type **assignment** + term inside; type **definition** + fee ledger outside)? | C-2 · ← V4 naming rule 4 | **A** | PO | 2 | Either EM-VOC-001 is confirmed, or an amendment is adopted (a PO act) | G0 |
| **EQ-12** | **Vocabulary acts:** (a) keep "Member" or adopt "Organisation Membership" + "member = role"; (b) name the affiliation concept (**OrganisationAffiliation**); (c) the authorization role value `'member'` | C-1 · Part 1 **MQ-9** · Part 1 L-1 | **A** | PO | 2 | Terms adopted in a PO act; glossary entries with tests | G0 |
| **EQ-13** | **"Organisation Statutes" (Satzung)** as the term for the tenant's own rules, reserving "Constitution" for PublicDigit governance | reconciliation §9 · Principal Architect ruling 2026-08-13 | **A** | PO | 3 | Term adopted | G0 |
| **EQ-14** | **Cross-tenant disclosure:** adopt INV-TEN-1? Is **account existence** (*"user does not exist in the platform"*) itself protected? | reconciliation §10 | **A** (invariant) + **D** (enforcement) | ARB (+ PO for account existence) | 2 | Invariant adopted or rejected. Account-existence policy recorded | G5 |
| **EQ-15** | **Election admission: bound or discretionary?** (Is everyone who meets the criteria registered unless a named ground applies, or does the election committee choose?) | ← 0041 **SQ-2** · PI-2 · Blueprint §21 (presented as settled: **not settled**) | **A** | PO | **1** (integrity) | Rule adopted. If bound: the list of refusal grounds + the evidence. **Must respect ADR: no stored admitted flag** | G6 |
| **EQ-16** | **The reference date and the post-registration effect of facts:** at which date is eligibility evaluated? After registration, does a change in a criterion fact (dues, committee, ban, type) have no effect, suspend, or terminate? | ← 0041 **SQ-1** (INV-SEL-1/2) · ← FM-6/7/9 · ← W-3 | **A** (the frame; must respect EM-ENT-004) + **B** (per-criterion effect) | PO | 2 | Reference-date rule + an effect table per criterion family | G6 |
| *(carried)* | SQ-3 committee level = a committee property or a jurisdiction · SQ-5 who adopts an election's selection contract · SQ-6 guardrails on criteria (age, gender) | 0041 | A/B | PO | 3 | — | G6 (phase E2) |
| *(carried)* | Part 1 **MQ-0** (product promise) · **MQ-4** (paid / exempt / waived) · **MQ-10** (auto-enrolment, now an Election policy) | Part 1 | product / B | PO | 3 | — | E0b / E2 |
| *(consumed, not re-asked)* | **FM-1…FM-15**, **W-1…W-8**, EM-OPEN-003, `PBDIGIT-66` D-1…D-6, ADR G8/G10/G11, `PBDIGIT-221` decisions 1–4 | existing registers | — | as registered | — | — | — |

### 1.1 Old → new map (nothing deleted)

| Old ID | Meaning | Now |
|---|---|---|
| Part 1 MQ-0 | the product promise | carried (product) |
| Part 1 MQ-1 | active on approval vs payment | **EQ-1** |
| Part 1 MQ-2 | approval always required? | **EQ-6** |
| Part 1 MQ-3 | membership authority | **EQ-5** |
| Part 1 MQ-4 | paid / exempt / waived | carried (E0b) |
| Part 1 MQ-5 | source of voting rights | **answered in direction** by PI-1…4 (election-owned criteria). Formal adoption pending → EQ-15/16, SQ-5 |
| Part 1 MQ-6 | expiry, grace, renewal | **EQ-4**, **EQ-10** |
| Part 1 MQ-7 | end final? rejoin? | **EQ-7**, **EQ-8** |
| Part 1 MQ-8 | committee membership | **answered in direction:** Governance (pending the status of ADR-F3.2 → EQ-5 frame) |
| Part 1 MQ-9 | the `member` role / affiliation term | **EQ-12** |
| Part 1 MQ-10 | auto-enrolment | carried (Election policy, E2) |
| V4 MQ-1 / MQ-2 / MQ-3 / MQ-4 | existence / recognition / multiplicity / lifecycle | **EQ-1 / EQ-2 / EQ-3 / EQ-4 + EQ-9** |
| 0041 SQ-1 / SQ-2 / SQ-4 | post-admission effect / bound admission / disciplinary ban | **EQ-16 / EQ-15 / EQ-9** |

---

## 2. Decision gates (redesigned)

Each gate is defined by: question · owner · required evidence · dependencies · exit criterion · decision record · status.

| Gate | Questions | Owner | Required evidence | Depends on | Exit criterion | Decision record | Status |
|---|---|---|---|---|---|---|---|
| **G0: Vocabulary** | EQ-11, EQ-12, EQ-13 | PO | EM-VOC-001…003 text; reconciliation C-1/C-2, §7, §9 | — | The three terms adopted or rejected, with glossary entries carrying a definition + test | a PO act appended to Manifesto §4 (or a Membership catalogue, if one is created) | 🔴 OPEN |
| **G1: Existence & admission** | EQ-1, EQ-2, EQ-6 | PO (+ ARB for EQ-2) | the current flows (application, approval, `addMember`, invitation, import); the business case; the conformance evidence (an approved member is never eligible) | G0 (only its name) | An existence rule + the validity matrix + the admission paths, each as a requirement card | PO decision record + cards in the discovery doc §4 | 🔴 OPEN |
| **G2: Temporal semantics** | TC-01 adoption; date vs instant; the tenant time zone; valid vs recorded time; retroactive correction | ARB (A) | V4 §5; `joined_at` / `membership_expires_at` usage | G1 | `[from, until)` adopted; per-fact types listed; the time-zone source defined | ARB record | 🟡 PROPOSED (V4 TC-01) |
| **G3: Multiplicity & rejoin** | EQ-3, EQ-8 | PO (rule), engineering (mechanism, D) | `members.organisation_user_id` is **unique** (one Member per affiliation today); soft-deletes | G1 | Overlap rule + consistency latency + override authority + reconciliation owner | PO record; engineering ADR for the mechanism | 🔴 OPEN |
| **G4: Lifecycle** | EQ-4, EQ-7, EQ-9, EQ-10 | PO (+ ARB for representation) | the three status vocabularies; `ExpireMemberships`; renewal; dormant `endMembership`; SM2 as the **election-side** precedent | G1, G2 | The state machine + automatic-vs-decided table + final states + type→term rule. **FM-5 and FM-6 answers derivable** | PO record; one card per transition | 🔴 OPEN |
| **G5: Authority & tenancy** | EQ-5, EQ-14 | PO (frame) · tenant (assignment) · ARB (INV-TEN-1) | `MembershipPolicy` (owner/admin); `manageMembers` undefined; EM-FM-005 / EM-OPEN-014; ADR §3 (the election-side authority precedent) | G1 | An authority model (act × organ) + how it is configured + evidence fields; INV-TEN-1 decided | PO + ARB records | 🔴 OPEN. **Moved up:** V4 said G5 *"may remain provisional"*, but MS-3 / R-M2/6/7 cannot be specified without it |
| **G6: Election selection (phase E2)** | EQ-15, EQ-16, SQ-3/5/6 | PO | ADR_20261004 (the chain is fixed); `voter_source_strategy` (`PBDIGIT-66` D-5 OPEN); `PBDIGIT-221` | G1, G4, G5; **EM-SEQ-001** (Full Membership deferred). **EQ-15 also applies to Election-Only** | Bound/discretionary decided; reference-date rule; per-criterion effect table | PO record; ADR amendment **only if** it touches ADR_20261004 | 🔴 OPEN |

**Ordering:** G0 (names) ∥ G1 → G2 → G3 ∥ G4 → G5 runs **in parallel with G1** (authority is needed for G1's admission cards) → G6.

**Implementation gate (unchanged):** no Membership implementation before G1, G3, G4 and G5 close **and** a Full Membership phase is authorised (EM-SEQ-001). **Defect stories** (`PBDIGIT-257`, `-202`) remain independently authorisable.

---

## 3. The single highest-value next decision

**EQ-1 (+ EQ-2): when does an Organisation Membership exist, and is that the same act as its recognition?**

Why this one comes first:
- every lifecycle state (EQ-4), every authority (EQ-5: *who* performs the creating act), multiplicity (EQ-3) and `HasMembership` (reconciliation §6.3) are defined **relative to it**;
- it also decides Part 1's X-2 contradiction (approval vs payment);
- it explains the most severe conformance defect: an approved member can never be eligible.

**Runner-up, outside Membership:** **EQ-15** (bound vs discretionary election admission). It is an **integrity** decision that applies to Election-Only **now**, in the current phase.

---

**Update (2026-10-08, additive):** the development report (`2026-10-08-e0a2-membership-development-report.md` §19) revises §3's recommendation on evidence found later the same day. EQ-1 is largely answered by practice (existence = the admission act). The smallest decision that unlocks the most is now **EQ-7 + EQ-8** (termination final; rejoin = new identity; lapse resumable). It unlocks EQ-4, EQ-3/INV-MULT-1 and FM-5. **Both remain the Principal Reviewer's / PO's call.**

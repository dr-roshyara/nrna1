# Organisational Participation: Domain Research (+ Membership Slice 0 closure)

**Date:** 2026-10-09 · **Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable)
**Status:** RESEARCH. **Not a decision and not an ADR.** It implements nothing: no committee, participation or position class, no table, no migration of `MembershipLineage`.
**Commission:** `docs/publicdigit/membership/architecture/brainstorming/20261009_0756_implementatin_02.md` (with `20261009_0741_mebmership_and_roles.md`). The PO's instruction was "follow the prompt if you agree; write your disagreement". The disagreements are in §0.
**Evidence:**
- **repository code inventory** (code-semantics sweep);
- **corpus inventory** (ADRs, the Election Manifesto, the 2026-10-08 Membership corpus, the brainstorming files);
- **bounded computational models** (scratchpad `participation/`, written test-first; research code, not product code).

Every repository claim carries `path:line`.

---

## 0. Where I disagree with the commission

| # | Commission says | My position | Evidence |
|---|---|---|---|
| X-1 | the relationship chain is **Member → Participation** (0741 diagrams; 0756 §3, "Member M123 → Committee …") | **The subject of participation is the Person, not the Member.** Organisation membership is an **eligibility condition per position**, not the owner of the relationship | non-members participate today: `organisation_participants.participant_type ∈ {staff, guest, election_committee}` (`database/migrations/2026_04_05_000003…:15`); scrutineers are imported as `election_committee` (`ParticipantImportService.php:166`). Ex-officio membership runs the other way: office ⇒ member (`2026-10-08-membership-final-proof.md` §14.9). Anchoring participation on `MemberId` would make these unrepresentable |
| X-2 | candidate relation `Participation ⊆ P × O × C × R × T` (§14) | **The role must not sit inside the participation relation.** A role/position is its own temporal object (a tenure) | §21: the role-as-attribute representation is lossy in 369 / 625 bounded cases; legacy `updateRole()` overwrites history (`CommitteeAssignment.php:261-263`) |
| X-3 | §11 asks for one global choice between Models A–D (does Governance require Membership?) | it is a **per-position eligibility rule**, not one global model. The architecture must support A, C and D at once | the election committee and scrutineers (non-members) vs board offices (members), both in the same organisation (above) |
| X-4 | §2.2 "check whether PBDIGIT-258 is correctly indexed" | it **cannot** be indexed without committing another session's uncommitted edit to `backlog/README.md` (it adds the missing `PBDIGIT-256` row). Needs the PO's word: commit that row too, or wait | `git diff docs/publicdigit/backlog/README.md` |
| X-5 | §2.3 "do not proceed unless the conditions are genuinely closed **or explicitly accepted**" | agreed. I can recommend, but **cannot accept my own conditions**, so Slice 0 stays GREEN WITH CONDITIONS until the PO / Principal Reviewer acts | standing rule: engineering never accepts its own work |

Everything else in the commission I agree with and follow.

---

## Part I: Membership Slice 0 closure

### I.1 D-1 (`DuplicateFactId`)

| Step | Analysis |
|---|---|
| **Business necessity** | none directly: no business person speaks about fact ids. Indirectly, every later act **refers to an earlier fact**: End → its period; Correct / Annul / Void → their target (ratified correction algebra, MA §9). A reference that can denote two facts makes these acts ambiguous |
| **Mathematical necessity** | **yes.** The correction algebra needs `target(f)` to be a **function** (a fact id denotes exactly one fact). The Slice 0 contract's Theorem 1 (Admit ↦ period is a bijection) presupposes injective FactIds. The causal-closure D(c) is defined over target references. Without uniqueness, all three are ill-defined |
| **Architectural consequence** | uniqueness **within one Member's log** is an aggregate-local identity property: the entities and facts inside an aggregate must be distinguishable for its own invariants. **Global** uniqueness (across Members and tenants) is a persistence concern: a primary key in Slice 1b |
| **Implementation consequence** | keep the guard and the refusal. Slice 1a must check **idempotency before decide**, so that a *retried* command (same idempotency key, same FactId) returns its original outcome (`AlreadyApplied`) instead of `DuplicateFactId` |
| **Classification** | **B: aggregate technical (identity) invariant.** It is not a business rule (A), not only a repository rule (C: the domain needs it before any persistence), and not a convenience (D: it carries proofs) |
| **Recommendation** | confirm D-1 as invariant **INV-FID**: FactIds are unique within a Member's log; a decision reusing one is refused. No code change. Its global counterpart is the Slice 1b primary key |

### I.2 Administrative closure

| Check | Result |
|---|---|
| PBDIGIT-258 indexed in `backlog/README.md` | **not yet** (X-4) |
| implementation review consistent with code and tests | yes: 59 + 5 tests; commit list `187792b32`…`bc91da46a` matches `git log` |
| developer guide consistent | yes: file list and API match `app/Contexts/Membership/Domain/Organisational/` |
| session / context records | session log `2026-10-09` committed; the `CONTEXT-publicdigit.md` entry is written but uncommitted (another session's edits share the file) |
| accidental Slice 1a work | **none**: `git grep` finds no repository port, append or version check outside the kernel; no `Organisational` references outside the kernel except unrelated Election files (`CommitteeSeatFilled.php`, `OrganisationalAppointmentAuthority.php`) |

### I.3 Slice 0 verdict

**GREEN WITH CONDITIONS.** All tests and verification pass. Two conditions remain:
1. the reviewer confirms D-1 (recommended: INV-FID, class B);
2. the README index row (X-4).

**Slice 1a is not started.**

---

## Part II: Organisational participation research

## 1. Executive conclusion

1. **"How a person participates" is a separate domain from "who belongs",** owned by **Governance**. This is ratified at the boundary (act §10–§11). There is **no ratified participation model**.
2. **The code has four mutually inconsistent "member in committee" models:**
   - **A.** `MembershipLineage` / `CommitteeAssociation`: no role;
   - **B.** `Committee` + `CommitteeAssignment`: one mutable role, a term;
   - **C.** Governance `Committee`: a member → `CommitteeRole` map, no time;
   - **D.** Election officers / `organisation_participants` / the `ElectionCommittee` seats.

   Only D (the Election `ElectionCommittee`) has a principled seat model ("the seat survives its occupant").
3. **What the evidence supports** (minimal model, §19):
   - **a Person participates in a Committee** in episodes: 1 person → N committees; 1 committee → N persons;
   - **a Position (office) of a Committee** has its own identity and capacity, and outlives its holders (vacancy, succession);
   - **a Tenure** is a person holding a position over an interval, opened by an **Appointment** (an act with provenance and authority evidence);
   - **roles are tenures, not attributes;**
   - **authority is derived** from effective tenures + versioned rules + delegation, and never stored per person.
4. **Membership affects participation only through per-position eligibility,** evaluated as a **derived intersection**:

   ```text
   EffectiveTenure = Tenure ∧ Eligibility(Membership predicates at t)
   ```

   It is **never** a cascade write. Computed: a cascade breaks the ratified annulment semantics (140 / 476 bounded cases); the derived intersection breaks nothing.
5. **The next Membership slice (1a) is unchanged** by this research. The next Governance step is a **semantic contract**, not code, gated by the decisions in §25.

## 2. Corpus evidence (summary)

- **Ratified:**
  - the Membership aggregate must not absorb committee participation or Governance competence; Governance owns competence, organs, authority and governance rules (`2026-10-08-membership-architecture-ratification-act.md` §10–§11);
  - committee roles are Governance's; ex-officio = a provenance (office ⇒ member) with Governance owning the office (`…-final-proof.md` §14.9).
- **Adopted (Election Manifesto, `docs/publicdigit/business_rules/ELECTION_MANIFESTO.md`):**
  - the Election Committee and the Deputy are appointed by "the organisational governance body, or an authority expressly authorised by the organisation's governance rules"; the Chief has no appointing power (EM-GOV-028, :137);
  - ≥ 3 members, independent, no candidate-representative overlap (EM-GOV-029 / 033);
  - vacancies only from a recorded event, filled by the governance body (EM-GOV-056 / 064);
  - "NO DEFAULT AUTHORITY MAY BE INVENTED" (EM-OPEN-049 / 066);
  - EM-VOC-002: a technical role assignment ≠ Organisation Membership.
- **Legacy decisions in tension:**
  - ADR-0001 places the committee write model in `Membership/Domain/Committee`;
  - ADR-F3.2 D8 makes "Membership" (meaning committee membership) the authority (`architecture_legacy/membership/decisions/ADR-F3.2-Semantic-Hardening.md:338-341`);
  - ADR-004 names only "Election Governance" as a confirmed context.
- **Brainstorming (not decisions):**
  - committee association belongs to Governance (`20261008_0032…:314-330`);
  - Election selection criteria may reference committee level, type and role, published by Governance (`20261008_0035…:379-411`, `20261008_0041…:132-145`).

## 3. Existing terminology (repository)

| Term | Where | Meaning in code |
|---|---|---|
| `MembershipLineage` | `Membership/Domain/Membership/MembershipLineage.php:24` | "constitutional lifecycle of a member's relationship with a **committee**": episodes ACTIVE / SUSPENDED / TERMINATED; **no role** |
| `CommitteeAssociation` | `…/CommitteeAssociation.php:18-28` | one episode: `associationType` (residence / exception / manual), `associatedAt`, actor and reason on transitions; **no validity interval, no role** |
| `CommitteeAssignment` | `Membership/Domain/Committee/CommitteeAssignment.php:36-50` | member in committee with **one** `rolePath`, joined / left, `termEndDate`, `nominationType`, `appointedByUserId`; `updateRole()` overwrites (`:261-263`) |
| Governance `Committee` | `Governance/Domain/Committee/Committee.php:15-46` | map member → `CommitteeRole` {chair, deputy, member, observer}; no time |
| `AuthorityAssignment` | `Governance/Domain/Authority/AuthorityAssignment.php:25-35` | **committee → committee** delegation (type, scope, validity, delegatedBy) |
| `ElectionCommittee` / `CommitteeSeat` | `Election/Domain/OperatingCore/Committee/` | seat-based body; "the seat survives its occupant" (`CommitteeSeat.php:11`); vacancy by a ground; filling only through `OrganisationalAppointmentAuthority` (an empty port) |
| `election_officers` | migration `2026_03_19_214205…` | chief / deputy / commissioner, appointed_by, term_ends_at |
| "participant" | `ParticipantController.php:16-18`; `organisation_participants` | anyone with a platform role / staff / guest / election committee, **regardless of membership** |
| role vocabularies | `Role.php`, `CentralCommitteeStructure.php`, `RolePath.php`, the `role_hierarchy` seed, `CommitteeRole.php`, `GovernanceRole.php`, `UserOrganisationRole.php` | **seven vocabularies**, mutually inconsistent (§20) |

## 4. Terminology collisions

- **"Membership"** means organisation membership (ratified) **and** committee membership (legacy lineage events `MembershipSuspended` / `Terminated` / `Restored`; ADR-F3.2 D8).
- **"Member"** means organisation member, committee member, `user_organisation_roles.role = member` (EM-VOC-002 says this is not membership), and ElectionMember (EM-VOC-003).
- **"Participation"** means organisational participation (this research) **and** the election voter-source freeze (`tests/Feature/Governance/ParticipationFreezeTest.php`).
- **"Role"** means committee office, platform permission level and election role.
- **"Committee"** means an organisational committee, the Election Committee and a constitutional committee.

**Recommendation:**
- **canonical terms:** *Committee Participation*, *Position*, *Tenure*, *Appointment*, *Authority*;
- **forbidden ambiguity:**
  - avoid "committee membership" in new code;
  - use "Member" only for organisation membership (EM-VOC-001);
  - use "Participation" only for organisational participation, with the election freeze renamed in its own context;
- this is a glossary candidate for governance.

## 5. Business scenarios (used for falsification)

| S | Scenario | What the model must do |
|---|---|---|
| S1 | M123 is Treasurer (Finance Cttee), Secretary (Election Cttee), member (Executive Cttee), Chair (Regional Cttee) at once | 1 person → N participations / tenures |
| S2 | Finance Cttee: treasurer 01.01–30.06, chair 01.07–31.08, leaves 01.09 | the role change does not end participation; history kept |
| S3 | chair and treasurer held by one person at once (small association) | multiple tenures in one committee, if the statute allows |
| S4 | the chair resigns; vacant for 3 weeks; successor appointed | the position outlives its holders; vacancy representable |
| S5 | membership ends; later the End is **annulled** on appeal | effective office holding must revive ex tunc (ratified Annul) |
| S6 | membership suspended | the office may or may not be exercised: a per-position rule |
| S7 | scrutineer / external auditor / staff on a committee without membership | non-member participation |
| S8 | the committee is dissolved | tenures stop being effective; the records remain (ADR-F3.2: dissolution does not delete) |
| S9 | the treasurer may sign payments up to X only with a second signature | authority ≠ role; it is rule-derived |
| S10 | an Election Committee member is not automatically a voter; a President does not hold every authority | authority and entitlement are separate (0741 :175) |

## 6. Person → Member → Participation distinction

| Relationship | Subject | Answers | Owner |
|---|---|---|---|
| OrganisationAffiliation | Person ↔ Organisation | platform access / roles (EM-VOC-002) | Organisation |
| Membership | Person ↔ Organisation (Member aggregate) | does the person belong? | Membership |
| Committee Participation | **Person** ↔ Committee | is the person part of this body? | Governance |
| Tenure (in a Position) | Person ↔ Position | does the person hold this office? | Governance |
| Authority | derived | may the person perform act a? | Governance (rules) |
| ElectionMembership / Entitlement | Person ↔ Election | registered / may vote? | Election |

**The six are independent relations.** Membership feeds Participation only through eligibility rules (X-1, X-3).

## 7. Committee model

- **What it is:** an organisational body with identity, a type / level, a lifecycle (constituted … dissolved) and a mandate period (legacy `GovernanceState`, `TermPeriod`), plus a set of **positions**.
- **What it is not:** an organisation, a membership class, or an election.
- **Owner:** Governance.
- **Classification:** a candidate **aggregate root**. Its capacity invariants span all holders of its positions (§16).
- **Note:** level / type / jurisdiction are partly overloaded in legacy (`CommitteeType` mixes level and wing, `20261008_0041…:104-113`; SQ-3 open).

## 8. Role model

**"Role" is not one concept.** The evidence splits it three ways:
1. **Position kind** (chair, treasurer, secretary, ordinary member): a classification of a position;
2. **Position** (the Finance Committee's treasurer office): an entity with identity and capacity;
3. **Platform permission role** (owner / admin / …): not organisational, so EM-VOC-002 applies.

**Rejected:** role as a mutable attribute of participation (CX-P1, §20).

## 9. Position model

| Question | Answer (evidence-supported) |
|---|---|
| identity | yes; it outlives its holders (the Election `CommitteeSeat` already encodes this; S4) |
| belongs to | exactly one committee |
| capacity κ(s) | 1 for an office (chair); k for coordinators / seats (legacy limits: chair 1, vice 2, coordinator 5, member unlimited; `CentralCommitteeStructure.php:37-43`) |
| eligibility ε(s) | per position: requires membership? minimum membership years (legacy `CommitteeLevel.minMembershipYears`)? age? geography? |
| does "ordinary member" need a position? | **no** (business choice): an ordinary member is a Participation without a tenure. Seat-composed bodies (Election Committee) model composition as positions |

## 10. Appointment model

- **Appointment** = the act that opens a tenure: elected / appointed / co-opted (legacy `nominationType`), by whom (an organ), on what basis (statute / rule version), with authority evidence.
- **Analogue:** it is the analogue of Membership's Admit: a **fact**, and the tenure is the derived episode.
- **Ending acts:** resignation, removal, end of term, ineligibility (Election `VacancyGround` is a precedent).

## 11. Mandate model

**"Mandate" has two meanings in the corpus:**
1. the **term of office of a body** (legacy `GovernanceState.TermPeriod` "mandate period");
2. the **scope of power** given to a holder or body (38C-15 "entrenched mandate", BDR-03 "Appointment/Authority owns Mandate truth").

**Recommendation:**
- name (1) **Term** (of committee or tenure);
- name (2) **Mandate** = the scope of authority. It is a rule object, versioned, owned by Governance.

## 12. Authority boundary

```text
HasAuthority(p, a, t)  ⇔  ∃ s : EffectiveTenure(p, s, t) ∧ a ∈ Grants(kind(s), rules@t)
                          ∨  ∃ delegation d to p valid at t, from a holder who has a at t
```

- **Evidence:** today's authority is **separate** from committee roles. It comes from platform roles (`ActorPosition`, `GovernanceCapabilityContextFactory.php:39-43`), committee → committee delegation (`AuthorityAssignment`) and election officers.
- **Role ≠ authority** (S9, S10).
- **Authority is never stored per person.**
- **Rejected:** "Participation → Authority directly": a co-signature rule (S9) or a jurisdiction limit is unrepresentable.

## 13. Temporal model

**S2 resolved formally.** The two terms are independent attributes:
- the committee-membership term depends on (person, committee, appointment-to-committee);
- the office term depends on (person, position, appointment-to-office).

That is a multivalued dependency `person, committee ↠ office-term`. A single relation forces either a cross product of rows or loss of information. **Normalisation therefore requires two temporal relations.**

| Option | Verdict |
|---|---|
| one participation + changing role | **rejected**: loses history (CX-P1); 369 / 625 lossy |
| multiple position terms (tenures) inside a participation | **supported** (M2): lossless 625 / 625 |
| appointment episodes only, participation derived (M3) | lossless, but **cannot enforce** "officers are committee members" (R6). It fits seat-composed bodies (Election Committee) only |

Each fact carries effectiveAt / decidedAt / recordedAt / seq. This reuses the ratified **pattern**, not the code.

## 14. Membership dependency analysis

| Model (commission §11) | Verdict |
|---|---|
| A: participation requires membership | valid **for some positions** |
| B: participation independent | valid for some (S7) |
| C: participation allowed; authority requires membership | a special case of per-position eligibility |
| D: statutory / external / honorary participants | evidenced (`organisation_participants`) |

**Conclusion:** eligibility ε(s) is per position, and evaluated as a derived intersection.

| Policy for "membership ends" | Effect (computed, §21) |
|---|---|
| **DERIVED:** effective = tenure ∧ ε(Membership predicates at t) | 0 violations; S5 revives automatically |
| **CASCADE:** Governance writes the tenure's end | **breaks the annulment counterfactual** in 140 / 476 cases (smallest: membership End @1 annulled; the tenure [0,2) stays cut) |
| **NONE:** ignore membership | office held by a non-member in 140 / 476 cases where ε requires membership |

- **Suspension:** whether a suspended member may exercise an office is a ε(s) rule over `Suspended` (Membership publishes it; Governance decides; `e0b…:216`).
- **Business decision:** whether membership end *ends* the tenure legally (derived lapse) or only *suspends its effect* until a recorded vacancy decision. The architecture (derived intersection) supports both; the record is never rewritten.
- **The rule is Governance's.** Nothing goes into `Member`.

## 15. Candidate bounded-context ownership

| Context | Owns |
|---|---|
| **Membership** | Member, periods, membership predicates (IsMember, Suspended, CurrentType, admission dates, durations), the authority evidence snapshot on its own facts |
| **Governance** | Committee, Position (kind, capacity, eligibility), Participation, Appointment / Tenure, Mandate (scope rules), Authority derivation, delegation, the governance role vocabulary |
| **Election** | ElectionMembership, entitlement, exercisability, the Election Committee's internal seat logic (already modelled), appointment *requests* through `OrganisationalAppointmentAuthority`, answered by Governance |
| **Finance** | settlement and fees; consumes authority evidence for e.g. treasurer approvals (not owns it) |
| **Identity** | Person, equivalence |

**Integration:**
- **Membership → Governance:** a **query port** (published language: the same `MembershipQueryPort` EP-01 designs for Election, plus admission-date and duration predicates for ε rules such as minimum years). Reason: the derived intersection needs the *current* Membership truth for any t, including after annulments. Events (if any) only trigger workflow (e.g. "check vacancies"), never truth.
- **Governance → Election:** `OrganisationalAppointmentAuthority` (the port already exists, empty) and published committee / position facts for selection criteria (`20261008_0035…:379-411`).

## 16. Candidate aggregates (not decided)

| Candidate | Consistency it protects | Pros | Cons |
|---|---|---|---|
| **G-A:** Committee aggregate owns its positions, participations and tenures | capacity κ(s) per position at t; ≤ 1 participation episode per (p,c) at t; tenure ⊆ participation (when ε says so) | all intra-committee invariants are local (like D1) | large committees mean a large log (mitigated: few facts per year) |
| **G-B:** Participation per (p,c) aggregate + set-based capacity check | per-person consistency | small aggregates | capacity spans persons, so it needs set-based enforcement (like D2) |
| **G-C:** Position aggregate (tenures per position) | capacity, succession, vacancy | natural for offices | "tenure ⊆ participation" crosses aggregates |

**Recommendation for the decision: G-A.** Capacity and "officers are members of the body" are intra-committee invariants. This mirrors the D1 reasoning.

## 17. Candidate entities / value objects

- **Entities:** Committee (root), Position, Participation, Tenure.
- **Facts:** JoinCommittee / LeaveCommittee, Appoint / Vacate (with Annul / Void / Correct by analogy, later).
- **Value objects:** PositionKind, Capacity, EligibilityRule, Term, AppointmentBasis (provenance + authority evidence), VacancyGround.
- **External references:** PersonRef, OrganisationId, MemberId (only for evidence, never as the subject).
- **Rule objects:** Mandate / Grants (versioned).

## 18. Candidate invariants (hypotheses, with status)

| ID | Statement | Kind | Status |
|---|---|---|---|
| INV-P1 | a participation belongs to exactly one committee, hence one organisation | definition | supported |
| INV-P2 | at most one participation episode per (person, committee) at any t | domain rule (analogue of INV-9) | supported (legacy: one active assignment per member per committee) |
| INV-P3 | for every position s and time t: \|holders(s,t)\| ≤ κ(s) | domain rule | supported (legacy role limits) |
| INV-P4 | Tenure(p,s,t) ∧ requiresParticipation(s) ⇒ Participates(p, committee(s), t) | business rule (per position) | supported by R6 (M2 only) |
| INV-P5 | EffectiveTenure(p,s,t) = Tenure ∧ ε(s)(p,t) ∧ CommitteeActive(committee(s), t) | definition (derived) | **computed** (§21) |
| INV-P6 | incompatible positions (e.g. auditor vs board; committee member vs candidate representative, EM-GOV-033) never overlap for one person | business rule (rule table) | supported (Election I-6) |
| INV-P7 | HasAuthority is derived only (§12); no stored per-person authority | architecture rule | supported |
| INV-P8 | a position outlives its holders; vacancy is a state, not a deletion | definition | supported (`CommitteeSeat`) |
| INV-P9 | one person may hold several positions in one committee only if no incompatibility forbids it | business rule | **open** (legacy forbids; scenario S3) |
| INV-FID | FactIds are unique within an aggregate's log | aggregate identity invariant | recommended (D-1) |

## 19. Formal model (minimal)

```text
Sets:     P persons, O organisations, C committees, S positions, A authority acts, 𝕋 time (with ∞)
Maps:     org: C → O,  committee: S → C,  kind: S → K,  κ: S → ℕ ∪ {∞},  ε: S → Rules
Facts:    Join(p,c) / Leave(p,c), Appoint(p,s,basis) / Vacate(p,s,ground), each with effectiveAt, decidedAt, recordedAt, seq
Derived:  Part(p,c) = episodes from Join / Leave        Participates(p,c,t) ⇔ t ∈ Part(p,c)
          Ten(p,s)  = episodes from Appoint / Vacate    Holds(p,s,t)        ⇔ t ∈ Ten(p,s)
          Effective(p,s,t) ⇔ Holds(p,s,t) ∧ ε(s)(MembershipPredicates(p, org(committee(s)), t))
                             ∧ CommitteeActive(committee(s), t)
                             ∧ (¬requiresParticipation(s) ∨ Participates(p, committee(s), t))
          HasAuthority(p,a,t) ⇔ ∃ s: Effective(p,s,t) ∧ a ∈ Grants(kind(s), rules@t)  ∨  Delegated(p,a,t)
```

**Relation to Membership:**
- Membership(p,o,t) appears **only** inside ε, via the query port;
- the role is **not** a component of Part (X-2).

## 20. Counterexamples

| CX | Finding | Kind |
|---|---|---|
| CX-P1 | role as an attribute of participation: chair + treasurer at once is inexpressible; a role change splits the participation episode; legacy `updateRole()` destroys role history (`CommitteeAssignment.php:261-263`) | **model**: 369 / 625 bounded ground truths are lossy |
| CX-P2 | a cascade write on membership end, followed by an annulled End: the office does not revive | **model**: 140 / 476; contradicts the ratified Annul semantics |
| CX-P3 | `unique_active_role_per_committee (committee_id, role_path) WHERE is_active` also applies to the ordinary-member path, so a committee can have only one active ordinary member per path (`…/2026_01_16_000003…:113-116`) | legacy defect |
| CX-P4 | role limits keyed by names (`'chairperson'`) but validated against numeric role paths: never applied (`Committee.php:769-797`) | legacy defect |
| CX-P5 | `committee_associations.association_id` is unique, but suspend / restore episodes reuse the id, so a second episode row likely violates it (`EloquentMembershipLineageRepository.php:35-65`) | legacy defect (likely; not executed) |
| CX-P6 | no effect of membership end on committee roles anywhere (`EventServiceProvider.php:56` "MembershipExpired — event not yet created") | gap |
| CX-P7 | two orderings of `DelegationType` priority (Governance: AUTHORITY > OVERRIDE; Membership Geo: OVERRIDE > AUTHORITY) | legacy contradiction |
| CX-P8 | `AssignMemberToCommitteeHandler` discards the role path, term and appointing user, and never calls `Committee::assignMember` (`:60-101`) | legacy defect |
| CX-P9 | seven role vocabularies; `RolePath` and the `role_hierarchy` seed disagree on 1.1.2 / 1.1.3 | legacy contradiction (PBDIGIT-141 / H-13 open) |

**These are recorded, not repaired.** Backlog items are recommended for CX-P3, P4, P5 and P8 (not created in this research task).

## 21. Computational verification (research model; written RED-first)

**Method:**
1. Properties written first (`test_participation.py`, `test_dependency.py`); they failed (no model).
2. Then three candidate representations (`model.py`) and three dependency policies (`dependency.py`).

**Space:** exhaustive over time 0..4 (participation) and 0..6 (dependency); one or two persons; committee c1 with offices chair and treasurer; offices inside committee membership (the usual statute).

| Property | M1 role-on-participation | M2 participation + tenure | M3 tenure-only (derived participation) |
|---|---|---|---|
| R1 lossless (625 ground truths) | **256 / 625** | 625 / 625 | 625 / 625 |
| R2 role change keeps the participation episode | **no** | yes | yes |
| R3 multiple offices in one committee | **no** | yes | yes |
| R4 a person is counted once | **no** (not representable) | yes | yes |
| R5 vacancy and succession | yes | yes | yes |
| R6 "office requires participation" enforceable | yes (by unrepresentability) | yes | **no** |

| Policy (476 cases) | P1 counterfactual after annulled End | P2 office without membership |
|---|---|---|
| DERIVED | **0** | **0** |
| CASCADE | **140** | 0 |
| NONE | 0 | **140** |

**Limits:**
- small bounded spaces;
- one organisation;
- capacity > 1, incompatibility rules, delegation and committee dissolution **not** enumerated (dissolution is the same structure as membership end, so the same argument applies by analogy; not computed);
- the counts are counts of **generated cases**, not statistics about organisations.

## 22. Alternatives considered

M1 / M2 / M3 (§13, §21); participation anchored on Member vs Person (X-1); cascade vs derived vs none (§14); aggregates G-A / G-B / G-C (§16); authority stored vs derived (§12).

## 23. Rejected alternatives

| Alternative | Reason |
|---|---|
| role as an attribute of participation (legacy B / C) | CX-P1 |
| committee participation inside the Member aggregate | ratification act §10; boundary |
| cascade writes from membership events | CX-P2; it also couples write paths across contexts |
| participation keyed by MemberId | X-1 (non-member participants exist) |
| authority stored per person or role | §12; S9 / S10 |
| pure M3 for organisational committees | R6 (cannot enforce officers-are-members) |

## 24. Open business questions (genuine)

| ID | Question |
|---|---|
| BQ-P1 | May one person hold several positions in the same committee at once (S3)? Which combinations are incompatible (INV-P6 / P9)? |
| BQ-P2 | Per position kind: does it require organisation membership? A minimum membership duration? |
| BQ-P3 | When membership ends: does the tenure lapse legally at once (derived), or remain until a recorded vacancy decision? |
| BQ-P4 | During a membership suspension: may the office be exercised? |
| BQ-P5 | Who appoints and removes for each position (organ per statute)? (EM-OPEN-049 / 066 for the Election Committee) |
| BQ-P6 | Is committee level a property of the committee or of an organisational unit / jurisdiction (SQ-3)? |
| BQ-P7 | Is ordinary committee membership itself an appointed act (application / approval) for every committee type? |

## 25. Required decisions (FINDING → QUESTION → OPTIONS → CONSEQUENCES → RECOMMENDATION → DECISION REQUIRED)

**RD-1: Participation subject**
- **Finding:** non-members participate (X-1).
- **Question:** is the subject Person or Member?
- **Options:** Person + eligibility, or Member-only.
- **Consequences:** Member-only cannot represent staff, guests or scrutineers.
- **Recommendation:** Person.
- **Decision required:** PO / Principal Reviewer.

**RD-2: Role representation**
- **Finding:** CX-P1.
- **Options:** M1, M2, M3.
- **Recommendation:** M2 for organisational committees; M3-style seats remain inside the Election Committee.
- **Decision required.**

**RD-3: Membership dependency**
- **Finding:** CX-P2.
- **Options:** derived, cascade, none.
- **Recommendation:** derived intersection via the Membership query port.
- **Decision required.**

**RD-4: Aggregate**
- **Options:** G-A, G-B, G-C.
- **Recommendation:** G-A.
- **Decision required** (with BQ-P1 and BQ-P2 answered).

**RD-5: Legacy disposition**
- **Finding:** four committee models.
- **Options:** migrate one, or re-model in Governance.
- **Recommendation:** model the target in Governance first (contract), then a migration mapping; ADR-0001 and ADR-F3.2 D8 need a status act.
- **Decision required:** governance.

## 26. Recommended next TDD slice

- **Membership: Slice 1a**, unchanged by this research. It covers the repository port, `append` with `basedOnVersion`, the conflict re-decide (re-authorise → re-guard → G\*), idempotency checked **before** decide (D-1 interplay), the creation race (INV-M), monotone `recordedAt` and the explicit TenantId.

  It has no committees, roles, Governance, Election, Finance, UI, migration, CQRS framework or event sourcing.
- **Governance:** **not** a code slice yet. The next artifact is a **Participation Semantic Contract**, the analogue of the Slice 0 contract. It comes after RD-1 to RD-4 and BQ-P1 to BQ-P3.

## 27. Requirements traceability (new candidate requirement IDs)

| ID | Requirement | Evidence | Concept |
|---|---|---|---|
| REQ-PART-01 | a person may participate in several committees at once | test `CommitteeMemberProjectionTest.php:253-272`; 0741 | Participation |
| REQ-PART-02 | a committee has many participants | `Committee::$assignments`; role limits | Participation |
| REQ-PART-03 | a role change does not end committee participation; role history is kept | S2; CX-P1 | Tenure |
| REQ-PART-04 | a position outlives its holders; vacancy and succession are recorded | `CommitteeSeat`; EM-GOV-056 / 064 | Position |
| REQ-PART-05 | each appointment records organ, basis and authority evidence | legacy `appointedByUserId`, `nominationType`; EM-GOV-028 | Appointment |
| REQ-PART-06 | eligibility per position (membership, duration, age, geography) | `CommitteeLevel`, `CommitteeEligibilityPolicy` | EligibilityRule |
| REQ-PART-07 | membership changes affect office effectiveness without rewriting governance history | ratified Annul; CX-P2 | INV-P5 |
| REQ-PART-08 | authority derives from effective positions and versioned rules / delegation; never stored per person | S9 / S10 | Authority |
| REQ-PART-09 | non-members may participate where a statute allows | `organisation_participants` | RD-1 |

## 28. Architecture implications

1. **No change to the ratified Membership architecture.** The `MembershipQueryPort` (EP-01) gains a second consumer (Governance), with no new semantics.
2. **Governance needs its own discovery → contract → slices track,** parallel to Membership and strictly separated.
3. **Legacy committee code:** four models, nine recorded defects or contradictions. Do not migrate before the target contract exists.
4. **The Election Committee already has a seat model.** Governance must *answer* its appointment port, not re-model it.
5. **Glossary act needed** for Member / Membership / Participation / Role / Committee (§4).

---

## Final output (commission §22)

**A. Slice 0 status:** **GREEN WITH CONDITIONS.** The conditions: confirm D-1 (recommended INV-FID, class B), and the README index row (X-4).

**B. Membership next slice:** **Slice 1a**, as specified in §26, after the PO / Principal Reviewer accepts Slice 0.

**C. Organisational participation model** (evidence supports):
- a Person participates in many Committees;
- Committees have Positions with capacity and eligibility;
- a Tenure is a separate temporal object opened by an Appointment;
- effectiveness is derived from Membership via eligibility;
- authority is derived from rules.

**D. Architecture boundary:**
- **Membership owns** members, periods and membership predicates;
- **Governance owns** committees, positions, participation, appointments, tenures, mandates, the authority rules and the governance role vocabulary;
- **Election owns** election membership, entitlement, exercisability and its committee's seat logic;
- **Finance owns** fees and settlement.

**E. New requirements:** REQ-PART-01 … REQ-PART-09.

**F. New invariants:** INV-P1 … INV-P9 (hypotheses with status, §18); INV-FID (recommended).

**G. Open business decisions:** BQ-P1 … BQ-P7; RD-1 … RD-5.

**H. Counterexamples:** CX-P1 (role-as-attribute lossy), CX-P2 (cascade breaks annulment), CX-P3 … P9 (legacy).

**I. Mathematical model:** §19.

**J. Computational verification:** §21. Exhaustive within tiny bounds; RED-first; limits stated.

**K. Recommended TDD slice:** Membership **Slice 1a**, the only implementation slice recommended now.

**L. STOP:** nothing is implemented in this task. Slice 1a starts only on explicit authorisation.

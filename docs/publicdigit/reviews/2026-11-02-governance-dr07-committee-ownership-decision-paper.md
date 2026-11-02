# DR-07 decision paper: who owns the Committee, its positions, appointments and tenure

**Date:** 2026-11-02 · **Author:** Claude (DDD architect / requirements engineer; authority: generated)
**Status:** DECISION PAPER. **No ownership decision has been made by preparing it.** DR-07 stays OPEN (register O-6).
**Builds on, does not replace:**
- decision sheet `2026-10-11-cross-domain-decision-round-1-sheet.md` **B-1** (options A / A+B / C; never decided);
- plan `docs/plans/20261011-0103-cross-domain-architecture-implementation-plan.md:83`.

This paper deepens B-1 with the question split, the code evidence and the §11 finding. **The decision is recorded in §13 of this paper.** B-1 points here.

**Placement:** `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0).
**Baseline:** branch `election-flow-updated`, HEAD `72d8d5470`.

Labels:
- **[F]** fact verified in the repository;
- **[I]** interpretation;
- **[A]** assumption;
- **[R]** recommendation;
- **[O]** open business question.

---

## 1. Decision required

DR-07 is three questions that must not be merged:

| # | Question | Kind |
|---|---|---|
| Q1 | **Aggregate:** which aggregate protects which committee invariants, and what must stay outside the Membership aggregate? | tactical; largely settled by ratified §10 |
| Q2 | **Context:** which bounded context owns the authoritative lifecycle of a Committee and of its positions, appointments, tenure and jurisdiction assignments? | **strategic: the decision** |
| Q3 | **Interaction:** for each operation, who requests it, who validates, who decides, and who records the fact? | strategic; follows from Q2; contracts are finalised later (T6) |

## 2. Owners

- **PO:** the business meaning, i.e. whether committees are organs of governance.
- **ARB:**
  - the reading of ratified §10 and §11;
  - a new ADR superseding ADR-0001 (fully or in part);
  - the freeze rule for the legacy code.
- **Membership owner** (another developer): agreement, because the legacy committee code sits in the Membership context.

## 3. Current authoritative rules

| Source | Status | What it says |
|---|---|---|
| Ratified act §10 (`2026-10-08-membership-architecture-ratification-act.md`, "Ratified Aggregate Boundary") | **RATIFIED** | the Membership **aggregate** "must not absorb … Governance competence; … committee participation; unrelated organisation structures" |
| Ratified act §11 ("Ratified Context Boundaries", `:309`; Governance `:319-321`) | **RATIFIED** | "**Governance** owns competence, **organs**, authority and organisational governance rules." "Membership owns organisational membership truth." |
| Recorder's note **N-6** (same file `:19`) | **not binding** ("these do not amend the act", `:8`) | the committee code in `app/Contexts/Membership` is "a known legacy boundary defect"; migration "must place it in Governance" |
| **ADR-0001** (`docs/adr/ADR-0001-committee-read-write-separation.md`) | **ACCEPTED** (March 2025) | two things: (1) **CQRS separation**: a committee write model is the source of truth, read models are projections with no writes; (2) **location**: the write model is in `app/Contexts/Membership/Domain/Committee/`, read models in `app/Contexts/Committee/` |
| v1.5 vocabulary (`…/cross_domain_discovery_package_v1.5.md:60-100`) | PROPOSED (CANDIDATE) | Committee = "an **organ**: a constituted body with lifecycle, term, positions", owner "[OPEN] DR-07"; Position, Participation, Appointment, Tenure, Mandate are Governance |

**Finding [I], new relative to B-1:**
- **ADR-0001 does not conflict with §10 at aggregate level.** It makes Committee its own aggregate root; it does not put committee participation inside the Membership aggregate.
- **The conflict is with §11.** If a committee is an **organ**, §11 assigns it to Governance, while ADR-0001 places its write model in the Membership context.
- B-1 grounded the conflict in N-6, which is not binding. **§11 is the binding ground**, conditional on the PO confirming that committees are organs.
- **Only ADR-0001's location clause is affected.** Its CQRS principle is independent and compatible with every option.

## 4. Evidence: the code today [F]

**Committee write model in the Membership context:**
- `app/Contexts/Membership/Domain/Committee/`: `Committee` aggregate, `CommitteeAssignment`, structures, constitutions, geography policy, capability policies.
- Use cases in `app/Contexts/Membership/Application/Committee/` (Create, AssignMember, RemoveMember, UpdateDetails) and `…/CommitteeStructure/`.
- Called from `app/Http/Controllers/Committee/CommitteeMemberController.php` (routes `routes/committee/committeeRoutes.php`).

**Its persistence:**
- `committees`, `committee_assignments` and `committee_structures`, through Membership models (`Infrastructure/Models/CommitteeModel.php`, `CommitteeAssignmentModel.php`, `Persistence/Models/CommitteeStructureModel.php`).
- Known defect: ended assignments are rewritten to `left_date = now()` (`app/Contexts/Membership/Infrastructure/Repositories/EloquentCommitteeRepository.php:143-150`). This is against candidate invariant G-9 (register §5).

**A second write path in Governance:**
- `app/Contexts/Governance/Domain/Committee/Committee.php` holds only a member → role map.
- `EloquentCommitteeRepository::findById` checks that the `committees` row exists and then **creates a blank aggregate** (`app/Contexts/Governance/Infrastructure/Repositories/EloquentCommitteeRepository.php:31-48`, "events will rebuild member state").
- `save()` only dispatches events; listeners write projections (`:56-66`).
- Exposed as write endpoints: `POST` / `DELETE /api/v1/governance/committees/{id}/members…` (`routes/api_v1.php:52-56`, loaded by `bootstrap/app.php:22`); also `routes/governance-api.php:67-71`.
- **So two contexts write committee membership through different stores:**
  - Membership writes `committee_assignments`;
  - Governance writes projections.
  - Governance's invariants run against an empty aggregate.
  - Whether this path is used in production is **not established** (VERIFY).

**Read models:**
- `app/Contexts/Committee/` holds read models only, as ADR-0001 says.
- Governance also has hierarchy and projection services (`app/Contexts/Governance/Application/Services/`).

**"Committee association" in Membership:**
- `app/Contexts/Membership/Domain/Membership/CommitteeAssociation.php` carries a `MembershipStatus`.
- It is created when a membership application is approved (`ReviewMembershipApplicationHandler.php:27-38`).
- **[I]:** this is membership **held through an organisational unit** (a branch or chapter modelled as a committee type, v1.5 "OrganisationalUnit … today mis-modelled as committee types"), not participation in an organ.
- If that reading holds, it belongs to Membership / Organisation structure, not to DR-07. **To confirm: OQ-DR07-1.**

**Three assignment stores** (`committee_assignments`, `committee_associations`, Governance projections): C4 in the register.

**Consequence:** under every option, two of the three stores must be retired or reclassified.

## 5. Alternatives

The ownership options are **A** and **C**. **B** (in B-1) is not an ownership option; it is the **cutover mechanism**, and it combines with A.

| | **A: Governance owns** | **C: Membership owns (ADR-0001 as is)** |
|---|---|---|
| Ownership and authority | Governance owns Committee (organ), Position, Appointment, Tenure, Mandate and JurisdictionAssignment; it decides validity and records the facts. Membership owns membership facts only | Membership context owns committee lifecycle and assignments; Governance keeps competence and authority only |
| Aggregates | Committee AR in Governance (positions inside); `PositionHoldings` per (Person, Organisation) for per-organisation incompatibility (BR-14); Membership aggregate unchanged (§10 holds) | Committee AR stays in the Membership context; §10 still holds (separate aggregate) |
| Membership lifecycle interaction | Membership publishes facts (current member, membership ended). Governance decides any consequence for seats per statute (O-9). Membership never writes committee data (the mirror of V1) | Membership use cases may end assignments directly; the coupling stays within one context |
| Appointment and tenure | append-only Appoint / Vacate facts; tenure derived (v1.5); the G-9 defect is removed in the new model | must be redesigned anyway to remove the G-9 defect |
| Consistency | Membership facts are read at appointment time through a published contract; eventual consistency for termination effects | in-context transactions; simpler |
| Coupling | two directed relationships: Membership → Governance (facts); Governance → Membership (authority evidence for Membership acts, §10 "authority evidence relevant to Membership acts") | Governance needs committee and organ facts from Membership in order to evaluate authority; the context map keeps a two-way dependency on organs |
| Audit | appointment facts carry authority evidence (Adjudication's `AuthorityAssertionPort`, register §0) | as today |
| Failure | Membership source unavailable → appointment refused or pending, never "assumed member" (same semantics as OQ-ME-4) | in-process |
| Migration | large: new model, retire Governance stub writes, reconcile three stores; use B (legacy as oracle, shadow until parity) | smaller code change, but the stub and the three stores must still be fixed |
| Ratified fit | consistent with §10 and §11 | consistent with §10; **conflicts with §11 "organs"** → needs a **Change Request to the ratified Membership architecture** |
| Context map | Governance becomes a real context; `app/Contexts/Committee` read models become Governance read models | Governance stays thin; Committee and Governance relationships stay ambiguous |

**Considered and not offered:** a separate "Committee" context apart from Governance.
- It would separate organs from mandate and authority, which §11 keeps together in one context.
- There is no ratified or proposed source for it. The existing `app/Contexts/Committee` is a read-model folder, not a context.

## 6. Interaction and authority (Q3, under A; content subject to the business questions in §7)

| Operation | Requested by | Validates | Decides | Records | Depends on |
|---|---|---|---|---|---|
| Constitute / dissolve committee | an authorised actor | Governance | the organ per statute | Governance | CQ-2, CQ-10 [O] |
| Appoint to position | an authorised actor | Governance (eligibility, capacity, incompatibility per organisation, BR-14) | the competent organ | Governance (Appointment fact) | O-8 [O]; CQ-5: does the seat require membership? |
| Membership ends | Membership | Membership | Membership | Membership (fact) | — |
| Effect on seats of a membership end | Governance (on the published fact) | Governance | Governance per statute | Governance (Vacate fact) | O-9 (BQ-P2 / P3) [O] |
| Admit / exclude a member | an applicant or organ | Membership (its own rules) | the competent organ (authority assertion from Governance) | Membership | CQ-3 [O] |
| Acts by a holder whose tenure is later annulled | — | Governance | statute | Governance | CQ-6 [O] |

**A request is not a decision:** a context may request an operation that another context decides.

## 7. Business questions and whether they block DR-07

| # | Question | Owner | Blocks the ownership decision? | Blocks |
|---|---|---|---|---|
| OQ-DR07-0 | **Are committees organs of governance** (constituted bodies with positions and authority)? | PO | **yes**: it is the premise of the §11 reading | — |
| OQ-DR07-1 | Is `CommitteeAssociation` membership through an organisational unit, or participation? | PO + Membership owner | no | store reconciliation |
| O-8 (BQ-P1 / CQ-7) | which positions are incompatible | PO | no | Governance invariants |
| O-9 (BQ-P2 / P3, CQ-5) | Membership ↔ tenure; does a seat require membership? | PO | no | the Membership → Governance contract |
| O-10 (CQ-2 / 3 / 4 / 10) | committee authority; statutes | PO | no | authority matrix (T-08) |
| CQ-6 | validity of acts after a tenure is annulled | PO | no | audit semantics |
| O-11 | jurisdiction exclusivity; geography | PO + ARB | no | JurisdictionAssignment |
| O-14 | vocabulary: member / role / observer | ARB | no, but the new ADR must use the agreed terms | context map |

**Evidence state of the statutes:**
- they are not in the corpus;
- they have **not yet been requested** (T-08).

**Ownership (Q2) can be decided without them; the contracts (T6) cannot.**

## 8. Recommendation [R]

**A, with B as the cutover mechanism.** This is the same as B-1, now with stronger grounds.

**Why:**
- It is the only option consistent with ratified §11 without a Change Request.
- It keeps the ratified Membership aggregate unchanged.
- It removes the conflicting Governance stub write path by giving Governance a real model.

**How ADR-0001 is handled:**
- supersede **only its location clause**;
- keep its CQRS principle (one write model as the source of truth; read models never write).

**Risks:**
- a large migration;
- dependence on the Membership owner's cooperation;
- before cutover, two models exist in shadow.

The oracle-and-parity mechanism (B) addresses the last risk.

**Interim recommendation, independent of the choice; needs its own authorisation:**
- record the Governance write endpoints (`api/v1/governance/committees/{id}/members` POST / DELETE) as a gap and verify production use;
- no change here.

## 9. Approval sequence

1. **PO:** answer OQ-DR07-0, then choose A or C (§13).
2. **Membership owner:** agreement, under A to freezing the legacy committee code and the oracle role; under C to keeping ownership.
3. **ARB:**
   - under A: a new ADR superseding ADR-0001's location clause, the freeze rule wording, and a recorded reading of §11, with no amendment of the ratified act;
   - under C: a Change Request against ratified §11.
4. Records updated: the ADR index, register O-6, governance map §2 (Committee owner), and decision sheet B-1.

## 10. Reversal conditions

**A would be reconsidered if:**
- the PO answers OQ-DR07-0 "no" (committees are not organs but organisational units or work groups). Those parts then go to Organisation structure.
- statutes show committee lifecycle is wholly determined by membership acts.

**C would be reconsidered if:** a Governance authority model is required for Adjudication (`AuthorityAssertionPort`) and cannot be built without owning organs.

## 11. Downstream changes after approval (under A)

**Context map:**
- Governance is upstream of Membership for authority assertions.
- Membership is upstream of Governance for membership facts (published language).
- This replaces the unspecified "Membership ↔ Governance cycle" (governance map §5).

**Contracts (T6):**
- membership-fact query and event, with OQ-ME-4-style semantics (fact vs decision, unknown vs no, unavailable source);
- authority assertion.

**Code:**
- a later, separately authorised slice: freeze rule, oracle tests, shadow Governance model, reconciliation, and retiring the stub path.
- **Nothing here authorises it.**

## 12. Acceptance criteria for closing DR-07

- PO answer to OQ-DR07-0 and the choice of A or C recorded in §13.
- Membership owner's position recorded.
- ARB act recorded:
  - the new ADR id with ADR-0001's status annotated (A);
  - or the Change Request outcome (C).
- Register O-6 RESOLVED; governance map Committee row and decision sheet B-1 updated.
- T6 (relationship specifications) unblocked and started from §11 of this paper.

## 13. The decision requested

> **Are committees organs of governance and, if so, does Governance own the Committee, its positions, appointments and tenure (A: ADR-0001's location clause superseded; legacy code frozen as the behavioural oracle until a Governance model reaches parity), or does the Membership context keep them (C: ADR-0001 unchanged; a Change Request against ratified §11)?**

**Decision:** ☐ A (with B cutover) ☐ C ☐ other: ____ · decided by: ____ · date: ____ · ARB act: ____

---

## 14. Premise: Principal architectural position (2026-11-02)

**Recorded:** the Principal's architectural position on OQ-DR07-0 is **BOTH**. Two different concepts are represented today by committee code, and they must be modelled separately:

| | **DR-07a: governance organs** | **DR-07b: organisational units** |
|---|---|---|
| Meaning | constituted bodies with positions, appointments, tenure and authority within a mandate | structure (branch, chapter, wing) through which membership is held |
| Ownership candidate | Governance (ratified §11 "organs") | Organisation structure (`OrganisationalUnit`, v1.5 candidate; governance map `:57, :87, :115`) |
| Relation to persons | appointment or position, under governance rules | membership through the unit, under membership rules |
| Recommendation | **A + B** (unchanged; C not recommended on current evidence) | resolve in the organisation-structure design; no context name, aggregate or store decided |
| Open | PO confirmation that governance committees are organs | OQ-DR07-1: meaning of `CommitteeAssociation` (Membership owner + PO) |

**Rules recorded with the position:**
- A membership relationship is not a governance appointment.
- An organisational unit is not a governance organ merely because both are represented by a `Committee` model.
- `app/Contexts/Committee/` is a read-model folder; it does not establish a context.
- The evidence suggests the split; it does not classify individual committee records. That needs domain clarification and later data reconciliation.

**Status of each authority:**

| Act | Status |
|---|---|
| Principal architectural position (BOTH; A + B for organs) | **recorded 2026-11-02** |
| PO business confirmation (governance committees are organs; units are a separate concept) | **pending** |
| Membership owner: meaning of `CommitteeAssociation`; position on A + B, freeze and reference rule | **pending** |
| ARB act (Governance ownership of organs; ADR-0001 location clause) | **pending** |

**DR-07 stays OPEN.** Nothing here authorises:
- code or schema changes;
- store reconciliation;
- production queries or data changes;
- migration.

T6 starts only after DR-07a is decided.

**Naming note:** DR-07a / DR-07b avoid confusion with options A / B / C above, where B is the cutover mechanism.

---

## 15. PO business decision PO-DR07-01 (2026-11-02)

**Authority:** the PO, in this session, 2026-11-02. This is a **PO business ruling**, separate from the Principal's architectural position (§14).

1. **Governance committees are constituted governance organs** with positions, appointments and governance authority.
2. **Organisational units** (branches, chapters, wings) are a distinct domain concept and must not be conflated with governance organs.
3. The distinction **does not classify existing committee records**. Classification is an evidence-based task (O-16).
4. The meaning of `CommitteeAssociation` is **unresolved** pending the Membership owner (OQ-DR07-1).

**Effect:**
- OQ-DR07-0 (the premise) is **answered**.
- DR-07a proceeds to the Membership owner and the ARB.
- DR-07b stays open under O-16.

**Not resolved by this ruling:**
- O-6 (DR-07 stays **OPEN**);
- O-16;
- G-7;
- T6.

**Not authorised by this ruling:** any implementation.

**Status of each authority, updating §14:**

| Act | Status |
|---|---|
| Principal architectural position (BOTH; A + B for organs) | recorded 2026-11-02 |
| **PO business decision PO-DR07-01** | **recorded 2026-11-02** |
| Membership owner position | **pending** (§16) |
| ARB ruling | **pending** (§17) |
| Implementation authorisation | **none** |

## 16. Decision request to the Membership owner (DR-07a, DR-07b)

**Context:**
- PO-DR07-01 confirms that governance committees are organs.
- The recommendation for organs is **A + B**: Governance owns them, and the legacy Membership committee write model is frozen as the behavioural reference until a Governance model reaches verified parity.
- **Your answer is recorded as given. Nothing is assumed.**

| # | Question | Evidence to check |
|---|---|---|
| M-1 | Is §4's inventory of the committee write model in `app/Contexts/Membership` complete and correct? Which invariants does it enforce today? | `Domain/Committee/*`, `Application/Committee*`, `Infrastructure/Repositories/EloquentCommitteeRepository.php` |
| M-2 | What does `CommitteeAssociation` mean: membership through an organisational unit, or participation in an organ? | `Domain/Membership/CommitteeAssociation.php`, `CommitteeAssociationLifecyclePolicy.php`, `MembershipApplication.php`, `ReviewMembershipApplicationHandler.php:27-38` |
| M-3 | Do you agree that Governance owns governance organs (option A)? If not, why? | ratified §11; PO-DR07-01 |
| M-4 | Do you agree to freeze the legacy committee write model (security and bug fixes only) and keep it as the behavioural reference until verified parity (B)? Proposed freeze wording? | B-1; plan `20261011-0103…:83` |
| M-5 | Operational risks or objections to resolve before any cutover: live users of the legacy endpoints, the `committee_assignments` history, the G-9 end-date defect | register §5 |

## 17. Decision request to the ARB (DR-07a)

| # | Item | Ask |
|---|---|---|
| ARB-1 | Ratified §10 | confirm the reading: an **aggregate** boundary (the Membership aggregate must not absorb committee participation), not a context-ownership rule |
| ARB-2 | Ratified §11 | confirm the reading: "Governance owns … organs" is the **context-ownership** rule; with PO-DR07-01 it places governance organs in Governance; **no amendment** of the ratified act is needed |
| ARB-3 | PO-DR07-01 | take note of the business premise |
| ARB-4 | ADR-0001 CQRS principle | confirm it remains valid (one write model as the source of truth; read models never write) |
| ARB-5 | ADR-0001 location clause | rule on it: under A, a **new ADR** supersedes only this clause. ADR-0001's status is **not** changed until that act exists |
| ARB-6 | Freeze and behavioural reference (B) | adopt the freeze rule and the parity criteria (characterisation tests over the legacy model; shadow; reconciliation) |
| ARB-7 | Conditions before implementation and cutover | at least: Membership owner position (M-1…M-5); G-7 production use verified; store classification (O-16) sufficient for the migration scope; isolated test database (O-4); T6 contract for membership facts |

**Inputs:** this paper §3–§8, §14–§15; register §5 (G-7, G-9, C4).

## 18. DR-07b evidence plan (register O-16)

| # | Question | Evidence needed | Owner | Not to be done |
|---|---|---|---|---|
| U-Q1 | Which context owns organisational units and their lifecycle? | business lifecycle of a unit (create, merge, close), from the PO; relation to geography (Geography / XD-5) and to membership (Membership owner); candidate: Organisation (`OrganisationalUnit`, v1.5, PROPOSED) | PO; ARB for ownership | assuming Organisation owns it |
| U-Q2 | Meaning of `CommitteeAssociation` | Membership owner's answer M-2; the membership-application workflow | Membership owner + PO | inferring it from the class name |
| U-Q3 | Which existing committee records are units and which are organs? | the business definition per type: `CommitteeType` has `central`, `province`, `district`, `ward`, `youth_wing`, `women_wing`, `student_wing`, `diaspora`, `geographic` (`app/Contexts/Membership/Domain/ValueObjects/CommitteeType.php:27-35`); whether a record has positions or assignments; read-only counts per type and environment (**needs authorisation**, like V-7) | PO + Membership owner; Ops for counts | classifying by name or table alone. Types are a hint only: e.g. a `ward` committee may be an organ with a jurisdiction, while a branch in the same ward is a unit |

---

## 19. Addendum: frozen boundary register not cited before (2026-11-02)

**Correction to §3:** §3 omitted a canonical record. The **frozen Round 49-06 Boundary Decision Register** (`docs/architecture/design/Round49-06_Boundary_Decision_Register.md`):
- **BDR-03:** "Appointment (Authority)" is a Confirmed BC that "owns Mandate truth".
- **BDR-07:** Authorization owns no truth and consumes Mandate.

Round 50 (frozen) makes Appointment the producer of `MandateGranted` / `MandateRevoked`.

**Effect:**
- Option A must state its relation to BDR-03. That is ARB-8 in the ARB pack (`2026-11-02-organisation-governance-arb-decision-pack.md` §3).
- Recommendation: Governance is the context BDR-03 names "Appointment (Authority)", re-issued via ADR.
- The recommendation A + B is otherwise unchanged.

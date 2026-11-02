# Governance domain: discovery map, roadmap and readiness (strategic DDD discovery)

**Date:** 2026-11-02 · **Author:** Claude (DDD architect / requirements engineer; authority: generated)
**Status:** RESEARCH / PROPOSAL. It decides nothing, ratifies nothing and authorises nothing.
**Scope:** organisation and governance. Election is out of scope: election records are cited only as evidence of shared constraints.
**Baseline:** branch `election-flow-updated`, HEAD `d812c937d`.
**Placement:** `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0).
**Companion:** Decision D proposal, `2026-11-02-organisation-xd15-tenancy-and-data-ownership-decision-proposal.md`.

**Labels:**
- **[F]** fact from current source;
- **[R-D]** ratified or approved;
- **[P]** proposed (decision record or candidate architecture);
- **[H]** hypothesis;
- **[R]** recommendation;
- **[O]** open question.

**Code facts** marked [F, R2] were verified in the Principal Reviewer Round 2 review (HEAD `02fbb59af`). The commits since then touch no Membership, committee or geography code.

---

## 1. Current-state verification

| Record | Status (verified in file) |
|---|---|
| Ratified Membership architecture (2026-10-08) | **[R-D] ratified** |
| PBDIGIT-30 B1 / B10 | **[R-D] approved by the PO, 2026-08-06** |
| INV-ORG-SCOPE-1 | **principle approved by the PO, 2026-11-01; ARB ratification pending** |
| XD-3a, XD-3b, XD-3c, Decision C, INV-AFF-1, XD-PROV (with AM-1 r2) | [P] PROPOSED |
| BR-1…BR-12 | BR-1…8 confirmed by the PO; BR-10…12 PO direction; BR-9 open; BR-12 needs a B1 amendment (K7) |
| XD-15 (tenancy), DR-07 (committee ownership), XD-4 (authorisation), XD-5 (geography history) | [O] open; decision fields blank (`2026-10-11-cross-domain-decision-round-1-sheet.md`) |
| v1.5 cross-domain ontology (Committee as organ, Position, Appointment, Tenure, Mandate, JurisdictionAssignment, Communication) | [P] CANDIDATE (`…/cross_domain_brainstorming/20261011_0008_cross_domain_discovery_package_v1.5.md:60-100`) |
| Committee discovery questions CQ-1…CQ-10 | [O] (`2026-10-09-committee-responsibility-authority-and-membership-boundary-discovery.md:189-198`) |
| Business questions BQ-P1/P2/P3/P8, BQ-J1, BQ-U1, BQ-F1…F3, BQ-P9 | [O] (v1.5 `:344-355`) |
| ADR-0001 (committee write model in Membership) | **Accepted** (`docs/adr/ADR-0001-committee-read-write-separation.md:20-21`). **Corrected 2026-11-02:** it is **not** directly contradicted by a ratified rule. Ratification act §10 bars the Membership **aggregate** from absorbing committee participation (`2026-10-08-membership-architecture-ratification-act.md:283-304`); ADR-0001 places the committee aggregate in the Membership **context**. Note N-6 reads §10 at context level, but notes "are … not decisions" (`:8-10`). **DR-07 must settle the reading**; a superseding ADR is needed only if the context-level reading is adopted. |
| PBDIGIT-317 (secrets written to the log) | `OPEN`, priority high. The log statements are still present at `app/Http/Controllers/Demo/DemoVoteController.php` lines 4837, 4876, 4879 (line numbers only; no values reproduced) |

**Changes since the previous status report:** none to these records. The only commits since are KnowledgeOS documents.

**Conflicts with newer decisions inside the v1.5 candidate** (not resolved here):

| v1.5 states | Newer record states | Owner |
|---|---|---|
| "Account: not 1:1 with Person" | XD-3b B2 [P]: 0..1 account per Person | ARB |
| "Organisation: tenancy = one shared database" | Decision D proposal, option C | PO + ARB |
| "OrganisationAffiliation = platform access / roles" | Decision C (User role) and INV-AFF-1 (organisation-local) [P] | ARB |

## 2. Ubiquitous language: governance concepts and conflicts

| Term | Working definition | Owner (candidate) | Evidence / status | Conflicts and open points |
|---|---|---|---|---|
| **Person** | a human; identified by Identity | Identity | [R-D] ratified | — |
| **Member** | a Person holding a Membership in an organisation | Membership | [R-D] ("Member" aggregate, EM-VOC-001) | **The word is overloaded** [F]: `user_organisation_roles.role = 'member'` (registrant / any affiliation), `organisation_users.role` enum `member`, committee role "member", and `MemberId` (a party member number). Decision C DC-R4 [P] drops "member" for registrants. |
| **Membership** | the enduring relation Person ↔ Organisation; periods; append-only facts | Membership | [R-D] | — |
| **OrganisationAffiliation** | access and roles of a Person in an organisation, e.g. User (Decision C); whether Guest and staff are affiliation roles is open, and Observer is overloaded (see the rows below) | Organisation | [R-D] EM-VOC-002; Decision C [P] | **two live stores** [F]: `organisation_users` (enrolment), `user_organisation_roles` (roles); one role per person per organisation (F-C-2) |
| **Organisation** | the recognising body (a business concept). The **tenant** is its technical isolation and execution context, not a domain concept of its own. | Organisation | [R-D] (ratified ontology) | PublicDigit's status: K7 |
| **OrganisationalUnit** | a branch, chapter, wing or department (structure) | Organisation | [P] v1.5 | **wings are modelled as committee types today** [F, R2] (`CommitteeType`: `youth_wing`, `women_wing`, …) |
| **Committee** | an **organ**: a constituted body with lifecycle, term and positions | Governance (target) vs Membership (ADR-0001) | [P] v1.5; **[O] DR-07** | the code holds the committee aggregate under `app/Contexts/Membership/Domain/Committee/` [F] |
| **Geographical committee** | [H] a committee whose constitution binds it to a jurisdiction (e.g. ward, district, province committee) | Governance | [F, R2]: types `ward` / `district` / `province` must carry `operational_geo_reference` (CHECK); `central` must not | **distinguish** from a committee that merely *acts* in an area [H]; type ↔ level mapping open (C1); four inconsistent level scales (C10) [F] |
| **Position** | an office or seat of a committee; capacity κ; eligibility ε; outlives its holders | Governance | [P] v1.5 | code: `role_path` + role limits keyed by name, so limits are **never enforced** [F, R2 C11] |
| **Role** | **ambiguous** [F]: (a) PositionKind (chair, treasurer: the organisational meaning, v1.5); (b) an organisation access role (owner, admin, commission); (c) a Spatie role (global, not tenant-scoped); (d) a dashboard role | — | [O] | **must be split in the language** [R]: PositionKind (Governance) vs AccessRole (Organisation / Authorisation) |
| **Appointment** | the act opening a tenure, with provenance and authority evidence | Governance | [P] v1.5 | three live assignment stores with different lifecycles [F, R2 C4/C8] |
| **Tenure** | Person holds Position over `[s, e)`; derived from Appoint / Vacate facts | Governance | [P] v1.5 | the live save rewrites `left_date` of ended assignments [F, R2 C8] |
| **EffectiveTenure** | Tenure ∧ ε(Membership) ∧ CommitteeActive | Governance | [P] v1.5; **[O] BQ-P2 / P3** | g14 S1: derive, never cascade |
| **Mandate** | the scope of authority of a position or organ (decision kinds × scope) | Governance | [P] v1.5 | CQ-4, CQ-10 open |
| **Delegation** | a time-bounded transfer of authority (never duty) | Governance | [P] v1.5 | CQ-8, BQ-DEL open |
| **Jurisdiction (Assignment)** | the geographic units a committee answers for, over time | Governance | [P] v1.5; **[O] BQ-J1** (exclusivity) | live: 0..1 `geo_unit_id` + parallel path columns [F, R2] |
| **GeoUnit** | an administrative area in a hierarchy | Geography | [F] `geo_administrative_units` with `parent_id`; official (NULL org) and organisation-owned units | revision history missing (C6, XD-5) |
| **Permission** | `(action, resourceType)` capability | Authorisation | [P] v1.5 | live: role-name policies, organisation-scoped (Stage 1 item 2) [F] |
| **Guest** | [H] time-limited, non-member participation in an organisation | Organisation (affiliation) [O] | [F] `organisation_participants.participant_type = 'guest'` with `expires_at`, `permissions` JSON; requires an account (`user_id`) | lifecycle vs simple access: [O] |
| **Worker / staff** | [H] a person working for an organisation | [O]: affiliation role, or a Workforce context **only if** employment is a business requirement (none found) | [F] `staff` in `organisation_participants` and the `organisation_users.role` enum; **no "worker" or employment model** | [O] |
| **Observer** | **overloaded** [F]: (a) an election roster role (`election_memberships.role = 'observer'`); (b) **an associate-member type, "observer / no voting rights"** (`app/Models/MembershipType.php:56`), i.e. a kind of **Membership**; (c) a **default authorisation label** for actors without a role (`app/Models/Election.php:1868`) | (a) Election; (b) Membership; (c) none: an artefact of authorisation | [F] | **split required** [R]; a generic "observers need no Membership" is false for (b) |
| **ElectionMembership** | [F] one record per (user, organisation, election) with `role` ∈ {voter, candidate, observer, admin, committee} and its own status / expiry; references the **enrolment** `(user_id, organisation_id)`, not Membership (`database/migrations/2026_03_17_213212_create_election_memberships_table.php`) | Election (out of scope) | [F] | **conflates** roster / eligibility, candidature, observation and committee seats in one role column. Boundary relevance only: Election must read Membership / enrolment, never own a second Membership lifecycle (ratified boundary) |
| **Working Organisation** | the organisation a request runs in | Platform / tenancy | [R-D] PBDIGIT-30 | — |
| **Forum / Topic / Post** | communication space / thread / contribution | Communication | [P] v1.5 | **no forum in code** [F]; legacy "Openion" wall only; a competing "Collective Intelligence" concept is NOT APPROVED |

## 3. Relationship map (typed)

Cardinalities are given only where a record states them. Everything else is [H].

| From | Relation | To | Type | Status |
|---|---|---|---|---|
| Person | identified by | Identity | identity | [R-D] |
| Person | has 0..1 | Account | identity | [P] XD-3b |
| Person | has 0..1 per Organisation | Membership | organisational | [R-D] (one Membership per (p, o)) |
| Person | has 0..n per Organisation | OrganisationAffiliation | organisational | [P] (roles coexist, DC-R5); store supports 1 [F] |
| Organisation | contains 0..n | OrganisationalUnit | organisational | [P] v1.5 |
| Organisation | constitutes 0..n | Committee | governance | [F] (`committees.organisation_id`); owner [O] DR-07 |
| Committee | may have a parent | Committee | governance (tree) | [F] `parent_committee_id` FK; acyclicity not enforced [H] |
| Committee | has 0..n | Position | governance | [P] |
| Position | capacity κ | — | governance constraint | [P]; [O] BQ-P1 |
| Person | appointed to (Appointment) | Position | governance + temporal | [P] |
| Appointment | opens | Tenure `[s, e)` | temporal | [P] |
| Tenure | effective only if | Membership (ε) | temporal + organisational | [P]; **[O] BQ-P2 / P3** |
| Position | carries | Mandate | governance + authorisation | [P]; [O] CQ-4 / CQ-10 |
| Committee | answers for, over time | GeoUnit (JurisdictionAssignment) | geographic + temporal | [P]; [O] BQ-J1 |
| GeoUnit | parent of | GeoUnit | geographic (tree per country) | [F] `parent_id` |
| Organisation | owns 0..n custom | GeoUnit | geographic | [F] (`organisation_id` on units) |
| Member | resides in 0..1 | GeoUnit | geographic | [F] `members.residence_geo_unit_id`; owner [O] |
| Permission | derived from | AccessRole ∪ EffectiveTenure × Mandate × Jurisdiction | authorisation | [P] XD-4 direction; **not** from Membership alone [R-D] |
| Forum | owned by | Organisation / Committee / unit | communication | [P] v1.5; [O] |
| Forum participation | decided by | Authorisation (policy) | authorisation | [P] |

**Two hierarchies, not one** [F + H]:
- the committee tree (`parent_committee_id`);
- the geography tree (`parent_id`).

They are related only through JurisdictionAssignment. The code shows they do not coincide: `central` has no geography, and wings have optional geography.

## 4. Bounded-context candidates

| Context | Owns (authoritative) | Protects (invariants) | Consumes | Unresolved |
|---|---|---|---|---|
| **Identity** (platform) | Person (bare id), Account, platform service relationship, claim-binding | one account per Person; explicit merges (C-5) | — | XD-3a option D; OQ-PROV-7 |
| **Organisation** | registry, OrganisationAffiliation, OrganisationalUnit, provenance | INV-ORG-SCOPE-1; INV-AFF-1 | Identity (`PersonRef`) | XD-2, OQ-C-5 (which store), K7 |
| **Membership** | Membership, periods, facts | ratified INV-M, INV-6 / 14 | Identity; Governance (authority evidence for acts, CQ-3) | BQ-P2 / P3 (via Governance) |
| **Governance** | Committee, Position, Appointment, Tenure, Mandate, Delegation, JurisdictionAssignment, PositionHoldings | κ, EffectiveTenure, jurisdiction rules | Membership (ε), Geography (units, revisions), Identity | **DR-07**, CQ-1…10, BQ-P1/P8/J1 |
| **Geography** | GeoUnits and hierarchy; revisions | hierarchy acyclic; revision immutability | — | XD-5, OQ-OS-1, C10 |
| **Authorisation** | policy semantics, decisions and decision records | INV-AUTH-OWN (grants only on own resources) | every context's facts and policy modules | **XD-4**, XD-7 |
| **Communication** | Forum, Topic, Post, Moderation | moderation evidence | Authorisation, Identity | product choice (forum vs Collective Intelligence); BQ-F1…F3, BQ-P9 |
| Election | its own bounded context ([R-D] "Election owns entitlement and eligibility", `2026-10-08-membership-final-ratification-verdict.md:46`); **design out of scope here** | eligibility is Election's decision; Membership alone grants nothing (G-7) | **two voter sources, chosen per organisation** [F]: **enrolment** (`organisation_users`) when `uses_full_membership = false`, **Membership** when `true` (`app/Domain/Election/Enum/VoterSourceStrategy.php:18-28,127`); Governance (officers) | CQ-1 vocabulary collision. Boundary facts [F]: **two context folders** (`app/Contexts/Election`, `app/Contexts/Elections`); `ElectionMembership` **combines** roster, candidature, observation, admin and committee roles (§2); two representations of electoral-body roles: `election_officers` (`chief / deputy / commissioner`) and `election_memberships.role = 'committee'`. **Whether they denote the same body, and which context owns it, is open (CQ-1).** The two folders are a codebase fact; whether they are separate contexts, parallel implementations or a legacy / current split is **not established**. |

## 5. Context map (draft; dependency direction = consumer → provider)

| Downstream (consumer) → Upstream (provider) | Contract (published language) | Authoritative or projection | Consistency | Failure behaviour | Boundary |
|---|---|---|---|---|---|
| Organisation, Membership, Governance → Identity | `PersonRef` (per organisation, preferred direction); claim-binding | authoritative in Identity | eventual (Δ) across databases | UNKNOWN → deny (XD-7 K2 [P]) | platform ↔ organisation |
| Governance → Membership | `isMember(org, person, asOf) → T / F / U + version` (MembershipQueryPort) | authoritative in Membership | same organisation database → transactional | U → not effective | organisation-local |
| **Membership → Governance** | authority evidence for membership acts (`AuthorityCheckPort`, CQ-3) | authoritative in Governance | same organisation database | no evidence → refuse | organisation-local |
| Governance → Geography | `unit / contains(org, id, revision)` | official: platform reference; custom: organisation | read-only, pinned revision | missing revision → U | platform ↔ organisation |
| Authorisation → all | policy modules owned by each context | authoritative per context | evaluated per request | U → deny | inside the Working Organisation |
| Communication → Authorisation | `may(actor, forum.action, forumRef, t)` | decision record | per request | deny | organisation-local |

**Circular dependency exposed:**
- Governance consumes Membership (ε, for EffectiveTenure).
- Membership consumes Governance (which organ may admit, exclude or suspend, CQ-3).

[R]: acceptable as two **separate published languages** in opposite directions. Membership facts flow up; *authority evidence* flows the other way. Neither context writes the other's facts. It must be recorded explicitly when DR-07 is decided, otherwise it becomes a hidden cycle.

## 6. Business authority vs data ownership (governance facts)

| Fact | Business authority: may establish / change / end | Data owner (stores) | Consumers |
|---|---|---|---|
| Membership admit / end / annul | an organ per statute [O] CQ-3; the person (resignation) | Membership | Governance, Authorisation, Election |
| Affiliation (User, Worker, …) | registration (User); the organisation per its rules | Organisation | Authorisation, Communication |
| Committee constitute / dissolve | an organ per statute [O] CQ-2 / CQ-10 | Governance [O] DR-07 | Authorisation, Communication |
| Appointment / vacate | the appointing organ or election result [O] | Governance | Authorisation, Membership (evidence) |
| Mandate / delegation | statute; delegating holder [O] CQ-8 | Governance | Authorisation |
| Jurisdiction assignment | an organ per statute [O] | Governance | Authorisation, Communication |
| GeoUnit (official) | platform / official sources | Geography (platform) | Governance, Organisation |
| GeoUnit (organisation-custom) | the organisation | Geography (organisation) [O] OQ-OS-1 | Governance |
| Permission decision | none: derived | Authorisation (decision record) | all |

## 7. Invariants (candidate; formal where it adds precision)

| ID | Business rule | Formal | Status | Checked |
|---|---|---|---|---|
| G-1 | a committee tree has no cycles | parent ⊂ C × C is a forest | [H] | locally (Governance) |
| G-2 | position capacity | ∀ s, t: \|{p : tenure(p, s, t)}\| ≤ κ(s) | [P]; κ [O] BQ-P1 | locally |
| G-3 | effective tenure needs eligibility | eff(p, s, t) ⇔ tenure(p, s, t) ∧ ε(p, o(s), t) ∧ active(c(s), t) | [P]; ε [O] BQ-P2 / P3 | same organisation database |
| G-4 | jurisdiction within reach | J(c, t) ⊆ Official ∪ Custom(o(c)) | [P] (X-TEN) | locally |
| G-5 | jurisdiction exclusivity (if decided) | ∀ u, t, k: \|{c : kind(c) = k ∧ u ∈ J(c, t)}\| ≤ 1 | **[O] BQ-J1** | locally |
| G-6 | historical jurisdiction replay | decisions pin a geography revision r; J is evaluated at r | [P] XD-5 | Governance + Geography |
| G-7 | permission is never implied by Membership alone | ¬(member ⇒ permit) | **[R-D]** ratified boundary | Authorisation |
| G-8 | organisation scope | owner(r) = WO(request) | **[R-D principle]** INV-ORG-SCOPE-1 | every context |
| G-9 | appointment history is append-only | no update or delete of ended tenure facts | [P] TR-1 / INV-HIST; **violated today** [F, R2 C8] | Governance |

## 8. Dependency-aware roadmap

| Order | Item | Why here |
|---|---|---|
| 1 | **Decision D (XD-15) + D-2 (cross-organisation rules?)** | every other context's storage and consistency assumptions depend on it; D-2 decides whether any cross-organisation invariant exists |
| 2 | **E: DR-07 committee ownership** | Governance must have an owner before its contracts can be stated; it also decides where the existing committee code stands (ADR-0001 vs N-6) |
| 3a | **F: BQ-P2 / P3 (Membership ↔ tenure)**, in parallel with 3b | needs E (who owns tenure); PO business questions, no model blocker |
| 3b | **G: geography and jurisdiction** (XD-5, BQ-J1, OQ-OS-1, C10 scale) | needs D (official vs custom placement) and E (owner of JurisdictionAssignment) |
| 4 | **H: authorisation (XD-4, XD-7)** | its structure (who owns policy modules) could be decided early. Its **content** needs E, F and G: permissions derive from tenure × mandate × jurisdiction. [R]: decide XD-4's *ownership* question alongside E; the policy content after F and G. |
| 5 | Communication / forum | needs H and the product choice |
| always | ratifications (INV-ORG-SCOPE-1 ARB), PBDIGIT-317 (security, independent) | — |

**Deviation from the original A–H order:** F and G run in parallel after E, and H is split (ownership early, content late). Reason: H's content consumes the outputs of E, F and G, while its ownership model does not.

## 9. Implementation readiness

| Dimension | Status | Basis |
|---|---|---|
| Domain understanding | **In progress** | identity / affiliation: Proposed; governance: candidate vocabulary (v1.5) + code facts; no decisions |
| Ubiquitous language | **In progress** | §2; "member" and "role" conflicts unresolved |
| Bounded contexts | **Proposed** (candidates) | §4; DR-07 open |
| Context map | **In progress** (draft) | §5; one cycle exposed |
| Business authority | **Not started** for governance | CQ-2 / 3 / 4 / 10 open; statutes not in the corpus |
| Data ownership | **Proposed** (identity side); **In progress** (governance) | Decision D proposal |
| Domain invariants | **Proposed / Open** | §7; G-8 principle approved; G-9 violated today |
| Security boundaries | **In progress** | INV-ORG-SCOPE-1 principle approved; Stage 1 findings; PBDIGIT-317 open |
| Requirements traceability | **In progress** | BR-1…12, CQ / BQ registers; no single register yet |
| Tactical design | **Not started** for governance | — |
| Implementation planning | **Not started** | EP-01 plan required after decisions |
| Testability / operations | **Not started** | isolated test database missing (ARB-SEC-01 FAIL) |

## 10. Prioritised backlog

| ID | Problem | Why it matters | Depends on | Evidence needed | Output | Authority | Acceptance | Status | Next action |
|---|---|---|---|---|---|---|---|---|---|
| T-01 | XD-15 tenancy target | foundation for every context | — | this proposal; U-1…U-5 | decision record | PO + ARB | decision fields filled; INV-DB-1 / 2 recorded | proposed | PO decision on D-1 |
| T-02 | cross-organisation business rules? | decides whether any cross-organisation invariant exists | — | statutes / PO knowledge. **Corpus searched 2026-11-02:** no rule found; recorded as missing evidence (`20261010_1212…:34`); open question BQ-INC ("global, per org, per committee kind?") | rule list or "none", plus the BQ-INC answer | PO | answer recorded in BR | open | ask the PO (with BQ-INC) |
| T-03 | DR-07 committee ownership | Governance owner; ADR-0001 vs N-6 | T-01 | decision sheet B-1 | ADR superseding ADR-0001 or a change request | PO + ARB | ADR accepted | open | prepare the decision paper |
| T-04 | "role", "member" and "observer" vocabulary split; Guest / staff / ElectionMembership definitions (Q1–Q5 of the 2026-11-02 relationship analysis; code evidence in §2) | ambiguity drives defects (C5, C12, F-C-3) | — | §2 | glossary entries | ARB | glossary adopted | in progress | draft after T-03 |
| T-05 | BQ-P2 / P3 Membership ↔ tenure | EffectiveTenure | T-03 | g14 S1, CQ-5 | business rules | PO | rules recorded | open | after T-03 |
| T-06 | geography: official vs custom placement, XD-5, BQ-J1, the C10 scale | jurisdiction model | T-01, T-03 | Round 2 C1 / C6 / C10 | decision records | ARB + PO | recorded | open | after T-03 |
| T-07 | XD-4 authorisation ownership | permission model | T-03 (content: T-05, T-06) | v1.5 §2 | decision record | ARB | recorded | open | with T-03 |
| T-08 | committee business authority (CQ-2 / 3 / 4 / 10) | who may do what | T-03 | statutes | authority matrix | PO | matrix approved | open | request statutes |
| T-09 | forum product choice and BQ-F1…F3, BQ-P9 | Communication context | T-07 | forum docs | decision | PO | recorded | open | later |
| T-10 | consolidated decision and risk register | one source of status | — | all records | register document | ARB | adopted | not started | after T-01 |
| T-11 | ARB ratification of INV-ORG-SCOPE-1; review of the PROPOSED records | governance | — | records | ARB acts | ARB | acts recorded | pending | schedule an ARB session |
| T-12 | **PBDIGIT-317 secrets in logs** | security | — | backlog item | PO / Security decision | PO + Security | logs handled; fix authorised | open | **independent, urgent** |
| T-13 | isolated test database | prerequisite for any test | — | ARB-SEC-01 | database assigned | PO / Ops | gate PASS | open | PO / Ops |
| T-14 | scale figures U-1…U-5 | discovery and topology feasibility | — | ops data | figures | PO + Ops | recorded | open | ask Ops |

## 11. Machine learning

**Not justified at this stage.**
- The governance rules are deterministic and statute-driven.
- The corpus is small enough for direct reading.
- The only plausible use is **terminology discovery** over the document corpus, e.g. clustering uses of "role" and "member". Its value would be minor against a direct review, and it would need human validation. It is not proposed.

## 12. Traceability

- Decision D proposal (companion).
- v1.5 discovery package; decision sheet 2026-10-11; committee discovery 2026-10-09.
- Round 2 review findings C1–C12.
- Stage 1 static investigation.
- XD-3a / XD-3b / XD-3c, Decision C, INV-AFF-1, INV-ORG-SCOPE-1, XD-PROV records.
- PBDIGIT-30.

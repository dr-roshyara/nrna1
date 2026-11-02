# Foundation boundary and ubiquitous-language audit (consolidated)

**Date:** 2026-11-02 · **Author:** Claude (DDD architect; authority: generated)
**Status:** **REPORT. It decides, accepts, ratifies and authorises nothing.** No decision status in any other record is changed by this document. Recommendations are marked [R].
**Placement:** `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0); `reviews/` convention.
**Baseline:** branch `election-flow-updated`, HEAD `620e90c3a`. No commit touching `app/`, `database/`, `routes/`, `docs/publicdigit` or `docs/adr` between `9722efe49` and `620e90c3a`.
**Method:** read-only inspection (`git`, `grep`, `sed`). No tests run; test statuses are cited from their records.

**History of this audit (one conversation, 2026-11-02):**
1. Foundation audit (first pass).
2. Validation pass. **It corrected the first pass:** the first pass said a Person/Account decision had to come first. That was wrong. The ratified Membership artifact already provides what bounded discovery needs, so **the minimum set of prerequisite decisions is empty**.
3. Bounded Membership discovery, within the limits from step 2 (findings summarised in §11).
4. This consolidation. Future rounds should cite this file instead of re-running the audit, and re-verify only what changed after the baseline above.

**Evidence labels:**

| Label | Meaning |
|---|---|
| RATIFIED | a ratification record |
| DECIDED | a PO decision on record |
| DECIDED, NOT RATIFIED | PO-approved; the ARB act is pending |
| PROPOSED | a recorded proposal, not accepted |
| OPEN | explicitly unresolved |
| NOT EVIDENCED | no source establishes it |
| CONFLICTING | the sources disagree |
| [I] | implementation fact (code, schema, test) |
| [Inf] | inference |

---

## 1. Executive conclusion

**Conditional GO: bounded Membership discovery only (recommendation B).**

The inside of Membership (concepts, lifecycle, invariants) rests on ratified decisions:
- Person = `PersonId`, owned by Identity;
- affiliation ≠ Membership;
- Admission opens a period;
- Membership never grants voting entitlement.

**No foundational decision blocks it.**

Membership's **outer edges** depend on open questions, which block only those edges:
- the link to affiliation and to accounts;
- the Election contract;
- unit belonging;
- the direction of settlement with Finance;
- physical persistence.

## 2. Decisions that already settle the foundation

| Decision | Source | Status |
|---|---|---|
| Person = `PersonId`, owned by Identity. OrganisationAffiliation (access and roles) is owned by Organisation and is **not** Membership. Admission opens a period. Eligibility is Election's predicate. | `2026-10-08-membership-architecture-ratified.md` §1 (`:39-47`), §4 | RATIFIED |
| Context ownership: Governance owns competence, organs and authority; Election owns ElectionMembership and entitlement; Finance owns settlement. No shared persistence. | `2026-10-08-membership-architecture-ratification-act.md` §10–§12; verdict `:46` | RATIFIED |
| Organisation is the business concept; the tenant is only its technical isolation | ratified ontology (governance map `2026-11-02-governance-domain-discovery-map-roadmap-and-readiness.md` §2) | RATIFIED |
| Registration ≠ Admission ≠ VotingEntitlement ≠ Exercisability. Admission is **derived, never stored**, and evaluated fresh. The admission policy is locked from ReadyForVoting. | `docs/publicdigit/adr/ADR_20261004_1549_…` (points 1, 8, 11); `ADR_20261005_0950_Voting_Authorization_Boundary.md` (F8, I2) | DECIDED / APPROVED |
| BR-1…BR-8, BR-10…BR-14 | `business_rules/20261101_1739_person_account_affiliation_business_rules.md` | DECIDED (PO) |
| PBDIGIT-30 B1 with K′: the default organisation is infrastructure and never a Working Organisation. Real organisational life is in a separate, ordinary operator organisation. | PBDIGIT-30; `2026-11-02-organisation-k7-b1-amendment-decision-brief.md` | DECIDED (PO) |
| Governance organs ≠ organisational units | PO-DR07-01 | DECIDED (PO) |
| Membership never decides election eligibility | `2026-11-02-membership-election-separation-decision-record.md` | DECIDED (PO) |
| Organisation-scoped access | `2026-11-01-organisation-inv-org-scope-1-…` | DECIDED, NOT RATIFIED |
| Person ≠ Account, 0..1 accounts per Person (XD-3b); account lifecycle R-XD3c-1…5 (XD-3c); Decision C DC-R1…R9; INV-AFF-1 | records dated 2026-11-01 | PROPOSED |

What BR-1…BR-14 settle:
- Registration makes a person a **User** of the default organisation, not a Member.
- A person can exist without an account.
- A person can relate to several organisations.
- Importing someone creates no User affiliation.
- Separate imports are not assumed to be the same person.
- There are no business rules across organisations.
- Which positions are incompatible is decided per organisation.

## 3. Foundation readiness matrix

| Area | Status | Remaining question | Blocks Membership? | Next action |
|---|---|---|---|---|
| Basic Setup | **NOT EVIDENCED** (the term appears nowhere in `docs`, `app`, `routes`, `resources/js`) | The code has three separate mechanisms: bootstrap organisation (`organisations.type='platform'`, `is_default`); founding an organisation (`OrganisationController::store`: `type='tenant'`, the creator gets `user_organisation_roles.role='owner'`, `uses_full_membership`); demo setup (`DemoSetupController`) [I] | No | your wording, optional (G-4) |
| Person and User | **PARTIALLY COMPLETE** | Settled: Person/`PersonId` (RATIFIED); a person without an account (BR-5). PROPOSED: 0..1 accounts per Person, lifecycle R-1…R-5. OPEN: K3/K4 (how `PersonRef` is produced), B3, BR-11 merge policy, OQ-3b-2, OQ-3c-1/2 | Only the identity edge | G-1 (optional) |
| Default Organisation | **DECIDED** (B1 + K′, BR-1, BR-12) | Production data check (needs Ops authorisation) | No | — |
| Individual Organisation | **NOT EVIDENCED** as a term; nearest concept is an ordinary organisation (`type='tenant'`) [Inf] | What the PO means by it | No | G-4 |
| User–Organisation relationship | **PARTIALLY COMPLETE**: meaning RATIFIED (EM-VOC-002); storage OPEN (BR-9, OQ-C-5) | Several roles per person (DC-R5); not a login gate (DC-R8, OQ-C-0) | Only the Member ↔ affiliation edge | G-2 (optional); INV-AFF-1 evidence U-1…U-5 |
| Membership terminology | **COMPLETE AND RATIFIED** | — | No | — |
| Election participation terminology | **RATIFIED / DECIDED** | three voter stores in code [I] | No | Election's work |
| Guest and Observer | **PROPOSED / CONFLICTING** (Observer has three meanings) | — | No | G-3 |
| Worker / Staff | **PROPOSED**; no employment model [I] | — | No | G-3 |
| Role and Permission | **OPEN** (O-14 marked READY in the ARB pack; XD-4) | — | No | G-3; XD-4 |
| Context ownership | **RATIFIED** for the core; DR-07a and O-16 OPEN | — | Only the unit edge | — |
| Cross-context contracts | **PARTIALLY COMPLETE**: §8 outlines RATIFIED; OQ-ME-3/4, Q-new-1/2 OPEN | — | Only integration | ARB items |
| Lifecycle and timing rules | **RATIFIED** for Membership and Election admission; XD-3c PROPOSED | — | No | — |
| Traceability to the code | **PARTIALLY COMPLETE** (§8) | — | No | — |

## 4. Ubiquitous language

The terms adopted are those of governance map §2 and ARB pack §2 (O-14). This table consolidates them; it does not compete with them.

| Term | Meaning | Kind | Owner | Status | Ambiguity → [R] action |
|---|---|---|---|---|---|
| Person | a human | person | Identity | RATIFIED | — |
| Account | the sign-in credential; 0..1 per Person | account | Identity | PROPOSED (XD-3b) | "User" is also used for the account → say "Account" |
| User | the affiliation role given at registration with the default organisation | relationship role | Organisation | DECIDED (BR-2) | the code writes `role='member'` (`RegisterController.php:63`) |
| OrganisationUser | the enrolment table `organisation_users` | implementation name | — | not a domain term | do not use in the business language |
| Affiliation (OrganisationAffiliation) | access and roles of a Person in an organisation | relationship | Organisation | meaning RATIFIED; storage OPEN | two stores, each allowing one role per person [I] |
| Member / Membership | a Person holding a Membership / the lasting Person ↔ Organisation relationship, made of periods | person-in-role / relationship | Membership | RATIFIED (EM-VOC-001) | "member" is also an affiliation role, a committee role and `MemberId` [I] |
| Voter | a person on an election's voter list (`ElectionMembership` role `voter`) | election role | Election | RATIFIED | three stores: `voters`, `voter_registrations`, `election_memberships` [I] |
| Election Participant / ElectionMembership | one roster record per (person, organisation, election) | election relationship | Election | RATIFIED (§11) | one `role` column holds voter, candidate, observer, admin and committee [I] |
| Election Registration / Admission / VotingEntitlement | three distinct steps. Admission (all requirements met) is evaluated inside entitlement; derived, fresh, never stored | decision | Election | DECIDED | — |
| Guest | time-limited participation without Membership | affiliation capacity | Organisation | PROPOSED (Decision C) | lifecycle open; requires an account (`organisation_participants.user_id`) [I] |
| Observer | (a) an election roster role; (b) a "no voting rights" membership type (`MembershipType.php:56`); (c) the default authorisation label (`Election.php:1868`) | three concepts | Election / Membership / none | CONFLICTING | context-qualified names: ElectionObserver; observer membership type (also depends on BD-GVR); (c) is not a domain term |
| Worker / Staff | a person working for an organisation; not Membership | affiliation capacity | Organisation | PROPOSED | "platform staff" (`users.platform_role`) is a separate concept; no Workforce context unless employment becomes a requirement |
| Administrator | (a) organisation owner/admin access role; (b) platform administrator; (c) election admin role | three roles | Organisation / Platform / Election | NOT EVIDENCED as one term | three names |
| Organisation | the recognising body | organisation | Organisation | RATIFIED | — |
| Organisational Unit | a branch, chapter or wing | structure | Organisation (candidate) | PROPOSED; ownership OPEN (O-16) | wings are modelled as committee types [I] |
| Governance Organ / Appointment | a constituted body with positions / the act that opens a term of office | — | Governance | premise DECIDED (PO-DR07-01); ownership OPEN (DR-07a) | committee code is inside `app/Contexts/Membership` [I] |
| Role | (a) PositionKind; (b) AccessRole; (c) Spatie role; (d) dashboard role | — | — | OPEN (O-14) | split into PositionKind (Governance) and AccessRole (Organisation / Authorisation) |
| Permission | an (action, resource type) pair | capability | Authorisation (owns no truth, BDR-07) | PROPOSED | XD-4 |
| Access | being allowed to act in a Working Organisation | decision | Authorisation, on Organisation facts | INV-ORG-SCOPE-1 DECIDED, NOT RATIFIED | the middleware is named "Member" (`EnsureOrganisationMember`) |

### 4a. Member versus Voter

- **Organisational Membership is not part of the Election-only decision.** In ADR_20261004_1549, VotingEntitlement = `registered ∧ role=voter ∧ status=active ∧ org match ∧ ¬deleted`. "Membership" there means the **election roster** (`ElectionMembership`), not organisational Membership.
- **In full-membership organisations**, the voter list is drawn from Membership (ratified §12). Membership facts are *read*; the decision stays Election's (INV-11).
- **A Member may be ineligible** for a given election: eligibility is Election's predicate (§4).
- **A non-member may take part** where Election-only rules apply (EM-EO; BR-6, BR-7).
- **When it is decided:**
  - admission is decided fresh at each attempt to enter or cast a vote, and is never stored (ADR_20261004_1549 point 11; ADR_20261005_0950 "derived, fresh");
  - the admission **policy** is locked from ReadyForVoting (F8);
  - the election's `voter_source_strategy` is immutable (`VoterSourceStrategy.php`).
- **Genuinely open:** OQ-ME-1, what Election does when a participant's Membership ends.

## 5. Outer context boundary matrix

| Fact | Owner | Consumers | Contract today [I] | Deviation | Status |
|---|---|---|---|---|---|
| Person, person sameness | Identity | everyone | the `users.id` foreign key | the account is used as the identity | owner RATIFIED; mechanism OPEN (K3/K4) |
| Account, lifecycle | Identity | Authentication; Election (BR-7) | Eloquent | — | PROPOSED |
| Organisation identity | Organisation | everyone | `organisations` | — | RATIFIED |
| Affiliation (User, Staff, Guest, owner/admin) | Organisation | Authorisation; the Election-only voter list | direct reads; two stores | `role='member'`; organisation access used as the voter list | owner RATIFIED; store OPEN |
| Access decision | Authorisation (no truth of its own) | all | middleware | any role row counts as "member" (`EnsureOrganisationMember.php:119-122`) | INV-ORG-SCOPE-1 DECIDED, NOT RATIFIED |
| Membership, status, type, tenure | Membership | Election, Finance, Governance, Audit | V2 direct reads (baselined, PBDIGIT-318 S1b) | V1, V1b, V2, V3′ | RATIFIED; contract OPEN (OQ-ME-4, Q-new-1) |
| Voter list, admission, entitlement | Election | Audit | Election-internal | three stores | DECIDED / RATIFIED |
| Organ, position, appointment | Governance | Authorisation, Election | committee code inside Membership | G-7, G-9, L-5 | DR-07a OPEN |
| Unit belonging | Organisation (candidate) | Membership, Governance | `committee_associations` | — | O-16 OPEN |
| Payment, settlement | Finance | Membership (Standing) | `MembershipFeePaid` sent Membership → Finance (`MembershipPaymentService.php:35-81`) | direction reversed relative to §8 / N-5 | owner RATIFIED; direction not decided |

Integration style across contexts is **not settled platform-wide** (Q-new-2). The only binding event-only rule is scoped to the Push B loop (Blueprint §11).

## 6. Decisions open to the PO

**None of these blocks bounded discovery.** All are PROPOSED; none is accepted by this document.

### G-1. Person and Account (reduced from the earlier F-2)

> "XD-3b §1 and XD-3c R-XD3c-1…5 are accepted as PO decisions: a Person has 0..1 Account; `PersonRef` is never the account id; ending Membership, revoking access and deleting an account are separate operations. K3, K4, B3, BR-11 merge policy, OQ-3b-2, OQ-3c-1/2 stay open."

- **Does not authorise:** any code change or choice of mechanism.
- **Depends on:** nothing.

### G-2. Affiliation rules (reduced from the earlier F-3)

> "DC-R5 (a person may hold several capacities in one organisation) and DC-R8 (the User affiliation is not a login gate; answers OQ-C-0) are accepted. Affiliation storage stays open (BR-9, OQ-C-5)."

- **Already settled elsewhere:** DC-R1…R4 (RATIFIED EM-VOC-002; DECIDED BR-2, BR-3, BR-8).
- **Risk:** DC-R5 cannot be represented in today's stores. That is recorded as a gap, not something to fix.
- **Depends on:** G-1 (for DC-R8).

### G-3. Vocabulary (was F-4; mostly O-14)

> "The vocabulary of ARB pack §2 is adopted, extended by: Administrator = three terms (organisation AccessRole owner/admin; platform administrator; election administrator); Staff = an affiliation capacity, distinct from platform staff; Guest = an affiliation capacity, not Membership; OrganisationUser = an implementation name, not a domain term; Permission ≠ Role. Ownership marked candidate stays open. No renaming authorised."

- **Depends on:** G-2.

### G-4. "Basic Setup" and "Individual Organisation" (was F-1)

- **Only the PO knows the intended meaning.**
- One possible reading: "Basic Setup" is a workflow (platform bootstrap plus founding an organisation), not a context; an "Individual Organisation" is an ordinary organisation (`type='tenant'`).
- If "Individual Organisation" means something else (for example, an organisation of a single person), the wording must change.
- Needed only if the terms are to be used.

### F-5. Founder / owner: already settled, no new decision

- "Founding creates no Membership" follows from the ratified rule that only Admission opens a period.
- "Owner" is the AccessRole example in ARB pack §2.

### Investigations still needed (not decisions)

- INV-AFF-1 feasibility (U-1…U-5) and a data-protection review.
- A read-only check of production data in the default organisation (needs Ops authorisation).
- Whether G-7 is used in production.

## 7. Recommended sequence

1. **Now:** bounded Membership discovery. Next step [R]: **E0b-S** (types → obligations → dues → Standing).
2. **In parallel, at any time:** G-1, then G-2, then G-3; G-4 if wanted. Also the still-open Membership BD-* business policies (§11). BD-3 and BD-REC0 are already decided (D-3, D-2); BD-CONSENT's basis rule is decided (D-1), and its legal part LQ-1 is open.
3. **Waits for evidence or other decisions:**
   - BR-9 / INV-AFF-1;
   - K3/K4;
   - OQ-ME-1…4;
   - O-16 and DR-07a;
   - the fee direction;
   - D-1, Q-new-1, Q-new-2.

## 8. Implementation versus target

**Confirmed [I]:**

| # | Finding | Evidence |
|---|---|---|
| 1 | Registration writes `role='member'` into the default organisation | `RegisterController.php:63`; second writer `CreateUserOrganisationRole` (PBDIGIT-310) |
| 2 | Access middleware treats any role row as "member" | `EnsureOrganisationMember.php:119-122` |
| 3 | Legacy `members` rows reach the person only through `organisation_users` → `users` (an account). The link has been nullable since `2026_05_16_000000`, with no person reference replacing it. The new Membership code uses `person_ref` (`2026_10_09_100000…:28`). | as cited |
| 4 | Affiliation is held in two stores, each allowing one role per person | `organisation_users` with `unique(organisation_id, user_id)`; `user_organisation_roles` |
| 5 | Election-only mode uses "all active org users" as the voter list | `VoterSourceStrategy.php:18-21` |
| 6 | "Observer" has three meanings | `election_memberships` role (`:24`); `MembershipType.php:56`; `Election.php:1868` |
| 7 | Membership–Election coupling V1, V1b, V2, V3′ | baselined in PBDIGIT-318 S1b |
| 8 | Fee settlement direction is reversed | `MembershipPaymentService.php:35-81` → Finance Income listeners |
| 9 | Committee code is inside Membership | act note N-6 |
| 10 | Founding an organisation logs the owner role as "Organisation membership created" | `OrganisationController.php:353` |

**Inferred [Inf]:** Google sign-up probably fails on the NOT NULL `users.organisation_id` (Decision C, E-5). Not executed.

**Documentation gaps:**
- no definition of Basic Setup or Individual Organisation;
- no single glossary has been adopted (O-14 OPEN).

## 9. Effect on the related open items (statuses unchanged)

| Item | Dependency on the foundation |
|---|---|
| CommitteeAssociation / O-16 | depends on the Organisational Unit concept (G-3 vocabulary; O-16) |
| DR-07a, ARB-6/7/8 | largely independent (PO-DR07-01 already separates organs and units); ARB-7 condition on O-16 depends on the foundation |
| FeePaid direction | independent of the foundation |
| INV-ORG-SCOPE-1 | is itself a foundation item; ARB act pending |
| O-14, XD-4 | O-14 = G-3; XD-4 content depends on the Role split |
| INV-AFF-1 / BR-9 | foundation item; blocks only the Member ↔ affiliation edge |
| Q-new-1/2, OQ-ME-3/4 | persistence and integration edges |
| Legacy Membership migration | blocked by item 3 of §8, D-1, ARB-MEM-01 |

## 10. Bounded Membership discovery: limits

**Allowed** (within ratified canonical §1, §2, §4, §5, §8, §12):
- Membership and its types, including the "observer, no voting rights" associate type (as a classification; BD-GVR);
- periods, Admission, Renew, Suspend/Restore, End(kind), Annul/Void/Correct, tenure;
- Standing derived from fee facts (E0b-S);
- `PeriodTrimmed` / `TrimRevived` as published facts;
- each organisation's own Membership rules (OQ-C-6 applies to the operator organisation).

**Excluded until the named blocker is resolved:**

| Excluded topic | Blocked by |
|---|---|
| How `PersonRef` is produced | K3/K4 |
| Where affiliation is stored | BR-9 |
| Migrating legacy account-bound `members` | §8 item 3; D-1 |
| Physical persistence and cross-context foreign keys | D-1, OQ-ME-3, Q-new-1 |
| The Election contract | OQ-ME-4 |
| Election-only voter-list policy | Election |
| Unit belonging and CommitteeAssociation | O-16 |
| Governance ownership beyond the existing decisions | DR-07a |
| Finance settlement direction | fee-direction decision |
| Authorisation architecture | XD-4 |

## 11. Bounded discovery findings (summary)

**Readiness:** READY WITH BOUNDARY RESTRICTIONS. The ontology, facts, resolution, invariants and aggregate D1 are ratified. Slice 0 and Slice 1a are ACCEPTED (PO, 2026-11-02).

**Not yet built in the new Membership code [I]:**
- Renew, Suspend/Restore, ChangeType, Correct;
- persistence of Annul/Void;
- trim consequences and their events;
- Standing.

End is refused for every actor until G-1 (ending authority) is decided (`InterimRoleAuthority.php:18-19`).

**Legacy conflicts with ratified invariants [I]:**

| Invariant | Conflict |
|---|---|
| INV-6/14 (append-only) | `members` rows are updated in place |
| INV-2 / INV-3 | `status ∈ {active, expired, suspended}` and `ended_at` are independent; "expired" mixes Lapsed and Ended |
| INV-11 | V1b `canVoteInElection`; the `grants_voting_rights` type flag (BD-GVR open, recommended "classification only") |

**Membership business policies already DECIDED by the PO** (`2026-10-09-membership-slice1b-acceptance-review-and-slice2-decision-closure.md:290-296`; summarised in the adopted roadmap as BR-4…BR-8):

| Decision | Content | Settles |
|---|---|---|
| D-1 | every admission carries exactly one recognised basis: a declaration reference, a statutory-rule reference, or attested history (migration only) | the basis part of BD-CONSENT; **LQ-1 (legal) stays open** |
| D-2 | no pending Member: the application stays outside the aggregate; approval records an Admit with the application as `declarationRef` | BD-REC0; how application relates to admission |
| D-3 | ending acts are a closed list: resignation, exclusion, death; `legacy` for attested migration only. Non-payment is a ground for exclusion, not an act; term expiry is derived; organisation dissolution is out of scope | BD-3 |
| D-4 | interim admit/end authority mirrors owner/admin, recording AuthorityEvidence, replaceable by Governance | interim only; GOV-END open |
| D-5(b)(c) | backdating allowed within the invariants; future-dated admissions and endings refused; only the technical date range applies | dating rules |

**Still-open Membership business policies** (decision register `2026-10-08-membership-final-decision-register.md:36-48`, recommendations on file):
- BD-1, BD-2, BD-SUSP, BD-TERM4, BD-TEN, BD-NU, BD-TYPE, BD-GVR, BD-NORM;
- BD-REV-ER (act note N-4; roadmap BR-11, blocks COR-2);
- GOV-END (ending authority; called G-1 in the Slice 2 plan).

**Correction (2026-11-02, same day):** an earlier version of this section, and the bounded discovery report in the conversation, listed BD-3, BD-REC0 and BD-CONSENT as open, and called the two application flows unverified. D-1…D-3 above had already decided them. The delivery roadmap that cites them is ADOPTED (`docs/publicdigit/membership/architecture/brainstorming/cross_domain_brainstorming/20261016_2219_membership_architecture_verification_and_completion_plan.md`, BR-4…BR-8).

**Undiscovered:** E0b-S (types → obligations → dues → Standing): "research-ready", not discovered (`2026-10-08-e0b-membership-architecture-finalization.md:68, :351`).

**Corrections to briefs received on 2026-11-02:**
- the Finance event is `TrimRevived`, not "PeriodRevived";
- no Membership-owned "fee period" exists: Finance's fees reference Membership periods, and Membership derives Standing;
- "Application" is not in the *ratified* ontology, but it is governed by PO decision D-2: the application stays outside the Membership aggregate, and its approval records an Admit (roadmap W7, Slice 8 "application → Admit"). The legacy code has two application flows (`membership_applications`; committee `MembershipApplication::approve()`, which creates a CommitteeAssociation). How each legacy flow maps to D-2 was not verified.

## 12. Traceability

- Ratified: `2026-10-08-membership-architecture-ratified.md`; `2026-10-08-membership-architecture-ratification-act.md`.
- Decision register: `2026-10-08-membership-final-decision-register.md`.
- Election ADRs: `docs/publicdigit/adr/ADR_20261004_1549_Voter_Admission_Policy_And_Voting_Entitlement.md`; `docs/publicdigit/adr/ADR_20261005_0950_Voting_Authorization_Boundary.md`.
- Business rules: `business_rules/20261101_1739_person_account_affiliation_business_rules.md`.
- Identity and Organisation records: `2026-11-01-identity-xd3a/b/c-…`, `2026-11-01-organisation-dc-…`, `2026-11-01-organisation-inv-aff-1-…`, `2026-11-01-organisation-inv-org-scope-1-…`.
- Governance: `2026-11-02-governance-domain-discovery-map-roadmap-and-readiness.md` §2; `2026-11-02-organisation-governance-arb-decision-pack.md` §2; `2026-11-02-governance-dr07-committee-ownership-decision-paper.md`.
- Membership status: `2026-11-02-membership-ddd-status.md`; `2026-11-02-membership-election-separation-decision-record.md`.
- Context map: `docs/publicdigit/architecture/context-map.md` (PROPOSED).
- Register: `2026-11-02-organisation-governance-strategic-design-status-assessment.md`.

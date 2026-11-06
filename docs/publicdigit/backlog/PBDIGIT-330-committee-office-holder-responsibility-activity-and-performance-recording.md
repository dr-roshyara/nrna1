# PBDIGIT-330: committee office-holder responsibility, activity and performance recording (strategic discovery)

**Type:** Strategic discovery / domain discovery · **Created:** 2026-11-06 · **Raised by:** the Principal / PO (story text supplied 2026-11-06). Recorded by Claude, Architecture Session A (Organisation baseline).

| | |
|---|---|
| **Suggested title** | Establish a framework for recording and reviewing committee office-holder responsibilities and performance |
| **Status** | `PROPOSED` · not started · **implementation not authorised** |
| **Priority** | to be determined (PO) |
| **Workstream** | Organisation / Governance |
| **Epic** | none assigned |

## 1. Background

Committee members are elected to posts such as President, Vice-President, General Secretary, Secretary, Treasurer and other designated positions. Each post carries responsibilities, duties and expectations that the elected person is expected to fulfil during their term of office.

PublicDigit should eventually provide a way to record the work performed by committee office holders, so that the organisation can review how responsibilities are being fulfilled. The organisation needs to understand not only who holds a post, but also:
- what responsibilities belong to that post;
- what activities and initiatives were undertaken;
- what commitments and objectives were agreed;
- what outputs or results were achieved;
- what evidence supports the reported work;
- what responsibilities remain outstanding;
- what obstacles or external dependencies affected delivery.

## 2. Problem statement

There is no agreed, systematic and transparent way to document the work of elected committee office holders. Without one, organisations find it difficult to:
- understand what each office holder has actually worked on;
- track responsibilities and commitments over a committee's term;
- document completed and outstanding activities;
- review progress against agreed responsibilities;
- preserve institutional knowledge between committee terms;
- give evidence-based reports to the committee and members;
- support accountability without reducing performance to arbitrary numerical scores.

## 3. Business objective

Discover and design a governance-aligned capability that enables organisations to:
1. define responsibilities associated with committee posts;
2. record activities and initiatives undertaken by office holders;
3. document objectives, commitments, milestones and deliverables;
4. record outcomes and supporting evidence;
5. review progress periodically;
6. identify outstanding responsibilities and constraints;
7. produce transparent activity and responsibility reports;
8. preserve the history of work across committee terms.

## 4. Strategic DDD discovery questions

To be investigated before any domain model is designed.

**A. Organisation and Governance**
- Who defines the responsibilities of each committee post?
- Are responsibilities established by the constitution, statutes, regulations or committee decisions?
- Can responsibilities be delegated?
- Who is authorised to review an office holder's work?
- Who can accept or challenge an activity report?

**B. Responsibility and commitment**
- Is a responsibility permanently associated with a post, or can it change during a term?
- How are annual or term-based objectives established?
- Can several office holders share a responsibility?
- How should delegated responsibilities be represented?
- What happens when an office holder changes or leaves the post?

**C. Activity and evidence**
- What is a reportable activity?
- Should activities be linked to specific responsibilities?
- What evidence can be attached to an activity?
- How are meetings, events, projects, decisions and initiatives recorded?
- How are incomplete, cancelled or delayed activities documented?

**D. Review and accountability**
- Who reviews reported activities?
- Can an office holder give a self-assessment?
- Can committee members give feedback?
- How are disagreements or disputed reports handled?
- How are external dependencies and resource constraints taken into account?

**E. Performance and evaluation**
- What does fulfilment of a committee responsibility mean?
- Should assessment consider activities, deliverables, outcomes, timeliness and quality?
- How can qualitative contributions be represented?
- How can the system avoid unfair comparisons between different posts?
- Should the organisation define its own review criteria?
- What safeguards are needed against arbitrary ratings or politically motivated evaluations?

## 5. Discovery candidates (hypotheses only)

CommitteePost · OfficeHolder · Responsibility · Delegation · TermObjective · Commitment · Activity · Deliverable · Outcome · Evidence · ProgressReview · PerformanceAssessment.

These are **not** approved concepts or aggregates. Their ownership, relationships, lifecycle and context placement must come out of strategic DDD discovery (§6, Phase 1).

## 6. Expected discovery and architecture work

1. **Strategic DDD discovery:** business needs; ubiquitous language; domain events and business rules; stakeholders and decision authorities; bounded-context candidates; relationship to Organisation, Governance and Membership.
2. **Tactical DDD and architecture:** aggregates and invariants; entities and value objects; domain services and events; context boundaries and integration contracts; audit, evidence and history requirements; authorisation and review responsibilities.
3. **Product and functional design:** activity recording; responsibility and objective tracking; reporting and review workflows; organisation-configurable review criteria; reporting and access control.
4. **Implementation planning:** dependencies; migration and integration; acceptance criteria and backlog; the required Principal and ARB approvals.

Each phase needs its own authorisation. Phase 1 is not yet authorised either.

## 7. Architectural and governance constraints

- Constitutional authority comes from the organisation's legitimate governance arrangements, not from software permissions.
- A technical account or administrative role does not by itself confer authority to evaluate another office holder.
- Responsibilities and expectations must be traceable to an authorised source.
- Different posts must not be compared with arbitrary universal numerical scores.
- Self-reported activities, verified evidence and formal review decisions must remain distinguishable.
- Historical records must preserve the applicable committee term and office-holder assignment.
- The ratified Membership architecture is not modified or reopened.
- No aggregate, bounded context, schema or implementation decision is authorised by this story.

## 8. Expected outcome and definition of ready

**Outcome:** a reviewed and approved domain and architecture proposal for how PublicDigit supports responsibility management, activity recording, evidence collection, periodic review and organisational accountability for elected committee office holders.

**Ready for implementation only when:**
- strategic DDD discovery is completed;
- domain ownership and context boundaries are established;
- business rules and the authority model are agreed;
- the tactical architecture is reviewed;
- the Principal and ARB decisions are recorded;
- implementation scope and acceptance criteria are approved.

## 9. Scope boundary

This story records a future discovery and architecture initiative only. It does **not** authorise:
- code or database changes;
- performance scoring or ranking of office holders;
- automatic decisions about an office holder's competence or suitability;
- changes to constitutional authority;
- changes to the existing Membership or Governance architecture.

Discovery first establishes what the organisation needs to record and review, before deciding how performance is measured.

## 10. Dependencies on open decisions (added by the recorder)

These existing records bear on the discovery questions. They are listed so that the discovery consumes them rather than re-deciding them.

| Topic | Existing record | State | Bears on |
|---|---|---|---|
| Who establishes posts and offices | [D] PO-ORG-MEM-01 Decision A: "The Organisation's Constitution (statutes) establishes the competent organisational organs, offices and positions" (`docs/publicdigit/reviews/2026-11-03-organisation-po-org-mem-01-membership-ownership-decision-record.md`, §1) | approved; record being completed by Session B | §4 A |
| Committees as organs | [D] PO-DR07-01 (`docs/publicdigit/reviews/2026-11-02-governance-dr07-committee-ownership-decision-paper.md` §15) | PO business decision | CommitteePost |
| Owner of organs, competence, authority | [D] ACT §11 (`docs/publicdigit/reviews/2026-10-08-membership-architecture-ratification-act.md:321`) | ratified | §4 A, D |
| Owner of appointments, tenure and mandate | ARB-8: Governance or BDR-03 "Appointment (Authority)" | **open** | OfficeHolder, term history |
| Positions under ACT §11 | ODA-01 v1.1 C-2 | **open** (ARB) | CommitteePost |
| Offices outside organs; organ scope | ODA-01 v1.1 E-2 | **open** (external evidence) | §4 A, B |
| Governance committee write model | DR-07a (`docs/publicdigit/architecture/reviews/2026-11-03-dr07a-membership-technical-discovery-m1-m5.md`) | **open** (ARB-5…7) | any later tactical work |
| Position incompatibility per organisation | [D] BR-14 (`docs/publicdigit/business_rules/20261101_1739_person_account_affiliation_business_rules.md:40`) | PO confirmed | shared responsibilities |
| Platform access ≠ constitutional authority | [D] PO-ORG-MEM-01 A; D-4 interim | approved | §7 |
| Constitutional source vs interpretation vs authority evidence vs operational rule | ODA-01 v1.1 §5.1, M-12 | **proposal** (ARB) | "traceable to an authorised source" |

[I] Recorder's note: performance reviews of named office holders in political organisations are sensitive personal data. Data-protection and retention requirements belong in Phase 1, not later. The election anonymity invariant (ADR-T11) is not affected, since this concerns office holders' work, not votes.

## Traceability

- Story text: the Principal / PO, 2026-11-06 (Architecture Session A).
- Related: ODA-01 v1.1 (`docs/publicdigit/architecture/reviews/2026-11-03-organisation-domain-architecture-baseline-oda-01-v1.1.md`); GMAP Position / Appointment / Tenure (`docs/publicdigit/reviews/2026-11-02-governance-domain-discovery-map-roadmap-and-readiness.md` §2).

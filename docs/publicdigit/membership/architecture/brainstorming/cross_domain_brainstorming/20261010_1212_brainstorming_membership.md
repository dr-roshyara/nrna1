# Revised Phase 2 Research Plan — Incorporating the Principal-Review Verdict

Below is the rewritten plan. It accepts the verdict's amendments, corrects the premature technical preferences, reframes the work as iterative discovery, and redefines the immediate next deliverable as an **evidence-and-decision foundation** followed by a **traceable, evidence-backed ubiquitous language**. It remains a research plan, not an approved architecture.

---

## 1. What Changed from the Previous Plan

The verdict identified six corrections and one additional recommendation. All are adopted.

| # | Verdict Correction | How It Is Applied |
|---|---|---|
| 1 | Remove premature tenancy hypothesis (old H8) | Tenancy is now an **open comparison**, not a preference. No shared-schema assumption. |
| 2 | Do not assume Person is globally shared | Global identity vs. organization-scoped member records are now **competing options**, not a decided model. |
| 3 | Refine forum-access hypothesis (old H7) | Authoritative policy vs. cached projection is now explicit, with freshness and revocation requirements. |
| 4 | Make work packages iterative | The work packages are reorganized into **iterative discovery cycles** with explicit checkpoints, not a linear waterfall. |
| 5 | Require proportionate mathematical proof | Formal verification is applied **selectively** to high-risk, well-specified rules; ordinary invariants use state-transition and property-based tests. |
| 6 | Strengthen approval protocol | **Three separate ARB gates**: research authorization, architecture approval, implementation authorization. Each records decision, authority, artifact version, conditions, blockers. |
| + | Evidence and decision foundation before glossary | The first research deliverable is now the **Evidence and Decision Foundation**, not a completed glossary. The glossary follows and distinguishes **verified** from **proposed** meanings. |

---

## 2. Gate 0 — Corpus and Scope Verification (Revised)

### 2.1 Corpus Inventory

| Artifact | Status | Role |
|---|---|---|
| Phase 1 brainstorming document | Available | Conceptual foundation |
| Principal review (Phase 2) | Available | Governance protocol and gates |
| Principal-review verdict | Available | Amendments and corrections |
| This conversation | Available | Working context |
| Organizational constitutions, statutes, bylaws | Not available | Required evidence |
| Federation agreements and cross-organization rules | Not available | Required evidence |
| Stakeholder interviews | Not available | Required for business validation |
| Legal and regulatory documents | Not available | Required for compliance rules |
| Existing repository / codebase | Not inspected | Required before implementation |
| Non-functional requirements | Not available | Required for tenancy and operations |

### 2.2 Evidence Register (Initial)

| Claim | Source | Type | Status |
|---|---|---|---|
| Organizations have different structures | Phase 1 | Observed | Accepted |
| Membership ≠ Member | Phase 1 | Derived | Accepted |
| Committee ≠ OrganizationalUnit | Phase 1 | Derived | Accepted |
| Role ≠ Permission | Phase 1 | Derived | Accepted |
| Geography ≠ Organizational hierarchy | Phase 1 | Derived | Accepted |
| One chairperson per committee | Phase 1 | Hypothesis | Unverified — needs evidence |
| Membership requires approval | Phase 1 | Hypothesis | Unverified — needs evidence |
| Committee appointment requires membership | Phase 1 | Hypothesis | Unverified — needs evidence |
| Person is globally shared | Phase 1 (implied) | Hypothesis | **Contested — competing option** |
| Shared-schema tenancy is correct | Previous plan H8 | Premature | **Withdrawn** |
| Forum access is derived, not stored | Previous plan H7 | Partially correct | **Refined** |

### 2.3 Contradictions and Open Questions

1. Global Person identity vs. organization-scoped member records — unresolved, privacy-sensitive.
2. Tenancy model — unresolved, must follow isolation requirements.
3. Forum access — authoritative policy vs. cached projection — needs freshness and revocation semantics.
4. Universal vs. configurable rules — many Phase 1 claims are hypotheses, not facts.
5. Six bounded contexts — candidates, not validated.
6. Aggregate boundaries — candidates, not validated.

### 2.4 Exclusions

- Election and voting: out of scope.
- Implementation: not authorized.
- Database schema, Laravel models, API contracts: not authorized.

---

## 3. Gate 1 — Research Plan (Revised)

### 3.1 Research Questions

**RQ1 — Evidence:** What do real organizational documents actually say about membership, structure, governance, geography, communication, and identity?

**RQ2 — Vocabulary:** What is the precise meaning of each term, and how does it vary across organizations?

**RQ3 — Relationships:** What are the cardinalities, optionalities, ownership, lifecycles, and historical requirements?

**RQ4 — Integrity boundary:** What must hold universally, what is configurable, and what is organization-specific?

**RQ5 — Lifecycles:** What states and transitions exist for membership, appointment, mandate, committee, unit, and forum?

**RQ6 — Aggregates:** What are the correct consistency boundaries?

**RQ7 — Contexts:** Are the candidate contexts valid, or should they be revised?

**RQ8 — Identity:** Should Person be globally shared, organization-scoped, or a hybrid?

**RQ9 — Tenancy:** Which isolation model best fits requirements, cost, security, residency, and recovery?

**RQ10 — Authorization:** How is forum access derived, cached, revoked, and audited?

**RQ11 — Logic:** Which rules justify formal verification, and which only require testing?

**RQ12 — ML scope:** Where can ML responsibly assist, and where must it be excluded?

### 3.2 Hypotheses to Test

| ID | Hypothesis | Test Method | Status |
|---|---|---|---|
| H1 | Person, Member, Membership are distinct | Document analysis + scenarios | Open |
| H2 | MembershipType is a policy, not a label | Document analysis | Open |
| H3 | Committee and OrganizationalUnit are distinct | Scenario testing | Open |
| H4 | CommitteeAppointment is its own aggregate when mandates are independent | Concurrency + lifecycle analysis | Open |
| H5 | Geographic jurisdiction is separate from organizational hierarchy | Document analysis | Open |
| H6 | Role and Permission are separate concerns | Authorization scenario testing | Open |
| H7a | Authoritative forum access is derived from organizational facts | Policy analysis | Open |
| H7b | Cached projections are acceptable if freshness and revocation are guaranteed | Operational analysis | Open |
| H8a | Global Person identity is the correct model | Privacy + isolation analysis | Open |
| H8b | Organization-scoped member records are the correct model | Privacy + isolation analysis | Open |
| H8c | A hybrid model (global identity + scoped records) is correct | Privacy + isolation analysis | Open |
| H9 | Tenancy must be decided by requirements, not preference | Cost/security/residency comparison | Open |

### 3.3 Work Packages (Iterative, Not Linear)

The verdict requires iterative discovery. Work packages are grouped into **cycles**, each ending with a checkpoint. Later cycles may revise earlier outputs.

**Cycle A — Evidence Foundation**
- WP-A1: Corpus inventory and evidence register
- WP-A2: Source document analysis (constitutions, bylaws, statutes)
- WP-A3: Evidence quality and traceability rules
- WP-A4: Open questions and decision log

**Cycle B — Vocabulary and Relationships (provisional)**
- WP-B1: Provisional glossary (verified vs. proposed meanings)
- WP-B2: Relationship specification (provisional)
- WP-B3: Cross-organization term comparison

**Cycle C — Rules and Lifecycles**
- WP-C1: Rule classification (universal / configurable / specific / legal / operational)
- WP-C2: Domain events
- WP-C3: State machines for membership, appointment, mandate, committee, unit, forum
- WP-C4: Policy versioning rules

**Cycle D — Scenarios and Validation**
- WP-D1: Scenario catalogue (normal, edge, historical, adversarial)
- WP-D2: Invariant and acceptance criteria
- WP-D3: Proportionate formal verification (selective)
- WP-D4: Counterexamples and rule revision

**Cycle E — Contexts and Aggregates**
- WP-E1: Bounded-context validation
- WP-E2: Aggregate boundary validation
- WP-E3: Context map and contracts
- WP-E4: Identity model decision (global vs. scoped vs. hybrid)

**Cycle F — Operations**
- WP-F1: Tenancy comparison and recommendation
- WP-F2: Forum access and caching semantics
- WP-F3: ML scope and oversight rules
- WP-F4: Security, audit, retention, residency

**Cycle G — Decision Package**
- WP-G1: Architecture decision package
- WP-G2: Implementation plan (post-approval)

### 3.4 Iteration and Checkpoints

Each cycle ends with a checkpoint:

- **Checkpoint A:** Evidence foundation reviewed and accepted by ARB.
- **Checkpoint B:** Provisional vocabulary reviewed by ChatGPT and ARB.
- **Checkpoint C:** Rules and lifecycles reviewed.
- **Checkpoint D:** Scenarios and validation reviewed.
- **Checkpoint E:** Contexts and aggregates reviewed.
- **Checkpoint F:** Operations reviewed.
- **Checkpoint G:** Architecture decision package submitted for approval.

Cycles may revisit earlier outputs when new evidence or counterexamples require revision. Decisions are versioned.

### 3.5 Proportionate Validation Strategy

| Rule Class | Validation Method |
|---|---|
| Ordinary invariants | State-transition tests, property-based tests |
| Structural rules | Graph properties, cycle detection |
| Temporal rules | Temporal logic checks |
| High-risk, well-specified rules | Constraint solving, model checking |
| Ambiguous or contested rules | Stakeholder validation, not formal proof |

Formal proof is applied only where the state space and assumptions can be specified adequately. It proves correctness relative to the model, not correctness relative to reality.

### 3.6 Evidence Quality and Traceability

Every rule must trace:

**Source evidence → Business concept → Requirement or invariant → Architecture decision → Implementation task → Test evidence**

Evidence quality labels:

- **Observed:** directly supported by a source document.
- **Derived:** follows from explicitly stated facts.
- **Hypothesis:** plausible, unverified.
- **Contested:** conflicting evidence exists.
- **Recommendation:** proposed course of action.
- **Decision required:** needs ARB decision.
- **Approved / Rejected:** explicit ARB outcome.

Conflicts are preserved, not silently reconciled.

### 3.7 Approval Protocol (Three Separate Gates)

| Gate | Purpose | Authority |
|---|---|---|
| ARB-1 | Approve research plan | Human ARB |
| ARB-2 | Approve architecture baseline | Human ARB |
| ARB-3 | Authorize implementation | Human ARB |

Each gate records:

- Decision (approved / rejected / returned)
- Approving authority
- Version of reviewed artifacts
- Conditions
- Unresolved blockers

A research plan approved for proceeding is **not** an architecture approved for implementation. Architecture approval is **not** implementation authorization.

### 3.8 Risks and Uncertainties

| Risk | Impact | Mitigation |
|---|---|---|
| Missing real organizational documents | Speculative rules | Request documents; mark assumptions |
| Stakeholders unavailable | Unvalidated rules | Escalate to ARB |
| Over-generalization | Forced uniform model | Keep integrity boundary minimal |
| Under-generalization | Cannot support diversity | Keep policies configurable |
| Premature identity model | Privacy and isolation defects | Keep options open until evidence |
| Premature tenancy model | Costly rework | Compare before deciding |
| Over-formalization | Wasted effort | Proportionate validation |
| ML overreach | Incorrect decisions | Restrict ML to assistive roles |
| Linear process | Rework and blind spots | Iterative cycles with checkpoints |

### 3.9 Entry and Exit Criteria

**Gate 1 (this gate):**
- Entry: Corpus available.
- Exit: ARB-1 approves research plan.

**Cycle A:**
- Entry: ARB-1 approved.
- Exit: Evidence foundation accepted.

**Cycle B:**
- Entry: Cycle A accepted.
- Exit: Provisional vocabulary reviewed.

**Cycle C:**
- Entry: Cycle B reviewed.
- Exit: Rules and lifecycles reviewed.

**Cycle D:**
- Entry: Cycle C reviewed.
- Exit: Scenarios and validation reviewed.

**Cycle E:**
- Entry: Cycle D reviewed.
- Exit: Contexts and aggregates reviewed.

**Cycle F:**
- Entry: Cycle E reviewed.
- Exit: Operations reviewed.

**Cycle G:**
- Entry: Cycles A–F reviewed.
- Exit: Architecture decision package submitted.

**ARB-2:**
- Entry: Package submitted.
- Exit: Architecture baseline approved and frozen.

**ARB-3:**
- Entry: Implementation plan approved.
- Exit: Implementation authorized.

### 3.10 Prioritized Backlog with Dependencies

**P0 — Evidence Foundation**
- WP-A1 (no dependencies)
- WP-A2 (depends on A1)
- WP-A3 (depends on A1)
- WP-A4 (depends on A2, A3)

**P1 — Vocabulary and Relationships (provisional)**
- WP-B1 (depends on A4)
- WP-B2 (depends on B1)
- WP-B3 (depends on B1)

**P2 — Rules and Lifecycles**
- WP-C1 (depends on B1, B2)
- WP-C2 (depends on C1)
- WP-C3 (depends on C2)
- WP-C4 (depends on C1)

**P3 — Scenarios and Validation**
- WP-D1 (depends on C3, C4)
- WP-D2 (depends on D1)
- WP-D3 (depends on D2)
- WP-D4 (depends on D3)

**P4 — Contexts and Aggregates**
- WP-E1 (depends on D4)
- WP-E2 (depends on E1)
- WP-E3 (depends on E2)
- WP-E4 (depends on E3)

**P5 — Operations**
- WP-F1 (depends on E4)
- WP-F2 (depends on E4)
- WP-F3 (depends on E4)
- WP-F4 (depends on E4)

**P6 — Decision Package**
- WP-G1 (depends on F1–F4)
- WP-G2 (depends on ARB-2)

### 3.11 Immediate Next Step

The next concrete deliverable is **Cycle A — the Evidence and Decision Foundation**, not a completed glossary, not a context diagram, and not code.

Specifically:

1. Complete WP-A1: corpus inventory and evidence register.
2. Begin WP-A2: analyze available source documents and mark what is missing.
3. Draft WP-A3: evidence quality and traceability rules.
4. Draft WP-A4: open questions and decision log.

Only after Cycle A is accepted should the provisional glossary (WP-B1) begin. That glossary must distinguish **verified meanings** from **proposed meanings**, and must preserve cross-organization differences rather than erasing them for uniformity.

**Authorization required:** ARB-1 approval to proceed from Gate 1 to Cycle A.

---

## 4. How Far We Are and What Remains

### 4.1 Current Status

| Phase | Status |
|---|---|
| Phase 1 — Conceptual brainstorming | Foundation established (~20–25%) |
| Gate 0 — Corpus verification | Completed (revised in this document) |
| Gate 1 — Research plan | Completed (revised in this document) |
| Cycle A — Evidence foundation | Not started |
| Cycle B — Vocabulary and relationships | Not started |
| Cycle C — Rules and lifecycles | Not started |
| Cycle D — Scenarios and validation | Not started |
| Cycle E — Contexts and aggregates | Not started |
| Cycle F — Operations | Not started |
| Cycle G — Decision package | Not started |
| ARB-1 — Research authorization | Pending |
| ARB-2 — Architecture approval | Not started |
| ARB-3 — Implementation authorization | Not started |

### 4.2 Remaining TODOs (Prioritized)

**P0 — Evidence foundation**
- Inventory all available source documents and mark missing ones.
- Build the evidence register with source, type, and status.
- Define evidence quality and traceability rules.
- Open the decision log and record unresolved questions.
- Confirm exclusion of election and voting.
- Obtain ARB-1 approval to proceed.

**P1 — Provisional vocabulary and relationships**
- Draft the glossary with verified vs. proposed meanings.
- Preserve cross-organization term differences.
- Specify relationships with cardinality, ownership, lifecycle, history.
- Compare terms across organization types.
- Submit for independent review.

**P2 — Rules and lifecycles**
- Classify every rule (universal / configurable / specific / legal / operational).
- Define domain events.
- Model state machines for membership, appointment, mandate, committee, unit, forum.
- Define policy versioning and change consequences.

**P3 — Scenarios and validation**
- Build the scenario catalogue (normal, edge, historical, adversarial).
- Derive invariants and acceptance criteria.
- Apply proportionate formal verification (selective).
- Produce counterexamples and revise rules.

**P4 — Contexts and aggregates**
- Validate each candidate bounded context against scenarios.
- Validate aggregate boundaries with invariants and concurrency.
- Decide CommitteeAppointment, MembershipType, Jurisdiction, ForumAccessPolicy aggregate status.
- Produce the context map with contracts.
- Decide the identity model (global vs. scoped vs. hybrid).

**P5 — Operations**
- Compare tenancy models against requirements.
- Define forum access, caching, freshness, and revocation semantics.
- Scope ML use cases and oversight rules.
- Define security, audit, retention, residency requirements.

**P6 — Decision package and approval**
- Assemble the architecture decision package.
- Submit for independent ChatGPT review.
- Obtain ARB-2 architecture approval and freeze the baseline.
- Produce the implementation plan.
- Obtain ARB-3 implementation authorization.
- Implement incrementally with tests and evidence.
- Track deviations and conformance.

### 4.3 Principal Recommendation

Approve this revised research plan with the amendments from the verdict. Do not treat the six bounded contexts, the aggregate hypotheses, the identity model, or the tenancy model as decided. The next useful output is the **evidence and decision foundation**, followed by a **traceable, evidence-backed ubiquitous language** that preserves organizational differences rather than erasing them.

No application code, no database schema, and no speculative context diagram should be produced until ARB-1 authorizes the research and the evidence foundation is accepted.
# Principal Domain Architect: focus on cross-domain relationships

I understand the direction: our primary task is to discover and validate relationships across the organizational domain, not to produce a general architecture plan in isolation.

I have reviewed the revised Phase 2 research plan. It incorporates the previous corrections, particularly the separation of verified evidence from hypotheses, the open tenancy and identity decisions, iterative discovery, and the three ARB gates.&#x20;

Eingefügter Text.txt



I agree with that foundation. However, I would now narrow our immediate work further: we should investigate the domain cross-relationships that determine how membership, organizational structure, governance, geography, roles, and discussion forums interact.

The objective is to discover a coherent domain model from which we can derive bounded contexts—not to assume that the proposed bounded contexts are already correct.

## 1. The central relationship problem

Consider one person in a social organization:

- The person is registered as a member.
- The membership has a particular type and status.
- The person belongs to a local branch.
- The person serves as secretary of a district committee.
- The district committee is responsible for a geographical jurisdiction.
- The person participates in a specialist wing.
- The person can access some discussion forums but not others.

These are not seven independent features. They are connected business facts with different meanings, owners, and lifecycles.

Our central question is:

Which relationships are fundamental domain facts, which are derived relationships, and which are policies that determine what a person is allowed to do?

## 2. First relationship map: six relationship families

I propose that we organize our investigation into six relationship families. This is more useful than starting with a list of entities because it exposes the different kinds of business meaning.

## Organization

Organizational identity, scope and governing policies

Identity & belonging

Person ↔ Member ↔ Membership ↔ MembershipType

Structure

Organization ↔ Branch ↔ Wing ↔ OrganizationalUnit

Governance

Committee ↔ Appointment ↔ Role ↔ Mandate

Geography

Jurisdiction ↔ GeographicUnit ↔ Committee responsibility

Communication

Forum ↔ Topic ↔ Post ↔ Participation

Authorization

Business facts + policies → permitted actions

Conceptual map only. These are relationship families, not approved bounded contexts.

A key point: the arrows between these families do not all represent the same relationship. Some represent ownership, others participation, authority, geography, or policy evaluation.

## 3. The first cross-domain relationship catalogue

Here is the initial set of relationships I would investigate as principal domain architect.

| Relationship                            | Initial hypothesis                                               | Key unresolved question                                                           |
| --------------------------------------- | ---------------------------------------------------------------- | --------------------------------------------------------------------------------- |
| Person → Member                         | A person may be represented in multiple organizations            | What identity information can be shared across organizations?                     |
| Member → Membership                     | A member may have one or more membership episodes                | Can memberships overlap, and how is rejoining represented?                        |
| Membership → MembershipType             | A membership has an applicable classification                    | How are type changes and historical policies handled?                             |
| Member → OrganizationalUnit             | A member may participate in or belong to units                   | Is unit affiliation distinct from membership in the organization?                 |
| OrganizationalUnit → OrganizationalUnit | Units may form a hierarchy                                       | Can a unit have multiple parents or belong to several structures?                 |
| Committee → OrganizationalUnit          | A committee may govern or serve a unit                           | Can a committee serve multiple units, and what authority does it have?            |
| Member → Committee                      | A member may participate in multiple committees                  | Must every participant be an active member?                                       |
| CommitteeAppointment → Role             | An appointment carries one or more responsibilities or positions | Can an appointment carry multiple roles, and can roles be shared?                 |
| CommitteeAppointment → Mandate          | An appointment may have a defined period                         | Can it be indefinite, renewed, suspended, or revoked?                             |
| Committee → GeographicJurisdiction      | A committee may be responsible for a territory                   | Can jurisdictions overlap, and how are historical changes preserved?              |
| Member → Forum                          | A member may participate in discussions                          | Does access follow membership, unit affiliation, appointment, or explicit policy? |
| Forum → GovernancePolicy                | A forum may have discussion and moderation rules                 | Who can change those rules, and which policy version applies?                     |

These are hypotheses, not final rules. For example, `Member → OrganizationalUnit` might eventually be represented through an explicit affiliation rather than a direct relationship. We should choose the representation based on its business meaning and lifecycle.

## 4. The most important distinction: direct, derived, and authorized relationships

We should classify every cross-domain relationship into one of three categories.

Type A

### Direct business relationship

A fact that is explicitly established by a business operation.

Example: a person is appointed secretary of a district committee.

The appointment has its own identity, validity period, and lifecycle. It should not be inferred merely because the person is a member of that district.

Type B

### Derived relationship

A relationship calculated from other authoritative facts.

Example: a member is considered affiliated with a province because the member belongs to a local unit that is associated with that province.

This is valid only if the organization defines that derivation. It must not be assumed from geographical proximity alone.

Type C

### Policy-authorized relationship

A relationship or action permitted by a rule.

Example: a district secretary may moderate a district forum because an active appointment and an applicable authorization policy permit it.

The appointment is a business fact; permission to moderate is a separate authorization decision.

This distinction is fundamental. If we mix these categories, the platform will gradually accumulate hidden rules, duplicated facts, and authorization defects.

## 5. How I would conduct the next brainstorming round

We should not attempt to finalize all relationships simultaneously. I recommend starting with the relationships that establish organizational identity and propagate into the greatest number of other domains.

1. Organization ↔ Person ↔ Member ↔ Membership: establish identity, belonging, and membership lifecycle.
2. Organization ↔ OrganizationalUnit: establish branches, wings, hierarchy, and affiliations.
3. Member ↔ CommitteeAppointment ↔ Role ↔ Mandate: establish participation, responsibility, and authority.
4. Committee ↔ GeographicJurisdiction: establish territorial responsibility independently of organizational hierarchy.
5. Membership, Governance, and Organization Structure ↔ Forum: establish communication scope and access rules.
6. All relevant facts ↔ Identity & Access: establish how authoritative domain facts inform application permissions.

For each relationship, we will ask the same questions:

- What does this relationship mean in the real organization?
- Who is authorized to establish, change, and terminate it?
- Is it direct, derived, or policy-controlled?
- Can it have its own lifecycle and history?
- What happens when either related object changes or disappears?
- Which invariants must hold?
- Which domain owns the authoritative fact?
- Which other domains depend on that fact?
- What scenario would prove our proposed model wrong?

This gives us a repeatable method for discovering domain boundaries from the relationships themselves.

## 6. My immediate priority

I would begin with Member ↔ Membership ↔ OrganizationalUnit, before deciding the bounded contexts.

Why? Because we need to establish whether these are three independent concepts, how they relate, and whether organizational affiliation is part of membership or a separate relationship. That decision affects committee eligibility, geographical affiliation, forum access, and potentially the tenancy and identity model.

We should also preserve the previously established architectural distinction that committee assignment and membership have separate responsibilities, rather than making committee membership an automatic consequence of organizational membership.&#x20;

The first concrete deliverable should be a Cross-Domain Relationship Specification v0.1 containing:

- Relationship identifier and name.
- Source and target concepts.
- Business meaning.
- Cardinality and optionality.
- Relationship type: direct, derived, or policy-authorized.
- Authoritative owner.
- Lifecycle and temporal validity.
- Invariants and exceptions.
- Evidence status and unresolved questions.
- Scenarios that validate or falsify the hypothesis.

Once we have validated this relationship catalogue, we can derive the bounded contexts with much greater confidence.
